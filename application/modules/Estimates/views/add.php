<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord) ? 'updatedata' : 'insertdata';
$path = $ctr_view . '/' . $formAction;
?> 
<!-- Example row of columns -->
<div class="row">
  <div class="col-md-6 col-md-6 col-lg-6">
    <ul class="breadcrumb nobreadcrumb-bg">
      <?php echo $this->breadcrumbs->show(); ?>
    </ul>
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
	<input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
	<?php $dftSymbole = getDefaultCurrencyInfo(); 
		//if(!empty($dftSymbole))
		//{
			$currecyId = $dftSymbole['country_id'];
		/*} else {
			$currecyId = '227';
		}*/
	?>
    <input type="hidden" name="country_id_symbol_edit" id="country_id_symbol_edit" value="<?php echo $currecyId;?>">
	<div class="whitebox">
	<?php /*RJ Change col-xs-12 col-md-6 col-lg-6 To col-xs-12 col-md-6 col-lg-8 */?>	
	  <div class="col-xs-12 col-md-12 col-lg-12"><br/>
        <div class="form-group">
          <?php /* ?><input type="submit" name="sbt_estimate" value="Save Estimate" class="btn btn-blue"><?php */ ?>
          <a href="javascript:;" class="btn btn-blue" onclick="SaveEstForm('save');"><?php echo lang('EST_LBL_SAVE_EST');?></a>&nbsp; <a href="javascript:;" class="btn btn-blue" onclick="emailTemplatePopup();"><?php echo lang('EST_LBL_SEND_EST');?></a>&nbsp; <a href="javascript:;" class="btn btn-blue"><?php echo lang('EST_LBL_VISIBLE_FOR_CLIENT');?></a>&nbsp; <a href="javascript:;" class="btn btn-blue" onclick="SaveEstForm('pdf');"><?php echo lang('EST_LBL_PDF');?></a>&nbsp; <a href="javascript:;" class="btn btn-blue" onclick="SaveEstForm('print');"><?php echo lang('EST_LBL_PRINT');?></a>&nbsp; <a href="javascript:;" class="btn btn-blue" onclick="SaveEstForm('draft');"><?php echo lang('EST_LBL_SAVE_DRAFT');?></a>&nbsp; <a href="javascript:;" class="btn btn-blue" onclick="SaveEstForm('preview');"><?php echo lang('EST_LBL_VIEW_PREVIEW');?></a> </div>
        <div class="clr"></div>
		<h3><b><?php echo lang('EST_LABEL_ESTIMATE');?></b><span class="pull-right"><span class="form-group">
          <input type="text" class="form-control" name="estimate_auto_id" readonly placeholder="EST XXXXX" value="EST-<?php echo random_string('nozero', 12); ?>" required>
          <input type="hidden" name="client_name" id="client_name" >
          </span></span></h3>
        <div class="clr"></div>
        <br/>
        <div class="form-group">
          <select name="prospect_id" id="prospect_id" class="form-control chosen-select" onChange="ShowClientRelatedToCompany(this.value);" data-parsley-trigger="change" data-placeholder="<?php echo lang('EST_LABEL_SELECT_CLIENT');?> *" required>
            <option value=""></option>
            <optgroup label="<?php echo lang('company'); ?>">
            <?php foreach ($company_info as $company) { ?>
            <option value="company_<?php echo $company['company_id']; ?>"><?php echo $company['company_name']; ?></option>
            <?php } ?>
            </optgroup>
            <optgroup label="<?php echo lang('client'); ?>">
            <?php foreach ($client_info as $client) { ?>
            <option value="client_<?php echo $client['prospect_id']; ?>"><?php echo $client['prospect_name']; ?></option>
            <?php } ?>
            </optgroup>
            <optgroup label="<?php echo lang('COMM_CONTACT'); ?>">
            <?php foreach ($contact_info as $contact) { ?>
            <option value="contact_<?php echo $contact['contact_id']; ?>"><?php echo $contact['contact_name']; ?></option>
            <?php } ?>
            </optgroup>
          </select>
        </div>
        <div class="form-group" id="ShowRecipientAsPerComapny">
          <select name="recipient_id[]" id="recipient_id" multiple="" class="form-control chosen-select" data-placeholder="<?php echo lang('EST_LABEL_SELECT_RECIPIENTS');?> *" required>
            <option value=""></option>
            <optgroup label="<?php echo lang('client'); ?>">
            <?php foreach ($client_info as $client) { ?>
            <option value="client_<?php echo $client['prospect_id']; ?>"><?php echo $client['prospect_name']; ?></option>
            <?php } ?>
            </optgroup>
            <optgroup label="<?php echo lang('COMM_CONTACT'); ?>">
            <?php foreach ($contact_info as $contact) { ?>
            <option value="contact_<?php echo $contact['contact_id']; ?>"><?php echo $contact['contact_name']; ?></option>
            <?php } ?>
            </optgroup>
          </select>
        </div>
        <div class="clr"></div>
        <div class="row ">
          <div class="col-xs-12 col-md-3 col-lg-3 no-left-pad">
            <div class="form-group">
                <input type="text" name="subject" class="form-control" id="subject" required placeholder="<?php echo lang('COMM_SUBJECT'); ?> *" value="<?= !empty($editRecord[0]['subject']) ? $editRecord[0]['subject'] : '' ?>" />
            </div>
          </div>
          <div class="col-xs-6 col-md-3 col-lg-3 no-right-pad">
            <div class="input-group date" id="creation_date">
                <input type="text" class="form-control" placeholder="<?php echo lang('due_date'); ?> *" required id="creation_date" name="creation_date" onkeydown="return false" data-parsley-errors-container="#dueDateErrors" />
              <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
            <span id="dueDateErrors"></span> </div>
          <div class="col-xs-6 col-md-6 col-lg-6 no-left-pad">
            <div class="form-group" id="showPrdTempSelectBoxDiv">
              <select name="template_box" id="template_box" class="form-control chosen-select" data-placeholder="Use Estimation Template" onChange="return templateFormOpen(this.value);">
                <option value=""><?php echo lang('EST_ADD_LABEL_USE_PRODUCT_TEMPLATE');?></option>
                <optgroup label="<?php echo lang('EST_ADD_LABEL_SAVE_PRODUCT_TEMPLATE');?>">
                <option value="add"><?php echo lang('EST_ADD_LABEL_SAVE_PRODUCT_TEMPLATE');?></option>
                </optgroup>
                <optgroup label="<?php echo lang('EXIST_TEMPLATE');?>">
                <?php if (count($estimate_temp_info) > 0) { ?>
                <?php foreach ($estimate_temp_info as $estimate) { ?>
                <option value="<?php echo $estimate['est_temp_id']; ?>"><?php echo $estimate['est_temp_name']; ?></option>
                <?php } //Close Estimate Template fore loop?>
                <?php } //Close Estimate If Condition ?>
                </optgroup>
              </select>
            </div>
          </div>
          <div class="clr"></div>
        </div>
        <div class="row ">
          <div class="col-xs-6 col-md-6 col-lg-6 form-group">
            <select name="estStatus" class="form-control chosen-select" id="estStatus" tabindex="9">
              <option value="1" <?php if (isset($editRecord[0]['status']) && $editRecord[0]['status']==1 ) { echo 'selected=selected'; } ?>>
              <?=  lang('active'); ?>
              </option>
              <option value="0" <?php if (isset($editRecord[0]['status']) && ($editRecord[0]['status'] == 0 || $editRecord[0]['status'] == 3)) { echo 'selected=selected'; } ?>>
              <?=  lang('inactive'); ?>
              </option>
            </select>
          </div>
          <div class="col-xs-6 col-md-6 col-lg-6 no-left-pad form-group"> 
			<select name="prospect_owner_id" class="form-control chosen-select" id="prospect_owner_id">
				<option value="">
					<?php echo $this->lang->line('select_prospect_owner'); ?>
				</option>
				<?php if (isset($prospect_owner) && count($prospect_owner) > 0) { ?>
					<?php foreach ($prospect_owner as $prospect) { ?>
						<option value="<?php echo $prospect['login_id']; ?>" <?php if($login_id == $prospect['login_id']){ echo "selected"; }?>><?php echo ucfirst($prospect['firstname']) . " " . ucfirst($prospect['lastname']); ?></option>
							<?php } ?>
						<?php } ?>
			</select>
		  </div>
        </div>
        <div class="form-group txtBoxValidation">
          <label><?PHP ECHO lang('EST_LABEL_EST_CONTENT');?> *</label>
          <ul class="parsley-errors-list filled hidden" id="est_content_Error" >
            <li class="parsley-required"><?php echo lang('EST_ADD_LABEL_REQUIRED_FIELD');?></li>
          </ul>
          <textarea class="form-control" name="est_content" id="est_content" required></textarea>
        </div>
        <div class="bg-gray mb15">
          <div class="row">
			<div class="col-md-11 col-xs-12  col-lg-11 hidden-xs hidden" id="estPrdLabelDiv">
				<div class="row">
					<div class="col-xs-12 col-md-2 col-lg-3 col-sm-2 prod-1">
						<div class="form-group">
							<select name="estTaxOptForAll" id="estTaxOptForAll" class="form-control chosen-select" onChange="return estTaxOptChngPrice(this.value);">
								<option value="excTax"><?php echo lang('EST_TITLE_EXCLUD_TAX');?></option>
								<option value="incTax"><?php echo lang('EST_TITLE_INCLUD_TAX');?></option>
							</select>
						</div>
					</div>
				</div>
              <div class="clr"></div>
            </div>
            <div class="clr"></div>
          </div>
          <div id="tempProductBox"> </div>
          <div id="productBox">
            <?php //echo $this->load->view('estimateProductSection'); ?>
          </div>
          <div id="newProductBox"> </div>
          <div id="newProductGroupBox"> </div>
          <div class="text-center" id="prdErrorMsg"></div>
          <div class="col-xs-12 col-md-12  col-lg-12 text-center"> <a href="javascript:;" onclick="addProductBox();" class="btn btn-primary "><?php echo lang('EST_TITLE_ADD_EXISTING_PRD');?></a> <a href="javascript:;" onclick="addNewProductBox();" class="btn btn-primary"><?php echo lang('EST_TITLE_ADD_NEW_PRD');?></a> <a href="javascript:;" onclick="addProductGroupBox();" class="btn btn-primary"><?php echo lang('EST_TITLE_ADD_GROUP_PRD');?></a>
            <div class="clr"> </div>
          </div>
          <div class="clr"></div>
          <div id="subtotalSection" >
            <?php // echo $this->load->view('files/estimateSubtotalSection'); ?>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-sm-6 col-xs-12">
            <div class="row"><div class="bd-form-group col-xs-6 col-md-6 col-sm-6 col-lg-3">
              <label><?php echo lang('EST_TITLE_USER_DESCRIPTION');?></label>
            </div>
            <div class="btn-group btn-toggle col-xs-6 col-sm-6 col-lg-2">
              <div class="pull-right">
                <input data-toggle="toggle" data-onstyle="success" type="checkbox"  id="est_userdescription_status" name="est_userdescription_status" onChange="toggle_show('#first_time_hide', this)" value="1" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"/>
              </div>
            </div>
            <div class="clr"></div></div>
          </div>
          <div class="clr"></div>
          <div id="first_time_hide" class="col-xs-12 col-md-12 col-lg-12 mt15 txtBoxValidation">
            <ul class="parsley-errors-list filled hidden" id="est_userdescription_Error" >
              <li class="parsley-required"><?php echo lang('EST_ADD_LABEL_REQUIRED_FIELD');?></li>
            </ul>
            <textarea class="form-control" name="est_userdescription" id="est_userdescription" placeholder="<?php echo lang('EST_TITLE_USER_DESCRIPTION');?>"></textarea>
          </div>
        </div>
        <div class="col-xs-12 col-md-12 col-lg-12">
          <div id="textParagraphSection" class="row "> </div>
          <div class="row">
            <div class="form-group  text-center"> <a href="javascript:;" onclick="appendTextParagraph();" class="btn btn-primary"><?php echo lang('EST_TITLE_ADD_TEXT_PARAGRAPH');?></a> </div>
            <div class="clr"> </div>
          </div>
        </div>
        <div class="clr"> </div>
        <div class="form-group">
          <label><?php echo lang('EST_TITLE_TERMS_AND_CONDITIONS');?></label>
          <select id="est_termcondition" name="est_termcondition" class="form-control chosen-select" >
            <?php foreach($TermsConditionDataArray as $TermsConditionData){ ?>
            <option <?php if($TermsConditionData['estimate_settings_id'] == 1){ echo "selected"; }?> value="<?php echo $TermsConditionData['estimate_settings_id'];?>"><?php echo $TermsConditionData['name'];?></option>
            <?php }?>
          </select>
          <!--<ul class="parsley-errors-list filled hidden" id="estTerms_Error" ><li class="parsley-required">This value is required.</li></ul>
                    <textarea class="form-control" name="est_termcondition" id="est_termcondition" placeholder="Terms &amp; Conditions"></textarea>--> 
        </div>
        <div class="clr"> </div>
        <div class="row">
          <?php if(checkPermission('Signature','view')){ ?>
          <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
            <div class="form-group row">
              <div class="bd-form-group col-xs-6 col-md-6 col-lg-3 col-sm-6">
                <label><?php echo lang('EST_LBL_ADD_AUTOGRAPH');?></label>
              </div>
              <div class="  text-right col-xs-6 col-md-6 col-sm-6 col-lg-2">
                <div class="btn-group btn-toggle">
                  <input data-toggle="toggle" data-onstyle="success" type="checkbox"  id="SignatureTypeOn" name="SignatureTypeOn" onChange="toggle_show('#signatureTypeDiv', this)" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"/>
                  <!--<a href="javascript:;" onclick="addNewAutograph();">Plus</a>--> 
                </div>
              </div>
              <div class="clr"> </div>
            </div>
            <div class="clr"> </div>
          </div>
          <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
            <div class="form-group row">
              <div class="bd-form-group col-xs-6 col-md-6 col-lg-3 col-sm-6">
                <label><?php echo lang('EST_TITLE_CLIENT_CAN_ACCEPT_ONLINE');?></label>
              </div>
              <div class="col-xs-6 col-md-6 col-lg-2 text-right col-sm-6">
                <div class="btn-group btn-toggle">
                  <input data-toggle="toggle" data-onstyle="success" type="checkbox" value="1" id="client_can_accept_online" name="client_can_accept_online" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"/>
                </div>
              </div>
              <div class="clr"> </div>
            </div>
            <div class="clr"> </div>
          </div>
          <div class="clr"> </div>
        </div>
        <div id="autoGraphDiv">
          <div class="col-xs-12 col-md-12 no-left-pad">
            <div class=" row" id="signatureTypeDiv" style="display:none">
              <div class="input-group date col-sm-3 pull-left form-group" id="">
                <input type="text" class="form-control" placeholder="<?php echo lang('EST_TITLE_AUTOGRAPH_DATE');?>" id="signature_date" name="signature_date" onkeydown="return false" data-parsley-errors-container="#signatureDateErrors" />
                <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
              <div id="signatureDateErrors" ></div>
              <div class="col-sm-2 form-group">
                <input type="text" value="" placeholder="<?php echo lang('EST_TITLE_AUTOGRAPH_PLACE');?>" id="signature_place" class="form-control" name="signature_place" />
              </div>
              <div class="col-sm-2 form-group">
                <input type="text" value="" placeholder="<?php echo lang('EST_TITLE_AUTOGRAPH_NAME');?>" id="signature_name" class="form-control" name="signature_name" />
              </div>
              <div class="col-sm-2 form-group">
                <input type="text" value="" placeholder="<?php echo lang('EST_TITLE_AUTOGRAPH_JOBROLE');?>" id="signature_jobrole" class="form-control" name="signature_jobrole" />
              </div>
              <input type="hidden" name="signature-digital" id="signature-digital" class="input-group autographclass">
              <div class="col-sm-3 form-group">
                <select id="signature_type" name="signature_type" class="form-control chosen-select signatureTypeChosen" onchange="displaySignature(this.value);" >
                  <option value=""><?php echo lang('EST_TITLE_AUTOGRAPH_SELECT');?></option>
                  <option value="0"><?php echo lang('EST_TITLE_AUTOGRAPH_DIGITAL_SIGNATURE');?></option>
                  <option value="1"><?php echo lang('EST_TITLE_AUTOGRAPH_CANVAS');?></option>
                </select>
              </div>
            </div>
            <div class="form-group row" id="signature-upload" style="display:none">
              <label class="custom-upload btn btn-blue"><?php echo lang('EST_TITLE_AUTOGRAPH_UPLOAD'); ?>
                <input type="file" onchange="autoGraphUpload(this)" name="signature-file" id="singnature-file" class="input-group">
              </label>
              <div> <img id="autographimg" class="noimage" src="<?php echo base_url('uploads/contact').'/noimage.jpg'?>"  width="100" /> </div>
              <div class="clr"> </div>
            </div>
            <div class="form-group form-control row heightauto" id="signature" style="display:none">
              <div class='js-signature' style="width:100px;height:100px"></div>
              <div class="clr"> </div>
            </div>
          </div>
        </div>
        <?php }?>
        <div class ="form-group row">
          <div class = "col-sm-6 col-xs-12 col-md-6 col-lg-6 form-group">
            <div class="mediaGalleryDiv">
              <button type="button" name="gallery" id="gallery-btn" data-href="<?php echo $url; ?>"  class="btn btn-primary"><?php echo lang('cost_placeholder_uploadlib') ?></button>
              <div class="mediaGalleryImg"> </div>
            </div>
          </div>
          <div class = "col-sm-6 col-xs-12 col-md-6 col-lg-6"> 
            <!-- new code-->
            <div class="col-xs-12 col-md-12 no-right-pad col-lg-12">
              <div id="dragAndDropFiles" class="uploadArea uploadarea-sm bd-dragimage">
                <div class="image_part" style="height: 100px;">
                  <label name="estimate_files[]">
                  <h1 style="top: -162px;"> <i class="fa fa-cloud-upload"></i>
                    <?= lang('DROP_IMAGES_HERE') ?>
                  </h1>
                  <input type="file" onchange="showimagepreview(this)" name="estimate_files[]" style="display: none" id="upl" multiple />
                  </label>
                </div>
              </div>
              <div class="clr"> </div>
            </div>
            <!-- end new code --> 
          </div>
        </div>
      </div>
      <div class="clr"></div>
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
        <h4 class="modal-title"><?php echo lang('EST_TITLE_AUTOGRAPH_UPLOADS'); ?></h4>
      </div>
      <div class="modal-body" id="modbdy"> </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onClick="$('#modalGallery').modal('hide');"><?php echo lang('CLOSE'); ?></button>
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
      <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h4 class="modal-title">
          <div class="modelTaskTitle"> <?php echo lang('EST_TITLE_PRD_TEMPLATE'); ?> </div>
        </h4>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <div class="col-xs-12 col-sm-12 col-lg-12">
            <label> <?php echo lang('EST_TITLE_PRD_TEMPLATE_NAME'); ?> *</label>
            <input type="text" required="" value="" placeholder="<?php echo lang('EST_TITLE_PRD_TEMPLATE_NAME'); ?>" name="est_temp_name" id="est_temp_name" class="form-control">
          </div>
        </div>
        
        <!--<a onchange="return addNewTemplate(this.value);" >Save</a>--> 
      </div>
      <div class="modal-footer">
        <center>
          <a onclick="addNewTemplate();" href="javascript:;">
          <input type="button" value="<?php echo lang('EST_EDIT_SAVE');?>" name="remove" class="btn btn-info">
          </a>
        </center>
      </div>
      <?php echo form_close(); ?> </div>
  </div>
</div>
<script>
    var inc = 1;
    var newProductboxIncrement = 1;
    var newProductGroupboxIncrement = 1;
    var textParagraphIncrement = 1;
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
					//$('.prdGrpTaxAmtInclud').removeClass('hidden');
					//$('.prdGrpFnlAmtInclud').removeClass('hidden');
					$('.commonIncludTimeDiv').removeClass('hidden');
				} 
				if(option == 'excTax'){
					var snglPrdTaxAttrPrice = $(this).attr("data-taxexclude-amount");
					//$('.prdGrpTaxAmtInclud').addClass('hidden');
					//$('.prdGrpFnlAmtInclud').addClass('hidden');
					$('.commonIncludTimeDiv').addClass('hidden');
				}
					$(this).val(snglPrdTaxAttrPrice);
			}
		});
	}
	
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
	//Set Editor For Estimate Content
		$('#est_content,#est_userdescription,#text_paragraph').summernote({
			disableDragAndDrop : true,
			height: 150,   //set editable area's height
			  codemirror: { // codemirror options
				theme: 'monokai'
			}	   
		});
        //Main form 
        $('#frmsubmit').parsley();
        //
        $('#frm_prd_template').parsley();
		$('#creation_date').datepicker({autoclose: true,startDate: '-0m'});
		$('#signature_date').datepicker({autoclose: true,startDate: '-0m'});
        
        $('.chosen-select').chosen({placeholder_text_single: "Product Group", no_results_text: "Oops, nothing found!"});
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });
	/**
     * Following function Show Product Label Box
	 */
	function showPrdLabelDiv()
	{
		$('#estPrdLabelDiv').removeClass('hidden');
	}
    /**
     * this function is used for adding dynamic existing product box to the div
     */
    function addProductBox()
    {
		//alert(inc);
		//alert($('.product_id').length);
		showPrdLabelDiv();	//Show Product Label 
		$.ajax({
            url: "<?php echo base_url('Estimates/getProductBox'); ?>/" + inc,
            type: "GET",
			async : false,
            success: function (data)
            {
                //$('#productBox').append(data);	//Comment For Product Box Delete this line after make testing
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
		showPrdLabelDiv();	//Show Product Label
        $.ajax({
            url: "<?php echo base_url('Estimates/getProductBox'); ?>/" + newProductboxIncrement,
            type: "GET",
            async : false,
		    data: {type: "new"},
            success: function (data)
            {
                //$('#newProductBox').append(data);	//Comment New product box div append here RJ
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
		showPrdLabelDiv();	//Show Product Label 
        $.ajax({
            url: "<?php echo base_url('Estimates/getProductBox'); ?>/" + newProductGroupboxIncrement,
            type: "GET",
            async : false,
			data: {type: "group"},
            success: function (data)
            {

                $('#newProductGroupBox').append(data);
                /*
                 * Reinileze of chosen select 
                 */
                $('.chosen-select').chosen({placeholder_text_single: "Product Group", no_results_text: "Oops, nothing found!"});
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
		var curSymbolGet = $("#country_id_symbol_edit").val();
        $.ajax({
            url: "<?php echo base_url('Estimates/getProductById'); ?>/" + currId,
            type: "GET",
            dataType: "json",
			data: {curSymbol: curSymbolGet},
            success: function (data)
            {
				//var taxWithfinalAmt 	= data.data.sales_price_unit;
				//var singleSalesPriceUnit= data.data.sales_price_unit;
				var taxWithfinalAmt 	= data.calculatedPrice;	//Converted Price amount
				var singleSalesPriceUnit= data.calculatedPrice; //Converted Price amount
				var taxValueForInfo 	= "";
				var taxID 				= "";
			//If Condition for not show Deleted Product 
				if(data.data.is_delete == 1){  } else {
					if(data.data.tax_percentage)
					{
						console.log(data.data);
						taxID = data.data.product_tax_id;
						taxValueForInfo = data.data.tax_percentage;
					//Remove after use
					//Calculate Tax as per Price and tax
						var taxAmtCal = (singleSalesPriceUnit * data.data.tax_percentage) / 100 ;
					//Place Calculated Tax in hidden field
						$('#product_tax_calculated_' + incId).val(taxAmtCal);
					//Calculate Final Value
						taxWithfinalAmt = (parseFloat(taxAmtCal) + parseFloat(singleSalesPriceUnit)).toFixed(2);
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
				$('#product_amount_sales_' + incId).val(singleSalesPriceUnit);
			//Product Price in Attribute with Tax
				$('#product_amount_sales_' + incId).attr("data-taxincluded-amount", taxWithfinalAmt);
			//Product Price in Attribute Without Tax
				$('#product_amount_sales_' + incId).attr("data-taxexclude-amount", singleSalesPriceUnit);
			//Set Discount 0.00
				
				$("#product_discount_"+incId).val("0.00");
                doSubtotal();
            }
        });
    }
	/**
	  * Add Single Product Price and Tax in attribute 
	  */
	function addAmtInTaxIncludedAttr(amount, incId, type)
	{
		//var tax = $('#product' + type + '_tax_calculated_' + incId).val();
		var taxId = $('#product' + type + '_tax_' + incId).val();
		
		//Start Ajax Call For Excluding and Including amount change functionality
            $.ajax({
                url: "<?php echo base_url('Estimates/prdAmntIncExcCalculation'); ?>",
                type: "POST",
                data: {taxId: taxId, amount: amount},
                dataType: "JSON",
                success: function (data)
                {
					$('#product' + type + '_amount_sales_' + incId).attr('data-taxincluded-amount', data.taxIncludedAmt);
					$('#product' + type + '_amount_sales_' + incId).attr('data-taxexclude-amount', data.taxExcludedAmt);
                    //$('#product' + type + '_amount_' + incId).val(data.total.toFixed(2));
                    //$('#product' + type + '_tax_calculated_' + incId).val(data.tax);
				}
            });
		
		
	//Place Tax Amount in data-taxincluded-amount attribute
		/*var fnlAmtWithTax = parseFloat(amount) + parseFloat(tax);
		//alert(fnlAmtWithTax.toFixed(2));
		$('#product' + type + '_amount_sales_' + incId).attr('data-taxincluded-amount', fnlAmtWithTax);
		$('#product' + type + '_amount_sales_' + incId).attr('data-taxexclude-amount', amount);*/
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
        var amount = '';
        var qty = currVal;
        var tax = $('#product' + type + '_tax_' + incId).val();
        amount = $('#product' + type + '_amount_sales_' + incId).val();
		var discount 	= $('#product' + type + '_discount_' + incId).val();
		var discountOption = $('#product' + type + '_disOption_' + incId).val();
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
		//addAmtInTaxIncludedAttr function call for set updated Attribute in Price / This function set updated Tax in Product Price
			addAmtInTaxIncludedAttr(amount, incId, type);
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
		var qty 			= '';
        var amount 			= '';
		var tax 			= '';
		var discountOption 	= currVal;
        var discount 		= $('#product' + type + '_discount_' + incId).val();
        qty 				= $('#product' + type + '_qty_' + incId).val();
		tax 				= $('#product' + type + '_tax_' + incId).val();
        amount 				= $('#product' + type + '_amount_sales_' + incId).val();
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
		addAmtInTaxIncludedAttr(amount, incId, type);
        manageProductAmount(qty, tax, amount, incId, type, discount, discountOption);
    }
	/* 
	 *checkValidTotalAmtOrNot function for check Final Total amount valid or not 
	 */
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
			if($(this).val() != "")
			{
				taxPercentageValue.push($(this).val());
				taxPercentageValue.push($(this).val());
			} else {
				taxPercentageValue.push('null');
			}
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
	function showPrdErrorMessage(action)
	{
		if(action == 'show')
		{
			$('#prdErrorMsg').html('<ul id="" class="parsley-errors-list filled"><li class="parsley-min"><?php echo lang('select_product_properly'); ?></li></ul>');
		} else {
			$('#prdErrorMsg').html('');
		}
	}
	function addPrdOrder()
	{
		var flgOrd = 0;
		$(".estCustomPrdOrder").each(function () {
			flgOrd++;
			$(this).val(flgOrd);
		});
	}
    /**
     * this function is used for calculation of subtotal
     */
    function doSubtotal()
    {
	//Change Currency as per in Select Box
		chngCurrencySym();
	//Add Product Order in Hidden Fields
		addPrdOrder();
	//Amount array for execute Subtotal DIV if Products are available
		if(checkValidTotalAmtOrNot() != 0) {
			//Hide "Select Product Properly." message when Total amount is proper
				showPrdErrorMessage('');
			var amount 			= [];
			var singleAmount 	= [];
			var singleQuantity 	= [];
			var taxid 			= [];
			var taxes 			= [];
			var cntSymbol 		= '';
		/*Product Wise Discount and Discount Option*/
			var prdSngDiscount 	= [];
			var prdsngDiscountOpt = [];
		/*Remove Following code at the end*/
			var discount 		= '';
			var discountOption 	= '';
			
		//Get Discount option as per selected 
			/*
			Remove Following code at the end
			if($("#discount_Opt").val())	Remove this line at the end
			{
				discountOption = $("#discount_Opt").val();
			}*/
		//discount = $('#discount').val();	//Remove this line at the end
		//Get Country Symbol ID option as per selected
			if($("#country_id_symbol").val())
			{
				cntSymbol = $("#country_id_symbol").val();
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
					$('#subtotalSection').html(data);
					$('.chosen-select').chosen();
					//Change Currency as per in Select Box
						chngCurrencySym();
				}
			});
		} else {
			$('#subtotalSection').empty();
			showPrdErrorMessage('show');
		}
    }
    /**
     * 
     * this function is used to display discount box
     */
    function showDiscount(data)
    {
        if ($.isNumeric(data) && data > 0) {
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
            $("#signature").empty();
            $("#signature").jSignature({'UndoButton': true});
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
     * 
     * this function is used to append text paragraph
     */
    function appendTextParagraph()
    {
        $.ajax({
            url: "<?php echo base_url('Estimates/appendTextParagraph'); ?>/" + textParagraphIncrement,
            type: "GET",
			async : false,
            success: function (data)
            {
                $('#textParagraphSection').append(data);
                $('#text_paragraph_'+textParagraphIncrement).summernote({
						disableDragAndDrop : true,
						height: 150,   //set editable area's height
						codemirror: { // codemirror options
							theme: 'monokai'
						}	   
					});
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
        var delete_meg ="<?php echo lang('delete_item');?>";
        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('EST_LABEL_ESTIMATE');?>',
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
                        doSubtotal();
                        dialog.close();
                    }
                }]
            });

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
            var delete_meg ="<?php echo lang('apply_template');?>";
            BootstrapDialog.show(
                {
                    title: '<?php echo lang('EST_LABEL_ESTIMATE');?>',
                    message: delete_meg,
                    buttons: [{
                        label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                        action: function(dialog) {
                            dialog.close();
                        }
                    }, {
                        label: '<?php echo $this->lang->line('ok');?>',
                        action: function(dialog) {
                            var curSym = $("#country_id_symbol option:selected").text();
                            if(curSym)
                            {
                                var selecteSym = curSym;
                            }
                            else {
                                var	selecteSym = "";
                            }
                            $.ajax({
                                url: "<?php echo base_url('Estimates/showTemplateProduct'); ?>",
                                type: "POST",
                                data: {est_temp_id: val, estSymbolSelected: selecteSym},
                                success: function (data)
                                {
                                    $('#tempProductBox').append(data);
                                    $('.chosen-select').chosen();
                                    showPrdLabelDiv();
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
            BootstrapDialog.show(
                {
                    title: '<?php echo lang('EST_LABEL_ESTIMATE');?>',
                    message: 'Are you sure want to save "' + est_temp_name + '" as a Estimate Template?',
                    buttons: [{
                        label: '<?php echo lang('COMMON_LABEL_CANCEL');?>',
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
                            var product_discount = [];		//Array For Product Discount
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
    function SaveEstForm(val,emailTmpStatus)
    {
		//Place Country id in text box
			var selectedCountryIdSymbol = $("#country_id_symbol_edit").val();
			$("#inserted_country_id_symbol").val(selectedCountryIdSymbol);
		var changeEmailTmp = '';
		if(emailTmpStatus)
		{
			changeEmailTmp = emailTmpStatus;
		}
		var ShowMsg = '';					//Set Msg for Bootstrap 
		var hdn_submit_status = "";       	//Set Value for hidden Status
		var action = '';					//Set value for its related with Bootstrap or Just save the information
		if (val == 'draft')
        {
			ShowMsg = '<?php echo lang('estimate_save_draft'); ?>';
			hdn_submit_status = "2";       //2 Value for Draft
			action = '1';
		} else if (val == 'preview')
		{
			ShowMsg = '<?php echo lang('bfr_preview_save_estimate'); ?>';
			hdn_submit_status = "1";       //2 Value for Store as a Draft
			action = '1';
		} else if (val == 'pdf')
		{
			ShowMsg = '<?php echo lang('bfr_pdf_save_estimate'); ?>';
			hdn_submit_status = "1";       //2 Value for Store as a Draft
			action = '1';
		} else if (val == 'print')
		{
			ShowMsg = '<?php echo lang('bfr_print_save_estimate'); ?>';
			hdn_submit_status = "1";       //2 Value for Store as a Draft
			action = '1';
		} else if (val == 'currencyChange')
		{
			//Place Country id in text box
				var selectedCountryIdSymbol = $("#country_id_symbol").val();
			$("#inserted_country_id_symbol").val(selectedCountryIdSymbol);
			//Chosen Reinitialize
				symbolReinitialize();
			ShowMsg = '<?php echo lang('estimate_change_currency'); ?>';
			hdn_submit_status 	= "1";       
			action 				= '1';
		} else if (val == 'sendEstimate')
		{
			if(changeEmailTmp){
				ShowMsg = '<?php echo lang('change_template_save_estimate'); ?>';
			} else {
				ShowMsg = '<?php echo lang('save_current_estimate');?> <?php echo lang('sure_want_to_continue'); ?>';
			}
			hdn_submit_status = "1";       //2 Value for Store as a Draft
			action = '1';
		}else {
			action = '0';
		}
		if(action == 1)
		{
			/*BootstrapDialog.confirm(
			{
				title: '<?php echo lang('EST_LABEL_ESTIMATE') ?>',
				message: ShowMsg,
				callback: function (result) {
					if (result) {
						//RJ Set here Active and Inactive select box  
						$("#hdn_submit_status").val(hdn_submit_status);       
						$("#HdnSubmitBtnVlaue").val(val);  
						if(changeEmailTmp){
						//Show Change Email Template Popup After Save
							$("#HdnChangeEmailTmp").val(changeEmailTmp);   //Show popup for Change Template
						}
						formSbmitValidation();
					}
				}
			});*/
			BootstrapDialog.show(
			{
				title: '<?php echo lang('estimate_module'); ?>',
				message: ShowMsg,
				buttons: [{
                label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                action: function(dialog) {
					if (val == 'currencyChange'){
						//Chosen Reinitialize
						var selectedSymbolVal = $("#country_id_symbol_edit").val();
						$("#country_id_symbol").val(selectedSymbolVal).trigger("chosen:updated");
					}
					dialog.close();
                    }
				}, {
					label: '<?php echo $this->lang->line('ok');?>',
					action: function(dialog) {
						//dialog.setTitle('Title 2');
						$("#hdn_submit_status").val(hdn_submit_status);       
						$("#HdnSubmitBtnVlaue").val(val);   
						if(changeEmailTmp){
						//Show Change Email Template Popup After Save
						$("#HdnChangeEmailTmp").val(changeEmailTmp);   //Show popup for Change Template
						}
						dialog.close();
						formSbmitValidation();
					}
				}]
			});
		} else {
			//$("#hdn_submit_status").val("1");                   
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
			title: '<?php echo lang('EST_LABEL_ESTIMATE'); ?>',
			message: '<?php echo lang('cnfrm_change_email_template'); ?>',
			buttons: [{
                label: '<?php echo lang('EST_LBL_CHANGE_EMAIL_TEMPLATE');?>',
                action: function(dialog) {
					SaveEstForm('sendEstimate', 'yes');
                }
            }, {
                label: '<?php echo lang('EST_LBL_SEND_EST'); ?>',
                action: function(dialog) {
					SaveEstForm('sendEstimate');
                }
            }]
			
		});
	}
	function symbolReinitialize()
	{
		var selectedSymbolVal = $("#country_id_symbol_edit").val();
		$("#country_id_symbol").val(selectedSymbolVal).trigger("chosen:updated");
	}
	function formSbmitValidation()
	{
		var prdTotal = "";
	//Validation for Content section
		var estContent = $('#est_content').code();
		if(estContent !== '' && estContent !== '<p><br></p>'){ 
			$("#est_content_Error").addClass( "hidden");
		}else{ $("#est_content_Error").removeClass( "hidden"); return false; }
	//Validation for Total Calculation
		if($('.prdTotal').val()){	prdTotal = $('.prdTotal').val();	}
		if(prdTotal == "undefined" || prdTotal == 0 || prdTotal == "" || prdTotal < 0 )
		{
			showPrdErrorMessage('show');
			//$('#prdErrorMsg').html('<ul id="" class="parsley-errors-list filled"><li class="parsley-min">Select Product Properly.</li></ul>');
		} else {
			$("#frmsubmit").submit();
		}
	}	
</script> 
<script>
    /**
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
     * this function is used to show popup of the choose gallery box
     */
    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('costModel').modal('hide');
        $('#modalGallery').modal('show');
    });
	/*Check New Product Name is Unique */
	function checkPrdNameUnique(prdName, incId, type)
	{
		$.ajax({
            url: "<?php echo base_url('Estimates/checkProductNameUnique'); ?>/",
            type: "POST",
            data: {prdName: prdName},
			dataType: "json",
			success: function (data)
            {
				if(data.data == 'notavailable')
				{
					var delete_meg = '<?php echo lang('enter_another_prod'); ?>"'+ data.prdName +'" <?php echo lang('duplicate_msg'); ?>';
                    BootstrapDialog.show(
                        {
                            title: '<?php echo $this->lang->line('Information');?>',
                            message: delete_meg,
                            buttons: [{
                                label: '<?php echo $this->lang->line('ok');?>',
                                action: function(dialog) {
                                    $('#product'+type+'_name_'+incId).val('');
                                    $('#product'+type+'_name_'+incId).focus();
                                    dialog.close();
                                }
                            }]
                        });
				}
            }
        });
	}
	/* image upload */
    $('.delimg').on('click', function () {
        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');
        var delete_meg ="<?php echo lang('confirm_delete_item');?>";
        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: delete_meg,
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
                        $('#deletedImagesDiv').append("<input type='hidden' name='softDeletedImages[]' value='" + str1 + "'> <input type='hidden' name='softDeletedImagesUrls[]' value='" + dataPath + '/' + imgName + "'>");
                        $('#' + divId).remove();
                        $('#confirm-id').on('hidden.bs.modal', function () {
                            $('body').addClass('modal-open');
                        });
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
                        BootstrapDialog.alert("<?php echo lang("file"); ?> \"" + file.name + "\" is too big.Max allowed size is 20 MB.");
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
			//console.log(xhr[rand]);
            var filename = file.name;
            var fileext = filename.split('.').pop();
			//console.log(fileext);
            xhr[rand].open("post", "<?php echo base_url('/Estimates/upload_file') ?>/" + fileext, true);
            xhr[rand].upload.addEventListener("progress", function (event) {
                //console.log(event);
                if (event.lengthComputable) {
                    $(".progress[id='" + rand + "'] span").css("width", (event.loaded / event.total) * 100 + "%");
                    $(".preview[id='" + rand + "'] .updone").html(((event.loaded / event.total) * 100).toFixed(2) + "%");
                }
                else {
                    BootstrapDialog.alert("<?php echo lang('fail_file_upload'); ?>");
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
				var delete_meg ="<?php echo lang('MSG_UPLOAD_PROFILE_PIC'); ?>";
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
			var delete_meg ="<?php echo lang('max_upload') . ' 1 MB'; ?>";
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
			//console.log(b.name);
        });
        //console.log(input.files[0]['name']);
        var maximum = input.files[0].size/20480;
        //alert(maximum);
    }
    function delete_row(rand) {
        jQuery('#' + rand).remove();
    }
	function addNewAutograph()
	{
		var autogrphCnt = 0; 
		if($(".autographclass").length)
		{
			autogrphCnt = $(".autographclass").length + 1;
		}
		//alert(autogrphCnt);
		$.ajax({
            url: "<?php echo base_url('Estimates/addNewAutograph'); ?>/" + inc,
            type: "GET",
            success: function (data)
            {
				$('autoGraphDiv').append(data);
            }
        });
	}
</script>