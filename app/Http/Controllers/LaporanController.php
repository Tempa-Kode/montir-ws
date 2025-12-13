<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bengkel;
use App\Models\OrderLayanan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function laporanDataBengkel()
    {
        $data = Bengkel::select('id', 'user_id', 'nama', 'alamat', 'created_at')
            ->with('user')
            ->where('verifikasi', true)
            ->latest()
            ->get();

        $pdf = Pdf::loadView('pdf.bengkel', compact('data'));
        $timestamp = now()->format('Y-m-d_H-i-s');
        return $pdf->stream("data-bengkel-{$timestamp}.pdf");
    }

    public function laporanDataPelanggan()
    {
        $data = User::select('id', 'nama', 'alamat', 'no_telp', 'email', 'created_at')
            ->where('role', 'pelanggan')
            ->latest()
            ->get();

        $pdf = Pdf::loadView('pdf.pelanggan', compact('data'));
        $timestamp = now()->format('Y-m-d_H-i-s');
        return $pdf->stream("data-pelanggan-{$timestamp}.pdf");
    }

    public function laporanDataTransaksi()
    {
        $transaksi = OrderLayanan::with('layananBengkel.bengkel', 'layananBengkel', 'pelanggan', 'itemService')->latest()->get();
        $pdf = Pdf::loadView('pdf.transaksi', compact('transaksi'));
        $timestamp = now()->format('Y-m-d_H-i-s');
        return $pdf->setPaper('a4', 'landscape')->stream("data-transaksi-{$timestamp}.pdf");
    }
}
