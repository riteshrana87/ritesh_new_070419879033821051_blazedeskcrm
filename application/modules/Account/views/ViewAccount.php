<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$setting_current_tab = $this->session->userdata('setting_current_tab');
if (!isset($setting_current_tab) || $setting_current_tab == '') {
    $sess_array = array('setting_current_tab' => 'Communication');
    $this->session->set_userdata($sess_array);
}
$formAction = 'insertdata';
$path = $account_view . '/' . $formAction;
?>
<div class="clr"></div>
<div class="container">
    <!-- Example row of columns -->
    <div class="row">
        <div class="col-md-6 col-md-6">
            <ul class="breadcrumb nobreadcrumb-bg">
                <li><a href="<?php echo base_url(); ?>"><?= lang('crm') ?></a></li>
                <li><a href="<?php echo base_url() . 'SalesOverview'; ?>"><?= lang('sales_overview') ?></a></li>
                <li><a href="<?php echo base_url() . 'Account'; ?>"><?= lang('accounts') ?></a></li>
                <li class="active"><?php echo htmlentities(stripslashes($all_records[0]['prospect_name'])); ?></li>
            </ul>
        </div>

        <!-- Search: Start -->
        <div class="col-xs-12 col-md-3 col-sm-6 pull-right text-right col-md-offset-3">
            <div class="row">
                <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#"><i class="fa fa-gear fa-2x"></i></a> </div>
                <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                    <div class="navbar-form navbar-left pull-right" id="searchForm">
                        <div class="input-group">
                            <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>">
                            <input type="text" name="searchtext" id="searchtext"  class="form-control" placeholder="<?= $this->lang->line('EST_LISTING_SEARCH_FOR') ?>" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
                            <span class="input-group-btn">
                                <button onclick="data_search()" class="btn btn-default" title="<?= $this->lang->line('search') ?>" type="button"><i class="fa fa-search fa-x"></i></button>&nbsp;

                                <button class="btn btn-default" title="<?= $this->lang->line('reset') ?>" onclick="reset_data()"><i class="fa fa-refresh fa-x"></i></button>
                            </span> </div>
                        <!-- /input-group -->
                    </div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
        <!-- Search: End -->
        <div class="clr"></div>
        <?php if ($this->session->flashdata('msg')) { ?>
            <div class='alert alert-success text-center'> <?php echo $this->session->flashdata('msg'); ?></div>
        <?php } ?>
        <?php if ($this->session->flashdata('error')) { ?>
            <div class='alert alert-danger text-center'> <?php echo $this->session->flashdata('error'); ?></div>
        <?php } ?>
        <div class="col-xs-12 col-md-12">
            <div class="whitebox">

                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <div class="col-sm-9"><h3><?php echo $all_records[0]['prospect_name']; ?></h3></div><div class="text-right col-sm-3 mt15"><span class="form-group">
                                <input type="text" class="form-control" id="prospect_auto_id" name="prospect_auto_id"  value="<?php
                                if (!empty($all_records[0]['prospect_auto_id'])) {
                                    echo $all_records[0]['prospect_auto_id'];
                                }
                                ?>" readonly>
                            </span></div><div class="clr"></div>
                    </div>	
                    <div class="clr"></div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="<?= $this->lang->line('account_name') ?> *" value="<?php
                        if (!empty($all_records[0]['prospect_name'])) {
                            echo htmlentities(stripslashes($all_records[0]['prospect_name']));
                        }
                        ?>" readonly>
                    </div>
                    <div class="clr"></div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="<?= $this->lang->line('address1') ?>" value="<?php
                        if (!empty($all_records[0]['address1'])) {
                            echo htmlentities(stripslashes($all_records[0]['address1']));
                        }
                        ?>" readonly>
                    </div>
                    <div class="clr"></div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="<?= $this->lang->line('address2') ?>" value="<?php
                        if (!empty($all_records[0]['address2'])) {
                            echo htmlentities(stripslashes($all_records[0]['address2']));
                        }
                        ?>" readonly>
                    </div>
                    <div class="clr"></div>

                    <div class="col-xs-6 col-md-6 no-left-pad">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('postal_code') ?>" value="<?php
                            if (!empty($all_records[0]['postal_code'])) {
                                echo $all_records[0]['postal_code'];
                            }
                            ?>" readonly>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6 no-right-pad">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('city') ?>" value="<?php
                            if (!empty($all_records[0]['city'])) {
                                echo htmlentities(stripslashes($all_records[0]['city']));
                            }
                            ?>" readonly>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-xs-6 col-md-6 no-left-pad">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('state') ?>" value="<?php
                            if (!empty($all_records[0]['state'])) {
                                echo htmlentities(stripslashes($all_records[0]['state']));
                            }
                            ?>" readonly>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6 no-right-pad" >
                        <div class="form-group">
                            <select class="form-control chosen-select"  tabindex="8" data-parsley-errors-container="#country-errors" disabled="true">
                                <option value="">
                                    <?= $this->lang->line('select_country') ?> *
                                </option>
                                <?php if (isset($country_data) && count($country_data) > 0) { ?>
                                    <?php
                                    foreach ($country_data as $country_data) {
                                        if ($country_data['country_id'] == $all_records[0]['country_id']) {
                                            ?>
                                            <option value="<?php echo $country_data['country_id']; ?>" <?php
                                            if (!empty($all_records[0]['country_id']) && $all_records[0]['country_id'] == $country_data['country_id']) {
                                                echo 'selected';
                                            }
                                            ?>><?php echo $country_data['country_name']; ?></option>
                                                <?php } ?>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-xs-6 col-md-6 no-left-pad">
                        <div class="form-group">
                            <select  class="form-control chosen-select"  tabindex="13" disabled="true">
                                <option value="">
                                <?= $this->lang->line('select_number_type') ?>
                                </option>
                                <option value="1" <?php
                                if (!empty($all_records[0]['number_type1']) && $all_records[0]['number_type1'] == '1') {
                                    echo "selected='selected'";
                                }
                                ?>>
                                <?= $this->lang->line('home') ?>
                                </option>
                                <option value="2" <?php
                                if (!empty($all_records[0]['number_type1']) && ($all_records[0]['number_type1'] == '2')) {
                                    echo 'selected=selected';
                                }
                                ?>>
                                <?= $this->lang->line('mobile') ?>
                                </option>
                                <option value="3" <?php
                                if (!empty($all_records[0]['number_type1']) && ($all_records[0]['number_type1'] == '3')) {
                                    echo 'selected=selected';
                                }
                                ?>>
                                <?= $this->lang->line('office') ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6 no-right-pad" >
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('number') ?>" value="<?php
                            if (!empty($all_records[0]['phone_number'])) {
                                echo $all_records[0]['phone_number'];
                            }
                            ?>" readonly>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="clr"></div>
                    <div class="col-xs-6 col-md-6 no-left-pad">
                        <div class="form-group">
                            <select class="form-control chosen-select" tabindex="13" disabled="true">
                                <option value="">
                                <?= $this->lang->line('select_number_type') ?>
                                </option>
                                <?php if (!empty($all_records[0]['number_type2'])) { ?>
                                    <option value="1" <?php
                                    if (!empty($all_records[0]['number_type2']) && $all_records[0]['number_type2'] == '1') {
                                        echo "selected='selected'";
                                    }
                                    ?>>
                                    <?= $this->lang->line('home') ?>
                                    </option>
                                    <option value="2" <?php
                                    if (!empty($all_records[0]['number_type2']) && ($all_records[0]['number_type2'] == '2')) {
                                        echo 'selected=selected';
                                    }
                                    ?>>
                                    <?= $this->lang->line('mobile') ?>
                                    </option>
                                    <option value="3" <?php
                                    if (!empty($all_records[0]['number_type2']) && ($all_records[0]['number_type2'] == '3')) {
                                        echo 'selected=selected';
                                    }
                                    ?>>
                                    <?= $this->lang->line('office') ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6 no-right-pad" >
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('number') ?>" value="<?php
                            if (!empty($all_records[0]['phone_no2'])) {
                                echo $all_records[0]['phone_no2'];
                            }
                            ?>" readonly>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="form-group">
                        <select class="form-control chosen-select" disabled="true">
                            <option value=""><?= $this->lang->line('language_not_filled') ?></option>
                            <?php if (isset($language_data) && count($language_data) > 0) { ?>
                                <?php foreach ($language_data as $language) {
                                    if ($language['language_id'] == $all_records[0]['language_id']) {
                                        ?>
                                        <option value="<?php echo $language['language_id']; ?>" <?php
                                                if (!empty($all_records[0]['language_id']) && $all_records[0]['language_id'] == $language['language_id']) {
                                                    echo 'selected';
                                                }
                                                ?>><?php echo $language['language_name']; ?></option>
                                <?php } ?>
                            <?php } } ?>
                        </select>
                    </div>
                    <div class="clr"></div>

                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="<?= $this->lang->line('branche') ?>" value="<?php
                               if (!empty($all_records[0]['branch_name'])) {
                                   echo htmlentities(stripslashes($all_records[0]['branch_name']));
                               }?>" readonly>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="col-xs-12 col-md-6 pad-tb6">
                   <div class="row"><!--estimate worth--> <div class="col-xs-12 col-md-4 no-left-pad">
                        <div class="border-box text-center"><h4><b><?= $this->lang->line('estimated_worth') ?><br/><?php
                               if (!empty($estimate_prospect_worth[0]['value'])) {
                                   echo $estimate_prospect_worth[0]['value'];
                               }?></b></h4></div>
                    </div>
                    <div class="col-xs-12 col-md-8  no-right-pad">

                        <div class="col-xs-12 col-md-6 no-left-pad form-group">
                            <label style="padding-top:10px"><?= $this->lang->line('creation_date') ?></label>
                        </div>
                        <div class="col-xs-6 col-md-6  no-right-pad form-group">
                            <input type="text" class="form-control creation_date" placeholder="<?= $this->lang->line('creation_date') ?>"  onkeydown="return false" value="<?php
                                   if (!empty($all_records[0]['creation_date']) && $all_records[0]['creation_date'] != '0000-00-00') {
                                       echo configDateTime($all_records[0]['creation_date']);
                                   } else {
                                       echo date("m/d/Y");
                                   }?>" readonly>
                        </div>
                        <div class="clr"></div>
                        <div class="col-xs-12 col-md-12 no-right-pad no-left-pad">
                            <div class="form-group">
                                <select class="form-control chosen-select" tabindex="9">
                                    <option value="">
                                    <?= $this->lang->line('select_prospect_owner') ?>
                                    </option>
                                    <?php if (isset($prospect_owner) && count($prospect_owner) > 0) { ?>
                                        <?php
                                        foreach ($prospect_owner as $prospect) {
                                            if ($prospect['login_id'] == $all_records[0]['prospect_owner_id']) {
                                                ?>
                                                <option value="<?php echo $prospect['login_id']; ?>" <?php
                                                if (!empty($all_records[0]['prospect_owner_id']) && $all_records[0]['prospect_owner_id'] == $prospect['login_id']) {
                                                    echo 'selected';
                                                }
                                                ?>><?php echo ucfirst($prospect['firstname']) . " " . ucfirst($prospect['lastname']); ?></option>
                                            <?php } ?>
                                        <?php }
                                    } ?>
                                </select>
                            </div></div>
                    </div>
                    <div class="clr"></div><!--estimate worth--></div>
                    <div class="form-group"><label><?= $this->lang->line('contacts') ?></label> </div>
                    <div class="clr"> </div>

                    <!-- contact data of company show here -->
                    <div id="Contacts">

                    </div>

                    <div id="deletedContactsDiv"></div>
                    <div class="clr"> </div>
                    <div class="form-group">
                        <?php if (checkPermission('Account', 'add')) { ?>
                            <a id="add_row_contact" class="btn btn-primary align-center" tabindex="15"> <span class="glyphicon glyphicon-plus"></span>
                                <?= $this->lang->line('add_another_contact') ?>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="clr"> </div>
                </div>
                <div class="clr"></div>
            </div>
        </div>
        <div class="clr"></div>
        <div class="col-xs-12 col-md-12">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="<?php
                if ($this->session->userdata('setting_current_tab') == 'Communication') {
                    echo "active";
                }
                ?>"><a href="#Communication" aria-controls="Communication" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_COMMUNICATION'); ?></a></li>

                <li role="presentation" class="<?php
                if ($this->session->userdata('setting_current_tab') == 'Deals') {
                    echo "active";
                }
                ?>"><a href="#Deals" aria-controls="Deals" role="tab" data-toggle="tab"><?php echo lang('deals'); ?></a>
                </li>


                <li role="presentation" class="<?php
                if ($this->session->userdata('setting_current_tab') == 'Estimates') {
                    echo "active";
                }
                ?>"><a href="#Estimates" aria-controls="Estimates" role="tab" data-toggle="tab"><?php echo lang('estimates'); ?></a>
                </li>
                <li role="presentation" class="<?php
                if ($this->session->userdata('setting_current_tab') == 'Contracts') {
                    echo "active";
                }
                ?>"><a href="#Contracts" aria-controls="Contracts" role="tab" data-toggle="tab"><?php echo lang('contracts'); ?></a>
                </li>
                <li role="presentation" class="<?php
                if ($this->session->userdata('setting_current_tab') == 'Projects') {
                    echo "active";
                }
                ?>"><a href="#Projects" aria-controls="Projects" role="tab" data-toggle="tab"><?php echo lang('projects'); ?></a>
                </li>
                <li role="presentation" class="<?php
                if ($this->session->userdata('setting_current_tab') == 'Invoices') {
                    echo "active";
                }
                ?>"><a href="#Invoices" aria-controls="Invoices" role="tab" data-toggle="tab"><?php echo lang('invoices'); ?></a>
                </li>

                <li role="presentation" class="<?php
                if ($this->session->userdata('setting_current_tab') == 'SupportTickets') {
                    echo "active";
                }
                ?>"><a href="#SupportTickets" aria-controls="SupportTickets" role="tab" data-toggle="tab"><?php echo lang('Supporttickets'); ?></a>
                </li>

                <li role="presentation" class="<?php
                if ($this->session->userdata('setting_current_tab') == 'Documents') {
                    echo "active";
                }
                ?>"><a href="#Documents" aria-controls="Documents" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_DOCUMENTS'); ?></a>
                </li>

            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane <?php
                if ($this->session->userdata('setting_current_tab') == 'Communication') {
                    echo "active";
                }
                ?>" id="Communication">
                    <!-- Communication data has been placed by ajax call  -->
                </div>
                <div role="tabpanel" class="tab-pane <?php
                        if ($this->session->userdata('setting_current_tab') == 'Deals') {
                            echo "active";
                        }
                        ?>" id="Deals">

                    <div class="whitebox">
                        <?php
                        $opportunityArray = $prospectingArray = array();
                        if (count($prospect_data) > 0) {
                            foreach ($prospect_data as $row) {
                                if (($row['is_estimate_sent'] == 1 ) and ( $row['status_type'] == 1 )) {
                                    $prospectingArray[] = $row;
                                } else {
                                    $opportunityArray[$row['status_type']][] = $row;
                                }
                            }
                        }
                        ?>
                        <div id="heads" class="col-xs-12 col-sm-12">
                            <div id="<?php echo lang('prospecting'); ?>" class="oppHead text-center pad-6 col-sm-3">
                                <b><?php echo lang('prospecting'); ?></b>
                            </div>
                            <div id="<?php echo lang('proposal'); ?>" class="oppHead text-center pad-6 col-sm-3">
                                <b><?php echo lang('proposal'); ?></b>
                            </div>
                            <div id="<?php echo lang('won'); ?>" class="oppHead text-center pad-6 col-sm-3">
                                <b> <?php echo lang('won'); ?></b>
                            </div>
                            <div id="<?php echo lang('lost'); ?>" class="oppHead text-center pad-6 col-sm-3">
                                <b>  <?php echo lang('lost'); ?></b>
                            </div>
                        </div>
                        <div class="clr"></div>
                                <?php if (count($prospect_data) > 0) { ?>

                            <div id="proposalType" class="col-xs-12 col-sm-12 verticl-scroll row">
                                <div id="<?php echo lang('prospecting'); ?>" data-dataType="1" class="col-sm-3" style="min-height: 500px">
                                                <?php
                                                if (array_key_exists(1, $opportunityArray)) {
                                                    foreach ($opportunityArray[1] as $row) {
                                                        ?>
                                            <div class="oppSort" data-id="<?php echo $row['prospect_id']; ?>"><div class="gray-borderbox" data-dataType="<?php echo $row['status_type']; ?>" data-type="<?php echo lang('prospecting'); ?>" data-id="<?php echo $row['prospect_id']; ?>"> <b><?php echo $row['prospect_name']; ?></b> 
                                                    <span class="pull-right bd-actbn-btn">
                                                        <a href="<?= base_url('Opportunity/viewdata/' . $row['prospect_id']) ?>" class="edit_contact" title="<?php echo lang('view'); ?>"><i class="fa fa-search greencol"></i></a>
                                                        <?php if (checkPermission('Opportunity', 'edit')) { ?><a  data-href="<?php echo base_url('Opportunity/editdata/' . $row['prospect_id']); ?>" title="<?php echo lang('edit'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="edit_contact" id="edit_opportunity" ><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
                                                        <?php if (checkPermission('Opportunity', 'delete')) { ?><a data-href="javascript:;" title="<?php echo lang('delete'); ?>" onclick="delete_request('<?php echo $row['prospect_id']; ?>', '<?php echo base_url('Opportunity/viewdata/' . $row['prospect_id']); ?>');" class="edit_contact" ><i class="fa fa-remove fa-x redcol" ></i></a><?php } ?>
                                                        <a href="javascript:;"><i class="fa fa-gear bluecol"></i></a>
                                                    </span> 
                                                    <span class="clr"></span>
                                          <div class="bd-span-block"> <span> <?php echo $row['prospect_auto_id']; ?></span>
                                           <span> <?php echo lang('worth'); ?> <?php echo ($row['value'] != '') ? getCurrencySymbol($row['country_id_symbol']) . $row['value'] : 0; ?></span></div>
                                                    
                                                    <div class="pad-l6"><input type="text" class="form-control" value="<?php echo configDateTime($row['creation_date']); ?>" disabled/></div>
                                                </div>
                                                <div class="clr"></div></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="oppSort" style="min-height:122px"></div>
    <?php } ?>
                                </div>
                                <div id="<?php echo lang('proposal'); ?>" data-dataType="0" class="col-sm-3" style="min-height: 500px">
                                                <?php
                                                if (count($prospectingArray) > 0) {
                                                    foreach ($prospectingArray as $row) {
                                                        ?>
                                    <div style="min-height:122px" class="oppSort" data-id="<?php echo $row['prospect_id']; ?>"><div class="gray-borderbox" data-dataType="<?php echo $row['status_type']; ?>" data-type="<?php echo lang('proposal'); ?>" data-id="<?php echo $row['prospect_id']; ?>"> <b><?php echo $row['prospect_name']; ?></b> 
                                                        <span class="pull-right bd-actbn-btn">
                                                            <a href="<?= base_url('Opportunity/viewdata/' . $row['prospect_id']) ?>" class="edit_contact" title="<?php echo lang('view'); ?>"><i class="fa fa-search greencol"></i></a>
                                                            <?php if (checkPermission('Opportunity', 'edit')) { ?><a  data-href="<?php echo base_url('Opportunity/editdata/' . $row['prospect_id']); ?>" title="<?php echo lang('edit'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="edit_contact" id="edit_opportunity" ><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
                                                            <?php if (checkPermission('Opportunity', 'delete')) { ?><a data-href="javascript:;" title="<?php echo lang('delete'); ?>" onclick="delete_request('<?php echo $row['prospect_id']; ?>', '<?php echo base_url('Opportunity/viewdata/' . $row['prospect_id']); ?>');" class="edit_contact" ><i class="fa fa-remove fa-x redcol" ></i></a><?php } ?>
                                                            <a href="javascript:;"><i class="fa fa-gear bluecol"></i></a>
                                                        </span> 
                                                        <span class="clr"></span>
                                                        <div class="bd-span-block"><span><?php echo $row['prospect_auto_id']; ?></span>
                                                        <span><?php echo lang('worth'); ?> <?php echo ($row['value'] != '') ? getCurrencySymbol($row['country_id_symbol']) . $row['value'] : 0; ?></span></div>
                                                        
                                                        <div class="pad-l6"><input type="text" class="form-control" value="<?php echo configDateTime($row['creation_date']); ?>" disabled/></div>
                                                    </div>
                                                    <div class="clr"></div>

                                        </div>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <div class="oppSort" style="min-height:122px" ></div>    
                                            <?php
                                        }
                                        ?>
                                </div>
                                <div id="<?php echo lang('won'); ?>" data-dataType="3" class="col-sm-3" style="min-height: 500px">
                                                <?php
                                                if (array_key_exists(3, $opportunityArray)) {
                                                    foreach ($opportunityArray[3] as $row) {
                                                        ?>
                                            <div style="min-height:122px" class="oppSort" data-id="<?php echo $row['prospect_id']; ?>"><div class="gray-borderbox" data-dataType="<?php echo $row['status_type']; ?>" data-type="<?php echo lang('won'); ?>" data-id="<?php echo $row['prospect_id']; ?>"> <b><?php echo $row['prospect_name']; ?></b> 
                                                    <span class="pull-right bd-actbn-btn">
                                                        <a href="<?= base_url('Account/viewdata/' . $row['prospect_id']) ?>" class="edit_contact" title="<?php echo lang('view'); ?>"><i class="fa fa-search greencol"></i></a>
                                                        <?php if (checkPermission('Account', 'edit')) { ?><a  data-href="<?php echo base_url('Opportunity/editdata/' . $row['prospect_id']); ?>" title="<?php echo lang('edit'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="edit_contact" id="edit_opportunity" ><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
                                                        <?php if (checkPermission('Account', 'delete')) { ?><a data-href="javascript:;" title="<?php echo lang('delete'); ?>" onclick="delete_account('<?php echo $row['prospect_id']; ?>', '<?php echo base_url('Opportunity/viewdata/' . $row['prospect_id']); ?>');" class="edit_contact" ><i class="fa fa-remove fa-x redcol" ></i></a><?php } ?>
                                                        <a href="javascript:;"><i class="fa fa-gear bluecol"></i></a>
                                                    </span> 
                                                    
                                           <div class="bd-span-block"> <span><?php echo $row['prospect_auto_id']; ?></span>
                                           <span> <?php echo lang('worth'); ?> <?php echo ($row['value'] != '') ? getCurrencySymbol($row['country_id_symbol']) . $row['value'] : 0; ?></span></div>
                                                    
                                                    <div class="pad-l6"><input type="text" class="form-control" value="<?php echo configDateTime($row['creation_date']); ?>" disabled/></div>
                                                </div>
                                                <div class="clr"></div></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="oppSort" style="min-height:122px" ></div>
    <?php } ?>
                                </div>
                                <div id="<?php echo lang('lost'); ?>" data-dataType="4" class="col-sm-3" style="min-height: 500px">
                                                <?php
                                                if (array_key_exists(4, $opportunityArray)) {
                                                    foreach ($opportunityArray[4] as $row) {
                                                        ?>
                                            <div style="min-height:122px" class="oppSort" ><div class="gray-borderbox" data-type="<?php echo lang('lost'); ?>" data-dataType="<?php echo $row['status_type']; ?>" data-id="<?php echo $row['prospect_id']; ?>"> <b><?php echo $row['prospect_name']; ?></b> 
                                                    <span class="pull-right bd-actbn-btn">
                                                        <a href="<?= base_url('Account/viewdata/' . $row['prospect_id']) ?>" class="edit_contact" title="<?php echo lang('view'); ?>"><i class="fa fa-search greencol"></i></a>
                                                        <?php if (checkPermission('Account', 'edit')) { ?><a  data-href="<?php echo base_url('Opportunity/editdata/' . $row['prospect_id']); ?>" data-toggle="ajaxModal" title="<?php echo lang('edit'); ?>" aria-hidden="true" data-refresh="true" class="edit_contact" id="edit_opportunity" ><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
                                                        <?php if (checkPermission('Account', 'delete')) { ?><a data-href="javascript:;" title="<?php echo lang('delete'); ?>" onclick="delete_account('<?php echo $row['prospect_id']; ?>', '<?php echo base_url('Opportunity/viewdata/' . $row['prospect_id']); ?>');" class="edit_contact" ><i class="fa fa-remove fa-x redcol" ></i></a><?php } ?>
                                                        <a href="javascript:;"><i class="fa fa-gear bluecol"></i></a>
                                                    </span> 
                                                    <span class="clr"></span>
                                           <div class="bd-span-block"> <span><?php echo $row['prospect_auto_id']; ?></span>
                                            <span><?php echo lang('worth'); ?> <?php echo ($row['value'] != '') ? getCurrencySymbol($row['country_id_symbol']) . $row['value'] : 0; ?></span></div>
                                                    
                                                    <div class="pad-l6"><input type="text" class="form-control" value="<?php echo configDateTime($row['creation_date']); ?>" disabled/></div>
                                                </div>
                                                <div class="clr"></div></div>
            <?php
        }
    } else {
        ?>
                                        <div class="oppSort" style="min-height:122px" ></div>
    <?php } ?>
                                </div>
                            </div>


                        <?php } else { ?>
                            <div id="proposalType" class="col-xs-12 col-sm-12 verticl-scroll row">
                                <div class="col-sm-12">
                                    <h6 class="text-center"><?php echo lang('common_no_record_found'); ?></h6>
                                </div>
                            </div>
<?php }
?>
                        <div class="clr"></div>
                    </div>




                </div>
                <div role="tabpanel" class="tab-pane <?php
                if ($this->session->userdata('setting_current_tab') == 'Estimates') {
                    echo "active";
                }
                ?>" id="Estimates">
                    <!-- Estimates data has been placed by ajax call  -->
                </div>

                <div role="tabpanel" class="tab-pane <?php
                if ($this->session->userdata('setting_current_tab') == 'Contracts') {
                    echo "active";
                }
                ?>" id="Contracts">
                    <div class="col-sm-12 whitebox">
                        <h6 class="text-center"><?php echo lang('common_no_record_found'); ?></h6>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane <?php
                if ($this->session->userdata('setting_current_tab') == 'Projects') {
                    echo "active";
                }
                ?>" id="Projects">
                    <!-- Campaign data has been placed by ajax call  -->
                </div>

                <div role="tabpanel" class="tab-pane <?php
                     if ($this->session->userdata('setting_current_tab') == 'Invoices') {
                         echo "active";
                     }
                ?>" id="Invoices">
                    <div class="col-sm-12 whitebox">
                        <h6 class="text-center"><?php echo lang('common_no_record_found'); ?></h6>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane <?php
                if ($this->session->userdata('setting_current_tab') == 'SupportTickets') {
                    echo "active";
                }
                ?>" id="SupportTickets">
                    <!-- Attach File data has been placed by ajax call  -->
                </div>
                <div role="tabpanel" class="tab-pane <?php
                if ($this->session->userdata('setting_current_tab') == 'Documents') {
                    echo "active";
                }
                ?>" id="Documents">


                </div>
            </div>
        </div>

    </div>

    <div class="clr"></div>
</div>
</div>

<div id="form_contact">
    <div class="modal in" id="add_contact_data" name="add_contact_data" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
<?php
$contact_path = 'Account/saveContactData';
$attributes = array("name" => "frm_contact_data", "id" => "frm_contact_data", 'data-parsley-validate' => "");
echo form_open_multipart($contact_path, $attributes);
?>
                <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button">×</button>
                    <h4 class="modal-title">
                        <div class="modelTaskTitle"> <?= $this->lang->line('add_contact'); ?> </div>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class = " row contacts" id="add_contact">

                        <div class = "col-sm-3 form-group">
                            <input type="hidden" name="prospect_id" id="prospect_id" value="<?php
                            if (!empty($all_records[0]['prospect_id'])) {
                                echo $all_records[0]['prospect_id'];
                            }
?>">
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('contact_name') ?> *" name="contact_name_account[]" id="contact_name_account"  >
                        </div>
                        <div class = "col-sm-3 form-group">
                            <input type="email" class="form-control email_contact" onchange='validateContactUniqueness(this.value)' placeholder="<?= $this->lang->line('email_address') ?> *" onchange='validateContactUniqueness(this.value)' data-parsley-trigger="change" name="email_id_account[]" id="email_id_account" >
                            <ul class='parsley-errors-list filled hidden' id='email_err'><li class='parsley-remote'><?php echo lang('contact_email_error'); ?></li></ul>
                        </div>
                        <div class = "col-sm-3 form-group">
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('phone_no') ?> *" name="phone_no_account[]"  id="phone_no_account" data-parsley-pattern='^[\d\+\-\.\(\)\/\s]*$' maxlength="25"  >
                        </div>
                        <div class = "col-sm-2 col-lg-2 text-right pad-t6 txt-left-resp form-group">
                            <input type="hidden" name="contact_id[]" id="contact_id" >
                            <input name='primary_contact[]'  type='hidden' id="primary_def" class="defprimary primary" value='0'>
                            <input name="primary_contact[]"  type="radio" value="1" id="primary0" ><label class="radio-inline"> <?= $this->lang->line('primary') ?></label>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <center>
                        <input type="submit" value="<?= lang('EST_EDIT_SAVE') ?>" name="contact_submit_button" id="contact_submit_button" class="btn btn-primary">
                    </center>
                </div>
<?php echo form_close(); ?> </div>
        </div>
    </div>
</div>
<script>


     //Not allow duplicate contact email code
    function validateContactUniqueness(email)
    {
        $('.email_contact').each(function () {
            if ("email_id_account"  != $(this).attr('id'))
            {
                if (email == $(this).val())
                {
                    $(this).val('');
                }
            }
        });
        var companyId = "<?php echo $all_records[0]['company_id']; ?>";
        
        if (email != '')
        {

            $.ajax({
                url: "<?php echo base_url('Opportunity/validateContactUniqueness'); ?>",
                data: {'email': email, 'company_id': companyId},
                type: "POST",
                dataType: "json",
                success: function (d)
                {
                    if (d.status == 1)
                    {
                        $('#email_id_account').val('');
                        $('#email_id_account').addClass('parsley-error');
                        $('#email_err').removeClass('hidden');
                    }
                    else
                    {
                        $('#email_id_account').removeClass('parsley-error')
                        $('#email_err').addClass('hidden');
                    }
                }
            });
        }

    }

    var prospect_id = $('#prospect_id').val();
    //add another contact code
    if (prospect_id)
    {
        var regex = new RegExp("^[\\d\\+\\-\\.\\(\\)\\/\\s]*$");
        var add_row_no = $('.contacts').length - 1;
        $("#add_row_contact").click(function () {

        	$('#frm_contact_data').parsley().destroy();
            $('#contact_name_account').val('');
            $('#email_id_account').val('');
            $('#phone_no_account').val('');
            $('#email_id_account').removeClass('parsley-error')
            $('#email_err').addClass('hidden');
//
//            $('#contact_name_account').attr('data-parsley-required', 'false');
//            $('#email_id_account').attr('data-parsley-required', 'false');
//            $('#phone_no_account').attr('data-parsley-required', 'false');
//
//            $('#contact_name_account').removeClass('parsley-success parsley-error parsley-type parsley-pattern');
//            $('#email_id_account').removeClass('parsley-success parsley-error parsley-type parsley-pattern');
//            $('#phone_no_account').removeClass('parsley-success parsley-error parsley-type parsley-pattern');
//            $("input[id=primary0]").removeAttr("checked");
//            $('.parsley-errors-list').hide();

            $('#form_contact').load($('#add_contact_data').modal('show'));
            // $('#add_contact_data').modal('show');

        });
    }
    else {
        var regex = new RegExp("^[\\d\\+\\-\\.\\(\\)\\/\\s]*$");
        var add_row_no = 0;
        $("#add_row_contact").click(function () {

        	$('#frm_contact_data').parsley().destroy();
            $('#contact_name_account').val('');
            $('#email_id_account').val('');
            $('#phone_no_account').val('');
            $('#email_id_account').removeClass('parsley-error')
            $('#email_err').addClass('hidden');
//            //$('#frm_contact_data').parsley().destroy();
//            $('#contact_name_account').attr('data-parsley-required', 'false');
//            $('#email_id_account').attr('data-parsley-required', 'false');
//            $('#phone_no_account').attr('data-parsley-required', 'false');
//            $("input[name=primary0]").removeAttr("checked");
//            $('#contact_name_account').removeClass('parsley-error parsley-type parsley-pattern');
//            $('#email_id_account').removeClass('parsley-error parsley-type parsley-pattern');
//            $('#phone_no_account').removeClass('parsley-error parsley-type parsley-pattern');
//
//            $('.parsley-errors-list').hide();


            $('#form_contact').load($('#add_contact_data').modal('show'));
            //$('#add_contact_data').modal('show');
        });
    }
    $('body').delegate('#contact_submit_button', 'click', function (e) {

        $('#contact_name_account').attr('data-parsley-required', 'true');
        $('#email_id_account').attr('data-parsley-required', 'true');
        $('#phone_no_account').attr('data-parsley-required', 'true');

        $('.parsley-errors-list').show();

        if ($('#frm_contact_data').parsley().isValid()) {
            //disabled submit button after submit
            $('input[type="submit"]').prop('disabled', true);
            $('#frm_contact_data').submit();
        }
    });

    $('body').delegate("input[name='primary_contact[]']", 'click', function () {
        var cls = $(this).attr('id');
        $('.defprimary').val(0);
        $('.' + cls).val('');
    });

// add another delete contact save in array code
    $('.delcontacts').on('click', function () {

        var divId = ($(this).attr('data-id'));
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');
        var delete_meg = "<?php echo lang('CONFIRM_DELETE_CONTACT'); ?>";
        BootstrapDialog.show(
                {
                    title: '<?php echo $this->lang->line('Information'); ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                                dialog.close();
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
                                $('#deletedContactsDiv').append("<input type='hidden' name='softDeletedContacts[]' value='" + str1 + "'>");
                                $('#add_contact' + divId).remove();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                                dialog.close();

                            }
                        }]
                });


    });

    //add another delete contact remove row code
    function delete_row_contact(removeNum, contact_id) {
        var add_row_no = $('.contacts').length;
        if (add_row_no > 0) {
            var delete_meg = "<?php echo lang('CONFIRM_DELETE_CONTACT'); ?>";
            BootstrapDialog.show(
                    {
                        title: '<?php echo $this->lang->line('Information'); ?>',
                        message: delete_meg,
                        buttons: [{
                                label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                                action: function (dialog) {
                                    dialog.close();
                                    $('#confirm-id').on('hidden.bs.modal', function () {
                                        $('body').addClass('modal-open');
                                    });
                                }
                            }, {
                                label: '<?php echo $this->lang->line('ok'); ?>',
                                action: function (dialog) {
                                    if (contact_id) {
                                        $.ajax({
                                            type: "POST",
                                            url: "Account/delete_contact_master/",
                                            data: {'contact_id': contact_id},
                                            success: function (data)
                                            {
                                                BootstrapDialog.show({
                                                    message: "<?php echo lang('contact_delete_message'); ?>",
                                                    buttons: [{
                                                            label: '<?php echo lang('close'); ?>',
                                                            action: function (dialogItself) {
                                                                jQuery('#add_contact' + removeNum).remove();
                                                                add_row_no--;
                                                                dialogItself.close();
                                                            }
                                                        }]
                                                });


                                            }
                                        });
                                    }
                                    else {
                                        jQuery('#add_contact' + removeNum).remove();
                                        add_row_no--;
                                    }
                                    $('#confirm-id').on('hidden.bs.modal', function () {
                                        $('body').addClass('modal-open');
                                    });

                                    dialog.close();
                                }
                            }]
                    });
        }
    }
//not included in loadjsFiles bcoz there are to many validations are working as global

    $('#contact_address').html('<img src="<?php echo base_url() . "uploads/images/ajax-loader.gif"; ?>">');
    var contact_address = $('#hdn_contact_addres').val();
    var embed = '<iframe width="200" height="150" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?&amp;q=' + encodeURIComponent(contact_address) + '&amp;output=embed"></iframe>';
    $('#contact_address').html(embed);
    function delete_note(note_id, redirect_link)
    {
        //var url_contact = '<?php echo base_url() . "Contact/delete_note" ?>';
        var delete_url = "<?php echo base_url(); ?>Contact/delete_note/?note_id=" + note_id + "&link=" + redirect_link;
        var delete_meg = "<?php echo lang('CONFIRM_DELETE_NOTE'); ?>";
        BootstrapDialog.show(
                {
                    title: '<?php echo $this->lang->line('Information'); ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
                                window.location.href = delete_url;
                                dialog.close();
                            }
                        }]
                });

    }

    function delete_task(task_id, redirect_link)
    {

        //var url_contact = '<?php echo base_url() . "Contact/deletetask" ?>';
        var delete_url = "<?php echo base_url(); ?>Contact/delete_task/?task_id=" + task_id + "&link=" + redirect_link;
        var delete_meg = "<?php echo lang('CONFIRM_DELETE_TASK'); ?>";
        BootstrapDialog.show(
                {
                    title: '<?php echo $this->lang->line('Information'); ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
                                window.location.href = delete_url;
                                dialog.close();
                            }
                        }]
                });

    }

    function getContactData(searchtext)
    {
        var url_contact = '<?php echo base_url() . "Account/getContactData/" ?>';
        $.ajax({
            type: "POST",
            url: url_contact,
            data: {'getContactData': 'Contacts', 'searchtext': searchtext, 'company_id': '<?php echo $all_records[0]['company_id']; ?>'},
            success: function (data)
            {
                $('#Contacts').html(data);
            }
        });
    }

    function getCommunicationData(searchtext)
    {
        var url_contact = '<?php echo base_url() . "Account/getCommunicationData/" ?>';
        $.ajax({
            type: "POST",
            url: url_contact,
            data: {'getCommunicationData': 'Communication', 'searchtext': searchtext, 'company_id': '<?php echo $all_records[0]['company_id']; ?>'},
            success: function (data)
            {
                $('#Communication').html(data);
            }
        });
    }

    function getEstimatesData(searchtext)
    {
        var url_contact = '<?php echo base_url() . "Account/viewEstimateData/" ?>';
        $.ajax({
            type: "POST",
            url: url_contact,
            data: {'getEstimatesData': 'Estimates', 'searchtext': searchtext, 'company_id': '<?php echo $all_records[0]['company_id']; ?>'},
            success: function (data)
            {
                $('#Estimates').html(data);
            }
        });
    }

    function getDealsData(searchtext)
    {
        var url_contact = '<?php echo base_url() . "Account/viewAllDealsData/" ?>';
        $.ajax({
            type: "POST",
            url: url_contact,
            data: {'getDealsData': 'Deals', 'searchtext': searchtext, 'company_id': '<?php echo $all_records[0]['company_id']; ?>'},
            success: function (data)
            {
                $('#Deals').html(data);
            }
        });
    }

    function viewSupportData(searchtext)
    {
        var url_contact = '<?php echo base_url() . "Account/viewSupportData/" ?>';
        $.ajax({
            type: "POST",
            url: url_contact,
            data: {'viewSupportData': 'SupportTickets', 'searchtext': searchtext, 'company_id': '<?php echo $all_records[0]['company_id']; ?>'},
            success: function (data)
            {
                $('#SupportTickets').html(data);
            }
        });
    }

    function viewProjectsData(searchtext)
    {
        var url_contact = '<?php echo base_url() . "Account/viewProjectsData/" ?>';
        $.ajax({
            type: "POST",
            url: url_contact,
            data: {'viewProjectsData': 'Projects', 'searchtext': searchtext, 'company_id': '<?php echo $all_records[0]['company_id']; ?>'},
            success: function (data)
            {
                $('#Projects').html(data);
            }
        });
    }

    function getAccountFile(searchtext)
    {
        var url_contact = '<?php echo base_url() . "Account/getAccountFile/" ?>';
        $.ajax({
            type: "POST",
            url: url_contact,
            data: {'getAccountFile': 'Documents', 'searchtext': searchtext, 'prospect_id': '<?php echo $all_records[0]['prospect_id']; ?>'},
            success: function (data)
            {
                $('#Documents').html(data);
            }
        });
    }
    function data_search()
    {
        var searchtext = $('#searchtext').val();
        getContactData(searchtext);
        getCommunicationData(searchtext);
        getEstimatesData(searchtext);
        //  getDealsData(searchtext);
        viewSupportData(searchtext);
        viewProjectsData(searchtext);
        getAccountFile(searchtext);
    }

    function reset_data()
    {
        $('#searchtext').val('');
        getContactData('clearData');
        getCommunicationData('clearData');
        getEstimatesData('clearData');
        // getDealsData('clearData');
        viewSupportData('clearData');
        viewProjectsData('clearData');
        getAccountFile('clearData');
    }
    $(document).ready(function ()
    {
        getContactData('');
        getCommunicationData('');
        getEstimatesData('');
        // getDealsData('');
        viewSupportData('');
        viewProjectsData('');
        getAccountFile('');

        $("#proposalType .oppSort").sortable({
            connectWith: ".oppSort",
            axis: 'x',
            cursor: 'move',
            // containment: 'parent',
            tolerance: 'pointer', // this is the important bit
            receive: function (event, ui) {

                var id = ui.item.attr('data-id');
                var currtype = ui.item.attr('data-type');
                var dataType = $(this).parent('div').attr('data-dataType');
                var type = $(this).parent('div').attr('id');
                $.ajax({
                    url: "<?php echo base_url('Opportunity/updateStatus'); ?>",
                    type: 'POST',
                    data: {'id': id, 'type': type, 'currtype': currtype, 'dataType': dataType},
                    success: function (res)
                    {
                        if (res == 'done')
                        {
                            //alert(type);
                            $('#' + id).data('data-type', type);
                            //alert(type);
                            $('#' + id).data('data-datatype', dataType);
                            return false;
                        }
                    },
                    error: function ()
                    {
                        console.log('Error in call');
                    }

                });
            }
        }).disableSelection();


        //parsley validation
        $('.chosen-select').chosen();
        $('#add_account').parsley();


        //value for primary contact radio 1
        $("input[name='primary_contact[]']:checked").val('1');
        var cls = $("input[name='primary_contact[]']:checked").attr('id');
        $('.' + cls).val('');

        /* Start for Contacts */
        $("body").delegate("#table_contacts ul.tsc_pagination a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Contacts").empty();
                    $("#Contacts").html(response);

                    return false;
                }
            });
            return false;
        });

        $("body").delegate("#table_contacts th.sortTask a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Contacts").empty();
                    $("#Contacts").html(response);

                    return false;
                }
            });
            return false;
        });

        /* End for Contacts */


        /* Start for Communication */
        $("body").delegate("#table_communication ul.tsc_pagination a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Communication").empty();
                    $("#Communication").html(response);

                    return false;
                }
            });
            return false;
        });

        $("body").delegate("#table_communication th.sortTask a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Communication").empty();
                    $("#Communication").html(response);

                    return false;
                }
            });
            return false;
        });

        /* End for Communication */

        /* Start for Estimates */
        $("body").delegate("#table_estimates ul.tsc_pagination a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Estimates").empty();
                    $("#Estimates").html(response);

                    return false;
                }
            });
            return false;
        });

        $("body").delegate("#table_estimates th.sortTask a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Estimates").empty();
                    $("#Estimates").html(response);

                    return false;
                }
            });
            return false;
        });
        /* End for Estimates */

        /* Start for Deal */
        $("body").delegate("#table_deals ul.tsc_pagination a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Deals").empty();
                    $("#Deals").html(response);

                    return false;
                }
            });
            return false;
        });

        $("body").delegate("#table_deals th.sortTask a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Deals").empty();
                    $("#Deals").html(response);

                    return false;
                }
            });
            return false;
        });
        /* End for Deal */

        /* Start for SupportTickets */
        $("body").delegate("#table_support_ticket ul.tsc_pagination a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#SupportTickets").empty();
                    $("#SupportTickets").html(response);

                    return false;
                }
            });
            return false;
        });

        $("body").delegate("#table_support_ticket th.sortTask a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#SupportTickets").empty();
                    $("#SupportTickets").html(response);

                    return false;
                }
            });
            return false;
        });
        /* End for SupportTickets */
        /* Start for Projects */
        $("body").delegate("#table_projects ul.tsc_pagination a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Projects").empty();
                    $("#Projects").html(response);

                    return false;
                }
            });
            return false;
        });

        $("body").delegate("#table_projects th.sortTask a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Projects").empty();
                    $("#Projects").html(response);

                    return false;
                }
            });
            return false;
        });
        /* End for Projects */
        /* Start for Attach */
        $("body").delegate("#table_attach ul.tsc_pagination a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Documents").empty();
                    $("#Documents").html(response);

                    return false;
                }
            });
            return false;
        });

        /* End for  Attach */

    });

    function delete_request(prospect_id, redirect_link) {

        var delete_url = '<?php echo base_url(); ?>' + "Opportunity/deletedata/?id=" + prospect_id + "&link=" + redirect_link;
        var delete_meg = "<?php echo lang('CONFIRM_DELETE_CONTACT'); ?>";
        BootstrapDialog.show(
                {
                    title: '<?php echo $this->lang->line('Information'); ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
                                window.location.href = delete_url;
                                dialog.close();
                            }
                        }]
                });

    }

    function delete_account(prospect_id) {

        var delete_url = '<?php echo base_url(); ?>' + 'Account/deletedata/?id=' + prospect_id;
        var delete_meg = "<?php echo lang('CONFIRM_DELETE_CONTACT'); ?>";
        BootstrapDialog.show(
                {
                    title: '<?php echo $this->lang->line('Information'); ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
                                window.location.href = delete_url;
                                dialog.close();
                            }
                        }]
                });


    }

    //Not allow duplicate contact email code
    function validateContactUniqueness(email)
    {
        $('.email_contact').each(function () {
            if ("email_id_account" != $(this).attr('id'))
            {
                if (email == $(this).val())
                {
                    $(this).val('');
                }
            }
        });
        //var companyId = $('#company_id').val();
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        if (email != '')
        {

            $.ajax({
                url: "<?php echo base_url('Opportunity/validateContactUniqueness'); ?>",
                data: {'email': email, 'id':<?php echo (isset($all_records[0]['prospect_id'])) ? $all_records[0]['prospect_id'] : 0; ?>, 'company_id': <?php echo $all_records[0]['company_id']; ?>},
                type: "POST",
                dataType: "json",
                success: function (d)
                {
                    if (d.status == 1)
                    {
                        $('#email_id_account').val('');
                        $('#email_id_account').addClass('parsley-error');
                        $('#email_err').removeClass('hidden');
                    }
                    else
                    {
                        $('#email_id_account').removeClass('parsley-error')
                        $('#email_err').addClass('hidden');
                    }
                }
            });
        }

    }
</script>

<?php unset($_SESSION['setting_current_tab']); ?>