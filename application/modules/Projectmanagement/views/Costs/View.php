<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'saveCostData';
$formPath = $project_view . '/' . $formAction;
$path = "";
$name = "";
?>    
<?php
//$attributes = array("name" => "frmtask","id"=>"frmtask");
//echo form_open_multipart(base_url($formPath));
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><b><?php echo!empty($edit_record[0]['cost_name']) ? $edit_record[0]['cost_name'] : '' ?></b> </h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('cost_placeholder_name') ?></label>
                        <?php echo!empty($edit_record[0]['cost_name']) ? $edit_record[0]['cost_name'] : '' ?>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label><?php echo 'Cost Code'; ?></label>
                        <?php echo!empty($edit_record[0]['cost_code']) ? $edit_record[0]['cost_code'] : rand() ?>
                    </div>
                </div>
                <div class="clearfix visible-xs-block"></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-inline">
                        <label><?php echo lang('Task'); ?></label>
                        <select tabindex="-1" id="task_id"  name="task_id" class="form-control" data-placeholder="Choose a Task" disabled="">
                            <option value=""><?php echo lang('cost_placeholder_task'); ?></option>
                            <?php
                            if (!empty($tasks)) {
                                foreach ($tasks as $row) {
                                    ?>
                                    <option  value="<?php echo $row['task_id'] ?>" <?php echo (isset($edit_record[0]['task_id']) && $edit_record[0]['task_id'] == $row['task_id']) ? 'selected' : ''; ?>><?php echo ucfirst($row['task_name']); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <label><?php echo lang('cost_placeholder_createddate') ?></label>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="input-group date" id="created_date">
                                <?php echo!empty($edit_record[0]['created_date']) ? configDateTime($edit_record[0]['created_date']) : date('m/d/Y') ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-inline">
                        <label><?php echo lang('Member'); ?></label>  
                        <select tabindex="-1" id="user_id"  name="user_id" class="form-control" data-placeholder="Choose a user" disabled="">
                            <option value=""><?php echo lang('cost_placeholder_member'); ?></option>
                            <?php
                            if (!empty($res_user)) {
                                foreach ($res_user as $row) {
                                    ?>
                                    <option <?php echo ($edit_record[0]['user_id'] == $row['login_id']) ? 'selected' : ''; ?> value="<?php echo $row['login_id'] ?>"><?php echo ucfirst($row['firstname']) . ' ' . $row['lastname'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <label><?php echo lang('start_date'); ?></label>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="input-group date" id="start_date">
                                <?php echo!empty($edit_record[0]['start_date']) ? configDateTime($edit_record[0]['start_date']) : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>

                <div class="clr"></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-inline">
                        <label><?php echo lang('cost_type');?></label>
                        <select class="form-control" name="cost_type" id="cost_type" disabled>
                            <option value=""><?php echo lang('cost_type');?></option>
                            <option value="Finance" <?php echo ($edit_record[0]['cost_type'] == 'Finance') ? 'selected' : ''; ?>>Finance</option>
                            <option value="Commission" <?php echo ($edit_record[0]['cost_type'] == 'Commission') ? 'selected' : ''; ?>>Commission</option>
                            <option value="Tax" <?php echo ($edit_record[0]['cost_type'] == 'Tax') ? 'selected' : ''; ?>>Tax</option>
                            <option value="Design" <?php echo ($edit_record[0]['cost_type'] == 'Design') ? 'selected' : ''; ?>>Design</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <label><?php echo lang('due_date'); ?></label>

                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="input-group date" id="due_date">
                                <?php echo!empty($edit_record[0]['due_date']) ?  configDateTime($edit_record[0]['due_date']): '' ?> </div>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>

                <div class="clr"></div>
            </div>
            <div class="clr"></div>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="form-group">
                        <label >
                            <?php echo lang('cost_placeholder_projectbudget'); ?>
                        </label>
                        <?php echo ($edit_record[0]['within_project'] == 1) ? 'Yes' : 'No'; ?> 
                        <!--                        <div class="btn-group btn-toggle">
                                                    <button class="btn btn-xs btn-default">ON</button>
                                                    <button class="btn btn-xs btn-primary active">OFF</button>
                                                </div>-->
                        <!--                        <label>Within Project Budget?</label>-->
                    </div>
                </div>
                <div class="clr"></div>
                <div class="col-xs-12 col-md-5">
                    <div class="form-group">
                        <label><?php echo lang('cost_placeholder_amount') ?></label>

                        <?php echo!empty($edit_record[0]['ammount']) ? $edit_record[0]['ammount'] : '' ?>
                    </div>
                </div>
                <div class="col-xs-12 col-md-1">
                    <div class="pad-tb6"></div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-inline">
                        <label><?php echo lang('product_service');?></label>  
                        <select class="form-control" name="product_id" disabled="" data-parsley-errors-container="#pid-errors" id="product_id" required>
                            <?php
                            if (count($products > 0)) {
                                foreach ($products as $prod) {
                                    ?>
                                    <option value="<?php echo $prod['product_id']; ?>" <?php echo ($edit_record[0]['product_id'] == $prod['product_id']) ? 'selected' : ''; ?>><?php echo $prod['product_name']; ?></option>
                                <?php } ?>
                            <?php } ?>

                        </select>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">

                        <label><?php echo lang('cost_placeholder_expense'); ?></label>  
                        <?php echo ($edit_record[0]['expense_supplier'] == 1) ? 'Yes' : 'No'; ?>

                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('supplier');?></label>   
                        <?php if (count($supplier_data) > 0) { ?>
                            <?php foreach ($supplier_data as $supp) { ?>
                                <span><?php echo $supp['supplier_name']; ?></span>

                                <?php
                            }
                        } else {
                            ?>
                            <p><?php echo lang('no_supplier_msg');?></p>
                        <?php } ?>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="clr"> </div>

            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="form-group">
                        <label><?php echo lang('cost_placeholder_desc') ?></label>
                        <?php echo!empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?>

                    </div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="clr"> </div>

            <div class="row">

                <div class ="form-group row">
                    <div class = "col-sm-12">

                        <?php
                        if (count($cost_files) > 0) {
//                                $file_img = $campaign_data[0]['file'];
//                                $img_data = explode(',', $file_img);
                            $i = 15482564;
                            foreach ($cost_files as $image) {
                                $path = $image['file_path'];
                                $name = $image['file_name'];
                                $arr_list = explode('.', $name);
                                $arr = $arr_list[1];
                                if (!file_exists($this->config->item('Request_img_base_url') . $name)) {
                                    ?>
                                    <div id="img_<?php echo $image['cost_file_id']; ?>" class="eachImage">
                                        <span id="<?php echo $i; ?>" class="preview">
                                            <a href='<?php echo base_url($project_view . '/download/' . $image['cost_file_id']); ?>' target="_blank">

                                                <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>                  
                                                    <img src="<?= base_url($path . '/' . $name); ?>"  width="75"/>        <?php } else { ?>
                                                    <div><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png"  width="75"/><p class="img_show"><?php echo $arr; ?></p></div>
                                                <?php } ?>
                                            </a>
                                            <p class="img_name"><?php echo $name; ?></p>
                                            <span class="overlay" style="display: none;">
                                                <span class="updone">100%</span></span>
            <!--                                                <input type="hidden" value="<?php echo $name; ?>" name="fileToUpload[]">-->
                                        </span>

                                    </div>

                                <?php } ?>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </div>

                </div>
                <div class="clr"></div>
            </div>
            <div class="clr"> </div>
        </div>

    </div>
