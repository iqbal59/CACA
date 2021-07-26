<?php 
include APPPATH . '/third_party/vendor/autoload.php';
use Dompdf\Dompdf;
class Deposits_Model extends CI_Model { 
	function __construct() { 
		parent::__construct(); 
	} 
	/* Get Deposits by ID */ 
	function get_deposits( $id, $token='' ) { 
		$this->db->select( '*,customers.company as customer,customers.type as type,customers.namesurname as individual,customers.email as customeremail,customers.phone as customer_phone,customers.address as customeraddress,accounts.name as account,depositcat.name as category,staff.staffname as staff,staff.email as staffemail,deposits.description as desc, deposits.staff_id as depositstaff,deposits.id as id,deposits.status as deposit_status,deposits.created as depositcreate,recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate' ); 
		$this->db->join( 'customers', 'deposits.customer_id = customers.id', 'left' ); 
		$this->db->join( 'accounts', 'deposits.account_id = accounts.id', 'left' ); 
		$this->db->join( 'depositcat', 'deposits.category_id = depositcat.id', 'left' ); 
		$this->db->join( 'staff', 'deposits.staff_id = staff.id', 'left' ); 
		$this->db->join( 'recurring', "deposits.id = recurring.relation AND recurring.relation_type = 'deposit'", 'left' );
		$this->db->order_by( 'deposits.id', 'desc' ); 
		if($token == '') {
			return $this->db->get_where( 'deposits', array( 'deposits.id' => $id ) )->row_array(); 	
		} else {
			return $this->db->get_where( 'deposits', array( 'deposits.token' => $token ) )->row_array();
		}
	} 

	// All Deposits Count 
	function get_all_deposits_count() { 
		$this->db->from( 'deposits' ); 
		return $this->db->count_all_results(); 
	} 

	/* Get All Deposits */ 
	function get_all_deposits($staff_id='') { 
		$this->db->select( '*,customers.company as customer,customers.type as type,customers.namesurname as individual,depositcat.name as category,staff.staffname as staff,deposits.description as desc, deposits.id as id' ); 
		$this->db->join( 'customers', 'deposits.customer_id = customers.id', 'left' ); 
		$this->db->join( 'depositcat', 'deposits.category_id = depositcat.id', 'left' ); 
		$this->db->join( 'staff', 'deposits.staff_id = staff.id', 'left' ); 
		$this->db->order_by( 'deposits.id', 'desc' ); 
		if($staff_id) {
			$this->db->where('(deposits_created_by='.$staff_id.' OR deposits.staff_id='.$staff_id.')');
			return $this->db->get( 'deposits' )->result_array(); 
		} else {
			return $this->db->get( 'deposits' )->result_array(); 	
		}
		
	} 

	function get_all_deposits_by_relation($relation_type,$relation_id) { 
		$this->db->select( '*,customers.company as customer,customers.type as type,customers.namesurname as individual,depositcat.name as category,staff.staffname as staff,deposits.description as desc, deposits.id as id' ); 
		$this->db->join( 'customers', 'deposits.customer_id = customers.id', 'left' ); 
		$this->db->join( 'depositcat', 'deposits.category_id = depositcat.id', 'left' ); 
		$this->db->join( 'staff', 'deposits.staff_id = staff.id', 'left' ); 
		$this->db->order_by( 'deposits.id', 'desc' ); 
		return $this->db->get_where( 'deposits', array( 'relation' => $relation_id, 'relation_type' => $relation_type ) )->result_array(); 
	} 
	
	// Function to add new deposits
	function create( $params ) {
		$this->db->insert( 'deposits', $params );
		$deposit = $this->db->insert_id();
		$appconfig = get_appconfig();
		$number = $appconfig['deposit_series'] ? $appconfig['deposit_series'] : $deposit;
		$deposit_number = $appconfig['deposit_prefix'].$number;
		$this->db->where('id', $deposit)->update( 'deposits', array('deposit_number' => $deposit_number ) );
		$loggedinuserid = $this->session->usr_id;
		if ( $params['status'] == '2') {
			$this->db->insert( 'payments', array(
				'transactiontype' => 0,
				'deposit_id' => $deposit,
				'staff_id' => $loggedinuserid,
				'amount' => $this->input->post( 'total' ),
				'account_id' => $this->input->post( 'account' ),
				'customer_id' => $this->input->post( 'customer' ),
				'is_transfer' => 0,
				'not' => lang('deposit').' '.lang('for').' '.'<a href="' . base_url( 'deposits/deposit/' . $deposit . '' ) . '">'. get_number('deposits',$deposit,'deposit','deposit') . '</a>',
				'date' => _pdate( $this->input->post( 'date' ) ),
			) );
		}
		$items = $this->input->post( 'items' );
		$i = 0;
		foreach ( $items as $item ) {
			$this->db->insert( 'items', array(
				'relation_type' => 'deposit',
				'relation' => $deposit,
				'product_id' => $item[ 'product_id' ],
				'code' => $item[ 'code' ],
				'name' => $item[ 'name' ],
				'description' => $item[ 'description' ],
				'quantity' => $item[ 'quantity' ],
				'unit' => $item[ 'unit' ],
				'price' => $item[ 'price' ],
				'tax' => $item[ 'tax' ],
				'discount' => $item[ 'discount' ],
				'total' => $item[ 'quantity' ] * $item[ 'price' ] + ( ( $item[ 'tax' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ) - ( ( $item[ 'discount' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ),
			));
			$i++;
		};
		//LOG
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="'.base_url().'staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'added' ) . ' <a href="'.base_url().'deposits/deposit/' . $deposit . '">' . get_number('deposits',$deposit,'deposit','deposit'). '</a>.' ),
			'staff_id' => $loggedinuserid,
			'customer_id' => $this->input->post( 'customer' )
		) );
		return $deposit;
	}

	// Function to update deposits
	function update_deposit( $id, $params ) {
		$appconfig = get_appconfig();
		$deposit_data = $this->get_deposits($id,'');
		if($deposit_data['deposit_number']==''){
			$number = $appconfig['deposit_series'] ? $appconfig['deposit_series'] : $id;
			$deposit_number = $appconfig['deposit_prefix'].$number;
			$this->db->where('id',$id)->update('deposits',array('deposit_number'=>$deposit_number));
			if(($appconfig['deposit_series']!='')){
				$deposit_number = $appconfig['deposit_series'];
				$deposit_number = $deposit_number + 1;
				$this->Settings_Model->increment_series('deposit_series',$deposit_number);
			}
		}
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'deposits', $params );
		$items = $this->input->post( 'items' );
		$i = 0;
		foreach ( $items as $item ) {
			if ( isset( $item[ 'id' ] ) ) {
				$params = array(
					'relation_type' => 'deposit',
					'relation' => $id,
					'product_id' => $item[ 'product_id' ],
					'code' => $item[ 'code' ],
					'name' => $item[ 'name' ],
					'description' => $item[ 'description' ],
					'quantity' => $item[ 'quantity' ],
					'unit' => $item[ 'unit' ],
					'price' => $item[ 'price' ],
					'tax' => $item[ 'tax' ],
					'discount' => $item[ 'discount' ],
					'total' => $item[ 'quantity' ] * $item[ 'price' ] + ( ( $item[ 'tax' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ) - ( ( $item[ 'discount' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ),
				);
				$this->db->where( 'id', $item[ 'id' ] );
				$response = $this->db->update( 'items', $params );
			}
			if ( empty( $item[ 'id' ] ) ) {
				$this->db->insert( 'items', array(
					'relation_type' => 'deposit',
					'relation' => $id,
					'product_id' => $item[ 'product_id' ],
					'code' => $item[ 'code' ],
					'name' => $item[ 'name' ],
					'description' => $item[ 'description' ],
					'quantity' => $item[ 'quantity' ],
					'unit' => $item[ 'unit' ],
					'price' => $item[ 'price' ],
					'tax' => $item[ 'tax' ],
					'discount' => $item[ 'discount' ],
					'total' => $item[ 'quantity' ] * $item[ 'price' ] + ( ( $item[ 'tax' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ) - ( ( $item[ 'discount' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ),
				) );
			}
			$i++;
		};
		$loggedinuserid = $this->session->usr_id;
		$staffname = $this->session->staffname;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="'.base_url().'staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'updated' ) . ' <a href="'.base_url().'deposits/deposit/' . $id . '">' . get_number('deposits',$id,'deposit','deposit'). '</a>.' ),
			'staff_id' => $loggedinuserid,
			'customer_id' => $this->input->post( 'customer' )
		) );
	}

	// Function to delete deposits
	function delete_deposits( $id, $number ) {
		$response = $this->db->delete( 'deposits', array( 'id' => $id ) );
		$response = $this->db->delete( 'payments', array( 'deposit_id' => $id ) );
		$response = $this->db->delete( 'pending_process', array( 'process_relation' => $id, 'process_relation_type' => 'deposit') );
		// LOG
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="'.base_url().'staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'deleted' ). ' ' . $number),
			'staff_id' => $loggedinuserid
		) );
	}

	function get_depositcategory( $id ) {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get_where( 'depositcat', array( 'id' => $id ) )->row_array();
	}

	/* Get All deposit Categories */
	function get_all_depositcat() {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get( 'depositcat' )->result_array();
	}

	/* Add deposit Category */
	function add_category( $params ) {
		$this->db->insert( 'depositcat', $params );
		return $this->db->insert_id();
	}

	/* Update deposit Category */
	function update_category( $id, $params ) {
		$this->db->where( 'id', $id );
		return $this->db->update( 'depositcat', $params );
	}

	/* Delete deposit Category */
	function delete_category( $id ) {
		return $this->db->delete( 'depositcat', array( 'id' => $id ) );
	}

	/* Check deposit Category */
	function check_category($id) {
		$data = $this->db->get_where( 'deposits', array( 'category_id' => $id ) )->num_rows();
		return $data;
	}

	/* Check Total  deposit Amount */
	function depositsTotalAmount() {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'deposits' );
		$total_value = $this->db->get()->row()->amount;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	/* Check Number of Row For deposit  */
	function total_deposit_num($status) {
		$this->db->from( 'deposits' );
		$this->db->where('status' , $status);
		return $this->db->get()->num_rows();
	}

	/* Check Total Internal deposit  */
	function total_deposits($status) {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'deposits' );
		$this->db->where('status' , $status);
		$total_value = $this->db->get()->row()->amount;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	/* Add Recurring */
	function recurring_add( $params ) {
		$this->db->insert( 'recurring', $params );
		$sharax = $this->db->insert_id();
		return $sharax;
	}

	/* Get All Recurring */
	function get_all_recurring() { 
		$this->db->select( '*' );
		$this->db->order_by('id', 'asc');
		return $this->db->get_where( 'recurring', array( 'status' => '0', 'relation_type' => 'deposit' ) )->result_array();
	}

	/* Recurring Deposits Via Cron Job */
	function recurring_deposits( $params, $items ) {
		$this->db->insert( 'deposits', $params );
		$deposit = $this->db->insert_id();
		$appconfig = get_appconfig();
		$number = $appconfig['deposit_series'] ? $appconfig['deposit_series'] : $deposit;
		$deposit_number = $appconfig['deposit_prefix'].$number;
		$this->db->where('id', $deposit)->update( 'deposits', array('deposit_number' => $deposit_number ) );
		if($appconfig['deposit_series']){
			$deposit_number = $appconfig['deposit_series'];
			$deposit_number = $deposit_number + 1 ;
			$this->Settings_Model->increment_series('deposit_series',$deposit_number);
		}
		$loggedinuserid = 0;
		if ($params['status'] == '2') {
			$this->db->insert( 'payments', array(
				'transactiontype' => 0,
				'is_transfer' => 0,
				'deposit_id' => $deposit,
				'staff_id' => $loggedinuserid,
				'amount' => $params['amount'],
				'account_id' => $params['account_id'],
				'customer_id' => $params['customer_id']?$params['customer_id']:0,
				'not' => lang('incomings').' '.lang('for').' <a href="' . base_url( 'deposits/deposit/' . $deposit . '' ) . '">'. get_number('deposits',$deposit,'deposit','deposit') . '</a>',
				'date' => _pdate( date('Y-m-d') ),
			) );
		}
		$i = 0;
		foreach ( $items as $item ) {
			$this->db->insert( 'items', array(
				'relation_type' => 'deposit',
				'relation' => $deposit,
				'product_id' => $item[ 'product_id' ],
				'code' => $item[ 'code' ],
				'name' => $item[ 'name' ],
				'description' => $item[ 'description' ],
				'quantity' => $item[ 'quantity' ],
				'unit' => $item[ 'unit' ],
				'price' => $item[ 'price' ],
				'tax' => $item[ 'tax' ],
				'discount' => $item[ 'discount' ],
				'total' => $item[ 'quantity' ] * $item[ 'price' ] + ( ( $item[ 'tax' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ) - ( ( $item[ 'discount' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ),
			));
			$i++;
		};
		//LOG
		$staffname = lang( 'recurring_deposit' );
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="<'.base_url().'staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'added' ) . ' <a href="'.base_url().'deposits/deposit/' . $deposit . '">' . get_number('deposits',$deposit,'deposit','deposit') . ''  . '</a>.' ),
			'staff_id' => $loggedinuserid,
			'customer_id' => $this->input->post( 'customer' )
		) );
		return $deposit;
	}

	/* Update Recurring Deposits Date */
	function update_recurring_date($id) {
		$this->db->where( 'id', $id );
		return $this->db->update( 'deposits', array('last_recurring' => date('Y-m-d')) );
	}

	/* Update Recurring Deposits */
	function recurring_update( $id, $params ) {
		$this->db->where( 'relation', $id )->where('relation_type','deposit');
		$sharax = $this->db->update( 'recurring', $params );
		return $sharax;
	}

	function update_pdf_status($id, $value) {
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'deposits', array('pdf_status' => $value));
	}

	function generate_pdf( $id ) {
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
		if (!is_dir('uploads/files/deposits/'.$id)) {
			mkdir('./uploads/files/deposits/'.$id, 0777, true);
		}
		$data[ 'title' ] = '' .get_number('deposits', $id, 'deposit', 'deposit') . '';
		$data[ 'deposit' ] = $this->get_deposits( $id, '' );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data['state'] = get_state_name($data['settings']['state'],$data['settings']['state_id']);
		$data['customercountry'] = get_country($data[ 'deposit' ]['country_id']);
		$data['customerstate'] = get_state_name($data['deposit']['state'],$data['deposit']['state_id']);
		$data['country'] = get_country($data[ 'settings' ]['country_id']);
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'deposit', 'relation' => $id ) )->result_array();
		$html = $this->load->view( 'deposits/pdf', $data, TRUE );
		$file_name = '' . get_number('deposits', $id, 'deposit', 'deposit') . '.pdf';
		$this->dompdf = new DOMPDF();
		$this->dompdf->loadHtml( $html );
		$this->dompdf->set_option('isRemoteEnabled', TRUE );
		$this->dompdf->set_option('isHtml5ParserEnabled', TRUE );
		$this->dompdf->setPaper('A4', 'portrait' );
		$this->dompdf->render();
		$output = $this->dompdf->output();
		file_put_contents( 'uploads/files/deposits/'.$id.'/'.$file_name . '', $output ); 
		$this->update_pdf_status($id, '1');
		$html = null;
		$this->output->delete_cache();
		$this->dompdf->loadHtml(null);
		$this->dompdf = null;
		unset($this->dompdf);
		return true;
	}

	function get_deposit_by_privileges( $id, $staff_id='' ) { 
		$this->db->select( '*,customers.company as customer,customers.type as type,customers.namesurname as individual,customers.email as customeremail,customers.phone as customer_phone,customers.address as customeraddress,accounts.name as account,depositcat.name as category,staff.staffname as staff,staff.email as staffemail,deposits.description as desc, deposits.staff_id as depositstaff,deposits.id as id,deposits.status as deposit_status,deposits.created as depositcreate,recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate' ); 
		$this->db->join( 'customers', 'deposits.customer_id = customers.id', 'left' ); 
		$this->db->join( 'accounts', 'deposits.account_id = accounts.id', 'left' ); 
		$this->db->join( 'depositcat', 'deposits.category_id = depositcat.id', 'left' ); 
		$this->db->join( 'staff', 'deposits.staff_id = staff.id', 'left' ); 
		$this->db->join( 'recurring', "deposits.id = recurring.relation AND recurring.relation_type = 'deposit'", 'left' );
		$this->db->order_by( 'deposits.id', 'desc' ); 
		if($staff_id) {
			$this->db->where('(deposits_created_by='.$staff_id.' OR deposits.staff_id='.$staff_id.')');
			return $this->db->get_where( 'deposits', array( 'deposits.id' => $id ) )->row_array(); 	
		} else {
			return $this->db->get_where( 'deposits', array( 'deposits.id' => $id ) )->row_array();
		}
	} 

	function total_deposit_num_by_status($status, $staff_id) {
		$this->db->from( 'deposits' );
		$this->db->where('(deposits_created_by='.$staff_id.' OR deposits.staff_id='.$staff_id.')');
		$this->db->where('status' , $status);
		return $this->db->get()->num_rows();
	}

	/* Check Total Internal deposit  */
	function total_deposits_by_status($staff_id, $status='') {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'deposits' );
		if($status != '') {
			$this->db->where('status='.$status);
		} 
		$this->db->where('(deposits_created_by='.$staff_id.' OR deposits.staff_id='.$staff_id.')');
		$total_value = $this->db->get()->row()->amount;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}
}