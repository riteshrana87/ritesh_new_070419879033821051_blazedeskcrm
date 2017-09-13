<?php

defined ('BASEPATH') OR exit('No direct script access allowed');

class Meeting extends CI_Controller {

    function __construct() {
        parent::__construct ();
        if(checkPermission('Meeting','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->module    = $this->router->fetch_module();
        $this->viewname  = $this->router->fetch_module();
        $this->user_info = $this->session->userdata ('LOGGED_IN');  //Current Login information
    }

    /*
      @Author : sanket Jayani
      @Desc   : CasesType index
      @Input  : 
      @Output :
      @Date   : 27/01/2016
     */

    public function index() {
         
        $data['header']         = array('menu_module' => 'CRM');
        $searchtext             = '';
        $perpage                = '';
        $where                  = '';
        $searchtext             = $this->input->post ('searchtext');
        $sortfield              = $this->input->post ('sortfield');
        $sortby                 = $this->input->post ('sortby');
        $perpage                = RECORD_PER_PAGE;
        $data['groupFieldName'] = $groupFieldName = $this->input->post ('groupFieldName');
        $data['groupFieldData'] = $groupFieldData = $this->input->post ('groupFieldData');

        $allflag = $this->input->post ('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata ('meetingpaging_data');
        }
        $searchsort_session = $this->session->userdata ('meetingpaging_data');
        //Sorting
        
        if (!empty($sortfield) && !empty($sortby)) {
            $data['sortfield'] = $sortfield;
            $data['sortby']    = $sortby;
        } else {
            if (!empty($searchsort_session['sortfield'])) {
                
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby']    = $searchsort_session['sortby'];
                $sortfield         = $searchsort_session['sortfield'];
                $sortby            = $searchsort_session['sortby'];
            } else {
                $sortfield         = 'pa.meeting_date';
                $sortby            = 'ASC';
                $data['sortfield'] = $sortfield;
                $data['sortby']    = $sortby;
            }
        }
        //Search text
        if (!empty($searchtext)) {
            $data['searchtext'] = $searchtext;
        } else {
            if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext         = $data['searchtext'];
            } else {
                $data['searchtext'] = '';
            }
        }

        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage']    = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage']    = trim ($searchsort_session['perpage']);
                $config['per_page'] = trim ($searchsort_session['perpage']);
            } else {
                $config['per_page'] = RECORD_PER_PAGE;
                $data['perpage']    = RECORD_PER_PAGE;
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url']   = base_url ($this->module . '/' . $this->viewname . '/index');

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment           = 0;
        } else {
            $segment_array = $this->uri->segment_array();
            $uri_segment = end($segment_array);
            $config['uri_segment'] = key(array_slice($segment_array, -1, 1, TRUE));
        }
        
        $data['meeting_view'] = $this->module . '/' . $this->viewname;
        
        //Get Records From PROJECT_MASTER Table  
        
        $dbSearch = "";
        if (!empty($searchtext)) {
            $searchFields = array('meet_title');
            foreach ($searchFields as $fields):
                $dbSearch .= " " . $fields . " like '%" . $searchtext . "%'  or ";
            endforeach;
            $dbSearch = '(' . substr ($dbSearch, 0, -3) . ')';
        }
        
        $user_id = $this->user_info['ID'];
        $params['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=pa.meet_user_id');
        $params['join_type'] = 'left';
        
        $table                = TBL_SCHEDULE_MEETING_MASTER . ' as pa';
        //$fields               = array('pa.*,CONCAT(l.firstname," ",l.lastname) as login_user_name,(SELECT group_concat(cm.contact_name) from blzdsk_contact_master as cm, blzdsk_schedule_meeting as sm where cm.contact_id=sm.meet_contact_id  AND pa.meeting_master_id = sm.meeting_master_id) as contact_name');
        $fields               = array('pa.*,CONCAT(l.firstname," ",l.lastname) as login_user_name ');
        //$match  = " pa.meeting_date >='".date('Y-m-d')."' and pa.is_delete = 0 and pa.meet_user_id =".$user_id;
        $match  = " pa.is_delete = 0 and pa.meet_user_id =".$user_id;
        $config['total_rows'] = $this->common_model->get_records ($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', 'pa.meeting_date', 'desc', '', $dbSearch, '', '', '1');
        //Get Records From MILESTONE_MASTER Table   
        $data['meeting_view_data'] = $this->common_model->get_records ($table, $fields, $params['join_tables'], $params['join_type'], $match, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $dbSearch);
        
        $this->ajax_pagination->initialize ($config);
        $data['pagination']  = $this->ajax_pagination->create_links ();
        $data['uri_segment'] = $uri_segment;
        $data['tasksSortDefault'] = 'asc';
        $sortsearchpage_data = array(
            'tasksortField'      => $data['sortfield'],
            'tasksortOrder'         => $data['sortby'],
            'searchtext'     => $data['searchtext'],
            'perpage'        => trim ($data['perpage']),
            'uri_segment'    => $uri_segment,
            'groupFieldName' => $this->input->post ('groupFieldName'),
            'groupFieldData' => $this->input->post ('groupFieldData'),
            'total_rows'     => $config['total_rows']);
        $this->session->set_userdata ('meetingpaging_data', $sortsearchpage_data);
        $data['drag'] = true;
        $data['header'] = array('menu_module'=>'MyProfile');
        if ($this->input->is_ajax_request ()) {
       
            if ($this->input->post ('project_ajax')) {
                $this->load->view ('/Meeting', $data);
            } else {
                $this->load->view ('/MeetingAjaxList', $data);
            }
        } else {
           // pr($data);
            $data['main_content'] = 'Meeting';
            $data['js_content']   = '/loadJsFiles';
            $this->parser->parse ('layouts/DashboardTemplate', $data);
        }
        
    }

    /*
      @Author : sanket Jayani
      @Desc   : Insert/update data
      @Input  : Post data/Update id
      @Output : Insert/update data
      @Date   : 27/01/2016
     */

    public function insertdata() {
        if ($this->input->post ('cases_type_id')) {
            $id = $this->input->post ('cases_type_id');
        }
        $display = $this->input->post ('display');
        
        $insert_data['cases_type_name'] = ucfirst ($this->input->post ('cases_type_name'));
        $insert_data['status']             = 1;
        //Insert Record in Database
        if (!empty($id)) //update
        {
            $insert_data['modified_by']   = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $insert_data['modified_date'] = datetimeformat ();
            $where                        = array('cases_type_id' => $id);
            $success_update               = $this->common_model->update (TBL_CRM_CASES_TYPE, $insert_data, $where);
            $msg                          = $this->lang->line ('cases_type_update_msg');
            $this->session->set_flashdata ('msg', "<div class='alert alert-success text-center'>$msg</div>");
            
        } else //insert
        {
            $insert_data['created_by']   = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $insert_data['created_date'] = datetimeformat ();
            $id                          = $this->common_model->insert (TBL_CRM_CASES_TYPE, $insert_data);
            $msg                         = $this->lang->line ('cases_type_add_msg');
            $this->session->set_flashdata ('msg', "<div class='alert alert-success text-center'>$msg</div>");

        }
        
       
        redirect ('CasesType');
    }

    /*
      @Author : sanket Jayani
      @Desc   : Add record
      @Input  : Add id
      @Output : Give record
      @Date   : 18/01/2016
     */

    public function add_record() {

        $data['modal_title']         = 'Create New Cases Type';
        $data['submit_button_title'] = 'Create Cases Type';
        
        //url for filemanager
        $data['project_incidenttype_view'] = '/CasesType';
        $this->load->view ('Add_CasesType', $data);
    }

    /*
      @Author : sanket Jayani
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function edit_record($id= NULL) {
        
        //$id = $this->uri->segment ('4');
        //Get Records From PROSPECT_MASTER Table
        if (!empty($id)) {
            $table = TBL_CRM_CASES_TYPE;
            $match = "cases_type_id = " . $id;

            $edit_record = $this->common_model->get_records ($table, '', '', '', $match);

            $data['id']          = $id;
            $data['edit_record'] = $edit_record;
            
            $data['modal_title']         = 'Update Cases Type';
            $data['submit_button_title'] = 'Update Cases Type';
        }
        
        $data['project_incidenttype_view'] = 'CasesType';
        $this->load->view ('Add_CasesType', $data);
    }

    /*
      @Author : sanket Jayani
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function view_record() {
        $id = $this->uri->segment ('4');
        //Get Records From PROSPECT_MASTER Table
        if (!empty($id)) {
            $table       = TBL_CRM_CASES_TYPE;
            $match       = "cases_type_id = " . $id;
            $fields      = array('cases_type_id,cases_type_name');
            $edit_record = $this->common_model->get_records ($table, $fields, '', '', $match);
            
            $data['id']          = $id;
            $data['edit_record'] = $edit_record;
            $data['modal_title'] = 'View Project Incident Type';
            
        }
        $data['project_incidenttype_view'] = $this->module . '/CasesType';
        $this->load->view ('/CasesType/View_CasesType', $data);
    }

    
    /*
      @Author : sanket Jayani
      @Desc   : Update is delete
      @Input  : Edit id
      @Output : Update is delete
      @Date   : 18/01/2016
     */
    
    public function delete_record($meeting_master_id = null) {
        
       
        if (!empty($meeting_master_id)) {
            
            //delete from meeting master
            $meeting_master['is_delete']   = 1;
            $where_meeting_master          = array('meeting_master_id' => $meeting_master_id);
            $this->common_model->update (TBL_SCHEDULE_MEETING_MASTER, $meeting_master, $where_meeting_master);
           
            //delete from schedule meeting
            $schedule_meeting['is_delete']   = 1;
            $where_schedule_meeting          = array('meeting_master_id' => $meeting_master_id);
            $this->common_model->update (TBL_SCHDEULE_MEETING, $schedule_meeting, $where_schedule_meeting);
            
            //delete from meeting receipents
            $meeting_receiepents['is_delete']   = 1;
            $where_meeting_receiepents          = array('meeting_master_id' => $meeting_master_id);
            $this->common_model->update (TBL_SCHEDULE_MEETING_RECEIPENT, $meeting_receiepents, $where_meeting_receiepents);
            
            //delete from events if generated from schedule meeting
            $event['is_delete']   = 1;
            $where_event          = array('meeting_master_id' => $meeting_master_id);
            $this->common_model->update (TBL_EVENTS, $event, $where_event);
            
            $table_master = TBL_SCHEDULE_MEETING_MASTER . ' as MM';
            $match_master = "MM.meeting_master_id=".$meeting_master_id;
            $fields_master = array("MM.*");
            $meeting_data = $this->common_model->get_records($table_master, $fields_master, '', '', $match_master);
            $meeting_master = $meeting_data[0];
           
            //Started Code For Sending Mail for Meeting Cancel
            $table1 = TBL_SCHEDULE_MEETING_RECEIPENT . ' as mr';
            $match1 = "mr.meeting_master_id=".$meeting_master_id;
            $fields1 = array("mr.*");
            $get_from_receipent = $this->common_model->get_records($table1, $fields1, '', '', $match1);
            
            $rec_exist = [];
           
            foreach ($get_from_receipent as $rec)
            {
                $rec_exist[] = $rec['user_id']."/".$rec['user_type'];
               
            }
            
            $contact_particiapnt_id_edit = [];
            $employee_participants_id_edit = [];
            $company_participants_id = [];
            foreach ($rec_exist as $contact)
            {
                $particiapants_arr  = explode('/',$contact);
                $participant_id =  $particiapants_arr[0];
                $participant_type =  $particiapants_arr[1];
                if($participant_type == '2')
                {
                    $contact_particiapnt_id_edit[] = $participant_id;
                }else  if($participant_type == '3')
                {
                    $company_participants_id[] = $participant_id;
                }else if($participant_type == '1')
                {
                    $employee_participants_id_edit[] = $participant_id;
                }
            }
            
            $contact_email_str = ''; 
            $employee_email_str = '';
            $company_email_str = '';
            if(count($contact_particiapnt_id_edit) > 0)
            {
                $contact_email_str = $this->getContactEmailbyId($contact_particiapnt_id_edit);
            }
            
            if(count($employee_participants_id_edit) > 0)
            {
                $employee_email_str = $this->getMultipleLoginUserEmail($employee_participants_id_edit);
            }
            
            if(count($company_participants_id) > 0)
            {
                $company_email_str = $this->getMultipleCompanyUserEmail($company_participants_id);
            }
            
            $email_meeting_receipent = $contact_email_str.",".$employee_email_str.",".$company_email_str.",".$meeting_master['additiona_receipent_email']; 
            
            $email_send_to = $email_meeting_receipent;
            
            $from_email =  $this->user_info['EMAIL'];
            $from_name =  $this->user_info['FIRSTNAME']." ".$this->user_info['LASTNAME'];

            $template =  systemTemplateDataBySlug(TEMPLATE_CANCEL_SCHEDULE_MEETING);
            
            $search  = array('{MEETING_TITLE}', '{MEETING_DATE}', '{MEETING_TIME}','{MEETING_END_TIME}','{FROM_NAME}');
            $replace = array( ucfirst($meeting_master['meet_title']), configDateTime($meeting_master['meeting_date']),convertTimeTo12HourFormat($meeting_master['meeting_time']),convertTimeTo12HourFormat($meeting_master['meeting_end_time']),$from_name);
            $body1 = str_replace($search,$replace, $template[0]['body']);
            
            $subject = "BLAZEDESK :: " . $template[0]['subject'];
            $headers = array('MIME-Version'=>'1.0\r\n','Disposition-Notification-To'=>$from_email);
           
            $msg = lang('MEETING_DELETED_SUCCESSFULLY');
            $this->session->set_flashdata ('message', $msg);
           // send_mail1($email_send_to,$subject,$body1, '',$from_email,$from_name, '','',$headers,'');
            send_mail1($email_send_to, $subject, $body1, $attach = '',$from_email,$from_name, '','',$headers,'') ;
           
            //End Code For Sending Mail for Meeting Cancel
            unset($meeting_master_id);
           
        }
        
        redirect ('/Meeting'); //Redirect On Listing page
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
    
    function view_meeting($meeting_id)
    {
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $data['meeting_id'] = $meeting_id;
            $data['modal_title'] = $this->lang->line('VIEW_MEETING');

            $params['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=pa.meet_user_id');
            $params['join_type'] = 'left';

            $table                = TBL_SCHEDULE_MEETING_MASTER . ' as pa ';
            $fields               = array('pa.*,CONCAT(l.firstname," ",l.lastname) as login_user_name, (SELECT group_concat(cm.contact_name) from blzdsk_contact_master as cm, blzdsk_schedule_meeting as sm where cm.contact_id=sm.meet_contact_id  AND pa.meeting_master_id = sm.meeting_master_id) as meeting_contact_name');
            $where                = array('pa.is_delete' => 0,'pa.meeting_master_id'=>$meeting_id); 
            $data['meeting_data'] = $this->common_model->get_records ($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', '', '', '', $where);

            if($data['meeting_data'][0]['is_another_location'] == '1')
            {
                $meeting_location =  $data['meeting_data'][0]['meeting_location'];
                $company_name = '';
            }else
            {
                $company_id_location = $data['meeting_data'][0]['company_id_location'];
                $meeting_location = $this->getCompany_location($company_id_location);
                $company_name = $this->getCompanyNameById($company_id_location);
            }
            //for  updating array for meetin lcoation 
            $data['meeting_data'][0]['meeting_location'] = $meeting_location;
            $data['meeting_data'][0]['company_name'] = $company_name;

            $table1 = TBL_SCHEDULE_MEETING_RECEIPENT . ' as sr';
            $match1 = " sr.is_delete=0 and sr.meeting_master_id=".$meeting_id;
            $fields1 = array("sr.*");
            $res_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);

            $receipent_email = ''; 
            $participant_array = []; 
            if(count($res_data) > 0)
            {
                foreach ($res_data as $receipent_data)
                {
                    if($receipent_data['user_type'] == '1')
                    {
                        $table1 = LOGIN . ' as l';
                        $match1 = "l.login_id=".$receipent_data['user_id'];
                        $fields1 = array("l.firstname,l.lastname,l.email,l.profile_photo");
                        $employee_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);
                        $employee_name = $employee_data[0]['email']." (".ucfirst($employee_data[0]['firstname'])." ".ucfirst($employee_data[0]['lastname']).")";
                        $receipent_email.= $employee_name.", ";

                        $participant_name = ucfirst($employee_data[0]['firstname'])." ".ucfirst($employee_data[0]['lastname']);
                        if($employee_data[0]['profile_photo'] != '')
                        {
                            $participant_image = base_url()."uploads/profile_photo/".$employee_data[0]['profile_photo'];
                        }else
                        {
                            $participant_image = base_url()."uploads/profile_photo/noimage.jpg";
                        }


                        $participant_array[] = array('participant_name'=>$participant_name,
                                                    'participant_image'=>$participant_image,
                                                    'participant_email'=>$employee_data[0]['email'],
                                                    'participant_title'=>'','participant_company'=>'');

                    }else if($receipent_data['user_type'] == '2')
                    {
                        $table1 = CONTACT_MASTER . ' as cm, '.COMPANY_MASTER.' as ed';
                        $match1 = "cm.contact_id=".$receipent_data['user_id']." and ed.company_id=cm.company_id";
                        $fields1 = array("cm.contact_name,cm.email,cm.image,cm.job_title,ed.company_name");
                        $employee_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);
                        $contact_name = $employee_data[0]['email']." (".ucfirst($employee_data[0]['contact_name']).")";
                        $receipent_email.= $contact_name.", ";

                        $participant_name = ucfirst($employee_data[0]['contact_name']);
                        if($employee_data[0]['image'] != '')
                        {
                            $participant_image = base_url()."uploads/contact/".$employee_data[0]['image'];
                        }else
                        {
                            $participant_image = base_url()."uploads/contact/noimage.jpg";
                        }

                        $participant_array[] = array('participant_name'=>$participant_name,
                                                    'participant_image'=>$participant_image,
                                                    'participant_email'=>$employee_data[0]['email'],
                                                    'participant_title'=>$employee_data[0]['job_title'],
                                                    'participant_company'=>$employee_data[0]['company_name']);

                    }else if($receipent_data['user_type'] == '3')
                    {
                        $table1 = COMPANY_MASTER . ' as c';
                        $match1 = "c.company_id=".$receipent_data['user_id'];
                        $fields1 = array("c.company_name,c.email_id,c.logo_img");
                        $employee_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);
                        $contact_name = $employee_data[0]['email_id']." (".ucfirst($employee_data[0]['company_name']).")";
                        $receipent_email.= $contact_name.", ";

                        $participant_name = ucfirst($employee_data[0]['company_name']);
                        if($employee_data[0]['logo_img'] != '')
                        {
                            $participant_image = base_url()."uploads/company/".$employee_data[0]['logo_img'];
                        }else
                        {
                            $participant_image = base_url()."uploads/company/noimage.jpg";
                        }

                        $participant_array[] = array('participant_name'=>$participant_name,
                                                    'participant_image'=>$participant_image,
                                                    'participant_email'=>$employee_data[0]['email_id'],
                                                    'participant_title'=>'','participant_company'=>'');
                    }
                }
            }

            $data['participant_array'] = $participant_array;

            $file_master = TBL_SCHEDULE_MEETING_FILE_MASTER . ' as fm';
            $match_file_master = "fm.meeting_master_id=".$meeting_id;
            $fields_file_master = array("fm.*");
            $meeting_attach_data = $this->common_model->get_records($file_master, $fields_file_master, '', '', $match_file_master);
            $data['meeting_data'][0]['meeting_participants'] = rtrim($receipent_email, ", ");
            $data['meeting_attach_data'] = $meeting_attach_data;
            $data['main_content'] = '/ViewMeeting_New';
            $this->load->view('/ViewMeeting_New', $data);

        }
        
    }
    
    function update_meeting($meeting_id)
    {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $data['modal_title'] = $this->lang->line('UPDATE_SCHDEDULE_MEETING');
            $data['submit_button_title'] = $this->lang->line('UPDATE_SCHDEDULE_MEETING');

            $fields = array("td.*,"
                . " (SELECT group_concat(meet_contact_id) FROM  blzdsk_schedule_meeting as sm WHERE sm.meeting_master_id = td.meeting_master_id) as meeting_contact");
            $where = array("td.is_delete" => "0","td.meeting_master_id"=>$meeting_id);

            $data['editRecord'] = $this->common_model->get_records(TBL_SCHEDULE_MEETING_MASTER . ' as td', $fields, '', '', '', '', '', '', '', '', '', $where,'','','','','','');

            $table1 = CONTACT_MASTER . ' as cm';
            $match1 = " cm.is_delete=0 and cm.status=1 ";
            $fields1 = array("cm.contact_id,cm.contact_name");
            $contact_participants = $data['contact_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);


            $participants_arr = [] ;
            foreach ($contact_participants as $con_participants)
            {
                $participants_arr[] = array('user_id'=>$con_participants['contact_id'],'user_name'=>$con_participants['contact_name'],'user_type'=>'2');
            }
            $employee_participants = $data['system_employee']  = $this->common_model->getSystemUserData();

            $logged_in_user_id = $this->session->userdata('LOGGED_IN')['ID'];
            foreach ($employee_participants as $emp_participants)
            {
                if($logged_in_user_id != $emp_participants['login_id'])
                {
                    $emp_name = $emp_participants['firstname']." ".$emp_participants['lastname'];
                    $participants_arr[] = array('user_id'=>$emp_participants['login_id'],'user_name'=>$emp_name,'user_type'=>'1');
                }
            }

            $table_company = COMPANY_MASTER . ' as c';
            $match_company = " c.is_delete=0 and c.status=1 ";
            $fields_company = array("c.company_id,c.company_name");
            $data['company_data'] = $company_participants =  $this->common_model->get_records($table_company, $fields_company, '', '', $match_company);

             foreach ($company_participants as $company_participants)
            {
                $participants_arr[] = array('user_id'=>$company_participants['company_id'],'user_name'=>$company_participants['company_name'],'user_type'=>'3');
            }

            $table_receipent = TBL_SCHEDULE_MEETING_RECEIPENT . ' as cm';
            $match_receipent = " cm.is_delete=0 and cm.meeting_master_id =".$meeting_id;
            $fields_receipent = array("cm.meeting_master_id,cm.user_id,cm.user_type");
            $meeting_participant_arr =  $this->common_model->get_records($table_receipent, $fields_receipent, '', '', $match_receipent);

            $edit_participants = []; 
            foreach ($meeting_participant_arr as $participants_edit)
            {
               $edit_participants[] =  $participants_edit['user_id']."/".$participants_edit['user_type'];
            }

            $table_file = TBL_SCHEDULE_MEETING_FILE_MASTER . ' as f';
            $match_file = " f.meeting_master_id =".$meeting_id;
            $fields_file = array("f.*");
            $meeting_attach_file =  $this->common_model->get_records($table_file, $fields_file, '', '', $match_file);
            $data['image_data'] = $meeting_attach_file;


            $data['edited_id'] = '';
            $data['meeting_related_id']=$meeting_id;
            $data['meeting_id']=$meeting_id;
            $data['contact_id']= '';
            $data['edit_participants'] = $edit_participants;
            $data['url'] = base_url("/Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            $data['drag'] = true;
            $data['meeting_particiapnts'] = $participants_arr;
            $data['form_action'] = 'addUpdateMeeting';
            $data['display_from'] = 'meeting';
            $data['meeting_master_id'] = $data['editRecord'][0]['meeting_master_id'];
            $data['main_content'] = '/AddEditMeetingNew';
            $this->load->view('Contact/AddEditMeetingNew', $data);
        }
        
    }
    
    function addUpdateMeeting()
    {
        
        $contact_id_array = $this->input->post('contact_id');
        $contact_id_array[] = $this->input->post('hdn_contact_id');
        $contact_id_array = array_filter($contact_id_array);
       // pr($contact_id_array);exit;
        
        $redirect_link = $_SERVER['HTTP_REFERER'];
        
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $insert_data['meet_status']  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $insert_data['meet_status']  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $insert_data['meet_status']  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $insert_data['meet_status'] = 1;
        }else
        {
            $insert_data['meet_status'] = 1;
        }
        if ($this->input->post('meeting_master_id') != '') {
            
            $meeting_master_id= $this->input->post('meeting_master_id');
        }
        
        $redirect_link = $this->input->post ('redirect_link');
        
        $insert_data['meet_title'] = ucfirst ($this->input->post ('meeting_title'));
        $insert_data['meet_date_time'] = date('Y-m-d h:i:s',  strtotime($this->input->post('meeting_date')));
        
        
        if( $this->input->post ('event_reminder') == "on")
        {
            $insert_data['meet_reminder'] = 1;
        }else
        {
            $insert_data['meet_reminder'] = 0;
        }
        
        //Insert Record in Database
        if ($meeting_master_id != '') //update
        {
            $meeting_master['meet_title'] = $insert_data['meet_title'] ;
            $meeting_master['meet_date_time'] = $insert_data['meet_date_time'] ;
           // $meeting_master['meet_reminder'] =  $insert_data['meet_reminder'];
            $meeting_master['modified_date'] = datetimeformat();
            $where                        = array('meeting_master_id' => $meeting_master_id);
            $success_update               = $this->common_model->update (TBL_SCHEDULE_MEETING_MASTER, $meeting_master, $where);
            
            $table1 = TBL_SCHDEULE_MEETING . ' as ds';
            $match1 = "ds.meeting_master_id=".$meeting_master_id;
            $fields1 = array("ds.meet_contact_id");
            $tmp_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);
            foreach ($tmp_data as $t_dat)
            {
                $var_data[] = $t_dat['meet_contact_id'];
            }
            
            $aarr_diff = array_diff($var_data,$contact_id_array);
            
           
           // pr($aarr_diff);exit;
            if(count($aarr_diff) > 0)
            {
                foreach ($aarr_diff as $arr)
                {
                    $delete_contact_where = array('meet_contact_id' => $arr,'meeting_master_id'=>$meeting_master_id);
                    $meeting_id  = $this->common_model->delete(TBL_SCHDEULE_MEETING,$delete_contact_where);
                }
            }
            
            foreach ($contact_id_array as $contact_id)
            {
              
                $table1 = TBL_SCHDEULE_MEETING . ' as ds';
                $match1 = " ds.meet_contact_id=".$contact_id." and ds.meeting_master_id=".$meeting_master_id;
                $fields1 = array("ds.*");
                $meeting_master = $this->common_model->get_records($table1, $fields1, '', '', $match1);
                
                $where    = array('meet_contact_id' => $contact_id,'meeting_master_id'=>$meeting_master_id);
                
               
                $insert_data1['meet_title'] = $insert_data['meet_title'] ;
                $insert_data1['meet_date_time'] = $insert_data['meet_date_time'];
                $insert_data1['meet_reminder'] = $insert_data['meet_reminder'] ;
                //
               if(!empty($meeting_master) && in_array($meeting_master[0]['meet_contact_id'],$contact_id_array) )
               {
                    $meeting_id  = $this->common_model->update(TBL_SCHDEULE_MEETING, $insert_data1,$where);
               }else
               {
                  // echo $contact_id;
                    $insert_data1['meeting_master_id'] = $meeting_master_id;
                    $insert_data1['meet_related_id'] = $contact_id;
                    $insert_data1['meet_contact_id'] = $contact_id;
                    $insert_data1['is_delete'] = 0;
                    $insert_data1['meet_status'] = 1;
                    $insert_data1['meet_user_id'] = $this->user_info['ID'];;
                    $insert_data1['created_date'] =datetimeformat();
                    
                    $meeting_id  = $this->common_model->insert(TBL_SCHDEULE_MEETING,$insert_data1);
                    
               }
                
            }
         
            $add_where =   array('meeting_master_id' => $meeting_master_id);
            $add_recepent_data['additiona_receipent_email'] = $this->input->post('additional_receipent');
            $add_recepent_data['modified_date'] = datetimeformat();
            $this->common_model->update (TBL_SCHEDULE_MEETING_ADDITIONAL_RECEIPENT, $add_recepent_data,$add_where);
                
            
            $additional_receipent = $this->input->post('additional_receipent');
            $contact_email_str = $this->getContactEmailbyId($contact_id_array);
            $email_send_to = $contact_email_str.",".$additional_receipent;
            
            $from_email =  $_SESSION['LOGGED_IN']['EMAIL'];
            $from_name =  $_SESSION['LOGGED_IN']['FIRSTNAME']. " ". $_SESSION['LOGGED_IN']['LASTNAME'];

            $CI = & get_instance();
            $configs = getMailConfig();
            
            $config['protocol'] = $configs['email_protocol'];
            $config['smtp_host'] = $configs['smtp_host']; //change this
            $config['smtp_port'] = $configs['smtp_port'];
            $config['smtp_user'] = $configs['smtp_user']; //change this
            $config['smtp_pass'] = $configs['smtp_pass']; //change this
            $config['mailtype'] = 'html';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8
            
            $template =  systemTemplateDataBySlug(TEMPLATE_UPDATE_SCHEDULE_MEETING);
            
            $search  = array('{MEETING_TITLE}', '{MEETING_DATE}');
            $replace = array( ucfirst($insert_data['meet_title']), $insert_data['meet_date_time']);
            $body1 = str_replace($search,$replace, $template[0]['body']);
          
            $subject = "BLAZEDESK :: " . $template[0]['subject'];
            
            $CI->load->library('email', $config); 
            $CI->email->set_header('MIME-Version', '1.0\r\n');
            $CI->email->set_header('Disposition-Notification-To', $from_email);
          
            $CI->email->from($from_email, $from_name);
            $CI->email->to($email_send_to);
          
            $CI->email->subject($subject);
            $CI->email->message($body1);

            if($CI->email->send())
            {
                $msg = $this->lang->line('EVENT_MAIL_SUUCESSFULLY_SENT');
                //$this->session->set_flashdata('message', $msg);
            }else
            {
                $msg = $this->lang->line('FAIL_WITH_SENDING_EMAIL');
                $this->session->set_flashdata('error', $msg);
            }
            
            $msg                          = $this->lang->line ('SCHEDULE_MEETING_UPDATES_MSG');
            $this->session->set_flashdata ('message', $msg);
            
        } 
        
        $sess_array = array('setting_current_tab' => 'Meeting');
        $this->session->set_userdata($sess_array);
        redirect ($redirect_link);
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
    
    function getCompany_location($company_id)
    {
        if($company_id != '' )
        {
           $table = COMPANY_MASTER . ' as c, '.COUNTRIES.' as co';
            $match = "c.company_id =".$company_id." and c.country_id=co.country_id";
            $fields = array("c.address1,c.address2,c.city,c.state,co.country_name,c.postal_code");
            $company_location = $this->common_model->get_records($table, $fields, '', '', $match);

            if(count($company_location) > 0)
            {
                $str_location = $company_location[0]['address1'].", ".$company_location[0]['address2'].", ".$company_location[0]['city'].", ".$company_location[0]['state'].", ".$company_location[0]['country_name']." - ".$company_location[0]['postal_code'];
            }else
            {
                $str_location = '';
            } 
        }else
        {
             $str_location = '';
        }
        

     return $str_location;

    }
    
    function getCompanyNameById($company_id)
    {
         $table = COMPANY_MASTER . ' as c';
        $match = "c.company_id =".$company_id;
        $fields = array("c.company_name");
        $company_name = $this->common_model->get_records($table, $fields, '', '', $match);

        if(count($company_name) > 0)
        {
            $str_company_name = $company_name[0]['company_name'];
        }else
        {
            $str_company_name = '';
        }

        return   $str_company_name;
    }
}
