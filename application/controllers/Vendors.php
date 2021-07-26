<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Vendors extends CIUIS_Controller {

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
		$data[ 'title' ] = lang( 'vendors' );
		$data[ 'vendors' ] = $this->Vendors_Model->get_all_vendors();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'vendors/index', $data );
	}

	function create() {
		if ( $this->Privileges_Model->check_privilege( 'vendors', 'create' ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$company = $this->input->post( 'name' );
				$email = $this->input->post( 'email' );
				$groupid = $this->input->post( 'groupid' );
				$hasError = false;
				$data['message'] = '';
				if ($company == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('vendor'). ' ' . lang('name') ;
				}else if ($groupid == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('vendor'). ' ' .lang('group');
				}else if ($email == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('vendor'). ' ' .lang('email');
				}
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					$appconfig = get_appconfig();
					$params = array(
						'created' => date( 'Y-m-d H:i:s' ),
						'company' => $this->input->post( 'name' ),
						'groupid' => $this->input->post( 'groupid' ),
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
						'state' => $this->input->post( 'state' ),
						'city' => $this->input->post( 'city' ),
						'town' => $this->input->post( 'town' ),
						'zipcode' => $this->input->post( 'zipcode' ),
						'staff_id' => $this->session->userdata( 'usr_id' ),
						'vendor_status_id' => '1',
					);
					$vendors_id = $this->Vendors_Model->add_vendors( $params );
					$data['success'] = true;
					$data['id'] = $vendors_id;
					if($appconfig['vendor_series']){
						$vendor_number = $appconfig['vendor_series'];
						$vendor_number = $vendor_number + 1 ;
						$this->Settings_Model->increment_series('vendor_series',$vendor_number);
					}
					echo json_encode($data);
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang( 'you_dont_have_permission' );
			echo json_encode($data);
		}
	}

	function vendor( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'vendors', 'all' ) ) {
			$vendor = $this->Vendors_Model->get_vendor_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'vendors', 'own') ) {
			$vendor = $this->Vendors_Model->get_vendor_by_privileges( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('vendors'));
		}
		if($vendor) {
			$data[ 'title' ] = lang( 'vendor' ).' '.get_number('vendors', $id, 'vendor', 'vendor');
			$data[ 'ycr' ] = $this->Report_Model->ycr();
			if ( isset( $vendor[ 'id' ] ) ) {
				if ( isset( $_POST ) && count( $_POST ) > 0 ) {
					if ( $this->Privileges_Model->check_privilege( 'vendors', 'edit' ) ) {
						$company = $this->input->post( 'name' );
						$email = $this->input->post( 'email' );
						$groupid = $this->input->post( 'groupid' );
						$hasError = false;
						$data['message'] = '';
						if ($company == '') {
							$hasError = true;
							$data['message'] = lang('invalidmessage'). ' ' .lang('vendor'). ' ' . lang('name') ;
						}else if ($groupid == '') {
							$hasError = true;
							$data['message'] = lang('invalidmessage'). ' ' .lang('vendor'). ' ' .lang('group');
						}else if ($email == '') {
							$hasError = true;
							$data['message'] = lang('invalidmessage'). ' ' .lang('vendor'). ' ' .lang('email');
						}
						if ($hasError) {
							$data['success'] = false;
							echo json_encode($data);
						}
						if (!$hasError) {
							$appconfig = get_appconfig();
							$params = array(
								'company' => $this->input->post( 'name' ),
								'groupid' => $this->input->post('groupid'),
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
								'state' => $this->input->post( 'state' ),
								'city' => $this->input->post( 'city' ),
								'town' => $this->input->post( 'town' ),
								'zipcode' => $this->input->post( 'zipcode' ),
								'staff_id' => $this->session->userdata( 'usr_id' ),
								'risk' => $this->input->post( 'risk' ),
								'vendor_status_id' => $this->input->post( 'status_id' ),
								'vendor_number' => get_number('vendors', $id, 'vendor', 'vendor'),
							);
							$this->Vendors_Model->update_vendors( $id, $params );
							$data['success'] = true;
							$data['message'] = lang('vendorsupdated');
							echo json_encode($data);
						}	
					} else {
						$data['success'] = false;
						$data['message'] = lang( 'you_dont_have_permission' );
						echo json_encode($data);
					}
				} else {
					$data[ 'vendors' ] = $this->Vendors_Model->get_vendors( $id );
					$this->load->view( 'vendors/vendor', $data );
				}
			} else {
				redirect(base_url('vendors'));
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('vendors'));
		}
	}

	function groups() {
		if ( $this->Privileges_Model->check_privilege( 'vendors', 'all' ) ) {
			$data = $this->Vendors_Model->get_groups();
		} else if ($this->Privileges_Model->check_privilege( 'vendors', 'own') ) {
			$data = $this->Vendors_Model->get_groups($this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('vendors'));
		}
		echo json_encode( $data );
	}

	function add_group() {
		if ( $this->Privileges_Model->check_privilege( 'vendors', 'create' ) ) {
			if (isset($_POST)) {
				$params = array(
					'name' => $this->input->post('name')
				);
				$this->db->insert( 'vendors_groups', $params );
				$id = $this->db->insert_id();
				if ($id) {
					$data['success'] = true;
					$data['message'] = lang('vendor').' '.lang('group'). ' ' .lang('createmessage');
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function update_group( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'vendors', 'edit' ) ) {
			$data[ 'group' ] = $this->Vendors_Model->get_group( $id );
			if ( isset( $data[ 'group' ][ 'id' ] ) ) {
				if ( isset( $_POST ) && count( $_POST ) > 0 ) {
					$params = array(
						'name' => $this->input->post( 'name' ),
					);
					$this->Vendors_Model->update_group( $id, $params );
					$data['success'] = true;
					$data['message'] = lang('vendor').' '.lang('group'). ' ' .lang('updatemessage');
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function remove_group( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'vendors', 'delete' ) ) {
			$group = $this->Vendors_Model->get_group( $id );
			if ( isset( $group[ 'id' ] ) ) { 
				if ($this->Vendors_Model->check_group($id) == 0) {
					$this->Vendors_Model->remove_group( $id );
					$data['success'] = true;
					$data['message'] = lang('vendor').' '.lang('group'). ' ' .lang('deletemessage');
				} else {
					$data['success'] = false;
					$data['message'] = $data['message'] = lang('group').' '.lang('is_linked').' '.lang('with').' '.lang('vendor').', '.lang('so').' '.lang('cannot_delete').' '.lang('group');
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function get_vendor_groups() {
		$groups = $this->Vendors_Model->get_vendor_groups();
		$data_categories = array();
		foreach ( $groups as $group ) {
			$data_categories[] = array(
				'name' => $group[ 'name' ],
				'id' => $group[ 'id' ],
			);
		};
		echo json_encode( $data_categories );
	}

	function remove( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'vendors', 'all' ) ) {
			$vendor = $this->Vendors_Model->get_vendor_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'vendors', 'own') ) {
			$vendor = $this->Vendors_Model->get_vendor_by_privileges( $id, $this->session->usr_id );
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
		if($vendor) {
			if ( $this->Privileges_Model->check_privilege( 'vendors', 'delete' ) ) {
				if ( isset( $vendor[ 'id' ] ) ) {
					$this->Vendors_Model->delete_vendors( $id, get_number('vendors',$id,'vendor','vendor') );
					$data['success'] = true;
					$data['message'] = lang('vendor').' '.lang('deletemessage');
					echo json_encode($data);
				} else {
					show_error( 'vendor not deleted' );	
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('you_dont_have_permission');
				echo json_encode($data);
			}
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('vendors'));
		}
	}

	function get_vendor( $id ) {
		$vendor = array();
		if ( $this->Privileges_Model->check_privilege( 'vendors', 'all' ) ) {
			$vendor = $this->Vendors_Model->get_vendor_by_privileges( $id );
		} else if ($this->Privileges_Model->check_privilege( 'vendors', 'own') ) {
			$vendor = $this->Vendors_Model->get_vendor_by_privileges( $id, $this->session->usr_id );
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('vendors'));
		}
		if($vendor) {
			$this->load->model('Vendors_Model'); 
			$country = get_country($vendor['country_id']);
			$state = get_state_name('', $vendor['state']);
			$this->db->select_sum( 'total' );
			$this->db->from( 'purchases' );
			$this->db->where( '(status_id = 2 AND vendor_id = ' . $vendor[ 'id' ] . ') ' );
			$netrevenue = $this->db->get();
			$this->db->select_sum( 'total' );
			$this->db->from( 'purchases' );
			$this->db->where( '(status_id != 1 and  status_id!=4 AND vendor_id = ' . $vendor[ 'id' ] . ') ' );
			$grossrevenue = $this->db->get();
			$data_customerdetail = array(
				'id' => $vendor[ 'id' ],
				'created' => $vendor[ 'created' ],
				'staff_id' => $vendor[ 'staff_id' ],
				'name' => $vendor[ 'company' ],
				'taxoffice' => $vendor[ 'taxoffice' ],
				'taxnumber' => $vendor[ 'taxnumber' ],
				'ssn' => $vendor[ 'ssn' ],
				'executive' => $vendor[ 'executive' ],
				'address' => $vendor[ 'address' ],
				'zipcode' => $vendor[ 'zipcode' ],
				'country_id' => $vendor[ 'country_id' ],
				'state' => $vendor['state'],
				'state_name' => $state,
				'city' => $vendor[ 'city' ],
				'town' => $vendor[ 'town' ],
				'phone' => $vendor[ 'phone' ],
				'fax' => $vendor[ 'fax' ],
				'email' => $vendor[ 'email' ],
				'web' => $vendor[ 'web' ],
				'risk' => intval( $vendor[ 'risk' ] ),
				'country' => $country,
				'group_id' => $vendor['groupid'],
				'netrevenue' => $netrevenue->row()->total,
				'grossrevenue' => $grossrevenue->row()->total,
				'vendor_number' => $vendor['vendor_number'],
				'vendor_status_id' => ($vendor['vendor_status_id'] == '1') ? true : false,
			);
			echo json_encode( $data_customerdetail );	
		} else {
			$this->session->set_flashdata( 'ntf3',lang( 'you_dont_have_permission' ) );
			redirect(base_url('vendors'));
		}
	}

	function get_vendors() {
		$vendors = array();
		if ( $this->Privileges_Model->check_privilege( 'vendors', 'all' ) ) {
			$vendors = $this->Vendors_Model->get_all_vendors_by_privileges();
		} else if ( $this->Privileges_Model->check_privilege( 'vendors', 'own' ) ) {
			$vendors = $this->Vendors_Model->get_all_vendors_by_privileges($this->session->usr_id);
		}
		$data_customers = array();
		foreach ( $vendors as $vendor ) {
			$this->db->select_sum( 'total' )->from( 'purchases' )->where( '(status_id = 3 AND vendor_id = ' . $vendor[ 'id' ] . ') ' );
			$total_unpaid_invoice_amount = $this->db->get()->row()->total;
			$this->db->select_sum( 'total' )->from( 'purchases' )->where( '(status_id = 2 AND vendor_id = ' . $vendor[ 'id' ] . ') ' );
			$total_paid_invoice_amount = $this->db->get()->row()->total;
			$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(transactiontype = 0 AND vendor_id = ' . $vendor[ 'id' ] . ') ' );
			$total_paid_amount = $this->db->get()->row()->amount;
			$country = get_country($vendor['country_id']);
			$data_customers[] = array(
				'id' => $vendor[ 'id' ],
				'vendor' => $vendor[ 'id' ],
				'name' => $vendor[ 'company' ],
				'address' => $vendor[ 'address' ],
				'group_name' => $vendor['name'],
				'email' => $vendor[ 'email' ],
				'balance' => $total_unpaid_invoice_amount - $total_paid_amount + $total_paid_invoice_amount,
				'phone' => $vendor[ 'phone' ],
				'' . lang( 'filterbycountry' ) . '' => $country,
				'vendor_number' => get_number('vendors', $vendor[ 'id' ], 'vendor','vendor'),
			);
		};
		echo json_encode( $data_customers );
	}
}