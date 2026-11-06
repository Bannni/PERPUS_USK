<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of registered users.
     */
    public function index()
    {
        return redirect()->route('admin.dashboard', ['tab' => 'users']);
    }
}
