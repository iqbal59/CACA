<?php
class Payments_Model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function addpayment( $params ) {
		$this->db->insert('deposits', $params);
		$deposit = $this->db->insert_id();
		$appconfig = get_appconfig();
		$number = $appconfig['deposit_series'] ? $appconfig['deposit_series'] : $deposit;
		$deposit_number = $appconfig['deposit_prefix'].$number;
		$this->db->where('id', $deposit)->update( 'deposits', array('deposit_number' => $deposit_number ) );
		if ( $this->input->post( 'balance' ) == 0 ) {
			$response = $this->db->where( 'id', $this->input->post( 'invoice' ) )->update( 'invoices', array( 'status_id' => 2, 'duedate' => 0 ) );
			$response = $this->db->where( 'invoice_id', $this->input->post( 'invoice' ) )->update( 'sales', array( 'status_id' => 2 ) );
		} else {
			$response = $this->db->where( 'id', $this->input->post( 'invoice' ) )->update( 'invoices', array( 'status_id' => 3 ) );
			$response = $this->db->where( 'invoice_id', $this->input->post( 'invoice' ) )->update( 'sales', array( 'status_id' => 3 ) );
		}
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'payments', array(
			'transactiontype' => '0',
			'is_transfer' => '0',
			'invoice_id' => $this->input->post( 'invoice' ),
			'amount' => $this->input->post( 'amount' ),
			'account_id' => $this->input->post( 'account' ),
			'date' => $this->input->post( 'date' ),
			'not' => $this->input->post( 'not' ),
			'attachment' => $this->input->post( 'attachment' ),
			'customer_id' => $this->input->post( 'customer' ),
			'staff_id' => $loggedinuserid,
		) );
		$payment_id = $this->db->insert_id();

		$this->db->insert( 'items', array(
			'relation_type' => 'deposit',
			'relation' => $deposit,
			'code' =>'deposit',
			'description' => get_number('deposits', $deposit, 'deposit', 'deposit'),
			'name' => 'deposit',
			'quantity' => '1',
			'price' => $this->input->post( 'amount' ),
			'total' => $this->input->post( 'amount' ),
		));

		//LOG
		$staffname = $this->session->staffname;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'added' ) . ' <a href="deposits/deposit/' . $deposit . '">' . get_number('deposits',$deposit,'deposit','deposit'). '</a>.' ),
			'staff_id' => $loggedinuserid,
			'customer_id' => $this->input->post( 'customer' )
		) );

		return $payment_id;
	}

	function todaypayments() {
		return $this->db->get_where( 'payments', array( 'DATE(date)' => date( 'Y-m-d' ) ) )->result_array();
	}

	function todaypayments_by_staff() {
		return $this->db->get_where( 'payments', array( 'DATE(date)' => date( 'Y-m-d' ), 'staff_id' => $this->session->usr_id ) )->result_array();
	}

	//Return the payment details
	function get_payment_details($id)
	{
		$this->db->join('accounts ac', 'ac.id = vp.account_id');
		$this->db->join('staff st', 'st.id = vp.staff_id');
		$this->db->where('vp.id', $id);
		return $this->db->get('payments vp')->row_array();
	}
}