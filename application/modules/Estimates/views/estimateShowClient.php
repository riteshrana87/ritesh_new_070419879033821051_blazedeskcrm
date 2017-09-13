<?php 
if($ShowData == 'RelatedToCompany')
 {
  $SelectedValue = 'selected=""';
  $ClientInfo = "" ;
 }
else
 {
  $SelectedValue = "";
  $ClientInfo = "show" ;
 }
?>
<select name="recipient_id[]" id="recipient_id" multiple="" class="form-control chosen-select" data-placeholder="<?php echo lang('EST_SHOW_CLIENT_SELECT_RECIPIENTS'); ?>" required>
 <option value=""></option>
 <?php //Remove if condition and show client related with company if(isset($ClientInfo) && $ClientInfo == 'show'){?>
  <optgroup label="<?php echo lang('client'); ?>">
  <?php
  $client_id = $client_data;
  foreach ($client_info as $client) {
   if($client_id == $client['prospect_id']){
   ?>
   <option selected value="client_<?php echo $client['prospect_id']; ?>"><?php echo $client['prospect_name']; ?></option>
  <?php }else{?>
    <option value="client_<?php echo $client['prospect_id']; ?>" <?php echo $SelectedValue;?>><?php echo $client['prospect_name']; ?></option>
   <?php }} ?>
  </optgroup>
 <?php //}?>
 <optgroup label="<?php echo lang('COMM_CONTACT'); ?>">
  <?php
  $contact_id = $contact_data;
  foreach ($contact_info as $contact) {
   if($contact_id == $contact['contact_id']){
   ?>
   <option selected value="contact_<?php echo $contact['contact_id']; ?>" <?php echo $SelectedValue;?>><?php echo $contact['contact_name']; ?></option>
  <?php }else{?>
  <option value="contact_<?php echo $contact['contact_id']; ?>" <?php echo $SelectedValue;?>><?php echo $contact['contact_name']; ?></option>

   <?php }} ?>
 </optgroup>
</select>