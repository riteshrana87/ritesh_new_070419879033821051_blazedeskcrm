<?php
defined ('BASEPATH') OR exit('No direct script access allowed');

// $formAction = 'insertdata';
// $formPath   = $project_status_view . '/' . $formAction;

defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'updatedataStatus':'insertdataStatus'; 
$formPath = $crnt_view.'/'.$formAction;
$selected = "";
if(isset($readonly)){	
	$disable = $readonly['disabled'];
}else{
	$disable = "";
}

?>
<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" title="<?php echo lang('close') ?>" >&times;</button>
            <h4 class="modal-title"><div class="title"><?PHP if($formAction == "insertdataStatus"){ ?><?=$this->lang->line('support_settings_status')?><?php }elseif($formAction == "updatedataStatus" && !isset($readonly)){?><?=$this->lang->line('support_settings_staus_update')?><?php }elseif(isset($readonly)){?><?=$this->lang->line('support_settings_status_view')?><?php } ?></div></h4>
        </div>
        <!--<form id="registration" method="post" enctype="multipart/form-data" action="<?php echo base_url($formPath); ?>" data-parsley-validate>-->
		<?php $attributes = array("name" => "support_settings", "id" => "support_settings", "data-parsley-validate" => "");
			echo form_open_multipart($formPath, $attributes);
		?>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="control-label"><?= lang ('status_name') ?><?php if($disable ==""){?>*<?php }?> : </label>
                        <input type="text" class="form-control" placeholder="<?= lang ('status_name') ?>"
                               id="status_name" name="status_name"
                               value="<?= !empty($editRecord[0]['status_name']) ? $editRecord[0]['status_name'] : '' ?>"
                               required <?php echo $disable; ?> >
                    </div>
                </div>
				<?php if(!isset($readonly)){ ?>
                <div class="form-group row">
                    <div class="col-sm-12 input-group selectcolor">
                        <label class="control-label"><?= lang ('status_color') ?><?php if($disable ==""){?>*<?php }?> : </label>

                        <div class="input-group demo2">
                            <input readonly type="text" data-format="hex" id="status_color" class="form-control"
                                   placeholder="<?= lang ('status_color') ?>" id="status_color" name="status_color"
                                   value="<?= !empty($editRecord[0]['status_color']) ? $editRecord[0]['status_color'] : '' ?>"
                                   required  >
                            <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                        </div>
                    </div>
                </div>
				<?php }else{ ?>
				<div class="selectcolor colorpicker-element">
                        <label class="control-label"><?php echo lang('status_color'); ?><?php if($disable ==""){?>*<?php }?> : </label>

                        <div class="input-group  demo2">
                            <input type="text" disabled="" required=""  value="<?= !empty($editRecord[0]['status_color']) ? $editRecord[0]['status_color'] : '' ?>" name="status_color" placeholder="<?= lang ('status_color') ?>" class="form-control" id="status_color" data-format="hex" readonly data-parsley-id="60">
                            
                        </div>
                    </div>
				<?php } ?>
				<?php if(!isset($readonly)){ ?>
				     <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="control-label"><?= lang ('status_font_icon') ?> <span class="viewtimehide">*</span> </label>
                        <select tabindex="-1" id="status_font_icon" name="status_font_icon"
                            class="form-control chosen-select" required data-placeholder="<?= lang ('status_font_icon') ?>">
                            <option value=""><?php echo lang('comm_select'); ?> <?= lang ('status_font_icon') ?></option>
                            <?php
                            if (!empty($font_awesome_data)) {
                                foreach ($font_awesome_data as $key =>$row) {
                                    ?>

                                    <option <?php if (!empty($editRecord[0]['status_font_icon']) && $editRecord[0]['status_font_icon'] == strtolower($key)) {
                                        echo 'selected="selected"';
                                    } ?>
                                        value="<?= strtolower($key) ?>"><?=$row?> <?=ucfirst($key);?> </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        
                    </div>
                </div>
                <?php }else{?>
                <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label"><?= lang ('status_font_icon') ?> : </label>
                </div>
                <div class="col-sm-9">
                    <?php $icon = !empty($editRecord[0]['status_font_icon']) ? $editRecord[0]['status_font_icon'] : '' ?>
                    <i class="fa fa-<?= $icon ?> blackcol"></i>
                </div>

            </div>
                <?php }?>
                <!-- <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="control-label"><?= lang ('status_font_icon') ?><?php if($disable ==""){?>*<?php }?> : </label>
                        <input type="text" class="form-control" placeholder="<?= lang ('status_font_icon') ?>"
                               id="status_font_icon" name="status_font_icon"
                               value="<?= !empty($editRecord[0]['status_font_icon']) ? $editRecord[0]['status_font_icon'] : '' ?>"
                               required <?php echo $disable; ?> >
                        (Please use "Font Awesome" icon name from <a target="_blank"
                                                                     href='https://fortawesome.github.io/Font-Awesome/icons/'>
                            here </a> like "home" for this symbol <i class="fa fa-home blackcol"></i>)
                    </div>
                </div>-->
            </div>
            <div class="modal-footer">
            <?php if(!isset($readonly)){?>
                <center> 
                	<input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
               		<input name="status_id" type="hidden" value="<?=!empty($editRecord[0]['status_id'])?$editRecord[0]['status_id']:''?>" />
               		   <?php if($formAction == "insertdataStatus"){?>
               		   <input type="submit" id="submit_btn" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?= lang ('add_status') ?>" />
					  <?php }else{?>
					   <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?= lang ('update_status') ?>" />
					  <?php }?>
					   <input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" />
				</center>								
		           <?php }?> 
            </div>
       <?php echo form_close(); ?>
    </div>
</div>
<style type="text/css">
    .chosen-drop{font-family: "Helvetica Neue",Helvetica,Arial,sans-serif, 'FontAwesome';}
    .chosen-single{font-family: "Helvetica Neue",Helvetica,Arial,sans-serif, 'FontAwesome';}
</style>
<script> 
$(document).ready(function () {
	$('#support_settings').parsley();
	$('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({allow_single_deselect: true});
});
$(function () {    
    $('.selectcolor').colorpicker({format: 'hex'});
  
});

</script>