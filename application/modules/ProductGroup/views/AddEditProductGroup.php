<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction =!empty($editRecord)? 'updatedata': 'insertdata';
$path = $crnt_view . '/' . $formAction;
?>

<div class="modal-dialog modal-lg modal-md">
    <div class="modal-content prodgroupmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" title="<?php echo lang('close'); ?>" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                <div class="title">
                    <?php
                    if ($formAction == "insertdata") { ?>
                        <?= lang('create_product_group') ?>
                    <?php } else { ?>
                        <?= lang('update_product_group') ?>
                    <?php } ?>
                </div>
            </h4>
        </div>
        <?php
        $attributes = array("name" => "frmproductgrp", "id" => "frmproductgrp", "data-parsley-validate" => "");
        echo form_open_multipart($path, $attributes);
        ?>
        <div class="modal-body">
            <div class="form-group">
                <input name="prod_grp_id" type="hidden" value="<?=!empty($editRecord[0]['product_id'])? $editRecord[0]['product_id'] : '' ?>" />
            </div>

            <!-- name -->
            <div class="form-group">
                <label for="product group name">
                    <?= lang('product_group_name') ?>
                    <span>*</span></label>
                <input class="form-control" name="prod_grp_name" data-parsley-maxlength="30" <?php ?> placeholder="<?= lang('product_group_name') ?>" type="text" value="<?php
                if ($formAction =="insertdata") { echo set_value('product_group_name'); ?><?php } else { ?><?=!empty($editRecord[0]['product_group_name'])? htmlentities($editRecord[0]['product_group_name']): ''?><?php } ?>" required="" />
            </div>

            <!-- description -->
            <div class="form-group">
                <label for="product group description">
                    <?= lang('product_group_desc') ?>
                </label>
                <textarea class="form-control textarea-resizenone" name="prod_grp_desc" id="prod_grp_desc"  placeholder="<?= lang('product_group_desc') ?>" value="" ><?php if ($formAction =="insertdata") { echo set_value('product_group_description'); ?><?php } else { ?><?=!empty($editRecord[0]['product_group_description'])? $editRecord[0]['product_group_description']: '' ?><?php } ?>
                </textarea>
            </div>

            <!-- related products -->
            <div class="form-group">
                <label for="product">
                    <?= lang('product') ?>
                </label>
                <div class="listNav" id="productnav">
                    <div class="alphabets"> 
                        <a href="javascript:void(0)" class="letter">All</a> 
                        <a href="javascript:void(0)" class="letter">A</a> 
                        <a href="javascript:void(0)" class="letter">B</a> 
                        <a href="javascript:void(0)" class="letter">C</a> 
                        <a href="javascript:void(0)" class="letter">D</a> 
                        <a href="javascript:void(0)" class="letter">E</a> 
                        <a href="javascript:void(0)" class="letter">F</a> 
                        <a href="javascript:void(0)" class="letter">G</a> 
                        <a href="javascript:void(0)" class="letter">H</a> 
                        <a href="javascript:void(0)" class="letter">I</a> 
                        <a href="javascript:void(0)" class="letter">J</a> 
                        <a href="javascript:void(0)" class="letter">K</a> 
                        <a href="javascript:void(0)" class="letter">L</a> 
                        <a href="javascript:void(0)" class="letter">M</a> 
                        <a href="javascript:void(0)" class="letter">N</a> 
                        <a href="javascript:void(0)" class="letter">O</a> 
                        <a href="javascript:void(0)" class="letter">P</a> 
                        <a href="javascript:void(0)" class="letter">Q</a> 
                        <a href="javascript:void(0)" class="letter">R</a> 
                        <a href="javascript:void(0)" class="letter">S</a> 
                        <a href="javascript:void(0)" class="letter">T</a> 
                        <a href="javascript:void(0)" class="letter">U</a> 
                        <a href="javascript:void(0)" class="letter">V</a> 
                        <a href="javascript:void(0)" class="letter">W</a> 
                        <a href="javascript:void(0)" class="letter">X</a> 
                        <a href="javascript:void(0)" class="letter">Y</a> 
                        <a href="javascript:void(0)" class="letter">Z</a> 
                        <a href="javascript:void(0)" class="letter">0-9</a> 
                    </div>
                </div>
                <div class="row bd-hidden-xs">
                    <div class="col-sm-1 col-md-1 bd-prod-md"><label><?= lang('product_name') ?></label></div> 
                    <div class="pull-left bd-prod-lg"><label><?= lang('product_status') ?></label></div>
                    <div class="col-sm-1 col-md-1"><label><?= lang('product_spu') ?></label></div>
                    <div class="col-sm-1 col-md-1"><label><?= lang('discount_option') ?></label></div>
                    <div class="col-sm-1 col-md-1"><label><?= lang('discount') ?></label></div>
                    <div class="col-sm-1 col-md-1"><label><?= lang('discounted_amount') ?></label></div>
                    <div class="col-sm-1 col-md-1"><label><?= lang('EST_LABEL_QUANTITY') ?></label></div>
                    <div class="col-sm-1 col-md-1 bd-prod-sm"><label><?= lang('product_wise_total') ?></label></div>
                    <div class="pull-left fetr-selct-tax"><label><?= lang('EST_LABEL_TAX') ?></label></div>
                    <div class="col-sm-1 col-md-1"><label><?= lang('PRODUCT_REQUIRED_TAX_PERCENTAGE') ?></label></div>
                </div>
                <ul class="names nav">
                    <?php
                    $prdct_grp_data_id = array();
                    $product_checked_sts = $prdct_tax_id = array();
                    $disocunt_arr = array('prsnt', 'amt');
                    if ($formAction == 'updatedata') {
                        foreach ($product_grp_rel_info as $product_grp_data) {
                            $prdct_grp_data_id[] =
                                    $product_grp_data['product_id'];
                            $prdct_tax_id[$product_grp_data['product_id']][] =
                                    $product_grp_data['product_tax_id'];
                            $prdct_tax_percent[$product_grp_data['product_id']] =
                                    $product_grp_data['tax_percentage'];
                            $product_checked_sts[$product_grp_data['product_id']] =
                                    $product_grp_data['product_group_status'];
                            $discountOption[$product_grp_data['product_id']] =
                                    $product_grp_data['discount_option'];
                            $discountvalue[$product_grp_data['product_id']] =
                                    $product_grp_data['product_discount'];
                            $productQuantity[$product_grp_data['product_id']] =
                                    $product_grp_data['product_qty'];
                            $productTotal[$product_grp_data['product_id']] =
                                    $product_grp_data['product_total'];
                        }
                    }

                    $checked = "";
                    $selected = "";
                    foreach ($product_info as $row) {
                        $ess_checked = "";
                        $acc_checked = "";

                        //product checkbox
                        if (in_array($row['product_id'], $prdct_grp_data_id)) {
                            $checked = 'checked="checked"';
                        } else {
                            $checked = '';
                        }

                        //radio button
                        if (isset($product_checked_sts[$row['product_id']]) && $product_checked_sts[$row['product_id']] == 1) {
                            $ess_checked = 'checked="checked"';
                        }
                        if (isset($product_checked_sts[$row['product_id']]) && $product_checked_sts[$row['product_id']] == 0) {
                            $acc_checked = 'checked="checked"';
                        }
                        ?>
                        <li class="hidden">
                            <div class="row" id="show_products"> 
                                <!-- Products -->
                                <div class="col-sm-1 bd-width-100 col-md-1 bd-form-group bd-prod-md">
                                    <label class="bd-visible-xs">Product Name</label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="products[]" <?php echo $checked; ?> value="<?php echo $row['product_id']; ?>" id="product<?php echo $row['product_id']; ?>">
                                        <?php echo $row['product_name']; ?> </label>
                                    <input type="hidden" name="hdn_product_<?php echo $row['product_id']; ?>" id="hdn_product_<?php echo $row['product_id']; ?>" value="<?php echo $row['product_name']; ?>"/>
                                </div>

                                <div class="pull-left bd-prod-lg bd-width-100"> <!-- Essential -->
                                    <label class="bd-visible-xs">Product Status</label>
                                    <div class="pull-left bd-form-group essential-ftr">

                                        <label class="radio-inline">
                                            <input type="radio" <?php echo $ess_checked; ?> name="product_group_status_<?php echo $row['product_id'] ?>" value="1" checked>
                                            <?= lang('essential') ?>
                                        </label>
                                    </div>

                                    <!-- Accessories -->
                                    <div class="pull-left bd-form-group">
                                        <label class="radio-inline">
                                            <input type="radio" <?php echo $acc_checked; ?> name="product_group_status_<?php echo $row['product_id'] ?>" value="0"  >
                                            <?= lang('accessories') ?>
                                        </label>
                                    </div><div class="clr"></div></div>
                                <!-- Sales Price Unit -->
                                <div class="col-sm-1 col-md-1 bd-form-group bd-width-100">
                                    <label class="bd-visible-xs"><?= lang('product_spu') ?></label>
                                    <input type="text" readonly  class="form-control spu" name="spu_<?php echo $row['product_id'] ?>" id="spu_<?php echo $row['product_id'] ?>" value="<?=!empty($row['sales_price_unit'])? $row['sales_price_unit'] : '' ?>">
                                </div>

                                <!-- Discount Option -->
                                <div class="col-sm-1 col-md-1 bd-form-group bd-width-100">
                                    <label class="bd-visible-xs"><?= lang('discount_option') ?></label>
                                    <select  name="discount_Opt_<?php echo $row['product_id'] ?>" id="discount_Opt_<?php echo $row['product_id'] ?>" class="chosen-select discount_option form-control" data-parsley-trigger="change" data-placeholder="Select" required>
                                        <?php
                                        if (isset($discountOption[$row['product_id']]) && $discountOption[$row['product_id']] =='prsnt') { ?>
                                            <option value="prsnt" selected="selected">%</option>
                                        <?php } else { ?>
                                            <option value="prsnt">%</option>
                                            <?php
                                        } if (isset($discountOption[$row['product_id']]) && $discountOption[$row['product_id']] == 'amt') { ?>
                                            <option value="amt" selected="selected"><?php echo $currencyInfo['symbol']; ?></option>
                                        <?php } else { ?>
                                            <option value="amt"><?php echo $currencyInfo['symbol']; ?></option>
                                        <?php } ?>

                                        ?>
                                    </select>
                                </div>

                                <!-- Discount Value -->
                                <div class="col-sm-1 col-md-1 bd-form-group bd-width-100">
                                    <label class="bd-visible-xs"><?= lang('discount') ?></label>
                                    <input type="text"  class="form-control prod_discount" name="prod_discount_<?php echo $row['product_id'] ?>" id="prod_discount_<?php echo $row['product_id'] ?>" value="<?php
                                    if ($formAction == "insertdata") { echo set_value('product_discount'); ?><?php } else { ?><?=!empty($discountvalue[$row['product_id']]) ? $discountvalue[$row['product_id']] : '' ?><?php } ?>" class="prod_discount">
                                </div>

                                <!-- Discounted Price -->
                                <div class="col-sm-1 col-md-1 bd-form-group bd-width-100">
                                    <label class="bd-visible-xs"><?= lang('discounted_amount') ?></label>
                                    <input type="text"  class="form-control" name="prod_discounted_price_<?php echo $row['product_id'] ?>" id="prod_discounted_price_<?php echo $row['product_id'] ?>" value="" readonly>
                                </div>

                                <!-- Quantity -->
                                <div class="col-sm-1 col-md-1 bd-form-group bd-width-100">
                                    <label class="bd-visible-xs"><?= lang('EST_LABEL_QUANTITY') ?></label>
                                    <input type="number"  class="quantity form-control" name="prod_qty_<?php echo $row['product_id'] ?>" id="prod_qty_<?php echo $row['product_id'] ?>" value="<?php
                                    if ($formAction =="insertdata") { echo 1; ?><?php } else { ?><?=!empty($productQuantity[$row['product_id']])? $productQuantity[$row['product_id']] : 1 ?><?php } ?>" min="1" class="quantity">
                                    <input type="hidden" style="width:30px;" name="total_<?php echo $row['product_id'] ?>" id="total_<?php echo $row['product_id'] ?>" class="<?php
                                    echo (in_array($row['product_id'], $prdct_grp_data_id))? 'hidden_show_tot' : ''; ?>" value="">
                                </div>

                                <!-- Total Amount -->
                                <div class="col-sm-1 col-md-1 bd-form-group bd-prod-sm bd-width-100">
                                    <label class="bd-visible-xs"><?= lang('product_wise_total') ?></label>
                                    <input type="text" class="form-control <?php
                                    echo (in_array($row['product_id'], $prdct_grp_data_id))? 'show_prod_total': '';?>" name="prod_total_<?php echo $row['product_id'] ?>" id="prod_total_<?php echo $row['product_id'] ?>" value="<?php
                                           if ($formAction == "insertdata") { echo set_value('product_total');?><?php } else { ?><?=!empty($productTotal[$row['product_id']]) ? $productTotal[$row['product_id']] : '' ?><?php } ?>" readonly>
                                </div>

                                <!-- Tax Name -->
                                <div class="form-group bd-form-group pull-left fetr-selct-tax bd-width-100">
                                    <label class="bd-visible-xs"><?= lang('EST_LABEL_TAX') ?></label>
                                    <select class="selectpicker form-control chosen-select tax_name" name="tax_id<?php echo $row['product_id'] ?>" id="tax_id_<?php echo $row['product_id']; ?>" data-parsley-errors-container="#taxErrors<?php echo $row['product_id']; ?>">
                                        <option value="" selected="">
                                            <?= $this->lang->line('select_tax') ?>
                                        </option>
                                        <?php
                                        foreach ($tax_info as $row1) {
                                            ?>
                                            <?php
                                            if (in_array($row1['tax_id'], $prdct_tax_id[$row['product_id']])) { ?>
                                                <option value="<?php echo $row1['tax_id']; ?>" selected="selected" ><?php echo $row1['tax_name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $row1['tax_id']; ?>" ><?php echo $row1['tax_name']; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <span id="taxErrors<?php echo $row['product_id']; ?>"></span> </div>

                                <!-- Tax Percentage -->
                                <div class="col-sm-1 col-md-1 bd-form-group bd-width-100">
                                    <label class="bd-visible-xs"><?= lang('PRODUCT_REQUIRED_TAX_PERCENTAGE') ?></label>
                                    <input type="text"  name="prod_tax_val_<?php echo $row['product_id'] ?>" id="prod_tax_val_<?php echo $row['product_id'] ?>" value="<?=!empty($prdct_tax_percent[$row['product_id']]) ? $prdct_tax_percent[$row['product_id']] : ''
                                    ?>" class="prod_tax form-control" readonly>
                                    <input type="hidden" style="width:30px;" name="discounted_taxed_val_<?php echo $row['product_id'] ?>" id="discounted_taxed_val_<?php echo $row['product_id'] ?>" value="" class="discounted_tax <?php
                                    echo (in_array($row['product_id'], $prdct_grp_data_id)) ? 'hidden_show_tax' : '';?>">
                                </div>
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <!-- Total Amount -->
            <div class="col-md-5 col-md-offset-7">
                <div class="form-group row">
                    <div class="col-sm-3"><label><?= lang('total_amount') ?></label></div>
                    <div class="col-sm-9 nopadding"> <input type="text" name="total_amt" class="form-control" id="total_amt" value="<?php if ($formAction == "insertdata") { echo set_value('product_group_total_amt'); ?><?php } else { ?><?=!empty($editRecord[0]['product_group_total_amt'])? $editRecord[0]['product_group_total_amt']: '' ?><?php } ?>" readonly></div>
                    <div class="clr"></div>
                </div>

                <!-- Discounted Price -->
                <div class="form-group row">
                    <div class="col-sm-3"><label><?= lang('discounted_amount') ?></label></div>
                    <div class="col-sm-9 nopadding"> <input type="text" name="discounted_amt" class="form-control" id="discounted_amt" value="<?php if ($formAction == "insertdata") { echo set_value('product_group_discounted_amt'); ?><?php } else { ?><?=!empty($editRecord[0]['product_group_discounted_amt']) ? $editRecord[0]['product_group_discounted_amt'] : '' ?><?php } ?>" readonly></div>
                    <div class="clr"></div>
                </div>

            </div>

            <!-- Tax Calculated Price -->
            <div class="form-group">
                <input type="hidden" name="taxed_amt" class="form-control" id="taxed_amt" value="<?php if ($formAction == "insertdata") { echo set_value('product_group_tax_amt'); ?><?php } else { ?><?=!empty($editRecord[0]['product_group_tax_amt'])? $editRecord[0]['product_group_tax_amt']: '' ?><?php } ?>" readonly>
            </div>

            <!-- status -->
            <div class="form-group">
                <label for="status">
                    <?= lang('status') ?>
                </label>
                <?php
                $options = array('1' => lang('active'), '0' => lang('inactive'));
                $name = "status";
                if ($formAction == "insertdata") {
                    $selected = 1;
                } else {
                    $selected = $editRecord[0]['status'];
                }
                echo dropdown($name, $options, $selected);
                ?>
            </div>
        </div>
        <div class="modal-footer">
            <center>
                <input name="prod_grp_id" type="hidden" value="<?=!empty($editRecord[0]['product_group_id'])? $editRecord[0]['product_group_id'] : '' ?>" />
                <input type="button" class="btn btn-primary" onclick="submitProductGroup()" name="product_group_submit" id="product_group_submit_btn" value="<?=
                       ($formAction == "insertdata") ? lang('create_product_group') : lang('update_product_group') ?>" tabindex="25"/>
            </center>
        </div>
        <?php form_close() ?>
    </div>
</div>
<script>
    //Show Product Lising on form submission
    function submitProductGroup()
    {
        if ($('#frmproductgrp').parsley().validate())
        {

            var message_str = '';
            message_str = '<h4><?php echo lang('CONFIRM_ADD_PRODUCT_TO_GROUP'); ?></h4>';
            message_str += '<ul class="fa-ul">';
            var checkboxProductArr = [];
            $('input[name="products[]"]:checked').each(function ()
            {

                checkboxProductArr.push($('#hdn_product_' + $(this).val()).val());
                message_str += '<li><i class="fa-li fa fa-check-square"></i>' + $('#hdn_product_' + $(this).val()).val() + '</li>';
            });

            message_str += '</ul>';

            if (checkboxProductArr.length > 0)
            {
                BootstrapDialog.show(
                    {
                        title: '<?php echo $this->lang->line('Information');?>',
                        message: message_str,
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
                                $('#frmproductgrp').submit();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                                dialog.close();

                            }
                        }]
                    });
            } else
            {
                $('#frmproductgrp').submit();
            }

        }
    }
    function Calculate(thisCheck, productId) {
        var spu = $("#spu_" + productId).val();
        var quantity = $("#prod_qty_" + productId).val();
        var hidden = spu * quantity;
        if (thisCheck.is(':checked')) {
            //Add Class on Products Checked
            $("#spu_" + productId).addClass("show_total"); //for spu
            $("#prod_qty_" + productId).addClass("show_prod_qty"); //for quantity
            $("#prod_total_" + productId).addClass("show_prod_total");// for product total
            $("#total_" + productId).addClass("hidden_show_tot");//for hidden total
            $("#discounted_taxed_val_" + productId).addClass("hidden_show_tax");

            $("#total_" + productId).val(hidden);
            if ($("#prod_total_" + productId).val() == '') {
                $("#prod_total_" + productId).val(hidden);
            }

            var prod_total = $("#prod_total_" + productId).val();
            var prod_tax_val = $("#prod_tax_val_" + productId).val();
            var discounted_tax_val = prod_total * (prod_tax_val / 100);
            $("#discounted_taxed_val_" + productId).val(discounted_tax_val);

            //Total Amount: Start
            var total_amount = 0;
<?php if (!isset($editRecord)) { ?>
                $(".hidden_show_tot").each(function () {
                    total_amount = parseFloat(total_amount) + parseFloat($(this).val());
                    $("#total_amt").val(total_amount.toString().match(/^\d+(?:\.\d{0,2})?/));
                });

<?php } else { ?>
                var editTimeTotalAmt = 0;
                editTimeTotalAmt = parseFloat($("#total_amt").val()) + parseFloat($("#total_" + productId).val());
                $("#total_amt").val(editTimeTotalAmt.toString().match(/^\d+(?:\.\d{0,2})?/));

<?php } ?>
            //Total Amount: End

            var discounted_price_total = 0;
            $(".show_prod_total").each(function () {
                if ($(this).val() != '') {
                    discounted_price_total = parseFloat(discounted_price_total) + parseFloat($(this).val());
                    $("#discounted_amt").val(discounted_price_total.toString().match(/^\d+(?:\.\d{0,2})?/));
                } else {
                    $("#discounted_amt").val(discounted_price_total.toString().match(/^\d+(?:\.\d{0,2})?/));
                }

            });

            //Total Tax: Start
            var total_taxed_amount = 0;
            var editTimeTaxAmt = 0;
            <?php if (!isset($editRecord)) { ?>
                $(".hidden_show_tax").each(function () {
                    if ($(this).val() != '') {

                        total_taxed_amount = parseFloat(total_taxed_amount) + parseFloat($(this).val());
                        $("#taxed_amt").val(total_taxed_amount.toString().match(/^\d+(?:\.\d{0,2})?/));
                    }
                });
            <?php } else { ?>
                editTimeTaxAmt = parseFloat($("#taxed_amt").val()) + parseFloat($("#discounted_taxed_val_" + productId).val());
                $("#taxed_amt").val(editTimeTaxAmt.toString().match(/^\d+(?:\.\d{0,2})?/));

            <?php } ?>
            //Total Tax: End

            // Tax Validation: Start
            if ($("#tax_id_" + productId).val() == '') {
                $("#tax_id_" + productId).attr("required", "required");
            } else {
                $("#tax_id_" + productId).removeAttr("required");
            }
            $("#tax_id_" + productId).on("change", function () {
                if ($("#tax_id_" + productId).val() == '') {
                    $("#tax_id_" + productId).attr("required", "required");
                } else {
                    $("#tax_id_" + productId).removeAttr("required");
                }
            });
            // Tax Validation: End
        } else {
            $("#tax_id_" + productId).removeAttr("required");

            //Remove Class on Products Checked
            $("#spu_" + productId).removeClass("show_total");
            $("#prod_qty_" + productId).removeClass("show_prod_qty");
            $("#prod_total_" + productId).removeClass("show_prod_total");
            $("#total_" + productId).removeClass("hidden_show_tot");
            $("#discounted_taxed_val_" + productId).removeClass("hidden_show_tax");


            var total_amt = $("#total_amt").val();
            var total_tax_amt = $("#taxed_amt").val();
            var total_discounted_amt = $("#discounted_amt").val();
            var discounted_amt = $("#prod_total_" + productId).val();

            <?php if (isset($editRecord)) { ?>
                var spu = $("#spu_" + productId).val();
                var quantity = $("#prod_qty_" + productId).val();
                var hidden = spu * quantity;
                $("#total_" + productId).val(hidden);
                var hidden_amt = $("#total_" + productId).val();

                var prod_tax_val = $("#prod_tax_val_" + productId).val();
                var discounted_tax_val = discounted_amt * (prod_tax_val / 100);

                $("#discounted_taxed_val_" + productId).val(discounted_tax_val);
                var hidden_tax = $("#discounted_taxed_val_" + productId).val();
                var total = parseFloat(total_amt) - parseFloat(hidden_amt);
                $("#total_amt").val(total.toString().match(/^\d+(?:\.\d{0,2})?/));
                var total_tax = total_tax_amt - hidden_tax;
                    if(total_tax > 0){
                    $("#taxed_amt").val(total_tax.toString().match(/^\d+(?:\.\d{0,2})?/));
                    }else{
                        $("#taxed_amt").val('0');
                    }

            <?php } else { ?>
                var hidden_amt = $("#total_" + productId).val();
                var total = parseFloat(total_amt) - parseFloat(hidden_amt);
                $("#total_amt").val(total.toString().match(/^\d+(?:\.\d{0,2})?/));
                var hidden_tax = $("#discounted_taxed_val_" + productId).val();
                var total_tax = total_tax_amt - hidden_tax;
                if(total_tax>0){
                    $("#taxed_amt").val(total_tax.toString().match(/^\d+(?:\.\d{0,2})?/));
                }else{
                    $("#taxed_amt").val('0');
                }
            <?php } ?>

             var total_discounted = parseFloat(total_discounted_amt) - parseFloat(discounted_amt);
            $("#discounted_amt").val(total_discounted.toString().match(/^\d+(?:\.\d{0,2})?/));
            $("#discounted_taxed_val_" + productId).val();
        }
    }
    
    $(document).ready(function () {
        $('#frmproductgrp').parsley();

        /* Products Navigation: Start */
        $(".names li").removeClass('hidden');
        $(".names li").addClass('show');

        $(".alphabets a").on("click", function () {
            var letter = $(this).html(); // get alphabet from link

            $(".names li .checkbox-inline").each(function () {
                var li_list = $.trim($(this).text().toUpperCase());
                if (letter == 'All') {
                    $(".names li").removeClass('hidden').addClass('show');
                }
                else
                {
                    if (letter == '0-9')
                    {
                        if ($.isNumeric(li_list.charAt(0)))
                        {
                            $(this).closest('li').removeClass('hidden').addClass('show');
                        }
                        else
                        {
                            $(this).closest('li').addClass("hidden");
                        }
                    } else
                    {
                        if (li_list.indexOf(letter) == 0) {
                            $(this).closest('li').removeClass('hidden').addClass('show');
                        }
                        else
                        {
                            $(this).closest('li').addClass("hidden");
                        }
                    }


                }
            });
        });
        /*Products Navigation: End */

        //WYSIWYG Editor
        $('#prod_grp_desc').summernote({
            height: 150, //set editable area's height
            codemirror: {// codemirror options
                theme: 'monokai'
            },
            focus: true
        });
        
        $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
    });
        
        /* Discount Option Change: Start */
        $('.discount_option').on('change', function () {
            var productId = $(this).attr('id').replace('discount_Opt_', '');
            var spu = $("#spu_" + productId).val();
            var discount_Opt = $(this).val();
            var prod_discount = $("#prod_discount_" + productId).val();

            var prod_qty = $("#prod_qty_" + productId).val();
            var total_hidden = parseFloat(spu) * parseFloat(prod_qty);
            var prod_dicounted_price;
            var prod_total;
            
            $("#total_" + productId).val(total_hidden);
            if (discount_Opt == 'prsnt') {
                if (prod_discount > 100) {
                    $("#prod_discount_" + productId).attr("data-parsley-min", "0");
                    $("#prod_discount_" + productId).attr("data-parsley-max", "100");
                    return false;
                } else {
                    if(prod_discount != ''){
                        prod_dicounted_price = spu - (spu * (prod_discount / 100));
                        prod_total = prod_qty * prod_dicounted_price;
                        $("#prod_discounted_price_" + productId).val(prod_dicounted_price);
                        $("#prod_total_" + productId).val(prod_total);
                    }else{
                        prod_total = spu * prod_qty;
                        $("#prod_total_" + productId).val(prod_total);
                    }
                }
            }
            else if (discount_Opt == 'amt') {
                if(parseFloat(prod_discount) > parseFloat(spu)){
                    $("#prod_discount_" + productId).attr("data-parsley-le", "#spu_"+productId);
                    return false;
                }else{
                    if(prod_discount != ''){
                        prod_dicounted_price = spu - prod_discount;
                        $("#prod_discounted_price_" + productId).val(prod_dicounted_price);
                        prod_total = prod_qty * prod_dicounted_price;

                        $("#prod_total_" + productId).val(prod_total);
                    }else{
                        prod_total = spu * prod_qty;
                        $("#prod_total_" + productId).val(prod_total);
                    }
                }
            }
            var total_amount = 0;
            var discounted_amount = 0;
            if ($('#product'+productId).is(':checked')) {
                $(".hidden_show_tot").each(function () {
                    total_amount = parseFloat(total_amount) + parseFloat($(this).val());
                    $("#total_amt").val(total_amount.toString().match(/^\d+(?:\.\d{0,2})?/));
                });
                $(".show_prod_total").each(function () {
                    discounted_amount = parseFloat(discounted_amount) + parseFloat($(this).val());
                    $("#discounted_amt").val(discounted_amount.toString().match(/^\d+(?:\.\d{0,2})?/));
                });
            }
        });
        /* Discount Option Change: End */

        /* Discount Value Change: Start */
        $('.prod_discount').on('change', function () {
            var productId = $(this).attr('id').replace('prod_discount_', '');
            var spu = $("#spu_" + productId).val();
            var discount_Opt = $("#discount_Opt_" + productId).val();
            var prod_discount = $("#prod_discount_" + productId).val();
            var prod_qty = $("#prod_qty_" + productId).val();
            var prod_tax_percent = $("#prod_tax_val_" + productId).val();

            var total_hidden = parseFloat(spu) * parseFloat(prod_qty);

            var total_amount = 0;
            var discounted_amount = 0;
            var total_taxed_amount = 0;
            
            $("#total_" + productId).val(total_hidden);
            if (discount_Opt == 'prsnt') {
                if (prod_discount > 100) {
                    $("#prod_discount_" + productId).attr("data-parsley-min", "0");
                    $("#prod_discount_" + productId).attr("data-parsley-max", "100");
                    return false;
                } else {
                    var prod_dicounted_price = spu - (spu * (prod_discount / 100));
                    var prod_total = prod_qty * prod_dicounted_price;
                    $("#prod_discounted_price_" + productId).val(prod_dicounted_price);
                    $("#prod_total_" + productId).val(prod_total);
                    var prod_tax_val = ((prod_total) * (prod_tax_percent)) / 100;
                    $("#discounted_taxed_val_" + productId).val(prod_tax_val.toString().match(/^\d+(?:\.\d{0,2})?/));
                }
            }
            else if (discount_Opt == 'amt') {
                if(parseFloat(prod_discount) > parseFloat(spu)){
                    $("#prod_discount_" + productId).attr("data-parsley-le", "#spu_"+productId);
                    return false;
                }else{
                    $("#prod_discount_" + productId).removeAttr("data-parsley-le");
                    var prod_dicounted_price = spu - prod_discount;
                    var prod_total = prod_qty * prod_dicounted_price;
                    $("#prod_discounted_price_" + productId).val(prod_dicounted_price);
                    $("#prod_total_" + productId).val(prod_total);
                    var prod_tax_val = ((prod_total) * (prod_tax_percent)) / 100;
                    $("#discounted_taxed_val_" + productId).val(prod_tax_val.toString().match(/^\d+(?:\.\d{0,2})?/));
                }
            }
            
            if ($('#product'+productId).is(':checked')) {
                $(".hidden_show_tot").each(function () {
                    if($(this).val() != ''){
                        total_amount = parseFloat(total_amount) + parseFloat($(this).val());
                        $("#total_amt").val(total_amount);
                    }
                });
                $(".show_prod_total").each(function () {
                    discounted_amount = parseFloat(discounted_amount) + parseFloat($(this).val());
                    $("#discounted_amt").val(discounted_amount.toString().match(/^\d+(?:\.\d{0,2})?/));
                });
                $(".hidden_show_tax").each(function () {
                    if ($(this).val() != '') {
                        total_taxed_amount = parseFloat(total_taxed_amount) + parseFloat($(this).val());
                        $("#taxed_amt").val(total_taxed_amount.toString().match(/^\d+(?:\.\d{0,2})?/));
                    }
                });
            }
        });
        /* Discount Value: End */

        /* Quantity Change: Start */
        $('.quantity').on('change', function () {
            var productId = $(this).attr('id').replace('prod_qty_', '');
            var spu = $("#spu_" + productId).val();
            var discount_Opt = $("#discount_Opt_" + productId).val();
            var prod_discount = $("#prod_discount_" + productId).val();
            var prod_qty = $("#prod_qty_" + productId).val();
            var prod_tax_percent = $("#prod_tax_val_" + productId).val();

            var total_hidden = parseFloat(spu) * parseFloat(prod_qty);
            $("#total_" + productId).val(total_hidden);
            
            var total_amount = 0;
            var discounted_amount = 0;
            var total_taxed_amount = 0;
            
            if (discount_Opt == 'prsnt') {
                if (prod_discount > 100) {
                    $("#prod_discount_" + productId).attr("data-parsley-min", "0");
                    $("#prod_discount_" + productId).attr("data-parsley-max", "100");
                    return false;
                } else {
                    if(prod_discount != ''){
                        var prod_dicounted_price = spu - (spu * (prod_discount / 100));
                        var prod_total = prod_qty * prod_dicounted_price;
                        $("#prod_discounted_price_" + productId).val(prod_dicounted_price);
                        $("#prod_total_" + productId).val(prod_total);
                        var prod_tax_val = prod_total * (prod_tax_percent / 100);
                        $("#discounted_taxed_val_" + productId).val(prod_tax_val.toString().match(/^\d+(?:\.\d{0,2})?/));
                    }else{
                        prod_total = prod_qty * spu;
                        $("#prod_total_" + productId).val(prod_total);
                        prod_tax_val = prod_total * (prod_tax_percent / 100);
                        $("#discounted_taxed_val_" + productId).val(prod_tax_val.toString().match(/^\d+(?:\.\d{0,2})?/));
                    }
                }
            }
            else if (discount_Opt == 'amt') {
                if(parseFloat(prod_discount) > parseFloat(spu)){
                    $("#prod_discount_" + productId).attr("data-parsley-le", "#spu_"+productId);
                    return false;
                }else{
                    $("#prod_discount_" + productId).removeAttr("data-parsley-le");
                    var prod_dicounted_price = spu - prod_discount;
                    var prod_total = prod_qty * prod_dicounted_price;
                    $("#prod_discounted_price_" + productId).val(prod_dicounted_price);
                    $("#prod_total_" + productId).val(prod_total);
                    var prod_tax_val = ((prod_total) * (prod_tax_percent)) / 100;
                    $("#discounted_taxed_val_" + productId).val(prod_tax_val.toString().match(/^\d+(?:\.\d{0,2})?/));
                }
            }
            
            if ($('#product'+productId).is(':checked')) {
                $(".hidden_show_tot").each(function () {
                    total_amount = parseFloat(total_amount) + parseFloat($(this).val());
                    $("#total_amt").val(total_amount);
                });
                $(".show_prod_total").each(function () {
                    discounted_amount = parseFloat(discounted_amount) + parseFloat($(this).val());
                    $("#discounted_amt").val(discounted_amount.toString().match(/^\d+(?:\.\d{0,2})?/));
                });
                $(".discounted_tax").each(function () {
                    if ($(this).val() != '') {
                        total_taxed_amount = parseFloat(total_taxed_amount) + parseFloat($(this).val());
                        $("#taxed_amt").val(total_taxed_amount.toString().match(/^\d+(?:\.\d{0,2})?/));
                    }
                });

            }
        });
        /* Quantity Change: End */

        //Changing of Tax
        $('.tax_name').on('change', function () {
            var that = $(this);
            var taxId = that.val();
            var productId = that.attr('id').replace('tax_id_', '');
            var prod_dicounted_price = $("#prod_total_" + productId).val();
            var spu = $("#spu_" + productId).val();
            var quantity = $("#prod_qty_" + productId).val();
            var taxed_val;
            var total_taxed_amount = 0;
            $.ajax({
                url: "ProductGroup/GetTaxPercent",
                type: "POST",
                data: {'taxId': taxId},
                success: function (data) {
                    $("#prod_tax_val_" + productId).val(data);
                    if (prod_dicounted_price != '' || prod_dicounted_price == '0') {
                        taxed_val = ((parseFloat(prod_dicounted_price)) * (parseFloat(data))) / 100;
                        $("#discounted_taxed_val_" + productId).val(taxed_val.toString().match(/^\d+(?:\.\d{0,2})?/));
                    } else {
                        taxed_val = ((parseFloat(spu)) * (parseFloat(quantity)) * (parseFloat(data))) / 100;
                        $("#discounted_taxed_val_" + productId).val(taxed_val.toString().match(/^\d+(?:\.\d{0,2})?/));
                    }

                    $(".hidden_show_tax").each(function () {
                        if ($(this).val() != '') {
                            if ($('#product' + productId).is(':checked'))
                            {
                                total_taxed_amount = parseFloat(total_taxed_amount) + parseFloat($(this).val());
                                if(total_taxed_amount>0){
                                    $("#taxed_amt").val(total_taxed_amount.toString().match(/^\d+(?:\.\d{0,2})?/));
                                }else{
                                    $("#taxed_amt").val('0');
                                }
                            }
                        }
                    });
                }
            });
        });

        //for selected Products: Start
        $('input[name="products[]"]').on("click", function () {

            var thisCheck = $(this);
            var productId = thisCheck.attr("id").replace("product", "");
            Calculate(thisCheck, productId);

        });
        //for selected Products: End
        
        $(".hidden_show_tot").each(function(){
            var productIds = $(this).attr("id").replace("total_", "");
            var spus = $("#spu_"+productIds).val();
            var qty = $("#prod_qty_"+productIds).val();
            var total = spus * qty;
            $("#total_"+productIds).val(total);
        });

        
        $(".hidden_show_tax").each(function(){
            var productIds = $(this).attr("id").replace("discounted_taxed_val_", "");
            var product_tax = parseFloat($("#prod_total_"+productIds).val()) * parseFloat($("#prod_tax_val_"+productIds).val())/100;
            $("#discounted_taxed_val_"+productIds).val(product_tax);
        });
        
    });

</script>