<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$formAction = 'insertdata';
$path = $opportunity_view . '/' . $formAction;
?>    
<?php echo form_open_multipart($path); ?>

<div class="col-lg-2 col-md-2 col-xs-4">

    <div class="link-style1">

        <!-- Modal New Opportunity-->
        <div id="newOpportunity" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><div class="modelTitle"></div></h4>
                    </div>

                    <div class="modal-body">

                        <div class = "form-group row">
                            <div class = "col-sm-7">

                                <input type="text" id="prospect_id" name="prospect_id"  hidden="">
                                <input type="text" class="form-control" placeholder="<?= $this->lang->line('prospect_name') ?>" id="prospect_name" name="prospect_name" value="">
                                <span id="prospect_name_error" class="alert-danger"></span>
                            </div>
                            <div class = "col-sm-5">
                                <?php $six_digit_random_number = mt_rand(100000, 999999);
                                $pros_auto_id = 'P' . $six_digit_random_number;
                                ?>
                                <input type="text" class="form-control" placeholder="<?php echo $pros_auto_id; ?>" id="pros_auto_id" name="pros_auto_id"  value="<?php echo $pros_auto_id; ?>" readonly="">
                            </div>
                        </div>

                        <div class = "form-group row">
                            <div class = "col-sm-7">
                                <input type="text" class="form-control" placeholder="<?= $this->lang->line('company_name') ?>" id="company_name" name="company_name" value=""> 
                                <span id="company_name_error" class="alert-danger"></span>
                            </div>
                            <div class = "col-sm-5">
                                <select name="prospect_owner_id" class="form-control selectpicker" id="prospect_owner_id">
                                    <option value=""><?= $this->lang->line('select_prospect_owner') ?></option>

                                    <?php if (isset($prospect_owner) && count($prospect_owner) > 0) { ?>
                                        <?php foreach ($prospect_owner as $prospect) { ?>
                                            <option value="<?php echo $prospect['contact_id']; ?>"><?php echo $prospect['contact_name']; ?></option>
                                        <?php } ?>
<?php } ?>
                                </select>
                                <span id="prospect_owner_error" class="alert-danger"></span>
                            </div>
                        </div>

                        <div class = "form-group row">
                            <div class = "col-sm-7">
                                <input type="text" class="form-control" placeholder="<?= $this->lang->line('address1') ?>" id="address1" name="address1">
                            </div>

                            <div class = "col-sm-5">
                                <div class="input-group date">
                                    <input type="text" class="form-control" placeholder="<?= $this->lang->line('creation_date') ?>" id="creation_date" name="creation_date" onkeydown="return false">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class = "form-group row">
                            <div class = "col-sm-7">
                                <input type="text" class="form-control" placeholder="<?= $this->lang->line('address2') ?>" id="address2" name="address2">
                            </div>
                            <div class = "col-sm-5">
                                <select name="language_id" class="form-control selectpicker" id='language_id'>
                                    <option value=""><?= $this->lang->line('language_not_filled') ?></option>
                                    <option value="1" ><?= $this->lang->line('english') ?></option>
                                    <option value="0"><?= $this->lang->line('spanish') ?></option>
                                </select> 
                            </div>
                        </div>

                        <div class = "form-group row">
                            <div class = "col-sm-4">
                                <input type="text" class="form-control" placeholder="<?= $this->lang->line('postal_code') ?>" id="postal_code" name="postal_code" onkeypress="return isNumberKey(event)">
                            </div>
                            <div class = "col-sm-3">
                                <input type="text" class="form-control" placeholder="<?= $this->lang->line('state') ?>" id="state" name="state"> 
                            </div>
                            <div class = "col-sm-5">
                                <select name="branch_id" class="form-control selectpicker" id="branch_id">
                                    <option value=""><?= $this->lang->line('branche') ?></option>
                                    <?php if (isset($branch_data) && count($branch_data) > 0) { ?>
                                        <?php foreach ($branch_data as $branch) { ?>
                                            <option value="<?php echo $branch['branch_id']; ?>"><?php echo $branch['branch_name']; ?></option>
                                        <?php } ?>
<?php } ?>
                                </select>
                                <span id="branch_error" class="alert-danger"></span>
                            </div>
                        </div>

                        <div class = "form-group row">
                            <div class = "col-sm-7">
                                <input type="text" class="form-control" placeholder="<?= $this->lang->line('country') ?>" id="country" name="country">
                            </div>
                            <div class = "col-sm-5">
                                <input type="text" class="form-control" placeholder="<?= $this->lang->line('estimate_prospect_worth') ?>" id="estimate_prospect_worth" name="estimate_prospect_worth" onkeypress="return isNumberKey(event)">
                            </div>
                        </div>

                        <div id="add_more" class="form-group add_contacts">
                            <div class = "form-group row contacts" id="add_contact0">
                                <div class = "col-sm-3">
                                    <input type="text" class="form-control" placeholder="<?= $this->lang->line('contact_name') ?>" name="contact_name[]" id="contact_name0">
                                    <span class="alert-danger" id="contact_name_error0"></span>
                                </div>
                                <div class = "col-sm-4">
                                    <input type="text" class="form-control" placeholder="<?= $this->lang->line('email_address') ?>" name="email_id[]" id="email_id0">
                                    <span class="alert-danger" id="email_error0"></span>
                                </div>
                                <div class = "col-sm-4">
                                    <input type="text" class="form-control" placeholder="<?= $this->lang->line('phone_no') ?>" name="phone_no[]" min="0" id="phone_no0" onkeypress="return isNumberKey(event)" size="20" maxlength="20">
                                    <span class="alert-danger" id="phone_no_error0"></span>
                                </div>
                                <div class = "col-sm-1">
                                    <a id='delete_row' class="pull-right btn btn-default"><span class="glyphicon glyphicon-trash"></span></a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <a id="add_row" class="btn btn-default align-center">
                                <span class="glyphicon glyphicon-plus"></span><?= $this->lang->line('add_another_contact') ?>
                            </a>
                        </div>

                        <div class = "form-group row">
                            <div class = "col-sm-8">
                                <input checked data-toggle="toggle" data-onstyle="success" type="checkbox"  id="prospect_generate" name="prospect_generate"/>
                                <label class="checkbox-inline"><?= $this->lang->line('prospect_generated_by_marketing_campaign?') ?></label>
                                <select name="campaign_id" class="form-control selectpicker" id="campaign_id">
                                    <option value="" selected=""><?= $this->lang->line('select_campaign') ?></option>
                                    <?php if (isset($campaign) && count($campaign) > 0) { ?>
                                        <?php foreach ($campaign as $result) { ?>
                                            <option value="<?php echo $result['campaign_id']; ?>"><?php echo $result['campaign_name']; ?></option>
                                        <?php } ?>
<?php } ?>
                                </select>
                                <span id="campaign_error" class="alert-danger"></span>
                            </div>

                            <div class = "col-sm-4">
                                <label for='interested_products[]'><?= $this->lang->line('interested_products/services') ?></label><br>
                                <select multiple="true" name="interested_products[]" id="interested_products" class="chosen-select">
                                    <?php if (isset($product_data) && count($product_data) > 0) { ?>
                                        <?php foreach ($product_data as $product) { ?>
                                            <option value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></option>
                                        <?php } ?>
<?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class = "form-group row">
                            <div class = "col-sm-6">
                                <textarea class="form-control" rows="4" placeholder="<?= $this->lang->line('description') ?>" name="description" id="description"></textarea>
                            </div>
                            <div class = "col-sm-6">
                                <div class="input-group date">
                                    <input type="text" class="form-control" placeholder="<?= $this->lang->line('contact_date') ?>" id="contact_date" name="contact_date" onkeydown="return false">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
<?= $this->lang->line('add_a_file') ?> <input type="file" title="Add a File" class="file" id="file" name="userfile">
                                <input type="hidden" name="file_name" id="file_name">
                                <p id="file_name_text"></p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <center><input type="submit" class="btn btn-info" name="prospect_submit" id="opp_submit_btn" value="Submit" /></center>
                        </div>
                    </div>

                </div>
            </div> 
        </div>
        <div class="clr"></div>

    </div>
</div>
<?php echo form_close(); ?>
