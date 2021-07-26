<?php
class Customers_Model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function get_customers( $id ) {
		$this->db->select( '*, customers.id as id ' );
		$this->db->join('customergroups','customers.groupid = customergroups.id','left');
		return $this->db->get_where( 'customers', array( 'customers.id' => $id ) )->row_array();
	}

	function get_all_customers($staff_id='') {
		$this->db->select( '*, customers.id as id ' );
		$this->db->join('customergroups','customers.groupid = customergroups.id','left');
		$this->db->order_by( 'customers.id', 'desc' );
		if($staff_id) {
			return $this->db->get_where( 'customers', array( 'staff_id' => $staff_id) )->result_array();
		} else {
			return $this->db->get_where( 'customers', array( '' ) )->result_array();	
		}
	}

	function get_subsidiaries($id) {
		$this->db->select( '*, customers.id as id ' );
		$this->db->order_by( 'customers.id', 'desc' );
		return $this->db->get_where( 'customers', array( 'customers.subsidiary_parent_id' => $id ) )->result_array();
	}
	
	function add_customers( $params ) {
		$this->db->insert( 'customers', $params );
		$customer_id = $this->db->insert_id();
		$appconfig = get_appconfig();
		$number = $appconfig['customer_series'] ? $appconfig['customer_series'] : $customer_id;
		$customer_number = $appconfig['customer_prefix'].$number;
		$this->db->where('id', $customer_id)->update( 'customers', array('customer_number' => $customer_number ) );
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id . '"> ' . $this->session->staffname . '</a> ' . lang( 'addedacustomer' ) . ' <a href="customers/customer/' . $customer_id . '">' . get_number('customers',$customer_id,'customer','customer') . '</a>' ),
			'staff_id' => $this->session->usr_id
		) );

		$isContact = $this->input->post('contact');
		if ($isContact == '1') {
			$password = password_hash( $this->input->post( 'password' ), PASSWORD_BCRYPT );
			if($this->input->post('company')){
				$company = $this->input->post('company');
			} else {
				$company = $this->input->post('namesurname');
			}
			$param = array(
				'name' => $company,
				'surname' => '',
				'phone' => $this->input->post('phone'),
				'email' => $this->input->post('email'),
				'password' => $password,
				'address' => $this->input->post('address'),
				'customer_id' => $customer_id,
			);
			$contacts_id = $this->Contacts_Model->create( $param );
			if($contacts_id){
				$update_contact_privilege = $this->update_contact_privilege($contacts_id, 'true', '1');
				$update_contact_privilege = $this->update_contact_privilege($contacts_id, 'true', '2');
				$update_contact_privilege = $this->update_contact_privilege($contacts_id, 'true', '7');
				$update_contact_privilege = $this->update_contact_privilege($contacts_id, 'true', '9');
				$update_contact_privilege = $this->update_contact_privilege($contacts_id, 'true', '16');

				$template = $this->Emails_Model->get_template('customer', 'new_contact_added');
				if ($template['status'] == 1 ) {
					$message_vars = array(
						'{login_email}' => $this->input->post( 'email' ),
						'{login_password}' => ($this->input->post( 'password' ))?($this->input->post( 'password' )):' ',
						'{app_url}' => '' . base_url( 'area/login' ) . '',
						'{email_signature}' => $this->session->userdata( 'email' ),
						'{name}' => $this->session->userdata( 'staffname' ),
						'{customer}' => $this->input->post( 'name' )
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);
					$param = array(
						'from_name' => $template['from_name'],
						'email' => $this->input->post( 'email' ),
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" ),
					);
					if ($this->input->post( 'email' )) {
						$this->db->insert( 'email_queue', $param );
					}
				}
			}
		}
		return $customer_id;
	}

	function update_contact_privilege( $id, $value, $privilege_id ) {
		if ( $value != 'false' ) {
			$params = array(
				'relation' => ( int )$id,
				'relation_type' => 'contact',
				'permission_id' => ( int )$privilege_id
			);
			$this->db->insert( 'privileges', $params );
			return $this->db->insert_id();
		} else {
			$response = $this->db->delete( 'privileges', array( 'relation' => $id, 'relation_type' => 'contact', 'permission_id' => $privilege_id ) );
		}
	}

	function get_customers_for_import() {     
        $query = $this->db->get('customers');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
	}
	
	function get_customer_groups() {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get_where( 'customergroups', array( '' ) )->result_array();
	}

	function get_groups($staff_id='') {
		$this->db->select('customergroups.name as name, COUNT(customergroups.name) as y');
		$this->db->join( 'customergroups', 'customers.groupid = customergroups.id', 'left' );
		if($staff_id){
			$this->db->where('staff_id', $staff_id);
		}
		$this->db->group_by('customergroups.name'); 
		return $this->db->get_where( 'customers', array( '' ) )->result_array();
	}

	function get_group( $id ) {
		return $this->db->get_where( 'customergroups', array( 'id' => $id ) )->row_array();
	}

	function update_group( $id, $params ) {
		$this->db->where( 'id', $id );
		return $this->db->update( 'customergroups', $params );
	}

	function check_group($id) {
		$data = $this->db->get_where( 'customers', array( 'groupid' => $id ) )->num_rows();
		return $data;
	}

	function remove_group( $id ) {
		$response = $this->db->delete( 'customergroups', array( 'id' => $id ) );
	}

    function insert_customers_csv($data) {
		$this->db->insert('customers', $data);
		$customer = $this->db->insert_id();
		$appconfig = get_appconfig();
		$number = $appconfig['customer_series'] ? $appconfig['customer_series'] : $customer;
		$customer_number = $appconfig['customer_prefix'].$number;
		$this->db->where('id', $customer)->update( 'customers', array('customer_number' => $customer_number ) );
    }

	function update_customers( $id, $params ) {
		$appconfig = get_appconfig();
		$customer_data = $this->get_customers($id);
		if($customer_data['customer_number']==''){
			$number = $appconfig['customer_series'] ? $appconfig['customer_series'] : $id;
			$customer_number = $appconfig['customer_prefix'].$number;
			$this->db->where('id',$id)->update('customers',array('customer_number'=>$customer_number));
			if(($appconfig['customer_series']!='')){
				$customer_number = $appconfig['customer_series'];
				$customer_number = $customer_number + 1;
				$this->Settings_Model->increment_series('invoice_series',$customer_number);
			}
		}
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'customers', $params );
		$loggedinuserid = $this->session->usr_id;
		$staffname = $this->session->staffname;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="'.base_url().'staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'updated' ) . ' <a href="'.base_url().'customers/customer/' . $id . '">' . get_number('customers',$id,'customer','customer'). '</a>.' ),
			'staff_id' => $loggedinuserid,
		) );
	}

	function delete_customers( $id, $number ) {
		$invoice = $this->db->get_where('invoices', array('customer_id' => $id))->num_rows();
		$proposal = $this->db->get_where('proposals', array('relation_type' => 'customer', 'relation' => $id))->num_rows();
		$expense = $this->db->get_where('expenses', array('customer_id' => $id))->num_rows();
		$project = $this->db->get_where('projects', array('customer_id' => $id))->num_rows();
		$ticket = $this->db->get_where('tickets', array('customer_id' => $id))->num_rows();
		$deposit = $this->db->get_where('deposits', array('customer_id' => $id))->num_rows();
		$order = $this->db->get_where('orders', array('relation_type' => 'customer', 'relation' => $id))->num_rows();
		if (($invoice > 0) || ($proposal > 0) || ($expense > 0) || ($project > 0) || ($ticket > 0) || ($deposit > 0) || ($order > 0)) {
			return false;
		} else {
			$response = $this->db->delete( 'customers', array( 'id' => $id ) );
			$response = $this->db->delete( 'contacts', array( 'customer_id' => $id ) );
			$response = $this->db->delete( 'logs', array( 'customer_id' => $id ) );
			$response = $this->db->delete( 'notifications', array( 'customer_id' => $id ) );
			$response = $this->db->delete( 'reminders', array( 'relation_type' => 'customer', 'relation' => $id ) );
			$response = $this->db->delete( 'notes', array( 'relation_type' => 'customer', 'relation' => $id ) );
			$loggedinuserid = $this->session->usr_id;
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $this->session->staffname . '</a> ' . lang( 'deleted' ) . ' '. $number . '' ),
				'staff_id' => $this->session->usr_id
			) );
			return true;
		}
	}

	function search_json_customer() {
		$this->db->select( 'id customer,type customertype,company company,namesurname individual,' );
		$this->db->from( 'customers' );
		return $this->db->get()->result();
	}

	function get_customers_by_privileges( $id, $staff_id='' ) {
		$this->db->select( '*, customers.id as id ' );
		$this->db->join('customergroups','customers.groupid = customergroups.id','left');
		if($staff_id) {
			return $this->db->get_where( 'customers', array( 'customers.id' => $id, 'staff_id' => $staff_id ) )->row_array();
		} else {
			return $this->db->get_where( 'customers', array( 'customers.id' => $id ) )->row_array();
		}
		
	}

	function search_customers($q) {
		$this->db->select( '*' );
		$this->db->from('customers');
		$this->db->where('(
			email LIKE "%' . $q . '%"
			OR company LIKE "%' . $q . '%"
			OR namesurname LIKE "%' . $q . '%"
		)');
		$this->db->order_by('id', 'desc');
		return $this->db->get()->result_array();
	}
}
