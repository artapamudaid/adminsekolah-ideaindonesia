<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>b-asset/css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>b-asset/css/iofrm-theme9.css">

    <link href="<?php echo media_url() ?>css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo media_url() ?>css/font-awesome.min.css" rel="stylesheet" />
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	
	<style>
        .bubble {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #007bff;
            color: white;
            font-size: 1.2rem;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .bubble:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }
        .bubble i {
            font-size: 3rem;
        }
        .bubble-container {
            position: fixed;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            bottom: 30px;
            z-index: 10;
        }
    </style>
</head>

<body>
    <div class="form-body">
        <div class="row">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">
                    <center>
                            <img src="https://adminsekolah.net/wp-content/uploads/2023/07/logo-adminsekolah-baru.png" class="img-responsive" style="width:50%">
                    </center>
                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items" style="max-width:380px">
                    <h3 style="text-align: center;padding-bottom: 45px;">Halaman Registrasi</h3>
                        <div class="website-logo-inside">
                        <div class="page-links">
                            <a href="<?php echo base_url() ?>">Login</a
                            >
                            <a href="<?php echo base_url('registrasi') ?>" class="active">Daftar Di Sini</a>
                         
                        </div>
                        <?php echo form_open_multipart(current_url()); ?>
                            <input name="registrasi_name" type="text" class="form-control" value="" placeholder="Nama Lengkap" required="">

                            <input name="registrasi_school" type="text" class="form-control" value="" placeholder="Nama Sekolah/Yayasan" required="">

                            <select name="registrasi_province" id="registrasi_province" class="form-control" onchange="get_city()" required="">
                            <option value=""> -- Pilih Provinsi -- </option>
                            <?php foreach ($province as $row): ?>
                                <option value="<?php echo $row['province_id'] ?>" ><?php echo $row['province_name'] ?></option>
                            <?php endforeach ?>
							</select>
                            &nbsp
							<div id="div_city">
                            <select name="registrasi_city" id="registrasi_city" class="form-control" required="">
                                <option value=""> -- Pilih Kota/Kabupaten -- </option>
							</select>
							</div>
                            &nbsp
							
                            <select name="registrasi_sumber" id="registrasi_sumber" class="form-control" required="">
                                <option value="" <?php echo ($medium == '') ? 'selected' : '' ?>>Tahu AdminSekolah Dari ?</option>
								<option value="Website" <?php echo ($medium == 'website_header') ? 'selected' : '' ?>>Website</option>
								<option value="CS AdminSekolah" <?php echo ($medium == 'cs_header') ? 'selected' : '' ?>>CS AdminSekolah</option>
								<option value="Whatsapp" <?php echo ($medium == 'whatsapp_header') ? 'selected' : '' ?>>Whatsapp</option>
								<option value="Instagram" <?php echo ($medium == 'instagram_header') ? 'selected' : '' ?>>Instagram</option>
								<option value="Facebook" <?php echo ($medium == 'facebook_header') ? 'selected' : '' ?>>Facebook</option>
								<option value="Tiktok" <?php echo ($medium == 'tiktok_header') ? 'selected' : '' ?>>Tiktok</option>
								<option value="Teman/Sekolah Lain" <?php echo ($medium == 'teman_header') ? 'selected' : '' ?>>Teman/Sekolah Lain</option>
							</select>
							
                            &nbsp
                            <textarea class="form-control" name="registrasi_address" placeholder="Alamat"></textarea>

                            <input name="registrasi_email" type="email" class="form-control" value="" placeholder="Email" required="">

                            <input name="registrasi_phone" type="number" class="form-control" value="" maxlength="14" placeholder="Nomor Telepon" required="">

                            <?php if(ENVIRONMENT=='production'): ?>
							<div class="g-recaptcha" data-sitekey="6LeuXAwrAAAAABgfW5GvcAVx1lNt4NYKqfrCOeR3"></div>
                            <?php endif;?>
                            <p style="font-size: 14px;">username & password akan dikirim via Email/Whatsapp</p>
                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">Daftar</button>
                                <div id="pesan" style="display:none;"><p>Username & Password sedang dikirim ke Email/WhatsApp Anda</p></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
		<div class="bubble-container">
        <a href="https://tutorial.adminsekolah.net" class="bubble" style="background-color: #B8B8B8; color: #000;" target="_blank">
        <i class="bi bi-journal-check"></i>
        </a>
			
			<p style="margin-top:0px; color:#fff; font-weight:bold;">
		Tutorial
			</p>
        <!-- WhatsApp Bubble -->
        <a href="https://wa.me/6281233640003" class="bubble bg-success" target="_blank">
        <i class="bi bi-whatsapp"></i>
        </a>
			
			<p style="margin-top:0px; color:#fff; font-weight:bold;">
		Hubungi CS
			</p>
    </div>
    </div>
    <script src="https://brandio.io/envato/iofrm/html/js/jquery.min.js"></script>
    <script src="https://brandio.io/envato/iofrm/html/js/popper.min.js"></script>
    <script src="https://brandio.io/envato/iofrm/html/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>b-asset/js/main.js"></script>
    <script src="<?php echo base_url() ?>b-asset/jquery-1.9.1.min"></script>
    
</body>

</html>
<script src="<?php echo media_url() ?>/js/jquery.min.js"></script>
<script src="<?php echo base_url() ?>b-asset/js/bootstrap-notify.js"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>

<script>
	function get_city(){
	    var province_id    = $("#registrasi_province").val();
	    
        $.ajax({ 
            url: '<?php echo base_url();?>registrasi/get_city',
            type: 'POST', 
            data: {
                    'province_id': province_id,
            },    
            success: function(msg) {
                    $("#div_city").html(msg);
            },
			error: function(msg){
					alert('msg');
			}
            
        });
	}
</script>

<script>
//   function myFunction() {
//     var x = document.getElementById("submit");
//     x.disabled = true;
//     }

$(document).ready(function(){
   $("#submit").click(function(){             
      $("#submit").hide();
      $("#pesan").show();
   });
});
</script>

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
</script>

<script>
   $("#login_form").validate();
</script>
<script>
   $(document).ready(function(){
     $("[rel=tooltip]").tooltip({ placement: 'top'});
   });
</script>
<!-- Notification Script -->
<script>
   <?php
      $success = $this->session->flashdata('success');
      $error   = $this->session->flashdata('error');
      if (!empty($success))
       {
      ?>
    $.notify({
         
         icon: 'glyphicon glyphicon-info-sign',
         title: '<b>Notification</b><br>',
         message: '<?php echo $success ?>',
     },
     {
         type: "success success-noty col-md-3",
         allow_dismiss: true,
         placement: {
             from: "top",
             align: "right"
         },
         offset: 20,
         spacing: 10,
         z_index: 1431,
         delay: 5000,
         timer: 1000,
         animate: {
             enter: 'animated bounceInDown',
             exit: 'animated bounceOutUp'
         }
     });
   <?php
      } 
      if (!empty($error))
       {
      ?>
    $.notify({
             
             icon: 'glyphicon glyphicon-info-sign',
             title: '<b>Notification</b><br>',
             message: '<?php echo $error ?>',
         },{
             
             
             type: "danger noty-color col-md-3",
             allow_dismiss: true,
             placement: {
                 from: "top",
                 align: "right"
             },
             offset: 20,
             spacing: 10,
             z_index: 1431,
             delay: 5000,
             timer: 1000,
             animate: {
                 enter: 'animated fadeInDown',
                 exit: 'animated fadeOutUp'
             }
         });
    <?php            
      }
      ?>
   
</script>  
<script>
   <?php
      if (!empty($message))
        {
      ?>
    $.notify({
         
         icon: 'glyphicon glyphicon-info-sign',
         title: '<b>Notification</b><br>',
         message: '<?php echo $message;?>',
     },
     {
         
         type: "success success-noty col-md-3 col-md-offset-2",
         allow_dismiss: true,
         placement: {
             from: "top",
             align: "right"
         },
         offset: 20,
         spacing: 10,
         z_index: 1431,
         delay: 5000,
         timer: 1000,
         animate: {
             enter: 'animated bounceInDown',
             exit: 'animated bounceOutUp'
         }
     });
   <?php
      }
      ?> 
</script> 
<script>
   <?php  
      if (!empty($_GET['err_msg']))  
      {
      ?> 
      $.notify({
      title: '<strong><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Error!</strong>',
      message: '<?php echo $_GET['err_msg'] ?>'
      },{
        type: 'danger alert-danger-alt col-md-3 col-md-offset-3'
      });
     <?php 
      } 
       elseif (!empty($_GET['success_msg']))  
      {
       ?>
   
      $.notify({
      title: '<strong> <span class= "glyphicon glyphicon-ok"></span> Success!</strong>',
      message: '<?php echo $_GET['success_msg'] ?>'
      },{
        type: 'success alert-success-alt col-md-3'
      });
   
    <?php    
      }    
      ?>  
   
</script>
<script>
    function showPassword() {
        var input = document.getElementById("password");
            if (input.type === "password") {
                input.type = "teinputt";
            } else {
                input.type = "password";
        }
    }
</script>
