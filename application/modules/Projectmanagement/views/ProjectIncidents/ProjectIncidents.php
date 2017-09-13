<?php
defined ('BASEPATH') OR exit('No direct script access allowed');
?>
 <div class="clr"></div>
    <?php echo $this->session->flashdata ('msg'); ?>
    <div class="clr"></div>
<div id="main-page">
    <div class="row">
        <div class="col-xs-6 col-md-6 no-left-pad">
            <h3 class="white-link"><?= lang ('projectincidents') ?></h3>
        </div>
        <div class="col-xs-6 col-md-6 pull-right no-right-pad">
            <?php if (checkPermission ('ProjectIncidents', 'add')) { ?><a
                data-href="<?php echo base_url () . 'Projectmanagement/ProjectIncidents/add_record'; ?>"
                data-toggle="ajaxModal" title="<?= lang ('create') ?> <?=lang('projectincident')?>"
                class="btn btn-white pull-right add_record" ><?= $this->lang->line ('create') ?> <?=lang('projectincident')?></a><?php } ?>
        </div>
    </div>
   
    <div class="whitebox" id="common_div">

        <?php $this->load->view ('IncidentsAjaxList') ?>
    </div>
    <div class="clr"></div>

</div>
</div>

<div class="clr"></div>

