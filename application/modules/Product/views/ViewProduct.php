<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>   
<div class="modal-dialog">
    <div class="modal-content prodmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                <div class="title">
                    <?= lang('view_product'); ?>
                </div>
            </h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <!-- name -->
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="product name">
                            <?= lang('product_name')?>
                        </label>
                        <p><?=!empty($editRecord[0]['product_name'])?$editRecord[0]['product_name']:''?></p>
                    </div>    
                </div>
                
                <!-- type -->
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="product type">
                            <?= lang('product_type')?>
                        </label>
                        <p><?=!empty($editRecord[0]['product_type'])?$editRecord[0]['product_type']:''?></p>
                    </div>
                </div>
            </div>
            
            <!-- group -->
            <div class="form-group">
                <label for="product group">
                    <?= lang('product_group')?>
                </label>
                <?php if(empty($product_rel_info[0]['product_group_name'])){?>
                <p>N/A</p>
                <?php } else {?>
                <p>
                <?php echo $product_rel_info[0]['product_group_name'];?>
                <?php }?>
                </p>
            </div>    
            
            <!-- Description -->
            <div class="form-group">
                <label for="product description">
                    <?= lang('product_desc')?>
                </label>
                <?php if(empty($editRecord[0]['product_description'])){?>
                    <p>N/A</p>
                <?php } else {?>
                    <p><?php echo $editRecord[0]['product_description']; ?></p>
                <?php } ?>
            </div>
            
            <div class="row">
                <!-- purchase price unit -->
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="purchase price unit">
                            <?= lang('product_ppu')?>
                        </label>
                        <p><?=!empty($editRecord[0]['purchase_price_unit'])?$editRecord[0]['purchase_price_unit']:''?></p>
                    </div>    
                </div>
                
                <!-- sales price unit -->
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="sales price unit">
                            <?= lang('product_spu')?>
                        </label>
                        <p><?=!empty($editRecord[0]['sales_price_unit'])?$editRecord[0]['sales_price_unit']:''?></p>
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
                        </label>
                        <p><?=!empty($editRecord[0]['gross_margin'])?$editRecord[0]['gross_margin']:''?></p>
                    </div>    
                </div>
                
                <!-- Currency -->
                <div class="col-xs-12 col-md-6 col-sm-6 form-group">
                    <label for="currency symbol">
                      <?= lang('currency_symbol')?>
                    </label>
                    <p>
                        <?php foreach($currency_info as $row){
                            if($row['country_id'] == $prod_currency_info[0]['product_currency_id']){?>
                                <?=!empty($row['currency_symbol'])?$row['currency_symbol']:'';
                            }
                        }?>
                    </p>
                </div>    
                
                <div class="clr"></div>
            </div>    
            
            <div class="row">
                <!-- Tax -->
                <div class="col-xs-12 col-md-6 col-sm-6 form-group">
                    <label for="tax">
                        <?= lang('EST_LABEL_TAX')?>
                    </label>
                    <p>
                    <?php foreach($tax_info as $row){
                            if($row['tax_id'] == $prod_tax_info[0]['product_tax_id']){?>
                                <?=!empty($row['tax_name'])?$row['tax_name']:'';
                            }
                        }    
                    ?>
                    </p>
                </div>
                
                <!-- status -->
                 <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="status">
                          <?= lang('status')?>
                        </label>
                        <p>
                            <?=($editRecord[0]['status'] == 1)? lang('active'): lang('inactive');?>
                        </p>
                    </div>
                 </div>  
                
                <div class="clr"></div>
            </div>    
        </div>    
    </div>   
</div>