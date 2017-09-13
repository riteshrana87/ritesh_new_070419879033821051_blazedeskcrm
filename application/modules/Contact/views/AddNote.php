<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!empty($editRecord)){
    $formAction = 'updateNoteRecord';
}else{
    $formAction = 'insertNote';
}
$path = base_url().'Contact/'.$formAction;

?>
  <div class="modal-dialog ">
      <?php $attributes = array("name" => "add_note", "id" => "add_note", 'data-parsley-validate' => "");
echo form_open_multipart($path,$attributes);
?>
  <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="set_label"><?php echo $modal_title;?>
        </h4>
      </div>
      <div class="modal-body">
            
                 <?php 
                if(isset($display_from) && $display_from == 'dashboard_salesoverview')
                { ?>
                    <div class ="form-group row">
                        <div class="col-sm-6">
                            <select name="contact_id" data-placeholder="<?php echo lang('SELECT_USER'); ?> *" class="form-control chosen-select" id="contact_id" required data-parsley-errors-container="#company-errors">
                            <option value=""></option>
                             <?php 
                            foreach ($contact_data as $contact)
                            {?>
                            <option value="<?php echo $contact['contact_id'];?>"><?php echo $contact['contact_name'];?></option>
                           <?php  }    
                            ?>
                        </select>
                        <div id="company-errors"></div>
                            
                        </div>
                        
                       
                    </div>
          
               <?php  }
                ?>
                 <div class="form-group row">
                    <div class="col-sm-12">
                        <label><?= $this->lang->line('NOTE_SUBJECT') ?> : *</label>
                        <div class="form-group">
                            <input type="text" class="form-control" required  name="note_subject" id="note_subject"  placeholder="<?php echo lang('NOTE_SUBJECT');?>" value="<?=!empty($editRecord[0]['note_subject'])?htmlentities($editRecord[0]['note_subject']):''?>"/>
                            </div>
                        <label id="QtChar"> </label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label><?= $this->lang->line('NOTE_DESCRIPTION') ?> : *</label>
                        <!--onkeyup="checkTextareaWord()" -->
                        <div class="form-group">
                           
                            <textarea class="form-control"   rows="4" name="note_description" id="note_description" placeholder="<?php echo lang('NOTE_DESCRIPTION');?>"><?=!empty($editRecord[0]['note_description'])?$editRecord[0]['note_description']:''?></textarea>
                             <ul class="parsley-errors-list filled" id="termsError" ><li class="parsley-required"><?= $this->lang->line('EST_ADD_LABEL_REQUIRED_FIELD') ?>.</li></ul> 
                        </div>
                        <label id="QtChar"> </label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label><?= $this->lang->line('ADD_TO_COMMUNICATION') ?> : </label>
                        
                        <div class="form-group">
                             <input <?php if (!empty($editRecord[0]['add_to_communication'])) { ?>checked="checked"<?php } ?> data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>" class="toggle_add_to_communication" data-toggle="toggle" data-onstyle="success" type="checkbox"  id="add_to_communication" name="add_to_communication"/>
                        </div>
                            
                    </div>
                </div>
                
            <input type="text" id="redirect_link" name="redirect_link"  hidden="" value="<?php echo $_SERVER['HTTP_REFERER'];?>">
            <input type="hidden" name="note_related_id" id="note_related_id" value="<?=!empty($note_related_id)?$note_related_id:''?>">
            <input type="hidden" name="note_id" id="note_id" value="<?=!empty($note_id)?$note_id:''?>  " />
            <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>"> 
      </div>
      <div class="modal-footer">
        <center> <input type="submit" class="btn btn-primary" id="contact_submit_btn" value="<?=$submit_button_title?>"></center>
      </div>
    </div>
  <?php echo form_close(); ?>
  </div>

<script>
    $(document).ready(function ()
    {  
        $('#add_note').parsley();
        $('#note_description').summernote({
            height: 150,   //set editable area's height
            codemirror: { // codemirror options
                theme: 'monokai'
            },
            focus: true
        });
        $("#termsError").css("display", "none");
    });
    
    $('body').delegate('#add_note', 'submit', function () 
    {
		
        var code1 = $('#note_description').code();
        var wys = $('.note-editable').html();
                var value = wys.replace(/(?:&nbsp;|<br>|<p>|<\/p>)/ig, "");
                var final_value = value.replace(/&nbsp;/g,'');
                final_value = final_value .replace(/^\s+/g,'');
               
        if (final_value!='')
        {   

            var code1 = $('#note_description').code();
            if (code1 !== '' && code1 !== '<p><br></p>' && code1 !== '<br>') 
            {
                $("#termsError").css("display", "none");
                response = true;
                return true;
            } 
        }else {
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
 $(function()
    {
       //$("#QtChar")[0].innerText = "Allowed Character :"+ parseInt($("#note_description")[0].maxLength);
    });
    function checkTextareaWord()
    {
       $("#QtChar")[0].innerText = "Allowed Character : "+ parseInt($("#note_description")[0].maxLength - $("#note_description").val().length);	
    }

$('.toggle_add_to_communication').bootstrapToggle();
</script>