<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->viewname = $this->uri->segment(1);

if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}

?>

    <div class="table table-responsive whitebox">
    <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
    <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />

        <table id="productTable" class="table table-striped dataTable" role="grid" >
            <thead>
            <tr>
                <th <?php if(isset($sortfield) && $sortfield == 'product_name'){
                                if($sortby == 'asc'){echo "class = 'sorting_desc'";}
                                else{echo "class = 'sorting_asc'";}}
                           else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="product" rowspan="1" colspan="1" onclick="apply_sorting('product_name','<?php echo $sorttypepass;?>')">
                    <?= lang('product_name')?>
                </th>
		<th <?php if(isset($sortfield) && $sortfield == 'product_type'){
                                if($sortby == 'asc'){echo "class = 'sorting_desc'";}
                                else{echo "class = 'sorting_asc'";}}
                           else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="product" rowspan="1" colspan="1" onclick="apply_sorting('product_type','<?php echo $sorttypepass;?>')">
                    <?= lang('product_type')?> 
                </th>
		<th <?php if(isset($sortfield) && $sortfield == 'product_group_name'){
                                if($sortby == 'asc'){echo "class = 'sorting_desc'";}
                                else{echo "class = 'sorting_asc'";}}
                           else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="product" rowspan="1" colspan="1" onclick="apply_sorting('product_group_name','<?php echo $sorttypepass;?>')">
                            
                    <?= lang('product_group')?>      
                </th>
                <th <?php if(isset($sortfield) && $sortfield == 'product_description'){
                                if($sortby == 'asc'){echo "class = 'sorting_desc'";}
                                else{echo "class = 'sorting_asc'";}}
                           else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="product" rowspan="1" colspan="1" onclick="apply_sorting('product_description','<?php echo $sorttypepass;?>')">
                    <?= lang('product_desc')?>
                </th>
                <th <?php if(isset($sortfield) && $sortfield == 'purchase_price_unit'){
                                if($sortby == 'asc'){echo "class = 'sorting_desc'";}
                                else{echo "class = 'sorting_asc'";}}
                           else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="product" rowspan="1" colspan="1" onclick="apply_sorting('purchase_price_unit','<?php echo $sorttypepass;?>')">
                    <?= lang('product_ppu')?>
                </th> 
		<th <?php if(isset($sortfield) && $sortfield == 'sales_price_unit'){
                                if($sortby == 'asc'){echo "class = 'sorting_desc'";}
                                else{echo "class = 'sorting_asc'";}}
                           else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="product" rowspan="1" colspan="1" onclick="apply_sorting('sales_price_unit','<?php echo $sorttypepass;?>')">
                    <?= lang('product_spu')?>
                </th>  						
                <th <?php if(isset($sortfield) && $sortfield == 'gross_margin'){
                                if($sortby == 'asc'){echo "class = 'sorting_desc'";}
                                else{echo "class = 'sorting_asc'";}}
                           else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="product" rowspan="1" colspan="1" onclick="apply_sorting('gross_margin','<?php echo $sorttypepass;?>')">
                    <?= lang('product_gm')?>
                </th>
                <th>
                    <?= lang('currency_symbol')?>
                </th>        
                <th><?= lang('actions')?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(isset($product_data) && count($product_data) > 0 ){ ?>
                <?php foreach($product_data as $data){ ?>
                    <tr>
                        <td class="col-md-2"><?= !empty($data['product_name']) ? $data['product_name'] : '' ?></td>
                        <td class="col-md-1"><?= !empty($data['product_type']) ? $data['product_type'] : '' ?></td>
                        <td class="col-md-2"><?php if(!empty($data['group_name'])){
                                $count = 0;
                                foreach($data['group_name'] as $data_grp_name){
                                    $count++;
                                    echo $data_grp_name['product_group_name'];
                                    if($count != count($data['group_name'])){ echo ", "; }
                                }
                            }else{
                            echo "";
                        }?></td>
                        <td class="col-md-2"><?php 
                                if(!empty($data['product_description'])){
                                   if(strlen($data['product_description']) > 40) {
                                       echo substr(strip_tags($data['product_description']),0, 30).'...';
                                   }else {
                                       echo $data['product_description'];
                                   }
                                } else {
                                    echo "";
                                }
                                ?>
			</td>
                        <td class="col-md-1"><?= !empty($data['purchase_price_unit']) ? $data['purchase_price_unit'] : '' ?></td>
                        <td class="col-md-1"><?= !empty($data['sales_price_unit']) ? $data['sales_price_unit'] : '' ?></td>
                        <td class="col-md-1"><?= !empty($data['gross_margin']) ? $data['gross_margin'] : '' ?></td>
                        <td class="col-md-1"><?= !empty($data['currency_symbol']) ? $data['currency_symbol'] : '' ?></td>
                        <td class="bd-actbn-btn">
                            <?php if (checkPermission('Product', 'view')) { ?><a data-href="<?php echo base_url($crnt_view.'/display/'.$data['product_id']);?>"data-toggle="ajaxModal" title="<?= lang('view')?>"><i class="fa fa-search fa-x greencol"></i></a><?php } ?>
                            <?php if (checkPermission('Product', 'edit')) { ?><a data-href="<?php echo base_url($crnt_view.'/edit/'.$data['product_id']);?>" data-toggle="ajaxModal" title="<?= lang('edit')?>"><i class="fa fa-pencil bluecol" ></i></a><?php } ?>

                            <?php if (checkPermission('Product', 'delete')) { ?><a data-href="javascript:;" onclick="delete_request('<?php echo $data['product_id']; ?>')" title="<?= lang('delete')?>"><i class="fa fa-remove fa-x redcol"></i> </a><?php } ?>
                        </td>
                    </tr>
                <?php }?>
            <?php }else{?>
                    <tr>
                        <td colspan="8" class="text-center"><?= lang('common_no_record_found') ?></td>
                    </tr>
            <?php
                }
            ?>
            <?php //echo $this->ajax_pagination->create_links(); ?>
            </tbody>

        </table>
        <div class="clr"></div>
	<div id="common_tb" class="no_of_records">
        <?php
            if (isset($pagination) && !empty($pagination)) {
		echo $pagination;
            }else{
		echo '<div class="no_of_records">'.lang('showing').' :'.count($product_data).'</div>';
            }
//            if (isset($pagination)) {
//                echo $pagination;
//            }
        ?>
        </div>
    </div>

<script>
    function delete_request(product_id){
	var delete_url = "Product/deletedata/?id=" + product_id;
        var delete_meg ="<?php echo lang('delete_product') ?>";
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
                        window.location.href = delete_url;
                        dialog.close();
                    }
                }]
            });

    }
</script>
