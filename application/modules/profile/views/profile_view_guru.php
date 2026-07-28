<div class="content-wrapper">
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<!-- /.box-header -->
					<div class="box-body">
						<div class="col-md-12 col-sm-12 col-xs-12 pull-left">
							<br>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-hover">
										<tbody>
											<tr>
												<td>NIP</td>
												<td>:</td>
												<td><?php echo $guru['employee_nip'] ?></td>
											</tr>
											<tr>
												<td>Nama lengkap</td>
												<td>:</td>
												<td><?php echo $guru['employee_name'] ?></td>
											</tr>
											<tr>
												<td>Jabatan</td>
												<td>:</td>
												<td><?php echo $guru['position_name'] ?></td>
											</tr>
											<tr>
												<td>No. Whatsapp </td>
												<td>:</td>
												<td><?php echo ($guru['employee_phone'] == '+62' or $guru['employee_phone'] == NULL) ? '' : $guru['employee_phone'] ?></td>
											</tr>
										</tbody>
									</table>
								</div>

								<div class="col-md-6 pull-right mt-10">
									<a href="<?php echo site_url('guru/profile/edit') ?>" class="btn btn-sm btn-warning">
										<i class="fa fa-edit"></i> Ganti No. HP/Whatsapp
									</a>
									<a href="<?php echo site_url('guru/profile/edit_bobot') ?>" class="btn btn-sm btn-success">
										<i class="fa fa-list"></i> Bobot Nilai
									</a>
									<a href="<?php echo site_url('guru/profile/cpw'); ?>" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Ganti Password</a>

								</div>
							</div>
							<!-- /.box-body -->
						</div>
					</div>
				</div>

			</div>
		</div>

	</section>
</div>
<script type="text/javascript">
	(function(a) {
		a.createModal = function(b) {
			defaults = {
				title: "",
				message: "Your Message Goes Here!",
				closeButton: true,
				scrollable: false
			};
			var b = a.extend({}, defaults, b);
			var c = (b.scrollable === true) ? 'style="max-height: 420px;overflow-y: auto;"' : "";
			html = '<div class="modal fade" id="myModal">';
			html += '<div class="modal-dialog">';
			html += '<div class="modal-content">';
			html += '<div class="modal-header">';
			html += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>';
			if (b.title.length > 0) {
				html += '<h4 class="modal-title">' + b.title + "</h4>"
			}
			html += "</div>";
			html += '<div class="modal-body" ' + c + ">";
			html += b.message;
			html += "</div>";
			html += '<div class="modal-footer">';
			if (b.closeButton === true) {
				html += '<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>'
			}
			html += "</div>";
			html += "</div>";
			html += "</div>";
			html += "</div>";
			a("body").prepend(html);
			a("#myModal").modal().on("hidden.bs.modal", function() {
				a(this).remove()
			})
		}
	})(jQuery);

	/*
	 * Here is how you use it
	 */
	$(function() {
		$('.view-pdf').on('click', function() {
			var pdf_link = $(this).attr('href');
			var iframe = '<object type="application/pdf" data="' + pdf_link + '" width="100%" height="350">No Support</object>'
			$.createModal({
				title: 'Cetak Kartu Santri',
				message: iframe,
				closeButton: true,
				scrollable: false
			});
			return false;
		});
	})
</script>