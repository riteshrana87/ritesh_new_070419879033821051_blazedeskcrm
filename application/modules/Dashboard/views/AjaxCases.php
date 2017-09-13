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
                    <th class="col-md-1"> <?= $this->lang->line('title') ?></th>
                    <th class="col-md-1"><?= $this->lang->line('type') ?></th>
                    <th class="col-md-2"><?= $this->lang->line('BUSINESS_CASES') ?></th>
                    <th class="col-md-2"><?= $this->lang->line('CASE_SUBJECT') ?></th>
                    <th class="col-md-2"><?= $this->lang->line('RESPONSIBLE') ?></th>
                    <th><?= $this->lang->line('DEADLINE') ?></th>
                    <th><?= $this->lang->line('INCIDENT_STATUS') ?></th>
                    <th><?= lang('actions') ?></th>
                </tr>
            </thead>

            <tbody>
                <?php
                //pr($incidents_data);
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
                                    <td><?php echo configDateTime($data['deadline']);?></td>
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
                                    <td>
                                        
                                         <?php if (checkPermission ("Contact", 'view')) { ?>
                                        <a data-href="<?=base_url('Contact/viewCasesRecord/'.$data['cases_id'])?>"  data-toggle="ajaxModal" aria-hidden="true" title="<?php echo lang('view');?>" class="edit_contact" ><i class="fa fa-search fa-x greencol"></i></a>&nbsp;&nbsp;&nbsp;
                                         <?php } ?>
                                        
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
    
    var delete_url = "<?php echo base_url('Contact/deleteCases/?cases_id=');?>" + cases_id +"&link=" + redirect_link;
    var delete_meg ="<?php echo lang('crm_cases_confirm_message');?>";
    BootstrapDialog.show(
        {
            title: '<?php echo $this->lang->line('Information');?>',
            message: delete_meg,
            buttons: [{
                label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                action: function(dialog) {
                    dialog.close();
                    return false;
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