<?php
class Currencysettings_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : getUserDetails
	 @Input  : 
	 @Output :
	 @Date   : 01/02/2016
	 */
	public function getUserDetails(){
		
		$table	= LOGIN.' as l';
		$match 	= "";
		$fields = array("l.login_id, l.firstname, l.lastname, l.email, l.password, l.address, l.telephone1, l.telephone2, l.user_type, l.created_date, l.status");
		$data['information']  = $this->common_model->get_records($table,$fields,'','',$match);
		return $data['information']; 
	}
}