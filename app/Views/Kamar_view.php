<?= $this->extend('template/Header') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#modal_add">
                    <span class="fas fa-plus"></span>
                    Tambah Data
                </button>

            </div>

            <div class="card-body">
                <table id="myTable" class="table table-bordered table-striped">
                    <thead>
                        <th class="text-center">No</th>
                        <th>Nama Kamar</th>
                        <th>Fasilitas</th>
                        <th>Harga Sewa</th>
                        <th>Status</th>
                        <th>Foto</th>
                        <th class="text-center">Aksi</th>
                    </thead>

                    <tbody>
                        <?php $no = 1;
                        foreach ($data as $d) { ?>
                            <tr>
                                <td width="50px" class="text-center"><?php echo $no . '.'; ?></td>
                                <td><?= $d->nama_kamar ?></td>
                                <td><?= nl2br($d->fasilitas) ?></td>
                                <td><?= $d->harga ?></td>
                                <td><?= $d->status ?></td>
                                <td>
                                    <?php if ($d->foto) : ?>
                                        <img src="<?= base_url('uploads/kamar/' . $d->foto) ?>" width="100">
                                    <?php else : ?>
                                        <em>-</em>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center" width="100px">
                                    <a class="btn btn-sm btn-success edit-btn" href="javascript:void(0)" data-id="<?= $d->id ?>" data-nama="<?= htmlspecialchars($d->nama_kamar, ENT_QUOTES, 'UTF-8') ?>" data-fasilitas="<?= htmlspecialchars($d->fasilitas, ENT_QUOTES, 'UTF-8') ?>" data-harga="<?= htmlspecialchars($d->harga, ENT_QUOTES, 'UTF-8') ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="hapus('<?= $d->id ?>','<?= $d->nama_kamar ?>')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php $no++;
                        } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/') ?>plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });

    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        let nama = $(this).data('nama');
        let fasilitas = $(this).data('fasilitas');
        let harga = $(this).data('harga');

        edit(id, nama, fasilitas, harga);
    });

    function edit(id_kamar, nama_kamar, fasilitas, harga) {
        $('#id_kamar').val(id_kamar);
        $('#nama_kamar').val(nama_kamar);
        $('#fasilitas').val(fasilitas);
        $('#harga').val(harga);
        $('#edit_data').modal('show');
    }

    function hapus(id, nama) {
        $('#hid').val(id);
        $('#hnama').html(nama);
        $('#hapus_data').modal('show');
    }
</script>

<div class="modal fade" id="modal_add">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Input Data</h4>
                <button type="button" class="close btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form role="form" method="POST" action="<?= site_url('save_kamar') ?>" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Nama Kamar</label>
                            <input type="text" name="nama_kamar" class="form-control" required>
                        </div>
                        <div class="form-group col-6">
                            <label>Harga Sewa</label>
                            <input type="number" name="harga" class="form-control" required>
                        </div>
                        <div class="form-group col-12">
                            <label>Fasilitas</label>
                            <textarea class="form-control" rows="4" name="fasilitas" required></textarea>
                        </div>
                        <div class="form-group col-12">
                            <label>Foto</label>
                            <input type="file" name="foto" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_data">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Data</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form role="form" method="POST" action="<?= site_url('edit_kamar') ?>" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Nama kamar</label>
                            <input type="hidden" name="id_kamar" id="id_kamar" class="form-control" required>
                            <input type="text" name="nama_kamar" id="nama_kamar" class="form-control" required>
                        </div>
                        <div class="form-group col-6">
                            <label>Harga Sewa</label>
                            <input type="number" name="harga" id="harga" class="form-control" required>
                        </div>

                        <div class="form-group col-12">
                            <label>Fasilitas</label>
                            <textarea class="form-control" rows="4" name="fasilitas" id="fasilitas" required></textarea>
                        </div>
                        <div class="form-group col-12">
                            <label>Foto</label>
                            <input type="file" name="foto" class="form-control">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="hapus_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form method="POST" action="<?= site_url('delete_kamar') ?>">
                <div class="modal-body">
                    <input type="hidden" name="id" id="hid">
                    Anda yakin hapus data <strong><span id="hnama"></span></strong> ?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-trash"></i> Hapus</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>