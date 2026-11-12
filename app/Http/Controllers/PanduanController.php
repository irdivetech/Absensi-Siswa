<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanduanController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role->nama_role;
        return view('panduan.index', compact('role'));
    }
}
