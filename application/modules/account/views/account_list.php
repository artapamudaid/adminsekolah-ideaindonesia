<?php
// Function helpers for auto-calculating next code
if (!function_exists('calc_sub_code')) {
    function calc_sub_code($parent_code, $max_code) {
        $max = !empty($max_code) ? $max_code : $parent_code;
        $subFront = substr($max, 0, 2);
        $subBack = substr($max, 2);
        $subAccount = (int)$subBack + 100;
        return $subFront . $subAccount;
    }
}
if (!function_exists('calc_child_code')) {
    function calc_child_code($parent_code, $max_code) {
        $max = !empty($max_code) ? $max_code : $parent_code;
        $childFront = substr($max, 0, 2);
        $childBack = substr($max, 2);
        $childAccount = (int)$childBack + 1;
        return $childFront . $childAccount;
    }
}
if (!function_exists('calc_grandchild_code')) {
    function calc_grandchild_code($parent_code, $max_code) {
        if (!empty($max_code)) {
            $grandchildFront = substr($max_code, 0, 2);
            $grandchildBack = substr($max_code, 2);
            return $grandchildFront . $grandchildBack . '.01';
        } else {
            $grandchildFront = substr($parent_code, 0, 2);
            $grandchildBack = substr($parent_code, 2);
            return $grandchildFront . $grandchildBack . '.01';
        }
    }
}
if (!function_exists('calc_grandgrandchild_code')) {
    function calc_grandgrandchild_code($parent_code, $max_code) {
        $source = !empty($max_code) ? $max_code : $parent_code;
        $grandgrandchildFront = substr($source, 0, 2);
        $grandgrandchildBack = substr($source, 2);
        $code = substr($grandgrandchildBack, 0, 9);
        $last = (float)substr($grandgrandchildBack, 8) + 0.01;
        $lastcode = $code . substr((string)$last, 2);
        return $grandgrandchildFront . $lastcode;
    }
}
?>
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
        $is_super = ($this->session->userdata('uroleid') == 3);
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
                                                    &nbsp;&nbsp;
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
                                        <?php if ($is_super): ?>
                                            <button type="button" id="btn-show-multi-delete" class="btn btn-danger">
                                                <i class="fa fa-trash"></i>&nbsp; Hapus Data Terpilih
                                            </button>
                                        <?php endif; ?>
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
                    <?php if ($is_super): ?>
                        <?php echo form_open('manage/account/delete_multi', array('id' => 'form-multi-delete')); ?>
                        <input type="hidden" name="majors_id" value="<?php echo isset($s['m']) ? $s['m'] : ''; ?>">
                    <?php endif; ?>
                    <div class="box-body table-responsive">
                        <table id='dtable-coa' class='table table-hover table-responsive'>
                            <thead>
                                <tr>
                                    <?php if ($is_super): ?>
                                        <th width="40" class="text-center"><input type="checkbox" id="check-all"></th>
                                    <?php endif; ?>
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
                                $attribute = "";
                                if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
                                    $attribute = "readonly style='pointer-events: none;'";
                                }

                                $main_account = isset($main_accounts) ? $main_accounts : array();
                                $no = 1;

                                foreach ($main_account as $main) {
                                    $main_id = $main->account_id;
                                    $sub_account = isset($accounts_by_parent[$main_id]) ? $accounts_by_parent[$main_id] : array();
                                    $has_sub = count($sub_account) > 0;

                                    $maxMain = isset($max_code_by_parent[$main_id]) ? $max_code_by_parent[$main_id] : '';
                                    $next_sub_code = calc_sub_code($main->account_code, $maxMain);
                                ?>
                                    <tr style="font-weight: bold;">
                                        <?php if ($is_super): ?>
                                            <td class="text-center">
                                                <?php if ($main->account_id > 7): ?>
                                                    <input type="checkbox" class="check-item" name="account_id[]" value="<?php echo $main->account_id ?>" data-id="<?php echo $main->account_id ?>" data-parent="<?php echo $main->account_note ?>">
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $main->account_code ?></td>
                                        <td><?php echo $main->account_description ?></td>
                                        <td><?php echo ($main->account_status == 0) ? 'Header' : 'Non-Header'  ?></td>
                                        <td>Akun Utama</td>
                                        <td>
                                            <?php
                                            if ($main->account_category == '1') echo "Pembayaran";
                                            else if ($main->account_category == '2') echo "Keuangan";
                                            else echo "#";
                                            ?>
                                        </td>
                                        <td><?php echo ($main->account_majors_id != "0") ? $main->majors_short_name : "Semua Unit" ?></td>
                                        <td align='left'>
                                            <button type="button" class="btn btn-success btn-xs btn-trigger-add"
                                                data-level="1"
                                                data-parent-id="<?php echo $main->account_id ?>"
                                                data-parent-code="<?php echo $main->account_code ?>"
                                                data-next-code="<?php echo $next_sub_code ?>"
                                                data-majors-id="<?php echo $main->account_majors_id ?>"
                                                title="Tambah Sub Akun"><i class="fa fa-plus"></i></button>
                                        </td>
                                    </tr>

                                    <?php
                                    foreach ($sub_account as $sub) {
                                        $sub_id = $sub->account_id;
                                        $child_account = isset($accounts_by_parent[$sub_id]) ? $accounts_by_parent[$sub_id] : array();
                                        $has_child = count($child_account) > 0;

                                        $maxSub = isset($max_code_by_parent[$sub_id]) ? $max_code_by_parent[$sub_id] : '';
                                        $next_child_code = calc_child_code($sub->account_code, $maxSub);
                                        $unitShortName = ($sub->account_majors_id != '0' && !empty($sub->majors_short_name)) ? trim($sub->majors_short_name) : '';
                                    ?>
                                        <tr style="font-weight: bold;">
                                            <?php if ($is_super): ?>
                                                <td class="text-center">
                                                    <input type="checkbox" class="check-item" name="account_id[]" value="<?php echo $sub->account_id ?>" data-id="<?php echo $sub->account_id ?>" data-parent="<?php echo $sub->account_note ?>">
                                                </td>
                                            <?php endif; ?>
                                            <td><?php echo $no++ ?></td>
                                            <td>&nbsp;&nbsp;<?php echo $sub->account_code ?></td>
                                            <td><?php echo $sub->account_description ?></td>
                                            <td><?php echo ($sub->account_status == 0) ? 'Header' : 'Non-Header'  ?></td>
                                            <td>Sub Akun 1</td>
                                            <td>
                                                <?php
                                                if ($sub->account_category == '1') echo "Pembayaran";
                                                else if ($sub->account_category == '2') echo "Keuangan";
                                                else echo "#";
                                                ?>
                                            </td>
                                            <td><?php echo ($sub->account_majors_id != "0") ? $sub->majors_short_name : "Semua Unit" ?></td>
                                            <td align='left'>
                                                <button type="button" class="btn btn-success btn-xs btn-trigger-add"
                                                    data-level="2"
                                                    data-parent-id="<?php echo $sub->account_id ?>"
                                                    data-parent-code="<?php echo $sub->account_code ?>"
                                                    data-next-code="<?php echo $next_child_code ?>"
                                                    data-majors-id="<?php echo $sub->account_majors_id ?>"
                                                    data-unit-name="<?php echo htmlspecialchars($unitShortName) ?>"
                                                    title="Tambah Sub Akun"><i class="fa fa-plus"></i></button>
                                                <button type="button" class="btn btn-warning btn-xs btn-trigger-edit"
                                                    data-id="<?php echo $sub->account_id ?>"
                                                    data-code="<?php echo $sub->account_code ?>"
                                                    data-desc="<?php echo htmlspecialchars($sub->account_description) ?>"
                                                    data-note="<?php echo $sub->account_note ?>"
                                                    data-category="<?php echo $sub->account_category ?>"
                                                    data-majors-id="<?php echo $sub->account_majors_id ?>"
                                                    title="Edit Sub Akun"><i class="fa fa-pencil"></i></button>
                                                <?php if (!$has_child): ?>
                                                    <button type="button" class="btn btn-danger btn-xs btn-trigger-delete"
                                                        data-id="<?php echo $sub->account_id ?>"
                                                        data-code="<?php echo $sub->account_code ?>"
                                                        title="Hapus Sub Akun"><i class="fa fa-trash"></i></button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                        <?php
                                        foreach ($child_account as $child) {
                                            $child_id = $child->account_id;
                                            $grandchild_account = isset($accounts_by_parent[$child_id]) ? $accounts_by_parent[$child_id] : array();
                                            $has_grandchild = count($grandchild_account) > 0;

                                            $maxChild = isset($max_code_by_parent[$child_id]) ? $max_code_by_parent[$child_id] : '';
                                            $next_grandchild_code = calc_grandchild_code($child->account_code, $maxChild);

                                            $subCode = substr($child->account_code, 0, 1);
                                            $assignPosButton = ($subCode == '4');
                                            $countQPos = isset($pos_account_map[$child->account_id]) ? 1 : 0;
                                            $disabledAssignPosButton = ($countQPos > 0) ? "disabled" : "";
                                        ?>
                                            <tr>
                                                <?php if ($is_super): ?>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="check-item" name="account_id[]" value="<?php echo $child->account_id ?>" data-id="<?php echo $child->account_id ?>" data-parent="<?php echo $child->account_note ?>">
                                                    </td>
                                                <?php endif; ?>
                                                <td><?php echo $no++ ?></td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child->account_code ?></td>
                                                <td><?php echo $child->account_description ?></td>
                                                <td><?php echo ($child->account_status == 0) ? 'Header' : 'Non-Header'  ?></td>
                                                <td>Sub Akun 2</td>
                                                <td>
                                                    <?php
                                                    if ($child->account_category == '1') echo "Pembayaran";
                                                    else if ($child->account_category == '2') echo "Keuangan";
                                                    else echo "#";
                                                    ?>
                                                </td>
                                                <td><?php echo ($child->account_majors_id != "0") ? $child->majors_short_name : "Semua Unit" ?></td>
                                                <td align='left'>
                                                    <button type="button" class="btn btn-success btn-xs btn-trigger-add"
                                                        data-level="3"
                                                        data-parent-id="<?php echo $child->account_id ?>"
                                                        data-parent-code="<?php echo $child->account_code ?>"
                                                        data-next-code="<?php echo $next_grandchild_code ?>"
                                                        data-majors-id="<?php echo $child->account_majors_id ?>"
                                                        title="Tambah Sub Akun"><i class="fa fa-plus"></i></button>
                                                    <button type="button" class="btn btn-warning btn-xs btn-trigger-edit"
                                                        data-id="<?php echo $child->account_id ?>"
                                                        data-code="<?php echo $child->account_code ?>"
                                                        data-desc="<?php echo htmlspecialchars($child->account_description) ?>"
                                                        data-note="<?php echo $child->account_note ?>"
                                                        data-category="<?php echo $child->account_category ?>"
                                                        data-majors-id="<?php echo $child->account_majors_id ?>"
                                                        title="Edit Sub Akun"><i class="fa fa-pencil"></i></button>
                                                    <?php
                                                    if (!$has_grandchild) {
                                                        if ($assignPosButton):
                                                            if ($countQPos > 0):
                                                    ?>
                                                                <button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" title="Buat POS" <?= $disabledAssignPosButton ?>><i class="fa fa-magic"></i></button>
                                                            <?php
                                                            else:
                                                                $url = site_url("manage/account/assign/" . $child->account_id);
                                                                if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
                                                                    $url .= "?m=" . $s['m'];
                                                                }
                                                            ?>
                                                                <a href="<?= $url ?>" class="btn btn-info btn-xs" data-toggle="tooltip" title="Buat POS"><i class="fa fa-magic"></i></a>
                                                    <?php
                                                            endif;
                                                        endif;
                                                    ?>
                                                        <button type="button" class="btn btn-danger btn-xs btn-trigger-delete"
                                                            data-id="<?php echo $child->account_id ?>"
                                                            data-code="<?php echo $child->account_code ?>"
                                                            title="Hapus Sub Akun"><i class="fa fa-trash"></i></button>
                                                    <?php } ?>
                                                </td>
                                            </tr>

                                            <?php
                                            foreach ($grandchild_account as $grandchild) {
                                                $grandchild_id = $grandchild->account_id;
                                                $grandgrandchild_account = isset($accounts_by_parent[$grandchild_id]) ? $accounts_by_parent[$grandchild_id] : array();
                                                $has_grandgrandchild = count($grandgrandchild_account) > 0;

                                                $maxGrandChild = isset($max_code_by_parent[$grandchild_id]) ? $max_code_by_parent[$grandchild_id] : '';
                                                $next_grandgrandchild_code = calc_grandgrandchild_code($grandchild->account_code, $maxGrandChild);
                                            ?>
                                                <tr>
                                                    <?php if ($is_super): ?>
                                                        <td class="text-center">
                                                            <input type="checkbox" class="check-item" name="account_id[]" value="<?php echo $grandchild->account_id ?>" data-id="<?php echo $grandchild->account_id ?>" data-parent="<?php echo $grandchild->account_note ?>">
                                                        </td>
                                                    <?php endif; ?>
                                                    <td><?php echo $no++ ?></td>
                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $grandchild->account_code ?></td>
                                                    <td><?php echo $grandchild->account_description ?></td>
                                                    <td><?php echo ($grandchild->account_status == 0) ? 'Header' : 'Non-Header'  ?></td>
                                                    <td>Sub Akun 3</td>
                                                    <td>
                                                        <?php
                                                        if ($grandchild->account_category == '1') echo "Pembayaran";
                                                        else if ($grandchild->account_category == '2') echo "Keuangan";
                                                        else echo "#";
                                                        ?>
                                                    </td>
                                                    <td><?php echo ($grandchild->account_majors_id != "0") ? $grandchild->majors_short_name : "Semua Unit" ?></td>
                                                    <td align='left'>
                                                        <button type="button" class="btn btn-success btn-xs btn-trigger-add"
                                                            data-level="4"
                                                            data-parent-id="<?php echo $grandchild->account_id ?>"
                                                            data-parent-code="<?php echo $grandchild->account_code ?>"
                                                            data-next-code="<?php echo $next_grandgrandchild_code ?>"
                                                            data-majors-id="<?php echo $grandchild->account_majors_id ?>"
                                                            title="Tambah Sub Akun"><i class="fa fa-plus"></i></button>
                                                        <button type="button" class="btn btn-warning btn-xs btn-trigger-edit"
                                                            data-id="<?php echo $grandchild->account_id ?>"
                                                            data-code="<?php echo $grandchild->account_code ?>"
                                                            data-desc="<?php echo htmlspecialchars($grandchild->account_description) ?>"
                                                            data-note="<?php echo $grandchild->account_note ?>"
                                                            data-category="<?php echo $grandchild->account_category ?>"
                                                            data-majors-id="<?php echo $grandchild->account_majors_id ?>"
                                                            title="Edit Sub Akun"><i class="fa fa-pencil"></i></button>
                                                        <?php if (!$has_grandgrandchild): ?>
                                                            <button type="button" class="btn btn-danger btn-xs btn-trigger-delete"
                                                                data-id="<?php echo $grandchild->account_id ?>"
                                                                data-code="<?php echo $grandchild->account_code ?>"
                                                                title="Hapus Sub Akun"><i class="fa fa-trash"></i></button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <?php
                                                foreach ($grandgrandchild_account as $lastchild) {
                                                ?>
                                                    <tr>
                                                        <?php if ($is_super): ?>
                                                            <td class="text-center">
                                                                <input type="checkbox" class="check-item" name="account_id[]" value="<?php echo $lastchild->account_id ?>" data-id="<?php echo $lastchild->account_id ?>" data-parent="<?php echo $lastchild->account_note ?>">
                                                            </td>
                                                        <?php endif; ?>
                                                        <td><?php echo $no++ ?></td>
                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lastchild->account_code ?></td>
                                                        <td><?php echo $lastchild->account_description ?></td>
                                                        <td><?php echo ($lastchild->account_status == 0) ? 'Header' : 'Non-Header'  ?></td>
                                                        <td>Sub Akun 4</td>
                                                        <td>
                                                            <?php
                                                            if ($lastchild->account_category == '1') echo "Pembayaran";
                                                            else if ($lastchild->account_category == '2') echo "Keuangan";
                                                            else echo "#";
                                                            ?>
                                                        </td>
                                                        <td><?php echo ($lastchild->account_majors_id != "0") ? $lastchild->majors_short_name : "Semua Unit" ?></td>
                                                        <td align='left'>
                                                            <button type="button" class="btn btn-warning btn-xs btn-trigger-edit"
                                                                data-id="<?php echo $lastchild->account_id ?>"
                                                                data-code="<?php echo $lastchild->account_code ?>"
                                                                data-desc="<?php echo htmlspecialchars($lastchild->account_description) ?>"
                                                                data-note="<?php echo $lastchild->account_note ?>"
                                                                data-category="<?php echo $lastchild->account_category ?>"
                                                                data-majors-id="<?php echo $lastchild->account_majors_id ?>"
                                                                title="Edit Sub Akun"><i class="fa fa-pencil"></i></button>
                                                            <button type="button" class="btn btn-danger btn-xs btn-trigger-delete"
                                                                data-id="<?php echo $lastchild->account_id ?>"
                                                                data-code="<?php echo $lastchild->account_code ?>"
                                                                title="Hapus Sub Akun"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($is_super): ?>
                        <?php echo form_close(); ?>
                    <?php endif; ?>
                    <!-- /.box-body -->
                </div>
                <div>
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<!-- ==================== REUSABLE MODALS (HANYA 3 MODAL DI LUAR TABEL) ==================== -->

<!-- 1. Modal Tambah Sub Akun (Dinamis dengan Wizard untuk Biaya) -->
<div class="modal fade" id="modal-add-account" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-add-title">Tambah Sub Akun</h4>
            </div>
            <form id="form-add-account" method="post" action="<?php echo site_url('manage/account/add_glob'); ?>" class="form-multi-step">
                <div class="modal-body">
                    <!-- Step 1: Data Akun -->
                    <div class="step-section step-1" data-step="1">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-info step-indicator" style="display: none;"><strong>Langkah 1 dari 3: Data Akun</strong></p>
                                <div class="form-group">
                                    <label>Kode Akun</label>
                                    <input type="text" required="" readonly="" name="account_code" id="add_account_code" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <input type="text" required="" name="account_description" id="add_account_desc" class="form-control input-account-desc" placeholder="Masukkan Keterangan">
                                </div>
                                <input type="hidden" required="" name="account_note" id="add_account_note" class="form-control">
                                <div class="form-group" id="div-add-category">
                                    <label>Kategori <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                    <select name="account_category" id="add_account_category" class="form-control select-category">
                                        <option value="">-Pilih Kategori-</option>
                                        <option value="1">Pembayaran</option>
                                        <option value="2">Keuangan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                                    <select required="" name="account_majors_id" id="add_account_majors_id" class="form-control select-unit" <?= $attribute ?>>
                                        <option value="">-Pilih Unit Pesantren-</option>
                                        <?php foreach ($majors as $row) { ?>
                                            <option value="<?php echo $row['majors_id']; ?>"><?php echo $row['majors_short_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Data POS (Hanya jika Kode 4- dan Kategori 1) -->
                    <div class="step-section step-2" data-step="2" style="display: none;">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-info step-indicator"><strong>Langkah 2 dari 3: Data POS</strong></p>
                                <div class="form-group">
                                    <label>Akun Piutang <span class="text-danger">*</span></label>
                                    <select name="piutang_account_id" id="add_piutang_account_id" class="form-control input-piutang">
                                        <option value="">-Pilih Akun Piutang-</option>
                                        <?php if (isset($piutangs_all)): ?>
                                            <?php foreach ($piutangs_all as $row): ?>
                                                <option value="<?php echo $row->account_id ?>" data-majors="<?php echo $row->account_majors_id ?>"><?php echo $row->account_code . ' - ' . $row->account_description ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nama POS <span class="text-danger">*</span></label>
                                    <input type="text" name="pos_name" id="add_pos_name" class="form-control input-pos-name" placeholder="Tulis Nama POS">
                                </div>
                                <div class="form-group">
                                    <label>Keterangan <span class="text-danger">*</span></label>
                                    <input type="text" name="pos_description" id="add_pos_desc" class="form-control input-pos-desc" placeholder="Tulis Keterangan POS">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Data Jenis Bayar -->
                    <div class="step-section step-3" data-step="3" style="display: none;">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-info step-indicator"><strong>Langkah 3 dari 3: Data Jenis Bayar</strong></p>
                                <div class="form-group">
                                    <label>Tahun Ajaran <span class="text-danger">*</span></label>
                                    <select name="period_id" id="add_period_id" class="form-control input-period">
                                        <option value="">-Pilih Tahun Ajaran-</option>
                                        <?php if (isset($periods)): ?>
                                            <?php foreach ($periods as $row): ?>
                                                <option value="<?php echo $row->period_id; ?>" selected><?php echo $row->period_start . '/' . $row->period_end; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="form-group div-payment-type">
                                    <label>Tipe <span class="text-danger">*</span></label>
                                    <select name="payment_type" id="add_payment_type" class="form-control select-payment-type">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left btn-prev" style="display: none;">Sebelumnya</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-next" style="display: none;">Selanjutnya</button>
                    <button type="submit" class="btn btn-success btn-simpan">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. Modal Edit Sub Akun (Dinamis) -->
<div class="modal fade" id="modal-edit-account" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Sub Akun</h4>
            </div>
            <?php echo form_open('manage/account/add', array('method' => 'post')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" required="" name="account_id" id="edit_account_id" class="form-control">
                        <div class="form-group">
                            <label>Kode Akun</label>
                            <input type="text" required="" readonly="" name="account_code" id="edit_account_code" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <input type="text" required="" name="account_description" id="edit_account_desc" class="form-control" placeholder="Masukkan Keterangan">
                        </div>
                        <input type="hidden" required="" name="account_note" id="edit_account_note" class="form-control">
                        <div class="form-group">
                            <label>Kategori <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                            <select required="" name="account_category" id="edit_account_category" class="form-control">
                                <option value="">-Pilih Kategori-</option>
                                <option value="1">Pembayaran</option>
                                <option value="2">Keuangan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                            <select required="" name="account_majors_id" id="edit_account_majors_id" class="form-control" <?= $attribute ?>>
                                <option value="">-Pilih Unit Pesantren-</option>
                                <?php foreach ($majors as $row) { ?>
                                    <option value="<?php echo $row['majors_id']; ?>"><?php echo $row['majors_short_name'] ?></option>
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

<!-- 3. Modal Hapus Akun (Dinamis) -->
<div class="modal modal-default fade" id="modal-delete-account">
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
            <form id="form-delete-account" method="post" action="">
                <div class="modal-footer">
                    <input type="hidden" name="delCode" id="delete_delCode" value="">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
                    <button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($is_super): ?>
<!-- 4. Modal Multi Delete -->
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
<?php endif; ?>

<script>
    $(function() {
        $('#dtable-coa').DataTable({
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All']
            ],
            pageLength: 50,
            ordering: false
        });

        // ----------------------------------------------------
        // REUSABLE MODAL HANDLERS
        // ----------------------------------------------------

        var $addModal = $('#modal-add-account');
        var $addForm = $('#form-add-account');
        var isExpenseAccount = false;
        var currentStep = 1;
        var maxStep = 1;
        var currentUnitName = '';

        // Trigger Add Modal (Delegated)
        $(document).on('click', '.btn-trigger-add', function() {
            var level = $(this).data('level');
            var parentId = $(this).data('parent-id');
            var nextCode = $(this).data('next-code') || '';
            var majorsId = $(this).data('majors-id') || '';
            var unitName = $(this).data('unit-name') || '';

            currentUnitName = unitName;
            isExpenseAccount = (nextCode.charAt(0) === '4' && level == 2);
            maxStep = isExpenseAccount ? 3 : 1;
            currentStep = 1;

            // Form action: level <= 2 uses add_glob, level >= 3 uses add
            var targetAction = (level <= 2) ? '<?php echo site_url("manage/account/add_glob"); ?>' : '<?php echo site_url("manage/account/add"); ?>';
            $addForm.attr('action', targetAction);

            $('#add_account_code').val(nextCode);
            $('#add_account_desc').val('');
            $('#add_account_note').val(parentId);
            $('#add_account_category').val('').prop('required', level > 1);
            $('#div-add-category').toggle(level > 1);

            // Unit select
            if (majorsId && majorsId != '0') {
                $('#add_account_majors_id').val(majorsId);
            } else {
                $('#add_account_majors_id').val('<?php echo isset($s["m"]) ? $s["m"] : ""; ?>');
            }

            // Filter dropdown piutang sesuai unit jika ada
            if (majorsId && majorsId != '0') {
                $('#add_piutang_account_id option').each(function() {
                    var optMajors = $(this).data('majors');
                    if (!optMajors || optMajors == majorsId || $(this).val() === '') {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            } else {
                $('#add_piutang_account_id option').show();
            }

            $('.step-indicator').toggle(isExpenseAccount);
            showAddStep(1);
            $addModal.modal('show');
        });

        function showAddStep(step) {
            $addModal.find('.step-section').hide();
            $addModal.find('.step-' + step).show();

            var $cat = $('#add_account_category');
            var $btnNext = $addModal.find('.btn-next');
            var $btnPrev = $addModal.find('.btn-prev');
            var $btnSimpan = $addModal.find('.btn-simpan');

            if (step === 1 && isExpenseAccount && $cat.val() === '1') {
                $btnNext.show();
                $btnSimpan.hide();
            } else if (step === maxStep) {
                $btnNext.hide();
                $btnSimpan.show();
            } else if (step > 1) {
                $btnNext.show();
                $btnSimpan.hide();
            } else {
                $btnNext.hide();
                $btnSimpan.show();
            }

            $btnPrev.toggle(step > 1);
            currentStep = step;
        }

        $('#add_account_category').on('change', function() {
            var val = $(this).val();
            if (isExpenseAccount && val === '1') {
                showAddStep(1);
            } else {
                $addModal.find('.btn-next').hide();
                $addModal.find('.btn-prev').hide();
                $addModal.find('.btn-simpan').show();
                $addModal.find('.step-section').hide();
                $addModal.find('.step-1').show();
                currentStep = 1;
            }
        });

        $addModal.find('.btn-next').on('click', function() {
            var valid = true;
            $addModal.find('.step-' + currentStep + ' [required], .step-' + currentStep + ' .input-piutang, .step-' + currentStep + ' .input-pos-name, .step-' + currentStep + ' .input-pos-desc, .step-' + currentStep + ' .input-period').each(function() {
                if ($(this).val() === '') {
                    valid = false;
                    $(this).closest('.form-group').addClass('has-error');
                } else {
                    $(this).closest('.form-group').removeClass('has-error');
                }
            });

            if (!valid) {
                if (typeof toastNotifikasi === 'function') {
                    toastNotifikasi('error', 'Lengkapi field wajib pada langkah ini!', 'Gagal');
                } else {
                    alert('Lengkapi field wajib pada langkah ini!');
                }
                return;
            }

            if (currentStep < maxStep) {
                showAddStep(currentStep + 1);
            }
        });

        $addModal.find('.btn-prev').on('click', function() {
            if (currentStep > 1) {
                showAddStep(currentStep - 1);
            }
        });

        // Auto fill POS name
        function fillPosLabels() {
            var desc = ($('#add_account_desc').val() || '').trim();
            var unit = currentUnitName || $('#add_account_majors_id option:selected').text().trim();
            if (!desc) return;
            var label = desc + (unit ? ' ' + unit : '');
            $('#add_pos_name').val(label);
            $('#add_pos_desc').val('POS Pembayaran ' + label);
        }
        $('#add_account_desc').on('blur', fillPosLabels);
        $('#add_account_majors_id').on('change', fillPosLabels);

        $('#add_payment_type').on('change', function() {
            if ($(this).val() === 'BULAN') {
                $addModal.find('.toggle-by-type').show();
            } else {
                $addModal.find('.toggle-by-type').hide();
            }
        });

        // Trigger Edit Modal (Delegated)
        $(document).on('click', '.btn-trigger-edit', function() {
            var id = $(this).data('id');
            var code = $(this).data('code');
            var desc = $(this).data('desc');
            var note = $(this).data('note');
            var cat = $(this).data('category');
            var majorsId = $(this).data('majors-id');

            $('#edit_account_id').val(id);
            $('#edit_account_code').val(code);
            $('#edit_account_desc').val(desc);
            $('#edit_account_note').val(note);
            $('#edit_account_category').val(cat);
            $('#edit_account_majors_id').val(majorsId);

            $('#modal-edit-account').modal('show');
        });

        // Trigger Delete Modal (Delegated)
        $(document).on('click', '.btn-trigger-delete', function() {
            var id = $(this).data('id');
            var code = $(this).data('code');

            $('#form-delete-account').attr('action', '<?php echo site_url("manage/account/delete/"); ?>' + id);
            $('#delete_delCode').val(code);

            $('#modal-delete-account').modal('show');
        });

        <?php if ($is_super): ?>
        // ----------------------------------------------------
        // HIERARCHY CHECKBOX & MULTI-DELETE (SUPER ADMIN)
        // ----------------------------------------------------
        $('#check-all').on('change', function() {
            $('.check-item').prop('checked', $(this).prop('checked'));
        });

        $(document).on('change', '.check-item', function() {
            var isChecked = $(this).prop('checked');
            var id = $(this).data('id');
            var parentId = $(this).data('parent');

            function toggleChildren(parentId, checked) {
                $('.check-item[data-parent="' + parentId + '"]').each(function() {
                    $(this).prop('checked', checked);
                    toggleChildren($(this).data('id'), checked);
                });
            }
            toggleChildren(id, isChecked);

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
                if (typeof toastNotifikasi === 'function') {
                    toastNotifikasi('error', 'Pilih minimal satu data untuk dihapus.', 'Gagal');
                } else {
                    alert('Pilih minimal satu data untuk dihapus.');
                }
            } else {
                $('#modal-multi-delete').modal('show');
            }
        });

        $('#btn-confirm-multi-delete').on('click', function() {
            $('#form-multi-delete')[0].submit();
        });
        <?php endif; ?>
    });
</script>