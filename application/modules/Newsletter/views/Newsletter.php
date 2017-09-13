<?php
defined ('BASEPATH') OR exit('No direct script access allowed');
?>

<?php 
$type = '';
if($newsletter_type == '1')
{
    $type = "(".lang('NEWSLETTER_TYPE_MAILCHIMP').")";
}else if($newsletter_type == '2')
{
    $type = "(".lang('NEWSLETTER_TYPE_CAMPAIGN_MONITOR').")";
}else if($newsletter_type == '3')
{
    $type = "(".lang('NEWSLETTER_TYPE_MOOSEND').")";
}else if($newsletter_type == '4')
{
    $type = "(".lang('NEWSLETTER_TYPE_GET_RESPONSE').")";
}

?>
<div class="row">
    <div class="col-md-6 col-sm-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url();?>"><?= lang('crm') ?></a></li>
            <li><a href="<?php echo base_url()."Marketingcampaign";?>"><?= lang('marketing_campaigns') ?></a></li>
            <li class="active"><?= lang ('NEWSLETTER')." ".$type; ?></li>
        </ul>
    </div>
    <!-- Search: Start -->
   
    <div class="col-xs-12 col-sm-6 col-md-3 pull-right text-right col-md-offset-3">
        <div class="row">
            <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#"><i
                        class="fa fa-gear fa-2x"></i></a></div>
            <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                <form id="searchForm" class="navbar-form navbar-left pull-right">
                    <div class="input-group">
                        <input type="text" placeholder="<?php echo lang('EST_LISTING_SEARCH_FOR');?>"  name="search_input" id="search_input"
                               class="form-control">
                    <span class="input-group-btn">
                        <button type="button" id="submit" name="submit" class="btn btn-default" title="<?php echo lang('search');?>"><i
                                class="fa fa-search fa-x"></i></button>
                        <button onclick="reset_data();" title="<?php echo lang('reset');?>" class="btn btn-default" type="reset"><i
                                class="fa fa-refresh fa-x"></i></button>
                    </span></div>
                    </form>
            </div>

        </div>

    </div> 
</div>
<div class="clr"></div>
<?php if ($this->session->flashdata('message')) { ?>
        <div class='alert alert-success text-center'> <?php echo $this->session->flashdata('message'); ?></div>
    <?php } ?>
    <?php if ($this->session->flashdata('error')) { ?>
        <div class='alert alert-danger text-center'> <?php echo $this->session->flashdata('error'); ?></div>
    <?php } ?>
<div class="clr"></div>

<div class="row">
    <div class="col-xs-12 col-md-6 no-left-pad">
        <h3 class="white-link"><?= lang ('NEWSLETTER')." ".$type; ?></h3>
    </div>
    <div class="col-xs-6 col-md-6 pull-right no-right-pad">
        
    </div>
</div>

<div class="whitebox" id="common_div">

    <?php
    
    if($newsletter_type == '1')
    {
         $this->load->view ('NewsletterAjaxList');
    }else if($newsletter_type == '2')
    {
         $this->load->view ('CampaignMonitorAjaxList');
    }else if($newsletter_type == '3')
    {
         $this->load->view ('MoosendAjaxList');
    }else if($newsletter_type == '4')
    {
         $this->load->view ('GetResponseAjaxList');
    }
    ?>
</div>
<div class="clr"></div>

<script type="text/javascript">
    $(document).ready(function() {
    oTable = $('#milestonetable1').DataTable({
     "columnDefs": [{
    "defaultContent": "-",
    "targets": "_all"
  }],"language": {
            "lengthMenu": "<?php echo lang('DATATABLES_RECORD_PER_PAGE'); ?>",
            "zeroRecords": "<?php echo lang('DATATABLES_NO_RECORD_FOUND');?>",
            "infoEmpty": "<?php echo lang('DATATABLES_NO_RECORD_AVAILABLE'); ?>",
            "paginate": {
                "previous": "<?php echo lang('prev');?>",
                "next": "<?php echo lang('next');?>"
            }
        },"fnDrawCallback": function(oSettings) {
        $(oSettings.nTableWrapper).find('.dataTables_paginate').show();
        if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
            $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
        }
        if(oSettings.fnRecordsDisplay() == 0){
            $('#milestonetable1 td.dataTables_empty').css("text-align","center");
        }
    }
});
    
    $('#search_input').val('');
} );
 $('#search_input').keyup(function(){
      oTable.search($(this).val()).draw() ;
      $('#milestonetable1 td.dataTables_empty').css("text-align","center");
});

function reset_data()
{
    oTable.search( '' ).columns().search( '' ).draw();
}

$('#submit').click(function() {
    var tmp_var = $('#search_input').val();
    oTable.search(tmp_var).draw() ;
});

</script>




