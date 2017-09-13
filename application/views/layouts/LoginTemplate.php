<!DOCTYPE html>
<html lang="en">
<head>
	<?php 
		/*
		  Author : Rupesh Jorkar(RJ)
		  Desc   : Call Head area
		  Input  : Bunch of Array
		  Output : All CSS and JS
		  Date   : 04/02/2016
		 */
		if(empty($head))
		{
			$head = array();
		}
		echo Modules::run('Sidebar/head', $head); 
$system_lang = $this->common_model->get_lang();
        ?>
    </head>	
   <body id=<?php echo $system_lang;?> class="login <?php if($system_lang!='english'){echo 'bd-lang-format';}?>"> 	


    <?php 
		/*
		  Author : Rupesh Jorkar(RJ)
		  Desc   : Call Head area
		  Input  : Bunch of Array
		  Output : All CSS and JS
		  Date   : 04/02/2016
		 */
		if(empty($header))
		{
			$header = array();
		}
		echo Modules::run('Sidebar/loginheader', $header); 
	?>


  <div class="clr"></div>
  <div  class="loginbg">
	<div class="container">
	<!-- Example row of columns -->
		<?php 
			/*
			  Author : Rupesh Jorkar(RJ)
			  Desc   : Call Page Content Area
			  Input  : View Page Name and Bunch of array
			  Output : View Page
			  Date   : 11/01/2016
			 */
				$this->load->view($main_content);
		?>
	</div>
</div></div> 
<div class="clr"></div>
<!-- /container --> 
	<?php 
	/*
	  Author : Rupesh Jorkar(RJ)
	  Desc   : Call Footer Area
	  Input  : 
	  Output : Footer Area( Menu, Content)
	  Date   : 18/01/2016
	 */
	echo Modules::run('Sidebar/loginfooter'); 
	?>

	<?php 
	/*
	  Author : Rupesh Jorkar(RJ)
	  Desc   : Unset Error Message Variable for all Form
	  Input  : 
	  Output : Unset Error Session
	  Date   : 18/01/2016
	 */
	echo Modules::run('Sidebar/unseterror'); 
	?>
	<script src="<?= base_url() ?>uploads/dist/js/datatables.min.js"></script>

	<?php if (isset($drag) && $drag == true): ?>
		<script src="<?= base_url() ?>uploads/custom/js/jquery-ui.min.js"></script>
	<?php endif; ?>
	<link id="bsdp" href="<?= base_url() ?>uploads/custom/css/bootstrap-chosen.css" rel="stylesheet">
	<script src="<?= base_url() ?>uploads/custom/js/bootstrap-toggle.js"></script>
	<link href="<?= base_url() ?>uploads/custom/css/bootstrap-toggle.css" rel="stylesheet">
	<script src="<?= base_url() ?>uploads/custom/js/chosen.jquery.js"></script>
	<script src="<?= base_url() ?>uploads/custom/js/projectmanagement/parsley.js"></script>
	<script src="<?= base_url() ?>uploads/dist/js/bootstrap-datepicker.js"></script>
	<link href="<?= base_url() ?>uploads/custom/css/projectmanagement/parsley.css" rel="stylesheet">


	<!-- summernote Core JS-->
	<script src="<?= base_url() ?>uploads/dist/js/summernote.min.js"></script>
	<link href="<?= base_url() ?>uploads/dist/css/summernote.css" rel="stylesheet">
	<link href="<?php echo base_url('uploads/custom/css/bootstrap-dialog.css'); ?>" rel="stylesheet" type="text/css" />
	<script src="<?php echo base_url('uploads/custom/js/bootstrap-dialog-min.js'); ?>"></script>


	<script>
	$(document).ready(function () {
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>Masteradmin/removed_session",
			data: { session_id: $('#remove_session').val() }
		})
	});
	</script>
	<script>
		$(document).ready(function () {
			$('#frmlogin').parsley();

		});

		$('body').delegate('#lgnsubmit', 'click', function () {
			if ($('#frmlogin').parsley().isValid()) {
				$('button[type="submit"]').prop('disabled', true);
				$('#frmlogin').submit();
			}
		});


		$('body').delegate('[data-toggle="ajaxModal"]', 'click',
			function (e) {
				$('#ajaxModal').remove();
				e.preventDefault();
				var $this = $(this)
					, $remote = $this.data('remote') || $this.attr('data-href') || $this.attr('href')
					, $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
				$('body').append($modal);
				$modal.modal();
				$modal.load($remote);

				//$("body").addClass("modal-open");
				$("body").css("padding-right", "0 !important");

			}
		);
	</script>
</body>
</html>
