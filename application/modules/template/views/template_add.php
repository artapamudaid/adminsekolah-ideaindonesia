<style>
    .button-group {
      margin-bottom: 15px;
      text-align: left;
    }

    .btn-insert {
      background-color: #5b89d3;
      color: #fff;
      font-weight: 500;
      border: none;
      padding: 8px 16px;
      margin: 5px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.2s;
    }

    .btn-insert:hover {
      background-color: #3d5c8e;
    }

    textarea {
      width: 100%;
      height: 300px;
      border-radius: 6px;
      border: 1px solid #ccc;
      padding: 10px;
      font-size: 14px;
      resize: vertical;
    }
</style>

<?php

if (isset($template)) {

	$inputLabelValue     = $template['title'];
	$inputContentValue   = $template['content'];
	$inputLocationValue  = $template['location'];
	$inputStatusValue    = $template['is_active'];
	
} else {
	
	$inputLabelValue     = set_value('title');
	$inputContentValue   = set_value('content');
	$inputLocationValue  = set_value('location');
	$inputStatusValue    = set_value('is_active');
}
?>

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
		<?php echo form_open_multipart(current_url()); ?>
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<?php echo validation_errors(); ?>
						<?php if (isset($template)) { ?>
						<input type="hidden" name="id" value="<?php echo $template['id']; ?>">
						<?php } ?>
						
                        <div class="form-group">
							<label>Modul <small data-bs-toggle="tooltip" title="Wajib diisi">*</small></label>
                            <select  id="location" name="location" class="form-control" required>
                                <option value="">--- Pilih Modul  ---</option>
                                <?php 
                                    foreach ($locations as $loc) :
                                ?>
                                    <option value="<?= $loc['kode'] ?>" <?php echo ($inputLocationValue == $loc['kode']) ? 'selected' : '' ?>><?= $loc['value'] ?></option>
                                <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>
						
                        <div class="form-group">
							<label>Label <small data-bs-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="title" type="text" class="form-control" value="<?php echo $inputLabelValue ?>" placeholder="Label Notifkasi">
						</div>
                        
						<div class="form-group">
							<label>Isi Notifikasi <small data-bs-toggle="tooltip" title="Wajib diisi">*</small></label>
                            <div class="button-group">
                                <button type="button" class="btn-insert" data-value="[STUDENT_NIS]">[NOMOR INDUK SISWA]</button>
                                <button type="button" class="btn-insert" data-value="[STUDENT_FULL_NAME]">[NAMA SISWA]</button>
                                <button type="button" class="btn-insert" data-value="[EMPLOYEE_NIP]">[NOMOR INDUK KARYAWAN]</button>
                                <button type="button" class="btn-insert" data-value="[EMPLOYEE_NAME]">[NAMA PEGAWAI]</button>
                                <button type="button" class="btn-insert" data-value="[POSITION_NAME]">[JABATAN]</button>
                                <button type="button" class="btn-insert" data-value="[CLASS_NAME]">[KELAS]</button>
                                <button type="button" class="btn-insert" data-value="[MAJORS_SCHOOL_NAME]">[UNIT]</button>
                                <button type="button" class="btn-insert" data-value="[MAJORS_SHORT_NAME]">[SINGKATAN UNIT]</button>
                                <button type="button" class="btn-insert" data-value="[NOMINAL]">[NOMINAL]</button>
                                <button type="button" class="btn-insert" data-value="[TRX_DATE]">[TANGGAL TRANSAKSI]</button>
                                <button type="button" class="btn-insert" data-value="[NOREF]">[NOREF]</button>
                                <button type="button" class="btn-insert" data-value="[MONTH_NAME]">[BULAN]</button>
                                <button type="button" class="btn-insert" data-value="[YEAR]">[TAHUN]</button>
                            </div>

							<textarea name="content" id="content" rows="20" class="form-control" required><?php echo $inputContentValue ?></textarea>
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
                        <div class="form-group">
							<label>Status Notifikasi</label>
							<div class="radio">
								<label>
									<input type="radio" name="is_active" value="Y" <?php echo ($inputStatusValue == 'Y') ? 'checked' : ''; ?>> Aktif
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="is_active" value="N" <?php echo ($inputStatusValue == 'N') ? 'checked' : ''; ?>> Non Aktif
								</label>
							</div>
						</div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-success">Simpan</button>
                            <a href="<?php echo site_url('manage/template') ?>" class="btn btn-block btn-default">Batal</a>
                        </div>
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
    const textarea = document.getElementById('content');
    const locationSelect = document.getElementById('location');
    const statusRadios = document.querySelectorAll('input[name="is_active"]');
    const insertButtons = document.querySelectorAll('.btn-insert');

    insertButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const value = btn.dataset.value;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;

        textarea.value = text.substring(0, start) + value + text.substring(end);
        textarea.selectionStart = textarea.selectionEnd = start + value.length;
        textarea.focus();
      });
    });
</script>

<?php if ($this->uri->segment('3') == 'edit') : ?>

<script>
    function toggleFields() {
        const locValue = locationSelect.value;

        locationSelect.setAttribute('disabled', true);

        let hiddenLocation = document.querySelector('input[name="location_hidden"]');
        if (!hiddenLocation) {
            hiddenLocation = document.createElement('input');
            hiddenLocation.type = 'hidden';
            hiddenLocation.name = 'location';
            hiddenLocation.value = locValue;
            locationSelect.parentNode.appendChild(hiddenLocation);
        } else {
            hiddenLocation.value = locValue;
        }

        if (locValue && locValue !== 'PENGUMUMAN') {
            statusRadios.forEach(r => r.setAttribute('disabled', true));
        } else {
            statusRadios.forEach(r => r.removeAttribute('disabled'));
        }

        
    }

    toggleFields();
</script>
<?php endif ?>