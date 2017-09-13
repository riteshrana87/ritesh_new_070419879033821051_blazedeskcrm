<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content prodgroupmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                <div class="title">
                    <?= lang('view_product_group'); ?> 
                </div>
            </h4>
        </div>
        <div class="modal-body">
            
            <!-- name -->
            <div class="form-group">
                <label for="product group name">
                    <?= lang('product_group_name') ?>
                    <span class="viewtimehide">*</span>
                </label>
                <p><?=!empty($editRecord[0]['product_group_name'])? $editRecord[0]['product_group_name']: ''?></p>
            </div>
            
            <!-- description -->
            <div class="form-group">
                <label for="product group description">
                    <?= lang('product_group_desc') ?>
                </label>
                <p>
                <?php if(empty($editRecord[0]['product_group_description'])){?>
                    N/A
                <?php } else {?>
                    <?php echo $editRecord[0]['product_group_description']; ?>
                <?php } ?>
                </p>
            </div> 
            
            <!-- related products -->
            <div class="table table-responsive" id="products_list">
                <table class="table table-striped dataTable" id="products_list_tab" role="grid">
                    <thead>
                        <tr>
                                <th><?= lang('product_name') ?></th>
                                <th><?= lang('product_status') ?></th>
                                <th><?= lang('product_spu')?></th>
                                <th><?= lang('discount_option')?></th>
                                <th><?= lang('discount')?></th>
                                <th><?= lang('EST_LABEL_QUANTITY')?></th>
                                <th><?= lang('product_wise_total')?></th>
                                <th><?= lang('EST_LABEL_TAX')?></th>
                                <th><?= lang('PRODUCT_REQUIRED_TAX_PERCENTAGE')?></th>
                        </tr>
                    </thead>
                    <tbody id="products_list_body">
                        <?php foreach ($product_grp_rel_info as $row) {?>

                        <tr>
                                <td><?=$row['product_name']; ?></td>
                                <td><?=($row['product_group_status'] == '0')? lang('accessories'):lang('essential') ?></td>
                                <td><?=$row['sales_price_unit']; ?></td>
                                <td><?=($row['discount_option'] == 'prsnt')? '%':$currencyInfo['symbol']; ?></td>
                                <td><?=$row['product_discount']; ?></td>
                                <td><?=$row['product_qty']; ?></td>
                                <td><?=$row['product_total']; ?></td>
                                <td><?=$row['tax_name']; ?></td>
                                <td><?=$row['tax_percentage']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div> 
            
             <div class="form-group">
                <label for="total amount">
                    <?= lang('total_amount')?>
                </label>
                <p>
                    <?=$editRecord[0]['product_group_total_amt']?>
                </p>
            </div>
            
            <div class="form-group">
                <label for="discounted amount">
                    <?= lang('discounted_amount')?>
                </label>
                <p>
                    <?=$editRecord[0]['product_group_discounted_amt']?>
                </p>
            </div>
            
            <!-- status -->
            <div class="form-group">
                <label for="status">
                    <?= lang('status')?>
                </label>
                <p>
                    <?=($editRecord[0]['status'] == 1)? lang('active'): lang('inactive');?>
                </p>
            </div> 
        </div>
    </div>
</div>    