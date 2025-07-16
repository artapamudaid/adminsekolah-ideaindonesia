<!DOCTYPE html>
<html>

<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="<?php echo media_url('css/bootstrap.min.css') ?>">
</head>

<body>
	<section class="content">
		<div class="row">

			<div class="col-md-12">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Item</th>
							<th>Jumlah Bayar</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (!empty($list)) {
							$i = 1;
							$total = 0;
							$bulan_bill = 0;
							$mergedArray = array();

							foreach ($list as $ls) {
								foreach ($bill as $bl) {
									if ($ls["payment_id"] == $bl["payment_payment_id"]) {
										$mergedArray[] = array_merge($ls, $bl);
									}
								}
							}

							foreach ($mergedArray as $row) :
						?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $row['pos_name'] . ' ' . $row['period_start'] . '/' . $row['period_end']; ?></td>
									<td align="right"><?php echo 'Rp. ' . number_format($row['bulan_bill'], 0, ',', '.') ?></td>
								</tr>

							<?php
								$i++;
								$total += $row['bulan_bill'];
							endforeach;
							?>
							<tr class="success">
								<td colspan="2"><b>Total</b></td>
								<td><b><?php echo 'Rp. ' . number_format($total, 0, ',', '.') ?></b></td>
							</tr>
						<?php }  ?>

					</tbody>
				</table>
			</div>
		</div>
	</section>
</body>

</html>