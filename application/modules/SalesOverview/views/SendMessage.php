<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!empty($editRecord)) {
    $formAction = 'updateNoteRecord';
} else {
    $formAction = 'insertMessage';
}
$path = base_url() . "SalesOverview/" . $formAction;
?>
<div class="modal-dialog ">
    <?php
    $attributes = array("name" => "add_note", "id" => "add_note", 'data-parsley-validate' => "");
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


            <div class ="form-group row">
                <div class="col-sm-6">
                    <select name="user_id[]" multiple="true" data-placeholder="<?php echo lang('SELECT_USER'); ?> *" class="form-control chosen-select" id="user_id" required data-parsley-errors-container="#company-errors">
                        <option value=""></option>
                        <?php
                        foreach ($user_data as $user) {
                            ?>
                            <option value="<?php echo $user['login_id']; ?>"><?php echo $user['firstname'] . "&nbsp" . $user['lastname']; ?></option>
                        <?php }
                        ?>
                    </select>
                    <div id="company-errors"></div>

                </div>


            </div>


            <div class="form-group row">
                <div class="col-sm-12">
                    <label><?= $this->lang->line('MESSAGE_SUBJECT') ?> : *</label>
                    <div class="form-group">
                        <input type="text" class="form-control" required  name="note_subject" id="note_subject"  placeholder="<?php echo lang('NOTE_SUBJECT'); ?>" value="<?= !empty($editRecord[0]['note_subject']) ? $editRecord[0]['note_subject'] : '' ?>"/>
                    </div>
                    <label id="QtChar"> </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <label><?= $this->lang->line('MESSAGE_DESCRIPTION') ?> : *</label>
                    <!--onkeyup="checkTextareaWord()" -->
                    <div class="form-group">

                        <textarea class="form-control"   rows="4" name="note_description" id="note_description" placeholder="<?php echo lang('NOTE_DESCRIPTION'); ?>"><?= !empty($editRecord[0]['note_description']) ? $editRecord[0]['note_description'] : '' ?></textarea></div>
                    <ul class="parsley-errors-list filled" id="termsError" ><li class="parsley-required">This value is required.</li></ul>
                    <label id="QtChar"> </label>

                </div>
            </div>

            <input type="text" id="redirect_link" name="redirect_link"  hidden="" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
            <input type="hidden" name="note_id" id="note_id" value="<?= !empty($note_id) ? $note_id : '' ?>  " />
            <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>"> 
        </div>
        <div class="modal-footer">
            <center> <input type="submit" class="btn btn-primary" id="contact_submit_btn" value="<?= $submit_button_title ?>"></center>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script>
    $(document).ready(function ()
    {
        $('#add_note').parsley();
        $('#note_description').summernote({
            height: 150, //set editable area's height
            codemirror: {// codemirror options
                theme: 'monokai'
            },
            focus: true,
            onImageUpload: function (files, editor, $editable) {
                sendFile(files[0], editor, $editable);
            }
        });
        function sendFile(file, editor, welEditable) {
            data = new FormData();
            data.append("file", file);
            $.ajax({
                url: "<?php echo base_url('Contact/uploadFromEditor'); ?>",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data) {
                    editor.insertImage(welEditable, data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                }
            });
        }
        $("#termsError").css("display", "none");

    });

    $('body').delegate('#add_note', 'submit', function ()
    {

        var code1 = $('#note_description').code();
        var wys = $('.note-editable').html();
        var value = wys.replace(/(<([^>]+)>)/ig, "");
        var final_value = value.replace(/&nbsp;/g, '');

        final_value = final_value.replace(/^\s+/g, '');

        if (final_value != '')
        {

            var code1 = $('#description').code();
            if (code1 !== '' && code1 !== '<p><br></p>' && code1 !== '<br>')
            {
                $("#termsError").css("display", "none");
                response = true;
                return true;
            }
        } else {
            $("#termsError").css("display", "block");
            response = false;
            return false;
        }


        if ($('#add_note').parsley().isValid())
        {
            $('input[type="submit"]').prop('disabled', true);
            $('#add_note').submit();
        }
    });

</script>
<script>
    $('.chosen-select').chosen();
    $(function ()
    {
        //$("#QtChar")[0].innerText = "Allowed Character :"+ parseInt($("#note_description")[0].maxLength);
    });
    function checkTextareaWord()
    {
        $("#QtChar")[0].innerText = "Allowed Character : " + parseInt($("#note_description")[0].maxLength - $("#note_description").val().length);
    }

</script>