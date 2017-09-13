<?php
/*
@Author : sanket Jayani
@Desc   : Campaign Group Create/Update
@Date   : 21/01/2016
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class MyProfile extends CI_Controller 
{
    function __construct()
    {
        parent::__construct();
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(1);
        $this->load->library(array('form_validation','Session'));
        $this->load->model('MyProfile_model');
    }

    public function index()
    {
        $this->breadcrumbs->push(lang('MY_PROFILE'),'/');
        
        $data['main_content'] = '/'.$this->viewname;
        $user_id =  $this->session->userdata('LOGGED_IN')['ID'];
        $data['profile_data'] = $this->MyProfile_model->getUserData($user_id);
        
        $table = COUNTRIES.' as cm';
        $fields = array("cm.country_name,cm.country_id");
        
        $data['country_data']      = $this->common_model->get_records($table,$fields,'','','','','','','','','','');
        $data['salution_list'] = $this->common_model->salution_list();
        $data['header'] = array('menu_module'=>'MyProfile');
        $this->parser->parse('layouts/DashboardTemplate', $data);
    }
    
    public function updateProfile()
    {
        
        if($_FILES['profile_photo']['name'] != '' && !empty($_FILES))
        {
            $tmp_name_arr = explode('.', $_FILES['profile_photo']['name']);
//            pr($tmp_name_arr);
//            $tmp_file_name = str_replace('.', '_', $tmp_name_arr[0]);
            
            $tmp_file_name = $tmp_file_name.$tmp_name_arr[1];
            $profile_pic_new_name = time()."_profile_photo.".end($tmp_name_arr);
            $config['upload_path']   = PROFILE_PIC_UPLOAD_PATH;
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['file_name'] = $profile_pic_new_name;

            $this->load->library('upload', $config);
            
            if ( !$this->upload->do_upload('profile_photo',FALSE))
            {
                
                $this->form_validation->set_message('checkdoc', $data['error'] = $this->upload->display_errors());

                if($_FILES['profile_photo']['error'] != 4)
                {
                    $msg = $this->upload->display_errors();
                    $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
                    return false;
                }
            }
            else
            {
               
                $upload = array('upload_data' => $this->upload->data());
                $thumbnail_img_name =  $this->common_model->create_thumnail($upload,PROFILE_PIC_HEIGHT,PROFILE_PIC_WIDTH); //Create thumbnail
                
                // start for unlink the photo from the folder itself;
                $session_pic_name = $_SESSION['LOGGED_IN']['PROFILE_PHOTO'];
                $tmp_session_explode = explode('.', $session_pic_name);
                $tmp_thum_img_name = $tmp_session_explode[0]."_thumb.".$tmp_session_explode[1];
                unlink(PROFILE_PIC_UPLOAD_PATH."/".$session_pic_name);
                unlink(PROFILE_PIC_UPLOAD_PATH."/".$tmp_thum_img_name);
                // End for unlink the photo from the folder itself;
                
                $_SESSION['LOGGED_IN']['PROFILE_PHOTO'] = $profile_pic_new_name;                
                $data['profile_photo'] = $profile_pic_new_name;
            }
        }
        
        $data['salution_prefix'] = $this->input->post('salutions_prefix');
        $data['firstname'] = $this->input->post('fname');
        $data['lastname'] = $this->input->post('lname');
        $data['address'] = $this->input->post('address');
        $data['address_1'] = $this->input->post('address_1');
        $data['city'] = $this->input->post('profile_city');
        $data['state'] = $this->input->post('profile_state');
        $data['pincode'] = $this->input->post('profile_pincode');
        $data['country'] = $this->input->post('country_id');
        $data['telephone1'] = $this->input->post('telephone1');
        $data['telephone2'] = $this->input->post('telephone2');
        $data['modified_date'] = datetimeformat();
        
        $user_id =  $this->session->userdata('LOGGED_IN')['ID'];
        
        $where = $this->db->where('login_id', $user_id);
        $updateProfile = $this->common_model->update(LOGIN, $data,$where); 
       
        if($updateProfile)
        {
            $_SESSION['LOGGED_IN']['FIRSTNAME'] = $data['firstname'];
            $_SESSION['LOGGED_IN']['LASTNAME'] = $data['lastname'];
           
            $msg = $this->lang->line('USERPROFILE_UPDATED');
            $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
            
        }else
        {
            $msg = $this->lang->line('FAIL_USERPROFILE_UPDATED');
            $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect($this->viewname);
        
    }

    function ChangePassword()
    {
        $data['main_content'] = '/ChangePassword';
        $user_id =  $this->session->userdata('LOGGED_IN')['ID'];
        $data['profile_data'] = $this->MyProfile_model->getUserData($user_id);
        $data['header'] = array('menu_module'=>'MyProfile');
        $this->parser->parse('layouts/DashboardTemplate', $data);
        
    }
    
    function updatePassword()
    {
        $user_id =  $this->session->userdata('LOGGED_IN')['ID'];
        $data['password'] = md5($this->input->post('password'));
        $data['modified_date'] = datetimeformat();
        
        $where = $this->db->where('login_id', $user_id);
        $updatePassword = $this->db->update(LOGIN, $data, $where); 
        
        if($updatePassword)
        {
            $msg = $this->lang->line('MSG_UPDATE_PASSWORD');
            $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
            
        }else
        {
            $msg = $this->lang->line('FAIL_USERPROFILE_UPDATED');
            $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect('MyProfile/ChangePassword');
    }
    
}
