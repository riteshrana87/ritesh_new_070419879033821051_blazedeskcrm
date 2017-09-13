<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Filemanager extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->module = 'Projectmanagement';
        $this->viewname = $this->router->fetch_class();
        $this->project_id = $this->session->userdata('PROJECT_ID');
        $this->load->library(array('form_validation', 'Session', 'breadcrumbs'));
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Index Page loads view of the file manager
      @Input 	:
      @Output	:
      @Date   : 29/01/2016
     */

    public function index() {

        /*  $master_user_id = $this->config->item('master_user_id');
          //$master_user_id = $data['user_info']['ID'];

          $table = SETUP_MASTER . ' as ct';
          $where_setup_data = array("ct.login_id" => $master_user_id);
          $fields = array("ct.*");
          $check_user_menu = $this->common_model->get_records_data($table, $fields, '', '', '', '', '', '', '', '', '', $where_setup_data);
          //pr($check_user_menu);exit;
          if (isset($check_user_menu[0]['is_pm']) && $check_user_menu[0]['is_pm'] == 0) {
          if (isset($check_user_menu[0]['is_crm']) && $check_user_menu[0]['is_crm'] == 1) {
          $msg = $this->lang->line('DONT_HAVE_PAGE_PERMISSION');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
          redirect(base_url()); //Redirect on Dashboard
          } elseif (isset($check_user_menu[0]['is_support']) && $check_user_menu[0]['is_support'] == 1) {
          $msg = $this->lang->line('DONT_HAVE_PAGE_PERMISSION');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
          redirect(base_url('Support')); //Redirect on Dashboard
          }
          }
         */
        if (isset($_SERVER['HTTP_REFERER']) && preg_match('/Projectmanagement/', $_SERVER['HTTP_REFERER'])) {
            $this->module = 'Projectmanagement';
            $this->viewname = 'Filemanager';
            $data['is_crm'] = 0;
            $data['cur_viewname'] = 'Filemanager';
        } else {
            $this->module = 'CRM';
            $data['is_crm'] = 1;
            $this->viewname = 'Filemanager';
            $data['cur_viewname'] = 'Filemanager';
        }
        $data['project_view'] = $this->module . '/' . $this->viewname;
        // Make sure we have the correct directory
        $data['refresh'] = $data['parent'] = $directory = 'uploads/filemanager/';
        $directory = 'uploads/filemanager/';

        if (isset($_SERVER['HTTP_REFERER']) && preg_match('/Projectmanagement/', $_SERVER['HTTP_REFERER'])) {
            $this->module = 'Projectmanagement';
            $this->breadcrumbs->push(lang('project_management'), 'Projectmanagement/Projectdashboard');
            $this->breadcrumbs->push(lang('file_manager'), 'File Manager');
            $data['header'] = array('menu_module' => 'Projectmanagement');
        } else {
            $this->breadcrumbs->push(lang('crm'), '/');
            $this->breadcrumbs->push(lang('file_manager'), 'File Manager');
            $this->module = 'CRM';
            $data['header'] = array('menu_module' => 'crm');
        }


        if ($this->module == 'CRM') {
            if ($this->input->get('dir') == 'uploads/') {
                $dir = 'uploads/filemanager/';
            } else {
                $dir = $this->input->get('dir');
            }

            if ($this->input->get('modal') && $this->input->get('dir') == 'uploads/') {
                if ($dir && $dir != '' && $dir != 'uploads/filemanager' && $dir != 'uploads/filemanager/' && $dir != '.') {
                    $directory = $dir . '/';
                    $data['refresh'] = $directory = $dir . '/';
                    $data['parent'] = dirname($directory);
                }
            } else {
                if (($this->input->get('dir')) && $this->input->get('dir') != '' && $this->input->get('dir') != 'uploads/filemanager' && $this->input->get('dir') != 'uploads/filemanager/' && $this->input->get('dir') != '.') {

                    $data['refresh'] = $directory = $this->input->get('dir') . '/';
                    $data['parent'] = dirname($directory);
                }
            }
        }
        $data['module'] = '';

        if ($this->module == 'Projectmanagement') {

            if ($this->input->get('module') && $this->input->get('module') == 'invoice') {

                $data['module'] = $this->input->get('module');
                $cond = 1;
                $data['refresh'] = $data['parent'] = $directory = 'uploads/';
                $directory = 'uploads/';
                if (($this->input->get('dir')) && $this->input->get('dir') != 'uploads' && $this->input->get('dir') != 'uploads/projectManagement' && $this->input->get('dir') != "'uploads/projectManagement/Project0' . $this->project_id . '/'" && $this->input->get('dir') != '.') {
                    $directory = $this->input->get('dir') . '/';
                    $data['refresh'] = $directory = $this->input->get('dir') . '/';
                    $data['parent'] = dirname($directory);
                }
            } else {

                $data['refresh'] = $data['parent'] = $directory = 'uploads/projectManagement/Project0' . $this->project_id . '/';
                $directory = 'uploads/projectManagement/Project0' . $this->project_id . '/';
                if (($this->input->get('dir')) && $this->input->get('dir') != 'uploads' && $this->input->get('dir') != 'uploads/projectManagement' && $this->input->get('dir') != "'uploads/projectManagement/Project0' . $this->project_id . '/'" && $this->input->get('dir') != '.') {
                    $directory = $this->input->get('dir') . '/';
                    $data['refresh'] = $directory = $this->input->get('dir') . '/';
                    $data['parent'] = dirname($directory);
                }
            }
        }
        $ignoreFolders = array('uploads//assets', 'uploads//css', 'uploads//custom', 'uploads//dist', 'uploads//font-awesome-4.5.0', 'uploads/filemanager//index.html', 'uploads///index.html');
        $filter_name = null;
        $data['images'] = array();
        // echo $directory;
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

        if ($this->input->get('modal')) {
            $this->load->view('Filemanager/Imagemanagerpopup', $data);
        } else {
            $data['drag'] = true;
            $data['main_content'] = '/' . $this->viewname . '/Imagemanager.php';
            $this->parser->parse('layouts/ProjectTemplate', $data);
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Used to create new directory
      @Input 	:
      @Output	:
      @Date   : 02/02/2016
     */

    public function makeDir() {
        if (!$this->input->is_ajax_request()) {
            exit('no direct scripts are allowed');
        }
        $dir = $this->input->post('name') . '/';
        $path = $this->input->post('path') . '/';

        if (!empty($dir) && !empty($path)) {

            if (is_dir(BASEPATH . '../' . $path . $dir) === false) {

                mkdir(BASEPATH . '../' . $path . $dir, 0777, true);
                echo json_encode(['status' => 1]);
                die;
            } else {
                echo json_encode(['status' => 0]);
                die;
            }
        } else {
            echo json_encode(['status' => 0]);
            die;
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Image Upload function
      @Input 	:
      @Output	:
      @Date   : 02/02/2016
     */

    public function upload() {
        $json = array();
        if (!$this->input->is_ajax_request()) {
            exit('no direct scripts allowed');
        }

        if ($this->input->get('path')) {
            // Make sure we have the correct directory
            if (($this->input->get('path'))) {
                $directory = $this->input->get('path') . '/';
            } else {
                $directory = 'uploads/';
            }

            // Check its a directory
            if (!is_dir($directory)) {
                $json['error'] = $this->language->get('error_directory');
            }


            if (!$json) {
                $files = $_FILES;
                $config['upload_path'] = $directory;
                $config['allowed_types'] = '*';
                // $config['encrypt_name'] = true;
                //     $config['max_size'] = 100;
                //  $config['max_width'] = 1024;
                //    $config['max_height'] = 768;
                $tmpFile = (isset($_FILES['file'])) ? count($_FILES['file']['name']) : 0;
                if ($tmpFile > 0) {
                    for ($i = 0; $i < $tmpFile; $i++) {
                        $_FILES['file']['name'] = $files['file']['name'][$i];
                        $_FILES['file']['type'] = $files['file']['type'][$i];
                        $_FILES['file']['tmp_name'] = $files['file']['tmp_name'][$i];
                        $_FILES['file']['error'] = $files['file']['error'][$i];
                        $_FILES['file']['size'] = $files['file']['size'][$i];
//                    $content = file_get_contents($_FILES['file']['tmp_name']);
//                    if (preg_match('/\<\?php/i', $content)) {
//                        $json['error'] = 'Invalid File';
//                        echo json_encode($json);
//                        die;
//                    }
                        $this->load->library('upload', $config);
                        if ($this->upload->do_upload('file')) {
                            $json['success'] = 'Uploaded';
                        } else {
                            $json['error'] = $this->upload->display_errors();
                        }
                    }
                } else {
                    $json['error'] = lang('fail_file_upload');
                }
            }
            echo json_encode($json);
        }
    }

    public function deleteImage() {
        if (!$this->input->is_ajax_request()) {
            exit('no direct scripts allowed');
        } else {
            if ($this->input->post('name')) {
                $images = $this->input->post('name');

                if (count($images) > 0) {
                    $this->load->helper("file");
                    foreach ($images as $img) {
                        if (unlink($img)) {
                            $stat = 1;
                        } else {
                            $stat = 0;
                        }
                    }
                    if ($stat == 1) {
                        echo json_encode(array('status' => 1, 'error' => ''));
                        die;
                    } else {
                        echo json_encode(array('status' => 0, 'error' => 'Error While Deletion!'));
                        die;
                    }
                }
            }
        }
    }

    function loadAjaxView() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct scripts are allowed');
        }
        $is_crm = $this->input->get('is_crm');
        $ignoreFolders = array('uploads//assets', 'uploads//css', 'uploads//custom', 'uploads//dist', 'uploads//font-awesome-4.5.0', 'uploads/filemanager//index.html');

        $data['project_view'] = $this->module . '/' . $this->viewname;
        // Make sure we have the correct directory
        $data['refresh'] = $data['parent'] = $directory = 'uploads/filemanager/';
        $directory = 'uploads/filemanager/';
        if ($this->input->get('dir') == 'uploads/') {
            $dir = 'uploads/filemanager';
        } else {
            $dir = $this->input->get('dir');
        }
        if ($dir && $dir != '' && $dir != 'uploads/filemanager' && $dir != 'uploads/filemanager/' && $dir != '.') {
            $directory = $dir . '/';
            $data['refresh'] = $directory = $dir . '/';
            $data['parent'] = dirname($directory);
        }
        $data['is_crm'] = $is_crm;
        if ($is_crm == 0) {

            $data['refresh'] = $data['parent'] = $directory = 'uploads/projectManagement/Project0' . $this->project_id . '/';
            $directory = 'uploads/projectManagement/Project0' . $this->project_id . '/';
            if (($this->input->get('dir')) && $this->input->get('dir') != 'uploads' && $this->input->get('dir') != 'uploads/projectManagement' && $this->input->get('dir') != "'uploads/projectManagement/Project0' . $this->project_id . '/'" && $this->input->get('dir') != '.') {
                $directory = $this->input->get('dir') . '/';
                $data['refresh'] = $directory = $this->input->get('dir') . '/';
                $data['parent'] = dirname($directory);
            }
        }
        $filter_name = null;
        $data['images'] = array();
        $directory = rawurldecode($directory);
        // Get directories
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
        $this->load->view('Filemanager/Ajaxview', $data);
    }

}
