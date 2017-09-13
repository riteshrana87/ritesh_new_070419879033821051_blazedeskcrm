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
            <th <?php if(isset($sortfield) && $sortfield == 'campaign_id'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('campaign_id','<?php echo $sorttypepass;?>')"><?=$this->lang->line('id')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'campaign_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('campaign_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('CAMPAIGN_NAME')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'camp_type_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('camp_type_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('TYPE_OF_CAMPAIGN')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'start_date'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('start_date','<?php echo $sorttypepass;?>')"><?=$this->lang->line('START_DATE')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'end_date'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('end_date','<?php echo $sorttypepass;?>')"><?=$this->lang->line('END_DATE')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'contact_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('contact_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('RESPONSIBLE_EMPLOYEE')?></th>

            <th><a class="greencol"><i class="fa fa-plus"></i> Create estimate</a>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
            </th>
        </tr>
        </thead>
        <tbody id="postList">
        <?php if(isset($campaign_info) && count($campaign_info) > 0 ){ ?>
            <?php foreach($campaign_info as $data){ ?>
                <tr>
                    <td><?php echo $data['campaign_id'];?></td>
                    <td><?php echo $data['campaign_name'];?></td>
                    <td><?php echo $data['camp_type_name']; ?></td>
                    <td><?php echo $data['start_date'];?></td>
                    <td><?php echo $data['end_date'];?></td>
                    <td><?php echo $data['contact_name'];?></td>
                    <td>

                        <a href="#"><i class="fa fa-search fa-x greencol"></i></a>&nbsp;&nbsp;&nbsp;
                        <?php if(checkPermission('SalesCampaign','edit')){ ?><a href="<?=base_url()?>SalesCampaign/edit/<?=$data['campaign_id']?>" data-toggle="ajaxModal" title="<?=lang('edit')?>" class="btn btn-sm btn-dark"><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
&nbsp;&nbsp;
                     <!--   <a href="<?php echo base_url($sales_view.'/deletedata?id='.$data['campaign_id']);?>" onclick="return confirm('Are you sure you want to delete this campaign?','yes','no');"><i class="fa fa-remove fa-x redcol"></i></a>-->

&nbsp;&nbsp;
                       <!-- <a href="<?php echo base_url($sales_view.'/deletedata?id='.$data['campaign_id']);?>" onclick="return confirm('Are you sure you want to delete this campaign?','yes','no');"><i class="fa fa-remove fa-x redcol"></i></a>-->
                    <a onclick="delete_campaign('<?php echo $data['campaign_id'];?>');"><i class="fa fa-remove fa-x redcol"></i></a>

                    </td>
                </tr>
            <?php }?>
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
