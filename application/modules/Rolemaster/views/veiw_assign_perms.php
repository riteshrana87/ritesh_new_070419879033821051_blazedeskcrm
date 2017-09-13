<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord) ? 'updatedata' : 'view_perms_to_role_list';
?>
<!DOCTYPE html>
<?php echo $this->session->flashdata('verify_msg'); ?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv bd-cust-tab">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                <div class="title"><?= $this->lang->line('viewPerms') ?></div>            
            </h4>
        </div>
        <ul class="nav nav-tabs ">
            <li role="presentation" id="CRM_LI" ><a  name="CRM" id="CRM" onclick="permsTab(this.id);"><?= $this->lang->line('cms') ?></a></li>
            <li role="presentation" id="PM_LI" ><a name="PM" id="PM" onclick="permsTab(this.id);"><?= $this->lang->line('pm') ?></a></li>
            <!--  <li role="presentation" id="Finance_LI" ><a name="Finance" id="Finance" onclick="permsTab(this.id);"><?= $this->lang->line('finance') ?></a></li> -->
            <li role="presentation" id="Support_LI" ><a name="Support" id="Support" onclick="permsTab(this.id);"><?= $this->lang->line('support') ?></a></li>
            <!--  <li role="presentation" id="HR_LI" ><a name="HR" id="HR" onclick="permsTab(this.id);"><?= $this->lang->line('hr') ?></a></li> -->
            <li role="presentation" id="User_LI" ><a name="User" id="User" onclick="permsTab(this.id);"><?= $this->lang->line('user') ?></a></li>
            <li role="presentation" id="settings_LI" ><a name="settings" id="settings" onclick="permsTab(this.id);"><?= $this->lang->line('settings') ?></a></li>				  		
        </ul>
        <?php
        $attributes = array("name" => "permissionform");
        echo form_open(base_url($path));
        ?>
<!--   <form id="viewPermission" accept-charset="utf-8" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate name="permissionform"> --> 
        <div class="modal-body">				
            <div class="form-group">
                <label id="datatable1" class="table table-striped" for="usertype"><?= $this->lang->line('user_name') ?>:
                    <?php
                    //$options = array('1'=>"Super Admin",'2'=>"Admin");
                    $options1 = array();
                    $options2 = array();
                    $selected = "";
                    foreach ($userType as $key => $value) {
                        if ($value['role_id'] == $this->uri->segment(3)) {
                            echo $value['role_name'];
                        }
                    }
                    ?>
                    <span class="text-danger"><?php echo form_error('username'); ?></span>

                </label>
            </div>  
            <div class="form-group" id="CRM_LIST">
<?php if (isset($hasPermission[0]['is_crm']) && $hasPermission[0]['is_crm'] == 1) { ?>
                    <table class="table table-responsive" >
                        <thead>

                        <th><?php echo lang('module_list') ?></th>
    <?php
    if (count($getPermList) > 0) {
        foreach ($getPermList as $perm) {
            ?>
                                <th><?php echo lang($perm['name']) ; ?></th>
                                <?php
                            }
                        }
                        ?>
                        </thead>
                        <tbody>
    <?php
    if (count($CRM_module_list) > 0) {
        foreach ($CRM_module_list as $modObj) {
            ?>
                                    <tr>

                                        <td><?php echo $modObj['module_name']; ?></td>
            <?php
            if (count($getPermList) > 0) {
                foreach ($getPermList as $perm) {
                    ?>

                                                <td>
                    <?php
                    foreach ($view_perms_to_role_list as $assignData) {
                        $checked = '';
                        if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                            echo $checked = '<i class="fa fa-check"></i>';
                        } else {
                            echo $checked = "";
                        }
                    }
                    ?>

                                                </td>
                    <?php
                }
            }
            ?>
                                    </tr>
                                        <?php
                                    }
                                }
                                ?>
                        </tbody>
                    </table> 
<?php } else { ?>
                    <p> <?php echo $this->lang->line('permission_error_msg'); ?> </p>
                <?php } ?>
            </div>
            <div class="form-group" id="PM_LIST">
<?php if (isset($hasPermission[0]['is_pm']) && $hasPermission[0]['is_pm'] == 1) { ?>
                    <table class="table table-responsive" >
                        <thead>

                        <th><?php echo lang('module_list') ?></th>
    <?php
    if (count($getPermList) > 0) {
        foreach ($getPermList as $perm) {
            ?>
                                <th><?php echo lang($perm['name']) ; ?></th>
                                <?php
                            }
                        }
                        ?>
                        </thead>
                        <tbody>
    <?php
    if (count($PM_module_list) > 0) {
        foreach ($PM_module_list as $modObj) {
            ?>
                                    <tr>

                                        <td><?php echo $modObj['module_name']; ?></td>
            <?php
            if (count($getPermList) > 0) {
                foreach ($getPermList as $perm) {
                    ?>

                                                <td>
                    <?php
                    foreach ($view_perms_to_role_list as $assignData) {
                        $checked = '';
                        if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                            echo $checked = '<i class="fa fa-check"></i>';
                        } else {
                            echo $checked = "";
                        }
                    }
                    ?>

                                                </td>
                    <?php
                }
            }
            ?>
                                    </tr>
                                        <?php
                                    }
                                }
                                ?>
                        </tbody>
                    </table> 
<?php } else { ?>
                    <p> <?php echo $this->lang->line('permission_error_msg'); ?> </p>
                <?php } ?>
            </div>
            <div class="form-group" id="Finance_LIST">
                <table class="table table-responsive" >
                    <thead>

                    <th><?php echo lang('module_list') ?></th>
<?php
if (count($getPermList) > 0) {
    foreach ($getPermList as $perm) {
        ?>
                            <th><?php echo lang($perm['name']); ?></th>
                            <?php
                        }
                    }
                    ?>
                    </thead>
                    <tbody>
<?php
if (count($Finance_module_list) > 0) {
    foreach ($Finance_module_list as $modObj) {
        ?>
                                <tr>

                                    <td><?php echo $modObj['module_name']; ?></td>
        <?php
        if (count($getPermList) > 0) {
            foreach ($getPermList as $perm) {
                ?>

                                            <td>
                <?php
                foreach ($view_perms_to_role_list as $assignData) {
                    $checked = '';
                    if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                        echo $checked = '<i class="fa fa-check"></i>';
                    } else {
                        echo $checked = "";
                    }
                }
                ?>

                                            </td>
                <?php
            }
        }
        ?>
                                </tr>
                                    <?php
                                }
                            }
                            ?>
                    </tbody>
                </table> 
            </div>
            <div class="form-group" id="Support_LIST">
<?php if (isset($hasPermission[0]['is_support']) && $hasPermission[0]['is_support'] == 1) { ?>
                    <table class="table table-responsive" >
                        <thead>
                        <th><?php echo lang('module_list') ?></th>
    <?php
    if (count($getPermList) > 0) {
        foreach ($getPermList as $perm) {
            ?>
                                <th><?php echo lang($perm['name']); ?></th>
                                <?php
                            }
                        }
                        ?>
                        </thead>
                        <tbody>
    <?php
    if (count($Support_module_list) > 0) {
        foreach ($Support_module_list as $modObj) {
            ?>
                                    <tr>

                                        <td><?php echo $modObj['module_name']; ?></td>
            <?php
            if (count($getPermList) > 0) {
                foreach ($getPermList as $perm) {
                    ?>

                                                <td>
                    <?php
                    foreach ($view_perms_to_role_list as $assignData) {
                        $checked = '';
                        if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                            echo $checked = '<i class="fa fa-check"></i>';
                        } else {
                            echo $checked = "";
                        }
                    }
                    ?>

                                                </td>
                    <?php
                }
            }
            ?>
                                    </tr>
                                        <?php
                                    }
                                }
                                ?>
                        </tbody>
                    </table> 
<?php } else { ?>
                    <p> <?php echo $this->lang->line('permission_error_msg'); ?> </p>
                <?php } ?>
            </div>
            <div class="form-group" id="HR_LIST">
                <table class="table table-responsive" >
                    <thead>

                    <th><?php echo lang('module_list') ?></th>
<?php
if (count($getPermList) > 0) {
    foreach ($getPermList as $perm) {
        ?>
                            <th><?php echo lang($perm['name']); ?></th>
                            <?php
                        }
                    }
                    ?>
                    </thead>
                    <tbody>
<?php
if (count($HR_module_list) > 0) {
    foreach ($HR_module_list as $modObj) {
        ?>
                                <tr>

                                    <td><?php echo $modObj['module_name']; ?></td>
        <?php
        if (count($getPermList) > 0) {
            foreach ($getPermList as $perm) {
                ?>

                                            <td>
                <?php
                foreach ($view_perms_to_role_list as $assignData) {
                    $checked = '';
                    if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                        echo $checked = '<i class="fa fa-check"></i>';
                    } else {
                        echo $checked = "";
                    }
                }
                ?>

                                            </td>
                <?php
            }
        }
        ?>
                                </tr>
                                    <?php
                                }
                            }
                            ?>
                    </tbody>
                </table> 
            </div>
            <div class="form-group" id="User_LIST">
                <table class="table table-responsive" >
                    <thead>

                    <th><?php echo lang('module_list') ?></th>
<?php
if (count($getPermList) > 0) {
    foreach ($getPermList as $perm) {
        ?>
                            <th><?php echo lang($perm['name']); ?></th>
                            <?php
                        }
                    }
                    ?>
                    </thead>
                    <tbody>
<?php
if (count($User_module_list) > 0) {
    foreach ($User_module_list as $modObj) {
        ?>
                                <tr>

                                    <td><?php echo $modObj['module_name']; ?></td>
        <?php
        if (count($getPermList) > 0) {
            foreach ($getPermList as $perm) {
                ?>

                                            <td>
                <?php
                foreach ($view_perms_to_role_list as $assignData) {
                    $checked = '';
                    if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                        echo $checked = '<i class="fa fa-check"></i>';
                    } else {
                        echo $checked = "";
                    }
                }
                ?>

                                            </td>
                <?php
            }
        }
        ?>
                                </tr>
                                    <?php
                                }
                            }
                            ?>
                    </tbody>
                </table> 
            </div>
            <div class="form-group" id="settings_LIST">
                <table class="table table-responsive" >
                    <thead>

                    <th><?php echo lang('module_list') ?></th>
<?php
if (count($getPermList) > 0) {
    foreach ($getPermList as $perm) {
        ?>
                            <th><?php echo lang($perm['name']); ?></th>
                            <?php
                        }
                    }
                    ?>
                    </thead>
                    <tbody>
<?php
if (count($settings_module_list) > 0) {
    foreach ($settings_module_list as $modObj) {
        ?>
                                <tr>

                                    <td><?php echo $modObj['module_name']; ?></td>
        <?php
        if (count($getPermList) > 0) {
            foreach ($getPermList as $perm) {
                ?>

                                            <td>
                <?php
                foreach ($view_perms_to_role_list as $assignData) {
                    $checked = '';
                    if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                        echo $checked = '<i class="fa fa-check"></i>';
                    } else {
                        echo $checked = "";
                    }
                }
                ?>

                                            </td>
                <?php
            }
        }
        ?>
                                </tr>
                                    <?php
                                }
                            }
                            ?>
                    </tbody>
                </table> 
            </div>                    	
        </div>
        <!--  <div class="modal-footer">
             <center> 
             <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>"> 
             <input type="hidden" name="editPerm" value="Edit Permissions">
            <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Save" />
                     
                                     <input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" /></center>								
                         
         </div> -->
<?php echo form_close(); ?>		
    </div>
</div>
<script type="text/javascript">
    $("#CRM_LIST").show();
    $("#PM_LIST").hide();
    $("#Finance_LIST").hide();
    $("#Support_LIST").hide();
    $("#HR_LIST").hide();
    $("#User_LIST").hide();
    $("#settings_LIST").hide();
    $("#CRM_LI").addClass("active");

    function permsTab(module) {

        if (module == "CRM") {
            $("#CRM_LIST").show();
            $("#PM_LIST").hide();
            $("#Finance_LIST").hide();
            $("#Support_LIST").hide();
            $("#HR_LIST").hide();
            $("#User_LIST").hide();
            $("#settings_LIST").hide();
            $("#CRM_LI").addClass("active");
            $("#PM_LI").removeClass("active");
            $("#Finance_LI").removeClass("active");
            $("#Support_LI").removeClass("active");
            $("#HR_LI").removeClass("active");
            $("#User_LI").removeClass("active");
            $("#settings_LI").removeClass("active");
        } else if (module == "PM") {
            $("#PM_LIST").show();
            $("#CRM_LIST").hide();
            $("#Finance_LIST").hide();
            $("#Support_LIST").hide();
            $("#HR_LIST").hide();
            $("#User_LIST").hide();
            $("#settings_LIST").hide();
            $("#CRM_LI").removeClass("active");
            $("#PM_LI").addClass("active");
            $("#Finance_LI").removeClass("active");
            $("#Support_LI").removeClass("active");
            $("#HR_LI").removeClass("active");
            $("#User_LI").removeClass("active");
            $("#settings_LI").removeClass("active");
        } else if (module == "Finance") {
            $("#PM_LIST").hide();
            $("#CRM_LIST").hide();
            $("#Finance_LIST").show();
            $("#Support_LIST").hide();
            $("#HR_LIST").hide();
            $("#User_LIST").hide();
            $("#settings_LIST").hide();
            $("#CRM_LI").removeClass("active");
            $("#PM_LI").removeClass("active");
            $("#Finance_LI").addClass("active");
            $("#Support_LI").removeClass("active");
            $("#HR_LI").removeClass("active");
            $("#User_LI").removeClass("active");
            $("#settings_LI").removeClass("active");
        } else if (module == "Support") {
            $("#PM_LIST").hide();
            $("#CRM_LIST").hide();
            $("#Finance_LIST").hide();
            $("#Support_LIST").show();
            $("#HR_LIST").hide();
            $("#User_LIST").hide();
            $("#settings_LIST").hide();
            $("#CRM_LI").removeClass("active");
            $("#PM_LI").removeClass("active");
            $("#Finance_LI").removeClass("active");
            $("#Support_LI").addClass("active");
            $("#HR_LI").removeClass("active");
            $("#User_LI").removeClass("active");
            $("#settings_LI").removeClass("active");
        } else if (module == "HR") {
            $("#PM_LIST").hide();
            $("#CRM_LIST").hide();
            $("#Finance_LIST").hide();
            $("#Support_LIST").hide();
            $("#HR_LIST").show();
            $("#User_LIST").hide();
            $("#settings_LIST").hide();
            $("#CRM_LI").removeClass("active");
            $("#PM_LI").removeClass("active");
            $("#Finance_LI").removeClass("active");
            $("#Support_LI").removeClass("active");
            $("#HR_LI").addClass("active");
            $("#User_LI").removeClass("active");
            $("#settings_LI").removeClass("active");
        } else if (module == "User") {
            $("#PM_LIST").hide();
            $("#CRM_LIST").hide();
            $("#Finance_LIST").hide();
            $("#Support_LIST").hide();
            $("#HR_LIST").hide();
            $("#User_LIST").show();
            $("#settings_LIST").hide();
            $("#CRM_LI").removeClass("active");
            $("#PM_LI").removeClass("active");
            $("#Finance_LI").removeClass("active");
            $("#Support_LI").removeClass("active");
            $("#HR_LI").removeClass("active");
            $("#User_LI").addClass("active");
            $("#settings_LI").removeClass("active");
        } else if (module == "settings") {
            $("#PM_LIST").hide();
            $("#CRM_LIST").hide();
            $("#Finance_LIST").hide();
            $("#Support_LIST").hide();
            $("#HR_LIST").hide();
            $("#User_LIST").hide();
            $("#settings_LIST").show();
            $("#CRM_LI").removeClass("active");
            $("#PM_LI").removeClass("active");
            $("#Finance_LI").removeClass("active");
            $("#Support_LI").removeClass("active");
            $("#HR_LI").removeClass("active");
            $("#User_LI").removeClass("active");
            $("#settings_LI").addClass("active");
        }

    }

</script>

