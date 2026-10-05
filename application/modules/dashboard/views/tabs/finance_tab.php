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
                            <?php endforeach;
                            endif; ?>
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
                        <button class="btn btn-default" id="btn_filter_realisasi">
                            <i class="fa fa-filter"></i>
                            <?= $this->lang->line('dash_filter') ?>
                        </button>
                        <button class="btn btn-success" id="btn_export_realisasi">
                            <i class="fa fa-file-excel-o"></i>
                            <?= $this->lang->line('dash_export_xls') ?>
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="table_realisasi_akun">
                                <thead>
                                    <tr class="bg-gray">
                                        <th><?= $this->lang->line('dash_unit') ?></th>
                                        <th><?= $this->lang->line('dash_payment_account') ?></th>
                                        <th><?= $this->lang->line('dash_bill') ?></th>
                                        <th><?= $this->lang->line('dash_paid') ?></th>
                                        <th><?= $this->lang->line('dash_unpaid') ?></th>
                                        <th><?= $this->lang->line('dash_achievement') ?></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
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
            <div class="box-body" style="min-height: 270px;">
                <div class="row" style="display: flex; align-items: center; margin: 0;">
                    <div class="col-xs-6" style="padding: 0; position: relative;">
                        <div style="position: relative; height: 160px; width: 100%;">
                            <canvas id="Chart_ikhtisarDoughnut"></canvas>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                                <span style="font-size: 10px; color: #2b2b2b; display: block; line-height: 1;">Total</span>
                                <strong id="ikhtisar_total_display" style="font-size: 13px; color: #333; line-height: 1.2; display: block; margin-top: 2px;"><?= !empty($ikhtisar_doughnut['total_display']) ? $ikhtisar_doughnut['total_display'] : '0' ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6" style="padding-left: 10px; padding-right: 0;">
                        <div style="margin-bottom: 12px;">
                            <div style="display:flex; align-items:center; margin-bottom: 2px;">
                                <span style="display:inline-block; width:10px; height:10px; background-color: #28a745; margin-right:6px; border-radius:2px; flex-shrink: 0;"></span>
                                <span style="font-size: 12px; font-weight: 600; color: #444;"><?= $this->lang->line('dash_income'); ?></span>
                            </div>
                            <div style="font-size: 11px; color: #555; padding-left: 16px;">
                                <span id="ikhtisar_pemasukan_text"><?= !empty($ikhtisar_doughnut['pemasukan']['text']) ? $ikhtisar_doughnut['pemasukan']['text'] : '0' ?></span> (<span id="ikhtisar_pemasukan_pct"><?= !empty($ikhtisar_doughnut['pemasukan']['pct']) ? $ikhtisar_doughnut['pemasukan']['pct'] : '0%' ?></span>)
                            </div>
                        </div>
                        <div style="margin-bottom: 12px;">
                            <div style="display:flex; align-items:center; margin-bottom: 2px;">
                                <span style="display:inline-block; width:10px; height:10px; background-color: #dc3545; margin-right:6px; border-radius:2px; flex-shrink: 0;"></span>
                                <span style="font-size: 12px; font-weight: 600; color: #444;"><?= $this->lang->line('dash_expense'); ?></span>
                            </div>
                            <div style="font-size: 11px; color: #555; padding-left: 16px;">
                                <span id="ikhtisar_pengeluaran_text"><?= !empty($ikhtisar_doughnut['pengeluaran']['text']) ? $ikhtisar_doughnut['pengeluaran']['text'] : '0' ?></span> (<span id="ikhtisar_pengeluaran_pct"><?= !empty($ikhtisar_doughnut['pengeluaran']['pct']) ? $ikhtisar_doughnut['pengeluaran']['pct'] : '0%' ?></span>)
                            </div>
                        </div>
                        <div>
                            <div style="display:flex; align-items:center; margin-bottom: 2px;">
                                <span style="display:inline-block; width:10px; height:10px; background-color: #007bff; margin-right:6px; border-radius:2px; flex-shrink: 0;"></span>
                                <span style="font-size: 12px; font-weight: 600; color: #444;"><?= $this->lang->line('dash_final_balance'); ?></span>
                            </div>
                            <div style="font-size: 11px; color: #555; padding-left: 16px;">
                                <span id="ikhtisar_saldo_text"><?= !empty($ikhtisar_doughnut['saldo_akhir']['text']) ? $ikhtisar_doughnut['saldo_akhir']['text'] : '0' ?></span> (<span id="ikhtisar_saldo_pct"><?= !empty($ikhtisar_doughnut['saldo_akhir']['pct']) ? $ikhtisar_doughnut['saldo_akhir']['pct'] : '0%' ?></span>)
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
                <h3 class="box-title"><?= $this->lang->line('dash_monthly_bills') ?: 'Tagihan Bulan Ini' ?></h3>
                <div class="box-tools pull-right">
                    <?php if (!empty($has_more_tagihan)): ?>
                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_tagihan_bulan_ini"><?= $this->lang->line('dash_view_all') ?: 'Lihat Semua' ?></button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="box-body" style="min-height: 270px;" id="container_tagihan_bulan_ini">
                <?php if (!empty($tagihan_bulan_ini)): foreach ($tagihan_bulan_ini as $tg): ?>
                        <div class="progress-group" style="margin-bottom: 8px;">
                            <span class="progress-text" style="font-size: 12px;"><?= html_escape($tg['name']) ?></span>
                            <span class="progress-number pull-right" style="font-size: 12px;"><b><?= $tg['text'] ?></b> (<?= $tg['progress'] ?>%)</span>
                            <div class="progress sm" style="margin-top: 3px; margin-bottom: 0; height: 8px;">
                                <div class="progress-bar <?= $tg['bar'] ?>" style="width: <?= $tg['progress'] ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach;
                else: ?>
                    <p class="text-muted text-center" style="margin-top: 50px;">Tidak ada tagihan bulan ini</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Kolom 3: Arus Kas 6 bulan Terakhir -->
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" id="title_arus_kas" data-title-template="<?= htmlspecialchars($this->lang->line('dash_cash_flow_title')) ?>">
                    <?= sprintf($this->lang->line('dash_cash_flow_title'), 6) ?>
                </h3>
                <div class="box-tools pull-right">
                    <select id="filter_arus_kas_bulan" class="form-control input-sm" style="width:auto; display:inline-block; font-size:12px; height:28px; padding:2px 8px;">
                        <option value="3"><?= sprintf($this->lang->line('dash_x_months'), 3) ?></option>
                        <option value="6" selected><?= sprintf($this->lang->line('dash_x_months'), 6) ?></option>
                        <option value="9"><?= sprintf($this->lang->line('dash_x_months'), 9) ?></option>
                        <option value="12"><?= sprintf($this->lang->line('dash_x_months'), 12) ?></option>
                    </select>
                </div>
            </div>
            <div class="box-body">
                <div style="position: relative; height: 250px; width: 100%;">
                    <canvas id="Chart_arusKas"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3 widget ketiga: 3 Kolom (Grafik Pembayaran SPP, Transaksi Terbaru, Tabungan Siswa) -->
<div class="row">
    <!-- Kolom 1: Grafik Pembayaran (SPP) -->
    <div class="<?= $spp_chart_col_class ?? 'col-lg-4 col-md-12 col-sm-12' ?>">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $spp_chart_title ?? 'Grafik Pembayaran (SPP)' ?></h3>
            </div>
            <div class="box-body" style="min-height: 270px;">
                <div style="position: relative; height: 250px; width: 100%;">
                    <canvas id="Chart_pembayaranSPP"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom 2: Transaksi Terbaru -->
    <div class="<?= $other_widget_col_class ?? 'col-lg-4 col-md-6 col-sm-12' ?>">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->lang->line('dash_recent_transactions') ?: 'Transaksi Terbaru' ?></h3>
                <div class="box-tools pull-right">
                    <a href="<?= site_url('manage/logs/trx_keuangan') ?>" class="btn btn-default btn-sm"><?= $this->lang->line('dash_view_all') ?: 'Lihat Semua' ?></a>
                </div>
            </div>
            <div class="box-body no-padding" style="min-height: 270px;">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-hover no-margin">
                        <thead>
                            <tr>
                                <th><?= $this->lang->line('dash_date') ?: 'Tanggal' ?></th>
                                <th><?= $this->lang->line('dash_description') ?: 'Deskripsi' ?></th>
                                <th><?= $this->lang->line('dash_category') ?: 'Kategori' ?></th>
                                <th><?= $this->lang->line('dash_nominal') ?: 'Nominal' ?></th>
                                <th><?= $this->lang->line('dash_status') ?: 'Status' ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($recent_transactions)):
                                foreach ($recent_transactions as $tr):
                            ?>
                                    <tr>
                                        <td style="font-size:12px; white-space:nowrap;"><?= $tr['tgl'] ?></td>
                                        <td style="font-size:12px;"><?= $tr['desc'] ?></td>
                                        <td style="font-size:12px;"><span style="font-size:12px;"><?= $tr['kategori'] ?></span></td>
                                        <td style="font-size:12px; white-space:nowrap;"><?= $tr['nominal'] ?></td>
                                        <td><span class="label <?= $tr['label'] ?> label-xs"><?= $tr['status'] ?></span></td>
                                    </tr>
                                <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td colspan="5" class="text-center" style="font-size:11px; padding: 15px;"><?= $this->lang->line('dash_no_transactions') ?: 'Tidak ada transaksi terbaru' ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom 3: Top 5 Tabungan Siswa -->
    <div class="<?= $other_widget_col_class ?? 'col-lg-4 col-md-6 col-sm-12' ?>">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->lang->line('dash_top_5_savings') ?: '5 Siswa Tabungan Terbesar' ?></h3>
            </div>
            <div class="box-body no-padding" style="min-height: 270px;">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-hover no-margin">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th style="text-align: left;"><?= $this->lang->line('dash_student_name') ?: 'Nama' ?></th>
                                <th class="text-center"><?= $this->lang->line('dash_class') ?: 'Kelas' ?></th>
                                <th class="text-center"><?= $this->lang->line('dash_unit') ?: 'Unit' ?></th>
                                <th class="text-right"><?= $this->lang->line('dash_nominal') ?: 'Saldo' ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($top_5_tabungan)): $no = 1;
                                foreach ($top_5_tabungan as $tb): ?>
                                    <tr class="row-tabungan-siswa" data-student-id="<?= $tb['student_id'] ?>" style="cursor: pointer;" title="Klik untuk lihat detail transaksi">
                                        <td class="text-center" style="font-size: 12px;"><?= $no++ ?></td>
                                        <td style="font-size:12px; font-weight: 600;"><?= html_escape($tb['student_full_name']) ?></td>
                                        <td class="text-center" style="font-size:12px;"><?= html_escape($tb['class_name'] ?: '-') ?></td>
                                        <td class="text-center" style="font-size:12px;"><?= html_escape($tb['majors_short_name'] ?: '-') ?></td>
                                        <td class="text-right" style="font-size:12px; font-weight: 600;">Rp <?= number_format($tb['total_saldo'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted"><?= $this->lang->line('dash_no_data'); ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lihat Semua Tagihan Bulan Ini -->
<div class="modal fade" id="modal_tagihan_bulan_ini" tabindex="-1" role="dialog" aria-labelledby="modalTagihanLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalTagihanLabel"><?= $this->lang->line('dash_modal_all_bills'); ?></h4>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <div id="modal_tagihan_loading" class="text-center" style="display: none; padding: 20px;">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                </div>
                <div id="modal_tagihan_content">
                    <!-- Loaded via AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Tabungan Siswa -->
<div class="modal fade" id="modal_detail_tabungan" tabindex="-1" role="dialog" aria-labelledby="modalDetailTabunganLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalDetailTabunganLabel"><?= $this->lang->line('dash_savings_details') ?: 'Detail Tabungan Siswa' ?></h4>
            </div>
            <div class="modal-body">
                <div id="modal_tabungan_loading" class="text-center" style="padding: 30px;">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                </div>
                <div id="modal_tabungan_info" style="display:none; margin-bottom: 15px; padding: 10px; background: #f9f9f9; border-left: 4px solid #3c8dbc; border-radius: 3px;">
                    <div class="row">
                        <div class="col-xs-6">
                            <small class="text-muted">NIS</small><br><strong id="info_nis">-</strong>
                        </div>
                        <div class="col-xs-6">
                            <small class="text-muted"><?= $this->lang->line('dash_student_name') ?: 'Nama Siswa' ?></small><br><strong id="info_nama">-</strong>
                        </div>
                        <div class="col-xs-6" style="margin-top:8px;">
                            <small class="text-muted"><?= $this->lang->line('dash_unit') ?: 'Unit' ?></small><br><strong id="info_unit">-</strong>
                        </div>
                        <div class="col-xs-6" style="margin-top:8px;">
                            <small class="text-muted"><?= $this->lang->line('dash_class') ?: 'Kelas' ?></small><br><strong id="info_kelas">-</strong>
                        </div>
                    </div>
                </div>
                <div id="modal_tabungan_table_wrap" style="display:none;">
                    <div class="table-responsive">
                        <table id="table_detail_tabungan" class="table table-bordered table-striped table-condensed" style="width:100%;">
                            <thead>
                                <tr>
                                    <th><?= $this->lang->line('dash_no') ?: 'No' ?></th>
                                    <th><?= $this->lang->line('dash_transaction_date') ?: 'Tanggal' ?></th>
                                    <th><?= $this->lang->line('dash_deposit') ?: 'Setoran' ?></th>
                                    <th><?= $this->lang->line('dash_withdrawal') ?: 'Penarikan' ?></th>
                                    <th><?= $this->lang->line('dash_note') ?: 'Keterangan' ?></th>
                                    <th><?= $this->lang->line('dash_balance') ?: 'Saldo' ?></th>
                                </tr>
                            </thead>
                            <tbody id="modal_tabungan_tbody"></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2"><?= $this->lang->line('dash_total') ?: 'Total' ?></th>
                                    <th id="tfoot_setoran">-</th>
                                    <th id="tfoot_penarikan">-</th>
                                    <th><?= $this->lang->line('dash_final_balance') ?: 'Saldo Akhir' ?></th>
                                    <th id="tfoot_saldo_akhir">-</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>