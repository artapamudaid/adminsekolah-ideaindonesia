<!-- Row 1: 3 Kolom (Ikhtisar Keuangan, Arus Kas X Bulan Terakhir, Tagihan Bulan Ini) -->
<div class="row">

    <!-- Kolom 1: Ikhtisar Keuangan -->
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Ikhtisar Keuangan</h3>
                <div class="box-tools pull-right">
                    <select id="filter_bulan_ikhtisar" class="form-control input-sm" style="width:auto; display:inline-block; font-size:11px; height:28px; padding:2px 8px;">
                        <option value="1">Januari 2026</option>
                        <option value="2">Februari 2026</option>
                        <option value="3">Maret 2026</option>
                        <option value="4">April 2026</option>
                        <option value="5" selected>Mei 2026</option>
                        <option value="6">Juni 2026</option>
                        <option value="7">Juli 2026</option>
                        <option value="8">Agustus 2026</option>
                        <option value="9">September 2026</option>
                        <option value="10">Oktober 2026</option>
                        <option value="11">November 2026</option>
                        <option value="12">Desember 2026</option>
                    </select>
                </div>
            </div>
            <div class="box-body" style="min-height: 200px;">
                <div class="row" style="display: flex; align-items: center; margin: 0;">
                    <div class="col-xs-6" style="padding: 0; position: relative;">
                        <div style="position: relative; height: 160px; width: 100%;">
                            <canvas id="Chart_ikhtisarDoughnut"></canvas>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                                <span style="font-size: 10px; color: #888; display: block; line-height: 1;">Total</span>
                                <strong style="font-size: 13px; color: #333; line-height: 1.2; display: block; margin-top: 2px;"><?= !empty($dummy_ikhtisar_doughnut['total_display']) ? $dummy_ikhtisar_doughnut['total_display'] : '245,75 JT' ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6" style="padding-left: 10px; padding-right: 0;">
                        <div style="margin-bottom: 12px;">
                            <div style="display:flex; align-items:center; margin-bottom: 2px;">
                                <span style="display:inline-block; width:10px; height:10px; background-color:#28a745; margin-right:6px; border-radius:2px; flex-shrink: 0;"></span>
                                <span style="font-size: 11px; font-weight: 600; color: #444;">Pemasukan</span>
                            </div>
                            <div style="font-size: 11px; color: #555; padding-left: 16px;">
                                <?= !empty($dummy_ikhtisar_doughnut['pemasukan']['text']) ? $dummy_ikhtisar_doughnut['pemasukan']['text'] : '312.450.000' ?> (<?= !empty($dummy_ikhtisar_doughnut['pemasukan']['pct']) ? $dummy_ikhtisar_doughnut['pemasukan']['pct'] : '56.2%' ?>)
                            </div>
                        </div>
                        <div style="margin-bottom: 12px;">
                            <div style="display:flex; align-items:center; margin-bottom: 2px;">
                                <span style="display:inline-block; width:10px; height:10px; background-color:#dc3545; margin-right:6px; border-radius:2px; flex-shrink: 0;"></span>
                                <span style="font-size: 11px; font-weight: 600; color: #444;">Pengeluaran</span>
                            </div>
                            <div style="font-size: 11px; color: #555; padding-left: 16px;">
                                <?= !empty($dummy_ikhtisar_doughnut['pengeluaran']['text']) ? $dummy_ikhtisar_doughnut['pengeluaran']['text'] : '198.230.000' ?> (<?= !empty($dummy_ikhtisar_doughnut['pengeluaran']['pct']) ? $dummy_ikhtisar_doughnut['pengeluaran']['pct'] : '35.6%' ?>)
                            </div>
                        </div>
                        <div>
                            <div style="display:flex; align-items:center; margin-bottom: 2px;">
                                <span style="display:inline-block; width:10px; height:10px; background-color:#007bff; margin-right:6px; border-radius:2px; flex-shrink: 0;"></span>
                                <span style="font-size: 11px; font-weight: 600; color: #444;">Saldo Akhir</span>
                            </div>
                            <div style="font-size: 11px; color: #555; padding-left: 16px;">
                                <?= !empty($dummy_ikhtisar_doughnut['saldo_akhir']['text']) ? $dummy_ikhtisar_doughnut['saldo_akhir']['text'] : '245.750.000' ?> (<?= !empty($dummy_ikhtisar_doughnut['saldo_akhir']['pct']) ? $dummy_ikhtisar_doughnut['saldo_akhir']['pct'] : '8.2%' ?>)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom 2: Arus Kas 6 bulan Terakhir -->
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" id="title_arus_kas">Arus Kas 6 Bulan Terakhir</h3>
                <div class="box-tools pull-right">
                    <select id="filter_arus_kas_bulan" class="form-control input-sm" style="width:auto; display:inline-block; font-size:11px; height:28px; padding:2px 8px;">
                        <option value="3">3 Bulan</option>
                        <option value="6" selected>6 Bulan</option>
                        <option value="9">9 Bulan</option>
                        <option value="12">12 Bulan</option>
                    </select>
                </div>
            </div>
            <div class="box-body" style="min-height: 200px;">
                <div style="position: relative; height: 160px; width: 100%;">
                    <canvas id="Chart_arusKas"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom 3: Tagihan Bulan Ini -->
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Tagihan Bulan Ini</h3>
            </div>
            <div class="box-body" style="min-height: 200px;">
                <?php if (!empty($dummy_tagihan_bulan)): foreach ($dummy_tagihan_bulan as $tg): ?>
                        <div class="progress-group" style="margin-bottom: 8px;">
                            <span class="progress-text" style="font-size: 11px;"><?= $tg['name'] ?></span>
                            <span class="progress-number pull-right" style="font-size: 11px;"><b><?= $tg['text'] ?></b> (<?= $tg['progress'] ?>%)</span>
                            <div class="progress sm" style="margin-top: 3px; margin-bottom: 0; height: 8px;">
                                <div class="progress-bar <?= $tg['bar'] ?>" style="width: <?= $tg['progress'] ?>%"></div>
                            </div>
                        </div>
                <?php endforeach;
                endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- Row 2: 3 Kolom (Grafik Pembayaran SPP, Transaksi Terbaru, Tabungan Siswa) -->
<div class="row">

    <!-- Kolom 1: Grafik Pembayaran (SPP) -->
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Grafik Pembayaran (SPP)</h3>
            </div>
            <div class="box-body" style="min-height: 200px;">
                <div style="position: relative; height: 160px; width: 100%;">
                    <canvas id="Chart_pembayaranSPP"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom 2: Transaksi Terbaru -->
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Transaksi Terbaru</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-default btn-sm">Lihat Semua</button>
                </div>
            </div>
            <div class="box-body no-padding" style="min-height: 200px;">
                <div class="table-responsive">
                    <table class="table table-condensed no-margin">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($dummy_transaksi)): foreach ($dummy_transaksi as $tr): ?>
                                    <tr>
                                        <td style="font-size:11px;"><?= $tr['tgl'] ?></td>
                                        <td style="font-size:11px;"><?= $tr['desc'] ?></td>
                                        <td style="font-size:11px;"><?= $tr['nominal'] ?></td>
                                        <td><span class="label <?= $tr['label'] ?>" style="font-size:10px;"><?= $tr['status'] ?></span></td>
                                    </tr>
                            <?php endforeach;
                            endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom 3: Top 5 Tabungan Siswa -->
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">5 Siswa Tabungan Terbesar</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-default btn-sm">Detail</button>
                </div>
            </div>
            <div class="box-body no-padding" style="min-height: 200px;">
                <table class="table table-condensed table-striped no-margin">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dummy_tabungan)): foreach ($dummy_tabungan as $tb): ?>
                                <tr>
                                    <td><?= $tb['no'] ?></td>
                                    <td><?= $tb['nama'] ?></td>
                                    <td><?= $tb['kelas'] ?></td>
                                    <td><?= $tb['nominal'] ?></td>
                                </tr>
                        <?php endforeach;
                        endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>