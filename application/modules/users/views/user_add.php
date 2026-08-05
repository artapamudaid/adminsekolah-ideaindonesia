<?php

if (isset($user)) {
	$id = $user['user_id'];
	$inputFullnameValue = $user['user_full_name'];
	$inputRoleValue = $user['user_role_role_id'];
	$inputMajorsValue = $user['user_majors_id'];
	$inputEmailValue = $user['user_email'];
	$inputDescValue = $user['user_description'];
} else {
	$inputFullnameValue = set_value('user_full_name');
	$inputRoleValue = set_value('user_role_role_id');
	$inputMajorsValue = set_value('user_majors_id');
	$inputEmailValue = set_value('user_email');
	$inputDescValue = set_value('user_description');
}
?>

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li><a href="<?php echo site_url('manage/users') ?>">Manage Users</a></li>
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
						<?php if (isset($user)) { ?>
							<input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
						<?php } ?>
						<div class="form-group">
							<label>Email <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="user_email" type="text" class="form-control" <?php echo ($this->session->userdata('uroleid') == '1' or $this->session->userdata('uroleid') == '3') ? '' : 'disabled'; ?> value="<?php echo $inputEmailValue ?>" placeholder="Email" required>
						</div>

						<div class="form-group">
							<label>Nama lengkap <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="user_full_name" type="text" class="form-control" value="<?php echo $inputFullnameValue ?>" placeholder="Nama lengkap" required>
						</div>

						<?php if (!isset($user)) { ?>
							<div class="form-group">
								<label>Password <small data-toggle="tooltip" title="Wajib diisi">*</small>
								</label>
								<button type="button" class="btn btn-xs btn-info pull-right" onclick="generatePassword()">Generate Password</button>
								<div class="input-group">
									<input name="user_password" id="user_password" type="password" class="form-control" placeholder="Password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$" title="Password minimal 12 karakter, mengandung huruf besar, kecil, angka, dan karakter khusus." required>
									<span class="input-group-addon" onclick="togglePassword('user_password', 'toggleIcon1')"><i class="fa fa-eye" id="toggleIcon1"></i></span>
								</div>
							</div>

							<div class="form-group">
								<label>Konfirmasi Password <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
								<div class="input-group">
									<input name="passconf" id="passconf" type="password" class="form-control" placeholder="Konfirmasi Password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$" title="Password minimal 12 karakter, mengandung huruf besar, kecil, angka, dan karakter khusus." required>
									<span class="input-group-addon" onclick="togglePassword('passconf', 'toggleIcon2')"><i class="fa fa-eye" id="toggleIcon2"></i></span>
								</div>
							</div>
						<?php } ?>

						<div class="form-group">
							<label>Deskripsi</label>
							<textarea class="form-control" name="user_description" placeholder="Deskripsi"><?php echo $inputDescValue ?></textarea>
						</div>
						<?php if ($inputRoleValue == '1' or $inputRoleValue == '3') { ?>
							<input type="hidden" name="role_id" value="<?php echo $inputRoleValue; ?>">
						<?php } else { ?>
							<div class="form-group">
								<label>Hak Akses <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
								<select name="role_id" class="form-control" required>
									<option value="">-Pilih Hak Akses-</option>
									<?php foreach ($roles as $row): ?>
										<option value="<?php echo $row['role_id']; ?>" <?php echo ($inputRoleValue == $row['role_id']) ? 'selected' : '' ?>><?php echo $row['role_name']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						<?php } ?>

						<?php if ($inputRoleValue == '3') { ?>
							<input type="hidden" name="majors_id" value="<?php echo $inputMajorsValue; ?>">
						<?php } else { ?>
							<div class="form-group">
								<label>Unit Sekolah <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
								<select name="majors_id" class="form-control" required>
									<option value="">-Pilih Unit Sekolah-</option>
									<?php if ($this->session->userdata('umajorsid') == '0') { ?>
										<option value="0">Semua Unit Sekolah</option>
									<?php } ?>
									<?php foreach ($majors as $row): ?>
										<option value="<?php echo $row['majors_id']; ?>" <?php echo ($inputMajorsValue == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						<?php } ?>
						<p class="text-muted">*) Kolom wajib diisi.</p>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
			<div class="col-md-3">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<label>Foto</label>
						<a href="#" class="thumbnail">
							<?php if (isset($user) and $user['user_image'] != NULL) { ?>
								<img src="<?php echo upload_url('users/' . $user['user_image']) ?>" class="img-responsive avatar">
							<?php } else { ?>
								<img id="target" alt="Choose image to upload">
							<?php } ?>
						</a>
						<input type='file' id="user_image" name="user_image">
						<br>
						<button type="submit" class="btn btn-block btn-success">Simpan</button>
						<a href="<?php echo site_url('manage/users'); ?>" class="btn btn-block btn-info">Batal</a>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>
<script src="<?php echo media_url() ?>/js/jquery.min.js"></script>
<script type="text/javascript">
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#target').attr('src', e.target.result);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}

	$("#user_image").change(function() {
		readURL(this);
	});

	function generatePassword() {
		var length = 7,
			charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=";
		var hasUpper = false;
		var hasLower = false;
		var hasNum = false;
		var hasSym = false;
		var randomPart = "";
		while (!(hasUpper && hasLower && hasNum && hasSym) || randomPart.length < length) {
			randomPart = "";
			hasUpper = false;
			hasLower = false;
			hasNum = false;
			hasSym = false;
			for (var i = 0, n = charset.length; i < length; ++i) {
				var char = charset.charAt(Math.floor(Math.random() * n));
				randomPart += char;
				if (/[a-z]/.test(char)) hasLower = true;
				if (/[A-Z]/.test(char)) hasUpper = true;
				if (/[0-9]/.test(char)) hasNum = true;
				if (/[\W_]/.test(char)) hasSym = true;
			}
		}
		var d = new Date();
		var m = ("0" + (d.getMonth() + 1)).slice(-2);
		var y = d.getFullYear().toString().substr(-2);
		var retVal = randomPart + "#" + m + y;

		document.getElementById("user_password").value = retVal;
		document.getElementById("passconf").value = retVal;
		document.getElementById("user_password").type = "text";
		document.getElementById("passconf").type = "text";
	}

	function togglePassword(inputId, iconId) {
		var x = document.getElementById(inputId);
		var icon = document.getElementById(iconId);
		if (x.type === "password") {
			x.type = "text";
			icon.classList.remove('fa-eye');
			icon.classList.add('fa-eye-slash');
		} else {
			x.type = "password";
			icon.classList.remove('fa-eye-slash');
			icon.classList.add('fa-eye');
		}
	}
</script>