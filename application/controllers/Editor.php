<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Editor extends CIUIS_Controller {

	function __construct() {
		parent::__construct();
		if (!$this->session->userdata('admin')) {
			$this->session->set_flashdata( 'ntf3', '' . lang( 'you_dont_have_permission' ) );
			redirect( 'panel' );
		}
	}

	function index() {
		$data['title'] = lang('editor');
		$this->load->view('inc/header', $data);
		$this->load->view('editor/editor', $data);
	}

	function restore_default($type = null) {
		switch ($type) {
			case '1':
				$data = file_get_contents(APPPATH. 'language/'.$this->session->userdata('language').'/'.$this->session->userdata('language').'_lang_default.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '2':
				$data = file_get_contents(APPPATH. 'views/invoices/pdf_default.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '3':
				$data = file_get_contents(APPPATH. 'views/expenses/pdf_default.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '4':
				$data = file_get_contents(APPPATH. 'views/purchases/pdf_default.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '5':
				$data = file_get_contents(APPPATH. 'views/proposals/pdf_default.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '6':
				$data = file_get_contents(APPPATH. 'views/orders/pdf_default.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '7':
				$data = file_get_contents(APPPATH. 'views/deposits/pdf_default.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '8':
				$data = file_get_contents(APPPATH. 'views/projects/pdf_default.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '9':
				$data = print_r(file_get_contents(base_url('assets/json/countries_default.json')));
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '10':
				$data = print_r(file_get_contents(base_url('assets/json/states_default.json')));
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			default:
				$data = file_get_contents(APPPATH. 'language/'.$this->session->userdata('language').'/'.$this->session->userdata('language').'_lang_default.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
		}
		echo $data;
	}

	function get_data($type = null) {
		switch ($type) {
			case '1':
				$data = file_get_contents(APPPATH. 'language/'.$this->session->userdata('language').'/'.$this->session->userdata('language').'_lang.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '2':
				$data = file_get_contents(APPPATH. 'views/invoices/pdf.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '3':
				$data = file_get_contents(APPPATH. 'views/expenses/pdf.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '4':
				$data = file_get_contents(APPPATH. 'views/purchases/pdf.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '5':
				$data = file_get_contents(APPPATH. 'views/proposals/pdf_default.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '6':
				$data = file_get_contents(APPPATH. 'views/orders/pdf_default.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '7':
				$data = file_get_contents(APPPATH. 'views/deposits/pdf_default.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '8':
				$data = file_get_contents(APPPATH. 'views/projects/pdf_default.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '9':
				$data = print_r(file_get_contents(base_url('assets/json/countries.json')));
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			case '10':
				$data = print_r(file_get_contents(base_url('assets/json/states.json')));
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
			default:
				$data = file_get_contents(APPPATH. 'language/'.$this->session->userdata('language').'/'.$this->session->userdata('language').'_lang.php');
				if ($data == false) {
					$data = lang('unable_to_read');
				}
				break;
		}
		echo $data;
	}

	function save($file) {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			if ($file == '1' || $file == '2' || $file == '3' || $file == '4' || $file == '5' || $file == '6' || $file == '7' || $file == '8' || $file == '9' || $file == '10') {
				if ($file == '1') {
					$file_path = APPPATH. 'language/'.$this->session->userdata('language').'/'.$this->session->userdata('language').'_lang.php';
				}
				if ($file == '2') {
					$file_path = APPPATH. 'views/invoices/pdf.php';
				}
				if ($file == '3') {
					$file_path = APPPATH. 'views/expenses/pdf.php';
				}
				if ($file == '4') {
					$file_path = APPPATH. 'views/purchases/pdf.php';
				}
				if ($file == '5') {
					$file_path = APPPATH. 'views/proposals/pdf.php';
				}
				if ($file == '6') {
					$file_path = APPPATH. 'views/orders/pdf.php';
				}
				if ($file == '7') {
					$file_path = APPPATH. 'views/deposits/pdf.php';
				}
				if ($file == '8') {
					$file_path = APPPATH. 'views/projects/pdf.php';
				}
				if ($file == '9') {
					$file_path = base_url('assets/json/countries.json');
				}
				if ($file == '10') {
					$file_path = base_url('assets/json/states.json');
				}
				if(!is_writable($file_path)) {
					$data['message'] = lang('file_not_writable');
					$data['success'] = false;
				} else {
					$fp = fopen($file_path, 'w+');
					fwrite($fp, $this->input->post('data', FALSE));
					fclose($fp);
					$data['message'] = lang('file_data').' '.lang('updatemessage');
					$data['success'] = true;
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('select_valid_file');
			}
			echo json_encode($data);
		}
	}
}


