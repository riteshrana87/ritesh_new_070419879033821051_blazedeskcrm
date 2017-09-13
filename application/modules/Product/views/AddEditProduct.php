<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $formAction = !empty($editRecord)?'updatedata':'insertdata'; 
    $path = $crnt_view.'/'.$formAction;
?>

<div class="modal-dialog" role="dialog">
    <div class="modal-content prodmodaldiv">
        <div class="modal-header">
          <button type="button" class="close" title="<?php echo lang('close'); ?>" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">
            <div class="title">
              <?php if($formAction == "insertdata"){ ?>
              <?= lang('create_product')?>
              <?php }else{ ?>
              <?= lang('update_product')?>
              <?php } ?>
            </div>
          </h4>
        </div>
        <?php
            $attributes = array("name" => "frmproduct", "id" => "frmproduct", "data-parsley-validate" => "");
            echo form_open_multipart($path, $attributes);
        ?>
        <div class="modal-body">
            <div class="form-group">
              <input name="prod_id" type="hidden" value="<?=!empty($editRecord[0]['product_id'])?$editRecord[0]['product_id']:''?>" />
            </div>
            
            <div class="row">
                <!-- name -->
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="product name">
                            <?= lang('product_name')?>
                            <span>*</span>
                        </label>
                        <input class="form-control" name="prod_name" id="prod_name" <?php ?> placeholder="<?= lang ('product_name')?>" type="text" value="<?php if($formAction == "insertdata"){ echo set_value('product_name');?><?php }else{?><?=!empty($editRecord[0]['product_name'])?htmlentities($editRecord[0]['product_name']):''?><?php }?>" required="" />
                    </div>
                </div>
                <!-- type -->
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="product type">
                            <?= lang('product_type')?>
                            <span>*</span></label>
                        <input class="form-control" name="prod_type" placeholder="<?= lang ('product_type')?>" type="text" value="<?php if($formAction == "insertdata"){ echo set_value('product_type');?><?php }else{?><?=!empty($editRecord[0]['product_type'])?htmlentities($editRecord[0]['product_type']):''?><?php }?>" required="" />
                    </div>
                </div>
            </div>
      
            <!-- group -->
            <div class="form-group">
                <label for="product group">
                    <?= lang('product_group')?>
                </label>
                <select name="prod_group[]" id="product_group_id" class="chosen-select" multiple="true" data-placeholder="<?php echo lang('select_option'); ?>">
                    <?php
                        $prdct_data = array();
                        if($formAction == 'updatedata'){
                            foreach ($product_rel_info as $product_data){
                                
                                $prdct_data[] = $product_data['product_group_id']; 
                            }
                        }

                        foreach($product_group_info as $row){?>
                        <?php if (in_array($row['product_group_id'], $prdct_data)) {?>
                            <option selected="selected" value="<?php echo $row['product_group_id'];?>"><?php echo $row['product_group_name'];?></option>
                        <?php } else { ?>
                            <option  value="<?php echo $row['product_group_id'];?>"><?php echo $row['product_group_name'];?></option>
                        <?php }?>
                        <?php } ?>
                        </select>
            </div>
      
            <!-- description -->
            <div class="form-group">
                <label for="product description">
                    <?= lang('product_desc')?>
                </label>
                <textarea class="form-control textarea-resizenone" name="prod_desc" id="prod_desc" placeholder="<?=lang('product_desc') ?>" value=""><?php if($formAction == "insertdata"){ echo set_value('product_description');?><?php }else{?><?=!empty($editRecord[0]['product_description'])?$editRecord[0]['product_description']:''?><?php }?></textarea>
            </div>
      
            <!-- purchase price unit -->
            <div class="row">
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="purchase price unit">
                            <?= lang('product_ppu')?>
                            <span>*</span>
                        </label>
                        <input class="form-control" type="text" data-parsley-pattern="/^\d{0,10}(\.\d{0,2})?$/" data-parsley-max="9999999999.99" min="0" name="prod_ppu" id="prod_ppu" placeholder="<?= lang ('product_ppu')?>" value="<?PHP if($formAction == "insertdata"){ echo set_value('purchase_price_unit');?><?php }else{?><?=!empty($editRecord[0]['purchase_price_unit'])?$editRecord[0]['purchase_price_unit']:''?><?php }?>" required="" />
                    </div>
                </div>
                
                <!-- sales price unit -->
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="sales price unit">
                            <?= lang('product_spu')?>
                            <span>*</span>
                        </label>
                        <input class="form-control" type="text" data-parsley-pattern="/^\d{0,10}(\.\d{0,2})?$/" data-parsley-max="9999999999.99" min="0" name="prod_spu" id="prod_spu" placeholder="<?= lang ('product_spu')?>" value="<?php if($formAction == "insertdata"){ echo set_value('sales_price_unit');?><?php }else{?><?=!empty($editRecord[0]['sales_price_unit'])?$editRecord[0]['sales_price_unit']:''?><?php }?>"  required=""/>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
            
            <div class="row">
                <!-- gross margin -->
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="gross margin">
                            <?= lang('product_gm')?>
                            <span>*</span>
                        </label>
                        <input class="form-control" type="text" data-parsley-pattern="/^\d{0,10}(\.\d{0,3})?$/" data-parsley-max="9999999999.99" min="0" name="prod_gm" id="prod_gm" placeholder="<?= lang ('product_gm')?>" value="<?php if($formAction == "insertdata"){ echo set_value('gross_margin');?><?php }else{?><?=!empty($editRecord[0]['gross_margin'])?$editRecord[0]['gross_margin']:''?><?php }?>" required=""/>
                    </div>
                </div>
                <!-- Currency -->
                <div class="col-xs-12 col-md-6 col-sm-6 form-group">
                    <label for="currency symbol">
                      <?= lang('currency_symbol')?>
                        <span>*</span>
                    </label>
                    <select class="selectpicker form-control chosen-select" name="currency_id" id="currency_id" required data-parsley-errors-container="#currencyErrors">
                        <option value="" selected=""><?=lang('select_currency_symbole')?></option>
                        <?php foreach($currency_info as $row){
                                    if($row['country_id'] == $prod_currency_info[0]['product_currency_id']){
                                        $selected_cur = "selected";
                                    }
                                    else{
                                        $selected_cur = "";
                                    }?>
                        <option <?php echo $selected_cur ?> value="<?php echo $row['country_id'];?>"><?php echo $row['currency_symbol'];?></option>
                        <?php } ?>
                    </select>
                    <span id="currencyErrors"></span>
                </div>
                <div class="clr"></div>
            </div>
            
            <div class="row">
                <!-- Tax -->
                <div class="col-xs-12 col-md-6 col-sm-6 form-group">
                    <label for="tax">
                        <?= lang('EST_LABEL_TAX')?>
                        <span>*</span>
                    </label>
                    <select class="selectpicker form-control chosen-select" name="tax_id" id="tax_id" required data-parsley-errors-container="#taxErrors">
                        <option value="" selected=""><?=$this->lang->line('select_tax')?></option>
                    <?php foreach($tax_info as $row){
                            if($row['tax_id'] == $prod_tax_info[0]['product_tax_id']){
                                $selected_tax = "selected";
                            }
                            else{
                                $selected_tax = "";
                            }?>
                        <option <?php echo $selected_tax ?> value="<?php echo $row['tax_id'];?>"><?php echo $row['tax_name'];?></option>
                    <?php } ?>
                    </select>
                    <span id="taxErrors"></span>
                </div>
                
                <!-- status -->
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="status">
                          <?= lang('status')?>
                        </label>
                    <?php
                        $options = array('1'=>lang('active'),'0'=>lang('inactive'));
			$name = "status";
			if($formAction == "insertdata"){
                            $selected = 1; 
			}else{
                            $selected = $editRecord[0]['status'];	 		 	
			}		 		
			echo dropdown( $name, $options, $selected); 
                    ?>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
        </div>
        
        <div class="modal-footer">
            <center>
                <input name="prod_id" id="prod_id" type="hidden" value="<?=!empty($editRecord[0]['product_id'])?$editRecord[0]['product_id']:''?>" />
                <input type="submit" class="btn btn-primary" name="product_submit" id="product_submit_btn" value="<?=($formAction == "insertdata") ?lang('create_product'): lang('update_product')  ?>" tabindex="25"/>
            </center>
        </div>
        <?php form_close() ?>
    </div>
</div>

<script> 
$(document).ready(function () {
    $('#frmproduct').parsley();
    $('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({allow_single_deselect: true}); 
    $('#product_group_id').trigger('chosen:updated');

    $("input[name='prod_spu']").on("change", function(){
        if($("#prod_ppu").val()!=''){
            var ppu = $("input[name='prod_ppu']").val();
            var spu = $(this).val();
            var gm = Math.abs(spu - ppu).toFixed(2);
            $("input[name='prod_gm']").val(gm);
        }
    });
    
    $("input[name='prod_ppu']").on("change", function(){
        if($("#prod_spu").val()!=''){
            var spu = $("input[name='prod_spu']").val();
            var ppu = $(this).val();
            var gm = Math.abs(spu - ppu).toFixed(2);
            $("input[name='prod_gm']").val(gm);
        }
    });
    
    //Get SPU, PPU and GM on click of edit button
    
    
    //Change SPU, PPU and GM on change of currency
    <?php if(!empty($editRecord)){ ?>
        var oldCurrencySymbolId = $("#currency_id").val();
        
        $("#currency_id").on("change", function(){
            var newCurrencySymbolId = $(this).val();
            var productId = $("#prod_id").val();
            var ppu;
            var spu;
            //Ajax Call for Getting SPU, PPU and GM By Id
            $.ajax({
                url: "<?php echo base_url('Product/getPPUandSPUById'); ?>",
                type: "POST",
                dataType: "json",
                data: {'productId': productId},
                success: function (data)
                {
                    ppu = data.PurchasePriceUnit;
                    spu = data.SalesPriceUnit;
                    if(ppu != '' && spu != '')
                    {
                        //Ajax Call for currency wise values of SPU, PPU and GM
                        $.ajax({
                            url: "<?php echo base_url('Product/getCurWiseSPUandPPU'); ?>",
                            type: "POST",
                            dataType: "json",
                            data: {'oldCurrencySymbolId': oldCurrencySymbolId, 'newCurrencySymbolId': newCurrencySymbolId, 'spu': spu, 'ppu': ppu},
                            success: function (data)
                            {
                                $("#prod_ppu").val(data.purchaseAmt.toString().match(/^\d+(?:\.\d{0,2})?/));
                                $("#prod_spu").val(data.salesAmt.toString().match(/^\d+(?:\.\d{0,2})?/));
                                spu = $("#prod_spu").val();
                                ppu = $("#prod_ppu").val();
                                var gm = Math.abs(spu - ppu).toFixed(2);
                                $("#prod_gm").val(gm);
                            }
                        });
                    }
                }
            });
            
    });
    <?php } ?>

    $('#prod_desc').summernote({
        
        height: 150, //set editable area's height
        codemirror: {// codemirror options
            theme: 'monokai'
        },
        focus: true
    });
    
    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
    });
});
</script>