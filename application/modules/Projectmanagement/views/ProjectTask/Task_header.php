<?php /*<div class="innersortablediv" id="Task_header" style="min-height: 200px">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 connectedSortable " id="headerTask" style="min-height: 200px" >
      <div class="">
        <?php
//    if (array_key_exists('sectionLeft', $widgets)) {
//        foreach ($widgets['sectionLeft'] as $views) {
//            if ($views == 'project_budget_count_view') {
//                echo $this->load->view('widgets/project_budget_count_view');
//                echo '<div class="clr"></div>';
//            }
//            if ($views == 'budget_spent') {
//                echo $this->load->view('widgets/budget_spent');
//                echo '<div class="clr"></div>';
//            }
//        }
//    }
//    
        ?>
        <?php
        if (array_key_exists('sectionLeft', $widgets)) {
            foreach ($widgets['sectionLeft'] as $views) {

                echo $this->load->view('widgets/' . $views);
               
            }
        } else if (empty($widgets['sectionRight'])) {
            echo "<div class='empty hidden sortableDiv'></div>";
        }
        ?>
          
      </div>
    </div>
    <div id="progress_div" class="col-xs-12 col-sm-12 col-md-9 col-lg-9"> 
      <!--  <div class="big-title pad_tb12">Half Way Done</div> -->
      <div class="text-center">
        <div class="col-md-2 col-xs-12 col-sm-12 text-center"><span>
          <?= lang('start_date') ?>
          <br>
          <?php
                    if (!empty($project_detail) && $project_detail[0]['start_date'] != '0000-00-00') {
                        echo configDateTime($project_detail[0]['start_date']);
                    }
                    ?>
          </span></div>
        <div class="col-md-8 col-xs-12 col-sm-12 text-center">
          <div class="progres">
            <div class="progres-bar progres-bar-success" role="progressbar" aria-valuenow="50"
                         aria-valuemin="0" aria-valuemax="100"
                         style="width:<?= !empty($project_per) ? $project_per : '' ?>%"> <span
                            class="project-text">
              <?= !empty($project_per) ? $project_per . '% of project completion' : '' ?>
              </span> </div>
          </div>
        </div>
        <div class="col-md-2 col-xs-12 col-sm-12 text-center"><span>
          <?= lang('due_date') ?>
          <br>
          <?php
                    if (!empty($project_detail) && $project_detail[0]['due_date'] != '0000-00-00') {
                        echo configDateTime($project_detail[0]['due_date']);
                    }
                    ?>
          </span></div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<div class="clr"></div>
*/ ?>