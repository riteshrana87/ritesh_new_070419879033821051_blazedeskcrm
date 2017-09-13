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
            <h4 class="modal-title"><div class="modelTitle">View <?php echo (isset($edit_record) && $edit_record[0]['supplier_name'] != '') ? $edit_record[0]['supplier_name'] : ''; ?></div></h4>
        </div>
        <div class="modal-body">
            <div class = "form-group row">
                <div class = "col-sm-12">
                    <label><?php echo lang('supplier_name'); ?></label>
                    <p><?php echo (isset($edit_record) && $edit_record[0]['supplier_name'] != '') ? $edit_record[0]['supplier_name'] : ''; ?></p>
                </div>
            </div>
            <div class = "form-group row">
                <div class = "col-sm-12">
                    <label><?php echo lang('address'); ?></label>
                    <p><?php echo (isset($edit_record) && $edit_record[0]['address'] != '') ? $edit_record[0]['address'] : ''; ?></p>
                </div>
            </div>

            <div class="modal-footer">

            </div>
        </div>
    </div>
</div> 
</div>
<div class="clr"></div>

<script>
    $(document).ready(function () {
        $('#frmsupplier').parsley();

    });
</script>