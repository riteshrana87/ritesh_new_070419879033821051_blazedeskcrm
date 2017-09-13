<?php

class TeamMembers_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getTeamMemberList($team_id) {
        //L.login_id=TM.team_lead_id AND 
        $this->db->select('PTM.member_id,TM.team_id,TM.team_name,concat(L.firstname," ",L.lastname) as full_name,L.email,RM.role_name,L.telephone1,L.telephone2,L.email,TM.team_lead_id,L.profile_photo', false);
        $this->db->from(SUPPORT_TEAM . ' as TM');
        $this->db->join(SUPPORT_TEAM_MEMBER . ' as PTM', 'PTM.team_id=TM.team_id', 'left');
        $this->db->join(LOGIN . ' as L', 'L.login_id=PTM.member_id', 'left');
        $this->db->join(ROLE_MASTER . ' as RM', 'RM.role_id=L.user_type', 'left');
        $this->db->where('TM.team_id', $team_id);
        $this->db->where('TM.is_delete', 0, false);
        $this->db->where('PTM.is_delete', 0);
		 $this->db->where('L.is_delete', 0);
        $this->db->group_by('PTM.member_id');
        return $this->db->get()->result_array();
    }

    public function getTeamLeaderbyId($team_id) {
        //L.login_id=TM.team_lead_id AND 
        $this->db->select('TM.team_lead_id as member_id,TM.team_id,TM.team_name,concat(L.firstname," ",L.lastname) as full_name,L.email,RM.role_name,L.telephone1,L.telephone2,L.email,TM.team_lead_id,L.profile_photo', false);
        $this->db->from(SUPPORT_TEAM . ' as TM');
        $this->db->join(LOGIN . ' as L', 'L.login_id=TM.team_lead_id', 'left');
       
        $this->db->join(ROLE_MASTER . ' as RM', 'RM.role_id=L.user_type', 'left');
//        $this->db->join(aauth_perm_to_group . ' as APG', 'APG.role_id=RM.role_id', 'left');
//        $this->db->join(MODULE_MASTER . ' as MM', 'APG.role_id=MM.role_id', 'left');
     
        $this->db->where('TM.is_delete', 0);
        $this->db->where('L.is_delete', 0);

        $this->db->where('TM.team_id', $team_id);
//        $this->db->where('RM.role_name!=', 'Team_Leader');
//echo $this->db->last_query();exit;
        return $this->db->get()->result_array();
    }
	function validateTeamUnique($team_name, $id) {
        $this->db->select('TM.team_id,TM.team_name');
        $this->db->from(SUPPORT_TEAM . ' as TM');
        $this->db->where('TM.team_name', $team_name);
        if ($id > 0) {
            $this->db->where('TM.team_id !=', $id);
        }
        $this->db->where('TM.is_delete', '0');
        return $query = $this->db->get()->result_array();
    }
    public function getUnsortedMembers() {
        //L.login_id=TM.team_lead_id AND 
        $this->db->select('PTM.member_id,concat(L.firstname," ",L.lastname) as full_name,L.email,RM.role_name,L.telephone1,L.telephone2,L.email,L.profile_photo', false);
        $this->db->from(SUPPORT_TEAM_MEMBER . ' as PTM');
        $this->db->join(LOGIN . ' as L', 'L.login_id=PTM.member_id', 'left');
        $this->db->join(ROLE_MASTER . ' as RM', 'RM.role_id=L.user_type', 'left');
        $this->db->where('PTM.team_id', 0);
       $this->db->where('PTM.is_delete', 0);
        $this->db->group_by('PTM.member_id');
       // $this->db->where('RM.role_name!=', 'Team Leader');
		
        return $this->db->get()->result_array();
    }

    function getTeamList($project_id = null) {
        $this->db->select('TM.team_id,TM.team_name');
        $this->db->from(SUPPORT_TEAM . ' as TM');
        $this->db->where('TM.is_delete', '0');

        return $query = $this->db->get()->result_array();
    }

    public function getTeamMemberDetailsById($project_id = null, $team_id, $member_id = null, $roleType = null) {
        //L.login_id=TM.team_lead_id AND
        $this->db->select('PTM.member_id,TM.team_id,TM.team_name,concat(L.firstname," ",L.lastname) as full_name,L.email,RM.role_name,L.telephone1,L.telephone2,L.email,L.profile_photo', false);
        $this->db->from(SUPPORT_TEAM . ' as TM');
        $this->db->join(SUPPORT_TEAM_MEMBER . ' as PTM', 'PTM.team_id=TM.team_id', 'left');
        $this->db->join(LOGIN . ' as L', 'L.login_id=PTM.member_id', 'left');
        $this->db->join(ROLE_MASTER . ' as RM', 'RM.role_id=L.user_type', 'left');
        $this->db->where('TM.team_id', $team_id);
        $this->db->where('TM.is_delete', 0);
        $this->db->where('PTM.is_delete', 0);
        if ($member_id != null) {
            $this->db->where('TM.member_id', $member_id);
        }
        if ($roleType != null) {
            $this->db->where('RM.role_name', $roleType);
        }
        return $query = $this->db->get()->result_array();
    }

}
