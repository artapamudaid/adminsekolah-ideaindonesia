<html> 
<body>
    <h2>Data Statuan</h2>

    <style>
        table,font { font-family:'Calibri'; line-height:100%; }
        .header,ttl{ font-family:'Calibri'; font-size:14px; line-height:90%; }
        .garis {height:0px; line-height:0px;}
        .text{
          mso-number-format:"\@";/*force text*/
      }
  </style>


  <table class=bsc cellpadding="0" cellspacing="0" border="1"> 
    <tr>
        <th>No</th>
        <th>Nama Satuan</th>
        <th>Keterangan</th>

        </tr><?php
        foreach ($m_satuan_data as $m_satuan)
        {
            ?>
            <tr>
                <td><?php echo ++$start ?></td>
                <td><?php echo $m_satuan->nama_satuan ?></td>
                <td><?php echo $m_satuan->keterangan ?></td>	
            </tr>
            <?php
        }
        ?>
</table>
</body>
</html>