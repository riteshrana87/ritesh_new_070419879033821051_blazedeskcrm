<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  @Author : RJ(Rupesh Jorkar)
  @Desc   : Common Model For Insert, Update, Delete and Get all records
  @Input 	:
  @Output	:
  @Date   : 12/01/2016
 */

class Common_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->prefix = $this->db->dbprefix;
        $this->admin_db = $this->load->database('ADMINDB', TRUE);
        $this->blaze_db = $this->load->database('default', TRUE);
    }

    public function insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function insert_batch($table, $data) {
        return $this->db->insert_batch($table, $data);
//return $this->db->insert_id();
    }

    public function update($table, $data, $where) {
        return $this->db->where($where)->update($table, $data);

        /* $this->db->where($where);
          $this->db->update($table, $data);
         */
    }

    public function delete($table, $where) {
        return $this->db->where($where)->delete($table);
        /* $this->db->where($where);
          $this->db->delete($table);
         */
    }

    /* Delete multiple id  by niral */

    public function delete_where_in($table, $where, $field, $wherefield) {
        return $this->db->where($where)->where_in($field, $wherefield)->delete($table);
    }

//GET all type of data
    function get_records($table = '', $fields = '', $join_tables = '', $join_type = '', $match_and = '', $match_like = '', $num = '', $offset = '', $orderby = '', $sort = '', $group_by = '', $wherestring = '', $having = '', $where_in = '', $totalrow = '', $or_where = '', $where_not_in = '', $where_between = '') {
        if (!empty($fields)) {
            foreach ($fields as $coll => $value) {
                $this->db->select($value, false);
            }
        }

        $this->db->from($table);

        if (!empty($join_tables)) {
            foreach ($join_tables as $coll => $value) {
                $this->db->join($coll, $value, $join_type);
            }
        }
        if ($match_like != null)
            $this->db->or_like($match_like);
        if ($match_and != null)
            $this->db->where($match_and);

        if ($wherestring != '')
            $this->db->where($wherestring, NULL, FALSE);

        if ($where_between != '' && !empty($where_between)) {
            $this->db->where($where_between, NULL, FALSE);
        }
        if (!empty($where_in) && is_array($where_in)) {
            foreach ($where_in as $key => $value) {
                $this->db->where_in($key, $value);
            }
        }

        if (!empty($where_not_in)) {
            foreach ($where_not_in as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }

        if (!empty($or_where)) {
            foreach ($or_where as $key => $value) {
                $this->db->or_where($key, $value);
            }
        }

        if ($group_by != null)
            $this->db->group_by($group_by);
        if ($having != null)
            $this->db->having($having);
        if ($orderby != null && $sort != null)
            $this->db->order_by($orderby, $sort);



        if ($offset != null && $num != null)
            $this->db->limit($num, $offset);
        elseif ($num != null)
            $this->db->limit($num);
        $query_FC = $this->db->get();
//echo $this->db->last_query();exit;
        if (!empty($totalrow))
            return $query_FC->num_rows();
        else
            return $query_FC->result_array();
    }

    public function insert_data($table, $data) {
        $this->admin_db->insert($table, $data);
        return $this->admin_db->insert_id();
    }

    public function insert_batch_data($table, $data) {
        return $this->admin_db->insert_batch($table, $data);
//return $this->db->insert_id();
    }

    public function update_data($table, $data, $where) {
        return $this->admin_db->where($where)->update($table, $data);

        /* $this->db->where($where);
          $this->db->update($table, $data);
         */
    }

    public function delete_data($table, $where) {
        return $this->admin_db->where($where)->delete($table);
        /* $this->db->where($where);
          $this->db->delete($table);
         */
    }

    /* Delete multiple id  by niral */

    public function delete_where_in_data($table, $where, $field, $wherefield) {
        return $this->admin_db->where($where)->where_in($field, $wherefield)->delete($table);
    }

//GET all type of data
    function get_records_data($table = '', $fields = '', $join_tables = '', $join_type = '', $match_and = '', $match_like = '', $num = '', $offset = '', $orderby = '', $sort = '', $group_by = '', $wherestring = '', $having = '', $where_in = '', $totalrow = '', $or_where = '', $where_not_in = '', $where_between = '') {
        if (!empty($fields)) {
            foreach ($fields as $coll => $value) {
                $this->admin_db->select($value, false);
            }
        }

        $this->admin_db->from($table);

        if (!empty($join_tables)) {
            foreach ($join_tables as $coll => $value) {
                $this->admin_db->join($coll, $value, $join_type);
            }
        }
        if ($match_like != null)
            $this->admin_db->or_like($match_like);
        if ($match_and != null)
            $this->admin_db->where($match_and);

        if ($wherestring != '')
            $this->admin_db->where($wherestring, NULL, FALSE);

        if ($where_between != '' && !empty($where_between)) {
            $this->admin_db->where($where_between, NULL, FALSE);
        }
        if (!empty($where_in)) {
            foreach ($where_in as $key => $value) {
                $this->admin_db->where_in($key, $value);
            }
        }

        if (!empty($where_not_in)) {
            foreach ($where_not_in as $key => $value) {
                $this->admin_db->where_not_in($key, $value);
            }
        }

        if (!empty($or_where)) {
            foreach ($or_where as $key => $value) {
                $this->admin_db->or_where($key, $value);
            }
        }

        if ($group_by != null)
            $this->admin_db->group_by($group_by);
        if ($having != null)
            $this->admin_db->having($having);
        if ($orderby != null && $sort != null)
            $this->admin_db->order_by($orderby, $sort);

        if ($offset != null && $num != null)
            $this->admin_db->limit($num, $offset);
        elseif ($num != null)
            $this->admin_db->limit($num);
        $query_FC = $this->admin_db->get();
//echo $this->db->last_query();exit;
        if (!empty($totalrow))
            return $query_FC->num_rows();
        else
            return $query_FC->result_array();
    }

    function get_records_array($params = array()) {

        if (array_key_exists("fields", $params)) {
            if (!empty($params['fields'])) {
                foreach ($params['fields'] as $coll => $value) {
                    $this->db->select($value, false);
                }
            }
        }

        if (array_key_exists("table", $params)) {
            $this->db->from($params['table']);
        }

        if (array_key_exists("join_tables", $params)) {
            if (!empty($params['join_tables'])) {
                foreach ($params['join_tables'] as $coll => $value) {
                    $this->db->join($coll, $value, $params['join_type']);
                }
            }
        }

        if (array_key_exists("match_like", $params)) {
            if ($params['match_like'] != null)
                $this->db->or_like($params['match_like']);
        }

        if (array_key_exists("match_and", $params)) {
            if ($params['match_and'] != null)
                $this->db->where($params['match_and']);
        }

        if (array_key_exists("wherestring", $params)) {
            if ($params['wherestring'] != '')
                $this->db->where($params['wherestring'], NULL, FALSE);
        }

        if (array_key_exists("where_between", $params)) {
            if ($params['where_between'] != '' && !empty($params['where_between'])) {
                $this->db->where($params['where_between'], NULL, FALSE);
            }
        }

        if (array_key_exists("where_in", $params)) {
            if (!empty($params['where_in'])) {

                foreach ($params['where_in'] as $key => $value) {

                    $this->db->where_in($key, $value);
                }
            }
        }


        if (array_key_exists("where_not_in", $params)) {
            if (!empty($params['where_not_in'])) {
                foreach ($params['where_not_in'] as $key => $value) {
                    $this->db->where_not_in($key, $value);
                }
            }
        }

        if (array_key_exists("or_where", $params)) {
            if (!empty($params['or_where'])) {
                foreach ($params['or_where'] as $key => $value) {
                    $this->db->or_where($key, $value);
                }
            }
        }

        if (array_key_exists("group_by", $params)) {
            if ($params['group_by'] != null)
                $this->db->group_by($params['group_by']);
        }

        if (array_key_exists("having", $params)) {
            if ($params['having'] != null)
                $this->db->having($params['having']);
        }

        if (array_key_exists("orderby", $params)) {
            if ($params['orderby'] != null && $params['sort'] != null)
                $this->db->order_by($params['orderby'], $params['sort']);
        }

        if (array_key_exists("offset", $params)) {
            if ($params['offset'] != null && $params['num'] != null)
                $this->db->limit($params['num'], $params['offset']);
            elseif ($params['num'] != null)
                $this->db->limit($params['num']);
        }

        $query_FC = $this->db->get();
//echo $this->db->last_query();exit;
        if (array_key_exists("totalrow", $params)) {
            if (!empty($params['totalrow'])) {
                return $query_FC->num_rows();
            }
        } else {
            return $query_FC->result_array();
        }
    }

//Function For Get Config 
    public function get_config() {
        return $this->db->get('config');
    }

    public function get_lang() {
        $this->load->helper('cookie');
        $selectedLang = get_cookie('languageSet');
        /* if ($this->session->userdata('lang')) {
          return $this->session->userdata('lang'); */
        if ($selectedLang) {
            return $selectedLang;
        } else {
            $query = $this->db->select('value')->where('config_key', 'language')->get('config');
            if ($query->num_rows() > 0) {
                $row = $query->row();
                return $row->value;
            }
        }
    }

    /* Start added by sanket om 27/01/2016 */

    public function pagination_html($config) {
        $this->load->library('pagination');


// $config["uri_segment"] = $config["uri_segment"];
        $config["uri_segment"] = 3;

        $config["uri_segment"] = 3;

        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }

    /* Start added by niral om 10/02/2016 */

    public function common_pagination($config, $page_url) {
        $config["uri_segment"] = $config["uri_segment"];
        $choice = $config["total_rows"] / $config["per_page"];
        $config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01 pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="' . $page_url . '">';
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

    public function customQuery($sql) {
        $result = $this->db->query($sql, false);
        return $result->result_array();
    }

    /* End added by sanket om 27/01/2016 */
    /*
      @Description: Function for image upload
      @Author: Niral Patel
      @Input:
      @Output: Image will upload on perticular folder
      @Date: 26-10-2015
     */

    function upload_files($uploadFile = '', $filePath = '') {
        $upload_name = $uploadFile;
        $config['upload_path'] = $filePath; /* NB! crea          te this dir! */
//$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg|csv|doc|docx|txt|pdf|xls|mov|mp4|PNG';
        $config['allowed_types'] = '*';

        $random = substr(md5(rand()), 0, 7);
        $config['file_name'] = $random . "-" . (strtolower($_FILES[$uploadFile]['name']));
        $config['overwrite'] = false;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload($upload_name))
            $data = $this->upload->data();
        else {
            echo $this->upload->display_errors();
            exit;
        }
//Upload thumb image
        $sourcePath = $data['full_path'];
        $thumbPath = $smallImgPath;
        $fileName = $data['file_name'];

        return $fileName;
    }

    function upload_image($uploadFile = '', $bigImgPath = '', $smallImgPath = '', $thumb = '', $existImage = '') {

        if (!empty($existImage)) {
            $path = $bigImgPath . $existImage;
            $path_thumb = $smallImgPath . $existImage;
            @unlink($path);
            @unlink($path_thumb);
        }
        $upload_name = $uploadFile;
        $config['upload_path'] = $bigImgPath; /* NB! crea          te this dir! */
//$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg|csv|doc|docx|txt|pdf|xls|mov|mp4|PNG';
        $config['allowed_types'] = '*';

        $random = substr(md5(rand()), 0, 7);
        $config['file_name'] = $random . "-" . (strtolower($_FILES[$uploadFile]['name']));
        $config['overwrite'] = false;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload($upload_name))
            $data = $this->upload->data();
        else {
            echo $this->upload->display_errors();
            exit;
        }
//Upload thumb image
        $sourcePath = $data['full_path'];
        $thumbPath = $smallImgPath;
        $fileName = $data['file_name'];

        list($width, $height, $type, $attr) = getimagesize($bigImgPath . $fileName);

        if (!empty($thumb) && $thumb == 'thumb') {
            if (!file_exists($smallImgPath)) {
                mkdir($bigImgPath, 0777);
            }

            $basename = explode('.', $_FILES[$uploadFile]['name']);
            $filename = $basename[0];
//for create small image
            if ($data['file_type'] == 'image/bmp' || $basename[1] == 'bmp') {
                $sourceImgBig = base_url() . $bigImgPath . $fileName;
                copy($sourceImgBig, $smallImgPath . $filename . ".jpeg");
                $imgurl = base_url() . $smallImgPath . $filename . ".jpeg";
                $width = 150;
                $this->make_thumb($imgurl, $smallImgPath . $fileName, $width);
                @unlink($smallImgPath . $filename . ".jpeg");
            } else {
                $filename = $this->upload_small_image($sourcePath, $thumbPath, $fileName);
            }


            return $fileName;
        }
    }

//Upload thumb images
    public function upload_small_image($sourceImgPath, $thumbPath) {
        $configThumb = array();
        $configThumb['image_library'] = 'gd2';
        $configThumb['thumb_marker'] = FALSE;
        $configThumb['source_image'] = '';
        $configThumb['create_thumb'] = TRUE;
        $configThumb['maintain_ratio'] = FALSE;
        $configThumb['width'] = 150;
        $configThumb['height'] = 150;
        $this->load->library('image_lib');

        $configThumb['source_image'] = $sourceImgPath;
        $configThumb['new_image'] = $thumbPath;
        $this->image_lib->initialize($configThumb);
        $this->image_lib->resize();
    }

    /*
      @Author : Mehul Patel
      @Desc   : getConfigValues
      @Input  :
      @Output :
      @Date   : 01/02/2016
     */

    public function getConfigValues() {

//$sql = "select * From blzdsk_role_master WHERE role_id NOT IN ( select role_id from blzdsk_aauth_perm_to_group GROUP BY role_id )";

        $this->db->select('*')->from(EMAIL_SETTINGS);
//$this->db->where("`config_key` IN ('company_email','email_protocol','smtp_host','smtp_user','smtp_pass','smtp_port')", NULL, FALSE);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    /*
      @Author : Sanket Jayani
      @Desc   : Getting Thumnail Image
      @Input  : upload array ,height, width
      @Output : Thumbnail image name
      @Date   : 29/02/2016
     */

    public function create_thumnail($upload, $height, $width) {
        $source_path = $upload['upload_data']['full_path'];
        $target_path = $upload['upload_data']['file_path'] . 'thumbnail';

        $config_manip = array(
            'image_library' => 'gd2',
            'source_image' => $source_path,
            'dest_image' => $target_path,
            'maintain_ratio' => TRUE,
            'create_thumb' => TRUE,
            'thumb_marker' => '_thumb',
            'width' => $height,
            'height' => $width
        );

        $this->load->library('image_lib', $config_manip);
        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }

// get file extension //
        $filename = $upload['upload_data']['file_name'];
        preg_match('/(?<extension>\.\w+)$/im', $filename, $matches);
        $extension = $matches['extension'];
// thumbnail //
        $thumbnail = preg_replace('/(\.\w+)$/im', '', $filename) . '_thumb' . $extension;
        $this->image_lib->clear();

        return $thumbnail;
    }

    /*
      @Author : Sanket Jayani
      @Desc   : Get Settings Data By Key
      @Input  : config_key
      @Output : config_value
      @Date   : 29/02/2016
     */

    function getSettingsData($config_key) {
        $this->db->select('*');
        $this->db->where('config_key', $config_key);

        $query = $this->db->get('blzdsk_config');
        $settings_obj = $query->result();


        foreach ($settings_obj as $settings_key => $settings_value) {
            $settings_data[$settings_value->config_key] = $settings_value->value;
        }


        return $settings_data[$config_key];
    }

    /*
      @Author : Seema Tankariya
      @Desc   : Get Union Data
      @Input  :
      @Output : Get Prospect and lead data
      @Date   : 29/02/2016
     */

    function getSalesoverviewData($num = '', $offset = '', $dbSearch = '', $orderby = '', $sort = '') {
        $this->db->select("pm.prospect_id,pm.prospect_name,pm.prospect_auto_id, pm.status_type,(select count(pc.prospect_id) from " . $this->prefix . CONTACT_MASTER . " as cm inner join " . $this->prefix . OPPORTUNITY_REQUIREMENT_CONTACTS . " as pc on cm.contact_id=pc.contact_id where cm.is_delete=0 and cm.status=1 and pc.prospect_id=pm.prospect_id group by pm.prospect_id) as contact_count,(select cm.contact_name from " . $this->prefix . CONTACT_MASTER . " as cm inner join " . $this->prefix . OPPORTUNITY_REQUIREMENT_CONTACTS . " as pc on cm.contact_id=pc.contact_id where pc.primary_contact=1 and cm.is_delete=0 and cm.status=1 and pc.prospect_id=pm.prospect_id group by pm.prospect_id) as contact_name,pm.creation_date,OR.requirement_id");
        $this->db->from(PROSPECT_MASTER . ' as pm');
        $this->db->join(OPPORTUNITY_REQUIREMENT_CONTACTS . ' as pc', 'pc.prospect_id=pm.prospect_id', 'LEFT');
        $this->db->join(OPPORTUNITY_REQUIREMENT . ' as OR', 'OR.prospect_id=pm.prospect_id', 'LEFT');
        $this->db->join(CONTACT_MASTER . ' as cm', 'cm.contact_id=pc.contact_id', 'LEFT');
        $this->db->where(" pm.is_delete=0 and pm.status=1 and pm.status_type=1 ", NULL, FALSE);
        if ($dbSearch != '' && !empty($dbSearch)) {
            $this->db->where($dbSearch, NULL, FALSE);
        }
        $this->db->group_by(' pm.prospect_id');

        $this->db->get();
        $query1 = $this->db->last_query();

        $this->db->select("lm.lead_id,lm.prospect_name,lm.prospect_auto_id, lm.status_type,(select count(pc.lead_id) from " . $this->prefix . CONTACT_MASTER . " as cm inner join " . $this->prefix . LEAD_CONTACTS_TRAN . " as pc on cm.contact_id=pc.contact_id where cm.is_delete=0 and cm.status=1 and pc.lead_id=lm.lead_id group by lm.lead_id) as contact_count,(select cm.contact_name from " . $this->prefix . CONTACT_MASTER . " as cm inner join " . $this->prefix . LEAD_CONTACTS_TRAN . " as pc on cm.contact_id=pc.contact_id where pc.primary_contact=1 and cm.is_delete=0 and cm.status=1 and pc.lead_id=lm.lead_id group by lm.lead_id) as contact_name,lm.creation_date,lm.description");
        $this->db->from(LEAD_MASTER . ' as lm');
        $this->db->join(LEAD_CONTACTS_TRAN . ' as pc', 'pc.lead_id=lm.lead_id', 'LEFT');
        $this->db->join(CONTACT_MASTER . ' as cm', 'cm.contact_id=pc.contact_id', 'LEFT');
        $this->db->where(" lm.is_delete=0 and lm.status=1 ", NULL, FALSE);
        if ($dbSearch != '' && !empty($dbSearch)) {
            $this->db->where($dbSearch, NULL, FALSE);
        }
        $this->db->limit($num, $offset);
        $this->db->group_by(' lm.lead_id');
        $this->db->order_by($orderby, $sort);
        $this->db->get();
        $query2 = $this->db->last_query();
        $query = $this->db->query($query1 . " UNION ALL " . $query2);

        return $query->result_array();
    }

    /*
      @Author : Maulik suthar
      @Desc   : Get Union Data for  draggable divs for the lead
      @Input  :
      @Output : Get Prospect and lead data
      @Date   : 29/02/2016
     */

    function getSalesoverviewDataDrag($num = '', $offset = '', $dbSearch = '', $orderby = '', $sort = '') {
        $login_id = $this->session->userdata['LOGGED_IN']['ID'];
        $this->db->select("pm.prospect_id,pm.prospect_name,pm.prospect_auto_id, pm.status_type,(select count(pc.prospect_id) from " . $this->prefix . CONTACT_MASTER . " as cm inner join " . $this->prefix . OPPORTUNITY_REQUIREMENT_CONTACTS . " as pc on cm.contact_id=pc.contact_id where cm.is_delete=0 and cm.status=1 and pc.prospect_id=pm.prospect_id group by pm.prospect_id) as contact_count,(select cm.contact_name from " . $this->prefix . CONTACT_MASTER . " as cm inner join " . $this->prefix . OPPORTUNITY_REQUIREMENT_CONTACTS . " as pc on cm.contact_id=pc.contact_id where pc.primary_contact=1 and cm.is_delete=0 and cm.status=1 and pc.prospect_id=pm.prospect_id group by pm.prospect_id) as contact_name,pm.creation_date,OR.requirement_id,em.value as estimate_prospect_worth");
        $this->db->from(PROSPECT_MASTER . ' as pm');
        $this->db->join(OPPORTUNITY_REQUIREMENT_CONTACTS . ' as pc', 'pc.prospect_id=pm.prospect_id', 'LEFT');
        $this->db->join(OPPORTUNITY_REQUIREMENT . ' as OR', 'OR.prospect_id=pm.prospect_id', 'LEFT');
        $this->db->join(CONTACT_MASTER . ' as cm', 'cm.contact_id=pc.contact_id', 'LEFT');
        $this->db->join(ESTIMATE_MASTER . ' as em', 'em.estimate_id= pm.estimate_prospect_worth', 'left');
//        $this->db->join('blzdsk_countries as cm', 'cm.country_id = em.country_id_symbol', 'left');
        $this->db->where("pm.is_delete=0 and pm.status=1 and pm.status_type!=2", NULL, FALSE);
        //$this->db->where("pm.created_by", $login_id);

        if ($dbSearch != '' && !empty($dbSearch)) {
            $this->db->where($dbSearch, NULL, FALSE);
        }
// $this->db->limit($num, $offset);
        $this->db->group_by(' pm.prospect_id');
// $this->db->order_by($orderby, $sort);
        $this->db->get();
        $query1 = $this->db->last_query();
        $this->db->select("lm.lead_id,lm.prospect_name,lm.prospect_auto_id, lm.status_type,(select count(pc.lead_id) from " . $this->prefix . CONTACT_MASTER . " as cm inner join " . $this->prefix . LEAD_CONTACTS_TRAN . " as pc on cm.contact_id=pc.contact_id where cm.is_delete=0 and cm.status=1 and pc.lead_id=lm.lead_id group by lm.lead_id) as contact_count,(select cm.contact_name from " . $this->prefix . CONTACT_MASTER . " as cm inner join " . $this->prefix . LEAD_CONTACTS_TRAN . " as pc on cm.contact_id=pc.contact_id where pc.primary_contact=1 and cm.is_delete=0 and cm.status=1 and pc.lead_id=lm.lead_id group by lm.lead_id) as contact_name,lm.creation_date,lm.description,em.value as estimate_prospect_wort");
        $this->db->from(LEAD_MASTER . ' as lm');
        $this->db->join(LEAD_CONTACTS_TRAN . ' as pc', 'pc.lead_id=lm.lead_id', 'LEFT');
        $this->db->join(CONTACT_MASTER . ' as cm', 'cm.contact_id=pc.contact_id', 'LEFT');
        $this->db->join(ESTIMATE_MASTER . ' as em', 'em.estimate_id= lm.estimate_prospect_worth', 'left');

        $this->db->where(" lm.is_delete=0 and lm.status=1 ", NULL, FALSE);
        //  $this->db->where("lm.created_by", $login_id);
        if ($dbSearch != '' && !empty($dbSearch)) {
            $this->db->where($dbSearch, NULL, FALSE);
        }
// $this->db->limit($num, $offset);
        $this->db->group_by(' lm.lead_id');
//$this->db->order_by($orderby, $sort);
        $this->db->get();
        $query2 = $this->db->last_query();

        $query = $this->db->query($query1 . " UNION " . $query2 . 'ORDER BY `prospect_id` DESC');
        return $query->result_array();
    }

    /*
      Added by sanket for returnig language name by ID
     * 
     *      */

    function getLanguageNameById($language_id) {
        if ($language_id == '1') {
            return "English";
        } else if ($language_id == '2') {
            return "Spanish";
        }
    }

    function salution_list() {
        $this->db->select('*');
        $this->db->from(SALUTIONS_LIST);
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result_array();
    }

    function salution_name($id) {
        $this->db->select('s_name');
        $this->db->from(SALUTIONS_LIST);
        $this->db->where('status', '1');
        $this->db->where('s_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    /*
      @Author : Maulik suthar
      @Desc   :Default data set for the projectmanagement
      @Input  :
      @Output : Get Prospect and lead data
      @Date   : 29/02/2016
     */

    function sampleSqlData() {
        /*
         * insertion of default project 
         */
        echo "<pre>";

        $project_data['project_name'] = ucfirst('Default Project');
        $project_data['project_desc'] = 'Default Project';
        $project_data['project_budget'] = 4000;
        $project_data['status'] = 1;
        $start_date = datetimeformat();
        $due_date = date('Y-m-d', strtotime("+30 days"));
        $project_data['start_date'] = $start_date;
        $project_data['due_date'] = $due_date;
        $project_data['project_code'] = random_string();
        $project_data['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
        $project_data['created_date'] = datetimeformat();
        echo "//project INsertion" . "<br>";
        echo $this->db->insert_string(PROJECT_MASTER, $project_data) . "<br>";
        $where = array('status' => 1);
        $res_user = $this->common_model->get_records('login', '', '', '', $where, '');
        echo "//project User Assignment" . "<br>";
        if (!empty($res_user)) {
            foreach ($res_user as $res_user) {
                $project_assign_data['project_id'] = 1;
                $project_assign_data['user_id'] = $res_user['login_id'];
                $project_assign_data['created_date'] = datetimeformat();
                echo $this->db->insert_string(PROJECT_ASSIGN_MASTER, $project_assign_data) . "<br>";
                ;
            }
        }
        echo "//project Milestone" . "<br>";
        $this->insertMilestone();
        $this->sampleTeamCreation();
        $this->createTask();
        $this->createTimesheets();
        $this->createProjectIncidentsType();
        $this->createProjectIncidents();
        $this->createCost();
    }

    /*
      @Author : Maulik suthar
      @Desc   :Creation of the milestone sql
      @Input  :
      @Output :
      @Date   :11-03-2016
     */

    private function insertMilestone() {
        $insert_data['project_id'] = 1;
        $insert_data['milestone_name'] = ucfirst($this->input->post('milestone_name'));
        $insert_data['res_user'] = $this->input->post('res_user');
        $insert_data['description'] = $this->input->post('description', FALSE);
        $insert_data['status'] = 1;
        $start_date = $this->input->post('start_date');
        $due_date = $this->input->post('due_date');
        $start_date = datetimeformat();
        $due_date = date('Y-m-d', strtotime("+30 days"));
        $insert_data['start_date'] = $start_date;
        $insert_data['due_date'] = $due_date;
        $insert_data['milestone_code'] = random_string();
        $insert_data['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
        $insert_data['created_date'] = datetimeformat();
        echo $this->db->insert_string(MILESTONE_MASTER, $insert_data) . "<br>";
    }

    /*
      @Author : Maulik suthar
      @Desc   :Creation of the Team Data sql
      @Input  :
      @Output :
      @Date   :11-03-2016
     */

    private function sampleTeamCreation() {
        /*
         * getting team leader id 
         */

        $where = array('L.status' => 1, 'RM.role_name' => 'Team Leader');
        $params['join_tables'] = array(ROLE_MASTER . ' as RM' => 'RM.role_id=L.user_type');
        $params['join_type'] = 'left';
        $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
        $teamLeaderData = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'], $where, '');
        $team_data['team_name'] = 'Default Team';
        $team_data['team_lead_id'] = $teamLeaderData[0]['login_id'];
        $team_data['notify_members'] = 0;
        $team_data['project_id'] = 1;
        $team_data['schedule_meeting'] = datetimeformat();
        $team_data['status'] = 1;
        $team_data['created_date'] = datetimeformat();
        echo "// Insertion of the team " . "<br>";
        echo $this->db->insert_string(PROJECT_TEAM_MASTER, $team_data) . "<br>";
        $id = 1;
        $where = array('status' => 1);
        $membersId = $this->common_model->get_records('login', '', '', '', $where, '');
        echo "// Insertion of the team members to the team" . "<br>";
        if (count($membersId) > 0) {
            foreach ($membersId as $ids) {
                echo $this->db->insert_string(PROJECT_TEAM_MEMBERS, array('team_id' => $id, 'member_id' => $ids['login_id'], 'project_id' => 1, 'created_date' => datetimeformat(), 'created_by' => $this->session->userdata('LOGGED_IN')['ID'])) . "<br/>";
            }
        }
        /*
         * get pm data
         */
        $where = array('L.status' => 1, 'RM.role_name' => 'Project Manager');
        $params['join_tables'] = array(ROLE_MASTER . ' as RM' => 'RM.role_id=L.user_type');
        $params['join_type'] = 'left';
        echo "//Assignment of Project Manager" . "<br>";
        $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
        $pmData = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'], $where, '');
        echo $this->db->insert_string(PROJECT_PM_ASSIGN, array('project_id' => 1, 'user_id' => $pmData[0]['login_id'])) . "<br/>";
    }

    /*
      @Author : Maulik suthar
      @Desc   :Creation of the Task sql
      @Input  :
      @Output :
      @Date   :11-03-2016
     */

    private function createTask() {
        $sub_task_id = 0;
        $insert_data['project_id'] = 1;
        $insert_data['task_name'] = 'Default Task1';
        $insert_data['description'] = 'Default Task1';
        $insert_data['milestone_id'] = 1;
        $start_date = datetimeformat();
        $due_date = date('Y-m-d', strtotime("+30 days"));
        $insert_data['start_date'] = $start_date;
        $insert_data['due_date'] = $due_date;
        $insert_data['task_code'] = random_string();
        $insert_data['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
        $insert_data['created_date'] = datetimeformat();
        $insert_data['status'] = 1;
        echo "//Task Creation" . "<br/>";
        echo $this->db->insert_string(PROJECT_TASK_MASTER, $insert_data);
        $where = array('status' => 1);
        $membersId = $this->common_model->get_records('login', '', '', '', $where, '');
        echo "//Task Members assignment" . "<br/>";
        if (!empty($membersId)) {
            foreach ($membersId as $members) {
                $team_assign_data['task_id'] = 1;
                $team_assign_data['user_id'] = $members['login_id'];
                $team_assign_data['created_date'] = datetimeformat();
                echo $this->db->insert_string(PROJECT_TASK_TEAM_TRAN, $team_assign_data) . "<br/>";
            }
        }
    }

    /*
      @Author : Maulik suthar
      @Desc   :Creation of the Timesheets sql
      @Input  :
      @Output :
      @Date   :11-03-2016
     */

    private function createTimesheets() {

        $insert_data['project_id'] = 1;
        $insert_data['task_id'] = 1;
        $insert_data['estimate_time'] = 20;
        $insert_data['spent_time'] = 5;
        $insert_data['description'] = 'Default Task1 Timesheet Example';
        $insert_data['user_id'] = $this->session->userdata('LOGGED_IN')['ID'];
        $insert_data['created_date'] = datetimeformat();
        echo "// Creation of Timesheet for the default task1" . "<br>";
        echo $this->db->insert_string(PROJECT_TIMESHEETS, $insert_data) . "<br/>";
    }

    /*
      @Author : Maulik suthar
      @Desc   :Creation of the ProjectIncidents sql
      @Input  :
      @Output :
      @Date   :11-03-2016
     */

    private function createProjectIncidents() {

        $insert_data['project_id'] = 1;
        $insert_data['title'] = 'Default_Incident_1';
        $insert_data['type_id'] = 1;
        $insert_data['description'] = 'Default Incident 1 Description';
        $insert_data['status'] = 1;
        $insert_data['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
        $insert_data['created_date'] = datetimeformat();
        echo "// Insertion of the ProjectIncident" . "<br>";
        echo $this->db->insert_string(PROJECT_INCIDENTS, $insert_data) . "<br>";
    }

    /*
      @Author : Maulik suthar
      @Desc   :Creation of the ProjectIncidents Type sql
      @Input  :
      @Output :
      @Date   :11-03-2016
     */

    private function createProjectIncidentsType() {
        $insert_data['incident_type_name'] = 'Default_Incident_type';
        $insert_data['status'] = 1;
        $insert_data['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
        $insert_data['created_date'] = datetimeformat();
        echo "// Insertion of the ProjectIncidentType" . "<br/>";
        echo $this->db->insert_string(PROJECT_INCIDENTS_TYPE, $insert_data) . "<br/>";
    }

    /*
      @Author : Maulik suthar
      @Desc   :Creation of the Cost sql
      @Input  :
      @Output :
      @Date   :11-03-2016
     */

    private function createCost() {
        $cost_data['cost_name'] = 'Default Cost 1';
        $cost_data['cost_code'] = random_string();
        $cost_data['task_id'] = 1;
        $cost_data['project_id'] = 1;
        $cost_data['created_date'] = datetimeformat();
        $cost_data['user_id'] = 1;
        $start_date = datetimeformat();
        $due_date = date('Y-m-d', strtotime("+30 days"));
        $cost_data['start_date'] = $start_date;
        $cost_data['cost_type'] = 'Default Type';
        $cost_data['due_date'] = $due_date;
        $cost_data['within_project'] = 0;
        $cost_data['ammount'] = 1000;
        $cost_data['product_id'] = 1;
        $cost_data['expense_supplier'] = 0;
        $cost_data['description'] = 'Default Cost 1 Description';
        $cost_data['supplier_id'] = 1;
        $cost_data['status'] = 1;
        $cost_data['created_date'] = $start_date;
        echo "// Insertion of the COST to the Default Task 1" . "<br/>";
        echo $this->db->insert_string(COST_MASTER, $cost_data) . "<br/>";
    }

    function estimates_list_data_sdsd() {
        $this->db->select('*,ish1.intervention_strategies_home as need_home');
        $this->db->from('intervention_strategies_home ish1');
        $this->db->join('intervention_strategies_home ish2', 'ish2.parent_id = ish1.id', 'INNER');
        $this->db->where('ish1.district_id', $this->session->userdata('district_id'));
        $this->db->where('ish2.is_delete', '0');
//$this->db->group_by('students.student_id');
        $query = $this->db->get();
//print_r($this->db->last_query());exit;

        $result = $query->result();
        return $result;
    }

    /* start Added  By Sanket on 12/03/2015 */

    function getCompanyidFromContactid($cotact_id) {
        $table1 = CONTACT_MASTER . ' as cm';
        $match1 = "cm.contact_id=" . $cotact_id;
        $fields1 = array("cm.company_id,cm.address1,cm.address2,cm.postal_code,cm.city,cm.state,cm.country_id,cm.language_id");
        $company_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);

        return $company_data[0];
    }

    //for employee
    function getSystemUserData() {
        $table1 = LOGIN . ' as l';
        $match1 = "l.is_delete=0";
        $fields1 = array("l.login_id,l.firstname,l.lastname");
        $order_by = "l.firstname";
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1, '', '', '', $order_by, 'ASC');

        return $user_data;
    }

    //for getting all email template data
    function getEmailTemplateData() {
        $table1 = EMAIL_TEMPLATE_MASTER . ' as et';
        $match1 = "et.status=1 AND et.is_delete=0";
        $fields1 = array("et.template_id,et.subject");
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        return $user_data;
    }

    //for getting all company data
    function getAllCompanyData() {
        $table1 = COMPANY_MASTER . ' as cm';
        $match1 = "cm.status=1 ";
        $fields1 = array("cm.company_id,cm.company_name");
        $company_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);

        return $company_data;
    }

    function getAllCampaignData() {
        $table1 = CAMPAIGN_MASTER . ' as cm';
        $match1 = "cm.status=1 ";
        $fields1 = array("cm.campaign_name,cm.campaign_id");
        $order_by = "cm.campaign_name";
        $company_data = $this->common_model->get_records($table1, $fields1, '', '', $match1, '', '', '', $order_by, 'ASC');

        return $company_data;
    }

    /* start Added  By Sanket on 12/03/2015 */

    function getCompanyEmailFromComapanyid($company_id) {
        $table1 = COMPANY_MASTER . ' as cm';
        $match1 = "cm.company_id=" . $company_id;
        $fields1 = array("cm.email_id,cm.company_name");
        $company_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);

        return $company_data[0];
    }

    //for generating auto generated lead id 
    function lead_auto_gen_Id() {
        return 'L' . mt_rand(100000, 999999);
    }

    //for generating auto generated client id 
    function client_auto_gen_Id() {
        return 'C' . mt_rand(100000, 999999);
    }

    //for generating auto generated opportunity id 
    function opportunity_auto_gen_Id() {
        return 'P' . mt_rand(100000, 999999);
    }

    function campaign_auto_gen_Id() {
        return 'MC' . mt_rand(100000, 999999);
    }

    function getComapnyIdByName($company_name) {

        $this->db->select('company_id');
        $this->db->from(COMPANY_MASTER);
        $where_array = array('company_name' => $company_name, 'status' => '1');
        $this->db->where($where_array);
        $dataarr = $this->db->get()->result();

        //echo $this->db->last_query();
        //pr($dataarr);
        if (is_array($dataarr) && !empty($dataarr)) {
            return $dataarr[0]->company_id;
        } else {
            return 0;
        }
    }

    function getComapnyBranchIdByName($branch_name) {

        $this->db->select('branch_id');
        $this->db->from(BRANCH_MASTER);
        $where_array = array('branch_name' => $branch_name, 'status' => '1');
        $this->db->where($where_array);
        $dataarr = $this->db->get()->result();

        //echo $this->db->last_query();
        //pr($dataarr);
        if (is_array($dataarr) && !empty($dataarr)) {
            return $dataarr[0]->branch_id;
        } else {
            return 0;
        }
    }

    function getCountryIdByName($country_name) {

        $this->db->select('country_id');
        $this->db->from(COUNTRIES);
        $this->db->where('country_name', $country_name);
        $dataarr = $this->db->get()->result();


        //pr($dataarr);
        if (is_array($dataarr) && !empty($dataarr)) {

            return $dataarr[0]->country_id;
        } else {
            return 0;
        }
    }

    function getBranchIdByName($branch_name) {
        $this->db->select('branch_id');
        $this->db->from(BRANCH_MASTER);
        $this->db->where('branch_name', $branch_name);
        $dataarr = $this->db->get()->result();

        if (is_array($dataarr) && !empty($dataarr)) {
            return $dataarr[0]->branch_id;
        } else {
            return 0;
        }
    }

    function account_auto_gen_Id() {
        return 'C' . mt_rand(100000, 999999);
    }

    function getBCampaignIdByName($campaign_name) {
        $this->db->select('campaign_id');
        $this->db->from(CAMPAIGN_MASTER);
        $this->db->where('campaign_name', $campaign_name);
        $dataarr = $this->db->get()->result();

        if (is_array($dataarr) && !empty($dataarr)) {
            return $dataarr[0]->campaign_id;
        } else {
            return 0;
        }
    }

    function getIntrestedProductIdByName($product_name) {
        $this->db->select('product_id');
        $this->db->from(PRODUCT_MASTER);
        $this->db->where('product_name', $product_name);
        $dataarr = $this->db->get()->result();

        if (is_array($dataarr) && !empty($dataarr)) {
            return $dataarr[0]->product_id;
        } else {
            return 0;
        }
    }

    /* End Added  By Sanket on 12/03/2015 */
	/*Function use during migrate data*/
	public function insert_mbrt($table, $data) {
        $this->blaze_db->insert($table, $data);
        return $this->blaze_db->insert_id();
    }
	public function update_mbrt($table, $data, $where) {
        return $this->blaze_db->where($where)->update($table, $data);
    }

	function get_records_array_mbrt($params = array()) {

        if (array_key_exists("fields", $params)) {
            if (!empty($params['fields'])) {
                foreach ($params['fields'] as $coll => $value) {
                    $this->blaze_db->select($value, false);
                }
            }
        }

        if (array_key_exists("table", $params)) {
            $this->blaze_db->from($params['table']);
        }

        if (array_key_exists("join_tables", $params)) {
            if (!empty($params['join_tables'])) {
                foreach ($params['join_tables'] as $coll => $value) {
                    $this->blaze_db->join($coll, $value, $params['join_type']);
                }
            }
        }

        if (array_key_exists("match_like", $params)) {
            if ($params['match_like'] != null)
                $this->blaze_db->or_like($params['match_like']);
        }

        if (array_key_exists("match_and", $params)) {
            if ($params['match_and'] != null)
                $this->blaze_db->where($params['match_and']);
        }

        if (array_key_exists("wherestring", $params)) {
            if ($params['wherestring'] != '')
                $this->blaze_db->where($params['wherestring'], NULL, FALSE);
        }

        if (array_key_exists("where_between", $params)) {
            if ($params['where_between'] != '' && !empty($params['where_between'])) {
                $this->blaze_db->where($params['where_between'], NULL, FALSE);
            }
        }

        if (array_key_exists("where_in", $params)) {
            if (!empty($params['where_in'])) {

                foreach ($params['where_in'] as $key => $value) {

                    $this->blaze_db->where_in($key, $value);
                }
            }
        }


        if (array_key_exists("where_not_in", $params)) {
            if (!empty($params['where_not_in'])) {
                foreach ($params['where_not_in'] as $key => $value) {
                    $this->blaze_db->where_not_in($key, $value);
                }
            }
        }

        if (array_key_exists("or_where", $params)) {
            if (!empty($params['or_where'])) {
                foreach ($params['or_where'] as $key => $value) {
                    $this->blaze_db->or_where($key, $value);
                }
            }
        }

        if (array_key_exists("group_by", $params)) {
            if ($params['group_by'] != null)
                $this->blaze_db->group_by($params['group_by']);
        }

        if (array_key_exists("having", $params)) {
            if ($params['having'] != null)
                $this->blaze_db->having($params['having']);
        }

        if (array_key_exists("orderby", $params)) {
            if ($params['orderby'] != null && $params['sort'] != null)
                $this->blaze_db->order_by($params['orderby'], $params['sort']);
        }

        if (array_key_exists("offset", $params)) {
            if ($params['offset'] != null && $params['num'] != null)
                $this->blaze_db->limit($params['num'], $params['offset']);
            elseif ($params['num'] != null)
                $this->blaze_db->limit($params['num']);
        }

        $query_FC = $this->blaze_db->get();
//echo $this->blaze_db->last_query();exit;
        if (array_key_exists("totalrow", $params)) {
            if (!empty($params['totalrow'])) {
                return $query_FC->num_rows();
            }
        } else {
            return $query_FC->result_array();
        }
    }
}
