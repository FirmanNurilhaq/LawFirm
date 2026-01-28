<!DOCTYPE html>
<html lang="id">

<head>
    <title>Laporan_<?= date('Y-m-d') ?></title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            padding: 40px;
        }

        .header {
            text-align: center;
            border-bottom: 3px double black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <button onclick="window.print()" class="no-print" style="margin-bottom: 20px; padding: 10px 20px; cursor: pointer;">Cetak Sekarang</button>

    <div class="header">
        <h1>Firma Hukum Keadilan & Rekan</h1>
        <p>Jl. Keadilan No. 99, Jakarta Selatan | Telp: (021) 555-9999</p>
        <p>Email: official@firmahukum.com | Website: www.firmahukum.com</p>
    </div>

    <h3 style="text-align: center;">LAPORAN KONSULTASI HUKUM</h3>
    <p>Periode: <?= date('d F Y', strtotime($tgl_awal)) ?> s/d <?= date('d F Y', strtotime($tgl_akhir)) ?></p>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 20%">Klien</th>
                <th style="width: 25%">Perkara</th>
                <th style="width: 20%">Lawyer</th>
                <th style="width: 15%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($laporan as $row):
            ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td><?= date('d/m/Y', strtotime($row['tanggal_usulan'])) ?></td>
                    <td>
                        <b><?= $row['nama_klien'] ?></b><br>
                        <?= $row['no_telp'] ?? '-' ?>
                    </td>
                    <td>
                        <?= $row['jenis_perkara'] ?><br>
                        <small><i><?= ucfirst($row['tipe_konsultasi']) ?></i></small>
                    </td>
                    <td><?= $row['nama_lawyer'] ?? 'Belum Ditentukan' ?></td>
                    <td><?= strtoupper($row['status']) ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($laporan)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data pada periode ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Jakarta, <?= date('d F Y') ?></p>
        <br><br><br>
        <p><b>Sekretaris Admin</b></p>
    </div>

</body>

</html>