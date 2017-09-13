<?php
//if ($taskPage == 0)
//    $taskPage = 1;
$tasksSortDefault = '<i class="fa fa-sort"></i>';
$taskSortAsc = '<i class="fa fa-sort-desc"></i>';
$taskSortDesc = '<i class="fa fa-sort-asc"></i>';
if ($tasksortOrder == "asc") {
    $tasksortOrder = "desc";
} else {
    $tasksortOrder = "asc";
}

?>   
<div class="whitebox" id="table_attach">
    
                    <?php 
                    
                    //pr($contact_file_data);
                    ?>
                    <ul class="files">
                        <?php
                        if(isset($file_data) && count($file_data) > 0)
                        {
                        
                            foreach ($file_data as $data)
                            {
                                $file_name = $data['file_name'];
                                
                                $file_extension = explode('.',$file_name);
                                
                                $document_logo_file_name = getImgFromFileExtension($file_extension[1]);
                                $document_logo_file_path = base_url()."/uploads/images/icons64/".$document_logo_file_name;
                                
                                $image_path = base_url().$data['file_path']."/".$file_name;
                                ?>
                            <li id="contact_file_<?php echo $data['file_id'];?>" class="bd-contact-rmv">
                                <p class="text-center"><a href="<?php echo $image_path; ?>" download>
                                        <img src="<?php echo $document_logo_file_path; ?>" alt=""/>
                                    </a>
                                </p>
                                <p class="text-center"><a href="<?php echo $image_path; ?>" download><?php echo $file_name;?></a></p>
                                <?php if (checkPermission ("SupportContact", 'delete')) { ?><i class="fa fa-remove" title="<?= $this->lang->line('delete') ?>" onclick="delete_contact_file('<?php echo $data['file_id'];?>','<?php echo $data['file_path'] ?>','<?php echo $_SERVER['HTTP_REFERER']; ?>')"></i><?php } ?>
                            </li>
                        <?php   }
                        }else
                         { ?>
                            <p class="text-center"><?= lang ('common_no_record_found') ?></p>
                        <?php }
                        ?>
                       			
                    </ul>
                    <div class="clr"></div>

                
    <div class="clr"></div>
</div>
<script>
function delete_contact_file(file_id,file_path,redirect_link)
{
    var delete_url = "../../SupportContact/delete_contact_attach/?file_id=" + file_id+"&file_path="+file_path+"&redirect_link="+redirect_link;
    var delete_meg ="<?php echo $this->lang->line('CONFIRM_DELETE_FILE');?>";
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
                    $.ajax({
                        type: "POST",
                        url: delete_url,
                        data: {'file_id': file_id},
                        success: function (data)
                        {
                            BootstrapDialog.show({
                                message: '<?php echo lang('SUCCESS_FILE_DELETED_MSG'); ?>',
                                buttons: [{
                                    label: '<?php echo lang('close');?>',
                                    action: function (dialogItself) {
                                        $('#contact_file_' + file_id).remove();
                                        dialogItself.close();
                                    }
                                }]
                            });
                        }
                    });
                    dialog.close();
                }
            }]
        });

}
</script>