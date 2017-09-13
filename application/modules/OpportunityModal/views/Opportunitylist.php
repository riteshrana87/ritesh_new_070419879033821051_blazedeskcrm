<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$formAction = 'insertdata';
$path = $opportunity_view . '/' . $formAction;
?>

<!--<button type="button" class="btn btn-info btn-lg add_camp" data-toggle="modal" data-target="#createSalesCampaign"><?= $this->lang->line('create_sales_campaign') ?></button>-->

<div class="row">

    <div class="clr"></div>

    <div class="col-xs-12 col-md-12">
        <h3><?= $this->lang->line('opportunities'); ?></h3>

        <br>
        <div class="clr">&nbsp;</div>
        <?php echo $this->session->flashdata('msg'); ?>
        <div class="whitebox">
            <div class="table table-responsive" id="postList">
                <table id="datatable1" class="table table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th><?= $this->lang->line('name') ?></th>
                            <th><?= $this->lang->line('id') ?></th>
                            <th><?= $this->lang->line('status') ?></th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th><?= $this->lang->line('name') ?></th>
                            <th><?= $this->lang->line('id') ?></th>
                            <th><?= $this->lang->line('status') ?></th>
                            <th> </th>
                        </tr>
                    </tfoot>
                    <tbody id="postList">
                        <?php if (isset($prospect_data) && count($prospect_data) > 0) { ?>
                            <?php foreach ($prospect_data as $data) { ?>
                                <tr>
                                    <td><?php echo $data['prospect_name']; ?></td>
                                    <td><?php echo $data['prospect_auto_id']; ?></td>
                                    <td><?php echo $data['status']; ?></td>
                                    <td> 

                                        <button class="edit_opportunity" id="edit_opportunity" type="button" data-id=<?php echo $data['prospect_id']; ?>  >
                                            <i class="fa fa-pencil greencol"></i>
                                        </button>
                                        &nbsp;&nbsp; 
                                        <a href="<?php echo base_url($opportunity_view . '/deletedata?id=' . $data['prospect_id']); ?>">
                                            <i class="fa fa-remove redcol"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>           
                    </tbody>

                </table>
            </div>

            <div class="clr"></div>
        </div>
        <div class="clr"></div>
        <div class="col-sm-12">
            <div class="col-sm-2">
                <div class=" whitebox text-center small-white-box2"><br><a href="#" class="add_opportunity" data-toggle="modal" data-target="#newOpportunity"><?= $this->lang->line('create_new_opportunity') ?></a></div>
            </div>
            <div class="col-sm-2">
                <div class=" whitebox text-center small-white-box2"><br> <a href="<?php echo base_url() . 'Prospect'; ?>">Manage Opportunities</a></div>
            </div>
            <div class="col-sm-2 ">
                <div class=" whitebox text-center small-white-box2"> <br><a href="<?php echo base_url() . 'Lead'; ?>">Create New Lead</a></div>
            </div>
            <div class="col-sm-2 ">
                <div class=" whitebox text-center small-white-box2"> <br><a href="<?php echo base_url() . 'Lead'; ?>">Manage Leads</a></div>
            </div>
            <div class="col-sm-2">
                <div class=" whitebox text-center small-white-box2"> <br><a href="<?php echo base_url() . 'Client'; ?>">Create New Client</a></div>
            </div>
            <div class="col-sm-2">
                <div class=" whitebox text-center small-white-box2"> <br><a href="<?php echo base_url() . 'Client'; ?>">Manage Clients</a></div>
            </div>
             

        </div>
        <div class="clr"></div>
        

        <div class="clr"></div>

    </div>

</div>