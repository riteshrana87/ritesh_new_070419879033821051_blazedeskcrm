<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$formAction = 'insertdata';
$path = $estimate_view . '/' . $formAction;
?>    

<?php
    $attributes = array("name" => "frmestimate", "id" => "frmestimate");
    echo form_open_multipart($path);
    ?>

    <div class="col-lg-2 col-md-2 col-xs-4">

        <div class="link-style1"><button type="button" class="btn btn-info btn-lg add_estimate" data-toggle="modal" data-target="#estimate"><?= $this->lang->line('create_new_estimate') ?></button>

            <!-- Modal New Opportunity-->
            <div id="estimate" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><div class="modelTitle"></div></h4>
                        </div>

                        <div class="modal-body">

                            <div class = "form-group row">
                                <div class = "col-sm-6 col-xs-12 col-md-6 col-lg-6">

                                    <input type="text" id="estimate_id" name="estimate_id"  hidden="">
                                    <input type="text" class="form-control" placeholder="<?= lang('company_name') ?>" id="company_name" name="company_name"> 
                                    <span id="company_name_error" class="alert-danger"></span>
                                </div>
                                <?php $six_digit_random_number = mt_rand(100000, 999999);
                                $est_auto_id = 'C' . $six_digit_random_number;
                                ?>
                                <div class = "col-sm-6 col-xs-12 col-md-6 col-lg-6">
                                    <input type="text" class="form-control" placeholder="<?php echo $est_auto_id; ?>" id="estimate_auto_id" name="estimate_auto_id">
                                </div>
                            </div>

                            <div class = "form-group row">
                                <div class = "col-sm-6 col-xs-12 col-md-6 col-lg-6">
                                    <input type="text" class="form-control" placeholder="<?= lang('subject') ?>" id="subject" name="subject"> 
                                </div>
                                <div class = "col-sm-6 col-xs-12 col-md-6 col-lg-6">
                                    <input type="text" class="form-control" placeholder="<?= lang('value') ?>" id="subject" name="value"> 
                                </div>
                            </div>
                            <div class = "form-group row">
                                <div class = "col-sm-6 col-xs-12 col-md-6 col-lg-6">
                                <div class="input-group date">
                                    <input type="text" class="form-control" placeholder="<?= $this->lang->line('send_date') ?>" id="creation_date" name="creation_date"  onkeydown="return false">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                                <div class = "col-sm-6 col-xs-12 col-md-6 col-lg-6">
                                <div class="input-group date">
                                    <input type="text" class="form-control" placeholder="<?= $this->lang->line('due_date') ?>" id="creation_date" name="creation_date"  onkeydown="return false">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            </div>
                            <div class="modal-footer">
                                <center><input type="submit" class="btn btn-info" name="estimate_submit" id="estimate_submit" value="Submit" /></center>
                            </div>
                        </div>

                    </div>
                </div> 
            </div>
            <div class="clr"></div>

        </div>
    </div>
<?php echo form_close(); ?>
