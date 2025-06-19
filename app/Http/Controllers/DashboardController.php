<?php

namespace App\Http\Controllers;

use App\Models\ArisanGroup;
use App\Models\Payment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Redirect berdasarkan role user
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('member')) {
            return redirect()->route('member.dashboard');
        }
        
        // Fallback jika tidak ada role yang cocok
        $groups = ArisanGroup::all();
        return view('dashboard', compact('groups'));
    }

    public function admin()
    {
        // Statistik untuk dashboard admin
        $stats = [
            'total_groups' => ArisanGroup::count(),
            'total_members' => User::role('member')->count(),
            'total_payments' => Payment::count(),
            'recent_payments' => Payment::with(['user', 'group'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'pending_payments' => Payment::where('payment_status', 'pending')->count(),
            'total_income' => Payment::where('payment_status', 'paid')->sum('amount'),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function member()
    {
        $user = auth()->user();
        $activeGroups = $user->arisanGroups()->count();
        $completedPayments = $user->payments()->where('status', 'success')->count();
        $pendingPayments = $user->payments()->where('status', 'pending')->count();

        return view('member.dashboard', compact('activeGroups', 'completedPayments', 'pendingPayments'));
    }
}