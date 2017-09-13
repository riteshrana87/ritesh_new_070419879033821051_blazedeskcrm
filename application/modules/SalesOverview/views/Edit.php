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
    <div class="modal-content costmodaldiv">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><div class="title">Edit Cost</div></h4>
        </div>
        <form id="from-model" method="post" enctype="multipart/form-data" action="<?php echo base_url($formPath); ?>" data-parsley-validate>
            <div class="modal-body">

                <div class = "form-group row">
                    <div class = "col-xs-12 col-sm-6 col-md-6">
                        <input type="text" class="form-control" placeholder="<?php echo lang('cost_placeholder_name') ?>" id="cost_name" name="cost_name" value="<?php echo!empty($edit_record[0]['cost_name']) ? $edit_record[0]['cost_name'] : '' ?>" required>
                        <span id="cost_name_error" class="alert-danger"></span>
                    </div>
                    <div class ="col-xs-12 col-sm-6 col-md-6">
                        <input type="text" readonly class="form-control" placeholder="P###" id="cost_code" name="cost_code" value="<?php echo!empty($edit_record[0]['project_code']) ? $edit_record[0]['project_code'] : rand() ?>">
                        <span id="task_name_error" class="alert-danger"></span>
                    </div>
                </div>
                <div class = "form-group row">
                    <div class ="col-xs-12 col-sm-6 col-md-6">
                        <select tabindex="-1" id="task_id"  name="task_id" class="chosen-select" data-placeholder="Choose a Task" required>
                            <option value=""><?php echo lang('cost_placeholder_task'); ?></option>
                            <?php
                            if (!empty($tasks)) {
                                foreach ($tasks as $row) {
                                    ?>
                                    <option  value="<?php echo $row['task_id'] ?>" <?php echo ($edit_record[0]['task_id'] == $row['task_id']) ? 'selected' : ''; ?>><?php echo ucfirst($row['task_name']); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <span id="task_error" class="alert-danger"></span>
                    </div>
                    <div class ="col-xs-12 col-sm-6 col-md-6">
                        <input type="text" class="form-control" placeholder="<?php echo lang('cost_placeholder_createddate') ?>" id="created_date" name="created_date" value="<?php echo!empty($edit_record[0]['created_date']) ? date('Y-m-d', strtotime($edit_record[0]['created_date'])) : date('Y-m-d') ?>" readonly required>
                        <span id="task_name_error" class="alert-danger"></span>
                    </div>
                </div>
                <div class = "form-group row">
                    <div class="col-xs-12" "col-sm-6" "col-md-6">
                        <select tabindex="-1" id="user_id"  name="user_id" class="chosen-select" data-placeholder="Choose a user" required>
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
                    <div class = "col-sm-6 date" >
                        <input type="text" value="<?php echo!empty($edit_record[0]['start_date']) ? $edit_record[0]['start_date'] : '' ?>" class="form-control" placeholder="<?php echo lang('start_date') ?>" id="start_date" name="start_date" data-date-format="yyyy-mm-dd">
                        <span id="startdate_error" class="alert-danger"></span>
                    </div>
                </div>

                <div class = "form-group row">
                    <div class ="col-xs-12 col-sm-6 col-md-6">
                        <select class="form-control" name="cost_type" id="cost_type" required>
                            <option value="Finance">Finance</option>
                            <option value="Commission">Commission</option>
                            <option value="Tax">Tax</option>
                            <option value="Design">Design</option>
                        </select>
                        <span id="cost_type_error" class="alert-danger"></span>
                    </div>
                    <div class = "col-xs-12 col-sm-6 col-md-6 date" >
                        <input type="text" required value="<?php echo!empty($edit_record[0]['due_date']) ? $edit_record[0]['due_date'] : '' ?>" class="form-control" placeholder="<?php echo lang('due_date') ?>" id="due_date" name="due_date" data-date-format="yyyy-mm-dd">
                        <span id="duedate_error" class="alert-danger"></span>
                    </div>
                </div>
                <div class = "form-group row">
                    <div class ="col-xs-12 col-sm-6 col-md-6">
                        <input <?php echo ($edit_record[0]['within_project'] == 1) ? 'checked' : ''; ?>  data-toggle="toggle" data-onstyle="success" type="checkbox" id="within_project" name="within_project"  value="<?php echo!empty($edit_record[0]['within_project']) ? $edit_record[0]['within_project'] : 0 ?>">
                        <label class="checkbox-inline">
                            <?php echo lang('cost_placeholder_projectbudget'); ?>
                        </label>
                        <span id="budget_error" class="alert-danger"></span>
                    </div>
                    <div class="col-xs-12" "col-sm-6" "col-md-6">
                        <div class ="form-group row">
                            <div class = "col-sm-12">
                                <select class="form-control" name="status" id="status" required>
                                    <option value="">Status</option>
                                    <option value="0" <?php echo ($edit_record[0]['status'] == 0) ? 'selected' : ''; ?> >Unpaid</option>
                                    <option value="1" <?php echo ($edit_record[0]['status'] == 1) ? 'selected' : ''; ?>>Paid</option>
                                </select>
                            </div>

                        </div>
                    </div>

                </div>
                <div class = "form-group row">
                    <div class ="col-xs-12 col-sm-6 col-md-6">
                        <input type="text" required value="<?php echo!empty($edit_record[0]['ammount']) ? $edit_record[0]['ammount'] : '' ?>" data-parsley-type="number" class="form-control" placeholder="<?php echo lang('cost_placeholder_amount') ?>" id="amount" name="amount" required pattern="/^\d{0,8}(\.\d{0,2})?$/" data-parsley-pattern="/^\d{0,8}(\.\d{0,2})?$/">
                        <span id="amount_error" class="alert-danger"></span>
                    </div>
                    <div class ="col-xs-12 col-sm-6 col-md-6">
                        <select class="form-control" name="product_id" id="product_id" required>
                            <option value="1">CRM</option>
                            <option value="2">PMS</option>
                            <option value="3">CMS</option>
                            <option value="4">HMS</option>
                        </select>
                        <span id="product_error" class="alert-danger"></span>
                    </div>
                </div>


                <div class = "form-group row">
                    <div class ="col-xs-12 col-sm-6 col-md-6">
                        <input <?php echo ($edit_record[0]['expense_supplier'] == 1) ? 'checked' : ''; ?> data-toggle="toggle" data-onstyle="success" type="checkbox" name="expense_supplier" id="expense_supplier" value="<?php echo!empty($edit_record[0]['expense_supplier']) ? $edit_record[0]['expense_supplier'] : 0 ?>">
                        <label class="checkbox-inline"><?php echo lang('cost_placeholder_expense'); ?></label>  
                        <span id="expense_supplier_error" class="alert-danger"></span>
                    </div>
                    <div class ="col-xs-12 col-sm-6 col-md-6">
                        <select tabindex="-1" id="supplier_id"  name="supplier_id" class="chosen-select" data-placeholder="Choose a Supplier" required>
                            <option value=""><?php echo lang('cost_placeholder_supplier'); ?></option>
                            <?php
                            if (!empty($supplier)) {
                                foreach ($supplier as $row) {
                                    ?>
                                    <option  value="<?php echo $row['supplier_id'] ?>" <?php echo ($edit_record[0]['supplier_id'] == $row['supplier_id']) ? 'selected' : ''; ?>><?php echo ucfirst($row['supplier_name']); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>

                        <span id="product_error" class="alert-danger"></span>
                    </div>

                </div>
                <div class ="form-group row">
                    <div class = "col-sm-12">
                        <textarea  class="form-control" rows="4" placeholder="<?php echo lang('cost_placeholder_desc') ?>" name="description" id="description" required><?php echo!empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?></textarea>
                    </div>
                </div>
                <div class ="form-group row">
                    <div class ="col-xs-12 col-sm-6 col-md-6">
                        <div class="mediaGalleryDiv">

                            <button type="button" name="gallery" id="gallery-btn" data-href="<?php echo $url; ?>"  class="btn btn-primary"><?php echo lang('cost_placeholder_uploadlib') ?></button>
                            <div class="mediaGalleryImg">

                            </div> 

                        </div>                </div>
                    <div class ="col-xs-12 col-sm-6 col-md-6">
                        <input type="file" name="cost_files[]" id="cost_files" class="form-control" multiple>
                    </div>
                </div>
                <div class ="form-group row">
                    <div class = "col-sm-12">
                        <ul>
                            <?php
                            if (count($cost_files) > 0) {
                                foreach ($cost_files as $files) {
                                    $path = $files['file_path'];
                                    $name = $files['file_name'];
                                    ?>
                                    <?php if (file_exists($path . '/' . $name)) { ?>
                                        <li id="img_<?php echo $files['cost_file_id']; ?>">
                                            <a href='<?php echo base_url($project_view . '/download/' . $files['cost_file_id']); ?>' target="_blank">
                                                <?php echo $files['file_name']; ?>
                                            </a>
                                            <?php if ($files['upload_status'] == 0) {
                                                ?>
                                                <a class="btn delimg" href="javascript:;" data-id="img_<?php echo $files['cost_file_id']; ?>" data-href="<?php echo base_url($project_view . '/deleteImage/' . $files['cost_file_id']); ?>"><i class="fa fa-remove redcol"></i></a>
                                            <?php } ?>
                                        </li>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div>

                </div>


                <div class = "modal-footer">
                    <center>
                        <input type = "hidden" id = "cost_id" name = "cost_id" value = "<?php echo!empty($edit_record[0]['cost_id']) ? $edit_record[0]['cost_id'] : '' ?>">

                        <input type = "submit" class = "btn btn-info" name = "submit_btn" id = "submit_btn" value = "Save" />

                        <input type = "button" style = "display:none" class = "btn btn-info remove_btn" name = "remove" id = "remove_btn" value = "Remove" /></center>
                </div>
        </form>
    </div>
</div><!-- /.modal-dialog -->
<div class="modal fade modal-image" id="modalGallery" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onClick="$('#modalGallery').modal('hide');" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Uploads</h4>
            </div>
            <div class="modal-body" id="modbdy">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onClick="$('#modalGallery').modal('hide');">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="<?php echo base_url() ?>uploads/dist/js/bootstrap-datepicker.js"></script> 
<script src="<?php echo base_url() ?>uploads/custom/js/chosen.jquery.js"></script>
<script src="<?php echo base_url() ?>uploads/custom/js/projectmanagement/parsley.js"></script>
<!--<script src="<?php echo base_url() ?>uploads/custom/js/bootstrap-toggle.js"></script>-->
<script>
                    $('.chosen-select').chosen();
                    $('.chosen-select-deselect').chosen({allow_single_deselect: true});
                    $('#start_date').datepicker({
                        autoclose: true
                    }).on('changeDate', function (selected) {
                        startDate = new Date(selected.date.valueOf());
                        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                        $('#due_date').datepicker('setStartDate', startDate);
                    });
                    $('#due_date')
                            .datepicker({autoclose: true
                            });</script>
<script>
    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('costModel').modal('hide');
        $('#modalGallery').modal('show');
    });
    $('.delimg').on('click', function () {
        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');
        var delete_meg = "<?php echo $this->lang->line('confirm_delete_item'); ?>";

        BootstrapDialog.show(
                {
                    title: '<?php echo $this->lang->line('Information'); ?>',
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