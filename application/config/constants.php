<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
  Author : RJ
  Desc   : Define Constants Value
  Date   : 12/01/2016
 */
define('SAMPLE_TABLE', 'sampletable');
define('LOGIN', 'login');
define('ERROR_DANGER_DIV', '<div class="alert alert-danger text-center">');
define('ERROR_SUCCESS_DIV', '<div class="alert alert-success text-center">');
define('ERROR_START_DIV', '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>');
/* Added  By Sanket */
define('ERROR_START_DIV_NEW', '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>');
define('ERROR_END_DIV', '</div>');
define('ESTIMATE_TEMPLATE', 'estimate_template');
define('ESTIMATE_TEMPLATE_PRODUCT', 'estimate_template_product');
define('ESTIMATE_CLIENT', 'estimate_client');
define('ESTIMATE_SEND_EST', 'estimate_send_est');
define('ESTIMATE_PRODUCT_GROUP', 'estimate_product_group');

/*
  Author : Ritesh Rana
  Desc   : Define Constants Value
  Date   : 12/01/2016
 */
define('CAMPAIGN_MASTER', 'campaign_master');
define('CAMPAIGN_FILE_MASTER', 'campaign_files_master');
define('CAMPAIGN_GROUP_MASTER', 'campaign_group_master');
define('CAMPAIGN_RECEIPIENT_TRAN', 'campaign_receipents_tran');
define('CAMPAIGN_TYPE_MASTER', 'campaign_type_master');
define('BRANCH_MASTER', 'branch_master');
define('PRODUCT_MASTER', 'product_master');
define('CONTACT_MASTER', 'contact_master');
define('SUPPLIER_MASTER', 'supplier_master');
define('COMPANY_MASTER', 'company_master');
define('CAMPAIGN_GROUP_SALES_MASTER', 'campaign_group_sales_master');
define('BUDGET_CAMPAIGN_PRODUCT_TRAN', 'budget_campaign_product_tran');
define('CAMPAIGN_PRODUCT_TRAN', 'campaign_product_tran');
define('Ticket_upload_path', 'uploads/ticket_file');
define('KNOWLEDGEBASE_KNOWLEDGE_ARTICLE', 'knowledgebase_knowledge_article');
define('KNOWLEDGEBASE_FILE_MASTER', 'knowledge_file_master');
define('KNOWLEDGEBASE_MAIN_CATEGORY', 'knowledgebase_main_category');
define('KNOWLEDGEBASE_SUB_CATEGORY', 'knowledgebase_sub_category');
define('REQUEST_BUDGET_FILES', 'request_budget_files_master');
define('ESTIMATE_CLIENT_APPROVAL', 'estimate_client_approval');
define('LOG_MASTER', 'log_master');
define('CI_SESSION', 'ci_sessions');
define('SETUP_MASTER', 'setup_master');
define('CAMPAIGN_RESPONSIBLE_EMPLOYEE_TRAN', 'campaign_responsible_employee_tran');
define('BUDGET_CAMPAIGN_RESPONSIBLE_EMPLOYEE_TRAN', 'budget_campaign_responsible_employee_tran');
define('COMPANY', 'blzdsk_company');



/*
  Author : Seema Tankariya
  Desc   : Define Constants Value
  Date   : 13/01/2016
 */
define('PROSPECT_MASTER', 'prospect_master');
define('PROSPECT_CONTACTS_TRAN', 'prospect_contacts_tran');
define('LEAD_CONTACTS_TRAN', 'lead_contacts_tran');
define('PROSPECT_PRODUCTS_TRAN', 'prospect_products_tran');
define('LEAD_PRODUCTS_TRAN', 'lead_products_tran');
define('TASK_MASTER', 'task_master');
define('DEAL_SALES', 'deal_sales');
define('CONTRACT_SALES_MASTER', 'contract_sales_master');
define('INVOICE_SALES_MASTER', 'invoice_sales_master');
define('PROJECT_SALES_MASTER', 'project_sales_master');
define('COMMUNICATION_SALES', 'communication_sales');
define('SUPPORT_SALES_MASTER', 'support_sales_master');
define('ESTIMATE_MASTER', 'estimate_master');
define('COUNTRIES', 'countries');
define('LEAD_MASTER', 'lead_master');
define('OPPORTUNITY_REQUIREMENT', 'opportunity_requirement');
define('OPPORTUNITY_REQUIREMENT_CONTACTS', 'opportunity_requirement_contacts');
define('FILES_LEAD_MASTER', 'files_lead_master');
define('prospect_upload_path', 'uploads/prospect_upload');
define('cases_upload_path', 'uploads/cases_upload');
define('sales_upload_path', 'uploads/sales_campaign');
define('budget_upload_path', 'uploads/budgetCampaign');
define('milestone_upload_path', 'uploads/projectMilestone');
define('project_task_upload_path', 'uploads/projectTask');
define('project_incidents_upload_path', 'uploads/projectManagement/ProjectIncidents');
define('knowledge_upload_path', 'uploads/knowledgebase');
define('SETTINGS_PROFILE_PIC_UPLOAD_PATH', 'uploads/settings_upload');
define('LANGUAGE_MASTER', 'language_master');
define('COMPANY_ACCOUNTS_TRAN','company_accounts_tran');
define('CONTACT_PIC_UPLOAD_PATH', 'uploads/profile_photo');
define('CONTACT_PIC_HEIGHT', '36');
define('CONTACT_PIC_WIDTH', '36');
//define('GOOGLE_APPLICATION_CREDENTIALS', 'GoogleAnalytics/upload_json');
define('GOOGLE_APPLICATION_CREDENTIALS', 'application/third_party/GoogleAnalytics/upload_json');
// this is for youtube by brt
define('YOUTUBE_APPLICATION_CREDENTIALS', 'third_party/GoogleAnalytics/upload_json');
define('CRM_CASES', 'crm_cases');
define('CRM_CASES_FILES_MASTER','crm_cases_files_master');

/*
  Author : Mehul Patel
  Desc   : Define Constants Value
  Date   : 21/01/2016
 */

define('ROLE_MASTER', 'role_master');
define('AAUTH_PERMS_TO_ROLE', 'aauth_perm_to_group');
define('AAUTH_PERMS', 'aauth_perms');
define('MODULE_MASTER', 'module_master');
define('EMAIL_TEMPLATE_MASTER', 'email_template_master');
define('EMAIL_SETTINGS', 'email_settings');


/*
  Author : Sanket Jayani
  Desc   : Define Constants Value
  Date   : 27/01/2016
 */
define('RECORD_PER_PAGE', '10');
define('CURRENCY_CODE', '$');
define('SALUTIONS_LIST', 'blzdsk_salutions_list');
define('TBL_NOTE', 'blzdsk_notes');
define('TBL_CRM_CASES_TYPE', 'blzdsk_crm_cases_type');
define('TBL_CRM_JOB_TITLE', 'blzdsk_crm_job_title');
define('TBL_EMAIL_COMMUNICATION', 'blzdsk_email_communication');
define('TBL_EMAIL_COMMUNICATION_FILE_MASTER', 'blzdsk_email_communication_files_master');


define('TBL_EMAIL_PROSPECT', 'blzdsk_email_prospect');
define('TBL_EMAIL_PROSPECT_FILE_MASTER', 'blzdsk_email_prospect_files_master');
define('EMAIL_PROSPECT_ATTACH_PATH', 'uploads/attach_email_prospect/');
define('EMAIL_EVENT_ATTACH_PATH', 'uploads/event_email_attach/');
define('CONTACT_FILE_ATTACH_PATH', 'uploads/contact_attach/');
define('TBL_CAMPAIGN_CONTACT', 'blzdsk_campaign_contact');
define('TBL_CONTACT_FILE_MASTER', 'blzdsk_contact_files_master');
define('TBL_EVENTS', 'blzdsk_events');
define('TBL_SCHDEULE_MEETING', 'blzdsk_schedule_meeting');
define('TBL_SCHEDULE_MEETING_ADDITIONAL_RECEIPENT', 'blzdsk_schedule_meeting_additional_receipent');
define('TBL_SCHEDULE_MEETING_MASTER', 'blzdsk_schedule_meeting_master');
define('TBL_MESSAGE_MASTER', 'blzdsk_message_master');
define('TBL_SCHEDULE_MEETING_RECEIPENT', 'blzdsk_schedule_meeting_receipents');
define('TBL_SCHEDULE_MEETING_FILE_MASTER', 'blzdsk_schedule_meeting_files_master');
define('TBL_NEWSLETTER_LISTS_MASTER', 'blzdsk_newsletter_lists_master');
define('TBL_NEWSLETTER_LISTS_CONTACT', 'blzdsk_newsletter_lists_contact');
define('SCHEDULE_MEETING_ATTACH_PATH', 'uploads/schedule_meeting_attach/');
 
 

/*
  Author : Niral Patel
  Desc   : Define Project management table
  Date   : 28/01/2016
 */
define('PROJECT_MASTER', 'project_master');
define('PROJECT_ASSIGN_MASTER', 'project_assign_trans');
define('MILESTONE_MASTER', 'milestone_master');
define('MILESTONE_ASSIGN_MASTER', 'milestone_team_tran');
define('MILESTONE_FILES', 'milestone_files');
define('MILESTONE_TASK_TRANS', 'milestone_task_trans');
define('PROJECT_TASK_MASTER', 'project_task_master');
define('PROJECT_TASK_FILES', 'project_task_files');
define('PROJECT_TASK_TEAM_TRAN', 'project_task_team_tran');
define('PROJECT_ACTIVITIES', 'project_activities');
define('PROJECT_TIMESHEETS', 'project_timesheets');
define('PROJECT_STATUS', 'project_status');
define('PROJECT_MESSAGE', 'project_message');
define('PROJECT_INCIDENTS', 'project_incidents');
define('PROJECT_INCIDENTS_TYPE', 'project_incidents_type');
define('PROJECT_INCIDENTS_FILES', 'project_incidents_files');
define('PROJECT_INVOICES', 'project_invoices');
define('PROJECT_INVOICES_FILES', 'project_invoices_files');
define('PROJECT_INVOICES_ITEMS', 'project_invoices_items');
define('PROJECT_INVOICES_PAYMENT', 'project_invoices_payment');
define('PROJECT_INVOICE_CLIENTS', 'project_invoice_clients');
define('PROJECT_INVOICE_SEND', 'project_invoice_send');






/*
  Author : Maulik Suthar
  Desc   : Define Cost Master table
  Date   : 28/01/2016
 */
define('COST_MASTER', 'cost_master');
define('COST_FILES', 'cost_files');
define('cost_upload_path', 'uploads/projectManagement/Costs');
define('PROJECT_TEAM_MASTER', 'project_team_master');
define('PROJECT_TEAM_MEMBERS', 'project_team_members');
define('CONFIG', 'config');
define('FILES_SALES_MASTER', 'files_sales_master');
define('ESTIMATE_PRODUCT', 'estimate_product');
define('ESTIMATE_FILES', 'estimate_files');
define('estimate_upload_path', 'uploads/estimate');
define('PRODUCT_TAX_MASTER', 'product_tax_master');
define('PROJECT_PM_ASSIGN', 'project_projectmanager_assign');
define('campaign_upload_path', 'uploads/sales_campaign');
define('COST_SUPPLIER_ASSIGNS', 'cost_supplier_assigns');


/*
  Author : Disha Patel
  Desc   : Define Constants Value
  Date   : 10/02/2016
 */
define('PRODUCT_GROUP_MASTER', 'product_group_master');
define('PRODUCT_GROUP_RELATION', 'product_group_relation');
define('BUDGET_COMPAIGN_MASTER', 'budget_campaign_master');

define('CAMPAING_ARCHIVE', 'campaign_archive');

/*
  Author : Sanket  Jayani
  Desc   : Define Constants Value
  Date   : 29/02/2016
 */
define('PROFILE_PIC_UPLOAD_PATH', 'uploads/profile_photo');
define('PROFILE_PIC_HEIGHT', '36');
define('PROFILE_PIC_WIDTH', '36');


/*
  Author : Nikunj ghelani
  Desc   : Define Constants Value
  Date   : 26/02/2016
 */
define('TICKET_MASTER', 'ticket_master');
define('TICKET_ACTIVITY', 'ticket_activity');
define('SUPPORT_TEAM', 'support_team_master');
define('SUPPORT_TEAM_MEMBER', 'support_team_members');
define('FILES_TICKET_MASTER', 'files_ticket_master');
define('ticket_upload_path', 'uploads/ticket_file');
define('LIVE_CHAT', 'livechat_master');
define('TICKET_CONTACT', 'ticket_contact');
define('TICKET_SUPPORT_USER', 'ticket_support_user');


/*
  Author : Brijesh Tiwari
  Desc   : Define Constants Value for Email client
  Date   : 09/03/2016
 */
define('EMAIL_CONFIG', 'email_config');
// for saving twitter count
define('TWITTER_MONTHLY_COUNT', 'twitter_monthly_count');


/*
  Author : Mehul Patel
  Desc   : Define Constants Value
  Date   : 08/03/2016
 */
define('COMPANY_PROFILE_PIC_UPLOAD_PATH', 'uploads/company_profile_image');
define('SUPPORT_TYPE', 'support_type');
define('SUPPORT_PRIORITY', 'support_priority');
define('SUPPORT_STATUS', 'support_status');
define('ESTIMATE_SETTINGS', 'estimate_settings');
define('KNOWLEDGEBASE_BASE_SETTINGS_TYPE', 'knowledge_base_settings_type');
define('SALES_TARGET_SETTINGS', 'sales_target_settings');

/*
  Author : ishani dave
  Desc   : Define Constants Value
  Date   : 15/03/2016
 */
define('KNOWLEDGEBASE_LIKE','knowledgebase_like');
/*
  Author : Nikunj ghelani
  Desc   : Define Constants Value
  Date   : 08/04/2016
 */
define('HELP', 'help');
define('SETUP', 'setup_master');


/*
  Author : Sanket jayani
  Desc   : Changing Email Template  Slug
  Date   : 18/04/2016
 * 
 */

define('TEMPLATE_SCHEDULE_MEETING', 'ST_7654');
define('TEMPLATE_UPDATE_SCHEDULE_MEETING', 'ST_1535');
define('TEMPLATE_CANCEL_SCHEDULE_MEETING', 'ST_1535');
define('TEMPLATE_MESSAGE', 'ST_8686');
define('TEMPLATE_EVENT_REMINDER', 'ST_6136');

//ishani dave
define('SUPPORT_TASK_MASTER', 'support_task_master');
/*
  Author : Maulik Suthar
  Desc   : email Client tables
  Date   : 17/05/2016
 * 
 */
define('EMAIL_CLIENT_MASTER', 'email_client_master');
define('EMAIL_CLIENT_ATTACHMENTS', 'email_client_attachments');

     /*
      @Author : Ishani Dave
      @Date   : 25/05/2016
     */

define('TEMPLATE_TO_DO_REMINDER', 'ST_4708');

//nikunj ghelani
define('BILL', 'billing_information');
define('TELE_MARKETING', 'telemarketing_master');


//nikunj ghelani for stripe payment key 
define('STRIPE_KEY_SK', 'sk_test_ELazZ12erwvhGaSFfpzaAAmB');
define('STRIPE_KEY_PK', 'pk_test_xN0QLDiaKoIQ1RNDwItzw8sk');
