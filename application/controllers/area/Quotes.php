<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Quotes extends AREA_Controller {


	function index() {
		$data[ 'title' ] = lang( 'quotes' );
		$data[ 'proposals' ] = $this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffavatar,customers.company as customer,customers.email as toemail,customers.namesurname as individual,customers.address as toaddress, proposals.id as id ' )->join( 'customers', 'proposals.relation = customers.id', 'left' )->join( 'staff', 'proposals.assigned = staff.id', 'left' )->get_where( 'proposals', array( 'relation' => $_SESSION[ 'customer' ], 'relation_type' => 'customer' ) )->result_array();
		//Detaylar
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'area/quotes/index', $data );

	}

	function create() {
		$data[ 'title' ] = lang( 'request' ).' '.lang( 'quote' );
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$details = $this->input->post('details');
			$subject = $this->input->post('subject');
			$hasError = false;
			$data['message'] = '';
			if ($subject == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('subject');
			} else if ($details == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('details');
			}

			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				$relation_type = 'customer';
				$cutomer_staff = $this->Settings_Model->get_cutomer_staff($_SESSION[ 'customer' ]);
				$params = array(
					'token' => md5( uniqid() ),
					'subject' => $this->input->post( 'subject' ),
					'customer_quote' => $this->input->post( 'details' ),
					'date' => date( 'Y-m-d H:i:s' ),
					'created' => date( 'Y-m-d H:i:s' ),
					'opentill' => date( 'Y-m-d H:i:s' ),
					'relation_type' => 'customer',
					'relation' => $_SESSION[ 'customer' ],
					'assigned' => $cutomer_staff['staff_id'],
					'addedfrom' => $cutomer_staff['staff_id'],
					'datesend' => date( 'Y-m-d H:i:s' ),
					'comment' => 0,
					'status_id' => 0,
					'is_requested' => 1,
					//'invoice_id' => $this->input->post( 'invoice' ),
					//'dateconverted' => $this->input->post( 'dateconverted' ),
					'sub_total' => 0,
					'total_discount' => 0,
					'total_tax' => 0,
					'total' => 0,
				);
				$proposals_id = $this->Proposals_Model->proposal_add_by_customer( $params );
				
				$template = $this->Emails_Model->get_template('quote', 'request_quote');
				if ($template['status'] == 1) {
					$proposal = $this->Proposals_Model->get_proposals( $proposals_id, 'customer' );
					$customer = $proposal['customercompany'] ? $proposal['customercompany'] : $proposal['namesurname'];
					$settings = $this->Settings_Model->get_settings_ciuis();
					$link = base_url('share/quote/' . $proposal[ 'token' ] . '');
					$message_vars = array(
						'{customer_name}' => $customer,
						'{quote_link}' => $link,
						'{subject}' => $this->input->post( 'subject' ),
						'{details}' => $this->input->post( 'details' ),
						'{company_name}' => $settings['company'],
						'{company_email}' => $settings['email'],
						'{staff}' => $cutomer_staff['staffname']
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);
					$param = array(
						'from_name' => $template['from_name'],
						'email' => $cutomer_staff['staff_email'],
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" )
					);
					if ($cutomer_staff['staff_email']) {
						$this->db->insert( 'email_queue', $param );
					}
				}
				$data['success'] = true;
				$data['message'] = lang('proposal'). ' '. lang('createmessage');
				$data['id'] = $proposals_id;
				echo json_encode($data);
			}
		} else {
			$this->load->view( 'area/inc/header', $data );
			$this->load->view( 'area/quotes/create', $data );
			$this->load->view( 'area/inc/footer', $data );
		}
	}

	function request( $token ) {
		$proposal = $this->Proposals_Model->get_proposal_by_token( $token );
		$id = $proposal[ 'id' ];
		$data[ 'title' ] = 'PRO-' . $id . ' Detail';
		$this->load->model( 'Proposals_Model' );
		$this->load->model( 'Settings_Model' );
		$pro = $this->Proposals_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		$data[ 'proposals' ] = $this->Proposals_Model->get_proposals( $id, $rel_type );
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'proposal', 'relation' => $id ) )->result_array();
		$data[ 'comments' ] = $this->db->get_where( 'comments', array( 'relation' => $id, 'relation_type' => 'proposal' ) )->result_array();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'area/quotes/request', $data );
	}

	function customercomment() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'content' => $this->input->post( 'content' ),
				'relation' => $this->input->post( 'relation' ),
				'relation_type' => 'proposal',
				'staff_id' => $this->session->userdata( 'usr_id' ),
				'created' => date( 'Y-m-d H:i:s' ),
			);
			$action = $this->db->insert( 'comments', $params );
			$proposals = $this->Proposals_Model->get_pro_rel_type( $this->input->post( 'relation' ) );
			$this->db->insert( 'notifications', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => $message = sprintf( lang( 'newcommentforproposal' ), get_number('proposals',$proposals['id'],'proposal','proposal') ),
				'staff_id' => $proposals[ 'assigned' ],
				'perres' => 'customer_avatar_comment.png',
				'target' => '' . base_url( 'proposals/proposal/' . $proposals[ 'id' ] . '' ) . ''
			) );
			$this->session->set_flashdata( 'ntf1', '' . lang( 'commentadded' ) . '' );
			redirect( 'area/quotes/request/' . $proposals[ 'token' ] . '' );
		} else {
			redirect( 'area/quotes/index' );
		}
	}
}