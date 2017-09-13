<?php
defined ('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="clr"></div>
<?php echo $this->session->flashdata ('msg'); ?>
<div class="clr"></div>
<div id="main-page">
    <div class="row">
        <div class="col-xs-6 col-md-6 no-left-pad">
            <h3 class="white-link"><?= lang ('milestone') ?></h3>
        </div>
        <div class="col-xs-6 col-md-6 pull-right no-right-pad">
            <?php if (checkPermission ('Milestone', 'add')) { ?><a
                data-href="<?php echo base_url () . 'Projectmanagement/Milestone/add_record'; ?>"
                data-toggle="ajaxModal" title="<?= lang ('create_milestone') ?>"
                class="btn btn-white pull-right add_record" ><?= $this->lang->line ('create_milestone') ?></a><?php } ?>
        </div>
    </div>
    
    <div class="whitebox" id="common_div">

        <?php $this->load->view ('MilestoneAjaxList') ?>
    </div>
    <div class="clr"></div>

</div>
</div>

<div class="clr"></div>

