<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$this->load->view('CampaignReport/CampaignReportAjax.php','',true);
?>

<div class="row">
    <div class="col-md-6 col-md-6">
        <?php echo $this->breadcrumbs->show(); ?>
    </div>
</div>
<div class="clr"></div>
<div class="row">
    <div class="col-xs-6 col-md-6">
        <h3 class="white-link">
            <?= lang('MY_PROFILE') ?>
        </h3>

    </div>
    <div class="clr"></div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12"> <?php echo $this->session->flashdata('msg'); ?>
        <div class="whitebox pad-10">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <form action="<?php echo base_url(); ?>MyProfile/updateProfile" name="update_myprofile" id="update_myprofile" data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate>
                        <div class="row">
                            <div class="col-xs-12 col-md-4 col-sm-4">
                                <div class="row">
                                    <div class="col-md-4 col-sm-3">

                                        <div class="form-group">
                                            <label for="salution list"><?php echo lang('SALUTION_PREFIX'); ?>*</label>
                                            <select class="chosen-select form-control" placeholder="<?php echo lang('SALUTION_PREFIX'); ?>" data-parsley-errors-container="#salution-errors"  name="salutions_prefix" id="salutions_prefix" required>
                                                <option value=""><?= $this->lang->line('SALUTION_PREFIX_SELECT') ?></option>
                                                <?php $salutions_id = $profile_data['salution_prefix']; ?>
                                                <?php foreach ($salution_list as $row) {
                                                    if ($salutions_id == $row['s_id']) {
                                                        ?>
                                                        <option selected value="<?php echo $row['s_id']; ?>"><?php echo $row['s_name']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $row['s_id']; ?>"><?php echo $row['s_name']; ?></option>

                                                    <?php }
                                                } ?>
                                            </select>
                                            <div id="salution-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-sm-9">

                                        <div class="form-group">
                                            <label for="fname"><?php echo lang('FIRST_NAME'); ?>*</label>
                                            <input class="form-control" name="fname" placeholder="<?php echo lang('FIRST_NAME'); ?>" type="text" value="<?php if ($profile_data['firstname'] != '') {
                                                    echo htmlentities($profile_data['firstname']);
                                                } ?>" data-parsley-pattern="/^([^0-9]*)$/" required="" data-parsley-id="4">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label for="lname"><?php echo lang('LAST_NAME'); ?>*</label>
                                    <input class="form-control" name="lname" placeholder="<?php echo lang('LAST_NAME'); ?>" type="text" value="<?php if ($profile_data['lastname'] != '') {
                                                    echo htmlentities($profile_data['lastname']);
                                                } ?>" data-parsley-pattern="/^([^0-9]*)$/" required="" data-parsley-id="6">
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label for="email"><?php echo lang('EMAIL'); ?></label>
                                    <input class="form-control" name="email" placeholder="Email" data-parsley-trigger="change" required="" type="email" disabled="true" value="<?php if ($profile_data['email'] != '') {
                                                    echo $profile_data['email'];
                                                } ?>" data-parsley-id="8">
                                </div>
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>

                        <div class="row">
                            <div class="col-xs-12 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label for="address"><?php echo lang('ADDRESS_1'); ?>*</label>
                                    <input type="text" class="form-control" name="address" placeholder="<?php echo lang('ADDRESS_1'); ?>" required value="<?php if ($profile_data['address'] != '') {
                                                    echo htmlentities($profile_data['address']);
                                                } ?>" data-parsley-id="14"/>
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label for="address"><?php echo lang('ADDRESS_2'); ?></label>
                                    <input type="text" class="form-control" name="address_1" placeholder="<?php echo lang('ADDRESS_2'); ?>" value="<?php if ($profile_data['address_1'] != '') {
                                                    echo htmlentities($profile_data['address_1']);
                                                } ?>" data-parsley-id="14"/>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label for="city"><?php echo lang('MY_PROFILE_CITY'); ?>*</label>
                                    <input type="text" class="form-control" name="profile_city" id="profile_city" placeholder="<?php echo lang('MY_PROFILE_CITY'); ?>" value="<?php if ($profile_data['city'] != '') {
                                                    echo htmlentities($profile_data['city']);
                                                } ?>" data-parsley-id="14" required/>
                                </div>
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                        <div class="row">


                            <div class="col-xs-12 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label for="state"><?php echo lang('MY_PROFILE_STATE'); ?></label>
                                    <input type="text" class="form-control" name="profile_state" id="profile_state" placeholder="<?php echo lang('MY_PROFILE_STATE'); ?>" value="<?php if ($profile_data['state'] != '') {
                                                    echo htmlentities($profile_data['state']);
                                                } ?>" data-parsley-id="14"/>
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label for="pincode"><?php echo lang('MY_PROFILE_PINCODE'); ?>*</label>
                                    <input type="text" class="form-control" name="profile_pincode" id="profile_pincode" placeholder="<?php echo lang('MY_PROFILE_PINCODE'); ?>" value="<?php if ($profile_data['pincode'] != '') {
                                            echo htmlentities($profile_data['pincode']);
                                        } ?>" data-parsley-id="14" required/>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label for="country"><?php echo lang('MY_PROFILE_COUNTRY'); ?>*</label>
                                    <select class="chosen-select form-control" placeholder="country"  name="country_id" id="country_id" required>
                                        <option value=""><?= $this->lang->line('select_country') ?></option>
<?php $country_id = $profile_data['country']; ?>
<?php foreach ($country_data as $row) {
    if ($country_id == $row['country_id']) {
        ?>
                                                <option selected value="<?php echo $row['country_id']; ?>"><?php echo $row['country_name']; ?></option>
    <?php } else { ?>
                                                <option value="<?php echo $row['country_id']; ?>"><?php echo $row['country_name']; ?></option>

    <?php }
} ?>
                                    </select>
                                </div>
                            </div>
                            <div class="clr"></div>
                        </div>

                        <div class="clr"></div>


                        <div class="row">
                            <div class="col-xs-12 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label for="telephone1"><?php echo lang('TELEPHONE_1'); ?>*</label>
                                    <input class="form-control" name="telephone1" placeholder="<?php echo lang('TELEPHONE_1'); ?>" type="text" value="<?php if ($profile_data['telephone1'] != '') {
    echo $profile_data['telephone1'];
} ?>" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" maxlength="25" required="" data-parsley-id="16">
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label for="telephone2"><?php echo lang('TELEPHONE_2'); ?></label>
                                    <input class="form-control" name="telephone2" placeholder="<?php echo lang('TELEPHONE_2'); ?>" type="text" value="<?php if ($profile_data['telephone2'] != '') {
    echo $profile_data['telephone2'];
} ?>" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-id="18">
                                </div>
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                        <div class="row">
                            <div class="col-xs-12 col-md-12 col-sm-4">
                                <div class="form-group row">
                                    <label for="profile_pic" class="col-xs-12"><?php echo lang('PROFILE_PIC'); ?></label>
                                    
                                  <div class="col-xs-12"> <label class="custom-upload btn btn-blue mar-tp0"> <?php echo lang('upload'); ?><input type="file" class="form-control" name="profile_photo" id="profile_photo" data-parsley-fileextension='png|jpeg|jpg|JPG|PNG|JPEG' data-parsley-max-file-size="2000" placeholder="Profile Pic"></label></div>
                                </div>
                            </div>
                            <div class="col-xs-4 col-md-2 col-sm-4">
                                <div class="form-group">
<?php
$profileURL =FCPATH . PROFILE_PIC_UPLOAD_PATH . "/" . $profile_data['profile_photo'];
if ($profile_data['profile_photo'] && file_exists($profileURL)) {
    ?>
                                       <!-- <label for="profile_pic"><?php echo lang('PROFILE_PIC'); ?></label>-->
                                        <div class="clr"></div>
                                        <img class="img-responsive" src="<?php echo base_url() . PROFILE_PIC_UPLOAD_PATH . "/" . $profile_data['profile_photo']; ?>"/>
<?php }else{ ?>
                                        <div class="clr"></div>
                                        <img class="img-responsive" src="<?php echo base_url() . "uploads/profile_photo/noimage.jpg"; ?>"/>
<?php } ?>
                                </div>
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                        <center>
                            <input type="submit" class="btn btn-lg btn-green" name="submit_btn" id="submit_btn" value="<?php echo lang('EST_EDIT_SAVE');?>">
                        </center>
                    </form>
                </div>
                <div class="clr"></div>
            </div>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
</div>
<script>

    $(document).ready(function () {

        $('.chosen-select').chosen();
    });
</script>
<script>
    $(document).ready(function () {
        window.ParsleyValidator
                .addValidator('fileextension', function (value, requirement) {
                    // the value contains the file path, so we can pop the extension
                    var fileExtension = value.split('.').pop();
                    var multipleFileType = requirement.split('|');
                   
                    if ($.inArray(fileExtension, multipleFileType) != -1)
                    {
                        return true;
                    }else
                    {
                        return false;
                    }
                    
                }, 32)
                .addMessage('en', 'fileextension', '<?php echo lang('MSG_UPLOAD_PROFILE_PIC');?>');

        $("#update_myprofile").parsley();

        /*
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
        }); */
    });
</script>
