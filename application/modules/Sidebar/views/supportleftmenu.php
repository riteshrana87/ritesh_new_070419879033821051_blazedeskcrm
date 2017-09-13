<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<nav role="navigation" class="navbar-collapse collapse" style="">
	<ul class="nav">
	  <li>
		  <?php if(checkPermission('Support','view')){ ?>
		  <a href="<?php echo base_url('Support');?>" <?php if($cur_viewname == "Support"){?>class="active"<?php }?>><?=lang('support_dashboard')?></a>
		  <?php }?>
	  </li>
	  <li>
		  <?php if(checkPermission('Ticket','view')){ ?>
		  <a href="<?php echo base_url('Ticket');?>" <?php if($cur_viewname == "Ticket"){?>class="active"<?php }?>><?=lang('ticket')?></a>
		  <?php }?>
	  </li>
	  <li>
		  <?php if(checkPermission('KnowledgeBase','view')){ ?>
		  <a href="<?php echo base_url('Support/KnowledgeBase');?>" <?php if($cur_viewname == "KnowledgeBase"){?>class="active"<?php }?>><?=lang('know_base')?></a>
		  <?php }?>
	  </li>
	  <!--<li><a href="<?php echo base_url('Support/LiveChat');?>" <?php if($cur_viewname == "LiveChat"){?>class="active"<?php }?>><?=lang('live_chat')?></a></li>-->
	  <li>
		  <?php if(checkPermission('Company','view')){ ?>
		  <a href="<?php echo base_url('Company');?>" <?php if($cur_viewname == "Company"){?>class="active"<?php }?>><?=lang('companies')?></a>
		  <?php }?>
	  </li>
	  <li>
		  <?php if(checkPermission('SupportContact','view')){ ?>
		  <a href="<?php echo base_url().'SupportContact';?>" <?php if($cur_viewname == "SupportContact"){?>class="active"<?php }?>><?=lang('contacts')?></a>
		  <?php }?>
	  </li>
	  <li>
		  <?php if(checkPermission('SupportReport','view')){ ?>
		  <a href="<?php echo base_url('Support/SupportReport');?>" <?php if($cur_viewname == "SupportReport"){?>class="active"<?php }?>><?=lang('support_report')?></a>
		  <?php }?>
	  </li>
	<!-- <li><a href="<?php echo base_url('SupportSettings');?>" <?php if($cur_viewname == "Settings"){?>class="active"<?php }?>><?=lang('settings_support')?></a></li>
	  <li><a href="<?php echo base_url('KnowledgeBaseSettings');?>" <?php if($cur_viewname == "KnowledgeBaseSettings"){?>class="active"<?php }?>><?=lang('settings_knowledge')?></a></li> -->  
	  <li>
		  <?php if(checkPermission('SupportTeam','view')){ ?>
		  <a href="<?php echo base_url('SupportTeam');?>" <?php if($cur_viewname == "SupportTeam"){?>class="active"<?php }?>><?=lang('sup_team')?></a>
		  <?php }?>
	  </li>
           <li class="<?php if($cur_viewname == "SupportSettings"){ echo "active";} ?>">
               <?php if(checkPermission('SupportSettings','view')){ ?>
               <a href="<?php echo base_url('SupportSettings'); ?>" <?php if($cur_viewname == "SupportSettings"){?>class="active"<?php }?> ><?php echo lang('settings_support'); ?></a>
                <?php }?>
           </li>
<!--	   <li class="dropdown"><a href="#" class="dropdown-toggle <?php //if($cur_viewname == "SupportSettings" || $cur_viewname == "KnowledgeBaseSettings"){ echo "active";} ?>" data-toggle="dropdown">Settings <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    
                    <li class="<?php //if($cur_viewname == "KnowledgeBaseSettings"){ echo "active";} ?>"><a href="<?php //echo base_url('KnowledgeBaseSettings');?>" <?php //if($cur_viewname == "KnowledgeBaseSettings" && $this->uri->segment("2") == 'KnowledgeBaseSettings'){?>class="active"<?php //}?>><?//lang('settings_knowledge')?></a></li>
                </ul>
            </li>-->
	  
	</ul>
</nav>
