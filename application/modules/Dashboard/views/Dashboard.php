<?php
$leftClass = '';
$rightClass = '';
$lflag = 0;
$rflag = 0;
$positionArr = array('position-left-top' => 'widgetGraph', 'position-left-bottom' => 'widgetTabs', 'position-right-top' => 'widgetCalender', 'position-right-bottom' => 'widgetTasks');
//if (array_key_exists('sectionLeft', $widgets)) {
//    foreach ($widgets['sectionLeft'] as $views) {
//        if ($views == 'position-left-top') {
//            $lflag = 1;
//            //  $leftClass = 'col-md-8';
//            // $this->load->view($positionArr[$views]);
//        } else {
//            //   $leftClass = 'col-md-4';
//        }
//    }
//}
//if (array_key_exists('sectionRight', $widgets)) {
//    foreach ($widgets['sectionRight'] as $views) {
//        if ($views == 'position-left-top') {
//            $rflag = 1;
//            //$rightClass = 'col-md-8';
//            // $this->load->view($positionArr[$views]);
//        } else {
//            //$rightClass = 'col-md-4';
//        }
//    }
//    if ($lflag == 1) {
//        $leftClass = 'col-md-8';
//    } else {
//        $leftClass = 'col-md-4';
//    }
//    if ($rflag == 1) {
//        $rightClass = 'col-md-8';
//    } else {
//        $rightClass = 'col-md-4';
//    }
//}
?>

<div class="row">
    <div class="col-md-6 col-md-6">

        <?php echo $this->breadcrumbs->show(); ?>

    </div>
    <div class="col-xs-12 col-md-1 pull-right settings text-right" >
        <a class="pull-right" style="margin-left:15px;" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a>
        <a class="pull-right" title="<?php echo lang('reset_dashboard'); ?>"><i class="fa fa-refresh fa-2x"></i></a>
    </div>

    <div class="clr"></div>
</div>
<div class="row">
    <?php if ($this->session->flashdata('msg')) { ?>
    <?php echo $this->session->flashdata('msg'); ?>
    <?php } ?>
  
    <div class="col-md-12 col-md-8 connectedSortable"  id="sectionLeft" style="min-height: 300px">
        <?php
        if (array_key_exists('sectionLeft', $widgets)) {
            foreach ($widgets['sectionLeft'] as $views) {
                if (array_key_exists($views, $positionArr)) {
                    $this->load->view($positionArr[$views]);
                }
            }
        } else if (empty($widgets['sectionLeft'])) {
            echo "<div class='empty hidden sortableDiv'></div>";
        }
        ?>
        <!-- Nav tabs -->
        <?php //$this->load->view('widgetGraph');   ?>
        <?php // $this->load->view('widgetTabs'); ?>
        <div class="clr"></div>
    </div>
    <div class="col-md-12  col-md-4 connectedSortable" id="sectionRight" style="min-height: 300px">
        <?php
        if (array_key_exists('sectionRight', $widgets)) {
            foreach ($widgets['sectionRight'] as $views) {
                if (array_key_exists($views, $positionArr)) {
                    $this->load->view($positionArr[$views]);
                }
            }
        } else if (empty($widgets['sectionRight'])) {
            echo "<div class='empty hidden sortableDiv'></div>";
        }
        ?>

        <?php //$this->load->view('widgetTasks');  ?>
    </div>
    <div class="clr"></div>
</div>
<script>
    function opportunities()
    {
        var url_opportunities = '<?php echo base_url() . "Dashboard/worth_opportunities" ?>';
        $.ajax({
            type: "POST",
            url: url_opportunities,
            dataType: 'json',
            data: {},
            success: function (res)
            {
                $('#worth_data').html(res.symbol + res.data_worth);
                $('#worth_opportunities').html(res.worth_opportunitie);
            }
        });
    }
    function salestarget()
    {
        var url_opportunities = '<?php echo base_url() . "Dashboard/salestarget" ?>';
        $.ajax({
            type: "POST",
            url: url_opportunities,
            dataType: 'json',
            data: {},
            success: function (res)
            {
                $('#target_data').html(res.symbol + res.target_data);
            }
        });
    }

    function won_opportunities()
    {
        var url_opportunities = '<?php echo base_url() . "Dashboard/won_opportunities" ?>';
        $.ajax({
            type: "POST",
            url: url_opportunities,
            dataType: 'json',
            data: {},
            success: function (res)
            {
                $('#won_data').html(res.symbol + res.data_won);
                $('#won_opportunities').html(res.won_opportunitie);
            }
        });
    }

    $(document).ready(function () {
		/*for enjoyhint demo start*/
		var enjoyhint_instance = new EnjoyHint({
			onStart: function () {
				//alert('first');
			},
			onEnd: function () {
										//alert('aa');
					//$("#sales").trigger("click");
					window.location.href="<?php echo base_url('/SalesOverview')?>";
					//alert('hi');
					
					//$('#side-collapse').removeClass('side-collapse in');
					
					//$('#side-collapse').css('left', '200px')
					
			}
		   
		});
		
		var enjoyhint_script_steps = [{
			
			
			'next .navbar-brand' : 'WelCome to Blazedesk',
			'shape': 'circle',
			/* selector:'.navbar-brand',//jquery selector
				event:'click',
				description:'Click on this btn',*/
				
			},
			{
			
			'next .pull-left' : 'Here You Can Start With Left Menu',
				
			
			},
			{
			
			'next #sales' : 'You Can Start Your Lead From This Link',
			onBeforeStart:function(){
				  //$(".ticketadd").trigger("click");
				  $('#side-collapse').addClass('addclsleft');
				}	
			
			},
			
			
			];
			enjoyhint_instance.set(enjoyhint_script_steps);
			enjoyhint_instance.run();
		
		/*for enjoyhint demo end*/
        opportunities();
        salestarget();
        getDocuments();
        won_opportunities();

    });

</script>
<script>

    $(document).ready(function () {
        var returnUrl = $.trim($('#returnUrl').val());
        var path = $.trim($('#path').val());
        $('body').delegate('a.directory', 'click', function (e) {
            e.preventDefault();
            //mediagalleryPopupData
            $('.mediagalleryPopupData').load($(this).attr('href'));
        });
        $('body').delegate('#button-parent', 'click', function (e) {
            e.preventDefault();
            $('.mediagalleryPopupData').load($(this).attr('href'));
        });
        $('body').delegate('#button-refresh', 'click', function (e) {
            e.preventDefault();
            $('.mediagalleryPopupData').load($(this).attr('href'));
        });
    });

</script>
<script>
    function showPreview(elurl)
    {
        //directory
        $('#previewImg').attr('src', elurl);
        $('#imgviewpopup').modal('show');
    }
//    $('#imgviewpopup').on('hidden.bs.modal', function () {
//        $('body').addClass('modal-open');
//    });
</script>
