<?php
include APPPATH . '/third_party/vendor/autoload.php';
use Dompdf\Dompdf;
class Invoices_Model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function get_invoices( $id ) {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.company as customercompany,customers.namesurname as individual,customers.address as customeraddress,customers.type as type,customers.email as email,customers.phone as customerphone,invoicestatus.name as statusname,invoices.status_id as status_id,invoices.created as created, invoices.id as id,invoices.billing_street as bill_street,invoices.billing_country as bill_country, invoices.billing_city as bill_city, invoices.billing_state as bill_state,invoices.billing_state_id as bill_state_id, invoices.billing_zip as bill_zip,invoices.shipping_street as shipp_street,invoices.shipping_country as shipp_country, invoices.shipping_city as shipp_city, invoices.shipping_state as shipp_state,invoices.shipping_state_id as shipp_state_id, invoices.shipping_zip as shipp_zip,recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate, invoices.invoiceId, invoices.default_payment_method as default_payment_method
		 ' );
		$this->db->join( 'customers', 'invoices.customer_id = customers.id', 'left' );
		$this->db->join( 'invoicestatus', 'invoices.status_id = invoicestatus.id', 'left' );
		$this->db->join( 'recurring', 'invoices.id = recurring.relation AND recurring.relation_type = "invoice"', 'left' );
		$this->db->join( 'staff', 'invoices.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'invoices', array( 'invoices.id' => $id ) )->row_array();
	}

	function get_invoices_by_token( $token ) {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.company as customercompany,customers.namesurname as individualindividual,customers.address as customeraddress,customers.email as email,customers.phone as customerphone,invoicestatus.name as statusname,invoices.status_id as status_id, invoices.created as created, invoices.id as id ,invoices.billing_street as bill_street,invoices.billing_country as bill_country, invoices.billing_city as bill_city, invoices.billing_state as bill_state,invoices.billing_state_id as bill_state_id, invoices.billing_zip as bill_zip,invoices.shipping_street as shipp_street,invoices.shipping_country as shipp_country, invoices.shipping_city as shipp_city, invoices.shipping_state as shipp_state,invoices.shipping_state_id as shipp_state_id, invoices.shipping_zip as shipp_zip, recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate, invoices.default_payment_method as default_payment_method' );
		$this->db->join( 'customers', 'invoices.customer_id = customers.id', 'left' );
		$this->db->join( 'invoicestatus', 'invoices.status_id = invoicestatus.id', 'left' );
		$this->db->join( 'recurring', 'invoices.id = recurring.relation AND recurring.relation_type = "invoice"', 'left' );
		$this->db->join( 'staff', 'invoices.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'invoices', array( 'invoices.token' => $token ) )->row_array();
	}

	function get_all_invoices() {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.company as customercompany,customers.namesurname as individual,customers.address as customeraddress,customers.email as email, customers.type as type, invoicestatus.name as statusname,invoices.status_id as status_id, invoices.created as created, invoices.id as id ,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate
		 ' );
		$this->db->join( 'customers', 'invoices.customer_id = customers.id', 'left' );
		$this->db->join( 'invoicestatus', 'invoices.status_id = invoicestatus.id', 'left' );
		$this->db->join( 'recurring', 'invoices.id = recurring.relation AND recurring.relation_type = "invoice"', 'left' );
		$this->db->join( 'staff', 'invoices.staff_id = staff.id', 'left' );
		$this->db->order_by( 'invoices.id', 'desc' );
		return $this->db->get_where( 'invoices', array( '' ) )->result_array();
	}

	function get_all_invoices_by_customer( $id ) {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.company as customercompany,customers.namesurname as individual,customers.address as customeraddress,customers.email as email,invoicestatus.name as statusname,invoices.status_id as status_id, invoices.created as created, invoices.id as id ,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate
		 ' );
		$this->db->join( 'customers', 'invoices.customer_id = customers.id', 'left' );
		$this->db->join( 'invoicestatus', 'invoices.status_id = invoicestatus.id', 'left' );
		$this->db->join( 'recurring', 'invoices.id = recurring.relation AND recurring.relation_type = "invoice"', 'left' );
		$this->db->join( 'staff', 'invoices.staff_id = staff.id', 'left' );
		$this->db->order_by( 'invoices.id', 'desc' );
		return $this->db->get_where( 'invoices', array( 'customer_id' => $id ) )->result_array();
	}

	function dueinvoices() {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.company as customercompany,customers.namesurname as individual,customers.address as customeraddress,customers.email as email,customers.type as type,invoicestatus.name as statusname, invoices.created as created, invoices.id as id,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate
		 ' );
		$this->db->join( 'customers', 'invoices.customer_id = customers.id', 'left' );
		$this->db->join( 'invoicestatus', 'invoices.status_id = invoicestatus.id', 'left' );
		$this->db->join( 'recurring', 'invoices.id = recurring.relation AND recurring.relation_type = "invoice"', 'left' );
		$this->db->join( 'staff', 'invoices.staff_id = staff.id', 'left' );
		$this->db->order_by( 'invoices.id', 'desc' );
		return $this->db->get_where( 'invoices', array( 'DATE(duedate)' => date( 'Y-m-d' ) ) )->result_array();
	}

	function dueinvoices_by_staff() {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.company as customercompany,customers.namesurname as individual,customers.address as customeraddress,customers.email as email,customers.type as type,invoicestatus.name as statusname, invoices.created as created, invoices.id as id,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate
		 ' );
		$this->db->join( 'customers', 'invoices.customer_id = customers.id', 'left' );
		$this->db->join( 'invoicestatus', 'invoices.status_id = invoicestatus.id', 'left' );
		$this->db->join( 'recurring', 'invoices.id = recurring.relation AND recurring.relation_type = "invoice"', 'left' );
		$this->db->join( 'staff', 'invoices.staff_id = staff.id', 'left' );
		$this->db->order_by( 'invoices.id', 'desc' );
		return $this->db->get_where( 'invoices', array( 'DATE(duedate)' => date( 'Y-m-d' ), 'invoices.staff_id' => $this->session->usr_id ) )->result_array();
	}

	function get_invoices_payment( $id ) {
		$this->db->select( '*' );
		$this->db->from( 'payments' );
		$this->db->join( 'accounts', 'accounts.id = payments.account_id' );
		$this->db->where( '(payments.invoice_id = ' . $id . ') ' );
		return $this->db->get()->result_array();
	}

	function overdueinvoices() {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.company as customercompany,customers.namesurname as individual,customers.address as customeraddress,customers.email as email,customers.type as type,invoicestatus.name as statusname, invoices.duedate, invoices.created as created, invoices.id as id ,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate
		 ' );
		$this->db->join( 'customers', 'invoices.customer_id = customers.id', 'left' );
		$this->db->join( 'invoicestatus', 'invoices.status_id = invoicestatus.id', 'left' );
		$this->db->join( 'recurring', 'invoices.id = recurring.relation AND recurring.relation_type = "invoice"', 'left' );
		$this->db->join( 'staff', 'invoices.staff_id = staff.id', 'left' );
		$this->db->where( 'CURDATE() > invoices.duedate AND invoices.duedate != "0000.00.00" AND invoices.status_id != "4" AND invoices.status_id != "2"' );
		$this->db->order_by( 'invoices.id', 'desc' );
		return $this->db->get( 'invoices' )->result_array();
	}

	function overdueinvoices_by_staff() {
		$this->db->select('*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.company as customercompany,customers.namesurname as individual,customers.address as customeraddress,customers.email as email,customers.type as type,invoicestatus.name as statusname, invoices.duedate, invoices.created as created, invoices.id as id ,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate');
		$this->db->join( 'customers', 'invoices.customer_id = customers.id', 'left' );
		$this->db->join( 'invoicestatus', 'invoices.status_id = invoicestatus.id', 'left' );
		$this->db->join( 'recurring', 'invoices.id = recurring.relation AND recurring.relation_type = "invoice"', 'left' );
		$this->db->join( 'staff', 'invoices.staff_id = staff.id', 'left' );
		$this->db->where( 'CURDATE() > invoices.duedate AND invoices.duedate != "0000.00.00" AND invoices.status_id != "4" AND invoices.status_id != "2"' );
		$this->db->order_by( 'invoices.id', 'desc' );
		return $this->db->get_where( 'invoices', array('invoices.staff_id' => $this->session->usr_id ) )->result_array();
	}

	function get_invoice_detail( $id ) {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.company as customercompany,customers.namesurname as individualindividual,customers.address as customeraddress,customers.email as email,customers.phone as customerphone,invoicestatus.name as statusname,invoices.status_id as status_id, invoices.created as created, invoices.id as id ,invoices.billing_street as bill_street,invoices.billing_country as bill_country, invoices.billing_city as bill_city, invoices.billing_state as bill_state,invoices.billing_state_id as bill_state_id, invoices.billing_zip as bill_zip,invoices.shipping_street as shipp_street,invoices.shipping_country as shipp_country, invoices.shipping_city as shipp_city, invoices.shipping_state as shipp_state,invoices.shipping_state_id as shipp_state_id, invoices.shipping_zip as shipp_zip,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate, invoices.default_payment_method as default_payment_method
		 ' );
		$this->db->join( 'customers', 'invoices.customer_id = customers.id', 'left' );
		$this->db->join( 'invoicestatus', 'invoices.status_id = invoicestatus.id', 'left' );
		$this->db->join( 'recurring', 'invoices.id = recurring.relation AND recurring.relation_type = "invoice"', 'left' );
		$this->db->join( 'staff', 'invoices.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'invoices', array( 'invoices.id' => $id ) )->row_array();
	}

	function get_items_invoices( $id ) {
		$this->db->select_sum( 'total' );
		$this->db->from( 'items' );
		$this->db->where( '(relation_type = "invoice" AND relation = ' . $id . ')' );
		return $this->db->get();
	}

	function get_paid_invoices( $id ) {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'payments' );
		$this->db->where( '(invoice_id = ' . $id . ') ' );
		return $this->db->get();
	}
	// ADD RECURRING

	function recurring_add( $params ) {
		$this->db->insert( 'recurring', $params );
		$sharax = $this->db->insert_id();
		return $sharax;
	}

	// END ADD RECURRING
	// UPDATE RECURRING

	function recurring_update( $id, $params ) {
		$this->db->where( 'relation', $id )->where( 'relation_type', 'invoice' );
		$sharax = $this->db->update( 'recurring', $params );
		return $sharax;
	}

	// END UPDATE RECURRING

	// GET ALL RECURRING
	function get_all_recurring() { 
		$this->db->select( '*' );
		$this->db->order_by('id', 'asc');
		return $this->db->get_where( 'recurring', array( 'status' => '0', 'relation_type' => 'invoice' ) )->result_array();
	}
	// END GET ALL RECURRING
	// Copy Invoice
	function recurring_invoice( $invoices, $items ) {
		$this->db->insert( 'invoices', $invoices );
		$invoice = $this->db->insert_id();
		$appconfig = get_appconfig();
		$number = $appconfig['invoice_series'] ? $appconfig['invoice_series'] : $invoice;
		$invoice_number = $appconfig['inv_prefix'].$number;
		$this->db->where('id', $invoice)->update( 'invoices', array('invoice_number' => $invoice_number ) );
		if($appconfig['invoice_series']){
			$invoice_number = $appconfig['invoice_series'];
			$invoice_number = $invoice_number + 1 ;
			$this->Settings_Model->increment_series('invoice_series',$invoice_number);
		}
		$loggedinuserid = 0;
		$i = 0;
		foreach ( $items as $item ) {
			$this->db->insert( 'items', array(
				'relation_type' => 'invoice',
				'relation' => $invoice,
				'product_id' => $item[ 'product_id' ],
				'code' => $item[ 'code' ],
				'name' => $item[ 'name' ],
				'description' => $item[ 'description' ],
				'quantity' => $item[ 'quantity' ],
				'unit' => $item[ 'unit' ],
				'price' => $item[ 'price' ],
				'tax' => $item[ 'tax' ],
				'discount' => $item[ 'discount' ],
				'total' => $item[ 'total' ],
			) );
			$i++;
		};
		//LOG
		$staffname = lang( 'recurring_invoice' );
		$appconfig = get_appconfig();
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="#"> ' . $staffname . '</a> ' . lang( 'added' ) . ' <a href="invoices/invoice/' . $invoice . '">' . get_number('invoices',$invoice,'invoice','inv') .  '</a>.' ),
			'staff_id' => $loggedinuserid,
			'customer_id' => $invoices[ 'customer_id' ]
		) );
		//NOTIFICATION
		$staffavatar = 'defualt-avatar.jpg';
		$this->db->insert( 'notifications', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '' . $staffname . ' ' . lang( 'isaddedanewinvoice' ) . '' ),
			'customer_id' => $invoices[ 'customer_id' ],
			'perres' => $staffavatar,
			'target' => '' . base_url( 'area/invoice/' . $invoice . '' ) . ''
		) );
		//--------------------------------------------------------------------------------------
		$status = 3;
		$this->db->insert( $this->db->dbprefix . 'sales', array(
			'invoice_id' => '' . $invoice . '',
			'status_id' => $status,
			'staff_id' => $loggedinuserid,
			'customer_id' => $invoices[ 'customer_id' ],
			'total' => $invoices[ 'total' ],
			'date' => date( 'Y-m-d H:i:s' )
		) );
		//----------------------------------------------------------------------------------------
		return $invoice;
	}

	function update_recurring_date($id) {
		$this->db->where( 'id', $id );
		return $this->db->update( 'invoices', array('last_recurring' => date('Y-m-d')) );
	}
	// END Copy Invoice
	// ADD INVOICE
	function invoice_add( $params ) {
		$this->db->insert( 'invoices', $params );
		$invoice = $this->db->insert_id();
		$appconfig = get_appconfig();
		$number = $appconfig['invoice_series'] ? $appconfig['invoice_series'] : $invoice;
		$invoice_number = $appconfig['inv_prefix'].$number;
		$this->db->where('id', $invoice)->update( 'invoices', array('invoice_number' => $invoice_number ) );
		if ( $this->input->post( 'status' ) == 'true' ) {
			$loggedinuserid = $this->session->usr_id;
			$this->db->insert( 'deposits', array(
					'token' => md5( uniqid() ),
					'relation_type' => 'invoice',
					'category_id' => $this->Invoices_Model->get_category_id(), 
					'staff_id' => $loggedinuserid,
					'customer_id' => $this->input->post( 'customer' ),
					'invoice_id' =>  $invoice,
					'account_id' => $this->input->post( 'account' ),
					'title' => lang('invoice'),
					'date' => date('Y-m-d'),
					'created' => date( 'Y-m-d H:i:s' ),
					'amount' => $this->input->post( 'total' ),
					'total_tax' => '0',
					'sub_total' => $this->input->post( 'total' ),
					'status' => '2',
					'description' =>'' . $message = sprintf( lang( 'paymentfor' ), $invoice ) . '',
					'last_recurring' => date('Y-m-d'),
			) );
			$this->db->insert( 'payments', array(
				'transactiontype' => 0,
				'is_transfer' => 0,
				'invoice_id' => $invoice,
				'staff_id' => $loggedinuserid,
				'amount' => $this->input->post( 'total' ),
				'customer_id' => $this->input->post( 'customer' ),
				'account_id' => $this->input->post( 'account' ),
				'not' => '' . $message = sprintf( lang( 'paymentfor' ), $invoice ) . '',
				'date' => date('Y-m-d H:i:s')
			) );
		}
		$items = $this->input->post( 'items', TRUE );
		$i = 0;
		foreach ( $items as $item ) {
			$this->db->insert( 'items', array(
				'relation_type' => 'invoice',
				'relation' => $invoice,
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
			$i++;
		};
		//LOG
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'added' ) . ' <a href="invoices/invoice/' . $invoice . '">' . get_number('invoices',$invoice,'invoice','inv') . '</a>.' ),
			'staff_id' => $loggedinuserid,
			'customer_id' => $this->input->post( 'customer' )
		) );
		//NOTIFICATION
		$staffname = $this->session->staffname;
		$staffavatar = $this->session->staffavatar;
		$this->db->insert( 'notifications', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '' . $staffname . ' ' . lang( 'isaddedanewinvoice' ) . '' ),
			'customer_id' => $this->input->post( 'customer' ),
			'perres' => $staffavatar,
			'target' => '' . base_url( 'area/invoices/invoice/' . $params['token'] . '' ) . ''
		) );
		//--------------------------------------------------------------------------------------
		$status_value = $this->input->post( 'status' );
		if ( $status_value == 'true' ) {
			$status = 2;
		} else {
			$status = 3;
		}
		$this->db->insert( $this->db->dbprefix . 'sales', array(
			'invoice_id' => '' . $invoice . '',
			'status_id' => $status,
			'staff_id' => $loggedinuserid,
			'customer_id' => $this->input->post( 'customer' ),
			'total' => $this->input->post( 'total' ),
			'date' => date( 'Y-m-d H:i:s' )
		) );
		//----------------------------------------------------------------------------------------
		return $invoice;
	}

	// UPDATE INVOCE
	function update_invoices( $id, $params ) {
		
		$appconfig = get_appconfig();
		$invoice_data = $this->get_invoices($id);
		if($invoice_data['invoice_number']==''){
			$number = $appconfig['invoice_series'] ? $appconfig['invoice_series'] : $id;
			$invoice_number = $appconfig['inv_prefix'].$number;
			$this->db->where('id',$id)->update('invoices',array('invoice_number'=>$invoice_number));
			if(($appconfig['invoice_series']!='')){
				$invoice_number = $appconfig['invoice_series'];
				$invoice_number = $invoice_number + 1;
				$this->Settings_Model->increment_series('invoice_series',$invoice_number);
			}
		}
		$this->db->where( 'id', $id );
		$invoice = $id;
		$response = $this->db->update( 'invoices', $params );
		$items = $this->input->post( 'items' );
		$i = 0;
		foreach ( $items as $item ) {
			if ( isset( $item[ 'id' ] ) ) {
				$params = array(
					'relation_type' => 'invoice',
					'relation' => $invoice,
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
					'relation_type' => 'invoice',
					'relation' => $invoice,
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
		$invoices = $this->Invoices_Model->get_invoices( $id );
		$response = $this->db->where( 'invoice_id', $id )->update( 'sales', array(
			'status_id' => $invoices[ 'status_id' ],
			'staff_id' => $this->session->usr_id,
			'customer_id' => $this->input->post( 'customer' ),
			'total' => $this->input->post( 'total' ),
		) );
		//LOG
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$appconfig = get_appconfig();
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'updated' ) . ' <a href="invoices/invoice/' . $id . '">' . get_number('invoices',$id,'invoice','inv') . '</a>.' ),
			'staff_id' => $loggedinuserid,
			'customer_id' => $this->input->post( 'customer' )
		) );
		//NOTIFICATION
		$staffname = $this->session->staffname;
		$staffavatar = $this->session->staffavatar;
		$this->db->insert( 'notifications', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '' . $staffname . ' ' . lang( 'uptdatedinvoice' ) . '' ),
			'customer_id' => $this->input->post( 'customer' ),
			'perres' => $staffavatar,
			'target' => '' . base_url( 'area/invoice/' . $invoice . '' ) . ''
		) );
	}

	function copy_invoice( $params, $items, $total ) {
		$this->db->insert( 'invoices', $params );
		$invoice = $this->db->insert_id();
		$i = 0;
		foreach ( $items as $item ) {
			$this->db->insert( 'items', array(
				'relation_type' => 'invoice',
				'relation' => $invoice,
				'product_id' => $item[ 'product_id' ],
				'code' => $item[ 'code' ],
				'name' => $item[ 'name' ],
				'description' => $item[ 'description' ],
				'quantity' => $item[ 'quantity' ],
				'unit' => $item[ 'unit' ],
				'price' => $item[ 'price' ],
				'tax' => $item[ 'tax' ],
				'tax_code' => $item[ 'tax_code' ],
				'discount' => $item[ 'discount' ],
				'total' => $item[ 'quantity' ] * $item[ 'price' ] + ( ( $item[ 'tax' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ) - ( ( $item[ 'discount' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ),
			) );
			$i++;
		};
		//LOG
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$appconfig = get_appconfig();
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'added' ) . ' <a href="invoices/invoice/' . $invoice . '">' . get_number('invoices',$invoice,'invoice','inv'). '</a>.' ),
			'staff_id' => $loggedinuserid,
			'customer_id' => $params['customer_id']
		) );
		//NOTIFICATION
		$staffname = $this->session->staffname;
		$staffavatar = $this->session->staffavatar;
		$this->db->insert( 'notifications', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '' . $staffname . ' ' . lang( 'isaddedanewinvoice' ) . '' ),
			'customer_id' => $params['customer_id'],
			'perres' => $staffavatar,
			'target' => '' . base_url( 'area/invoice/' . $invoice . '' ) . ''
		) );
		//--------------------------------------------------------------------------------------
		$status_value = $params['status_id'];
		if ( $status_value == 'true' ) { 
			$status = 2;
		} else {
			$status = 3;
		}
		$this->db->insert( $this->db->dbprefix . 'sales', array(
			'invoice_id' => '' . $invoice . '',
			'status_id' => $status,
			'staff_id' => $loggedinuserid,
			'customer_id' => $params['customer_id'],
			'total' => $params['total'],
			'date' => $params['created']
		) );
		//----------------------------------------------------------------------------------------
		return $invoice;
	}

	//INVOICE DELETE
	function delete_invoices( $id, $number ) {
		$response = $this->db->delete( 'invoices', array( 'id' => $id ) );
		$response = $this->db->delete( 'items', array( 'relation_type' => 'invoice', 'relation' => $id ) );
		$response = $this->db->delete( 'payments', array( 'invoice_id' => $id ) );
		$response = $this->db->delete( 'sales', array( 'invoice_id' => $id ) );
		$response = $this->db->delete( 'recurring', array( 'relation_type' => 'invoice', 'relation' => $id ) );
		$response = $this->db->where( 'invoice_id', $id )->update( 'expenses', array( 'invoice_id' => null ) );
		$response = $this->db->delete('deposits', array('relation_type' => 'invoice', 'invoice_id' => $id));
		$response = $this->db->delete( 'pending_process', array('process_relation' => $id, 'process_relation_type' => 'invoice'));
		// LOG
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'deleted' ) . ' ' . $number . '' ),
			'staff_id' => $loggedinuserid
		) );
	}

	function get_invoice_year() {
		return $this->db->query( 'SELECT DISTINCT(YEAR(date)) as year FROM invoices ORDER BY year DESC' )->result_array();
	}


	function update_pdf_status($id, $value){
		$this->db->where('id', $id);
		$response = $this->db->update('invoices',array('pdf_status' => $value));
	}

	function generate_pdf($id) { 
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
		if (!is_dir('uploads/files/invoices/'.$id)) {
			mkdir('./uploads/files/invoices/'.$id, 0777, true);
		}
		$data['invoice'] = $this->get_invoice_detail($id);
		$data['billing_country'] = get_country($data[ 'invoice' ]['bill_country']);
		$data['billing_state'] = get_state_name($data['invoice']['bill_state'],$data['invoice']['bill_state_id']);
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data['state'] = get_state_name($data['settings']['state'],$data['settings']['state_id']);
		$data['country'] = get_country($data[ 'settings' ]['country_id']);
		$dafault_payment_method = $data['invoice']['default_payment_method'];
		if ($dafault_payment_method == 'bank') {
			$modes = $this->Settings_Model->get_payment_gateway_data();
			$method = $modes['bank'];
		} else {
			$method = lang($data['invoice']['default_payment_method']);
		}
		$data['default_payment'] = $method;
		$data[ 'payments' ] = $this->Invoices_Model->get_invoices_payment($id);
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'invoice', 'relation' => $id ) )->result_array();
		$file_name = get_number('invoices', $id, 'invoice', 'inv'). '.pdf';
		$html = $this->load->view('invoices/pdf', $data, TRUE);
		$this->dompdf = new DOMPDF();
		$this->dompdf->loadHtml( $html );
		$this->dompdf->set_option( 'isRemoteEnabled', TRUE );
		$this->dompdf->set_option('isHtml5ParserEnabled', TRUE );
		$this->dompdf->setPaper( 'A4', 'portrait' );
		$this->dompdf->render();
		$output = $this->dompdf->output();
		$result = file_put_contents( 'uploads/files/invoices/'. $id . '/' . $file_name . '', $output );
		$this->update_pdf_status($id, '1');
		$html = null;
		$this->output->delete_cache();
		$this->dompdf->loadHtml(null);
		$this->dompdf = null;
		unset($this->dompdf);
		return true; 
	}

	function get_category_id() {
		$this->db->select( 'id' );
		$this->db->from( 'depositcat' );
		$this->db->where('name',lang('invoice'));
		$query=$this->db->get();
		if ($query->num_rows()>0) {
			$data=$query->row();
		
			return $data->id;
		} else {
			$params = array(
				'name' => lang('invoice'),
				'description' => lang('invoice'),
			);
			$this->db->insert( 'depositcat', $params );
			return $this->db->insert_id();
		}
	}

	function get_all_invoices_by_privileges($staff_id='') {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.company as customercompany,customers.namesurname as individual,customers.address as customeraddress,customers.email as email, customers.type as type, invoicestatus.name as statusname,invoices.status_id as status_id, invoices.created as created, invoices.id as id ,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate
		 ' );
		$this->db->join( 'customers', 'invoices.customer_id = customers.id', 'left' );
		$this->db->join( 'invoicestatus', 'invoices.status_id = invoicestatus.id', 'left' );
		$this->db->join( 'recurring', 'invoices.id = recurring.relation AND recurring.relation_type = "invoice"', 'left' );
		$this->db->join( 'staff', 'invoices.staff_id = staff.id', 'left' );
		$this->db->order_by( 'invoices.id', 'desc' );
		if($staff_id) {
			return $this->db->get_where( 'invoices', array( 'invoices.staff_id' => $staff_id ) )->result_array();
		} else {
			return $this->db->get_where( 'invoices', array( '' ) )->result_array();
		}
	}

	function get_invoice_detail_by_privilegs( $id, $staff_id='' ) {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.company as customercompany,customers.namesurname as individualindividual,customers.address as customeraddress,customers.email as email,customers.phone as customerphone,invoicestatus.name as statusname,invoices.status_id as status_id, invoices.created as created, invoices.id as id ,invoices.billing_street as bill_street,invoices.billing_country as bill_country, invoices.billing_city as bill_city, invoices.billing_state as bill_state,invoices.billing_state_id as bill_state_id, invoices.billing_zip as bill_zip,invoices.shipping_street as shipp_street,invoices.shipping_country as shipp_country, invoices.shipping_city as shipp_city, invoices.shipping_state as shipp_state,invoices.shipping_state_id as shipp_state_id, invoices.shipping_zip as shipp_zip,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate, invoices.default_payment_method as default_payment_method
		 ' );
		$this->db->join( 'customers', 'invoices.customer_id = customers.id', 'left' );
		$this->db->join( 'invoicestatus', 'invoices.status_id = invoicestatus.id', 'left' );
		$this->db->join( 'recurring', 'invoices.id = recurring.relation AND recurring.relation_type = "invoice"', 'left' );
		$this->db->join( 'staff', 'staff.id = invoices.staff_id ', 'left' );
		if($staff_id) {
			return $this->db->get_where( 'invoices', array( 'invoices.id' => $id, 'invoices.staff_id' => $this->session->usr_id ) )->row_array();
		} else {
			return $this->db->get_where( 'invoices', array( 'invoices.id' => $id ) )->row_array();
		}
	}
}