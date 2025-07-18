<?= $this->extend('template/Header') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-sm-12">
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
                                <td class="text-center">
                                    <?php if ($d->status == 'Check-in') { ?>
                                        <a class="btn btn-xs btn-danger" href="javaScript:void(0)" onclick="checkout('<?= $d->id ?>', '<?= esc($d->nama_penyewa) ?>','<?= esc($d->nama_kamar) ?>')">
                                            <i class="fas fa-sign-out-alt"></i>
                                            Check out
                                        </a>
                                        <a class="btn btn-xs btn-info" href="<?= site_url('cetak-struk/' . $d->id) ?>" target="_blank">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                    <?php } else if ($d->status == 'Check-out') { ?>
                                        <a class="btn btn-xs btn-info" href="<?= site_url('cetak-struk/' . $d->id) ?>" target="_blank">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                    <?php } else if ($d->status == 'Booked') { ?>
                                        <a class="btn btn-xs btn-primary" href="javascript:void(0)" onclick="pelunasan('<?= $d->id ?>', '<?= esc($d->nama_penyewa) ?>','<?= esc($d->nama_kamar) ?>', '<?= esc($d->total_harga) ?>', '<?= esc($d->id_kamar) ?>')">
                                            <i class="fas fa-money-bill-wave"></i> Pelunasan
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
                        <select name="id_penyewa" id="id_penyewa" class="form-control select2" required>
                            <option value="">- Pilih -</option>
                            <?php foreach ($penyewa as $p) : ?>
                                <option value="<?= $p->id ?>"><?= esc($p->nama_penyewa) ?></option>
                            <?php endforeach; ?>
                        </select>
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
                        <label>Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-control" required>
                            <option value="">- Pilih -</option>
                            <option value="Cash">Cash</option>
                            <option value="Transfer">Transfer</option>
                            <option value="QRIS">QRIS</option>
                        </select>
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

<div class="modal fade" id="modal_pelunasan">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="form-pelunasan">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Pelunasan Sewa Kamar</h4>
                    <button type="button" class="close btn-danger" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="nama_penyewa" id="nama_penyewa">
                        <input type="hidden" name="nama_kamar" id="nama_kamar">
                        <input type="hidden" name="id_kamar" id="id_kamar_pelunasan">
                        <div class="form-group col-6">
                            <label>Nama Penyewa</label>
                            <input type="text" name="nama_penyewa" id="nama_penyewa_pelunasan" class="form-control" readonly required>
                        </div>
                        <div class="form-group col-6">
                            <label>Nama Kamar</label>
                            <input type="text" name="nama_kamar" id="nama_kamar_pelunasan" class="form-control" readonly required>
                        </div>
                        <div class="form-group col-6">
                            <label>Total Harga</label>
                            <input type="text" name="total_harga" id="total_harga_pelunasan" class="form-control" readonly required>
                        </div>
                        <div class="form-group col-6">
                            <label>Sudah Dibayar</label>
                            <input type="number" name="jumlah_sudah_bayar" id="jumlah_sudah_bayar" class="form-control" readonly required>
                        </div>
                        <div class="form-group col-12">
                            <img id="bukti_pembayaran_preview" src="" alt="Bukti Pembayaran" class="img-fluid mt-2" style="max-height: 300px; display: none;">
                        </div>

                        <div class="form-group col-6">
                            <label>Sisa Pembayaran</label>
                            <input type="number" name="jumlah_pembayaran" id="sisa_pembayaran_pelunasan" class="form-control" readonly required>
                        </div>
                        <div class="form-group col-6">
                            <label>Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="form-control" required>
                                <option value="">- Pilih -</option>
                                <option value="Cash">Cash</option>
                                <option value="Transfer">Transfer</option>
                                <option value="QRIS">QRIS</option>
                            </select>
                        </div>
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

<script src="<?= base_url('assets/') ?>plugins/jquery/jquery.min.js"></script>
<script>
    var base_url = "<?= base_url() ?>";
</script>
<script>
    $(document).ready(() => {
        $('#myTable').DataTable();
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        let today = new Date();
        let yyyy = today.getFullYear();
        let mm = String(today.getMonth() + 1).padStart(2, '0');
        let dd = String(today.getDate()).padStart(2, '0');
        let todayStr = `${yyyy}-${mm}-${dd}`;

        // Hitung tanggal besok
        let tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        let yyyy2 = tomorrow.getFullYear();
        let mm2 = String(tomorrow.getMonth() + 1).padStart(2, '0');
        let dd2 = String(tomorrow.getDate()).padStart(2, '0');
        let tomorrowStr = `${yyyy2}-${mm2}-${dd2}`;

        $('#tanggal_masuk').attr('min', todayStr);
        $('#tanggal_keluar').attr('min', tomorrowStr);


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
            } else {
                $('#lama_sewa').val('');
                $('#total_harga').val('');
            }
        }

        $('#tanggal_masuk, #tanggal_keluar').on('change', hitungSewaPerMalam);

        $("#form-add").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?= site_url('save-sewa-kamar') ?>",
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
    });

    $("#form-pelunasan").submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?= site_url('save-pelunasan') ?>",
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


    function pelunasan(id, nama, kamar, total, id_kamar) {
        $('#id').val(id);
        $('#nama_penyewa_pelunasan').val(nama);
        $('#nama_kamar_pelunasan').val(kamar);
        $('#id_kamar_pelunasan').val(id_kamar);
        $('#total_harga_pelunasan').val('Rp ' + total.toLocaleString('id-ID'));
        $('#sisa_pembayaran_pelunasan').val(total / 2);
        $('#jumlah_sudah_bayar').val(total / 2);

        $.ajax({
            url: base_url + 'getBukti/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.bukti) {
                    $('#bukti_pembayaran_preview')
                        .attr('src', base_url + 'uploads/bukti_pembayaran/' + response.bukti)
                        .show();
                } else {
                    $('#bukti_pembayaran_preview').hide();
                }

                $('#modal_pelunasan').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Gagal mengambil bukti:', error);
                $('#bukti_pembayaran_preview').hide();
                $('#modal_pelunasan').modal('show');
            }
        });
    }


    function checkout(id, nama, kamar) {
        Swal.fire({
            title: 'Checkout',
            text: `Yakin ingin checkout penyewa  ${nama} dari kamar : ${kamar}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Checkout!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= site_url('checkout') ?>",
                    type: "POST",
                    data: {
                        id: id
                    },
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
                        Toast.fire({
                            icon: 'error',
                            title: 'Terjadi kesalahan saat checkout.'
                        });
                    }
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>