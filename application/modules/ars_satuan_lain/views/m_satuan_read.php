 
<div class='col-lg-12'>
<div class='widget'>
    <div class='callout callout-info'>
        <span class='widget-caption'><?= ucfirst($judul) ?></span>
    </div>
  <div class='widget-body'> 
        <div class='form-title'><h2 style="margin-top:0px"><?= ucfirst($judul) ?> : Detail </h2></div>
        <table class="table">
	    <tr><td>Nama Satuan</td><td><?php echo $nama_satuan; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('m_satuan') ?>" class="btn btn-default"><i class='fa fa-home'></i>Back To Home</a></td></tr>
	</table>
</div>
</div>
</div>