<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Accounts extends CIUIS_Controller {

	function __construct() {
		parent::__construct();
		$path = $this->uri->segment( 1 );
		if ( !$this->Privileges_Model->has_privilege( $path ) ) {
			$this->session->set_flashdata( 'ntf3', '' . lang( 'you_dont_have_permission' ) );
			redirect( 'panel/' );
			die;
		}
		$data['appconfig'] = get_appconfig();
	}

	function index() {
		$data[ 'title' ] = lang( 'accounts' );
		$data[ 'accounts' ] = $this->Accounts_Model->get_all_accounts();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'accounts/index', $data );
	}

	function account( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'accounts', 'all' ) ) {
			$data[ 'title' ] = lang( 'account' );
			$data[ 'account' ] = $this->Accounts_Model->get_accounts( $id );
			$this->load->view( 'accounts/account', $data );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('accounts'));
		}
	}

	function create() {
		if ( $this->Privileges_Model->check_privilege( 'accounts', 'create' ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$name = $this->input->post( 'name' );
				$type = $this->input->post( 'type' );
				$bankname = $this->input->post( 'bankname' );
				$branchbank = $this->input->post( 'branchbank' );
				$account = $this->input->post( 'account' );
				$iban = $this->input->post( 'iban' );
				$hasError = false;
				if ( $name == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage') .' '. lang('account') .' '. lang('name');
				} else if ( $account == '' && $type == 1 ) {
					$hasError = true;
					$data['message'] = lang('invalidmessage') .' '. lang('account') . ' '. lang('number');
				}
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					$params = array(
						'name' => $this->input->post( 'name' ),
						'type' => $this->input->post( 'type' ),
						'bankname' => $this->input->post( 'bankname' ),
						'branchbank' => $this->input->post( 'branchbank' ),
						'account' => $this->input->post( 'account' ),
						'iban' => $this->input->post( 'iban' ),
						'status_id' => 0,
					);
					$id = $this->Accounts_Model->create( $params );
					$data = array(
						'id' => $id,
						'name' => $this->input->post( 'name' ),
						'amount' => 0,
						'icon' => 'mdi mdi-balance',
						'status' => lang( 'accuntactive' ),
					);
					$data['success'] = true;
					$data['message'] = lang('accountadded');
					echo json_encode( $data );
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode( $data );
		}
	}

	function update( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'accounts', 'all' ) ) {
			if ( $this->Privileges_Model->check_privilege( 'accounts', 'edit' ) ) {
				$data[ 'accounts' ] = $this->Accounts_Model->get_accounts( $id );
				if ( isset( $data[ 'accounts' ][ 'id' ] ) ) {
					if ( isset( $_POST ) && count( $_POST ) > 0 ) {
						$params = array(
							'name' => $this->input->post( 'name' ),
							'bankname' => $this->input->post( 'bankname' ),
							'branchbank' => $this->input->post( 'branchbank' ),
							'account' => $this->input->post( 'account' ),
							'iban' => $this->input->post( 'iban' ),
							'status_id' => $this->input->post( 'status' ),
						);
						$this->Accounts_Model->update( $id, $params );
						$data['success'] = true;
						$data['message'] = lang( 'accountupdated' );
						echo json_encode($data);
					} else {
						$this->load->view( 'accounts/', $data );
					}
				} else {
					show_error( lang('expense_not_exist') );
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang( 'you_dont_have_permission' );
				echo json_encode($data);
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang( 'you_dont_have_permission' );
			echo json_encode($data);
		}
	}

	function make_transfer( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'accounts', 'edit' ) ) {
			if ( isset($_POST) && count($_POST) > 0){
				$from_account_id = $this->input->post( 'from_account_id' );
				$to_account_id = $this->input->post( 'to_account_id' );
				$amount = $this->input->post( 'amount' );
				$account = $this->Accounts_Model->account_details($id);
				$hasError = false;

				if ( $to_account_id == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage') .' '. lang('account');
				} else if ( $account['account_total'] == '' ) {
					$hasError = true;
					$data['message'] = lang('invalidmessage') .' '. lang('amount');
				} else if ( $from_account_id == $to_account_id ) {
					$hasError = true;
					$data['message'] = lang('sameaccounterror') .' '. lang('account');
				} else if ( $account['account_total'] < $amount ) {
					$hasError = true;
					$data['message'] = lang('insufficient_balance');
				} else if ( $amount <= 0 ) {
					$hasError = true;
					$data['message'] = lang('invalidmessage').' '.lang('amount');
				}
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}

				if (!$hasError) {
					$this->db->insert( 'payments', array(
					'transactiontype' => 0,
					'is_transfer' => 1,
					'expense_id' => 0,
					'staff_id' => $this->session->usr_id,
					'amount' => $this->input->post( 'amount' ),
					'account_id' => $this->input->post( 'to_account_id' ),
					'customer_id' => 0,
					'not' => lang('money_transfer_between_accounts'),
					'date' => date( 'Y-m-d h:i:s' ),
				) );
				$this->db->insert( 'payments', array(
					'transactiontype' => 1,
					'is_transfer' => 1,
					'expense_id' => 0,
					'staff_id' => $this->session->usr_id,
					'amount' => $this->input->post( 'amount' ),
					'account_id' => $this->input->post( 'from_account_id' ),
					'customer_id' => 0,
					'not' => lang('money_transfer_between_accounts'),
					'date' => date( 'Y-m-d h:i:s' ),
				) );
					$data['success'] = true;
					$data['message'] = lang('success');
					echo json_encode($data);
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function remove( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'accounts', 'all' ) ) {
			if ( $this->Privileges_Model->check_privilege( 'accounts', 'delete' ) ) {										
				$accounts = $this->Accounts_Model->get_accounts( $id );
				if ( isset( $accounts[ 'id' ] ) ) {
					$result = $this->Accounts_Model->delete_account( $id );
					$account = lang('account');
					if ( $result ) {
						$data['message'] = sprintf( lang( 'success_delete' ), $account . '' );
						$data['success'] = true;
						echo json_encode($data);
					} else {
						$data['message'] = sprintf( lang( 'cant_delete' ), $account . '' );
						$data['success'] = false;
						echo json_encode($data);
					}
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang( 'you_dont_have_permission' );
				echo json_encode($data);
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang( 'you_dont_have_permission' );
			echo json_encode($data);
		}
	}

	function get_account( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'accounts', 'all' ) ) {
			$account = $this->Accounts_Model->get_accounts( $id );
			$payments = $this->db->select( '*' )->order_by( 'id', 'desc' )->get_where( 'payments', array( 'account_id' => $id ) )->result_array();
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

			$payments_data = array();
			foreach ( $payments as $payment ) {
				if ( $payment[ 'customer_id' ] != 0 ) {
					$customer = $this->Customers_Model->get_customers( $payment[ 'customer_id' ] );
					switch ( $customer[ 'type' ] ) {
						case '0':
							$name = $customer[ 'company' ];
							$type = lang( 'corporatecustomers' );
							break;
						case '1':
							$name = $customer[ 'namesurname' ];
							$type = lang( 'individual' );
							break;
					};
				} else {
					$name = 'false';
				}
				$staff = $this->Staff_Model->get_staff( $payment[ 'staff_id' ] );

				if ( $payment[ 'customer_id' ] != 0 ) {
					$for_customer = true;
				} else {
					$for_customer = false;
				}
				$payments_data[] = array(
					'id' => $payment[ 'id' ],
					'transactiontype' => $payment[ 'transactiontype' ],
					'is_transfer' => $payment[ 'is_transfer' ],
					'invoice_id' => $payment[ 'invoice_id' ],
					'expense_id' => $payment[ 'expense_id' ],
					'customer_id' => $payment[ 'customer_id' ],
					'customer' => $name,
					'amount' => $payment[ 'amount' ],
					'account_id' => $payment[ 'account_id' ],
					'date' => date( DATE_ISO8601, strtotime( $payment[ 'date' ] ) ),
					'attachment' => $payment[ 'attachment' ],
					'staff_id' => $payment[ 'staff_id' ],
					'not' => $payment[ 'not' ],
					'staff' => $staff[ 'staffname' ],
					'for_customer' => $for_customer,
				);
			};

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
				'payments' => $payments_data,
			);
			echo json_encode( $data_account );
		} 
	}

	function get_accounts() {
		if ( $this->Privileges_Model->check_privilege( 'accounts', 'all' ) ) {
			$accounts = $this->Accounts_Model->get_all_accounts();
			$data_account = array();
			foreach ( $accounts as $account ) {
				switch ( $account[ 'type' ] ) {
					case '0':
						$icon = 'mdi mdi-balance-wallet';
						break;
					case '1':
						$icon = 'mdi mdi-balance';
						break;
				};
				switch ( $account[ 'status_id' ] ) {
					case '0':
						$status = lang( 'accuntactive' );
						break;
					case '0':
						$status = lang( 'accuntnotactive' );
						break;
				};
				$data_account[] = array(
					'id' => $account[ 'id' ],
					'name' => $account[ 'name' ],
					'amount' => $data = $amountby = $this->Report_Model->get_account_amount( $account[ 'id' ] ),
					'icon' => $icon,
					'status' => $status,
				);
			};
			echo json_encode( $data_account );
		} 
	}
}