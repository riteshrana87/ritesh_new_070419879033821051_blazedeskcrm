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
<div class="whitebox" id="table_contacts">
    <div class="table table-responsive">
        <table class="table table-responsive" >
            <thead >
                <tr>
                    <th></th>
                    <th class='sortTask col-md-3'>

                        <a href="<?php echo base_url(); ?>Account/getContactData/?orderField=contact_name&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'contact_name') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'contact_name') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('name') ?>
                        </a>
                    </th>
                    <th class='sortTask col-md-3'>

                        <a href="<?php echo base_url(); ?>Account/getContactData/?orderField=email&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'email') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'email') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('email') ?>
                        </a>
                    </th>
                    <th class='sortTask col-md-3'>

                        <a href="<?php echo base_url(); ?>Account/getContactData/?orderField=mobile_number&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'mobile_number') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'mobile_number') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('number') ?>
                        </a>
                    </th>
                    <th class='sortTask col-md-2'></th>
                    <th class='sortTask col-md-1'></th>
                </tr>
            </thead>

            <tbody id="postList">
                <?php
                $redirect_link = $_SERVER['HTTP_REFERER'];
                $primaryCount = 0;
                $delete_count = 0;
                if (isset($opportunity_contact_data) && count($opportunity_contact_data) > 0) {
                    foreach ($opportunity_contact_data as $contact) {
                        ?>
                        <tr class = " row contacts" id="add_contact<?php echo $contact['contact_id']; ?>">

                            <td><?php if (!empty($contact['contact_name'])) { ?>
                                    <?php if (checkPermission('Contact', 'view')) { ?><a href="<?= base_url() ?>Contact/view/<?php echo $contact['contact_id'] ?>"  title="<?= lang('view') ?>" ><?php } ?><?php echo $contact['contact_name']; ?></a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php
                                if (!empty($contact['email'])) {
                                    echo $contact['email'];
                                }
                                ?>

                            </td>
                            <td>
                                <?php
                                if (!empty($contact['mobile_number'])) {
                                    echo $contact['mobile_number'];
                                }
                                ?>
                            </td>
                            <td>
                                <input type="hidden" name="contact_id[]" id="contact_id" value="<?php
                                if (!empty($contact['contact_id'])) {
                                    echo $contact['contact_id'];
                                }
                                ?>">
                                <input name="primary_contact"   type="radio" onclick="ChangeStatus(<?php echo $contact['contact_id'] ?>,<?php echo $contact['company_id'] ?>);" value="1" id="primary1" <?php if (!empty($primaryData[0]['contact_id'])) {

                            if (isset($primaryData[0]['contact_id'])) {
                                if ($contact['contact_id'] == $primaryData[0]['contact_id']) {
                                    echo "checked=checked";
                                }
                            }
                        }
                        ?> ><label class="radio-inline"> <?= $this->lang->line('primary') ?></label>
                            </td>   
                                <td>
                                        <?php if (isset($primaryData[0]['contact_id'])) {
                                            if ($contact['contact_id'] != $primaryData[0]['contact_id']) { ?> 
                                                <?php if (checkPermission('Contact', 'delete')) { ?>
                                            <a class="delcontacts" title="<?= $this->lang->line('delete') ?>" onclick="delete_row_contact(<?php echo $contact['contact_id'] ?>,<?php echo $contact['contact_id'] ?>);">
                                                <i class="fa fa-remove fa-x redcol"></i> </a>
                                                <?php } ?>
                                            <?php }
                                        } ?>
                                </td>
                               
                           
                        </tr>
                        <?php
                        $delete_count++;
                    }
                } else {
                    ?>
                    <tr><td colspan="4" class="text-center"> <?= lang('common_no_record_found') ?></td></tr>  
                <?php }
                ?>       
            </tbody>
        </table>
        <div id="deletedContactsDiv"></div>
        <div class="row">
            <div class="col-md-12 text-center">
<?php echo (!empty($pagination)) ? $pagination : ''; ?>
            </div>
        </div>
    </div>
    <div class="clr"></div>
</div>
<script>
//Change Primary Contact
    function ChangeStatus(contact_id, company_id) {
        //var redirectlink='<?php echo base_url() . "Account/getContactData/0" ?>';
        var url = '<?php echo base_url() . "Account/changePrimaryStatus/" ?>';
        if (contact_id) {
            $.ajax({
                type: "POST",
                url: url,
                data: {'company_id': company_id, 'contact_id': contact_id},
                dataType: "JSON",
                success: function (data)
                {
<?php if (!empty($pagination)) { ?>
                        if (data.status == 1) {
                            $('ul.pagination > li.active a').click();
                        }
<?php } else {
    ?>
                        var url_contact = '<?php echo base_url() . "Account/getContactData/" ?>';
                        $.ajax({
                            type: "POST",
                            url: url_contact,
                            // data: { 'searchtext': searchtext},
                            success: function (data)
                            {
                                $('#Contacts').html(data);
                            }
                        });
<?php } ?>
                }
            });
        }
    }

    //add another delete contact remove row code
    function delete_row_contact(removeNum, contact_id) {
        var url = '<?php echo base_url() . "Account/delete_contact_master/" ?>';
        var add_row_no = $('.contacts').length;
        if (add_row_no > 0) {
            var delete_meg = "<?php echo lang('CONFIRM_DELETE_CONTACT'); ?>";
            BootstrapDialog.show(
                    {
                        title: '<?php echo $this->lang->line('Information'); ?>',
                        message: delete_meg,
                        buttons: [{
                                label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                                action: function (dialog) {
                                    dialog.close();
                                    $('#confirm-id').on('hidden.bs.modal', function () {
                                        $('body').addClass('modal-open');
                                    });
                                }
                            }, {
                                label: '<?php echo $this->lang->line('ok'); ?>',
                                action: function (dialog) {
                                    if (contact_id) {
                                        $.ajax({
                                            type: "POST",
                                            url: url,
                                            data: {'contact_id': contact_id},
                                            success: function (data)
                                            {
                                                BootstrapDialog.show({
                                                    message: "<?php echo lang('contact_delete_message'); ?>",
                                                    buttons: [{
                                                            label: '<?php echo lang('close'); ?>',
                                                            action: function (dialogItself) {
                                                                jQuery('#add_contact' + removeNum).remove();
                                                                add_row_no--;
                                                                dialogItself.close();
                                                            }
                                                        }]
                                                });


                                            }
                                        });
                                    }
                                    else {
                                        jQuery('#add_contact' + removeNum).remove();
                                        add_row_no--;
                                    }
                                    //open modal after click on delete button
                                    $('#confirm-id').on('hidden.bs.modal', function () {
                                        $('body').addClass('modal-open');
                                    });
                                    dialog.close();
                                }
                            }]
                    });

        }
    }
</script>