<?php
class Role_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
    /*
      @Author : Mehul Patel
      @Desc   : checkRoleAssigne
      @Input  : $id
      @Output :
      @Date   : 01/02/2016
     */	
	public function checkRoleAssigne($id = null){
		if($id){
			$this->db->select('lg.user_type,lg.status as userStatus,rm.status as roleStatus');
			$this->db->from(LOGIN.' AS lg' );
			$this->db->join(ROLE_MASTER.' AS rm','lg.user_type = rm.role_id', 'LEFT');
			$this->db->where('rm.role_id', $id);
			$this->db->where('lg.is_delete = 0'); 
			$query = $this->db->get();
			$result = $query->result_array();
			//$result1 = $this->db->last_query();
			//echo $result1; exit;
			return $result;
		}
	}
    /*
      @Author : Mehul Patel
      @Desc   : permsToRoleList
      @Input  :
      @Output :
      @Date   : 01/02/2016
     */
	public function permsToRoleList(){				
		$this->db->select('*');
		$this->db->from(ROLE_MASTER.' AS rm' );
		$this->db->join(AAUTH_PERMS_TO_ROLE.' AS authgroup','rm.role_id = authgroup.role_id', 'INNER');
		$this->db->group_by('rm.role_id'); 
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	/*
      @Author : Mehul Patel
      @Desc   : Get User Role 
      @Input  :
      @Output :
      @Date   : 01/02/2016
     */
	public function getRoles(){
		
		//$sql = "select * From blzdsk_role_master WHERE role_id NOT IN ( select role_id from blzdsk_aauth_perm_to_group GROUP BY role_id )";
		
		$this->db->select('*')->from(ROLE_MASTER);
		$this->db->where('`role_id` NOT IN (SELECT `role_id` FROM `blzdsk_aauth_perm_to_group`)', NULL, FALSE);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
}