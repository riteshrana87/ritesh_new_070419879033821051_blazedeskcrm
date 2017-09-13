<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord) ? 'add_autograph' : 'add_autograph';

$path = $ctr_view . '/' . $formAction;
?>
<!-- Example row of columns -->
<div class="row">
<div class="col-xs-12 col-md-12">
        <?php
        $attributes = array("name" => "frmsubmit", "id" => "frmsubmit", "data-parsley-validate" => "");
        echo form_open_multipart($path, $attributes);
        ?>
        <input type="hidden" name="hdn_submit_status" id="hdn_submit_status" value="1" />
        <input type="hidden" name="est_client_id" id="est_client_id" value="<?php echo $est_client_id; ?>">
        <div class="whitebox">
            <?php echo $this->session->flashdata('msg'); ?>
            <div class="col-xs-12 col-md-offset-2 mt15 col-md-8 bg-gray">
                <h3><b><?php echo lang('client_estimate');?></b><span class="pull-right"><span class="form-group">
                            <input type="hidden" name="estimate_id" id="estimate_id" value="<?php echo $editRecord[0]['estimate_id']; ?>">
                            <input type="text" class="form-control" name="estimate_auto_id" readonly placeholder="EST XXXXX" value="<?php echo $editRecord[0]['estimate_auto_id']; ?>" required>
                        </span></span></h3>
                <div class="clr"></div><br/>

            <div class="form-group" id="ShowRecipientAsPerComapny">
                <h1><?php echo lang('client_name');?>:
                <?php foreach ($RecipientClientInfo as $client) { ?>
                    <?php if (in_array("client_" . $client['prospect_id'], $EstRecipientArray)) {
                                    echo $client['prospect_name'];
                    } ?>
            <?php } ?>

                <?php foreach ($RecipientContactInfo as $contact) { ?>
                   <?php if (in_array("contact_" . $contact['contact_id'], $EstRecipientArray)) {
                         echo $contact['contact_name'];
                    } ?>
            <?php } ?>
                </h1>
            </div>
            <div class="clr"></div>
                <!-- Preview Starts here -->
					<?php $this->load->view('estimatePreview'); ?>
                <!-- Preview Ends here -->
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
            <div class="col-xs-12 col-md-8 col-md-offset-2 bg-gray">
			<div class="clr"> </div>
				<input type="hidden"  id="est_client_approval_status" name="est_client_approval_status" value="1"/> 
            <div class="col-xs-12 col-md-6 no-left-pad">
                    <div class="form-group">
                        <label><?php echo lang('EST_LBL_ADD_AUTOGRAPH');?></label>
                        <div class="pull-right">
        <?php if ($editRecord[0]['signature'] != null) {?>
                <input type="hidden" name="signature-digital_old" id="signature-digital" value="<?php echo $editRecord[0]['signature']; ?>">
                                <!--<img id="signImg" class="show" src="<?php //echo $editRecord[0]['signature']; ?>" height="100px" width="100px">-->
<?php } ?>
                            <div class="btn-group btn-toggle">
								<span id="autoGraphError"></span>
                                <input data-toggle="toggle" data-onstyle="success" type="checkbox"  id="signature_type" name="signature_type" onChange="toggle_show('#signatureTypeDiv', this);
                                        $('#signImg').toggleClass('show', 'hidden');" data-parsley-errors-container="#autoGraphError" required/>
                            </div>
                        </div>

                        <div class="clr"> </div>
                    </div>
                    <div class="clr"> </div>
                </div>
                <div class="clr"> </div>
				<div class="col-xs-12 col-md-12 no-left-pad">
                    <div class="form-group" id="signatureTypeDiv" style="display:none">
                        <input type="hidden" name="signature-digital" id="signature-digital" class="input-group">
                        <select id="signature_type" name="signature_type" required class="form-control chosen-select signatureTypeChosen" onchange="displaySignature(this.value);" >
                            <option value=""><?php echo lang('EST_TITLE_AUTOGRAPH_SELECT');?></option>
                            <option value="0"><?php echo lang('EST_TITLE_AUTOGRAPH_DIGITAL_SIGNATURE'); ?></option>
                            <option value="1"><?php echo lang('EST_TITLE_AUTOGRAPH_CANVAS'); ?></option>
                        </select>
                    </div>

                    <div class="form-group" id="signature-upload" style="display:none">
						<label class="custom-upload btn btn-blue"><?php echo lang('EST_TITLE_AUTOGRAPH_UPLOAD'); ?>
							<input type="file" name="signature-file" id="singnature-file" class="input-group" onchange="autoGraphUpload(this)">
						</label>
						<div> <img id="autographimg" class="noimage" src="<?php echo base_url('uploads/contact').'/noimage.jpg'?>"  width="100" /> </div>
                        <div class="clr"> </div>
                    </div>
                    <div class="form-group" id="signature" style="display:none">

                        <div class='js-signature' style="width:100px;height:100px"></div>
                        <div class="clr"> </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 ">
                    <div class="form-group" id="client_can_accept_online">
                        <textarea class="form-control" id="client_can_accept_online_textbox" name='client_can_accept_online_textbox'></textarea>
                        <div class="clr"> </div>
                    </div>
                </div>

            </div>
            <div class = "clr"></div>
            <div class="mt15 mb15">
                <div class="text-center">
                    <input type="button" value="Accept Estimate" id="camp_submit_btn" class="btn btn-primary" onclick="return validateApprovalForm('1');">
					
                    <input type="button" value="Decline Estimate" id="camp_submit_btn" class="btn btn-primary" onclick="return validateApprovalForm('0');">
                </div>
            </div>
        </div>
<?php echo form_close();
?>
        <div class="clr"></div>
    </div>
</div>
<div class="clr"></div><br/>
<div class="modal fade modal-image" id="modalGallery" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onClick="$('#modalGallery').modal('hide');" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo lang('EST_TITLE_AUTOGRAPH_UPLOADS'); ?></h4>
            </div>
            <div class="modal-body" id="modbdy">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onClick="$('#modalGallery').modal('hide');"><?php echo lang('EST_PREVIEW_CLOSE'); ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Modal -->
<div class="modal in" id="prd_template_box" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
<?php
$attributes = array("name" => "frm_prd_template", "id" => "frm_prd_template", 'data-parsley-validate' => "");
echo form_open_multipart('', $attributes);
?>
            <input type="hidden" name="hdn_submit_status" id="hdn_submit_status" value="1" />
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">
                    <div class="modelTaskTitle"> <?php echo lang('EST_TITLE_PRD_TEMPLATE'); ?> </div>
                </h4>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12">
                        <label> <?php echo lang('EST_PREVIEW_TEMPLATE_NAME'); ?>          </label>
                        <input type="text" required="" value="" placeholder="<?php echo lang('EST_PREVIEW_TEMPLATE_NAME'); ?>" name="est_temp_name" id="est_temp_name" class="form-control">
                    </div>
                </div>
                <!--<a onchange="return addNewTemplate(this.value);" >Save</a>-->
            </div>
            <div class="modal-footer">
                <center>
                    <a onclick="addNewTemplate();" href="javascript:;"><input type="button" value="<?php echo lang('EST_EDIT_SAVE'); ?>" name="remove" class="btn btn-info"></a>
                </center>
            </div>
<?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>
	/*
	 * validateApprovalForm() form for Make confirmation and validation.
	 */
	function validateApprovalForm(status)
	{
		if(status == 1)
		{
			var StatusName = 'Accept';
		}
		else {
			var StatusName = 'Decline';
		}
		
		BootstrapDialog.show(
            {
                title: 'Estimate Client Module',
                message: 'Are you sure want to '+ StatusName +' Estimate?',
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {
                        $('#est_client_approval_status').val(status);
                        $("#frmsubmit").submit();
                        dialog.close();
                    }
                }]
            });

	}
	 //Autograph upload
    function autoGraphUpload(input)
    {
		console.log(input);
		var maximum = input.files[0].size/1024;
		//alert(maximum);
		if (input.files && input.files[0] && maximum <= 1024)
		{
			var arr1 = input.files[0]['name'].split('.');
			var arr= arr1[1].toLowerCase(); 
			if(arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif')
			{
			  var filerdr = new FileReader();
			  filerdr.onload = function(e) {
			  $('#autographimg').attr('src', e.target.result);
			  }
			  filerdr.readAsDataURL(input.files[0]);
			}
			else
			{
				var delete_meg ="Please upload jpg | jpeg | png | gif image only";
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
		}
		else
		{
			var delete_meg ="Maximum upload size 1 MB";
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
    }	
</script>
<script>
    var inc = $('#productBox').find('a').length + 1;
    var newProductboxIncrement = 1;
    var newProductGroupboxIncrement = $('[name="product_group_id[]"]').length + 1;
    var textParagraphIncrement = $('#textParagraphSection').find('textarea').length + 1;
    function toggle_show(className, obj) {
	//Set Codition for autograph
		if(className == '#signatureTypeDiv')
		{
			$('#signature-upload').hide();
            $('#signature').hide();
			$(".signatureTypeChosen").val('').trigger("chosen:updated");
		}
        var $input = $(obj);
        if ($input.prop('checked'))
            $(className).show();
        else
            $(className).hide();
    }
    $(document).ready(function () {
        $('#frmsubmit').parsley();
        $('#creation_date').datepicker().on('changeDate', function (ev) {

            $('#creation_date').datepicker('hide');
        });
        $('.chosen-select').chosen({placeholder_text_single: "Product Group",
            no_results_text: "Oops, nothing found!"});
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
        doSubtotal();

    });

    /**
     *
     * this function is used for adding dynamic existing product box to the div
     */
    function addProductBox()
    {
        $.ajax({
            url: "<?php echo base_url('Estimates/getProductBox'); ?>/" + inc,
            type: "GET",
            success: function (data)
            {
                $('#productBox').append(data);
                $('.chosen-select').chosen();
                $('.chosen-select-deselect').chosen({allow_single_deselect: true});
                $('#frmsubmit').parsley().destroy();
                $('#frmsubmit').parsley();
                inc++;
            }
        });
    }
    /**
     *
     * this function is used for adding dynamic new product box to the div
     */
    function addNewProductBox()
    {
        $.ajax({
            url: "<?php echo base_url('Estimates/getProductBox'); ?>/" + newProductboxIncrement,
            type: "GET",
            data: {type: "new"},
            success: function (data)
            {
                $('#newProductBox').append(data);
                newProductboxIncrement++;
            }
        }
        );
    }
    /**
     *
     * this function is used for adding dynamic  product group box to the div
     */
    function addProductGroupBox()
    {
        $.ajax({
            url: "<?php echo base_url('Estimates/getProductBox'); ?>/" + newProductGroupboxIncrement,
            type: "GET",
            data: {type: "group"},
            success: function (data)
            {

                $('#newProductGroupBox').append(data);
                /*
                 * Reinileze of chosen select
                 */
                $('.chosen-select').chosen({placeholder_text_single: "Product Group",
                    no_results_text: "Oops, nothing found!"});
                $('.chosen-select-deselect').chosen({allow_single_deselect: true});
                newProductGroupboxIncrement++;
                if ($('#productBox').children("div").length > 0)
                {
                    doSubtotal();
                }
            }
        }
        );
    }
    /**
     *
     * this function is used for displaying the data of products by group id
     */
    function showInnerBox(className, currId, incId)
    {
        $('.' + className).removeClass('hidden');
        $.ajax({
            url: "<?php echo base_url('Estimates/getProductById'); ?>/" + currId,
            type: "GET",
            dataType: "json",
            success: function (data)
            {

                $('#product_name_' + incId).val(data.data.product_name);
                $('#product_description_' + incId).val(data.data.product_description);
                //$('#product_qty_' + incId).value(data.product_name);
                // $('#product_tax_' + incId).value(data.product_name);
                $('#product_amount_' + incId).val(data.data.sales_price_unit);
                $('#product_amount_sales_' + incId).val(data.data.sales_price_unit);
                doSubtotal();
            }
        });
    }
    /**
     *
     * this function is used for calculation of the product amount
     */
    function manageProductAmount(qty, tax, amount, incId, type)
    {

        if ($.isNumeric(qty) && qty > 0)
        {
            $.ajax({
                url: "<?php echo base_url('Estimates/productCalculationTaxesQty'); ?>",
                type: "POST",
                data: {qty: qty, tax: tax, amount: amount},
                dataType: "JSON",
                success: function (data)
                {
                    console.log(data.total);
                    $('#product' + type + '_amount_' + incId).val(data.total);
                    $('#product' + type + '_tax_calculated_' + incId).val(data.tax);
                    doSubtotal();
                }
            }
            );
        }
    }
    /**
     *
     * this function is used for calculation of the product amount when qty changes
     */
    function changeAmountValueQty(currVal, incId, type)
    {
        var amount = '';
        var qty = currVal;
        var tax = $('#product' + type + '_tax_' + incId).val();
        amount = $('#product' + type + '_amount_sales_' + incId).val();

        manageProductAmount(qty, tax, amount, incId, type);
    }
    /**
     *
     * this function is used for calculation of the product amount when amount changes for new product only
     */
    function calculateAmountValueFromField(currVal, incId, type)
    {
        var qty = '';
        var amount = currVal;
        var tax = $('#product' + type + '_tax_' + incId).val();
        qty = $('#product' + type + '_qty_' + incId).val();
        manageProductAmount(qty, tax, amount, incId, type);
    }
    /**
     *
     * this function is used for calculation of the product amount when amount changes for new product only
     */
    function calculateSalesAmountFromField(currVal, incId, type)
    {
        var qty = '';
        var amount = currVal;
        var tax = $('#product' + type + '_tax_' + incId).val();
        qty = $('#product' + type + '_qty_' + incId).val();
        manageProductAmount(qty, tax, amount, incId, type);
    }
    /**
     *
     * this function is used for calculation of the product amount when tax changes
     */
    function changeAmountValueTax(currVal, incId, type)
    {
        var qty = $('#product' + type + '_qty_' + incId).val();
        var tax = currVal;
        var amount = $('#product' + type + '_amount_sales_' + incId).val();

        manageProductAmount(qty, tax, amount, incId, type);
    }
    /**
     *
     * this function is used for calculation of subtotal
     */
    function doSubtotal()
    {
        var amount = [];
        var taxes = [];
        var discount = '';
        $(".product_amount").each(function () {
            amount.push($(this).val());
        });
        $(".product_tax_calculated").each(function () {
            taxes.push($(this).val());
        });
        discount = $('#discount').val();
        $.ajax({
            url: "<?php echo base_url('EstimatesClient/productCalculationSubTotal'); ?>",
            type: "POST",
            data: {tax: taxes, amount: amount, discount: discount},
            success: function (data)
            {
                $('#subtotalSection').html(data);
            }
        }
        );
    }
    /**
     *
     * this function is used to display discount box
     */
    function showDiscount(data)
    {
        if ($.isNumeric(data) && data > 0 && data <= 100) {
            $('#discountbox1').show();
            doSubtotal();

        }
        else
        {
            $('#discountbox1').hide();
            doSubtotal();
        }
    }
    /**
     *
     * this function is used to display product list by group id
     */
    function getproductListByGroup(dataVal, incId)
    {
        $.ajax({
            url: "<?php echo base_url('Estimates/getProductsListByGroupId'); ?>/" + dataVal,
            type: "GET",
            data: {incVar: incId},
            success: function (data)
            {
                $('#prod-group-products-' + incId).html(data);
                doSubtotal();
            }
        }
        );
    }
    /**
     *
     * this function is used to display digital signature type
     */
    function displaySignature(data)
    {
        if (data == 0)
        {
            $('#signature-upload').hide();
            $('#signature').show();
            $("#signature").empty()
            $("#signature").jSignature({'UndoButton': true})
        }
        else
        {
            $('#signature-upload').show();
            $('#signature-digital').val();
            $('#signature').hide();
        }

    }
    /**
     *
     * this function is used to append text paragraph
     */
    function appendTextParagraph()
    {
        $.ajax({
            url: "<?php echo base_url('Estimates/appendTextParagraph'); ?>/" + textParagraphIncrement,
            type: "GET",
            success: function (data)
            {
                $('#textParagraphSection').append(data);
                textParagraphIncrement++;
            }
        }
        );

    }
    /**
     *
     * this function is used remove any element
     */
    function removeItem(el)
    {
        BootstrapDialog.show(
            {
                title: 'Estimate Module',
                message: 'Are you sure want to delete?',
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {
                        $(el).remove();
                        doSubtotal();
                        dialog.close();
                    }
                }]
            });

        //$(el).remove();
    }
    /*
     * This function Execute When Template Select box changed
     */
    function templateFormOpen(val)
    {
        if (val == 'add')
        {
            $('#prd_template_box').modal('show');
        } else if (val != "")
        {
            BootstrapDialog.show(
                {
                    title: 'Estimate Template',
                    message: 'Are your sure want to apply the estimate template?',
                    buttons: [{
                        label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                        action: function(dialog) {
                            dialog.close();
                        }
                    }, {
                        label: '<?php echo $this->lang->line('ok');?>',
                        action: function(dialog) {
                            $.ajax({
                                url: "<?php echo base_url('Estimates/showTemplateProduct'); ?>",
                                type: "POST",
                                data: {est_temp_id: val},
                                success: function (data)
                                {
                                    $('#tempProductBox').append(data);
                                }
                            });
                            dialog.close();
                        }
                    }]
                });

        }
    }
    /**
     * Save Selected Product and Product Group  this function is used for add new template ajax
     */
    function addNewTemplate(val)
    {
        /*
         * this condition for add new Estimate template
         */
        var est_temp_name = $('#est_temp_name').val();
        if (est_temp_name != "" && $.trim(est_temp_name) != '')
        {
            $("#est_temp_name").addClass("form-control parsley-success");
            BootstrapDialog.show(
                {
                    title: 'Estimate Template',
                    message: 'Are your sure want to save "' + est_temp_name + '" as a Estimate Template?',
                    buttons: [{
                        label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                        action: function(dialog) {
                            dialog.close();
                        }
                    }, {
                        label: '<?php echo $this->lang->line('ok');?>',
                        action: function(dialog) {

                            var product_id = [];                        //Array For Single Product ID
                            var product_qty = [];                       //Array For Single Product Quantity
                            var product_tax = [];                       //Array For Single Product Tax
                            var product_amount = [];                    //Array For Single Product Amount
                            var product_group_id = [];                  //Array For Product Group ID
                            var product_group_product_id = {};          //Object For Product ID
                            var product_group_productid_array = []; //Array For Product ID Based on Group ID

                            //Push/Get Product ID in Array
                            $(".product_id").each(function () {
                                if ($(this).val())
                                {
                                    product_id.push($(this).val());
                                }
                            });
                            //Push/Get Product Quantity in Array
                            $(".product_qty").each(function () {
                                if ($(this).val())
                                {
                                    product_qty.push($(this).val());
                                }
                            });
                            //Push/Get Product Tax in Array
                            $(".product_tax").each(function () {
                                if ($(this).val())
                                {
                                    product_tax.push($(this).val());
                                }
                            });
                            //Push/Get Product Amount in Array
                            $(".product_existing_amount").each(function () {
                                if ($(this).val())
                                {
                                    product_amount.push($(this).val());
                                }
                            });
                            //Push/Get Group ID in Array
                            $(".product_group_id").each(function () {
                                if ($(this).val())
                                {
                                    product_group_id.push($(this).val());
                                }
                            });

                            //console.log(product_id);
                            //console.log(product_qty);
                            //console.log(product_tax);
                            //console.log(product_amount);
                            //console.log(product_group_id.length);

                            //Push/Get Group Related Product id, Product Quantity, Product Tax, Sales Amount
                            if (product_group_id.length != 0 && $.isEmptyObject(product_group_id) == false)
                            {
                                $.each(product_group_id, function (index, value) {
                                    //alert( index + ": " + value );

                                    //Push/Get Product ID based On Group ID in Object
                                    var product_group_productid = [];               //Array For Group Product ID
                                    $(".product_group_product_id_" + value).each(function () {
                                        if ($(this).val())
                                        {
                                            product_group_productid.push($(this).val());
                                        }
                                    });
                                    //Push/Get Product Quantity based On Group ID in Object
                                    var product_group_qty = [];             //Array For Group Product ID
                                    $(".product_group_qty_" + value).each(function () {
                                        if ($(this).val())
                                        {
                                            product_group_qty.push($(this).val());
                                        }
                                    });
                                    //Push/Get Product Tax based On Group ID in Object
                                    var product_group_tax = [];             //Array For Group Product ID
                                    $(".product_group_tax_" + value).each(function () {
                                        if ($(this).val())
                                        {
                                            product_group_tax.push($(this).val());
                                        }
                                    });
                                    //Push/Get Product Sales Amount based On Group ID in Object
                                    var product_group_amount = [];              //Array For Group Product ID
                                    $(".product_group_amount_" + value).each(function () {
                                        if ($(this).val())
                                        {
                                            product_group_amount.push($(this).val());
                                        }
                                    });
                                    //Create Product Group Object
                                    product_group_product_id[value] = {
                                        product_id: product_group_productid,
                                        product_qty: product_group_qty,
                                        product_tax: product_group_tax,
                                        product_amount: product_group_amount
                                    }
                                });
                                //
                                product_group_productid_array.push(product_group_product_id);
                            }
                            //console.log(product_group_productid_array);

                            $.ajax({
                                url: "<?php echo base_url('Estimates/insertTemplate'); ?>",
                                type: "POST",
                                data: {
                                    est_temp_name: est_temp_name,
                                    product_group_id: product_group_id,
                                    group_product_information: product_group_productid_array,
                                    product_id: product_id,
                                    product_qty: product_qty,
                                    product_tax: product_tax,
                                    product_amount: product_amount
                                },
                                success: function (data)
                                {
                                    //Make Product Template Blank.
                                    $('#est_temp_name').val('');
                                    $('#prd_template_box').modal('hide');
                                    //$('#subtotalSection').html(data);
                                }
                            });

                            dialog.close();
                        }
                    }]
                });

        }
        else
        {
            $("#est_temp_name").addClass("form-control parsley-error");
        }
    }
    /*
     * Effect when change "Select Client" Select box
     * Show Company Related Client information
     */
    function ShowClientRelatedToCompany(val)
    {
        selectedInfo = val.split("_");
        var prospectName = $('#prospect_id_chosen .chosen-single span').html();
        $('#client_name').val(prospectName);
        if (selectedInfo[0] === 'company')
        {
            //When Company select then
            if (selectedInfo[1])
            {
                $.ajax({
                    url: "<?php echo base_url('Estimates/ShowClientRelatedToCompany'); ?>",
                    type: "POST",
                    data: {company_id: selectedInfo[1], selectedinfo: selectedInfo[0]},
                    success: function (data)
                    {
                        $('#ShowRecipientAsPerComapny').html(data);
                        $('.chosen-select').chosen();
                        //$('.chosen-select-deselect').chosen({allow_single_deselect: true});
                        //$('#frmsubmit').parsley().destroy();
                        //$('#frmsubmit').parsley();
                    }
                });
            }
        }
        else
        {
            //When Client and Contact Select
            $.ajax({
                url: "<?php echo base_url('Estimates/ShowClientRelatedToCompany'); ?>",
                type: "POST",
                data: {company_id: selectedInfo[1], selectedinfo: selectedInfo[0]},
                success: function (data)
                {
                    $('#ShowRecipientAsPerComapny').html(data);
                    $('.chosen-select').chosen();
                    //$('.chosen-select-deselect').chosen({allow_single_deselect: true});
                    //$('#frmsubmit').parsley().destroy();
                    //$('#frmsubmit').parsley();
                }
            });
        }
    }
    function SaveEstForm(val)
    {
        if (val == 'draft')
        {
            BootstrapDialog.show(
                {
                    title: 'Estimate Module',
                    message: 'Are you sure want to save estimate as a Draft?',
                    buttons: [{
                        label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                        action: function(dialog) {
                            dialog.close();
                        }
                    }, {
                        label: '<?php echo $this->lang->line('ok');?>',
                        action: function(dialog) {
                            $("#hdn_submit_status").val("2");       //2 Value for Draft
                            $("#frmsubmit").submit();
                            dialog.close();
                        }
                    }]
                });


        } else if (val == 'preview')
        {
            BootstrapDialog.show(
                {
                    title: 'Estimate Module',
                    message: 'Are you sure want to preview estimate?',
                    buttons: [{
                        label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                        action: function(dialog) {
                            dialog.close();
                        }
                    }, {
                        label: '<?php echo $this->lang->line('ok');?>',
                        action: function(dialog) {
                            $("#hdn_submit_status").val("2");       //2 Value for Preview
                            $("#frmsubmit").submit();
                            dialog.close();
                        }
                    }]
                });

        } else {
            $("#hdn_submit_status").val("1");                   //1 Value For Save as a active
            $("#frmsubmit").submit();
        }
    }
    function GeneratePDF($estimate_id)
    {
        var base_url = window.location.origin;
        var pathArray = window.location.pathname.split('/');
        var send_url = base_url + "/" + pathArray[1] + "/" + pathArray[2] + '/GeneratePDF/' + $estimate_id;
        window.location.href = send_url;
    }
</script>
<script>
    /**
     *
     * this function is used to convert signature digital data
     */
    $('body').delegate('#signature', 'click', function () {
        var $sigdiv = $("#signature");
        var datapair = $sigdiv.jSignature("getData", 'image');
        var i = new Image();
        var x = "data:" + datapair[0] + "," + datapair[1];
        $("#signature-digital").val(x);
        //        $(i).appendTo($("#signature-digital")); // append the image (SVG) to DOM.
        // console.log(datapair);
    });
    /**
     *
     * this function is used to show popup of the choose gallery box
     */
    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('costModel').modal('hide');
        $('#modalGallery').modal('show');
    });
</script>