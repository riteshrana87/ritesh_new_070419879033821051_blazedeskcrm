<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$setting_current_tab = $this->session->userdata('setting_current_tab');
if (!isset($setting_current_tab) || $setting_current_tab == '') {
    $sess_array = array('setting_current_tab' => 'Contacts');
    $this->session->set_userdata($sess_array);
}
$formAction = 'insertdata';
$path = $account_view . '/' . $formAction;
?>

<!-- Example row of columns -->
<div class="row">
    <div class="col-md-6 col-lg-6 col-sm-7 ">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url(); ?>"><?= lang('crm') ?></a></li>
            <li><a href="<?php echo base_url() . 'SalesOverview'; ?>"><?= lang('sales_overview') ?></a></li>
            <li><a href="<?php echo base_url() . 'Account'; ?>"><?= lang('accounts') ?></a></li>
            <li><a href="<?php echo base_url() . 'Account/lostClient'; ?>"><?= lang('LOST_CLIENT') ?></a></li>
            <li class="active"><?php echo htmlentities(stripslashes($all_records[0]['prospect_name'])); ?></li>
        </ul>
    </div>

    <!-- Search: Start -->
    <div class="col-xs-12 col-md-3  col-sm-5 pull-right text-right col-md-offset-3">
        <div class="row">
            <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#"><i class="fa fa-gear fa-2x"></i></a> </div>
            <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                <div class="navbar-form navbar-left pull-right" id="searchForm">
                    <div class="input-group">
                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>">
                        <input type="text" name="searchtext" id="searchtext"  class="form-control" placeholder="<?= $this->lang->line('EST_LISTING_SEARCH_FOR') ?>" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
                        <span class="input-group-btn">
                            <button onclick="data_search()" title="<?= $this->lang->line('search') ?>" class="btn btn-default" type="button"><i class="fa fa-search fa-x"></i></button>&nbsp;

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
    <?php if ($this->session->flashdata('message')) { ?>
        <div class='alert alert-success text-center'> <?php echo $this->session->flashdata('message'); ?></div>
    <?php } ?>
    <?php if ($this->session->flashdata('error')) { ?>
        <div class='alert alert-danger text-center'> <?php echo $this->session->flashdata('error'); ?></div>
    <?php } ?>
</div>
<div class="row">
    <div class="col-xs-12 col-md-9">
        <div class="whitebox pad-10">
            <div class="row">
                <div class="col-xs-12 col-md-4 col-sm-4">
                    <div class="row">
                        <div class="col-xs-4 col-md-4">
                            <div class="client-img">
                                <?php if (isset($all_records[0]['image']) && $all_records[0]['image'] != "") { ?>

                                    <?php
                                    if (isset($all_records[0]['image']) && $all_records[0]['image'] != "") {
                                        $img_alt = $all_records[0]['image'];
                                    } else {
                                        $img_alt = "No Image";
                                    }
                                    ?>

                                    <img src="<?php echo base_url('uploads/contact/' . $all_records[0]['image']); ?>" alt="<?php echo $img_alt; ?>" title="<?php echo $img_alt; ?>" class="img-responsive"/>
                                <?php } else { ?>
                                    <img src="<?php echo base_url('uploads/contact/noimage.jpg'); ?>" alt=""  class="img-responsive"/>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-xs-8 col-md-8 pad-l6">
                            <p class="contact-title1">
                                <?php if (isset($all_records[0]['prospect_name']) && $all_records[0]['prospect_name'] != "") { ?>
                                    <b><?php echo htmlentities(stripslashes($all_records[0]['prospect_name'])); ?></b><br/>
                                <?php } ?>
                                <?php if (isset($all_records[0]['job_title']) && $all_records[0]['job_title'] != "") { ?>
                                    <?php echo htmlentities(stripslashes($all_records[0]['job_title'])); ?>
                                <?php } ?>
                            </p>
                            <ul class="contact-list1">
                                    <?php if (isset($all_records[0]['phone_number']) && $all_records[0]['phone_number'] != "") { ?>
                                    <li> <i class="fa fa-phone bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo $all_records[0]['phone_number']; ?> 
                                        <?php
                                        if (isset($all_records[0]['phone_no2']) && $all_records[0]['phone_no2'] != "") {
                                            echo ',' . $all_records[0]['phone_no2'];
                                        }
                                        ?>
                                    </li>
                                    <?php } ?>
                                    <?php if (isset($all_records[0]['phone_no']) && $all_records[0]['phone_no'] != "") { ?>
                                    <li> <i class="fa fa-mobile bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo $all_records[0]['phone_no']; ?></li>
                                    <?php } ?>
                                <li> <i class="fa fa-envelope bluecol"></i>&nbsp;&nbsp;&nbsp;<?php if (!empty($all_records[0]['company_email']) && $all_records[0]['company_email'] != "") {
                                        echo $all_records[0]['company_email'];
                                    } ?> 
                                    <?php if (isset($all_records[0]['email_id']) && $all_records[0]['email_id'] != "") { ?>
                                        <?php echo $all_records[0]['email_id']; ?>
                                    <?php } ?>
                                </li>
                            </ul>
                        </div>
                        <div class="clr"></div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-5 col-sm-4">
                    <div class="gray-ver-line">
                        <div class="col-sm-4 col-xs-4"> <div class="company-img pad-tb6">
                                <?php if (isset($all_records[0]['logo_img']) && $all_records[0]['logo_img'] != "") { ?>

                                    <?php
                                    if (isset($all_records[0]['logo_img']) && $all_records[0]['logo_img'] != "") {
                                        $img_alt = $all_records[0]['logo_img'];
                                    } else {
                                        $img_alt = "No Image";
                                    }
                                    ?>

                                    <img src="<?php echo base_url('uploads/company/' . $all_records[0]['logo_img']); ?>" alt="<?php echo $img_alt; ?>" title="<?php echo $img_alt; ?>" class="img-responsive" />
                                <?php } else { ?>
                                    <img src="<?php echo base_url('uploads/contact/noimage.jpg'); ?>" alt=""  class="img-responsive"/>
                                <?php } ?>	
                            </div>	</div>
                        <div class="col-sm-8 col-xs-8">
                            <ul class="contact-list2">
                                    <?php if (isset($all_records[0]['address1']) && $all_records[0]['address1'] != "") { ?>
                                    <li><?php echo htmlentities(stripslashes($all_records[0]['address1'])); ?></li>
                                    <?php } ?>
                                    <?php if (isset($all_records[0]['address2']) && $all_records[0]['address2'] != "") { ?>
                                    <li><?php echo htmlentities(stripslashes($all_records[0]['address2'])); ?></li>
                                    <?php } ?>
                                <li>
                                <?php
                                if (isset($all_records[0]['postal_code']) && $all_records[0]['postal_code'] != "") {
                                    echo $all_records[0]['postal_code'] . ',';
                                }
                                ?> 
                                <?php
                                if (isset($all_records[0]['city']) && $all_records[0]['city'] != "") {
                                    echo htmlentities(stripslashes($all_records[0]['city']));
                                }
                                ?> 
                                </li>
                                <?php if (isset($all_records[0]['country_name']) && $all_records[0]['country_name'] != "") { ?> 
                                                                    <li><?php echo $all_records[0]['country_name']; ?></li>	
                                <?php } ?>
                                <?php if (isset($all_records[0]['language_name']) && $all_records[0]['language_name'] != "") { ?> 
                                                                    <li>Language: <?php echo $all_records[0]['language_name']; ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="clr"></div>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="col-xs-12 col-md-3 col-sm-4">

                    <div class="text-right bd-text-left-resp mb15" id="contact_address"></div>
                    <input type="hidden" name="hdn_contact_addres" id="hdn_contact_addres" value="<?php echo $all_records[0]['address1'] . "," . $all_records[0]['address2'] . "," . $all_records[0]['city'] . "," . $all_records[0]['state'] . "," . $all_records[0]['country_name']; ?>"/>
                </div> 
                <div class="clr"></div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                                    <!--                <div class="border-box text-center">
                                                        <h4><b> <?= $this->lang->line('estimate_prospect_worth') ?>:<br>
                <?php if (!empty($estimate_prospect_worth[0]['value'])) {
                    echo $estimate_prospect_worth[0]['value'];
                } ?>
                                                           </b></h4></div>-->
                                </div>
                <!--                        <li class="form-group"><b> <?= $this->lang->line('creation_date') ?></b>
                                            <div>
                                               <p><?php
                if (!empty($all_records[0]['creation_date']) && $all_records[0]['creation_date'] != '0000-00-00') {
                    echo configDateTime($all_records[0]['creation_date']);
                }
                ?></p>

                                            </div>
                                        </li>-->
                <div class="clr"></div>
            </div>   
            <div class="row">
                <div class="pad-6">

                      <div class="col-xs-6 col-md-3">
                                <?php
                                if ($all_records[0]['fb'] != '' || $all_records[0]['linkedin'] != '' || $all_records[0]['twitter'] != '') {
                                    ?>
                          
                                <ul class="social-list2 clearfix">
                                    <?php
                                    if (isset($all_records[0]['fb']) && $all_records[0]['fb'] != '') {
                                        ?>
                                        <li> <a href="<?php echo $all_records[0]['fb']; ?>" target="_blank"><img src="<?php echo base_url('uploads'); ?>/images/fb-icon.png" alt="Facebook Url"/></a></li>
                                    <?php } ?>

                                    <?php
                                    if (isset($all_records[0]['linkedin']) && $all_records[0]['linkedin'] != '') {
                                        ?>
                                        <li><a href="<?php echo $all_records[0]['linkedin']; ?>" target="_blank"><img src="<?php echo base_url('uploads'); ?>/images/linkdin-icon.png" alt="Linkedin Url"/></a></li>
                                    <?php } ?>

                                    <?php
                                    if (isset($all_records[0]['twitter']) && $all_records[0]['twitter'] != '') {
                                        ?>
                                        <li><a href="<?php echo $all_records[0]['twitter']; ?>" target="_blank"><img src="<?php echo base_url('uploads'); ?>/images/twitter-icon1.png" alt=""/></a></li>
                                    <?php } ?>
                                </ul>
                           

<?php }
?>
                           </div>
                        <div class="col-xs-12 col-md-9"> 
<?php if (checkPermission('Account', 'edit')) { ?><a class="btn btn-blue" data-href="<?php echo base_url() . "Account/send_email_view/" . $all_records[0]['prospect_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('SEND_EMAIL'); ?>"><i class="fa fa-envelope"></i>&nbsp;&nbsp;<span><?php echo lang('SEND_EMAIL'); ?></span></a><?php } ?>&nbsp;&nbsp;&nbsp;
<?php if (checkPermission('Contact', 'edit')) { ?><a class="btn btn-blue" data-href="<?php echo base_url() . "Contact/scheduleMeeting/" . $all_records[0]['prospect_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('SCHEDULE_MEETING'); ?>"><i class="fa fa-calendar"></i>&nbsp;&nbsp;<span><?php echo lang('SCHEDULE_MEETING'); ?></span></a><?php } ?>&nbsp;&nbsp;&nbsp;
<?php if (checkPermission('Account', 'edit')) { ?><button type="submit" class="btn btn-blue"><i class="fa fa-map-marker"></i><span>&nbsp;&nbsp;<?php echo lang('NAVIGATE_CONTACT'); ?></span></button><?php } ?>
                        </div>
                        <div class="clr"></div>
                   
                    
                </div>
               
            </div>

            <div class="clr"></div>


        </div>
        <div class="clr"></div>
        <br/>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="<?php
            if ($this->session->userdata('setting_current_tab') == 'Contacts') {
                echo "active";
            }
            ?>"><a href="#Contacts" aria-controls="Contacts" role="tab" data-toggle="tab"><?php echo lang('contacts'); ?></a></li>
            <li role="presentation" class="<?php
            if ($this->session->userdata('setting_current_tab') == 'Notes') {
                echo "active";
            }
            ?>"><a href="#Notes" aria-controls="Notes" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_NOTES'); ?></a></li>
            <li role="presentation" class="<?php
            if ($this->session->userdata('setting_current_tab') == 'Task') {
                echo "active";
            }
            ?>"><a href="#Tasks" aria-controls="Tasks" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_TASKS'); ?></a></li>
            <li role="presentation" class="<?php
            if ($this->session->userdata('setting_current_tab') == 'Deals') {
                echo "active";
            }
            ?>"><a href="#Deals" aria-controls="Deals" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_DEAL'); ?></a></li>
            <li role="presentation" class="<?php
                 if ($this->session->userdata('setting_current_tab') == 'Campaign') {
                     echo "active";
                 }
                 ?>"><a href="#Campaign" aria-controls="Campaign" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_CAMPAIGN'); ?></a></li>
            <li role="presentation" class="<?php
                 if ($this->session->userdata('setting_current_tab') == 'Documents') {
                     echo "active";
                 }
                 ?>"><a href="#Documents" aria-controls="Documents" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_DOCUMENTS'); ?></a></li>
            <li role="presentation" class="<?php
                 if ($this->session->userdata('setting_current_tab') == 'Cases') {
                     echo "active";
                 }
                 ?>"><a href="#Cases" aria-controls="Cases" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_CASES'); ?></a></li>
<!--            <li role="presentation"><a href="#Communication" aria-controls="Communication" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_COMMUNICATION'); ?></a></li>-->
            <li role="presentation" class="<?php
                 if ($this->session->userdata('setting_current_tab') == 'Events') {
                     echo "active";
                 }
                 ?>"><a href="#Events" aria-controls="Events" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_EVENTS'); ?></a></li>
<!--            <li role="presentation"><a href="#Opportunities" aria-controls="Opportunities" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_OPPORTUNITIES'); ?></a></li>-->

<!--  <li role="presentation"><a href="#Campaigns" aria-controls="Campaigns" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_CAMPAIGNS'); ?></a></li>
            -->
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane <?php
                 if ($this->session->userdata('setting_current_tab') == 'Contacts') {
                     echo "active";
                 }
                 ?>" id="Contacts">
                <!-- Contact data has been placed by ajax call  -->
            </div>
            <div role="tabpanel" class="tab-pane <?php
                 if ($this->session->userdata('setting_current_tab') == 'Notes') {
                     echo "active";
                 }
                 ?>" id="Notes">
                <!-- Note data has been placed by ajax call  -->
            </div>
            <div role="tabpanel" class="tab-pane <?php if ($this->session->userdata('setting_current_tab') == 'Task') {
                     echo "active";
                 } ?>" id="Tasks">
                <!-- Tasks data has been placed by ajax call  -->
            </div>

            <div role="tabpanel" class="tab-pane <?php
            if ($this->session->userdata('setting_current_tab') == 'Deals') {
                echo "active";
            }
                 ?>" id="Deals">
                <!-- Deals data has been placed by ajax call  -->
            </div>

            <div role="tabpanel" class="tab-pane <?php
            if ($this->session->userdata('setting_current_tab') == 'Campaign') {
                echo "active";
            }
                 ?>" id="Campaign">
                <!-- Campaign data has been placed by ajax call  -->
            </div>

            <div role="tabpanel" class="tab-pane <?php
            if ($this->session->userdata('setting_current_tab') == 'Cases') {
                echo "active";
            }
                 ?>" id="Cases">
                <!-- Case (Project Incident) data has been placed by ajax call  -->
            </div>

            <div role="tabpanel" class="tab-pane <?php
            if ($this->session->userdata('setting_current_tab') == 'Documents') {
                echo "active";
            }
                 ?>" id="Documents">
                <!-- Attach File data has been placed by ajax call  -->
            </div>

            <div role="tabpanel" class="tab-pane <?php if ($this->session->userdata('setting_current_tab') == 'Events') {
                echo "active";
            } ?>" id="Events">


            </div>
            <!--    <div role="tabpanel" class="tab-pane" id="Opportunities">
                            <div class="whitebox">
<?php echo Modules::run('Sidebar/opportunitiesTable'); ?>
                    <div class="clr"></div>
                  </div>
                     
                     
                    </div>-->
            <div role="tabpanel" class="tab-pane" id="Cases">

                <div class="whitebox">
                    <div class="table table-responsive">
                        <table id="datatable6" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Business Cases</th>
                                    <th>Case Subject</th>
                                    <th>Responsible</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Company's name proof of concept</td>
                                    <td>Telephone solution implement</td>
                                    <td>John Doe</td>
                                    <td>12-31-2016</td>
                                    <td>In Progress</td>
                                    <td><a href="#"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;<a href="#"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp; <a href="#"><i class="fa fa-remove redcol"></i></a></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="clr"></div>
                </div>


            </div>


        </div>

    </div>


    <div class="col-xs-12 col-md-3">

        <div class="whitebox pad-10">

            <form>
                <div class="form-group">
                    <select class="form-control">
                        <option><?= lang('prospect_owner') ?></option>
<?php if (isset($prospect_owner) && count($prospect_owner) > 0) { ?>
    <?php
    foreach ($prospect_owner as $prospect) {
        if ($all_records[0]['prospect_owner_id'] == $prospect['login_id']) {
            ?>
                                    <option value="<?php echo $prospect['login_id']; ?>" <?php
                    if (!empty($all_records[0]['prospect_owner_id']) && $all_records[0]['prospect_owner_id'] == $prospect['login_id']) {
                        echo 'selected';
                    }
            ?>><?php echo ucfirst($prospect['firstname']) . " " . ucfirst($prospect['lastname']); ?></option>
        <?php }
    } ?>
<?php } ?>
                    </select>
                </div>
                <div class="pad-tb6">
                <?php if (checkPermission('Account', 'edit')) { ?><a data-href="<?php echo base_url('Opportunity/editdata/' . $all_records[0]['prospect_id']); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true" class="edit_opportunity" id="edit_opportunity"><i class="fa fa-pencil fa-x bluecol"></i>&nbsp;&nbsp;&nbsp;<?= lang('edit') ?></a><?php } ?>
                </div>
                <div class="grayline-1"></div>
                <div>
                <?php if (checkPermission('Contact', 'add')) { ?><a data-href="<?= base_url() ?>Contact/addNote/<?= $all_records[0]['prospect_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('ADD_NOTES'); ?>"><i class="fa fa-comment bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('ADD_NOTES'); ?></a><?php } ?>
                </div>
                <div>
                    <!--  //base_url() ?>Contact/viewTask/ //$all_records[0]['contact_id'] ?>-->
<?php if (checkPermission('Task', 'add')) { ?><a data-href="<?php echo base_url('Task/add') . "/" . $all_records[0]['prospect_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('ADD_TASKS'); ?>"><i class="fa fa-file-text bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('ADD_TASKS'); ?></a><?php } ?>
                </div>
                <div>
<?php if (checkPermission('Opportunity', 'add')) { ?> <a data-href="<?= base_url() ?>Opportunity/add/<?= $all_records[0]['prospect_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('ADD_DEAL'); ?>"><i class="fa fa-tag bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('ADD_DEAL'); ?></a>
                    </div>
                    <div class="grayline-1"></div>
<?php }
?>


                <div class="pad-tb6">
<?php if (checkPermission('Contact', 'add')) { ?>
                        <a data-href="<?php echo base_url() . 'Contact/view_add_campaign/' . $all_records[0]['prospect_id'] ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('ADD_TO_CAMPAIGN'); ?>"><i class="fa fa-sitemap bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('ADD_TO_CAMPAIGN'); ?> </a>
<?php } ?>

                </div>
                <div class="grayline-1"></div>
                <div class="pad-tb6">
<?php if (checkPermission('Contact', 'add')) { ?>
                        <a data-href="<?php echo base_url() . 'Contact/AddCases/' . $all_records[0]['prospect_id']; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('add_cases'); ?>"><i class="fa fa-sitemap bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('add_cases'); ?> </a>
<?php } ?>

                </div>
                <div class="grayline-1"></div>
                <div>
<?php
if (checkPermission('Account', 'delete')) {
    ?>
                        <div>
                            <a onclick="delete_account('<?php echo $all_records[0]['prospect_id']; ?>', '<?php if (!empty($_SERVER['HTTP_REFERER'])) echo $_SERVER['HTTP_REFERER']; ?>');" title="<?php echo lang('CONTACT_DELETE'); ?>"><i class="fa fa-remove bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('CONTACT_DELETE'); ?> </a>
                        </div>
<?php } ?>
                </div>

                <div class="clr"></div>

<?php
if (checkPermission('Contact', 'add')) {
    ?>
                    <div class="pad-10 text-center">
                        <a data-href="<?= base_url() ?>Contact/viewLeadFile/<?= $all_records[0]['prospect_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('ADD_FILE_TO_CONTACT'); ?>" class="width-100 btn small-white-btn2"><?php echo lang('ADD_FILE_TO_CONTACT'); ?></a>
                    </div>
<?php } ?>
                <div class="clr"></div>
            </form>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>

</div>
</div>
<!-- /container --> 


<div class="modal fade bs-email-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="pad-10">
                <h4>
                    <b>Email Client </b>
                </h4>
                <div class="col-xs-12 col-md-6 no-left-pad">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Example Lead/Client/Prospect" />
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 no-right-pad">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="P-### (auto generated) " />
                    </div>
                </div>
                <div class="clr"></div>
                <div class="col-xs-12 col-md-6 no-left-pad">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Company Name" />
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 no-right-pad">
                    <div class="form-group">
                        <select class="form-control">
                            <option>Responsible employee</option>
                        </select>
                    </div>
                </div>
                <div class="clr"></div>
                <div class="col-xs-12 col-md-6 no-left-pad">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Select Prospect Contact to receive email" />
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 no-right-pad">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Send email to employee?" />
                    </div>
                </div>
                <div class="clr"> </div>
                <div class="col-xs-12 col-md-6 no-left-pad">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Subject" />
                    </div> 
                </div>
                <div class="col-xs-1 col-md-1 no-right-pad">
                    or
                </div>
                <div class="col-xs-11 col-md-5 no-right-pad">
                    <div class="form-group">
                        <select class="form-control">
                            <option>Select Email Template</option>
                        </select>
                    </div>
                </div>
                <div class="clr"> </div>

                <div class="col-xs-12 col-md-12 no-left-pad">
                    <div class="form-group">
                        <textarea class="form-control"></textarea>
                    </div>
                </div>
                <div class="clr"> </div>
                <div class="col-xs-6 col-md-3 no-right-pad">
                    <div class="text-center">
                        <button class="btn btn-primary col-xs-12 col-md-12">Flag an interest</button>
                    </div>
                    <div class="clr"> </div><br/>
                    <div class="text-center">
                        <button class="btn btn-primary col-xs-12 col-md-12">Request Read notification</button>
                    </div>
                    <div class="clr"> </div><br/>
                    <div class="text-center">
                        <button class="btn btn-primary col-xs-12 col-md-12">Send Meeting Date	</button>
                    </div>
                    <div class="clr"> </div>


                </div>
                <div class="col-xs-6 col-md-5">
                    <div class="form-group">
                        <textarea class="form-control"></textarea>
                    </div> 
                    <div class="clr"> </div>


                </div>
                <div class="col-xs-12 col-md-4 no-right-pad">
                    <div class="form-group">
                        <textarea class="form-control"></textarea>
                    </div> 
                </div>
                <div class="clr"> </div><br/>
                <div class="text-center">
                    <button class="btn btn-primary">Create Prospect</button>
                </div>
                <div class="clr"> </div><br/>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="//www.google.fr/jsapi"></script>

<script type="text/javascript" src="<?php echo base_url() . "uploads/custom/js/jquery.googlemap.js" ?>"></script>
<script>

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
                                var url_contact = '<?php echo base_url() . "Contact/viewContactData/" ?>';
                                $.ajax({
                                    type: "POST",
                                    url: url_contact,
                                    data: {'getContactData': 'Contacts', 'searchtext': searchtext, 'contact_related_id': '<?php echo $all_records[0]['prospect_id']; ?>', 'contact_status': '2'},
                                    success: function (data)
                                    {
                                        $('#Contacts').html(data);
                                    }
                                });
                            }
                            function getNoteData(searchtext)
                            {
                                var url_contact = '<?php echo base_url() . "Contact/viewNoteData/" ?>';
                                $.ajax({
                                    type: "POST",
                                    url: url_contact,
                                    data: {'getNoteData': 'Note', 'searchtext': searchtext, 'note_related_id': '<?php echo $all_records[0]['prospect_id']; ?>', 'note_status': '2'},
                                    success: function (data)
                                    {
                                        $('#Notes').html(data);
                                    }
                                });
                            }

                            function getTasksData(searchtext)
                            {
                                var url_contact = '<?php echo base_url() . "Contact/viewTaskData/" ?>';
                                $.ajax({
                                    type: "POST",
                                    url: url_contact,
                                    data: {'getTasksData': 'Tasks', 'searchtext': searchtext, 'task_related_id': '<?php echo $all_records[0]['prospect_id']; ?>', 'task_status': '2'},
                                    success: function (data)
                                    {
                                        $('#Tasks').html(data);
                                    }
                                });
                            }

                            function getDealsData(searchtext)
                            {
                                var url_contact = '<?php echo base_url() . "Contact/viewDealsData/" ?>';
                                $.ajax({
                                    type: "POST",
                                    url: url_contact,
                                    data: {'getDealsData': 'Deals', 'searchtext': searchtext, 'deals_related_id': '<?php echo $all_records[0]['prospect_id']; ?>', 'deal_status': '2'},
                                    success: function (data)
                                    {
                                        $('#Deals').html(data);
                                    }
                                });
                            }

                            function getCampaignData(searchtext)
                            {
                                var url_contact = '<?php echo base_url() . "Contact/viewCampaignData/" ?>';
                                $.ajax({
                                    type: "POST",
                                    url: url_contact,
                                    data: {'getDealsData': 'Campaign', 'searchtext': searchtext, 'campaign_related_id': '<?php echo $all_records[0]['prospect_id']; ?>', 'campaign_status': '2'},
                                    success: function (data)
                                    {
                                        $('#Campaign').html(data);
                                    }
                                });
                            }

                            function getLeadFile(searchtext)
                            {
                                var url_contact = '<?php echo base_url() . "Contact/getLeadFile/" ?>';
                                $.ajax({
                                    type: "POST",
                                    url: url_contact,
                                    data: {'getDealsData': 'Documents', 'searchtext': searchtext, 'file_related_id': '<?php echo $all_records[0]['prospect_id']; ?>', 'file_status': '2'},
                                    success: function (data)
                                    {
                                        $('#Documents').html(data);
                                    }
                                });
                            }

                            function getContactCases(searchtext)
                            {
                                var url_contact = '<?php echo base_url() . "Contact/getContactCases/" ?>';
                                $.ajax({
                                    type: "POST",
                                    url: url_contact,
                                    data: {'getDealsData': 'Campaign', 'searchtext': searchtext, 'cases_related_id': '<?php echo $all_records[0]['prospect_id']; ?>', 'cases_status': '2'},
                                    success: function (data)
                                    {
                                        $('#Cases').html(data);
                                    }
                                });
                            }

                            function getContactEvents(searchtext)
                            {
                                var url_contact = '<?php echo base_url() . "Contact/getContactEvents/" ?>';
                                $.ajax({
                                    type: "POST",
                                    url: url_contact,
                                    data: {'getDealsData': 'Campaign', 'searchtext': searchtext, 'event_related_id': '<?php echo $all_records[0]['prospect_id']; ?>', 'cases_status': '2'},
                                    success: function (data)
                                    {
                                        $('#Events').html(data);
                                    }
                                });
                            }
                            function data_search()
                            {
                                var searchtext = $('#searchtext').val();
                                getContactData(searchtext);
                                getNoteData(searchtext);
                                getTasksData(searchtext);
                                getDealsData(searchtext);
                                getCampaignData(searchtext);
                                getContactCases(searchtext);
                                getLeadFile(searchtext);
                                getContactEvents(searchtext);
                            }

                            function reset_data()
                            {
                                $('#searchtext').val('');
                                getContactData('clearData');
                                getNoteData('clearData');
                                getTasksData('clearData');
                                getDealsData('clearData');
                                getCampaignData('clearData');
                                getLeadFile('clearData');
                                getContactCases('clearData');
                                getContactEvents('clearData');
                            }
                            $(document).ready(function ()
                            {
                                getContactData('');
                                getNoteData('');
                                getTasksData('');
                                getDealsData('');
                                getCampaignData('');
                                getLeadFile('');
                                getContactCases('');
                                getContactEvents('');
                                /* Start for note */
                                $("body").delegate("#table_note ul.tsc_pagination a", "click", function () {
                                    var href = $(this).attr('href');

                                    $.ajax({
                                        type: "GET",
                                        url: href,
                                        data: {},
                                        success: function (response)
                                        {
                                            $("#Notes").empty();
                                            $("#Notes").html(response);

                                            return false;
                                        }
                                    });
                                    return false;
                                });

                                $("body").delegate("#table_note th.sortTask a", "click", function () {
                                    var href = $(this).attr('href');

                                    $.ajax({
                                        type: "GET",
                                        url: href,
                                        data: {},
                                        success: function (response)
                                        {
                                            $("#Notes").empty();
                                            $("#Notes").html(response);

                                            return false;
                                        }
                                    });
                                    return false;
                                });

                                /* End for note */

                                /* Start for Tasks */
                                $("body").delegate("#table_tasks ul.tsc_pagination a", "click", function () {
                                    var href = $(this).attr('href');

                                    $.ajax({
                                        type: "GET",
                                        url: href,
                                        data: {},
                                        success: function (response)
                                        {
                                            $("#Tasks").empty();
                                            $("#Tasks").html(response);

                                            return false;
                                        }
                                    });
                                    return false;
                                });

                                $("body").delegate("#table_tasks th.sortTask a", "click", function () {
                                    var href = $(this).attr('href');

                                    $.ajax({
                                        type: "GET",
                                        url: href,
                                        data: {},
                                        success: function (response)
                                        {
                                            $("#Tasks").empty();
                                            $("#Tasks").html(response);

                                            return false;
                                        }
                                    });
                                    return false;
                                });
                                /* End for Tasks */

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

                                /* Start for Campaign */
                                $("body").delegate("#table_campaign ul.tsc_pagination a", "click", function () {
                                    var href = $(this).attr('href');

                                    $.ajax({
                                        type: "GET",
                                        url: href,
                                        data: {},
                                        success: function (response)
                                        {
                                            $("#Campaign").empty();
                                            $("#Campaign").html(response);

                                            return false;
                                        }
                                    });
                                    return false;
                                });

                                $("body").delegate("#table_campaign th.sortTask a", "click", function () {
                                    var href = $(this).attr('href');

                                    $.ajax({
                                        type: "GET",
                                        url: href,
                                        data: {},
                                        success: function (response)
                                        {
                                            $("#Campaign").empty();
                                            $("#Campaign").html(response);

                                            return false;
                                        }
                                    });
                                    return false;
                                });
                                /* End for Campaign */

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
                                /* Start for Cases */
                                $("body").delegate("#table_cases ul.tsc_pagination a", "click", function () {
                                    var href = $(this).attr('href');

                                    $.ajax({
                                        type: "GET",
                                        url: href,
                                        data: {},
                                        success: function (response)
                                        {
                                            $("#Cases").empty();
                                            $("#Cases").html(response);

                                            return false;
                                        }
                                    });
                                    return false;
                                });

                                $("body").delegate("#table_cases th.sortTask a", "click", function () {
                                    var href = $(this).attr('href');

                                    $.ajax({
                                        type: "GET",
                                        url: href,
                                        data: {},
                                        success: function (response)
                                        {
                                            $("#Cases").empty();
                                            $("#Cases").html(response);

                                            return false;
                                        }
                                    });
                                    return false;
                                });
                                /* End for Cases */

                                /* Start for Events */
                                $("body").delegate("#table_events ul.tsc_pagination a", "click", function () {
                                    var href = $(this).attr('href');

                                    $.ajax({
                                        type: "GET",
                                        url: href,
                                        data: {},
                                        success: function (response)
                                        {
                                            $("#Events").empty();
                                            $("#Events").html(response);

                                            return false;
                                        }
                                    });
                                    return false;
                                });

                                $("body").delegate("#table_events th.sortTask a", "click", function () {
                                    var href = $(this).attr('href');

                                    $.ajax({
                                        type: "GET",
                                        url: href,
                                        data: {},
                                        success: function (response)
                                        {
                                            $("#Events").empty();
                                            $("#Events").html(response);

                                            return false;
                                        }
                                    });
                                    return false;
                                });
                                /* End for Events */
                                /* Start for Meeting */
                                $("body").delegate("#table_meeting ul.tsc_pagination a", "click", function () {
                                    var href = $(this).attr('href');

                                    $.ajax({
                                        type: "GET",
                                        url: href,
                                        data: {},
                                        success: function (response)
                                        {
                                            $("#Meeting").empty();
                                            $("#Meeting").html(response);

                                            return false;
                                        }
                                    });
                                    return false;
                                });

                                $("body").delegate("#table_meeting th.sortTask a", "click", function () {
                                    var href = $(this).attr('href');

                                    $.ajax({
                                        type: "GET",
                                        url: href,
                                        data: {},
                                        success: function (response)
                                        {
                                            $("#Meeting").empty();
                                            $("#Meeting").html(response);

                                            return false;
                                        }
                                    });
                                    return false;
                                });
                                /* End for Meeting */
                                /* Start for Contacts */
                                $("body").delegate("#table_contact ul.tsc_pagination a", "click", function () {
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

                                $("body").delegate("#table_contact th.sortTask a", "click", function () {
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

                            });

                            function delete_account(prospect_id) {

                                var delete_url = '<?php echo base_url(); ?>' + "Account/deletedata/?id=" + prospect_id;
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