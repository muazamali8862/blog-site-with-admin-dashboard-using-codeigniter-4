<div class="modal fade" id="sub-category-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <?php $validation =  \Config\Services::validation(); ?>
        <form class="modal-content" action="<?= route_to('add-subcategory') ?>" method="post" id="add-subcategory-form">
            
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
                    <label for="parent_cat"><b>Parent Category</b></label>
                    <select class="form-control" name="parent_cat" id="parent_cat">
                        <option value="">Uncategorized</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="subcategory_name"><b>Sub Category Name</b></label>
                    <input type="text" class="form-control" name="subcategory_name" placeholder="Enter Sub Category Name" id="subcategory_name">
                    <span class="text-danger error-text category_name_error"></span>
                </div>
                <div class="form-group">
                    <label for="desc"><b>Description</b></label>
                    <textarea name="description" id="desc" cols="30" rows="10" placeholder="Type...."  class="form-control"></textarea>

                </div>

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