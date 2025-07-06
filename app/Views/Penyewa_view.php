<?= $this->extend('template/Header') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#modal_add">
                    <span class="fas fa-plus"></span> Tambah Penyewa
                </button>
            </div>

            <div class="card-body">
                <table id="myTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Alamat</th>
                            <th>No HP</th>
                            <th>Jenis Kelamin</th>
                            <th>Jenis Registrasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $no = 1;
                        foreach ($data as $d) { ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?>.</td>
                                <td><?= esc($d->nama_penyewa) ?></td>
                                <td><?= esc($d->nik) ?></td>
                                <td><?= esc($d->alamat) ?></td>
                                <td><?= esc($d->nohp) ?></td>
                                <td><?= esc(ucfirst($d->jenis_kelamin)) ?></td>
                                <td><?= esc($d->jenis_registrasi) ?></td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-success edit-btn" href="javascript:void(0)" data-id="<?= $d->id ?>" data-nama="<?= esc($d->nama_penyewa) ?>" data-nik="<?= esc($d->nik) ?>" data-alamat="<?= esc($d->alamat) ?>" data-nohp="<?= esc($d->nohp) ?>" data-jk="<?= $d->jenis_kelamin ?>" data-username="<?= esc($d->username) ?>" data-jenis="<?= $d->jenis_registrasi ?>" data-status="<?= $d->is_active ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="hapus('<?= $d->id ?>','<?= $d->nama_penyewa ?>')">
                                        <i class="fas fa-trash"></i>
                                    </a>
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
        <form method="POST" action="<?= site_url('save_penyewa') ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Penyewa</h4>
                    <button type="button" class="close btn-danger" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body row">
                    <div class="form-group col-md-6">
                        <label>Nama Penyewa</label>
                        <input type="text" name="nama_penyewa" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>NIK</label>
                        <input type="text" name="nik" class="form-control" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label>No HP</label>
                        <input type="text" name="nohp" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">- Pilih -</option>
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
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

<!-- Modal Edit -->
<div class="modal fade" id="edit_data">
    <div class="modal-dialog modal-xl">
        <form method="POST" action="<?= site_url('edit_penyewa') ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Penyewa</h4>
                    <button type="button" class="close btn-danger" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body row">
                    <div class="form-group col-md-6">
                        <label>Nama Penyewa</label>
                        <input type="text" name="nama_penyewa" id="nama_penyewa" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>NIK</label>
                        <input type="text" name="nik" id="nik" class="form-control" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label>No HP</label>
                        <input type="text" name="nohp" id="nohp" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                            <option value="">- Pilih -</option>
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                    </div>

                    <input type="hidden" name="id" id="id_penyewa">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Modal Hapus -->
<div class="modal fade" id="hapus_data">
    <div class="modal-dialog">
        <form method="POST" action="<?= site_url('delete_penyewa') ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Penyewa</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="hid">
                    Yakin ingin menghapus <strong><span id="hnama"></span></strong>?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url('assets/') ?>plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(() => {
        $('#myTable').DataTable();
    });

    $(document).on('click', '.edit-btn', function() {
        $('#id_penyewa').val($(this).data('id'));
        $('#nama_penyewa').val($(this).data('nama'));
        $('#nik').val($(this).data('nik'));
        $('#alamat').val($(this).data('alamat'));
        $('#nohp').val($(this).data('nohp'));
        $('#jenis_kelamin').val($(this).data('jk'));
        $('#username').val($(this).data('username'));
        $('#jenis_registrasi').val($(this).data('jenis'));
        $('#is_active').val($(this).data('status'));
        $('#edit_data').modal('show');
    });

    function hapus(id, nama) {
        $('#hid').val(id);
        $('#hnama').html(nama);
        $('#hapus_data').modal('show');
    }
</script>
<?= $this->endSection() ?>