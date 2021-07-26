<?php
class Forms extends CI_Controller {

	public function __construct(){
        parent::__construct();

        $this->load->model('Leads_Model');
        $this->load->model('Emails_Model');
        $this->load->model('Settings_Model');
        define( 'LANG', $this->Settings_Model->get_crm_lang() );
        $this->lang->load( LANG.'_default', LANG);
        $this->lang->load( LANG, LANG );
    }

	function index(){
		header('Location: /');
	}

	function wlf($token) {
		if ($token) {
			$form = $this->Leads_Model->getFormData_by_token($token);
			if ($form) {
				if ($form['status'] == '1') {
					$data['form'] = $form;
					$data['otherData'] = array(
						'name' => lang('name'),
						'confirm_text' => lang('confirm_text_field'),
						'matching_text_field' => lang('matching_text_field'),
						'please_correct_all_errors' => lang('please_correct_all_errors'),
						'form_invalid_email' => lang('invalid_email'),
						'invalid_regex' => lang('invalid_regex'),
						'maxLength' => lang('maxLength'),
						'minLength' => lang('minLength'),
						'required' => lang('required'),
						'error_message' => lang('error_message'),
						'invalid_date' => lang('invalid_date'),
						'max' => lang('max'),
						'min' => lang('min'),
						'next' => lang('next'),
						'pattern' => lang('pattern'),
						'previous' => lang('previous'),
						'translations' => lang('translations'),
					);
					$this->load->view( 'forms/web-form', $data);
				}
			}
		}
	}

	function save_lead() {
		$token = $this->input->post('token');
		if ($token) {
			$form = $this->Leads_Model->getFormData_by_token($token);
			if ($form) {
				if ($form['duplicate'] == '0') {
					if ($this->input->post('data[lEmail]')) {
						$email = $this->input->post('data[lEmail]');
						$is_duplicate = $this->Leads_Model->check_duplicate_lead($email);
						if ($is_duplicate) {
							$return['success'] = false;
							$return['message'] = lang('duplicate_lead');
							echo json_encode($return);
						} else {
							echo $this->add_lead();
						}
					} else {
						echo $this->add_lead();
					}
				} else {
					echo $this->add_lead();
				}
			} else {
				$return['success'] = false;
				$return['message'] = lang('invalid_token');
				echo json_encode($return);
			}
		} else {
			$return['success'] = false;
			$return['message'] = lang('errormessage');
			echo json_encode($return);
		}
	}

	function add_lead() {
		$token = $this->input->post('token');
		$form = $this->Leads_Model->getFormData_by_token($token);
		$params = array();
		if ($this->input->post('data[lEmail]') == '') {
			$return['success'] = false;
			$return['message'] = lang('invalid_email');
			return json_encode($return);
		}
		if ($this->input->post('data[lTitle]')) {
			$params['title'] = $this->input->post('data[lTitle]');
		}
		if ($this->input->post('data[lDate]')) {
			$params['dateassigned'] = date('Y-m-d' ,strtotime($this->input->post('data[lDate]')));
		}
		if ($this->input->post('data[lDescription]')) {
			$params['description'] = $this->input->post('data[lDescription]');
		}
		if ($this->input->post('data[lAddress]')) {
			$params['address'] = $this->input->post('data[lAddress]');
		}
		if ($this->input->post('data[lCity]')) {
			$params['city'] = $this->input->post('data[lCity]');
		}
		if ($this->input->post('data[lState]')) {
			$params['state'] = $this->input->post('data[lState]');
		}
		if ($this->input->post('data[lCountry]')) {
			$params['country'] = $this->input->post('data[lCountry]');
		}
		if ($this->input->post('data[lWebsite]')) {
			$params['website'] = $this->input->post('data[lWebsite]');
		}
		if ($this->input->post('data[lEmail]')) {
			$params['email'] = $this->input->post('data[lEmail]');
		}
		if ($this->input->post('data[lCompany]')) {
			$params['company'] = $this->input->post('data[lCompany]');
		}
		if ($this->input->post('data[lName]')) {
			$params['name'] = $this->input->post('data[lName]');
		}
		if ($this->input->post('data[lPhone]')) {
			$params['phone'] = $this->input->post('data[lPhone]');
		}
		if ($this->input->post('data[lZip]')) {
			$params['zipCode'] = $this->input->post('data[lZip]');
		}
		$params['weblead'] = $form['id'];
		$params['type'] = 0;
		$params['assigned_id'] = $form['assigned_id'];
		$params['status'] = $form['lead_status'];
		$params['source'] = $form['lead_source'];
		$params['created'] = date('Y-m-d');
		$params['date_contacted'] = date('Y-m-d H:i:s');
		$this->db->insert('leads', $params);
		$id = $this->db->insert_id();
		if ($id) {
			$items = $this->input->post('custom');
			if ($items) {
				foreach ($items as $key => $value) {
					$paramm = array(
						'field_id' => $key,
						'data' => $value,
						'relation_type' => 'lead',
						'relation' => $id
					);
					$this->db->insert('custom_fields_data', $paramm);
				}
			}
			if ($form['notification'] == '1') {
				$template = $this->Emails_Model->get_template('lead', 'lead_assigned');
				if ($template['status'] == 1) {
					$settings = $this->Settings_Model->get_settings_ciuis();
					$lead = $this->Leads_Model->get_lead( $id );
					$lead_url = '' . base_url( 'leads/lead/' . $id . '' ) . '';
					$message_vars = array(
						'{lead_name}' => $this->input->post('data[lName]'),
						'{lead_email}' => $this->input->post('data[lEmail]'), 
						'{lead_url}' => $lead_url,
						'{lead_assigned_staff}' => $lead['leadassigned'],
						'{company_name}' => $settings['company'],
						'{company_email}' => $settings['email'],
						'{name}' => $settings['company'],
						'{email_signature}' => $settings['email'],
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);

					$param = array(
						'from_name' => $template['from_name'],
						'email' => $lead['staffemail'],
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" )
					);
					if ($lead['staffemail']) {
						$this->db->insert( 'email_queue', $param );
					}
				}

				$template = $this->Emails_Model->get_template('lead', 'lead_submitted');
				if ($template['status'] == 1) {
					$lead = $this->Leads_Model->get_lead( $id );
					if (!$this->input->post('data[name]')) {
						$params['name'] = '';
					}
					$message_vars = array(
						'{lead_name}' => $params['name'],
						'{lead_email}' => $params['email'],
						'{lead_assigned_staff}' => $lead['leadassigned'],
						'{email_signature}' => $template['from_name'],
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);

					$param = array(
						'from_name' => $template['from_name'],
						'email' => $params['email'],
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" )
					);
					if ($params['email']) {
						$this->db->insert( 'email_queue', $param );
					}
				}
			}
			$return['success'] = true;
			$return['check'] = $this->input->post('custom');
			$return['message'] = $form['success_message'];
			return json_encode($return);
		} else {
			$return['success'] = false;
			$return['message'] = lang('errormessage');
			return json_encode($return);
		}
	}
}