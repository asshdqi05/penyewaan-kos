<?= $this->extend('template/Header') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-sm-12">
        <div class="card card-maroon card">
            <div class="card-body">
                <div class="row no-gutters">

                    <div class="col-md-6">
                        <form method="POST" action="<?= site_url('laporan-kamar') ?>" target="_blank">
                            <table>
                                <tr>
                                    <div class="col-md">
                                        <div class="card card-maroon card-outline">
                                            <div class="card-header">
                                                <div class="card-title">Laporan data Kamar</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="col-xs">
                                                    <button type="submit" class="btn btn-block bg-maroon"><i class="fa fa-print"></i> Cetak</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            </table>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <form method="POST" action="<?= site_url('laporan-penyewa') ?>" target="_blank">
                            <table>
                                <tr>
                                    <div class="col-md">
                                        <div class="card card-maroon card-outline">
                                            <div class="card-header">
                                                <div class="card-title">Laporan data Penyewa</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="col-xs">
                                                    <button type="submit" class="btn btn-block bg-maroon"><i class="fa fa-print"></i> Cetak</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            </table>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <form method="POST" action="<?= site_url('laporan-penyewaan-kamar') ?>" target="_blank">
                            <table>
                                <tr>
                                    <div class="col-md">
                                        <div class="card card-maroon card-outline" style="height: 18.2rem;">
                                            <div class="card-header">
                                                <div class="card-title">Laporan Penyewaan Kamar</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Tanggal Awal</label>
                                                    <input type="date" name="tgl_awal" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal Akhir</label>
                                                    <input type="date" name="tgl_akhir" class="form-control" required>
                                                </div>
                                                <div class="col-xs">
                                                    <button type="submit" class="btn btn-block bg-maroon"><i class="fa fa-print"></i> Cetak</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            </table>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <form method="POST" action="<?= site_url('laporan-pembayaran') ?>" target="_blank">
                            <table>
                                <tr>
                                    <div class="col-md">
                                        <div class="card card-maroon card-outline" style="height: 18.2rem;">
                                            <div class="card-header">
                                                <div class="card-title">Laporan Pembayaran</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Tanggal Awal</label>
                                                    <input type="date" name="tgl_awal" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal Akhir</label>
                                                    <input type="date" name="tgl_akhir" class="form-control" required>
                                                </div>
                                                <div class="col-xs">
                                                    <button type="submit" class="btn btn-block bg-maroon"><i class="fa fa-print"></i> Cetak</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>