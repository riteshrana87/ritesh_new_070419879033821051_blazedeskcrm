<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!empty($editRecord)){
    $formAction = 'updateCampaigndata';
}else{
    $formAction = 'merge_contact';
}
$path = base_url().'Contact/'.$formAction;
?>
  <div class="modal-dialog modal-lg">
      <?php $attributes = array("name" => "merge_contact", "id" => "merge_contact", 'data-parsley-validate' => "");
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
                        <label><?= $this->lang->line('contact_name') ?> : *</label>
                        <select class="form-control chosen-select" name="id_merge_to_contact" id="id_merge_to_contact" required data-parsley-errors-container="#campaign-errors">
                            <option value="">Select Contact</option>
                        <?php
                        foreach ($contact_data as $contact)
                        { 
                            if($contact_id != $contact['contact_id'])
                            {
                            ?>
                            <option value="<?php echo $contact['contact_id']; ?>"><?php echo $contact['contact_name']; ?></option>
                            <?php  }}
                        ?>    
                        </select>
                        <label id="campaign-errors"></label>
                    </div>
                </div>
                 
            <input type="text" id="redirect_link" name="redirect_link"  hidden="" value="<?php echo $_SERVER['HTTP_REFERER'];?>">
            <input type="hidden" name="contact_id" id="contact_id" value="<?=!empty($contact_id)?$contact_id:''?>">
            <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>"> 
      </div>
      <div class="modal-footer">
        <center> <input type="submit" class="btn btn-primary" id="contact_submit_btn" value="<?=$submit_button_title?>"></center>
      </div>
    </div>
  <?php echo form_close(); ?>
  </div>

<script>
    $(document).ready(function () {
        $('#merge_contact').parsley();
        $('.chosen-select').chosen();
    });
</script>
