<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$TaskAction = 'inserttask';
$taskPath = $task_view . '/' . $TaskAction;
?>

<div class="row">
    <div class="col-sm-9">
        <div class="col-xs-12 col-md-12">
            
		 <div class="clr"></div>
        <?php if($this->session->flashdata('msg')){ ?>
        <div class='alert alert-success text-center'> <?php  echo $this->session->flashdata('msg'); ?></div>
        <?php } ?>
         <?php if($this->session->flashdata('error')){ ?>
             <div class='alert alert-danger text-center'> <?php echo $this->session->flashdata('error'); ?></div>
            <?php } ?>
            <div class="row">
                <div class="col-md-6"><h2><?= $this->lang->line('to_do') ?></h2>
                </div>
                <div class="col-md-6">
                    <?PHP if (checkPermission('SupportTask', 'add')) { ?><a data-href="<?php echo base_url('SupportTask/add'); ?>" class="btn btn-default pull-right" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true">+<?php echo lang('create'); ?></a><?php } ?>
                </div>

            </div>
            <div class="whitebox">

                <div class="table table-responsive">

                    <table id="datatable1" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th><?= $this->lang->line('name') ?></th>
                                <th><?= $this->lang->line('importance') ?></th>
                                <th><?= $this->lang->line('finish') ?></th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (isset($task_data) && count($task_data) > 0) { ?>
                                <?php foreach ($task_data as $task_data) { ?>
                                    <tr>
                                        <td><?php echo $task_data['task_name']; ?></td>
                                        <td><?php
                                            if ($task_data['importance'] == 'High') {
                                                echo '<div class="fa fa-circle alert-danger"></div>';
                                            } elseif ($task_data['importance'] == 'Medium') {
                                                echo '<div class="fa fa-circle alert-warning"></div>';
                                            } else
                                                echo '<div class="fa fa-circle alert-success"></div>';
                                            ?><?php //echo $task_data['importance']; ?></td>
                                        <td><?php echo $task_data['end_date']; ?></td>
                                        <td> <?PHP if (checkPermission('SupportTask', 'edit')) { ?><a data-href="<?php echo base_url('SupportTask/edittask/'.$task_data['task_id'].''); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="edit_lead" id="edit_lead"><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                                 <?PHP if (checkPermission('SupportTask', 'view')) { ?><a data-href="<?php echo base_url('SupportTask/viewtask/'.$task_data['task_id'].''); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="view_lead" id="view_lead"><i class="fa fa-search fa-x greencol"></i></a><?php } ?>    
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>

        </div>
    </div>
    <div class="col-sm-4">

    </div>
</div>  

<div class="clr"></div>
