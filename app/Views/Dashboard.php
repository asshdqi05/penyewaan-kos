<?= $this->extend('template/Header') ?>
<?= $this->section('content') ?>



<div class="col-md-12">
    <div class="card card-white">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0 text-maroon">Ketersediaan Kamar</h4>
                <small class="text-muted">Pantau status kamar terkini</small>
            </div>
            <div class="ml-auto d-flex flex-wrap gap-1">
                <span class="badge badge-primary mr-1">Total: <?= $total_kamar ?></span>
                <span class="badge badge-success mr-1">Tersedia: <?= $total_kamar_kosong ?></span>
                <span class="badge badge-danger mr-1">Terisi: <?= $total_kamar_terisi ?></span>
                <span class="badge badge-warning">Dipesan: <?= $total_kamar_dipesan ?></span>
            </div>
        </div>


        <!-- /.card-header -->
        <div class="card-body">

            <div class="row">
                <?php
                $warnaStatus = [
                    'Kosong'  => 'badge-success',
                    'Dipesan' => 'badge-warning',
                    'Terisi'  => 'badge-danger'
                ];
                ?>

                <?php foreach ($kamar as $k) : ?>
                    <div class="col-md-3 mb-4">
                        <div class="card room-card">
                            <div class="room-img-container">
                                <?php if ($k->foto && file_exists(FCPATH . 'uploads/kamar/' . $k->foto)) : ?>
                                    <img src="<?= base_url('uploads/kamar/' . $k->foto) ?>" alt="Foto kamar">
                                <?php else : ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 100%;">
                                        <span>Tidak ada foto</span>
                                    </div>
                                <?php endif; ?>
                                <span class="room-status-badge badge <?= $warnaStatus[$k->status] ?? 'badge-secondary' ?>">
                                    <?= esc($k->status) == 'Kosong' ? 'Tersedia' : esc($k->status) ?>
                                </span>
                            </div>
                            <div class="room-info">
                                <div class="room-title"><?= esc($k->nama_kamar) ?></div>
                                <div><strong>Fasilitas:</strong></div>
                                <div style="white-space: pre-line;"><?= esc($k->fasilitas) ?></div>
                                <div class="room-price">Harga: Rp <?= number_format($k->harga, 0, ',', '.') ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>


        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>

<?= $this->endSection() ?>