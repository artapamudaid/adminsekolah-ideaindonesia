<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="box box-primary">
            <div class="box-body">

                <div id="habitForm">
                    <div class="row">
                       <?php foreach ($habit as $h) : ?>
                           <div class="col-md-12" style="margin-bottom:10px; color:#fff;">
                               <a href="<?= site_url('student/anakhebat/input/' . $h['id']) ?>" class="btn-success btn-lg btn-block" style="font-weight: bold;"><?= $h['name'] ?></a>
                           </div>
                       <?php endforeach; ?>
                    </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>