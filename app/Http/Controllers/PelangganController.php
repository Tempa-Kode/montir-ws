<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = User::where('role', 'pelanggan')->latest()->get();
        return view('admin.pelanggan.index', compact('pelanggan'));
    }
}
