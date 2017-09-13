<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'insertdata';
$formPath = $invoice_view . '/' . $formAction;
?>

<div class="modal-dialog modal-lg" >
    <div class="modal-content">
        <form id="from-model" method="post" action="<?php echo base_url($formPath); ?>" enctype="multipart/form-data"
              data-parsley-validate>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><b>
                        <?= !empty($modal_title) ? $modal_title : '' ?>
                    </b> </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-md-9 col-lg-9">
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="form-group">
                                    <label class="  control-label">
                                        <?= lang('description') ?>
                                    </label>
                                    <textarea name="description" placeholder="<?= lang('description') ?>" id="description"
                                              class="form-control"><?= !empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?>
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <!-- <label class="  control-label">
                            <?= lang('line_items') ?> 
                             </label>-->
                            <div class = "form-group row" id="add_items">
                                <div class="col-xs-12 col-md-12 visible-lg visible-md">
                                    <div class="col-xs-12 col-md-2">
                                        <label>
                                            <?= lang('line_items') ?> <span class="viewtimehide">*</span>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-md-1">
                                        <label>
                                            <?= lang('qty_hrs') ?> <span class="viewtimehide">*</span>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-md-2">
                                        <label>
                                            <?= lang('rate') ?> <span class="viewtimehide">*</span>
                                        </label>
                                    </div>
                                    <!-- <div class="col-xs-12 col-md-2">Type</div> -->
                                    <div class="col-xs-12 col-md-2">
                                        <label>
                                            <?= lang('discount') ?> 
                                            (%)</label> <span class="viewtimehide">*</span>
                                    </div>
                                    <div class="col-xs-12 col-md-2">
                                        <label>
                                            <?= lang('tax_rate') ?> 
                                            (%)</label> <span class="viewtimehide">*</span>
                                    </div>
                                    <div class="col-xs-12 col-md-2">
                                        <label>
                                            <?= lang('cost') ?>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-md-1">
                                        <label>
                                            <?= lang('actions') ?>
                                        </label>
                                    </div>
                                </div>
                                <?php if (!empty($item_details)) {
                                    foreach ($item_details as $row) {
                                        ?>
                                        <div class="col-xs-12 col-md-12 form-group newrow" id="item_edit_<?= $row['invoice_item_id'] ?>">
                                            <div class="col-xs-12 col-md-2">
                                                <input type="text" name="item_name_<?= $row['invoice_item_id'] ?>" maxlength="80" class="form-control" placeholder="" required value="<?= !empty($row['item_name']) ? $row['item_name'] : '' ?>">
                                            </div>
                                            <div class="col-xs-12 col-md-1">
                                                <input type="text" maxlength="5" name="qty_hours_<?= $row['invoice_item_id'] ?>" onkeypress="return numericDecimal(event)" required class="form-control item_cal qty_item" placeholder="" value="<?= !empty($row['qty_hours']) ? $row['qty_hours'] : '' ?>">
                                            </div>
                                            <div class="col-xs-12 col-md-2">
                                                <input type="text" maxlength="10" name="rate_<?= $row['invoice_item_id'] ?>" onkeypress="return numericDecimal(event)" required class="form-control item_cal rate_item" placeholder="" value="<?= !empty($row['rate']) ? $row['rate'] : '' ?>">
                                            </div>
                                            <div class="col-xs-12 col-md-2">
                                                <input type="text" maxlength="5"  data-parsley-gteqm="#discount_<?= $row['invoice_item_id'] ?>" id="discount_<?= $row['invoice_item_id'] ?>"  name="discount_<?= $row['invoice_item_id'] ?>" onkeypress="return numericDecimal(event)" required class="form-control item_cal discount_item" placeholder="" value="<?= !empty($row['discount']) ? $row['discount'] : '' ?>">
                                            </div>
                                            <div class="col-xs-12 col-md-2">
                                                <select class="form-control item_cal tax_item" name="tax_rate_<?= $row['invoice_item_id'] ?>" required data-parsley-trigger="change" >
                                                    <option value=""><?= lang('tax') ?></option>
                                                    <?php if (count($taxes) > 0) { ?>
                                                        <?php foreach ($taxes as $tax) { ?>
                                                            <option <?php if (!empty($row['tax_rate']) && $row['tax_rate'] == $tax["tax_id"]) {
                                                                echo 'selected="selected"';
                                                            } ?> value="<?= $tax["tax_id"] ?>" ><?php echo $tax["tax_percentage"]; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <!--  <div class="col-xs-12 col-md-2">
                                                              <input type="text" name="type_<?= $row['invoice_item_id'] ?>" class="form-control" placeholder="" value="">
                                                              </div> -->

                                            <div class="col-xs-12 col-md-2">
                                                <input type="text" name="cost_<?= $row['invoice_item_id'] ?>" onkeydown="return false" class="form-control total_cost" placeholder="" value="<?= !empty($row['cost']) ? $row['cost'] : '' ?>">
                                            </div>
                                            <div class="col-xs-12 col-md-1"> <a class="pull-right btn btn-default "> <span class="glyphicon glyphicon-trash" onclick="delete_item_row('item_edit_<?= $row['invoice_item_id'] ?>');"></span> </a> </div>
                                        </div>
    <?php }
} ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="  control-label">
<?= lang('total_amount') ?>
                                : <span id="total_item">
                                <?= !empty($edit_record[0]['amount']) ? $edit_record[0]['amount'] : '0.00' ?>
                                </span></label>
                            <input type="hidden" name="amount_total" id="amount_total" value="<?= !empty($edit_record[0]['amount']) ? $edit_record[0]['amount'] : '' ?>" />
                        </div>
                        <div class="form-group"> <a id="add_new_item" class="btn btn-default align-center"> <span class="glyphicon glyphicon-plus"></span>
<?= lang('add_more_item') ?>
                            </a> </div>

                        <!--<div class="form-group">
                                    <label class="  control-label">Files </label>
                                    <input type="file" name="addfile[]" id="upl" multiple/>
                                     <div class = "form-group row" id="add_items">
                                            <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-md-4">File Name</div>
                                                    <div class="col-xs-12 col-md-3">Date Created</div>
                                                    <div class="col-xs-12 col-md-3">Size</div>
                                                    <div class="col-xs-12 col-md-2">Action</div>
                                            </div>
                                             
                                    </div>
                                </div> -->

                        <div class="">
                            <label class="control-label">
<?= lang('files') ?>
                                : </label>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mediaGalleryDiv form-group">
                                    <button type="button" name="gallery" id="gallery-btn" data-href="<?php echo $url; ?>"  class="btn btn-primary"><?php echo lang('cost_placeholder_uploadlib') ?></button>
                                    <div class="mediaGalleryImg"> </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div id="dragAndDropFiles" class="uploadArea bd-dragimage">
                                    <div class="image_part">
                                        <label name="addfile[]">
                                            <h1 style="top: -162px;"> <i class="fa fa-cloud-upload"></i>
                                    <?= lang('DROP_IMAGES_HERE') ?>
                                            </h1>
                                            <input type="file" onchange="showimagepreview(this)" name="addfile[]" style="display: none" id="upl" multiple />
                                        </label>
                                    </div>
                                    <?php
                                    if (!empty($invoice_files)) {
                                        $img_data = $invoice_files;
                                        $i = 15482564;
                                        foreach ($img_data as $image) {
                                            $arr_list = explode('.', $image['file_name']);
                                            $arr = $arr_list[1];
                                            if (!file_exists($this->config->item('project_incidents_img_base_url') . $image['file_name'])) {
                                                ?>
                                                <!--<a onclick="delete_row(<?php echo $i; ?>)"class="remove_drag_img" id="delete_row">×</a>-->
                                                <div id="img_<?php echo $image['invoice_file_id']; ?>" class="eachImage"> <a title="<?php echo lang('delete'); ?>" class="delete_file remove_drag_img" href="javascript:;"
                                                                                                                             data-id="img_<?php echo $image['invoice_file_id']; ?>"
                                                                                                                             data-href="<?php echo base_url($invoice_view . '/delete_file/' . $image['invoice_file_id']); ?>"
                                                                                                                             data-name="<?php echo $image['file_name']; ?>" data-path="<?php echo $image['file_path']; ?>"
                                                                                                                             >x</a> <span id="<?php echo $i; ?>" class="preview"> <a href='<?php echo base_url($invoice_view . '/download/' . $image['invoice_file_id']); ?>' target="_blank">
                                                            <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                                                                <img src="<?= base_url($image['file_path']) . '/' . $image['file_name'] ?>"  width="75"/>
            <?php } else { ?>
                                                                <div><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png"  width="75"/>
                                                                    <p class="img_show"><?php echo $arr; ?></p>
                                                                </div>
                                                <?php } ?>
                                                        </a>
                                                        <p class="img_name"><?php echo $image['file_name']; ?></p>
                                                        <span class="overlay" style="display: none;"> <span class="updone">100%</span></span> 
                                                        <!-- <input type="hidden" value="<?php //echo $image['file_name']; ?>" name="fileToUpload[]"> --></span> </div>
        <?php } ?>
        <?php $i++;
    } ?>
                                        <div id="deletedImagesDiv"></div>
<?php } ?>
                                </div>
                                <div class="clr"> </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="form-group">
                                    <label class="  control-label">
<?= lang('CONTACT_VIEW_NOTES') ?>
                                    </label>
                                    <textarea name="notes_desc" placeholder="<?= lang('notes') ?>" id="notes_desc"
                                              class="form-control"><?= !empty($edit_record[0]['notes']) ? $edit_record[0]['notes'] : '' ?>
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="  control-label">
                                            <?= lang('payment_schedule') ?>
                            </label>
                            <div class = "form-group row" id="add_payment">
                                <div class="col-xs-12 col-md-12 visible-lg visible-md">
                                    <div class="col-xs-12 col-md-2">
                                        <label>
                                            <?= lang('cost_amount') ?> <span class="viewtimehide">*</span>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-md-2"></div>
                                    <div class="col-xs-12 col-md-3">
                                        <label>
<?= lang('due_on') ?> <span class="viewtimehide">*</span>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                        <label>
<?= lang('CONTACT_VIEW_NOTES') ?>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-md-1">
                                        <label>
                                <?= lang('actions') ?>
                                        </label>
                                    </div>
                                </div>
                                <!-- edit payment -->
<?php if (!empty($payment_details)) {
    foreach ($payment_details as $row) {
        ?>
                                        <div class="col-xs-12 col-md-12 form-group newrow" id="payment_edit_<?= $row['invoice_payment_id'] ?>">
                                            <div class="col-xs-12 col-md-2">
                                                <label class="visible-xs visible-sm">Amount</label>
                                                <input type="text" name="amount_<?= $row['invoice_payment_id'] ?>" required onkeypress="return numericDecimal(event)" maxlength="10" class="form-control amount_payment" placeholder="" value="<?= !empty($row['amount']) ? $row['amount'] : '' ?>">
                                            </div>
                                            <div class="col-xs-12 col-md-2">

                                                <select class="form-control tax_item" name="amount_per_<?= $row['invoice_payment_id'] ?>" data-parsley-trigger="change" >
        <?php if (count($country_info) > 0) { ?>
            <?php foreach ($country_info as $country) { ?>
                                                            <option <?php if (!empty($row['amount_per']) && $row['amount_per'] == $country["country_id"]) {
                    echo 'selected="selected"';
                } ?> value="<?php echo $country["country_id"]; ?>"><?php echo $country["currency_symbol"]; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-xs-12 col-md-3 date">
                                                <div class="input-group date due_on">
                                                    <input type="text" name="due_on_<?= $row['invoice_payment_id'] ?>" data-parsley-errors-container="#dt_err_edit_<?= $row['invoice_payment_id'] ?>" onkeydown="return false" required class="form-control due_on" placeholder="" value="<?php
                                                   if (isset($row['due_on']) && $row['due_on'] != '0000-00-00') {
                                                       echo configDateTime($row['due_on']);
                                                   };
                                                   ?>">
                                                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                                                <span id="dt_err_edit_<?= $row['invoice_payment_id'] ?>"></span> </div>
                                            <div class="col-xs-12 col-md-4">
                                                <input type="text" name="notes_<?= $row['invoice_payment_id'] ?>" class="form-control" placeholder="" value="<?= !empty($row['notes']) ? $row['notes'] : '' ?>">
                                            </div>
                                            <div class="col-xs-12 col-md-1"> <a class="pull-right btn btn-default" onclick="delete_payment_row('payment_edit_<?= $row['invoice_payment_id'] ?>');"> <span class="glyphicon glyphicon-trash"></span> </a> </div>
                                        </div>
    <?php }
} ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="  control-label">
<?= lang('total_amount_pay_to_client') ?>
                                : <span id="total_payment">
<?= !empty($edit_record[0]['total_payment']) ? $edit_record[0]['total_payment'] : '0.00' ?>
                                </span></label>
                            <input type="hidden" name="total_payment" id="total_payment_input" value="<?= !empty($edit_record[0]['total_payment']) ? $edit_record[0]['total_payment'] : '' ?>" />
                        </div>
                        <div class="form-group"> <a id="add_new_payment" class="btn btn-default align-center"> <span class="glyphicon glyphicon-plus"></span>
                                <?= lang('add_other_part_payment') ?>
                            </a> </div>
                        <div class="form-group bd-togl-label">
                            <input type="checkbox" class="btn-success" checked data-toggle="toggle" value="1" data-onstyle="success" <?php if (!empty($edit_record[0]['send_invoice_client'])) {
                                    echo 'checked="checked"';
                                } ?> id="send_invoice_client" name="send_invoice_client" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"/>
                            <label><?= lang('send_invoice') ?></label>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 col-lg-3">
                        <div class="form-group row">
                            <label class="col-lg-12 control-label">
                                        <?= lang('client') ?>
                            </label>
                            <div class="col-lg-12">
                                <select name="prospect_id" id="prospect_id" class="form-control chosen-select" onChange="ShowClientRelatedToCompany(this.value);" data-parsley-trigger="change" data-placeholder="<?= lang('select_client') ?> *" required>
                                    <option value=""></option>
                                    <optgroup label="Company">
<?php foreach ($company_info as $company) { ?>
                                            <option  <?php
                                            if ($EstClntArray == 'company_' . $company['company_id']) {
                                                echo "selected";
                                            }
                                            ?> value="company_<?php echo $company['company_id']; ?>"><?php echo $company['company_name']; ?></option>
<?php } ?>
                                    </optgroup>
                                    <optgroup label="Client">
<?php foreach ($client_info as $client) { ?>
                                            <option  <?php
    if ($EstClntArray == 'client_' . $client['prospect_id']) {
        echo "selected";
    }
    ?> value="client_<?php echo $client['prospect_id']; ?>"><?php echo $client['prospect_name']; ?></option>
                                    <?php } ?>
                                    </optgroup>
                                    <optgroup label="Contact">
                                        <?php foreach ($contact_info as $contact) { ?>
                                            <option <?php if ($EstClntArray == 'contact_' . $contact['contact_id']) {
                                            echo "selected";
                                        }
                                            ?> value="contact_<?php echo $contact['contact_id']; ?>"><?php echo $contact['contact_name']; ?></option>
                                    <?php } ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="ShowRecipientAsPerComapny">
                            <select name="recipient_id[]" id="recipient_id" multiple="" class="form-control chosen-select" data-placeholder="<?= lang('EST_SHOW_CLIENT_SELECT_RECIPIENTS') ?> *" required>
                                <option value=""></option>
                                <optgroup label="Client">
<?php foreach ($client_info as $client) { ?>
                                        <option  <?php
    if (in_array("client_" . $client['prospect_id'], $EstRecipientArray)) {
        echo "selected";
    }
    ?> value="client_<?php echo $client['prospect_id']; ?>"><?php echo $client['prospect_name']; ?></option>
                                    <?php } ?>
                                </optgroup>
                                <optgroup label="Contact">
                                    <?php foreach ($contact_info as $contact) { ?>
                                        <option <?php
                                    if (in_array("contact_" . $contact['contact_id'], $EstRecipientArray)) {
                                        echo "selected";
                                    }
                                        ?> value="contact_<?php echo $contact['contact_id']; ?>"><?php echo $contact['contact_name']; ?></option>
                                            <?php } ?>
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-12 control-label">
<?= lang('not_associat_project_id') ?>
                            </label>
                            <div class="col-lg-12">
                                <select tabindex="-1" id="not_associat_project_id" name="not_associat_project_id"
                                        class="chosen-select" data-placeholder="<?= lang('choose_a_project') ?>">
                                    <option value="">
<?= lang('choose_a_project') ?>
                                    </option>
<?php
if (!empty($projects_data)) {
    foreach ($projects_data as $row) {
        ?>
                                            <option <?php
                                        if (!empty($edit_record[0]['not_associat_project_id']) && $edit_record[0]['not_associat_project_id'] == $row['project_id']) {
                                            echo 'selected="selected"';
                                        }
                                        ?>
                                                value="<?= $row['project_id'] ?>">
        <?= ucfirst($row['project_name']) ?>
                                            </option>
                                        <?php
                                    }
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-12 control-label">
                        <?= lang('invoice') ?>
                                #</label>
                            <div class="col-lg-12">
                                <input type="text" readonly name="invoice_code" class="form-control" placeholder="" value="<?= !empty($edit_record[0]['invoice_code']) ? $edit_record[0]['invoice_code'] : $invoice_code ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-12 control-label">
                        <?= lang('date_of_creation') ?>
                            </label>
                            <div class="col-lg-12">
                                <input type="text" onkeydown="return false" class="form-control" placeholder="<?php echo lang('cost_placeholder_createddate') ?>" id="created_date" name="created_date" value="<?php if (isset($edit_record[0]['created_date'])) {
                            echo configDateTime($edit_record[0]['created_date']);
                        } else {
                            echo configDateTime();
                        } ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-12 control-label">
<?= lang('show_in_client_area') ?>
                            </label>
                            <div class="col-lg-12">
                                <select name="show_in_client_area" id="show_in_client_area" class="form-control" placeholder="">
                                    <option  <?php if (empty($edit_record[0]['show_in_client_area'])) {
    echo 'selected="selected"';
} ?> value="0">No</option>
                                    <option <?php if (!empty($edit_record[0]['show_in_client_area'])) {
    echo 'selected="selected"';
} ?> value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                                        <?php /* <div class="form-group row">
                                          <label class="col-lg-12 control-label">Auto Send?</label>
                                          <div class="col-lg-12">
                                          <select name="auto_send" class="form-control" placeholder="">
                                          <option  <?php if (empty($edit_record[0]['auto_send'])) { echo 'selected="selected"';  } ?> value="0">No</option>
                                          <option <?php if (!empty($edit_record[0]['auto_send'])) { echo 'selected="selected"';  } ?> value="1">Yes</option>
                                          </select>
                                          </div>

                                          </div>
                                         */ ?>
                        <div class="form-group row">
                            <label class="col-lg-12 control-label">
                                    <?= lang('recurring') ?>
                            </label>
                            <div class="col-lg-12">
                                <select id="recurring" onchange="return change_recurring(this.value);" name="recurring" class="form-control" placeholder="">
                                    <option value="0">
<?= lang('no_recurring') ?>
                                    </option>
                                    <option  <?php if (!empty($edit_record[0]['recurring']) && $edit_record[0]['recurring'] == 1) {
    echo 'selected="selected"';
} ?> value="1">Weekly</option>
                                    <option  <?php if (!empty($edit_record[0]['recurring']) && $edit_record[0]['recurring'] == 2) {
    echo 'selected="selected"';
} ?> value="2">Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                                    <?php $req = '';
                                    if (!empty($edit_record[0]['recurring']) && $edit_record[0]['recurring'] == 1) {
                                        $sty = 'style="display:block;"';
                                        $req = 'required';
                                    } else {
                                        $sty = 'style="display:none;"';
                                    } ?>
<?php $req1 = '';
if (!empty($edit_record[0]['recurring']) && $edit_record[0]['recurring'] == 2) {
    $sty1 = 'style="display:block;"';
    $req1 = 'required';
} else {
    $sty1 = 'style="display:none;"';
} ?>
                            <div class="col-lg-12" id="weekly" <?= $sty ?>>
<?php $weekday = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"); ?>
                                <select <?= $req ?> name="recurring_time" class="form-control" placeholder="">
                                    <option value="">
<?= lang('select_day') ?> *
                                    </option>
<?php foreach ($weekday as $day) { ?>
                                        <option  <?php if (!empty($edit_record[0]['recurring_time']) && $edit_record[0]['recurring_time'] == $day) {
        echo 'selected="selected"';
    } ?> value="<?= $day ?>">
    <?= $day ?>
                                        </option>
<?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-12" id="monthly" <?= $sty1 ?>>
                                <div class="input-group date recurring_time">
                                    <input <?= $req1 ?> type="text" onkeydown="return false" name="recurring_time_month" data-parsley-errors-container="#dt_err" onkeydown="return false" class="form-control recurring_time" placeholder="Date *" value="<?php
if (!empty($edit_record[0]['recurring_time']) && $edit_record[0]['recurring_time'] != '0000-00-00') {
    echo configDateTime($edit_record[0]['recurring_time']);
};
?>">
                                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                                <span id="dt_err"></span> </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-12 control-label">
<?= lang('currency') ?>
                            </label>
                            <div class="col-lg-12">
                                <select name="currency" class="form-control" placeholder="">
<?php if (count($country_info) > 0) { ?>
    <?php foreach ($country_info as $country) { ?>
                                            <option <?php if (!empty($edit_record[0]['currency']) && $edit_record[0]['currency'] == $country["country_id"]) {
            echo 'selected="selected"';
        } ?> value="<?php echo trim($country["country_id"]); ?>"><?php echo $country["currency_symbol"]; ?></option>
        <?php
    }
}
?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <input type="hidden" id="invoice_id" name="invoice_id"
                           value="<?= !empty($edit_record[0]['invoice_id']) ? $edit_record[0]['invoice_id'] : '' ?>">
                    <input type="hidden" id="delete_item_id" name="delete_item_id"
                           value="">
                    <input type="hidden" id="delete_payment_id" name="delete_payment_id"
                           value="">
                    <input type="hidden" name="client_name" id="client_name" value="<?= !empty($client_name) ? $client_name : '' ?>">
                    <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn"
                           value="<?= $submit_button_title ?>"/>
                    <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                </div>
                <div class="clr"></div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade modal-image" id="modalGallery" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onClick="$('#modalGallery').modal('hide');" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?= lang('uploads') ?></h4>
            </div>
            <div class="modal-body" id="modbdy"> </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onClick="$('#modalGallery').modal('hide');"><?= lang('close') ?></button>
            </div>
        </div>
        <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
</div>
<!-- /.modal -->
<style type="text/css">
    .parsley-required {
        word-break: normal;
        word-wrap: normal;
    }
</style>
<?php //echo form_close();  ?>
<script type="text/javascript">
<?php if (!empty($project_id)) { ?>
        $('#not_associat_project_id').val('<?= $project_id ?>');
        $('#prospect_id').val('<?= $project_data[0]["client_type"] ?>' + '_' + '<?= $project_data[0]["client_id"] ?>');
        //ShowClientRelatedToCompany('<?= $project_data[0]["client_type"] ?>'+'_'+'<?= $project_data[0]["client_id"] ?>');
    <?php if ($project_data[0]["client_type"] == 'company') { ?>
            ShowClientRelatedToCompany('<?= $project_data[0]["client_type"] ?>' + '_' + '<?= $project_data[0]["client_id"] ?>');
    <?php } else { ?>
            $('#recipient_id').val('<?= $project_data[0]["client_type"] ?>' + '_' + '<?= $project_data[0]["client_id"] ?>');
    <?php } ?>



<?php } ?>
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
                    url: "<?php echo base_url('Projectmanagement/Invoices/show_client_related_recipients'); ?>",
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
                url: "<?php echo base_url('Projectmanagement/Invoices/show_client_related_recipients'); ?>",
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
    //change recurring
    function change_recurring(id)
    {
        if (id == 1)
        {
            $('#monthly input,#weekly select').val('');
            $('#monthly input').removeAttr('required');
            $('#weekly').show();
            $('#weekly select').attr('required', '');
            $('#monthly').hide();

        }
        else if (id == 2)
        {
            $('#monthly input,#weekly select').val('');
            $('#weekly select').removeAttr('required');
            $('#monthly').show();
            $('#monthly input').attr('required', '');
            $('#weekly').hide();

        }
        else
        {
            $('#monthly input,#weekly select').val('');
            $('#monthly input').removeAttr('required');
            $('#weekly select').removeAttr('required');
            $('#monthly').hide();
            $('#weekly').hide();

        }
    }
    //Add item html
    count = 0;
    function add_item_limit()
    {
        var html = '';
        html += '<div class="col-xs-12 col-md-12  newrow" id="item_new_' + count + '">';
        html += '<div class="col-xs-12 col-md-2 form-group">';
        html += '<label class="visible-xs visible-sm">Line items</label>';
        html += '<input type="text" name="item_name[]" data-parsley-required-message="Required" maxlength="80" required class="form-control" placeholder="" value="">';
        html += '</div>';
        html += '<div class="col-xs-12 col-md-1 form-group">';
        html += '<label class="visible-xs visible-sm">Qty/Hrs</label>';
        html += '<input type="text" maxlength="5" name="qty_hours[]" required data-parsley-required-message="Required" onkeypress="return numericDecimal(event)" class="form-control item_cal qty_item" placeholder="" value="">';
        html += '</div>';
        html += '<div class="col-xs-12 col-md-2 form-group">';
        html += '<label class="visible-xs visible-sm">Rate</label>';
        html += '<input type="text" maxlength="10" name="rate[]" required data-parsley-required-message="Required" onkeypress="return numericDecimal(event)" class="form-control item_cal rate_item" placeholder="" value="">';
        html += '</div>';
        html += '<div class="col-xs-12 col-md-2 form-group">';
        html += '<label class="visible-xs visible-sm">Discount (%)</label>';
        html += '<input type="text" maxlength="5" name="discount[]" data-parsley-gteqm="#discount_' + count + '" id="discount_' + count + '" required data-parsley-required-message="Required" onkeypress="return numericDecimal(event)" class="form-control item_cal discount_item" placeholder="" value="">';
        html += '</div>';
        html += '<div class="col-xs-12 col-md-2 form-group">';
        html += '<label class="visible-xs visible-sm">Tax Rate (%)</label>';
        html += '<select class="form-control item_cal tax_item" name="tax_rate[]" required data-parsley-required-message="Required" data-parsley-trigger="change" >';
        html += '<option value=""><?= lang('tax') ?></option>';
<?php if (count($taxes) > 0) { ?>
    <?php foreach ($taxes as $tax) { ?>
                html += '<option value="<?php echo $tax["tax_id"]; ?>"><?php echo $tax["tax_percentage"]; ?></option>';
        <?php
    }
}
?>
        html += '</select>';
        html += '</div>';
        /*html +='<div class="col-xs-12 col-md-2">';
         html +='<input type="text" name="type[]" class="form-control" placeholder="" value="">';
         html +='</div>';*/

        html += '<div class="col-xs-12 col-md-2 form-group">';
        html += '<label class="visible-xs visible-sm">Cost</label>';
        html += '<input type="text" name="cost[]" onkeydown="return false" class="form-control total_cost" placeholder="" value="">';
        html += '</div>';
        html += '<div class="col-xs-12 col-md-1 form-group">';
        html += '<a class="pull-right btn btn-default ">';
        html += '<span class="glyphicon glyphicon-trash" onclick="delete_new_row(\'item_new_' + count + '\');"></span>';
        html += '</a>';
        html += '</div>';
        html += '</div>';
        count++;
        return html;
    }
    /*payment html code*/
    count = 0;
    function add_payment_schedule()
    {
        var html = '';
        html += '<div class="col-xs-12 col-md-12  newrow" id="payment_new_' + count + '">';
        html += '<div class="col-xs-9 col-md-2 form-group">';
        html += '<label class="visible-xs visible-sm">Amount</label>';
        html += '<input type="text" name="amount[]" onkeypress="return numericDecimal(event)" required data-parsley-required-message="Required" maxlength="10" class="form-control amount_payment" placeholder="" value="">';
        html += '</div>';
        html += '<div class="col-xs-3 col-md-2 form-group">';
        html += '<label class="visible-xs visible-sm">&nbsp;</label>';
        html += '<select class="form-control tax_item" name="amount_per[]" data-parsley-trigger="change" >';
<?php if (count($country_info) > 0) { ?>
    <?php foreach ($country_info as $country) { ?>
                html += '<option value="<?php echo $country["country_id"]; ?>"><?php echo $country["currency_symbol"]; ?></option>';
        <?php
    }
}
?>
        html += '</select>';
        html += '</div>';
        html += '<div class="col-xs-12 col-md-3 form-group">';
        html += '<label class="visible-xs visible-sm">Due on</label>';
        html += '<div class="input-group date due_on"><input type="text" name="due_on[]" onkeydown="return false" required data-parsley-errors-container="#dt_err' + count + '" data-parsley-required-message="Required" class="form-control due_on" placeholder="" value="">';
        html += '<span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span></div><span id="dt_err' + count + '"></span>';
        html += '</div>';
        html += '<div class="col-xs-12 col-md-4 form-group">';
        html += '<label class="visible-xs visible-sm">Notes</label>';
        html += '<input type="text" name="notes[]" class="form-control" placeholder="" value="">';
        html += '</div>';

        html += '<div class="col-xs-12 col-md-1 form-group">';
        html += '<label class="visible-xs visible-sm ">Action</label>';
        html += '<a class="pull-right btn btn-default" onclick="delete_new_row(\'payment_new_' + count + '\');">';
        html += '<span class="glyphicon glyphicon-trash"></span>';
        html += '</a>';
        html += '</div>';
        html += '</div>';
        count++;

        return html;
    }
    $(function () {
        //Add more item
        $('#add_new_item').click(function () {
            item_html = add_item_limit();
            $('#add_items').append(item_html);
            $('#from-model').parsley();
        });
        /*end item code*/

        $('#add_new_payment').click(function () {
            html = add_payment_schedule();
            $('#add_payment').append(html);
            $('#from-model').parsley();
            $('.due_on')
                    .datepicker({
                        autoclose: true, startDate: new Date()
                    });
        });
        /*end payment code*/
<?php if (!isset($edit_record)) { ?>

            //Append first time item
            item_html = add_item_limit();
            $('#add_items').append(item_html);

            //Append first time payment
            payment_html = add_payment_schedule();
            $('#add_payment').append(payment_html);
            $('#from-model').parsley();
<?php } ?>
        //auto select client area
        $('#send_invoice_client').click(function () {
            if (this.checked) {
                $('#show_in_client_area').val(1);
            }
            else {
                $('#show_in_client_area').val(0);
            }
        });

    });
//remove new row
    function delete_new_row(del_id)
    {
        var delete_meg = "<?= lang('delete_item') ?>";
        BootstrapDialog.show(
                {
                    title: '<?= lang('alert') ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
                                $('#' + del_id).remove();
                                //count current item
                                var total_amt = 0;
                                $("#add_items .total_cost").each(function (index) {
                                    if ($(this).val() != 0.00 && $(this).val() != '')
                                    {
                                        total_amt += parseFloat($(this).val());
                                    }

                                });
                                $('#total_item').text(total_amt.toFixed(2));
                                $('#amount_total').val(total_amt.toFixed(2));
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                                dialog.close();
                            }

                        }]
                });


    }
//remove new row
    function delete_item_row(del_id)
    {
        var delete_meg = "<?= lang('delete_item') ?>";
        BootstrapDialog.show(
                {
                    title: '<?= lang('alert') ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {

                                var del_ids = $('#delete_item_id').val();
                                remove_id = del_id.split('item_edit_');
                                $('#delete_item_id').val(del_ids + remove_id[1] + ',');

                                $('#' + del_id).remove();
                                //count current item
                                var total_amt = 0;
                                $("#add_items .total_cost").each(function (index) {
                                    if ($(this).val() != 0.00 && $(this).val() != '')
                                    {
                                        total_amt += parseFloat($(this).val());
                                    }

                                });
                                $('#total_item').text(total_amt.toFixed(2));
                                $('#amount_total').val(total_amt.toFixed(2));
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                                dialog.close();
                            }

                        }]
                });
    }
//remove payment row
    function delete_payment_row(del_id)
    {
        var delete_meg = "<?= lang('delete_item') ?>";
        BootstrapDialog.show(
                {
                    title: '<?= lang('alert') ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
                                var del_ids = $('#delete_payment_id').val();
                                remove_id = del_id.split('payment_edit_');
                                $('#delete_payment_id').val(del_ids + remove_id[1] + ',');

                                $('#' + del_id).remove();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                                dialog.close();
                            }

                        }]
                });


    }
//calculation
    $('body').delegate('.item_cal', 'change', function () {
        dis_id = $(this).parent().parent().attr('id');
        get_row_total(dis_id);
    });
//count total payment
    $('body').delegate('.amount_payment', 'blur', function () {
        //count total payment
        var total_payment_amt = 0;
        $("#add_payment .amount_payment").each(function (index) {
            if ($(this).val() != 0.00 && $(this).val() != '')
            {
                total_payment_amt += parseFloat($(this).val());
            }

        });
        $('#total_payment').text(total_payment_amt.toFixed(2));
        $('#total_payment_input').val(total_payment_amt.toFixed(2));
    });
//datepicker
    $('body .due_on').datepicker({
        autoclose: true, startDate: new Date()
    });
    $('body .recurring_time').datepicker({
        //format: 'dd'
    });

//get row wise total
    function get_row_total(dis_id)
    {
        var qty = '';
        var rate = '';
        var tax = '';
        var discount = '';
        var cost = '';
        var total_cost = 0;
        qty = $('#' + dis_id + ' .qty_item').val();
        rate = $('#' + dis_id + ' .rate_item').val();

        //tax      = $('#'+dis_id+' .tax_item:selected').text();
        tax = $('#' + dis_id + ' .tax_item option:selected').text();
        discount = $('#' + dis_id + ' .discount_item').val();

        //rate calculation
        if (rate != '')
        {
            total_cost = qty * parseFloat(rate);
            total_rate = qty * parseFloat(rate);

            $('#' + dis_id + ' .total_cost').val(total_cost.toFixed(2));
        }
        //discount
        if (discount != '')
        {
            var dis_total = parseFloat(total_cost) * parseFloat(discount) / 100;
            var total_cost = parseFloat(total_rate) - parseFloat(dis_total);
            //alert(total_cost);
            $('#' + dis_id + ' .total_cost').val(total_cost.toFixed(2));
        }
        //tax
        if ($.isNumeric(tax))
        {

            if (total_cost != 0)
            {
                var tax_total = parseFloat(total_cost) * parseFloat(tax) / 100;
                var total_cost = parseFloat(total_cost) + parseFloat(tax_total);
            }
            else
            {
                var total_cost = parseFloat(total_rate) * parseFloat(tax) / 100;
            }

            $('#' + dis_id + ' .total_cost').val(total_cost.toFixed(2));
        }
        var total_amt = 0;
        $("#add_items .total_cost").each(function (index) {
            if ($(this).val() != 0.00 && $(this).val() != '')
            {
                total_amt += parseFloat($(this).val());
            }

        });
        $('#total_item').text(total_amt.toFixed(2));
        $('#amount_total').val(total_amt.toFixed(2));
        //alert(total_amt);

    }
//numeric decimal number
    function numericDecimal(e) {
        var unicode = e.charCode ? e.charCode : e.keyCode;
        //alert(unicode);
        if (unicode != 8) {
            if (unicode < 9 || unicode > 9 && unicode < 46 || unicode > 57 || unicode == 47) {
                return false;
            }
            else {
                return true;
            }
        }
        else {
            return true;
        }
    }
</script> 
<script type="text/javascript">
    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('costModel').modal('hide');
        $('#modalGallery').modal('show');
    });
    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

        $('body').addClass('modal-open');
    });
    $('#notes_desc,#description').summernote({
        height: 150, //set editable area's height
        codemirror: {// codemirror options
            theme: 'monokai'
        },
        focus: true
    });

    $(function () {

        $('.chosen-select').chosen();


        $('#from-model').parsley();//parsaley validation reload

        //disabled after submit
        $('body').delegate('#submit_btn', 'click', function () {
            if ($('#from-model').parsley().isValid()) {
                var total_item = $('#amount_total').val();
                var total_payment = $('#total_payment_input').val();
                if (total_item != total_payment)
                {
                    var delete_meg = "<?= lang('total_amount_Same') ?>";
                    BootstrapDialog.show(
                            {
                                title: '<?= lang('alert') ?>',
                                message: delete_meg,
                                buttons: [{
                                        label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                                        action: function (dialog) {
                                            $('#confirm-id').on('hidden.bs.modal', function () {
                                                $('body').addClass('modal-open');
                                            });
                                            dialog.close();
                                        }
                                    }, {
                                        label: '<?php echo $this->lang->line('ok'); ?>',
                                        action: function (dialog) {
                                            $('input[type="submit"]').prop('disabled', true);
                                            $('#from-model').submit();
                                            $('#confirm-id').on('hidden.bs.modal', function () {
                                                $('body').addClass('modal-open');
                                            });
                                            dialog.close();
                                        }


                                    }]
                            });
                    return false;
                }
                $('input[type="submit"]').prop('disabled', true);
                $('#from-model').submit();
            }
        });
    });
    //Remove files
    $('.delete_file').on('click', function () {
        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');

        var delete_meg = "<?= lang('common_delete_file') ?>";
        BootstrapDialog.show(
                {
                    title: '<?= lang('alert') ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
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

</script> 
<!-- Upload image script --> 
<script type="text/javascript">

    var config = {
        //support : "image/jpg,image/png,image/bmp,image/jpeg,image/gif",       // Valid file formats
        support: "*", // Valid file formats
        form: "demoFiler", // Form ID
        dragArea: "dragAndDropFiles", // Upload Area ID
        uploadUrl: "<?php echo $invoice_view; ?>/upload_file"              // Server side upload url
    }

    $(document).ready(function () {
        //initMultiUploader(config);
        var dropbox;
        var oprand = {
            dragClass: "active",
            on: {
                load: function (e, file) {
                    // check file size
                    if (parseInt(file.size / 1024) > 20480) {
                        alert("File \"" + file.name + "\" is too big.Max allowed size is 2 MB.");
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
            xhr[rand].open("post", "<?php echo base_url('/Projectmanagement/Invoices/file_upload') ?>/" + fileext, true);

            xhr[rand].upload.addEventListener("progress", function (event) {
                //console.log(event);
                if (event.lengthComputable) {
                    $(".progress[id='" + rand + "'] span").css("width", (event.loaded / event.total) * 100 + "%");
                    $(".preview[id='" + rand + "'] .updone").html(((event.loaded / event.total) * 100).toFixed(2) + "%");
                }
                else {
                    var delete_meg = "<?php echo lang("fail_file_upload"); ?>";
                    BootstrapDialog.show(
                            {
                                title: '<?php echo $this->lang->line('Information'); ?>',
                                message: delete_meg,
                                buttons: [{
                                        label: '<?php echo $this->lang->line('ok'); ?>',
                                        action: function (dialog) {
                                            dialog.close();
                                        }
                                    }]
                            });
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
                            template += '<span class="preview" id="' + rand + '"><div class="image_ext"><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + filetype + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
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
    function delete_row(rand) {
        jQuery('#' + rand).remove();
    }

    //image upload
    function showimagepreview(input)
    {
        $('.upload_recent').remove();
        var url = '<?php echo base_url(); ?>';
        $.each(input.files, function (a, b) {
            var rand = Math.floor((Math.random() * 100000) + 3);
            var arr1 = b.name.split('.');
            var arr = arr1[1].toLowerCase();
            var filerdr = new FileReader();
            var img = b.name;
            filerdr.onload = function (e) {
                var template = '<div class="eachImage upload_recent" id="' + rand + '">';
                var randtest = 'delete_row("' + rand + '")';
                template += '<a id="delete_row" class="remove_drag_img" onclick=' + randtest + '>×</a>';
                if (arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif') {
                    template += '<span class="preview" id="' + rand + '"><img src="' + e.target.result + '"><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                } else {
                    template += '<span class="preview" id="' + rand + '"><div><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + arr + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                }
                template += '<input type="hidden" name="file_data[]" value="' + b.name + '">';
                template += '</span>';
                $('#dragAndDropFiles').append(template);
            }
            filerdr.readAsDataURL(b);
        });
        var maximum = input.files[0].size / 1024;

    }
    window.Parsley.addValidator('gteqm',
            function (value, requirement) {
                return parseFloat(value) <= 100;
            }, 32)
            .addMessage('en', 'gteqm', '<?= lang('less_value') ?>');
    //multiple popup open
    $('#modalGallery,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

        $('body').addClass('modal-open');
    });
    //checkbox toggle
    $('#send_invoice_client').bootstrapToggle('off');

<?php if (isset($edit_record[0]['send_invoice_client']) && $edit_record[0]['send_invoice_client'] == 1) { ?>
        $('#send_invoice_client').bootstrapToggle('on');
<?php } ?>

<?php if (!empty($project_id)) { ?>
        var prospectName = $('#prospect_id_chosen .chosen-single span').html();

        $('#client_name').val(prospectName);

<?php } ?>
    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

        $('body').addClass('modal-open');
    });
</script> 