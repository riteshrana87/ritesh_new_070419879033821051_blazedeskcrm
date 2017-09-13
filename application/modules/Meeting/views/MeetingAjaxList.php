<?php
//$this->viewname = $this->uri->segment(1);
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="table table-responsive">
    <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>"/>
    <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>"/>
    <input type="hidden" id="uri_segment" name="uri_segment"
           value="<?php if (isset($uri_segment)) echo $uri_segment; ?>"/>


    <table id="milestonetable1" class="table table-striped dataTable" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th <?php if(isset($sortfield) && $sortfield == 'meeting_date'){if($sortby == 'asc'){echo "class = 'sorting_desc col-md-1'";}else{echo "class = 'sorting_asc col-md-1'";}}else{echo "class = 'sorting col-md-1'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('meeting_date','<?php echo $sorttypepass;?>')"><?=lang('MEETING_DATE')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'meeting_time'){if($sortby == 'asc'){echo "class = 'sorting_desc col-md-1'";}else{echo "class = 'sorting_asc col-md-1'";}}else{echo "class = 'sorting col-md-1'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('meeting_time','<?php echo $sorttypepass;?>')"><?=lang('START_TIME')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'meeting_end_time'){if($sortby == 'asc'){echo "class = 'sorting_desc col-md-1'";}else{echo "class = 'sorting_asc col-md-1'";}}else{echo "class = 'sorting col-md-1'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('meeting_end_time','<?php echo $sorttypepass;?>')"><?=lang('END_TIME')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'meet_title'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('meet_title','<?php echo $sorttypepass;?>')"><?=lang('MEETING_TITLE')?></th>
            <th></th>
            <th><?php echo lang('MEETING_REMINDER');?></th>
            <th><?php echo lang('MEETING_USER');?> </th>
            <th <?php if(isset($sortfield) && $sortfield == 'created_date'){if($sortby == 'asc'){echo "class = 'sorting_desc col-md-2'";}else{echo "class = 'sorting_asc col-md-2'";}}else{echo "class = 'sorting col-md-2'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('created_date','<?php echo $sorttypepass;?>')"><?=lang('CREATED_DATE')?></th>
            
                   
            <?php if (checkPermission ('Meeting', 'edit') || checkPermission ('Meeting', 'delete')) { ?>
                <th><?= lang ('action') ?></th>
            <?php } ?>
        </tr>
        </thead>
        
        <tbody>
        <?php
      
        if (isset($meeting_view_data) && count ($meeting_view_data) > 0) { ?>
            <?php foreach ($meeting_view_data as $meeting_view_data) { ?>
                <tr>
                    <td>
                        
                    <?= !empty($meeting_view_data['meeting_date']) ? configDateTime($meeting_view_data['meeting_date']) : '' ?>
                    </td>
                    <td>
                        
                    <?= !empty($meeting_view_data['meeting_time']) ? convertTimeTo12HourFormat($meeting_view_data['meeting_time']) : '' ?>
                    </td>
                    <td>
                        
                    <?= !empty($meeting_view_data['meeting_end_time']) ? convertTimeTo12HourFormat($meeting_view_data['meeting_end_time']) : '' ?>
                    </td>
                    <td>
                            <?= !empty($meeting_view_data['meet_title']) ? strlen($meeting_view_data['meet_title']) > 50 ? substr($meeting_view_data['meet_title'],0,50)."..." : $meeting_view_data['meet_title'] : '' 
                            ?>
                    </td>
                    <td>
                            <?php 
                            $meet_date_time =  $meeting_view_data['meeting_date']." ".$meeting_view_data['meeting_time'];
                            $event_date = date('Y-m-d',  strtotime($meet_date_time));
                            echo calculate_day_left($event_date);
                            ?>
                        </td>
                        <td>
                            <?php
                            if($meeting_view_data['meeting_reminder'] == '1' )
                            {
                                echo lang('on');
                            }else
                            {
                                echo  lang('off');
                            }
                            
                            ?>
                            
                        </td>
<!--                    <td>
                        <?php
                           // $in = $meeting_view_data['contact_name'];
                           // echo  strlen($in) > 50 ? substr($in,0,50)."..." : $in; 
                            ?>
                        <?php // !empty($meeting_view_data['contact_name']) ? $meeting_view_data['contact_name'] : '' ?>
                    </td>-->
                    <td><?= !empty($meeting_view_data['login_user_name']) ? $meeting_view_data['login_user_name'] : '' ?></td>
                    <td><?php echo configDateTime($meeting_view_data['created_date']); ?></td>
                    <?php if (checkPermission ('Meeting', 'edit') || checkPermission ('Meeting', 'delete')) { ?>
                        <td class="bd-actbn-btn">
                            <?php if (checkPermission ('Meeting', 'view')) { ?>
                            <a data-href="<?= base_url() ?>Meeting/view_meeting/<?= $meeting_view_data['meeting_master_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('VIEW_MEETING'); ?>"><i class="fa fa-search greencol"></i></a>
                            
                            <?php } ?>
                            <?php if (checkPermission ('Meeting', 'edit')) { ?>
                            <a data-href="<?= base_url () ?>Meeting/update_meeting/<?= $meeting_view_data['meeting_master_id'] ?>"
                                data-toggle="ajaxModal" title="<?= lang ('edit') ?>" class=""><i
                                        class="fa fa-pencil bluecol"></i></a><?php } ?>
                            <?php if(checkPermission('Meeting','delete')){ ?><a href="javascript:void(0);" title="<?php echo lang('delete');?>" onclick="deleteItem(<?php echo $meeting_view_data['meeting_master_id']; ?>)"><i class="fa fa-remove redcol"></i></a><?php } ?>
                        </td>
                    <?php } ?>
                </tr>

            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="9" class="text-center">
                    <?= lang ('common_no_record_found') ?>
                </td>

            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<div class="clr"></div>
<div class="row">
    <div class="col-md-12 text-center">
        <div id="common_tb" class="no_of_records">
            <?php
            if (isset($pagination)) {
                echo $pagination;
            }
            ?>
        </div>
    </div>
</div>
<script>
    function deleteItem(id) {
        var delete_meg ="<?php echo lang('CONFIRM_DELETE_MEETING');?>";
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
                        window.location.href = "<?php echo base_url('Meeting/delete_record/'); ?>/" + id;
                        dialog.close();
                    }
                }]
            });

    }
</script>