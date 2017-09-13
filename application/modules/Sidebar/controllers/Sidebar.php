<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sidebar extends CI_Controller {

    function __construct() {
        $this->CI = & get_instance();
        // parent::__construct();
		$system_lang = $this->CI->common_model->get_lang();
		
		$this->selectedLang = $system_lang;
    }

    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : Call Head area
      Input  : Bunch of Array
      Output : All CSS and JS
      Date   : 04/02/2016
     */

    public function head($param = NULL) {
		$data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $data['selected_language'] = $this->selectedLang;  //get Selected Language file 

        $this->CI->load->view('Sidebar/head', $data);
    }

    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : Call Header For all the template
      Input  : Bunch of Array
      Output : Top Side Header(Logo, Menu, Language)
      Date   : 13/01/2016
     */

    public function header($param = NULL) {
		
		
        $data['param'] = $param;           //Default Parameter
        $data['user_info'] = $this->CI->session->userdata('LOGGED_IN');  //Current Login information 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View
       // $data['role_type'] = $this->CI->session->userdata('LOGGED_IN');
        $selected_new_language = $this->selectedLang;  //get Selected Language file 
		//$data['main_role_type'] = $this->config->item('super_admin_role_id');
        
        $table = LANGUAGE_MASTER . ' as lm';
        $where = "lm.language_name= '" . $selected_new_language . "' ";
        $fieldsn = array("name");
        $language = $this->CI->common_model->get_records($table, $fieldsn, '', '', '', '', '', '', '', '', '', $where, '', '');
        $data['selected_language'] = $language;
        /*pr( $data['selected_language']);
        die();*/

        // by brt for passing mail config data

        $where = array("user_id" => $data['user_info']['ID']);
        $fields = array("id,user_id,email_id,email_pass");
        $emailConfigData = $this->CI->common_model->get_records(EMAIL_CONFIG, $fields, '', '', '', '', '', '', '', '', '', $where, '', '');
        @$data['email_config_data'] = $emailConfigData[0];
		
		 $table = SETUP_MASTER.' as ct';
        $where_setup_data = array("ct.login_id" => $data['user_info']['ID']);
        $fields = array("ct.*");
        $data['check_setup_user_menu'] = $this->CI->common_model->get_records_data($table,$fields,'','','','','','','','','',$where_setup_data);
        //pr($check_setup_user_menu);exit;

       // $this-> messageCount();
        @$data['email_detail_data']['Unread'] = 0;

        $this->CI->load->view('Sidebar/header', $data);
    }

    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : Call Header For Login template
      Input  : Bunch of Array
      Output : Top Side Header(Logo, Menu, Language)
      Date   : 04/02/2016
     */

    public function loginheader($param = NULL) {
        $data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $selected_new_language = $this->selectedLang;  //get Selected Language file 
              
        $table = LANGUAGE_MASTER . ' as lm';
        $where = "lm.language_name = '" . $selected_new_language . "' ";
        $fieldsn = array("name");
        $language = $this->CI->common_model->get_records($table, $fieldsn, '', '', '', '', '', '', '', '', '', $where, '', '');
        $data['selected_language'] = $language;

        $this->CI->load->view('Sidebar/loginheader', $data);
    }

    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : Call Footer
      Input  : Bunch of Array
      Output : Top Side Header(Logo, Menu, Language)
      Date   : 04/02/2016
     */

    public function loginfooter($param = NULL) {
        $data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $data['selected_language'] = $this->selectedLang;  //get Selected Language file 

        $this->CI->load->view('Sidebar/loginfooter', $data);
    }

    public function footer($param = NULL) {
        $data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $data['selected_language'] = $this->selectedLang;  //get Selected Language file 

        $this->CI->load->view('Sidebar/footer', $data);
    }

    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : Call Left Menu area
      Input  : Bunch of array
      Output : Unset Error Session
      Date   : 13/01/2016
     */

    public function leftmenu($param = NULL) {
        $data['param'] = $param;           //Default Parameter
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
		$data['sub_domain']=array_shift((explode(".",$_SERVER['HTTP_HOST'])));
        $this->CI->load->view('Sidebar/leftmenu', $data);
    }

    /*
      Author : Niral Patel
      Desc   : Call Project Left Menu area
      Input  : Bunch of array
      Output : Unset Error Session
      Date   : 27/01/2016
     */

    public function projrctleftmenu($param = NULL) {
        $data['param'] = $param;           //Default Parameter
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 

        $this->CI->load->view('Sidebar/projectleftmenu', $data);
    }

    /*
      Author : Nikunj Ghelani
      Desc   : Call Support Left Menu area
      Input  :
      Output :
      Date   : 25/02/2016
     */

    public function supportleftmenu($param = NULL) {
        $data['param'] = $param;           //Default Parameter
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 

        $this->CI->load->view('Sidebar/supportleftmenu', $data);
    }

    /*
      Author : Niral Patel
      Desc   : Call Project Left Menu area
      Input  : Bunch of array
      Output : Unset Error Session
      Date   : 27/01/2016
     */

    public function projrctheader($param = NULL) {
        $data['param'] = $param;           //Default Parameter
        $data['project_viewname'] = $this->CI->router->fetch_class();
        //Current View 
        //get project
        $project_id = $this->CI->session->userdata('PROJECT_ID');
        $this->user_info = $this->CI->session->userdata('LOGGED_IN');  //Current Login information
        if (!empty($project_id)) {
            $where = array('project_id' => $project_id);
            $data['project'] = $this->CI->common_model->get_records(PROJECT_MASTER, array('project_id', 'project_name', 'status'), '', '', $where, '');
        }
        $data['cur_project_id'] = $project_id;
        $fields = array('pt.project_id,pt.project_code,pt.project_name');
        if($this->user_info['ROLE_TYPE'] == '39')
        {$where = array('pt.is_delete' => 0);}
        else
        {$where = array('pt.is_delete' => 0,'te.user_id'=>$this->user_info['ID']);}
        $group_by = 'pt.project_id';
        $join_table = array(
            PROJECT_ASSIGN_MASTER . ' as te' => 'te.project_id=pt.project_id',
            LOGIN . ' as l' => 'te.user_id=l.login_id  and l.is_delete = 0',
            );
        $data['project_data'] = $this->CI->common_model->get_records(PROJECT_MASTER.' as pt', $fields, $join_table, 'left', $where, '', '', '', 'project_id', 'desc',$group_by);
        $this->CI->load->view('Sidebar/projectheader', $data);
    }

    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : Call Mobile Menu and Header
      Input  : Bunch of array
      Output : Top Side Header and Menu
      Date   : 18/01/2016
     */

    public function mobileheader($param = NULL) {
        $data['param'] = $param;           //Default Parameter
        $data['cur_viewname'] = $this->CI->router->fetch_class();    //Current View

        $this->CI->load->view('Sidebar/mobileheader', $param);
    }
    /*
      Author : Niral Patel
      Desc   : Call Mobile Menu and Header
      Input  : Bunch of array
      Output : Top Side Header and Menu
      Date   : 30/03/2016
     */

    public function mobileprojectheader($param = NULL) {
        $data['param'] = $param;           //Default Parameter
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View

        $this->CI->load->view('Sidebar/mobileprojectheader', $param);
    }
    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : Unset Error Message Variable for all Form
      Input  :
      Output : Unset Error Session
      Date   : 18/01/2016
     */

    public function unseterror() {
        $error = $this->CI->session->userdata('ERRORMSG');
        if (isset($error) && !empty($error)) {
            $this->CI->session->unset_userdata('ERRORMSG');
        }
        //session_destroy();
    }

    /*
      Author : Maulik Suthar
      Desc   : Calendar Widget for the pages
      Input  :
      Output :
      Date   : 23/02/2016
     */

    public function taskCalendar() {
        $this->CI->load->view('Sidebar/widgetCalendar');
    }

    /*
      Author : Maulik Suthar
      Desc   :Opportinity dragabble table
      Input  :
      Output :
      Date   : 10/03/2016
     */

    public function opportunitiesTable($param = NULL) {
        $data['param'] = $param;           //Default Parameter
        $data['cur_viewname'] = $this->CI->uri->segment(1);     //Current View
        $this->CI->load->library('pagination');
        $data['project_view'] = '';
        //Get Records From COST_MASTER Table       
        //config variable for the pagination
        $data['lead_view'] = '/LEAD';
        $table = PROSPECT_MASTER . ' as pm';
        $data['opportunity_view'] = '';
        $data['status'] = array(1 => 'Opportunity', 2 => 'Lead', 3 => 'Account');
        $config1['base_url'] = site_url($data['project_view'] . '/salesAjaxList');
        $data['salessortField'] = 'creation_date';
        $data['salessortOrder'] = 'desc';
        $dbSearch = "";
        $params['join_tables'] = array(OPPORTUNITY_REQUIREMENT_CONTACTS . ' as pc' => 'pm.prospect_id=pc.prospect_id');
        $params['join_type'] = 'left';
        $match = "";
        $where_search = '';
        $group_by = 'pm.prospect_id';
        $fields = array("pm.prospect_id,count(pm.prospect_id) as opp_count,pm.prospect_name,pm.prospect_auto_id, pm.status_type,count(pc.prospect_id) as contact_count,pc.contact_id,pm.creation_date");
        //  $where = array("pm.is_delete" => "0", "pm.status" => "1");
        if ($this->CI->input->get('search') != '') {
            $data['search'] = $searchtext = $this->CI->input->get('search');
            $where_search = '(prospect_name LIKE "%' . $searchtext . '%" OR prospect_auto_id LIKE "%' . $searchtext . '%" OR status_type LIKE "%' . $searchtext . '%" OR creation_date LIKE "%' . $searchtext . '%"  OR contact_name LIKE "%' . $searchtext . '%")';
        }
        $config1['total_rows'] = count($this->CI->common_model->getSalesoverviewDataDrag('', '', $where_search));
        $config1['per_page'] = RECORD_PER_PAGE;
        $choice = $config1["total_rows"] / $config1["per_page"];
        $config1["num_links"] = floor($choice);

        if ($this->CI->input->get('orderField') != '') {
            $data['salessortField'] = $this->CI->input->get('orderField');
        }
        if ($this->CI->input->get('sortOrder') != '') {
            $data['salessortOrder'] = $this->CI->input->get('sortOrder');
        }


        $data['salePage'] = $data['page'] = ($this->CI->uri->segment(3)) ? $this->CI->uri->segment(3) : 0;
        $data['prospect_data'] = $this->CI->common_model->getSalesoverviewDataDrag($config1['per_page'], $data['page'], $where_search, $data['salessortField'], $data['salessortOrder']);
//        echo $this->CI->db->last_query();
        $data['config1'] = $config1;

        $this->CI->load->view('Sidebar/opportunitiesTable', $data);
    }


    public function messageCount(){

        $this->CI->load->library('Encryption');  // this library is for encoding/decoding password
        $converter = new Encryption;

        $user_id= $this->CI->session->userdata('LOGGED_IN')['ID'];

        $where = array("user_id" => $user_id);
        $fields = array("id,user_id,email_id,email_pass");

        $emailAccountData = $this->CI->common_model->get_records(EMAIL_CONFIG, $fields, '', '', '', '', '', '', '', '', '', $where, '', '');

//        $server = '{imap.gmail.com:993/ssl/novalidate-cert}';

        $emailAccountData[0]['email_pass']=$converter->decode($emailAccountData[0]['email_pass']);
        $domain=explode('@',$emailAccountData[0]['email_id']);
        $configPath=$_SERVER['DOCUMENT_ROOT'].'blazedeskcrm/email_client/data/_data_/_default_/domains/'.$domain[1].'.ini';
        $iniSetting= parse_ini_file($configPath);


        $server = '{'.$iniSetting['imap_host'].':'.$iniSetting['imap_port'].'/'.$iniSetting['imap_secure'].'}';
        $login=$emailAccountData[0]['email_id'];
        $passwd=$emailAccountData[0]['email_pass'];

        $mbox = imap_open("$server", $login, $passwd);
        $mBoxDetail=imap_mailboxmsginfo($mbox);
        return $mBoxDetail;
    }
}
