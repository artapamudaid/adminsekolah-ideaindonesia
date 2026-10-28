<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php echo isset($title) ? '' . $title : null; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
            <li><a href="<?php echo $redirect_url ?>">Akun Biaya</a></li>
            <li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        <h4>Petunjuk Singkat</h4>
                        <p>
                            Import data Akun dapat dilakukan dengan mengunggah file Microsoft Excel (.xlsx). Pastikan format file sesuai dengan kebutuhan aplikasi. Silakan unduh templatenya <a href="<?= site_url('manage/account/download?m=' . $s) ?>">
                                <span class="label label-success">disini</span>
                            </a>
                        </p>
                        <br>
                        <strong>CATATAN:</strong>
                        <ol>
                            <li>Pilih <strong>Template</strong> sesuai <strong>Unit Sekolah</strong> yang dipilih.</li>
                            <li>Lakukan proses import secara <strong>bertahap</strong>.
                                <ol type="i">
                                    <li>Mulailah dengan menginput <strong>anak akun dari Akun Header</strong> (akan menjadi <strong>Sub Akun 1</strong>).</li>
                                    <li>Setelah itu, input kembali <strong>anak akun dari Sub Akun 1</strong> (menjadi <strong>Sub Akun 2</strong>),</li>
                                    <li>dilanjutkan dengan <strong>anak akun dari Sub Akun 2</strong> (menjadi <strong>Sub Akun 3</strong>),</li>
                                    <li>hingga <strong>anak akun dari Sub Akun 3</strong> (menjadi <strong>Sub Akun 4</strong>).</li>
                                </ol>
                            </li>
                            <li>Sistem membatasi struktur akun hanya sampai <strong>Sub Akun 4</strong>.</li>
                            <li><strong>Sub Akun 4</strong> (anak akun dari <strong>Sub Akun 3</strong>) tidak boleh memiliki anak akun.</li>
                            <li>Masukkan seluruh data yang akan di-upload pada sheet <strong>INPUT_DATA</strong>.</li>
                            <li>Jangan ubah data pada sheet <strong>MASTER_COA</strong> dan <strong>MASTER_CATEGORY</strong> karena digunakan sebagai referensi sistem.</li>
                        </ol>
                        <hr>
                    </div>

                    <div class="col-xs-12">
                        <div class="box-body">
                            <?= form_open_multipart('manage/account/do_import?m=' . $s) ?>
                            <div class="form-group">
                                <label for="file">Masukkan File (.xlsx)</label>
                                <input type="file" name="file" accept=".xlsx" required="">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-sm btn-flat">Import</button>
                                <a href="<?php echo site_url('manage/account') ?>" class="btn btn-info btn-sm btn-flat">Kembali</a>
                            </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>