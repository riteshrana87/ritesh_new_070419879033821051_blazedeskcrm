<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('UTC'); // set default time by brt
// Autoload the required files

//require_once( APPPATH . '/third_party/facebookAPI/autoload.php' );

require_once( APPPATH . '/third_party/facebookAPI/vendor/facebook/php-sdk-v4/src/Facebook/autoload.php' );
 
// Make sure to load the Facebook SDK for PHP via composer or manually
 
use Facebook\Exceptions;
use Facebook\FacebookResponse;
use Facebook\FacebookSession;
// add other classes you plan to use, e.g.:
// use Facebook\FacebookRequest;
// use Facebook\GraphUser;
// use Facebook\FacebookRequestException;
 
class Facebook
{
  var $ci;
  var $session = false;
 
  public function __construct()
  {
    // Get CI object.
    $this->ci =& get_instance();
 
    // Initialize the SDK
   
    $app_id = $this->ci->config->item('api_id', 'facebook');
    $app_secret = $this->ci->config->item('app_secret', 'facebook');

    $this->fb = new Facebook\Facebook([
    'app_id' => $app_id ,
    'app_secret' => $app_secret,
    'default_graph_version' => 'v2.4',
     'default_access_token' =>isset($_SESSION['accessToken'])?$_SESSION['accessToken']:"$app_id|$app_secret" 
    ]);

    return $this->fb;
  }
 
  
  // get like from time difference
  public function getFBLikes($page_id,$startData,$endedate){
// startDate and endDtae shoule be in linux timestamp


    $res = $this->fb->get("$page_id/insights/?since=$startData&until=$endedate");
  
  
  // If you already have a valid access token:

     $response = $res->getDecodedBody(); // for Array resonse


     $totalcount=count($response['data'][0]['values']);
     $totalcount=array_sum($response['data'][0]['values'][$totalcount-1]['value']);
  }
}