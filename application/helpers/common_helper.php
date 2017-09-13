<?php

/*
  @Author	: RJ(Rupesh Jorkar)
  @Desc	: Function for use Pre in Short-Cut
  @Input 	: Array
  @Output	: Array
  @Date	: 12/01/2016
 */
function pr($var) {
    echo '<pre>';
    if (is_array($var)) {
        print_r($var);
    } else {
        var_dump($var);
    }
    echo '</pre>';
}

/*
  @Author	: RJ(Rupesh Jorkar)
  @Desc		: Function for Show Round Amount
  @Input 	: Array
  @Output	: Array
  @Date		: 10/03/2016
 */

function amtRound($val) {
    return round($val, 2);
}

/*
  @Author	: RJ(Rupesh Jorkar)
  @Desc		: Function for get Default Currency ID
  @Input 	: Array
  @Output	: Array
  @Date		: 22/04/2016
 */

function getDefaultCurrencyInfo() {
    $CI = & get_instance();
    $gensettingWhere = "config_key='general_settings'";
    $defaultDashboard1 = $CI->common_model->get_records(CONFIG, array('value'), '', '', $gensettingWhere);
    $generalSettings = (array) json_decode($defaultDashboard1[0]['value']);

    if (isset($generalSettings['default_currency']) && !empty($generalSettings['default_currency'])) {
        $table = COUNTRIES . ' as c';
        $match = "c.country_id=" . $generalSettings['default_currency'];
        $fields = array("c.country_id,c.currency_code,c.currency_symbol");
        $sale_aacount_count = $CI->common_model->get_records($table, $fields, '', '', $match);
        $data['country_id'] = $sale_aacount_count[0]['country_id'];
        $data['symbol'] = $sale_aacount_count[0]['currency_symbol'];
        $data['currency_code'] = $sale_aacount_count[0]['currency_code'];
        $data;
    } else {
        $data['country_id'] = '227';
        $data['symbol'] = '$';
        $data['currency_code'] = 'USD';
    }
    return $data;
}

/*
  @Author	: RJ(Rupesh Jorkar)
  @Desc		: Function for get currency Symbol
  @Input 	: Array
  @Output	: Array
  @Date		: 10/03/2016
 */

function getCurrencySymbol($country_id) {
    $ci = & get_instance();
    $table = COUNTRIES . ' as cnt';
    $fields = array("cnt.country_id, cnt.country_name, cnt.currency_symbol");
    $where = "cnt.country_id = '" . $country_id . "'";
    $currencyInfo = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    if (!empty($currencyInfo) && isset($currencyInfo)) {
        $currencySymbole = $currencyInfo[0]['currency_symbol'] . ' ';
    } else {
        $currencySymbole = "";
    }
    return $currencySymbole;
}

/*
  @Author	: RJ(Rupesh Jorkar)
  @Desc		: Set Date Formate as per Config Table
  @Input 	: Date
  @Output	: Date with formate
  @Date		: 13/04/2016
 */

function configDateTime($date) {
    $ci = & get_instance();
    $table = CONFIG . ' as con';
    $fields = array("con.value, con.config_key");
    $where = "con.config_key = 'date_format'";
    $dateInfo = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    //return date('m/d/Y', strtotime($date));
    if (!empty($date)) {
        return date($dateInfo[0]['value'], strtotime($date));
    } else {
        return date($dateInfo[0]['value']);
    }
}

/*
  @Author : Niral patel
  @Desc   : Set Datetime Formate as per Config Table
  @Input  : Date
  @Output : Datetime with formate
  @Date   : 11/05/2016
 */

function configDateTimeFormat($date) {
    $ci = & get_instance();
    $table = CONFIG . ' as con';
    $fields = array("con.value, con.config_key");
    $where = "con.config_key = 'datetime_format'";
    $dateInfo = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    //return date('m/d/Y', strtotime($date));
    if (!empty($date)) {
        return date($dateInfo[0]['value'], strtotime($date));
    } else {
        return date($dateInfo[0]['value']);
    }
}

/*
  @Author	: Ritesh
  @Desc		: Show Responsible Employee
  @Input 	: Employee ID
  @Output	: Date with formate
  @Date		: 14-04-2016
 */

function showResponsibleEmployee($campaign_auto_id) {
    $ci = & get_instance();
    $table = CAMPAIGN_RESPONSIBLE_EMPLOYEE_TRAN . ' as ct';
    $where = array("ct.status" => "1", "campaign_id" => $campaign_auto_id);
    $fields = array("cm.firstname,cm.lastname,cm.login_id,ct.campaign_id");
    $join_tables = array('blzdsk_login as cm' => 'cm.login_id = ct.user_id');
    $responsible_info_id = $ci->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

    $responsible_name = array();
    if (count($responsible_info_id) > 0) {
        foreach ($responsible_info_id as $responsible) {
            $responsible_name[] = $responsible['firstname'] . ' ' . $responsible['lastname'];
        }
    }
    $final = implode(',', $responsible_name);
    return $final;
}

function showResponsibleEmployeeRequestBudget($budget_campaign_id) {
    $ci = & get_instance();
    $table = BUDGET_CAMPAIGN_RESPONSIBLE_EMPLOYEE_TRAN . ' as ct';
    $where = array("ct.status" => "1", "budget_campaign_id" => $budget_campaign_id);
    $fields = array("cm.firstname,cm.lastname,cm.login_id,ct.budget_campaign_id");
    $join_tables = array('blzdsk_login as cm' => 'cm.login_id = ct.employee_id');
    $responsible_info_id = $ci->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

    $responsible_name = array();
    if (count($responsible_info_id) > 0) {
        foreach ($responsible_info_id as $responsible) {
            $responsible_name[] = $responsible['firstname'] . ' ' . $responsible['lastname'];
        }
    }
    $final = implode(',', $responsible_name);
    return $final;
}

function showProductNameRequestBudget($budget_campaign_id) {
    $ci = & get_instance();
    $table = BUDGET_CAMPAIGN_PRODUCT_TRAN . ' as ct';
    $where = array("budget_campaign_id" => $budget_campaign_id);
    $fields = array("pm.product_id,pm.product_name,ct.budget_campaign_id");
    $join_tables = array('blzdsk_product_master as pm' => 'pm.product_id = ct.product_id');
    $product_info_id = $ci->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

    $product_name = array();
    if (count($product_info_id) > 0) {
        foreach ($product_info_id as $product_info) {
            $product_name[] = $product_info['product_name'];
        }
    }
    $final = implode(',', $product_name);
    return $final;
}

/*
  @Author	: RJ(Rupesh Jorkar)
  @Desc		: Job Role Functionality get
  @Input 	: Array
  @Output	: Array
  @Date		: 12/04/2016
 */

function getJobRole($country_id) {
    $ci = & get_instance();
    $jobTitleArray = array();
    $table = TBL_CRM_JOB_TITLE . ' as jbt';
    $fields = array("jbt.job_title_name");
    $where = "jbt.is_delete = 0 AND jbt.status = 1";
    $jobRoleInfo = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    /* if(!empty($jobRoleInfo) && isset($jobRoleInfo))
      {
      $jobTitleArray = $currencyInfo[0]['job_title_name'].' ';
      } else {
      $jobTitleArray = "";
      }
      return $jobTitleArray; */
}

/*
  @Author	: RJ(Rupesh Jorkar)
  @Desc		: Function for get terms and condition
  @Input 	: Array
  @Output	: Array
  @Date		: 04/07/2016
 */

function getTermsAndCondition($country_id) {
    $ci = & get_instance();
    $table = ESTIMATE_SETTINGS . ' as EstSet';
    $fields = array("EstSet.estimate_settings_id, EstSet.name, EstSet.terms, EstSet.conditions, EstSet.status");
    $where = 'EstSet.status = 1 AND EstSet.is_delete = 0';
    $termConditionInfo = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    if (!empty($termConditionInfo) && isset($termConditionInfo)) {
        $termsAndCondtion = $termConditionInfo[0]['terms'] . ' ';
    } else {
        $termsAndCondtion = "";
    }
    return $termsAndCondtion;
}

/*
  @Author : RJ(Rupesh Jorkar)
  @Desc   : Function for Formate Date
  @Input 	: Date Formate
  @Output	: Date
  @Date   : 12/01/2016
 */

function datetimeformat($date = '') {
    if (!empty($date)) {
        return date("Y-m-d H:i:s", strtotime($date));
    } else {
        return date("Y-m-d H:i:s");
    }
}

/*
  @Author : RJ(Rupesh Jorkar)
  @Desc   : Get User Detail As per User ID
  @Input  : User ID
  @Output : User Information
  @Date   : 21/04/2016
 */

function getUserDetail($login_id) {
    $CI = & get_instance();
    $table = LOGIN . ' as lgn';
    $fields = array("lgn.firstname, lgn.lastname, lgn.email, lgn.address");
    $where = array('lgn.status' => '1', 'lgn.is_delete' => '0', 'lgn.login_id' => $login_id);
    $userArray = $CI->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $userArray;
}

/*
  @Author : RJ(Rupesh Jorkar)
  @Desc   : Get Random String as you want
  @Input  :
  @param  : type of random string.basic,alpha,alunum,numeric,nozero,unique,md5,encrypt and sha1
  @Output : string
  @Date   : 21/01/2016
 */

function random_string($type = 'alnum', $len = 8) {
    switch ($type) {
        case 'basic' : return mt_rand();
            break;
        case 'alnum' :
        case 'numeric' :
        case 'nozero' :
        case 'alpha' :

            switch ($type) {
                case 'alpha' : $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;
                case 'alnum' : $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;
                case 'numeric' : $pool = '0123456789';
                    break;
                case 'nozero' : $pool = '123456789';
                    break;
            }

            $str = '';
            for ($i = 0; $i < $len; $i++) {
                $str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
            }
            return $str;
            break;
        case 'unique' :
        case 'md5' :

            return md5(uniqid(mt_rand()));
            break;
        case 'encrypt' :
        case 'sha1' :

            $CI = & get_instance();
            $CI->load->helper('security');

            return do_hash(uniqid(mt_rand(), TRUE), 'sha1');
            break;
    }
}

/*
  @Author : Mehul Patel
  @Desc   : Get User Type from Role Master
  @Input  :
  @Output :
  @Date   : 21/01/2016
 */

function getUserType() {

    $ci = & get_instance();
    $table = ROLE_MASTER . ' as rm';
    $fields = array("rm.role_id, rm.role_name");
    $where = array('rm.status' => 1, 'rm.is_delete' => 0);
    // $where = array('rm.is_delete' => '0');
    $data['role_option'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

    return $data['role_option'];
}

/*
  @Author : Mehul Patel
  @Desc   : Get User Type from Role Master
  @Input  :
  @Output :
  @Date   : 21/01/2016
 */

function getUserTypeList() {

    $ci = & get_instance();
    $ci->db->select('*')->from(ROLE_MASTER);
    $ci->db->where('`role_id` NOT IN (SELECT `role_id` FROM `blzdsk_aauth_perm_to_group`)', NULL, FALSE);
    $ci->db->where('is_delete = 0');
    $query = $ci->db->get();
    $data['role_option'] = $query->result_array();
    return $data['role_option'];
}

/*
  @Author : Mehul Patel
  @Desc   : Get User Type from Role Master
  @Input  :
  @Output :
  @Date   : 21/01/2016
 */

function getUserTypeAssign() {

    $ci = & get_instance();
    $ci->db->select('*')->from(ROLE_MASTER);
    $ci->db->where('`role_id` NOT IN (SELECT `role_id` FROM `blzdsk_aauth_perm_to_group`)', NULL, FALSE);
    $ci->db->where('is_delete = 0');
    $query = $ci->db->get();
    $data['role_option'] = $query->result_array();
    return $data['role_option'];
}

/*
  @Author : Mehul Patel
  @Desc   :  Create Dropdown
  @Input 	:  $name ,array $options,$selected
  @Output	:  Dropdown create
  @Date   : 20/01/2016
 */

function dropdown($name, array $options, $selected = null, $readonly = null, $first_option = null, $second_option = null) {
    //pr($first_option);die();
    /*     * * begin the select ** */
    $dropdown = '<select class="form-control" name="' . $name . '" id="' . $name . '" ' . $readonly . '>' . "\n";

    $selected = $selected;
    /*     * * loop over the options ** */
    if ($first_option != '') {
        $dropdown .= '<option value="">' . $first_option . '</option>' . "\n";
    }
    if ($second_option != '') {
        $select = $selected == '0' ? ' selected' : null;
        $dropdown .= '<option value="0" ' . $select . '>' . $second_option . '</option>' . "\n";
    }
    foreach ($options as $key => $option) {
        /*         * * assign a selected value ** */
        $select = $selected == $key ? ' selected' : null;

        /*         * * add each option to the dropdown ** */

        $dropdown .= '<option value="' . $key . '"' . $select . '>' . $option . '</option>' . "\n";
    }

    /*     * * close the select ** */
    $dropdown .= '</select>' . "\n";

    /*     * * and return the completed dropdown ** */
    return $dropdown;
}

/*
  @Author : Niral Patel
  @Desc   :  Create lang function for get lang line
  @Input 	:  $line
  @Output	:  Display line
  @Date   : 27/01/2016
 */
if (!function_exists('lang')) {

    function lang($line, $id = '') {
        $CI = & get_instance();
        $line = $CI->lang->line($line);

        if ($id != '') {
            $line = '<label for="' . $id . '">' . $line . "</label>";
        }

        return $line;
    }

}
/*
  @Author : Niral Patel
  @Desc   : Check project session
  @Input  :
  @Output :If session expaire than redirect to dashboard
  @Date   : 27/01/2016
 */

function check_project() {
    $CI = & get_instance();  //get instance, access the CI superobject
    $PROJECT_ID = $CI->session->userdata('PROJECT_ID');
    (!empty($PROJECT_ID)) ? '' : redirect('Projectmanagement/Projectdashboard');
    $match = "pt.project_id = " . $PROJECT_ID . ' and is_delete=0';
    $table = PROJECT_MASTER . ' as pt';
    $CI->load->model('common_model');
    $projectData = $CI->common_model->get_records($table, '', '', '', $match);
    if (count($projectData) > 0) {
        
    } else {
        $msg = $CI->lang->line('project_not_found');
        if ($CI->session->has_userdata('PROJECT_ID')):
            $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>You are trying to access deleted or inactive.Project please contact your Project Manager!</div>");
        endif;
        $CI->session->unset_userdata('PROJECT_ID');
        echo "<script>window.location.href='" . base_url('Projectmanagement/Projectdashboard') . "';</script>";
        //        redirect('Projectmanagement/Projectdashboard', 'refresh');

        die;
    }
}

/*
  @Author : Mehul Patel
  @Desc   : Get permission list from aauth_perms
  @Input  :
  @Output :
  @Date   : 26/01/2016
 */

function getPermsList() {

    $ci = & get_instance();
    $table = AAUTH_PERMS . ' as ap';
    $match = "";
    $fields = array("ap.id, ap.name");
    $data['permsList'] = $ci->common_model->get_records($table, $fields);
    return $data['permsList'];
}

/*
  @Author : Mehul Patel
  @Desc   : Get Module list from Module Master
  @Input  :
  @Output :
  @Date   : 26/01/2016
 */

function getModuleList() {

    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $match = "";
    $fields = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name, mm.status");
    $where = array('mm.status' => '1');
    $data['moduleList'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $data['moduleList'];
}

/*
  @Author : Mehul Patel
  @Desc   : Get Module list from Module Master
  @Input  :
  @Output :
  @Date   : 26/01/2016
 */

function getPMModuleList() {
    $moduleComponent = "PM";
    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $match = "";
    $fields = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name, mm.status");
    $where = array('mm.status' => '1', 'mm.component_name' => '"' . $moduleComponent . '"');
    $data['moduleList'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $data['moduleList'];
}

/*
  @Author : Mehul Patel
  @Desc   : Get Module list from Module Master
  @Input  :
  @Output :
  @Date   : 26/01/2016
 */

function getCRMModuleList() {
    $moduleComponent = "CRM";
    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $match = "";
    $fields = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name, mm.status");
    $where = array('mm.status' => '1', 'mm.component_name' => '"' . $moduleComponent . '"');
    $data['moduleList'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $data['moduleList'];
}

/*
  @Author : Mehul Patel
  @Desc   : Get Module list from Module Master
  @Input  :
  @Output :
  @Date   : 26/01/2016
 */

function getFinanceModuleList() {
    $moduleComponent = "Finance";
    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $match = "";
    $fields = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name, mm.status");
    $where = array('mm.status' => '1', 'mm.component_name' => '"' . $moduleComponent . '"');
    $data['moduleList'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $data['moduleList'];
}

/*
  @Author : Mehul Patel
  @Desc   : Get Module list from Module Master
  @Input  :
  @Output :
  @Date   : 26/01/2016
 */

function getSupportModuleList() {
    $moduleComponent = "Support";
    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $match = "";
    $fields = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name, mm.status");
    $where = array('mm.status' => '1', 'mm.component_name' => '"' . $moduleComponent . '"');
    $data['moduleList'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $data['moduleList'];
}

/*
  @Author : Mehul Patel
  @Desc   : Get Module list from Module Master
  @Input  :
  @Output :
  @Date   : 26/01/2016
 */

function getHRModuleList() {
    $moduleComponent = "HR";
    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $match = "";
    $fields = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name, mm.status");
    $where = array('mm.status' => '1', 'mm.component_name' => '"' . $moduleComponent . '"');
    $data['moduleList'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $data['moduleList'];
}

/*
  @Author : Mehul Patel
  @Desc   : Get Module list from Module Master
  @Input  :
  @Output :
  @Date   : 26/01/2016
 */

function getUserModuleList() {
    $moduleComponent = "User";
    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $match = "";
    $fields = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name, mm.status");
    $where = array('mm.status' => '1', 'mm.component_name' => '"' . $moduleComponent . '"');
    $data['moduleList'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $data['moduleList'];
}

/*
  @Author : Mehul Patel
  @Desc   : Get Module list from Module Master
  @Input  :
  @Output :
  @Date   : 26/01/2016
 */

function getsettingsModuleList() {
    $moduleComponent = "settings";
    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $match = "";
    $fields = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name, mm.status");
    $where = array('mm.status' => '1', 'mm.component_name' => '"' . $moduleComponent . '"');
    $data['moduleList'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $data['moduleList'];
}

/*
  @Author : Mehul Patel
  @Desc   :  Create Dropdown
  @Input 	:  $name ,array $options,$selected
  @Output	:  Dropdown create
  @Date   : 20/01/2016
 */

function perms_dropdown($name, array $options, $selected = null) {
    /*     * * begin the select ** */
    $dropdown = '<select class="form-control" name="' . $name . '" multiple="multiple" id="' . $name . '" required >' . "\n";

    $selected = $selected;
    /*     * * loop over the options ** */
    foreach ($options as $key => $option) {
        /*         * * assign a selected value ** */
        $select = $selected == $key ? ' selected' : null;

        /*         * * add each option to the dropdown ** */
        $dropdown .= '<option value="' . $key . '"' . $select . '>' . $option . '</option>' . "\n";
    }

    /*     * * close the select ** */
    $dropdown .= '</select>' . "\n";

    /*     * * and return the completed dropdown ** */
    return $dropdown;
}

/*
  @Author : Mehul Patel
  @Desc   : Get Module Status from Module Master
  @Input  :
  @Output :
  @Date   : 21/01/2016
 */

function getModuleStatus() {

    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $fields = array("mm.status");
    $data['module_option'] = $ci->common_model->get_records($table, $fields);

    return $data['module_option'];
}

/*
  @Author : Mehul Patel
  @Desc   : Helperfunction for checkpermission
  @Input  : action name
  @Output : if has permission then return true else false
  @Date   : 02/02/2016
 */

function checkPermission($controller, $method) {
    $CI = & get_instance();

    $system_lang = $CI->common_model->get_lang();
    $CI->config->set_item('language', $system_lang);
    $CI->lang->load('label', $system_lang ? $system_lang : 'english');

    //$CI->loginpage_redirect();  //Function added by RJ for redirection

    if (!isset($CI->router)) { # Router is not loaded
        $CI->load->library('router');
    }
    if (!isset($CI->session)) { # Sessions are not loaded
        $CI->load->library('session');
        $CI->load->library('database');
    }
    $dbPermArray = $resultData = $permArrMaster = $validateArr = array();
    $flag = 0;
    //$class = $CI->router->fetch_class();
    $class = $controller;
    // $method = $CI->router->fetch_method();
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
        $newArr = array();
        $permsArray = $CI->config->item($class);

        if (count($resultData) > 0) {
            $dbPermArray = array_map(function ($obj) {
                return $obj['name'];
            }, $resultData);

            foreach ($dbPermArray as $prmObj) {
                if (array_key_exists($prmObj, $permsArray)) {
                    $permArrMaster[$prmObj] = $permsArray[$prmObj];
                }
            }
            if (array_key_exists($method, $permArrMaster)) {
                /*
                 * custom code for validating project status condition whether project is completed or not
                 */
                if ($resultData[0]['component_name'] == 'PM' && $method != 'view' && $class != 'Projectmanagement') {

                    if ($CI->session->has_userdata('PROJECT_STATUS') && $CI->session->userdata('PROJECT_STATUS') == 3) {
                        return false;
                    }
                }
                return true;
            } else {
                return false;
            }
        }
    }
    /*
      @Author : Maulik Suthar
      @Desc   : Common Upload Function
      @Input 	:
      @Output	:
      @Date   : 29/01/2016
     */
}

function uploadImage($input, $path, $redirect, $file_name = null, $file_ext_tolower = false, $encrypt_name = false, $remove_spaces = false, $detect_mime = true) {
    $CI = & get_instance();
    $files = $_FILES;
    $FileDataArr = array();
    $config['upload_path'] = $path;
    $config['allowed_types'] = '*';
    $config['max_size'] = 204800;
//        $config['max_width'] = 1024;
//        $config['max_height'] = 768;
    $config['file_ext_tolower'] = $file_ext_tolower;
    $config['encrypt_name'] = $encrypt_name;
    $config['remove_spaces'] = $remove_spaces;
    $config['detect_mime'] = $detect_mime;
    if ($file_name != null) {
        $config['file_name'] = $file_name;
    }
    $tmpFile = count($_FILES[$input]['name']);

    if ($tmpFile > 0 && $_FILES[$input]['name'][0] != NULL) {
        for ($i = 0; $i < $tmpFile; $i++) {
            $_FILES[$input]['name'] = $files[$input]['name'][$i];
            $_FILES[$input]['type'] = $files[$input]['type'][$i];
            $_FILES[$input]['tmp_name'] = $files[$input]['tmp_name'][$i];
            $_FILES[$input]['error'] = $files[$input]['error'][$i];
            $_FILES[$input]['size'] = $files[$input]['size'][$i];

            $content = file_get_contents($_FILES[$input]['tmp_name']);
            if (preg_match('/\<\?php/i', $content)) {
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect($redirect);
                die;
            }
            $CI->load->library('upload', $config);
            if ($CI->upload->do_upload($input)) {
                $FileDataArr[] = $CI->upload->data();
            } else {
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . $CI->upload->display_errors() . "</div>");
                redirect($redirect);
                die;
            }
        }
    }
    return $FileDataArr;
}

/*
  @Author : Mehul Patel
  @Desc   : Get Email settings
  @Input  :
  @Output :
  @Date   : 11/02/2016
 */

function getMailConfig() {

    $CI = & get_instance();
    $dashWhere = "config_key='email_settings'";
    $defaultDashboard = $CI->common_model->get_records(CONFIG, array('value'), '', '', $dashWhere);
    $configData = (array) json_decode($defaultDashboard[0]['value']);
    return $configData;
}

/*
  @Author : Mehul Patel
  @Desc   : Send mail with CI Helper
  @Input  :
  @Output :
  @Date   : 11/02/2016
 */

function send_mail($to, $subject, $message, $attach = NULL) {

    $CI = & get_instance();

    $configs = getMailConfig(); // Get Email configs from Email settigs page
    //$CI->load->library('parser');
    if (!empty($configs)) {
        $config['protocol'] = $configs['email_protocol'];
        $config['smtp_host'] = $configs['smtp_host']; //change this
        $config['smtp_port'] = $configs['smtp_port'];
        $config['smtp_user'] = $configs['smtp_user']; //change this
        $config['smtp_pass'] = $configs['smtp_pass']; //change this
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8
        $CI->load->library('email', $config); // load email library
        $CI->email->from($configs['smtp_user'], "CMS TEST");
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if (isset($attach) && $attach != "") {
            $CI->email->attach($attach); // attach file /path/to/file1.png
        }

        return $CI->email->send();
    } else {

        $where = "config_key='email'";
        $fromEmail = $CI->common_model->get_records(CONFIG, array('value'), '', '', $where);
        if (isset($fromEmail[0]['value']) && !empty($fromEmail[0]['value'])) {
            $from_Email = $fromEmail[0]['value'];
        }
        $where1 = "config_key='project_name'";
        $projectName = $CI->common_model->get_records(CONFIG, array('value'), '', '', $where1);
        if (isset($projectName[0]['value']) && !empty($projectName[0]['value'])) {
            $project_Name = $projectName[0]['value'];
        }
        $CI->load->library('email');
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $CI->email->initialize($config);
        $config['mailtype'] = "html";
        $CI->email->initialize($config);
        $CI->email->set_newline("\r\n");
        $CI->email->from($from_Email, $project_Name);
        //$list = array('mehul.patel@c-metric.com');
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if (isset($attach) && $attach != "") {
            $CI->email->attach($attach); // attach file /path/to/file1.png
        }
        return $CI->email->send();
    }


    // pr($data); exit;
}

/*
  @Author : Niral Patel
  @Desc   : Send mail with CI Helper
  @Input  : $attach = array(),
  $headers = array(),
  $configs_arr = array(),
  other inputs are strings
  @Output :
  @Date   : 4/05/2016
 */

function send_mail1($to, $subject, $message, $attach = '', $from_email = '', $from_name = '', $cc = '', $bcc = '', $headers = '', $configs_arr = '') {

    $CI = & get_instance();

    $configs = getMailConfig(); // Get Email configs from Email settigs page
    //$CI->load->library('parser');
    if (!empty($configs)) {
        $config['protocol'] = $configs['email_protocol'];
        $config['smtp_host'] = $configs['smtp_host']; //change this
        $config['smtp_port'] = $configs['smtp_port'];
        $config['smtp_user'] = $configs['smtp_user']; //change this
        $config['smtp_pass'] = $configs['smtp_pass']; //change this
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8
        //Add other config
        if (!empty($configs_arr)) {
            foreach ($configs_arr as $key => $value) {
                $config[$key] = $value;
            }
        }
        $CI->load->library('email', $config); // load email library
        //Add header
        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                $CI->email->set_header($key, $value);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if (!empty($from_email)) {
            $from_name = !empty($from_name) ? $from_name : 'CMS TEST';
            $CI->email->from($from_email, $from_name);
        } else {
            $CI->email->from($configs['smtp_user'], "CMS TEST");
        }
        $CI->email->to($to);
        if (!empty($cc)) {
            $CI->email->cc($cc);
        }
        if (!empty($bcc)) {
            $CI->email->bcc($bcc);
        }


        if (!empty($attach)) {
            foreach ($attach as $row_attachment) {
                $CI->email->attach($row_attachment);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }

        return $CI->email->send();
    } else {

        $where = "config_key='email'";
        $fromEmail = $CI->common_model->get_records(CONFIG, array('value'), '', '', $where);
        if (isset($fromEmail[0]['value']) && !empty($fromEmail[0]['value'])) {
            $from_email_conf = $fromEmail[0]['value'];
        }
        $where1 = "config_key='project_name'";
        $projectName = $CI->common_model->get_records(CONFIG, array('value'), '', '', $where1);
        if (isset($projectName[0]['value']) && !empty($projectName[0]['value'])) {
            $project_Name = $projectName[0]['value'];
        }
        $CI->load->library('email');
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = "html";
        //Add other config
        if (!empty($configs_arr)) {
            foreach ($configs_arr as $key => $value) {
                $config[$key] = $value;
            }
        }
        //$CI->load->library('email', $config); // load email library
        $CI->email->initialize($config);
        //Add header
        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                $CI->email->set_header($key, $value);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }
        $CI->email->set_newline("\r\n");

        //$list = array('mehul.patel@c-metric.com');
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if (!empty($from_email)) {
            $from_name = !empty($from_name) ? $from_name : 'CMS TEST';
            $CI->email->from($from_email, $from_name);
        } else {
            $CI->email->from($from_email_conf, $project_Name);
        }
        $CI->email->to($to);
        if (!empty($cc)) {
            $CI->email->cc($cc);
        }
        if (!empty($bcc)) {
            $CI->email->bcc($bcc);
        }


        if (!empty($attach)) {
            foreach ($attach as $row_attachment) {
                $CI->email->attach($row_attachment);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }
        return $CI->email->send();
//    if($CI->email->send())
//    { 
//      return true;
//    }
//    else
//    {
//      return false;
//    }
//    
    }


    // pr($data); exit;
}

/*
  @Author : Suthar Maulik
  @Desc   :Generates Token on Form
  @Input  :
  @Output :
  @Date   : 22/02/2016
 */

function send_mail_imap($to, $subject, $message, $attach = '', $from_email = '', $from_name = '', $cc = '', $bcc = '', $headers = '', $configs_arr = '') {
    $CI = & get_instance();

    $configs = getMailConfig(); // Get Email configs from Email settigs page
    //$CI->load->library('parser');
    //sends email from client config 
    if (!empty($configs_arr)) {
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8
        //Add other config
        if (!empty($configs_arr)) {
            foreach ($configs_arr as $key => $value) {
                $config[$key] = $value;
            }
        }
        //   print_r($configs_arr);
        $CI->load->library('email', $config); // load email library
        //Add header
        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                $CI->email->set_header($key, $value);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);

        $from_name = !empty($from_name) ? $from_name : $from_email;

        $CI->email->from($from_email, $from_name);

        $CI->email->to($to);
        if (!empty($cc)) {
            $CI->email->cc($cc);
        }
        if (!empty($bcc)) {
            $CI->email->bcc($bcc);
        }


        if (!empty($attach)) {
            foreach ($attach as $row_attachment) {
                $CI->email->attach($row_attachment);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }

        if ($CI->email->send()) {
            return true;
        } else {
          
            //sends email from system config 
            if (!empty($configs)) {
                $config['protocol'] = $configs['email_protocol'];
                $config['smtp_host'] = $configs['smtp_host']; //change this
                $config['smtp_port'] = $configs['smtp_port'];
                $config['smtp_user'] = $configs['smtp_user']; //change this
                $config['smtp_pass'] = $configs['smtp_pass']; //change this
                $config['mailtype'] = 'html';
                $config['charset'] = 'iso-8859-1';
                $config['wordwrap'] = TRUE;
                $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8
                //Add other config
                if (!empty($configs_arr)) {
                    foreach ($configs_arr as $key => $value) {
                        $config[$key] = $value;
                    }
                }
                $CI->load->library('email', $config); // load email library
                //Add header
                if (!empty($headers)) {
                    foreach ($headers as $key => $value) {
                        $CI->email->set_header($key, $value);
                    }
                    //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
                }
                $CI->email->to($to);
                $CI->email->subject($subject);
                $CI->email->message($message);
                if (!empty($from_email)) {
                    $from_name = !empty($from_name) ? $from_name : 'CMS TEST';
                    $CI->email->from($from_email, $from_name);
                } else {
                    $CI->email->from($configs['smtp_user'], "CMS TEST");
                }
                $CI->email->to($to);
                if (!empty($cc)) {
                    $CI->email->cc($cc);
                }
                if (!empty($bcc)) {
                    $CI->email->bcc($bcc);
                }


                if (!empty($attach)) {
                    foreach ($attach as $row_attachment) {
                        $CI->email->attach($row_attachment);
                    }
                    //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
                }

                return $CI->email->send();
            }
        }
    } else {
        //sends email from system default 
        $CI->load->library('email');
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = "html";
        //Add other config
        if (!empty($configs_arr)) {
            foreach ($configs_arr as $key => $value) {
                $config[$key] = $value;
            }
        }
        //$CI->load->library('email', $config); // load email library
        $CI->email->initialize($config);
        //Add header
        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                $CI->email->set_header($key, $value);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }
        $CI->email->set_newline("\r\n");
        //$list = array('mehul.patel@c-metric.com');
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        $from_name = !empty($from_name) ? $from_name : $from_email;
        $CI->email->from($from_email, $from_name);
        $CI->email->to($to);
        if (!empty($cc)) {
            $CI->email->cc($cc);
        }
        if (!empty($bcc)) {
            $CI->email->bcc($bcc);
        }

        if (!empty($attach)) {
            foreach ($attach as $row_attachment) {
                $CI->email->attach($row_attachment);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }
        return $CI->email->send();
    }
}

/*
  @Author : Suthar Maulik
  @Desc   :Generates Token on Form
  @Input  :
  @Output :
  @Date   : 22/02/2016
 */

function createFormToken() {
    $CI = & get_instance();
    $CI->load->library('session'); // load Session library
    $secret = md5(uniqid(rand(), true));
    $CI->session->set_userdata('FORM_SECRET', $secret);
    return $secret;
}


function generateFormToken() {
    $CI = & get_instance();
    $CI->load->library('session'); // load Session library
    $secret = md5(uniqid(rand(), true));
    $CI->session->set_userdata('FORM_SECRET_DATA', $secret);
    return $secret;
}
/*
  @Author : Suthar Maulik
  @Desc   :validates Token on Form
  @Input  :
  @Output :
  @Date   : 22/02/2016
 */

function validateFormSecret() {
    $CI = & get_instance();
    $CI->load->library('session'); // load Session library
    $frmSession = $CI->session->userdata('FORM_SECRET');
    $form_secret = isset($_POST["form_secret"]) ? $_POST["form_secret"] : '';

    if (isset($frmSession)) {
        if (strcasecmp($form_secret, $frmSession) === 0) {
            /* Put your form submission code here after processing the form data, unset the secret key from the session */
            $CI->session->unset_userdata('FORM_SECRET', '');
            return true;
        } else {
            //Invalid secret key
            return false;
        }
    } else {
        //Secret key missing
        return false;
    }
}


function validateFormSecretsData() {
    $CI = & get_instance();
    $CI->load->library('session'); // load Session library
    $frmSession = $CI->session->userdata('FORM_SECRET');
    $form_secret = isset($_POST["form_secret"]) ? $_POST["form_secret"] : '';

    if (isset($frmSession)) {
        if (strcasecmp($form_secret, $frmSession) === 0) {
            /* Put your form submission code here after processing the form data, unset the secret key from the session */
            $CI->session->unset_userdata('FORM_SECRET', '');
            return true;
        } else {
            //Invalid secret key
            return false;
        }
    } else {
        //Secret key missing
        return false;
    }
}

/*
  @Author : Sanket jayani
  @Desc   :calculate_day_left
  @Input  :
  @Output :
  @Date   : 22/02/2016
 */

function calculate_day_left($input_date) {
    $now = time(); // or your date as well
    $your_date = strtotime($input_date);
    $datediff = $now - $your_date;
    $calculate_days = floor($datediff / (60 * 60 * 24));

    if ($calculate_days == 0) {
        return "<span class='tody-task-clr bd-event btn btn-blue width-100'>" . lang('TODAY') . "</span>";
    } else if ($calculate_days < -1) {
        return "<span class='bd-event-day bd-event btn btn-blue width-100'>" . abs($calculate_days) . "&nbsp " . lang('DAY_LEFT') . "</span>";
    } else if ($calculate_days > 0) {
        return "<span class='bd-event-today bd-event btn btn-blue width-100'>" . lang('EXPIRED') . "</span>";
    } else {
        return "<span class='bd-event-tmrw bd-event btn btn-blue width-100'>" . lang('TOMORROW') . "</span>";
    }
}

function con_min_days($mins) {
    $d = floor($mins / 1440);
    $h = floor(($mins - $d * 1440) / 60);
    $m = $mins - ($d * 1440) - ($h * 60);

    if (isset($d) && $d > 0) {
        return $d = $d . "/Day[s] ";
    }

    if (isset($h) && $h > 0) {
        return $h = $h . "/Hour[s] ";
    }
    if (isset($m)) {
        return $m = $m . "/Min[s]";
    }


//        $hours = str_pad(floor($mins /60),2,"0",STR_PAD_LEFT);
//        $mins  = str_pad($mins %60,2,"0",STR_PAD_LEFT);
//
//        if((int)$hours > 24){
//        $days = str_pad(floor($hours /24),2,"0",STR_PAD_LEFT);
//        $hours = str_pad($hours %24,2,"0",STR_PAD_LEFT);
//        }
//        if(isset($days)) {
//          return  $days = $days."/Day[s] ";
//        }
//
//        if(isset($mins)) {
//           return  $mins = $mins."/Min[s] ";
//        }
//
//        //return $days;
}

/*
  @Author : Sanket jayani
  @Desc   :for getting document image name
  @Input  :
  @Output :
  @Date   : 22/02/2016
 */

function getImgFromFileExtension($file_extension) {
    if ($file_extension == '') {
        $file_extension = 'txt';
    }

    $document_array = array('jpg' => 'jpg-64.png', 'csv' => 'xls-64.png',
        'aac' => 'aac-64.png', 'aib' => 'aib-64.png',
        'avi' => 'avi-64.png', 'docx' => 'docs-64.png',
        'flac' => 'flac-64.png', 'gif' => 'gif-64.png',
        'html' => 'html-64.png', 'js' => 'js-64.png',
        'movs' => 'movs-64.png', 'mp4' => 'mp3-64.png',
        'mp4' => 'mp4-64.png', 'pdf' => 'pdf-64.png', 'default' => 'file-64.png',
        'png' => 'png-64.png', 'psd' => 'psd-64.png',
        'txt' => 'txt-64.png', 'xlsx' => 'xlsx-64.png', 'xls' => 'xls-64.png', 'ppt' => 'ppt-64.png', 'pptx' => 'pptx-64.png');

    if (array_key_exists(strtolower($file_extension), $document_array)) {
        return $document_array[strtolower($file_extension)];
    } else {

        return $document_array['default'];
    }
}

function helperConvertCurrency($amount, $from, $to) {
    $CI = & get_instance();
    $getConvertedFromAmount = "";
    $getConvertedToAmount = "";
    // Convert into Indian currency
    $table = COUNTRIES . ' as c';
    $match = "c.currency_code='" . $from . "'";
    $fields = array("c.currency_amount");
    $getFromAmount = $CI->common_model->get_records($table, $fields, '', '', $match);

    if (!empty($getFromAmount[0]['currency_amount'])) {
        $getConvertedFromAmount = $amount / $getFromAmount[0]['currency_amount'];
    }

    // Convert into requested currency
    $table1 = COUNTRIES . ' as c';
    $match1 = "c.currency_code='" . $to . "'";
    $fields1 = array("c.currency_amount");
    $getToAmount = $CI->common_model->get_records($table1, $fields1, '', '', $match1);

    if (!empty($getToAmount[0]['currency_amount'])) {

        $getConvertedToAmount = $getConvertedFromAmount * $getToAmount[0]['currency_amount'];
    }

    return $getConvertedToAmount;
}

/*
  function helperConvertCurrency($amount, $from, $to)
  {
  $url = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
  $data = file_get_contents($url);
  //print_r($data);exit;
  preg_match("/<span class=bld>(.*)<\/span>/", $data, $converted);
  if(count($converted)>0){
  $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
  }
  //echo $converted; exit;
  return round($converted, 3);
  }
 */

function getCourrencyCode($country_id) {

    $CI = & get_instance();
    $table = COUNTRIES . ' as c';
    $match = "c.is_delete_currency=0 AND  country_id = " . $country_id;
    $fields = array("c.currency_code");
    $data['currency_code'] = $CI->common_model->get_records($table, $fields, '', '', $match);
    return $data['currency_code'];
}

function getCountryName($country_id) {

    $CI = & get_instance();
    $table = COUNTRIES . ' as c';
    $match = "country_id = " . $country_id;
    $fields = array("c.country_name");
    $countryName = $CI->common_model->get_records($table, $fields, '', '', $match);
    return $countryName;
}

function getRoleName($role_id) {

	
	//echo "ROLE ID :".$role_id;
    $CI = & get_instance();
    $table = ROLE_MASTER . ' as rm';
    $match = "rm.role_id = " . $role_id;
    $fields = array("rm.role_name");
    $roleName = $CI->common_model->get_records($table, $fields, '', '', $match);   
    //print_r($roleName);
    return $roleName;
}

// Get List of Purchased Module details
function getListOfPurchasedModule() {

    $CI = & get_instance();
    $main_user_id = $CI->config->item('master_user_id');
    // Added by Ritesh Rana
    $table_user = LOGIN . ' as lg';
    $where_user = array("lg.login_id" => $main_user_id, "lg.is_delete" => 0);
    $fields_user = array("lg.login_id,lg.email,lg.parent_id");
    $check_user_data = $CI->common_model->get_records($table_user, $fields_user, '', '', '', '', '', '', '', '', '', $where_user);

    // Added by Ritesh Rana
    $table = SETUP_MASTER . ' as ct';
    //$where = array("ct.login_id" => $main_user_id);
    $where = "ct.login_id = " . $main_user_id . " AND ct.email = '" . $check_user_data[0]['email'] . "'";
    $fields = array("ct.total_user,ct.login_id,ct.is_crm,ct.is_pm,ct.is_support,ct.support_user,ct.crm_user,ct.pm_user");
    $check_setup_user = $CI->common_model->get_records_data($table, $fields, '', '', '', '', '', '', '', '', '', $where);

    return $check_setup_user;
}

function checkPurchasedUserLimitPerModule() {
    $CI = & get_instance();
    $main_user_id = $CI->config->item('master_user_id');
    $table = SETUP_MASTER . ' as ct';
    $where = "ct.login_id = " . $main_user_id;
    $fields = array("ct.total_user,ct.login_id,ct.is_crm,ct.is_pm,ct.is_support,ct.support_user,ct.crm_user,ct.pm_user");
    $checkPurchasedUserLimitPerModule = $CI->common_model->get_records_data($table, $fields, '', '', '', '', '', '', '', '', '', $where);

    return $checkPurchasedUserLimitPerModule;
}

function getSelectedModule($id) {
    $CI = & get_instance();
    $table3 = LOGIN . ' as l';
    $where3 = array("l.login_id " => $id);
    $fields3 = array("l.is_crm_user,l.is_pm_user,l.is_support_user,l.user_type");
    $getCountofSupportuser1 = $CI->common_model->get_records($table3, $fields3, '', '', '', '', '', '', '', '', '', $where3);
    return $getCountofSupportuser1;
}

// Added by Ritesh Rana
function checkPurchasedUserModuleForPM() {
    //echo "test";exit;
    $CI = & get_instance();
    $master_user_id = $CI->config->item('master_user_id');
    //$master_user_id = $data['user_info']['ID'];
    $table_user = LOGIN . ' as lg';
    $where_user = array("lg.login_id" => $master_user_id);
    $fields_user = array("lg.*");
    $check_user_data = $CI->common_model->get_records($table_user, $fields_user, '', '', '', '', '', '', '', '', '', $where_user);
    //pr($check_user_data);exit;
    $table = SETUP_MASTER . ' as ct';
    //$where = array("ct.login_id" => $main_user_id);
    $where = "ct.login_id = " . $master_user_id . " AND ct.email = '" . $check_user_data[0]['email'] . "'";
    $fields = array("ct.*");
    $check_user_menu = $CI->common_model->get_records_data($table, $fields, '', '', '', '', '', '', '', '', '', $where);

    //pr($check_user_menu);exit;
    if (isset($check_user_menu[0]['is_pm']) && $check_user_menu[0]['is_pm'] == 0) {
        if (isset($check_user_menu[0]['is_crm']) && $check_user_menu[0]['is_crm'] == 1) {
            if ($_SERVER["HTTP_REFERER"] != base_url('Masteradmin')) {
                $msg = $CI->lang->line('DONT_HAVE_PAGE_PERMISSION');
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
            redirect(base_url()); //Redirect on Dashboard
        } elseif (isset($check_user_menu[0]['is_support']) && $check_user_menu[0]['is_support'] == 1) {
            $msg = $CI->lang->line('DONT_HAVE_PAGE_PERMISSION');
            $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(base_url('Support')); //Redirect on Dashboard
        }
    }
    $user_login_id = $CI->session->userdata('LOGGED_IN')['ID'];
    $table_user_check = LOGIN . ' as lg';
    $where_user_check = array("lg.login_id" => $user_login_id);
    $fields_user_check = array("lg.*");
    $check_user = $CI->common_model->get_records($table_user_check, $fields_user_check, '', '', '', '', '', '', '', '', '', $where_user_check);
//pr($check_user);exit;
    if (isset($check_user[0]['is_pm_user']) && $check_user[0]['is_pm_user'] == 0) {
        if (isset($check_user[0]['is_crm_user']) && $check_user[0]['is_crm_user'] == 1) {
            if ($_SERVER["HTTP_REFERER"] != base_url('Masteradmin')) {
                $msg = $CI->lang->line('DONT_HAVE_PAGE_PERMISSION');
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
            redirect(base_url()); //Redirect on Dashboard
        } elseif (isset($check_user[0]['is_support_user']) && $check_user[0]['is_support_user'] == 1) {
            if ($_SERVER["HTTP_REFERER"] != base_url('Masteradmin')) {
                $msg = $CI->lang->line('DONT_HAVE_PAGE_PERMISSION');
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
            redirect(base_url('Support')); //Redirect on Dashboard
        }
    }
}

// Added by Ritesh Rana
function checkPurchasedUserModuleForCRM() {
    $CI = & get_instance();
    $master_user_id = $CI->config->item('master_user_id');
    $user_login_id = $CI->session->userdata('LOGGED_IN')['ID'];
    $table_user = LOGIN . ' as lg';
    $where_user = array("lg.login_id" => $master_user_id);
    $fields_user = array("lg.*");
    $check_user_data = $CI->common_model->get_records($table_user, $fields_user, '', '', '', '', '', '', '', '', '', $where_user);

    $table = SETUP_MASTER . ' as ct';
    //$where_setup_data = array("ct.login_id" => $master_user_id);
    $where_setup_data = "ct.login_id = " . $master_user_id . " AND ct.email = '" . $check_user_data[0]['email'] . "'";
    $fields = array("ct.*");
    $check_user_menu = $CI->common_model->get_records_data($table, $fields, '', '', '', '', '', '', '', '', '', $where_setup_data);
    //pr($check_user_menu);exit;
    if (isset($check_user_menu[0]['is_crm']) && $check_user_menu[0]['is_crm'] == 0) {
        if (isset($check_user_menu[0]['is_pm']) && $check_user_menu[0]['is_pm'] == 1) {
            if ($_SERVER["HTTP_REFERER"] != base_url('Masteradmin')) {
                $msg = $CI->lang->line('DONT_HAVE_PAGE_PERMISSION');
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
            redirect(base_url('Projectmanagement/Projectdashboard')); //Redirect on Dashboard
        } elseif (isset($check_user_menu[0]['is_support']) && $check_user_menu[0]['is_support'] == 1) {
            if ($_SERVER["HTTP_REFERER"] != base_url('Masteradmin')) {
                $msg = $CI->lang->line('DONT_HAVE_PAGE_PERMISSION');
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
            redirect(base_url('Support')); //Redirect on Dashboard
        }
    }
    $table_user = LOGIN . ' as lg';
    $where_user = array("lg.login_id" => $user_login_id);
    $fields_user = array("lg.*");
    $user_data = $CI->common_model->get_records($table_user, $fields_user, '', '', '', '', '', '', '', '', '', $where_user);

    if (isset($user_data[0]['is_crm_user']) && $user_data[0]['is_crm_user'] == 0) {
        if (isset($user_data[0]['is_pm_user']) && $user_data[0]['is_pm_user'] == 1) {
            if ($_SERVER["HTTP_REFERER"] != base_url('Masteradmin')) {
                $msg = $CI->lang->line('DONT_HAVE_PAGE_PERMISSION');
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
            redirect(base_url('Projectmanagement/Projectdashboard')); //Redirect on Dashboard
        } elseif (isset($user_data[0]['is_support_user']) && $user_data[0]['is_support_user'] == 1) {
            if ($_SERVER["HTTP_REFERER"] != base_url('Masteradmin')) {
                $msg = $CI->lang->line('DONT_HAVE_PAGE_PERMISSION');
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
            redirect(base_url('Support')); //Redirect on Dashboard
        }
    }
}

// Added by Ritesh Rana
function checkPurchasedUserModuleForSupport() {
    $CI = & get_instance();
    $master_user_id = $CI->config->item('master_user_id');
    //$master_user_id = $data['user_info']['ID'];
    $user_login_id = $CI->session->userdata('LOGGED_IN')['ID'];
    $table_user = LOGIN . ' as lg';
    $where_user = array("lg.login_id" => $master_user_id);
    $fields_user = array("lg.*");
    $check_user_data = $CI->common_model->get_records($table_user, $fields_user, '', '', '', '', '', '', '', '', '', $where_user);

    $table = SETUP_MASTER . ' as ct';
    //$where_setup_data = array("ct.login_id" => $master_user_id);
    $where_setup_data = "ct.login_id = " . $master_user_id . " AND ct.email = '" . $check_user_data[0]['email'] . "'";
    $fields = array("ct.*");
    $check_user_menu = $CI->common_model->get_records_data($table, $fields, '', '', '', '', '', '', '', '', '', $where_setup_data);
    //pr($check_user_menu);exit;
    if (isset($check_user_menu[0]['is_support']) && $check_user_menu[0]['is_support'] == 0) {
        // echo "if";
        if (isset($check_user_menu[0]['is_crm']) && $check_user_menu[0]['is_crm'] == 1) {
            // echo "else";
            if ($_SERVER["HTTP_REFERER"] != base_url('Masteradmin')) {
                $msg = $CI->lang->line('DONT_HAVE_PAGE_PERMISSION');
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
            redirect(base_url()); //Redirect on Dashboard
        } elseif (isset($check_user_menu[0]['is_pm']) && $check_user_menu[0]['is_pm'] == 1) {
            if ($_SERVER["HTTP_REFERER"] != base_url('Masteradmin')) {
                $msg = $CI->lang->line('DONT_HAVE_PAGE_PERMISSION');
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
            redirect(base_url('Projectmanagement/Projectdashboard')); //Redirect on Dashboard
        }
    }

    $table_user_support = LOGIN . ' as lg';
    $where_user_support = array("lg.login_id" => $user_login_id);
    $fields_user_support = array("lg.*");
    $user_data_support = $CI->common_model->get_records($table_user_support, $fields_user_support, '', '', '', '', '', '', '', '', '', $where_user_support);
    if (isset($user_data_support[0]['is_support_user']) && $user_data_support[0]['is_support_user'] == 0) {
        if (isset($user_data_support[0]['is_crm_user']) && $user_data_support[0]['is_crm_user'] == 1) {
            if ($_SERVER["HTTP_REFERER"] != base_url('Masteradmin')) {
                $msg = $CI->lang->line('DONT_HAVE_PAGE_PERMISSION');
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
            redirect(base_url()); //Redirect on Dashboard
        } elseif (isset($user_data_support[0]['is_pm_user']) && $user_data_support[0]['is_pm_user'] == 1) {
            if ($_SERVER["HTTP_REFERER"] != base_url('Masteradmin')) {
                $msg = $CI->lang->line('DONT_HAVE_PAGE_PERMISSION');
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
            redirect(base_url('Projectmanagement/Projectdashboard')); //Redirect on Dashboard
        }
    }
}

/*
 * Function Added By Sanket  Jayani ON 27/04/2016 Fro Generating CSV From Array
 * 
 * 
 */

function export_to_csv($input_array, $output_file_name, $delimiter) {
    /** open raw memory as file, no need for temp files, be careful not to run out of memory thought */
    $tmp = FCPATH . "uploads/csv_leads/test.php";
    //$f = fopen($tmp, 'w+');
    $f = fopen('php://memory', 'w');
    /** loop through array  */
    foreach ($input_array as $line) {
        /** default php csv handler * */
        fputcsv($f, $line, $delimiter);
    }
    /** rewrind the "file" with the csv lines * */
    fseek($f, 0);
    /** modify header to be downloadable csv file * */
    header('Content-Type: application/csv');
    header('Content-Disposition: attachement; filename="' . $output_file_name . '";');
    /** Send file to browser for download */
    fpassthru($f);
}

/*
  @Author : Niral Patel
  @Desc   : Function for substr of html
  @Input  : Html string, length
  @Output : substr
  @Date   : 10/03/2016
 */

function html_substr($s, $srt, $len = NULL, $strict = false, $suffix = NULL) {
    if (is_null($len)) {
        $len = strlen($s);
    }

    $f = 'static $strlen=0; 
      if ( $strlen >= ' . $len . ' ) { return "><"; } 
      $html_str = html_entity_decode( $a[1] );
      $subsrt   = max(0, (' . $srt . '-$strlen));
      $sublen = ' . ( empty($strict) ? '(' . $len . '-$strlen)' : 'max(@strpos( $html_str, "' . ($strict === 2 ? '.' : ' ') . '", (' . $len . ' - $strlen + $subsrt - 1 )), ' . $len . ' - $strlen)' ) . ';
      $new_str = substr( $html_str, $subsrt,$sublen); 
      $strlen += $new_str_len = strlen( $new_str );
      $suffix = ' . (!empty($suffix) ? '($new_str_len===$sublen?"' . $suffix . '":"")' : '""' ) . ';
      return ">" . htmlentities($new_str, ENT_QUOTES, "UTF-8") . "$suffix<";';

    return preg_replace(array("#<[^/][^>]+>(?R)*</[^>]+>#", "#(<(b|h)r\s?/?>){2,}$#is"), "", trim(rtrim(ltrim(preg_replace_callback("#>([^<]+)<#", create_function(
                                                    '$a', $f
                                            ), ">$s<"), ">"), "<")));
}

/*
  @Author : Sanket Jayani
  @Desc   : Function for convert time to 12 hour
  @Input  : time
  @Output : convert time into 12 Hour Format
  @Date   : 10/03/2016
 */

function convertTimeTo12HourFormat($time) {
    return date("g:i A", strtotime($time));
}

/*
  @Author : Sanket Jayani
  @Desc   : Function for generation system template auto generated id
  @Input  :
  @Output : system template slug
  @Date   : 10/03/2016
 */

function systemTemplateFlag() {
    return "ST_" . rand(1000, 9999);
}

/*
  @Author : Sanket Jayani
  @Desc   : Function for getting template data
  @Input  : st_slug (System template Slug)
  @Output : system template data
  @Date   : 10/03/2016
 */

function systemTemplateDataBySlug($st_slug) {
    $CI = & get_instance();
    $table = EMAIL_TEMPLATE_MASTER . ' as rm';
    $match = "rm.st_slug = '" . $st_slug . "'";
    $fields = array("rm.*");
    $email_template_data = $CI->common_model->get_records($table, $fields, '', '', $match);

    return $email_template_data;
}

function calculate_days($start_date, $end_date) {

    $now = strtotime($start_date); // or your date as well
    $your_date = strtotime($end_date);
    return $days_between = ceil(abs($your_date - $now) / 86400);
}

//by sanket
function get_newsletter_type() {
    $CI = & get_instance();
    $Where_mailchimp = "config_key='newsletter_type'";
    $data_mailchimp = $CI->common_model->get_records(CONFIG, array('value'), '', '', $Where_mailchimp);

    if (isset($data_mailchimp[0]['value']) && $data_mailchimp[0]['value'] != '') {
        $tmp_val = $data_mailchimp[0]['value'];
    } else {
        $tmp_val = 0;
    }
    return $tmp_val;
}

//by sanket mailchimp list id
function config_list_id() {
    $CI = & get_instance();
    $Where_mailchimp = "config_key='mailchimp_configuration'";
    $data_mailchimp = $CI->common_model->get_records(CONFIG, array('value'), '', '', $Where_mailchimp);
    $mailchimp_data = json_decode($data_mailchimp[0]['value']);
    return $list_id = $mailchimp_data->list_id;
}

//by sanket campaing monitor list id
function config_cmonitor_list_id() {
    $CI = & get_instance();
    $Where_mailchimp = "config_key='campaign_monitor_configuration'";
    $data_mailchimp = $CI->common_model->get_records(CONFIG, array('value'), '', '', $Where_mailchimp);
    $mailchimp_data = json_decode($data_mailchimp[0]['value']);
    return $list_id = $mailchimp_data->list_id;
}

// get Languages from blzdsk_language_master table 
function getLanguages() {
    $CI = & get_instance();
    $table = LANGUAGE_MASTER . ' as lm';
    $fields = array("lm.language_id,lm.language_name,lm.name");
    $order_by = 'lm.language_name';
    $order = 'ASC';
    $language_data = $CI->common_model->get_records($table, $fields, '', '', '', '', '', '', $order_by, $order);
    return $language_data;
}
function getPurchasedModuleCounts() {
	
    $CI = & get_instance();
    	 $sub_domain=array_shift((explode(".",$_SERVER['HTTP_HOST'])));
		 
    $main_user_id = $CI->config->item('master_user_id');
    $table = SETUP_MASTER . ' as ct';
    $where= "ct.domain_name = '".$sub_domain."'";
  //  $where = "ct.login_id ='".$main_user_id."',ct.domain_name = '".$sub_domain."';
    $fields = array("ct.support_user,ct.crm_user,ct.pm_user");
    $checkPurchasedUserLimitPerModule = $CI->common_model->get_records_data($table, $fields, '', '', '', '', '', '', '', '', '', $where);
	//pr($checkPurchasedUserLimitPerModule);
    return $checkPurchasedUserLimitPerModule;
}

function getAssignedModuleCounts($role_id) {

    $CI = & get_instance();
    $table = LOGIN;
    $where = "is_delete = 0 AND status=1  AND user_type = " . $role_id;
    $fields = array("sum(`is_crm_user`) as crm_user ,sum(`is_pm_user`) as pm_user,sum(`is_support_user`) as support_user");
    $getAssignedModuleCounts = $CI->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $getAssignedModuleCounts;
}

// Get Assigned Module name from Role
function checkHavingModuleAssign($role_id) {

    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('aauth_perm_to_group as APG');
    $CI->db->where('role_id', $role_id);
    $CI->db->group_by(array("component_name"));
    $resultData = $CI->db->get()->result_array();

    return $resultData;
}

// Get User List from Role 
function getUserList($roleID) {
    $CI = & get_instance();
    $table3 = LOGIN . ' as l';
    $where3 = array("l.user_type " => $roleID, "l.is_delete" => "0");
    $fields3 = array("l.login_id");
    $getCountofSupportuser1 = $CI->common_model->get_records($table3, $fields3, '', '', '', '', '', '', '', '', '', $where3);

    return $getCountofSupportuser1;
}

//Added by Sanket
function change_moosend_date($timestamp) {
    $ci = & get_instance();
    $table = CONFIG . ' as con';
    $fields = array("con.value, con.config_key");
    $where = "con.config_key = 'date_format'";
    $dateInfo = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

    if (!empty($timestamp)) {
        return date($dateInfo[0]['value'], $timestamp);
    } else {
        return date($dateInfo[0]['value']);
    }
}

/*
  @Author : Mehul Patel
  @Desc   :  Create Dropdown
  @Input 	:  $name ,array $options,$selected
  @Output	:  Dropdown create
  @Date   : 20/01/2016
 */

function dropdownUserStatus($name, array $options, $selected = null, $readonly = null, $first_option = null, $second_option = null) {
    //pr($first_option);die();
    /*     * * begin the select ** */
    $dropdown = '<select class="form-control" onchange="checkUserCounts(this.value)" name="' . $name . '" id="' . $name . '" ' . $readonly . '>' . "\n";

    $selected = $selected;
    /*     * * loop over the options ** */
    if ($first_option != '') {
        $dropdown .= '<option value="">' . $first_option . '</option>' . "\n";
    }
    if ($second_option != '') {
        $select = $selected == '0' ? ' selected' : null;
        $dropdown .= '<option value="0" ' . $select . '>' . $second_option . '</option>' . "\n";
    }
    foreach ($options as $key => $option) {
        /*         * * assign a selected value ** */
        $select = $selected == $key ? ' selected' : null;

        /*         * * add each option to the dropdown ** */

        $dropdown .= '<option value="' . $key . '"' . $select . '>' . $option . '</option>' . "\n";
    }

    /*     * * close the select ** */
    $dropdown .= '</select>' . "\n";

    /*     * * and return the completed dropdown ** */
    return $dropdown;
}



/*
  @Author : sanket Jayani
  @Desc   :  Getting Config General INformation
  @Input  :  
  @Output :  Dropdown create
  @Date   : 20/01/2016
 */
function configGeneralSettgins() {
    $CI = & get_instance();
    $Where_generalSettings = "config_key='general_settings'";
    $dataGenralSettings = $CI->common_model->get_records(CONFIG, array('value'), '', '', $Where_generalSettings);
    $generalSettings = json_decode($dataGenralSettings[0]['value']);
    return $generalSettings;
}

/*
  @Author : sanket Jayani
  @Desc   :  Getting Newsletter lists array
  @Input  :  newsletter type 1-Mailchimp,2- Campaign Monitor, 3- Moosend, 4- Getresponse
  @Output :  Array of all lists by type
  @Date   : 20/01/2016
 */
function getNewsletterListsByType($type) 
{
    $CI = & get_instance();
    $table = TBL_NEWSLETTER_LISTS_MASTER;
    $where = "status=1 AND lists_type = " . $type;
    $fields = array("lists_id,lists_name");
    $newsletterLists = $CI->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    
    $tmp_arr = [];
    
    if(count($newsletterLists) > 0)
    {
        foreach ($newsletterLists as $lists)
        {
            $tmp_arr[$lists['lists_id']] = $lists['lists_name'];
        }
    }
    return $tmp_arr;
}

/*
  @Author : Mehul Patel
  @Desc   :  getListofSelectedModules
  @Input 	: $role_id
  @Output	: return list of Assigned Modules
  @Date   : 20/01/2016
 */

function getListofSelectedModules($role_id) {

    $CI = & get_instance();
    $arraylists = array();
    $CI->db->select('*');
    $CI->db->from('aauth_perm_to_group as APG');
    $CI->db->where('role_id', $role_id);
    $CI->db->group_by(array("component_name"));
    $resultData = $CI->db->get()->result_array();

    foreach ($resultData as $keys => $vals) {

        if ($vals['component_name'] == "CRM") {
            $vals['component_name'] = 'crm_user';
            $arraylists['crm_user'] = 'crm_user';
        } elseif ($vals['component_name'] == "PM") {
            $vals['component_name'] = 'pm_user';
            $arraylists['pm_user'] = 'pm_user';
        } elseif ($vals['component_name'] == "Support") {
            $vals['component_name'] = 'support_user';
            $arraylists['support_user'] = 'support_user';
        }
    }

    return $arraylists;
}

function messageCount() {
    $ci = & get_instance();
    $ci->load->library('Encryption');  // this library is for encoding/decoding password
    $ci->load->library('session');  // this library is for encoding/decoding password
    $ci->load->library('imap');  // this library is for encoding/decoding password
    $ci->load->model('common_model');  // this library is for encoding/decoding password
    $user_id = $ci->session->userdata('LOGGED_IN')['ID'];
    $where = "user_id = " . $user_id;
    $configData = $ci->common_model->get_records(EMAIL_CONFIG, '', '', '', '', '', '1', '', '', '', '', $where, '', '', '', '', '', '');

    if ($configData) {

        $converter = new Encryption;
        $mailbox = $configData[0]['email_server'] . ':' . $configData[0]['email_port'] . '/';
        $username = $configData[0]['email_id'];
        $password = $converter->decode($configData[0]['email_pass']);
        $encryption = $configData[0]['email_encryption'];
        if ($ci->imap->connect($mailbox, $username, $password, $encryption)) {
            $ci->imap->selectFolder('INBOX');

            if ($ci->imap->countUnreadMessages() > 0):
                echo $ci->imap->countUnreadMessages();
            else:
                echo '';
            endif;
        } else {
            echo '';
        }
    }
}


function getListsofSelectedModules($role_id) {

    $CI = & get_instance();
    $arraylists = array();
    $CI->db->select('*');
    $CI->db->from('aauth_perm_to_group as APG');
    $CI->db->where('role_id', $role_id);
    $CI->db->group_by(array("component_name"));
    $resultData = $CI->db->get()->result_array();

    foreach ($resultData as $keys => $vals) {

        if ($vals['component_name'] == "CRM") {
            $vals['component_name'] = 'crm_user';
            $arraylists['crm_user'] = 1;
        } elseif ($vals['component_name'] == "PM") {
            $vals['component_name'] = 'pm_user';
            $arraylists['pm_user'] = 1;
        } elseif ($vals['component_name'] == "Support") {
            $vals['component_name'] = 1;
            $arraylists['support_user'] = 1;
        }
    }

    return $arraylists;
}

// Get User List from Role 
function isUserCreate($role_id) {

	$CI = & get_instance();
	$array_lists = array();
	$table3 = LOGIN . ' as l';
	$where3 = array("l.user_type " => $role_id, "l.is_delete" => "0", "l.status" => "1");
	$fields3 = array("count(l.login_id) totalUsers");
	$getCountofSupportuser1 = $CI->common_model->get_records($table3, $fields3, '', '', '', '', '', '', '', '', '', $where3);

	$array_lists['crm_user'] = $getCountofSupportuser1[0]['totalUsers'];
	$array_lists['pm_user'] = $getCountofSupportuser1[0]['totalUsers'];
	$array_lists['support_user'] = $getCountofSupportuser1[0]['totalUsers'];
		
	
	return $array_lists;
}


function getSelectedModules($role_id) {

    $CI = & get_instance();
    $arraylists = array();
    $CI->db->select('*');
    $CI->db->from('aauth_perm_to_group as APG');
    $CI->db->where('role_id', $role_id);
    $CI->db->group_by(array("component_name"));
    $resultData = $CI->db->get()->result_array();
	$result_array1 = array("crm_user"=>0,"pm_user" =>0,"support_user" =>0);
    foreach ($resultData as $keys => $vals) {

        if ($vals['component_name'] == "CRM") {
            $vals['component_name'] = 'crm_user';
            $arraylists['crm_user'] = 1;
        } elseif ($vals['component_name'] == "PM") {
            $vals['component_name'] = 'pm_user';
            $arraylists['pm_user'] = 1;
        } elseif ($vals['component_name'] == "Support") {
            $vals['component_name'] = 1;
            $arraylists['support_user'] = 1;
        }
    }
	$arraylists = array_merge($result_array1,$arraylists);
	
    return $arraylists;
}

/*
  @Author : sanket Jayani
  @Desc   :  Check user purchase crm or not
  @Input  :  User Id
  @Output :  return true if purchase or false if not
  @Date   : 20/01/2016
 */

function checkUserIsCrm($userId)
{
    $CI = & get_instance();
    $where = array('is_delete' => 0, 'status' => 1,'is_crm_user'=>1,'login_id'=>$userId);
    $chkUser = $CI->common_model->get_records('login', '', '', '', $where, '');
    
    if(is_array($chkUser) && !empty($chkUser) && $chkUser[0]['login_id'] == $userId)
    {
        return true;
    }else
    {
        return false;
    }
}
?>
