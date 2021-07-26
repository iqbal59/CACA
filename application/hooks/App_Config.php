<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );

class Remote_Check {
	private $CI;
	public function __construct() {
		$this->CI =& get_instance();
	}
	
	function index() {
		if (!empty($this->CI->session->userdata('remote_check'))) {
			if (!$this->CI->session->userdata('remote_check')) {
				include_once(APPPATH . 'third_party/script/app_configuration.php');
				include_once(APPPATH . 'third_party/script/app_functions.php');
				print_r(aplVerifyLicense());
				if (empty($apl_core_notifications=aplCheckSettings())) {
					if (!empty(aplGetLicenseData()) && is_array(aplGetLicenseData())) {
						$verifyRemoteCheck = aplVerifyLicense();
						if ($verifyRemoteCheck['notification_case'] != 'notification_license_ok') {
							$this->CI->session->set_userdata( 'ntf4', $verifyRemoteCheck['notification_text']);
							$this->CI->session->set_userdata( 'error', $verifyRemoteCheck['notification_text']);
							unsetSession();
						}
					} else {
						unsetSession();
					}
				} else {
					redirect(base_url('login/license'));
				}
			}
		} else {
			//$this->CI->session->set_userdata( 'ntf4', "Licence not found, Please install the licence");
			//$this->CI->session->set_userdata( 'error', "Licence not found, Please install the licence");
			unsetSession();
		}
	}
}