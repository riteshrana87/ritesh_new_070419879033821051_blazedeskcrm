<?php
$opportunityArray = array();
if (count($prospect_data) > 0) {
    foreach ($prospect_data as $row) {
        $opportunityArray[$row['status_type']][] = $row;
    }
}
?>
<div id="heads" class="col-xs-12 col-sm-12">
    <div id="<?php echo lang('new'); ?>" class="oppHead text-center pad-6 col-sm-3">
        <b><?php echo lang('new'); ?></b>
    </div>
    <div id="<?php echo lang('prospect'); ?>" class="oppHead text-center pad-6 col-sm-3">
        <b><?php echo lang('prospect'); ?></b>
    </div>
    <div id="<?php echo lang('won'); ?>" class="oppHead text-center pad-6 col-sm-3">
        <b> <?php echo lang('won'); ?></b>
    </div>
    <div id="<?php echo lang('lost'); ?>" class="oppHead text-center pad-6 col-sm-3">
        <b>  <?php echo lang('lost'); ?></b>
    </div>
</div>
<?php if (count($prospect_data) > 0) { ?>

    <div id="proposalType" class="col-xs-12 col-sm-12 verticl-scroll row">
        <div id="<?php echo lang('new'); ?>" data-dataType="2" class="col-sm-3" style="min-height: 500px">
            <?php
            if (array_key_exists(2, $opportunityArray)) {
                foreach ($opportunityArray[2] as $row) {
                    ?>
                    <div class="oppSort" data-id="<?php echo $row['prospect_id']; ?>"><div class="gray-borderbox" data-dataType="<?php echo $row['status_type']; ?>" data-type="<?php echo lang('new'); ?>" data-id="<?php echo $row['prospect_id']; ?>"> <b><?php echo $row['prospect_name']; ?></b> <span class="pull-right"><a href="<?= base_url('Lead/viewdata/' . $row['prospect_id']) ?>" title="<?= $this->lang->line('view') ?>" class="edit_contact" ><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;<a href="#"><i class="fa fa-gear bluecol"></i></a></span> <span class="clr"></span><br/>
                            <?php echo $row['prospect_auto_id']; ?><br/>
                            Worth $<?php echo $row['estimate_prospect_worth']; ?><br/>
                            <br/>
                            <input type="text" class="form-control" value="<?php echo $row['creation_date']; ?>" disabled/>
                        </div>
                        <div class="clr"></div></div>
                    <?php
                }
            } else {
                ?>
                <div class="oppSort" style="min-height:122px"></div>
            <?php } ?>
        </div>
        <div id="<?php echo lang('prospect'); ?>" data-dataType="1" class="col-sm-3" style="min-height: 500px">
            <?php
            if (array_key_exists(1, $opportunityArray)) {
                foreach ($opportunityArray[1] as $row) {
                    ?>
                    <div style="min-height:122px" class="oppSort" data-id="<?php echo $row['prospect_id']; ?>"><div class="gray-borderbox" data-dataType="<?php echo $row['status_type']; ?>" data-type="<?php echo lang('prospect'); ?>" data-id="<?php echo $row['prospect_id']; ?>"> <b><?php echo $row['prospect_name']; ?></b> <span class="pull-right"><a href="<?= base_url('Opportunity/viewdata/' . $row['prospect_id']) ?>" title="<?= $this->lang->line('view') ?>" class="edit_contact" ><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;<a href="#"><i class="fa fa-gear bluecol"></i></a></span> <span class="clr"></span><br/>
                            <?php echo $row['prospect_auto_id']; ?><br/>
                            Worth $<?php echo $row['estimate_prospect_worth']; ?><br/>
                            <br/>
                            <input type="text" class="form-control" value="<?php echo $row['creation_date']; ?>" disabled/>
                        </div>
                        <div class="clr"></div></div>
                    <?php
                }
            } else {
                ?>
                <div class="oppSort" style="min-height:122px" ></div>
            <?php } ?>
        </div>
        <div id="<?php echo lang('won'); ?>" data-dataType="3" class="col-sm-3" style="min-height: 500px">
            <?php
            if (array_key_exists(3, $opportunityArray)) {
                foreach ($opportunityArray[3] as $row) {
                    ?>
                    <div style="min-height:122px" class="oppSort" data-id="<?php echo $row['prospect_id']; ?>"><div class="gray-borderbox" data-dataType="<?php echo $row['status_type']; ?>" data-type="<?php echo lang('won'); ?>" data-id="<?php echo $row['prospect_id']; ?>"> <b><?php echo $row['prospect_name']; ?></b> <span class="pull-right"><a href="<?= base_url('Account/viewdata/' . $row['prospect_id']) ?>" title="<?= $this->lang->line('view') ?>" class="edit_contact" ><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;<a href="#"><i class="fa fa-gear bluecol"></i></a></span> <span class="clr"></span><br/>
                            <?php echo $row['prospect_auto_id']; ?><br/>
                            Worth $<?php echo $row['estimate_prospect_worth']; ?><br/>
                            <br/>
                            <input type="text" class="form-control" value="<?php echo $row['creation_date']; ?>" disabled/>
                        </div>
                        <div class="clr"></div></div>
                    <?php
                }
            } else {
                ?>
                <div class="oppSort" style="min-height:122px" ></div>
            <?php } ?>
        </div>
        <div id="<?php echo lang('lost'); ?>" data-dataType="4" class="col-sm-3" style="min-height: 500px">
            <?php
            if (array_key_exists(4, $opportunityArray)) {
                foreach ($opportunityArray[4] as $row) {
                    ?>
                    <div style="min-height:122px" class="oppSort" ><div class="gray-borderbox" data-type="<?php echo lang('lost'); ?>" data-dataType="<?php echo $row['status_type']; ?>" data-id="<?php echo $row['prospect_id']; ?>"> <b><?php echo $row['prospect_name']; ?></b> <span class="pull-right"><a href="<?= base_url('Account/viewLostClient/' . $row['prospect_id']) ?>" title="<?= $this->lang->line('view') ?>" class="edit_contact" ><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;<a href="#"><i class="fa fa-gear bluecol"></i></a></span> <span class="clr"></span><br/>
                            <?php echo $row['prospect_auto_id']; ?><br/>
                            Worth $<?php echo number_format($row['estimate_prospect_worth'],2); ?><br/>
                            <br/>
                            <input type="text" class="form-control" value="<?php echo configDateTime($row['creation_date']); ?>" disabled/>
                        </div>
                        <div class="clr"></div></div>
                    <?php
                }
            } else {
                ?>
                <div class="oppSort" style="min-height:122px" ></div>
            <?php } ?>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#proposalType .oppSort").sortable({
                connectWith: ".oppSort",
                axis: 'x',
                cursor: 'move',
                // containment: 'parent',
                tolerance: 'pointer', // this is the important bit
                receive: function (event, ui) {

                    var id = ui.item.attr('data-id');
                    var currtype = ui.item.attr('data-type');
                    var dataType = $(this).parent('div').attr('data-dataType');
                    var type = $(this).parent('div').attr('id');
                    if ((type == 'Lead' && currtype == 'Won') || (type == 'Lead' && currtype == 'Lost'))
                    {
                        return false;
                    }
                    $.ajax({
                        url: "<?php echo base_url('SalesOverview/update_status'); ?>",
                        type: 'POST',
                        data: {'id': id, 'type': type, 'currtype': currtype, 'dataType': dataType},
                        success: function (res)
                        {
                            if (res == 'done')
                            {
                                //                                alert(type);
                                //                                $('#' + id).data('data-type', type);
                                //                                alert(type);
                                //                                $('#' + id).data('data-datatype', dataType);
                                return false;
                            }
                        },
                        error: function ()
                        {
                            console.log('Error in call');
                        }

                    });
                }
            }).disableSelection();
        });

    </script>
<?php } else { ?>
    <div id="proposalType" class="col-xs-12 col-sm-12 verticl-scroll row">
        <div class="col-sm-12">
            <h6 class="text-center"><?php echo lang('common_no_record_found'); ?></h6>
        </div>
    </div>
    <?php
}?>