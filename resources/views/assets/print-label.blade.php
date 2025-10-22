<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label - {{ $asset->asset_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Single label box */
        .label {
            width: 80mm;
            height: 30mm;
            border: 2px solid #000;
            background: white;
            page-break-inside: avoid;
            padding: 2mm;
            display: flex;
            flex-direction: row;
            align-items: stretch;
            overflow: hidden;
            margin: 20mm auto;
        }

        /* QR Code Section - Left Side */
        .qrcode {
            flex-shrink: 0;
            width: 26mm;
            height: 26mm;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 2mm;
        }

        .qrcode svg {
            width: 26mm !important;
            height: 26mm !important;
            display: block;
        }

        /* Info Section - Right Side */
        .info-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
            overflow: hidden;
        }

        .header {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 1mm;
            margin-bottom: 1mm;
        }

        .header h1 {
            font-size: 7pt;
            font-weight: bold;
            line-height: 1.1;
            margin-bottom: 0.5mm;
        }

        .header p {
            font-size: 6pt;
            color: #333;
        }

        .info {
            flex: 1;
            font-size: 6pt;
            line-height: 1.2;
        }

        .info .code {
            font-size: 7pt;
            font-weight: bold;
            margin-bottom: 0.5mm;
            word-break: break-all;
        }

        .info .detail {
            margin: 0.3mm 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .footer {
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 0.5mm;
            font-size: 5pt;
            color: #666;
        }

        /* Print button */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 30px;
            background: #3B82F6;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14pt;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .print-button:hover {
            background: #2563EB;
        }

        .print-info {
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
            background: white;
            border: 2px solid #3B82F6;
            border-radius: 8px;
        }

        .print-info h3 {
            color: #3B82F6;
            margin-bottom: 10px;
        }

        .print-info ul {
            margin-left: 20px;
        }

        .print-info li {
            margin: 5px 0;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .print-button,
            .print-info {
                display: none !important;
            }

            .label {
                margin: 0;
                border: 2px solid #000;
            }
        }
    </style>
</head>
<body>
    <!-- Print Info (hanya tampil di screen) -->
    <div class="print-info">
        <h3>📄 Panduan Print Label</h3>
        <ul>
            <li><strong>Paper Size:</strong> A4 (210mm x 297mm)</li>
            <li><strong>Orientation:</strong> Portrait</li>
            <li><strong>Margins:</strong> 10mm semua sisi</li>
            <li><strong>Scale:</strong> 100% (Actual Size)</li>
            <li><strong>Background Graphics:</strong> ON</li>
            <li>💡 <strong>Ukuran Label: 80x30mm</strong> - Hanya 1 label per halaman</li>
        </ul>
    </div>

    <!-- Print Button -->
    <button onclick="window.print()" class="print-button">
        🖨️ Print Label
    </button>

    <!-- HANYA 1 LABEL -->
    <div class="label">
        <div class="qrcode">
            {!! $qrcode !!}
        </div>
        <div class="info-section">
            <div class="header">
                <h1>RSU PKU Muhammadiyah Bantul</h1>
                <p>Aset IT</p>
            </div>
            <div class="info">
                <div class="code">{{ $asset->asset_code }}</div>
                <div class="detail"><strong>{{ $asset->type }}</strong></div>
                <div class="detail">{{ $asset->brand }}</div>
                <div class="detail">{{ $asset->model }}</div>
                <div class="detail">📍 {{ $asset->location->name }}</div>
            </div>
            <div class="footer">
                Scan untuk info lengkap • {{ date('Y') }}
            </div>
        </div>
    </div>

</body>
</html>