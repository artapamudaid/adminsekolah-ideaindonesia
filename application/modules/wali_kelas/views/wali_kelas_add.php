<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo isset($title) ? '' . $title : null; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
            <li><a href="<?php echo site_url('manage/wali_kelas') ?>">Wali Kelas</a></li>
            <li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <!-- Main content -->
    <section class="content">
        <?php echo form_open_multipart(current_url()); ?>
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-9">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?php echo validation_errors(); ?>
                        <?php if (isset($wali_kelas)) { ?>
                            <input type="hidden" name="wali_kelas_id" value="<?php echo $wali_kelas['wali_kelas_id']; ?>">
                            <input type="hidden" name="wali_kelas_old" value="<?php echo $wali_kelas['wali_kelas_employee_id']; ?>">
                        <?php } ?>

                        <div class="form-group">
                            <label>Tahun Ajaran *</label>
                            <select required="" name="wali_kelas_period_id" class="form-control" onchange="getSemester(this.value)">
                                <option value="">-Pilih Tahun Ajaran-</option>
                                <?php foreach ($period as $p) { ?>
                                    <option value="<?php echo $p['period_id'] ?>" <?= ($wali_kelas['period_id'] == $p['period_id']) ? 'selected' : '' ?>><?php echo $p['period_start'] . '/' . $p['period_end'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Semester *</label>
                            <select required="" name="wali_kelas_semester_id" class="form-control">
                                <option value="">-Pilih Semester-</option>
                                <?php foreach ($semester as $s) { ?>
                                    <option value="<?php echo $s['semester_id'] ?>" <?= ($wali_kelas['semester_id'] == $s['semester_id']) ? 'selected' : '' ?>><?php echo $s['semester_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label>Unit</label>
                        <select required="" name="wali_kelas_majors_id" class="form-control" onchange="getClass(this.value)">
                            <option value="">-Pilih Unit-</option>
                            <?php foreach ($majors as $m) { ?>
                                <option value="<?php echo $m['majors_id'] ?>" <?= ($wali_kelas['majors_majors_id'] == $m['majors_id']) ? 'selected' : '' ?>><?php echo $m['majors_short_name'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="form-group">
                            <label>Kelas *</label>
                            <select required="" name="wali_kelas_class_id" class="form-control">
                                <option value="">-Pilih Kelas-</option>
                                <?php foreach ($classes as $class) { ?>
                                    <option value="<?php echo $class['class_id'] ?>" <?= ($wali_kelas['wali_kelas_class_id'] == $class['class_id']) ? 'selected' : '' ?>><?php echo $class['class_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama Wali Kelas *</label>
                            <select required="" name="wali_kelas_employee_id" class="form-control">
                                <option value="">-Pilih Guru-</option>
                                <?php foreach ($employees as $employee) { ?>
                                    <option value="<?php echo $employee['employee_id'] ?>" <?= ($wali_kelas['wali_kelas_employee_id'] == $employee['employee_id']) ? 'selected' : '' ?>><?php echo $employee['employee_nip'] . ' - ' . $employee['employee_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <p class="text-muted">*) Kolom wajib diisi.</p>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <button type="submit" class="btn btn-block btn-success">Simpan</button>
                        <a href="<?php echo site_url('manage/wali_kelas'); ?>" class="btn btn-block btn-info">Batal</a>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
        <!-- /.row -->
    </section>
</div>

<script>
    getSemester = function(period_id) {
        $.ajax({
            url: "<?php echo site_url('manage/wali_kelas/get_semester') ?>",
            type: "POST",
            data: {
                period_id: period_id
            },
            dataType: "json",
            success: function(data) {
                var options = '<option value="">-Pilih Semester-</option>';
                $.each(data, function(index, semester) {
                    options += '<option value="' + semester.semester_id + '">' + semester.semester_name + '</option>';
                });
                $('select[name="wali_kelas_semester_id"]').html(options);
            }
        });
    };

    getClass = function(majors_id) {
        $.ajax({
            url: "<?php echo site_url('manage/wali_kelas/get_class') ?>",
            type: "POST",
            data: {
                majors_id: majors_id
            },
            dataType: "json",
            success: function(data) {
                var options = '<option value="">-Pilih Kelas-</option>';
                $.each(data, function(index, kelas) {
                    options += '<option value="' + kelas.class_id + '">' + kelas.class_name + '</option>';
                });
                $('select[name="wali_kelas_class_id"]').html(options);
            }
        });
    };
</script>