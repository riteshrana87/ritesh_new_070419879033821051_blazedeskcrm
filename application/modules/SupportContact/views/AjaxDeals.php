<?php
//if ($taskPage == 0)
//    $taskPage = 1;
$tasksSortDefault = '<i class="fa fa-sort"></i>';
$taskSortAsc = '<i class="fa fa-sort-desc"></i>';
$taskSortDesc = '<i class="fa fa-sort-asc"></i>';
if ($tasksortOrder == "asc") {
    $tasksortOrder = "desc";
} else {
    $tasksortOrder = "asc";
}

?>   
<div class="whitebox" id="table_deals">
    <div class="table table-responsive">
        <table class="table table-responsive" >
            <thead>
                <tr role="row">
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>SupportContact/viewDealsData/?orderField=prospect_name&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('name') ?>
                        </a>
                        
                    </th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>SupportContact/viewDealsData/?orderField=prospect_auto_id&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('id') ?>
                        </a>
                    </th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>SupportContact/viewDealsData/?orderField=contact_count&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                             <?= $this->lang->line('no_of_contacts') ?>
                        </a></th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>SupportContact/viewDealsData/?orderField=contact_name&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('contact_name') ?>
                        </a>
                        
                    </th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>SupportContact/viewDealsData/?orderField=creation_date&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('opportunity_since') ?>
                        </a>
                        
                    </th>
                    <th></th>
                    <th></th>
                    <th><?= lang('actions') ?></th>
                </tr>
            </thead>

            <tbody>
                <?php
                $redirect_link =  $_SERVER['HTTP_REFERER'];
                //pr($prospect_data);
                if (isset($prospect_data) && count($prospect_data) > 0) { ?>
                            <?php foreach ($prospect_data as $data) {
                                
                                //$redirect_link = base_url()."Contact/view/".$data['prospect_related_id'];
                                ?>
                                <tr id="opportunity_<?php echo $data['prospect_id'];?>">
                                    <td class="col-xs-4"><?php echo $data['prospect_name']; ?></td>
                                    <td><?php echo $data['prospect_auto_id']; ?></td>
                                    <td class="text-center"><?php echo $data['contact_count']; ?></td>
                                    <td><?php echo $data['contact_name']; ?></td>
                                    <td><?php echo configDateTime($data['creation_date']); ?></td>
                                    
                                    <td><?PHP if (checkPermission('Opportunity', 'edit')) { ?><a class="btn btn-sm btn-green" data-href="javascript:;" onclick="ConvertWin('<?php echo $data['prospect_id']; ?>');" ><?=$this->lang->line('win')?></a><?php } ?></td>
                                    <td><?PHP if (checkPermission('Opportunity', 'edit')) { ?><a class="btn btn-sm btn-danger" data-href="javascript:;" onclick="ConvertLost('<?php echo $data['prospect_id']; ?>');" ><?=$this->lang->line('lost')?></a><?php } ?></td>
                                    <!--<td><?PHP if (checkPermission('Opportunity', 'edit')) { ?><a class="btn btn-sm btn-green convert_client" data-href="javascript:;" onclick="convert_request('<?php echo $data['prospect_id']; ?>','<?php echo $redirect_link;?>');" ><?=$this->lang->line('close')?></a><?php } ?></td>-->
                                    <td><?PHP if (checkPermission('Opportunity', 'view')) { ?><a href="<?=base_url('Opportunity/viewdata/'.$data['prospect_id'])?>" title="<?= $this->lang->line('view') ?>" class="edit_contact" ><i class="fa fa-search fa-x greencol"></i></a><?php } ?>&nbsp;&nbsp;&nbsp;
                                        <?PHP if (checkPermission('Opportunity', 'edit')) { ?><a  data-href="<?php echo base_url('/Opportunity/editdata/'.$data['prospect_id']); ?>" title="<?= $this->lang->line('edit') ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="edit_opportunity" id="edit_opportunity" ><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>&nbsp;&nbsp;&nbsp;
                                            <?PHP if (checkPermission('Opportunity', 'delete')) { ?><a data-href="javascript:;" title="<?= $this->lang->line('delete') ?>" onclick="delete_request('<?php echo $data['prospect_id']; ?>','<?php echo $redirect_link;?>');" ><i class="fa fa-remove fa-x redcol" ></i></a><?php } ?></td>
                                </tr>
                            <?php } ?>
                        <?php }else{ ?>
                            <tr><td colspan="8" class="text-center"> <?= lang ('common_no_record_found') ?></td></tr>
                        <?php }?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12 text-center">
                <?php echo (!empty($pagination)) ? $pagination : ''; ?>
            </div>
        </div>
    </div>
    <div class="clr"></div>
</div>
<script>
function delete_request(prospect_id,redirect_link)
{
    var delete_url = "../../Opportunity/deletedata/?id=" + prospect_id +"&link=" + redirect_link;
    var delete_meg ="<?php echo $this->lang->line('CONFIRM_DELETE_DEALS');?>";
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
function ConvertWin(prospect_id)
{

    var delete_meg ="<?php echo $this->lang->line('CONFIRM_CONVERT_CONVERTWIN');?>";
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
                    var lostclinetUrl = '<?php echo base_url()."Opportunity/convertWinAccount/"?>';
                    $.ajax({
                        type: "POST",
                        url:lostclinetUrl,
                        data: {'prospect_id': prospect_id},
                        success: function (data)
                        {
                            BootstrapDialog.show({
                                message: '<?php echo lang('account_win_convert_msg');?>',
                                buttons: [{
                                    label: '<?php echo lang('close');?>',
                                    action: function(dialogItself){
                                        //var showing_html = $('#common_tb').html();
                                        //var html_arr  = showing_html.split(":");
                                        //var total_record = html_arr[1] - 1;

                                        //$('#common_tb').html('Showing : '+total_record+'');
                                        $('#opportunity_'+prospect_id).remove();
                                        var total=$('#opportunity_count').html();
                                        total=total-1;
                                        $('#opportunity_count').html(total);
                                        dialogItself.close();
                                    }
                                }]
                            });


                        }
                    });
                    dialog.close();
                }
            }]
        });


}
function ConvertLost(prospect_id)
{
    var delete_meg ="<?php echo $this->lang->line('CONFIRM_CONVERT_CONVERTLOST');?>";
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
                    var lostclinetUrl = '<?php echo base_url()."Opportunity/convertLostAccount/"?>';
                    $.ajax({
                        type: "POST",
                        url:lostclinetUrl,
                        data: {'prospect_id': prospect_id},
                        success: function (data)
                        {
                            BootstrapDialog.show({
                                message: '<?php echo lang('account_lose_convert_msg');?>',
                                buttons: [{
                                    label: '<?php echo lang('close');?>',
                                    action: function(dialogItself){

                                        if($('#common_tb').length != 0 && $('#example1_paginate').length == 0)
                                        {
                                            //var showing_html = $('#common_tb').html();
                                            //var html_arr  = showing_html.split(":");
                                            //var total_record = html_arr[1] - 1;
                                            //$('#common_tb').html('Showing : '+total_record+'');
                                        }

                                        $('#opportunity_'+prospect_id).remove();
                                        var opportunity_count=$('#opportunity_count').html();
                                        var total=opportunity_count-1;

                                        $('#opportunity_count').html(total);
                                        dialogItself.close();
                                    }
                                }]
                            });
                        }
                    });
                    dialog.close();
                }
            }]
        });
}
</script>