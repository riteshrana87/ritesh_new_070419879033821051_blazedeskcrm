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
        <table id="productTable" class="table table-striped dataTable" role="grid">
            <thead>
            <tr>
                <th <?php if(isset($sortfield) && $sortfield == 'product_group_name'){
                                if($sortby == 'asc'){echo "class = 'sorting_desc'";}
                                else{echo "class = 'sorting_asc'";}}
                           else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="productgroup" rowspan="1" colspan="1" onclick="apply_sorting('product_group_name','<?php echo $sorttypepass;?>')">
                    <?= lang('product_group_name')?>
                </th>
		<th><?= lang('actions')?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(isset($product_group_info) && count($product_group_info) > 0 ){  ?>
                <?php foreach($product_group_info as $data){ ?>
                    <tr>
                        <td class="col-md-9"><?= !empty($data['product_group_name']) ? $data['product_group_name'] : '' ?></td>
                        <td  class="col-md-3 bd-actbn-btn">
                            <?php if (checkPermission('ProductGroup', 'view')) { ?><a data-href="<?php echo base_url($crnt_view.'/display/'.$data['product_group_id']);?>"data-toggle="ajaxModal" title="<?= lang('view')?>"><i class="fa fa-search fa-x greencol"></i></a><?php } ?>
                            <?php if (checkPermission('ProductGroup', 'edit')) { ?><a data-href="<?php echo base_url($crnt_view.'/edit/'.$data['product_group_id']);?>" data-toggle="ajaxModal" title="<?= lang('edit')?>"><i class="fa fa-pencil bluecol" ></i></a><?php } ?>
                            <?php if (checkPermission('ProductGroup', 'delete')) { ?><a data-href="javascript:;" onclick="delete_request('<?php echo $data['product_group_id']; ?>');"  title="<?= lang('delete')?>"><i class="fa fa-remove fa-x redcol"></i> </a><?php } ?>
                        </td>
                    </tr>
                <?php }?>
            <?php }else{?>
                    <tr>
                        <td colspan="8" class="text-center"><?php echo lang('common_no_record_found'); ?></td>
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
		echo '<div class="no_of_records">'.lang('showing').' :'.count($product_group_info).'</div>';
            }
//            if (isset($pagination)) {
//                echo $pagination;
//            }
        ?>
        </div>
    </div>

<script>
    function delete_request(product_group_id){
	    var delete_url = "ProductGroup/deletedata/?id=" + product_group_id;
        var delete_meg ="<?php echo lang('delete_product_group'); ?>";
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
