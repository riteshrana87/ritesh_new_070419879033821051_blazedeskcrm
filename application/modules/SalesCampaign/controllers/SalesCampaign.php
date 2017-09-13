<?php
/*
@Author : Madhuri Chotaliya
@Desc   : Sales Campaign Create/Update
@Date   : 13/01/2016
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class SalesCampaign extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(1);
        $this->load->library(array('form_validation','Session'));
        //$this->perPage = 5;
    }
    /*
@Author : Madhuri Chotaliya
@Desc   : Common Model Index Page
@Input 	:
@Output	:
@Date   : 12/01/2016
*/

    public function index()
    {
        // $data['js_content'] = '/loadJsFiles';
        $searchtext='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('salescampaign_data');
        }

        $searchsort_session = $this->session->userdata('salescampaign_data');
        //Sorting
        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield']))
            {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            }
            else
            {
                $sortfield = 'campaign_id';
                $sortby = 'desc';
                $data['sortfield']		= $sortfield;
                $data['sortby']			= $sortby;
            }
        }
        //Search text
        if(!empty($searchtext))
        {
            $data['searchtext'] = $searchtext;
        } else
        {
            if(empty($allflag) && !empty($searchsort_session['searchtext']))
            {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext =  $data['searchtext'];
            }
            else
            {
                $data['searchtext'] = '';
            }
        }

        if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link']  = 'First';
        $config['base_url']    = base_url().$this->viewname.'/index';

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }

        //Query

        $table = CAMPAIGN_MASTER.' as ct';
        $where = array("ct.status" => "1");
        $fields = array("ct.campaign_id,ct.campaign_name,ct.start_date,ct.end_date,ct.campaign_auto_id,ct.campaign_type_id,ct.responsible_employee_id,ctm.camp_type_name,cm.contact_name");
        $join_tables   =  array('blzdsk_campaign_type_master as ctm' =>'ctm.camp_type_id = ct.campaign_type_id','blzdsk_contact_master as cm' =>'cm.contact_id = ct.responsible_employee_id');


        if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim($searchtext));
            $match=array('cm.contact_name'=>$searchtext,'ct.campaign_name'=>$searchtext);
            $data['campaign_info']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where,'','','1');
            //echo $this->db->last_query();exit;
        }
        else
        {

            $data['campaign_info']      = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','',$sortfield,$sortby,'',$where,'','','1');
        }

        $this->ajax_pagination->initialize($config);
        $data['pagination']  = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield'  => $data['sortfield'],
            'sortby'     => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage'    => trim($data['perpage']),
            'uri_segment'=> $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('salescampaign_data', $sortsearchpage_data);
        
        //$data['sales_view'] = $this->viewname;
        //$data['facebook_count'] = $this->facebook_count();
        //$data['twiiter_count'] = $this->get_twitter_follower_count();
        //pr($data['pagination']);exit;

        //$data['popup'] = $this->load->view($this->viewname.'/Add',$data);
        if($this->input->post('result_type') == 'ajax'){
            $this->load->view($this->viewname.'/ajax_list',$data);
        } else {
            //$data['main_content'] = '/SalesCampaign';
            $data['main_content'] = '/'.$this->viewname;

            //$this->parser->parse('layouts/CampaignTemplate', $data);
            $this->parser->parse('layouts/CampaignTemplate', $data);
            // $this->parser->parse('layouts/ProspectTemplate', $data);
        }

    }

    public function insertdata()
    {
        //pr($_FILES);exit;


        $this->form_validation->set_rules('campaign_name', 'Campaign Name', 'required');
        // pr($this->config->item('Campaign_img_url'));exit;
       /* if(!empty($_FILES['fileUpload']['name']))
       {
            $oldbookimg                 = $this->input->post('fileUpload');//new add
            $bgImgPath                  = $this->config->item('Campaign_img_url');
            $smallImgPath               = $this->config->item('Campaign_img_url');
            $uploadFile = 'fileUpload';
            $thumb = "thumb";
            $hiddenImage = !empty($oldbookimg)?$oldbookimg:'';
            $compaigndata['file'] = $this->common_model->upload_image($uploadFile,$bgImgPath,$smallImgPath,$thumb,TRUE);

        }
        pr($compaigndata);exit;
*/
        if($this->input->post('campaign_name'))
        {
            $compaigndata['campaign_name'] = strip_slashes($this->input->post('campaign_name'));
        }
        $compaigndata['campaign_auto_id'] = $this->input->post('campaign_auto_id');
        $compaigndata['campaign_type_id'] = $this->input->post('campaign_type_id');
        $compaigndata['responsible_employee_id'] = $this->input->post('responsible_employee_id');
        $compaigndata['start_date'] = date_format(date_create($this->input->post('start_date')), 'Y-m-d');
        $compaigndata['end_date'] = date_format(date_create($this->input->post('end_date')), 'Y-m-d');
        $compaigndata['campaign_description'] = strip_slashes($this->input->post('campaign_description'));
        $file2 = $this->input->post('fileToUpload');
        $compaigndata['file'] = implode(",",$file2);
        $budget_requirement='0';
        if($this->input->post('budget_requirement')=='on')
        {
            $budget_requirement='1';
        }

        $compaigndata['budget_requirement'] = $budget_requirement;
        $compaigndata['budget_ammount'] = $this->input->post('budget_ammount');
        $campaign_supplier='0';
        if($this->input->post('campaign_supplier')=='on')
        {
            $campaign_supplier='1';
        }
        $compaigndata['campaign_supplier'] = $campaign_supplier;
        $revenue_goal='0';
        if($this->input->post('revenue_goal')=='on')
        {
            $revenue_goal='1';
        }
        $compaigndata['revenue_goal'] = $revenue_goal;
        $compaigndata['revenue_amount'] = $this->input->post('revenue_amount');
        $related_product='';
        if($this->input->post('related_product')=='on')
        {
            $related_product='1';
        }
        $compaigndata['related_product'] = $related_product;
        $compaigndata['supplier_id'] = $this->input->post('supplier_id');
        $compaigndata['product_id'] = $this->input->post('product_id');
        $compaigndata['campaign_group_id'] = $this->input->post('campaign_group_id');
        $compaigndata['status']     = '1';
        $compaigndata['created_date']   = datetimeformat();
        $compaigndata['modified_date']  = datetimeformat();
        //Insert Record in Database

        $success_insert=$this->common_model->insert(CAMPAIGN_MASTER,$compaigndata);
        $insert_id = $this->db->insert_id();
        $contact_id=$this->input->post('contact_id');
        for($i=0;$i<count($contact_id);$i++)
        {
            $campaign_receipents_tran['campaign_id']=$insert_id;
            $campaign_receipents_tran['contact_id']=$contact_id[$i];
            $campaign_receipents_tran['created_date']=datetimeformat();
            $campaign_receipents_tran['modified_date']=datetimeformat();
            $campaign_receipents_tran['status']='1';
            $this->common_model->insert(CAMPAIGN_RECEIPIENT_TRAN,$campaign_receipents_tran);
        }
        if ($success_insert) {
            $msg = $this->lang->line('campign_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }

        redirect($this->viewname);  //Redirect On Listing page
    }
    
    public function add_record() {
        $data['js_content'] = '/loadJsFiles';

        $data['modal_title'] = $this->lang->line('create_sales_campaign');
        $ct_params=array();
        $ct_params['table'] = CAMPAIGN_TYPE_MASTER.' as ct';
        $ct_params['fields'] = array("ct.camp_type_id,ct.camp_type_name");
        $ct_params['where_in']=array("ct.status" => "1");
        $data['campaign_type_info']  = $this->common_model->get_records_array($ct_params);

        $cm_params=array();
        $cm_params['table'] = CONTACT_MASTER.' as cm';
        $cm_params['fields'] = array("cm.contact_id,cm.contact_name");
        $cm_params['where_in']=array("cm.status" => "1");
        $data['contact_info']  = $this->common_model->get_records_array($cm_params);

        $sm_params=array();
        $sm_params['table'] = SUPPLIER_MASTER.' as sm';
        $sm_params['fields'] = array("sm.supplier_id,sm.supplier_name");
        $sm_params['where_in']=array("sm.status" => "1");
        $data['supplier_info']  = $this->common_model->get_records_array($sm_params);

        $cg_params=array();
        $cg_params['table'] = CAMPAIGN_GROUP_MASTER.' as cg';
        $cg_params['fields'] = array("cg.campaign_group_id,cg.group_name");
        $cg_params['where_in']=array("cg.status" => "1");
        $data['campaign_group_info']  = $this->common_model->get_records_array($cg_params);

        $pm_params=array();
        $pm_params['table'] = PRODUCT_MASTER.' as pm';
        $pm_params['fields'] = array("pm.product_id,pm.product_name");
        $pm_params['where_in']=array("pm.status" => "1");
        $data['product_info']  = $this->common_model->get_records_array($pm_params);

        $data['sales_view'] = $this->viewname;

        $data['main_content'] = '/Add';
        $data['js_content'] = '/loadJsFiles';
        //$this->parser->parse('layouts/CampaignTemplate', $data);
        $this->load->view('/Add',$data);
    }

    public function edit()
    {
        $id = $this->uri->segment('3');
        $data['crnt_view'] = $this->viewname;

        $data['modal_title'] = $this->lang->line('edit_sales_campaign');


        $ct_params=array();
        $ct_params['table'] = CAMPAIGN_TYPE_MASTER.' as ct';
        $ct_params['fields'] = array("ct.camp_type_id,ct.camp_type_name");
        $ct_params['where_in']=array("ct.status" => "1");
        $data['campaign_type_info']  = $this->common_model->get_records_array($ct_params);

        $cm_params=array();
        $cm_params['table'] = CONTACT_MASTER.' as cm';
        $cm_params['fields'] = array("cm.contact_id,cm.contact_name");
        $cm_params['where_in']=array("cm.status" => "1");
        $data['contact_info']  = $this->common_model->get_records_array($cm_params);

        $sm_params=array();
        $sm_params['table'] = SUPPLIER_MASTER.' as sm';
        $sm_params['fields'] = array("sm.supplier_id,sm.supplier_name");
        $sm_params['where_in']=array("sm.status" => "1");
        $data['supplier_info']  = $this->common_model->get_records_array($sm_params);

        $cg_params=array();
        $cg_params['table'] = CAMPAIGN_GROUP_MASTER.' as cg';
        $cg_params['fields'] = array("cg.campaign_group_id,cg.group_name");
        $cg_params['where_in']=array("cg.status" => "1");
        $data['campaign_group_info']  = $this->common_model->get_records_array($cg_params);

        $pm_params=array();
        $pm_params['table'] = PRODUCT_MASTER.' as pm';
        $pm_params['fields'] = array("pm.product_id,pm.product_name");
        $pm_params['where_in']=array("pm.status" => "1");
        $data['product_info']  = $this->common_model->get_records_array($pm_params);

        $data['sales_view'] = $this->viewname;

        $table = CAMPAIGN_MASTER.' as cm';
        $where = array("cm.campaign_id" => $id);
        $fields = array("cm.campaign_id, cm.campaign_name,cm.campaign_description, cm.campaign_auto_id, cm.campaign_type_id,cm.responsible_employee_id,cm.start_date,cm.end_date,cm.budget_requirement,cm.budget_ammount,cm.campaign_supplier,cm.supplier_id,cm.revenue_goal,cm.revenue_amount,cm.related_product,cm.product_id,cm.campaign_group_id,cm.file,cmc.contact_id,cmc.contact_name");
        $join_tables   =  array('blzdsk_campaign_receipents_tran as crt' =>'crt.campaign_id = cm.campaign_id','blzdsk_contact_master as cmc' =>'cmc.contact_id = crt.contact_id');
        $data['editRecord']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','','',$where);

        //pr($data['editRecord']);exit;

        $contact_info=array();
        foreach($data['editRecord'] as $row){
            $contact_info[] = $row['contact_id'];
        }
        $data['content_data']=$contact_info;


        //pr($data['editRecord']);exit;
        $data['id'] = $id;
        $data['main_content'] = '/Add';
        $data['js_content'] = '/loadJsFiles';
        $this->load->view('/Add',$data);

        //echo json_encode($data['editRecord']);
    }


    public function updatedata()
    {
        $id = $this->input->post('id');
        $compaigndata['campaign_auto_id'] = $this->input->post('campaign_auto_id');
        $compaigndata['campaign_name']= strip_slashes($this->input->post('campaign_name'));
        $compaigndata['campaign_type_id']= $this->input->post('campaign_type_id');
        $compaigndata['responsible_employee_id']= $this->input->post('responsible_employee_id');
        $compaigndata['campaign_description']= strip_slashes($this->input->post('campaign_description'));
        $compaigndata['revenue_amount']= $this->input->post('revenue_amount');
        $compaigndata['start_date'] = date_format(date_create($this->input->post('start_date')), 'Y-m-d');
        $compaigndata['end_date'] = date_format(date_create($this->input->post('end_date')), 'Y-m-d');
        if($this->input->post('budget_requirement')=='on' || $this->input->post('budget_requirement')=='1')
        {
            $budget_requirement='1';
        }

        $compaigndata['budget_requirement'] = $budget_requirement;
        $compaigndata['budget_ammount']= $this->input->post('budget_ammount');
        $campaign_supplier='0';
        if($this->input->post('campaign_supplier')=='on' || $this->input->post('campaign_supplier')=='1')
        {
            $campaign_supplier='1';
        }
        $compaigndata['campaign_supplier'] = $campaign_supplier;
        $revenue_goal='0';
        if($this->input->post('revenue_goal')=='on' || $this->input->post('revenue_goal')=='1')
        {
            $revenue_goal='1';
        }
        $compaigndata['revenue_goal'] = $revenue_goal;
        $compaigndata['revenue_amount'] = $this->input->post('revenue_amount');
        $related_product='';
        if($this->input->post('related_product')=='on' || $this->input->post('related_product')=='1')
        {
            $related_product='1';
        }
        $compaigndata['related_product'] = $related_product;
        $compaigndata['supplier_id'] = $this->input->post('supplier_id');
        $compaigndata['product_id'] = $this->input->post('product_id');
        $compaigndata['campaign_group_id'] = $this->input->post('campaign_group_id');
        $file2 = $this->input->post('fileToUpload');
        $compaigndata['file'] = implode(",",$file2);
       /* if(!empty($_FILES["fileToUpload"]["name"]))
        {
            $target_dir = $this->config->item('directory_root').'application/modules/SalesCampaign/views/upload/';
            $temp = explode(".", $_FILES["fileToUpload"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $target_file = $target_dir . basename($newfilename);
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            // Check if image file is a actual image or fake image

            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $compaigndata['file'] =$newfilename;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }

        }*/

        $compaigndata['modified_date'] 	= datetimeformat();
        //Update Record in Database
        $where = array('campaign_id' => $id);
        $success_update = $this->common_model->update(CAMPAIGN_MASTER, $compaigndata, $where);

        $where = array('campaign_id' => $id);
        $this->common_model->delete(CAMPAIGN_RECEIPIENT_TRAN,$where);

        $contact_id=$this->input->post('contact_id');
        for($i=0;$i<count($contact_id);$i++)
        {
            $campaign_receipents_tran['campaign_id']=$id;
            $campaign_receipents_tran['contact_id']=$contact_id[$i];
            $campaign_receipents_tran['created_date']=datetimeformat();
            $campaign_receipents_tran['modified_date']=datetimeformat();
            $campaign_receipents_tran['status']='1';
            $this->common_model->insert(CAMPAIGN_RECEIPIENT_TRAN,$campaign_receipents_tran);
        }

        if ($success_update) {
            $msg = $this->lang->line('campign_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }

        redirect($this->viewname);	//Redirect On Listing page
    }


    /*
@Author : Madhuri Chotaliya
@Desc   : Sales Campaign Delete Query
@Input 	: Post id from List page
@Output	: Delete data from database and redirect
@Date   : 18/01/2016
*/

    public function deletedata()
    {
        $id = $this->input->get('id');
        //Delete Record From Database
        if(!empty($id))
        {
            $trans_where = array('campaign_id' => $id);
            $this->common_model->delete(CAMPAIGN_RECEIPIENT_TRAN,$trans_where);

            $where = array('campaign_id' => $id);
            $success_delete=$this->common_model->delete(CAMPAIGN_MASTER,$where);
            unset($id);
            if ($success_delete) {
                $msg = $this->lang->line('campign_del_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }

        }
        redirect($this->viewname);	//Redirect On Listing page
    }
    public function upload_file(){

        $str = file_get_contents('php://input');
        echo $filename = time().uniqid().".jpg";

        file_put_contents($this->config->item('Campaign_img_url').'/'.$filename,$str);

        /*    if(!empty($_FILES['fileToUpload']['name']))
            {
                $oldbookimg                 = $this->input->post('fileToUpload');//new add
                $bgImgPath                  = $this->config->item('Campaign_img_url');
                $smallImgPath               = $this->config->item('Campaign_img_url');
                $uploadFile = 'fileToUpload';
                $thumb = "thumb";
                $hiddenImage = !empty($oldbookimg)?$oldbookimg:'';
                $compaigndata['file'] = $this->common_model->upload_image($uploadFile,$bgImgPath,$smallImgPath,$thumb,TRUE);
            }
        */

    }
    
    function facebook_count()
    {
        $url = 'https://www.facebook.com/C-Metric-1401983436714324/';
        $fql  = "SELECT share_count, like_count, comment_count ";
        $fql .= " FROM link_stat WHERE url = '$url'";

        $fqlURL = "https://api.facebook.com/method/fql.query?format=json&query=" . urlencode($fql);
        $response = json_decode(file_get_contents($fqlURL));
        echo $response[0]->like_count;
    }
    
    function get_twitter_follower_count()
    {
        $tw_username = 'c_metric'; 
        $data = file_get_contents('https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names='.$tw_username); 
        $parsed =  json_decode($data,true);
        $tw_followers =  $parsed[0]['followers_count'];
        
        echo $tw_followers;
    }

}

