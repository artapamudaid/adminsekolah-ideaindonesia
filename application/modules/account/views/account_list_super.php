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
        $assignPosButton = false;
        $disabledAssignPosButton = "";
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-6">
                                <?php if ($majors_id == '0') { ?>
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
                                    <div class="box-body table-responsive">
                                        <table>
                                            <tr>
                                                <td>
                                                    <select style="width: 200px;" id="m" name="m" class="form-control" required>
                                                        <option value="">--- Pilih Unit Pesantren ---</option>
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
                                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <?php echo form_close(); ?>
                                <?php } ?>
                            </div>
                            <div class="col-md-6" style="margin-top: 10px;">
                                <?php
                                if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all'):
                                ?>
                                    <div class="pull-right">
                                        <button type="button" id="btn-show-multi-delete" class="btn btn-danger">
                                            <i class="fa fa-trash"></i>&nbsp; Hapus Data Terpilih
                                        </button>
                                        <a href="<?= site_url('manage/account/import_account?m=' . $s['m']) ?>" class="btn btn-info">
                                            <i class="fa fa-upload"></i>&nbsp;
                                            Upload Akun Biaya
                                        </a>
                                    </div>
                                <?php
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <?php echo form_open('manage/account/delete_multi', array('id' => 'form-multi-delete')); ?>
                    <input type="hidden" name="majors_id" value="<?php echo isset($s['m']) ? $s['m'] : ''; ?>">
                    <div class="box-body table-responsive">
                        <table id='dtable-coa' class='table table-hover table-responsive'>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="check-all"></th>
                                    <th>No</th>
                                    <th width="15%">Kode Akun</th>
                                    <th>Nama Akun</th>
                                    <th>Keterangan</th>
                                    <th>Jenis Akun</th>
                                    <th>Kategori</th>
                                    <th>Unit Pesantren</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($majors_id == 0) {
                                    if (isset($s['m'])) {
                                        $majorsID = $s['m'];
                                        $piece = "account_majors_id = '$majorsID' AND";
                                    } else {
                                        $piece = "";
                                    }
                                } else {
                                    $piece = "account_majors_id = '$majors_id' AND";
                                }

                                $attribute = "";
                                if (@$s['m'] != 'all') {
                                    $attribute = "readonly ";
                                    $attribute .= "style='pointer-events: none;'";
                                }

                                $sql_main = "SELECT * FROM account LEFT JOIN majors ON majors.majors_id = account.account_majors_id WHERE (account_note = '0' OR account_note IS NULL) ORDER BY account_code ASC";
                                $main_account = $this->db->query($sql_main)->result();
                                $no = 1;
                                foreach ($main_account as $main) {

                                    $main_id = $main->account_id;
                                    $sql_sub = "SELECT * FROM account LEFT JOIN majors ON majors.majors_id = account.account_majors_id WHERE $piece account_note = '$main_id' ORDER BY account_code ASC";
                                    $sub_account = $this->db->query($sql_sub)->result();

                                    if (count($sub_account) > 0) {
                                ?>
                                        <tr style="font-weight: bold;">
                                            <td class="text-center">
                                                <?php if ($main->account_id > 7): ?>
                                                    <input type="checkbox" class="check-item" name="account_id[]" value="<?php echo $main->account_id ?>" data-id="<?php echo $main->account_id ?>" data-parent="<?php echo $main->account_note ?>">
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center"><?php echo $no++ ?></td>
                                            <td><?php echo $main->account_code ?></td>
                                            <td><?php echo $main->account_description ?></td>
                                            <td><?php echo ($main->account_status == 0) ? 'Header' : 'Non-Header'  ?></td>
                                            <td>Akun Utama</td>
                                            <td>
                                                <?php
                                                if ($main->account_category == '1') {
                                                    echo "Pembayaran";
                                                } else if ($main->account_category == '2') {
                                                    echo "Keuangan";
                                                } else {
                                                    echo "#";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo ($main->account_majors_id != "0") ? $main->majors_short_name : "Semua Unit" ?></td>
                                            <td align='left'>
                                                <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addAccount<?php echo $main->account_id ?>" title="Tambah Sub Akun"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="addAccount<?php echo $main->account_id ?>" role="dialog">
                                            <div class="modal-dialog modal-md">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Tambah Sub Akun</h4>
                                                    </div>
                                                    <?php echo form_open('manage/account/add_glob', array('method' => 'post')); ?>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <?php
                                                                $main_code = $this->db->query("SELECT MAX(account_code) as maxMain FROM account WHERE account_note = '$main_id'")->row();
                                                                $subParams = substr($main_code->maxMain, 2);
                                                                if ($subParams > 0) {
                                                                    $subFront = substr($main_code->maxMain, 0, 2);
                                                                    $subBack = substr($main_code->maxMain, 2);
                                                                    $subAccount = $subBack + 100;
                                                                } else {
                                                                    $subFront = substr($main->account_code, 0, 2);
                                                                    $subBack = substr($main->account_code, 2);
                                                                    $subAccount = $subBack + 100;
                                                                }
                                                                ?>
                                                                <div class="form-group">
                                                                    <label>Kode Akun</label>
                                                                    <input type="text" required="" readonly="" name="account_code" class="form-control" value="<?php echo $subFront . $subAccount ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Keterangan</label>
                                                                    <input type="text" required="" name="account_description" class="form-control" placeholder="Masukkan Keterangan">
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="hidden" required="" name="account_note" class="form-control" value="<?php echo $main->account_id ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                    <select required="" name="account_majors_id" class="form-control" <?= $attribute ?>>
                                                                        <option value="">-Pilih Unit Pesantren-</option>
                                                                        <?php foreach ($majors as $row) {
                                                                            $selected = ($s['m'] == $row['majors_id']) ? 'selected' : '';
                                                                        ?>
                                                                            <option value="<?php echo $row['majors_id']; ?>" <?= $selected ?>><?php echo $row['majors_short_name'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
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
                                        foreach ($sub_account as $sub) {

                                            $sub_id = $sub->account_id;
                                            $sql_child = "SELECT * FROM account LEFT JOIN majors ON majors.majors_id = account.account_majors_id WHERE $piece account_note = '$sub_id' ORDER BY account_code ASC";
                                            $child_account = $this->db->query($sql_child)->result();

                                            if (count($child_account) > 0) {
                                        ?>
                                                <tr style="font-weight: bold;">
                                                    <td class="text-center">
                                                        <input type="checkbox" class="check-item" name="account_id[]" value="<?php echo $sub->account_id ?>" data-id="<?php echo $sub->account_id ?>" data-parent="<?php echo $sub->account_note ?>">
                                                    </td>
                                                    <td class="text-center"><?php echo $no++ ?></td>
                                                    <td>&nbsp;&nbsp;<?php echo $sub->account_code ?></td>
                                                    <td><?php echo $sub->account_description ?></td>
                                                    <td><?php echo ($sub->account_status == 0) ? 'Header' : 'Non-Header'  ?></td>
                                                    <td>Sub Akun 1</td>
                                                    <td>
                                                        <?php
                                                        if ($sub->account_category == '1') {
                                                            echo "Pembayaran";
                                                        } else if ($sub->account_category == '2') {
                                                            echo "Keuangan";
                                                        } else {
                                                            echo "#";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo ($sub->account_majors_id != "0") ? $sub->majors_short_name : "Semua Unit" ?></td>
                                                    <td align='left'>
                                                        <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addSubAccount<?php echo $sub->account_id ?>" title="Tambah Sub Akun"><i class="fa fa-plus"></i></button>
                                                        <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editSubAccount<?php echo $sub->account_id ?>" title="Edit Sub Akun"><i class="fa fa-pencil"></i></button>
                                                    </td>
                                                </tr>
                                                <div class="modal fade modal-add-sub" id="addSubAccount<?php echo $sub->account_id ?>" role="dialog" data-unit-name="<?php
                                                                                                                                                                        if (isset($s['m']) && $s['m'] != '') {
                                                                                                                                                                            $uNameRow = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '" . $s['m'] . "'")->row();
                                                                                                                                                                            if ($uNameRow) echo $uNameRow->majors_short_name;
                                                                                                                                                                        }
                                                                                                                                                                        ?>">
                                                    <div class="modal-dialog modal-md">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Tambah Sub Akun</h4>
                                                            </div>
                                                            <?php echo form_open('manage/account/add_glob', array('method' => 'post', 'class' => 'form-multi-step')); ?>
                                                            <div class="modal-body">
                                                                <?php
                                                                $sub_code = $this->db->query("SELECT MAX(account_code) as maxSub FROM account WHERE account_note = '$sub_id'")->row();
                                                                $childParams = substr($sub_code->maxSub, 2);
                                                                if ($childParams > 0) {
                                                                    $childFront = substr($sub_code->maxSub, 0, 2);
                                                                    $childBack = substr($sub_code->maxSub, 2);
                                                                    $childAccount = $childBack + 1;
                                                                } else {
                                                                    $childFront = substr($sub->account_code, 0, 2);
                                                                    $childBack = substr($sub->account_code, 2);
                                                                    $childAccount = $childBack + 1;
                                                                }

                                                                $is_expense = (substr($childFront, 0, 1) === '4'); // Akun Biaya
                                                                ?>

                                                                <div class="step-section step-1" data-step="1">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <?php if ($is_expense): ?>
                                                                                <p class="text-info step-indicator"><strong>Langkah 1 dari 3: Data Akun</strong></p>
                                                                            <?php endif; ?>
                                                                            <div class="form-group">
                                                                                <label>Kode Akun</label>
                                                                                <input type="text" required="" readonly="" name="account_code" class="form-control" value="<?php echo $childFront . $childAccount ?>">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Keterangan</label>
                                                                                <input type="text" required="" name="account_description" class="form-control input-account-desc" placeholder="Masukkan Keterangan">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <input type="hidden" required="" name="account_note" class="form-control" value="<?php echo $sub->account_id ?>">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Kategori <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                <select required="" name="account_category" class="form-control select-category" data-is-expense="<?php echo $is_expense ? '1' : '0' ?>">
                                                                                    <option value="">-Pilih Kategori-</option>
                                                                                    <option value="1">Pembayaran</option>
                                                                                    <option value="2">Keuangan</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                <select required="" name="account_majors_id" class="form-control select-unit" <?= $attribute ?>>
                                                                                    <option value="">-Pilih Unit Pesantren-</option>
                                                                                    <?php foreach ($majors as $row) {
                                                                                        $selected = ($s['m'] == $row['majors_id']) ? 'selected' : '';
                                                                                    ?>
                                                                                        <option value="<?php echo $row['majors_id']; ?>" <?= $selected ?>>
                                                                                            <?php echo $row['majors_short_name'] ?>
                                                                                        </option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <?php if ($is_expense): ?>
                                                                    <div class="step-section step-2" data-step="2" style="display: none;">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <p class="text-info step-indicator"><strong>Langkah 2 dari 3: Data POS</strong></p>
                                                                                <div class="form-group">
                                                                                    <label>Akun Piutang <span class="text-danger">*</span></label>
                                                                                    <select name="piutang_account_id" class="form-control input-piutang">
                                                                                        <option value="">-Pilih Akun Piutang-</option>
                                                                                        <?php
                                                                                        $piutangMajors = ($sub->account_majors_id != '0') ? "AND account_majors_id = '" . $sub->account_majors_id . "'" : "";
                                                                                        $piutangs = $this->db->query("SELECT * FROM account WHERE account_code LIKE '1%' AND account_description LIKE '%Piutang%' AND account_category = '2' $piutangMajors")->result();
                                                                                        foreach ($piutangs as $row):
                                                                                        ?>
                                                                                            <option value="<?php echo $row->account_id ?>"><?php echo $row->account_code . ' - ' . $row->account_description ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Nama POS <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="pos_name" class="form-control input-pos-name" placeholder="Tulis Nama POS">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Keterangan <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="pos_description" class="form-control input-pos-desc" placeholder="Tulis Keterangan POS">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="step-section step-3" data-step="3" style="display: none;">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <p class="text-info step-indicator"><strong>Langkah 3 dari 3: Data Jenis Bayar</strong></p>
                                                                                <div class="form-group">
                                                                                    <label>Tahun Ajaran <span class="text-danger">*</span></label>
                                                                                    <select name="period_id" class="form-control input-period">
                                                                                        <option value="">-Pilih Tahun Ajaran-</option>
                                                                                        <?php
                                                                                        $periods = $this->db->query("SELECT * FROM period WHERE period_status = '1'")->result();
                                                                                        foreach ($periods as $row) :
                                                                                        ?>
                                                                                            <option value="<?php echo $row->period_id; ?>" selected><?php echo $row->period_start . '/' . $row->period_end; ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group div-payment-type">
                                                                                    <label>Tipe <span class="text-danger">*</span></label>
                                                                                    <select name="payment_type" class="form-control select-payment-type">
                                                                                        <option value="BULAN">Bulanan</option>
                                                                                        <option value="BEBAS">Bebas</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group toggle-by-type">
                                                                                    <label>Model Pembayaran <span class="text-danger">*</span></label>
                                                                                    <div class="radio">
                                                                                        <label><input type="radio" name="paymentModes" value="TETAP" checked> Tetap</label>
                                                                                    </div>
                                                                                    <div class="radio">
                                                                                        <label><input type="radio" name="paymentModes" value="TIDAK TETAP"> Tidak Tetap</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group toggle-by-type">
                                                                                    <label>Apakah Item Paket Pembayaran <span class="text-danger">*</span></label>
                                                                                    <br>
                                                                                    <span class="help">Pilih Ya jika item ini merupakan bagian dari paket pembayaran</span>
                                                                                    <div class="radio">
                                                                                        <label><input type="radio" name="paymentIsBatchs" value="1" checked> Ya</label>
                                                                                    </div>
                                                                                    <div class="radio">
                                                                                        <label><input type="radio" name="paymentIsBatchs" value="0"> Tidak</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default pull-left btn-prev" style="display: none;">Sebelumnya</button>
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-primary btn-next" style="display: none;">Selanjutnya</button>
                                                                <button type="submit" class="btn btn-success btn-simpan">Simpan</button>
                                                            </div>
                                                            <?php echo form_close(); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="editSubAccount<?php echo $sub->account_id ?>" role="dialog">
                                                    <div class="modal-dialog modal-md">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Edit Sub Akun</h4>
                                                            </div>
                                                            <?php echo form_open('manage/account/add', array('method' => 'post')); ?>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-8">
                                                                        <div class="form-group">
                                                                            <input type="hidden" required="" name="account_id" class="form-control" value="<?php echo $sub->account_id ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Kode Akun</label>
                                                                            <input type="text" required="" readonly="" name="account_code" class="form-control" placeholder="Masukkan Kode Akun" value="<?php echo $sub->account_code ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Keterangan</label>
                                                                            <input type="text" required="" name="account_description" class="form-control" placeholder="Masukkan Keterangan" value="<?php echo $sub->account_description ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <input type="hidden" required="" name="account_note" class="form-control" value="<?php echo $sub->account_note ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                            <select required="" name="account_majors_id" class="form-control" <?= $attribute ?>>
                                                                                <option value="">-Pilih Unit Pesantren-</option>
                                                                                <?php foreach ($majors as $row) { ?>
                                                                                    <option value="<?php echo $row['majors_id']; ?>" <?php echo ($sub->account_majors_id == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
                                                                                <?php } ?>
                                                                            </select>
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
                                                foreach ($child_account as $child) {

                                                    $child_id = $child->account_id;
                                                    $sql_grandchild = "SELECT * FROM account LEFT JOIN majors ON majors.majors_id = account.account_majors_id WHERE $piece account_note = '$child_id' ORDER BY account_code ASC";
                                                    $grandchild_account = $this->db->query($sql_grandchild)->result();

                                                    if (count($grandchild_account) > 0) {
                                                ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <input type="checkbox" class="check-item" name="account_id[]" value="<?php echo $child->account_id ?>" data-id="<?php echo $child->account_id ?>" data-parent="<?php echo $child->account_note ?>">
                                                            </td>
                                                            <td class="text-center"><?php echo $no++ ?></td>
                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child->account_code ?></td>
                                                            <td><?php echo $child->account_description ?></td>
                                                            <td><?php echo ($child->account_status == 0) ? 'Header' : 'Non-Header'  ?></td>
                                                            <td>Sub Akun 2</td>
                                                            <td>
                                                                <?php
                                                                if ($child->account_category == '1') {
                                                                    echo "Pembayaran";
                                                                } else if ($child->account_category == '2') {
                                                                    echo "Keuangan";
                                                                } else {
                                                                    echo "#";
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><?php echo ($child->account_majors_id != "0") ? $child->majors_short_name : "Semua Unit" ?></td>
                                                            <td align='left'>
                                                                <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addChildAccount<?php echo $child->account_id ?>" title="Tambah Sub Akun"><i class="fa fa-plus"></i></button>
                                                                <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editChildAccount<?php echo $child->account_id ?>" title="Edit Sub Akun"><i class="fa fa-pencil"></i></button>
                                                            </td>
                                                        </tr>

                                                        <div class="modal fade" id="addChildAccount<?php echo $child->account_id ?>" role="dialog">
                                                            <div class="modal-dialog modal-md">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        <h4 class="modal-title">Tambah Sub Akun</h4>
                                                                    </div>
                                                                    <?php echo form_open('manage/account/add', array('method' => 'post')); ?>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-8">
                                                                                <?php
                                                                                //$childID = $child->account_id;
                                                                                $child_code = $this->db->query("SELECT MAX(account_code) as maxChild FROM account WHERE account_note = '$child_id'")->row();
                                                                                $grandchildParams = substr($child_code->maxChild, 2);
                                                                                if ($grandchildParams > 0) {
                                                                                    $grandchildFront = substr($child_code->maxChild, 0, 2);
                                                                                    $grandchildBack = substr($child_code->maxChild, 2);
                                                                                    $grandchildAccount = $grandchildBack + '.01';
                                                                                } else {
                                                                                    $grandchildFront = substr($child->account_code, 0, 2);
                                                                                    $grandchildBack = substr($child->account_code, 2);
                                                                                    $grandchildAccount = $grandchildBack + '.01';
                                                                                }
                                                                                ?>
                                                                                <div class="form-group">
                                                                                    <label>Kode Akun</label>
                                                                                    <input type="text" required="" readonly="" name="account_code" class="form-control" placeholder="Masukkan Kode Akun" value="<?php echo $grandchildFront . $grandchildAccount ?>">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Keterangan</label>
                                                                                    <input type="text" required="" name="account_description" class="form-control" placeholder="Masukkan Keterangan">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <input type="hidden" required="" name="account_note" class="form-control" value="<?php echo $child->account_id ?>">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Kategori <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                    <select required="" name="account_category" class="form-control">
                                                                                        <option value="">-Pilih Kategori-</option>
                                                                                        <option value="1">Pembayaran</option>
                                                                                        <option value="2">Keuangan</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                    <select required="" name="account_majors_id" class="form-control" <?= $attribute ?>>
                                                                                        <option value="">-Pilih Unit Pesantren-</option>
                                                                                        <?php foreach ($majors as $row) {
                                                                                            $selected = ($s['m'] == $row['majors_id']) ? 'selected' : '';
                                                                                        ?>
                                                                                            <option
                                                                                                value="<?php echo $row['majors_id']; ?>"
                                                                                                <?= $selected ?>>
                                                                                                <?php echo $row['majors_short_name'] ?>
                                                                                            </option>
                                                                                        <?php } ?>
                                                                                    </select>
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

                                                        <div class="modal fade" id="editChildAccount<?php echo $child->account_id ?>" role="dialog">
                                                            <div class="modal-dialog modal-md">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        <h4 class="modal-title">Edit Sub Akun</h4>
                                                                    </div>
                                                                    <?php echo form_open('manage/account/add', array('method' => 'post')); ?>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-8">
                                                                                <div class="form-group">
                                                                                    <input type="hidden" required="" name="account_id" class="form-control" value="<?php echo $child->account_id ?>">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Kode Akun</label>
                                                                                    <input type="text" required="" readonly="" name="account_code" class="form-control" placeholder="Masukkan Kode Akun" value="<?php echo $child->account_code ?>">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Keterangan</label>
                                                                                    <input type="text" required="" name="account_description" class="form-control" placeholder="Masukkan Keterangan" value="<?php echo $child->account_description ?>">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <input type="hidden" required="" name="account_note" class="form-control" value="<?php echo $child->account_note ?>">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Kategori <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                    <select required="" name="account_category" class="form-control">
                                                                                        <option value="">-Pilih Kategori-</option>
                                                                                        <option value="1" <?php echo ($child->account_category == '1') ? 'selected' : '' ?>>Pembayaran</option>
                                                                                        <option value="2" <?php echo ($child->account_category == '2') ? 'selected' : '' ?>>Keuangan</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                    <select required="" name="account_majors_id" class="form-control" <?= $attribute ?>>
                                                                                        <option value="">-Pilih Unit Pesantren-</option>
                                                                                        <?php foreach ($majors as $row) { ?>
                                                                                            <option value="<?php echo $row['majors_id']; ?>" <?php echo ($child->account_majors_id == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
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
                                                        foreach ($grandchild_account as $grandchild) {

                                                            $grandchild_id = $grandchild->account_id;
                                                            $sql_grandgrandchild = "SELECT * FROM account LEFT JOIN majors ON majors.majors_id = account.account_majors_id WHERE $piece account_note = '$grandchild_id' ORDER BY account_code ASC";
                                                            $grandgrandchild_account = $this->db->query($sql_grandgrandchild)->result();

                                                            if (count($grandgrandchild_account) > 0) {
                                                        ?>

                                                                <tr>
                                                                    <td class="text-center">
                                                                        <input type="checkbox" class="check-item" name="account_id[]" value="<?php echo $grandchild->account_id ?>" data-id="<?php echo $grandchild->account_id ?>" data-parent="<?php echo $grandchild->account_note ?>">
                                                                    </td>
                                                                    <td class="text-center"><?php echo $no++ ?></td>
                                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $grandchild->account_code ?></td>
                                                                    <td><?php echo $grandchild->account_description ?></td>
                                                                    <td><?php echo ($grandchild->account_status == 0) ? 'Header' : 'Non-Header'  ?></td>
                                                                    <td>Sub Akun 3</td>
                                                                    <td>
                                                                        <?php
                                                                        if ($grandchild->account_category == '1') {
                                                                            echo "Pembayaran";
                                                                        } else if ($grandchild->account_category == '2') {
                                                                            echo "Keuangan";
                                                                        } else {
                                                                            echo "#";
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo ($grandchild->account_majors_id != "0") ? $grandchild->majors_short_name : "Semua Unit" ?></td>
                                                                    <td align='left'>
                                                                        <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addGrandchildAccount<?php echo $grandchild->account_id ?>" title="Tambah Sub Akun"><i class="fa fa-plus"></i></button>
                                                                        <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editGrandchildAccount<?php echo $grandchild->account_id ?>" title="Edit Sub Akun"><i class="fa fa-pencil"></i></button>
                                                                    </td>
                                                                </tr>

                                                                <div class="modal fade" id="addGrandchildAccount<?php echo $grandchild->account_id ?>" role="dialog">
                                                                    <div class="modal-dialog modal-md">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                <h4 class="modal-title">Tambah Sub Akun</h4>
                                                                            </div>
                                                                            <?php echo form_open('manage/account/add', array('method' => 'post')); ?>
                                                                            <div class="modal-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-8">
                                                                                        <?php
                                                                                        //$childID = $child->account_id;
                                                                                        $grandchild_code = $this->db->query("SELECT MAX(account_code) as maxGrandChild FROM account WHERE account_note = '$grandchild_id'")->row();
                                                                                        $grandgrandchildParams = substr($grandchild_code->maxGrandChild, 2);
                                                                                        if ($grandgrandchildParams > 0) {
                                                                                            $grandgrandchildFront = substr($grandchild_code->maxGrandChild, 0, 2);
                                                                                            $grandgrandchildBack = substr($grandchild_code->maxGrandChild, 2);

                                                                                            $code = substr($grandgrandchildBack, 0, 9);
                                                                                            $last = substr($grandgrandchildBack, 8) + '.01';
                                                                                            $lastcode = $code . substr($last, 2);
                                                                                            $grandgrandchildAccount = $lastcode;
                                                                                        } else {
                                                                                            $grandgrandchildFront = substr($grandchild->account_code, 0, 2);
                                                                                            $grandgrandchildBack = substr($grandchild->account_code, 2);
                                                                                            $code = substr($grandgrandchildBack, 0, 9);
                                                                                            $last = substr($grandgrandchildBack, 8) + '.01';
                                                                                            $lastcode = $code . substr($last, 2);
                                                                                            $grandgrandchildAccount = $lastcode;
                                                                                        }
                                                                                        ?>
                                                                                        <div class="form-group">
                                                                                            <label>Kode Akun</label>
                                                                                            <input type="text" required="" readonly="" name="account_code" class="form-control" placeholder="Masukkan Kode Akun" value="<?php echo $grandgrandchildFront . $grandgrandchildAccount ?>">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Keterangan</label>
                                                                                            <input type="text" required="" name="account_description" class="form-control" placeholder="Masukkan Keterangan">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <input type="hidden" required="" name="account_note" class="form-control" value="<?php echo $grandchild->account_id ?>">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Kategori <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                            <select required="" name="account_category" class="form-control">
                                                                                                <option value="">-Pilih Kategori-</option>
                                                                                                <option value="1">Pembayaran</option>
                                                                                                <option value="2">Keuangan</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                            <select required="" name="account_majors_id" class="form-control" <?= $attribute ?>>
                                                                                                <option value="">-Pilih Unit Pesantren-</option>
                                                                                                <?php foreach ($majors as $row) {
                                                                                                    $selected = ($s['m'] == $row['majors_id']) ? 'selected' : '';
                                                                                                ?>
                                                                                                    <option value="<?php echo $row['majors_id']; ?>" <?= $selected ?>><?php echo $row['majors_short_name'] ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
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

                                                                <div class="modal fade" id="editGrandchildAccount<?php echo $grandchild->account_id ?>" role="dialog">
                                                                    <div class="modal-dialog modal-md">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                <h4 class="modal-title">Edit Sub Akun</h4>
                                                                            </div>
                                                                            <?php echo form_open('manage/account/add', array('method' => 'post')); ?>
                                                                            <div class="modal-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-8">
                                                                                        <div class="form-group">
                                                                                            <input type="hidden" required="" name="account_id" class="form-control" value="<?php echo $grandchild->account_id ?>">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Kode Akun</label>
                                                                                            <input type="text" required="" readonly="" name="account_code" class="form-control" placeholder="Masukkan Kode Akun" value="<?php echo $grandchild->account_code ?>">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Keterangan</label>
                                                                                            <input type="text" required="" name="account_description" class="form-control" placeholder="Masukkan Keterangan" value="<?php echo $grandchild->account_description ?>">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <input type="hidden" required="" name="account_note" class="form-control" value="<?php echo $grandchild->account_note ?>">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Kategori <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                            <select required="" name="account_category" class="form-control">
                                                                                                <option value="">-Pilih Kategori-</option>
                                                                                                <option value="1" <?php echo ($grandchild->account_category == '1') ? 'selected' : '' ?>>Pembayaran</option>
                                                                                                <option value="2" <?php echo ($grandchild->account_category == '2') ? 'selected' : '' ?>>Keuangan</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                            <select required="" name="account_majors_id" class="form-control" <?= $attribute ?>>
                                                                                                <option value="">-Pilih Unit Pesantren-</option>
                                                                                                <?php foreach ($majors as $row) { ?>
                                                                                                    <option value="<?php echo $row['majors_id']; ?>" <?php echo ($grandchild->account_majors_id == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
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

                                                                <div class="modal modal-default fade" id="deleteGrandchildAccount<?php echo $grandchild->account_id ?>">
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
                                                                                <?php echo form_open('manage/account/delete/' . $grandchild->account_id); ?>
                                                                                <input type="hidden" name="delCode" value="<?php echo $grandchild->account_code; ?>">
                                                                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
                                                                                <button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
                                                                                <?php echo form_close(); ?>
                                                                            </div>
                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                    </div>
                                                                    <!-- /.modal-dialog -->
                                                                </div>

                                                                <?php
                                                                foreach ($grandgrandchild_account as $lastchild) {
                                                                ?>

                                                                    <tr>
                                                                        <td class="text-center">
                                                                            <input type="checkbox" class="check-item" name="account_id[]" value="<?php echo $lastchild->account_id ?>" data-id="<?php echo $lastchild->account_id ?>" data-parent="<?php echo $lastchild->account_note ?>">
                                                                        </td>
                                                                        <td class="text-center"><?php echo $no++ ?></td>
                                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lastchild->account_code ?></td>
                                                                        <td><?php echo $lastchild->account_description ?></td>
                                                                        <td><?php echo ($lastchild->account_status == 0) ? 'Header' : 'Non-Header'  ?></td>
                                                                        <td>Sub Akun 4</td>
                                                                        <td>
                                                                            <?php
                                                                            if ($lastchild->account_category == '1') {
                                                                                echo "Pembayaran";
                                                                            } else if ($lastchild->account_category == '2') {
                                                                                echo "Keuangan";
                                                                            } else {
                                                                                echo "#";
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td><?php echo ($lastchild->account_majors_id != "0") ? $lastchild->majors_short_name : "Semua Unit" ?></td>
                                                                        <td align='left'>
                                                                            <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editGrandchildAccount<?php echo $lastchild->account_id ?>" title="Edit Sub Akun"><i class="fa fa-pencil"></i></button>
                                                                            <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteGrandchildAccount<?php echo $lastchild->account_id ?>" title="Hapus Sub Akun"><i class="fa fa-trash"></i></button>
                                                                        </td>
                                                                    </tr>

                                                                    <div class="modal fade" id="editGrandchildAccount<?php echo $lastchild->account_id ?>" role="dialog">
                                                                        <div class="modal-dialog modal-md">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    <h4 class="modal-title">Edit Sub Akun</h4>
                                                                                </div>
                                                                                <?php echo form_open('manage/account/add', array('method' => 'post')); ?>
                                                                                <div class="modal-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-8">
                                                                                            <div class="form-group">
                                                                                                <input type="hidden" required="" name="account_id" class="form-control" value="<?php echo $lastchild->account_id ?>">
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Kode Akun</label>
                                                                                                <input type="text" required="" readonly="" name="account_code" class="form-control" placeholder="Masukkan Kode Akun" value="<?php echo $lastchild->account_code ?>">
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Keterangan</label>
                                                                                                <input type="text" required="" name="account_description" class="form-control" placeholder="Masukkan Keterangan" value="<?php echo $lastchild->account_description ?>">
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <input type="hidden" required="" name="account_note" class="form-control" value="<?php echo $lastchild->account_note ?>">
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Kategori <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                                <select required="" name="account_category" class="form-control">
                                                                                                    <option value="">-Pilih Kategori-</option>
                                                                                                    <option value="1" <?php echo ($lastchild->account_category == '1') ? 'selected' : '' ?>>Pembayaran</option>
                                                                                                    <option value="2" <?php echo ($lastchild->account_category == '2') ? 'selected' : '' ?>>Keuangan</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Unit Sekolah <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                                <select required="" name="account_majors_id" class="form-control" <?= $attribute ?>>
                                                                                                    <option value="">-Pilih Unit Sekolah-</option>
                                                                                                    <?php foreach ($majors as $row) { ?>
                                                                                                        <option value="<?php echo $row['majors_id']; ?>" <?php echo ($lastchild->account_majors_id == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
                                                                                                    <?php } ?>
                                                                                                </select>
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

                                                                    <div class="modal modal-default fade" id="deleteGrandchildAccount<?php echo $lastchild->account_id ?>">
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
                                                                                    <?php echo form_open('manage/account/delete/' . $lastchild->account_id); ?>
                                                                                    <input type="hidden" name="delCode" value="<?php echo $lastchild->account_code; ?>">
                                                                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
                                                                                    <button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
                                                                                    <?php echo form_close(); ?>
                                                                                </div>
                                                                            </div>
                                                                            <!-- /.modal-content -->
                                                                        </div>
                                                                        <!-- /.modal-dialog -->
                                                                    </div>
                                                                <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <td class="text-center">
                                                                        <input type="checkbox" class="check-item" name="account_id[]" value="<?php echo $grandchild->account_id ?>" data-id="<?php echo $grandchild->account_id ?>" data-parent="<?php echo $grandchild->account_note ?>">
                                                                    </td>
                                                                    <td class="text-center"><?php echo $no++ ?></td>
                                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $grandchild->account_code ?></td>
                                                                    <td><?php echo $grandchild->account_description ?></td>
                                                                    <td><?php echo ($grandchild->account_status == 0) ? 'Header' : 'Non-Header'  ?></td>
                                                                    <td>Sub Akun 3</td>
                                                                    <td>
                                                                        <?php
                                                                        if ($grandchild->account_category == '1') {
                                                                            echo "Pembayaran";
                                                                        } else if ($grandchild->account_category == '2') {
                                                                            echo "Keuangan";
                                                                        } else {
                                                                            echo "#";
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo ($grandchild->account_majors_id != "0") ? $grandchild->majors_short_name : "Semua Unit" ?></td>
                                                                    <td align='left'>

                                                                        <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addGrandchildAccount<?php echo $grandchild->account_id ?>" title="Tambah Sub Akun"><i class="fa fa-plus"></i></button>
                                                                        <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editGrandchildAccount<?php echo $grandchild->account_id ?>" title="Edit Sub Akun"><i class="fa fa-pencil"></i></button>
                                                                        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteGrandchildAccount<?php echo $grandchild->account_id ?>" title="Hapus Sub Akun"><i class="fa fa-trash"></i></button>
                                                                    </td>
                                                                </tr>

                                                                <div class="modal fade" id="addGrandchildAccount<?php echo $grandchild->account_id ?>" role="dialog">
                                                                    <div class="modal-dialog modal-md">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                <h4 class="modal-title">Tambah Sub Akun</h4>
                                                                            </div>
                                                                            <?php echo form_open('manage/account/add', array('method' => 'post')); ?>
                                                                            <div class="modal-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-8">
                                                                                        <?php
                                                                                        //$childID = $child->account_id;
                                                                                        $grandchild_code = $this->db->query("SELECT MAX(account_code) as maxGrandChild FROM account WHERE account_note = '$grandchild_id'")->row();
                                                                                        // $grandgrandchildParams = substr($grandchild_code->maxGrandChild, 2);
                                                                                        $grandgrandchildParams = !empty($grandchild_code->maxGrandChild) ? substr($grandchild_code->maxGrandChild, 2) : '';
                                                                                        if ($grandgrandchildParams > 0) {
                                                                                            $grandgrandchildFront = substr($grandchild_code->maxGrandChild, 0, 2);
                                                                                            $grandgrandchildBack = substr($grandchild_code->maxGrandChild, 2);

                                                                                            $grandgrandchildAccount = $grandgrandchildBack . '.01';
                                                                                        } else {
                                                                                            $grandgrandchildFront = substr($grandchild->account_code, 0, 2);
                                                                                            $grandgrandchildBack = substr($grandchild->account_code, 2);
                                                                                            $grandgrandchildAccount = $grandgrandchildBack . '.01';
                                                                                        }
                                                                                        ?>
                                                                                        <div class="form-group">
                                                                                            <label>Kode Akun</label>
                                                                                            <input type="text" required="" readonly="" name="account_code" class="form-control" placeholder="Masukkan Kode Akun" value="<?php echo $grandgrandchildFront . $grandgrandchildAccount ?>">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Keterangan</label>
                                                                                            <input type="text" required="" name="account_description" class="form-control" placeholder="Masukkan Keterangan">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <input type="hidden" required="" name="account_note" class="form-control" value="<?php echo $grandchild->account_id ?>">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Kategori <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                            <select required="" name="account_category" class="form-control">
                                                                                                <option value="">-Pilih Kategori-</option>
                                                                                                <option value="1">Pembayaran</option>
                                                                                                <option value="2">Keuangan</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                            <select required="" name="account_majors_id" class="form-control" <?= $attribute ?>>
                                                                                                <option value="">-Pilih Unit Pesantren-</option>
                                                                                                <?php foreach ($majors as $row) {
                                                                                                    $selected = ($s['m'] == $row['majors_id']) ? 'selected' : '';
                                                                                                ?>
                                                                                                    <option
                                                                                                        value="<?php echo $row['majors_id']; ?>"
                                                                                                        <?= $selected ?>>
                                                                                                        <?php echo $row['majors_short_name'] ?>
                                                                                                    </option>
                                                                                                <?php } ?>
                                                                                            </select>
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

                                                                <div class="modal fade" id="editGrandchildAccount<?php echo $grandchild->account_id ?>" role="dialog">
                                                                    <div class="modal-dialog modal-md">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                <h4 class="modal-title">Edit Sub Akun</h4>
                                                                            </div>
                                                                            <?php echo form_open('manage/account/add', array('method' => 'post')); ?>
                                                                            <div class="modal-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-8">
                                                                                        <div class="form-group">
                                                                                            <input type="hidden" required="" name="account_id" class="form-control" value="<?php echo $grandchild->account_id ?>">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Kode Akun</label>
                                                                                            <input type="text" required="" readonly="" name="account_code" class="form-control" placeholder="Masukkan Kode Akun" value="<?php echo $grandchild->account_code ?>">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Keterangan</label>
                                                                                            <input type="text" required="" name="account_description" class="form-control" placeholder="Masukkan Keterangan" value="<?php echo $grandchild->account_description ?>">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <input type="hidden" required="" name="account_note" class="form-control" value="<?php echo $grandchild->account_note ?>">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Kategori <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                            <select required="" name="account_category" class="form-control">
                                                                                                <option value="">-Pilih Kategori-</option>
                                                                                                <option value="1" <?php echo ($grandchild->account_category == '1') ? 'selected' : '' ?>>Pembayaran</option>
                                                                                                <option value="2" <?php echo ($grandchild->account_category == '2') ? 'selected' : '' ?>>Keuangan</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                            <select required="" name="account_majors_id" class="form-control" <?= $attribute ?>>
                                                                                                <option value="">-Pilih Unit Pesantren-</option>
                                                                                                <?php foreach ($majors as $row) { ?>
                                                                                                    <option value="<?php echo $row['majors_id']; ?>" <?php echo ($grandchild->account_majors_id == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
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

                                                                <div class="modal modal-default fade" id="deleteGrandchildAccount<?php echo $grandchild->account_id ?>">
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
                                                                                <?php echo form_open('manage/account/delete/' . $grandchild->account_id); ?>
                                                                                <input type="hidden" name="delCode" value="<?php echo $grandchild->account_code; ?>">
                                                                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
                                                                                <button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
                                                                                <?php echo form_close(); ?>
                                                                            </div>
                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                    </div>
                                                                    <!-- /.modal-dialog -->
                                                                </div>
                                                        <?php
                                                            }
                                                        }
                                                    } else {
                                                        $subCode = substr($child->account_code, 0, 1);
                                                        if ($subCode == '4') $assignPosButton = true;
                                                        else $assignPosButton = false;

                                                        $colWhere = [
                                                            "account_account_id" => $child->account_id,
                                                        ];
                                                        $qPos = $this->db->get_where("pos", $colWhere)->result_array();
                                                        $countQPos = count($qPos);
                                                        if ($countQPos > 0) $disabledAssignPosButton = "disabled";
                                                        else $disabledAssignPosButton = "";

                                                        ?>

                                                        <tr>
                                                            <td class="text-center">
                                                                <input type="checkbox" class="check-item" name="account_id[]" value="<?php echo $child->account_id ?>" data-id="<?php echo $child->account_id ?>" data-parent="<?php echo $child->account_note ?>">
                                                            </td>
                                                            <td class="text-center"><?php echo $no++ ?></td>
                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child->account_code ?></td>
                                                            <td><?php echo $child->account_description ?></td>
                                                            <td><?php echo ($child->account_status == 0) ? 'Header' : 'Non-Header'  ?></td>
                                                            <td>Sub Akun 2</td>
                                                            <td>
                                                                <?php
                                                                if ($child->account_category == '1') {
                                                                    echo "Pembayaran";
                                                                } else if ($child->account_category == '2') {
                                                                    echo "Keuangan";
                                                                } else {
                                                                    echo "#";
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><?php echo ($child->account_majors_id != "0") ? $child->majors_short_name : "Semua Unit" ?></td>
                                                            <td align='left'>
                                                                <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addChildAccount<?php echo $child->account_id ?>" title="Tambah Sub Akun"><i class="fa fa-plus"></i></button>
                                                                <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editChildAccount<?php echo $child->account_id ?>" title="Edit Sub Akun"><i class="fa fa-pencil"></i></button>
                                                                <?php
                                                                if ($assignPosButton):
                                                                    if ($countQPos > 0):
                                                                ?>
                                                                        <button
                                                                            type="button"
                                                                            class="btn btn-info btn-xs"
                                                                            data-toggle="tooltip"
                                                                            title="Buat POS"
                                                                            <?= $disabledAssignPosButton ?>>
                                                                            <i class="fa fa-magic"></i>
                                                                        </button>
                                                                    <?php
                                                                    else:
                                                                        $url = site_url("manage/account/assign/");
                                                                        $url .= $child->account_id;
                                                                        if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
                                                                            $url .= "?m=" . $s['m'];
                                                                        }
                                                                    ?>
                                                                        <a href="<?= $url ?>" class="btn btn-info btn-xs"
                                                                            data-toggle="tooltip"
                                                                            title="Buat POS">
                                                                            <i class="fa fa-magic"></i>
                                                                        </a>
                                                                <?php
                                                                    endif;
                                                                endif;
                                                                ?>
                                                                <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteChildAccount<?php echo $child->account_id ?>" title="Hapus Sub Akun"><i class="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>

                                                        <div class="modal fade" id="addChildAccount<?php echo $child->account_id ?>" role="dialog">
                                                            <div class="modal-dialog modal-md">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        <h4 class="modal-title">Tambah Sub Akun</h4>
                                                                    </div>
                                                                    <?php echo form_open('manage/account/add', array('method' => 'post')); ?>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-8">
                                                                                <?php
                                                                                //$childID = $child->account_id;
                                                                                $child_code = $this->db->query("SELECT MAX(account_code) as maxChild FROM account WHERE account_note = '$child_id'")->row();
                                                                                // $grandchildParams = substr($child_code->maxChild, 2);
                                                                                $grandchildParams = !empty($child_code->maxChild) ? substr($child_code->maxChild, 2) : '';
                                                                                if ($grandchildParams > 0) {
                                                                                    $grandchildFront = substr($child_code->maxChild, 0, 2);
                                                                                    $grandchildBack = substr($child_code->maxChild, 2);
                                                                                    $grandchildAccount = $grandchildBack + '.01';
                                                                                } else {
                                                                                    $grandchildFront = substr($child->account_code, 0, 2);
                                                                                    $grandchildBack = substr($child->account_code, 2);
                                                                                    $grandchildAccount = $grandchildBack + '.01';
                                                                                }
                                                                                ?>
                                                                                <div class="form-group">
                                                                                    <label>Kode Akun</label>
                                                                                    <input type="text" required="" readonly="" name="account_code" class="form-control" placeholder="Masukkan Kode Akun" value="<?php echo $grandchildFront . $grandchildAccount ?>">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Keterangan</label>
                                                                                    <input type="text" required="" name="account_description" class="form-control" placeholder="Masukkan Keterangan">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <input type="hidden" required="" name="account_note" class="form-control" value="<?php echo $child->account_id ?>">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Kategori <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                    <select required="" name="account_category" class="form-control">
                                                                                        <option value="">-Pilih Kategori-</option>
                                                                                        <option value="1">Pembayaran</option>
                                                                                        <option value="2">Keuangan</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                    <select required="" name="account_majors_id" class="form-control" <?= $attribute ?>>
                                                                                        <option value="">-Pilih Unit Pesantren-</option>
                                                                                        <?php foreach ($majors as $row) {
                                                                                            $selected = ($s['m'] == $row['majors_id']) ? 'selected' : '';
                                                                                        ?>
                                                                                            <option
                                                                                                value="<?php echo $row['majors_id']; ?>"
                                                                                                <?= $selected ?>>
                                                                                                <?php echo $row['majors_short_name'] ?>
                                                                                            </option>
                                                                                        <?php } ?>
                                                                                    </select>
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

                                                        <div class="modal fade" id="editChildAccount<?php echo $child->account_id ?>" role="dialog">
                                                            <div class="modal-dialog modal-md">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        <h4 class="modal-title">Edit Sub Akun</h4>
                                                                    </div>
                                                                    <?php echo form_open('manage/account/add', array('method' => 'post')); ?>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-8">
                                                                                <div class="form-group">
                                                                                    <input type="hidden" required="" name="account_id" class="form-control" value="<?php echo $child->account_id ?>">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Kode Akun</label>
                                                                                    <input type="text" required="" readonly="" name="account_code" class="form-control" placeholder="Masukkan Kode Akun" value="<?php echo $child->account_code ?>">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Keterangan</label>
                                                                                    <input type="text" required="" name="account_description" class="form-control" placeholder="Masukkan Keterangan" value="<?php echo $child->account_description ?>">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <input type="hidden" required="" name="account_note" class="form-control" value="<?php echo $child->account_note ?>">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Kategori <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                    <select required="" name="account_category" class="form-control">
                                                                                        <option value="">-Pilih Kategori-</option>
                                                                                        <option value="1" <?php echo ($child->account_category == '1') ? 'selected' : '' ?>>Pembayaran</option>
                                                                                        <option value="2" <?php echo ($child->account_category == '2') ? 'selected' : '' ?>>Keuangan</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                    <select required="" name="account_majors_id" class="form-control" <?= $attribute ?>>
                                                                                        <option value="">-Pilih Unit Pesantren-</option>
                                                                                        <?php foreach ($majors as $row) { ?>
                                                                                            <option value="<?php echo $row['majors_id']; ?>" <?php echo ($child->account_majors_id == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
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

                                                        <div class="modal modal-default fade" id="deleteChildAccount<?php echo $child->account_id ?>">
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
                                                                        <?php echo form_open('manage/account/delete/' . $child->account_id); ?>
                                                                        <input type="hidden" name="delCode" value="<?php echo $child->account_code; ?>">
                                                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
                                                                        <button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
                                                                        <?php echo form_close(); ?>
                                                                    </div>
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </div>
                                                            <!-- /.modal-dialog -->
                                                        </div>
                                                <?php
                                                    }
                                                }
                                            } else {
                                                ?>
                                                <tr style="font-weight: bold;">
                                                    <td class="text-center">
                                                        <input type="checkbox" class="check-item" name="account_id[]" value="<?php echo $sub->account_id ?>" data-id="<?php echo $sub->account_id ?>" data-parent="<?php echo $sub->account_note ?>">
                                                    </td>
                                                    <td class="text-center"><?php echo $no++ ?></td>
                                                    <td>&nbsp;&nbsp;<?php echo $sub->account_code ?></td>
                                                    <td><?php echo $sub->account_description ?></td>
                                                    <td><?php echo ($sub->account_status == 0) ? 'Header' : 'Non-Header'  ?></td>
                                                    <td>Sub Akun 1</td>
                                                    <td>
                                                        <?php
                                                        if ($sub->account_category == '1') {
                                                            echo "Pembayaran";
                                                        } else if ($sub->account_category == '2') {
                                                            echo "Keuangan";
                                                        } else {
                                                            echo "#";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo ($sub->account_majors_id != "0") ? $sub->majors_short_name : "Semua Unit" ?></td>
                                                    <td align='left'>
                                                        <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addSubAccount<?php echo $sub->account_id ?>" title="Tambah Sub Akun"><i class="fa fa-plus"></i></button>
                                                        <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editSubAccount<?php echo $sub->account_id ?>" title="Edit Sub Akun"><i class="fa fa-pencil"></i></button>
                                                        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteSubAccount<?php echo $sub->account_id ?>" title="Hapus Sub Akun"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                                <div class="modal fade modal-add-sub" id="addSubAccount<?php echo $sub->account_id ?>" role="dialog" data-unit-name="<?php
                                                                                                                                                                        if (isset($s['m']) && $s['m'] != '') {
                                                                                                                                                                            $uNameRow = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '" . $s['m'] . "'")->row();
                                                                                                                                                                            if ($uNameRow) echo $uNameRow->majors_short_name;
                                                                                                                                                                        }
                                                                                                                                                                        ?>">
                                                    <div class="modal-dialog modal-md">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Tambah Sub Akun</h4>
                                                            </div>
                                                            <?php echo form_open('manage/account/add_glob', array('method' => 'post', 'class' => 'form-multi-step')); ?>
                                                            <div class="modal-body">
                                                                <?php
                                                                $sub_code = $this->db->query("SELECT MAX(account_code) as maxSub FROM account WHERE account_note = '$sub_id'")->row();
                                                                $childParams = !empty($sub_code->maxSub) ? substr($sub_code->maxSub, 2) : '';
                                                                if ($childParams > 0) {
                                                                    $childFront = substr($sub_code->maxSub, 0, 2);
                                                                    $childBack = substr($sub_code->maxSub, 2);
                                                                    $childAccount = $childBack + 1;
                                                                } else {
                                                                    $childFront = substr($sub->account_code, 0, 2);
                                                                    $childBack = substr($sub->account_code, 2);
                                                                    $childAccount = $childBack + 1;
                                                                }

                                                                $is_expense = (substr($childFront, 0, 1) === '4'); // Akun Biaya
                                                                ?>

                                                                <div class="step-section step-1" data-step="1">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <?php if ($is_expense): ?>
                                                                                <p class="text-info step-indicator"><strong>Langkah 1 dari 3: Data Akun</strong></p>
                                                                            <?php endif; ?>
                                                                            <div class="form-group">
                                                                                <label>Kode Akun</label>
                                                                                <input type="text" required="" readonly="" name="account_code" class="form-control" value="<?php echo $childFront . $childAccount ?>">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Keterangan</label>
                                                                                <input type="text" required="" name="account_description" class="form-control input-account-desc" placeholder="Masukkan Keterangan">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <input type="hidden" required="" name="account_note" class="form-control" value="<?php echo $sub->account_id ?>">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Kategori <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                <select required="" name="account_category" class="form-control select-category" data-is-expense="<?php echo $is_expense ? '1' : '0' ?>">
                                                                                    <option value="">-Pilih Kategori-</option>
                                                                                    <option value="1">Pembayaran</option>
                                                                                    <option value="2">Keuangan</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                                <select required="" name="account_majors_id" class="form-control select-unit" <?= $attribute ?>>
                                                                                    <option value="">-Pilih Unit Pesantren-</option>
                                                                                    <?php foreach ($majors as $row) {
                                                                                        $selected = ($s['m'] == $row['majors_id']) ? 'selected' : '';
                                                                                    ?>
                                                                                        <option value="<?php echo $row['majors_id']; ?>" <?= $selected ?>>
                                                                                            <?php echo $row['majors_short_name'] ?>
                                                                                        </option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <?php if ($is_expense): ?>
                                                                    <div class="step-section step-2" data-step="2" style="display: none;">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <p class="text-info step-indicator"><strong>Langkah 2 dari 3: Data POS</strong></p>
                                                                                <div class="form-group">
                                                                                    <label>Akun Piutang <span class="text-danger">*</span></label>
                                                                                    <select name="piutang_account_id" class="form-control input-piutang">
                                                                                        <option value="">-Pilih Akun Piutang-</option>
                                                                                        <?php
                                                                                        $piutangMajors = ($sub->account_majors_id != '0') ? "AND account_majors_id = '" . $sub->account_majors_id . "'" : "";
                                                                                        $piutangs = $this->db->query("SELECT * FROM account WHERE account_code LIKE '1%' AND account_description LIKE '%Piutang%' AND account_category = '2' $piutangMajors")->result();
                                                                                        foreach ($piutangs as $row):
                                                                                        ?>
                                                                                            <option value="<?php echo $row->account_id ?>"><?php echo $row->account_code . ' - ' . $row->account_description ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Nama POS <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="pos_name" class="form-control input-pos-name" placeholder="Tulis Nama POS">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Keterangan <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="pos_description" class="form-control input-pos-desc" placeholder="Tulis Keterangan POS">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="step-section step-3" data-step="3" style="display: none;">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <p class="text-info step-indicator"><strong>Langkah 3 dari 3: Data Jenis Bayar</strong></p>
                                                                                <div class="form-group">
                                                                                    <label>Tahun Ajaran <span class="text-danger">*</span></label>
                                                                                    <select name="period_id" class="form-control input-period">
                                                                                        <option value="">-Pilih Tahun Ajaran-</option>
                                                                                        <?php
                                                                                        $periods = $this->db->query("SELECT * FROM period WHERE period_status = '1'")->result();
                                                                                        foreach ($periods as $row) :
                                                                                        ?>
                                                                                            <option value="<?php echo $row->period_id; ?>" selected><?php echo $row->period_start . '/' . $row->period_end; ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group div-payment-type">
                                                                                    <label>Tipe <span class="text-danger">*</span></label>
                                                                                    <select name="payment_type" class="form-control select-payment-type">
                                                                                        <option value="BULAN">Bulanan</option>
                                                                                        <option value="BEBAS">Bebas</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group toggle-by-type">
                                                                                    <label>Model Pembayaran <span class="text-danger">*</span></label>
                                                                                    <div class="radio">
                                                                                        <label><input type="radio" name="paymentModes" value="TETAP" checked> Tetap</label>
                                                                                    </div>
                                                                                    <div class="radio">
                                                                                        <label><input type="radio" name="paymentModes" value="TIDAK TETAP"> Tidak Tetap</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group toggle-by-type">
                                                                                    <label>Apakah Item Paket Pembayaran <span class="text-danger">*</span></label>
                                                                                    <br>
                                                                                    <span class="help">Pilih Ya jika item ini merupakan bagian dari paket pembayaran</span>
                                                                                    <div class="radio">
                                                                                        <label><input type="radio" name="paymentIsBatchs" value="1" checked> Ya</label>
                                                                                    </div>
                                                                                    <div class="radio">
                                                                                        <label><input type="radio" name="paymentIsBatchs" value="0"> Tidak</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default pull-left btn-prev" style="display: none;">Sebelumnya</button>
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-primary btn-next" style="display: none;">Selanjutnya</button>
                                                                <button type="submit" class="btn btn-success btn-simpan">Simpan</button>
                                                            </div>
                                                            <?php echo form_close(); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="editSubAccount<?php echo $sub->account_id ?>" role="dialog">
                                                    <div class="modal-dialog modal-md">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Edit Sub Akun</h4>
                                                            </div>
                                                            <?php echo form_open('manage/account/add', array('method' => 'post')); ?>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-8">
                                                                        <div class="form-group">
                                                                            <input type="hidden" required="" name="account_id" class="form-control" value="<?php echo $sub->account_id ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Kode Akun</label>
                                                                            <input type="text" required="" readonly="" name="account_code" class="form-control" placeholder="Masukkan Kode Akun" value="<?php echo $sub->account_code ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Keterangan</label>
                                                                            <input type="text" required="" name="account_description" class="form-control" placeholder="Masukkan Keterangan" value="<?php echo $sub->account_description ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <input type="hidden" required="" name="account_note" class="form-control" value="<?php echo $sub->account_note ?>">
                                                                        </div>
                                                                        <!---->
                                                                        <div class="form-group">
                                                                            <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                            <select required="" name="account_majors_id" class="form-control" <?= $attribute ?>>
                                                                                <option value="">-Pilih Unit Pesantren-</option>
                                                                                <?php foreach ($majors as $row) { ?>
                                                                                    <option value="<?php echo $row['majors_id']; ?>" <?php echo ($sub->account_majors_id == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
                                                                                <?php } ?>
                                                                            </select>
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

                                                <div class="modal modal-default fade" id="deleteSubAccount<?php echo $sub->account_id ?>">
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
                                                                <?php echo form_open('manage/account/delete/' . $sub->account_id); ?>
                                                                <input type="hidden" name="delCode" value="<?php echo $sub->account_code; ?>">
                                                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
                                                                <button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
                                                                <?php echo form_close(); ?>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                        <?php
                                            }
                                        }
                                    } else {
                                        ?>
                                        <tr style="font-weight: bold;">
                                            <td class="text-center">
                                                <?php if ($main->account_id > 7): ?>
                                                    <input type="checkbox" class="check-item" name="account_id[]" value="<?php echo $main->account_id ?>" data-id="<?php echo $main->account_id ?>" data-parent="<?php echo $main->account_note ?>">
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center"><?php echo $no++ ?></td>
                                            <td><?php echo $main->account_code ?></td>
                                            <td><?php echo $main->account_description ?></td>
                                            <td><?php echo ($main->account_status == 0) ? 'Header' : 'Non-Header'  ?></td>
                                            <td>Akun Utama</td>
                                            <td>
                                                <?php
                                                if ($main->account_category == '1') {
                                                    echo "Pembayaran";
                                                } else if ($main->account_category == '2') {
                                                    echo "Keuangan";
                                                } else {
                                                    echo "#";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo ($main->account_majors_id != "0") ? $main->majors_short_name : "Semua Unit" ?></td>
                                            <td align='left'>
                                                <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addAccount<?php echo $main->account_id ?>" title="Tambah Sub Akun"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="addAccount<?php echo $main->account_id ?>" role="dialog">
                                            <div class="modal-dialog modal-md">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Tambah Sub Akun</h4>
                                                    </div>
                                                    <?php echo form_open('manage/account/add_glob', array('method' => 'post')); ?>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <?php
                                                                $main_code = $this->db->query("SELECT MAX(account_code) as maxMain FROM account WHERE account_note = '$main_id'")->row();
                                                                // $subParams = substr($main_code->maxMain, 2);
                                                                $subParams = !empty($main_code->maxMain) ? substr($main_code->maxMain, 2) : '';
                                                                if ($subParams > 0) {
                                                                    $subFront = substr($main_code->maxMain, 0, 2);
                                                                    $subBack = substr($main_code->maxMain, 2);
                                                                    $subAccount = $subBack + 100;
                                                                } else {
                                                                    $subFront = substr($main->account_code, 0, 2);
                                                                    $subBack = substr($main->account_code, 2);
                                                                    $subAccount = $subBack + 100;
                                                                }
                                                                ?>
                                                                <div class="form-group">
                                                                    <label>Kode Akun</label>
                                                                    <input type="text" required="" readonly="" name="account_code" class="form-control" value="<?php echo $subFront . $subAccount ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Keterangan</label>
                                                                    <input type="text" required="" name="account_description" class="form-control" placeholder="Masukkan Keterangan">
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="hidden" required="" name="account_note" class="form-control" value="<?php echo $main->account_id ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                                                    <select required="" name="account_majors_id" class="form-control" <?= $attribute ?>>
                                                                        <option value="">-Pilih Unit Pesantren-</option>
                                                                        <?php foreach ($majors as $row) {
                                                                            $selected = ($s['m'] == $row['majors_id']) ? 'selected' : '';
                                                                        ?>
                                                                            <option value="<?php echo $row['majors_id']; ?>" <?= $selected ?>><?php echo $row['majors_short_name'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
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
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php echo form_close(); ?>
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

<!-- Modal Multi Delete -->
<div class="modal modal-default fade" id="modal-multi-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi Penghapusan</h3>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data terpilih?</p>
                <p class="text-danger"><i class="fa fa-info-circle"></i> <b>Peringatan:</b> Menghapus parent akan ikut menghapus semua sub-akun di bawahnya.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
                <button type="button" class="btn btn-danger" id="btn-confirm-multi-delete"><span class="fa fa-check"></span> Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#dtable-coa').DataTable({
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All']
            ],
            pageLength: -1,
            ordering: false
        });

        // Hierarchy checkbox
        $('#check-all').on('change', function() {
            $('.check-item').prop('checked', $(this).prop('checked'));
        });

        $('.check-item').on('change', function() {
            var isChecked = $(this).prop('checked');
            var id = $(this).data('id');
            var parentId = $(this).data('parent');

            // Toggle descendants recursively
            function toggleChildren(parentId, checked) {
                $('.check-item[data-parent="' + parentId + '"]').each(function() {
                    $(this).prop('checked', checked);
                    toggleChildren($(this).data('id'), checked);
                });
            }
            toggleChildren(id, isChecked);

            // Jika uncheck, uncheck juga parent-parent di atasnya
            if (!isChecked) {
                function uncheckParent(pId) {
                    if (pId && pId != 0) {
                        var $parent = $('.check-item[data-id="' + pId + '"]');
                        if ($parent.length) {
                            $parent.prop('checked', false);
                            uncheckParent($parent.data('parent'));
                        }
                    }
                }
                uncheckParent(parentId);
            }
        });

        $('#btn-show-multi-delete').on('click', function() {
            if ($('.check-item:checked').length === 0) {
                toastNotifikasi('error', 'Pilih minimal satu data untuk dihapus.', 'Gagal');
            } else {
                $('#modal-multi-delete').modal('show');
            }
        });

        $('#btn-confirm-multi-delete').on('click', function() {
            $('#form-multi-delete')[0].submit();
        });

        // Multi-step wizard untuk modal addSubAccount (kode akun prefix 4-)
        $('.modal-add-sub').each(function() {
            var $modal = $(this);
            var $form = $modal.find('.form-multi-step');
            var $steps = $modal.find('.step-section');
            var $cat = $modal.find('.select-category');
            var isExpense = $cat.data('is-expense') == '1';
            var currentStep = 1;
            var maxStep = isExpense ? 3 : 1;

            var $btnNext = $modal.find('.btn-next');
            var $btnPrev = $modal.find('.btn-prev');
            var $btnSimpan = $modal.find('.btn-simpan');

            function showStep(step) {
                $steps.hide();
                $modal.find('.step-' + step).show();

                if (step === 1 && $cat.val() === '1') {
                    $btnNext.show();
                    $btnSimpan.hide();
                } else if (step === maxStep) {
                    $btnNext.hide();
                    $btnSimpan.show();
                } else {
                    $btnNext.show();
                    $btnSimpan.hide();
                }

                $btnPrev.toggle(step > 1);
                currentStep = step;
            }

            function validateStep(step) {
                var valid = true;
                $modal.find('.step-' + step + ' [required], .step-' + step + ' .input-piutang, .step-' + step + ' .input-pos-name, .step-' + step + ' .input-pos-desc, .step-' + step + ' .input-period').each(function() {
                    if ($(this).val() === '') {
                        valid = false;
                        $(this).closest('.form-group').addClass('has-error');
                    } else {
                        $(this).closest('.form-group').removeClass('has-error');
                    }
                });
                return valid;
            }

            // Kategori change
            $cat.change(function() {
                var val = $(this).val();
                if (isExpense && val === '1') {
                    showStep(1);
                } else {
                    $btnNext.hide();
                    $btnPrev.hide();
                    $btnSimpan.show();
                    $steps.hide();
                    $modal.find('.step-1').show();
                    currentStep = 1;
                }
            });

            // Next
            $btnNext.click(function() {
                if (!validateStep(currentStep)) {
                    toastNotifikasi('error', 'Lengkapi field wajib pada langkah ini!', 'Gagal');
                    return;
                }
                if (currentStep < maxStep) {
                    showStep(currentStep + 1);
                }
            });

            // Prev
            $btnPrev.click(function() {
                if (currentStep > 1) {
                    showStep(currentStep - 1);
                }
            });

            // Auto-fill POS name & description: [akun] [unit] / POS Pembayaran [akun] [unit]
            // Unit diambil dari majors_short_name hasil query (data-unit-name), bukan text option (bisa ada spasi/indentasi)
            function fillPosLabels() {
                var desc = ($modal.find('.input-account-desc').val() || '').trim();
                var unit = $modal.data('unit-name') || $modal.find('.select-unit option:selected').text().trim();
                if (!desc) return;
                var label = desc + (unit ? ' ' + unit : '');
                $modal.find('.input-pos-name').val(label);
                $modal.find('.input-pos-desc').val('POS Pembayaran ' + label);
            }
            $modal.find('.input-account-desc').on('blur', fillPosLabels);
            $modal.find('.select-unit').on('change', fillPosLabels);

            // Toggle jenis bayar fields by tipe
            var $typeSel = $modal.find('.select-payment-type');

            function toggleType() {
                if ($typeSel.val() === 'BULAN') {
                    $modal.find('.toggle-by-type').show();
                } else {
                    $modal.find('.toggle-by-type').hide();
                }
            }
            $typeSel.change(toggleType);
            toggleType();

            // Reset on modal open
            $modal.on('show.bs.modal', function() {
                $form[0].reset();
                $cat.val('');
                showStep(1);
            });
        });
    });
</script>