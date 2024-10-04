<?php

use App\Libraries\CIAuth;

 echo $this->extend('backend/layouts/pages-layout'); ?>
<?php echo $this->section('content'); ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Edit Post</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Post
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="<?= route_to('all-posts') ?>" class="btn btn-primary">View All Posts</a>
        </div>
    </div>
</div>
<?php $validation =  \Config\Services::validation(); ?>
<form action="<?= route_to('update-post') ?>" method="post" enctype="multipart/form-data" autocomplete="off" id="updatepostform">
    <input type="hidden" name="post_id" value = "<?= $post->id ?>">
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
        <div class="col-md-9">
            <div class="card card-box mb-2">
                <div class="card-body">
                    <div class="form-group">
                        <label for="title"><b>Post Title</b></label>
                        <input type="text" class="form-control" placeholder="Enter Post title" name="title" id="title"  value="<?= $post->title ?>">

                    </div>
                    <?php if ($validation->getErrors('title')): ?>
                        <div class="d-block text-danger" style=" margin-bottom: 10px">
                            <?= $validation->getError('title'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="content"><b>Content</b></label>
                        <textarea name="content" id="content" placeholder="Enter Post content" class="form-control" cols="30" rows="10">  <?= $post->content ?> </textarea>

                    </div>
                    <?php if ($validation->getErrors('content')): ?>
                        <div class="d-block text-danger" style=" margin-bottom: 10px">
                            <?= $validation->getError('content'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card card-box mb-2">
                <h5 class="card-header weight-500">SEO</h5>
                <div class="card-body">
                    <div class="form-group">
                        <label for="meta_keywords"><b>Post meta keywords</b><small>(Separated by comma)</small></label>
                        <input type="text" class="form-control" placeholder="Enter Post meta keywords" name="meta_keywords" id="meta_keywords" value="<?= $post->meta_keywords ?>">
                    </div>
                    <div class="form-group">
                        <label for="meta_description"><b>Post meta description</b></label>
                        <textarea name="meta_description" id="meta_description" class="form-control" placeholder="Enter Post meta description" cols="30" rows="10"><?=  $post->meta_description ?></textarea>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-box mb-2">
                <div class="card-body">

                    <div class="form-group">
                        <label for=""><b>Post Category</b></label>
                        <select name="category" id="category" class="custom-select form-control">
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category->id ?>" <?= $category->id == $post->category_id ? 'selected' : '' ?>><?= $category->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <?php if ($validation->getErrors('category')): ?>
                        <div class="d-block text-danger" style=" margin-bottom: 10px">
                            <?= $validation->getError('category'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="featured_image">Post featured image</label>
                        <!-- <input type="file" name="featured_image" class="form-control form-control-file" id="featured_image" height="auto"> -->
                        <input type="file" id="featured_image" name="featured_image" class="form-control form-control-file"/>
                        
                    </div>
                    <?php if ($validation->getErrors('featured_image')): ?>
                        <div class="d-block text-danger" style=" margin-bottom: 10px">
                            <?= $validation->getError('featured_image'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="d-block mb-3" style="max-width: 250px;">
                        <img id="image-preview" src="/images/posts/resized<?= $post->featured_image ?>" class="img-thumbnail">
                    </div>
                    <div class="form-group">
                        <label for="tags"><b>Tags</b></label>
                        <input type="text" name="tags" id="tags" placeholder="Enter tags" class="form-control" data-role="tagsinput"  value="<?= $post->tags ?>">

                    </div>
                    <?php if ($validation->getErrors('tags')): ?>
                        <div class="d-block text-danger" style=" margin-bottom: 10px">
                            <?= $validation->getError('tags'); ?>
                        </div>
                    <?php endif; ?>
                    <hr>
                    <div class="form-group">
                        <label for=""><b>Visibility</b></label>
                        <div class="custom-control custom-radio mb-5">
                            <input type="radio" name="visibility" id="customradio1" class="custom-control-input" value="1" <?= $post->visibility == 1 ? 'checked' : '' ?>>
                            <label for="customradio1" class="custom-control-label">Public</label>
                        </div>
                        <div class="custom-control custom-radio mb-5">
                            <input type="radio" name="visibility" id="customradio2" class="custom-control-input" value="0" <?= $post->visibility == 0 ? 'checked' : '' ?>>
                            <label for="customradio2" class="custom-control-label">Private</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <input type="submit" class="btn btn-primary" value="Save Changes">
    </div>
</form>
<?php echo $this->endSection() ?>
<?php echo $this->section('stylesheets') ?>
<link rel="stylesheet" href="/backend/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
<?= $this->endSection() ?>
<?php echo $this->section('scripts') ?>
<script src="/backend/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<script src="/extra-assets/ckeditor/ckeditor.js"></script>
<script>
$(function(){
    var elfinderpath = '/extra-assets/elFinder/elfinder.src.php?integration=ckeditor&uid=<?= CIAuth::id() ?>';
    CKEDITOR.replace('content',{
        filebrowserBrowseUrl:elfinderpath,
        filebrowserImageBrowseUrl:elfinderpath+'&type=image',
        removeDialogTabs:'link:upload;image:upload'
    });

})


    // image preview
    const imageInput = document.getElementById('featured_image');
const imagePreview = document.getElementById('image-preview');

imageInput.addEventListener('change', (e) => {
  const file = imageInput.files[0];
  const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
  const fileType = file.type;

  if (allowedTypes.includes(fileType)) {
    const reader = new FileReader();

    reader.onload = (event) => {
      imagePreview.src = event.target.result;
    };

    reader.readAsDataURL(file);
  } else {
    alert('Only JPG, PNG, and JPEG files are allowed.');
    imageInput.value = ''; // Reset the input field
  }
});


</script>
<?= $this->endSection() ?>