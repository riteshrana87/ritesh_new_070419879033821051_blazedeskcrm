<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->viewname = $this->uri->segment(1);

        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 13/01/2016
     */

    public function index() {
        //$this->config->item('directory_root');

        $searchtext = '';
        $perpage = '';
        $where = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $data['groupFieldName'] = $groupFieldName = $this->input->post('groupFieldName');
        $data['groupFieldData'] = $groupFieldData = $this->input->post('groupFieldData');
        $this->breadcrumbs->push(lang('crm'), '/');
        $this->breadcrumbs->push(lang('settings'), 'Supplier');
        $this->breadcrumbs->push(lang('supplier'), 'Dashboard');
        $data['header'] = array('menu_module' => 'crm');

        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('supportpaging_data');
        }
        $searchsort_session = $this->session->userdata('supportpaging_data');
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
                $sortfield = 'supplier_id';
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
        $config['base_url'] = base_url('Supplier/index');

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }
        $group_by = 'CM.supplier_id';
        //Query
        $table = SUPPLIER_MASTER . ' as CM';
        $where['CM.is_delete'] = 0;
        $where['CM.status'] = 0;
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim($searchtext));
            $match = ''; // array('CM.cost_name' => $searchtext, 'CM.cost_type' => $searchtext, 'CM.status' => $searchtext, 'CM.ammount' => $searchtext, 'SM.supplier_name' => $searchtext);
            $where_search = '(CM.supplier_name LIKE "%' . $searchtext . '%" OR CM.address LIKE "%' . $searchtext . '%")';
            if (!empty($groupFieldName) && !empty($groupFieldData)) {
                for ($i = 0; $i < count($groupFieldName); $i++) {
                    if (strlen($groupFieldData[$i]) > 0):
                        $where[$groupFieldName[$i]] = $groupFieldData[$i];
                    endif;
                }
            }
            $data['supplier_data'] = $this->common_model->get_records($table, '', '', '', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', $where_search);
            $config['total_rows'] = $this->common_model->get_records($table, '', '', '', '', $match, '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $where_search);
        } else {
            $match = '';

            if (!empty($groupFieldName) && !empty($groupFieldData)) {
                for ($i = 0; $i < count($groupFieldName); $i++) {
                    $where[$groupFieldName[$i]] = $groupFieldData[$i];
                }
            }
            $data['supplier_data'] = $this->common_model->get_records($table, '', '', '', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where);
            $config['total_rows'] = $this->common_model->get_records($table, '', '', '', '', $match, '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');
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
            'groupFieldName' => $this->input->post('groupFieldName'),
            'groupFieldData' => $this->input->post('groupFieldData'),
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('supportpaging_data', $sortsearchpage_data);
        //$data['sales_view'] = $this->viewname;
        //$data['facebook_count'] = $this->facebook_count();
        //$data['twiiter_count'] = $this->get_twitter_follower_count();
        //pr($data['pagination']);exit;
        //$data['popup'] = $this->load->view($this->viewname.'/Add',$data);
        $data['sales_view'] = $this->viewname;
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('project_ajax')) {
                $this->load->view('Supplier', $data);
            } else {
                $this->load->view('AjaxSupplier', $data);
            }
        } else {

            //$data['main_content'] = '/SalesCampaign';
            $data['main_content'] = '/Supplier';
            //   $data['js_content'] = '/LoadJsFileSupplier';
            $data['drag'] = true;
            //$this->parser->parse('layouts/CampaignTemplate', $data);
            $this->parser->parse('layouts/DashboardTemplate', $data);
            // $this->parser->parse('layouts/ProspectTemplate', $data);
        }
    }

    /*
      @Author : Seema Tankariya
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 13/01/2016
     */

    public function add() {
        $this->load->view('AddEditSupplier');
    }

    /*
      @Author : Seema Tankariya
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 13/01/2016
     */

    public function savedata() {
        $id = '';
        if ($this->input->post('supplier_id')) {
            $id = $this->input->post('supplier_id');
        }

        $data = array();

        $supplier_data['supplier_name'] = $this->input->post('supplier_name');
        $supplier_data['address'] = $this->input->post('address');


        //Insert Record in Database

        if ($id) {
            $where = array('supplier_id' => $id);
            $supplier_data['modified_date'] = datetimeformat();
            $success_update = $this->common_model->update(SUPPLIER_MASTER, $supplier_data, $where);
            if ($success_update) {
                $msg = $this->lang->line('supplier_update_success');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
        } else {

            $supplier_data['created_date'] = datetimeformat();
            $success_insert = $this->common_model->insert(SUPPLIER_MASTER, $supplier_data);

            if ($success_insert) {
                $msg = $this->lang->line('supplier_add_success');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
        }
        redirect($this->viewname); //Redirect On Listing page
    }

    /*
      @Author : Seema Tankariya
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 18/01/2016
     */

    public function edit($id) {

        $table = SUPPLIER_MASTER . ' as ds';
        if ($id > 0) {
            $data['edit_record'] = $this->common_model->get_records($table, '', '', '', '', '', '', '', '', '', '', 'is_delete=0 and supplier_id=' . $id);
            $this->load->view('AddEditSupplier', $data);
        }
    }

    /*
      @Author : Seema Tankariya
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 18/01/2016
     */

    public function view($id) {

        $table = SUPPLIER_MASTER . ' as ds';
        if ($id > 0) {
            $data['edit_record'] = $this->common_model->get_records($table, '', '', '', '', '', '', '', '', '', '', 'is_delete=0 and supplier_id=' . $id);
            $this->load->view('viewSupplier', $data);
        }
    }

    /*
      @Author : Seema Tankariya
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 13/01/2016
     */

    public function deletedata($id) {

        // $id = $this->input->get('id');
        //Delete Record From Database
        if (!empty($id)) {
            $where = array('supplier_id' => $id);

            //$delete_suceess = $this->common_model->delete(SUPPLIER_MASTER, $where);
            $supplier_data['status'] = 1;
            $supplier_data['is_delete'] = 1;
            $supplier_data['modified_date'] = datetimeformat();
            $delete_suceess = $this->common_model->update(SUPPLIER_MASTER, $supplier_data, $where);

            if ($delete_suceess) {
                $msg = $this->lang->line('supplier_delete_success');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }

            unset($id);
        }
        redirect($this->viewname); //Redirect On Listing page
    }

}
