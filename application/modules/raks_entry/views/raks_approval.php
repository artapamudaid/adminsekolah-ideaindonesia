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
                        <?php if ($majors_id == '0') { ?>
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
                        <?php } ?>
                    </div>

                </div>

                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>