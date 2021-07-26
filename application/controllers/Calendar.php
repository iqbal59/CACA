<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Calendar extends CIUIS_Controller {
	function index() {
		$data[ 'title' ] = 'Calendar';
		$data[ 'logs' ] = $this->Logs_Model->get_all_logs();
		$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
		$data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
		$data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
		$data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
		$data[ 'events' ] = $this->Events_Model->get_all_events();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'calendar/index', $data );
	}

	function get_Events() {
		$data = $this->Events_Model->get_events_json();
		echo json_encode( $data );

	}

	function addevent() { 
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$vat = $this->input->post('title');
			$hasError = false;
			$data['message'] = '';
			if ($this->input->post('title') == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('event'). ' ' .lang('title');
			} else if ($this->input->post('eventType') == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('event').' '.lang('type');
			} else if ($this->input->post('eventstart') == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('start'). ' ' .lang('time');
			} else if ($this->input->post('eventend') == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('end'). ' ' .lang('time');
			} else if ($this->input->post('detail') == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('event'). ' ' .lang('detail');
			} else if (($_POST[ 'notification' ] == '1') && ($_POST[ 'notification_duration' ] == '0' || $_POST[ 'notification_duration' ] == '')) {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('period');
			}
			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				$event_type = $this->Events_Model->get_eventtype($this->input->post('eventType'));
				$params = array(
					'title' => $_POST[ 'title' ],
					'public' => $event_type[ 'public' ],
					'detail' => $_POST[ 'detail' ],
					'start' => date_by_timezone($_POST[ 'eventstart' ]),
					'end' => date_by_timezone($_POST[ 'eventend' ]),
					'color' => $event_type[ 'color' ],
					'is_all' => $_POST['email_to_all'],
					'event_type' => $_POST[ 'eventType' ],
					'reminder' => $_POST[ 'notification' ],
					'staff_id' => $_POST[ 'staff_id' ],
					'added_by' => $this->session->userdata( 'usr_id' ),
					'staffname' => $this->session->userdata( 'staffname' ),
					'created' => timestamp(),
				);
				$todos = $this->Events_Model->add_event( $params );
				if ($_POST[ 'notification' ] == '1') {
					$param = array(
						'relation' => $todos,
						'relation_type' => 'event',
						'type' => $_POST['notification_type'],
						'duration_type' => $_POST[ 'notification_time' ],
						'duration_period' => $_POST[ 'notification_duration' ],
						'start' => $_POST[ 'eventstart' ],
						'end' => $_POST[ 'eventend' ],
					);
					$this->db->insert('event_triggers', $param);
				}
				if ($todos) {
					$data['success'] = true;
					$data['message'] = lang('event').' '.lang('createmessage');
					echo json_encode($data);
				} else {
					$data['success'] = false;
					$data['message'] = lang('errormessage');
					echo json_encode($data);
				}
			}
		}
	}

	function new_appointment() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'title' => $_POST[ 'title' ],
				'public' => $_POST[ 'public' ],
				'detail' => $_POST[ 'detail' ],
				'start' => $_POST[ 'eventstart' ],
				'end' => $_POST[ 'eventend' ],
				'staff_id' => $this->session->userdata( 'usr_id' ),
				'staffname' => $this->session->userdata( 'staffname' ),
			);
			$todos = $this->Events_Model->new_appointment( $params );
		}
	}

	function confirm_appointment( $id ) {
		if ( isset( $id ) ) {
			$response = $this->db->where( 'id', $id )->update( 'appointments', array( 'status' => 1 ) );
		}
	}

	function decline_appointment( $id ) {
		if ( isset( $id ) ) {
			$response = $this->db->where( 'id', $id )->update( 'appointments', array( 'status' => 2 ) );
		}
	}

	function mark_as_done_appointment( $id ) {
		if ( isset( $id ) ) {
			$response = $this->db->where( 'id', $id )->update( 'appointments', array( 'status' => 3 ) );
		}
	}

	function remove_appointment( $id ) {
		if ( isset( $id ) ) {
			$response = $this->db->delete( 'appointments', array( 'id' => $id ) );
		}
	}

	function remove( $id ) {
		$events = $this->Events_Model->remove( $id );
		if ( isset( $events[ 'id' ] ) ) {
			$this->Events_Model->remove( $id );

		}
		$this->session->set_flashdata( 'ntf1', lang( 'eventdeleted' ) );
		redirect( 'calendar/index' );
	}

	function save_colors() {
		if(isset($_POST) && count($_POST) > 0) {
			$staff = $this->db->get_where('staff',array('id' => $this->session->usr_id))->row_array();
			if($staff['admin']) {
					$params = array(
						'appointment_color' => $this->input->post('appointment_color'),
						'project_color' => $this->input->post('project_color'),
						'task_color' => $this->input->post('task_color'),
					);
					$this->Settings_Model->update_colors($params);
					$data['success'] = true;
					$data['message'] = lang('color').' '.lang('updatemessage');
					echo json_encode( $data );
				} else {
					$data['success'] = false;
					$data['message'] = lang('errormessage');
					echo json_encode( $data );
				}
		} else {
			$data['success'] = false;
			$data['message'] = lang('errormessage');
			echo json_encode( $data );
		}
	}
}