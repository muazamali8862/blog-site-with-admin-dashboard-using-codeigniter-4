<?php echo $this->extend('backend/layouts/auth-layout'); ?>
<?php echo $this->section('content'); ?>

<div
    class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-7">
                <img src="/backend/vendors/images/register-page-img.png" alt="" />
            </div>
            <div class="col-md-6 col-lg-5">

                <div class="login-box bg-white box-shadow border-radius-10">
                    <div class="login-title">
                        <h2 class="text-center text-primary">Registration</h2>
                    </div>
                    <?php $validation =  \Config\Services::validation(); ?>

                    <form action="<?= route_to('admin.register') ?>" method="post">
                        <?= csrf_field(); ?>
                        <?php if (!empty(session()->getFlashdata('success'))): ?>
                            <div class="alert alert-success">
                                <?= session()->getFlashdata('success'); ?>
                                <button class="close" type="button" data-dismiss="alert" aria-label="Close">

                                    <span aria-hidden="true">&times;</span>
                                </button>

                            </div>
                        <?php endif; ?>

                        <?php if (!empty(session()->getFlashdata('error'))): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error'); ?>
                                <button class="close" type="button" data-dismiss="alert" aria-label="Close">

                                    <span aria-hidden="true">&times;</span>
                                </button>

                            </div>
                        <?php endif; ?>
                        <div class="input-group custom">
                            <input type="text" class="form-control form-control-lg" placeholder="Name" name="name" value="<?= set_value('name'); ?>">
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                            </div>
                        </div>
                        <?php if ($validation->getErrors('name')): ?>
                            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px">
                                <?= $validation->getError('name'); ?>

                            </div>
                        <?php endif; ?>
                        <div class="input-group custom">
                            <input type="text" class="form-control form-control-lg" placeholder="Username" name="username" value="<?= set_value('username'); ?>">
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                            </div>
                        </div>
                        <?php if ($validation->getErrors('username')): ?>
                            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px">
                                <?= $validation->getError('username'); ?>

                            </div>
                        <?php endif; ?>
                        <div class="input-group custom">
                            <input type="text" class="form-control form-control-lg" placeholder="Email" name="email" value="<?= set_value('email'); ?>">
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="micon dw dw-email"></i></span>
                            </div>
                        </div>
                        <?php if ($validation->getErrors('email')): ?>
                            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px">
                                <?= $validation->getError('email'); ?>

                            </div>
                        <?php endif; ?>
                        <div class="input-group custom">
                            <input type="password" class="form-control form-control-lg" placeholder="**********" name="password" value="<?= set_value('password'); ?>">
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                            </div>
                        </div>
                        <?php if ($validation->getErrors('password')): ?>
                            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px">
                                <?= $validation->getError('password'); ?>

                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="input-group mb-0">
                                    <input type="submit" class="btn btn-primary btn-lg btn-block" value="Register">

                                </div>
                                <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373" style="color: rgb(112, 115, 115);">
                                    OR
                                </div>
                                <div class="input-group mb-0">
                                    <a class="btn btn-outline-primary btn-lg btn-block" href="<?= route_to('admin.login.form') ?>">Already Have an Account? Login</a>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<?php echo $this->endSection(); ?>