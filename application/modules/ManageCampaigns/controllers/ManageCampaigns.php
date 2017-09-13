<?php
/*
@Author : Madhuri Chotaliya
@Desc   : Sales Campaign Create/Update
@Date   : 13/01/2016
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class ManageCampaigns extends CI_Controller
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
            $this->session->unset_userdata('managecampaigns_data');
        }

        $searchsort_session = $this->session->userdata('managecampaigns_data');
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
        $fields = array("ct.campaign_id,ct.campaign_name,ct.start_date,ct.end_date,ct.campaign_auto_id,ct.campaign_type_id,ct.responsible_employee_id,ctm.camp_type_name");
        $join_tables   =  array('blzdsk_campaign_type_master as ctm' =>'ctm.camp_type_id = ct.campaign_type_id');

        if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((ct.campaign_name LIKE "%'.$searchtext.'%" OR ct.campaign_auto_id LIKE "%'.$searchtext.'%" OR ctm.camp_type_name LIKE "%'.$searchtext.'%" OR ct.start_date LIKE "%'.date("y-m-d", strtotime($searchtext)).'%" OR ct.end_date LIKE "%'.date("y-m-d", strtotime($searchtext)).'%") AND ct.status = "1")';

            $data['campaign_info']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1');
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
        $this->session->set_userdata('managecampaigns_data', $sortsearchpage_data);
        $data['header'] = array('menu_module'=>'crm');
        //$data['popup'] = $this->load->view($this->viewname.'/Add',$data);
        $data['drag'] = true;
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
            $this->session->set_flashdata('msg', $msg);
        }

        redirect($this->viewname);  //Redirect On Listing page
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
            $this->session->set_flashdata('msg', $msg);
        }

        redirect($this->viewname);	//Redirect On Listing page
    }


    /*
@Author : Ritesh Rana
@Desc   : Campaign Delete Query
@Input 	: Post id from List page
@Output	: Delete data from database and redirect
@Date   : 18/01/2016
*/

    public function deletedata()
    {
        $id  = $this->input->post('single_remove_id');
        if(!empty($id))
        {
            $compaigndata['status']= 0;
            $trans_where = array('campaign_id' => $id);
            $this->common_model->update(CAMPAIGN_RECEIPIENT_TRAN, $compaigndata, $trans_where);

            $where = array('campaign_id' => $id);
            $success_delete = $this->common_model->update(CAMPAIGN_MASTER, $compaigndata, $where);

            unset($id);
        }
        $delete_all_flag = 0;$cnt = 0;
        //pagingation
        $searchsort_session = $this->session->userdata('managecampaigns_data');
        //pr($searchsort_session);exit;
        if(!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;
        $perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
        $total_rows = $searchsort_session['total_rows'];
        if($delete_all_flag == 1)
        {
            $total_rows -= $cnt;
            $pagingid*$perpage;
            if($pagingid*$perpage > $total_rows) {
                if($total_rows % $perpage == 0) // if all record delete
                {
                    $pagingid -= $perpage;
                }
            }
        } else {
            if($total_rows % $perpage == 1)
                $pagingid -= $perpage;
        }

        if($pagingid < 0)
            $pagingid = 0;
        echo $pagingid;

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
        return $response[0]->like_count;
    }
    
    function get_twitter_follower_count()
    {
        $tw_username = 'c_metric'; 
        $data = file_get_contents('https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names='.$tw_username); 
        $parsed =  json_decode($data,true);
        $tw_followers =  $parsed[0]['followers_count'];
    
        return $tw_followers;
    }

}

