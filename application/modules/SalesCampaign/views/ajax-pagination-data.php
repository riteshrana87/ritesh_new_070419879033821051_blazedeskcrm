<table id="datatable1" class="table table-striped" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th><?=$this->lang->line('id')?></th>
              <th><?=$this->lang->line('name')?></th>
              <th><?=$this->lang->line('START_DATE')?></th>
              <th><?=$this->lang->line('END_DATE')?></th>
              <th> </th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th><?=$this->lang->line('id')?></th>
              <th><?=$this->lang->line('name')?></th>
              <th><?=$this->lang->line('START_DATE')?></th>
              <th><?=$this->lang->line('END_DATE')?></th>
              <th> </th>
            </tr>
          </tfoot>
          <tbody id="postList">
              <?php if(isset($campaign_info) && count($campaign_info) > 0 ){ ?>
			  <?php foreach($campaign_info as $data){ ?>
				<tr>
				  <td><?php echo $data['campaign_id'];?></td>
				  <td><?php echo $data['campaign_name'];?></td>
                                 <td><?php echo $data['start_date'];?></td>
                                 <td><?php echo $data['end_date'];?></td>
				 <td>
                                    
                                     <a href="#" class="edit_camp"  data-toggle="modal" data-target="#createSalesCampaign" data-id="<?php echo $data['campaign_id'];?>"><i class="fa fa-pencil greencol"></i></a>&nbsp;&nbsp; <a href="<?php echo base_url($sales_view.'/deletedata?id='.$data['campaign_id']);?>" onclick="return confirm('Are you sure you want to delete this campaign?','yes','no');"><i class="fa fa-remove redcol"></i></a></td>
				</tr>
			  <?php }?>
			<?php }?>
                  <?php echo $this->ajax_pagination->create_links(); ?>              
          </tbody>
           
        </table>