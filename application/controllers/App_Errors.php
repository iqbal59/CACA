<?php
class App_Errors extends CIUIS_Controller {

	// Function for error-logging with timestamp
	function index() {
		$this->load->view('app-errors/errors-log');
	}
}