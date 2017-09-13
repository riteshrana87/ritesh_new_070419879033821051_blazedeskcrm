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
                <li><a href="<?php echo base_url() . 'CrmCompany'; ?>"><?= lang('company') ?></a></li>
                <li class="active"><?php echo htmlentities(stripslashes($all_records[0]['company_name'])); ?></li>
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
                        <h3><b><?php echo $all_records[0]['company_name']; ?></b><span class="pull-right"><span class="form-group">

                                </span></span></h3>
                    </div>	
                    <div class="clr"></div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="<?= $this->lang->line('account_name') ?> *"  value="<?php
                        if (!empty($all_records[0]['company_name'])) {
                            echo htmlentities(stripslashes($all_records[0]['company_name']));
                        }
                        ?>" readonly>
                    </div>
                    <div class="clr"></div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="<?= $this->lang->line('address1') ?>"  value="<?php
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

                   <div class="row"> <div class="col-xs-12 col-sm-6 col-md-6 no-left-pad">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('postal_code') ?>" value="<?php
                            if (!empty($all_records[0]['postal_code'])) {
                                echo $all_records[0]['postal_code'];
                            }
                            ?>" readonly>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 no-right-pad">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('city') ?>" value="<?php
                            if (!empty($all_records[0]['city'])) {
                                echo htmlentities(stripslashes($all_records[0]['city']));
                            }
                            ?>" readonly>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-xs-12 col-sm-6 col-md-6 no-left-pad">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('state') ?>" value="<?php
                            if (!empty($all_records[0]['state'])) {
                                echo htmlentities($all_records[0]['state']);
                            }
                            ?>" readonly>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 no-right-pad" >
                        <div class="form-group">
                            <select class="form-control chosen-select" tabindex="8" data-parsley-errors-container="#country-errors" disabled="true">
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
                    <div class="clr"></div></div>
                    <div class="row">
                        
                            <div class="col-xs-12 col-sm-6 col-md-6 no-right-pad form-group" >

                                <input type="text" class="form-control" placeholder="<?= $this->lang->line('number') ?>" value="<?php
                                if (!empty($all_records[0]['phone_number'])) {
                                    echo $all_records[0]['phone_number'];
                                }
                                ?>" readonly>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 no-right-pad form-group" >
                                <input type="text" class="form-control" placeholder="<?= $this->lang->line('branche') ?>" value="<?php
                                if (!empty($all_records[0]['branch_name'])) {
                                    echo htmlentities(stripslashes($all_records[0]['branch_name']));
                                }
                                ?>" readonly>
                            </div>
                            <div class="clr"></div>
                       
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="col-xs-12 col-md-6 pad-tb6">
                    <div class="col-xs-12 col-md-4 no-left-pad">
                    </div>
                   <div class="row"> <div class="col-xs-12 col-md-12  no-right-pad">

                       <div class="row"> <div class="col-xs-12 col-md-6 no-left-pad form-group">
                            <label style="padding-top:10px"><?= $this->lang->line('creation_date') ?></label>
                        </div>
                        <div class="col-xs-6 col-md-6  no-right-pad form-group">
                            <input type="text" class="form-control creation_date" placeholder="<?= $this->lang->line('creation_date') ?>" onkeydown="return false" value="<?php
                            if (!empty($all_records[0]['creation_date']) && $all_records[0]['creation_date'] != '0000-00-00') {
                                echo configDateTime($all_records[0]['creation_date']);
                            } else {
                                echo date("m/d/Y");
                            }
                            ?>" readonly>
                        </div>
                        <div class="clr"></div></div>
                       <div class="row"> <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <select class="form-control chosen-select" tabindex="9">
                                    <option value="">
                                    <?= $this->lang->line('select_prospect_owner') ?>
                                    </option>
                                    <?php if (isset($prospect_owner) && count($prospect_owner) > 0) { ?>
                                        <?php foreach ($prospect_owner as $prospect) {
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
                            </div></div></div>
                    </div>
                    <div class="clr"></div></div>
                    <div class="form-group"><label><?= $this->lang->line('contacts') ?></label> </div>
                    <div class="clr"> </div>

                    <!-- contact data of company show here -->
                    <div id="Contacts">

                    </div>

                    <div id="deletedContactsDiv"></div>
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
                                                    <span class="pull-right">
                                                        <a href="<?= base_url('Opportunity/viewdata/' . $row['prospect_id']) ?>" class="edit_contact" title="<?php echo lang('view'); ?>"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;
            <?php if (checkPermission('Opportunity', 'edit')) { ?><a  data-href="<?php echo base_url('Opportunity/editdata/' . $row['prospect_id']); ?>" title="<?php echo lang('edit'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="edit_contact" id="edit_opportunity" ><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>&nbsp;&nbsp;&nbsp;
                                                    <?php if (checkPermission('Opportunity', 'delete')) { ?><a data-href="javascript:;" title="<?php echo lang('delete'); ?>" onclick="delete_request('<?php echo $row['prospect_id']; ?>', '<?php echo base_url('Opportunity/viewdata/' . $row['prospect_id']); ?>');" class="edit_contact" ><i class="fa fa-remove fa-x redcol" ></i></a><?php } ?>
                                                        <a href="javascript:;"><i class="fa fa-gear bluecol"></i></a>
                                                    </span> 
                                                    <span class="clr"></span>
            <?php echo $row['prospect_auto_id']; ?>
                                                    Worth $<?php echo ($row['value'] != '') ? $row['value'] : 0; ?>
                                                    
                                                    <input type="text" class="form-control" value="<?php echo configDateTime($row['creation_date']); ?>" disabled/>
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
                                                    <span class="pull-right">
                                                        <a href="<?= base_url('Opportunity/viewdata/' . $row['prospect_id']) ?>" class="edit_contact" title="<?php echo lang('view'); ?>"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;
            <?php if (checkPermission('Opportunity', 'edit')) { ?><a  data-href="<?php echo base_url('Opportunity/editdata/' . $row['prospect_id']); ?>" title="<?php echo lang('edit'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="edit_contact" id="edit_opportunity" ><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>&nbsp;&nbsp;&nbsp;
                                                    <?php if (checkPermission('Opportunity', 'delete')) { ?><a data-href="javascript:;" title="<?php echo lang('delete'); ?>" onclick="delete_request('<?php echo $row['prospect_id']; ?>', '<?php echo base_url('Opportunity/viewdata/' . $row['prospect_id']); ?>');" class="edit_contact" ><i class="fa fa-remove fa-x redcol" ></i></a><?php } ?>
                                                        <a href="javascript:;"><i class="fa fa-gear bluecol"></i></a>
                                                    </span> 
                                                    <span class="clr"></span>
            <?php echo $row['prospect_auto_id']; ?>
                                                    Worth $<?php echo $row['value']; ?>
                                                    
                                                    <input type="text" class="form-control" value="<?php echo configDateTime($row['creation_date']); ?>" disabled/>
                                                </div>
                                                <div class="clr"></div></div>
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
                                                    <span class="pull-right">
                                                        <a href="<?= base_url('Opportunity/viewdata/' . $row['prospect_id']) ?>" class="edit_contact" title="<?php echo lang('view'); ?>"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;
            <?php if (checkPermission('Opportunity', 'edit')) { ?><a  data-href="<?php echo base_url('Opportunity/editdata/' . $row['prospect_id']); ?>" title="<?php echo lang('edit'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="edit_contact" id="edit_opportunity" ><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>&nbsp;&nbsp;&nbsp;
                                                    <?php if (checkPermission('Opportunity', 'delete')) { ?><a data-href="javascript:;" title="<?php echo lang('delete'); ?>" onclick="delete_request('<?php echo $row['prospect_id']; ?>', '<?php echo base_url('Opportunity/viewdata/' . $row['prospect_id']); ?>');" class="edit_contact" ><i class="fa fa-remove fa-x redcol" ></i></a><?php } ?>
                                                        <a href="javascript:;"><i class="fa fa-gear bluecol"></i></a>
                                                    </span> 
                                                    <span class="clr"></span>
            <?php echo $row['prospect_auto_id']; ?>
                                                    Worth $<?php echo $row['value']; ?>
                                                    
                                                    <input type="text" class="form-control" value="<?php echo configDateTime($row['creation_date']); ?>" disabled/>
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
                                                    <span class="pull-right">
                                                        <a href="<?= base_url('Opportunity/viewdata/' . $row['prospect_id']) ?>" class="edit_contact" title="<?php echo lang('view'); ?>"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;
            <?php if (checkPermission('Opportunity', 'edit')) { ?><a  data-href="<?php echo base_url('Opportunity/editdata/' . $row['prospect_id']); ?>" data-toggle="ajaxModal" title="<?php echo lang('edit'); ?>" aria-hidden="true" data-refresh="true" class="edit_contact" id="edit_opportunity" ><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>&nbsp;&nbsp;&nbsp;
                                                    <?php if (checkPermission('Opportunity', 'delete')) { ?><a data-href="javascript:;" title="<?php echo lang('delete'); ?>" onclick="delete_request('<?php echo $row['prospect_id']; ?>', '<?php echo base_url('Opportunity/viewdata/' . $row['prospect_id']); ?>');" class="edit_contact" ><i class="fa fa-remove fa-x redcol" ></i></a><?php } ?>
                                                        <a href="javascript:;"><i class="fa fa-gear bluecol"></i></a>
                                                    </span> 
                                                    <span class="clr"></span>
            <?php echo $row['prospect_auto_id']; ?>
                                                    Worth $<?php echo $row['value']; ?>
                                                    
                                                    <input type="text" class="form-control" value="<?php echo configDateTime($row['creation_date']); ?>" disabled/>
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

                </div>
            </div>

        </div>

        <div class="clr"></div>
    </div>
</div>


<script>


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
        var delete_meg = "<?php echo $this->lang->line('CONFIRM_DELETE_CONTACT'); ?>";
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


    function data_search()
    {
        var searchtext = $('#searchtext').val();
        getContactData(searchtext);
        getCommunicationData(searchtext);
        getEstimatesData(searchtext);
        viewSupportData(searchtext);
        viewProjectsData(searchtext);

    }

    function reset_data()
    {
        $('#searchtext').val('');
        getContactData('clearData');
        getCommunicationData('clearData');
        getEstimatesData('clearData');
        viewSupportData('clearData');
        viewProjectsData('clearData');

    }
    $(document).ready(function ()
    {
        getContactData('');
        getCommunicationData('');
        getEstimatesData('');
        viewSupportData('');
        viewProjectsData('');

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

                            $('#' + id).data('data-type', type);
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

</script>

<?php unset($_SESSION['setting_current_tab']); ?>