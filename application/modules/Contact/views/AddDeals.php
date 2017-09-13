<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!empty($editRecord)){
    $formAction = 'updateNoteRecord';
}else{
    $formAction = 'insertNote';
}
$path = $sales_view.'/Contact/'.$formAction;
?>
  <div class="modal-dialog modal-lg">
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
            
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('NOTE_DESCRIPTION') ?> : *</label>
                        <textarea class="form-control" required="" name="note_description" id="note_description" placeholder="<?php echo lang('NOTE_DESCRIPTION');?>"><?=!empty($editRecord[0]['note_description'])?$editRecord[0]['note_description']:''?></textarea>
                    </div>
                </div>
                 
            <input type="text" id="redirect_link" name="redirect_link"  hidden="" value="<?php echo $_SERVER['HTTP_REFERER'];?>">
            <input type="hidden" name="contact_id" id="contact_id" value="<?=!empty($contact_id)?$contact_id:''?>">
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
    $(document).ready(function () {    $('#add_note').parsley();});
</script>