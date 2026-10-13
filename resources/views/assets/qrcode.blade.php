<x-app-layout :title="'QR Code ' . $asset->asset_code">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('assets.show', $asset) }}" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-xl border border-gray-200 transition-colors shadow-sm" title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">QR Code Aset</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">{{ $asset->asset_code }} • {{ $asset->brand }} {{ $asset->model }}</p>
                </div>
            </div>
            <a href="{{ route('assets.show', $asset) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Aset
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- QR Code Preview -->
                <div class="text-center">
                    <div class="bg-white p-6 rounded-lg border-2 border-gray-200 inline-block" id="qr-preview">
                        {!! $qrcode !!}
                    </div>
                    <p class="mt-4 text-sm text-gray-600">Scan untuk melihat info aset</p>
                </div>

                <!-- Info Aset -->
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-3">Informasi Aset</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Kode:</span>
                                <span class="font-medium">{{ $asset->asset_code }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Jenis:</span>
                                {{-- FIX: Ganti $asset->type jadi $asset->assetType->name --}}
                                <span class="font-medium">{{ $asset->assetType->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Merek/Model:</span>
                                <span class="font-medium">{{ $asset->brand }} {{ $asset->model }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Lokasi:</span>
                                <span class="font-medium">{{ $asset->location->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="px-2 py-1 text-xs rounded-full {{ $asset->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $asset->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-blue-900 mb-2">📱 Cara Menggunakan</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Scan QR Code dengan smartphone</li>
                            <li>• Info aset akan tampil di browser</li>
                            <li>• Tidak perlu login untuk melihat info</li>
                            <li>• Link aktif: <a href="{{ $url }}" target="_blank" class="underline break-all">{{ $url }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <button onclick="window.print()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print QR Code
                </button>

                <a href="{{ route('assets.qrcode.pdf', $asset) }}" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Label (PDF)
                </a>

                <a href="{{ route('assets.qrcode.download', $asset) }}" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download QR (PNG)
                </a>

                <a href="{{ $url }}" target="_blank" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    Preview Info
                </a>

                <a href="{{ route('assets.show', $asset) }}" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Hidden Print Template - Label 80x30mm -->
        <div id="print-label" style="display: none;">
            <div class="label-print">
                <div class="qrcode-print">
                    {!! $qrcode !!}
                </div>
                <div class="info-section-print">
                    <div class="header-print">
                        <h1>SMK Kesehatan Binatama</h1>
                        <p>SIASET</p>
                    </div>
                    <div class="info-print">
                        <div class="code-print">{{ $asset->asset_code }}</div>
                        {{-- FIX: Ganti $asset->type jadi $asset->assetType->name --}}
                        <div class="detail-print"><strong>{{ $asset->assetType->name ?? 'N/A' }}</strong></div>
                        <div class="detail-print">{{ $asset->brand }}</div>
                        <div class="detail-print">{{ $asset->model }}</div>
                        <div class="detail-print">📍 {{ $asset->location->name }}</div>
                    </div>
                    <div class="footer-print">
                        Scan untuk info lengkap • {{ date('Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Print Label Styles - 80x30mm */
        .label-print {
            width: 80mm;
            height: 30mm;
            border: 2px solid #000;
            background: white;
            padding: 2mm;
            display: flex;
            flex-direction: row;
            align-items: stretch;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .qrcode-print {
            flex-shrink: 0;
            width: 26mm;
            height: 26mm;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 2mm;
        }

        .qrcode-print svg {
            width: 26mm !important;
            height: 26mm !important;
            display: block;
        }

        .info-section-print {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
            overflow: hidden;
        }

        .header-print {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 1mm;
            margin-bottom: 1mm;
        }

        .header-print h1 {
            font-size: 7pt;
            font-weight: bold;
            line-height: 1.1;
            margin-bottom: 0.5mm;
        }

        .header-print p {
            font-size: 6pt;
            color: #333;
        }

        .info-print {
            flex: 1;
            font-size: 6pt;
            line-height: 1.2;
        }

        .code-print {
            font-size: 7pt;
            font-weight: bold;
            margin-bottom: 0.5mm;
            word-break: break-all;
        }

        .detail-print {
            margin: 0.3mm 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .footer-print {
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 0.5mm;
            font-size: 5pt;
            color: #666;
        }

        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }

            body * {
                visibility: hidden;
            }

            #print-label,
            #print-label * {
                visibility: visible;
            }

            #print-label {
                position: absolute;
                left: 0;
                top: 0;
                display: block !important;
            }

            .label-print {
                margin: 20mm auto;
            }
        }
    </style>
</x-app-layout>