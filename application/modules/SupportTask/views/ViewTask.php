<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if($view === true){
    $readonly = 'disabled';
}else{
    $readonly = '';
}
?>
<!-- Modal View Task-->
<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                <div class="modelTaskTitle"><?= $this->lang->line('task_details') ?>:<?php if (!empty($editRecord[0]['status']) && ($editRecord[0]['status'])==2) { ?>
            <?= $this->lang->line('completed_status') ?>
                <?php } ?></div></h4>  
        </div>

        <div class="modal-body">
   
            <div class = "form-group row">
                <div class = "col-sm-12 col-xs-12 ">
                   
                     <label class="mar-tb0 "><b><?=!empty($editRecord[0]['task_name'])?$editRecord[0]['task_name']:''?></b></label>
                </div>
            </div>
                <div class ="form-group row">
                <div class = "col-sm-12">
                   <p><?php
                    if (!empty($editRecord[0]['task_description'])) {
                        echo $editRecord[0]['task_description'];
                    }
                    ?></p>
                </div>
            </div>
                 <div class ="form-group row">

                <div class = "col-sm-4 col-xs-12">
                    <label class="inline"><?= $this->lang->line('start_date') ?> </label>
                  <div class="input-group date" id="start_date">
                    <p><?php
                    if (!empty($editRecord[0]['start_date']) && $editRecord[0]['start_date'] != '0000-00-00') {
                        echo date('m/d/Y',strtotime($editRecord[0]['start_date']));
                    }
                    ?></p>
               </div>
                    
                </div>

                <div class ="col-sm-4 col-xs-12">
                    <label class="inline"><?= $this->lang->line('finish_before') ?> </label>
                    <div class="input-group date" id="end_date">
                        <p><?php
                        if (!empty($editRecord[0]['end_date']) && $editRecord[0]['end_date'] != '0000-00-00') {
                            echo date('m/d/Y',strtotime($editRecord[0]['end_date']));
                        }
                        ?></p>
               </div>
                </div>
                <div class = "col-sm-4 col-xs-12 bd-form-group">
                    <label ><?= $this->lang->line('importance') ?></label>
                    <?php
                    if(!empty($editRecord[0]['importance']))
                    {
                    if ($editRecord[0]['importance'] == 'High') {
                        $class = 'bd-prior-high bd-prior-cust';
                    } elseif ($editRecord[0]['importance'] == 'Medium') {
                        $class = 'bd-prior-med bd-prior-cust';
                    } else if ($editRecord[0]['importance'] == 'Low') {
                        $class = 'bd-prior-low bd-prior-cust';
                    }
                    }
                    ?>
                    <span class="bd-prior-cust <?php echo $class; ?>"> <?=!empty($editRecord[0]['importance'])?$editRecord[0]['importance']:''?></span>
                </div>
            </div>
                <div class ="form-group row">
                    <?php if(empty($editRecord[0]['remember'])) { ?>
                <div class = "col-sm-4 col-xs-12">
                    <label class=""><?= $this->lang->line('reminder?') ?></label>
                    <p>N/A</p>
                </div>
                    <?php } ?>
                <?php if(!empty($editRecord[0]['remember'])) { ?>
           

                <div class = "col-sm-4 col-xs-12">
                    <label class="inline"><?= $this->lang->line('REMINDER_DATE') ?></label>
                  <div class="input-group date" id="reminder_date">
                      <p><?php
if (!empty($editRecord[0]['reminder_date']) && $editRecord[0]['reminder_date'] != '0000-00-00') {
    echo date('m/d/Y',strtotime($editRecord[0]['reminder_date']));
}
?></p>
               </div>
                    
                </div
             
             
                <div class ="col-sm-4 col-xs-12">
                    <label class="inline"><?= $this->lang->line('REMINDER_TIME') ?></label>
                    <div class="input-group date" id="REMINDER_TIME">
                        <p><?php
if (!empty($editRecord[0]['reminder_time']) ) {
    echo $editRecord[0]['reminder_time'];
}
?></p>
               </div>
                </div>

           
            <?php } ?>
            </div>
           
        </div>
        
        <div class="modal-footer">
             <?php $redirect_link = $_SERVER['HTTP_REFERER']; ?>
 <?php if ((!empty($editRecord[0]['status'])) && ($editRecord[0]['status']==2)){ ?>
<?PHP if (checkPermission('SupportTask', 'edit')) { ?> <a data-href="javascript:;" onclick="reopen_request('<?php echo $editRecord[0]['task_id']; ?>', '<?php echo $redirect_link; ?>');" ><input type="button" class="btn btn-primary" name="completed"  value="<?= $this->lang->line('reopen') ?>" /></a><?php } ?>
<?php } ?>
        </div>
    </div>

</div>

<script>
    //toggle display
     $('#remember').bootstrapToggle();
     //delete task
    function reopen_request(task_id, redirect_link) {
       
        var complete_url = "<?php echo base_url(); ?>SupportTask/reopen/?id=" + task_id + "&link=" + redirect_link;
        var delete_meg ="<?php echo $this->lang->line('confirm_reopen_task');?>";
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
                        window.location.href = complete_url;
                        dialog.close();
                    }
                }]
            });
    }
</script>
