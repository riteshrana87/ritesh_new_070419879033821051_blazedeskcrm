<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
?>    

<?php
$attributes = array("name" => "frmsupplier", "id" => "frmsupplier");
?>
<!-- Modal New Opportunity-->
<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><div class="modelTitle"><?php echo  (isset($edit_record))?lang('edit_supplier'):lang('add_supplier'); ?></div></h4>
        </div>
        <?php echo form_open_multipart(base_url('Supplier/savedata'), $attributes); ?>
        <div class="modal-body">
            <div class = "form-group row">
                <div class = "col-sm-12">
                 <label for="supplier_name"><?=$this->lang->line('supplier_name')?>*</label>
                    <input type="text" id="supplier_name" class="form-control"  data-parsley-pattern="/^[ A-Za-z0-9_@.#&]*$/" required="" name="supplier_name" value="<?php echo (isset($edit_record) && $edit_record[0]['supplier_name'] != '') ? $edit_record[0]['supplier_name'] : ''; ?>" placeholder="<?php echo lang('supplier_name'); ?>" >
                </div>
            </div>
            <div class = "form-group row">
                <div class = "col-sm-12">
                 <label for="address"><?=$this->lang->line('address')?>*</label>
                    <textarea name="address" placeholder="<?php echo lang('address'); ?>" data-parsley-pattern="/^[ A-Za-z0-9_().,#&]*$/" id="address" required="" class="form-control"><?php echo (isset($edit_record) && $edit_record[0]['address'] != '') ? $edit_record[0]['address'] : ''; ?></textarea>
                </div>
            </div>

            <div class="modal-footer text">
                <input type="hidden" id="supplier_id" name="supplier_id" value="<?php echo (isset($edit_record) && $edit_record[0]['supplier_id'] != '') ? $edit_record[0]['supplier_id'] : ''; ?>">
                <input type="submit"  id="submit_btn" name="submit_btn" value="<?php echo  (isset($edit_record))?lang('update_supplier'):lang('add_supplier'); ?>" class="btn btn-primary">
            </div>
        </div>
        <?php echo form_close(); ?>

    </div>
</div> 
</div>
<div class="clr"></div>

<script>
    $(document).ready(function () {
        $('#frmsupplier').parsley();
    });
</script>
