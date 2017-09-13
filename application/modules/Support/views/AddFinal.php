<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'saveTicketData';
$formPath = $project_view . '/' . $formAction;
//echo $project_view;die;
?>    
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><div class="title">Add Ticket</div></h4>
        </div>
        <form id="from-model" method="post" enctype="multipart/form-data" action="<?php echo base_url($formPath); ?>" data-parsley-validate>
            <div class="modal-body">
				<div class = "form-group row">
                    <div class="col-sm-6">
                        <select tabindex="-1" id="user_id"  name="user_id" class="chosen-select" data-placeholder="Choose a user" >
                            <option value=""><?php echo lang('sel_client'); ?></option>
                            <?php
                            if (!empty($res_user)) {
                                foreach ($res_user as $row) {
                                    ?>
                                    <option  value="<?php echo $row['login_id'] ?>"><?php echo ucfirst($row['firstname']) . ' ' . $row['lastname'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                   <div class="col-sm-6">
                        <select tabindex="-1" id="user_id"  name="user_id" class="chosen-select" data-placeholder="Choose a user" >
                            <option value=""><?php echo lang('sel_cont'); ?></option>
                            <?php
                            if (!empty($res_user)) {
                                foreach ($res_user as $row) {
                                    ?>
                                    <option  value="<?php echo $row['login_id'] ?>"><?php echo ucfirst($row['firstname']) . ' ' . $row['lastname'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
				<div class = "form-group row">
                    <div class = "col-sm-12">
                       <input type="text" class="form-control" placeholder="<?php echo lang('ticket_sub') ?>" id="project_name" name="cost_name"  required>
                        <span id="cost_name_error" class="alert-danger"></span>
                    </div>
                </div>
				<div class = "form-group row">
                    <div class = "col-sm-6">
                        <select class="form-control" name="cost_type" id="cost_type" required>
                            <option value="">Product Or Service</option>
							<option value="Finance">Finance</option>
                            <option value="Commission">Commission</option>
                            <option value="Tax">Tax</option>
                            <option value="Design">Design</option>
                        </select>
                        <span id="cost_type_error" class="alert-danger"></span>
                    </div>
                   <div class = "col-sm-6">
                        <select class="form-control" name="cost_type" id="cost_type" required>
						    <option value="">Type</option>
                            <option value="Finance">Finance</option>
                            <option value="Commission">Commission</option>
                            <option value="Tax">Tax</option>
                            <option value="Design">Design</option>
                        </select>
                        <span id="cost_type_error" class="alert-danger"></span>
                    </div>
                </div>
				<div class = "form-group row">
                    <div class = "col-sm-6">
                        <select class="form-control" name="cost_type" id="cost_type" required>
                            <option value="">Status</option>
							<option value="Finance">Finance</option>
                            <option value="Commission">Commission</option>
                            <option value="Tax">Tax</option>
                            <option value="Design">Design</option>
                        </select>
                        <span id="cost_type_error" class="alert-danger"></span>
                    </div>
                   <div class = "col-sm-6">
                        <select class="form-control" name="cost_type" id="cost_type" required>
                            <option value="">Priority</option>
							<option value="Finance">Finance</option>
                            <option value="Commission">Commission</option>
                            <option value="Tax">Tax</option>
                            <option value="Design">Design</option>
                        </select>
                        <span id="cost_type_error" class="alert-danger"></span>
                    </div>
                </div>
				<div class ="form-group row">
                <div class = "col-sm-12">
                    <textarea  class="form-control" rows="4" placeholder="<?php echo lang('cost_placeholder_desc') ?>" name="description" id="description" required><?php echo!empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?></textarea>
                </div>
                </div>
				
				<div class="modal-header">
					<h4 class="modal-title"><div class="title">Assigned to</div></h4>
				</div>
				<div class = "form-group row">
                    <div class="col-sm-6">
                        <select tabindex="-1" id="user_id"  name="user_id" class="chosen-select" data-placeholder="Choose a user" >
                            <option value=""><?php echo lang('sup_team'); ?></option>
                            <?php
                            if (!empty($res_user)) {
                                foreach ($res_user as $row) {
                                    ?>
                                    <option  value="<?php echo $row['login_id'] ?>"><?php echo ucfirst($row['firstname']) . ' ' . $row['lastname'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                   <div class="col-sm-6">
                        <select tabindex="-1" id="user_id"  name="user_id" class="chosen-select" data-placeholder="Choose a user">
                            <option value=""><?php echo lang('sup_user'); ?></option>
                            <?php
                            if (!empty($res_user)) {
                                foreach ($res_user as $row) {
                                    ?>
                                    <option  value="<?php echo $row['login_id'] ?>"><?php echo ucfirst($row['firstname']) . ' ' . $row['lastname'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
				<div class ="form-group row">
				<div class="col-sm-6">
                       <div class="form-group">
                            <input  data-toggle="toggle" data-onstyle="success" type="checkbox" id="within_project" name="within_project" value="1" >
                            <label for="client_notification">
                                <?php echo lang('client_notification'); ?>
                            </label>
                            <!--                        <div class="btn-group btn-toggle">
                                                        <button class="btn btn-xs btn-default">ON</button>
                                                        <button class="btn btn-xs btn-primary active">OFF</button>
                                                    </div>-->
                            <!--                        <label>Within Project Budget?</label>-->
                        </div>
                    </div>
					<div class="col-sm-6">
                       <div class="form-group">
                            <input  data-toggle="toggle" data-onstyle="success" type="checkbox" id="within_project" name="within_project" value="1" >
                            <label for="notify_agent">
                                <?php echo lang('notify_agent'); ?>
                            </label>
                            <!--                        <div class="btn-group btn-toggle">
                                                        <button class="btn btn-xs btn-default">ON</button>
                                                        <button class="btn btn-xs btn-primary active">OFF</button>
                                                    </div>-->
                            <!--                        <label>Within Project Budget?</label>-->
                        </div>
                    </div>
				</div>
				<div class="modal-footer">

                <div class="text-center">
                    <input type="submit" class="btn btn-green" name="submit_btn" id="submit_btn" value="Create Ticket" />
					 <label>OR</label>
					 <input type="submit" class="btn btn-green" name="submit_btn" id="submit_btn" value="Create another Ticket" />
                </div>
                <div class="clr"> </div>
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
            });

</script>
<script>
    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('costModel').modal('hide');
        $('#modalGallery').modal('show');
    });
</script>
