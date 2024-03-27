<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function impersonate(): View
    {
        $users = User::orderBy('name')->get();
        return view('admin.impersonation', ['users' => $users]);
    }
}
