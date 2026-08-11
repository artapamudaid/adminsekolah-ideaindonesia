<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $this->lang->line('dash_title'); ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('dash_home'); ?></a></li>
            <li class="active"><?php echo $this->lang->line('dash_title'); ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-aqua-gradient">
                    <span class="info-box-icon"><i class="fa fa-calculator icon" aria-hidden="true"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text"><strong><?php echo $this->lang->line('dash_receipts'); ?></strong></span>
                        <span class="info-box-number" style="opacity: 0;">kosong</span>

                        <span class="info-box-text dash-text"><?php echo $this->lang->line('dash_today'); ?></span>
                        <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_debit_today, 0, ',', '.') ?></span>
                        <span class="info-box-text dash-text"><?php echo $this->lang->line('dash_this_month'); ?></span>
                        <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_debit_month, 0, ',', '.') ?></span>
                        <span class="info-box-text dash-text"><?php echo $this->lang->line('dash_this_year'); ?></span>
                        <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_debit_period, 0, ',', '.') ?></span>
                    </div>
                </div>
                <!-- <div class="mini-box">
          <a href="#">More Info  <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>
        </div> -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-red-gradient">
                    <span class="info-box-icon"><i class="fa fa-money icon"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text"><strong><?php echo $this->lang->line('dash_disbursements'); ?></strong></span>
                        <span class="info-box-number" style="opacity: 0;">kosong</span>

                        <span class="info-box-text dash-text"><?php echo $this->lang->line('dash_today'); ?></span>
                        <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_kredit_today, 0, ',', '.') ?></span>
                        <span class="info-box-text dash-text"><?php echo $this->lang->line('dash_this_month'); ?></span>
                        <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_kredit_month, 0, ',', '.') ?></span>
                        <span class="info-box-text dash-text"><?php echo $this->lang->line('dash_this_year'); ?></span>
                        <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_kredit_period, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green-gradient">
                    <span class="info-box-icon"><i class="fa fa-bank icon"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text"><strong><?php echo $this->lang->line('dash_student_payments'); ?></strong></span>
                        <span class="info-box-number" style="opacity: 0;">kosong</span>
                        <span class="info-box-text dash-text"><?php echo $this->lang->line('dash_today'); ?></span>
                        <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_pay_today, 0, ',', '.') ?></span>
                        <span class="info-box-text dash-text"><?php echo $this->lang->line('dash_this_month'); ?></span>
                        <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_pay_month, 0, ',', '.') ?></span>
                        <span class="info-box-text dash-text"><?php echo $this->lang->line('dash_this_year'); ?></span>
                        <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_pay_period, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-yellow-gradient">
                    <span class="info-box-icon"><i class="fa fa-credit-card icon"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text"><strong><?php echo $this->lang->line('dash_student_savings'); ?></strong></span>
                        <span class="info-box-number" style="opacity: 0;">kosong</span>
                        <span class="info-box-text dash-text"><?php echo $this->lang->line('dash_deposit'); ?></span>
                        <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_setoran, 0, ',', '.') ?></span>
                        <span class="info-box-text dash-text"><?php echo $this->lang->line('dash_withdrawal'); ?></span>
                        <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_penarikan, 0, ',', '.') ?></span>
                        <span class="info-box-text dash-text"><?php echo $this->lang->line('dash_balance'); ?></span>
                        <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_setoran - $total_penarikan, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('dash_payment_realization'); ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-control" id="period">
                                    <?php foreach ($period as $p) { ?>
                                        <option value="<?= $p['period_id'] ?>" <?= $p['period_id'] == $periodActive ? 'selected' : '' ?>><?= $p['period_start'] . '/' . $p['period_end'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="start">
                                    <?php foreach ($month as $m) { ?>
                                        <option value="<?= $m['month_id'] ?>" <?= $m['month_id'] == 1 ? 'selected' : '' ?>><?= $m['month_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="end">
                                    <?php foreach ($month as $m) { ?>
                                        <option value="<?= $m['month_id'] ?>" <?= $m['month_id'] == 12 ? 'selected' : '' ?>><?= $m['month_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-md btn-default" onclick="get_data()"><?php echo $this->lang->line('dash_filter'); ?></button>
                                <button class="btn btn-md btn-success" onclick="export_xls()"><?php echo $this->lang->line('dash_export_xls'); ?></button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-border table-striped" id="data_tabel">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- row -->
        <div class="row">
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('dash_financial_journal'); ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="table-responsive">
                            <canvas id="Chart_jurnalKeuangan"></canvas>
                        </div>
                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('dash_teacher_staff_status'); ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?php if ($total_all > 0): ?>
                            <div class="table-responsive">
                                <canvas id="Chart_guru_pegawai"></canvas>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($package['setting_value'] == 3) { ?>

                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('dash_earliest_attendance'); ?></h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-stripes">
                                    <?php foreach ($rekap_presensi as $row) : ?>
                                        <tr>
                                            <td><?php echo $row['nama_pegawai']; ?></td>
                                            <td><?php echo $row['tanggal']; ?></td>
                                            <td><?php echo $row['time']; ?></td>
                                            <td><?php echo $row['area_absen_nama']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('dash_information'); ?></h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-stripes">
                                    <?php foreach ($information as $info) : ?>
                                        <tr>
                                            <td><img src="<?php echo upload_url('information/' . $info['information_img']) ?>" width="50px"></td>
                                            <td><?php echo $info['information_title'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php } ?>

            </div>
            <!-- new card -->
            <div class="col-md-6">

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $widget_jumlah_siswa_title; ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('dash_calendar'); ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="calendar"></div>
                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('dash_last_payments'); ?></h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-stripes">
                                <?php foreach ($lastPayments as $lastPayment) : ?>
                                    <tr>
                                        <td><?php echo 'Pembayaran ' . $lastPayment['student_full_name'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<div class="modal fade in" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <?php echo form_open(current_url()); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addModalLabel">Tambah Agenda</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="add" value="1">
                <label>Tanggal*</label>
                <p id="labelDate"></p>
                <input type="hidden" name="date" class="form-control" id="inputDate">
                <label>Keterangan*</label>
                <textarea name="info" id="inputDesc" class="form-control"></textarea><br />
            </div>
            <div class="modal-footer">
                <button type="submit" id="btnSimpan" class="btn btn-success">Simpan</button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="delModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <?php echo form_open(current_url()); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="delModalLabel">Hapus Hari Libur</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="del" value="1">
                <input type="hidden" name="id" id="idDel">
                <label>Tahun</label>
                <p id="showYear"></p>
                <label>Tanggal</label>
                <p id="showDate"></p>
                <label>Keterangan*</label>
                <p id="showDesc"></p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'prevYear,nextYear',
        },

        events: "<?php echo site_url('manage/dashboard/get'); ?>",

        dayClick: function(date, jsEvent, view) {

            var tanggal = date.getDate();
            var bulan = date.getMonth() + 1;
            var tahun = date.getFullYear();
            var fullDate = tahun + '-' + bulan + '-' + tanggal;

            $('#addModal').modal('toggle');
            $('#addModal').modal('show');

            $("#inputDate").val(fullDate);
            $("#labelDate").text(fullDate);
            $("#inputYear").val(date.getFullYear());
            $("#labelYear").text(date.getFullYear());
        },

        eventClick: function(calEvent, jsEvent, view) {
            $("#delModal").modal('toggle');
            $("#delModal").modal('show');
            $("#idDel").val(calEvent.id);
            $("#showYear").text(calEvent.year);

            var tgl = calEvent.start.getDate();
            var bln = calEvent.start.getMonth() + 1;
            var thn = calEvent.start.getFullYear();

            $("#showDate").text(tgl + '-' + bln + '-' + thn);
            $("#showDesc").text(calEvent.title);
        }


    });

    $("#inputDesc").on('change keyup focus input propertychange', function() {
        var desc = $("#inputDesc").val();
        if (desc.trim().length > 0) {
            $("#btnSimpan").removeClass('disabled');
        } else {
            $("#btnSimpan").addClass('disabled');
        }
    })

    $("#closeModal").click(function() {
        $("#inputDesc").val('');
        $("#btnSimpan").addClass('disabled');
    });

    // Pie Chart JUMLAH STATUS GURU DAN PEGAWAI
    <?php if ($total_all > 0):    ?>
        var ctx = document.getElementById("Chart_guru_pegawai").getContext("2d");
        var piechart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["Total Guru dan Pegawai", "Total Guru Tetap", "Total Guru Tidak Tetap"],
                datasets: [{
                    label: "",
                    data: [<?= $total_all ?>, <?= $total_tetap ?>, <?= $total_non_tetap ?>],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    hoverOffset: 1
                }]
            },
            options: {
                plugins: {
                    labels: {
                        fontSize: 20,
                        fontColor: 'black',
                        render: 'value'
                    }
                }
            }
        });
    <?php endif; ?>
</script>

<script>
    // FORMAT LABEL ANGKA
    function number_format(number, decimals, dec_point, thousands_sep) {

        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };

        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    // Bar Chart JURNAL KEUANGAN
    // var ctx = document.getElementById("Chart_jurnalKeuangan").getContext('2d');
    // var myChart = new Chart(ctx, {
    //     type: 'bar',
    //     data: {
    //         labels: ["Penerimaan", "Pengeluaran", "Saldo"],
    //         datasets: [{
    //                 label: "bulan ini",
    //                 data: [<?php echo $total_debit_month ?>, <?php echo $total_kredit_month ?>, <?php echo ($total_debit_month - $total_kredit_month) ?>],
    //                 backgroundColor: "rgba(123, 103, 238)",
    //                 borderWidth: 1
    //             },
    //             {
    //                 label: "thn ajaran ini",
    //                 data: [<?php echo $total_debit_period ?>, <?php echo $total_kredit_period ?>, <?php echo ($total_debit_period - $total_kredit_period) ?>],
    //                 backgroundColor: "rgba(60, 179, 113)",
    //                 borderWidth: 1
    //             }
    //         ]
    //     },
    //     options: {
    //         responsive: true,
    //         plugins: {
    //             labels: {
    //                 render: 'value'
    //             }
    //         },
    //         tooltips: {
    //             callbacks: {
    //                 label: function(tooltipItem, data) {
    //                     return tooltipItem.yLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    //                 }
    //             }
    //         },
    //         scales: {
    //             yAxes: [{
    //                 ticks: {
    //                     beginAtZero: true,
    //                     callback: function(value, index, values) {
    //                         return new Intl.NumberFormat('id-ID').format(value);
    //                     }
    //                 }
    //             }]
    //         }
    //     }
    // });

    var ctx = document.getElementById("Chart_jurnalKeuangan").getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Penerimaan", "Pengeluaran", "Saldo"],
            datasets: [{
                    label: "bulan ini",
                    data: [
                        <?php echo $total_debit_month ?>,
                        <?php echo $total_kredit_month ?>,
                        <?php echo ($total_debit_month - $total_kredit_month) ?>
                    ],
                    backgroundColor: "rgba(123, 103, 238)",
                    borderWidth: 1
                },
                {
                    label: "thn ajaran ini",
                    data: [
                        <?php echo $total_debit_period ?>,
                        <?php echo $total_kredit_period ?>,
                        <?php echo ($total_debit_period - $total_kredit_period) ?>
                    ],
                    backgroundColor: "rgba(60, 179, 113)",
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                labels: {
                    render: 'value'
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return new Intl.NumberFormat('id-ID').format(tooltipItem.yLabel);
                    }
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value, index, values) {
                            return new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }]
            }
        }
    });


    // Bar Chart LAPORAN KAS TIAP UNIT


    // Bar Chart JUMLAH SISWA TIAP JENJANG
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php foreach ($majors as $major) {
                            echo '"' . $major['majors_short_name'] . '", ';
                        } ?>],
            datasets: [{
                label: "Total Siswa",
                data: [<?php foreach ($numStudents as $num) {
                            echo $num['numbers'] . ', ';
                        } ?>],
                backgroundColor: "rgba(60, 179, 113)",
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                labels: {
                    render: 'value'
                }
            }
        }
    });
</script>

<script>
    $(document).ready(function() {
        get_data();
    });

    function get_data() {
        var period = $("#period").val();
        var start = $("#start").val();
        var end = $("#end").val();

        $.ajax({
            type: "GET",
            url: "<?= base_url() . "manage/dashboard/realisasi" ?>",
            data: {
                period: period,
                start: start,
                end: end,
            },
            beforeSend: function() {
                loadingOverlay();
            },
            success: function(data) {
                $("#data_tabel").html(data);
            },
            error: function(jqXHR, textStatus) {
                handleAjaxError(jqXHR, textStatus)
            },
            complete: function() {
                $.LoadingOverlay("hide");
            }
        });
    }
</script>

<script>
    function export_xls() {

        var newTab = window.open('', '_blank');

        if (!newTab || newTab.closed || typeof newTab.closed === 'undefined') {
            alert("Popup Diblok");
        } else {

            let start = $('#start').val();
            let end = $('#end').val();
            let period = $('#period').val();

            newTab.location.href = "<?php echo base_url() . 'manage/dashboard/realisasi_xls' ?>?start=" + start + "&end=" + end + "&period=" + period;
        }
    }
</script>
<style>
    /* Baris Ganjil */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #ccf0d5;
        /* Ganti dengan warna yang diinginkan */
    }

    /* Baris Genap */
    .table-striped tbody tr:nth-of-type(even) {
        background-color: #ffffff;
        /* Ganti dengan warna yang diinginkan */
    }
</style>