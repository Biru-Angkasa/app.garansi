<?php

namespace App\Http\Controllers;

use App\Models\Garansi;
use App\Models\GaransiItem;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
                ->orWhere('no_hp', 'like', "%{$search}%")
                ->orWhereHas('items', function ($iq) use ($search) {
                    $iq->where('serial_number', 'like', "%{$search}%")
                        ->orWhere('serial_number_baru', 'like', "%{$search}%");
                });
            });
        }

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
            'invoice_pembelian'  => ['nullable', 'string', 'max:255'],
            'tanggal_beli'       => ['required', 'date'],
            'nama_marketplace'   => ['required', 'string', 'max:255'],
            'kerusakan'          => ['required', 'string'],
            'kelengkapan_barang' => ['required', 'string'],
            'lokasi_chat'        => ['required', 'string', Rule::in(array_keys(config('whatsapp.lokasi')))],
            'items'              => ['required', 'array', 'min:1'],
            'items.*.nama_barang'   => ['required', 'string', 'max:255'],
            'items.*.serial_number' => ['required', 'string', 'max:255'],
        ]);

        $garansi = Garansi::create([
            'nama'               => $validated['nama'],
            'no_hp'              => $validated['no_hp'],
            'invoice_pembelian'  => $validated['invoice_pembelian'] ?? null,
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

        try {
            $garansi->load('items');
            app(WhatsappService::class)->sendCreateNotification($garansi);
        } catch (\Exception $e) {
            Log::error("Gagal kirim WA create: " . $e->getMessage());
        }

        return redirect()
            ->route('garansi.show', $garansi)
            ->with('success', 'Data garansi berhasil dibuat. Notifikasi WhatsApp sedang dikirim.');
    }

    public function show(Garansi $garansi)
    {
        $garansi->load(['items', 'whatsappLogs' => function ($query) {
            $query->latest();
        }]);
        
        $statusList = [
            'pending', 'repair', 'replace', 'to distribution', 'pengiriman', 'selesai',
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
            'invoice_pembelian'  => ['nullable', 'string', 'max:255'],
            'tanggal_beli'       => ['required', 'date'],
            'nama_marketplace'   => ['required', 'string', 'max:255'],
            'kerusakan'          => ['required', 'string'],
            'kelengkapan_barang' => ['required', 'string'],
            'lokasi_chat'        => ['required', 'string', Rule::in(array_keys(config('whatsapp.lokasi')))],
            'items'              => ['required', 'array', 'min:1'],
            'items.*.id'         => ['nullable', 'exists:garansi_items,id'],
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

        $garansi->items()->whereNotIn('id', $existingIds)->delete();

        return redirect()
            ->route('garansi.show', $garansi)
            ->with('success', 'Data garansi berhasil diperbarui.');
    }

    public function destroy(Garansi $garansi)
    {
        $garansi->delete();
        return redirect()->route('garansi.index')->with('success', 'Data garansi berhasil dihapus.');
    }

    /**
     * Update status garansi dengan percabangan (Replace, Pengiriman, Kamera)
     */
    public function updateStatus(Request $request, Garansi $garansi)
    {
        $rules = [
            'status'           => ['required', 'in:pending,repair,replace,to distribution,pengiriman,selesai'],
            'catatan'          => ['nullable', 'string'],
            'sn_pengganti'     => ['nullable', 'string', 'max:255'],
            'resi_pengiriman'  => ['nullable', 'string', 'max:255'],
            'bukti_foto_data'  => ['nullable', 'string'], // Base64 dari JS
        ];

        $validated = $request->validate($rules);

        $data = [
            'status'  => $validated['status'],
            'catatan' => $validated['catatan'] ?? $garansi->catatan,
        ];

        if (isset($validated['sn_pengganti'])) $data['sn_pengganti'] = $validated['sn_pengganti'];
        if (isset($validated['resi_pengiriman'])) $data['resi_pengiriman'] = $validated['resi_pengiriman'];

        $garansi->update($data);

        // Matikan observer default agar tidak dobel kirim, kita handle di sini
        // (Pastikan di GaransiObserver.php method updated() di kosongkan/dirombak)

        try {
            if (!empty($validated['bukti_foto_data'])) {
                // Jika ada foto, kirim sebagai Image + Caption ke WAHA
                $caption = "Halo *{$garansi->nama}*,\n\n";
                $caption .= "Status garansi Anda telah diperbarui:\n";
                $caption .= "🔄 Status: *" . strtoupper($garansi->status) . "*\n";
                
                if ($garansi->status === 'replace' && !empty($garansi->sn_pengganti)) {
                    $caption .= "🔧 SN Pengganti: *{$garansi->sn_pengganti}*\n";
                }
                if ($garansi->status === 'pengiriman' && !empty($garansi->resi_pengiriman)) {
                    $caption .= "🚚 No Resi: *{$garansi->resi_pengiriman}*\n";
                }
                if ($garansi->catatan) {
                    $caption .= "📝 Catatan: {$garansi->catatan}\n";
                }
                $caption .= "\nTerima kasih 🙏";

                $result = app(WhatsappService::class)->sendImage(
                $garansi->no_hp,
                $caption,
                $validated['bukti_foto_data'],
                $garansi->lokasi_chat,
                $garansi->id,
                'photo_update'
            );

            if (!$result['success']) {
                Log::error('WA foto gagal dikirim', $result);
            }
            } else {
                // Jika tidak ada foto, kirim WA teks biasa
                app(WhatsappService::class)->sendStatusNotification($garansi);
            }
        } catch (\Exception $e) {
            Log::error("Gagal kirim WA update status: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui.',
            'status'  => $garansi->status,
        ]);
    }

    /**
     * Simpan Serial Number baru untuk sebuah item
     */
    public function replaceItemSerial(Request $request, Garansi $garansi, GaransiItem $item)
    {
        abort_unless($item->garansi_id === $garansi->id, 404);

        $validated = $request->validate([
            'serial_number_baru' => ['required', 'string', 'max:255'],
        ]);

        $item->update([
            'serial_number_baru' => $validated['serial_number_baru'],
            'replaced_at'        => now(),
        ]);

        try {
            app(WhatsappService::class)->sendReplaceNotification($garansi, $item->fresh());
        } catch (\Exception $e) {
            Log::error("Gagal kirim WA replace SN: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'SN baru berhasil disimpan. Notifikasi WhatsApp sedang dikirim.',
            'item'    => $item->fresh(),
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
     * Scraping Invoice Pembelian dari Odoo ERP
     */
    public function scrapeInvoice(Request $request)
    {
        $request->validate([
            'invoice_pembelian' => ['required', 'string'],
            'odoo_instance'     => ['required', 'string', Rule::in(array_keys(config('odoo.instances')))],
        ]);

        try {
            $odooService = app(\App\Services\OdooService::class);
            $result = $odooService->scrapeByInvoice(
                $request->odoo_instance,
                $request->invoice_pembelian
            );

            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan dari ' . config("odoo.instances.{$request->odoo_instance}.nama") . '!',
                'data'    => $result,
            ]);
        } catch (\Exception $e) {
            Log::warning('Scrape invoice gagal: ' . $e->getMessage(), [
                'invoice' => $request->invoice_pembelian,
                'odoo'    => $request->odoo_instance,
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Scraping data berdasarkan Serial Number (SN) dari Odoo ERP
     */
    public function scrapeSN(Request $request)
    {
        $request->validate([
            'serial_number' => ['required', 'string'],
            'odoo_instance' => ['required', 'string', Rule::in(array_keys(config('odoo.instances')))],
        ]);

        try {
            $odooService = app(\App\Services\OdooService::class);
            $result = $odooService->scrapeBySN(
                $request->odoo_instance,
                $request->serial_number
            );

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'SN tidak ditemukan di ' . config("odoo.instances.{$request->odoo_instance}.nama")
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan dari ' . config("odoo.instances.{$request->odoo_instance}.nama") . '!',
                'data'    => $result,
            ]);
        } catch (\Exception $e) {
            Log::warning('Scrape SN gagal: ' . $e->getMessage(), [
                'sn'   => $request->serial_number,
                'odoo' => $request->odoo_instance,
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Resend WhatsApp Messagen WhatsApp berdasarkan Log
     */
    public function resendWA(Request $request, Garansi $garansi, $logId)
    {
        try {
            $log = \App\Models\WhatsappLog::findOrFail($logId);

            // Jika log punya foto, kirim ulang sebagai foto
            if ($log->image_data) {
                $result = app(WhatsappService::class)->sendImage(
                    $garansi->no_hp,
                    $log->pesan,
                    $log->image_data,
                    $garansi->lokasi_chat,
                    $garansi->id,
                    'resend'
                );
            } else {
                // Jika tidak, kirim ulang sebagai teks
                $result = app(WhatsappService::class)->send(
                    $garansi->no_hp,
                    $log->pesan,
                    $garansi->lokasi_chat,
                    $garansi->id,
                    'resend'
                );
            }

            return response()->json($result);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
}