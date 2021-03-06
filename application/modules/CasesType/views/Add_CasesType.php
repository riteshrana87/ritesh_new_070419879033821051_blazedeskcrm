<?php
defined ('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'insertdata';
$formPath   = $project_incidenttype_view . '/' . $formAction;
?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <form id="from-model" method="post" action="<?php echo base_url ($formPath); ?>" enctype="multipart/form-data"
              data-parsley-validate>
            <!-- Modal content-->
            

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <div class="modelMilestoneTitle"><?= $modal_title ?></div>
                </h4>
            </div>

            <div class="modal-body">

                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="control-label"><?= lang ('cases_type_name') ?> :* </label>
                        <input type="text" maxlength="100" class="form-control" placeholder="<?= lang ('cases_type_name') ?>"
                               id="cases_type_name" name="cases_type_name"
                               value="<?= !empty($edit_record[0]['cases_type_name']) ? htmlentities($edit_record[0]['cases_type_name']) : '' ?>"
                               required>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <center>
                    <input type="hidden" id="cases_type_id" name="cases_type_id"
                           value="<?= !empty($edit_record[0]['cases_type_id']) ? $edit_record[0]['cases_type_id'] : '' ?>">
                    <input type="hidden" id="display" name="display" value="<?= !empty($home) ? $home : '' ?>">
                    
                    <input type="submit" class="btn btn-primary" title="<?= $submit_button_title ?>" name="submit_btn" id="submit_btn"
                           value="<?= $submit_button_title ?>"/>

                     <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>"> 
            </div>

    </div>
    </form>
</div>
</div><!-- /.modal-dialog -->


<?php //echo form_close(); ?>
<script>
    //disabled after submit
    $('body').delegate('#submit_btn', 'click', function () {

        if ($('#from-model').parsley().isValid()) {
            $('input[type="submit"]').prop('disabled', true);
            $('#from-model').submit();
        }
    });
    $(function () {
        $('#from-model').parsley();
       
        ;
    });
</script>



