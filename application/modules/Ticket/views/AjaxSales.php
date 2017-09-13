<?php
//if ($salePage == 0)
//    $salePage = 1;
$tasksSortDefault = '<i class="fa fa-sort"></i>';
$taskSortAsc = '<i class="fa fa-sort-desc"></i>';
$taskSortDesc = '<i class="fa fa-sort-asc"></i>';
if ($salessortOrder == "asc")
    $salessortOrder = "desc";
else
    $salessortOrder = "asc";
?>     

<div class="whitebox" id="salesTable">
    <div class="table table-responsive">
        <table  class="table table-striped">
            <thead>
                <tr>
                    <th class="salesSort">
                        <a  href="<?php echo base_url(); ?>SalesOverview/salesAjaxList/<?php echo $salePage ?>/?orderField=prospect_name&sortOrder=<?php echo $salessortOrder ?>">

                            <?php
                            if ($salessortOrder == 'asc' && $salessortField == 'prospect_name') {
                                echo $taskSortAsc;
                            } else if ($salessortOrder == 'desc' && $salessortField == 'prospect_name') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?php echo lang('name') ?>
                        </a>
                    </th>
                    <th class="salesSort">
                        <a  href="<?php echo base_url(); ?>SalesOverview/salesAjaxList/<?php echo $salePage ?>/?orderField=prospect_auto_id&sortOrder=<?php echo $salessortOrder ?>">
                            <?php
                            if ($salessortOrder == 'asc' && $salessortField == 'prospect_auto_id') {
                                echo $taskSortAsc;
                            } else if ($salessortOrder == 'desc' && $salessortField == 'prospect_auto_id') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?php echo lang('id') ?>
                        </a>
                    </th>
                    <th class="salesSort">
                        <a  href="<?php echo base_url(); ?>SalesOverview/salesAjaxList/<?php echo $salePage ?>/?orderField=contact_count&sortOrder=<?php echo $salessortOrder ?>">
                            <?php
                            if ($salessortOrder == 'asc' && $salessortField == 'contact_count') {
                                echo $taskSortAsc;
                            } else if ($salessortOrder == 'desc' && $salessortField == 'contact_count') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?php echo lang('no_of_contacts') ?>
                        </a>
                    </th>
                    <th class="salesSort">
                        <a  href="<?php echo base_url(); ?>SalesOverview/salesAjaxList/<?php echo $salePage ?>/?orderField=contact_name&sortOrder=<?php echo $salessortOrder ?>">
                            <?php
                            if ($salessortOrder == 'asc' && $salessortField == 'contact_name') {
                                echo $taskSortAsc;
                            } else if ($salessortOrder == 'desc' && $salessortField == 'contact_name') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>                                   
                            <?php echo lang('primary_contact') ?>
                        </a>
                    </th>
                    <th class="salesSort">
                        <a  href="<?php echo base_url(); ?>SalesOverview/salesAjaxList/<?php echo $salePage ?>/?orderField=pm.created_date&sortOrder=<?php echo $salessortOrder ?>">
                            <?php
                            if ($salessortOrder == 'asc' && $salessortField == 'pm.created_date') {
                                echo $taskSortAsc;
                            } else if ($salessortOrder == 'desc' && $salessortField == 'pm.created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?> 
                            <?php echo lang('client_since') ?>
                        </a>
                    </th>
                    <th class="salesSort">
                        <a  href="<?php echo base_url(); ?>SalesOverview/salesAjaxList/<?php echo $salePage ?>/?orderField=pm.creation_date&sortOrder=<?php echo $salessortOrder ?>">
                            <?php
                            if ($salessortOrder == 'asc' && $salessortField == 'pm.creation_date') {
                                echo $taskSortAsc;
                            } else if ($salessortOrder == 'desc' && $salessortField == 'pm.creation_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>                                    
                            <?php echo lang('contract_expiration') ?>
                            <a/>
                    </th>
                    <th class="salesSort">
                        <a  href="<?php echo base_url(); ?>SalesOverview/salesAjaxList/<?php echo $salePage ?>/?orderField=status_type&sortOrder=<?php echo $salessortOrder ?>">
                            <?php
                            if ($salessortOrder == 'asc' && $salessortField == 'status_type') {
                                echo $taskSortAsc;
                            } else if ($salessortOrder == 'desc' && $salessortField == 'status_type') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>                                     
                            <?php echo lang('status') ?>
                        </a>

                    </th>
                    <th><?php echo lang('actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($prospect_data) && count($prospect_data) > 0) { ?>
                    <?php foreach ($prospect_data as $data) { ?>
                        <tr>
                            <td><?php echo $data['prospect_name']; ?></td>
                            <td><?php echo $data['prospect_auto_id']; ?></td>
                            <td class="text-center"><?php echo $data['contact_count']; ?></td>
                            <td><?php echo $data['contact_name']; ?></td>
                            <td><?php echo $data['creation_date']; ?></td>
                            <td>Not a client yet</td>
                            <td><?php echo $status[$data['status_type']]; ?></td>
                            <?php if($data['status_type']==1){ ?>
                           <td><a href="<?php echo base_url('Opportunity/viewdata/'.$data['prospect_id']); ?>" data-toggle="ajaxModal"><i class="fa fa-search fa-x greencol"></i></a>&nbsp;&nbsp;&nbsp;<?PHP if (checkPermission('Opportunity', 'edit')) { ?><a href="<?php echo base_url('Opportunity/editdata/'.$data['prospect_id']); ?>" data-toggle="ajaxModal"><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>&nbsp;&nbsp;&nbsp;<?PHP if (checkPermission('Opportunity', 'edit')) { ?> <a href="javascript:;" onclick="delete_opportunity('<?php echo $data['prospect_id']; ?>','<?php echo $redirect_link;?>');" ><i class="fa fa-remove fa-x redcol"></i></a><?php } ?></td>
                            <?php } ?>
                           <?php if($data['status_type']==2){ ?>
                           <td><?PHP if (checkPermission('Lead', 'edit')) { ?><a href="<?php echo base_url($lead_view . '/convert_opportunity?id=' . $data['lead_id']); ?>">Convert To Opportunity</a><?php } ?>&nbsp;&nbsp;&nbsp;<a  href="<?php echo base_url('Lead/viewdata/'.$data['lead_id']); ?>" data-toggle="ajaxModal"><i class="fa fa-search fa-x greencol"></i></a>&nbsp;&nbsp;&nbsp;<?PHP if (checkPermission('Lead', 'edit')) { ?><a href="<?php echo base_url('Lead/editdata/'.$data['lead_id']); ?>" data-toggle="ajaxModal"><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>&nbsp;&nbsp;&nbsp;<?PHP if (checkPermission('Lead', 'edit')) { ?> <a href="javascript:;" onclick="delete_lead('<?php echo $data['prospect_id']; ?>','<?php echo $redirect_link;?>');" ><i class="fa fa-remove fa-x redcol"></i></a><?php } ?></td>
                            <?php } ?>
                           <?php if($data['status_type']==3){ ?>
                           <?php $redirect_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'];?>
                           <td><a href="<?php echo base_url('Client/viewdata/'.$data['prospect_id']); ?>" data-toggle="ajaxModal"><i class="fa fa-search fa-x greencol"></i></a>&nbsp;&nbsp;&nbsp;<?PHP if (checkPermission('Client', 'edit')) { ?><a href="<?php echo base_url('Client/editdata/'.$data['prospect_id']); ?>" data-toggle="ajaxModal"><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>&nbsp;&nbsp;&nbsp;<?PHP if (checkPermission('Client', 'edit')) { ?> <a href="javascript:;" onclick="delete_client('<?php echo $data['prospect_id']; ?>','<?php echo $redirect_link;?>');" > <i class="fa fa-remove fa-x redcol"></i></a><?php } ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7">No Records Found!</td>
                    </tr>
                <?php } ?> 
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12 text-center">
                <?php echo (!empty($paginationSales)) ? $paginationSales : ''; ?>
            </div>
        </div>
    </div>

    <div class="clr"></div>
</div>
<script>
    function delete_lead(lead_id,redirect_link){
	var delete_url = "Lead/deletedata/?id=" + lead_id +"&link=" + redirect_link;
    var delete_meg ="Are You Sure Want to Delete This Lead ?";
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
function delete_opportunity(prospect_id,redirect_link){
	var delete_url = "Opportunity/deletedata/?id=" + prospect_id +"&link=" + redirect_link;
    var delete_meg ="Are You Sure Want to Delete This Opportunity ?";
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
function delete_client(prospect_id,redirect_link){
	var delete_url = "Client/deletedata/?id=" + prospect_id +"&link=" + redirect_link;
    var delete_meg ="Are You Sure Want to Delete This Account ?";
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