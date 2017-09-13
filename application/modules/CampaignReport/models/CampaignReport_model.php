<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@Author : Sanket Jayani
@Desc   : Model For Request Budget Module
@Input 	: 
@Output	: 
@Date   : 12/01/2016
*/
class CampaignReport_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function total_budget_request($st = NULL)
    {
        if(isset($st) && $st != '')
        {
            $txt_search_val = $st;
            $search_val = "AND (cm.campaign_name LIKE '%$txt_search_val%' OR cm.campaign_auto_id LIKE '%$txt_search_val%')";
        }  else {
            $search_val = '';
        }
        
        $this->db->select('cm.*,ct.camp_type_name, conm.contact_name');
        $this->db->from('blzdsk_campaign_master as cm');
        $this->db->join('blzdsk_campaign_type_master as ct','ct.camp_type_id = cm.campaign_type_id');
        $this->db->join('blzdsk_contact_master as conm','conm.contact_id = cm.responsible_employee_id');
        $this->db->where('cm.status !=','2');
        if(isset($st) && $st != '')
        {
            $this->db->like('cm.campaign_name', $st);
            $this->db->or_like('cm.campaign_auto_id',$st);
        } 
        
        $this->db->order_by("cm.campaign_id", "DESC"); 
        
        $query  = $this->db->get();
        /*$sql = 'select cm.*,ct.camp_type_name, conm.contact_name from '
                . 'blzdsk_campaign_master as cm, '
                . 'blzdsk_campaign_type_master as ct, blzdsk_contact_master as conm'
                . ' where conm.contact_id = cm.responsible_employee_id AND ct.camp_type_id = cm.campaign_type_id AND  cm.status != 2 '.$search_val.'';
        
        $query = $this->db->query($sql);
         * 
         */
        return count($query->result());
    }
    function get_campaign_report_list($limit, $start, $st = NULL)
    {
        $this->db->select('cm.*,ct.camp_type_name, conm.contact_name');
        $this->db->from('blzdsk_campaign_master cm');
        $this->db->join('blzdsk_campaign_type_master as ct', 'ct.camp_type_id = cm.campaign_type_id'); 
        $this->db->join('blzdsk_contact_master as conm', 'conm.contact_id = cm.responsible_employee_id'); 
        $this->db->where('cm.status !=','2');
        
        if(isset($st) && $st != '')
        {
            $this->db->like('cm.campaign_name', $st);
            $this->db->or_like('cm.campaign_auto_id',$st);
        } 
        
        $this->db->order_by("cm.campaign_id", "DESC"); 
        $this->db->limit($limit, $start);
        $query  = $this->db->get();
       
        /*
        $sql = 'select cm.*,ct.camp_type_name, conm.contact_name from '
                . 'blzdsk_campaign_master as cm, '
                . 'blzdsk_campaign_type_master as ct, blzdsk_contact_master as conm'
                . ' where conm.contact_id = cm.responsible_employee_id AND 0.
         *  AND  cm.status != 2 '.$search_val.' ORDER BY cm.campaign_id desc  limit ' . $start . ', ' . $limit;
        */
       
      //  echo $this->db->last_query();
        return $query->result();
    }
    
    function getSearchBook($searchBook)
    {
        if(empty($searchBook))
           return array();

        $result = $this->db->like('title', $searchBook)
                 ->or_like('author', $searchBook)
                 ->get('books');

        return $result->result();
    }
    
    function ExportCSV($campaign_ids)
    {
//        $responsible = showResponsibleEmployee($campaign_ids);
//        pr($responsible);exit;
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Campaign_Report.csv";
        //Get Records to Export CSV
        $this->db->select('cm.campaign_name AS Campaign Name,cm.campaign_auto_id AS ID,cm.budget_ammount AS Budget Available,cm.start_date AS Start Date,cm.end_date AS End Date,ct.camp_type_name AS Campaign Type, group_concat(l.firstname," ", l.lastname) AS Responsible');
        $this->db->from('blzdsk_campaign_master cm');
        $this->db->join('blzdsk_campaign_type_master as ct', 'ct.camp_type_id = cm.campaign_type_id', 'left'); 
        $this->db->join('blzdsk_contact_master as conm', 'conm.contact_id = cm.responsible_employee_id', 'left');
        $this->db->join('blzdsk_campaign_responsible_employee_tran as cre', 'cm.campaign_id = cre.campaign_id', 'left');
        $this->db->join('blzdsk_login as l', 'l.login_id = cre.user_id');
        $this->db->where_in('cm.campaign_id', $campaign_ids);
        $this->db->order_by("cm.campaign_id", "desc"); 
        $this->db->group_by('cm.campaign_id');
        $result = $this->db->get();
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }   
    
    function ExportPDF($campaign_ids){

        //pr($campaign_ids);

       $this->db->select('cm.*,ct.camp_type_name, conm.contact_name, cg.group_name, cg.group_description, bc.   budget_ammount, bc.revenue_goal');
        $this->db->from('blzdsk_campaign_master cm');
        $this->db->join('blzdsk_campaign_type_master as ct', 'ct.camp_type_id = cm.campaign_type_id', 'left'); 
        $this->db->join('blzdsk_contact_master as conm', 'conm.contact_id = cm.responsible_employee_id', 'left');
        $this->db->join('blzdsk_campaign_group_master as cg', 'cg.campaign_id = cm.campaign_id', 'left'); 
        $this->db->join('blzdsk_budget_campaign_master as bc', 'bc.campaign_id = cm.campaign_id', 'left'); 
        $this->db->where_in('cm.campaign_id', $campaign_ids);
        $this->db->order_by("cm.campaign_id", "desc"); 
        $query = $this->db->get();
        return $query->result();
    }
    
    

    /*
    @Author : Brijesh Tiwari
    @Desc   : This function is to get the lead by campagine
    @Input  : 
    @Output : 
    @Date   : 31/03/2016
    */

    function getLeadsByMarketing($campaign_ids,$preMonth=0,$postMonth=0){
  
        $this->db->select("lm.campaign_id,cm.campaign_name ,DATE_FORMAT(lm.creation_date,'%b-%y') as month ,count(*) as  total_lead");
        $this->db->from('blzdsk_lead_master lm');
        $this->db->join('blzdsk_campaign_master as cm', 'cm.campaign_id=lm.campaign_id', 'left'); 
        $this->db->where('lm.is_delete =','0');
        $this->db->where('lm.status =','1');
         if($preMonth && $postMonth){

         $this->db->where('lm.creation_date BETWEEN "'.$preMonth.'" AND "'.$postMonth.'"');

        }
        $this->db->where_in('lm.campaign_id', $campaign_ids);
       $this->db->where('lm.creation_date < CURDATE()'); 
        //$this->db->group_by("MONTH(lm.creation_date)"); 
        $this->db->order_by("lm.creation_date", "ASC"); 
         $this->db->group_by("MONTH(lm.creation_date), lm.campaign_id"); 
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }

 /*
    @Author : Brijesh Tiwari
    @Desc   : This function is to get the customer by campagine
    @Input  : 
    @Output : 
    @Date   : 31/03/2016
    */
    function getCustomerBySource($campaign_ids){

        /*

        SELECT pm.campaign_id,cm.campaign_name ,DATE_FORMAT(pm.creation_date,'%b-%y') as month,count(*) as  total_lead  FROM blzdsk_prospect_master as pm
LEFT JOIN blzdsk_campaign_master as cm on cm.campaign_id=pm.campaign_id
 WHERE pm.is_delete=0 and pm.`status`=1 and pm.status_type=3
GROUP BY MONTH(pm.creation_date),pm.campaign_id */

$this->db->select("pm.campaign_id,cm.campaign_name ,DATE_FORMAT(pm.creation_date,'%b-%y') as month ,count(*) as  total_lead");
        $this->db->from('blzdsk_prospect_master pm');
        $this->db->join('blzdsk_campaign_master as cm', 'cm.campaign_id=pm.campaign_id', 'left');
        $this->db->where('pm.is_delete =','0');
        $this->db->where('pm.status =','1');
        $this->db->where('pm.status_type =','3'); 
        $this->db->where('pm.creation_date < CURDATE()'); 
        $this->db->where_in('pm.campaign_id', $campaign_ids);
        $this->db->order_by("pm.creation_date", "ASC"); 
         $this->db->group_by("MONTH(pm.creation_date), pm.campaign_id"); 
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }


function percentage( $oldFigure,$newFigure, $precision) 
{
    
    //$percentChange = round(((1 - $oldFigure / $newFigure) * 100),$precision);
    $percentChange =(((1 - $oldFigure)   / $newFigure) * 100);
 
return $percentChange;
}


/*
@Author : Brijesh Tiwari
@Desc   : This function is to get user visit count my specific date
@Input  : 
@Output : 
@Date   : 11/04/2016
*/

     function monthlyPageVisitCount($startData,$endData,$type='pageviews'){
            $this->load->library('googleanly');
           
          
            try {
                $anal = $this->googleanly->getServices();
                $profileId = $this->googleanly->getFirstProfileId($anal);
                $totlaPageviews = $this->googleanly->visitorCount($anal, $profileId,$startData,$endData,$type);
                if($totlaPageviews == ""){
                    $totlaPageviews = 0;
                }else{
                    $totlaPageviews = $totlaPageviews;
                }
                return $totlaPageviews;
            }
            
            //catch exception
            catch(Exception $e) {
                $totlaPageviews = 0;
                 return $totlaPageviews;
            }
     }


function monthlyPageVisitCountBySource($startData,$endData,$type='pageviews'){
            $this->load->library('googleanly');
           
          
            try {
                $anal = $this->googleanly->getServices();
                $profileId = $this->googleanly->getFirstProfileId($anal);



                $visitorCountBySource = $this->googleanly->visitorCountBySource($anal, $profileId,$startData,$endData,$type);
               
               $totlaPageviews=0;
                if($visitorCountBySource == ""){
                    $totlaPageviews = 0;
                }else{
                    $totlaPageviews = $visitorCountBySource;
                }
                return $totlaPageviews;
            }
            
            //catch exception
            catch(Exception $e) {
                $totlaPageviews = 0;
                 return $totlaPageviews;
            }
     }

     function newCustomerORLeadByMonth($type='lead'){

        $cur_date   = date('m');
       //echo"prev_month-> ".
        $prev_month = date("m", mktime(0, 0, 0, date("m")-1, 1, date("Y")));
  
  

        if($type=='lead'){
           $wheresting="(MONTH(creation_date) = ".$prev_month." or MONTH(creation_date) = ".$cur_date.")";        $where = array('is_delete' => 0);
            $group_by="DATE_FORMAT(creation_date,'%m')";
            
            $fields=array("DATE_FORMAT(creation_date, '%M') as name_month, COUNT(DATE_FORMAT(creation_date, '%m')) as total_count") ;
            $data = $this->common_model->get_records(LEAD_MASTER,$fields, '', '', $where, '', '', '', '', '', $group_by, $wheresting, '', '', '');

      }else{
        
            $wheresting="(MONTH(creation_date) = ".$prev_month." or MONTH(creation_date) = ".$cur_date.")";
            $where = array('is_delete' => 0,'status_type' => 3);
            $group_by="DATE_FORMAT(creation_date,'%m')";
           
            $fields=array("DATE_FORMAT(creation_date, '%M') as name_month, COUNT(DATE_FORMAT(creation_date, '%m')) as total_count") ;
            $data = $this->common_model->get_records(PROSPECT_MASTER,$fields, '', '', $where, '', '', '', '', '', $group_by, $wheresting, '', '', '');
        }
   //echo"<br>--------". $this->db->last_query();
        return $data;
     }

     function lostCustomer(){

        /*SELECT count(*) as total_leads, (Select count(*) from blzdsk_prospect_master  WHERE is_delete=0 and status_type=4) as lost_client 
from blzdsk_prospect_master 
Where is_delete=0 and status=1*/

        $wheresting="is_delete=0 and status=1";
         $fields=array("count(*) as total_leads, (Select count(*) from blzdsk_prospect_master  WHERE is_delete=0 and status_type=4) as lost_client") ;

         $data = $this->common_model->get_records(PROSPECT_MASTER,$fields, '', '', '', '', '', '', '', '', '', $wheresting, '', '', '');
         //echo"<br>--------". $this->db->last_query();
          return $data;
     }

/*
@Author : Brijesh Tiwari
@Desc   : This function is to get top Marketing Campaigns 
@Input  : 
@Output : 
@Date   : 10/05/2016
*/

    function topMarketingCampaign(){
//SELECT count(*) as lead_count,lm.campaign_id, cm.campaign_name from blzdsk_lead_master as lm RIGHT Join blzdsk_campaign_master as cm on cm.campaign_id=lm.campaign_id WHERE lm.`status`=1  group by lm.campaign_id order by lead_count DESC limit 3


    $this->db->select("lm.campaign_id, count(*) as lead_count, cm.campaign_name");
        $this->db->from('blzdsk_lead_master lm');
        $this->db->join('blzdsk_campaign_master as cm', 'cm.campaign_id=lm.campaign_id', 'RIGHT');
        $this->db->where('lm.status =','1');
        //$this->db->where_in('lm.campaign_id', $campaign_ids);
        $this->db->order_by("lead_count", "DESC"); 
        $this->db->group_by("lm.campaign_id");
        $this->db->limit('3'); 
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }



/*
@Author : Brijesh Tiwari
@Desc   : This function is to get Customers Sourced by Marketing 
@Input  : 
@Output : 
@Date   : 10/05/2016
*/

    function customerSourceByMarketing($campaign_ids){

    /*
    SELECT `pm`.`campaign_id`, `cm`.`campaign_name`, DATE_FORMAT(pm.creation_date, '%b-%y') as month, 
    count(*) as total_lead FROM `blzdsk_prospect_master` `pm` 
    LEFT JOIN `blzdsk_campaign_master` as `cm` ON `cm`.`campaign_id`=`pm`.`campaign_id` 
    WHERE `pm`.`is_delete` = '0' AND `pm`.`status` = '1' AND `pm`.`status_type` = '3' 
    AND `pm`.`creation_date` < CURDATE() 
    AND `pm`.`campaign_id` IN('81', '52', '51', '50', '49', '43', '38', '34', '32', '30', '0') 
    GROUP BY MONTH(pm.creation_date) ORDER BY `pm`.`creation_date` ASC
    */

    $this->db->select("pm.campaign_id,cm.campaign_name ,DATE_FORMAT(pm.creation_date,'%b-%y') as month ,count(*) as  total_lead");
        $this->db->from('blzdsk_prospect_master pm');
        $this->db->join('blzdsk_campaign_master as cm', 'cm.campaign_id=pm.campaign_id', 'left');
        $this->db->where('pm.is_delete =','0');
        $this->db->where('pm.status =','1');
        $this->db->where('pm.status_type =','3'); 
        $this->db->where('pm.creation_date < CURDATE()'); 
        $this->db->where_in('pm.campaign_id', $campaign_ids);
        $this->db->order_by("pm.creation_date", "ASC"); 
         $this->db->group_by("MONTH(pm.creation_date)"); 
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }


/*
@Author : Brijesh Tiwari
@Desc   : This function is to get Lead to Customer Performance 
@Input  : 
@Output : 
@Date   : 11/05/2016
*/    
    function leadToCustomerPerformance(){

     /*   SELECT `pm`.`campaign_id`, DATE_FORMAT(pm.creation_date, '%b-%y') as cmonth, 
    count(*) as total_lead FROM `blzdsk_prospect_master` `pm` 
    WHERE `pm`.`is_delete` = '0' AND `pm`.`status` = '1' AND `pm`.`status_type` = '3' 
    AND `pm`.`creation_date` < CURDATE() 
    
    GROUP BY MONTH(pm.creation_date) ORDER BY `pm`.`creation_date` ASC */

 $this->db->select("pm.campaign_id ,DATE_FORMAT(pm.creation_date,'%b-%y') as month ,count(*) as  total_lead");
        $this->db->from('blzdsk_prospect_master pm');
        $this->db->where('pm.is_delete =','0');
        $this->db->where('pm.status =','1');
        $this->db->where('pm.status_type =','3'); 
        $this->db->where('pm.creation_date < CURDATE()'); 
        $this->db->order_by("pm.creation_date", "ASC"); 
        $this->db->group_by("MONTH(pm.creation_date)"); 
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();

    }

  /*
    @Author : Brijesh Tiwari
    @Desc   : This function is to get twitter count monthly 
    @Input  : 
    @Output : 
    @Date   : 13/05/2016
*/  

    function getTwitterCountMonthly($startData=0,$endData=0){
  /*Select DATE_FORMAT(created_date, '%c') as month, id, followers_count from blzdsk_twitter_monthly_count Where created_date between '2016-01-01' AND '2016-05-14' 
group by MONTH(created_date) ORDER BY `created_date` ASC*/
       
       $this->db->select("DATE_FORMAT(created_date, '%c') as month, id, followers_count");
        $this->db->from('blzdsk_twitter_monthly_count');
        $this->db->where("created_date between '".$startData."' AND '".$endData."'");
        $this->db->order_by("created_date", "ASC"); 
        $this->db->group_by("MONTH(created_date)"); 
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result(); 
    }
}
