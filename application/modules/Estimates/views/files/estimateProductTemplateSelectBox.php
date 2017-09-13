<select name="template_box" id="template_box" class="form-control chosen-select" data-placeholder="Use Estimation Template" onChange="return templateFormOpen(this.value);">
    <option value=""><?php echo lang('EST_ADD_LABEL_USE_PRODUCT_TEMPLATE') ?></option>
	<optgroup label="Save Product Template">
            <option value="add"><?php echo lang('EST_ADD_LABEL_SAVE_PRODUCT_TEMPLATE'); ?></option>
	</optgroup>
	<optgroup label="Existing Template">
		<?php if (count($estimate_temp_info) > 0) { ?>
			<?php foreach ($estimate_temp_info as $estimate) { ?>
				<option value="<?php echo $estimate['est_temp_id']; ?>"><?php echo $estimate['est_temp_name']; ?></option>
			<?php } //Close Estimate Template fore loop ?>
		<?php } //Close Estimate If Condition  ?>
	</optgroup>
</select>