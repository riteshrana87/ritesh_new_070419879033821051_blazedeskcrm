<?php


class CampaignMonitor
{
    private $api_key;
    

  
    
    public function __construct()
    { 
        //$this->api_key = '33c1d024acf992cbd79d2cc3d5cbcb65';
        //$this->list_id = '0cef41c25c9d3a7e36a35fd82464fd81';
        $this->api_key = $this->get_api_key();
        //$this->list_id = $this->get_list_id();
    }

    public function get_active_subscribers($list_id)
    {
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_lists.php';
       
        $auth = array('api_key' => $this->api_key);
        $wrap = new CS_REST_Lists($list_id, $auth);
        $result = $wrap->get_active_subscribers('2016-05-01', 'email', 'asc');
       
        
        if((isset($result->response->Code) && $result->response->Code == '50') || ((isset($result->response->Code) && $result->response->Code == '101')))
        {
            return $result->response;
        }else
        {
            return $result->response->Results;
        }
        
        
    }
    
    public function get_unsubscribed_subscribers($list_id)
    {
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_lists.php';
        $auth = array('api_key' => $this->api_key);
        $wrap = new CS_REST_Lists($list_id, $auth);
        $result = $wrap->get_unsubscribed_subscribers('2016-05-01', 'email', 'asc');
        
        return $result->response->Results;
    }
    
    public function get_lists()
    {
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_lists.php';
        $auth = array('api_key' => $this->api_key);
        $wrap = new CS_REST_Lists($this->list_id, $auth);
        $result = $wrap->get();
        return $result->response;
        
    }
    
    public function add_subscriber($data)
    {
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_subscribers.php';
        $auth = array('api_key' => $this->api_key);
        $wrap = new CS_REST_Subscribers($data['listId'], $auth);
        
        $data1 = array('EmailAddress' => $data['email_address'],'Name' => $data['contact_name'],'Resubscribe' => true);
        $result = $wrap->add($data1);
       
        return $result->response;
    }
    
    public function addSubscribersByLists($data)
    {
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_subscribers.php';
        $auth = array('api_key' => $this->api_key);
        $wrap = new CS_REST_Subscribers($data['listID'], $auth);
        
        $data1 = array('EmailAddress' => $data['email_address'],'Name' => $data['contact_name'],'Resubscribe' => true);
        $result = $wrap->add($data1);
       
        
        return $result->response;
    }
    
    public function update_subscriber($data)
    {
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_subscribers.php';
        $auth = array('api_key' => $this->api_key);
        $wrap = new CS_REST_Subscribers($data['listID'], $auth);
        
        $data1 = array('EmailAddress' => $data['email_address'],'Name' => $data['contact_name'],'Resubscribe' => true);
        
        $result = $wrap->update($data['old_email_address'], $data1);
        return $result->response;
    }
    
    public function make_subscribe($data)
    {
        
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_subscribers.php';
        $auth = array('api_key' => $this->api_key);
        $wrap = new CS_REST_Subscribers($data['listID'], $auth);
        
        $data_update = array('EmailAddress' =>$data['email_address'],'Resubscribe' => true);
        $result = $wrap->update($data['email_address'], $data_update);
        
        return $result->response;
        
    }
    
    public function make_unsubscribe($data)
    {
        
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_subscribers.php';
        $auth = array('api_key' => $this->api_key);
        $wrap = new CS_REST_Subscribers($data['listID'], $auth);
        
        $result = $wrap->unsubscribe($data['email_address']);
        
        return $result->response;
        
    }


    public function delete_subscriber($data)
    {
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_subscribers.php';
        $auth = array('api_key' => $this->api_key);
        $wrap = new CS_REST_Subscribers($data['listID'], $auth);
        
        $result = $wrap->delete($data['email_address']);
       
        return $result->response;
        
    }
    
    public function deleteSubscribersByLists($data)
    {
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_subscribers.php';
        $auth = array('api_key' => $this->api_key);
        $wrap = new CS_REST_Subscribers($data['listID'], $auth);
        
        $result = $wrap->delete($data['email_address']);
       
       
        return $result->response;
    }
    
    public function getClientId()
    {
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_general.php';
        $auth = array('api_key' => $this->api_key);
        $wrap = new CS_REST_General($auth);
        $result = $wrap->get_clients();
        
        
        return $result->response[0]->ClientID;
    }
    
    public function create_lists($data)
    {
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_lists.php';
        $auth = array('api_key' => $this->api_key);
        $wrap = new CS_REST_Lists($this->api_key, $auth);
        // $client_id = 'f44be94192e9091c70a8664645345814';
        $client_id = $this->getClientId();
        $result = $wrap->create($client_id,$data);
         
        return $result->response;
    }
    
    public function deleteLists($listId)
    {
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_lists.php';
        $auth = array('api_key' => $this->api_key);
        $wrap = new CS_REST_Lists($listId, $auth);
        $result = $wrap->delete();
        
        return $result;
    }
    
    public function getAllLists()
    {
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_clients.php';
        $auth = array('api_key' => $this->api_key);
        $client_id = $this->getClientId();
        $wrap = new CS_REST_Clients($client_id, $auth);
        $result = $wrap->get_lists();
        
        return $result;
    }
    
    public function update_lists($data)
    {
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_lists.php';
        $auth = array('api_key' => $this->api_key);
        $wrap = new CS_REST_Lists($data['listId'], $auth);
        $result = $wrap->update($data);
        
        return $result;
    }

    private function get_api_key()
    {
        $CI = &get_instance();
        $config_api_key= "config_key='campaign_monitor_configuration'";
        $api_key_str = $CI->common_model->get_records(CONFIG, array('value'), '', '', $config_api_key);
        
        $mailchimp_data = json_decode($api_key_str[0]['value']);
        return $mailchimp_data->api_key;

    }
    
    private function get_list_id()
    {
        $CI = &get_instance();
        $config_api_key= "config_key='campaign_monitor_configuration'";
        $api_key_str = $CI->common_model->get_records(CONFIG, array('value'), '', '', $config_api_key);
        
        $mailchimp_data = json_decode($api_key_str[0]['value']);
        return $mailchimp_data->list_id;

    }
    
    
    
    public function create_campaign($data)
    {
        require_once APPPATH.'/third_party/CampaignMonitor/csrest_campaigns.php';
        $auth = array('api_key' => $this->api_key);
        $wrap = new CS_REST_Campaigns(NULL, $auth);

        $result = $wrap->create('Campaigns Client ID', array(
            'Subject' => 'Campaign Subject',
            'Name' => 'Campaign Name',
            'FromName' => 'Campaign From Name',
            'FromEmail' => 'Campaign From Email Address',
            'ReplyTo' => 'Campaign Reply To Email Address',
            'HtmlUrl' => 'Campaign HTML Import URL',
            # 'TextUrl' => 'Optional campaign text import URL',
            'ListIDs' => array('First List', 'Second List'),
            'SegmentIDs' => array('First Segment', 'Second Segment')
        ));

        
    }
}
