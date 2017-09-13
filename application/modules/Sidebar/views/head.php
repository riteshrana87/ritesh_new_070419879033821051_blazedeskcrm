<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php //pr($param);    ?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
<meta name="description" content="">
<meta name="author" content="">
<title>Blazedesk</title>
<!-- Bootstrap core CSS -->
<link href="<?= base_url() ?>uploads/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url() ?>uploads/dist/css/bootstrap-theme.min.css" rel="stylesheet">
<link id="bsdp-css" href="<?= base_url() ?>uploads/dist/css/bootstrap-datepicker3.css" rel="stylesheet">
<link href="<?= base_url() ?>uploads/dist/css/datatables.min.css" rel="stylesheet">
<link href="<?= base_url() ?>uploads/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url() ?>uploads/css/style.css" rel="stylesheet">
<link href="<?= base_url() ?>uploads/custom/css/dropzone.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url() ?>/uploads/custom/css/jquery-ui.css">
<script src="<?= base_url() ?>uploads/custom/js/Salescampaign/dropzone.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="<?= base_url() ?>uploads/css/style.css" rel="stylesheet">
<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="<?= base_url() ?>uploads/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="<?= base_url() ?>uploads/dist/js/jquery-2.1.4.min.js"></script>
<script src="<?= base_url() ?>uploads/assets/js/ie-emulation-modes-warning.js"></script>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<link href="<?= base_url() ?>uploads/custom/css/development_css.css" rel="stylesheet">

<script>
var value_required = "<?php echo lang('value_required'); ?>";
var email_required = "<?php echo lang('email_required'); ?>";
var url_required = "<?php echo lang('url_required'); ?>";
var number_required = "<?php echo lang('number_required'); ?>";
var integer_required = "<?php echo lang('integer_required'); ?>";
var digit_required = "<?php echo lang('digit_required'); ?>";
var numeric_value = "<?php echo lang('numeric_value'); ?>";
var sign_require = "<?php echo lang('sign_require'); ?>";
var not_blank = "<?php echo lang('not_blank'); ?>";
var invalid_value = "<?php echo lang('invalid_value'); ?>";
var greater_equal_value = "<?php echo lang('greater_equal_value'); ?>";
var lower_value = "<?php echo lang('lower_value'); ?>";
var between_value = "<?php echo lang('between_value'); ?>";
var short_value = "<?php echo lang('short_value'); ?>";
var long_value = "<?php echo lang('long_value'); ?>";
var invalid_length_value = "<?php echo lang('invalid_length_value'); ?>";
var select_one_value = "<?php echo lang('select_one_value'); ?>";
var select_less_value = "<?php echo lang('select_less_value'); ?>";
var select_between_value = "<?php echo lang('select_between_value'); ?>";
var same_value = "<?php echo lang('same_value'); ?>";
var same_not_value = "<?php echo lang('same_not_value'); ?>";
var grater_value = "<?php echo lang('grater_value'); ?>";
var grater_or_equal_value = "<?php echo lang('grater_or_equal_value'); ?>";
var less_value = "<?php echo lang('less_value'); ?>";
var less_equal_value = "<?php echo lang('less_equal_value'); ?>";
</script>


<?php
/*
  @Author : Maulik Suthar
  @Desc   : Used for the custom CSS initilization just pass array of the scripts with links
  @Input 	:
  @Output	:
  @Date   : 29/02/2016
 */
if (isset($headerCss) && count($headerCss) > 0) {
    foreach ($headerCss as $css) {
        ?>
        <link href="<?php echo $css; ?>" rel="stylesheet" type="text/css" />
        <?php
    }
}
?>