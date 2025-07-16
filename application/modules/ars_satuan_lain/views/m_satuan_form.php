<script>
  $(function() {
    $('#batal').click(function(e) {
      e.preventDefault();
      $('#main_form').hide().slideUp().scrollTop(0);
      $('#cari').show();
      $('#tambah').show();
    });
  });
  /* action add or edit */
  $(function() {
    $('#simpan').submit(function(e) {
      e.preventDefault();
      var action = $(this).attr('to');
      var datastring = $(this).serialize();

      $.ajax({
        url: action,
        type: 'post',
        data: datastring,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
          $('form').attr("disabled", "disabled");
          $('form').css("opacity", ".5");
        },
        success: function(data) {
          if (data.ket == 1) {
            swal('Keterangan', 'Data berhasill di simpan', 'success');
            $('form').css("opacity", "");
            $("form").removeAttr("disabled");
            $('#datatables').DataTable().ajax.reload();
          } else if (data.ket == 2) {
            $('#notifikasi').html('<div class="callout callout-danger">' + data.respon + '</div>');
            $('form').css("opacity", "");
            $("form").removeAttr("disabled");
            $('#datatables').DataTable().ajax.reload();
          }
        },
        error: function(data) {
          swal('Keterangan', 'server belum bisa respon', 'warning');
        }
      });
    });
  });
</script>


<div class='row'>
  <div class='col-md-12'>
    <div class='panel panel-info'>
      <div class='panel-heading'><?= ucfirst($judul) ?> Arsip.</div>
      <div class='panel-wrapper collapse in' aria-expanded='true'>
        <div class='panel-body'>
          <form to="<?php echo $action; ?>" id="simpan" method="post">
            <div class="form-group">
              <label for="varchar">Nama Satuan <?php echo form_error('nama_satuan') ?></label>
              <input type="text" class="form-control" name="nama_satuan" id="nama_satuan" placeholder="Nama Satuan" value="<?php echo $nama_satuan; ?>" />
            </div>
            <div class="form-group">
              <label for="varchar">Keterangan <?php echo form_error('keterangan') ?></label>
              <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" value="<?php echo $keterangan; ?>" />
            </div>
            <input type="hidden" name="id_satuan" value="<?php echo $id_satuan; ?>" />
            <button type="submit" id="simpan" class="btn btn-primary btn-xs"><i class='fa fa-save'></i><?php echo $button ?></button>
            <button id="batal" class="btn btn-warning btn-xs"><i class='fa fa-cancel'></i>Batal</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>