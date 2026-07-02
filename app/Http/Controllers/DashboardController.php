<?php

namespace App\Http\Controllers;

use App\Models\Garansi;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'      => Garansi::count(),
            'pending'    => Garansi::where('status', 'pending')->count(),
            'repair'     => Garansi::where('status', 'repair')->count(),
            'replace'    => Garansi::where('status', 'replace')->count(),
            'distribusi' => Garansi::where('status', 'to distribution')->count(),
            'pengiriman' => Garansi::where('status', 'pengiriman')->count(),
            'selesai'    => Garansi::where('status', 'selesai')->count(),
        ];

        $recentGaransis = Garansi::with('items')->latest()->limit(5)->get();

        return view('dashboard', compact('stats', 'recentGaransis'));
    }
}