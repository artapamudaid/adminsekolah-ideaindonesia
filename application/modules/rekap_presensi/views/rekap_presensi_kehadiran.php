<?php     
header("Content-Type: application/vnd.ms-excel"); //IE and Opera    
header("Content-Type: application/x-msexcel"); // Other browsers    
header("Content-Disposition: attachment; filename=Rekap_Absensi_Periode_".$tgl_awal."_".$tgl_akhir."_download_at_".date('YmdHis').".xls");  
header("Expires: 0");    
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");      
?>
<!DOCTYPE html >
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>REKAPITULASI ABSENSI </title>
    <link rel="icon" type="image/png" href="<?php echo media_url('ico/favicon.ico') ?>">
    <style>
      a {
          color: #000000;
          font-weight: bold;
          font-family: Verdana;
          font-size: 11px;
          text-decoration: none;
      }
      tr, td {
          font-family: Verdana;
          font-size: 11px;
          border-collapse: collapse;
      }
      .td {
          font-weight: bold;
          font-family: Verdana;
          font-size: 11px;
          text-decoration: none;
          border-collapse: collapse;
      }
      .table>tbody>tr.info>td, .table>tbody>tr.info>th, .table>tbody>tr>td.info, .table>tbody>tr>th.info, .table>tfoot>tr.info>td, .table>tfoot>tr.info>th, .table>tfoot>tr>td.info, .table>tfoot>tr>th.info, .table>thead>tr.info>td, .table>thead>tr.info>th, .table>thead>tr>td.info, .table>thead>tr>th.info {
          background-color: #d9edf7;
      }
      .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
          border: 1px solid #f4f4f4;
      }
      .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
          border: 1px solid #ddd;
      }
      .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
          padding: 8px;
          line-height: 1.42857143;
      }
      a:hover {
        color: red;
      }
    </style>
  </head>
  <body>

<table border="0" cellpadding="10" cellpadding="5" width="100%" align="left">   
  <tr>
    <td align="left" valign="top">   
      <center>   
        <font size="4"><strong>REKAPITULASI KEHADIRAN & KEPULANGAN ABSENSI</strong></font><br/>
        <br>   
        TANGGAL : <?=$tgl_awal?> s/d <?=$tgl_akhir?>
      </center><br /><br />   
      <table class="table table-bordered" border=1 style="border-collapse:collapse">
        <thead>   
          <tr bgcolor=#d9edf7 height="30">   
              <th>No</th>
              <th>Pegawai</th>
              <th>Jenis Absen</th>
              <th>Tanggal</th>
              <th>Time</th>
              <th>Kedatangan</th>
              <th>Area Absen</th>
              <th>Titik Absen</th>
              <th>Catatan Absen</th>  
          </tr>
        <thead>
        <tbody>
          <?php
          $no=1;
          foreach($rekap_absensi as $row):
          ?>
          <tr>
            <td><?=$no++?></td>
            <td><?php echo $row['nama_pegawai']; ?></td>
            <td><?php echo $row['jenis_absen']; ?></td>
            <td><?php echo $row['tanggal']; ?></td>
            <td><?php echo $row['time']; ?></td>
            <td><?php echo $row['statushadir']; ?></td>
            <td><?php echo $row['area_absen_nama']; ?></td>
            <td><?php echo '<b><a href="javascript:void(0)" onclick="maps_area(\''.$row['longi'].'\',\''.$row['lati'].'\',null)" data-toggle="tooltip" title="Maps Area">Longi : "'.$row['longi'].'" || Lati : "'.$row['lati'].'"</a></b>'; ?></td>
            
            <td><?php echo $row['catatan_absen']; ?></td>
          </tr>
          <?php
          endforeach;
          ?>
        </tbody>
      </table>   
    </td>   
  </tr>   
  <!-- END TABLE CENTER -->       
</table>

  </body>
</html>