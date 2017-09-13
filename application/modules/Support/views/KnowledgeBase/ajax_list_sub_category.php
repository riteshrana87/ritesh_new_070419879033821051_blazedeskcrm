<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="table table-responsive">

    <table class="table table-striped dataTable" id="example1" role="grid" aria-describedby="example1_info" width="100%">
        <thead>
        <tr>
            <th <?php if(isset($sortfield) && $sortfield == 'sub_category_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('sub_category_name','<?php echo $sorttypepass;?>')"><?=lang('sub_category')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'category_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('category_name','<?php echo $sorttypepass;?>')"><?=lang('main_category_name')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'firstname'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('firstname','<?php echo $sorttypepass;?>')"><?=lang('article_owner')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'product_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('product_name','<?php echo $sorttypepass;?>')"><?=lang('product_name')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'created_date'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('created_date','<?php echo $sorttypepass;?>')"><?=lang('create_date')?></th>
            <th tabindex="0" aria-controls="example1" rowspan="1" colspan="1"> <?= lang('actions') ?>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
            </th>
        </tr>
        </thead>
        <tbody id="postList">
        <?php if(isset($sub_category_info) && count($sub_category_info) > 0 ){ ?>
            <?php foreach($sub_category_info as $data){ ?>
                <tr>
                    <td style="width: 15%"><?php echo ucfirst($data['sub_category_name']);?></td>
                    <td style="width: 15%"><?php echo ucfirst($data['category_name']);?></td>
                    <td style="width: 15%"><?php echo ucfirst($data['firstname'].' '.$data['lastname']) ;?></td>
                    <td style="width: 15%"><?php echo ucfirst($data['product_name']);?></td>
                    <td><?php echo date("m/d/Y", strtotime($data['created_date']));?></td>
                    <td>
                        <?php if(checkPermission('KnowledgeBase','view')){ ?><a data-href="<?=base_url()?>Support/KnowledgeBase/view/<?=$data['sub_category_id']?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?=lang('view')?>" ><i class="fa fa-search fa-x greencol"></i></a><?php } ?>&nbsp;&nbsp;&nbsp;
                        <?php if(checkPermission('KnowledgeBase','edit')){ ?><a data-href="<?=base_url()?>Support/KnowledgeBase/editsubcat/<?php echo $data['sub_category_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?=lang('edit')?>" ><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
                        &nbsp;&nbsp;

                        <?php $redirect_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'];?>
                         <?php if(checkPermission("KnowledgeBase",'delete')){ ?><a href="javascript:;" title="<?= $this->lang->line('delete') ?>" onclick="deleteItem('<?php echo $data['sub_category_id']; ?>');" title="<?= lang('delete') ?>"><i class="fa fa-remove redcol"></i></a><?php } ?>
                    </td>
                </tr>
            <?php }?>
        <?php }else{?>
            <tr>
                <td colspan="8" class="text-center"><?php echo lang('NO_RECORD_FOUND'); ?></td>
            </tr>
        <?php }?>
        </tbody>

    </table>
    <div class="clearfix visible-xs-block"></div>
    <div id="common_tb" class="no_of_records">
        <?php
        if (isset($pagination)) {
            echo $pagination;
        }
        ?>
    </div>
</div>

<script>

function deleteItem(id)
    {
        var delete_meg ="<?php echo $this->lang->line('sub_category_delete_message');?>";
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
                        window.location.href = "<?php echo base_url('Support/KnowledgeBase/deletedatasub/'); ?>/" + id;
                        dialog.close();
                    }
                }]
            });

    }
    
</script>

