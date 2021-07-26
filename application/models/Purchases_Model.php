<?php
include APPPATH . '/third_party/vendor/autoload.php';
use Dompdf\Dompdf;
class Purchases_Model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function get_appconfig() {
		$configs = $this->db->get_where('appconfig', array())->result_array();
		$data = array();
		foreach ($configs as $config) {
			$data[$config['name']] = $config['value'];
		}
		echo json_encode($data);
	}

	function get_purchases( $id ) {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,vendors.company as vendorcompany,vendors.phone as vendor_phone,vendors.address as vendoraddress,vendors.email as email,purchases.status_id as status_id,purchases.created as created, purchases.id as id,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate
		 ' );
		$this->db->join( 'vendors', 'purchases.vendor_id = vendors.id', 'left' );
		//$this->db->join( 'purchasestatus', 'purchases.status_id = purchasestatus.id', 'left' );
		$this->db->join( 'recurring', 'purchases.id = recurring.relation AND recurring.relation_type = "purchase"', 'left' );
		$this->db->join( 'staff', 'purchases.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'purchases', array( 'purchases.id' => $id ) )->row_array();
	}

	function get_purchases_by_token( $token ) {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,vendors.company as vendorcompany,vendors.address as vendoraddress,vendors.email as email,vendors.phone as vendor_phone,purchases.status_id as status_id, purchases.created as created, purchases.id as id,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate
		 ' );
		$this->db->join( 'vendors', 'purchases.vendor_id = vendors.id', 'left' );
		//$this->db->join( 'purchasestatus', 'purchases.status_id = purchasestatus.id', 'left');
		$this->db->join( 'recurring', 'purchases.id = recurring.relation AND recurring.relation_type = "purchase"', 'left' );
		$this->db->join( 'staff', 'purchases.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'purchases', array( 'purchases.token' => $token ) )->row_array();
	}

	function get_all_purchases() {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,vendors.company as vendorcompany,vendors.address as vendoraddress,vendors.phone as vendor_phone,vendors.email as email,purchases.status_id as status_id, purchases.created as created, purchases.id as id ,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate
		 ' );
		$this->db->join( 'vendors', 'purchases.vendor_id = vendors.id', 'left' );
		$this->db->join( 'recurring', 'purchases.id = recurring.relation AND recurring.relation_type = "purchase"', 'left' );
		$this->db->join( 'staff', 'purchases.staff_id = staff.id', 'left' );
		
		$this->db->order_by( 'purchases.id', 'desc' );
		return $this->db->get_where( 'purchases', array( '' ) )->result_array();
	}

	function get_all_purchases_by_customer( $id ) {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,vendors.company as vendorcompany,vendors.address as vendoraddress,vendors.phone as vendor_phone,vendors.email as email,invoices.status_id as status_id, invoices.created as created, invoices.id as id ,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate
		 ' );
		$this->db->join( 'vendors', 'purchases.vendors_id = vendors.id', 'left' );
		$this->db->join( 'recurring', 'purchases.id = recurring.relation AND recurring.relation_type = "purchase"', 'left' );
		$this->db->join( 'staff', 'purchases.staff_id = staff.id', 'left' );
		$this->db->order_by( 'purchases.id', 'desc' );
		return $this->db->get_where( 'purchases', array( 'vendors_id' => $id ) )->result_array();
	}

	function duepurchases() {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,vendors.company as vendorcompany,vendors.address as vendoraddress,vendors.phone as vendor_phone,vendors.email as email,vendors.type as type, purchases.created as created, purchases.id as id,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate
		 ' );
		$this->db->join( 'vendors', 'purchases.vendor_id = vendors.id', 'left' );
		//$this->db->join( 'purchasestatus', 'purchases.status_id = purchasestatus.id', 'left' );
		$this->db->join( 'recurring', 'purchases.id = recurring.relation AND recurring.relation_type = "invoice"', 'left' );
		$this->db->join( 'staff', 'purchases.staff_id = staff.id', 'left' );
		$this->db->order_by( 'purchases.id', 'desc' );
		return $this->db->get_where( 'purchases', array( 'DATE(duedate)' => date( 'Y-m-d' ) ) )->result_array();
	}

	function overduepurchases() {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,vendors.company as vendorcompany,vendors.address as vendoraddress,vendors.phone as vendor_phone,vendors.email as email,vendors.type as type, purchases.created as created, purchases.id as id ,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate
		 ' );
		$this->db->join( 'vendors', 'purchases.vendor_id = vendors.id', 'left' );
		//$this->db->join( 'purchasestatus', 'purchases.status_id = purchasestatus.id', 'left' );
		$this->db->join( 'recurring', 'purchases.id = recurring.relation AND recurring.relation_type = "invoice"', 'left' );
		$this->db->join( 'staff', 'purchases.staff_id = staff.id', 'left' );
		$this->db->where( 'CURDATE() > purchases.duedate AND purchases.duedate != "0000.00.00" AND purchases.status_id != "4" AND purchases.status_id != "2"' );
		$this->db->order_by( 'purchases.id', 'desc' );
		return $this->db->get( 'purchases' )->result_array();
	}

	function get_purchases_detail( $id ) {
		$this->db->select( '*,IFNULL(vendors.type, 0) as type,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,vendors.company as vendorcompany,vendors.address as vendoraddress,vendors.country_id as vendorcountry,vendors.state as vendorstate,vendors.city as vendorcity,vendors.town as vendortown,vendors.zipcode as vendorzip,vendors.phone as vendor_phone,vendors.email as email,purchases.status_id as status_id,,purchases.created as created, purchases.id as id , recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate' );
		$this->db->join( 'vendors', 'purchases.vendor_id = vendors.id', 'left' );
		//$this->db->join( 'purchasestatus', 'purchases.status_id = purchasestatus.id', 'left' );
		$this->db->join( 'recurring', 'purchases.id = recurring.relation AND recurring.relation_type = "purchase"', 'left' );
		$this->db->join( 'staff', 'purchases.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'purchases', array( 'purchases.id' => $id ) )->row_array();
	}

	function get_items_purchases( $id ) {
		$this->db->select_sum( 'total' );
		$this->db->from( 'items' );
		$this->db->where( '(relation_type = "purchase" AND relation = ' . $id . ')' );
		return $this->db->get();
	}

	function get_paid_purchases( $id ) {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'payments' );
		$this->db->where( '(purchase_id = ' . $id . ') ' );
		return $this->db->get();
	}

	function get_items_detail( $id ) {
		$this->db->select('*');
		$this->db->from('items');
		$this->db->where( '(items.relation_type = "purchase" AND items.relation = ' . $id . ')' );
		return $this->db->get()->result_array();
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
		$this->db->where( 'relation', $id )->where('relation_type','purchase');
		$sharax = $this->db->update( 'recurring', $params );
		return $sharax;
	}

	// END UPDATE RECURRING

	// GET ALL RECURRING
	function get_all_recurring() { 
		$this->db->select( '*' );
		$this->db->order_by('id', 'asc');
		return $this->db->get_where( 'recurring', array( 'status' => '0', 'relation_type' => 'purchase' ) )->result_array();
	}
	// END GET ALL RECURRING
	// Copy Invoice
	function recurring_purchases( $purchases, $items) {
		// p($purchases);die;
		$this->db->insert( 'purchases', $purchases );
		$purchase = $this->db->insert_id();
		$appconfig = get_appconfig();
		$number = $appconfig['purchase_series'] ? $appconfig['purchase_series'] : $purchase;
		$purchase_number = $appconfig['purchase_prefix'].$number;
		$this->db->where('id', $purchase)->update( 'purchases', array('purchase_number' => $purchase_number ) );
		if($appconfig['purchase_series']){
			$purchase_number = $appconfig['purchase_series'];
			$purchase_number = $purchase_number + 1 ;
			$this->Settings_Model->increment_series('purchase_series',$purchase_number);
		}
			$loggedinuserid = 0;
		$i = 0;
		foreach ( $items as $item ) {
			$this->db->insert( 'items', array(
				'relation_type' => 'purchase',
				'relation' => $purchase,
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
		$appconfig = get_appconfig();
		$staffname = 'Ciuis CRM Recurring';
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="#"> ' . $staffname . '</a> ' . lang( 'added' ) . ' <a href="purchases/purchase/' . $purchase . '">' . get_number('purchases',$purchase,'purchase','purchase'). '</a>.' ),
			'staff_id' => $loggedinuserid,
			'vendor_id' => $purchases['vendor_id']
		) );
		//NOTIFICATION
		$staffavatar = 'defualt-avatar.jpg';
		$this->db->insert( 'notifications', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '' . $staffname . ' ' . lang( 'isaddedanewinvoice' ) . '' ),
			'vendor_id' => $purchases['vendor_id'],
			'perres' => $staffavatar,
			'target' => '' . base_url( 'area/purchase/' . $purchase . '' ) . ''
		) );
		//--------------------------------------------------------------------------------------
			$status = 3;
		$this->db->insert( $this->db->dbprefix . 'vendor_sales', array(
			'purchase_id' => $purchase,
			'status_id' => $status,
			'staff_id' => $loggedinuserid,
			'vendor_id' => $purchases['vendor_id'],
			'total' => $purchases['total'],
			'date' => date( 'Y-m-d H:i:s' )
		) );
		//----------------------------------------------------------------------------------------
		return $purchase;
	}

	function update_recurring_date($id) {
		$this->db->where( 'id', $id );
		return $this->db->update( 'purchases', array('last_recurring' => date('Y-m-d')) );
	}

	// END Copy Invoice
	// ADD INVOICE
	function purchases_add( $params ) {
		$this->db->insert( 'purchases', $params );
		$purchase = $this->db->insert_id();
		$appconfig = get_appconfig();
		$number = $appconfig['purchase_series'] ? $appconfig['purchase_series'] : $purchase;
		$purchase_number = $appconfig['purchase_prefix'].$number;
		$this->db->where('id', $purchase)->update( 'purchases', array('purchase_number' => $purchase_number ) );
		if ( $this->input->post( 'status' ) == 'false' ) {
			$loggedinuserid = $this->session->usr_id;
			$this->db->insert( 'payments', array(
				'purchase_id' => $purchase,
				'staff_id' => $loggedinuserid,
				'amount' => $this->input->post( 'total' ),
				'vendor_id' => $this->input->post( 'vendor' ),
				'account_id' => $this->input->post( 'account' ),
				'not' => '' . $message = sprintf( lang( 'paymentfor' ), $purchase ) . '',
				'date' => $this->input->post( 'datepayment' )
			) );
		}
		$items = $this->input->post( 'items' );
		$i = 0;
		foreach ( $items as $item ) {
			$this->db->insert( 'items', array(
				'relation_type' => 'purchase',
				'relation' => $purchase,
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
		$appconfig = get_appconfig();
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'added' ) . ' <a href="purchases/purchase/' . $purchase . '">' . get_number('purchases',$purchase,'purchase','purchase'). '</a>.' ),
			'staff_id' => $loggedinuserid,
			'vendor_id' => $this->input->post( 'vendor' )
		) );
		//NOTIFICATION
		$staffname = $this->session->staffname;
		$staffavatar = $this->session->staffavatar;
		$this->db->insert( 'notifications', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '' . $staffname . ' ' . lang( 'isaddedanewpurchase' ) . '' ),
			'vendor_id' => $this->input->post( 'vendor' ),
			'perres' => $staffavatar,
			'target' => '' . base_url( 'area/purchases/' . $purchase . '' ) . ''
		) );
		//--------------------------------------------------------------------------------------
		$status_value = $this->input->post( 'status' );
		if ( $status_value == 'true' ) {
			$status = 2;
		} else {
			$status = 3;
		}
		//----------------------------------------------------------------------------------------
		return $purchase;
	}

	// UPDATE PURCHASE
	function update_purchases( $id, $params ) {
		$appconfig = get_appconfig();
		$purchase_data = $this->get_purchases($id);
		if($purchase_data['purchase_number']==''){
			$number = $appconfig['purchase_series'] ? $appconfig['purchase_series'] : $id;
			$purchase_number = $appconfig['purchase_prefix'].$number;
			$this->db->where('id',$id)->update('purchases',array('purchase_number'=>$purchase_number));
			if(($appconfig['purchase_series']!='')){
				$purchase_number = $appconfig['purchase_series'];
				$purchase_number = $purchase_number + 1;
				$this->Settings_Model->increment_series('purchase_series',$purchase_number);
			}
		}
		$this->db->where( 'id', $id );
		$purchase = $id;
		$response = $this->db->update( 'purchases', $params );
		$items = $this->input->post( 'items' );
		$i = 0;
		foreach ( $items as $item ) {
			if ( isset( $item[ 'id' ] ) ) { 
				$params = array(
					'relation_type' => 'purchase',
					'relation' => $purchase,
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
					'relation_type' => 'purchase',
					'relation' => $purchase,
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
		$purchases = $this->Purchases_Model->get_purchases( $id );
		$response = $this->db->where( 'purchase_id', $id )->update( 'vendor_sales', array(
			'status_id' => $purchases[ 'status_id' ],
			'staff_id' => $this->session->usr_id,
			'vendor_id' => $this->input->post( 'vendor' ),
			'total' => $this->input->post( 'total' ),
		) );
		//LOG
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$appconfig = get_appconfig();
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'updated' ) . ' <a href="purchases/purchase/' . $id . '">' . get_number('purchases',$id,'purchase','purchase') . '</a>.' ),
			'staff_id' => $loggedinuserid,
			'vendor_id' => $this->input->post( 'vendor' )
		) );
		//NOTIFICATION
		$staffname = $this->session->staffname;
		$staffavatar = $this->session->staffavatar;
		$this->db->insert( 'notifications', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '' . $staffname . ' ' . lang( 'updated' ).' '.lang('purchase') . '' ),
			'vendor_id' => $this->input->post( 'vendor' ),
			'perres' => $staffavatar,
			'target' => '' . base_url( 'area/purchases/' . $purchase . '' ) . ''
		) );
	}

	//INVOICE DELETE
	function delete_purchases( $id, $number ) {
		$appconfig = get_appconfig();
		$response = $this->db->delete( 'purchases', array( 'id' => $id ) );
		$response = $this->db->delete( 'items', array( 'relation_type' => 'purchase', 'relation' => $id ) );
		$response = $this->db->delete( 'payments', array( '	purchase_id' => $id ) );
		$response = $this->db->delete( 'expenses', array( '	purchase_id' => $id ) );
		$response = $this->db->delete( 'pending_process', array( 'process_relation' => $id, 'process_relation_type' => 'purchase'));
		$file_name = '' . get_number('purchases',$id,'purchase','purchase').'.pdf';
		$file = './assets/files/generated_pdf_files/purchases/' . $file_name;
		if(is_file($file)){
			unlink($file);
		}
		// LOG
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$appconfig = get_appconfig();
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'deleted' ) . ' ' . $number . '' ),
			'staff_id' => $loggedinuserid
		) );

		return true;
	}

	function get_purchases_year() {
		return $this->db->query( 'SELECT DISTINCT(YEAR(date)) as year FROM purchases ORDER BY year DESC' )->result_array();
	}

	function get_category_id() {
		$this->db->select( 'id' );
		$this->db->from( 'expensecat' );
		$this->db->where('name',lang('purchase'));
		$query=$this->db->get();
		if($query->num_rows()>0){
			$data=$query->row();
		
			return $data->id;
		}else{
			$params = array(
				'name' => lang('purchase'),
				'description' => lang('purchase'),
			);
			$this->db->insert( 'expensecat', $params );
			return $this->db->insert_id();
		}
	}

	function update_pdf_status($id, $value){
		$this->db->where('id', $id);
		$response = $this->db->update('purchases',array('pdf_status' => $value));
	}

	function generate_pdf($id) {
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
		if (!is_dir('uploads/files/purchases/'.$id)) {
			mkdir('./uploads/files/purchases/'.$id, 0777, true);
		}
		$data[ 'title' ] = '' .get_number('purchases', $id, 'purchase', 'purchase') . '';
		$data[ 'purchase' ] = $this->Purchases_Model->get_purchases_detail( $id );
		$data['vendor_country'] = get_country($data[ 'purchase' ]['vendorcountry']);
		$data['vendor_state'] = get_state_name(' ',$data['purchase']['vendorstate']);
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data['state'] = get_state_name($data['settings']['state'],$data['settings']['state_id']);
		$data['country'] = get_country($data[ 'settings' ]['country_id']);
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'purchase', 'relation' => $id ) )->result_array();
		$file_name = '' . get_number('purchases', $id, 'purchase', 'purchase'). '.pdf';
		$html = $this->load->view('purchases/pdf', $data, TRUE);
		$this->dompdf = new DOMPDF();
		$this->dompdf->loadHtml( $html );
		$this->dompdf->set_option( 'isRemoteEnabled', TRUE );
		$this->dompdf->set_option('isHtml5ParserEnabled', TRUE );
		$this->dompdf->setPaper( 'A4', 'portrait' );
		$this->dompdf->render();
		$output = $this->dompdf->output();
		$result = file_put_contents( 'uploads/files/purchases/'. $id .'/' . $file_name . '', $output );
		$this->update_pdf_status($id, '1');
		$html = null;
		$this->output->delete_cache();
		$this->dompdf->loadHtml(null);
		$this->dompdf = null;
		unset($this->dompdf);
		return true; 
	}

	function get_all_purchases_by_privileges($staff_id='') {
		$this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,vendors.company as vendorcompany,vendors.address as vendoraddress,vendors.phone as vendor_phone,vendors.email as email,purchases.status_id as status_id, purchases.created as created, purchases.id as id ,
			recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate
		 ' );
		$this->db->join( 'vendors', 'purchases.vendor_id = vendors.id', 'left' );
		$this->db->join( 'recurring', 'purchases.id = recurring.relation AND recurring.relation_type = "purchase"', 'left' );
		$this->db->join( 'staff', 'purchases.staff_id = staff.id', 'left' );
		
		$this->db->order_by( 'purchases.id', 'desc' );
		if($staff_id) {
			return $this->db->get_where( 'purchases', array( 'purchases.staff_id' => $staff_id ) )->result_array();
		} else {
			return $this->db->get_where( 'purchases', array( '' ) )->result_array();
		}
	}

	function get_purchase_by_privileges( $id, $staff_id='' ) {
		$this->db->select( '*,IFNULL(vendors.type, 0) as type,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,vendors.company as vendorcompany,vendors.address as vendoraddress,vendors.country_id as vendorcountry,vendors.state as vendorstate,vendors.city as vendorcity,vendors.town as vendortown,vendors.zipcode as vendorzip,vendors.phone as vendor_phone,vendors.email as email,purchases.status_id as status_id,,purchases.created as created, purchases.id as id , recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate' );
		$this->db->join( 'vendors', 'purchases.vendor_id = vendors.id', 'left' );
		$this->db->join( 'recurring', 'purchases.id = recurring.relation AND recurring.relation_type = "purchase"', 'left' );
		$this->db->join( 'staff', 'purchases.staff_id = staff.id', 'left' );
		if($staff_id) {
			return $this->db->get_where( 'purchases', array( 'purchases.id' => $id, 'purchases.staff_id' => $staff_id ) )->row_array();
		} else {
			return $this->db->get_where( 'purchases', array( 'purchases.id' => $id ) )->row_array();
		}
	}
}