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
            <li class="breadcrumb-item active">Penghapusan</li>
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
          Cari Data Aset yang dihapuskan
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
        <form action="<?= base_url('manage/easet/laporan/searchPenghapusan') ?>" method="post">
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
              <select name="tahun_perolehan" class="form-control" required>
                <option value="">- Tahun Perolehan --</option>
                <?php
                for ($i = 2010; $i <= date('Y'); $i++) {
                  echo "<option value='$i'>$i</option>";
                }
                ?>
              </select>
            </div>
            <div class="col">
              <button type="submit" class="btn btn-info btn-outline-primary">Cari</button>
              <button type="reset" class="btn btn-warning btn-outline-danger">Reset</button>
            </div>

          </div>
        </form>
        <br>
        <a href="<?= base_url('manage/easet/laporan/printPenghapusan/') . $this->input->post('id_lokasi') . '/' . $this->input->post('tahun_perolehan') ?>" target="_blank" class="btn btn-danger mt-4">
          <i class="fa fa-print" aria-hidden="true"></i> Print
        </a>
        <a href="<?= base_url('manage/easet/laporan/exportPenghapusan/') . $this->input->post('id_lokasi') . '/' . $this->input->post('tahun_perolehan') ?>" class="btn btn-success mt-4">
          <i class="fa fa-file" aria-hidden="true"></i> Export Excel
        </a>
        <div class="mt-4">
          <div class="col">
            <b>Lokasi Aset :</b> <?= $lok['nama_lokasi'] ?>
          </div>
        </div>
        <br>
        <table class="table table-bordered mt-4">
          <thead>
            <tr>
              <th>NO.</th>
              <th>NAMA</th>
              <th>VOLUME</th>
              <th>SATUAN</th>
              <th>HARGA (Rp.)</th>
              <th>JUMLAH (Rp.)</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $sum = 0;
            foreach ($aset as $row):
              $sum += ($row['harga'] * $row['jumlah']);
            ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['nama_barang'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td><?= $row['satuan'] ?></td>
                <td><?= laporan($row['harga']) ?></td>
                <td><?= laporan($row['harga'] * $row['jumlah']) ?></td>
              </tr>
            <?php endforeach ?>
            <tr>
              <td colspan="5"><b>JUMLAH TOTAL</b></td>
              <td><?= laporan($sum); ?></td>
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