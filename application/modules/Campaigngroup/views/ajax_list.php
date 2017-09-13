<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$this->viewname = $this->uri->segment(1);
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
            <th <?php if(isset($sortfield) && $sortfield == 'group_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('group_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('GROUP_NAME')?></th>
            <th><?=$this->lang->line('action')?>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
            </th>
    </tr>
        </thead>
        <tbody>
        <?php if(isset($campaign_group_info) && count($campaign_group_info) > 0 ){

            ?>
            <?php foreach($campaign_group_info as $data){ ?>
                <tr>
                    <td class="col-md-9"><?php echo $data['group_name'];?></td>
                    <td class="col-md-3 bd-actbn-btn">
                        <?php if(checkPermission('Campaigngroup','view')){ ?><a data-href="<?=base_url()?>Campaigngroup/view_page/<?=$data['campaign_group_id']?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?=lang('view')?>"><i class="fa fa-search fa-x greencol"></i></a><?php } ?>

                <?php if(checkPermission('Campaigngroup','edit')){ ?><a data-href="<?=base_url()?>Campaigngroup/edit/<?=$data['campaign_group_id']?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?=lang('edit')?>" ><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>

                        <a title="<?=lang('CONTACT_DELETE')?>" <?php if(checkPermission('Campaigngroup','delete')){ ?> onclick="delete_campaign_group('<?php echo $data['campaign_group_id'];?>');"><i class="fa fa-remove fa-x redcol"></i></a><?php } ?>
                    </td>
                </tr>
            <?php

            }?>
        <?php }else{?>
            <tr>
                <td colspan="8" class="text-center"><?=lang('NO_RECORD_FOUND')?></td>
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
