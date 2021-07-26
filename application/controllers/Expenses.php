<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Expenses extends CIUIS_Controller {

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
		$data[ 'title' ] = lang( 'expenses' );
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'all' ) ) {
			$data[ 'expenses' ] = $this->Expenses_Model->get_all_expenses_by_privileges();
			$data[ 'expensesAmount' ] = $this->Expenses_Model->expensesTotalAmount();
			$data[ 'billed_expenses' ] = $this->Expenses_Model->billed_expenses();
			$data[ 'not_billed_expenses' ] = $this->Expenses_Model->not_billed_expenses();
			$data[ 'internal_expenses' ] = $this->Expenses_Model->internal_expenses();
			$data[ 'expenses_num' ] = $this->Expenses_Model->expenses_num();
			$data[ 'billed_expenses_num' ] = $this->Expenses_Model->billed_expenses_num();
			$data[ 'not_billed_expenses_num' ] = $this->Expenses_Model->not_billed_expenses_num();
			$data[ 'internal_expenses_num' ] = $this->Expenses_Model->internal_expenses_num();
		} else {
			$data[ 'expenses' ] = $this->Expenses_Model->get_all_expenses_by_privileges($this->session->usr_id);
			$data[ 'expensesAmount' ] = $this->Expenses_Model->expenses_total_amount_by_status();
			$data[ 'billed_expenses' ] = $this->Expenses_Model->expenses_total_amount_by_status('billed');
			$data[ 'not_billed_expenses' ] = $this->Expenses_Model->expenses_total_amount_by_status('notbilled');
			$data[ 'internal_expenses' ] = $this->Expenses_Model->expenses_total_amount_by_status('internal');
			$data[ 'expenses_num' ] = $this->Expenses_Model->expenses_num_by_type();
			$data[ 'billed_expenses_num' ] = $this->Expenses_Model->expenses_num_by_type('billed');
			$data[ 'not_billed_expenses_num' ] =$this->Expenses_Model->expenses_num_by_type('notbilled');
			$data[ 'internal_expenses_num' ] = $this->Expenses_Model->expenses_num_by_type('internal');
		}
		$data[ 'billed' ] = ( $data[ 'expenses_num' ] > 0 ? number_format( ( $data[ 'billed_expenses_num' ] * 100 ) / $data[ 'expenses_num' ] ) : 0 );
		$data[ 'not_billed' ] = ( $data[ 'expenses_num' ] > 0 ? number_format( ( $data[ 'not_billed_expenses_num' ] * 100 ) / $data[ 'expenses_num' ] ) : 0 );
		$data[ 'internal' ] = ( $data[ 'expenses_num' ] > 0 ? number_format( ( $data[ 'internal_expenses_num' ] * 100 ) / $data[ 'expenses_num' ] ) : 0 );
		$this->load->view( 'expenses/index', $data );
	}

	function create() { 
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'create' ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$hash = '';
				$hash = ciuis_Hash();
				$category_id = $this->input->post( 'category' );
				$customer_id = $this->input->post( 'customer' );
				$account_id = $this->input->post( 'account' );
				$title = $this->input->post( 'title' );
				$date = $this->input->post( 'date' );
				$amount = $this->input->post( 'amount' );
				$description = $this->input->post( 'description' );
				$internal = $this->input->post( 'internal' );
				$internal = ($internal == 'true')? '1' : '0';
				$total = filter_var($this->input->post('total'), FILTER_SANITIZE_NUMBER_INT);

				$hasError = false;

				if ($title == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('title');
				} else if ($category_id == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('category');
				} else if (($customer_id == '') && ($internal == '0')) {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('customer');
				} else if (($internal == '1') && ($this->input->post('staff') == '')) {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('staff');
				} else if ($account_id == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('account');
				} else if ($this->input->post('totalItems') < 1) {
					$hasError = true;
					$data['message'] = lang('invalid_expense_items');
				} else if (((int)($this->input->post('totalItems'))) == 0) {
					$hasError = true;
					$data['message'] = lang('invalid_expense_items_value');
				} else if ($total == 0) {
					$hasError = true;
					$data['message'] = lang('invalid_items_total');
				}

				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}

				if (!$hasError) {
					$appconfig = get_appconfig();
					if ($internal == '1') {
						$staff = $this->input->post( 'staff' );
						$customer = NULL;
					} else {
						$staff = $this->session->usr_id;
						$customer = $customer_id;
					}
					$params = array(
						'hash' => $hash,
						'category_id' => $category_id,
						'staff_id' => $staff,
						'customer_id' => $customer,
						'account_id' => $account_id,
						'title' => $title,
						'number' => $this->input->post('number'),
						'date' => $date,
						'created' => date( 'Y-m-d H:i:s' ),
						'amount' => $this->input->post( 'total' ),
						'total_tax' => $this->input->post( 'total_tax' ),
						'total_discount' => $this->input->post( 'total_discount' ),
						'sub_total' => $this->input->post( 'sub_total' ),
						'description' => $description,
						'internal' => $internal,
						'last_recurring' => $date,
						'expense_created_by' => $this->session->usr_id,
					);
					$expenses_id = $this->Expenses_Model->create( $params );
					if ( $this->input->post( 'custom_fields' ) ) {
						$custom_fields = array(
							'custom_fields' => $this->input->post( 'custom_fields' )
						);
						$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'expense', $expenses_id );
					}
					$this->Settings_Model->create_process('pdf', $expenses_id, 'expense', 'expense_created');
					if ( $this->input->post( 'recurring' ) == 'true'  || $this->input->post('recurring') == '1') {
						$SHXparams = array(
							'relation_type' => 'expense',
							'relation' => $expenses_id,
							'period' => $this->input->post( 'recurring_period' ),
							'end_date' => $this->input->post( 'end_recurring' ),
							'type' => $this->input->post( 'recurring_type' ),
						);
						$recurring_invoices_id = $this->Invoices_Model->recurring_add( $SHXparams );
					}
					$data['success'] = true;
					$data['message'] = lang('expense'). ' ' .lang('createmessage');
					$data['id'] = $expenses_id;
					if($appconfig['expense_series']){
						$expense_number = $appconfig['expense_series'];
						$expense_number = $expense_number + 1 ;
						$this->Settings_Model->increment_series('expense_series',$expense_number);
					}
					echo json_encode($data);
				}
			} else {
				$data['title'] = lang('newexpense');
				$data[ 'all_customers' ] = $this->Customers_Model->get_all_customers();
				$data[ 'all_accounts' ] = $this->Accounts_Model->get_all_accounts();
				$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
				$this->load->view( 'expenses/create', $data );
			}
		} else {
			$this->session->set_flashdata( 'ntf3', lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));
		}
	}

	function update( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'all' ) ) {
			$data[ 'expenses' ] = $this->Expenses_Model->get_expenses_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'expenses', 'own') ) {
			$data[ 'expenses' ] = $this->Expenses_Model->get_expenses_by_privileges( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));
		}
		if($data[ 'expenses' ]) {
			if ( $this->Privileges_Model->check_privilege( 'expenses', 'edit' ) ) {
				if (!$this->session->userdata('other')) {
					if ( isset( $data[ 'expenses' ][ 'id' ] ) ) {
						if ( isset( $_POST ) && count( $_POST ) > 0 ) {
							$category_id = $this->input->post( 'category' );
							$customer_id = $this->input->post( 'customer' );
							$account_id = $this->input->post( 'account' );
							$title = $this->input->post( 'title' );
							$date = $this->input->post( 'date' );
							$amount = $this->input->post( 'amount' );
							$description = $this->input->post( 'description' );
							$internal = $this->input->post( 'internal' );
							$internal = ($internal == 'true')? '1' : '0';
							$total = filter_var($this->input->post('total'), FILTER_SANITIZE_NUMBER_INT);

							$hasError = false;

							if ($title == '') {
								$hasError = true;
								$data['message'] = lang('invalidmessage'). ' ' .lang('title');
							} else if ($category_id == '') {
								$hasError = true;
								$data['message'] = lang('selectinvalidmessage'). ' ' .lang('category');
							} else if (($customer_id == '') && ($internal == '0')) {
								$hasError = true;
								$data['message'] = lang('selectinvalidmessage'). ' ' .lang('customer');
							} else if (($internal == '1') && ($this->input->post('staff') == '')) {
								$hasError = true;
								$data['message'] = lang('selectinvalidmessage'). ' ' .lang('staff');
							} else if ($account_id == '') {
								$hasError = true;
								$data['message'] = lang('selectinvalidmessage'). ' ' .lang('account');
							} else if ($this->input->post('totalItems') < 1) {
								$hasError = true;
								$data['message'] = lang('invalid_expense_items');
							} else if (((int)($this->input->post('totalItems'))) == 0) {
								$hasError = true;
								$data['message'] = lang('invalid_expense_items_value');
							} else if ($total == 0) {
								$hasError = true;
								$data['message'] = lang('invalid_items_total');
							}

							if ($hasError) {
								$data['success'] = false;
								echo json_encode($data);
							}

							if (!$hasError) {
								if ($internal == '1') {
									$staff = $this->input->post( 'staff' );
									$customer = NULL;
								} else {
									$staff = $this->session->usr_id;
									$customer = $customer_id;
								}
								$params = array(
									'category_id' => $category_id,
									'staff_id' => $staff,
									'customer_id' => $customer,
									'account_id' => $account_id,
									'title' => $title,
									'number' => $this->input->post('number'),
									'date' => $date,
									'amount' => $this->input->post( 'total' ),
									'total_tax' => $this->input->post( 'total_tax' ),
									'sub_total' => $this->input->post( 'sub_total' ),
									'description' => $description,
									'internal' => $internal,
									'last_recurring' => date('Y-m-d'),
								);
								$this->Expenses_Model->update_expenses( $id, $params );
								// Custom Field Post
								if ( $this->input->post( 'custom_fields' ) ) {
									$custom_fields = array(
										'custom_fields' => $this->input->post( 'custom_fields' )
									);
									$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'expense', $id );
								}
								if ($this->input->post('recurring') == 'true'  || $this->input->post('recurring') == '1') {
									$SHXparams = array(
										'period' => $this->input->post( 'recurring_period' ),
										'end_date' => $this->input->post( 'end_recurring' ),
										'type' => $this->input->post( 'recurring_type' ),
										'status' => 0,
									);
									$recurring_invoices_id = $this->Expenses_Model->recurring_update( $id, $SHXparams );
								} else {
									$SHXparams = array(
										'period' => $this->input->post( 'recurring_period' ),
										'end_date' => $this->input->post( 'end_recurring' ),
										'type' => $this->input->post( 'recurring_type' ),
										'status' => 1,
									);
									$recurring_invoices_id = $this->Expenses_Model->recurring_update( $id, $SHXparams );
								}
								if ( !is_numeric( $this->input->post( 'recurring_id' ) ) && ($this->input->post('recurring') == 'true'  || $this->input->post('recurring') == '1') ) {
									$SHXparams = array(
										'relation_type' => 'expense',
										'relation' => $id,
										'period' => $this->input->post( 'recurring_period' ),
										'end_date' => $this->input->post( 'end_recurring' ),
										'type' => $this->input->post( 'recurring_type' ),
									);
									$recurring_invoices_id = $this->Invoices_Model->recurring_add( $SHXparams );
								}
								$this->Expenses_Model->update_pdf_status($id, '0');
								$data['success'] = true;
								$data['message'] = lang('expense'). ' '.lang('updatemessage');
								$data['id'] = $id;
								echo json_encode($data);
							}
						} else {
							$data[ 'title' ] = lang('update'). ' '. lang('expense');
							$data[ 'all_customers' ] = $this->Customers_Model->get_all_customers();
							$data[ 'all_accounts' ] = $this->Accounts_Model->get_all_accounts();
							$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
							$this->load->view( 'expenses/update', $data );
						}
					} else {
						$this->session->set_flashdata( 'ntf3', '' . $id . lang( 'error' ) );
						redirect('expenses');
					}
				} else {
					$this->session->set_flashdata( 'ntf3', '' . $id . lang( 'you_dont_have_permission' ) );
					redirect('expenses');
				}
			} else{
				$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
				redirect('expenses/receipt/'.$id);
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));
		}
	}

	function receipt( $id ) {
		$data[ 'title' ] = lang( 'expense' );
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'all' ) ) {
			$data[ 'expenses' ] = $this->Expenses_Model->get_expenses_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'expenses', 'own') ) {
			$data[ 'expenses' ] = $this->Expenses_Model->get_expenses_by_privileges( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));
		}
		if($data[ 'expenses' ] ) {
			if ( isset( $data[ 'expenses' ][ 'id' ] ) ) {
				if ( isset( $_POST ) && count( $_POST ) > 0 ) {
					$params = array(
						'category_id' => $this->input->post( 'category' ),
						'staff_id' => $this->input->post( 'staff' ),
						'customer_id' => $this->input->post( 'customer' ),
						'account_id' => $this->input->post( 'account' ),
						'title' => $this->input->post( 'title' ),
						'date' => _pdate( $this->input->post( 'date' ) ),
						'created' => $this->input->post( 'created' ),
						'amount' => $this->input->post( 'amount' ),
						'description' => $this->input->post( 'description' ),
					);
					$this->Expenses_Model->update_expenses( $id, $params );
					redirect( 'expenses/index' );
				} else {
					$this->load->view( 'expenses/receipt', $data );
				}
			} else {
				show_error( 'The expenses you are trying to edit does not exist.' );
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));
		}
	}

	function add_file( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'edit' ) ) {
			if ( isset( $id ) ) {
				if ( isset( $_POST ) ) {
					if (!is_dir('uploads/files/expenses/'.$id)) { 
						mkdir('./uploads/files/expenses/'.$id, 0777, true);
					}
					$config[ 'upload_path' ] = './uploads/files/expenses/'.$id.'';
					$config[ 'allowed_types' ] = 'zip|rar|tar|gif|jpg|png|jpeg|gif|pdf|doc|docx|xls|xlsx|txt|csv|ppt|opt';
					$config['max_size'] = '9000';
					$new_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES["file"]['name']));
					$config['file'] = $new_name;
					$this->load->library( 'upload', $config );
					$this->upload->do_upload('file');
					$data_upload_files = $this->upload->data();
					$image_data = $this->upload->data();
					if (is_file('./uploads/files/expenses/'.$id.'/'.$image_data[ 'file_name' ])) {
						$params = array(
							'relation_type' => 'expense',
							'relation' => $id,
							'file_name' => $image_data[ 'file_name' ],
							'created' => date( " Y.m.d H:i:s " ),
						);
						$this->Expenses_Model->update_pdf_status($id, '0');
						$this->db->insert( 'files', $params );
						$data['success'] = true;
						$data['message'] = lang('file').' '.lang('uploadmessage');
						echo json_encode($data);
					} else {
						$data['success'] = false;
						$data['message'] = $this->upload->display_errors();
						echo json_encode($data);
					}
					//redirect( 'expenses/receipt/' . $id . '' );
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function delete_file($id) {
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'delete' ) ) {
			if (isset($id)) {
				$fileData = $this->Expenses_Model->get_file($id);
				$response = $this->db->where( 'id', $id )->delete( 'files', array( 'id' => $id ) );
				if (is_file('./uploads/files/expenses/'.$fileData['relation'].'/' . $fileData['file_name'])) {
		    		unlink('./uploads/files/expenses/'.$fileData['relation'].'/' . $fileData['file_name']);
		    	}
		    	$this->Expenses_Model->update_pdf_status($fileData['relation'], '0');
		    	if ($response) {
		    		$data['success'] = true;
		    		$data['message'] = lang('file'). ' '.lang('deletemessage');
		    	} else {
		    		$data['success'] = false;
		    		$data['message'] = lang('errormessage');
		    	}
		    	echo json_encode($data);
			} else {
				redirect('expenses');
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function download_file($id) {
		if (isset($id)) {
			$fileData = $this->Expenses_Model->get_file( $id );
			if (is_file('./uploads/files/expenses/'.$fileData['relation'].'/' . $fileData['file_name'])) {
	    		$this->load->helper('file');
	    		$this->load->helper('download');
	    		$data = file_get_contents('./uploads/files/expenses/'.$fileData['relation'].'/' . $fileData['file_name']);
	    		force_download($fileData['file_name'], $data);
	    	} else {
	    		$this->session->set_flashdata( 'ntf4', lang('filenotexist'));
	    		redirect('expenses/receipt/'.$fileData['relation']);
	    	}
		}
	}

	function download_pdf($id) { 
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'all' ) ) {
			$expense = $this->Expenses_Model->get_expenses_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'expenses', 'own') ) {
			$expense = $this->Expenses_Model->get_expenses_by_privileges( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));
		}
		if($expense) {
			if (isset($id)) {
				$file_name = '' . get_number('expenses', $id, 'expense', 'expense') . '.pdf';
				if (is_file('./uploads/files/expenses/'.$id.'/' . $file_name)) {
		    		$this->load->helper('file');
		    		$this->load->helper('download');
		    		$data = file_get_contents('./uploads/files/expenses/'.$id.'/' . $file_name);
		    		force_download($file_name, $data);
		    	} else {
		    		$this->session->set_flashdata( 'ntf4', lang('filenotexist'));
		    		redirect('expenses/receipt/'.$id);
		    	}
			} else {
				redirect('expenses/receipt/'.$id);
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));
		}
	} 

	function create_pdf( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'all' ) ) {
			$expense = $this->Expenses_Model->get_expenses_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'expenses', 'own') ) {
			$expense = $this->Expenses_Model->get_expenses_by_privileges( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));
		}
		if($expense) {
			ini_set('max_execution_time', 0); 
			ini_set('memory_limit','2048M');
			if (!is_dir('uploads/files/expenses/'.$id)) {
				mkdir('./uploads/files/expenses/'.$id, 0777, true);
			}
			$data[ 'expense' ] = $this->Expenses_Model->all_expenses( $id );
			$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
			$data['state'] = get_state_name($data['settings']['state'],$data['settings']['state_id']);
			$data['billing_country'] = get_country($data[ 'expense' ]['country_id']);
			$data['billing_state'] = get_state_name($data['expense']['billing_state'],$data['expense']['billing_state_id']);
			$data['country'] = get_country($data[ 'settings' ]['country_id']);
			$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'expense', 'relation' => $id ) )->result_array();
				$files = $this->Expenses_Model->get_files( $id );
				$images = array();
				$otherFiles = array();
				foreach ($files as $file) {
					$ext = pathinfo($file['file_name'], PATHINFO_EXTENSION);
					if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif') {
						$display = true;
						$images[] = array(
							'id' => $file['id'],
							'expense_id' => $file['relation'],
							'file_name' => $file['file_name'],
							'created' => $file['created'],
							'display' => $display,
							'path' => base_url('uploads/files/expenses/'.$id.'/'.$file['file_name']),
						);
					} else {
						$display = false;
						$otherFiles[] = array(
							'id' => $file['id'],
							'expense_id' => $file['relation'],
							'file_name' => $file['file_name'],
							'created' => $file['created'],
							'display' => $display,
							'path' => base_url('uploads/files/expenses/'.$id.'/'.$file['file_name']),
						);
					}
				}
			$data['images'] = $images;
			$data['otherFiles'] = $otherFiles;
			$this->load->view( 'expenses/pdf', $data );
			$file_name = '' . get_number('expenses', $id, 'expense', 'expense') . '.pdf';
			$html = $this->output->get_output();
			$this->load->library( 'dom' );
			$this->dompdf->loadHtml( $html );
			$this->dompdf->set_option('isRemoteEnabled', TRUE );
			$this->dompdf->setPaper('A4', 'portrait' );
			$this->dompdf->render();
			$output = $this->dompdf->output();
			file_put_contents( 'uploads/files/expenses/'.$id.'/' . $file_name . '', $output ); 
			$this->Expenses_Model->update_pdf_status($id, '1');
			if ($output) {
				redirect(base_url('expenses/pdf_generated/'.$file_name.''));
			} else {
				redirect( base_url('expenses/pdf_fault/'));
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));
		}
	}

	function print_pdf( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'all' ) ) {
			$expense = $this->Expenses_Model->get_expenses_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'expenses', 'own') ) {
			$expense = $this->Expenses_Model->get_expenses_by_privileges( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));
		}
		if($expense) {
			ini_set('max_execution_time', 0); 
			ini_set('memory_limit','2048M');
			if (!is_dir('uploads/files/expenses/'.$id)) {
				mkdir('./uploads/files/expenses/'.$id, 0777, true);
			}
			$data[ 'expense' ] = $this->Expenses_Model->all_expenses( $id );
			$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
			$data['state'] = get_state_name($data['settings']['state'],$data['settings']['state_id']);
			$data['billing_state'] = get_state_name($data['expense']['billing_state'],$data['expense']['billing_state_id']);
			$data['billing_country'] = get_country($data[ 'expense' ]['country_id']);
			$data['country'] = get_country($data[ 'settings' ]['country_id']);
			$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'expense', 'relation' => $id ) )->result_array();
				$files = $this->Expenses_Model->get_files( $id );
				$images = array();
				$otherFiles = array();
				foreach ($files as $file) {
					$ext = pathinfo($file['file_name'], PATHINFO_EXTENSION);
					if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif') {
						$display = true;
						$images[] = array(
							'id' => $file['id'],
							'expense_id' => $file['relation'],
							'file_name' => $file['file_name'],
							'created' => $file['created'],
							'display' => $display,
							'path' => base_url('uploads/files/expenses/'.$id.'/'.$file['file_name']),
						);
					} else {
						$display = false;
						$otherFiles[] = array(
							'id' => $file['id'],
							'expense_id' => $file['relation'],
							'file_name' => $file['file_name'],
							'created' => $file['created'],
							'display' => $display,
							'path' => base_url('uploads/files/expenses/'.$id.'/'.$file['file_name']),
						);
					}
				}
			$data['images'] = $images;
			$data['otherFiles'] = $otherFiles;
			$this->load->view( 'expenses/pdf', $data );
			$appconfig = get_appconfig();
			$file_name = '' . get_number('expenses', $id, 'expense', 'expense') . '.pdf';
			$html = $this->output->get_output();
			$this->load->library( 'dom' );
			$this->dompdf->loadHtml( $html );
			$this->dompdf->set_option('isRemoteEnabled', TRUE );
			$this->dompdf->setPaper('A4', 'portrait' );
			$this->dompdf->render();
			$output = $this->dompdf->output();
			file_put_contents( 'uploads/files/expenses/'.$id.'/' . $file_name . '', $output );
			if ($output) {
				$this->dompdf->stream( '' . $file_name . '', array( "Attachment" => 0 ) );
			} else {
				redirect( base_url( 'expenses/pdf_fault/' ) );
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));
		}
	}

	function generate_pdf($id) {
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'all' ) ) {
			$expense = $this->Expenses_Model->get_expenses_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'expenses', 'own') ) {
			$expense = $this->Expenses_Model->get_expenses_by_privileges( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));
		}
		if($expenses) {
			ini_set('max_execution_time', 0); 
			ini_set('memory_limit','2048M');
			if (!is_dir('uploads/files/expenses/'.$id)) {
				mkdir('./uploads/files/expenses/'.$id, 0777, true);
			}
			$data[ 'expense' ] = $this->Expenses_Model->all_expenses( $id );
			$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
			$data['billing_state'] = get_state_name($data['expense']['billing_state'],$data['expense']['billing_state_id']);
			$data['country'] = get_country($data[ 'settings' ]['country_id']);
			$data['billing_country'] = get_country($data[ 'expense' ]['country_id']);
			$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'expense', 'relation' => $id ) )->result_array();
				$files = $this->Expenses_Model->get_files( $id );
				$images = array();
				$otherFiles = array();
				foreach ($files as $file) {
					$ext = pathinfo($file['file_name'], PATHINFO_EXTENSION);
					if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif') {
						$display = true;
						$images[] = array(
							'id' => $file['id'],
							'expense_id' => $file['relation'],
							'file_name' => $file['file_name'],
							'created' => $file['created'],
							'display' => $display,
							'path' => base_url('uploads/files/expenses/'.$id.'/'.$file['file_name']),
						);
					} else {
						$display = false;
						$otherFiles[] = array(
							'id' => $file['id'],
							'expense_id' => $file['relation'],
							'file_name' => $file['file_name'],
							'created' => $file['created'],
							'display' => $display,
							'path' => base_url('uploads/files/expenses/'.$id.'/'.$file['file_name']),
						);
					}
				}
			$data['images'] = $images;
			$data['otherFiles'] = $otherFiles;
			$this->load->view( 'expenses/pdf', $data );
			$appconfig = get_appconfig();
			$file_name = '' . get_number('expenses', $id, 'expense', 'expense') . '.pdf';
			$html = $this->output->get_output();
			$this->load->library( 'dom' );
			$this->dompdf->loadHtml( $html );
			$this->dompdf->set_option('isRemoteEnabled', TRUE );
			$this->dompdf->setPaper('A4', 'portrait' );
			$this->dompdf->render();
			$output = $this->dompdf->output();
			file_put_contents( 'uploads/files/expenses/'.$id.'/' . $file_name . '', $output ); 
			$this->Expenses_Model->update_pdf_status($id, '1');
			return true;
			//redirect(base_url('expenses/pdf_generates/'.$file_name.''));
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));
		}
	}

	function send_expense_email($id) {
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'all' ) ) {
			$expense = $this->Expenses_Model->get_expenses_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'expenses', 'own') ) {
			$expense = $this->Expenses_Model->get_expenses_by_privileges( $id, $this->session->usr_id );
		} else {
			$return['status'] = false;
			$return['message'] = lang('you_dont_have_permission');
			echo json_encode($return);
		}
		if($expense) {
			$template = $this->Emails_Model->get_template('expense', 'expense_created');
			$path = '';
			if ($template['attachment'] == '1') {
				$appconfig = get_appconfig();
				if ($expense['pdf_status'] == '0') {
					$this->Expenses_Model->generate_pdf($id);
					$file = get_number('expenses', $expense['id'], 'expense', 'expense');
					$path = base_url('uploads/files/expenses/'.$id.'/'.$file.'.pdf');
				} else {
					$file = get_number('expenses', $expense['id'], 'expense', 'expense');
					$path = base_url('uploads/files/expenses/'.$id.'/'.$file.'.pdf');
				}
			}
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
				'{expense_number}' => get_number('expenses', $expense['id'], 'expense', 'expense'),
				'{expense_title}' => $expense[ 'title' ],
				'{expense_category}' => $expense[ 'category' ],
				'{expense_date}' => $expense[ 'date' ],
				'{expense_description}' => $expense[ 'description' ],
				'{expense_amount}' => $expense[ 'amount' ],
				'{name}' => $this->session->userdata('staffname'),
				'{email_signature}' => $this->session->userdata('email'),
			);
			$subject = strtr($template['subject'], $message_vars);
			$message = strtr($template['message'], $message_vars);

			$email = $expense['customeremail'] ? $expense['customeremail'] : $expense['staffemail'];
			if ($email) {
				$param = array(
					'from_name' => $template['from_name'],
					'email' => $email,
					'subject' => $subject,
					'message' => $message,
					'created' => date( "Y.m.d H:i:s" ),
					'status' => 0,
					'attachments' => $path?$path:NULL
				);
				$this->load->library('mail'); 
				$data = $this->mail->send_email($email, $template['from_name'], $subject, $message, $path);
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

	function pdf_generates( $file ) {
		return true;
	}

	function pdf_generated( $file ) {
		$result = array(
			'status' => true,
			'file_name' => $file,
		);
		echo json_encode( $result );
	}

	function files($id) {
		if (isset($id)) {
			$files = $this->Expenses_Model->get_files( $id );
			$data = array();
			foreach ($files as $file) {
				$ext = pathinfo($file['file_name'], PATHINFO_EXTENSION);
				$type = 'file';
				if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif') {
					$type = 'image';
				}
				if ($ext == 'pdf') {
					$type = 'pdf';
				}
				if ($ext == 'zip' || $ext == 'rar' || $ext == 'tar') {
					$type = 'archive';
				}
				if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif') {
					$display = true;
				} else {
					$display = false;
				}
				if ($ext == 'pdf') {
					$pdf = true;
				} else {
					$pdf = false;
				}
				$data[] = array(
					'id' => $file['id'],
					'expense_id' => $file['relation'],
					'file_name' => $file['file_name'],
					'created' => $file['created'],
					'display' => $display,
					'pdf' => $pdf,
					'type' => $type,
					'path' => base_url('uploads/files/expenses/'.$id.'/'.$file['file_name']),
				);
			}
			echo json_encode($data);
		}
	}

	function convert( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'create' ) ) {
			$expenses = $this->Expenses_Model->get_expenses( $id );
			if ( isset( $id ) ) {
				$params = array(
					'staff_id' => $expenses[ 'staff_id' ],
					'customer_id' => $expenses[ 'customer_id' ],
					'created' => date( 'Y-m-d H:i:s' ),
					'status_id' => 3,
					'total' => $expenses[ 'amount' ],
					'sub_total' => $expenses[ 'sub_total' ],
					'total_discount' => $expenses[ 'total_discount' ],
					'total_tax' => $expenses[ 'total_tax' ],
					'serie' => $expenses[ 'number' ],
					'expense_id' => $id,
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
				$items = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'expense', 'relation' => $id ) )->result_array();
				foreach ($items as $item) {
					$this->db->insert( 'items', array(
						'relation' => $invoice,
						'relation_type' => 'invoice',
						'name' => $item[ 'name' ],
						'total' => $item[ 'total' ],
						'price' => $item[ 'price' ],
						'tax' => $item['tax'],
						'discount' => $item['discount'],
						'quantity' => $item[ 'quantity' ],
						'unit' => $item[ 'unit' ],
						'description' => $item[ 'description' ],
					));
				}
				$loggedinuserid = $this->session->usr_id;
				$this->db->insert( $this->db->dbprefix . 'sales', array(
					'invoice_id' => '' . $invoice . '',
					'status_id' => 3,
					'staff_id' => $loggedinuserid,
					'customer_id' => $expenses[ 'customer_id' ],
					'total' => $expenses[ 'amount' ],
					'date' => date( 'Y-m-d H:i:s' )
				));
				$staffname = $this->session->staffname;
				$this->db->insert( 'logs', array(
					'date' => date( 'Y-m-d H:i:s' ),
					'detail' => ( '' . $message = sprintf( lang( 'coverttoinvoice' ), $staffname, get_number('expenses', $expenses[ 'id' ], 'expense','expense') ) . '' ),
					'staff_id' => $loggedinuserid,
					'customer_id' => $expenses[ 'customer_id' ],
				) );
				$response = $this->db->where( 'id', $id )->update( 'expenses', array( 'invoice_id' => $invoice ) );
				$data['id'] = $invoice;
				$data['success'] = true;
				echo json_encode($data) ;
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function remove( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'all' ) ) {
			$expense = $this->Expenses_Model->get_expenses_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'expenses', 'own') ) {
			$expense = $this->Expenses_Model->get_expenses_by_privileges( $id, $this->session->usr_id );
		} else {
			$data['success'] = false;
			$data['message'] = lang( 'you_dont_have_permission' );
			echo json_encode($data);
		}
		if($expense) {
			if ( $this->Privileges_Model->check_privilege( 'expenses', 'delete' ) ) {
				if ( isset( $expense[ 'id' ] ) ) {
					$this->Expenses_Model->delete_expenses( $id, get_number('expenses', $id,'expense','expense') );
					$this->load->helper('file');
					$folder = './uploads/files/expenses/'.$id;
					if(is_dir($folder)) {
						delete_files($folder, true);
						rmdir($folder);	
					}
					$data['success'] = true;
					$data['message'] = lang('expense').' '.lang('deletemessage');
					echo json_encode($data);
				} else {
					show_error( 'The expenses you are trying to delete does not exist.' );
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang( 'you_dont_have_permission' );
				echo json_encode($data);
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));
		}
	}

	function add_category() {
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'create' ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'name' => $this->input->post( 'name' ),
					'description' => $this->input->post( 'description' ),
				);
				$category = $this->Expenses_Model->add_category( $params );
				$data['success'] = true;
				$data['message'] = lang('expensecategory'). ' ' .lang('createmessage');
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	} 

	function update_category( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'edit' ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'name' => $this->input->post( 'name' ),
					'description' => $this->input->post( 'description' ),
				);
				$this->Expenses_Model->update_category( $id, $params );
				$data['success'] = true;
				$data['message'] = lang('expensecategory'). ' ' .lang('updatemessage');
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function remove_category( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'delete' ) ) {
			$expensecategory = $this->Expenses_Model->get_expensecategory( $id );
			if ( isset( $expensecategory[ 'id' ] ) ) {
				if ($this->Expenses_Model->check_category($id) == 0) {
					$this->Expenses_Model->delete_category( $id );
					$data['success'] = true;
					$data['message'] = lang('expensecategory'). ' ' .lang('deletemessage');
				} else {
					$data['success'] = false;
					$data['message'] = $data['message'] = lang('category').' '.lang('is_linked').' '.lang('with').' '.lang('expense').', '.lang('so').' '.lang('cannot_delete').' '.lang('category');
				}
			} else {
				$data['success'] = false;
				$data['message'] = 'The expensecategory you are trying to delete does not exist.';
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function get_expense( $id ) {
		$expense = array();
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'all' ) ) {
			$expense = $this->Expenses_Model->all_expenses( $id );
		} else if ($this->Privileges_Model->check_privilege( 'expenses', 'own') ) {
			$expense = $this->Expenses_Model->all_expenses( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));
		}
		if($expense) {
			$items = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'expense', 'relation' => $id ) )->result_array();

			if ($expense[ 'recurring_endDate' ] != 'Invalid date') {
				$recurring_endDate = date( DATE_ISO8601, strtotime( $expense[ 'recurring_endDate' ] ) );
			} else {
				$recurring_endDate = '';
			}
			$appconfig = get_appconfig();
			$data_expense = array( 
				'id' => $expense[ 'id' ],
				'prefix' => $appconfig['expense_prefix'],
				'longid' => get_number('expenses', $expense[ 'id' ], 'expense','expense'),
				'title' => $expense[ 'title' ],
				'amount' => $expense[ 'amount' ],
				'total' => $expense[ 'amount' ],
				'date' => date(get_dateFormat(),strtotime($expense[ 'date' ])),
				'date_edit' => $expense[ 'date' ],
				'created' =>  date(get_dateFormat(),strtotime($expense[ 'created' ])),
				'internal' => $expense[ 'internal' ] == '1' ? true : false,
				'category' => $expense[ 'category_id' ],
				'customer' => $expense[ 'customer_id' ],
				'customername' => $expense['individual']?$expense['individual']:$expense['customer'],
				'customeremail' => $expense[ 'customeremail' ],
				'customer_phone' => $expense[ 'customer_phone' ],
				'account' => $expense[ 'account_id' ],
				'number' => $expense[ 'number' ],
				'invoice_id' => $expense[ 'invoice_id' ],
				'pdf_status' => $expense[ 'pdf_status' ],
				'description' => $expense[ 'desc' ],
				'category_name' => $expense[ 'category' ],
				'staff_name' => $expense[ 'staff' ],
				'staff_id' => $expense['staff_id'],
				'account_name' => $expense[ 'account' ], 
				'sub_total' => $expense[ 'sub_total' ],
				'total_discount' => $expense[ 'total_discount' ],
				'total_tax' => $expense[ 'total_tax' ],
				'items' => $items,
				'EndRecurring' => $recurring_endDate,
				'recurring_id' => $expense[ 'recurring_id' ],
				'recurring_status' => $expense[ 'recurring_status' ] == '0' ? true : false,
				'recurring_period' => (int)$expense[ 'recurring_period' ],
				'recurring_type' => $expense[ 'recurring_type' ] ? $expense[ 'recurring_type' ] : 0,
			);
			echo json_encode( $data_expense );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('expenses'));	
		}
	}

	function get_expenses() {
		$expenses = array();
		if ( $this->Privileges_Model->check_privilege( 'expenses', 'all' ) ) {
			$expenses = $this->Expenses_Model->get_all_expenses_by_privileges();
		} else if ( $this->Privileges_Model->check_privilege( 'expenses', 'own' ) ) {
			$expenses = $this->Expenses_Model->get_all_expenses_by_privileges($this->session->usr_id);
		}
		$data_expenses = array();
		foreach ( $expenses as $expense ) {
			$settings = $this->Settings_Model->get_settings_ciuis();
			switch ( $settings[ 'dateformat' ] ) {
				case 'yy.mm.dd':
					$expensedate = _rdate( $expense[ 'date' ] );
					break;
				case 'dd.mm.yy':
					$expensedate = _udate( $expense[ 'date' ] );
					break;
				case 'yy-mm-dd':
					$expensedate = _mdate( $expense[ 'date' ] );
					break;
				case 'dd-mm-yy':
					$expensedate = _cdate( $expense[ 'date' ] );
					break;
				case 'yy/mm/dd':
					$expensedate = _zdate( $expense[ 'date' ] );
					break;
				case 'dd/mm/yy':
					$expensedate = _kdate( $expense[ 'date' ] );
					break;
			};
			if ( $expense[ 'invoice_id' ] == NULL) {
				$billstatus = lang( 'notbilled' ) and $color = 'warning';
			} else {
				$billstatus = lang( 'billed' ) and $color = 'success';
			}
			if ( $expense[ 'customer_id' ] != 0 ) {
				$billable = 'true';
			} else {
				$billable = 'false';
			}
			if ( $expense[ 'internal' ] == '1') {
				$billstatus = lang( 'internal' ) and $color = 'success';
			}
			$appconfig = get_appconfig();
			$data_expenses[] = array(
				'id' => $expense[ 'id' ],
				'title' => $expense[ 'title' ],
				'prefix' => $appconfig['expense_prefix'],
				'longid' => get_number('expenses', $expense[ 'id' ], 'expense','expense'),
				'amount' => (float)$expense[ 'amount' ],
				'staff' => $expense[ 'staff' ],
				'category' => $expense[ 'category' ],
				'billstatus' => $billstatus,
				'color' => $color,
				'billable' => $billable,
				'date' => $expensedate,
				'' . lang( 'filterbycategory' ) . '' => $expense[ 'category' ],
				'' . lang( 'filterbybillstatus' ) . '' => $billstatus,
			);
		};
		echo json_encode( $data_expenses );
	}

}