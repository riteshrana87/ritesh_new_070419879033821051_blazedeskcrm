<div  class="sortableDiv" id="btn">
        <?php if (checkPermission('Ticket', 'add')) { ?>
           
                <a class="btn btn-white ticketadd" data-href="<?php echo base_url('Ticket/add'); ?>" data-toggle="ajaxModal"><?php echo $this->lang->line('create_new_ticket') ?></a> 
          
        <?php } ?>

        <?php if (checkPermission('SupportTeam', 'add')) { ?>
           <a class="btn btn-white " data-href="<?php echo base_url('SupportTeam/addTeam'); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true"><?php echo lang('add_team'); ?>                       </a><?php } ?>


        <?php if (checkPermission('SupportTeam', 'add')) { ?>
           <a class="btn btn-white " data-href="<?php echo base_url('SupportTeam/addTeamMembers'); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true"><?php echo lang('add_team_member') ?>   </a>
        <?php } ?>
 <div class="clr"></div>
    </div>
