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
$master_user_id = $this->config->item('master_user_id'); 
?>
<div class="table table-responsive" id="postList">
        <table id="productTable" class="table table-striped">
            <thead>
            <tr>
                <th class='sortUserList' >
                            <a id="name" href="<?php echo base_url(); ?>User/index/<?= $page ?>/?orderField=name&sortOrder=<?= $sortOrder ?>" >
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'name') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'name') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('name')?>
                            </a>
                        </th>
						<th class='sortUserList'>
                            <a  href="<?php echo base_url(); ?>User/index/<?= $page ?>/?orderField=email&sortOrder=<?= $sortOrder ?>">
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'email') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'email') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('emails')?>
                            </a>
                        </th>
						<th class='sortUserList'>
                            <a  href="<?php echo base_url(); ?>User/index/<?= $page ?>/?orderField=telephone1&sortOrder=<?= $sortOrder ?>">
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'telephone1') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'telephone1') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('telephone1')?>
                            </a>
                        </th>
                        <th class='sortUserList'>
                            <a  href="<?php echo base_url(); ?>User/index/<?= $page ?>/?orderField=user_type&sortOrder=<?= $sortOrder ?>">
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'user_type') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'user_type') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('usertype')?>
                            </a>
                        </th>
                        <th class='sortUserList'>
                            <a  href="<?php echo base_url(); ?>User/index/<?= $page ?>/?orderField=status&sortOrder=<?= $sortOrder ?>">
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
                        <th><?= lang('actions')?></th>
            </tr>
            </thead>
             <tbody>
		        <?php if(isset($information) && count($information) > 0 ){ ?>
		        <?php foreach($information as $data){
		          if($data['status'] == 1){ 
		            $data['status'] = lang('active'); 
		          }else{ 
		            $data['status'] = lang('inactive'); 
		          }?>
		        <tr>
		          <td><?php echo $data['name'];?></td>
		          <td><?php echo $data['email'];?></td>
		          <td><?php echo $data['telephone1'];?></td>
		          <td><?php echo $data['user_type'];?></td>
		           <td><?php echo $data['status'];?></td>
		           <?php if($this->session->userdata['LOGGED_IN']['ROLE_TYPE'] != $role_id) {?>
		          <td class="bd-actbn-btn"><?php if(checkPermission('User','view')){ ?><a data-href="<?php echo base_url($crnt_view.'/view/'.$data['login_id']);?>" title="<?= lang('view')?>" data-toggle="ajaxModal"><i class="fa fa-search fa-x greencol" ></i></a><?php }?><?php if(checkPermission('User','edit')){ ?><a data-href="<?php echo base_url($crnt_view.'/edit/'.$data['login_id']);?>" title="<?= lang('edit')?>" data-toggle="ajaxModal"><i class="fa fa-pencil bluecol" ></i></a><?php }?><?php if(checkPermission("User","delete")){ if($this->session->userdata['LOGGED_IN']['ID'] != $data['login_id'] && $data['role_type'] != $role_id && $master_user_id != $data['login_id'] ){?><a data-href="javascript:;" title="<?= lang('delete')?>" onclick="delete_request(<?php echo $data['login_id']; ?>);" ><i class="fa fa-remove redcol"></i></a><?php } } ?></td>
		        <?php }else{?>
		         <td class="bd-actbn-btn"><?php if(checkPermission('User','view')){ ?><a data-href="<?php echo base_url($crnt_view.'/view/'.$data['login_id']);?>" title="<?= lang('view')?>" data-toggle="ajaxModal"><i class="fa fa-search fa-x greencol" ></i></a><?php }?><?php if(checkPermission('User','edit')){ ?><a data-href="<?php echo base_url($crnt_view.'/edit/'.$data['login_id']);?>" title="<?= lang('edit')?>" data-toggle="ajaxModal"><i class="fa fa-pencil bluecol" ></i></a><?php }?><?php if(checkPermission("User","delete")){ if($this->session->userdata['LOGGED_IN']['ID'] != $data['login_id'] && $master_user_id != $data['login_id']){?><a data-href="javascript:;" title="<?= lang('delete')?>" onclick="delete_request(<?php echo $data['login_id']; ?>);" ><i class="fa fa-remove redcol"></i></a><?php } } ?></td>
		        <?php }?>
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
					            }// echo (!empty($pagination)) ? $pagination : ''; ?>
            </div>
        </div>
    </div>
<script> 
	function delete_request(loginId){
	    var delete_meg ="<?php echo $this->lang->line('user_delete_confrim_msg');?>";
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
                        window.location.href = "<?php echo base_url();?>User/deletedata/" + loginId;
                        dialog.close();
                    }
                }]
            });

}

	

</script>	
