<div class="row">
    <div class="col-md-6 col-md-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url() . 'Support'; ?>">
                    <?= lang('support') ?>
                </a></li>
            <li class="active"><a href="<?php echo base_url() . 'Support/KnowledgeBase'; ?>">
                    <?= lang('knowledgebase') ?>
                </a></li>
            <li class="active">
                    <?= lang('sub_categories') ?>
                </li>
        </ul>
    </div>
    <!-- Search: Start -->
    <div class="col-xs-12 col-md-3 pull-right text-right col-md-offset-3">
        <div class="row">
            <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
            <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
            <div class="navbar-form navbar-left pull-right" id="searchForm">
                    <div class="input-group">
                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>">
                        <input type="text" name="searchtext" id="searchtext" class="form-control"  placeholder="<?= lang('EST_LISTING_SEARCH_FOR') ?>" aria-controls="example1" placeholder="Search" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
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
            <div class="col-xs-12 col-md-6">
                <h3 class="white-link"> <?= lang('sub_categories') ?></h3>
            </div>
            
             <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                   
                    <select id="search_category"  name="search_category" class="chosen-select form-control" placeholder="<?= lang('sel_cat') ?>">
                        <option value="0"><?= lang('sel_cat') ?></option>
                        <?php
                        if (!empty($main_category_data)) {
                            foreach ($main_category_data as $row) {
                                ?>
                                <option  value="<?php echo $row['main_category_id'] ?>" <?php echo ($id == $row['main_category_id']) ? 'selected' : ''; ?>><?php echo ucfirst($row['category_name']); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 text-left">
                <button class="btn btn-white" type="button" name="btn_type_of_rep" id="btn_type_of_rep"><?= lang('reset') ?></button>
                
            </div>
            <div class="clr"></div>
        </div>
        <div class="whitebox" id="common_div">
            <?= $this->load->view('Support/KnowledgeBase/ajax_list_general_category.php', '', true); ?>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>
</div>



<script>
     $("#search_category").change(function () {
          
            var id = $("#search_category").val();
            
            $.ajax({
                type: "POST",
                url: "",
                data: {
                    'id': id,
                    'result_type': 'ajax'},
                success: function (response)
                {
                    $("#common_div").html(response);
                }
            });
        });
        
        $("#btn_type_of_rep").on("click", function () {
             $('#search_category').val('0');
            $(document).ready(function () {
          
            var id = $("#search_category").val();
            
            $.ajax({
                type: "POST",
                url: "",
                data: {
                    'id': id,
                    'result_type': 'ajax'},
                success: function (response)
                {
                    $("#common_div").html(response);
                }
            });
        });
            });
        
    </script>
    
    <script type="text/javascript">
    $(document).ready(function () {

        //serch by enter
        $('#searchtext').keyup(function (event)
        {
            if (event.keyCode == 13) {
                data_search('changesearch');
            }

        });
    });
    //Search data
    function data_search(allflag)
    {
        var uri_segment = $("#uri_segment").val();
        /* Start Added By Sanket*/
        var request_url = '';
        if (uri_segment == 0)
        {
            request_url = '<?php echo $this->config->item('base_url') . '/' . $this->viewname ?>/index/' + uri_segment;
        } else
        {
            request_url = '<?php echo $this->config->item('base_url') . '/' . $this->viewname ?>/' + uri_segment;
        }
        /* End Added By Sanket*/

        $.ajax({
            type: "POST",
            url: request_url,
            data: $('#search_form').serialize() + '&result_type=ajax&perpage=' + $("#perpage").val() + '&searchtext=' + $("#searchtext").val() + '&id=' + $("#id").val() + '&sortfield=' + $("#sortfield").val() + '&sortby=' + $("#sortby").val() + '&allflag=' + allflag,
            success: function (html) {

                $("#common_div").html(html);
                $('#totalAccountText').text($('#total_account_count').val());
            }
        });
        return false;
    }
    function reset_form() {
        $(".chosen-select").val('').trigger("chosen:updated");
        $('#search_branch_id').val('');
        $('#search_country_id').val('');
        $('#search_status').val('');
        $('#search_creation_date').val('');
        $('#creation_end_date').val('');
        $("#searchtext").val("");
        apply_sorting('', '');
        data_search('all');
       location.reload();  
    }
    function reset_data()
    {
        $("#searchtext").val("");
        apply_sorting('', '');
        data_search('all');
        
    }

    function reset_data_list(data)
    {
        $("#searchtext").val(data);
        apply_sorting('', '');
        data_search('all');
       
    }

    function changepages()
    {
        data_search('');
    }

    function apply_sorting(sortfilter, sorttype)
    {
        $("#sortfield").val(sortfilter);
        $("#sortby").val(sorttype);
        data_search('changesorting');
    }
    //pagination
    $('body').on('click', '#common_tb ul.pagination a.ajax_paging', function (e) {
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: $('#search_form').serialize() + '&result_type=ajax&perpage=' + $("#perpage").val() + '&searchtext=' + $("#searchtext").val() + '&sortfield=' + $("#sortfield").val() + '&sortby=' + $("#sortby").val() + '&id=' + $("#id").val(),
            /*
             beforeSend: function () {
             $('#common_div').block({message: 'Loading...'});
             },
             */
            success: function (html) {
                $("#common_div").html(html);
                $('#totalAccountText').text($('#total_account_count').val());
                //    $.unblockUI();
            }
        });
        return false;
    });
    function deletepopup(id, name)
    {
        if (id == '0')
        {
            var boxes = $('input[name="check[]"]:checked');
            if (boxes.length == '0')
            {
                $.alert({
                    title: 'Alert!',
                    //backgroundDismiss: false,
                    content: "<strong> Please select record(s) to delete.<strong>",
                    confirm: function () {
                    }
                });
                return false;
            }
        }
        if (id == '0')
        {
            var msg = 'Are you sure want to delete Record(s)';
        }
        else
        {
            var msg = 'Are you sure want to delete ' + name + '?';
        }
        $.confirm({
            title: 'Confirm!',
            content: "<strong> " + msg + " " + "<strong>",
            confirm: function () {
                delete_all_multipal('delete', id);
            },
            cancel: function () {

            }
        });
    }
    /*on and off button function */
    function toggle_show(className, obj) {
        var $input = $(obj);
        if ($input.prop('checked'))
            $(className).show();
        else
            $(className).hide();
    }

</script>