<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(2);
        $this->load->library(array('form_validation', 'Session', 'breadcrumbs'));
    }

    public function index() {
        /*
         * for getting Files
         */
        // $data = $this->salesTargetProgressGraph();
        // pr($data);
        $currencyData = $this->getDefaultCurrency();
        if (count($currencyData) > 0) {
            $currency = $currencyData['currency_code'];
            $symbol = $currencyData['symbol'];
        } else {
            $currency = $this->config->item('currency');
        }

        $data = $this->salesTargetProgressGraph();
        $data['user_info'] = $this->session->userdata('LOGGED_IN');  //Current Login information
        //  $master_user_id = $data['user_info']['ID'];
        checkPurchasedUserModuleForCRM();
        $user_id = $this->session->userdata('LOGGED_IN')['ID'];
        $data['drag'] = true;
        $this->breadcrumbs->push(lang('crm'), '/');
        $this->breadcrumbs->push(lang('dashboard'), 'Dashboard');

        //   $defaultDashboard = array('sectionLeft' => array('position-left-top', 'position-left-bottom'), 'sectionRight' => array('position-right-top', 'position-right-bottom'));
        $dashWhere = "config_key='dashboard_widgets'";
        $defaultDashboard = $this->common_model->get_records(CONFIG, array('value'), '', '', $dashWhere);
        $tasksWhere = '';
        $data['task_data'] = $this->common_model->get_records(TASK_MASTER . ' as td', '', '', '', 'is_delete=0 and created_by=' . $user_id, '', 10, 0, 'task_id', 'desc');
        //  $data['task_data'] =$this->common_model->get_records(TASK_MASTER, '', '', '', $tasksWhere,10,);
        //$contactWhere = ' DATE(created_date) = DATE(NOW())';
        $contactWhere = 'is_delete = 0 AND status= 1 AND DATE(created_date) -  INTERVAL 1 MONTH';
        $data['count_contacts'] = count($this->common_model->get_records(CONTACT_MASTER, array('contact_id'), '', '', $contactWhere));

        //$opportunitiesWhere = 'status_type=2 and DATE(created_date) = DATE(NOW())';
        $opportunitiesWhere = 'status_type=2 and DATE(created_date) - INTERVAL 1 MONTH';
        $data['count_opportunities'] = $this->common_model->get_records(PROSPECT_MASTER, array('prospect_id'), '', '', $opportunitiesWhere);

        $lostClientsWhere = 'status_type=1';
        $data['count_lost_clients'] = count($this->common_model->get_records(PROSPECT_MASTER, array('prospect_id'), '', '', $lostClientsWhere));
        $data['header'] = array('menu_module' => 'crm');
        $data['opportunities'] = $this->getSalesData();
//       / $data['total_salestarget'] = $this->salestarget(false);
        //this valirable to include javascript for drag & drop
        $data['drag'] = true;
        if ($this->session->has_userdata('blazedesk_dashboardWidgets')) {
            $data['widgets'] = $this->session->userdata('blazedesk_dashboardWidgets');
        } else {
            $data['widgets'] = (array) json_decode($defaultDashboard[0]['value']);
        }
        /*
         * for getting salesTarget
         */
        // $data = array_merge($data, $this->getMonthlySalesData());
        //  echo $data['sales_difference'];
        if ($data['sales_difference'] != '') {
            if ($data['sales_difference'] < 0) {
                $target = 0;
                if (count($data['sales_target'] > 0)) {
                    $target = $data['sales_target'][0]['target'];
                }
                $data['sales_difference'] = '+' . $data['sales_amount_by_user'] - $target;
                $data['plus'] = '+';
            }
        } else {
            $data['sales_difference'] = 0;
        }
        /*
         * sales target code ends
         */

        /*
         * sales comparision code starts
         */

        $mnt = date('m', strtotime('-1 month'));
        $year = date('Y');
        $cmonth = date('m');
        $currentMonthSdate = $year . '-' . $cmonth . '-01';
        $currentMonthEdate = $year . '-' . $cmonth . '-31';
        $nextMonthSdate = $year . '-' . $mnt . '-01';
        $nextMonthEdate = $year . '-' . $mnt . '-31';

        $login_id = $this->session->userdata('LOGGED_IN')['ID'];
        $table = PROSPECT_MASTER . ' as pm';
        $params['join_tables'] = array(ESTIMATE_MASTER . ' as em' => 'em.estimate_id= pm.estimate_prospect_worth',
            'blzdsk_countries as cm' => 'cm.country_id = em.country_id_symbol');
        $params['join_type'] = 'inner';
        $match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND pm.created_by = " . $login_id . " and pm.created_date>='$currentMonthSdate' and pm.created_date<='$currentMonthEdate'";
        $fields = array("em.value as estimate_prospect_worth,cm.currency_code");
        $currentMonthData = $nextMonthData = array();
        $current_month_data = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', '', '', '', $match);
        //  echo $this->db->last_query();
        if (count($current_month_data) > 0) {
            foreach ($current_month_data as $cdata) {
                 if (isset($cdata['currency_code']) &&  $cdata['currency_code']!= $currency) {
                    $currentMonthData[] = number_format($this->postConvertCurrency($cdata['estimate_prospect_worth'], $cdata['currency_code'], $currency), 2, '.', '');
                } else {
                    $currentMonthData[] = number_format($cdata['estimate_prospect_worth'], 2, '.', '');
                }
            }
        }
        $matchNext = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND pm.created_by = " . $login_id . " and pm.created_date>='$nextMonthSdate' and pm.created_date<='$nextMonthEdate'";
        $fields = array("em.value  as estimate_prospect_worth");
        $next_month_data = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', '', '', '', $matchNext);
        if (count($next_month_data) > 0) {
            foreach ($next_month_data as $ndata) {
                if (isset($ndata['currency_code']) &&  $ndata['currency_code']!= $currency) {
                    $nextMonthData[] = number_format($this->postConvertCurrency($ndata['estimate_prospect_worth'], $ndata['currency_code'], $currency), 2, '.', '');
                } else {
                    $nextMonthData[] = number_format($ndata['estimate_prospect_worth'], 2, '.', '');
                }
            }
        }

        $data['currentMonthData'] = count($currentMonthData > 0) ? implode(',', $currentMonthData) : '';
        $data['nextMonthData'] = count($nextMonthData > 0) ? implode(',', $nextMonthData) : '';
        $data['currentMonthName'] = date("F", mktime(null, null, null, $cmonth));
        $data['nextMonthName'] = date("F", mktime(null, null, null, $mnt));
        /*
         * sales comparision code ends
         */

        /*
         * sales forecast code starts
         */
        $data['salesForecast'] = 0;
        $maxDays = date('t');
        $currency = $this->config->item('currency');
        $currentDayOfMonth = date('j');
        $passedDays = $maxDays - $currentDayOfMonth;
        $totalAmnt = 0;
        $finalAmnt = 0;
        $perAmt = 0;
        $table = PROSPECT_MASTER . ' as pm';
        $login_id = $this->session->userdata('LOGGED_IN')['ID'];
        $params['join_tables'] = array(ESTIMATE_MASTER . ' as em' => 'em.estimate_id= pm.estimate_prospect_worth', COUNTRIES . ' as c' => 'c.country_id = em.country_id_symbol');
        $params['join_type'] = 'left';
//$match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND pm.prospect_assign = ".$login_id;
        $match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND pm.created_by = " . $login_id . " and MONTH(CURDATE()) and YEAR(pm.created_date) = YEAR(CURDATE())";
        $fields = array("em.value AS createdAccount,c.currency_code");
        $sale_aacount_count = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);

        if (count($sale_aacount_count) > 0) {
            foreach ($sale_aacount_count as $row) {
                if ($row['currency_code'] != $currency) {
                    $salesAmmountArr[] = helperConvertCurrency($row['createdAccount'], $row['currency_code'], $currency);
                } else {
                    $salesAmmountArr[] = $row['createdAccount'];
                }
            }
            $totalAmnt = $salesAmmount = array_sum($salesAmmountArr);
            if ($totalAmnt > 0) {
                $finalAmnt = ($totalAmnt * $maxDays) / $passedDays;
            }
            $match = "sts.is_delete=0 AND  sts.status=1  AND sts.login_id = " . $login_id;
            $fields = array("sts.target , c.currency_symbol AS currency_symbol , sts.country_id");
            $params['join_tables'] = array(COUNTRIES . ' as c' => 'c.country_id = sts.country_id');
            $params['join_type'] = 'left';
            $data['sales_target'] = $this->common_model->get_records(SALES_TARGET_SETTINGS . ' as sts', $fields, $params['join_tables'], $params['join_type'], $match, '');
            if (isset($data['sales_target'][0]['country_id'])) {
                $country_code = getCourrencyCode($data['sales_target'][0]['country_id']);
            }

//	pr($country_code[0]['currency_code']); exit;
            if ($finalAmnt > 0) {
                $perAmt = ($finalAmnt * 100) / $totalAmnt;
                $data['salesForecast'] = round($perAmt, 2);
            }
        } else {
            $salesAmmount = 0;
        }

        /*
         * sales forecast code ends
         */
        $data['header'] = array('menu_module' => 'crm');
        $data['main_content'] = '/Dashboard';
        $data['js_content'] = '/loadJsFiles';
        /*
         * logged in user data
         */
        $umatch = "login_id =" . $login_id;
        $ufields = array("created_date");
        $data['logged_user'] = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);
        /*
         * logged in user data ends
         */
        if ($this->input->is_ajax_request()) {
            $this->load->view('Dashboard', $data);
        } else {

            $this->parser->parse('layouts/DashboardTemplate', $data);
        }
    }

    public function postConvertCurrency($amount, $from, $to) {
        $url = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
        $data = file_get_contents($url);
        //print_r($data);exit;
        preg_match("/<span class=bld>(.*)<\/span>/", $data, $converted);
        if (count($converted) > 0) {
            $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
        }
        return round($converted, 3);
    }

    public function worth_opportunities() {
        $currencyData = $this->getDefaultCurrency();
        if (count($currencyData) > 0) {
            $currency = $currencyData['currency_code'];
            $symbol = $currencyData['symbol'];
        } else {
            $currency = $this->config->item('currency');
            $symbol = '$';
        }

        $table_worth = PROSPECT_MASTER . ' as pm';
        $worth_opportunities_data = 'pm.status_type = 1 and DATE(pm.created_date) - INTERVAL 1 MONTH';
        $fields_worth = array("pm.prospect_id,em.value as estimate_prospect_worth,pm.country_id,cm.currency_code");
        $join_tables_worth = array(ESTIMATE_MASTER . ' as em' => 'em.estimate_id= pm.estimate_prospect_worth', 'blzdsk_countries as cm' => 'cm.country_id = em.country_id_symbol');
        $worth_opportunities = $this->common_model->get_records($table_worth, $fields_worth, $join_tables_worth, 'left', '', '', '', '', '', '', '', $worth_opportunities_data);
        //pr($data['worth_opportunities']);exit;

        foreach ($worth_opportunities as $key => $value) {
            if ($value['currency_code'] == $currency) {
                $block_data[$key]['worth_opportunities_price'] = $value['estimate_prospect_worth'];
            } else {
                $block_data[$key]['worth_opportunities_price'] = $this->postConvertCurrency($value['estimate_prospect_worth'], $value['currency_code'], $currency);
            }
        }

        $worth_price = array();
        foreach ($block_data as $worth_info) {
            $worth_price[] = $worth_info['worth_opportunities_price'];
        }

        $worth_data = array_sum($worth_price);
        $worth_opportunitie = (count($worth_opportunities) > 0) ? count($worth_opportunities) : 0;


        echo json_encode(array('data_worth' => $worth_data, 'worth_opportunitie' => $worth_opportunitie, 'symbol' => $symbol));
        die();
    }

    public function salestarget($is_json = true) {
        $data = $this->salesTargetProgressGraph();

        if ($data['sales_difference'] != '') {
            if ($data['sales_difference'] < 0) {
                $target = 0;
                if (count($data['sales_target'] > 0)) {
                    $target = $data['sales_target'][0]['target'];
                }
                if($target<$data['sales_amount_by_user']){
                     $data['sales_difference'] = $data['sales_amount_by_user'] - $target;
                }
                else
                {
                     $data['sales_difference'] =$target- $data['sales_amount_by_user'];
                }
               // $data['sales_difference'] = $data['sales_amount_by_user'] - $target;
                $data['sales_difference'] = '+' . $data['sales_difference'];
            }
        } else {
            $data['sales_difference'] = 0;
        }
//      /  pr($data);
        echo json_encode(array('target_data' => $data['sales_difference'], 'symbol' => $data['symbol']));
        die;
//        $currency = $this->config->item('currency');
//        $user_id = $this->session->userdata('LOGGED_IN')['ID'];
//
//        $sal_table = SALES_TARGET_SETTINGS . ' as sts';
//        $targets_where = 'sts.is_delete = 0 and sts.status = 1 and DATE(sts.created_date) - INTERVAL 1 MONTH and login_id=' . $user_id;
//        $fields_won = array("sts.target_id,sts.login_id,sts.country_id,sts.currency_symbol,sts.target,cm.currency_code");
//        $join_tables_won = array('blzdsk_countries as cm' => 'cm.country_id = sts.country_id');
//        $data['sales_targets'] = $this->common_model->get_records($sal_table, $fields_won, $join_tables_won, 'left', '', '', '', '', '', '', '', $targets_where);
//        $sales_data = array();
//
//        foreach ($data['sales_targets'] as $key => $sales_value) {
//            if ($sales_value['currency_code'] == $currency) {
//                $sales_data[$key]['sales_price'] = $sales_value['target'];
//            } else {
//                $sales_data[$key]['sales_price'] = $this->postConvertCurrency($sales_value['target'], $sales_value['currency_code'], $currency);
//            }
//        }
//
//        $sales_price = array();
//        foreach ($sales_data as $sales_info) {
//            $sales_price[] = $sales_info['sales_price'];
//        }
//
//        $target_data = array_sum($sales_price);
//        $target_data_list = (!empty($target_data) && $target_data != '') ? $target_data : 0;
//
//        if ($is_json == true) {
//            echo json_encode(array('target_data' => $target_data_list));
//            die();
//        } else {
//            return $target_data_list;
//        }
    }

    public function won_opportunities($is_json = true) {
        $user_id = $this->session->userdata('LOGGED_IN')['ID'];
        $currencyData = $this->getDefaultCurrency();
        if (count($currencyData) > 0) {
            $currency = $currencyData['currency_code'];
            $symbol = $currencyData['symbol'];
        } else {
            $currency = $this->config->item('currency');
        }

        $table = PROSPECT_MASTER . ' as pm';
        $won_opportunities_data = 'pm.status_type=3 and  MONTH(CURDATE()) and YEAR(pm.created_date) = YEAR(CURDATE()) and pm.created_by=' . $user_id;
        $fields_won = array("pm.prospect_id,em.value as estimate_prospect_worth,pm.country_id,cm.currency_code");
        $join_tables_won = array(ESTIMATE_MASTER . ' as em' => 'em.estimate_id= pm.estimate_prospect_worth', 'blzdsk_countries as cm' => 'cm.country_id = em.country_id_symbol');
        $won_opportunities = $this->common_model->get_records($table, $fields_won, $join_tables_won, 'left', '', '', '', '', '', '', '', $won_opportunities_data);
        $block_data = array();
        foreach ($won_opportunities as $key => $value) {
            if ($value['currency_code'] == $currency) {
                $block_data[$key]['won_opportunities_price'] = $value['estimate_prospect_worth'];
            } else {
                $block_data[$key]['won_opportunities_price'] = $this->postConvertCurrency($value['estimate_prospect_worth'], $value['currency_code'], $currency);
            }
        }
        $won_price = array();
        foreach ($block_data as $won_info) {
            $won_price[] = $won_info['won_opportunities_price'];
        }

        $won_data = array_sum($won_price);
        $won_opportunitie = (count($won_opportunities) > 0) ? count($won_opportunities) : 0;

        if ($is_json == true) {
            echo json_encode(array('data_won' => $won_data, 'won_opportunitie' => $won_opportunitie, 'symbol' => $symbol));
            die();
        } else {
            return $won_opportunitie;
        }
    }

    private function getSalesData() {
        $table = PROSPECT_MASTER . ' as pm';
        $data['opportunity_view'] = $this->viewname;
        $data['status'] = array(1 => 'Prospect', 2 => 'Lead', 3 => 'Client');
        $data['salessortField'] = 'pm.prospect_id';
        $data['salessortOrder'] = 'desc';
        $params['join_tables'] = array(PROSPECT_CONTACTS_TRAN . ' as pc' => 'pm.prospect_id=pc.prospect_id');
        $params['join_type'] = 'inner';
        $match = "";
        $group_by = 'pm.prospect_id';
        $fields = array("pm.prospect_id,count(pm.prospect_id) as opp_count,pm.prospect_name,pm.prospect_auto_id, pm.status_type,count(pc.prospect_id) as contact_count,pc.contact_name,pm.creation_date");
        return $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', 10, 0, $data['salessortField'], $data['salessortOrder'], $group_by);
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Common pagination initialization
      @Input 	:
      @Output	:
      @Date   : 01/02/2016
     */

    private function pagingConfig($config, $page_url) {
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
        $config['cur_tag_open'] = '<li class="current"><a href="' . $page_url . '">';
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

    public function dashboardWidgetsOrder() {
        if (!$this->input->is_ajax_request()) {
            exit("no direct scripts allowed");
        } else {
            $user_id = $this->session->userdata('LOGGED_IN')['ID'];
            if ($this->input->get('resetWidgets')) {

                $defaultDashboard = array('sectionLeft' => array('position-left-top', 'position-left-bottom'), 'sectionRight' => array('position-right-top', 'position-right-bottom'));
                $data = array('dashboard_widgets' => json_encode($defaultDashboard));
                $this->common_model->update(LOGIN, $data, array('login_id' => $user_id));
                $this->session->unset_userdata('blazedesk_dashboardWidgets');
                $this->session->set_userdata('blazedesk_dashboardWidgets', $defaultDashboard);
                echo json_encode(array('status' => 1, 'type' => 'reset'));
                die;
            } else {
                $placeHolderDiv1 = $this->input->post('placeholder1');
                $innerWidgets1 = $this->input->post('innerWidgets1');
                $placeHolderDiv2 = $this->input->post('placeholder2');
                $innerWidgets2 = $this->input->post('innerWidgets2');
                $widgetsArr = array();
                if (count($innerWidgets1) > 0) {

                    $widgetsArr[$placeHolderDiv1] = $innerWidgets1;
                }
                if (count($innerWidgets2) > 0) {
                    $widgetsArr[$placeHolderDiv2] = $innerWidgets2;
                }
                if (count($widgetsArr) > 0) {
                    $data = array('dashboard_widgets' => json_encode($widgetsArr));
                    $this->common_model->update(LOGIN, $data, array('login_id' => $user_id));
                    $this->session->unset_userdata('blazedesk_dashboardWidgets');
                    $this->session->set_userdata('blazedesk_dashboardWidgets', $widgetsArr);
                }

                echo json_encode(array('status' => 1, 'type' => 'new'));
                die;
            }
        }
    }

    public function logout() {
        $user_session = $this->session->userdata('LOGGED_IN');
        if ($user_session) {
            $this->session->unset_userdata('LOGGED_IN');
            $this->session->unset_userdata('blazedesk_dashboardWidgets');
            $this->session->unset_userdata('blazedesk_pm_taskdashboardinnerWidgets');
            $this->session->unset_userdata('blazedesk_pm_taskdashboardWidgets');
            $this->session->unset_userdata('ticketDashboardWidgets');
            $this->session->unset_userdata('salesoverview_dashboard_widgets');
            $this->session->unset_userdata('checked_campaign_id');
            $this->session->unset_userdata('PROJECT_ID');
            $this->session->unset_userdata('PROJECT_STATUS');
            $error_msg = ERROR_START_DIV . lang('SUCCESS_LOGOUT') . ERROR_END_DIV;
            $this->session->set_userdata('ERRORMSG', $error_msg);
            $this->session->sess_destroy();
            redirect(base_url('Masteradmin'));
        } else {
            redirect(base_url());
        }
    }

    public function getContactEvents() {

        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if ($searchtext == 'clearData') {
            $searchtext = '';
            $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) {

            $where_search = '(td.event_title LIKE "%' . $searchtext . '%" )';
            $this->session->set_userdata('searchtext', $searchtext);
        } else if (!empty($session_searchtext)) {
            $searchtext1 = $this->session->userdata('searchtext');
            $where_search = '(td.event_title LIKE "%' . $searchtext1 . '%" )';
        } else {
            $this->session->set_userdata('searchtext', '');
        }

        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;

        //config variable for the pagination
        $config['base_url'] = base_url('Dashboard') . '/getContactEvents';

        $data['tasksortField'] = 'event_date';
        $data['tasksortOrder'] = 'ASC';
        $fields = array("td.*");
        $where = array("td.is_delete" => "0");
        
        $tomorrowdate = date("Y-m-d", strtotime("+5 day"));
        
        $config['per_page'] = 5;
        $rocordToShow = 5;
        $order_by = 'td.event_date';
        $order = 'DESC';
        //$match = " td.event_date ='" . date('Y-m-d') . "' OR td.event_date = '" . $tomorrowdate . "'";
        $match = "td.is_delete = 0 AND td.event_date >='".date('Y-m-d')."'";
        $config['total_rows'] = count($this->common_model->get_records(TBL_EVENTS . ' as td', $fields, '', '', $match, '', $rocordToShow, '', $order_by, $order, '', $where, '', '', '', '', '', $where_search));
        
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }

        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['event_data'] = $this->common_model->get_records(TBL_EVENTS . ' as td', $fields, '', '', $match, '', $rocordToShow, $data['page'], $order_by, $order, '', $where, '', '', '', '', '', $where_search);

       // echo $this->db->last_query();
        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        //$this->pagination->initialize($config);
        $data['pagination'] = $this->pagingConfig($config, $page_url);


        $data['main_content'] = '/AjaxEvents';
        $this->load->view('/AjaxEvents', $data);
    }

    public function getContactCases() {
        $redirect_link = $_SERVER['HTTP_REFERER'];

        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if ($searchtext == 'clearData') {
            $searchtext = '';
            $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) {

            $where_search = '(cc.title LIKE "%' . $searchtext . '%" )';
            $this->session->set_userdata('searchtext', $searchtext);
        } else if (!empty($session_searchtext)) {
            $searchtext1 = $this->session->userdata('searchtext');
            $where_search = '(cc.title LIKE "%' . $searchtext1 . '%" )';
        } else {
            $this->session->set_userdata('searchtext', '');
        }

        $this->load->library('pagination');
        $data['contact_view'] = $this->viewname;
        $config['base_url'] = base_url('Dashboard') . '/getContactCases';
        //$user_id =  $this->session->userdata('LOGGED_IN')['ID'];
        $data['tasksortField'] = 'cases_id';
        $data['tasksortOrder'] = 'desc';

        $rocordToShow = 5;

        $order_by = 'cc.created_date';
        $order = 'DESC';

        $table = CRM_CASES . ' as cc';
        $fields = array('cc.*,cf.file_id,cf.file_name,cf.file_path,CONCAT(l.firstname,"   ",l.lastname) as responsible_name,er.cases_type_name as incident_type_name');
        $join_table = array(CRM_CASES_FILES_MASTER . ' as cf' => 'cf.cases_related_id=cc.cases_related_id and cf.cases_status=cc.cases_status', CONTACT_MASTER . ' as cm' => 'cc.responsible=cm.contact_id',LOGIN . ' as l' => 'l.login_id=cc.responsible', TBL_CRM_CASES_TYPE . ' as er' => 'er.cases_type_id=cc.cases_type_id');
        //$where                = array('cc.is_delete' => 0,'cc.status' => 1,'cc.created_date' =>$pastonemonthdate);
        //$where = " cc.is_delete = 0 AND cc.status = 1 AND  cc.created_date BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND CURRENT_DATE()";
        $where = " cc.is_delete = 0 AND cc.status = 1";
        $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', $rocordToShow, '', $order_by, $order, '', $where_search, '', '', '1');

        $config['per_page'] = 5;
        $choice = $config["total_rows"] / $config["per_page"];

        $config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }

        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['cases_data'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', $rocordToShow, $data['page'], $order_by, $order, '', $where_search);

        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        $data['pagination'] = $this->pagingConfig($config, $page_url);

        $data['main_content'] = '/AjaxCases';
        $this->load->view('/AjaxCases', $data);
    }

    public function getEmails() {

        /*
          $table = TBL_EMAIL_PROSPECT . ' as ep';
          $fields = array('ep.email_prospect_id,ep.created_date,ep.prospect_owner_id,ep.send_to,ep.subject');
          $where = " ep.status = 1 AND ep.send_to !=''";
         */
        $data['tasksortField'] = 'comm_id';
        $data['tasksortOrder'] = 'DESC';
        $fields = array("td.*,(SELECT CONCAT(l.firstname,' ',l.lastname) FROM  blzdsk_login as l WHERE l.login_id=td.comm_sender) as sender_name,(SELECT GROUP_CONCAT(cm.contact_name) FROM blzdsk_contact_master as cm WHERE FIND_IN_SET(cm.contact_id, td.comm_receiver) > 0) as receiver_name");
        $where = array("td.is_delete" => "0");
        // $match  = " FIND_IN_SET($contact_id,td.comm_related_id) > 0 ";

        $config['per_page'] = 5;

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }
        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $rocordToShow = 5;
        //$data_email_prospect = $this->common_model->get_records($table, $fields, '', '', $where, '', $rocordToShow, $data['page'], $data['tasksortField'], $data['tasksortOrder'], '', '');

        /*
          $i = 0;
          foreach ($data_email_prospect as $data_email) {
          $data['email_data'][$i] = $data_email;
          $data['email_data'][$i]['sender_name'] = $this->getContactNamebyId($data_email['prospect_owner_id']);
          $data['email_data'][$i]['receiver_name'] = $this->getContactNamebyId($data_email['send_to']);

          $i++;
          }
         */

        $data['communication_data'] = $this->common_model->get_records(TBL_EMAIL_COMMUNICATION . ' as td', $fields, '', '', '', '', $rocordToShow, $data['page'], $data['tasksortField'], $data['tasksortOrder'], '', $where, '', '', '', '', '', '');
        //pr($data['communication_data']);
        $data['main_content'] = '/AjaxEmails';
        $this->load->view('/AjaxEmails', $data);
    }

    function getContactNamebyId($arr_contact_id) {
        $table1 = CONTACT_MASTER . ' as l';
        $match1 = "l.contact_id IN (" . $arr_contact_id . ")";
        $fields1 = array("l.contact_name");
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);

        $str_email = '';
        foreach ($user_data as $user_email) {
            $str_email.= ucwords($user_email['contact_name']) . ",";
        }
        $tmp_str_email = rtrim($str_email, ",");
        return $tmp_str_email;
    }

    /*
     * function is used to get files data in crm dashboard
     */

    function getFilesList() {

        $this->module = 'CRM';
        $data['is_crm'] = 1;
        $this->viewname = 'Filemanager';
        $data['cur_viewname'] = 'Filemanager';

        $data['project_view'] = $this->module . '/' . $this->viewname;
        // Make sure we have the correct directory
        $data['refresh'] = $data['parent'] = $directory = 'uploads/filemanager/';
        $directory = 'uploads/filemanager/';
        if (($this->input->get('dir')) && $this->input->get('dir') != '' && $this->input->get('dir') != 'uploads/filemanager' && $this->input->get('dir') != 'uploads/filemanager/' && $this->input->get('dir') != '.') {
            $directory = $this->input->get('dir') . '/';
            $data['refresh'] = $directory = $this->input->get('dir') . '/';
            $data['parent'] = dirname($directory);
        }

        $ignoreFolders = array('uploads//assets', 'uploads//css', 'uploads//custom', 'uploads//dist', 'uploads//font-awesome-4.5.0', 'uploads/filemanager//index.html');
        $filter_name = null;
        $data['images'] = array();
        // Get directories
        $directory = rawurldecode($directory);
        $directories = glob($directory . '/' . '*', GLOB_ONLYDIR);
        if (!$directories) {
            $directories = array();
        }
        // Get files
        $files = glob($directory . '/' . $filter_name . '*.{*}', GLOB_BRACE);
        if (!$files) {
            $files = array();
        }

        // Merge directories and files

        $images = array_merge($directories, $files);
        // Get total number of files and directories
        $image_total = count($images);
        //  pr($images);
        if (count($images)) {
            foreach ($images as $image) {
                if (!in_array($image, $ignoreFolders)) {
                    $ext = pathinfo($image, PATHINFO_EXTENSION);
                    if ($ext != NULL) {
                        $data['images'][] = array('name' => basename($image), 'path' => $directory, 'href' => base_url($directory . basename($image)), 'type' => 'image', 'ext' => $ext);
                    } else {
                        $data['images'][] = array('name' => basename($image), 'path' => $directory . basename($image), 'href' => base_url($directory . basename($image)), 'type' => 'directory', 'ext' => $ext);
                    }
                    //echo $name = str_split(basename($image), 14);
                }
            }
        }
        if ($this->input->is_ajax_request()) {
            $this->load->view('widgetFiles', $data);
        } else {
            return $data;
        }
    }

    /*
     * Used to get dashboard data for monthly comparision
     */

    function getMonthlySalesData() {
        if ($this->input->is_ajax_request()) {
            $mnt = $this->input->post('month');

            //$month = 'MONTH("' . $mnt . '") and YEAR(created_date) = YEAR("' . $mnt . '") ';
        } else {
            $mnt = date('m', strtotime('+1 month'));
            //  $month = 'MONTH(CURDATE()+INTERVAL 1 MONTH) and YEAR(created_date) = YEAR(CURDATE())';
        }
        $year = date('Y');
        $cmonth = date('m');
        $currentMonthSdate = $year . '-' . $cmonth . '-1';
        $currentMonthEdate = $year . '-' . $cmonth . '-31';
        $nextMonthSdate = $year . '-' . $mnt . '-1';
        $nextMonthEdate = $year . '-' . $mnt . '-31';

        $login_id = $this->session->userdata('LOGGED_IN')['ID'];
        $table = PROSPECT_MASTER . ' as pm';
//$match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND pm.prospect_assign = ".$login_id;
        $match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND pm.created_by = " . $login_id . " and created_date>='$currentMonthSdate' and created_date<='$currentMonthEdate'";
        $fields = array("estimate_prospect_worth");
        $currentMonthData = $nextMonthData = array();
        $current_month_data = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $match);
        // echo $this->db->last_query();
        if (count($current_month_data) > 0) {
            foreach ($current_month_data as $cdata) {
                $currentMonthData[] = (float) $cdata['estimate_prospect_worth'];
            }
        }
        $matchNext = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND pm.created_by = " . $login_id . " and created_date>='$nextMonthSdate' and created_date<='$nextMonthEdate'";
        $fields = array("estimate_prospect_worth");
        $next_month_data = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $matchNext);
        // echo $this->db->last_query();
        if (count($next_month_data) > 0) {
            foreach ($next_month_data as $ndata) {
                $nextMonthData[] = (float) $ndata['estimate_prospect_worth'];
            }
        }
        $data['currentMonthData'] = count($currentMonthData > 0) ? implode(',', $currentMonthData) : '';
        $data['nextMonthData'] = count($nextMonthData > 0) ? implode(',', $nextMonthData) : '';
        $data['currentMonthName'] = date("F", mktime(null, null, null, $cmonth));
        $data['nextMonthName'] = date("F", mktime(null, null, null, $mnt));

        if ($this->input->is_ajax_request()) {

            $passArr = array('currMonthData' => $currentMonthData, 'nextMonthData' => $nextMonthData, 'currMonth' => $data['currentMonthName'], 'nextMonth' => $data['nextMonthName']);
            echo json_encode($passArr);
        } else {

            return $data;
        }
    }

    function getSalesForecastData() {
        $maxDays = date('t');
        $currency = $this->config->item('currency');
        $currentDayOfMonth = date('j');
        $passedDays = $maxDays - $currentDayOfMonth;
        $totalAmnt = 0;
        $finalAmnt = 0;
        $perAmt = 0;
        $table = PROSPECT_MASTER . ' as pm';
        $login_id = $this->session->userdata('LOGGED_IN')['ID'];
//$match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND pm.prospect_assign = ".$login_id;
        $match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND pm.created_by = " . $login_id;
        $fields = array(" SUM(`estimate_prospect_worth`)  AS createdAccount");
        $sale_aacount_count = $this->common_model->get_records($table, $fields, '', '', $match);
        if (count($sale_aacount_count) > 0) {
            $totalAmnt = $sale_aacount_count[0]['createdAccount'];

            if ($totalAmnt > 0) {


                $finalAmnt = ($totalAmnt * $maxDays) / $passedDays;
            }
            $match = "sts.is_delete=0 AND  sts.status=1  AND sts.login_id = " . $login_id;
            $fields = array("sts.target , c.currency_symbol AS currency_symbol , sts.country_id");
            $params['join_tables'] = array(COUNTRIES . ' as c' => 'c.country_id = sts.country_id');
            $params['join_type'] = 'left';
            $data['sales_target'] = $this->common_model->get_records(SALES_TARGET_SETTINGS . ' as sts', $fields, $params['join_tables'], $params['join_type'], $match, '');

            if (isset($sale_aacount_count[0]['createdAccount'])) {
                $salesAmmount = $sale_aacount_count[0]['createdAccount'];
            }



            if (isset($data['sales_target'][0]['country_id'])) {
                $country_code = getCourrencyCode($data['sales_target'][0]['country_id']);
            }

//	pr($country_code[0]['currency_code']); exit;
            if ($finalAmnt > 0) {

//                $converted_amount = helperConvertCurrency($salesAmmount, $country_code[0]['currency_code'], $currency);
//                $converted_amount_target = helperConvertCurrency($finalAmnt, $country_code[0]['currency_code'], $currency);
//                $data['converted_amount'] = $converted_amount;
//                $data['converted_amount_target'] = $converted_amount_target;
//                $data['sales_difference'] = $finalAmnt - $salesAmmount;
//                $data['sales_amount_by_user'] = $salesAmmount;
//            }
//            if (!empty($data['sales_target'][0]['target'])) {
//                if ($data['converted_amount'] > 0) {
//                    $data['salesForecast'] = round(($data['converted_amount'] * 100) / $data['converted_amount_target'], 2);
//                } else {
//                    $data['salesForecast'] = 0;
//                }
//            if ($finalAmnt > 0) {
//                    $data['salesForecast'] = round(($data['converted_amount'] * 100) / $data['converted_amount_target'], 2);
//                } else {
//                    $data['salesForecast'] = 0;
//                }
                $perAmt = ($finalAmnt * 100) / $totalAmnt;
                $data['salesForecast'] = $perAmt;
                return $data;
            }
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : salesTargetProgressGraph
      @Input 	:
      @Output	:
      @Date   : 06/04/2016
     */

    function salesTargetProgressGraph() {

        $login_id = $this->session->userdata['LOGGED_IN']['ID'];
        $currencyData = $this->getDefaultCurrency();
        if (count($currencyData) > 0) {
            $currency = $currencyData['currency_code'];
            $symbol = $currencyData['symbol'];
        } else {
            $currency = $this->config->item('currency');
            $symbol = '$';
        }
        $salesAmmount = "";
        $target = "";
        $converted_amount_target = "";
        $converted_amount_target = "";
        $country_code = "";
        $salesAmmountArr = array();
        $data['converted_amount'] = "";
        $data['converted_amount_target'] = "";
        $data['sales_difference'] = "";
        $data['sales_amount_by_user'] = "";
//        $table = PROSPECT_MASTER . ' as pm';
////$match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND pm.prospect_assign = ".$login_id;
//        $match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND pm.created_by = " . $login_id . ' and MONTH(CURDATE()) and YEAR(created_date) = YEAR(CURDATE())';
//        $fields = array(" SUM(`estimate_prospect_worth`)  AS createdAccount");
//        $sale_aacount_count = $this->common_model->get_records($table, $fields, '', '', $match);
//        

        $table = PROSPECT_MASTER . ' as pm';
        $match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND pm.created_by = " . $login_id . ' and MONTH(CURDATE()) and YEAR(pm.created_date) = YEAR(CURDATE())';
        // $match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 and MONTH(CURDATE()) and YEAR(pm.created_date) = YEAR(CURDATE())";
        $fields = array("em.value  AS createdAccount ,c.currency_code,em.estimate_id");
        $params['join_tables'] = array(ESTIMATE_MASTER . ' as em' => 'em.estimate_id= pm.estimate_prospect_worth', COUNTRIES . ' as c' => 'c.country_id = em.country_id_symbol');
        $params['join_type'] = 'left';
        $sale_aacount_count = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '');
        //  pr($sale_aacount_count);
        // get Login user's sales Target
        $match = "sts.is_delete=0 AND  sts.status=1  AND sts.login_id = " . $login_id;
        $fields = array("sts.target , c.currency_symbol AS currency_symbol , sts.country_id");
        $params['join_tables'] = array(COUNTRIES . ' as c' => 'c.country_id = sts.country_id');
        $params['join_type'] = 'left';
        $data['sales_target'] = $this->common_model->get_records(SALES_TARGET_SETTINGS . ' as sts', $fields, $params['join_tables'], $params['join_type'], $match, '');
        if (count($sale_aacount_count) > 0) {
            foreach ($sale_aacount_count as $row) {
                if ($row['currency_code'] != $currency) {
                    $salesAmmountArr[] = helperConvertCurrency($row['createdAccount'], $row['currency_code'], $currency);
                } else {
                    $salesAmmountArr[] = $row['createdAccount'];
                }
            }
            $salesAmmount = array_sum($salesAmmountArr);
        } else {
            $salesAmmount = 0;
        }

        if (isset($data['sales_target'][0]['target'])) {
            $target = $data['sales_target'][0]['target'];
        }

        if (isset($data['sales_target'][0]['country_id'])) {
            $country_code = getCourrencyCode($data['sales_target'][0]['country_id']);
        }
        if ($target != "" && count($country_code)>0) {
            // echo $currency;
            if ($country_code[0]['currency_code'] != $currency) {
                $converted_amount = helperConvertCurrency($salesAmmount, $country_code[0]['currency_code'], $currency);
                $converted_amount_target = helperConvertCurrency($target, $country_code[0]['currency_code'], $currency);
            } else {
                $converted_amount = $salesAmmount;
                $converted_amount_target = $target;
            }
            //  echo $converted_amount_target;
            //echo $country_code[0]['currency_code'];
            $data['converted_amount'] = number_format($converted_amount, 2, '.', '');
            $data['converted_amount_target'] = number_format($converted_amount_target, 2, '.', '');
            $data['sales_difference'] = number_format($converted_amount_target - $converted_amount, 2, '.', '');
            $data['sales_amount_by_user'] = number_format($converted_amount, 2, '.', '');
        }
        if ($data['sales_difference'] == 0) {
            $data['sales_difference'] = $target;
        }
        if (!empty($data['sales_target'][0]['target'])) {
            if ($data['converted_amount'] > 0) {
                $data['sales_percentage'] = round(($data['converted_amount'] * 100) / $data['converted_amount_target'], 2);
            } else {
                $data['sales_percentage'] = 0;
            }

//  $milistone_per += round(($task_per * $per_devide) / 100, 2);
//echo "PERCENTEAHE :".$data['sales_percentage']; exit;
        } else {
            $data['sales_percentage'] = 0;
        }
        $data['symbol'] = $symbol;

        return $data;
    }

    /*
     * function is used to get default currency code and symbol
     */

    function getDefaultCurrency() {
        $gensettingWhere = "config_key='general_settings'";
        $defaultDashboard1 = $this->common_model->get_records(CONFIG, array('value'), '', '', $gensettingWhere);
        $generalSettings = (array) json_decode($defaultDashboard1[0]['value']);

        if (isset($generalSettings['default_currency']) && !empty($generalSettings['default_currency'])) {
            $table = COUNTRIES . ' as c';
            $match = "c.country_id=" . $generalSettings['default_currency'];
            $fields = array("c.currency_code,c.currency_symbol");
            $sale_aacount_count = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['symbol'] = $sale_aacount_count[0]['currency_symbol'];
            $data['currency_code'] = $sale_aacount_count[0]['currency_code'];
            return $data;
        }
    }

}
