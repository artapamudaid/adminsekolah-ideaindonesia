<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Analisa dan Performa
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Analisa dan Performa</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua-gradient">
          <span class="info-box-icon"><i class="fa fa-calculator icon" aria-hidden="true"></i></span>

          <div class="info-box-content">
            <span class="info-box-text"><strong>Penerimaan KAS</strong></span>
            <span class="info-box-number" style="opacity: 0;">&nbsp;</span>

            <span class="info-box-text dash-text">Hari ini</span>
            <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_debit_today, 0, ',', '.') ?></span>
            <span class="info-box-text dash-text">Bulan ini</span>
            <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_debit_month, 0, ',', '.') ?></span>
            <span class="info-box-text dash-text">Tahun ini</span>
            <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_debit_period, 0, ',', '.') ?></span>
          </div>
        </div>
      </div>
      <!-- /.col -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-red-gradient">
          <span class="info-box-icon"><i class="fa fa-money icon"></i></span>

          <div class="info-box-content">
            <span class="info-box-text"><strong>Pengeluaran KAS</strong></span>
            <span class="info-box-number" style="opacity: 0;">&nbsp;</span>

            <span class="info-box-text dash-text">Hari ini</span>
            <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_kredit_today, 0, ',', '.') ?></span>
            <span class="info-box-text dash-text">Bulan ini</span>
            <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_kredit_month, 0, ',', '.') ?></span>
            <span class="info-box-text dash-text">Tahun ini</span>
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

            <span class="info-box-text"><strong>Pembayaran Siswa</strong></span>
            <span class="info-box-number" style="opacity: 0;">&nbsp;</span>

            <span class="info-box-text dash-text">Hari ini</span>
            <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_pay_today, 0, ',', '.') ?></span>
            <span class="info-box-text dash-text">Bulan ini</span>
            <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_pay_month, 0, ',', '.') ?></span>
            <span class="info-box-text dash-text">Tahun ini</span>
            <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_pay_period, 0, ',', '.') ?></span>
          </div>
        </div>
      </div>
      <!-- /.col -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow-gradient">
          <span class="info-box-icon"><i class="fa fa-credit-card icon"></i></span>

          <div class="info-box-content">
            <span class="info-box-text"><strong>Tagihan Siswa</strong></span>
            <span class="info-box-number" style="opacity: 0;">&nbsp;</span>

            <span class="info-box-text dash-text">Bulan ini</span>
            <span class="info-box-number"><?php echo 'Rp. ' . number_format($tagihan_bulan_ini, 0, ',', '.') ?></span>
            <span class="info-box-text dash-text">Tahun ini</span>
            <span class="info-box-number"><?php echo 'Rp. ' . number_format($tagihan_tahun_ini, 0, ',', '.') ?></span>
            <span class="info-box-text dash-text" style="opacity: 0;">&nbsp;</span>
            <span class="info-box-number" style="opacity: 0;">&nbsp;</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow-gradient">
          <span class="info-box-icon"><i class="fa fa-users icon"></i></span>
          <div class="info-box-content">
            <span class="info-box-text"><strong>Jumlah Siswa</strong></span>
            <span class="info-box-number" style="opacity: 0;">&nbsp;</span>

            <span class="info-box-text dash-text">Aktif</span>
            <span class="info-box-number"><?php echo $total_active ?></span>
            <span class="info-box-text dash-text">Alumni</span>
            <span class="info-box-number"><?php echo $total_alumni ?></span>
            <span class="info-box-text dash-text">Keterangan Lainnya</span>
            <span class="info-box-number"><?php echo $total_other ?></span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green-gradient">
          <span class="info-box-icon"><i class="fa fa-users icon"></i></span>
          <div class="info-box-content">
            <span class="info-box-text"><strong>Jumlah Guru</strong></span>
            <span class="info-box-number" style="opacity: 0;">&nbsp;</span>

            <span class="info-box-text dash-text">Status Tetap</span>
            <span class="info-box-number"><?php echo $total_tetap ?></span>
            <span class="info-box-text dash-text">Tidak tetap</span>
            <span class="info-box-number"><?php echo $total_non_tetap ?></span>
            <span class="info-box-text dash-text">Total semua</span>
            <span class="info-box-number"><?php echo $total_all ?></span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-red-gradient">
          <span class="info-box-icon"><i class="fa fa-google-wallet icon"></i></span>
          <div class="info-box-content">
            <span class="info-box-text"><strong>Tabungan Siswa</strong></span>
            <span class="info-box-number" style="opacity: 0;">&nbsp;</span>

            <span class="info-box-text dash-text">Hari ini</span>
            <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_banking_today, 0, ',', '.') ?></span>
            <span class="info-box-text dash-text">Bulan ini</span>
            <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_banking_month, 0, ',', '.') ?></span>
            <span class="info-box-text dash-text">Tahun ini</span>
            <span class="info-box-number"><?php echo 'Rp. ' . number_format($total_banking_period, 0, ',', '.') ?></span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua-gradient">
          <span class="info-box-icon">
            <i class="fa fa-calendar icon"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text"><strong>Masa Aktif</strong></span>
            <span class="info-box-number" style="opacity: 0;">&nbsp;</span>

            <span class="info-box-text dash-text">Pilihan Paket</span>
            <span class="info-box-number">Paket Atas</span>
            <span class="info-box-text dash-text">Mulai Tanggal</span>
            <span class="info-box-number">20/02/2025</span>
            <span class="info-box-text dash-text">Berakhir Tanggal</span>
            <span class="info-box-number">20/02/2026</span>
          </div>
        </div>
      </div>
    </div>

    <!-- row -->
    <div class="row">
      <div class="col-md-6">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Tagihan Siswa Setiap Unit</h3>
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
                <tr>
                  <th>Jenjang</th>
                  <th style="text-align:center;">Terlambat 3 Bulan</th>
                  <th style="text-align:center;">Terlambat Lebih dari 3 Bulan</th>
                </tr>
                <tbody>
                  <?php
                  $totalA = 0;
                  $totalB = 0;
                  foreach ($groupTagihan as $key => $value) {
                  ?>
                    <tr>
                      <td><?php echo $key ?></td>
                      <td align="center">
                        <?php
                        $totalA += $value['kategori_a'];
                        echo 'Rp. ' .  number_format($value['kategori_a'], 0, ',', '.')
                        ?>
                      </td>
                      <td align="center">
                        <?php
                        $totalB += $value['kategori_b'];
                        echo 'Rp. ' . number_format($value['kategori_b'], 0, ',', '.')
                        ?>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot style="font-weight: bold;">
                  <td>Total</td>
                  <td align="center"><?php echo 'Rp. ' . number_format($totalA, 0, ',', '.') ?></td>
                  <td align="center"><?php echo 'Rp. ' . number_format($totalB, 0, ',', '.') ?></td>
                </tfoot>
              </table>
            </div>
            <div class="box-footer text-center">
              <a href="<?php echo site_url('manage/statistik/tagihan_siswa'); ?>" class="uppercase">Lihat Semua Tagihan</a>
            </div>
            <!-- /.box-footer -->
          </div>
        </div>

        <!-- <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Laporan Akunting</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-hover table-stripes">
                <tr>
                  <th>Keterangan</th>
                  <th style="text-align:center;">Bulan Ini</th>
                  <th style="text-align:center;">Tahun Ajaran Ini</th>
                </tr>
                <tr>
                  <td>Neraca</td>
                  <td align="center">0</td>
                  <td align="center">0</td>
                </tr>
                <tr>
                  <td>Rugi Laba</td>
                  <td align="center">0</td>
                  <td align="center">0</td>
                </tr>
                <tr>
                  <td>Modal</td>
                  <td align="center">0</td>
                  <td align="center">0</td>
                </tr>
                </tbody>
              </table>
            </div>
            <div class="box-footer text-center">
              <a href="<?php echo site_url('manage/statistik/laporan_akunting'); ?>" class="uppercase">Lihat Laporan Akunting</a>
            </div>
          </div>
        </div> -->

        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">5 Top Pemasukan per akun</h3>
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
                <tr>
                  <th>Keterangan</th>
                  <th style="text-align:center;">Unit</th>
                  <th style="text-align:center;">Jumlah</th>
                </tr>
                <tbody>
                  <?php foreach ($topfive as $row) { ?>
                    <tr>
                      <td><?php echo $row['account_description'] ?></td>
                      <td align="center"><?php echo $row['majors_short_name'] ?></td>
                      <td align="center"><?php echo 'Rp. ' . number_format($row['total_deb'], 0, ',', '.') ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="box-footer text-center">
              <a href="<?php echo site_url('manage/statistik/pemasukan_per_akun'); ?>" class="uppercase">Lihat Semua Pemasukan per Akun</a>
            </div>
            <!-- /.box-footer -->
          </div>
        </div>

        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Jumlah Status Guru dan Pegawai</h3>
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
                <tr>
                  <th>Keterangan</th>
                  <th style="text-align:center;">Jumlah</th>
                </tr>
                <tr>
                  <td>Total Guru dan Pegawai</td>
                  <td align="center"><?php echo $total_all ?></td>
                </tr>
                <tr>
                  <td>Total Guru Tetap</td>
                  <td align="center"><?php echo $total_tetap ?></td>
                </tr>
                <tr>
                  <td>Total Guru Tidak Tetap</td>
                  <td align="center"><?php echo $total_non_tetap ?></td>
                </tr>
                </tbody>
              </table>
            </div>
            <div class="box-footer text-center">
              <a href="<?php echo site_url('manage/statistik/total_guru_pegawai'); ?>" class="uppercase">Lihat Semua Guru & Pegawai</a>
            </div>
            <!-- /.box-footer -->
          </div>
        </div>
      </div>
      <!-- new card -->
      <div class="col-md-6">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Jurnal Keuangan</h3>
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
                <tr>
                  <th>Keterangan</th>
                  <th style="text-align:center;">Bulan Ini</th>
                  <th style="text-align:center;">Tahun Ajaran Ini</th>
                </tr>
                <tr>
                  <td>Penerimaan</td>
                  <td align="center"><?php echo 'Rp. ' . number_format($total_debit_month, 0, ',', '.') ?></td>
                  <td align="center"><?php echo 'Rp. ' . number_format($total_debit_period, 0, ',', '.') ?></td>
                </tr>
                <tr>
                  <td>Pengeluaran</td>
                  <td align="center"><?php echo 'Rp. ' . number_format($total_kredit_month, 0, ',', '.') ?></td>
                  <td align="center"><?php echo 'Rp. ' . number_format($total_kredit_period, 0, ',', '.') ?></td>
                </tr>
                <tr>
                  <td>Saldo</td>
                  <td align="center"><?php echo 'Rp. ' . number_format($total_debit_month - $total_kredit_month, 0, ',', '.')  ?></td>
                  <td align="center"><?php echo 'Rp. ' . number_format($total_debit_period - $total_kredit_period, 0, ',', '.')  ?></td>
                </tr>
                </tbody>
              </table>
            </div>
            <div class="box-footer text-center">
              <a href="<?php echo site_url('manage/statistik/jurnal_keuangan'); ?>" class="uppercase">Lihat Jurnal Keuangan</a>
            </div>
            <!-- /.box-footer -->
          </div>
        </div>

        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Laporan KAS Setiap Unit</h3>
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
                <tr>
                  <th>Unit</th>
                  <th style="text-align:center;">Bulan Ini</th>
                  <th style="text-align:center;">Tahun Ajaran Ini</th>
                </tr>
                <?php foreach ($kasUnit as $key => $kas) { ?>
                  <tr>
                    <td><?php echo  $key ?></td>
                    <td align="center"><?php echo 'Rp. ' . number_format($kas['bulan'], 0, ',', '.') ?></td>
                    <td align="center"><?php echo 'Rp. ' . number_format($kas['tahun'], 0, ',', '.') ?></td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="box-footer text-center">
              <a href="<?php echo site_url('manage/statistik/laporan_kas'); ?>" class="uppercase">Lihat Semua Laporan KAS</a>
            </div>
            <!-- /.box-footer -->
          </div>
        </div>

        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Jumlah Siswa Setiap Jenjang</h3>
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
                <tr>
                  <th>Jenjang</th>
                  <th style="text-align:center;">Siswa Aktif</th>
                  <th style="text-align:center;">Siswa Keseluruhan</th>
                </tr>
                <?php foreach ($majors as $major) { ?>
                  <tr>
                    <td><?php echo $major['majors_short_name'] ?></td>
                    <td align="center">
                      <?php
                      $studentActive = 0;
                      foreach ($student as $stud) {
                        if ($major['majors_id'] == $stud['majors_majors_id'] && $stud['student_status'] == 1) {
                          $studentActive += 1;
                        }
                      }
                      echo $studentActive;
                      ?>
                    </td>
                    <td align="center">
                      <?php
                      $studentAll = 0;
                      foreach ($student as $stud) {
                        if ($major['majors_id'] == $stud['majors_majors_id']) {
                          $studentAll += 1;
                        }
                      }
                      echo $studentAll;
                      ?>
                    </td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="box-footer text-center">
              <a href="<?php echo site_url('manage/statistik/jumlah_siswa'); ?>" class="uppercase">Lihat Jumlah Siswa</a>
            </div>
            <!-- /.box-footer -->
          </div>
        </div>

      </div>
    </div>
    <!-- row -->
    <div class="row">
      <div class="col-md-6">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Informasi</h3>

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
            <div class="box-footer text-center">
              <a href="<?php echo site_url('manage/information'); ?>" class="uppercase">Lihat Semua Informasi</a>
            </div>
          </div>
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->

      <div class="col-md-6">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Kalender</h3>
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
</script>