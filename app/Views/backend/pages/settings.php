<?php echo $this->extend('backend/layouts/pages-layout'); ?>
<?php echo $this->section('content'); ?>
<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Settings</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Settings
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="pd-20 card-box mb-4">
    <div class="tab">
        <ul class="nav nav-tabs customtab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#general-settings" role="tab" aria-selected="true">General Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#logo" role="tab" aria-selected="false">Logo &amp; Favicon</a>

            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#social" role="tab" aria-selected="false">Social Media</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="general-settings" role="tabpanel">
                <div class="pd-20">
                    <?php $validation =  \Config\Services::validation(); ?>
                    <form action="<?= route_to('update-general-setting') ?>" method="post" id="general_settings_form">
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
                                    <label for="blog_title">Blog Title</label>
                                    <input type="text" class="form-control" name="blog_title" placeholder="Enter blog title" id="blog_title" value="<?= get_settings()->blog_title ?>">
                                </div>
                                <?php if ($validation->getErrors('blog_title')): ?>
                                    <div class="d-block text-danger" style=" margin-bottom: 10px">
                                        <?= $validation->getError('blog_title'); ?>

                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="blog_email">Blog Email</label>
                                    <input type="text" class="form-control" name="blog_email" placeholder="Enter blog Email" id="blog_email" value="<?= get_settings()->blog_email ?>">
                                </div>
                                <?php if ($validation->getErrors('blog_email')): ?>
                                    <div class="d-block text-danger" style=" margin-bottom: 10px">
                                        <?= $validation->getError('blog_email'); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="blog_phone">Blog Phone number</label>
                                    <input type="text" class="form-control" name="blog_phone" placeholder="Enter blog Phone  number" id="blog_phone" value="<?= get_settings()->blog_phone ?>">

                                </div>
                                <?php if ($validation->getErrors('blog_phone')): ?>
                                    <div class="d-block text-danger" style=" margin-bottom: 10px">
                                        <?= $validation->getError('blog_phone'); ?>

                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="blog_meta_keyword">Blog meta keywords</label>
                                    <input type="text" class="form-control" name="blog_meta_keyword" placeholder="Enter blog meta keywords" id="blog_meta_keyword" value=" <?= get_settings()->blog_meta_keyword ?>">
                                </div>
                                <?php if ($validation->getErrors('blog_meta_keyword')): ?>
                                    <div class="d-block text-danger" style=" margin-bottom: 10px">
                                        <?= $validation->getError('blog_meta_keyword'); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="blog_meta_description">Blog meta description</label>
                            <textarea class="form-control" name="blog_meta_description" id="blog_meta_description" cols="4" rows="3" placeholder="Write blog meta description" value="<?= get_settings()->blog_meta_description ?>"></textarea>
                        </div>
                        <?php if ($validation->getErrors('blog_meta_description')): ?>
                            <div class="d-block text-danger" style=" margin-bottom: 10px">
                                <?= $validation->getError('blog_meta_description'); ?>

                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" name="submit" value="Save Changes">

                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane fade" id="logo" role="tabpanel">
                <div class="pd-20">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Set blog logo</h5>
                            <div class="brand-logo m-3">

                                <img src="<?= get_settings()->blog_logo == null ? '/backend/vendors/images/deskapp-logo.svg' : '/images/blogs/' . get_settings()->blog_logo ?>" alt="" class="avatar-photo ci-avatar-photo" style="background-size: cover;height: 100%; width: 100%;">
                                <!-- <img src="/backend/vendors/images/deskapp-logo.svg" alt="" class="dark-logo" /> -->

                            </div>

                            <?php $validation =  \Config\Services::validation(); ?>
                            <form action="<?= route_to('update-blog-logo') ?>" method="post" enctype="multipart/form-data" id="bloglogoform">

                                <div class="form-group">
                                    <input type="file" name="blog_logo" class="form-control">
                                </div>
                                <?php if ($validation->getErrors('blog_logo')): ?>
                                    <div class="d-block text-danger" style=" margin-bottom: 10px">
                                        <?= $validation->getError('blog_logo'); ?>

                                    </div>
                                <?php endif; ?>
                                <input type="submit" class="btn btn-primary" value="Change  Logo">

                            </form>
                        </div>

                        <div class="col-md-6">
                            <h5>Set blog Favicon</h5>
                            <div class="brand-logo m-3">  

                                <img src="<?= get_settings()->blog_favicon == null ? '/backend/vendors/images/favicon-16x16.png' : '/images/blogs/' . get_settings()->blog_favicon ?>" alt="" class="avatar-photo ci-avatar-photo my-auto" width="50px" height="50px">


                                <!-- <img src="/backend/vendors/images/deskapp-logo.svg" alt="" class="dark-logo" /> -->

                            </div>

                            <?php $validation =  \Config\Services::validation(); ?>
                            <form action="<?= route_to('update-blog-favicon') ?>" method="post" enctype="multipart/form-data" id="bloglogoform">

                                <div class="form-group">
                                    <input type="file" name="blog_favicon" class="form-control">
                                </div>
                                <?php if ($validation->getErrors('blog_favicon')): ?>
                                    <div class="d-block text-danger" style=" margin-bottom: 10px">
                                        <?= $validation->getError('blog_favicon'); ?>

                                    </div>
                                <?php endif; ?>
                                <input type="submit" class="btn btn-primary" value="Change Favicon">

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="social" role="tabpanel">
                <div class="pd-20">
                <?php $validation =  \Config\Services::validation(); ?>
                   <form action="<?= route_to('update-social-media') ?>" method="post">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="facebook_url">Facebook URL</label>
                                <input type="text" class="form-control" name="facebook_url" placeholder="Enter Facebook URL" id="facebook_url"  value="<?= get_social_media()->facebook_url ?>">

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="twitter_url">Twitter URL</label>
                                <input type="text" class="form-control" name="twitter_url" placeholder="Enter Twitter URL" id="twitter_url"  value="<?= get_social_media()->twitter_url ?>" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="instagram_url">Instagram URL</label>
                                <input type="text" class="form-control" name="instagram_url" placeholder="Enter Instagram URL" id="instagram_url"   value="<?= get_social_media()->instagram_url ?>">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="youtube_url">Youtube URL</label>
                                <input type="text" class="form-control" name="youtube_url" placeholder="Enter Youtube URL" id="youtube_url"   value="<?= get_social_media()->youtube_url ?>">

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="github_url">GitHub URL</label>
                                <input type="text" class="form-control" name="github_url" placeholder="Enter GitHub URL" id="github_url"  value="<?= get_social_media()->github_url ?>" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="linkedin_url">Linkedin URL</label>
                                <input type="text" class="form-control" name="linkedin_url" placeholder="Enter Linkedin URL" id="linkedin_url"   value="<?= get_social_media()->linkedin_url ?>" >

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Save Changes">
                    </div>
                   </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->endSection() ?>