<html>

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
</head>

<body onload="window.print();">
    <div align="center">
        <table style="border-collapse: collapse; width: 96%" border="1">
            <tr>
                <td align="center">
                    <table style="border-collapse: collapse; width: 100%;" border="0">
                        <tr>
                            <td align="center">
                                <div style="display: flex; align-items: center;">
                                    <div style="flex: 1;">
                                        <img src="<?= base_url('assets') ?>/dist/img/logobuken.png" width="120">
                                    </div>
                                    <div style="text-align: center; justify-content: center;flex:3;">
                                        <span style="font-size: 20pt; font-weight: bold; color: black;">Penginapan / Kos Buk En</span><br>
                                    </div>
                                    <div style="flex: 1;">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>


            <tr>
                <td align="center">
                    <h3 style="font-weight: bold; color: black; margin:1;"><?= $title ?></h3>
                    <h6 style="color: black; margin:2;">Tanggal <?= date('d M Y', strtotime($tgl_awal)) ?> s/d <?= date('d M Y', strtotime($tgl_akhir)) ?></h6>

                    <table style="border-collapse: collapse; width: 90%;" border="1">
                        <thead>
                            <tr>
                                <th style="text-align: center;">No</th>
                                <th>Tanggal</th>
                                <th>Nama Penyewa</th>
                                <th>Nama Kamar</th>
                                <th>Jenis Pembayaran</th>
                                <th>Metode Pembayaran</th>
                                <th>Jumlah</th>
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
                                    <td><?= esc(date('d-m-Y', strtotime($d->tanggal_pembayaran))) ?></td>
                                    <td><?= esc($d->nama_penyewa) ?></td>
                                    <td><?= esc($d->nama_kamar) ?></td>
                                    <td><?= esc($d->jenis_pembayaran) ?></td>
                                    <td><?= esc($d->metode_pembayaran) ?></td>
                                    <td><?= esc('Rp. ' . number_format($d->jumlah)) ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" style="text-align: right;">Total:</td>
                                <td><?= esc('Rp. ' . number_format(array_sum(array_column($data, 'jumlah')))) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                    <br>
                </td>
            </tr>
            <tr>
                <td style="text-align: right; padding-right: 50px;">
                    <br>
                    <br>
                    <p>Padang, <?= date('d F Y') ?></p>
                    <p>Owner</p>
                    <br>
                    <br>
                    <br>
                    <p>(....................)</p>
                    <br>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>