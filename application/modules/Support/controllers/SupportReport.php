<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SupportReport extends CI_Controller {

    public $viewname;

    function __construct() {
        parent::__construct();

        $this->load->library(array('form_validation', 'Session'));
        $this->load->model('Support_model');
        $this->current_module = $this->router->fetch_module();
        $this->module = $this->router->fetch_module();
        $this->viewname = 'Support';
        $this->load->library('pagination');
        $this->load->helper(array('form', 'url'));
    }

    //view of indexpage
    public function index($page = '') {

        //Get uri segment for pagination
        $cur_uri = explode('/', $_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);

        $data['header'] = array('menu_module' => 'support');
        $data['support_view'] = $this->viewname;
        $data['drag'] = false;

        $search_status = $this->input->post('search_status');
        $search_type = $this->input->post('search_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');


        $data['checked_campaign_id'] = array();
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url'] = base_url() . $this->viewname . '/SupportReport/index';

        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('supportreport_data');
        }
        if ($this->session->has_userdata('supportreport_data')) {
            $data['searchsort_session'] = $this->session->userdata('supportreport_data');
        }
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
                $sortfield = 'ticket_id';
                $sortby = 'asc';
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
        $config['base_url'] = base_url() . $this->viewname . '/SupportReport/index';

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {

            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(2);
        } else {

            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(2);
        }
        $data['sales_view'] = $this->viewname;
        $table1 = SUPPORT_STATUS . ' as s';
        $where1 = array("s.is_delete" => "0");
        $fields1 = array('status_id', 'status_name');
        $data['status'] = $this->common_model->get_records($table1, $fields1, '', '', '', '', '', '', '', '', '', $where1);
        $table2 = SUPPORT_TYPE . ' as st';
        $where2 = array("st.is_delete" => "0");
        $fields2 = array('support_type_id', 'type');
        $data['type'] = $this->common_model->get_records($table2, $fields2, '', '', '', '', '', '', '', '', '', $where2);
        $table = TICKET_MASTER . " AS tm";
        $fields = array("tm.*,pm.prospect_name,pm.prospect_id,s.status_id,s.status_name,st.support_type_id,st.type");
        $params['join_tables'] = array(PROSPECT_MASTER . ' AS pm' => 'pm.prospect_id = tm.client_id', SUPPORT_STATUS . ' AS s' => 's.status_id = tm.status', SUPPORT_TYPE . ' AS st' => 'st.support_type_id = tm.type');
        $params['join_type'] = 'left';
        $group_by = 'tm.ticket_id';
        $where = "tm.is_delete=0";
//If search data are there take post value and update query
        $leftSearch = array();
        $data['start_date'] = "";
        $data['end_date'] = "";
        if (($this->input->post('start_date') != "") || ($this->input->post('end_date') != "")) {
            $data['start_date'] = date_format(date_create($this->input->post('start_date')), 'Y-m-d');
            $data['end_date'] = date_format(date_create($this->input->post('end_date')), 'Y-m-d');
            $where.=' and tm.created_date >="' . $data['start_date'] . '" and tm.created_date <="' . $data['end_date'] . '"';
            $leftSearch['start_date'] = date_format(date_create($this->input->post('start_date')), 'Y-m-d');
            $leftSearch['end_date'] = date_format(date_create($this->input->post('end_date')), 'Y-m-d');
        }
        $data['search_status'] = "";
        if ($this->input->post('search_status') != "") {
            $data['search_status'] = $this->input->post('search_status');
            $where.=' and tm.status=' . $data['search_status'];
            $leftSearch['search_status'] = $this->input->post('search_status');
        }
        $data['search_type'] = "";
        if ($this->input->post('search_type') != "") {
            $data['search_type'] = $this->input->post('search_type');
            $where.=' and tm.type=' . $data['search_type'];
            $leftSearch['search_type'] = $this->input->post('search_type');
        }

        if ($format_start_date = '' && $format_end_date = '' && $search_status = '' && $search_type = '') {
            $where_all = array("tm.is_delete" => 0);

            $data['support_report'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $this->uri->segment(4), $sortfield, $sortby, $group_by, $where, '', '', '', '', '', $where_all);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $where_all);
        } else {

            $data['support_report'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $this->uri->segment(4), $sortfield, $sortby, $group_by, $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');
        
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
        $this->session->set_userdata('supportreport_data', $sortsearchpage_data);
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view('SupportReport/SupportList', $data);
        } else {
            $data['main_content'] = $this->viewname . '/SupportReport/SupportReport';
            $this->parser->parse('layouts/SupportTemplate', $data);
        }
    }

    //generate pdf
    function generatePDF() {

        $dirPath = $this->config->item('directory_root') . "application/modules/Support";
        $outputPath = $this->config->item('directory_root') . "uploads/mediagenerated";
        $phantomPath = $this->config->item('directory_root') . "uploads/phantomjs";

        $data['supports_list'] = $this->Support_model->ExportPDF();

        $ticket_list = array();
        $ticket_list = $this->Support_model->get_data();

        foreach ($ticket_list as $key => $ticket) {
            for ($month = 1; $month <= 12; $month++) {
                if (!isset($ticketarr1[$ticket['status_name']][$month])) {
                    $ticketarr1[$ticket['status_name']][$month] = 0;
                }
            }
        }

        foreach ($ticket_list as $key => $ticket) {
            $ticketarr1[$ticket['status_name']][$ticket['month']] = $ticketarr1[$ticket['status_name']][$ticket['month']] + 1;
        }
        $data['tickets'] = $ticketarr1;
        $tickets = $data['tickets'];
        $monthname = ['Jan', 'Feb', 'Mar', 'April', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'dec'];

        $high = array();
        $high['chart']['type'] = "column";
        $high['title']['text'] = $this->lang->line('support_report');
        $high['xAxis']['categories'] = $monthname;
        $high['yAxis']['title']['text'] = $this->lang->line('monthly_status');
        $high['yAxis']['title']['stackLabels']['enabled'] = true;
        $high['yAxis']['title']['stackLabels']['style']['fontWeight'] = 'bold';
        $high['yAxis']['title']['stackLabels']['style']['color'] = "(Highcharts.theme && Highcharts.theme.textColor) || 'gray'";
        $high['credits']['enabled'] = false;
        $high['legend']['shadow'] = false;
        $high['tooltip']['headerFormat'] = '<b>{point.x}</b><br/>';
        $high['tooltip']['pointFormat'] = '{series.name}: {point.y}<br/>Total: {point.stackTotal}';
        $high['plotOptions']['column']['stacking'] = 'normal';
        $high['plotOptions']['column']['dataLabels']['enabled'] = true;
        $high['plotOptions']['column']['dataLabels']['color'] = "(Highcharts.theme && Highcharts.theme.textColor) || 'white'";
        $high['plotOptions']['column']['dataLabels']['style']['textShadow'] = '0 0px 0px black';

        foreach ($tickets as $status => $ticket) {

            $high['series'][] = array('name' => $status, 'data' => array_values($ticket));
        }

        $chartName = 'suport_report';

        $data['chartName'] = $chartName;

        $myfile = fopen($outputPath . "/inrep_$chartName.json", "w") or die("Unable to open file!");
        $txt = json_encode($high);
        fwrite($myfile, $txt);
        fclose($myfile);

        //$command = "C:/phantomjs/bin/phantomjs.exe $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$chartName.json -outfile " . $outputPath . "/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
        $command = "/usr/local/bin/phantomjs $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$chartName.json -outfile " . $outputPath . "/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";

        exec($command);

        //load the view and saved it into $html variable
        $html = $this->load->view('SupportReport/SupportReport_pdf', $data, true);

        //this the the PDF filename that user will get to download
        $pdfFilePath = "Support_Report.pdf";

        //load mPDF library
        $this->load->library('m_pdf');

        //generate the PDF from the given html
        $this->m_pdf->pdf->WriteHTML($html);

        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "D");
    }

    //generate csv report
    function generateCSV() {
        $data['supports_list'] = $this->Support_model->ExportCSV();
    }

}
?> 


