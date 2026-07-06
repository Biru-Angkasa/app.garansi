<?php

namespace App\Http\Controllers;

use App\Models\Garansi;
use Spatie\Activitylog\Models\Activity;

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

        // Statistik SLA (1-2 hari = Warning, >2 hari = Breach)
        $slaWarning = Garansi::where('status', '!=', 'selesai')
            ->whereBetween('updated_at', [now()->subDays(2), now()->subDays(1)])
            ->count();

        $slaBreach = Garansi::where('status', '!=', 'selesai')
            ->where('updated_at', '<', now()->subDays(2))
            ->count();

        $recentGaransis = Garansi::with('items')->latest()->limit(5)->get();

        // Ambil 10 log aktivitas terakhir
        $activities = Activity::with('causer')->latest()->limit(10)->get();

        return view('dashboard', compact('stats', 'slaWarning', 'slaBreach', 'recentGaransis', 'activities'));
    }
}