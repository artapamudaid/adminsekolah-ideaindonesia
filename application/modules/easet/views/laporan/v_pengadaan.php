<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!-- <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Laporan</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('home') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Laporan</a></li>
            <li class="breadcrumb-item active">Pengadaan</li>
          </ol>
        </div>
      </div>
    </div>
  </section> -->

  <div class="flash-data-gagal" data-flashdatagagal="<?= $this->session->flashdata('gagal'); ?>"></div>

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="card box" style="padding: 10px;">
      <div class="card-header">
        <h3 class="card-title">
          Cari Pengadaan Aset
        </h3>

        <!-- <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i>
            </button>
          </div> -->
      </div>
      <div class="card-body">
        <form action="<?= base_url('manage/easet/laporan/searchPengadaan') ?>" method="post" autocomplete="off">
          <div class="row">
            <div class="col-md-3">
              <select name="id_lokasi" class="form-control" required>
                <option value="">- Lokasi Aset --</option>
                <?php foreach ($lokasi as $row): ?>
                  <option value="<?= $row['id_lokasi']; ?>"><?= $row['nama_lokasi']; ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control" name="tahun_pengadaan" placeholder="-- Tahun Pengadaan --">
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-info btn-outline-primary">Cari</button>
              <button type="reset" class="btn btn-warning btn-outline-danger">Reset</button>
            </div>
          </div>
        </form>
        <br>
        <button type="button" class="btn btn-danger mt-4" disabled>
          <i class="fa fa-print" aria-hidden="true"></i> Print
        </button>
        <button type="button" class="btn btn-success mt-4" disabled>
          <i class="fa fa-file" aria-hidden="true"></i> Export Excel
        </button>
        <br>
        <br>
        <table class="table table-bordered mt-4">
          <thead>
            <tr>
              <th>No.</th>
              <th>Nama</th>
              <th>Satuan</th>
              <th>Volume</th>
              <th>Harga</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="5" align="center">Data tidak tersedia.. silahkan cari data</td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">

      </div>
      <!-- /.card-footer-->
    </div>
    <!-- /.card -->

  </section>
  <!-- /.content -->
</div>