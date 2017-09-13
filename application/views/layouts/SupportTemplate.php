<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        /*
          Author : Nikunj Ghelani
          Desc   : Call Head area
          Input  : Bunch of Array
          Output : All CSS and JS
          Date   : 02/03/2016
         */
        if (empty($head)) {
            $head = array();
        }
       //pr($_SESSION);
        echo Modules::run('Sidebar/head', $head);
        $system_lang = $this->common_model->get_lang();
		//echo $system_lang;die();
        ?>
    </head>	
    <body id=<?php echo $system_lang;?> <?php if($system_lang!='english'){?>class="bd-lang-format"<?php }?>>
        <nav class="navbar navbar-default navbar-web">
            <div class="menu-whitebg">
                <div class="container">
                    <?php
                    /*
                      Author : Nikunj Ghelani
					  Desc   : Call Head area
					  Input  : Bunch of Array
					  Output : All CSS and JS
						Date   : 02/03/2016
                     */
                    if (empty($header)) {
                        $header = array();
                    }
                    echo Modules::run('Sidebar/header', $header);
                    ?>
                </div>
            </div>
            <div class="navbar-inverse side-collapse in" id="side-collapse">
                <?php
                /*
                 Author : Nikunj Ghelani
				  Desc   : Call Head area
				  Input  : Bunch of Array
				  Output : All CSS and JS
				  Date   : 02/03/2016
                 */
                if (empty($leftmenu)) {
                    $leftmenu = array();
                }
                echo Modules::run('Sidebar/supportleftmenu', $leftmenu);
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
                      Author : Nikunj Ghelani
					  Desc   : Call Head area
					  Input  : Bunch of Array
					  Output : All CSS and JS
					  Date   : 02/03/2016
                     */
                    if (empty($mobileheader)) {
                        $mobileheader = array();
                    }
                    echo Modules::run('Sidebar/mobileheader', $mobileheader);
                    ?>
                </div> </div>
        </nav>
        <!-- /.navbar-collapse -->
        <div class="clr pad-tb6"></div>
        <div class="container"> 
            <!-- Example row of columns -->
            <?php
            /*
             Author : Nikunj Ghelani
			  Desc   : Call Head area
			  Input  : Bunch of Array
			  Output : All CSS and JS
			  Date   : 02/03/2016
				 */
            $this->load->view($main_content);
            ?>
        </div>

        <?php
        /*
           Author : Nikunj Ghelani
          Desc   : Call Head area
          Input  : Bunch of Array
          Output : All CSS and JS
          Date   : 02/03/2016
         */
        echo Modules::run('Sidebar/footer');
        ?>
        <?php
        /*
         Author : Nikunj Ghelani
          Desc   : Call Head area
          Input  : Bunch of Array
          Output : All CSS and JS
          Date   : 02/03/2016
         */
        ?>
		<script src='<?= base_url() ?>uploads/custom/js/enjoy_hint/enjoyhint.min.js'></script>
		<link href='<?= base_url() ?>uploads/custom/js/enjoy_hint/enjoyhint.css' rel='stylesheet'/>
        <link href='<?= base_url() ?>uploads/custom/css/projectmanagement/fullcalendar.css' rel='stylesheet' />
        <script src='<?= base_url() ?>uploads/custom/js/projectmanagement/moment.js'></script>
        <script src='<?= base_url() ?>uploads/custom/js/projectmanagement/fullcalendar.js'></script>
		<link href='<?= base_url() ?>uploads/custom/css/projectstatus/bootstrap-colorpicker.css' rel='stylesheet' />
        <script src='<?= base_url() ?>uploads/custom/js/projectstatus/bootstrap-colorpicker.js'></script>
        <script src='<?= base_url() ?>uploads/custom/js/jquery.blockUI.js'></script>
		<script src='<?= base_url() ?>uploads/dist/js/livestamp.js'></script>
		<script src='<?= base_url() ?>uploads/dist/js/moment.min.js'></script>
        <script src='<?= base_url() ?>uploads/dist/js/bootstrap-datetimepicker.js'></script>
        <link href='<?= base_url() ?>uploads/dist/css/bootstrap-datetimepicker.min.css' rel="stylesheet" type="text/css" />
        
        
        <script>

            $(document).ready(function () {
                $('body').delegate('[data-toggle="ajaxModal"]', 'click',
                        function (e) {
                            $('#ajaxModal').remove();
                            e.preventDefault();
                            var $this = $(this)
                                    , $remote = $this.data('remote') || $this.attr('href') || $this.attr('data-href')
                                    , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
                            $('body').append($modal);
                            $modal.modal();
                            $modal.load($remote);
                        }
                );
                $('#newcalendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,basicWeek,basicDay'
                    },
                    defaultDate: '<?= date("Y-m-d") ?>',
                    defaultView: 'month',
                    yearColumns: 2,
                    selectable: false,
                    selectHelper: true,
                    //firstDay: 0,
                    editable: false,
                    eventLimit: true, // allow "more" link when too many events
                    events: {
                        url: '<?= base_url("Support/grantview") ?>',
                        success: function (data) {

                        },
                        error: function () {
                            $('#script-warning').show();
                        }}
                    ,
                    eventRender: function (event, element) {
                        element.attr("data-toggle", "ajaxModal")
                    },
                });

            });

        </script>
        <?php
        if (isset($js_content) && !empty($js_content)) {
            $this->load->view($js_content);
        }
        ?>
        <?php
        /*
       Author : Nikunj Ghelani
          Desc   : Call Head area
          Input  : Bunch of Array
          Output : All CSS and JS
          Date   : 02/03/2016
         */
        echo Modules::run('Sidebar/unseterror');
        ?>
    </body>
</html>
