<div id="position-left-top" class="sortableDiv">
    <div class="whitebox">
        <div class="clr"></div>
        <div class="col-md-12 col-md-6">
            <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
        <div class="col-md-12 col-md-6">
            <div class="text-right pull-right">

                <form class="navbar-form navbar-left">
                    <div class="form-group"> <?php echo lang('compare_to'); ?> :

                        <?php
                        if (count($logged_user) > 0 && (date('m', strtotime($logged_user[0]['created_date'])) >= date('m'))) {
                            ?>
                            <select class="form-control" id="prevMonth" onchange="getComparisionData(this.value);">
                                <option value=""><?php echo lang('previous_month'); ?></option>
                            </select>
                        <?php } else { ?>
                            <select class="form-control" id="prevMonth" onchange="getComparisionData(this.value);">
                                <option value=""><?php echo lang('previous_month'); ?></option>
                                <?php
                                for ($m = date('m'); $m >= date('m', strtotime($logged_user[0]['created_date'])); $m--) {
                                    ?>
                                    <?php
                                    if ($m != date('m')) {
                                        ?>
                                        <option value="<?php echo date('m', mktime(0, 0, 0, $m)); ?>" ><?php echo date('M', mktime(0, 0, 0, $m)); ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        <?php } ?>
                    </div>
                </form>

            </div>
            <div class="clr"></div>
            <div class="col-xs-6 col-md-6 col-lg-6 col-sm-6 text-center">
                <div id="pie1" ></div>
            </div>
            <div class="col-xs-6 col-md-6 col-lg-6 col-sm-6 text-center">
                <div id="pie2" ></div>
            </div>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div class="overview">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 no-left-pad">
                <div class=" whitebox text-center small-white-box1 first-small-box"> <span class="bluecol"><?php echo $count_contacts; ?></span> <br/>
                    <?php echo lang('new_contacts'); ?> </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <div class=" whitebox text-center small-white-box2"><span class="bluecol" id="worth_opportunities"></span> <br/>
                    <?php echo lang('new_opprttunity_worth'); ?> <br/>
                    <span class="bluecol" id="worth_data"></span> </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <div class=" whitebox text-center small-white-box1"> <span class="bluecol" id="target_data">0</span> <br/>
                    <?php echo lang('sales_target'); ?></div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 no-right-pad">

                <div class="whitebox text-center small-white-box2"> <span class="bluecol" id="won_opportunities"></span><br/>
                    <?php echo lang('won_opportunity'); ?> <br/>
                    <span class="bluecol" id="won_data">
                    </span> </div>
            </div>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
</div>

