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
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addHabit"><i class="fa fa-plus"></i> Tambah Kebiasaan</button>
                            <a href="<?php echo site_url('manage/anakhebat/import_data'); ?>" class="btn btn-default btn-sm"><i class="fa fa-upload"></i> Import Data</a>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="anakhebatTable" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kebiasaan</th>
                                    <th>Multi Isian</th>
                                    <th>Pilihan Multi Isian</th>
                                    <th>Harus Upload Foto</th>
                                    <th>Harus Masukkan Waktu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <div class="modal fade" id="addHabit" role="dialog">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Tambah Data Kebiasaan Baik</h4>
                            </div>
                            <form id="addFormHabit">
                                <div class="modal-body">
                                    <label for="name">Nama Kebiasaan Baik</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <label for="is_checkbox">Multi Isian</label>
                                    <select class="form-control" id="is_checkbox" name="is_checkbox" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                    <label for="description">Pilihan Multi Isian</label>
                                    <textarea class="form-control" id="description" name="description" placeholder="Contoh: Subuh, Dhuhur, Asar, Maghrib, Isya"></textarea>
                                    <p class="text-muted">*) pisahkan dengan tanda koma (,)</p>
                                    <label for="use_photo">Harus Upload Foto</label>
                                    <select class="form-control" id="use_photo" name="use_photo" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                    <label for="use_time">Harus Masukkan Waktu</label>
                                    <select class="form-control" id="use_time" name="use_time" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editHabit" role="dialog">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Edit Data Kebiasaan Baik</h4>
                            </div>
                            <form id="editFormHabit">
                                <div class="modal-body">
                                    <label for="name">Nama Kebiasaan Baik</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <label for="is_checkbox">Multi Isian</label>
                                    <select class="form-control" id="is_checkbox" name="is_checkbox" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                    <label for="description">Pilihan Multi Isian</label>
                                    <textarea class="form-control" id="description" name="description" placeholder="Contoh: Subuh, Dhuhur, Asar, Maghrib, Isya" ></textarea>
                                    <p class="text-muted">*) pisahkan dengan tanda koma (,)</p>
                                    <label for="use_photo">Harus Upload Foto</label>
                                    <select class="form-control" id="use_photo" name="use_photo" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                    <label for="use_time">Harus Masukkan Waktu</label>
                                    <select class="form-control" id="use_time" name="use_time" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-warning">Update</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>

</div>

<script>
    $(document).ready(function() {
        var table = $('#anakhebatTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('manage/anakhebat/habit_list'); ?>",
                "type": "GET"
            },
            "columns": [ 
                {
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "name"
                },
                {
                    "data": "is_checkbox",
                    "render": function(data, type, row) {
                        return data == 1 ? 'Ya' : 'Tidak';
                    }
                },
                {
                    "data": "description"
                },
                {
                    "data": "use_photo",
                    "render": function(data, type, row) {
                        return data == 1 ? 'Ya' : 'Tidak';
                    }
                },
                {
                    "data": "use_time",
                    "render": function(data, type, row) {
                        return data == 1 ? 'Ya' : 'Tidak';
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                    <button class="btn btn-warning btn-sm btn-edit" data-id="${row.id}">
                        <i class="fa fa-edit"></i> 
                    </button>
                    <button class="btn btn-danger btn-sm btn-delete" data-id="${row.id}">
                        <i class="fa fa-trash"></i> 
                    </button>
                `;
                    },
                    "orderable": false,
                    "searchable": false
                }
            ]
        });

        $('#anakhebatTable').on('click', '.btn-edit', function () {
            var id = $(this).data('id');

            $.ajax({
                url: "<?php echo site_url('manage/anakhebat/habit_by_id'); ?>",
                type: "POST",
                data: { id: id },
                dataType: "json",
                success: function (res) {
                    data = res.data;

                    $('#editFormHabit').find('input[name="name"]').val(data.name);
                    $('#editFormHabit').find('textarea[name="description"]').val(data.description);
                    $('#editFormHabit').find('select[name="is_checkbox"]').val(data.is_checkbox);
                    $('#editFormHabit').find('select[name="use_photo"]').val(data.use_photo);
                    $('#editFormHabit').find('select[name="use_time"]').val(data.use_time);
                    $('#editFormHabit').find('input[name="id"]').val(data.id); // hidden input untuk ID

                    if ($('#habit_id').length === 0) {
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'habit_id',
                            name: 'id',
                            value: data.id
                        }).appendTo('#editFormHabit');
                    } else {
                        $('#habit_id').val(data.id);
                    }

                    $('#editHabit').modal('show');
                },
                error: function () {
                    $.toast({
                        heading: 'Error',
                        text: 'Gagal mengambil data kebiasaan.',
                        icon: 'error',
                        position: 'top-right',
                        loaderBg: '#ff2e2e',
                        hideAfter: 3500,
                        stack: 6
                    });
                }
            });
        });

        $('#anakhebatTable').on('click', '.btn-delete', function () {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data kebiasaan akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33333',
                cancelButtonColor: '#ced1d4ff',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request hapus ke server
                    $.ajax({
                        url: "<?php echo site_url('manage/anakhebat/habit_delete'); ?>",
                        type: "POST",
                        data: { id: id },
                        dataType: "json",
                        success: function (response) {
                            if (response.is_correct) {
                                $('#anakhebatTable').DataTable().ajax.reload();
                                Swal.fire({
                                    title: 'Terhapus!',
                                    text: 'Data berhasil dihapus.',
                                    icon: 'success',
                                    timer: 2500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: response.message || 'Data gagal dihapus.',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan: ' + error,
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });


        $("#addFormHabit").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "<?php echo site_url('manage/anakhebat/habit_insert'); ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    $("#addHabit").modal('hide');
                    $("#addFormHabit")[0].reset();
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
                        loaderBg: '#49ff9bff',
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

        $("#editFormHabit").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "<?php echo site_url('manage/anakhebat/habit_update'); ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    $("#editHabit").modal('hide');
                    $("#editFormHabit")[0].reset();
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
                        text: 'Data berhasil diupdate!',
                        icon: 'success',
                        position: 'top-right',
                        loaderBg: '#49ff9bff',
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>