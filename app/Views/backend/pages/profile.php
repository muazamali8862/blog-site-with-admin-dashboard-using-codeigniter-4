<?php echo $this->extend('backend/layouts/pages-layout'); ?>
<?php echo $this->section('content'); ?>

<div class="min-height-200px">
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Profile</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= route_to('admin.home') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Profile
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
            <div class="pd-20 card-box height-100-p">
                <div class="profile-photo">
                    <img src="<?= get_user()->picture == null ? '/images/users/avatar.png' : '/images/users/' . get_user()->picture ?>" alt="" class="avatar-photo ci-avatar-photo" style="background-size: cover;height: 100%; width: 100%;">

                </div>
                <h5 class="text-center h5 mb-0 ci-user-name"><?= get_user()->name ?></h5>
                <p class="text-center text-muted font-14 ci-user-email">
                    <?= get_user()->email ?>
                </p>

            </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
            <div class="card-box height-100-p overflow-hidden">
                <div class="profile-tab height-100-p">
                    <div class="tab height-100-p">
                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#personal_details" role="tab">Personal Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#change_password" role="tab">Change Password</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Timeline Tab start -->
                            <div class="tab-pane fade show active" id="personal_details" role="tabpanel">
                                <div class="pd-20">
                                    <?php $validation =  \Config\Services::validation(); ?>
                                    <form action="<?= route_to('update-personal-details') ?>" method="post" enctype="multipart/form-data">
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
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Name:</label>
                                                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter full  name" value="<?= get_user()->name ?>">
                                                    <!-- <span class="text-danger error-text name-error"></span> -->
                                                </div>
                                                <?php if ($validation->getErrors('name')): ?>
                                                    <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px">
                                                        <?= $validation->getError('name'); ?>

                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="uname">Username:</label>
                                                    <input type="text" name="username" id="uname" class="form-control" placeholder="Enter username" value="<?= get_user()->username ?>">
                                                    <!-- <span class="text-danger error-text username-error"></span> -->
                                                </div>
                                                <?php if ($validation->getErrors('username')): ?>
                                                    <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px">
                                                        <?= $validation->getError('username'); ?>

                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Import Image</label>
                                            <input type="file" name="picture" class="form-control" value="<?= get_user()->picture ?>" class="form-control">
                                        </div>
                                        <?php if ($validation->getErrors('picture')): ?>
                                            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px">
                                                <?= $validation->getError('picture'); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label for="bio">Bio</label>
                                            <textarea name="bio" id="bio" cols="30" rows="10" class="form-control" placeholder="Bio...."><?= get_user()->bio ?></textarea>
                                            <span class="text-danger error-text bio-error"></span>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary" value="Save Changes">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Timeline Tab End -->
                            <!-- Tasks Tab start -->
                            <div class="tab-pane fade" id="change_password" role="tabpanel">
                                <div class="pd-20 profile-task-wrap">
                                    <?php $validation =  \Config\Services::validation(); ?>
                                    <form action="<?= route_to('change-password') ?>" method="post">
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
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="current">Current Password</label>
                                                    <input type="text" class="form-control" name="current_password" placeholder="Enter Current Password" id="current">
                                                </div>
                                                <?php if ($validation->getErrors('current_password')): ?>
                                                    <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px">
                                                        <?= $validation->getError('current_password'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="new">New Password</label>
                                                    <input type="text" class="form-control" name="new_password" placeholder="Enter New Password" id="new">
                                                </div>
                                                <?php if ($validation->getErrors('new_password')): ?>
                                                    <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px">
                                                        <?= $validation->getError('new_password'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="cnew">Confirm New Password</label>
                                                    <input type="text" class="form-control" name="confirm_new_password" placeholder="Enter Confirm Password" id="cnew">
                                                </div>
                                                <?php if ($validation->getErrors('confirm_new_password')): ?>
                                                    <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px">
                                                        <?= $validation->getError('confirm_new_password'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Change Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Tasks Tab End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection() ?>
<!-- <?= $this->section('scripts') ?>
<script>
    $('#personal_details_form').on('submit', function(e){
        e.preventDefault();
        var form = this;
        var formdata = new FormData(form);
        $.ajax({
            url:$(form).attr('action'),
            method:$(form).attr('method'),
            data:formdata,
            processData:false,
            dataType:  'json',
            contentType:false,
            beforeSend:function(){
                toastr.remove();
                $(form).find('span.error-text').text(''); 
            },
            success: function(response){
                if($.isEmptyObject(response.error)){
                    if(response.status == 1){
                        $('.ci-user-name').each(function(){
                            $(this).html(response.user_info.name);
                        });
                        toastr.success(response.msg);
                    } else{
                        toastr.error(response.msg);
                    }
                } else{
                    $.each(response.error, function(prefix,val) {
                        $(form).find('span.'+prefix+'_error').text(val);
                    })
                }
            }
        })

    })
</script>
<?= $this->endSection() ?> -->