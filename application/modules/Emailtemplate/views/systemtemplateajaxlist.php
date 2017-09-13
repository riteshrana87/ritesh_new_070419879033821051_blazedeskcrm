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
    
//$array_list = array("2","3","29","36","37","38","39","40");    

?>
<div class="table table-responsive" id="postList">
        <table id="emailTemplateTable" class="table table-striped">
            <thead>
            <tr>
                <th class='sortEmailTemplateList'>
							<a  href="<?php echo base_url(); ?>Emailtemplate/systemTemplateList/<?= $page ?>/?orderField=subject&sortOrder=<?= $sortOrder ?>" > 
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'name') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'name') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('emailTemplate_sub')?>
                         <!--   </a> -->
                        </th>
					<!-- 	<th><?= lang('emailTemplate_body')?></th>  -->
                        <th class='sortEmailTemplateList'>
                            <a  href="<?php echo base_url(); ?>Emailtemplate/systemTemplateList/<?= $page ?>/?orderField=status&sortOrder=<?= $sortOrder ?>">
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
		          <td><?php if(!empty($data['subject'])){
                                   if(strlen($data['subject']) > 50) {
                                       echo substr($data['subject'],0, 40).'...';
                                   }else {
                                       echo $data['subject'];
                                   }
                                } ?></td>         
		                      
		           <td><?php echo $data['status'];?></td>
		          <td class="bd-actbn-btn"><?php if(checkPermission('Emailtemplate','view')){ ?><a data-href="<?php echo base_url($crnt_view.'/viewEmailTemplate/'.$data['template_id']);?>" data-toggle="ajaxModal" title="<?= lang('view')?>"><i class="fa fa-search fa-x greencol" ></i></a><?php }?><?php if(checkPermission('Emailtemplate','edit')){ ?><a data-href="<?php echo base_url($crnt_view.'/edit/'.$data['template_id']);?>" data-toggle="ajaxModal" title="<?= lang('edit')?>" ><i class="fa fa-pencil bluecol" ></i></a><?php }?><?php if(checkPermission("Emailtemplate","delete")){ if($data['system_template'] == 0) {?><a data-href="javascript:;" onclick="delete_request(<?php echo $data['template_id']; ?>);" title="<?= lang('delete')?>"><i class="fa fa-remove redcol" ></i></a><?php } } ?></td>
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
                        <?php 
                          if (isset($pagination) && !empty($pagination)) {
					                echo $pagination;
					            }else{
					            	echo '<div class="no_of_records">'.lang('showing').' :'.count($information).'</div>';
					            }
                        
                        //echo (!empty($pagination)) ? $pagination : ''; ?>
            </div>
        </div>
    </div>
<script> 
	function delete_request(template_id){
	    var delete_meg ="<?php echo $this->lang->line('emailtemplate_delete_confrim_msg');?>";
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
                        window.location.href = "Emailtemplate/deletedata/" + template_id;
                        dialog.close();
                    }
                }]
            });

}
</script>	