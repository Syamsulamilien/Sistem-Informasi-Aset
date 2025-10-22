<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Aset IT</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            background-color: white;
        }
        .navbar {
            display: none;
        }
        .navbar-item {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .navbar-label {
            font-size: 10px;
            opacity: 0.85;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .navbar-value {
            font-size: 18px;
            font-weight: bold;
        }
        .navbar-item.maintenance {
            background: rgba(255,255,255,0.1);
            padding: 10px 15px;
            border-radius: 6px;
            backdrop-filter: blur(10px);
        }
        .maintenance-summary {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .maintenance-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
        }
        .maintenance-badge-small {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            white-space: nowrap;
        }
        .badge-scheduled {
            background-color: #ffc107;
            color: #333;
        }
        .badge-completed {
            background-color: #28a745;
            color: white;
        }
        .badge-overdue {
            background-color: #dc3545;
            color: white;
        }
        .header {
            text-align: center;
            margin-bottom: 8px;
            border-bottom: 3px solid #333;
            padding-bottom: 8px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            margin-bottom: 3px;
        }
        .header p {
            margin: 2px 0;
            color: #666;
            font-size: 11px;
        }
        .description {
            text-align: justify;
            font-size: 10px;
            color: #444;
            margin-top: 10px;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        .description p {
            margin: 6px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }
        th {
            background-color: #4A5568;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-aktif {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-nonaktif {
            background-color: #f8d7da;
            color: #721c24;
        }
        .badge-good {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .badge-fair {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-poor {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .total {
            font-weight: bold;
            background-color: #e2e8f0;
        }
        @media print {
            body {
                background-color: white;
                margin: 0;
            }
            .navbar {
                margin-left: -20px;
                margin-right: -20px;
                margin-top: -20px;
            }
        }
    </style>
</head>
<body>
    @php
        $total = $statistics['total_assets'] ?? $assets->count();
        $aktif = $statistics['active_count'] ?? $assets->where('status', 'Aktif')->count();
        $nonaktif = $statistics['inactive_count'] ?? $assets->where('status', 'Nonaktif')->count();
        $nilai = $statistics['total_value'] ?? $assets->sum('price');

        $lokasiTerbanyak = collect($statistics['by_location'] ?? [])->sortDesc()->keys()->first() ?? '-';
        $jenisTerbanyak = collect($statistics['by_type'] ?? [])->sortByDesc('count')->keys()->first() ?? '-';
        
        // Maintenance Statistics
        $maintenanceStats = [
            'scheduled' => 0,
            'completed' => 0,
            'overdue' => 0,
            'total_cost' => 0
        ];
        
        if(isset($maintenances)) {
            foreach($maintenances as $m) {
                if($m->status === 'Scheduled') {
                    $maintenanceStats['scheduled']++;
                    if($m->schedule_date && $m->schedule_date->isPast()) {
                        $maintenanceStats['overdue']++;
                    }
                } elseif($m->status === 'Completed') {
                    $maintenanceStats['completed']++;
                }
                $maintenanceStats['total_cost'] += $m->cost ?? 0;
            }
        }
    @endphp

    <!-- Navbar dengan Info -->
    <div class="navbar">
        <div class="navbar-item">
            <div class="navbar-label">Total Aset</div>
            <div class="navbar-value">{{ $total }}</div>
        </div>
        <div class="navbar-item">
            <div class="navbar-label">Aset Aktif</div>
            <div class="navbar-value" style="color: #84fab0;">{{ $aktif }}</div>
        </div>
        <div class="navbar-item">
            <div class="navbar-label">Total Nilai</div>
            <div class="navbar-value" style="font-size: 14px;">Rp {{ number_format($nilai, 0, ',', '.') }}</div>
        </div>
    @php
        $maintenanceStats = [
            'scheduled' => $maintenances->where('status', 'Scheduled')->count(),
            'completed' => $maintenances->where('status', 'Completed')->count(),
            'cancelled' => $maintenances->where('status', 'Cancelled')->count(),
            'overdue' => $maintenances->where('status', 'Scheduled')->filter(fn($m) => $m->schedule_date && $m->schedule_date->isPast())->count(),
            'total_cost' => $maintenances->sum('cost')
        ];
    @endphp
    </div>

    <div class="header">
        <h1>LAPORAN ASET IT</h1>
        <p>RSU PKU Muhammadiyah Bantul</p>
        <p>Tanggal: {{ date('d F Y') }}</p>
    </div>

    <div class="description">
        <p>
            Berdasarkan hasil pendataan terbaru, tercatat sebanyak <strong>{{ $total }}</strong> aset TI di lingkungan 
            <strong>RSU PKU Muhammadiyah Bantul</strong>. Dari jumlah tersebut, sebanyak 
            <strong>{{ $aktif }}</strong> aset berstatus aktif dan <strong>{{ $nonaktif }}</strong> aset berstatus nonaktif.
            Total nilai keseluruhan aset mencapai <strong>Rp {{ number_format($nilai, 0, ',', '.') }}</strong>.
        </p>
        <p>
            Jenis aset yang paling banyak digunakan adalah <strong>{{ $jenisTerbanyak }}</strong>, 
            sedangkan lokasi dengan jumlah aset terbanyak adalah <strong>{{ $lokasiTerbanyak }}</strong>. 
            Data ini menunjukkan distribusi aset yang merata di berbagai unit kerja, dengan fokus utama pada 
            pemeliharaan perangkat yang mendukung operasional rumah sakit.
        </p>
        <p>
            Laporan ini dibuat secara otomatis oleh sistem monitoring aset untuk membantu manajemen dalam 
            pengawasan, perencanaan pengadaan, serta evaluasi efektivitas pemanfaatan teknologi informasi di rumah sakit.
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Aset</th>
                <th>Jenis</th>
                <th>Merek/Model</th>
                <th>Lokasi</th>
                <th>Kondisi</th>
                <th>Status</th>
                <th>Tahun</th>
                <th>Harga (Rp)</th>
                <th>Status Maintenance</th>
                <th>Jadwal Maintenance</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalHarga = 0;
            @endphp
            @forelse($assets as $index => $asset)
                @php
                    // Ambil maintenance terbaru untuk aset ini
                    $latestMaintenance = $maintenances->where('asset_id', $asset->id)->sortByDesc('schedule_date')->first();
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $asset->asset_code }}</td>
                    <td>{{ $asset->assetType->name ?? '-' }}</td>
                    <td>{{ $asset->brand }} {{ $asset->model }}</td>
                    <td>{{ $asset->location->name ?? '-' }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower($asset->condition) }}">
                            {{ $asset->condition }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $asset->status == 'Aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                            {{ $asset->status }}
                        </span>
                    </td>
                    <td>{{ $asset->purchase_year }}</td>
                    <td>
                        Rp {{ number_format($asset->price ?? 0, 0, ',', '.') }}
                    </td>
                    <td style="min-width: 120px;">
                        @if($latestMaintenance)
                            @if($latestMaintenance->status === 'Completed')
                                <span class="badge" style="background-color: #d4edda; color: #155724; display: inline-block; margin-bottom: 8px;">Selesai</span>
                            @elseif($latestMaintenance->status === 'Scheduled')
                                <span class="badge" style="background-color: #fff3cd; color: #856404; display: inline-block; margin-bottom: 8px;">Sedang Maintenance</span>
                            @else
                                <span class="badge" style="background-color: #e2e8f0; color: #4a5568; display: inline-block; margin-bottom: 8px;">Dibatalkan</span>
                            @endif
                            <br>
                            <small style="color: #666; display: block;">Rp {{ number_format($latestMaintenance->cost ?? 0, 0, ',', '.') }}</small>
                        @else
                            <span style="color: #999; font-size: 9px;">-</span>
                        @endif
                    </td>
                    <td style="min-width: 140px;">
                        @if($latestMaintenance)
                            <div style="margin-bottom: 6px;">
                                <strong style="display: block; margin-bottom: 3px;">{{ $latestMaintenance->schedule_date->format('d M Y') }}</strong>
                                <small style="color: #666; display: block; margin-bottom: 2px;">{{ $latestMaintenance->technician->name ?? '-' }}</small>
                            </div>
                            @if($latestMaintenance->performed_date)
                                <div style="border-top: 1px solid #e0e0e0; padding-top: 6px;">
                                    <small style="color: #4a5568; display: block; font-weight: 600;">Selesai: {{ $latestMaintenance->performed_date->format('d M Y') }}</small>
                                </div>
                            @endif
                        @else
                            <span style="color: #999; font-size: 9px;">-</span>
                        @endif
                    </td>
                    @php
                        $totalHarga += $asset->price ?? 0;
                    @endphp
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
            <tr class="total">
                <td colspan="9" style="text-align: right;">Total Harga:</td>
                <td colspan="2">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Total Aset: {{ $assets->count() }}</p>
        <p>Dicetak pada: {{ date('d F Y H:i') }}</p>
    </div>
</body>
</html>