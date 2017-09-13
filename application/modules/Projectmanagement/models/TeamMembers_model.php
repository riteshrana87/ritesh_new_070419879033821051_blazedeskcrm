<?php

class TeamMembers_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getTeamMemberList($project_id, $team_id) {
        //L.login_id=TM.team_lead_id AND 
        $this->db->select('PTM.member_id,TM.team_id,TM.team_name,concat(L.firstname," ",L.lastname) as full_name,L.email,RM.role_name,L.telephone1,L.telephone2,L.email,TM.team_lead_id,(select count(PTT.task_id)  from blzdsk_' . PROJECT_TASK_TEAM_TRAN . ' as PTT  LEFT JOIN `blzdsk_project_task_master` as `PTM` ON `PTM`.`task_id`=`PTT`.`task_id` WHERE PTM.member_id=user_id and PTM.project_id=' . $project_id . ') as count_task,L.profile_photo', false);
        $this->db->from(PROJECT_TEAM_MASTER . ' as TM');
        $this->db->join(PROJECT_TEAM_MEMBERS . ' as PTM', 'PTM.team_id=TM.team_id', 'left');
        $this->db->join(LOGIN . ' as L', 'L.login_id=PTM.member_id', 'left');
        $this->db->join(PROJECT_TASK_TEAM_TRAN . ' as PTT', 'PTT.user_id=PTM.member_id', 'left');
        $this->db->join(ROLE_MASTER . ' as RM', 'RM.role_id=L.user_type', 'left');
//        $this->db->join(aauth_perm_to_group . ' as APG', 'APG.role_id=RM.role_id', 'left');
//        $this->db->join(MODULE_MASTER . ' as MM', 'APG.role_id=MM.role_id', 'left');
//       / $this->db->where('TM.project_id', $project_id);
        $this->db->where('PTM.team_id', $team_id);
        $this->db->where('TM.is_delete', 0, false);
        $this->db->where('PTM.is_delete', 0);
        $this->db->where('L.is_delete', 0);
        $this->db->group_by('PTM.member_id');
        $this->db->order_by('PTM.team_member_id desc,RM.role_name desc');

        $this->db->where('RM.role_name!=', 'Team Leader');

        return $this->db->get()->result_array();
    }

    public function getallTeamMemberList($project_id) {
        //L.login_id=TM.team_lead_id AND 
        $this->db->select('TM.member_id', false);
        $this->db->from(PROJECT_TEAM_MEMBERS . ' as TM');
        //  $this->db->join(PROJECT_TEAM_MEMBERS . ' as PTM', 'PTM.team_id=TM.team_id', 'left');
        $this->db->where('TM.project_id', $project_id);
        $this->db->where('TM.is_delete', 0, false);
        $this->db->group_by('TM.member_id');
        return $this->db->get()->result_array();
    }

    public function getUnsortedMembers($project_id) {
        //L.login_id=TM.team_lead_id AND 
        $this->db->select('PTM.member_id,concat(L.firstname," ",L.lastname) as full_name,L.email,RM.role_name,L.telephone1,L.telephone2,L.email,(select count(PTT.task_id)  from blzdsk_' . PROJECT_TASK_TEAM_TRAN . ' as PTT  LEFT JOIN `blzdsk_project_task_master` as `PTM` ON `PTM`.`task_id`=`PTT`.`task_id` and PTM.is_delete = 0 WHERE PTM.member_id=user_id and PTM.project_id=' . $project_id . ') as count_task,L.profile_photo', false);
        $this->db->from(PROJECT_TEAM_MEMBERS . ' as PTM');
        $this->db->join(LOGIN . ' as L', 'L.login_id=PTM.member_id', 'left');
        $this->db->join(PROJECT_TASK_TEAM_TRAN . ' as PTT', 'PTT.user_id=PTM.member_id', 'left');
        $this->db->join(ROLE_MASTER . ' as RM', 'RM.role_id=L.user_type', 'left');
        $this->db->where('PTM.project_id', $project_id);
        $this->db->where('PTM.team_id', 0);
        $this->db->where('PTM.is_delete', 0);
        $this->db->where('L.is_delete', 0);
        $this->db->group_by('PTM.member_id');
        // $this->db->where('RM.role_name!=', 'Team Leader');

        return $this->db->get()->result_array();
    }

    public function getTeamLeaderbyId($project_id, $team_id) {
        //L.login_id=TM.team_lead_id AND 
//        $this->db->select('TM.team_lead_id as member_id,TM.team_id,TM.team_name,concat(L.firstname," ",L.lastname) as full_name,L.email,RM.role_name,L.telephone1,L.telephone2,L.email,TM.team_lead_id,(select count(PTT.task_id)  from blzdsk_' . PROJECT_TASK_TEAM_TRAN . ' as PTT  LEFT JOIN `blzdsk_project_task_master` as `PTM` ON `PTM`.`task_id`=`PTT`.`task_id` WHERE TM.team_lead_id=user_id and PTM.project_id=' . $project_id . ') as count_task,L.profile_photo', false);
//        $this->db->from(PROJECT_TEAM_MASTER . ' as TM');
//        $this->db->join(PROJECT_TEAM_MEMBERS . ' as PTMS', 'PTMS.team_id=TM.team_id', 'inner');
//        $this->db->join(LOGIN . ' as L', 'L.login_id=PTMS.member_id', 'left');
//        $this->db->join(PROJECT_TASK_TEAM_TRAN . ' as PTT', 'PTT.user_id=TM.team_lead_id', 'left');
//        $this->db->join(PROJECT_TASK_MASTER . ' as PTM', 'PTM.task_id=PTT.task_id', 'left');
//        $this->db->join(ROLE_MASTER . ' as RM', 'RM.role_id=L.user_type', 'left');
////        $this->db->join(aauth_perm_to_group . ' as APG', 'APG.role_id=RM.role_id', 'left');
////        $this->db->join(MODULE_MASTER . ' as MM', 'APG.role_id=MM.role_id', 'left');
//        // $this->db->where('TM.project_id', $project_id);
//        $this->db->where('TM.is_delete', 0);
//        $this->db->where('TM.team_id', $team_id);
//        $this->db->group_by('L.login_id');
//        $this->db->where('RM.role_name', 'Team_Leader');
//        return $this->db->get()->result_array();
        $this->db->select('PTM.member_id,TM.team_id,TM.team_name,concat(L.firstname," ",L.lastname) as full_name,L.email,RM.role_name,L.telephone1,L.telephone2,L.email,TM.team_lead_id,(select count(PTT.task_id)  from blzdsk_' . PROJECT_TASK_TEAM_TRAN . ' as PTT  LEFT JOIN `blzdsk_project_task_master` as `PTM` ON `PTM`.`task_id`=`PTT`.`task_id` WHERE PTM.member_id=user_id and PTM.project_id=' . $project_id . ') as count_task,L.profile_photo', false);
        $this->db->from(PROJECT_TEAM_MASTER . ' as TM');
        $this->db->join(PROJECT_TEAM_MEMBERS . ' as PTM', 'PTM.team_id=TM.team_id', 'left');
        $this->db->join(LOGIN . ' as L', 'L.login_id=PTM.member_id', 'left');
        $this->db->join(PROJECT_TASK_TEAM_TRAN . ' as PTT', 'PTT.user_id=PTM.member_id', 'left');
        $this->db->join(ROLE_MASTER . ' as RM', 'RM.role_id=L.user_type', 'left');
//        $this->db->join(aauth_perm_to_group . ' as APG', 'APG.role_id=RM.role_id', 'left');
//        $this->db->join(MODULE_MASTER . ' as MM', 'APG.role_id=MM.role_id', 'left');
//       / $this->db->where('TM.project_id', $project_id);
        $this->db->where('PTM.team_id', $team_id);
        $this->db->where('TM.is_delete', 0, false);
        $this->db->where('PTM.is_delete', 0);
        $this->db->where('L.is_delete', 0);
        $this->db->group_by('PTM.member_id');
        $this->db->order_by('PTM.team_member_id desc,RM.role_name desc');

        $this->db->where('RM.role_name', 'Team Leader');
        return $this->db->get()->result_array();
    }

    public function getProjectManager($project_id) {
        //L.login_id=TM.team_lead_id AND PROJECT_PM_ASSIGN LEFT JOIN `blzdsk_login` as `L` ON `L`.`login_id`=`PPA`.`user_id`
        $this->db->select('PPA.user_id as member_id,concat(L.firstname," ",L.lastname) as full_name,L.email,RM.role_name,L.telephone1,L.telephone2,L.email,(select count(task_id)  from blzdsk_' . PROJECT_TASK_TEAM_TRAN . ' as PTT  WHERE PPA.user_id=user_id) as count_task,L.profile_photo', false);
        $this->db->from(PROJECT_PM_ASSIGN . ' as PPA');
        $this->db->join(LOGIN . ' as L', 'L.login_id=PPA.user_id', 'inner');
        $this->db->join(PROJECT_TASK_TEAM_TRAN . ' as PTT', 'PTT.user_id=PPA.user_id', 'left');
        $this->db->join(ROLE_MASTER . ' as RM', 'RM.role_id=L.user_type', 'left');
        $this->db->where('PPA.project_id', $project_id);
        $this->db->where('L.is_delete', 0);
//        $this->db->where('RM.role_name!=', 'Team_Leader');
        return $this->db->get()->result_array();
    }

    public function getProjectManagerId($project_id) {
        //L.login_id=TM.team_lead_id AND PROJECT_PM_ASSIGN LEFT JOIN `blzdsk_login` as `L` ON `L`.`login_id`=`PPA`.`user_id`
        $this->db->select();
        $this->db->from(PROJECT_PM_ASSIGN . ' as PPA');
        $this->db->where('PPA.project_id', $project_id);
//        $this->db->where('RM.role_name!=', 'Team_Leader');
        return $this->db->get()->result_array();
    }

    function getTeamList($project_id) {
        $this->db->select('TM.team_id,TM.team_name');
        $this->db->from(PROJECT_TEAM_MASTER . ' as TM');
        $this->db->join(PROJECT_TEAM_MEMBERS . ' as PTM', 'PTM.team_id=TM.team_id', 'inner');
        $this->db->where('TM.project_id', $project_id);
        $this->db->where('TM.is_delete', '0');
        $this->db->where('PTM.is_delete', '0');
        $this->db->group_by('PTM.team_id');

        return $query = $this->db->get()->result_array();
    }

    public function getTeamMemberDetailsById($project_id, $team_id, $member_id = null, $roleType = null) {
        //L.login_id=TM.team_lead_id AND
        $this->db->select('PTM.member_id,TM.team_id,TM.team_name,concat(L.firstname," ",L.lastname) as full_name,L.email,RM.role_name,L.telephone1,L.telephone2,L.email,L.profile_photo', false);
        $this->db->from(PROJECT_TEAM_MASTER . ' as TM');
        $this->db->join(PROJECT_TEAM_MEMBERS . ' as PTM', 'PTM.team_id=TM.team_id', 'left');
        $this->db->join(LOGIN . ' as L', 'L.login_id=PTM.member_id', 'left');
        $this->db->join(ROLE_MASTER . ' as RM', 'RM.role_id=L.user_type', 'left');
        $this->db->where('TM.project_id', $project_id);
        $this->db->where('TM.team_id', $team_id);
        $this->db->where('TM.is_delete', 0);

        if ($member_id != null) {
            $this->db->where('TM.member_id', $member_id);
        }
        if ($roleType != null) {
            $this->db->where('RM.role_name', $roleType);
        }
        return $query = $this->db->get()->result_array();
    }

    function validateTeamUnique($team_name, $id) {
        $this->db->select('TM.team_id,TM.team_name');
        $this->db->from(PROJECT_TEAM_MASTER . ' as TM');
        $this->db->where('TM.team_name', $team_name);
        if ($id > 0) {
            $this->db->where('TM.team_id !=', $id);
        }
        $this->db->where('TM.is_delete', 0);
        return $query = $this->db->get()->result_array();
    }

    public function getRolesByuser($member_id) {
        //L.login_id=TM.team_lead_id AND
        $this->db->select();
        $this->db->from(LOGIN . ' as L');
        $this->db->join(ROLE_MASTER . ' as RM', 'RM.role_id=L.user_type', 'left');
        $this->db->where('L.is_delete', 0);

        if ($member_id != null) {
            $this->db->where('L.login_id', $member_id);
        }

        return $query = $this->db->get()->result_array();
    }

    public function getTaskDetails($task_id) {

        //L.login_id=TM.team_lead_id AND
        $this->db->select();
        $this->db->from(PROJECT_TASK_MASTER.' as pt');
      //  $this->db->join(PROJECT_STATUS . ' as ps', 'ps.status_id=status', 'left');
        $this->db->where('is_delete', 0);
        //  if ($member_id != null) {
        $this->db->where('task_id', $task_id);
        //  }

        return $query = $this->db->get()->result_array();
    }

}
