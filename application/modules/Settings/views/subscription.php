<script type="text/javascript" src="https://js.stripe.com/v1/"></script>
<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
$infinite = base_url('uploads/images/infinite.png');
$now = time(); // or your date as well
$edate = (isset($setup[0]['start_date'])) ? strtotime($setup[0]['start_date']) : $now;
$your_date =$edate;
$dff=($now - $your_date);
$datediff=0;
 $dff=floor($dff/(60*60*24));

$datediff=15-$dff;
if (($setup[0]['domain_name'] == 'blazedesk' || $setup[0]['domain_name'] == 'localhost' || $setup[0]['domain_name'] == '103')) {
    $datediff = 0;
}

$remainDay = $datediff;
if (!isset($_SESSION['setting_current_tab']) || $_SESSION['setting_current_tab'] == '') {
    $sess_array = array('setting_current_tab' => 'general_setting');
    $this->session->set_userdata($sess_array);
}
?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <?php echo $this->breadcrumbs->show(); ?>
    </div>
</div>
<div class="clr"></div>
<div class="row">
    <div class="col-xs-12 col-md-12 bd-cust-tab">
        <?php if ($this->session->flashdata('msg')) { ?>
            <?php echo $this->session->flashdata('msg'); ?>
        <?php } ?>
        <ul class="nav nav-tabs ">
            <li class="<?php
        if ($this->session->userdata('setting_current_tab') == 'biling_information') {
            echo "active";
        }
        ?>"><a data-toggle="pill" href="#biling_information">
                <?= lang('biling_information') ?>
                </a></li>
            <li class="<?php
                if ($this->session->userdata('setting_current_tab') == 'subscription') {
                    echo "active";
                }
                ?>"><a data-toggle="pill" href="#subscription" id="subli">
                <?= lang('subscription') ?>
                </a></li>
        </ul>
        <div class="whitebox">
            <div class="pad-10">
                <div class="tab-content ">
                    <div id="biling_information" class="tab-pane fade in <?php
                if ($this->session->userdata('setting_current_tab') == 'biling_information') {
                    echo "active";
                }
                ?>">
                        <h3>
                            <?= lang('biling_information') ?>
                        </h3>
                        <form data-parsley-validate id="payment-form" action="<?php echo base_url(); ?>Settings/billing_information" name="checkout"  data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate>
                            <input type="hidden" name="setup_id" id="setup_id" value="<?php
                            if (isset($setup[0]['setup_id'])) {
                                echo $setup[0]['setup_id'];
                            }
                            ?>"/>
                            <input type="hidden" name="cust_id" id="cust_id" value="<?php
                            if (isset($setup[0]['cust_id'])) {
                                echo $setup[0]['cust_id'];
                            }
                            ?>"/>
                                   <?php //pr($setup);  ?>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="bd-form-group row mb15">
                                    <div class="col-md-2"><label><?php echo lang('company_name') ?>*</label></div>
                                    <div class="col-md-10"><input type="text" data-parsley-pattern="/^[ A-Za-z0-9_@./#()&+-]*$/" class="form-control"  placeholder="<?php echo lang('company_name') ?> *" id="company_name" name="company_name"  required value="<?php
                                   if (isset($setup[0]['company_name'])) {
                                       echo $setup[0]['company_name'];
                                   }
                                   ?>"></div><div class="clr"></div>
                                    <span id="cost_name_error" class="alert-danger"></span> </div>
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="bd-form-group row mb15">
                                    <div class="col-md-2"><label><?php echo lang('address1') ?>*</label></div>
                                    <div class="col-md-10"><input type="text" data-parsley-pattern="/^[ A-Za-z0-9_@./#()&+-]*$/" class="form-control"  placeholder="<?php echo lang('address1') ?> *" id="add1" name="add1"  required value="<?php
                                        if (isset($bill[0]['address'])) {
                                            echo $bill[0]['address'];
                                        }
                                   ?>"></div><div class="clr"></div>
                                    <span id="cost_name_error" class="alert-danger"></span> </div>
                                <div class="clr"></div>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="bd-form-group row mb15">
                                    <div class="col-md-2"><label><?php echo lang('address2') ?></label></div>
                                    <div class="col-md-10"><input type="text" data-parsley-pattern="/^[ A-Za-z0-9_@./#()&+-]*$/" class="form-control"  placeholder="<?php echo lang('address2') ?> " id="add2" name="add2"  value="<?php
                                        if (isset($bill[0]['address2'])) {
                                            echo $bill[0]['address2'];
                                        }
                                   ?>"></div><div class="clr"></div>
                                    <span id="cost_name_error" class="alert-danger"></span> </div>
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="bd-form-group row mb15">
                                    <div class="col-md-2"> <label><?php echo lang('postal_code') ?>*</label></div>
                                    <div class="col-md-10"> <input type="text" data-parsley-pattern="/^[ A-Za-z0-9_@./#()&+-]*$/" class="form-control"  placeholder="<?php echo lang('postal_code') ?> *" id="postal_code" name="postal_code"  required value="<?php
                                        if (isset($bill[0]['pincode'])) {
                                            echo $bill[0]['pincode'];
                                        }
                                   ?>"></div><div class="clr"></div>
                                    <span id="cost_name_error" class="alert-danger"></span> </div>
                                <div class="clr"></div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="bd-form-group row mb15">
                                    <div class="col-md-2"><label><?php echo lang('state') ?>*</label></div>
                                    <div class="col-md-10"><input type="text" data-parsley-pattern="/^[ A-Za-z0-9_@./#()&+-]*$/" class="form-control"  placeholder="<?php echo lang('state') ?> *" id="state" name="state"  required value="<?php
                                        if (isset($bill[0]['state'])) {
                                            echo $bill[0]['state'];
                                        }
                                   ?>"></div><div class="clr"></div>
                                    <span id="cost_name_error" class="alert-danger"></span> </div>
                                <div class="clr"></div>
                            </div>

                            <div class="clr"></div>

                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="bd-form-group row mb15">
                                    <div class="col-md-2"><label><?php echo lang('city') ?>*</label></div>
                                    <div class="col-md-10"><input type="text" data-parsley-pattern="/^[ A-Za-z0-9_@./#()&+-]*$/" class="form-control"  placeholder="<?php echo lang('city') ?> *" id="city" name="city"  required value="<?php
                                        if (isset($bill[0]['city'])) {
                                            echo $bill[0]['city'];
                                        }
                                   ?>"></div><div class="clr"></div>
                                    <span id="cost_name_error" class="alert-danger"></span> </div>
                                <div class="clr"></div>
                            </div>


                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="bd-form-group row mb15">
                                    <div class="col-md-2"><label><?php echo lang('country') ?>*</label></div>
                                    <div class="col-md-10">

                                        <select name="country" id="country" required  class="form-control selectpicker chosen-select">
                                            <option value="">
                                                <?= $this->lang->line('select_country') ?>
                                            </option>
                                            <?php if (isset($country_data) && count($country_data) > 0) { ?>
                                                <?php
                                                foreach ($country_data as $country_data) {
                                                    if ($country_data['country_id'] == $bill[0]['country']) {
                                                        $selected = 'selected="selected"';
                                                    } else {
                                                        $selected = '';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $country_data['country_id']; ?>" <?php echo $selected; ?> ><?php echo $country_data['country_name']; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>

                                    </div>
                                    <span id="cost_name_error" class="alert-danger"></span> </div>
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="bd-form-group row mb15">
                                    <div class="col-md-2"> <label><?php echo lang('telephone1') ?>*</label></div>
                                    <div class="col-md-10"><input type="text" data-parsley-pattern='^[\d\+\-\.\(\)\/\s]*$' maxlength="25" class="form-control"  placeholder="<?php echo lang('telephone1') ?> *" id="telephone1" name="telephone1"  required value="<?php
                                            if (isset($bill[0]['phoneno'])) {
                                                echo $bill[0]['phoneno'];
                                            }
                                            ?>"></div><div class="clr"></div>
                                    <span id="cost_name_error" class="alert-danger"></span> </div>
                                <div class="clr"></div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="bd-form-group row mb15">
                                    <div class="col-md-2"><label><?php echo lang('email') ?>*</label></div>
                                    <div class="col-md-10"><input type="text" data-parsley-pattern="/^[ A-Za-z0-9_@./#()&+-]*$/" class="form-control"  placeholder="<?php echo lang('email') ?> *" id="email" name="email"  required value="<?php
                                        if (isset($bill[0]['email'])) {
                                            echo $bill[0]['email'];
                                        }
                                            ?>"></div><div class="clr"></div>
                                    <span id="cost_name_error" class="alert-danger"></span> </div>
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="bd-form-group row mb15">
                                    <div class="col-md-2"><label><?php echo lang('vat_number') ?>*</label></div>
                                    <div class="col-md-10"> <input type="text" class="form-control" name="vat_number" id="vat_number" placeholder="<?= lang('vat_number'); ?> *" value="<?php
                                        if (isset($bill[0]['vat_number'])) {
                                            echo $bill[0]['vat_number'];
                                        }
                                            ?>" required></div>
                                </div>
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                            <!--html fot billing method-->
                            <div class="row mb15">
                                <h3><?= lang('bil_method'); ?></h3>
                                <div class="bd-pay-method"> <label class="col-md-1 col-sm-2"><?= lang('pay_method'); ?></label>
                                    <div class="col-md-9 col-pay col-sm-9"> <ul class="nav">
                                            <li class="col-xs-3 text-center"><img class="img-responsive" src="<?php echo base_url() . "/uploads/images/visa.jpg"; ?>" alt="visa"></li>
                                            <li class="col-xs-3 text-center"><img class="img-responsive" src="<?php echo base_url() . "/uploads/images/paypal.jpg"; ?>" alt="paypal"></li>
                                            <li class="col-xs-3 text-center"><img class="img-responsive" src="<?php echo base_url() . "/uploads/images/deal.jpg"; ?>" alt="deal"></li>
                                            <li class="col-xs-3 text-center"><img class="img-responsive" src="<?php echo base_url() . "/uploads/images/wire-trans.jpg"; ?>" alt="wire-trans"></li>
                                        </ul></div>
                                    <div class="clr"></div></div>
                            </div>
                            <!--html fot billing method-->
                            <div class="clr"></div>



                            <center>
                                <input type="submit" class="btn btn-lg btn-green btn-theme submit-button" name="sub_form" id="sub_form" value="<?= lang('EST_EDIT_SAVE'); ?>">
                            </center>
                        </form>
                    </div>
                    <div id="subscription" class="tab-pane fade in <?php
                                        if ($this->session->userdata('setting_current_tab') == 'subscription') {
                                            echo "active";
                                        }
                                            ?>">
                        <div class="bd-subs-pageinfo text-center"> <span><?php echo lang('remain_day_line'); ?> <?php echo $remainDay; ?> <?php echo lang('days'); ?></span>
                            <h2>$<span id="mon_cost"></span> USD</h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-5 bd-subs-list">
                                <div class="col-md-6 col-xs-6 bd-subs-logo text-center ">

                                    <img src="<?php echo base_url() . "/uploads/images/crm-logo1.jpg"; ?>" alt="crm">
                                    <span><?= lang('crm_user'); ?></span>
                                </div>
                                <div class="col-md-6  col-xs-6">
                                    <div class="bd-numb-user blue-label mar_b6">
                                        <span id="current_crm_users"><?php echo ($setup[0]['status'] == 1) ? $setup[0]['crm_user'] : ' <img src="' . $infinite . '" alt="crm">'; ?></span>
                                        <p><?= lang('current_user'); ?></p>
                                    </div>

                                    <a data-href="<?php echo base_url('Settings/addcrmuser'); ?>" data-toggle="ajaxModal" class="greenbg width-100 btn mar_b6" aria-hidden="true" data-refresh="true">
                                        <?= $this->lang->line('add_user') ?>
                                    </a>
                                    <a data-href="<?php echo base_url('Settings/removecrmuser'); ?>" data-toggle="ajaxModal" class="redbg width-100 btn mar_b6" aria-hidden="true" data-refresh="true">
                                        <?= $this->lang->line('remove_user') ?>
                                    </a>
                                </div>
                                <div class="clr"></div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-5 bd-subs-list">
                                <div class="col-md-6 col-xs-6 bd-subs-logo text-center ">

                                    <img src="<?php echo base_url() . "/uploads/images/pm-logo.jpg"; ?>" alt="crm">
                                    <span><?= lang('pm_user'); ?></span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="bd-numb-user blue-label mar_b6">
                                        <span id="current_pm_users"><?php echo ($setup[0]['status'] == 1) ? $setup[0]['pm_user'] : ' <img src="' . $infinite . '" alt="crm">'; ?></span>
                                        <p><?= lang('current_user'); ?></p>
                                    </div>
                                    <a data-href="<?php echo base_url('Settings/addpmuser'); ?>" data-toggle="ajaxModal" class="greenbg width-100 btn mar_b6" aria-hidden="true" data-refresh="true">
                                        <?= $this->lang->line('add_user') ?>
                                    </a>
                                    <a data-href="<?php echo base_url('Settings/removepmuser'); ?>" data-toggle="ajaxModal" class="redbg width-100 btn mar_b6" aria-hidden="true" data-refresh="true">
                                        <?= $this->lang->line('remove_user') ?>
                                    </a>
                                </div>
                                <div class="clr"></div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-5 bd-subs-list">
                                <div class="col-md-6 col-xs-6 bd-subs-logo text-center ">

                                    <img src="<?php echo base_url() . "/uploads/images/finc-logo.jpg"; ?>" alt="crm">
                                    <span><?= lang('finance'); ?></span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="bd-numb-user blue-label mar_b6">
                                        <span>5</span>
                                        <p><?= lang('current_user'); ?></p>
                                    </div>
                                    <button type="button" class="greenbg width-100 btn mar_b6 under_development"><?= $this->lang->line('add_user') ?></button>
                                    <button type="button" class="redbg width-100 btn under_development"><?= $this->lang->line('remove_user') ?></button>
                                </div>
                                <div class="clr"></div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-5 bd-subs-list">
                                <div class="col-md-6 col-xs-6 bd-subs-logo text-center ">

                                    <img src="<?php echo base_url() . "/uploads/images/support-logo.jpg"; ?>" alt="crm">
                                    <span><?= lang('support_user'); ?></span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="bd-numb-user blue-label mar_b6">
                                        <span id="current_sup_users"><?php echo ($setup[0]['status'] == 1) ? $setup[0]['support_user'] : '<img src="' . $infinite . '" alt="crm">'; ?></span>
                                        <p><?= lang('current_user'); ?></p>
                                    </div>
                                    <a data-href="<?php echo base_url('Settings/addsupuser'); ?>" data-toggle="ajaxModal" class="greenbg width-100 btn mar_b6" aria-hidden="true" data-refresh="true">
                                        <?= $this->lang->line('add_user') ?>
                                    </a>
                                    <a data-href="<?php echo base_url('Settings/removesupuser'); ?>" data-toggle="ajaxModal" class="redbg width-100 btn mar_b6" aria-hidden="true" data-refresh="true">
                                        <?= $this->lang->line('remove_user') ?>
                                    </a>
                                </div>
                                <div class="clr"></div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-5 bd-subs-list">
                                <div class="col-md-6 col-xs-6 bd-subs-logo text-center ">

                                    <img src="<?php echo base_url() . "/uploads/images/hr-logo.jpg"; ?>" alt="crm">
                                    <span><?= lang('hr'); ?></span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="bd-numb-user blue-label mar_b6">
                                        <span>5</span>
                                        <p><?= lang('current_user'); ?></p>
                                    </div>
                                    <button type="button" class="greenbg width-100 btn mar_b6 under_development"><?= $this->lang->line('add_user') ?></button>
                                    <button type="button" class="redbg width-100 btn under_development"><?= $this->lang->line('remove_user') ?></button>
                                </div>
                                <div class="clr"></div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-5 bd-subs-list">
                                <div class="col-md-6 col-xs-6 bd-subs-logo text-center ">

                                    <img src="<?php echo base_url() . "/uploads/images/dbs-logo.jpg"; ?>" alt="crm">
                                    <span><?= lang('sys_data_storage'); ?></span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="bd-numb-user blue-label mar_b6">
                                        <span>5 GB</span>
                                        <p><?= lang('current_storage'); ?></p>
                                    </div>
                                    <button type="button" class="greenbg width-100 btn mar_b6 under_development"><?= $this->lang->line('add_storage') ?></button>
                                    <button type="button" class="redbg width-100 btn under_development"><?= $this->lang->line('remove_storage') ?></button>
                                </div>
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                        </div>
                    </div>
                    <div id="menu4" class="tab-pane fade in">
                        <h3>Under Development</h3>
                    </div>
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

<script>
    var i = $('#hdnTaxCnt').val();		//Set i Variable for Increase Tax Box 
    $(document).ready(function () {
<?php if (isset($setup) && $setup[0]['is_active'] == 0) { ?>
            $('#subli').trigger('click');
<?php } ?>


        $('.under_development').click(function () {

            BootstrapDialog.show({
                title: '<?php echo $this->lang->line('Information'); ?>',
                message: "<?php echo lang('under_process'); ?>",
                buttons: [{
                        label: '<?= lang('COMMON_LABEL_CANCEL'); ?>',
                        action: function (dialogItself) {
                            //jQuery('#add_contact' + removeNum).remove();
                            //add_row_no--;
                            dialogItself.close();
                            $('#confirm-id').on('hidden.bs.modal', function () {
                                $('body').addClass('modal-open');
                            });
                        }
                    }]
            });

        });

        var total_sub = $('#current_crm_users').text() * 20 + $('#current_pm_users').text() * 20 + $('#current_sup_users').text() * 15;
        $('#mon_cost').text(total_sub);
        $sup_amount = 15 * $('#support_user').val();
<?php if (isset($setup) && $setup[0]['is_active'] == 0 || $setup[0]['status'] == 0) { ?>
            $('#mon_cost').text(0);
<?php } else { ?>
            $('#mon_cost').text(total_sub);

<?php } ?>
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
                    title: 'Setting Module',
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
