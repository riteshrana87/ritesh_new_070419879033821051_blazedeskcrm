<?php
if (isset($campaign_data) && !is_array($campaign_data)) {
    $action_title = $this->lang->line('BUDGET_REQUEST_SALES_CAMPAIGN');
    $fn_name = 'createRecord()';
    $form_action = 'create';
} else {
    $action_title = $this->lang->line('UPDATE_BUDGET_REQUEST_SALES_CAMPAIGN');
    $fn_name = 'updateRecord()';
    $form_action = 'update';
}
//var_dump($campaign_data);
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" title="<?=lang('close')?>" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><span id="type_action"><?php echo $action_title; ?></span></h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <form role="form" data-toggle="validator" name="frm_request_budget" id="frm_request_budget" enctype="multipart/form-data" action="<?php echo base_url() . 'RequestBudget/' . $form_action ?>" method="post">

                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 no-left-pad">
                        <div class="form-group">
                            <input type="hidden" id="budget_campaign" name="budget_campaign" value="<?php
                            if (isset($campaign_data[0]['budget_campaign_id']) && $campaign_data[0]['budget_campaign_id'] != '') {
                                echo $campaign_data[0]['budget_campaign_id'];
                            }
                            ?>">
                            <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                            <select data-parsley-errors-container="#compaign-errors" class="selectpicker form-control chosen-select" name="master_compaign" id="master_compaign" required onchange="autofilldata(this.value);" >
                                <option value="" selected=""><?= $this->lang->line('SELECT_COMPAIGN') ?> *</option>
                                <?php
                                foreach ($compaign_master as $compaign_master_array) {
                                    ?>
                                    <option <?php
                                    if (isset($campaign_data[0]['campaign_id']) && $campaign_data[0]['campaign_id'] == $compaign_master_array['campaign_id']) {
                                        echo "selected='selected'";
                                    }
                                    ?> value="<?php echo $compaign_master_array['campaign_id'] ?>"><?php echo $compaign_master_array['campaign_name'] ?></option>
                                    <?php } ?>
                            </select>

                            <div id="compaign-errors"></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 no-right-pad">
                        <div class="form-group">
                            <input type="text" value="<?php
                            if (isset($campaign_data[0]['campaign_auto_id']) && $campaign_data[0]['campaign_auto_id'] != '') {
                                echo $campaign_data[0]['campaign_auto_id'];
                            }
                            ?>" disabled="true" name="campaign_auto_id" class="form-control" id="campaign_auto_id" placeholder="<?= $this->lang->line('AUTOFILLED_BASED_ON_COMPAIGN') ?>"/>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 no-left-pad">
                        <div class="form-group">
                            <input type="text" name="campaign_type_id" data-parsley-maxlength="200" class="form-control" id="campaign_type_id" placeholder="<?=$this->lang->line('REQUEST_TYPE_OF_CAMPAIGN')?> *" value="<?=!empty($campaign_data[0]['camp_type_name'])?htmlentities(stripslashes($campaign_data[0]['camp_type_name'])):''?>" required=""/>
                            <div id="compaign-type-errors"></div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                        <div class="form-group" id="responsible_employee">
                            <select multiple class="selectpicker form-control chosen-select" name="responsible_employee_id[]" id="responsible_employee_id" data-placeholder="<?=$this->lang->line('REQUEST_EMPLOYEE')?>">
                                <?php
                                foreach($responsible_employee_data as $row){
                                    if (in_array($row['login_id'], $responsible_user_data)){
                                        ?>
                                        <option selected value="<?php echo $row['login_id'];?>"><?php echo $row['firstname'].' '.$row['lastname'];?></option>
                                    <?php }else{?>
                                        <option value="<?php echo $row['login_id'];?>"><?php echo $row['firstname'].' '.$row['lastname'];?></option>

                                    <?php }}?>
                            </select>

                            <div id="responsible-errors"></div>
                        </div>
                    </div>

                    <div class="clr"></div>
                    <div class="">
                        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label><?= lang('START_DATE') ?></label>
                                <div class='input-group date' id="start_date">

                                    <input type='text' data-parsley-errors-container="#start-date-errors" class="form-control" value="<?= !empty($campaign_data[0]['start_date']) ? date("m/d/Y", strtotime($campaign_data[0]['start_date'])) : '' ?>" name="start_date" id="start_date" placeholder="<?= $this->lang->line('START_DATE') ?> *" required/>
                                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>

                                </div>
                                <div id="start-date-errors"></div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label><?= lang('END_DATE') ?></label>
                                <div class='input-group date' id="end_date">
                                    <input data-parsley-errors-container="#end-date-errors" type='text' data-parsley-gteq="#start_date" class="form-control" value="<?= !empty($campaign_data[0]['end_date']) ? date("m/d/Y", strtotime($campaign_data[0]['end_date'])) : '' ?>" class="form-control" name="end_date" id="end_date" placeholder="<?= $this->lang->line('END_DATE') ?> *" required/>
                                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                                <div id="end-date-errors"></div>
                            </div>

                        </div>
                        <div class="clr"></div>
                    </div>
                    <div class="form-group col-xs-12">
                        <?php
                        if (isset($campaign_data[0]['campaign_description']) && $campaign_data[0]['campaign_description'] != '') {
                            $camp_description = $campaign_data[0]['campaign_description'];
                        } else {
                            $camp_description = '';
                        }
                        ?>
                        <textarea class="form-control" name="campaign_description" value="" rows="5" id="campaign_description" placeholder="<?= $this->lang->line('CAMPAIGN_DESCRIPTION') ?>"><?php echo $camp_description; ?></textarea>
                    </div>

                    <div class="clr"></div>

                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 no-left-pad">
                        <label><?= lang('BUDGET_AMOUNT') ?></label>
                        <div class="form-group">
                            <input type="text" value="<?php
                            if (isset($campaign_data[0]['budget_ammount']) && $campaign_data[0]['budget_ammount'] != '') {
                                echo $campaign_data[0]['budget_ammount'];
                            }
                            ?>" name="budget_ammount" class="form-control" id="budget_ammount_pri" placeholder="<?= $this->lang->line('AUTOFILLED_BASED_ON_COMPAIGN') ?> *" required pattern="/^\d{0,8}(\.\d{0,2})?$/" data-parsley-pattern="/^\d{0,8}(\.\d{0,2})?$/" />
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 no-right-pad">
                        <label><?= lang('REVENUE_GOAL') ?></label>
                        <div class="form-group">

                            <input type="text" value="<?php
                            if (isset($campaign_data[0]['revenue_goal']) && $campaign_data[0]['revenue_goal'] != '') {
                                echo $campaign_data[0]['revenue_goal'];
                            }
                            ?>" name="revenue_goal" class="form-control" id="revenue_goal" placeholder="<?= $this->lang->line('AUTOFILLED_BASED_ON_COMPAIGN') ?> *" required pattern="/^\d{0,8}(\.\d{0,2})?$/" data-parsley-pattern="/^\d{0,8}(\.\d{0,2})?$/"/>
                        </div>
                    </div>

                    <div class="clr"></div>

                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 no-left-pad">
                        <label><?= lang('BUDGET_REQUEST_FOR_SUPPLIER') ?></label>
                        <div class="form-group">
                            <select data-parsley-errors-container="#supplier-errors" class="form-control chosen-select" name="supplier_id" id="supplier_id" required>
                                <option value="" selected=""><?= $this->lang->line('AUTOFILLED_BASED_ON_COMPAIGN') ?>*</option>
                                <?php
                                foreach ($supplier_list as $supplier) {
                                    ?>
                                    <option <?php
                                    if (isset($campaign_data[0]['supplier_id']) && $campaign_data[0]['supplier_id'] == $supplier['supplier_id']) {
                                        echo "selected='selected'";
                                    }
                                    ?> value="<?php echo $supplier['supplier_id']; ?>"><?php echo $supplier['supplier_name']; ?></option>
                                <?php }
                                ?>
                            </select>
                            <div id="supplier-errors"></div>
                        </div>
                    </div>

                        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 no-right-pad">
                            <label><?= lang('BUDGET_REQUEST_FOR_PRODUCT') ?></label>
                            <div class="form-group" id="product_data">
                                <select multiple="true" class="chosen-select form-control" name="budget_for_product[]" data-placeholder="<?= $this->lang->line('AUTOFILLED_BASED_ON_COMPAIGN') ?>" id="budget_for_product">

                                    <?php if (!empty($product_list) && count($product_list) > 0) { ?>
                                        <?php foreach ($product_list as $product) { ?>
                                            <option value="<?php echo $product['product_id']; ?>" <?php
                                            if (!empty($campaign_product_data) && in_array($product['product_id'], $campaign_product_data)) {
                                                echo 'selected="selected"';
                                            }
                                            ?>><?php echo $product['product_name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>

                                </select>
                            </div>
                        </div>

                    <div class="clr"></div>

                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 no-left-pad">
                        <label><?= lang('ADDITIONAL_NOTES') ?></label>
                        <div class="form-group">
                            <?php
                            if (isset($campaign_data[0]['aditional_notes']) && $campaign_data[0]['aditional_notes'] != '') {
                                $additional_notes = $campaign_data[0]['aditional_notes'];
                            } else {
                                $additional_notes = '';
                            }
                            ?>
                            <textarea class="form-control" name="aditional_notes" id="aditional_notes" rows="5" id="campaign_description" placeholder="<?= $this->lang->line('ADDITIONAL_NOTES') ?>"><?php echo $additional_notes; ?></textarea>
                        </div>
                    </div>
                    <!-- new code-->
                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 no-right-pad">
                        <div class="mediaGalleryDiv mb15">

                            <button type="button" name="gallery" id="gallery-btn" data-href="<?php echo $url; ?>"  class="btn btn-primary"><?php echo lang('cost_placeholder_uploadlib') ?></button>
                            <div class="mediaGalleryImg">

                            </div>

                        </div>
                        <div id="dragAndDropFiles" class="uploadArea bd-dragimage">
                            <div class="image_part" style="height: 100px;">
                                <label name="fileUpload[]">
                                    <h1 style="top: -162px;">
                                        <i class="fa fa-cloud-upload"></i>
                                        <?= lang('DROP_IMAGES_HERE') ?>
                                    </h1>
                                    <input type="file" onchange="showimagepreview(this)" name="fileUpload[]" style="display: none" id="upl" multiple />
                                </label>
                            </div>
                            <?php

                            if (!empty($image_data)){
                                if (count($image_data) > 0) {
//                                $file_img = $campaign_data[0]['file'];
//                                $img_data = explode(',', $file_img);
                                    $i = 15482564;
                                    foreach ($image_data as $image) {
                                        $path = $image['file_path'];
                                        $name = $image['file_name'];
                                        $arr_list = explode('.', $name);
                                        $arr = $arr_list[1];
                                        if (file_exists($path . '/' . $name)) { ?>
                                            <div id="img_<?php echo $image['file_id']; ?>" class="eachImage">
                    <!--<a class="btn delimg remove_drag_img" href="javascript:;" data-id="img_<?php echo $image['file_id']; ?>" data-href="<?php echo base_url('RequestBudget/deleteImage/' . $image['file_id']); ?>">x</a>-->

                    <a class="btn delimg remove_drag_img" href="javascript:;" data-name="<?php echo $name; ?>" data-id="img_<?php echo $image['file_id']; ?>" data-path="<?php echo $path; ?>">x</a>

                                                <span id="<?php echo $i; ?>" class="preview">
           <a href='<?php echo base_url('RequestBudget/download/' . $image['file_id']); ?>' target="_blank">
               <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                   <img src="<?= base_url($path . '/' . $name); ?>"  width="75"/>
               <?php } else { ?>
                   <div class="image_ext"><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png"  width="75"/>
                       <p class="img_show"><?php echo $arr; ?></p>
                   </div>
               <?php } ?>
           </a>
                <p class="img_name"><?php echo $name; ?></p>
                <span class="overlay" style="display: none;"> <span class="updone">100%</span></span>
                <!--<input type="hidden" value="<?php echo $name; ?>" name="fileToUpload[]">-->
                </span> </div>
                                        <?php } ?>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                      <div id="deletedImagesDiv"></div>
                      <?php }  }?>
                </div>
           <div class="clr"> </div>
           </div>

                    <!-- end new code -->
                    <div class="clr"> </div><br/>

                    <div class="clr"> </div><br/>

                    <input type="hidden" name="hdn_budget_campaign_id" id="hdn_budget_campaign_id" value="<?php
                           if (isset($campaign_data[0]['budget_campaign_id']) && $campaign_data[0]['budget_campaign_id'] != '') {
                               echo $campaign_data[0]['budget_campaign_id'];
                           }
                           ?>"/>

                    <input type="hidden" name="hdn_campaign_id" id="hdn_campaign_id" value="<?php
                           if (isset($campaign_data[0]['campaign_id']) && $campaign_data[0]['campaign_id'] != '') {
                               echo $campaign_data[0]['campaign_id'];
                           }
                           ?>"/>

                    <input type="hidden" name="hdn_auto_gen_id" id="hdn_auto_gen_id" value="<?php
                           if (isset($campaign_data[0]['campaign_auto_id']) && $campaign_data[0]['campaign_auto_id'] != '') {
                               echo $campaign_data[0]['campaign_auto_id'];
                           }
                           ?>"/>
                    <div class="modal-footer">
                        <div class="text-center">
                            <input type="submit" class="btn btn-primary" id="camp_submit_btn" value="
                            <?php if(isset($submit_btn_val)){echo $submit_btn_val;}?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<div class="modal fade modal-image" id="modalGallery" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onClick="$('#modalGallery').modal('hide');" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Uploads</h4>
            </div>
            <div class="modal-body" id="modbdy">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onClick="$('#modalGallery').modal('hide');">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
    $(document).ready(function () {
        $('#frm_request_budget').parsley();
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
        $('#budget_for_product').trigger('chosen:updated');

       /* $(document).on("hidden.bs.modal", ".modal:not(.local-modal)", function (e) {
            $(e.target).removeData("bs.modal").find(".modal-content").empty();
        });
        */
    });
                      $(function ()
                                    {
                                        $('#start_date').datepicker({
                                            autoclose: true,
                                            startDate: new Date(),
                                        }).on('changeDate', function (selected) {
                    startDate = new Date(selected.date.valueOf());
                    startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                                            $('#end_date').datepicker('setStartDate', startDate);
                                        });
                                        $('#end_date')
                                                .datepicker({autoclose: true, startDate: new Date()
                                                });

                    });
</script>
<script>
    /* image upload */
    $('.delimg').on('click', function () {

        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');
        var delete_meg ="<?php echo $this->lang->line('delete_request_item');?>";

        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: delete_meg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                        $('#confirm-id').on('hidden.bs.modal', function () {
                            $('body').addClass('modal-open');
                        });
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {
                        $('#deletedImagesDiv').append("<input type='hidden' name='softDeletedImages[]' value='" + str1 + "'> <input type='hidden' name='softDeletedImagesUrls[]' value='" + dataPath + '/' + imgName + "'>");
                        $('#' + divId).remove();
                        $('#confirm-id').on('hidden.bs.modal', function () {
                            $('body').addClass('modal-open');
                        });
                        dialog.close();
                    }

                }]
            });



    });

    var config = {
        support: "*", // Valid file formats
        form: "demoFiler", // Form ID
        dragArea: "dragAndDropFiles", // Upload Area ID
        uploadUrl: "<?php echo $sales_view; ?>/upload_file"				// Server side upload url
    }
    $(document).ready(function () {
        //initMultiUploader(config);
        var dropbox;
        var oprand = {
            dragClass: "active",
            on: {
                load: function (e, file) {
                    // check file size
                    if (parseInt(file.size / 256) > 20480) {
                        var delete_meg ="<?php echo lang("file"); ?> \"" + file.name + "\" <?php echo lang('too_big_size'); ?>";
                        BootstrapDialog.show(
                            {
                                title: '<?php echo $this->lang->line('Information');?>',
                                message: delete_meg,
                                buttons: [{
                                    label: '<?php echo $this->lang->line('ok');?>',
                                    action: function(dialog) {
                                        dialog.close();
                                    }
                                }]
                            });

                        return false;
                    }

                    create_box(e, file);
                },
            }
        };
        FileReaderJS.setupDrop(document.getElementById('dragAndDropFiles'), oprand);
        var fileArr = [];

        create_box = function (e, file) {
            var rand = Math.floor((Math.random() * 100000) + 3);
            var imgName = file.name; // not used, Irand just in case if user wanrand to print it.
            var src = e.target.result;
            var xhr = new Array();
            xhr[rand] = new XMLHttpRequest();
//            console.log(xhr[rand]);

            var filename = file.name;
            var fileext = filename.split('.').pop();
//            console.log(fileext);
            xhr[rand].open("post", "<?php echo base_url('/RequestBudget/upload_file') ?>/" + fileext, true);

            xhr[rand].upload.addEventListener("progress", function (event) {
                //console.log(event);
                if (event.lengthComputable) {
                    $(".progress[id='" + rand + "'] span").css("width", (event.loaded / event.total) * 100 + "%");
                    $(".preview[id='" + rand + "'] .updone").html(((event.loaded / event.total) * 100).toFixed(2) + "%");
                }
                else {
                    var delete_meg ="<?php echo lang("fail_file_upload"); ?>";
                    BootstrapDialog.show(
                        {
                            title: '<?php echo $this->lang->line('Information');?>',
                            message: delete_meg,
                            buttons: [{
                                label: '<?php echo $this->lang->line('ok');?>',
                                action: function(dialog) {
                                    dialog.close();
                                }
                            }]
                        });
                }
            }, false);

            xhr[rand].onreadystatechange = function (oEvent) {
                var img = xhr[rand].response;
                var url = '<?php echo base_url(); ?>';
                if (xhr[rand].readyState === 4) {
                    var filetype = img.split(".")[1];
                    if (xhr[rand].status === 200) {
                        var template = '<div class="eachImage" id="' + rand + '">';
                        var randtest = 'delete_row("' + rand + '")';
                        template += '<a id="delete_row" class="remove_drag_img" onclick=' + randtest + '>×</a>';
                        if (filetype == 'jpg' || filetype == 'jpeg' || filetype == 'png' || filetype == 'gif') {
                            template += '<span class="preview" id="' + rand + '"><img src="' + src + '"><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                        } else {
                            template += '<span class="preview" id="' + rand + '"><div class="image_ext"><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + filetype + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                        }
                        template += '<input type="hidden" name="fileToUpload[]" value="' + img + '">';
                        template += '</span>';
                        $("#dragAndDropFiles").append(template);
                    }
                }
            };

            xhr[rand].setRequestHeader("Content-Type", "multipart/form-data");
            xhr[rand].setRequestHeader("X-File-Name", file.fileName);
            xhr[rand].setRequestHeader("X-File-Size", file.fileSize);
            xhr[rand].setRequestHeader("X-File-Type", file.type);

            // Send the file (doh)
            xhr[rand].send(file);

        }
        upload = function (file, rand) {
        }

    });


    //image upload
    function showimagepreview(input)
    {
        console.log(input);
        $('.upload_recent').remove();
        var url = '<?php echo base_url();?>';
        $.each(input.files, function(a,b){
            var rand = Math.floor((Math.random()*100000)+3);
            var arr1 = b.name.split('.');
            var arr= arr1[1].toLowerCase();
            var filerdr = new FileReader();
            var img = b.name;
            filerdr.onload = function(e) {
                var template = '<div class="eachImage upload_recent" id="'+rand+'">';
                var randtest = 'delete_row("' +rand+ '")';
                template += '<a id="delete_row" class="remove_drag_img" onclick='+randtest+'>×</a>';
                if(arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif'){
                    template += '<span class="preview" id="'+rand+'"><img src="'+e.target.result+'"><p class="img_name">'+img+'</p><span class="overlay"><span class="updone"></span></span>';
                }else{
                    template += '<span class="preview" id="'+rand+'"><div class="image_ext"><img src="'+url+'/uploads/images/icons64/file-64.png"><p class="img_show">' + arr + '</p></div><p class="img_name">'+img+'</p><span class="overlay"><span class="updone"></span></span>';
                }
                template += '<input type="hidden" name="file_data[]" value="'+b.name+'">';
                template += '</span>';
                $('#dragAndDropFiles').append(template);
            }
            filerdr.readAsDataURL(b);

//           console.log(b.name);
        });
        //console.log(input.files[0]['name']);
        var maximum = input.files[0].size/1024;
        //alert(maximum);
    }
    function delete_row(rand) {
        jQuery('#' + rand).remove();
    }

    window.Parsley.addValidator('gteq',
        function (value, requirement) {
            return Date.parse($('#end_date input').val()) >= Date.parse($('#start_date input').val());
        }, 32)
        .addMessage('en', 'le', '<?php echo $this->lang->line('should_be_less_or_equal');?>');

    function toggle_show(className, obj) {
        var $input = $(obj);
        if ($input.prop('checked'))
            $(className).show();
        else
            $(className).hide();
    }
    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('costModel').modal('hide');
        $('#modalGallery').modal('show');
    });

    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
    });

    $(function () {
        var availableBranch = [
            <?php
            if (isset($type_compaign) && count($type_compaign) > 0) {
                $count = 0;
                foreach ($type_compaign as $campaign_type) {
                    $count++;
                    echo '"' . addslashes($campaign_type['camp_type_name']) . '"';
                    if ($count != count($type_compaign)) {
                        echo ", ";
                    }
                }
            }
            ?>
        ];
        $("#campaign_type_id").autocomplete({
            source: availableBranch
        });
    });

    $('#campaign_description').summernote({
        disableDragAndDrop : true,
        height: 150, //set editable area's height
        codemirror: {// codemirror options
            theme: 'monokai'
        },
        focus: true
    });
    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
    });
</script>

