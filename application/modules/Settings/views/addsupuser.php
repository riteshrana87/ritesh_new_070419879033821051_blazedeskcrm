<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$TaskAction = 'checkout';
$taskPath = 'Settings/' . $TaskAction;
?>
<div class="modal-dialog modal-lg"> 
    <form id="paymentfrm1" method="post" enctype="multipart/form-data" action="" data-parsley-validate>
		<input type="hidden" name="setup_id" id="setup_id" value="<?php echo $setup[0]['setup_id'];?>"/>
    <!-- Modal content-->
   <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>
					<?php if($project_view=='adddata'){
						echo lang('add_new_sup');
					}else{
						echo lang('remove_sup');
						}
						?>
					</b>
				</h4>
              </div>
              <div class="modal-body">
               	<div class="row"><div class="col-md-6 form-group">
                	<input type="text" data-parsley-pattern="^\d{0,9}?$" name="no_of_user" id="no_of_user" required class="form-control support_user_new" placeholder="<?php echo lang('no_of_user');?>">
                	<input type="hidden" name="support_amount" id="support_amount" value=""/>
                </div>
                <div class="col-md-6 form-group">
                	<input type="text" name="current_user" id="current_user" class="form-control support_user" value="<?php echo $setup[0]['support_user'];?>" placeholder="<?php echo $setup[0]['support_user'];?> <?php echo lang('current_user');?>" readonly>
                </div>
                <div class="col-md-6 form-group">
                	<p><?= lang('new_mon_price_crm');?>&nbsp;<span><span id="new_add_user"></span> | +$<span id="current_user_price1"></span></span></p>
                </div>
                <div class="col-md-6 form-group">
					<p><?= lang('cur_mon_price_crm');?>&nbsp;<span><span id="current_user_price"></span></p>
                </div></div>
              </div>
              <div class="modal-footer">
                <div class="text-center">
                <p> <?= lang('bill_msg');?></p>
                <input type="submit" class="btn btn-lg btn-green btn-theme submit-button" 
                name="sub_form2" id="<?php if($project_view=='adddata'){
					echo 'sub_form';
					}else{
					echo 'remove_form';	
						}?>" value="<?php if($project_view=='adddata'){
					echo lang('add_user');
					}else{
					echo lang('remove_user');	
						}?>">
                  
                </div>
              </div>
            </div>
            </form>
</div>
<div class="modal in" id="add_contact_data" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php
            $contact_path='Settings/checkout_support';
            $attributes = array("name" => "frm_payment_data", "id" => "frm_payment_data1", 'data-parsley-validate' => "");
            echo form_open_multipart($contact_path, $attributes);
            ?>
            <input type="hidden" name="setup_id" id="setup_id" value="<?php echo $setup[0]['setup_id'];?>"/>
            <input type="hidden" name="cust_id" id="cust_id" value="<?php if(isset($setup[0]['cust_id'])){echo $setup[0]['cust_id'];}?>"/>
            <input type="hidden" name="email" id="email" value="<?php if(isset($bill[0]['email']) && $bill[0]['email']!=''){echo $bill[0]['email'];}else{echo $setup[0]['email'];}?>"/>
            <input type="hidden" name="quantity" id="quantity" value=""/>
            <input type="hidden" name="type" id="type" value="<?php if($project_view=='adddata'){
					echo 'add';
					}else{
					echo 'remove';	
						}?>"/>
      <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h4 class="modal-title">
          <div class="modelTaskTitle"> <?= $this->lang->line('card_detail'); ?> </div>
        </h4>
      </div>
      <div class="modal-body">
         <div class = " row contacts" id="add_contact">
                            
                            <div class = "col-lg-12 col-xs-12 col-sm-12">
                                <div class="col-lg-12 col-xs-12 col-sm-12">
									<div class="col-lg-12 col-xs-12 col-sm-3">
							  <div style="color: red;" id="errorjs"></div>
								<div class="form-group">
									<input value="" type="text" class="form-control card-holder-name" data-parsley-pattern="/^([^0-9]*)$/"  size="20" autocomplete="off" placeholder="<?= lang('card_name');?>" autofocus name="card_name" id="card_name" required>
									
								</div>
							</div>
                     <div class="col-lg-6 col-xs-12 col-sm-3">
                        <div style="color: red;" id="errorjs"></div>
                        <div class="form-group">
                            <input value="" type="text" class="form-control card-number" onkeypress='return validateQty(event);'  size="20" autocomplete="off" placeholder="<?= lang('card_number');?>" autofocus name="card_number" id="card_number" required>
                            
                        </div>
                    </div>
                     
                     <div class="col-lg-6 col-xs-12 col-sm-6">
                        <div class="form-group">
                           <input value="" type="password" onkeypress='return validateQty(event);' class="form-control card-cvc" maxlength="4" placeholder="<?= lang('cvc');?>" autocomplete="off" name="cvc" id="cvc" required>
                            
                        </div>
                    </div>
                     
                     <div class="col-lg-6 col-xs-12 col-sm-6">
                        <div class="form-group">
                           <input value="" type="text" onkeypress='return validateQty(event);' class="form-control card-expiry-month" size="2" placeholder="<?= lang('expm');?>" autocomplete="off" name="exp_month" id="exp_month" required>
                            
                        </div>
                    </div>
                     
                     <div class="col-lg-6 col-xs-12 col-sm-6">
                        <div class="form-group">
                             <input value="" type="text" onkeypress='return validateQty(event);' size="4" class="form-control card-expiry-year" placeholder="<?= lang('expy');?>" autocomplete="off" name="exp_year" id="exp_year" required/>
                            
                        </div>
                    </div>
                 </div>
                            </div>
                        </div>
      </div>
      <div class="modal-footer">
        <center>
            <input type="submit" value="<?= lang('EST_EDIT_SAVE');?>" name="contact_submit_buttons" id="contact_submit_button" class="btn btn-primary">
        </center>
      </div>
      <?php echo form_close(); ?> </div>
  </div>
</div>
 <script type="text/javascript">
// this identifies your website in the createToken call below
//                Stripe.setPublishableKey('pk_test_suxHAAvKSymUCw8lxGk7ZxLs'); 
            // Stripe.setPublishableKey('pk_test_suxHAAvKSymUCw8lxGk7ZxLs');  // DEV1's publishable key
             //Stripe.setPublishableKey(STRIPE_KEY_PK);
             Stripe.setPublishableKey('<?php echo STRIPE_KEY_PK;?>');
            //Stripe.setPublishableKey('pk_live_SOVTnN8wMLfiSgGMrWdCVcsQ');

            function stripeResponseHandler(status, response) {
               // alert('hi');
               //alert(response);return false;
                if (response.error) {
                    // re-enable the submit button
                     $('#contact_submit_button').prop('disabled', false);
                    $('.submit-button').show();
                    // show the errors on the form
                    $("#errorjs").html(response.error.message);
                } else {
					 $('#contact_submit_button').prop('disabled', true);
                    var form$ = $("#frm_payment_data1");
                    // token contains id, last4, and card type
                    var token = response.id;
                 //   alert(token);//return false;
                    // insert the token into the form so it gets submitted to the server
                    form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                    // and submit
                    form$.get(0).submit();
                }
            }

            $(document).ready(function() {
				
					var support_amount=15*$('.support_user').val();
					
					   $('#current_user_price').text(support_amount);
					   $('#current_user_price1').text(support_amount);
					
					$('.support_user_new').keyup(function(){
						 $support_amount=15*$(this).val();
						$('#new_add_user').text($support_amount);
					});
			
                 $('#paymentfrm1').parsley();
                 $('#frm_payment_data1').parsley();
                 var type=$('#type').val();
                	$('#remove_form').click(function(){
						var setup_id=$('#setup_id').val();
					var users=$('#no_of_user').val();
					  if ($('#paymentfrm1').parsley().isValid()) {
						  
						
							$.ajax({
									type: 'POST',
									url: '<?php echo base_url("Settings/count_inactive_user?type=support&setup_id="); ?>'+setup_id,
									success: function(data) {
											
									if(parseInt(users) > parseInt(data)){
										BootstrapDialog.show({
									title: '<?php echo $this->lang->line('Information');?>',
                                   message: "<?php echo lang('not_possible'); ?>"+' '+data+' '+"<?php echo lang('not_possible1'); ?>",
                                    buttons: [{
                                            label: '<?= lang('COMMON_LABEL_CANCEL');?>',
                                            action: function (dialogItself) {
                                                //jQuery('#add_contact' + removeNum).remove();
                                                //add_row_no--;
                                                dialogItself.close();
                                                $('#confirm-id').on('hidden.bs.modal', function () {
													$('body').addClass('modal-open');
												});
                                            }
                                        }]
                                });
										return false;
										}else if(parseInt(users)<=0){
							
							BootstrapDialog.show({
									title: '<?php echo $this->lang->line('Information');?>',
                                    message: "<?php echo lang('not_proper'); ?>",
                                    buttons: [{
                                            label: '<?= lang('COMMON_LABEL_CANCEL');?>',
                                            action: function (dialogItself) {
                                                //jQuery('#add_contact' + removeNum).remove();
                                                //add_row_no--;
                                                dialogItself.close();
                                                $('#confirm-id').on('hidden.bs.modal', function () {
													$('body').addClass('modal-open');
												});
                                            }
                                        }]
                                });
							return false;
							
							}
										else{
											
											$('#add_contact_data').modal('show');	 
											}
									}
								});
							}
							
							});
                 $("#paymentfrm1").submit(function(event) {
					 
					 var type_form=$('#type').val();
				
					 $('#quantity').val($('#no_of_user').val());
					 if(type=='add'){
				   $('#add_contact_data').modal('show');	 
			   }
					return false;
					 });
                $("#frm_payment_data1").submit(function(event) {
                if($('#card_number').val()==''){
                     $("#errorjs").html('Please enter Credit Card Number');
                    return false;
                }
                    var currentYear = (new Date).getFullYear();
                  
                    var currentMonth = (new Date).getMonth() + 1;
                    if($('.card-expiry-month').val()<currentMonth && $('.card-expiry-year').val()==currentYear){
                             $("#errorjs").html('Invalid Month');
                            return false;
                    }
                    if($('.card-expiry-year').val()<currentYear){
                             $("#errorjs").html('Invalid Year');
                            return false;
                    }

                   // $('.submit-button').hide();
                   $('#contact_submit_button').prop('disabled', true);
// createToken returns immediately - the supplied callback submits the form if there are no errors
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val(),
                        quantity:$('#no_of_std').val()
                        }, stripeResponseHandler);

                        return false; // submit from callback
                        });

            });

            function validateQty(event) {
                var key = window.event ? event.keyCode : event.which;
                
                if (event.keyCode == 8 || event.keyCode == 46
                        || event.keyCode == 37 || event.keyCode == 39) {
                    return true;
                }
                if (event.keyCode == 9) {
                    return true;
                }
                else if (key < 48 || key > 57) {
                    return false;
                }
                else
                    return true;
            }
            ;
        </script>

<script>
    //disabled after submit
    $('body').delegate('#task_submit_btn', 'click', function () {
        if ($('#paymentfrm1').parsley().isValid()) {
            $('input[type="submit"]').prop('disabled', true);
            $('#paymentfrm1').submit();
        }
         if ($('#frm_payment_data1').parsley().isValid()) {
            $('input[type="submit"]').prop('disabled', true);
            $('#frm_payment_data1').submit();
        }
    });
    
    //chosen select code
    $('.chosen-select').chosen();
    
    $(document).ready(function () {
     //parsley validation    
        window.Parsley.addValidator('gteq',
        function (value, requirement) {
            return Date.parse($('#end_date input').val()) >= Date.parse($('#start_date input').val());
        }, 32)
        .addMessage('en', 'le', 'This value should be less or equal');

    });

    //toggle button
    $('#reminder').bootstrapToggle();

    
</script> 
