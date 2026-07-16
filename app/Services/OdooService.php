<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OdooService
{
    protected string $username;
    protected string $password;

    public function __construct()
    {
        $this->username = config('odoo.username');
        $this->password = config('odoo.password');

        if (empty($this->username) || empty($this->password)) {
            throw new \RuntimeException('Odoo credential belum dikonfigurasi di .env');
        }
    }

    /**
     * Authenticate ke Odoo via JSON-RPC.
     * Return uid (user ID) jika berhasil.
     */
    public function authenticate(string $instanceKey): array
    {
        $instance = $this->getInstance($instanceKey);

        $response = Http::timeout(15)->post($instance['url'] . '/jsonrpc', [
            'jsonrpc' => '2.0',
            'method'  => 'call',
            'id'      => 1,
            'params'  => [
                'service' => 'common',
                'method'  => 'authenticate',
                'args'    => [
                    $instance['db'],
                    $this->username,
                    $this->password,
                    [],
                ],
            ],
        ]);

        $result = $response->json('result');

        if (!$result || $result === false) {
            throw new \RuntimeException(
                "Login Odoo gagal untuk instance '{$instance['nama']}'. Cek username/password/database."
            );
        }

        return [
            'uid'      => $result,
            'url'      => $instance['url'],
            'db'       => $instance['db'],
            'instance' => $instance,
        ];
    }

    /**
     * Generic execute_kw via JSON-RPC (stateless, no cookie needed).
     */
    public function executeKw(string $url, string $db, int $uid, string $model, string $method, array $args = [], array $kwargs = []): mixed
    {
        $response = Http::timeout(30)->post($url . '/jsonrpc', [
            'jsonrpc' => '2.0',
            'method'  => 'call',
            'id'      => 2,
            'params'  => [
                'service' => 'object',
                'method'  => 'execute_kw',
                'args'    => [
                    $db,
                    $uid,
                    $this->password,
                    $model,
                    $method,
                    $args,
                    $kwargs,
                ],
            ],
        ]);

        $json = $response->json();

        if (isset($json['error'])) {
            $errorMsg = $json['error']['data']['message'] ?? $json['error']['message'] ?? 'Unknown Odoo error';
            throw new \RuntimeException("Odoo Error: {$errorMsg}");
        }

        return $json['result'] ?? null;
    }

    /**
     * Scrape data invoice dari Odoo.
     *
     * Flow:
     * 1. Login ke instance
     * 2. Cari sale.order berdasarkan invoice marketplace
     * 3. Ambil data produk dari order lines
     * 4. Cari stock.picking terkait untuk SN
     */
    public function scrapeByInvoice(string $instanceKey, string $invoiceNumber): ?array
    {
        $url = config("odoo.instances.{$instanceKey}.url");
        $db = config("odoo.instances.{$instanceKey}.db");
        
        $auth = $this->authenticate($instanceKey);
        $uid = $auth['uid'];

        $saleOrder = $this->findSaleOrder($url, $db, $uid, $invoiceNumber);
        
        if (!$saleOrder) {
            Log::warning("Odoo: Invoice '{$invoiceNumber}' tidak ditemukan di {$instanceKey}.");
            return null;
        }

        return $this->formatSaleOrderData($url, $db, $uid, $saleOrder);
    }

    /**
     * Scrape data berdasarkan Serial Number (SN)
     */
    public function scrapeBySN(string $instanceKey, string $serialNumber): ?array
    {
        $url = config("odoo.instances.{$instanceKey}.url");
        $db = config("odoo.instances.{$instanceKey}.db");
        
        $auth = $this->authenticate($instanceKey);
        $uid = $auth['uid'];

        // 1. Cari SN di stock.move.line
        $moveLines = $this->executeKw($url, $db, $uid, 'stock.move.line', 'search_read', [
            [['lot_id.name', '=', $serialNumber]]
        ], [
            'fields' => ['picking_id'],
        ]);

        if (empty($moveLines)) {
            // Fallback (kadang Odoo lama simpan di lot_name sebagai text)
            $moveLines = $this->executeKw($url, $db, $uid, 'stock.move.line', 'search_read', [
                [['lot_name', '=', $serialNumber]]
            ], [
                'fields' => ['picking_id'],
            ]);
        }

        if (empty($moveLines)) {
            Log::warning("Odoo: SN '{$serialNumber}' tidak ditemukan di {$instanceKey}.");
            return null;
        }

        $pickingIdsRaw = array_filter(array_column($moveLines, 'picking_id'), function($p) {
            return is_array($p) && !empty($p);
        });
        $pickingIds = array_unique(array_map(function($p) { return $p[0]; }, $pickingIdsRaw));

        if (empty($pickingIds)) return null;

        // 2. Cari origin (Sales Order) dari picking tersebut
        $pickings = $this->executeKw($url, $db, $uid, 'stock.picking', 'search_read', [
            [['id', 'in', array_values($pickingIds)]]
        ], [
            'fields' => ['origin'],
        ]);

        $soName = null;
        foreach ($pickings as $picking) {
            $origin = $picking['origin'] ?? '';
            // Pastikan kita ambil Sales Order (biasanya depannya SO/)
            if (str_starts_with(strtoupper($origin), 'SO/')) {
                $soName = $origin;
                break;
            }
        }

        if (!$soName) {
            Log::warning("Odoo: SO untuk SN '{$serialNumber}' tidak ditemukan.");
            return null;
        }

        // 3. Tarik data SO nya
        $saleOrder = $this->findSaleOrder($url, $db, $uid, $soName);
        if (!$saleOrder) return null;

        return $this->formatSaleOrderData($url, $db, $uid, $saleOrder);
    }

    /**
     * Format data Sale Order menjadi format standard aplikasi
     */
    protected function formatSaleOrderData(string $url, string $db, int $uid, array $saleOrder): array
    {

        // 3. Ambil order lines (produk + qty)
        $orderLines = $this->getOrderLines($url, $db, $uid, $saleOrder['id']);

        // 4. Cari SN dari delivery order (stock.picking)
        $serialNumbers = $this->getSerialNumbers($url, $db, $uid, $saleOrder['name']);

        // 5. Gabungkan data items
        $items = $this->mergeItemsWithSN($orderLines, $serialNumbers);

        // 6. Tentukan marketplace dari source / team / tag
        $marketplace = $this->detectMarketplace($saleOrder);

        // 7. Tanggal beli
        $tanggalBeli = null;
        if (!empty($saleOrder['date_order'])) {
            try {
                $tanggalBeli = date('Y-m-d', strtotime($saleOrder['date_order']));
            } catch (\Exception $e) {
                $tanggalBeli = null;
            }
        }

        return [
            'items'                   => $items,
            'tanggal_beli'            => $tanggalBeli,
            'nama_marketplace'        => $marketplace,
            'sale_order_name'         => $saleOrder['name'] ?? null,
            'customer_name'           => is_array($saleOrder['partner_id'] ?? null) ? ($saleOrder['partner_id'][1] ?? '') : '',
            'invoice_or_order_number' => $saleOrder['ht_inv_marketplace'] ?: ($saleOrder['client_order_ref'] ?: ($saleOrder['name'] ?? null)),
        ];
    }

    /**
     * Cari sale.order berdasarkan invoice marketplace.
     * Coba beberapa field: ht_inv_marketplace, client_order_ref, origin, name.
     */
    protected function findSaleOrder(string $url, string $db, int $uid, string $invoiceNumber): ?array
    {
        $fieldsToFetch = [
            'id', 'name', 'client_order_ref', 'origin', 'date_order',
            'partner_id', 'team_id', 'order_line', 'state',
            'ht_inv_marketplace', 'ht_ekspedisi', 'ht_customer_id',
        ];

        // Custom field Odoo (ht_inv_marketplace) jadi prioritas utama
        $searchFields = ['ht_inv_marketplace', 'client_order_ref', 'origin', 'name'];

        foreach ($searchFields as $field) {
            $results = $this->executeKw($url, $db, $uid, 'sale.order', 'search_read', [
                [[$field, 'ilike', $invoiceNumber]],
            ], [
                'fields' => $fieldsToFetch,
                'limit'  => 1,
            ]);

            if (!empty($results)) {
                Log::info("Odoo: Invoice '{$invoiceNumber}' ditemukan di field '{$field}'", [
                    'so_name' => $results[0]['name'] ?? 'N/A',
                ]);
                return $results[0];
            }
        }

        return null;
    }

    /**
     * Ambil detail order lines dari sale.order.
     */
    protected function getOrderLines(string $url, string $db, int $uid, int $orderId): array
    {
        $lines = $this->executeKw($url, $db, $uid, 'sale.order.line', 'search_read', [
            [['order_id', '=', $orderId]],
        ], [
            'fields' => ['product_id', 'name', 'product_uom_qty'],
        ]);

        $items = [];
        foreach ($lines as $line) {
            // Skip section/note lines (no product)
            if (empty($line['product_id'])) {
                continue;
            }

            $productName = is_array($line['product_id']) ? $line['product_id'][1] : ($line['name'] ?? 'Unknown Product');

            // Bersihkan nama produk (hilangkan deskripsi HTML panjang)
            $productName = strip_tags($productName);
            if (strlen($productName) > 150) {
                $productName = substr($productName, 0, 150);
            }

            $qty = (int) ($line['product_uom_qty'] ?? 1);
            for ($i = 0; $i < $qty; $i++) {
                $items[] = [
                    'nama_barang'   => $productName,
                    'serial_number' => '',
                    'product_id'    => is_array($line['product_id']) ? $line['product_id'][0] : null,
                ];
            }
        }

        return $items;
    }

    /**
     * Ambil Serial Numbers dari delivery order (stock.picking → stock.move.line).
     */
    protected function getSerialNumbers(string $url, string $db, int $uid, string $soName): array
    {
        // Cari stock.picking terkait SO
        $pickings = $this->executeKw($url, $db, $uid, 'stock.picking', 'search_read', [
            [['origin', '=', $soName]],
        ], [
            'fields' => ['id', 'name', 'state'],
        ]);

        if (empty($pickings)) {
            return [];
        }

        $pickingIds = array_column($pickings, 'id');

        // Ambil stock.move.line dari picking(s)
        $moveLines = $this->executeKw($url, $db, $uid, 'stock.move.line', 'search_read', [
            [['picking_id', 'in', $pickingIds]],
        ], [
            'fields' => ['product_id', 'lot_name', 'lot_id', 'qty_done'],
        ]);

        $serialNumbers = [];
        foreach ($moveLines as $ml) {
            $sn = '';
            if (!empty($ml['lot_name'])) {
                $sn = $ml['lot_name'];
            } elseif (is_array($ml['lot_id'] ?? null) && !empty($ml['lot_id'][1])) {
                $sn = $ml['lot_id'][1];
            }

            if (!empty($sn)) {
                $productId = is_array($ml['product_id']) ? $ml['product_id'][0] : null;
                $serialNumbers[] = [
                    'product_id'    => $productId,
                    'serial_number' => $sn,
                ];
            }
        }

        return $serialNumbers;
    }

    /**
     * Gabungkan items (dari order lines) dengan SN (dari delivery).
     * Match berdasarkan product_id.
     */
    protected function mergeItemsWithSN(array $orderLines, array $serialNumbers): array
    {
        // Group SN by product_id
        $snByProduct = [];
        foreach ($serialNumbers as $sn) {
            $pid = $sn['product_id'];
            if ($pid) {
                $snByProduct[$pid][] = $sn['serial_number'];
            }
        }

        // Assign SN ke items
        foreach ($orderLines as &$item) {
            $pid = $item['product_id'] ?? null;
            if ($pid && !empty($snByProduct[$pid])) {
                $item['serial_number'] = array_shift($snByProduct[$pid]);
            }
            unset($item['product_id']); // Gak perlu di-return ke frontend
        }

        return $orderLines;
    }

    /**
     * Deteksi nama marketplace dari data sale.order.
     */
    protected function detectMarketplace(array $saleOrder): string
    {
        // 1. Coba deteksi dari nama Customer (partner_id) - format Odoo: "Shopee HaidarShop6"
        $partnerName = is_array($saleOrder['partner_id'] ?? null) ? $saleOrder['partner_id'][1] : '';
        $partnerLower = strtolower($partnerName);
        
        // Return nama lengkap jika terdeteksi ada keyword marketplace
        if (str_contains($partnerLower, 'shopee') || 
            str_contains($partnerLower, 'tokopedia') || 
            str_contains($partnerLower, 'tiktok') || 
            str_contains($partnerLower, 'lazada') || 
            str_contains($partnerLower, 'bukalapak')) {
            return trim($partnerName);
        }
        
        // 2. Coba deteksi dari team_id (Sales Team)
        if (!empty($saleOrder['team_id']) && is_array($saleOrder['team_id'])) {
            $teamName = $saleOrder['team_id'][1] ?? '';
            $teamLower = strtolower($teamName);
            
            if (str_contains($teamLower, 'shopee') || 
                str_contains($teamLower, 'tokopedia') || 
                str_contains($teamLower, 'tiktok')) {
                return trim($teamName);
            }
        }

        // 3. Coba deteksi dari format referensi / invoice number (Fallback)
        $ref = $saleOrder['ht_inv_marketplace'] ?? $saleOrder['client_order_ref'] ?? $saleOrder['origin'] ?? '';
        $ref = strtolower($ref);

        if (str_contains($ref, 'shopee') || preg_match('/^\d{14,15}[A-Z0-9]+$/i', trim($ref))) {
            return 'Shopee';
        }
        if (str_contains($ref, 'tokopedia') || str_starts_with($ref, 'inv/')) {
            return 'Tokopedia';
        }
        if (str_contains($ref, 'tiktok') || str_contains($ref, 'tts')) {
            return 'TikTok Shop';
        }
        
        return 'Lainnya';
    }

    /**
     * Get instance config by key.
     */
    protected function getInstance(string $key): array
    {
        $instance = config("odoo.instances.{$key}");

        if (!$instance) {
            $available = implode(', ', array_keys(config('odoo.instances', [])));
            throw new \RuntimeException("Instance Odoo '{$key}' tidak ditemukan. Available: {$available}");
        }

        if (empty($instance['url']) || empty($instance['db'])) {
            throw new \RuntimeException("Konfigurasi instance Odoo '{$key}' belum lengkap (url/db).");
        }

        return $instance;
    }
}
