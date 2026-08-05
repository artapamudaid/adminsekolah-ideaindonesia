<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Reset Password
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		<?php echo form_open(current_url()); ?>
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<?php if (isset($user) && isset($user['user_password_last_update'])) { ?>
							<div class="alert alert-info alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<h4><i class="icon fa fa-info"></i> Info!</h4>
								Password terakhir diubah pada <?php echo time_passed($user['user_password_last_update']); ?>.
							</div>
						<?php } ?>
						<?php echo form_open(current_url()); ?>
						<?php echo validation_errors(); ?>
						<div class="form-group">
							<?php if ($this->uri->segment(3) == 'cpw') { ?>
								<label>Password lama *</label>
								<input type="password" name="user_current_password" class="form-control" placeholder="Password lama">
							<?php } ?>
						</div>
						<div class="form-group">
							<label>Password baru*
							</label>
							<button type="button" class="btn btn-xs btn-info pull-right" onclick="generatePassword()">Generate Password</button>
							<div class="input-group">
								<input type="password" name="user_password" id="user_password" class="form-control" placeholder="Password baru" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$&])[a-zA-Z\d@#$&]{12,}$" title="Password minimal 12 karakter, mengandung huruf besar, kecil, angka, dan karakter khusus (@#$&)." required>
								<span class="input-group-addon" onclick="togglePassword('user_password', 'toggleIcon1')"><i class="fa fa-eye" id="toggleIcon1"></i></span>
							</div>
							<?php if ($this->uri->segment(3) == 'cpw') { ?>
								<input type="hidden" name="user_id" value="<?php echo $this->session->userdata('uid'); ?>">
							<?php } else { ?>
								<input type="hidden" name="user_id" value="<?php echo $user['user_id'] ?>">
							<?php } ?>
						</div>
						<div class="form-group">
							<label> Konfirmasi password baru*</label>
							<div class="input-group">
								<input type="password" name="passconf" id="passconf" class="form-control" placeholder="Konfirmasi password baru" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$&])[a-zA-Z\d@#$&]{12,}$" title="Password minimal 12 karakter, mengandung huruf besar, kecil, angka, dan karakter khusus (@#$&)." required>
								<span class="input-group-addon" onclick="togglePassword('passconf', 'toggleIcon2')"><i class="fa fa-eye" id="toggleIcon2"></i></span>
							</div>
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
<script>
	function generatePassword() {
		var length = 7,
			charset = "abcdefghjklmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ0123456789@#$&";
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
				if (/[@#$&]/.test(char)) hasSym = true;
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