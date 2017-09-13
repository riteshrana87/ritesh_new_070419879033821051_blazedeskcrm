<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Base Site URL
  |--------------------------------------------------------------------------
  |
  | URL to your CodeIgniter root. Typically this will be your base URL,
  | WITH a trailing slash:
  |
  |	http://example.com/
  |
  | WARNING: You MUST set this value!
  |
  | If it is not set, then CodeIgniter will try guess the protocol and path
  | your installation, but due to security concerns the hostname will be set
  | to $_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
  | The auto-detection mechanism exists only for convenience during
  | development and MUST NOT be used in production!
  |
  | If you need to allow multiple domains, remember that this file is still
  | a PHP script and you can easily do that on your own.
  |
 */
/**
 * User Module
 * [controller name][action] = array('respective function name for the Controller')
 */
$config['User']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'checkCRMUserCreateLimites', 'checkPMUserCreateLimites', 'checkSupportUserCreateLimites', 'assignModuleCount','updateUserCreationCount','getCountofCreatedUser','checkUserCounts','InsertTimeCheckUserCount','checkUserCreateLimits','updateTimecheckAvailability','InsertTimeCheckUserCounts','insertTimecheckAvailability');
$config['User']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['User']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail', 'checkCRMUserCreateLimites', 'checkPMUserCreateLimites', 'checkSupportUserCreateLimites','updateUserCreationCount','getCountofCreatedUser','checkUserCounts','InsertTimeCheckUserCount','checkUserCreateLimits','updateTimecheckAvailability','InsertTimeCheckUserCounts','insertTimecheckAvailability');
$config['User']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');
/*
  Opportunity Module
 */
$config['Opportunity']['add'] = array('navigation', 'insertdata', 'add', 'upload_file', 'getCompanyDataById', 'exportOpportunitys', 'importOpportunity', 'importOpportunitydata');
$config['Opportunity']['delete'] = array('navigation', 'deletedata', 'deleteImage', 'delete_contact_master', 'deletedataSalesOverview');
$config['Opportunity']['edit'] = array('navigation', 'edit', 'insertdata', 'convertWinAccount', 'convertLostAccount', 'editdata', 'upload_file', 'getCompanyDataById', 'exportOpportunitys', 'importOpportunity', 'importOpportunitydata', 'updateStatus');
$config['Opportunity']['view'] = array('navigation', 'index', 'pagination', 'loadAjaxOpportunityList', 'viewdata', 'exportToCSV', 'exportOpportunitys', 'importOpportunity', 'importOpportunitydata', 'validateContactUniqueness');
/*
  Prospect Module
 */
$config['Prospect']['add'] = array('insertdata', 'add');
$config['Prospect']['delete'] = array('deletedata');
$config['Prospect']['edit'] = array('edit', 'insertdata');
$config['Prospect']['view'] = array('index', 'pagination');
/*
  Lead Module
 */
$config['Lead']['add'] = array('index','insertdata', 'add', 'download', 'upload_file', 'exportLeads', 'importLeaddata', 'getCompanyDataById');
$config['Lead']['delete'] = array('index','deletedata', 'deleteImage', 'delete_contact_master', 'deletedataSalesOverview');
$config['Lead']['edit'] = array('index','edit', 'insertdata', 'convertOpportunity', 'editdata', 'converToQualified', 'upload_file', 'send_email_view', 'getLeadContact', 'getAllContactByCompany', 'getCompanyDataById');
$config['Lead']['view'] = array('index','pagination', 'viewdata', 'exportToCSV', 'importLeads','navigation');
/*
  Client Module
 */
$config['Client']['add'] = array('insertdata', 'add');
$config['Client']['delete'] = array('deletedata');
$config['Client']['edit'] = array('edit', 'insertdata', 'editdata');
$config['Client']['view'] = array('index', 'pagination', 'viewdata', 'view', 'exportToCSV');
/*
  Account Module
 */
$config['Account']['add'] = array('navigation', 'insertdata', 'add', 'download', 'upload_file', 'importAccount', 'importAccountdata', 'exportAccounts', 'getCompanyDataById', 'client_export_to_csv', 'saveContactData', 'changePrimaryStatus'.'insertLostdata');
$config['Account']['delete'] = array('navigation', 'deletedata', 'deleteImage', 'delete_contact_master', 'deletedataSalesOverview', 'ConvertToClient', 'deleteAccountAttach');
$config['Account']['edit'] = array('navigation', 'edit', 'insertdata', 'editdata', 'upload_file', 'send_email_view', 'sendProspectEmail', 'getCompanyDataById','insertLostdata','changePrimaryStatus');
$config['Account']['view'] = array('navigation', 'index', 'pagination', 'viewdata', 'view', 'exportToCSV', 'lostClient', 'viewLostClient', 'viewEstimateData', 'getCommunicationData', 'viewAllDealsData', 'viewSupportData', 'viewProjectsData', 'getAccountFile', 'getContactData');
/*
  Task Module
 */
$config['Task']['add'] = array('inserttask', 'add');
$config['Task']['delete'] = array('deletetask');
$config['Task']['edit'] = array('edittask', 'inserttask', 'completeTask');
$config['Task']['view'] = array('index', 'viewtask', 'reopen');
/*
  Campaigngroup Module
 */
$config['Campaigngroup']['add'] = array('insertdata', 'add_record', 'getCompanyByCampaign', 'getCompanyByOwner','CheckCampaignGroup');
$config['Campaigngroup']['delete'] = array('deletedata','CheckCampaignGroup');
$config['Campaigngroup']['edit'] = array('edit', 'updatedata', 'get_options_data', 'getCompanyByCampaign', 'getCompanyByOwner','CheckCampaignGroup');
$config['Campaigngroup']['view'] = array('index', 'get_options_data', 'view_page','CheckCampaignGroup');
/*
  Contact Module
 */

$config['Contact']['add'] = array('insertdata', 'addrecord', 'add_job_title', 'insert_job_title', 'importContact', 'exportContact', 'importContactdata', 'addNote', 'insertNote', 'inserttask', 'sendProspectEmail', 'insertContactToCampaign', 'createDuplicate', 'merge_contact', 'add_contact_file', 'insertEvent', 'sendEventEmail', 'viewLeadFile', 'addLeadFile', 'AddCases', 'insertCases', 'download_incident_file', 'addUpdateMeeting', 'createDuplicateOpportunity', 'viewMergeOpportunity', 'mergeOpportunity');
$config['Contact']['delete'] = array('deletedata', 'delete_note', 'delete_job_title', 'delete_task', 'delete_campaign', 'delete_contact_attach', 'delete_event', 'delete_incident_file', 'deleteCases', 'delete_meeting', 'deleteLeadCampaign', 'deleteProspectCampaign');
$config['Contact']['edit'] = array('edit', 'update_event', 'edit_job_title', 'updatedata', 'updateNote', 'updateNoteRecord', 'edittask', 'updateEventRecord', 'editCases', 'uploadFromEditor');
$config['Contact']['view'] = array('index', 'view', 'exportToCSV', 'viewNote', 'viewNoteData', 'viewTaskData', 'viewDealsData', 'viewCampaignData', 'getContactFile', 'viewEvents', 'send_email_view', 'getProspectDataById', 'getEmailTemplateDataById', 'upload_file', 'view_add_campaign', 'getContactEvents', 'viewMergeContact', 'view_event', 'view_add_file', 'getContactCases', 'viewContactData', 'getLeadFile', 'event_send_email', 'viewCasesRecord', 'jobTitle', 'getContactCommunication', 'viewCommunication', 'scheduleMeeting', 'getContactMeeting', 'update_meeting', 'getCompanyAddressById', 'view_meeting', 'getCompany_location','navigation');

// Company
$config['CrmCompany']['add'] = array('insertData', 'add', 'validateCompanyUniqueness');
$config['CrmCompany']['delete'] = array('deleteData');
$config['CrmCompany']['edit'] = array('edittask', 'insertData', 'edit', 'updateData');
$config['CrmCompany']['view'] = array('index', 'view');

/*
 * Maulik Suthar
  Costs Module
 */
$config['Costs']['add'] = array('add', 'saveCostData', 'download', 'upload_file');
$config['Costs']['edit'] = array('edit', 'saveCostData', 'get_options_data', 'upload_file');
$config['Costs']['delete'] = array('delete', 'deleteImage');
$config['Costs']['view'] = array('index', 'listCosts', 'listCostsData', 'view', 'dragDropImgSave');
$config['Filemanager']['view'] = array('index', 'loadAjaxView', 'upload', 'delete', 'deleteImage', 'makeDir');
$config['Filemanager']['add'] = array('upload', 'makeDir');
$config['Filemanager']['delete'] = array('delete', 'deleteImage');

$config['TeamMembers']['add'] = array('addTeam', 'saveCostData', 'download', 'editProjectTeam');
$config['TeamMembers']['edit'] = array('edit', 'SaveTeamData', 'get_options_data', 'SaveTeamMemberData');
$config['TeamMembers']['delete'] = array('removeTeamMembers', 'removeTeam');
$config['TeamMembers']['view'] = array('index', 'getTeamListbyId', 'addTeamMembers', 'assignProjectManager', 'getTaskByUser', 'teamNameValidate');


$config['SalesOverview']['view'] = array('index', 'taskAjaxList', 'salesAjaxList', 'grantview', 'update_status', 'view_meeting', 'view_message', 'view_email', 'dashboardWidgetsOrder');
$config['SalesOverview']['add'] = array('sendMessageEmail', 'insertMessage');

$config['SalesOverviewTest']['view'] = array('index', 'taskAjaxList', 'salesAjaxList', 'grantview');

/*
 * Niral Patel
  Project management Module
 */
$config['Projectmanagement']['add'] = array('add_record', 'insertdata', 'delete_file', 'download');
$config['Projectmanagement']['edit'] = array('edit_record', 'insertdata', 'delete_file', 'download');
$config['Projectmanagement']['delete'] = array('delete_record');
$config['Projectmanagement']['view'] = array('index', 'project', 'select_project', 'view_record', 'finishProject');
/*
 * Niral Patel
  Project management dashboard
 */

$config['Projectdashboard']['view'] = array('index', 'select_project', 'check_project', 'dashboardWidgetsOrder', 'getFilesList', 'getMonthlySalesData');
/*
 * Niral Patel
  Project Home Module
 */
/* $config['Home']['add']    = array('add_record');
  $config['Home']['edit']   = array('edit_record');
  $config['Home']['delete'] = array('delete_record'); */
$config['Home']['view'] = array('index', 'changeview', 'grantview', 'get_home_header', 'get_home_activity');
$config['Errors']['view'] = array('index');

/*
 * Niral Patel
  Project task Module
 */
$config['ProjectTask']['add'] = array('add_record', 'insertdata', 'add_subtask', 'delete_file', 'update_status', 'file_upload');
$config['ProjectTask']['edit'] = array('edit_record', 'insertdata', 'edit_subtask', 'delete_file', 'download', 'update_status', 'file_upload');
$config['ProjectTask']['delete'] = array('delete_record');
$config['ProjectTask']['view'] = array('index', 'peoject', 'update_status', 'view_record', 'changeview', 'grantview', 'get_home_header', 'get_home_activity', 'get_today_task', 'dashboardWidgetsOrder', 'dashboardWidgetsInnerOrder');

/*
 * Niral Patel
  Project milistone Module
 */
$config['Timesheets']['add'] = array('add_record', 'insertdata');
$config['Timesheets']['edit'] = array('edit_record', 'insertdata');
$config['Timesheets']['delete'] = array('delete_record');
$config['Timesheets']['view'] = array('index', 'view_record', 'getEstimateHrs');
/* Project incidents */
$config['ProjectIncidents']['add'] = array('add_record', 'insertdata', 'delete_file', 'download', 'file_upload');
$config['ProjectIncidents']['edit'] = array('edit_record', 'insertdata', 'delete_file', 'download', 'file_upload');
$config['ProjectIncidents']['delete'] = array('delete_record');
$config['ProjectIncidents']['view'] = array('index', 'view_record');
/* project invoice */

$config['Invoices']['add'] = array('add_record', 'insertdata', 'delete_file', 'download', 'file_upload', 'show_client_related_recipients', 'send_invoice', 'DownloadPDF');
$config['Invoices']['edit'] = array('edit_record', 'insertdata', 'delete_file', 'download', 'file_upload', 'show_client_related_recipients', 'send_invoice', 'DownloadPDF');
$config['Invoices']['delete'] = array('delete_record');
$config['Invoices']['view'] = array('index', 'view_record', 'DownloadPDF');

//$config['InvoicesCron']['view'] = array('index', 'send_invoice','DownloadPDF');
//$config['ProjectCron']['view'] = array('index', 'send_notification_pm');

/*
 * Niral Patel
  Project milistone Module
 */

$config['Milestone']['add'] = array('add_record', 'insertdata', 'delete_file', 'download', 'file_upload');
$config['Milestone']['edit'] = array('edit_record', 'insertdata', 'delete_file', 'download', 'file_upload');
$config['Milestone']['delete'] = array('delete_record');
$config['Milestone']['view'] = array('index', 'view_record');

/*
 * Niral Patel
  Project Project Status Module
 */
$config['ProjectStatus']['add'] = array('add_record', 'insertdata', 'update_order', 'get_font_awesome_icon');
$config['ProjectStatus']['edit'] = array('edit_record', 'insertdata', 'update_order', 'get_font_awesome_icon');
$config['ProjectStatus']['delete'] = array('delete_record');
$config['ProjectStatus']['view'] = array('index', 'view_record', 'update_order');

$config['ProjectIncidentsType']['add'] = array('add_record', 'insertdata');
$config['ProjectIncidentsType']['edit'] = array('edit_record', 'insertdata');
$config['ProjectIncidentsType']['delete'] = array('delete_record');
$config['ProjectIncidentsType']['view'] = array('index', 'view_record');

/*
 * Niral Patel
  Project activity Module
 */
$config['Activities']['view'] = array('index');
/*
  /*
 * Maulik Suthar
  Project Report Module
 */
$config['ProjectReport']['view'] = array('index');
/*
 * Niral Patel
  Project activity Module
 */
$config['Messages']['add'] = array('insert_message');
$config['Messages']['edit'] = array('insert_message');
$config['Messages']['delete'] = array('delete_record');
$config['Messages']['view'] = array('index', 'get_message', 'insert_message');

// Rolemaster Module
$config['Rolemaster']['add'] = array('insertdata', 'add', 'addPermission', 'insertPerms', 'assignPermission', 'addModule', 'insertAssginPerms', 'insertModule', 'checkRoleStatus', 'checkRoleAssignedToUser', 'permissionTab','getPurchasedModulesCounts','updateRolebasedUserCreationCount','assignModuleCount','editTimeCheckPurchasedUserLimit','updateTimeCheckUserAvailbility');
$config['Rolemaster']['delete'] = array('deletedata', 'deletePerms', 'deleteAssignperms', 'deleteModuleData', 'permissionTab','editTimeCheckPurchasedUserLimit','updateTimeCheckUserAvailbility');
$config['Rolemaster']['edit'] = array('edit', 'updatedata', 'editPerms', 'updatePerms', 'editPermission', 'editModule', 'updateModule', 'insertAssginPerms', 'permissionTab','getPurchasedModulesCounts','updateRolebasedUserCreationCount','assignModuleCount','editTimeCheckPurchasedUserLimit','updateTimeCheckUserAvailbility');
$config['Rolemaster']['view'] = array('index', 'role_list', 'view_perms_to_role_list', 'checkRoleStatus', 'checkRoleAssignedToUser', 'permissionTab');

/* RequestBudget */

$config['RequestBudget']['add'] = array('create', 'index', 'page', 'download');
$config['RequestBudget']['edit'] = array('update', 'get_request_data', 'edit_record', 'download');
$config['RequestBudget']['delete'] = array('delete_request', 'download', 'deleteImage');
$config['RequestBudget']['view'] = array('index', 'page', 'create_campaign', 'getCampaignDataById', 'get_request_data', 'display', 'upload_file', 'download', 'show_Campaign_product', 'show_Campaign_responsible');

/* CampaignReport */

$config['CampaignReport']['view'] = array('index', 'page', 'search', 'generatePDF', 'generateCSV', 'facebook_count', 'get_twitter_follower_count', 'setCampaignId', 'download', 'checkFBaccessright', 'FBcallback', 'getFbLikes', 'linkedinFollowerCnt', 'checkYoutubecallback', 'getYoutubeLikes', 'youTubeCallback');


/* MyProfile */

/* Commented By ssanket on 25/04 for my profile acesing everywhere
  $config['MyProfile']['add'] = array();
  $config['MyProfile']['edit'] = array('updateProfile', 'updatePassword');
  $config['MyProfile']['delete'] = array();
  $config['MyProfile']['view'] = array('index', 'ChangePassword');
 */

/* Dashboard */
// $config['Dashboard']['view'] = array('index', 'logout', 'sendmail', 'dashboardWidgetsOrder', 'worth_opportunities', 'salestarget');


//Marketingcampaign
$config['Marketingcampaign']['add'] = array('index', 'insertdata', 'send_email', 'upload_file', 'add_record', 'download', 'ShowClientRelatedToCompany');
$config['Marketingcampaign']['edit'] = array('index', 'edit', 'get_options_data', 'updatedata', 'send_email', 'upload_file', 'add_record', 'download', 'ShowClientRelatedToCompany');
$config['Marketingcampaign']['delete'] = array('index', 'deletedata', 'send_email', 'upload_file', 'download', 'deleteImage', 'ShowClientRelatedToCompany');
$config['Marketingcampaign']['view'] = array('index', 'send_email', 'upload_file', 'get_twitter_follower_count', 'facebook_count', 'view_page', 'grantview', 'viewTask', 'getWebsiteVisitorCount', 'ShowClientRelatedToCompany');

//ManageCampaigns moduls
$config['ManageCampaigns']['add'] = array('index', 'insertdata', 'send_email', 'upload_file', 'add_record');
$config['ManageCampaigns']['edit'] = array('index', 'edit', 'get_options_data', 'updatedata', 'send_email', 'upload_file', 'add_record');
$config['ManageCampaigns']['delete'] = array('index', 'deletedata', 'send_email', 'upload_file');
$config['ManageCampaigns']['view'] = array('index', 'send_email', 'upload_file');

//Campaignarchive
$config['Campaignarchive']['add'] = array('index', 'insertdata', 'send_email', 'upload_file', 'add_record');
$config['Campaignarchive']['edit'] = array('index', 'edit', 'get_options_data', 'updatedata', 'send_email', 'upload_file', 'add_record');
$config['Campaignarchive']['delete'] = array('index', 'deletedata', 'send_email', 'upload_file');
$config['Campaignarchive']['view'] = array('index', 'send_email', 'upload_file', 'campaign_archive');


//ArchiveCampaign
$config['ArchiveCampaign']['add'] = array('index', 'insertdata', 'send_email', 'upload_file', 'add_record', 'download');
$config['ArchiveCampaign']['edit'] = array('index', 'edit', 'get_options_data', 'updatedata', 'send_email', 'upload_file', 'add_record', 'download');
$config['ArchiveCampaign']['delete'] = array('index', 'deletedata', 'send_email', 'upload_file', 'download', 'deleteImage');
$config['ArchiveCampaign']['view'] = array('index', 'send_email', 'upload_file', 'get_twitter_follower_count', 'facebook_count', 'view_page', 'grantview', 'viewTask', 'getWebsiteVisitorCount');



/*
 * Estimates Module
 */
$config['Signature']['view'] = array('add');
$config['Estimates']['add'] = array('insertdata', 'add', 'insertTemplate', 'showTemplateProduct', 'upload_file', 'download', 'showPrdTempSelectBox', 'addNewAutograph', 'getGroupDataByGroupId', 'checkProductNameUnique', 'prdAmntIncExcCalculation');
$config['Estimates']['delete'] = array('deletedata', 'deleteImage');
$config['Estimates']['edit'] = array('edit', 'insertdata', 'ProductSortOrderUpdate', 'GeneratePrintPDF', 'StoreWidgets', 'SendEstimate', 'download', 'DownloadPDF', 'view', 'showPrdTempSelectBox', 'addNewAutograph', 'getGroupDataByGroupId', 'preview', 'checkProductNameUnique', 'prdAmntIncExcCalculation');
$config['Estimates']['view'] = array('index', 'pagination', 'getProductBox', 'getProductById', 'productCalculationTaxesQty', 'productCalculationSubTotal', 'getProductsListByGroupId', 'appendTextParagraph', 'getProductsListByExistingGroupId', 'ShowClientRelatedToCompany', 'view', 'estNotifyForDueDate');

/*
  EstimatesClient
 */
$config['EstimatesClient']['add'] = array('insertdata', 'add', 'insertTemplate', 'showTemplateProduct', 'add_autograph', 'welcome', 'error');
$config['EstimatesClient']['delete'] = array('deletedata');
$config['EstimatesClient']['edit'] = array('edit', 'insertdata', 'ProductSortOrderUpdate', 'GeneratePDF', 'StoreWidgets');
$config['EstimatesClient']['view'] = array('ClientView');


/*
  Deal Module
 */
$config['Deal']['add'] = array('insertdata');
$config['Deal']['delete'] = array('deletedata');
$config['Deal']['edit'] = array('edit', 'insertdata');
$config['Deal']['view'] = array('index', 'pagination');

// Company
$config['Company']['add'] = array('insertData', 'add');
$config['Company']['delete'] = array('deleteData');
$config['Company']['edit'] = array('edittask', 'insertData', 'edit', 'updateData');
$config['Company']['view'] = array('index', 'view');

/*
 * Mehul Patel
  Email Template Module
 */
$config['Emailtemplate']['add'] = array('insertdata', 'add', 'testTemplate', 'sendmail', 'systemTemplateList');
$config['Emailtemplate']['edit'] = array('deletedata', 'testTemplate', 'sendmail');
$config['Emailtemplate']['delete'] = array('edit', 'updatedata', 'testTemplate', 'sendmail');
$config['Emailtemplate']['view'] = array('index', 'templatelist', 'viewEmailTemplate', 'testTemplate', 'sendmail');

/*
  SalesOverview Module
 */
//$config['SalesOverview']['add'] = array('insertdata','add','add_opportunity');
//$config['SalesOverview']['delete'] = array('deletedata');
//$config['SalesOverview']['edit'] = array('edittask','insertdata');
//$config['SalesOverview']['view'] = array('index');

/*
  OpportunityModal Module
 */
$config['OpportunityModal']['add'] = array('insertdata', 'add');
$config['OpportunityModal']['delete'] = array('deletedata');
$config['OpportunityModal']['edit'] = array('edittask', 'insertdata');
$config['OpportunityModal']['view'] = array('index');
/*

Settings Module
 */
$config['Settings']['add'] = array('billing_information','checkout_support','checkout_pm','count_inactive_user','removesupuser','addsupuser','removepmuser','addpmuser','removecrmuser','addcrmuser','subscription','checkout_crm', 'insertdata', 'add');
$config['Settings']['delete'] = array('billing_information','checkout_support','checkout_pm','count_inactive_user','removesupuser','addsupuser','removepmuser','addpmuser','removecrmuser','addcrmuser','subscription','checkout_crm', 'deletedata', 'removeTaxItem');
$config['Settings']['edit'] = array('billing_information','checkout_support','checkout_pm','count_inactive_user','removesupuser','addsupuser','removepmuser','addpmuser','removecrmuser','addcrmuser','subscription','checkout_crm', 'edit', 'updatedata', 'updateGeneralSettings', 'updateSocialMediaSettings', 'updateGoogleAnalyticsSettings', 'updateTaxSettings', 'updatemailNotification','newsletter_configuration');
$config['Settings']['view'] = array('billing_information','checkout_support','checkout_pm','count_inactive_user','removesupuser','addsupuser','removepmuser','addpmuser','removecrmuser','addcrmuser','subscription','checkout_crm', 'index', 'settingslist', 'getSettingsData', 'emailSettings', 'userSettings');


/*
 * Disha Patel
  Product Module
 */

$config['Product']['add'] = array('insertdata', 'AddEditProduct', 'importProduct', 'importProductdata', 'exportProduct');
$config['Product']['delete'] = array('deletedata');
$config['Product']['edit'] = array('edit', 'updatedata', 'AddEditProduct', 'getCurWiseSPUandPPU', 'getOldCurrency', 'getPPUandSPUById');
$config['Product']['view'] = array('index', 'display');

/*
 * Disha Patel
  Product Module
 */
$config['ProductGroup']['add'] = array('insertdata', 'AddEditProductGroup', 'GetTaxPercent');
$config['ProductGroup']['delete'] = array('deletedata');
$config['ProductGroup']['edit'] = array('edit', 'updatedata', 'AddEditProductGroup', 'GetTaxPercent');
$config['ProductGroup']['view'] = array('index', 'display');
//17/02/2016
$config['Currencysettings']['add'] = array('insertdata', 'add');
$config['Currencysettings']['delete'] = array('deletedata');
$config['Currencysettings']['edit'] = array('edit', 'updatedata');
$config['Currencysettings']['view'] = array('index', 'currencylist', 'view');


/**
  Nikunj Ghelani
 * product:support
 */
$config['Support']['add'] = array('insertdata', 'add', 'saveTicketData', 'taskAjaxList');
$config['Support']['delete'] = array('deletedata', 'taskAjaxList');
$config['Support']['edit'] = array('edit', 'updatedata', 'taskAjaxList');
$config['Support']['view'] = array('index', 'userlist', 'view', 'taskAjaxList', 'dashboardWidgetsOrder', 'grantview');

/* Nikunj Ghelani
  product:ticket
 */
$config['Ticket']['add'] = array('insertdata', 'add', 'saveTicketData', 'upload_file', 'download', 'getTeam');
$config['Ticket']['delete'] = array('deletedata', 'download');
$config['Ticket']['edit'] = array('edit', 'updatedata', 'edit_record', 'updatedata', 'update_status', 'download');
$config['Ticket']['view'] = array('index', 'userlist', 'view', 'salesPaginationData', 'view_record', 'download');

$config['Team']['add'] = array('insertdata', 'add_team', 'add');
$config['Team']['delete'] = array('deletedata');
$config['Team']['edit'] = array('edit', 'insertdata', 'convert_account', 'editdata');
$config['Team']['view'] = array('index', 'pagination', 'loadAjaxTeamList', 'viewdata', 'exportToCSV');
// mehul patel
$config['Email']['add'] = array('insertdata', 'registration');
$config['Email']['delete'] = array('deletedata');
$config['Email']['edit'] = array('edit', 'updatedata');
$config['Email']['view'] = array('index', 'userlist', 'view');



/**
  Ishani Dave
 * product:Knowledgebase
 */
$config['KnowledgeBase']['add'] = array('AddMainCategory', 'saveMainCategory', 'AddSubCategory', 'saveSubCategory', 'AddArticle', 'saveArticle', 'upload_file', 'deleteImage', 'download', 'addlike', 'getSubCategories', 'getProducts', 'checkVisible');
$config['KnowledgeBase']['delete'] = array('deletedata', 'deletedatasub', 'deletesub', 'deletearticle', 'deleteImage', 'download');
$config['KnowledgeBase']['edit'] = array('edit', 'updatedata', 'editsubcat', 'updatesubcat', 'editarticle', 'updatearticle', 'deleteImage', 'download', 'dislike', 'updatelike');
$config['KnowledgeBase']['view'] = array('index', 'userlist', 'view', 'ListofMainCategory', 'ListofSubCategory', 'viewdata', 'ListofArticle', 'viewArticle', 'deleteImage', 'download', 'ListofGeneralCategory');

// Mehul Patel Support Settings 

$config['SupportSettings']['add'] = array('insertdata', 'add', 'insertdataType', 'insertdataStatus', 'addType', 'addStatus', 'taskAjaxList', 'typeAjaxList', 'statusAjaxList', 'get_font_awesome_icon');
$config['SupportSettings']['delete'] = array('deletedataPriority', 'deletedataType', 'deletedataStatus', 'taskAjaxList', 'typeAjaxList', 'statusAjaxList');
$config['SupportSettings']['edit'] = array('edit', 'updatedata', 'editType', 'editStatus', 'updatedataType', 'updatedataStatus', 'taskAjaxList', 'typeAjaxList', 'statusAjaxList', 'get_font_awesome_icon');
$config['SupportSettings']['view'] = array('index', 'settingslist', 'view', 'viewType', 'viewStatus', 'taskAjaxList', 'typeAjaxList', 'statusAjaxList');


// Nikunj Ghelani
$config['SupportTeam']['add'] = array('SaveTeamData','addTeam', 'saveCostData', 'addTeamMembers', 'download', 'editSupportTeam', 'teamNameValidate');
$config['SupportTeam']['edit'] = array('edit', 'SaveTeamData', 'addTeamMembers', 'get_options_data', 'SaveTeamMemberData', 'teamNameValidate');
$config['SupportTeam']['delete'] = array('SaveTeamData','removeTeamMembers', 'removeTeam', 'addTeamMembers', 'teamNameValidate');
$config['SupportTeam']['view'] = array('SaveTeamData','index', 'getTeamListbyId', 'addTeamMembers', 'assignProjectManager', 'getTaskByUser', 'teamNameValidate');

//$config['Mail']['add'] = array('ComposeMail', 'saveConcept', 'forwardEmail', 'uploadFromEditor');
//$config['Mail']['delete'] = array('deletedata');
//$config['Mail']['edit'] = array('getUnreadMailCount', 'mailconfig');
//$config['Mail']['view'] = array('index', 'mailDataExport', 'getHeader', 'getEmails', 'moveMessage', 'markasFlagged');

// Mehul Patel EstimateSettings
$config['EstimateSettings']['add'] = array('index', 'insertdata', 'add');
$config['EstimateSettings']['edit'] = array('index', 'edit', 'updatedata');
$config['EstimateSettings']['delete'] = array('index', 'deletedata');
$config['EstimateSettings']['view'] = array('index', 'estimateSettingsList', 'view');


// Nikunj ghelani livechat
//$config['LiveChat']['add'] = array('chat', 'chatM', 'AddSubCategory', 'saveSubCategory', 'AddArticle', 'saveArticle', 'upload_file', 'deleteImage', 'download');
//$config['LiveChat']['delete'] = array('chatM', 'deletedata', 'deletedatasub', 'deletearticle', 'deleteImage', 'download');
//$config['LiveChat']['edit'] = array('chatM', 'edit', 'updatedata', 'editsubcat', 'updatesubcat', 'editarticle', 'updatearticle', 'deleteImage', 'download');
//$config['LiveChat']['view'] = array('chatM', 'index', 'userlist', 'view', 'ListofMainCategory', 'ListofSubCategory', 'viewdata', 'ListofArticle', 'viewarticle', 'deleteImage', 'download');
//Mehul Patel  KNOWLEDGEBASE_BASE_SETTINGS_TYPE
$config['KnowledgeBaseSettings']['add'] = array('insertdata', 'add');
$config['KnowledgeBaseSettings']['edit'] = array('edit', 'updatedata');
$config['KnowledgeBaseSettings']['delete'] = array('deletedata');
$config['KnowledgeBaseSettings']['view'] = array('index', 'estimateSettingsList', 'view');
/**
  Ishani Dave
 * product: SupportReport
 */
$config['SupportReport']['view'] = array('index', 'page', 'search', 'generatePDF', 'generateCSV', 'test', 'data', 'SupportReport');

//$config['Chart']['view'] = array('index');
// Ghelani Nikunj
//for help module
/*
  $config['Help']['add'] = array('insertdata', 'registration','saveHelpData','add');
  $config['Help']['delete'] = array('deletedata','saveHelpData','add');
  $config['Help']['edit'] = array('getUnreadMailCount', 'mailconfig','saveHelpData','add');
  $config['Help']['view'] = array('index', 'mailDataExport', 'getHeader','saveHelpData','add');
 */
//Mehul Patel : SalesTargetSettings  31/03/2016
$config['SalesTargetSettings']['add'] = array('index', 'insertdata', 'add');
$config['SalesTargetSettings']['edit'] = array('index', 'edit', 'updatedata');
$config['SalesTargetSettings']['delete'] = array('index', 'deletedata');
$config['SalesTargetSettings']['view'] = array('index', 'estimateSettingsList', 'view');

//Sanket Jayani For Cases Type
$config['CasesType']['add'] = array('add_record', 'insertdata');
$config['CasesType']['edit'] = array('edit_record', 'insertdata');
$config['CasesType']['delete'] = array('delete_record');
$config['CasesType']['view'] = array('index', 'view_record');

//Sanket Jayani For Message
$config['Message']['add'] = array('add_record', 'insertdata');
$config['Message']['edit'] = array('edit_record', 'insertdata');
$config['Message']['delete'] = array('delete_record');
$config['Message']['view'] = array('index', 'view_record', 'getNotifiedMessage');

//Sanket Jayani For Meeting
$config['Meeting']['add'] = array('add_record', 'insertdata');
$config['Meeting']['edit'] = array('edit_record', 'insertdata', 'update_meeting', 'addUpdateMeeting');
$config['Meeting']['delete'] = array('delete_record');
$config['Meeting']['view'] = array('index', 'view_record', 'view_meeting');


//Maulik Suthar for Suppier
$config['Supplier']['add'] = array('add', 'savedata');
$config['Supplier']['edit'] = array('edit', 'savedata');
$config['Supplier']['delete'] = array('deletedata');
$config['Supplier']['view'] = array('index', 'view');

/* ishani dave
  product:support contact
 */
$config['SupportContact']['add'] = array('insertdata', 'addrecord', 'add_job_title', 'insert_job_title', 'importContact', 'exportContact', 'importContactdata', 'addNote', 'insertNote', 'inserttask', 'sendProspectEmail', 'insertContactToCampaign', 'createDuplicate', 'merge_contact', 'add_contact_file', 'insertEvent', 'sendEventEmail', 'viewLeadFile', 'addLeadFile', 'AddCases', 'insertCases', 'download_incident_file', 'addUpdateMeeting', 'createDuplicateOpportunity', 'viewMergeOpportunity', 'mergeOpportunity', 'addTask','navigation');
$config['SupportContact']['delete'] = array('deletedata', 'delete_note', 'delete_job_title', 'delete_task', 'delete_campaign', 'delete_contact_attach', 'delete_event', 'delete_incident_file', 'deleteCases', 'delete_meeting', 'deleteLeadCampaign', 'deleteProspectCampaign','navigation');
$config['SupportContact']['edit'] = array('edit', 'update_event', 'edit_job_title', 'updatedata', 'updateNote', 'updateNoteRecord', 'edittask', 'updateEventRecord', 'editCases', 'uploadFromEditor','navigation');
$config['SupportContact']['view'] = array('index', 'view', 'exportToCSV', 'viewNote', 'viewNoteData', 'viewTaskData', 'viewDealsData', 'viewCampaignData', 'getContactFile', 'viewEvents', 'send_email_view', 'getProspectDataById', 'getEmailTemplateDataById', 'upload_file', 'view_add_campaign', 'getContactEvents', 'viewMergeContact', 'view_event', 'view_add_file', 'getContactCases', 'viewContactData', 'getLeadFile', 'event_send_email', 'viewCasesRecord', 'jobTitle', 'getContactCommunication', 'viewCommunication', 'scheduleMeeting', 'getContactMeeting', 'update_meeting', 'getCompanyAddressById', 'view_meeting', 'getCompany_location','navigation');

/* Niral Patel
  CRM cron
 */
//$config['CRMCron']['view'] = array('index', 'to_do', 'estNotifyForDueDate');


/*
 * ishani dave
  SupportTask Module
 */
$config['SupportTask']['add'] = array('inserttask', 'add');
$config['SupportTask']['delete'] = array('deletetask');
$config['SupportTask']['edit'] = array('edittask', 'inserttask', 'completeTask');
$config['SupportTask']['view'] = array('index', 'viewtask', 'reopen');


$config['GetApiData']['add'] = array('CompanyReport','GetCompanyReport','CompanyInquiry','companyReportTest');
$config['GetApiData']['delete'] = array('');
$config['GetApiData']['edit'] = array('');
$config['GetApiData']['view'] = array('index');

/* Added By Sanket For Newsletter Configuration*/
$config['Newsletter']['add'] = array('newsletter_configuration');
$config['Newsletter']['view'] = array('index');
$config['Newsletter']['edit'] = array('newsletter_configuration','mailchimp_make_subscribe','mailchimp_make_unsubscribe','cmonitor_make_subscribe','cmonitor_make_unsubscribe','moosend_make_subscribe','moosend_make_unsubscribe');
$config['Newsletter']['delete'] = array('delete_from_mailchimp','delete_from_cmonitor','delete_contact_getResponse','moosend_delete_contact');

/*Added By Nikunj ghelani for TeleMarketing*/
$config['TeleMarketing']['add'] = array('importContactdata','importContact','exportTeleMarketing','insertdata', 'add', 'saveTelemarketingData', 'upload_file', 'download', 'getTeam');
$config['TeleMarketing']['delete'] = array('deletedata', 'download');
$config['TeleMarketing']['edit'] = array('edit', 'updateTelemarketingData', 'edit_record', 'updatedata', 'update_status', 'download');
$config['TeleMarketing']['view'] = array('index', 'userlist', 'view', 'salesPaginationData', 'view_record', 'download');

