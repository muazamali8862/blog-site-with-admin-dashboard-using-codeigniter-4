<?php echo $this->extend('backend/layouts/pages-layout'); ?>
<?php echo $this->section('content'); ?>
<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Catogories</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Catogories
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

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
    <div class="alert alert-danger d-flex justify-content-between">
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
                    <div class="pull-left">
                        Catogories
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-default btn-sm p-0" role="button" id="add_catogery_btn"><i class="fa fa-plus-circle"></i> Add Category</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table class="table table-sm table-borderless table-hover table-striped" id="categories-table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Category Name</th>
                                <th scope="col">N. of Sub Categories</th>
                                <th scope="col">Action</th>
                                <th scope="col">Ordering</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-12 mb-4">
        <div class="card card-box">
            <div class="card-header">
                <div class="clearfix">
                    <div class="pull-left">
                        Sub Catogories
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-default btn-sm p-0" role="button" id="add_subcatogery_btn"><i class="fa fa-plus-circle"></i> Add Sub Category</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table class="table table-sm table-borderless table-hover table-striped " id="sub-categories-table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Sub Category Name</th>
                                <th scope="col">Parent Category</th>
                                <th scope="col">N. of Posts</th>
                                <th scope="col">Action</th>
                                <th scope="col">Ordering</th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- include  modal -->
<?php include('modals/category_modal_form.php') ?>
<?php include('modals/edit_category_modal_form.php') ?>
<?php include('modals/subcategory_modal_form.php') ?>
<?php include('modals/edit_sub_category_modal.php') ?>
<?php echo $this->endSection() ?>
<?= $this->section('stylesheets') ?>
<!-- include  stylesheets -->

<link rel="stylesheet" href="/backend/src/plugins/datatables/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/backend/src/plugins/datatables/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="/extra-assets/jquery-ui-1.14.0/jquery-ui.min.css">
<link rel="stylesheet" href="/extra-assets/jquery-ui-1.14.0/jquery-ui.structure.min.css">
<link rel="stylesheet" href="/extra-assets/jquery-ui-1.14.0/jquery-ui.theme.min.css">
<?= $this->endSection() ?>
<?php $this->section('scripts') ?>
<!-- include scripts -->
<script src="/backend/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="/backend/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<script src="/extra-assets/jquery-ui-1.14.0/jquery-ui.min.js"></script>
<script>
    // show category modal
    $(document).on('click', '#add_catogery_btn', function(e) {
        e.preventDefault();
        var modal = $('body').find('div#category-modal');
        var modal_title = 'Add Category';
        var modal_btn_text = 'ADD';
        modal.find('.modal-title').html(modal_title);
        modal.find('.modal-footer > button.action').html(modal_btn_text);
        modal.find('input.error-text').html('');
        modal.find('input[type="text"]').val('');
        modal.modal('show');
    });
    // show category data
    var categories_DT = $('#categories-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "<?= route_to('get-categories') ?>",
        dom: "BrTip",
        info: true,
        fnCreatedRow: function(row, data, index) {
            $('td', row).eq(0).html(index + 1);
            // console.log(data);
            $('td', row).parent().attr('data-index', data[0]).attr('data-ordering', data[4]);
        },
        columnDefs: [{
                orderable: false,
                targets: [0, 1, 2, 3]
            },
            {
                visible: false,
                targets: 4
            }
        ],
        order: [
            [4, 'asc']
        ]
    });

    // edit category
    $(document).on('click', '.editcategorybtn', function(e) {
        e.preventDefault();
        var category_id = $(this).data('id');
        var url = "<?= route_to('get-cetegory') ?>";
        $.get(url, {
            category_id: category_id
        }, function(response) {
            var model_title = 'Edit  Category';
            var modal_btn_text = 'Save Changes';
            var modal = $('body').find('div#edit-category-modal');
            modal.find('form').find('input[type="hidden"][name="category_id"]').val(category_id);
            modal.find('.modal-title').html(model_title);
            modal.find('.modal-footer > button.action').html(modal_btn_text);
            modal.find('input[type="text"]').val(response.data.name);
            modal.find('span.error-text').html('');
            modal.modal('show');
        }, 'json')
    });
    // delete category
    $(document).on('click', '.deletecategorybtn', function(e) {
        e.preventDefault();
        var category_id = $(this).data('id');
        var url = "<?= route_to('delete-category') ?>";
        var form = $('<form action="' + url + '" method="post"></form>');
        var input = $('<input type="hidden" name="category_id" value="' + category_id + '">');
        var csrf_input = $('<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">');
        form.append(input);
        form.append(csrf_input);
        $('body').append(form); // append the form to the body
        form.submit();
        form.remove(); // remove the form after submission
        return false;
    });
    // sorting category
    $('table#categories-table').find('tbody').sortable({
        update: function(event, ui) {
            $(this).children().each(function(index) {
                if ($(this).attr('data-ordering') != (index + 1)) {
                    $(this).attr('data-ordering', (index + 1)).addClass('updated');
                }
            });
            var positions = [];
            $('.updated').each(function() {
                positions.push([$(this).attr('data-index'), $(this).attr('data-ordering')]);
                $(this).removeClass('updated');
            });
            var url = "<?= route_to('reorder-categories') ?>";
            var form = $('<form action="' + url + '" method="post"></form>');
            for (var i = 0; i < positions.length; i++) {
                var input = $('<input type="hidden" name="positions[]" value="' + positions[i][0] + ',' + positions[i][1] + '">');
                form.append(input);
            }
            var csrf_input = $('<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">');
            form.append(csrf_input);
            $('body').append(form); // append the form to the body
            form.submit();
            form.remove(); // remove the form after submission
        }
    });
    // show sub category modal
    $(document).on('click', '#add_subcatogery_btn', function(e) {
        e.preventDefault();
        var modal_title = 'Add Sub Category';
        var modal_btn_text = 'Add';
        var modal = $('body').find('div#sub-category-modal');
        var select = modal.find('select[name="parent_cat"]');
        var url = "<?= route_to('get-parent-categories') ?>";
        $.getJSON(url, {
            parent_category_id: null
        }, function(response) {
            select.find('option').remove();
            select.html(response.data);
        });
        modal.find('.modal-title').html(modal_title);
        modal.find('.modal-footer > button.action').html(modal_btn_text);
        modal.find('input[type="text"]').val('');
        modal.find('textarea').html('');
        modal.modal('show');

    });
    // show sub category data
    var categories_DT = $('#sub-categories-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "<?= route_to('get-subcategories') ?>",
        dom: "BrTip",
        info: true,
        fnCreatedRow: function(row, data, index) {
            $('td', row).eq(0).html(index + 1);
            // console.log(data);
            $('td', row).parent().attr('data-index', data[0]).attr('data-ordering', data[5]);
        },
        columnDefs: [{
                orderable: false,
                targets: [0, 1, 2, 3, 4]
            },
            {
                visible: false,
                targets: 5
            }
        ],
        order: [
            [5, 'asc']
        ]
    });

    // edit sub category
    $(document).on('click', '.editsubcategorybtn', function(e) {
        e.preventDefault();
        var subcategory_id = $(this).data('id');
        var get_subcategory_url = "<?= route_to('get-subcetegory') ?>";
        var get_parent_url = "<?= route_to('get-parent-categories') ?>";
        var model_title = 'Edit Sub Category';
        var modal_btn_text = 'Save Changes';
        var modal = $('body').find('div#edit-sub-category-modal');
        modal.find('.modal-title').html(model_title);
        modal.find('.modal-footer > button.action').html(modal_btn_text);
        modal.find('span.error-text').html('');
        var select = modal.find('select[name="parent_cat"]');
        $.getJSON(get_subcategory_url, {
            subcategory_id: subcategory_id
        }, function(response) {
            modal.find('input[type="text"][name="subcategory_name"]').val(response.data.name);
            modal.find('form').find('input[type="hidden"][name="subcategory_id"]').val(response.data.id);
            modal.find('form').find('textarea[name="description"]').val(response.data.description);

            $.getJSON(get_parent_url, {
                parent_category_id: response.data.parent_cat
            }, function(response) {
                select.find('option').remove();
                select.html(response.data);
            }, 'json');

            modal.modal('show');

        }, 'json')
    });
// reorder sub  categories

    $('table#sub-categories-table').find('tbody').sortable({
        update: function(event, ui) {
            $(this).children().each(function(index) {
                if ($(this).attr('data-ordering') != (index + 1)) {
                    $(this).attr('data-ordering', (index + 1)).addClass('updated');
                }
            });
            var positions = [];
            $('.updated').each(function() {
                positions.push([$(this).attr('data-index'), $(this).attr('data-ordering')]);
                $(this).removeClass('updated');
            });
            var url = "<?= route_to('reorder-subcategories') ?>";
            var form = $('<form action="' + url + '" method="post"></form>');
            for (var i = 0; i < positions.length; i++) {
                var input = $('<input type="hidden" name="positions[]" value="' + positions[i][0] + ',' + positions[i][1] + '">');
                form.append(input);
            }
            var csrf_input = $('<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">');
            form.append(csrf_input);
            $('body').append(form); // append the form to the body
            form.submit();
            form.remove(); // remove the form after submission
        }
    });
    // delete sub category

    $(document).on('click', '.deletesubcategorybtn', function(e) {
        e.preventDefault();
        var subcategory_id = $(this).data('id');
        var url = "<?= route_to('delete-subcategory') ?>";
        var form = $('<form action="' + url + '" method="post"></form>');
        var input = $('<input type="hidden" name="subcategory_id" value="' + subcategory_id + '">');
        var csrf_input = $('<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">');
        form.append(input);
        form.append(csrf_input);
        $('body').append(form); // append the form to the body
        form.submit();
        form.remove(); // remove the form after submission
        return false;
    });
</script>
<?php echo $this->endSection() ?>