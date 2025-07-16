<style type="text/css">
    .body {
        padding: 10px 10px 10px;
    }

    p {
        text-align: justify;
    }

    #wrap {
        width: auto;
        position: relative;
    }

    .left,
    .right {
        width: 50%;
        float: left;
    }

    .clearBoth {
        clear: both;
    }

    a {
        font-family: "calibri";
    }

    .center {
        text-align: center;
    }

    .header {
        font-size: 20px;
        font-weight: bold;
    }

    .child {
        text-align: center;
        font-size: 12px;
        font-weight: normal;

    }

    .head-images {
        width: 100px;
        height: 100px;
        float: left;

    }

    .child {
        text-align: center;
        font-weight: normal;

    }

    .cstable td {
        border: 0.6px solid black;
        text-align: center
    }

    .cstable th {
        border: 0.1px solid black;
    }

    .cstable {
        border-collapse: collapse;
        width: 100%;
        font-size: 11px;
    }
</style>

<div class="body">
    <table>
        <tr>
            <td>
                <?php
                $logo = (file_exists("./assets/img/" . logo())) ? "assets/img/" . logo() : "assets/img/no_image.png";

                ?>
                <img src="<?= base_url($logo) ?>" class="head-images">
            </td>
            <td>
                <center>
                    <div class="header"><?= strip_tags(strtoupper(identitas('nama_instansi'))) ?>
                    </div>
                    <br />
                    <div class="child">
                        <?= strip_tags(strtoupper(identitas('alamat_lengkap'))) ?> <br />
                        <?= 'Tepl : ' . strip_tags(strtoupper(identitas('telp'))) . ', Fax : ' . strip_tags(strtoupper(identitas('fax'))) ?>
                    </div>
                </center>
            </td>
        </tr>
    </table>
    <hr />
    <div class="child" style="font-size:18px">
        PERINTAH PERJALANAN DINAS <br />
        <b> (SPPD) </b>
    </div>
    <br />


   <table>
            <tr>
                <td>Lembar Ke</td>
                <td>:</td>
                <td>1</td>
            </tr>
            <tr>
                <td>Kode No</td>
                <td>:</td>
                <td><?= $sppd['code'] ?></td>
            </tr>
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td></td>
            </tr>
        </table>
    <br />
    <br />
    
    <br />
    <br />
    
    <br />
    <br />
    <table>

        <tr>
            <td>1. Pejabat yang memberi perintah .</td>
            <td>:</td>
            <td><?= $sppd['pimpinan'] ?></td>
        </tr>

        <tr>
            <td>2. Nama Pegawai Yang Di Perintah </td>
            <td>:</td> 
            <td><?= $sppd['pengikut'] ?></td>
        </tr>
        <tr>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;a. Pangkat dan Golongan</td>
            <td>:</td>
            <td><?= $sppd['jabatan_pimpinan'] ?> / <?= $this->properti->golongan($sppd['golongan_pimpinan']) ?></td>
            
        </tr>
        <tr>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;b. Jabatan</td>
            <td>:</td>
            <td><?= $sppd['jabatan_pengikut'] ?></td>
        </tr>
        <tr>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;c. Tingkat menurut peraturan perjalanan</td>
            <td>:</td>
        </tr>
        <tr>
            <td>4. Maksud Perjalanan Dinas</td>
            <td>:</td>
            <td><?= $sppd['purpose'] ?></td>
        </tr>
        <tr>
            <td>5. Alat yang dipergunakan</td>
            <td>:</td>
            <td><?= $sppd['code'] ?></td>
        </tr>
        <tr>
            <td>6. a. Tempat Berangkat</td>
            <td>:</td>
            <td><?= $sppd['code'] ?></td>
        </tr>

        <tr>
            <td>6. b. Tempat Tujuan</td>
            <td>:</td>
            <td><?= $sppd['place_to'] ?></td>

        </tr>

        <tr>
            <td>7. Lamanya Perjalanan Dinas</td>
            <td>:</td>
            <td><?= $sppd['length_journey'] ?> / Hari</td>
        </tr>

        <tr>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;b. Tanggal Berangkat</td>
            <td>:</td>
            <td><?= tgl_indonesia($sppd['date_go']) ?></td>
        </tr>

        <tr>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;b. Tanggal Harus Kembali</td>
            <td>:</td>
            <td><?= tgl_indonesia($sppd['date_back']) ?></td>

        </tr>
        <tr>
            <td>8. Pengikut</td>
            <td>:</td>
            <td></td>
        </tr>

        <tr>
            <td>9. Pembebanan Anggaran</td>
            <td>:</td>
            <td></td>
        </tr>
        <tr>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;a. Instansi</td>
            <td>:</td>
            <td><?= $sppd['government'] ?></td>
        </tr>
        <tr>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;a. Mata Anggaran</td>
            <td>:</td>
            <td>Ada</td>
        </tr>
        <tr>
            <td>10. Keterangan Lain-Lain</td>
            <td>:</td>
            <td><?= $sppd['description'] ?></td>

        </tr>
    </table>

    <div style="float: right; width:  50%;text-align: center;">
        <br />
        Dikeluarkan di Pada Tanggal
        , <?= (identitas('alamat')) ? strip_tags(strtoupper(identitas('alamat'))) : 'Alamat Kosong'; ?>
        : <?= tgl_indonesia(date('Y-m-d')) ?>
        <br /><br />
        -
        An. <?= strip_tags(strtoupper(identitas('jabatan'))); ?>

        <br /><br /> <br /><br />

        <?= strip_tags(strtoupper(identitas('nama_pejabat'))); ?> <br />
        <?= strip_tags(strtoupper(identitas('nip'))); ?>

    </div>


    <pagebreak></pagebreak>
    <table>
        <tr>
            <td>
                <?php
                $logo = (file_exists("./assets/img/" . logo())) ? "assets/img/" . logo() : "assets/img/no_image.png";

                ?>
                <img src="<?= base_url($logo) ?>" class="head-images">
            </td>
            <td>
                <center>
                    <div class="header"><?= strip_tags(strtoupper(identitas('nama_instansi'))) ?>
                    </div>
                    <br />

                    <div class="child">
                        <?= strip_tags(strtoupper(identitas('alamat_lengkap'))) ?> <br />
                        <?= 'Tepl : ' . strip_tags(strtoupper(identitas('telp'))) . ', Fax : ' . strip_tags(strtoupper(identitas('fax'))) ?>
                    </div>
                </center>
            </td>
        </tr>
    </table>
    <hr />


    <div class="child" style="font-size:18px">
        SURAT PERINTAH TUGAS <br /> 
    </div>
    <b>NOMOR : <?= $sppd['code'] ?> </b>
    <br />


    <table>
        <tr>
            <td>Dasar </td>
            <td>:</td>
            <td></td>
        </tr>
    </table>
    <br />
    <br />
    <br />


    <div class="center">
       <b>  MEMERINTAHKAN </b>
    </div>
    <br /><br />
    <table>
        <tr>
            <td>
                Kepada :
            </td>
            <td>

            <?php 
              $no       = 1;
              $sc       = $this->properti->parsing($sppd['nip']);
              $pengikut = $this->Pegawai_model->getPengikut($sc);
                  foreach($pengikut->result_array() as $listp){ 
            ?>
                <table style="margin-left: 30px;">
                    <tr>
                        <td><?= $no ?>. Nama</td>
                        <td>:</td>
                        <td><?= $listp['nama'] ?></td>
                    </tr>
                    <tr>
                        <td>
                            Pangkat / gol</td>
                        <td> :</td>
                        <td><?= $listp['jabatan'] ?> 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            NIP </td>
                        <td>:</td>
                        <td><?= $listp['nip'] ?></td>
                    </tr>
                    <tr>
                        <td>
                            Jabatan </td>
                        <td>:</td>
                        <td><?= $listp['jabatan'] ?></td>
                    </tr>  
                </table>
                  <?php $no++; } ?>    
            </td>
        </tr>

    </table>

    <table>
        <tr>
            <td>
                Untuk </td>
            <td style="
    padding: 14px;">:</td>
            <td style="
    padding: 14px;"> <?= $sppd['purpose'] ?></td>
        </tr>
    </table>
    <br />
    <br />

    <br />
    <br />
    <div style="float: right; width:  50%;text-align: center;">
        <br />
        Dikeluarkan di Pada Tanggal
        : <?= strip_tags(strtoupper(identitas('alamat'))); ?>
        : <?= tgl_indonesia(date('Y-m-d')) ?>
        <br /><br />
        -
        An. <?= strip_tags(strtoupper(identitas('jabatan'))); ?> 
        <br /><br /> <br /><br /> 
        <?= strip_tags(strtoupper(identitas('nama_pejabat'))); ?> <br />
        <?= strip_tags(strtoupper(identitas('nip'))); ?>

    </div>
</div>
</div>