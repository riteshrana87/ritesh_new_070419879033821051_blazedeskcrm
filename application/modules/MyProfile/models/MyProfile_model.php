<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@Author : Sanket Jayani
@Desc   : Model For Request Budget Module
@Input 	: 
@Output	: 
@Date   : 12/01/2016
*/
class MyProfile_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function getUserData($user_id)
    {
        $table = LOGIN;
        $match = "login_id = " . $user_id;
        $edit_record = $this->common_model->get_records ($table, '', '', '', $match);
        return  $edit_record[0]; 
    }
   
}