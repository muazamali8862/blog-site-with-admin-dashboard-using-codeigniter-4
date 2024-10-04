<?php echo $this->extend('backend/layouts/pages-layout'); ?>
<?php echo $this->section('content'); ?>

<div class="card-box pd-20 height-100-p mb-30">
    <div class="row align-items-center">
        <div class="col-md-4">
            <img src="/backend/vendors/images/banner-img.png" alt="">
        </div>
        <div class="col-md-8">
            <?php
            $visit_count = session()->get('visit_count');
            if (!$visit_count) {
                session()->set('visit_count', 1);
                $message = 'Welcome';
            } else {
                $message = 'Welcome back';
            }
            ?>
            <h4 class="font-20 weight-500 mb-10 text-capitalize">
                <?= $message ?>
                <div class="weight-600 font-30 text-blue"><?= get_user()->name ?></div>
            </h4>
            <p class="font-18 max-width-600">
                Thanks for being part of our community. We are excited to have you on board.
            </p>
        </div>
    </div>
</div>

<?php echo $this->endSection() ?>