<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord) ? 'insertdata' : 'insertdata';
$path = $ctr_view . '/' . $formAction;
?>

<!-- Example row of columns -->

<div class="row">
  <div class="col-md-6 col-xs-6 col-lg-6">
   
      <?php echo $this->breadcrumbs->show(); ?>
  
  </div>
  <div class="clr"></div>
  <div class="col-xs-12 col-md-12 col-lg-12">
    <?php
        $attributes = array("name" => "frmsubmit", "id" => "frmsubmit", "data-parsley-validate" => "");
        echo form_open_multipart($path, $attributes);
        ?>
   
                        
    <input type="hidden" name="hdn_submit_status" id="hdn_submit_status" value="1" />
    <input type="hidden" name="HdnSubmitBtnVlaue" id="HdnSubmitBtnVlaue" value="save" />
    <input type="hidden" name="HdnChangeEmailTmp" id="HdnChangeEmailTmp" value="no" />
    <input type="hidden" name="client_name" id="client_name" value="<?php echo $client_name; ?>">
    <input type="hidden" name="discount_Opt_edit" id="discount_Opt_edit" value="<?php echo $editRecord[0]['discount_option']; ?>">
    <input type="hidden" name="country_id_symbol_edit" id="country_id_symbol_edit" value="<?php echo $editRecord[0]['country_id_symbol']; ?>">
	<input type="hidden" name="estimate_id" id="estimate_id" value="<?php echo $editRecord[0]['estimate_id']; ?>">
    <div class="whitebox">
	 <div class="col-xs-12 col-md-8 bg-gray mt15 mb15 col-lg-8 col-lg-offset-2 col-md-offset-2"><div class="form-group  text-right">
		<div id="errorMsgLoader" class="text-center"> </div>
      <div id="errorMsg"> <?php echo $this->session->flashdata('msg'); ?> </div>
		<a class="btn btn-blue" href="<?php echo base_url('Estimates/edit/'.$editRecord[0]['estimate_id']);?>"><?php echo lang('EST_TITLE_PREVIEW_BACK');?></a> &nbsp; <a href="javascript:;" class="btn btn-blue" onclick="SendEstimate('<?php echo $editRecord[0]['estimate_id'];?>');"><?php echo lang('EST_LBL_SEND_EST');?></a> &nbsp; <a class="btn btn-blue" href="javascript:;"><?php echo lang('EST_LBL_VISIBLE_FOR_CLIENT');?></a>&nbsp; <a onclick="GeneratePDF('<?php echo $editRecord[0]['estimate_id'];?>');" class="btn btn-blue" href="javascript:;"><?php echo lang('EST_LBL_PDF');?></a>&nbsp; <a onclick="GeneratePrint('<?php echo $editRecord[0]['estimate_id'];?>');" class="btn btn-blue" href="javascript:;"><?php echo lang('EST_LBL_PRINT');?></a>&nbsp; <a onclick="showMarginPopup();" class="btn btn-blue" data-href="javascript:;"><?php echo lang('EST_LABEL_SHOW_MARGIN');?></a> </div>
		<div class="mb15 col-sm-8 col-xs-12"><h3><b><?php echo lang('EST_LABEL_PREVIEW_ESTIMATE');?></b></h3></div>
    <?php /*RJ Change col-xs-12 col-md-6 col-lg-6 To col-xs-12 col-md-6 col-lg-4 */?>
      <div class="col-xs-12  col-lg-12">
        <div class="clr"></div>
        <!-- Preview Starts here -->
			<?php $this->load->view('estimatePreview'); ?>
        <!-- Preview Ends here -->
        <div class="clr"></div>
      </div>
    <div class = "clr"></div></div><div class = "clr"></div>
    </div>
    <?php echo form_close(); ?>
    <div class="clr"></div>
  </div>
</div>
<div class="clr"></div>
<br/>
<div class="modal fade modal-image" id="modalGallery" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" onClick="$('#modalGallery').modal('hide');" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo lang('EST_PREVIEW_UPLOADS'); ?></h4>
      </div>
      <div class="modal-body" id="modbdy"> </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onClick="$('#modalGallery').modal('hide');"><?php echo lang('EST_PREVIEW_CLOSE'); ?></button>
      </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<!-- /.modal --> 
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
          <div class="col-xs-12 col-sm-12 col-lg-12">
            <label> <?php echo lang('EST_PREVIEW_TEMPLATE_NAME'); ?> *</label>
            <input type="text" required="" value="" placeholder="<?php echo lang('EST_TITLE_PRD_TEMPLATE_NAME'); ?>" name="est_temp_name" id="est_temp_name" class="form-control">
          </div>
        </div>
        
        <!--<a onchange="return addNewTemplate(this.value);" >Save</a>--> 
      </div>
      <div class="modal-footer">
        <center>
          <a onclick="addNewTemplate();" href="javascript:;">
          <input type="button" value="<?php echo lang('EST_EDIT_SAVE'); ?>" name="remove" class="btn btn-info">
          </a>
        </center>
      </div>
      <?php echo form_close(); ?> </div>
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
			<div class="table table-responsive"><table class="table table-striped table-bordered dataTable">
			<thead>
<tr>
				<th class="col-md-2"><?php echo lang('EST_LABEL_PRODUCT_NAME'); ?></th>
				<th class="col-md-1"><?php echo lang('EST_LABEL_QUANTITY'); ?></th>
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
<?php /*Model Popup for Change Email Template */?>
<!-- Modal -->
<div class="modal in" id="estChangeEmailTemplate" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <?php
//$attributes = array("name" => "frm_prd_template", "id" => "frm_prd_template", 'data-parsley-validate' => "");
//echo form_open_multipart('', $attributes);
?>
      <input type="hidden" name="hdn_submit_status" id="hdn_submit_status" value="1" />
      <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h4 class="modal-title">
          <div class="modelTaskTitle"> <?php echo lang('EST_TITLE_PRD_EMAIL_TEMPLATE'); ?> </div>
        </h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="emailTemplate_sub">
            <?=$this->lang->line('emailTemplate_sub')?>
            *</label>
          <input class="form-control" name="emailTemplate_sub" id="emailTemplate_sub" placeholder="<?=$this->lang->line('emailTemplate_sub')?>" type="text" value="<?=!empty($EmailTMPInfo[0]['subject'])?$EmailTMPInfo[0]['subject']:''?>" required="" />
        </div>
        <div class="form-group">
          <label for="emailTemplate_body">
            <?=$this->lang->line('emailTemplate_body')?>
            *</label>
          <ul class="parsley-errors-list filled hidden" id="emailTemplate_body_Error" >
            <li class="parsley-required"><?php echo lang('EST_ADD_LABEL_REQUIRED_FIELD'); ?></li>
          </ul>
          <textarea class="form-control" id="emailTemplate_body" name="emailTemplate_body" placeholder="<?=$this->lang->line('emailTemplate_body')?>" value="" ><?=!empty($EmailTMPInfo[0]['body'])?$EmailTMPInfo[0]['body']:''?>
</textarea>
        </div>
      </div>
      <div class="modal-footer">
        <center>
          <a onclick="estSendWithCustEmailTemp();" href="javascript:;">
          <input type="button" value="<?php echo lang('EST_EDIT_SAVE'); ?>" name="remove" class="btn btn-info">
          </a>
        </center>
      </div>
      <?php //echo form_close(); ?>
    </div>
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
<?php if(isset($ESTChngEmiTMP) && $ESTChngEmiTMP == "yes"){?>
<script>
		$(document).ready(function () {
			$('#estChangeEmailTemplate').modal('show');
			//GeneratePrint('<?php echo $editRecord[0]['estimate_id'];?>');
		});
	</script>
<?php } else {?>
<script>
		$(document).ready(function () {
			 SendEstimate('<?php echo $editRecord[0]['estimate_id'];?>');
		});
	</script>
<?php }?>
<?php }?>
<script>
    var inc = $('#productBox').find('a').length + 1;
    var newProductboxIncrement = 1;
    var newProductGroupboxIncrement = $('[name="product_group_id[]"]').length + 1;
    var textParagraphIncrement = $('#textParagraphSection').find('textarea').length + 1;
	/*
	 * This function use for show including tax and Excluding tax 
	 */
	function estTaxOptChngPrice(option)
	{
		$(".prdAmtSingle").each(function(){
			if($(this).val())
			{
				if(option == 'incTax')
				{
					var snglPrdTaxAttrPrice = $(this).attr("data-taxincluded-amount");
					$('.prdGrpTaxAmtInclud').removeClass('hidden');
					$('.prdGrpFnlAmtInclud').removeClass('hidden');
					$('.subTotalTaxArea').addClass('hidden');
				} else {
					var snglPrdTaxAttrPrice = $(this).attr("data-taxexclude-amount");
					$('.prdGrpTaxAmtInclud').addClass('hidden');
					$('.prdGrpFnlAmtInclud').addClass('hidden');
					$('.subTotalTaxArea').removeClass('hidden');
				}
				$(this).val(snglPrdTaxAttrPrice);
			}
		});
	}
	function autographToggle_show(className, obj, signtrSelectBoxBlnk) {
		var $input = $(obj);
		if(signtrSelectBoxBlnk == 'yes')
		{
			//alert('Make Select Box Blnk');
			$(".signatureTypeChosen").val('').trigger("chosen:updated");
			$('#signature').hide();			//Hide Signature Div
			$('#signature-upload').hide();	//Hide Signature Upload File input div
		}
		if ($input.prop('checked')){
            $(className).show();
            $('#signatureImgDiv').show();
		} else {
            $(className).hide();			//Hide Signature Option Select Box Div
			$('#signatureImgDiv').hide();	//Hide Signature Image Div 
		}
	}
    function toggle_show(className, obj) {
        var $input = $(obj);
        if ($input.prop('checked'))
            $(className).show();
        else
            $(className).hide();
    }
    $(document).ready(function () {
	//Assign Chosen to Signature 
		$('.signatureTypeChosen').chosen();
	//Set Editor For Estimate Content
		$('#est_content,#est_userdescription,#text_paragraph,#emailTemplate_body').summernote({
			height: 150,   //set editable area's height
			  codemirror: { // codemirror options
				theme: 'monokai'
			}
		});
        $('#frmsubmit').parsley();
		$('#creation_date').datepicker({autoclose: true,startDate: '-0m'});
        $('#signature_date').datepicker({autoclose: true,startDate: '-0m'});
		$('.chosen-select').chosen({placeholder_text_single: "Product Group",
            no_results_text: "Oops, nothing found!"});
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
        doSubtotal();

    //Script for Drag and Drop Product In Estimate Preview
        $("#tableRowContainer").sortable({
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
        }).disableSelection();
    
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
                $('#newProductGroupBox').append(data);
				$('.chosen-select').chosen();
			//Change Currency as per in Select Box
				chngCurrencySym();
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
				var discountOpt = $("#product_disOption_"+incId).val();
				var discountVal = $("#product_discount_"+incId).val();
				var taxWithfinalAmt = data.data.sales_price_unit;
				var taxValueForInfo = "";
				var taxID 			= "";
			//Make Discount Calculation 
				if(discountVal != "")
				{
					if(discountOpt == 'prsnt')
					{
						var discountedAmt 		= ( taxWithfinalAmt * discountVal ) / 100;
						var discountedDeduct 	= taxWithfinalAmt - discountedAmt;
					}
					if(discountOpt == 'amt')
					{
						var discountedDeduct 	= taxWithfinalAmt - discountVal;
					}
				}
				taxWithfinalAmt = discountedDeduct;
			//If Condition for not show Deleted Product 
				if(data.data.is_delete == 1){  } else {
					if(data.data.tax_percentage)
					{
						taxID = data.data.product_tax_id;
						taxValueForInfo = data.data.tax_percentage;
					//Remove after use
					//Calculate Tax as per Price and tax 
						var taxAmtCal = (taxWithfinalAmt * data.data.tax_percentage) / 100 ;
					//Place Calculated Tax in hidden field 
						$('#product_tax_calculated_' + incId).val(taxAmtCal);
					//Calculate Final Value
						taxWithfinalAmt = (parseFloat(taxAmtCal) + parseFloat(taxWithfinalAmt)).toFixed(2);
					}
				}
				$('#product_name_' + incId).val(data.data.product_name);
                $('#product_description_' + incId).val(data.data.product_description);
            //$('#product_qty_' + incId).value(data.product_name);
                $('#product_tax_' + incId).val(taxID);
				$('#taxValueForInfo_' + incId).val(taxValueForInfo);
			//Final amount with Tax
                $('#product_amount_' + incId).val(taxWithfinalAmt);
            //Product Price without Tax
				$('#product_amount_sales_' + incId).val(data.data.sales_price_unit);
			//Product Price in Attribute with Tax
				$('#product_amount_sales_' + incId).attr("data-taxincluded-amount", taxWithfinalAmt);
			//Product Price in Attribute Without Tax
				$('#product_amount_sales_' + incId).attr("data-taxexclude-amount", data.data.sales_price_unit);
                doSubtotal();
            }
        });
    }
    /**
	  * Add Single Product Price and Tax in attribute 
	  */
	function addAmtInTaxIncludedAttr(amount, incId, type)
	{
		var tax = $('#product' + type + '_tax_calculated_' + incId).val();
	//Place Tax Amount in data-taxincluded-amount attribute
		var fnlAmtWithTax = parseFloat(amount) + parseFloat(tax);
		//alert(fnlAmtWithTax.toFixed(2));
		$('#product' + type + '_amount_sales_' + incId).attr('data-taxincluded-amount', fnlAmtWithTax);
		$('#product' + type + '_amount_sales_' + incId).attr('data-taxexclude-amount', amount);
	}
	/**
     * this function is Add Currency Symbol before amount
     */
	function chngCurrencySym()
	{
		var curSym = $("#country_id_symbol option:selected").text();
		if(curSym)
		{
			$('.estCurSymbolDiv').html(curSym);
		}
	}
	/**
     * this function is used for calculation of the product amount 
     */
    function manageProductAmount(qty, tax, amount, incId, type, discount, discountOption)
    {
		if ($.isNumeric(qty) && qty > 0)
        {
		//Change Currency as per in Select Box
			chngCurrencySym();
		//Start Ajax Call Calculation
            $.ajax({
                url: "<?php echo base_url('Estimates/productCalculationTaxesQty'); ?>",
                type: "POST",
                data: {qty: qty, tax: tax, amount: amount, discount: discount, discountOption: discountOption},
                dataType: "JSON",
                success: function (data)
                {
                    $('#product' + type + '_amount_' + incId).val(data.total.toFixed(2));
                    $('#product' + type + '_tax_calculated_' + incId).val(data.tax);
				//addAmtInTaxIncludedAttr function call for set updated Attribute in Price / This function set updated Tax in Product Price
					//addAmtInTaxIncludedAttr(amount, incId, type);
				//Call Final Sum of Calculation
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
		var amount 			= '';
        var qty 			= currVal;
        var tax 			= $('#product' + type + '_tax_' + incId).val();
        amount 				= $('#product' + type + '_amount_sales_' + incId).val();
		var discount 		= $('#product' + type + '_discount_' + incId).val();
		var discountOption 	= $('#product' + type + '_disOption_' + incId).val();
        manageProductAmount(qty, tax, amount, incId, type, discount, discountOption);
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
        var discount 	= $('#product' + type + '_discount_' + incId).val();
		var discountOption = $('#product' + type + '_disOption_' + incId).val();
        manageProductAmount(qty, tax, amount, incId, type, discount, discountOption);
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
		var discount 	= $('#product' + type + '_discount_' + incId).val();
		var discountOption = $('#product' + type + '_disOption_' + incId).val();
        manageProductAmount(qty, tax, amount, incId, type, discount, discountOption);
    }
	/**
	 * Add Attribute in Discount as per selected % and $
	 */
	function addAttrAsPerDiscountOption(currVal, incId, type)
	{
		if( currVal == 'prsnt')
		{
			var test = "^([0-9]{1,3}){1}(\.[0-9]{1,2})?$";
			$('#product' + type + '_discount_' + incId).removeAttr('data-parsley-pattern');
			$('#product' + type + '_discount_' + incId).attr('data-parsley-pattern', test);
			$('#product' + type + '_discount_' + incId).attr('data-parsley-range', "[0, 100]");
		}
		if( currVal == 'amt')
		{
			var test = "^([0-9]{1,8}){1}(\.[0-9]{1,2})?$";
			$('#product' + type + '_discount_' + incId).removeAttr('data-parsley-pattern');
			$('#product' + type + '_discount_' + incId).removeAttr('data-parsley-range');
			$('#product' + type + '_discount_' + incId).attr('data-parsley-pattern', test);
		}
	}
	/**
	 * Make Discount Option Calculation
	 */
	function calDisAmtOptFrm(currVal, incId, type)
    {
		var qty 	= '';
        var amount 	= '';
		var tax 	= '';
		var discountOption = currVal;
		var discount = $('#product' + type + '_discount_' + incId).val();
        qty 		= $('#product' + type + '_qty_' + incId).val();
		tax 		= $('#product' + type + '_tax_' + incId).val();
        amount 		= $('#product' + type + '_amount_sales_' + incId).val();
	//Call addAttrAsPerDiscountOption function for add Validation Attribute
		addAttrAsPerDiscountOption(currVal, incId, type);
	//Call manageProductAmount for Calculation
		manageProductAmount(qty, tax, amount, incId, type, discount, discountOption);
	}
	/**
	 * Make Discount Calculation
	 */
    function calDisAmtFrm(currVal, incId, type)
    {
		var qty 	= '';
        var amount 	= '';
		var tax 	= '';
        var discount = currVal;
        qty 		= $('#product' + type + '_qty_' + incId).val();
		tax 		= $('#product' + type + '_tax_' + incId).val();
        amount 		= $('#product' + type + '_amount_sales_' + incId).val();
		var discountOption = $('#product' + type + '_disOption_' + incId).val();
		manageProductAmount(qty, tax, amount, incId, type, discount, discountOption);
	}
    /**
     * 
     * this function is used for calculation of the product amount when tax changes
     */
    function changeAmountValueTax(currVal, incId, type)
    {
		var selectText = $("#product"+ type +"_tax_" + incId + " :selected").text();
		$("#" + type + "TaxValueForInfo_" + incId).val(selectText);	//Add Text in _newTaxValueForInfo_1
		
        var qty = $('#product' + type + '_qty_' + incId).val();
        var tax = currVal;
        var amount = $('#product' + type + '_amount_sales_' + incId).val();
		var discount 	= $('#product' + type + '_discount_' + incId).val();
		var discountOption = $('#product' + type + '_disOption_' + incId).val();
        manageProductAmount(qty, tax, amount, incId, type, discount, discountOption);
    }
	/* 
	 *checkValidTotalAmtOrNot function for check Final Total amount valid or not */
	function checkValidTotalAmtOrNot()
	{
		var finalAmount = [];
		$(".product_amount").each(function () {
			if($(this).val() && $(this).val() != 0) {
			finalAmount.push($(this).val());
			}
		});
		return finalAmount.length;
	}
	/**
     * this function is used for Get All Tax Percentage value and Create return In Array Formate
     */
	function createTaxValArray()
	{
		var taxPercentageValue = [];
		var taxCalAmt = [];
		var taxPercentageValueObj = {};
		var taxCalAmt = [];
		$(".prdAllTaxPercentageVal").each(function () {
			taxPercentageValue.push($(this).val());
		});

	//"prdPerticularValue" This class is second option if "product_tax_calculated"  Class not worked
		$(".product_tax_calculated").each(function (){
			taxCalAmt.push($(this).val());
		});

		for(var i=0;i<taxPercentageValue.length;i++){
			if(taxPercentageValueObj[taxPercentageValue[i]]==undefined){
				taxPercentageValueObj[taxPercentageValue[i]] = [];
				taxPercentageValueObj[taxPercentageValue[i]].push(taxCalAmt[i]);
			}else{
				taxPercentageValueObj[taxPercentageValue[i]].push(taxCalAmt[i]);
			}
		}
		//console.log(taxPercentageValue);	//Show all Percentage Value
		//console.log(taxCalAmt);			//Show all Tax Calculated Amount
		return taxPercentageValueObj;		//Return Percentage wise Array 
	}
    /**
     * 
     * this function is used for calculation of subtotal
     */
    function doSubtotal()
    {
	//Change Currency as per in Select Box
		chngCurrencySym();
	//Amount array for execute Subtotal DIV if Products are available
		if(checkValidTotalAmtOrNot() != 0) {
			var amount 			= [];
			var singleAmount 	= [];
			var singleQuantity 	= [];
			var taxid 			= [];
			var taxes 			= [];
		/*Product Wise Discount and Discount Option*/
			var prdSngDiscount 	= [];
			var prdsngDiscountOpt = [];
		/*Remove Following code at the end*/
			var discount 		= '';
			var discountOption 	= '';
		//Get Discount option as per selected
			/* Remove Following code at the end
			if($("#discount_Opt").val())
			{
				discountOption = $("#discount_Opt").val();
			} else {
				discountOption = $("#discount_Opt_edit").val();
			}*/
			//discount = $('#discount').val();
		//Get Country Symbol ID option as per selected
			if($("#country_id_symbol").val())
			{
				cntSymbol = $("#country_id_symbol").val();
			} else {
				cntSymbol = $("#country_id_symbol_edit").val();
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
			$(".prdDisSng").each(function () {
				prdSngDiscount.push($(this).val());
			});
			$(".prdDiscountOptSng").each( function () {
				prdsngDiscountOpt.push($(this).val());
			});
		//Get Tax Array 
			var allTaxInArray = createTaxValArray();
			
			$.ajax({
				url: "<?php echo base_url('Estimates/productCalculationSubTotal'); ?>",
				type: "POST",
				data: {allTaxInArray: allTaxInArray, cntSymbol: cntSymbol, tax_id:taxid, singleQuantity: singleQuantity, singleAmt: singleAmount, tax: taxes, amount: amount, prdSngDiscount: prdSngDiscount, prdsngDiscountOpt: prdsngDiscountOpt, discountOption: discountOption, discount: discount},
				success: function (data)
				{
					$('#prdErrorMsg').html('');
					$('#subtotalSection').html(data);
					$('.chosen-select').chosen({disable_search_threshold: 10});
				}
			});
		} else {
			$('#prdErrorMsg').html('');
			$('#subtotalSection').empty();
		}
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
				getPrdGroupData(dataVal, incId);
                doSubtotal();
            }
        }
        );
    }
	/**
     * this function is used to display Group Related information by group id
     */
	function getPrdGroupData(dataVal, incId)
	{
		$('#prod-group-relatedData-'+incId).removeClass('hidden');
		$.ajax({
            url: "<?php echo base_url('Estimates/getGroupDataByGroupId'); ?>/" + dataVal,
            type: "GET",
			dataType: "JSON",
            data: {incVar: incId},
            success: function (data)
            {
				$("#product_group_total_amt_" + incId).val(data.data.product_group_total_amt);
			//product_group_discounted_amt means Final Amount after deduct Discount
				$("#product_group_discounted_amt_" + incId).val(data.data.product_group_discounted_amt);
			//Set Tax amount in Tax Value
				$("#product_group_tax_amt_" + incId).val(data.data.product_group_tax_amt);
			//Set Tax Amount in data-main-tax Attribute
				$("#product_group_tax_amt_" + incId).attr("data-main-tax", data.data.product_group_tax_amt);
			var fnlCalTaxPlusAmt = parseFloat(data.data.product_group_tax_amt) + parseFloat(data.data.product_group_discounted_amt);
				$("#product_group_fnl_amt_" + incId).val(fnlCalTaxPlusAmt);
				$("#product_group_qty_" + incId).val(1);
				//$('#prod-group-relatedData-' + incId).html(data);
				//doSubtotal();
            }
        }
        );
	}
	/**
     * this function is used for change Group Final amount
     */
	function chngGrpfnlAmt(qty, incId)
	{
		var fnlAmtArray 		= [];
		var grpDiscountedAmt 	= $("#product_group_discounted_amt_"+incId).val();
		var grpTotalTaxAmt 		= $("#product_group_tax_amt_"+incId).attr("data-main-tax");
		
		var calculateQty 		= (qty * grpDiscountedAmt);
		var qtyMultTax			= (qty * grpTotalTaxAmt);
		
		var calculateTax 		= (calculateQty+parseInt(qtyMultTax));
		
		//Class - product_group_qty_1
			$('.prdQtyGrpUniqNumber_'+incId).each(function(){
				var oldQty=$(this).attr('data-oldqty');	//Get Old quantity
				var qtyFnl = oldQty * qty;	//Multiple with Old Quantity and new Quantity
				$(this).val(qtyFnl).trigger('change');		//Place New Quantity in Input box
			});
			
			$("#product_group_fnl_amt_"+incId).val(calculateTax.toFixed(2));
			$("#product_group_tax_amt_"+incId).val(qtyMultTax.toFixed(2));
		//Set Time Out Function use execute after 2 second and get all Group product and make addition
			/*setTimeout(function () {
			//Get Group Product Final Amount and place in array 
				$('.prdGrpAmtUniqNumbr_'+incId).each(function(){
					fnlAmtArray.push($(this).val());
				});
			//Make Sum of Final Amount Array 
				var sumFnlAmtArray = eval(fnlAmtArray.join("+"))
			//Place Final Calculated amount in Input Box
				//console.log(sumFnlAmtArray);
				//console.log(fnlAmtArray);
				$("#product_group_fnl_amt_"+incId).val(calculateTax);
			}, 2000);*/
	//"changeAmountValueQty" function change in group product quantity
		//changeAmountValueQty(this.value, '1', '_group')"
	}
    /**
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
        if (data == 1)
        {
			$('#signature-upload').show();
            $('#signature-digital').val();
            $('#signature').hide();
        }
		if(data == "")
		{
			$('#signature').hide();
			$('#signature-upload').hide();
            $('#signature-digital').val("");
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
		var delete_meg ="Are you sure want to delete Autograph?";
        BootstrapDialog.show(
            {
                title: 'Estimate Module',
                message: delete_meg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {
                        $(el).remove();
                        $(ShowDiv).removeClass('hidden');
                        $('#signatureTypeDiv').show();
                        $(".signatureTypeChosen").val('').trigger("chosen:updated");
                        removeAutographFields();
                        dialog.close();
                    }
                }]
            });

    }
	function removeAutographFields()
	{
		$("#newDigitalSign").val('1');
		$("#signature_date").val('');
		$("#signature_place").val('');
		$("#signature_name").val('');
		$("#signature_jobrole").val('');
		$("#signature-digital").val('');
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
 * Following Function show Product Template Select Box With All template Name
 */
	function showPrdTempSelectBox()
	{
		$.ajax({
			url: "<?php echo base_url('Estimates/showPrdTempSelectBox'); ?>",
			type: "POST",
			data: {},
			success: function (data)
			{
				$('#showPrdTempSelectBoxDiv').html(data);
				$('.chosen-select').chosen();
			}
		});
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
            var delete_meg ="<?php echo lang('CONFIRM_EXPORT_ACCOUNTS');?>";
            BootstrapDialog.show(
                {
                    title: 'Estimate Template',
                    message: 'Are you sure want to save "' + est_temp_name + '" as a Estimate Template?',
                    buttons: [{
                        label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                        action: function(dialog) {
                            dialog.close();
                        }
                    }, {
                        label: '<?php echo $this->lang->line('ok');?>',
                        action: function(dialog) {

                            var product_id = [];					//Array For Single Product ID
                            var product_qty = [];					//Array For Single Product Quantity
                            var product_tax = [];					//Array For Single Product Tax
                            var product_amount = [];				//Array For Single Product Amount
                            var product_disOption = [];				//Array for Discount Option
                            var Product_discount  = [];				//Array For Product Discount

                            var product_group_id = [];				//Array For Product Group ID
                            var product_group_product_id = {};		//Object For Product ID
                            var product_group_productid_array = [];	//Array For Product ID Based on Group ID

                            //Push/Get Product ID in Array
                            $(".product_id").each(function () {
                                if ($(this).val())
                                {
                                    product_id.push($(this).val());
                                }
                            });
                            //Push/Get Product Discount Option in Array
                            $(".prdSngDisOpt").each(function () {
                                if ($(this).val())
                                {
                                    product_disOption.push($(this).val());
                                }
                            });
                            //Push/Get Product Discount in Array
                            $(".prdSngDiscount").each(function () {
                                if ($(this).val())
                                {
                                    product_discount.push($(this).val());
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
                                    var product_group_productid = [];				//Array For Group Product ID
                                    $(".product_group_product_id_" + value).each(function () {
                                        if ($(this).val())
                                        {
                                            product_group_productid.push($(this).val());
                                        }
                                    });
                                    //Push/Get Product Discount Option based On Group ID in Object
                                    var product_group_productDisOpt = [];				//Array For Group Product ID
                                    $(".prdGrpDisOpt_" + value).each(function () {
                                        if ($(this).val())
                                        {
                                            product_group_productDisOpt.push($(this).val());
                                        }
                                    });
                                    //Push/Get Product Discount based On Group ID in Object
                                    var product_group_productDiscount = [];				//Array For Group Product ID
                                    $(".prdGrpDiscount_" + value).each(function () {
                                        if ($(this).val())
                                        {
                                            product_group_productDiscount.push($(this).val());
                                        }
                                    });
                                    //Push/Get Product Quantity based On Group ID in Object
                                    var product_group_qty = [];				//Array For Group Product ID
                                    $(".product_group_qty_" + value).each(function () {
                                        if ($(this).val())
                                        {
                                            product_group_qty.push($(this).val());
                                        }
                                    });
                                    //Push/Get Product Tax based On Group ID in Object
                                    var product_group_tax = [];				//Array For Group Product ID
                                    $(".product_group_tax_" + value).each(function () {
                                        if ($(this).val())
                                        {
                                            product_group_tax.push($(this).val());
                                        }
                                    });
                                    //Push/Get Product Sales Amount based On Group ID in Object
                                    var product_group_amount = [];				//Array For Group Product ID
                                    $(".product_group_amount_" + value).each(function () {
                                        if ($(this).val())
                                        {
                                            product_group_amount.push($(this).val());
                                        }
                                    });
                                    //Create Product Group Object
                                    product_group_product_id[value] = {
                                        product_id		: product_group_productid,
                                        product_disOption: product_group_productDisOpt,
                                        product_discount: product_group_productDiscount,
                                        product_qty		: product_group_qty,
                                        product_tax		: product_group_tax,
                                        product_amount	: product_group_amount
                                    }
                                });
                                product_group_productid_array.push(product_group_product_id);
                            }
                            //console.log(product_group_productid_array);

                            $.ajax({
                                url: "<?php echo base_url('Estimates/insertTemplate'); ?>",
                                type: "POST",
                                data: {
                                    est_temp_name: est_temp_name,
                                    product_group_id	: product_group_id,
                                    group_product_information: product_group_productid_array,
                                    product_id			: product_id,
                                    product_disOption	: product_disOption,
                                    product_discount	: product_discount,
                                    product_qty			: product_qty,
                                    product_tax			: product_tax,
                                    product_amount		: product_amount
                                },
                                success: function (data)
                                {
                                    //Make Product Template Blank.
                                    $('#est_temp_name').val('');
                                    $('#prd_template_box').modal('hide');
                                    //Set One Ajax call for show Template Select box with new added Template
                                    showPrdTempSelectBox();
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
                }
            });
        }
    }
	function estSendWithCustEmailTemp()
	{
		var emailTemplate_sub 	= $('#emailTemplate_sub').val();
		var emailTemplate_body = $('#emailTemplate_body').code();
		if (emailTemplate_sub != "" && $.trim(emailTemplate_sub) != '' && emailTemplate_body !== '' && emailTemplate_body !== '<p><br></p>' && emailTemplate_body !== '<br>')
		{
			$("#emailTemplate_sub").addClass("form-control parsley-success");
			$("#emailTemplate_body_Error").addClass( "hidden");
			$('#estChangeEmailTemplate').modal('hide');
			
			SendEstimate('<?php echo $editRecord[0]['estimate_id'];?>', 'takeEmailContent');
		} else {
			if(emailTemplate_sub == "" && $.trim(emailTemplate_sub) == '')
			{
				$("#emailTemplate_sub").addClass("form-control parsley-error");
			} else {	$("#emailTemplate_sub").addClass("form-control parsley-success");	}
			if(emailTemplate_body !== '' && emailTemplate_body !== '<p><br></p>' && emailTemplate_body !== '<br>'){} else {
				$("#emailTemplate_body_Error").removeClass( "hidden");
			}
			return false;
		}
	}
    function SaveEstForm(val,emailTmpStatus)
    {
		var changeEmailTmp = '';
		if(emailTmpStatus)
		{
			changeEmailTmp = emailTmpStatus;
		}
		var ShowMsg = '';					//Set Msg for Bootstrap 
		var hdn_submit_status = "";       	//Set Value for hidden Status
		var action 	= '';					//Set value for its related with Bootstrap or Just save
		if (val == 'draft')
        {
			ShowMsg = 'Are you sure want to save estimate as a Draft?';
			hdn_submit_status 	= "2";       //2 Value for Draft
			action 				= '1';
		} else if (val == 'preview')
		{
			ShowMsg = 'Before make preview you require to save the current Estimation, Are you sure want to continue?';
			hdn_submit_status 	= "1";       
			action 				= '1';
		} else if (val == 'pdf')
		{
			ShowMsg = 'Before export in PDF you have to save the current Estimation, Are you sure want to continue?';
			hdn_submit_status 	= "1";       
			action 				= '1';
		} else if (val == 'print')
		{
			ShowMsg = 'Before make Print you have to save the current Estimation, Are you sure want to continue?';
			hdn_submit_status 	= "1";       
			action 				= '1';
		} else if (val == 'sendEstimate')
		{
			if(changeEmailTmp){
				ShowMsg = 'Before Change Email Template and Send Estimate you have to save the current Estimation, Are you sure want to continue?';
			} else {
				ShowMsg = 'Before Send Estimate you have to save the current Estimation, Are you sure want to continue?';
			}
			hdn_submit_status 	= "1";
			action 				= '1';
		} else {
			action = '0';
		}
		if(action == 1)
		{
		//Confirmation for Save Estimate 
			var delete_meg ="Estimate Module";
            BootstrapDialog.show(
                {
                    title: '<?php echo $this->lang->line('Information');?>',
                    message: delete_meg,
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
                            if(changeEmailTmp){
                                //Show Change Email Template Popup After Save
                                $("#HdnChangeEmailTmp").val(changeEmailTmp);   //Show popup for Change Template
                            }
                            formSbmitValidation();
                            dialog.close();
                        }
                    }]
                });
        } else {
			//$("#hdn_submit_status").val("1");           //default set 1 for Active   
			$("#hdn_submit_status").val($("#estStatus").val());                   
			$("#HdnSubmitBtnVlaue").val(val);   
			formSbmitValidation();
        }
		return false;
	}
//Below function for open template Popup
	function emailTemplatePopup()
	{
		BootstrapDialog.show(
		{
			title: 'Estimate Module',
			message: 'Are you want to change Email template before continue.',
			buttons: [{
                label: '<?php echo lang('EST_LBL_CHANGE_EMAIL_TEMPLATE');?>',
                action: function(dialog) {
					SaveEstForm('sendEstimate', 'yes');
                }
            }, {
                label: 'Send Estimate',
                action: function(dialog) {
					SaveEstForm('sendEstimate');
				}
            }]
			
		});
	}
	function formSbmitValidation()
	{
		var prdTotal = "";
	//Validation for Content section
		var estContent = $('#est_content').code();
		if(estContent !== '' && estContent !== '<p><br></p>' && estContent !== '<br>'){
			$("#est_content_Error").addClass( "hidden");
		}else{ $("#est_content_Error").removeClass( "hidden"); return false; }
	//Validation for Total Calculation
		if($('.prdTotal').val()){	prdTotal = $('.prdTotal').val();	}
		if(prdTotal == "undefined" || prdTotal == 0 || prdTotal == "" || prdTotal < 0 )
		{
			$('#prdErrorMsg').html('<ul id="" class="parsley-errors-list filled"><li class="parsley-min">Select Product Properly.</li></ul>');
		} else {
			$("#frmsubmit").submit();
		}
	}
    function GeneratePDF($estimate_id)
    {
		send_url = '<?php echo base_url();?>Estimates/DownloadPDF/'+ $estimate_id;
        window.location.href = send_url;
	}
	function SendEstimate($estimate_id, emailTempSts)
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
                        if(emailTempSts)
                        {
                            chngEmlTmp = emailTempSts;
                        }
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
		window.open(send_url, '_blank');
		//window.location.href = send_url;
		/*$.ajax({
                url: send_url,
                type: "GET",
                data: {},
                success: function (data)
                {
					//$('#printDIV').html(data);
					//$("#printDIV").print();
					//window.close(); 
					//window.location.href = window.location.href;
					//$('#printDIV').html("");
					return (false);
				}
            });*/
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
<script>
    /* image upload */

    $('.delimg').on('click', function () {

        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');
        var delete_meg ="Are your Sure to delete this item?";
        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: delete_meg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {
                        $('#deletedImagesDiv').append("<input type='hidden' name='softDeletedImages[]' value='" + str1 + "'> <input type='hidden' name='softDeletedImagesUrls[]' value='" + dataPath + '/' + imgName + "'>");
                        $('#' + divId).remove();
                        dialog.close();
                    }
                }]
            });

    });

    var config = {
        support: "*", // Valid file formats
        form: "demoFiler", // Form ID
        dragArea: "dragAndDropFiles", // Upload Area ID
        uploadUrl: "<?php echo $ctr_view; ?>/upload_file"				// Server side upload url
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
                        alert("File \"" + file.name + "\" is too big.Max allowed size is 20 MB.");
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
            xhr[rand].open("post", "<?php echo base_url('/Estimates/upload_file') ?>/" + fileext, true);

            xhr[rand].upload.addEventListener("progress", function (event) {
                //console.log(event);
                if (event.lengthComputable) {
                    $(".progress[id='" + rand + "'] span").css("width", (event.loaded / event.total) * 100 + "%");
                    $(".preview[id='" + rand + "'] .updone").html(((event.loaded / event.total) * 100).toFixed(2) + "%");
                }
                else {
                    alert("Failed to compute file upload length");
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
                            template += '<span class="preview" id="' + rand + '"><div class="image_ext"><img src="' + url + '/uploads/images/icons64/file-ico.png"><p class="img_show">' + filetype + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
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
     //Autograph upload
    function autoGraphUpload(input)
    {
		//console.log(input);
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
	//image upload
    function showimagepreview(input)
    {
        //console.log(input);
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
                    template += '<span class="preview" id="'+rand+'"><div class="image_ext"><img src="'+url+'/uploads/images/icons64/file-ico.png"><p class="img_show">' + arr + '</p></div><p class="img_name">'+img+'</p><span class="overlay"><span class="updone"></span></span>';
                }
                template += '<input type="hidden" name="file_data[]" value="'+b.name+'">';
                template += '</span>';
                $('#dragAndDropFiles').append(template);
            }
            filerdr.readAsDataURL(b);

//           console.log(b.name);
        });
        //console.log(input.files[0]['name']);
        var maximum = input.files[0].size/20480;
        //alert(maximum);
    }
    function delete_row(rand) {
        jQuery('#' + rand).remove();

    }
</script> 
