<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran Sewa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 700px;
            margin: auto;
            padding: 20px;
            border: 1px solid #000;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            justify-content: center;
            gap: 15px;
        }

        .header img {
            width: 80px;
            height: auto;
        }

        .header .title {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .header .title h2,
        .header .title h4 {
            margin: 0;
            text-align: left;
        }

        .section {
            margin-bottom: 15px;
        }

        .section table {
            width: 100%;
        }

        .section td {
            padding: 5px;
        }

        .right {
            text-align: right;
        }

        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature .box {
            text-align: center;
            width: 200px;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <img src="<?= base_url('assets') ?>/dist/img/logobuken.png" alt="Logo Penginapan">
        <div class="title">
            <h2>BUKTI PEMBAYARAN SEWA</h2>
            <h4>Penginapan / Kos Buk En</h4>
        </div>
    </div>


    <div class="section">
        <table>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>: <?= $sewa->tanggal_masuk ?></td>
            </tr>
            <tr>
                <td><strong>Nama Penyewa</strong></td>
                <td>: <?= $penyewa->nama_penyewa ?></td>
            </tr>
            <tr>
                <td><strong>Kamar</strong></td>
                <td>: <?= $kamar->nama_kamar ?></td>
            </tr>
            <tr>
                <td><strong>Durasi</strong></td>
                <td>: <?= $sewa->lama_sewa ?> Malam</td>
            </tr>
            <tr>
                <td><strong>Total Harga</strong></td>
                <td>: Rp <?= number_format($sewa->total_harga, 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Uraian</th>
                <th>Metode Pembayaran</th>
                <th class="right"><strong>Total</strong></th>
            </tr>
            <?php foreach ($pembayaran as $d) : ?>
                <tr>
                    <td><?= $d->jenis_pembayaran ?></td>
                    <td><?= $d->metode_pembayaran ?></td>
                    <td class="right"><strong>Rp <?= number_format($d->jumlah, 0, ',', '.') ?></strong></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2" class="right"><strong>Total Pembayaran</strong></td>
                <td class="right"><strong>Rp <?= number_format(array_sum(array_column($pembayaran, 'jumlah')), 0, ',', '.') ?></strong></td>
            </tr>
        </table>

    </div>

    <div class="section">
        <p><strong>Status:</strong> <?= $sewa->status_pembayaran ?></p>
    </div>

</body>

</html>