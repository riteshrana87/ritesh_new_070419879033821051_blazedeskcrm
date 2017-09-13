<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = 'KnowledgeBase/saveMainCategory';
$formPath = $project_view . '/' . $formAction;
?>    
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><div class="title"><?= $this->lang->line('add_main_category') ?></div></h4>
        </div>
        <form id="from-model" class="add_main" method="post"  action="<?php echo base_url($formPath); ?>" data-parsley-validate enctype="multipart/form-data"> 
            <div class="modal-body">
                <div class = "form-group row">
                    <div class = "col-sm-12">
                        <input type="text" class="form-control" placeholder="<?php echo lang('category_name') ?> *" id="category_name" name="category_name"  required>
                        <span id="cost_name_error" class="alert-danger"></span>
                    </div>
                </div>
                <div class = "form-group row">
                    <div class = "col-sm-12">
                        <input type="text" id="type_id" name="type_id" class="form-control"  placeholder="<?php echo lang('type') ?> *" value="" required>

                        <span id="cost_name_error" class="alert-danger"></span>
                    </div>
                </div>
                <div class = "row">
                    <div class="col-lg-12  col-xs-12 col-sm-12">
                        <div class="row">
                            <div class="col-lg-5 col-xs-12 col-sm-5">
                                <?php
                                if (!empty($editRecord[0]['client_visible'])) {
                                    $client_time_id = "client_amount_show";
                                } else {
                                    $client_time_id = "client_amount_hide";
                                }
                                ?>
                                <div class="form-group">
                                    <input data-toggle="toggle" data-onstyle="success" type="checkbox"  id="client_visible" name="client_visible" onChange="toggle_show(<?php echo "'#" . $client_time_id . "'"; ?>, this)" <?= !empty($editRecord[0]['client_visible']) ? 'checked="checked"' : '' ?> data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"/>

                                    <label for="client_visible">
                                        <?php echo lang('client_visible'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-7 col-xs-12 col-sm-7 form-group">
                             
                                    <select class="form-control client_visible" name="article_owner" id="article_owner" >
                                        <option value=""><?php echo lang('article_owner') ?></option>
                                        <?php foreach ($user as $user_data) { ?>
                                            <option value="<?php echo $user_data['login_id']; ?>" <?php echo ($articleOwner == $user_data['login_id']) ? 'selected' : ''; ?>><?php echo $user_data['firstname'] . ' ' . $user_data['lastname']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span id="cost_type_error" class="alert-danger"></span>
                                
                            </div>
                            <div class="clr"></div>
                        </div>
                    </div>
                    <div class="col-lg-12  col-xs-12 col-sm-12">
                        <div class="row">
                            <div class="col-lg-5 col-xs-12 col-sm-5">
                                <?php
                                if (!empty($editRecord[0]['related_product'])) {
                                    $related_time_id = "related_amount_show";
                                } else {
                                    $related_time_id = "related_amount";
                                }
                                ?>
                                <div class="form-group">
                                    <input data-toggle="toggle" data-onstyle="success" type="checkbox"  id="product_related" name="product_related" onChange="toggle_show(<?php echo "'#" . $related_time_id . "'"; ?>, this)" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"/>

                                    <label for="product_related">
                                        <?php echo lang('product_related'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-7 col-xs-12 col-sm-7">
                                <div id="<?php echo $related_time_id; ?>">
                                    <select class="form-control chosen-select product_related" multiple="true"  name="product_id[]" id="" data-placeholder="<?= $this->lang->line('select_option') ?>">
                                        <?php if (!empty($product_info) && count($product_info) > 0) { ?>
                                            <?php foreach ($product_info as $row) { ?>
                                                <option value="<?php echo $row['product_id']; ?>" <?php
                                                if (!empty($product_data) && in_array($row['product_id'], $product_data)) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>><?php echo $row['product_name']; ?></option>
    <?php } ?>
<?php } ?>
                                    </select>
                                </div>

                                <span id="cost_type_error" class="alert-danger"></span>
                            </div>
                           
                        </div>
                    </div>
                    <div class="col-lg-12  col-xs-12 col-sm-12">
                        <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label class="custom-upload btn btn-primary"><?= $this->lang->line('category_icon') ?>
                                            <input type="file" class="form-control" name="icon_image"  id="icon_image" onchange="$('#icon_image_txt').html($('#icon_image').val().split('\\').pop());" placeholder="<?= $this->lang->line('category_icon') ?>" value="" data-parsley-fileextension='png|jpeg|jpg|JPG|PNG|JPEG' data-parsley-max-file-size="50" data-parsley-errors-container="#icon_image_errors"/>
                                        </label>
                                        <p id="icon_image_txt"></p>
                                        <p id="icon_image_errors"></p>
                                    </div>
                                    
                                </div>
                            </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?= $this->lang->line('add_main_category') ?>" />
                </div>
            </div>
        </form>

    </div>


    <script>
        $('#from-model').parsley();
                     window.ParsleyValidator.addValidator('fileextension', function (value, requirement) {
            // the value contains the file path, so we can pop the extension
            var fileExtension = value.split('.').pop();
            var multipleFileType = requirement.split('|');

            if ($.inArray(fileExtension, multipleFileType) != -1)
            {
                return true;
            } else
            {
                return false;
            }

        }, 32).addMessage('en', 'fileextension', '<?php echo lang('MSG_UPLOAD_PROFILE_PIC'); ?>');
        window.Parsley.addValidator('maxFileSize', {
            validateString: function (_value, maxSize, parsleyInstance) {
                if (!window.FormData) {
					
					  var delete_meg ="<?php echo lang('upgrade_your_browser');?>";
                    BootstrapDialog.show(
                        {
                            title: '<?php echo $this->lang->line('Information');?>',
                            message: delete_meg,
                            buttons: [{
                                label: '<?php echo $this->lang->line('ok');?>',
                                action: function(dialog) {
                                    dialog.close();
                                }
                            }]
                        });
                    //alert('You are making all developpers in the world cringe. Upgrade your browser!');
                    return true;
                }
                var files = parsleyInstance.$element[0].files;
                return files.length != 1 || files[0].size <= maxSize * 1024;
            },
            requirementType: 'integer',
            messages: {
                en: 'This file should not be larger than %s Kb',
                fr: "Ce fichier est plus grand que %s Kb."
            }
        });
        $('.product_related').trigger('chosen:updated');
        $('.client_visible').trigger('chosen:updated');
        $('#client_visible').bootstrapToggle();
        $('#product_related').bootstrapToggle();

        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    </script>
    
    <script>
        $('#gallery-btn').click(function () {
            $('#modbdy').load($(this).attr('data-href'));
            $('costModel').modal('hide');
            $('#modalGallery').modal('show');
        });
        
            $('body').delegate('#submit_btn', 'click', function () {
           
            if ($('.add_main').parsley().isValid()) {
                 //disabled submit button after submit
                $('input[type="submit"]').prop('disabled', true);
                $('.add_main').submit();
            }

        });
    </script>
           

    <script>
        $(document).ready(function () {
            $("input[name='primary_contact[]']:checked").val('1');
            var cls = $("input[name='primary_contact[]']:checked").attr('id');
            $('.' + cls).val('');
            
        });
        $(function () {
            var availabletype = [
<?php
if (isset($type_data) && count($type_data) > 0) {
    $count = 0;
    foreach ($type_data as $type) {
        $count++;
        echo '"' . addslashes($type['type']) . '"';
        if ($count != count($type_data)) {
            echo ", ";
        }
    }
}
?>
            ];
            $("#type_id").autocomplete({
                source: availabletype
            });
        });
    </script>
