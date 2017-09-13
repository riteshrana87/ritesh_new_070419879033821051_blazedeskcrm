<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->viewname = $this->uri->segment(1);

$SortDefault = '<i class="fa fa-sort"></i>';
$sortAsc = '<i class="fa fa-sort-desc"></i>';
$sortDesc = '<i class="fa fa-sort-asc"></i>';
if ($sortOrder == "asc")
    $sortOrder = "desc";
else
    $sortOrder = "asc";
    
    
$role_id =  $this->config->item('super_admin_role_id');            

?>
<div class="table table-responsive" id="postList">
        <table id="roleTable" class="table table-striped">
            <thead>
            <tr>
                <th class='sortRoleList'>
                            <a  href="<?php echo base_url(); ?>Rolemaster/index/<?= $page ?>/?orderField=role_name&sortOrder=<?= $sortOrder ?>" >
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'role_name') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'role_name') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('role_name')?>
                            </a>
                        </th>
						<th class='sortRoleList'>
                            <a  href="<?php echo base_url(); ?>Rolemaster/index/<?= $page ?>/?orderField=status&sortOrder=<?= $sortOrder ?>">
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'status') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'status') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('status')?>
                            </a>
                        </th>				
                        <th><?= $this->lang->line('edit_delete_perms') ?></th> 		
                        <th><?= $this->lang->line('edit_delete_role') ?></th>
                                             
            </tr>
            </thead>
             <tbody>
            <?php if (isset($information) && count($information) > 0) { ?>
            <?php
                      foreach ($information as $data) {
                        if ($data['status'] == 1) {
                            $data['status'] = lang('active');
                        } else {
                            $data['status'] = lang('inactive');
                        }
                    ?>
            <tr>
              <td><?php echo $data['role_name']; ?></td>
              <td><?php echo $data['status']; ?></td>         
              <td class="bd-actbn-btn">
              
              <?php if(checkPermission('Rolemaster','view')){ ?><a style="cursor:pointer;" data-toggle="ajaxModal"  data-href="<?php echo base_url($crnt_view . '/view_perms_to_role_list/' . $data['role_id']); ?>" title="<?= lang('view')?>" >
        			<i class="fa fa-search fa-x greencol" ></i>
                </a> <?php }?> <?php if(checkPermission('Rolemaster','edit')){ if($data['role_id']!=$role_id){ ?> <a style="cursor:pointer;" data-href="<?php echo base_url($crnt_view . '/editPermission/' . $data['role_id']); ?>"  data-toggle="ajaxModal" title="<?= lang('edit')?>" ><i class="fa fa-pencil bluecol"></i></a> <?php } }?><!-- <a  href="javascript:;" onclick="deleteAssignedPermission(<?php echo $data['role_id']; ?>);"><i class="fa fa-remove redcol"></i></a> --></td>    
              <td class="bd-actbn-btn">
              <?php if(checkPermission('Rolemaster','edit')){ ?>
              <a style="cursor:pointer;"
                    data-href="<?php echo base_url($crnt_view . '/edit/' . $data['role_id']); ?>" data-toggle="ajaxModal" title="<?= lang('edit')?>" ><i
                    class="fa fa-pencil bluecol" ></i></a> <?php }?> <?php if($data['role_id']!=$role_id){?> <?php if(checkPermission('Rolemaster','delete')){ ?><a
                    href="javascript:;" onclick="deleteRole_t(<?php echo $data['role_id']; ?>);" title="<?= lang('delete')?>" ><i class="fa fa-remove redcol"></i></a><?php }?> <?php }?></td>
              
            </tr>
            <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="6" class="text-center"><?= lang('common_no_record_found') ?></td>
                </tr>
            <?php } ?>
          </tbody>
        </table>
        <div class="clr"></div>
		<div class="row">
                    <div class="col-md-12 text-center">
                        <?php if (isset($pagination) && !empty($pagination)) {
					                echo $pagination;
					            }else{
					            	echo '<div class="no_of_records">'.lang('showing').' :'.count($information).'</div>';
					            } //echo (!empty($pagination)) ? $pagination : ''; ?>
            </div>
        </div>
    </div>
<script>
function deleteRole_t(role_id){
    var delete_meg ="<?php echo $this->lang->line('role_delete_confrim_msg');?>";
    BootstrapDialog.show(
        {
            title: '<?php echo $this->lang->line('Information');?>',
            message: delete_meg,
            buttons: [{
                label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '<?php echo $this->lang->line('ok');?>',
                action: function(dialog) {
                    window.location.href = "<?php echo base_url(); ?>Rolemaster/deletedata/" + role_id;
                    dialog.close();
                }
            }]
        });
    }

</script>