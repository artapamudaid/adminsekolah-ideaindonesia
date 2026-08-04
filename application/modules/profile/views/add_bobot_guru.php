<?php

if (isset($bobot)) {

    $inputAbsensiValue = $bobot['absensi'];
    $inputHarianValue = $bobot['harian'];
    $inputSumatifValue = $bobot['sumatif'];
} else {
    $inputAbsensiValue = 0;
    $inputHarianValue = 0;
    $inputSumatifValue = 0;
}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content">
        <?php echo form_open_multipart(current_url()); ?>
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-9">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?php echo validation_errors(); ?>
                        <?php if (isset($bobot)) { ?>
                            <input type="hidden" name="empl_id" value="<?php echo $bobot['empl_id']; ?>">
                        <?php } ?>

                        <div class="form-group">
                            <label>Bobot Nilai Absensi <small data-bs-toggle="tooltip" title="Wajib diisi"></small></label>
                            <input name="absensi" type="number" class="form-control" value="<?php echo $inputAbsensiValue ?>" placeholder="Bobot Nilai Absensi">
                        </div>

                        <div class="form-group">
                            <label>Bobot Nilai Harian <small data-bs-toggle="tooltip" title="Wajib diisi"></small></label>
                            <input name="harian" type="number" class="form-control" value="<?php echo $inputHarianValue ?>" placeholder="Bobot Nilai Harian">
                        </div>

                        <div class="form-group">
                            <label>Bobot Nilai Sumatif <small data-bs-toggle="tooltip" title="Wajib diisi"></small></label>
                            <input name="sumatif" type="number" class="form-control" value="<?php echo $inputSumatifValue ?>" placeholder="Bobot Nilai Sumatif">
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
                        <a href="<?php echo site_url('guru/profile'); ?>" class="btn btn-block btn-info">Batal</a>

                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
        <!-- /.row -->
    </section>
</div>