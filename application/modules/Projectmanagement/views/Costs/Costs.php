<div id="main-page"> <?php echo $this->session->flashdata('msg'); ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9" id="costReplacer">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                    <form class="">
                        <div class="form-group row">
                            <label class="col-md-1 col-xs-2 white-link pad-t6"><?php echo lang('filter'); ?></label>
                            <div class="col-md-3 col-xs-4">
                                <select class="form-control filter" data-field="CM.status" id="filter_status" name="filter[]" onchange="data_group('CM.status', this.value);">
                                    <option value="" data-field="CM.status"><?php echo lang('status'); ?></option>
                                    <option value="1" data-field="CM.status"><?php echo lang('paid'); ?></option>
                                    <option value="0" data-field="CM.status"><?php echo lang('unpaid'); ?></option>
                                </select>
                            </div>
                            <div class="col-md-4 col-xs-6">
                                <select tabindex="-1" id="filter_user " data-field="CM.user_id"  name="filter[]" class="form-control filter" onchange="data_group('cm.user_id', this.value);" data-placeholder="Choose a employee" required>
                                    <option value=""><?php echo lang('cost_placeholder_member'); ?></option>
                                    <?php
                                    if (!empty($res_user)) {
                                        foreach ($res_user as $row) {
                                            ?>
                                            <option data-field="CM.user_id" <?php
                                            if (!empty($edit_record[0]['res_user']) && $edit_record[0]['res_user'] == $row['login_id']) {
                                                echo 'selected="selected"';
                                            }
                                            ?> value="<?= $row['login_id'] ?>">
                                                        <?= ucfirst($row['firstname']) . ' ' . $row['lastname'] ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="clr"></div>
                        </div>
                    </form>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 text-right pull-right">
                    <?php if (checkPermission('Costs', 'add')) { ?>
                        <a class="btn btn-white pull-right add_record add_cost full-width" href="<?php echo base_url($project_view . '/add'); ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal"><?php echo lang('add_cost') ?> </a>
                    <?php } ?>
                </div>
                <!--            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 text-right">
                                    <div class="btn-group">
                                        <button class="btn btn-white"><i class="fa fa-list"></i></button>
                                        <button class="btn btn-white"><i class="fa fa-th"></i></button>
                                    </div>
                                </div>-->
                <div class="clr"></div>
                <div class="col-xs-12">
                    <div class="whitebox" id="common_div">
                        <?php $this->load->view('AjaxCosts'); ?>
                        <div class="clr"></div>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
            <?php echo $this->load->view('Project_links'); ?>
            <div class="clr"></div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"> 
            <?php echo Modules::run('Sidebar/taskCalendar'); ?> 
            <div class="clr"></div>
            <?php echo $this->load->view('Task_activities'); ?>
            <div class="clr"></div>
            <?php echo $this->load->view('messagebox_view'); ?>
            <div class="clr"></div>
 
        </div>

        <div class="clr"></div>
    </div>
</div>
<script>

    $(document).ready(function () {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'
            },
            defaultDate: '<?= date("Y-m-d") ?>',
            defaultView: 'month',
            yearColumns: 2,
            selectable: false,
            selectHelper: true,
            //firstDay: 0,
            editable: false,
            eventLimit: true, // allow "more" link when too many eventsProjectmanagement/ProjectTask/grantview/grantview"
            events: {
                url: '<?= base_url("Projectmanagement/ProjectTask/grantview/grantview") ?>',
                success: function (data) {

                },
                error: function () {
                    $('#script-warning').show();
                }}
            ,
            eventRender: function (event, element) {
                element.attr("data-toggle", "ajaxModal")
            },
        });

    });
    function delete_row(rand) {
        jQuery('#' + rand).remove();
    }


    //image upload
    function showimagepreview(input)
    {
        $('.upload_recent').remove();
        var url = '<?php echo base_url(); ?>';
        $.each(input.files, function (a, b) {
            var rand = Math.floor((Math.random() * 100000) + 3);
            var arr1 = b.name.split('.');
            var arr = arr1[1].toLowerCase();
            var filerdr = new FileReader();
            var img = b.name;
            filerdr.onload = function (e) {
                var template = '<div class="eachImage upload_recent" id="' + rand + '">';
                var randtest = 'delete_row("' + rand + '")';
                template += '<a id="delete_row" class="remove_drag_img" onclick=' + randtest + '>×</a>';
                if (arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif') {
                    template += '<span class="preview" id="' + rand + '"><img src="' + e.target.result + '"><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                } else {
                    template += '<span class="preview" id="' + rand + '"><div><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + arr + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                }
                template += '<input type="hidden" name="file_data[]" value="' + b.name + '">';
                template += '</span>';
                $('#dragAndDropFiles').append(template);
            }
            filerdr.readAsDataURL(b);

//           console.log(b.name);
        });
        //console.log(input.files[0]['name']);
        var maximum = input.files[0].size / 1024;
        //alert(maximum);

    }



</script> 
