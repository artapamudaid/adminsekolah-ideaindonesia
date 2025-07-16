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
                    <div class="box-header">
                        <?php if ($majors_id == '0') { ?>
                            <div class="box-body table-responsive">
                                <table>
                                    <tr>
                                        <td>
                                            <select style="width: 200px;" class="form-control search-input-text" data-column="1">
                                                <option value="">--- Pilih Unit Sekolah ---</option>
                                                <?php if ($this->session->userdata('umajorsid') == '0') { ?>
                                                    <option value="all" <?php echo (isset($s['m']) && $s['m'] == 'all') ? 'selected' : '' ?>>Semua Unit</option>
                                                <?php } ?>
                                                <?php foreach ($majors as $row) { ?>
                                                    <option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            &nbsp;&nbsp;
                                        </td>
                                        <td>
                                            <button type="submit" id="searchForm" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                                            <a class="btn btn-success" data-toggle="modal" data-target="#tambahData"><i class="fa fa-plus"></i> Jurnal Baru</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        <?php } else {
                            echo ' 
                            <a class="btn btn-success" data-toggle="modal" data-target="#tambahData" ><i class="fa fa-plus"></i> Jurnal Baru</a>
                            ';
                        } ?>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id='dtable' class='table table-hover table-responsive'>
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>No. Ref</th>
                                    <th width="15%">Kode Akun</th>
                                    <th>Nama Akun</th>
                                    <th>Keterangan</th>
                                    <th>Debet</th>
                                    <th>Kredit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<!-- modal tambah data  start -->
<div class="modal fade" id="tambahData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <section class="panel panel-primary">
                <?php echo form_open('#', ' id="FormulirTambah"'); ?>
                <header class="panel-heading">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h2 class="panel-title">Tambah Jurnal Baru</h2>
                </header>
                <div class="panel-body">
                    <div class="row">
                        <?php if ($majors_id == '0') { ?>
                            <div class="col-md-3">
                                Pilih Sekolah <span class="required">*</span> <br />
                                <select style="width: 200px;" id="sekolah_id_add" name="sekolah_id" class="form-control" required>
                                    <option value="">--- Pilih Unit Sekolah ---</option>
                                    <?php foreach ($majors as $row) { ?>
                                        <option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group mt-lg keterangan">
                                    Keterangan <span class="required">*</span> <br />
                                    <input type="text" name="keterangan" class="form-control" />
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-md-12">
                                <div class="form-group mt-lg keterangan">
                                    Keterangan <span class="required">*</span> <br />
                                    <input type="hidden" id="sekolah_id_add" name="sekolah_id" value="<?php echo $row['majors_id']; ?>" />
                                    <input type="text" name="keterangan" class="form-control" />
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mt-lg tanggal">
                                Tanggal <span class="required">*</span> <br />
                                <div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    <input class="form-control" type="text" name="tanggal" readonly="readonly" placeholder="Tanggal" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group mt-lg keterangan">
                                Keterangan Lainnya<span class="required">*</span> <br />
                                <input type="text" name="keterangan_lainnya" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <button type="button" class="btn btn-primary" id="tambahItem">
                                <i class="fa fa-plus"></i> Akun / COA
                            </button>
                            <table class="table table-bordered table-hover table-striped dataTable no-footer" id="ListCoa">
                                <thead>
                                    <tr>
                                        <td></td>
                                        <th style="min-width:250px;">Nama Akun</th>
                                        <th style="max-width:200px;">Debet</th>
                                        <th style="max-width:200px;">Kredit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-success modal-confirm" type="submit" id="submitform">Simpan</button>
                        </div>
                    </div>
                </footer>
                </form>
            </section>
        </div>
    </div>
</div>
<!-- modal tambah data  end -->


<!-- modal hapus data  start -->
<div class="modal fade" id="modalHapus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <section class="panel  panel-danger">
                <header class="panel-heading">
                    <h2 class="panel-title">Hapus Kelompok Data Jurnal</h2>
                </header>
                <div class="panel-body">
                    <div class="modal-wrapper">
                        <div class="modal-text">
                            <h4>
                                Apakah Anda Yakin Ingin Menghapus
                                <i class="fa fa-question-circle"></i>
                            </h4>
                        </div>
                    </div>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <?php echo form_open('#', ' id="FormulirHapus"'); ?>
                            <input type="hidden" name="idd" id="idddelete">
                            <button type="submit" class="btn btn-danger" id="submitformHapus">Hapus</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            </form>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
    </div>
</div>
<!-- modal hapus data  end -->

<!-- modal edit data  start -->
<div class="modal fade" id="editData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <section class="panel panel-primary">
                <?php echo form_open('#', ' id="FormulirEdit"'); ?>
                <input type="hidden" name="idd" id="idd" />
                <header class="panel-heading">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h2 class="panel-title">Edit Jurnal Umum <span id="nomor_jurnal_edit"></span></h2>
                </header>
                <div class="panel-body">
                    <div class="row">
                        <?php if ($majors_id == '0') { ?>
                            <div class="col-md-3">
                                Pilih Sekolah <span class="required">*</span> <br />
                                <select style="width: 200px;" id="sekolah_id_edit" name="sekolah_id" class="form-control" required>
                                    <option value="">--- Pilih Unit Sekolah ---</option>
                                    <?php foreach ($majors as $row) { ?>
                                        <option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group mt-lg keterangan">
                                    Keterangan <span class="required">*</span> <br />
                                    <input type="text" name="keterangan" id="keterangan_edit" class="form-control" />
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-md-12">
                                <div class="form-group mt-lg keterangan">
                                    Keterangan <span class="required">*</span> <br />
                                    <input type="hidden" id="sekolah_id_edit" name="sekolah_id" value="<?php echo $row['majors_id']; ?>" />
                                    <input type="text" name="keterangan" id="keterangan_edit" class="form-control" />
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mt-lg tanggal">
                                Tanggal <span class="required">*</span> <br />
                                <div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    <input class="form-control" type="text" name="tanggal" id="tanggal_edit" readonly="readonly" placeholder="Tanggal" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group mt-lg keterangan">
                                Keterangan Lainnya<span class="required">*</span> <br />
                                <input type="text" name="keterangan_lainnya" id="keterangan_lainnya_edit" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <button type="button" class="btn btn-xs btn-success" id="tambahItem_edit">
                                <i class="fa fa-plus"></i> Akun / COA
                            </button>
                            <table class="table table-bordered table-hover table-striped dataTable no-footer" id="ListCoa_edit">
                                <thead>
                                    <tr>
                                        <td></td>
                                        <th style="min-width:250px;">Nama Akun</th>
                                        <th style="max-width:200px;">Debet</th>
                                        <th style="max-width:200px;">Kredit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-primary modal-confirm" type="submit" id="submitformEdit">Simpan</button>
                        </div>
                    </div>
                </footer>
                </form>
            </section>
        </div>
    </div>
</div>
<!-- modal edit data  end -->

<script src="<?php echo base_url() ?>assets/select2/select2.js"></script>
<script src="<?php echo base_url() ?>assets/inputmask/inputmask.js"></script>
<script src="<?php echo base_url() ?>assets/pnotify/pnotify.custom.js"></script>
<script>
    let jurnal_umum = $('#dtable').DataTable({
        "serverSide": true,
        "pageLength": "50",
        "processing": true,
        "order": [],
        "ajax": {
            "url": "<?php echo base_url() ?>manage/jurnal_umum/list_jurnal",
            "type": "GET"
        },
        "columnDefs": [{
            "targets": [5, 6],
            "render": function(data, type, row) {
                return 'Rp ' + Number(data).toLocaleString('id-ID');
            }
        }],
        "initComplete": function(settings, json) {
            var debet = json.debet;
            var kredit = json.kredit;
            var api = this.api();

            console.log("Debet: ", debet);
            console.log("Kredit: ", kredit);

            $(api.column(0).footer()).html('Total');
            $(api.column(1).footer()).html('');
            $(api.column(2).footer()).html('');
            $(api.column(3).footer()).html('');
            $(api.column(4).footer()).html('');
            $(api.column(5).footer()).html('Rp ' + Number(debet).toLocaleString('id-ID'));
            $(api.column(6).footer()).html('Rp ' + Number(kredit).toLocaleString('id-ID'));
        }
    });

    $('#searchForm').on('click', function() {
        jurnal_umum.columns('').search('').draw();
        $('.search-input-text').each(
            function(index, element) {
                let i = $(this).attr('data-column');
                let v = $(this).val();
                if (v != '') {
                    jurnal_umum.columns(i).search(v).draw();
                }
            }
        );
    });

    function select2jsSub(ID, LinkUrl, FirstOpt, kategori = '', IdSelected = '', TxtSelected = '') {
        let $dropdown = $('#' + ID);
        $dropdown.select2({
            allowClear: true,
            placeholder: FirstOpt,
            ajax: {
                url: LinkUrl,
                dataType: 'json',
                delay: 250,
                data: function(term, page) {
                    return {
                        kword: term,
                        kategori: kategori,
                    };
                },
                results: function(response) {
                    return {
                        results: $.map(response.data, function(item) {
                            return {
                                text: item.text,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
        if (IdSelected != '') {
            $dropdown.select2('data', {
                id: IdSelected,
                text: TxtSelected
            });
        }
    }

    let wrapperItem = $("#ListCoa");
    let add_button_mg = $("#tambahItem");
    let x = 0;

    $('#sekolah_id_add').on('change', function() {
        $(wrapperItem).find("tr:gt(0)").remove();
    });

    $(add_button_mg).click(function(e) {
        e.preventDefault();
        $('#coa_multi' + x).select2('close');
        let sekolah_id_add = $("#sekolah_id_add").val();
        x = x + 1;
        let formtambah = '';
        formtambah += '<tr data-urutan="' + x + '"><td style="width:22px;"><a class="mb-xs mt-xs btn-xs mr-xs btn btn-danger deleterow"><i class="fa fa-trash-o"></i></a></td>';
        formtambah += '<td><input type="text" style="width:100%;" id="coa_multi' + x + '"><input type="hidden" name="kode_akun[]" id="kode_akun' + x + '" ></td>';
        formtambah += '<td><input type="text" style="width:200px;" name="debet[]" id="debet' + x + '" class="form-control  rupiah" value="0"></td>';
        formtambah += '<td><input type="text" style="width:200px;" name="kredit[]" id="kredit' + x + '" class="form-control  rupiah" value="0"></td>';
        formtambah += '</td></tr>';
        $(wrapperItem).append(formtambah);
        select2jsSub("coa_multi" + x, '<?php echo base_url() ?>manage/jurnal_umum/list_all_coa_jurnal', 'Pilih Akun', sekolah_id_add);
        $('#coa_multi' + x).on('change', function(e) {
            let $row = $(this).closest("tr");
            let urutan = $row.data("urutan");
            let id = $(this).val();
            if (id == '') {
                $('#kode_akun' + urutan).val('');
            } else {
                $("#kode_akun" + urutan).val(id);
                window.setTimeout(function() {
                    $('#debet' + urutan).focus();
                }, 500);
            }
        });
        $(".rupiah").inputmask("decimal", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 2,
            autoGroup: true,
            prefix: 'Rp '
        });
        window.setTimeout(function() {
            $('#coa_multi' + x).select2('open');
        }, 200);
    });

    $(wrapperItem).on("click", ".deleterow", function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
    });

    $("#FormulirTambah").submit(function(e) {
        PNotify.removeAll();
        $('.help-block').hide();
        $('.form-group').removeClass('has-error');
        $("#submitform").attr("disabled", true).html('Loading ...');
        let form = $('#FormulirTambah')[0];
        let formData = new FormData(form);
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url() ?>manage/jurnal_umum/tambahjurnal',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json'
        }).done(function(data) {
            if (!data.success) {
                $("#submitform").attr("disabled", false).html('Simpan');
                let objek = Object.keys(data.errors);
                for (let key in data.errors) {
                    if (data.errors.hasOwnProperty(key)) {
                        let msg = '<div class="help-block">' + data.errors[key] + '</span>';
                        $('.' + key).addClass('has-error');
                        $('input[name="' + key + '"]').after(msg).focus();
                        $('textarea[name="' + key + '"]').after(msg).focus();
                    }
                    if (key == 'fail') {
                        new PNotify({
                            title: 'Notifikasi',
                            text: data.errors[key],
                            type: 'danger'
                        });
                    }
                }
            } else {
                jurnal_umum.ajax.reload();
                $("#submitform").attr("disabled", false).html('Simpan');
                $('#tambahData').modal('hide');
                $('#FormulirTambah')[0].reset();
                $(wrapperItem).find("tr:gt(0)").remove();
                new PNotify({
                    title: 'Notifikasi',
                    text: data.message,
                    type: 'success'
                });
            }
        }).fail(function(data) {
            new PNotify({
                title: 'Notifikasi',
                text: "Gagal",
                type: 'danger'
            });
            $("#submitform").attr("disabled", false).html('Simpan');
        });
        e.preventDefault();
    });

    function hapus(elem) {
        let dataId = $(elem).data("id");
        $("#idddelete").val(dataId);
        $('#modalHapus').modal();
    }

    $("#FormulirHapus").submit(function(e) {
        PNotify.removeAll();
        $("#submitformHapus").attr("disabled", true).html('Loading ...');
        let form = $('#FormulirHapus')[0];
        let formData = new FormData(form);
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url() ?>manage/jurnal_umum/hapusjurnal',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json'
        }).done(function(data) {
            if (!data.success) {
                $("#submitformHapus").attr("disabled", false).html('Hapus');
                let objek = Object.keys(data.errors);
                for (let key in data.errors) {
                    if (key == 'fail') {
                        new PNotify({
                            title: 'Notifikasi',
                            text: data.errors[key],
                            type: 'danger'
                        });
                    }
                }
            } else {
                jurnal_umum.ajax.reload();
                $("#submitformHapus").attr("disabled", false).html('Hapus');
                $('#modalHapus').modal('hide');
                $('#FormulirHapus')[0].reset();
                new PNotify({
                    title: 'Notifikasi',
                    text: data.message,
                    type: 'success'
                });
            }
        }).fail(function(data) {
            jurnal_umum.ajax.reload();
            $("#submitformHapus").attr("disabled", false).html('Hapus');
            $('#modalHapus').modal('hide');
            $('#FormulirHapus')[0].reset();
            new PNotify({
                title: 'Notifikasi',
                text: "Gagal",
                type: 'danger'
            });
        });
        e.preventDefault();
    });

    let wrapperItem_edit = $("#ListCoa_edit");
    let add_button_mg_edit = $("#tambahItem_edit");
    let z = 0;
    $('#sekolah_id_edit').on('change', function() {
        $(wrapperItem_edit).find("tr:gt(0)").remove();
    });
    $(add_button_mg_edit).click(function(e) {
        e.preventDefault();
        $('#coa_multi' + z).select2('close');
        let sekolah_id_edit = $("#sekolah_id_edit").val();
        z = z + 1;
        let formtambah = '';
        formtambah += '<tr data-urutan="' + z + '"><td style="width:22px;"><a class="mb-xs mt-xs btn-xs mr-xs btn btn-danger deleterow_edit"><i class="fa fa-trash-o"></i></a></td>';
        formtambah += '<td><input type="text" style="width:100%;" id="coa_multi' + z + '"><input type="hidden" name="kode_akun[]" id="kode_akun' + z + '" ></td>';
        formtambah += '<td><input type="text" style="width:200px;" name="debet[]" id="debet' + z + '" class="form-control  rupiah" value="0"></td>';
        formtambah += '<td><input type="text" style="width:200px;" name="kredit[]" id="kredit' + z + '" class="form-control  rupiah" value="0"></td>';
        $(wrapperItem_edit).append(formtambah);
        select2jsSub("coa_multi" + z, '<?php echo base_url() ?>manage/jurnal_umum/list_all_coa_jurnal', 'Pilih Akun', sekolah_id_edit);
        $('#coa_multi' + z).on('change', function(e) {
            let $row = $(this).closest("tr");
            let urutan = $row.data("urutan");
            let id = $(this).val();
            if (id == '') {
                $('#kode_akun' + urutan).val('');
            } else {
                $("#kode_akun" + urutan).val(id);
                window.setTimeout(function() {
                    $('#debet' + urutan).focus();
                }, 500);
            }
        });
        $(".rupiah").inputmask("decimal", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 2,
            autoGroup: true,
            prefix: 'Rp '
        });
        window.setTimeout(function() {
            $('#coa_multi' + z).select2('open');
        }, 200);
    });

    $(wrapperItem_edit).on("click", ".deleterow_edit", function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
    });

    function edit(elem) {
        PNotify.removeAll();
        $(".modal-content").addClass("loadingform");
        let dataId = $(elem).data("id");
        $(wrapperItem).find("tr:gt(0)").remove();
        $(wrapperItem_edit).find("tr:gt(0)").remove();
        $("#idd").val(dataId);
        $('#editData').modal();
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url() ?>manage/jurnal_umum/detaildata',
            data: 'id=' + dataId,
            dataType: 'json',
            success: function(response) {
                $(".modal-content").removeClass("loadingform");
                $.each(response.datarows, function(i, item) {
                    $("#sekolah_id_edit").val(item.sekolah_id);
                    $("#tanggal_edit").val(item.tanggal_ymd);
                    $("#keterangan_lainnya_edit").val(item.keterangan_lainnya);
                    $("#keterangan_edit").val(item.keterangan);
                });
                let y = 100000;
                let y2 = 100000;
                let sekolah_id_edit = $("#sekolah_id_edit").val();
                let datarow = '';
                $.each(response.datasub, function(i, item) {
                    y = y + 1;
                    datarow += '<tr data-urutan="' + y + '"><td style="width:22px;"><a class="mb-xs mt-xs btn-xs mr-xs btn btn-danger deleterow_edit"><i class="fa fa-trash-o"></i></a></td>';
                    datarow += '<td><input type="text" style="width:100%;" id="coa_multi' + y + '"><input type="hidden" name="kode_akun[]" id="kode_akun' + y + '" value="' + item.kode_akun + '"></td>';
                    datarow += '<td><input type="text" style="width:200px;" name="debet[]" value="' + item.debet_input + '" id="debet' + y + '" class="form-control  rupiah" value="0"></td>';
                    datarow += '<td><input type="text" style="width:200px;" name="kredit[]" value="' + item.kredit_input + '" id="kredit' + y + '" class="form-control  rupiah" value="0"></td>';
                    datarow += '</td></tr>';
                });
                $(wrapperItem_edit).append(datarow);
                $.each(response.datasub, function(i, item) {
                    y2 = y2 + 1;
                    select2jsSub("coa_multi" + y2, '<?php echo base_url() ?>manage/jurnal_umum/list_all_coa_jurnal', 'Pilih Akun', sekolah_id_edit, item.kode_akun, item.kode_akun + ' [' + item.nama_akun + ' ]');
                    $('#coa_multi' + y2).on('change', function(e) {
                        let $row = $(this).closest("tr");
                        let urutan = $row.data("urutan");
                        let id = $(this).val();
                        if (id == '') {
                            $('#kode_akun' + urutan).val('');
                        } else {
                            $("#kode_akun" + urutan).val(id);
                            window.setTimeout(function() {
                                $('#debet' + urutan).focus();
                            }, 500);
                        }
                    });
                });
                $(".rupiah").inputmask("decimal", {
                    radixPoint: ",",
                    groupSeparator: ".",
                    digits: 2,
                    autoGroup: true,
                    prefix: 'Rp '
                });

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $(".modal-content").removeClass("loadingform");
                new PNotify({
                    title: textStatus,
                    text: errorThrown,
                    type: 'danger'
                });
                $('#editData').modal('hide');
            }
        });
        return false;
    }

    $("#FormulirEdit").submit(function(e) {
        PNotify.removeAll();
        $('.help-block').hide();
        $('.form-group').removeClass('has-error');
        $("#submitformEdit").attr("disabled", true).html('Loading ...');
        let form = $('#FormulirEdit')[0];
        let formData = new FormData(form);
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url() ?>manage/jurnal_umum/edit',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json'
        }).done(function(data) {
            if (!data.success) {
                $("#submitformEdit").attr("disabled", false).html('Simpan');
                let objek = Object.keys(data.errors);
                for (let key in data.errors) {
                    if (data.errors.hasOwnProperty(key)) {
                        let msg = '<div class="help-block">' + data.errors[key] + '</span>';
                        $('.' + key).addClass('has-error');
                        $('input[name="' + key + '"]').after(msg).focus();
                        $('textarea[name="' + key + '"]').after(msg).focus();
                    }
                    if (key == 'fail') {
                        new PNotify({
                            title: 'Notifikasi',
                            text: data.errors[key],
                            type: 'danger'
                        });
                    }
                }
            } else {
                jurnal_umum.ajax.reload();
                $("#submitformEdit").attr("disabled", false).html('Simpan');
                $('#editData').modal('hide');
                $('#FormulirEdit')[0].reset();
                new PNotify({
                    title: 'Notifikasi',
                    text: data.message,
                    type: 'success'
                });
            }
        }).fail(function(data) {
            jurnal_umum.ajax.reload();
            $("#submitformEdit").attr("disabled", false).html('Simpan');
            $('#editData').modal('hide');
            $('#FormulirEdit')[0].reset();
            new PNotify({
                title: 'Notifikasi',
                text: "Gagal",
                type: 'danger'
            });
        });
        e.preventDefault();
    });
</script>