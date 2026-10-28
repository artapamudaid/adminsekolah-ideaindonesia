<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak QR Code Aset</title>

  <style>
    body {
      background: #fff;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 15px;
    }

    h2 {
      font-size: 16px;
      margin: 20px 0 10px 0;
    }

    /* Container grid */
    .qrcode-grid {
      display: table;
      width: 100%;
      border-collapse: separate;
      border-spacing: 10px;
      /* jarak antar QR code */
    }

    .qrcode-row {
      display: table-row;
    }

    .qrcode-item {
      display: table-cell;
      vertical-align: middle;
      text-align: center;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 6px;
      width: 130px;
      height: 160px;
    }

    .qrcode-item img {
      width: 110px;
      height: 110px;
      object-fit: contain;
    }

    .qrcode-item p {
      font-size: 10px;
      margin-top: 4px;
      color: #000;
      word-wrap: break-word;
    }

    @media print {
      .qrcode-item {
        border: 1px solid #000;
        page-break-inside: avoid;
      }

      @page {
        margin: 10mm;
      }
    }
  </style>
</head>

<body>

  <div class="qrcode-container">
    <?php $kode_aset = ''; ?>
    <?php foreach ($aset as $row): ?>
      <?php if ($row['kode_aset'] != $kode_aset): ?>
        <h2><?= $row['nama_barang']; ?></h2>
        <div class="qrcode-grid">
          <?php
          $kode_aset = $row['kode_aset'];
          $arrContextOptions = [
            "ssl" => ["verify_peer" => false, "verify_peer_name" => false],
          ];

          $path = base_url('easet/src/img/qrcode/' . $row['qr_code']);
          $type = pathinfo($path, PATHINFO_EXTENSION);
          $data = @file_get_contents($path, false, stream_context_create($arrContextOptions));
          $base64 = $data ? 'data:image/' . $type . ';base64,' . base64_encode($data) : '';
          ?>

          <?php
          $perBaris = 4; // jumlah kolom per baris
          for ($i = 0; $i < $row['volume']; $i++):
            if ($i % $perBaris == 0) echo '<div class="qrcode-row">';
          ?>
            <?php if ($base64): ?>
              <div class="qrcode-item">
                <img src="<?= $base64; ?>" alt="QR Code <?= $row['kode_aset']; ?>">
                <p><?= $row['kode_aset']; ?></p>
              </div>
            <?php else: ?>
              <div class="qrcode-item" style="border: none;"></div>
            <?php endif; ?>

          <?php
            if (($i + 1) % $perBaris == 0) echo '</div>';
          endfor;

          // jika jumlah volume tidak kelipatan kolom, tutup baris terakhir
          if ($row['volume'] % $perBaris != 0) echo '</div>';
          ?>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>

</body>

</html>