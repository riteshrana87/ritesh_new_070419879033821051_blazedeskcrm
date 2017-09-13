<?php

function getService() {
    // Creates and returns the Analytics service object.
    // Load the Google API PHP Client Library.

    require_once 'vendor/autoload.php';

    // Use the developers console and replace the values with your
    // service account email, and relative location of your key file.
    $service_account_email = 'testapi210316@web-api-1254.iam.gserviceaccount.com';
    $key_file_location = 'web API-849de8eff1c4.p12';

    // Create and configure a new client object.

    $client = new Google_Client();
    $client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));
//    $client->setDefaultOption('verify', false);
    $client->setApplicationName("Client for testapi210316");
    $analytics = new Google_Service_Analytics($client);

    // Read the generated client_secrets.p12 key.
//  $key = file_get_contents($key_file_location);
//  $cred = new Google_Auth_AssertionCredentials(
//      $service_account_email,
//      array(Google_Service_Analytics::ANALYTICS_READONLY),
//      $key
//  );
//  $client->setAssertionCredentials($cred);
//  if($client->getAuth()->isAccessTokenExpired()) {
//    $client->getAuth()->refreshTokenWithAssertion($cred);
//  }
//    https://www.googleapis.com/auth/analytics.readonly
    $client->setAuthConfig('web API-318d73fdf462.json');
    putenv('GOOGLE_APPLICATION_CREDENTIALS=web API-318d73fdf462.json');
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

function getResults(&$analytics, $profileId) {
    // Calls the Core Reporting API and queries for the number of sessions
    // for the last seven days.
   return $analytics->data_ga->get(
		    'ga:' . $profile,
		    '2015-01-01',
		    date("Y-m-d"),
		    'ga:visits',
		    array(
		        'filters' => 'ga:pagePath==/news',
		        'dimensions' => 'ga:pagePath',
		        'metrics' => 'ga:pageviews',
		        'sort' => '-ga:pageviews',
		        'max-results' => '10000'
		    )
		);
   // return $analytics->data_ga->get('ga:' . $profileId,'2015-01-01', '2016-03-24', 'ga:sessions');
}

function printResults(&$results) {
    // Parses the response from the Core Reporting API and prints
    // the profile name and total sessions.
    if (count($results->getRows()) > 0) {

        // Get the profile name.
        $profileName = $results->getProfileInfo()->getProfileName();

        // Get the entry for the first entry in the first row.
        $rows = $results->getRows();
        $sessions = $rows[0][0];

        // Print the results.
        print "First view (profile) found: $profileName\n";
        print "Total sessions: $sessions\n";
    } else {
        print "No results found.\n";
    }
}

function get_total($analytics,$profile){
	$type = 'Pageviews';
	$optParams = array('max-results' => '10000');

	try {
		
		/*
		$results = $analytics->data_ga->get(
		    'ga:' . $profile,
		    '2012-01-01',
		    date("Y-m-d"),
		    'ga:visits',
		    array(
		        'filters' => 'ga:pagePath==/news',
		        'dimensions' => 'ga:pagePath',
		        'metrics' => 'ga:pageviews',
		        'sort' => '-ga:pageviews',
		        'max-results' => '10000'
		    )
		);*/
		//$results = $analytics->data_ga->get('ga:pageviews','ga:uniquePageviews','ga:' . $profile, '7daysAgo', 'today', 'ga:' . $type, $optParams);
	$results = $analytics->data_ga->get('ga:' . $profile, '2015-01-01', date("Y-m-d"), 'ga:' . $type, $optParams);
		echo "<pre>";
		print_r($results); exit();
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
	//print_r($ga_total); exit();
	
}

$analytics = getService();
$profile = getFirstProfileId($analytics);
//$results = getResults($analytics, $profile);
$totlaUsers = get_total($analytics,$profile);
print_r($totlaUsers); exit();
//printResults($results);
?>
