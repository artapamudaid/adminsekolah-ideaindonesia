<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!-- <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Data Barang</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('home') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Data Master</a></li>
            <li class="breadcrumb-item active">Barang</li>
          </ol>
        </div>
      </div>
    </div>
  </section> -->

  <div class="flash-data" data-flashdata="<?= $this->session->flashdata('sukses'); ?>"></div>
  <div class="flash-data-gagal" data-flashdatagagal="<?= $this->session->flashdata('gagal'); ?>"></div>

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="card box" style="padding: 10px;">
      <div class="card-header">
        <h3 class="card-title">Master Data Barang</h3>

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
        <h3 class="card-title">
          <a href="<?= base_url('manage/easet/barang/tambahBarang') ?>" class="btn btn-success bg-gradient-primary">
            Tambah Barang
          </a>
        </h3>
        <div class="table-responsive">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No.</th>
                <th>Kategori</th>
                <th>Nama</th>
                <th>Merek</th>
                <th>Tahun Perolehan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              foreach ($barang as $row): ?>
                <tr>
                  <td><?= $no++; ?></td>
                  <td><?= $row['nama_kategori']; ?></td>
                  <td><?= $row['nama_barang']; ?></td>
                  <td><?= $row['merek']; ?></td>
                  <td><?= $row['tahun_perolehan']; ?></td>
                  <td>
                    <a href="<?= base_url('manage/easet/barang/editBarang/' . $row['id_barang']) ?>" class="btn btn-info btn-sm">
                      <i class="fa fa-edit"></i>
                    </a>
                    <a href="<?= base_url('manage/easet/barang/hapusBarang/' . $row['id_barang']) ?>" class="btn btn-danger btn-sm tombol-hapus">
                      <i class="fa fa-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
            <tfoot>
              <tr>
                <th>No.</th>
                <th>Kategori</th>
                <th>Nama</th>
                <th>Merek</th>
                <th>Tahun Perolehan</th>
                <th>Aksi</th>
              </tr>
            </tfoot>
          </table>
        </div>
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
<!-- <script src="<?= base_url() ?>easet/src/backend/plugins/datatables/jquery.dataTables.js"></script> -->
<!-- <script src="<?= base_url() ?>easet/src/backend/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script> -->
<script>
  $(function() {
    $("#example1").DataTable({
      "language": {
        "zeroRecords": "Data masih kosong",
        "sSearch": "Cari"
      }
    });
  });
</script>