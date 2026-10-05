<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Label Asset {{ $asset->asset_code }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Helvetica, Arial, sans-serif;
            width: 226.77pt;
            height: 85.04pt;
        }
        .label-container {
            width: 226.77pt; /* 80mm */
            height: 85.04pt; /* 30mm */
            border: 2pt solid #000;
            box-sizing: border-box;
            padding: 4pt;
        }
        table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
        }
        .qr-col {
            width: 65pt;
            text-align: center;
            vertical-align: middle;
        }
        .info-col {
            padding-left: 4pt;
        }
        .header {
            font-size: 7.5pt;
            font-weight: bold;
            border-bottom: 1pt solid #000;
            padding-bottom: 2pt;
            margin-bottom: 3pt;
            text-align: center;
        }
        .code {
            font-size: 8.5pt;
            font-weight: bold;
            margin-bottom: 2pt;
        }
        .detail {
            font-size: 6.5pt;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <div class="label-container">
        <table>
            <tr>
                <td class="qr-col">
                    <img src="data:image/svg+xml;base64,{{ $qrcodeBase64 }}" width="65" height="65" style="display:block; margin:0 auto;" />
                </td>
                <td class="info-col">
                    <div class="header">
                        RSU PKU Muh Bantul<br>
                        <span style="font-size:5.5pt; font-weight:normal;">Aset IT</span>
                    </div>
                    <div class="code">{{ $asset->asset_code }}</div>
                    <div class="detail"><strong>{{ $asset->assetType->name ?? 'N/A' }}</strong></div>
                    <div class="detail">{{ Str::limit($asset->brand . ' ' . $asset->model, 25) }}</div>
                    <div class="detail">📍 {{ Str::limit($asset->location->name, 25) }}</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
