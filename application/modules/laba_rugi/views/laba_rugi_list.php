<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/pnotify/pnotify.custom.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>/assets/select2/select2.css" />
<style>
    .loadingform {
        content: url("<?php echo base_url() ?>/assets/loading.gif");
        position: absolute;
        z-index: 999;
        overflow: visible;
        margin: auto;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }
</style>
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
        <?php
        $majors_id = $this->session->userdata('umajorsid');
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header"> <br>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select required="" name="majors_id" id="majors_id" class="form-control">
                                        <option value="">-- Pilih Unit --</option>
                                        <?php if ($this->session->userdata('umajorsid') == '0') { ?>
                                            <option value="all">Semua Unit</option>
                                        <?php } ?>
                                        <?php foreach ($majors as $row) { ?>
                                            <option value="<?php echo $row['majors_id']; ?>"><?php echo $row['majors_short_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                        <input class="form-control" type="text" name="start" id="start" readonly="readonly" placeholder="Tanggal Awal">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                        <input class="form-control" type="text" name="end" id="end" readonly="readonly" placeholder="Tanggal Awal">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" onclick="cari_data()">Filter</button>
                        </div>

                    </div>
                    <div id="div_show_data">
                        <div>
                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<script src="<?php echo base_url() ?>assets/select2/select2.js"></script>
<script src="<?php echo base_url() ?>assets/inputmask/inputmask.js"></script>
<script src="<?php echo base_url() ?>assets/pnotify/pnotify.custom.js"></script>
<script>
    let laba_rugi = $('#dtable').DataTable({
        "serverSide": true,
        "pageLength": "50",
        "processing": true,
        "order": [],
        "ajax": {
            "url": "<?php echo base_url() ?>manage/laba_rugi/list_laba_rugi",
            "type": "GET"
        },
        "columnDefs": [{
            "targets": [2, 3],
            "render": function(data, type, row) {
                return 'Rp ' + Number(data).toLocaleString('id-ID');
            }
        }],
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api(),
                data;

            // Remove the formatting to get the numeric data for summation
            var intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                    i : 0;
            };

            var debitTotal = api
                .column(2)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            var creditTotal = api
                .column(3)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            var difference = debitTotal - creditTotal;

            $('tr:eq(0) th:eq(0)', api.table().footer()).html('Total');
            $('tr:eq(0) th:eq(1)', api.table().footer()).html('Rp ' + Number(debitTotal).toLocaleString('id-ID'));
            $('tr:eq(0) th:eq(2)', api.table().footer()).html('Rp ' + Number(creditTotal).toLocaleString('id-ID'));
            $('tr:eq(1) th:eq(0)', api.table().footer()).html('Laba/Rugi');
            $('tr:eq(1) th:eq(1)', api.table().footer()).html('');
            $('tr:eq(1) th:eq(2)', api.table().footer()).html('Rp ' + Number(difference).toLocaleString('id-ID'));
        }
    });

    $('#searchForm').on('click', function() {
        laba_rugi.columns('').search('').draw();
        $('.search-input-text').each(
            function(index, element) {
                let i = $(this).attr('data-column');
                let v = $(this).val();
                if (v != '') {
                    laba_rugi.columns(i).search(v).draw();
                }
            }
        );
    });
</script>

<script>
    function cari_data() {
        var start = $("#start").val();
        var end = $("#end").val();
        var majors_id = $("#majors_id").val();
        //alert(id_jurusan+id_kelas);
        if (start != '' && end != '' && majors_id != '') {
            $.ajax({
                url: '<?php echo base_url(); ?>manage/laba_rugi/get_laba_rugi',
                type: 'POST',
                data: {
                    'start': start,
                    'end': end,
                    'majors_id': majors_id,
                },
                success: function(msg) {
                    $("#div_show_data").html(msg);
                },
                error: function(msg) {
                    alert('msg');
                }

            });
        }
    }
</script>