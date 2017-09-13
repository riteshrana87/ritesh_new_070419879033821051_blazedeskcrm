<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = !empty($editRecord)?'updatedata':'insertdata'; 

$path = $crnt_view.'/'.$formAction;
?>
   <div class="row">
   
  <div class="clr"></div>
  
  <div class="col-xs-12 col-md-12">
    <?php echo $this->session->flashdata('msg'); ?>
    <div class="whitebox">
      <div class="table table-responsive">
        <table id="datatable1" class="table table-striped" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th><?=$this->lang->line('perms_name')?></th>                            
              <th><?=$this->lang->line('perms_defination')?></th>
              <th></th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th><?=$this->lang->line('perms_name')?></th>                         
              <th><?=$this->lang->line('perms_defination')?></th>
              <th></th>
            </tr>
          </tfoot>
         <tbody>
        <?php if(isset($information) && count($information) > 0 ){ ?>
        <?php foreach($information as $data){ ?>
        <tr>
          <td><?php echo $data['name'];?></td>        
          <td><?php echo $data['defination'];?></td>
          <td><a href="<?php echo base_url($crnt_view.'/edit?id='.$data['id']);?>"><i class="fa fa-pencil greencol"></i></a>&nbsp;&nbsp; <a href="<?php echo base_url($crnt_view.'/deletedata?id='.$data['id']);?>"><i class="fa fa-remove redcol"></i></a></td>
        </tr>
        <?php } ?>
      <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
  <div class="clr"></div>
 
  <div class="col-lg-2 col-md-2 col-xs-4">
    <div class=" whitebox text-center small-white-box1 first-small-box">
      <div class="link-style1"><a href="<?php echo base_url($crnt_view.'/add');?>"><?=$this->lang->line('add_role')?></a> </div>
    </div>
  </div>
  <div class="clr"></div>
 
</div>
