<?php

namespace App\Http\Controllers;

use App\Models\Bengkel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BengkelController extends Controller
{
    /*
    * menampilkan data bengkel
    */
    public function index()
    {
        $bengkel = Bengkel::with('user')->latest()->get();
        return view('admin.bengkel.index', compact('bengkel'));
    }

    /*
    * menampilkan detail bengkel
    */
    public function show($id)
    {
        $bengkel = Bengkel::with('user', 'montirs', 'layananBengkel')->findOrFail($id);
        return view('admin.bengkel.detail', compact('bengkel'));
    }

    /*
    * menolak permohonan bengkel
    */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:255',
        ]);
        $bengkel = Bengkel::findOrFail($id);
        DB::beginTransaction();
        try {
            $bengkel->update(['verifikasi' => false, 'alasan_penolakan' => $request->alasan_penolakan]);
            DB::commit();
            return redirect()->route('bengkel.show', $bengkel->id)->with('success', 'Permohonan bengkel ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('bengkel.show', $bengkel->id)->with('error', 'Terjadi kesalahan saat menolak permohonan bengkel.');
        }

    }

    /*
     * menerima permohonan bengkel
     */
    public function accept($id)
    {
        $bengkel = Bengkel::findOrFail($id);
        DB::beginTransaction();
        try {
            $bengkel->update(['verifikasi' => true, 'alasan_penolakan' => null]);
            DB::commit();
            return redirect()->route('bengkel.show', $bengkel->id)->with('success', 'Permohonan bengkel diterima.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('bengkel.show', $bengkel->id)->with('error', 'Terjadi kesalahan saat menerima permohonan bengkel.');
        }
    }
}
