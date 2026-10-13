<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php echo isset($title) ? "" . $title : null; ?>
            <small>Hasil Pencarian</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("manage") ?>"><i class="fa fa-th"></i> Home</a></li>
            <li class="active"><?php echo isset($title) ? "" . $title : null; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <?php if (!empty($keyword)): ?>
                <div class="col-xs-12">
                    <div class="alert alert-info">
                        Menampilkan hasil pencarian untuk kata kunci: <strong><?php echo htmlspecialchars($keyword); ?></strong>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <div class="input-group">
                            <input type="text" id="global_search_input" class="form-control" placeholder="Kata kunci: [Nama] [NIS] [NIP]" value="<?php echo isset($keyword) ? htmlspecialchars($keyword) : "; "; ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="global_search_btn">
                                    <i class="fa fa-search"></i> Cari
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($keyword)): ?>

                <!-- Siswa / Student Section -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fa fa-users"></i> Siswa</h3>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NIS</th>
                                        <th>Nama Lengkap</th>
                                        <th>Unit</th>
                                        <th>Kelas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($students)): ?>
                                        <?php $i = 1;
                                        foreach ($students as $row): ?>
                                            <tr>
                                                <td class="text-center"><?php echo $i++; ?></td>
                                                <td class="text-center"><?php echo $row["student_nis"]; ?></td>
                                                <td><?php echo $row["student_full_name"]; ?></td>
                                                <td class="text-center"><?php echo isset($row["majors_short_name"]) ? $row["majors_short_name"] : "-"; ?></td>
                                                <td class="text-center"><?php echo $row["class_name"]; ?></td>
                                                <td class="text-center">
                                                    <a href="<?php echo site_url("manage/student/view/" . $row["student_id"]); ?>" title="Detail"><i class="fa fa-eye"></i> Lihat Detail</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Tidak ada data siswa yang ditemukan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pegawai / Employee Section -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fa fa-user-secret"></i> Pegawai</h3>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NIP</th>
                                        <th>Nama Lengkap</th>
                                        <th>Unit</th>
                                        <th>Jabatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($employees)): ?>
                                        <?php $i = 1;
                                        foreach ($employees as $row): ?>
                                            <tr>
                                                <td class="text-center"><?php echo $i++; ?></td>
                                                <td class="text-center"><?php echo isset($row["employee_nip"]) ? $row["employee_nip"] : "-"; ?></td>
                                                <td><?php echo $row["employee_name"]; ?></td>
                                                <td class="text-center"><?php echo isset($row["majors_short_name"]) ? $row["majors_short_name"] : "-"; ?></td>
                                                <td><?php echo isset($row["position_name"]) ? $row["position_name"] : "-"; ?></td>
                                                <td class="text-center">
                                                    <a href="<?php echo site_url("manage/employees/view/" . $row["employee_id"]); ?>" title="Detail"><i class="fa fa-eye"></i> Lihat Detail</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Tidak ada data pegawai yang ditemukan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pembayaran Siswa Section -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fa fa-credit-card"></i> Pembayaran Siswa</h3>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NIS</th>
                                        <th>Nama Lengkap</th>
                                        <th>Unit</th>
                                        <th>Kelas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($payments)): ?>
                                        <?php $i = 1;
                                        foreach ($payments as $row): ?>
                                            <tr>
                                                <td class="text-center"><?php echo $i++; ?></td>
                                                <td class="text-center"><?php echo $row["student_nis"]; ?></td>
                                                <td><?php echo $row["student_full_name"]; ?></td>
                                                <td class="text-center"><?php echo isset($row["majors_short_name"]) ? $row["majors_short_name"] : "-"; ?></td>
                                                <td class="text-center"><?php echo $row["class_name"]; ?></td>
                                                <td class="text-center">
                                                    <a href="<?php echo site_url("manage/payout?n=" . (isset($periodActive["period_id"]) ? $periodActive["period_id"] : "") . "&r=" . $row["student_nis"]); ?>" title="Detail"><i class="fa fa-eye"></i> Lihat Detail</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Tidak ada data pembayaran siswa yang ditemukan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-xs-12">
                    <div class="alert alert-warning text-center">
                        Silakan masukkan kata kunci pada kolom di atas (Nama, NIS, atau NIP) untuk mulai mencari.
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        function doSearch() {
            var keyword = $("#global_search_input").val().trim();
            if (keyword !== "") {
                window.location.href = "<?php echo site_url("manage/pencarian"); ?>?q=" + encodeURIComponent(keyword);
            }
        }

        $("#global_search_btn").on("click", function() {
            doSearch();
        });

        $("#global_search_input").on("keypress", function(e) {
            if (e.which === 13) {
                doSearch();
            }
        });
    });
</script>