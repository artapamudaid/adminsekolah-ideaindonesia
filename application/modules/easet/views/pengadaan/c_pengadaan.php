<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!-- <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Pengadaan</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('home') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pengadaan</a></li>
            <li class="breadcrumb-item active">Pengajuan</li>
          </ol>
        </div>
      </div>
    </div>
  </section> -->

  <div class="flash-data" data-flashdata="<?= $this->session->flashdata('sukses'); ?>"></div>
  <div class="flash-data-gagal" data-flashdatagagal="<?= $this->session->flashdata('gagal'); ?>"></div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <div class="card card-primary box box-primary">
            <div class="card-header">
              <h3 class="card-title">Rekomendasi Pengadaan Aset</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <?php
              $no = 1;
              $arr = array();

              foreach ($nilai as $row) {
                $spek = ($row['nilai_spek'] / $maxspek['maks_spek']);
                $kual = ($row['nilai_kualitas'] / $maxkual['maks_kualitas']);
                $hrg = ($row['harga'] / $minharga['min_harga']);
                $nilai = round(($spek * 0.3) + ($kual * 0.3) + ($hrg * 0.4), 3);

                $arr[] = '<b>' . $row['nama_aset'] . '</b>';
              }

              $output = max($arr);

              echo "<p>Berdasarkan hasil perhitungan, maka pemilihan aset terbaik untuk pengadaan  adalah " . $output;
              ?>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
        <div class="col-md-6">
          <div class="card card-primary box box-primary">
            <div class="card-header">
              <h3 class="card-title">Rekomendasi Jumlah Pengadaan</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <select name="id_lokasi" class="id_lokasi form-control" required>
                <option value="">- Cari --</option>
                <?php foreach ($mt as $x): ?>
                  <option><?= $x['nama_barang']; ?> | Jumlah Kerusakan : <?= $x['jml_rusak']; ?> <?= $x['satuan']; ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
      </div>
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="card card-primary box box-primary">
            <div class="card-header">
              <h3 class="card-title">Form Pengadaan Aset</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <?php if ($this->session->userdata('role') == '1' || $this->session->userdata('role') == '2'): ?>
              <?php endif ?>
              <form class="form-horizontal" action="<?= base_url('manage/easet/pengadaan/simpanPengadaan') ?>" autocomplete="off" method="post">
                <div class="card-body">
                  <div class="form-group row">
                    <label for="id_lokasi" class="col-sm-2 col-form-label">Lokasi Penempatan</label>
                    <div class="col-sm-6">
                      <select name="id_lokasi" class="id_lokasi form-control" required>
                        <option value="">- Pilih --</option>
                        <?php foreach ($lokasi as $x): ?>
                          <option value="<?= $x['id_lokasi']; ?>"><?= $x['nama_lokasi']; ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="nama_aset" class="col-sm-2 col-form-label">Nama Aset</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="nama_aset" placeholder="Masukan Nama Aset.." required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="volume" class="col-sm-2 col-form-label">Volume</label>
                    <div class="col-sm-6">
                      <input type="number" class="form-control" name="volume" min="0" placeholder="Masukan Volume.." required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                    <div class="col-sm-6">
                      <select name="satuan" class="form-control" required>
                        <option value="">- Pilih --</option>
                        <option value="Buah">Buah</option>
                        <option value="Lembar">Lembar</option>
                        <option value="Unit">Unit</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="harga_satuan" class="col-sm-2 col-form-label">Harga Satuan</label>
                    <div class="col-sm-6">
                      <input type="number" class="form-control" name="harga_satuan" placeholder="Masukan Harga.." required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="tahun_pengadaan" class="col-sm-2 col-form-label">Tahun Pengadaan</label>
                    <div class="col-sm-6">
                      <div class="input-group mb-3">
                        <input type="text" name="tahun_pengadaan" placeholder="20XX" class="form-control" required>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <div class="col-2">

                  </div>
                  <div class="col-6">
                    <button type="submit" class="btn btn-info">Simpan</button>
                  </div>
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!--/.col (left) -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('.id_lokasi').select2({
      theme: "classic"
    });
  });
</script>