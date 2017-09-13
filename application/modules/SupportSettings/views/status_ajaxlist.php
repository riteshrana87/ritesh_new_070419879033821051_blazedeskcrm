<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->viewname = $this->uri->segment(1);

$SortDefault = '<i class="fa fa-sort"></i>';
$sortAsc = '<i class="fa fa-sort-desc"></i>';
$sortDesc = '<i class="fa fa-sort-asc"></i>';

if ($statussortOrder == "asc")
    $statussortOrder = "desc";
else
    $statussortOrder = "asc";
    
    
$array_list = array("1","2","3","4","5","6");        

?>
<div class="table table-responsive" id="tablestatusDiv">
        <table id="productTable" class="table table-striped">
            <thead>
            <tr>
                <th class='sortSettingsListStatus col-lg-5'>
                            <a  href="<?php echo base_url(); ?>SupportSettings/statusAjaxList/<?= $statusPage ?>/?orderStatusField=status_name&sortOrder=<?= $statussortOrder ?>" >
                            <?php
                                if ($statussortOrder == 'asc' && $statussortField == 'status_name') {
                                    echo $sortAsc;
                                } else if ($statussortOrder == 'desc' && $statussortField == 'status_name') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('status_name')?>
                            </a>
                        </th>
						<th class='sortSettingsListStatus col-lg-3'>
                            <a  href="<?php echo base_url(); ?>SupportSettings/statusAjaxList/<?= $statusPage ?>/?orderStatusField=status_color&sortOrder=<?= $statussortOrder ?>">
                            <?php
                                if ($statussortOrder == 'asc' && $statussortField == 'status_color') {
                                    echo $sortAsc;
                                } else if ($statussortOrder == 'desc' && $statussortField == 'status_color') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('status_color')?>
                            </a>
                        </th>
                        <th class='sortSettingsListStatus col-lg-2'>
                            <a  href="<?php echo base_url(); ?>SupportSettings/statusAjaxList/<?= $statusPage ?>/?orderStatusField=status_font_icon&sortOrder=<?= $statussortOrder ?>">
                            <?php
                                if ($statussortOrder == 'asc' && $statussortField == 'status_font_icon') {
                                    echo $sortAsc;
                                } else if ($statussortOrder == 'desc' && $statussortField == 'status_font_icon') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('status_font_icon')?>
                            </a>
                        </th>					
                        <th class="col-lg-2"><?= lang('actions')?></th>
            </tr>
            </thead>
             <tbody>
		        <?php if(isset($information_status) && count($information_status) > 0 ){ ?>
		        <?php foreach($information_status as $data){  ?>
		        <tr>
		          <td><?php echo $data['status_name'];?></td>
		          <td><span class="color_box"
                              style="background-color:<?= !empty($data['status_color']) ? $data['status_color'] : '' ?>">&nbsp;</span></td>
		          <td><?= !empty($data['status_font_icon']) ? '<i class="fa fa-' . $data["status_font_icon"] . ' blackcol"></i>' : '' ?></td>		         
		          <td class="bd-actbn-btn"><?php if(checkPermission('SupportSettings','view')){ ?><a data-href="<?php echo base_url($crnt_view.'/viewStatus/'.$data['status_id']);?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" style="cursor:pointer;" title="<?= lang('view')?>" ><i class="fa fa-search greencol" ></i></a><?php }?><?php if(checkPermission('SupportSettings','edit')){ if(!in_array($data['status_id'], $array_list)){ ?><a data-href="<?php echo base_url($crnt_view.'/editStatus/'.$data['status_id']);?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" style="cursor:pointer;" title="<?= lang('edit')?>" ><i class="fa fa-pencil bluecol" ></i></a><?php }}?><?php if(checkPermission("SupportSettings","delete")){ if(!in_array($data['status_id'], $array_list)){?><a  href="javascript:;" onclick="delete_request2(<?php echo $data['status_id']; ?>);" title="<?= lang('delete')?>"><i class="fa fa-remove redcol"></i></a><?php }} ?></td>
		        </tr>
		        <?php } ?>
		      <?php }else { ?>
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
					            	echo '<div class="no_of_records">'.lang('showing').' :'.count($information_status).'</div>';
					            }
                        
                        //echo (!empty($paginationStatus)) ? $paginationStatus : ''; ?>
            </div>
        </div>
    </div>
	<script> 
	function delete_request2(status_id){
	    var delete_meg ="<?php echo $this->lang->line('support_status_setting_delete_confrim_msg');?>";
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
                        window.location.href = "SupportSettings/deletedataStatus/" + status_id;
                        dialog.close();
                    }
                }]
            });

}
</script>	
