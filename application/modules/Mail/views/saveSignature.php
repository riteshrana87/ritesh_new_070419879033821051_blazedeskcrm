<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" <?php echo lang('close'); ?> class="close" onClick="$('#signaturemodal').modal('hide');" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo lang('mail_signature'); ?></h4>
        </div>
        <form method="post" action="<?php echo base_url('Mail/SaveSignature'); ?>">
            <div class="modal-body" id="modbdy">
                <textarea name="signarea" id="signarea"><?php echo $email_signature; ?></textarea>
                <input type="hidden" name="mailtype" value="<?php echo isset($mailtype) ? $mailtype : ''; ?>">
                <input type="hidden" name="uid" value="<?php echo isset($uid) ? $uid : ''; ?>">
                <input type="hidden" name="url" id="url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
            </div>
            <div class="modal-footer">
                <input type="submit" id="submitbtn" name="submitbn" value="Save Signature" class="btn btn-success">
                <button type="button" class="btn btn-default" onClick="$('#ajaxModal').modal('hide');" title="<?php echo lang('COMMON_LABEL_CANCEL'); ?>"><?php echo lang('COMMON_LABEL_CANCEL'); ?></button>
            </div>
        </form>
    </div><!-- /.modal-content -->
</div>
<script>
    $('#signarea').summernote({
        disableDragAndDrop: true,
        height: 150, //set editable area's height
        codemirror: {// codemirror options
            theme: 'monokai'
        }
    });
</script>