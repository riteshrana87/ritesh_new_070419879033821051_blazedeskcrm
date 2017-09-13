<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$formAction = 'insertdata';
$path = $lead_view . '/' . $formAction;
?>

<div class="modal-dialog modal-lg">
    <?php
    $attributes = array("name" => "add_lead", "id" => "add_lead", 'data-parsley-validate' => "");
    echo form_open_multipart($path, $attributes);
    ?>
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                <div class="modelTitle">
                    <?= $modal_title ?>
                </div>
            </h4>   
        </div>
        <div class="modal-body">
            <div class = " row">
                <div class = "col-sm-7 form-group">
                    <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                    <input type="text" id="redirect_link" name="redirect_link"  hidden="" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
                    <input type="text" id="lead_id" name="lead_id"  hidden="" value="<?php
                    if (!empty($edit_record[0]['lead_id'])) {
                        echo $edit_record[0]['lead_id'];
                    }
                    ?>">
                    <input type="text" class="form-control" placeholder="<?= $this->lang->line('lead_name') ?> *" id="lead_name" name="lead_name" value="<?php
                    if (!empty($edit_record[0]['prospect_name'])) {
                        echo htmlentities(stripslashes($edit_record[0]['prospect_name']));
                    }
                    ?>" required tabindex="1">
                </div>
                <div class = "col-sm-5 form-group">
                    <input type="text" class="form-control" id="prospect_auto_id" name="prospect_auto_id"  value="<?php
                    if (!empty($edit_record[0]['prospect_auto_id'])) {
                        echo $edit_record[0]['prospect_auto_id'];
                    } else {
                        echo $pros_auto_id;
                    }
                    ?>" readonly>
                </div>
            </div>
            <div class = " row">
                <div class = "col-sm-7 form-group">
                    <select name="country_id" onchange="api_call();" class="form-control chosen-select" id="country_id" data-placeholder=" <?= $this->lang->line('select_country') ?> *" tabindex="2" required data-parsley-errors-container="#country-errors">
                        <option value="">
                            <?= $this->lang->line('select_country') ?> *
                        </option>
                        <?php if (isset($country_data) && count($country_data) > 0) { ?>
                            <?php foreach ($country_data as $country_data) { ?>
                                <option data-taxincluded-amount="<?php echo $country_data['country_code']; ?>" value="<?php echo $country_data['country_id']; ?>" <?php
                                if (!empty($edit_record[0]['country_id']) && $edit_record[0]['country_id'] == $country_data['country_id']) {
                                    echo 'selected';
                                }
                                ?>><?php echo $country_data['country_name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                    </select>
                    <div id="country-errors"></div>

                </div>
                <div class = "col-sm-5 form-group">
                    <select name="company_id"  class="form-control chosen-select" onchange="return company_show('#second_time_hide', this.value);" id="company_id" tabindex="3" required data-parsley-errors-container="#company-errors">
                        <option value="">
                            <?= $this->lang->line('select_company') ?> *
                        </option>
                        <option value="add_another"><?= lang('add_new_company') ?></option>
                        <?php if (isset($company_data) && count($company_data) > 0) { ?>
                            <?php foreach ($company_data as $company_data) { ?>
                                <option value="<?php echo $company_data['company_id']; ?>" <?php
                                if (!empty($edit_record[0]['company_id']) && $edit_record[0]['company_id'] == $company_data['company_id']) {
                                    echo 'selected';
                                }
                                ?>><?php echo $company_data['company_name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                    </select>
                    <div id="company-errors"></div>
                    <input type="hidden" name="com_reg_number" id="com_reg_number" value="">
                    <input type="hidden" name="company_id_data" id="company_id_data" value="">

                </div>
            </div>
            <div class = " row" id="second_time_hide">
                <div class = "col-sm-3 form-group">
                    <input type="text" class="form-control " placeholder="<?= $this->lang->line('company_name') ?> *" id="company_name" name="company_name"><span></span>
                </div>
                <div class = "col-sm-3 form-group">
                    <input type="email" class="form-control" data-parsley-trigger="change" placeholder="<?= $this->lang->line('email_id_company') ?> *" id="email_id_company" name="email_id_company">
                </div>
                <div class = "col-sm-3 form-group">
                    <input  class="form-control" placeholder="<?= $this->lang->line('website') ?>" id="website" name="website" type="url" data-parsley-trigger="change" >
                </div>
                <div class = "col-sm-3 form-group">
                    <input type="text" class="form-control" placeholder="<?= $this->lang->line('phone_no_company') ?> *" id="phone_no_company" name="phone_no_company" data-parsley-pattern='^[\d\+\-\.\(\)\/\s]*$' maxlength="25">
                </div>
                <div class="col-sm-12 form-group">
                    <label class="custom-upload btn btn-primary"><?= $this->lang->line('logo_image') ?>
                        <input type="file" class="form-control" name="logo_image"  id="logo_image" onchange="$('#logo_image_txt').html($('#logo_image').val().split('\\').pop());" placeholder="<?= $this->lang->line('logo_image') ?>" value="<?= !empty($edit_record[0]['logo_img']) ? $edit_record[0]['logo_img'] : '' ?>" data-parsley-fileextension='png|jpeg|jpg|JPG|PNG|JPEG' data-parsley-max-file-size="2000" data-parsley-errors-container="#logo_image_errors"/>
                    </label>
                    <p id="logo_image_txt"></p>
                    <p id="logo_image_errors"></p>
                </div>
                <div class="col-sm-6 form-group">
                    <?php if (!empty($edit_record[0]['logo_img'])) { ?>
                        <div class="col-lg-6"><img class="img-responsive thumbnail" src="<?php echo base_url('uploads/company') . '/' . $edit_record[0]['logo_img']; ?>">
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class = " row">
                <div class = "col-sm-7 form-group">
                    <input type="text" class="form-control" placeholder="<?= $this->lang->line('address1') ?>" id="address1" name="address1" value="<?php
                    if (!empty($edit_record[0]['address1'])) {
                        echo htmlentities(stripslashes($edit_record[0]['address1']));
                    }
                    ?>" tabindex="4">
                </div>
                <div class = "col-sm-5 form-group">
                    <div class="input-group date" id="creation_date">
                        <input type="text" class="form-control creation_date" tabindex="10" placeholder="<?= $this->lang->line('creation_date') ?>" id="creation_date" name="creation_date"  onkeydown="return false" value="<?php
                        if (!empty($edit_record[0]['creation_date']) && $edit_record[0]['creation_date'] != '0000-00-00') {
                            echo configDateTime($edit_record[0]['creation_date']);
                        } else {
                            echo date("m/d/Y");
                        }
                        ?>">
                        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                </div>
            </div>
            <div class = " row">
                <div class = "col-sm-7 form-group">
                    <input type="text" class="form-control" placeholder="<?= $this->lang->line('address2') ?>" id="address2" name="address2" value="<?php
                    if (!empty($edit_record[0]['address2'])) {
                        echo htmlentities(stripslashes($edit_record[0]['address2']));
                    }
                    ?>" tabindex="5">
                </div>
                <div class = "col-sm-5 form-group">
                    <select name="language_id" class="form-control chosen-select" data-placeholder="<?= $this->lang->line('language_not_filled') ?>" id='language_id' tabindex="11">
                        <option value=""><?= $this->lang->line('language_not_filled') ?></option>
                        <?php if (isset($language_data) && count($language_data) > 0) { ?>
                            <?php foreach ($language_data as $language) { ?>
                                <option value="<?php echo $language['language_id']; ?>" <?php
                                if (!empty($edit_record[0]['language_id']) && $edit_record[0]['language_id'] == $language['language_id']) {
                                    echo 'selected';
                                }
                                ?>><?php echo $language['language_name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                    </select>
                </div>
            </div>
            <div class = " row">
                <div class = "col-xs-12 col-sm-7 col-md-7 ">
                    <div class="row">
                        <div class = "col-xs-12 col-sm-6 col-md-6 form-group">
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('postal_code') ?>" id="postal_code" name="postal_code" value="<?php
                            if (!empty($edit_record[0]['postal_code'])) {
                                echo $edit_record[0]['postal_code'];
                            }
                            ?>" tabindex="6">
                        </div>
                        <div class = "col-xs-12 col-sm-6 col-md-6 form-group">
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('city') ?>" id="city" name="city" value="<?php
                            if (!empty($edit_record[0]['city'])) {
                                echo htmlentities(stripslashes($edit_record[0]['city']));
                            }
                            ?>" tabindex="7">
                        </div>
                        <div class="clr"></div>
                    </div>
                </div>
                <div class = "col-xs-12 col-sm-5 col-md-5 form-group">                      
                    <div class="ui-widget">
                        <input type="text" id="branch_id" name="branch_id" class="form-control" tabindex="12" placeholder="<?= $this->lang->line('branche') ?>" value="<?php
                        if (!empty($branch_data1[0]['branch_name'])) {
                            echo htmlentities(stripslashes($branch_data1[0]['branch_name']));
                        } else {
                            echo '';
                        }
                        ?>">
                    </div>
                </div>
            </div>
            <div class = " row">
                <div class = "col-xs-12 col-sm-7 col-md-7 ">
                    <div class="row">
                        <div class = "col-xs-12 col-sm-6 col-md-6 form-group">
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('state') ?>" id="state" name="state" value="<?php
                            if (!empty($edit_record[0]['state'])) {
                                echo htmlentities(stripslashes($edit_record[0]['state']));
                            }
                            ?>" tabindex="8">
                        </div>
                        <div class = "col-xs-12 col-sm-6 col-md-6 form-group">
                            <select name="prospect_owner_id" class="form-control chosen-select" id="prospect_owner_id" tabindex="9">
                                <option value="">
                                    <?= $this->lang->line('select_lead_owner') ?>
                                </option>
                                <?php if (isset($prospect_owner) && count($prospect_owner) > 0) { ?>
                                    <?php foreach ($prospect_owner as $prospect) { ?>
                                        <option value="<?php echo $prospect['login_id']; ?>" <?php
                                        if (!empty($edit_record[0]['prospect_owner_id']) && $edit_record[0]['prospect_owner_id'] == $prospect['login_id']) {
                                            echo 'selected';
                                        }
                                        ?>><?php echo ucfirst($prospect['firstname']) . " " . ucfirst($prospect['lastname']); ?></option>
                                            <?php } ?>
                                        <?php } ?>
                            </select>

                        </div>
                        <div class="clr"></div>
                    </div>
                </div>
                <div class = "col-sm-5 form-group">
                    <select name="estimate_prospect_worth" class="form-control chosen-select" id="estimate_prospect_worth" tabindex="13" data-parsley-errors-container="#estimate-errors" data-placeholder="<?php echo $this->lang->line('select_estimate'); ?>">
                        <option value="">
                            <?= $this->lang->line('select_estimate') ?>
                        </option>
                        <?php if (isset($EstimateArray) && count($EstimateArray) > 0) { ?>
                            <?php foreach ($EstimateArray as $result) { ?>
                                <option value="<?php echo $result['estimate_id']; ?>" <?php
                                if (!empty($edit_record[0]['estimate_prospect_worth']) && $edit_record[0]['estimate_prospect_worth'] == $result['estimate_id']) {
                                    echo 'selected';
                                }
                                ?>><?php echo $result['subject']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                    </select>
                    <div id="estimate-errors"></div>

                </div>
                <div class = "col-xs-12 col-sm-5"> </div>
            </div>
            <div class = "row">
                <div class = "col-xs-12 col-sm-6 col-md-6 form-group">
                    <input type="url" name="fb" class="form-control" id="fb" placeholder="<?= $this->lang->line('URL_FACEBOOK') ?>" tabindex="14" value="<?= !empty($edit_record[0]['fb']) ? $edit_record[0]['fb'] : '' ?>" data-parsley-trigger="change"/>
                </div>
                <div class = "col-xs-12 col-sm-6 col-md-6 form-group">
                    <input type="url" name="linkedin" class="form-control" id="linkedin" placeholder="<?= $this->lang->line('URL_LINKEDIN') ?>" tabindex="15" value="<?= !empty($edit_record[0]['linkedin']) ? $edit_record[0]['linkedin'] : '' ?>" data-parsley-trigger="change"/>
                </div>
            </div>
            <div class = "row">
                <div class = "col-xs-12 col-sm-6 col-md-6 form-group">
                    <input type="url" name="twitter" class="form-control" id="twitter" placeholder="<?= $this->lang->line('URL_TWITTER') ?>" tabindex="16" value="<?= !empty($edit_record[0]['twitter']) ? $edit_record[0]['twitter'] : '' ?>" data-parsley-trigger="change"/>
                </div>
                <div class = "col-xs-12 col-sm-6 col-md-6 form-group"></div>
            </div>
            <div class = "row">
                <div class = "col-xs-12 col-sm-6 col-md-6">

                    <div class="row">
                        <div class = "col-xs-12 col-sm-6 col-md-6 form-group">
                            <select name="number_type1" class="form-control chosen-select" id='number_type1' tabindex="17">
                                <option value="">
                                    <?= $this->lang->line('select_number_type') ?>
                                </option>
                                <option value="1" <?php
                                if (!empty($edit_record[0]['number_type1']) && $edit_record[0]['number_type1'] == '1') {
                                    echo "selected='selected'";
                                }
                                ?>>
                                            <?= $this->lang->line('home') ?>
                                </option>

                                <option value="2" <?php
                                if (!empty($edit_record[0]['number_type1']) && ($edit_record[0]['number_type1'] == '2')) {
                                    echo 'selected=selected';
                                }
                                ?>>
                                            <?= $this->lang->line('mobile') ?>
                                </option>
                                <option value="3" <?php
                                if (!empty($edit_record[0]['number_type1']) && ($edit_record[0]['number_type1'] == '3')) {
                                    echo 'selected=selected';
                                }
                                ?>>
                                            <?= $this->lang->line('office') ?>
                                </option>
                            </select>
                        </div>
                        <div class = "col-xs-12 col-sm-6 col-md-6 form-group">
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('number') ?>" id="phone_no1_lead" name="phone_no1_lead"  data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" maxlength="25" value="<?php
                            if (!empty($edit_record[0]['phone_no'])) {
                                echo $edit_record[0]['phone_no'];
                            }
                            ?>" tabindex="18">
                        </div>
                    </div> 
                </div>
                <div class = " row">
                    <div class = "col-xs-12 col-sm-6 col-md-6">
                        <div class="">
                            <div class = "col-xs-12 col-sm-6 col-md-6 form-group">
                                <select name="number_type2" class="form-control chosen-select" id='number_type2' tabindex="19">
                                    <option value="">
                                        <?= $this->lang->line('select_number_type') ?>
                                    </option>
                                    <option value="1" <?php
                                    if (!empty($edit_record[0]['number_type2']) && $edit_record[0]['number_type2'] == '1') {
                                        echo "selected='selected'";
                                    }
                                    ?>>
                                                <?= $this->lang->line('home') ?>
                                    </option>
                                    <option value="2" <?php
                                    if (!empty($edit_record[0]['number_type2']) && ($edit_record[0]['number_type2'] == '2')) {
                                        echo 'selected=selected';
                                    }
                                    ?>>
                                                <?= $this->lang->line('mobile') ?>
                                    </option>
                                    <option value="3" <?php
                                    if (!empty($edit_record[0]['number_type2']) && ($edit_record[0]['number_type2'] == '3')) {
                                        echo 'selected=selected';
                                    }
                                    ?>>
                                                <?= $this->lang->line('office') ?>
                                    </option>
                                </select>
                            </div>
                            <div class = "col-xs-12 col-sm-6 col-md-6 form-group">
                                <input type="text" class="form-control" placeholder=" <?= $this->lang->line('number') ?>" id="phone_no2_lead" name="phone_no2_lead" data-parsley-pattern='^[\d\+\-\.\(\)\/\s]*$' maxlength="25" value="<?php
                                if (!empty($edit_record[0]['phone_no2'])) {
                                    echo $edit_record[0]['phone_no2'];
                                }
                                ?>" tabindex="20">
                            </div>
                        </div>

                    </div>
                </div>
                <div id="add_more" class=" add_contacts">
                    <?php
                    $delete_count = 0;
                    if (!empty($contact_data)) {
                        foreach ($contact_data as $contact) {
                            ?>
                            <div class = "row contacts" id="add_contact<?php echo $contact['contact_id']; ?>">

                                <div class = "col-sm-2 col-lg-2 text-right pad-t6 txt-left-resp form-group">
                                    <input type="hidden" name="contact_id[]" id="contact_id" value="<?php
                                    if (!empty($contact['contact_id'])) {
                                        echo $contact['contact_id'];
                                    }
                                    ?>">

                                    <input name='primary_contact[]'  type='hidden' id="primary_def<?php echo $delete_count; ?>" class="defprimary primary<?php echo $delete_count; ?>" value='0'>
                                    <input name="primary_contact[]" required data-parsley-errors-container="#primary-errors" type="radio" value="1" id="primary<?php echo $delete_count; ?>" <?php
                                    if (!empty($contact['primary_contact']) && $contact['primary_contact'] == 1) {
                                        echo 'checked=checked';
                                    }
                                    ?>>
                                   
                                    <label class="radio-inline"> <?= $this->lang->line('primary') ?> *</label>
                                     <div id="primary-errors"></div>
                                </div>
                                <div class = "col-sm-3 form-group">
                                    <input type="text" class="form-control" placeholder="<?= $this->lang->line('contact_name') ?> *" name="contact_name[]" id="contact_name<?php echo $delete_count; ?>" value="<?php
                                    if (!empty($contact['contact_name'])) {
                                        echo htmlentities(stripslashes($contact['contact_name']));
                                    }
                                    ?>" required>
                                </div>
                                <div class = "col-sm-3 form-group">
                                    <input type="email" class="form-control" data-parsley-trigger="change" placeholder="<?= $this->lang->line('email_address') ?> *" name="email_id[]" onchange='validateContactUniqueness(this.value, "<?php echo $delete_count; ?>")' id="email_id<?php echo $delete_count; ?>" value="<?php
                                    if (!empty($contact['email'])) {
                                        echo $contact['email'];
                                    }
                                    ?>" required>
                                </div>
                                <div class = "col-sm-3 form-group">

                                    <input type="text" class="form-control" placeholder="<?= $this->lang->line('phone_no') ?> *" name="phone_no[]" id="phone_no<?php echo $delete_count; ?>" data-parsley-pattern='^[\d\+\-\.\(\)\/\s]*$' maxlength="25"  value="<?php
                                    if (!empty($contact['mobile_number'])) {
                                        echo $contact['mobile_number'];
                                    }
                                    ?>" required>
                                </div>
                                <div class = "bd-error"> 
                                    <a class="delcontacts" href="javascript:;" title="<?= $this->lang->line('delete') ?>"  data-id="<?php
                                    if (!empty($contact['contact_id'])) {
                                        echo $contact['contact_id'];
                                    }
                                    ?>" data-path="<?php echo $path; ?>">
                                        <i class="fa fa-remove fa-x redcol"></i> </a> </div>
        <!--                                <div class = "col-sm-1  pad-top10"> <a id='delete_row<?php echo $delete_count; ?>' class='' onclick="delete_row_contact('<?php echo $delete_count; ?>', '<?php
                                if (!empty($contact['contact_id'])) {
                                    echo $contact['contact_id'];
                                }
                                ?>')">  <i class="fa fa-remove fa-x redcol"></i> </a> </div>-->
                            </div>
                            <?php
                            $delete_count++;
                        }
                    }
                    ?>

                </div>
                <div id="deletedContactsDiv"></div>
                <div class="form-group col-xs-12"> <a id="add_row" class="btn btn-primary align-center" tabindex="21"> <span class="glyphicon glyphicon-plus"></span>
                        <?= $this->lang->line('add_another_contact') ?>
                    </a> </div>
                <div class = " ">
                    <div class = "col-xs-12 col-sm-7 col-md-7 form-group">
                        <?php
                        if (!empty($edit_record[0]['prospect_generate'])) {
                            $campaign_id = "prospect_generate_show";
                        } else {
                            $campaign_id = "prospect_generate_hide";
                        }
                        ?>
                       <div class="pull-left"> <input <?php if (!empty($edit_record[0]['prospect_generate'])) { ?>checked="checked"<?php } ?> data-toggle="toggle" data-onstyle="success" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>" type="checkbox"  id="prospect_generate" name="prospect_generate" onChange="toggle_show(<?php echo "'#" . $campaign_id . "'"; ?>, this)" tabindex="22"/></div>
                       <div class="bd-form-group col-sm-8 col-xs-9"> <label >
                            <?= $this->lang->line('prospect_generated_by_marketing_campaign?') ?>
                        </label></div>
                        <div class="clr"></div>
                    </div>
                    <div class = "col-xs-12 col-sm-5 col-md-5 form-group" id="<?php echo $campaign_id; ?>">
                        <select name="campaign_id" class="form-control chosen-select" id="campaign_id" data-parsley-errors-container="#campaign-errors" data-parsley-trigger="change" tabindex="23">
                            <option value="">
                                <?= $this->lang->line('select_campaign') ?> *
                            </option>
                            <?php if (isset($campaign) && count($campaign) > 0) { ?>
                                <?php foreach ($campaign as $result) { ?>
                                    <option value="<?php echo $result['campaign_id']; ?>" <?php
                                    if (!empty($edit_record[0]['campaign_id']) && $edit_record[0]['campaign_id'] == $result['campaign_id']) {
                                        echo 'selected';
                                    }
                                    ?>><?php echo $result['campaign_name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                        </select>
                        <div id="campaign-errors"></div>
                    </div>
                </div>

                <div class = " ">
                    <div class = "col-xs-12 col-sm-7 col-md-7 form-group">
                        <label for='interested_products[]'>
                            <?= $this->lang->line('interested_products/services') ?>
                        </label>

                        <select multiple="true" name="interested_products[]" id="interested_products" data-placeholder="<?= lang('select_product'); ?>" class="chosen-select" tabindex="24">
                            <?php if (isset($product_data) && count($product_data) > 0) { ?>
                                <?php foreach ($product_data as $product) { ?>
                                    <option value="<?php echo $product['product_id']; ?>" <?php
                                    if (!empty($opportunity_product_data) && in_array($product['product_id'], $opportunity_product_data)) {
                                        echo 'selected="selected"';
                                    }
                                    ?>><?php echo $product['product_name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                        </select>

                    </div>
                    <div class = "col-xs-12 col-sm-5 col-md-5 form-group">
                        <label for='contact_date'>
                            <?= $this->lang->line('contact_date') ?>
                        </label>
                        <div class="input-group date" id="contact_date">
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('contact_date') ?>" id="contact_date" name="contact_date" onkeydown="return false" value="<?php
                            if (!empty($edit_record[0]['contact_date']) && $edit_record[0]['contact_date'] != '0000-00-00') {
                                echo configDateTime($edit_record[0]['contact_date']);
                            }
                            ?>" tabindex="25">
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                    </div>
                </div>

                <div class = " ">
                    <div class = "col-xs-12 col-sm-7 col-md-7 form-group">
                        <label> <?= $this->lang->line('description') ?> *</label>
                        <textarea class="form-control" rows="4"  name="description" id="description" tabindex="26"> <?php
                            if (!empty($edit_record[0]['description'])) {
                                echo $edit_record[0]['description'];
                            }
                            ?></textarea>
                        <ul class="parsley-errors-list filled hidden" id="descriptionError" ><li class="parsley-required"><?= $this->lang->line('EST_ADD_LABEL_REQUIRED_FIELD') ?></li></ul>
                    </div>
                    <div class = "col-xs-12 col-sm-5 col-md-5 form-group">
                        <div class="form-group">
                            <div class="mediaGalleryDiv">

                                <button type="button" name="gallery" id="gallery-btn" data-href="<?php echo $url; ?>"
                                        class="btn btn-primary "><?= lang('cost_placeholder_uploadlib') ?></button>
                                <div class="mediaGalleryImg">

                                </div>

                            </div>
                        </div>
                        <!--          <?= $this->lang->line('add_a_file') ?>
                                  <input type="file" title="Add a File" class="file" id="prospect_files" name="prospect_files[]" multiple>-->
                        <!-- new code-->
                        <div class="col-xs-12 col-md-12 no-right-pad bd-dragimage">
                            <div id="dragAndDropFiles" class="uploadArea uploadarea-sm bd-dragimage">
                                <div class="image_part">
                                    <label name="prospect_files[]">
                                        <h1 style="top: -162px;">
                                            <i class="fa fa-cloud-upload"></i>
                                            <?= lang('DROP_IMAGES_HERE') ?>
                                        </h1>
                                        <input type="file" onchange="showimagepreview(this)" name="prospect_files[]" style="display: none" id="upl" multiple />
                                    </label>
                                </div>
                                <?php
                                if (!empty($prospect_files)) {
                                    if (count($prospect_files) > 0) {
//                                $file_img = $campaign_data[0]['file'];
//                                $img_data = explode(',', $file_img);
                                        $i = 15482564;
                                        foreach ($prospect_files as $image) {
                                            $path = $image['file_path'];
                                            $name = $image['file_name'];

                                            $arr_list = explode('.', $name);

                                            $arr = $arr_list[1];
                                            if (file_exists($path . '/' . $name)) {
                                                ?>
                                                <div id="img_<?php echo $image['file_id']; ?>" class="eachImage"> 
                                                    <a class="btn delimg remove_drag_img" href="javascript:;" title="<?= $this->lang->line('delete') ?>" data-name="<?php echo $name; ?>" data-id="img_<?php echo $image['file_id']; ?>" data-path="<?php echo $path; ?>">x</a>
                                                    <span id="<?php echo $i; ?>" class="preview">    
                                                        <a href='<?php echo base_url($lead_view . '/download/' . $image['file_id']); ?>' target="_blank">
                                                            <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                                                                <img src="<?= base_url($path . '/' . $name); ?>"  width="75"/>
                                                            <?php } else { ?>
                                                                <div class="image_ext"><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png"  width="75"/>
                                                                    <p class="img_show"><?php echo $arr; ?></p>
                                                                </div>
                                                            <?php } ?>
                                                        </a>
                                                        <p class="img_name"><?php echo $name; ?></p>
                                                        <span class="overlay" style="display: none;"> <span class="updone">100%</span></span>
                                                        <!--<input type="hidden" value="<?php echo $name; ?>" name="fileToUpload[]">-->
                                                    </span> </div>
                                            <?php } ?>
                                            <?php
                                            $i++;
                                        }
                                        ?>
                                        <div id="deletedImagesDiv"></div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <div class="clr"> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <center>
                    <input type="submit"  class="btn btn-primary" name="lead_submit" id="lead_submit_btn" value="<?= $submit_button_title ?>" />
                </center>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?> </div>
<div class="modal fade modal-image" id="modalGallery" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onClick="$('#modalGallery').modal('hide');" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo lang('uploads'); ?></h4>
            </div>
            <div class="modal-body" id="modbdy">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onClick="$('#modalGallery').modal('hide');"><?php echo lang('close'); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="clr"></div>
<script>
    //for scroll to top
    $("div.modal").scrollTop(1);
    //upload from library
    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('#modalGallery').modal('show');
    });

    //editor
    $('#description').summernote({
        disableDragAndDrop: true,
        height: 150, //set editable area's height
        codemirror: {// codemirror options
            theme: 'monokai'
        },
        // focus: true

    });

    //open modal after image upload etc in description
    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

        $('body').addClass('modal-open');
    });
    function stripHTML(text) {
        var regex = /(<([^>]+)>)/ig;
        return text.replace(regex, "");
    }

    //Validation for Description Wysiwyc editor not allow blank space in description
    $('body').delegate('#lead_submit_btn', 'click', function () {
        var wys = $('.note-editable').html();
        var value = wys.replace(/(?:&nbsp;|<br>|<p>|<\/p>)/ig, "");
        var final_value = value.replace(/&nbsp;/g, '');
        final_value = final_value.replace(/^\s+/g, '');

        if (final_value != '') {
            //Validation for Description Wysiwyc editor
            var description = $('#description').code();
            if (description !== '' && description !== '<p><br></p>' && description !== '<br>') {
                $("#descriptionError").addClass("hidden");
            }
        } else {
            $("#descriptionError").removeClass("hidden");
            return false;
        }
        if ($('#add_lead').parsley().isValid()) {
            //disabled submit button after submit
            $('input[type="submit"]').prop('disabled', true);
            $('#add_lead').submit();
        }


    });
    $(document).ready(function () {
        $("#desciptionError").css("display", "none");
        //parsley validation
        $('#add_lead').parsley();
        //validation for file type and file size
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
                .addMessage('en', 'fileextension', '<?php echo lang('MSG_UPLOAD_PROFILE_PIC'); ?>');

        window.Parsley.addValidator('maxFileSize', {
            validateString: function (_value, maxSize, parsleyInstance) {
                if (!window.FormData) {
                    var delete_meg = "<?php echo lang('upgrade_your_browser'); ?>";
                    BootstrapDialog.show(
                            {
                                title: '<?php echo $this->lang->line('Information'); ?>',
                                message: delete_meg,
                                buttons: [{
                                        label: '<?php echo $this->lang->line('ok'); ?>',
                                        action: function (dialog) {
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
        //value for primary contact radio 1
        $("input[name='primary_contact[]']:checked").val('1');
        var cls = $("input[name='primary_contact[]']:checked").attr('id');
        $('.' + cls).val('');

    });

    var lead_id = $('#lead_id').val();
    //add another contact code
    if (lead_id)
    {
        var regex = new RegExp("^[\\d\\+\\-\\.\\(\\)\\/\\s]*$");
        var add_row_no = $('.contacts').length - 1;
        $("#add_row").click(function () {
            add_row_no++;
            $('#add_more').append("<div id=\"add_contact" + add_row_no + "\" class = \" row contacts\"><div class='col-sm-2 col-lg-2 text-right pad-t6 txt-left-resp form-group'><input class='defprimary primary" + add_row_no + "' name='primary_contact[]' id='primary_def" + add_row_no + "'  type='hidden' value='0'><input type='hidden' name='contact_id[]' id='contact_id' value=0><input name='primary_contact[]' required  data-parsley-errors-container='#primary-errors' value=1 type='radio' id='primary" + add_row_no + "'> <label class='radio-inline'> <?= $this->lang->line('primary') ?> * </label><div id='primary-errors'></div></div> <div class = 'col-sm-3 form-group'><input name='contact_name[]' type='text' required placeholder='<?= $this->lang->line('contact_name') ?> *' class='form-control' id='contact_name" + add_row_no + "'/> </div><div class = 'col-sm-3 form-group'><input name='email_id[]' required type='email' placeholder='<?= $this->lang->line('email_address') ?> *' class='form-control email_contact' onchange='validateContactUniqueness(this.value," + add_row_no + ")' data-parsley-trigger='change' id='email_id" + add_row_no + "'/> <ul class='parsley-errors-list filled hidden' id='email_err" + add_row_no + "'><li class='parsley-remote'><?php echo lang('contact_email_error'); ?></li></ul><ul class='parsley-errors-list filled hidden' id='email_err" + add_row_no + "'><li class='parsley-remote'><?php echo lang('contact_email_error'); ?></li></ul></div><div class = 'col-sm-3 form-group'><input name='phone_no[]' required type='text' placeholder='<?= $this->lang->line('phone_no') ?> *'  maxlength='25' class='form-control' data-parsley-pattern='" + regex.source + "' id='phone_no" + add_row_no + "'/> </div><div class = 'bd-error'><a id='delete_row' title='<?= $this->lang->line('delete') ?>'  class='' onclick=\"delete_row_contact(" + add_row_no + ");\"><i class='fa fa-remove fa-x redcol'></i></a></div></div>");
            // data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$"
        });
    }
    else {
        var regex = new RegExp("^[\\d\\+\\-\\.\\(\\)\\/\\s]*$");
        var add_row_no = 0;
        $("#add_row").click(function () {
            add_row_no++;
            $('#add_more').append("<div id=\"add_contact" + add_row_no + "\" class = \" row contacts\"><div class='col-sm-2 col-lg-2 text-right pad-t6 txt-left-resp form-group'><input class='defprimary primary" + add_row_no + "' name='primary_contact[]' id='primary_def" + add_row_no + "'  type='hidden' value='0'> <input type='hidden' name='contact_id[]' id='contact_id' value=0>     <input name='primary_contact[]' required data-parsley-errors-container='#primary-errors'  value=1 type='radio' id='primary" + add_row_no + "'>  <label class='radio-inline'> <?= $this->lang->line('primary') ?> * </label> <div id='primary-errors'></div></div> <div class = 'col-sm-3 col-sm-3 form-group'><input name='contact_name[]' type='text' required placeholder='<?= $this->lang->line('contact_name') ?> *' class='form-control' id='contact_name" + add_row_no + "'/> </div><div class = 'col-sm-3 form-group'><input name='email_id[]' required type='email' placeholder='<?= $this->lang->line('email_address') ?> *' class='form-control email_contact' onchange='validateContactUniqueness(this.value," + add_row_no + ")' data-parsley-trigger='change' id='email_id" + add_row_no + "'/> <ul class='parsley-errors-list filled hidden' id='email_err" + add_row_no + "'><li class='parsley-remote'><?php echo lang('contact_email_error'); ?></li></ul></div><div class = 'col-sm-3 form-group'><input name='phone_no[]' required type='text' placeholder='<?= $this->lang->line('phone_no') ?> *'  maxlength='25' class='form-control' data-parsley-pattern='" + regex.source + "' id='phone_no" + add_row_no + "'/> </div><div class = 'bd-error'><a id='delete_row' title='<?= $this->lang->line('delete') ?>'  class='' onclick=\"delete_row_contact(" + add_row_no + ");\"><i class='fa fa-remove fa-x redcol'></i></a></div></div>");

        });
    }

    $('body').delegate("input[name='primary_contact[]']", 'click', function () {
        var cls = $(this).attr('id');
        $('.defprimary').val(0);
        $('.' + cls).val('');
    });

// add another delete contact save in array code
    $('.delcontacts').on('click', function () {

        var divId = ($(this).attr('data-id'));
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');
        var delete_meg = "<?php echo lang('CONFIRM_DELETE_CONTACT'); ?>";
        BootstrapDialog.show(
                {
                    title: '<?php echo $this->lang->line('Information'); ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
                                $('#deletedContactsDiv').append("<input type='hidden' name='softDeletedContacts[]' value='" + str1 + "'>");
                                $('#add_contact' + divId).remove();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                                dialog.close();
                            }
                        }]
                });

    });

//add another delete contact remove row code
    function delete_row_contact(removeNum) {

        var add_row_no = $('.contacts').length;
        if (add_row_no > 0) {
            //If contact save in contact master then delete from contact master
            var delete_meg = "<?php echo lang('CONFIRM_DELETE_CONTACT'); ?>";
            BootstrapDialog.show(
                    {
                        title: '<?php echo $this->lang->line('Information'); ?>',
                        message: delete_meg,
                        buttons: [{
                                label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                                action: function (dialog) {
                                    dialog.close();
                                    $('#confirm-id').on('hidden.bs.modal', function () {
                                        $('body').addClass('modal-open');
                                    });
                                }
                            }, {
                                label: '<?php echo $this->lang->line('ok'); ?>',
                                action: function (dialog) {
                                    jQuery('#add_contact' + removeNum).remove();
                                    add_row_no--;

                                    $('#confirm-id').on('hidden.bs.modal', function () {
                                        $('body').addClass('modal-open');
                                    });
                                    dialog.close();
                                }
                            }]
                    });


        }
    }

    //creation date code
    $('#creation_date').datepicker({
        autoclose: true
    }).on('changeDate', function (selected) {
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#contact_date').datepicker('setStartDate', startDate);
    });
    //set contact date as per creation date
    var startDate = $('.creation_date').val();
    $('#contact_date').datepicker({
        autoclose: true,
        startDate: startDate
    });

    //toggle button
    $('#prospect_generate').bootstrapToggle();

    //chosen select code
    $('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    $('#interested_products').trigger('chosen:updated');

    //if toggle checked for campaign required field
    function toggle_show(className, obj) {
        var $input = $(obj);

        if ($input.prop('checked')) {
            $(className).show();
            $("#campaign_id").attr("required", "required");
        }
        else {
            $("#campaign_id").removeAttr("required");
            $(className).hide();
        }
    }

    //When change select Box for company then autofill company data 
    function company_show(className, val) {
        //If select add another company make fields required
        if (val == 'add_another') {
            $("#branch_id").attr("placeholder", "<?php echo lang('branche'); ?> *").blur();
            $("#company_name, #email_id_company, #phone_no_company,#branch_id").attr("required", "required");
            $('#phone_number').val('');
            $('#address1').val('');
            $('#address2').val('');
            $('#city').val('');
            $('#state').val('');
            $('#postal_code').val('');
            $('#branch_id').val('');
            $(className).show();
        }
        else {
            var url_contact = '<?php echo base_url() . "Lead/getCompanyDataById" ?>';
            var company_id = val;
            $.ajax({
                type: "POST",
                url: url_contact,
                data: {'company_id': company_id},
                success: function (data)
                {
                    var dataObj = jQuery.parseJSON(data);
                    $('#phone_number').val(dataObj.phone_no);
                    $('#address1').val(dataObj.address1);
                    $('#address2').val(dataObj.address2);
                    $('#city').val(dataObj.city);
                    $('#state').val(dataObj.state);
                    $('#postal_code').val(dataObj.postal_code);
                    $('#country_id').val(dataObj.country_id).trigger("chosen:updated");
                    $('#branch_id').val(dataObj.branch_name);

                    if (dataObj.logo_img != '')
                    {
                        $('#logo_img_dv').html("<div class='col-lg-6'><img class='img-responsive thumbnail' src='<?php echo base_url() . "uploads/company/" ?>" + dataObj.logo_img + "'></div>");
                    }

                }
            });

            $("#branch_id").attr("placeholder", "<?php echo lang('branche'); ?>").blur();

            $("#company_name, #email_id_company, #phone_no_company,#branch_id").removeAttr("required");
            $(className).hide();
        }
    }
</script> 
<script>
    //delete image and file save in array
    $('.delimg').on('click', function () {

        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');
        var delete_meg = "<?php echo lang('confirm_delete_item'); ?>";
        BootstrapDialog.show(
                {
                    title: '<?php echo $this->lang->line('Information'); ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
                                $('#deletedImagesDiv').append("<input type='hidden' name='softDeletedImages[]' value='" + str1 + "'> <input type='hidden' name='softDeletedImagesUrls[]' value='" + dataPath + '/' + imgName + "'>");
                                $('#' + divId).remove();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                                dialog.close();
                            }
                        }]
                });
    });
    var config = {
        support: "*", // Valid file formats
        form: "demoFiler", // Form ID
        dragArea: "dragAndDropFiles", // Upload Area ID
        uploadUrl: "<?php echo $lead_view; ?>/upload_file"				// Server side upload url
    }
    $(document).ready(function () {
        //initMultiUploader(config);
        var dropbox;
        var oprand = {
            dragClass: "active",
            on: {
                load: function (e, file) {
                    // check file size
                    if (parseInt(file.size / 1024) > 20480) {
                        var delete_meg = 'File \"" + file.name + "\" <?php echo lang('too_big_size'); ?>';
                        BootstrapDialog.show(
                                {
                                    title: '<?php echo $this->lang->line('Information'); ?>',
                                    message: delete_meg,
                                    buttons: [{
                                            label: '<?php echo $this->lang->line('ok'); ?>',
                                            action: function (dialog) {
                                                dialog.close();
                                            }
                                        }]
                                });
                        return false;
                    }

                    create_box(e, file);
                },
            }
        };
        FileReaderJS.setupDrop(document.getElementById('dragAndDropFiles'), oprand);
        var fileArr = [];

        create_box = function (e, file) {
            var rand = Math.floor((Math.random() * 100000) + 3);
            var imgName = file.name; // not used, Irand just in case if user wanrand to print it.
            var src = e.target.result;
            var xhr = new Array();
            xhr[rand] = new XMLHttpRequest();
            //            console.log(xhr[rand]);

            var filename = file.name;
            var fileext = filename.split('.').pop();
            //            console.log(fileext);
            xhr[rand].open("post", "<?php echo base_url('/Lead/upload_file') ?>/" + fileext, true);

            xhr[rand].upload.addEventListener("progress", function (event) {
                //console.log(event);
                if (event.lengthComputable) {
                    $(".progress[id='" + rand + "'] span").css("width", (event.loaded / event.total) * 100 + "%");
                    $(".preview[id='" + rand + "'] .updone").html(((event.loaded / event.total) * 100).toFixed(2) + "%");
                }
                else {
                    var delete_meg = "<?php echo lang('failed_file_upload'); ?>";
                    BootstrapDialog.show(
                            {
                                title: '<?php echo $this->lang->line('Information'); ?>',
                                message: delete_meg,
                                buttons: [{
                                        label: '<?php echo $this->lang->line('ok'); ?>',
                                        action: function (dialog) {
                                            dialog.close();
                                        }
                                    }]
                            });
                }
            }, false);

            xhr[rand].onreadystatechange = function (oEvent) {
                var img = xhr[rand].response;
                var url = '<?php echo base_url(); ?>';
                if (xhr[rand].readyState === 4) {
                    var filetype = img.split(".")[1];
                    if (xhr[rand].status === 200) {
                        var template = '<div class="eachImage" id="' + rand + '">';
                        var randtest = 'delete_row("' + rand + '")';
                        template += '<a id="delete_row" title="<?php echo lang('delete') ?>" class="remove_drag_img" onclick=' + randtest + '>×</a>';
                        if (filetype == 'jpg' || filetype == 'jpeg' || filetype == 'png' || filetype == 'gif') {
                            template += '<span class="preview" id="' + rand + '"><img src="' + src + '"><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                        } else {
                            template += '<span class="preview" id="' + rand + '"><div class="image_ext"><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + filetype + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                        }
                        template += '<input type="hidden" name="fileToUpload[]" value="' + img + '">';
                        template += '</span>';
                        $("#dragAndDropFiles").append(template);
                    }
                }
            };

            xhr[rand].setRequestHeader("Content-Type", "multipart/form-data");
            xhr[rand].setRequestHeader("X-File-Name", file.fileName);
            xhr[rand].setRequestHeader("X-File-Size", file.fileSize);
            xhr[rand].setRequestHeader("X-File-Type", file.type);

            // Send the file (doh)
            xhr[rand].send(file);

        }
        upload = function (file, rand) {
        }

    });
    function delete_row(rand) {
        jQuery('#' + rand).remove();
    }

    //image upload
    function showimagepreview(input)
    {
        console.log(input);
        $('.upload_recent').remove();
        var url = '<?php echo base_url(); ?>';
        $.each(input.files, function (a, b) {
            var rand = Math.floor((Math.random() * 100000) + 3);
            var arr1 = b.name.split('.');
            var arr = arr1[1].toLowerCase();
            var filerdr = new FileReader();
            var img = b.name;
            filerdr.onload = function (e) {
                var template = '<div class="eachImage upload_recent" id="' + rand + '">';
                var randtest = 'delete_row("' + rand + '")';
                template += '<a id="delete_row" title="<?php echo lang('delete') ?>" class="remove_drag_img" onclick=' + randtest + '>×</a>';
                if (arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif') {
                    template += '<span class="preview" id="' + rand + '"><img src="' + e.target.result + '"><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                } else {
                    template += '<span class="preview" id="' + rand + '"><div class="image_ext"><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + arr + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                }
                template += '<input type="hidden" name="file_data[]" value="' + b.name + '">';
                template += '</span>';
                $('#dragAndDropFiles').append(template);
            }
            filerdr.readAsDataURL(b);

        });

        var maximum = input.files[0].size / 1024;

    }

    //code for auto fill branch data
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

//Not allow duplicate contact email code
    function validateContactUniqueness(email, id)
    {
        $('.email_contact').each(function () {
            if ("email_id" + id != $(this).attr('id'))
            {
                if (email == $(this).val())
                {
                    $(this).val('');
                }
            }
        });
        var companyId = $('#company_id').val();
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        if (email != '')
        {

            $.ajax({
                url: "<?php echo base_url('Opportunity/validateContactUniqueness'); ?>",
                data: {'email': email, 'id':<?php echo (isset($edit_record[0]['lead_id'])) ? $edit_record[0]['lead_id'] : 0; ?>, 'company_id': companyId},
                type: "POST",
                dataType: "json",
                success: function (d)
                {
                    if (d.status == 1)
                    {
                        $('#email_id' + id).val('');
                        $('#email_id' + id).addClass('parsley-error');
                        $('#email_err' + id).removeClass('hidden');
                    }
                    else
                    {
                        $('#email_id' + id).removeClass('parsley-error')
                        $('#email_err' + id).addClass('hidden');
                    }
                }
            });
        }

    }

    function api_call()
    {
        
        var country = $('#country_id').find(':selected').attr("data-taxincluded-amount");
        $("#company_name").autocomplete({
            width: 260,
            matchContains: true,
            selectFirst: false,
            max: 15,
            minLength: 3,
            source: '<?php echo base_url(); ?>GetApiData?country_id=' + country,  
            search: function(){
                $("#company_name").siblings().addClass("bd-input-load");
            },
            response: function (event, ui) {
                if (ui.content.length === 0) {
                    $(".bd-input-load").removeClass("bd-input-load");
                }
            },
            select: function (event, ui) {
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
    
<?php if (!empty($edit_record[0]['country_id'])) { ?>
        api_call();
<?php } ?>
</script>