<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-md-6 col-md-6">
        <ul class="breadcrumb nobreadcrumb-bg">
           
            <li><?= lang('CHANGE_PASSWORD') ?></li>
        </ul>
    </div>
</div>
<div class="clr"></div>

<div class="col-xs-12 col-md-12">
    <div class="col-xs-6 col-md-6 no-left-pad">
        <h3 class="white-link"><?= lang('CHANGE_PASSWORD') ?></h3> 
    </div>

</div>
<div class="clr"></div>
<div class="row">


    <div class="col-xs-12 col-md-12">
        <?php echo $this->session->flashdata('msg'); ?>

        <div class="whitebox pad-10">
       <div class="row">


    <div class="col-xs-12 col-md-12">
        <form action="<?php echo base_url();?>MyProfile/updatePassword" name="update_password" id="update_password" data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate>
                     	
                 <div class="row"> <div class="col-xs-12 col-md-4">  <div class="row">
                       	<div class="col-xs-12 col-md-12 col-sm-6">			
                            <div class="form-group">
                                <label for="subject"><?php echo lang('PASSWORD'); ?>*</label>
                                <input class="form-control" autocomplete="off" id="password" name="password" value=""  data-parsley-trigger="change" placeholder="<?php echo lang('PASSWORD'); ?>" type="password" required data-parsley-minlength="6">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12 col-sm-6">			
                            <div class="form-group">
                                <label for="subject"><?php echo lang('CONFIRM_PASSWORD'); ?>*</label>
                                <input class="form-control" required name="cpassword" id="cpassword" data-parsley-eq="#password" placeholder="<?php echo lang('CONFIRM_PASSWORD'); ?>" data-parsley-trigger="change" type="password" data-parsley-equalto="#password" data-parsley-minlength="6">

                            </div>
                        </div>
                        <div class="clr"></div>	
                    </div></div><div class="clr"></div>	</div>
                    
                   							
                    <div> 
                        <input type="button" class="btn btn-green" onclick="change_password()"  name="submit_btn" value="<?php echo lang('UPDATE');?>">
                    </div>	
              
            </form>
             </div>  
        <div class="clr"></div>
    </div>
        </div>  
        <div class="clr"></div>
    </div>
    <div class="clr"></div>

</div>
<script>
    
function change_password()
{
    var $form = $('#update_password');
    $form.parsley().validate();
    if ($('#update_password').parsley().isValid()) 
    {
            $('input[type="button"]').prop('disabled', true);
        var delete_meg ="<?php echo lang('CONFIRM_CHANGE_PASSWORD');?>";
        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: delete_meg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {
                        $('#update_password').submit();
                        dialog.close();
                    }
                }]
            });

   
        }
}
$('#password').attr("autocomplete", "off");
setTimeout('$("#password").val("");', 2000);

</script>
<script>
    $(document).ready(function() {
        $("#update_password").parsley();
    });
</script>
