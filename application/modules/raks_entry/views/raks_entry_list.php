<style>
    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    /* Style the buttons that are used to open the tab content */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
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
        <?php
        $majors_id = $this->session->userdata('umajorsid');
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">

                        <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
                        <div class="box-body table-responsive">
                            <table>
                                <tr>
                                    <td>
                                        <select style="width: 200px;" id="m" name="m" class="form-control" required>
                                            <option value="">--- Pilih Unit ---</option>
                                            <?php if ($this->session->userdata('umajorsid') == '0') { ?>
                                                <option value="all" <?php echo (isset($s['m']) && $s['m'] == 'all') ? 'selected' : '' ?>>Semua Unit</option>
                                            <?php } ?>
                                            <?php foreach ($majors as $row) { ?>
                                                <option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        &nbsp&nbsp
                                    </td>
                                    <td>
                                        <select style="width: 200px;" id="p" name="p" class="form-control" required>
                                            <option value="">--- Pilih Tahun Ajaran ---</option>
                                            <?php foreach ($period as $row) : ?>
                                                <option <?php if (isset($s['p']) and $s['p'] == $row['period_id']) {
                                                            echo 'selected';
                                                        } else if (empty($s['p']) and $periodActive['period_id'] == $row['period_id']) {
                                                            echo 'selected';
                                                        } else {
                                                            echo '';
                                                        } ?> value="<?php echo $row['period_id'] ?>"><?php echo $row['period_start'] . '/' . $row['period_end'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        &nbsp&nbsp
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php echo form_close(); ?>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class='table table-hover table-responsive'>
                            <thead>
                                <tr>
                                    <th hidden="true"></th>
                                    <th hidden="true"></th>
                                    <th style="width: 10%;">Kode</th>
                                    <th style="width: 50%;">Nama</th>
                                    <th style="width: 20%;"> Total </th>
                                    <!-- <th>Status</th> -->
                                    <th style="width: 20%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($s['m']) and $s['m'] != 'all' and !empty($s['p'])) { ?>
                                    <tr style="font-weight: bold;">
                                        <td hidden="true"></td>
                                        <td hidden="true" class="kode-entry"></td>
                                        <td colspan="2">RAKS <?php echo $dataPeriod['period_start'] . '/' . $dataPeriod['period_end'] . " - " . $dataMajors['majors_short_name'] ?></td>

                                        <td>Rp. <?php echo number_format($total_anggaran ?? 0, 0, ',', '.')  ?></td>
                                        <td align='left'>
                                            <?php if (empty($status_approval) or $status_approval == 'PROCESS') { ?>
                                                <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addEntry0" title="Tambah Sub Entry"><i class="fa fa-plus"></i></button>
                                            <?php } ?>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="addEntry0" role="dialog">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Tambah Entry</h4>
                                                </div>
                                                <?php echo form_open('manage/raks_entry/add_entry', array('method' => 'post')); ?>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <?php
                                                            $idMajor  = $s['m'];
                                                            $idPeriod = $s['p'];
                                                            $kodeHeader = $this->db->query("SELECT COUNT(parent) as totalParent FROM raks_entry WHERE parent = 0 AND majors_id = '$idMajor' AND period_id = '$idPeriod'")->row();
                                                            $kodeParent = $kodeHeader->totalParent + 1;
                                                            ?>
                                                            <div class="form-group">
                                                                <label>Kode</label>
                                                                <input type="text" required="" readonly="" name="kode" class="form-control" value="<?php echo $kodeParent ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nama</label>
                                                                <input type="text" required="" name="nama" class="form-control" placeholder="Masukkan Keterangan">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="hidden" required="" name="parent" class="form-control" value="0">
                                                                <input type="hidden" required="" name="majors_id" class="form-control" value="<?php echo $s['m'] ?>">
                                                                <input type="hidden" required="" name="period_id" class="form-control" value="<?php echo $s['p'] ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Simpan</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                                <?php echo form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                foreach ($raks_entry as $main) {
                                ?>
                                    <tr style="font-weight: bold;">
                                        <td hidden="true"></td>
                                        <td hidden="true" class="kode-entry"><?php echo $main->kode; ?></td>
                                        <td><?php echo $main->indented . "" . $main->kode; ?></td>
                                        <td class="nama-entry"><?php echo $main->nama; ?></td>
                                        <td class="total-entry"><?php echo 'Rp. ' . number_format($main->totalPrice, 0, ',', '.')  ?></td>
                                        <!-- <td><?php echo $main->proposal_status; ?></td> -->
                                        <td align='left'>
                                            <?php if ($main->approval_status != 'APPROVED') { ?>
                                                <?php if ($main->proposal_status == 'EMPTY' and $main->approval_status == 'PROCESS') { ?>
                                                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addEntry<?php echo $main->id ?>" title="Tambah Sub Entry"><i class="fa fa-plus"></i></button>
                                                <?php } ?>

                                                <?php if (empty($status_approval) or $status_approval == 'PROCESS') { ?>
                                                    <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editEntry<?php echo $main->id ?>" title="Edit Entry"><i class="fa fa-pencil"></i></button>
                                                <?php } ?>

                                                <?php if ((empty($status_approval) or $status_approval == 'PROCESS') and $main->has_child == 0) { ?>
                                                    <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteEntry<?php echo $main->id ?>" title="Delete Entry"><i class="fa fa-trash"></i></button>
                                                <?php } ?>

                                                <?php if ($main->has_child == 0) { ?>
                                                    <button type="button" class="btn btn-info btn-xs settingProposal" data-id="<?php echo $main->id ?>" title="Proposal"><i class="fa fa-cog"></i></button>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="addEntry<?php echo $main->id ?>" role="dialog">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Tambah Sub Entry</h4>
                                                </div>
                                                <?php echo form_open('manage/raks_entry/add_entry', array('method' => 'post')); ?>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <?php
                                                            $totalSub = $this->db->query("SELECT COUNT(parent) as totalParent FROM raks_entry WHERE parent = '$main->id'")->row();
                                                            $subParent = $totalSub->totalParent + 1;
                                                            ?>
                                                            <div class="form-group">
                                                                <label>Kode</label>
                                                                <input type="text" required="" readonly="" name="kode" class="form-control" value="<?php echo $main->kode . "." . $subParent ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nama</label>
                                                                <input type="text" required="" name="nama" class="form-control" placeholder="Nama/Keterangan">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="hidden" required="" name="parent" class="form-control" value="<?php echo $main->id ?>">
                                                                <input type="hidden" required="" name="majors_id" class="form-control" value="<?php echo $s['m'] ?>">
                                                                <input type="hidden" required="" name="period_id" class="form-control" value="<?php echo $s['p'] ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Simpan</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                                <?php echo form_close(); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="editEntry<?php echo $main->id ?>" role="dialog">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Edit Entry</h4>
                                                </div>
                                                <?php echo form_open('manage/raks_entry/edit_entry', array('method' => 'post')); ?>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>Kode</label>
                                                                <input type="text" required="" readonly="" name="kode" class="form-control" value="<?php echo $main->kode ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nama</label>
                                                                <input type="text" required="" name="nama" class="form-control" value="<?php echo $main->nama; ?>" placeholder="Masukkan Nama/Keterangan">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="hidden" required="" name="id_entry" class="form-control" value="<?php echo $main->id ?>">
                                                                <input type="hidden" required="" name="majors_id" class="form-control" value="<?php echo $s['m'] ?>">
                                                                <input type="hidden" required="" name="period_id" class="form-control" value="<?php echo $s['p'] ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Simpan</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                                <?php echo form_close(); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal modal-default fade" id="deleteEntry<?php echo $main->id ?>">
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
                                                    <?php echo form_open('manage/raks_entry/delete/' . $main->id); ?>
                                                    <input type="hidden" name="delCode" value="<?php echo $main->kode; ?>">
                                                    <input type="hidden" required="" name="majors_id" class="form-control" value="<?php echo $s['m'] ?>">
                                                    <input type="hidden" required="" name="period_id" class="form-control" value="<?php echo $s['p'] ?>">
                                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
                                                    <button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
                                                    <?php echo form_close(); ?>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div>
                    <?php echo $this->pagination->create_links(); ?>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<!-- modal tambah data  start -->
<div class="modal fade" id="modalSettingProposal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <section class="panel panel-primary">
                <header class="panel-heading">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h2 class="panel-title">RAKS DETAIL<br>
                        <span id="judul-entry"></span>
                    </h2>
                </header>
                <div class="panel-body">
                    <!-- Tab links -->
                    <div class="tab">
                        <button disabled class="tablinks" onclick="openTab(event, 'inputData')">Input Data</button>
                        <button disabled class="tablinks" onclick="openTab(event, 'proposal')">Proposal</button>
                    </div>

                    <div id="inputData" class="tabcontent" style="display: block;">
                        <div id="detailArea">
                            <div class="row">
                                <div id="formInputData" class="col-md-6">
                                    <b>
                                        <h4>Input Data</h4>
                                    </b>
                                    <div class="row">
                                        <div class="col-md-12">
                                            Inventory Code
                                            <input type="hidden" name="entry_id" id="entryId" class="form-control" />
                                            <input type="hidden" name="detail_id" id="detailId" class="form-control" />
                                            <input type="text" name="inventoryCode" id="inventoryCode" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            Nama
                                            <input type="text" name="detailName" id="detailName" class="form-control" />
                                        </div>
                                        <div class="col-md-6">
                                            Volume
                                            <input type="text" name="volumeDetail" id="volumeDetail" class="nominalProcess form-control" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            QTY
                                            <input type="text" name="qty" id="qty" class="nominalProcess form-control" />
                                        </div>
                                        <div class="col-md-6">
                                            Unit Price
                                            <input type="text" name="unitPrice" id="unitPrice" class="nominalProcess form-control" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            Total Price
                                            <input readonly type="text" name="totalPrice" id="totalPrice" class="form-control" />
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <b>
                                        <h4>Source</h4>
                                    </b>
                                    <div class="row" style="margin: 2px;">
                                        <div>
                                            <select id="sourceName">
                                                <?php foreach ($grade as $key => $value) { ?>
                                                    <option value="<?php echo $value ?>"><?php echo $value ?></option>
                                                <?php } ?>
                                            </select>
                                            <!-- <input type="text" id="sourceName" placeholder="Nama Sumber Dana"> -->
                                            <input style="width: 100px;" type="text" id="sourceTotal" placeholder="Total Dana">
                                            <button id="addSource" class="btn-success">Add Source</button>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-right: 2px;">
                                        <div style="min-height: 135px; max-height: 200px; overflow-y: auto;" class="col-md-12">
                                            <table class="table table-bordered dataTable table-hover table-striped no-footer">
                                                <thead>
                                                    <tr>
                                                        <th>Nama</th>
                                                        <th>Total</th>
                                                        <th width="50px"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="listSource">


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        Total Source
                                        <input readonly type="text" id="totalSource" class="form-control" />
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="row">
                            <div id="divExecutionTime" class="col-md-12">
                                <b>
                                    <h4>Execution</h4>
                                </b>
                                <div class="row">
                                    <div class="col-md-2">
                                        <span> <input type="checkbox" class="checkExecution" id="bulan1" name="execution[]" value="1"> Juli <?php echo $periodStart; ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span> <input type="checkbox" class="checkExecution" id="bulan2" name="execution[]" value="2"> Agustus <?php echo $periodStart; ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span> <input type="checkbox" class="checkExecution" id="bulan3" name="execution[]" value="3"> September <?php echo $periodStart; ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span> <input type="checkbox" class="checkExecution" id="bulan4" name="execution[]" value="4"> Oktober <?php echo $periodStart; ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span> <input type="checkbox" class="checkExecution" id="bulan5" name="execution[]" value="5"> November <?php echo $periodStart; ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span> <input type="checkbox" class="checkExecution" id="bulan6" name="execution[]" value="6"> Desember <?php echo $periodStart; ?></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <span> <input type="checkbox" class="checkExecution" id="bulan7" name="execution[]" value="7"> Januari <?php echo $periodEnd; ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span> <input type="checkbox" class="checkExecution" id="bulan8" name="execution[]" value="8"> Februari <?php echo $periodEnd; ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span> <input type="checkbox" class="checkExecution" id="bulan9" name="execution[]" value="9"> Maret <?php echo $periodEnd; ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span> <input type="checkbox" class="checkExecution" id="bulan10" name="execution[]" value="10"> April <?php echo $periodEnd; ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span> <input type="checkbox" class="checkExecution" id="bulan11" name="execution[]" value="11"> Mei <?php echo $periodEnd; ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span> <input type="checkbox" class="checkExecution" id="bulan12" name="execution[]" value="12"> Juni <?php echo $periodEnd; ?></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <br />

                        <?php if (empty($status_approval) or $status_approval == 'PROCESS') { ?>
                            <button type="button" class="btn btn-primary" id="simpanDetailEntry">
                                <i class="fa fa-plus"></i> Simpan Detail Entry
                            </button>

                            <button onclick="resetInputs()" type="button" class="btn btn-warning" id="resetDetailEntry">
                                <i class="fa fa-eraser"></i> Reset Form
                            </button>
                        <?php } ?>

                    </div>
                    <div id="proposal" class="tabcontent">
                        <div class="row">
                            <div id="information" class="col-md-6">
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="width: 35%;vertical-align: top;">RAKS Rincian</td>
                                        <td style="width: 5%;vertical-align: top;">:</td>
                                        <td style="width: 60%;vertical-align: top;" id="judul-rincian"></td>
                                        <input type="hidden" id="nama-rincian" value="">
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px;">Nomor Permintaan</td>
                                        <td style="padding-top: 5px;">:</td>
                                        <td style="padding-top: 5px;" id="no-permintaan"></td>
                                        <input type="hidden" id="noPermintaan-prop" value="">
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px;">Tanggal Permintaan</td>
                                        <td style="padding-top: 5px;">:</td>
                                        <td style="padding-top: 5px;" id="tglPermintaanProposal">2024-10-10</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px;">Target Date</td>
                                        <td style="padding-top: 5px;">:</td>
                                        <td style="padding-top: 5px;"><input type="date" name="targetDate" id="targetDate"></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px;">Request Type</td>
                                        <td style="padding-top: 5px;">:</td>
                                        <td style="padding-top: 5px;">
                                            <select name="requestType" id="requestType">
                                                <option value="PROPOSAL">Proposal</option>
                                                <option value="BARANG">Permintaan Barang</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px;">Cost Center</td>
                                        <td style="padding-top: 5px;">:</td>
                                        <td style="padding-top: 5px;">
                                            <select name="costCenter" id="costCenter">
                                                <?php foreach ($majors as $row) { ?>
                                                    <option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;padding-top: 5px;">Notes</td>
                                        <td style="vertical-align: top;padding-top: 5px;">:</td>
                                        <td style="padding-top: 5px;">
                                            <textarea style="width: 100%;height:100px;" name="requestNotes" id="requestNotes"></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="fileAttachment" class="col-md-6">
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="width: 20%; vertical-align:top;">Attachment 1</td>
                                        <td style="width: 5%; vertical-align:top;">:</td>
                                        <td style="width: 75%;"><input type="file" name="attachment1" id="attachment1"> <span id="isi-attachment1"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px;vertical-align:top;">Attachment 2</td>
                                        <td style="padding-top: 5px;vertical-align:top;">:</td>
                                        <td style="padding-top: 5px;"><input type="file" name="attachment2" id="attachment2"><span id="isi-attachment2"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px;vertical-align:top;">Attachment 3</td>
                                        <td style="padding-top: 5px;vertical-align:top;">:</td>
                                        <td style="padding-top: 5px;"><input type="file" name="attachment3" id="attachment3"><span id="isi-attachment3"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px;vertical-align:top;">Attachment 4</td>
                                        <td style="padding-top: 5px;vertical-align:top;">:</td>
                                        <td style="padding-top: 5px;"><input type="file" name="attachment4" id="attachment4"><span id="isi-attachment4"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px;vertical-align:top;">Attachment 5</td>
                                        <td style="padding-top: 5px;vertical-align:top;">:</td>
                                        <td style="padding-top: 5px;"><input type="file" name="attachment5" id="attachment5"><span id="isi-attachment5"></span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <br />

                        <span id="buttonHapus"></span>
                        <span id="buttonSimpan"></span>
                        <span id="buttonSubmit"></span>
                        <span id="buttonApprove"></span>
                        <span id="buttonDisapprove"></span>

                    </div>
                </div>

                <br />
                <div class="row" style="margin: 2px;">
                    <!-- Tombol Kirim Banyak -->
                    <div class="col-md-12 mb-2">
                        <button type="button" class="btn btn-info" id="btnKirimBanyak">
                            <i class="fa fa-paper-plane"></i> Kirim Banyak
                        </button>
                    </div>
                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered table-hover table-striped dataTable no-footer" id="ListCoa">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkAllDetail"></th> <!-- Checkbox untuk select all -->
                                    <th>Nama</th>
                                    <th>QTY</th>
                                    <th>Vol</th>
                                    <th>Unit Cost</th>
                                    <th>Total</th>
                                    <th>Balance</th>
                                    <th width="100px"></th>
                                </tr>
                            </thead>
                            <tbody id="listEntryDetail">
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
        <!-- <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-success modal-confirm" type="submit" id="submitform">Simpan</button>
                        </div>
                    </div>
                </footer> -->
        </section>
    </div>
</div>
</div>
<!-- modal tambah data  end -->

<!-- Modal Send Detail -->
<div class="modal fade" id="modalSendDetail" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Request Detail<br>
                    <span id="judul-send"></span>
                </h4>
            </div>
            <?php echo form_open('manage/raks_entry/sendRequest', array('method' => 'post', 'id' => 'formSendRequest')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>No Permintaan</label>
                            <input type="hidden" required="" name="detail_id" id="idDetailPermintaan" class="form-control">
                            <input type="hidden" required="" name="status" id="statusRequest" class="form-control" value="NEW">
                            <input type="hidden" required="" name="majors_id" id="majorsRequest" class="form-control" value="<?php echo $s['m'] ?>">
                            <input type="hidden" required="" name="period_id" id="periodRequest" class="form-control" value="<?php echo $s['p'] ?>">
                            <input type="text" required name="noPermintaan" id="noPermintaan" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Inventory Code</label>
                            <input id="send-inv-code" type="text" readonly class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama</label>
                            <input id="send-inv-name" type="text" readonly class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>QTY</label>
                            <input id="send-inv-qty" type="text" readonly class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Volume</label>
                            <input id="send-inv-volume" type="text" readonly class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Unit Price</label>
                            <input id="send-inv-unitPrice" type="text" readonly class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Total Price</label>
                            <input id="send-inv-totalPrice" type="text" readonly class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Kirim</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!-- Modal Send Detail -->

<!-- Modal Send Detail -->
<div class="modal fade" id="modalSendDetailBatch" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Kirim Detail Entry<br>
                    <span id="judul-send-batch"></span>
                </h4>
            </div>
            <?php echo form_open('manage/raks_entry/sendRequestBatch', array('method' => 'post', 'id' => 'formSendBatch')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>No Permintaan</label>
                            <input type="hidden" required="" name="statusBatch" id="statusBatch" class="form-control" value="NEW">
                            <input type="hidden" required="" name="majorsBatch" id="majorsBatch" class="form-control" value="<?php echo $s['m'] ?>">
                            <input type="hidden" required="" name="periodBatch" id="periodBatch" class="form-control" value="<?php echo $s['p'] ?>">
                            <input type="text" required name="noPermintaanBatch" id="noPermintaanBatch" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Kirim</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!-- Modal Send Detail -->

<!-- Modal delete detail -->
<div class="modal modal-default fade" id="modalDeleteDetail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi penghapusan</h3>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin akan menghapus data beserta rinciannya?</p>
            </div>
            <div class="modal-footer">
                <?php echo form_open('manage/raks_entry/deleteDetail', array('method' => 'post', 'id' => 'formDeleteDetail')); ?>
                <input type="hidden" name="del_id" id="delID">
                <input type="hidden" required="" name="majors_id" class="form-control" value="<?php echo $s['m'] ?>">
                <input type="hidden" required="" name="period_id" class="form-control" value="<?php echo $s['p'] ?>">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
                <button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
                <?php echo form_close(); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- MOdal delete detail -->

<!-- Modal disapprove -->
<div class="modal modal-default fade" id="modalDisapprove">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi Disapprove Proposal</h3>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin akan menolak permintaan ini? <br> <span><i class="fa fa-exclamation-triangle" style="color: orange;"></i></span><b> Anda bisa menambahkan catatan/alasan pada kolom Notes</b></p>
            </div>
            <div class="modal-footer">
                <?php echo form_open('manage/raks_entry/disapproveRequest'); ?>
                <input type="hidden" name="detail_id" id="idDetailDisapprove" value="">
                <input type="hidden" name="noPermintaan" id="noDisapprove" value="">
                <input type="hidden" name="notes" id="noteDisapprove" value="">
                <input type="hidden" required="" name="majors_id" class="form-control" value="<?php echo $s['m'] ?>">
                <input type="hidden" required="" name="period_id" class="form-control" value="<?php echo $s['p'] ?>">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
                <button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Disapprove</button>
                <?php echo form_close(); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Modal disapprove -->

<script type="text/javascript">
    var idEntry = 0;
    var judulEntry = '';
    var listDetailEntri = [];

    $(document).ready(function() {
        const inputs = ['#qty', '#volumeDetail', '#unitPrice', '#sourceTotal'];

        inputs.forEach(id => {
            const el = document.querySelector(id);
            if (el) {
                el.addEventListener('input', function() {
                    this.value = formatAngka(this.value);
                });
            }
        });

        function formatAngka(value) {
            return value.replace(/\D/g, '')
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        $('#formDeleteDetail').on('submit', function(e) {
            e.preventDefault(); // cegah redirect

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // respon dari controller, misal angka 1 untuk sukses
                    if (response == 1) {
                        // tutup modal, refresh data, tampilkan notifikasi, dll
                        $('#modalDeleteDetail').modal('hide'); // contoh jika pakai Bootstrap modal
                        getEntryDetail(idEntry); // misal kamu ingin refresh tabel detail
                    } else {
                        alert('Gagal menghapus data.');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menghapus.');
                }
            });
        });

        $('#formSendRequest').on('submit', function(e) {
            e.preventDefault(); // cegah redirect

            let idDetailRequest = $("#idDetailPermintaan").val();
            let noPermintaanRequest = $("#noPermintaan").val();
            let statusRequest = $("#statusRequest").val();
            let majorsRequest = $("#majorsRequest").val();
            let periodRequest = $("#periodRequest").val();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: {
                    detail_id: idDetailRequest,
                    noPermintaan: noPermintaanRequest,
                    status: statusRequest,
                    majors_id: majorsRequest,
                    period_id: periodRequest
                },
                success: function(response) {
                    if (response == 1) {
                        $('#modalSendDetail').modal('hide');
                        getEntryDetail(idEntry);
                    } else {
                        alert('Gagal mengirim request.');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat kirim request.');
                }
            });
        });
    });


    $('.settingProposal').on('click', function() {
        var id = $(this).attr('data-id');
        idEntry = id;

        var row = $(this).closest("tr");
        var kodeEntry = row.find(".kode-entry").text();
        var namaEntry = row.find(".nama-entry").text();
        judulEntry = kodeEntry + " " + namaEntry;
        $("#judul-entry").html('[' + judulEntry + ']');
        $("#judul-rincian").html(judulEntry);
        $("#nama-rincian").val(namaEntry);

        getEntryDetail(id);
        $('#modalSettingProposal').modal('show');
    });

    $('#checkAllDetail').on('change', function() {
        let isChecked = $(this).is(':checked');
        $('.row-check-detail').prop('checked', isChecked);
    });

    function getEntryDetail(id) {
        resetInputs();

        let table = document.getElementById('listEntryDetail');
        table.innerHTML = '';

        let listSource = document.getElementById('listSource');
        listSource.innerHTML = '';
        openTab(event, 'inputData');

        $.ajax({
            url: '<?php echo base_url(); ?>manage/raks_entry/getDetail/' + id,
            type: 'GET',
            beforeSend: function() {
                listDetailEntri = [];
            },
            success: function(res) {
                var data = JSON.parse(res);
                // console.log(data);
                if (data.length > 0) {
                    for (let i = 0; i < data.length; i++) {
                        var dEntry = {
                            "idEntry": parseInt(data[i].entry_id),
                            "idDetail": parseInt(data[i].id),
                            "inventoryCode": data[i].inventoryCode,
                            "detailName": data[i].nama,
                            "detailQty": parseInt(data[i].qty),
                            "detailVolume": parseInt(data[i].volume),
                            "detailUnitPrice": parseInt(data[i].unitPrice),
                            "detailTotalPrice": parseInt(data[i].totalPrice),
                            "dataSource": data[i].dataSource,
                            "executionTime": data[i].executionTime,
                            "status": data[i].status,
                            "noPermintaan": data[i].noPermintaan,
                        }
                        listDetailEntri.push(dEntry);
                        var btn = generateBtn(dEntry.idDetail, dEntry.status)
                        let row = table.insertRow();
                        var ceklist = `<input type="checkbox" class="row-check-detail" data-id="${dEntry.idDetail}">`;
                        if (dEntry.status != 'CREATED') {
                            ceklist = '';
                        }
                        row.innerHTML = `
                            <td style="text-align:center;">${ceklist}</td>
                            <td hidden="true" class="detail-code-${dEntry.idDetail}">${dEntry.inventoryCode}</td>
                            <td hidden="true" class="detail-noPermintaan-${dEntry.idDetail}">${dEntry.noPermintaan}</td>
                            <td class="detail-nama-${dEntry.idDetail}">${dEntry.detailName}</td>
                            <td class="detail-qty-${dEntry.idDetail}" style="text-align:right;">${formatAngka(dEntry.detailQty)}</td>
                            <td class="detail-volume-${dEntry.idDetail}" style="text-align:right;">${formatAngka(dEntry.detailVolume)}</td>
                            <td class="detail-unitPrice-${dEntry.idDetail}" style="text-align:right;">${formatRupiah(dEntry.detailUnitPrice)}</td>
                            <td class="detail-totalPrice-${dEntry.idDetail}" style="text-align:right;">${formatRupiah(dEntry.detailTotalPrice)}</td>
                            <td style="text-align:right;">${formatRupiah(dEntry.detailTotalPrice)}</td>
                            ${btn}
                        `;
                    }
                }
            },
            error: function(msg) {
                alert('msg');
            }

        });
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }

    function formatAngka(angka) {
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(Number(angka)); // konversi ke number
    }

    function unformatAngka(formatted) {
        return parseInt(formatted.replace(/[^0-9]/g, ''), 10);
    }

    function generateBtn(id, status) {
        var btn = `<td>`;
        if (status != 'SUBMITED' && status != 'APPROVED') {
            btn += ` 
                <button type="button" class="btn btn-danger btn-xs" onclick="deleteDetail(${id})" title="Hapus Detail Entry"><i class="fa fa-trash"></i></button>
                <button type="button" class="btn btn-warning btn-xs" onclick="editDetail(${id})" title="Edit Detail Entry"><i class="fa fa-pencil"></i></button>`;
        }

        if (status == 'CREATED') {
            btn += ` <button type="button" class="btn btn-info btn-xs" onclick="sendDetail(${id})" title="Kirim Detail Entry"><i class="fa fa-send"></i></button>`;
        }

        if (status != 'CREATED') {
            btn += ` <button type="button" class="btn btn-success btn-xs" onclick="proposalDetail(${id},'${status}')" title="Proposal"><i class="fa fa-book"></i></button>`;
        }
        btn += `</td>`;

        return btn;
    }

    function sendDetail(id) {
        $('#idDetailPermintaan').val(id);
        let invCode = $('.detail-code-' + id).text();
        let invName = $('.detail-nama-' + id).text();
        let invQty = unformatAngka($('.detail-qty-' + id).text());
        let invVolume = unformatAngka($('.detail-volume-' + id).text());
        let invUnitPrice = unformatAngka($('.detail-unitPrice-' + id).text());
        let invTotalPrice = unformatAngka($('.detail-totalPrice-' + id).text());

        $("#send-inv-code").val(invCode);
        $("#send-inv-name").val(invName);
        $("#send-inv-qty").val(formatAngka(invQty));
        $("#send-inv-volume").val(formatAngka(invVolume));
        $("#send-inv-unitPrice").val(formatAngka(invUnitPrice));
        $("#send-inv-totalPrice").val(formatAngka(invTotalPrice));

        $('#modalSendDetail').modal('show');
    }

    $('#btnKirimBanyak').on('click', function() {
        const selected = $('.row-check-detail:checked');

        if (selected.length === 0) {
            alert('Silakan centang setidaknya satu data untuk dikirim.');
            return;
        }

        $('#modalSendDetailBatch').modal('show');
    });

    $('#formSendBatch').on('submit', function(e) {
        e.preventDefault(); // cegah redirect

        let noPermintaanBatch = $("#noPermintaanBatch").val();
        let statusBatch = $("#statusBatch").val();
        let majorsBatch = $("#majorsBatch").val();
        let periodBatch = $("#periodBatch").val();
        const listDataDetail = getDataTercentang();

        console.log(listDataDetail);

        // Contoh kirim AJAX ke server
        $.ajax({
            url: '<?php echo base_url(); ?>manage/raks_entry/sendRequestBatch',
            type: 'POST',
            data: {
                noPermintaan: noPermintaanBatch,
                status: statusBatch,
                majors_id: majorsBatch,
                period_id: periodBatch,
                listEntry: listDataDetail
            },
            success: function(res) {
                if (res == 1) {
                    getEntryDetail(idEntry);
                    $('#modalSendDetailBatch').modal('hide');
                }
                // console.log(res);
                // alert('Data berhasil dikirim!');
                // getEntryDetail(idEntry); // misalnya untuk reload tabel
            },
            error: function() {
                alert('Terjadi kesalahan saat mengirim.');
            }
        });
    });

    function getDataTercentang() {
        let dataTerpilih = [];

        $('.row-check-detail:checked').each(function() {
            let id = $(this).data('id');

            let invCode = $('.detail-code-' + id).text().trim();
            let invName = $('.detail-nama-' + id).text().trim();
            let invQty = unformatAngka($('.detail-qty-' + id).text().trim());
            let invVolume = unformatAngka($('.detail-volume-' + id).text().trim());
            let invUnitPrice = unformatAngka($('.detail-unitPrice-' + id).text().trim());
            let invTotalPrice = unformatAngka($('.detail-totalPrice-' + id).text().trim());

            dataTerpilih.push({
                id: id,
                invCode: invCode,
                invName: invName,
                invQty: invQty,
                invVolume: invVolume,
                invUnitPrice: invUnitPrice,
                invTotalPrice: invTotalPrice
            });
        });

        return dataTerpilih;
    }


    $('#simpanDetailEntry').on('click', function() {
        var idDetail = $('#detailId').val();
        var inputData = getInputData();
        var sumberDana = getNamaSumber();
        var jumlahDana = getTotalSumber();
        var executionTime = getExecutionTime();

        var totalPrice = unformatAngka($('#totalPrice').val());
        var totalSource = generateTotalSumber();

        // console.log(inputData);
        // console.log(sumberDana);
        // console.log(jumlahDana);
        // console.log(executionTime);

        if (parseInt(totalPrice) != totalSource) {
            alert('Maaf Total Price dan Total Sorce tidak balanced');
        } else {
            $.ajax({
                url: '<?php echo base_url(); ?>manage/raks_entry/saveDetail',
                type: 'POST',
                data: {
                    entry_id: idEntry,
                    detail_id: idDetail,
                    majors_id: <?php echo $s['m'] ?? 0 ?>,
                    period_id: <?php echo $s['p'] ?? 0 ?>,
                    dataEntry: inputData,
                    sourceName: sumberDana,
                    sourceValue: jumlahDana,
                    executionTime: executionTime
                },
                success: function(res) {
                    if (res == 1) {
                        getEntryDetail(idEntry);
                        // location.reload();
                    }
                    // console.log(JSON.parse(res));
                },
                error: function(msg) {
                    alert('msg');
                }

            });
        }
    });

    $('.nominalProcess').on('change', function() {
        var qty = $('#qty').val() == '' ? 0 : $('#qty').val();

        var volume = $('#volumeDetail').val() == '' ? 0 : unformatAngka($('#volumeDetail').val());
        var unitPrice = $('#unitPrice').val() == '' ? 0 : unformatAngka($('#unitPrice').val());
        var totalPrice = parseInt(qty) * parseInt(volume) * parseInt(unitPrice);
        // console.log(totalPrice);
        if (totalPrice > 0) {
            $('#totalPrice').val(formatAngka(totalPrice));
            console.log(totalPrice);
        }
    });

    document.getElementById('addSource').addEventListener('click', function() {
        let name = document.getElementById('sourceName').value;
        let total = unformatAngka(document.getElementById('sourceTotal').value);

        let namaSource = getNamaSumber();
        let result = namaSource.find(function(item) {
            return item === name;
        });
        if (result === undefined) {
            result = '';
        } else {
            alert('Maaf, sumber tersebut sudah ada. Silahkan hapus atau ganti total.')
        }

        if (name && total && result == '') {
            let table = document.getElementById('listSource');
            let row = table.insertRow();
            row.innerHTML = `
            <td><input readonly type="text" name="namaSumber[]" value="${name}"></td>
            <td><input type="text" class="jumlahDana" name="totalSumber[]" value="${formatAngka(total)}"></td>
            <td>
                <button class="deleteSource btn-xs btn-danger"><i class="fa fa-trash"></i></button>
            </td>
        `;
            document.getElementById('sourceName').value = '';
            document.getElementById('sourceTotal').value = '';
            addListeners();

            generateTotalSumber();
        }
    });

    function addListeners() {
        // Delete Function
        document.querySelectorAll('.deleteSource').forEach(function(btn) {
            btn.addEventListener('click', function() {
                this.closest('tr').remove();
                generateTotalSumber();
            });
        });

        document.querySelectorAll('.jumlahDana').forEach(function(btn) {
            btn.addEventListener('change', function() {
                generateTotalSumber();
            });
        });
    }

    // Add initial listeners for existing buttons
    addListeners();

    function getInputData() {
        let form = document.getElementById('formInputData');
        let formData = {};

        form.querySelectorAll('input').forEach(function(input) {
            let name = input.name;
            let value = input.value;

            // Bersihkan format ribuan jika field terkait uang/angka
            if (['qty', 'unitPrice', 'totalPrice', 'volumeDetail'].includes(name)) {
                value = value.replace(/\./g, '');
            }

            formData[name] = value;
        });

        return formData;
    }

    function getNamaSumber() {
        let sumberInputs = document.querySelectorAll('input[name="namaSumber[]"]');
        let sumberValues = [];

        sumberInputs.forEach(function(input) {
            sumberValues.push(input.value);
        });

        return sumberValues;
    }

    function generateTotalSumber() {
        let sumberInputs = document.querySelectorAll('input[name="totalSumber[]"]');
        let total = 0;

        sumberInputs.forEach(function(input) {
            total += parseInt(unformatAngka(input.value));
        });

        $('#totalSource').val(formatAngka(total));
        return total;
    }

    function getTotalSumber() {
        let sumberInputs = document.querySelectorAll('input[name="totalSumber[]"]');
        let sumberValues = [];

        sumberInputs.forEach(function(input) {
            sumberValues.push(parseInt(unformatAngka(input.value)));
        });

        return sumberValues;
    }

    function getExecutionTime() {
        let sumberInputs = document.querySelectorAll('input[name="execution[]"]');
        let executionTime = [];

        sumberInputs.forEach(function(input) {
            if (input.checked) {
                executionTime.push(input.value);
            }
        });

        return executionTime;
    }

    function editDetail(id) {
        resetInputs();
        let listSource = document.getElementById('listSource');
        listSource.innerHTML = '';

        openTab(event, 'inputData');

        var result = listDetailEntri.find(function(item) {
            return item.idDetail === id;
        });

        $('#detailId').val(id);

        $('#inventoryCode').val(result.inventoryCode);
        $('#detailName').val(result.detailName);
        $('#qty').val(formatAngka(result.detailQty));
        $('#volumeDetail').val(formatAngka(result.detailVolume));
        $('#unitPrice').val(formatAngka(result.detailUnitPrice));
        $('#totalPrice').val(formatAngka(result.detailTotalPrice));
        $('#totalSource').val(formatAngka(result.detailTotalPrice));

        var dataSource = result.dataSource;
        var source = dataSource.split('[--]').map(function(item) {
            return item.split('[-]');
        });

        let table = document.getElementById('listSource');

        for (let index = 0; index < source.length; index++) {
            let nama = source[index][0];
            let nominal = source[index][1];

            let row = table.insertRow();
            row.innerHTML = `
            <td><input type="text" name="namaSumber[]" value="${nama}"></td>
            <td><input type="text" class="jumlahDana" name="totalSumber[]" value="${formatAngka(nominal)}"></td>
            <td>
                <button class="deleteSource btn-xs btn-danger"><i class="fa fa-trash"></i></button>
            </td>
        `;

        }
        addListeners();

        var executionTime = result.executionTime;
        var check = executionTime.split(',');

        for (let i = 0; i < check.length; i++) {
            let bulan = "bulan" + check[i];
            var checkbox = document.getElementById(bulan); // Ambil elemen checkbox berdasarkan id
            checkbox.checked = true;
        }

    }

    function deleteDetail(id) {
        $("#delID").val(id);
        $("#modalDeleteDetail").modal('show');
    }

    function proposalDetail(id, status) {
        $('#detailId').val(id);
        let noPermintaan = $('.detail-noPermintaan-' + id).text();
        $('#no-permintaan').text(noPermintaan);
        $('#noPermintaan-prop').val(noPermintaan);

        $("#buttonHapus").html('');
        $("#buttonSimpan").html('');
        $("#buttonSubmit").html('');
        $("#buttonApprove").html('');
        $("#buttonDisapprove").html('');
        // console.log(noPermintaan)
        if (status == "NEW") {
            let btnDelete = `<button type="button" class="btn btn-danger" data-detail="${id}" data-permintaan="${noPermintaan}" id="hapusProposal">Hapus</button>`;
            $("#buttonHapus").html(btnDelete);
            let btnSimpan = `<button type="button" class="btn btn-primary" id="simpanProposal">Update Proposal</button>`;
            $("#buttonSimpan").html(btnSimpan);
            let btnSubmit = `<button type="button" class="btn btn-warning" data-detail="${id}" data-permintaan="${noPermintaan}" id="submitProposal">Submit Proposal</button>`;
            $("#buttonSubmit").html(btnSubmit);
        }

        if (status == "SUBMITED") {
            let btnApprove = `<button type="button" class="btn btn-success" data-detail="${id}">Proposal Telah Dikirim</button>`;
            $("#buttonApprove").html(btnApprove);
        } else if (status == "KEPALA SEKOLAH") {
            let btnApprove = `<button type="button" class="btn btn-success" data-detail="${id}">Proposal Disetujui Kepala Sekolah</button>`;
            $("#buttonApprove").html(btnApprove);
        } else if (status == "KEPALA AKADEMIK") {
            let btnApprove = `<button type="button" class="btn btn-success" data-detail="${id}">Proposal Disetujui Kepala Akademik</button>`;
            $("#buttonApprove").html(btnApprove);
        } else if (status == "FINANCE") {
            let btnApprove = `<button type="button" class="btn btn-success" data-detail="${id}">Proposal Disetujui Finance</button>`;
            $("#buttonApprove").html(btnApprove);
        }

        if (noPermintaan != '') {
            $.ajax({
                url: '<?php echo base_url(); ?>manage/raks_entry/requestProposal',
                type: 'POST',
                data: {
                    idDetail: id,
                    noPermintaan: noPermintaan,
                },
                success: function(res) {
                    // console.log(JSON.parse(res));
                    var proposal = JSON.parse(res);
                    $("#tglPermintaanProposal").text(proposal.tanggalPermintaan);
                    $("#targetDate").val(proposal.targetDate);
                    $("#requestNotes").val(proposal.notes);
                    $("#requestType").val(proposal.requestType).trigger('change');

                    if (proposal.attachment1 != null) {
                        let btnView = `<a target="_blank" href="<?php echo base_url(); ?>/uploads/raks/${proposal.attachment1}"><span><i class="fa fa-eye"></i></span></a> `;
                        $("#isi-attachment1").html(btnView + proposal.attachment1);
                    } else {
                        $("#isi-attachment1").html('');
                    }
                    if (proposal.attachment2 != null) {
                        let btnView2 = `<a target="_blank" href="<?php echo base_url(); ?>/uploads/raks/${proposal.attachment2}"><span><i class="fa fa-eye"></i></span></a> `;
                        $("#isi-attachment2").html(btnView2 + proposal.attachment2);
                    } else {
                        $("#isi-attachment2").html('');
                    }
                    if (proposal.attachment3 != null) {
                        let btnView3 = `<a target="_blank" href="<?php echo base_url(); ?>/uploads/raks/${proposal.attachment3}"><span><i class="fa fa-eye"></i></span></a> `;
                        $("#isi-attachment3").html(btnView3 + proposal.attachment3);
                    } else {
                        $("#isi-attachment3").html('');
                    }
                    if (proposal.attachment4 != null) {
                        let btnView4 = `<a target="_blank" href="<?php echo base_url(); ?>/uploads/raks/${proposal.attachment4}"><span><i class="fa fa-eye"></i></span></a> `;
                        $("#isi-attachment4").html(btnView4 + proposal.attachment4);
                    } else {
                        $("#isi-attachment4").html('');
                    }
                    if (proposal.attachment5 != null) {
                        let btnView5 = `<a target="_blank" href="<?php echo base_url(); ?>/uploads/raks/${proposal.attachment5}"><span><i class="fa fa-eye"></i></span></a> `;
                        $("#isi-attachment5").html(btnView5 + proposal.attachment5);
                    } else {
                        $("#isi-attachment5").html('');
                    }
                },
                error: function(msg) {
                    alert('msg');
                }

            });
        }

        openTab(event, 'proposal');
    }

    function resetInputs() {
        var inputsData = document.querySelectorAll('#formInputData input');

        inputsData.forEach(function(input) {
            input.value = "";
        });

        var inputsExecution = document.querySelectorAll('#divExecutionTime input');

        inputsExecution.forEach(function(input) {
            input.checked = false;
        });

        let listSource = document.getElementById('listSource');
        listSource.innerHTML = '';
        document.getElementById("totalSource").value = "";
    }

    function openTab(evt, tabName) {
        console.log(123);
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    $(document).on('click', '#simpanProposal', function() {
        saveRequest();
    });

    $(document).on('click', '#hapusProposal', function() {
        var idDetail = $(this).attr("data-detail");
        var noPer = $(this).attr("data-permintaan");
        if (confirm("Apakah Anda yakin ingin menghapus proposal ini?")) {
            deleteRequest(idDetail, noPer);
        }
    });

    $(document).on('click', '#submitProposal', function() {
        var idDetail = $(this).attr("data-detail");
        var noPer = $(this).attr("data-permintaan");
        if (confirm("Apakah Anda yakin ingin mengirim proposal ini?")) {
            submitRequest(idDetail, noPer);
        }
    });

    $(document).on('click', '#approveProposal', function() {
        var idDetail = $(this).attr("data-detail");
        if (confirm("Apakah Anda yakin ingin menyetujui proposal ini?")) {
            approveRequest(idDetail);
        }
    });

    $(document).on('click', '#disapproveProposal', function() {
        var idDetail = $(this).attr("data-detail");
        $('#idDetailDisapprove').val(idDetail);
        var requestNotes = $('#requestNotes').val();
        $('#noteDisapprove').val(requestNotes);
        var noper = $('#no-permintaan').text();
        $('#noDisapprove').val(noper);
        $('#modalDisapprove').modal('show');
    });

    function saveRequest() {
        var entryId = $('#entryId').val();
        var detail_id = $('#detailId').val();
        var noPermintaan = $('#noPermintaan-prop').val();
        var targetDate = $('#targetDate').val();
        var requestType = $('#requestType').val();
        var costCenter = $('#costCenter').val();
        var attachment1 = $('#attachment1')[0].files[0];
        var attachment2 = $('#attachment2')[0].files[0];
        var attachment3 = $('#attachment3')[0].files[0];
        var attachment4 = $('#attachment4')[0].files[0];
        var attachment5 = $('#attachment5')[0].files[0];
        var requestNotes = $('#requestNotes').val();
        var namaRincian = $('#nama-rincian').val();
        var formData = new FormData();

        formData.append('detail_id', detail_id);
        formData.append('majors_id', <?php echo $s['m'] ?>);
        formData.append('period_id', <?php echo $s['p'] ?>);
        formData.append('noPermintaan', noPermintaan);
        formData.append('targetDate', targetDate);
        formData.append('requestType', requestType);
        formData.append('costCenter', costCenter);
        formData.append('attachment1', attachment1);
        formData.append('attachment2', attachment2);
        formData.append('attachment3', attachment3);
        formData.append('attachment4', attachment4);
        formData.append('attachment5', attachment5);
        formData.append('notes', requestNotes);
        formData.append('namaRincian', namaRincian);

        $.ajax({
            method: "POST",
            url: '<?php echo base_url(); ?>manage/raks_entry/saveRequest',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {

            },
            success: function(res) {
                // console.log(res);
                location.reload();
            }
        });
    }

    function deleteRequest(detail_id, nomor) {
        dataReq = {
            idDetail: detail_id,
            noPermintaan: nomor
        };

        $.ajax({
            method: "POST",
            url: '<?php echo base_url(); ?>manage/raks_entry/deleteRequest',
            data: dataReq,
            beforeSend: function() {

            },
            success: function(res) {
                var dataRes = JSON.parse(res);
                // console.log(dataRes)
                if (dataRes.status) {
                    location.reload();
                }
            }
        });
    }

    function submitRequest() {
        var entryId = $('#entryId').val();
        var detail_id = $('#detailId').val();
        var noPermintaan = $('#noPermintaan-prop').val();
        var targetDate = $('#targetDate').val();
        var requestType = $('#requestType').val();
        var costCenter = $('#costCenter').val();
        var attachment1 = $('#attachment1')[0].files[0];
        var attachment2 = $('#attachment2')[0].files[0];
        var attachment3 = $('#attachment3')[0].files[0];
        var attachment4 = $('#attachment4')[0].files[0];
        var attachment5 = $('#attachment5')[0].files[0];
        var requestNotes = $('#requestNotes').val();
        var namaRincian = $('#nama-rincian').val();
        var submitStatus = 1;

        var formData = new FormData();
        formData.append('detail_id', detail_id);
        formData.append('majors_id', <?php echo $s['m'] ?>);
        formData.append('period_id', <?php echo $s['p'] ?>);
        formData.append('noPermintaan', noPermintaan);
        formData.append('targetDate', targetDate);
        formData.append('requestType', requestType);
        formData.append('costCenter', costCenter);
        formData.append('attachment1', attachment1);
        formData.append('attachment2', attachment2);
        formData.append('attachment3', attachment3);
        formData.append('attachment4', attachment4);
        formData.append('attachment5', attachment5);
        formData.append('notes', requestNotes);
        formData.append('namaRincian', namaRincian);
        formData.append('submitStatus', submitStatus);

        $.ajax({
            method: "POST",
            url: '<?php echo base_url(); ?>manage/raks_entry/saveRequest',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {

            },
            success: function(res) {
                // console.log(res);
                location.reload();
            }
        });
    }

    function approveRequest(detail_id) {
        dataReq = {
            idDetail: detail_id
        };

        $.ajax({
            method: "POST",
            url: '<?php echo base_url(); ?>manage/raks_entry/approveRequest',
            data: dataReq,
            beforeSend: function() {

            },
            success: function(res) {
                var dataRes = JSON.parse(res);
                // console.log(dataRes)
                if (dataRes.status) {
                    location.reload();
                }
            }
        });
    }
</script>