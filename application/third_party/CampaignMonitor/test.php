<?php

//require_once 'csrest_general.php';

//require_once 'csrest_subscribers.php';

require_once 'csrest_lists.php';

$auth = array('api_key' => '33c1d024acf992cbd79d2cc3d5cbcb65');
//$wrap = new CS_REST_General($auth);
//$result = $wrap->get_clients();

//$wrap = new CS_REST_Subscribers('0cef41c25c9d3a7e36a35fd82464fd81', $auth);

/* =======================Start for getting subscriber ful ldetail============================== */

//$result = $wrap->get('sanket.jayani@c-metric.com');

/* =======================End for getting subscriber ful ldetail============================== */


/* =======================Start for Add Subscriber============================== */

$data = array('EmailAddress' => 'rupesh.jorkar@c-metric.com','Name' => 'Rupesh Jorkar','Resubscribe' => true);
//$result = $wrap->add($data);
/* =======================End for Add Subscriber============================== */

$wrap = new CS_REST_Lists('0cef41c25c9d3a7e36a35fd82464fd81', $auth);
$result = $wrap->get_active_subscribers('2016-05-01', 1, 50, 'email', 'asc');
echo  "<pre>";
print_r($result->response);
echo  "</pre>";

?>