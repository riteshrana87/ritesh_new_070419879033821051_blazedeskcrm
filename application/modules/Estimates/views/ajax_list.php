<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<input type="hidden" value="<?php echo $totalEstimate; ?>" name="hdnTotalCount" id="hdnTotalCount" />
<div class="table table-responsive">
            <table class="table table-striped dataTable" id="example1" role="grid" aria-describedby="example1_info" width="100%">
        <thead>
        <tr>
            <th <?php if(isset($sortfield) && $sortfield == 'client_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('client_name','<?php echo $sorttypepass;?>')"><?php echo lang('EST_LISTING_LABEL_NAME');?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'estimate_auto_id'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('estimate_auto_id','<?php echo $sorttypepass;?>')"><?PHP echo lang('EST_LISTING_LABEL_ESTIMATE_ID');?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'subject'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('subject','<?php echo $sorttypepass;?>')"><?PHP echo lang('EST_LISTING_LABEL_SUBJECT');?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'value'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('value','<?php echo $sorttypepass;?>')"><?php echo lang('EST_LISTING_LABEL_VALUE');?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'send_date'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('send_date','<?php echo $sorttypepass;?>')"><?php echo lang('EST_LIST_LABEL_CREATION_DATE'); ?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'due_date'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('due_date','<?php echo $sorttypepass;?>')"><?php echo lang('EST_LIST_LABEL_DUE_DATE');?></th>
            <th><?= lang('status') ?></th>
            <th><?= lang('actions') ?>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
            </th>
        </tr>
        </thead>
        <tbody>
		<?php if(isset($estimate_data) && count($estimate_data) != 0){?>
        <?php if (isset($estimate_data) && count($estimate_data) > 0) { ?>
            <?php foreach ($estimate_data as $data) { ?>
                <tr>
                    <td class="col-md-2"><?php echo $data['client_name']; ?></td>
                    <td class="col-md-1"><?php echo $data['estimate_auto_id']; ?></td>
                    <td class="col-md-3"><?php echo $data['subject']; ?></td>
                    <td class="col-md-1"><?php echo getCurrencySymbol($data['country_id_symbol']).$data['value']; ?></td>
                    <td>
						<?php echo configDateTime($data['created_date']); ?>
					</td>
                    <td><?php echo configDateTime($data['due_date']); ?>
					</td>
                    <td><!--Awaiting Approval-->
                        <?php if($data['status'] == 0){	echo lang('inactive'); }
                        if($data['status'] == 1){	echo lang('active'); }
                        if($data['status'] == 2){	echo lang('draft'); }
                        ?>
                    </td>
                    <td class="bd-actbn-btn">
                        <!--<a href="#"><i class="fa fa-search fa-x greencol"></i></a>&nbsp;&nbsp;&nbsp;-->
                        <?php if(checkPermission('Estimates','view')){?>
							<a href="<?php echo base_url().$estimate_view.'/view/'.$data['estimate_id'];?>" title="<?php echo lang('view');?>"><i class="fa fa-search fa-x greencol"></i></a>
						<?php }?>
						<?php if(checkPermission('Estimates','edit')){?>
							<a href="<?php echo base_url().$estimate_view.'/edit/'.$data['estimate_id'];?>" title="<?php echo lang('edit');?>"><i class="fa fa-pencil fa-x bluecol"></i></a>
						<?php }?>
						<?php if(checkPermission('Estimates','delete')){?>
							<a href="javascript:;" onclick="deleteRecord(<?php echo $data['estimate_id']; ?>);" title="<?php echo lang('delete');?>"><i class="fa fa-remove fa-x redcol"></i></a>
						<?php }?>
					</td>
							
                </tr>
            <?php }//Close For each Condition
        }//Close If Condition?>
        <?php } else { ?>
			<tr>
                    <td colspan="8" class="text-center"><?php echo lang('NO_RECORD_FOUND');?></td>
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