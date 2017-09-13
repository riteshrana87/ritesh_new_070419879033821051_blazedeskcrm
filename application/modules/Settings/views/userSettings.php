<?php
defined('BASEPATH') OR exit('No direct script access allowed');


if (!isset($_SESSION['setting_current_tab']) || $_SESSION['setting_current_tab'] == '') {
    $sess_array = array('setting_current_tab' => 'setting_user');
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
                <?= lang('SETTING_USER') ?>
            </li>
        </ul>
    </div>
</div>
<div class="clr"></div>
<div class="row">
    <div class="col-xs-12 col-md-12 bd-cust-tab"> <?php echo $this->session->flashdata('msg'); ?>
        <ul class="nav nav-tabs ">
            <li class="<?php
            if ($this->session->userdata('setting_current_tab') == 'setting_user') {
                echo "active";
            }
            ?>"><a data-toggle="pill" href="#setting_user">
                        <?= lang('SETTING_USER') ?>
                </a></li>
                <?php if(checkPermission('Rolemaster','view')){?>
            <li class="<?php
            if ($this->session->userdata('setting_current_tab') == 'setting_role_permission') {
                echo "active";
            }
            ?>"><a data-toggle="pill" href="#setting_role_permission">
                        <?= lang('SETTING_ROLE_PERMISSION') ?>
                </a></li> 
               <?php }?> 
        </ul>
        <div class="whitebox">
            <div class="pad-10">

                <div class="tab-content ">


                    <div id="setting_user" class="tab-pane fade in <?php
                    if ($this->session->userdata('setting_current_tab') == 'setting_user') {
                        echo "active";
                    }
                    ?>">
                        <div class="col-xs-12 col-sm-6 col-md-3 pull-right text-right col-md-offset-3">
                            <div class="row mb15">
                                <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">

                                    <div class="navbar-form navbar-left pull-right" id="searchForm">
                                        <div class="input-group">
                                            <input type="text" name="search_input_user" id="search_input_user" class="form-control" placeholder="<?= lang('EST_LISTING_SEARCH_FOR')?>">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="submit" title="<?= lang('search') ?>" id="submit_search_user" name="submit" ><i class="fa fa-search fa-x"></i></button>&nbsp;
                                                <button class="btn btn-default" type="button" title="<?= lang('reset') ?>" onclick="resetUser_data()" ><i class="fa fa-refresh fa-x"></i></button>
                                            </span> 
                                        </div>
                                    </div>
                                </div>     
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                        </div> 

<div class="clr"></div>

                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <h3 class="white-link">
                                    <?= $this->lang->line('USER_LIST_HEADER_MENU_LABEL') ?>
                                </h3>
                            </div>
                            <?php if (checkPermission('User', 'add')) { ?>
                                <div class="col-xs-6 col-sm-6 text-right col-md-6 col-lg-6 "> <a data-href="<?php echo base_url('User/registration'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="btn btn-blue">
                                        <?= $this->lang->line('create_user') ?>
                                    </a>
                                    <div class="clr"></div>
                                </div>
                            <?php } ?>
                            <div class="clr"></div>
                        </div>
                        <!-- Listing of User List Table: Start -->
                        <div id="tableUserDiv">

                        </div>

                    </div>
					  <?php if (checkPermission('Rolemaster', 'view')) { ?>

                    <div id="setting_role_permission" class="tab-pane fade in <?php
                    if ($this->session->userdata('setting_current_tab') == 'setting_role_permission') {
                        echo "active";
                    }
                    ?>">
                        <div class="col-xs-12 col-sm-6 col-md-3 pull-right text-right col-md-offset-3">
                            <div class="row mb15">
                               
                                <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">

                                    <div class="navbar-form navbar-left pull-right" id="searchForm">
                                        <div class="input-group">
                                            <input type="text" name="search_input_role" id="search_input_role" class="form-control" placeholder="<?= lang('EST_LISTING_SEARCH_FOR')?>">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="submit" id="submit_search_role" name="submit" title="<?= lang('search') ?>" ><i class="fa fa-search fa-x"></i></button>&nbsp;
                                                <button class="btn btn-default" type="button" onclick="reset_data_role()" title="<?= lang('reset') ?>" ><i class="fa fa-refresh fa-x"></i></button>
                                            </span> </div>
                                    </div>

                                </div>     
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                        </div>

                       <div class="clr"></div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                               
                                <h3 class="white-link"> <?= $this->lang->line('SETTING_ROLE_PERMISSION') ?></h3>
                                
                            </div>
                            <div class="col-xs-6 col-sm-6 text-right col-md-6 col-lg-6 ">
                                <div class="row"><?php if (checkPermission('Rolemaster', 'add')) { ?>
                                        <a class="btn btn-blue" data-href="<?php echo base_url('Rolemaster/add'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" >
                                            <?= $this->lang->line('add_role') ?>
                                        </a>
                                    <?php } ?>
                                </div> 
                            </div>
                            <div class="clr"></div>
                            
                        </div>
                         
                        <div id="tableRoleManagementDiv">
                        </div>
                    </div>
                    
                    <?php }?>


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

<!-- Start script for User -->

<script>
    function getUsersData()
    {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('User/'); ?>',
            data: {},
            success: function (html)
            {
                $('#tableUserDiv').html(html);
            }
        });
    }

    $(document).ready(function () {

        getUsersData();
        $('body').delegate('#submit_search_user', 'click', function () {
            paginationTCClick();
            return false;
        });
        $('body').delegate('#search_input_user', 'keyup', function (event) {
            if (event.keyCode == 13) {
                paginationTCClick();
            }
            return false;
        });

        function paginationTCClick() {
            //  var href = $(this).attr('href');
            var href = '<?php echo base_url('User'); ?>';
            $("#rounded-corner").css("opacity", "0.4");
            var search = $('#search_input_user').val();
            $.ajax({
                type: "GET",
                url: href,
                data: {search: search},
                success: function (response)
                {
                    //alert(response);
                    $("#rounded-corner").css("opacity", "1");
                    $("#tableUserDiv").empty();
                    $("#tableUserDiv").html(response);

                }
            });
            return false;
        }


        $("body").delegate("#tableUserDiv ul.tsc_pagination a", "click", function () {
            var href = $(this).attr('href');
            var search = $('#search_input_user').val();
            $.ajax({
                type: "GET",
                url: href,
                data: {search: search},
                success: function (response)
                {
                    $("#tableUserDiv").empty();
                    $("#tableUserDiv").html(response);

                    return false;
                }
            });
            return false;
        });

        $("body").delegate("#tableUserDiv th.sortUserList a", "click", function () {
            var href = $(this).attr('href');
            var search = $('#search_input_user').val();
            $.ajax({
                type: "GET",
                url: href,
                data: {search: search},
                success: function (response)
                {
                    $("#tableUserDiv").empty();
                    $("#tableUserDiv").html(response);

                    return false;
                }
            });
            return false;
        });
    });
    function resetUser_data()
    {
        $("#search_input_user").val("");
        $("#submit_search_user").trigger("click");

    }
</script>
<!-- End script for USer-->

<!-- Start script for Role Management -->

<script>
    function getRoleManagementData()
    {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('Rolemaster/'); ?>',
            data: {},
            success: function (html)
            {
                $('#tableRoleManagementDiv').html(html);
            }
        });
    }

    $(document).ready(function () {

        getRoleManagementData();
        $('body').delegate('#submit_search_role', 'click', function () {
            paginationRoleClick();
            return false;
        });
        $('body').delegate('#search_input_role', 'keyup', function (event) {
            if (event.keyCode == 13) {
                paginationRoleClick();
            }
            return false;
        });

        function paginationRoleClick() {
            //  var href = $(this).attr('href');
            var href = '<?php echo base_url('Rolemaster'); ?>';
            $("#rounded-corner").css("opacity", "0.4");
            var search = $('#search_input_role').val();
            $.ajax({
                type: "GET",
                url: href,
                data: {search: search},
                success: function (response)
                {
                    //alert(response);
                    $("#rounded-corner").css("opacity", "1");
                    $("#tableRoleManagementDiv").empty();
                    $("#tableRoleManagementDiv").html(response);

                }
            });
            return false;
        }


        $("body").delegate("#tableRoleManagementDiv ul.tsc_pagination a", "click", function () {
            var href = $(this).attr('href');
            var search = $('#search_input_role').val();
            $.ajax({
                type: "GET",
                url: href,
                data: {search: search},
                success: function (response)
                {
                    $("#tableRoleManagementDiv").empty();
                    $("#tableRoleManagementDiv").html(response);

                    return false;
                }
            });
            return false;
        });

        $("body").delegate("#tableRoleManagementDiv th.sortRoleList a", "click", function () {
            var href = $(this).attr('href');
            var search = $('#search_input_role').val();
            $.ajax({
                type: "GET",
                url: href,
                data: {search: search},
                success: function (response)
                {
                    $("#tableRoleManagementDiv").empty();
                    $("#tableRoleManagementDiv").html(response);

                    return false;
                }
            });
            return false;
        });
    });
    function reset_data_role()
    {
        $("#search_input_role").val("");
        $("#submit_search_role").trigger("click");

    }
</script>
<!-- End script for Role Management-->