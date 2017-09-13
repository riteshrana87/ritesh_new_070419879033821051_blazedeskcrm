<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  @Author : Seema Tankariya
  @Desc   : Model For Contact Model
  @Input 	:
  @Output	:
  @Date   : 26/02/2016
 */

class Contact_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function exportCsvData($dbSearch) {
        $this->load->dbutil();
        
        $delimiter = ",";
        $newline = "\r\n";
        $filename = random_string() . "contact.csv";
        $join_tables = array(OPPORTUNITY_REQUIREMENT_CONTACTS . ' as pc' => 'pm.prospect_id=pc.prospect_id');
        $params['join_type'] = 'left';
        $table = PROSPECT_MASTER . ' as pm';
        $group_by = 'pm.prospect_id';
        $fields = array("pm.prospect_name,pm.prospect_auto_id,count(pc.prospect_id) as 'number of contact',pc.contact_name,
          pm.creation_date as 'client since',CASE WHEN pm.status_type =3 THEN ' Account' else ' Opportunity' END as status");
        
        $data['sortField'] = 'pm.prospect_id';
        $data['sortOrder'] = 'desc';
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->join(OPPORTUNITY_REQUIREMENT_CONTACTS . ' as pc', 'pm.prospect_id=pc.prospect_id', 'left');
        $this->db->where($dbSearch, '', false);
        $this->db->order_by($data['sortField'], $data['sortOrder']);
        $this->db->group_by($group_by);
       
        $dataarr = $this->db->get();
        $data1 = $this->dbutil->csv_from_result($dataarr, $delimiter, $newline);
        force_download($filename, $data1);
    }
     /*
    function getComapnyIdByName($company_name)
    {
        
        $this->db->select('company_id');
        $this->db->from(COMPANY_MASTER);
        $where_array = array('company_name'=>$company_name,'status'=>'1');
        $this->db->where($where_array);
        $dataarr = $this->db->get()->result();
        
        //echo $this->db->last_query();
        //pr($dataarr);
        if(is_array($dataarr) && !empty($dataarr))
        {
            return $dataarr[0]->company_id;
        }else
        {
            return 0;
        }
    }
    
   
    function getCountryIdByName($country_name)
    {
        $this->db->select('country_id');
        $this->db->from(COUNTRIES);
        $this->db->where('country_name',$country_name);
        $dataarr = $this->db->get()->result();
        
       
        //pr($dataarr);
        if(is_array($dataarr) && !empty($dataarr))
        {
            
            return $dataarr[0]->country_id;
        }else
        {
            return 0;
        }
    }
    */
    function getAllContactByCompany($company_id)
    {
        $table1 = CONTACT_MASTER . ' as cm';
        $match1 = "cm.status=1 AND cm.is_delete=0 AND cm.company_id=".$company_id;
        $fields1 = array("cm.contact_id,cm.contact_name,cm.company_id");
        $order_by = 'cm.contact_name';
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1,'','','',$order_by,'asc');
        
        return $user_data;
    }
    
    function getProspectDataById($prospect_id)
    {
        $table_prospect = PROSPECT_MASTER . ' as pm';
        $prospect_match = "pm.status_type = 1 AND pm.is_delete=0 AND pm.status=1 AND pm.prospect_id=".$prospect_id;
        $prospect_fields = array("pm.prospect_id,pm.prospect_auto_id,pm.prospect_name");
        $data['prospect_data'] = $this->common_model->get_records($table_prospect, $prospect_fields, '', '', $prospect_match);
        
        return  $data['prospect_data'];
        
    }
    
    
    function getContactEmailByContactId($contact_id)
    {
      
        $table1 = CONTACT_MASTER . ' as cm';
        $match1 = "cm.contact_id=".$contact_id;
        $fields1 = array("cm.email,cm.contact_name");
        $contat_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        
        return $contat_data[0];
    }
    
    function getLoginUserEmail($user_id)
    {
        $table1 = LOGIN . ' as l';
        $match1 = "l.login_id=".$user_id;
        $fields1 = array("l.email");
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        
        return $user_data[0]['email'];
    }
    
    function getMultipleLoginUserEmail($user_id)
    {
        $temp_arr_str = implode(",",$user_id);
        $table1 = LOGIN . ' as l';
        $match1 = "l.login_id IN (".$temp_arr_str.")";
        $fields1 = array("l.email");
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        
        $str_email = '';
        foreach ($user_data as $user_email)
        {
            $str_email.= $user_email['email'].",";
        }
        $tmp_str_email = rtrim($str_email,",");
        return $tmp_str_email;
    }
    
    function getMultipleCompanyUserEmail($company_id)
    {
        $temp_arr_str = implode(",",$company_id);
        $table1 = COMPANY_MASTER . ' as c';
        $match1 = "c.company_id IN (".$temp_arr_str.")";
        $fields1 = array("c.email_id");
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        
        $str_email = '';
        foreach ($user_data as $user_email)
        {
            $str_email.= $user_email['email_id'].",";
        }
        $tmp_str_email = rtrim($str_email,",");
        return $tmp_str_email;
    }
    
    function getContactEmailbyId($arr_contact_id)
    {
        $temp_arr_str = implode(",",$arr_contact_id);
        
       
        $table1 = CONTACT_MASTER . ' as l';
        $match1 = "l.contact_id IN (".rtrim($temp_arr_str,',').")";
        $fields1 = array("l.email");
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        
        $str_email = '';
        foreach ($user_data as $user_email)
        {
            $str_email.= $user_email['email'].",";
        }
        $tmp_str_email = rtrim($str_email,",");
        return $tmp_str_email;
    }


    
    /*
      @Author   : Brijesh Tiwari
      @Desc     : Return random key  
      @Input    : 
      @Output   : 
      @Date     : 28/03/2016
     */
    
    public function getUUID() {

        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

     /*
      @Author   : Brijesh Tiwari
      @Desc     : Sync Contact with email_client contact
      @Input    : input post data
      @Output   : 
      @Date     : 28/03/2016
     */

     public function contactSync($postData) 
        {

            $configPath      = $this->config->item('directory_root') . '/email_client/data/_data_/_default_/configs/application.ini';
            $iniSetting      = parse_ini_file ($configPath);
            $adminUser_id= $this->session->userdata('LOGGED_IN')['ID'];
            
            
                    try {
                        
                         $where = array("user_id" => $adminUser_id);
                         $fields = array("id,user_id,email_id,email_pass");
                         $emailConfigData = $this->common_model->get_records(EMAIL_CONFIG, $fields, '', '', '', '', '', '', '', '', '', $where, '', '');
                        
                        if(!empty($emailConfigData)){
                            //$error = $this->lang->line ('contact_sync_error');
                            //$this->session->set_flashdata('error', $error);
                            //return false;
                       

                            $dbh = new PDO($iniSetting['pdo_dsn'], $iniSetting['pdo_user'], $iniSetting['pdo_password']);
                            
                            // get user data from email client 
                            $sth = $dbh->prepare("SELECT * from rainloop_users Where rl_email='".$emailConfigData[0]['email_id']."'");
                            $sth->execute();

                            /* Fetch all of the remaining rows in the result set */
                            $rlUser_result = $sth->fetchAll();
                            
                            $etag='';
                            $changed=time();
                            $idContactStr=$this->getUUID();

                            $sSql = 'INSERT INTO rainloop_ab_contacts '.
                            '( id_user,  id_contact_str,  display,  changed,  etag)'.
                            ' VALUES '.
                            '(:id_user, :id_contact_str, :display, :changed, :etag)';
                            $sth = $dbh->prepare($sSql);
                            
                            // $sth->bindParam(':id_user' ,$rlUser_result[0]['id_user'],PDO::PARAM_INT);
                            // $sth->bindParam(':id_contact_str' , $idContactStr,PDO::PARAM_STR );
                            // $sth->bindParam(':display' , $postData['contact_name'],PDO::PARAM_STR);
                            // $sth->bindParam(':changed' , $changed,PDO::PARAM_INT);
                            // $sth->bindParam(':etag' , $etag,PDO::PARAM_STR);
                            // $sth->execute();

                             $sth->execute(array(
                                ':id_user' => $rlUser_result[0]['id_user'],
                                ':id_contact_str' => $idContactStr,
                                ':display' => $postData['contact_name'],
                                ':changed' =>$changed,
                                ':etag' => $etag
                            ));
                            
                        
                        // Insert contact properties in email client
                        
                            $sSql = 'INSERT INTO rainloop_ab_properties '.
                        '( id_contact,  id_user,  prop_type,  prop_type_str,  prop_value,  prop_value_lower, prop_value_custom,  prop_frec)'.
                        ' VALUES '.
                        '(:id_contact, :id_user, :prop_type, :prop_type_str, :prop_value, :prop_value_lower, :prop_value_custom, :prop_frec)';

                        $propTypeArray=array('FULLNAME'=>10,'EMAIl'=>30);
                        
                        
                        $id = $dbh->lastInsertId();
                        
                        $propValueCustom='';
                        $propTypeStr='';
                        $propFrec=0;
                        foreach($propTypeArray as $key => $pType){
                            if($key=='FULLNAME'){
                                $propValue=$postData['contact_name'];
                            }else{
                                $propValue=$postData['email'];
                            }
                            $propValueLower=strtolower($propValue);
                            $sthp = $dbh->prepare($sSql);
                            
                             // $sthp->bindParam(':id_contact' , $id,PDO::PARAM_INT);
                             // $sthp->bindParam(':id_user' , $rlUser_result[0]['id_user'],PDO::PARAM_INT);
                             // $sthp->bindParam(':prop_type' , $pType, PDO::PARAM_INT);
                             // $sthp->bindParam(':prop_type_str' , $propTypeStr,PDO::PARAM_STR);
                             // $sthp->bindParam(':prop_value' , $propValue,PDO::PARAM_STR);
                             // $sthp->bindParam(':prop_value_lower' , $propValueLower,PDO::PARAM_STR);
                             // $sthp->bindParam(':prop_value_custom' ,$propValueCustom,PDO::PARAM_STR);
                             // $sthp->bindParam(':prop_frec' , $propFrec,PDO::PARAM_INT);
                             //$sthp->execute();


             
                            $sthp->execute(array(
                                ':id_contact' => $id,
                                ':id_user' => $rlUser_result[0]['id_user'],
                                ':prop_type' => $pType,
                                ':prop_type_str' => $propTypeStr,
                                ':prop_value' => $propValue,
                                ':prop_value_lower' => $propValueLower,
                                ':prop_value_custom' => $propValueCustom,
                                ':prop_frec' => $propFrec,
                            ));
                        }             
        
                  } // if close
                        $dbh = null;
                    } catch (PDOException $e) {
                            print "Error!: " . $e->getMessage() . "<br/>";
                            die();
                    }

                   return true; 
    }
    
    function exportContact()
    {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = time()."-".date('m-d-Y')."_contact.csv";
        $table = CONTACT_MASTER . ' as ca';
        $group_by = 'ca.contact_id';
        //ca.contact_id as 'ID',
        $fields = array("ca.contact_name as 'Contact Name',"
            . "cm.company_name as 'Company Name',"
            . "cm.email_id as 'Company Email',cm.phone_no as 'Company Phone',(SELECT b.branch_name FROM blzdsk_branch_master as b WHERE b.branch_id = cm.branch_id) as 'Company Branch',"
            . "ca.job_title as 'Job Title',"
            . "ca.phone_number as 'Phone Number',"
            . "ca.mobile_number as 'Mobile  Number',"
            . "ca.address1 as 'Address1',"
            . "ca.address2 as 'Address2',"
            . "ca.postal_code as 'Postal Code',"
            . "ca.city as 'City',"
            . "ca.state as 'State',"
            . "c.country_name as 'Country',"
            //. "CASE ca.language_id WHEN ca.language_id = 1 THEN 'English' WHEN ca.language_id = 2 THEN 'Spanish' ELSE '' END as 'Language',"
            . "l.language_name as 'Language',"
            . "ca.email as 'Email',"
            . "ca.contact_for as 'Contact For',"
            . "ca.fb as 'FB',ca.linkdin as 'Linkedin',ca.twitter as 'Twitter'");
        
        $data['sortField'] = 'ca.contact_name';
        $data['sortOrder'] = 'desc';
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->join(COUNTRIES . ' as c', 'c.country_id=ca.country_id', 'left');
        $this->db->join(COMPANY_MASTER . ' as cm','cm.company_id=ca.company_id', 'left');
        $this->db->join(LANGUAGE_MASTER . ' as l','l.language_id=ca.language_id', 'left');
        $this->db->where('ca.status', '1', false);
        $this->db->where('ca.is_delete', '0', false);
        $this->db->order_by($data['sortField'], $data['sortOrder']);
        $this->db->group_by($group_by);
        $dataarr = $this->db->get();
        $data1 = $this->dbutil->csv_from_result($dataarr, $delimiter, $newline);
        force_download($filename, $data1);
    }
    
}
