<?php

/*
  @Author : sanket Jayani
  @Desc   : Campaign Group Create/Update
  @Date   : 29/02/2016
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
    
    function __construct() {
        
        parent::__construct();
        if(checkPermission('Settings','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(1);
        $this->load->library(array('form_validation', 'Session'));
        $this->load->model('Settings_model');
		//echo APPPATH.'libraries/payment/lib';die('');
		require_once(APPPATH.'libraries/payment/lib/Stripe.php');
        //$this->load->libraries('Stripe');
        $this->newsletter_type = get_newsletter_type();
    }
	public function addcrmuser(){
		$main_user_id = $this->config->item('master_user_id');
		$data['project_view'] = 'adddata';
		//die($main_user_id);
        // Added by Nikunj Ghelani
        $table_user = LOGIN.' as lg';
        $where_user = array("lg.login_id" => $main_user_id,"lg.is_delete" => 0);
        $fields_user = array("lg.*");
        $data['check_user_data'] = $this->common_model->get_records($table_user,$fields_user,'','','','','','','','','',$where_user);
		/*pr($data['check_user_data'] );
		die();*/
		$main_user_id = $this->config->item('master_user_id');
		$table_2 = BILL.' as ct';
		$where_2 = "ct.login_id =".$main_user_id;
		$fields_2 = array("ct.*");
		$data['bill'] = $this->common_model->get_records_data($table_2,$fields_2,'','','','','','','','','',$where_2);
		
		 $sub_domain=array_shift((explode(".",$_SERVER['HTTP_HOST'])));
		 
		
        $table_1 = SETUP_MASTER.' as ct';
		
        //$where_1 = "ct.login_id = ".$main_user_id." AND ct.email = '".$data['check_user_data'][0]['email']."'";
        if($sub_domain!='' && $sub_domain!='localhost'){
        $where_1 = "ct.domain_name = '".$sub_domain."'";
		}else{
		
		$where_1 = "ct.email = '".$data['check_user_data'][0]['email']."'";
		
		}
        $fields_1 = array("ct.*");
        $data['setup'] = $this->common_model->get_records_data($table_1,$fields_1,'','','','','','','','','',$where_1);
		/*pr($data['setup']);
		die();*/
		 $this->load->view('addcrmuser',$data);
	}
	
	public function removecrmuser(){
		$main_user_id = $this->config->item('master_user_id');
		$data['project_view'] = 'removedata';
		//die($main_user_id);
        // Added by Nikunj Ghelani
        $table_user = LOGIN.' as lg';
        $where_user = array("lg.login_id" => $main_user_id,"lg.is_delete" => 0);
        $fields_user = array("lg.*");
        $data['check_user_data'] = $this->common_model->get_records($table_user,$fields_user,'','','','','','','','','',$where_user);
		/*pr($data['check_user_data'] );
		die();*/
		$main_user_id = $this->config->item('master_user_id');
		$table_2 = BILL.' as ct';
		$where_2 = "ct.login_id =".$main_user_id;
		$fields_2 = array("ct.*");
		$data['bill'] = $this->common_model->get_records_data($table_2,$fields_2,'','','','','','','','','',$where_2);
		 $sub_domain=array_shift((explode(".",$_SERVER['HTTP_HOST'])));
		  

        $table_1 = SETUP_MASTER.' as ct';

        //$where_1 = "ct.login_id = ".$main_user_id." AND ct.email = '".$data['check_user_data'][0]['email']."'";
        if($sub_domain!='' && $sub_domain!='localhost'){
        $where_1 = "ct.domain_name = '".$sub_domain."'";
		}else{
		
		$where_1 = "ct.email = '".$data['check_user_data'][0]['email']."'";
		
		}
        $fields_1 = array("ct.*");
        $data['setup'] = $this->common_model->get_records_data($table_1,$fields_1,'','','','','','','','','',$where_1);
		/*pr($data['setup']);
		die();*/
		 $this->load->view('addcrmuser',$data);
	}
	
	public function addpmuser(){
		$main_user_id = $this->config->item('master_user_id');
		$data['project_view'] = 'adddata';
		//die($main_user_id);
        // Added by Nikunj Ghelani
        $table_user = LOGIN.' as lg';
        $where_user = array("lg.login_id" => $main_user_id,"lg.is_delete" => 0);
        $fields_user = array("lg.*");
        $data['check_user_data'] = $this->common_model->get_records($table_user,$fields_user,'','','','','','','','','',$where_user);
		/*pr($data['check_user_data'] );
		die();*/
$main_user_id = $this->config->item('master_user_id');
		$table_2 = BILL.' as ct';
		$where_2 = "ct.login_id =".$main_user_id;
		$fields_2 = array("ct.*");
		$data['bill'] = $this->common_model->get_records_data($table_2,$fields_2,'','','','','','','','','',$where_2);
		
         $sub_domain=array_shift((explode(".",$_SERVER['HTTP_HOST'])));
		  

        $table_1 = SETUP_MASTER.' as ct';

        //$where_1 = "ct.login_id = ".$main_user_id." AND ct.email = '".$data['check_user_data'][0]['email']."'";
        if($sub_domain!='' && $sub_domain!='localhost'){
        $where_1 = "ct.domain_name = '".$sub_domain."'";
		}else{
		
		$where_1 = "ct.email = '".$data['check_user_data'][0]['email']."'";
		
		}
        //$where_1 = "ct.login_id = ".$main_user_id." AND ct.email = '".$data['check_user_data'][0]['email']."'";
        $fields_1 = array("ct.*");
        $data['setup'] = $this->common_model->get_records_data($table_1,$fields_1,'','','','','','','','','',$where_1);
		/*pr($data['setup']);
		die();*/
		 $this->load->view('addpmuser',$data);
	}
	
	public function removepmuser(){
		$main_user_id = $this->config->item('master_user_id');
		$data['project_view'] = 'removedata';
		//die($main_user_id);
        // Added by Nikunj Ghelani
        $table_user = LOGIN.' as lg';
        $where_user = array("lg.login_id" => $main_user_id,"lg.is_delete" => 0);
        $fields_user = array("lg.*");
        $data['check_user_data'] = $this->common_model->get_records($table_user,$fields_user,'','','','','','','','','',$where_user);
		/*pr($data['check_user_data'] );
		die();*/
$main_user_id = $this->config->item('master_user_id');
		$table_2 = BILL.' as ct';
		$where_2 = "ct.login_id =".$main_user_id;
		$fields_2 = array("ct.*");
		$data['bill'] = $this->common_model->get_records_data($table_2,$fields_2,'','','','','','','','','',$where_2);
		
         $sub_domain=array_shift((explode(".",$_SERVER['HTTP_HOST'])));
		  

        $table_1 = SETUP_MASTER.' as ct';

        //$where_1 = "ct.login_id = ".$main_user_id." AND ct.email = '".$data['check_user_data'][0]['email']."'";
        if($sub_domain!='' && $sub_domain!='localhost'){
        $where_1 = "ct.domain_name = '".$sub_domain."'";
		}else{
		
		$where_1 = "ct.email = '".$data['check_user_data'][0]['email']."'";
		
		}
        //$where_1 = "ct.login_id = ".$main_user_id." AND ct.email = '".$data['check_user_data'][0]['email']."'";
        $fields_1 = array("ct.*");
        $data['setup'] = $this->common_model->get_records_data($table_1,$fields_1,'','','','','','','','','',$where_1);
		/*pr($data['setup']);
		die();*/
		 $this->load->view('addpmuser',$data);
	}
	
	public function addsupuser(){
		$main_user_id = $this->config->item('master_user_id');
		$data['project_view'] = 'adddata';
		//die($main_user_id);
        // Added by Nikunj Ghelani
        $table_user = LOGIN.' as lg';
        $where_user = array("lg.login_id" => $main_user_id,"lg.is_delete" => 0);
        $fields_user = array("lg.*");
        $data['check_user_data'] = $this->common_model->get_records($table_user,$fields_user,'','','','','','','','','',$where_user);
		/*pr($data['check_user_data'] );
		die();*/
$main_user_id = $this->config->item('master_user_id');
		$table_2 = BILL.' as ct';
		$where_2 = "ct.login_id =".$main_user_id;
		$fields_2 = array("ct.*");
		$data['bill'] = $this->common_model->get_records_data($table_2,$fields_2,'','','','','','','','','',$where_2);
		
         $sub_domain=array_shift((explode(".",$_SERVER['HTTP_HOST'])));
		  

        $table_1 = SETUP_MASTER.' as ct';

        //$where_1 = "ct.login_id = ".$main_user_id." AND ct.email = '".$data['check_user_data'][0]['email']."'";
        if($sub_domain!='' && $sub_domain!='localhost'){
        $where_1 = "ct.domain_name = '".$sub_domain."'";
		}else{
		
		$where_1 = "ct.email = '".$data['check_user_data'][0]['email']."'";
		
		}

        //$where_1 = "ct.login_id = ".$main_user_id." AND ct.email = '".$data['check_user_data'][0]['email']."'";
        $fields_1 = array("ct.*");
        $data['setup'] = $this->common_model->get_records_data($table_1,$fields_1,'','','','','','','','','',$where_1);
		/*pr($data['setup']);
		die();*/
		 $this->load->view('addsupuser',$data);
	}
	
	public function removesupuser(){
		$main_user_id = $this->config->item('master_user_id');
		$data['project_view'] = 'removedata';
		//die($main_user_id);
        // Added by Nikunj Ghelani
        $table_user = LOGIN.' as lg';
        $where_user = array("lg.login_id" => $main_user_id,"lg.is_delete" => 0);
        $fields_user = array("lg.*");
        $data['check_user_data'] = $this->common_model->get_records($table_user,$fields_user,'','','','','','','','','',$where_user);
		/*pr($data['check_user_data'] );
		die();*/
$main_user_id = $this->config->item('master_user_id');
		$table_2 = BILL.' as ct';
		$where_2 = "ct.login_id =".$main_user_id;
		$fields_2 = array("ct.*");
		$data['bill'] = $this->common_model->get_records_data($table_2,$fields_2,'','','','','','','','','',$where_2);
		
         $sub_domain=array_shift((explode(".",$_SERVER['HTTP_HOST'])));
		  

        $table_1 = SETUP_MASTER.' as ct';

        //$where_1 = "ct.login_id = ".$main_user_id." AND ct.email = '".$data['check_user_data'][0]['email']."'";
        if($sub_domain!='' && $sub_domain!='localhost'){
        $where_1 = "ct.domain_name = '".$sub_domain."'";
		}else{
		
		$where_1 = "ct.email = '".$data['check_user_data'][0]['email']."'";
		
		}

        //$where_1 = "ct.login_id = ".$main_user_id." AND ct.email = '".$data['check_user_data'][0]['email']."'";
        $fields_1 = array("ct.*");
        $data['setup'] = $this->common_model->get_records_data($table_1,$fields_1,'','','','','','','','','',$where_1);
		/*pr($data['setup']);
		die();*/
		 $this->load->view('addsupuser',$data);
	}
	public function subscription() {
		
		$sess_array = array('setting_current_tab' => 'subscription');
        $this->session->set_userdata($sess_array);
        
        
		$user_info = $this->session->userdata('LOGGED_IN');
		 $main_user_role_id = $this->config->item('super_admin_role_id');
		
		if($user_info['ROLE_TYPE']!=$main_user_role_id){
			
            
            $msg = $this->lang->line('DONT_HAVE_PAGE_PERMISSION');
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
			redirect($this->viewname.'/Subscription');
			
		}
		$this->breadcrumbs->push(lang('subscription'),'/Settings');
		$this->breadcrumbs->push(lang('subscription_dashboard'),' ');
		
		$sess_array = array('setting_current_tab' => 'biling_information');


        $this->session->set_userdata($sess_array);
		
		 $data['main_content'] = '/subscription';
        $data['settings_data'] = $this->Settings_model->getSettingsData();
        $data['drag'] = false;
        $dashWhere = "config_key='email_settings'";
        $defaultDashboard = $this->common_model->get_records(CONFIG, array('value'), '', '', $dashWhere);
		 
		$table3 = COUNTRIES . ' as cm';
        $fields3 = array("cm.country_name,cm.country_id");
        $data['country_data'] = $this->common_model->get_records_data($table3, $fields3, '', '', '', '', '', '', '', '', '', '');
        
		
         //Get Tax From PRODUCT_TAX_MASTER
        $TaxInfo['fields'] = ['txprd.tax_id,txprd.tax_name,txprd.tax_percentage,txprd.created_date'];
        $TaxInfo['table'] = PRODUCT_TAX_MASTER . ' as txprd';
        $TaxInfo['wherestring'] = 'txprd.is_delete=0';
        $data['TaxArray'] = $this->common_model->get_records_array($TaxInfo);
        
        $data['editRecord'] = (array) json_decode($defaultDashboard[0]['value']);
        /*for admin table data start*/

        $main_user_id = $this->config->item('master_user_id');
		//die($main_user_id);
        // Added by Nikunj Ghelani
        $table_user = LOGIN.' as lg';
        $where_user = array("lg.login_id" => $main_user_id,"lg.is_delete" => 0);
        $fields_user = array("lg.*");
        $data['check_user_data'] = $this->common_model->get_records($table_user,$fields_user,'','','','','','','','','',$where_user);
		/*pr($data['check_user_data'] );
		die();*/
		
		
		 
		 $sub_domain=array_shift((explode(".",$_SERVER['HTTP_HOST'])));
        $table_1 = SETUP_MASTER.' as ct1';
	if($sub_domain!='' && $sub_domain!='localhost'){
        $where_1 = "ct1.domain_name = '".$sub_domain."'";
	}else{
		
		$where_1 = "ct1.email = '".$data['check_user_data'][0]['email']."'";
		
		}
        $fields_1 = array("ct1.*");
        $data['setup'] = $this->common_model->get_records_data($table_1,$fields_1,'','','','','','','','','',$where_1);
		//echo $this->db->last_query();
		/*pr($data['setup']);
		die();*/
		
		$table_2 = BILL.' as ct1';
		if(count($data['setup'])>0){
        $where_2 = "ct1.domain_name = '".$data['setup'][0]['domain_name']."'";
		}
		else{
		$where_2 = "ct1.login_id = '".$main_user_id."'";	
			}	
        $fields_2 = array("ct1.*");
        $data['bill'] = $this->common_model->get_records_data($table_2,$fields_2,'','','','','','','','','',$where_2);
		/*echo $this->db->last_query();
		die();*/
		/*pr($data['bill']);
		die();*/
        //Get Records of General settings from config table
        $gensettingWhere = "config_key='general_settings'";
        $defaultDashboard1 = $this->common_model->get_records(CONFIG, array('value'), '', '', $gensettingWhere);
		
        $table1 = COUNTRIES . ' as c';
        $match1 = "c.use_status=1 AND country_status=1 AND is_delete_currency=0 ";
        $fields1 = array("c.country_id,c.currency_code,c.currency_symbol");
        $data['currency_list'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
		
        $data['editRecords'] = (array) json_decode($defaultDashboard1[0]['value']);

        // Get Records of Goole Analytics Settings from Config table
    	$google_analytics_settingsWhere = "config_key='google_analytics_settings'";
    	$defaultDashboard2 = $this->common_model->get_records(CONFIG, array('value'), '', '', $google_analytics_settingsWhere);
        $data['editRecords1'] = (array)json_decode($defaultDashboard2[0]['value']);
		
		
        $data['header'] = array('menu_module' => 'settings');

        $table = COUNTRIES . ' as cm';
        $fields = array("cm.country_name,cm.country_id");

        $data['country_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', '');
        //$this->add();
        
        $this->parser->parse('layouts/DashboardTemplate', $data);
		
	}

    public function index() {
		
        $data['main_content'] = '/' . $this->viewname;
        $data['settings_data'] = $this->Settings_model->getSettingsData();
        $data['drag'] = false;
        $dashWhere = "config_key='email_settings'";
        $defaultDashboard = $this->common_model->get_records(CONFIG, array('value'), '', '', $dashWhere);

        //start added by sanket on 15/06/2016
        $data['newsletter_type'] = $this->newsletter_type;
        //for getting mailchimp configuration data
        $Where_mailchimp = "config_key='mailchimp_configuration'";
        $data_mailchimp = $this->common_model->get_records(CONFIG, array('value'), '', '', $Where_mailchimp);
        
        if($data_mailchimp[0]['value'] == '')
        {
            $data['mailchimp_data'] = array('newsleeter_type'=>'','api_key'=>'','list_id'=>'');
        }else
        {
            $mailchimp_data = json_decode($data_mailchimp[0]['value']);
            $data['mailchimp_data'] = array('newsleeter_type'=>$mailchimp_data->newsleeter_type,
                'api_key'=>$mailchimp_data->api_key);
        }
       
        
        //for getting campaign monitro vonfiguration data
        $Where_cmonitor = "config_key='campaign_monitor_configuration'";
        $data_cmonitor = $this->common_model->get_records(CONFIG, array('value'), '', '', $Where_cmonitor);
        
        if($data_cmonitor[0]['value'] == '')
        {
            $data['cmonitor_data'] = array('newsleeter_type'=>'','api_key'=>'','list_id'=>'');
        }else
        {
            $cmonitor_data = json_decode($data_cmonitor[0]['value']);
            $data['cmonitor_data'] = array('newsleeter_type'=>$cmonitor_data->newsleeter_type,
                'api_key'=>$cmonitor_data->api_key);
        }
        
        
        //for getting moosend configuration data
        $Where_moosend = "config_key='moosend_configuration'";
        $data_moosend = $this->common_model->get_records(CONFIG, array('value'), '', '', $Where_moosend);
        
        if($data_moosend[0]['value'] == '')
        {
            $data['moosend_data'] = array('newsleeter_type'=>'','api_key'=>'','list_id'=>'');
        }else
        {
            $moosend_data = json_decode($data_moosend[0]['value']);
            $data['moosend_data'] = array('newsleeter_type'=>$moosend_data->newsleeter_type,
                'api_key'=>$moosend_data->api_key);
        }
        
        
        //for getting getresponse configuration data
        $Where_get_response = "config_key='get_response_configuration'";
        $data_get_response = $this->common_model->get_records(CONFIG, array('value'), '', '', $Where_get_response);
        
        if($data_get_response[0]['value'] == '')
        {
            $data['get_response_data'] = array('newsleeter_type'=>'','api_key'=>'','list_id'=>'');
        }else
        {
            $get_response_data = json_decode($data_get_response[0]['value']);
            $data['get_response_data'] = array('newsleeter_type'=>$get_response_data->newsleeter_type,
                'api_key'=>$get_response_data->api_key);
        }
       
      
	//End added by sanket on 15/06/2016
        	
         //Get Tax From PRODUCT_TAX_MASTER
        $TaxInfo['fields'] = ['txprd.tax_id,txprd.tax_name,txprd.tax_percentage,txprd.created_date'];
        $TaxInfo['table'] = PRODUCT_TAX_MASTER . ' as txprd';
        $TaxInfo['wherestring'] = 'txprd.is_delete=0';
        $data['TaxArray'] = $this->common_model->get_records_array($TaxInfo);
        
        $data['editRecord'] = (array) json_decode($defaultDashboard[0]['value']);
        /*for admin table data start*/

        $main_user_id = $this->config->item('master_user_id');
		//die($main_user_id);
        // Added by Nikunj Ghelani
        $table_user = LOGIN.' as lg';
        $where_user = array("lg.login_id" => $main_user_id,"lg.is_delete" => 0);
        $fields_user = array("lg.*");
        $check_user_data = $this->common_model->get_records($table_user,$fields_user,'','','','','','','','','',$where_user);
		/*pr($check_user_data);
		die();*/


        $table_1 = SETUP_MASTER.' as ct';

        $where_1 = "ct.login_id = ".$main_user_id." AND ct.email = '".$check_user_data[0]['email']."'";
        $fields_1 = array("ct.*");
        $data['setup'] = $this->common_model->get_records_data($table_1,$fields_1,'','','','','','','','','',$where_1);
		/*echo $this->db->last_query();
		die();*/
		/*pr($data['setup']);
		die();*/
        //Get Records of General settings from config table
        $gensettingWhere = "config_key='general_settings'";
        $defaultDashboard1 = $this->common_model->get_records(CONFIG, array('value'), '', '', $gensettingWhere);
		
        $table1 = COUNTRIES . ' as c';
        $match1 = "c.use_status=1 AND country_status=1 AND is_delete_currency=0 ";
        $fields1 = array("c.country_id,c.currency_code,c.currency_symbol");
        $data['currency_list'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
		
        $data['editRecords'] = (array) json_decode($defaultDashboard1[0]['value']);

        // Get Records of Goole Analytics Settings from Config table
    	$google_analytics_settingsWhere = "config_key='google_analytics_settings'";
    	$defaultDashboard2 = $this->common_model->get_records(CONFIG, array('value'), '', '', $google_analytics_settingsWhere);
        $data['editRecords1'] = (array)json_decode($defaultDashboard2[0]['value']);
		
		
        $data['header'] = array('menu_module' => 'settings');

        $table = COUNTRIES . ' as cm';
        $fields = array("cm.country_name,cm.country_id");

        $data['country_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', '');
        //$this->add();
        
        $this->parser->parse('layouts/DashboardTemplate', $data);
    }

    /*
      @Author : Disha Patel
      @Desc   : updateSettings General Settings
      @Input 	: Post record from settingslist
      @Output	: Update data in database and redirect
      @Date   : 12/01/2016
     */

    public function updateGeneralSettings() {
        //added for selected tab
        $sess_array = array('setting_current_tab' => 'general_setting');


        $this->session->set_userdata($sess_array);
        $settings_arr = array();
        if ($_FILES['profile_photo']['name'] != '' && !empty($_FILES)) {

            $image_name = $_FILES['profile_photo']['name']; // image_name.extension
            $imageFileType = pathinfo($image_name, PATHINFO_EXTENSION); // Get Extention of image e.g. jpeg, png
            $profile_pic_new_name = "genset_". time() . "_profile_photo." . $imageFileType;
            $config['upload_path'] = SETTINGS_PROFILE_PIC_UPLOAD_PATH;
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['file_name'] = $profile_pic_new_name;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('profile_photo', FALSE)) {
                $this->form_validation->set_message('checkdoc', $data['error'] = $this->upload->display_errors());

                if ($_FILES['profile_photo']['error'] != 4) {
                    $msg = $this->upload->display_errors();
                    $this->session->set_flashdata('msg', $msg);
                    return false;
                }
            } else {
                $upload = array('upload_data' => $this->upload->data());
                $thumbnail_img_name = $this->common_model->create_thumnail($upload, PROFILE_PIC_HEIGHT, PROFILE_PIC_WIDTH); //Create thumbnail

                $profile_photo = $profile_pic_new_name;
            }
        }

        if (count($this->input->post()) > 0) {
            $settings_arr['city'] = $this->input->post('city');
            $settings_arr['state'] = $this->input->post('state');
            $settings_arr['pincode'] = $this->input->post('pincode');
            $settings_arr['country_id'] = $this->input->post('country_id');
            $settings_arr['telephone1'] = $this->input->post('telephone1');
            $settings_arr['telephone2'] = $this->input->post('telephone2');
            $settings_arr['company_name'] = $this->input->post('company_name');
            $settings_arr['address1'] = $this->input->post('address1');
            $settings_arr['address2'] = $this->input->post('address2');
            $settings_arr['company_street'] = $this->input->post('company_street');
            $settings_arr['default_currency'] = $this->input->post('default_currency');
            if ($_FILES['profile_photo']['name'] != NULL) {
                $settings_arr['profile_photo'] = $profile_photo;
            } else {
                $settings_arr['profile_photo'] = $this->input->post('hidden_img_name');
            }
        }

        $data['config_key'] = 'general_settings';
        $data['value'] = json_encode($settings_arr);
        $where = array('config_key' => 'general_settings');
        if ($this->common_model->update(CONFIG, $data, $where)) {
            $msg = $this->lang->line('SETTINGS_UPDATED_SUCESSFULLY');
            $this->session->set_flashdata('msg', $msg);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg',  $msg);
        }

        redirect($this->viewname); //Redirect On Listing page
    }

    /*
      @Author : Mehul Patel
      @Desc   : updatedata Email Settings
      @Input 	: Post record from settingslist
      @Output	: Update data in database and redirect
      @Date   : 12/01/2016
     */

    public function updatedata() 
    {
        //added for selected tab
        $sess_array = array('setting_current_tab' => 'email_configuration');
        $this->session->set_userdata($sess_array);
        $email_settings_arr = array();

        if (count($this->input->post()) > 0) {
			$id = $this->input->post('id');
			
			
			if($id==""){
				$id =1;
			}else{
				$id=$id;
			}
			
            $email_settings_arr["id"] = $id;
            $email_settings_arr["company_email"] = $this->input->post('company_email');
            $email_settings_arr["email_protocol"] = $this->input->post('email_protocol');
            $email_settings_arr["smtp_host"] = $this->input->post('smtp_host');
            $email_settings_arr["smtp_user"] = $this->input->post('smtp_user');
            $email_settings_arr["smtp_pass"] = $this->input->post('smtp_pass');
            $email_settings_arr["smtp_port"] = $this->input->post('smtp_port');
        }


        $data['config_key'] = 'email_settings';
        $data['value'] = json_encode($email_settings_arr);
        $where = array('config_key' => 'email_settings');

        // Update form data into database
        if ($this->common_model->update(CONFIG, $data, $where)) {
            
            $msg = lang('UPDATE_SUC_EMAIL_CONFIGURATION');
            $this->session->set_flashdata('msg', $msg);
        } else {
        
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('error_msg', $msg);
        }
        
     
        redirect('Settings/emailSettings'); //Redirect On Listing page
    }

    /*
      @Author 	: RJ(Rupesh Jorkar)
      @Desc   	: Update Tax Settings
      @Input 	: Post record from Tax List
      @Output	: Update data in database and redirect
      @Date   	: 18/03/2016
     */

    public function updateTaxSettings() {
        $oldTaxIds = array();
        $oldTaxName = array();
        $oldTaxValue = array();
        $newTaxIds = array();
        $newTaxName = array();
        $newTaxValue = array();
        //added for selected tab
        $sess_array = array('setting_current_tab' => 'tax_setting');
        $this->session->set_userdata($sess_array);

        $TaxArraySetting = array();
        if (count($this->input->post()) > 0) {
            $TaxArraySetting["taxsetting"] = $this->input->post('taxsetting');
        }
        $oldTaxIds = $this->input->post('tax_id');
        $oldTaxName = $this->input->post('nametaxsetting');
        $oldTaxValue = $this->input->post('taxsetting');
        if (count($oldTaxIds) > 0) {
            foreach ($oldTaxIds as $ids) {
                $this->common_model->update(PRODUCT_TAX_MASTER, array('tax_name' => $oldTaxName[$ids], 'tax_percentage' => $oldTaxValue[$ids]), 'tax_id=' . $ids);
            }
        }

        $newTaxIds = $this->input->post('tax_id_new');
        $newTaxName = $this->input->post('nametaxsetting_new');
        $newTaxValue = $this->input->post('taxsetting_new');
        if (count($newTaxIds) > 0) {
            for ($i = 0; $i < count($newTaxIds); $i++) {
				$this->common_model->insert(PRODUCT_TAX_MASTER, array('tax_name' => $newTaxName[$i], 'tax_percentage' => $newTaxValue[$i]));
            }
        }

//        $data['config_key'] = 'BZTaxSetting';
//        $data['value'] = json_encode($TaxArraySetting);
//        $where = array('config_key' => 'BZTaxSetting');
//        //Check Setting available in Cofig table or not
//        $TaxWhere = "config_key='BZTaxSetting'";
//        $TaxAvailability = $this->common_model->get_records(CONFIG, array('value'), '', '', $TaxWhere);
//
//        if (!empty($TaxAvailability) && count($TaxAvailability) > 0) {
//            //Update Query in Database
//            $this->common_model->update(CONFIG, $data, $where);
//        } else {
//            //Insert Query in Database
//            echo $TaxInsert = $this->common_model->insert(CONFIG, $data);
//        }

        $msg = lang('TAX_SETTING_UPDATED');;
        $this->session->set_flashdata('msg', $msg);
        redirect($this->viewname);
    }

    /*
      @Author : Disha Patel
      @Desc   : updatedata Social Media Settings
      @Input 	: Post record from settingslist
      @Output	: Update data in database and redirect
      @Date   : 12/01/2016
     */

    public function updateSocialMediaSettings() {
//            if($_FILES['company_profile_image']['name'] != '' && !empty($_FILES))
//            {
//    		$profile_pic_new_name = time()."_".$_FILES['company_profile_image']['name'];
//    		$config['upload_path']   = COMPANY_PROFILE_PIC_UPLOAD_PATH;
//    		$config['allowed_types'] = 'gif|jpg|png|jpeg';
//    		$config['file_name'] = $profile_pic_new_name;
//
//    		$this->load->library('upload', $config);
//
//    		if ( !$this->upload->do_upload('company_profile_image',FALSE))
//    		{
//                    $this->form_validation->set_message('checkdoc', $data['error'] = $this->upload->display_errors());
//
//                    if($_FILES['company_profile_image']['error'] != 4)
//                    {
//    			$msg = $this->upload->display_errors();
//    			$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
//    			return false;
//                    }
//    		}
//    		else
//    		{
//                    $upload = array('upload_data' => $this->upload->data());
//                    $thumbnail_img_name =  $this->common_model->create_thumnail($upload,PROFILE_PIC_HEIGHT,PROFILE_PIC_WIDTH); //Create thumbnail
//
//                    // $_SESSION['LOGGED_IN']['PROFILE_PHOTO'] = $profile_pic_new_name;
//                    //  $this->session->set_userdata('LOGGED_IN', $sess_array);
//                    $company_profile_image['company_profile_image'] = $profile_pic_new_name;
//    		}
//            }


        $userProfile = $this->Settings_model->updateSettings();

// this is for youtube analytics credential file by brt 17-5-16

        if ($_FILES['youtube_app_credentials']['name'] != '' && !empty($_FILES)) {
             
            
            $image_name = $_FILES['youtube_app_credentials']['name']; // image_name.extension
            $imageFileType = pathinfo($image_name, PATHINFO_EXTENSION); // Get Extention of image e.g. jpeg, png
            if($imageFileType == "json"){
                $profile_pic_new_name = $image_name;
                $config['upload_path'] = 'application/'.YOUTUBE_APPLICATION_CREDENTIALS;
                $config['allowed_types'] = '*';
                //$config['file_name'] = $profile_pic_new_name;
                $config['file_name'] ='client_secrets';
                $config['overwrite'] = true;
                
                $this->load->library('upload', $config);

                 //$data['config_key'] = 'youtube_analytics_settings';
                 //$data['value'] = json_encode($google_analytics_settings);
                // $where = array('config_key' => 'google_analytics_settings');


                  $userProfile = $this->Settings_model->updateSettings();
                if (!$this->upload->do_upload('youtube_app_credentials', FALSE)) {
                    $this->form_validation->set_message('checkdoc', $data['error'] = $this->upload->display_errors());
                   
                    if ($_FILES['youtube_app_credentials']['error'] != 4) {
                        $msg = $this->upload->display_errors();
                        $this->session->set_flashdata('msg', $msg);
                        return false;
                    }
                }
            }else{

                $msg = $this->lang->line('MSG_UPLOAD_JSON');
                $this->session->set_flashdata('msg',$msg);

                redirect($this->viewname);
            }

        }


// Youtube credential setting ends here 

        $sess_array = array('setting_current_tab' => 'social_media_setting');
        $this->session->set_userdata($sess_array);

        if ($userProfile) {
            $msg = $this->lang->line('SETTINGS_UPDATED_SUCESSFULLY');
            $this->session->set_flashdata('msg', $msg);
        } else {
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', $msg);
        }

        redirect($this->viewname);
    }

    /**
     * function is used to remove tax item
     */
    function removeTaxItem() {
        if(!$this->input->is_ajax_request())
        {
           exit("No Direct scripts allowed"); 
        }
        $id=$this->input->post('id');
        if ($id > 0) {

            $this->common_model->update(PRODUCT_TAX_MASTER, array('is_delete' =>1), array('tax_id' => $id));
            
            echo json_encode(array('status'=>1));
        }
    }
	  /*
		 @Author : Mehul Patel
		 @Desc   : updatedata Google Analytics Settings
		 @Input 	: Post record from settingslist
		 @Output	: Update data in database and redirect
		 @Date   : 18/03/2016
		 */
    public function updateGoogleAnalyticsSettings(){

    	//added for selected tab
    	$sess_array = array('setting_current_tab' => 'google_analytics_settings');
    	$this->session->set_userdata($sess_array);
    	$google_analytics_settings = array();
    	if ($_FILES['google_app_credentials']['name'] != '' && !empty($_FILES)) {
    		 
    		$image_name = $_FILES['google_app_credentials']['name']; // image_name.extension
    		$imageFileType = pathinfo($image_name, PATHINFO_EXTENSION); // Get Extention of image e.g. jpeg, png
    		if($imageFileType == "json"){
    			$profile_pic_new_name = $image_name;
    			$config['upload_path'] = GOOGLE_APPLICATION_CREDENTIALS;
    			$config['allowed_types'] = '*';
    			$config['file_name'] = $profile_pic_new_name;

    			$this->load->library('upload', $config);

    			if (!$this->upload->do_upload('google_app_credentials', FALSE)) {
    				$this->form_validation->set_message('checkdoc', $data['error'] = $this->upload->display_errors());

    				if ($_FILES['google_app_credentials']['error'] != 4) {
    					$msg = $this->upload->display_errors();
    					$this->session->set_flashdata('msg', $msg);
    					return false;
    				}
    			}
    		}else{

    			$msg = $this->lang->line('MSG_UPLOAD_JSON');
    			$this->session->set_flashdata('msg',$msg);

    			redirect($this->viewname);
    		}

    	}
    	 
    	if(count($this->input->post()) > 0){

    		$google_analytics_settings["application_name"]=$this->input->post('application_name');
    		$google_analytics_settings["service_account_email"]=$this->input->post('service_account_email');
    		if ($_FILES['google_app_credentials']['name'] != NULL) {
    			$google_analytics_settings['google_app_credentials'] = $profile_pic_new_name;
    		} else {
    			$google_analytics_settings['google_app_credentials'] = $this->input->post('hidden_img_name');
    		}
    		 
    	}
    	//	pr($google_analytics_settings); exit;
    	$data['config_key'] = 'google_analytics_settings';
    	$data['value'] = json_encode($google_analytics_settings);
    	$where = array('config_key' => 'google_analytics_settings');

    	// Update form data into database
    	if ($this->common_model->update(CONFIG, $data,$where))
    	{
    		//$this->session->set_flashdata('msg','<div class="alert alert-success text-center">You are Successfully Updated! </div>');
    		$msg = lang('SUCCESS_GOOGLE_ANALYTICS');
    		$this->session->set_flashdata('msg',$msg);
    		redirect($this->viewname);
    	}
    	else
    	{
    		// error
    		$msg = $this->lang->line('error_msg');
    		$this->session->set_flashdata('msg',$msg);

    		redirect($this->viewname);
    	}

    	redirect($this->viewname);	//Redirect On Listing page

    }
	
	/*nikunj ghelani
	update subscription form
	*/
	public function checkout_crm(){
		//pr($_POST);
		
		$setup_master_id=$this->input->post('setup_id');
		$table_1 = SETUP_MASTER.' as ct';
		$where_1 = "ct.setup_id =".$setup_master_id;
		$fields_1 = array("ct.*");
		$data['setup'] = $this->common_model->get_records_data($table_1,$fields_1,'','','','','','','','','',$where_1);
			/*pr($data['setup']);
			die('here');	*/
		$sess_array = array('setting_current_tab' => 'checkout');
        $this->session->set_userdata($sess_array);
        $currentCRM = $data['setup'][0]['crm_user'];
        $tot_amount='';
		if($this->input->post('type')=='add'){
			
			 $tot_amount=$this->input->post('quantity')+$currentCRM;
             
			}
		//echo $tot_amount;
		//die();
		if($this->input->post('type')=='remove'){
			
			 $tot_amount=$currentCRM-$this->input->post('quantity');
			 	
			}
				
	    //$tot_amount=$this->input->post('quantity')+$currentCRM;
		//sk_test_ELazZ12erwvhGaSFfpzaAAmB
                        
		if ($this->input->post('stripeToken')) {
			
		try {
			
			Stripe::setApiKey(STRIPE_KEY_SK);
			$stripe = array( "secret_key" => STRIPE_KEY_SK);
			$token=$this->input->post('stripeToken');
			
			if($this->input->post('cust_id')==''){
				//die('blank');
				//echo  "in";
				
				try{
					  $customer = Stripe_Customer::create(array(
                      "source" => $this->input->post('stripeToken'),
                      "plan" => 'CRM',
                      "email" => $this->input->post('email'),
					  "quantity"=>$tot_amount
                      ), STRIPE_KEY_SK);
                      
                      $cust_id=$customer->id;
			   
			   $invoice = Stripe_Charge::create( array(
							'customer'    => $cust_id, // the customer to apply the fee to
							'description' => "Charge for test@example.com",
							'receipt_email' => $this->input->post('email'),
							'currency'=>'usd',//REQUIRED
							'amount'=>100 //REQUIRED
							), STRIPE_KEY_SK );
					}catch(Exception $e){
						echo $e->getmessage();
						exit;
						
					}
			 
                 
			   
			   
			   //die('create');
			}
			else{
				
				try{
				$cust_id=$this->input->post("cust_id");
				 $customer = Stripe_Customer::retrieve($this->input->post("cust_id"), STRIPE_KEY_SK);
                    $customer->updateSubscription(array(
                        'plan' => 'CRM',
                        "quantity" => $tot_amount
                            )
                    );
                    
                    $customer->save();
                    $invoice = Stripe_Charge::create( array(
							'customer'    => $cust_id, // the customer to apply the fee to
							'description' => "Charge for test@example.com",
							'receipt_email' => $this->input->post('email'),
							'currency'=>'usd',
							'amount'=>100
							), STRIPE_KEY_SK );
					}catch(Exception $e){
						//echo $e->getmessage();
						
						
					}
					
					
					//die('update');
			}
				/*pr($data['setup']);
				die('here');*/
				
				/*$tot_pm_user=$data['setup'][0]['pm_user'] + $this->input->post('pm_user');
				$tot_support_user=$data['setup'][0]['support_user'] + $this->input->post('support_user');
				$tot_crm_user=$data['setup'][0]['crm_user'] + $this->input->post('crm_user');*/
				/*echo $tot_amount;
				die('here');*/
				$tot_crm_user=$this->input->post('quantity');
				$setup_master['crm_user'] = $tot_amount;
				$total_user = $data['setup'][0]['pm_user'] + $data['setup'][0]['support_user'] + $tot_amount;
				$setup_master['total_user'] = $total_user;
				$setup_master['cust_id'] = $cust_id;
				
			 $where = array('setup_id' => $setup_master_id);
			
				/*pr($setup_master);
				die('here');*/
				$success_setup = $this->common_model->update_data(SETUP,$setup_master,$where);
			
			/*pr($customer->id);
			die();*/
				
			echo "<h1>Your payment has been completed.</h1>";
			
			
		}
 
		catch(Stripe_CardError $e) {
 
		}
		catch (Stripe_InvalidRequestError $e) {
 
		} catch (Stripe_AuthenticationError $e) {
		} catch (Stripe_ApiConnectionError $e) {
		} catch (Stripe_Error $e) {
		} catch (Exception $e) {
			
		}
		
		 
		 /*if ($success_setup) {
            $msg = $this->lang->line('setup_edit_msg');
            $this->session->set_flashdata('msg', $msg);
        }*/
		}
		/*$msg = $this->lang->line('payment_msg');
        $this->session->set_flashdata('msg', $msg);*/
		$msg = $this->lang->line('payment_msg');
        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
      
        redirect($this->viewname.'/subscription');  //Redirect On Listing page
		
		
	}
	
	
	public function checkout_support(){
		//pr($_POST);
		//die();
		$setup_master_id=$this->input->post('setup_id');
		$table_1 = SETUP_MASTER.' as ct';
		$where_1 = "ct.setup_id =".$setup_master_id;
		$fields_1 = array("ct.*");
		$data['setup'] = $this->common_model->get_records_data($table_1,$fields_1,'','','','','','','','','',$where_1);
				
		$sess_array = array('setting_current_tab' => 'checkout');
        $this->session->set_userdata($sess_array);
        $currentSUPPORT = $data['setup'][0]['support_user'];
        $tot_amount='';
		if($this->input->post('type')=='add'){
			
			 $tot_amount=$this->input->post('quantity')+$currentSUPPORT;
             
			}
		
		if($this->input->post('type')=='remove'){
			
			 $tot_amount=$currentSUPPORT-$this->input->post('quantity');
			 	
			}
				
	    //$tot_amount=$this->input->post('quantity')+$currentCRM;
		//sk_test_ELazZ12erwvhGaSFfpzaAAmB
                        
		if ($this->input->post('stripeToken')) {
		try {
			
			Stripe::setApiKey(STRIPE_KEY_SK);
			$stripe = array( "secret_key" => STRIPE_KEY_SK);
			$token=$this->input->post('stripeToken');
			
			if($this->input->post('cust_id')==''){
				
			   $customer = Stripe_Customer::create(array(
                      "source" => $this->input->post('stripeToken'),
                      "plan" => 'SUPPORT',
                      "email" => $this->input->post('email'),
					  "quantity"=>$tot_amount
                      ), STRIPE_KEY_SK);
			   $cust_id=$customer->id;
			   
			   $invoice = Stripe_Charge::create( array(
							'customer'    => $cust_id, // the customer to apply the fee to
							'description' => "Charge for test@example.com",
							'receipt_email' => $this->input->post('email'),
							'currency'=>'usd',//REQUIRED
							'amount'=>100 //REQUIRED
							), STRIPE_KEY_SK );
			   
			  // die('create');
			}
			else{
				
				try{
				$cust_id=$this->input->post("cust_id");
				 $customer = Stripe_Customer::retrieve($this->input->post("cust_id"), STRIPE_KEY_SK);
                    $customer->updateSubscription(array(
                        'plan' => 'SUPPORT',
                        "quantity" => $tot_amount
                            )
                    );
                    
                    $customer->save();
                    $invoice = Stripe_Charge::create( array(
							'customer'    => $cust_id, // the customer to apply the fee to
							'description' => "Charge for test@example.com",
							'receipt_email' => $this->input->post('email'),
							'currency'=>'usd',//REQUIRED
							'amount'=>100 //REQUIRED
							), STRIPE_KEY_SK );
					}catch(Exception $e){
						echo $e->getmessage();
						
						
					}
					
					
					//die('update');
			}
				/*pr($data['setup']);
				die('here');*/
				
				/*$tot_pm_user=$data['setup'][0]['pm_user'] + $this->input->post('pm_user');
				$tot_support_user=$data['setup'][0]['support_user'] + $this->input->post('support_user');
				$tot_crm_user=$data['setup'][0]['crm_user'] + $this->input->post('crm_user');*/
				$tot_crm_user=$this->input->post('quantity');
				$setup_master['support_user'] = $tot_amount;
				$total_user = $data['setup'][0]['pm_user'] + $data['setup'][0]['crm_user'] + $tot_amount;
				$setup_master['total_user'] = $total_user;
				$setup_master['cust_id'] = $cust_id;
				
				$where = array('setup_id' => $setup_master_id);
				/*pr($total_user);
				die('here');*/
				$success_setup = $this->common_model->update_data(SETUP,$setup_master,$where);
			
			/*pr($customer->id);
			die();*/
				
			echo "<h1>Your payment has been completed.</h1>";
			
			
		}
 
		catch(Stripe_CardError $e) {
 
		}
		catch (Stripe_InvalidRequestError $e) {
 
		} catch (Stripe_AuthenticationError $e) {
		} catch (Stripe_ApiConnectionError $e) {
		} catch (Stripe_Error $e) {
		} catch (Exception $e) {
			
		}
		
		 
		 /*if ($success_setup) {
            $msg = $this->lang->line('setup_edit_msg');
            $this->session->set_flashdata('msg', $msg);
        }*/
		}
		$msg = $this->lang->line('payment_msg');
        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
      

        redirect($this->viewname.'/subscription');  //Redirect On Listing page
		
		
	}
	
	
	public function checkout_pm(){
		//pr($_POST);
		//die();
		$setup_master_id=$this->input->post('setup_id');
		$table_1 = SETUP_MASTER.' as ct';
		$where_1 = "ct.setup_id =".$setup_master_id;
		$fields_1 = array("ct.*");
		$data['setup'] = $this->common_model->get_records_data($table_1,$fields_1,'','','','','','','','','',$where_1);
				
		$sess_array = array('setting_current_tab' => 'checkout');
        $this->session->set_userdata($sess_array);
        $currentpm = $data['setup'][0]['pm_user'];
        $tot_amount='';
		if($this->input->post('type')=='add'){
			
			 $tot_amount=$this->input->post('quantity')+$currentpm;
             
			}
		
		if($this->input->post('type')=='remove'){
			
			 $tot_amount=$currentpm-$this->input->post('quantity');
			 	
			}
				
	    //$tot_amount=$this->input->post('quantity')+$currentCRM;
		
		if ($this->input->post('stripeToken')) {
		try {
			
			Stripe::setApiKey(STRIPE_KEY_SK);
			$stripe = array( "secret_key" => STRIPE_KEY_SK);
			$token=$this->input->post('stripeToken');
			
			if($this->input->post('cust_id')==''){
				
			   $customer = Stripe_Customer::create(array(
                      "source" => $this->input->post('stripeToken'),
                      "plan" => 'PM',
                      "email" => $this->input->post('email'),
					  "quantity"=>$tot_amount
                      ), STRIPE_KEY_SK);
			   $cust_id=$customer->id;
			  // die('create');
			}
			else{
				
				try{
				$cust_id=$this->input->post("cust_id");
				 $customer = Stripe_Customer::retrieve($this->input->post("cust_id"), STRIPE_KEY_SK);
                    $customer->updateSubscription(array(
                        'plan' => 'PM',
                        "quantity" => $tot_amount
                            )
                    );
                    
                    $customer->save();
					}catch(Exception $e){
						echo $e->getmessage();
						
						
					}
					
					
					//die('update');
			}
				/*pr($data['setup']);
				die('here');*/
				
				/*$tot_pm_user=$data['setup'][0]['pm_user'] + $this->input->post('pm_user');
				$tot_support_user=$data['setup'][0]['support_user'] + $this->input->post('support_user');
				$tot_crm_user=$data['setup'][0]['crm_user'] + $this->input->post('crm_user');*/
				$tot_crm_user=$this->input->post('quantity');
				$setup_master['pm_user'] = $tot_amount;
				$total_user = $data['setup'][0]['crm_user'] + $data['setup'][0]['support_user'] + $tot_amount;
				$setup_master['total_user'] = $total_user;
				$setup_master['cust_id'] = $cust_id;
				
				$where = array('setup_id' => $setup_master_id);
				/*pr($total_user);
				die('here');*/
				$success_setup = $this->common_model->update_data(SETUP,$setup_master,$where);
			
			/*pr($customer->id);
			die();*/
				
			echo "<h1>Your payment has been completed.</h1>";
			
			
		}
 
		catch(Stripe_CardError $e) {
 
		}
		catch (Stripe_InvalidRequestError $e) {
 
		} catch (Stripe_AuthenticationError $e) {
		} catch (Stripe_ApiConnectionError $e) {
		} catch (Stripe_Error $e) {
		} catch (Exception $e) {
			
		}
		
		 
		 /*if ($success_setup) {
            $msg = $this->lang->line('setup_edit_msg');
            $this->session->set_flashdata('msg', $msg);
        }*/
		}
		$msg = $this->lang->line('payment_msg');
        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
      

        redirect($this->viewname.'/subscription');  //Redirect On Listing page
		
		
	}
	
	
	/*end here*/
    function emailSettings()
    {
        $data['settings_data'] = $this->Settings_model->getSettingsData();
        $data['drag'] = false;
        $dashWhere = "config_key='email_settings' ";
        $defaultDashboard = $this->common_model->get_records(CONFIG, array('value'), '', '', $dashWhere);

	$dashWhere1 = "config_key='email_notification_settings' ";
        $defaultNotification = $this->common_model->get_records(CONFIG, array('value'), '', '', $dashWhere1);
       
        $data['editRecord'] = (array) json_decode($defaultDashboard[0]['value']);
        $data['editRecordNotification'] = (array) json_decode($defaultNotification[0]['value']);
       
        /*for admin table data start*/

        $main_user_id = $this->config->item('master_user_id');
        // Added by Nikunj Ghelani
        $table_user = LOGIN.' as lg';
        $where_user = array("lg.login_id" => $main_user_id,"lg.is_delete" => 0);
        $fields_user = array("lg.*");
        $check_user_data = $this->common_model->get_records($table_user,$fields_user,'','','','','','','','','',$where_user);



        $table_1 = SETUP_MASTER.' as ct';

        $where_1 = "ct.login_id = ".$main_user_id." AND ct.email = '".$check_user_data[0]['email']."'";
        $fields_1 = array("ct.*");
        $data['setup'] = $this->common_model->get_records_data($table_1,$fields_1,'','','','','','','','','',$where_1);

        //Get Records of General settings from config table
        $gensettingWhere = "config_key='general_settings'";
        $defaultDashboard1 = $this->common_model->get_records(CONFIG, array('value'), '', '', $gensettingWhere);
		
        $table1 = COUNTRIES . ' as c';
        $match1 = "c.use_status=1 AND country_status=1 AND is_delete_currency=0 ";
        $fields1 = array("c.country_id,c.currency_code,c.currency_symbol");
        $data['currency_list'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
		
        $data['editRecords'] = (array) json_decode($defaultDashboard1[0]['value']);

        // Get Records of Goole Analytics Settings from Config table
    	$google_analytics_settingsWhere = "config_key='google_analytics_settings'";
    	$defaultDashboard2 = $this->common_model->get_records(CONFIG, array('value'), '', '', $google_analytics_settingsWhere);
        $data['editRecords1'] = (array)json_decode($defaultDashboard2[0]['value']);
		
		
        $data['header'] = array('menu_module' => 'settings');

        $table = COUNTRIES . ' as cm';
        $fields = array("cm.country_name,cm.country_id");

        $data['country_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', '');
        //$this->add();
        
        $data['header'] = array('menu_module' => 'settings');
        $data['main_content'] = 'emailSettings';
        $this->parser->parse('layouts/DashboardTemplate', $data);
    }
    
    public function updatemailNotification() 
    {
       
        //added for selected tab
        $sess_array = array('setting_current_tab' => 'email_notification');
        $this->session->set_userdata($sess_array);
        $email_settings_arr = array();

        if (count($this->input->post()) > 0)
        {
            $id = $this->input->post('id');
            if($id==""){
                    $id =1;
            }else{
                    $id=$id;
            }

            $email_settings_arr["id"] = $id;
            $email_settings_arr["global_email"] = $this->input->post('global_email');
            $email_settings_arr["crm_email"] = $this->input->post('crm_email');
            $email_settings_arr["pm_email"] = $this->input->post('pm_email');
            $email_settings_arr["finance_email"] = $this->input->post('finance_email');
            $email_settings_arr["support_email"] = $this->input->post('support_email');
            $email_settings_arr["hr_email"] = $this->input->post('hr_email');
        }


        $data['config_key'] = 'email_notification_settings';
        $data['value'] = json_encode($email_settings_arr);
        $where = array('config_key' => 'email_notification_settings');

        // Update form data into database
        if ($this->common_model->update(CONFIG, $data, $where)) {
            
            $msg = lang('UPDATE_SUC_EMAIL_NOTIFICATION');
            $this->session->set_flashdata('msg', $msg);
        } else {
        
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', $msg);
        }
        
     
        redirect('Settings/emailSettings'); //Redirect On Listing page
    }
    
    function userSettings()
    {
        $data['header'] = array('menu_module' => 'settings');
        $data['main_content'] = 'userSettings';
        $this->parser->parse('layouts/DashboardTemplate', $data);
    }
    public function count_inactive_user(){
		$this->input->get('type');
		
		
		$setup_master_id=$this->input->get('setup_id');
		$table_1 = SETUP_MASTER.' as ct';
		$where_1 = "ct.setup_id =".$setup_master_id;
		$fields_1 = array("ct.*");
		$data['setup'] = $this->common_model->get_records_data($table_1,$fields_1,'','','','','','','','','',$where_1);
		/*pr($data['setup']);
		die();*/
		$tot_user='';
		if($this->input->get('type')=='crm'){
			$tot_user=$data['setup'][0]['crm_user'];
			}
		elseif($this->input->get('type')=='pm'){
			$tot_user=$data['setup'][0]['pm_user'];
			}
		elseif($this->input->get('type')=='support'){
			$tot_user=$data['setup'][0]['support_user'];
			}
		$main_user_id = $this->config->item('master_user_id');
		$table1 = LOGIN . ' as lg';
		if($this->input->get('type')=='crm'){
			
			$match1 = "lg.status=1 AND lg.is_crm_user=1 AND lg.is_delete=0 AND lg.parent_id=".$main_user_id;
		}
		elseif($this->input->get('type')=='pm'){
			$match1 = "lg.status=1 AND lg.is_pm_user=1 AND lg.is_delete=0 AND  lg.parent_id=".$main_user_id;
			}
			elseif($this->input->get('type')=='support'){
				$match1 = "lg.status=1 AND lg.is_support_user=1 AND lg.is_delete=0 AND lg.parent_id=".$main_user_id;
				}
		//$match1 = "lg.status=1 AND lg.parent_id=".$main_user_id;
        $fields1 = array("count(lg.login_id) as total_user");
        $total_inactive = $this->common_model->get_records($table1, $fields1, '', '', $match1);
       
       
        echo $tot_user-$total_inactive[0]['total_user']-1;
        
        /*pr($total_inactive);
        die('here');*/
        
		}
		
		public function billing_information(){
		//pr($_POST);
		$sub_domain=array_shift((explode(".",$_SERVER['HTTP_HOST'])));
		$sess_array = array('setting_current_tab' => 'biling_information');
        $this->session->set_userdata($sess_array);
    		
		$setup_master_id=$this->input->post('setup_id');
		$table_1 = SETUP_MASTER.' as ct';
		$where_1 = "ct.setup_id =".$setup_master_id;
		$fields_1 = array("ct.*");
		$data['setup'] = $this->common_model->get_records_data($table_1,$fields_1,'','','','','','','','','',$where_1);
		
		$main_user_id = $this->config->item('master_user_id');
		$table_2 = BILL.' as ct';
		if(count($data['setup'])>0){
        $where_2 = "ct.domain_name = '".$data['setup'][0]['domain_name']."'";
		}
		else{
		$where_2 = "ct.login_id = '".$main_user_id."'";	
			}	
		//$where_2 = "ct.login_id =".$main_user_id;
		$fields_2 = array("ct.*");
		$data['bill'] = $this->common_model->get_records_data($table_2,$fields_2,'','','','','','','','','',$where_2);
		//pr($data['bill']);
				
	    //$tot_amount=$this->input->post('quantity')+$currentCRM;
	    //for company name edit 
		 $setup_master['company_name'] = $this->input->post('company_name');
		 $where = array('setup_id' => $setup_master_id);
		 $success_setup = $this->common_model->update_data(SETUP,$setup_master,$where);
		 // for login data edit data
		 if(!isset($data['bill'][0]['login_id'])){
			 //die('empty');
		 $login['domain_name'] = $data['setup'][0]['domain_name'];
		 $login['email'] = $this->input->post('email');
		 $login['login_id'] = $main_user_id;
		 $login['address'] = $this->input->post('add1');
		 $login['address2'] = $this->input->post('add2');
		 $login['city'] = $this->input->post('city');
		 $login['state'] = $this->input->post('state');
		 $login['country'] = $this->input->post('country');
		 $login['pincode'] = $this->input->post('postal_code');
		 $login['phoneno'] = $this->input->post('telephone1');
		 $login['vat_number'] = $this->input->post('vat_number');
		 $success_setup = $this->common_model->insert_data(BILL,$login);
			 }
			 else{
				 //die('not empty');
		 $login['email'] = $this->input->post('email');
		 $login['address'] = $this->input->post('add1');
		 $login['address2'] = $this->input->post('add2');
		 $login['city'] = $this->input->post('city');
		 $login['state'] = $this->input->post('state');
		 $login['country'] = $this->input->post('country');
		 $login['pincode'] = $this->input->post('postal_code');
		 $login['phoneno'] = $this->input->post('telephone1');
		 $login['vat_number'] = $this->input->post('vat_number');
		 $where = array('login_id' => $data['bill'][0]['login_id']);
		 //pr($login);
		 //die('here');
		 $success_setup = $this->common_model->update_data(BILL,$login,$where);
		}
		if ($success_setup) {
            $msg = $this->lang->line('bill_update');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
		
		redirect($this->viewname.'/subscription');  //Redirect On Listing page
		
		
	}
        
    /*
    @Author : sanket Jayani
    @Desc   : Configure Newsletter API Settings
    @Date   : 29/02/2016
   */

    function newsletter_configuration()
    {
        $newsletter_type = $this->input->post('newsletter_type');
        
        $sess_array = array('setting_current_tab' => 'newsletter_configuration');
        $this->session->set_userdata($sess_array);
        
        $arr_newsletter_configuration = array('newsletter_type',$newsletter_type); 
        
        $data_type['value'] = $newsletter_type;
        $where_type = array('config_key' => 'newsletter_type');
        $this->common_model->update(CONFIG, $data_type, $where_type);
        if($newsletter_type == '1')
        {
            $mailchimp_api_key = $this->input->post('mailchimp_api_key');
            
            
            $mailchimnp_configuration = array('newsleeter_type'=>$newsletter_type,
                'api_key'=>$mailchimp_api_key);
            
            $data['config_key'] = 'mailchimp_configuration';
            $data['value'] = json_encode($mailchimnp_configuration);
            $where = array('config_key' => 'mailchimp_configuration');
            
            if ($this->common_model->update(CONFIG, $data, $where)) 
            {
                $this->session->set_flashdata('msg', lang('SUCCESS_MAILCHIMP_CONFIGURATION'));
            } else {
                $this->session->set_flashdata('msg', $this->lang->line('error_msg'));
            }
        
        }else if($newsletter_type == '2')
        {
            $mailchimp_api_key = $this->input->post('cmonitor_api_key');
            
            
            $mailchimnp_configuration = array('newsleeter_type'=>$newsletter_type,
                'api_key'=>$mailchimp_api_key);
            
            $data['config_key'] = 'campaign_monitor_configuration';
            $data['value'] = json_encode($mailchimnp_configuration);
            $where = array('config_key' => 'campaign_monitor_configuration');
            
            if ($this->common_model->update(CONFIG, $data, $where)) 
            {
                $this->session->set_flashdata('msg', lang('SUCCESS_CAMPAIGN_MONITOR_CONFIGURATION'));
            } else {
                $this->session->set_flashdata('msg',$this->lang->line('error_msg'));
            }
        }else if($newsletter_type == '3')
        {
            $moosend_api_key = $this->input->post('moosend_api_key');
            
            
            $mailchimnp_configuration = array('newsleeter_type'=>$newsletter_type,
                'api_key'=>$moosend_api_key);
            
            $data['config_key'] = 'moosend_configuration';
            $data['value'] = json_encode($mailchimnp_configuration);
            $where = array('config_key' => 'moosend_configuration');
            
            if ($this->common_model->update(CONFIG, $data, $where)) 
            {
                $this->session->set_flashdata('msg', lang('SUCCESS_MOOSEND_CONFIGURATION'));
            } else {
                
                $this->session->set_flashdata('msg', $this->lang->line('error_msg'));
            }
        }else if($newsletter_type == '4')
        {
            $getresponse_api_key = $this->input->post('getresponse_api_key');
            
            
            $mailchimnp_configuration = array('newsleeter_type'=>$newsletter_type,
                'api_key'=>$getresponse_api_key);
            
            $data['config_key'] = 'get_response_configuration';
            $data['value'] = json_encode($mailchimnp_configuration);
            $where = array('config_key' => 'get_response_configuration');
            
            if ($this->common_model->update(CONFIG, $data, $where)) 
            {
                $this->session->set_flashdata('msg', lang('SUCCESS_GET_RESPOSNE_CONFIGURATION'));
            } else {
                
                $this->session->set_flashdata('msg', $this->lang->line('error_msg'));
            }
        }
      
        redirect('Settings');
    }
	
}

?>
