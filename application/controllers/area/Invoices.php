<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Invoices extends AREA_Controller {
	

	function index() {
		$data[ 'title' ] = lang( 'areatitleinvoices' );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'area/invoices/index', $data );

	}

	function invoice( $token ) { 
		$invoice = $this->Invoices_Model->get_invoices_by_token( $token );
		$data[ 'invoice' ] = $invoice;
		$data['payment'] = $this->Settings_Model->payment_mode($data['invoice']['default_payment_method']);
		//$this->Settings_Model->get_payment_gateway_data();
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'invoice', 'relation' => $invoice[ 'id' ] ) )->result_array();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'title' ] = get_number('invoices', $invoice['id'], 'invoice', 'inv') .' '. lang('detail');
		$this->load->view( 'area/invoices/invoice_detail', $data );
	}

	// function save_amount($id) {
	// 	$this->db->where( 'id', $id );
	// 	$response = $this->db->update( 'customers', $params );
	// }
}