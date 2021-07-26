<?php
class Settings_Model extends CI_Model {
	function get_settings( $settingname ) {
		$this->db->select( '*,languages.name as language,settings.settingname as settingname ' );
		$this->db->join( 'languages', 'settings.languageid = languages.foldername', 'left' );
		return $this->db->get_where( 'settings', array( 'settingname' => $settingname ) )->row_array();
	}

	function get_settings_ciuis() {
		$this->db->select( '*,languages.name as language,settings.settingname as settingname ' );
		$this->db->join( 'languages', 'settings.languageid = languages.foldername', 'left' );
		return $this->db->get_where( 'settings', array( 'settingname' === 'ciuis' ) )->row_array();
	}
	
	function get_settings_ciuis_origin() {
		return $this->db->get_where( 'settings', array( 'settingname' === 'ciuis' ) )->row_array();
	}

	function get_cutomer_staff($id) {
		$this->db->select( 'staff.staffname, staff.id as staff_id, staff.email as staff_email' );
		$this->db->join( 'staff', 'customers.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'customers', array( 'customers.id' => $id ) )->row_array();
	}

	function get_payment_modes() {
		//$this->db->group_by('relation'); 
		return $this->db->get_where('payment_modes', array())->result_array();
	}

	function update_settings( $settingname, $params ) {
		$this->db->where( 'settingname', $settingname );
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'updatedsettings' ) . '' ),
			'staff_id' => $loggedinuserid
		) );
		$response = $this->db->update( 'settings', $params );
	}

	function update_appconfig() {
		$this->db->where('name', 'inv_prefix')->update('appconfig', array('value' => $this->input->post('inv_prefix')));
		$this->db->where('name', 'project_prefix')->update('appconfig', array('value' => $this->input->post('project_prefix')));
		$this->db->where('name', 'order_prefix')->update('appconfig', array('value' => $this->input->post('order_prefix')));
		$this->db->where('name', 'expense_prefix')->update('appconfig', array('value' => $this->input->post('expense_prefix')));
		$this->db->where('name', 'proposal_prefix')->update('appconfig', array('value' => $this->input->post('proposal_prefix')));
		$this->db->where('name', 'tax_label')->update('appconfig', array('value' => $this->input->post('tax_label')));

		$this->db->where('name', 'product_prefix')->update('appconfig', array('value' => $this->input->post('product_prefix')));
		$this->db->where('name', 'vendor_prefix')->update('appconfig', array('value' => $this->input->post('vendor_prefix')));
		$this->db->where('name', 'customer_prefix')->update('appconfig', array('value' => $this->input->post('customer_prefix')));
		$this->db->where('name', 'lead_prefix')->update('appconfig', array('value' => $this->input->post('lead_prefix')));
		$this->db->where('name', 'ticket_prefix')->update('appconfig', array('value' => $this->input->post('ticket_prefix')));
		$this->db->where('name', 'staff_prefix')->update('appconfig', array('value' => $this->input->post('staff_prefix')));
		$this->db->where('name', 'purchase_prefix')->update('appconfig', array('value' => $this->input->post('purchase_prefix')));
		$this->db->where('name', 'task_prefix')->update('appconfig', array('value' => $this->input->post('task_prefix')));
		$this->db->where('name', 'invoice_series')->update('appconfig', array('value' => $this->input->post('invoice_series')));
		$this->db->where('name', 'project_series')->update('appconfig', array('value' => $this->input->post('project_series')));
		$this->db->where('name', 'product_series')->update('appconfig', array('value' => $this->input->post('product_series')));
		$this->db->where('name', 'order_series')->update('appconfig', array('value' => $this->input->post('order_series')));
		$this->db->where('name', 'proposal_series')->update('appconfig', array('value' => $this->input->post('proposal_series')));
		$this->db->where('name', 'vendor_series')->update('appconfig', array('value' => $this->input->post('vendor_series')));
		$this->db->where('name', 'customer_series')->update('appconfig', array('value' => $this->input->post('customer_series')));
		$this->db->where('name', 'expense_series')->update('appconfig', array('value' => $this->input->post('expense_series')));
		$this->db->where('name', 'lead_series')->update('appconfig', array('value' => $this->input->post('lead_series')));
		$this->db->where('name', 'ticket_series')->update('appconfig', array('value' => $this->input->post('ticket_series')));
		$this->db->where('name', 'staff_series')->update('appconfig', array('value' => $this->input->post('staff_series')));
		$this->db->where('name', 'purchase_series')->update('appconfig', array('value' => $this->input->post('purchase_series')));
		$this->db->where('name', 'task_series')->update('appconfig', array('value' => $this->input->post('task_series')));
		$this->db->where('name', 'deposit_series')->update('appconfig', array('value' => $this->input->post('deposit_series')));
		$this->db->where('name', 'deposit_prefix')->update('appconfig', array('value' => $this->input->post('deposit_prefix')));
	}

	function is_demo() {
		$data = $this->db->get_where('settings', array())->row_array();
		return ($data['is_demo'] == '1')?TRUE:FALSE;
	}

	function get_version_detail() {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get( 'versions' )->row_array();
	}

	function db_backup( $params ) {
		$this->db->insert( 'db_backup', $params );
		return $this->db->insert_id();
	}

	function get_backup() {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get_where( 'db_backup', array( '' ) )->result_array();
	}
	
	function get_db_backup($id) {
		return $this->db->get_where( 'db_backup', array( 'id' => $id ) )->row_array();
	}

	// function get_currencies() {
	// 	return $this->db->get_where( 'currencies', array( '' ) )->result_array();
	// }
	function get_languages() {
		return $this->db->get_where( 'languages', array( '' ) )->result_array();
	}
	function get_department( $id ) {
		return $this->db->get_where( 'departments', array( 'id' => $id ) )->row_array();
	}

	function get_departments() {
		return $this->db->get_where( 'departments', array( '' ) )->result_array();
	}
	function add_department( $params ) {
		$this->db->insert( 'departments', $params );
		return $this->db->insert_id();
	}
	function update_department( $id, $params ) {
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'departments', $params );
	}
	function delete_department( $id ) {
		$response = $this->db->delete( 'departments', array( 'id' => $id ) );
	}
	function check_department($id) {
		$data = $this->db->get_where( 'staff', array( 'department_id' => $id ) )->num_rows();
		return $data;
	}
	function get_menus() {
		return $this->db->get_where( 'menu', array( 'main_menu' => '0' ) )->result_array();
		
	}
	function get_submenus( $id ) {
		$this->db->order_by('order_id', 'ASC');
		return $this->db->get_where( 'menu', array( 'main_menu' => $id ) )->result_array();
	}

	function get_crm_lang() {
        $this->db->limit(1, 0);
        $query = $this->db->get('settings');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->languageid;
        }
    }
	function default_timezone() {
        $query = $this->db->get('settings');
        $row = $query->row();
        return $row->default_timezone;
    }
	
	function two_factor_authentication() {
        $query = $this->db->get('settings');
        $row = $query->row();
        return $row->two_factor_authentication;
    }
	
//v_162 replacing database query by json file
	// function get_currency() {
    //     $this->db->limit(1, 0);
    //     $query = $this->db->get('settings');
    //     if ($query->num_rows() > 0) {
    //         $row = $query->row();
    //         $currencyid =  $row->currencyid;
	// 	}
	// 	// $this->db->limit(1, 0);
    //     // $query = $this->db->get_where( 'currencies', array( 'id' => $currencyid ));
    //     // if ($query->num_rows() > 0) {
    //     //     $row = $query->row();
    //     //     return $row->code;
    //     // }
	// }
	
//this function gets the currency id from the json file 
	function get_currency(){
		$this->db->limit(1, 0);
			$query = $this->db->get('settings');
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$currencyid =  $row->currencyid;
			}
		$jsonstring = file_get_contents( 'assets/json/currencies.json' );
		$obj = json_decode( $jsonstring, true );
		foreach($obj as $currency){
			if($currency['id'] == $currencyid){
				$currency_symbol = $currency['code'];
			}
		}
		return $currency_symbol;
	}
	

	public function load_config() {
        $this->db->limit(1, 0);
        $query = $this->db->get('settings');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        } else {
            return FALSE;
        }
    }

    function get_rebranding_data() {
		$configs = $this->db->get_where('branding', array())->result_array();
		$data = array();
		foreach ($configs as $config) {
			$data[$config['name']] = $config['value'];
		}
		return $data;
	}

    function get_payment_gateway_data() {
		$payments = $this->get_payment_modes();
		$data = array();
		foreach ($payments as $payment) {
			$data[$payment['name']] = $payment['value'];
			if ($payment['name'] == 'authorize_aim_active' || 
				$payment['name'] == 'paypal_active' || 
				$payment['name'] == 'stripe_active' || 
				$payment['name'] == 'payu_money_active' || 
				$payment['name'] == 'ccavenue_active' || 
				$payment['name'] == 'paypal_test_mode_enabled' ||
				$payment['name'] == 'payu_money_test_mode_enabled' ||
				$payment['name'] == 'ccavenue_test_mode' ||
				$payment['name'] == 'razorpay_active' ||
				$payment['name'] == 'razorpay_test_mode_enabled' ||
				$payment['name'] == 'authorize_test_mode_enabled' //||
				//$payment['name'] == 'payu_money_active' ||
				//$payment['name'] == 'payu_money_active'
				) {
					if ($payment['value'] == '1') {
						$data[$payment['name']] = TRUE;
					} else if ($payment['value'] == '0') {
						$data[$payment['name']] = FALSE;
					}
			}
			if ($payment['name'] == 'primary_bank_account') {
				if ($payment['value']) {
					$bank = $this->db->get_where('accounts', array('id' => $payment['value']))->row_array();
					if (count($bank) > 0) {
						$data['bank'] = $bank['name'];
					}
				}
			}
		}
		return $data;
	}

	function if_timer(){
		$data = $this->db->get_where( 'tasktimer', array( 'tasktimer.end' => NULL, 'tasktimer.staff_id' => $this->session->usr_id ) )->num_rows();
		if($data > 0){
			return 'true';
		}
		else{
			return 'false';
		}
	}

	function increment_series($column, $number){
		$response = $this->db->where( 'name', $column )->update( 'appconfig', array( 'value' => $number ) );
	}

	function get_pending_process() {
		$this->db->select( '*' );
		$this->db->from('pending_process');
		$this->db->order_by('process_id', 'asc');
		$this->db->limit(10);
		return $this->db->get()->result_array();
	}

	function create_process( $process, $process_relation, $process_relation_type, $process_template_name ) {
		$process_param =array(
			'process_type' => $process,
			'process_relation' => $process_relation,
			'process_relation_type' => $process_relation_type,
			'process_created' => date( "Y.m.d H:i:s" ),
			'process_template_name' => $process_template_name
			//'process_createdby' => $this->session->usr_id,
		);
		$this->db->insert('pending_process', $process_param);
	}

	function remove_pending_process( $id ) {
		$response = $this->db->delete( 'pending_process', array( 'process_id' => $id ) );
	}

	function isAdmin() {
		$id = $this->session->usr_id;
		$this->db->select('*');
		$rows = $this->db->get_where( 'staff', array( 'admin' => 1, 'id' => $id ) )->num_rows();
		if ($rows > 0) {
			return 'true';
		} else {
			return 'false';
		}
	}

	function update_colors($params) {
		$appointment = $params['appointment_color'];
		$project = $params['project_color'];
		$task = $params['task_color'];
		$this->db->where(array('name' => 'appointment_color'));
		$this->db->update('appconfig', array('value'=> $appointment));
		$this->db->where(array('name' => 'project_color'));
		$this->db->update('appconfig', array('value'=> $project));
		$this->db->where(array('name' => 'task_color'));
		$this->db->update('appconfig', array('value'=> $task));
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id . '"> ' . $this->session->staffname . '</a> ' . lang( 'updated' ).' '.lang('calendar').' '.lang('settings') ),
			'staff_id' => $this->session->usr_id,
		) );

	}

	/**********Create New Role************/
	function create_role($params) {
		$this->db->insert('roles', $params);
		$role_id = $this->db->insert_id();
		//LOG
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'added' ).' '.lang('role') ),
			'staff_id' => $loggedinuserid,
		) );
		$permissions = $this->input->post( 'permissions' );
		foreach ( $permissions as $permission ) {
			$this->db->insert( 'role_permissions', array(
				'permission_id' => $permission['id'],
				'permission_view_own' => $permission['permission_view_own'] == 'true' ? 1 : 0,
				'permission_view_all' => $permission['permission_view_all'] == 'true' ? 1 : 0,
				'permission_create' => $permission['permission_create'] == 'true' ? 1 : 0,
				'permission_edit' => $permission['permission_edit'] == 'true' ? 1 : 0,
				'permission_delete' => $permission['permission_delete'] == 'true' ? 1 : 0,
				'role_id' => $role_id,
			));
		}
	}

	/**********Update Role************/
	function update_role($params, $role_id) {
		$this->db->where('role_id', $role_id);												
		$this->db->update('roles', $params);
		//LOG
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'updated' ).' '.lang('role') ),
			'staff_id' => $loggedinuserid,
		) );
		$permissions = $this->input->post( 'permissions' );
		foreach ( $permissions as $permission ) {
			if ( isset( $permission[ 'role_permission_id' ] ) ) {
				$param = array(
					'permission_view_own' => $permission['permission_view_own'] == 'true' ? 1 : 0,
					'permission_view_all' => $permission['permission_view_all'] == 'true' ? 1 : 0,
					'permission_create' => $permission['permission_create'] == 'true' ? 1 : 0,
					'permission_edit' => $permission['permission_edit'] == 'true' ? 1 : 0,
					'permission_delete' => $permission['permission_delete'] == 'true' ? 1 : 0,
				);
				$this->db->where('role_permission_id', $permission[ 'role_permission_id' ]);
				$this->db->update( 'role_permissions', $param);
			} else if ( empty( $permission[ 'role_permission_id' ] ) ) {
				$this->db->insert( 'role_permissions', array(
					'permission_id' => $permission['id'],
					'permission_view_own' => $permission['permission_view_own'] == 'true' ? 1 : 0,
					'permission_view_all' => $permission['permission_view_all'] == 'true' ? 1 : 0,
					'permission_create' => $permission['permission_create'] == 'true' ? 1 : 0,
					'permission_edit' => $permission['permission_edit'] == 'true' ? 1 : 0,
					'permission_delete' => $permission['permission_delete'] == 'true' ? 1 : 0,
					'role_id' => $role_id,
				));
			}
		}
	}

	/**********Get All Roles************/
	function get_all_roles() {
		$this->db->select('role_id, role_name, role_type, role_updatedat');
		$this->db->order_by('roles.role_id' , 'desc');
		return $this->db->get('roles')->result_array();
	}

	function get_role($id) {
		return $this->db->get_where('roles', array('role_id' => $id))->row_array();
	}

	/**********Get The Permission of Roles************/
	function get_role_permission($permission_id, $role_id) {
		return $this->db->get_where('role_permissions', array('role_permissions.role_id' => $role_id, 'role_permissions.permission_id' => $permission_id))->row_array();
	}

	function check_role($id) {
		$data = $this->db->get_where( 'staff', array( 'role_id' => $id ) )->num_rows();
		return $data;
	}

	/**********Delete Role************/
	function delete_role($id) {
		$this->db->delete('roles', array('role_id' => $id));
		$this->db->delete('role_permissions', array('role_id' => $id));
		// LOG
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'deleted' ) . ' ' . lang('role') . '' ),
			'staff_id' => $loggedinuserid
		) );

		return true;
	}

	function payment_mode($payment) {
		return $this->db->get_where( 'payment_methods', array( 'relation' => $payment ) )->row_array();
	}
}
