<?php
defined ('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'insert_job_title';
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
                        <label class="control-label"><?= lang ('JOB_TITLE_NAME') ?> *: </label>
                        <input type="text" maxlength="100" class="form-control" placeholder="<?= lang ('JOB_TITLE_NAME') ?>"
                               id="job_title_name" name="job_title_name"
                               value="<?= !empty($edit_record[0]['job_title_name']) ? htmlentities($edit_record[0]['job_title_name']) : set_value('job_title_name', ''); ?>"
                               required>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <center>
                    <input type="text" id="redirect_link" name="redirect_link"  hidden="" value="<?php  echo $_SERVER['HTTP_REFERER'];?>">
                    <input type="hidden" id="job_title_id" name="job_title_id"
                           value="<?= !empty($edit_record[0]['job_title_id']) ? $edit_record[0]['job_title_id'] : '' ?>">
                    <input type="hidden" id="display" name="display" value="<?= !empty($home) ? $home : '' ?>">
                    
                    <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn"
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



