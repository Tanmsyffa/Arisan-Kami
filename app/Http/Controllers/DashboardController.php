<?php

namespace App\Http\Controllers;

use App\Models\ArisanGroup;

class DashboardController extends Controller
{
    public function index()
    {
        $groups = ArisanGroup::all();
        return view('dashboard', compact('groups'));
    }
}
