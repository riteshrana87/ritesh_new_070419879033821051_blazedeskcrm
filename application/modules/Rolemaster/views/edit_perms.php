<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = 'insertAssginPerms/';
$path = $crnt_view . '/' . $formAction;
//pr($getModuleCounts);
// pr($assignedModule);
?>
<!DOCTYPE html>
<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" title="<?php echo lang('close') ?>" >&times;</button>
            <h4 class="modal-title">
            <div class="title"><?PHP if($formAction == "insertAssginPerms"){ ?><?=$this->lang->line('assigned_perms_list')?><?php }else{ ?><?=$this->lang->line('edit_perms')?><?php }?>
            </div>
            </h4>
        </div>
        <ul class="nav nav-pills">
			  <li role="presentation" id="editCRM_LI" ><a  name="CRM" id="CRM" onclick="editpermsTab(this.id);"><?= $this->lang->line('cms') ?></a></li>
			  <li role="presentation" id="editPM_LI" ><a name="PM" id="PM" onclick="editpermsTab(this.id);"><?= $this->lang->line('pm') ?></a></li>
			  <!--  <li role="presentation" id="editFinance_LI" ><a name="Finance" id="Finance" onclick="editpermsTab(this.id);"><?= $this->lang->line('finance') ?></a></li> -->
			  <li role="presentation" id="editSupport_LI" ><a name="Support" id="Support" onclick="editpermsTab(this.id);"><?= $this->lang->line('support') ?></a></li>
			  <!--  <li role="presentation" id="editHR_LI" ><a name="HR" id="HR" onclick="editpermsTab(this.id);"><?= $this->lang->line('hr') ?></a></li> -->
			  <li role="presentation" id="editUser_LI" ><a name="User" id="User" onclick="editpermsTab(this.id);"><?= $this->lang->line('user') ?></a></li>
			  <li role="presentation" id="editsettings_LI" ><a name="settings" id="settings" onclick="editpermsTab(this.id);"><?= $this->lang->line('settings') ?></a></li>				  		
		</ul>
        <form id="assignPermission" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate name="permissionform">
            <div class="modal-body">				
                <div class="form-group">
                        <label for="usertype"><?= $this->lang->line('user_name') ?>:</label>
                        <?php
//$options = array('1'=>"Super Admin",'2'=>"Admin");
                        $options1 = array();
                        $options2 = array();
                        $selected = "";
                        foreach ($userType as $key => $value) {
                            if ($this->uri->segment(3) == $value['role_id']) {
                                echo $value['role_name'];
                                echo "<input type='hidden' name='usertype' value='".$value['role_id']."'>";
                            }                  
                        }
                       
                        ?>
                        <span class="text-danger"><?php echo form_error('user_name'); ?></span>
                    </div>  
               
               			<div class="form-group" id="edit_CRM_LIST" >
               			  <?php if(isset($hasPermission[0]['is_crm']) && $hasPermission[0]['is_crm'] == 1){?>
               			  <?php if(!empty($getModuleCounts) && isset($getModuleCounts['crm_user']) && $getModuleCounts['crm_user'] >0 ) {?>
               			  <?php if(isset($assignedModule) && ($assignedModule[0]['crm_user']=="" || $assignedModule[0]['crm_user']==0 ) ){?>
               			      <p> <?php echo $this->lang->line('CRM_module_limit_over'); ?> </p>
               			  <?php }elseif(isset($getModuleCounts['crm_user']) && $getModuleCounts['crm_user'] >0 ){?>
                        		<table class="table table-responsive" >
                            <thead>
                            <th><?php echo lang('module_list') ?></th>
                            <?php
                            if (count($getPermList) > 0) {
                                foreach ($getPermList as $perm) {
                                    ?>
                                   
                                    <th><input type="checkbox" class="edit_CRM_LIST_parent_horizontal_checkbox" data-tag="child_edit_CRM_LIST_<?php echo $perm['name']; ?>" data-box="box_edit_CRM_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang($perm['name']); ?></th>
                                    
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="edit_CRM_LIST_parent_horizontal_checkbox_All" data-tag="parent_edit_CRM_LIST_<?php echo $perm['name']; ?>"/><?php echo lang('all_perm'); ?></th>
                            </thead>
                            <tbody>
                                <?php
                                if (count($CRM_module_list) > 0) {
                                    foreach ($CRM_module_list as $modObj) {
                                    	$counter = 0;
                                        ?>
                                        <tr>

                                            <td><?php echo $modObj['module_unique_name']; ?></td>
                                            <?php
                                            if (count($getPermList) > 0) {
                                                foreach ($getPermList as $perm) {
                                                    ?>

                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>"
                                                        <?php
                                                        foreach ($view_perms_to_role_list as $assignData) {
                                                            $checked = '';
                                                            if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                                                                echo $checked = "checked=true";
                                                                $counter++;
                                                            } else {
                                                                echo $checked = "";
                                                            }
                                                        }
                                                        ?>

                                                              class="child <?php echo $modObj['module_unique_name']; ?> child_edit_CRM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_edit_CRM_LIST_<?php echo $perm['name']; ?>" ></td>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                
                                                <td><input type="checkbox" <?php echo ($counter==4)?'checked':'';?> class="parent <?php echo $modObj['module_unique_name']; ?> parent_edit_CRM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="edit_all_CRM_LIST" ></td>
                                            <!--  <td><input type="checkbox" name=""></td> -->
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                      		<?php }?>
                        <?php }else{?>
                        <table class="table table-responsive" >
                            <thead>
                            <th><?php echo lang('module_list') ?></th>
                            <?php
                            if (count($getPermList) > 0) {
                                foreach ($getPermList as $perm) {
                                    ?>
                                   
                                    <th><input type="checkbox" class="edit_CRM_LIST_parent_horizontal_checkbox" data-tag="child_edit_CRM_LIST_<?php echo $perm['name']; ?>" data-box="box_edit_CRM_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang($perm['name']); ?></th>
                                    
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="edit_CRM_LIST_parent_horizontal_checkbox_All" data-tag="parent_edit_CRM_LIST_<?php echo $perm['name']; ?>"/><?php echo lang('all_perm'); ?></th>
                            </thead>
                            <tbody>
                                <?php
                                if (count($CRM_module_list) > 0) {
                                    foreach ($CRM_module_list as $modObj) {
                                    	$counter = 0;
                                        ?>
                                        <tr>

                                            <td><?php echo $modObj['module_name']; ?></td>
                                            <?php
                                            if (count($getPermList) > 0) {
                                                foreach ($getPermList as $perm) {
                                                    ?>

                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>"
                                                        <?php
                                                        foreach ($view_perms_to_role_list as $assignData) {
                                                            $checked = '';
                                                            if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                                                                echo $checked = "checked=true";
                                                                $counter++;
                                                            } else {
                                                                echo $checked = "";
                                                            }
                                                        }
                                                        ?>

                                                              class="child <?php echo $modObj['module_unique_name']; ?> child_edit_CRM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_edit_CRM_LIST_<?php echo $perm['name']; ?>" ></td>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                
                                                <td><input type="checkbox" <?php echo ($counter==4)?'checked':'';?> class="parent <?php echo $modObj['module_unique_name']; ?> parent_edit_CRM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="edit_all_CRM_LIST" ></td>
                                            <!--  <td><input type="checkbox" name=""></td> -->
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php }?>
                         <?php }else{ ?>
                 <p> <?php echo $this->lang->line('permission_error_msg'); ?> </p>
                 <?php }?> 
                    </div>   
                   <div class="form-group" id="edit_PM_LIST" >
                    <?php if(isset($hasPermission[0]['is_pm']) && $hasPermission[0]['is_pm'] == 1){?>
                    <?php if(!empty($getModuleCounts) && isset($getModuleCounts['pm_user']) && $getModuleCounts['pm_user'] >0 ) {?>
                      <?php if(isset($assignedModule) && ($assignedModule[0]['pm_user']=="" || $assignedModule[0]['pm_user']==0 ) ){?>
                      <p> <?php echo $this->lang->line('PM_module_limit_over'); ?> </p>
                      <?php }elseif (isset($getModuleCounts['pm_user']) && $getModuleCounts['pm_user'] >0 ){?>
                       <table class="table table-responsive" >
                            <thead>

                            <th><?php echo lang('module_list') ?></th>
                            <?php
                            if (count($getPermList) > 0) {
                                foreach ($getPermList as $perm) {
                                    ?>
                                    <th><input type="checkbox" class="edit_PM_LIST_parent_horizontal_checkbox" data-tag="child_edit_PM_LIST_<?php echo $perm['name']; ?>" data-box="box_edit_PM_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang($perm['name']); ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="edit_PM_LIST_parent_horizontal_checkbox_All" data-tag="parent_edit_PM_LIST_<?php echo $perm['name']; ?>" /><?php echo lang('all_perm'); ?></th>
                            </thead>
                            <tbody>
                                <?php
                                if (count($PM_module_list) > 0) {
                                    foreach ($PM_module_list as $modObj) {
                                    	$counter = 0;
                                        ?>
                                        <tr>

                                            <td><?php echo $modObj['module_name']; ?></td>
                                            <?php
                                            if (count($getPermList) > 0) {
                                                foreach ($getPermList as $perm) {
                                                    ?>

                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>"
                                                        <?php
                                                        foreach ($view_perms_to_role_list as $assignData) {
                                                            $checked = '';
                                                            if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                                                                echo $checked = "checked=true";
                                                                $counter++;
                                                            } else {
                                                                echo $checked = "";
                                                            }
                                                        }
                                                        ?>

                                                              class="child <?php echo $modObj['module_unique_name']; ?> child_edit_PM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_edit_PM_LIST_<?php echo $perm['name']; ?>" ></td>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <td><input type="checkbox" <?php echo ($counter==4)?'checked':'';?> class="parent <?php echo $modObj['module_unique_name']; ?> parent_edit_PM_LIST_<?php echo $perm['name']; ?>"" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="edit_all_PM_LIST" ></td>
                                            <!--  <td><input type="checkbox" name=""></td> -->
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table> 
                      <?php }?>	
                    
                    <?php }else{ ?>
                     <table class="table table-responsive" >
                            <thead>

                            <th><?php echo lang('module_list'); ?></th>
                            <?php
                            if (count($getPermList) > 0) {
                                foreach ($getPermList as $perm) {
                                    ?>
                                    <th><input type="checkbox" class="edit_PM_LIST_parent_horizontal_checkbox" data-tag="child_edit_PM_LIST_<?php echo $perm['name']; ?>" data-box="box_edit_PM_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang($perm['name']); ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="edit_PM_LIST_parent_horizontal_checkbox_All" data-tag="parent_edit_PM_LIST_<?php echo $perm['name']; ?>" /><?php echo lang('all_perm'); ?></th>
                            </thead>
                            <tbody>
                                <?php
                                if (count($PM_module_list) > 0) {
                                    foreach ($PM_module_list as $modObj) {
                                    	$counter = 0;
                                        ?>
                                        <tr>

                                            <td><?php echo $modObj['module_unique_name']; ?></td>
                                            <?php
                                            if (count($getPermList) > 0) {
                                                foreach ($getPermList as $perm) {
                                                    ?>

                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>"
                                                        <?php
                                                        foreach ($view_perms_to_role_list as $assignData) {
                                                            $checked = '';
                                                            if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                                                                echo $checked = "checked=true";
                                                                $counter++;
                                                            } else {
                                                                echo $checked = "";
                                                            }
                                                        }
                                                        ?>

                                                              class="child <?php echo $modObj['module_unique_name']; ?> child_edit_PM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_edit_PM_LIST_<?php echo $perm['name']; ?>" ></td>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <td><input type="checkbox" <?php echo ($counter==4)?'checked':'';?> class="parent <?php echo $modObj['module_unique_name']; ?> parent_edit_PM_LIST_<?php echo $perm['name']; ?>"" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="edit_all_PM_LIST" ></td>
                                            <!--  <td><input type="checkbox" name=""></td> -->
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table> 
                    
                    <?php }?>
                   
                       <?php }else{?>
                   <p> <?php echo $this->lang->line('permission_error_msg'); ?> </p>
                 <?php }?>
                    </div>

                    
                    <div class="form-group" id="edit_Finance_LIST" >
                        <table class="table table-responsive" >
                            <thead>

                            <th><?php echo lang('module_list') ?></th>
                            <?php
                            if (count($getPermList) > 0) {
                                foreach ($getPermList as $perm) {
                                	
                                    ?>
                                    <th><input type="checkbox" class="edit_Finance_LIST_parent_horizontal_checkbox" data-tag="child_edit_Finance_LIST_<?php echo $perm['name']; ?>" data-box="box_edit_Finance_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang($perm['name']); ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="edit_Finance_LIST_parent_horizontal_checkbox_All" data-tag="parent_edit_Finance_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang('all_perm'); ?></th>
                            </thead>
                            <tbody>
                                <?php
                                if (count($Finance_module_list) > 0) {
                                    foreach ($Finance_module_list as $modObj) {
                                    	   	$counter = 0;
                                        ?>
                                        <tr>

                                            <td><?php echo $modObj['module_name']; ?></td>
                                            <?php
                                            if (count($getPermList) > 0) {
                                                foreach ($getPermList as $perm) {
                                             
                                                    ?>

                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>"
                                                        <?php
                                                        foreach ($view_perms_to_role_list as $assignData) {
                                                            $checked = '';
                                                            if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                                                                echo $checked = "checked=true";
                                                                $counter++;
                                                            } else {
                                                                echo $checked = "";
                                                            }
                                                        }
                                                        ?>

                                                              class="child <?php echo $modObj['module_unique_name']; ?> child_edit_Finance_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_edit_Finance_LIST_<?php echo $perm['name']; ?>" ></td>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <td><input type="checkbox" <?php echo ($counter==4)?'checked':'';?> class="parent <?php echo $modObj['module_unique_name']; ?> parent_edit_Finance_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="edit_all_Finance_LIST" ></td>
                                            <!--  <td><input type="checkbox" name=""></td> -->
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table> 
                    </div>
                   
                    <div class="form-group" id="edit_Support_LIST" >
                     <?php if(isset($hasPermission[0]['is_support']) && $hasPermission[0]['is_support'] == 1){?>
                         <?php if(!empty($getModuleCounts) && isset($getModuleCounts['support_user']) && $getModuleCounts['support_user'] >0 ) {?>
                         	 <?php if(isset($assignedModule) && ($assignedModule[0]['support_user']=="" || $assignedModule[0]['support_user']==0 ) ){?>
                         	   <p> <?php echo $this->lang->line('Support_module_limit_over'); ?> </p>
                         	 <?php }elseif (isset($getModuleCounts['support_user']) && $getModuleCounts['support_user'] >0 ){?>
                         	 <table class="table table-responsive" >
                            <thead>
                            <th><?php echo lang('module_list') ?></th>
                            <?php
                            if (count($getPermList) > 0) {
                                foreach ($getPermList as $perm) {
                                    ?>
                                    <th><input type="checkbox" class="edit_Support_LIST_parent_horizontal_checkbox" data-tag="child_edit_Support_LIST_<?php echo $perm['name']; ?>" data-box="box_edit_Support_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang($perm['name']); ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="edit_Support_LIST_parent_horizontal_checkbox_All" data-tag="parent_edit_Support_LIST_<?php echo $perm['name']; ?>" /><?php echo lang('all_perm'); ?></th>
                            </thead>
                            <tbody>
                                <?php
                                if (count($Support_module_list) > 0) {
                                    foreach ($Support_module_list as $modObj) {
                                    	$counter=0;
                                        ?>
                                        <tr>

                                            <td><?php echo $modObj['module_name']; ?></td>
                                            <?php
                                            if (count($getPermList) > 0) {
                                                foreach ($getPermList as $perm) {
                                                    ?>

                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>"
                                                        <?php
                                                        foreach ($view_perms_to_role_list as $assignData) {
                                                            $checked = '';
                                                            if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                                                                echo $checked = "checked=true";
                                                                $counter++;
                                                            } else {
                                                                echo $checked = "";
                                                            }
                                                        }
                                                        ?>

                                                              class="child <?php echo $modObj['module_unique_name']; ?> child_edit_Support_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_edit_Support_LIST_<?php echo $perm['name']; ?>" ></td>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <td><input type="checkbox" <?php echo ($counter==4)?'checked':'';?> class="parent <?php echo $modObj['module_unique_name']; ?> parent_edit_Support_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="edit_all_Support_LIST" ></td>
                                            <!--  <td><input type="checkbox" name=""></td> -->
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table> 
                         	 <?php }?>
                         <?php }else{?>
                         <table class="table table-responsive" >
                            <thead>
                            <th><?php echo lang('module_list') ?></th>
                            <?php
                            if (count($getPermList) > 0) {
                                foreach ($getPermList as $perm) {
                                    ?>
                                    <th><input type="checkbox" class="edit_Support_LIST_parent_horizontal_checkbox" data-tag="child_edit_Support_LIST_<?php echo $perm['name']; ?>" data-box="box_edit_Support_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang($perm['name']); ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="edit_Support_LIST_parent_horizontal_checkbox_All" data-tag="parent_edit_Support_LIST_<?php echo $perm['name']; ?>" /><?php echo lang('all_perm'); ?></th>
                            </thead>
                            <tbody>
                                <?php
                                if (count($Support_module_list) > 0) {
                                    foreach ($Support_module_list as $modObj) {
                                    	$counter=0;
                                        ?>
                                        <tr>

                                            <td><?php echo $modObj['module_unique_name']; ?></td>
                                            <?php
                                            if (count($getPermList) > 0) {
                                                foreach ($getPermList as $perm) {
                                                    ?>

                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>"
                                                        <?php
                                                        foreach ($view_perms_to_role_list as $assignData) {
                                                            $checked = '';
                                                            if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                                                                echo $checked = "checked=true";
                                                                $counter++;
                                                            } else {
                                                                echo $checked = "";
                                                            }
                                                        }
                                                        ?>

                                                              class="child <?php echo $modObj['module_unique_name']; ?> child_edit_Support_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_edit_Support_LIST_<?php echo $perm['name']; ?>" ></td>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <td><input type="checkbox" <?php echo ($counter==4)?'checked':'';?> class="parent <?php echo $modObj['module_unique_name']; ?> parent_edit_Support_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="edit_all_Support_LIST" ></td>
                                            <!--  <td><input type="checkbox" name=""></td> -->
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table> 
                         <?php }?>
                        <?php }else{?>
                      <p> <?php echo $this->lang->line('permission_error_msg'); ?> </p>
                    <?php }?>
                    </div>

                    
                    
                    <div class="form-group" id="edit_HR_LIST" >
                        <table class="table table-responsive" >
                            <thead>

                            <th><?php echo lang('module_list') ?></th>
                            <?php
                            if (count($getPermList) > 0) {
                                foreach ($getPermList as $perm) {
                                	
                                    ?>
                                    <th><input type="checkbox" class="edit_HR_LIST_parent_horizontal_checkbox" data-tag="child_edit_HR_LIST_<?php echo $perm['name']; ?>" data-box="box_edit_HR_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang($perm['name']); ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="edit_HR_LIST_parent_horizontal_checkbox_All" data-tag="parent_edit_HR_LIST_<?php echo $perm['name']; ?>" /><?php echo lang('all_perm'); ?></th>
                            </thead>
                            <tbody>
                                <?php
                                if (count($HR_module_list) > 0) {
                                    foreach ($HR_module_list as $modObj) {
                                    	$counter=0;
                                        ?>
                                        <tr>

                                            <td><?php echo $modObj['module_name']; ?></td>
                                            <?php
                                            if (count($getPermList) > 0) {
                                                foreach ($getPermList as $perm) {
                                                    ?>

                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>"
                                                        <?php
                                                        foreach ($view_perms_to_role_list as $assignData) {
                                                            $checked = '';
                                                            if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                                                                echo $checked = "checked=true";
                                                                $counter++;
                                                            } else {
                                                                echo $checked = "";
                                                            }
                                                        }
                                                        ?>

                                                              class="child <?php echo $modObj['module_unique_name']; ?> child_edit_HR_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_edit_HR_LIST_<?php echo $perm['name']; ?>"  ></td>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <td><input type="checkbox" <?php echo ($counter==4)?'checked':'';?> class="parent <?php echo $modObj['module_unique_name']; ?> parent_edit_HR_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="edit_all_HR_LIST" ></td>
                                            <!--  <td><input type="checkbox" name=""></td> -->
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table> 
                    </div>
                    <div class="form-group" id="edit_User_LIST" >
                        <table class="table table-responsive" >
                            <thead>

                            <th><?php echo lang('module_list') ?></th>
                            <?php
                            if (count($getPermList) > 0) {
                                foreach ($getPermList as $perm) {
                                    ?>
                                    <th><input type="checkbox" class="edit_User_LIST_parent_horizontal_checkbox" data-tag="child_edit_User_LIST_<?php echo $perm['name']; ?>" data-box="box_edit_User_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang($perm['name']); ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="edit_User_LIST_parent_horizontal_checkbox_All" data-tag="parent_edit_User_LIST_<?php echo $perm['name']; ?>"/><?php echo lang('all_perm'); ?></th>
                            </thead>
                            <tbody>
                                <?php 
                                if (count($User_module_list) > 0) {
                                    foreach ($User_module_list as $modObj) {
                                    	$counter=0;
                                        ?>
                                        <tr>

                                            <td><?php echo $modObj['module_name']; ?></td>
                                            <?php 
                                            if (count($getPermList) > 0) {
                                                foreach ($getPermList as $perm) {
                                                	
                                                    ?>

                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>"
                                                        <?php
                                                        foreach ($view_perms_to_role_list as $assignData) {
                                                            $checked = '';
                                                            if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                                                                echo $checked = "checked=true";
                                                                $counter++;
                                                            } else {
                                                                echo $checked = "";
                                                            }
                                                        }
                                                        ?>

                                                              class="child <?php echo $modObj['module_unique_name']; ?> child_edit_User_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_edit_User_LIST_<?php echo $perm['name']; ?>" ></td>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <td><input type="checkbox" <?php echo ($counter==4)?'checked':'';?> class="parent <?php echo $modObj['module_unique_name']; ?> parent_edit_User_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="edit_all_User_LIST"  ></td>
                                            <!--  <td><input type="checkbox" name=""></td> -->
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table> 
                    </div>
                    <div class="form-group" id="edit_settings_LIST" >
                        <table class="table table-responsive" >
                            <thead>

                            <th><?php echo lang('module_list') ?></th>
                            <?php
                            if (count($getPermList) > 0) {
                                foreach ($getPermList as $perm) {
                                    ?>
                                    <th><input type="checkbox" class="edit_settings_LIST_parent_horizontal_checkbox" data-tag="child_edit_settings_LIST_<?php echo $perm['name']; ?>" data-box="box_edit_settings_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang($perm['name']); ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="edit_settings_LIST_parent_horizontal_checkbox_All" data-tag="parent_edit_settings_LIST_<?php echo $perm['name']; ?>"/><?php echo lang('all_perm'); ?></th>
                            </thead>
                            <tbody>
                                <?php  
                                if (count($settings_module_list) > 0) {
                                    foreach ($settings_module_list as $modObj) { 
                                    	$counter = 0;
                                        ?>
                                        <tr>

                                            <td><?php echo $modObj['module_name']; ?></td>
                                            <?php
                                       
                                            if (count($getPermList) > 0) {
                                                foreach ($getPermList as $perm) {    	
                                                    ?>
                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>"
                                                        <?php
                                                        foreach ($view_perms_to_role_list as $assignData) {
                                                            $checked = '';
                                                            if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                                                                echo $checked = "checked=true";
                                                                 $counter++;
                                                            } else {
                                                                echo $checked = "";
                                                            }
                                                           
                                                        }
                                                        ?>

                                                              class="child <?php echo $modObj['module_unique_name']; ?> child_edit_settings_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_edit_settings_LIST_<?php echo $perm['name']; ?>" ></td>
                                                        <?php 
                                                    }
                                                }
                                              
                                                ?>
                                                <td><input type="checkbox" <?php echo ($counter==4)?'checked':'';?> class="parent <?php echo $modObj['module_unique_name']; ?> parent_edit_settings_LIST_<?php echo $perm['name']; ?>"  data-attr="<?php echo $modObj['module_unique_name']; ?>"  data-all="edit_all_settings_LIST"  ></td>
                                            <!--  <td><input type="checkbox" name=""></td> -->
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table> 
                    </div>
                    	
            </div>
            <div class="modal-footer">
                <center> 
                <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>"> 
                <input type="hidden" name="editPerm" value="Edit Permissions">
                <?php if($formAction == "insertAssginPerms"){?>
                 <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Assign Permission" />
                <?php }else{?>
                 <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('edit_perms')?>" />
                <?php }?>
              
			
					<input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" /></center>								
		            
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">

$("#edit_CRM_LIST").show();
$("#edit_PM_LIST").hide();
$("#edit_Finance_LIST").hide();
$("#edit_Support_LIST").hide();
$("#edit_HR_LIST").hide();
$("#edit_User_LIST").hide();
$("#edit_settings_LIST").hide();
$("#editCRM_LI").addClass("active");


function editpermsTab(module){

	if(module == "CRM"){
		$("#edit_CRM_LIST").show();		
		$("#edit_PM_LIST").hide();
		$("#edit_Finance_LIST").hide();
		$("#edit_Support_LIST").hide();
		$("#edit_HR_LIST").hide();
		$("#edit_User_LIST").hide();
		$("#edit_settings_LIST").hide();
		$("#editCRM_LI").addClass("active");
		$("#editPM_LI").removeClass("active");
		$("#editFinance_LI").removeClass("active");
		$("#editSupport_LI").removeClass("active");
		$("#editHR_LI").removeClass("active");
		$("#editUser_LI").removeClass("active");
		$("#editsettings_LI").removeClass("active");
	
		
	}else if(module == "PM"){	
		$("#edit_PM_LIST").show();
		$("#edit_CRM_LIST").hide();
		$("#edit_Finance_LIST").hide();
		$("#edit_Support_LIST").hide();
		$("#edit_HR_LIST").hide();
		$("#edit_User_LIST").hide();
		$("#edit_settings_LIST").hide();
		$("#editCRM_LI").removeClass("active");
		$("#editPM_LI").addClass("active");
		$("#editFinance_LI").removeClass("active");
		$("#editSupport_LI").removeClass("active");
		$("#editHR_LI").removeClass("active");
		$("#editUser_LI").removeClass("active");
		$("#editsettings_LI").removeClass("active");
		
	}else if(module == "Finance"){	
		$("#edit_PM_LIST").hide();
		$("#edit_CRM_LIST").hide();
		$("#edit_Finance_LIST").show();
		$("#edit_Support_LIST").hide();
		$("#edit_HR_LIST").hide();
		$("#edit_User_LIST").hide();
		$("#edit_settings_LIST").hide();
		$("#editCRM_LI").removeClass("active");
		$("#editPM_LI").removeClass("active");
		$("#editFinance_LI").addClass("active");
		$("#editSupport_LI").removeClass("active");
		$("#editHR_LI").removeClass("active");
		$("#editUser_LI").removeClass("active");
		$("#editsettings_LI").removeClass("active");		
	}else if(module == "Support"){
		$("#edit_PM_LIST").hide();
		$("#edit_CRM_LIST").hide();
		$("#edit_Finance_LIST").hide();
		$("#edit_Support_LIST").show();
		$("#edit_HR_LIST").hide();
		$("#edit_User_LIST").hide();
		$("#edit_settings_LIST").hide();
		$("#editCRM_LI").removeClass("active");
		$("#editPM_LI").removeClass("active");
		$("#editFinance_LI").removeClass("active");
		$("#editSupport_LI").addClass("active");
		$("#editHR_LI").removeClass("active");
		$("#editUser_LI").removeClass("active");
		$("#editsettings_LI").removeClass("active");
		
	}else if(module == "HR"){
		$("#edit_PM_LIST").hide();
		$("#edit_CRM_LIST").hide();
		$("#edit_Finance_LIST").hide();
		$("#edit_Support_LIST").hide();
		$("#edit_HR_LIST").show();
		$("#edit_User_LIST").hide();
		$("#edit_settings_LIST").hide();
		$("#editCRM_LI").removeClass("active");
		$("#editPM_LI").removeClass("active");
		$("#editFinance_LI").removeClass("active");
		$("#editSupport_LI").removeClass("active");
		$("#editHR_LI").addClass("active");
		$("#editUser_LI").removeClass("active");
		$("#editsettings_LI").removeClass("active");
	}else if(module == "User"){
		$("#edit_PM_LIST").hide();
		$("#edit_CRM_LIST").hide();
		$("#edit_Finance_LIST").hide();
		$("#edit_Support_LIST").hide();
		$("#edit_HR_LIST").hide();
		$("#edit_User_LIST").show();
		$("#edit_settings_LIST").hide();
		$("#editCRM_LI").removeClass("active");
		$("#editPM_LI").removeClass("active");
		$("#editFinance_LI").removeClass("active");
		$("#editSupport_LI").removeClass("active");
		$("#editHR_LI").removeClass("active");
		$("#editUser_LI").addClass("active");
		$("#editsettings_LI").removeClass("active");
	}else if(module == "settings"){
		$("#edit_PM_LIST").hide();
		$("#edit_CRM_LIST").hide();
		$("#edit_Finance_LIST").hide();
		$("#edit_Support_LIST").hide();
		$("#edit_HR_LIST").hide();
		$("#edit_User_LIST").hide();
		$("#edit_settings_LIST").show();
		$("#editCRM_LI").removeClass("active");
		$("#editPM_LI").removeClass("active");
		$("#editFinance_LI").removeClass("active");
		$("#editSupport_LI").removeClass("active");
		$("#editHR_LI").removeClass("active");
		$("#editUser_LI").removeClass("active");
		$("#editsettings_LI").addClass("active");
	}
		
}

</script>
<?php echo $roleMasterJs; ?>
