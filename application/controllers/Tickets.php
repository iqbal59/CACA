<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Tickets extends CIUIS_Controller {

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
		$data[ 'title' ] = lang( 'tickets' );
		$data[ 'tickets' ] = $this->Tickets_Model->get_all_tickets();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'tickets/index', $data );
	}

	function create() {
		$data[ 'title' ] = lang( 'addticket' );
		if ( $this->Privileges_Model->check_privilege( 'tickets', 'create' ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$hasError = false;
				$data['message'] = '';
				if ($this->input->post( 'subject' ) == '' || $this->input->post( 'subject' ) == 'undefined') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('subject');
				} else if ($this->input->post( 'customer' ) == '' || $this->input->post( 'customer' ) == 'undefined') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('customer');
				} else if ($this->input->post( 'contact' ) == '' || $this->input->post( 'contact' ) == 'undefined') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('contact');
				} else if ($this->input->post( 'department' ) == '' || $this->input->post( 'department' ) == 'undefined') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('department');
				} else if ($this->input->post( 'priority' ) == '' || $this->input->post( 'priority' ) == 'undefined') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('priority');
				} else if ($this->input->post( 'message' ) == '' || $this->input->post( 'message' ) == 'undefined') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('message');
				}
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					$appconfig = get_appconfig();
					if (isset($_FILES['file']) && $_FILES['file']['name'] != '') {
						$config[ 'upload_path' ] = './uploads/attachments/';
						$config[ 'allowed_types' ] = 'zip|rar|tar|gif|jpg|png|jpeg|gif|pdf|doc|docx|xls|xlsx|txt|csv|ppt|opt';
						$config['max_size'] = '9000';
						$new_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES["file"]['name']));
						$config['file'] = $new_name;
						$this->load->library( 'upload', $config );
						$this->upload->do_upload('file');
						$data_upload_files = $this->upload->data();
						$image_data = $this->upload->data();
						$filename = $image_data['file_name'];
					} else {
						$filename = NULL;
					}

					$params = array(
						'contact_id' => $this->input->post( 'contact' ),
						'customer_id' => $this->input->post( 'customer' ),
						'department_id' => $this->input->post( 'department' ),
						'priority' => $this->input->post( 'priority' ),
						'status_id' => 1,
						'subject' => $this->input->post( 'subject' ),
						'message' => $this->input->post( 'message' ),
						'attachment' => $filename,
						'date' => date( " Y.m.d H:i:s " ),
						'staff_id' => $this->session->usr_id,
					);
					$this->session->set_flashdata( 'ntf1', lang( 'ticketadded' ) );
					$tickets_id = $this->Tickets_Model->add_tickets( $params );
					if ($tickets_id) {
						if ( $this->input->post( 'custom_fields' ) ) {
							$custom_fields = array(
								'custom_fields' => $this->input->post( 'custom_fields' )
							);
							$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'ticket', $tickets_id );
						} 
						$template = $this->Emails_Model->get_template('ticket', 'new_ticket');
						if ($template['status'] == 1) {
							$ticket = $this->Tickets_Model->get_tickets( $tickets_id );
							if ( $ticket[ 'type' ] == 0 ) {
								$customer = $ticket[ 'company' ];
							} else {
								$customer = $ticket[ 'namesurname' ]; 
							} 
							switch ( $ticket[ 'priority' ] ) {
								case '1':
									$priority = lang( 'low' );
									break;
								case '2':
									$priority = lang( 'medium' );
									break;
								case '3':
									$priority = lang( 'high' );
									break;
							};
							$message_vars = array(
								'{customer}' => $customer,
								'{name}' => $this->session->userdata('staffname'),
								'{email_signature}' => $this->session->userdata('email'),
								'{ticket_subject}' => $this->input->post( 'subject' ),
								'{ticket_message}' => $this->input->post( 'message' ),
								'{ticket_priority}' => $priority,
								'{ticket_department}' => $ticket['department'],
							);
							$subject = strtr($template['subject'], $message_vars);
							$message = strtr($template['message'], $message_vars);
							$param = array(
								'from_name' => $template['from_name'],
								'email' => $ticket['customeremail'],
								'subject' => $subject,
								'message' => $message,
								'created' => date( "Y.m.d H:i:s" )
							);
							if ($ticket['customeremail']) {
								$this->db->insert( 'email_queue', $param );
							}
						}
						$data['success'] = true;
						$data['message'] = lang('ticket').' '.lang('createmessage');
						$data['id'] = $tickets_id;
						if($appconfig['ticket_series']){
							$ticket_number = $appconfig['ticket_series'];
							$ticket_number = $ticket_number + 1 ;
							$this->Settings_Model->increment_series('ticket_series',$ticket_number);
						}
						echo json_encode($data);
					} else {
						$data['success'] = false;
						$data['message'] = lang('errormessage');
						echo json_encode($data);
					}
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function ticket( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'tickets', 'all' ) ) {
			$data['ticket'] = $this->Tickets_Model->get_ticket_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'tickets', 'own') ) {
			$data['ticket'] = $this->Tickets_Model->get_ticket_by_privileges( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('tickets'));
		}
		if($data['ticket']) {
			$data[ 'title' ] = $data['ticket'][ 'subject' ];
			$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
			$data[ 'all_staff' ] = $this->Staff_Model->get_all_staff();
			$this->load->view( 'tickets/ticket', $data );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('tickets'));
		}
	}

	function assign_staff( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'tickets', 'edit' ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'ticket_id' => $id,
					'staff_id' => $this->input->post( 'staff' ),
				);
				$response = $this->db->where( 'id', $id )->update( 'tickets', array( 'staff_id' => $this->input->post( 'staff' ) ) );
				$this->db->insert( 'notifications', array(
					'date' => date( 'Y-m-d H:i:s' ),
					'detail' => ( '' . $this->session->staffname . lang('assigned').' '. lang( 'ticket' ) . '-' . $id . '' ),
					'staff_id' => $this->input->post( 'staff' ),
					'perres' => $this->session->staffavatar,
					'target' => '' . base_url( 'tickets/ticket/' . $id . '' ) . ''
				) );
				$user = $this->Staff_Model->get_staff( $this->input->post( 'staff' ) );

				$template = $this->Emails_Model->get_template('ticket', 'ticket_assigned');
				if ($template['status'] == 1) {
					$ticket = $this->Tickets_Model->get_tickets( $id );
					if ( $ticket[ 'type' ] == 0 ) {
						$customer = $ticket[ 'company' ];
					} else {
						$customer = $ticket[ 'namesurname' ];
					} 

					switch ( $ticket[ 'priority' ] ) {
						case '1':
							$priority = lang( 'low' );
							break;
						case '2':
							$priority = lang( 'medium' );
							break;
						case '3':
							$priority = lang( 'high' );
							break;
					};

					$message_vars = array(
						'{assigned}' => $ticket['staffmembername'],
						'{customer}' => $customer,
						'{name}' => $this->session->userdata('staffname'),
						'{email_signature}' => $this->session->userdata('email'),
						'{ticket_subject}' => $ticket['subject'],
						'{ticket_message}' => $ticket['message'],
						'{ticket_priority}' => $priority,
						'{ticket_department}' => $ticket['department'],
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);

					$param = array(
						'from_name' => $template['from_name'],
						'email' => $ticket['staffemail'],
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" )
					);
					if ($ticket['staffemail']) {
						$this->db->insert( 'email_queue', $param );
					}
				}
				$data['name'] = $user[ 'staffname' ];
				$data['success'] = true;
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function reply( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'tickets', 'edit' ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$hasError = false;
				$data['message'] = '';
				if ($this->input->post( 'message' ) == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('message');
				}
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					$ticket = $this->Tickets_Model->get_tickets( $id );
					if (isset($_FILES['file']) && $_FILES['file']['name'] != '') {
						$config[ 'upload_path' ] = './uploads/attachments/';
						$config[ 'allowed_types' ] = 'zip|rar|tar|gif|jpg|png|jpeg|gif|pdf|doc|docx|xls|xlsx|txt|csv|ppt|opt';
						$config['max_size'] = '9000';
						$new_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES["file"]['name']));
						$config['file'] = $new_name;
						$this->load->library( 'upload', $config );
						$this->upload->do_upload('file');
						$data_upload_files = $this->upload->data();
						$image_data = $this->upload->data();
						$filename = $image_data['file_name'];
					} else {
						$filename = NULL;
					}
					$params = array(
						'ticket_id' => $id,
						'staff_id' => $this->session->userdata( 'usr_id' ),
						'contact_id' => $ticket[ 'contact_id' ],
						'date' => date( " Y-m-d h:i:sa" ),
						'name' => $this->session->userdata( 'staffname' ),
						'message' => $this->input->post( 'message' ),
						'attachment' => $filename,
					);
					$this->db->insert( 'ticketreplies', $params );
					$staffname = $this->session->staffname;
					$loggedinuserid = $this->session->usr_id;
					$this->db->insert( 'logs', array(
						'date' => date( 'Y-m-d H:i:s' ),
						'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'replied' ) . ' <a href="tickets/ticket/' . $id . '"> ' . get_number('tickets', $id, 'ticket', 'ticket') . '</a>' ),
						'staff_id' => $loggedinuserid
					) );
					$staffname = $this->session->staffname;
					$loggedinuserid = $this->session->usr_id;
					$staffavatar = $this->session->staffavatar;
					$this->db->insert( 'notifications', array(
						'date' => date( 'Y-m-d H:i:s' ),
						'detail' => ( '' . $staffname . ' '. lang( 'replied' ).' ' . get_number('tickets', $id, 'ticket', 'ticket') . '' ),
						'contact_id' => $ticket[ 'contact_id' ],
						'perres' => $staffavatar,
						'target' => '' . base_url( 'area/tickets/ticket/' . $id . '' ) . ''
					) );
					$response = $this->db->where( 'id', $id )->update( 'tickets', array(
						'status_id' => 3,
						'lastreply' => date( "Y.m.d H:i:s " ),
						'staff_id' => $loggedinuserid,
					));
					$template = $this->Emails_Model->get_template('ticket', 'ticket_reply_to_customer');
					if ($template['status'] == 1) {
						if ( $ticket[ 'type' ] == 0 ) {
							$customer = $ticket[ 'company' ];
						} else {
							$customer = $ticket[ 'namesurname' ];
						} 
						$message_vars = array(
							'{customer}' => $customer,
							'{name}' => $this->session->userdata('staffname'),
							'{email_signature}' => $this->session->userdata('email'),
							'{ticket_subject}' => $ticket['subject'],
							'{ticket_message}' => $ticket['message'],
						);
						$subject = strtr($template['subject'], $message_vars);
						$message = strtr($template['message'], $message_vars);
						$param = array(
							'from_name' => $template['from_name'],
							'email' => $ticket['customeremail'],
							'subject' => $subject,
							'message' => $message,
							'created' => date( "Y.m.d H:i:s" )
						);
						if ($ticket['customeremail']) {
							$this->db->insert( 'email_queue', $param );
						}
					}
					$data['success'] = true;
					$data['message'] = lang('ticket').' '.lang('updatemessage');
					echo json_encode($data);
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function attachments($file) {
		if (is_file('./uploads/attachments/' . $file)) {
    		$this->load->helper('file');
    		$this->load->helper('download');
    		$data = file_get_contents('./uploads/attachments/' . $file);
    		force_download($file, $data);
    	} else {
    		$this->session->set_flashdata( 'ntf4', lang('filenotexist'));
    		redirect('tickets/index');
    	}
	}

	function markas() {
		if ( $this->Privileges_Model->check_privilege( 'tickets', 'edit' ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$name = $_POST[ 'name' ];
				$params = array(
					'ticket_id' => $_POST[ 'ticket_id' ],
					'status_id' => $_POST[ 'status_id' ],
				);
				$data['success'] = true;
				$data['message'] = lang('ticket').' '.lang('markas').' '.$name;
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function remove( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'tickets', 'all' ) ) {
			$ticket = $this->Tickets_Model->get_ticket_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'tickets', 'own') ) {
			$ticket = $this->Tickets_Model->get_ticket_by_privileges( $id, $this->session->usr_id );
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
		if($ticket) {
			if ( $this->Privileges_Model->check_privilege( 'tickets', 'delete' ) ) {
				if ( isset( $ticket[ 'id' ] ) ) {
					$this->Tickets_Model->delete_tickets( $id, get_number('tickets',$id,'ticket','ticket') );
					$data['success'] = true;
					$data['message'] = lang('ticket').' '.lang('deletemessage');
					
				} else {
					show_error( 'Eror' );
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('you_dont_have_permission');
			}
			echo json_encode($data);
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('tickets'));
		}
	}

	function get_ticket( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'tickets', 'all' ) ) {
			$ticket = $this->Tickets_Model->get_ticket_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'tickets', 'own') ) {
			$ticket = $this->Tickets_Model->get_ticket_by_privileges( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('tickets'));
		}
		if($ticket) {
			switch ( $ticket[ 'priority' ] ) {
				case '1':
					$priority = lang( 'low' );
					break;
				case '2':
					$priority = lang( 'medium' );
					break;
				case '3':
					$priority = lang( 'high' );
					break;
			};
			switch ( $ticket[ 'status_id' ] ) {
				case '1':
					$status = lang( 'open' );
					break;
				case '2':
					$status = lang( 'inprogress' );
					break;
				case '3':
					$status = lang( 'answered' );
					break;
				case '4':
					$status = lang( 'closed' );
					break;
			};
			if ( $ticket[ 'type' ] == 0 ) {
				$customer = $ticket[ 'company' ];
			} else $customer = $ticket[ 'namesurname' ];
			$replies = $this->db->get_where( 'ticketreplies', array( 'ticket_id' => $id ) )->result_array();
			$data_ticketdetails = array(
				'id' => $ticket[ 'id' ],
				'subject' => $ticket[ 'subject' ],
				'message' => $ticket[ 'message' ],
				'relation' => $ticket[ 'relation' ],
				'relation_id' => $ticket[ 'relation_id' ],
				'staff_id' => $ticket[ 'staff_id' ],
				'contact_id' => $ticket[ 'contact_id' ],
				'contactname' => '' . $ticket[ 'contactname' ] . ' ' . $ticket[ 'contactsurname' ] . '',
				'priority' => $priority,
				'priority_id' => $ticket[ 'priority' ],
				'lastreply' => $ticket[ 'lastreply' ]?(date(get_dateTimeFormat(),strtotime($ticket[ 'lastreply' ]))):lang('n_a'),
				'status' => $status,
				'status_id' => $ticket[ 'status_id' ],
				'customer_id' => $ticket[ 'customer_id' ],
				'department' => $ticket[ 'department' ],
				'opened_date' => date(get_dateTimeFormat(),strtotime($ticket[ 'date' ])),
				'last_reply_date' => $ticket[ 'lastreply' ]?(date(get_dateTimeFormat(),strtotime($ticket[ 'lastreply' ]))):lang('n_a'),
				'attachment' => $ticket[ 'attachment' ],
				'customer' => $customer,
				'assigned_staff_name' => $ticket[ 'staffmembername' ],
				'replies' => $replies,
				'ticket_number' => get_number('tickets', $ticket[ 'id' ], 'ticket','ticket'),
			);
			echo json_encode( $data_ticketdetails );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('tickets'));
		}
	}
}