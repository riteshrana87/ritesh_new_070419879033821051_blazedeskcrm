<?php
defined ('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'insertdata';
$formPath   = $project_status_view . '/' . $formAction;
?>
<style type="text/css">
    .chosen-drop{font-family: "Helvetica Neue",Helvetica,Arial,sans-serif, 'FontAwesome';}
    .chosen-single{font-family: "Helvetica Neue",Helvetica,Arial,sans-serif, 'FontAwesome';}
</style>
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
                        <label class="control-label"><?= lang ('status_name') ?> <span class="viewtimehide">*</span> </label>
                        <input type="text" maxlength="15" class="form-control" placeholder="<?= lang ('status_name') ?>"
                               id="status_name" name="status_name"
                               value="<?= !empty($edit_record[0]['status_name']) ? $edit_record[0]['status_name'] : '' ?>"
                               required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12 input-group selectcolor">
                        <label class="control-label"><?= lang ('status_color') ?> <span class="viewtimehide">*</span> </label>

                        <div class="input-group demo2">
                        <!-- <span class="bd-clr-selectd"></span> -->
                            <input onkeydown="return false" data-parsley-errors-container="#dt_err" type="text" data-format="hex" id="status_color" class="form-control"
                                   placeholder="<?= lang ('status_color') ?>" id="status_color" name="status_color"
                                   value="<?= !empty($edit_record[0]['status_color']) ? $edit_record[0]['status_color'] : '' ?>"
                                   required>
                            <span class="input-group-addon"><i title="<?= lang ('pick_color') ?>" class="fa <?=isset($edit_record)?'':'fa-eyedropper'?> blackcol"></i></span>
                        </div>
                        <span id="dt_err"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="control-label"><?= lang ('status_font_icon') ?> <span class="viewtimehide">*</span> </label>
                        <select tabindex="-1" id="status_font_icon" name="status_font_icon"
                            class="form-control chosen-select" required data-placeholder="<?= lang ('font_icon') ?>">
                            <option value=""><?= lang ('font_icon') ?></option>
                            <?php
                            if (!empty($font_awesome_data)) {
                                foreach ($font_awesome_data as $key =>$row) {
                                    ?>

                                    <option <?php if (!empty($edit_record[0]['status_font_icon']) && $edit_record[0]['status_font_icon'] == strtolower($key)) {
                                        echo 'selected="selected"';
                                    } ?>
                                        value="<?= strtolower($key) ?>"><?=$row?> <?=ucfirst($key);?> </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <center>
                    <input type="hidden" id="status_id" name="status_id"
                           value="<?= !empty($edit_record[0]['status_id']) ? $edit_record[0]['status_id'] : '' ?>">
                    <input type="hidden" id="display" name="display" value="<?= !empty($home) ? $home : '' ?>">
                    <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                    <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn"
                           value="<?= $submit_button_title ?>"/>


            </div>

    </div>
    </form>
</div>
</div><!-- /.modal-dialog -->


<?php //echo form_close(); ?>
<script>
    $('body').delegate('#submit_btn', 'click', function () {

        if ($('#from-model').parsley().isValid()) {
            $('input[type="submit"]').prop('disabled', true);
            $('#from-model').submit();
        }
    });
    $(function () {
        $('#from-model').parsley();
        /*$('.selectcolor').colorpicker({format: 'hex'});*/
        $('.selectcolor').colorpicker().on('changeColor.colorpicker', function(event){
            var color = $('#status_color').val();
              if(color != '')
              {$('.blackcol').removeClass('fa-eyedropper');}
            });
        $('.chosen-select').chosen();
    });
</script>



