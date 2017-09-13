<?php
/**
 * This class will be called by the post_controller_constructor hook and act as ACL
 * @author Mehul Patel
 */
class ACL {
	/**
	 * Array to hold the rules
	 * Keys are the role_id and values arrays
	 * In this second level arrays the key is the controller and value an array with key method and value boolean
	 * @var Array
	 */
	public function __construct() {

	}
	/**
	 * The main method, determines if the a user is allowed to view a site
	 * @author
	 * @return boolean
	 */

	public function auth() {
		$CI = & get_instance();

		$system_lang = $CI->common_model->get_lang();
		$CI->config->set_item('language', $system_lang);
		$CI->lang->load('label', $system_lang ? $system_lang : 'english');

		$this->loginpage_redirect();  //Function added by RJ for redirection
		// added by Ritesh Rana
		$class = $CI->router->fetch_class();		//Get Class
		$method = $CI->router->fetch_method();		//Get Method

		$crm_module = $this->listofCRMcontroller(); // Get List of CRM Module's Controller

		$pm_module = $this->listofPMcontroller();// Get List of PM Module's Controller

		$support_module = $this->listofSupportcontroller(); // Get List of Support Module's Controller


		// Added by Ritesh

		if(in_array($class,$crm_module)){
			if($method != 'logout') {
				checkPurchasedUserModuleForCRM();
			}
		}// Added by Ritesh
		if(in_array($class,$pm_module)){
			if($class != "Filemanager"){
					
				checkPurchasedUserModuleForPM();
			}
		}// Added by Ritesh
		if(in_array($class,$support_module)){
			checkPurchasedUserModuleForSupport();

		}

		if (!isset($CI->router)) { # Router is not loaded
			$CI->load->library('router');
		}
		if (!isset($CI->session)) { # Sessions are not loaded
			$CI->load->library('session');
			$CI->load->library('database');
		}

		$dbPermArray = $resultData = $permArrMaster = $validateArr = array();
		$flag = 0;
		$class = $CI->router->fetch_class();
		// Allow Controller class which can be access without permission  added by Mehul Patel
		$allow_class = array('Dashboard', 'Help','Masteradmin','Set_language','CRMCron','InvoicesCron','ProjectCron','MyProfile','Mail');
		$method = $CI->router->fetch_method();

		if($CI->session->has_userdata('LOGGED_IN')){
			$login_id = $CI->session->userdata['LOGGED_IN']['ID'];
			// Below Code is use for expiring your beta version of Blazedeskcrm
	
       
                $servername = "localhost";
                $db_name = 'masterblazedeskcrm';
                $db_pass = 'Infoc!ty2';
                $username = 'root';
                $password = $db_pass;


            $con = @mysqli_connect($servername, $username, $db_pass, $db_name);

            if (@mysqli_connect_errno($con)) {
              //  echo "Failed to connect to MySQL: " . @mysqli_connect_error();
            }


            $subdomin = array_shift((explode(".", $_SERVER['HTTP_HOST'])));
            //$subdomin = 'nmtst';
            $row = array();
            $qry = "SELECT * from blzdsk_setup_master WHERE domain_name='" . $subdomin . "' AND is_active=0";
            $result = @mysqli_query($con, $qry);
            $row = @mysqli_fetch_array($result, MYSQLI_ASSOC);

            @mysqli_free_result($result);
            @mysqli_close($con);
            
            $urlSubArr=array();
            $protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
            $current_url = $protocol . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			$urlSubArr=array($protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'Settings/subscription',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'Settings/billing_information',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'Settings/checkout_pm',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'Settings/checkout_support',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'Settings/checkout_crm',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'Settings/addcrmuser',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'Settings/addpmuser',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'Settings/addsupuser',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'Settings/removecrmuser',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'Settings/removepmuser',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'Settings/removesupuser',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'Help/add',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'Help/saveHelpData',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'Set_language',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'CRMCron/index',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'InvoicesCron/index',
			 $protocol . '://' . $_SERVER['SERVER_NAME'] .'/'.$subdomin.'/'. 'ProjectCron/index'
			 );
         
            if (count($row) > 0) {
                $redFlag = 0;
               // echo $current_url . "<br/>";
               // print_r($urlSubArr) . "<br/>";
                // die();
				
				
				if(strpos($current_url,'Set_language')) {
					$urlSubArr[] = $current_url;
				}
				if (!in_array($current_url,$urlSubArr) ) {
                    redirect('Settings/subscription');
                }
				
            }
			
			
		}else{
			$login_id = "";
		}

		//$master_user_id = $CI->config->item('master_user_id');

		if ($CI->session->has_userdata('LOGGED_IN')) {
			$session = $CI->session->userdata('LOGGED_IN');
			$CI->db->select('module_unique_name,controller_name,name,MM.component_name');
			$CI->db->from('aauth_perm_to_group as APG');
			$CI->db->join('module_master as MM', 'MM.module_id=APG.module_id');
			$CI->db->join('aauth_perms as AP', 'AP.id=APG.perm_id');
			$CI->db->where('role_id', $session['ROLE_TYPE']);
			$CI->db->where('controller_name', $class);
			$resultData = $CI->db->get()->result_array();
			$configPerms = $CI->load->config('acl');
			//print_r($resultData);
			$permsArray = $CI->config->item($class);
			if (count($resultData) > 0) {
				$dbPermArray = array_map(function ($obj) {
					return $obj['name'];
				}, $resultData);
			}
			foreach ($dbPermArray as $prmObj) {
				if (is_array($permsArray) && array_key_exists($prmObj, $permsArray)) {
					$permArrMaster[] = $permsArray[$prmObj];
				}
			}
			foreach ($permArrMaster as $permObj) {
				foreach ($permObj as $innerObj) {
					$validateArr[] = $innerObj;
				}
			}
			if (in_array($class, $allow_class)) {
				return true;
			} else {

				if (in_array($method, $validateArr)) {
						
					/*
					 * custom code for validating project status condition whether project is completed or not
					 */
					if ($resultData[0]['component_name'] == 'PM' && $method != 'view' && $class != 'Projectmanagement') {
						if ($CI->session->has_userdata('PROJECT_STATUS') && $CI->session->userdata('PROJECT_STATUS') == 3) {
							return false;
						}else{
							return true;
						}
					}else{
						return true;
					}
						
				} else {
						
					if(!empty($validateArr)){
						//	$login_id = $CI->session->userdata['LOGGED_IN']['ID'];
						$CI->db->select('l.is_crm_user,l.is_pm_user,l.is_support_user');
						$CI->db->from('login as l');
						$CI->db->where('l.login_id', $login_id);
						$resultData = $CI->db->get()->result_array();
						
						if (count($resultData) != 0) {
							if (isset($resultData[0]['is_crm_user']) && $resultData[0]['is_crm_user'] == 1) {
								$msg = $CI->lang->line('rights_error_msg');
								$CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
								redirect('Dashboard'); //Redirect on Dashboard
								// return true;
							} elseif (isset($resultData[0]['is_pm_user']) && $resultData[0]['is_pm_user'] == 1) {
								$msg = $CI->lang->line('rights_error_msg');
								$CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
								redirect('Projectmanagement/Projectdashboard'); //Redirect on PM Dashboard
								// return true;
							} elseif (isset($resultData[0]['is_support_user']) && $resultData[0]['is_support_user'] == 1) {

								$msg = $CI->lang->line('rights_error_msg');
								$CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
								redirect('Support'); //Redirect on Support Dashboard
								// return true;
							}else{
								$msg = $CI->lang->line('rights_error_msg');
								$CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
								redirect('Dashboard');
								// return true;
							}
						} else {
							/* $this->session->set_flashdata('msgs', "<div class='alert alert-danger text-center'>" . lang('ERROR_MSG_LOGIN') . "</div>");
							 */
							redirect('Dashboard');
							// return true;
							//redirect(base_url('Dashboard/logout')); //Redirect on Dashboard
						}

						//	return true;
					}
			}
			}


			// }
		}
	}

	/*
	 public function auth() {
	 $CI = & get_instance();

	 $system_lang = $CI->common_model->get_lang();
	 $CI->config->set_item('language', $system_lang);
	 $CI->lang->load('label', $system_lang ? $system_lang : 'english');

	 $this->loginpage_redirect();  //Function added by RJ for redirection

	 if (!isset($CI->router)) { # Router is not loaded
	 $CI->load->library('router');
	 }
	 if (!isset($CI->session)) { # Sessions are not loaded
	 $CI->load->library('session');
	 $CI->load->library('database');
	 }
	 $dbPermArray = $resultData = $permArrMaster = $validateArr = array();
	 //   $flag = 0;
	 $class = $CI->router->fetch_class();
		// Allow Controller class which can be access without permission  added by Mehul Patel
		$allow_class = array('Dashboard', 'Help','Masteradmin','Set_language');
		$method = $CI->router->fetch_method();
		if ($CI->session->has_userdata('LOGGED_IN')) {
		$session = $CI->session->userdata('LOGGED_IN');
		$CI->db->select('module_unique_name,controller_name,name,MM.component_name');
		$CI->db->from('aauth_perm_to_group as APG');
		$CI->db->join('module_master as MM', 'MM.module_id=APG.module_id');
		$CI->db->join('aauth_perms as AP', 'AP.id=APG.perm_id');
		$CI->db->where('role_id', $session['ROLE_TYPE']);
		$CI->db->where('controller_name', $class);
		$resultData = $CI->db->get()->result_array();
		$configPerms = $CI->load->config('acl');
		$permsArray = $CI->config->item($class);

		if(!empty($resultData)){
		if (count($resultData) > 0) {
		$dbPermArray = array_map(function ($obj) {
		return $obj['name'];
		}, $resultData);
		}
		if(!empty($dbPermArray)){
		foreach ($dbPermArray as $prmObj) {
		if (array_key_exists($prmObj, $permsArray)) {
		$permArrMaster[] = $permsArray[$prmObj];
		}
		}
		}
		if(!empty($permArrMaster)){
		foreach ($permArrMaster as $permObj) {
		foreach ($permObj as $innerObj) {
		$validateArr[] = $innerObj;
		}
		}
		}
		if (in_array($method, $validateArr)) {
		 
		// custom code for validating project status condition whether project is completed or not

		if ($resultData[0]['component_name'] == 'PM' && $method != 'view' && $class != 'Projectmanagement') {
		if ($CI->session->has_userdata('PROJECT_STATUS') && $CI->session->userdata('PROJECT_STATUS') == 3) {
		return false;
		}
		}
		return true;
		} else {
		$msg = $CI->lang->line('rights_error_msg');
		//$msg = "Don't show this msg";
		$CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
		redirect('Dashboard');
		return true;
		}

		}else{
		if (in_array($class, $allow_class)) {

		return true;
		}
		}
		}
		}
		 
		/*
		Author : RJ(Rupesh Jorkar)
		Desc   : Make Login condition
		Output : If user are Logged out then redirect on login page.
		Date   : 01/02/2016
		*/
	public function loginpage_redirect() {
		$CI = & get_instance();
		$user_info = $CI->session->userdata('LOGGED_IN');  //Current Login information
		if (empty($user_info) && $user_info == "") {
				
			if (!in_array($CI->uri->ruri_string(), $this->before_login_allow_pages)) {
				//Condition for compare module
				if(in_array($CI->router->fetch_class(), $this->before_login_allow_module)){

				} else {
					echo "<script>window.location.href='" . base_url('Masteradmin/login') . "';</script>";
					die;
					//redirect(base_url('Masteradmin/login'),'refresh');  //Redirect on login//exit;
				}
			}
		} else {
								
			if (in_array($CI->uri->ruri_string(), $this->after_login_ignore_pages)) {
				//$msg = $CI->lang->line('DONT_HAVE_PAGE_PERMISSION');
				//$CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
				redirect(base_url());  //Redirect on Dashboard
			}
		}
	}
	/*
	 Author : Rupesh Jorkar(RJ)
	 Desc   : $before_login_allow_module = Allow whole Module to access before Login.
	 : $before_login_allow_pages  = Allow Only page before Login.
	 : $after_login_ignore_pages  = Ignore Pages after login.
	 Date   : 01/02/2016
	 */
	private $before_login_allow_module = array('EstimatesClient', 'ClientView', 'Help','CRMCron','ProjectCron','InvoicesCron','KnowledgeBase');
	private $before_login_allow_pages  = array('../modules/Masteradmin/controllers/Masteradmin/index', '../modules/Masteradmin/controllers/Masteradmin/login', '../modules/Masteradmin/controllers/Masteradmin/verifylogin', 'Set_language/index', '../modules/Masteradmin/controllers/Masteradmin/forgotpassword', '../modules/Masteradmin/controllers/Masteradmin/resetpassword', '../modules/Masteradmin/controllers/Masteradmin/updatepassword', '../modules/Masteradmin/controllers/Masteradmin/updatePasswords', '../modules/EstimatesClient/controllers/EstimatesClient/ClientView', '../modules/EstimatesClient/controllers/EstimatesClient/add_autograph', '../modules/EstimatesClient/controllers/EstimatesClient/welcome', '../modules/EstimatesClient/controllers/EstimatesClient/error', '../modules/Masteradmin/controllers/Masteradmin/removed_session', '../modules/Masteradmin/controllers/Masteradmin/forgotpassword', '../modules/Masteradmin/controllers/Masteradmin/resetpassword', '../modules/Masteradmin/controllers/Masteradmin/updatepassword', '../modules/Masteradmin/controllers/Masteradmin/updatePasswords', '../modules/EstimatesClient/controllers/EstimatesClient/ClientView', '../modules/EstimatesClient/controllers/EstimatesClient/add_autograph', '../modules/EstimatesClient/controllers/EstimatesClient/welcome', '../modules/EstimatesClient/controllers/EstimatesClient/error','../modules/Support/controllers/KnowledgeBase/index');
	private $after_login_ignore_pages  = array('../modules/Masteradmin/controllers/Masteradmin/index', '../modules/Masteradmin/controllers/Masteradmin/login', '../modules/Masteradmin/controllers/Masteradmin/verifylogin', '../modules/Masteradmin/controllers/Masteradmin/forgotpassword');

	// Added by Mehul Patel
	public function listofCRMcontroller(){
		$CI = & get_instance();
		$crmController = array();
		$CI->db->select('mm.controller_name');
		$CI->db->from('module_master as mm');
		$CI->db->where('mm.component_name', "CRM");
		$CI->db->where('mm.status', "1");
		$resultData = $CI->db->get()->result_array();
		 
		foreach ($resultData As $key=>$val){
			foreach($val as $k=>$v){
				array_push($crmController,$v);
			}
		}
		return $crmController;
	}
	// Added by Mehul Patel
	public function listofPMcontroller(){
		$CI = & get_instance();
		$pmController = array();
		$CI->db->select('mm.controller_name');
		$CI->db->from('module_master as mm');
		$CI->db->where('mm.component_name', "PM");
		$CI->db->where('mm.status', "1");
		$resultData = $CI->db->get()->result_array();
		 
		foreach ($resultData As $key=>$val){
			foreach($val as $k=>$v){
				array_push($pmController,$v);
			}
		}
		return $pmController;
	}
		// Added by Mehul Patel
	public function listofSupportcontroller(){
		$CI = & get_instance();
		$supportController = array();
		$CI->db->select('mm.controller_name');
		$CI->db->from('module_master as mm');
		$CI->db->where('mm.component_name', "Support");
		$CI->db->where('mm.status', "1");
		$resultData = $CI->db->get()->result_array();
		 
		foreach ($resultData As $key=>$val){
			foreach($val as $k=>$v){
				array_push($supportController,$v);
			}
		}
        return $supportController;
	}
	
	// Added by Mehul Patel
	public function listofUsercontroller(){
		$CI = & get_instance();
		$userController = array();
		$CI->db->select('mm.controller_name');
		$CI->db->from('module_master as mm');
		$CI->db->where('mm.component_name', "User");
		$CI->db->where('mm.status', "1");
		$resultData = $CI->db->get()->result_array();
		 
		foreach ($resultData As $key=>$val){
			foreach($val as $k=>$v){
				array_push($userController,$v);
			}
		}
        return $userController;
	}

}
