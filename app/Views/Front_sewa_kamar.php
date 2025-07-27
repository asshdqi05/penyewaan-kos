<?= $this->extend('template/Front_header') ?>
<?= $this->section('content') ?>
<div class="row">

    <div class="col-sm-12">
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
            Penyewa <b>wajib melakukan pelunasan</b> <u>sebelum jam 12:00 siang</u> di tanggal check-in.<br>
            Jika tidak, <b>status sewa akan otomatis dibatalkan</b> dan kamar akan tersedia untuk penyewa lain.
        </div>

        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#modal_add">
                    <span class="fas fa-plus"></span> Tambah Data
                </button>
            </div>

            <div class="card-body">
                <table id="myTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Penyewa</th>
                            <th>Nama Kamar</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Keluar</th>
                            <th>Lama Sewa</th>
                            <th>Total Harga</th>
                            <th>Status Bayar</th>
                            <th>Status</th>
                            <th>
                                <center>Countdown Pelunasan</center>
                            </th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $no = 1;
                        foreach ($data as $d) { ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?>.</td>
                                <td><?= esc($d->nama_penyewa) ?></td>
                                <td><?= esc($d->nama_kamar) ?></td>
                                <td><?= esc(date('d-m-Y', strtotime($d->tanggal_masuk))) ?></td>
                                <td><?= esc(date('d-m-Y', strtotime($d->tanggal_keluar))) ?></td>
                                <td><?= esc($d->lama_sewa . ' Malam') ?></td>
                                <td><?= esc('Rp. ' . number_format($d->total_harga)) ?></td>
                                <td><?= esc($d->status_pembayaran) ?></td>
                                <td><?= esc($d->status) ?></td>
                                <td>
                                    <?php if ($d->status_pembayaran == 'Menunggu pembayaran' || $d->status == 'Booked') : ?>
                                        <span class="countdown" data-deadline="<?= $d->tanggal_masuk ?> 12:00:00"></span>
                                    <?php else : ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($d->status_pembayaran == 'Menunggu pembayaran') { ?>
                                        <a class="btn btn-xs btn-primary" href="javascript:void(0)" onclick="pembayaran('<?= $d->id ?>', '<?= $d->nama_penyewa ?>', '<?= $d->nama_kamar ?>','<?= $d->total_harga ?>', '<?= $d->lama_sewa ?>','<?= $d->id_kamar ?>')">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </a>
                                        <a class="btn btn-xs btn-danger" href="javascript:void(0)" onclick="hapus('<?= $d->id ?>')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php } else if ($d->status_pembayaran == 'Sudah bayar DP') { ?>
                                        <a class="btn btn-xs btn-info" href="<?= site_url('cetak-struk-dp/' . $d->id) ?>" target="_blank">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                    <?php } else if ($d->status_pembayaran == 'Lunas') { ?>
                                        <a class="btn btn-xs btn-success" href="<?= site_url('cetak-struk-dp/' . $d->id) ?>" target="_blank">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modal_add">
    <div class="modal-dialog modal-xl">
        <form method="POST" id="form-add">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data</h4>
                    <button type="button" class="close btn-danger" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body row">
                    <div class="form-group col-md-6">
                        <label>Nama Penyewa</label>
                        <input type="text" name="nama_penyewa" id="nama_penyewa" class="form-control" value="<?= session()->get('nama_penyewa') ?>" readonly required>
                        <input type="hidden" name="id_penyewa" id="id_penyewa" class="form-control" value="<?= session()->get('id_penyewa') ?>" readonly required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Pilih Kamar</label>
                        <div class="row">
                            <button type="button" class="btn btn-info btn-flat col-3" data-toggle="modal" data-target="#pilih_kamar">
                                <span class="fas fa-search"></span> Pilih Kamar
                            </button>
                            <input type="text" name="nama_kamar" id="nama_kamar" class="form-control col-9" readonly required>
                            <input type="hidden" name="id_kamar" id="id_kamar" class="form-control" readonly required>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Fasilitas Kamar</label>
                        <textarea name="fasilitas_kamar" id="fasilitas_kamar" class="form-control" rows="5" readonly required></textarea>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Harga Kamar Permalam</label>
                        <input type="text" name="harga" id="harga-select" class="form-control" readonly required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Tanggal Keluar</label>
                        <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="form-control" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Lama Sewa (Permalam)</label>
                        <input type="number" name="lama_sewa" id="lama_sewa" class="form-control" readonly required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Total</label>
                        <input type="text" name="total" id="total_harga" class="form-control" readonly required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>DP</label>
                        <input type="text" name="dp" id="dp" class="form-control" readonly required>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Pilih Kamar -->
<div class="modal fade" id="pilih_kamar">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pilih Kamar</h4>
                <button type="button" class="close btn-danger" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="card card-white">
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 text-maroon">Ketersediaan Kamar</h4>
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
                                            <div class="card-footer text-center">
                                                <?php if ($k->status == 'Kosong') : ?>
                                                    <a class="btn btn-primary btn-sm select-kamar-btn" data-id="<?= $k->id ?>" data-nama="<?= esc($k->nama_kamar) ?>" data-fasilitas="<?= esc($k->fasilitas) ?>" data-harga="<?= $k->harga ?>">
                                                        Pilih Kamar
                                                    </a>
                                                <?php else : ?>
                                                    <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                        Kamar Tidak Tersedia
                                                    </button>
                                                <?php endif; ?>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_ganti_kamar_add">
    <div class="modal-dialog modal-xl">
        <form method="POST" id="form-ganti-kamar">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ganti Kamar</h4>
                    <button type="button" class="close btn-danger" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body row">
                    <div class="form-group col-md-6">
                        <label>Nama Penyewa</label>
                        <input type="text" name="nama_penyewa" id="nama_penyewa" class="form-control" value="<?= session()->get('nama_penyewa') ?>" readonly required>
                        <input type="hidden" name="id_penyewa" id="id_penyewa" class="form-control" value="<?= session()->get('id_penyewa') ?>" readonly required>
                        <input type="hidden" name="id_sewa" id="id_sewa_kamar_lain" class="form-control" readonly required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Pilih Kamar</label>
                        <div class="row">
                            <button type="button" class="btn btn-info btn-flat col-3" data-toggle="modal" data-target="#pilih_kamar_lain">
                                <span class="fas fa-search"></span> Pilih Kamar
                            </button>
                            <input type="text" name="nama_kamar" id="nama_kamar_lain" class="form-control col-9" readonly required>
                            <input type="hidden" name="id_kamar" id="id_kamar_lain" class="form-control" readonly required>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Fasilitas Kamar</label>
                        <textarea name="fasilitas_kamar" id="fasilitas_kamar_lain" class="form-control" rows="5" readonly required></textarea>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Harga Kamar Permalam</label>
                        <input type="text" name="harga" id="harga-select_lain" class="form-control" readonly required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk_lain" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Tanggal Keluar</label>
                        <input type="date" name="tanggal_keluar" id="tanggal_keluar_lain" class="form-control" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Lama Sewa (Permalam)</label>
                        <input type="number" name="lama_sewa" id="lama_sewa_lain" class="form-control" readonly required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Total</label>
                        <input type="text" name="total" id="total_harga_lain" class="form-control" readonly required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>DP</label>
                        <input type="text" name="dp" id="dp_lain" class="form-control" readonly required>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="pilih_kamar_lain">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pilih Kamar</h4>
                <button type="button" class="close btn-danger" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="card card-white">
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 text-maroon">Ketersediaan Kamar</h4>
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
                                            <div class="card-footer text-center">
                                                <?php if ($k->status == 'Kosong') : ?>
                                                    <a class="btn btn-primary btn-sm select-kamar-lain-btn" data-id="<?= $k->id ?>" data-nama="<?= esc($k->nama_kamar) ?>" data-fasilitas="<?= esc($k->fasilitas) ?>" data-harga="<?= $k->harga ?>">
                                                        Pilih Kamar
                                                    </a>
                                                <?php else : ?>
                                                    <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                        Kamar Tidak Tersedia
                                                    </button>
                                                <?php endif; ?>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPembayaran">
    <div class="modal-dialog">
        <form id="formPembayaran" enctype="multipart/form-data">
            <input type="hidden" name="id_sewa" id="id_sewa">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Pembayaran</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body body-pembayaran">
                    <div class="form-group">
                        <label for="nama_penyewa">Penyewa</label>
                        <input type="text" class="form-control" id="nama_penyewa_pay" readonly>
                    </div>

                    <div class="form-group">
                        <label for="nama_kamar">Kamar</label>
                        <input type="text" class="form-control" id="nama_kamar_pay" readonly>
                        <input type="hidden" class="form-control" name="id_kamar" id="id_kamar_hidden" readonly>
                    </div>
                    <div class="form-group">
                        <label for="lama_sewa">Lama Sewa</label>
                        <input type="text" class="form-control" id="lama_sewa_pay" readonly>
                    </div>
                    <div class="form-group">
                        <label for="total_harga">Total Harga</label>
                        <input type="text" class="form-control" id="total_harga_pay" readonly>
                    </div>

                    <hr>
                    <div class="form-group">
                        <label for="jumlah_pembayaran">Jumlah DP Pembayaran</label>
                        <input type="text" class="form-control" id="jumlah_pembayaran_pay" readonly>
                        <input type="hidden" class="form-control" name="jumlah_pembayaran" id="jumlah_pembayaran_hidden" readonly>

                    </div>


                    <div class="form-group">
                        <label for="metode_pembayaran">Metode Pembayaran</label>
                        <select class="form-control" id="metode_pembayaran" name="metode_pembayaran">
                            <option value="">-- Pilih Metode --</option>
                            <option value="Transfer">Transfer Bank</option>
                            <option value="QRIS">QRIS</option>
                        </select>
                    </div>

                    <div id="info_transfer" class="d-none">
                        <div class="alert alert-info">
                            <strong>Transfer ke:</strong><br>
                            Bank BCA<br>
                            No. Rekening: 1234567890<br>
                            a.n. Kos Buk En
                        </div>
                    </div>

                    <div id="info_qris" class="d-none text-center">
                        <p>Silakan scan QR berikut untuk pembayaran via QRIS:</p>
                        <img src="<?= base_url('assets/') ?>dist/img/qris.jpg" alt="QRIS" class="img-fluid" style="max-width: 200px;">
                    </div>

                    <div class="form-group">
                        <label for="bukti_pembayaran">Upload Bukti Pembayaran</label>
                        <div class="custom-file">
                            <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer footer-pembayaran justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>

                </div>
            </div>
        </form>
    </div>
</div>




<script src="<?= base_url('assets/') ?>plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(() => {
        $('#myTable').DataTable();
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        setInterval(updateCountdown, 1000);
        updateCountdown();

        let today = new Date();

        // Tanggal masuk: besok (H+1)
        let masuk = new Date(today);
        masuk.setDate(today.getDate() + 1);
        let masukStr = masuk.toISOString().split('T')[0];

        // Tanggal keluar: lusa (H+2)
        let keluar = new Date(today);
        keluar.setDate(today.getDate() + 2);
        let keluarStr = keluar.toISOString().split('T')[0];

        $('#tanggal_masuk').attr('min', masukStr);
        $('#tanggal_keluar').attr('min', keluarStr);
        $('#tanggal_masuk_lain').attr('min', masukStr);
        $('#tanggal_keluar_lain').attr('min', keluarStr);



        $(document).on('click', '.select-kamar-btn', function() {
            $('#id_kamar').val($(this).data('id'));
            $('#nama_kamar').val($(this).data('nama'));
            $('#fasilitas_kamar').val($(this).data('fasilitas'));
            $('#harga-select').val($(this).data('harga'));
            $('#pilih_kamar').modal('hide');
        });

        function hitungSewaPerMalam() {
            const masuk = new Date($('#tanggal_masuk').val());
            const keluar = new Date($('#tanggal_keluar').val());
            const harga = parseInt($('#harga-select').val());

            if (!isNaN(masuk) && !isNaN(keluar) && keluar > masuk) {
                let malam = (keluar - masuk) / (1000 * 60 * 60 * 24);

                if (isNaN(malam)) {
                    malam = 0;
                }

                if (malam < 1) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Minimal sewa adalah 1 malam.'
                    });
                    $('#tanggal_keluar').val('');
                    $('#lama_sewa').val('');
                    $('#total_harga').val('');
                    return;
                }

                $('#lama_sewa').val(malam);
                const total = malam * harga;
                $('#total_harga').val('Rp ' + total.toLocaleString('id-ID'));
                $('#dp').val('Rp ' + (total / 2).toLocaleString('id-ID'));
            } else {
                $('#lama_sewa').val('');
                $('#total_harga').val('');
            }
        }

        $('#tanggal_masuk, #tanggal_keluar').on('change', hitungSewaPerMalam);

        function hitungSewaPerMalamLain() {
            const masuk = new Date($('#tanggal_masuk_lain').val());
            const keluar = new Date($('#tanggal_keluar_lain').val());
            const harga = parseInt($('#harga-select_lain').val());

            if (!isNaN(masuk) && !isNaN(keluar) && keluar > masuk) {
                let malam = (keluar - masuk) / (1000 * 60 * 60 * 24);

                if (isNaN(malam)) {
                    malam = 0;
                }

                if (malam < 1) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Minimal sewa adalah 1 malam.'
                    });
                    $('#tanggal_keluar_lain').val('');
                    $('#lama_sewa_lain').val('');
                    $('#total_harga_lain').val('');
                    return;
                }

                $('#lama_sewa_lain').val(malam);
                const total = malam * harga;
                $('#total_harga_lain').val('Rp ' + total.toLocaleString('id-ID'));
                $('#dp_lain').val('Rp ' + (total / 2).toLocaleString('id-ID'));
            } else {
                $('#lama_sewa_lain').val('');
                $('#total_harga_lain').val('');
            }
        }

        $('#tanggal_masuk_lain, #tanggal_keluar_lain').on('change', hitungSewaPerMalamLain);

        $("#form-add").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?= site_url('save-sewa-kamar-penyewa') ?>",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status == 'success') {
                        $('#modal_add').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        location.reload();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        });
                    }
                },
                error: function() {
                    alert('Error saving data!');
                }
            });
        });

        $("#form-ganti-kamar").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?= site_url('edit-sewa-kamar-penyewa') ?>",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status == 'success') {
                        $('#modal_ganti_kamar').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        });
                    }
                },
                error: function() {
                    alert('Error saving data!');
                }
            });
        });

        $('#metode_pembayaran').on('change', function() {
            const metode = $(this).val();
            $('#info_transfer').addClass('d-none');
            $('#info_qris').addClass('d-none');

            if (metode === 'Transfer') {
                $('#info_transfer').removeClass('d-none');
            } else if (metode === 'QRIS') {
                $('#info_qris').removeClass('d-none');
            }
        });

        $('#formPembayaran').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            formData.append('jumlah_pembayaran', $('#jumlah_pembayaran_hidden').val());

            $.ajax({
                type: "POST",
                url: "<?= site_url('save-pembayaran') ?>",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(response) {
                    if (response.status == 'success') {
                        $('#modalPembayaran').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else if (response.status == 'error') {
                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        });
                    } else if (response.status == 'pembatalan') {
                        Toast.fire({
                            icon: 'info',
                            title: response.message
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                },
                error: function() {
                    alert('Error saving payment!');
                }
            });
        });

        $(document).on('click', '#btn_pilih_kamar_lain', function() {
            let id_sewa = $(this).data('id_sewa');
            $('#id_sewa_kamar_lain').val(id_sewa);

            $('#modalPembayaran').on('hidden.bs.modal', function() {
                $('#pilih_kamar_lain').modal('show');
                $(this).off('hidden.bs.modal');
            });

            $('#modalPembayaran').modal('hide');
        });


        $(document).on('click', '.select-kamar-lain-btn', function() {
            $('#id_kamar_lain').val($(this).data('id'));
            $('#nama_kamar_lain').val($(this).data('nama'));
            $('#fasilitas_kamar_lain').val($(this).data('fasilitas'));
            $('#harga-select_lain').val($(this).data('harga'));
            $('#pilih_kamar_lain').on('hidden.bs.modal', function() {
                $('#modal_ganti_kamar_add').modal('show');
                $(this).off('hidden.bs.modal');
            });
            $('#pilih_kamar_lain').modal('hide');
        });

    });

    function pembayaran(id, nama_penyewa, nama_kamar, total_harga, lama_sewa, id_kamar) {
        $('.footer-pembayaran').empty();
        $.ajax({
            url: '<?= base_url('cekKamarKosong') ?>',
            type: 'POST',
            data: {
                id_kamar: id_kamar
            },
            dataType: 'json',
            success: function(response) {
                let dp = total_harga / 2;

                $('#id_sewa').val(id);
                $('#nama_penyewa_pay').val(nama_penyewa);
                $('#nama_kamar_pay').val(nama_kamar);
                $('#id_kamar_hidden').val(id_kamar);
                $('#total_harga_pay').val('Rp ' + total_harga.toLocaleString());
                $('#lama_sewa_pay').val(lama_sewa + ' hari');
                $('#jumlah_pembayaran_pay').val('Rp ' + dp.toLocaleString());
                $('#jumlah_pembayaran_hidden').val(dp);

                $('#info_pembatalan').remove();
                $('#btn_submit_pembayaran').remove();
                $('#btn_batal_pesanan').remove();

                if (response.kamar_kosong) {

                    $('.footer-pembayaran').append(`
                    <button type="submit" class="btn btn-primary" id="btn_submit_pembayaran">Kirim Pembayaran</button>
                `);
                } else {

                    $('.footer-pembayaran').prepend(`
                    <div class="alert alert-danger" id="info_pembatalan">
                        Kamar <strong>${nama_kamar}</strong> Tidak tersedia. Anda bisa membatalkan pemesanan Atau Memilih kamar lain yang tersedia.
                    </div>
                `);
                    $('.footer-pembayaran').append(`
                    <button type="button" class="btn btn-primary" data-id_sewa="${id}" id="btn_pilih_kamar_lain">Pilih Kamar Lain</button>
                    <button type="submit" class="btn btn-danger" id="btn_batal_pesanan">Batalkan Pesanan</button>
                `);

                    $('.body-pembayaran').hide();

                }

                $('#modalPembayaran').modal('show');
            },
            error: function(xhr, status, error) {
                alert('Gagal memeriksa status kamar. Silakan coba lagi.');
            }
        });
    }


    function hapus(id) {
        Swal.fire({
            title: 'Hapus Data Sewa Kamar',
            text: "Apakah Anda yakin ingin menghapus data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('delete-sewa-kamar') ?>",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == 'success') {
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                            location.reload();
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: response.message
                            });
                        }
                    },
                    error: function() {
                        alert('Error deleting data!');
                    }
                });
            }
        });
    }
</script>
<script>
    function updateCountdown() {
        const countdowns = document.querySelectorAll('.countdown');

        countdowns.forEach(function(el) {
            const deadlineStr = el.getAttribute('data-deadline');
            const deadline = new Date(deadlineStr);
            const now = new Date();

            const diff = deadline - now;

            if (diff > 0) {
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                el.textContent = `${hours}j ${minutes}m ${seconds}d`;
            } else {
                el.textContent = 'Batas waktu habis';
                el.classList.add('text-danger');
            }
        });
    }
</script>

<?= $this->endSection() ?>