<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Customers extends CIUIS_Controller {

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
		$data[ 'title' ] = lang( 'customers' );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data['payment'] = $this->Settings_Model->get_payment_gateway_data();
		$this->load->view( 'customers/index', $data );
	}

	function create() {
		if ( $this->Privileges_Model->check_privilege( 'customers', 'create' ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$company = $this->input->post( 'company' );
				$namesurname = $this->input->post( 'namesurname' );
				$email = $this->input->post( 'email' );
				$default_payment_method = $this->input->post( 'default_payment_method' );
				$group = $this->input->post('groupid');
				if ( $this->input->post('type') == 'true' ) {
					$type = 1;
					$company = '';
				} else {
					$type = 0;
					$namesurname = '';
				}
				$hasError = false;
				$data['message'] = '';
				if ($company == '' && $type == 0) {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('company');
				} else if ($namesurname == '' && $type == 1) {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('customer'). ' ' .lang('name');
				} else if ($email == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('customer'). ' ' .lang('email');
				} else if ($this->Contacts_Model->isDuplicate( $this->input->post( 'email' ) )) {
					$hasError = true;
					$data['message'] = lang('contact').' '.lang('email_exist');
				} else if ($group == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('customer'). ' ' .lang('group');
				}

				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					$appconfig = get_appconfig();
					$params = array(
						'created' => date( 'Y-m-d H:i:s' ),
						'type' => $type,
						'company' => $company,
						'namesurname' => $namesurname,
						'ssn' => $this->input->post( 'ssn' ),
						'executive' => $this->input->post( 'executive' ),
						'address' => $this->input->post( 'address' ),
						'phone' => $this->input->post( 'phone' ),
						'email' => $this->input->post( 'email' ),
						'fax' => $this->input->post( 'fax' ),
						'web' => $this->input->post( 'web' ),
						'taxoffice' => $this->input->post( 'taxoffice' ),
						'taxnumber' => $this->input->post( 'taxnumber' ),
						'country_id' => $this->input->post( 'country_id' ),
						'state_id' => $this->input->post( 'state_id' ),
						'city' => $this->input->post( 'city' ),
						'town' => $this->input->post( 'town' ),
						'zipcode' => $this->input->post( 'zipcode' ),
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
						'staff_id' => $this->session->userdata( 'usr_id' ),
						'default_payment_method' => $this->input->post('default_payment_method'),
						'groupid' => $this->input->post('groupid'),
					);
					$customers_id = $this->Customers_Model->add_customers( $params );

					$template = $this->Emails_Model->get_template('customer', 'new_customer');
					if ($template['status'] == 1) {
						$admins = $this->Staff_Model->get_all_admins(); 
						if($this->input->post( 'namesurname' )) {
							$name = $this->input->post( 'namesurname' );
							$type = lang('individual');
						} else {
							$name = $this->input->post( 'company' );
							$type = lang('company');
						}
						$message_vars = array(
							'{customer_type}' => $type,
							'{name}' => $name,
							'{customer_email}' => $this->input->post( 'email' ),
						);
						$subject = strtr($template['subject'], $message_vars);
						$message = strtr($template['message'], $message_vars);

						$param = array(
							'from_name' => $template['from_name'],
							'email' => $admins['email'],
							'subject' => $subject,
							'message' => $message,
						);
						if ($param['email']) {
							$this->db->insert( 'email_queue', $param );
						}
					}
					if ( $this->input->post( 'custom_fields' ) ) {
						$custom_fields = array(
							'custom_fields' => $this->input->post( 'custom_fields' )
						);
						$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'customer', $customers_id );
					}
					$data['success'] = true;
					$data['id'] = $customers_id;
					$data['message'] = lang('customer').' '.lang('createmessage');
					if($appconfig['customer_series']){
						$customer_number = $appconfig['customer_series'];
						$customer_number = $customer_number + 1 ;
						$this->Settings_Model->increment_series('customer_series',$customer_number);
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

	function groups() {
		if ( $this->Privileges_Model->check_privilege( 'customers', 'all' ) ) {
			$data = $this->Customers_Model->get_groups();
		} else if ($this->Privileges_Model->check_privilege( 'customers', 'own') ) {
			$data = $this->Customers_Model->get_groups($this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('customers'));
		}
		echo json_encode( $data );
	}

	function add_group() {
		if ( $this->Privileges_Model->check_privilege( 'customers', 'create' ) ) {
			if (isset($_POST)) {
				$params = array(
					'name' => $this->input->post('name')
				);
				$this->db->insert( 'customergroups', $params );
				$id = $this->db->insert_id();
				if ($id) {
					$data['success'] = true;
					$data['message'] = lang('customergroup'). ' ' .lang('createmessage');
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function update_group( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'customers', 'edit' ) ) {
			$data[ 'group' ] = $this->Customers_Model->get_group( $id );
			if ( isset( $data[ 'group' ][ 'id' ] ) ) {
				if ( isset( $_POST ) && count( $_POST ) > 0 ) {
					$params = array(
						'name' => $this->input->post( 'name' ),
					);
					$this->Customers_Model->update_group( $id, $params );
					$data['success'] = true;
					$data['message'] = lang('customergroup'). ' ' .lang('updatemessage');
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function remove_group( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'customers', 'delete' ) ) {
			$group = $this->Customers_Model->get_group( $id );
			if ( isset( $group[ 'id' ] ) ) { 
				if ($this->Customers_Model->check_group($id) == 0) {
					$this->Customers_Model->remove_group( $id );
					$data['success'] = true;
					$data['message'] = lang('customergroup'). ' ' .lang('deletemessage');
					echo json_encode($data);
				} else {
					$data['success'] = false;
					$data['message'] = $data['message'] = lang('group').' '.lang('is_linked').' '.lang('with').' '.lang('customer').', '.lang('so').' '.lang('cannot_delete').' '.lang('group');
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function get_customer_groups() {
		$groups = $this->Customers_Model->get_customer_groups();
		$data_categories = array();
		foreach ( $groups as $group ) {
			$data_categories[] = array(
				'name' => $group[ 'name' ],
				'id' => $group[ 'id' ],
			);
		};
		echo json_encode( $data_categories );
	}

	function customer( $id ) {
		$data['payment'] = $this->Settings_Model->get_payment_gateway_data();
		$data[ 'title' ] = lang( 'customer' ).' '.get_number('customers', $id, 'customer', 'customer');
		if ( $this->Privileges_Model->check_privilege( 'customers', 'all' ) ) {
			$customer = $this->Customers_Model->get_customers_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'customers', 'own') ) {
			$customer = $this->Customers_Model->get_customers_by_privileges( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('customers'));
		}
		if($customer) {
			$data[ 'ycr' ] = $this->Report_Model->ycr();
			if ( isset( $customer[ 'id' ] ) ) {
				if ( isset( $_POST ) && count( $_POST ) > 0 ) {
					if ( $this->Privileges_Model->check_privilege( 'customers', 'edit' ) ) {
						$company = $this->input->post( 'company' );
						$namesurname = $this->input->post( 'namesurname' );
						$email = $this->input->post( 'email' );
						$default_payment_method = $this->input->post( 'default_payment_method' );
						if ( $this->input->post('type') == 'true' ) {
							$type = 1;
							$company = '';
						} else {
							$type = 0;
							$namesurname = '';
						}
						$hasError = false;
						$data['message'] = '';
						if ($company == '' && $type == 0) {
							$hasError = true;
							$data['message'] = lang('invalidmessage'). ' ' .lang('company');
						} else if ($namesurname == '' && $type == 1) {
							$hasError = true;
							$data['message'] = lang('invalidmessage'). ' ' .lang('customer'). ' ' .lang('name');
						} else if ($email == '') {
							$hasError = true;
							$data['message'] = lang('invalidmessage'). ' ' .lang('customer'). ' ' .lang('email');
						}	
						if ($hasError) {
							$data['success'] = false;
							echo json_encode($data);
						}
						if (!$hasError) {
							$params = array(
								'company' => $company,
								'type' => $type,
								'namesurname' => $namesurname,
								'groupid' => $this->input->post( 'group_id' ),
								'ssn' => $this->input->post( 'ssn' ),
								'executive' => $this->input->post( 'executive' ),
								'address' => $this->input->post( 'address' ),
								'phone' => $this->input->post( 'phone' ),
								'email' => $this->input->post( 'email' ),
								'fax' => $this->input->post( 'fax' ),
								'web' => $this->input->post( 'web' ),
								'taxoffice' => $this->input->post( 'taxoffice' ),
								'taxnumber' => $this->input->post( 'taxnumber' ),
								'country_id' => $this->input->post( 'country_id' ),
								'state_id' => $this->input->post( 'state_id' ),
								'city' => $this->input->post( 'city' ),
								'town' => $this->input->post( 'town' ),
								'zipcode' => $this->input->post( 'zipcode' ),
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
								'staff_id' => $this->session->userdata( 'usr_id' ),
								'risk' => $this->input->post( 'risk' ),
								'customer_status_id' => $this->input->post( 'status_id' ),
								'default_payment_method' => $this->input->post('default_payment_method'),
							);
							$this->Customers_Model->update_customers( $id, $params );
							if ( $this->input->post( 'custom_fields' ) ) {
								$custom_fields = array(
									'custom_fields' => $this->input->post( 'custom_fields' )
								);
								$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'customer', $id );
							}
							$data['success'] = true;
							$data['message'] = lang('customer').' '.lang('updatemessage');
							echo json_encode($data);
						}
					} else {
						$data['success'] = false;
						$data['message'] = lang( 'you_dont_have_permission' );
						echo json_encode($data);
					}
				} else {
					$data[ 'customers' ] = $this->Customers_Model->get_customers( $id );
					$this->load->view( 'customers/customer', $data );
				}
			} else {
				show_error( 'Eror' );
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('customers'));
		}
	}

	function get_group_id($name){
		$rows = $this->db->get_where('customergroups',array(''))->result_array();
		foreach($rows as $row){
			if($row['name'] = $name){
				$group_id = $row['id'];
			}
		}
		return $group_id?$group_id:'';
	}

	function customersimport () {
		if ( $this->Privileges_Model->check_privilege( 'customers', 'create' ) ) {
			$this->load->library( 'import' );
			$data[ 'customers' ] = $this->Customers_Model->get_customers_for_import();
			$data[ 'error' ] = '';
			$config[ 'upload_path' ] = './uploads/imports/';
			$config[ 'allowed_types' ] = 'csv';
			$config[ 'max_size' ] = '1000';
			$this->load->library( 'upload', $config ); 
			if ( !$this->upload->do_upload('file') ) {
				$data[ 'error' ] = $this->upload->display_errors();
				$this->session->set_flashdata( 'ntf1', lang('csvimporterror') );
				//redirect( 'customers/index' );
			} else {
				$file_data = $this->upload->data();
				$file_path = './uploads/imports/' . $file_data[ 'file_name' ];
				if ( $this->import->get_array( $file_path ) ) {
					$appconfig = get_appconfig();
					$csv_array = $this->import->get_array( $file_path );
					$num = 1;
					$csv_errors = array();
					foreach ( $csv_array as $row ) {
						$group_name = $row['group_name'];
						$group_id = $this->get_group_id($group_name);
						if(($row['email']=='')||($row['type']=='')){
							$num++;
							$csv_errors[] = array(
								'line' => $num,
							);
							continue;
						} else {
						$insert_data = array(
							'created' => date( 'Y-m-d H:i:s' ),
							'type' => $row[ 'type' ],
							'namesurname' => $row[ 'namesurname' ],
							'company' => $row[ 'company' ],
							'taxoffice' => $row[ 'taxoffice' ],
							'taxnumber' => $row[ 'taxnumber' ],
							'executive' => $row[ 'executive' ],
							'ssn' => $row[ 'ssn' ],
							'town' => $row[ 'town' ],
							'zipcode' => $row[ 'zipcode' ],
							'city' => $row[ 'city' ],
							'state' => $row[ 'state' ],
							'country_id' => $row[ 'country_id' ],
							'address' => $row[ 'address' ],
							'fax' => $row[ 'fax' ],
							'email' => $row[ 'email' ],
							'web' => $row[ 'web' ],
							'status_id' => $row[ 'status_id' ],
							'phone' => $row[ 'phone' ],
							'risk' => $row[ 'risk' ],
							'staff_id' => $this->session->userdata('usr_id'),
							'groupid' => $group_id,
						);
						$num++;
						$this->Customers_Model->insert_customers_csv( $insert_data );
						if($appconfig['customer_series']){
							$customer_number = $appconfig['customer_series'];
							$customer_number = $customer_number + 1 ;
							$this->Settings_Model->increment_series('customer_series',$customer_number);
							}
						}
					}
					$datas['success'] = true;
					$datas['message'] = lang('file') . ' ' . lang('csvimportsuccess') . ' ' . lang('butErrors');
					$datas['errors'] = $csv_errors;
					echo json_encode($datas);
				} else{
					$datas['success'] = false;
					$datas['message'] = lang('errormessage');
					echo json_encode($datas);
				}
			}
		} else {
			$datas['success'] = false;
			$datas['message'] = lang('you_dont_have_permission');
			echo json_encode($datas);
		}
	}

	function exportdata() {
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		if ( $this->Privileges_Model->check_privilege( 'customers', 'all' ) ) {
			$this->db->select('type, created, company, namesurname, taxoffice, taxnumber, ssn, executive, address, zipcode, country_id, state, city, town, phone, fax, email, web, customer_status_id, risk,customergroups.name as group,customergroups.id as group_id');
			$this->db->join('customergroups','customers.groupid = customergroups.id','left');
			$this->db->order_by( 'customers.id', 'desc' );
			$q = $this->db->get_where( 'customers', array( ''  ) );
		} else if ($this->Privileges_Model->check_privilege( 'customers', 'own') ) {
			$this->db->select('type, created, company, namesurname, taxoffice, taxnumber, ssn, executive, address, zipcode, country_id, state, city, town, phone, fax, email, web, customer_status_id, risk,customergroups.name as group,customergroups.id as group_id');
			$this->db->join('customergroups','customers.groupid = customergroups.id','left');
			$this->db->order_by( 'customers.id', 'desc' );
			$q = $this->db->get_where( 'customers', array( 'staff_id' => $this->session->usr_id ) );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('customers'));
		}
		
		$delimiter = ",";
		$nuline    = "\r\n";
		force_download('Customers.csv', $this->dbutil->csv_from_result($q, $delimiter, $nuline));
	}

	function addreminder() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'description' => $this->input->post( 'description' ),
				'relation' => $this->input->post( 'relation' ),
				'relation_type' => 'customer',
				'staff_id' => $this->input->post( 'staff' ),
				'addedfrom' => $this->session->userdata( 'usr_id' ),
				'date' => $this->input->post( 'date' ),
			);
			$notes = $this->Trivia_Model->add_reminder( $params );
			$this->session->set_flashdata( 'ntf1', '' . lang( 'reminderadded' ) . '' );
			redirect( 'customers/customer/' . $this->input->post( 'relation' ) . '' );
		} else {
			redirect( 'leads/index' );
		}
	}

	function remove( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'customers', 'all' ) ) {
			$customer = $this->Customers_Model->get_customers_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'customers', 'own') ) {
			$customer = $this->Customers_Model->get_customers_by_privileges( $id, $this->session->usr_id );
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
		if($customer) {
			if ( $this->Privileges_Model->check_privilege( 'customers', 'delete' ) ) {
				if ( isset( $customer[ 'id' ] ) ) {
					$result = $this->Customers_Model->delete_customers( $id, get_number('customers',$id,'customer','customer'));
					$customer = lang('customer');
					if ( $result ) {
						$data['message'] = sprintf( lang( 'success_delete' ), $customer . '' );
						$data['success'] = true;
						echo json_encode($data);
					} else {
						$data['message'] = sprintf( lang( 'cant_delete' ), $customer . '' );
						$data['success'] = false;
						echo json_encode($data);
					}
				} else {
					show_error( 'Customer not deleted' );
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('you_dont_have_permission');
				echo json_encode($data);
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('customers'));
		}
	}

	function customers_json() {
		$customers = $this->Customers_Model->get_all_customers();
		header( 'Content-Type: application/json' );
		echo json_encode( $customers );
	}

	function customers_arama_json() {
		$veriler = $this->Customers_Model->search_json_customer();
		echo json_encode( $veriler );

	}

	function create_contact() {
		if ( $this->Privileges_Model->check_privilege( 'customers', 'edit' ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$hasError = false;
				$data['message'] = '';
				if ($this->input->post( 'name' ) == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage').' '.lang('name');
				} else if ($this->input->post( 'email' ) == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage').' '.lang('email');
				}
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					if ( $this->Contacts_Model->isDuplicate( $this->input->post( 'email' ) ) ) {
						$data['success'] = false;
						$data['message'] = lang('contact').' '.lang('email_exist');
						echo json_encode($data);
					} else {
						if (($this->input->post( 'password' ) == '') && ($this->input->post( 'isPrimary' ) == 'true')) {
							$hasError = true;
							$data['message'] = lang('invalidmessage').' '.lang('password');
						}
						if ($hasError) {
							$data['success'] = false;
							echo json_encode($data);
						} else {
							switch ($this->input->post('isPrimary')) {
								case 'true':
									$primary = 1;
									$passNew = password_hash( $this->input->post( 'password' ), PASSWORD_BCRYPT );
									break;
								case 'false':
									$primary = 0;
									$passNew = null;
									break;
							}
							switch ( $this->input->post( 'isAdmin' ) ) {
								case true:
									$isAdmin = 1;
									break;
								case false:
									$isAdmin = 0;
									break;
							}
							$params = array(
								'name' => $this->input->post( 'name' ),
								'surname' => $this->input->post( 'surname' ),
								'phone' => $this->input->post( 'phone' ),
								'extension' => $this->input->post( 'extension' ),
								'mobile' => $this->input->post( 'mobile' ),
								'email' => $this->input->post( 'email' ),
								'address' => $this->input->post( 'address' ),
								'skype' => $this->input->post( 'skype' ),
								'linkedin' => $this->input->post( 'linkedin' ),
								'customer_id' => $this->input->post( 'customer' ),
								'position' => $this->input->post( 'position' ),
								'primary' => $primary,
								'admin' => $isAdmin,
								'password' => $passNew,
							);
							$contacts_id = $this->Contacts_Model->create( $params );
							if ( $contacts_id ) {
								$permissions = $this->input->post('permissions');
								foreach ($permissions as $perm) {
									if($perm['value'] == 'true'){
										$params = array(
											'relation' => ( int )$contacts_id,
											'relation_type' => 'contact',
											'permission_id' => ( int )$perm['id'],
										);
										$this->db->insert( 'privileges', $params );
									}
								}
								$template = $this->Emails_Model->get_template('customer', 'new_contact_added');
								if ($template['status'] == 1 && $primary == 1) {
									$message_vars = array(
										'{login_email}' => $this->input->post( 'email' ),
										'{login_password}' => ($this->input->post( 'password' ))?($this->input->post( 'password' )):' ',
										'{app_url}' => '' . base_url( 'area/login' ) . '',
										'{email_signature}' => $this->session->userdata( 'email' ),
										'{name}' => $this->session->userdata( 'staffname' ),
										'{customer}' => $this->input->post( 'name' )
									);
									$subject = strtr($template['subject'], $message_vars);
									$message = strtr($template['message'], $message_vars);
									$param = array(
										'from_name' => $template['from_name'],
										'email' => $this->input->post( 'email' ),
										'subject' => $subject,
										'message' => $message,
										'created' => date( "Y.m.d H:i:s" ),
									);
									if ($this->input->post( 'email' )) {
										$this->db->insert( 'email_queue', $param );
									}
								}
								$data['success'] = true;
								$data['message'] = lang('contact').' '.lang('createmessage');
								echo json_encode($data);
							} else {
								$data['success'] = false;
								$data['message'] = lang('errormessage');
								echo json_encode($data);
							}
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

	function contact() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$hasError = false;
			$data['message'] = '';
			if ($this->input->post( 'name' ) == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage').' '.lang('name');
			} else if ($this->input->post( 'email' ) == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage').' '.lang('email');
			}
			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				if ( $this->Contacts_Model->isDuplicate( $this->input->post( 'email' ) ) ) {
					$data['success'] = false;
					$data['message'] = lang('contact').' '.lang('email_exist');
					echo json_encode($data);
				} else {
					if (($this->input->post( 'password' ) == '') && ($this->input->post( 'isPrimary' ) == 'true')) {
						$hasError = true;
						$data['message'] = lang('invalidmessage').' '.lang('password');
					}
					if ($hasError) {
						$data['success'] = false;
						echo json_encode($data);
					} else {
						$data['success'] = true;
						$data['message'] = lang('contact').' '.lang('createmessage');
						echo json_encode($data);
					}
				}
			}
		}
	}

	function update_contact( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'customers', 'edit' ) ) {
			$contacts = $this->Contacts_Model->get_contacts( $id );
			if ( isset( $contacts[ 'id' ] ) ) {
				if ( isset( $_POST ) && count( $_POST ) > 0 ) {
					$hasError = false;
					$data['message'] = '';
					if ($this->input->post( 'name' ) == '') {
						$hasError = true;
						$data['message'] = lang('invalidmessage').' '.lang('name');
					} else if ($this->input->post( 'email' ) == '') {
						$hasError = true;
						$data['message'] = lang('invalidmessage').' '.lang('email');
					}
					if ($hasError) {
						$data['success'] = false;
						echo json_encode($data);
					}
					if (!$hasError) {
						if ( $this->Contacts_Model->isDuplicate( $this->input->post( 'email' ) ) &&  ($this->input->post( 'email' ) != $contacts['email'])) {
							$data['success'] = false;
							$data['message'] = lang('contact').' '.lang('email_exist');
							echo json_encode($data);
						} else {
							$params = array(
								'name' => $this->input->post( 'name' ),
								'surname' => $this->input->post( 'surname' ),
								'phone' => $this->input->post( 'phone' ),
								'extension' => $this->input->post( 'extension' ),
								'mobile' => $this->input->post( 'mobile' ),
								'email' => $this->input->post( 'email' ),
								'address' => $this->input->post( 'address' ),
								'skype' => $this->input->post( 'skype' ),
								'linkedin' => $this->input->post( 'linkedin' ),
								'position' => $this->input->post( 'position' ),
							);
							$this->Contacts_Model->update( $id, $params );
							$data['success'] = true;
							$data['message'] = lang('contact').' '.lang('updatemessage');
							echo json_encode($data);
						}
					}
				}
			} else { 
				show_error( 'The contacts you are trying to edit does not exist.' );
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function update_contact_privilege( $id, $value, $privilege_id ) {
		if ( $this->Privileges_Model->check_privilege( 'customers', 'edit' ) ) {
			if ( $value != 'false' ) {
				$params = array(
					'relation' => ( int )$id,
					'relation_type' => 'contact',
					'permission_id' => ( int )$privilege_id
				);
				$this->db->insert( 'privileges', $params );
			} else {
				$response = $this->db->delete( 'privileges', array( 'relation' => $id, 'relation_type' => 'contact', 'permission_id' => $privilege_id ) );
			}
			$data['success'] = true;
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function change_password_contact( $id ) { 
		if ( $this->Privileges_Model->check_privilege( 'customers', 'edit' ) ) {
			$contact = $this->Contacts_Model->get_contacts( $id );
			if ( isset( $contact[ 'id' ] ) ) {
				if ( isset( $_POST ) && count( $_POST ) > 0 ) {
					$params = array(
						'password' => password_hash( $this->input->post( 'password' ), PASSWORD_BCRYPT ),
					);
					$customer = $contact[ 'customer_id' ];
					$staffname = $this->session->staffname;
					$contactname = $contact[ 'name' ];
					$contactsurname = $contact[ 'surname' ];
					$loggedinuserid = $this->session->usr_id;
					$this->db->insert( 'logs', array(
						'date' => date( 'Y-m-d H:i:s' ),
						'detail' => ( '' . $message = sprintf( lang( 'changedpassword' ), $staffname, $contactname, $contactsurname ) . '' ),
						'staff_id' => $loggedinuserid,
						'customer_id' => $customer,
					) );
					$this->Contacts_Model->update( $id, $params ); 

					// send email to contact about password change
					$template = $this->Emails_Model->get_template('staff', 'customer_password_reset');
					$message_vars = array(
						'{email}' => $contact['email'],
						'{contact}' => $contact['name'].' '.$contact['surname'],
						'{email_signature}' => $template['from_name'],
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);

					$param = array(
						'from_name' => $template['from_name'],
						'email' => $contact['email'],
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" ),
						'status' => 1
					);
					if ($contact['email']) {
						$this->db->insert( 'email_queue', $param );
					}
					$data['success'] = true;
					$data['message'] = ' ' . $contact[ 'name' ] . ' ' . lang( 'passwordchanged' ) . '';
					echo json_encode($data);
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function remove_contact( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'customers', 'edit' ) ) {
			$contacts = $this->Contacts_Model->get_contacts( $id );
			if ( isset( $contacts[ 'id' ] ) ) {
				$this->Contacts_Model->delete( $id );
				$data['success'] = true;
				$data['message'] = lang('contactdeleted');
				echo json_encode($data);
			} else{
				show_error( 'The contacts you are trying to delete does not exist.' );
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		} 
	}
	
	function get_customer( $id ) {
		$customer = array();
		if ( $this->Privileges_Model->check_privilege( 'customers', 'all' ) ) {
			$customer = $this->Customers_Model->get_customers_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'customers', 'own') ) {
			$customer = $this->Customers_Model->get_customers_by_privileges( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('customers'));
		}
		if($customer) {
			$country = get_country($customer['country_id']);
			$state = get_state_name($customer['state'], $customer['state_id']);
			$contacts = $this->Contacts_Model->get_customer_contacts( $id );
			$subsidiaries = $this->Customers_Model->get_subsidiaries( $id );
			$this->db->select_sum( 'total' );
			$this->db->from( 'invoices' );
			$this->db->where( '(status_id = 2 AND customer_id = ' . $customer[ 'id' ] . ') ' );
			$netrevenue = $this->db->get();
			$this->db->select_sum( 'total' );
			$this->db->from( 'invoices' );
			$this->db->where( '(status_id != 1 AND customer_id = ' . $customer[ 'id' ] . ') ' );
			$grossrevenue = $this->db->get();
			$data_customerdetail = array(
				'id' => $customer[ 'id' ],
				'type' => $customer[ 'type' ],
				'isIndividual' => ($customer['type'] == '1') ? true : false,
				'created' => $customer[ 'created' ],
				'staff_id' => $customer[ 'staff_id' ],
				'company' => $customer[ 'company' ],
				'namesurname' => $customer[ 'namesurname' ],
				'taxoffice' => $customer[ 'taxoffice' ],
				'taxnumber' => $customer[ 'taxnumber' ],
				'ssn' => $customer[ 'ssn' ],
				'executive' => $customer[ 'executive' ],
				'address' => $customer[ 'address' ],
				'zipcode' => $customer[ 'zipcode' ],
				'country_id' => $customer[ 'country_id' ],
				'state' => $state,
				'state_id' => $customer['state_id'],
				'city' => $customer[ 'city' ],
				'town' => $customer[ 'town' ],
				'default_payment_method' => $customer[ 'default_payment_method' ],
				'billing_street' => $customer[ 'billing_street' ],
				'billing_city' => $customer[ 'billing_city' ],
				'billing_state' => $customer['billing_state'],
				'billing_state_id' => $customer['billing_state_id'],
				'billing_zip' => $customer[ 'billing_zip' ],
				'billing_country' => $customer[ 'billing_country' ],
				'country' => $country,
				'shipping_street' => $customer[ 'shipping_street' ],
				'shipping_city' => $customer[ 'shipping_city' ],
				'shipping_state' => $customer['shipping_state'],
				'shipping_state_id' => $customer['shipping_state_id'],
				'shipping_zip' => $customer[ 'shipping_zip' ],
				'shipping_country' => $customer[ 'shipping_country' ],
				'phone' => $customer[ 'phone' ],
				'fax' => $customer[ 'fax' ],
				'email' => $customer[ 'email' ],
				'web' => $customer[ 'web' ],
				'risk' => intval( $customer[ 'risk' ] ),
				'netrevenue' => $netrevenue->row()->total,
				'grossrevenue' => $grossrevenue->row()->total,
				'subsidiaries' => $subsidiaries,
				'contacts' => $contacts,
				'chart_data' => $this->Report_Model->customer_annual_sales_chart( $id ),
				'group_name' => $customer['name'],
				'group_id' => $customer['groupid'],
				'customer_number' => get_number('customers', $customer[ 'id' ], 'customer','customer'),
				'customer_status_id' => ($customer['customer_status_id'] == 1 ? true : false),
			);
			echo json_encode( $data_customerdetail );
		}
	}
}
