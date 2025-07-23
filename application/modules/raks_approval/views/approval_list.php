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
                                        <select style="width: 200px;" id="majorsRaks" name="m" class="form-control" required>
                                            <option value="">--- Pilih Unit ---</option>
                                            <?php foreach ($majors as $row) { ?>
                                                <option data-type="<?php echo $row['sekolah_id']; ?>" value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
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
                        <input type="hidden" id="typeRaks" value="<?php echo $typeRaks ?>">
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
                                <?php if (!empty($s['m']) and !empty($s['p'])) { ?>
                                    <tr style="font-weight: bold;">
                                        <td hidden="true"></td>
                                        <td hidden="true" class="kode-entry"></td>
                                        <td colspan="2">RAKS <?php echo $dataPeriod['period_start'] . '/' . $dataPeriod['period_end'] . " - " . $dataMajors['majors_short_name'] ?></td>

                                        <td>Rp. <?php echo number_format($total_anggaran ?? 0, 0, ',', '.')  ?></td>
                                        <td align='left'>
                                            <?php if (empty($status_approval) or $status_approval == 'PROCESS') { ?>
                                                <!-- <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addEntry0" title="Tambah Sub Entry"><i class="fa fa-plus"></i></button> -->
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
                                                    <!-- <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addEntry<?php echo $main->id ?>" title="Tambah Sub Entry"><i class="fa fa-plus"></i></button> -->
                                                <?php } ?>

                                                <?php if (empty($status_approval) or $status_approval == 'PROCESS') { ?>
                                                    <!-- <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editEntry<?php echo $main->id ?>" title="Edit Entry"><i class="fa fa-pencil"></i></button> -->
                                                <?php } ?>

                                                <?php if ((empty($status_approval) or $status_approval == 'PROCESS') and $main->has_child == 0) { ?>
                                                    <!-- <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteEntry<?php echo $main->id ?>" title="Delete Entry"><i class="fa fa-trash"></i></button> -->
                                                <?php } ?>

                                                <?php if ($main->has_child == 0 and $main->has_non_created > 0) { ?>
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
                                            <input type="number" name="volumeDetail" id="volumeDetail" class="nominalProcess form-control" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            QTY
                                            <input type="number" name="qty" id="qty" class="nominalProcess form-control" />
                                        </div>
                                        <div class="col-md-6">
                                            Unit Price
                                            <input type="number" name="unitPrice" id="unitPrice" class="nominalProcess form-control" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            Total Price
                                            <input readonly type="number" name="totalPrice" id="totalPrice" class="form-control" />
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <b>
                                        <h4>Source</h4>
                                    </b>
                                    <div class="row" style="margin: 2px;">
                                        <div>
                                            <!-- <select id="sourceName"> -->
                                            <?php foreach ($grade as $key => $value) { ?>
                                                <!-- <option value="<?php echo $value ?>"><?php echo $value ?></option> -->
                                            <?php } ?>
                                            <!-- </select> -->
                                            <!-- <input type="text" id="sourceName" placeholder="Nama Sumber Dana"> -->
                                            <!-- <input style="width: 100px;" type="number" id="sourceTotal" placeholder="Total Dana">
                                            <button id="addSource" class="btn-success">Add Source</button> -->
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
                                        <input readonly type="number" id="totalSource" class="form-control" />
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
                            <!-- <button type="button" class="btn btn-primary" id="simpanDetailEntry">
                                <i class="fa fa-plus"></i> Simpan Detail Entry
                            </button> -->
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
                                        <td style="padding-top: 5px;"><input type="date" name="targetDate" id="targetDate" readonly></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px;">Request Type</td>
                                        <td style="padding-top: 5px;">:</td>
                                        <td style="padding-top: 5px;">
                                            <select name="requestType" id="requestType" disabled>
                                                <option value="PROPOSAL">Proposal</option>
                                                <option value="BARANG">Permintaan Barang</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px;">Cost Center</td>
                                        <td style="padding-top: 5px;">:</td>
                                        <td style="padding-top: 5px;">
                                            <select name="costCenter" id="costCenter" disabled>
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
                                        <td style="width: 75%;"><span id="isi-attachment1"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px;vertical-align:top;">Attachment 2</td>
                                        <td style="padding-top: 5px;vertical-align:top;">:</td>
                                        <td style="padding-top: 5px;"><span id="isi-attachment2"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px;vertical-align:top;">Attachment 3</td>
                                        <td style="padding-top: 5px;vertical-align:top;">:</td>
                                        <td style="padding-top: 5px;"><span id="isi-attachment3"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px;vertical-align:top;">Attachment 4</td>
                                        <td style="padding-top: 5px;vertical-align:top;">:</td>
                                        <td style="padding-top: 5px;"><span id="isi-attachment4"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px;vertical-align:top;">Attachment 5</td>
                                        <td style="padding-top: 5px;vertical-align:top;">:</td>
                                        <td style="padding-top: 5px;"><span id="isi-attachment5"></span></td>
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

                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered table-hover table-striped dataTable no-footer" id="ListCoa">
                            <thead>
                                <tr>
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
            <?php echo form_open('manage/raks_entry/sendRequest', array('method' => 'post')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>No Permintaan</label>
                            <input type="hidden" required="" name="detail_id" id="idDetailPermintaan" class="form-control">
                            <input type="hidden" required="" name="status" class="form-control" value="NEW">
                            <input type="hidden" required="" name="majors_id" class="form-control" value="<?php echo $s['m'] ?>">
                            <input type="hidden" required="" name="period_id" class="form-control" value="<?php echo $s['p'] ?>">
                            <input type="text" required name="noPermintaan" class="form-control">
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
                            <input id="send-inv-qty" type="number" readonly class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Volume</label>
                            <input id="send-inv-volume" type="number" readonly class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Unit Price</label>
                            <input id="send-inv-unitPrice" type="number" readonly class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Total Price</label>
                            <input id="send-inv-totalPrice" type="number" readonly class="form-control">
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
                <?php echo form_open('manage/raks_entry/deleteDetail', array('method' => 'post')); ?>
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
                <?php echo form_open('manage/raks_approval/disapproveRequest', array('method' => 'post', 'id' => 'formDisapproveRequest')); ?>
                <input type="hidden" name="typeRaks" value="<?php echo $typeRaks ?>">
                <input type="hidden" name="detail_id" id="idDetailDisapprove" value="">
                <input type="hidden" name="detail_status" id="statusDetailDisapprove" value="">
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
    var typeRaks = '';

    $(document).ready(function() {
        typeRaks = $('#typeRaks').val();

        $('#formDisapproveRequest').on('submit', function(e) {
            e.preventDefault(); // cegah submit biasa

            const form = $(this);
            const url = form.attr('action');
            const formData = form.serialize(); // ambil semua input

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(res) {
                    var response = JSON.parse(res);
                    // console.log(response);
                    // console.log(123);
                    // Jika server return 1, maka dianggap sukses
                    if (response.status == 'success') {
                        proposalDetail(response.detailId, response.detailStatus);
                        getEntryDetail(idEntry);
                        alert('Berhasil di-disapprove.');
                        $('#modalDisapprove').modal('hide');
                    } else {
                        $('#modalDisapprove').modal('hide');
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat mengirim data.');
                    console.error(error);
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
                        row.innerHTML = `
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

    function generateBtn(id, status) {
        var btn = `<td>`;
        // if (status != 'SUBMITED' && status != 'APPROVED') {
        //     btn += ` 
        //         <button type="button" class="btn btn-danger btn-xs" onclick="deleteDetail(${id})" title="Hapus Detail Entry"><i class="fa fa-trash"></i></button>
        //         <button type="button" class="btn btn-warning btn-xs" onclick="editDetail(${id})" title="Edit Detail Entry"><i class="fa fa-pencil"></i></button>`;
        // }

        // if (status == 'CREATED') {
        //     btn += ` <button type="button" class="btn btn-info btn-xs" onclick="sendDetail(${id})" title="Kirim Detail Entry"><i class="fa fa-send"></i></button>`;
        // }
        let approvalRole = [];
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
        let invQty = $('.detail-qty-' + id).text();
        let invVolume = $('.detail-volume-' + id).text();
        let invUnitPrice = $('.detail-unitPrice-' + id).text();
        let invTotalPrice = $('.detail-totalPrice-' + id).text();

        $("#send-inv-code").val(invCode);
        $("#send-inv-name").val(invName);
        $("#send-inv-qty").val(invQty);
        $("#send-inv-volume").val(invVolume);
        $("#send-inv-unitPrice").val(invUnitPrice);
        $("#send-inv-totalPrice").val(invTotalPrice);

        $('#modalSendDetail').modal('show');
    }

    $('#simpanDetailEntry').on('click', function() {
        var idDetail = $('#detailId').val();
        var inputData = getInputData();
        var sumberDana = getNamaSumber();
        var jumlahDana = getTotalSumber();
        var executionTime = getExecutionTime();

        var totalPrice = $('#totalPrice').val();
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
                        location.reload();
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
        var volume = $('#volumeDetail').val() == '' ? 0 : $('#volumeDetail').val();
        var unitPrice = $('#unitPrice').val() == '' ? 0 : $('#unitPrice').val();
        var totalPrice = parseInt(qty) * parseInt(volume) * parseInt(unitPrice);
        // console.log(totalPrice);
        if (totalPrice > 0) {
            $('#totalPrice').val(totalPrice);
        }
    });

    // document.getElementById('addSource').addEventListener('click', function() {
    //     let name = document.getElementById('sourceName').value;
    //     let total = document.getElementById('sourceTotal').value;

    //     let namaSource = getNamaSumber();
    //     let result = namaSource.find(function(item) {
    //         return item === name;
    //     });
    //     if (result === undefined) {
    //         result = '';
    //     } else {
    //         alert('Maaf, sumber tersebut sudah ada. Silahkan hapus atau ganti total.')
    //     }

    //     if (name && total && result == '') {
    //         let table = document.getElementById('listSource');
    //         let row = table.insertRow();
    //         row.innerHTML = `
    //         <td><input readonly type="text" name="namaSumber[]" value="${name}"></td>
    //         <td><input type="text" class="jumlahDana" name="totalSumber[]" value="${total}"></td>
    //         <td>
    //             <button class="deleteSource btn-xs btn-danger"><i class="fa fa-trash"></i></button>
    //         </td>
    //     `;
    //         document.getElementById('sourceName').value = '';
    //         document.getElementById('sourceTotal').value = '';
    //         addListeners();

    //         generateTotalSumber();
    //     }
    // });

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
            formData[input.name] = input.value;
        });

        let jsonData = JSON.stringify(formData);

        return JSON.parse(jsonData);
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
            total += parseInt(input.value);
        });

        $('#totalSource').val(total);
        return total;
    }

    function getTotalSumber() {
        let sumberInputs = document.querySelectorAll('input[name="totalSumber[]"]');
        let sumberValues = [];

        sumberInputs.forEach(function(input) {
            sumberValues.push(parseInt(input.value));
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

                    var disType = '';
                    if (proposal.requestType == 'BARANG') {
                        disType = 'Permintaan Barang';
                    } else if (proposal.requestType == 'PROPOSAL') {
                        disType = 'Proposal';
                    }

                    if (status == "NEW") {
                        let btnApprove = ``;
                        $("#buttonApprove").html(btnApprove);
                        let btnDisapprove = ``;
                        $("#buttonDisapprove").html(btnDisapprove);
                    }

                    // Type approval berdasarkan type RAKS. Unit atau Divisi
                    if (typeRaks == '1') {
                        if (status == "SUBMITED") {
                            let btnApprove = `<button type="button" class="btn btn-success" data-detail="${id}" data-status="${status}" id="approveProposal">Approve Kepala Sekolah</button>`;
                            $("#buttonApprove").html(btnApprove);
                            let btnDisapprove = `<button type="button" class="btn btn-danger" data-detail="${id}" data-status="${status}" id="disapproveProposal">Disapprove ${disType}</button>`;
                            $("#buttonDisapprove").html(btnDisapprove);
                        } else if (status == "KEPALA SEKOLAH") {
                            let btnApprove = `<button type="button" class="btn btn-success" data-detail="${id}" data-status="${status}" id="approveProposal">Approve Kepala Akademik</button>`;
                            $("#buttonApprove").html(btnApprove);
                            let btnDisapprove = `<button type="button" class="btn btn-danger" data-detail="${id}" data-status="${status}" id="disapproveProposal">Disapprove ${disType}</button>`;
                            $("#buttonDisapprove").html(btnDisapprove);
                        } else if (status == "KEPALA AKADEMIK") {
                            let btnApprove = `<button type="button" class="btn btn-success" data-detail="${id}" data-status="${status}" id="approveProposal">Approve Finance</button>`;
                            $("#buttonApprove").html(btnApprove);
                            let btnDisapprove = `<button type="button" class="btn btn-danger" data-detail="${id}" data-status="${status}" id="disapproveProposal">Disapprove ${disType}</button>`;
                            $("#buttonDisapprove").html(btnDisapprove);
                        }
                    } else if (typeRaks == '0') {
                        if (status == "SUBMITED") {
                            let btnApprove = `<button type="button" class="btn btn-success" data-detail="${id}" data-status="${status}" id="approveProposal">Approve Kepala Divisi</button>`;
                            $("#buttonApprove").html(btnApprove);
                            let btnDisapprove = `<button type="button" class="btn btn-danger" data-detail="${id}" data-status="${status}" id="disapproveProposal">Disapprove ${disType}</button>`;
                            $("#buttonDisapprove").html(btnDisapprove);
                        } else if (status == "KEPALA DIVISI") {
                            let btnApprove = `<button type="button" class="btn btn-success" data-detail="${id}" data-status="${status}" id="approveProposal">Approve Kepala Finance</button>`;
                            $("#buttonApprove").html(btnApprove);
                            let btnDisapprove = `<button type="button" class="btn btn-danger" data-detail="${id}" data-status="${status}" id="disapproveProposal">Disapprove ${disType}</button>`;
                            $("#buttonDisapprove").html(btnDisapprove);
                        }
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
    }

    function openTab(evt, tabName) {
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


    $(document).on('click', '#approveProposal', function() {
        var idDetail = $(this).attr("data-detail");
        var statusDetail = $(this).attr("data-status");
        if (confirm("Apakah Anda yakin ingin menyetujui proposal ini?")) {
            approveRequest(idDetail, statusDetail);
        }
    });

    $(document).on('click', '#disapproveProposal', function() {
        var idDetail = $(this).attr("data-detail");
        var statusDetail = $(this).attr("data-status");

        $('#idDetailDisapprove').val(idDetail);
        $('#statusDetailDisapprove').val(statusDetail);

        console.log(statusDetail);

        var requestNotes = $('#requestNotes').val();
        $('#noteDisapprove').val(requestNotes);
        var noper = $('#no-permintaan').text();
        $('#noDisapprove').val(noper);

        $('#modalDisapprove').modal('show');
    });

    function approveRequest(detail_id, detail_status) {
        dataReq = {
            typeRaks: typeRaks,
            idDetail: detail_id,
            status: detail_status
        };

        $.ajax({
            method: "POST",
            url: '<?php echo base_url(); ?>manage/raks_approval/approveRequest',
            data: dataReq,
            beforeSend: function() {

            },
            success: function(res) {
                var dataRes = JSON.parse(res);
                console.log(dataRes)
                if (dataRes.status == 'success') {
                    let idStatus = dataRes.detailId;
                    let detailStatus = dataRes.detailStatus;
                    proposalDetail(idStatus, detailStatus);
                    // location.reload();
                } else if ((dataRes.status == 'failed')) {
                    alert(dataRes.message);
                }
            }
        });
    }
</script>