<?php
$pkgActive = $setting_package['setting_value'];
$package = $this->db->query("SELECT package_name FROM package WHERE package_id = '$pkgActive'")->row_array();

$activeDate = $setting_date['setting_value'];

$datetime = new DateTime($activeDate);
$datetime->modify('+12 month');
$expired = $datetime->format('Y-m-d');

$today = date('Y-m-d');
$diff = abs(strtotime($expired) - strtotime($today));
$tahun = floor($diff / (365 * 60 * 60 * 24));
$bulan = floor(($diff - $tahun * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
$hari = floor(($diff - $tahun * 365 * 60 * 60 * 24 - $bulan * 30 * 60 * 60 * 24) / (60 * 60 * 24));
if ($tahun == 0) {
  $sisa =  $bulan . ' Bulan ' . $hari . ' Hari';
} else if ($tahun == 0 && $bulan == 0) {
  $sisa =  $hari . ' Hari';
} else {
  $sisa =  $tahun . ' Tahun ' . $bulan . ' Bulan ' . $hari . ' Hari';
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
      <li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <?php echo form_open_multipart(current_url()); ?>
      <div class="col-md-8">

        <div class="box-body">

          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Info Lembaga</a></li>
              <li><a href="#tab_2" data-toggle="tab">Kepengurusan</a></li>
              <li><a href="#tab_3" data-toggle="tab">Konfirgurasi</a></li>
              <li><a href="#tab_4" data-toggle="tab">Media Sosial</a></li>
              <li><a href="#tab_5" data-toggle="tab">Informasi Layaran</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">

                <input type="hidden" name="setting_level" value="<?php echo $setting_level['setting_value'] ?>" class="form-control">

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Nama Sekolah</label>
                      <input type="text" name="setting_school" value="<?php echo $setting_school['setting_value'] ?>" class="form-control" <?php if ($this->session->userdata('uroleid') != '3') {
                                                                                                                                              echo "readonly";
                                                                                                                                            } ?>>
                      <!-- <input type="hidden" name="setting_school" value="<?php echo $setting_school['setting_value'] ?>" class="form-control" > -->
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Alamat Sekolah</label>
                      <input name="setting_address" type="text" value="<?php echo $setting_address['setting_value'] ?>" class="form-control" <?php if ($this->session->userdata('uroleid') != '3') {
                                                                                                                                                echo "readonly";
                                                                                                                                              } ?>>
                      <!-- <input name="setting_address" type="hidden" value="<?php echo $setting_address['setting_value'] ?>" class="form-control" > -->
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Nama Kecamatan</label>
                      <input type="text" name="setting_district" value="<?php echo $setting_district['setting_value'] ?>" class="form-control" <?php if ($this->session->userdata('uroleid') != '3') {
                                                                                                                                                  echo "readonly";
                                                                                                                                                } ?>>
                      <!-- <input type="hidden" name="setting_district" value="<?php echo $setting_district['setting_value'] ?>" class="form-control" > -->
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Nama Kota/Kab</label>
                      <input type="text" name="setting_city" value="<?php echo $setting_city['setting_value'] ?>" class="form-control" <?php if ($this->session->userdata('uroleid') != '3') {
                                                                                                                                          echo "readonly";
                                                                                                                                        } ?>>
                      <!-- <input type="hidden" name="setting_city" value="<?php echo $setting_city['setting_value'] ?>" class="form-control" > -->
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Nomor Telepon</label>
                      <input type="text" name="setting_phone" value="<?php echo $setting_phone['setting_value'] ?>" class="form-control">
                    </div>
                  </div>
                </div>

              </div>
              <div class="tab-pane" id="tab_2">

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">NIP Kepsek</label>
                      <input type="text" name="setting_nip_kepsek" value="<?php echo $setting_nip_kepsek['setting_value'] ?>" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Nama Kepsek</label>
                      <input type="text" name="setting_nama_kepsek" value="<?php echo $setting_nama_kepsek['setting_value'] ?>" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">NIP Ka. TU</label>
                      <input type="text" name="setting_nip_katu" value="<?php echo $setting_nip_katu['setting_value'] ?>" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Nama Ka. TU</label>
                      <input type="text" name="setting_nama_katu" value="<?php echo $setting_nama_katu['setting_value'] ?>" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">NIP Bendahara</label>
                      <input type="text" name="setting_nip_bendahara" value="<?php echo $setting_nip_bendahara['setting_value'] ?>" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Nama Bendahara</label>
                      <input type="text" name="setting_nama_bendahara" value="<?php echo $setting_nama_bendahara['setting_value'] ?>" class="form-control">
                    </div>
                  </div>
                </div>

              </div>
              <div class="tab-pane" id="tab_3">
                <?php if ($this->session->userdata('uroleid') == '3') { ?>
                  <div class="row">
                    <div class="">
                      <div class="col-md-12">
                        <label class="control-label">WA Notifikasi</label>
                        <input type="text" name="setting_whatsapp" value="<?php echo $setting_whatsapp['setting_value'] ?>" class="form-control">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="">
                      <div class="col-md-12">
                        <label class="control-label">WA Key API</label>
                        <input type="text" name="setting_wa_key" value="<?php echo $setting_wa_key['setting_value'] ?>" class="form-control">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="">
                      <div class="col-md-12">
                        <label class="control-label">Status Notifikasi WA</label>
                        <select name="setting_status_wa" id="" class="form-control">
                          <option value="0" <?php echo ($setting_status_wa['setting_value'] == 0 ? 'selected' : '') ?>>
                            Free/Gratis
                          </option>
                          <option value="1" <?php echo ($setting_status_wa['setting_value'] == 1 ? 'selected' : '') ?>>
                            Berbayar
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="">
                      <div class="col-md-12">
                        <label class="control-label">Notifikasi WA</label>
                        <input type="checkbox" class="checkbox" <?php echo ($setting_notif['setting_value'] == 'active') ? 'checked' : ''; ?> onchange="changeAct('<?php echo ($setting_notif['setting_value'] == 'active') ? 'inactive' : 'active'; ?>')">
                      </div>
                    </div>
                  </div>

                <?php } else { ?>
                  <div class="row">
                    <div class="">
                      <div class="col-md-12">
                        <label class="control-label">Gunakan Limit Penarikan Tabungan</label>
                        <select name="use_limit" id="" class="form-control">
                          <option value="N" <?php echo ($use_limit['setting_value'] == 'N' ? 'selected' : '') ?>>
                            Tidak
                          </option>
                          <option value="Y" <?php echo ($use_limit['setting_value'] == 'Y' ? 'selected' : '') ?>>
                            Ya
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="">
                      <div class="col-md-12">
                        <label class="control-label">Limit Penarikan Tabungan</label>
                        <input type="number" name="limit_tabungan" value="<?php echo $limit_tabungan['setting_value'] ?? 0 ?>" class="form-control">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="">
                      <div class="col-md-12">
                        <label class="control-label">Status Notifikasi WA</label>
                        <input type="text" value="<?php echo ($setting_status_wa['setting_value'] == 0 ? 'Free/Gratis' : 'Berbayar') ?>" class="form-control" readonly>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="">
                      <div class="col-md-12">
                        <label class="control-label">Notifikasi WA</label>
                        <input type="text" value="<?php echo ucwords($setting_notif['setting_value']) ?>" class="form-control" readonly>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
              <div class="tab-pane" id="tab_4">

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Alamat Email</label>
                      <input type="text" name="setting_email" value="<?php echo $setting_email['setting_value'] ?>" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Website</label>
                      <input type="text" name="setting_website" value="<?php echo $setting_website['setting_value'] ?>" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Whatsapp</label>
                      <input type="text" name="setting_wa_center" value="<?php echo $setting_wa_center['setting_value'] ?>" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Facebook</label>
                      <input type="text" name="setting_facebook" value="<?php echo $setting_facebook['setting_value'] ?>" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Instagram</label>
                      <input type="text" name="setting_instagram" value="<?php echo $setting_instagram['setting_value'] ?>" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Youtube</label>
                      <input type="text" name="setting_youtube" value="<?php echo $setting_youtube['setting_value'] ?>" class="form-control">
                    </div>
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="tab_5">

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Paket</label>
                      <select name="package_id" id="package_id" class="form-control" <?php if ($this->session->userdata('uroleid') != '3') {
                                                                                        echo "readonly";
                                                                                      } ?>>
                        <option value="1" <?php echo ($pkgActive == 1 ? 'selected' : '') ?>>BAWAH</option>
                        <option value="2" <?php echo ($pkgActive == 2 ? 'selected' : '') ?>>TENGAH</option>
                        <option value="3" <?php echo ($pkgActive == 3 ? 'selected' : '') ?>>ATAS</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Tanggal Aktif</label>
                      <?php if ($this->session->userdata('uroleid') != '3') {
                      ?>
                        <div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                          <input class="form-control" required="" type="text" name="package_date" readonly="" placeholder="Tanggal Aktif" value="<?php echo pretty_date($activeDate, 'd F Y', false) ?>" disabled>
                        </div>
                      <?php
                      } else {
                      ?>
                        <input type="date" name="package_date" class="form-control" placeholder="Tanggal Aktif" value="<?php echo $activeDate ?>">
                      <?php
                      } ?>

                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Tanggal Berakhir</label>
                      <input type="text" value="<?php echo pretty_date($expired, 'd F Y', false) ?>" class="form-control" disabled>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <label class="control-label">Sisa Masa Aktif</label>
                      <input type="text" value="<?php echo $sisa ?>" class="form-control" disabled>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <input type="submit" value="Simpan" class="btn btn-success btn-block">
          </div>
        </div>
      </div>
      <br>
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-3">
            <label>Logo Sekolah</label>
            <a href="#" class="thumbnail">
              <?php if (isset($setting_logo) and $setting_logo['setting_value'] != NULL) { ?>
                <img src="<?php echo upload_url('school/' . $setting_logo['setting_value']) ?>" style="height: 50px">
              <?php } else { ?>
                <img src="<?php echo media_url('img/missing_logo.gif') ?>" id="target" alt="Choose image to upload">
              <?php } ?>
            </a>
            <input type='file' id="setting_logo" name="setting_logo">
            <p>Ukuran Logo 50x50 pixel</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <label>Kartu Depan</label>
            <a href="#" class="thumbnail">
              <?php if (isset($setting_kartu_depan) and $setting_kartu_depan['setting_value'] != NULL) { ?>
                <img src="<?php echo upload_url('kartu/' . $setting_kartu_depan['setting_value']) ?>" style="height: 203px; width: 321px;">
              <?php } else { ?>
                <img src="<?php echo media_url('img/missing_logo.gif') ?>" id="target_depan" alt="Choose image to upload">
              <?php } ?>
            </a>
            <input type='file' id="setting_kartu_depan" name="setting_kartu_depan">
            <p>Ukuran Gambar 321x203 pixel</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <label>Kartu Belakang</label>
            <a href="#" class="thumbnail">
              <?php if (isset($setting_kartu_belakang) and $setting_kartu_belakang['setting_value'] != NULL) { ?>
                <img src="<?php echo upload_url('kartu/' . $setting_kartu_belakang['setting_value']) ?>" style="height: 203px; width: 321px;">
              <?php } else { ?>
                <img src="<?php echo media_url('img/missing_logo.gif') ?>" id="target_belakang" alt="Choose image to upload">
              <?php } ?>
            </a>
            <input type='file' id="setting_kartu_belakang" name="setting_kartu_belakang">
            <p>Ukuran Gambar 321x203 pixel</p>
          </div>
        </div>
        <!--<button class="btn btn-info" type="button" data-toggle="collapse" data-target="#gateway"><i class="fa fa-arrow-down"></i> Set SMS Gateway</button>-->
        <!--<div id="gateway" class="collapse">
        <div class="col-md-12">
          <label class="control-label">User SMS Gateway</label>
          <input type="text" name="setting_user_sms" value="<?php //echo $setting_user_sms['setting_value'] 
                                                            ?>" class="form-control" >
        </div>
        <div class="col-md-12">
          <label class="control-label">Password SMS Gateway</label>
          <input type="password" name="setting_pass_sms" value="<?php //echo $setting_pass_sms['setting_value'] 
                                                                ?>" class="form-control" >
        </div>
        <div class="form-group">
          <label>Aktifkan SMS Gateway</label>
          <div class="radio">
            <label>
              <input type="radio" name="setting_sms" value="Y" <?php //echo ($setting_sms['setting_value'] == 'Y') ? 'checked' : ''; 
                                                                ?>> Ya
            </label>&nbsp;&nbsp;
            <label>
              <input type="radio" name="setting_sms" value="N" <?php //echo ($setting_sms['setting_value'] == 'N') ? 'checked' : ''; 
                                                                ?>> Tidak
            </label>
          </div>
        </div>
      </div>-->
      </div>
      <?php echo form_close() ?>
    </div>
  </section>
</div>

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

  $("#setting_logo").change(function() {
    readURL(this);
  });
</script>

<script type="text/javascript">
  function readDepan(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#target_depan').attr('src', e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#setting_kartu_depan").change(function() {
    readDepan(this);
  });
</script>

<script type="text/javascript">
  function readBelakang(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#target_belakang').attr('src', e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#setting_kartu_belakang").change(function() {
    readBelakang(this);
  });
</script>
<script>
  function changeAct(act) {
    var actValue = act;

    $.ajax({
      url: '<?php echo base_url(); ?>manage/setting/active/',
      type: 'POST',
      data: {
        'act_value': actValue,
      },
      success: function(msg) {
        window.location.href = '<?php echo base_url() ?>manage/setting/act_val?=' + act;
      },
      error: function(msg) {
        alert('msg');
      }

    });
  }
</script>