<!-- 1 widget pertama: 1 Kolom (Realisasi Akun Pembayaran) -->
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->lang->line('dash_payment_realization') ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-4 form-group">
                        <select class="form-control s2" id="filter_period_realisasi" style="width: 100%;">
                            <?php if (!empty($periods_list)): foreach ($periods_list as $per): ?>
                                <option value="<?= $per['period_id'] ?>" <?= ($per['period_status'] == 1) ? 'selected' : '' ?>>
                                    T.A. <?= $per['period_start'] ?>/<?= $per['period_end'] ?>
                                </option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4 form-group">
                        <select class="form-control s2" id="start_month" style="width: 100%;">
                            <?php foreach ($months_list as $m): ?>
                                <option value="<?= $m['month_id'] ?>"><?= $m['month_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4 form-group">
                        <select class="form-control s2" id="end_month" style="width: 100%;">
                            <?php foreach ($months_list as $m): ?>
                                <option value="<?= $m['month_id'] ?>" <?= ($m['month_id'] == 12) ? 'selected' : '' ?>><?= $m['month_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 form-group" style="display: flex; flex-direction: row;justify-content: flex-end;align-items: center;gap: 5px;">
                        <button class="btn btn-primary" id="btn_filter_realisasi"><?= $this->lang->line('dash_filter') ?></button>
                        <button class="btn btn-success" id="btn_export_realisasi"><i class="fa fa-file-excel-o"></i> <?= $this->lang->line('dash_export_xls') ?></button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="table_realisasi_akun">
                                <thead>
                                    <tr>
                                        <th><?= $this->lang->line('dash_unit') ?></th>
                                        <th><?= $this->lang->line('dash_payment_account') ?></th>
                                        <th><?= $this->lang->line('dash_bill') ?></th>
                                        <th><?= $this->lang->line('dash_paid') ?></th>
                                        <th><?= $this->lang->line('dash_unpaid') ?></th>
                                        <th><?= $this->lang->line('dash_achievement') ?></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr style="background-color: #f9f9f9; font-weight: bold;">
                                        <th colspan="2" class="text-right">Total</th>
                                        <th class="text-right"></th>
                                        <th class="text-right"></th>
                                        <th class="text-right"></th>
                                        <th class="text-center"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3 widget kedua: 3 Kolom (Ikhtisar Keuangan, Tagihan Bulan Ini, Arus Kas X Bulan Terakhir) -->
<div class="row">

    <!-- Kolom 1: Ikhtisar Keuangan -->
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="box box-primary" id="box_ikhtisar_keuangan">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->lang->line('dash_financial_overview') ?></h3>
                <div class="box-tools pull-right">
                    <?php
                    $nama_bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    $period_start = !empty($ikhtisar_period['period_start']) ? (int)$ikhtisar_period['period_start'] : (int)date('Y');
                    $period_end   = !empty($ikhtisar_period['period_end'])   ? (int)$ikhtisar_period['period_end']   : (int)date('Y') + 1;
                    $bulan_opsi = [];
                    for ($m = 7; $m <= 12; $m++) {
                        $bulan_opsi[] = ['m' => $m, 'y' => $period_start];
                    }
                    for ($m = 1; $m <= 6; $m++) {
                        $bulan_opsi[] = ['m' => $m, 'y' => $period_end];
                    }
                    $active_m = !empty($ikhtisar_month) ? (int)$ikhtisar_month : (int)date('n');
                    $active_y = !empty($ikhtisar_year)  ? (int)$ikhtisar_year  : (int)date('Y');
                    ?>
                    <select id="filter_bulan_ikhtisar" class="form-control input-sm" style="width:auto; display:inline-block; font-size:11px; height:28px; padding:2px 8px;">
                        <?php foreach ($bulan_opsi as $bo): ?>
                            <option value="<?= $bo['m'] ?>" data-year="<?= $bo['y'] ?>" <?= ($bo['m'] == $active_m && $bo['y'] == $active_y) ? 'selected' : '' ?>>
                                <?= $nama_bulan[$bo['m']] . ' ' . $bo['y'] ?>
                            </option>
                        <?php endforeach; ?>
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
                                <strong id="ikhtisar_total_display" style="font-size: 13px; color: #333; line-height: 1.2; display: block; margin-top: 2px;"><?= !empty($dummy_ikhtisar_doughnut['total_display']) ? $dummy_ikhtisar_doughnut['total_display'] : '0' ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6" style="padding-left: 10px; padding-right: 0;">
                        <div style="margin-bottom: 12px;">
                            <div style="display:flex; align-items:center; margin-bottom: 2px;">
                                <span style="display:inline-block; width:10px; height:10px; background-color:#28a745; margin-right:6px; border-radius:2px; flex-shrink: 0;"></span>
                                <span style="font-size: 11px; font-weight: 600; color: #444;"><?= $this->lang->line('dash_income'); ?></span>
                            </div>
                            <div style="font-size: 11px; color: #555; padding-left: 16px;">
                                <span id="ikhtisar_pemasukan_text"><?= !empty($dummy_ikhtisar_doughnut['pemasukan']['text']) ? $dummy_ikhtisar_doughnut['pemasukan']['text'] : '0' ?></span> (<span id="ikhtisar_pemasukan_pct"><?= !empty($dummy_ikhtisar_doughnut['pemasukan']['pct']) ? $dummy_ikhtisar_doughnut['pemasukan']['pct'] : '0%' ?></span>)
                            </div>
                        </div>
                        <div style="margin-bottom: 12px;">
                            <div style="display:flex; align-items:center; margin-bottom: 2px;">
                                <span style="display:inline-block; width:10px; height:10px; background-color:#dc3545; margin-right:6px; border-radius:2px; flex-shrink: 0;"></span>
                                <span style="font-size: 11px; font-weight: 600; color: #444;"><?= $this->lang->line('dash_expense'); ?></span>
                            </div>
                            <div style="font-size: 11px; color: #555; padding-left: 16px;">
                                <span id="ikhtisar_pengeluaran_text"><?= !empty($dummy_ikhtisar_doughnut['pengeluaran']['text']) ? $dummy_ikhtisar_doughnut['pengeluaran']['text'] : '0' ?></span> (<span id="ikhtisar_pengeluaran_pct"><?= !empty($dummy_ikhtisar_doughnut['pengeluaran']['pct']) ? $dummy_ikhtisar_doughnut['pengeluaran']['pct'] : '0%' ?></span>)
                            </div>
                        </div>
                        <div>
                            <div style="display:flex; align-items:center; margin-bottom: 2px;">
                                <span style="display:inline-block; width:10px; height:10px; background-color:#007bff; margin-right:6px; border-radius:2px; flex-shrink: 0;"></span>
                                <span style="font-size: 11px; font-weight: 600; color: #444;"><?= $this->lang->line('dash_ending_balance'); ?></span>
                            </div>
                            <div style="font-size: 11px; color: #555; padding-left: 16px;">
                                <span id="ikhtisar_saldo_text"><?= !empty($dummy_ikhtisar_doughnut['saldo_akhir']['text']) ? $dummy_ikhtisar_doughnut['saldo_akhir']['text'] : '0' ?></span> (<span id="ikhtisar_saldo_pct"><?= !empty($dummy_ikhtisar_doughnut['saldo_akhir']['pct']) ? $dummy_ikhtisar_doughnut['saldo_akhir']['pct'] : '0%' ?></span>)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom 2: Tagihan Bulan Ini -->
    <div class="col-lg-4 col-md-6 col-sm-12">
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

    <!-- Kolom 3: Arus Kas 6 bulan Terakhir -->
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" id="title_arus_kas" data-title-template="<?= htmlspecialchars($this->lang->line('dash_cash_flow_title')) ?>"><?= sprintf($this->lang->line('dash_cash_flow_title'), 6) ?></h3>
                <div class="box-tools pull-right">
                    <select id="filter_arus_kas_bulan" class="form-control input-sm" style="width:auto; display:inline-block; font-size:11px; height:28px; padding:2px 8px;">
                        <option value="3"><?= sprintf($this->lang->line('dash_x_months'), 3) ?></option>
                        <option value="6" selected><?= sprintf($this->lang->line('dash_x_months'), 6) ?></option>
                        <option value="9"><?= sprintf($this->lang->line('dash_x_months'), 9) ?></option>
                        <option value="12"><?= sprintf($this->lang->line('dash_x_months'), 12) ?></option>
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
</div>

<!-- 3 widget ketiga: 3 Kolom (Grafik Pembayaran SPP, Transaksi Terbaru, Tabungan Siswa) -->
<div class="row">
    <!-- Kolom 1: Grafik Pembayaran (SPP) -->
    <div class="col-lg-4 col-md-12 col-sm-12">
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
    <div class="col-lg-4 col-md-6 col-sm-12">
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
    <div class="col-lg-4 col-md-6 col-sm-12">
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