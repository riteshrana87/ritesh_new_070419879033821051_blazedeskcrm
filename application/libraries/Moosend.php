<?php

class Moosend
{

	function __construct()
	{
            //$apiKey = 'b59949e2-0da7-406e-b79a-60c5ffbda045';
            $apiKey = $this->get_api_key();
            //$list_id = $this->get_list_id();
            if (empty($apiKey)) {
                    throw new \InvalidArgumentException('apiKey is a required parameter when a creating MoosendApi instance');
            } elseif (is_numeric($apiKey)) {
                    throw new \InvalidArgumentException('Please provide a valid API key. API key must be a string');
            }

            $this->_apiKey = $apiKey ;
            //$this->_list_id = $list_id;
            $this->api_endpoint = 'http://api.moosend.com/v2/';
	}

	public function get_active_mailing_list()
	{
		$page = 1;
                $PageSize = 100;
		return $this->execute("get","lists/$page/$PageSize");
	}

        public function get_mailing_list_by_id()
	{
		return $this->execute("get","lists/$this->_list_id/details");
	}

	public function get_subscribers()
	{
		return $this->execute("get","lists/$this->_list_id/subscribers/1");
	}

	public function get_unsubscribe_contact()
	{
		return $this->execute("get","lists/$this->_list_id/subscribers/Unsubscribed");
	}

	public function get_subscribe_contact($list_id)
	{
		return $this->execute("get","lists/$list_id/subscribers/Subscribed");
	}

	public function get_subscriber_by_email($list_id,$email)
	{
		$args = array("Email"=>$email);
		return $this->execute("get","subscribers/$list_id/view",$args);
	}

	public function add_subscriber($list_id,$name,$email)
	{
		$args = array("Name"=>$name,"Email"=>$email);
		return $this->execute("post","subscribers/$list_id/subscribe",$args);
	}

	public function unsubscribe_subscribers($email)
	{
		$args = array("Email"=>$email);
		return $this->execute("post","subscribers/$this->_list_id/unsubscribe",$args);
	}

	public function remove_subscribers($list_id,$email)
	{
		$args = array("Email"=>$email);
		return $this->execute("post","subscribers/$list_id/remove",$args);
	}

	public function update_subscriber($list_id,$name,$email,$subscriber_id)
	{

		$args = array('SubscriberID'=>$subscriber_id,'MailingListID'=>$list_id,'Email'=>$email,'Name'=>$name);
		return $this->execute("post","subscribers/$list_id/update/$subscriber_id",$args);
	}

        public function addSubscribersMany($listId,$data)
        {
            $args = array('Subscribers'=>$data);
            return  $this->execute("post","subscribers/$listId/subscribe_many",$args);

        }

        public function removeSubscibersMany($listId,$data)
        {
            $args = array('Emails'=>$data);
            return  $this->execute("post","subscribers/$listId/remove_many",$args);
        }

        public function createLists($listName)
        {
            $args = array('Name'=>$listName);
            $createLists =  $this->execute("post","lists/create",$args);

            return $createLists->Context;
        }

        public function updateListsById($listId,$listName)
        {
             $args = array('Name'=>$listName);
             $updateLists =  $this->execute("post","lists/$listId/update",$args);
             return $updateLists->Context;
        }

        public function deleteLists($listId)
        {
            return  $this->execute("delete","lists/$listId/delete");
        }

        private function execute($http_verb,$method= null,$args = null)
	{
            if(is_array($args) && !empty($args))
            {
                    $query = http_build_query($args);
            }else
            {
                    $query = '';
            }

            $url = $this->api_endpoint.$method.'.json?'.$query.'&apikey='.$this->_apiKey;

            $handle = curl_init();
            curl_setopt($handle, CURLOPT_URL, $url);

            if($http_verb == 'post')
            {
                curl_setopt($handle, CURLOPT_POSTFIELDS, $query);
            }

            if($http_verb == 'delete')
            {
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
            }

            curl_setopt($handle, CURLOPT_HEADER, 'Content-type: application/json');
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $response = json_decode(curl_exec($handle));
           // if(curl_error($handle)) trigger_error(curl_error($handle), E_USER_ERROR);
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            if(!(($httpCode == '200') || ($httpCode == '204')))
            {
                //trigger_error('API call failed. Server returned status code '.$httpCode, E_USER_ERROR);
                $response = 'API call failed. Server returned status code '.$httpCode;
            }
                
            curl_close($handle);
            
            return $response;
	}

    private function get_api_key()
    {
        $CI = &get_instance();
        $config_api_key= "config_key='moosend_configuration'";
        $api_key_str = $CI->common_model->get_records(CONFIG, array('value'), '', '', $config_api_key);

        $mailchimp_data = json_decode($api_key_str[0]['value']);
        return $mailchimp_data->api_key;

    }

}

 ?>