<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Aset - <?= $instansi; ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body {
      background: #f4f6f9;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .content-wrapper {
      margin: 30px auto;
      max-width: 700px;
    }

    .card {
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }

    .card-body {
      padding: 25px;
    }

    .table td {
      padding: 6px 10px;
      vertical-align: middle;
    }

    .table td:first-child {
      font-weight: 600;
      color: #333;
    }

    img.qr {
      border: 4px solid #ddd;
      border-radius: 8px;
      padding: 5px;
    }

    .asset-title {
      text-align: center;
      font-size: 22px;
      font-weight: 700;
      color: #007bff;
      margin-top: 15px;
      margin-bottom: 20px;
    }

    .footer-note {
      text-align: center;
      font-size: 13px;
      color: #888;
      margin-top: 25px;
    }
  </style>
</head>

<body>

  <div class="content-wrapper">
    <section class="content">

      <div class="card">
        <div class="card-header bg-primary text-white text-center">
          <strong>Detail Aset <?= $instansi; ?></strong>
        </div>

        <div class="card-body">
          <?php foreach ($aset as $d) { ?>
            <?php if ($d['qr_code'] != NULL): ?>
              <center>
                <img src="<?= base_url() ?>easet/src/img/qrcode/<?= $d['qr_code']; ?>" alt="QR Code" class="qr" style="height:150px;width:150px;">
              </center>
              <div class="asset-title"><?= $d['nama_barang']; ?></div>
            <?php endif ?>
          <?php } ?>

          <table class="table table-striped table-bordered">
            <tbody>
              <?php foreach ($aset as $d) { ?>
                <tr>
                  <td>Kode Aset</td>
                  <td><?= $d['kode_aset'] ?></td>
                </tr>
                <tr>
                  <td>Nama Aset</td>
                  <td><?= $d['nama_barang'] ?></td>
                </tr>
                <tr>
                  <td>Kategori Aset</td>
                  <td><?= $d['kode_kategori'] ?> - <?= $d['nama_kategori'] ?></td>
                </tr>
                <tr>
                  <td>Merek</td>
                  <td><?= $d['merek'] ?></td>
                </tr>
                <tr>
                  <td>Kondisi</td>
                  <td><?= $d['kondisi'] ?></td>
                </tr>
                <tr>
                  <td>Tahun Perolehan</td>
                  <td><?= $d['tahun_perolehan'] ?></td>
                </tr>
                <tr>
                  <td>Lokasi Aset</td>
                  <td><?= $d['nama_lokasi'] ?></td>
                </tr>
                <tr>
                  <td>Satuan</td>
                  <td><?= $d['satuan']; ?></td>
                </tr>
                <tr>
                  <td>Volume</td>
                  <td><?= $d['volume'] ?></td>
                </tr>
                <tr>
                  <td>Nilai Aset</td>
                  <td><?= rupiah($d['harga']); ?></td>
                </tr>
                <tr>
                  <td>Total Nilai Aset</td>
                  <td><?= rupiah($d['total_harga']); ?></td>
                </tr>
                <tr>
                  <td>Status Aset</td>
                  <td><?= $d['status_aset']; ?></td>
                </tr>
                <tr>
                  <td>Umur Ekonomis</td>
                  <td><?= $d['umur_ekonomis']; ?> Tahun</td>
                </tr>
                <tr>
                  <td>Masa Pemakaian</td>
                  <td>
                    <?php
                    $usia = date('Y') - $d['tahun_perolehan'];
                    if ($usia < 0) {
                      echo "Aset sudah melewati umur ekonomis";
                    } else {
                      echo $usia, " Tahun";
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Sumber Bantuan</td>
                  <td><?= $d['jenis_bantuan']; ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="footer-note">
        Copyright &copy; <?= '2018-' . date('Y') ?> <a target="_blank" href="https://indoweb.id">indoweb.id</a>. All rights reserved.
      </div>

    </section>
  </div>

</body>

</html>