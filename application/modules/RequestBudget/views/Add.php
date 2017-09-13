<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>


<div id="createSalesCampaign" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span id="type_action">Add&nbsp;</span><?= $this->lang->line('BUDGET_REQUEST_SALES_CAMPAIGN') ?></h4>
            </div>

            <div class="modal-body">
    <form class="form-horizontal" role="form" data-toggle="validator" name="frm_request_budget" id="frm_request_budget" enctype="multipart/form-data" action="" method="post">
                    <div class="row">
                        <div class="col-md-6">   
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <select class="selectpicker form-control" name="master_compaign" id="master_compaign" required onchange="autofilldata(this.value);" >
                                        <option value="" selected=""><?= $this->lang->line('SELECT_COMPAIGN') ?></option>
                                        <?php 
                                        foreach ($compaign_master as $compaign_master_array)
                                        { ?>
                                        <option value="<?php echo $compaign_master_array['campaign_id']?>"><?php echo $compaign_master_array['campaign_name']?></option>
                                       <?php } ?>
                                    </select>
                                    <span id="error_master_compaign" class="alert-danger"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <select class="selectpicker form-control" name="campaign_type_id" id="campaign_type_id" required>
                                        <option value="" selected=""><?= $this->lang->line('TYPE_OF_CAMPAIGN') ?></option>
                                        <?php 
                                        foreach ($type_compaign as $type_compaign_array)
                                        { ?>
                                        <option value="<?php echo $type_compaign_array['camp_type_id']?>"><?php echo $type_compaign_array['camp_type_name']?></option>
                                       <?php } ?>
                                    </select>
                                    <span id="error_campaign_type_id" class="alert-danger"></span>
                                </div>
                            </div>

                            <div class='col-sm-12 input-group date' id='start_date'>
                                <input type='text' class="form-control" name="start_date" readonly id="start_date" value=""  placeholder="<?= $this->lang->line('START_DATE') ?>" required/>

                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                <span id="error_start_date" class="alert-danger"></span>
                            </div>
                        </div> 
								
                        <div class="col-md-6">   
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input type="text" disabled="true" name="campaign_auto_id" class="form-control" id="campaign_auto_id" placeholder="<?= $this->lang->line('AUTOFILLED_BASED_ON_COMPAIGN') ?>" value=""  required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <select class="selectpicker form-control" name="responsible_employee_id" id="responsible_employee_id"  required>
                                        <option value="" selected=""><?= $this->lang->line('RESPONSIBLE_EMPLOYEE') ?></option>
                                         <?php 
                                        foreach ($employee_list as $employee_list_array)
                                        { ?>
                                        <option value="<?php echo $employee_list_array['contact_id']?>"><?php echo $employee_list_array['contact_name']?></option>
                                       <?php } ?>
                                    </select>
                                     <span id="error_responsible_employee_id" class="alert-danger"></span>
                                </div>
                            </div>
							
                            <div class="form-group">
                                    <div class='col-sm-12 input-group date' id='end_date'>
                                        <input type='text' class="form-control" name="end_date" readonly id="end_date"  placeholder="<?= $this->lang->line('END_DATE') ?>" required/>
                                        <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <span id="error_end_date" class="alert-danger"></span>
                                    </div>
                            </div>
                        </div>  

                        <div class="col-md-12"> 
                            <div class="form-group">
                                <div class="col-sm-12">
                                        <textarea class="form-control" name="campaign_description" value="" rows="5" id="campaign_description" placeholder="<?= $this->lang->line('CAMPAIGN_DESCRIPTION') ?>"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">  
		
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label for="email">Budget Amount</label>
                                    <input type="text" name="budget_ammount" class="form-control" id="budget_ammount_pri" placeholder="<?= $this->lang->line('BUDGET_AMOUNT') ?>" required/>
                                    <span id="error_budget_ammount" class="alert-danger"></span>
                                </div>
                            </div>
							
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label for="email">Budget Request For Supplier</label>
                                    <select class="form-control selectpicker" name="supplier_id" id="supplier_id" required>
                                        <option value="" selected=""><?= $this->lang->line('AUTOFILLED_BASED_ON_COMPAIGN') ?></option>
                                         <?php
                                            foreach ($supplier_list as $supplier)
                                            { ?>
                                        <option value="<?php echo $supplier['supplier_id'];?>"><?php echo $supplier['supplier_name'];?></option>
                                           <?php }
                                        
                                        ?>	  
                                    </select>
                                    <span id="error_revenue_goal" class="alert-danger"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="aditional_notes" id="aditional_notes" rows="5" id="campaign_description" placeholder="<?= $this->lang->line('ADDITIONAL_NOTES') ?>"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">  
		
                            <div class="form-group">
								
                                <div class="col-sm-12">
                                    <label for="email">Revenue Goal</label>
                                    <input type="text" name="revenue_goal" class="form-control" id="revenue_goal" placeholder="<?= $this->lang->line('BUDGET_AMOUNT') ?>" required/>
                                    <span id="error_revenue_goal" class="alert-danger"></span>
                                </div>
                            </div>
							
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label for="email">Sales Budget For Product</label>
                                    <select class="form-control" name="budget_for_product" id="budget_for_product">
                                        <option value="" selected=""><?= $this->lang->line('AUTOFILLED_BASED_ON_COMPAIGN') ?></option>
					<?php
                                            foreach ($product_list as $product)
                                            { ?>
                                        <option value="<?php echo $product['product_id'];?>"><?php echo $product['product_name'];?></option>
                                           <?php }
                                        
                                        ?>					
                                    </select>
                                    <span id="error_budget_for_product" class="alert-danger"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12" id="">
                                    <input type="file" name="budget_request_file" id="budget_request_file" class="form-control"/>
                                    <span id="spn_download"></span>
                                    <span id="error_budget_request_file" class="alert-danger"></span>
                                </div>
                            </div>
                        </div>

                            
                    </div>  

                    <input type="hidden" name="hdn_budget_campaign_id" id="hdn_budget_campaign_id" value=""/>
                    <input type="hidden" name="hdn_campaign_id" id="hdn_campaign_id" value=""/>
                    <input type="hidden" name="hdn_auto_gen_id" id="hdn_auto_gen_id" value=""/>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-10">
                            <input type="button" id="btn_submit" onclick="createRecord();" class="btn btn-info" value="<?= $this->lang->line('REQUEST_CAMPAIGN_BUDGET') ?>">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= $this->lang->line('CLOSE') ?></button>
            </div>
        </div>

    </div>

</div>
