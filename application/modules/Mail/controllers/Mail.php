<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mail extends CI_Controller {

    public $viewname;
    public $userId;

    function __construct() {
        parent::__construct();
        //   error_reporting(0);
        $this->viewname = $this->router->fetch_class();
        $this->load->library(array('form_validation', 'Encryption'));
        $this->load->model('Mail_model');
        $this->load->library('imap');
        $this->load->model('Common_model');
        $this->userId = $this->session->userdata('LOGGED_IN')['ID'];
        /*
         * it redirects to config page if user havent updated email config 
         */
    }

    /*
      @Author : Brijesh Tiwari
      @Desc   : This module is for email management
      @Input  :
      @Output :
      @Date   : 1/03/2016
     */

    public function index() {
        $this->load->library('ajax_pagination_mail');
        $configArr = $this->getEmailConfigData($this->userId);
        if (empty($configArr)) {
            redirect('Mail/mailconfig');
            die;
        }

        $boxtype = ($this->input->post('boxtype')) ? $this->input->post('boxtype') : 'INBOX';
//$folderName = ($this->input->post('folderName')) ? $this->input->post('folderName') : '';

        $searchtext = ($this->input->post('searchtext')) ? $this->input->post('searchtext') : '';
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('mail_data');
        }

        $searchsort_session = $this->session->userdata('mail_data');

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
                $sortfield = 'msg_no';
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
        $config['first_link'] = '<i class="fa fa-backward"></i>';
        $config['last_link'] = '<i class=" fa fa-forward"></i>';
        $config['next_link'] = '<i class="fa fa-caret-right "></i>';
        $config['prev_link'] = '<i class="fa fa-caret-left"></i>';
        $config['num_links'] = 0;
        $config['base_url'] = base_url() . 'Mail/index/';

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uriSegment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uriSegment = $this->uri->segment(3);
        }
        $group_by = 'ec.uid';
        $where = "ec.boxtype='" . $boxtype . "' and ec.user_id =" . $this->session->userdata('LOGGED_IN')['ID'];
        if ($boxtype != 'Trash'):
            $where .= ' && ec.is_deleted =0';
        endif;


        $join = array(EMAIL_CLIENT_ATTACHMENTS . ' as ema' => 'ema.email_id=ec.email_unq_id');
//$type = 'left';

        if (!empty($searchtext)) {

//If have any search text
            $searchtext = html_entity_decode(trim($searchtext));
//$clientSince = date('Y-m-d', strtotime($searchtext));
            $whereSearch = '(to_mail LIKE "%' . $searchtext . '%" OR send_date LIKE "%' . $searchtext . '%" OR mail_subject LIKE "%' . $searchtext . '%" OR mail_body LIKE "%' . $searchtext . '%")';
            $data['mail_data'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', '', $join, 'left', '', '', $config['per_page'], $uriSegment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', $whereSearch);

            $config['total_rows'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', '', $join, 'left', '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $whereSearch);
        } else {
//Not have any search input
            $data['mail_data'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', '', $join, 'left', '', '', $config['per_page'], $uriSegment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', '');
            $config['total_rows'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', '', $join, 'left', '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', '');
        }
//        echo $boxtype;
        $liveCount = $this->getTotalMailCount($boxtype);
        $dbCount = $config['total_rows'];

        /*
         * first check argument is within db 
         * 
         */



        if ($liveCount > $dbCount && $searchtext == '') {
            $config['total_rows'] = $liveCount;
            if ($uriSegment >= $dbCount) {

                $offsetN = round(($uriSegment) / $config['per_page'] + 1);
                $this->pagingMails($offsetN, $config['per_page'], $boxtype);
                $sortby = 'asc';
                $data['mail_data'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', '', $join, 'left', '', '', $config['per_page'], $uriSegment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', '');
                //  echo $this->db->last_query();
            }
        }

        $data['liveCount'] = $liveCount;
        $data['dbCount'] = $dbCount;

        // build query

        $config['use_page_numbers'] = true;
        $this->ajax_pagination_mail->initialize($config);
        $data['pagination'] = $this->ajax_pagination_mail->create_links();
        $data['uri_segment'] = $uriSegment;

        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uriSegment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('mail_data', $sortsearchpage_data);
        $data['drag'] = true;
        $data['account_view'] = $this->viewname;
        $data['sales_view'] = $this->viewname;
        $data['header'] = array('menu_module' => 'MyProfile');
        $unreadMessages = array();
        $emailData = $this->getEmailConfigData($this->session->userdata('LOGGED_IN')['ID'])[0];

        $emailAccountDetails = array(
            'email_server' => $emailData['email_server'],
            'email_port' => $emailData['email_port'],
            'email_id' => $emailData['email_id'],
            'email_pass' => $emailData['email_pass'],
            'email_encryption' => $emailData['email_encryption']
        );

        $unreadMessagesData = $this->getAccounFolderList($emailAccountDetails, false); // get Folder lists
//  pr($unreadMessagesData);
//            if (count($unreadMessages) > 0) {
//            //    $unreadMessages = json_decode($unreadMessagesData[0]['account_folder'], true);
//            }
//            $percentageSize = $this->getLeftMenuMailSize();
        $data['percentageSize'] = $configArr[0]['mail_quota'];
        $data['leftMenuFolderCount'] = $unreadMessagesData;
        if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'full') {
            $this->load->view('mailMainThread', $data);
        } else if ($this->input->post('result_type') == 'ajax') {
            $this->load->view('MailAjaxList', $data);
        } else {

            $data['main_content'] = 'Mail';
            $this->parser->parse('layouts/MailTemplate', $data);
        }
///   }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : This function is used to compose or reply or fordward email address
      @Input 	:
      @Output	:
      @Date   : 19/05/2016
     */

    function ComposeMail($id = 0) {
        $configArr = $this->getEmailConfigData($this->userId);
        if (empty($configArr)) {
            redirect('Mail/mailconfig');
            die;
        }

        $userId = $this->session->userdata('LOGGED_IN')['ID'];

        $emailConfigData = $this->getEmailConfigData($userId);

        $unreadMessagesData = $this->getAccounFolderList($emailConfigData[0], false); // get Folder lists
//  pr($unreadMessagesData);
//            if (count($unreadMessages) > 0) {
//            //    $unreadMessages = json_decode($unreadMessagesData[0]['account_folder'], true);
//            }
        // $percentageSize = $this->getLeftMenuMailSize();
        $data['percentageSize'] = $configArr[0]['mail_quota'];
        $data['leftMenuFolderCount'] = $unreadMessagesData;
        $data['email_signature'] = (isset($emailConfigData[0]['email_signature'])) ? $emailConfigData[0]['email_signature'] : '';
        $data['fromMail'] = (isset($emailConfigData[0]['email_id'])) ? $emailConfigData[0]['email_id'] : '';

        $searchContactFields = array('contact_id', 'contact_name', 'email'); // added by Maitrak Modi
        $contacts = $this->common_model->get_records(CONTACT_MASTER, $searchContactFields, '', '', '', '', '', '', 'contact_name', 'ASC', '', '', '', '', '', '', '', ''); // changed by Maitrak Modi

        $currentUserEmailList = $this->getUniqueEmailList($this->session->userdata('LOGGED_IN')['ID'], $searchValue = ''); // get Unique Mail lists
        $data['contacts'] = array_merge($contacts, $currentUserEmailList);

//$data['contacts'] = $contacts;
        $data['mailtype'] = 'ComposeMail';
        $data['to'] = '';
        $data['header'] = array('menu_module' => 'MyProfile');

        $data['main_content'] = 'ComposeEmail';
        $this->parser->parse('layouts/MailTemplate', $data);
    }

    /*
      @Author : Maulik Suthar
      @Desc   : This function is used to fetch emails from the account
      @Input 	:
      @Output	:
      @Date   : 19/05/2016
     */

    function getEmails() {

        set_time_limit(0);
        $folderName = $this->input->post('folderName');

        if (!empty($folderName)) {

            $where = "ec.boxType='$folderName'";

            $resultEmails = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', '', '', '', '', '', '', '', '', '', '', $where, '', '', '1', '', '', '');

            if (empty($resultEmails) || $this->input->post('manualSync')) {
                $this->pagingMails(1, 10, $folderName);
                echo "done";
                exit;
//                    $this->storeMsgs($folderName);
            }
            if ($this->input->post('new')) {
                $this->storeMsgs($folderName);
            }
        }
        echo "done";
        exit;
    }

    /*
      @Author : Maulik Suthar
      @Desc   : This function is used to compose or reply or fordward email address
      @Input  :
      @Output :
      @Date   : 19/05/2016
     */

    function sendEmail() {
        $converter = new Encryption;
        $to = $this->input->post('to');
        $uid = $this->input->post('uid');
        $msg_no = $this->input->post('msg_no');
        $subject = $this->input->post('subject');
        $from = $from_name = $this->input->post('from');
        $cc = $this->input->post('cc');
        $bcc = $this->input->post('bcc');
        $message = $this->input->post('message', false);
        $fileToUpload = $this->input->post('fileToUpload');
        $userConfigData = $this->getEmailConfigData($this->userId);
// pr($userConfigData);
        if ($this->input->post('mailtype') && $this->input->post('mailtype') == 'forward') {
//  $connectData=$this->checkImapConnection($userConfigData);
//if ($userConfigData) {
//            if ($this->checkImapConnection($userConfigData)) {
            if (count($userConfigData) > 0) {
                $server = "{" . $userConfigData[0]['email_server'] . ":" . $userConfigData[0]['email_port'] . "/ssl}INBOX";
                $username = $userConfigData[0]['email_id'];
                $password = $converter->decode($userConfigData[0]['email_pass']);
                $userid = $this->session->userdata('LOGGED_IN')['ID'];
                $mailbox = $server;
                $folder = '';
                $encryption = 'tls'; // or ssl or ''
                $inbox = imap_open($server, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
// open connection
                $forwardMailData = $this->Message_Parse($uid);
                $headers = imap_fetchheader($inbox, $msg_no);
                preg_match_all('/([^: ]+): (.+?(?:\r\n\s(?:.+?))*)\r\n/m', $headers, $matches);
                $heads = $matches;
                if (count($forwardMailData) > 0) {
                    $headers.= "From: $from";
                    $files = array();
                    for ($x = 0; $x < count($forwardMailData); $x++) {
                        $files[] = $dirpth = FCPATH . "uploads/Mail/$userid/$uid/" . $uid . '-' . $forwardMailData[$x]['file_name'];
                    }
                    $folder = "/uploads/Mail/";
                    $path = FCPATH . $folder . '/';
                    if (count($fileToUpload) > 0) {
                        foreach ($fileToUpload as $file):
                            echo $files[] = $path . $file;
                        endforeach;
                    }
                    $config['protocol'] = 'smtp';
                    //$config['smtp_crypto'] = strtolower($userConfigData[0]['email_encryption']);
                    $config['smtp_host'] = $userConfigData[0]['email_smtp']; //change this
                    $config['smtp_port'] = $userConfigData[0]['email_smtp_port'];
                    $config['smtp_user'] = $userConfigData[0]['email_id']; //change this
                    $config['smtp_pass'] = $converter->decode($userConfigData[0]['email_pass']); //change this
                    $sendmail = send_mail_imap($to, $subject, $message, $files, $from, '', $cc, $bcc, $headers, $config);
                    // pr($sendmail);
                }
                if ($sendmail) {
                    $data = array('to_mail' => $this->input->post('to'), 'from_mail' => $this->input->post('from'), 'cc_email' => $this->input->post('cc'), 'bcc_email' => $this->input->post('bcc'), 'mail_subject' => $this->input->post('subject'), 'mail_body' => $this->input->post('message', false), 'is_local' => 1, 'boxtype' => 'Send', 'creation_date' => datetimeformat());
                    $this->Common_model->insert(EMAIL_CLIENT_MASTER, $data);
                    $this->imap->saveMessageInSent($headers, $message);
                    $msg = 'Email has been sent!';
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                    redirect('Mail');
                }
            }
        } else if ($this->input->post('mailtype') && $this->input->post('mailtype') == 'reply') {
            $headers = array();
            $body = $message;
            $headers[] = "From:" . $from . "\r\n";
            $headers[] = "Date: " . date("r") . "\r\n";
            $headers[] = "MIME-Version: 1.0\r\n";
            $headers[] = "In-Reply-To:" . $uid . "";
            $fileToUpload = $this->input->post('fileToUpload');
            $files = array();
            $folder = "/uploads/Mail/";
// $dataAttachment = $this->common_model->get_records(EMAIL_CLIENT_ATTACHMENTS . ' as eca', '', array(EMAIL_CLIENT_MASTER . ' as ecm' => 'ecm.email_unq_id=eca.email_id'), 'LEFT', 'email_id=' . $id . '', '', '', '', '', '', '', '', '', '', '', '', '', '');
//        $directories = glob($folder . '/' . '*');
//  if (count($dataAttachment) > 0) {
//            foreach ($dataAttachment as $data) {
            $path = FCPATH . $folder . '/';
            if (count($fileToUpload) > 0) {
                foreach ($fileToUpload as $file):
                    echo $files[] = $path . $file;
                endforeach;
            }
//            $headers .="Content-Type: multipart/" . $subtype . ";
//        \t boundary=\"" . $value . "\"" . "\r\n";
            $config['protocol'] = 'smtp';
            // $config['smtp_crypto'] = strtolower($userConfigData[0]['email_encryption']);
            $config['smtp_host'] = $userConfigData[0]['email_smtp']; //change this
            $config['smtp_port'] = $userConfigData[0]['email_smtp_port'];
            $config['smtp_user'] = $userConfigData[0]['email_id']; //change this
            $config['smtp_pass'] = $converter->decode($userConfigData[0]['email_pass']); //change this
            $sendmail = send_mail_imap($to, $subject, $body, $files, $from, '', $cc, $bcc, $headers, $config);
            if ($sendmail) {
                $data = array('to_mail' => $this->input->post('to'), 'from_mail' => $this->input->post('from'), 'cc_email' => $this->input->post('cc'), 'bcc_email' => $this->input->post('bcc'), 'mail_subject' => $this->input->post('subject'), 'mail_body' => $this->input->post('message', false), 'is_local' => 1, 'boxtype' => 'Send', 'creation_date' => datetimeformat());
                $this->Common_model->insert(EMAIL_CLIENT_MASTER, $data);
                $this->imap->saveMessageInSent($headers, $message);
                $msg = 'Email has been sent!';
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                redirect('Mail');
            } else {
                $msg = 'Error While sending message';
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Mail');
            }
        } else {
            if ($this->input->post('to') != '' && $this->input->post('subject') != '' && $this->input->post('message') != '') {
                $to = $this->input->post('to');
                $subject = $this->input->post('subject');
                $from = $from_name = $this->input->post('from');
                $cc = $this->input->post('cc');
                $bcc = $this->input->post('bcc');
                $message = $_POST['message'];
                $fileToUpload = $this->input->post('fileToUpload');
                $files = array();
                $folder = "/uploads/Mail/";
// $dataAttachment = $this->common_model->get_records(EMAIL_CLIENT_ATTACHMENTS . ' as eca', '', array(EMAIL_CLIENT_MASTER . ' as ecm' => 'ecm.email_unq_id=eca.email_id'), 'LEFT', 'email_id=' . $id . '', '', '', '', '', '', '', '', '', '', '', '', '', '');
//        $directories = glob($folder . '/' . '*');
//  if (count($dataAttachment) > 0) {
//            foreach ($dataAttachment as $data) {
                $path = FCPATH . $folder . '/';
                if (count($fileToUpload) > 0) {
                    foreach ($fileToUpload as $file):
                        echo $files[] = $path . $file;
                    endforeach;
                }
                $headers = array('MIME-Version: 1.0\r\n', 'Content-Type: image/png; charset=utf-8\r\n', 'X-Priority: 1\r\n', 'Content-Transfer-Encoding: base64');
// $from_email = '', $from_name = '', $cc = '', $bcc = '', '', ''
                $config['protocol'] = 'smtp';
                //  $config['smtp_crypto'] = strtolower($userConfigData[0]['email_encryption']);
                $config['smtp_host'] = $userConfigData[0]['email_smtp']; //change this
                $config['smtp_port'] = $userConfigData[0]['email_smtp_port'];
                $config['smtp_user'] = $userConfigData[0]['email_id']; //change this
                $config['smtp_pass'] = $converter->decode($userConfigData[0]['email_pass']); //change this
                $config['from'] = $userConfigData[0]['email_id']; //change this
                // pr($config);
                $send = send_mail_imap($to, $subject, $message, $files, $from, '', $cc, $bcc, $headers, $config);
// pr($send);
// die;
                if ($send) {
                    $data = array('to_mail' => $this->input->post('to'), 'from_mail' => $this->input->post('from'), 'cc_email' => $this->input->post('cc'), 'bcc_email' => $this->input->post('bcc'), 'mail_subject' => $this->input->post('subject'), 'mail_body' => $this->input->post('message', false), 'is_local' => 1, 'boxtype' => 'Send', 'creation_date' => datetimeformat());
                    $this->Common_model->insert(EMAIL_CLIENT_MASTER, $data);
                    $this->imap->saveMessageInSent($headers, $message);
                    $msg = 'Email has been sent!';
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                    redirect('Mail');
                } else {

                    $msg = 'Error While sending message';
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    redirect('Mail');
                }
            }
        }
    }

    function saveConcept() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        } else {
            $data = array('to_mail' => $this->input->post('to'), 'from_mail' => $this->input->post('from'), 'cc_email' => $this->input->post('cc'), 'bcc_email' => $this->input->post('bcc'), 'mail_subject' => $this->input->post('subject'), 'mail_body' => $this->input->post('message', false), 'is_local' => 1, 'boxtype' => 'Concept', 'creation_date' => datetimeformat());
            $this->Common_model->insert(EMAIL_CLIENT_MASTER, $data);
            echo json_encode(array('status'=>1));
        }
    }

    function moveMessage() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct scripts are allowed");
        }

        ini_set('max_execution_time', 0);
        set_time_limit(0);

        $userId = $this->session->userdata('LOGGED_IN')['ID'];

        $userConfigData = $this->getEmailConfigData($userId);

        if ($userConfigData) {

            if ($this->checkImapConnection($userConfigData[0])) {
                $id = $this->input->post('id');
                $path = $this->input->post('path');
                if ($path == 'starred') {
                    $folder = '[Gmail]/Starred';
                    $dataArray = array('is_starred' => 1);
                } else if ($path == 'unstarred') {
                    $folder = 'INBOX';
                    $dataArray = array('is_starred' => 0);
                }
                $this->imap->moveMessage($id, $folder);
                $this->Common_model->update(EMAIL_CLIENT_MASTER, $dataArray, array('uid' => $id));
                echo json_encode(array('status' => 1));
            }
        }
    }

    function markasFlagged() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct scripts are allowed");
        }

        ini_set('max_execution_time', 0);
        set_time_limit(0);

        $userId = $this->session->userdata('LOGGED_IN')['ID'];
        $userConfigData = $this->getEmailConfigData($userId);

        if ($userConfigData) {

            if ($this->checkImapConnection($userConfigData[0])) {

                $id = $this->input->post('id');
                $path = $this->input->post('path');

                if ($path == 'flagged'):
                    $dataArray = array('is_flagged' => 1);
                else:
                    $dataArray = array('is_flagged' => 0);
                endif;

                $this->imap->setasFlagged($id);
                $this->Common_model->update(EMAIL_CLIENT_MASTER, $dataArray, array('uid' => $id));
                echo json_encode(array('status' => 1));
            }
        }
    }

    function uploadFromEditor() {

        $allowed = array('png', 'jpg', 'gif', 'zip', 'jpeg');

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {

            $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if (!in_array(strtolower($extension), $allowed)) {
                echo '{"status":"error"}';
                exit;
            }
            if (move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/Mail/' . time() . "_" . $_FILES['file']['name'])) {
                $tmp = 'uploads/Mail/' . time() . "_" . preg_replace('/\s+/', '', $_FILES['file']['name']);
                $path = 'uploads/Mail/' . time() . "_" . preg_replace('/\s+/', '', $_FILES['file']['name']);
                echo base_url($path);
            }
        } else {
            echo '{"status":"error"}';
            exit;
        }
    }

    function forwardEmail($uid) {
        $configArr = $this->getEmailConfigData($this->userId);
        if (empty($configArr)) {
            redirect('Mail/mailconfig');
            die;
        }
//$table = EMAIL_CONFIG . ' as ec';
//$where = array("user_id" => $this->session->userdata('LOGGED_IN')['ID']);
//$emailConfigData = $this->common_model->get_records(EMAIL_CONFIG, '', '', '', '', '', '', '', '', '', '', $where, '', '');
//$data['config'] = $emailConfigData;
//$data['fromMail'] = 'cmswtest101@gmail.com';
        $userId = $this->session->userdata('LOGGED_IN')['ID'];

        $emailConfigData = $this->getEmailConfigData($userId);

        $unreadMessagesData = $this->getAccounFolderList($emailConfigData, false); // get Folder lists
//  pr($unreadMessagesData);
//            if (count($unreadMessages) > 0) {
//            //    $unreadMessages = json_decode($unreadMessagesData[0]['account_folder'], true);
//            }
        $data['percentageSize'] = $configArr[0]['mail_quota'];
//        $data['percentageSize'] = $percentageSize;
        $data['leftMenuFolderCount'] = $unreadMessagesData;

        $data['email_signature'] = (isset($emailConfigData[0]['email_signature'])) ? $emailConfigData[0]['email_signature'] : '';
        $data['fromMail'] = (isset($emailConfigData[0]['email_id'])) ? $emailConfigData[0]['email_id'] : '';

        $searchContactFields = array('contact_id', 'contact_name', 'email'); // added by Maitrak Modi
        $contacts = $this->common_model->get_records(CONTACT_MASTER, $searchContactFields, '', '', '', '', '', '', 'contact_name', 'ASC', '', '', '', '', '', '', '', ''); // changed by Maitrak Modi

        $currentUserEmailList = $this->getUniqueEmailList($this->session->userdata('LOGGED_IN')['ID'], $searchValue = ''); // get Unique Mail lists
        $data['contacts'] = array_merge($contacts, $currentUserEmailList);

        $data['header'] = array('menu_module' => 'crm');
        $data['mailtype'] = "forward";
        $data['uid'] = $uid;
        $unreadMessages = array();

// $emailData = $this->common_model->get_records(EMAIL_CLIENT_MASTER, '', '', '', 'uid=' . $uid . '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        $data['main_content'] = 'ComposeEmail';
        $emailData = $this->Message_Parse($uid);
//  pr($emailData);
        if (count($emailData) > 0) {
            $frmAddr = ($emailData[0]['from_mail_address']);

            $frmDate = $emailData[0]['send_date'];
            $frmSubject = $emailData[0]['mail_subject'];
            $frmTo = $emailData[0]['to_mail'];
            $frmCC = $emailData[0]['cc_email'];
            $frmEmail = $emailData[0]['from_mail'];
            $data['to'] = '';
            $space = "<br/>";
            $defaultBody = '';
            $defaultBody.='---------- Forwarded message ----------' . $space;
            $defaultBody.='From:' . $frmAddr . '[mailto:' . $frmEmail . ']' . $space;
            $defaultBody.='Date:' . $frmDate . '' . $space;
            $defaultBody.='Subject:' . $frmSubject . '' . $space;
            $defaultBody.='To:' . $frmTo . '' . $space;
            $defaultBody.='Cc:' . $frmCC . '' . $space;
// $defaultBody.=($emailData[0]['mail_body']) . $space;
            $data['subject'] = 'Fwd:' . $frmSubject;
            $body = '';
            $cidArr = [];
            $vp = [];
//  pr($defaultBody);
            $mail_files = array();
            $userid = $this->session->userdata('LOGGED_IN')['ID'];
            foreach ($emailData as $files) {
//echo $files['file_name'];
                $dirpth = FCPATH . "uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name'];
                $fp = "uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name'];
                $vp[] = base_url("uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name']);
                $mail_files[] = array('file_name' => $uid . '-' . $files['file_name'], 'file_path' => $dirpth, 'auto_id' => $files['auto_id'], 'file_path_abs' => $fp, 'file_name_app' => $files['file_name_app']);
                if ($files['file_name_app'] != '') {
                    $cidArr[] = 'cid:' . $files['file_name_app'];
                }
            }
// pr($cidArr);
            $body = str_replace($cidArr, $vp, $emailData[0]['mail_body']);
            $defaultBody.=$body;
            $data['defaultBody'] = $defaultBody;
        }
        $data['mail_files'] = $mail_files;
        $data['emailData'] = $emailData;
        $this->parser->parse('layouts/MailTemplate', $data);
    }

    function replyEmail($uid) {
        $configArr = $this->getEmailConfigData($this->userId);
        if (empty($configArr)) {
            redirect('Mail/mailconfig');
            die;
        }
        $table = EMAIL_CONFIG . ' as ec';
        $where = array("user_id" => $this->session->userdata('LOGGED_IN')['ID']);
        $userId = $this->session->userdata('LOGGED_IN')['ID'];
        $emailConfigData = $this->getEmailConfigData($userId);
        $data['email_signature'] = (isset($emailConfigData[0]['email_signature'])) ? $emailConfigData[0]['email_signature'] : '';
        $data['fromMail'] = (isset($emailConfigData[0]['email_id'])) ? $emailConfigData[0]['email_id'] : '';
        $searchContactFields = array('contact_id', 'contact_name', 'email'); // added by Maitrak Modi
        $contacts = $this->common_model->get_records(CONTACT_MASTER, $searchContactFields, '', '', '', '', '', '', 'contact_name', 'ASC', '', '', '', '', '', '', '', ''); // changed by Maitrak Modi
        $unreadMessages = array();
        $emailAccountDetails = $this->getEmailConfigData($userId);
        $unreadMessagesData = $this->getAccounFolderList($emailAccountDetails[0], false); // get Folder lists
//  pr($unreadMessagesData);
//            if (count($unreadMessages) > 0) {
//            //    $unreadMessages = json_decode($unreadMessagesData[0]['account_folder'], true);
//            }
        $data['percentageSize'] = $configArr[0]['mail_quota'];

        $data['leftMenuFolderCount'] = $unreadMessagesData;
        $currentUserEmailList = $this->getUniqueEmailList($this->session->userdata('LOGGED_IN')['ID'], $searchValue = ''); // get Unique Mail lists
        $data['contacts'] = array_merge($contacts, $currentUserEmailList);

        $data['header'] = array('menu_module' => 'MyProfile');

        $data['mailtype'] = "reply";
        $data['uid'] = $uid;

        $emailData = $this->common_model->get_records(EMAIL_CLIENT_MASTER, '', '', '', 'uid=' . $uid . '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        $data['main_content'] = 'ComposeEmail';
        $data['to'] = count(($emailData) > 0) ? $emailData[0]['from_mail'] : '';
        $data['subject'] = 'Re:' . $emailData[0]['mail_subject'];
        $data['defaultBody'] = $emailData[0]['mail_body'];
        $this->parser->parse('layouts/MailTemplate', $data);
    }

    function saveAttachment($id) {

        $this->load->library('zip');

        $folder = "/uploads/Mail/" . $this->session->userdata('LOGGED_IN')['ID'];
        $dataAttachment = $this->common_model->get_records(EMAIL_CLIENT_ATTACHMENTS . ' as eca', '', array(EMAIL_CLIENT_MASTER . ' as ecm' => 'ecm.email_unq_id=eca.email_id'), 'LEFT', 'email_id=' . $id . '', '', '', '', '', '', '', '', '', '', '', '', '', '');
//        $directories = glob($folder . '/' . '*');

        if (count($dataAttachment) > 0) {
//            foreach ($dataAttachment as $data) {
            $path = FCPATH . $folder . '/' . $dataAttachment[0]['uid'] . '/';
//                $this->zip->read_file($path);
//            }
//   die;
            $this->zip->read_dir($path, false);
            $zip_name = 'attachment-' . $dataAttachment[0]['uid'];
            $this->zip->download($zip_name);
        }
    }

    function SaveSignature() {

        $table = EMAIL_CONFIG . ' as ec';
        $where = array("user_id" => $this->session->userdata('LOGGED_IN')['ID']);
        $emailConfigData = $this->common_model->get_records(EMAIL_CONFIG, '', '', '', '', '', '', '', '', '', '', $where, '', '');
        $data['config'] = $emailConfigData;
        if (count($emailConfigData) > 0) {
            if ($this->input->post('signarea') != '') {
                $sign = $this->input->post('signarea', false);
                $this->Common_model->update($table, array('email_signature' => $sign), $where);
                if ($this->input->post('mailtype') == 'forward') {
                    $reg = 'forwardEmail';
                } elseif ($this->input->post('mailtype') == 'reply') {
                    $reg = 'replyEmail';
                } elseif ($this->input->post('mailtype') == 'replyall') {
                    $reg = 'replyEmailAll';
                } else {
                    $reg = 'ComposeMail';
                }
                redirect('Mail/' . $reg . '/' . $this->input->post('uid'));
            }
        } else {
            $sign = $this->input->post('signarea', false);
            $this->Common_model->insert(EMAIL_CONFIG, array('email_signature' => $sign, 'user_id' => $this->session->userdata('LOGGED_IN')['ID']));
// redirect('Mail/' . $this->input->post('mailtype') . '/' . $this->input->post('uid'));
            if ($this->input->post('mailtype') == 'forward') {
                $reg = 'forwardEmail';
            } elseif ($this->input->post('mailtype') == 'reply') {
                $reg = 'replyEmail';
            } elseif ($this->input->post('mailtype') == 'replyall') {
                $reg = 'replyEmailAll';
            } else {
                $reg = 'ComposeMail';
            }
            redirect('Mail/' . $reg . '/' . $this->input->post('uid'));
        }
    }

    function signatureBox() {

        $table = EMAIL_CONFIG . ' as ec';
        $where = array("user_id" => $this->session->userdata('LOGGED_IN')['ID']);
        $emailConfigData = $this->common_model->get_records(EMAIL_CONFIG, '', '', '', '', '', '', '', '', '', '', $where, '', '');
        $data['config'] = $emailConfigData;
        $data['email_signature'] = (isset($emailConfigData[0]['email_signature'])) ? $emailConfigData[0]['email_signature'] : '';

        $this->load->view('saveSignature', $data);
    }

    /*
      @Author : Brijesh "Tiwari"
      @Desc   : This function is to add email account detail
      @Input  :
      @Output :
      @Date   : 09/03/2016
     */

    function mailconfig() {

        $this->input->post();

//      $this->load->library('Encryption');  // this library is for encoding/decoding password
        $converter = new Encryption;

//$table = EMAIL_CONFIG . ' as ec';
        $where = array("user_id" => $this->session->userdata('LOGGED_IN')['ID']);
//$fields = array("id,user_id,email_id,email_pass");

        $emailConfigData = $this->common_model->get_records(EMAIL_CONFIG, '', '', '', '', '', '', '', '', '', '', $where, '', '');
        if ($emailConfigData) {
            $btlLbl = $this->lang->line('update_btn_lbl');
            $headerLbl = $this->lang->line('update_header_lbl');
        } else {
            $btlLbl = $this->lang->line('add_btn_lbl');
            $headerLbl = $this->lang->line('add_header_lbl');
        }


        $data['viewName'] = $this->viewname;
        $data['header'] = array('menu_module' => 'Settings');
        $data['main_content'] = 'configMail';
        if ($emailConfigData) {
            $data['emailConfigData'] = $emailConfigData[0];
            $data['emailConfigData']['email_pass'] = $converter->decode($emailConfigData[0]['email_pass']);
        }
        $data['emailLbl'] = $this->lang->line('email_lbl');
        $data['passLbl'] = $this->lang->line('email_pass_lbl');
        $data['btnLbl'] = $btlLbl;
        $data['headerLbl'] = $headerLbl;
        $this->parser->parse('layouts/MailTemplate', $data);
    }

    function mailDataExport($user_id) {
        $where = array("user_id" => $user_id);
        $fields = array("id,user_id,email_id,email_pass");
        $emailConfigData = $this->common_model->get_records(EMAIL_CONFIG, $fields, '', '', '', '', '', '', '', '', '', $where, '', '');
        if ($emailConfigData) {
            return $emailConfigData;
        } else {
            return FALSE;
        }
    }

    /*
      @Author : Brijesh Tiwari
      @Desc   : This function is use to get header
      @Input 	:
      @Output	:
      @Date   : 2/03/2016
     */

    public function getHeader($param = NULL) {

        $data['header'] = array('menu_module' => 'crm');
        $this->parser->parse('layouts/HeaderTemplate', $data);
    }

    /*
      @Author : Brijesh Tiwari
      @Desc   : This function is to get unreaded mail count
      @Input  :
      @Output :
      @Date   : 2/03/2016
     */

    public function getUnreadMailCount() {
//error_reporting(E_ERROR);

        $user_id = $this->session->userdata('LOGGED_IN')['ID'];
        $userConfigData = $this->getEmailConfigData($user_id);

        if ($userConfigData) {

            if ($this->checkImapConnection($userConfigData[0])) {
                $this->imap->selectFolder('INBOX');
                return $this->imap->countUnreadMessages();
            } else {
                return 0;
            }
        }
    }

    public function getTotalMailCount($folder) {
//error_reporting(E_ERROR);

        $user_id = $this->session->userdata('LOGGED_IN')['ID'];
        $userConfigData = $this->getEmailConfigData($user_id);
        if ($userConfigData) {
            if ($this->checkImapConnection($userConfigData[0])) {
                $this->imap->selectFolder($folder);
                return $this->imap->countMessages();
            } else {
                return 0;
            }
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : dragDropImgSave function
      @Input 	:
      @Output	:
      @Date   : 27/05/2016
     */

    public function dragDropImgSave($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents(FCPATH . 'uploads/Mail/' . $filename, $str);
    }

    function Message_Parse($id) {
        $userid = $this->session->userdata('LOGGED_IN')['ID'];

        $join = array(EMAIL_CLIENT_ATTACHMENTS . ' as ema' => 'ema.email_id=ec.email_unq_id');
        $where = "uid=$id and ec.user_id=$userid";
        $result = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', '', $join, 'left', '', '', '', '', '', '', '', $where, '', '', '', '', '', '');
// echo $this->db->last_query();
        return $result;
    }

    /*
      @Author : Maitrak Modi
      @Desc   : reomve / move to trash folder function
      @Input 	:
      @Output	:
      @Date   : 3rd June 2016
     */

    public function movetoTrash() {
        error_reporting(0);
        $checkedList = $this->input->post('ids');
        $explodeList = explode(",", $checkedList);

        if (empty($checkedList)) {
            echo json_encode(array('status' => 0));
            exit;
        }

        $userId = $this->session->userdata('LOGGED_IN')['ID'];
        $userConfigData = $this->getEmailConfigData($userId);

        if ($userConfigData) {

            if ($this->checkImapConnection($userConfigData[0])) {

                foreach ($explodeList as $listId) {
                    $dataArray = array('is_deleted' => 1);
                    $this->Common_model->update(EMAIL_CLIENT_MASTER, $dataArray, array('uid' => $listId));
                }

                $this->imap->deleteMessages($explodeList); // move gmail trash folder

                echo json_encode(array('status' => 1));
                exit;
            }
        }
    }

    /*
      @Author : Maitrak Modi
      @Desc   : reomve / move to trash folder function
      @Input 	:
      @Output	:
      @Date   : 3rd June 2016
     */

    public function movetoImportant() {

        $checkedList = $this->input->post('ids');
        $explodeList = explode(",", $checkedList);
        $folder = '[Gmail]/Important';

        if (empty($checkedList)) {
            echo json_encode(array('status' => 0));
            exit;
        }

        $userId = $this->session->userdata('LOGGED_IN')['ID'];
        $userConfigData = $this->getEmailConfigData($userId);

        if ($userConfigData) {

            if ($this->checkImapConnection($userConfigData[0])) {

                foreach ($explodeList as $listId) {
                    $dataArray = array('is_flagged' => 1);
                    $this->Common_model->update(EMAIL_CLIENT_MASTER, $dataArray, array('uid' => $listId));
                }

                $this->imap->moveMessages($explodeList, $folder);

                echo json_encode(array('status' => 1));
                exit;
            }
        }
    }

    /* public function test() {

      $this->load->library('imap');
      $server = "{imap.gmail.com:993/ssl}INBOX";
      $username = "cmswtest101@gmail.com";
      $password = "inf0city";
      $mailbox = $server;
      //$folder = '[Gmail]/Important';
      $encryption = 'tls'; // or ssl or ''

      $imap = $this->imap->connect('imap.gmail.com:993/', $username, $password, $encryption);

      $test = $this->imap->getMailboxStatistics();
      echo "<pre>";
      print_r($test);

      $sent = $this->imap->getSent();
      echo "<pre>";
      print_r($sent);
      exit;
      } */
    /*
      @Author : Mehul Patel
      @Desc   : viewThread page
      @Input 	:
      @Output	:
      @Date   : 04-06-2016
     */

    function viewThread($uid) { {
            $table = EMAIL_CONFIG . ' as ec';

            $userId = $this->session->userdata('LOGGED_IN')['ID'];
            $emailConfigData = $this->getEmailConfigData($userId, array('email_signature', 'email_id'));

            $data['email_signature'] = (isset($emailConfigData[0]['email_signature'])) ? $emailConfigData[0]['email_signature'] : '';
            $data['fromMail'] = (isset($emailConfigData[0]['email_id'])) ? $emailConfigData[0]['email_id'] : '';

            $searchContactFields = array('contact_id', 'contact_name', 'email'); // added by Maitrak Modi
            $contacts = $this->common_model->get_records(CONTACT_MASTER, $searchContactFields, '', '', '', '', '', '', 'contact_name', 'ASC', '', '', '', '', '', '', '', ''); // changed by Maitrak Modi

            $currentUserEmailList = $this->getUniqueEmailList($this->session->userdata('LOGGED_IN')['ID'], $searchValue = ''); // get Unique Mail lists
            $data['contacts'] = array_merge($contacts, $currentUserEmailList);

            $data['header'] = array('menu_module' => 'crm');
            $data['mailtype'] = "forward";
            $data['uid'] = $uid;
            $data['main_content'] = 'ComposeEmail';
            $emailData = $this->Message_Parse($uid);


            if (count($emailData) > 0) {
                $frmAddr = ($emailData[0]['from_mail_address']);

                $frmDate = $emailData[0]['send_date'];
                $frmSubject = $emailData[0]['mail_subject'];
                $frmTo = $emailData[0]['to_mail'];
                $frmCC = $emailData[0]['cc_email'];
                $frmEmail = $emailData[0]['from_mail'];
                $data['to'] = $frmTo;
                $defaultBody = ($emailData[0]['mail_body']);
                $data['subject'] = $frmSubject;
                $data['defaultBody'] = $defaultBody;
                $mail_files = array();
                $userid = $this->session->userdata('LOGGED_IN')['ID'];
                foreach ($emailData as $files) {
//echo $files['file_name'];
                    $dirpth = FCPATH . "uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name'];
                    $fp = base_url() . "uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name'];
                    $mail_files[] = array('file_name' => $uid . '-' . $files['file_name'], 'file_path' => $dirpth, 'auto_id' => $files['auto_id'], 'file_path_abs' => $fp, 'file_name_app' => $files['file_name_app']);
                }
            }
            $data['emailData'] = $emailData;
            $data['mail_files'] = $mail_files;
            $data['emailData'] = $emailData;
            $data['main_content'] = '/ViewThread';
            $this->load->view("ViewThread", $data);
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : Reply All
      @Input 	:
      @Output	:
      @Date   : 04-06-2016
     */

    function replyEmailAll($uid) { {
            $table = EMAIL_CONFIG . ' as ec';

            $userId = $this->session->userdata('LOGGED_IN')['ID'];
            $emailConfigData = $this->getEmailConfigData($userId, array('email_signature', 'email_id'));

            $data['email_signature'] = (isset($emailConfigData[0]['email_signature'])) ? $emailConfigData[0]['email_signature'] : '';
            $data['fromMail'] = (isset($emailConfigData[0]['email_id'])) ? $emailConfigData[0]['email_id'] : '';

            $searchContactFields = array('contact_id', 'contact_name', 'email'); // added by Maitrak Modi
            $contacts = $this->common_model->get_records(CONTACT_MASTER, $searchContactFields, '', '', '', '', '', '', 'contact_name', 'ASC', '', '', '', '', '', '', '', ''); // changed by Maitrak Modi

            $currentUserEmailList = $this->getUniqueEmailList($this->session->userdata('LOGGED_IN')['ID'], $searchValue = ''); // get Unique Mail lists
            $data['contacts'] = array_merge($contacts, $currentUserEmailList);

            $data['header'] = array('menu_module' => 'MyProfile');
            $data['mailtype'] = "replyall";
            $data['uid'] = $uid;
            $unreadMessages = array();
            $emailAccountDetails = $this->getEmailConfigData($userId);

            $unreadMessagesData = $this->getAccounFolderList($emailAccountDetails, false); // get Folder lists
//  pr($unreadMessagesData);
//            if (count($unreadMessages) > 0) {
//            //    $unreadMessages = json_decode($unreadMessagesData[0]['account_folder'], true);
//            }
            $percentageSize = $this->getLeftMenuMailSize();
            $data['percentageSize'] = $percentageSize;
            $data['leftMenuFolderCount'] = $unreadMessagesData;
// $emailData = $this->common_model->get_records(EMAIL_CLIENT_MASTER, '', '', '', 'uid=' . $uid . '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $data['main_content'] = 'ComposeEmail';
            $emailData = $this->Message_Parse($uid);

            if (count($emailData) > 0) {
                $frmAddr = ($emailData[0]['from_mail_address']);

                $frmDate = $emailData[0]['send_date'];
                $frmSubject = $emailData[0]['mail_subject'];
                $frmTo = $emailData[0]['to_mail'];
                $frmCC = $emailData[0]['cc_email'];
                $frmEmail = $emailData[0]['from_mail'];
                $data['to'] = $frmTo;
                $space = "<br/>";
                $defaultBody = '';
                $defaultBody.='---------- Forwarded message ----------' . $space;
                $defaultBody.='From:' . $frmAddr . '[mailto:' . $frmEmail . ']' . $space;
                $defaultBody.='Date:' . $frmDate . '' . $space;
                $defaultBody.='Subject:' . $frmSubject . '' . $space;
                $defaultBody.='To:' . $frmTo . '' . $space;
                $defaultBody.='Cc:' . $frmCC . '' . $space;
                $defaultBody.=($emailData[0]['mail_body']) . $space;
                $data['subject'] = 'RE:' . $frmSubject;
                $data['defaultBody'] = htmlentities($defaultBody);
                $mail_files = array();
                $userid = $this->session->userdata('LOGGED_IN')['ID'];
                foreach ($emailData as $files) {
//echo $files['file_name'];
                    $dirpth = FCPATH . "uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name'];
                    $fp = base_url() . "uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name'];
                    $vp[] = base_url("uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name']);
                    $mail_files[] = array('file_name' => $uid . '-' . $files['file_name'], 'file_path' => $dirpth, 'auto_id' => $files['auto_id'], 'file_path_abs' => $fp, 'file_name_app' => $files['file_name_app']);
                    if ($files['file_name_app'] != '') {
                        $cidArr[] = 'cid:' . $files['file_name_app'];
                    }
                }
            }
            $data['mail_files'] = $mail_files;
            $data['emailData'] = $emailData;
            $this->parser->parse('layouts/MailTemplate', $data);
        }
    }

    function storeMsgs($folderName) {

        ini_set('max_execution_time', 0);
        set_time_limit(0);

        $userId = $this->session->userdata('LOGGED_IN')['ID'];

        $userConfigData = $this->getEmailConfigData($userId);

        if ($userConfigData) {

            if ($this->checkImapConnection($userConfigData[0])) {

                $this->Common_model->delete(EMAIL_CLIENT_MASTER, array('is_local' => 0, 'boxType' => $folderName, 'user_id' => $userId));

                $this->Common_model->delete(EMAIL_CLIENT_ATTACHMENTS, array('is_local' => 0, 'boxtype_file' => $folderName, 'user_id' => $userId));

                $this->imap->selectFolder($folderName);
                $emails = $this->imap->getMessages();

                if (count($emails) > 0) {

                    $dirpth = FCPATH . "uploads/Mail/$userId/";
                    if (!is_dir($dirpth)) {
                        mkdir($dirpth, 0777);
                    }

                    foreach ($emails as $row) {

                        $relPath = base_url("uploads/Mail/$userId/" . $row['uid'] . '/');
                        $absPath = FCPATH . "uploads/Mail/$userId/" . $row['uid'] . '/';

                        if (!is_dir($absPath)) {
                            mkdir($absPath, 0777);
                        }

                        $attachmentArr = (isset($row['attachments'])) ? $row['attachments'] : array();
                        $bodyContent = $this->inlineBodyImageConversion($row['body'], $attachmentArr, $relPath, $absPath);

                        $data = array(
                            'to_mail' => isset($row['to_email']) ? implode(',', $row['to_email']) : '',
                            'from_mail' => $row['from_email'],
                            'send_date' => $row['date'],
                            'mail_subject' => $row['subject'],
                            'uid' => $row['uid'],
                            'is_unread' => $row['unread'],
                            'is_answered' => $row['answered'],
                            'is_deleted' => $row['deleted'],
                            'mail_body' => $bodyContent,
                            'is_html' => $row['html'],
                            'user_id' => $userId,
                            'creation_date' => datetimeformat(),
                            'sync_on' => datetimeformat(),
                            'cc_email' => isset($row['cc']) ? implode(',', $row['cc']) : '',
                            'bcc_email' => isset($row['bcc']) ? implode(',', $row['bcc']) : '',
                            'reply_to_address' => (isset($row['reply_to_address']) && $row['reply_to_address'] != '') ? implode(',', $row['reply_to_address']) : '',
                            'reply_to_email' => (isset($row['reply_to_email']) && $row['reply_to_email'] != '') ? implode(',', $row['reply_to_email']) : '',
                            'from_mail_address' => $row['from'],
                            'to_email_address' => isset($row['to']) ? implode(',', $row['to']) : '',
                            'header_data' => json_encode(array($row['header'])),
                            'msg_no' => $row['message_no'],
                            'boxtype' => $folderName
                        );

                        $this->db->insert('email_client_master', $data);
                        $mailId = $this->db->insert_id();

// start - insert attachment  data
                        if (isset($row['attachments'])) {

                            foreach ($row['attachments'] as $attch) {
                                if (isset($attch['is_attachment']) && $attch['is_attachment']) {
                                    $attcharr = array(
                                        'email_id' => $mailId,
                                        'file_name' => $attch['filename'],
                                        'file_size' => $attch['size'],
                                        'user_id' => $userId,
                                        'boxtype_file' => $folderName,
                                        'file_name_app' => $attch['cid']
                                    );
                                    $this->db->insert('email_client_attachments', $attcharr);
                                }
                            }
                        }
                    }
                }
            } else {
                die($this->imap->getError());
            }
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : download attachment function
      @Input 	:
      @Output	:
      @Date   : 01/02/2016
     */

    function download($id) {
        if ($id > 0) {

            $this->load->library('zip');
            $params['fields'] = ['*'];
            $params['table'] = EMAIL_CLIENT_ATTACHMENTS . ' as CM';
            $params['match_and'] = 'CM.auto_id=' . $id . '';
            $params['join_tables'] = array(EMAIL_CLIENT_MASTER . ' as ecm' => 'ecm.email_unq_id=CM.email_id');
            $params['join_type'] = 'inner';
            $cost_files = $this->common_model->get_records_array($params);
            if (count($cost_files) > 0) {
                $folder = "/uploads/Mail/" . $this->session->userdata('LOGGED_IN')['ID'] . '/' . $cost_files[0]['uid'];
                $pth = file_get_contents(base_url($folder . '/' . $cost_files[0]['file_name']));
                $this->load->helper('download');
                force_download($cost_files[0]['file_name'], $pth);
            }
        }
    }

    function markasRead() {

        if (!$this->input->is_ajax_request()) {
            exit('No Direct Scripts are allowed');
        }

        $this->load->model('Common_model');
        $uid = $this->input->post('uid');
        $userId = $this->session->userdata('LOGGED_IN')['ID'];

        $where = " uid = $uid and ec.is_unread = 1 and ec.user_id = " . $userId;
        $result = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', '', '', '', '', '', '', '', '', '', '', $where, '', '', '', '', '', '');

        if (count($result) > 0) {

            $this->Common_model->update(EMAIL_CLIENT_MASTER, array('is_unread' => 0), array('uid' => $uid, 'user_id' => $userId));

            $userConfigData = $this->getEmailConfigData($userId);

            if ($this->checkImapConnection($userConfigData[0])) {
                $this->imap->setUnseenMessage($uid);
            }
        }
        return true;
    }

    function getEmailTest() {

        ini_set('max_execution_time', 0);
        set_time_limit(0);

        $this->load->library('imap');

        $username = "cmswtest101@gmail.com"; //cmetric2016@outlook.com";
        $password = "inf0city";
        $encryption = 'SSL'; // or ssl or ''
        //if ($this->imap->connect('imap-mail.outlook.com:993/', $username, $password, $encryption)) {


        $mbox = $this->imap->connect('imap..gmail.com:993/', $username, $password, $encryption) or die("can't connect: " . imap_last_error());

        $quota = imap_get_quotaroot($mbox, "INBOX");
        if (is_array($quota)) {
            $storage = $quota['STORAGE'];
            echo "STORAGE usage level is: " . $storage['usage'];
            echo "STORAGE limit level is: " . $storage['limit'];

            $message = $quota['MESSAGE'];
            echo "MESSAGE usage level is: " . $message['usage'];
            echo "MESSAGE limit level is: " . $message['limit'];
        }
        /* ...  */

        //imap_close($mbox);
        //$this->imap->getQuotaUsage();
        /* $quota = imap_get_quota($this->imap->connect('imap-mail.outlook.com:993/', $username, $password, $encryption), "user.kalowsky");

          if (is_array($quota)) {
          $storage = $quota['STORAGE'];
          echo "STORAGE usage level is: " . $storage['usage'];
          echo "STORAGE limit level is: " . $storage['limit'];

          $message = $quota['MESSAGE'];
          echo "MESSAGE usage level is: " . $message['usage'];
          echo "MESSAGE limit level is: " . $message['limit'];
          } */

        exit;

        /* $this->imap->selectFolder('INBOX');
          $emails = $this->imap->getMessages();
          //  echo "<prE>";
          // print_r($emails);
          //exit;
          foreach ($emails as $row) {

          $bodyData = $row['body'];
          $attachmentArray = $row['attachments'];
          $this->inlineBodyImageConversion($bodyData, $attachmentArray, $path = FCPATH . '/uploads/Mail/');
          }
          echo "done";
          exit; */

        /* preg_match_all('/src="cid:(.*)"/Uims', $bodyData, $matches);

          //echo "<prE>";
          //print_r($matches);

          if (count($matches)) {

          $search = array();
          $replace = array();

          foreach ($matches[1] as $key => $match) {

          ++$key;

          //$ext =  substr(strrchr($row['attachments'][$match]['filename'],'.'),1);

          if ($row['attachments'][$key]['is_attachment']) {

          //$currCID = $row['attachments'][$key]['cid'];
          //echo $currCID. '------'. $match;
          $uniqueFilename = $row['attachments'][$key]['name'];

          file_put_contents(FCPATH . 'uploads/Mail/' . $uniqueFilename, $row['attachments'][$key]['attachment']);
          $search[] = "src=\"cid:$currCID\"";
          $replace[] = "src=\"http://localhost/maitrak/blazedeskcrm/uploads/Mail/$uniqueFilename\"";
          }
          }

          $row['body'] = str_replace($search, $replace, $row['body']);
          echo $row['body'];
          }
          }
          } else {
          die($this->imap->getError());
          }

          echo "===== Done =====";
          exit; */
        //}
    }

    /*
      @Author : Maitrak Modi
      @Desc   : Search Contacts
      @Input 	:
      @Output	:
      @Date   : 6th June 2016
     */

    public function searchContacts() {

        $this->load->library('session');
        $currentUserId = $this->session->userdata('LOGGED_IN')['ID'];

        $searchValue = ($this->input->post('searchValue')) ? $this->input->post('searchValue') : '';
        $whereSearch = '';
        if (!empty($searchValue)) {
            $whereSearch = '(contact_name LIKE "%' . $searchValue . '%" OR email LIKE "%' . $searchValue . '%" )';
        }

        $currentUserEmailList = $this->getUniqueEmailList($currentUserId, $searchValue); // get Unique Mail lists

        $searchFields = array('contact_id', 'contact_name', 'email');
        $contacts = $this->common_model->get_records(CONTACT_MASTER, $searchFields, '', '', '', '', '', '', 'contact_name', 'ASC', '', $whereSearch, '', '', '', '', '', '');

        $data['contacts'] = array_merge($contacts, $currentUserEmailList);

        return $this->load->view('searchContactsList', $data);
    }

    /*
      @Author : Maitrak Modi
      @Desc   : get Unique Emails Ids
      @Input 	:
      @Output	:
      @Date   : 6th June 2016
     */

    public function getUniqueEmailList($userId = '', $searchValue = '') {

        $emailListArray = array();

        if (empty($userId)) {
            return $emailListArray;
        }

        $whereSearchForMail = ' AND (to_mail LIKE "%' . $searchValue . '%" '
                . 'OR from_mail LIKE "%' . $searchValue . '%" '
                . 'OR cc_email LIKE "%' . $searchValue . '%" '
                . 'OR bcc_email LIKE "%' . $searchValue . '%" '
                . 'OR bcc_email LIKE "%' . $searchValue . '%" '
                . ')';


        $whereSearch = '(user_id = ' . $userId . ') ' . $whereSearchForMail;

        $searchFields = array('from_mail', 'cc_email', 'to_mail', 'bcc_email'); // search fields

        $emailData = $this->common_model->get_records(EMAIL_CLIENT_MASTER, $searchFields, '', '', '', '', '', '', '', '', '', $whereSearch, '', '', '', '', '', '');

        foreach ($emailData as $email) {

            $emailListArray[] = trim($email['from_mail']);

            if (!empty($email['to_mail'])) {
                $toMails = trim($email['to_mail']);
                $explodeToMail = explode(',', $toMails);
                $emailListArray = array_merge($emailListArray, $explodeToMail);
            }

            if (!empty($email['cc_mail'])) {
                $ccMails = trim($email['cc_mail']);
                $explodeCCMail = explode(',', $ccMails);
                $emailListArray = array_merge($emailListArray, $explodeCCMail);
            }

            if (!empty($email['bcc_mail'])) {
                $bccMails = trim($email['bcc_mail']);
                $explodeBCCMail = explode(',', $bccMails);
                $emailListArray = array_merge($emailListArray, $explodeBCCMail);
            }
        }

        $emailListArray = preg_grep('~' . $searchValue . '~', $emailListArray);

        return array_unique($emailListArray);
    }

    /*
      @Author : Maitrak Modi
      @Desc   : get Conneted folder list
      @Input  : array parameters : mailbox, username, password, encryption
      @Output : return folder lists
      @Date   : 7th June 2016
     * 
     */

    public function getAccounFolderList($emailAccountDetails = array(), $manualReferesh = true) {

        if (empty($emailAccountDetails)) {
            return $emailAccountDetails;
        }
        set_time_limit(0);
        $userId = $this->session->userdata('LOGGED_IN')['ID'];
        $unreadMessages = array();

        /*
         * firsts checks whether data is there in the array then wont call imap count
         */

        $configArr = $this->getEmailConfigData($this->userId);
        if (count($configArr) > 0 && $manualReferesh == false) {

            $folders = (array) json_decode($configArr[0]['account_folder']);
            $unreadMessages = $folders;
// pr($folders);
//            foreach ($folders as $key => $valf) {
//
////$this->imap->selectFolder($valf);
////$overallMessages[$valf] = $this->imap->countMessages();
//                $where = "ec.boxType='" . $key . "' AND ec.is_unread = 1 AND ec.user_id='" . $userId . "' "; // unread message count
//                $fields = array("email_unq_id");
//                $countEmails = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, '', '', '', '', '', '', '', '', '', $where, '', '', '1', '', '', '');
////   pr($countEmails);
//
//                $unreadMessages[$key] = $countEmails;
//            }
        } else {
            if ($this->checkImapConnection($emailAccountDetails)) {

                $folderlist = $this->imap->getFolders();

//echo "<pre>";  print_R($folderlist); exit;
                foreach ($folderlist as $valf) {
//$this->imap->selectFolder($valf);
//$overallMessages[$valf] = $this->imap->countMessages();
                    $where = "ec.boxType='" . $valf . "' AND ec.is_unread = 1 AND ec.user_id='" . $userId . "' "; // unread message count
                    $fields = array("email_unq_id");
                    $countEmails = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, '', '', '', '', '', '', '', '', '', $where, '', '', '1', '', '', '');
//   pr($countEmails);
                    if (!empty($countEmails)) {
                        $unreadMessages[$valf] = $countEmails;
                    } else {
                        $this->imap->selectFolder($valf);
                        $unreadMessages[$valf] = $this->imap->countUnreadMessages();
                    }
                }
            }
        }


        return $unreadMessages;
    }

    public function getAccounFolderListManual($emailAccountDetails = array(), $manualReferesh = true) {

        if (empty($emailAccountDetails)) {
            return $emailAccountDetails;
        }
        set_time_limit(0);
        $userId = $this->session->userdata('LOGGED_IN')['ID'];
        $unreadMessages = array();

        /*
         * firsts checks whether data is there in the array then wont call imap count
         */

        $configArr = $this->getEmailConfigData($this->userId);
        if ($this->checkImapConnection($emailAccountDetails)) {
            $emailData['mail_quota'] = $this->getLeftMenuMailSize();
            $where = array("user_id" => $this->session->userdata('LOGGED_IN')['ID']);
            // $emailConfigData = $this->common_model->get_records(EMAIL_CONFIG, '', '', '', '', '', '', '', '', '', '', $where, '', '');



            $folderlist = $this->imap->getFolders();
//echo "<pre>";  print_R($folderlist); exit;
            foreach ($folderlist as $valf) {
                $this->imap->selectFolder($valf);
                $unreadMessages[$valf] = $this->imap->countUnreadMessages();
            }
            $emailData['account_folder'] = json_encode($unreadMessages);
            $success_update = $this->common_model->update(EMAIL_CONFIG, $emailData, $where);
        }


        return $unreadMessages;
    }

    /*
      @Author : Maitrak Modi
      @Desc   : inline mail's image src changes (from Body)
      @Input  :
      @Output :
      @Date   : 7th June 2016
     * 
     */

    public function inlineBodyImageConversion($body = '', $attachmentArray = array(), $relpath = '', $abspath = '') {

        $returnBody = '';
        if (empty($body)) {
            return $returnBody;
        }
        if (!empty($attachmentArray)) {

            preg_match_all('/src="cid:(.*)"/Uims', $body, $matches);

            if (count($matches)) {

                $search = array();
                $replace = array();

                foreach ($matches[1] as $key => $match) {
                    ++$key;

                    if (isset($attachmentArray[$key]['is_attachment'])) {
                        $uniqueFilename = $attachmentArray[$key]['name'];
// @file_put_contents($abspath . $uniqueFilename, $attachmentArray[$key]['attachment']);
                        $search[] = "src=\"cid:$match\"";
                        $replace[] = "src=\"$relpath/$uniqueFilename\"";
                    }
                }
                $returnBody = str_replace($search, $replace, $body);
            }

            return $returnBody;
        } else {
            return $body;
        }
    }

    /*
      @Author : Maitrak Modi
      @Desc   : get data from email config data
      @Input 	: userId , fields
      @Output	: return config data array
      @Date   : 8th June 2016
     * 
     */

    public function getEmailConfigData($userId = '', $fields = '') {

        $configData = array();

        if (empty($userId)) {

            return $configData;
        }

        $where = "user_id = " . $userId;
        $configData = $this->common_model->get_records(EMAIL_CONFIG, $fields, '', '', '', '', '1', '', '', '', '', $where, '', '', '', '', '', '');

        return $configData;
    }

    /*
      @Author : Maitrak Modi
      @Desc   : Check Imap connection
      @Input 	: config data
      @Output	:
      @Date   : 8th June 2016
     * 
     */

    public function checkImapConnection($configEmailData = array()) {

//pr($configEmailData);
        if (empty($configEmailData)) {
            return false;
        }

        $converter = new Encryption;

        $mailbox = $configEmailData['email_server'] . ':' . $configEmailData['email_port'] . '/';

        $username = $configEmailData['email_id'];
        $password = $converter->decode($configEmailData['email_pass']);
        $encryption = $configEmailData['email_encryption'];
        if ($this->imap->connect($mailbox, $username, $password, $encryption)) {

            return true;
        } else {
            return false;
        }
    }

    function leftBarCount() {
        $unreadMessages = array();
        $emailData = $this->getEmailConfigData($this->session->userdata('LOGGED_IN')['ID'])[0];

        $emailAccountDetails = array(
            'email_server' => $emailData['email_server'],
            'email_port' => $emailData['email_port'],
            'email_id' => $emailData['email_id'],
            'email_pass' => $emailData['email_pass'],
            'email_encryption' => $emailData['email_encryption']
        );

        $unreadMessagesData = $this->getAccounFolderListManual($emailAccountDetails, true); // get Folder lists
//  pr($unreadMessagesData);
//            if (count($unreadMessages) > 0) {
//            //    $unreadMessages = json_decode($unreadMessagesData[0]['account_folder'], true);
//            }

        $data['percentageSize'] = $emailData['mail_quota'];
        $data['leftMenuFolderCount'] = $unreadMessagesData;
        $this->load->view('leftbar', $data);
    }

    /*
      @Author : Maitrak Modi
      @Desc   : Add / Edit Imap connection in db
      @Input 	:
      @Output	:
      @Date   : 16th June 2016
     * 
     */

    function validateMailConfig() {

        set_time_limit(0);

        $this->input->post();

        $converter = new Encryption;
        $unreadMessages = $responseData = array();

        if ($this->input->post()) {

            $emailData['email_id'] = ($this->input->post('email') != '') ? $this->input->post('email') : '';
            $emailData['email_pass'] = $converter->encode($this->input->post('password'));
            $emailData['email_server'] = ($this->input->post('email_server') != '') ? $this->input->post('email_server') : '';
            $emailData['email_port'] = ($this->input->post('email_port') != '') ? $this->input->post('email_port') : '';
            $emailData['email_encryption'] = ($this->input->post('email_encryption') != '') ? $this->input->post('email_encryption') : '';
            $emailData['email_smtp'] = ($this->input->post('email_smtp') != '') ? $this->input->post('email_smtp') : '';
            $emailData['email_smtp_port'] = ($this->input->post('email_smtp_port') != '') ? $this->input->post('email_smtp_port') : '';
            $emailData['modified_date'] = datetimeformat();



            if (!empty($emailData['email_id']) && !empty($emailData['email_pass']) && !empty($emailData['email_server']) &&
                    !empty($emailData['email_port']) && !empty($emailData['email_encryption']) && !empty($emailData['email_smtp']) &&
                    !empty($emailData['email_smtp_port'])) {

                $emailAccountDetails = array(
                    'email_server' => $emailData['email_server'],
                    'email_port' => $emailData['email_port'],
                    'email_id' => $emailData['email_id'],
                    'email_pass' => $emailData['email_pass'],
                    'email_encryption' => $emailData['email_encryption']
                );

                if ($this->checkImapConnection($emailAccountDetails)) {
                    $emailData['mail_quota'] = $this->getLeftMenuMailSize();
                    $folderlist = $this->imap->getFolders();

                    if (!empty($folderlist)) {

                        foreach ($folderlist as $valf) {

                            $this->imap->selectFolder($valf);
                            $unreadMessages[$valf] = $this->imap->countUnreadMessages();
                        }

                        $emailData['account_folder'] = json_encode($unreadMessages);
                    }

                    $where = array("user_id" => $this->session->userdata('LOGGED_IN')['ID']);
                    $emailConfigData = $this->common_model->get_records(EMAIL_CONFIG, '', '', '', '', '', '', '', '', '', '', $where, '', '');

                    if (!empty($emailConfigData)) {

                        $success_update = $this->common_model->update(EMAIL_CONFIG, $emailData, $where);
                        $this->common_model->delete(EMAIL_CLIENT_MASTER, array('user_id' => $this->userId, 'is_local' => 0));
                        $this->common_model->delete(EMAIL_CLIENT_ATTACHMENTS, array('user_id' => $this->userId, 'is_local' => 0));
                        $this->pagingMails(1, 10, 'INBOX');
                        $pass_msg = $this->lang->line('mail_config_success_update');
                    } else {

                        $emailData['user_id'] = $this->session->userdata('LOGGED_IN')['ID'];
                        $emailData['created_date'] = datetimeformat();
                        $success_insert = $this->common_model->insert(EMAIL_CONFIG, $emailData);
                        $this->common_model->delete(EMAIL_CLIENT_MASTER, array('user_id' => $this->userId, 'is_local' => 0));
                        $this->common_model->delete(EMAIL_CLIENT_ATTACHMENTS, array('user_id' => $this->userId, 'is_local' => 0));
                        $this->pagingMails(1, 10, 'INBOX');

                        $pass_msg = $this->lang->line('mail_config_success_insert');
                    }

                    $status = 1;
                    $message = $pass_msg;
                } else {
                    $status = 0;
                    error_reporting(0);
                    $data = (string) $this->imap->getError();
                    $message = $data;
                }
            } else {
                $status = 0;
                $message = $this->lang->line('mail_config_error_msg');
            }
        } else {
            $status = 0;
            $message = $this->lang->line('mail_config_error_method');
        }

        $responseData = array(
            'status' => $status,
            'message' => $message
        );

        echo json_encode($responseData);
        exit;
    }

    function pagingMails($offset, $nopage, $folder) {
        $userId = $this->userId;
        $configEmailData = $this->getEmailConfigData($this->userId)[0];
        $converter = new Encryption;
        $mailbox = $configEmailData['email_server'] . ':' . $configEmailData['email_port'] . '/';
        $username = $configEmailData['email_id'];
        $password = $converter->decode($configEmailData['email_pass']);
        $encryption = $configEmailData['email_encryption'];
        $connect = $this->imap->connect($mailbox, $username, $password, $encryption);
        $this->imap->selectFolder($folder);
        $emails = $this->imap->listMessages($offset, $nopage, $sort = null);

        if (count($emails) > 0) {

            $dirpth = FCPATH . "uploads/Mail/$userId/";
            if (!is_dir($dirpth)) {
                mkdir($dirpth, 0777);
            }

            foreach ($emails as $row) {

                $relPath = base_url("uploads/Mail/$userId/" . $row['uid'] . '/');
                $absPath = FCPATH . "uploads/Mail/$userId/" . $row['uid'] . '/';

                if (!is_dir($absPath)) {
                    mkdir($absPath, 0777);
                }

                $attachmentArr = (isset($row['attachments'])) ? $row['attachments'] : array();
                $bodyContent = $this->inlineBodyImageConversion($row['body'], $attachmentArr, $relPath, $absPath);

                $data = array(
                    'to_mail' => isset($row['to_email']) ? implode(',', $row['to_email']) : '',
                    'from_mail' => $row['from_email'],
                    'send_date' => $row['date'],
                    'mail_subject' => $row['subject'],
                    'uid' => $row['uid'],
                    'is_unread' => $row['unread'],
                    'is_answered' => $row['answered'],
                    'is_deleted' => $row['deleted'],
                    'mail_body' => $bodyContent,
                    'is_html' => $row['html'],
                    'user_id' => $userId,
                    'creation_date' => datetimeformat(),
                    'sync_on' => datetimeformat(),
                    'cc_email' => isset($row['cc']) ? implode(',', $row['cc']) : '',
                    'bcc_email' => isset($row['bcc']) ? implode(',', $row['bcc']) : '',
                    'reply_to_address' => (isset($row['reply_to_address']) && $row['reply_to_address'] != '') ? implode(',', $row['reply_to_address']) : '',
                    'reply_to_email' => (isset($row['reply_to_email']) && $row['reply_to_email'] != '') ? implode(',', $row['reply_to_email']) : '',
                    'from_mail_address' => $row['from'],
                    'to_email_address' => isset($row['to']) ? implode(',', $row['to']) : '',
                    'header_data' => json_encode(array($row['header'])),
                    'msg_no' => $row['message_no'],
                    'boxtype' => $folder
                );

                $this->db->insert('email_client_master', $data);
                $mailId = $this->db->insert_id();

// start - insert attachment  data
                if (isset($row['attachments'])) {

                    foreach ($row['attachments'] as $attch) {
                        if (isset($attch['is_attachment']) && $attch['is_attachment']) {
                            $attcharr = array(
                                'email_id' => $mailId,
                                'file_name' => $attch['filename'],
                                'file_size' => $attch['size'],
                                'user_id' => $userId,
                                'boxtype_file' => $folder,
                                'file_name_app' => $attch['cid']
                            );
                            $this->db->insert('email_client_attachments', $attcharr);
                        }
                    }
                }
            }
        } else {
            //  die($this->imap->getError());
        }

//$this->imap->pagingMails($offset,$nopage, $folder);
// Fetch an overview for all messages in INBOX
    }

    public function getLeftMenuMailSize() {

        $sizeData = $this->imap->getQuotaUsage();
        //error_reporting(0);
        if ($sizeData) {
            $percentageSize = floor(($sizeData['usage'] * 100 ) / $sizeData['limit']);
            $percentageSize .= ' % ' . $this->lang->line('mail_storage');
        } else {
            $percentageSize = 'No data available.';
        }

        return $percentageSize;
    }

    public function getNewEmails() {
        $userId = $this->userId;
        $configEmailData = $this->getEmailConfigData($this->userId)[0];
        $converter = new Encryption;
        $mailbox = $configEmailData['email_server'] . ':' . $configEmailData['email_port'] . '/';
        $username = $configEmailData['email_id'];
        $password = $converter->decode($configEmailData['email_pass']);
        $encryption = $configEmailData['email_encryption'];
        $connect = $this->imap->connect($mailbox, $username, $password, $encryption);
        $this->imap->selectFolder('INBOX');

        $emails = $this->imap->pagingMails();
        //pr($emails);
        die;
    }

}
