<?php

namespace App\Http\Controllers;

use App\Models\ArisanGroup;
use Illuminate\Http\Request;

class ArisanGroupController extends Controller
{
    public function index()
    {
        $groups = ArisanGroup::withCount('members')
            ->with('owner')
            ->latest()
            ->paginate(10);

        return view('admin.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     * Method ini diperlukan untuk resource route
     */
    public function create()
    {
        return view('admin.groups.create');
    }

    // Membuat grup baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $group = ArisanGroup::create([
            'name' => $request->name,
            'amount' => $request->amount,
            'owner_id' => auth()->id(),
            'status' => 'active'
        ]);

        // Otomatis tambahkan owner sebagai anggota
        $group->members()->attach(auth()->id());

        return redirect()->route('admin.groups.index')
            ->with('success', 'Group created successfully');
    }

    public function show(ArisanGroup $group)
    {
        // Eager load data yang diperlukan
        $group->load([
            'owner',
            'members',
            'payments' => function ($query) {
                $query->with('user')->latest();
            }
        ]);

        return view('groups.show', [
            'group' => $group,
            'isOwner' => fn($userId) => $group->isOwner($userId)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * Method ini diperlukan untuk resource route
     */
    public function edit(ArisanGroup $group)
    {
        return view('admin.groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     * Method ini diperlukan untuk resource route
     */
    public function update(Request $request, ArisanGroup $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $group->update([
            'name' => $request->name,
            'amount' => $request->amount,
        ]);

        return redirect()->route('admin.groups.index')
            ->with('success', 'Group updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     * Method ini diperlukan untuk resource route
     */
    public function destroy(ArisanGroup $group)
    {
        $group->delete();

        return redirect()->route('admin.groups.index')
            ->with('success', 'Group deleted successfully');
    }

    // Menambahkan anggota ke grup
    public function addMember(ArisanGroup $group, Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        if (!$group->isActive()) {
            return back()->with('error', 'Grup tidak aktif');
        }

        if ($group->hasMember($request->user_id)) {
            return back()->with('error', 'User sudah menjadi anggota');
        }

        $group->members()->attach($request->user_id);

        return back()->with('success', 'Anggota berhasil ditambahkan');
    }

    // Menandai grup selesai
    public function complete(ArisanGroup $group)
    {
        if (!$group->isOwner(auth()->id())) {
            abort(403, 'Hanya pemilik grup yang dapat melakukan ini');
        }

        $group->update(['status' => 'completed']);

        return back()->with('success', 'Grup arisan ditandai selesai');
    }

    /**
     * Display groups for members
     */
    public function memberGroups()
    {
        $user = auth()->user();
        $groups = $user->arisanGroups()->with('members')->get();
        
        return view('member.groups.index', compact('groups'));
    }
}