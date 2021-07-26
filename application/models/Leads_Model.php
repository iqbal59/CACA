<?php
class Leads_Model extends CI_Model {

	function get_lead( $id ) {
		$this->db->select( '*,leadsstatus.name as statusname,staff.staffname as leadassigned,staff.staffavatar as assignedavatar,staff.email as staffemail,leadssources.name as sourcename,leads.name as leadname,leads.email as leadmail,leads.phone as leadphone,leads.address as address,leads.id as id' );
		$this->db->join( 'leadsstatus', 'leads.status = leadsstatus.id', 'left' );
		$this->db->join( 'leadssources', 'leads.source = leadssources.id', 'left' );
		$this->db->join( 'staff', 'leads.assigned_id = staff.id', 'left' );
		return $this->db->get_where( 'leads', array( 'leads.id' => $id ) )->row_array();
	}
	function get_source( $id ) {
		return $this->db->get_where( 'leadssources', array( 'id' => $id ) )->row_array();
	}
	function get_status( $id ) {
		return $this->db->get_where( 'leadsstatus', array( 'id' => $id ) )->row_array();
	}
	function check_sources($id) {
		$data = $this->db->get_where( 'leads', array( 'source' => $id ) )->num_rows();
		return $data;
	}

	function check_statuses($id) {
		$data = $this->db->get_where( 'leads', array( 'status' => $id ) )->num_rows();
		return $data;
	}
	function get_all_leads() {
		$this->db->select( '*,leadsstatus.name as statusname,staff.staffname as leadassigned,staff.staffavatar as assignedavatar,leadssources.name as sourcename,leads.name as leadname,leads.email as leadmail,leads.phone as leadphone,leads.id as id' );
		$this->db->join( 'leadsstatus', 'leads.status = leadsstatus.id', 'left' );
		$this->db->join( 'leadssources', 'leads.source = leadssources.id', 'left' );
		$this->db->join( 'staff', 'leads.assigned_id = staff.id', 'left' );
		$this->db->where( 'public = 1 OR assigned_id = '.$this->session->userdata( 'usr_id' ) .'' );
		$this->db->order_by( 'leads.id', 'desc' );
		return $this->db->get( 'leads' )->result_array();
	}
	function get_all_leads_for_admin() { 
		$this->db->select( '*,leadsstatus.name as statusname,staff.staffname as leadassigned,staff.staffavatar as assignedavatar,leadssources.name as sourcename,leads.name as leadname,leads.email as leadmail,leads.phone as leadphone,leads.id as id' );
		$this->db->join( 'leadsstatus', 'leads.status = leadsstatus.id', 'left' );
		$this->db->join( 'leadssources', 'leads.source = leadssources.id', 'left' );
		$this->db->join( 'staff', 'leads.assigned_id = staff.id', 'left' );
		$this->db->order_by( 'leads.id', 'desc' );
		return $this->db->get( 'leads' )->result_array();
	}

	function get_all_web_leads() {
		$this->db->select( 'webleads.name,webleads.status as formstatus,webleads.created,webleads.id,leadsstatus.name as statusname,staff.staffname as leadassigned,staff.staffavatar as assignedavatar,leadssources.name as sourcename' );
		$this->db->join( 'leadsstatus', 'webleads.lead_status = leadsstatus.id', 'left' );
		$this->db->join( 'leadssources', 'webleads.lead_source = leadssources.id', 'left' );
		$this->db->join( 'staff', 'webleads.assigned_id = staff.id', 'left' );
		$this->db->where( 'assigned_id = '.$this->session->userdata( 'usr_id' ) .'' );
		$this->db->order_by( 'webleads.id', 'desc' );
		return $this->db->get( 'webleads' )->result_array();
	}

	function get_all_web_leads_for_admin() {
		$this->db->select( 'webleads.name,webleads.status as formstatus,webleads.created,webleads.id,leadsstatus.name as statusname,staff.staffname as leadassigned,staff.staffavatar as assignedavatar,leadssources.name as sourcename' );
		$this->db->join( 'leadsstatus', 'webleads.lead_status = leadsstatus.id', 'left' );
		$this->db->join( 'leadssources', 'webleads.lead_source = leadssources.id', 'left' );
		$this->db->join( 'staff', 'webleads.assigned_id = staff.id', 'left' );
		$this->db->order_by( 'webleads.id', 'desc' );
		return $this->db->get( 'webleads' )->result_array();
	}

	function get_weblead($id, $staff_id='') { 
		if ($staff_id) {
			return $this->db->get_where( 'webleads', array('assigned_id' => $this->session->userdata( 'usr_id' ), 'webleads.id' => $id ))->row_array();
		} else {
			return $this->db->get_where( 'webleads', array('webleads.id' => $id ))->row_array();
		}
	}

	function isAdmin() {
		$id = $this->session->usr_id;
		$rows = $this->db->get_where( 'staff', array( 'admin' => 1, 'id' => $id ) )->num_rows();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
	}

	function getFormData_by_token($token) {
		$total = $this->db->get_where( 'webleads', array('token' => $token ))->num_rows();
		if ($total > 0) {
			return $this->db->get_where( 'webleads', array('token' => $token ))->row_array();
		} else {
			return false;
		}
	}

	function check_duplicate_lead($email) {
		$total = $this->db->get_where( 'leads', array('email' => $email ))->num_rows();
		if ($total > 0) {
			return true;
		} else {
			return false;
		}
	}

	function delete_web_form($id) {
		$loggedinuserid = $this->session->usr_id;
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $this->session->staffname . '</a> ' . lang( 'deleted' ) . ' ' . lang( 'webleads' ).' '. lang('form')  . '' ),
				'staff_id' => $this->session->usr_id
			) );
		return $this->db->delete( 'webleads', array( 'id' => $id ) );
	}

	function update_weblead_form( $id, $params ) {
		$this->db->where( 'id', $id );
		$response = $this->db->update('webleads', $params );
		if ($response) {
			$loggedinuserid = $this->session->usr_id;
			$staffname = $this->session->staffname;
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '<a href="'.base_url().'staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'updated' ) .' '.lang('webleads'). ' <a href="'.base_url().'leads/form/' . $id . '">' .lang( 'form' ). '</a>.' ),
				'staff_id' => $loggedinuserid,
			) );
			return true;
		} else {
			return false;
		}
	}

	function get_leads_sources() {
		return $this->db->get( 'leadssources' )->result_array();
	}
	function get_leads_status() {
		return $this->db->get( 'leadsstatus' )->result_array();
	}

	function create_weblead_form($params) {
		$this->db->insert( 'webleads', $params );
		$id = $this->db->insert_id();

		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'addedwebleadform' ) . ' <a href="leads/form/' . $id . '">' . lang( 'form' ) . '</a>.' ),
			'staff_id' => $loggedinuserid,
		) );

		return $id;
	}

	function add_lead( $params ) {
		$this->db->insert( 'leads', $params );
		$lead = $this->db->insert_id();
		$appconfig = get_appconfig();
		$number = $appconfig['lead_series'] ? $appconfig['lead_series'] : $lead;
		$lead_number = $appconfig['lead_prefix'].$number;
		$this->db->where('id', $lead)->update( 'leads', array('lead_number' => $lead_number ) );
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id . '"> ' . $this->session->staffname . '</a> ' . lang( 'added' ) . ' <a href="leads/lead/' . $lead . '">' . get_number('leads',$lead,'lead','lead') . '</a>' ),
			'staff_id' => $this->session->usr_id
		) );
		return $lead;
	}

	function update_lead( $id, $params ) {
		$appconfig = get_appconfig();
		$lead_data = $this->get_lead($id);
		if($lead_data['lead_number']==''){
			$number = $appconfig['lead_series'] ? $appconfig['lead_series'] : $id;
			$lead_number = $appconfig['lead_prefix'].$number;
			$this->db->where('id',$id)->update('leads',array('lead_number'=>$lead_number));
			if(($appconfig['lead_series']!='')){
				$lead_number = $appconfig['lead_series'];
				$lead_number = $lead_number + 1;
				$this->Settings_Model->increment_series('lead_series',$lead_number);
			}
		}
		$this->db->where( 'id', $id );
		$response = $this->db->update('leads', $params );
		$loggedinuserid = $this->session->usr_id;
		$staffname = $this->session->staffname;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="'.base_url().'staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'updated' ) . ' <a href="'.base_url().'leads/lead/' . $id . '">' . get_number('leads',$id,'lead','lead'). '</a>.' ),
			'staff_id' => $loggedinuserid,
		) );
	}

	function delete_lead( $id, $number ) {
		$proposal = $this->db->get_where('proposals', array('relation_type' => 'lead', 'relation' => $id))->num_rows();
		$order = $this->db->get_where('orders', array('relation_type' => 'lead', 'relation' => $id))->num_rows();
		if(($proposal > 0) || ($order > 0)) {
			return false;
		} else {
			$response = $this->db->delete( 'leads', array( 'id' => $id ) );
			$loggedinuserid = $this->session->usr_id;
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $this->session->staffname . '</a> ' . lang( 'deleted' ) . ' ' . lang( 'lead' ) .' '. $number  . '' ),
				'staff_id' => $this->session->usr_id
			) );
			return true;
		}

	}

	function delete_source( $id ) {
		$response = $this->db->delete( 'leadssources', array( 'id' => $id ) );
	}

	function delete_status( $id ) {
		$response = $this->db->delete( 'leadsstatus', array( 'id' => $id ) );
	}

	public

	function isDuplicate( $email ) {
		$this->db->get_where( 'leads', array( 'email' => $email ), 1 );
		return $this->db->affected_rows() > 0 ? TRUE : FALSE;
	}
	
	/* Add Lead Status and Sources */
	
	function add_status( $params ) {
		$this->db->insert( 'leadsstatus', $params );
		return $this->db->insert_id();
	}

	function add_source( $params ) {
		$this->db->insert( 'leadssources', $params );
		return $this->db->insert_id();
	}

	/* Update Leads Status and Sources  */
	
	function update_status( $id, $params ) {
		$this->db->where( 'id', $id );
		return $this->db->update( 'leadsstatus', $params );
	}

	function update_source( $id, $params ) {
		$this->db->where( 'id', $id );
		return $this->db->update( 'leadssources', $params );
	}
	
	function get_leads_for_import() {     
        $query = $this->db->get('leads');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
    
    function insert_csv($data) {
        $this->db->insert('leads', $data);
    }

    function get_all_leads_by_privileges($staff_id='') {
		$this->db->select( '*,leadsstatus.name as statusname,staff.staffname as leadassigned,staff.staffavatar as assignedavatar,leadssources.name as sourcename,leads.name as leadname,leads.email as leadmail,leads.phone as leadphone,leads.id as id' );
		$this->db->join( 'leadsstatus', 'leads.status = leadsstatus.id', 'left' );
		$this->db->join( 'leadssources', 'leads.source = leadssources.id', 'left' );
		$this->db->join( 'staff', 'leads.assigned_id = staff.id', 'left' );
		$this->db->order_by( 'leads.id', 'desc' );
		if($staff_id) {
			$this->db->where('(leads.staff_id='.$staff_id.' OR leads.assigned_id='.$staff_id.')');
			return $this->db->get('leads')->result_array();
		} else {
			return $this->db->get( 'leads' )->result_array();	
		}
	}

	function get_lead_by_privileges( $id, $staff_id='') {
		$this->db->select( '*,leadsstatus.name as statusname,staff.staffname as leadassigned,staff.staffavatar as assignedavatar,staff.email as staffemail,leadssources.name as sourcename,leads.name as leadname,leads.email as leadmail,leads.phone as leadphone,leads.address as address,leads.id as id' );
		$this->db->join( 'leadsstatus', 'leads.status = leadsstatus.id', 'left' );
		$this->db->join( 'leadssources', 'leads.source = leadssources.id', 'left' );
		$this->db->join( 'staff', 'leads.assigned_id = staff.id', 'left' );
		if($staff_id) {
			$this->db->where('leads.id' ,$id);
			$this->db->where('(leads.staff_id='.$staff_id.' OR leads.assigned_id='.$staff_id.')');
			return $this->db->get('leads')->row_array();
		} else {
			return $this->db->get_where( 'leads', array( 'leads.id' => $id ) )->row_array();
		}
		
	}
}