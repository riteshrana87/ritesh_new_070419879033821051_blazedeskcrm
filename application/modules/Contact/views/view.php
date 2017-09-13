<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$setting_current_tab = $this->session->userdata('setting_current_tab');
if (!isset($setting_current_tab) || $setting_current_tab == '') {
    $sess_array = array('setting_current_tab' => 'Communication');
    $this->session->set_userdata($sess_array);
}

?>

<!-- Example row of columns -->
<div class="row">
    <div class="col-md-6 col-sm-6">
        <?php echo $this->breadcrumbs->show(); ?>
    </div>

    <!-- Search: Start -->
    <div class="col-xs-12 col-md-3 col-sm-6 pull-right text-right col-md-offset-3">
        <div class="row">
            <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
            <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                <div class="navbar-form navbar-left pull-right" id="searchForm">
                    <div class="input-group">
                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>">
                        <input type="text" name="searchtext" id="searchtext"  class="form-control" placeholder="<?php echo lang('EST_LISTING_SEARCH_FOR');?>" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
                        <span class="input-group-btn">
                            <button onclick="data_search()" title="<?php echo lang('search');?>" class="btn btn-default" type="button"><i class="fa fa-search fa-x"></i></button>&nbsp;

                            <button class="btn btn-default" title="<?php echo lang('reset');?>" onclick="reset_data()"><i class="fa fa-refresh fa-x"></i></button>
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
        <div class='alert alert-success text-center' id=""> <?php echo $this->session->flashdata('message'); ?></div>
    <?php } ?>
    <?php if ($this->session->flashdata('error')) { ?>
        <div class='alert alert-danger text-center'> <?php echo $this->session->flashdata('error'); ?></div>
    <?php } ?>
</div>
<div class="row">
    <div class="col-xs-12 col-md-9">
        <div class="whitebox">
            <div class="row pad-10">
                <div class="col-xs-12 col-md-12 col-sm-12 font-12em">
                    <div class="row mb15">
                        <div class="col-xs-12 col-md-4 col-sm-4">
                            <div class="row">
                                <div class="col-xs-4 col-md-4 col-sm-3">
                                    <div class="client-img">
                                       <?php if (isset($all_records[0]['image']) && $all_records[0]['image'] != "")
                                       { 
                                        $path= FCPATH.('uploads/contact').'/'.$all_records[0]['image'];
                                            if (file_exists($path)) { ?>
                                           
                                               <img src="<?php echo base_url('uploads/contact/' . $all_records[0]['image']); ?>"   class="img-responsive" />
                                        <?php     }
                                            else { ?>
                                               <img src="<?php echo base_url('uploads/contact/noimage.jpg'); ?>" alt="" class="img-responsive"/>
                                         <?php    }
                                        ?>

                                        
                                    <?php } else { ?>
                                        <img src="<?php echo base_url('uploads/contact/noimage.jpg'); ?>" alt="" class="img-responsive"/>
                                    <?php } ?>
                                    
                                    </div>
                                </div>
                               
                                <div class="col-xs-8 col-md-8 pad-l6">
                                    <p class="contact-title1"> <?php if (isset($all_records[0]['contact_name']) && $all_records[0]['contact_name'] != "") { ?>
                                    <b><?php echo $all_records[0]['contact_name']; ?></b><br/>
                                <?php } ?>
                                <?php if (isset($all_records[0]['job_title']) && $all_records[0]['job_title'] != "") { ?>
                                    <?php echo $all_records[0]['job_title']; ?>
                                <?php } ?></p>
                                    <ul class="contact-list1">
                                       <?php if (isset($all_records[0]['phone_number']) && $all_records[0]['phone_number'] != "") { ?>
                                        <li> <i class="fa fa-phone bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo $all_records[0]['phone_number']; ?> </li>
                                    <?php } ?>
                                    <?php if (isset($all_records[0]['mobile_number']) && $all_records[0]['mobile_number'] != "") { ?>
                                        <li> <i class="fa fa-mobile bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo $all_records[0]['mobile_number']; ?></li>
                                    <?php } ?>
                                    <?php if (isset($all_records[0]['email']) && $all_records[0]['email'] != "") { ?>
                                        <li> <i class="fa fa-envelope bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo $all_records[0]['email']; ?></li>
                                    <?php } ?>
                                    </ul>
                                </div>
                                <div class="clr"></div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4 col-sm-4">
                          <div class="row">  <div class="gray-ver-line">
                                <div class="company-img pad-tb6 col-xs-4 col-md-4 col-sm-3">
                                     <?php if (isset($all_records[0]['company_logo']) && $all_records[0]['company_logo'] != "")
                                       { 
                                        $path= FCPATH.('uploads/company').'/'.$all_records[0]['company_logo'];
                                            if (file_exists($path)) { ?>
                                           
                                               <img src="<?php echo base_url('uploads/company/' . $all_records[0]['company_logo']); ?>"  class="img-responsive" />
                                        <?php     }
                                            else { ?>
                                               <img src="<?php echo base_url('uploads/contact/noimage.jpg'); ?>" alt="" class="img-responsive"/>
                                         <?php    }
                                        ?>

                                        
                                    <?php } else { ?>
                                        <img src="<?php echo base_url('uploads/contact/noimage.jpg'); ?>" alt="" class="img-responsive"/>
                                    <?php } ?>
                                    
                                </div>
                               <div class="col-xs-8 col-md-8 pad-l6"> <ul class="contact-list2">
                                     <?php if (isset($all_records[0]['address1']) && $all_records[0]['address1'] != "") { ?>
                                    <li><?php echo $all_records[0]['address1']; ?></li>
                                <?php } ?>
                                <?php if (isset($all_records[0]['address2']) && $all_records[0]['address2'] != "") { ?>
                                    <li><?php echo $all_records[0]['address2']; ?></li>
                                <?php } ?>
                                <li>
                                    <?php
                                    if (isset($all_records[0]['city']) && $all_records[0]['city'] != "") {
                                        echo $all_records[0]['city'] . ",&nbsp;";
                                    }
                                    ?> 
                                    <?php
                                    if (isset($all_records[0]['state']) && $all_records[0]['state'] != "") {
                                        echo $all_records[0]['state'] . ",&nbsp;";
                                    }
                                    ?> 
                                    <?php
                                    if (isset($all_records[0]['postal_code']) && $all_records[0]['postal_code'] != "") {
                                        echo $all_records[0]['postal_code'] . ',';
                                    }
                                    ?> 

                                </li>
                                <?php if (isset($all_records[0]['country_name']) && $all_records[0]['country_name'] != "") { ?> 
                                    <li><?php echo $all_records[0]['country_name']; ?></li>	
                                <?php } ?>
                                <?php if (isset($all_records[0]['language_name']) && $all_records[0]['language_name'] != "") { ?> 
                                    <li>Language: <?php
                                if ($all_records[0]['language_name'] == "1") {
                                    echo "English";
                                } else {
                                    echo "Spanish";
                                }
                                    ?>
                                    </li>
                                <?php } ?>
                                </ul></div>
                                <div class="clr"></div>
                            </div></div>
                            <div class="clr"></div>
                        </div>
                         <div class="col-xs-12 col-md-3 col-sm-3 no-right-pad">
                    <div class="text-right bd-text-left-resp mb15" id="contact_address"></div>
                    <input type="hidden" name="hdn_contact_addres" id="hdn_contact_addres" value="<?php echo $all_records[0]['address1'].",".$all_records[0]['address2'].",".$all_records[0]['city'].",".$all_records[0]['state'].",".$all_records[0]['country_name'];?>"/>
                </div>
                        <div class="clr"></div>
                    </div>
                    <div class="row">
                        <div class="pad-6">
                            
                                    <div class="col-xs-6 col-md-3">
                                        
                                     <?php
                                if($all_records[0]['fb'] != '' || $all_records[0]['linkdin'] != '' || $all_records[0]['twitter'] != '')
                                { ?>    
                                    <ul class="social-list2 clearfix">
                                        <?php
                                if (isset($all_records[0]['fb']) && $all_records[0]['fb'] != '') {
                                    ?>
                                    <li> <a href="<?php echo $all_records[0]['fb']; ?>" target="_blank"><img src="<?php echo base_url('uploads'); ?>/images/fb-icon.png" alt="Facebook Url"/></a></li>
                                <?php } ?>

                                <?php
                                if (isset($all_records[0]['linkdin']) && $all_records[0]['linkdin'] != '') {
                                    ?>
                                    <li><a href="<?php echo $all_records[0]['linkdin']; ?>" target="_blank"><img src="<?php echo base_url('uploads'); ?>/images/linkdin-icon.png" alt="Linkedin Url"/></a></li>
                                <?php } ?>

                                <?php
                                if (isset($all_records[0]['twitter']) && $all_records[0]['twitter'] != '') {
                                    ?>
                                    <li><a href="<?php echo $all_records[0]['twitter']; ?>" target="_blank"><img src="<?php echo base_url('uploads'); ?>/images/twitter-icon1.png" alt=""/></a></li>
                                <?php } ?>
                                    </ul>
                                        <?php  }
                                ?>
                                </div>
                            
                            
                            
                            
                            <div class="col-xs-12 col-md-9">
                                <?php if (checkPermission('Contact', 'add')) { ?>
                                <a class="btn btn-blue" data-href="<?php echo base_url() . "Contact/send_email_view/" . $all_records[0]['contact_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('SEND_EMAIL'); ?>"><i class="fa fa-envelope"></i>&nbsp;&nbsp;<span><?php echo lang('SEND_EMAIL'); ?></span></a>&nbsp;&nbsp;&nbsp;
                               <?php } ?>
                               <?php if (checkPermission('Contact', 'add')) { ?>
                               <a class="btn btn-blue" data-href="<?php echo base_url() . "Contact/scheduleMeeting/" . $all_records[0]['contact_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('SCHEDULE_MEETING'); ?>"><i class="fa fa-calendar"></i>&nbsp;&nbsp;<span><?php echo lang('SCHEDULE_MEETING'); ?></span></a>&nbsp;&nbsp;&nbsp;
                                <?php } ?>
                                 <?php if (checkPermission('Contact', 'view')) { ?>
                                <a class="btn btn-blue" id ="navigate_contact" data-href="<?php echo base_url() . "Contact/navigation/" . $all_records[0]['contact_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('NAVIGATE_CONTACT'); ?>"><i class="fa fa-map-marker"></i><span>&nbsp;&nbsp;<?php echo lang('NAVIGATE_CONTACT'); ?></span></a>&nbsp;&nbsp;&nbsp;
                                <?php } ?>
                            </div>
                            <div class="clr"></div>
                        </div>
                    </div>
                </div>
               
                <div class="clr"></div>
            </div>
        </div>
        <!--<div class="whitebox pad-10">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="row">
                        <div class="col-xs-4 col-md-4 col-sm-3">
                            <div class="client-img">
                                <?php if (isset($all_records[0]['image']) && $all_records[0]['image'] != "") { ?>

                                    <?php
                                    if (isset($all_records[0]['image']) && $all_records[0]['image'] != "") {
                                        $img_alt = $all_records[0]['image'];
                                    } else {
                                        $img_alt = "No Image";
                                    }
                                    ?>

                                    <img src="<?php echo base_url('uploads/contact/' . $all_records[0]['image']); ?>" alt="<?php echo $img_alt; ?>" title="<?php echo $img_alt; ?>" class="img-responsive" />
                                <?php } else { ?>
                                    <img src="<?php echo base_url('uploads/contact/noimage.jpg'); ?>" alt="" class="img-responsive"/>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-xs-8 col-md-8 col-sm-9 pad-l6">
                            <p class="contact-title1">
                                <?php if (isset($all_records[0]['contact_name']) && $all_records[0]['contact_name'] != "") { ?>
                                    <b><?php echo $all_records[0]['contact_name']; ?></b><br/>
                                <?php } ?>
                                <?php if (isset($all_records[0]['job_title']) && $all_records[0]['job_title'] != "") { ?>
                                    <?php echo $all_records[0]['job_title']; ?>
                                <?php } ?>
                            </p>
                            <ul class="contact-list1">
                                <?php if (isset($all_records[0]['phone_number']) && $all_records[0]['phone_number'] != "") { ?>
                                    <li> <i class="fa fa-phone bluecol"></i>&nbsp;&nbsp;&nbsp;+<?php echo $all_records[0]['phone_number']; ?> </li>
                                <?php } ?>
                                <?php if (isset($all_records[0]['mobile_number']) && $all_records[0]['mobile_number'] != "") { ?>
                                    <li> <i class="fa fa-mobile bluecol"></i>&nbsp;&nbsp;&nbsp;+<?php echo $all_records[0]['mobile_number']; ?></li>
                                <?php } ?>
                                <?php if (isset($all_records[0]['email']) && $all_records[0]['email'] != "") { ?>
                                    <li> <i class="fa fa-envelope bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo $all_records[0]['email']; ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="clr"></div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="gray-ver-line">
                        <div class="company-img pad-tb6 col-xs-4 col-md-4 col-sm-3">
                            <?php if (isset($all_records[0]['logo_img']) && $all_records[0]['logo_img'] != "") { ?>

                                <?php
                                if (isset($all_records[0]['logo_img']) && $all_records[0]['logo_img'] != "") {
                                    $img_alt = $all_records[0]['logo_img'];
                                } else {
                                    $img_alt = "No Image";
                                }
                                ?>

                                <img src="<?php echo base_url('uploads/contact/' . $all_records[0]['logo_img']); ?>" alt="<?php echo $img_alt; ?>" title="<?php echo $img_alt; ?>" class="img-responsive" />
                            <?php } else { ?>
                                <img src="<?php echo base_url('uploads/contact/noimage.jpg'); ?>" alt=""  class="img-responsive"/>
                            <?php } ?>
                        </div>	
                        <div class="col-xs-8 col-md-8 col-sm-9 pad-tb6"><ul class="contact-list2 ">
                                <?php if (isset($all_records[0]['address1']) && $all_records[0]['address1'] != "") { ?>
                                    <li><?php echo $all_records[0]['address1']; ?></li>
                                <?php } ?>
                                <?php if (isset($all_records[0]['address2']) && $all_records[0]['address2'] != "") { ?>
                                    <li><?php echo $all_records[0]['address2']; ?></li>
                                <?php } ?>
                                <li>
                                    <?php
                                    if (isset($all_records[0]['city']) && $all_records[0]['city'] != "") {
                                        echo $all_records[0]['city'] . ",&nbsp;";
                                    }
                                    ?> 
                                    <?php
                                    if (isset($all_records[0]['state']) && $all_records[0]['state'] != "") {
                                        echo $all_records[0]['state'] . ",&nbsp;";
                                    }
                                    ?> 
                                    <?php
                                    if (isset($all_records[0]['postal_code']) && $all_records[0]['postal_code'] != "") {
                                        echo $all_records[0]['postal_code'] . ',';
                                    }
                                    ?> 

                                </li>
                                <?php if (isset($all_records[0]['country_name']) && $all_records[0]['country_name'] != "") { ?> 
                                    <li><?php echo $all_records[0]['country_name']; ?></li>	
                                <?php } ?>
                                <?php if (isset($all_records[0]['language_name']) && $all_records[0]['language_name'] != "") { ?> 
                                    <li>Language: <?php
                                        if ($all_records[0]['language_name'] == "1") {
                                            echo "English";
                                        } else {
                                            echo "Spanish";
                                        }
                                        ?>
                                    </li>
                                <?php } ?>
                            </ul></div>
                        <div class="clr"></div>
                    </div>
                    <div class="clr"></div>
                </div>

                <div class="clr"></div>
            </div>   
            <div class="row">
                <div class="pad-6">
                    <div class="col-xs-12 col-md-3">
                        <ul class="social-list2 clearfix">
                            <?php
                            if (isset($all_records[0]['fb']) && $all_records[0]['fb'] != '') {
                                ?>
                                <li> <a href="<?php echo $all_records[0]['fb']; ?>" target="_blank"><img src="<?php echo base_url('uploads'); ?>/images/fb-icon.png" alt="Facebook Url"/></a></li>
                            <?php } ?>

                            <?php
                            if (isset($all_records[0]['linkdin']) && $all_records[0]['linkdin'] != '') {
                                ?>
                                <li><a href="<?php echo $all_records[0]['linkdin']; ?>" target="_blank"><img src="<?php echo base_url('uploads'); ?>/images/linkdin-icon.png" alt="Linkedin Url"/></a></li>
                            <?php } ?>

                            <?php
                            if (isset($all_records[0]['twitter']) && $all_records[0]['twitter'] != '') {
                                ?>
                                <li><a href="<?php echo $all_records[0]['twitter']; ?>" target="_blank"><img src="<?php echo base_url('uploads'); ?>/images/twitter-icon1.png" alt=""/></a></li>
                            <?php } ?>

                        </ul>

                    </div>
                    <div class="col-xs-12 col-md-9 text-right">
                        <a class="btn btn-blue" data-href="<?php echo base_url() . "Contact/send_email_view/" . $all_records[0]['contact_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('SEND_EMAIL'); ?>"><?php echo lang('SEND_EMAIL'); ?></a>&nbsp;&nbsp;&nbsp;

                        <a class="btn btn-blue" data-href="<?php echo base_url() . "Contact/scheduleMeeting/" . $all_records[0]['contact_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('SCHEDULE_MEETING'); ?>"><?php echo lang('SCHEDULE_MEETING'); ?></a>&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-blue"><?php echo lang('NAVIGATE_CONTACT'); ?></button>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
            </div>

            <div class="clr"></div>


        </div>-->
        <div class="clr"></div>
        <br/>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="<?php
            if ($this->session->userdata('setting_current_tab') == 'Communication') {
                echo "active";
            }
            ?>"><a href="#Communication" aria-controls="Notes" role="tab" data-toggle="tab"><?php echo lang('COMMUNICATION'); ?></a></li>
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
            if ($this->session->userdata('setting_current_tab') == 'Document') {
                echo "active";
            }
            ?>"><a href="#Documents" aria-controls="Documents" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_DOCUMENTS'); ?></a></li>
            <li role="presentation" class="<?php
            if ($this->session->userdata('setting_current_tab') == 'Cases') {
                echo "active";
            }
            ?>"><a href="#Cases" aria-controls="Cases" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_CASES'); ?></a></li>

            <li role="presentation" class="<?php
            if ($this->session->userdata('setting_current_tab') == 'Events') {
                echo "active";
            }
            ?>"><a href="#Events" aria-controls="Events" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_EVENTS'); ?></a></li>
            <li role="presentation" class="<?php
            if ($this->session->userdata('setting_current_tab') == 'Meeting') {
                echo "active";
            }
            ?>"><a href="#Meeting" aria-controls="Meeting" role="tab" data-toggle="tab"><?php echo lang('SCHDEDULE_MEETING'); ?></a></li>
            <!-- <li role="presentation"><a href="#Opportunities" aria-controls="Opportunities" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_OPPORTUNITIES'); ?></a></li>
            <li role="presentation"><a href="#Communication" aria-controls="Communication" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_COMMUNICATION'); ?></a></li>
           <li role="presentation"><a href="#Campaigns" aria-controls="Campaigns" role="tab" data-toggle="tab"><?php echo lang('CONTACT_VIEW_CAMPAIGNS'); ?></a></li>
            -->
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane <?php
                 if ($this->session->userdata('setting_current_tab') == 'Communication') {
                     echo "active";
                 }
            ?>" id="Communication">
            </div>

            <div role="tabpanel" class="tab-pane <?php
                 if ($this->session->userdata('setting_current_tab') == 'Notes') {
                     echo "active";
                 }
            ?>" id="Notes">
                <!-- Note data has been placed by ajax call  -->
            </div>
            <div role="tabpanel" class="tab-pane <?php
                 if ($this->session->userdata('setting_current_tab') == 'Task') {
                     echo "active";
                 }
            ?>" id="Tasks">
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
                 if ($this->session->userdata('setting_current_tab') == 'Document') {
                     echo "active";
                 }
                 ?>" id="Documents">
                <!-- Attach File data has been placed by ajax call  -->
            </div>

            <div role="tabpanel" class="tab-pane <?php
                        if ($this->session->userdata('setting_current_tab') == 'Events') {
                            echo "active";
                        }
                        ?>" id="Events">


            </div>

            <div role="tabpanel" class="tab-pane <?php
                                if ($this->session->userdata('setting_current_tab') == 'Meeting') {
                                    echo "active";
                                }
                        ?>" id="Meeting">


            </div>



        </div>

    </div>


    <div class="col-xs-12 col-md-3">

        <div class="whitebox pad-10">
            
          
                <div class="form-group">
                    <select name="contact_owner" class="form-control chosen-select"  id="contact_owner" data-parsley-errors-container="#job_title-errors">
                        <option value=""><?php echo lang('CONTACT_OWNER');?></option>             
<?php if (isset($prospect_owner) && count($prospect_owner) > 0) { ?>
    <?php foreach ($prospect_owner as $user) { 
        
        if($all_records[0]['created_by'] == $user['login_id'])
        { ?>
            <option value="<?php echo $user['login_id']; ?>" 
        <?php
        if (!empty($all_records[0]['created_by']) && $all_records[0]['created_by'] == $user['login_id']) {
            echo "selected='selected'";
        }
        ?>> <?php echo ucfirst($user['firstname'])." ".ucfirst($user['lastname']); ?> </option>
        <?php } } ?>
<?php } ?>
                    </select>
                </div>
            
                <?php
                if (checkPermission('Contact', 'edit')) {
                    ?>
                <div class="pad-tb6">
                    <a data-href="<?= base_url() ?>Contact/edit/<?= $all_records[0]['contact_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('edit'); ?>"><i class="fa fa-edit bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('edit'); ?> </a>
                </div>
                <div class="grayline-1"></div>
                <?php }
                ?>

<?php
if (checkPermission('Contact', 'add')) {
    ?>

                <div>
                    <a data-href="<?= base_url() ?>Contact/addNote/<?= $all_records[0]['contact_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('ADD_NOTES'); ?>"><i class="fa fa-comment bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('ADD_NOTES'); ?></a>
                </div>
<?php } ?>
                <div>
                    <!--  //base_url() ?>Contact/viewTask/ //$all_records[0]['contact_id'] ?>-->
                   <?php
if (checkPermission('Task', 'add')) {
    ?> <a data-href="<?php echo base_url('Task/add') . "/" . $all_records[0]['contact_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('ADD_TASKS'); ?>"><i class="fa fa-file-text bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('ADD_TASKS'); ?></a>
                </div>
<?php } ?>
                <div>
                <?php
if (checkPermission('Opportunity', 'add')) {
    ?>    <a data-href="<?= base_url() ?>Opportunity/add/<?= $all_records[0]['contact_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('ADD_DEAL'); ?>"><i class="fa fa-tag bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('ADD_DEAL'); ?></a>
                </div>

                <!--
                <div>
                    <a data-href="<?= base_url() ?>Contact/view_event/<?= $all_records[0]['contact_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('ADD_EVENTS'); ?>"><i class="fa fa-tag bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('ADD_EVENTS'); ?></a>
                </div>-->
                <div class="grayline-1"></div>
            <?php }
            ?>


            <div class="pad-tb6">
            <?php if (checkPermission('Contact', 'add')) { ?>
                    <a data-href="<?php echo base_url() . 'Contact/view_add_campaign/' . $all_records[0]['contact_id']; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('ADD_TO_CAMPAIGN'); ?>"><i class="fa fa-sitemap bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('ADD_TO_CAMPAIGN'); ?> </a>


            </div>
            <div class="grayline-1"></div>
            <?php } ?>
            <div class="pad-tb6">
            <?php if (checkPermission('Contact', 'add')) { ?>
                    <a data-href="<?php echo base_url() . 'Contact/AddCases/' . $all_records[0]['contact_id']; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('add_cases'); ?>"><i class="fa fa-sitemap bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('add_cases'); ?> </a>


            </div>
            <div class="grayline-1"></div>
            <?php } ?>
<?php
if (checkPermission('Contact', 'add')) {
    ?>
                <div>
                    <a onclick="duplicate_contact('<?php echo $all_records[0]['contact_id']; ?>');" title="<?php echo lang('CONTACT_DUPLICATE'); ?>"><i class="fa fa-copy bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('CONTACT_DUPLICATE'); ?> </a>
                </div>
<?php }
?>

<?php
if (checkPermission('Contact', 'add') && checkPermission('Contact', 'edit') && checkPermission('Contact', 'delete')) {
    ?>
                <div>
                    <a data-href="<?= base_url() ?>Contact/viewMergeContact/<?= $all_records[0]['contact_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('CONTACT_MERGE_DUPLICATE'); ?>"><i class="fa fa-exchange bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('CONTACT_MERGE_DUPLICATE'); ?> </a>
                </div> 


            <div class="grayline-1"></div>
<?php } ?>
<?php
if (checkPermission('Contact', 'delete')) {
    ?>
                <div>
                    <a onclick="delete_contact('<?php echo $all_records[0]['contact_id']; ?>', '<?php echo base_url('Contact'); ?>');" title="<?php echo lang('CONTACT_DELETE'); ?>"><i class="fa fa-remove bluecol"></i>&nbsp;&nbsp;&nbsp;<?php echo lang('CONTACT_DELETE'); ?> </a>
                </div>
<?php } ?>


            <div class="clr"></div>

<?php
if (checkPermission('Contact', 'add')) {
    ?>
                <div class="pad-10 text-center">
                    <a data-href="<?= base_url() ?>Contact/view_add_file/<?= $all_records[0]['contact_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('ADD_FILE_TO_CONTACT'); ?>" class="width-100 btn small-white-btn2"><?php echo lang('ADD_FILE_TO_CONTACT'); ?></a>
                </div>
<?php } ?>

            <div class="clr"></div>

        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>

</div>
</div>
<!-- /container --> 

<script>
//not included in loadjsFiles bcoz there are to many validations are working as global

    function delete_note(note_id, redirect_link)
    {
        //var url_contact = '<?php echo base_url() . "Contact/delete_note" ?>';
        var delete_url = "../../Contact/delete_note/?note_id=" + note_id + "&link=" + redirect_link;
        var delete_meg ="<?php echo lang('CONFIRM_DELETE_NOTE'); ?>";
        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: delete_meg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
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

    function delete_task(task_id, redirect_link)
    {

        //var url_contact = '<?php echo base_url() . "Contact/deletetask" ?>';
        var delete_url = "../../Contact/delete_task/?task_id=" + task_id + "&link=" + redirect_link;
        var delete_meg ="<?php echo lang('CONFIRM_DELETE_TASK'); ?>";
        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: delete_meg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
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

    function getNoteData(searchtext)
    {
        var url_contact = '<?php echo base_url() . "Contact/viewNoteData/" ?>';
        $.ajax({
            type: "POST",
            url: url_contact,
            data: {'getNoteData': 'Note', 'searchtext': searchtext, 'contact_id': '<?php echo $all_records[0]['contact_id']; ?>', 'note_status': '1'},
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
            data: {'getTasksData': 'Tasks', 'searchtext': searchtext, 'contact_id': '<?php echo $all_records[0]['contact_id']; ?>', 'task_status': '1'},
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
            data: {'getDealsData': 'Deals', 'searchtext': searchtext, 'contact_id': '<?php echo $all_records[0]['contact_id']; ?>', 'deal_status': '1'},
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
            data: {'getDealsData': 'Campaign', 'searchtext': searchtext, 'contact_id': '<?php echo $all_records[0]['contact_id']; ?>', 'campaign_status': '1'},
            success: function (data)
            {
                $('#Campaign').html(data);
            }
        });
    }

    function getContactFile(searchtext)
    {
        var url_contact = '<?php echo base_url() . "Contact/getContactFile/" ?>';
        $.ajax({
            type: "POST",
            url: url_contact,
            data: {'getDealsData': 'Campaign', 'searchtext': searchtext, 'contact_id': '<?php echo $all_records[0]['contact_id']; ?>', 'file_status': '1'},
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
            data: {'getDealsData': 'Campaign', 'searchtext': searchtext, 'contact_id': '<?php echo $all_records[0]['contact_id']; ?>', 'cases_status': '1'},
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
            data: {'getDealsData': 'Campaign', 'searchtext': searchtext, 'event_related_id': '<?php echo $all_records[0]['contact_id']; ?>', 'cases_status': '1'},
            success: function (data)
            {
                $('#Events').html(data);
            }
        });
    }

    function getContactCommunication(searchtext)
    {
        var url_contact = '<?php echo base_url() . "Contact/getContactCommunication/" ?>';
        $.ajax({
            type: "POST",
            url: url_contact,
            data: {'getDealsData': 'Campaign', 'searchtext': searchtext, 'contact_related_id': '<?php echo $all_records[0]['contact_id']; ?>', 'cases_status': '1'},
            success: function (data)
            {
                $('#Communication').html(data);
            }
        });
    }

    function getContactMeeting(searchtext)
    {
        var url_contact = '<?php echo base_url() . "Contact/getContactMeeting/" ?>';
        $.ajax({
            type: "POST",
            url: url_contact,
            data: {'getDealsData': 'Campaign', 'searchtext': searchtext, 'meeting_related_id': '<?php echo $all_records[0]['contact_id']; ?>', 'cases_status': '1'},
            success: function (data)
            {
                $('#Meeting').html(data);
            }
        });
    }
    function data_search()
    {
        var searchtext = $('#searchtext').val();
        getNoteData(searchtext);
        getTasksData(searchtext);
        getDealsData(searchtext);
        getCampaignData(searchtext);
        getContactFile(searchtext);
        getContactCases(searchtext);
        getContactEvents(searchtext);
        getContactCommunication(searchtext);
        getContactMeeting(searchtext);
    }

    function reset_data()
    {
        $('#searchtext').val('');
        getNoteData('clearData');
        getTasksData('clearData');
        getDealsData('clearData');
        getCampaignData('clearData');
        getContactFile('clearData');
        getContactCases('clearData');
        getContactEvents('clearData');
        getContactCommunication('clearData');
        getContactMeeting('clearData');
    }
    $(document).ready(function ()
    {
       
        getNoteData('');
        getTasksData('');
        getDealsData('');
        getCampaignData('');
        getContactFile('');
        getContactCases('');
        getContactEvents('');
        getContactCommunication('');
        getContactMeeting('');
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
    });

    function delete_contact(contact_id, redirect_link) {

        var delete_url = '<?php echo base_url(); ?>' + "Contact/deletedata/?id=" + contact_id + "&link=" + redirect_link;
        var delete_meg ="<?php echo lang('CONFIRM_DELETE_CONTACT'); ?>";
        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: delete_meg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
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


    function duplicate_contact(contact_id) {

        var duplicate_url = '<?php echo base_url(); ?>' + "Contact/createDuplicate/?id=" + contact_id;
        var delete_meg ="<?php echo lang('CONFIRM_DUPLICATE_CONTACT'); ?>";
        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: delete_meg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {
                        window.location.href = duplicate_url;
                        dialog.close();
                    }
                }]
            });

    }
    
    function navidate_contact()
    {
        /*
        var address = $('#hdn_contact_addres').val();
        
        $.ajax({
          url:"http://maps.googleapis.com/maps/api/geocode/json?address="+address+"&sensor=false",
          type: "POST",
          success:function(res){
             console.log(res.results[0].geometry.location.lat);
             console.log(res.results[0].geometry.location.lng);
             var address_lattitude = res.results[0].geometry.location.lat;
             var address_longitutde = res.results[0].geometry.location.lng;
              $("#contact_address").googleMap();
                $("#contact_address").addMarker({
                  coords: [48.895651, 2.290569], // GPS coords
                  url: 'http://www.tiloweb.com', // Link to redirect onclick (optional)
                  id: 'marker1' // Unique ID for your marker
                });
          }
        });
            
            return false;
        */
        $('#contact_address').html('<img src="<?php echo base_url()."uploads/images/ajax-loader.gif"; ?>">');
        
        var contact_address = $('#hdn_contact_addres').val();
        var embed ='<iframe width="200" height="150" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?&amp;q='+ encodeURIComponent(contact_address ) +'&amp;output=embed"></iframe>';
        $('#contact_address').html(embed);
    }
    
        navidate_contact();
</script>
<!--<script type="text/javascript" src="//www.google.fr/jsapi"></script>-->

<!--<script type="text/javascript" src="<?php //echo base_url()."uploads/custom/js/jquery.googlemap.js"?>"></script>-->
<script>
    /*
     $(document).ready(function(){
        $("address").each(function(){
             var embed ='<iframe width="200" height="200" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?&amp;q='+ encodeURIComponent( $(this).text() ) +'&amp;output=embed"></iframe>';
             $(this).html(embed);
       });
     });
     */
 </script> 
<?php unset($_SESSION['setting_current_tab']); ?>