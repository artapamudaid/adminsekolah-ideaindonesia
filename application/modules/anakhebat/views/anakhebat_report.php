<style>
    @media (min-width: 768px) {
        .modal-xl {
            width: 90%;
            max-width: 1200px;
        }
    }
</style>
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
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                        <input class="form-control" type="text" name="filter_date" id="filter_date" readonly="readonly" placeholder="Pilih Tanggal ">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select required="" name="filter_majors" id="filter_majors" class="form-control">
                                        <option value="">-- Pilih Unit Sekolah --</option>
                                        <?php foreach ($majors as $row) { ?>
                                            <option value="<?php echo $row['majors_id']; ?>"><?php echo $row['majors_short_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div id="div_classes">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select required="" name="filter_class" id="filter_class" class="form-control">
                                            <option value="">-- Pilih Kelas --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary" id="btnFilter">Filter</button>
                            </div>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="anakhebatTable" class="table table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Rekap</th>
                                    <th>Persentase</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="modal fade" id="viewReport" role="dialog">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Lihat Detail Laporan</h4>
                            </div>
                            <div class="modal-body">

                                <table id="reportDetail" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kegiatan</th>
                                            <th>Tanggal, Waktu</th>
                                            <th>Keterangan</th>
                                            <th>Bukti Foto</th>
                                            <th>Pengisi</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
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

        let table; // Simpan instance DataTable
        let detail;

        $('#filter_majors').on('change', function() {

            $.ajax({
                url: '<?php echo base_url(); ?>manage/anakhebat/class_searching',
                type: 'POST',
                data: {
                    'id_majors': $(this).val(),
                },
                success: function(msg) {
                    $("#div_classes").html(msg);
                },
                error: function(msg) {
                    alert('msg');
                }

            });
        });

        $('#btnFilter').on('click', function() {
            // Tampilkan table
            $('#anakhebatTable').show();

            // Jika belum pernah diinisialisasi
            if (!table) {
                table = $('#anakhebatTable').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "<?php echo site_url('manage/anakhebat/habit_student_list'); ?>",
                        "type": "GET",
                        "data": function(d) {
                            d.date = $('#filter_date').val();
                            d.majors_majors_id = $('#filter_majors').val();
                            d.class_class_id = $('#filter_class').val();
                        }
                    },
                    "columns": [{
                            "data": null,
                            "render": function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            "data": "student_nis"
                        },
                        {
                            "data": "student_full_name"
                        },
                        {
                            "data": "class_name"
                        },
                        {
                            "data": "capaian"
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                return `
                                    <span class="label label-${row.color}">
                            ${row.presentase}
                                    </span>
                                `;
                            },
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                return `
                                    <button class="btn btn-info btn-sm btn-detail" data-id="${row.student_id}" data-date="${row.date}">
                                        <i class="fa fa-list"></i>
                                    </button>
                                `;
                            },
                            "orderable": false,
                            "searchable": false
                        }
                    ]
                });
            } else {
                // Reload jika sudah ada
                table.ajax.reload();
            }
        });

        $('#anakhebatTable').on('click', '.btn-detail', function() {
            $('#viewReport').modal('show');
            $('#reportDetail').show();
            let student_id = $(this).data('id');
            let date = $(this).data('date');
            // console.log(student_id);

            let status;
            // if (!detail) {
            detail = $('#reportDetail').DataTable({
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "ajax": {
                    "url": "<?php echo site_url('manage/anakhebat/habit_report_detail'); ?>",
                    "type": "POST",
                    "data": function(d) {
                        d.student_id = student_id;
                        d.date = date;
                    }
                },
                "columns": [{
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        "data": "habit_name"
                    },
                    {
                        "data": "timestamp",
                        "render": function(data) {
                            if (!data) {
                                return ''; // atau bisa return '-' kalau mau placeholder
                            }

                            let date = new Date(data);
                            if (isNaN(date.getTime())) {
                                return ''; // kalau formatnya nggak valid
                            }

                            const bulan = [
                                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                            ];

                            let d = String(date.getDate()).padStart(2, '0');
                            let F = bulan[date.getMonth()];
                            let Y = date.getFullYear();

                            let H = String(date.getHours()).padStart(2, '0');
                            let i = String(date.getMinutes()).padStart(2, '0');
                            let s = String(date.getSeconds()).padStart(2, '0');

                            return `${d} ${F} ${Y} ${H}:${i}:${s}`;
                        }
                    },
                    {
                        "data": "report_note",
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            let photo;
                            if (row.report_photo) {
                                photo = `
                                <a href="${row.report_photo}" class="btn btn-primary btn-sm" target="_blank">Lihat Foto</a>
                                `;
                            } else {
                                photo = `-`;
                            }
                            return photo;
                        }
                    },
                    {
                        "data": "assign"
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            if (row.status == 'Sudah') {
                                status = `<span class="label label-success">
                                    <i class="fa fa-check"></i>
                                    </span>`
                            } else {
                                status = `<span class="label label-danger">
                                    <i class="fa fa-remove"></i>
                                    </span>`
                            }
                            return status;
                        },
                    },
                ]
            });
            // } else {
            //     // Reload jika sudah ada
            //     detail.ajax.reload();
            // }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>