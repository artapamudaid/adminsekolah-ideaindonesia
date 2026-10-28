<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="box box-primary">
            <div class="box-body">

                <div id="habitForm">
                    <div class="row">
                            <div class="col-md-12" style="margin-bottom:20px;">
                                <div class="habit-card">
                                    <h4 class="habit-title"><?= $habit['name'] ?></h4>

                                    <!-- Form dinamis -->
                                    <form action="<?= site_url('student/anakhebat/save') ?>" method="post" enctype="multipart/form-data">
                                        <!-- hidden input -->
                                        <input type="hidden" name="id" value="<?= $habit['id'] ?>">

                                        <?php if ($habit['is_checkbox'] == 1 && !empty($habit['description'])): ?>
                                            <div class="form-group">
                                                <label>Pilihan:</label><br>
                                                <?php foreach (explode(',', $habit['description']) as $item): ?>
                                                    <?php $item = trim($item); ?>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" name="description[]" value="<?= htmlspecialchars($item) ?>">
                                                        <?= htmlspecialchars($item) ?>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($habit['use_photo'] == 1): ?>
                                            <div class="form-group">
                                                <label>Upload Foto:</label>
                                                <input type="file" name="photo" class="form-control" accept="image/*">
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($habit['use_time'] == 1): ?>
                                            <div class="form-group">
                                                <label>Pilih Waktu:</label>
                                                <input type="time" name="time" class="form-control">
                                            </div>
                                        <?php endif; ?>

                                        <button type="submit" class="btn btn-success btn-block" style="margin-top:10px;">
                                            Simpan
                                        </button>
                                        <a href="<?= site_url('student/anakhebat') ?>" class="btn btn-default btn-block" style="margin-top: 10px;">Batal</a>
                                    </form>
                                </div>
                            </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

