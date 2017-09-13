<?php
/*
@Author : Sanket Jayani
@Desc   : Request Campaign Budget
@Date   : 22/01/2016
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class CampaignReport extends CI_Controller 
{
    function __construct()
    {
        parent::__construct();

        if(checkPermission('CampaignReport','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->load->library(array('form_validation','Session',"Facebook"));
        $this->load->model('CampaignReport_model');
        $this->current_module = $this->router->fetch_module();
        $this->viewname = $this->current_module;
        $this->load->library('pagination');
        $this->load->helper(array('form','url'));
        $this->viewname = $this->router->fetch_class();

        $this->linkedin_api_key =  $this->common_model->getSettingsData('linkedin_api_key');
        $this->linkedin_company_id =  $this->common_model->getSettingsData('linkedin_company_id');
        

    }
    
    function index()
    {    
         if(empty($_COOKIE['LinkedinStat'])){
        
            $linkedinLikes=$this->linkedinFollowerCnt(); // call linkedin api to set cookies
        }

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $format_start_date = '';
        $format_end_date = '';
        if($start_date != '' && $end_date !=''){
            $format_start_date = date('Y-m-d', strtotime($start_date));
            $format_end_date = date('Y-m-d', strtotime($end_date));
        }
        
        //echo $format_start_date;exit;
        $archive_camp = $this->input->post('archive_campaign');
        $type_of_report = $this->input->post('type_of_report');
        $searchtext='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('campaignreport_data');
        }

        $searchsort_session = $this->session->userdata('campaignreport_data');
        //Sorting
        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield']))
            {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            }
            else
            {
                $sortfield = 'campaign_id';
                $sortby = 'desc';
                $data['sortfield']		= $sortfield;
                $data['sortby']			= $sortby;
            }
        }
        //Search text
        if(!empty($searchtext))
        {
            $data['searchtext'] = $searchtext;
        } else
        {
            if(empty($allflag) && !empty($searchsort_session['searchtext']))
            {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext =  $data['searchtext'];
            }
            else
            {
                $data['searchtext'] = '';
            }
        }

        if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        $data['checked_campaign_id'] = array();
        //pagination configuration
        $config['first_link']  = 'First';
        $config['base_url']    = base_url().$this->viewname.'/index';
        $searchtext='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('campaignreport_data');
        }

        if($this->session->has_userdata('campaignreport_data')){
            $data['searchsort_session'] = $this->session->userdata('campaignreport_data');
        }
        
        //Sorting
        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield']))
            {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            }
            if($this->input->post('sortfield')) {
                $sortfield = $this->input->post('sortfield');
                
                $sortby = 'desc';
                $data['sortfield']		= 'ca.'.$sortfield;
                $data['sortby']			= $sortby;
            }
            else
            {
                $sortfield = 'campaign_id';
                $sortby = 'desc';
                $data['sortfield']		= $sortfield;
                $data['sortby']			= $sortby;
            }
        }

        //Search text
        if(!empty($searchtext))
        {
            $data['searchtext'] = $searchtext;
        } else
        {
            if(empty($allflag) && !empty($searchsort_session['searchtext']))
            {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext =  $data['searchtext'];
            }
            else
            {
                $data['searchtext'] = '';
            }
        }

        if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link']  = 'First';
        $config['base_url']    = base_url().$this->viewname.'/index';

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }

        $data['sales_view']=$this->viewname;
        
        $table = CAMPAIGN_MASTER . " AS cm";
        $where = array("cm.status" => 1);
        $fields = array("cm.*,ct.camp_type_name, conm.contact_name");
        
        $params['join_tables'] = array( CAMPAIGN_TYPE_MASTER . ' AS ct' => 'ct.camp_type_id = cm.campaign_type_id',                                CONTACT_MASTER . ' As conm' => 'conm.contact_id = cm.responsible_employee_id'
                                );
        $params['join_type'] = 'left';
        
        $table_archive = CAMPAING_ARCHIVE. ' AS ca';
        $fields_archive = array("ca.*,ct.camp_type_name, conm.contact_name");
        $params_archive['join_tables'] = array( CAMPAIGN_TYPE_MASTER . ' AS ct' => 'ct.camp_type_id = ca.campaign_type_id',                                      CONTACT_MASTER . ' As conm' => 'conm.contact_id = ca.responsible_employee_id'
                                        );
        //search
        if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim($searchtext));
            //$match=array('cm.campaign_name'=>$searchtext, 'ct.camp_type_name'=>$searchtext);
            $match_archive = array('ca.campaign_name'=>$searchtext, 'ca.campaign_auto_id'=>$searchtext);
            //search with archive campaign data
            if($archive_camp == 'on'){
                if($format_start_date != '' && $format_end_date != ''){
                    $where_date = array("(ca.start_date >="=> '\'' . $format_start_date . '\'', "ca.end_date <="=>'\'' . $format_end_date . '\')');
                    
                    $config['total_rows'] = count($this->common_model->get_records($table_archive, $fields_archive, $params_archive['join_tables'], $params['join_type'], '', $match_archive, '', '', '', '', '', $where_date));

                    $data['campaign_report_list'] = $this->common_model->get_records($table_archive, $fields_archive, $params_archive['join_tables'], $params['join_type'], '', $match_archive, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_date,'','','');
                }else{
                    $config['total_rows'] = count($this->common_model->get_records($table_archive, $fields_archive, $params_archive['join_tables'], $params['join_type'], '', $match_archive, '', '', '', '', '', ''));

                    $data['campaign_report_list'] = $this->common_model->get_records($table_archive, $fields_archive, $params_archive['join_tables'], $params['join_type'], '', $match_archive, $config['per_page'], $uri_segment, $sortfield, $sortby, '', '','','','');
                }
                
            }
            //search with date filter data
            elseif($format_start_date != '' && $format_end_date != '') {
                $searchtext1 = html_entity_decode(trim(addslashes($searchtext)));
                if($archive_camp == 'on'){
                    echo "search with archive with date";
                }else{
                $where_date = '((cm.campaign_name LIKE "%'.$searchtext1.'%"ESCAPE "!" OR cm.campaign_auto_id LIKE "%'.$searchtext1.'%"ESCAPE "!" OR ct.camp_type_name LIKE "%'.$searchtext1.'%" ) AND (cm.start_date >= "'.$format_start_date.'" AND cm.end_date <= "'.$format_end_date.'") AND (cm.status = "1"))';
                $config['total_rows'] = count($this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', '', '', '', $where_date));

                $data['campaign_report_list'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_date,'','','');      
                }
                
            }
            //search only data
            else {
                $searchtext1 = html_entity_decode(trim(addslashes($searchtext)));
                $match_where = '((cm.campaign_name LIKE "%'.$searchtext1.'%"ESCAPE "!" OR cm.campaign_auto_id LIKE "%'.$searchtext1.'%"ESCAPE "!" OR ct.camp_type_name LIKE "%'.$searchtext1.'%" ) AND (cm.status = "1"))';
                $config['total_rows'] = count($this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', '', '', '', $match_where));

                $data['campaign_report_list'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $match_where,'','','');   
            
            }
        } 
        // without search
        else {
            //archive campaign data
            if($archive_camp == 'on'){
                
                if($format_start_date != '' && $format_end_date != ''){
                    $where_date = array("(ca.start_date >="=> '\'' . $format_start_date . '\'', "ca.end_date <="=>'\'' . $format_end_date . '\')');
                    
                    $config['total_rows'] = count($this->common_model->get_records($table_archive, $fields_archive, $params_archive['join_tables'], $params['join_type'], '', '', '', '', '', '', '', $where_date));

                    $data['campaign_report_list'] = $this->common_model->get_records($table_archive, $fields_archive, $params_archive['join_tables'], $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_date,'','','');
                    
                }else{
                    
                    $config['total_rows'] = count($this->common_model->get_records($table_archive, $fields_archive, $params_archive['join_tables'], $params['join_type'], '', '', '', '', '', '', '', ''));

                    $data['campaign_report_list'] = $this->common_model->get_records($table_archive, $fields_archive, $params_archive['join_tables'], $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', '','','',''); 
                    //echo $this->db->last_query();
                }
                
            } 
            //date filter data
            elseif($format_start_date != '' && $format_end_date != '') {
                $where_date = array("(cm.start_date >="=> '\'' . $format_start_date . '\'', "cm.end_date <="=>'\'' . $format_end_date . '\')', "cm.status"=> '1');
                    
                    $config['total_rows'] = count($this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', '', '', '', $where_date));

                    $data['campaign_report_list'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_date,'','','');
                
            } 
            //data
            else {
                $config['total_rows'] = count($this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', '', '', '', $where));

                $data['campaign_report_list'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where,'','','');   
            }
        }
        $data['header'] = array('menu_module'=>'crm');
        $this->ajax_pagination->initialize($config);
        $data['pagination']  = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield'  => $data['sortfield'],
            'sortby'     => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage'    => trim($data['perpage']),
            'uri_segment'=> $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('campaignreport_data', $sortsearchpage_data);
        $data['drag']=true;
         //Pass Records In View
        if($this->input->post('result_type') == 'ajax'){
            $this->load->view('CampaignReportAjax',$data);
        } else {
            $data['main_content'] = $this->current_module;
            //$data['main_content'] = '/'.$this->viewname;
            $this->parser->parse('layouts/CampaignReportTemplate', $data);
        }
    }


/*
This functio nid to get Linked followers count 
*/

function linkedinFollowerCnt($loadJs=true,$startDate='',$endDate=''){

    if($startDate=='' & $endDate==''){
       $currentMonth=date('m');
       $startDate= strtotime( date("d-m-Y",strtotime("-$currentMonth months")));
       $endDate= time();

     $startDateMillSec= $startDate*1000;
     $endDateMillSec= $endDate*1000;
    }
    ?>

    <script>
    var linkedin_api_key = '<?php echo $this->linkedin_api_key; ?>';
    var linkedin_company_id = '<?php echo $this->linkedin_company_id; ?>';
    var startDateMillSec='<?php echo $startDateMillSec; ?>';
    var endDateMillSec='<?php echo $endDateMillSec; ?>';
    
</script>
<!-- 75qdwdkxx05h1n -->
<?php 
if($loadJs){

    if($this->linkedin_api_key != '' && $this->linkedin_company_id != '')
    {
        printf('<script src="//platform.linkedin.com/in.js" type="text/javascript">
            api_key: %s
            onLoad: onLinkedInLoad
            authorize: true
            </script>', 
            $this->linkedin_api_key
        );  
        
        
    }
}

?>
    <script type="text/javascript">


     function setCookie(cname, cvalue, exdays) {
     var d = new Date();
     //exdayss = 1700;
     d.setTime(d.getTime() + (exdays * 60 * 1000));
     var expires = "expires=" + d.toGMTString();
     document.cookie = cname + "=" + cvalue + "; " + expires;
    }
/*
    function getCookie(cname) {
     var name = cname + "=";
     var ca = document.cookie.split(';');
     for (var i = 0; i < ca.length; i++) {
      var c = ca[i].trim();
      if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
     }
     return "";
    }*/

        <?php

            if($this->linkedin_api_key != '' && $this->linkedin_company_id != '')
            { ?>
             
           function onLinkedInLoad() 
            {     
            IN.API.Raw("/companies/"+linkedin_company_id+"/historical-follow-statistics?start-timestamp="+startDateMillSec+"&time-granularity=month&end-timestamp="+endDateMillSec+"&format=json")
             .result( function(result) 
                { 
                  //  console.log("/companies/"+linkedin_company_id+"/historical-follow-statistics?start-timestamp=1422144000&end-timestamp=1458864000&time-granularity=month&format=json");
                   console.log(result);
                   setCookie('LinkedinStat', JSON.stringify(result), 10);
                                   
                  // return JSON.stringify(result);
                
                })


                .error( function(error) {  /*do nothing*/  } )
                ;
           }  
            <?php } 


            ?>

    </script>
<?php 

}
/*
 This function will hecck for FB access right
Author: Brijesh Tiwari
*/

function checkFBaccessright($redirect_url=''){

    $this->load->library("Facebook","Encryption");
    $converter = new Encryption;

    $encodedUrl=$converter->encode($redirect_url);

    $URL=base_url("CampaignReport/FBcallback/")."/".$encodedUrl;
    
    $app_id = $this->common_model->getSettingsData('facebook_app_id');
    $app_secret = $this->common_model->getSettingsData('facebook_app_secret');

    $this->fb = new Facebook\Facebook([
    'app_id' => $app_id ,
    'app_secret' => $app_secret,
    'default_graph_version' => 'v2.4',
     'default_access_token' =>isset($_SESSION['accessToken'])?$_SESSION['accessToken']:"$app_id|$app_secret" 
    ]);
  
    //$fb = new Facebook();
 
    $params = array('req_perms' => 'publish_actions');
    $helper = $this->fb->getRedirectLoginHelper();
    $loginUrl = $helper->getLoginUrl($URL, $params);
        
    header('Location: '. $loginUrl);
    exit;

}

function FBcallback($redirect_url=''){

    $this->load->library("Facebook","Encryption");
    $converter = new Encryption;

    $decodedUrl=$converter->decode($redirect_url);

  
    $app_id = $this->common_model->getSettingsData('facebook_app_id');
    $app_secret = $this->common_model->getSettingsData('facebook_app_secret');

    $this->fb = new Facebook\Facebook([
    'app_id' => $app_id ,
    'app_secret' => $app_secret,
    'default_graph_version' => 'v2.4',
     'default_access_token' =>isset($_SESSION['accessToken'])?$_SESSION['accessToken']:"$app_id|$app_secret" 
    ]);

    $helper = $this->fb->getRedirectLoginHelper();

   
    
    try {
       
     $accessToken = $helper->getAccessToken();
   
   
      // this token will be valid for next 2 hours
     
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      // When Graph returns an error
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      // When validation fails or other local issues
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }
    //exit;

    // OAuth 2.0 client handler
  
    $oAuth2Client = $this->fb->getOAuth2Client();
       
    // Exchanges a short-lived access token for a long-lived one
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    
    //use Facebook;
    if($accessToken){
    $_SESSION['accessToken']=(string)$accessToken;
    header("Location: $decodedUrl");
    exit;
    }
}


/*
 This function is for getting like count after getting acces token
*/

    function getFbLikes($page_id,$starData,$endDate){


     $this->load->library("Facebook");
  
    $app_id = $this->common_model->getSettingsData('facebook_app_id');
    $app_secret = $this->common_model->getSettingsData('facebook_app_secret');

    $this->fb = new Facebook\Facebook([
    'app_id' => $app_id ,
    'app_secret' => $app_secret,
    'default_graph_version' => 'v2.4',
     'default_access_token' =>isset($_SESSION['accessToken'])?$_SESSION['accessToken']:"$app_id|$app_secret" 
    ]);

        $res = $this->fb->get("$page_id/insights/?since=$starData&until=$endDate");
    
    
    // If you already have a valid access token:


     $response = $res->getDecodedBody(); // for Array resonse
     
     
    $totalcount=count($response['data'][0]['values']);
      
      if(!empty($response['data'][0]['values'][$totalcount-1]['value'])){
        $totalLikescount=array_sum($response['data'][0]['values'][$totalcount-1]['value']);
      }else{

        $totalcount=date("j");
        $totalLikescount=array_sum(@$response['data'][0]['values'][$totalcount-2]['value']);
      }
    
   return $totalLikescount;
    }


function lastday($month = '', $year = '') {
   if (empty($month)) {
      $month = date('m');
   }
   if (empty($year)) {
      $year = date('Y');
   }
   $result = strtotime("{$year}-{$month}-01");
   $result = strtotime('-1 second', strtotime('+1 month', $result));
   return  $result;
}

function firstDay($month = '', $year = '')
{
    if (empty($month)) {
      $month = date('m');
   }
   if (empty($year)) {
      $year = date('Y');
   }
   $result = strtotime("{$year}-{$month}-01");
   return $result;
} 

    function youTubeCallback(){

        $this->load->library("Googleanly"); 
        $client = new Google_Client();
        $client->setAuthConfigFile(APPPATH .'/'.YOUTUBE_APPLICATION_CREDENTIALS.'/client_secrets.json');
       
        $client->setAccessType("offline");
        //$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST']. '/youtubeAPI/oauth2callback');
        $client->setScopes(array('https://www.googleapis.com/auth/youtube.force-ssl', 'https://www.googleapis.com/auth/youtubepartner-channel-audit', 'https://www.googleapis.com/auth/youtube', 'https://www.googleapis.com/auth/youtube.readonly', 'https://www.googleapis.com/auth/yt-analytics.readonly', 'https://www.googleapis.com/auth/yt-analytics-monetary.readonly','https://www.googleapis.com/auth/youtubepartner'));

        $youtube = new Google_Service_YouTube($client);
        $redirect_url= base_url("CampaignReport/youTubeCallback");;
        $client->setRedirectUri($redirect_url);

          $client->authenticate($_GET['code']);
         //echo "<br>getAccessToken-> ".
          $client->getAccessToken();
          
          $_SESSION['youtube']['access_token'] = $client->getAccessToken();

   
     header('Location: ' . filter_var($_SESSION['youtubecallback'], FILTER_SANITIZE_URL));
              //header('Location: ' . $auth_url);
              exit;
    }

    // this if for youtube like count 
    function checkYoutubecallback($redirect_url=''){
       // $this->load->library("Youtube");  //Googleanly
        $this->load->library("Googleanly"); 
        $client = new Google_Client();
        $client->setAuthConfigFile(APPPATH .'/'.YOUTUBE_APPLICATION_CREDENTIALS.'/client_secrets.json');
       
        $client->setAccessType("offline");
        //$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST']. '/youtubeAPI/oauth2callback');
        $client->setScopes(array('https://www.googleapis.com/auth/youtube.force-ssl', 'https://www.googleapis.com/auth/youtubepartner-channel-audit', 'https://www.googleapis.com/auth/youtube', 'https://www.googleapis.com/auth/youtube.readonly', 'https://www.googleapis.com/auth/yt-analytics.readonly', 'https://www.googleapis.com/auth/yt-analytics-monetary.readonly','https://www.googleapis.com/auth/youtubepartner'));

        $youtube = new Google_Service_YouTube($client);
        if(isset($_GET['code'])){
           $urlArray=  explode('&code', $redirect_url);
           
           $redirect_url=$urlArray[0];
         }

        $client->setRedirectUri($redirect_url);

        if (! isset($_GET['code'])) {
          
         $auth_url = $client->createAuthUrl();
  
          header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
         
          exit;
        } else {
           
          $client->authenticate($_GET['code']);
         //echo "<br>getAccessToken-> ".
          $client->getAccessToken();
          
          $_SESSION['youtube']['access_token'] = $client->getAccessToken();
        

          header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
          exit;
        }

    }

    function getYoutubeLikes($channel_url,$start_date,$end_date){

        if (isset($_SESSION['youtube']['access_token']) && $_SESSION['youtube']['access_token']) {
           // $this->load->library("Youtube");
        $this->load->library("Googleanly"); 
        $client = new Google_Client();
        $client->setAuthConfigFile(APPPATH . '/'.YOUTUBE_APPLICATION_CREDENTIALS.'/client_secrets.json');
       
        $client->setAccessType("offline");
        //$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST']. '/youtubeAPI/oauth2callback');
        $client->setScopes(array('https://www.googleapis.com/auth/youtube.force-ssl', 'https://www.googleapis.com/auth/youtubepartner-channel-audit', 'https://www.googleapis.com/auth/youtube', 'https://www.googleapis.com/auth/youtube.readonly', 'https://www.googleapis.com/auth/yt-analytics.readonly', 'https://www.googleapis.com/auth/yt-analytics-monetary.readonly','https://www.googleapis.com/auth/youtubepartner'));

        $youtube = new Google_Service_YouTube($client);
          $client->setAccessToken($_SESSION['youtube']['access_token']);
         
          
          
          $analytics = new Google_Service_YouTubeAnalytics($client);
        
            $ids = 'channel==' . $channel_url . '';
           // $end_date = date("Y-m-d"); 
           // $start_date = date('Y-m-d', strtotime("-30 days"));
            $optparams = array(
            //'dimensions' => 'day',
            );

            $metric = 'views,likes,dislikes';

            try{

            $api = $analytics->reports->query($ids, $start_date, $end_date, $metric, $optparams);

           
           if($api->getRows()){
               $rowData=$api->getRows();
               $headerData=$api->getColumnHeaders();
               $resultData=array();
               foreach ($rowData[0] as $key => $value) {
                 $resultData[0][$headerData[$key]->getName()]=$value;
               }
            }else{
                $resultData=array();
            }
                   
            return $resultData;
            // $params = array( 
            //              'managedByMe' => true
                        
            //              );

            }catch (Google_Service_Exception $e) {
                             
                echo sprintf('<p>A service error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
            }
          
          
        } else {
            
          $redirect_uri = base_url("CampaignReport/checkYoutubecallback");;
          
          header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
          //header('Location: ' . $redirect_uri);
          exit;
          
        }
    }

    function generatePDF()
    {
         $app_id = $this->common_model->getSettingsData('facebook_app_id');
         $app_secret = $this->common_model->getSettingsData('facebook_app_secret');
         $page_id = $this->common_model->getSettingsData('facebook_page_id');

         $channel_id = $this->common_model->getSettingsData('youtube_channel_id');
         

         
        if($app_id=='' || $app_secret=='' || $page_id==''){
            $this->session->set_flashdata('error', lang('FACEBOOK_API_ERROR'));
            header("Location: ../CampaignReport");
            exit;
        }

                 
        if($channel_id=='' || !file_exists(APPPATH .'/'.YOUTUBE_APPLICATION_CREDENTIALS.'/client_secrets.json')){
            $this->session->set_flashdata('error', lang('YOUTUBE_API_ERROR'));
            header("Location: ../CampaignReport");
            exit;
        }

        $linkedin_api_key =  $this->common_model->getSettingsData('linkedin_api_key');
        $linkedin_company_id =  $this->common_model->getSettingsData('linkedin_company_id');


        if($linkedin_api_key=='' || $linkedin_company_id==''){
            $this->session->set_flashdata('error', lang('LINKEDIN_API_ERROR'));
            header("Location: ../CampaignReport");
            exit;
        }

        if(empty($_COOKIE['LinkedinStat'])){

        $linkedinLikes=$this->linkedinFollowerCnt(); // call linkedin api to set cookies
        }

        $campaign_data = $this->input->get('campaign_id'); //Get campaign ids
        $campaign_ids_arr = explode(",",$campaign_data);
        $data = [];
        $data['user_data']=$this->session->userdata('LOGGED_IN');
        $data['campaign_list'] = $this->CampaignReport_model->ExportPDF($campaign_ids_arr);


       array_push($campaign_ids_arr, '0') ;
       $getLeadsByMarketing= $this->CampaignReport_model->getLeadsByMarketing($campaign_ids_arr);


       $month=array();
       $monthArray=array();
       $newLeads=array();

        foreach ($getLeadsByMarketing as $key => $leads) {
                  $month[$leads->month]= $leads->month; 
                  if (!in_array($leads->month, $monthArray)) {
                        $monthArray[]=$leads->month;
                    }
               
          }

     foreach ($getLeadsByMarketing as $key => $leadsMonth) {
           // assign 0 to montly leads
           foreach ($month as $key => $value) {
                $newLeads[$leadsMonth->campaign_id]['monthly_count'][$value]=0;
            }
        }
           

       foreach ($getLeadsByMarketing as $key => $leads) {
        
        if($leads->campaign_name==''){
            $campaign_name='Direct Lead';
        }else{
            $campaign_name=$leads->campaign_name;
        }

       

        $data['leads_by_marketing']['campaign_count']=count($getLeadsByMarketing);
        $data['leads_by_marketing']['month']=$month;
        //$data['leads_by_marketing']['campaign_ids']=$campaign_ids_arr ;
        $newLeads[$leads->campaign_id]['campaign_name']=trim($campaign_name);
       
        //$newLeads[$leads->campaign_id]['monthly_count'][$leads->month]=$leads->total_lead;
        $newLeads[$leads->campaign_id]['monthly_count'][$leads->month]=$leads->total_lead;
        }


        $data['leads_by_marketing']['categories']="'".implode("','", $month)."'";
        $data['leads_by_marketing']['categoriesArray']=$monthArray;
        $data['leads_by_marketing']['data']=$newLeads;



        // get Total Marketing reach grew form last month

       
        $reachGrew=array();
        $peviousMonth= date("Y-m-d", mktime(0, 0, 0, date("m")-1, 1, date("Y")));
        $currentMonth=date("Y-m-d",time());

        //$peviousMonth="2016-02-01";
        //$currentMonth="2016-03-31";
         $reachGrewData=$this->CampaignReport_model->getLeadsByMarketing($campaign_ids_arr,$peviousMonth,$currentMonth);

         foreach ($reachGrewData as $key => $reach) {
             $reachGrew[$reach->month]=$reach->total_lead+ @$reachGrew[$reach->month];
         }

        @$val=array_keys(@$reachGrew);
        if(count($val)==1){
            $data['percentageReachGrew']=0;
        }else{
             $data['percentageReachGrew']=$this->CampaignReport_model->percentage( @$reachGrew[$val[0]],@$reachGrew[$val[1]], 0);
         }

  // get page visit from last 2 month from google analytics

 
         $peviousMonthStartDate= date("Y-m-d", mktime(0, 0, 0, date("m")-1, 1, date("Y"))); 
         $peviousMonthendDate=date("Y-m-d", mktime(0, 0, 0, date("m"), 0, date("Y")));
    
        $peviousMonth = $this->CampaignReport_model->monthlyPageVisitCount($peviousMonthStartDate,$peviousMonthendDate);

        $currentMonthStartDate=date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
        $currentMonthEndDate=date("Y-m-d", time());


    $currentMonth = $this->CampaignReport_model->monthlyPageVisitCount($currentMonthStartDate,$currentMonthEndDate);


//organicSearches
        if($peviousMonth==0){
            $data['websiteVisit']=0;
        }else{
             $data['websiteVisit']=$this->CampaignReport_model->percentage( $peviousMonth,$currentMonth, 0);
         }

    $peviousMonthOS='';
    $currentMonthOS='';
    $searchType="organicSearches";
    
        $peviousMonthOS = $this->CampaignReport_model->monthlyPageVisitCount($peviousMonthStartDate,$peviousMonthendDate,$searchType);

    
    $currentMonthOS = $this->CampaignReport_model->monthlyPageVisitCount($currentMonthStartDate,$currentMonthEndDate,$searchType);

        if($peviousMonthOS==0){
            $data['organicSearches']=0;
        }else{
             $data['organicSearches']=$this->CampaignReport_model->percentage( $peviousMonthOS,$currentMonthOS, 0);
         }



// new leads grew per month

 $newLeadsByMonth=$this->CampaignReport_model->newCustomerORLeadByMonth();
        
        if($newLeadsByMonth[0]['total_count']==0){
            $data['newLeadsByMonth']=0;
            $data['newLeadsByMonthCount']=$newLeadsByMonth[1]['total_count'];
        }else{
             $data['newLeadsByMonth']=$this->CampaignReport_model->percentage( @$newLeadsByMonth[0]['total_count'],@$newLeadsByMonth[1]['total_count'], 0);
             $data['newLeadsByMonthCount']=@$newLeadsByMonth[1]['total_count'];
         }



// new custom grew per month
    $newcustomerByMonth=$this->CampaignReport_model->newCustomerORLeadByMonth("customer");

        if($newcustomerByMonth[0]['total_count']==0){
            $data['newcustomerByMonth']=0;
            $data['newcustomerByMonthCount']=$newcustomerByMonth[1]['total_count'];
        }else{
             $data['newcustomerByMonth']=$this->CampaignReport_model->percentage( @$newcustomerByMonth[0]['total_count'],@$newcustomerByMonth[1]['total_count'], 0);
             $data['newcustomerByMonthCount']=@$newcustomerByMonth[1]['total_count'];
         }


// Ratio for total customer to lost customer 

        $lostCustomer= $this->CampaignReport_model->lostCustomer();

$data['percentageLostCustomer']= round((($lostCustomer[0]['lost_client']/$lostCustomer[0]['total_leads'])*100),0);


//Marketing Reach 
//========================================
$facebookLikes=array();
    if(!isset($_SESSION['accessToken'])){
        $calBackUrl=base_url("CampaignReport/generatePDF")."?".$_SERVER['REDIRECT_QUERY_STRING'];

        $this->checkFBaccessright($calBackUrl);
    }else{

   
   $year=date("Y");
   $currentMonth=date("m");
   $page_id = $this->common_model->getSettingsData('facebook_page_id');

   // count prevoius year like
         $previousYear= date("Y",strtotime("-1 year"));
         $since=$this->firstDay(12 , $previousYear);
         $until=$this->lastday(12 , $previousYear);
         $totalLikes= $this->getFbLikes($page_id,$since,$until);
         $facebookLikes['previous_year'][0]['month']=0;
         $facebookLikes['previous_year'][0]['likes']=$totalLikes;
       for ($i=1; $i <=$currentMonth ; $i++) { 
          
         $month = $i;
         $since=$this->firstDay($month , $year);
         $until=$this->lastday($month , $year );
         $totalLikes= $this->getFbLikes($page_id,$since,$until);
         $facebookLikes['current_year'][$i]['month']=$month;
         $facebookLikes['current_year'][$i]['total_likes']=$totalLikes;

            if($i==1){

              $facebookLikes['current_year'][$i]['likes']=$totalLikes- $facebookLikes['previous_year'][0]['likes'];   
            }else{
                $j=$i-1;
             $facebookLikes['current_year'][$i]['likes']=$totalLikes -  $facebookLikes['current_year'][$j]['total_likes'];
            }
       }
               
       
    } 
   

// Youtub likes start here
//=================================================================
 /*
 $youtubeLikes=array();

    if(!isset($_SESSION['youtube']['access_token'])){

      $calBackUrl=base_url("CampaignReport/generatePDF")."?".$_SERVER['REDIRECT_QUERY_STRING'];
      $_SESSION['youtubecallback']=$calBackUrl;
     $youtubeUrl=base_url("CampaignReport/youTubeCallback");

     $youtubeLikes=   $this->checkYoutubecallback($youtubeUrl);
     
    }else{
     

   $year=date("Y");
   $currentMonth=date("m");
   $channel_url = $this->common_model->getSettingsData('youtube_channel_id');
   //$channel_url = $this->config->item('channel_url', 'youtube');

   
       for ($i=1; $i <=$currentMonth ; $i++) { 
          
         $month = $i;
         $since= date('Y-m-d', $this->firstDay($month , $year));
         $until=date('Y-m-d',$this->lastday($month , $year ));
         $totalLikes= $this->getYoutubeLikes($channel_url,$since,$until);
         if($totalLikes){
             $youtubeLikes['current_year'][$i]['month']=$month;
             $youtubeLikes['current_year'][$i]['likes']=$totalLikes[0]['likes'];
             $youtubeLikes['current_year'][$i]['views']=$totalLikes[0]['views'];
             $youtubeLikes['current_year'][$i]['dislikes']=$totalLikes[0]['dislikes'];
         }else{

            $youtubeLikes['current_year'][$i]['month']=$month;
            $youtubeLikes['current_year'][$i]['likes']=0;
            $youtubeLikes['current_year'][$i]['views']=0;
            $youtubeLikes['current_year'][$i]['dislikes']=0; 
         }
     }
 
   }
*/
//======================================
// check for linkedin cookies isset or not 

    if(empty($_COOKIE['LinkedinStat'])){
       // echo "----->".base_url("CampaignReport/generatePDF")."?".$_SERVER['REDIRECT_QUERY_STRING'];
       
           $reloadLocation=base_url("CampaignReport/generatePDF")."?".$_SERVER['REDIRECT_QUERY_STRING'];
           
    }else
    {
      

        $LinkedinStat=json_decode($_COOKIE['LinkedinStat']);
        $linkedinLikes=array();
            foreach ($LinkedinStat->values as $key => $linkedinData) {
            
            $dateTimestamp= date('m', ($linkedinData->time/1000));
            $totalLikes=$linkedinData->totalFollowerCount;

                if($key==0){
                    $linkedinLikes['previous_year'][$key]['month']=$dateTimestamp;
                     $linkedinLikes['previous_year'][$key]['total_likes']=$linkedinData->totalFollowerCount;
                     $linkedinLikes['previous_year'][$key]['likes'] =$totalLikes;
                }else{

                     $linkedinLikes['current_year'][$key]['month']=$dateTimestamp;
                     $linkedinLikes['current_year'][$key]['total_likes']=$totalLikes;


                      $j=$key-1;
                      if($key==1){
                         $linkedinLikes['current_year'][$key]['likes']=$totalLikes-$linkedinLikes['previous_year'][$j]['total_likes'];
                      }else{
                         $linkedinLikes['current_year'][$key]['likes']=$totalLikes-$linkedinLikes['current_year'][$j]['total_likes'];
                     }
                }
                
        }
    }
    

    // Twitter Count per month 

    $tstarData= date('Y-m-d',strtotime(date('Y-01-01')));
    $tendDate=date('Y-m-d', strtotime(' +1 day'));
       $twitterLikesObj =$this->CampaignReport_model->getTwitterCountMonthly($tstarData,$tendDate);

    foreach ($twitterLikesObj as $key => $value) {
       $twitterLikes[$value->month]=$value;
    }

     $marketingReach=array(); 
    $currentMonth=date("m");
    for ($i=1; $i <= $currentMonth; $i++) { 
        
        //$marketingReach['campaign_name']=array('facebook','youtube','linkedin','twitter');
        $marketingReach['campaign_name']=array('facebook','linkedin','twitter');
        $marketingReach['data'][$i]['facebook']=$facebookLikes['current_year'][$i]['likes'];
        //$marketingReach['data'][$i]['youtube']=$youtubeLikes['current_year'][$i]['likes'];
        $marketingReach['data'][$i]['linkedin']=$linkedinLikes['current_year'][$i]['likes'];
        if(isset($twitterLikes[$i])){
            $marketingReach['data'][$i]['twitter']=$twitterLikes[$i]->followers_count;
        }else{
             $marketingReach['data'][$i]['twitter']=0;
        }

    }


// Website Visits by source


   $currentMonthStartDate=date("Y-m-d",strtotime("-11 months"));
   $sourceType='pageviews';
   $monthlyPageVisitCountBySource= $this->CampaignReport_model->monthlyPageVisitCountBySource($currentMonthStartDate,$currentMonthEndDate,$sourceType);



$temp=array();
$monthNameArray=array();
$googleAnalitics=array();
$monthName=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
foreach ($monthlyPageVisitCountBySource as $key => $rowData) {

$monthNameArray[$monthName[$rowData['month']].'-'.substr($rowData['year'], -2)]=$monthName[$rowData['month']].'-'.substr($rowData['year'], -2);


 $googleData[$rowData['ref_name']]=$rowData;

}

// assign 0 to montly leads
        foreach ($googleData as $rkey => $refferal) {
            
            // foreach ($monthNameArray as $key => $value) {

            //       $googleAnalitics[$refferal['source']]['monthly_count'][$key]='0';
            // }

            if($refferal['source']=='(none)'){
                $campaign_name='Direct Lead';
            }else{

                $campaign_name=$refferal['source'];
            }

            $googleAnalitics[$refferal['source']]['campaign_name']=$campaign_name;
        }


        foreach ($monthlyPageVisitCountBySource as $key => $refferal) {

          @$googleAnalitics[$refferal['source']]['monthly_count'][@$monthName[$refferal['month']].'-'.substr(@$refferal['year'], -2)]= (int)$refferal['page_view'] + @$googleAnalitics[@$refferal['source']]['monthly_count'][@$monthName[@$refferal['month']].'-'.substr(@$refferal['year'], -2)];

        }



// Get customer by source

      $getCustomerBySource= $this->CampaignReport_model->getCustomerBySource($campaign_ids_arr);

     

       $monthSource=array();
       $monthSourceArray=array();
       $newLeadsSource=array();

       foreach ($getCustomerBySource as $key => $leadsSource) {
          $monthSource[$leadsSource->month]= $leadsSource->month;
          if (!in_array($leadsSource->month, $monthSourceArray)) {
            $monthSourceArray[]=$leadsSource->month;
         } 
      }
      // assign 0 to montly leads
            // foreach ($monthSource as $key => $value) {
                
            //     $newLeadsSource[$leadsSource->campaign_id]['monthly_count'][$key]='0';
            // }
        foreach ($getCustomerBySource as $key => $leadsSource) {
          //$monthSource[$leadsSource->month]= $leadsSource->month; 

            if($leadsSource->campaign_name==''){
                $campaign_name='Direct Lead';
            }else{
                $campaign_name=$leadsSource->campaign_name;
            }

            $data['customer_by_source']['campaign_count']=count($getCustomerBySource);
            $data['customer_by_source']['month']=$monthSource;
            $data['customer_by_source']['campaign_ids']=$campaign_ids_arr ;
            $newLeadsSource[$leadsSource->campaign_id]['campaign_name']=trim($campaign_name);
            $newLeadsSource[$leadsSource->campaign_id]['monthly_count'][$leadsSource->month]= (int)$leadsSource->total_lead;
            
        }
      
       

        $data['customer_by_source']['categories']="'".implode("','", $monthSource)."'";
        $data['customer_by_source']['categoriesArray']=$monthSourceArray;
        $data['customer_by_source']['data']=$newLeadsSource;



        $data['page_visit_by_source']['categories']="'".implode("','", $monthNameArray)."'";
        $data['page_visit_by_source']['categoriesArray']=$monthNameArray;
        $data['page_visit_by_source']['data']=$googleAnalitics;


        // Top marketing Campaign
        $topMarketingCampaign= $this->CampaignReport_model->topMarketingCampaign();
        $data['topMarketingCampaign']=$topMarketingCampaign;


        //Customers Sourced by Marketing  

        $totalLeadCount=0;
        $monthNameArray=array();
        $customerMonthlyData=array();
        $customerSourceByMarketing= $this->CampaignReport_model->customerSourceByMarketing($campaign_ids_arr);

        foreach ($customerSourceByMarketing as $key => $customerData) {
           $monthNameArray[]=$customerData->month;
            $totalLeadCount = $totalLeadCount+ $customerData->total_lead;
        }

        foreach ($customerSourceByMarketing as $key => $value) {
           $percentageValue=((100*$value->total_lead)/$totalLeadCount);

            // $customerMonthlyData[0]['campaign_name']='Reference';
             $customerMonthlyData[1]['campaign_name']='Monthly Leads';
           
            $customerMonthlyData[1]['monthly_count'][$value->month]=$percentageValue;
            //$customerMonthlyData[0]['monthly_count'][$value->month]=100-$percentageValue;
        }

       
        $data['customer_source_by_marketing']['categories']="'".implode("','", $monthNameArray)."'";
        $data['customer_source_by_marketing']['categoriesArray']=$monthNameArray;
        $data['customer_source_by_marketing']['data']=$customerMonthlyData;

   


    //Lead to Customer Performance

        $totalLeadCount=0;
        $monthNameArray=array();
        $leadMonthlyData=array();
        $leadToCustomerPerformance= $this->CampaignReport_model->leadToCustomerPerformance();

        foreach ($leadToCustomerPerformance as $key => $leadData) {
           $monthNameArray[]=$leadData->month;
            $totalLeadCount = $totalLeadCount+ $leadData->total_lead;
        }

        foreach ($leadToCustomerPerformance as $key => $value) {
           $percentageValue=((100*$value->total_lead)/$totalLeadCount);

            // $customerMonthlyData[0]['campaign_name']='Reference';
             $leadMonthlyData[1]['campaign_name']='Leads Performance';
           
            $leadMonthlyData[1]['monthly_count'][$value->month]=$percentageValue;
           
        }

       // echo"<br>totalLeadCount->".$totalLeadCount;
       // pr($data['customer_by_source']);
        $data['lead_to_customer_performance']['categories']="'".implode("','", $monthNameArray)."'";
        $data['lead_to_customer_performance']['categoriesArray']=$monthNameArray;
        $data['lead_to_customer_performance']['data']=$leadMonthlyData;


//  Marketing Reach Data evalution start
      $monthNameArray=array();
      $marketReachMonthlyData=array();  

      $keyArray=array_keys($marketingReach['data']);
      foreach ($keyArray as $key => $value) {

         $monthNameArray[$key]=$monthName[str_pad($value, 2,'0',STR_PAD_LEFT)].'-'.date('y');
 
     }

foreach ($marketingReach['campaign_name'] as $mkey => $marketingReachData) {

    foreach ($monthNameArray as $key => $value) {

        if($marketingReach['data'][$key+1][$marketingReachData]){
             $marketReachMonthlyData[$mkey]['monthly_count'][$value]= $marketingReach['data'][$key+1][$marketingReachData];
        }
    }
  

   $marketReachMonthlyData[$mkey]['campaign_name']=$marketingReachData;
}

        $data['marketingReach']['categories']="'".implode("','", $monthNameArray)."'";
        $data['marketingReach']['categoriesArray']=$monthNameArray;
        $data['marketingReach']['data']=$marketReachMonthlyData;

        // unset session after user
        //unset($_SESSION['accessToken']);
        unset($_SESSION['youtube']['access_token']);

        //load mPDF library
        $this->load->library('m_pdf');
        
        // $data['main_content'] =$data['campaign_list']; // by brt
        //load the view and saved it into $html variable
        
        $html=$this->load->view('PDF_report', $data, true);
    
     //echo $html;exit;


        //this the the PDF filename that user will get to download
        $pdfFilePath = "Campaign_Report.pdf";
 
        
       $header="<img src='". base_url()."uploads/images/logo.png' style='margin-bottom:60px !important; float:right;clear: both;' alt='logo'/>";

       $this->m_pdf->pdf->SetHTMLHeader($header);

       //generate the PDF from the given html
        $this->m_pdf->pdf->WriteHTML($html);

        $footer="<span class='pull-right' style='float:right !important; padding-right:20px;'>Generated using Blazedesk</span>";

      $this->m_pdf->pdf->SetHTMLFooter($footer);

        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "D");
    }
    
    function generateCSV()
    {
        $campaign_data = $this->input->get('campaign_id'); //Get campaign ids
        $campaign_ids_arr = explode(",",$campaign_data);
        
        $data = [];
        $data['campaign_list'] = $this->CampaignReport_model->ExportCSV($campaign_ids_arr);
    
    }
}