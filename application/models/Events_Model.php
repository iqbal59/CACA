<?php
class Events_Model extends CI_Model {

	function get_all_events() {
		$this->db->select( '*,,staff.staffname as staff, staff.staffavatar as staff_avatar, events.id as id, event_types.name as event_type ' );
		$this->db->join( 'staff', 'events.staff_id = staff.id', 'left' );
		$this->db->join('event_types', 'events.event_type = event_types.id', 'left');
		$this->db->where( 'events.public = "1" OR events.staff_id = ' . $this->session->userdata( 'usr_id' ) . '' );
		return $this->db->get_where( 'events' )->result_array();
	}

	function get_eventtypes() {
		return $this->db->get_where( 'event_types' )->result_array();
	}

	function get_eventtype($id) {
		$this->db->where( 'id = ' . $id . '' );
		return $this->db->get_where( 'event_types' )->row_array();
	}

	function remove_eventtype( $id ) {
		$this->db->delete('events', array('event_type' => $id));
		$response = $this->db->delete('event_types', array('id' => $id));
	}

	function add_eventtype($params) {
		$this->db->insert( 'event_types', $params );
		return $this->db->insert_id();
	}

	function get_event_triggers() {
		return $this->db->get_where('event_triggers', array('status' => '0'))->result_array();
	}

	function get_event($id) {
		$this->db->select('*,staff.staffname as staff, staff.staffavatar as staff_avatar, events.id as id, event_types.name as event_type, events.added_by as  added_by');
		$this->db->join( 'staff', 'events.staff_id = staff.id', 'left' );
		$this->db->join('event_types', 'events.event_type = event_types.id', 'left');
		return $this->db->get_where('events', array('events.id' => $id))->row_array();
	}

	function update_event_trigger($id) {
		$this->db->where( 'id', $id);
		$this->db->update( 'event_triggers', array( 'status' => 1));
		return true;
	}

	function get_all_staffs() {
		$this->db->select( '*,departments.name as department, staff.id as id' );
		$this->db->join( 'departments', 'staff.department_id = departments.id', 'left' );
		return $this->db->get_where('staff', array('inactive' => '0'))->result_array();
	}

	function add_event($params) {
		$this->db->insert('events', $params);
		return $this->db->insert_id();
	}

	function remove( $id ) {
		$response = $this->db->delete( 'events', array( 'id' => $id ) );
	}

}