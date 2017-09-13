<div class="whitebox recent_activities sortableDiv" id="SupportActivities">
                <h2 class="title-2"><?= lang('recent_act');?></h2>
                <ul class="activities-list">
                    <?php
                    foreach ($ticket_recent_data as $activity_data) {
                        /* pr($activity_data);
                          die('here'); */
                        ?>
                        <li> <a data-toggle="ajaxModal" aria-hidden="true" href="<?=base_url()?>Ticket/view_record/<?=$activity_data['ticket_id']?>" class="bluecol"><i class="fa fa-search"></i> <span class="blue-col"><?php echo $activity_data['activity_date']; ?></span></a>



                            <br/>
                            <?php echo $activity_data['firstname']; ?>&nbsp;<?php echo $activity_data['lastname']; ?>&nbsp;<?php echo $activity_data['activity']; ?>&nbsp;<b><?php echo $activity_data['ticket_subject']; ?> </b></li>

                    <?php } ?>
                </ul>
            </div> 
