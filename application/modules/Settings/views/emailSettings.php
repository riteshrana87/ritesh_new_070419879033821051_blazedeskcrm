<?php
defined('BASEPATH') OR exit('No direct script access allowed');


if (!isset($_SESSION['setting_current_tab']) || $_SESSION['setting_current_tab'] == '') {
    $sess_array = array('setting_current_tab' => 'email_configuration');
    $this->session->set_userdata($sess_array);
}

$flg_email_configuration_setting = 0;
$flg_company_email_settings = 0;
$flg_email_notification_settings = 0;

if (isset($_SESSION['setting_current_tab']) && $_SESSION['setting_current_tab'] == 'email_configuration') {
    $flg_email_configuration_setting = 1;
}

if (isset($_SESSION['setting_current_tab']) && $_SESSION['setting_current_tab'] == 'email_settigns') {
    $flg_company_email_settings = 1;
}

if (isset($_SESSION['setting_current_tab']) && $_SESSION['setting_current_tab'] == 'email_notification') {
    $flg_email_notification_settings = 1;
}
?>

<div class="row">
    <div class="col-md-6 col-md-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url(); ?>Settings">
                    <?= lang('TOP_MENU_SETTINGS') ?>
                </a></li>
            <li class="active">
                <?= lang('SETTING_EMAIL_SETTINGS') ?>
            </li>
        </ul>
    </div>
</div>
<div class="clr"></div>
<div class="row">
    <div class="col-xs-12 col-md-12 bd-cust-tab"> 
    <?php //echo $this->session->flashdata('msg'); ?>
    <?php if ($this->session->flashdata('msg')) { ?>
            <div class='alert alert-success text-center'> <?php echo $this->session->flashdata('msg'); ?></div>
        <?php } ?>
          <?php if ($this->session->flashdata('error_msg')) { ?>
            <div class='alert alert-danger text-center'> <?php echo $this->session->flashdata('error_msg'); ?></div>
        <?php } ?>
        <ul class="nav nav-tabs ">
            <?php if(checkPermission('Settings','view')){ ?>
            <li class="<?php
            if ($flg_email_configuration_setting == 1) {
                echo "active";
            }
            ?>"><a data-toggle="pill" href="#email_settings">
                        <?= lang('SETTING_EMAIL_CONFIGURATION') ?>
                </a>
            </li>
            <?php }?>
             
            <?php if(checkPermission('Settings','view')){ ?>
            <li class="<?php
            if ($flg_email_notification_settings == 1) {
                echo "active";
            }
            ?>"><a data-toggle="pill" href="#email_notification">
                        <?= $this->lang->line('SETTING_NOTIFICATION_SETTINGS') ?>
                </a>
            </li>
            <?php }?>
        </ul>
        <div class="whitebox">
            <div class="pad-10">

                <div class="tab-content ">

                    
                    <div id="email_settings" class="tab-pane fade in <?php
                    if ($flg_email_configuration_setting == 1) {
                        echo "active";
                    }
                    ?>">
                             <?php $formAction = !empty($editRecord) ? 'updatedata' : 'insertdata'; ?>
                        <h3>     <?= $this->lang->line('SETTING_EMAIL_CONFIGURATION') ?></h3>
                        <form action="<?php echo base_url(); ?>Settings/updatedata" name="emailsettings" id="emailsettings" data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate >
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group bd-form-group" >
                                        <label for="company_email" class="col-lg-3">
                                            <?= $this->lang->line('company_email') ?>
                                            *</label>
                                        <div class="col-lg-9"><input class="form-control" name="company_email" placeholder="<?= $this->lang->line('company_email') ?>" data-parsley-trigger="change" required="" type="email" value="<?PHP
                                            if ($formAction == "insertdata") {
                                                echo set_value('company_email');
                                                ?><?php } else { ?><?= !empty($editRecord['company_email']) ? $editRecord['company_email'] : '' ?><?php } ?>" /></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="email_protocol" class="col-lg-3">
                                            <?= $this->lang->line('email_protocol') ?>
                                            *</label>
                                        <div class="col-lg-9"> <?php
                                            $options = array('smtp' => "smtp", 'mail' => "mail", 'imap' => "imap");
                                            $name = "email_protocol";
                                            if ($formAction == "insertdata") {
                                                $selected = 1;
                                            } else {
                                                $selected = $editRecord['email_protocol'];
                                            }
                                            echo dropdown($name, $options, $selected);
                                            ?></div>
                                    </div>
                                </div>

                                <div class="clr"></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="smtp_host" class="col-lg-3">
                                            <?= $this->lang->line('smtp_host') ?>
                                            *</label>
                                        <div class="col-lg-9"><input class="form-control" name="smtp_host" placeholder="<?= $this->lang->line('smtp_host') ?>" data-parsley-trigger="change" required="" type="text" value="<?PHP
                                            if ($formAction == "insertdata") {
                                                echo set_value('smtp_host');
                                                ?><?php } else { ?><?= !empty($editRecord['smtp_host']) ? $editRecord['smtp_host'] : '' ?><?php } ?>" /></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="smtp_user" class="col-lg-3">
                                            <?= $this->lang->line('smtp_user') ?>
                                            *</label>
                                        <div class="col-lg-9"> <input class="form-control" name="smtp_user" placeholder="<?= $this->lang->line('smtp_user') ?>" type="text" value="<?PHP
                                            if ($formAction == "insertdata") {
                                                echo set_value('smtp_user');
                                                ?><?php } else { ?><?= !empty($editRecord['smtp_user']) ? $editRecord['smtp_user'] : '' ?><?php } ?>" required  type="email"/></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="smtp_pass" class="col-lg-3">
                                            <?= $this->lang->line('smtp_pass') ?>
                                            *</label>
                                        <div class="col-lg-9"><input class="form-control" id="smtp_pass" name="smtp_pass" placeholder="<?= $this->lang->line('smtp_pass') ?>" type="password"  required=""  value="<?PHP
                                            if ($formAction == "insertdata") {
                                                echo set_value('smtp_pass');
                                                ?><?php } else { ?><?= !empty($editRecord['smtp_pass']) ? $editRecord['smtp_pass'] : '' ?><?php } ?>" /></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="smtp_port" class="col-lg-3">
                                            <?= $this->lang->line('smtp_port') ?>
                                            *</label>
                                        <div class="col-lg-9"><input class="form-control" name="smtp_port" placeholder="<?= $this->lang->line('smtp_port') ?>" type="text" value="<?PHP
                                            if ($formAction == "insertdata") {
                                                echo set_value('smtp_port');
                                                ?><?php } else { ?><?= !empty($editRecord['smtp_port']) ? $editRecord['smtp_port'] : '' ?><?php } ?>" required="" /></div>
                                        <div class="clr"></div>
                                    </div>
                                    <div class="clr"></div>
                                </div>
                                <div class="clr"></div>
                                
                                <?php if(checkPermission('Settings','edit')){?>
                                <center>
                                    <input name="id" type="hidden" value="<?= !empty($editRecord['id']) ? $editRecord['id'] : '' ?>" />
                                    <input type="submit" class="btn btn-lg btn-green" name="submit_btn" id="submit_btn" value="<?php echo lang('EST_EDIT_SAVE'); ?>">
                                </center>
                                <?php }?>
                            </div>
                        </form>
                    </div>
                    
                   
                    
                    <div id="email_notification" class="tab-pane fade in <?php
                    if ($flg_email_notification_settings == 1) {
                        echo "active";
                    }
                    ?>"> 
                        <?php $formAction = !empty($editRecord) ? 'updatedata' : 'insertdata'; ?>
                        <h3> <?= $this->lang->line('EMAIL_NOTIFICATION_SETTINGS') ?></h3>
                        <form action="<?php echo base_url(); ?>Settings/updatemailNotification" name="emailsettings" id="emailsettings" data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate >
                            <div class="row">
                                
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group bd-form-group" >
                                        <label for="company_email" class="col-lg-3">
                                            <?= $this->lang->line('EMAIL_GLOBAL_EMAIL') ?>
                                            *</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="global_email" placeholder="<?= $this->lang->line('EMAIL_GLOBAL_EMAIL') ?>" data-parsley-trigger="change" required="" type="email" value="<?PHP
                                            if ($formAction == "insertdata") {
                                                echo set_value('global_email');
                                                ?><?php } else { ?><?= !empty($editRecordNotification['global_email']) ? $editRecordNotification['global_email'] : '' ?><?php } ?>" />
                                        </div>
                                        <div class="clr"></div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="crm_email" class="col-lg-3">
                                            <?= $this->lang->line('EMAIL_CRM_EMAIL') ?>
                                            *</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="crm_email" placeholder="<?= $this->lang->line('EMAIL_CRM_EMAIL') ?>" data-parsley-trigger="change" required="" type="email" value="<?PHP
                                            if ($formAction == "insertdata") {
                                                echo set_value('crm_email');
                                                ?><?php } else { ?><?= !empty($editRecordNotification['crm_email']) ? $editRecordNotification['crm_email'] : '' ?><?php } ?>" />
                                        </div>
                                         <div class="clr"></div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="pm_email" class="col-lg-3">
                                            <?= $this->lang->line('EMAIL_PM_EMAIL') ?>
                                            *</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="pm_email" placeholder="<?= $this->lang->line('EMAIL_PM_EMAIL') ?>" data-parsley-trigger="change" required="" type="email" value="<?PHP
                                            if ($formAction == "insertdata") {
                                                echo set_value('pm_email');
                                                ?><?php } else { ?><?= !empty($editRecordNotification['pm_email']) ? $editRecordNotification['pm_email'] : '' ?><?php } ?>" />
                                        </div>
                                         <div class="clr"></div>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="finance_email" class="col-lg-3">
                                            <?= $this->lang->line('EMAIL_FINANCE_EMAIL') ?>
                                            *</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="finance_email" placeholder="<?= $this->lang->line('EMAIL_FINANCE_EMAIL') ?>" data-parsley-trigger="change" required="" type="email" value="<?PHP
                                            if ($formAction == "insertdata") {
                                                echo set_value('finance_email');
                                                ?><?php } else { ?><?= !empty($editRecordNotification['finance_email']) ? $editRecordNotification['finance_email'] : '' ?><?php } ?>" />
                                        </div>
                                         <div class="clr"></div>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="support_email" class="col-lg-3">
                                            <?= $this->lang->line('EMAIL_SUPPORT_EMAIL') ?>
                                            *</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="support_email" placeholder="<?= $this->lang->line('EMAIL_SUPPORT_EMAIL') ?>" data-parsley-trigger="change" required="" type="email" value="<?PHP
                                            if ($formAction == "insertdata") {
                                                echo set_value('support_email');
                                                ?><?php } else { ?><?= !empty($editRecordNotification['support_email']) ? $editRecordNotification['support_email'] : '' ?><?php } ?>" />
                                        </div>
                                         <div class="clr"></div>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group bd-form-group">
                                        <label for="hr_email" class="col-lg-3">
                                            <?= $this->lang->line('EMAIL_HR_EMAIL') ?>
                                            *</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="hr_email" placeholder="<?= $this->lang->line('EMAIL_HR_EMAIL') ?>" data-parsley-trigger="change" required="" type="email" value="<?PHP
                                            if ($formAction == "insertdata") {
                                                echo set_value('hr_email');
                                                ?><?php } else { ?><?= !empty($editRecordNotification['hr_email']) ? $editRecordNotification['hr_email'] : '' ?><?php } ?>" />
                                        </div>
                                       <div class="clr"></div>
                                    </div>
                                </div>

                                <?php if(checkPermission('Settings','edit')){?>
                                <center>
                                    <input name="id" type="hidden" value="<?= !empty($editRecordNotification['id']) ? $editRecordNotification['id'] : '' ?>" />
                                    <input type="submit" class="btn btn-lg btn-green" name="submit_btn" id="submit_btn" value="<?php echo lang('EST_EDIT_SAVE'); ?>">
                                </center>
                                <?php }?>
                            </div>
                           
                        </form>
                    </div>
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

