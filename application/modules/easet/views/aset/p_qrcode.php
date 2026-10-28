<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Cetak QR Code Aset</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: 10pt;
      background: #fff;
      margin: 10px;
    }

    .qrcode-wrapper {
      width: 100%;
      text-align: center;
    }

    /* Gunakan display:inline-block agar bisa membentuk grid */
    .qrcode-item {
      display: inline-block;
      width: 18%;
      /* 5 kolom per baris, sesuaikan jika ingin 4 kolom -> 23% */
      margin: 0.8%;
      border: 1px solid #999;
      border-radius: 5px;
      padding: 8px;
      text-align: center;
      vertical-align: top;
      height: 160px;
      box-sizing: border-box;
    }

    .qrcode-item img {
      width: 110px;
      height: 110px;
      object-fit: contain;
      margin-bottom: 5px;
    }

    .qrcode-item p {
      font-size: 9pt;
      margin: 0;
      word-wrap: break-word;
    }

    @page {
      margin: 10mm;
    }

    @media print {
      .qrcode-item {
        page-break-inside: avoid;
      }
    }
  </style>
</head>

<body>

  <div class="qrcode-wrapper">
    <?php
    $arrContextOptions = [
      "ssl" => ["verify_peer" => false, "verify_peer_name" => false],
    ];

    $path = base_url('easet/src/img/qrcode/' . $qr_code);
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path, false, stream_context_create($arrContextOptions));
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    ?>

    <?php for ($i = 0; $i < $jumlah_aset; $i++) { ?>
      <div class="qrcode-item">
        <img src="<?= $base64 ?>" alt="QR Code <?= $kode_aset; ?>">
        <p><?= $kode_aset; ?></p>
      </div>
    <?php } ?>
  </div>

</body>

</html>