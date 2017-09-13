<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="navbar-header"> <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?= base_url() ?>uploads/images/logo.png" alt="" /></a> </div>
<div class="no-collapse">
    <ul class="nav navbar-nav navbar-right">
        <li>
            <a data-href="<?php echo base_url('Help/add'); ?>" data-toggle="ajaxModal" class="" aria-hidden="true" data-refresh="true">
                <?= $this->lang->line('HELP_HEADER_MENU_LABEL') ?>
            </a></li>
        <!-- by brt for email Link-->
        <li><a href="<?php echo base_url() . 'Mail'; ?>"><i class="fa fa-envelope fa-2x"> </i><span class="mailCount"><?php echo (messageCount() > 0) ? messageCount() : ''; ?></span>  </a> </li>

        <!-- brt ends here -->
        <?php if (isset($user_info) && !empty($user_info) && $user_info != "") { ?>
            <li> <a href="<?php echo base_url('Dashboard/logout/'); ?>"><?php echo lang('LOGOUT_HEADER_MENU_LABEL'); ?></a> </li>
            <?php /* ?><li> <a href="<?php echo base_url('User/');?>"><?php echo lang('USER_LIST_HEADER_MENU_LABEL');?></a> </li><?php */ ?>
        <?php } else { ?>
            <li> <a href="<?php echo base_url('Masteradmin/'); ?>"><?php echo lang('email_config'); ?></a> </li>
            <li> <a href="<?php echo base_url('User/registration/'); ?>"><?php echo lang('REGISTRATION_HEADER_MENU_LABEL'); ?></a> </li>
        <?php } ?>
        <li class="lang"> <?php echo lang('LANGUAGE_HEADER_MENU_LABEL'); ?> :
            <div class="dropdown pull-right">
                <?php
                if (isset($selected_language) && $selected_language != "") {
                    foreach ($selected_language as $row) {
                        ?>
                        <button class="dropdown-toggle whitebg cust-lang" type="button" data-toggle="dropdown"><?php echo $row['name']; ?> <span class="caret"></span></button>
                        <?php
                    }
                } else {
                    ?>
                    <button class="dropdown-toggle whitebg" type="button" data-toggle="dropdown"><?php echo lang('CR_SELECT_CAMPAIGN'); ?>  <span class="caret"></span></button>
                <?php } ?>
                <ul class="dropdown-menu absposition">
                    <!-- <li><a href="<?php echo base_url('Set_language?lang=english'); ?>">English</a></li>
                    <li><a href="<?php echo base_url('Set_language?lang=spanish'); ?>">Spanish</a></li> -->
                    <?php
                    $lang_data = getLanguages();
                    foreach ($lang_data as $data) {
						//pr($data);
                        ?>
                        <li><a href="<?php echo base_url('Set_language?lang=' . $data['language_name']); ?>"><?= $data['name'] ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </li>
    </ul>
</div>
<!--/.nav-collapse -->

<div class="clr"></div>
</div>
</div>
<div class="clr"></div>
<div class="menu-graybg">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-4 col-sm-4 col-lg-5">
                <div class="navbar-header pull-left">
                    <button data-toggle="collapse-side" data-target=".side-collapse" data-target-2=".side-collapse-container" type="button" class="navbar-toggle pull-left navbar-toggle1"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                </div>
                <?php
                $myProfileActiveClass = '';
                if ($param['menu_module'] == 'MyProfile') {
                    $myProfileActiveClass = 'active';
                }
                ?>
                <ul class="nav pull-left userbox">
                    <li class="dropdown"> 
                        <a href="#" class="dropdown-toggle <?php echo $myProfileActiveClass; ?>" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">

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
                            <?php if (isset($user_info) && !empty($user_info)) { ?>
                                <span class="bd-pf-name">
                                    <?php echo $user_info['FIRSTNAME'] . ' ' . $user_info['LASTNAME']; ?>
                                </span>
                            <?php }
                            ?>
                            <span class="caret"></span> </a>
                        <ul class="dropdown-menu absposition">
                            <li><a href="<?php echo base_url() . 'MyProfile'; ?>" ><?php echo lang('MY_PROFILE'); ?></a></li>
                            <li><a href="<?php echo base_url() . 'MyProfile/ChangePassword'; ?>" ><?php echo lang('CHANGE_PASSWORD'); ?></a></li>
                            <!-- by brt 09-3-016-->
                            <!--                             create email client url-->

                            <?php
                            $this->load->library('Encryption');  // this library is for encoding/decoding password
                            $converter = new Encryption;

                            $encodedUrl = $converter->mail_client_url($email_config_data);
                            ?>
                            <li>  <a href="<?php echo base_url() . 'Mail/?data=' . $encodedUrl['encoded'] ?>" id="emailClientLogin" title="<?= lang('email_login') ?>"><?= $this->lang->line('email_login') ?></a></li>
                            <?php if(checkPermission('Message','view')){ ?>
                            <li>  <a href="<?php echo base_url('Message'); ?>"><?= $this->lang->line('MY_MESSAGES') ?></a></li>
                            <?php }?>
                            
                            <?php if(checkPermission('Meeting','view')){ ?>
                            <li>  <a href="<?php echo base_url('Meeting'); ?>"><?= $this->lang->line('OWNER_MEETING') ?></a></li>
                             <?php }?>
                            <!-- brt ends-->
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="col-xs-12 col-md-8 col-sm-8 col-lg-7">
                <ul class="nav navbar-nav navbar-right navbar-main">

                    <li <?php if ($param['menu_module'] == "crm" && isset($param['menu_module'])) { ?>class="active"<?php } ?>><a href="<?php echo base_url(); ?>"><?php echo lang('crm'); ?> </a></li>
                    <li <?php if ($param['menu_module'] == "Projectmanagement" && isset($param['menu_module'])) { ?>class="active"<?php } ?>><a href="<?php echo base_url('Projectmanagement/Projectdashboard'); ?>"><?php echo lang('TOP_MENU_PROJECT_MANAGEMENT'); ?> </a></li>

                    <li class="hidden" <?php if ($param['menu_module'] == "finance" && isset($param['menu_module'])) { ?>class="active"<?php } ?>><a href="#"><?php echo lang('TOP_MENU_FINANCE'); ?></a></li>

                    <li <?php if ($param['menu_module'] == "support" && isset($param['menu_module'])) { ?>class="active"<?php } ?>><a href="<?php echo base_url('Support'); ?>"><?php echo lang('TOP_MENU_SUPPORT'); ?></a></li>
                    <li class="hidden" <?php if ($param['menu_module'] == "hr" && isset($param['menu_module'])) { ?>class="active"<?php } ?>><a href="#"><?php echo lang('hr'); ?></a></li>
<!--                    <li <?php if ($param['menu_module'] == "user" && isset($param['menu_module'])) { ?>class="active"<?php } ?>><a href="<?php echo base_url('User'); ?>"><?php echo lang('TOP_MENU_USER'); ?> </a></li>-->
           <!-- <li <?php if ($cur_viewname == "User") { ?>class="active"<?php } ?>><a href="<?php echo base_url('User'); ?>"><?php echo lang('TOP_MENU_USER'); ?> </a></li>-->

<!--<li <?php if ($param['menu_module'] == "settings" && isset($param['menu_module'])) { ?>class="active"<?php } ?> class="dropdown">
    <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo lang('TOP_MENU_SETTINGS'); ?> <span class="caret"></span></a>
    <ul class="dropdown-menu absposition">
        <li><a href="<?php echo base_url('Settings/'); ?>" ><?php echo lang('GENERAL_SETTINGS'); ?></a></li>
        <li><a href="<?php echo base_url('Rolemaster/'); ?>" ><?php echo lang('TOP_MENU_ROLLMANAGEMENT'); ?></a></li>
                
    </ul>
</li>-->
                    <li <?php if ($param['menu_module'] == "settings" && isset($param['menu_module'])) { ?>class="active"<?php } ?> class="dropdown">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo lang('TOP_MENU_SETTINGS'); ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu absposition">
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
                    </li>
                </ul>
            </div>
            <div class="clr"></div>
        </div>


    </div>


</div>


<?php //$cur_viewname;   ?>
<script>
   
    $(document).ready(function () {
        $(document).click(function (event) {
            var clickover = $(event.target);
            //alert(clickover);
            var _opened = $(".navbar-inverse").hasClass("side-collapse");
            if (_opened === true && !clickover.hasClass("navbar-toggle")) {
                // $("side-collapse").hide();
                $('.navbar-inverse').addClass('side-collapse in');
            }
            
            if($(".navbar-inverse").hasClass("addclsleft"))
            {
				$('.navbar-inverse').removeClass('in');
			}
			else if(_opened === true && !clickover.hasClass("navbar-toggle")){
				 $('.navbar-inverse').addClass('side-collapse in');
				}
				
        });
    });
</script>
