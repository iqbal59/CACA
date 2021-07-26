<?php
class Login_Model extends CI_Model {

	var $details;

	function validate_user( $email, $password ) {
		$this->db->from( 'staff' );
		$this->db->where( 'email', $email );
		$this->db->where( 'password', md5( $password ) );
		$login = $this->db->get()->result();

		if ( is_array( $login ) && count( $login ) > 0 ) {
			$this->details = $login[ 0 ];
			if ($this->input->post('remember')) {
				$data['new_expiration'] = 300*24*60*60*1000;
				$this->config->set_item('sess_expiration', $data['new_expiration']);
				$this->config->set_item('sess_expire_on_close', FALSE);
				$this->session->sess_expiration = $data['new_expiration'];
			} else {
				$this->config->set_item('sess_expire_on_close', TRUE);
			}
			$this->set_session();
			return true;
		}
		return false;
	}

	function two_factor_authentication() {
		$this->set_two_factor_authentication_session();
	}

	function get_logged_in_staff_info( $loggedinuserid = 0 ) {
		$staff_tablo = $this->db->dbprefix( 'staff' );
		$sql = "SELECT $staff_tablo.id,$staff_tablo.root,$staff_tablo.language, $staff_tablo.admin,$staff_tablo.staffmember,$staff_tablo.email,$staff_tablo.staffname, $staff_tablo.staffavatar
		FROM $staff_tablo
		WHERE $staff_tablo.inactive=0 AND $staff_tablo.id=$loggedinuserid";
		return $this->db->query( $sql )->row();
	}

	function set_session() {
		$this->session->set_userdata( array(
			'usr_id' => $this->details->id,
			'staffname' => $this->details->staffname,
			'email' => $this->details->email,
			'root' => $this->details->root,
			'language' => $this->details->language,
			'admin' => $this->details->admin,
			'staffmember' => $this->details->staffmember,
			'staffavatar' => $this->details->staffavatar,
			'staff_timezone' => $this->details->timezone,
			'other' => $this->details->other,
			'LoginOK' => true
		) );
		if (empty($apl_core_notifications=aplCheckSettings())) {
			if (!empty(aplGetLicenseData()) && is_array(aplGetLicenseData())) {
				$verifyRemoteCheck = aplVerifyLicense();
				if ($verifyRemoteCheck['notification_case'] != 'notification_license_ok') {
					$this->session->set_userdata(array('remote_check' => true, 'LoginOK' => true));
				} else {
					$this->session->set_userdata(array('remote_check' => true, 'LoginOK' => true));
				}
			} else {
				$this->session->set_userdata(array('remote_check' => true, 'LoginOK' => true));
			}
		} else {
			$this->session->set_userdata(array('remote_check' => true, 'LoginOK' => true));
		}
	}

	function set_two_factor_authentication_session() {
		$this->session->set_userdata( array(
			'2FAVerify' => true
		) );
	}

	function if_admin() {
		if ( !$this->session->userdata( 'admin' ) ) {
			return 'display:none';
		}
	}

	function usr_id() {
		$loggedinuserid = $this->session->usr_id;
		return $loggedinuserid ? $loggedinuserid : false;
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
		$stafadded = $this->input->post( 'name' );
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '' . $message = sprintf( lang( 'xaddedstaff' ), $stafadded, $stafadded ) . '' ),
			'staff_id' => $staffmember,
		) );
		return $staffmember;
	}
}
