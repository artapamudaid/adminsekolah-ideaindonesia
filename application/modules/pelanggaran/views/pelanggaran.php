<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo isset($title) ? '' . $title : null; ?>
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
            <li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
        </ol>
    </section>
    <section class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#kategori_pelanggaran" data-toggle="tab">Kategori Pelanggaran</a></li>
                <li><a href="#jenis_pelanggaran" data-toggle="tab">Jenis Pelanggaran</a></li>
            </ul>
            <div class="tab-content">
                <!-- Kategori Tab -->
                <div class="tab-pane active" id="kategori_pelanggaran">
                    <div class="box-header">
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalTambahKategoriPelanggaran">
                            <i class="fa fa-plus"></i> Tambah Kategori
                        </button>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-hover" id="tableKategoriPelanggaran">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Kategori</th>
                                    <th>ID Kategori</th>
                                    <th style="width: 5%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($kategori_pelanggaran as $k): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $k['kategori'] ?></td>
                                        <td style="text-align: center;"><?= $k['id'] ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-xs btnEditKategoriPelanggaran" data-id="<?= $k['id'] ?>" data-nama="<?= htmlspecialchars($k['kategori']) ?>">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-xs btnDelKategoriPelanggaran" data-id="<?= $k['id'] ?>">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Jenis Pelanggaran Tab -->
                <div class="tab-pane" id="jenis_pelanggaran">
                    <div class="box-header">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalTambahPelanggaran">
                            <i class="fa fa-plus"></i> Tambah Pelanggaran
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#importPelanggaranModal">
                            <i class="fa fa-upload"></i> Import Pelanggaran
                        </button>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-hover" id="tablePelanggaran">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Pelanggaran</th>
                                    <th>Poin</th>
                                    <th>Kategori</th>
                                    <th style="width: 5%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($pelanggaran as $p): ?>
                                    <tr>
                                        <td style="width: 5%;"><?= $no++ ?></td>
                                        <td><?= $p->pelanggaran ?></td>
                                        <td style="text-align: center;"><?= $p->poin ?></td>
                                        <td><?= $p->kategori ?></td>
                                        <td style="width: 5%;">
                                            <button class="btn btn-warning btn-xs btnEditPelanggaran" data-id="<?= $p->id ?>" data-nama="<?= htmlspecialchars($p->pelanggaran) ?>" data-poin="<?= $p->poin ?>" data-id_kategori="<?= $p->id_kategori ?>">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-xs btnDelPelanggaran" data-id="<?= $p->id ?>">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<!-- Modal Tambah/Edit Pelanggaran -->
<div class="modal fade" id="modalTambahPelanggaran">
    <div class="modal-dialog">
        <form action="<?= base_url('manage/pelanggaran/simpan_pelanggaran') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah/Edit Pelanggaran</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="pelanggaran_id">
                    <div class="form-group">
                        <label>Pelanggaran</label>
                        <input type="text" name="pelanggaran" class="form-control" id="pelanggaran_nama" required>
                    </div>
                    <div class="form-group">
                        <label>Poin</label>
                        <input type="number" name="poin" class="form-control" id="pelanggaran_poin" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="id_kategori" id="pelanggaran_id_kategori" class="form-control" required>
                            <?php foreach ($kategori_pelanggaran as $kat): ?>
                                <option value="<?= $kat['id'] ?>"><?= $kat['kategori'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah/Edit Kategori -->
<div class="modal fade" id="modalTambahKategoriPelanggaran">
    <div class="modal-dialog">
        <form action="<?= base_url('manage/pelanggaran/simpan_kategori_pelanggaran') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah/Edit Kategori Pelanggaran</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="kategori_pelanggaran_id">
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text" name="kategori" id="kategori_pelanggaran_nama" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="importPelanggaranModal" tabindex="-1" role="dialog" aria-labelledby="importPelanggaranLabel">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('manage/pelanggaran/import_pelanggaran') ?>" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title" id="importPelanggaranLabel">Import Data Pelanggaran</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p>Gunakan template yang disediakan agar format sesuai. Silahkan download templatenya <a href="<?php echo site_url('manage/pelanggaran/download_template_pelanggaran'); ?>" class="btn btn-info btn-sm">
                                <i class="fa fa-download"></i> Download Template
                            </a></p>
                        <br>
                        <label for="file_excel">Upload File Excel (.xlsx)</label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control" required accept=".xls,.xlsx">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Import</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- jQuery Script -->
<script>
    $(document).ready(function() {
        $("#tableKategoriPelanggaran").DataTable();
        $("#tablePelanggaran").DataTable();
    });
    $('.btnEditPelanggaran').click(function() {
        $('#modalTambahPelanggaran').modal('show');
        $('#pelanggaran_id').val($(this).data('id'));
        $('#pelanggaran_nama').val($(this).data('nama'));
        $('#pelanggaran_poin').val($(this).data('poin'));
        $('#pelanggaran_id_kategori').val($(this).data('id_kategori'));
    });

    $('.btnEditKategoriPelanggaran').click(function() {
        $('#modalTambahKategoriPelanggaran').modal('show');
        $('#kategori_pelanggaran_id').val($(this).data('id'));
        $('#kategori_pelanggaran_nama').val($(this).data('nama'));
    });

    $('.btnDelPelanggaran').click(function() {
        if (confirm('Yakin ingin menghapus pelanggaran ini?')) {
            window.location.href = '<?= base_url("manage/pelanggaran/delete_pelanggaran/") ?>' + $(this).data('id');
        }
    });

    $('.btnDelKategoriPelanggaran').click(function() {
        if (confirm('Yakin ingin menghapus kategori ini?')) {
            window.location.href = '<?= base_url("manage/pelanggaran/delete_kategori_pelanggaran/") ?>' + $(this).data('id');
        }
    });
</script>