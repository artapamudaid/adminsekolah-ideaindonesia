<link href="<?= site_url('assets/plugins/bootstrap-wizard/dist/css/smart_wizard_all.css') ?>" rel="stylesheet" />

<style>
    .toggle-by-type {
        display: none;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?= isset($title) ? $title : 'Assign POS' ?></h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
            <li><a href="<?= isset($redirect_url) ? $redirect_url : '#' ?>">Akun Biaya</a></li>
            <li class="active"><?= isset($title) ? $title : 'Assign' ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header">
                <div class="">
                    <a href="<?= $redirect_url ?>" class="btn btn-sm btn-default">
                        <i class="fa fa-arrow-left"></i>
                        Batal
                    </a>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <td>
                                    <strong>
                                        Akun Biaya
                                    </strong>
                                </td>
                                <td>:</td>
                                <td>
                                    <?= $account['account_code'] ?> - <?= $account['account_description'] ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php echo form_open(
                    '',
                    [
                        'id' => 'assign-form',
                        'method' => 'post'
                    ]
                ); ?>
                <input type="hidden" name="m" class="form-control" value="<?= $account['account_majors_id'] ?>" readonly>
                <div id="smartwizard">
                    <ul class="nav nav-tabs step-anchor">
                        <li><a class="nav-link" href="#step-1">Langkah 1<br /><small>Akun Pos Bayar</small></a></li>
                        <li><a class="nav-link" href="#step-2">Langkah 2<br /><small>Akun Jenis Bayar</small></a></li>
                        <li><a class="nav-link" href="#step-3">Langkah 3<br /><small>Review & Submit</small></a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="step-1" class="step-content tab-pane" role="tabpanel">
                            <h4 class="text-center"><strong>POS Bayar</strong></h4>
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="select_akun_piutang" class="col-sm-2 control-label">
                                        Akun Piutang
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="piutang_account_id" id="select_akun_piutang" class="form-control">
                                            <option value="">-Pilih Akun Piutang-</option>
                                            <?php foreach ($piutang as $row): ?>
                                                <option value="<?php echo $row->account_id ?>"><?php echo $row->account_code . ' - ' . $row->account_description ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php echo form_error('piutang_account_id') ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input_pos_name" class="col-sm-2 control-label">
                                        Nama POS
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="pos_name" id="input_pos_name" placeholder="Tulis Nama POS" class="form-control" autocomplete="off" required value="<?= $account['account_description'] ?>">
                                        <?php echo form_error('pos_name') ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input_pos_description" class="col-sm-2 control-label">
                                        Keterangan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="pos_description" id="input_pos_description" placeholder="Tulis Keterangan POS" class="form-control" autocomplete="off" required value="<?= $account['account_description'] ?>">
                                        <?php echo form_error('pos_description') ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="step-2" class="step-content tab-pane" role="tabpanel">
                            <h4 class="text-center"><strong>Jenis Bayar</strong></h4>
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="input_pos_bayar" class="col-sm-2 control-label">POS Bayar</label>
                                    <div class="col-sm-10">
                                        <input type="text" readonly class="form-control" id="input_pos_bayar" placeholder="POS Bayar">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="select_period_id" class="col-sm-2 control-label">
                                        Tahun Ajaran
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-4">
                                        <select name="period_id" class="form-control" id="select_period_id" required>
                                            <option value="">-Pilih Tahun Ajaran-</option>
                                            <?php foreach ($period as $row) : ?>
                                                <option value="<?php echo $row['period_id']; ?>"><?php echo $row['period_start'] . '/' . $row['period_end']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" id="div-type">
                                    <label for="select_payment_type" class="col-sm-2 control-label">
                                        Tipe
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-4">
                                        <!-- <div class="radio">
                                            <label>
                                                <input type="radio" name="paymentTypes" id="paymentTypes1" value="BULAN" checked="">
                                                Bulanan
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="paymentTypes" id="paymentTypes2" value="BEBAS">
                                                Bebas
                                            </label>
                                        </div> -->
                                        <select name="payment_type" id="select_payment_type" class="form-control" required>
                                            <option value="BULAN">Bulanan</option>
                                            <option value="BEBAS">Bebas</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group toggle-by-type">
                                    <label for="radio_payment_mode" class="col-sm-2 control-label">
                                        Model Pembayaran
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="paymentModes" id="paymentModes1" value="TETAP" checked="">
                                                Tetap
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="paymentModes" id="paymentModes2" value="TIDAK TETAP">
                                                Tidak Tetap
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group toggle-by-type">
                                    <label for="radio_payment_is_batch" class="col-sm-2 control-label">
                                        Apakah Item Paket Pembayaran
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <?php if ($paket['val'] == '2' || $paket['val'] == '3'): ?>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="paymentIsBatchs" id="paymentIsBatchs1" value="1" checked="">
                                                    Ya
                                                </label>
                                            </div>
                                        <?php endif; ?>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="paymentIsBatchs" id="paymentIsBatchs2" value="0">
                                                Tidak
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="step-3" class="step-content tab-pane" role="tabpanel">
                            <h4 class="text-center"><strong>Review & Simpan</strong></h4>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="box box-success">
                                        <div class="box-header">
                                            <h3 class="box-title">POS Bayar</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <td>Akun Piutang</td>
                                                            <td>:</td>
                                                            <td>
                                                                <div id="pb-ap"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Nama POS</td>
                                                            <td>:</td>
                                                            <td>
                                                                <div id="pb-nmp"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Keterangan</td>
                                                            <td>:</td>
                                                            <td>
                                                                <div id="pb-ket"></div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <div class="box box-success">
                                        <div class="box-header">
                                            <h3 class="box-title">Jenis Bayar</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <td>POS Bayar</td>
                                                            <td>:</td>
                                                            <td>
                                                                <div id="jb-pb"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tahun Ajaran</td>
                                                            <td>:</td>
                                                            <td>
                                                                <div id="jb-ta"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tipe</td>
                                                            <td>:</td>
                                                            <td>
                                                                <div id="jb-tipe"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Model Pembayaran</td>
                                                            <td>:</td>
                                                            <td>
                                                                <div id="jb-mp"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Apakah Pembayaran Item Bisa Paket?</td>
                                                            <td>:</td>
                                                            <td>
                                                                <div id="jb-isBatch"></div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="btn-submit-form" class="sw-btn" style="display: none;">
                        Simpan
                    </button>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </section>
</div>

<script src="<?= site_url('assets/plugins/bootstrap-wizard/dist/js/jquery.smartWizard.min.js') ?>"></script>

<script>
    $(document).ready(function() {
        $('#assign-form')[0].reset();
        $('#smartwizard').smartWizard({
            selected: 0,
            theme: 'arrows',
            justified: true,
            autoAdjustHeight: false,
            enableURLhash: false,
            backButtonSupport: true,
            transition: {
                animation: 'none',
                speed: 400
            },
            toolbar: {
                position: 'bottom',
                showNextButton: true,
                showPreviousButton: true
            },
            anchor: {
                enableNavigationAlways: false,
                enableDoneState: true,
                markPreviousStepsAsDone: true,
            },
            keyboard: {
                keyNavigation: false
            },
            lang: {
                next: 'Selanjutnya',
                previous: 'Sebelumnya'
            }
        });
        $('#smartwizard').smartWizard('reset');

        $("#smartwizard").on("leaveStep", function(e, anchor, currIndex, nextIndex, direction) {
            if (currIndex === 0 && direction === 'forward') {
                const piutangAccount = $('#select_akun_piutang').val();
                const posName = $('#input_pos_name').val().trim();
                const posDescription = $('#input_pos_description').val().trim();

                if (!piutangAccount) {
                    toastNotifikasi('error', 'Akun Piutang harus dipilih!', 'Gagal');
                    $('#select_akun_piutang').focus();
                    return false;
                }

                if (posName === '') {
                    toastNotifikasi('error', 'Nama POS tidak boleh kosong!', 'Gagal');
                    $('#input_pos_name').focus();
                    return false;
                }

                if (posDescription === '') {
                    toastNotifikasi('error', 'Keterangan POS tidak boleh kosong!', 'Gagal');
                    $('#input_pos_description').focus();
                    return false;
                }

                $('#input_pos_bayar').val(posName);

                const piutangAccountText = $('#select_akun_piutang option:selected').text();
                $('#pb-ap').html(piutangAccountText);
                $('#pb-nmp').html(posName);
                $('#pb-ket').html(posDescription);
            }

            return true;
        });

        $("#smartwizard").on("leaveStep", function(e, acnhor, currIndex, nextIndex, direction) {
            if (currIndex === 1 && direction === 'forward') {
                const posBayar = $('#input_pos_bayar').val().trim();
                const periodId = $('#select_period_id').val();
                const type = $('#select_payment_type').val();
                const mode = $('input[name="paymentModes"]:checked').val();
                const isBatch = $('input[name="paymentIsBatchs"]:checked').val();

                if (posBayar === '') {
                    toastNotifikasi('error', 'POS Bayar tidak boleh kosong!', 'Gagal');
                    $('#input_pos_bayar').focus();
                    return false;
                }

                if (!periodId) {
                    toastNotifikasi('error', 'Tahun Ajaran harus dipilih!', 'Gagal');
                    return false;
                }

                if (!type) {
                    toastNotifikasi('error', 'Tipe harus dipilih!', 'Gagal');
                    return false;
                }

                if (!mode) {
                    toastNotifikasi('error', 'Tipe harus dipilih!', 'Gagal');
                    return false;
                }

                if (type === 'BULAN') {
                    if (!isBatch) {
                        toastNotifikasi('error', 'Tipe harus dipilih!', 'Gagal');
                        return false;
                    }
                }

                $("#jb-pb").html(posBayar);
                const periodText = $('#select_period_id option:selected').text();
                $("#jb-ta").html(periodText);
                $("#jb-tipe").html(type);
                $("#jb-mp").html(mode);
                const textIsBatch = (isBatch == 1) ? "Ya" : "Tidak";
                $("#jb-isBatch").html(textIsBatch);
            }

            return true;
        });

        $("#smartwizard").on("showStep", function(e, anchor, stepIndex, stepDirection, stepPosition) {
            const $toolbar = $('#smartwizard').closest('.box').find('.sw-toolbar-elm');
            const $nextBtn = $toolbar.find('.sw-btn-next');
            const $submitBtn = $('#btn-submit-form');

            if (stepIndex === 2) {
                $nextBtn.hide();
                $submitBtn.detach().appendTo($toolbar);
                $submitBtn.show();
            } else {
                $nextBtn.show();
                $submitBtn.detach().insertAfter($toolbar);
                $submitBtn.hide();
            }

            if (stepIndex === 1) {
                show_form('#div-type select');
            }
        });

        function show_form(selektor) {
            if ($(selektor).val() == 'BULAN') {
                $('#paymentIsBatchs2').prop('checked', true);
                $('.toggle-by-type').fadeIn();
            } else if ($(selektor).val() == 'BEBAS') {
                $('#paymentModes1').prop('checked', true);
                $('.toggle-by-type').fadeOut();
            }
        }

        show_form('#div-type select');

        $('#div-type select').change(function() {
            show_form(this);
            let value = $(this).val();
            $("[name=payment_type]").val(value);
        });

    });
</script>