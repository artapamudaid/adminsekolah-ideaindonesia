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
                    <div class="box-body table-responsive">
                        <table id="pencairanTable" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nama Toko</th>
                                    <th>Total Pencairan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>

</div>

<script>
    $(document).ready(function() {
        var table = $('#pencairanTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('manage/ekantin/fetch_pencairan'); ?>",
                "type": "GET"
            },
            "columns": [{
                    "data": "nama_toko"
                },
                {
                    "data": "total_pencairan"
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                    <button class="btn btn-info btn-sm btn-detail" data-id="${row.id_penjual}">
                        <i class="fa fa-eye"></i> Detail
                    </button>
                `;
                    },
                    "orderable": false,
                    "searchable": false
                }
            ]
        });

        $('#pencairanTable').on('click', '.btn-detail', function() {
            var id = $(this).data('id');

            // Contoh: buka modal atau redirect ke halaman detail
            window.location.href = "<?php echo site_url('manage/ekantin/detail_pencairan'); ?>/" + id;
        });


    });
</script>