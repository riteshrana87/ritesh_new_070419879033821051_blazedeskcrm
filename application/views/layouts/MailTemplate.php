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
                      Author : Niral Patel
                      Desc   : Call Header area
                      Input  : Bunch of Array
                      Output : Top Side Header(Logo, Menu, Language)
                      Date   : 27-1-2016
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
                      Author : Niral Patel
                      Desc   : Call Left Menu area
                      Input  : Bunch of array
                      Output : Top Side Header
                      Date   : 27-1-2016
                     */
                    if (empty($LeftMenu)) {
                        $LeftMenu = array();
                    }
                    echo Modules::run('Sidebar/LeftMenu', $LeftMenu);
                    ?>
                </div>

                <!--/.nav-collapse -->
                <div class="clr"></div>
                <!--</div>  This div not started at top any reason need to discuss-->
        </nav>
        <!------------------->
        <nav class="navbar navbar-default navbar-mobile">
            <div class="menu-whitebg2"> <div class="container">
                    <?php
                    /*
                      Author : Niral Patel
                      Desc   : Call Mobile Menu and Header
                      Input  : Bunch of array
                      Output : Top Side Header and Menu
                      Date   : 27-1-2016
                     */
                    if (empty($mobileheader)) {
                        $mobileheader = array();
                    }
                    echo Modules::run('Sidebar/mobileheader', $mobileheader);
                    ?>
                </div> </div>
        </nav>
        <!-- /.navbar-collapse -->
        <div class="clr"></div>
        <div class="outer-wrapper">     
            <div class="container"> 
                <!-- Example row of columns -->
                <?php
                /*
                  Author : Niral Patel
                  Desc   : Call Page Content Area
                  Input  : View Page Name and Bunch of array
                  Output : View Page
                  Date   : 11/01/2016
                 */
                if (!empty($main_content)) {
                    $this->load->view($main_content);
                }
                ?>
            </div>
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
        <link id="bsdp" href="<?= base_url() ?>uploads/custom/css/bootstrap-chosen.css" rel="stylesheet">
        <script src="<?= base_url() ?>uploads/custom/js/chosen.jquery.js"></script>
        <script src="<?= base_url() ?>uploads/custom/js/bootstrap-toggle.js"></script>
        <link href="<?= base_url() ?>uploads/custom/css/bootstrap-toggle.css" rel="stylesheet">
        <script src='<?= base_url() ?>uploads/custom/js/jquery.blockUI.js'></script>


        <?php
        /*
          Author : Niral Patel
          Desc   : Unset Error Message Variable for all Form
          Input  :
          Output : Unset Error Session
          Date   : 27-1-2016
         */
        echo Modules::run('Sidebar/unseterror');
        ?>
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
                

            });

        </script>
        <?php
        if (isset($js_content) && $js_content != "") {
            $this->load->view($js_content);
        }
        ?>
        <!-- Modal HTML -->
        <!--  <div id="ajaxModal" class="modal fade">
             <div class="modal-dialog">
                 <div class="modal-content">
                     
                 </div>
             </div>
         </div> -->
    </body>
</html>
