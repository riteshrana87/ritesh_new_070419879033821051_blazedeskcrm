<?php
//$courrency_symbol='';
$sales_by_user = 0;
if (isset($salesTargetProgressGraph['sales_target'][0]['currency_symbol'])) {
    // $courrency_symbol = $salesTargetProgressGraph['sales_target'][0]['currency_symbol'];
    $sales_by_user = $salesTargetProgressGraph['converted_amount'];
}
?>
<div class="col-md-12 col-md-6 connectedSortable" style="min-height:300px">

    <div class="sortableDiv" id="progressView">
        <div id="progress_div" class="text-center mar-tb5" >
            <div class="col-lg-2 col-sm-2" >
                <p>
                    <?php if (!empty($salesTargetProgressGraph['sales_target'])) { ?>
                        <?php
                        if (isset($salesTargetProgressGraph['sales_target'][0]['currency_symbol'])) {
                            echo $salesTargetProgressGraph['symbol'] . " 0";
                        }
                        ?>
                        <?php
                    } else {
                        echo $salesTargetProgressGraph['symbol'] . "0";
                    }
                    ?>
                </p>
            </div>
            <div class="progres col-lg-8 col-sm-8 nopadding">
                <div class="progres-bar progres-bar-success" role="progressbar" aria-valuenow="50"
                     aria-valuemin="0" aria-valuemax="100"
                     <?php
                     if ($salesTargetProgressGraph['sales_percentage'] != "" && $salesTargetProgressGraph['sales_percentage'] > 100) {
                         $salesTargetProgressGraph['sales_percentage'] = 100;
                     } else {
                         $salesTargetProgressGraph['sales_percentage'] = $salesTargetProgressGraph['sales_percentage'];
                     }
                     ?>
                     style="width:<?= !empty($salesTargetProgressGraph['sales_percentage']) ? $salesTargetProgressGraph['sales_percentage'] : '' ?>%"> </div>
                <span class="project-text-cust">
                    <?php
                    if (!empty($salesTargetProgressGraph['sales_percentage']) && $sales_by_user != "") {
                        echo lang('realised_sales') . " " . $salesTargetProgressGraph['symbol'] . ' ' . $sales_by_user;
                        ?>
                        <?php
                    } else {
                        echo lang('realised_sales') . " " . $salesTargetProgressGraph['symbol'] . ' 0';
                    }
                    ?>
                </span> </div>
            <div class=" col-lg-2 col-sm-2" >
                <p>
                    <?php if (!empty($salesTargetProgressGraph['sales_target'])) { ?>
                        <?php
                        if (isset($salesTargetProgressGraph['sales_target'][0]['currency_symbol'])) {
                            echo $salesTargetProgressGraph['symbol'] . " " . $salesTargetProgressGraph['converted_amount_target'];
                        }
                        ?>
                        <?php
                    } else {
                        echo $salesTargetProgressGraph['symbol'] . " 0";
                    }
                    ?>
                </p>
            </div>
        </div>
        <!--  <p class="big-title"><?= lang('YOU_ARE_AWESOME') ?></p> --> 
        <!--  <p class="text-center mar-tb5"><img src="<?php echo base_url('uploads/images/graph3.png'); ?>" class="graph-image" alt=""/></p> --> 

        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="white-label">
                    <p class="label-font greencol text-center"><?php echo $count_clients . ' ' . lang('new_clients'); ?> </p>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="white-label">
                    <p class="label-font orangecol text-center"><?php echo $count_opportunities . ' ' . lang('opportunities'); ?> </p>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="white-label">
                    <p class="label-font redcol text-center"><?php echo $count_lost_clients . ' ' . lang('lost_clients'); ?> </p>
                </div>
            </div>
            <div class="clr"></div>
        </div>
    </div>
</div>