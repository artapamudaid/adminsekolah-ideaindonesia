<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo isset($title) ? '' . $title : null; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
            <li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Form <?php echo isset($title) ? '' . $title : null; ?></h3>
                    </div>

                    <form action="<?php echo site_url('manage/setting_akuntansi/save'); ?>" method="post">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-check"></i> Sukses!</h4>
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php } ?>

                            <div class="form-group">
                                <label>Mode Akrual</label>
                                <select name="mode" id="mode" class="form-control" required>
                                    <option value="manual" <?php echo (isset($akrual_setting) && $akrual_setting->mode == 'manual') ? 'selected' : ''; ?>>Manual</option>
                                    <option value="auto" <?php echo (isset($akrual_setting) && $akrual_setting->mode == 'auto') ? 'selected' : ''; ?>>Otomatis</option>
                                </select>
                            </div>

                            <div id="auto_settings" style="<?php echo (isset($akrual_setting) && $akrual_setting->mode == 'auto') ? 'display:block;' : 'display:none;'; ?>">
                                <div class="form-group">
                                    <label>Frekuensi</label>
                                    <select name="frequency" id="frequency" class="form-control">
                                        <option value="daily" <?php echo (isset($akrual_setting) && $akrual_setting->frequency == 'daily') ? 'selected' : ''; ?>>Harian (Daily)</option>
                                        <option value="weekly" <?php echo (isset($akrual_setting) && $akrual_setting->frequency == 'weekly') ? 'selected' : ''; ?>>Mingguan (Weekly)</option>
                                        <option value="monthly" <?php echo (isset($akrual_setting) && $akrual_setting->frequency == 'monthly') ? 'selected' : ''; ?>>Bulanan (Monthly)</option>
                                    </select>
                                </div>

                                <div class="form-group" id="run_time_group">
                                    <label>Waktu Eksekusi (Run Time) <span class="text-danger">*</span></label>
                                    <input type="time" name="run_time" id="run_time" class="form-control" value="<?php echo isset($akrual_setting->run_time) ? $akrual_setting->run_time : '00:00:00'; ?>">
                                </div>

                                <div class="form-group" id="run_day_group" style="<?php echo (isset($akrual_setting) && $akrual_setting->frequency == 'weekly') ? 'display:block;' : 'display:none;'; ?>">
                                    <label>Hari Eksekusi (Run Day) <span class="text-danger">*</span></label>
                                    <select name="run_day" id="run_day" class="form-control">
                                        <option value="">-- Pilih Hari --</option>
                                        <option value="1" <?php echo (isset($akrual_setting) && $akrual_setting->run_day == '1') ? 'selected' : ''; ?>>Senin</option>
                                        <option value="2" <?php echo (isset($akrual_setting) && $akrual_setting->run_day == '2') ? 'selected' : ''; ?>>Selasa</option>
                                        <option value="3" <?php echo (isset($akrual_setting) && $akrual_setting->run_day == '3') ? 'selected' : ''; ?>>Rabu</option>
                                        <option value="4" <?php echo (isset($akrual_setting) && $akrual_setting->run_day == '4') ? 'selected' : ''; ?>>Kamis</option>
                                        <option value="5" <?php echo (isset($akrual_setting) && $akrual_setting->run_day == '5') ? 'selected' : ''; ?>>Jumat</option>
                                        <option value="6" <?php echo (isset($akrual_setting) && $akrual_setting->run_day == '6') ? 'selected' : ''; ?>>Sabtu</option>
                                        <option value="0" <?php echo (isset($akrual_setting) && $akrual_setting->run_day == '0') ? 'selected' : ''; ?>>Minggu</option>
                                    </select>
                                </div>

                                <div class="form-group" id="run_date_group" style="<?php echo (isset($akrual_setting) && $akrual_setting->frequency == 'monthly') ? 'display:block;' : 'display:none;'; ?>">
                                    <label>Tanggal Eksekusi (Run Date) <span class="text-danger">*</span></label>
                                    <select name="run_date" id="run_date" class="form-control">
                                        <option value="">-- Pilih Tanggal --</option>
                                        <?php for ($i = 1; $i <= 31; $i++) { ?>
                                            <option value="<?php echo $i; ?>" <?php echo (isset($akrual_setting) && $akrual_setting->run_date == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Pengaturan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var modeSelect = document.getElementById('mode');
        var frequencySelect = document.getElementById('frequency');

        var autoSettings = document.getElementById('auto_settings');
        var runDayGroup = document.getElementById('run_day_group');
        var runDateGroup = document.getElementById('run_date_group');
        var runTimeInput = document.getElementById('run_time');
        var runDayInput = document.getElementById('run_day');
        var runDateInput = document.getElementById('run_date');

        function toggleMode() {
            if (modeSelect.value === 'auto') {
                autoSettings.style.display = 'block';
                toggleFrequency();
            } else {
                autoSettings.style.display = 'none';
                runTimeInput.removeAttribute('required');
                runDayInput.removeAttribute('required');
                runDateInput.removeAttribute('required');
            }
        }

        function toggleFrequency() {
            if (modeSelect.value !== 'auto') return;

            if (frequencySelect.value === 'daily') {
                runDayGroup.style.display = 'none';
                runDateGroup.style.display = 'none';

                runDayInput.removeAttribute('required');
                runDateInput.removeAttribute('required');
                runTimeInput.setAttribute('required', 'required');

            } else if (frequencySelect.value === 'weekly') {
                runDayGroup.style.display = 'block';
                runDateGroup.style.display = 'none';

                runDayInput.setAttribute('required', 'required');
                runDateInput.removeAttribute('required');
                runTimeInput.setAttribute('required', 'required');

            } else if (frequencySelect.value === 'monthly') {
                runDayGroup.style.display = 'none';
                runDateGroup.style.display = 'block';

                runDayInput.removeAttribute('required');
                runDateInput.setAttribute('required', 'required');
                runTimeInput.setAttribute('required', 'required');
            }
        }

        modeSelect.addEventListener('change', toggleMode);
        frequencySelect.addEventListener('change', toggleFrequency);

        // Initial call to set correct required attributes
        toggleMode();
    });
</script>