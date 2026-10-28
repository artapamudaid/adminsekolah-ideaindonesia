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
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id='dtable' class='table table-hover table-responsive'>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="15%">Kode Akun</th>
                                    <th>Nama Akun</th>
                                    <th>Keterangan</th>
                                    <th>Jenis Akun</th>
                                    <th>Kategori</th>
                                    <th>Unit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($accounts as $acc):
                                    $isHeader = $acc->has_child == 1; // kalau punya anak, berarti header
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <?= $isHeader
                                                ? "<b>{$acc->account_code}</b>"
                                                : $acc->account_code ?></td>
                                        <td>
                                            <?= $isHeader
                                                ? "<b>{$acc->account_description}</b>"
                                                : $acc->account_description ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo ($acc->has_child == 1) ? '<b>Header</b>' : 'Detail';
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($isHeader) {
                                                echo '<b>';
                                                echo ($acc->level == 0) ? 'Akun Utama' : 'Sub Akun ' . $acc->level;
                                                echo '</b>';
                                            } else {
                                                echo ($acc->level == 0) ? 'Akun Utama' : 'Sub Akun ' . $acc->level;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (!$isHeader) {
                                                if ($acc->account_category == 1) {
                                                    echo 'Pembayaran';
                                                } else if ($acc->account_category == 2) {
                                                    echo 'Keuangan';
                                                } else {
                                                    echo '<b>#</b>';
                                                }
                                            } else {
                                                echo '<b>#</b>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?= $isHeader ? "<b>" : '' ?>
                                            <?= $acc->majors_short_name ?? 'Semua Unit' ?>
                                            <?= $isHeader ? "</b>" : '' ?>
                                        </td>
                                        <td align="left">
                                            <button type="button"
                                                class="btn btn-success btn-xs btn-add-sub"
                                                data-id="<?= $acc->account_id ?>"
                                                title="Tambah Sub Akun">
                                                <i class="fa fa-plus"></i>
                                            </button>

                                            <?php if ($acc->level != 0) { ?>
                                                <button type="button"
                                                    class="btn btn-warning btn-xs btn-edit-sub"
                                                    data-id="<?= $acc->account_id ?>"
                                                    title="Edit Sub Akun">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            <?php } ?>

                                            <?php if ($acc->has_child == 0) { ?>
                                                <button type="button"
                                                    class="btn btn-danger btn-xs btn-deleted-sub"
                                                    data-id="<?= $acc->account_id ?>"
                                                    title="Hapus Sub Akun">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            <?php } ?>


                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
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