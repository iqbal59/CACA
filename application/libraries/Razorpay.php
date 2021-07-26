<?php
if ( !defined( 'BASEPATH' ) )exit( 'No direct script access allowed' );

require_once( APPPATH . 'third_party/vendor/razorpay/Razorpay.php' );

use Razorpay\Api\Api;

class Razorpay {
	public function __construct() {
		$this->CI = & get_instance();
		$this->CI->load->helper( 'url' );
		$this->CI->load->model( 'Settings_Model' );
	}

	function razorpay_success($data) {
		$payment = $this->CI->Settings_Model->payment_mode('razorpay');
		$api = new Api($payment['input_value1'], $payment['input_value2']);
		$amount = filter_var($data['amount'], FILTER_SANITIZE_NUMBER_INT);
		$payment  = $api->payment->fetch($data['razorpay_payment_id'])->capture(array('amount'=>$amount));
		if ($payment) {
			return true;
		} else {
			return false;
		}
	}
}