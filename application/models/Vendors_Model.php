<?php
class Vendors_Model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function get_vendors( $id ) {
		$this->db->select( '*, vendors.id as id ' );
		return $this->db->get_where( 'vendors', array( 'vendors.id' => $id ) )->row_array();
	}

	function get_all_vendors() {
		$this->db->select( '*, vendors.id as id ' );
		$this->db->join('vendors_groups','vendors.groupid = vendors_groups.id','left');
		$this->db->order_by( 'vendors.id', 'desc' );
		return $this->db->get_where( 'vendors', array( '' ) )->result_array();
	}

	function add_vendors( $params ) {
		$this->db->insert( 'vendors', $params );
		$vendor_id = $this->db->insert_id();
		$appconfig = get_appconfig();
		$number = $appconfig['vendor_series'] ? $appconfig['vendor_series'] : $vendor_id;
		$vendor_number = $appconfig['vendor_prefix'].$number;
		$this->db->where('id', $vendor_id)->update( 'vendors', array('vendor_number' => $vendor_number ) );
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id . '"> ' . $this->session->staffname . '</a> ' . lang( 'addedavendor' ) . ' <a href="vendors/vendor/' . $vendor_id . '">' . ' ' . get_number('vendors',$vendor_id,'vendor','vendor') . '</a>' ),
			'staff_id' => $this->session->usr_id
		) );
		return $vendor_id;
	}

	function update_vendors( $id, $params ) {
		$appconfig = get_appconfig();
		$vendor_data = $this->get_vendors($id);
		if($vendor_data['vendor_number']==''){
			$number = $appconfig['vendor_series'] ? $appconfig['vendor_series'] : $id;
			$vendor_number = $appconfig['vendor_prefix'].$number;
			$this->db->where('id',$id)->update('vendors',array('vendor_number'=>$vendor_number));
			if(($appconfig['vendor_series']!='')){
				$vendor_number = $appconfig['vendor_series'];
				$vendor_number = $vendor_number + 1;
				$this->Settings_Model->increment_series('vendor_series',$vendor_number);
			}
		}
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'vendors', $params );
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id . '"> ' . $this->session->staffname . '</a> ' . lang( 'updated' ) . ' <a href="vendors/vendor/' . $id . '">' . get_number('vendors',$id,'vendor','vendor') . '</a>' ),
			'staff_id' => $this->session->usr_id
		) );
	}

	function delete_vendors( $id, $number ) {
		$purchase = $this->db->get_where('purchases', array('vendor_id' => $id))->num_rows();
		if($purchase > 0) {
			return false;
		} else {
			$response = $this->db->delete( 'vendors', array( 'id' => $id ) );
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id. '"> ' . $this->session->staffname . '</a> ' . lang( 'deleted' ) . ' ' . $number . '' ),
				'staff_id' => $this->session->usr_id
			) );
			return true;
		}
	}

	function get_vendor_groups() {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get_where( 'vendors_groups', array( '' ) )->result_array();
	}

	function get_groups($staff_id='') {
		$this->db->select('vendors_groups.name as name, COUNT(vendors_groups.name) as y');
		$this->db->join( 'vendors_groups', 'vendors.groupid = vendors_groups.id', 'left' );
		if($staff_id){
			$this->db->where('staff_id', $staff_id);
		}
		$this->db->group_by('vendors_groups.name'); 
		return $this->db->get_where( 'vendors', array( '' ) )->result_array();
	}

	function get_group( $id ) {
		return $this->db->get_where( 'vendors_groups', array( 'id' => $id ) )->row_array();
	}

	function update_group( $id, $params ) {
		$this->db->where( 'id', $id );
		return $this->db->update( 'vendors_groups', $params );
	}

	function check_group($id) {
		$data = $this->db->get_where( 'vendors', array( 'groupid' => $id ) )->num_rows();
		return $data;
	}

	function remove_group( $id ) {
		$response = $this->db->delete( 'vendors_groups', array( 'id' => $id ) );
	}

	function get_all_vendors_by_privileges($staff_id='') {
		$this->db->select( '*, vendors.id as id ' );
		$this->db->join('vendors_groups','vendors.groupid = vendors_groups.id','left');
		$this->db->order_by( 'vendors.id', 'desc' );
		if($staff_id) {
			return $this->db->get_where( 'vendors', array( 'staff_id' => $staff_id ) )->result_array();
		} else {
			return $this->db->get_where( 'vendors', array( '' ) )->result_array();
		}
	}

	function get_vendor_by_privileges( $id, $staff_id='' ) {
		$this->db->select( '*, vendors.id as id ' );
		if($staff_id) {
			return $this->db->get_where( 'vendors', array( 'vendors.id' => $id, 'staff_id' => $staff_id ) )->row_array();
		} else {
			return $this->db->get_where( 'vendors', array( 'vendors.id' => $id ) )->row_array();
		}
		
	}

}