<script type="text/javascript" src="https://js.stripe.com/v1/"></script>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!isset($_SESSION['setting_current_tab']) || $_SESSION['setting_current_tab'] == '') {
    $sess_array = array('setting_current_tab' => 'general_setting');
    $this->session->set_userdata($sess_array);
}
?>

<div class="row">
    <div class="col-md-6 col-md-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url(); ?>Settings">
                    <?= lang('TOP_MENU_SETTINGS') ?>
                </a></li>
            <li class="active">
                <?= lang('GENERAL_SETTINGS') ?>
            </li>
        </ul>
    </div>
</div>
<div class="clr"></div>
<div class="row">
    <div class="col-xs-12 col-md-12 bd-cust-tab">
        <?php if ($this->session->flashdata('msg')) { ?>
            <div class='alert alert-success text-center'> <?php echo $this->session->flashdata('msg'); ?></div>
        <?php } ?>
         <?php if ($this->session->flashdata('error_msg')) { ?>
            <div class='alert alert-danger text-center'> <?php echo $this->session->flashdata('error_msg'); ?></div>
        <?php } ?>
        <ul class="nav nav-tabs ">
            <?php if(checkPermission('Settings','view')){?>
            <li class="<?php
        if ($this->session->userdata('setting_current_tab') == 'general_setting') {
            echo "active";
        }
        ?>">
                <a data-toggle="pill" href="#general_settings">
                    <?= lang('SETTING_COMPANY_INFORMATION') ?>
                </a>
            </li>
            <?php }?> 
            
            <?php if(checkPermission('Currencysettings','view')){ ?>
            <li class="<?php
                    if ($this->session->userdata('setting_current_tab') == 'settings_currency') {
                        echo "active";
                    }
                    ?>"><a data-toggle="pill" href="#settings_currency">
                <?= lang('SETTING_CURRENCY') ?>
                </a>
            </li>
             <?php }?> 
            <?php if(checkPermission('Settings','view')){?>
            <li class="<?php
                if ($this->session->userdata('setting_current_tab') == 'social_media_setting') {
                    echo "active";
                }
                ?>"><a data-toggle="pill" href="#social_media_settings">
                <?= lang('SOCIAL_MEDIA_SETTINGS') ?>
                </a>
            </li>
            <?php }?> 
            
            <?php if(checkPermission('Settings','view')){?>
            <li class="<?php
                if ($this->session->userdata('setting_current_tab') == 'tax_setting') {
                    echo "active";
                }
                ?>"><a data-toggle="pill" href="#taxSetting"><?= lang('SETTING_TAX_RULES'); ?></a>
            </li>
             <?php }?> 
            
            <?php if (checkPermission('EstimateSettings', 'view')) { ?>
                <li class="<?php
                if ($this->session->userdata('setting_current_tab') == 'settings_termsConditions') {
                    echo "active";
                }
                ?>"><a data-toggle="pill" href="#termsAndConditions"><?= lang('SETTING_TERMS_AND_CONDITION'); ?></a>
                </li>
            <?php } ?>
                
            <?php if(checkPermission('Settings','view')){?>   
            <li class="<?php
            if ($this->session->userdata('setting_current_tab') == 'google_analytics_settings') {
                echo "active";
            }
            ?>"><a data-toggle="pill" href="#google_analytics_settings">
                <?= lang('google_analytics_settings') ?>
                </a>
            </li>
            <?php } ?>
            
            <?php if(checkPermission('Settings','view')){?>  
            <li class="<?php
                if ($this->session->userdata('setting_current_tab') == 'newsletter_configuration') {
                    echo "active";
                }
                ?>"><a data-toggle="pill" href="#newsletter_configuration"><?= lang('NEWSLETTER_CONFIGURATION'); ?></a>
            </li>    
            <?php } ?>
        </ul>
        <div class="whitebox">
            <div class="pad-10">

                <div class="tab-content ">

                    <!-- Start General Settings-->
                    <div id="general_settings" class="tab-pane fade in <?php
            if ($this->session->userdata('setting_current_tab') == 'general_setting') {
                echo "active";
            }
                ?>">
                         <?php $formAction = !empty($editRecords) ? 'updatedata' : 'insertdata'; ?>
                        <h3>
                            <?php if ($formAction == "insertdata") { ?>
                                <?= lang('GENERAL_SETTINGS') ?>
                            <?php } else { ?>
                                <?= lang('UPDATE_GENERAL_SETTINGS') ?>
                            <?php } ?>

                        </h3>
                        <form action="<?php echo base_url(); ?>Settings/updateGeneralSettings" name="update_settings" id="update_settings" data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate>
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="telephone1" class="col-lg-2"><?php echo lang('company_name_genralsettings'); ?>*</label>
                                        <div class="col-lg-10"><input class="form-control" name="company_name" placeholder="Company Name" type="text" value="<?php
                            if ($formAction == "insertdata") {
                                echo set_value('company_name');
                                ?><?php } else { ?><?= !empty($editRecords['company_name']) ? $editRecords['company_name'] : '' ?><?php } ?>" required="" data-parsley-id="19"></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>
                                <!--  <div class="col-xs-12 col-md-4 col-sm-4 col-lg-6">
                                      <div class="form-group bd-form-group">
                                          <label for="telephone2" class="col-lg-2"><?php echo lang('company_street'); ?>*</label>
                                          <div class="col-lg-10"><input class="form-control" name="company_street" placeholder="<?php echo lang('company_street'); ?>" type="text" value="<?php
                                            if ($formAction == "insertdata") {
                                                echo set_value('company_street');
                                ?><?php } else { ?><?= !empty($editRecords['company_street']) ? $editRecords['company_street'] : '' ?><?php } ?>" required=""  data-parsley-id="20"></div>
                                          <div class="clr"></div>
                                      </div>
                                  </div> -->
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="telephone1" class="col-lg-2"><?php echo lang('SETTINGS_ADDRESS1'); ?>*</label>
                                        <div class="col-lg-10"><input class="form-control" name="address1" placeholder="<?php echo lang('SETTINGS_ADDRESS1'); ?>" type="text" value="<?php
                                if ($formAction == "insertdata") {
                                    echo set_value('address1');
                                ?><?php } else { ?><?= !empty($editRecords['address1']) ? $editRecords['address1'] : '' ?><?php } ?>" required="" data-parsley-id="19"></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="telephone2" class="col-lg-2"><?php echo lang('SETTINGS_ADDRESS2'); ?></label>
                                        <div class="col-lg-10"><input class="form-control" name="address2" placeholder="<?php echo lang('SETTINGS_ADDRESS2'); ?>" type="text" value="<?php
                                            if ($formAction == "insertdata") {
                                                echo set_value('address2');
                                ?><?php } else { ?><?= !empty($editRecords['address2']) ? $editRecords['address2'] : '' ?><?php } ?>" data-parsley-id="20"></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="city" class="col-lg-2"><?php echo lang('MY_PROFILE_CITY'); ?>*</label>
                                        <div class="col-lg-10"> <input type="text" class="form-control" name="city" id="city" placeholder="<?php echo lang('MY_PROFILE_CITY'); ?>" value="<?php
                                            if ($formAction == "insertdata") {
                                                echo set_value('city');
                                ?><?php } else { ?><?= !empty($editRecords['city']) ? $editRecords['city'] : '' ?><?php } ?>" data-parsley-id="14" required/></div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="state" class="col-lg-2"><?php echo lang('MY_PROFILE_STATE'); ?>*</label>
                                        <div class="col-lg-10"><input type="text" class="form-control" name="state" id="state" placeholder="<?php echo lang('MY_PROFILE_STATE'); ?>" value="<?php
                                            if ($formAction == "insertdata") {
                                                echo set_value('state');
                                ?><?php } else { ?><?= !empty($editRecords['state']) ? $editRecords['state'] : '' ?><?php } ?>" data-parsley-id="14" required/></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>


                                <div class="clr"></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="pincode" class="col-lg-2"><?php echo lang('postal_code'); ?>*</label>
                                        <div class="col-lg-10"><input type="text" class="form-control" name="pincode" id="pincode" placeholder="<?php echo lang('postal_code'); ?>" value="<?php
                                            if ($formAction == "insertdata") {
                                                echo set_value('pincode');
                                ?><?php } else { ?><?= !empty($editRecords['pincode']) ? $editRecords['pincode'] : '' ?><?php } ?>" data-parsley-id="14" required/></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="country" class="col-lg-2"><?php echo lang('MY_PROFILE_COUNTRY'); ?>*</label>
                                        <div class="col-lg-10"> 
                                            <select class="chosen-select form-control" placeholder="country"  name="country_id" id="country_id" required>
                                                <option value=""><?= lang('select_country') ?></option>
                                                <?php $country_id = $editRecords['country_id']; ?>
                                                <?php
                                                foreach ($country_data as $row) {
                                                    if ($country_id == $row['country_id']) {
                                                        ?>
                                                        <option selected value="<?php echo $row['country_id']; ?>"><?php echo $row['country_name']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $row['country_id']; ?>"><?php echo $row['country_name']; ?></option>

                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select></div>
                                    </div>
                                </div>
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="telephone1" class="col-lg-2"><?php echo lang('TELEPHONE_1'); ?>*</label>
                                        <div class="col-lg-10"><input class="form-control" name="telephone1" data-parsley-pattern='^[\d\+\-\.\(\)\/\s]*$' maxlength="25" placeholder="Telephone1" type="text" value="<?php
                                                if ($formAction == "insertdata") {
                                                    echo set_value('telephone1');
                                                    ?><?php } else { ?><?= !empty($editRecords['telephone1']) ? $editRecords['telephone1'] : '' ?><?php } ?>" data-parsley-pattern="/^\d{10}$/" required="" data-parsley-id="16"></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="telephone2" class="col-lg-2"><?php echo lang('TELEPHONE_2'); ?></label>
                                        <div class="col-lg-10"><input class="form-control" name="telephone2" data-parsley-pattern='^[\d\+\-\.\(\)\/\s]*$' maxlength="25" placeholder="Telephone2" type="text" value="<?php
                                            if ($formAction == "insertdata") {
                                                echo set_value('telephone2');
                                                    ?><?php } else { ?><?= !empty($editRecords['telephone2']) ? $editRecords['telephone2'] : '' ?><?php } ?>" data-parsley-pattern="/^\d{10}$/" data-parsley-id="18"></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="default_currency" class="col-lg-2"><?php echo lang('default_currency'); ?>*</label>
                                        <div class="col-lg-10"><select class="chosen-select form-control" data-parsley-errors-container="#default_currency_error" placeholder="<?= $this->lang->line('default_currency') ?>"  name="default_currency" id="default_currency" required <?php echo $disable; ?> >
                                                <option value="">
                                                    <?= $this->lang->line('default_currency') ?>
                                                </option>
                                                <?php $salutions_id = $editRecords['default_currency']; ?>
                                                <?php if (isset($currency_list)) { ?>   
                                                    <?php
                                                    foreach ($currency_list as $row) {
                                                        if ($salutions_id == $row['country_id']) {
                                                            ?>
                                                            <option selected value="<?php echo $row['country_id']; ?>"><?php echo $row['currency_code'] . " (" . $row['currency_symbol'] . ")"; ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $row['country_id']; ?>"><?php echo $row['currency_code'] . " ( " . $row['currency_symbol'] . " )"; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                <?php } ?>
                                            </select>
                                            <span id="default_currency_error"></span>
                                        </div>
                                        <div class="clr"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="clr"></div>
                            <div class="clr"></div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <?php if(checkPermission('Settings','edit')){ ?>
                                    <div class="form-group bd-form-group">
                                        <label for="profile_pic" class="col-lg-2"><?php echo lang('company_logo'); ?></label>
                                        <label class="custom-upload btn btn-blue mar-tp0"><?php echo lang('EST_TITLE_AUTOGRAPH_UPLOAD'); ?>
                                            <input type="file" class="form-control" name="profile_photo" id="profile_photo" data-parsley-fileextension='png|jpeg|jpg|JPG|PNG|JPEG' data-parsley-max-file-size="2000" placeholder="<?php echo lang('company_logo'); ?>"></label>
                                        <div class="clr"></div>
                                    </div>  
                                    <?php }?>
                                    <input type="hidden" value="<?php
                                                if ($formAction == "insertdata") {
                                                    echo set_value('profile_photo');
                                                    ?><?php } else { ?><?= !empty($editRecords['profile_photo']) ? $editRecords['profile_photo'] : '' ?><?php } ?>" name="hidden_img_name" id="hidden_img_name">
                                    <div class="col-xs-12 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <?php
                                            $tmpUrl = FCPATH . SETTINGS_PROFILE_PIC_UPLOAD_PATH . "/" . $editRecords['profile_photo'];
                                            if (isset($editRecords['profile_photo']) && $editRecords['profile_photo'] != '' && file_exists($tmpUrl)) {
                                                ?>
                                                <div class="clr"></div>
                                                <img class="img-responsive" src="<?php echo base_url() . SETTINGS_PROFILE_PIC_UPLOAD_PATH . "/" . $editRecords['profile_photo']; ?>"/>
                                            <?php }else
                                            { ?>
                                            <img class="img-responsive" src="<?php echo base_url() . "uploads/profile_photo/noimage.jpg"; ?>"/>
                                           <?php  } ?>

                                        </div>
                                    </div>
                                </div>    
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                            <?php if(checkPermission('Settings','edit')) { ?>
                            <center>
                                <input type="submit" class="btn btn-lg btn-green" name="submit_btn" id="submit_btn" value="<?php echo lang('EST_EDIT_SAVE'); ?>">
                            </center>
                            
                            <?php } ?>
                        </form>
                    </div>

                    <!-- End General Settings-->

                    <!-- Start Currency Settings-->
                    <div id="settings_currency" class="tab-pane fade in <?php
                                            if ($this->session->userdata('setting_current_tab') == 'settings_currency') {
                                                echo "active";
                                            }
                                            ?>">
                        <div class="col-xs-12 col-sm-6 col-md-3 pull-right text-right col-md-offset-3">
                            <div class="row mb15">

                                <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                                    <div id="searchForm" class="navbar-form navbar-left pull-right"> <div class="input-group">
                                            <input type="text" name="search_input" id="search_input" class="form-control" placeholder="<?php echo lang('EST_LISTING_SEARCH_FOR'); ?>">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="submit" id="submit_search" name="submit" title="<?php echo lang('search'); ?>" ><i class="fa fa-search fa-x"></i></button>&nbsp;
                                                <button class="btn btn-default" type="button" onclick="reset_data()" title="<?php echo lang('reset'); ?>"><i class="fa fa-refresh fa-x"></i></button>
                                            </span> </div></div>
                                </div>     
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <h3 class="white-link"><?= $this->lang->line('currency_settings_menu_hader') ?></h3>
                            </div>
                            <?php if (checkPermission('Currencysettings', 'add')) { ?>
                                <div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-sm-offset-3 mb15 col-md-offset-4 text-right"> <a data-href="<?php echo base_url('Currencysettings/add'); ?>" data-toggle="ajaxModal" class="btn btn-blue" title="<?php echo lang('add_currency'); ?>" aria-hidden="true" data-refresh="true" >
                                        <?= $this->lang->line('add_currency') ?>
                                    </a> </div>
                            <?php } ?>
                            <div class="clr"></div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
                                <!-- Listing of User List Table: Start -->
                                <div id="tableCurrencyDiv">

                                    <!-- Listing of User List Table: End --> 
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- End Currency Settings-->





                    <div id="social_media_settings" class="tab-pane fade in <?php
                            if ($this->session->userdata('setting_current_tab') == 'social_media_setting') {
                                echo "active";
                            }
                            ?>">
                        <h3>
                            <?= lang('SOCIAL_MEDIA_SETTINGS') ?>
                        </h3>
                        <form action="<?php echo base_url(); ?>Settings/updateSocialMediaSettings" name="update_social_media_settings" id="update_social_media_settings" data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate>
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-sm-6  col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="name"  class="col-lg-3"><?php echo lang('LINKEDIN_API_KEY'); ?></label>
                                        <div class="col-lg-9"><input class="form-control" name="linkedin_api_key" placeholder="<?php echo lang('LINKEDIN_API_KEY'); ?>" type="text" value="<?php
                            if ($settings_data['linkedin_api_key'] != '') {
                                echo $settings_data['linkedin_api_key'];
                            }
                            ?>" ></div>
                                    </div>
                                    <div class="clr"></div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6  col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="name" class="col-lg-3"><?php echo lang('LINKEDIN_COMPANY_ID'); ?></label>
                                        <div class="col-lg-9"><input class="form-control" name="linkedin_company_id" placeholder="<?php echo lang('LINKEDIN_COMPANY_ID'); ?>" type="text" value="<?php
                                            if ($settings_data['linkedin_company_id'] != '') {
                                                echo $settings_data['linkedin_company_id'];
                                            }
                            ?>"></div>
                                        <div class="clr"></div>
                                    </div>

                                </div>

                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="name"  class="col-lg-3"><?php echo lang('TWITTER_USERNAME'); ?></label>
                                        <div class="col-lg-9"><input class="form-control" name="twitter_username" placeholder="<?php echo lang('TWITTER_USERNAME'); ?>" type="text" value="<?php
                                            if ($settings_data['twitter_username'] != '') {
                                                echo $settings_data['twitter_username'];
                                            }
                            ?>" ></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6 col-sm-6  col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="Facebook Page Url"  class="col-lg-3"><?php echo lang('FACEBOOK_PAGE_URL'); ?></label>
                                        <div class="col-lg-9"><input type="text" class="form-control" name="facebook_page_url" placeholder="<?php echo lang('FACEBOOK_PAGE_URL'); ?>" value="<?php
                                            if ($settings_data['facebook_page_url'] != '') {
                                                echo $settings_data['facebook_page_url'];
                                            }
                            ?>" data-parsley-id="14"/></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>
                                <div class="clr"></div>
                            </div>

                            <!-- by brt for facebook API Setting-->
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border"><?php echo lang('FACEBOOK_APP_SETTING'); ?></legend>
                            </fieldset>
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-sm-6  col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="Facebook Page ID"  class="col-lg-3"><?php echo lang('FACEBOOK_PAGE_ID'); ?></label>
                                        <div class="col-lg-9"><input type="text" class="form-control" name="facebook_page_id" placeholder="<?php echo lang('FACEBOOK_PAGE_ID'); ?>" value="<?php
                                            if (isset($settings_data['facebook_page_id']) && $settings_data['facebook_page_id'] != '') {

                                                echo $settings_data['facebook_page_id'];
                                            }
                            ?>" data-parsley-id="14"/></div>
                                        <div class="clr"></div>
                                    </div>
                                </div> 

                                <div class="col-xs-12 col-md-6 col-sm-6  col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="Facebook APP ID"  class="col-lg-3"><?php echo lang('FACEBOOK_APP_ID'); ?></label>
                                        <div class="col-lg-9"><input type="text" class="form-control" name="facebook_app_id" placeholder="<?php echo lang('FACEBOOK_APP_ID'); ?>" value="<?php
                                            if (isset($settings_data['facebook_app_id']) && $settings_data['facebook_app_id'] != '') {
                                                echo $settings_data['facebook_app_id'];
                                            }
                            ?>" data-parsley-id="14"/></div>
                                        <div class="clr"></div>
                                    </div>
                                </div> 
                                <div class="clr"></div>
                            </div> 

                            <div class="row">

                                <div class="col-xs-12 col-md-6 col-sm-6  col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="Facebook APP SECRET"  class="col-lg-3"><?php echo lang('FACEBOOK_APP_SECRET'); ?></label>
                                        <div class="col-lg-9"><input type="text" class="form-control" name="facebook_app_secret" placeholder="<?php echo lang('FACEBOOK_APP_SECRET'); ?>" value="<?php
                                            if (isset($settings_data['facebook_app_secret']) && $settings_data['facebook_app_secret'] != '') {
                                                echo $settings_data['facebook_app_secret'];
                                            }
                            ?>" data-parsley-id="14"/></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6 col-sm-6  col-lg-6"></div>
                                <div class="clr"></div>
                            </div> 

                            <!-- this code for youtube JSON Setting-->
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border"><?php echo lang('YOUTUBE_JSON_SETTING'); ?></legend>
                            </fieldset>


                            <div class="row">

                                <div class="col-xs-12 col-md-6 col-sm-6  col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="Youtube Channel ID"  class="col-lg-3"><?php echo lang('YOUTUBE_CHANNEL_ID'); ?></label>
                                        <div class="col-lg-9"><input type="text" class="form-control" name="youtube_channel_id" placeholder="<?php echo lang('YOUTUBE_CHANNEL_ID'); ?>" value="<?php
                                            if (isset($settings_data['youtube_channel_id']) && $settings_data['youtube_channel_id'] != '') {
                                                echo $settings_data['youtube_channel_id'];
                                            }
                            ?>" data-parsley-id="14"/></div>
                                        <div class="clr"></div>
                                    </div> 
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6  col-lg-6"></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">

                                    <div class="form-group bd-form-group">
                                        <label for="youtube_app_credentials" class="col-xs-12"><?php echo lang('youtube_app_credentials'); ?>*</label>
                                        <label class="custom-upload btn btn-blue mar-tp0"><?php echo lang('upload'); ?><input type="file" class="form-control" name="youtube_app_credentials" id="youtube_app_credentials" placeholder="<?php echo lang('youtube_app_credentials'); ?>"></label>
                                        <div class="clr"></div>
                                    </div>    
                                    <input type="hidden" value="<?php
                                            if ($formAction == "insertdata") {
                                                echo set_value('youtube_app_credentials');
                                ?><?php } else { ?><?= !empty($editRecords1['youtube_app_credentials']) ? $editRecords1['youtube_app_credentials'] : '' ?><?php } ?>" name="hidden_img_name" id="hidden_img_name">
                                    <div class="col-xs-12 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <?php echo 'client_secrets.json'; ?>
                                            <div class="clr"></div>

                                        </div>
                                    </div>
                                </div>


                                <div class="clr"></div>

                                <div class="clr"></div>
                                <div class="clr"></div>
                            </div> 
                            <!-- brt ends here-->

                            <?php
//			                        if(@$readonly['disabled'] != 'disabled')
//			                        { 
                            ?>
                            <!--			        <div class="col-xs-12 col-md-3 col-sm-3">
                                                              <div class="form-group">
                                                                <label for="company_profile_image"><?php //echo lang('company_profile_image');          ?></label>
                                                                <input type="file" class="form-control" name="company_profile_image" id="company_profile_image" placeholder="<?php //echo lang('company_profile_image');           ?>">
                                                              </div>
                                                            </div>-->
                            <?php //}      ?>
                            <!--			        <div class="col-xs-12 col-md-3 col-sm-3">
                                                              <div class="form-group">
                            <?php
//			                                if(isset($settings_data['company_profile_image']) && $settings_data['company_profile_image'] != '')
//			                                { 
                            ?>
                                                                <label for="company_profile_image"><?php //echo lang('company_profile_image');           ?></label>
                                                                <div class="clr"></div>
                                                                <img  style="max-width:150px;width:100%" src="<?php //echo base_url().COMPANY_PROFILE_PIC_UPLOAD_PATH."/".$settings_data['company_profile_image'];            ?>"/>
                            <?php //}       ?>
                                                              </div>
                                                            </div>-->

                            <?php if(checkPermission('Settings','edit')) { ?>
                            <center>
                                <input type="submit" class="btn btn-lg btn-green" name="submit_btn" id="submit_btn" value="<?php echo lang('EST_EDIT_SAVE'); ?>">
                            </center>
                            <?php } ?>
                        </form>
                    </div>

                    <div id="taxSetting" class="tab-pane fade in <?php
                            if ($this->session->userdata('setting_current_tab') == 'tax_setting') {
                                echo "active";
                            }
                            ?>">
                        <h3><?= lang('SETTING_TAX_RULES'); ?></h3>
                        <form action="<?php echo base_url(); ?>Settings/updateTaxSettings" name="frmSubEmailSetting" id="frmSubEmailSetting" data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate>
                            <?php
                            if (!empty($TaxArray) && count($TaxArray) != 0) {
                                $hdnVal = count($TaxArray);
                            } else {
                                $hdnVal = 0;
                            }
                            ?>
                            <input type="hidden" name="hdnTaxCnt" id="hdnTaxCnt" value="<?php echo $hdnVal; ?>" />
                            <div class="row">
                                <div class="col-xs-12 col-md-12 col-sm-12  col-lg-12">
                                    <div class="form-group bd-form-group">
                                        <div class="col-lg-1 col-xs-2 col-sm-1 col-md-2">
                                            <label for="name"  ><?php echo lang('TAX_SETTING_OPT'); ?></label>
                                        </div>
                                        <div class="col-lg-11 col-xs-10 col-sm-11 col-md-10" >
                                            <div class="col-lg-11 col-sm-11 col-md-11 col-xs-12" id="appendTaxBox">
                                                <?php
                                                if (!empty($TaxArray) && count($TaxArray) != 0) {
                                                    $flag = 0;
                                                    foreach ($TaxArray as $TaxValue) {
                                                        ?>
                                                        <div id="append_taxsetting_<?php echo $flag; ?>" class="col-xs-12 col-sm-6 col-md-6 col-lg-6  ">
                                                            <input type="hidden" value="<?php echo $TaxValue['tax_id']; ?>" name="tax_id[]" id="tax_id" class="form-control appendClass">
                                                            <div class="col-lg-6 col-sm-5 col-xs-5 form-group"><input type="text" required="" value="<?php echo $TaxValue['tax_name']; ?>" placeholder="<?php echo lang('SETTING_LABEL_TAX_NAME'); ?> *" name="nametaxsetting[<?php echo $TaxValue['tax_id']; ?>]" id="nametaxsetting" class="form-control appendClass"></div>
                                                            <div class="col-lg-5 col-sm-5 col-xs-5 form-group"><input type="text" required="" value="<?php echo $TaxValue['tax_percentage']; ?>" placeholder="<?php echo lang('TAX_SETTING_OPT'); ?> *" name="taxsetting[<?php echo $TaxValue['tax_id']; ?>]" data-parsley-pattern="/^\d{0,8}(\.\d{0,2})?$/" required id="taxsetting" class="form-control appendClass"></div><div class="col-lg-1 text-left col-xs-2 col-md-1">
                                                                <?php if(checkPermission('Settings','delete')){?>
                                                                <div class="bd-error"><a onclick="removeItem('#append_taxsetting_<?php echo $flag; ?>',<?php echo $TaxValue['tax_id']; ?>)" title="<?php echo lang('remove');?>"><i class="fa fa-trash redcol"></i></a></div>
                                                                <?php } ?>
                                                            </div><div class="clr"></div>
                                                        </div>
                                                        <?php
                                                        $flag++;
                                                    }
                                                } else {
                                                    ?>
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 form-group"> 
                                                        <input type="hidden"  value="0" name="tax_id_new[]" id="tax_id" class="form-control appendClass">
                                                        <input type="text" required="" value="" placeholder="<?php echo lang('SETTING_LABEL_TAX_NAME'); ?> *" name="nametaxsetting_new[]" id="nametaxsetting" class="form-control appendClass"></div>
                                                    <div class="col-lg-6 col-sm-6 form-group"><input class="form-control" id="taxsetting" name="taxsetting_new[]" placeholder="<?php echo lang('TAX_SETTING_OPT'); ?>" type="text" value="" required="" ></div>
                                                <?php } ?>
                                            </div>
                                            
                                            <?php if(checkPermission('Settings','add') && checkPermission('Settings','edit')){ ?>
                                            <div class="col-lg-1 col-sm-1 col-md-1 col-xs-12 text-right custm-add-btn">
                                                <a class="custom-upload btn btn-blue mar-tp0" onclick="addTaxBox();" ><?php echo lang('ADD'); ?></a>
                                            </div>
                                            
                                            <?php } ?>
                                        </div>
                                        <div class="col-lg-12" >
                                        </div>
                                        <div class="clr"></div>
                                    </div>
                                    <div class="clr"></div>
                                </div>
                            </div>
                            <?php if(checkPermission('Settings','edit')){ ?>
                            <center>
                                <input type="submit" class="btn btn-lg btn-green" name="submit_btn" id="submit_btn" value="<?php echo lang('EST_EDIT_SAVE'); ?>">
                            </center>
                            <?php }?>
                        </form>
                    </div>

                    <div id="termsAndConditions" class="tab-pane fade in <?php
                                                if ($this->session->userdata('setting_current_tab') == 'settings_termsConditions') {
                                                    echo "active";
                                                }
                                                ?>">
                        <div class="col-xs-12 col-sm-6 col-md-3 pull-right text-right col-md-offset-3">
                            <div class="row mb15">

                                <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                                    <div class="navbar-form navbar-left pull-right" id="searchForm">
                                        <div class="input-group">
                                            <input type="text" name="search_input_tc" id="search_input_tc" class="form-control" placeholder="<?php echo lang('EST_LISTING_SEARCH_FOR'); ?>">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="submit" id="submit_search_btn" name="submit" title="<?= lang('search') ?>"><i class="fa fa-search fa-x"></i></button>&nbsp;
                                                <button class="btn btn-default" type="button" onclick="resetTc_data()" title="<?= lang('reset') ?>"><i class="fa fa-refresh fa-x"></i></button>
                                            </span> </div>
                                    </div>    
                                </div>     
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <h3 class="white-link"><?= $this->lang->line('estimate_settings_list') ?></h3> 
                            </div>
                            <?php if (checkPermission('EstimateSettings', 'add')) { ?>
                                <div class="col-xs-12 col-sm-6 text-right mb15 col-md-2 col-lg-2 col-md-offset-4">
                                    <a data-href="<?php echo base_url('EstimateSettings/add'); ?>" title="<?= lang('estimate_settings_add') ?>" data-toggle="ajaxModal" class=" btn btn-blue"><?= $this->lang->line('estimate_settings_add') ?></a>
                                </div>
                            <?php } ?>  	    
                        </div>
                        <div class="clr"></div>
                        <!-- Listing of User List Table: Start -->
                        <div id="tableTermsNconditionsDiv">

                            <!-- Listing of User List Table: End -->
                        </div>

                    </div>

                    <div id="google_analytics_settings" class="tab-pane fade in <?php
                            if ($this->session->userdata('setting_current_tab') == 'google_analytics_settings') {
                                echo "active";
                            }
                            ?>">
                        <h3>
                            <?= lang('google_analytics_settings') ?>
                        </h3>
                        <form action="<?php echo base_url(); ?>Settings/updateGoogleAnalyticsSettings" name="update_google_analytics_settings" id="update_google_analytics_settings" data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate>

                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="application_name"  class="col-lg-3"><?php echo lang('application_name'); ?>*</label>
                                        <div class="col-lg-9"><input class="form-control" name="application_name" placeholder="<?php echo lang('application_name'); ?>" type="text" value="<?php
                            if (isset($editRecords1['application_name']) && $editRecords1['application_name'] != '') {
                                echo $editRecords1['application_name'];
                            }
                            ?>"  required="">                                            
                                        </div>
                                        <div class="clr"></div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="service_account_email"  class="col-lg-3"><?php echo lang('service_account_email'); ?>*</label>
                                        <div class="col-lg-9"><input type="text" class="form-control"  name="service_account_email" placeholder="<?php echo lang('service_account_email'); ?>" value="<?php
                                            if (isset($editRecords1['service_account_email']) && $editRecords1['service_account_email'] != '') {
                                                echo $editRecords1['service_account_email'];
                                            }
                            ?>" data-parsley-id="14" required="" /></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>			  
                                <div class="clr"></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    
                                    <?php if(checkPermission('Settings','edit')){ ?>
                                    <div class="form-group bd-form-group">
                                        <label for="google_app_credentials" class="col-xs-12"><?php echo lang('google_app_credentials'); ?>*</label>
                                        
                                        <label class="custom-upload btn btn-blue mar-tp0">
                                            <?php echo lang('upload'); ?><input type="file" class="form-control" name="google_app_credentials" id="google_app_credentials" placeholder="<?php echo lang('google_app_credentials'); ?>">
                                        </label>
                                        
                                        <div class="clr"></div>
                                    </div>  
                                    
                                    <?php }?>
                                    <input type="hidden" value="<?php
                                            if ($formAction == "insertdata") {
                                                echo set_value('google_app_credentials');
                                ?><?php } else { ?><?= !empty($editRecords1['google_app_credentials']) ? $editRecords1['google_app_credentials'] : '' ?><?php } ?>" name="hidden_img_name" id="hidden_img_name">
                                    <div class="col-xs-12 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <?php
                                            if (isset($editRecords1['google_app_credentials']) && $editRecords1['google_app_credentials'] != '') {
                                                ?>

                                                <?php echo $editRecords1['google_app_credentials']; ?>
                                                <div class="clr"></div>
                                                <!-- img height="150" width="150" src="<?php echo base_url() . SETTINGS_PROFILE_PIC_UPLOAD_PATH . "/" . $editRecords['google_app_credentials']; ?>"/> -->
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clr"></div>
                            </div>
                            
                            <?php if(checkPermission('Settings','edit')){?>
                            <center>
                                <input type="submit" class="btn btn-lg btn-green" name="submit_btn" id="submit_btn" value="<?php echo lang('EST_EDIT_SAVE'); ?>">
                            </center>
                            <?php }?>
                        </form>
                    </div>
                    
                    <!-- Start Added by Sanket for newsletter Configuration -->
                    <div id="newsletter_configuration" class="tab-pane fade in <?php
                                            if ($this->session->userdata('setting_current_tab') == 'newsletter_configuration') {
                                                echo "active";
                                            }
                                            ?>">
                        <h3>
                            <?= lang('NEWSLETTER_CONFIGURATION') ?>
                        </h3>

                        <form action="<?php echo base_url(); ?>Settings/newsletter_configuration" name="update_newsletter_co0nfiguration" id="update_newsletter_co0nfiguration" data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate>

                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="form-group bd-form-group">
                                        <label for="select_newzletter"  class="col-lg-3"><?php echo lang('NEWSLETTER_TYPE'); ?>*</label>

                                        <select name="newsletter_type" id="newsletter_type" onchange="display_configuration(this.value);" data-parsley-errors-container="#newsletter_error" class="form-control chosen-select" required>
                                            <option value=""><?php echo lang('NEWSLETTER_TYPE'); ?></option>
                                            <option value="1" <?php if ($newsletter_type == "1") {
                                echo "selected='selected'";
                            } ?>><?php echo lang('NEWSLETTER_TYPE_MAILCHIMP'); ?></option>
                                            <option value="2" <?php if ($newsletter_type == "2") {
                                echo "selected='selected'";
                            } ?>><?php echo lang('NEWSLETTER_TYPE_CAMPAIGN_MONITOR'); ?></option>
                                            <option value="3" <?php if ($newsletter_type == "3") {
                                echo "selected='selected'";
                            } ?>><?php echo lang('NEWSLETTER_TYPE_MOOSEND'); ?></option>
                                            <option value="4" <?php if ($newsletter_type == "4") {
                                echo "selected='selected'";
                            } ?>><?php echo lang('NEWSLETTER_TYPE_GET_RESPONSE'); ?></option>
                                        </select>                                       
                                        <span id="newsletter_error"></span>
                                        <div class="clr"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="div_mailchimp_conf">
                                <!-- name -->
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="Mailchimp API Key"><?php echo lang('MAILCHIMP_API_KEY'); ?><span>*</span></label>
                                        <input type="text"  value="<?php if (!empty($mailchimp_data) && $mailchimp_data['api_key'] != '') {
                                echo $mailchimp_data['api_key'];
                            } ?>" placeholder="<?php echo lang('MAILCHIMP_API_KEY'); ?>" id="mailchimp_api_key" name="mailchimp_api_key" class="form-control">
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row" id="div_campaign_monitor_">

                                <!-- name -->
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="Campaign Monitor API Key"><?php echo lang('MAILCHIMP_API_KEY'); ?><span>*</span></label>
                                        <input type="text" value="<?php if (!empty($cmonitor_data) && $cmonitor_data['api_key'] != '') {
                                echo $cmonitor_data['api_key'];
                            } ?>" placeholder="<?php echo lang('MAILCHIMP_API_KEY'); ?>" id="cmonitor_api_key" name="cmonitor_api_key" class="form-control">
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="row" id="div_moosend_configuration">
                                <!-- name -->
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="Moosend API Key"><?php echo lang('MAILCHIMP_API_KEY'); ?><span>*</span></label>
                                        <input type="text" value="<?php if (!empty($moosend_data) && $moosend_data['api_key'] != '') {
                                echo $moosend_data['api_key'];
                            } ?>" placeholder="<?php echo lang('MAILCHIMP_API_KEY'); ?>" id="moosend_api_key" name="moosend_api_key" class="form-control">
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row" id="div_get_response">
                                <!-- name -->
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="Get Response API Key"><?php echo lang('MAILCHIMP_API_KEY'); ?><span>*</span></label>
                                        <input type="text" value="<?php if (!empty($get_response_data) && $get_response_data['api_key'] != '') {
                                echo $get_response_data['api_key'];
                            } ?>" placeholder="<?php echo lang('MAILCHIMP_API_KEY'); ?>" id="getresponse_api_key" name="getresponse_api_key" class="form-control">
                                    </div>
                                </div>
                                
                            </div>
                            <?php
                            if(checkPermission('Settings','edit'))
                            { ?>
                            <center>
                                <input type="submit" class="btn btn-lg btn-green" name="submit_btn" id="submit_btn" value="<?php echo lang('EST_EDIT_SAVE'); ?>">
                            </center>
                        <?php } ?> 
                        </form>

                    </div>
                    <!-- End Added by Sanket for newsletter Configuration -->
                    
                    <div id="menu5" class="tab-pane fade in">
                        <h3>Under Development</h3>
                    </div>
                    <div class="clr"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="clr"></div>
</div>
<div class="clr"></div>
<?php unset($_SESSION['setting_current_tab']); ?>
<script type="text/javascript">
// this identifies your website in the createToken call below
//                Stripe.setPublishableKey('pk_test_suxHAAvKSymUCw8lxGk7ZxLs'); 
    // Stripe.setPublishableKey('pk_test_suxHAAvKSymUCw8lxGk7ZxLs');  // DEV1's publishable key
    Stripe.setPublishableKey('pk_test_xN0QLDiaKoIQ1RNDwItzw8sk');

    function stripeResponseHandler(status, response) {

        //alert(response);return false;
        if (response.error) {
            // re-enable the submit button
            $('.submit-button').show();
            // show the errors on the form
            $("#errorjs").html(response.error.message);
        } else {
            var form$ = $("#payment-form");
            // token contains id, last4, and card type
            var token = response.id;
            //   alert(token);//return false;
            // insert the token into the form so it gets submitted to the server
            form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
            // and submit
            form$.get(0).submit();
        }
    }

    $(document).ready(function () {
        $('#payment-form').parsley();
        $("#payment-form").submit(function (event) {
            // $('#payment-form').parsley('validate');
            //alert($('#support_notify').length();
            //alert($('[name="support_notify"]:checked').length);

            //alert('aa');
// disable the submit button to prevent repeated clicks
            if ($('#card_number').val() == '') {
                $("#errorjs").html('Please enter Credit Card Number');
                return false;
            }
            var currentYear = (new Date).getFullYear();
            alert(currentYear);
            var currentMonth = (new Date).getMonth() + 1;
            if ($('.card-expiry-month').val() < currentMonth && $('.card-expiry-year').val() == currentYear) {
                $("#errorjs").html('Invalid Month');
                return false;
            }
            if ($('.card-expiry-year').val() < currentYear) {
                $("#errorjs").html('Invalid Year');
                return false;
            }

            // $('.submit-button').hide();
            $('.submit-button').prop('disabled', true);
// createToken returns immediately - the supplied callback submits the form if there are no errors
            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val(),
                quantity: $('#no_of_std').val()
            }, stripeResponseHandler);

            return false; // submit from callback
        });

    });

    function validateQty(event) {
        var key = window.event ? event.keyCode : event.which;

        if (event.keyCode == 8 || event.keyCode == 46
                || event.keyCode == 37 || event.keyCode == 39) {
            return true;
        }
        if (event.keyCode == 9) {
            return true;
        }
        else if (key < 48 || key > 57) {
            return false;
        }
        else
            return true;
    }
    ;
</script>
<script>
    var i = $('#hdnTaxCnt').val();		//Set i Variable for Increase Tax Box 
    $(document).ready(function () {
        $sup_amount = 15 * $('#support_user').val();
        $('#sup_amount').val($sup_amount);

        $crm_amount = 20 * $('#crm_user').val();
        $('#crm_amount').val($crm_amount);

        $pm_amount = 20 * $('#pm_user').val();
        $('#pm_amount').val($pm_amount);


        $('#support_user').keyup(function () {

            $sup_amount = 15 * $(this).val();
            $('#sup_amount').val($sup_amount);

        });

        $('#crm_user').keyup(function () {
            $crm_amount = 20 * $(this).val();
            $('#crm_amount').val($crm_amount);
        });

        $('#pm_user').keyup(function () {
            $pm_amount = 20 * $(this).val();
            $('#pm_amount').val($pm_amount);
        });



        $('.chosen-select').chosen();
        $('#frmSubEmailSetting').parsley();
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

        $("#update_settings").parsley();

        window.Parsley.addValidator('maxFileSize', {
            validateString: function (_value, maxSize, parsleyInstance) {
                if (!window.FormData) {
                    alert('You are making all developpers in the world cringe. Upgrade your browser!');
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
    function addTaxBox()
    {

        var onClickVar = "removeItem('#append_taxsetting_" + i + "',0)";
        var inputId = "append_taxsetting_" + i;
        var data = '<div id="' + inputId + '" class="row"><input type="hidden"  value="0" name="tax_id_new[]" id="tax_id" class="form-control appendClass"><div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 form-group"><div class="col-lg-5 col-sm-5 col-md-5 col-xs-5"><input type="text" required="" value="" placeholder="<?php echo lang('SETTING_LABEL_TAX_NAME'); ?> *" name="nametaxsetting_new[]" id="nametaxsetting" class="form-control appendClass"></div><div class="col-lg-6 col-sm-6 col-md-6 col-xs-5"><input class="form-control appendClass" id="taxsetting" name="taxsetting_new[]" placeholder="<?php echo lang('TAX_SETTING_OPT'); ?> *" type="text" value="" data-parsley-pattern="/^\\d{0,8}(\\.\\d{0,2})?$/" required=""></div><div class="col-lg-1 text-left"><div class="bd-error"><a title="Remove Textbox?" onclick="' + onClickVar + '"><i class="fa fa-trash redcol"></i></a></div></div><div class="clr"></div></div></div>';
        //var data = '<input class="form-control appendClass" id="'+ inputId +'" name="taxsetting[]" placeholder="<?php echo lang('TAX_SETTING_OPT'); ?>" type="text" value="" required=""><a title="Remove Textbox?" onclick="'+ onClickVar +'"><i class="fa fa-trash redcol"></i></a>';
        i++;
        $('#frmSubEmailSetting').parsley();
        /*if($('.appendClass').length == 0)
         {
         i = 1;
         var onClickVar = "removeItem('#append_taxsetting_"+ i +"')";
         var inputId = "append_taxsetting_"+i;
         } else {
         i = $('.appendClass').length + 1;
         var onClickVar = "removeItem('#append_taxsetting_"+ i +"')";
         var inputId = "append_taxsetting_"+i;
         }
         alert(i);
         */
        $('#appendTaxBox').append(data);
    }
    function removeItem(el, id)
    {

        var delete_meg = "<?php echo $this->lang->line('delete_item'); ?>";
        BootstrapDialog.show(
                {
                    title: '<?php echo lang('Information');?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
                                if (id > 0)
                                {
                                    $.ajax({
                                        url: "<?php echo base_url('Settings/removeTaxItem'); ?>",
                                        type: "POST",
                                        data: {id: id},
                                        dataType: "JSON",
                                        success: function (data)
                                        {
                                            if (data.status == 1)
                                            {
                                                $(el).remove();
                                            }
                                        }
                                    });
                                }
                                else
                                {
                                    $(el).remove();

                                }
                                dialog.close();
                            }
                        }]
                });
    }
</script>
<!-- start script for Currency Settings-->
<script>
    function getCurrencyData()
    {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('Currencysettings/'); ?>',
            data: {},
            success: function (html)
            {
                $('#tableCurrencyDiv').html(html);
            }
        });
    }

    $(document).ready(function () {

        getCurrencyData();
        $('body').delegate('#submit_search', 'click', function () {
            paginationClick();
            return false;
        });
        $('body').delegate('#search_input', 'keyup', function (event) {
            if (event.keyCode == 13) {
                paginationClick();
            }
            return false;
        });

        function paginationClick() {
            //  var href = $(this).attr('href');
            var href = '<?php echo base_url('Currencysettings'); ?>';
            $("#rounded-corner").css("opacity", "0.4");
            var search = $('#search_input').val();
            $.ajax({
                type: "GET",
                url: href,
                data: {search: search},
                success: function (response)
                {
                    //alert(response);
                    $("#rounded-corner").css("opacity", "1");
                    $("#tableCurrencyDiv").empty();
                    $("#tableCurrencyDiv").html(response);

                }
            });
            return false;
        }


        $("body").delegate("#tableCurrencyDiv ul.tsc_pagination a", "click", function () {
            var href = $(this).attr('href');
            var search = $('#search_input').val();
            $.ajax({
                type: "GET",
                url: href,
                data: {search: search},
                success: function (response)
                {
                    $("#tableCurrencyDiv").empty();
                    $("#tableCurrencyDiv").html(response);

                    return false;
                }
            });
            return false;
        });

        $("body").delegate("#tableCurrencyDiv th.sortCurrencySettingsList a", "click", function () {
            var href = $(this).attr('href');
            var search = $('#search_input').val();
            $.ajax({
                type: "GET",
                url: href,
                data: {search: search},
                success: function (response)
                {
                    $("#tableCurrencyDiv").empty();
                    $("#tableCurrencyDiv").html(response);

                    return false;
                }
            });
            return false;
        });
    });
    function reset_data()
    {
        $("#search_input").val("");
        $("#submit_search").trigger("click");

    }
</script> 
<!-- End script for Currency Settings-->

<!-- Start script for Terms & Conditions-->

<script>
    function getTermNconditionsData()
    {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('EstimateSettings/'); ?>',
            data: {},
            success: function (html)
            {
                $('#tableTermsNconditionsDiv').html(html);
            }
        });
    }

    $(document).ready(function () {

        getTermNconditionsData();
        $('body').delegate('#submit_search_btn', 'click', function () {
            paginationTCClick();
            return false;
        });
        $('body').delegate('#search_input_tc', 'keyup', function (event) {
            if (event.keyCode == 13) {
                paginationTCClick();
            }
            return false;
        });

        function paginationTCClick() {
            //  var href = $(this).attr('href');
            var href = '<?php echo base_url('EstimateSettings'); ?>';
            $("#rounded-corner").css("opacity", "0.4");
            var search = $('#search_input_tc').val();
            $.ajax({
                type: "GET",
                url: href,
                data: {search: search},
                success: function (response)
                {
                    //alert(response);
                    $("#rounded-corner").css("opacity", "1");
                    $("#tableTermsNconditionsDiv").empty();
                    $("#tableTermsNconditionsDiv").html(response);

                }
            });
            return false;
        }


        $("body").delegate("#tableTermsNconditionsDiv ul.tsc_pagination a", "click", function () {
            var href = $(this).attr('href');
            var search = $('#search_input_tc').val();
            $.ajax({
                type: "GET",
                url: href,
                data: {search: search},
                success: function (response)
                {
                    $("#tableTermsNconditionsDiv").empty();
                    $("#tableTermsNconditionsDiv").html(response);

                    return false;
                }
            });
            return false;
        });

        $("body").delegate("#tableTermsNconditionsDiv th.sortEmailTemplateList a", "click", function () {
            var href = $(this).attr('href');
            var search = $('#search_input_tc').val();
            $.ajax({
                type: "GET",
                url: href,
                data: {search: search},
                success: function (response)
                {
                    $("#tableTermsNconditionsDiv").empty();
                    $("#tableTermsNconditionsDiv").html(response);

                    return false;
                }
            });
            return false;
        });
    });
    function resetTc_data()
    {
        $("#search_input_tc").val("");
        $("#submit_search_btn").trigger("click");

    }
</script>

<script>
    function toggle_show(className, obj) {


        var $input = $(obj);
        if ($input.prop('checked'))
            $(className).show();
        else
            //alert(className);
            $(className).hide();
        $(className).val('0');

    }
    $(document).ready(function () {
<?php
if (isset($setup[0]['is_crm']) && $setup[0]['is_crm'] == '1') {
    ?>

            $('#crm_user').show();
<?php } else { ?>

            $('#crm_user').hide();
<?php } ?>

<?php
if (isset($setup[0]['is_pm']) && $setup[0]['is_pm'] == '1') {
    ?>
            $('#pm_user').show();
<?php } else { ?>
            $('#pm_user').hide();
<?php } ?>

<?php
if (isset($setup[0]['is_support']) && $setup[0]['is_support'] == '1') {
    ?>
            $('#support_user').show();
<?php } else { ?>
            $('#support_user').hide();
<?php } ?>
    });
</script>

<!-- End script for Terms & Conditions-->

<!-- Start script for Newsletter-->
<script type="text/javascript">

    function display_configuration(newsletter_type)
    {
        if (newsletter_type != 0)
        {
            if(newsletter_type == "1")
            {
                $('#div_mailchimp_conf').css("display","block");
                $('#div_campaign_monitor_').css("display","none");
                $('#div_moosend_configuration').css("display","none");
                $('#div_get_response').css("display","none");
                
                //for validation
                $('#mailchimp_api_key').attr('data-parsley-required', 'true');
                //$('#mailchimp_list_id').attr('data-parsley-required', 'true');
                
                $('#cmonitor_api_key').attr('data-parsley-required', 'false');
                //$('#cmonitor_list_id').attr('data-parsley-required', 'false');
                $('#moosend_api_key').attr('data-parsley-required', 'false');
                //$('#moosend_list_id').attr('data-parsley-required', 'false');
                $('#getresponse_api_key').attr('data-parsley-required', 'false');
                //$('#getresposne_campaign_token').attr('data-parsley-required', 'false');
               // $('#update_newsletter_co0nfiguration').parsley();
            }else if(newsletter_type == "2")
            {
                $('#div_mailchimp_conf').css("display","none");
                $('#div_campaign_monitor_').css("display","block");
                $('#div_moosend_configuration').css("display","none");
                $('#div_get_response').css("display","none");
                
                //for validation
                $('#cmonitor_api_key').attr('data-parsley-required', 'true');
               // $('#cmonitor_list_id').attr('data-parsley-required', 'true');
                
                $('#mailchimp_api_key').attr('data-parsley-required', 'false');
                //$('#mailchimp_list_id').attr('data-parsley-required', 'false');
                $('#moosend_api_key').attr('data-parsley-required', 'false');
                //$('#moosend_list_id').attr('data-parsley-required', 'false');
                $('#getresponse_api_key').attr('data-parsley-required', 'false');
                //$('#getresposne_campaign_token').attr('data-parsley-required', 'false');
               // $('#update_newsletter_co0nfiguration').parsley();
            }else if(newsletter_type == "3")
            {
                $('#div_mailchimp_conf').css("display","none");
                $('#div_campaign_monitor_').css("display","none");
                $('#div_moosend_configuration').css("display","block");
                $('#div_get_response').css("display","none");
                
                //for validation
                $('#moosend_api_key').attr('data-parsley-required', 'true');
               // $('#moosend_list_id').attr('data-parsley-required', 'true');
                
                $('#mailchimp_api_key').attr('data-parsley-required', 'false');
               // $('#mailchimp_list_id').attr('data-parsley-required', 'false');
                $('#cmonitor_api_key').attr('data-parsley-required', 'false');
               // $('#cmonitor_list_id').attr('data-parsley-required', 'false');
                $('#getresponse_api_key').attr('data-parsley-required', 'false');
               // $('#getresposne_campaign_token').attr('data-parsley-required', 'false');
               // $('#update_newsletter_co0nfiguration').parsley();
            }else if(newsletter_type == "4")
            {
                $('#div_mailchimp_conf').css("display","none");
                $('#div_campaign_monitor_').css("display","none");
                $('#div_moosend_configuration').css("display","none");
                $('#div_get_response').css("display","block");
                
                //for validation
                $('#getresponse_api_key').attr('data-parsley-required', 'true');
                //$('#getresposne_campaign_token').attr('data-parsley-required', 'true');
                
                $('#mailchimp_api_key').attr('data-parsley-required', 'false');
                //$('#mailchimp_list_id').attr('data-parsley-required', 'false');
                $('#cmonitor_api_key').attr('data-parsley-required', 'false');
                //$('#cmonitor_list_id').attr('data-parsley-required', 'false');
                $('#moosend_api_key').attr('data-parsley-required', 'false');
                //$('#moosend_list_id').attr('data-parsley-required', 'false');
               // $('#update_newsletter_co0nfiguration').parsley();
            }
        }else
        {
            $('#div_mailchimp_conf').css("display","none");
            $('#div_campaign_monitor_').css("display","none");
            $('#div_moosend_configuration').css("display","none");
            $('#div_get_response').css("display","none");
        }
    }
    
    <?php if(isset($newsletter_type) && $newsletter_type != '') { ?>
        var newsletter_type = '<?php echo $newsletter_type?>';
        display_configuration(newsletter_type);
    <?php }else {?>    
      display_configuration('0');
    <?php }?>
        
</script>
<!-- End script for Newsletter-->
