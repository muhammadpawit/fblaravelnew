<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dokumen Bertanda Tangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f766e;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }
        .header h1 {
            color: #0f766e;
            margin: 0;
            text-transform: uppercase;
        }
        .content {
            margin-bottom: 60px;
        }
        .signature-section {
            width: 100%;
            margin-top: 80px;
        }
        .signature-box {
            float: right;
            text-align: center;
            width: 250px;
        }
        .signature-img {
            max-width: 250px;
            max-height: 120px;
            display: block;
            margin: 0 auto;
            border-bottom: 1px solid #333;
        }
        .name {
            font-weight: bold;
            margin-top: 10px;
        }
        .date {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Garment ERP</h1>
        <p>Dokumen Persetujuan Resmi</p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini:</p>
        <table>
            <tr>
                <td width="120"><strong>Nama</strong></td>
                <td>: {{ $name }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>: {{ $date }}</td>
            </tr>
        </table>

        <p style="margin-top: 30px;">
            Menyatakan dengan sadar dan tanpa paksaan dari pihak manapun bahwa dokumen ini telah dibaca, dipahami, dan disetujui. Tanda tangan yang tertera di bawah ini merupakan bukti sah persetujuan atas segala ketentuan yang berlaku dalam sistem Garment ERP.
        </p>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <div class="date">Ditandatangani pada {{ $date }}</div>
            <img src="{{ $signature }}" class="signature-img" alt="Tanda Tangan">
            <div class="name">{{ $name }}</div>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
