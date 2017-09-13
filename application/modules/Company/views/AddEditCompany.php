<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord) ? 'updateData' : 'insertData';
$path = $company_view . '/' . $formAction;
?>

<!-- Modal New Company-->
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><div class="title"><?PHP if ($formAction == "insertData") { ?><?= lang('create_company') ?><?php } else { ?><?= $this->lang->line('update_company') ?><?php } ?></div></h4>
        </div>
        <form id="from_model" name="from_model" method="post"  action="<?php echo base_url($path); ?>" data-parsley-validate="" enctype="multipart/form-data"> 
            <div class="modal-body">
                <input name="company_id" type="hidden" value="<?= !empty($editRecord[0]['company_id']) ? $editRecord[0]['company_id'] : '' ?>" />
                <div class=" row">
                    <div class="col-sm-6 form-group">
                        <select name="country_id" onchange="api_call();" class="form-control chosen-select" id="country_id" required  data-parsley-errors-container="#country-errors">
                            <option value="">
                                <?= $this->lang->line('select_country') ?> *
                            </option>
                            <?php if (isset($country) && count($country) > 0) { ?>
                                <?php foreach ($country as $country_data) { ?>
                                    <option data-taxincluded-amount="<?php echo $country_data['country_code']; ?>" value="<?php echo $country_data['country_id']; ?>" <?php
                                    if (!empty($editRecord[0]['country_id']) && $editRecord[0]['country_id'] == $country_data['country_id']) {
                                        echo 'selected';
                                    }
                                    ?>><?php echo $country_data['country_name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <div id="country-errors"></div>
                    </div>
                    <div class="col-sm-6 form-group">

                        <input class="form-control" name="company_name" id="company_name" placeholder="<?= lang('company_name') ?> *" type="text" value="<?PHP if ($formAction == "insertdata") {
                            echo set_value('company_name');
                            ?><?php } else { ?><?= !empty($editRecord[0]['company_name']) ? htmlentities(stripslashes($editRecord[0]['company_name'])) : '' ?><?php } ?>"  required/><span></span>
                        <input type="hidden" name="com_reg_number" id="com_reg_number" value="">
                        <input type="hidden" name="company_id_data" id="company_id_data" value="">
                    </div>
                </div>       
                <div class=" row">
                    <div class="col-sm-6 form-group">
                        <input type="text" id="branch_id" name="branch_id" class="form-control"  placeholder="<?= $this->lang->line('branche') ?> *" value="<?php
                        if (!empty($branch_data1[0]['branch_name'])) {
                            echo htmlentities(stripslashes($branch_data1[0]['branch_name']));
                        } else {
                            echo '';
                        }
                        ?>" required>

                    </div>
                    <div class="col-sm-6 form-group">
                        <input class="form-control" name="phone_no"  min="0" placeholder="<?= lang('contact_no') ?>" type="text" value="<?PHP if ($formAction == "insertdata") {
                            echo set_value('contact_no');
                            ?><?php } else { ?><?= !empty($editRecord[0]['phone_no']) ? $editRecord[0]['phone_no'] : '' ?><?php } ?>" />

                    </div>
                </div>

                <div class=" row">
                    <div class="col-sm-6 form-group">
                        <input class="form-control" name="website" placeholder="<?= lang('website') ?>" type="url" value="<?PHP if ($formAction == "insertdata") {
                            echo set_value('website');
                            ?><?php } else { ?><?= !empty($editRecord[0]['website']) ? $editRecord[0]['website'] : '' ?><?php } ?>"  data-parsley-trigger="change"  />

                    </div>
                    <div class="col-sm-6 form-group">
                        <input class="form-control" name="address1" id="address1" placeholder="<?= lang('address1') ?>" type="text" value="<?PHP if ($formAction == "insertdata") {
                               echo set_value('address1');
                               ?><?php } else { ?><?= !empty($editRecord[0]['address1']) ? htmlentities(stripslashes($editRecord[0]['address1'])) : '' ?><?php } ?>"  />

                    </div>
                </div>

                <div class=" row">
                    <div class="col-sm-6 form-group">
                        <input class="form-control" name="address2" placeholder="<?= lang('address2') ?>" type="text" value="<?PHP if ($formAction == "insertdata") {
                               echo set_value('address2');
                               ?><?php } else { ?><?= !empty($editRecord[0]['address2']) ? htmlentities(stripslashes($editRecord[0]['address2'])) : '' ?><?php } ?>"  />

                    </div>
                    <div class="col-sm-6 form-group">
                        <input class="form-control" name="city" id="city" placeholder="<?= lang('city') ?>" type="text" value="<?PHP if ($formAction == "insertdata") {
                               echo set_value('city');
                            ?><?php } else { ?><?= !empty($editRecord[0]['city']) ? htmlentities(stripslashes($editRecord[0]['city'])) : '' ?><?php } ?>"  /> 

                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-group ">
                        <input class="form-control" name="state" placeholder="<?= lang('state') ?>" type="text" value="<?PHP if ($formAction == "insertdata") {
                                echo set_value('state');
                            ?><?php } else { ?><?= !empty($editRecord[0]['state']) ? htmlentities(stripslashes($editRecord[0]['state'])) : '' ?><?php } ?>"  />

                    </div>
                    <div class="col-sm-6 form-group ">
                        <input class="form-control" name="email_id" placeholder="<?= lang('email') ?> *"  type="email" value="<?PHP if ($formAction == "insertdata") {
                            echo set_value('email_id');
                            ?><?php } else { ?><?= !empty($editRecord[0]['email_id']) ? $editRecord[0]['email_id'] : '' ?><?php } ?>" data-parsley-trigger="change" required/>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group ">
                        <input class="form-control" name="postal_code" id="postal_code" placeholder="<?= lang('postal_code') ?>" type="text" value="<?PHP if ($formAction == "insertdata") {
                                echo set_value('postal_code');
    ?><?php } else { ?><?= !empty($editRecord[0]['postal_code']) ? htmlentities(stripslashes($editRecord[0]['postal_code'])) : '' ?><?php } ?>"  />
                    </div>
                    <div class="col-sm-6 form-group">
                        <select name="status" class="form-control chosen-select" id="status" >
                          
                            <option value="1" <?php
if (isset($editRecord[0]['status']) && $editRecord[0]['status'] == 1) {
    echo 'selected';
}
?>><?= lang('active'); ?></option>
                            <option value="0" <?php
if (isset($editRecord[0]['status']) && $editRecord[0]['status'] == 0) {
    echo 'selected';
}
?>><?= lang('inactive'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label class="custom-upload btn btn-primary"><?= $this->lang->line('logo_image') ?>
                            <input type="file" class="form-control" name="logo_image"  id="logo_image" onchange="$('#logo_image_txt').html($('#logo_image').val().split('\\').pop());" placeholder="<?= $this->lang->line('logo_image') ?>" value="<?= !empty($editRecord[0]['logo_img']) ? $editRecord[0]['logo_img'] : '' ?>" data-parsley-fileextension='png|jpeg|jpg|JPG|PNG|JPEG' data-parsley-max-file-size="2000" data-parsley-errors-container="#logo_image_errors"/>
                        </label>
                        <p id="logo_image_txt"></p>
                        <p id="logo_image_errors"></p>
                    </div>
                    <div class="col-sm-6 form-group">
                <?php if (!empty($editRecord[0]['logo_img'])) { ?>
                            <div class="col-lg-6"><img class="img-responsive thumbnail" src="<?php echo base_url('uploads/company') . '/' . $editRecord[0]['logo_img']; ?>">
                            </div>
<?php } ?>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
<?php if ($formAction == "insertData") { ?>
                    <div class="text-center">
                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?= lang('create_company') ?>" />
                    </div>
<?php } else { ?>
                    <div class="text-center">
                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?= lang('update_company') ?>" />
                    </div>
<?php } ?>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#add_company').parsley();

        $('.chosen-select').chosen();

        window.ParsleyValidator
                .addValidator('fileextension', function (value, requirement) {
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

                }, 32)
                .addMessage('en', 'fileextension', '<?php echo lang('MSG_UPLOAD'); ?>');

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
    });

</script>

<script>
    $(document).ready(function () {
        $('#from_model').parsley();
        $("input[name='primary_contact[]']:checked").val('1');
        var cls = $("input[name='primary_contact[]']:checked").attr('id');
        $('.' + cls).val('');
    });
    $(function () {
        var availableBranch = [
<?php
if (isset($branch_data) && count($branch_data) > 0) {
    $count = 0;
    foreach ($branch_data as $branch) {
        $count++;
        echo '"' . addslashes($branch['branch_name']) . '"';
        if ($count != count($branch_data)) {
            echo ", ";
        }
    }
}
?>
        ];
        $("#branch_id").autocomplete({
            source: availableBranch
        });
    });

    function api_call()
    {
        var country = $('#country_id').find(':selected').attr("data-taxincluded-amount");
        $("#company_name").autocomplete( {
            width: 260,
            matchContains: true,
            selectFirst: false,
            max:15,
            minLength: 3,
            source:'<?php echo base_url();?>GetApiData?country_id='+country,
            search: function(){
                $("#company_name").siblings().addClass("bd-input-load");
            },
            response: function (event, ui) {
                if (ui.content.length === 0) {
                    $(".bd-input-load").removeClass("bd-input-load");
                }
            },
            select: function( event , ui ) {
                $(".bd-input-load").removeClass("bd-input-load");
                $("#company_id_data").val(ui.item.id);
                $("#address1").val(ui.item.address);
                $("#postal_code").val(ui.item.zipcode);
                $("#city").val(ui.item.city);
                $("#com_reg_number").val(ui.item.reg_number);
                $("#com_est_number").val(ui.item.est_number);
            }
        });
    }
    <?php if (!empty($editRecord[0]['country_id'])) { ?>
    api_call();
    <?php } ?>
</script>