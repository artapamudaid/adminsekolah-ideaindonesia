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
                            <div id="ikhtisar_doughnut_tooltip"
                                style="display: none; position: absolute; top: 0; left: 50%; transform: translateX(-50%); z-index: 20; background: rgba(33, 37, 41, 0.95); color: #fff; padding: 6px 10px; border-radius: 4px; font-size: 11px; white-space: nowrap; pointer-events: none; ">
                            </div>
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
            <div class="overlay" id="overlay_ikhtisar">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>

    <!-- Kolom 2: Tagihan Bulan Ini -->
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->lang->line('dash_monthly_bills') ?: 'Tagihan Bulan Ini' ?></h3>
                <div class="box-tools pull-right">
                    <div class="box-tools pull-right" id="tools_tagihan_bulan_ini">
                        <!-- Loaded via JS -->
                    </div>
                </div>
            </div>
            <div class="box-body" style="min-height: 270px;" id="container_tagihan_bulan_ini">
                <!-- Loaded via JS -->
            </div>
            <div class="overlay" id="overlay_tagihan">
                <i class="fa fa-refresh fa-spin"></i>
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
            <div class="overlay" id="overlay_arus_kas">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>
</div>

<!-- 3 widget ketiga: 3 Kolom (Grafik Pembayaran SPP, Transaksi Terbaru, Tabungan Siswa) -->
<div class="row">
    <!-- Kolom 1: Grafik Pembayaran (SPP) -->
    <div class="col-lg-4 col-md-12 col-sm-12" id="box_spp_chart_wrapper">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" id="spp_chart_title_el">Grafik Pembayaran (SPP)</h3>
            </div>
            <div class="box-body" style="min-height: 270px;">
                <div style="position: relative; height: 250px; width: 100%;">
                    <canvas id="Chart_pembayaranSPP"></canvas>
                </div>
            </div>
            <div class="overlay" id="overlay_spp">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>

    <!-- Kolom 2: Transaksi Terbaru -->
    <div class="col-lg-4 col-md-6 col-sm-12" id="box_recent_tr_wrapper">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->lang->line('dash_recent_transactions') ?: 'Transaksi Terbaru' ?></h3>
                <div class="box-tools pull-right">
                    <a href="<?= site_url('manage/logs/trx_keuangan') ?>" class="btn btn-default btn-sm"><?= $this->lang->line('dash_view_all') ?: 'Lihat Semua' ?></a>
                </div>
            </div>
            <div class="box-body no-padding" style="min-height: 270px; max-height: 270px; overflow-y: auto;">
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
                        <tbody id="tbody_recent_transactions">
                            <!-- Loaded via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="overlay" id="overlay_recent_tr">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>

    <!-- Kolom 3: Top 5 Tabungan Siswa -->
    <div class="col-lg-4 col-md-6 col-sm-12" id="box_top_5_tabungan_wrapper">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->lang->line('dash_top_5_savings') ?: '5 Siswa Tabungan Terbesar' ?></h3>
            </div>
            <div class="box-body no-padding" style="min-height: 270px; overflow-y: auto;">
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
                        <tbody id="tbody_top_5_tabungan">
                            <!-- Loaded via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="overlay" id="overlay_top_tabungan">
                <i class="fa fa-refresh fa-spin"></i>
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

<script>
    $(document).ready(function() {
        var selectedMajors = '<?= isset($selected_majors) ? $selected_majors : '' ?>';

        $('#select_majors').select2({
            width: '100%',
            theme: 'classic',
        });

        // 1. DataTables Realisasi Akun Pembayaran (inisiasi form filter)
        if ($('#table_realisasi_akun').length) {
            $('#filter_period_realisasi').select2({
                width: '100%',
                theme: 'classic'
            });
            $('#start_month').select2({
                theme: 'classic',
                width: '100%',
            });
            $('#end_month').select2({
                theme: 'classic',
                width: '100%'
            });

            $('#btn_filter_realisasi').on('click', function() {
                if ($.fn.DataTable.isDataTable('#table_realisasi_akun')) {
                    $('#table_realisasi_akun').DataTable().ajax.reload();
                }
            });

            $('#btn_export_realisasi').on('click', function(e) {
                e.preventDefault();
                var period_id = $('#filter_period_realisasi').val() || '';
                var start_month = $('#start_month').val() || '';
                var end_month = $('#end_month').val() || '';
                var url = "<?= site_url('manage/dashboard/export_realisasi_excel') ?>?period_id=" + period_id + "&start_month=" + start_month + "&end_month=" + end_month;
                window.open(url, '_blank');
            });
        }

        // 2. Doughnut Chart: Ikhtisar Keuangan
        var ctxDoughnut = document.getElementById("Chart_ikhtisarDoughnut");
        var chartIkhtisarInstance = null;

        if (ctxDoughnut) {
            chartIkhtisarInstance = new Chart(ctxDoughnut.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Pemasukan', 'Pengeluaran'],
                    datasets: [{
                        data: [0, 0],
                        backgroundColor: ['#28A745', '#DD4B39'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutoutPercentage: 50,
                    legend: {
                        display: false
                    },
                    plugins: {
                        labels: {
                            render: 'percentage',
                            precision: 1,
                            fontColor: '#fff',
                            fontSize: 11,
                            fontStyle: 'bold'
                        }
                    },
                    tooltips: {
                        enabled: false,

                        custom: function(tooltipModel) {
                            var tooltipEl = document.getElementById(
                                'ikhtisar_doughnut_tooltip'
                            );

                            if (!tooltipEl) return;

                            if (tooltipModel.opacity === 0) {
                                tooltipEl.style.display = 'none';
                                return;
                            }

                            if (!tooltipModel.dataPoints ||
                                tooltipModel.dataPoints.length === 0) {
                                tooltipEl.style.display = 'none';
                                return;
                            }

                            var index = tooltipModel.dataPoints[0].index;
                            var chart = chartIkhtisarInstance;

                            var label = chart.data.labels[index] || '';
                            var value = chart.data.datasets[0].data[index] || 0;

                            tooltipEl.textContent = label + ': Rp ' +
                                Number(value).toLocaleString('id-ID');

                            tooltipEl.style.display = 'block';
                        }
                    }
                }
            });
        }

        $('#filter_bulan_ikhtisar').on('change', function() {
            var selectedMonth = $(this).val();
            var selectedYear = $(this).find(':selected').data('year');

            $.ajax({
                url: '<?= site_url('manage/dashboard/ajax_ikhtisar_keuangan') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    month: selectedMonth,
                    year: selectedYear,
                    majors: selectedMajors
                },
                beforeSend: function() {
                    $('#box_ikhtisar_keuangan').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                },
                success: function(res) {
                    if (res) {
                        $('#ikhtisar_total_display').text(res.total_display);
                        $('#ikhtisar_pemasukan_text').text(res.pemasukan.text);
                        $('#ikhtisar_pemasukan_pct').text(res.pemasukan.pct);
                        $('#ikhtisar_pengeluaran_text').text(res.pengeluaran.text);
                        $('#ikhtisar_pengeluaran_pct').text(res.pengeluaran.pct);
                        $('#ikhtisar_saldo_text').text(res.saldo_akhir.text);
                        $('#ikhtisar_saldo_pct').text(res.saldo_akhir.pct);

                        if (chartIkhtisarInstance) {
                            chartIkhtisarInstance.data.datasets[0].data = [
                                res.pemasukan.val,
                                res.pengeluaran.val
                            ];
                            chartIkhtisarInstance.update();
                        }
                    }
                },
                complete: function() {
                    $('#box_ikhtisar_keuangan .overlay').remove();
                }
            });
        });

        // 3. Modal Tagihan Bulan Ini
        $('#modal_tagihan_bulan_ini').on('show.bs.modal', function() {
            var $loading = $('#modal_tagihan_loading');
            var $content = $('#modal_tagihan_content');

            $loading.show();
            $content.hide().empty();

            var currentMajors = '<?= isset($selected_majors) ? $selected_majors : '' ?>';

            $.ajax({
                url: '<?= site_url("manage/dashboard/ajax_tagihan_bulan_ini") ?>',
                type: 'POST',
                data: {
                    majors_id: currentMajors
                },
                dataType: 'json',
                success: function(res) {
                    $loading.hide();
                    if (res && res.data && res.data.length > 0) {
                        var html = '';
                        $.each(res.data, function(i, item) {
                            html += '<div class="progress-group" style="margin-bottom: 12px;">';
                            html += '  <span class="progress-text" style="font-size: 12px; font-weight: bold;">' + item.name + '</span>';
                            html += '  <span class="progress-number pull-right" style="font-size: 12px;"><b>' + item.text + '</b> (' + item.progress + '%)</span>';
                            html += '  <div class="progress sm" style="margin-top: 5px; margin-bottom: 0; height: 10px;">';
                            html += '    <div class="progress-bar ' + item.bar + '" style="width: ' + item.progress + '%"></div>';
                            html += '  </div>';
                            html += '</div>';
                        });
                        $content.html(html).show();
                    } else {
                        $content.html('<p class="text-muted text-center">Tidak ada data tagihan bulan ini.</p>').show();
                    }
                },
                error: function() {
                    $loading.hide();
                    $content.html('<p class="text-danger text-center">Gagal mengambil data tagihan.</p>').show();
                }
            });
        });

        // 4. Line Chart: Arus Kas X Bulan Terakhir (dengan filter [3, 6, 9, 12])
        window.dataArusKas = {};

        var ctxArusKas = document.getElementById("Chart_arusKas");
        var chartArusKasInstance = null;

        if (ctxArusKas) {
            chartArusKasInstance = new Chart(ctxArusKas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                            label: '<?= $this->lang->line('dash_income') ?>',
                            data: [],
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            pointBackgroundColor: '#28a745',
                            pointRadius: 4,
                            fill: true,
                            borderWidth: 2
                        },
                        {
                            label: '<?= $this->lang->line('dash_expense') ?>',
                            data: [],
                            borderColor: '#dc3545',
                            backgroundColor: 'rgba(220, 53, 69, 0.1)',
                            pointBackgroundColor: '#dc3545',
                            pointRadius: 4,
                            fill: true,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 10,
                            fontSize: 10
                        }
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                fontSize: 10
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                fontSize: 10,
                                callback: function(value) {
                                    if (value >= 1000000000000) {
                                        return (value / 1000000000000).toLocaleString('id-ID') + ' <?= $this->lang->line('singkatan_triliun') ?>';
                                    } else if (value >= 1000000000) {
                                        return (value / 1000000000).toLocaleString('id-ID') + ' <?= $this->lang->line('singkatan_miliar') ?>';
                                    } else if (value >= 1000000) {
                                        return (value / 1000000).toLocaleString('id-ID') + ' <?= $this->lang->line('singkatan_juta') ?>';
                                    } else if (value >= 1000) {
                                        return (value / 1000).toLocaleString('id-ID') + ' <?= $this->lang->line('singkatan_ribu') ?>';
                                    } else {
                                        return value.toLocaleString('id-ID');
                                    }
                                }
                            },
                            scaleLabel: {
                                display: false
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var label = data.datasets[tooltipItem.datasetIndex].label || '';
                                var value = tooltipItem.yLabel || 0;
                                return label + ': Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            });
        }

        $('#filter_arus_kas_bulan').on('change', function() {
            var selectedBulan = $(this).val();
            var titleTemplate = $('#title_arus_kas').data('title-template') || 'Arus Kas %s Bulan Terakhir';
            $('#title_arus_kas').text(titleTemplate.replace('%s', selectedBulan));

            if (chartArusKasInstance && window.dataArusKas && window.dataArusKas[selectedBulan]) {
                chartArusKasInstance.data.labels = window.dataArusKas[selectedBulan].labels;
                chartArusKasInstance.data.datasets[0].data = window.dataArusKas[selectedBulan].pemasukan;
                chartArusKasInstance.data.datasets[1].data = window.dataArusKas[selectedBulan].pengeluaran;
                chartArusKasInstance.update();
            }
        });

        // 5. Combo Bar & Line Chart: Grafik Pembayaran SPP
        var ctxSpp = document.getElementById("Chart_pembayaranSPP");
        var chartSppInstance = null;
        if (ctxSpp) {
            chartSppInstance = new Chart(ctxSpp.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                            label: 'Collection Rate (%)',
                            type: 'line',
                            data: [],
                            borderColor: '#007bff',
                            backgroundColor: 'transparent',
                            pointBackgroundColor: '#007bff',
                            pointRadius: 4,
                            yAxisID: 'y-axis-rate',
                            fill: false,
                            borderWidth: 2
                        },
                        {
                            label: '<?= $this->lang->line('dash_lunas') ?: 'Lunas' ?>',
                            type: 'bar',
                            data: [],
                            backgroundColor: '#28a745',
                            yAxisID: 'y-axis-count'
                        },
                        {
                            label: '<?= $this->lang->line('dash_belum_lunas') ?: 'Belum Lunas' ?>',
                            type: 'bar',
                            data: [],
                            backgroundColor: '#dc3545',
                            yAxisID: 'y-axis-count'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 10,
                            fontSize: 10
                        }
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                fontSize: 10
                            }
                        }],
                        yAxes: [{
                                id: 'y-axis-count',
                                type: 'linear',
                                position: 'left',
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: 10
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Siswa',
                                    fontSize: 10
                                }
                            },
                            {
                                id: 'y-axis-rate',
                                type: 'linear',
                                position: 'right',
                                ticks: {
                                    beginAtZero: true,
                                    max: 100,
                                    fontSize: 10,
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                },
                                gridLines: {
                                    display: false
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Rate (%)',
                                    fontSize: 10
                                }
                            }
                        ]
                    }
                }
            });
        }

        // 7. Modal Detail Tabungan Siswa
        var dtDetailTabungan = null;
        $(document).on('click', '.row-tabungan-siswa', function() {
            var studentId = $(this).data('student-id');
            if (!studentId) return;

            $('#modal_detail_tabungan').modal('show');
            var $loading = $('#modal_tabungan_loading');
            var $info = $('#modal_tabungan_info');
            var $tableWrap = $('#modal_tabungan_table_wrap');

            $loading.show();
            $info.hide();
            $tableWrap.hide();

            if (dtDetailTabungan) {
                dtDetailTabungan.destroy();
                dtDetailTabungan = null;
            }
            $('#modal_tabungan_tbody').empty();

            $.ajax({
                url: '<?= site_url("manage/dashboard/ajax_detail_tabungan_siswa") ?>',
                type: 'POST',
                data: {
                    student_id: studentId
                },
                dataType: 'json',
                success: function(res) {
                    $loading.hide();
                    if (res && res.student) {
                        $('#info_nis').text(res.student.student_nis || '-');
                        $('#info_nama').text(res.student.student_full_name || '-');
                        $('#info_unit').text(res.student.majors_name || res.student.majors_short_name || '-');
                        $('#info_kelas').text(res.student.class_name || '-');
                        $info.show();

                        $('#tfoot_setoran').text(res.total_setoran || 'Rp0');
                        $('#tfoot_penarikan').text(res.total_penarikan || 'Rp0');
                        $('#tfoot_saldo_akhir').text(res.saldo_akhir || 'Rp0');

                        var rowsHtml = '';
                        if (res.data && res.data.length > 0) {
                            $.each(res.data, function(i, item) {
                                rowsHtml += '<tr>';
                                rowsHtml += '<td class="text-center">' + item.no + '</td>';
                                rowsHtml += '<td>' + item.date + '</td>';
                                rowsHtml += '<td class="text-right">' + item.debit + '</td>';
                                rowsHtml += '<td class="text-right">' + item.kredit + '</td>';
                                rowsHtml += '<td>' + (item.note || '-') + '</td>';
                                rowsHtml += '<td class="text-right font-weight-bold">' + item.saldo + '</td>';
                                rowsHtml += '</tr>';
                            });
                        }
                        $('#modal_tabungan_tbody').html(rowsHtml);
                        $tableWrap.show();

                        dtDetailTabungan = $('#table_detail_tabungan').DataTable({
                            paging: true,
                            searching: true,
                            ordering: false,
                            info: true,
                            autoWidth: false,
                            pageLength: 10,
                            lengthMenu: [
                                [10, 25, 50, -1],
                                [10, 25, 50, "Semua"]
                            ]
                        });
                    } else {
                        $('#modal_tabungan_tbody').html('<tr><td colspan="6" class="text-center text-muted">Data tidak ditemukan.</td></tr>');
                        $tableWrap.show();
                    }
                },
                error: function() {
                    $loading.hide();
                    alert('Gagal mengambil detail tabungan siswa.');
                }
            });
        });

        window.loadFinanceWidgets = function(m) {
            var queryParam = m ? ('?m=' + m) : '';

            // Tampilkan kembali semua overlay loader untuk memulai lazyload per widget
            $('#overlay_ikhtisar, #overlay_tagihan, #overlay_arus_kas, #overlay_spp, #overlay_recent_tr, #overlay_top_tabungan').show();

            // 1. Ikhtisar Keuangan
            $.ajax({
                url: '<?= site_url("manage/dashboard/ajax_finance_ikhtisar") ?>' + queryParam,
                type: 'GET',
                dataType: 'json',
                success: function(ikhtisar) {
                    if (ikhtisar) {
                        $('#ikhtisar_total_display').text(ikhtisar.total_display || '0');
                        $('#ikhtisar_pemasukan_text').text(ikhtisar.pemasukan ? ikhtisar.pemasukan.text : '0');
                        $('#ikhtisar_pemasukan_pct').text(ikhtisar.pemasukan ? ikhtisar.pemasukan.pct : '0%');
                        $('#ikhtisar_pengeluaran_text').text(ikhtisar.pengeluaran ? ikhtisar.pengeluaran.text : '0');
                        $('#ikhtisar_pengeluaran_pct').text(ikhtisar.pengeluaran ? ikhtisar.pengeluaran.pct : '0%');
                        $('#ikhtisar_saldo_text').text(ikhtisar.saldo_akhir ? ikhtisar.saldo_akhir.text : '0');
                        $('#ikhtisar_saldo_pct').text(ikhtisar.saldo_akhir ? ikhtisar.saldo_akhir.pct : '0%');

                        if (chartIkhtisarInstance) {
                            chartIkhtisarInstance.data.datasets[0].data = [
                                ikhtisar.pemasukan ? ikhtisar.pemasukan.val : 0,
                                ikhtisar.pengeluaran ? ikhtisar.pengeluaran.val : 0
                            ];
                            chartIkhtisarInstance.update();
                        }
                    }
                },
                complete: function() {
                    $('#overlay_ikhtisar').fadeOut();
                }
            });

            // 2. Tagihan Bulan Ini
            $.ajax({
                url: '<?= site_url("manage/dashboard/ajax_finance_tagihan") ?>' + queryParam,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    var tagihanHtml = '';
                    if (res && res.tagihan_bulan_ini && res.tagihan_bulan_ini.length > 0) {
                        $.each(res.tagihan_bulan_ini, function(i, tg) {
                            tagihanHtml += '<div class="progress-group" style="margin-bottom: 8px;">' +
                                '<span class="progress-text" style="font-size: 12px;">' + tg.name + '</span>' +
                                '<span class="progress-number pull-right" style="font-size: 12px;"><b>' + tg.text + '</b> (' + tg.progress + '%)</span>' +
                                '<div class="progress sm" style="margin-top: 3px; margin-bottom: 0; height: 8px;">' +
                                '<div class="progress-bar ' + tg.bar + '" style="width: ' + tg.progress + '%"></div>' +
                                '</div></div>';
                        });
                    } else {
                        tagihanHtml = '<p class="text-muted text-center" style="margin-top: 50px;">Tidak ada tagihan bulan ini</p>';
                    }
                    $('#container_tagihan_bulan_ini').html(tagihanHtml);

                    if (res && res.has_more_tagihan) {
                        $('#tools_tagihan_bulan_ini').html('<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_tagihan_bulan_ini"><?= $this->lang->line("dash_view_all") ?: "Lihat Semua" ?></button>');
                    } else {
                        $('#tools_tagihan_bulan_ini').empty();
                    }
                },
                complete: function() {
                    $('#overlay_tagihan').fadeOut();
                }
            });

            // 3. Arus Kas
            $.ajax({
                url: '<?= site_url("manage/dashboard/ajax_finance_arus_kas") ?>' + queryParam,
                type: 'GET',
                dataType: 'json',
                success: function(arusKas) {
                    if (arusKas) {
                        window.dataArusKas = arusKas;
                        var selectedBulan = $('#filter_arus_kas_bulan').val() || 6;
                        if (chartArusKasInstance && window.dataArusKas[selectedBulan]) {
                            chartArusKasInstance.data.labels = window.dataArusKas[selectedBulan].labels;
                            chartArusKasInstance.data.datasets[0].data = window.dataArusKas[selectedBulan].pemasukan;
                            chartArusKasInstance.data.datasets[1].data = window.dataArusKas[selectedBulan].pengeluaran;
                            chartArusKasInstance.update();
                        }
                    }
                },
                complete: function() {
                    $('#overlay_arus_kas').fadeOut();
                }
            });
            // 4. SPP Chart & Col layout
            $.ajax({
                url: '<?= site_url("manage/dashboard/ajax_finance_spp") ?>' + queryParam,
                type: 'GET',
                dataType: 'json',
                success: function(sppRes) {
                    if (sppRes) {
                        if (sppRes.spp_chart_col_class) {
                            $('#box_spp_chart_wrapper').attr('class', sppRes.spp_chart_col_class);
                        }
                        if (sppRes.other_widget_col_class) {
                            $('#box_recent_tr_wrapper').attr('class', sppRes.other_widget_col_class);
                            $('#box_top_5_tabungan_wrapper').attr('class', sppRes.other_widget_col_class);
                        }
                        if (sppRes.spp_chart_title) {
                            $('#spp_chart_title_el').text(sppRes.spp_chart_title);
                        }
                        if (sppRes.spp_chart && chartSppInstance) {
                            chartSppInstance.data.labels = sppRes.spp_chart.labels || [];
                            chartSppInstance.data.datasets[0].data = sppRes.spp_chart.collection_rate || [];
                            chartSppInstance.data.datasets[1].data = sppRes.spp_chart.lunas || [];
                            chartSppInstance.data.datasets[2].data = sppRes.spp_chart.belum_lunas || [];
                            chartSppInstance.update();
                        }
                    }
                },
                complete: function() {
                    $('#overlay_spp').fadeOut();
                }
            });

            // 5. Transaksi Terbaru
            $.ajax({
                url: '<?= site_url("manage/dashboard/ajax_finance_recent_tr") ?>' + queryParam,
                type: 'GET',
                dataType: 'json',
                success: function(recentTr) {
                    var trHtml = '';
                    if (recentTr && recentTr.length > 0) {
                        $.each(recentTr, function(i, tr) {
                            trHtml += '<tr>' +
                                '<td style="font-size:12px; white-space:nowrap;">' + tr.tgl + '</td>' +
                                '<td style="font-size:12px;">' + tr.desc + '</td>' +
                                '<td style="font-size:12px;">' + tr.kategori + '</td>' +
                                '<td style="font-size:12px; white-space:nowrap;">' + tr.nominal + '</td>' +
                                '<td><span class="label ' + tr.label + ' label-xs">' + tr.status + '</span></td>' +
                                '</tr>';
                        });
                    } else {
                        trHtml = '<tr><td colspan="5" class="text-center" style="font-size:11px; padding: 15px;">Tidak ada transaksi terbaru</td></tr>';
                    }
                    $('#tbody_recent_transactions').html(trHtml);
                },
                complete: function() {
                    $('#overlay_recent_tr').fadeOut();
                }
            });

            // 6. Top 5 Tabungan
            $.ajax({
                url: '<?= site_url("manage/dashboard/ajax_finance_top_tabungan") ?>' + queryParam,
                type: 'GET',
                dataType: 'json',
                success: function(topTb) {
                    var tbHtml = '';
                    if (topTb && topTb.length > 0) {
                        $.each(topTb, function(i, tb) {
                            var formatSaldo = 'Rp ' + parseFloat(tb.total_saldo || 0).toLocaleString('id-ID');
                            tbHtml += '<tr class="row-tabungan-siswa" data-student-id="' + tb.student_id + '" style="cursor: pointer;" title="Klik untuk lihat detail transaksi">' +
                                '<td class="text-center" style="font-size: 12px;">' + (i + 1) + '</td>' +
                                '<td style="font-size:12px; font-weight: 600;">' + (tb.student_full_name || '') + '</td>' +
                                '<td class="text-center" style="font-size:12px;">' + (tb.class_name || '-') + '</td>' +
                                '<td class="text-center" style="font-size:12px;">' + (tb.majors_short_name || '-') + '</td>' +
                                '<td class="text-right" style="font-size:12px; font-weight: 600;">' + formatSaldo + '</td>' +
                                '</tr>';
                        });
                    } else {
                        tbHtml = '<tr><td colspan="5" class="text-center text-muted">Tidak ada data</td></tr>';
                    }
                    $('#tbody_top_5_tabungan').html(tbHtml);
                },
                complete: function() {
                    $('#overlay_top_tabungan').fadeOut();
                }
            });
            // 7. DataTables Realisasi Akun Pembayaran (Lazyload)
            if ($('#table_realisasi_akun').length) {
                if ($.fn.DataTable.isDataTable('#table_realisasi_akun')) {
                    $('#table_realisasi_akun').DataTable().ajax.reload();
                } else {
                    $('#table_realisasi_akun').DataTable({
                        "processing": true,
                        "serverSide": false,
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "searching": false,
                        "ajax": {
                            "url": "<?= site_url('manage/dashboard/ajax_realisasi_akun') ?>",
                            "type": "POST",
                            "data": function(d) {
                                d.period_id = $('#filter_period_realisasi').val();
                                d.start_month = $('#start_month').val();
                                d.end_month = $('#end_month').val();
                                d.majors_id = m || selectedMajors;
                            }
                        },
                        "columns": [{
                                "data": "majors_short_name",
                                "className": "text-center"
                            },
                            {
                                "data": "unit"
                            },
                            {
                                "data": "tagihan",
                                "className": "text-right"
                            },
                            {
                                "data": "terbayar",
                                "className": "text-right"
                            },
                            {
                                "data": "belum",
                                "className": "text-right"
                            },
                            {
                                "data": "pencapaian",
                                "className": "text-center"
                            }
                        ],
                        "createdRow": function(row, data, dataIndex) {
                            if (dataIndex % 2 === 0) {
                                $(row).css('background-color', '#a8e9f4');
                            }
                        },
                        "language": {
                            "emptyTable": "<?= $this->lang->line('dash_no_data') ?>",
                            "zeroRecords": "<?= $this->lang->line('dash_no_data') ?>"
                        },
                        "footerCallback": function(row, data, start, end, display) {
                            var api = this.api();
                            var tagihan = api.column(2).data().reduce(function(a, b) {
                                return a + (parseFloat(String(b).replace(/[^0-9]/g, '')) || 0);
                            }, 0);
                            var terbayar = api.column(3).data().reduce(function(a, b) {
                                return a + (parseFloat(String(b).replace(/[^0-9]/g, '')) || 0);
                            }, 0);
                            var belum = api.column(4).data().reduce(function(a, b) {
                                return a + (parseFloat(String(b).replace(/[^0-9]/g, '')) || 0);
                            }, 0);
                            var pct = tagihan > 0 ? (terbayar / tagihan * 100).toFixed(1) : '0.0';

                            var fmt = function(n) {
                                return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            };
                            $(api.column(2).footer()).html(fmt(tagihan));
                            $(api.column(3).footer()).html(fmt(terbayar));
                            $(api.column(4).footer()).html(fmt(belum));
                            $(api.column(5).footer()).html('<span class="label ' + (pct >= 100 ? 'label-success' : pct >= 75 ? 'label-primary' : pct >= 50 ? 'label-warning' : 'label-danger') + '">' + pct.replace('.', ',') + '%</span>');
                        },
                        "bDestroy": true,
                        "responsive": true,
                        "autoWidth": false
                    });
                }
            }
        };

        window.renderFinanceTab = function(data) {
            // Backward compatibility
            var m = '<?= isset($_GET["m"]) ? (int)$_GET["m"] : "" ?>';
            window.loadFinanceWidgets(m);
        };
    });
</script>