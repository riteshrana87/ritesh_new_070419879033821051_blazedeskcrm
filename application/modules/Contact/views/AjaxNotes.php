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
<div class="whitebox" id="table_note">
    <div class="table table-responsive">
        <table class="table table-responsive" >
            <thead>
                <tr role="row">
                    <th class="col-md-3"><?php echo lang('NOTE_SUBJECT');?></th>
                    <th class="col-md-3"><?php echo lang('NOTE_DESCRIPTION');?></th>
                    <th ><?php echo lang('NOTE_CREATED_BY');?></th>
                    <th class='sortTask'>
                        
                        <a    href="<?php echo base_url(); ?>Contact/viewNoteData/?orderField=created_date&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                           <?php echo lang('created_date')?>
                        </a>
                    </th>
                    <th><?= lang('actions') ?></th>
                </tr>
            </thead>

            <tbody>
                <?php
                
                if(isset($note_data) && count($note_data) > 0)
                { 
                    foreach ($note_data as $note) {
                    //$redirect_link = base_url()."Contact/view/".$note['notes_related_id'];
                         $redirect_link =  $_SERVER['HTTP_REFERER'];
                    ?>
                    <tr id="note_id_<?php echo $note['note_id']; ?>">
                        <td><?php
                            $note_dubject = $note['note_subject'];
                            echo  strlen($note_dubject) > 50 ? substr($note_dubject,0,50)."..." : $note_dubject; 
                            ?>
                        </td>
                        <td><?php
                            $in = $note['note_description'];
                            echo  strlen($in) > 50 ? substr($in,0,50)."..." : $in; 
                            ?>
                        </td>
                        <td><?php echo $note['login_user_name'];?></td>
                        <td><?php echo configDateTime($note['created_date']);  ?></td>
                        <td>
                            <?php  if (strpos($redirect_link, 'Contact/view') !== false) {
                                            if (checkPermission ("Contact", 'view')) {
                                            ?>
                            <a data-href="<?= base_url() ?>Contact/viewNote/<?= $note['note_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('VIEW_NOTES'); ?>"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;
                            <?php } } ?>
                            <?php  if (strpos($redirect_link, 'Lead/viewdata') !== false) {
                                            if (checkPermission ("Lead", 'view')) {
                                            ?>
                            <a data-href="<?= base_url() ?>Contact/viewNote/<?= $note['note_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('VIEW_NOTES'); ?>"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;
                            <?php } } ?>
                            <?php  if (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
                                            if (checkPermission ("Opportunity", 'view')) {
                                            ?>
                            <a data-href="<?= base_url() ?>Contact/viewNote/<?= $note['note_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('VIEW_NOTES'); ?>"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;
                            <?php } } ?>
                            
                             <?php  if (strpos($redirect_link, 'Contact/view') !== false) {
                            if (checkPermission ("Contact", 'edit')) {
                            ?>
                            <a data-href="<?= base_url() ?>Contact/updateNote/<?= $note['note_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('UPDATE_NOTES'); ?>"><i class="fa fa-pencil bluecol"></i></a>&nbsp;&nbsp; 
                            <?php } } ?>
                             <?php  if (strpos($redirect_link, 'Lead/viewdata') !== false) {
                            if (checkPermission ("Lead", 'edit')) {
                            ?>
                            <a data-href="<?= base_url() ?>Contact/updateNote/<?= $note['note_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('UPDATE_NOTES'); ?>"><i class="fa fa-pencil bluecol"></i></a>&nbsp;&nbsp; 
                            <?php } } ?>
                             <?php  if (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
                            if (checkPermission ("Opportunity", 'edit')) {
                            ?>
                            <a data-href="<?= base_url() ?>Contact/updateNote/<?= $note['note_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('UPDATE_NOTES'); ?>"><i class="fa fa-pencil bluecol"></i></a>&nbsp;&nbsp; 
                            <?php } } ?>
                            
                             <?php  if (strpos($redirect_link, 'Contact/view') !== false) {
                                            if (checkPermission ("Contact", 'delete')) {
                                            ?>
                            <a title="<?= $this->lang->line('delete') ?>" onclick="delete_note('<?php echo $note['note_id']; ?>','<?php echo $redirect_link;?>');"><i class="fa fa-remove redcol"></i></a></td>
                            <?php } } ?>
                            <?php  if (strpos($redirect_link, 'Lead/viewdata') !== false) {
                                            if (checkPermission ("Lead", 'delete')) {
                                            ?>
                            <a title="<?= $this->lang->line('delete') ?>" onclick="delete_note('<?php echo $note['note_id']; ?>','<?php echo $redirect_link;?>');"><i class="fa fa-remove redcol"></i></a></td>
                            <?php } } ?>
                            <?php  if (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
                                            if (checkPermission ("Opportunity", 'delete')) {
                                            ?>
                            <a title="<?= $this->lang->line('delete') ?>" onclick="delete_note('<?php echo $note['note_id']; ?>','<?php echo $redirect_link;?>');"><i class="fa fa-remove redcol"></i></a></td>
                            <?php } } ?>
                    </tr>
                <?php }
                }else
               { ?>
                    <tr><td colspan="5" class="text-center"> <?= lang ('common_no_record_found') ?></td></tr>  
              <?php }
                ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12 text-center">
                <?php echo (!empty($pagination)) ? $pagination : ''; ?>
            </div>
        </div>
    </div>
    <div class="clr"></div>
</div>