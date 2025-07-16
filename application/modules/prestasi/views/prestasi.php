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
        <div class="row">
            <div class="col-xs-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#kategori" data-toggle="tab">Kategori Prestasi</a></li>
                        <li><a href="#jenis" data-toggle="tab">Jenis Prestasi</a></li>
                    </ul>
                    <div class="tab-content">
                        <!-- KATEGORI PRESTASI -->
                        <div class="tab-pane active" id="kategori">
                            <div class="box">
                                <div class="box-header">
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addKategori">
                                        <i class="fa fa-plus"></i> Tambah Kategori
                                    </button>
                                </div>
                                <div class="box-body table-responsive">
                                    <table id="tabel-kategori" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;">No</th>
                                                <th>Kategori</th>
                                                <th>ID Kategori</th>
                                                <th style="width: 10%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($kategori_prestasi as $k) { ?>
                                                <tr>
                                                    <td><?php echo $no++ ?></td>
                                                    <td><?php echo $k['kategori'] ?></td>
                                                    <td style="text-align: center;"><?php echo $k['id'] ?></td>
                                                    <td>
                                                        <button
                                                            data-kategori="<?php echo $k['id'] ?>"
                                                            data-nama="<?php echo htmlspecialchars($k['kategori']) ?>"
                                                            class="btnEditKategori btn btn-xs btn-warning">
                                                            <i class="fa fa-edit" data-toggle="tooltip" title="Edit"></i>
                                                        </button>
                                                        <button data-kategori="<?php echo $k['id'] ?>" class="btnDelKategori btn btn-xs btn-danger">
                                                            <i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- JENIS PRESTASI -->
                        <div class="tab-pane" id="jenis">
                            <div class="box">
                                <div class="box-header">
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalTambahJenisPrestasi">
                                        <i class="fa fa-plus"></i> Tambah Prestasi
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#importPrestasiModal">
                                        <i class="fa fa-upload"></i> Import Prestasi
                                    </button>
                                </div>
                                <div class="box-body table-responsive">
                                    <table id="dtable" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;">No</th>
                                                <th>Prestasi</th>
                                                <th>Poin</th>
                                                <th>Kategori</th>
                                                <th style="width: 5%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($prestasi as $row) { ?>
                                                <tr>
                                                    <td style="width: 5%;"><?php echo $no++ ?></td>
                                                    <td><?php echo $row->prestasi ?></td>
                                                    <td style="text-align: center;"><?php echo $row->poin ?></td>
                                                    <td><?php echo $row->kategori ?></td>
                                                    <td style="width: 5%;">
                                                        <button
                                                            data-prestasi="<?php echo $row->id ?>"
                                                            data-nama="<?php echo htmlspecialchars($row->prestasi) ?>"
                                                            data-id_kategori="<?php echo $row->id_kategori ?>"
                                                            data-poin="<?php echo $row->poin ?>"
                                                            class="btnEditPrestasi btn btn-xs btn-warning">
                                                            <i class="fa fa-edit" data-toggle="tooltip" title="Edit"></i>
                                                        </button>
                                                        <button data-prestasi="<?php echo $row->id ?>" class="btnDelPrestasi btn btn-xs btn-danger">
                                                            <i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- /Jenis -->
                    </div> <!-- /.tab-content -->
                </div> <!-- /.nav-tabs-custom -->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<!-- Modal -->
<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addKategori" tabindex="-1" role="dialog" aria-labelledby="addKategoriLabel">
    <div class="modal-dialog" role="document">
        <form method="post" action="<?php echo base_url('manage/prestasi/tambah_kategori') ?>">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="addKategoriLabel">Tambah Kategori Prestasi</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kategori">Nama Kategori</label>
                        <input type="text" class="form-control" name="kategori" id="kategori" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Simpan</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editKategoriModal" tabindex="-1" role="dialog" aria-labelledby="editKategoriLabel">
    <div class="modal-dialog" role="document">
        <form method="post" action="<?php echo base_url('manage/prestasi/update_kategori') ?>">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h4 class="modal-title" id="editKategoriLabel">Edit Kategori Prestasi</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="edit_id_kategori" id="edit-id-kategori">
                    <div class="form-group">
                        <label for="edit-nama-kategori">Nama Kategori</label>
                        <input type="text" class="form-control" name="edit_nama_kategori" id="edit-nama-kategori" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="deleteKategoriModal" tabindex="-1" role="dialog" aria-labelledby="deleteKategoriLabel">
    <div class="modal-dialog" role="document">
        <form method="post" action="<?php echo base_url('manage/prestasi/delete_kategori') ?>">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title" id="deleteKategoriLabel">Hapus Kategori Prestasi</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="delete_id_kategori" id="delete-id-kategori">
                    <p>Apakah Anda yakin ingin menghapus kategori ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalTambahJenisPrestasi" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tambah Jenis Prestasi</h4>
            </div>
            <form action="<?php echo base_url('manage/prestasi/add_jenis_prestasi') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="period" value="<?php echo $f['n'] ?>">
                    <input type="hidden" name="nis" value="<?php echo $f['r'] ?>">
                    <input type="hidden" name="source" value="master">

                    <div class="form-group">
                        <label>Kategori Prestasi</label>
                        <select class="form-control" name="kategori_prestasi" required>
                            <?php foreach ($kategori_prestasi as $kp) { ?>
                                <option value="<?php echo $kp['id'] ?>"><?php echo $kp['kategori'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Prestasi</label>
                        <input class="form-control" required="" type="text" name="nama_prestasi" value="" placeholder="Contoh: Juara Olimpiade Matematika Nasional">
                    </div>
                    <div class="form-group">
                        <label>Poin Prestasi</label>
                        <input class="form-control" required="" type="number" name="poin_prestasi" value="" placeholder="Contoh: 60">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModalPrestasi" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
        <form method="post" action="<?php echo site_url('manage/prestasi/update_prestasi') ?>">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h4 class="modal-title" id="editModalLabel">Edit Prestasi</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">

                    <div class="form-group">
                        <label>Nama Prestasi</label>
                        <input type="text" name="prestasi" id="edit-prestasi" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="id_kategori" id="edit-kategori" class="form-control" required>
                            <?php foreach ($kategori_prestasi as $kp) { ?>
                                <option value="<?php echo $kp['id'] ?>"><?php echo $kp['kategori'] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Poin</label>
                        <input type="number" name="poin" id="edit-poin" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal modal-default fade" id="delModalPrestasi">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi penghapusan</h3>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin akan menghapus data ini?</p>
            </div>
            <div class="modal-footer">
                <?php echo form_open('manage/prestasi/delete_prestasi'); ?>
                <input type="hidden" id="del-prestasi" name="del_prestasi">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
                <button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
                <?php echo form_close(); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Modal Import Prestasi -->
<div class="modal fade" id="importPrestasiModal" tabindex="-1" role="dialog" aria-labelledby="importPrestasiLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formImportPrestasi" method="post" action="<?php echo site_url('manage/prestasi/import_prestasi'); ?>" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importPrestasiLabel">Import Data Prestasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Gunakan template yang disediakan agar format sesuai. Silahkan download templatenya <a href="<?php echo site_url('manage/prestasi/download_template_prestasi'); ?>" class="btn btn-info btn-sm">
                            <i class="fa fa-download"></i> Download Template
                        </a></p>
                    <br>
                    <div class="form-group">
                        <label for="fileExcel">Pilih file Excel (.xlsx)</label>
                        <input type="file" name="file_excel" id="fileExcel" class="form-control" accept=".xls,.xlsx" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Import</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal -->

<script type="text/javascript">
    $(document).ready(function() {
        $("#tabel-kategori").DataTable();
    });
    $('.btnEditKategori').click(function() {
        const id = $(this).data('kategori');
        const nama = $(this).data('nama');
        $('#edit-id-kategori').val(id);
        $('#edit-nama-kategori').val(nama);
        $('#editKategoriModal').modal('show');
    });

    // Delete Kategori
    $('.btnDelKategori').click(function() {
        const id = $(this).data('kategori');
        $('#delete-id-kategori').val(id);
        $('#deleteKategoriModal').modal('show');
    });

    $(document).on('click', '.btnEditPrestasi', function() {
        const id = $(this).data('prestasi');
        const nama = $(this).data('nama');
        const kategori = $(this).data('id_kategori');
        const poin = $(this).data('poin');

        $('#edit-id').val(id);
        $('#edit-prestasi').val(nama);
        $('#edit-kategori').val(kategori);
        $('#edit-poin').val(poin);

        $('#editModalPrestasi').modal('show');
    });

    $(document).on('click', '.btnDelPrestasi', function() {
        var id = $(this).data('prestasi');
        $('#del-prestasi').val(id);
        $('#delModalPrestasi').modal('show');
    });
</script>