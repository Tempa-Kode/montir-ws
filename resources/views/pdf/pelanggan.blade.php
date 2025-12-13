<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Data Pelanggan</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
            background-color: #ffffff;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #38a67c;
            padding-bottom: 20px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }

        .header h1 {
            margin: 0 0 10px 0;
            font-size: 26px;
            color: #38a67c;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header .subtitle {
            font-size: 14px;
            color: #64748b;
            margin: 5px 0;
            font-weight: 500;
        }

        .report-info {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border-left: 5px solid #38a67c;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .report-info .left,
        .report-info .right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .report-info .right {
            text-align: right;
        }

        .report-info .label {
            font-weight: bold;
            color: #374151;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .report-info .value {
            color: #1f2937;
            font-size: 14px;
        }

        .stats-container {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-spacing: 15px 0;
        }

        .stat-box {
            display: table-cell;
            background: linear-gradient(135deg, #38a67c, #38a67c);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
            position: relative;
            overflow: hidden;
        }

        .stat-box::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .stat-box .number {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .stat-box .label {
            font-size: 12px;
            opacity: 0.9;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #38a67c;
            margin: 30px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }

        .data-table thead {
            background: linear-gradient(135deg, #38a67c, #38a67c);
            color: white;
        }

        .data-table thead tr {
            background: #38a67c !important;
        }

        .data-table th {
            padding: 15px 12px;
            text-align: left;
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #38a67c;
            background-color: #38a67c;
            color: white;
        }

        .data-table td {
            padding: 12px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            background-color: white;
        }

        .data-table tbody tr:nth-child(even) td {
            background-color: #f9fafb;
        }

        .bengkel-name {
            font-weight: bold;
            color: #1f2937;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .contact-info {
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
            font-style: italic;
        }

        .address {
            max-width: 220px;
            word-wrap: break-word;
            line-height: 1.4;
            color: #374151;
        }

        .contact-details {
            line-height: 1.5;
        }

        .contact-details > div {
            margin-bottom: 3px;
        }

        .contact-details strong {
            color: #374151;
            font-weight: 600;
        }

        .date-registered {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }

        .date-time {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .no-data {
            text-align: center;
            padding: 60px 40px;
            color: #9ca3af;
            font-style: italic;
            background-color: #f9fafb;
            border-radius: 8px;
            margin: 20px 0;
        }

        .no-data h3 {
            color: #6b7280;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 2px solid #e5e7eb;
            padding-top: 20px;
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
        }

        .footer .generated-info {
            margin-bottom: 5px;
            font-weight: 500;
        }

        .footer .system-info {
            color: #9ca3af;
            font-size: 9px;
        }

        .page-break {
            page-break-before: always;
        }

        .row-number {
            background-color: #e2e8f0;
            color: #38a67c;
            font-weight: bold;
            text-align: center;
            border-radius: 4px;
            padding: 4px 8px;
        }

        @media print {
            body {
                margin: 0;
                padding: 15px;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            .no-print {
                display: none;
            }
            .stat-box::before {
                display: none;
            }
            .data-table th {
                background-color: #38a67c !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
            }
            .data-table thead {
                background-color: #38a67c !important;
            }
        }
    </style>
</head>
<body>
<!-- Header Section -->
<div class="header">
    <h1>LAPORAN DATA PELANGGAN</h1>
    <div class="subtitle">Montir App</div>
</div>

<!-- Data Table -->
@if(isset($data) && count($data) > 0)
    <table class="data-table">
        <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 25%;">Nama Pelanggan</th>
            <th style="width: 30%;">Alamat</th>
            <th style="width: 20%;">Kontak</th>
            <th style="width: 20%;">Tanggal Daftar</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $index => $pelanggan)
            <tr>
                <td style="text-align: center;">
                    <span class="row-number">{{ $index + 1 }}</span>
                </td>
                <td>
                    <div class="bengkel-name">{{ $pelanggan->nama ?? 'N/A' }}</div>
                </td>
                <td class="address">
                    {{ $pelanggan->alamat ?? 'Alamat tidak tersedia' }}
                </td>
                <td class="contact-details">
                    <div><strong>Telp:</strong> {{ $pelanggan->no_telp ?? 'N/A' }}</div>
                    <div><strong>Email:</strong> {{ $pelanggan->email ?? 'N/A' }}</div>
                </td>
                <td>
                    <div class="date-registered">
                        {{ \Carbon\Carbon::parse($pelanggan->created_at)->format('d M Y') }}
                    </div>
                    <div class="date-time">
                        {{ \Carbon\Carbon::parse($pelanggan->created_at)->format('H:i') }} WIB
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <div class="no-data">
        <h3>Tidak Ada Data</h3>
        <p>Tidak ada data bengkel yang tersedia untuk ditampilkan.</p>
    </div>
@endif

<!-- Footer -->
<div class="footer">
    <div class="generated-info">Laporan ini digenerate secara otomatis pada {{ \Carbon\Carbon::now()->format('d F Y, H:i:s') }} WIB</div>
    <div class="system-info">Montir App - {{ \Carbon\Carbon::now()->format('Y') }}</div>
</div>
</body>
</html>
