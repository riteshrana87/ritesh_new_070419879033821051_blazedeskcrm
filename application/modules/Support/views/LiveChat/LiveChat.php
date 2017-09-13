<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
//$formAction = 'insertdata';
//$path = $project_view . '/' . $formAction;


?>
<!-- Example row of columns -->
<div class="container"> 
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <ul class="breadcrumb nobreadcrumb-bg">
                <li><a href="<?php echo base_url() . 'Support'; ?>">
                        <?= lang('support') ?>
                    </a></li>
                <li class="active"><a href="<?php echo base_url() . 'Support/LiveChat'; ?>">
                        <?= lang('live_chat') ?>
                    </a></li>
            </ul>
        </div>

        <!-- Search: Start -->

        <div class="col-xs-12 col-md-3 pull-right text-right col-md-offset-3">
            <div class="row">
                <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right">
                    <a href="#"><i class="fa fa-gear fa-2x"></i></a> 
                </div>
                <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                    <div class="navbar-form navbar-left pull-right" id="searchForm">
                        <div class="input-group">
                            <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>">
                            <input type="text" name="searchtext" id="searchtext"  class="form-control" placeholder="Search for..." value="<?= !empty($searchtext) ? $searchtext : '' ?>">
                            <span class="input-group-btn">
                                <button onclick="data_search('changesearch')" class="btn btn-default" type="button"><i class="fa fa-search fa-x"></i></button>&nbsp;

                                <button class="btn btn-default" title="button" onclick="reset_data()"><i class="fa fa-refresh fa-x"></i></button>
                            </span> </div>
                        <!-- /input-group -->
                    </div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
    </div>
    <!-- Search: End -->
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="row">
            
            
                           <div class="col-xs-6 col-md-6 no-left-pad"> <h3 class="white-link"><?= lang('live_chat') ?></h3></div>
                            <div class="col-xs-6 col-md-6 no-left-pad text-right">
                                <?PHP if (checkPermission('KnowledgeBase', 'add')) { ?>
                                    <a href="<?php echo base_url('Support/KnowledgeBase/AddMainCategory'); ?>" data-toggle="ajaxModal" class="btn btn-white" aria-hidden="true" data-refresh="true">
                                        <?= $this->lang->line('add_main_category') ?>
                                    </a>
                                <?php } ?>
                                <?PHP if (checkPermission('KnowledgeBase', 'add')) { ?>
                                    <a href="<?php echo base_url('Support/KnowledgeBase/AddSubCategory'); ?>" data-toggle="ajaxModal" class="btn btn-white" aria-hidden="true" data-refresh="true">
                                        <?= $this->lang->line('add_sub_category') ?>
                                    </a>
                                <?php } ?>
                                <?PHP if (checkPermission('KnowledgeBase', 'add')) { ?>
                                    <a href="<?php echo base_url('Support/KnowledgeBase/AddArticle'); ?>" data-toggle="ajaxModal" class="btn btn-white" aria-hidden="true" data-refresh="true">
                                        <?= $this->lang->line('add_knowledge_article') ?>
                                    </a>
                                <?php } ?>
                            </div>
                            <div class="clr"></div>
                       
            
            <!--chat data listing start-->
                <div class="whitebox" id="common_div">
						<?=$this->load->view('AjaxChatList','',true);?>
						<div class="clr"></div>
				</div>
			<!--chat data listing end-->
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
    </div>
</div>
<div class="clr"></div>
<?= $this->load->view('/Common/common', '', true); ?>
<script>
    $(document).ready(function () {
        $('.search_creation_date').datepicker({
            dateFormat: 'yy/mm/dd',
            autoclose: true
        }).on('changeDate', function (selected) {
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            $('.creation_end_date').datepicker('setStartDate', startDate);
        });

        $('.creation_end_date').datepicker({
            dateFormat: 'yy/mm/dd',
            autoclose: true
        });

        $('.search_contact_date').datepicker({
            dateFormat: 'yy/mm/dd',
            autoclose: true
        }).on('changeDate', function (selected) {
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            $('.contact_end_date').datepicker('setStartDate', startDate);
        });

        $('.contact_end_date').datepicker({
            dateFormat: 'yy/mm/dd',
            autoclose: true
        });
    });
</script>
