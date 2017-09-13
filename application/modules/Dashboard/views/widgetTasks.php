<div class="sortableDiv" id="position-right-bottom">
            <div class="whitebox" id="taskTable">
                <div class="table table-responsive">
                     <table cellspacing="0" role="grid"  width="100%" class="table table-striped dataTable" >
            <thead>
                <tr role="row">
                    <th class="sortTask col-md-3" colspan="1" rowspan="1" >
                            <?php echo lang('to_do'); ?>
                    </th>
                    <th class="sortTask col-md-3" colspan="1" rowspan="1" >
                       
                            <?php echo lang('priority'); ?>
                    </th>
                    <th class="sortTask col-md-3" colspan="1" rowspan="1" >
                                        
                            <?php echo lang('deadline'); ?>
                    </th>
                    <th class="col-md-3" colspan="1" rowspan="1" ><?php if (checkPermission('Task', 'add')) { ?><a data-href="<?php echo base_url('Task/add'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true">+<?php echo lang('create'); ?></a><?php } ?> </th></tr>
            </thead>
            <tbody>
                <?php
                if (count($task_data) > 0) {
                    foreach ($task_data as $tasks) {
                        ?>
                        <tr>
                            <td class="col-md-3"><?php echo $tasks['task_name']; ?></td>
                            <td class="col-md-3"><?php
                                if ($tasks['importance'] == 'High') {

                                    echo '<div class="redline"></div>';
                                } elseif ($tasks['importance'] == 'Medium') {
                                    echo '<div class="blueline"></div>';
                                } else
                                    echo '<div class="greenline"></div>';
                                ?><?php //echo $task_data['importance']; ?></td>
                            <td class="col-md-3"><?php echo configDateTime($tasks['end_date']); ?></td>

                            <td class="col-md-3"> 
                               <?PHP if (checkPermission('Task', 'view')) { ?><a data-href="<?php echo base_url('Task/viewtask/'.$tasks['task_id'].'/SalesOverview'); ?>" title="<?= $this->lang->line('view') ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="view_lead" id="view_lead"><i class="fa fa-search fa-x greencol"></i></a><?php } ?>
                                &nbsp;<?PHP if (checkPermission('Task', 'edit')) { ?><a data-href="<?php echo base_url('Task/edittask/'.$tasks['task_id'].'/Dashboard'); ?>" title="<?= $this->lang->line('edit') ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="edit_lead" id="edit_lead"><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4" class="text-center"><?= lang ('common_no_record_found') ?></td>
                    </tr>
                <?php } ?> 
            </tbody>
        </table>

                </div>

                <div class="clr"></div>
            </div>
        </div>