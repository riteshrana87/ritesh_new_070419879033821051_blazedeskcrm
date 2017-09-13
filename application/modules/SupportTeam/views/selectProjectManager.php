<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'assignProjectManager';
$formPath = $project_view . '/' . $formAction;

$imgUrl = base_url('uploads/images/mark-johnson.png');
?>    
<div class="modal-dialog" id="teamReplacer">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><b><?php echo lang('select_project_manager'); ?></b> </h4>
        </div>
        <form id="from-model" method="post" enctype="multipart/form-data" action="<?php echo base_url($formPath); ?>" data-parsley-validate>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <select tabindex="-1" id="project_manager_id"  name="project_manager_id" class="chosen-select" >
                                <option value=""><?php echo lang('select_project_manager'); ?></option>
                                <?php
                                if (!empty($project_managers)) {
                                    foreach ($project_managers as $row) {
                                        ?>
                                        <option id="<?php echo $row['login_id'] ?>" data-img="<?php echo ($row['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $row['profile_photo'])) : $imgUrl; ?>" value="<?php echo $row['login_id'] ?>" data-name="<?php echo ucfirst($row['firstname']) . ' ' . $row['lastname'] ?> " data-role="<?php echo str_replace('_', ' ', $row['role_name']); ?>"><?php echo ucfirst($row['firstname']) . ' ' . $row['lastname']; ?><?php echo '(' . str_replace('_', ' ', $row['role_name']) . ')'; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <input type="submit" class="btn btn btn-green" name="submit_btn" id="submit_btn" value="<?php echo lang('assign_pm'); ?>" />

                </div>
                <div class="clr"> </div>
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#from-model').parsley();
        
        $('body').delegate('#submit_btn', 'click', function () {
            if ($('#from-model').parsley().isValid()) {
                $('input[type="submit"]').prop('disabled', true);
                $('#from-model').submit();
            }
        });
   $('.chosen-select').chosen();
    });
</script>