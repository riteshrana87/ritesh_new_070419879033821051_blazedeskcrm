<?php
defined ('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="main-page">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-right-pad">
        <div class="col-xs-6 col-md-6 no-left-pad">
            <h3 class="white-link"><?= lang ('messages') ?></h3>
        </div>
        <div class="col-xs-6 col-md-2 pull-right no-right-pad">

        </div>
    </div>
    <div class="clr"></div>
    <?php echo $this->session->flashdata ('msg'); ?>
    <div class="clr"></div>
    <div class="whitebox" id="common_div">

        <?php $this->load->view ('MessagesAjaxList') ?>
    </div>
    <div class="clr"></div>

</div>
</div>

<div class="clr"></div>

