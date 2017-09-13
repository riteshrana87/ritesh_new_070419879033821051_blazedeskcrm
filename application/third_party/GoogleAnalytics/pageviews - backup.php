<?php

function getService() {
    // Creates and returns the Analytics service object.
    // Load the Google API PHP Client Library.

    require_once 'vendor/autoload.php';

    // Use the developers console and replace the values with your
    // service account email, and relative location of your key file.
    $service_account_email = 'testapi210316@web-api-1254.iam.gserviceaccount.com';
 	//$key_file_location = 'web API-849de8eff1c4.p12';
    // Create and configure a new client object.

    $client = new Google_Client();
    $client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));

    $client->setApplicationName("Client for testapi210316");
    $analytics = new Google_Service_Analytics($client);

    $client->setAuthConfig('web API-d981f03be4a5.json');
    putenv('GOOGLE_APPLICATION_CREDENTIALS=web API-d981f03be4a5.json');
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


function get_total($analytics,$profile){
	$type = 'pageviews';
	$optParams = array('max-results' => '100');

	try {
	
		$results = $analytics->data_ga->get('ga:' . $profile, '7daysAgo', 'today', 'ga:' . $type, $optParams);
	
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

$analytics = getService();

//pr($analytics); exit;
$profile = getFirstProfileId($analytics);
$totlaPageviews = get_total($analytics,$profile);
    
//$results = getResults($analytics, $profile);
//$totlaUsers = get_total($analytics,$profile);
echo $totlaPageviews;
//printResults($results);
?>
