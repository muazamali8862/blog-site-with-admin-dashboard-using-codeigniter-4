<?php echo $this->extend('backend/layouts/pages-layout'); ?>
<?php echo $this->section('content'); ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>All Posts</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        All Posts
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="<?= route_to('new_post') ?>" class="btn btn-primary">Add new post</a>

        </div>
    </div>
</div>
<?php $validation =  \Config\Services::validation(); ?>
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
    <div class="col-md-12 mb-4">
        <div class="card card-box">
            <div class="card-header">
                <div class="clearfix">
                    <div class="pull-left">All Posts</div>
                    <div class="pull-right"></div>
                </div>
            </div>
            <div class="card-body">
            <div class="table-responsive-md">
                <table class="data-table stripe hover nowrap dataTable no-footer dtr-inline collapsed table" id="posts-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Featured Images</th>
                            <th scope="col">Title</th>
                            <th scope="col">Category</th>
                            <th scope="col">Visibility</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            </div>
        </div>
    </div>  
</div>
<?php echo $this->endSection() ?>
<?= $this->section('stylesheets') ?>
<link rel="stylesheet" href="/backend/src/plugins/datatables/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/backend/src/plugins/datatables/css/responsive.bootstrap4.min.css">
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="/backend/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="/backend/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<script>
    // retrieve posts
    var posts_DT = $('table#posts-table').DataTable({
        scrollCollapse:true,
        responsive: true,
        autoWidth: false,
        processing: true,
        serverSide: true,
        ajax: "<?= route_to('get-posts') ?>",
        "dom" : "IBfrtip",
        info:true,
        fnCreatedRow:function(row,data,index){
            $('td',row).eq(0).html(index+1);
        },
        columDefs:[
            {orderable:false,targets:[0,1,2,3,4,5]}
        ]
    })
    
// delete posts

$(document).on('click', '.deletepostbtn', function(e) {
        e.preventDefault();
        var post_id= $(this).data('id');
        var url = "<?= route_to('delete-post') ?>";
        var form = $('<form action="' + url + '" method="post"></form>');
        var input = $('<input type="hidden" name="post_id" value="' + post_id + '">');
        var csrf_input = $('<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">');
        form.append(input);
        form.append(csrf_input);
        $('body').append(form); // append the form to the body
        form.submit();
        form.remove(); // remove the form after submission
        return false;
    });
</script>
<?= $this->endSection() ?>