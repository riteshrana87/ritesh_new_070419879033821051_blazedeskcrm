<?php
if (count($contacts) > 0) {
    foreach ($contacts as $key => $contactValue) {
        $contactEmail = (isset($contactValue['email'])) ? $contactValue['email'] : $contactValue;
        $contactName = (isset($contactValue['contact_name'])) ? '<strong>' . $contactValue['contact_name'] . '</strong><br/>' : '';
        ?>
        <li>
            <a class="cmail" id="contact-<?php echo $key; ?>" onclick="addselectedclass('contact-<?php echo $key; ?>', '<?php echo $contactEmail ?>')" data-email='<?php echo $contactEmail; ?>'>
                <?php echo $contactName . $contactEmail; ?>
            </a>
        </li>
        <?php
    }
} else {
    ?>
    <li> <a><strong> <?= lang('mail_no_contact_found')?></strong></a></li>
    <?php
}
?>