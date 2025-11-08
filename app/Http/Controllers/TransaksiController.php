<?php

namespace App\Http\Controllers;

use App\Models\OrderLayanan;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = OrderLayanan::with('layananBengkel.bengkel', 'layananBengkel', 'pelanggan')->latest()->get();
        return view('admin.transaksi.index', compact('transaksi'));
    }

    public function show($id)
    {
        $transaksi = OrderLayanan::with([
            'layananBengkel.bengkel', 
            'layananBengkel', 
            'pelanggan', 
            'montir.user',
            'montir.bengkel',
            'itemService',
            'ulasanRating'
        ])->findOrFail($id);
        return view('admin.transaksi.detail', compact('transaksi'));
    }
}
