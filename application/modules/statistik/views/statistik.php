<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Statistik
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Statistik</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- row -->
    <div class="row">
      <div class="col-md-6">
        <!-- BAR CHART -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Tagihan Siswa Tiap Unit</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                   <canvas id="Chart_tagihan"></canvas>
                </div>
          </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Laporan Akunting</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get', 'style'=>'margin-bottom: 2em')) ?>
							<table>
							    <tr>
                                    <td id="thn_ajaran">
                                    <select style="width: 200px;" id="c" name="c" class="form-control" required>
                                    <option value="">--- Tahun Ajaran ---</option>
                                        <?php foreach($period as $row){?>
                                            <option value="<?php echo $row['period_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['period_id']) ? 'selected' : '' ?>><?php echo $row['period_start'] ?>/<?php echo $row['period_end'] ?></option>
                                        <?php } ?>
                                    </select>
                                    </td>
							        <td>
							            &nbsp&nbsp
							        </td>
							        <td>
							        <button type="submit" class="btn btn-primary">Tampilkan</button>    
							        </td>
							    </tr>
							</table>
				<?php echo form_close(); ?>
                <div class="table-responsive">
                   <canvas id="lap_akunting_line"></canvas>
                </div>
          </div>
        </div>

        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Jumlah Status Guru & Pegawai</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                   <canvas id="Chart_guru_pegawai"></canvas>
                </div>
          </div>
        </div>

      </div>
      <!-- new col -->
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
            <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get', 'style'=>'margin-bottom: 2em')) ?>
							<table>
							    <tr>
                                    <td id="month">
                                    <select style="width: 150px;" id="c" name="c" class="form-control" required>
                                    <option value="">Bulan</option>
                                        <?php foreach($month as $row){?>
                                                <option value="<?php echo $row['month_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['month_id']) ? 'selected' : '' ?>><?php echo $row['month_name'] ?></option>
                                            <?php } ?>
                                    </select>
                                    </td>
                                    <td>
							            &nbsp&nbsp
							        </td>
                                    <td id="thn_ajaran">
                                    <select style="width: 150px;" id="c" name="c" class="form-control" required>
                                    <option value="">Tahun</option>
                                        <?php foreach($period as $row){?>
                                            <option value="<?php echo $row['period_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['period_id']) ? 'selected' : '' ?>><?php echo $row['period_start'] ?>/<?php echo $row['period_end'] ?></option>
                                        <?php } ?>
                                    </select>
                                    </td>
							        <td>
							            &nbsp&nbsp
							        </td>
							        <td>
							        <button type="submit" class="btn btn-primary">Tampilkan</button>    
							        </td>
							    </tr>
							</table>
						<?php echo form_close(); ?>
                <div class="table-responsive">
                   <canvas id="Chart_jurnalKeuangan"></canvas>
                </div>
          </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Laporan KAS Tiap Unit</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                   <canvas id="Chart_lap_kas"></canvas>
                </div>
            </div>
        </div>

        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Jumlah Siswa Tiap Jenjang</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get', 'style'=>'margin-bottom: 2em')) ?>
							<table>
							    <tr>
                                    <td id="thn_ajaran">
                                    <select style="width: 200px;" id="c" name="c" class="form-control" required>
                                    <option value="">--- Tahun Ajaran ---</option>
                                        <?php foreach($period as $row){?>
                                            <option value="<?php echo $row['period_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['period_id']) ? 'selected' : '' ?>><?php echo $row['period_start'] ?>/<?php echo $row['period_end'] ?></option>
                                        <?php } ?>
                                    </select>
                                    </td>
							        <td>
							            &nbsp&nbsp
							        </td>
							        <td>
							        <button type="submit" class="btn btn-primary">Tampilkan</button>    
							        </td>
							    </tr>
							</table>
				<?php echo form_close(); ?>
                <div class="table-responsive">
                   <canvas id="myChart"></canvas>
                </div>
          </div>
        </div>
      </div>  
    </div>
    <!-- row -->
  </section>
  <!-- /.content -->
</div>
