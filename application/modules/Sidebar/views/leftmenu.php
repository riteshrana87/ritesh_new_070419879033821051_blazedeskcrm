<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<nav role="navigation" class="navbar-collapse collapse" style="">
    <ul class="nav">
        <li><a href="<?php echo base_url(); ?>" <?php if ($cur_viewname == "Dashboard") { ?>class="active"<?php } ?>><?=lang('crm_dashboard')?></a></li>
        
        <?php if(checkPermission ('Marketingcampaign', 'view')){?>
        <li><a href="<?php echo base_url() . 'Marketingcampaign'; ?>" class="<?php
            if ($cur_viewname == "Marketingcampaign") {
                echo "active";
            }
            ?>"><?=lang('marketing_campaigns')?></a>
        </li>
        <?php }?>
        <?php if (checkPermission ('SalesOverview', 'view')) { ?><li><a href="<?php echo base_url() . 'SalesOverview'; ?>" id="sales" class="<?php
               if ($cur_viewname == "SalesOverview") {
                   echo "active";
               }
            ?>"><?=lang('sales_overview')?></a></li><?php } ?>
        <?php if (checkPermission ('CrmCompany', 'view')) { ?><li><a href="<?php echo base_url() . 'CrmCompany'; ?>" class="<?php
               if ($cur_viewname == "CrmCompany") {
                   echo "active";
               }
               ?>"><?=lang('companies')?></a></li><?php } ?>
        <?php if (checkPermission ('Contact', 'view')) { ?><li><a href="<?php echo base_url() . 'Contact'; ?>" class="<?php
               if ($cur_viewname == "Contact") {
                   echo "active";
               }
               ?>"><?=lang('contacts')?></a></li><?php } ?>
        <?php if (checkPermission ('Account', 'view')) { ?>
			<li><a href="<?php echo base_url() . 'Account'; ?>" class="<?php
				if ($cur_viewname == "Account") {
					echo "active";
				}
				?>"><?=lang('clients')?></a></li>
		<?php } ?>
		<?php if (checkPermission ('Estimates', 'view')) { ?>
        <li><a href="<?php echo base_url() . 'Estimates'; ?>" class="<?php
            if ($cur_viewname == "Estimates") {
                echo "active";
            }
            ?>"><?=lang('estimates')?></a></li>
		<?php } ?>
        <?php if (checkPermission ('Opportunity', 'view')) { ?><li><a href="<?php echo base_url() . 'Opportunity'; ?>" class="<?php
            if ($cur_viewname == "Opportunity") {
                echo "active";
            }
            ?>"><?=lang('opportunities')?></a></li><?php } ?>
        <!--	  <li><a href="#">Cases</a></li>-->
        <li><a class="<?php echo ($this->uri->segment(1) == "Filemanager")?"active":''?>"href="<?php echo base_url('Filemanager'); ?>"><?=lang('filemanager')?></a></li>
        <?php if (checkPermission ('Product', 'view')) { ?><li><a href="<?php echo base_url() . 'Product'; ?>" class="<?php
            if ($cur_viewname == "Product") {
                echo "active";
            }
            ?>"><?=lang('products')?></a></li><?php } ?>
        <?php if (checkPermission ('Emailtemplate', 'view')) { ?>
		<li><a href="<?php echo base_url() . 'Emailtemplate'; ?>" class="<?php
            if ($cur_viewname == "Emailtemplate") {
                echo "active";
            }
            ?>"><?=lang('templates')?></a></li>
		<?php } ?>
            <?php if($sub_domain=='blazedesk'){
            ?>
            
        <li><a href="<?php echo base_url() . 'TeleMarketing'; ?>" class="<?php
            if ($cur_viewname == "TeleMarketing") {
                echo "active";
            }
            ?>"><?=lang('TeleMarketing')?></a></li>
            <?php }?>
        <?php if (checkPermission ('CampaignReport', 'view')) { ?>
        <li><a href="<?php echo base_url() . 'CampaignReport'; ?>" class="<?php
            if ($cur_viewname == "CampaignReport") {
                echo "active";
            }
            ?>"><?=lang('reports')?></a></li>
        <?php }?>
       
        <li class="dropdown">
            <a href="#" id="dLabel" class="dropdown-toggle <?php
                    if($cur_viewname == "SalesTargetSettings") 
                    {
                        echo "active";
                    }
                    else if ($cur_viewname == "CasesType") {
                        echo "active";
                    }
                    else if($cur_viewname == "jobTitle") 
                    {
                        echo "active";
                    }
                    else if($cur_viewname == "Supplier") 
                    {
                        echo "active";
                    }
                    
                    
               ?>" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo lang('settings'); ?> <span class="caret"></span></a>
            <ul class="dropdown-menu" aria-labelledby="dLabel">
                
                <!--<li class="<?php
                    if ($cur_viewname == "Currencysettings") {
                        echo "active";
                    }
               ?>"><a href="<?= base_url('Currencysettings') ?>">Currency Settings</a></li>
                <li class="<?php
                    if ($cur_viewname == "EstimateSettings") {
                        echo "active";
                    }
               ?>"><a href="<?= base_url('EstimateSettings') ?>" >Terms & Conditions</a></li>-->
               
                <?php if(checkPermission('SalesTargetSettings', 'view')){ ?>
                <li class="<?php
                    if ($cur_viewname == "SalesTargetSettings") {
                        echo "active";
                    }
               ?>"><a href="<?php echo base_url('SalesTargetSettings/'); ?>"><?php echo lang('sales_target_settings'); ?></a></li>
                <?php }?>
                
                 <?php if(checkPermission('CasesType','view')){ ?>
                <li class="<?php
                    if ($cur_viewname == "CasesType") {
                        echo "active";
                    }
               ?>"><a href="<?php echo base_url('CasesType'); ?>"><?php echo lang('case_type'); ?></a>
                </li>
                <?php }?>
                 <?php if(checkPermission('Contact','view')){ ?>
                <li class="<?php
                    if ($cur_viewname == "jobTitle") {
                        echo "active";
                    }
                    ?>"><a href="<?php echo base_url('Contact') . "/jobTitle"; ?>"><?php echo lang('JOB_TITLE'); ?></a>
                </li>
                <?php }?>
                <li class="<?php
                    if ($cur_viewname == "Supplier") {
                        echo "active";
                    }
               ?>"><a href="<?php echo base_url('Supplier'); ?>"><?php echo lang('supplier'); ?></a></li>
            </ul>
        </li>

   <!--<li><a href=<?php echo base_url('Support/'); ?>>Support</a></li>-->
    </ul>
</nav>
