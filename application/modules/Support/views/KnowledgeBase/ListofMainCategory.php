<div class="row">
    <div class="col-md-6 col-sm-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url() . 'Support'; ?>">
                    <?= lang('support') ?>
                </a></li>
            <li class="active"><a href="<?php echo base_url() . 'Support/KnowledgeBase'; ?>">
                    <?= lang('knowledgebase') ?>
                </a></li>
            <li class="active">
                 <?= lang('main_categories') ?>
                </li>
        </ul>
    </div>
    <!-- Search: Start -->
    <div class="col-xs-12 col-md-3 col-sm-6 pull-right text-right col-md-offset-3">
        <div class="row">
            <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
            <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                <div class="navbar-form navbar-left pull-right" id="searchForm">
                    <div class="input-group">
                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>">
                        <input type="text" name="searchtext" id="searchtext" class="form-control"  placeholder="<?= lang('EST_LISTING_SEARCH_FOR') ?>" aria-controls="example1" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
                        <span class="input-group-btn">
                            <button onclick="data_search('changesearch')" class="btn btn-default"  title="<?= $this->lang->line('search') ?>"><?= $this->lang->line('common_search_title') ?> <i class="fa fa-search fa-x"></i></button>
                            <button class="btn btn-default howler flt" title="<?= $this->lang->line('reset') ?>" onclick="reset_data()" title="Reset"><?= $this->lang->line('common_reset_title') ?><i class="fa fa-refresh fa-x"></i></button> 
                        </span> </div>
                 </div>   
            </div>

            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>
    <!-- Search: End -->
   <div class="clr"></div>
    <?php if($this->session->flashdata('msg')){ ?>
        <div class='alert alert-success text-center'>
            <?php  echo $this->session->flashdata('msg'); ?>
        </div>
    <?php } ?>
    <?php if ($this->session->flashdata('error')) { ?>
    <div class='alert alert-danger text-center'>
            <?php  echo $this->session->flashdata('error'); ?>
        </div>
           
        <?php } ?>
  <div class="clr"></div>
    <div class="col-xs-12 col-md-12">
        <div class="row">
            <div class="col-xs-12 col-md-6 col-sm-6">
                <h3 class="white-link"><?= lang('main_categories') ?></h3>
            </div>
            <div class="text-right col-md-6 col-sm-6">
                <?PHP if (checkPermission('KnowledgeBase', 'add')) { ?>
                                    <a data-href="<?php echo base_url('Support/KnowledgeBase/AddMainCategory'); ?>" data-toggle="ajaxModal" class="btn btn-white" aria-hidden="true" data-refresh="true">
                                        <?= $this->lang->line('add_main_category') ?>
                                    </a>
                                <?php } ?>
            </div>
            <div class="clr"></div>
        </div>
        <div class="whitebox" id="common_div">
            <?= $this->load->view('Support/KnowledgeBase/ajax_list_main_category.php', '', true); ?>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>
</div>
<?= $this->load->view('/Common/common', '', true); ?>


