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
                <div class="box box-success">
                    <div class="box-header with-border">
                        <div class="box-tools">
                            <?php echo form_open(current_url(), array('class' => 'form-inline', 'method' => 'get')); ?>
                            <select name="m" class="form-control input-sm" onchange="this.form.submit()">
                                <option value="all">Semua Unit</option>
                                <?php foreach ($majors as $row): ?>
                                    <option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : ''; ?>>
                                        <?php echo $row['majors_short_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr style="background-color: #f4f4f4;">
                                    <th>Kode Akun</th>
                                    <th>Nama Akun</th>
                                    <th>Unit</th>
                                    <th>Tipe</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th style="min-width: 180px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($groups)):
                                    foreach ($groups as $g):
                                        // Cek apakah ada COA di grup ini
                                        $has_coa = array_filter($coa, function ($c) use ($g) {
                                            return $c['group_id'] == $g['group_id']; });
                                        ?>
                                        <tr style="font-weight: bold; background-color: #e8f5e9;">
                                            <td colspan="6"><?php echo $g['group_id'] . ' - ' . $g['group_name']; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-xs" data-toggle="modal"
                                                    data-target="#modalAddCoa"
                                                    onclick="addForm(<?php echo $g['group_id']; ?>, 0, 'Menambah Akun untuk Group: <?php echo htmlspecialchars($g['group_name']); ?>')"><i
                                                        class="fa fa-plus"></i> Tambah Akun</button>
                                            </td>
                                        </tr>

                                        <?php
                                        if (!empty($has_coa)):
                                            foreach ($coa as $row):
                                                if ($row['group_id'] == $g['group_id']):
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $row['indented'] . $row['coa_code']; ?></td>
                                                        <td><?php echo $row['coa_name']; ?></td>
                                                        <td><?php echo $row['majors_short_name']; ?></td>
                                                        <td><?php echo $row['coa_type']; ?></td>
                                                        <td><?php echo ($row['coa_category'] == 'HEADER') ? '<b>HEADER</b>' : 'DETAIL'; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo ($row['is_active'] == 1) ? '<span class="label label-success">Aktif</span>' : '<span class="label label-danger">Non-Aktif</span>'; ?>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal"
                                                                data-target="#modalAddCoa"
                                                                onclick="addForm(<?php echo $row['group_id']; ?>, <?php echo $row['coa_id']; ?>, 'Menambah Sub-Akun untuk: <?php echo htmlspecialchars($row['coa_name']); ?>')"><i
                                                                    class="fa fa-plus"></i> Tambah Child</button>

                                                            <button type="button" class="btn btn-warning btn-xs" data-toggle="modal"
                                                                data-target="#modalAddCoa"
                                                                onclick="editForm(<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>)"><i
                                                                    class="fa fa-edit"></i> Edit</button>

                                                            <a href="#modalDelete" data-toggle="modal"
                                                                onclick="$('#modalDelete #delReq').attr('action', '<?php echo site_url('manage/coa/delete/' . $row['coa_id']); ?>');"
                                                                class="btn btn-danger btn-xs"><i class="fa fa-trash" data-toggle="tooltip"
                                                                    title="Hapus"></i></a>
                                                        </td>
                                                    </tr>
                                                <?php
                                                endif;
                                            endforeach;
                                        else:
                                            ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">Belum ada akun di grup ini.</td>
                                            </tr>
                                            <?php
                                        endif;
                                    endforeach;
                                else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Data kosong</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>

<!-- Modal Add/Edit -->
<div class="modal fade" id="modalAddCoa" tabindex="-1" role="dialog" aria-labelledby="modalAddCoaLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open('manage/coa/add', array('id' => 'formCoa')); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalAddCoaLabel">Tambah Akun COA</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="coa_id" id="coa_id">
                <input type="hidden" name="parent_id" id="parent_id" value="0">
                <input type="hidden" name="m_redirect" value="<?php echo isset($s['m']) ? $s['m'] : 'all'; ?>">

                <div class="alert alert-info" id="context_info" style="display:none;"></div>

                <div class="form-group">
                    <label>Group Account <span class="text-danger">*</span></label>
                    <select name="group_id" id="group_id" class="form-control" required>
                        <option value="">- Pilih Group -</option>
                        <?php foreach ($groups as $row): ?>
                            <option value="<?php echo $row['group_id']; ?>"
                                data-name="<?php echo strtolower($row['group_name']); ?>">
                                <?php echo $row['group_id'] . ' - ' . $row['group_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Unit (Majors) <span class="text-danger">*</span></label>
                    <select name="coa_majors" id="coa_majors" class="form-control" required>
                        <option value="">- Pilih Unit -</option>
                        <?php foreach ($majors as $row): ?>
                            <option value="<?php echo $row['majors_id']; ?>"><?php echo $row['majors_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kode Akun (Otomatis)</label>
                    <input type="text" name="coa_code_readonly" id="coa_code_readonly" class="form-control" readonly
                        placeholder="Pilih Unit">
                </div>

                <div class="form-group">
                    <label>Nama Akun <span class="text-danger">*</span></label>
                    <input type="text" name="coa_name" id="coa_name" class="form-control" required
                        placeholder="Masukkan Nama Akun">
                </div>

                <div class="form-group">
                    <label>Normal Balance (Tipe) <span class="text-danger">*</span></label>
                    <select name="coa_type" id="coa_type" class="form-control" required>
                        <option value="DEBET">DEBET</option>
                        <option value="KREDIT">KREDIT</option>
                    </select>
                </div>

                <div class="form-group" id="group_is_active" style="display:none;">
                    <label>Status Akun</label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked> Aktif
                        </label>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="delReq" method="post" action="">
                <input type="hidden" name="m_redirect" value="<?php echo isset($s['m']) ? $s['m'] : 'all'; ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalDeleteLabel">Konfirmasi Hapus</h4>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus akun ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function addForm(group_id, parent_id, context_text) {
        $('#formCoa')[0].reset();
        $('#coa_id').val('');
        $('#parent_id').val(parent_id);
        $('#modalAddCoaLabel').text('Tambah Akun COA');

        $('#context_info').html('<b>' + context_text + '</b>').show();
        $('#group_is_active').hide();
        $('#coa_code_readonly').val('');

        // Pre-fill and lock group_id, trigger change to update Normal Balance
        $('#group_id').val(group_id).prop('disabled', true).trigger('change');

        // Disable unit if Unit filter is active
        <?php if (isset($s['m']) && $s['m'] != 'all'): ?>
            $('#coa_majors').val('<?php echo $s['m']; ?>').trigger('change');
        <?php else: ?>
            $('#coa_majors').prop('disabled', false).val('').trigger('change');
        <?php endif; ?>
    }

    function editForm(data) {
        $('#formCoa')[0].reset();
        $('#context_info').hide();
        $('#modalAddCoaLabel').text('Sunting Akun COA');

        $('#coa_id').val(data.coa_id);
        $('#parent_id').val(data.parent_id);

        $('#group_id').val(data.group_id).prop('disabled', true);
        $('#coa_majors').val(data.coa_majors).prop('disabled', true);

        $('#coa_code_readonly').val(data.coa_code);
        $('#coa_name').val(data.coa_name);
        $('#coa_type').val(data.coa_type);

        $('#group_is_active').show();
        if (data.is_active == 1) {
            $('#is_active').prop('checked', true);
        } else {
            $('#is_active').prop('checked', false);
        }
    }

    // Append hidden fields when form is submitted if disabled
    $('#formCoa').submit(function () {
        if ($('#group_id').prop('disabled')) {
            $('<input />').attr('type', 'hidden')
                .attr('name', 'group_id')
                .attr('value', $('#group_id').val())
                .appendTo('#formCoa');
        }
        if ($('#coa_majors').prop('disabled')) {
            $('<input />').attr('type', 'hidden')
                .attr('name', 'coa_majors')
                .attr('value', $('#coa_majors').val())
                .appendTo('#formCoa');
        }

        // Also ensure disabled coa_type can be submitted if we disabled it
        if ($('#coa_type').prop('disabled')) {
            $('<input />').attr('type', 'hidden')
                .attr('name', 'coa_type')
                .attr('value', $('#coa_type').val())
                .appendTo('#formCoa');
        }
        return true;
    });

    $(document).ready(function () {
        $('#group_id').on('change', function () {
            var selectedOption = $(this).find('option:selected');
            var groupName = selectedOption.data('name');

            if (groupName) {
                if (groupName.includes('beban')) {
                    $('#coa_type').val('DEBET');
                } else if (groupName.includes('aset neto')) {
                    $('#coa_type').val('KREDIT');
                } else if (groupName.includes('aset')) {
                    $('#coa_type').val('DEBET');
                } else if (groupName.includes('liabilitas') || groupName.includes('kewajiban')) {
                    $('#coa_type').val('KREDIT');
                } else if (groupName.includes('pendapatan') || groupName.includes('penghasilan')) {
                    $('#coa_type').val('KREDIT');
                }
            }
        });

        $('#coa_majors, #group_id').on('change', function () {
            var majors_id = $('#coa_majors').val();
            var group_id = $('#group_id').val();
            var coa_id = $('#coa_id').val();
            var parent_id = $('#parent_id').val();

            // Only generate code if it's an Add operation (coa_id is empty)
            if (majors_id && group_id && coa_id === '') {
                $.ajax({
                    url: '<?php echo site_url("manage/coa/get_proposed_code"); ?>',
                    type: 'POST',
                    data: {
                        majors_id: majors_id,
                        group_id: group_id,
                        parent_id: parent_id
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status == 'success') {
                            $('#coa_code_readonly').val(response.code);
                        }
                    }
                });
            } else if (coa_id === '') {
                $('#coa_code_readonly').val('');
            }
        });
    });
</script>