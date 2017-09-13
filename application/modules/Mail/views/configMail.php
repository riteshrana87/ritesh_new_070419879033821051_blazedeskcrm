<?php
/**
 * Created by PhpStorm.
 * User: brijesh.tiwari@c-metric.com
 * Date: 3/9/2016
 * Time: 6:49 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="clr"></div>
<?php echo $this->session->flashdata('msg'); ?>
<div class="clr"></div>

<div class="modal-dialog ">
    <!--    <form role="form" name="frm_addemail" id="frm_addemail" enctype="multipart/form-data"
              action="<?php echo base_url() . 'Mail/mailconfig'; ?>" method="post" data-parsley-validate
              >-->
    <form role="form" name="frm_addemail" id="frm_addemail" enctype="multipart/form-data" method="post" data-parsley-validate >

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title">
                    <div class="modelTitle"><?php echo $headerLbl; ?></div>
                </h4>

            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-xs-12 col-md-12">
                        <label class="  control-label"><?php echo $emailLbl; ?> <span class="viewtimehide">*</span></label>

                        <div class="form-group">
                            <input type="email" name="email" value="<?php
                            if (isset($emailConfigData)) {
                                echo $emailConfigData['email_id'];
                            }
                            ?>" class="form-control" required>

                        </div>
                    </div>

                    <div class="clr"></div>
                </div>

                <div class="form-group row">
                    <div class="col-xs-12 col-md-12">
                        <label class="  control-label"><?php echo $passLbl; ?> <span class="viewtimehide">*</span></label>

                        <div class="form-group">
                            <input type="password" name="password" value="<?php
                            if (isset($emailConfigData)) {
                                echo $emailConfigData['email_pass'];
                            }
                            ?>" class="form-control" required>

                        </div>
                    </div>

                    <div class="clr"></div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-md-12">
                        <label class="  control-label"><?php echo lang('mail_host'); ?> <span class="viewtimehide">*</span></label>

                        <div class="form-group">
                            <input type="text" name="email_server" id="email_server" value="<?php
                            if (isset($emailConfigData)) {
                                echo $emailConfigData['email_server'];
                            }
                            ?>" class="form-control" required>

                        </div>
                    </div>

                    <div class="clr"></div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-md-12">
                        <label class="  control-label"><?php echo lang('mail_port'); ?> <span class="viewtimehide">*</span></label>

                        <div class="form-group">
                            <input type="text" name="email_port" id="email_port" value="<?php
                            if (isset($emailConfigData)) {
                                echo $emailConfigData['email_port'];
                            }
                            ?>" class="form-control" required>

                        </div>
                    </div>

                    <div class="clr"></div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-md-12">
                        <label class="  control-label"><?php echo lang('mail_encryption'); ?><span class="viewtimehide">*</span></label>

                        <div class="form-group">
                            <select class="form-control" name="email_encryption" id="email_encryption" required>
                                <option value="" >Select Encryption Method</option>
                                <option value="SSL" SELECTED="<?php
                                if (isset($emailConfigData)) {
                                    echo $emailConfigData['email_encryption'];
                                }
                                ?>">SSL</option>
                                <option value="TLS" SELECTED="<?php
                                if (isset($emailConfigData)) {
                                    echo $emailConfigData['email_encryption'];
                                }
                                ?>">TLS</option>
                            </select>

                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12 col-md-12">
                            <label class="  control-label"><?php echo lang('smtp_host'); ?> <span class="viewtimehide">*</span></label>

                            <div class="form-group">
                                <p><?php echo lang('mail_ssl_note');?><p>
                                    <input type="text" name="email_smtp" id="email_smtp" value="<?php
                                    if (isset($emailConfigData)) {
                                        echo $emailConfigData['email_smtp'];
                                    }
                                    ?>" class="form-control" required>

                            </div>
                        </div>

                        <div class="clr"></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12 col-md-12">
                            <label class="  control-label"><?php echo lang('smtp_port'); ?> <span class="viewtimehide">*</span></label>

                            <div class="form-group">

                                <input type="text" name="email_smtp_port" id="email_smtp_port" value="<?php
                                if (isset($emailConfigData)) {
                                    echo $emailConfigData['email_smtp_port'];
                                }
                                ?>" class="form-control" required>

                            </div>
                        </div>

                        <div class="clr"></div>
                    </div>
                    <div class="clr"></div>
                </div>

            </div>

            <div class="clr"></div>
            <br/>

            <div class="modal-footer">
                <div class="text-center">
                    <input type="submit" class="btn btn-primary" id="camp_submit_btn" name="action" value="<?php echo $btnLbl; ?>">
                </div>
            </div>

            <div class="clr"></div>
            <br/>
        </div>
    </form>
</div>
<!--</div>-->

<script>

    $(document).ready(function () {

        $('body').on('click', '#camp_submit_btn', function (event) {

            if ($('#frm_addemail').parsley().isValid()) {

                //$("#camp_submit_btn").on("click", function (event) {

                event.preventDefault();
                //$('input[type="submit"]').prop('disabled', true);
                var formData = $("#frm_addemail").serialize();

                $.ajax({
                    url: "<?php echo base_url('Mail/validateMailConfig'); ?>",
                    data: formData,
                    type: "POST",
                    beforeSend: function () {
                        $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> Please wait...'});
                    },
                    success: function (result)
                    {
                        $.unblockUI();
                        var arr = JSON.parse(result);
                        BootstrapDialog.alert(arr.message);
                        if (arr.status) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                });
                return false;
                //  });
            }
        });

    });
</script>
