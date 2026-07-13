<?php

namespace App\Http\Controllers;

use App\Models\Garansi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackingController extends Controller
{
    public function index(Request $request): View
    {
        $garansis = collect();
        
        // Cek apakah ada input pencarian yang masuk
        $hasSearch = $request->filled('serial_number') || 
                     $request->filled('nama') || 
                     $request->filled('no_hp');

        if ($hasSearch) {
            $hasSerialNumber = $request->filled('serial_number');
            $hasNamaDanPhone = $request->filled('nama') && $request->filled('no_hp');

            // VALIDASI: Harus isi Serial Number ATAU (Nama DAN No HP wajib lengkap)
            if (!$hasSerialNumber && !$hasNamaDanPhone) {
                
                session()->flash(
                    'tracking_error',
                    'Masukkan Serial Number atau lengkapi Nama beserta Nomor HP Anda.'
                );
                
            } else {
                
                // Jalankan Query jika validasi lolos
                $garansis = Garansi::with('items')
                    ->when($hasSerialNumber, function ($query) use ($request) {
                        $sn = trim($request->serial_number);
                        return $query->whereHas('items', function ($q) use ($sn) {
                            $q->where('serial_number', $sn)
                              ->orWhere('serial_number_baru', $sn);
                        });
                    })
                    ->when(!$hasSerialNumber && $hasNamaDanPhone, function ($query) use ($request) {
                        $nama = trim($request->nama);
                        // Bersihkan nomor HP hanya menyisakan angka
                        $phone = preg_replace('/[^0-9]/', '', $request->no_hp);
                        
                        return $query->where('nama', 'like', "%{$nama}%")
                                     ->where(function ($q) use ($phone) {
                                         $q->whereRaw(
                                             "REPLACE(REPLACE(REPLACE(no_hp,'+',''),' ',''),'-','') LIKE ?",
                                             ["%{$phone}%"]
                                         );
                                     });
                    })
                    ->latest('updated_at')
                    ->get();
                    
                // Jika pencarian dilakukan tapi data kosong, beri info (opsional)
                if ($garansis->isEmpty()) {
                    session()->flash('tracking_error', 'Data garansi tidak ditemukan. Silakan periksa kembali data yang Anda masukkan.');
                }
            }
        }

        return view('tracking.index', compact('garansis', 'hasSearch'));
    }
}