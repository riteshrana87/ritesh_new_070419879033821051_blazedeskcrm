<select multiple="true" class="chosen-select form-control" name="budget_for_product[]" data-placeholder="Select Product" id="budget_for_product">
    <?php if (!empty($product_list) && count($product_list) > 0) { ?>
        <?php foreach ($product_list as $product) { ?>
            <option value="<?php echo $product['product_id']; ?>" <?php
            if (!empty($product_data) && in_array($product['product_id'], $product_data)) {
                echo 'selected="selected"';
            }
            ?>><?php echo $product['product_name']; ?></option>
        <?php } ?>
    <?php } ?>

</select>