<?php
defined('BASEPATH')or exit('No direct script access allowed');
class Contacts extends CIUIS_Controller
{
    public function __construct()
    {
        parent::__construct();
        $path = $this->uri->segment(1);
        if (!$this->Privileges_Model->has_privilege($path)) {
            $this->session->set_flashdata('ntf3', '' . lang('you_dont_have_permission'));
            redirect('panel/');
            die;
        }
    }

    public function index()
    {
        $data[ 'title' ] = lang('contacts');
        $data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
        $data['payment'] = $this->Settings_Model->get_payment_gateway_data();
        $this->load->view('contacts/index', $data);
    }
}