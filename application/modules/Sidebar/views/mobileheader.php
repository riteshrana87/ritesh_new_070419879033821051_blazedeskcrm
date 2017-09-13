<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="navbar-header"> <a class="navbar-brand" href="<?= base_url() ?>"><img src="<?= base_url() ?>uploads/images/logo.png" alt="" /></a> </div>
<div class="navbar-collapse no-collapse">
    <ul class="nav navbar-nav navbar-right">
        <li><a href="javascript:;">Help</a></li>
        <?php if (isset($user_info) && !empty($user_info) && $user_info != "") { ?>
            <li> <a href="<?php echo base_url('Dashboard/logout/'); ?>"><?php echo lang('LOGOUT_HEADER_MENU_LABEL'); ?></a> </li>
            <li> <a href="<?php echo base_url('User/'); ?>"><?php echo lang('USER_LIST_HEADER_MENU_LABEL'); ?></a> </li>
        <?php } else { ?>
            <li> <a href="<?php echo base_url('Masteradmin/'); ?>"><?php echo lang('COMMON_LABEL_LOGIN'); ?></a> </li>
            <li> <a href="<?php echo base_url('User/registration/'); ?>"><?php echo lang('REGISTRATION_HEADER_MENU_LABEL'); ?></a> </li>
        <?php } ?>
        <li><a href="#"><?php echo lang('LANGUAGE_HEADER_MENU_LABEL'); ?></a></li>
        <li class="dropdown">
            <?php
            if (isset($selected_language) && $selected_language != "") {
                foreach ($selected_language as $row) {
                    ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $row['name']; ?><span class="caret"></span></a>
                <?php }
            } else {
                ?>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo lang('CR_SELECT_CAMPAIGN'); ?> <span class="caret"></span></a>
<?php } ?>
            <ul class="dropdown-menu">
                <!--<li><a href="<?php echo base_url('Set_language?lang=english'); ?>">English</a></li>
                <li><a href="<?php echo base_url('Set_language?lang=spanish'); ?>">Spanish</a></li>-->

                <?php
                $lang_data = getLanguages();
                foreach ($lang_data as $data) {
                    ?>
                    <li><a href="<?php echo base_url('Set_language?lang=' . $data['language_name']); ?>"><?= $data['name'] ?></a></li>
<?php } ?>

            </ul>

        </li>
        <li><a href="<?php echo base_url() . 'Mail'; ?>"><i class="fa fa-envelope fa-2x"> </i><span class="mailCount"><?php echo (messageCount() > 0) ? messageCount() : ''; ?></span>  </a> </li>
    </ul>
</div>
<div class="clr"></div>
<div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mobile-navbar-collapse" aria-expanded="false"> <span class="sr-only">Menu <span class="caret"></span></span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
</div>
<!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse" id="mobile-navbar-collapse">

    <div class="nav navbar-nav ">

        <div class="dropdown">
            <button  class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php
                $profile_src = base_url() . "uploads/images/profile-default.png";
                if (isset($user_info['PROFILE_PHOTO']) && $user_info['PROFILE_PHOTO'] != '') {
                    $explode_name = explode('.', $user_info['PROFILE_PHOTO']);
                    $thumbnail_name = $explode_name[0] . '_thumb.' . $explode_name[1];
                    $file = (FCPATH . "uploads/profile_photo/" . $thumbnail_name);

                    if (file_exists($file)) {
                        $profile_src = base_url() . "uploads/profile_photo/" . $thumbnail_name;
                    } else {
                        $profile_src = base_url() . "uploads/images/profile-default.png";
                    }
                }
                ?>
                <img src="<?= $profile_src ?>"/>
                    <?php /* ?><img src="<?= base_url() ?>uploads/images/mark-icon.png" alt="" /><?php */ ?>
                <span class="bd-pf-name">
                    <?php
                    if (isset($user_info) && !empty($user_info)) {
                        echo $user_info['FIRSTNAME'] . ' ' . $user_info['LASTNAME'];
                    }
                    ?>
                </span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu22">
                <li><a href="<?php echo base_url() . 'MyProfile'; ?>" ><?php echo lang('MY_PROFILE'); ?></a></li>
                <li><a href="<?php echo base_url() . 'MyProfile/ChangePassword'; ?>" ><?php echo lang('CHANGE_PASSWORD'); ?></a></li>
                <li><a href="<?php echo base_url() . 'Mail/' ?>" id="emailClientLogin" title="<?= lang('email_login') ?>"><?= $this->lang->line('email_login') ?></a></li>
                <?php if(checkPermission('Message','view')){ ?>
                <li><a href="<?php echo base_url('Message'); ?>"><?= $this->lang->line('MY_MESSAGES') ?></a></li>
                <?php }?>
                            
                <?php if(checkPermission('Meeting','view')){ ?>
                <li><a href="<?php echo base_url('Meeting'); ?>"><?= $this->lang->line('OWNER_MEETING') ?></a></li>
                <?php }?>
            </ul>
        </div>
        <div class="dropdown">
            <button style="display: block;    width: 100%;
                    text-align: left;" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                CRM
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <li><a href="<?php echo base_url(); ?>" <?php if ($cur_viewname == "") { ?>class="active"<?php } ?>>CRM Dashboard</a></li>
                <li><a href="<?php echo base_url() . 'Marketingcampaign'; ?>"><?= lang('marketing_campaigns') ?></a></li>
                <?php if (checkPermission ('SalesOverview', 'view')) { ?><li><a href="<?php echo base_url() . 'SalesOverview'; ?>"><?= lang('sales_overview') ?></a></li><?php } ?>
                <?php if (checkPermission ('CrmCompany', 'view')) { ?><li><a href="<?php echo base_url() . 'CrmCompany'; ?>" class="<?php
                       if ($cur_viewname == "CrmCompany") {
                           echo "active";
                       }
                       ?>"><?= lang('companies') ?></a></li><?php } ?>
                <?php if (checkPermission ('Contact', 'view')) { ?><li><a href="<?php echo base_url() . 'Contact'; ?>"><?= lang('contacts') ?></a></li><?php } ?>
                 <?php if (checkPermission ('Account', 'view')) { ?><li><a href="<?php echo base_url() . 'Account'; ?>" class="<?php
                       if ($cur_viewname == "Account") {
                           echo "active";
                       }
                       ?>"><?= lang('clients') ?></a></li><?php } ?>
                <?php if (checkPermission ('Estimates', 'view')) { ?>
					<li><a href="<?php echo base_url() . 'Estimates'; ?>"><?= lang('estimates') ?></a></li>
                <?php } ?>
				<?php if (checkPermission ('Opportunity', 'view')) { ?><li><a href="<?php echo base_url() . 'Opportunity'; ?>" class="<?php
                       if ($cur_viewname == "Opportunity") {
                           echo "active";
                       }
                       ?>"><?= lang('opportunities') ?></a></li><?php } ?>
                <li><a href="#">Cases</a></li>
                <li><a href="#">Files</a></li>
                <li><a href="<?php echo base_url() . 'Product'; ?>"><?= lang('products') ?></a></li>
				<?php if (checkPermission ('Emailtemplate', 'view')) { ?>
					<li><a href="<?php echo base_url() . 'Emailtemplate'; ?>"><?= lang('templates') ?></a></li>
                <?php } ?>
				<li><a href="#">Reports</a></li>
                <li><a href="<?= base_url('Currencysettings') ?>" >Currency</a></li>
                <li><a href=<?php echo base_url('Rolemaster/'); ?>>Settings</a></li>
            </ul>
        </div>
        <!-- PM menu start --> 
        <div class="dropdown">
            <button style="display: block;    width: 100%;
                    text-align: left;" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu-PM" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Project Management
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu-PM">
                <li><a href="<?php echo base_url('Projectmanagement/Projectdashboard'); ?>" <?php if ($cur_viewname == "Projectmanagement" && $this->uri->segment("2") == 'Projectdashboard') { ?>class="active"<?php } ?>><?= lang('project_management_dashboard') ?></a></li>
                <li><a href="<?php echo base_url('Projectmanagement'); ?>" <?php if ($cur_viewname == "Projectmanagement" && $this->uri->segment("2") == '') { ?>class=""<?php } ?>><?= lang('manage_projects') ?></a></li>
                <li><a href="<?php echo base_url('Projectmanagement/Milestone'); ?>" <?php if ($cur_viewname == "Projectmanagement" && $this->uri->segment("2") == 'Milestone') { ?>class=""<?php } ?>><?= lang('milestone') ?></a></li>
                <li><a href="<?php echo base_url('Projectmanagement/ProjectTask'); ?>" <?php if ($cur_viewname == "Projectmanagement" && $this->uri->segment("2") == 'ProjectTask') { ?>class=""<?php } ?>><?= lang('projecttask') ?></a></li>
                <li><a href="<?php echo base_url('Projectmanagement/Timesheets'); ?>" <?php if ($cur_viewname == "Projectmanagement" && $this->uri->segment("2") == 'Timesheets') { ?>class=""<?php } ?>><?= lang('timesheets') ?></a></li>
                <li><a href="<?php echo base_url('Projectmanagement/ProjectIncidents'); ?>" <?php if ($cur_viewname == "Projectmanagement" && $this->uri->segment("2") == 'ProjectIncidents') { ?>class=""<?php } ?>><?= lang('project_incidents') ?></a></li>
                <li><a href="<?php echo base_url('Projectmanagement/Activities'); ?>" <?php if ($cur_viewname == "Projectmanagement" && $this->uri->segment("2") == 'Activities') { ?>class=""<?php } ?>><?= lang('activities') ?></a></li>
                <li><a href="<?php echo base_url('Projectmanagement/Costs'); ?>" <?php if ($cur_viewname == "Projectmanagement" && $this->uri->segment("2") == 'Costs') { ?>class=""<?php } ?>><?= lang('costs') ?></a></li>
                <li><a href="<?php echo base_url('Projectmanagement/Filemanager'); ?>" <?php if ($cur_viewname == "Projectmanagement" && $this->uri->segment("2") == 'Filemanager') { ?>class=""<?php } ?>><?= lang('files') ?></a></li>
                <li><a href="<?php echo base_url('Projectmanagement/TeamMembers'); ?>" <?php if ($cur_viewname == "Projectmanagement" && $this->uri->segment("2") == 'TeamMembers') { ?>class=""<?php } ?>><?= lang('teammembers') ?></a></li>
                <li><a href="<?php echo base_url('Projectmanagement/Invoices'); ?>" <?php if ($cur_viewname == "Projectmanagement" && $this->uri->segment("2") == 'Invoices') { ?>class=""<?php } ?>><?= lang('invoices') ?></a></li>
                <li><a href="<?php echo base_url('ProjectStatus'); ?>" <?php if ($cur_viewname == "ProjectStatus") { ?>class=""<?php } ?> ><?php echo lang('TOP_MENU_MANAGE_PROJECT_STATUS'); ?></a></li>
                <li><a href="<?php echo base_url('Projectmanagement/ProjectIncidentsType'); ?>" <?php if ($cur_viewname == "Projectmanagement" && $this->uri->segment("2") == 'ProjectIncidentsType') { ?>class=""<?php } ?>><?= lang('projectincidentstype') ?></a></li>

            </ul>
        </div>

        <!-- PM menu end --> 
        <!-- Support menu start --> 
        <div class="dropdown">
            <button  class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu-PM" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Support 
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu-PM">
                <li><a href="<?php echo base_url('Support'); ?>" <?php if ($cur_viewname == "Support" && $this->uri->segment("2") == 'Support') { ?>class="active"<?php } ?>><?= lang('support_management_dashboard') ?></a></li>
                <li><a href="<?php echo base_url('Ticket'); ?>" <?php if ($cur_viewname == "Ticket" && $this->uri->segment("2") == '') { ?>class=""<?php } ?>><?= lang('ticket') ?></a></li>
                <li><a href="<?php echo base_url('Support/KnowledgeBase'); ?>" <?php if ($cur_viewname == "KnowledgeBase" && $this->uri->segment("2") == 'KnowledgeBase') { ?>class=""<?php } ?>><?= lang('know_base') ?></a></li>
                <!--<li><a href="<?php echo base_url('Support/LiveChat'); ?>" <?php if ($cur_viewname == "LiveChat" && $this->uri->segment("2") == 'LiveChat') { ?>class=""<?php } ?>><?= lang('live_chat') ?></a></li>-->
                <li><a href="<?php echo base_url('Company'); ?>" <?php if ($cur_viewname == "Companies" && $this->uri->segment("2") == 'Companies') { ?>class=""<?php } ?>><?= lang('companies') ?></a></li>
                <li><a href="<?php echo base_url() . 'Contact'; ?>" <?php if ($cur_viewname == "Contacts" && $this->uri->segment("2") == 'Contacts') { ?>class=""<?php } ?>><?= lang('contacts') ?></a></li>
                <li><a href="<?php echo base_url('Support/SupportReport'); ?>" <?php if ($cur_viewname == "Reports" && $this->uri->segment("2") == 'Reports') { ?>class=""<?php } ?>><?= lang('support_report') ?></a></li>
                <li><a href="<?php echo base_url('SupportSettings'); ?>" <?php if ($cur_viewname == "Settings" && $this->uri->segment("2") == 'Settings') { ?>class=""<?php } ?>><?= lang('settings_support') ?></a></li>
                <li><a href="<?php echo base_url('KnowledgeBaseSettings'); ?>" <?php if ($cur_viewname == "KnowledgeBaseSettings" && $this->uri->segment("2") == 'KnowledgeBaseSettings') { ?>class=""<?php } ?>><?= lang('settings_knowledge') ?></a></li>
                <li><a href="<?php echo base_url('SupportTeam'); ?>" <?php if ($cur_viewname == "SupportTeam" && $this->uri->segment("2") == 'SupportTeam') { ?>class=""<?php } ?>><?= lang('sup_team') ?></a></li>

            </ul>
        </div>
        <div class="dropdown">
            <button style="display: block;    width: 100%;
                    text-align: left;" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu-PM" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Settings 
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu-Settings">
                <?php if (checkPermission('Settings', 'view')) { ?>
                <li><a href="<?php echo base_url('Settings/'); ?>" ><?php echo lang('GENERAL_SETTINGS'); ?></a></li>
                <?php } ?>
                
                <?php if (checkPermission('User', 'view') && checkPermission('Settings', 'view')) { ?>
                <li><a href="<?php echo base_url('Settings/userSettings'); ?>" ><?php echo lang('MEETING_USER'); ?></a></li>
                <?php } ?>
                
                <?php if (checkPermission('Settings', 'view')) { ?>
                <li><a href="<?php echo base_url('Settings/emailSettings'); ?>" ><?php echo lang('update_email_settings'); ?></a></li>
                 <?php } ?>
                
                <?php if ($user_info['ROLE_TYPE'] == '39') { ?><li><a href="<?php echo base_url('Settings/subscription'); ?>" ><?= lang('billing_subscription'); ?></a></li><?php } ?>
                
                <li><a href="<?php echo base_url('Mail/mailconfig'); ?>" ><?php echo lang('email_client_config');?></a></li>

            </ul>
        </div>

        <!-- Support menu end --> 

    </div>
</div>
<?php /* Following variable related to parsley JS */ ?>

<!-- /.navbar-collapse --> 
