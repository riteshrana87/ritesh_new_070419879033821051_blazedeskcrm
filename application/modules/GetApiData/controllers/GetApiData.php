<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GetApiData extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->load->library(array('form_validation', 'Session'));
        require_once(APPPATH.'libraries/payment/lib/Stripe.php');
    }

    //view of indexpage
    public function index()
    {
        $country_id  = $this->input->get('country_id');
        $company_name = $this->input->get('term');
        $apiKey = '4nhytG8E87rHkUw';
        $term = $company_name;
        $country = $country_id;
        $url = 'https://inquiry.creditandcollection.nl/api/companies?term='.$term.'&country='.$country.'';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'BLAZEDESK:' . $apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result);
        $data='';
        //pr($result->list);exit;
        if(!empty($result)){
            foreach($result->list as $data_list){
                $company_id = $data_list->id;
                $data[]=array('id'=>$company_id,'label'=>$data_list->name,'address'=>$data_list->address,'zipcode'=>$data_list->zipcode,'city'=>$data_list->city,'reg_number'=>$data_list->reg_number,'est_number'=>$data_list->est_number);
            }
        }else{
            echo "no record found";
        }

        echo json_encode($data);

    }

    public function CompanyInquiry()
    {
        $session_data = $this->session->userdata('LOGGED_IN');
        if ($this->input->post('token')) {
            $error = '';
            try {
                /*useing for live */
                //Stripe::setApiKey('sk_live_xawxh42k2wMPlffgRt74ckCd');
                /*useing for test*/
                Stripe::setApiKey(STRIPE_KEY_SK);
                //Stripe::setApiKey('sk_test_ELazZ12erwvhGaSFfpzaAAmB');

                $customer = Stripe_Charge::create(array(
                    "source" => $this->input->post('token'),
                    "amount" => round(20*100),
                    "receipt_email" => $session_data['EMAIL'],
                    "currency" => 'usd'
                ));

            } catch (Exception $e) {
                $error .= $e->getMessage();
            }
        }
        if (!$error) {
            $country_id = $this->input->post('country_id');
            $company_name = $this->input->post('company_name');
            $com_reg_number = $this->input->post('com_reg_number');
            $company_id = $this->input->post('company_id_data');
            //$report_types = $this->input->post('report_types');
            $report_types = 'D44CI702';
            $language = $this->input->post('language');

            $apiKey = '4nhytG8E87rHkUw';
            $url = 'https://inquiry.creditandcollection.nl/api/inquiries';
            $fields = json_encode(array('company_id' => $company_id, 'product' => $report_types, 'language' => $language));
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, 'BLAZEDESK:' . $apiKey);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $data_list = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($data_list);
            // pr($result);exit;
            $data = "";
            if(isset($result->item)){
                $data = $result->item;
            }
            if(!empty($data)) {
                echo json_encode(array('inquery' => $data->id,'report_type' => $report_types));
                die();
            }else{
                if($report_types == 'D44CI102'){
                    $msg = lang('create_international_credit_report');
                }elseif($report_types == 'D44CI301'){
                    $msg = lang('create_chamber_of_commerce_extract');
                }elseif($report_types == 'D44CI302'){
                    $msg = lang('create_Basic_report');
                }elseif($report_types == 'D44CI501'){
                    $msg = lang('create_annual_report');
                }elseif($report_types == 'D44CI701'){
                    $msg = lang('create_credit_report');
                }elseif($report_types == 'D44CI702'){
                    $msg = lang('create_credit_report_plus');
                }elseif($report_types == 'D44CI704'){
                    $msg = lang('create_Rating_report');
                }elseif($report_types == 'D44CI707'){
                    $msg = lang('create_credit_report_plus_ips');
                }
                echo json_encode(array('error' => $msg));
                die();
            }

        }else{
            echo json_encode(array('error' => $error));
            die();
        }

    }
    public function GetCompanyReport() {
        
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $data = array();
            $data['company_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'crm');
            $data['main_content'] = '/CrmCompany';
            $data['drag'] = true;
            $table_branch_master = BRANCH_MASTER . ' as bm';
            $match_branch_master = "bm.status=1";
            $fields_branch_master = array("bm.branch_id,bm.branch_name");
            $data['branch_data'] = $this->common_model->get_records($table_branch_master, $fields_branch_master, '', '', $match_branch_master);
            $table1 = COUNTRIES . ' as c';
            $fields1 = array("c.country_id", "c.country_name", "c.country_code");
            $data['country'] = $this->common_model->get_records($table1, $fields1);

            $redirect_link = $this->input->post('redirect_link');

            $this->load->view('GetCompanyReport', $data);

        }
        
    }

    public function CompanyReport($inquery_data,$report_types){
        //ob_start();

//        $inquery_data = $this->input->post('inquery_data');
//        $report_types = $this->input->post('report_type');
//        sleep(10);
        if(!empty($inquery_data)) {
            $inquiry_id = $inquery_data;
            $apiKey = '4nhytG8E87rHkUw';
            $url = 'https://inquiry.creditandcollection.nl/api/inquiries/' . $inquiry_id . '/report';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERPWD, 'BLAZEDESK:' . $apiKey);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($result);
            //pr($result);exit;
            $review_data = "";
            if (isset($result->review)) {
                $review_data = $result->review;
            }
            $contact_info_data = "";
            if (isset($result->contact_info)) {
                $contact_info_data = $result->contact_info;
            }
            $address_info_data = "";
            if (isset($result->contact_info->address_office)) {
                $address_info_data = $result->contact_info->address_office;
            }

            $address_postal = "";
            if (isset($result->contact_info->address_postal)) {
                $address_postal = $result->contact_info->address_postal;
            }
            $registration = "";
            if (isset($result->registration)) {
                $registration = $result->registration;
            }
            $registration_dates = "";
            if (isset($result->registration->dates)) {
                $registration_dates = $result->registration->dates;
            }
            $activities = "";
            if (isset($result->registration->dates)) {
                $activities = $result->activities;
            }

            $relations = "";
            if (isset($result->relations->same_address)) {
                $relations = $result->relations->same_address;
            }
            $bank_accounts = "";
            if (isset($result->bank_accounts->items)) {
                $bank_accounts = $result->bank_accounts->items;
            }

            $real_estate = "";
            if (isset($result->real_estate->items)) {
                $real_estate = $result->real_estate->items;
            }
            $real_estate_details_office_address = "";
            if (isset($result->real_estate->details_office_address)) {
                $real_estate_details_office_address = $result->real_estate->details_office_address;
            }
            $employees = "";
            if (isset($result->employees->items)) {
                $employees = $result->employees->items;
            }
            $payments = "";
            if (isset($result->payments)) {
                $payments = $result->payments;
            }
            $key_figures = "";
            if (isset($result->key_figures->items)) {
                $key_figures = $result->key_figures->items;
            }
            $score = "";
            if (isset($review_data->score)) {
                $score = $review_data->score;
            }
            $limit_advice = "";
            if (isset($review_data->limit_advice)) {
                $limit_advice = $review_data->limit_advice;
            }
            $rating = "";
            if (isset($review_data->rating)) {
                $rating = $review_data->rating;
            }
            $risk = "";
            if (isset($review_data->risk)) {
                $risk = $review_data->risk;
            }
            $specification = "";
            if (isset($review_data->specification)) {
                $specification = $review_data->specification;
            }
            $report_data['review_data_list'] = array(
                'score' => $score,
                'limit_advice' => $limit_advice,
                'rating' => $rating,
                'risk' => $risk,
                'specification' =>$specification
            );
            $con_name = "";
            if (isset($contact_info_data->name)) {
                $con_name = $contact_info_data->name;
            }

            $tel_number = "";
            if (isset($contact_info_data->tel_number)) {
                $tel_number = $contact_info_data->tel_number;
            }
            $fax_number = "";
            if (isset($contact_info_data->fax_number)) {
                $fax_number = $contact_info_data->fax_number;
            }
            $mob_number = "";
            if (isset($contact_info_data->mob_number)) {
                $mob_number = $contact_info_data->mob_number;
            }
            $mail = "";
            if (isset($contact_info_data->mail)) {
                $mail = $contact_info_data->mail;
            }
            $website = "";
            if (isset($contact_info_data->website)) {
                $website = $contact_info_data->website;
            }

            $con_address = "";
            if (isset($address_info_data->address)) {
                $con_address = $address_info_data->address;
            }
            $con_zip = "";
            if (isset($address_info_data->zipcode)) {
                $con_zip = $address_info_data->zipcode;
            }
            $con_city = "";
            if (isset($address_info_data->city)) {
                $con_city = $address_info_data->city;
            }
            $con_country = "";
            if (isset($address_info_data->country)) {
                $con_country = $address_info_data->country;
            }
            $con_address1 = "";
            if (isset($address_postal->address)) {
                $con_address1 = $address_info_data->address;
            }
            $con_zipcode1 = "";
            if (isset($address_info_data->zipcode)) {
                $con_zipcode1 = $address_info_data->zipcode;
            }
            $con_city1 = "";
            if (isset($address_info_data->city)) {
                $con_city1 = $address_info_data->city;
            }
            $con_country1 = "";
            if (isset($address_info_data->country)) {
                $con_country1 = $address_info_data->country;
            }

            $report_data['contact_info'] = array(
                'name' => $con_name,
                'address' => $con_address,
                'zipcode' => $con_zip,
                'city' => $con_city,
                'country' => $con_country,
                'address1' => $con_address1,
                'zipcode1' => $con_zipcode1,
                'city1' => $con_city1,
                'country1' => $con_country1,
                'tel_number' => $tel_number,
                'fax_number' => $fax_number,
                'mob_number' => $mob_number,
                'mail' => $mail,
                'website' => $website
            );
            //pr($report_data['contact_info']);exit;
            $reg_office = "";
            if (isset($registration->reg_office)) {
                $reg_office = $registration->reg_office;
            }
            $legal_started = "";
            if (isset($registration->legal_started)) {
                $legal_started = $registration->legal_started;
            }
            $registration = "";
            if (isset($registration_dates->registration)) {
                $registration = $registration_dates->registration;
            }
            $memorandum = "";
            if (isset($registration_dates->memorandum)) {
                $memorandum = $registration_dates->memorandum;
            }
            $settlement = "";
            if (isset($registration_dates->settlement)) {
                $settlement = $registration_dates->settlement;
            }
            $modification = "";
            if (isset($registration_dates->modification)) {
                $modification = $registration_dates->modification;
            }
            $legal_since = "";
            if (isset($registration_dates->legal_since)) {
                $legal_since = $registration_dates->legal_since;
            }
            $reg_type = "";
            if (isset($result->registration->reg_type)) {
                $reg_type = $result->registration->reg_type;
            }
            $reg_number = "";
            if (isset($result->registration->reg_number)) {
                $reg_number = $result->registration->reg_number;
            }
            $branch_number = "";
            if (isset($result->registration->branch_number)) {
                $branch_number = $result->registration->branch_number;
            }
            $vat_number = "";
            if (isset($result->registration->vat_number)) {
                $vat_number = $result->registration->vat_number;
            }
            $vat_number_eu = "";
            if (isset($result->registration->vat_number_eu)) {
                $vat_number_eu = $result->registration->vat_number_eu;
            }
            $legal_current = "";
            if (isset($result->registration->legal_current)) {
                $legal_current = $result->registration->legal_current;
            }
            $active = "";
            if (isset($result->registration->active)) {
                $active = $result->registration->active;
            }
            $capital = "";
            if (isset($result->registration->capital)) {
                $capital = $result->registration->capital;
            }
            $issued = "";
            if (isset($capital->issued)) {
                $issued = $capital->issued;
            }
            $paidup = "";
            if (isset($capital->paidup)) {
                $paidup = $capital->paidup;
            }


            $report_data['registration'] = array(
                'reg_type' => $reg_type,
                'reg_number' => $reg_number,
                'branch_number' => $branch_number,
                'reg_office' => $reg_office,
                'vat_number' => $vat_number,
                'vat_number_eu' => $vat_number_eu,
                'legal_current' => $legal_current,
                'legal_started' => $legal_started,
                'active' => $active,
                'registration' => $registration,
                'memorandum' => $memorandum,
                'settlement' => $settlement,
                'modification' => $modification,
                'legal_since' => $legal_since,
                'paidup' => $paidup,
                'issued' => $issued
            );
            $sbi = "";
            if (isset($activities->sbi)) {
                $sbi = $activities->sbi;
            }

            $exporter = "";
            if (isset($activities->exporter)) {
                $exporter = $activities->exporter;
            }
            $importer = "";
            if (isset($activities->importer)) {
                $importer = $activities->importer;
            }

            $branche_organisations = "";
            if (isset($activities->branche_organisations)) {
                $branche_organisations = $activities->branche_organisations;
            }
            $goal = "";
            if (isset($activities->goal)) {
                $goal = $activities->goal;
            }

            $report_data['activitie_data'] = array(
                'exporter' => $exporter,
                'importer' => $importer,
                'goal' => $goal,
                'branche_organisations' => $branche_organisations,
            );

            $activities_data = array();
            if (!empty($sbi)) {
                foreach ($sbi as $sbi_list) {
                    $activities_list['code'] = $sbi_list->code;
                    $activities_list['text'] = $sbi_list->text;
                    $activities_data[] = $activities_list;
                }
            }
            $report_data['activities'] = $activities_data;

            $relations_data_report = array();
            if (!empty($relations)) {
                foreach ($relations as $relations_list) {
                    $relations_data['relations_name'] = $relations_list->name;
                    $relations_data['address'] = $relations_list->address;
                    $relations_data['reg_number'] = $relations_list->reg_number;
                    $relations_data['zipcode'] = $relations_list->zipcode;
                    $relations_data['city'] = $relations_list->city;
                    $relations_data['country'] = $relations_list->country;

                    $relations_data_report[] = $relations_data;
                }
            }
            $report_data['relations'] = $relations_data_report;

            $bank_accounts_details = array();
            if (!empty($bank_accounts)) {
                foreach ($bank_accounts as $bank_accounts_list) {
                    $bank_accounts_data['name'] = $bank_accounts_list->name;
                    $bank_accounts_data['number'] = $bank_accounts_list->number;
                    $bank_accounts_data['bic'] = $bank_accounts_list->bic;
                    $bank_accounts_details[] = $bank_accounts_data;
                }
            }
            $report_data['bank_accounts'] = $bank_accounts_details;

            $real_estate_details = array();
            if (!empty($real_estate)) {
                foreach ($real_estate as $real_estate_list) {
                    $real_estate_data['property'] = $real_estate_list->property;
                    $real_estate_data['surface'] = $real_estate_list->surface;
                    $real_estate_data['amount'] = $real_estate_list->amount;
                    $real_estate_data['code'] = $real_estate_list->code;
                    $real_estate_data['owner'] = $real_estate_list->owner;
                    $real_estate_data['description'] = $real_estate_list->description;
                    $real_estate_data['checked'] = $real_estate_list->checked;
                    $real_estate_data['private'] = $real_estate_list->private;
                    $real_estate_details[] = $real_estate_data;
                }
            }
            $report_data['real_estate'] = $real_estate_details;

            $surface = "";
            if (isset($real_estate_details_office_address->surface)) {
                $surface = $real_estate_details_office_address->surface;
            }
            $purpose = "";
            if (isset($real_estate_details_office_address->purpose)) {
                $purpose = $real_estate_details_office_address->purpose;
            }
            $building_construction_year = "";
            if (isset($real_estate_details_office_address->building_construction_year)) {
                $building_construction_year = $real_estate_details_office_address->building_construction_year;
            }
            $building_status = "";
            if (isset($real_estate_details_office_address->building_status)) {
                $building_status = $real_estate_details_office_address->building_status;
            }
            $building_is_under_investigation = "";
            if (isset($real_estate_details_office_address->building_is_under_investigation)) {
                $building_is_under_investigation = $real_estate_details_office_address->building_is_under_investigation;
            }
            $report_data['real_estate_details_office_address'] = array(
                'surface' => $surface,
                'purpose' => $purpose,
                'building_construction_year' => $building_construction_year,
                'building_status' => $building_status,
                'building_is_under_investigation' => $building_is_under_investigation,
            );

            $employees_details = array();
            if (!empty($employees)) {
                foreach ($employees as $employees_list) {
                    $employees_data['year'] = $employees_list->year;
                    $employees_data['total'] = $employees_list->total;

                    $employees_details[] = $employees_data;
                }
            }
            //pr($employees_details);exit;
            $report_data['employees'] = $employees_details;

            $score = "";
            if (isset($payments->score)) {
                $score = $payments->score;
            }
            $description = "";
            if (isset($payments->description)) {
                $description = $payments->description;
            }
            $report_data['payments'] = array(
                'score' => $score,
                'description' => $description,
            );

            $key_figures_details = array();
            if (!empty($key_figures)) {
                foreach ($key_figures as $key_figures_list) {
                    $year = "";
                    if (isset($key_figures_list->year)) {
                        $year = $key_figures_list->year;
                    }
                    $quick_ratio = "";
                    if (isset($key_figures_list->quick_ratio)) {
                        $quick_ratio = $key_figures_list->quick_ratio;
                    }
                    $current_ratio = "";
                    if (isset($key_figures_list->current_ratio)) {
                        $current_ratio = $key_figures_list->current_ratio;
                    }
                    $working_capital_div_assets = "";
                    if (isset($key_figures_list->working_capital_div_assets)) {
                        $working_capital_div_assets = $key_figures_list->working_capital_div_assets;
                    }
                    $capandres_div_assets = "";
                    if (isset($key_figures_list->capandres_div_assets)) {
                        $capandres_div_assets = $key_figures_list->capandres_div_assets;
                    }
                    $capandres_div_liabilities_debts = "";
                    if (isset($key_figures_list->capandres_div_liabilities_debts)) {
                        $capandres_div_liabilities_debts = $key_figures_list->capandres_div_liabilities_debts;
                    }
                    $solvency = "";
                    if (isset($key_figures_list->solvency)) {
                        $solvency = $key_figures_list->solvency;
                    }
                    $capandres_div_assets_fixed = "";
                    if (isset($key_figures_list->capandres_div_assets_fixed)) {
                        $capandres_div_assets_fixed = $key_figures_list->capandres_div_assets_fixed;
                    }
                    $working_capital = "";
                    if (isset($key_figures_list->working_capital)) {
                        $working_capital = $key_figures_list->working_capital;
                    }
                    $capandres = "";
                    if (isset($key_figures_list->capandres)) {
                        $capandres = $key_figures_list->capandres;
                    }
                    $capandres = "";
                    if (isset($key_figures_list->capandres)) {
                        $capandres = $key_figures_list->capandres;
                    }
                    $capandres_mutation = "";
                    if (isset($key_figures_list->capandres_mutation)) {
                        $capandres_mutation = $key_figures_list->capandres_mutation;
                    }
                    $liabilities_st_mutation = "";
                    if (isset($key_figures_list->liabilities_st_mutation)) {
                        $liabilities_st_mutation = $key_figures_list->liabilities_st_mutation;
                    }
                    $return_on_assets = "";
                    if (isset($key_figures_list->return_on_assets)) {
                        $return_on_assets = $key_figures_list->return_on_assets;
                    }
                    $return_on_equity = "";
                    if (isset($key_figures_list->return_on_equity)) {
                        $return_on_equity = $key_figures_list->return_on_equity;
                    }
                    $gross_margin = "";
                    if (isset($key_figures_list->gross_margin)) {
                        $gross_margin = $key_figures_list->gross_margin;
                    }
                    $net_margin = "";
                    if (isset($key_figures_list->net_margin)) {
                        $net_margin = $key_figures_list->net_margin;
                    }
                    $average_collection_ratio = "";
                    if (isset($key_figures_list->average_collection_ratio)) {
                        $average_collection_ratio = $key_figures_list->average_collection_ratio;
                    }
                    $average_payment_ratio = "";
                    if (isset($key_figures_list->average_payment_ratio)) {
                        $average_payment_ratio = $key_figures_list->average_payment_ratio;
                    }
                    $equity_turnover_ratio = "";
                    if (isset($key_figures_list->equity_turnover_ratio)) {
                        $equity_turnover_ratio = $key_figures_list->equity_turnover_ratio;
                    }
                    $fixed_assets_turnover_ratio = "";
                    if (isset($key_figures_list->fixed_assets_turnover_ratio)) {
                        $fixed_assets_turnover_ratio = $key_figures_list->fixed_assets_turnover_ratio;
                    }
                    $total_assets_turnover_ratio = "";
                    if (isset($key_figures_list->total_assets_turnover_ratio)) {
                        $total_assets_turnover_ratio = $key_figures_list->total_assets_turnover_ratio;
                    }
                    $stock_turnover_ratio = "";
                    if (isset($key_figures_list->stock_turnover_ratio)) {
                        $stock_turnover_ratio = $key_figures_list->stock_turnover_ratio;
                    }
                    $income = "";
                    if (isset($key_figures_list->income)) {
                        $income = $key_figures_list->income;
                    }
                    $income_result = "";
                    if (isset($key_figures_list->income_result)) {
                        $income_result = $key_figures_list->income_result;
                    }
                    $operating_result = "";
                    if (isset($key_figures_list->operating_result)) {
                        $operating_result = $key_figures_list->operating_result;
                    }
                    $business_result = "";
                    if (isset($key_figures_list->business_result)) {
                        $business_result = $key_figures_list->business_result;
                    }
                    $cashflow = "";
                    if (isset($key_figures_list->$cashflow)) {
                        $cashflow = $key_figures_list->cashflow;
                    }
                    $ebit = "";
                    if (isset($key_figures_list->ebit)) {
                        $ebit = $key_figures_list->ebit;
                    }
                    $ebitda = "";
                    if (isset($key_figures_list->ebitda)) {
                        $ebitda = $key_figures_list->ebitda;
                    }

                    $key_figures_text = "";
                    if (isset($result->key_figures->text)) {
                        $key_figures_text = $result->key_figures->text;
                    }

                    $key_figures_data['year'] = $year;
                    $key_figures_data['quick_ratio'] = $quick_ratio;
                    $key_figures_data['current_ratio'] = $current_ratio;
                    $key_figures_data['working_capital_div_assets'] = $working_capital_div_assets;
                    $key_figures_data['capandres_div_assets'] = $capandres_div_assets;
                    $key_figures_data['capandres_div_liabilities_debts'] = $capandres_div_liabilities_debts;
                    $key_figures_data['solvency'] = $solvency;
                    $key_figures_data['capandres_div_assets_fixed'] = $capandres_div_assets_fixed;
                    $key_figures_data['working_capital'] = $working_capital;
                    $key_figures_data['capandres'] = $capandres;
                    $key_figures_data['capandres_mutation'] = $capandres_mutation;
                    $key_figures_data['liabilities_st_mutation'] = $liabilities_st_mutation;
                    $key_figures_data['return_on_assets'] = $return_on_assets;
                    $key_figures_data['return_on_equity'] = $return_on_equity;
                    $key_figures_data['gross_margin'] = $gross_margin;
                    $key_figures_data['net_margin'] = $net_margin;
                    $key_figures_data['average_collection_ratio'] = $average_collection_ratio;
                    $key_figures_data['average_payment_ratio'] = $average_payment_ratio;
                    $key_figures_data['equity_turnover_ratio'] = $equity_turnover_ratio;

                    $key_figures_data['fixed_assets_turnover_ratio'] = $fixed_assets_turnover_ratio;
                    $key_figures_data['total_assets_turnover_ratio'] = $total_assets_turnover_ratio;
                    $key_figures_data['stock_turnover_ratio'] = $stock_turnover_ratio;
                    $key_figures_data['income'] = $income;
                    $key_figures_data['income_result'] = $income_result;
                    $key_figures_data['operating_result'] = $operating_result;
                    $key_figures_data['business_result'] = $business_result;
                    $key_figures_data['cashflow'] = $cashflow;
                    $key_figures_data['ebit'] = $ebit;
                    $key_figures_data['ebitda'] = $ebitda;


                    $key_figures_details[] = $key_figures_data;
                    //ishani dave

                    $key_figures_data_assets['year'] = $year;
                    $key_figures_data_assets['quick_ratio'] = $quick_ratio;
                    $key_figures_data_assets['current_ratio'] = $current_ratio;
                    $key_figures_data_assets['working_capital_div_assets'] = $working_capital_div_assets;
                    $key_figures_data_assets['capandres_div_assets'] = $capandres_div_assets;
                    $key_figures_data_assets['capandres_div_assets_fixed'] = $capandres_div_assets_fixed;
                    $key_figures_data_assets['solvency'] = $solvency;
                    $key_figures_details_assets[] = $key_figures_data_assets;
//                    $key_figures_details_year[] = $key_figures_data_year;


                }
            }
            $report_data['key_figures_text'] = $key_figures_text;
            $report_data['key_figures'] = $key_figures_details;
            $balance_sheets_details_data = array();
            if (!empty($key_figures)) {
                foreach ($key_figures as $key_figures_list) {
                    $Quick_ratio = "";
                    if (isset($key_figures_list->quick_ratio)) {
                        $Quick_ratio = $key_figures_list->quick_ratio;
                    }
                    $Working_capital = "";
                    if (isset($key_figures_list->working_capital)) {
                        $Working_capital = $key_figures_list->working_capital;
                    }
                    $balance_sheets['quick_ratio'] = $Quick_ratio;
                    $balance_sheets['working_capital'] = $Working_capital;
                    $balance_sheets_details_data[] = $balance_sheets;
                }
            }


            if (isset($result->balance_sheets->items)) {
                $balance_sheets = $result->balance_sheets->items;
            }
            //pr($balance_sheets);exit;
            $balance_sheets_details = array();
            if (!empty($balance_sheets)) {
                foreach ($balance_sheets as $balance_sheets_list) {
                    $balance_year = "";
                    if (isset($balance_sheets_list->year)) {
                        $balance_year = $balance_sheets_list->year;
                    }
                    $balance_date = "";
                    if (isset($balance_sheets_list->date)) {
                        $balance_date = $balance_sheets_list->date;
                    }

                    $balance_assets_fix_total = "";
                    if (isset($balance_sheets_list->assets_fix_total)) {
                        $balance_assets_fix_total = $balance_sheets_list->assets_fix_total;
                    }
                    $balance_assets_cur_receivable_total = "";
                    if (isset($balance_sheets_list->assets_cur_receivable_total)) {
                        $balance_assets_cur_receivable_total = $balance_sheets_list->assets_cur_receivable_total;
                    }
                    $balance_liabilities_capandres_total = "";
                    if (isset($balance_sheets_list->liabilities_capandres_total)) {
                        $balance_liabilities_capandres_total = $balance_sheets_list->liabilities_capandres_total;
                    }
                    $balance_liabilities_st_total = "";
                    if (isset($balance_sheets_list->liabilities_st_total)) {
                        $balance_liabilities_st_total = $balance_sheets_list->liabilities_st_total;
                    }
                    $balance_assets_cur_stocks = "";
                    if (isset($balance_sheets_list->assets_cur_stocks)) {
                        $balance_assets_cur_stocks = $balance_sheets_list->assets_cur_stocks;
                    }
                    $balance_assets_cur_liquid = "";
                    if (isset($balance_sheets_list->assets_cur_liquid)) {
                        $balance_assets_cur_liquid = $balance_sheets_list->assets_cur_liquid;
                    }
                    $balance_assets_fix_tangible_total = "";
                    if (isset($balance_sheets_list->assets_fix_tangible_total)) {
                        $balance_assets_fix_tangible_total = $balance_sheets_list->assets_fix_tangible_total;
                    }
                    $balance_assets_cur_total = "";
                    if (isset($balance_sheets_list->assets_cur_total)) {
                        $balance_assets_cur_total = $balance_sheets_list->assets_cur_total;
                    }
                    $balance_assets_total = "";
                    if (isset($balance_sheets_list->assets_total)) {
                        $balance_assets_total = $balance_sheets_list->assets_total;
                    }
                    $balance_liabilities_capandres_issued_capital = "";
                    if (isset($balance_sheets_list->liabilities_capandres_issued_capital)) {
                        $balance_liabilities_capandres_issued_capital = $balance_sheets_list->liabilities_capandres_issued_capital;
                    }
                    $balance_liabilities_capandres_reserves_total = "";
                    if (isset($balance_sheets_list->liabilities_capandres_reserves_total)) {
                        $balance_liabilities_capandres_reserves_total = $balance_sheets_list->liabilities_capandres_reserves_total;
                    }
                    $balance_liabilities_capandres_reserves_other = "";
                    if (isset($balance_sheets_list->liabilities_capandres_reserves_other)) {
                        $balance_liabilities_capandres_reserves_other = $balance_sheets_list->liabilities_capandres_reserves_other;
                    }
                    $balance_liabilities_debts = "";
                    if (isset($balance_sheets_list->liabilities_debts)) {
                        $balance_liabilities_debts = $balance_sheets_list->liabilities_debts;
                    }
                    $balance_liabilities_total = "";
                    if (isset($balance_sheets_list->liabilities_total)) {
                        $balance_liabilities_total = $balance_sheets_list->liabilities_total;
                    }


                    $balance_sheets_data['balance_year'] = $balance_year;
                        $balance_sheets_data['balance_date'] = $balance_date;
                    $balance_sheets_data['balance_assets_fix_total'] = $balance_assets_fix_total;
                    $balance_sheets_data['balance_assets_cur_receivable_total'] = $balance_assets_cur_receivable_total;
                    $balance_sheets_data['balance_liabilities_capandres_total'] = $balance_liabilities_capandres_total;
                    $balance_sheets_data['balance_liabilities_st_total'] = $balance_liabilities_st_total;
                    $balance_sheets_data['balance_assets_cur_stocks'] = $balance_assets_cur_stocks;
                    $balance_sheets_data['balance_assets_cur_liquid'] = $balance_assets_cur_liquid;
                    $balance_sheets_data['balance_assets_fix_tangible_total'] = $balance_assets_fix_tangible_total;
                    $balance_sheets_data['balance_assets_cur_total'] = $balance_assets_cur_total;
                    $balance_sheets_data['balance_assets_total'] = $balance_assets_total;
                    $balance_sheets_data['balance_liabilities_capandres_issued_capital'] = $balance_liabilities_capandres_issued_capital;
                    $balance_sheets_data['balance_liabilities_capandres_reserves_total'] = $balance_liabilities_capandres_reserves_total;
                    $balance_sheets_data['balance_liabilities_capandres_reserves_other'] = $balance_liabilities_capandres_reserves_other;
                    $balance_sheets_data['balance_liabilities_debts'] = $balance_liabilities_debts;
                    $balance_sheets_data['balance_liabilities_total'] = $balance_liabilities_total;
                    $balance_sheets_details[] = $balance_sheets_data;
                }
            }

            $finalArray = array();
            $ii = 0;
foreach($balance_sheets_details as $balance_sheets_detail)
{
    $finalArray[] = $balance_sheets_detail;
    $finalArray[$ii]['quick_ratio'] = $balance_sheets_details_data[$ii]['quick_ratio'];
    $finalArray[$ii]['working_capital'] = $balance_sheets_details_data[$ii]['working_capital'];
    $ii++;
}

            $size = count($finalArray);
            for($i=0; $i<($size-1); $i++) {
                $pesentageCount = ($finalArray[$i]['balance_assets_fix_total'] * 100) / $finalArray[$i+1]['balance_assets_fix_total'];
                $pesentageValue  = ($pesentageCount - 100);
                $finalArray[$i]['difference'] = number_format($pesentageValue, 2, '.', '');


                $BalanceAssetsPesentageCount = ($finalArray[$i]['balance_assets_cur_receivable_total'] * 100) / $finalArray[$i+1]['balance_assets_cur_receivable_total'];
                $BalanceAssetsPesentageValue  = ($BalanceAssetsPesentageCount - 100);
                $finalArray[$i]['assets_cur_receivable_difference'] = number_format($BalanceAssetsPesentageValue, 2, '.', '');


                $CapandresTotalPesentageCount = ($finalArray[$i]['balance_liabilities_capandres_total'] * 100) / $finalArray[$i+1]['balance_liabilities_capandres_total'];
                $CapandresTotalPesentageValue  = ($CapandresTotalPesentageCount - 100);
                $finalArray[$i]['CapandresTotal'] = number_format($CapandresTotalPesentageValue, 2, '.', '');

                $StTotalPesentageCount = ($finalArray[$i]['balance_liabilities_st_total'] * 100) / $finalArray[$i+1]['balance_liabilities_st_total'];
                $StTotalPesentageValue  = ($StTotalPesentageCount - 100);
                $finalArray[$i]['StTotal'] = number_format($StTotalPesentageValue, 2, '.', '');

                $QuickRatioPesentageCount = ($finalArray[$i]['quick_ratio'] * 100) / $finalArray[$i+1]['quick_ratio'];
                $QuickRatioPesentageValue  = ($QuickRatioPesentageCount - 100);
                $finalArray[$i]['QuickRatioTotal'] = number_format($QuickRatioPesentageValue, 2, '.', '');

                $WorkingCapitalPesentageCount = ($finalArray[$i]['working_capital'] * 100) / $finalArray[$i+1]['working_capital'];
                $WorkingCapitalPesentageValue  = ($WorkingCapitalPesentageCount - 100);
                $finalArray[$i]['WorkingCapitalDifference'] = number_format($WorkingCapitalPesentageValue, 2, '.', '');

            }
            $balance_sheets_text = "";
            if (isset($result->balance_sheets->text)) {
                $balance_sheets_text = $result->balance_sheets->text;
            }

            $report_data['balance_sheets_text'] = $balance_sheets_text;
            //pr($finalArray);exit;
            $report_data['balance_sheets'] = $finalArray;
           // pr($report_data['balance_sheets']);exit;


            //ishani dave
            /*employees chart image */
            $year=array();
            foreach ($employees_details as $key => $employees) {
                $employeearr[$employees['year']]  = $employees['total'];
                $year[] = $employees['year'];
            }
            $emp['categories']="'".implode(",", $year)."'";

            $data['emp'] = $employeearr;
            $emp_list = $data['emp'];

            $dirPath = $this->config->item('directory_root') . "application/modules/GetApiData";
            $outputPath = $this->config->item('directory_root') . "uploads/mediagenerated";
            $phantomPath = $this->config->item('directory_root') . "uploads/phantomjs";

            //$monthname = ['2016', '2015', '2014', '2013', '2012'];

            $dd='';
            $high=array();
            $high['chart']['type'] = "column";
            $high['credits']['enabled'] = false;
            $high['title']['text'] = "";
            $high['subtitle']['text'] = "";
            $high['xAxis']['categories'] = $year;
            $high['yAxis']['title']['text'] = "";
            $high['yAxis']['title']['stackLabels']['enabled'] =false;
            $high['yAxis']['title']['stackLabels']['style']['fontWeight'] ='bold';
            $high['yAxis']['title']['stackLabels']['style']['color'] ="(Highcharts.theme && Highcharts.theme.textColor) || 'gray'";

            $high['legend']['shadow'] = false;

            $high['tooltip']['headerFormat']='<b>{point.x}</b><br/>';
            $high['tooltip']['pointFormat']='Total: {point.stackTotal}';
            $high['plotOptions']['column']['stacking']='normal';
            $high['plotOptions']['column']['dataLabels']['enabled']=false;
            $high['plotOptions']['column']['dataLabels']['color']="white";

            $high['plotOptions']['column']['dataLabels']['style']['textShadow']='0 0px 0px white';

            $tempArray = array();
            foreach ($emp_list as $key => $monthVal) {
                $tempArray[]=(int)$monthVal;
            }

            $high['series'][] = array('name' => 'Total','data' => $tempArray);
            $t = time();
            $chartName = 'company_report_' .$t;

            $report_data['chartName'] = $chartName;

            $myfile = fopen($outputPath . "/inrep_$chartName.json", "w") or die("Unable to open file!");
            $txt = json_encode($high);
            fwrite($myfile, $txt);
            fclose($myfile);

            $command = "/usr/local/bin/phantomjs $phantomPath/highcharts-convert.js -infile  " . $outputPath . "/inrep_$chartName.json -outfile " . $outputPath . "/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";


            exec($command);
            //end

            //key figures graph

            $key_year = array();
            foreach ($key_figures_details_assets as $key => $result_assets) {
                $key_year[] = $result_assets['year'];
            }

            //assets graph
            $working_capital_div_assets_Arr=array();
            foreach ($key_figures_details_assets as $key => $key_figures ) {
                $array_value = $key_figures['working_capital_div_assets'];
                $array_value1 = $key_figures['capandres_div_assets'];
                $array_value2 = $key_figures['capandres_div_assets_fixed'];
                $array_value3 = $key_figures['solvency'];
                $working_capital_div_assets_Arr['Working capital / Total assets'][$key_figures['year']]=  $array_value;
                $working_capital_div_assets_Arr['Equity / Total assets'][$key_figures['year']]=  $array_value1;
                $working_capital_div_assets_Arr['Equity / Assets'][$key_figures['year']]=  $array_value2;
                $working_capital_div_assets_Arr['Balance sheet / Debt'][$key_figures['year']]=  $array_value3;
            }

            $ratio_Arr=array();
            foreach ($key_figures_details_assets as $key => $key_figures ) {
                //pr(array_values($key_year)); exit;
                $array_value = $key_figures['quick_ratio'];
                $array_value1 = $key_figures['current_ratio'];
                //$keyfiguresarr[$key_name][$key_figures['year']] = $keyfiguresarr[$key_figures][$key_figures['year']] ;
                $ratio_Arr['Quick Ratio'][$key_figures['year']]=  $array_value;
                $ratio_Arr['Current Ratio'][$key_figures['year']]=  $array_value1;

            }
            // pr($ratio_Arr); exit;

            $data['key_figures'] = $working_capital_div_assets_Arr;
            $key_figures = $data['key_figures'];


            $high = array();
            $high['chart']['type'] = "column";
            $high['title']['text'] = '';
            $high['xAxis']['categories'] = $key_year;
            $high['yAxis']['title']['text'] = '';
            $high['yAxis']['title']['stackLabels']['enabled'] = true;
            $high['yAxis']['title']['stackLabels']['style']['fontWeight'] = 'bold';
            $high['yAxis']['title']['stackLabels']['style']['color'] = "(Highcharts.theme && Highcharts.theme.textColor) || 'gray'";
            $high['credits']['enabled'] = false;
            $high['legend']['shadow'] = false;
            $high['tooltip']['headerFormat'] = '<b>{point.x}</b><br/>';
            $high['tooltip']['pointFormat'] = '{series.name}: {point.y}<br/>';
            $high['tooltip']['shared']=true;
            $high['plotOptions']['column']['pointPadding'] = '0.2';
            $high['plotOptions']['column']['dataLabels']['enabled'] = false;
            $high['plotOptions']['column']['dataLabels']['color'] = "(Highcharts.theme && Highcharts.theme.textColor) || 'white'";
            $high['plotOptions']['column']['dataLabels']['style']['textShadow'] = '0 0px 0px black';

            foreach ($key_figures as $key => $value) {

                $high['series'][] = array('name' => $key, 'data' => array_values($value));
            }
            $t = time();
            $chartName = 'company_key_figures_report_' .$t;

            $report_data['chartName_key_figures'] = $chartName;

            $myfile = fopen($outputPath . "/inrep_$chartName.json", "w") or die("Unable to open file!");
            $txt = json_encode($high);
            fwrite($myfile, $txt);
            fclose($myfile);

            $command = "/usr/local/bin/phantomjs $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$chartName.json -outfile " . $outputPath . "/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";

            exec($command);

            //ratio graph

            $data['key_figures_ratio'] = $ratio_Arr;
            $key_figures_ratio = $data['key_figures_ratio'];

            $high = array();
            $high['chart']['type'] = "column";
            $high['title']['text'] = '';
            $high['xAxis']['categories'] = $key_year;
            $high['yAxis']['title']['text'] = '';
            $high['yAxis']['title']['stackLabels']['enabled'] = true;
            $high['yAxis']['title']['stackLabels']['style']['fontWeight'] = 'bold';
            $high['yAxis']['title']['stackLabels']['style']['color'] = "(Highcharts.theme && Highcharts.theme.textColor) || 'gray'";
            $high['credits']['enabled'] = false;
            $high['legend']['shadow'] = false;
            $high['tooltip']['headerFormat'] = '<b>{point.x}</b><br/>';
            $high['tooltip']['pointFormat'] = '{series.name}: {point.y}<br/>';
            $high['tooltip']['shared']=true;
            $high['plotOptions']['column']['pointPadding'] = '0.2';
            $high['plotOptions']['column']['dataLabels']['enabled'] = false;
            $high['plotOptions']['column']['dataLabels']['color'] = "(Highcharts.theme && Highcharts.theme.textColor) || 'white'";
            $high['plotOptions']['column']['dataLabels']['style']['textShadow'] = '0 0px 0px black';

            foreach ($key_figures_ratio as $key => $value) {

                $high['series'][] = array('name' => $key, 'data' => array_values($value));
            }

            $t = time();
            $chartName = 'company_key_figur_ratio_report_' .$t;

            $report_data['chartName_ratio'] = $chartName;

            $myfile = fopen($outputPath . "/inrep_$chartName.json", "w") or die("Unable to open file!");
            $txt = json_encode($high);
            fwrite($myfile, $txt);
            fclose($myfile);

             $command = "/usr/local/bin/phantomjs $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$chartName.json -outfile " . $outputPath . "/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
            exec($command);
            //end
            //euity graph

            $equityArray = $report_data['balance_sheets'];
            //pr($equityArray);exit;
            $equity_year = array();
            foreach ($equityArray as $key => $result_equity) {
                $equity_year[] = $result_equity['balance_year'];
            }
            $equity_array=array();
            foreach ($equityArray as $key => $equity ) {

                $array_value = $equity['working_capital'];
                $array_value1 = $equity['balance_liabilities_capandres_total'];
                $equity_array['Working Capital'][$equity['balance_year']]=  $array_value;
                $equity_array['Equity'][$equity['balance_year']]=  $array_value1;

            }
            $data['total_equity'] = $equity_array;
            $totalequity_array = $data['total_equity'];
            //  pr($totalequity_array);exit;
            $high = array();
            $high['chart']['type'] = "column";
            $high['title']['text'] = '';
            $high['xAxis']['categories'] = $equity_year;
            $high['yAxis']['title']['text'] = '';
            $high['yAxis']['title']['stackLabels']['enabled'] = true;
            $high['yAxis']['title']['stackLabels']['style']['fontWeight'] = 'bold';
            $high['yAxis']['title']['stackLabels']['style']['color'] = "(Highcharts.theme && Highcharts.theme.textColor) || 'gray'";
            $high['credits']['enabled'] = false;
            $high['legend']['shadow'] = false;
            $high['tooltip']['headerFormat'] = '<b>{point.x}</b><br/>';
            $high['tooltip']['pointFormat'] = '{series.name}: {point.y}<br/>';
            $high['tooltip']['shared']=true;
            $high['plotOptions']['column']['pointPadding'] = '0.2';
            $high['plotOptions']['column']['dataLabels']['enabled'] = false;
            $high['plotOptions']['column']['dataLabels']['color'] = "(Highcharts.theme && Highcharts.theme.textColor) || 'white'";
            $high['plotOptions']['column']['dataLabels']['style']['textShadow'] = '0 0px 0px black';

            foreach ($totalequity_array as $key => $value) {

                $high['series'][] = array('name' => $key, 'data' => array_values($value));
            }
            $t = time();
            $chartName = 'company_equity_report_' .$t;

            $report_data['chartName_equity'] = $chartName;

            $myfile = fopen($outputPath . "/inrep_$chartName.json", "w") or die("Unable to open file!");
            $txt = json_encode($high);
            fwrite($myfile, $txt);
            fclose($myfile);

            $command = "/usr/local/bin/phantomjs  $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$chartName.json -outfile " . $outputPath . "/inrep_$chartName.png -scale 2.5 -width 3500 -constr Chart -callback $phantomPath/callback.js 2>&1";

            exec($command);
            //end

            //balance_sheet graph

            $balance_sheetArr = $report_data['balance_sheets'];
            $balance_sheet_year = array();
            foreach ($balance_sheetArr as $key => $result_sheetArr) {
                $balance_sheet_year[] = $result_sheetArr['balance_year'];
            }
            $balance_sheet_array=array();
            foreach ($balance_sheetArr as $key => $balance_sheet ) {
                $array_value = $balance_sheet['balance_assets_fix_total'];
                $array_value1 = $balance_sheet['balance_assets_cur_stocks'];
                $array_value2 = $balance_sheet['balance_assets_cur_receivable_total'];
                $array_value3 = $balance_sheet['balance_assets_cur_liquid'];
                $balance_sheet_array['Tangible assets'][$balance_sheet['balance_year']]=  $array_value;
                $balance_sheet_array['Total inventories'][$balance_sheet['balance_year']]=  $array_value1;
                $balance_sheet_array['Total receivables'][$balance_sheet['balance_year']]=  $array_value2;
                $balance_sheet_array['Liquid assets'][$balance_sheet['balance_year']]=  $array_value3;

            }
            $data['total_balance_sheet'] = $balance_sheet_array;
            $balancesheet_array = $data['total_balance_sheet'];

            $high = array();
            $high['chart']['type'] = "column";
            $high['title']['text'] = '';
            $high['xAxis']['categories'] = $balance_sheet_year;
            $high['yAxis']['title']['text'] = '';
            $high['yAxis']['title']['stackLabels']['enabled'] = true;
            $high['yAxis']['title']['stackLabels']['style']['fontWeight'] = 'bold';
            $high['yAxis']['title']['stackLabels']['style']['color'] = "(Highcharts.theme && Highcharts.theme.textColor) || 'gray'";
            $high['credits']['enabled'] = false;
            $high['legend']['shadow'] = false;
            $high['tooltip']['headerFormat'] = '<b>{point.x}</b><br/>';
            $high['tooltip']['pointFormat'] = '{series.name}: {point.y}<br/>';
            $high['plotOptions']['column']['stacking'] = 'normal';
            $high['plotOptions']['column']['dataLabels']['enabled'] = false;
            $high['plotOptions']['column']['dataLabels']['color'] = "(Highcharts.theme && Highcharts.theme.textColor) || 'white'";
            $high['plotOptions']['column']['dataLabels']['style']['textShadow'] = '0 0px 0px black';

            foreach ($balancesheet_array as $key => $value) {

                $high['series'][] = array('name' => $key, 'data' => array_values($value));
            }
            $t = time();
            $chartName = 'company_balance_sheet_report_' .$t;

            $report_data['chartName_balance_sheet'] = $chartName;

            $myfile = fopen($outputPath . "/inrep_$chartName.json", "w") or die("Unable to open file!");
            $txt = json_encode($high);
            fwrite($myfile, $txt);
            fclose($myfile);

            $command = "/usr/local/bin/phantomjs  $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$chartName.json -outfile " . $outputPath . "/inrep_$chartName.png -scale 2.5 -width 3500 -constr Chart -callback $phantomPath/callback.js 2>&1";

            exec($command);
            //end

            //balance_sheet liabilities graph
            // pr($report_data['balance_sheets']); exit;
            $total_balance_sheetArr = $report_data['balance_sheets'];
            $total_balance_sheet_year = array();
            foreach ($total_balance_sheetArr as $key => $balance_result) {
                $total_balance_sheet_year[] = $balance_result['balance_year'];
            }
            $total_balance_sheet_array=array();
            foreach ($total_balance_sheetArr as $key => $total_balance_sheet ) {
                $array_value = $total_balance_sheet['balance_liabilities_capandres_total'];
                $array_value1 = $total_balance_sheet['balance_liabilities_st_total'];

                $total_balance_sheet_array['Total Equity'][$total_balance_sheet['balance_year']]=  $array_value;
                $total_balance_sheet_array['Current Liabilities'][$total_balance_sheet['balance_year']]=  $array_value1;

            }
            $data['totalnew_balance_sheet'] = $total_balance_sheet_array;
            $total_balancesheet_array = $data['totalnew_balance_sheet'];

            $high = array();
            $high['chart']['type'] = "column";
            $high['title']['text'] = '';
            $high['xAxis']['categories'] = $balance_sheet_year;
            $high['yAxis']['title']['text'] = '';
            $high['yAxis']['title']['stackLabels']['enabled'] = true;
            $high['yAxis']['title']['stackLabels']['style']['fontWeight'] = 'bold';
            $high['yAxis']['title']['stackLabels']['style']['color'] = "(Highcharts.theme && Highcharts.theme.textColor) || 'gray'";
            $high['credits']['enabled'] = false;
            $high['legend']['shadow'] = false;
            $high['tooltip']['headerFormat'] = '<b>{point.x}</b><br/>';
            $high['tooltip']['pointFormat'] = '{series.name}: {point.y}<br/>';
            $high['plotOptions']['column']['stacking'] = 'normal';
            $high['plotOptions']['column']['dataLabels']['enabled'] = false;
            $high['plotOptions']['column']['dataLabels']['color'] = "(Highcharts.theme && Highcharts.theme.textColor) || 'white'";
            $high['plotOptions']['column']['dataLabels']['style']['textShadow'] = '0 0px 0px black';

            foreach ($total_balancesheet_array as $key => $value) {

                $high['series'][] = array('name' => $key, 'data' => array_values($value));
            }
            $t = time();
            $chartName = 'company_totalbalance_sheet_report_' .$t;

            $report_data['chartName_total_balance_sheet'] = $chartName;

            $myfile = fopen($outputPath . "/inrep_$chartName.json", "w") or die("Unable to open file!");
            $txt = json_encode($high);
            fwrite($myfile, $txt);
            fclose($myfile);

            $command = "/usr/local/bin/phantomjs  $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$chartName.json -outfile " . $outputPath . "/inrep_$chartName.png -scale 2.5 -width 3500 -constr Chart -callback $phantomPath/callback.js 2>&1";

            exec($command);
            //end

            if (isset($result->annual)) {
                $annual = $result->annual;
            }

            $annual_last = "";
            if (isset($annual->annual_last)) {
                $annual_last = $annual->annual_last;
            }
            $annual_type = "";
            if (isset($annual->annual_type)) {
                $annual_type = $annual->annual_type;
            }

            $annual_from = "";
            if (isset($annual->annual_from)) {
                $annual_from = $annual->annual_from;
            }
            $annual_from_name = "";
            if (isset($annual_from->name)) {
                $annual_from_name = $annual_from->name;
            }
            $annual_from_reg_number = "";
            if (isset($annual_from->reg_number)) {
                $annual_from_reg_number = $annual_from->reg_number;
            }
            $annual_from_address = "";
            if (isset($annual_from->address)) {
                $annual_from_address = $annual_from->address;
            }
            $annual_from_zipcode = "";
            if (isset($annual_from->zipcode)) {
                $annual_from_zipcode = $annual_from->zipcode;
            }

            $annual_from_city = "";
            if (isset($annual_from->city)) {
                $annual_from_city = $annual_from->city;
            }
            $annual_from_country = "";
            if (isset($annual_from->country)) {
                $annual_from_country = $annual_from->country;
            }
            $annual_remark = "";
            if (isset($annual->annual_remark)) {
                $annual_remark = $annual->annual_remark;
            }

            $report_data['annual_report'] = array(
                'annual_last' => $annual_last,
                'annual_type' => $annual_type,
                'name' => $annual_from_name,
                'reg_number' => $annual_from_reg_number,
                'address' => $annual_from_address,
                'zipcode' => $annual_from_zipcode,
                'city' => $annual_from_city,
                'country' => $annual_from_country,
                'remark' => $annual_remark,
            );
            if (isset($result->branche_analysis)) {
                $branche_analysis = $result->branche_analysis;
            }

            $branche_sbi = "";
            if (isset($branche_analysis->sbi)) {
                $branche_sbi = $branche_analysis->sbi;
            }
            $branche_sbi_description = "";
            if (isset($branche_analysis->sbi_description)) {
                $branche_sbi_description = $branche_analysis->sbi_description;
            }
            $branche_region = "";
            if (isset($branche_analysis->region)) {
                $branche_region = $branche_analysis->region;
            }
            $branche_companies_sbi = "";
            if (isset($branche_analysis->companies_sbi)) {
                $branche_companies_sbi = $branche_analysis->companies_sbi;
            }
            $branche_companies_sbi_region = "";
            if (isset($branche_analysis->companies_sbi_region)) {
                $branche_companies_sbi_region = $branche_analysis->companies_sbi_region;
            }
            $branche_insolvencies_sbi = "";
            if (isset($branche_analysis->insolvencies_sbi)) {
                $branche_insolvencies_sbi = $branche_analysis->insolvencies_sbi;
            }
            $branche_insolvencies_sbi_region = "";
            if (isset($branche_analysis->insolvencies_sbi_region)) {
                $branche_insolvencies_sbi_region = $branche_analysis->insolvencies_sbi_region;
            }
            $branche_risk_sbi = "";
            if (isset($branche_analysis->risk_sbi)) {
                $branche_risk_sbi = $branche_analysis->risk_sbi;
            }
            $branche_risk_sbi_region = "";
            if (isset($branche_analysis->risk_sbi_region)) {
                $branche_risk_sbi_region = $branche_analysis->risk_sbi_region;
            }


            $report_data['branche'] = array(
                'sbi' => $branche_sbi,
                'sbi_description' => $branche_sbi_description,
                'region' => $branche_region,
                'companies_sbi' => $branche_companies_sbi,
                'companies_sbi_region' => $branche_companies_sbi_region,
                'insolvencies_sbi' => $branche_insolvencies_sbi,
                'insolvencies_sbi_region' => $branche_insolvencies_sbi_region,
                'risk_sbi' => $branche_risk_sbi,
                'risk_sbi_region' => $branche_risk_sbi_region,
            );

            $deposits = "";
            if (isset($result->publications->deposits)) {
                $deposits = $result->publications->deposits;
            }
            $functions = "";
            if (isset($result->publications->functions)) {
                $functions = $result->publications->functions;
            }
            $modifications = "";
            if (isset($result->publications->modifications)) {
                $modifications = $result->publications->modifications;
            }

            $report_data['deposits'] = $deposits;
            $report_data['functions'] = $functions;
            $report_data['modifications'] = $modifications;

            $management = "";
            if (isset($result->management->items)) {
                $management = $result->management->items;
            }
//            pr($management);exit;


            $management_details = array();
            if (!empty($management) && isset($management[0]->name)) {
                foreach ($management as $management_list) {
                    $name = "";
                    if (isset($management_list->name)) {
                        $name = $management_list->name;
                    }
                    $reg_number = "";
                    if (isset($management_list->reg_number)) {
                        $reg_number = $management_list->reg_number;
                    }
                    $address = "";
                    if (isset($management_list->address)) {
                        $address = $management_list->address;
                    }
                    $zipcode = "";
                    if (isset($management_list->zipcode)) {
                        $zipcode = $management_list->zipcode;
                    }
                    $city = "";
                    if (isset($management_list->city)) {
                        $city = $management_list->city;
                    }
                    $country = "";
                    if (isset($management_list->country)) {
                        $country = $management_list->country;
                    }
                    $active = "";
                    if (isset($management_list->active)) {
                        $active = $management_list->active;
                    }
                    $qualification = "";
                    if (isset($management_list->qualification)) {
                        $qualification = $management_list->qualification;
                    }
                    $function = "";
                    if (isset($management_list->function)) {
                        $function = $management_list->function;
                    }
                    $date_started = "";
                    if (isset($management_list->date_started)) {
                        $date_started = $management_list->date_started;
                    }

                    $management_record['name'] = $name;
                    $management_record['reg_number'] = $reg_number;
                    $management_record['address'] = $address;
                    $management_record['zipcode'] = $zipcode;
                    $management_record['city'] = $city;
                    $management_record['country'] = $country;
                    $management_record['active'] = $active;
                    $management_record['qualification'] = $qualification;
                    $management_record['function'] = $function;
                    $management_record['date_started'] = $date_started;
                    $management_details[] = $management_record;
                }

            }elseif (!empty($management) && isset($management[0]->initials)) {
                foreach ($management as $management_list) {

                $initials = "";
                if (isset($management_list->initials)) {
                    $initials = $management_list->initials;
                }
                $firstname = "";
                if (isset($management_list->firstname)) {
                    $firstname = $management_list->firstname;
                }
                $lastname = "";
                if (isset($management_list->lastname)) {
                    $lastname = $management_list->lastname;
                }
                $country = "";
                if (isset($management_list->country)) {
                    $country = $management_list->country;
                }
                    $birth_date = "";
                    if (isset($management_list->birth->date)) {
                        $birth_date = $management_list->birth->date;
                    }
                    $city = "";
                    if (isset($management_list->birth->city)) {
                        $city = $management_list->birth->city;
                    }
                    $birth_country = "";
                    if (isset($management_list->birth->country)) {
                        $birth_country = $management_list->birth->country;
                    }
                    $active = "";
                    if (isset($management_list->active)) {
                        $active = $management_list->active;
                    }
                    $qualification = "";
                    if (isset($management_list->qualification)) {
                        $qualification = $management_list->qualification;
                    }
                    $function = "";
                    if (isset($management_list->function)) {
                        $function = $management_list->function;
                    }
                    $date_started = "";
                    if (isset($management_list->date_started)) {
                        $date_started = $management_list->date_started;
                    }
                    $involvements = "";
                    if (isset($management_list->involvements)) {
                        $involvements = $management_list->involvements;
                    }

                    $management_record['initials'] = $initials;
                    $management_record['fullname'] = $firstname .' '.$lastname;
                    $management_record['city'] = $city;
                    $management_record['country'] = $country;
                    $management_record['active'] = $active;
                    $management_record['qualification'] = $qualification;
                    $management_record['function'] = $function;
                    $management_record['date_started'] = $date_started;
                    $management_record['involvements'] = $involvements;
                    $management_details[] = $management_record;
                }
            }
            $report_data['management'] = $management_details;

            //pr($report_data['management']);exit;


//pr($report_data['management']);exit;


//pr($report_data['annual_report']);exit;


            $logo = base_url('uploads/images/logo_new.png');
            $BZComIMG = '<img src="'.$logo.'" alt="" />';
            $report_data['report_type'] = $report_types;

            $this->load->library('m_pdf');
            $this->m_pdf->pdf->mPDF('utf-8','A4','','','15','15','28','18');
            $this->m_pdf->pdf->SetHTMLHeader('<div style="text-align: right;">'.$BZComIMG.'</div>', 'O');

            $footer_data = "Applicant";
            $footer_data1 = "Fa-med BV";

            $this->m_pdf->pdf->SetHTMLFooter('<div style="text-align: left;width:33%;float:left; font-family: Arial, Helvetica,sans-serif; font-weight: bold;font-size: 7pt; ">'.$con_name.'</div><div style="text-align: center;width:33%;float:left;  font-family: Arial, Helvetica,sans-serif; font-weight: bold;font-size: 7pt; ">'.date('Y-m-d').'</div><div style="text-align: right;width:33%;float:left;  font-family: Arial, Helvetica,sans-serif; font-weight: bold;font-size: 7pt; "><div>'.$footer_data.'</div><div >'.$footer_data1.'</div></div>');

            $report_data['main_content'] = '/CompanyPdfReport';
           $html = $this->parser->parse('layouts/PDFTemplate', $report_data);


            $pdfFileName = "Company.pdf";


            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFileName, "D");

        }else{

            if($report_types == 'D44CI102'){
                $msg = lang('create_international_credit_report');
            }elseif($report_types == 'D44CI301'){
                $msg = lang('create_chamber_of_commerce_extract');
            }elseif($report_types == 'D44CI302'){
                $msg = lang('create_Basic_report');
            }elseif($report_types == 'D44CI501'){
                $msg = lang('create_annual_report');
            }elseif($report_types == 'D44CI701'){
                $msg = lang('create_credit_report');
            }elseif($report_types == 'D44CI702'){
                $msg = lang('create_credit_report_plus');
            }elseif($report_types == 'D44CI704'){
                $msg = lang('create_Rating_report');
            }elseif($report_types == 'D44CI707'){
                $msg = lang('create_credit_report_plus_ips');
            }

            $this->session->set_flashdata('msg_data', $msg);
            redirect('CrmCompany');
        }

    }
//
//    function companyReportTest(){
//
//        $session_data = $this->session->userdata('LOGGED_IN');
//        $country_id = $this->input->post('country_id');
//        $company_name = $this->input->post('company_name');
//        $com_reg_number = $this->input->post('com_reg_number');
//        $company_id = $this->input->post('company_id_data');
//        //$report_types = $this->input->post('report_types');
//        $language = $this->input->post('language');
//
//
//        $apiKey = '4nhytG8E87rHkUw';
//        $reportTypes = array('D44CI102','D44CI301','D44CI501','D44CI701','D44CI702','D44CI704','D44CI707');
//        $url = 'https://inquiry.creditandcollection.nl/api/inquiries';
//        foreach($reportTypes as $reportType){
//            $fields = json_encode(array('company_id' => 'Ucu+pQErQpkEZeBZB6QimQ==', 'product' => $reportType, 'language' => $language));
//            $ch = curl_init($url);
//            curl_setopt($ch, CURLOPT_USERPWD, 'BLAZEDESK:' . $apiKey);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            //curl_setopt($ch, CURLOPT_TIMEOUT, 30);
//            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
//            curl_setopt($ch, CURLOPT_POST, 1);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//
//            $data_list = curl_exec($ch);
//            curl_close($ch);
//            $results[] = json_decode($data_list);
//        }
////            print_r($results);exit;
//
//        foreach($results as $result){
//
//            $url = 'https://inquiry.creditandcollection.nl/api/inquiries/' . $result->item->id . '/report';
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_USERPWD, 'BLAZEDESK:' . $apiKey);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            //curl_setopt($ch, CURLOPT_TIMEOUT, 10);
//            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//            $result = curl_exec($ch);
//            curl_close($ch);
//            $resultinq[] = json_decode($result);
//        }
//
//        pr($resultinq);
////        pr($resultinq);exit;
//
//    }

    function companyReportTest(){
        $apiKey = '4nhytG8E87rHkUw';
        //$reportTypes = array('D44CI102','D44CI301','D44CI501','D44CI701','D44CI702','D44CI704','D44CI707');
        $reportTypes = array('D44CI501','D44CI701','D44CI702','D44CI704','D44CI707');
        $url = 'https://inquiry.creditandcollection.nl/api/inquiries';
        foreach($reportTypes as $reportType){
            $fields = json_encode(array('company_id' => 'Ucu+pQErQpkEZeBZB6QimQ==', 'product' => $reportType, 'language' => 'EN'));
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, 'BLAZEDESK:' . $apiKey);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $data_list = curl_exec($ch);
            curl_close($ch);
            $results[] = json_decode($data_list);
        }
//            print_r($results);exit;

        foreach($results as $result){

            $url = 'https://inquiry.creditandcollection.nl/api/inquiries/' . $result->item->id . '/report';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERPWD, 'BLAZEDESK:' . $apiKey);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            curl_close($ch);
            $resultinq[] = json_decode($result);
        }

        print_r($resultinq);exit;
    }
}
?>