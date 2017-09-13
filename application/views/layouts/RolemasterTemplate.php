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
        if (empty($head)) {
            $head = array();
        }
        echo Modules::run('Sidebar/head', $head);
      $system_lang = $this->common_model->get_lang();
        ?>
    </head>	
    <body id=<?php echo $system_lang;?> <?php if($system_lang!='english'){?>class="bd-lang-format"<?php }?>>
        <nav class="navbar navbar-default navbar-web">
          <div class="menu-whitebg">
  	<div class="container">
                <?php
                /*
                  Author : Rupesh Jorkar(RJ)
                  Desc   : Call Header area
                  Input  : Bunch of Array
                  Output : Top Side Header(Logo, Menu, Language)
                  Date   : 13/01/2016
                 */
                if (empty($header)) {
                    $header = array();
                }
                echo Modules::run('Sidebar/header', $header);
                ?>
            </div>
            <div class="navbar-inverse side-collapse in" id="side-collapse">
                <?php
                /*
                  Author : Rupesh Jorkar(RJ)
                  Desc   : Call Left Menu area
                  Input  : Bunch of array
                  Output : Top Side Header
                  Date   : 13/01/2016
                 */
                if (empty($leftmenu)) {
                    $leftmenu = array();
                }
                echo Modules::run('Sidebar/leftmenu', $leftmenu);
                ?>
            </div>

            <!--/.nav-collapse -->
            <div class="clr"></div>
            <!--</div> RJ This div not started at top any reason need to discuss-->
        </nav>
        <!------------------->
        <nav class="navbar navbar-default navbar-mobile">
           <div class="menu-whitebg2"> <div class="container">
                <?php
                /*
                  Author : Rupesh Jorkar(RJ)
                  Desc   : Call Mobile Menu and Header
                  Input  : Bunch of array
                  Output : Top Side Header and Menu
                  Date   : 18/01/2016
                 */
                if (empty($mobileheader)) {
                    $mobileheader = array();
                }
                echo Modules::run('Sidebar/mobileheader', $mobileheader);
                ?>
            </div></div>
        </nav>
        <!-- /.navbar-collapse -->
         <div class="clr pad-tb6"></div>
        <div class="container" id="common_div"> 
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
        <?php
        /*
          Author : Maulik Suthar
          Desc   : Call Footer Area
          Input  :
          Output : Footer Area( Menu, Content)
          Date   : 06/02/2016
         */
        echo Modules::run('Sidebar/footer');
        ?>
         <script src='<?= base_url() ?>uploads/custom/js/jquery.blockUI.js'></script>
		<script>
		
		//Open Popup
			$('body').delegate('[data-toggle="ajaxModal"]', 'click',
					
						
					
					function (e) {
						$('#common_div').block({message: 'Loading...'});
						$('#ajaxModal').remove();
						e.preventDefault();
						var $this = $(this)
								, $remote = $this.data('remote') || $this.attr('data-href')
								, $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
						$('body').append($modal);
						$modal.modal();
						$modal.load($remote);
						$('#common_div').unblock();
					}
			);
		
		</script>
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
    </body>
</html>
