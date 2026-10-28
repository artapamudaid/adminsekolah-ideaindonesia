<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
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
                <div class="box">
                    <div class="box-header">
                        <?php if ($this->session->userdata('uroleid') == 3) : ?>
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addPenjual"><i class="fa fa-plus"></i> Tambah Penjual</button>
                        <?php endif; ?>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="kantinTable" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nama Outlet</th>
                                    <th>Pemilik</th>
                                    <th>HP/WA</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <?php if ($this->session->userdata('uroleid') == 3) : ?>
                    <div class="modal fade" id="addPenjual" role="dialog">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Tambah Penjual</h4>
                                </div>
                                <form id="addFormPenjual">
                                    <div class="modal-body">

                                        <label for="kategori">Kategori</label>
                                        <select name="langganan" id="langganan" class="form-control">
                                            <option value="">Pilih Kategori</option>
                                            <?php foreach ($categories as $category) : ?>
                                                <option value="<?php echo $category['id_kategori'] ?>"><?php echo ucfirst($category['nama_kategori']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label for="nama_toko">Nama Outlet</label>
                                        <input type="text" class="form-control" id="nama_toko" name="nama_toko" required>
                                        <label for="nama_pemilik">Nama Pemilik</label>
                                        <input type="text" class="form-control" id="nama_pemilik" name="nama_pemilik" required>
                                        <label for="no_hp">HP/WA</label>
                                        <input type="number" class="form-control" id="no_hp" name="no_hp" required>
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>


                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>

</div>

<script>
    $(document).ready(function() {
        var table = $('#kantinTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('manage/ekantin/fetch_penjual'); ?>",
                "type": "GET"
            },
            "columns": [{
                    "data": "nama_toko"
                },
                {
                    "data": "nama_pemilik"
                },
                {
                    "data": "no_hp"
                },
                {
                    "data": "email"
                },
                {
                    "data": "status",
                    "render": function(data, type, row) {
                        return `<label class="label ${data === 'active' ? 'label-success' : 'label-danger'}">${data === 'active' ? 'Aktif' : 'Tidak Aktif'}</label>`;
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                    <button class="btn btn-info btn-sm btn-detail" data-id="${row.no_hp}">
                        <i class="fa fa-eye"></i> Detail
                    </button>
                `;
                    },
                    "orderable": false,
                    "searchable": false
                }
            ]
        });

        $('#kantinTable').on('click', '.btn-detail', function() {
            var id = $(this).data('id');

            // Contoh: buka modal atau redirect ke halaman detail
            window.location.href = "<?php echo site_url('manage/ekantin/detail_penjual'); ?>/" + id;
        });


        $("#addFormPenjual").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "<?php echo site_url('manage/ekantin/insert_data'); ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    $("#addPenjual").modal('hide');
                    $("#addFormPenjual")[0].reset();
                    table.ajax.reload();

                    if (response.is_correct == false) {
                        $.toast({
                            heading: 'Error',
                            text: response.message,
                            icon: 'error',
                            position: 'top-right',
                            loaderBg: '#ff2e2e',
                            icon: 'error',
                            hideAfter: 3500,
                            stack: 6
                        });
                        return;
                    }
                    $.toast({
                        heading: 'Sukses',
                        text: 'Data berhasil ditambahkan!',
                        icon: 'success',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3500,
                        stack: 6
                    });
                },
                error: function(xhr, status, error) {
                    $.toast({
                        heading: 'Error',
                        text: 'Terjadi kesalahan: ' + error,
                        icon: 'error',
                        position: 'top-right',
                        loaderBg: '#ff2e2e',
                        icon: 'error',
                        hideAfter: 3500,
                        stack: 6
                    });
                }
            });
        });
    });
</script>
