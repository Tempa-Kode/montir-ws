<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bengkel;
use App\Models\OrderLayanan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Active Request (order dengan status pending atau dalam proses)
        $activeRequests = OrderLayanan::whereIn('status', ['pending', 'kelokasi', 'kerjakan'])->count();

        // Total Registered Bengkel (yang sudah terverifikasi)
        $registeredBengkel = Bengkel::where('verifikasi', true)->count();

        // Total Earning (total dari semua transaksi yang sudah selesai dan lunas)
        $totalEarning = OrderLayanan::where('status', 'selesai')
            ->where('status_pembayaran', 'paid')
            ->sum('harga_layanan');

        // Recent Service Requests (5 order terakhir)
        $recentRequests = OrderLayanan::with(['pelanggan', 'layananBengkel.bengkel'])
            ->latest()
            ->limit(5)
            ->get();

        // Recent Registered Bengkel (5 bengkel terakhir yang terverifikasi)
        $recentBengkel = Bengkel::with('user')
            ->where('verifikasi', true)
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'activeRequests',
            'registeredBengkel',
            'totalEarning',
            'recentRequests',
            'recentBengkel'
        ));
    }
}
