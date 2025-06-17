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

        return view('arisan.groups.index', compact('groups'));
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

        return redirect()->route('groups.show', $group);
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
}