<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Staff extends CIUIS_Controller {

	function index() {
		$path = $this->uri->segment( 1 );
		if ( !$this->Privileges_Model->has_privilege( $path ) ) {
			$this->session->set_flashdata( 'ntf3', '' . lang( 'you_dont_have_permission' ) );
			redirect( 'panel/' );
			die;
		} else {
			$data[ 'title' ] = lang( 'staff' );
			$data[ 'staff' ] = $this->Staff_Model->get_all_staff();
			$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
			$data[ 'departments' ] = $this->Settings_Model->get_departments();
			$path = $this->uri->segment( 1 );
			if ( !$this->Privileges_Model->has_privilege( $path ) ) {
				$this->session->set_flashdata( 'ntf3', '' . lang( 'you_dont_have_permission' ) );
				redirect( 'panel/' );
				die;
			}
			$this->load->view( 'staff/index', $data );
		}
	} 

	function createStaff() {
		if ( $this->Privileges_Model->check_privilege( 'staff', 'create' ) ) {
			$data[ 'title' ] = 'Add Staff';
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$language = $this->input->post( 'language' );
				$staffname = $this->input->post( 'name' );
				$department_id = $this->input->post( 'department' );
				$email = $this->input->post( 'email' );
				$password = $this->input->post( 'password' );
				$timezone = $this->input->post( 'timezone' );
				$role = $this->input->post('role');
				$hasError = false;
				$data['message'] = '';
				if ($staffname == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('name');
				} else if ($email == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('email');
				} else if ($email != '') {
					if ($this->Staff_Model->isDuplicate($email) == TRUE) {
						$hasError = true;
						$data['message'] = lang('staffemailalreadyexists');
					}
				}
				if (!$hasError) {
					if ($password == '') {
						$hasError = true;
						$data['message'] = lang('invalidmessage'). ' ' .lang('password');
					} else if ($department_id == '') {
						$hasError = true;
						$data['message'] = lang('selectinvalidmessage'). ' ' .lang('staffdepartment');
					} else if ($language == '') {
						$hasError = true;
						$data['message'] = lang('selectinvalidmessage'). ' ' .lang('language');
					} else if ($timezone == '') {
						$hasError = true;
						$data['message'] = lang('selectinvalidmessage'). ' ' .lang('staff'). ' ' .lang('timezone');
					} else if ( $role == '' ) {
						$hasError = true;
						$data['message'] = lang('selectinvalidmessage'). ' ' .lang('role');
					}
				}
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					$role_type = $this->Staff_Model->get_role_type($role);
					$is_Admin = '';
					$is_Staff = '';
					$is_Other = '';
					if($role_type == 'admin') {
						$is_Admin = 1;
					} else if ( $role_type == 'staff') {
						$is_Staff = 1;
					} else {
						$is_Other = 1;
						$is_Staff = 1;
					}
					$appconfig = get_appconfig();
					$params = array(
						'language' => $this->input->post( 'language' ),
						'role_id' => $role,
						'staffname' => $this->input->post( 'name' ),
						'staffavatar' => 'n-img.jpg',
						'department_id' => $this->input->post( 'department' ),
						'phone' => $this->input->post( 'phone' ),
						'address' => $this->input->post( 'address' ),
						'email' => $this->input->post( 'email' ),
						'password' => md5( $this->input->post( 'password' ) ),
						'admin' => $is_Admin ? $is_Admin : null,
						'timezone' => $timezone,
						'other' => $is_Other ? $is_Other : null,
						'staffmember' => $is_Staff ? $is_Staff : null,
						'inactive' => NULL,
					);
					$staff_id = $this->Staff_Model->add_staff( $params );
					if ($staff_id) {
						if ( $this->input->post( 'custom_fields' ) ) {
							$custom_fields = array(
								'custom_fields' => $this->input->post( 'custom_fields' )
							);
							$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'staff', $staff_id );
						}
						$template = $this->Emails_Model->get_template('staff', 'new_staff');
						if ($template['status'] == 1) {
							$message_vars = array(
								'{staff_email}' => $this->input->post( 'email' ),
								'{staff}' => $this->input->post( 'name' ),
								'{password}' => $this->input->post( 'password' ),
								'{name}' => $this->session->userdata('staffname'),
								'{email_signature}' => $this->session->userdata('email'),
								'login_url' => '' . base_url( 'login' ) . '' 
							);
							$subject = strtr($template['subject'], $message_vars);
							$message = strtr($template['message'], $message_vars);
							$param = array(
								'from_name' => $template['from_name'],
								'email' => $this->input->post( 'email' ),
								'subject' => $subject,
								'message' => $message,
								'created' => date( "Y.m.d H:i:s" )
							);
							if ($this->input->post( 'email' )) {
								$this->db->insert( 'email_queue', $param );
							}
						}
						$data['success'] = true;
						if($appconfig['staff_series']){
							$staff_number = $appconfig['staff_series'];
							$staff_number = $staff_number + 1 ;
							$this->Settings_Model->increment_series('staff_series',$staff_number);
						}
						$data['message'] = lang('staff'). ' '.lang('addmessage');
						echo json_encode($data);
					}
				}
			} else {
				$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
				$data[ 'languages' ] = $this->Settings_Model->get_languages();
				$data[ 'departments' ] = $this->Settings_Model->get_departments();
				$this->load->view( 'staff/add', $data );
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function update( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'staff', 'all' ) ) {
			if ( $this->Privileges_Model->check_privilege( 'staff', 'edit' ) ) {
				if ( isset( $id ) ) {
					if ( isset( $_POST ) && count( $_POST ) > 0 ) {
						$is_demo = $this->Settings_Model->is_demo();
						if (!$is_demo) {
							$language = $this->input->post( 'language' );
							$staffname = $this->input->post( 'name' );
							$department_id = $this->input->post( 'department' );
							$email = $this->input->post( 'email' );
							$password = $this->input->post( 'password' );
							$primaryEmail = $this->Staff_Model->get_staff_email($id);
							$role = $this->input->post('role');
							$hasError = false;
							$data['message'] = '';
							if ($staffname == '') {
								$hasError = true;
								$data['message'] = lang('invalidmessage'). ' ' .lang('name');
							} else if ($email == '') {
								$hasError = true;
								$data['message'] = lang('invalidmessage'). ' ' .lang('email');
							} else if ($email != '') {
								if ($email != $primaryEmail['email']) {
									if ($this->Staff_Model->isDuplicate($email) == TRUE) {
										$hasError = true;
										$data['message'] = lang('staffemailalreadyexists');
									}
								}
							}
							if (!$hasError) {
								if ($department_id == '') {
									$hasError = true;
									$data['message'] = lang('selectinvalidmessage'). ' ' .lang('staffdepartment');
								} else if ($language == '') {
									$hasError = true;
									$data['message'] = lang('selectinvalidmessage'). ' ' .lang('language');
								} else if ($this->input->post( 'timezone' ) == '' || !$this->input->post( 'timezone' )) {
									$hasError = true;
									$data['message'] = lang('selectinvalidmessage'). ' ' .lang('timezone');
								}
							}
							if ($hasError) {
								$data['success'] = false;
								echo json_encode($data);
							}
							if (!$hasError) {
								$role_type = $this->Staff_Model->get_role_type($role);
								$is_Admin = '';
								$is_Staff = '';
								$is_Other = '';
								if($role_type == 'admin') {
									$is_Admin = 1;
								} else if ( $role_type == 'staff') {
									$is_Staff = 1;
								} else {
									$is_Other = 1;
									$is_Staff = 1;
								}
								switch ( $_POST[ 'inactive' ] ) {
									case 'true':
										$is_Active = null;
										break;
									case 'false':
										$is_Active = 0;
										break;
								}
								$params = array(
									'language' => $this->input->post( 'language' ),
									'staffname' => $this->input->post( 'name' ),
									'department_id' => $this->input->post( 'department' ),
									'role_id' => $role,
									'phone' => $this->input->post( 'phone' ),
									'address' => $this->input->post( 'address' ),
									'email' => $this->input->post( 'email' ),
									'timezone' => $this->input->post( 'timezone' ),
									'inactive' => $is_Active,
									'admin' => $is_Admin ? $is_Admin : null,
									'other' => $is_Other ? $is_Other : null,
									'staffmember' => $is_Staff ? $is_Staff : null,
								);
								$this->Staff_Model->update_staff( $id, $params ); 
								if ($this->session->usr_id == $id) {
									$this->session->set_userdata(array('language' => $this->input->post('language')));
									$this->session->set_userdata(array('staff_timezone' => $this->input->post('staff_timezone')));
								}
								if ( $this->input->post( 'custom_fields' ) ) {
									$custom_fields = array(
										'custom_fields' => $this->input->post( 'custom_fields' )
									);
									$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'staff', $id );
								}
								$data['success'] = true;
								$data['message'] = lang( 'staffupdated' );
								echo json_encode($data); 
							}
						} else {
							$datas['success'] = false;
							$datas['message'] = lang('demo_error');
							echo json_encode($datas);
						}
					}
				}
			} else {
				$datas['success'] = false;
				$datas['message'] = lang('you_dont_have_permission');
				echo json_encode($datas);
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('staff'));
		}
	}

	function staffmember( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'staff', 'all' ) ) {
			$data[ 'title' ] = lang( 'staffdetail' );
			$staff = $this->Staff_Model->get_staff( $id );
			if ( isset( $staff[ 'id' ] ) ) {
				$data[ 'id' ] = $staff[ 'id' ];
				$this->load->view( 'inc/header', $data );
				$this->load->view( 'staff/detail', $data );
			} else {
				redirect( 'staff/' );
			}
		} else {
			$this->session->set_flashdata( 'ntf3', lang( 'you_dont_have_permission' ) );
			redirect(base_url('staff'));
		}
	}

	function profile() {
		$id = $this->session->userdata('usr_id');
		$data[ 'title' ] = lang( 'staffdetail' );
		$staff = $this->Staff_Model->get_staff( $id );
		if ( isset( $staff[ 'id' ] ) ) {
			if (!$this->isAdmin()) {
				if ($staff[ 'id' ] != $this->session->usr_id) {
					$this->session->set_flashdata( 'ntf3', '' . $id . lang( 'you_dont_have_permission' ) );
					redirect('panel');
				} else {
					$data[ 'id' ] = $staff[ 'id' ];
					$this->load->view( 'inc/header', $data );
					$this->load->view( 'staff/detail', $data );
					$this->load->view( 'inc/footer', $data );
				}
			} else {
				$data[ 'id' ] = $staff[ 'id' ];
				$this->load->view( 'inc/header', $data );
				$this->load->view( 'staff/detail', $data );
			}
		} else {
			redirect( 'staff/' );
		}
	}

	function isAdmin() {
		$id = $this->session->usr_id;
		$this->db->select( '*');
		$rows = $this->db->get_where( 'staff', array( 'admin' => 1, 'id' => $id ) )->num_rows();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
	}

	function change_avatar( $id ) { 
		if($id = $this->session->usr_id) {
			goto profile;
		} else {
			if ( $this->Privileges_Model->check_privilege( 'staff', 'edit' ) ) {
				profile:
				if ( isset( $id ) ) {
					if ( isset( $_POST ) ) {
						$config[ 'upload_path' ] = './uploads/images/';
						$config[ 'allowed_types' ] = 'gif|jpg|png|jpeg';
						$this->load->library( 'upload', $config );
						$config[ 'allowed_types' ] = 'zip|rar|tar|gif|jpg|png|jpeg|gif|pdf|doc|docx|xls|xlsx|txt|csv|ppt|opt';
						$config['max_size'] = '9000';
						if (isset($_FILES["file"])) {
							$new_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES["file"]['name']));
							$config['file_name'] = $new_name;
						}
						if (!$this->upload->do_upload('file')) {
							$data['success'] = false;
							$data['message'] = $this->upload->display_errors();
							echo json_encode($data);
						} else {
							$image_data = $this->upload->data();
							if (is_file('./uploads/images/'.$image_data[ 'file_name' ])) {
								$params = array(
									'staffavatar' => $image_data[ 'file_name' ],
								);
								$response = $this->Staff_Model->update_staff( $id, $params );
								$data['success'] = true;
								$data['message'] = lang('profile').' '.lang('updatemessage');
								echo json_encode($data);
							} else {
								$data['success'] = true;
								$data['message'] = lang('errormessage');
								echo json_encode($data);
							}
						}
					}
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('you_dont_have_permission');
				echo json_encode($data);
			}
		}
	}

	function remove( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'staff', 'all' ) ) {
			if ( $this->Privileges_Model->check_privilege( 'staff', 'delete' ) ) {
				$staff = $this->Staff_Model->get_staff( $id );
				if ( isset( $staff[ 'id' ] ) ) { 
					$is_demo = $this->Settings_Model->is_demo();
					if (!$is_demo) {
						if ($staff[ 'id' ] == $this->session->usr_id) {
							$data['success'] = false;
							$data['message'] = lang( 'cannotdeleteown' );
							echo json_encode($data);
						} else {
							$result = $this->Staff_Model->delete_staff( $id, get_number('staff',$id,'staff','staff') );
							$staff = lang('staff');
							if ( $result ) {
								$data['message'] = sprintf( lang( 'success_delete' ), $staff . '' );
								$data['success'] = true;
								echo json_encode($data);
							} else {
								$data['message'] = sprintf( lang( 'cant_delete' ), $staff . '' );
								$data['success'] = false;
								echo json_encode($data);
							}	
						}
					} else {
						$data['success'] = false;
						$data['message'] = 'Some Staffs Can not be deleted in Demo Mode';
						echo json_encode($data);
					}
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang( 'you_dont_have_permission' );
				echo json_encode($data);
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang( 'you_dont_have_permission' );
			echo json_encode($data);
		}
	}

	function add_department() {
		if ( $this->Privileges_Model->check_privilege( 'staff', 'create' ) ) {
			if (isAdmin()) {
				if ( isset( $_POST ) && count( $_POST ) > 0 ) {
					$params = array(
						'name' => $this->input->post( 'name' ),
					);
					$department = $this->Settings_Model->add_department( $params );
					$data['message'] = lang('department').' '.lang('addmessage');
					$data['success'] = true;
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('only_admin'). ' '.lang('create'). ' '.lang('department');
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function update_department( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'staff', 'edit' ) ) {
			if (isAdmin()) {
				$departments = $this->Settings_Model->get_department( $id );
				if ( isset( $departments[ 'id' ] ) ) {
					if ( isset( $_POST ) && count( $_POST ) > 0 ) {
						$params = array(
							'name' => $this->input->post( 'name' ),
						);
						$this->session->set_flashdata('ntf1', '<span><b>' . lang('departmentupdated') . '</b></span>');
						$this->Settings_Model->update_department($id, $params);
						$data['message'] = lang('department').' '.lang('updatemessage');
						$data['success'] = true;
						echo json_encode($data);
					}
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('only_admin'). ' '.lang('update'). ' '.lang('department');
				echo json_encode($data);
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function update_workplan( $id ) {
		if($id == $this->session->usr_id) {
			goto next;
		} else {
			if ( $this->Privileges_Model->check_privilege( 'staff', 'edit' ) ) {
				next:
				$workplan = $this->Staff_Model->get_work_plan( $id );
				if ( isset( $workplan[ 'id' ] ) ) {
					if ( isset( $_POST ) && count( $_POST ) > 0 ) {
						$response = $this->db->where( 'id', $workplan[ 'id' ] )->update( 'staff_work_plan', array( 'work_plan' => $this->input->post( 'work_plan' ) ) );
					}
					$data['success'] = true;
					$data['message'] = lang('staff').' '.lang('work_plan').' '.lang('updated');
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('you_dont_have_permission');
			}
		}
		echo json_encode($data);
	}

	function remove_department( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'staff', 'delete' ) ) {
			if (isAdmin()) {
				$departments = $this->Settings_Model->get_department( $id );
				if ( isset( $departments[ 'id' ] ) ) {
					if ($this->Settings_Model->check_department($id) === 0) {
						$this->Settings_Model->delete_department($id);
						$data['message'] = lang('department').' '.lang('deletemessage');
						$data['success'] = true;
						echo json_encode($data);
					} else {
						$data['message'] = lang('department').' '.lang('is_linked').' '.lang('with').' '.lang('staff').', '.lang('so').' '.lang('cannot_delete').' '.lang('department');
						$data['success'] = false;
						echo json_encode($data);
					}
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('only_admin'). ' '.lang('delete'). ' '.lang('department');
				echo json_encode($data);
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function appointment_availability( $id, $value ) {
		if ( $value === 'true' ) {
			$availability = 1;
		} else {
			$availability = 0;
		}
		if ( isset( $id ) ) {
			$response = $this->db->where( 'id', $id )->update( 'staff', array( 'appointment_availability' => $availability ) );
		};
	}

	function update_google_calendar( $id ) {
		$staff = $this->Staff_Model->get_staff( $id );
		if ( isset( $staff[ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'google_calendar_id' => $this->input->post( 'google_calendar_id' ),
					'google_calendar_api_key' => $this->input->post( 'google_calendar_api_key' ),
					'google_calendar_enable' => $this->input->post( 'google_calendar_enable' ),
				);
				$this->Staff_Model->update_staff( $id, $params );
				$notification = array(
					'color' => 'color success',
					'message' => lang( 'google_calendar_settings_updated' )
				);
				echo json_encode( $notification );
			} else {
				$notification = array(
					'color' => 'color danger',
					'message' => lang( 'google_calendar_settings_not_updated' )
				);
				echo json_encode( $notification );
			}
		} else
			show_error( 'The staff you are trying to update google calendar settings does not exist.' );
	}

	function changestaffpassword() {
		$id = $this->session->userdata('usr_id');
		$staff = $this->Staff_Model->get_staff( $id );
		if ( isset( $staff[ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$password = $this->input->post('password');
				$new_password = $this->input->post('new_password');
				$c_new_password = $this->input->post('c_new_password');
				$hasError = false;
				if ($password == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('old'). ' ' .lang('password');
				} else if ($new_password == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('new'). ' ' .lang('password');
				} else if (strlen($new_password) < 6) {
					$hasError = true;
					$data['message'] = lang('password_length_error');
				} else if ($c_new_password != $new_password) {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('confirm'). ' ' .lang('new'). ' ' .lang('password');
				}
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					if ($this->Staff_Model->validate_user_password($id, $password)) {
						$params = array(
							'password' => md5($new_password),
						);
						$this->Staff_Model->update_staff( $id, $params );

						$template = $this->Emails_Model->get_template('staff', 'password_reset');
						$message_vars = array(
							'{staff_email}' => $staff['email'],
							'{staffname}' => $staff['staffname'],
							'{email_signature}' => $template['from_name'],
						);
						$subject = strtr($template['subject'], $message_vars);
						$message = strtr($template['message'], $message_vars);

						$param = array(
							'from_name' => $template['from_name'],
							'email' => $staff['email'],
							'subject' => $subject,
							'message' => $message,
							'created' => date( "Y.m.d H:i:s" ),
							'status' => 0
						);
						if ($staff['email']) {
							$this->db->insert( 'email_queue', $param );
							$this->load->library('mail');
							$this->mail->send_email($staff['email'], $template['from_name'], $subject, $message);
						}
						$data['success'] = true;
						$data['message'] = lang('password'). ' ' .lang('updatemessage');
						echo json_encode($data);
					} else {
						$data['success'] = false;
						$data['message'] = lang('incorrect'). ' ' .lang('old'). ' ' .lang('password');
						echo json_encode($data);
					}
				}
			} else {
				$data[ 'staff' ] = $this->Staff_Model->get_staff( $id );
			}
		} else
			show_error( 'The staff you are trying to update password does not exist.' );
	}

	function changestaffpassword_admin($id) {
		if ( $this->Privileges_Model->check_privilege( 'staff', 'edit' ) ) {
			$staff = $this->Staff_Model->get_staff( $id );
			if ( isset( $staff[ 'id' ] ) ) {
				if ( isset( $_POST ) && count( $_POST ) > 0 ) {
					$new_password = $this->input->post('new_password');
					$c_new_password = $this->input->post('c_new_password');
					$hasError = false;
					if ($new_password == '') {
						$hasError = true;
						$data['message'] = lang('invalidmessage'). ' ' .lang('new'). ' ' .lang('password');
					} else if (strlen($new_password) < 6) {
						$hasError = true;
						$data['message'] = lang('password_length_error');
					} else if ($c_new_password != $new_password) {
						$hasError = true;
						$data['message'] = lang('invalidmessage'). ' ' .lang('confirm'). ' ' .lang('new'). ' ' .lang('password');
					}
					if ($hasError) {
						$data['success'] = false;
						echo json_encode($data);
					}
					if (!$hasError) {
							$params = array(
								'password' => md5($new_password),
							);
							$this->Staff_Model->update_staff( $id, $params );

							$template = $this->Emails_Model->get_template('staff', 'password_reset');
							$message_vars = array(
								'{staff_email}' => $staff['email'],
								'{staffname}' => $staff['staffname'],
								'{email_signature}' => $template['from_name'],
							);
							$subject = strtr($template['subject'], $message_vars);
							$message = strtr($template['message'], $message_vars);

							$param = array(
								'from_name' => $template['from_name'],
								'email' => $staff['email'],
								'subject' => $subject,
								'message' => $message,
								'created' => date( "Y.m.d H:i:s" ),
								'status' => 0
							);
							if ($staff['email']) {
								$this->db->insert( 'email_queue', $param );
								$this->load->library('mail');
								$this->mail->send_email($staff['email'], $template['from_name'], $subject, $message);
							}
							$data['success'] = true;
							$data['message'] = lang('password'). ' ' .lang('updatemessage');
							echo json_encode($data);
					}
				} else {
					$data[ 'staff' ] = $this->Staff_Model->get_staff( $id );
				}
			} else {
				show_error( 'The staff you are trying to update password does not exist.' );
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function restore_workplan($staff_id) {
		if($staff_id == $this->session->usr_id) {
			$result = $this->Staff_Model->restore_workplan($staff_id);
			if($result) {
				$this->session->set_flashdata( 'ntf1', lang('staff').' '.lang('work_plan').' '.lang('updated') );
			} 
			redirect(base_url('staff/profile/'. $staff_id));
		} else {
			if ( $this->Privileges_Model->check_privilege( 'staff', 'all' ) ) {
				if ( $this->Privileges_Model->check_privilege( 'staff', 'edit' ) ) {
					$result = $this->Staff_Model->restore_workplan($staff_id);
					if($result) {
						$this->session->set_flashdata( 'ntf1', lang('staff').' '.lang('work_plan').' '.lang('updated') );
					} 
				} else {
					$this->session->set_flashdata( 'ntf3', '' . lang( 'you_dont_have_permission' ) );
				}
			} else {
				$this->session->set_flashdata( 'ntf3', '' . lang( 'you_dont_have_permission' ) );
			}
			redirect(base_url('staff/staffmember/'. $staff_id));
		}
	}

	function staff_detail( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'staff', 'all' ) ) {
			$staff = $this->Staff_Model->get_staff( $id );
			$permissions = $this->Privileges_Model->get_all_permissions();
			$privileges = $this->Privileges_Model->get_privileges();
			$work_plans = $this->db->select( '*' )->get_where( 'staff_work_plan', array( 'staff_id' => $id, ) )->row_array();
			$daily_work_plan = json_decode( $work_plans[ 'work_plan' ] );
			$arr = array();
			foreach ( $privileges as $privilege ) {
				if ( $privilege[ 'relation' ] == $staff[ 'id' ] && $privilege[ 'relation_type' ] == 'staff' ) {
					array_push( $arr, $privilege[ 'permission_id' ] );
				}
			}
			$data_privileges = array();
			if ($staff['other']) {
				foreach ( $permissions as $permission ) {
					if (($permission['key'] == 'invoices') || ($permission['key'] == 'expenses')) {
						$data_privileges[] = array(
							'id' => $permission[ 'id' ],
							'name' => '' . lang( $permission[ 'permission' ] ) . '',
							'value' => '' . ( array_search( $permission[ 'id' ], $arr ) !== FALSE ) ? true : false . ''
						);
					}
				}
			} else {
				foreach ( $permissions as $permission ) {
					if($permission['key'] != 'quotes'){
						$data_privileges[] = array(
						'id' => $permission[ 'id' ],
						'name' => '' . lang( $permission[ 'permission' ] ) . '',
						'value' => '' . ( array_search( $permission[ 'id' ], $arr ) !== FALSE ) ? true : false . ''
						);
					}
				}
			}
			switch ( $staff[ 'admin' ] ) {
				case 1:
					$isAdmin = true;
					$type = 'admin';
					break;
				case null || 0:
					$isAdmin = false;
					break;
			}
			switch ( $staff[ 'staffmember' ] ) {
				case 1:
					$isStaff = true;
					$type = 'staffmember';
					break;
				case null || 0:
					$isStaff = false;
					break;
			}
			switch ($staff['other']) {
				case 1:
					$isOther = true;
					$isStaff = false;
					$type = 'other';
					break;
				case null || 0:
					$isOther = false;
					break;
			}
			switch ( $staff[ 'inactive' ] ) {
				case null:
					$isInactive = true;
					break;
				case '0':
					$isInactive = false;
					break;
			}
			switch ( $staff[ 'google_calendar_enable' ] ) {
				case '0':
					$GoogleCalendarEnable = false;
					break;
				case '1':
					$GoogleCalendarEnable = true;
					break;
			}
			$properties = array(
				'department' => $staff[ 'department' ],
				'sales_total' => $this->Staff_Model->total_sales_by_staff( $id ),
				'total_customer' => $this->Staff_Model->total_custoemer_by_staff( $id ),
				'total_ticket' => $this->Staff_Model->total_ticket_by_staff( $id ),
				'chart_data' => $this->Report_Model->staff_sales_graph( $id ),
			);
			$user_data = array(
				'id' => $staff[ 'id' ],
				'assigned_role' => $staff[ 'role_id' ],
				'language' => $staff[ 'language' ],
				'name' => $staff[ 'staffname' ],
				'avatar' => $staff[ 'staffavatar' ],
				'department_id' => $staff[ 'department_id' ],
				'phone' => $staff[ 'phone' ],
				'email' => $staff[ 'email' ],
				'timezone' => $staff[ 'timezone' ],
				'address' => $staff[ 'address' ],
				'google_calendar_id' => $staff[ 'google_calendar_id' ],
				'google_calendar_api_key' => $staff[ 'google_calendar_api_key' ],
				'google_calendar_enable' => $GoogleCalendarEnable,
				'admin' => $isAdmin,
				'other' => $isOther,
				'type' => $type,
				'staffmember' => $isStaff,
				'last_login' => $staff[ 'last_login' ],
				'active' => $isInactive,
				'properties' => $properties,
				'privileges' => $data_privileges,
				'work_plan' => $daily_work_plan,
				'staff_number' => get_number('staff', $staff[ 'id' ], 'staff','staff'),
			);
			echo json_encode( $user_data );
		} else {
			$this->session->set_flashdata( 'ntf3', lang( 'you_dont_have_permission' ) );
			redirect(base_url('staff'));
		}
	}

	function get_staff() {
		if ( $this->Privileges_Model->check_privilege( 'staff', 'all' ) ) {
			$staffs = $this->Staff_Model->get_all_staff();
			$data_staffs = array();
			foreach ( $staffs as $staff ) {
				$type = lang('admin');
				$color = '#26c281';
				if ($staff[ 'admin' ] == '1') {
					$type = lang('admin');
					$color = '#26c281';
				}
				if ($staff[ 'staffmember' ] == '1') {
					$type = lang('staff');
					$color = '#686868';
				}
				if ($staff[ 'other' ] == '1') {
					$type = lang('other');
					$color = '#e8ab00';
				}
				$data_staffs[] = array(
					'id' => $staff[ 'id' ],
					'name' => $staff[ 'staffname' ],
					'avatar' => $staff[ 'staffavatar' ],
					'department' => $staff[ 'department' ],
					'phone' => $staff[ 'phone' ],
					'address' => $staff[ 'address' ],
					'email' => $staff[ 'email' ],
					'last_login' => $staff[ 'last_login' ],
					'appointment_availability' => $staff[ 'appointment_availability' ],
					'staff_number' => get_number('staff', $staff[ 'id' ], 'staff','staff'),
					'type' => $type,
					'color' => $color
				);
			};
			echo json_encode( $data_staffs );
		}
	}
}