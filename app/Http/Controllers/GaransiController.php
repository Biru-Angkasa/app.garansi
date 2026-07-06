<?php

namespace App\Http\Controllers;

use App\Models\Garansi;
use App\Models\GaransiItem;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GaransiController extends Controller
{
   public function index(Request $request)
    {
        $query = Garansi::with('items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('invoice_pembelian', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        // TAMBAHKAN URUTAN DI SINI:
        // 1. Status 'selesai' taruh paling bawah (1), selain itu di atas (0)
        // 2. Urutkan berdasarkan updated_at terlama (asc), sehingga yang merah (paling lama diam) naik ke atas
        $query->orderByRaw("CASE WHEN status = 'selesai' THEN 1 ELSE 0 END")
              ->orderBy('updated_at', 'asc');

        $garansis = $query->paginate(10)->withQueryString();

        $statusList = [
            'pending',
            'repair',
            'replace',
            'to distribution',
            'pengiriman',
            'selesai',
        ];

        return view('garansi.index', compact('garansis', 'statusList'));
    }

    public function create()
    {
        $lokasiList = config('whatsapp.lokasi');

        return view('garansi.create', compact('lokasiList'));
    }

        public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'               => ['required', 'string', 'max:255'],
            'no_hp'              => ['required', 'string', 'max:20'],
            'invoice_pembelian' => ['nullable', 'string', 'max:255'],
            'tanggal_beli'       => ['required', 'date'],
            'nama_marketplace'   => ['required', 'string', 'max:255'],
            'kerusakan'          => ['required', 'string'],
            'kelengkapan_barang' => ['required', 'string'],
            'lokasi_chat' => ['required', 'string', Rule::in(array_keys(config('whatsapp.lokasi')))],
            'items'              => ['required', 'array', 'min:1'],
            'items.*.nama_barang'   => ['required', 'string', 'max:255'],
            'items.*.serial_number' => ['required', 'string', 'max:255'],
        ]);

        $garansi = Garansi::create([
            'nama'               => $validated['nama'],
            'no_hp'              => $validated['no_hp'],
            'invoice_pembelian' => $validated['invoice_pembelian'] ?? null,
            'tanggal_beli'       => $validated['tanggal_beli'],
            'nama_marketplace'   => $validated['nama_marketplace'],
            'kerusakan'          => $validated['kerusakan'],
            'kelengkapan_barang' => $validated['kelengkapan_barang'],
            'lokasi_chat'        => $validated['lokasi_chat'],
            'status'             => 'pending',
        ]);

        foreach ($validated['items'] as $item) {
            $garansi->items()->create([
                'nama_barang'   => $item['nama_barang'],
                'serial_number' => $item['serial_number'],
            ]);
        }

        // PINDAHKAN KIRIM WA KE SINI (Setelah barang disimpan)
        try {
            $garansi->load('items'); // Pastikan relasi barang dimuat
            app(WhatsappService::class)->sendCreateNotification($garansi);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Gagal kirim WA create: " . $e->getMessage());
        }

        return redirect()
            ->route('garansi.show', $garansi)
            ->with('success', 'Data garansi berhasil dibuat. Notifikasi WhatsApp sedang dikirim.');
    }

    public function show(Garansi $garansi)
    {
        $garansi->load(['items', 'whatsappLogs' => function ($query) {
            $query->latest(); // Urutkan dari yang paling baru
        }]);
        $statusList = [
            'pending',
            'repair',
            'replace',
            'to distribution',
            'pengiriman',
            'selesai',
        ];

        return view('garansi.show', compact('garansi', 'statusList'));
    }

    public function edit(Garansi $garansi)
    {
        $garansi->load('items');
        $lokasiList = config('whatsapp.lokasi');

        return view('garansi.edit', compact('garansi', 'lokasiList'));
    }

    public function update(Request $request, Garansi $garansi)
    {
        $validated = $request->validate([
            'nama'               => ['required', 'string', 'max:255'],
            'no_hp'              => ['required', 'string', 'max:20'],
            'invoice_pembelian'          => ['nullable', 'string', 'max:255'],
            'tanggal_beli'       => ['required', 'date'],
            'nama_marketplace'   => ['required', 'string', 'max:255'],
            'kerusakan'          => ['required', 'string'],
            'kelengkapan_barang' => ['required', 'string'],
            'lokasi_chat' => ['required', 'string', Rule::in(array_keys(config('whatsapp.lokasi')))],
            'items'              => ['required', 'array', 'min:1'],
            'items.*.id'            => ['nullable', 'exists:garansi_items,id'],
            'items.*.nama_barang'   => ['required', 'string', 'max:255'],
            'items.*.serial_number' => ['required', 'string', 'max:255'],
        ]);

        $garansi->update([
            'nama'               => $validated['nama'],
            'no_hp'              => $validated['no_hp'],
            'invoice_pembelian'  => $validated['invoice_pembelian'] ?? null,
            'tanggal_beli'       => $validated['tanggal_beli'],
            'nama_marketplace'   => $validated['nama_marketplace'],
            'kerusakan'          => $validated['kerusakan'],
            'kelengkapan_barang' => $validated['kelengkapan_barang'],
            'lokasi_chat'        => $validated['lokasi_chat'],
        ]);

        // Sync items
        $existingIds = [];
        foreach ($validated['items'] as $itemData) {
            if (!empty($itemData['id'])) {
                GaransiItem::where('id', $itemData['id'])
                    ->where('garansi_id', $garansi->id)
                    ->update([
                        'nama_barang'   => $itemData['nama_barang'],
                        'serial_number' => $itemData['serial_number'],
                    ]);
                $existingIds[] = $itemData['id'];
            } else {
                $newItem = $garansi->items()->create([
                    'nama_barang'   => $itemData['nama_barang'],
                    'serial_number' => $itemData['serial_number'],
                ]);
                $existingIds[] = $newItem->id;
            }
        }

        // Hapus item yang tidak ada di form
        $garansi->items()->whereNotIn('id', $existingIds)->delete();

        return redirect()
            ->route('garansi.show', $garansi)
            ->with('success', 'Data garansi berhasil diperbarui.');
    }

    public function destroy(Garansi $garansi)
    {
        $garansi->delete();

        return redirect()
            ->route('garansi.index')
            ->with('success', 'Data garansi berhasil dihapus.');
    }

    /**
     * Update status garansi (AJAX)
     */
    public function updateStatus(Request $request, Garansi $garansi)
    {
        $validated = $request->validate([
            'status'  => ['required', 'in:pending,repair,replace,to distribution,pengiriman,selesai'],
            'catatan' => ['nullable', 'string'],
        ]);

        $garansi->update([
            'status'  => $validated['status'],
            'catatan' => $validated['catatan'] ?? $garansi->catatan,
        ]);

        // Observer akan otomatis kirim WA jika status = selesai

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui.',
            'status'  => $garansi->status,
        ]);
    }

    /**
     * Kirim WhatsApp manual
     */
    public function sendWA(Request $request, Garansi $garansi)
    {
        $request->validate([
            'pesan' => ['required', 'string'],
        ]);

        $result = app(WhatsappService::class)->send(
            $garansi->no_hp,
            $request->pesan,
            $garansi->lokasi_chat,
            $garansi->id,
            'manual'
        );

        return response()->json($result);
    }

    /**
     * Scraping Invoice Pembelian (placeholder — logic scraping dibuat nanti)
     */
    public function scrapeInvoice(Request $request)
    {
        $request->validate([
            'invoice_pembelian' => ['required', 'string'],
        ]);

        // TODO: Implementasi logic scraping Invoice Pembelian
        // Contoh: scrape data dari marketplace berdasarkan Invoice Pembelian

        return response()->json([
            'success' => false,
            'message' => 'Logic scraping belum diimplementassi. Akan dibuat nanti.',
            'invoice_pembelian' => $request->invoice_pembelian,
        ]);
    }

        /**
     * Kirim ulang pesan WhatsApp berdasarkan Log
     */
    public function resendWA(Request $request, Garansi $garansi, $logId)
    {
        try {
            $log = \App\Models\WhatsappLog::findOrFail($logId);

            $result = app(WhatsappService::class)->send(
                $garansi->no_hp,
                $log->pesan,
                $garansi->lokasi_chat,
                $garansi->id,
                'resend'
            );

            return response()->json($result);
            
        } catch (\Exception $e) {
            // Jika ada error, kembalikan sebagai JSON 500
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
}