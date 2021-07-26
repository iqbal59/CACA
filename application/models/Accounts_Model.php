<?php
class Accounts_Model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function get_accounts( $id ) {
		return $this->db->get_where( 'accounts', array( 'id' => $id ) )->row_array();
	}

	function get_all_accounts() {
		return $this->db->get_where( 'accounts', array( '' ) )->result_array();
	}
	
	function get_all_transactions() {
		return $this->db->get_where( 'payments', array( '' ) )->result_array();
	}

	function create( $params ) {
		$this->db->insert( 'accounts', $params );
		$account = $this->db->insert_id();
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> '.lang('addedanewaccount').' <a href="accounts/account/' . $account . '"> '.lang('account').'-' . $account . '</a>' ),
			'staff_id' => $loggedinuserid
		) );
		return $account;
		
	}

	function update( $id, $params ) {
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'accounts', $params );
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> '.lang('updated').' <a href="accounts/account/' . $id . '"> '.lang('account').'-' . $id . '</a>' ),
			'staff_id' => $loggedinuserid
		) );
	}

	function delete_account( $id ) {
		$payments = $this->db->get_where('payments', array('account_id' => $id))->num_rows();
		$expenses = $this->db->get_where('expenses', array('account_id' => $id))->num_rows();
		$order = $this->db->get_where('orders', array('relation_type' => 'customer', 'relation' => $id))->num_rows();
		if (($payments > 0) || ($expenses > 0)) {
			return false;
		} else {
			$response = $this->db->delete( 'accounts', array( 'id' => $id ) );
			$loggedinuserid = $this->session->usr_id;
			$staffname = $this->session->staffname;
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> '.lang('deleted').' '.lang('account').'-' . $id . '' ),
				'staff_id' => $loggedinuserid
			) );
			return true;
		}
	}

	function account_details ( $id ) {
		$account = $this->Accounts_Model->get_accounts( $id );
		$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(account_id = ' . $id . ' and transactiontype = 0)' );
		$account_incomings_sum = $this->db->get()->row()->amount;
		$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(account_id = ' . $id . ' and transactiontype = 1)' );
		$account_outgoings_sum = $this->db->get()->row()->amount;
		$account_sum = ( $account_incomings_sum - $account_outgoings_sum );
		if ( !empty( $account_sum ) ) {
			$account_total = $account_incomings_sum - $account_outgoings_sum;
		} else {
			$account_total = 0;
		}
		switch ( $account[ 'status_id' ] ) {
			case '1':
				$is_status = false;
				break;
			case '0':
				$is_status = true;
				break;
		}
		$data_account = array(
			'id' => $account[ 'id' ],
			'name' => $account[ 'name' ],
			'type' => $account[ 'type' ],
			'bankname' => $account[ 'bankname' ],
			'branchbank' => $account[ 'branchbank' ],
			'account' => $account[ 'account' ],
			'iban' => $account[ 'iban' ],
			'account_total' => $account_total,
			'status' => $is_status,
		);
		return $data_account;
	}
}