<?php

defined ('BASEPATH') OR exit('No direct script access allowed');

class Newsletter extends CI_Controller {

    function __construct() {
        
        set_time_limit(0);
        parent::__construct ();
        if(checkPermission('Newsletter','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->module = $this->viewname  = $this->router->fetch_module();
        $this->user_info = $this->session->userdata ('LOGGED_IN');  //Current Login information
        // $this->mailchimp_list_id = $this->config_list_id();
        $this->newsletter_type = get_newsletter_type();
        
        if($this->newsletter_type == '1' || $this->newsletter_type == '2'  ||  $this->newsletter_type == '3' || $this->newsletter_type == '4')
        {
            
            if($this->newsletter_type == '1')
            {
                $this->load->library('MailChimp');
            }else if($this->newsletter_type == '2')
            {
                $this->load->library('CampaignMonitor');
            }else if($this->newsletter_type == '3')
            {
                $this->load->library('Moosend');
            }else if($this->newsletter_type == '4')
            {
                $this->load->library('GetResponse');
            }
            
        }else
        {
            redirect('/Dashboard');
        }
    }

    /*
      @Author : sanket Jayani
      @Desc   : Newsletter API 
      @Input  : 
      @Output :
      @Date   : 27/01/2016
     */

    public function index() {
        
        $data['header'] = array('menu_module' => 'crm');
        $data['project_incidenttype_view'] = $this->module . '/' . $this->viewname;
        
        if($this->newsletter_type == '1')
        {
            $mailchimpListsArr = getNewsletterListsByType('1');
           
            $data['newsletter_data'] = [];
            
            if(count($mailchimpListsArr) > 0 && !empty($mailchimpListsArr))
            {
                foreach ($mailchimpListsArr as $mKey => $mVal)
                {
                    $list_param = array ('fields'=> 'name,id,stats.member_count,stats.unsubscribe_count');
                    $list_name =  $this->mailchimp->get("lists/$mKey",$list_param);
                    
                    if(isset($list_name['status']) && $list_name['status'] == '403')
                    {
                        $this->session->set_flashdata('error',lang('INVALID_MAILCHIMP_API_KEY'));
                        break;
                    }
                    $data['mailchimp_list_name'] =  $list_name['name'];
                    $total_member_count = $list_name['stats']['member_count'] + $list_name['stats']['unsubscribe_count'];

                    $start=0; $limit=$total_member_count; $sort_field='created'; $sort_dir='DESC';$fields = 'members.list_id,members.id,members.email_address,members.merge_fields,members.unique_email_id,members.status,members.timestamp_opt';
                    $_params = array("filters" => '','fields'=>$fields, "offset" => $start, "count" => $limit, "sort_field" => $sort_field, "sort_dir" => $sort_dir);
                    $result = $this->mailchimp->get("lists/$mKey/members", $_params);
                    
                    if(is_array($result['members']) && !empty($result['members']) && count($result['members']) > 0)
                    {
                        $data['newsletter_data'][$mVal] = $result['members'];
                    }
                }
            }
        }else if($this->newsletter_type == '2')
        {
            
            $campaignMonitorListsArr = getNewsletterListsByType('2');
            
            $data['campaign_monitor_data'] = [];
            $cmonitor_data = [];
            if(count($campaignMonitorListsArr) > 0 && !empty($campaignMonitorListsArr))
            {
                foreach ($campaignMonitorListsArr as $mKey => $mVal)
                {
                    $subscribe_subscriber = $this->campaignmonitor->get_active_subscribers($mKey);
                    
                    if(isset($subscribe_subscriber->Code) && $subscribe_subscriber->Code == '50')
                    {
                        //message : Must supply a valid HTTP Basic Authorization header
                        $this->session->set_flashdata('error',lang('INVALID_CAMPAIGN_MONITOR_API_KEY'));
                        break;
                    }
                    
                    foreach ($subscribe_subscriber as $subscriber)
                    {
                        $status = '';
                        if($subscriber->State == 'Active')
                        {
                            $status = lang('SUBSCRIBED');
                        }else
                        {
                            $status = lang('UNSUBSCRIBED');
                        }
                        $cmonitor_data[] = array ('EmailAddress'=>$subscriber->EmailAddress,'Name'=>$subscriber->Name,'State'=>$status,'Date'=>$subscriber->Date,'list_id'=>$mKey,'list_name'=>$mVal);
                    }
                    
                    $unsubscribe_subscriber = $this->campaignmonitor->get_unsubscribed_subscribers($mKey);

                   
                    if(count($unsubscribe_subscriber) > 0)
                    {
                        foreach ($unsubscribe_subscriber as $unsubscriber)
                        {
                            $status = '';
                            if($unsubscriber->State == 'Unsubscribed')
                            {
                                $status = lang('UNSUBSCRIBED');
                            }else
                            {
                                $status = lang('SUBSCRIBED');
                            }
                            $cmonitor_data[] = array ('EmailAddress'=>$unsubscriber->EmailAddress,'Name'=>$unsubscriber->Name,'State'=>$status,'Date'=>$unsubscriber->Date,'list_id'=>$mKey,'list_name'=>$mVal);
                        }
                    }
                    $data['campaign_monitor_data'] = $cmonitor_data;
                    
                }
            }
            
        }else if($this->newsletter_type == '3')
        {
            $moosendListsArr = getNewsletterListsByType('3');
            $data['moosend_data'] = [];
            
            if(count($moosendListsArr) > 0 && !empty($moosendListsArr))
            {
                foreach ($moosendListsArr as $mKey => $mVal)
                {
                    $subscribe_contact = $this->moosend->get_subscribe_contact($mKey);
                    
                    if(isset($subscribe_contact->Code) && $subscribe_contact->Code == '104')
                    {
                        $this->session->set_flashdata('error',lang('INVALID_MOOSEND_API_KEY'));
                        break;
                    }
                    $arr_subscriber = $subscribe_contact->Context->Subscribers;
                    $all_subscriber = $arr_subscriber;

                    if(is_array($all_subscriber) && !empty($all_subscriber) && count($all_subscriber) > 0)
                    {
                        foreach ($all_subscriber as $subscriber)
                        {
                            $subscriber->list_id = $mKey;
                            $subscriber->list_name = $mVal;
                            $data['moosend_data'][] = $subscriber;
                        }
                    }
                }
            }
           
        }else if($this->newsletter_type == '4')
        {
            $bv = $this->getresponse->ping();
            if($bv == null)
            {
                $this->session->set_flashdata('error',lang('INVALID_GET_RESPONSE_API_KEY'));
            }else
            {
                $getResponseListsArr = getNewsletterListsByType('4');
                $data['get_response_data'] = []; 
                
                if(count($getResponseListsArr) > 0 && !empty($getResponseListsArr))
                {
                    foreach ($getResponseListsArr as $mKey => $mVal)
                    {
                        $params = array($mKey);
                        $subscribed_contact = $this->getresponse->getContacts($params);
                        
                        if(!empty($subscribed_contact) && count($subscribed_contact) > 0)
                        {
                            foreach ($subscribed_contact as $subKey => $subscriber)
                            {
                                $subscriber->list_id = $mKey;
                                $subscriber->list_name = $mVal;
                                $subscriber->contact_id = $subKey;
                                $data['get_response_data'][] = $subscriber;
                            }
                        }
                    }
                }
            }
        }
        
       
        $data['newsletter_type'] = $this->newsletter_type;
       
        if ($this->input->is_ajax_request ()) {
       
            if ($this->input->post ('project_ajax')) {
                $this->load->view ('/Newsletter', $data);
            } else {
                $this->load->view ('/NewsletterAjaxList', $data);
            }
        } else {
            $data['main_content'] = 'Newsletter';
            $this->parser->parse ('layouts/DashboardTemplate', $data);
        }
        
    }

   
    private function pagingConfig($config, $page_url) 
    {
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01 pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="' . $page_url . '">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['first_link'] = '&lt;&lt;';
        $config['last_link'] = '&gt;&gt;';

        $this->pagination->cur_page = 4;

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }
    
    function mailchimp_make_subscribe()
    {
        $email_address = $this->input->get('email_address');
        $listsId = $this->input->get('listsId');
        $subscriber_hash = $this->mailchimp->subscriberHash($email_address);
       // $result = $this->mailchimp->patch("lists/$this->mailchimp_list_id/members/$subscriber_hash", ['status'=>'subscribed']);
         $result = $this->mailchimp->patch("lists/$listsId/members/$subscriber_hash", ['status'=>'subscribed']);
        
        $this->update_contact_subscribe_newsletter($email_address,'1');
        if ($result) {
            
            $msg = lang('SUCCESS_MAKE_SUBSCRIBE');
            $this->session->set_flashdata('message', $msg);
        } else {
        
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('error',$msg);
        }
        
        redirect('Newsletter');
       
    }
    
    function mailchimp_make_unsubscribe()
    {
        $email_address = $this->input->get('email_address');
        $listsId = $this->input->get('listsId');
        $subscriber_hash = $this->mailchimp->subscriberHash($email_address);
        //$result = $this->mailchimp->patch("lists/$this->mailchimp_list_id/members/$subscriber_hash", ['status'=>'unsubscribed']);
        $result = $this->mailchimp->patch("lists/$listsId/members/$subscriber_hash", ['status'=>'unsubscribed']);
        
        $this->update_contact_newsletter($email_address,'1');
        if ($result) {
            
            $msg = lang('SUCCESS_MAKE_UNSUBSCRIBE');
            $this->session->set_flashdata('message', $msg);
        } else {
        
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('error',$msg);
        }
        
        redirect('Newsletter');
    }
    
    function delete_from_mailchimp()
    {
        $email_address = $this->input->get('email_address');
        $listsId = $this->input->get('listsId');
        $subscriber_hash = $this->mailchimp->subscriberHash($email_address);
       // $result = $this->mailchimp->delete("lists/$this->mailchimp_list_id/members/$subscriber_hash");
        $result = $this->mailchimp->delete("lists/$listsId/members/$subscriber_hash");
        
        $this->update_contact_newsletter($email_address,'1');
        if ($result == false) {
            
            $msg = lang('SUCCESS_DELETE_FROM_MAILCHIMP');
            $this->session->set_flashdata('message', $msg);
        } else {
        
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('error',$msg);
        }
        
        redirect('Newsletter');
    }
    
    function delete_from_cmonitor()
    {
        $data['email_address'] = $this->input->get('email_address');
        $list_id = $this->input->get('list_id');
        $data['listID'] = $list_id;
        $this->campaignmonitor->make_subscribe($data);
        $result = $this->campaignmonitor->delete_subscriber($data);
        
        $this->update_contact_newsletter($data['email_address'],'2');
        if ($result == '') {
            
            $msg = lang('SUCCESS_DELETE_FROM_CMONITOR');
            $this->session->set_flashdata('message', $msg);
        } else {
        
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('error',$msg);
        }
        
        redirect('Newsletter');
        
    }
    
    function cmonitor_make_subscribe()
    {
        
        $email_address = $this->input->get('email_address');
        $list_id = $this->input->get('list_id');
        
        $data['email_address'] = $email_address;
        $data['listID'] = $list_id;
        $result = $this->campaignmonitor->make_subscribe($data);
      
        $this->update_contact_subscribe_newsletter($email_address,'2');
        if ($result == '') {
            
            $msg = lang('SUCCESS_MAKE_SUBSCRIBE');
            $this->session->set_flashdata('message', $msg);
        } else {
        
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('error',$msg);
        }
        
        redirect('Newsletter');
       
    }
    
    function cmonitor_make_unsubscribe()
    {
        
        $email_address = $this->input->get('email_address');
        $list_id = $this->input->get('list_id');
        $data['email_address'] = $email_address;
        $data['listID'] = $list_id;
        $result = $this->campaignmonitor->make_unsubscribe($data);
      
        $this->update_contact_newsletter($email_address,'2');
        if ($result == '') {
            
            $msg = lang('SUCCESS_MAKE_UNSUBSCRIBE');
            $this->session->set_flashdata('message', $msg);
        } else {
        
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('error',$msg);
        }
        
        redirect('Newsletter');
       
    }
    
    private function update_contact_newsletter($email,$configurewith)
    {
        //updating contact master
        $contact_data['is_newsletter'] = 0;
        $contact_data['configure_with'] = $configurewith;
        $where = array('email' => $email);
        $this->common_model->update(CONTACT_MASTER, $contact_data,$where);
    }
    
    private function update_contact_subscribe_newsletter($email,$configurewith)
    {
        //updating contact master
        $contact_data['is_newsletter'] = 1;
        $contact_data['configure_with'] = $configurewith;
        $where = array('email' => $email);
        $this->common_model->update(CONTACT_MASTER, $contact_data,$where);
    }
    
    public function delete_contact_getResponse()
    {
        $contact_id = $this->input->get('contact_id');
        $email_address = $this->input->get('email');
        $result = $this->getresponse->deleteContact($contact_id);
      
        $this->update_contact_newsletter($email_address,'4');
        if ($result->deleted == '1') 
        {
            $this->session->set_flashdata('message', lang('SUCCESS_MAKE_UNSUBSCRIBE'));
        } else {
            $this->session->set_flashdata('error',lang('error_msg'));
        }
        
        redirect('Newsletter');
    }
    
    function moosend_make_subscribe()
    {
        
        $email_address = $this->input->get('email_address');
       
        $data['email_address'] = $email_address;
        $email_detail = $this->moosend->get_subscriber_by_email($email_address);
        $contact_name = $email_detail->Context->Name;
        $contact_email = $email_detail->Context->Email;
        
        //remove contact first
        $remove_contact = $this->moosend->remove_subscribers($email_address);
       
        if($remove_contact->Code == '0')
        {
            //if remove successfully than add it 
            $add_contact = $this->moosend->add_subscriber($contact_name,$contact_email);
        }
       
        if ($result == '') {
            
            $msg = lang('SUCCESS_MAKE_SUBSCRIBE');
            $this->session->set_flashdata('message', $msg);
        } else {
        
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('error',$msg);
        }
        
        redirect('Newsletter');
       
    }
    
    public function moosend_make_unsubscribe()
    {
       $email_address = $this->input->get('email_address');
       $unsubscribe_contact = $this->moosend->unsubscribe_subscribers($email_address);
       
       if($unsubscribe_contact->Code == '0')
       {
            $msg = lang('SUCCESS_MAKE_UNSUBSCRIBE');
            $this->session->set_flashdata('message', $msg);
       }else
       {
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('error',$msg);
       }
       
        redirect('Newsletter');
    }
    
    public function moosend_delete_contact()
    {
       
        $email_address = $this->input->get('email_address');
        $list_id = $this->input->get('list_id');
        $delete_contact = $this->moosend->remove_subscribers($list_id,$email_address);
        
        $this->update_contact_newsletter($email_address,'3');
        if($delete_contact->Code == '0')
        {
            $msg = lang('SUCCESS_DELETE_FROM_MOOSEND');
            $this->session->set_flashdata('message', $msg);
           
        }else
        {
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('error',$msg);
        }
        
        redirect('Newsletter');
    }
}
