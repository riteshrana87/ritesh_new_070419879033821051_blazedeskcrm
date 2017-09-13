<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<nav role="navigation" class="navbar-collapse collapse" style="">
	<ul class="nav">
	  <?php if (checkPermission ('Projectdashboard', 'view')) { ?><li><a href="<?php echo base_url('Projectmanagement/Projectdashboard');?>" <?php if($cur_viewname == 'Projectdashboard'){?>class="active"<?php }?>><?=lang('project_management_dashboard')?></a><?php } ?>
	  <?php if (checkPermission ('Projectmanagement', 'view')) { ?><li><a href="<?php echo base_url('Projectmanagement');?>" <?php if($cur_viewname == 'Projectmanagement'){?>class="active"<?php }?>><?=lang('manage_projects')?></a><?php } ?>
	  <?php if (checkPermission ('Milestone', 'view')) { ?><li><a href="<?php echo base_url('Projectmanagement/Milestone');?>" <?php if($cur_viewname == 'Milestone'){?>class="active"<?php }?>><?=lang('milestone')?></a><?php } ?>
	  <?php if (checkPermission ('ProjectTask', 'view')) { ?><li><a href="<?php echo base_url('Projectmanagement/ProjectTask');?>" <?php if($cur_viewname == 'ProjectTask'){?>class="active"<?php }?>><?=lang('projecttask')?></a><?php } ?>
	  <?php if (checkPermission ('Timesheets', 'view')) { ?><li><a href="<?php echo base_url('Projectmanagement/Timesheets');?>" <?php if($cur_viewname == 'Timesheets'){?>class="active"<?php }?>><?=lang('timesheets')?></a><?php } ?>
	  <?php if (checkPermission ('ProjectIncidents', 'view')) { ?><li><a href="<?php echo base_url('Projectmanagement/ProjectIncidents');?>" <?php if($cur_viewname == 'ProjectIncidents'){?>class="active"<?php }?>><?=lang('project_incidents')?></a><?php } ?>
	  <?php if (checkPermission ('Activities', 'view')) { ?><li><a href="<?php echo base_url('Projectmanagement/Activities');?>" <?php if($cur_viewname == 'Activities'){?>class="active"<?php }?>><?=lang('activities')?></a><?php } ?>
	  <?php if (checkPermission ('Costs', 'view')) { ?><li><a href="<?php echo base_url('Projectmanagement/Costs');?>" <?php if($cur_viewname == 'Costs'){?>class="active"<?php }?>><?=lang('costs')?></a><?php } ?>
	  <?php if (checkPermission ('Filemanager', 'view')) { ?><li><a href="<?php echo base_url('Projectmanagement/Filemanager');?>" <?php if($cur_viewname == 'Filemanager'){?>class="active"<?php }?>><?=lang('files')?></a><?php } ?>
	  <?php if (checkPermission ('TeamMembers', 'view')) { ?><li><a href="<?php echo base_url('Projectmanagement/TeamMembers');?>" <?php if($cur_viewname == 'TeamMembers'){?>class="active"<?php }?>><?=lang('teammembers')?></a><?php } ?>
	  <?php if (checkPermission ('Invoices', 'view')) { ?><li><a href="<?php echo base_url('Projectmanagement/Invoices');?>" <?php if($cur_viewname == 'Invoices'){?>class="active"<?php }?>><?=lang('invoices')?></a><?php } ?>
	  <?php if (checkPermission ('ProjectStatus', 'view') || checkPermission ('ProjectIncidentsType', 'view')) { ?>
	      <li class="dropdown"><a href="#" class="dropdown-toggle <?php if($cur_viewname == "ProjectStatus" || $cur_viewname == "ProjectIncidentsType"){ echo "active";} ?>" data-toggle="dropdown"><?=lang('settings')?> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <?php if (checkPermission ('ProjectStatus', 'view')) { ?><li><a href="<?php echo base_url('ProjectStatus'); ?>" <?php if($cur_viewname == "ProjectStatus"){?>class="active"<?php }?> ><?php echo lang('TOP_MENU_MANAGE_PROJECT_STATUS'); ?></a></li><?php } ?>
                    <?php if (checkPermission ('ProjectIncidentsType', 'view')) { ?><li><a href="<?php echo base_url('Projectmanagement/ProjectIncidentsType');?>" <?php if($cur_viewname == 'ProjectIncidentsType'){?>class="active"<?php }?>><?=lang('projectincidentstype')?></a></li><?php } ?>
                </ul>
            </li>
        <?php } ?>
        </ul>
</nav>