<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Privileges_Model extends CI_MODEL {

	function __construct() {
		parent::__construct();
	}

	function get_staff_permissions( $id ) {
		$array = array('relation_type' => 'staff', 'relation' => $id);
		$result = $this->db->select( 'permission_id' )->where($array)->get( 'privileges' );
		return array_column( $result->result_array(), 'permission_id' );
	}
	
	function has_privilege ( $path ) {
		$staff_id = $this->session->usr_id;
		$role = $this->db->get_where('staff', array('id' => $staff_id))->row_array();
		$role_id = $role['role_id'];
		$permission = $this->db->get_where('permissions', array('key' => $path))->row_array();
		$permission_id = $permission['id'];
		$permissions = $this->db->get_where('role_permissions', array('permission_id' => $permission_id, 'role_id' => $role_id))->row_array();
		if($permissions) {
			if($permissions['permission_view_own'] == '1' || $permissions['permission_view_all'] == '1') {
				return true;
			} else { 
				return false;
			}
		} else {
			return false;
		}
	}

	function check_privilege ( $path, $type ) {
		$staff_id = $this->session->usr_id;
		$role = $this->db->get_where('staff a', array('a.id' => $staff_id))->row_array();
		$role_id = $role['role_id'];
		$permission = $this->db->get_where('permissions', array('key' => $path))->row_array();
		$permission_id = $permission['id'];
		$permission = $this->db->get_where('role_permissions', array('permission_id' => $permission_id, 'role_id' => $role_id))->row_array();
		if($permission) {
			if( $type == 'create' && $permission['permission_create'] == '1') {
				return true;
			} else if( $type == 'edit' && $permission['permission_edit'] == '1') {
				return true;
			} else if( $type == 'delete' && $permission['permission_delete'] == '1') {
				return true;
			} else if( $type == 'all' && $permission['permission_view_all'] == '1' ) {
				return true;
			} else if( $type == 'own' && $permission['permission_view_own'] == '1' ) {
				return true;
			} else { 
				return false;
			}
		} else {
			return false;
		}
	}
	
	function contact_has_privilege( $path ) {
		$relation = $_SESSION[ 'contact_id' ];
		$this->db->select( '*,permissions.key as permission_key');
		$this->db->join( 'permissions', 'privileges.permission_id = permissions.id', 'left' );
		$rows = $this->db->get_where( 'privileges', array( 'permissions.key' => $path, 'relation' => $relation, 'relation_type' => 'contact') )->num_rows();
		return ( $rows > 0 ) ? TRUE : FALSE;
	}

	function get_privileges() {
		$query = $this->db->get( 'privileges' );
		return $query->result_array();
	}

	function get_all_permissions() {
		return $this->db->get( 'permissions' )->result_array();
	}
	
	function get_all_common_permissions() {
		return $this->db->get_where( 'permissions', array( 'type' => 'common') )->result_array();
	}

	function add_privilege( $id, $privileges ) {
		$array = array('relation_type' => 'staff', 'relation' => $id);
		$delete_old = $this->db->where($array)->delete( 'privileges' );
		$data = array();
		foreach ( $privileges as $key ) {
			$arr = array(
				'relation' => ( int )$id,
				'relation_type' => 'staff',
				'permission_id' => ( int )$key
			);

			array_push( $data, $arr );
		}
		$insert_new = $this->db->insert_batch( 'privileges', $data );

		if ( $insert_new ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function add_contact_privilege( $id, $privileges ) {
		$array = array('relation_type' => 'contact', 'relation' => $id);
		$delete_old = $this->db->where($array)->delete( 'privileges' );
		$data = array();
		foreach ( $privileges as $key ) {
			$arr = array(
				'relation' => ( int )$id,
				'relation_type' => 'contact',
				'permission_id' => ( int )$key
			);

			array_push( $data, $arr );
		}
		$insert_new = $this->db->insert_batch( 'privileges', $data );

		if ( $insert_new ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}