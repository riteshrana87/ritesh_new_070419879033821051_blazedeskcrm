<div class="whitebox sortableDiv" id="TicketTable">
                <div class="table table-responsive">
                    <?php
                    $ticketArray = array();
                    if (count($ticket_data) > 0) {
                        foreach ($ticket_data as $tdata) {
                            $ticketArray[$tdata['status_name']][] = $tdata;
                        }
                        /* pr($ticketArray);
                          die('here is the code'); */
                    }
                    ?>

                    <div id="heads" class="col-xs-12 col-sm-12">
                        <div id="<?php echo lang('new'); ?>" class="oppHead text-center pad-6 col-sm-3">
                            <b><?php echo lang('new'); ?></b>
                        </div>
                        <div id="<?php echo lang('assigned'); ?>" class="oppHead text-center pad-6 col-sm-3">
                            <b><?php echo lang('assigned'); ?></b>
                        </div>
                        <div id="<?php echo lang('on_hold'); ?>" class="oppHead text-center pad-6 col-sm-3">
                            <b> <?php echo lang('on_hold'); ?></b>
                        </div>
                        <div id="<?php echo lang('completed_status'); ?>" class="oppHead text-center pad-6 col-sm-3">
                            <b>  <?php echo lang('completed_status'); ?></b>
                        </div>
                    </div>
                    <div id="proposalType" class="col-xs-12 col-sm-12 bd-scroll">
                        <div id="1" data-dataType="1" class="col-xs-3 col-sm-3 col-md-3" style="min-height: 500px;border:1px solid #FFF;">
                            <div></div>  <?php
                            if (array_key_exists('New', $ticketArray)) {
                                foreach ($ticketArray['New'] as $tdata) { //pr($tdata);
                                    ?>
		    
                                    <div id="oppSort" class="oppSort" style="min-height:160px;border:1px solid white;">
                                        <div data-id="<?php echo $tdata['ticket_id']; ?>" class="a">
                                            <div class="gray-borderbox2" > <b><?php echo lang('ticket');?> # <?php echo $tdata['ticket_subject']; ?></b> <br/>
								                   <?php if(!empty($tdata['ticket_desc'])){
							   if(strlen($tdata['ticket_desc']) > 50) {
									echo substr($tdata['ticket_desc'],0, 40).'...';
													   }else {
														   echo $tdata['ticket_desc'];
													   }
													}   ?>

												<br/>
                                                <a data-toggle="ajaxModal" aria-hidden="true" href="<?=base_url()?>Ticket/view_record/<?=$tdata['ticket_id']?>" class="bluecol"><i class="fa fa-search"></i> <?php echo lang('view');?></a> </div>
                                            <div class="white-borderbox2" ><?php echo lang('created');?> : <?php echo $tdata['created_date']; ?> <br/>
                                               <?php echo lang('due_date');?>  : <?php echo $tdata['due_date']; ?> <br/>
                                                <a href="#"><i class="bluecol fa fa-user"></i> <b><?php echo $tdata['firstname'] . $tdata['lastname']; ?></b></a> <br/>
                                                <div class="red-label <?php echo $tdata['ticket_id']; ?>"><?php echo lang($tdata['status_name']); ?></div> </div>

                                            <div class="clr"></div></div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="oppSort"  style="min-height: 500px"></div>
                            <?php } ?>
                        </div>
                        <div id="5" data-dataType="2" class="col-xs-3 col-sm-3 col-md-3" style="min-height: 500px;border:1px solid #FFF;">
                            <div></div>	<?php
                            if (array_key_exists('Assigned', $ticketArray)) {
                                foreach ($ticketArray['Assigned'] as $tdata) {
                                    ?>

                                    <div id="oppSort" class="oppSort" style="min-height:160px;border:1px solid white;">
                                        <div data-id="<?php echo $tdata['ticket_id']; ?>" class="b">
                                            <div class="gray-borderbox2" > <b><?php echo lang('ticket');?> # <?php echo $tdata['ticket_subject']; ?></b> <br/>
                                                 <?php if(!empty($tdata['ticket_desc'])){
													   if(strlen($tdata['ticket_desc']) > 50) {
														   echo substr($tdata['ticket_desc'],0, 40).'...';
													   }else {
														   echo $tdata['ticket_desc'];
													   }
													}   ?> <br/>
                                                <a data-toggle="ajaxModal" aria-hidden="true" href="<?=base_url()?>Ticket/view_record/<?=$tdata['ticket_id']?>" class="bluecol"><i class="fa fa-search"></i> <?php echo lang('view');?></a> </div>
                                            <div class="white-borderbox2" > created<?php echo lang('ticket');?> : <?php echo $tdata['created_date']; ?> <br/>
                                                <?php echo lang('due_date');?> : <?php echo $tdata['due_date']; ?> <br/>
                                                <a href="#"><i class="bluecol fa fa-user"></i> <b><?php echo $tdata['firstname'] . $tdata['lastname']; ?></b></a> <br/>
                                                <div class="green-label <?php echo $tdata['ticket_id']; ?>"><?php echo lang($tdata['status_name']); ?></div> </div>

                                            <div class="clr"></div></div></div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="oppSort" style="min-height: 500px" ></div>
                            <?php } ?>
                        </div>
                        <div id="3" data-dataType="3" class="col-xs-3 col-sm-3 col-md-3" style="min-height: 500px;">
                            <div></div>  <?php
                            if (array_key_exists('On Hold', $ticketArray)) {
                                foreach ($ticketArray['On Hold'] as $tdata) {
                                    ?>
                                    <div id="oppSort" class="oppSort" style="min-height:160px;border:1px solid white;">
                                        <div data-id="<?php echo $tdata['ticket_id']; ?>" class="c">
                                            <div class="gray-borderbox2" > <b><?php echo lang('ticket');?> # <?php echo $tdata['ticket_subject']; ?></b> <br/>
                                                 <?php if(!empty($tdata['ticket_desc'])){
													   if(strlen($tdata['ticket_desc']) > 50) {
														   echo substr($tdata['ticket_desc'],0, 40).'...';
													   }else {
														   echo $tdata['ticket_desc'];
													   }
													}   ?> <br/>
                                                <a data-toggle="ajaxModal" aria-hidden="true" href="<?=base_url()?>Ticket/view_record/<?=$tdata['ticket_id']?>" class="bluecol"><i class="fa fa-search"></i> <?php echo lang('view');?></a> </div>
                                            <div class="white-borderbox2" > <?php echo lang('created');?> : <?php echo $tdata['created_date']; ?> <br/>
                                                <?php echo lang('due_date');?> : <?php echo $tdata['due_date']; ?> <br/>
                                                <a href="#"><i class="bluecol fa fa-user"></i> <b><?php echo $tdata['firstname'] . $tdata['lastname']; ?></b></a> <br/>
                                                <div id="orange-label" class="orange-label <?php echo $tdata['ticket_id']; ?>"><?php echo lang($tdata['status_name']); ?></div> </div>
                                            <div class="clr"></div></div></div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="oppSort" style="min-height: 500px" ></div>
                            <?php } ?>
                        </div>
                        <div id="4" data-dataType="4" class="col-xs-3 col-sm-3 col-md-3" style="min-height: 500px;border:1px solid #FFF;">
                            <?php
                            if (array_key_exists('Completed', $ticketArray)) {
                                foreach ($ticketArray['Completed'] as $tdata) {
                                    ?>
                                    <div id="oppSort" class="oppSort" style="min-height:160px;border:1px solid white;">
                                        <div data-id="<?php echo $tdata['ticket_id']; ?>" class="d">
                                            <div class="gray-borderbox2" > <b><?php echo lang('ticket');?> # <?php echo $tdata['ticket_subject']; ?></b> <br/>
                                                <?php if(!empty($tdata['ticket_desc'])){
													   if(strlen($tdata['ticket_desc']) > 50) {
														   echo substr($tdata['ticket_desc'],0, 40).'...';
													   }else {
														   echo $tdata['ticket_desc'];
													   }
													}   ?> <br/>
                                                <a data-toggle="ajaxModal" aria-hidden="true" href="<?=base_url()?>Ticket/view_record/<?=$tdata['ticket_id']?>" class="bluecol"><i class="fa fa-search"></i> <?php echo lang('view');?></a> </div>
                                            <div class="white-borderbox2" > <?php echo lang('created');?> : <?php echo $tdata['created_date']; ?> <br/>
                                                <?php echo lang('due_date');?> : <?php echo $tdata['due_date']; ?> <br/>
                                                <a href="#"><i class="bluecol fa fa-user"></i> <b><?php echo $tdata['firstname'] . $tdata['lastname']; ?></b></a> <br/>
                                                <div class="blue-label <?php echo $tdata['ticket_id']; ?>"><?php echo lang($tdata['status_name']); ?></div> </div>
                                            <div class="clr"></div></div></div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="oppSort" style="min-height: 500px" ></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>