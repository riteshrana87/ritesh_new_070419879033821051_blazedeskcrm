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
<div class="whitebox" id="table_cases">
    <div class="table table-responsive">
        <table class="table table-responsive" >
            <thead>
                <tr role="row">
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>SupportContact/getContactCases/?orderField=title&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'title') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'title') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('title') ?>
                        </a>
                        
                    </th>
                    
                    
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>SupportContact/getContactCases/?orderField=incident_type_name&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'incident_type_name') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'incident_type_name') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('type') ?>
                        </a>
                        
                    </th>
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>SupportContact/getContactCases/?orderField=business_cases&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'business_cases') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'business_cases') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('BUSINESS_CASES') ?>
                        </a>
                        
                    </th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>SupportContact/getContactCases/?orderField=business_subject&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'business_subject') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'business_subject') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('CASE_SUBJECT') ?>
                        </a>
                    </th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>SupportContact/getContactCases/?orderField=responsible&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'responsible') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'responsible') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                             <?= $this->lang->line('RESPONSIBLE') ?>
                        </a></th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>SupportContact/getContactCases/?orderField=deadline&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'deadline') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'deadline') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('DEADLINE') ?>
                        </a>
                        
                    </th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>SupportContact/getContactCases/?orderField=incident_status&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'incident_status') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'incident_status') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('INCIDENT_STATUS') ?>
                        </a>
                        
                    </th>
                    <th><?= lang('actions') ?></th>
                </tr>
            </thead>

            <tbody>
                <?php
                $redirect_link=$_SERVER['HTTP_REFERER'];
                if (isset($cases_data) && count($cases_data) > 0) { ?>
                            <?php foreach ($cases_data as $data) {
                                ?>
                                <tr id="cases_<?php echo $data['cases_id'];?>">
                                    <td><?= !empty($data['title']) ? $data['title'] : '' ?></td>
                                     <td><?= !empty($data['incident_type_name']) ? $data['incident_type_name'] : '' ?></td>
                                    <td><?php
                                     $business_cases = $data['business_cases'];
                                    echo  strlen($business_cases) > 15 ? substr($business_cases,0,15)."..." : $business_cases; 
                                    
                                    ?></td>
                                    <td><?php 
                                    
                                        $business_subject =  $data['business_subject'];
                                         echo  strlen($business_subject) > 15 ? substr($business_subject,0,15)."..." : $business_subject; 
                                        ?>
                                    </td>
                                    <td><?php echo $data['responsible_name']; ?></td>
                                    <td><?php echo date('Y-m-d',strtotime($data['deadline'])); ?></td>
                                    <td>
                                        <?php
                                            if($data['incident_status'] == '1')
                                            {
                                                echo "In Process";
                                            }else if($data['incident_status'] == '2')
                                            {
                                                  echo "On Hold";
                                            }

                                            ?>
                                        
                                    </td>
                                    <td class="bd-actbn-btn">
                                        
                                         <?php if (checkPermission ("SupportContact", 'view')) { ?>
                                        <a data-href="<?=base_url('SupportContact/viewCasesRecord/'.$data['cases_id'])?>" title="<?= $this->lang->line('view') ?>"  data-toggle="ajaxModal" aria-hidden="true" class="edit_contact" ><i class="fa fa-search fa-x greencol"></i></a>
                                         <?php } ?>
                                        <?PHP if (checkPermission('SupportContact', 'edit')) { ?><a  data-href="<?php echo base_url('SupportContact/editCases/'.$data['cases_id']); ?>" title="<?= $this->lang->line('edit') ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="edit_opportunity" id="edit_opportunity" ><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
                                            <?PHP if (checkPermission('SupportContact', 'delete')) { ?><a data-href="javascript:;" title="<?= $this->lang->line('delete') ?>" onclick="delete_request_cases('<?php echo $data['cases_id']; ?>','<?php echo $redirect_link;?>');" ><i class="fa fa-remove fa-x redcol" ></i></a><?php } ?></td>
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
function delete_request_cases(cases_id,redirect_link)
{
    var delete_url = "<?php echo base_url('SupportContact/deleteCases/?cases_id=');?>" + cases_id +"&link=" + redirect_link;
    var delete_meg ="<?php echo $this->lang->line('crm_cases_confirm_message');?>";
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