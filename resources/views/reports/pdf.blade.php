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
            font-size: 11px;
            margin: 15px;
            background-color: white;
        }
        .header {
            text-align: center;
            margin-bottom: 8px;
            border-bottom: 3px solid #333;
            padding-bottom: 8px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            margin-bottom: 3px;
        }
        .header p {
            margin: 2px 0;
            color: #666;
            font-size: 10px;
        }
        .description {
            text-align: justify;
            font-size: 9px;
            color: #444;
            margin-top: 8px;
            margin-bottom: 12px;
            line-height: 1.4;
        }
        .description p {
            margin: 4px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: white;
        }
        th {
            background-color: #4A5568;
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }
        td {
            padding: 6px 4px;
            border-bottom: 1px solid #ddd;
            font-size: 8px;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-aktif {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-nonaktif {
            background-color: #f8d7da;
            color: #721c24;
        }
        .badge-good, .badge-baik {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .badge-fair, .badge-rusak.ringan {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-poor, .badge-rusak.berat {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #666;
        }
        .total {
            font-weight: bold;
            background-color: #e2e8f0;
        }
        .maintenance-info {
            font-size: 7px;
            line-height: 1.3;
        }
        .maintenance-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 6px;
            font-weight: bold;
            margin-bottom: 2px;
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
    @endphp

    <div class="header">
        <h1>LAPORAN ASET IT</h1>
        <p>RSU PKU Muhammadiyah Bantul</p>
        <p>Tanggal: {{ date('d F Y') }}</p>
    </div>

    @if(isset($appliedFilters) && array_filter($appliedFilters))
        <div style="background-color: #f0f9ff; padding: 8px; margin-bottom: 10px; border-left: 3px solid #3b82f6; font-size: 9px;">
            <strong style="color: #1e40af;">Filter yang Diterapkan:</strong>
            <div style="margin-top: 4px; color: #1e3a8a;">
                @if($appliedFilters['asset_type'])
                    <span style="margin-right: 10px;">• Jenis Aset: <strong>{{ $appliedFilters['asset_type'] }}</strong></span>
                @endif
                @if($appliedFilters['kategori'])
                    <span style="margin-right: 10px;">• Kategori: <strong>{{ $appliedFilters['kategori'] }}</strong></span>
                @endif
                @if($appliedFilters['status'])
                    <span style="margin-right: 10px;">• Status: <strong>{{ $appliedFilters['status'] }}</strong></span>
                @endif
                @if($appliedFilters['condition'])
                    <span style="margin-right: 10px;">• Kondisi: <strong>{{ $appliedFilters['condition'] }}</strong></span>
                @endif
                @if($appliedFilters['location'])
                    <span style="margin-right: 10px;">• Lokasi: <strong>{{ $appliedFilters['location'] }}</strong></span>
                @endif
                @if($appliedFilters['year'])
                    <span style="margin-right: 10px;">• Tahun: <strong>{{ $appliedFilters['year'] }}</strong></span>
                @endif
            </div>
        </div>
    @endif

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
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 10%;">Kode Aset</th>
                <th style="width: 8%;">Jenis</th>
                <th style="width: 12%;">Merek/Model</th>
                <th style="width: 9%;">Lokasi</th>
                <th style="width: 6%;">Status</th>
                <th style="width: 5%;">Tahun</th>
                <th style="width: 8%;">Intensitas</th>
                <th style="width: 8%;">Masa Pakai</th>
                <th style="width: 9%;">Penanggung Jawab</th>
                <th style="width: 10%;">Harga (Rp)</th>
                <th style="width: 12%;">Maintenance</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalHarga = 0;
            @endphp
            @forelse($assets as $index => $asset)
                @php
                    // ✅ OPTIMIZED: Langsung ambil dari collection yang sudah di-index
                    $latestMaintenance = $maintenances[$asset->id] ?? null;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $asset->asset_code }}</td>
                    <td>{{ $asset->assetType->name ?? '-' }}</td>
                    <td style="font-size: 7px;">{{ $asset->brand }} {{ $asset->model }}</td>
                    <td>{{ $asset->location->name ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $asset->status == 'Aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                            {{ $asset->status }}
                        </span>
                    </td>
                    <td>
                        @if($asset->purchase_year == 0)
                            <span style="color: #999; font-style: italic; font-size: 7px;">N/A</span>
                        @else
                            {{ $asset->purchase_year }}
                        @endif
                    </td>
                    <td style="font-size: 7px;">
                        @if($asset->intensitas_pemakaian)
                            {{ $asset->intensitas_pemakaian }}
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td style="font-size: 7px;">
                        @if($asset->masa_pemakaian)
                            {{ $asset->masa_pemakaian }} {{ $asset->masa_pemakaian_satuan ?? 'Bulan' }}
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td style="font-size: 7px;">
                        @if($asset->penanggung_jawab)
                            {{ $asset->penanggung_jawab }}
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td>Rp {{ number_format($asset->price ?? 0, 0, ',', '.') }}</td>
                    <td class="maintenance-info">
                        @if($latestMaintenance)
                            @if($latestMaintenance->status === 'Completed')
                                <span class="maintenance-badge" style="background-color: #d4edda; color: #155724;">Selesai</span>
                            @elseif($latestMaintenance->status === 'Scheduled')
                                <span class="maintenance-badge" style="background-color: #fff3cd; color: #856404;">Terjadwal</span>
                            @elseif($latestMaintenance->status === 'In Progress')
                                <span class="maintenance-badge" style="background-color: #d1ecf1; color: #0c5460;">Proses</span>
                            @else
                                <span class="maintenance-badge" style="background-color: #e2e8f0; color: #4a5568;">{{ $latestMaintenance->status }}</span>
                            @endif
                            <br><small style="font-size: 6px;">{{ $latestMaintenance->schedule_date->format('d M Y') }}</small>
                            @if($latestMaintenance->technician)
                                <br><small style="color: #666; font-size: 6px;">{{ $latestMaintenance->technician->name }}</small>
                            @elseif($latestMaintenance->technician_name)
                                <br><small style="color: #666; font-size: 6px;">{{ $latestMaintenance->technician_name }}</small>
                            @endif
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                </tr>
                @php
                    $totalHarga += $asset->price ?? 0;
                @endphp
            @empty
                <tr>
                    <td colspan="12" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
            <tr class="total">
                <td colspan="10" style="text-align: right;"><strong>Total Harga:</strong></td>
                <td colspan="2"><strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Total Aset: {{ $assets->count() }}</p>
        <p>Dicetak pada: {{ date('d F Y H:i') }}</p>
    </div>
</body>
</html>