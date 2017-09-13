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
                    if (empty($projrctleftmenu)) {
                        $projrctleftmenu = array();
                    }
                    echo Modules::run('Sidebar/projrctleftmenu', $projrctleftmenu);
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
                    if (empty($mobileprojectheader)) {
                        $mobileprojectheader = array();
                    }
                    echo Modules::run('Sidebar/mobileheader', $mobileprojectheader);
                    ?>
                </div> </div>
        </nav>
        <!-- /.navbar-collapse -->
        <div class="outer-wrapper">
            <div class="container"> 
                <?php
                /*
                  Author : Niral Patel
                  Desc   : Call Left Menu area
                  Input  : Bunch of array
                  Output : Top Side Header
                  Date   : 27-1-2016
                 */
                if (empty($projrctheader)) {
                    $projrctheader = array();
                }
                echo Modules::run('Sidebar/projrctheader', $projrctheader);
                ?>
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
                    $this->load->view('Projectmanagement' . $main_content);
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
        <script src='<?= base_url() ?>uploads/custom/js/projectmanagement/moment.js'></script>
        <script src='<?= base_url() ?>uploads/dist/js/bootstrap-datetimepicker.js'></script>
        <link href='<?= base_url() ?>uploads/dist/css/bootstrap-datetimepicker.min.css' rel="stylesheet" type="text/css" />
        <script src='<?= base_url() ?>uploads/custom/js/jquery.blockUI.js'></script>
        <script type="text/javascript">
            //Check session
            $(document).ready(function() {
              window.onfocus = function() {
                  focused = true;
                 $.ajax({
                          url: "<?php echo base_url('Projectmanagement/Projectdashboard/check_project'); ?>",
                          type: "POST",
                          data: {oldid: $('#projects').val(),check_class:$('*').hasClass('add_record') ? 1 : "0"},
                          //dataType: "json",
                          success: function (d)
                          {
                              if (d == 1)
                              {
                                  window.location.href = window.location.href;
                              }
                          }
                      });
                 return false;
              };
              window.onblur = function() {
                  $.ajax({
                          url: "<?php echo base_url('Projectmanagement/Projectdashboard/check_project'); ?>",
                          type: "POST",
                          data: {'set_project':'1',oldid: $('#projects').val()},
                          //dataType: "json",
                          success: function (d)
                          {}
                      });
              };
            });
            $(function () {
                $('#start_date').datepicker({
                    autoclose: true
                })
                        .on('changeDate', function (selected) {
                            startDate = new Date(selected.date.valueOf());
                            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                            $('#due_date').datepicker('setStartDate', startDate);
                        });
                $('#due_date')
                        .datepicker({autoclose: true
                        })

                $('.chosen-select').chosen();
                $('.chosen-select-deselect').chosen({allow_single_deselect: true});

            });
        </script>
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
        <?php
        if (isset($js_content) && $js_content != "") {
            $this->load->view($js_content);
        }
        ?>

        <!-- Modal HTML -->
        <div id="ajaxModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Content will be loaded here from "remote.php" file -->
                </div>
            </div>
        </div>
    </body>
</html>
