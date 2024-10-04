<div class="modal fade" id="category-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <?php $validation =  \Config\Services::validation(); ?>
        <form class="modal-content" action="<?= route_to('add-category') ?>" method="post" id="add-category-form">
            
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">
                    Large modal
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="category_name"><b>Category Name</b></label>
                    <input type="text" class="form-control" name="category_name" placeholder="Enter Category Name" id="category_name">
                    <span class="text-danger error-text category_name_error"></span>
                </div>
                <?php if ($validation->getErrors('category_name')): ?>
                    <div class="d-block text-danger" style=" margin-bottom: 10px">
                        <?= $validation->getError('category_name'); ?>

                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
                <button type="submit" class="btn btn-primary action">
                    Save changes
                </button>
            </div>
        </form>

    </div>
</div>