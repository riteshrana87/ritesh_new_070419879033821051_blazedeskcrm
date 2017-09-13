<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = !empty($editRecord) ? 'updatedata' : 'insertdata';

$path = $crnt_view . '/' . $formAction;

$SortDefault = '<i class="fa fa-sort"></i>';
$sortAsc = '<i class="fa fa-sort-desc"></i>';
$sortDesc = '<i class="fa fa-sort-asc"></i>';
if ($sortOrder == "asc")
    $sortOrder = "desc";
else
    $sortOrder = "asc";

$this->load->view('ajaxlist', '', true);
?>

<?php // echo $this->session->flashdata('msg');   ?>

<!-- Example row of columns -->

<div class="row">
    <div class="col-md-6 col-sm-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?= base_url('User/') ?>"><?= lang('USER_LIST_HEADER_MENU_LABEL') ?></a></li>

        </ul>
    </div>
    <!-- Search: Start -->

    <div class="col-xs-12 col-sm-6 col-md-3 pull-right text-right col-md-offset-3">
        <div class="row">
            <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#"><i class="fa fa-gear fa-2x"></i></a> </div>
            <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                <!--  <form class="navbar-form navbar-left pull-right" id="searchForm"> -->
                <div class="navbar-form navbar-left pull-right" id="searchForm">
                    <div class="input-group">
                        <input type="text" name="search_input" id="search_input" class="form-control" placeholder="<?= lang('EST_LISTING_SEARCH_FOR')?>">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit" title="<?= lang('search') ?>" id="submit_search" name="submit" ><i class="fa fa-search fa-x"></i></button>&nbsp;
                            <button class="btn btn-default" type="button" title="<?= lang('reset')?>" onclick="reset_data()" ><i class="fa fa-refresh fa-x"></i></button>
                        </span> 
                    </div>
                </div>

                <!--     </form> -->
            </div>     
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>

    <!--  <div class="col-md-6 col-md-6 text-right">
      <div class="pull-right settings"> <a href="#"><i class="fa fa-gear fa-2x"></i></a> </div>
      <div class="pull-right search-top">
        <form class="navbar-form navbar-left" id="searchForm">
          <div class="form-group">
            <input type="text" name="search_input" id="search_input" placeholder="Search for..." class="form-control">
          </div>
          <button class="fa fa-search btn btn-default" type="submit" id="submit" name="submit"></button>
          <button class="btn btn-default" type="button"  onclick="reset_data()"  ><i class="fa fa-refresh fa-x" ></i></button>
        </form>
      </div>
    </div> -->
    <!-- Search: End -->
    <div class="clr"></div>
    <?php echo $this->session->flashdata('msg'); ?>     
    <div class="clr"></div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <h3 class="white-link">
                    <?= $this->lang->line('USER_LIST_HEADER_MENU_LABEL') ?>
                </h3>
            </div>
            <?php if (checkPermission('User', 'add')) { ?>
                <div class="col-xs-6 col-sm-6 text-right col-md-6 col-lg-6 "> <a data-href="<?php echo base_url($crnt_view . '/registration'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="btn btn-white">
                        <?= $this->lang->line('create_user') ?>
                    </a>
                    <div class="clr"></div>
                </div>
            <?php } ?>
            <div class="clr"></div>
        </div>
        <!-- Listing of User List Table: Start -->
        <div class="whitebox" id="tableUserDiv">
            <?php $this->load->view('ajaxlist'); ?>
            <!-- Listing of User List Table: End --> 
        </div>

        <!--  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <h3 class="white-link"><?= $this->lang->line('USER_LIST_HEADER_MENU_LABEL') ?></h3> 
          <div class="whitebox">
            <div class="table table-responsive">
              <table id="datatable1" class="table table-striped" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th><?= $this->lang->line('name') ?></th>
                    <th><?= $this->lang->line('emails') ?></th>
                    <th><?= $this->lang->line('contact') ?></th>     
                     <th><?= $this->lang->line('usertype') ?></th>         
                    <th><?= $this->lang->line('status') ?></th>
                    <th></th>
                  </tr>
                </thead>        
               <tbody>
        <?php if (isset($information) && count($information) > 0) { ?>
            <?php
            foreach ($information as $data) {
                if ($data['status'] == 1) {
                    $data['status'] = "Active";
                } else {
                    $data['status'] = "InActive";
                }
                ?>
                              <tr>
                                <td><?php echo $data['firstname'] . " " . $data['lastname']; ?></td>
                                <td><?php echo $data['email']; ?></td>
                                <td><?php echo $data['telephone1']; ?></td>
                                <td><?php echo $data['user_type']; ?></td>
                                 <td><?php echo $data['status']; ?></td>
                                <td><?php if (checkPermission('User', 'view')) { ?><a href="<?php echo base_url($crnt_view . '/view/' . $data['login_id']); ?>" data-toggle="ajaxModal"><i class="fa fa-search fa-x greencol" ></i></a><?php } ?>&nbsp;&nbsp;<?php if (checkPermission("User", "delete")) { ?><a href="<?php echo base_url($crnt_view . '/deletedata/' . $data['login_id']); ?>"><i class="fa fa-remove redcol" onclick="return confirm('Are you sure you want to delete this User ?','yes','no');"></i></a><?php } ?></td>
                              </tr>
            <?php } ?>
        <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="clr"></div>
          </div>
          <div class="clr"></div>
        </div> -->
    </div>
</div>
<script>
    $(document).ready(function () {
        $('body').delegate('[data-toggle="ajaxModal"]', 'click',
                function (e) {
                    $('#ajaxModal').remove();
                    e.preventDefault();
                    var $this = $(this)
                            , $remote = $this.data('remote') || $this.attr('data-href')
                            , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
                    $('body').append($modal);
                    $modal.modal();
                    $modal.load($remote);

                }
        );
        function bindClicks() {
            $("ul.tsc_pagination a").click(paginationClick);
        }

        function bindClicksSort() {
            $("body").delegate('th.sortUserList a', 'click', (paginationClick));
        }

        $('body').delegate('#submit_search', 'click', function () {
            paginationClick();
            return false;
        });
        $('body').delegate('#search_input', 'keyup', function (event) {
            if (event.keyCode == 13) {
                paginationClick();
            }
            return false;
        });
        function paginationClick() {
            var href = $(this).attr('href');
            $("#rounded-corner").css("opacity", "0.4");
            var search = $('#search_input').val();
            $.ajax({
                type: "GET",
                url: href,
                data: {search: search},
                success: function (response)
                {
                    //alert(response);
                    $("#rounded-corner").css("opacity", "1");
                    $("#tableUserDiv").empty();
                    $("#tableUserDiv").html(response);
                    bindClicks();
                }
            });
            return false;
        }
        bindClicks();
        bindClicksSort();

    });
    function reset_data()
    {
        $("#search_input").val("");
        $("#submit_search").trigger("click");

    }
// Use for Dive click filer 
    /*
     $('body').delegate('.name_list','click',function(){
     $(this).children('a').trigger('click');
     });*/

</script> 
