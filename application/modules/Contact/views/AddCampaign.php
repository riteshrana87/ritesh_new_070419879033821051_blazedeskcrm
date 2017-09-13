<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!empty($editRecord)){
    $formAction = 'updateCampaigndata';
}else{
    $formAction = 'insertContactToCampaign';
}
$path = base_url().'Contact/'.$formAction;
?>
  <div class="modal-dialog ">
      <?php $attributes = array("name" => "add_campaign", "id" => "add_campaign", 'data-parsley-validate' => "");
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
                    <div class="col-sm-12">
                        <label><?= $this->lang->line('campaign_name') ?> : *</label>
                        <select multiple="true" data-placeholder="<?php echo lang('SELECT_CAMPAIGN');?>" class="form-control chosen-select" name="campaign_id[]" id="campaign_id" required data-parsley-errors-container="#campaign-errors">
                            <option value=""></option>
                        <?php
                        foreach ($campaign_data as $campaign)
                        { ?>
                            <option value="<?php echo $campaign['campaign_id']; ?>" <?php
                                    if (!empty($opportunity_product_data) && in_array($campaign['campaign_id'], $opportunity_product_data)) {
                                        echo 'selected="selected"';
                                    }
                                    ?>><?php echo $campaign['campaign_name']; ?></option>
                       <?php  }
                        ?>    
                        </select>
                        <label id="campaign-errors"></label>
                    </div>
                </div>
        <?php
        $redirect_link = $_SERVER['HTTP_REFERER'];
        
        ?>   
          <input type="text" id="redirect_link" name="redirect_link"  hidden="" value="<?php echo $redirect_link;?>">
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
        $('#add_campaign').parsley();
        
    });
    $('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    $('#campaign_id').trigger('chosen:updated');
</script>
