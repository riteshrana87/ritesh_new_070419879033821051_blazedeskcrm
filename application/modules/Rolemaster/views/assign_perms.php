<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = 'insertAssginPerms';
$path = $crnt_view . '/' . $formAction;

?>
<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" title="<?php echo lang('close') ?>">&times;</button>
            <h4 class="modal-title"><div class="title" ><?PHP if($formAction == "insertAssginPerms"){ ?><?=$this->lang->line('assigned_perms_list')?><?php }else{ ?><?=$this->lang->line('edit_perms')?><?php }?></div></h4>
        </div>
        <ul class="nav nav-pills">
			  <li role="presentation" id="CRM_LI" ><a  name="CRM" id="CRM" onclick="permsTab(this.id);"><?= $this->lang->line('cms') ?></a></li>
			  <li role="presentation" id="PM_LI" ><a name="PM" id="PM" onclick="permsTab(this.id);"><?= $this->lang->line('pm') ?></a></li>
			<!--  <li role="presentation" id="Finance_LI" ><a name="Finance" id="Finance" onclick="permsTab(this.id);"><?= $this->lang->line('finance') ?></a></li> --> 
			  <li role="presentation" id="Support_LI" ><a name="Support" id="Support" onclick="permsTab(this.id);"><?= $this->lang->line('support') ?></a></li>
			<!--  <li role="presentation" id="HR_LI" ><a name="HR" id="HR" onclick="permsTab(this.id);"><?= $this->lang->line('hr') ?></a></li> --> 
			  <li role="presentation" id="User_LI" ><a name="User" id="User" onclick="permsTab(this.id);"><?= $this->lang->line('user') ?></a></li>
			  <li role="presentation" id="settings_LI" ><a name="settings" id="settings" onclick="permsTab(this.id);"><?= $this->lang->line('settings') ?></a></li>			  		
		</ul>
        <form id="assignPermission" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate name="permissionform">
            <div class="modal-body">				
                <div class="form-group">
                        <label for="usertype"><?= $this->lang->line('user_name') ?>:</label>
                        <?php
                        
                         /*$options1 = array();
                        $options2 = array();
                        $selected = "";
                        foreach ($userType as $key => $value) {
                            if ($roleId == $value['role_id']) {
                                echo $value['role_name'];
                                //echo "<input type='hidden' name='usertype' value='".$value['role_id']."'>";
                            }                  
                        }*/
                        //$options = array('1'=>"Super Admin",'2'=>"Admin");
                        
                        
                        $options1 = array();
                        $options2 = array();
                        $selected = "";
                        foreach ($userType as $key => $value) {
                            array_push($options1, $value['role_id']);
                            array_push($options2, $value['role_name']);
                        }
                        $options = array_combine($options1, $options2);
                        $name = "usertype";
                        if ($formAction == "insertdata") {
                            $selected = 1;
                        } else {
                            $selected = $roleId; 
                        }
                        echo dropdown($name, $options, $selected);
                        
                        
                        ?>
                        <span class="text-danger"><?php echo form_error('usertype'); ?></span>
                    </div>   
                                            
				<div class="form-group" id="CRM_LIST">
				<?php if(isset($hasPermission[0]['is_crm']) && $hasPermission[0]['is_crm'] == 1){?>
                        <table class="table table-responsive" >
                            <thead>

                            <th><?php echo lang('module_list') ?></th>
                            <?php
                            if (count($getPermList) > 0) {
                                foreach ($getPermList as $perm) {
                                    ?>
                                    <th><input type="checkbox" class="CRM_LIST_parent_horizontal_checkbox" data-tag="child_CRM_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang($perm['name']); ?>
                                   
                                    </th>
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="CRM_LIST_parent_horizontal_checkbox_All" data-tag="parent_CRM_LIST_<?php echo $perm['name']; ?>"/><?php echo lang('all_perm'); ?></th>
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
                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>" class="child <?php echo $modObj['module_unique_name']; ?> child_CRM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_CRM_LIST_<?php echo $perm['name']; ?>"></td>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <td><input type="checkbox" class="parent <?php echo $modObj['module_unique_name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="all_CRM_LIST"></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table> 
                      <?php }else{?>
                      <p> <?php echo $this->lang->line('permission_error_msg'); ?> </p>
                    <?php }?>
                    </div>
                    <div class="form-group" id="PM_LIST">
                    	<?php if(isset($hasPermission[0]['is_pm']) && $hasPermission[0]['is_pm'] == 1){?>
                        <table class="table table-responsive" >
                            <thead>

                            <th><?php echo lang('module_list') ?></th>
                            <?php
                            if (count($getPermList) > 0) {
                                foreach ($getPermList as $perm) {
                                    ?>
                                    <th><input type="checkbox" class="PM_LIST_parent_horizontal_checkbox" data-tag="child_PM_LIST_<?php echo $perm['name']; ?>" /><?php echo lang($perm['name']); ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="PM_LIST_parent_horizontal_checkbox_All" data-tag="parent_PM_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang('all_perm'); ?></th>
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
                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>" class="child <?php echo $modObj['module_unique_name']; ?> child_PM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_PM_LIST_<?php echo $perm['name']; ?>"></td>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <td><input type="checkbox" class="parent <?php echo $modObj['module_unique_name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="all_PM_LIST"></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table> 
                  <?php }else{?>
                      <p> <?php echo $this->lang->line('permission_error_msg'); ?> </p>
                    <?php }?>
                 
                    </div>
                    <div class="form-group" id="Finance_LIST">
                        <table class="table table-responsive" >
                            <thead>

                            <th><?php echo lang('module_list') ?></th>
                            <?php
                            if (count($getPermList) > 0) {
                                foreach ($getPermList as $perm) {
                                    ?>
                                    <th><input type="checkbox" class="Finance_LIST_parent_horizontal_checkbox" data-tag="child_Finance_LIST_<?php echo $perm['name']; ?>" /><?php echo lang($perm['name']); ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="Finance_LIST_parent_horizontal_checkbox_All" data-tag="parent_Finance_LIST_<?php echo $perm['name']; ?>" /><?php echo lang('all_perm'); ?></th>
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
                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>" class="child <?php echo $modObj['module_unique_name']; ?> child_Finance_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_Finance_LIST_<?php echo $perm['name']; ?>" ></td>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <td><input type="checkbox" class="parent <?php echo $modObj['module_unique_name']; ?> " data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="all_Finance_LIST"></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table> 
                    </div>
                    <div class="form-group" id="Support_LIST">
                    <?php if(isset($hasPermission[0]['is_support']) && $hasPermission[0]['is_support'] == 1){?>
                        <table class="table table-responsive" >
                            <thead>

                            <th><?php echo lang('module_list') ?></th>
                            <?php
                            if (count($getPermList) > 0) {
                                foreach ($getPermList as $perm) {
                                    ?>
                                    <th><input type="checkbox" class="Support_LIST_parent_horizontal_checkbox" data-tag="child_Support_LIST_<?php echo $perm['name']; ?>" /><?php echo lang($perm['name']); ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="Support_LIST_parent_horizontal_checkbox_All" data-tag="parent_Support_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang('all_perm'); ?></th>
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
                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>" class="child <?php echo $modObj['module_unique_name']; ?> child_Support_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>"  data-parent="child_Support_LIST_<?php echo $perm['name']; ?>"></td>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <td><input type="checkbox" class="parent <?php echo $modObj['module_unique_name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="all_Support_LIST"></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table> 
                             <?php }else{?>
                      <p> <?php echo $this->lang->line('permission_error_msg'); ?> </p>
                    <?php }?>
                    </div>
                    <div class="form-group" id="HR_LIST">
                        <table class="table table-responsive" >
                            <thead>

                            <th><?php echo lang('module_list') ?></th>
                            <?php
                            if (count($getPermList) > 0) {
                                foreach ($getPermList as $perm) {
                                    ?>
                                    <th><input type="checkbox" class="HR_LIST_parent_horizontal_checkbox" data-tag="child_HR_LIST_<?php echo $perm['name']; ?>" /><?php echo lang($perm['name']); ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="HR_LIST_parent_horizontal_checkbox_All" data-tag="parent_HR_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang('all_perm'); ?></th>
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
                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>" class="child <?php echo $modObj['module_unique_name']; ?> child_HR_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_HR_LIST_<?php echo $perm['name']; ?>"></td>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <td><input type="checkbox" class="parent <?php echo $modObj['module_unique_name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>"  data-all="all_HR_LIST" ></td>
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
                                    <th><input type="checkbox" class="User_LIST_parent_horizontal_checkbox" data-tag="child_User_LIST_<?php echo $perm['name']; ?>" /><?php echo lang($perm['name']); ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="User_LIST_parent_horizontal_checkbox_All" data-tag="parent_User_LIST_<?php echo $perm['name']; ?>"/><?php echo lang('all_perm'); ?></th>
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
                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>" class="child <?php echo $modObj['module_unique_name']; ?> child_User_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_User_LIST_<?php echo $perm['name']; ?>"></td>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <td><input type="checkbox" class="parent <?php echo $modObj['module_unique_name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="all_User_LIST"  ></td>
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
                                    <th><input type="checkbox" class="settings_LIST_parent_horizontal_checkbox" data-tag="child_settings_LIST_<?php echo $perm['name']; ?>" /><?php echo lang($perm['name']); ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th><input type="checkbox"  class="settings_LIST_parent_horizontal_checkbox_All" data-tag="parent_settings_LIST_<?php echo $perm['name']; ?>"/><?php echo lang('all_perm'); ?></th>
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
                                                    <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] . '_' . $modObj['component_name'] ; ?>" class="child <?php echo $modObj['module_unique_name']; ?> child_settings_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_settings_LIST_<?php echo $perm['name']; ?>" ></td>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <td><input type="checkbox" class="parent <?php echo $modObj['module_unique_name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>"  data-all="all_settings_LIST" ></td>
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
               <!-- <input name="role_id" type="hidden" value="<?=!empty($editRecord[0]['role_id'])?$editRecord[0]['role_id']:''?>" /> --> 
               <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Assign Permission" />
				
					<input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" /></center>								
		            
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">

</script>
<?php echo $roleMasterJs; ?>