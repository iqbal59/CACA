<?php
class Staff_Model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function get_staff( $id ) {
		$this->db->select( '*,languages.name as stafflanguage,departments.name as department, staff.id as id, staff.role_id as role_id' );
		$this->db->join( 'departments', 'staff.department_id = departments.id', 'left' );
		$this->db->join( 'languages', 'staff.language = languages.foldername', 'left' );
		$this->db->join( 'roles', 'staff.role_id = roles.role_id', 'left' );
		return $this->db->get_where( 'staff', array( 'staff.id' => $id ) )->row_array();
	}

	function validate_user_password( $id, $password ) {
		$this->db->from( 'staff' );
		$this->db->where( 'id', $id );
		$this->db->where( 'password', md5($password));
		$login = $this->db->get()->result();
		if ( is_array( $login ) && count( $login ) == 1 ) {
			return true;
		} else {
			return false;
		}
	}


	function get_all_staff() {
		$this->db->select( '*,departments.name as department, staff.id as id' );
		$this->db->join( 'departments', 'staff.department_id = departments.id', 'left' );
		return $this->db->get_where( 'staff', array( '' ) )->result_array();
	}

	function get_all_admins() {
		$this->db->select( 'email' );
		$this->db->from('staff');
		$this->db->where('role_id = 1');
		$this->db->order_by('id', 'asc');
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}

	function add_staff( $params ) {
		$this->db->insert( 'staff', $params );
		$staffmember = $this->db->insert_id();
		$appconfig = get_appconfig();
		$number = $appconfig['staff_series'] ? $appconfig['staff_series'] : $staffmember;
		$staff_number = $appconfig['staff_prefix'].$number;
		$this->db->where('id', $staffmember)->update( 'staff', array('staff_number' => $staff_number ) );
		$workplan = array(
			0 =>
			array(
				'day' => lang('monday'),
				'status' => true,
				'start' => '09:00',
				'end' => '18:00',
				'breaks' =>
				array(
					'start' => '14:30',
					'end' => '15:00',
				),
				'$$hashKey' => 'object:360',
			),
			1 =>
			array(
				'day' => lang('tuesday'),
				'status' => true,
				'start' => '09:00',
				'end' => '18:00',
				'breaks' =>
				array(
					'start' => '14:30',
					'end' => '15:00',
				),
				'$$hashKey' => 'object:361',
			),
			2 =>
			array(
				'day' => lang('wednesday'),
				'status' => true,
				'start' => '09:00',
				'end' => '18:00',
				'breaks' =>
				array(
					'start' => '14:30',
					'end' => '15:00',
				),
				'$$hashKey' => 'object:362',
			),
			3 =>
			array(
				'day' => lang('thursday'),
				'status' => true,
				'start' => '09:00',
				'end' => '18:00',
				'breaks' =>
				array(
					'start' => '14:30',
					'end' => '15:00',
				),
				'$$hashKey' => 'object:363',
			),
			4 =>
			array(
				'day' => lang('friday'),
				'status' => true,
				'start' => '09:00',
				'end' => '18:00',
				'breaks' =>
				array(
					'start' => '14:30',
					'end' => '15:00',
				),
				'$$hashKey' => 'object:364',
			),
			5 =>
			array(
				'day' => lang('saturday'),
				'status' => false,
				'start' => '',
				'end' => '',
				'breaks' =>
				array(
					'start' => '',
					'end' => '',
				),
				'$$hashKey' => 'object:365',
			),
			6 =>
			array(
				'day' => lang('sunday'),
				'status' => false,
				'start' => '',
				'end' => '',
				'breaks' =>
				array(
					'start' => '',
					'end' => '',
				),
				'$$hashKey' => 'object:366',
			),
		);
		$this->db->insert( 'staff_work_plan', array(
			'staff_id' => $staffmember,
			'work_plan' => json_encode( $workplan ),
		) );
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id . '"> ' . $this->session->staffname . '</a> ' . lang( 'added' ) . ' <a href="staff/staffmember/' . $staffmember . '">' . get_number('staff',$staffmember,'staff','staff') . '</a>.' ),
			'staff_id' => $loggedinuserid,
		) );
		return $staffmember;
	}

	function update_language($id, $language) {
		$this->db->where('id', $id)->update('staff', array('language' => $language));
	}

	function update_staff( $id, $params ) {
		$appconfig = get_appconfig();
		$staff_data = $this->get_staff($id);
		if($staff_data['staff_number'] == '') {
			$number = $appconfig['staff_series'] ? $appconfig['staff_series'] : $id;
			$staff_number = $appconfig['staff_prefix'].$number;
			$this->db->where('id', $id)->update('staff', array('staff_number' => $staff_number));
			if(($appconfig['staff_series'] != '')) {
				$staff_number = $appconfig['staff_series'];
				$staff_number = $staff_number + 1;
				$this->Settings_Model->increment_series('staff_series', $staff_number);
			}
		}
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'staff', $params );
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id . '"> ' . $this->session->staffname . '</a> ' . lang( 'updated' ) . ' <a href="staff/staffmember/' . $id . '">' . get_number('staff',$id,'staff','staff') . '</a>.' ),
			'staff_id' => $this->session->usr_id,
		) );
	}

	function delete_staff( $id, $number ) {

		$appointments = $this->db->get_where('appointments', array('staff_id' => $id))->num_rows();
		$comments = $this->db->get_where('comments', array('staff_id' => $id))->num_rows();
		$db_backup = $this->db->get_where('db_backup', array('staff_id' => $id))->num_rows();
		$deposits = $this->db->get_where('deposits', array('staff_id' => $id))->num_rows();
		$discussions = $this->db->get_where('discussions', array('staff_id' => $id))->num_rows();
		$discussion_comments = $this->db->get_where('discussion_comments', array('staff_id' => $id))->num_rows();
		$events = $this->db->get_where('events', array('staff_id' => $id))->num_rows();
		$expenses = $this->db->get_where('expenses', array('staff_id' => $id))->num_rows();
		$invoices = $this->db->get_where('invoices', array('staff_id' => $id))->num_rows();
		$leads = $this->db->get_where('leads', array('staff_id' => $id))->num_rows();
		$lead = $this->db->get_where('leads', array('assigned_id' => $id))->num_rows();
		$meetings = $this->db->get_where('meetings', array('staff_id' => $id))->num_rows();
		$orders = $this->db->get_where('orders', array('assigned' => $id))->num_rows();
		$order = $this->db->get_where('orders', array('addedfrom' => $id))->num_rows();
		$payments = $this->db->get_where('payments', array('staff_id' => $id))->num_rows();
		$projectmembers = $this->db->get_where('projectmembers', array('staff_id' => $id))->num_rows();
		$projects = $this->db->get_where('projects', array('staff_id' => $id))->num_rows();
		$proposals = $this->db->get_where('proposals', array('assigned' => $id))->num_rows();
		$proposal = $this->db->get_where('proposals', array('addedfrom' => $id))->num_rows();
		$purchases = $this->db->get_where('purchases', array('staff_id' => $id))->num_rows();
		$subtasks = $this->db->get_where('subtasks', array('staff_id' => $id))->num_rows();
		$tasks = $this->db->get_where('tasks', array('assigned' => $id))->num_rows();
		$task = $this->db->get_where('tasks', array('addedfrom' => $id))->num_rows();
		$tasktimer = $this->db->get_where('tasktimer', array('staff_id' => $id))->num_rows();
		$ticketreplies = $this->db->get_where('ticketreplies', array('staff_id' => $id))->num_rows();
		$tickets = $this->db->get_where('tickets', array('staff_id' => $id))->num_rows();
		$vendors = $this->db->get_where('vendors', array('staff_id' => $id))->num_rows();
		$vendor_sales = $this->db->get_where('vendor_sales', array('staff_id' => $id))->num_rows();
		$webleads = $this->db->get_where('webleads', array('assigned_id' => $id))->num_rows();

		if (($appointments > 0) || ($comments > 0) || ($db_backup > 0) || ($deposits > 0) || ($discussions > 0) || ($discussion_comments > 0) || ($events > 0) || ($expenses > 0) || ($invoices > 0) || ($leads > 0) || ($lead > 0) || ($meetings > 0) || ($orders > 0) || ($order > 0) || ($payments > 0) || ($projectmembers > 0) || ($projects > 0) || ($proposals > 0) || ($proposal > 0) || ($purchases > 0) || ($subtasks > 0) || ($tasks > 0) || ($task > 0) || ($tasktimer > 0) || ($ticketreplies > 0) || ($tickets > 0) || ($vendors > 0) || ($vendor_sales > 0) || ($webleads > 0) ) {
			return false;
		} else {
			$response = $this->db->delete( 'staff', array( 'id' => $id ) );
			$response = $this->db->delete( 'privileges', array( 'relation' => $id, 'relation_type' => 'staff' ) );
			$staffname = $this->session->staffname;
			$loggedinuserid = $this->session->usr_id;
			$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'deleted' ) . ' ' . $number . '' ),
			'staff_id' => $loggedinuserid
		) );
			return true;
		}
	}

	function delete_avatar( $id ) {
		$response = $this->db->where( 'id', $id )->update( 'staff', array( 'staffavatar' => 'n-img.jpg' ) );
	}

	function total_sales_by_staff( $id ) {
		$this->db->select_sum( 'total' );
		$this->db->from( 'sales' );
		$sales_total = $this->db->get_where( '', array( 'staff_id' => $id ) )->row()->total;
		if ( isset( $sales_total ) ) {
			$total = $sales_total;
		} else {
			$total = 0;
		}
		return $total;
	}

	function total_custoemer_by_staff( $id ) {
		$this->db->from( 'customers' );
		return $this->db->get_where( '', array( 'staff_id' => $id ) )->num_rows();
	}

	function total_ticket_by_staff( $id ) {
		$this->db->from( 'tickets' );
		return $this->db->get_where( '', array( 'staff_id' => $id ) )->num_rows();
	}

	function isDuplicate( $email ) {
		$this->db->get_where( 'staff', array( 'email' => $email ), 1 );
		return $this->db->affected_rows() > 0 ? TRUE : FALSE;
	}

	function get_staff_email($id) {
		return $this->db->get_where( 'staff', array( 'id' => $id ), 1 )->row_array();
	}

	function insertToken( $user_id ) {
		$token = substr( sha1( rand() ), 0, 30 );
		$date = date( 'Y-m-d' );

		$string = array(
			'token' => $token,
			'user_id' => $user_id,
			'created' => $date
		);
		$query = $this->db->insert_string( 'tokens', $string );
		$this->db->query( $query );
		return $token . $user_id;

	}

	function isTokenValid( $token ) {
		$tkn = substr( $token, 0, 30 );
		$uid = substr( $token, 30 );

		$q = $this->db->get_where( 'tokens', array(
			'tokens.token' => $tkn,
			'tokens.user_id' => $uid ), 1 );

		if ( $this->db->affected_rows() > 0 ) {
			$row = $q->row();

			$created = $row->created;
			$createdTS = strtotime( $created );
			$today = date( 'Y-m-d' );
			$todayTS = strtotime( $today );

			if ( $createdTS != $todayTS ) {
				return false;
			}

			$user_info = $this->getUserInfo( $row->user_id );
			return $user_info;

		} else {
			return false;
		}

	}

	function getUserInfo( $id ) {
		$q = $this->db->get_where( 'staff', array( 'id' => $id ), 1 );
		if ( $this->db->affected_rows() > 0 ) {
			$row = $q->row();
			return $row;
		} else {
			error_log( 'no user found getUserInfo(' . $id . ')' );
			return false;
		}
	}

	function updateUserInfo( $post ) {
		$data = array(
			'password' => $post[ 'password' ],
			'last_login' => date( 'Y-m-d h:i:s A' ),
			'inactive' => $this->inactive[ 1 ]
		);
		$this->db->where( 'id', $post[ 'user_id' ] );
		$this->db->update( 'staff', $data );
		$success = $this->db->affected_rows();

		if ( !$success ) {
			error_log( 'Unable to updateUserInfo(' . $post[ 'user_id' ] . ')' );
			return false;
		}

		$user_info = $this->getUserInfo( $post[ 'user_id' ] );
		return $user_info;
	}

	function getUserInfoByEmail( $email ) {
		$q = $this->db->get_where( 'staff', array( 'email' => $email ), 1 );
		if ( $this->db->affected_rows() > 0 ) {
			$row = $q->row();
			return $row;
		} else {
			error_log( 'no user found getUserInfo(' . $email . ')' );
			return false;
		}
	}

	function updatePassword( $post ) {
		$this->db->where( 'id', $post[ 'user_id' ] );
		$this->db->update( 'staff', array( 'password' => $post[ 'password' ] ) );
		$success = $this->db->affected_rows();

		if ( !$success ) {
			error_log( 'Unable to updatePassword(' . $post[ 'user_id' ] . ')' );
			return false;
		}
		return true;
	}

	function get_work_plan( $id ) {
		return $this->db->get_where( 'staff_work_plan', array( 'staff_id' => $id ) )->row_array();
	}

	function get_role_type($role_id) {
		return $this->db->get_where( 'roles', array( 'role_id' => $role_id ) )->row()->role_type;	
	}

	function restore_workplan($staff_id) {
		$this->db->where('staff_id', $staff_id);
		$this->db->delete('staff_work_plan');
		$workplan = array(
			0 =>
			array(
				'day' => lang('monday'),
				'status' => true,
				'start' => '09:00',
				'end' => '18:00',
				'breaks' =>
				array(
					'start' => '14:30',
					'end' => '15:00',
				),
				'$$hashKey' => 'object:360',
			),
			1 =>
			array(
				'day' => lang('tuesday'),
				'status' => true,
				'start' => '09:00',
				'end' => '18:00',
				'breaks' =>
				array(
					'start' => '14:30',
					'end' => '15:00',
				),
				'$$hashKey' => 'object:361',
			),
			2 =>
			array(
				'day' => lang('wednesday'),
				'status' => true,
				'start' => '09:00',
				'end' => '18:00',
				'breaks' =>
				array(
					'start' => '14:30',
					'end' => '15:00',
				),
				'$$hashKey' => 'object:362',
			),
			3 =>
			array(
				'day' => lang('thursday'),
				'status' => true,
				'start' => '09:00',
				'end' => '18:00',
				'breaks' =>
				array(
					'start' => '14:30',
					'end' => '15:00',
				),
				'$$hashKey' => 'object:363',
			),
			4 =>
			array(
				'day' => lang('friday'),
				'status' => true,
				'start' => '09:00',
				'end' => '18:00',
				'breaks' =>
				array(
					'start' => '14:30',
					'end' => '15:00',
				),
				'$$hashKey' => 'object:364',
			),
			5 =>
			array(
				'day' => lang('saturday'),
				'status' => false,
				'start' => '',
				'end' => '',
				'breaks' =>
				array(
					'start' => '',
					'end' => '',
				),
				'$$hashKey' => 'object:365',
			),
			6 =>
			array(
				'day' => lang('sunday'),
				'status' => false,
				'start' => '',
				'end' => '',
				'breaks' =>
				array(
					'start' => '',
					'end' => '',
				),
				'$$hashKey' => 'object:366',
			),
		);
		$this->db->insert( 'staff_work_plan', array(
			'staff_id' => $staff_id,
			'work_plan' => json_encode( $workplan ),
		) );

		return true;
	}

}