<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@Author : Sanket Jayani
@Desc   : Model For Request Budget Module
@Input 	: 
@Output	: 
@Date   : 29/02/2016
*/
class Settings_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function getSettingsData($key='')
    {
        $this->db->select('*');
        if($key){
            $this->db->where('config_key',$key);

        }else{

            $this->db->where('config_key','facebook_page_url');
            $this->db->or_where('config_key','linkedin_api_key');
            $this->db->or_where('config_key','twitter_username');
            $this->db->or_where('config_key','linkedin_company_id');
            $this->db->or_where('config_key','company_profile_image');
            $this->db->or_where('config_key','facebook_app_id');
            $this->db->or_where('config_key','facebook_page_id');
            $this->db->or_where('config_key','facebook_app_secret');
            $this->db->or_where('config_key','youtube_channel_id');
        }
        $query = $this->db->get('blzdsk_config');
        $settings_obj = $query->result();
        
        
        foreach ( $settings_obj as $settings_key => $settings_value)
        {
            $settings_data[$settings_value->config_key] = $settings_value->value;
        
        }
        
        return  $settings_data; 
    }
    
    function updateSettings($data='')
    {
        if($data){
            $updateData = $data;
        }else{
            $updateData = $this->input->post();
        }
    	
        if(count($updateData) > 0)
        {
        	
            foreach ($updateData as $settings_key => $settings_value)
            { 
               if($settings_key != 'submit_btn')
                {
                    if($this->getSettingsData($settings_key)){
                        $data['config_key'] = $settings_key;
                        $data['value'] = $settings_value; 
                        $where = array('config_key' => $settings_key);                 
                        $this->common_model->update(CONFIG, $data,$where);
                    }else{
                        $data['config_key'] = $settings_key;
                        $data['value'] = $settings_value; 
                        $where = array('config_key' => $settings_key);    
                        $this->common_model->insert(CONFIG, $data);  
                    }
                             
                    //$this->common_model->update(CONFIG, $data,$where);
                }
            }
           
        }
        return true;
    }
    
   
    
}