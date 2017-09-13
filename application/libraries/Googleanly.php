<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();
ob_get_clean();

//require_once ( APPPATH . '/third_party/youtubeAPI/vendor/google/apiclient/src/Google/Client.php');
//require_once (APPPATH .'/third_party/youtubeAPI/vendor/google/apiclient/src/Google/Service/YouTubeAnalytics.php');

 require_once APPPATH . '/third_party/GoogleAnalytics/vendor/autoload.php';
 require_once APPPATH . '/third_party/GoogleAnalytics/src/Google/Client.php';
 require_once APPPATH . '/third_party/GoogleAnalytics/src/Google/Service/YouTubeAnalytics.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Googleanly {

    function __construct() {

       

//$results = getResults($analytics, $profile);
//$totlaUsers = get_total($analytics,$profile);
//printResults($results);
    }

    function getServices() {
        $view_folder = "";
        $CI = &get_instance();

        $CI->load->library("session");
		$service_account_email = "";
        $google_analytics_settingsWhere = "config_key='google_analytics_settings'";
        $defaultDashboard2 = $CI->common_model->get_records(CONFIG, array('value'), '', '', $google_analytics_settingsWhere);

        
        if((empty($defaultDashboard2)) || (empty($defaultDashboard2[0]['value']))){
            $CI->session->set_flashdata('error', lang('GOOGLE_ANALITICS_ERROR')); 
            header("Location: ../CampaignReport");
            exit;
        }

        $dbResult = (array) json_decode($defaultDashboard2[0]['value']);
        //pr($dbResult); exit;	
        // Creates and returns the Analytics service object.
        // Load the Google API PHP Client Library.

        //require_once APPPATH . '/third_party/GoogleAnalytics/vendor/autoload.php';
        $path = APPPATH . '/third_party/GoogleAnalytics/';
        // Use the developers console and replace the values with your
        // service account email, and relative location of your key file.								
        $service_account_email = "'" . $dbResult['service_account_email'] . "'";
        //$key_file_location = 'web API-849de8eff1c4.p12';
        // Create and configure a new client object.
        $client = new Google_Client();
        $client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));
        $client->setApplicationName('"' . $dbResult['application_name'] . '"');
        $analytics = new Google_Service_Analytics($client);

        $dbResult['google_app_credentials'] = str_replace(" ", "_", $dbResult['google_app_credentials']);
        $urls = $dbResult['google_app_credentials'];
        
        
        $client->setAuthConfig($path . "upload_json/$urls");
        putenv("GOOGLE_APPLICATION_CREDENTIALS=upload_json/$urls");
        $client->useApplicationDefaultCredentials();
        return $analytics;
    }

    function getFirstprofileId(&$analytics) {
        // Get the user's first view (profile) ID.
        // Get the list of accounts for the authorized user.
        $accounts = $analytics->management_accounts->listManagementAccounts();
       
        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            // Get the list of properties for the authorized user.
            $properties = $analytics->management_webproperties
                    ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                // Get the list of views (profiles) for the authorized user.
                $profiles = $analytics->management_profiles
                        ->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    // Return the first view (profile) ID.
                    return $items[0]->getId();
                } else {
                    throw new Exception('No views (profiles) found for this user.');
                }
            } else {
                throw new Exception('No properties found for this user.');
            }
        } else {
            throw new Exception('No accounts found for this user.');
        }
    }

    function get_total($analytics, $profile) {
        $type = 'pageviews';
        $optParams = array('max-results' => '100000');

        try {

            $results = $analytics->data_ga->get('ga:' . $profile, '2015-01-01', 'today', 'ga:' . $type, $optParams);

            $ga_total = 0;
            if (count($results->getRows()) > 0) {
                foreach ($results->getRows() as $row) {
                    foreach ($row as $cell) {
                        $ga_total = $cell;
                    }
                }
            } else {
                return 0;
            }

            return $ga_total;
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            die($error);
        }
    }


/*
@Author : Brijesh Tiwari
@Desc   : This function is to get page visited by source my specific date
@Input  : 
@Output : 
@Date   : 19/04/2016
*/

function visitorCountBySource($analytics, $profile,$startData,$endData,$type='source') {
        
        
        $optParams = array('dimensions' => 'ga:source,ga:month,ga:medium,ga:year');

        try {

            $results = $analytics->data_ga->get('ga:' . $profile, $startData, $endData, 'ga:' . $type, $optParams);


            $gaData = array();
            if (count($results->getRows()) > 0) {
               
                foreach ($results->getRows() as $key => $row) {
                   
                     $gaData[$key]['ref_name'] =$row[0];
                     $gaData[$key]['month'] =$row[1];
                     $gaData[$key]['source'] =$row[2];
                     $gaData[$key]['year'] =$row[3];
                     $gaData[$key]['page_view'] =$row[4];
                     
                    
                }
            } else {
                return 0;
            }

            return $gaData;
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            die($error);
        }
    }




/*
@Author : Brijesh Tiwari
@Desc   : This function is to get user visit count my specific date
@Input  : 
@Output : 
@Date   : 11/04/2016
*/

function visitorCount($analytics, $profile,$startData,$endData,$type='pageviews') {
        //$type = 'pageviews';
        //$type = 'organicSearches';
        
        $optParams = array('max-results' => '100000');

        try {

            $results = $analytics->data_ga->get('ga:' . $profile, $startData, $endData, 'ga:' . $type, $optParams);

            $ga_total = 0;
            if (count($results->getRows()) > 0) {
               
                foreach ($results->getRows() as $row) {
                    foreach ($row as $cell) {
                        $ga_total = $cell;
                    }
                }
            } else {
                return 0;
            }

            return $ga_total;
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            die($error);
        }
    }

}
