<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class KnowledgeBase extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(1);
        $this->knowledgeBase = $this->uri->segment(2);
        $this->listofmaincategory_data = $this->uri->segment(3);
    }

    /*
      @Author : ishani dave
      @Desc   : view of knowledgebase page
      @Date   : 02/03/2016
     */

    public function index() {
        /**
         * tasks pagination Function called before data variable
         */
        $data['header'] = array('menu_module' => 'support');
        $data['main_content'] = $this->module . '/KnowledgeBase/KnowledgeBase';
        $data['project_view'] = $this->module . 'Support/KnowledgeBase/' . $this->viewname;
        $data['js_content'] = 'loadJsFiles';

        $data['activities_total'] = 0;
        $data['activities'] = 0;
        $data['drag'] = true;
        $num = 10;
        $data['userid'] = $this->session->userdata('LOGGED_IN')['ID'];
        $userid = $data['userid'];

        $table = KNOWLEDGEBASE_MAIN_CATEGORY . ' as kmc';
        if ($userid == '') {
            $where = array("is_delete" => "0", "client_visible" => "1");
        } else {
            $where = array("is_delete" => "0");
        }
        $fields = array('kmc.*');
        $join1 = array(KNOWLEDGEBASE_SUB_CATEGORY . ' as ksc' => 'ksc.main_category_id=kmc.main_category_id');
        $group_by1 = 'kmc.main_category_id';
        $data['main_category'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

        if (count($data['main_category']) > 0) {
            foreach ($data['main_category'] as $row) {
                $data['all_cat'][$row['main_category_id']] = $row;
                if ($userid == '') {
                    $dataArr = $this->common_model->get_records(KNOWLEDGEBASE_SUB_CATEGORY, '', '', '', '', '', $num, '', '', '', '', array("is_delete" => "0", 'main_category_id' => $row['main_category_id'], "client_visible" => "1"));
                } else {
                    $dataArr = $this->common_model->get_records(KNOWLEDGEBASE_SUB_CATEGORY, '', '', '', '', '', $num, '', '', '', '', array("is_delete" => "0", 'main_category_id' => $row['main_category_id']));
                }

                if (count($dataArr) > 0) {

                    $data['all_cat'][$row['main_category_id']][] = $dataArr;
                }
            }
        }


        $table2 = KNOWLEDGEBASE_KNOWLEDGE_ARTICLE . ' as ka';
        if ($userid == '') {
            $where2 = array("is_delete" => "0", "client_visible" => "1");
        } else {
            $where2 = array("is_delete" => "0");
        }

        $join = array(KNOWLEDGEBASE_LIKE . ' as kl' => 'kl.article_id=ka.article_id');
        $fields2 = array('kl.*', 'ka.*', '(select count(id) from blzdsk_knowledgebase_like as bkl where ka.article_id=bkl.article_id and bkl.like = 1 and bkl.user_id = "' . $userid . '") as like_count');
        $sortfield = 'ka.article_id';
        $sortby = 'desc';
        $group_by = 'ka.article_id';
        $data['article'] = $this->common_model->get_records($table2, $fields2, $join, 'LEFT', '', '', $num, '', $sortfield, $sortby, $group_by, $where2);

        $article = $data['article'];
        $data['result'] = array();
        foreach ($article as $row) {
            $article_id = $row['article_id'];
            $table4 = KNOWLEDGEBASE_LIKE . ' as kl';
            $where4 = array("kl.like" => "1", "kl.article_id" => $article_id);
            $fields4 = array('kl.*', 'COUNT(kl.like) as like_count');
            $data['result'][] = $this->common_model->get_records($table4, $fields4, '', '', '', '', '', '', '', '', '', $where4);
        }
        $this->parser->parse('layouts/SupportTemplate', $data);
    }

    //View of Addmain category
    public function AddMainCategory() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {

            $data['userid'] = $this->session->userdata('LOGGED_IN')['ID'];
            $userid = $data['userid'];
            if ($userid != '') {
                $data = array();
                $data['project_view'] = $this->viewname;
                $redirect_link = $this->input->post('redirect_link');
                $data['articleOwner'] = $this->session->userdata('LOGGED_IN')['ID'];

                $table = LOGIN . ' as l';
                $where = array("l.status" => "1", "l.is_delete" => "0");
                $fields = array("l.*");
                $data['user'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
                $table_type_master = SUPPORT_TYPE . ' as st';
                $match_type_master = "st.is_delete=0";
                $fields_type_master = array("st.support_type_id,st.type");
                $data['type_data'] = $this->common_model->get_records($table_type_master, $fields_type_master, '', '', $match_type_master);

                $pm_params = array();
                $pm_params['table'] = PRODUCT_MASTER . ' as pm';
                $pm_params['fields'] = array("pm.product_id,pm.product_name");
                $pm_params['where_in'] = array("pm.status" => "1", "pm.is_delete" => "0");
                $data['product_info'] = $this->common_model->get_records_array($pm_params);
                $this->load->view('KnowledgeBase/AddMainCategory', $data);
            } else {
                redirect($this->viewname . '/KnowledgeBase');
            }
        }
    }

    //insert main category
    public function saveMainCategory() {

        $product_data = $this->input->post('product_id');
        $product = implode(",", $product_data);

        $knowledgebasecat['category_name'] = $this->input->post('category_name');
        $client_visible_data = '0';
        if ($this->input->post('client_visible') == 'on') {
            $client_visible_data = 1;
        }
        $knowledgebasecat['client_visible'] = $client_visible_data;
        $product_related_data = '0';
        if ($this->input->post('product_related') == 'on') {
            $product_related_data = '1';
        }
        $knowledgebasecat['product_related'] = $product_related_data;
        $type_name = $this->input->post('type_id');
        $table = SUPPORT_TYPE . ' as st';
        $match = "st.type='" . addslashes($type_name) . "' and is_delete=0 ";
        $fields = array("st.type, st.support_type_id");
        $type_record = $this->common_model->get_records($table, $fields, '', '', $match);
        if ($type_record) {
            $knowledgebasecat['type_id'] = $type_record[0]['support_type_id'];
        } else {
            $type_data['type'] = $type_name;
        }
        if (count($type_record) == 0) {
            //INSERT Branch
            $type_data['status'] = 1;
            $type_id = $this->common_model->insert(SUPPORT_TYPE, $type_data);
            $knowledgebasecat['type_id'] = $type_id;
        }


        $knowledgebasecat['article_owner'] = $this->input->post('article_owner');
        $knowledgebasecat['product_id'] = $product;
        $knowledgebasecat['created_date'] = datetimeformat();
        $knowledgebasecat['modified_date'] = datetimeformat();


        if (($_FILES['icon_image']['name']) != NULL) {
//            $config = array(
//                'upload_path' => "./uploads/knowledgebase/",
//                'allowed_types' => "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG",
//                'max_width' => "28",
//                'max_height' => "28",
//                    //'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
//            );
//
//            $this->load->library('upload', $config);

            $resize = array(
                'upload_path' => "./uploads/knowledgebase/",
                'allowed_types' => "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG",
                'width' => "28",
                'height' => "28",
                'overwrite' => "true",
                    //'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
            );
            $this->load->library('upload', $resize);

            if ($this->upload->do_upload('icon_image')) {

                $file_data = array('upload_data' => $this->upload->data());
                foreach ($file_data as $file) {
                    $knowledgebasecat['icon_image'] = $file['file_name'];
                }
            } else {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
            }
        }
        $this->common_model->insert(KNOWLEDGEBASE_MAIN_CATEGORY, $knowledgebasecat);
        $msg = $this->lang->line('category_add_msg');
        $this->session->set_flashdata('msg', $msg);

        //Loading View
        redirect($this->viewname . '/KnowledgeBase/ListofMainCategory', $data);
    }

    //View of addsubcategory
    public function AddSubCategory() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {

            $data['userid'] = $this->session->userdata('LOGGED_IN')['ID'];
            $userid = $data['userid'];
            if ($userid != '') {


                $data = array();
                $data['project_view'] = $this->viewname;
                $redirect_link = $this->input->post('redirect_link');
                $table1 = KNOWLEDGEBASE_MAIN_CATEGORY . ' as kcm';
                $where1 = array("kcm.is_delete" => "0");
                $fields1 = array("kcm.*");
                $data['main_category'] = $this->common_model->get_records($table1, $fields1, '', '', '', '', '', '', '', '', '', $where1);
                $data['articleOwner'] = $this->session->userdata('LOGGED_IN')['ID'];
                $table = LOGIN . ' as l';
                $where = array("l.status" => "1", "l.is_delete" => "0");
                $fields = array("l.*");
                $data['user'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
                $pm_params = array();
                $pm_params['table'] = PRODUCT_MASTER . ' as pm';
                $pm_params['fields'] = array("pm.product_id,pm.product_name");
                $pm_params['where_in'] = array("pm.status" => "1", "pm.is_delete" => "0");
                $data['product_info'] = $this->common_model->get_records_array($pm_params);

                $this->load->view('/KnowledgeBase/AddSubCategory', $data);
            } else {
                redirect($this->viewname . '/KnowledgeBase');
            }
        }
    }

    //get related products
    public function getProducts() {
        $data = array();

        $main_id = $this->input->post('id');
        if (!empty($main_id)) {
            $table2 = KNOWLEDGEBASE_MAIN_CATEGORY . ' as kmc';
            $where2 = array("kmc.is_delete" => "0", "kmc.main_category_id" => $main_id);
            $fields2 = array("kmc.product_id");
            $data['main_id'] = $this->common_model->get_records($table2, $fields2, '', '', '', '', '', '', '', '', '', $where2);
            $maincat_id = $data['main_id'];

            $product_info = array();
            foreach ($maincat_id as $row) {
                $product_info[] = $row['product_id'];
            }
            $product = $product_info;

            if ($product[0] != '') {
                $data['product_data'] = explode(",", $product[0]);
                $product_data = $data['product_data'];
            } else {
                $product_data = 0;
            }
            $data1['product'] = array();
            if ($product_data != 0) {
                foreach ($product_data as $row) {
                    $product_id = $row;
                    $table4 = PRODUCT_MASTER . ' as pm';
                    $where4 = array("pm.is_delete" => "'0'", "pm.product_id" => "'$product_id'");
                    $fields4 = array("pm.product_id");
                    $data1['product'][] = $this->common_model->get_records($table4, $fields4, '', '', '', '', '', '', '', '', '', $where4);
                }
            } else {
                $data1['product'] = 0;
            }

            $pm_params = array();
            $pm_params['table'] = PRODUCT_MASTER . ' as pm';
            $pm_params['fields'] = array("pm.product_id,pm.product_name");
            $pm_params['where_in'] = array("pm.status" => "1", "pm.is_delete" => "0");
            $data1['productnew_info'] = $this->common_model->get_records_array($pm_params);
            echo json_encode($data1);
        }
    }

    //insert subcategory
    public function saveSubCategory() {

        $product_data = $this->input->post('product_id');
        $product = implode(",", $product_data);

        $client_visible_data = '0';
        if ($this->input->post('client_visible') == 'on') {
            $client_visible_data = 1;
        }
        $product_related_data = '0';
        if ($this->input->post('product_related') == 'on') {
            $product_related_data = '1';
        }
        $knowledgebasesubcat['sub_category_name'] = $this->input->post('sub_category_name');
        $knowledgebasesubcat['main_category_id'] = $this->input->post('main_category_id');
        $knowledgebasesubcat['client_visible'] = $client_visible_data;
        $knowledgebasesubcat['article_owner'] = $this->input->post('article_owner');
        $knowledgebasesubcat['product_related'] = $product_related_data;
        $knowledgebasesubcat['product_id'] = $product;
        $knowledgebasesubcat['created_date'] = datetimeformat();
        $knowledgebasesubcat['modified_date'] = datetimeformat();
        //Transfering data to Model
        $this->common_model->insert(knowledgebase_sub_category, $knowledgebasesubcat);
        $msg = $this->lang->line('sub_category_add_msg');
        $this->session->set_flashdata('msg', $msg);
        //Loading View
        redirect($this->viewname . '/KnowledgeBase/ListofSubCategory', $data);
    }

    // view of add article        
    public function AddArticle() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $data['userid'] = $this->session->userdata('LOGGED_IN')['ID'];
            $userid = $data['userid'];
            if ($userid != '') {

                $data = array();
                $data['project_view'] = $this->viewname;
                $data['drag'] = true;
                $redirect_link = $this->input->post('redirect_link');
                $table = KNOWLEDGEBASE_MAIN_CATEGORY . ' as kcm';
                $where = array("kcm.is_delete" => "0");
                $fields = array("kcm.*");
                $data['main_category'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
                $table = KNOWLEDGEBASE_SUB_CATEGORY . ' as ksc';
                $where = array("ksc.is_delete" => "0");
                $fields = array("ksc.*");
                $data['sub_category'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

                $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
                $data['article_view'] = $this->viewname;
                $data['articleOwner'] = $this->session->userdata('LOGGED_IN')['ID'];
                $table = LOGIN . ' as l';
                $where = array("l.status" => "1", "l.is_delete" => "0");
                $fields = array("l.*");
                $data['user'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

                $pm_params = array();
                $pm_params['table'] = PRODUCT_MASTER . ' as pm';
                $pm_params['fields'] = array("pm.product_id,pm.product_name");
                $pm_params['where_in'] = array("pm.status" => "1", "pm.is_delete" => "0");
                $data['product_info'] = $this->common_model->get_records_array($pm_params);

                $this->load->view('/KnowledgeBase/AddArticle', $data);
            } else {
                redirect($this->viewname . '/KnowledgeBase');
            }
        }
    }

    // get sub category data
    public function getSubCategories() {
        $data = array();

        $main_id = $this->input->post('id');
        $table2 = KNOWLEDGEBASE_SUB_CATEGORY . ' as ksc';
        $where2 = array("ksc.is_delete" => "0", "ksc.main_category_id" => $main_id);
        $fields2 = array("ksc.*");
        $data['sub'] = $this->common_model->get_records($table2, $fields2, '', '', '', '', '', '', '', '', '', $where2);
        echo json_encode($data['sub']);
    }

    // insert article data
    public function saveArticle() {
        $id = '';
        $opportunity_requirement_id = '';
        //Get input post prospect_id if have
        if ($this->input->post('article_id')) {
            $article_id = $this->input->post('article_id');
        }
        $product_data = $this->input->post('product_id');
        $product = implode(",", $product_data);
        $client_visible_data = '0';
        if ($this->input->post('client_visible') == 'on') {
            $client_visible_data = 1;
        }
        $product_related_data = '0';
        if ($this->input->post('product_related') == 'on') {
            $product_related_data = '1';
        }
        $knowledgebasearticle['article_name'] = $this->input->post('article_name');
        $knowledgebasearticle['main_category_id'] = $this->input->post('main_category_id');
        $knowledgebasearticle['sub_category_id'] = $this->input->post('sub_category_id');
        $knowledgebasearticle['article_description'] = $this->input->post('article_description', false);
        $knowledgebasearticle['client_visible'] = $client_visible_data;
        $knowledgebasearticle['article_owner'] = $this->input->post('article_owner');
        $knowledgebasearticle['product_related'] = $product_related_data;
        $knowledgebasearticle['product_id'] = $product;
        $knowledgebasearticle['created_date'] = datetimeformat();
        $knowledgebasearticle['modified_date'] = datetimeformat();
//Transfering data to Model
//Insert Record in Database
        if (!empty($article_id)) {
            $where = array('article_id' => $article_id);
            $success_update = $this->common_model->update(KNOWLEDGEBASE_KNOWLEDGE_ARTICLE, $knowledgebasearticle, $where);
            if ($success_update) {
                $msg = $this->lang->line('account_update_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        } //Update Record in Database
        else {

            $article_new_id = $this->common_model->insert(KNOWLEDGEBASE_KNOWLEDGE_ARTICLE, $knowledgebasearticle);

            if ($article_new_id) {
                $msg = $this->lang->line('account_add_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }
        //upload file
        $data['article_view'] = $this->viewname;
        $file_name = array();
        $file_array1 = $this->input->post('file_data');

        $file_name = $_FILES['article_files']['name'];
        if (count($file_name) > 0 && count($file_array1) > 0) {
            $differentedImage = array_diff($file_name, $file_array1);
            foreach ($file_name as $file) {
                if (in_array($file, $differentedImage)) {
                    $key_data[] = array_search($file, $file_name); // $key = 2;
                }
            }
            if (!empty($key_data)) {
                foreach ($key_data as $key) {
                    unset($_FILES['article_files']['name'][$key]);
                    unset($_FILES['article_files']['type'][$key]);
                    unset($_FILES['article_files']['tmp_name'][$key]);
                    unset($_FILES['article_files']['error'][$key]);
                    unset($_FILES['article_files']['size'][$key]);
                }
            }
        }
        $_FILES['article_files'] = $arr = array_map('array_values', $_FILES['article_files']);
        $upload_data = uploadImage('article_files', knowledge_upload_path, $data['article_view']);
        $knowledgefiles = array();
        foreach ($upload_data as $dataname) {
            $knowledgefiles[] = $dataname['file_name'];
        }
        $knowledge_file_str = implode(",", $knowledgefiles);

        $file2 = $this->input->post('fileToUpload');
        if (!(empty($file2))) {
            $file_data = implode(",", $file2);
        } else {
            $file_data = '';
        }
        if (!empty($knowledge_file_str) && !empty($file_data)) {
            $compaigndata['file'] = $knowledge_file_str . ',' . $file_data;
        } else if (!empty($knowledge_file_str)) {
            $compaigndata['file'] = $knowledge_file_str;
        } else {
            $compaigndata['file'] = $file_data;
        }
        $compaigndata['file_name'] = $file_data;
        if ($compaigndata['file_name'] != '') {
            $explodedData = explode(',', $compaigndata['file_name']);
            foreach ($explodedData as $img) {
                array_push($upload_data, array('file_name' => $img));
            }
        }
        $article_files_data = array();
        if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $prospect_files = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {
                    if ($article_new_id) {
                        $article_files_data[] = ['file_name' => $prospect_files[$i], 'file_path' => $gallery_path[$i], 'article_id' => $article_new_id, 'upload_status' => 0, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    } else {
                        $article_files_data[] = ['file_name' => $prospect_files[$i], 'file_path' => $gallery_path[$i], 'article_id' => $article_id, 'upload_status' => 0, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    }
                }
            }
        }
        if (count($upload_data) > 0) {
            foreach ($upload_data as $files) {
                if (!empty($article_id)) {
                    $article_files_data[] = ['file_name' => $files['file_name'], 'file_path' => knowledge_upload_path, 'article_id' => $article_id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                } else {
                    $article_files_data[] = ['file_name' => $files['file_name'], 'file_path' => knowledge_upload_path, 'article_id' => $article_new_id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                }
            }
        }
        if (count($article_files_data) > 0) {
            if ($article_id) {
                $where = array('article_id' => $article_id);
            } else {
                $where = array('article_id' => $article_new_id);
            }

            if (!$this->common_model->insert_batch(KNOWLEDGEBASE_FILE_MASTER, $article_files_data)) {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }
        $msg = $this->lang->line('article_add_msg');
        $this->session->set_flashdata('msg', $msg);
        //Loading View
        redirect($this->viewname . '/KnowledgeBase/ListofArticle', $data);
    }

    // upload file
    public function upload_file($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents($this->config->item('knowledge_img_url') . '/' . $filename, $str);
    }

    //list of main category
    public function ListofMainCategory() {
        $data['userid'] = $this->session->userdata('LOGGED_IN')['ID'];
        $userid = $data['userid'];
        if ($userid != '') {

            $data['drag'] = true;
            $data['header'] = array('menu_module' => 'support');
            $searchtext = '';
            $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('list_of_main_category_data');
            }
            $searchsort_session = $this->session->userdata('list_of_main_category_data');
            //Sorting
            if (!empty($sortfield) && !empty($sortby)) {
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            } else {
                if (!empty($searchsort_session['sortfield'])) {
                    $data['sortfield'] = $searchsort_session['sortfield'];
                    $data['sortby'] = $searchsort_session['sortby'];
                    $sortfield = $searchsort_session['sortfield'];
                    $sortby = $searchsort_session['sortby'];
                } else {
                    $sortfield = 'main_category_id';
                    $sortby = 'desc';
                    $data['sortfield'] = $sortfield;
                    $data['sortby'] = $sortby;
                }
            }
            //Search text
            if (!empty($searchtext)) {
                $data['searchtext'] = $searchtext;
            } else {
                if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                    $data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext = $data['searchtext'];
                } else {
                    $data['searchtext'] = '';
                }
            }
            if (!empty($perpage) && $perpage != 'null') {
                $data['perpage'] = $perpage;
                $config['per_page'] = $perpage;
            } else {
                if (!empty($searchsort_session['perpage'])) {
                    $data['perpage'] = trim($searchsort_session['perpage']);
                    $config['per_page'] = trim($searchsort_session['perpage']);
                } else {
                    $config['per_page'] = '10';
                    $data['perpage'] = '10';
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
            $config['base_url'] = base_url() . $this->viewname . '/KnowledgeBase/ListofMainCategory/index';
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 5;
                $uri_segment = 3;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(2) . '/' . $this->uri->segment(3);
            }
            //Query
            $table = KNOWLEDGEBASE_MAIN_CATEGORY . ' as kcm';
            $where = "kcm.is_delete=0";
            $fields = array("kcm.main_category_id,kcm.category_name,kcm.type_id,kcm.client_visible,kcm.article_owner,kcm.product_related,kcm.product_id,kcm.created_date,kcm.status,u.firstname,u.lastname,pm.product_id,pm.product_name,st.type");
            $join_tables = array('blzdsk_login as u' => 'u.login_id= kcm.article_owner ', 'blzdsk_product_master as pm' => 'pm.product_id= kcm.product_id', 'blzdsk_support_type as st' => 'st.support_type_id= kcm.type_id');

            if (!empty($searchtext)) {
                $date_from = date('Y-m-d', strtotime($searchtext));

                $searchtext = html_entity_decode(trim($searchtext));
                $where_search = '(kcm.category_name LIKE "%' . $searchtext . '%" OR concat(u.firstname," ",u.lastname)  LIKE "%' . $searchtext . '%" OR st.type LIKE "%' . $searchtext . '%"  OR pm.product_name LIKE "%' . $searchtext . '%" OR kcm.created_date LIKE "%' . $date_from . '%")';
                $data['main_category_info'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $this->uri->segment(5), $sortfield, $sortby, '', $where, '', '', '', '', '', $where_search);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1', '', '', $where_search);
                $searchtext = html_entity_decode(trim($searchtext));
            } else {
                $data['main_category_info'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $this->uri->segment(5), $sortfield, $sortby, '', $where);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
            }

            $this->ajax_pagination->initialize($config);
            $data['pagination'] = $this->ajax_pagination->create_links();
            $data['uri_segment'] = $uri_segment;
            $sortsearchpage_data = array(
                'sortfield' => $data['sortfield'],
                'sortby' => $data['sortby'],
                'searchtext' => $data['searchtext'],
                'perpage' => trim($data['perpage']),
                'uri_segment' => $uri_segment,
                'total_rows' => $config['total_rows']);
            $this->session->set_userdata('list_of_main_category_data', $sortsearchpage_data);
            $data['sales_view'] = $this->viewname;

            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/KnowledgeBase/ajax_list_main_category', $data);
            } else {
                $data['main_content'] = '/KnowledgeBase/' . $this->listofmaincategory_data;
                $this->parser->parse('layouts/SupportTemplate', $data);
            }
        } else {
            redirect($this->viewname . '/KnowledgeBase');
        }
    }

    // list of sub category
    public function ListofSubCategory() {
        $data['userid'] = $this->session->userdata('LOGGED_IN')['ID'];
        $userid = $data['userid'];
        if ($userid != '') {

            $data['drag'] = true;
            $searchtext = '';
            $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('list_of_sub_category_data');
            }
            $searchsort_session = $this->session->userdata('list_of_sub_category_data');
            //Sorting
            if (!empty($sortfield) && !empty($sortby)) {
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            } else {
                if (!empty($searchsort_session['sortfield'])) {
                    $data['sortfield'] = $searchsort_session['sortfield'];
                    $data['sortby'] = $searchsort_session['sortby'];
                    $sortfield = $searchsort_session['sortfield'];
                    $sortby = $searchsort_session['sortby'];
                } else {
                    $sortfield = 'sub_category_id';
                    $sortby = 'desc';
                    $data['sortfield'] = $sortfield;
                    $data['sortby'] = $sortby;
                }
            }
            //Search text
            if (!empty($searchtext)) {
                $data['searchtext'] = $searchtext;
            } else {
                if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                    $data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext = $data['searchtext'];
                } else {
                    $data['searchtext'] = '';
                }
            }
            if (!empty($perpage) && $perpage != 'null') {
                $data['perpage'] = $perpage;
                $config['per_page'] = $perpage;
            } else {
                if (!empty($searchsort_session['perpage'])) {
                    $data['perpage'] = trim($searchsort_session['perpage']);
                    $config['per_page'] = trim($searchsort_session['perpage']);
                } else {
                    $config['per_page'] = '10';
                    $data['perpage'] = '10';
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
            $config['base_url'] = base_url() . $this->viewname . '/KnowledgeBase/ListofSubCategory/index';

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 5;
                $uri_segment = 3;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(2) . '/' . $this->uri->segment(3);
            }
            //Query

            $table = KNOWLEDGEBASE_SUB_CATEGORY . ' as ksc';
            $where = "ksc.is_delete=0";
            $fields = array("ksc.sub_category_id,ksc.sub_category_name,ksc.main_category_id,ksc.client_visible,ksc.article_owner,ksc.product_related,ksc.product_id,ksc.created_date,ksc.status,u.firstname,u.lastname,pm.product_id,pm.product_name,kmc.main_category_id,kmc.category_name");
            $join_tables = array('blzdsk_login as u' => 'u.login_id= ksc.article_owner', 'blzdsk_product_master as pm' => 'pm.product_id= ksc.product_id', 'blzdsk_knowledgebase_main_category as kmc' => 'kmc.main_category_id= ksc.main_category_id');

            if (!empty($searchtext)) {
                $date_from = date('Y-m-d', strtotime($searchtext));
                $searchtext = html_entity_decode(trim($searchtext));
                $where_search = '(ksc.sub_category_name LIKE "%' . $searchtext . '%" OR concat(u.firstname," ",u.lastname) LIKE "%' . $searchtext . '%" OR kmc.category_name LIKE "%' . $searchtext . '%"  OR pm.product_name LIKE "%' . $searchtext . '%" OR ksc.created_date LIKE "%' . $date_from . '%")';
                $data['sub_category_info'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $this->uri->segment(5), $sortfield, $sortby, '', $where, '', '', '', '', '', $where_search);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1', '', '', $where_search);
                $searchtext = html_entity_decode(trim($searchtext));
            } else {
                $data['sub_category_info'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $this->uri->segment(5), $sortfield, $sortby, '', $where);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
            }

            $this->ajax_pagination->initialize($config);
            $data['pagination'] = $this->ajax_pagination->create_links();
            $data['uri_segment'] = $uri_segment;
            $sortsearchpage_data = array(
                'sortfield' => $data['sortfield'],
                'sortby' => $data['sortby'],
                'searchtext' => $data['searchtext'],
                'perpage' => trim($data['perpage']),
                'uri_segment' => $uri_segment,
                'total_rows' => $config['total_rows']);
            $this->session->set_userdata('list_of_sub_category_data', $sortsearchpage_data);

            $data['sales_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'support');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/KnowledgeBase/ajax_list_sub_category', $data);
            } else {
                $data['main_content'] = '/KnowledgeBase/' . $this->listofmaincategory_data;
                $this->parser->parse('layouts/SupportTemplate', $data);
            }
        } else {
            redirect($this->viewname . '/KnowledgeBase');
        }
    }

    // delete main category
    public function deletedata($id) {

        //Delete Record From Database
        $redirect_link = $this->input->get('link');
        $data['main_category'] = $this->viewname;
        if (!empty($id)) {
            $where = array('main_category_id' => $id);
            $main_category_data['is_delete'] = 1;

            $table = KNOWLEDGEBASE_SUB_CATEGORY . ' as ksc';
            $match = "ksc.main_category_id='" . $id . "' and is_delete=0 ";
            $fields = array("ksc.main_category_id");
            $sub_category_record = $this->common_model->get_records($table, $fields, '', '', $match);

            if (count($sub_category_record) == 0) {

                $table = KNOWLEDGEBASE_KNOWLEDGE_ARTICLE . ' as ka';
                $match = "ka.main_category_id='" . $id . "' and is_delete=0 ";
                $fields = array("ka.main_category_id");
                $article_category_record = $this->common_model->get_records($table, $fields, '', '', $match);
                if (count($article_category_record) == 0) {
                    $delete_suceess = $this->common_model->update(blzdsk_knowledgebase_main_category, $main_category_data, $where);
                } else {
                    $msg = $this->lang->line('article_nt_delete');
                    $this->session->set_flashdata('error', $msg);
                }
            } else {
                $msg = $this->lang->line('category_nt_delete');
                $this->session->set_flashdata('error', $msg);
            }

            if ($delete_suceess) {
                $msg = $this->lang->line('category_del_msg');
                $this->session->set_flashdata('msg', $msg);
            }
            unset($id);
        }
        redirect($this->viewname . '/KnowledgeBase/ListofMainCategory'); //Redirect On Listing page
    }

    //Edit main Category 
    public function edit($id) {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $data['project_view'] = $this->viewname;
            $redirect_link = $this->input->post('redirect_link');

            $fields = array('kmc.*', 'pm.product_name', 'login.login_id', 'login.firstname', 'login.lastname', 'st.*');
            $where = array("kmc.main_category_id" => $id);
            $main_table = array("knowledgebase_main_category kmc");

            $join_tables = array('product_master pm' => 'pm.product_id=kmc.product_id', 'login' => 'login.login_id=kmc.article_owner', 'blzdsk_support_type as st' => 'st.support_type_id= kmc.type_id');
            $data['edit_record'] = $this->common_model->get_records($main_table, $fields, $join_tables, 'LEFT', '', '', '', '', '', '', '', '', '', $where);

            $table = LOGIN . ' as l';
            $where = array("l.status" => "1", "l.is_delete" => "0");
            $fields = array("l.*");
            $data['user'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            $table_type_master = SUPPORT_TYPE . ' as st';
            $match_type_master = "st.is_delete=0";
            $fields_type_master = array("st.support_type_id,st.type");
            $data['type_data'] = $this->common_model->get_records($table_type_master, $fields_type_master, '', '', $match_type_master);

            $table2 = SUPPORT_TYPE . ' as st';
            $match2 = "st.support_type_id=(SELECT kmc.type_id from blzdsk_knowledgebase_main_category as kmc WHERE kmc.main_category_id = " . $id . ") and st.is_delete=0 ";
            $fields2 = array("st.support_type_id,st.type");
            $data['type_data1'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);

            $pm_params = array();
            $pm_params['table'] = PRODUCT_MASTER . ' as pm';
            $pm_params['fields'] = array("pm.product_id,pm.product_name");
            $pm_params['where_in'] = array("pm.status" => "1");
            $data['product_info'] = $this->common_model->get_records_array($pm_params);

            $product_info = array();
            foreach ($data['edit_record'] as $row) {
                $product_info[] = $row['product_id'];
            }
            $product = $product_info;
            $data['product_data'] = explode(",", $product[0]);

            $this->load->view('/KnowledgeBase/EditMainCategory', $data);
        }
    }

    // update main category
    public function updatedata() {
        $id = $id = $this->input->post('m_id');
        $data['project_view'] = $this->viewname;

        $redirect_link = $this->input->post('redirect_link');
        $product_data = $this->input->post('product_id');
        $product = implode(",", $product_data);
        $updatecat['category_name'] = $this->input->post('category_name');
        $client_visible_data = '0';
        if ($this->input->post('client_visible') == 'on') {
            $client_visible_data = 1;
        }
        $updatecat['client_visible'] = $client_visible_data;

        $product_related_data = '0';
        if ($this->input->post('product_related') == 'on') {
            $product_related_data = '1';
        }

        $updatecat['product_related'] = $product_related_data;
        $type_name = $this->input->post('type_id');
        $table = SUPPORT_TYPE . ' as st';
        $match = "st.type='" . $type_name . "' and is_delete=0 ";
        $fields = array("st.type, st.support_type_id");
        $type_record = $this->common_model->get_records($table, $fields, '', '', $match);
        if ($type_record) {
            $updatecat['type_id'] = $type_record[0]['support_type_id'];
        } else {
            $type_data['type'] = $type_name;
        }
        if (count($type_record) == 0) {
            //INSERT Branch
            $type_data['status'] = 1;
            $type_id = $this->common_model->insert(SUPPORT_TYPE, $type_data);
            $updatecat['type_id'] = $type_id;
        }
        $updatecat['article_owner'] = $this->input->post('article_owner');
        $updatecat['product_id'] = $product;

        if (($_FILES['icon_image']['name']) != NULL) {
//            $config = array(
//                'upload_path' => "./uploads/knowledgebase/",
//                'allowed_types' => "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG",
//                'max_width' => "28",
//                'max_height' => "28",
//                    //'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
//            );
//
//            $this->load->library('upload', $config);
            $resize = array(
                'upload_path' => "./uploads/knowledgebase/",
                'allowed_types' => "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG",
                'width' => "28",
                'height' => "28",
                'overwrite' => "true",
                    //'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
            );
            $this->load->library('upload', $resize);
            if ($this->upload->do_upload('icon_image')) {

                $file_data = array('upload_data' => $this->upload->data());
                foreach ($file_data as $file) {
                    $updatecat['icon_image'] = $file['file_name'];
                    $updatecat['modified_date'] = datetimeformat();
                }
            } else {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
            }
        }
        $table = KNOWLEDGEBASE_MAIN_CATEGORY . ' as kmc';
        $where = "main_category_id = $id ";
        $data['update_record'] = $this->db->where($where)->update($table, $updatecat);

        $msg = $this->lang->line('category_edit_msg');
        $this->session->set_flashdata('msg', $msg);



        redirect($this->viewname . '/KnowledgeBase/ListofMainCategory');
    }

    //delete sub category
    public function deletedatasub($id) {

        $redirect_link = $this->input->get('link');
        $data['sub_category'] = $this->viewname;
        if (!empty($id)) {
            $where = array('sub_category_id' => $id);
            $sub_category_data['is_delete'] = 1;

            $table = KNOWLEDGEBASE_KNOWLEDGE_ARTICLE . ' as ka';
            $match = "ka.sub_category_id='" . $id . "' and is_delete=0 ";
            $fields = array("ka.sub_category_id");
            $article_category_record = $this->common_model->get_records($table, $fields, '', '', $match);
            //  echo $this->db->last_query();exit;
            if (count($article_category_record) == 0) {
                $delete_suceess = $this->common_model->update(blzdsk_knowledgebase_sub_category, $sub_category_data, $where);
            } else {
                $msg = $this->lang->line('sub_article_nt_delete');
                $this->session->set_flashdata('error', $msg);
            }

            if ($delete_suceess) {
                $msg = $this->lang->line('sub_category_del_msg');
                $this->session->set_flashdata('msg', $msg);
            }

            unset($id);
        }
        redirect($this->viewname . '/KnowledgeBase/ListofSubCategory'); //Redirect On Listing page
    }

    //edit subcategory
    public function editsubcat($id) {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $data['project_view'] = $this->viewname;

            $redirect_link = $this->input->post('redirect_link');

            $fields = array('ksc.*', 'pm.product_name', 'login.login_id', 'login.firstname', 'login.lastname', 'kmc.main_category_id', 'kmc.category_name');
            $where = array("ksc.sub_category_id" => $id);
            $main_table = array("knowledgebase_sub_category ksc");

            $join_tables = array('product_master pm' => 'pm.product_id=ksc.product_id', 'login' => 'login.login_id=ksc.article_owner', 'knowledgebase_main_category kmc' => 'kmc.main_category_id=ksc.main_category_id');

            $data['edit_record'] = $this->common_model->get_records($main_table, $fields, $join_tables, 'LEFT', '', '', '', '', '', '', '', '', '', $where);
            $table = KNOWLEDGEBASE_MAIN_CATEGORY . ' as kmc';
            $where = array("kmc.status" => "1", "kmc.is_delete" => "0");
            $fields = array("kmc.*");
            $data['main_category'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            $table = LOGIN . ' as l';
            $where = array("l.status" => "1", "l.is_delete" => "0");
            $fields = array("l.*");
            $data['user'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            $pm_params = array();
            $pm_params['table'] = PRODUCT_MASTER . ' as pm';
            $pm_params['fields'] = array("pm.product_id,pm.product_name");
            $pm_params['where_in'] = array("pm.status" => "1");
            $data['product_info'] = $this->common_model->get_records_array($pm_params);

            $product_info = array();
            foreach ($data['edit_record'] as $row) {
                $product_info[] = $row['product_id'];
            }
            $product = $product_info;

            if ($product[0] != '') {
                $data['product_data'] = explode(",", $product[0]);
                $product_data = $data['product_data'];
            } else {
                $product_data = 0;
            }
            if (count($product_data) > 0) {

                $product_id = array();
                foreach ($product_data as $result) {
                    $table4 = PRODUCT_MASTER . ' as pm';
                    $where4 = array("pm.product_id" => $result);
                    $fields4 = array("pm.product_id");
                    $data['productdata'] = $this->common_model->get_records($table4, $fields4, '', '', '', '', '', '', '', '', '', $where4);
                    $product_id[] = $data['productdata'];
                }
                $data['productnew_data'] = $product_id;
            }
            $data['redirect_link'] = $_SERVER['HTTP_REFERER'];

            $this->load->view('/KnowledgeBase/EditSubCategory', $data);
        }
    }

    //update sub category
    public function updatesubcat() {
        $sid = $this->input->post('s_id');
        $id = $this->input->post('m_id');
        $link = $this->input->post('link');
        $data['project_view'] = $this->viewname;

        $redirect_link = $this->input->post('redirect_link');
        $product_data = $this->input->post('product_id');
        $product = implode(",", $product_data);
        $updatesubcat['sub_category_name'] = $this->input->post('sub_category_name');

        $client_visible_data = '0';
        if ($this->input->post('client_visible') == 'on') {
            $client_visible_data = 1;
        }
        $updatesubcat['client_visible'] = $client_visible_data;

        $product_related_data = '0';
        if ($this->input->post('product_related') == 'on') {
            $product_related_data = '1';
        }

        $updatesubcat['product_related'] = $product_related_data;
        $updatesubcat['main_category_id'] = $this->input->post('main_category_id');
        $updatesubcat['article_owner'] = $this->input->post('article_owner');
        $updatesubcat['product_id'] = $product;
        $updatesubcat['modified_date'] = datetimeformat();
        $table = KNOWLEDGEBASE_SUB_CATEGORY . ' as kmc';
        $where = "sub_category_id = $sid ";
        $data['update_record'] = $this->db->where($where)->update($table, $updatesubcat);

        $msg = $this->lang->line('sub_category_edit_msg');
        $this->session->set_flashdata('msg', $msg);


//        $redirectlink = $_SERVER['HTTP_REFERER'];
//        redirect($redirectlink);
        if ($link != base_url() . $this->viewname . '/KnowledgeBase/ListofSubCategory') {
            redirect($this->viewname . '/KnowledgeBase/ListofGeneralCategory/' . $id);
        } else {
            redirect($this->viewname . '/KnowledgeBase/ListofSubCategory');
        }
    }

    //view main category
    public function viewdata($id) {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $data['project_view'] = $this->viewname;

            $redirect_link = $this->input->post('redirect_link');

            $fields = array('kmc.*', 'pm.product_name', 'login.login_id', 'login.firstname', 'login.lastname', 'st.type');
            $where = array("kmc.main_category_id" => $id);
            $main_table = array("knowledgebase_main_category kmc");

            $join_tables = array('product_master pm' => 'pm.product_id=kmc.product_id', 'login' => 'login.login_id=kmc.article_owner', 'blzdsk_support_type st' => 'st.support_type_id=kmc.type_id');

            $data['edit_record'] = $this->common_model->get_records($main_table, $fields, $join_tables, 'LEFT', '', '', '', '', '', '', '', '', '', $where);


            $pm_params = array();
            $pm_params['table'] = PRODUCT_MASTER . ' as pm';
            $pm_params['fields'] = array("pm.product_id,pm.product_name");
            $pm_params['where_in'] = array("pm.status" => "1");
            $data['product_info'] = $this->common_model->get_records_array($pm_params);

            $product_info = array();
            foreach ($data['edit_record'] as $row) {
                $product_info[] = $row['product_id'];
            }
            $product = $product_info;
            $data['product_data'] = explode(",", $product[0]);

            $this->load->view('/KnowledgeBase/ViewMainCategory', $data);
        }
    }

// view sub category
    public function view($id) {
          if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }else{

        $data['project_view'] = $this->viewname;

        $redirect_link = $this->input->post('redirect_link');

        $fields = array('ksc.*', 'pm.product_name', 'login.login_id', 'login.firstname', 'login.lastname', 'kmc.main_category_id', 'kmc.category_name');
        $where = array("ksc.sub_category_id" => $id);
        $main_table = array("knowledgebase_sub_category ksc");

        $join_tables = array('product_master pm' => 'pm.product_id=ksc.product_id', 'login' => 'login.login_id=ksc.article_owner', 'knowledgebase_main_category kmc' => 'kmc.main_category_id=ksc.main_category_id');

        $data['edit_record'] = $this->common_model->get_records($main_table, $fields, $join_tables, 'LEFT', '', '', '', '', '', '', '', '', '', $where);

        $pm_params = array();
        $pm_params['table'] = PRODUCT_MASTER . ' as pm';
        $pm_params['fields'] = array("pm.product_id,pm.product_name");
        $pm_params['where_in'] = array("pm.status" => "1");
        $data['product_info'] = $this->common_model->get_records_array($pm_params);

        $product_info = array();
        foreach ($data['edit_record'] as $row) {
            $product_info[] = $row['product_id'];
        }
        $product = $product_info;
        $data['product_data'] = explode(",", $product[0]);

        $this->load->view('/KnowledgeBase/ViewSubCategory', $data);
    }
    }

    //list of article
    public function ListofArticle() {
        $data['userid'] = $this->session->userdata('LOGGED_IN')['ID'];
        $userid = $data['userid'];
        if ($userid != '') {

            $data['header'] = array('menu_module' => 'support');
            $data['drag'] = true;
            $searchtext = '';
            $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('list_of_article_data');
            }

            $searchsort_session = $this->session->userdata('list_of_article_data');
            //Sorting
            if (!empty($sortfield) && !empty($sortby)) {
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            } else {
                if (!empty($searchsort_session['sortfield'])) {
                    $data['sortfield'] = $searchsort_session['sortfield'];
                    $data['sortby'] = $searchsort_session['sortby'];
                    $sortfield = $searchsort_session['sortfield'];
                    $sortby = $searchsort_session['sortby'];
                } else {
                    $sortfield = 'article_id';
                    $sortby = 'desc';
                    $data['sortfield'] = $sortfield;
                    $data['sortby'] = $sortby;
                }
            }
            //Search text
            if (!empty($searchtext)) {
                $data['searchtext'] = $searchtext;
            } else {
                if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                    $data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext = $data['searchtext'];
                } else {
                    $data['searchtext'] = '';
                }
            }

            if (!empty($perpage) && $perpage != 'null') {
                $data['perpage'] = $perpage;
                $config['per_page'] = $perpage;
            } else {
                if (!empty($searchsort_session['perpage'])) {
                    $data['perpage'] = trim($searchsort_session['perpage']);
                    $config['per_page'] = trim($searchsort_session['perpage']);
                } else {
                    $config['per_page'] = '10';
                    $data['perpage'] = '10';
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
            $config['base_url'] = base_url() . $this->viewname . '/KnowledgeBase/ListofArticle/index';

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 5;
                $uri_segment = 3;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(2) . '/' . $this->uri->segment(3);
            }

            //Query
            $table = KNOWLEDGEBASE_KNOWLEDGE_ARTICLE . ' as ka';
            $where = "ka.is_delete=0";
            $fields = array("ka.article_id,ka.article_name,ka.created_date,pm.product_name,u.login_id,u.firstname,u.lastname,kmc.main_category_id,kmc.category_name,ksc.sub_category_name");
            $join_tables = array('blzdsk_login as u' => 'u.login_id= ka.article_owner', 'blzdsk_knowledgebase_main_category as kmc' => 'kmc.main_category_id = ka.main_category_id', 'blzdsk_knowledgebase_sub_category as ksc' => 'ksc.sub_category_id = ka.sub_category_id', 'blzdsk_product_master as pm' => 'pm.product_id= ka.product_id');

            if (!empty($searchtext)) {
                $date_from = date('Y-m-d', strtotime($searchtext));
                $searchtext = html_entity_decode(trim($searchtext));
                $where_search = '(ka.article_name LIKE "%' . $searchtext . '%" OR concat(u.firstname," ",u.lastname) LIKE "%' . $searchtext . '%"  OR kmc.category_name LIKE "%' . $searchtext . '%" OR ksc.sub_category_name LIKE "%' . $searchtext . '%"  OR pm.product_name LIKE "%' . $searchtext . '%" OR ka.created_date LIKE "%' . $date_from . '%")';
                $data['article_info'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $this->uri->segment(5), $sortfield, $sortby, '', $where, '', '', '', '', '', $where_search);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1', '', '', $where_search);
                $searchtext = html_entity_decode(trim($searchtext));
            } else {
                $data['article_info'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $this->uri->segment(5), $sortfield, $sortby, '', $where);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
            }
            $this->ajax_pagination->initialize($config);
            $data['pagination'] = $this->ajax_pagination->create_links();
            $data['uri_segment'] = $uri_segment;
            $sortsearchpage_data = array(
                'sortfield' => $data['sortfield'],
                'sortby' => $data['sortby'],
                'searchtext' => $data['searchtext'],
                'perpage' => trim($data['perpage']),
                'uri_segment' => $uri_segment,
                'total_rows' => $config['total_rows']);
            $this->session->set_userdata('list_of_article_data', $sortsearchpage_data);

            $data['sales_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'support');

            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/KnowledgeBase/ajax_list_article', $data);
            } else {
                $data['main_content'] = '/KnowledgeBase/' . $this->listofmaincategory_data;
                $this->parser->parse('layouts/SupportTemplate', $data);
            }
        } else {
            redirect($this->viewname . '/KnowledgeBase');
        }
    }

//edit article
    public function editarticle($id) {
         if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }else{

        $data['project_view'] = $this->viewname;
        $data['drag'] = true;
        $redirect_link = $this->input->post('redirect_link');

        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $fields = array('ka.*', 'pm.product_name', 'login.login_id', 'login.firstname', 'login.lastname', 'kmc.main_category_id', 'kmc.category_name', 'kfm.file_name', 'kfm.file_path', 'ksc.sub_category_id', 'ksc.sub_category_name');
        $where = array("ka.article_id" => $id);
        $main_table = KNOWLEDGEBASE_KNOWLEDGE_ARTICLE . ' as ka';


        $join_tables = array('product_master pm' => 'pm.product_id = ka.product_id', 'login' => 'login.login_id=ka.article_owner', 'knowledgebase_main_category kmc' => 'kmc.main_category_id=ka.main_category_id', 'knowledge_file_master kfm' => 'kfm.article_id=ka.article_id', 'knowledgebase_sub_category ksc' => 'ksc.sub_category_id=ka.sub_category_id');
        $data['edit_record'] = $this->common_model->get_records($main_table, $fields, $join_tables, 'LEFT', '', '', '', '', '', '', '', '', '', $where);

        $table = KNOWLEDGEBASE_MAIN_CATEGORY . ' as kmc';
        $where = array("kmc.status" => "1", "kmc.is_delete" => "0");
        $fields = array("kmc.*");
        $data['main_category'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

        $table = KNOWLEDGEBASE_SUB_CATEGORY . ' as ksc';
        $where = array("ksc.status" => "1", "ksc.is_delete" => "0");
        $fields = array("ksc.*");
        $data['sub_category'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

        $table = LOGIN . ' as l';
        $where = array("l.status" => "1", "l.is_delete" => "0");
        $fields = array("l.*");
        $data['user'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

        $table = KNOWLEDGEBASE_FILE_MASTER . ' as art';
        $where = array("art.article_id" => $id);
        $fields = array("art.*");
        $data['image_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

        $pm_params = array();
        $pm_params['table'] = PRODUCT_MASTER . ' as pm';
        $pm_params['fields'] = array("pm.product_id,pm.product_name");
        $pm_params['where_in'] = array("pm.status" => "1");
        $data['product_info'] = $this->common_model->get_records_array($pm_params);

        $product_info = array();
        foreach ($data['edit_record'] as $row) {
            $product_info[] = $row['product_id'];
        }

        $product = $product_info;
        $data['product_data'] = explode(",", $product[0]);

        $this->load->view('/KnowledgeBase/EditArticle', $data);
    }
    }

    //delete article
    public function deletearticle($id) {

        $redirect_link = $this->input->get('link');
        $data['article'] = $this->viewname;
        if (!empty($id)) {
            $where = array('article_id' => $id);

            $article_data['is_delete'] = 1;

            $delete_suceess = $this->common_model->update(blzdsk_knowledgebase_knowledge_article, $article_data, $where);

            if ($delete_suceess) {
                $msg = $this->lang->line('article_del_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
            unset($id);
        }

        redirect($this->viewname . '/KnowledgeBase/ListofArticle'); //Redirect On Listing page
    }

//update article
    public function updatearticle() {

        $id = $id = $this->input->post('article_id');
        $data['project_view'] = $this->viewname;

        $redirect_link = $this->input->post('redirect_link');
        $product_data = $this->input->post('product_id');
        $product = implode(",", $product_data);
        $updatearticle['article_name'] = $this->input->post('article_name');

        $client_visible_data = '0';
        if ($this->input->post('client_visible') == 'on') {
            $client_visible_data = 1;
        }
        $updatearticle['client_visible'] = $client_visible_data;

        $product_related_data = '0';
        if ($this->input->post('product_related') == 'on') {
            $product_related_data = '1';
        }

        $updatearticle['product_related'] = $product_related_data;
        $updatearticle['main_category_id'] = $this->input->post('main_category_id');
        $updatearticle['sub_category_id'] = $this->input->post('sub_category_id');
        $updatearticle['article_description'] = $this->input->post('article_description', false);
        $updatearticle['article_owner'] = $this->input->post('article_owner');
        $updatearticle['product_id'] = $product;
        $updatearticle['modified_date'] = datetimeformat();
        $table = KNOWLEDGEBASE_KNOWLEDGE_ARTICLE . ' as ka';
        $where = "article_id = $id ";

        $data['update_record'] = $this->db->where($where)->update($table, $updatearticle);

        //upload file
        $data['article_view'] = $this->viewname;
        $file_name = array();
        $file_array1 = $this->input->post('file_data');

        $file_name = $_FILES['article_files']['name'];
        if (count($file_name) > 0 && count($file_array1) > 0) {
            $differentedImage = array_diff($file_name, $file_array1);
            foreach ($file_name as $file) {
                if (in_array($file, $differentedImage)) {
                    $key_data[] = array_search($file, $file_name); // $key = 2;
                }
            }
            if (!empty($key_data)) {
                foreach ($key_data as $key) {
                    unset($_FILES['article_files']['name'][$key]);
                    unset($_FILES['article_files']['type'][$key]);
                    unset($_FILES['article_files']['tmp_name'][$key]);
                    unset($_FILES['article_files']['error'][$key]);
                    unset($_FILES['article_files']['size'][$key]);
                }
            }
        }
        $_FILES['article_files'] = $arr = array_map('array_values', $_FILES['article_files']);
        $upload_data = uploadImage('article_files', knowledge_upload_path, $data['article_view']);

        $knowledgefiles = array();
        foreach ($upload_data as $dataname) {
            $knowledgefiles[] = $dataname['file_name'];
        }
        $knowledge_file_str = implode(",", $knowledgefiles);

        $file2 = $this->input->post('fileToUpload');
        if (!(empty($file2))) {
            $file_data = implode(",", $file2);
        } else {
            $file_data = '';
        }
        if (!empty($knowledge_file_str) && !empty($file_data)) {
            $compaigndata['file'] = $knowledge_file_str . ',' . $file_data;
        } else if (!empty($knowledge_file_str)) {
            $compaigndata['file'] = $knowledge_file_str;
        } else {
            $compaigndata['file'] = $file_data;
        }
        $compaigndata['file_name'] = $file_data;
        if ($compaigndata['file_name'] != '') {
            $explodedData = explode(',', $compaigndata['file_name']);

            foreach ($explodedData as $img) {
                array_push($upload_data, array('file_name' => $img));
            }
        }
        $article_files_data = array();

        if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $prospect_files = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {
                    $article_files_data[] = ['file_name' => $prospect_files[$i], 'file_path' => $gallery_path[$i], 'article_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat(), 'upload_status' => 1];
                }
            }
        }
        if (count($upload_data) > 0) {
            foreach ($upload_data as $files) {

                $article_files_data[] = ['file_name' => $files['file_name'], 'file_path' => knowledge_upload_path, 'article_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
            }
        }

        if (count($article_files_data) > 0) {
            $where = array('article_id' => $id);

            if (!$this->common_model->insert_batch(KNOWLEDGEBASE_FILE_MASTER, $article_files_data)) {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }
        /**
         * SOFT DELETION CODE STARTS MAULIK SUTHAR
         */
        $softDeleteImagesArr = $this->input->post('softDeletedImages');
        $softDeleteImagesUrlsArr = $this->input->post('softDeletedImagesUrls');
        if (count($softDeleteImagesUrlsArr) > 0) {
            foreach ($softDeleteImagesUrlsArr as $urls) {
                unlink(BASEPATH . '../' . $urls);
            }
        }
        if (count($softDeleteImagesUrlsArr) > 0) {
            $dlStr = implode(',', $softDeleteImagesArr);
            $this->common_model->delete(KNOWLEDGEBASE_FILE_MASTER, 'file_id IN(' . $dlStr . ')');
        }
        /*
         * SOFT DELETION CODE ENDS
         */
        $msg = $this->lang->line('article_edit_msg');
        $this->session->set_flashdata('msg', $msg);

        redirect($this->viewname . '/KnowledgeBase/ListofArticle');
    }

    //view article
    public function viewArticle($id) {
        if ($id > 0) {
            $data['drag'] = true;

            $data['main_content'] = '/KnowledgeBase/ViewArticle';

            $data['header'] = array('menu_module' => 'support');
            $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            $redirect_link = $this->input->post('redirect_link');

            $fields = array('ka.*', 'pm.product_name', 'login.login_id', 'login.firstname', 'login.lastname', 'kmc.main_category_id', 'kmc.category_name');
            $data['userid'] = $this->session->userdata('LOGGED_IN')['ID'];
            $userid = $data['userid'];
            if ($userid != '') {
                $where = array("ka.article_id" => $id);
            } else {
                $where = array("ka.article_id" => $id, "ka.client_visible" => "1");
            }
            $main_table = array("knowledgebase_knowledge_article ka");

            $join_tables = array('product_master pm' => 'pm.product_id=ka.product_id', 'login' => 'login.login_id=ka.article_owner', 'knowledgebase_main_category kmc' => 'kmc.main_category_id=ka.main_category_id');

            $data['update_record'] = $this->common_model->get_records($main_table, $fields, $join_tables, 'LEFT', '', '', '', '', '', '', '', '', '', $where);

            if (empty($data['update_record']) && $userid != '') {
                redirect($this->viewname . '/KnowledgeBase/ListofArticle');
            } if (empty($data['update_record'])) {
                redirect($this->viewname . '/KnowledgeBase');
            }

            $table = KNOWLEDGEBASE_FILE_MASTER . ' as art';
            $where = array("art.article_id" => $id);
            $fields = array("art.*");
            $data['image_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            $pm_params = array();
            $pm_params['table'] = PRODUCT_MASTER . ' as pm';
            $pm_params['fields'] = array("pm.product_id,pm.product_name");
            $pm_params['where_in'] = array("pm.status" => "1");
            $data['product_info'] = $this->common_model->get_records_array($pm_params);

            $product_info = array();
            foreach ($data['update_record'] as $row) {
                $product_info[] = $row['product_id'];
            }
            $product = $product_info;
            $data['product_data'] = explode(",", $product[0]);

            $this->parser->parse('layouts/SupportTemplate', $data);
        } else {
            redirect($this->viewname . '/KnowledgeBase/ListofArticle');
        }
    }

    //downlaod image
    function download($id) {
        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = KNOWLEDGEBASE_FILE_MASTER . ' as CM';
            $params['match_and'] = 'CM.file_id=' . $id . '';
            $cost_files = $this->common_model->get_records_array($params);
            if (count($cost_files) > 0) {
                $pth = file_get_contents(base_url($cost_files[0]['file_path'] . '/' . $cost_files[0]['file_name']));
                $this->load->helper('download');
                force_download($cost_files[0]['file_name'], $pth);
            }
            redirect($this->module);
        }
    }

    //delete imagew
    public function deleteImage($id) {
        //Delete Record From Database
        if (!empty($id)) {
            $match = array("file_id" => $id);
            $fields = array("file_name");
            $image_name = $this->common_model->get_records(KNOWLEDGEBASE_FILE_MASTER, $fields, '', '', $match);

            if (file_exists($this->config->item('knowledge_img_base_url') . $image_name[0]['file_name'])) {

                unlink($this->config->item('knowledge_img_base_url') . $image_name[0]['file_name']);
            }
            $where = array('file_id' => $id);
            if ($this->common_model->delete(KNOWLEDGEBASE_FILE_MASTER, $where)) {
                echo json_encode(array('status' => 1, 'error' => ''));
                die;
            } else {
                echo json_encode(array('status' => 0, 'error' => 'Someting went wrong!'));
                die;
            }

            unset($id);
        }
    }

    //list of general main category
    public function ListofGeneralCategory($id) {
        $data['userid'] = $this->session->userdata('LOGGED_IN')['ID'];
        $userid = $data['userid'];
        if ($userid != '') {
            if ($this->input->post('id') != '') {
                $id = $this->input->post('id');
            } else {
                $id = $this->uri->segment(4);
            }
            $data['id'] = $id;
            $data['drag'] = true;
            $searchtext = '';
            $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('list_of_sub_category_data');
            }
            $searchsort_session = $this->session->userdata('list_of_sub_category_data');
            //Sorting
            if (!empty($sortfield) && !empty($sortby)) {
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            } else {
                if (!empty($searchsort_session['sortfield'])) {
                    $data['sortfield'] = $searchsort_session['sortfield'];
                    $data['sortby'] = $searchsort_session['sortby'];
                    $sortfield = $searchsort_session['sortfield'];
                    $sortby = $searchsort_session['sortby'];
                } else {
                    $sortfield = 'sub_category_id';
                    $sortby = 'desc';
                    $data['sortfield'] = $sortfield;
                    $data['sortby'] = $sortby;
                }
            }
            //Search text
            if (!empty($searchtext)) {
                $data['searchtext'] = $searchtext;
            } else {
                if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                    $data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext = $data['searchtext'];
                } else {
                    $data['searchtext'] = '';
                }
            }
            if (!empty($perpage) && $perpage != 'null') {
                $data['perpage'] = $perpage;
                $config['per_page'] = $perpage;
            } else {
                if (!empty($searchsort_session['perpage'])) {
                    $data['perpage'] = trim($searchsort_session['perpage']);
                    $config['per_page'] = trim($searchsort_session['perpage']);
                } else {
                    $config['per_page'] = '10';
                    $data['perpage'] = '10';
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';

            $config['base_url'] = base_url() . $this->viewname . '/KnowledgeBase/ListofGeneralCategory/index/';

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 5;
                $uri_segment = 3;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(2) . '/' . $this->uri->segment(3);
            }

            $table = KNOWLEDGEBASE_MAIN_CATEGORY . ' as kmc';
            $where = array("kmc.status" => "1", "kmc.is_delete" => "0");
            $fields = array("kmc.*");
            $data['main_category_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            //Query

            $table = KNOWLEDGEBASE_SUB_CATEGORY . ' as ksc';

            $fields = array("ksc.sub_category_id,ksc.sub_category_name,ksc.main_category_id,ksc.client_visible,ksc.article_owner,ksc.product_related,ksc.product_id,ksc.created_date,ksc.status,u.firstname,u.lastname,pm.product_id,pm.product_name,kmc.category_name");
            $join_tables = array('blzdsk_login as u' => 'u.login_id= ksc.article_owner', 'blzdsk_product_master as pm' => 'pm.product_id= ksc.product_id', 'blzdsk_knowledgebase_main_category as kmc' => 'kmc.main_category_id= ksc.main_category_id');

            if ($id > "0") {
                $where = "ksc.is_delete=0 and ksc.main_category_id = $id";
            } else {
                $where = "ksc.is_delete=0";
            }

            if (!empty($searchtext)) {
                $date_from = date('Y-m-d', strtotime($searchtext));
                $searchtext = html_entity_decode(trim($searchtext));
                $where_search = '(ksc.sub_category_name LIKE "%' . $searchtext . '%" OR concat(u.firstname," ",u.lastname) LIKE "%' . $searchtext . '%" OR kmc.category_name LIKE "%' . $searchtext . '%"  OR pm.product_name LIKE "%' . $searchtext . '%" OR ksc.created_date LIKE "%' . $date_from . '%") ';
                $data['sub_category_info'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $this->uri->segment(5), $sortfield, $sortby, '', $where, '', '', '', '', '', $where_search);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1', '', '', $where_search);
                $searchtext = html_entity_decode(trim($searchtext));
            } else {
                $data['sub_category_info'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $this->uri->segment(5), $sortfield, $sortby, '', $where);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
            }

            $this->ajax_pagination->initialize($config);
            $data['pagination'] = $this->ajax_pagination->create_links();
            $data['uri_segment'] = $uri_segment;
            $sortsearchpage_data = array(
                'sortfield' => $data['sortfield'],
                'sortby' => $data['sortby'],
                'searchtext' => $data['searchtext'],
                'perpage' => trim($data['perpage']),
                'uri_segment' => $uri_segment,
                'total_rows' => $config['total_rows']);
            $this->session->set_userdata('list_of_sub_category_data', $sortsearchpage_data);

            $data['sales_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'support');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/KnowledgeBase/ajax_list_general_category', $data);
            } else {
                $data['main_content'] = '/KnowledgeBase/' . $this->listofmaincategory_data;
                $this->parser->parse('layouts/SupportTemplate', $data);
            }
        } else {
            redirect($this->viewname . '/KnowledgeBase');
        }
    }

    public function deletesub($id, $mid) {

        $redirect_link = $this->input->get('link');
        $data['sub_category'] = $this->viewname;
        if (!empty($id)) {
            $where = array('sub_category_id' => $id);
            $sub_category_data['is_delete'] = 1;

            $table = KNOWLEDGEBASE_KNOWLEDGE_ARTICLE . ' as ka';
            $match = "ka.sub_category_id='" . $id . "' and is_delete=0 ";
            $fields = array("ka.sub_category_id");
            $article_category_record = $this->common_model->get_records($table, $fields, '', '', $match);

            if (count($article_category_record) == 0) {
                $delete_suceess = $this->common_model->update(blzdsk_knowledgebase_sub_category, $sub_category_data, $where);
            } else {
                $msg = $this->lang->line('sub_article_nt_delete');
                $this->session->set_flashdata('error', $msg);
            }

            if ($delete_suceess) {
                $msg = $this->lang->line('sub_category_del_msg');
                $this->session->set_flashdata('msg', $msg);
            }

            unset($id);
        }
        redirect($this->viewname . '/KnowledgeBase/ListofGeneralCategory/' . $mid); //Redirect On Listing page
    }

    //insert like & dislike
    public function addlike() {

        switch ($_POST["action"]) {
            case "like":
                $liked = 1;
                $article_id = $this->input->post('id');
                $userid = $this->session->userdata('LOGGED_IN')['ID'];
                $knowledgebaselike['user_id'] = $userid;
                $knowledgebaselike['article_id'] = $article_id;
                $update['like'] = $liked;

                $table2 = KNOWLEDGEBASE_LIKE . ' as kl';
                $where2 = array("kl.user_id" => $userid, "kl.article_id" => $article_id);
                $fields2 = array('kl.*');
                $data['like'] = $this->common_model->get_records($table2, $fields2, '', '', '', '', '', '', '', '', '', $where2);
                if (!empty($data['like'])) {
                    $table1 = "blzdsk_knowledgebase_like";
                    $where1 = "user_id = $userid AND article_id = $article_id";
                    $data['update_record'] = $this->common_model->update($table1, $update, $where1);

                    $table3 = KNOWLEDGEBASE_LIKE . ' as kl';
                    $fields3 = array('COUNT(kl.like) as like_count');
                    $where3 = array("kl.like" => "1", "kl.article_id" => $article_id);
                    $data['total_like'] = $this->common_model->get_records($table3, $fields3, '', '', '', '', '', '', '', '', '', $where3);
                    $d = $data['total_like'];
                    echo json_encode(array('total_like' => $d[0]['like_count']));
                } else {

                    $data['result'] = $this->common_model->insert(KNOWLEDGEBASE_LIKE, $knowledgebaselike);
                    if (!empty($data['result'])) {
                        $table1 = "blzdsk_knowledgebase_like";
                        $where1 = "user_id = $userid AND article_id = $article_id";
                        $data['update_record'] = $this->common_model->update($table1, $update, $where1);
                        $table3 = KNOWLEDGEBASE_LIKE . ' as kl';
                        $fields3 = array('COUNT(kl.like) as like_count');
                        $where3 = array("kl.like" => "1", "kl.article_id" => $article_id);
                        $data['total_like'] = $this->common_model->get_records($table3, $fields3, '', '', '', '', '', '', '', '', '', $where3);
                        $d = $data['total_like'];
                        echo json_encode(array('total_like' => $d[0]['like_count']));
                    }
                }
                break;
            case "unlike":

                $liked1 = 0;
                $article_id = $this->input->post('id');
                $userid = $this->session->userdata('LOGGED_IN')['ID'];
                $updatedislike['like'] = $liked1;
                $table2 = "blzdsk_knowledgebase_like";
                $where2 = "user_id = $userid AND article_id = $article_id";
                $data['update_record1'] = $this->common_model->update($table2, $updatedislike, $where2);
                $table3 = KNOWLEDGEBASE_LIKE . ' as kl';
                $fields3 = array('COUNT(kl.like) as like_count');
                $where3 = array("kl.like" => "1", "kl.article_id" => $article_id);
                $data['total_like'] = $this->common_model->get_records($table3, $fields3, '', '', '', '', '', '', '', '', '', $where3);
                $d = $data['total_like'];
                echo json_encode(array('total_like' => $d[0]['like_count']));
                break;
        }
    }

    public function checkVisible() {
        $main_id = $this->input->post('id');

        $table2 = KNOWLEDGEBASE_MAIN_CATEGORY . ' as kmc';
        $where2 = array("kmc.is_delete" => "0", "kmc.main_category_id" => $main_id);
        $fields2 = array("kmc.client_visible");
        $data1['main_id'] = $this->common_model->get_records($table2, $fields2, '', '', '', '', '', '', '', '', '', $where2);
        $d = $data1['main_id'];
        echo json_encode($d[0]['client_visible']);
    }

}
