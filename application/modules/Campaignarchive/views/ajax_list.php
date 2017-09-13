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
            <th class="sorting_disabled text-center" role="columnheader" style="padding: 8px;" rowspan="1" colspan="1" aria-label=""> <div class="">
                    <input type="checkbox" class="selecctall" id="selecctall">
                </div>
            </th>
            <th <?php if(isset($sortfield) && $sortfield == 'campaign_auto_id'){if($sortby == 'asc'){echo "class = 'sorting_desc tabl-width20'";}else{echo "class = 'sorting_asc tabl-width20'";}}else{echo "class = 'sorting tabl-width20'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('campaign_auto_id','<?php echo $sorttypepass;?>')"><?=$this->lang->line('CAMPAIGN_ID')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'campaign_name'){if($sortby == 'asc'){echo "class = 'sorting_desc tabl-width60'";}else{echo "class = 'sorting_asc tabl-width60'";}}else{echo "class = 'sorting tabl-width60'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('campaign_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('CAMPAIGN_NAME')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'camp_type_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('camp_type_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('TYPE_OF_CAMPAIGN')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'start_date'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('start_date','<?php echo $sorttypepass;?>')"><?=$this->lang->line('START_DATE')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'end_date'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('end_date','<?php echo $sorttypepass;?>')"><?=$this->lang->line('END_DATE')?></th>
            <th tabindex="0" aria-controls="example1" rowspan="1" colspan="1" ><?=$this->lang->line('RESPONSIBLE_EMPLOYEE')?></th>

            <th><?=$this->lang->line('action')?>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
            </th>
        </tr>
        </thead>
        <tbody id="postList">
        <?php if(isset($campaign_info) && count($campaign_info) > 0 ){ ?>
            <?php foreach($campaign_info as $data){ ?>
                <tr>
                    <td class="text-center"><div class="" style="position: relative;">
                            <input type="checkbox" class="checkbox1" name="check[]" value="<?php echo  $data['campaign_id']; ?>">
                        </div></td>
                    <td class="col-md-2"><?php echo $data['campaign_auto_id'];?></td>
                    <td class="col-md-2"><?php echo $data['campaign_name'];?></td>
                    <td class="col-md-2"><?php echo $data['camp_type_name']; ?></td>
                    <td><?php echo date("m/d/Y", strtotime($data['start_date']));?></td>
                    <td><?php echo date("m/d/Y", strtotime($data['end_date']));?></td>
                    <td class="col-md-2"><?php echo showResponsibleEmployee($data['campaign_id']);?></td>
                    <td class="bd-actbn-btn">
                        <?php if(checkPermission('Marketingcampaign','view')){ ?><a data-href="<?=base_url()?>Marketingcampaign/view_page/<?=$data['campaign_id']?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?=lang('view')?>" ><i class="fa fa-search fa-x greencol"></i></a><?php } ?>
                        <?php if(checkPermission('Marketingcampaign','edit')){ ?><a data-href="<?=base_url()?>Marketingcampaign/edit/<?=$data['campaign_id']?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?=lang('edit')?>" ><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>

                <?php if(checkPermission('Marketingcampaign','delete')){ ?>
                    <?php $redirect_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'];?>
                    <!--<a onclick="delete_campaign('<?php echo $data['campaign_id'];?>','<?php echo $redirect_link;?>');"><i class="fa fa-remove fa-x redcol"></i></a>-->

                        <a title="<?=lang('CONTACT_DELETE')?>" onclick="delete_campaign('<?php echo $data['campaign_id'];?>');"><i class="fa fa-remove fa-x redcol"></i></a>
<?php }?>
                    </td>
                </tr>
            <?php }?>
        <?php }else{?>
            <tr>
                <td colspan="8" class="text-center"><?=lang('NO_RECORD_FOUND')?></td>
            </tr>
        <?php }?>
        <?php //echo $this->ajax_pagination->create_links(); ?>
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
