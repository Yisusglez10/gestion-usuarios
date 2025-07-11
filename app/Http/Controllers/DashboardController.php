<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('dashboard', compact('users'));
    }
}
