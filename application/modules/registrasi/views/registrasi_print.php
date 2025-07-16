<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo 'Pendaftaran Peneliti Nama '.$researcher_list['researcher_name'] ?></title>
</head>
<body>
    <?php
        $this->load->library('ciqrcode');
        
        //header("Content-Type: image/png");
        
        $params['data']     = 'Username: '.$researcher_list['researcher_email'].' | Password: '.$researcher_list['researcher_password'];
        $params['level']    = 'H';
        $params['size']     = 4;
        $params['savename'] = FCPATH.'uploads/researcher_qrcode/'.$researcher_list['researcher_password'].'.png';
        
        $this->ciqrcode->generate($params);
    ?>
    <table cellpadding='0' border='0' cellspacing='0' style='width: 190mm;'>
		<tr>
			<td align='left' style='font-size: 12pt; width: 190mm; padding: 5px 0px 5px 0px;'><b>Pendaftaran Pengusul</b></td>
		</tr>
	</table>
	<hr>
	<br>
	<table cellpadding='0' cellspacing='0' style='width: 190mm;'>
		<tr>
			<td align='left' style='font-size: 10pt; width: 50mm; padding: 5px 0px 5px 0px;'>Nama</td>
			<td align='center' style='font-size: 10pt; width: 5mm; padding: 5px 0px 5px 0px;'> : </td>
			<td align='left' style='font-size: 10pt; width: 70mm; padding: 5px 0px 5px 0px;'><?php echo $researcher_list['researcher_name'] ?></td>
			<td rowspan='12' align='right' valign='top' style='font-size: 10pt; width: 65mm; padding: 5px 0px 5px 0px;'>
			    <img src="<?php echo base_url().'uploads/researcher_qrcode/'.$researcher_list['researcher_password'].'.png' ?>" >
			</td>
		</tr>
		<tr>
			<td align='left' style='font-size: 10pt; width: 50mm; padding: 5px 0px 5px 0px;'>NIK KTP</td>
			<td align='center' style='font-size: 10pt; width: 5mm; padding: 5px 0px 5px 0px;'> : </td>
			<td align='left' style='font-size: 10pt; width: 70mm; padding: 5px 0px 5px 0px;'><?php echo $researcher_list['researcher_nik'] ?></td>
		</tr>
		<tr>
			<td align='left' style='font-size: 10pt; width: 50mm; padding: 5px 0px 5px 0px;'>Alamat</td>
			<td align='center' style='font-size: 10pt; width: 5mm; padding: 5px 0px 5px 0px;'> : </td>
			<td align='left' style='font-size: 10pt; width: 70mm; padding: 5px 0px 5px 0px;'><?php echo $researcher_list['researcher_address'] ?></td>
		</tr>
		<tr>
			<td align='left' style='font-size: 10pt; width: 50mm; padding: 5px 0px 5px 0px;'>Kota/Kabupaten</td>
			<td align='center' style='font-size: 10pt; width: 5mm; padding: 5px 0px 5px 0px;'> : </td>
			<td align='left' style='font-size: 10pt; width: 70mm; padding: 5px 0px 5px 0px;'><?php echo ucwords(strtolower($researcher_list['city_name'])) ?></td>
		</tr>
		<tr>
			<td align='left' style='font-size: 10pt; width: 50mm; padding: 5px 0px 5px 0px;'>Provinsi</td>
			<td align='center' style='font-size: 10pt; width: 5mm; padding: 5px 0px 5px 0px;'> : </td>
			<td align='left' style='font-size: 10pt; width: 70mm; padding: 5px 0px 5px 0px;'><?php echo $researcher_list['province_name'] ?></td>
		</tr>
		<tr>
			<td align='left' style='font-size: 10pt; width: 50mm; padding: 5px 0px 5px 0px;'>KEPK</td>
			<td align='center' style='font-size: 10pt; width: 5mm; padding: 5px 0px 5px 0px;'> : </td>
			<td align='left' style='font-size: 10pt; width: 70mm; padding: 5px 0px 5px 0px;'><?php echo $researcher_list['kepk_name'] ?></td>
		</tr>
		<tr>
			<td align='left' style='font-size: 10pt; width: 50mm; padding: 5px 0px 5px 0px;'>Prodi</td>
			<td align='center' style='font-size: 10pt; width: 5mm; padding: 5px 0px 5px 0px;'> : </td>
			<td align='left' style='font-size: 10pt; width: 70mm; padding: 5px 0px 5px 0px;'><?php echo $researcher_list['prodi_nama'] ?></td>
		</tr>
		<tr>
			<td align='left' style='font-size: 10pt; width: 50mm; padding: 5px 0px 5px 0px;'>Nomor Telepon</td>
			<td align='center' style='font-size: 10pt; width: 5mm; padding: 5px 0px 5px 0px;'> : </td>
			<td align='left' style='font-size: 10pt; width: 70mm; padding: 5px 0px 5px 0px;'><?php echo $researcher_list['researcher_phone'] ?></td>
		</tr>
		<tr>
			<td align='left' style='font-size: 10pt; width: 50mm; padding: 5px 0px 5px 0px;'>Email</td>
			<td align='center' style='font-size: 10pt; width: 5mm; padding: 5px 0px 5px 0px;'> : </td>
			<td align='left' style='font-size: 10pt; width: 70mm; padding: 5px 0px 5px 0px;'><?php echo $researcher_list['researcher_email'] ?></td>
		</tr>
		<tr>
			<td align='left' style='font-size: 10pt; width: 50mm; padding: 5px 0px 5px 0px;'>Username</td>
			<td align='center' style='font-size: 10pt; width: 5mm; padding: 5px 0px 5px 0px;'> : </td>
			<td align='left' style='font-size: 10pt; width: 70mm; padding: 5px 0px 5px 0px;'><?php echo $researcher_list['researcher_username'] ?></td>
		</tr>
		<tr>
			<td align='left' style='font-size: 10pt; width: 50mm; padding: 5px 0px 5px 0px;'>Password (*Segera ganti password setelah login)</td>
			<td align='center' style='font-size: 10pt; width: 5mm; padding: 5px 0px 5px 0px;'> : </td>
			<td align='left' style='font-size: 10pt; width: 70mm; padding: 5px 0px 5px 0px;'><?php echo $researcher_list['researcher_password'] ?></td>
		</tr>
	</table>
</body>
</html>