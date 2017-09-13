<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'KnowledgeBase/saveSubCategory';
$formPath = $project_view . '/' . $formAction;
?>    
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><div class="title"><?= $this->lang->line('add_sub_category') ?></div></h4>
        </div>
        <form id="from-model" method="post" class="add_sub" action="<?php echo base_url($formPath); ?>" data-parsley-validate>
            <div class="modal-body">
                <div class = "form-group row">
                    <div class = "col-sm-12">
                        <input type="text" class="form-control" placeholder="<?php echo lang('sub_category_name') ?> *" id="sub_category_name" name="sub_category_name"  required>
                        <span id="cost_name_error" class="alert-danger"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-xs-12 col-sm-12">
                        <div class = "row">
                            <div class = "col-sm-12 form-group">
                                <select class="form-control" name="main_category_id" id="main_category_id" required>
                                    <option value=""><?php echo lang('sel_cat'); ?> *</option>
                                    <?php
                                    if (!empty($main_category)) {
                                        foreach ($main_category as $row) {
                                            ?>
                                            <option value="<?php echo $row['main_category_id']; ?>"><?php echo $row['category_name']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <span id="cost_type_error" class="alert-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-xs-12 col-sm-12">
                        <div class = "row">
                            <div class="col-sm-6">
                                <?php
                                if (!empty($editRecord[0]['related_product'])) {
                                    $client_time_id = "client_amount_show";
                                } else {
                                    $client_time_id = "client_amount_hide";
                                }
                                ?>
                                <div class="form-group">
                                    <input data-toggle="toggle" data-onstyle="success" type="checkbox"  id="client_visible" name="client_visible" onChange="toggle_show(<?php echo "'#" . $client_time_id . "'"; ?>, this)" <?= !empty($editRecord[0]['client_visible']) ? 'checked="checked"' : '' ?> data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"/>
                                    <label for="client_visible">
                                        <?php echo lang('client_visible'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class = "col-sm-6 form-group">

                                <select class="form-control" name="article_owner" id="article_owner" >
                                    <option value=""><?php echo lang('article_owner'); ?></option>
                                    <?php foreach ($user as $user_data) { ?>
                                        <option value="<?php echo $user_data['login_id']; ?>" <?php echo ($articleOwner == $user_data['login_id']) ? 'selected' : ''; ?>><?php echo $user_data['firstname'] . ' ' . $user_data['lastname']; ?></option>
                                    <?php } ?>
                                </select>

                                <span id="cost_type_error" class="alert-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12  col-xs-12 col-sm-12">
                        <div class = "row">
                            <div class="col-sm-6">
                                <?php
                                if (!empty($editRecord[0]['related_product'])) {
                                    $related_time_id = "related_amount_show";
                                } else {
                                    $related_time_id = "related_amount";
                                }
                                ?>
                                <div class="form-group">

                                    <input data-toggle="toggle" data-onstyle="success"  type="checkbox"  id="product_related" name="product_related" onChange="toggle_show(<?php echo "'#" . $related_time_id . "'"; ?>, this)" <?= !empty($editRecord[0]['product_related']) ? 'checked="checked"' : '' ?> data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"/>

                                    <label for="product_related">
                                        <?php echo lang('product_related'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class = "col-sm-6">
                                <div id="<?php echo $related_time_id; ?>">
                                    <select class="form-control chosen-select product_related" multiple="true"  name="product_id[]" id="product_id" data-placeholder="<?= $this->lang->line('select_option') ?>">
                                        <?php if (!empty($product_info) && count($product_info) > 0) { ?>
                                            <?php foreach ($product_info as $row) { ?>
                                                <option value="<?php echo $row['product_id']; ?>" <?php
                                                if (!empty($product_data) && in_array($row['product_id'], $product_data)) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>><?php echo $row['product_name']; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                    </select>
                                </div>
                                <span id="cost_type_error" class="alert-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <div class="text-center">
                    <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?= $this->lang->line('add_sub_category') ?>" />
                </div>
                <div class="clr"> </div>
            </div>
        </form>
    </div>
    <script>
        $('#from-model').parsley();
        $('.product_related').trigger('chosen:updated');
        $('.client_visible').trigger('chosen:updated');
        $('#client_visible').bootstrapToggle();
        $('#product_related').bootstrapToggle();
        $('#sub_cat').bootstrapToggle();

        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    </script>
    <script>
        $('#gallery-btn').click(function () {
            $('#modbdy').load($(this).attr('data-href'));
            $('costModel').modal('hide');
            $('#modalGallery').modal('show');
        });

        $('body').delegate('#submit_btn', 'click', function () {

            if ($('.add_sub').parsley().isValid()) {
                //disabled submit button after submit
                $('input[type="submit"]').prop('disabled', true);
                $('.add_sub').submit();
            }

        });
        $("#main_category_id").change(function () {
            var id = this.value;
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Support/KnowledgeBase/checkVisible'); ?>',
                data: 'id=' + id,
                success: function (data) {
                    var visible = $.parseJSON(data);
                    if (visible == 0) {
                        console.log(visible);
                        $('#client_visible').attr("onchange", "toggle_show('#client_amount_hide', this)");
                        $("#client_visible").closest("div").removeClass('btn-success').addClass('btn-default off');
                        $("#client_visible").attr("disabled","true");
                        $("#client_visible").bootstrapToggle();

                    } else {
                        $("#client_visible").removeAttr("disabled");
                        $("#client_visible").bootstrapToggle();
                    }
                }

            });
        });

        $("#main_category_id").change(function () {
            var id = this.value;
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Support/KnowledgeBase/getProducts'); ?>',
                data: 'id=' + id,
                success: function (data) {
                    var productid = $.parseJSON(data);
                    // console.log(productid.product);
                    if (productid.product == 0) {
                        $("#product_related").closest("div").removeClass('btn-success').addClass('btn-default off');
                        $("#related_amount").css("display", "none");
                    } else {
                        $('#product_id option').remove();
                        $("#product_related").closest("div").removeClass('btn-default off').addClass('btn-success on');
                        $('#product_related').prop('checked', true);
                        $("#related_amount").css("display", "block");
                        $.each(productid.productnew_info, function (key, val) {

                            $('#product_id').append('<option value="' + val.product_id + '" >' + val.product_name + '</option>')
                            $.each(productid.product, function (key, val) {
                                $('#product_id option[value="' + val[0].product_id + '"]').attr("selected", "selected");
                            });
                        });
                        $('#product_id').trigger('chosen:updated');

                    }
                }

            });
        });
    </script>