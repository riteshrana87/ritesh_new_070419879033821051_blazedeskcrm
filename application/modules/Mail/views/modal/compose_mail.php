<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$formAction = !empty($editRecord) ? 'updatedata?id=' . $id : 'insertdata';
//$path = $crnt_view . '/' . $formAction;
$path = '';
$formAction='';
?>
<?php echo $this->session->flashdata('verify_msg'); ?>
<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?PHP if ($formAction == "insertdata") { ?><?= $this->lang->line('create_account') ?><?php } else { ?><?= $this->lang->line('update_account') ?><?php } ?><div class="modelTitle"></div></h4>
        </div>
        <?php
        $attributes = array('class' => 'myformclass', 'id' => 'add_account', 'name' => 'add_account', 'data-parsley-validate' => "");
        echo form_open_multipart($path, $attributes);
        ?>
        <div class="modal-body"></div>
<?php echo form_close(); ?>
    </div>
</div>

