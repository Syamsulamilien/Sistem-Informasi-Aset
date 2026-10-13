<x-app-layout :title="'Jadwal Maintenance Tertinggal'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('maintenance.index') }}" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-xl border border-gray-200 transition-colors shadow-sm" title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Jadwal Maintenance Tertinggal</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Daftar pemeliharaan aset yang sudah melampaui tanggal jatuh tempo</p>
                </div>
            </div>
            <a href="{{ route('maintenance.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Semua Maintenance
            </a>
        </div>
            
            @if ($overdueMaintenances->count() > 0)
            <div style="margin-bottom: 24px; background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 16px;">
                <div style="display: flex; align-items: flex-start;">
                    <svg style="width: 24px; height: 24px; color: #dc2626; margin-right: 12px; margin-top: 4px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <h3 style="color: #7f1d1d; font-weight: 600; margin-bottom: 4px;">Perhatian Diperlukan!</h3>
                        <p style="color: #991b1b; font-size: 14px;">Pekerjaan maintenance ini sudah melampaui tanggal terjadwal dan memerlukan perhatian segera.</p>
                    </div>
                </div>
            </div>
            @endif

            <div style="background-color: white; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 8px;">
                <div style="padding: 24px;">
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                    <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Aset</th>
                                    <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Tanggal Terjadwal</th>
                                    <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Hari Tertinggal</th>
                                    <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Teknisi</th>
                                    <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Biaya</th>
                                    <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($overdueMaintenances as $maintenance)
                                    @php
                                        $daysOverdue = abs(now()->diffInDays($maintenance->schedule_date, false));
                                        $isCritical = $daysOverdue > 30;
                                        $bgColor = $isCritical ? '#fef2f2' : '#fffbeb';
                                    @endphp
                                    <tr style="border-bottom: 1px solid #e5e7eb; background-color: {{ $bgColor }}; transition: background-color 0.2s; {{ $isCritical ? 'border-left: 4px solid #dc2626;' : 'border-left: 4px solid #f97316;' }}">
                                        <td style="padding: 12px;">
                                            <div style="font-weight: 600; color: #111827;">{{ $maintenance->asset->asset_code }}</div>
                                            <div style="font-size: 14px; color: #6b7280;">{{ $maintenance->asset->brand }} {{ $maintenance->asset->model }}</div>
                                            <div style="font-size: 12px; color: #9ca3af;">{{ $maintenance->asset->assetType->name ?? 'N/A' }}</div>
                                        </td>
                                        <td style="padding: 12px; font-size: 14px; color: #111827; white-space: nowrap;">
                                            {{ $maintenance->schedule_date->format('d M Y') }}
                                        </td>
                                        <td style="padding: 12px; font-size: 14px; white-space: nowrap;">
                                            @if ($isCritical)
                                                <span style="color: #dc2626; font-weight: bold; display: inline-block; padding: 4px 8px; background-color: #fee2e2; border-radius: 4px;">{{ $daysOverdue }} hari! 🔴</span>
                                            @else
                                                <span style="color: #ea580c; font-weight: 600; display: inline-block; padding: 4px 8px; background-color: #ffedd5; border-radius: 4px;">{{ $daysOverdue }} hari</span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px; font-size: 14px; color: #111827; white-space: nowrap;">
                                            {{ $maintenance->technician->name }}
                                        </td>
                                        <td style="padding: 12px; font-size: 14px; color: #111827; white-space: nowrap;">
                                            Rp {{ number_format($maintenance->cost, 0, ',', '.') }}
                                        </td>
                                        <td style="padding: 12px; font-size: 14px; font-weight: 500; white-space: nowrap;">
                                            <div style="display: flex; gap: 8px;">
                                                <a href="{{ route('maintenance.show', $maintenance) }}" style="color: #2563eb; text-decoration: none; cursor: pointer;"
                                                   onmouseover="this.style.color='#1d4ed8'"
                                                   onmouseout="this.style.color='#2563eb'">Lihat</a>
                                                <a href="{{ route('maintenance.edit', $maintenance) }}" style="color: #4f46e5; text-decoration: none; cursor: pointer;"
                                                   onmouseover="this.style.color='#4338ca'"
                                                   onmouseout="this.style.color='#4f46e5'">Edit</a>
                                                <form action="{{ route('maintenance.complete', $maintenance) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" style="color: #16a34a; text-decoration: none; cursor: pointer; border: none; background: none; padding: 0; font-weight: 500;"
                                                            onmouseover="this.style.color='#15803d'"
                                                            onmouseout="this.style.color='#16a34a'">Selesai</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="padding: 32px 12px; text-align: center;">
                                            <div style="color: #d1d5db; margin-bottom: 8px;">
                                                <svg style="width: 48px; height: 48px; margin-left: auto; margin-right: auto; margin-bottom: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <p style="color: #6b7280; font-weight: 600; margin-bottom: 4px;">Tidak ada jadwal maintenance yang tertinggal</p>
                                            <p style="color: #9ca3af; font-size: 14px;">Bagus! Semua tugas maintenance sudah terkini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 16px;">
                        {{ $overdueMaintenances->links() }}
                    </div>
                </div>
            </div>
        </div>

    <script>
        // Update hari tertinggal secara realtime setiap detik
        function updateOverdueStatus() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const scheduleDateCell = row.cells[1];
                const daysOverdueCell = row.cells[2];
                
                if (scheduleDateCell && scheduleDateCell.textContent) {
                    // Parse tanggal dari cell (format: dd M Y)
                    const scheduleDateText = scheduleDateCell.textContent.trim();
                    const dateObj = new Date(scheduleDateText);
                    
                    if (!isNaN(dateObj)) {
                        const now = new Date();
                        const daysOverdue = Math.floor((now - dateObj) / (1000 * 60 * 60 * 24));
                        
                        if (daysOverdue > 0 && daysOverdueCell) {
                            const isCritical = daysOverdue > 30;
                            let badgeHTML = '';
                            
                            if (isCritical) {
                                badgeHTML = `<span style="color: #dc2626; font-weight: bold; display: inline-block; padding: 4px 8px; background-color: #fee2e2; border-radius: 4px;">${daysOverdue} hari! 🔴</span>`;
                            } else {
                                badgeHTML = `<span style="color: #ea580c; font-weight: 600; display: inline-block; padding: 4px 8px; background-color: #ffedd5; border-radius: 4px;">${daysOverdue} hari</span>`;
                            }
                            
                            daysOverdueCell.innerHTML = badgeHTML;
                        }
                    }
                }
            });
        }

        // Update setiap 60 detik (1 menit)
        updateOverdueStatus();
        setInterval(updateOverdueStatus, 60000);
    </script>
</x-app-layout>