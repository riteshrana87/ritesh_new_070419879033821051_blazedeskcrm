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
    
    
//$array_list = array("152");        
//echo "DEFLT : ".$default_currency;
?>
<div class="table table-responsive" id="postList">
        <table id="productTable" class="table table-striped">
            <thead>
            <tr>
                <th class='sortCurrencySettingsList'>
                            <a  href="<?php echo base_url(); ?>Currencysettings/index/<?= $page ?>/?orderField=country_name&sortOrder=<?= $sortOrder ?>" >
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'country_name') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'country_name') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('country_name')?>
                            </a>
                        </th>
						<th class='sortCurrencySettingsList'>
                            <a  href="<?php echo base_url(); ?>Currencysettings/index/<?= $page ?>/?orderField=currency_name&sortOrder=<?= $sortOrder ?>">
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'currency_name') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'currency_name') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('currency_name')?>
                            </a>
                        </th>
						<th class='sortCurrencySettingsList'>
                            <a  href="<?php echo base_url(); ?>Currencysettings/index/<?= $page ?>/?orderField=currency_code&sortOrder=<?= $sortOrder ?>">
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'currency_code') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'currency_code') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('currency_code')?>
                            </a>
                        </th>
                        <th><?= lang('currency_symbol') ?></th>
                        <th class='sortCurrencySettingsList'>
                            <a  href="<?php echo base_url(); ?>Currencysettings/index/<?= $page ?>/?orderField=use_status&sortOrder=<?= $sortOrder ?>">
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'use_status') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'use_status') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('use_status')?>
                            </a>
                        </th>
						<th class='sortCurrencySettingsList'>
                            <a  href="<?php echo base_url(); ?>Currencysettings/index/<?= $page ?>/?orderField=country_status&sortOrder=<?= $sortOrder ?>">
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'country_status') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'country_status') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('country_status')?>
                            </a>
                        </th>
                        <th><?= lang('actions')?></th>
            </tr>
            </thead>
             <tbody>
		        <?php if(isset($information) && count($information) > 0 ){ ?>
		        <?php foreach($information as $data){ 
				
		          if($data['use_status'] == 1){ 
		            $data['use_status'] = lang('yes'); 
		          }else{ 
		            $data['use_status'] = lang('no'); 
		          }
				  if($data['country_status'] == 1){ 
		            $data['country_status'] = lang('active'); 
		          }else{ 
		            $data['country_status'] = lang('inactive'); 
		          }			  
			
				  ?>
		        <tr>
		          <td class="col-md-2"><?php echo $data['country_name'];?></td>
		          <td class="col-md-2"><?php echo $data['currency_name'];?></td>
		          <td class="col-md-2"><?php echo $data['currency_code'];?></td>
		          <td class="col-md-2"><?php echo $data['currency_symbol'];?></td>
		           <td><?php echo $data['use_status'];?></td>
				    <td><?php echo $data['country_status'];?></td>
		          <td class="bd-actbn-btn"><?php if(checkPermission('Currencysettings','view')){ ?><a data-href="<?php echo base_url($crnt_view.'/view/'.$data['country_id']);?>" data-toggle="ajaxModal" aria-hidden="true" title="<?=$this->lang->line('view')?>" data-refresh="true"><i class="fa fa-search greencol" ></i></a><?php }?><?php if(checkPermission('Currencysettings','edit')){ ?><a data-href="<?php echo base_url($crnt_view.'/edit/'.$data['country_id']);?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" title="<?=$this->lang->line('edit')?>" ><i class="fa fa-pencil bluecol" ></i></a><?php }?><?php if(checkPermission("Currencysettings","delete")){ if($data['country_id']!=$default_currency) {?><a  href="javascript:;" onclick="delete_request_currency(<?php echo $data['country_id']; ?>);" title="<?=$this->lang->line('delete') ?>"><i class="fa fa-remove redcol"></i></a><?php } } ?></td>
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
					            	echo '<div class="no_of_records">'.  lang('showing').' :'.count($information).'</div>';
					            }
                        //echo (!empty($pagination)) ? $pagination : ''; ?>
            </div>
        </div>
    </div>
	<script> 
	function delete_request_currency(country_id){
	    var delete_meg ="<?php echo lang('currency_delete_confrim_msg');?>";
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
                        window.location.href = "Currencysettings/deletedata/" + country_id;
                        dialog.close();
                    }
                }]
            });

}
</script>	
