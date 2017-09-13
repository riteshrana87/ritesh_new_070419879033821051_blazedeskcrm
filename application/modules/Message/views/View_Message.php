<?php
defined ('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <!-- Modal content-->
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                <div class="modelMilestoneTitle"><?= $modal_title ?></div>
            </h4>
        </div>

        <div class="modal-body">
             <div class="form-group row">
                <div class="col-sm-6">
                    <label class="control-label"><?= lang ('MESSAGE_FROM') ?> : </label>
                        <?php echo $edit_record[0]['login_user_name'];?>
                </div>
                <div class="col-sm-6">
                    <label class="control-label"><?= lang ('MESSAGE_TO') ?> : </label>
                    <?php echo $edit_record[0]['contact_name']; ?>
                    
                </div>

            </div>
           
            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="control-label"><?= lang ('MESSAGE_SUBJECT') ?> : </label><BR/>
                    <?= !empty($edit_record[0]['message_subject']) ? $edit_record[0]['message_subject'] : '' ?>
                </div>
               
            </div>
            <div class="form-group row">
               
                <div class="col-sm-12">
                    <label class="control-label"><?= lang ('MESSAGE_DESCRIPTION') ?> : </label><BR/>
                    <?= !empty($edit_record[0]['message_description']) ? $edit_record[0]['message_description'] : '' ?>
                </div>
            </div>
            
           
            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="control-label"><?= lang ('created_date') ?> : </label>
                    <?php echo configDateTime($edit_record[0]['created_date']); ?>                 
                    
                </div>

            </div>
        </div>

        <div class="modal-footer">
            
                

        </div>

    </div>
</div>
</div><!-- /.modal-dialog -->


