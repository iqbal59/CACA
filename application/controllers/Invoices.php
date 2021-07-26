<?php
require_once APPPATH . '/third_party/vendor/autoload.php';
use Dompdf\Dompdf;
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Invoices extends CIUIS_Controller {

	function __construct() {
		parent::__construct();
		$path = $this->uri->segment( 1 );
		if ( !$this->Privileges_Model->has_privilege( $path ) ) {
			$this->session->set_flashdata( 'ntf3', '' . lang( 'you_dont_have_permission' ) );
			redirect( 'panel/' );
			die;
		}
	}

	function index() {
		$data[ 'title' ] = lang( 'invoices' );
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'all' ) ) {
			$data[ 'invoices' ] = $this->Invoices_Model->get_all_invoices_by_privileges();
			$data[ 'off' ] = $this->Report_Model->pff(); 
			$data[ 'ofv' ] = $this->Report_Model->ofv();
			$data[ 'oft' ] = $this->Report_Model->oft();
			$data[ 'vgf' ] = $this->Report_Model->vgf();
			$data[ 'tfa' ] = $this->Report_Model->tfa();
			$data[ 'pfs' ] = $this->Report_Model->pfs();
			$data[ 'otf' ] = $this->Report_Model->otf();
			$data[ 'tef' ] = $this->Report_Model->tef();
			$data[ 'vdf' ] = $this->Report_Model->vdf();
			$data[ 'fam' ] = $this->Report_Model->fam();
		} else {
			$data['invoices'] = $this->Invoices_Model->get_all_invoices_by_privileges($this->session->usr_id);
			$data['off'] = $this->Report_Model->total_amount_by_status('1', 'invoices'); 
			$data['ofv'] = $this->Report_Model->total_amount_by_status('2', 'invoices'); 
			$data['oft'] = $this->Report_Model->total_amount_by_status('3', 'invoices'); 
			$data['vgf'] = $this->Report_Model->total_amount_by_status('due', 'invoices'); 
			$data['tfa'] = $this->Report_Model->total_number_of_data_by_status('4', 'invoices'); 
			$data['pfs'] = $this->Report_Model->total_number_of_data_by_status('1', 'invoices');
			$data['otf'] = $this->Report_Model->total_number_of_data_by_status('2', 'invoices');
			$data['tef'] = $this->Report_Model->total_number_of_data_by_status('3', 'invoices');
 			$data['vdf'] = $this->Report_Model->total_number_of_data_by_status('due', 'invoices');
 			$data['fam'] = $this->Report_Model->total_amount_by_status('', 'invoices'); 
		}
		$data[ 'ofy' ] = ( $data[ 'tfa' ] > 0 ? number_format( ( $data[ 'tef' ] * 100 ) / $data[ 'tfa' ] ) : 0 );
			$data[ 'ofx' ] = ( $data[ 'tfa' ] > 0 ? number_format( ( $data[ 'otf' ] * 100 ) / $data[ 'tfa' ] ) : 0 );
			$data[ 'vgy' ] = ( $data[ 'tfa' ] > 0 ? number_format( ( $data[ 'vdf' ] * 100 ) / $data[ 'tfa' ] ) : 0 );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'invoices/index', $data );
	}

	function get_content($type, $id = null) {
		$html="";
		if ($type == 'index') {
			$data[ 'title' ] = lang( 'invoices' );
			$data[ 'off' ] = $this->Report_Model->pff();
			$data[ 'ofv' ] = $this->Report_Model->ofv();
			$data[ 'oft' ] = $this->Report_Model->oft();
			$data[ 'vgf' ] = $this->Report_Model->vgf();
			$data[ 'tfa' ] = $this->Report_Model->tfa();
			$data[ 'pfs' ] = $this->Report_Model->pfs();
			$data[ 'otf' ] = $this->Report_Model->otf();
			$data[ 'tef' ] = $this->Report_Model->tef();
			$data[ 'vdf' ] = $this->Report_Model->vdf();
			$data[ 'fam' ] = $this->Report_Model->fam();
			$data[ 'ofy' ] = ( $data[ 'tfa' ] > 0 ? number_format( ( $data[ 'tef' ] * 100 ) / $data[ 'tfa' ] ) : 0 );
			$data[ 'ofx' ] = ( $data[ 'tfa' ] > 0 ? number_format( ( $data[ 'otf' ] * 100 ) / $data[ 'tfa' ] ) : 0 );
			$data[ 'vgy' ] = ( $data[ 'tfa' ] > 0 ? number_format( ( $data[ 'vdf' ] * 100 ) / $data[ 'tfa' ] ) : 0 );
			$data[ 'invoices' ] = $this->Invoices_Model->get_all_invoices();
			$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
			$html = $this->load->view('invoices/invoices', $data, true);
		}
		if ($type == 'invoice' && isset($id)) {
			$appconfig = get_appconfig();
			$invoices = $this->Invoices_Model->get_invoice_detail( $id );
			$data[ 'title' ] = '' . get_number('invoices', $invoices['id'], 'invoice', 'inv'). ' ' . lang( 'detail' ) . '';
			$data[ 'invoices' ] = $this->Invoices_Model->get_invoice_detail($id);
			$html = $this->load->view( 'invoices/invoice', $data);
		}
		//$html = $this->output->get_output($html);
		echo $html;
	}

	function create() {
		$data[ 'title' ] = lang( 'newinvoice' );
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'create' ) ) {
			$data[ 'title' ] = lang( 'newinvoice' );
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$customer = $this->input->post( 'customer' );
				$created = $this->input->post('created');
				$duedate = $this->input->post( 'duedate' );
				$datepayment = $this->input->post( 'datepayment' );
				$account = $this->input->post( 'account' );
				$totalItems = $this->input->post( 'totalItems' );
				$recurring_period = $this->input->post( 'recurring_period' );
				$recurring = $this->input->post( 'recurring' );
				$status = $this->input->post( 'status' );
				$total = filter_var($this->input->post('total'), FILTER_SANITIZE_NUMBER_INT);
				$paid = ($status == 'true')? '1' : '0';
				$hasError = false;

				if ($customer == '') {
					$hasError = true;
					$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('customer');
				} else if (($created == '')) {
					$hasError = true;
					$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('dateofissuance');
				} else if (($paid == '1') && ($this->input->post('account') == '')) {
					$hasError = true;
					$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('account');
				} else if (($paid == '1') && ($this->input->post('datepayment') == '')) {
					$hasError = true;
					$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('datepaid');
				} else if (($paid == '0') && ($duedate == '')) {
					$hasError = true;
					$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('duedate');
				} else if (($paid == '0') && (strtotime($duedate) < strtotime($created))) {
					$hasError = true;
					$datas['message'] = lang('dateofissuance').' '.lang('date_error'). ' ' .lang('duedate');
				} else if (($recurring == '1') && ($this->input->post('recurring_period') == '')) {
					$hasError = true;
					$datas['message'] = lang('invalidmessage'). ' ' .lang('recurring_period');
				} else if (((int)($this->input->post('totalItems'))) == 0) {
					$hasError = true;
					$datas['message'] = lang('invalid_items');
				} else if ($total == 0) {
					$hasError = true;
					$datas['message'] = lang('invalid_total');
				}
				if ($hasError) {
					$datas['success'] = false;
					echo json_encode($datas);
				}
				if (!$hasError) {
					$appconfig = get_appconfig();
					$status_value = $this->input->post( 'status' );
					if ( $status_value == 'true' ) {
						$datepayment = $this->input->post( 'datepayment' );
						$duenote = null;
						$duedate = null;
						$status = 2;
					} else {
						$duedate = $this->input->post( 'duedate' );
						$duenote = $this->input->post( 'duenote' );
						$datepayment = null;
						$status = 3;
					}
					$params = array(
						'token' => md5( uniqid() ),
						'no' => $this->input->post( 'no' ),
						'serie' => $this->input->post( 'serie' ),
						'customer_id' => $this->input->post( 'customer' ),
						'staff_id' => $this->session->usr_id,
						'status_id' => $status,
						'created' => $this->input->post( 'created' ),
						'last_recurring' => $this->input->post( 'created' ),
						'duedate' => $duedate,
						'datepayment' => $datepayment,
						'duenote' => $duenote,
						'sub_total' => $this->input->post( 'sub_total' ),
						'total_discount' => $this->input->post( 'total_discount' ),
						'total_tax' => $this->input->post( 'total_tax' ),
						'total' => $this->input->post( 'total' ),
						'billing_street' => $this->input->post( 'billing_street' ),
						'billing_city' => $this->input->post( 'billing_city' ),
						'billing_state_id' => $this->input->post( 'billing_state_id' ),
						'billing_zip' => $this->input->post( 'billing_zip' ),
						'billing_country' => $this->input->post( 'billing_country' ),
						'shipping_street' => $this->input->post( 'shipping_street' ),
						'shipping_city' => $this->input->post( 'shipping_city' ),
						'shipping_state_id' => $this->input->post( 'shipping_state_id' ),
						'shipping_zip' => $this->input->post( 'shipping_zip' ),
						'shipping_country' => $this->input->post( 'shipping_country' ),
						'default_payment_method' => $this->input->post('default_payment_method'),
					);
					$invoices_id = $this->Invoices_Model->invoice_add( $params );
					// Custom Field Post
					if ( $this->input->post( 'custom_fields' ) ) {
						$custom_fields = array(
							'custom_fields' => $this->input->post( 'custom_fields' ) 
						);
						$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'invoice', $invoices_id );
					}
					$this->Settings_Model->create_process('pdf', $invoices_id, 'invoice', 'invoice_message');
					if ( $this->input->post( 'recurring' ) == 'true'  || $this->input->post('recurring') == '1') {
						$SHXparams = array(
							'relation_type' => 'invoice',
							'relation' => $invoices_id,
							'period' => $this->input->post( 'recurring_period' ),
							'end_date' => $this->input->post( 'end_recurring' ),
							'type' => $this->input->post( 'recurring_type' ),
						);
						$recurring_invoices_id = $this->Invoices_Model->recurring_add( $SHXparams );
					}
					$datas['success'] = true;
					$datas['id'] = $invoices_id;
					if($appconfig['invoice_series']){
						$invoice_number = $appconfig['invoice_series'];
						$invoice_number = $invoice_number + 1 ;
						$this->Settings_Model->increment_series('invoice_series',$invoice_number);
					}
					$datas['message'] = lang('invoice').' '.lang('createmessasge');
					echo json_encode($datas);
				}
			} else {
				$this->load->view( 'invoices/create', $data );
			}
		} else {
			$this->session->set_flashdata( 'ntf3', '' . $id . lang( 'you_dont_have_permission' ) );
			redirect(base_url('invoices'));
		}
	}

	function update( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'all' ) ) {
			$invoices = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id );
		} else if ($this->Privileges_Model->check_privilege( 'invoices', 'own') ) {
			$invoices = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id, $this->session->usr_id );
		} else {
			$datas['success'] = false;
			$datas['message'] = lang('you_dont_have_permission');
			echo json_encode($datas);
		} 
		if($invoices) {
			if ( $this->Privileges_Model->check_privilege( 'invoices', 'edit' ) ) {
				$data['payment'] = $this->Settings_Model->get_payment_gateway_data();
				if (!$this->session->userdata('other')) {
					$appconfig = get_appconfig();
					$data[ 'title' ] = '' . lang( 'updateinvoicetitle' ) . ' ' . get_number('invoices', $id, 'invoice', 'inv');
					if ( isset( $invoices[ 'id' ] ) ) {
						if ( isset( $_POST ) && count( $_POST ) > 0 ) {
							$customer = $this->input->post( 'customer' );
							$created = $this->input->post('created');
							$duedate = $this->input->post( 'duedate' );
							$datepayment = $this->input->post( 'datepayment' );
							$account = $this->input->post( 'account' );
							$totalItems = $this->input->post( 'totalItems' );
							$recurring_period = $this->input->post( 'recurring_period' );
							$recurring_status = $this->input->post( 'recurring_status' );
							$status = $this->input->post( 'status' );
							$total = filter_var($this->input->post('total'), FILTER_SANITIZE_NUMBER_INT);
							$paid = ($status == 'true')? '1' : '0';

							$hasError = false;

							if ($customer == '') {
								$hasError = true;
								$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('customer');
							} else if (($created == '')) {
								$hasError = true;
								$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('dateofissuance');
							} else if (($paid == '1') && ($this->input->post('account') == '')) {
								$hasError = true;
								$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('account');
							} else if (($paid == '1') && ($this->input->post('datepayment') == '')) {
								$hasError = true;
								$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('datepaid');
							} else if (($paid == '0') && ($duedate == '')) {
								$hasError = true;
								$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('duedate');
							} else if (($paid == '0') && (strtotime($duedate) < strtotime($created))) {
								$hasError = true;
								$datas['message'] = lang('dateofissuance').' '.lang('date_error'). ' ' .lang('duedate');
							} else if (($recurring_status == 'true') && ($this->input->post('recurring_period') == '')) {
								$hasError = true;
								$datas['message'] = lang('invalidmessage'). ' ' .lang('recurring_period');
							} else if (((int)($this->input->post('totalItems'))) == 0) {
								$hasError = true;
								$datas['message'] = lang('invalid_items');
							} else if ($total == 0) {
								$hasError = true;
								$datas['message'] = lang('invalid_total');
							}
							if ($hasError) {
								$datas['success'] = false;
								echo json_encode($datas);
							}
							if (!$hasError) {
								$duedate = $this->input->post( 'duedate' );
								$duenote = $this->input->post( 'duenote' );
								$datepayment = null;
								$params = array(
									'no' => $this->input->post( 'no' ),
									'serie' => $this->input->post( 'serie' ),
									'customer_id' => $this->input->post( 'customer' ),
									'created' => $this->input->post( 'created' ),
									'last_recurring' => $this->input->post( 'created' ),
									'duedate' => $duedate,
									'duenote' => $duenote,
									'sub_total' => $this->input->post( 'sub_total' ),
									'total_discount' => $this->input->post( 'total_discount' ),
									'total_tax' => $this->input->post( 'total_tax' ),
									'total' => $this->input->post( 'total' ),
									'billing_street' => $this->input->post( 'billing_street' ),
									'billing_city' => $this->input->post( 'billing_city' ),
									'billing_state_id' => $this->input->post( 'billing_state_id' ),
									'billing_zip' => $this->input->post( 'billing_zip' ),
									'billing_country' => $this->input->post( 'billing_country' ),
									'shipping_street' => $this->input->post( 'shipping_street' ),
									'shipping_city' => $this->input->post( 'shipping_city' ),
									'shipping_state_id' => $this->input->post( 'shipping_state_id' ),
									'shipping_zip' => $this->input->post( 'shipping_zip' ),
									'shipping_country' => $this->input->post( 'shipping_country' ),							
									'default_payment_method' => $this->input->post('default_payment_method'),
								);
								$this->Invoices_Model->update_invoices( $id, $params );

								// Custom Field Post
								if ( $this->input->post( 'custom_fields' ) ) {
									$custom_fields = array(
										'custom_fields' => $this->input->post( 'custom_fields' )
									);
									$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'invoice', $id );
								}

								// START Recurring Invoice
								if ($this->input->post('recurring') == 'true') {
									$SHXparams = array(
										'period' => $this->input->post( 'recurring_period' ),
										'end_date' => $this->input->post( 'end_recurring' ),
										'type' => $this->input->post( 'recurring_type' ),
										'status' => 0,
									);
									$recurring_invoices_id = $this->Invoices_Model->recurring_update( $id, $SHXparams );
								} else {
									$SHXparams = array(
										'period' => $this->input->post( 'recurring_period' ),
										'end_date' => $this->input->post( 'end_recurring' ),
										'type' => $this->input->post( 'recurring_type' ),
										'status' => 1,
									);
									$recurring_invoices_id = $this->Invoices_Model->recurring_update( $id, $SHXparams );
								}
								if ( !is_numeric( $this->input->post( 'recurring_id' ) ) && ($this->input->post('recurring_status') == 'true') ) { // NEW Recurring From Update
									$SHXparams = array(
										'relation_type' => 'invoice',
										'relation' => $id,
										'period' => $this->input->post( 'recurring_period' ),
										'end_date' => $this->input->post( 'end_recurring' ),
										'type' => $this->input->post( 'recurring_type' ),
									);
									$recurring_invoices_id = $this->Invoices_Model->recurring_add( $SHXparams );
								}
								$this->Invoices_Model->update_pdf_status($id, '0');
								$datas['success'] = true;
								$datas['id'] = $id;
								$datas['message'] = lang('invoice').' '.lang('updatemessasge');
								echo json_encode($datas);
							}
							// END Recurring Invoice
						} else {
							$data['invoices'] = $invoices;
							$this->load->view( 'invoices/update', $data );
						}
					} else
						$this->session->set_flashdata( 'ntf3', '' . $id . lang( 'error' ) );
				} else {
					$this->session->set_flashdata( 'ntf3', '' . $id . lang( 'you_dont_have_permission' ) );
					redirect('invoices');
				}
			} else {
				$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
				redirect('invoices/invoice/'.$id);
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect('invoices');
		}
	}

	function invoice( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'all' ) ) {
			$invoice = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id );
		} else if ($this->Privileges_Model->check_privilege( 'invoices', 'own') ) {
			$invoice = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('invoices'));
		}
		if($invoice) {
			$data[ 'title' ] = ''. get_number('invoices', $id, 'invoice', 'inv');
			$data[ 'invoices' ] = $invoice;
			$this->load->view( 'invoices/invoice', $data );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('invoices'));
		}
	}

	function record_payment() {
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'edit' ) ) {
			$amount = $this->input->post( 'amount' );
			$invoicetotal = $this->input->post( 'invoicetotal' );
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$amount = $amount;
				$not = $this->input->post('not');
				$account = $this->input->post( 'account' );
				$invoice_id =  $this->input->post( 'invoice' );
				$hasError = false;
				if ($amount == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage').' '.lang('amount');
				} else if ($not == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage').' '.lang('description');
				} else if ($account == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage').' '.lang('account').' '.lang('type');
				} else if ($amount > $invoicetotal){
					$hasError = true;
					$data['message'] = lang( 'paymentamounthigh').' '.lang('invoice');
				}
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError){
					$params = array(
						'token' => md5( uniqid() ),
						'relation_type' => lang('invoice'),
						'category_id' => $this->Invoices_Model->get_category_id(), 
						'staff_id' => $this->session->usr_id,
						'customer_id' => $this->input->post( 'customer' ),
						'invoice_id' =>  $invoice_id,
						'account_id' => $this->input->post( 'account' ),
						'title' => lang('invoice'),
						'date' => $this->input->post( 'date' ),
						'created' => date( 'Y-m-d H:i:s' ),
						'amount' => $amount,
						'total_tax' => '0',
						'sub_total' => $amount,
						'description' => $this->input->post( 'not' ),
						'status' => '2',
						'last_recurring' => date('Y-m-d')
					);
					$payments = $this->Payments_Model->addpayment( $params );
					$template = $this->Emails_Model->get_template('invoice', 'invoice_payment');
					if ($template['status'] == 1) {
						$invoice = $this->Invoices_Model->get_invoice_detail( $invoice_id );
						$appconfig = get_appconfig();
						$inv_number = get_number('invoices', $invoice_id, 'invoice', 'inv') ;
						$name = $invoice['customercompany'] ? $invoice['customercompany'] : $invoice['individualindividual'];
						$link = base_url( 'share/invoice/' . $invoice[ 'token' ] . '' );
						$message_vars = array(
							'{invoice_number}' => $inv_number,
							'{invoice_link}' => $link,
							'{payment_total}' => $amount,
							'{payment_date}' => $this->input->post( 'date' ),
							'{email_signature}' => $this->session->userdata( 'email' ),
							'{name}' => $this->session->userdata( 'staffname' ),
							'{customer}' => $name
						);
						$subject = strtr($template['subject'], $message_vars);
						$message = strtr($template['message'], $message_vars);
						$param = array(
							'from_name' => $template['from_name'],
							'email' => $invoice['email'],
							'subject' => $subject,
							'message' => $message,
							'created' => date( "Y.m.d H:i:s" ),
						);
						if ($invoice['email']) {
							$this->db->insert( 'email_queue', $param );
						}
					}
					$data['success'] = true;
					$data['id'] = $invoice_id;
					$data['message'] = lang('paymentaddedsuccessfully');
					if($appconfig['deposit_series']){
						$deposit_number = $appconfig['deposit_series'];
						$deposit_number = $deposit_number + 1 ;
						$this->Settings_Model->increment_series('deposit_series',$deposit_number);
					}
					echo json_encode($data);
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function download_pdf($id){
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'all' ) ) {
			$invoice = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id );
		} else if ($this->Privileges_Model->check_privilege( 'invoices', 'own') ) {
			$invoice = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('invoices'));
		}
		if($invoice) {
			if (isset($id)) {
				$file_name = '' . get_number('invoices',$id,'invoice','inv').'.pdf';
				if (is_file('./uploads/files/invoices/'.$id.'/'.$file_name)) {
					$this->load->helper('file');
					$this->load->helper('download');
					$data = file_get_contents('./uploads/files/invoices/'.$id.'/'.$file_name);
					force_download($file_name, $data);
				} else {
					$this->session->set_flashdata( 'ntf4', lang('filenotexist'));
					redirect('invoices/invoice/'.$id);
				}
			} else {
				redirect('invoices/invoice/'.$id);
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('invoices'));
		}
	}

	function create_pdf($id) {
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'all' ) ) {
			$invoice = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id );
		} else if ($this->Privileges_Model->check_privilege( 'invoices', 'own') ) {
			$invoice = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('invoices'));
		}
		if($invoice) {
			ini_set('max_execution_time', 0); 
			ini_set('memory_limit','2048M');
			if (!is_dir('uploads/files/invoices/'.$id)) {
				mkdir('./uploads/files/invoices/'.$id, 0777, true);
			}
			$data[ 'invoice' ] = $invoice;
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
			$this->load->view( 'invoices/pdf', $data );
			$appconfig = get_appconfig();
			$file_name =  get_number('invoices', $id, 'invoice', 'inv') .'.pdf';
			$html = $this->output->get_output();
			require_once APPPATH . '/third_party/vendor/autoload.php';
			$this->dompdf = new DOMPDF();
			//$this->load->library( 'dom' );
			$this->dompdf->loadHtml( $html );
			$this->dompdf->set_option( 'isRemoteEnabled', TRUE );
			$this->dompdf->setPaper( 'A4', 'portrait' );
			$this->dompdf->render();
			$output = $this->dompdf->output();
			file_put_contents( 'uploads/files/invoices/'. $id . '/' . $file_name . '', $output );
			$this->Invoices_Model->update_pdf_status($id, '1');
			//$this->dompdf->stream( '' . $file_name . '', array( "Attachment" => 0 ) );
			if ( $output ) {
				redirect( base_url( 'invoices/pdf_generated/' . $file_name . '' ) );
			} else {
				redirect( base_url( 'invoices/pdf_fault/' ) );
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('invoices'));
		}
	}

	function print_( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'all' ) ) {
			$invoice = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id );
		} else if ($this->Privileges_Model->check_privilege( 'invoices', 'own') ) {
			$invoice = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('invoices'));
		}
		if($invoice) {
			ini_set('max_execution_time', 0); 
			ini_set('memory_limit','2048M');
			if (!is_dir('uploads/files/invoices/'.$id)) {
				mkdir('./uploads/files/invoices/'.$id, 0777, true);
			}
			$data[ 'payments' ] = $this->Invoices_Model->get_invoices_payment( $id );
			$data[ 'invoice' ] = $invoice;
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
			$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'invoice', 'relation' => $id ) )->result_array();
			$this->load->view( 'invoices/pdf', $data );
			$file_name = get_number('invoices', $id, 'invoice', 'inv'). '.pdf';
			$html = $this->output->get_output();
			$this->dompdf = new DOMPDF();
			$this->dompdf->loadHtml( $html );
			$this->dompdf->set_option( 'isRemoteEnabled', TRUE );
			$this->dompdf->setPaper( 'A4', 'portrait' );
			$this->dompdf->render();
			$output = $this->dompdf->output();
			file_put_contents( 'uploads/files/invoices/'. $id .'/'. $file_name . '', $output );
			if ($output) {
				redirect(base_url('uploads/files/invoices/'. $id .'/'. $file_name . ''));
				//$this->dompdf->stream( '' . $file_name . '', array( "Attachment" => 0 ) );
			} else {
				redirect(base_url('invoices/pdf_falut/'));
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('invoices'));
		}
	}

	function pdf_generated( $file ) {
		$result = array(
			'status' => true,
			'file_name' => $file,
		);
		echo json_encode( $result );
	}

	function pdf_fault() {
		$result = array(
			'status' => false,
		);
		echo json_encode( $result );
	}

	function dp( $id ) {
		$data[ 'invoice' ] = $this->Invoices_Model->get_invoice_detail( $id );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'invoice', 'relation' => $id ) )->result_array();
		$this->load->view( 'invoices/pdf', $data );
	}

	function send_invoice_email($id) {
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'all' ) ) {
			$invoice = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id );
		} else if ($this->Privileges_Model->check_privilege( 'invoices', 'own') ) {
			$invoice = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id, $this->session->usr_id );
		} else {
			$return['status'] = false;
			$return['message'] = lang('you_dont_have_permission');
			echo json_encode($return);
		}
		if($invoice) {
			$template = $this->Emails_Model->get_template('invoice', 'invoice_message');
			$path = '';
			if ($template['attachment'] == '1') {
				if ($invoice['pdf_status'] == '0') {
					$this->Invoices_Model->generate_pdf($id);
					$file = get_number('invoices', $invoice['id'], 'invoice', 'inv');
					$path = base_url('uploads/files/invoices/'.$id.'/'.$file.'.pdf');
				} else {
					$file = get_number('invoices', $invoice['id'], 'invoice', 'inv');
					$path = base_url('uploads/files/invoices/'.$id.'/'.$file.'.pdf');
				}
			}

			$inv_number = '' . get_number("invoices",$invoice['id'],'invoice','inv').'';
			if ( $invoice[ 'status_id' ] == 1 ) {
				$invoicestatus = lang( 'draft' );
			}
			if ( $invoice[ 'status_id' ] == 3 ) {
				$invoicestatus = lang( 'unpaid' );
			}
			if ( $invoice[ 'status_id' ] == 4 ) {
				$invoicestatus = lang( 'cancelled' );
			}
			if ( $invoice[ 'status_id' ] == 2 ) {
				$invoicestatus = lang( 'partial' );
			}
			$name = $invoice['customercompany'] ? $invoice['customercompany'] : $invoice['individualindividual'];
			$link = base_url( 'share/invoice/' . $invoice[ 'token' ] . '' );

			$message_vars = array(
				'{invoice_number}' => $inv_number,
				'{invoice_link}' => $link,
				'{invoice_status}' => $invoicestatus,
				'{email_signature}' => $this->session->userdata( 'email' ),
				'{name}' => $this->session->userdata( 'staffname' ),
				'{customer}' => $name
			);
			$subject = strtr($template['subject'], $message_vars);
			$message = strtr($template['message'], $message_vars);

			$param = array(
				'from_name' => $template['from_name'],
				'email' => $invoice['email'],
				'subject' => $subject,
				'message' => $message,
				'created' => date( "Y.m.d H:i:s" ),
				'status' => 0,
				'attachments' => $path?$path:NULL,
			);
			$this->load->library('mail'); 
			$data = $this->mail->send_email($invoice['email'], $template['from_name'], $subject, $message, $path);
			if ($data['success'] == true) {
				$return['status'] = true;
				$return['message'] = $data['message'];
				if ($invoice['email']) {
					$this->db->insert( 'email_queue', $param );
				}
				echo json_encode($return);
			} else {
				$return['status'] = false;
				$return['message'] = lang('errormessage');
				echo json_encode($return);
			}
		} else {
			$return['status'] = false;
			$return['message'] = lang('you_dont_have_permission');
			echo json_encode($return);
		}
	}

	function share( $id ) {
		$inv = $this->Invoices_Model->get_invoice_detail( $id );
		// SEND EMAIL SETTINGS
		switch ( $inv[ 'type' ] ) {
			case '0':
				$invcustomer = $inv[ 'customercompany' ];
				break;
			case '1':
				$invcustomer = $inv[ 'namesurname' ];
				break;
		}
		$subject = lang( 'yourinvoicedetails' );
		$to = $inv[ 'email' ];
		$data = array(
			'customer' => $invcustomer,
			'customermail' => $inv[ 'email' ],
			'invoicelink' => '' . base_url( 'share/invoice/' . $inv[ 'token' ] . '' ) . ''
		);
		$body = $this->load->view( 'email/invoices/sendinvoice.php', $data, TRUE );
		$result = send_email( $subject, $to, $data, $body );
		if ( $result ) {
			$response = $this->db->where( 'id', $id )->update( 'invoices', array( 'datesend' => date( 'Y-m-d H:i:s' ) ) );
			$this->session->set_flashdata( 'ntf1', '<b>' . $inv[ 'email' ], lang( 'sendmailcustomer' ) . '</b>' );
			redirect( 'invoices/invoice/' . $id . '' );

		} else {
			$this->session->set_flashdata( 'ntf4', '<b>' . lang( 'sendmailcustomereror' ) . '</b>' );
			redirect( 'invoices/invoice/' . $id . '' );
		}
	}

	function mark_as_draft( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'edit' ) ) {
			$response = $this->db->where( 'id', $id )->update( 'invoices', array( 'status_id' => 1 ) );
			$response = $this->db->update( 'sales', array( 'invoice_id' => $id, 'status_id' => 1 ) );
			$data['success'] = true;
			$data['message'] = lang( 'markedasdraft' );
		} else {
			$data['success'] = false;
			$data['message'] = lang( 'you_dont_have_permission' );
		}
		echo json_encode($data);
	}

	function mark_as_cancelled( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'edit' ) ) {
			$response = $this->db->where( 'id', $id )->update( 'invoices', array( 'status_id' => 4 ) );
			$response = $this->db->delete( 'sales', array( 'invoice_id' => $id ) );
			$response = $this->db->delete( 'payments', array( 'invoice_id' => $id ) );
			$data['success'] = true;
			$data['message'] = lang( 'markedascancelled' );
		} else {
			$data['success'] = false;
			$data['message'] = lang( 'you_dont_have_permission' );
		}
		echo json_encode($data);
	}

	function remove( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'all' ) ) {
			$invoice = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id );
		} else if ($this->Privileges_Model->check_privilege( 'invoices', 'own') ) {
			$invoice = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('invoices'));
		} 
		if($invoice) {
			if ( $this->Privileges_Model->check_privilege( 'invoices', 'delete' ) ) {
				if ( isset( $invoice[ 'id' ] ) ) {
					$this->load->helper('file');
					$folder = './uploads/files/invoices/'.$id;
					if(file_exists($folder)){
						delete_files($folder, true);
						rmdir($folder);
					}
					$this->Invoices_Model->delete_invoices( $id, get_number('invoices',$id,'invoice','inv'));
					$data['success'] = true;
					$data['message'] = lang('invoicedeleted');
	 			} else {
	 				show_error( 'The invoices you are trying to delete does not exist.' );
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('you_dont_have_permission');
			}
			echo json_encode($data);
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('invoices'));
		}
	} 

	function remove_item( $id ) {
		$response = $this->db->delete( 'items', array( 'id' => $id ) );
	}

	function get_invoice( $id ) {
		$invoice = array();
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'all' ) ) {
			$invoice = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id );
		} else if ($this->Privileges_Model->check_privilege( 'invoices', 'own') ) {
			$invoice = $this->Invoices_Model->get_invoice_detail_by_privilegs( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('invoices'));
		}
		if($invoice) {
			$fatop = $this->Invoices_Model->get_items_invoices( $id );
			$tadtu = $this->Invoices_Model->get_paid_invoices( $id );
			$total = $invoice[ 'total' ];
			$today = time();
			$duedate = strtotime( $invoice[ 'duedate' ] ); // or your date as well
			$created = strtotime( $invoice[ 'created' ] );
			$paymentday = $duedate - $created; // Bunun sonucu 14 gün olcak
			$paymentx = $today - $created;
			$datepaymentnet = $paymentday - $paymentx;
			if ( $invoice[ 'duedate' ] == 0 ) {
				$duedate_text = 'No Due Date';
			} else {
				if ( $datepaymentnet < 0 ) {
					$duedate_text = lang( 'overdue' );
					$duedate_text = '' . floor( $datepaymentnet / ( 60 * 60 * 24 ) ) . ' days';

				} else {
					$duedate_text = lang( 'payableafter' ) . floor( $datepaymentnet / ( 60 * 60 * 24 ) ) . ' ' . lang( 'day' ) . '';

				}
			}
			if ( $invoice[ 'datesend' ] == 0 ) {
				$mail_status = lang( 'notyetbeensent' );
			} else $mail_status = _adate( $invoice[ 'datesend' ] );
			$kalan = $total - $tadtu->row()->amount;
			$net_balance = $kalan;
			if ( $tadtu->row()->amount < $total && $tadtu->row()->amount > 0 ) {
				$partial_is = true;
			} else $partial_is = false;
			$payments = $this->db->select( '*,accounts.name as accountname,payments.id as id ' )->join( 'accounts', 'payments.account_id = accounts.id', 'left' )->get_where( 'payments', array( 'invoice_id' => $id ) )->result_array();
			$items = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'invoice', 'relation' => $id ) )->result_array();

			if ( $invoice[ 'type' ] == 1 ) {
				$customer = $invoice[ 'individual' ];
			} else {
				$customer = $invoice[ 'customercompany' ];
			}

			$properties = array(
				'invoice_id' => '' .  get_number('invoices', $invoice[ 'id' ], 'invoice','inv').'',
				'customer' => $customer,
				'customer_address' => $invoice[ 'customeraddress' ],
				'customer_phone' => $invoice[ 'customerphone' ],
				'invoice_staff' => $invoice[ 'staffmembername' ],
				'invoice_number' => $invoice['invoice_number']
			);

			if ($invoice[ 'recurring_endDate' ] != 'Invalid date') {
				$recurring_endDate = date( DATE_ISO8601, strtotime( $invoice[ 'recurring_endDate' ] ) );
			} else {
				$recurring_endDate = '';
			}
			$billing_country = get_country($invoice['bill_country']);
			$shipping_country = get_country($invoice['shipp_country']);  
			$billing_state = get_state_name($invoice['bill_state'],$invoice['bill_state_id']);
			$shipping_state = get_state_name($invoice['shipp_state'],$invoice['shipp_state_id']);
			$invoice_details = array(
				'id' => $invoice[ 'id' ],
				'sub_total' => $invoice[ 'sub_total' ],
				'total_discount' => $invoice[ 'total_discount' ],
				'total_tax' => $invoice[ 'total_tax' ],
				'total' => $invoice[ 'total' ],
				'no' => $invoice[ 'no' ],
				'serie' => $invoice[ 'serie' ],
				'created' => date(get_dateFormat(),strtotime($invoice[ 'created' ])),
				'duedate' => date(get_dateFormat(),strtotime($invoice[ 'duedate' ])),
				'created_edit' => $invoice[ 'created' ],
				'duedate_edit' => $invoice[ 'duedate' ],
				'customer' => $invoice[ 'customer_id' ],
				'billing_street' => $invoice[ 'bill_street' ],
				'billing_city' => $invoice[ 'bill_city' ],
				'billing_state' => $billing_state,
				'billing_state_id' => $invoice['bill_state_id'],
				'billing_zip' => $invoice[ 'bill_zip' ],
				'billing_country' => $billing_country,
				'billing_country_id' => $invoice['bill_country'],
				'shipping_street' => $invoice[ 'shipp_street' ],
				'shipping_city' => $invoice[ 'shipp_city' ],
				'shipping_state' => $shipping_state,
				'shipping_state_id' => $invoice['shipp_state_id'],
				'shipping_zip' => $invoice[ 'shipp_zip' ],
				'shipping_country' => $shipping_country,
				'shipping_country_id' => $invoice['shipp_country'],
				'datepayment' => $invoice[ 'datepayment' ],
				'duenote' => $invoice[ 'duenote' ],
				'status_id' => $invoice[ 'status_id' ],
				'default_payment_method' => $invoice[ 'default_payment_method' ],
				'duedate_text' => $duedate_text,
				'mail_status' => $mail_status,
				'balance' => $net_balance,
				'partial_is' => $partial_is,
				'items' => $items,
				'payments' => $payments,
				// Recurring Invoice
				'recurring_endDate' => $recurring_endDate,
				'recurring_id' => $invoice[ 'recurring_id' ],
				'recurring_status' => $invoice[ 'recurring_status' ] == '0' ? true : false,
				'recurring_period' => (int)$invoice[ 'recurring_period' ],
				'recurring_type' => $invoice[ 'recurring_type' ] ? $invoice[ 'recurring_type' ] : 0,
				// END Recurring Invoice
				'payments' => $payments,
				'properties' => $properties,
				'invoice_number' => $invoice['invoice_number'],
				'pdf_status' => $invoice['pdf_status'],

			);
			echo json_encode( $invoice_details );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('invoices'));
		}
	}
}
