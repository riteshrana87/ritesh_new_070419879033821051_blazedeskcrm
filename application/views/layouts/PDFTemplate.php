<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- BOOTSTRAP CORE STYLE  -->
		<link href="<?php echo base_url(); ?>uploads/reports/assets/css/bootstrap.css" rel="stylesheet" />
		<!-- CUSTOM STYLE  -->
		<link href="<?php echo base_url(); ?>uploads/reports/assets/css/custom-style.css" rel="stylesheet" />
		<!-- GOOGLE FONTS -->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css' />
		<script src="<?= base_url() ?>uploads/dist/js/jquery-2.1.4.min.js"></script>
    </head>	
    <body class="pdf-container">
        <div class="container">
            <!-- Example row of columns -->
            <?php
            /*
              Author : Rupesh Jorkar(RJ)
              Desc   : Call Page Content Area
              Input  : View Page Name and Bunch of array
              Output : View Page
              Date   : 11/03/2016
             */
            $this->load->view($main_content);
            ?>
        </div>
    </body>
</html>