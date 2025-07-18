<?= $this->extend('template/Front_header') ?>
<?= $this->section('content') ?>

<div class="col-md-12">
    <?php if (session()->get('logged_in_penyewa')) : ?>

        <div class="card shadow-sm border-0 mb-4 bg-maroon">
            <div class="card-body">
                <h5 class="card-title text-white mb-1">Selamat Datang, <strong><?= session()->get('nama_penyewa') ?></strong>!</h5>
            </div>
        </div>
    <?php endif; ?>

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
                            <div class="card-footer">
                                <?php if (session()->get('logged_in_penyewa')) : ?>
                                    <?php if ($k->status == 'Kosong') : ?>
                                        <a class="btn bg-maroon btn-sm btn-block select-kamar-btn" data-id="<?= $k->id ?>" data-nama="<?= esc($k->nama_kamar) ?>" data-fasilitas="<?= esc($k->fasilitas) ?>" data-harga="<?= $k->harga ?>">
                                            Pesan Kamar
                                        </a>
                                    <?php else : ?>
                                        <button type="button" class="btn btn-secondary btn-block btn-sm" disabled>
                                            Kamar Tidak Tersedia
                                        </button>
                                    <?php endif; ?>

                                <?php else : ?>
                                    <?php if ($k->status == 'Kosong') : ?>
                                        <a class="btn bg-maroon btn-sm btn-block" data-toggle="modal" data-target="#modal_login">
                                            Pesan Kamar
                                        </a>
                                    <?php else : ?>
                                        <button type="button" class="btn btn-secondary btn-block btn-sm" disabled>
                                            Kamar Tidak Tersedia
                                        </button>
                                    <?php endif; ?>
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


<div class="modal fade" id="modal_login" tabindex="-1" aria-labelledby="modalLoginLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title" id="modalLoginLabel"><i class="fas fa-sign-in-alt"></i> Login</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_login" method="post">
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img src="<?= base_url('assets') ?>/dist/img/logobuken.png" width="150" alt="Logo">
                        <p class="mt-2 text-muted">Silakan login terlebih dahulu</p>
                    </div>
                    <div class="form-group">
                        <label for="username"><i class="fas fa-user"></i> Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required>
                    </div>
                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn bg-maroon w-100"><i class="fas fa-sign-in-alt"></i> Login</button>
                    </div>
                </div>
                <div class="modal-footer d-flex flex-column align-items-start">
                    <a data-toggle="modal" data-target="#modal_register" class="btn btn-link p-0 mb-1">Belum punya akun? <b>Register</b></a>
                    <a href="<?= site_url('Login') ?>" class="btn btn-link p-0 text-danger">Login sebagai Admin</a>
                    <button type="button" class="btn btn-secondary btn-sm mt-2 align-self-end" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_register" tabindex="-1" aria-labelledby="modalRegisterLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title" id="modalRegisterLabel"><i class="fas fa-user-plus"></i> Registrasi Penyewa</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" id="form_register" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_penyewa">Nama</label>
                        <input type="text" class="form-control" name="nama_penyewa" required>
                    </div>
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input type="text" class="form-control" name="nik" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" name="alamat" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="nohp">No HP</label>
                        <input type="text" class="form-control" name="nohp" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select class="form-control" name="jenis_kelamin" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn bg-maroon"><i class="fas fa-save"></i> Daftar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>


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
                        <label>Kamar</label>
                        <div class="row">
                            <input type="text" name="nama_kamar" id="nama_kamar" class="form-control col-12" readonly required>
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


<script src="<?= base_url('assets/') ?>plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function() {

        $('#form_register').submit(function(e) {
            // Validasi form sebelum mengirim
            var isValid = true;
            $(this).find('input, select, textarea').each(function() {
                if ($(this).prop('required') && !$(this).val()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            e.preventDefault();
            if (!isValid) {
                Toast.fire({
                    icon: 'error',
                    title: 'Tolong lengkapi semua field yang wajib diisi.'
                });
                return;
            }

            $.ajax({
                url: '<?= site_url('Registrasi') ?>',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {

                    if (response.status === 'error') {
                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        });
                        return;
                    } else {
                        $('#modal_register').modal('hide');
                        $('#form_register')[0].reset();
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan. Silakan coba lagi.'
                    });
                }
            });
        });

        $('#form_login').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '<?= site_url('Login-Penyewa') ?>',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'error') {
                        Toast.fire({
                            icon: 'error',
                            title: 'Gagal login.',
                            text: response.message
                        });
                        return;
                    } else {
                        $('#modal_login').modal('hide');
                        $('#form_login')[0].reset();
                        Toast.fire({
                            icon: 'success',
                            title: 'Login berhasil. Selamat datang!'
                        });
                        location.reload();
                    }
                },
                error: function(xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan. Silakan coba lagi.'
                    });
                }
            });
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




        $('#tanggal_masuk, #tanggal_keluar').on('change', hitungSewaPerMalam);

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
                        setTimeout(() => {
                            window.location.href = '<?= base_url('sewa-kamar-penyewa') ?>';
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

        $(document).on('click', '.select-kamar-btn', function() {
            $('#id_kamar').val($(this).data('id'));
            $('#nama_kamar').val($(this).data('nama'));
            $('#fasilitas_kamar').val($(this).data('fasilitas'));
            $('#harga-select').val($(this).data('harga'));
            $('#modal_add').modal('show');
        });

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
</script>
<?= $this->endSection() ?>