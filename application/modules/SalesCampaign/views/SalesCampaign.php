<div class="row">
    <div class="col-md-6 col-md-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url();?>">CRM</a></li>
            <li><a href="<?php echo base_url().'SalesCampaign';?>">Marketing campaigns</a></li>
        </ul>
    </div>
    <div class="col-md-6 col-md-6 text-right">
        <div class="pull-right settings"> <a href="#"><i class="fa fa-gear fa-2x"></i></a> </div>
        <div class="pull-right search-top">
            <div class="navbar-form navbar-left">
                <div class="form-group">
                    <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                    <input type="text" name="searchtext" id="searchtext" class="form-control" placeholder="" aria-controls="example1" value="<?=!empty($searchtext)?$searchtext:''?>">
                </div>
                <button onclick="data_search('changesearch')" class="fa fa-search btn btn-default"  title="Search"><?=$this->lang->line('common_search_title')?> </button>
            </div>
        </div>
    </div>
    <div class="clearfix visible-xs-block"></div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <h3 class="white-link">Active Campaign Overview</h3>
           </div>
            <div class="col-xs-12 col-md-6 pull-right">
                <a href="<?php echo base_url().'Campaigngroup';?>" class="btn btn-white pull-right" ><?=$this->lang->line('CAMPAIGN_GROUP')?></a>
            </div>
                 <div class="clr"></div>
        </div>

        <div class="whitebox" id="common_div">
            <?=$this->load->view('SalesCampaign/ajax_list.php','',true);?>
            <div class="clr"></div>
        </div>
          <div class="clr"></div>
          <div class="row">
            <div class="col-md-2  col-xs-12 col-new-5th">
                <div class="text-center">
                    <?php  if(checkPermission('SalesCampaign','add')){ ?><a href="<?php echo base_url().'SalesCampaign/add_record';?>" data-toggle="ajaxModal" title="<?=lang('create')?>" class="btn btn-white pull-right" ><?=$this->lang->line('create_sales_campaign')?></a><?php }?>
                </div>
            </div>
            <div class="col-md-2  col-xs-12 col-new-5th">
                <div class="text-center">
                    <a href="<?php echo base_url().'SalesCampaign';?>" class="btn btn-white full-width">Manage campaigns</a>
                </div>
            </div>
            <div class="col-md-2  col-xs-12 col-new-5th">
                <div class="text-center">
                    <a href="<?php echo base_url().'RequestBudget';?>" class="btn btn-white full-width">Request Budget </a>
                </div>
            </div>
            <div class="col-md-2  col-xs-12 col-new-5th">
                <div class="text-center">
                    <a href="<?php echo base_url().'CampaignReport';?>" class="btn btn-white full-width">Generate Report</a>
                </div>
            </div>
            <div class="col-md-3  col-xs-12 col-new-5th no-right-pad">
                <div class="text-center">
                    <a href="#" class="btn btn-white full-width">Campaign archive </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <div class="socialbox">
            <h3 class="white-link">Brand Engagement</h3>
            <div class="whitebox">
                <div class="row">
                    <div class="col-xs-12 col-md-2 pad-tb12 text-center"><a href="#"><img src="<?php echo base_url();?>uploads/images/twiiter-icon.png" alt="" /></a></div>
                    <div class="col-xs-12 col-md-10">
                        <p class="social-text"><b><span id="twiiter_count"><img src="<?php echo base_url()."/uploads/images/ajax-loader.gif"; ?>"/></span> Follower</b></p>
                        <p class="social-subtext">+12 Last Months</p>
                    </div>
                    <div class="clr"></div>
                </div>
            </div>
            <div class="clr"></div>
            <div class="whitebox">
                <div class="row">
                    <div class="col-xs-12 col-md-2 pad-tb12 text-center"><a href="#"><img src="<?php echo base_url();?>uploads/images/fb.png" alt="" /></a></div>
                    <div class="col-xs-12 col-md-10">
                        <p class="social-text"><b><span id="facebook_count"><img src="<?php echo base_url()."/uploads/images/ajax-loader.gif"; ?>"/></span> Likes</b></p>
                     <p class="social-subtext">+12 Last Months</p></div>
                    <div class="clr"></div>
                </div>
            </div>
            <div class="clr"></div>
            <div class="whitebox">
               
                <div class="row">
                    <div class="col-xs-12 col-md-2 pad-tb12 text-center"><a href="#"><img src="<?php echo base_url();?>uploads/images/linkedin.png" alt="" /></a></div>
                    <div class="col-xs-12 col-md-10">
                        <p class="social-text"><b><span id="linkedin_count"><img src="<?php echo base_url()."/uploads/images/ajax-loader.gif"; ?>"/></span> Follower</b></p>
                     <p class="social-subtext">+6 Last Months</p></div>
                    <div class="clr"></div>
                </div>
            </div>
            <div class="clr"></div>
            <div class="whitebox">
                <div class="row">
                    <div class="col-xs-12 col-md-2 pad-tb12 text-center"><a href="#"><img src="<?php echo base_url();?>uploads/images/wwwicon.png" alt="" /></a></div>
                    <div class="col-xs-12 col-md-10">
                    <p class="social-text"><b>12.321 Follower</b></p>
                      <p class="social-subtext">923 Last Months</p></div>
                    <div class="clr"></div>
                </div>
            </div>
            <div class="clearfix visible-xs-block"></div>
              <div class="whitebox text-center">
            <div id="datepicker"></div>
        </div>
        <div class="clearfix visible-xs-block"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('body').delegate('[data-toggle="ajaxModal"]', 'click',
        function (e) {
            $('#ajaxModal').remove();
            e.preventDefault();
            var $this = $(this)
                , $remote = $this.data('remote') || $this.attr('href')
                , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
            $('body').append($modal);
            $modal.modal();
            $modal.load($remote);
        }
    );

</script>
 <script type="text/javascript" src="http://platform.linkedin.com/in.js">
                        api_key: 75qdwdkxx05h1n
                        onLoad: onLinkedInLoad
                        authorize: true
</script>
<script type="text/javascript">
function onLinkedInLoad() {
IN.API.Raw("/companies/2414183:(num-followers)").result( function(result) 
{ 
    document.getElementById("linkedin_count").innerHTML = result.numFollowers;
})
.error( function(error) {  /*do nothing*/  } )
;
}
function delete_campaign(campaign_id)
{
    var delete_url = "SalesCampaign/deletedata?id=" + campaign_id ;
    
    BootstrapDialog.confirm('Are you sure you want to delete this campaign?', function(result){
        if(result) {
            window.location.href = delete_url;
        }
    });
}
function get_twitter_count()
{
    var send_url = 'SalesCampaign/get_twitter_follower_count';

    $.ajax({
        type: "POST",
        url: send_url,
        data: {'get_twitter_follower_count': 'get_twitter_follower_count'},
        success: function (data)
        {
            $('#twiiter_count').html(data);
        }
    });
}

function get_facebook_count()
{
    var send_url = 'SalesCampaign/facebook_count';

    $.ajax({
        type: "POST",
        url: send_url,
        data: {'facebook_count': 'facebook_count'},
        success: function (data)
        {
            $('#facebook_count').html(data);
        }
    });
}
get_twitter_count();
get_facebook_count();
</script>

<?=$this->load->view('/Common/common','',true);?>