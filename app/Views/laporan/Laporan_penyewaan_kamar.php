<html>

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            color: black;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 6px;
            text-align: left;
        }

        .no-border {
            border: none;
        }

        .header-table td {
            border: none;
            padding: 0;
        }

        .header-center {
            text-align: center;
            line-height: 1.5;
        }

        .signature {
            width: 50%;
            display: inline-block;
            vertical-align: top;
        }

        .signature p {
            margin: 0;
        }

        .signature-space {
            height: 60px;
        }
    </style>
</head>

<body onload="window.print();">
    <div align="center" style="width: 96%; margin: auto;">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td style="width: 15%; text-align: center;">
                    <img src="<?= base_url('assets') ?>/dist/img/logobuken.png" width="70">
                </td>
                <td class="header-center" style="width: 70%;">
                    <span style="font-size: 15pt; font-weight: bold;">Penginapan / Kos Buk En</span><br>
                    <span style="font-size: 10pt;">Jl. Jati Rawang, Sawahan Kec. Padang Timur Kota Padang, Sumatera Barat 25121</span><br>
                </td>
                <td style="width: 15%;"></td>
            </tr>
        </table>

        <hr style="border: 1px solid black; margin-top: 10px;">

        <!-- Judul -->
        <h3 style="margin: 10px 0;"><?= $title ?></h3>
        <h4 style="color: black; margin:2;">Tanggal <?= date('d M Y', strtotime($tgl_awal)) ?> s/d <?= date('d M Y', strtotime($tgl_akhir)) ?></h4>


        <table style="border-collapse: collapse; width: 100%;" border="1">
            <thead>
                <tr>
                    <th style="text-align: center;">No</th>
                    <th>Nama Penyewa</th>
                    <th>Nama Kamar</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Keluar</th>
                    <th>Lama Sewa</th>
                    <th>Status</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 0;
                foreach ($data as $d) :
                    $no++;
                ?>
                    <tr>
                        <td width="50px" style="text-align: center;"><?php echo $no . '.'; ?></td>
                        <td><?= esc($d->nama_penyewa) ?></td>
                        <td><?= esc($d->nama_kamar) ?></td>
                        <td><?= esc(date('d-m-Y', strtotime($d->tanggal_masuk))) ?></td>
                        <td><?= esc(date('d-m-Y', strtotime($d->tanggal_keluar))) ?></td>
                        <td><?= esc($d->lama_sewa . ' Malam') ?></td>
                        <td><?= esc($d->status) ?></td>
                        <td><?= esc('Rp. ' . number_format($d->total_harga)) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7" style="text-align: center;">Total</th>
                    <th><?= esc('Rp. ' . number_format(array_sum(array_column($data, 'total_harga')))) ?></th>
                </tr>
        </table>

        <br><br>

        <div style="width: 100%; display: flex; justify-content: space-between; margin-top: 40px;">
            <div style="text-align: left; padding-left: 50px;">

            </div>

            <div style="text-align: right; padding-right: 50px;">
                <p>Padang, <?= date('d F Y') ?></p>
                <p>Pimpinan</p>
                <div style="height: 60px;"></div>
                <p>(....................)</p>
            </div>
        </div>
    </div>
</body>

</html>