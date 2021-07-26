<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Orders extends CIUIS_Controller {

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
		$data[ 'title' ] = lang( 'orders' );
		$data[ 'orders' ] = $this->Orders_Model->get_all_orders();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'orders/index', $data );
	}

	function create() { 
		if ( $this->Privileges_Model->check_privilege( 'orders', 'create' ) ) {
			$data[ 'title' ] = lang( 'new' ).' '.lang('order');
			$appconfig = get_appconfig();
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$order_type = $this->input->post( 'order_type' );
				$customer = $this->input->post('customer');
				$subject = $this->input->post('subject');
				$assigned = $this->input->post('assigned');
				$date = $this->input->post('date');
				$opentill = $this->input->post('opentill');
				$total = $this->input->post('total');
				$lead = $this->input->post('lead');
				$status = $this->input->post('status');
				$total_items = $this->input->post('total_items');
				$total = filter_var($this->input->post('total'), FILTER_SANITIZE_NUMBER_INT);
				$hasError = false;
				$data['message'] = '';
				if ($subject == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('subject');
				} else if ($customer == '' && $order_type == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('customer');
				} else if ($lead == '' && $order_type == 'true') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('lead');
				} else if ($date == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('issue'). ' ' .lang('date');
				} else if ($assigned == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('assigned');
				} else if ($status == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('status');
				} else if ($opentill == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('end'). ' ' .lang('date');
				} else if (strtotime($opentill) < strtotime($date)) {
					$hasError = true;
					$data['message'] = lang('issue'). ' ' .lang('date').' '.lang('date_error'). ' ' .lang('end'). ' ' .lang('date');
				} else if ($total_items == '0') {
					$hasError = true;
					$data['message'] = lang('invalid_items');
				} else if ($total == 0) {
					$hasError = true;
					$data['message'] = lang('invalid_total');
				}

				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					if ( $order_type != true ) {
						$relation_type = 'customer';
						$relation = $this->input->post( 'customer' );
					} else {
						$relation_type = 'lead';
						$relation = $this->input->post( 'lead' );
					};
					$allow_comment = $this->input->post( 'comment' );
					if ( $allow_comment != true ) {
						$comment_allow = 0;
					} else {
						$comment_allow = 1;
					};
					$params = array(
						'token' => md5( uniqid() ),
						'subject' => $this->input->post( 'subject' ),
						'content' => $this->input->post( 'content' ),
						'date' =>  $this->input->post( 'date' ) ,
						'created' => $this->input->post( 'created' ) ,
						'opentill' => $this->input->post( 'opentill' ) ,
						'relation_type' => $relation_type,
						'relation' => $relation,
						'assigned' => $this->input->post( 'assigned' ),
						'addedfrom' => $this->session->usr_id,
						'datesend' => _pdate( $this->input->post( 'datesend' ) ),
						'comment' => $comment_allow,
						'status_id' => $this->input->post( 'status' ),
						'invoice_id' => $this->input->post( 'invoice' ),
						'dateconverted' => $this->input->post( 'dateconverted' ),
						'sub_total' => $this->input->post( 'sub_total' ),
						'total_discount' => $this->input->post( 'total_discount' ),
						'total_tax' => $this->input->post( 'total_tax' ),
						'total' => $this->input->post( 'total' ),
					);
					$orders_id = $this->Orders_Model->order_add( $params );
					// Custom Field Post
					if ( $this->input->post( 'custom_fields' ) ) {
						$custom_fields = array(
							'custom_fields' => $this->input->post( 'custom_fields' )
						);
						$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'order', $orders_id );
					}
					$this->Settings_Model->create_process('pdf', $orders_id, 'order', 'order_message');
					$data['success'] = true;
					$data['message'] = lang('order'). ' '. lang('createmessage');
					$data['id'] = $orders_id;
					if( $appconfig['order_series']){
						$order_number = $appconfig['order_series'];
						$order_number = $order_number + 1 ;
						$this->Settings_Model->increment_series('order_series',$order_number);
					}
					echo json_encode($data);
				}
			} else {
				$this->load->view( 'orders/create', $data );
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function update( $id ) {
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		if ( $this->Privileges_Model->check_privilege( 'orders', 'all' ) ) {
			$data[ 'order' ] = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type );
		} else if ($this->Privileges_Model->check_privilege( 'orders', 'own') ) {
			$data[ 'order' ]  = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('orders'));
		}
		if($data[ 'order' ]) {
			if ( $this->Privileges_Model->check_privilege( 'orders', 'edit' ) ) {
				$data[ 'title' ] = lang('update').' '.lang ('order' );
				if ( isset( $pro[ 'id' ] ) ) {
					if ( isset( $_POST ) && count( $_POST ) > 0 ) {
						switch ( $this->input->post( 'order_type' ) ) {
							case 'true':
								$relation_type = 'lead';
								$relation = $this->input->post( 'lead' );
								break;
							case 'false':
								$relation_type = 'customer';
								$relation = $this->input->post( 'customer' );
								break;
						};
						switch ( $this->input->post( 'comment' ) ) {
							case 'true':
								$comment_allow = 1;
								break;
							case 'false':
								$comment_allow = 0;
								break;
						};
						$params = array(
							'subject' => $this->input->post( 'subject' ),
							'content' => $this->input->post( 'content' ),
							'date' =>  $this->input->post( 'date' ) ,
							'created' =>  $this->input->post( 'created' ) ,
							'opentill' =>  $this->input->post( 'opentill' ) ,
							'relation_type' => $relation_type,
							'relation' => $relation,
							'assigned' => $this->input->post( 'assigned' ),
							'addedfrom' => $this->session->usr_id,
							'datesend' => _pdate( $this->input->post( 'datesend' ) ),
							'comment' => $comment_allow,
							'status_id' => $this->input->post( 'status' ),
							'invoice_id' => $this->input->post( 'invoice' ),
							'dateconverted' => $this->input->post( 'dateconverted' ),
							'sub_total' => $this->input->post( 'sub_total' ),
							'total_discount' => $this->input->post( 'total_discount' ),
							'total_tax' => $this->input->post( 'total_tax' ),
							'total' => $this->input->post( 'total' ),
						);
						$this->Orders_Model->update_orders( $id, $params );
						// Custom Field Post
						if ( $this->input->post( 'custom_fields' ) ) {
							$custom_fields = array(
								'custom_fields' => $this->input->post( 'custom_fields' )
							);
							$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'order', $id );
						}
						$this->Orders_Model->update_pdf_status($id, '0');
						echo $id;
					} else {
						$this->load->view( 'orders/update', $data );
					}
				} else {
					$this->session->set_flashdata( 'ntf3', '' . $id . lang( 'orderediterror' ) );
				}
			} else {
				$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
				redirect(base_url('orders/order/'. $id));
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('orders'));
		}
	}

	function order( $id ) {
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		if ( $this->Privileges_Model->check_privilege( 'orders', 'all' ) ) {
			$data[ 'orders' ] = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type );
		} else if ($this->Privileges_Model->check_privilege( 'orders', 'own') ) {
			$data[ 'orders' ]  = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('orders'));
		}
		if($data[ 'orders' ]) {
			$data[ 'title' ] = lang( 'order' );
			$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
			$this->load->view( 'orders/order', $data );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('orders'));
		}
	}

	function download_pdf($id){
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		if ( $this->Privileges_Model->check_privilege( 'orders', 'all' ) ) {
			$order = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type );
		} else if ($this->Privileges_Model->check_privilege( 'orders', 'own') ) {
			$order  = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('orders'));
		}
		if($order) {
			if (isset($id)) {
				$file_name = '' . get_number('orders',$id,'order','order').'.pdf';
				if (is_file('./uploads/files/orders/'.$id.'/'.$file_name)) {
					$this->load->helper('file');
					$this->load->helper('download');
					$data = file_get_contents('./uploads/files/orders/'.$id.'/'.$file_name);
					force_download($file_name, $data);
				} else {
					$this->session->set_flashdata( 'ntf4', lang('filenotexist'));
					redirect('orders/order/'.$id);
				}
			} else {
				redirect('orders/order/'.$id);
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('orders'));
		}
	}

	function create_pdf( $id ) {
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		if ( $this->Privileges_Model->check_privilege( 'orders', 'all' ) ) {
			$order = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type );
		} else if ($this->Privileges_Model->check_privilege( 'orders', 'own') ) {
			$order  = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('orders'));
		}
		if($order) {
			ini_set('max_execution_time', 0); 
			ini_set('memory_limit','2048M');
			if (!is_dir('uploads/files/orders/'.$id)) {
				mkdir('./uploads/files/orders/'.$id, 0777, true);
			}
			$data[ 'orders' ] = $order;
			$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
			$data['state'] = get_state_name($data['settings']['state'],$data['settings']['state_id']);
			$data['country'] = get_country($data[ 'settings' ]['country_id']);
			$data['custcountry'] = get_country($data[ 'orders' ]['country_id']);
			$data['custstate'] = get_state_name($data['orders']['state'],$data['orders']['state_id']);
			$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'order', 'relation' => $id ) )->result_array();
			$this->load->view( 'orders/pdf', $data );
			$file_name = '' . get_number('orders', $id, 'order', 'order'). '.pdf';
			$html = $this->output->get_output();
			$this->load->library( 'dom' );
			$this->dompdf->loadHtml( $html );
			$this->dompdf->set_option( 'isRemoteEnabled', TRUE );
			$this->dompdf->setPaper( 'A4', 'portrait' );
			$this->dompdf->render();
			$output = $this->dompdf->output();
			unset($this->dompdf);
			file_put_contents( 'uploads/files/orders/'. $id . '/' . $file_name . '', $output );
			$this->Orders_Model->update_pdf_status($id, '1');
			//$this->dompdf->stream( '' . $file_name . '', array( "Attachment" => 0 ) );
			if ( $output ) {
				redirect( base_url( 'orders/pdf_generated/' . $file_name . '' ) );
			} else {
				redirect( base_url( 'orders/pdf_fault/' ) );
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('orders'));
		}
	}

	function print_( $id ) {
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		if ( $this->Privileges_Model->check_privilege( 'orders', 'all' ) ) {
			$order = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type );
		} else if ($this->Privileges_Model->check_privilege( 'orders', 'own') ) {
			$order  = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('orders'));
		}
		if($order) {
			$data[ 'orders' ] = $order;
			$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
			$data['state'] = get_state_name($data['settings']['state'],$data['settings']['state_id']);
			$data['country'] = get_country($data[ 'settings' ]['country_id']);
			$data['custcountry'] = get_country($data[ 'orders' ]['country_id']);
			$data['custstate'] = get_state_name($data['orders']['state'],$data['orders']['state_id']);
			$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'order', 'relation' => $id ) )->result_array();
			$this->load->view( 'orders/pdf', $data );
			$file_name = '' . get_number('orders', $id, 'order', 'order'). '.pdf';
			$html = $this->output->get_output();
			$this->load->library( 'dom' );
			$this->dompdf->loadHtml( $html );
			$this->dompdf->set_option( 'isRemoteEnabled', TRUE );
			$this->dompdf->setPaper( 'A4', 'portrait' );
			$this->dompdf->render();
			$output = $this->dompdf->output();
			file_put_contents( 'assets/files/generated_pdf_files/orders/' . $file_name . '', $output );
			redirect(base_url('assets/files/generated_pdf_files/orders/' . $file_name . ''));
			//$this->dompdf->stream( '' . $file_name . '', array( "Attachment" => 0 ) );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('orders'));
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
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		$data[ 'orders' ] = $this->Orders_Model->get_orders( $id, $rel_type );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'order', 'relation' => $id ) )->result_array();
		$this->load->view( 'orders/pdf', $data );
	}

	function share( $id ) {
		$setconfig = $this->Settings_Model->get_settings_ciuis();
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		if ( $rel_type == 'customer' ) {
			$order = $this->Orders_Model->get_orders( $id, $rel_type );
			$data[ 'orders' ] = $this->Orders_Model->get_orders( $id, $rel_type );
			switch ( $order[ 'type' ] ) {
				case '0':
					$orderto = $order[ 'customercompany' ];
					break;
				case '1':
					$orderto = $order[ 'namesurname' ];
					break;
			}
			$ordertoemail = $order[ 'toemail' ];
		}
		if ( $rel_type == 'lead' ) {
			$order = $this->Orders_Model->get_orders( $id, $rel_type );
			$data[ 'orders' ] = $this->Orders_Model->get_orders( $id, $rel_type );
			$orderto = $order[ 'leadname' ];
			$ordertoemail = $order[ 'toemail' ];
		}
		$subject = lang( 'neworder' );
		$to = $ordertoemail;
		$data = array(
			'customer' => $orderto,
			'customermail' => $ordertoemail,
			'orderlink' => '' . base_url( 'share/order/' . $pro[ 'token' ] . '' ) . ''
		);
		$body = $this->load->view( 'email/orders/send.php', $data, TRUE );
		$result = send_email( $subject, $to, $data, $body );
		if ( $result ) {
			$response = $this->db->where( 'id', $id )->update( 'orders', array( 'datesend' => date( 'Y-m-d H:i:s' ) ) );
			$this->session->set_flashdata( 'ntf1', '<b>' . lang( 'sendmailcustomer' ) . '</b>' );
			redirect( 'orders/order/' . $id . '' );
		} else {
			$this->session->set_flashdata( 'ntf4', '<b>' . lang( 'sendmailcustomereror' ) . '</b>' );
			redirect( 'orders/order/' . $id . '' );
		}
	}

	function send_order_email($id) {
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		if ( $this->Privileges_Model->check_privilege( 'orders', 'all' ) ) {
			$order = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type );
		} else if ($this->Privileges_Model->check_privilege( 'orders', 'own') ) {
			$order  = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type, $this->session->usr_id );
		} else {
			$return['status'] = false;
			$return['message'] = lang('you_dont_have_permission');
			echo json_encode($return);
		}
		if($order) {
			if ( $rel_type == 'customer' ) {
				$data[ 'orders' ] = $order;
				switch ( $order[ 'type' ] ) {
					case '0':
						$orderto = $order[ 'customercompany' ];
						break;
					case '1':
						$orderto = $order[ 'namesurname' ];
						break;
				}
				$ordertoemail = $order[ 'toemail' ];
			}
			if ($rel_type == 'lead') {
				$data['orders'] = $order;
				$orderto = $order['leadname'];
				$ordertoemail = $order['toemail'];
			}

			$template = $this->Emails_Model->get_template('order', 'order_message');

			$path = '';
			if ($template['attachment'] == '1') {
				if ($order['pdf_status'] == '0') {
					$this->Orders_Model->generate_pdf($id);
					$file = get_number('orders', $order['id'], 'order', 'order');
					$path = base_url('uploads/files/orders/'.$id.'/'.$file.'.pdf');
				} else {
					$file = get_number('orders', $order['id'], 'order', 'order');
					$path = base_url('uploads/files/orders/'.$id.'/'.$file.'.pdf');
				}
			}

			$order_number = get_number('orders', $id, 'order', 'order');
			$settings = $this->Settings_Model->get_settings_ciuis();
			$message_vars = array(
				'{customer}' => $orderto,
				'{order_to}' => $orderto,
				'{email_signature}' => $this->session->userdata( 'email' ),
				'{name}' => $this->session->userdata( 'staffname' ),
				'{order_number}' => $order_number,
				'{app_name}' => $settings['company'],
				'{company_name}' => $settings['company']
			);
			$subject = strtr($template['subject'], $message_vars);
			$message = strtr($template['message'], $message_vars);
			$param = array(
				'from_name' => $template['from_name'],
				'email' => $ordertoemail,
				'subject' => $subject,
				'message' => $message,
				'created' => date( "Y.m.d H:i:s" ),
				'status' => 0,
				'attachments' => $path?$path:NULL,
			);
			if ($ordertoemail) {
				$this->load->library('mail'); 
				$data = $this->mail->send_email($ordertoemail, $template['from_name'], $subject, $message, $path);
				if ($data['success'] == true) {
					$return['status'] = true;
					$return['message'] = $data['message'];
					$this->db->insert( 'email_queue', $param );
					echo json_encode($return);
				} else {
					$return['status'] = false;
					$return['message'] = lang('errormessage');
					echo json_encode($return);
				}
			}
		} else {
			$return['status'] = false;
			$return['message'] = lang('you_dont_have_permission');
			echo json_encode($return);
		}
	}

	function expiration( $id ) {
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$setconfig = $this->Settings_Model->get_settings_ciuis();
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		if ( $rel_type == 'customer' ) {
			$order = $this->Orders_Model->get_orders( $id, $rel_type );
			$data[ 'orders' ] = $this->Orders_Model->get_orders( $id, $rel_type );
			switch ( $order[ 'type' ] ) {
				case '0':
					$orderto = $order[ 'customercompany' ];
					break;
				case '1':
					$orderto = $order[ 'namesurname' ];
					break;
			}
			$ordertoemail = $order[ 'toemail' ];
		}
		if ( $rel_type == 'lead' ) {
			$order = $this->Orders_Model->get_orders( $id, $rel_type );
			$data[ 'orders' ] = $this->Orders_Model->get_orders( $id, $rel_type );
			$orderto = $order[ 'leadname' ];
			$ordertoemail = $order[ 'toemail' ];
		}
		$subject = lang( 'orderexpiryreminder' );
		$to = $ordertoemail;
		$data = array(
			'customer' => $orderto,
			'customermail' => $ordertoemail,
			'orderlink' => '' . base_url( 'share/order/' . $pro[ 'token' ] . '' ) . ''
		);
		$body = $this->load->view( 'email/orders/expiration.php', $data, TRUE );
		$result = send_email( $subject, $to, $data, $body );
		if ( $result ) {
			$response = $this->db->where( 'id', $id )->update( 'orders', array( 'datesend' => date( 'Y-m-d H:i:s' ) ) );
			$this->session->set_flashdata( 'ntf1', '<b>' . lang( 'sendmailcustomer' ) . '</b>' );
			redirect( 'orders/order/' . $id . '' );
		} else {
			$this->session->set_flashdata( 'ntf4', '<b>' . lang( 'sendmailcustomereror' ) . '</b>' );
			redirect( 'orders/order/' . $id . '' );
		}
	}

	function convert_invoice( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'create' ) ) {
			$data[ 'title' ] = lang( 'convertordertoinvoice' );
			$pro = $this->Orders_Model->get_pro_rel_type( $id );
			$rel_type = $pro[ 'relation_type' ];
			$order = $this->Orders_Model->get_orders( $id, $rel_type );
			$items = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'order', 'relation' => $order[ 'id' ] ) )->result_array();
			$date = strtotime( "+7 day" );
			if ( isset( $order[ 'id' ] ) ) {
				$params = array(
					'token' => md5( uniqid() ),
					'no' => null,
					'serie' => null,
					'customer_id' => $order[ 'relation' ],
					'staff_id' => $this->session->usr_id,
					'status_id' => 3,
					'created' => date( 'Y-m-d H:i:s' ),
					'duedate' => date( 'Y-m-d H:i:s', $date ),
					'datepayment' => 0,
					'duenote' => null,
					//'order_id' => $order[ 'id' ],
					'sub_total' => $order[ 'sub_total' ],
					'total_discount' => $order[ 'total_discount' ],
					'total_tax' => $order[ 'total_tax' ],
					'total' => $order[ 'total' ],
				);
				$this->db->insert( 'invoices', $params );
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
				$appconfig = get_appconfig();
				$this->db->insert( 'logs', array(
					'date' => date( 'Y-m-d H:i:s' ),
					'detail' => ( '' . $message = sprintf( lang( 'coverttoinvoice' ), $staffname, get_number('orders', $order[ 'id' ], 'order','order') ) . '' ),
					'staff_id' => $loggedinuserid,
					'customer_id' => $order[ 'relation' ]
				) );
				//NOTIFICATION
				$staffname = $this->session->staffname;
				$staffavatar = $this->session->staffavatar;
				$this->db->insert( 'notifications', array(
					'date' => date( 'Y-m-d H:i:s' ),
					'detail' => ( '' . $staffname . ' ' . lang( 'isaddedanewinvoice' ) . '' ),
					'customer_id' => $order[ 'relation' ],
					'perres' => $staffavatar,
					'target' => '' . base_url( 'area/invoice/' . $invoice . '' ) . ''
				) );
				//--------------------------------------------------------------------------------------
				$this->db->insert( $this->db->dbprefix . 'sales', array(
					'invoice_id' => '' . $invoice . '',
					'status_id' => 3,
					'staff_id' => $loggedinuserid,
					'customer_id' => $order[ 'relation' ],
					'total' => $order[ 'total' ],
					'date' => date( 'Y-m-d H:i:s' )
				) );

				$response = $this->db->where( 'id', $id )->update( 'orders', array( 'invoice_id' => $invoice, 'status_id' => 6, 'dateconverted' => date( 'Y-m-d H:i:s' ) ) );
				$data['id'] = $invoice;
				$data['success'] = true;
				echo json_encode($data);
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function markas() {
		if ( $this->Privileges_Model->check_privilege( 'orders', 'edit' ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$name = $_POST[ 'name' ];
				$params = array(
					'order_id' => $_POST[ 'order_id' ],
					'status_id' => $_POST[ 'status_id' ],
				);
				$tickets = $this->Orders_Model->markas();
				$data['success'] = true;
				$data['message'] = lang('order').' '.lang('markas').' '.$name ;
			}
		} else {
			
			$data['success'] = false; 
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function cancelled() {
		if ( $this->Privileges_Model->check_privilege( 'orders', 'edit' ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'order' => $_POST[ 'order_id' ],
					'status_id' => $_POST[ 'status_id' ],
				);
				$tickets = $this->Orders_Model->cancelled();
				$data['success'] = true;
			}
		} else {
			$data['success'] = false; 
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function remove( $id ) {
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		if ( $this->Privileges_Model->check_privilege( 'orders', 'all' ) ) {
			$order = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type );
		} else if ($this->Privileges_Model->check_privilege( 'orders', 'own') ) {
			$order  = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type, $this->session->usr_id );
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
		if($order) {
			if ( $this->Privileges_Model->check_privilege( 'orders', 'delete' ) ) {
				if ( isset( $order[ 'id' ] ) ) {
					$this->Orders_Model->delete_orders( $id, get_number('orders',$id,'order','order') );
					$this->load->helper('file');
					$folder = './uploads/files/orders/'.$id;
					if(is_dir($folder)) {
						delete_files($folder, true);
						rmdir($folder);
					}
					$data['success'] = true;
					$data['message'] = lang('order').' '.lang('deleted');
					echo json_encode($data);
				} else {
					show_error( 'The orders you are trying to delete does not exist.' );
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('you_dont_have_permission');
				echo json_encode($data);
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('orders'));
		}
	}

	function remove_item( $id ) {
		$response = $this->db->delete( 'items', array( 'id' => $id ) );
	}

	function get_order( $id ) {
		$order = array();
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		if ( $this->Privileges_Model->check_privilege( 'orders', 'all' ) ) {
			$order = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type );
		} else if ($this->Privileges_Model->check_privilege( 'orders', 'own') ) {
			$order  = $this->Orders_Model->get_order_by_priviliges( $id, $rel_type, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('orders'));
		}
		if($order) {
			$items = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'order', 'relation' => $id ) )->result_array();
			$comments = $this->db->get_where( 'comments', array( 'relation' => $id, 'relation_type' => 'order' ) )->result_array();
			$customername = '';
			if ( $rel_type == 'customer' ) {
				$customer_id = $order[ 'relation' ];
				$customername = $order['namesurname']?$order['namesurname']:$order['customercompany'];
				$lead_id = '';
				$order_type = false;
			} else {
				$lead_id = $order[ 'relation' ];
				$customer_id = '';
				$order_type = true;
			}
			if ( $order[ 'comment' ] != 0 ) {
				$comment = true;
			} else {
				$comment = false;
			}
			switch ( $order[ 'status_id' ] ) {
				case '1':
					$status = lang( 'draft' );
					break;
				case '2':
					$status = lang( 'sent' );
					break;
				case '3':
					$status = lang( 'open' );
					break;
				case '4':
					$status = lang( 'revised' );
					break;
				case '5':
					$status = lang( 'declined' );
					break;
				case '6':
					$status = lang( 'accepted' );
					break;
				default: 
					$status = lang( 'open' );
					break;

			};
			$appconfig = get_appconfig();
			$order_details = array(
				'id' => $order[ 'id' ],
				'token' => $order[ 'token' ],
				'long_id' => get_number('orders', $order[ 'id' ], 'order','order'),
				'subject' => $order[ 'subject' ],
				'content' => $order[ 'content' ],
				'comment' => $comment,
				'sub_total' => $order[ 'sub_total' ],
				'total_discount' => $order[ 'total_discount' ],
				'total_tax' => $order[ 'total_tax' ],
				'total' => $order[ 'total' ],
				'customer' => $customer_id,
				'customername' => $customername,
				'lead' => $lead_id,
				'order_type' => $order_type,
				'created' => date(get_dateFormat(),strtotime($order[ 'created' ])),
				'created_edit' => $order[ 'created' ],
				'date' => date(get_dateFormat(),strtotime($order[ 'date' ])),
				'opentill' => date(get_dateFormat(),strtotime($order[ 'opentill' ])),
				'opentill_edit' => $order[ 'opentill' ],
				'status' => $order[ 'status_id' ],
				'assigned' => $order[ 'assigned' ],
				'content' => $order[ 'content' ],
				'invoice_id' => $order[ 'invoice_id' ],
				'status_name' => $status,
				'items' => $items,
				'comments' => $comments,
				'order_number' => $order['order_number'],
				'pdf_status' => $order['pdf_status'],
			);
			echo json_encode( $order_details );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('orders'));
		}
	}

	function get_orders() {
		$orders = array();
		if ( $this->Privileges_Model->check_privilege( 'orders', 'all' ) ) {
			$orders = $this->Orders_Model->get_all_orders_by_privileges();
		} else if ( $this->Privileges_Model->check_privilege( 'orders', 'own' ) ){
			$orders = $this->Orders_Model->get_all_orders_by_privileges($this->session->usr_id);
		}
		$data_orders = array();
		foreach ( $orders as $order ) {
			$pro = $this->Orders_Model->get_orders( $order[ 'id' ], $order[ 'relation_type' ] );
			if ( $pro[ 'relation_type' ] == 'customer' ) {
				if ( ($pro[ 'customercompany' ] === NULL) || ($pro[ 'customercompany' ] == '') ) {
					$customer = $pro[ 'namesurname' ];
					$customer_email = $pro['toemail'];
				} else {
					$customer = $pro[ 'customercompany' ];
					$customer_email = $pro['toemail'];
				}
			}
			if ( $pro[ 'relation_type' ] == 'lead' ) {
				$customer = $pro[ 'leadname' ];
				$customer_email = $pro['toemail'];
			}
			$settings = $this->Settings_Model->get_settings_ciuis();
			switch ( $settings[ 'dateformat' ] ) {
				case 'yy.mm.dd':
					$date = _rdate( $order[ 'date' ] );
					$opentill = _rdate( $order[ 'opentill' ] );
					break;
				case 'dd.mm.yy':
					$date = _udate( $order[ 'date' ] );
					$opentill = _udate( $order[ 'opentill' ] );
					break;
				case 'yy-mm-dd':
					$date = _mdate( $order[ 'date' ] );
					$opentill = _mdate( $order[ 'opentill' ] );
					break;
				case 'dd-mm-yy':
					$date = _cdate( $order[ 'date' ] );
					$opentill = _cdate( $order[ 'opentill' ] );
					break;
				case 'yy/mm/dd':
					$date = _zdate( $order[ 'date' ] );
					$opentill = _zdate( $order[ 'opentill' ] );
					break;
				case 'dd/mm/yy':
					$date = _kdate( $order[ 'date' ] );
					$opentill = _kdate( $order[ 'opentill' ] );
					break;
			};
			switch ( $order[ 'status_id' ] ) {
				case '1':
					$status = lang( 'draft' );
					$class = 'order-status-accepted';
					break;
				case '2':
					$status = lang( 'sent' );
					$class = 'order-status-sent';
					break;
				case '3':
					$status = lang( 'open' );
					$class = 'order-status-open';
					break;
				case '4':
					$status = lang( 'revised' );
					$class = 'order-status-revised';
					break;
				case '5':
					$status = lang( 'declined' );
					$class = 'order-status-declined';
					break;
				case '6':
					$status = lang( 'accepted' );
					$class = 'order-status-accepted';
					break;
				default: 
					$status = lang( 'open' );
					$class = 'order-status-open';
					break;
			};
			$data_orders[] = array(
				'id' => $order[ 'id' ],
				'assigned' => $order[ 'assigned' ],
				'prefix' => lang( 'orderprefix' ),
				'longid' => get_number('orders', $order[ 'id' ], 'order','order'),
				'subject' => $order[ 'subject' ],
				'customer' => $customer,
				'relation' => $order[ 'relation' ],
				'date' => $date,
				'opentill' => $opentill,
				'status' => $status,
				'status_id' => $order[ 'status_id' ],
				'staff' => $order[ 'staffmembername' ],
				'staffavatar' => $order[ 'staffavatar' ],
				'total' => (float)$order[ 'total' ],
				'class' => $class,
				'relation_type' => $order[ 'relation_type' ],
				'customer_email' => $customer_email,
				'' . lang( 'relationtype' ) . '' => $order[ 'relation_type' ],
				'' . lang( 'filterbystatus' ) . '' => $status,
				'' . lang( 'filterbycustomer' ) . '' => $customer,
				'' . lang( 'filterbyassigned' ) . '' => $order[ 'staffmembername' ],
			);
		};
		echo json_encode( $data_orders );
	}
}