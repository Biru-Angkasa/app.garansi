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

        // ===============================
        // HITUNG SLA SAMA PERSIS DENGAN HALAMAN DATA GARANSI
        // ===============================
        $slaWarning = 0;
        $slaBreach = 0;

        $garansis = Garansi::where('status', '!=', 'selesai')->get();

        foreach ($garansis as $garansi) {

            $idleDays = $garansi->updated_at
                ->copy()
                ->startOfDay()
                ->diffInDays(now()->copy()->startOfDay());

            if ($idleDays >= 2) {
                $slaBreach++;
            } elseif ($idleDays >= 1) {
                $slaWarning++;
            }
        }

        // Data terbaru
        $recentGaransis = Garansi::with('items')
            ->latest()
            ->limit(5)
            ->get();

        // Aktivitas
        $activities = Activity::with('causer')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'stats',
            'slaWarning',
            'slaBreach',
            'recentGaransis',
            'activities'
        ));
    }
}