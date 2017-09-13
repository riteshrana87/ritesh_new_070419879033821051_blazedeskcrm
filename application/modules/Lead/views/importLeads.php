<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$path = $sales_view . '/importLeaddata';
?>
<div class="modal-dialog modal-lg">
    <?php
    $attributes = array("name" => "import_contact", "id" => "import_contact", 'data-parsley-validate' => "");
    echo form_open_multipart($path, $attributes);
    ?>
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="set_label"><?php echo $modal_title; ?>
            </h4>
        </div>
        <div class="modal-body">
            <div class="form-group row">
                <div class="col-sm-6 col-lg-12 form-group">
                    <label class="custom-upload btn btn-blue"><?= $this->lang->line('IMPORT_LEADS') ?> 
                        <input class="btn btn-default btn-file input-group " type="file" name="import_file"  id="import_file" placeholder="Import File" value="" />
                    </label>
                </div>

                <div class="col-sm-6 col-lg-12">
                    <a class="btn btn-green" downlaod target="_blank" href="<?php echo base_url() . "uploads/csv_leads/SAMPLE_CSV_Leads.csv"; ?>"><span class="glyphicon glyphicon-cloud-download"></span>
                        <?= $this->lang->line('SAMPLE_FILE') ?>   
                    </a>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-12"><h4 class="mar_tp0"><code class="colo"><?php echo lang('UPLOAD_NOTE_IMPORTANT'); ?></code></h4>
                    <div class="col-lg-12">
                        <ul class="bd-gen-list">
                            <li><span class="colo"><?php echo lang('UPLOAD_CSV_CONTACT'); ?></span></li>
                           <!-- <li><span class="colo"><?php echo lang('UPLOAD_COMPANY_NAME_MUST_BE_EXISTED'); ?></span></li>-->
                            <li><span class="colo"><?php echo lang('UPLOAD_COUNTRY_NAME_MUST_BE_EXISTED'); ?></span></li>
                           <!-- <li><span class="colo"><?php //echo lang('ESTIMATE_LEAD_WORTH_NOT_BLANK'); ?></span></li>-->
                        </ul></div></div>
            </div>
            <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>"> 
        </div>
        <div class="modal-footer">
            <center> <input type="button" class="btn btn-primary" id="import_contact_btn" onclick="validateCSV();" value="<?= $submit_button_title ?>"></center>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">


    function validateCSV()
    {
        var fname = $('#import_file').val();

        var re = /(\.csv|.xls|.xlsx)$/i;

        if (fname == '')
        {
            var delete_meg = "<?php echo lang('ALERT_IMPORT_CONTACT'); ?>";
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
            $('#import_file').focus();
            return false;
        } else
        {
            if (!re.exec(fname))
            {
                var delete_meg = "<?php echo lang('UPLOAD_CSV_CONTACT'); ?>";
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

                $('#import_file').focus();
                return false;
            } else
            {
                $('#import_contact').submit();
            }

        }

    }
</script>
