<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php echo isset($title) ? '' . $title : null; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
            <li><a href="<?php echo $redirect_url ?>">Akun Biaya</a></li>
            <li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- Unit Selection Card -->
                        <div class="box box-solid" style="border: 1px solid #cbd5e1; border-radius: 6px; margin-bottom: 20px; background: #ffffff;">
                            <div class="box-body" style="padding: 15px 20px;">
                                <?php
                                $currentUnitName = '';
                                if (!empty($s)) {
                                    foreach ($majors as $m_item) {
                                        if ($m_item['majors_id'] == $s) {
                                            $currentUnitName = $m_item['majors_short_name'];
                                            break;
                                        }
                                    }
                                }
                                ?>
                                <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
                                    <div class="col-sm-3 col-xs-12">
                                        <label style="margin: 0; font-size: 14px; color: #1e293b;">
                                            <i class="fa fa-university text-primary"></i> Unit Sekolah / Pesantren:
                                        </label>
                                    </div>
                                    <div class="col-sm-5 col-xs-12">
                                        <select id="select-unit-import" class="form-control" style="font-weight: 600; border-radius: 4px;" onchange="if(this.value){ window.location.href='<?= site_url('manage/account/import_account?m=') ?>' + this.value; } else { window.location.href='<?= site_url('manage/account/import_account') ?>'; }">
                                            <option value="">-- Pilih Unit Sekolah Terlebih Dahulu --</option>
                                            <?php foreach ($majors as $row): ?>
                                                <option value="<?= $row['majors_id'] ?>" <?= ($s == $row['majors_id']) ? 'selected' : '' ?>>
                                                    <?= $row['majors_short_name'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4 col-xs-12">
                                        <?php if (!empty($currentUnitName)): ?>
                                            <span class="label label-success" style="font-size: 13px; padding: 6px 12px; display: inline-block;">
                                                <i class="fa fa-check-circle"></i> Unit Aktif: <?= htmlspecialchars($currentUnitName) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-danger" style="font-weight: 600; font-size: 13px;">
                                                <i class="fa fa-exclamation-triangle"></i> Wajib pilih unit sebelum import
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="callout callout-info" style="border-radius: 6px; background-color: #f0fdf4 !important; border-left: 5px solid #22c55e !important; color: #1e293b !important;">
                            <h4 style="color: #15803d; font-weight: bold;"><i class="fa fa-info-circle"></i> Petunjuk Pengisian Template Import</h4>
                            <p>Import data Akun Biaya kini jauh lebih mudah dan praktis menggunakan format <strong>3 Kolom Sederhana</strong>:</p>
                            
                            <table class="table table-bordered" style="background: white; max-width: 600px; margin-top: 10px; margin-bottom: 12px; font-size: 13px;">
                                <thead>
                                    <tr style="background-color: #2563eb; color: white;">
                                        <th style="width: 30%;">KODE_INDUK</th>
                                        <th style="width: 45%;">NAMA_AKUN</th>
                                        <th style="width: 25%;">KATEGORI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>4-40100</code></td>
                                        <td>Uang Gedung Siswa / SPP</td>
                                        <td><span class="label label-primary">Pembayaran</span></td>
                                    </tr>
                                    <tr>
                                        <td><code>5-50100</code></td>
                                        <td>Biaya Listrik & Air</td>
                                        <td><span class="label label-warning">Keuangan</span></td>
                                    </tr>
                                </tbody>
                            </table>

                            <p><strong>Langkah Mudah Mengisi:</strong></p>
                            <ol style="padding-left: 20px; line-height: 1.8;">
                                <li>Pilih Unit Sekolah pada pilihan di atas, kemudian unduh template Excel resmi melalui tombol hijau di bawah.</li>
                                <li>Buka file Excel, lalu lihat sheet <strong>DAFTAR_KODE_INDUK</strong> untuk melihat daftar kode induk yang tersedia di Unit Sekolah Anda.</li>
                                <li>Salin (copy) kode induk yang diinginkan, lalu tempel (paste) ke kolom <strong>KODE_INDUK</strong> pada sheet <strong>DATA_AKUN</strong>.</li>
                                <li>Tuliskan nama akun pada kolom <strong>NAMA_AKUN</strong> dan pilih <strong>Keuangan</strong> atau <strong>Pembayaran</strong> pada kolom <strong>KATEGORI</strong>.</li>
                                <li><span class="label label-success" style="font-size: 11px;">Fitur Baru</span> <strong>Multi-Level Sekaligus:</strong> Anda dapat langsung menginput banyak anak akun maupun akun bertingkat dalam satu file tanpa perlu import bertahap. Sistem akan meng-generate nomor kode akun secara otomatis.</li>
                            </ol>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <?php if (!empty($s)): ?>
                                <a href="<?= site_url('manage/account/download?m=' . $s) ?>" class="btn btn-success btn-lg" style="font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    <i class="fa fa-download"></i> Unduh Template Excel (.xlsx) - <?= htmlspecialchars($currentUnitName) ?>
                                </a>
                            <?php else: ?>
                                <button type="button" class="btn btn-default btn-lg disabled" title="Pilih Unit terlebih dahulu" style="font-weight: 600;">
                                    <i class="fa fa-download"></i> Unduh Template Excel (Pilih Unit Dulu)
                                </button>
                            <?php endif; ?>
                        </div>
                        <hr>
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <div class="box box-primary" style="box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-upload"></i> Unggah File Excel</h3>
                            </div>
                            <div class="box-body">
                                <?= form_open_multipart('manage/account/do_import?m=' . $s) ?>
                                <div class="form-group">
                                    <label for="file">Pilih File Hasil Pengisian (.xlsx)</label>
                                    <input type="file" name="file" accept=".xlsx" required class="form-control" style="height: auto; padding: 10px;">
                                    <p class="help-block text-muted">Format file harus .xlsx (Microsoft Excel)</p>
                                </div>
                                <div class="form-group" style="margin-top: 20px;">
                                    <?php if (!empty($s)): ?>
                                        <button type="submit" class="btn btn-success btn-flat" style="padding: 8px 20px; font-weight: bold;"><i class="fa fa-cloud-upload"></i> Mulai Import Data</button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-default btn-flat disabled" title="Pilih Unit Sekolah terlebih dahulu" style="padding: 8px 20px; font-weight: bold;"><i class="fa fa-cloud-upload"></i> Mulai Import Data (Pilih Unit Dulu)</button>
                                    <?php endif; ?>
                                    <a href="<?php echo site_url('manage/account/' . (!empty($s) ? '?m=' . $s : '')) ?>" class="btn btn-default btn-flat" style="padding: 8px 15px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                                </div>
                                <?= form_close() ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>