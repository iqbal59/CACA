<?php
class CronJob extends CI_Controller {

	public function __construct(){
		parent::__construct();

				//load the model  
		$this->load->model('Invoices_Model'); 
		$this->load->model('Emails_Model');
		$this->load->model('Settings_Model');
		$this->load->model('Expenses_Model');
		$this->load->model('Events_Model');
		$this->load->model('Purchases_Model');
		$this->load->model('Payments_Model');
		$this->load->model('Proposals_Model');
		$this->load->model('Orders_Model');
		$this->load->model('Deposits_Model');
		define( 'LANG', $this->Settings_Model->get_crm_lang() );
		define( 'currency', $this->Settings_Model->get_currency() );
		$this->lang->load( LANG.'_default', LANG);
		$this->lang->load( LANG, LANG );
	}

	function index() {
		header('Location: /');
	}

	function run() {
		foreach ($this->Invoices_Model->get_all_recurring() as $key => $value) {
			if($value['relation_type'] == 'invoice') {
				if(($value['end_date'] != 'Invalid date') && (strtotime(date("Y-m-d",strtotime($value['end_date']))) <= strtotime(date('Y-m-d')))) {
					continue;
				}
				
				$id = $value['relation'];
				$invv = $this->db->get_where( 'invoices', array( 'id' => $value['relation'] ) );
				$invv = $invv->result_array();
				$invv = end($invv);
				if($invv['last_recurring'] != NULL && date('Y-m-d',strtotime($invv['last_recurring'])) == date('Y-m-d')) {
					continue;
				}
				// Years
				if($value['type'] == '3' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'Years')) != date('Y-m-d')) {
					continue;
				}
				// Month
				if($value['type'] == '2' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'month')) != date('Y-m-d')) {
					continue;
				}
				// Week
				if($value['type'] == '1' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'week')) != date('Y-m-d')) {
					continue;
				}
				// Day
				if($value['type'] == '0' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'day')) != date('Y-m-d')) {
					continue;
				}
				$invoice = $this->Invoices_Model->get_invoices($id);
				$created = date('Y-m-d');
				$invoices = array(
					'token' => md5( uniqid() ),
					'no' => $invoice['no'],
					'serie' => $invoice['serie'],
					'customer_id' => $invoice['customer_id'],
					'staff_id' => $invoice['staff_id'],
					'status_id' => 3,
					'created' => $created,
					'last_recurring' => $created,
					'duedate' => date('Y-m-d' ,strtotime($created . '+30 days')), // +30 day
					'duenote' => $invoice['duenote'],
					'sub_total' => $invoice['sub_total'],
					'total_discount' => $invoice['total_discount'],
					'total_tax' => $invoice['total_tax'],
					'total' => $invoice['total'],
					'recurring' => $value['id'],
				);
				$items = $this->db->select('*')->get_where( 'items', array( 'relation_type' => 'invoice', 'relation' => $id ) )->result_array();
				$invoices_id = $this->Invoices_Model->recurring_invoice($invoices,$items);
				if ($invoices_id) {
					$this->Invoices_Model->update_recurring_date($id);
					$this->Settings_Model->create_process('pdf', $invoices_id, 'invoice', 'invoice_recurring');
				}
			}
		}
		$this->expense_recurrings();
	}

	function expense_recurrings() {
		foreach ($this->Expenses_Model->get_all_recurring() as $key => $value) {
			if($value['relation_type'] == 'expense') {
				if(($value['end_date'] != 'Invalid date') && (strtotime(date("Y-m-d",strtotime($value['end_date']))) <= strtotime(date('Y-m-d')))) {
					continue;
				}
				$id = $value['relation'];
				$invv = $this->db->get_where( 'expenses', array( 'id' => $value['relation'] ) );
				$invv = $invv->result_array();
				$invv = end($invv);
				if($invv['last_recurring'] != NULL && date('Y-m-d',strtotime($invv['last_recurring'])) == date('Y-m-d')) {
					continue;
				}
				// Years
				if($value['type'] == '3' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'Years')) != date('Y-m-d')) {
					continue;
				}
				// Month
				if($value['type'] == '2' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'month')) != date('Y-m-d')) {
					continue;
				}
				// Week
				if($value['type'] == '1' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'week')) != date('Y-m-d')) {
					continue;
				}
				// Day
				if($value['type'] == '0' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'day')) != date('Y-m-d')) {
					continue;
				}
				$expense = $this->Expenses_Model->get_expenses($id);
				$expense_data = array(
					'category_id' => $expense['category_id'],
					'staff_id' => $expense['staff_id'],
					'customer_id' => $expense['customer_id'],
					'account_id' => $expense['account_id'],
					'title' => $expense['title'],
					'number' => $expense['number'],
					'date' => $expense['date'],
					'created' => date( 'Y-m-d H:i:s' ),
					'amount' => $expense['amount'],
					'total_tax' => $expense['total_tax'],
					'total_discount' => $expense['total_discount'],
					'sub_total' => $expense['sub_total'],
					'internal' => $expense['internal'],
					'last_recurring' => date('Y-m-d'),
					'expense_created_by' => $this->session->usr_id
				);
				$items = $this->db->select('*')->get_where( 'items', array( 'relation_type' => 'expense', 'relation' => $id ) )->result_array();
				$expense_id = $this->Expenses_Model->recurring_expense($expense_data, $items);

				if ($expense_id) {
					$this->Expenses_Model->update_recurring_date($id);
					$this->Settings_Model->create_process('pdf', $expense_id, 'expense', 'expense_recurring');
				}
			}
		}
		$this->purchase_recurrings();
	}

	function emails() {
		$emails = $this->Emails_Model->get_emails();
		if ($emails) {
			$path = NULL;
			foreach ($emails as $key => $email) {
				$path = $email['attachments'];
				$this->load->library('mail');
				$data_email = @unserialize($email['email']);
				if ($data_email !== false) {
					$recipient = unserialize($email['email']);
				} else {
					$recipient = $email['email'];
				}
				$data = $this->mail->send_email($recipient, $email['from_name'], $email['subject'], $email['message'], $path);
				$this->email->clear(TRUE);
				if ($data['success'] == true) {
					$this->Emails_Model->email_sent($email['id']);
					$path = NULL;
					unset($path);
				}
			}
		}
		$this->check_events();
		$this->check_pending_process();
	}   

	function check_events() {
		foreach ($this->Events_Model->get_event_triggers() as $key => $value) {
			if($value['relation_type'] == 'event') {
				$type = 'minute';
				switch ($value['duration_type']) {
					case '0':
					$type = 'minutes';
					break;
					case '1':
					$type = 'hour';
					break;
					case '2':
					$type = 'day';
					break;
					case '3':
					$type = 'week';
					break;
				}
				$time = strtotime((date('Y-m-d H:i:s',strtotime($value['start'].' +'.$value['duration_period'].$type))));
				$present = strtotime(date_by_timezone(date('Y-m-d H:i:s')));
				// echo strtotime((date('Y-m-d H:i:s',strtotime($value['start'].' +'.$value['duration_period'].$type)))).' calculated'.'<br>';
				// echo strtotime($value['start']).' start'.'<br>'.'<br>';

				// echo $time.' calculated'.'<br>';
				// echo $present.' now'.'<br>'.'<br>';
				// echo $value['id'].'<br>';
				// echo $type.'<br>';
				// echo $value['duration_period'].'<br>';
				// echo $value['start'].'<br>';
				// echo (date('Y-m-d H:i:s',strtotime($value['start'].' +'.$value['duration_period'].$type))).' calculated'.'<br>';
				// echo date_by_timezone(date('Y-m-d H:i:s')).' now'.'<br>';
				// echo strtotime((date('Y-m-d H:i:s',strtotime($value['start'].' +'.$value['duration_period'].$type)))).'<br>';
				// echo strtotime($value['start']).'<br>'.'<br>'.'<br>';

				if ($present >= $time) {
					$event = $this->Events_Model->get_event($value['relation']);
					if ($value['type'] == 'reminder') {
						echo json_encode($event);
						$reminder = array(
							'relation_type' => 'event',
							'relation' => $event['id'],
							'staff_id' => $event['staff_id'],
							'description' => $event['title'],
							'date' => $event['start'],
							'isnotified' => '0',
							'addedfrom' => $event['added_by'],
							'public' => ($event['public']=='1')?'1':'0',
						);
						$this->db->insert( 'reminders', $reminder );
						$this->Events_Model->update_event_trigger($value['id']);
					}
					if ($value['type'] == 'email') {
						$template = $this->Emails_Model->get_template('staff', 'event_reminder');
						if ($template['status'] == 1) {
							$event = $this->Events_Model->get_event($value['relation']);
							$settings = $this->Settings_Model->get_settings_ciuis();
							$message_vars = array(
								'{company_name}' => $settings['company'],
								'{company_email}' => $settings['email'],
								'{staff}' => $event['staffname'],
								'{staff_email}' => $event['email'],
								'{event_title}' => $event['title'],
								'{event_type}' => $event['event_type'],
								'{event_details}' => $event['detail'],
								'{event_start}' => $event['start'],
								'{event_end}' => $event['end'],
							);
							$subject = strtr($template['subject'], $message_vars);
							$message = strtr($template['message'], $message_vars);
							if ($event['is_all'] == '1') {
								$staffs = $this->Events_Model->get_all_staffs();
								foreach ($staffs as $staff) {
									$recipients[] = $staff['email'];
								}
							} else {
								$recipients[] = $event['email'];
							}
							if (count($recipients) > 0) {
								$param = array(
									'from_name' => $template['from_name'],
									'email' => serialize($recipients),
									'subject' => $subject,
									'message' => $message,
									'created' => date_by_timezone(date('Y-m-d H:i:s')),
								);
								$this->db->insert( 'email_queue', $param );
								$this->Events_Model->update_event_trigger($value['id']);
							}
						}
					}
				}
			}
		}
	}

	function check_pending_process() {
		$settings = $this->Settings_Model->get_settings_ciuis();
		$processes = $this->Settings_Model->get_pending_process();
		if ($processes) {
			foreach ($processes as $key => $value) {
				$path = NULL;
				unset($path);
				if ($value['process_type']) {
					if ( $value['process_type'] == 'pdf') {

						//Invoice
						if($value['process_relation_type'] == 'invoice' ) {
							$invoice = $this->Invoices_Model->get_invoice_detail( $value['process_relation'] );
							$path = '';
							if ( $invoice['pdf_status'] == 0) {
								$this->Invoices_Model->generate_pdf($invoice['id']); 
							}
							$file = get_number('invoices', $invoice['id'], 'invoice', 'inv');
							$path = base_url('uploads/files/invoices/'.$invoice['id'].'/'.$file.'.pdf');
							if ($value['process_template_name'] == 'invoice_message') {
								$template = $this->Emails_Model->get_template('invoice', 'invoice_message');
								if ($template['status'] == 1) {
									$inv_number = get_number('invoices', $invoice['id'], 'invoice', 'inv');
									$name = $invoice['customercompany'] ? $invoice['customercompany'] : $invoice['individualindividual'];
									$link = base_url('share/invoice/'.$invoice[ 'token' ].'');
									if ( $invoice['status_id'] == 1 ) { 
										$invoicestatus = lang( 'draft' );
									}
									if ( $invoice['status_id'] == 3 ) {
										$invoicestatus = lang( 'unpaid' );
									}
									if ( $invoice['status_id'] == 4 ) {
										$invoicestatus = lang( 'cancelled' );
									}
									if ( $invoice['status_id'] == 2 ) {
										$invoicestatus = lang( 'partial' );
									}
									$message_vars = array(
										'{invoice_number}' => $inv_number,
										'{invoice_link}' => $link,
										'{invoice_status}' => $invoicestatus,
										'{name}' => $settings['company'],
										'{email_signature}' => $settings['email'],
										'{customer}' => $name,
										'{company_name}' => $settings['company'],
										'{company_email}' => $settings['email'],
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
									if ($template['attachment'] == 1) {
										$param['attachments'] = $path?$path:NULL;
									}
									if ($invoice['email']) {
										$this->db->insert( 'email_queue', $param );
										$this->Settings_Model->remove_pending_process($value['process_id']);
									}
									$path = null;
									unset($path);
								}
							}
							if ($value['process_template_name'] == 'invoice_recurring') {
								$template = $this->Emails_Model->get_template('invoice', 'invoice_recurring');
								if ($template['status'] == 1) {
									$inv_number = get_number('invoices',$invoice['id'],'invoice','inv');
									$name = $invoice['customercompany'] ? $invoice['customercompany'] : $invoice['individualindividual'];
									$link = base_url( 'share/invoice/' . $invoice[ 'token' ] . '' );
									$message_vars = array(
										'{invoice_number}' => $inv_number,
										'{invoice_link}' => $link,
										'{invoice_status}' => lang( 'unpaid' ),
										'{email_signature}' => $settings['email'],
										'{customer}' => $name,
										'{name}' => $settings['company'],
										'{company_name}' => $settings['company'],
										'{company_email}' => $settings['email'],
									);
									$subject = strtr($template['subject'], $message_vars);
									$message = strtr($template['message'], $message_vars);
									$param = array(
										'from_name' => $template['from_name'],
										'email' => $invoice['email'],
										'subject' => $subject,
										'message' => $message,
										'created' => date_by_timezone(date('Y-m-d H:i:s')),
									);
									if ($template['attachment'] == 1) {
										$param['attachments'] = $path?$path:NULL;
									}
									if ($invoice['email']) {
										$this->db->insert( 'email_queue', $param );
										$this->Settings_Model->remove_pending_process($value['process_id']);
									}
									$path = null;
									unset($path);
								}
							}
						}

						//Expense
						if ( $value['process_relation_type'] == 'expense') {
							$expense = $this->Expenses_Model->get_expenses( $value['process_relation'] );
							$path = '';
							if ( $expense['pdf_status'] == 0) {
								$this->Expenses_Model->generate_pdf($expense['id']);
							}
							$file = get_number('expenses', $expense['id'], 'expense', 'expense');
							$path = base_url('uploads/files/expenses/'.$expense['id'].'/'.$file.'.pdf');
							if ($value['process_template_name'] == 'expense_created') {
								$template = $this->Emails_Model->get_template('expense', 'expense_created');
								if ($template['status'] == '1') {
									if ( $expense[ 'namesurname' ] ) {
										$customer = $expense[ 'namesurname' ];
									} else {
										$customer = $expense[ 'customer' ];
									}
									$message_vars = array(
										'{customer}' => $customer,
										'{expense_number}' => get_number('expenses', $expense[ 'id' ], 'expense','expense'),
										'{expense_title}' => $expense[ 'title' ],
										'{expense_category}' => $expense[ 'category' ],
										'{expense_date}' => $expense[ 'date' ],
										'{expense_description}' => $expense[ 'description' ],
										'{expense_amount}' => $expense[ 'amount' ],
										'{name}' => $settings['company'],
										'{email_signature}' => $settings['email'],
										'{company_name}' => $settings['company'],
										'{company_email}' => $settings['email'],
									);
									$subject = strtr($template['subject'], $message_vars);
									$message = strtr($template['message'], $message_vars);
									$email = $expense['customeremail'] ? $expense['customeremail'] : $expense['staffemail'];
									$consultants = $this->Expenses_Model->get_consultants();
									$recipients = array();
									foreach ($consultants as $consultant) {
										$recipients[] = $consultant['email'];
									}
									$recipients[] = $email;
									if ($email) {
										$param = array(
											'from_name' => $template['from_name'],
											'email' => serialize($recipients),
											'subject' => $subject,
											'message' => $message,
											'created' => date_by_timezone(date('Y-m-d H:i:s')),
										);
										if ($template['attachment'] == 1){
											$param['attachments'] = $path?$path:NULL;
										}
										$this->db->insert( 'email_queue', $param );
										$this->Settings_Model->remove_pending_process($value['process_id']);
									}
								}
							}
							if ($value['process_template_name'] == 'expense_recurring') {
								$template = $this->Emails_Model->get_template('expense', 'expense_recurring');
								if ($template['status'] == 1) {
									$customer = '';
									if ($expense[ 'namesurname' ] || $expense[ 'customer' ]) {
										if ( $expense[ 'namesurname' ] ) {
											$customer = $expense[ 'namesurname' ];
										} else {
											$customer = $expense[ 'customer' ];
										}
									}
									$message_vars = array(
										'{customer}' => $customer,
										'{staff}' => $expense[ 'staff' ],
										'{expense_number}' =>  get_number('expenses',$expense['id'],'expense','expense'),
										'{expense_title}' => $expense[ 'title' ],
										'{expense_category}' => $expense[ 'category' ],
										'{expense_date}' => $expense[ 'date' ],
										'{expense_description}' => $expense[ 'description' ],
										'{expense_amount}' => $expense[ 'amount' ],
										'{name}' => $settings['company'],
										'{email_signature}' => $settings['email'],
										'{company_name}' => $settings['company'],
										'{company_email}' => $settings['email'],
									);
									$subject = strtr($template['subject'], $message_vars);
									$message = strtr($template['message'], $message_vars);
									$email = $expense['staffemail'];
									if ($email) {
										$param = array(
											'from_name' => $template['from_name'],
											'email' => $email,
											'subject' => $subject,
											'message' => $message,
											'created' => date_by_timezone(date('Y-m-d H:i:s')),
										);
										if ($template['attachment'] == 1){
											$param['attachments'] = $path?$path:NULL;
										}
										$this->db->insert( 'email_queue', $param );
									}
								}
								//$template_c = $this->Emails_Model->get_template('expense', 'expense_consultant');
								if ($template['status'] == 1) {
									$customer = '';
									if ($expense[ 'namesurname' ] || $expense[ 'customer' ]) {
										if ( $expense[ 'namesurname' ] ) {
											$customer = $expense[ 'namesurname' ];
										} else {
											$customer = $expense[ 'customer' ];
										}
									}
									$message_vars = array(
										'{customer}' => $customer,
										'{staff}' => $expense[ 'staff' ],
										'{expense_number}' =>  get_number('expenses',$expense['id'],'expense','expense'),
										'{expense_title}' => $expense[ 'title' ],
										'{expense_category}' => $expense[ 'category' ],
										'{expense_date}' => $expense[ 'date' ],
										'{expense_description}' => $expense[ 'description' ],
										'{expense_amount}' => $expense[ 'amount' ],
										'{name}' => $settings['company'],
										'{email_signature}' => $settings['email'],
										'{company_name}' => $settings['company'],
										'{company_email}' => $settings['email'],
									);
									$subject = strtr($template['subject'], $message_vars);
									$message = strtr($template['message'], $message_vars);
									$consultants = $this->Expenses_Model->get_consultants();
									$recipients = array();
									foreach ($consultants as $consultant) {
										$recipients[] = $consultant['email'];
									}
									if (count($recipients) > 0) {
										$param = array(
											'from_name' => $template['from_name'],
											'email' => serialize($recipients),
											'subject' => $subject,
											'message' => $message,
											'created' => date_by_timezone(date('Y-m-d H:i:s')),
										);
										if ($template['attachment'] == 1) {
											$param['attachments'] = $path?$path:NULL;
										}
										$this->db->insert( 'email_queue', $param );
									}
								}
							}
							$this->Settings_Model->remove_pending_process($value['process_id']);
							$path = NULL;
							unset($path);
						}

						//Purchase
						if ( $value['process_relation_type'] == 'purchase') {
							$purchase = $this->Purchases_Model->get_purchases_detail( $value['process_relation'] );
							$path = '';
							if ( $purchase['pdf_status'] == 0) {
								$this->Purchases_Model->generate_pdf($purchase['id']);
							}
							$file = get_number('purchases', $purchase['id'], 'purchase', 'purchase');
							$path = base_url('uploads/files/purchases/'.$purchase['id'].'/'.$file.'.pdf');
							if ($value['process_template_name'] == 'purchase_message') {
								$template = $this->Emails_Model->get_template('purchase', 'purchase_message');
								if ($template['status'] == 1) {
									if ($purchase['status_id'] == '2' ) {
										$payments = $this->Expenses_Model->get_expense_by_purchase($purchase['id']);
										$payments_details = $this->Payments_Model->get_payment_details($payments);
									}
									$purchase_number = get_number('purchases', $purchase['id'], 'purchase', 'purchase');
									if ( $purchase[ 'status_id' ] == 1 ) {
										$purchasestatus = lang( 'draft' );
									}
									if ( $purchase[ 'status_id' ] == 3 ) {
										$purchasestatus = lang( 'unpaid' );
									}
									if ( $purchase[ 'status_id' ] == 4 ) {
										$purchasestatus = lang( 'cancelled' );
									}
									if ( $purchase[ 'status_id' ] == 2 ) {
										$purchasestatus = lang( 'partial' );
									}
									$name = $purchase[ 'vendorcompany' ];
									$link = base_url( 'share/purchases/' . $purchase[ 'token' ] . '' );
									if($purchase['status_id'] == '2'){
										$message_vars = array(
											'{purchase_number}' => $purchase_number,
											'{vendor_name}' => $name,
											'{issuance_date}' => $purchase['created'],
											'{due_date}' => $purchase['duedate'],
											'{payment_date}' => $payments_details['date'],
											'{payment_amount}' => $payments_details['amount'],
											'{payment_account}' => $payments_details['name'],
											'{payment_description}' => $payments_details['not'],
											'{payment_made_by}' => $payments_details['staffname'],
											'{purchase_status}' => $purchasestatus,
											'{total_amount}' => $purchase['total'],
											'{company_name}' =>  $settings['company'] ,
											'{company_email}' =>  $settings['email'],
											'{due_note}' => $purchase['duenote'],
											'{purchase_link}' => $link,
										);
									} else {
										$message_vars = array(
											'{purchase_number}' => $purchase_number,
											'{vendor_name}' => $name,
											'{issuance_date}' => $purchase['created'],
											'{due_date}' => $purchase['duedate'],
											'{purchase_status}' => $purchasestatus,
											'{total_amount}' => $purchase['total'],
											'{company_name}' =>  $settings['company'] ,
											'{company_email}' =>  $settings['email'],
											'{due_note}' => $purchase['duenote'],
											'{purchase_link}' => $link,
										);
									}
									$subject = strtr($template['subject'], $message_vars);
									$message = strtr($template['message'], $message_vars);
									
									$param = array(
										'from_name' => $template['from_name'],
										'email' => $purchase['email'],
										'subject' => $subject,
										'message' => $message,
										'created' => date_by_timezone(date('Y-m-d H:i:s')),
									);
									if ($template['attachment'] == 1) {
										$param['attachments'] = $path?$path:NULL;
									}
									if ($purchase['email']) {
										$this->db->insert( 'email_queue', $param );
										$this->Settings_Model->remove_pending_process($value['process_id']);
									}
									$path = NULL;
									unset($path);
								}
							}
							if ($value['process_template_name'] == 'purchase_recurring') {
								$template = $this->Emails_Model->get_template('purchase', 'purchase_recurring');
								if ($template['status'] == 1) {
									$purchase_number = get_number('purchases',$purchase['id'],'purchase','purchase');
									$name = $purchase['vendorcompany'];
									$link = base_url( 'share/purchase/' . $purchase[ 'token' ] . '' );
									$message_vars = array(
										'{purchase_number}' => $purchase_number,
										'{vendor_name}' => $name,
										'{issuance_date}' => $purchase['created'],
										'{due_date}' => $purchase['duedate'],
										'{purchase_status}' => lang( 'unpaid' ),
										'{total_amount}' => $purchase['total'],
										'{company_name}' =>  $settings['company'] ,
										'{company_email}' =>  $settings['email'],
										'{due_note}' => $purchase['duenote'],
										'{purchase_link}' => $link,
									);
									$subject = strtr($template['subject'], $message_vars);
									$message = strtr($template['message'], $message_vars);
									$param = array(
										'from_name' => $template['from_name'],
										'email' => $purchase['email'],
										'subject' => $subject,
										'message' => $message,
										'created' => date_by_timezone(date('Y-m-d H:i:s')),
									);
									if ($template['attachment'] == 1) {
										$param['attachments'] = $path?$path:NULL;
									}
									if ($purchase['email']) {
										$this->db->insert( 'email_queue', $param );
										$this->Settings_Model->remove_pending_process($value['process_id']);
									}
									$path = NULL;
									unset($path);
								}
							}
						}

						//Proposal
						if ( $value['process_relation_type'] == 'proposal') {
							$pro = $this->Proposals_Model->get_pro_rel_type( $value['process_relation'] );
							$rel_type = $pro[ 'relation_type' ];
							$proposal = $this->Proposals_Model->get_proposals( $value['process_relation'], $rel_type );
							if ( $proposal['pdf_status'] == 0) {
								$this->Proposals_Model->generate_pdf($proposal['id']);
							}
							$file = get_number('proposals', $proposal['id'], 'proposal', 'proposal');
							$path = base_url('uploads/files/proposals/'.$proposal['id'].'/'.$file.'.pdf');
							if ($value['process_template_name'] == 'send_proposal') {
								$template = $this->Emails_Model->get_template('proposal', 'send_proposal');
								if ($template['status'] == 1) {
									if ($rel_type == 'customer') { 
										$customer = $proposal['customercompany'] ? $proposal['customercompany'] : $proposal['namesurname'];
									} else {
										$customer = $proposal['leadname'];
									}
									$link = base_url( 'share/proposal/' . $proposal[ 'token' ] . '' );
									$message_vars = array(
										'{proposal_to}' => $customer,
										'{customer}' => $customer,
										'{proposal_number}' => get_number('proposals', $proposal['id'], 'proposal', 'proposal'),
										'{proposal_link}' => $link,
										'{subject}' => $this->input->post( 'subject' ),
										'{details}' => $this->input->post( 'content' ),
										'{name}' => $settings['company'],
										'{email_signature}' => $settings['email'],
										'{open_till}' => _pdate( $proposal['opentill'] ),
										'{company_name}' => $settings['company'],
										'{company_email}' => $settings['email'],
									);
									$subject = strtr($template['subject'], $message_vars);
									$message = strtr($template['message'], $message_vars);
									$param = array(
										'from_name' => $template['from_name'],
										'email' => $proposal['toemail'],
										'subject' => $subject,
										'message' => $message,
										'created' => date_by_timezone(date('Y-m-d H:i:s')),
									);
									if ($template['attachment'] == '1') {
										$param['attachments'] = $path?$path:NULL;
									}
									if ($proposal['toemail']) {
										$this->db->insert( 'email_queue', $param );
										$this->Settings_Model->remove_pending_process($value['process_id']);
									}
								}
							}
						}

						//Order
						if ( $value['process_relation_type'] == 'order') {
							$order = $this->Orders_Model->get_orders( $value['process_relation'], $rel_type );
							$data['orders'] = $this->Orders_Model->get_orders( $value['process_relation'], $rel_type );
							$path = '';
							if ($order['pdf_status'] == 1){
								$this->Orders_Model->generate_pdf($order['id']);
							}
							$file = get_number('orders', $order['id'], 'order', 'order');
							$path = base_url('uploads/files/orders/'.$order['id'].'/'.$file.'.pdf');
							if ($value['process_template_name'] == 'order_message') {
								$template = $this->Emails_Model->get_template('order','order_message');
								if ($template['status'] == 0) {
									$pro = $this->Orders_Model->get_pro_rel_type( $value['process_relation'] );
									$rel_type = $pro[ 'relation_type' ];
									if ( $rel_type == 'customer' ) {
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
										$orderto = $order['leadname'];
										$ordertoemail = $order['toemail'];
									}
									$order_number = get_number('orders', $order['id'], 'order', 'order');
									$message_vars = array(
										'{customer}' => $orderto,
										'{order_to}' => $orderto,
										'{name}' => $settings['company'],
										'{email_signature}' => $settings['email'],
										'{order_number}' => $order_number,
										'{app_name}' => $settings['company'],
										'{company_name}' => $settings['company'],
										'{company_email}' => $settings['email'],
									);
									$subject = strtr($template['subject'], $message_vars);
									$message = strtr($template['message'], $message_vars);
									$param = array(
										'from_name' => $template['from_name'],
										'email' => $ordertoemail,
										'subject' => $subject,
										'message' => $message,
										'created' => date_by_timezone(date('Y-m-d H:i:s')),
									);
									if ($template['attachment'] == 1) {
										$param['attachments'] = $path?$path:NULL;
									}
									if ($ordertoemail) {
										$this->db->insert( 'email_queue', $param );
										$this->Settings_Model->remove_pending_process($value['process_id']);
									}
									$path = null;
									unset($path);
								}
							}
						}

						//Deposit
						if ( $value['process_relation_type'] == 'deposit') {
							$deposit = $this->Deposits_Model->get_deposits( $value['process_relation'], '' );
							$path = '';
							if ($deposit['pdf_status'] == 0) {
								$this->Deposits_Model->generate_pdf($deposit['id']);
							}
							$file = get_number('deposits',$deposit['id'],'deposit','deposit');
							$path = base_url('uploads/files/deposits/'.$deposit['id'].'/'.$file.'.pdf');
							if ( $value['process_template_name'] == 'deposit_message') {
								$template = $this->Emails_Model->get_template('deposit', 'deposit_message');
								if ($template['status'] == '1') {
									if ( $deposit[ 'individual' ] ) {
										$customer = $deposit[ 'individual' ];
									} else {
										$customer = $deposit[ 'customer' ] ;
									}
									if ( $deposit[ 'deposit_status' ] == '1' ) {
										$depositstatus = lang( 'paid' );
									} else if ( $deposit[ 'deposit_status' ] == '2' ) {
										$depositstatus = lang( 'internal' );
									} else if ( $deposit[ 'deposit_status' ] == '0' ) {
										$depositstatus = lang( 'unpaid' );
									}
									$link = base_url('share/deposit/'.$deposit[ 'token' ].'');
									$message_vars = array(
										'{deposit_number}' => get_number('deposits', $deposit[ 'id' ], 'deposit','deposit'),
										'{customer_name}' => $customer,
										'{deposit_date}' => $deposit['date'],
										'{deposit_amount}' => $deposit['amount'],
										'{deposit_status}' => $depositstatus,
										'{deposit_link}' => $link,
										'{company_name}' =>  $settings['company'] ,
										'{company_email}' =>  $settings['email'],
									);
									$subject = strtr($template['subject'], $message_vars);
									$message = strtr($template['message'], $message_vars);
									$param = array(
										'from_name' => $template['from_name'],
										'email' =>  $deposit['customeremail'],
										'subject' => $subject,
										'message' => $message,
										'created' => date_by_timezone(date('Y-m-d H:i:s')),
									);
									if ($template['attachment'] == 1) {
										$param['attachments'] = $path?$path:NULL;
									}
									if($deposit['customeremail']) {
										$this->db->insert( 'email_queue', $param );
										$this->Settings_Model->remove_pending_process($value['process_id']);
									}
									$path = null;
									unset($path);
								}
							}
							if ( $value['process_template_name'] == 'recurring_deposit') {
								$template = $this->Emails_Model->get_template('deposit', 'recurring_deposit');
								if ($template['status'] == '1') {
									if ( $deposit[ 'individual' ] ) {
										$customer = $deposit[ 'individual' ];
									} else {
										$customer = $deposit[ 'customer' ];
									}
									if ( $deposit[ 'deposit_status' ] == '1' ) {
										$depositstatus = lang( 'paid' );
									} else if ( $deposit[ 'deposit_status' ] == '2' ) {
										$depositstatus = lang( 'internal' );
									} else if ( $deposit[ 'deposit_status' ] == '0' ) {
										$depositstatus = lang( 'unpaid' );
									}
									$link = base_url('share/deposit/'.$deposit[ 'token' ].'');
									$message_vars = array(
										'{deposit_number}' => get_number('deposits', $deposit[ 'id' ], 'deposit','deposit'),
										'{customer_name}' => $customer,
										'{deposit_date}' => $deposit['date'],
										'{deposit_amount}' => $deposit['amount'],
										'{deposit_status}' => $depositstatus,
										'{deposit_link}' => $link,
										'{company_name}' =>  $settings['company'] ,
										'{company_email}' =>  $settings['email'],
									);
									$subject = strtr($template['subject'], $message_vars);
									$message = strtr($template['message'], $message_vars);
									$param = array(
										'from_name' => $template['from_name'],
										'email' =>  $deposit['customeremail'],
										'subject' => $subject,
										'message' => $message,
										'created' => date_by_timezone(date('Y-m-d H:i:s')),
									);
									if ($template['attachment'] == 1) {
										$param['attachments'] = $path?$path:NULL;
									}
									if($deposit['customeremail']) {
										$this->db->insert( 'email_queue', $param );	
										$this->Settings_Model->remove_pending_process($value['process_id']);
									}
									$path = null;
									unset($path);
								}
							}
						}
					}
				}
			}
		}
	}

	function purchase_recurrings() {
		foreach ($this->Purchases_Model->get_all_recurring() as $key => $value) {
			if($value['relation_type'] == 'purchase') {
				if(($value['end_date'] != 'Invalid date') && (strtotime(date("Y-m-d",strtotime($value['end_date']))) <= strtotime(date('Y-m-d')))) {
					continue;
				}
				$id = $value['relation'];
				// $purchase = $this->Purchases_Model->get_purchases($id);
				$po = $this->db->get_where( 'purchases', array( 'id' => $value['relation'] ) );
				$po = $po->result_array();
				$po = end($po);
				if($po['last_recurring'] != NULL && date('Y-m-d',strtotime($po['last_recurring'])) == date('Y-m-d')) {
					continue;
				}
				// Years
				if($value['type'] == '3' && date('Y-m-d',strtotime($po['last_recurring'].' +'.$value['period'].'Years')) != date('Y-m-d')) {
					continue;
				}
				// Month
				if($value['type'] == '2' && date('Y-m-d',strtotime($po['last_recurring'].' +'.$value['period'].'month')) != date('Y-m-d')) {
					continue;
				}
				// Week
				if($value['type'] == '1' && date('Y-m-d',strtotime($po['last_recurring'].' +'.$value['period'].'week')) != date('Y-m-d')) {
					continue;
				}
				// Day
				if($value['type'] == '0' && date('Y-m-d',strtotime($po['last_recurring'].' +'.$value['period'].'day')) != date('Y-m-d')) {
					continue;
				}
				$purchase = $this->Purchases_Model->get_purchases($id);
				$created = date('Y-m-d');
				$purchases = array(
					'token' => md5( uniqid() ),
					'no' => $purchase['no'],
					'serie' => $purchase['serie'],
					'vendor_id' => $purchase['vendor_id'],
					'staff_id' => $purchase['staff_id'],
					'status_id' => 3,
					'created' => $created,
					'last_recurring' => $created,
					'duedate' => date('Y-m-d' ,strtotime($created . '+30 days')), // +30 day
					'duenote' => $purchase['duenote'],
					'sub_total' => $purchase['sub_total'],
					'total_discount' => $purchase['total_discount'],
					'total_tax' => $purchase['total_tax'],
					'total' => $purchase['total'],
					'recurring' => $value['id'],
				);
				$items = $this->db->select('*')->get_where( 'items', array( 'relation_type' => 'purchase', 'relation' => $id ) )->result_array();
				$purchases_id = $this->Purchases_Model->recurring_purchases($purchases,$items);
				if ($purchases_id) {
					$this->Purchases_Model->update_recurring_date($id);
					$this->Settings_Model->create_process('pdf', $purchases_id, 'purchase', 'purchase_recurring');
				}
			}
		}
		$this->deposit_recurrings();
	}

	function deposit_recurrings() {
		foreach ($this->Deposits_Model->get_all_recurring() as $key => $value) {
			if($value['relation_type'] == 'deposit') {
				if(($value['end_date'] != 'Invalid date') && (strtotime(date("Y-m-d",strtotime($value['end_date']))) <= strtotime(date('Y-m-d')))) {
					continue;
				}
				$id = $value['relation'];
				$invv = $this->db->get_where( 'deposits', array( 'id' => $value['relation'] ) );
				$invv = $invv->result_array();
				$invv = end($invv);
				if($invv['last_recurring'] != NULL && date('Y-m-d',strtotime($invv['last_recurring'])) == date('Y-m-d')) {
					continue;
				}
				// Years
				if($value['type'] == '3' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'Years')) != date('Y-m-d')) {
					continue;
				}
				// Month
				if($value['type'] == '2' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'month')) != date('Y-m-d')) {
					continue;
				}
				// Week
				if($value['type'] == '1' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'week')) != date('Y-m-d')) {
					continue;
				}
				// Day
				if($value['type'] == '0' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'day')) != date('Y-m-d')) {
					continue;
				}
				$deposit = $this->Deposits_Model->get_deposits($id);
				$deposit_data = array(
					'token' => md5( uniqid() ),
					'relation_type' => 'deposit',
					'category_id' => $deposit['category_id'],
					'staff_id' => $deposit['depositstaff'],
					'customer_id' => $deposit['customer_id'],
					'account_id' => $deposit['account_id'],
					'title' => $deposit['title'],
					'description' => $deposit['desc'],
					'date' => $deposit['date'],
					'created' => date('Y-m-d'),
					'amount' => $deposit['amount'],
					'total_tax' => $deposit['total_tax'],
					'sub_total' => $deposit['sub_total'],
					'status' => $deposit['deposit_status'],
					'recurring' => $value['id'],
					'last_recurring' => date('Y-m-d')
				);
				$items = $this->db->select('*')->get_where( 'items', array( 'relation_type' => 'deposit', 'relation' => $id ) )->result_array();
				$deposit_id = $this->Deposits_Model->recurring_deposits($deposit_data, $items);

				if ($deposit_id) {
					$this->Deposits_Model->update_recurring_date($id);
					$this->Settings_Model->create_process('pdf', $deposit_id, 'deposit', 'recurring_deposit');
				}
			}
		}
	}
}
