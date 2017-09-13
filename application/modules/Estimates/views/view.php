<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord) ? 'insertdata' : 'insertdata';
$path = $ctr_view . '/' . $formAction;
?>

<!-- Example row of columns -->

<div class="row">
  <div class="col-md-12 col-xs-12 col-lg-12">
      <?php echo $this->breadcrumbs->show(); ?>
  </div>
  <div class="clr"></div>
  <div class="col-xs-12 col-md-12 col-lg-12">
    <input type="hidden" name="hdn_submit_status" id="hdn_submit_status" value="1" />
    <input type="hidden" name="HdnSubmitBtnVlaue" id="HdnSubmitBtnVlaue" value="save" />
    <input type="hidden" name="client_name" id="client_name" value="<?php echo $client_name; ?>">
    <input type="hidden" name="discount_Opt_edit" id="discount_Opt_edit" value="<?php echo $editRecord[0]['discount_option']; ?>">
    <div class="whitebox">
      <div id="errorMsgLoader" class="text-center"> </div>
      <div id="errorMsg"> <?php echo $this->session->flashdata('msg'); ?> </div>
	  <div class="col-xs-12 col-md-8 bg-gray col-lg-8 col-lg-offset-2 col-md-offset-2 mt15 mb15">
		 <div class="text-right"><a href="javascript:;" class="btn btn-blue" onclick="SendEstimate('<?php echo $editRecord[0]['estimate_id'];?>');"><?php echo lang('EST_LBL_SEND_EST');?></a> &nbsp; <a class="btn btn-blue" href="javascript:;"><?php echo lang('EST_LBL_VISIBLE_FOR_CLIENT');?></a>&nbsp; <a onclick="GeneratePDF('<?php echo $editRecord[0]['estimate_id'];?>');" class="btn btn-blue" href="javascript:;"><?php echo lang('EST_LBL_PDF');?></a>&nbsp; <a onclick="GeneratePrint('<?php echo $editRecord[0]['estimate_id'];?>');" class="btn btn-blue" href="javascript:;"><?php echo lang('EST_LBL_PRINT');?></a>&nbsp; <a onclick="showMarginPopup();" class="btn btn-blue" data-href="javascript:;"><?php echo lang('EST_LABEL_SHOW_MARGIN');?></a></div>
        <div class="col-lg-12">
          <div class="mb15 col-sm-8 col-xs-12"><h3><b><?php echo lang('EST_LABEL_VIEW_ESTIMATE');?></b></h3></div>
          <div class="pull-left-resp col-sm-4 col-xs-12 pull-right text-right form-group">
            <input type="hidden" name="estimate_id" id="estimate_id" value="<?php echo $editRecord[0]['estimate_id']; ?>">
            <h3><p><?php echo $editRecord[0]['estimate_auto_id']; ?></p></h3>
            </div>
          <div class="clr"></div>
        </div>
        <!-- Preview Starts here -->
        <?php $this->load->view('estimatePreview'); ?>
        <!-- Preview Ends here -->
        
        <div class="clr"></div>
      </div>
      <div class = "clr"></div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<?php /*Model Popup For Show Margin*/?>
<div class="modal in" id="prdMarginShow" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <?php
$attributes = array("name" => "frm_prd_template", "id" => "frm_prd_template", 'data-parsley-validate' => "");
echo form_open_multipart('', $attributes);
?>
      <input type="hidden" name="hdn_submit_status" id="hdn_submit_status" value="1" />
      <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h4 class="modal-title">
          <div class="modelTaskTitle"> <?php echo lang('EST_TITLE_PREVIEW_PRD_MARGIN');?> </div>
        </h4>
      </div>
      <div class="modal-body">
				<div class="table table-responsive">
                <table class="table table-striped table-bordered dataTable">
			<thead>
<tr>
				<th class="col-md-2"><?php echo lang('EST_LABEL_PRODUCT_NAME'); ?></th>
				<th  class="col-md-1"><?php echo lang('EST_LABEL_QUANTITY'); ?></th>
				<th class="col-md-1"><?php echo lang('EST_LABEL_TAX'); ?></th>
				<th class="col-md-2"><?php echo lang('EST_LABEL_AMOUNT'); ?></th>
				<th class="col-md-2"><?php echo lang('product_ppu'); ?></th>
				<th class="col-md-2"><?php echo lang('EST_LABEL_MARGIN'); ?></th>
			</tr>
</thead>
			<?php foreach($previewAllProduct as $singleProduct){ ?>
			<tr>
			<td><?php echo $singleProduct['product_name'];?></td>
			<td><?php echo $singleProduct['product_qty'];?></td>
			<td><?php echo $singleProduct['tax_percentage'];?></td>
			<?php $Symbol = getCurrencySymbol($editRecord[0]['country_id_symbol']); ?>
				  <?php if(isset($singleProduct['product_sales_price']) && $singleProduct['product_sales_price'] != "")
				{
					$prdSalesPrice 		= $singleProduct['product_sales_price']; 
				} else {
					$prdSalesPrice 		= $singleProduct['sales_price_unit'];
				}?>
			<td><?php echo $Symbol.$prdSalesPrice;?></td>
			<td><?php echo $Symbol.$singleProduct['purchase_price_unit'];?></td>
			<?php $grossMargin = $prdSalesPrice - $singleProduct['purchase_price_unit']; ?>
			<td><?php echo $Symbol.$grossMargin;?></td>
			</tr>
			<?php } ?>
			</table></div>
	 
		</div>
      <div class="modal-footer">
        <center>
        </center>
      </div>
      <?php echo form_close(); ?> </div>
  </div>
</div>
<div id="printDIV" class=""></div>
<?php /*Created Script for Preview page only */?>
<script>
	function showMarginPopup()
	{
		$('#prdMarginShow').modal('show');
	}
</script>
<?php if(isset($estAction) && $estAction == "pdf"){?>
<script>
        $(document).ready(function () {
            GeneratePDF('<?php echo $editRecord[0]['estimate_id'];?>');
        });
    </script>
<?php }?>
<?php if(isset($estAction) && $estAction == "print"){?>
<script>
        $(document).ready(function () {
            GeneratePrint('<?php echo $editRecord[0]['estimate_id'];?>');
        });
    </script>
<?php }?>
<?php if(isset($estAction) && $estAction == "sendEstimate"){?>
<script>
        $(document).ready(function () {
            SendEstimate('<?php echo $editRecord[0]['estimate_id'];?>');
        });
    </script>
<?php }?>
<script>
    var inc = $('#productBox').find('a').length + 1;
    var newProductboxIncrement = 1;
    var newProductGroupboxIncrement = $('[name="product_group_id[]"]').length + 1;
    var textParagraphIncrement = $('#textParagraphSection').find('textarea').length + 1;
    function toggle_show(className, obj) {
        var $input = $(obj);
        if ($input.prop('checked'))
            $(className).show();
        else
            $(className).hide();
    }
    $(document).ready(function () {
        //Set Editor For Estimate Content
        $('#est_content,#est_userdescription,#text_paragraph,#est_termcondition').summernote({
            height: 150,   //set editable area's height
            codemirror: { // codemirror options
                theme: 'monokai'
            }
        });
        $('#frmsubmit').parsley();
        $('#creation_date').datepicker({autoclose: true,startDate: '-0m'});
        /*$('#creation_date').datepicker().on('changeDate', function (ev) {

         $('#creation_date').datepicker('hide');
         });*/
        $('.chosen-select').chosen({placeholder_text_single: "Product Group",
            no_results_text: "Oops, nothing found!"});
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
        doSubtotal();
		
		//Script for Drag and Drop Product In Estimate Preview
       /* $("#tableRowContainer").sortable({
            tolerance: 'pointer',
            revert: 'invalid',
            forceHelperSize: true,
            stop: function (event, ui) {
                //Stop function call after drag stop
                var sortorder = [];
                //console.log(ui);
                $('#tableRowContainer .tableRowSortable').each(function () {
                    sortorder.push($(this).attr('id'));
                });
                //Ajax call for Update Product order
                $.ajax({
                    url: "<?php echo base_url('Estimates/ProductSortOrderUpdate'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {'productOrderArray': sortorder},
                    success: function (data)
                    {
                        //Success msg pass here
                    }
                });
            }
        }).disableSelection();
    //Script for Drag and Drop Div In Estimate preview
        $(".parentSortable").sortable({
            connectWith: '.parentSortable',
            stop: function (event, ui) {
                //Stop function call after drag stop
                var sortorder = [];
                var estimate_id = $('#estimate_id').val();
                
                $('#previewBox .parentSortable .CustWhiteBorder').each(function () {
                    sortorder.push($(this).attr('id'));
                });
            //Ajax call for Update Product order
                $.ajax({
                    url: "<?php echo base_url('Estimates/StoreWidgets'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {'order': sortorder,'action':'content','estimate_id': estimate_id},
                    success: function (data)
                    {
                        //Success msg pass here
                    }
                });
            }
        }).disableSelection();
        
        $("#headerContainer").sortable({
            tolerance: 'pointer',
            revert: 'invalid',        
            forceHelperSize: true,
            stop: function (event, ui) {
                //Stop function call after drag stop
                var HeaderSortOrder = [];
                var estimate_id = $('#estimate_id').val();
                $('#headerContainer .CustHeaderSection').each(function () {
                    HeaderSortOrder.push($(this).attr('id'));
                });
            //Ajax call for Update Product order
                $.ajax({
                    url: "<?php echo base_url('Estimates/StoreWidgets'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {'order': HeaderSortOrder,'action':'header','estimate_id': estimate_id},
                    success: function (data)
                    {
                        //Success msg pass here
                    }
                });
            }
        }).disableSelection();*/
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
                $('#newProductGroupBox').append(data);
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
                    $('.chosen-select').chosen();
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
                        $('#ProductLabel').show();
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
        var singleAmount = [];
        var singleQuantity = [];
        var taxid = [];
        var taxes = [];
        var discount = '';
        var discountOption = '';
        //Get Discount option as per selected
        if($("#discount_Opt").val())
        {
            discountOption = $("#discount_Opt").val();
        } else {
            discountOption = $("#discount_Opt_edit").val();
        }
        $(".prdAmtSingle").each(function () {
            singleAmount.push($(this).val());
        });
        $(".prdQtySingle").each(function () {
            singleQuantity.push($(this).val());
        });
        $(".prdTaxidSingle").each(function () {
            taxid.push($(this).val());
        });
        $(".product_amount").each(function () {
            amount.push($(this).val());
        });
        $(".product_tax_calculated").each(function () {
            taxes.push($(this).val());
        });
        discount = $('#discount').val();
        $.ajax({
                url: "<?php echo base_url('Estimates/productCalculationSubTotal'); ?>",
                type: "POST",
                data: {discountOption: discountOption, tax_id:taxid, singleQuantity: singleQuantity, singleAmt: singleAmount, tax: taxes, amount: amount, discount: discount},
                success: function (data)
                {
                    $('#subtotalSection').html(data);
                    $('.chosen-select').chosen({disable_search_threshold: 10});
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
                    $('#text_paragraph_'+textParagraphIncrement).summernote({
                        height: 150,   //set editable area's height
                        codemirror: { // codemirror options
                            theme: 'monokai'
                        }
                    });
                    textParagraphIncrement++;
                    //Set Editor For Estimate Content
                }
            }
        );

    }
    /**
     * this function is remove Autograph Image Div
     */
    function RemoveAutographImg(el,ShowDiv)
    {
        BootstrapDialog.show(
            {
                title: 'Estimate Module',
                message: 'Are you sure want to delete Autograph?',
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {
                        $(el).remove();
                        $(ShowDiv).show();
                        dialog.close();
                    }
                }]
            });


        //$(el).remove();
    }
    /**
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
                                    $('.chosen-select').chosen();
                                    doSubtotal();
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
        var ShowMsg = '';					//Set Msg for Bootstrap
        var hdn_submit_status = "";       	//Set Value for hidden Status
        var action = '';					//Set value for its related with Bootstrap or Just save the information
        if (val == 'draft')
        {
            ShowMsg = 'Are you sure want to save estimate as a Draft?';
            hdn_submit_status = "2";       //2 Value for Draft
            action = '1';
        } else if (val == 'preview')
        {
            ShowMsg = 'Before make preview you require to save the current Estimation, Are you sure want to continue?';
            hdn_submit_status = "1";
            action = '1';
        } else if (val == 'pdf')
        {
            ShowMsg = 'Before export in PDF you require to save the current Estimation, Are you sure want to continue?';
            hdn_submit_status = "1";
            action = '1';
        } else if (val == 'print')
        {
            ShowMsg = 'Before make Print you require to save the current Estimation, Are you sure want to continue?';
            hdn_submit_status = "1";
            action = '1';
        } else if (val == 'sendEstimate')
        {
            ShowMsg = 'Before Send Estimate you require to save the current Estimation, Are you sure want to continue?';
            hdn_submit_status = "1";
            action = '1';
        } else {
            action = '0';
        }
        if(action == 1)
        {
            BootstrapDialog.show(
                {
                    title: 'Estimate Module',
                    message: ShowMsg,
                    buttons: [{
                        label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                        action: function(dialog) {
                            dialog.close();
                        }
                    }, {
                        label: '<?php echo $this->lang->line('ok');?>',
                        action: function(dialog) {
                            $("#hdn_submit_status").val(hdn_submit_status);
                            $("#HdnSubmitBtnVlaue").val(val);
                            $("#frmsubmit").submit();
                            dialog.close();
                        }
                    }]
                });
        } else {
            $("#hdn_submit_status").val("1");
            $("#HdnSubmitBtnVlaue").val(val);
            $("#frmsubmit").submit();
        }
        return false;
    }
    function GeneratePDF($estimate_id)
    {
        send_url = '<?php echo base_url();?>Estimates/DownloadPDF/'+ $estimate_id;
        window.location.href = send_url;
    }
    function SendEstimate($estimate_id)
    {
		BootstrapDialog.show(
            {
                title: 'Estimate Module',
                message: "Are you sure want to send Estimate?",
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {

                        var chngEmlTmp = '';
                        /*if(emailTempSts)
                         {
                         chngEmlTmp = emailTempSts;
                         }*/
                        var loaderIMG = '<img src="<?php echo base_url()."/uploads/images/ajax-loader.gif"; ?>" /> Sending Estimate To Recipient.' ;
                        $('#errorMsgLoader').html(loaderIMG);
                        var newEmailSubject		= '';
                        var newEmailTemplateBody= '';
                        if(chngEmlTmp == 'takeEmailContent')
                        {
                            var newEmailSubject 		= $('#emailTemplate_sub').val();
                            var newEmailTemplateBody 	= $('#emailTemplate_body').code();
                        }
                        send_url = '<?php echo base_url();?>Estimates/SendEstimate/'+ $estimate_id;
                        $.ajax({
                            url: send_url,
                            type: "POST",
                            //dataType: "json",
                            data: {'newEmailSubject': newEmailSubject, 'newEmailTemplateBody' : newEmailTemplateBody, 'chngEmlTmp': chngEmlTmp},
                            success: function (data)
                            {
                                //alert('Success send message.');
                                //Show Message in Top side div
                                $('#errorMsg').html("<div class='alert alert-success text-center'>Send Estimate To Recipient Successfully.</div>");
                                $('.chosen-select').chosen();
                                //Hide Error Loader Message Div when mail send
                                //$('#errorMsgLoader').hide();
                                $('#errorMsgLoader').html("");
                                //Hide Message after 3 second
                                setTimeout(function () {
                                    $('#errorMsg').html("");
                                    //$('#errorMsg').fadeOut('5000');
                                }, 3000);
                            }
                        });

                        dialog.close();
                    }
                }]
            });

	}
    function GeneratePrint($estimate_id)
    {
        $('#printDIV').html("");
        send_url = '<?php echo base_url();?>Estimates/DownloadPDF/'+ $estimate_id + '/print';
        $.ajax({
            url: send_url,
            type: "GET",
            data: {},
            success: function (data)
            {
                $('#printDIV').html(data);
                $("#printDIV").print();
                $('#printDIV').html("");
                return (false);
            }
        });
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
