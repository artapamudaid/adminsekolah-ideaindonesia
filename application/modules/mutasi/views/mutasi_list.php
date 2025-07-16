<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
			<small>List</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<div class="row">
						<div class="col-md-2">  
							<div class="form-group">
								<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
									<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
									<input class="form-control" type="text" name="start" id="start" readonly="readonly" placeholder="Tanggal Awal">
								</div>
							</div>
						</div>
						<div class="col-md-2">  
							<div class="form-group">
								<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
									<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
									<input class="form-control" type="text" name="end" id="end" readonly="readonly" placeholder="Tanggal Akhir">	
								</div>
							</div>
						</div>
						<div class="col-md-2">  
							<div class="form-group">
								<select class="form-control" name="status" id="status">
									<option value="">-- SEMUA STATUS --</option>
									<option value="LUNAS">LUNAS</option>
									<option value="PENDING">PENDING</option>
								</select>
							</div>
						</div>
						<div>
							<button type="button" class="btn btn-primary" id="btn_filter">Filter</button>
							<button type="button" class="btn btn-success" id="export_xls">Export</button>
						</div>
					</div>
				</div>
				
					<div class="box-body table-responsive">
						<table id="mydata" class="table table-hover">
							<thead>
								<tr>
									<th>Tanggal Bayar</th>
									<th>No. Ref</th>
									<th>No. VA</th>
									<th>Nama</th>
									<th>Nominal</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody id="show_data">
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

			</div>
		</div>

	</section>
	<!-- /.content -->
</div>

</div>
<script type="text/javascript">
	$(document).ready(function() {
    var dataTable = $('#mydata').DataTable(); // Inisialisasi DataTable

    show_data();

    function show_data(start = null, end = null, status = null) {
        $.ajax({
            type: 'ajax',
            method: 'POST', // Menggunakan metode POST
            url: '<?php echo site_url('manage/mutasi/get_data') ?>',
            data: { start: start, end: end, status: status },
            async: false,
            dataType: 'json',
            success: function(data) {
                let html = '';
                let i;
                let nominal = 0;
                let total = 0;

                for (i = 0; i < data.length; i++) {
                    nominal = parseInt(data[i].NOMINAL);
                    html += '<tr>' +
                        '<td>' + data[i].DATEPAY + '</td>' +
                        '<td>' + data[i].REFNO + '</td>' +
                        '<td style="text-align: center;">' + data[i].VANO + '</td>' +
                        '<td>' + data[i].CUSTNAME + '</td>' +
                        '<td style="text-align: right;">Rp ' + nominal.toLocaleString('id-ID') + '</td>' +
                        '<td>' + data[i].STATUS + '</td>' +
                        '</tr>';
                    total += parseInt(nominal);
                }

                html += '<tr style="background-color: #3dffa4; font-weight: bold;">' +
                    '<td colspan="4" style="text-align: center;">Total</td>' +
                    '<td style="text-align: right;">Rp ' + total.toLocaleString('id-ID') + '</td>' +
					'<td></td>'+
                    '</tr>';

                $('#show_data').html(html);

            }
        });
    }

    $('#btn_filter').on('click', function() {
        let start = $('#start').val();
        let end = $('#end').val();
		let status = $('#status').val();

        show_data(start, end, status);
    });
});

</script>

<script>
$(document).ready(function() {
  $('#export_xls').click(function(event) {
    event.preventDefault();
	  
    var newTab = window.open('', '_blank');

    if (!newTab || newTab.closed || typeof newTab.closed === 'undefined') {
      alert("Popup Diblok");
    } else {
		
	let start 	= $('#start').val();
	let end 	= $('#end').val();
	let status 	= $('#status').val();
	
	newTab.location.href = "<?php echo base_url() . 'manage/mutasi/export_data' ?>?start="+start+"&end="+end+"&status="+status;
	
	/*	
      $.ajax({
        type: "POST",
        url: '<?php echo site_url('manage/mutasi/export_data') ?>',
        data: { start: start, end: end, status: status },
        success: function(response) {
          newTab.document.write(response);
        },
        error: function() {
          newTab.document.write("Error.");
        }
      });
	*/
    }
  });
});
</script>