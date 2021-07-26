<?php
class Report_Model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function totalprojecttasks( $id ) { // corporate customers
		$this->db->from( 'tasks' );
		$this->db->where( '(relation = ' . $id . ' and relation_type = "project")' );
		$query = $this->db->get();
		$totalopentasks = $query->num_rows();
		return $totalopentasks;
	}

	function openprojecttasks( $id ) { // corporate customers 
		$this->db->from( 'tasks' );
		$this->db->where( '(relation = ' . $id . ' and relation_type = "project" and status_id = "1")' );
		$query = $this->db->get();
		$totalopentasks = $query->num_rows();
		return $totalopentasks;
	}

	function completeprojecttasks( $id ) { // corporate customers
		$this->db->from( 'tasks' );
		$this->db->where( '(relation = ' . $id . ' and relation_type = "project" and status_id = 4)' );
		$query = $this->db->get();
		$totalopentasks = $query->num_rows();
		return $totalopentasks;
	}

	function newreminder() {
		$this->db->from( 'reminders' )->where( 'date <= CURDATE() AND staff_id = "' . $this->session->userdata( 'usr_id' ) . '" AND isnotified != 1' );
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function expenses_percent_by_category( $id ) {
		$this->db->from( 'expenses' )->where( 'category_id', $id );
		$query = $this->db->get();
		$excat = $query->num_rows();
		//Total Expenses
		$this->db->from( 'expenses' );
		$query = $this->db->get();
		$totalexpenses = $query->num_rows();
		$percent = ( $totalexpenses > 0 ? number_format( ( $excat * 100 ) / $totalexpenses ) : 0 );;
		return $percent;
	}

	function expenses_amount_by_category( $id ) {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'expenses' );
		$this->db->where( 'category_id', $id );
		$total_value = $this->db->get()->row()->amount;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function get_account_amount( $id ) {
		$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(account_id = ' . $id . ' and transactiontype = 0)' );
		$account_incomings_sum = $this->db->get()->row()->amount;
		$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(account_id = ' . $id . ' and transactiontype = 1)' );
		$account_outgoings_sum = $this->db->get()->row()->amount;
		$account_sum = ( $account_incomings_sum - $account_outgoings_sum );
		if ( !empty( $account_sum ) ) {
			$account_total = $account_incomings_sum - $account_outgoings_sum;
		} else {
			$account_total = 0;
		}
		return $account_total;

	}

		//purchases TOTAL BY STATUS

	function pff_purchases() {
		$this->db->select_sum( 'total' );
		$this->db->from( 'purchases' );
		$this->db->where( 'status_id', 1 );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function ofv_purchases() {
		$this->db->select_sum( 'total' );
		$this->db->from( 'purchases' );
		$this->db->where( 'status_id = 2' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function oft_purchases() {
		$this->db->select_sum( 'total' );
		$this->db->from( 'purchases' );
		$this->db->where( 'status_id = 3 AND CURDATE() <= duedate' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function vgf_purchases() {
		$this->db->select_sum( 'total' );
		$this->db->from( 'purchases' );
		$this->db->where( 'CURDATE() >= duedate AND duedate != "0000.00.00" AND status_id != "4" AND status_id != "2"' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function tfa_purchases() {
		$this->db->from( 'purchases' )->where( 'status_id  != 4' );
		$query = $this->db->get();
		$tfa = $query->num_rows();
		return $tfa;
	}


	function pfs_purchases() {
		$this->db->from( 'purchases' )->where( 'status_id', 1 );
		$query = $this->db->get();
		$pfs = $query->num_rows();
		return $pfs;
	}

	function otf_purchases() {
		$this->db->from( 'purchases' )->where( 'status_id', 2 );
		$query = $this->db->get();
		$otf = $query->num_rows();
		return $otf;
	}

	function tef_purchases() {
		$this->db->from( 'purchases' )->where( 'status_id = 3 AND CURDATE() <= duedate' );
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function vdf_purchases() {
		$this->db->from( 'purchases' )->where( 'CURDATE() >= duedate AND duedate != "0000.00.00" AND status_id != "4" AND status_id != "2"' );
		$query = $this->db->get();
		$vdf = $query->num_rows();
		return $vdf;
	}
	

	function fam_purchases() {
		$this->db->select_sum( 'total' );
		$this->db->from( 'purchases' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	// ACCOUNTS
	function tht() {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'payments' );
		$this->db->where( 'transactiontype = 0' );
		$total_value = $this->db->get()->row()->amount;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function total_incomings() {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'payments' );
		$this->db->where( 'payments.transactiontype = 0 AND payments.is_transfer != 1' );
		$total_incomings = $this->db->get()->row()->amount;
		if ( !empty( $total_incomings ) ) {
			$total = $total_incomings;
		} else {
			$total = 0;
		}
		return $total;
	}

	function total_outgoings() {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'payments' );
		$this->db->where( 'payments.transactiontype = 1 AND payments.is_transfer != 1' );
		$total_outgoings = $this->db->get()->row()->amount;
		if ( !empty( $total_outgoings ) ) {
			$total = $total_outgoings;
		} else {
			$total = 0;
		}
		return $total;
	}

	// CASH FLOW
	// ONLY THIS WEEK
	function put() {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'payments' );
		$this->db->where( 'WEEK(date) = WEEK(CURRENT_DATE)' );
		$total_value = $this->db->get()->row()->amount;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function pay() {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'payments' )->where( 'WEEK(date) = WEEK(CURRENT_DATE) AND transactiontype = 0 AND is_transfer = 0' );
		$total_value = $this->db->get()->row()->amount;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}
	// ONLY THIS WEEK
	function exp() {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'payments' )->where( 'WEEK(date) = WEEK(CURRENT_DATE) AND transactiontype = 1 AND is_transfer = 0' );
		$total_value = $this->db->get()->row()->amount;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}
	// ONLY THIS WEEK
	function totalpaym() {
		$this->db->from( 'payments' )->where( 'WEEK(date) = WEEK(CURRENT_DATE)' );
		$query = $this->db->get();
		$totalpaym = $query->num_rows();
		return $totalpaym;
	}
	// ONLY THIS WEEK
	function incomings() {
		$this->db->from( 'payments' )->where( 'WEEK(date) = WEEK(CURRENT_DATE) AND transactiontype = 0 ' );
		$query = $this->db->get();
		$incomings = $query->num_rows();
		return $incomings;
	}
	// ONLY THIS WEEK
	function outgoings() {
		$this->db->from( 'payments' )->where( 'WEEK(date) = WEEK(CURRENT_DATE) AND transactiontype = 1 ' );
		$query = $this->db->get();
		$outgoings = $query->num_rows();
		return $outgoings;
	}


	// PROJECTS FUNCTIONS
	function tpz() { // Total Customer Count
		$this->db->from( 'projects' );
		$query = $this->db->get();
		$mst = $query->num_rows();
		return $mst;
	}

	function nsp() { // corporate customers
		$this->db->from( 'projects' )->where( 'status_id', 1 );
		$query = $this->db->get();
		$tks = $query->num_rows();
		return $tks;
	}

	function sep() { // corporate customers
		$this->db->from( 'projects' )->where( 'status_id', 2 );
		$query = $this->db->get();
		$tks = $query->num_rows();
		return $tks;
	}

	function pep() { // corporate customers
		$this->db->from( 'projects' )->where( 'status_id', 3 );
		$query = $this->db->get();
		$tks = $query->num_rows();
		return $tks;
	}

	function cap() { // corporate customers
		$this->db->from( 'projects' )->where( 'status_id', 4 );
		$query = $this->db->get();
		$tks = $query->num_rows();
		return $tks;
	}

	function cop() { // corporate customers
		$this->db->from( 'projects' )->where( 'status_id', 5 );
		$query = $this->db->get();
		$tks = $query->num_rows();
		return $tks;
	}


	/////////////////////////////////////////////////////////////////

	// CUSTOMER FUNCTIONS
	function mst() { // Total Customer Count
		$this->db->from( 'customers' );
		$query = $this->db->get();
		$mst = $query->num_rows();
		return $mst;
	}

	function tks() { // corporate customers
		$this->db->from( 'customers' )->where( 'type', 0 );
		$query = $this->db->get();
		$tks = $query->num_rows();
		return $tks;
	}

	function tbm() { // individual customers
		$this->db->from( 'customers' )->where( 'type', 1 );
		$query = $this->db->get();
		$tbm = $query->num_rows();
		return $tbm;
	}

	function yms() { // new customer count
		$this->db->from( 'customers' )->where( 'WEEK(created) = WEEK(CURRENT_DATE)' );
		$query = $this->db->get();
		$yms = $query->num_rows();
		return $yms;
	}

	function totaltasks() {
		$this->db->from( 'tasks' );
		$query = $this->db->get();
		$totaltasks = $query->num_rows();
		return $totaltasks;
	}

	function opentasks() {
		$this->db->from( 'tasks' )->where( 'status_id', 1 );
		$query = $this->db->get();
		$opentasks = $query->num_rows();
		return $opentasks;
	}

	function inprogresstasks() {
		$this->db->from( 'tasks' )->where( 'status_id', 2 );
		$query = $this->db->get();
		$inprogresstasks = $query->num_rows();
		return $inprogresstasks;
	}

	function waitingtasks() {
		$this->db->from( 'tasks' )->where( 'status_id', 3 );
		$query = $this->db->get();
		$waitingtasks = $query->num_rows();
		return $waitingtasks;
	}

	function completetasks() {
		$this->db->from( 'tasks' )->where( 'status_id', 4 );
		$query = $this->db->get();
		$completetasks = $query->num_rows();
		return $completetasks;
	}

	//PROPOSAL FUNCTIONS
	//AND COUNT BY PROPOSAL STATUSES
	//LIKE :'122'

	function tpc() {
		$this->db->from( 'proposals' );
		$query = $this->db->get();
		$tfa = $query->num_rows();
		return $tfa;
	}

	function dpc() {
		$this->db->from( 'proposals' )->where( 'status_id', 1 );
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function spc() {
		$this->db->from( 'proposals' )->where( 'status_id', 2 );
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function opc() {
		$this->db->from( 'proposals' )->where( 'status_id', 3 );
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function rpc() {
		$this->db->from( 'proposals' )->where( 'status_id', 4 );
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function pdc() {
		$this->db->from( 'proposals' )->where( 'status_id', 5 );
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function pac() {
		$this->db->from( 'proposals' )->where( 'status_id', 6 );
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	//TICKET FUNCTIONS
	//AND COUNT BY TICKET STATUSES
	//LIKE :'122'

	function ttc() {
		$this->db->from( 'tickets' );
		$query = $this->db->get();
		$tfa = $query->num_rows();
		return $tfa;
	}

	function twt() {
		$this->db->from( 'tickets' )->where( 'WEEK(date) = WEEK(date)' );
		$query = $this->db->get();
		$tfa = $query->num_rows();
		return $tfa;
	}

	function otc() {
		$this->db->from( 'tickets' )->where( 'status_id', 1 );
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function ipc() {
		$this->db->from( 'tickets' )->where( 'status_id', 2 );
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function atc() {
		$this->db->from( 'tickets' )->where( 'status_id', 3 );
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function ctc() {
		$this->db->from( 'tickets' )->where( 'status_id', 4 );
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	// Converted Leads Count // by staff
	function clc() {
		$this->db->from( 'leads' );
		$this->db->where( 'dateconverted != "NULL" AND assigned_id = ' . $this->session->userdata( 'usr_id' ) . '' );
		$query = $this->db->get();
		$clc = $query->num_rows();
		return $clc;
	}

	function mlc() {
		$this->db->from( 'leads' );
		$this->db->where( 'assigned_id = ' . $this->session->userdata( 'usr_id' ) . '' );
		$query = $this->db->get();
		$mlc = $query->num_rows();
		return $mlc;
	}

	// Closed Tickets Count // by staff
	function mct() {
		$this->db->from( 'tickets' );
		$this->db->where( 'status_id = 4 AND staff_id = ' . $this->session->userdata( 'usr_id' ) . '' );
		$query = $this->db->get();
		$clc = $query->num_rows();
		return $clc;
	}

	function mtt() {
		$this->db->from( 'tickets' );
		$this->db->where( 'staff_id = ' . $this->session->userdata( 'usr_id' ) . '' );
		$query = $this->db->get();
		$mlc = $query->num_rows();
		return $mlc;
	}

	function ues() {
		$monday_this_week = date( 'Y-m-d', strtotime( 'monday next week' ) );
		$sunday_this_week = date( 'Y-m-d', strtotime( 'sunday next week' ) );
		$this->db->where( '(start BETWEEN "' . $monday_this_week . '" AND "' . $sunday_this_week . '")' );
		$this->db->where( '(staff_id = ' . $this->session->userdata( 'usr_id' ) . ' OR public = 1)' );
		return $this->db->count_all_results( 'events' );
	}

	function myc() {
		$this->db->from( 'customers' );
		$this->db->where( 'staff_id = ' . $this->session->userdata( 'usr_id' ) . '' );
		$query = $this->db->get();
		$myc = $query->num_rows();
		return $myc;
	}

	// Total number of notifications

	function tbs() { // New notification counts
		return $this->db->get_where( 'notifications', array( 'markread' => 0, 'staff_id' => $this->session->userdata( 'usr_id' ) ) )->num_rows();;
	}

	function bkt() {
		$this->db->select_sum( 'total' );
		$this->db->from( 'sales' );
		$this->db->where( 'DATE(date) = CURDATE() AND status_id != "1"' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function ogt() {
		$this->db->select_sum( 'total' );
		$this->db->from( 'sales' );
		$this->db->where( 'DAY(date) = DAY(CURRENT_DATE - INTERVAL 1 DAY) AND status_id != "1"' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function bht() {
		$this->db->select_sum( 'total' );
		$this->db->from( 'sales' );
		$this->db->where( 'WEEK(date) = WEEK(CURRENT_DATE) AND status_id != "1"' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function ohc() {
		$this->db->select_sum( 'total' );
		$this->db->from( 'sales' );
		$this->db->where( 'WEEK(date) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK) AND status_id != "1"' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function mex() {
		// Monthly Expenses
		$this->db->select_sum( 'amount' );
		$this->db->from( 'expenses' );
		$this->db->where( 'MONTH(date) = MONTH(CURRENT_DATE)' );
		$total_value = $this->db->get()->row()->amount;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function pme() {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'expenses' );
		$this->db->where( 'MONTH(date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)' );
		$total_value = $this->db->get()->row()->amount;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function akt() {
		// MONTHLY EARN
		$this->db->select_sum( 'total' );
		$this->db->from( 'sales' );
		$this->db->where( 'MONTH(date) = MONTH(CURRENT_DATE) AND status_id != "1"' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function oak() {
		// LAST MOUNTH EARN
		$this->db->select_sum( 'total' );
		$this->db->from( 'sales' );
		$this->db->where( 'MONTH(date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) AND status_id != "1"' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function ycr() {
		// YEARLY EARN
		$this->db->select_sum( 'total' );
		$this->db->from( 'sales' );
		$this->db->where( 'YEAR(date) = YEAR(CURRENT_DATE) AND status_id != "1"' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function oyc() {
		// LAST YEAR EARN
		$this->db->select_sum( 'total' );
		$this->db->from( 'sales' );
		$this->db->where( 'YEAR(date) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR) AND status_id != "1"' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;

	}
	// INVOICES TOTAL BY STATUS

	function pff() {
		$this->db->select_sum( 'total' );
		$this->db->from( 'invoices' );
		$this->db->where( 'status_id', 1 );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}


	function fam() {
		$this->db->select_sum( 'total' );
		$this->db->from( 'invoices' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function ofv() {
		$this->db->select_sum( 'total' );
		$this->db->from( 'invoices' );
		$this->db->where( 'status_id = 2' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function oft() {
		$this->db->select_sum( 'total' );
		$this->db->from( 'invoices' );
		$this->db->where( 'status_id = 3 AND CURDATE() <= duedate' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function vgf() {
		$this->db->select_sum( 'total' );
		$this->db->from( 'invoices' );
		$this->db->where( 'CURDATE() >= duedate AND duedate != "0000.00.00" AND status_id != "4" AND status_id != "2"' );
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	// INVOICES COUNT BY STATUS
	function tfa() {
		$this->db->from( 'invoices' )->where( 'status_id  != 4' );
		$query = $this->db->get();
		$tfa = $query->num_rows();
		return $tfa;
	}

	function pfs() {
		$this->db->from( 'invoices' )->where( 'status_id', 1 );
		$query = $this->db->get();
		$pfs = $query->num_rows();
		return $pfs;
	}

	function otf() {
		$this->db->from( 'invoices' )->where( 'status_id', 2 );
		$query = $this->db->get();
		$otf = $query->num_rows();
		return $otf;
	}

	function tef() {
		$this->db->from( 'invoices' )->where( 'status_id = 3 AND CURDATE() <= duedate' );
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function vdf() {
		$this->db->from( 'invoices' )->where( 'CURDATE() >= duedate AND duedate != "0000.00.00" AND status_id != "4" AND status_id != "2"' );
		$query = $this->db->get();
		$vdf = $query->num_rows();
		return $vdf;
	}

	function tcl($staff_id='') {
		$this->db->where( 'dateconverted != "NULL"' );
		if($staff_id != ''){
			$this->db->where('(staff_id='.$staff_id.' OR assigned_id='.$staff_id.')');
		}
		$query = $this->db->get('leads' );
		$tcl = $query->num_rows();
		return $tcl;
	}

	function tll($staff_id='') {
		$this->db->where( 'lost = 1' );
		if($staff_id != ''){
			$this->db->where('(leads.staff_id='.$staff_id.' OR leads.assigned_id='.$staff_id.')');
		}
		$query = $this->db->get('leads');
		$tcl = $query->num_rows();
		return $tcl;
	}

	function tjl($staff_id='') {
		$this->db->where( 'junk = 1' );
		if($staff_id){
			$this->db->where('(leads.staff_id='.$staff_id.' OR leads.assigned_id='.$staff_id.')');
		}
		$query = $this->db->get('leads');
		$tcl = $query->num_rows();
		return $tcl;
	}

	function totalData($table) {
		$this->db->from( $table );
		$query = $this->db->get();
		$data = $query->num_rows();
		return $data;
	}

	function monthly_sales_graph() {
		$totalsales = array();
		$i = 0;
		for ( $mon = 1; $mon <= 12; $mon++ ) {
			$this->db->select( 'total' );
			$this->db->from( 'sales' );
			$this->db->where( 'MONTH(sales.date)', $mon );
			$sales_m = $this->db->get()->result_array();
			if ( !isset( $totalsales[ $mon ] ) ) {
				$totalsales[ $i ] = array();
			}
			if ( count( $sales_m ) > 0 ) {
				foreach ( $sales_m as $earn ) {
					$totalsales[ $i ][] = $earn[ 'total' ];
				}
			} else {
				$totalsales[ $i ][] = 0;
			}
			$totalsales[ $i ] = array_sum( $totalsales[ $i ] );
			$i++;
		}
		return json_encode( $totalsales );
	}

	function monthly_expenses() {
		$monthly_expenses = array();
		$i = 0;
		for ( $week = 1; $week <= 12; $week++ ) {
			$this->db->select( 'amount' );
			$this->db->from( 'expenses' );
			$this->db->where( 'MONTH(expenses.date)', $week );
			$sales_m = $this->db->get()->result_array();
			if ( !isset( $monthly_expenses[ $week ] ) ) {
				$monthly_expenses[ $i ] = array();
			}
			if ( count( $sales_m ) > 0 ) {
				foreach ( $sales_m as $earn ) {
					$monthly_expenses[ $i ][] = $earn[ 'amount' ];
				}
			} else {
				$monthly_expenses[ $i ][] = 0;
			}
			$monthly_expenses[ $i ] = array_sum( $monthly_expenses[ $i ] );
			$i++;
		}
		return $monthly_expenses;
	}

	function monthly_sales() {
		$monthly_sales = array();
		$i = 0;
		for ( $mon = 1; $mon <= 12; $mon++ ) {
			$this->db->select( 'total' );
			$this->db->from( 'sales' );
			$this->db->where( 'MONTH(sales.date)= "'.$mon.'" AND status_id != "1"');
			$sales_m = $this->db->get()->result_array();
			if ( !isset( $monthly_sales[ $mon ] ) ) {
				$monthly_sales[ $i ] = array();
			}
			if ( count( $sales_m ) > 0 ) {
				foreach ( $sales_m as $earn ) {
					$monthly_sales[ $i ][] = $earn[ 'total' ];
				}
			} else {
				$monthly_sales[ $i ][] = 0;
			}
			$monthly_sales[ $i ] = array_sum( $monthly_sales[ $i ] );
			$i++;
		}
		return $monthly_sales;
	}

	function weekly_sales_chart() {
		$allsales = array();
		$this->db->select( 'sales.total,sales.date' );
		$this->db->from( 'sales' );
		$this->db->where( 'CAST(sales.date as DATE) >= "' . date( 'Y-m-d', strtotime( 'monday this week' ) ) . '" AND CAST(sales.date as DATE) <= "' . date( 'Y-m-d', strtotime( 'sunday this week' ) ) . '"' );
		$allsales[] = $this->db->get()->result_array();
		$graphic = array(
			'labels' => weekdays(),
			'datasets' => array(
				array(
					'type' => 'bar',
					'backgroundColor' => '#C7CBD5',
					'hoverBackgroundColor' => '#ffe8a8',
					'hoverBorderColor' => '#f5f5f5',
					'data' => array( 0, 0, 0, 0, 0, 0, 0 )
				),
				array(
					'type' => 'line',
					'backgroundColor' => '#C7CBD5',
					'hoverBackgroundColor' => '#ffe8a8',
					'hoverBorderColor' => '#f5f5f5',
					'data' => array( 0, 0, 0, 0, 0, 0, 0 )
				),
			)
		);
		for ( $i = 0; $i < count( $allsales ); $i++ ) {
			foreach ( $allsales[ $i ] as $salesc ) {
				$salesday = date( 'l', strtotime( $salesc[ 'date' ] ) );
				$x = 0;
				foreach ( weekdays_git() as $dayc ) {
					if ( $salesday == $dayc ) {
						$graphic[ 'datasets' ][ $i ][ 'data' ][ $x ] += $salesc[ 'total' ];
					}
					$x++;
				}
			}
		}
		return $graphic;
	}

	function weekly_expense_chart(){
		$allexpense = array();
		$this->db->select( 'expenses.amount,expenses.date' );
		$this->db->from( 'expenses' );
		$this->db->where( 'CAST(expenses.date as DATE) >="' . date( 'Y-m-d', strtotime( 'monday this week' ) ) . '" AND CAST(expenses.date as DATE) <="' . date( 'Y-m-d', strtotime( 'sunday this week' ) ) . '"');
		$allexpense[] = $this->db->get()->result_array();
		$graphic = array(
			'labels' => weekdays(),
			'datasets' => array(
				array(
					'type' => 'bar',
					'backgroundColor' => '#C7CBD5',
					'hoverBackgroundColor' => '#ffe8a8',
					'hoverBorderColor' => '#f5f5f5',
					'data' => array( 0, 0, 0, 0, 0, 0, 0 )
				),
				array(
					'type' => 'line',
					'backgroundColor' => '#C7CBD5',
					'hoverBackgroundColor' => '#ffe8a8',
					'hoverBorderColor' => '#f5f5f5',
					'data' => array( 0, 0, 0, 0, 0, 0, 0 )
				),
			)
		);
		for ( $i = 0; $i < count( $allexpense ); $i++ ){
			foreach ( $allexpense[ $i ] as $expensec ) {
				$expenseday = date( '1' , strtotime( $expensec[ 'date' ] ) );
				$x = 0;
				foreach ( weekdays_git() as $dayc) {
					if ( $expenseday == $dayc ){
						$graphic[ 'datasets' ][ $i ][ 'data' ][ $x ] += $expensec[ 'amount' ];
					}
					$x++;
				}
			}
		}
		return $graphic;
	}

	function weekly_incomings() {
		$allsales = array();
		$this->db->select( 'sales.total,sales.date' );
		$this->db->from( 'sales' );
		$this->db->where( 'CAST(sales.date as DATE) >= "' . date( 'Y-m-d', strtotime( 'monday this week' ) ) . '" AND CAST(sales.date as DATE) <= "' . date( 'Y-m-d', strtotime( 'sunday this week' ) ) . '"' );
		return $this->db->get()->result_array();
	}

	function weekly_incomings_vs_outgoings() {
		$expenses = array( 0, 0, 0, 0, 0, 0, 0 );
		$payments = array( 0, 0, 0, 0, 0, 0, 0 );
		$this->db->select( 'expenses.amount,expenses.date' );
		$this->db->from( 'expenses' );
		$this->db->where( 'CAST(expenses.date as DATE) >= "' . date( 'Y-m-d', strtotime( 'monday this week' ) ) . '" AND CAST(expenses.date as DATE) <= "' . date( 'Y-m-d', strtotime( 'sunday this week' ) ) . '"' );
		$allsales[] = $this->db->get()->result_array();
		for ( $i = 0; $i < count( $allsales ); $i++ ) {
			foreach ( $allsales[ $i ] as $salesc ) {
				$salesday = date( 'l', strtotime( $salesc[ 'date' ] ) );
				$x = 0;
				foreach ( weekdays_git() as $dayc ) {
					if ( $salesday == $dayc ) {
						$expenses[ $x ] += $salesc[ 'amount' ];
					}
					$x++;
				}
			}
		}
		$this->db->select( 'payments.amount, payments.date' );
		$this->db->from( 'payments' );
		$this->db->join( 'invoices', 'invoices.id = payments.invoice_id' );
		$this->db->where( 'CAST(payments.date as DATE) >= "' . date( 'Y-m-d', strtotime( 'monday this week' ) ) . '" AND CAST(payments.date as DATE) <= "' . date( 'Y-m-d', strtotime( 'sunday this week' ) ) . '"' );
		$allpayments[] = $this->db->get()->result_array();
		for ( $i = 0; $i < count( $allpayments ); $i++ ) {
			foreach ( $allpayments[ $i ] as $salesc ) {
				$salesday = date( 'l', strtotime( $salesc[ 'date' ] ) );
				$x = 0;
				foreach ( weekdays_git() as $dayc ) {
					if ( $salesday == $dayc ) {
						$payments[ $x ] += $salesc[ 'amount' ];
					}
					$x++;
				}
			}
		}
		$data = array(
			'expenses' => $expenses,
			'payments' => $payments
		);
		return $data;
	}

	function invoice_chart_by_status() {
		$statuslar = $this->db->get_where( 'invoicestatus', array( '' ) )->result_array();
		$colors = ciuis_colors();
		$graphic = array(
			'labels' => array(),
			'datasets' => array()
		);
		$_data = array();
		$_data[ 'data' ] = array();
		$_data[ 'backgroundColor' ] = array();
		$_data[ 'hoverBackgroundColor' ] = array();
		$i = 0;
		foreach ( $statuslar as $status ) {
			$this->db->where( 'status_id', $status[ 'id' ] );
			array_push( $graphic[ 'labels' ], $status[ 'name' ] );
			array_push( $_data[ 'backgroundColor' ], $status[ 'color' ] );
			array_push( $_data[ 'hoverBackgroundColor' ], ciuis_set_color( $status[ 'color' ], -20 ) );
			array_push( $_data[ 'data' ], $this->db->count_all_results( 'invoices' ) );
			$i++;
		}
		$graphic[ 'datasets' ][] = $_data;
		$graphic[ 'datasets' ][ 0 ][ 'label' ] = lang('invoice_status');
		return $graphic;
	}

	function leads_to_win_by_leadsource() {
		$statuses = $this->db->get_where( 'leadssources', array( '' ) )->result_array();
		$colors = ciuis_colors();
		$graphic = array(
			'labels' => array(),
			'datasets' => array()
		);
		$_data = array();
		$_data[ 'data' ] = array();
		$_data[ 'backgroundColor' ] = array();
		$_data[ 'hoverBackgroundColor' ] = array();
		$i = 0;
		foreach ( $statuses as $status ) {
			$this->db->where( 'source = ' . $status[ 'id' ] . ' AND dateconverted != "NULL"' );
			array_push( $graphic[ 'labels' ], $status[ 'name' ] );
			array_push( $_data[ 'backgroundColor' ], '#777777' );
			array_push( $_data[ 'hoverBackgroundColor' ], ciuis_set_color( '#777777', -20 ) );
			array_push( $_data[ 'data' ], $this->db->count_all_results( 'leads' ) );
			$i++;
		}
		$graphic[ 'datasets' ][] = $_data;
		$graphic[ 'datasets' ][ 0 ][ 'label' ] = lang('lead_source');
		return $graphic;
	}

	function customer_annual_sales_chart( $id ) {
		$totalsales = array();
		$i = 0;
		for ( $MO = 1; $MO <= 12; $MO++ ) {
			$this->db->select( 'total' );
			$this->db->from( 'sales' );
			$this->db->where( 'MONTH(sales.date)', $MO );
			$this->db->where( 'customer_id = ' . $id . '' );
			$balances = $this->db->get()->result_array();
			if ( !isset( $totalsales[ $MO ] ) ) {
				$totalsales[ $i ] = array();
			}
			if ( count( $balances ) > 0 ) {
				foreach ( $balances as $balance ) {
					$totalsales[ $i ][] = $balance[ 'total' ];
				}
			} else {
				$totalsales[ $i ][] = 0;
			}
			$totalsales[ $i ] = array_sum( $totalsales[ $i ] );
			$i++;
		}
		$pre_totalsales = array();
		$i = 0;
		for ( $MO = 1; $MO <= 12; $MO++ ) {
			$this->db->select( 'total' );
			$this->db->from( 'sales' );
			$this->db->where( 'YEAR(date) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR) AND MONTH(date) = ' . $MO . '' );
			$this->db->where( 'customer_id = ' . $id . '' );
			$balances = $this->db->get()->result_array();
			if ( !isset( $pre_totalsales[ $MO ] ) ) {
				$pre_totalsales[ $i ] = array();
			}
			if ( count( $balances ) > 0 ) {
				foreach ( $balances as $balance ) {
					$pre_totalsales[ $i ][] = $balance[ 'total' ];
				}
			} else {
				$pre_totalsales[ $i ][] = 0;
			}
			$pre_totalsales[ $i ] = array_sum( $pre_totalsales[ $i ] );
			$i++;
		}

		$all_months = months();
		$inline_graph = array();
		foreach ( array_combine( $all_months, $totalsales ) as $month => $total ) {
			$inline_graph[] = array(
				//'month' => substr($month, 0, 3),
				'month' => mb_substr($month, 0, 3, 'UTF-8'),
				'total' => $total,
			);
		}

		$graph = array(
			'labels' => months(),
			'datasets' => array(
				array(
					'label' => lang('this_year'),
					'data' => $totalsales,
					'backgroundColor' => '#f6c1638a',
					'borderColor' => 'transparent',
					'pointBackgroundColor' => '#FFFFFF',
					'lineTension' => '0.40',
				),
				array(
					'label' => lang('lastyear'),
					'data' => $pre_totalsales,
					'backgroundColor' => '#ddd',
					'borderColor' => 'transparent',
					'pointBackgroundColor' => '#FFFFFF',
					'lineTension' => '0.40',
				)
			),
			'inline_graph' => $inline_graph,
		);
		return $graph;
	}

	function staff_sales_graph( $id ) {
		$totalsales = array();
		$i = 0;
		for ( $MO = 1; $MO <= 12; $MO++ ) {
			$this->db->select( 'total' );
			$this->db->from( 'sales' );
			$this->db->where( 'MONTH(sales.date)', $MO );
			$this->db->where( 'staff_id = ' . $id . '' );
			$balances = $this->db->get()->result_array();
			if ( !isset( $totalsales[ $MO ] ) ) {
				$totalsales[ $i ] = array();
			}
			if ( count( $balances ) > 0 ) {
				foreach ( $balances as $balance ) {
					$totalsales[ $i ][] = $balance[ 'total' ];
				}
			} else {
				$totalsales[ $i ][] = 0;
			}
			$totalsales[ $i ] = array_sum( $totalsales[ $i ] );
			$i++;
		}
		$pre_totalsales = array();
		$i = 0;
		for ( $MO = 1; $MO <= 12; $MO++ ) {
			$this->db->select( 'total' );
			$this->db->from( 'sales' );
			$this->db->where( 'YEAR(date) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR) AND MONTH(date) = ' . $MO . '' );
			$this->db->where( 'staff_id = ' . $id . '' );
			$balances = $this->db->get()->result_array();
			if ( !isset( $pre_totalsales[ $MO ] ) ) {
				$pre_totalsales[ $i ] = array();
			}
			if ( count( $balances ) > 0 ) {
				foreach ( $balances as $balance ) {
					$pre_totalsales[ $i ][] = $balance[ 'total' ];
				}
			} else {
				$pre_totalsales[ $i ][] = 0;
			}
			$pre_totalsales[ $i ] = array_sum( $pre_totalsales[ $i ] );
			$i++;
		}

		$all_months = months();
		$inline_graph = array();
		foreach ( array_combine( $all_months, $totalsales ) as $month => $total ) {
			$inline_graph[] = array(
				'month' => mb_substr($month, 0, 3, 'UTF-8'),
				'total' => $total,
			);
		}

		$graph = array(
			'labels' => months(),
			'datasets' => array(
				array(
					'label' => lang('this_year'),
					'data' => $totalsales,
					'backgroundColor' => '#f6c1638a',
					'borderColor' => 'transparent',
					'pointBackgroundColor' => '#FFFFFF',
					'lineTension' => '0.40',
				),
				array(
					'label' => lang('lastyear'),
					'data' => $pre_totalsales,
					'backgroundColor' => '#ddd',
					'borderColor' => 'transparent',
					'pointBackgroundColor' => '#FFFFFF',
					'lineTension' => '0.40',
				)
			),
			'inline_graph' => $inline_graph,
		);
		return $graph;
	}

	function leads_by_leadsource() {
		$statuses = $this->db->get_where( 'leadssources', array( '' ) )->result_array();
		$colors = ciuis_colors();
		$graphic = array(
			'labels' => array(),
			'datasets' => array()
		);
		$_data = array();
		$_data[ 'data' ] = array();
		$_data[ 'backgroundColor' ] = array();
		$_data[ 'hoverBackgroundColor' ] = array();
		$i = 0;
		foreach ( $statuses as $status ) {
			$this->db->where( 'source = ' . $status[ 'id' ] . '' );
			array_push( $graphic[ 'labels' ], $status[ 'name' ] );
			array_push( $_data[ 'backgroundColor' ], '#777777' );
			array_push( $_data[ 'hoverBackgroundColor' ], ciuis_set_color( '#777777', -20 ) );
			array_push( $_data[ 'data' ], $this->db->count_all_results( 'leads' ) );
			$i++;
		}
		$graphic[ 'datasets' ][] = $_data;
		$graphic[ 'datasets' ][ 0 ][ 'label' ] = lang('lead_source');
		return $graphic;
	}

	function leads_by_leadsource_leadpage() {
		$statuses = $this->db->get_where( 'leadssources', array( '' ) )->result_array();
		$colors = ciuis_colors();
		$graphic = array(
			'labels' => array(),
			'datasets' => array()
		);
		$_data = array();
		$_data[ 'data' ] = array();
		$_data[ 'backgroundColor' ] = array();
		$_data[ 'hoverBackgroundColor' ] = array();
		$i = 0;
		foreach ( $statuses as $status ) {
			$this->db->where( 'source = ' . $status[ 'id' ] . '' );
			array_push( $graphic[ 'labels' ], $status[ 'name' ] );
			array_push( $_data[ 'backgroundColor' ], '#26c281' );
			array_push( $_data[ 'hoverBackgroundColor' ], ciuis_set_color( '#39393b', -20 ) );
			array_push( $_data[ 'data' ], $this->db->count_all_results( 'leads' ) );
			$i++;
		}
		$graphic[ 'datasets' ][] = $_data;
		$graphic[ 'datasets' ][ 0 ][ 'label' ] = lang('lead_source');
		return $graphic;
	}

	public

	function top_selling_staff_chart() {
		$this->load->model( 'Staff_Model' );
		$staff = $this->Staff_Model->get_all_staff();
		$colors = 'rgb(235, 235, 235)';
		$graphic = array(
			'labels' => array(),
			'datasets' => array()
		);
		$_data = array();
		$_data[ 'data' ] = array();
		$_data[ 'backgroundColor' ] = array();
		$_data[ 'hoverBackgroundColor' ] = array();
		$i = 0;
		foreach ( $staff as $staffmember ) {
			$this->db->where( 'staff_id', $staffmember[ 'id' ] );
			array_push( $graphic[ 'labels' ], $staffmember[ 'staffname' ] );
			array_push( $_data[ 'backgroundColor' ], $colors );
			array_push( $_data[ 'hoverBackgroundColor' ], ciuis_set_color( $colors, -90 ) );
			array_push( $_data[ 'data' ], $this->db->count_all_results( 'sales' ) );

			$i++;
		}
		$graphic[ 'datasets' ][] = $_data;
		$graphic[ 'datasets' ][ 0 ][ 'label' ] = lang('staff');
		return $graphic;
	}

	public

	function weekly_sales_chart_report() {
		$allsales = array();
		$this->db->select( 'sales.total,sales.date' );
		$this->db->from( 'sales' );
		$this->db->where( 'CAST(sales.date as DATE) >= "' . date( 'Y-m-d', strtotime( 'monday this week' ) ) . '" AND CAST(sales.date as DATE) <= "' . date( 'Y-m-d', strtotime( 'sunday this week' ) ) . '"' );
		$allsales[] = $this->db->get()->result_array();
		$graphic = array(
			'labels' => weekdays(),
			'datasets' => array(
				array(
					'type' => 'line',
					'backgroundColor' => '#fff',
					'borderWidth' => '1',
					'hoverBackgroundColor' => '#ffe8a8',
					'hoverBorderColor' => '#f5f5f5',
					'data' => array( 0, 0, 0, 0, 0, 0, 0 )
				),
			)
		);
		for ( $i = 0; $i < count( $allsales ); $i++ ) {
			foreach ( $allsales[ $i ] as $salesc ) {
				$salesday = date( 'l', strtotime( $salesc[ 'date' ] ) );
				$x = 0;
				foreach ( weekdays_git() as $dayc ) {
					if ( $salesday == $dayc ) {
						$graphic[ 'datasets' ][ $i ][ 'data' ][ $x ] += $salesc[ 'total' ];
					}
					$x++;
				}
			}
		}
		return $graphic;
	}

	function weekly_dashboard_chart() {
		$this->db->select( 'expenses.amount,expenses.date as date' );
		$this->db->from( 'expenses' );
		$this->db->where( 'CAST(expenses.date as DATE) >= "' . date( 'Y-m-d', strtotime( 'monday this week' ) ) . '" AND CAST(expenses.date as DATE) <= "' . date( 'Y-m-d', strtotime( 'sunday this week' ) ) . '"' );
		$allexpenses = $this->db->get()->result_array();
		$data = $this->gets_sales_data();
		$graphic = array(
			'labels' => weekdays(),
			'datasets' => array(
				array(
					'type' => 'bar',
					'label' => lang('expenses'),
					'backgroundColor' => '#f52f24',
					'borderWidth' => '1',
					'hoverBackgroundColor' => '#C7CBD5',
					'hoverBorderColor' => '#f5f5f5',
					'data' => array( 0, 0, 0, 0, 0, 0, 0 )
				),
				array(
					'type' => 'bar',
					'label' => lang('sales'),
					'backgroundColor' => '#26c281',
					'borderWidth' => '1',
					'hoverBackgroundColor' =>'#C7CBD5',
					'hoverBorderColor' => '#f5f5f5',
					'data' => $data,
				),
				array(
					'type' => 'line', 
					'label' => 'Line',
					'backgroundColor' => '#ffffff', 
					'hoverBackgroundColor' => '#ffffff', 
					'hoverBorderColor' => '#ffffff', 
					'data' => array( 0, 0, 0, 0, 0, 0, 0 ) 
					), 
			)
		);
		foreach ( $allexpenses as $expensesc ) {
			$expensesday = date( 'l', strtotime( $expensesc[ 'date' ] ) );
			$x = 0;
			foreach ( weekdays_git() as $dayc ) {
				if ( $expensesday == $dayc ) {
					$graphic[ 'datasets' ][0][ 'data' ][ $x ] += $expensesc[ 'amount' ];
				}
				$x++;
			}
		}
		return $graphic;
	}

	function gets_sales_data(){
		$data = array( 0, 0, 0, 0, 0, 0, 0 );
		$this->db->select( 'sales.total,sales.date' );
		$this->db->from( 'sales' );
		$this->db->where( 'CAST(sales.date as DATE) >= "' . date( 'Y-m-d', strtotime( 'monday this week' ) ) . '" AND CAST(sales.date as DATE) <= "' . date( 'Y-m-d', strtotime( 'sunday this week' ) ) . '"' );
		$allsales = $this->db->get()->result_array();
		foreach ( $allsales as $allsale ) {
			$expensesday = date( 'l', strtotime( $allsale[ 'date' ] ) );
			$x = 0;
			foreach ( weekdays_git() as $dayc ) {
				if ( $expensesday == $dayc ) {
					$data[ $x ] += $allsale[ 'total' ];
				}
				$x++;
			}
		}
		return $data;
	}

	function weekly_sales() {
		$allsales = array();
		$this->db->select( 'sales.total,sales.date' );
		$this->db->from( 'sales' );
		$this->db->where( 'CAST(sales.date as DATE) >= "' . date( 'Y-m-d', strtotime( 'monday this week' ) ) . '" AND CAST(sales.date as DATE) <= "' . date( 'Y-m-d', strtotime( 'sunday this week' ) ) . '"' );
		$allsales[] = $this->db->get()->result_array();
		$graphic = array( 'data' => array( 0, 0, 0, 0, 0, 0, 0 ) );
		for ( $i = 0; $i < count( $allsales ); $i++ ) {
			foreach ( $allsales[ $i ] as $salesc ) {
				$salesday = date( 'l', strtotime( $salesc[ 'date' ] ) );
				$x = 0;
				foreach ( weekdays_git() as $dayc ) {
					if ( $salesday == $dayc ) {
						$graphic[ 'data' ][ $i ] += $salesc[ 'total' ];
					}
					$x++;
				}
			}
		}
		return $graphic;
	}

	public

	function customer_monthly_increase_chart( $month ) {
		$grp = $this->db->query( 'select created from customers where MONTH(created) = ' . $month . '' )->result_array();
		$month_d = array();
		$data = array();
		for ( $d = 1; $d <= 31; $d++ ) {
			$timec = mktime( 12, 0, 0, $month, $d, date( 'Y' ) );
			if ( date( 'm', $timec ) == $month ) {
				$month_d[] = _date( date( 'Y-m-d', $timec ) );
				$data[] = 0;
			}
		}
		$graphic = array(
			'labels' => $month_d,
			'datasets' => array(
				array(
					'label' => lang('customers'),
					'backgroundColor' => '#5ba768',
					'borderColor' => '#b9b9b9',
					'borderWidth' => 1,
					'tension' => false,
					'data' => $data
				)
			)
		);
		foreach ( $grp as $customer ) {
			$i = 0;
			foreach ( $graphic[ 'labels' ] as $date ) {
				if ( _date( $customer[ 'created' ] ) == $date ) {
					$graphic[ 'datasets' ][ 0 ][ 'data' ][ $i ]++;
				}
				$i++;
			}
		}
		return $graphic;
	}

	function lead_graph( $month ) {
		$grp = $this->db->query( 'select created from leads where MONTH(created) = ' . $month . '' )->result_array();
		$month_d = array();
		$data = array();
		for ( $d = 1; $d <= 31; $d++ ) {
			$timec = mktime( 12, 0, 0, $month, $d, date( 'Y' ) );
			if ( date( 'm', $timec ) == $month ) {
				$month_d[] = _date( date( 'Y-m-d', $timec ) );
				$data[] = 0;
			}
		}
		$graphic = array(
			'labels' => $month_d,
			'datasets' => array(
				array(
					'label' => lang('leads'),
					'backgroundColor' => '#e26862',
					'borderColor' => '#b9b9b9',
					'borderWidth' => 1,
					'tension' => false,
					'data' => $data
				)
			)
		);
		foreach ( $grp as $leads ) {
			$i = 0;
			foreach ( $graphic[ 'labels' ] as $date ) {
				if ( _date( $leads[ 'created' ] ) == $date ) {
					$graphic[ 'datasets' ][ 0 ][ 'data' ][ $i ]++;
				}
				$i++;
			}
		}
		return $graphic;
	}
	public

	function incomings_vs_outgoins( $currentyear = '' ) {
		$allmonths = array();
		$outgoings = array();
		$incomings = array();
		$i = 0;
		if ( !is_numeric( $currentyear ) ) {
			$currentyear = date( 'Y' );
		}
		for ( $m = 1; $m <= 12; $m++ ) {
			array_push( $allmonths, date( 'F', mktime( 0, 0, 0, $m, 1 ) ) );
			$this->db->select( 'amount' )->from( 'expenses' )->where( 'MONTH(date)', $m )->where( 'YEAR(date)', $currentyear );
			$expenses = $this->db->get()->result_array();
			if ( !isset( $outgoings[ $i ] ) ) {
				$outgoings[ $i ] = array();
			}
			if ( count( $expenses ) > 0 ) {
				foreach ( $expenses as $expense ) {
					$total = $expense[ 'amount' ];
					$outgoings[ $i ][] = $total;
				}
			} else {
				$outgoings[ $i ][] = 0;
			}
			$outgoings[ $i ] = array_sum( $outgoings[ $i ] );
			$this->db->select( 'amount' );
			$this->db->from( 'payments' );
			$this->db->join( 'invoices', 'invoices.id = payments.invoice_id' );
			$this->db->where( 'MONTH(payments.date)', $m );
			$this->db->where( 'YEAR(payments.date)', $currentyear );
			$payments = $this->db->get()->result_array();
			if ( !isset( $incomings[ $m ] ) ) {
				$incomings[ $i ] = array();
			}
			if ( count( $payments ) > 0 ) {
				foreach ( $payments as $payment ) {
					$incomings[ $i ][] = $payment[ 'amount' ];
				}
			} else {
				$incomings[ $i ][] = 0;
			}
			$incomings[ $i ] = array_sum( $incomings[ $i ] );
			$i++;
		}
		$graph = array(
			'labels' => $allmonths,
			'datasets' => array(
				array(
					'label' => lang('payments'),
					'name' => lang('payments'),
					'color' => '#26c281',
					'backgroundColor' => '#26c281',
					'borderColor' => "#26c281",
					'borderWidth' => 2,
					'tension' => false,
					'data' => $incomings
				),
				array(
					'label' => lang('expenses'),
					'name' => lang('expenses'),
					'color' => '#f52f24',
					'backgroundColor' => '#f52f24',
					'borderColor' => "#f52f24",
					'borderWidth' => 2,
					'tension' => false,
					'data' => $outgoings
				)
			)
		);

		return $graph;
	}

	function expenses_payments_graph( $currentyear ) {
		$allmonths = array();
		$outgoings = array();
		$incomings = array();
		$i = 0;
		if ( !is_numeric( $currentyear ) ) {
			$currentyear = date( 'Y' );
		}
		for ( $m = 1; $m <= 12; $m++ ) {
			array_push( $allmonths, date( 'F', mktime( 0, 0, 0, $m, 1 ) ) );
			$this->db->select( 'amount' )->from( 'expenses' )->where( 'MONTH(date)', $m )->where( 'YEAR(date)', $currentyear );
			$expenses = $this->db->get()->result_array();
			if ( !isset( $outgoings[ $i ] ) ) {
				$outgoings[ $i ] = array();
			}
			if ( count( $expenses ) > 0 ) {
				foreach ( $expenses as $expense ) {
					$total = $expense[ 'amount' ];
					$outgoings[ $i ][] = $total;
				}
			} else {
				$outgoings[ $i ][] = 0;
			}
			$outgoings[ $i ] = array_sum( $outgoings[ $i ] );
			$this->db->select( 'amount' );
			$this->db->from( 'payments' );
			$this->db->join( 'invoices', 'invoices.id = payments.invoice_id' );
			$this->db->where( 'MONTH(payments.date)', $m );
			$this->db->where( 'YEAR(payments.date)', $currentyear );
			$payments = $this->db->get()->result_array();
			if ( !isset( $incomings[ $m ] ) ) {
				$incomings[ $i ] = array();
			}
			if ( count( $payments ) > 0 ) {
				foreach ( $payments as $payment ) {
					$incomings[ $i ][] = $payment[ 'amount' ];
				}
			} else {
				$incomings[ $i ][] = 0;
			}
			$incomings[ $i ] = array_sum( $incomings[ $i ] );
			$i++;
		}
		$graph = array(
			'labels' => $allmonths,
			'datasets' => array(
				array(
					'label' => lang('payments'),
					'name' => lang('payments'),
					'color' => '#26c281',
					'backgroundColor' => '#26c281',
					'borderColor' => "#26c281",
					'borderWidth' => 2,
					'tension' => false,
					'data' => $incomings
				),
				array(
					'label' => lang('expenses'),
					'name' => lang('expenses'),
					'color' => '#f52f24',
					'backgroundColor' => '#f52f24',
					'borderColor' => "#f52f24",
					'borderWidth' => 2,
					'tension' => false,
					'data' => $outgoings
				)
			)
		);

		return $graph;
	}

	function expenses_by_categories() {
		$this->load->model( 'Expenses_Model' );
		$expensecategories = $this->Expenses_Model->get_all_expensecat();
		$colors = 'rgba(255, 188, 0, 0.83)';
		$graphic = array(
			'labels' => array(),
			'datasets' => array()
		);
		$_data = array();
		$_data[ 'data' ] = array();
		$_data[ 'backgroundColor' ] = array();
		$_data[ 'hoverBackgroundColor' ] = array();
		$i = 0;
		foreach ( $expensecategories as $expensecategory ) {
			$this->db->where( 'category_id', $expensecategory[ 'id' ] );
			array_push( $graphic[ 'labels' ], $expensecategory[ 'name' ] );
			array_push( $_data[ 'backgroundColor' ], $colors );
			array_push( $_data[ 'data' ], $this->db->count_all_results( 'expenses' ) );

			$i++;
		}
		$graphic[ 'datasets' ][] = $_data;
		$graphic[ 'datasets' ][ 0 ][ 'label' ] = lang('category');
		return $graphic;
	}

	function get_timesheet() {
		$admin = $this->isAdmin();
		$this->db->select( 'tasktimer.id, tasktimer.start, tasktimer.end, tasktimer.task_id, tasks.name, tasktimer.note, staff.staffname as staff, staff.id as staff_id, staff.staffavatar as avatar, staff.email as staff_email ' );
		$this->db->join( 'staff', 'tasktimer.staff_id = staff.id', 'left' );
		$this->db->join( 'tasks', 'tasktimer.task_id = tasks.id', 'left' );
		$this->db->order_by('tasktimer.id', 'desc');
		if (!$admin) {
			$data = $this->db->get_where( 'tasktimer', array( 'tasktimer.staff_id' => $this->session->usr_id ) )->result_array();
		} else {
			$data = $this->db->get_where( 'tasktimer', array(  ) )->result_array();
		}
		
		if (count($data) > 0 || $data) {
			return $data;
		} else {
			return false;
		}
	}

	function weekly_timesheet() {
		$allexpenses = array();
		$$this->db->select( 'timer.id, timer.start_time, timer.end_time, timer.relation_id, timer.relation, tasks.name, timer.note, staff.staffname as staff, staff.id as staff_id, staff.staffavatar as avatar ' );
		$this->db->join( 'staff', 'timer.staff_id = staff.id', 'left' );
		$this->db->join( 'tasks', 'timer.relation_id = tasks.id', 'left' );
		$this->db->order_by('timer.id', 'desc');
		$this->db->where( 'CAST(timer.start_time as TIME) >= "' . time( 'Y-m-d', strtotime( 'monday this week' ) ) . '" AND CAST(timer.start_time as TIME) <= "' . time( 'Y-m-d', strtotime( 'sunday this week' ) ) . '"' );
		$allexpenses[] = $this->db->get()->result_array();
		// $graphic = array( 'name' => 'Expenses', 'marker' => array( 'lineColor' => '#ffbc00', 'fillColor' => 'white', ), 'data' => array( 0, 0, 0, 0, 0, 0, 0 ) );
		// for ( $i = 0; $i < count( $allexpenses ); $i++ ) {
		// 	foreach ( $allexpenses[ $i ] as $expensesc ) {
		// 		$expensesday = date( 'l', strtotime( $expensesc[ 'date' ] ) );
		// 		$x = 0;
		// 		foreach ( weekdays_git() as $dayc ) {
		// 			if ( $expensesday == $dayc ) {
		// 				$graphic[ 'data' ][ $i ] += $expensesc[ 'amount' ];
		// 			}
		// 			$x++;
		// 		}
		// 	}
		// }
		return $allexpenses;
	}

	function isAdmin() {
		$id = $this->session->usr_id;
		$this->db->select( '*');
		$rows = $this->db->get_where( 'staff', array( 'admin' => 1, 'id' => $id ) )->num_rows();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
	}

	function invoices_thisweek() {
		$this->db->from( 'invoices' )->where( 'YEARWEEK(`created`, 1) = YEARWEEK(CURDATE(), 1)' );
		$query = $this->db->get();
		$invoices_thisweek = $query->num_rows();
		return $invoices_thisweek;
	}

	function expenses_thisweek() {
		$this->db->from( 'expenses' )->where( 'YEARWEEK(`created`, 1) = YEARWEEK(CURDATE(), 1)' );
		$query = $this->db->get();
		$expenses_thisweek = $query->num_rows();
		return $expenses_thisweek;
	}

	function deposits_amount_by_category( $id, $staff_id='' ) {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'deposits' );
		$this->db->where( 'category_id', $id );
		if($staff_id) {
			$this->db->where( 'staff_id', $staff_id );
		}
		$total_value = $this->db->get()->row()->amount;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function deposits_percent_by_category( $id ) {
		$this->db->from( 'deposits' )->where( 'category_id', $id );
		$query = $this->db->get();
		$excat = $query->num_rows();
		$this->db->from( 'deposits' );
		$query = $this->db->get();
		$totaldeposits = $query->num_rows();
		$percent = ( $totaldeposits > 0 ? number_format( ( $excat * 100 ) / $totaldeposits ) : 0 );;
		return $percent;
	}

	function get_all_invoices() {
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->db->select( '*,staff.staffname as staffmembername, customers.company as customercompany,customers.namesurname as individual,customers.address as customeraddress,invoicestatus.name as statusname,invoices.status_id as status_id, invoices.created as created, invoices.id as id' );
		$this->db->join( 'customers', 'invoices.customer_id = customers.id', 'left' );
		$this->db->join( 'invoicestatus', 'invoices.status_id = invoicestatus.id', 'left' );
		$this->db->join( 'staff', 'invoices.staff_id = staff.id', 'left' );
		$this->db->order_by( 'invoices.id', 'desc' );
		/*** Get All Invoices ***/
		if($period == '0') {
			return $this->db->get_where( 'invoices', array( '' ) )->result_array();
		} 
		/*** Get Current Year Invoices ***/
		else if ( $period == '1') {
			$year = date('Y');
			$this->db->from('invoices')->where('YEAR(invoices.created)', $year);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Month Invoices ***/
		else if ( $period == '2') {
			$month = date('m');
			$this->db->from('invoices')->where('MONTH(invoices.created)', $month);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Week Invoices ***/
		else if ( $period == '3') {
			$this->db->from('invoices')->where( 'WEEK(invoices.created) = WEEK(CURRENT_DATE)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Year Invoices ***/
		else if ( $period == '4') {
			$this->db->from('invoices')->where( 'YEAR(invoices.created) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Month Invoices ***/
		else if ( $period == '5') {
			$this->db->from('invoices')->where( 'MONTH(invoices.created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			return $this->db->get()->result_array();
		}
		/*** Get Last Week Invoices ***/
		else if ( $period == '6') {
			$this->db->from('invoices')->where( 'WEEK(invoices.created) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			return $this->db->get()->result_array();
		} 
		/*** Get Custom Invoices ***/
		else if ( $period == '7') {
			$this->db->where(array('invoices.created>=' => $from, 'invoices.created<=' => $to));
			return $this->db->get('invoices')->result_array();
		}
	}

	function get_all_customers() {
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->db->select( '*, customers.id as id, customergroups.id as groupid ' );
		$this->db->join('customergroups','customers.groupid = customergroups.id','left');
		$this->db->order_by( 'customers.id', 'desc' );
		/*** Get All Customers ***/
		if($period == '0') {
			return $this->db->get_where( 'customers', array( '' ) )->result_array();
		} 
		/*** Get Current Year Customers ***/
		else if ( $period == '1') {
			$year = date('Y');
			$this->db->from('customers')->where('YEAR(customers.created)', $year);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Month Customers ***/
		else if ( $period == '2') {
			$month = date('m');
			$this->db->from('customers')->where('MONTH(customers.created)', $month);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Week Customers ***/
		else if ( $period == '3') {
			$this->db->from('customers')->where( 'WEEK(customers.created) = WEEK(CURRENT_DATE)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Year Customers ***/
		else if ( $period == '4') {
			$this->db->from('customers')->where( 'YEAR(customers.created) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Month Customers ***/
		else if ( $period == '5') {
			$this->db->from('customers')->where( 'MONTH(customers.created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			return $this->db->get()->result_array();
		}
		/*** Get Last Week Customers ***/
		else if ( $period == '6') {
			$this->db->from('customers')->where( 'WEEK(customers.created) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			return $this->db->get()->result_array();
		}
		/*** Get Custom Customers ***/
		else if ( $period == '7') {
			$this->db->where(array('customers.created>=' => $from, 'customers.created<=' => $to));
			return $this->db->get('customers')->result_array();
		} 
	}

	function get_all_expenses() {
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->db->select( '*,customers.company as company, namesurname, customers.id as customerid ,customers.type as type, expensecat.name as category,staff.staffname as staff, expenses.id as id, staff.id as staffid,  accounts.name as payment_account' );
		$this->db->join( 'customers', 'expenses.customer_id = customers.id', 'left' );
		$this->db->join( 'expensecat', 'expenses.category_id = expensecat.id', 'left' );
		$this->db->join( 'staff', 'expenses.staff_id = staff.id', 'left' );
		$this->db->join( 'accounts', 'expenses.account_id = accounts.id', 'left' );
		$this->db->order_by( 'expenses.id', 'desc' );
		
		/*** Get All Expenses ***/
		if($period == '0') {
			return $this->db->get( 'expenses' )->result_array();
		} 
		/*** Get Current Year Expenses ***/
		else if ( $period == '1') {
			$year = date('Y');
			$this->db->from('expenses')->where('YEAR(expenses.created)', $year);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Month Expenses ***/
		else if ( $period == '2') {
			$month = date('m');
			$this->db->from('expenses')->where('MONTH(expenses.created)', $month);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Week Expenses ***/
		else if ( $period == '3') {
			$this->db->from('expenses')->where( 'WEEK(expenses.created) = WEEK(CURRENT_DATE)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Year Expenses ***/
		else if ( $period == '4') {
			$this->db->from('expenses')->where( 'YEAR(expenses.created) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Month Expenses ***/
		else if ( $period == '5') {
			$this->db->from('expenses')->where( 'MONTH(expenses.created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			return $this->db->get()->result_array();
		}
		/*** Get Last Week Expenses ***/
		else if ( $period == '6') {
			$this->db->from('expenses')->where( 'WEEK(expenses.created) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			return $this->db->get()->result_array();
		}
		/*** Get Custom Expenses ***/
		else if ( $period == '7') {
			$this->db->where(array('expenses.created >=' => $from, 'expenses.created <=' => $to));
			return $this->db->get('expenses')->result_array();
		} 
	}

	function get_all_proposals() {
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->db->select( '*,staff.staffname as staffmembername,proposals.id as id, staff.id as staffid, staff_number' );
		$this->db->join( 'staff', 'proposals.assigned = staff.id', 'left' );
		$this->db->order_by( 'proposals.id', 'desc' );
		
		/*** Get All Proposals ***/
		if($period == '0') {
			return $this->db->get( 'proposals' )->result_array();
		} 
		/*** Get Current Year Proposals ***/
		else if ( $period == '1') {
			$year = date('Y');
			$this->db->from('proposals')->where('YEAR(proposals.created)', $year);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Month Proposals ***/
		else if ( $period == '2') {
			$month = date('m');
			$this->db->from('proposals')->where('MONTH(proposals.created)', $month);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Week Proposals ***/
		else if ( $period == '3') {
			$this->db->from('proposals')->where( 'WEEK(proposals.created) = WEEK(CURRENT_DATE)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Year Proposals ***/
		else if ( $period == '4') {
			$this->db->from('proposals')->where( 'YEAR(proposals.created) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Month Proposals ***/
		else if ( $period == '5') {
			$this->db->from('proposals')->where( 'MONTH(proposals.created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			return $this->db->get()->result_array();
		}
		/*** Get Last Week Proposals ***/
		else if ( $period == '6') {
			$this->db->from('proposals')->where( 'WEEK(proposals.created) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			return $this->db->get()->result_array();
		}
		/*** Get Custom Proposals ***/
		else if ( $period == '7') {
			$this->db->where(array('proposals.created>=' => $from, 'proposals.created<=' => $to));
			return $this->db->get('proposals')->result_array();
		}
	}

	function get_all_deposits() { 
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->db->select( '*,customers.company as company, namesurname, customers.id as customerid, depositcat.name as category, deposits.id as id, deposits.created as created, staffname, staff.id as staffid, accounts.name as payment_account' ); 
		$this->db->join( 'customers', 'deposits.customer_id = customers.id', 'left' ); 
		$this->db->join( 'depositcat', 'deposits.category_id = depositcat.id', 'left' ); 
		$this->db->join( 'staff', 'deposits.staff_id = staff.id', 'left' ); 
		$this->db->join( 'accounts', 'deposits.account_id = accounts.id', 'left' );
		$this->db->order_by( 'deposits.id', 'desc' ); 

		/*** Get All Deposits ***/
		if($period == '0') {
			return $this->db->get( 'deposits' )->result_array(); 
		} 
		/*** Get Current Year Deposits ***/
		else if ( $period == '1') {
			$year = date('Y');
			$this->db->from('deposits')->where('YEAR(deposits.created)', $year);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Month Deposits ***/
		else if ( $period == '2') {
			$month = date('m');
			$this->db->from('deposits')->where('MONTH(deposits.created)', $month);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Week Deposits ***/
		else if ( $period == '3') {
			$this->db->from('deposits')->where( 'WEEK(deposits.created) = WEEK(CURRENT_DATE)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Year Deposits ***/
		else if ( $period == '4') {
			$this->db->from('deposits')->where( 'YEAR(deposits.created) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Month Deposits ***/
		else if ( $period == '5') {
			$this->db->from('deposits')->where( 'MONTH(deposits.created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			return $this->db->get()->result_array();
		}
		/*** Get Last Week Deposits ***/
		else if ( $period == '6') {
			$this->db->from('deposits')->where( 'WEEK(deposits.created) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			return $this->db->get()->result_array();
		}
		/*** Get Custom Deposits ***/
		else if ( $period == '7') {
			$this->db->where(array('deposits.created >=' => $from, 'deposits.created <=' => $to));
			return $this->db->get('deposits')->result_array();
		}
	} 

	function get_all_orders() {
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->db->select( '*,staff.staffname as staffmembername,staff.id as staffid,orders.id as id,customers.id customerid, customer_number, customers.company, namesurname, leads.id as leadid, lead_number, leads.name as leadname' );
		$this->db->join( 'staff', 'orders.assigned = staff.id', 'left' );
		$this->db->join( 'customers', 'orders.relation = customers.id', 'left' );
		$this->db->join( 'leads', 'orders.relation = leads.id', 'left' );
		$this->db->order_by( 'orders.id', 'desc' );
		/*** Get All Orders ***/
		if($period == '0') {
			return $this->db->get( 'orders' )->result_array();
		} 
		/*** Get Current Year Orders ***/
		else if ( $period == '1') {
			$year = date('Y');
			$this->db->from('orders')->where('YEAR(orders.created)', $year);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Month Orders ***/
		else if ( $period == '2') {
			$month = date('m');
			$this->db->from('orders')->where('MONTH(orders.created)', $month);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Week Orders ***/
		else if ( $period == '3') {
			$this->db->from('orders')->where( 'WEEK(orders.created) = WEEK(CURRENT_DATE)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Year Orders ***/
		else if ( $period == '4') {
			$this->db->from('orders')->where( 'YEAR(orders.created) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Month Orders ***/
		else if ( $period == '5') {
			$this->db->from('orders')->where( 'MONTH(orders.created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			return $this->db->get()->result_array();
		}
		/*** Get Last Week Orders ***/
		else if ( $period == '6') {
			$this->db->from('orders')->where( 'WEEK(orders.created) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			return $this->db->get()->result_array();
		}
		/*** Get Custom Orders ***/
		else if ( $period == '7') {
			$this->db->where(array('orders.created>=' => $from, 'orders.created<=' => $to));
			return $this->db->get('orders')->result_array();
		}
	}

	function get_all_vendors() {
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->db->select( '*, vendors.id as id ' );
		$this->db->join('vendors_groups','vendors.groupid = vendors_groups.id','left');
		$this->db->order_by( 'vendors.id', 'desc' );

		/*** Get All Vendors ***/
		if($period == '0') {
			return $this->db->get_where( 'vendors', array( '' ) )->result_array();
		} 
		/*** Get Current Year Vendors ***/
		else if ( $period == '1') {
			$year = date('Y');
			$this->db->from('vendors')->where('YEAR(vendors.created)', $year);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Month Vendors ***/
		else if ( $period == '2') {
			$month = date('m');
			$this->db->from('vendors')->where('MONTH(vendors.created)', $month);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Week Vendors ***/
		else if ( $period == '3') {
			$this->db->from('vendors')->where( 'WEEK(vendors.created) = WEEK(CURRENT_DATE)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Year Vendors ***/
		else if ( $period == '4') {
			$this->db->from('vendors')->where( 'YEAR(vendors.created) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Month Vendors ***/
		else if ( $period == '5') {
			$this->db->from('vendors')->where( 'MONTH(vendors.created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			return $this->db->get()->result_array();
		}
		/*** Get Last Week Vendors ***/
		else if ( $period == '6') {
			$this->db->from('vendors')->where( 'WEEK(vendors.created) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			return $this->db->get()->result_array();
		}
		/*** Get Custom Vendors ***/
		else if ( $period == '7') {
			$this->db->where(array('vendors.created>=' => $from, 'vendors.created<=' => $to));
			return $this->db->get('vendors')->result_array();
		}
	}

	function get_all_purchases() {
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->db->select( '*,staff.staffname as staffmembername,vendors.company as vendorcompany,vendors.id as vendorid, vendor_number, purchases.status_id as status_id, purchases.created as created, purchases.id as id' );
		$this->db->join( 'vendors', 'purchases.vendor_id = vendors.id', 'left' );
		$this->db->join( 'staff', 'purchases.staff_id = staff.id', 'left' );
		$this->db->order_by( 'purchases.id', 'desc' );
		/*** Get All Purchases ***/
		if($period == '0') {
			return $this->db->get_where( 'purchases', array( '' ) )->result_array();
		} 
		/*** Get Current Year Purchases ***/
		else if ( $period == '1') {
			$year = date('Y');
			$this->db->from('purchases')->where('YEAR(purchases.created)', $year);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Month Purchases ***/
		else if ( $period == '2') {
			$month = date('m');
			$this->db->from('purchases')->where('MONTH(purchases.created)', $month);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Week Purchases ***/
		else if ( $period == '3') {
			$this->db->from('purchases')->where( 'WEEK(purchases.created) = WEEK(CURRENT_DATE)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Year Purchases ***/
		else if ( $period == '4') {
			$this->db->from('purchases')->where( 'YEAR(purchases.created) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Month Purchases ***/
		else if ( $period == '5') {
			$this->db->from('purchases')->where( 'MONTH(purchases.created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			return $this->db->get()->result_array();
		}
		/*** Get Last Week Purchases ***/
		else if ( $period == '6') {
			$this->db->from('purchases')->where( 'WEEK(purchases.created) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			return $this->db->get()->result_array();
		}
		/*** Get Custom Purchases ***/
		else if ( $period == '7') {
			$this->db->where(array('purchases.created>=' => $from, 'purchases.created<=' => $to));
			return $this->db->get('purchases')->result_array();
		}
	}

	function get_all_contacts() {
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->db->select('contacts.id as contactid,  name, surname, contacts.email as email, contacts.phone as phone, mobile, company, namesurname, customers.id customerid, customer_number, created');
		$this->db->join('customers', 'contacts.customer_id = customers.id', 'left');
		/*** Get All Contacts ***/
		if($period == '0') {
			return $this->db->get( 'contacts' )->result_array();
		} 
		/*** Get Current Year Contacts ***/
		else if ( $period == '1') {
			$year = date('Y');
			$this->db->from('contacts')->where('YEAR(created)', $year);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Month Contacts ***/
		else if ( $period == '2') {
			$month = date('m');
			$this->db->from('contacts')->where('MONTH(created)', $month);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Week Contacts ***/
		else if ( $period == '3') {
			$this->db->from('contacts')->where( 'WEEK(created) = WEEK(CURRENT_DATE)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Year Contacts ***/
		else if ( $period == '4') {
			$this->db->from('contacts')->where( 'YEAR(created) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Month Contacts ***/
		else if ( $period == '5') {
			$this->db->from('contacts')->where( 'MONTH(created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			return $this->db->get()->result_array();
		}
		/*** Get Last Week Contacts ***/
		else if ( $period == '6') {
			$this->db->from('contacts')->where( 'WEEK(created) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			return $this->db->get()->result_array();
		}
		/*** Get Custom Contacts ***/
		else if ( $period == '7') {
			$this->db->where(array('created>=' => $from, 'created<=' => $to));
			return $this->db->get('contacts')->result_array();
		}
	}

	function get_all_tickets() {
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->db->select( '*,customers.type as type, customers.email as customeremail, customers.company as company,customers.namesurname as namesurname,departments.name as department,staff.staffname as staffmembername,staff.email as staffemail,contacts.name as contactname,contacts.surname as contactsurname,tickets.staff_id as stid,tickets.status_id as status_id, tickets.id as id' );
		$this->db->join( 'contacts', 'tickets.contact_id = contacts.id', 'left' );
		$this->db->join( 'customers', 'contacts.customer_id = customers.id', 'left' );
		$this->db->join( 'departments', 'tickets.department_id = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staff_id = staff.id', 'left' );
		/*** Get All Tickets ***/
		if($period == '0') {
			return $this->db->get_where( 'tickets' )->result_array();
		} 
		/*** Get Current Year Tickets ***/
		else if ( $period == '1') {
			$year = date('Y');
			$this->db->from('tickets')->where('YEAR(tickets.date)', $year);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Month Tickets ***/
		else if ( $period == '2') {
			$month = date('m');
			$this->db->from('tickets')->where('MONTH(tickets.date)', $month);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Week Tickets ***/
		else if ( $period == '3') {
			$this->db->from('tickets')->where( 'WEEK(tickets.date) = WEEK(CURRENT_DATE)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Year Tickets ***/
		else if ( $period == '4') {
			$this->db->from('tickets')->where( 'YEAR(tickets.date) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Month Tickets ***/
		else if ( $period == '5') {
			$this->db->from('tickets')->where( 'MONTH(tickets.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			return $this->db->get()->result_array();
		}
		/*** Get Last Week Tickets ***/
		else if ( $period == '6') {
			$this->db->from('tickets')->where( 'WEEK(tickets.date) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			return $this->db->get()->result_array();
		}
		/*** Get Custom Tickets ***/
		else if ( $period == '7') {
			$this->db->where(array('tickets.date>=' => $from, 'tickets.date<=' => $to));
			return $this->db->get('tickets')->result_array();
		}
	}

	function get_all_tasks() {
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->db->select( '*,staffname,tasks.name as taskname,tasks.id as id, projects.id as projectid, project_number, projects.name as projectname' );
		$this->db->join('staff', 'tasks.assigned = staff.id', 'left' );
		$this->db->join('projects' ,'tasks.relation = projects.id', 'left');
		/*** Get All Tasks ***/
		if($period == '0') {
			return $this->db->get_where( 'tasks' )->result_array();
		} 
		/*** Get Current Year Tasks ***/
		else if ( $period == '1') {
			$year = date('Y');
			$this->db->from('tasks')->where('YEAR(tasks.created)', $year);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Month Tasks ***/
		else if ( $period == '2') {
			$month = date('m');
			$this->db->from('tasks')->where('MONTH(tasks.created)', $month);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Week Tasks ***/
		else if ( $period == '3') {
			$this->db->from('tasks')->where( 'WEEK(tasks.created) = WEEK(CURRENT_DATE)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Year Tasks ***/
		else if ( $period == '4') {
			$this->db->from('tasks')->where( 'YEAR(tasks.created) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Month Tasks ***/
		else if ( $period == '5') {
			$this->db->from('tasks')->where( 'MONTH(tasks.created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			return $this->db->get()->result_array();
		}
		/*** Get Last Week Tasks ***/
		else if ( $period == '6') {
			$this->db->from('tasks')->where( 'WEEK(tasks.created) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			return $this->db->get()->result_array();
		}
		/*** Get Custom Tasks ***/
		else if ( $period == '7') {
			$this->db->where(array('tasks.created>=' => $from, 'tasks.created<=' => $to));
			return $this->db->get('tasks')->result_array();
		}
	}

	function get_all_leads() {
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->db->select( '*,leadsstatus.name as statusname,staff.staffname as leadassigned,leadssources.name as sourcename,leads.name as leadname,leads.phone as leadphone,leads.id as id, leads.email as email' );
		$this->db->join( 'leadsstatus', 'leads.status = leadsstatus.id', 'left' );
		$this->db->join( 'leadssources', 'leads.source = leadssources.id', 'left' );
		$this->db->join( 'staff', 'leads.assigned_id = staff.id', 'left' );
		$this->db->order_by( 'leads.id', 'desc' );
		/*** Get All Leads ***/
		if($period == '0') {
			return $this->db->get( 'leads' )->result_array();
		} 
		/*** Get Current Year Leads ***/
		else if ( $period == '1') {
			$year = date('Y');
			$this->db->from('leads')->where('YEAR(leads.created)', $year);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Month Leads ***/
		else if ( $period == '2') {
			$month = date('m');
			$this->db->from('leads')->where('MONTH(leads.created)', $month);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Week Leads ***/
		else if ( $period == '3') {
			$this->db->from('leads')->where( 'WEEK(leads.created) = WEEK(CURRENT_DATE)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Year Leads ***/
		else if ( $period == '4') {
			$this->db->from('leads')->where( 'YEAR(leads.created) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Month Leads ***/
		else if ( $period == '5') {
			$this->db->from('leads')->where( 'MONTH(leads.created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			return $this->db->get()->result_array();
		}
		/*** Get Last Week Leads ***/
		else if ( $period == '6') {
			$this->db->from('leads')->where( 'WEEK(leads.created) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			return $this->db->get()->result_array();
		}
		/*** Get Custom Leads ***/
		else if ( $period == '7') {
			$this->db->where(array('leads.created>=' => $from, 'leads.created<=' => $to));
			return $this->db->get('leads')->result_array();
		}
	}

	function get_all_products() { 
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->db->select('productcategories.name, productcategories.id as categoryid, products.id as id, products.code, products.productname, products.description, products.purchase_price, products.sale_price, products.stock, products.vat, products.status_id, products.productimage');
		$this->db->join( 'productcategories', 'products.categoryid = productcategories.id', 'left' );
		$this->db->order_by( 'products.id', 'desc' );
		/*** Get All Products ***/
		if($period == '0') {
			return $this->db->get_where( 'products', array( '' ) )->result_array();
		} 
		/*** Get Current Year Products ***/
		else if ( $period == '1') {
			$year = date('Y');
			$this->db->from('products')->where('YEAR(products.createdat)', $year);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Month Products ***/
		else if ( $period == '2') {
			$month = date('m');
			$this->db->from('products')->where('MONTH(products.createdat)', $month);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Week Products ***/
		else if ( $period == '3') {
			$this->db->from('products')->where( 'WEEK(products.createdat) = WEEK(CURRENT_DATE)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Year Products ***/
		else if ( $period == '4') {
			$this->db->from('products')->where( 'YEAR(products.createdat) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Month Products ***/
		else if ( $period == '5') {
			$this->db->from('products')->where( 'MONTH(products.createdat) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			return $this->db->get()->result_array();
		}
		/*** Get Last Week Products ***/
		else if ( $period == '6') {
			$this->db->from('products')->where( 'WEEK(products.createdat) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			return $this->db->get()->result_array();
		}
		/*** Get Custom Products ***/
		else if ( $period == '7') {
			$this->db->where(array('products.createdat>=' => $from, 'products.createdat<=' => $to));
			return $this->db->get('products')->result_array();
		}
	}

	function get_all_staff() {
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->db->select( '*,departments.name as department, staff.id as id' );
		$this->db->join( 'departments', 'staff.department_id = departments.id', 'left' );
		/*** Get All Staff ***/
		if($period == '0') {
			return $this->db->get_where( 'staff', array( '' ) )->result_array();
		} 
		/*** Get Current Year Staff ***/
		else if ( $period == '1') {
			$year = date('Y');
			$this->db->from('staff')->where('YEAR(staff.createdAt)', $year);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Month Staff ***/
		else if ( $period == '2') {
			$month = date('m');
			$this->db->from('staff')->where('MONTH(staff.createdAt)', $month);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Week Staff ***/
		else if ( $period == '3') {
			$this->db->from('staff')->where( 'WEEK(staff.createdAt) = WEEK(CURRENT_DATE)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Year Staff ***/
		else if ( $period == '4') {
			$this->db->from('staff')->where( 'YEAR(staff.createdAt) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Month Staff ***/
		else if ( $period == '5') {
			$this->db->from('staff')->where( 'MONTH(staff.createdAt) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			return $this->db->get()->result_array();
		}
		/*** Get Last Week Staff ***/
		else if ( $period == '6') {
			$this->db->from('staff')->where( 'WEEK(staff.createdAt) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			return $this->db->get()->result_array();
		}
		/*** Get Custom Staff ***/
		else if ( $period == '7') {
			$this->db->where(array('staff.createdAt>=' => $from, 'staff.createdAt<=' => $to));
			return $this->db->get('staff')->result_array();
		}
	}

	function get_all_projects() {
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->db->select( '*,customers.company as customercompany,customers.namesurname as individual,customers.address as customeraddress, customers.id customerid, projects.status_id as status, projects.id as id, projects.staff_id as staff_id ' );
		$this->db->join( 'customers', 'projects.customer_id = customers.id', 'left' );
		$this->db->order_by( 'projects.id', 'desc' );
		/*** Get All Projects ***/
		if($period == '0') {
			return $this->db->get( 'projects' )->result_array();
		} 
		/*** Get Current Year Projects ***/
		else if ( $period == '1') {
			$year = date('Y');
			$this->db->from('projects')->where('YEAR(projects.created)', $year);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Month Projects ***/
		else if ( $period == '2') {
			$month = date('m');
			$this->db->from('projects')->where('MONTH(projects.created)', $month);
			return $this->db->get()->result_array();
		} 
		/*** Get Current Week Projects ***/
		else if ( $period == '3') {
			$this->db->from('projects')->where( 'WEEK(projects.created) = WEEK(CURRENT_DATE)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Year Projects ***/
		else if ( $period == '4') {
			$this->db->from('projects')->where( 'YEAR(projects.created) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			return $this->db->get()->result_array();
		} 
		/*** Get Last Month Projects ***/
		else if ( $period == '5') {
			$this->db->from('projects')->where( 'MONTH(projects.created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			return $this->db->get()->result_array();
		}
		/*** Get Last Week Projects ***/
		else if ( $period == '6') {
			$this->db->from('projects')->where( 'WEEK(projects.created) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			return $this->db->get()->result_array();
		}
		/*** Get Custom Projects ***/
		else if ( $period == '7') {
			$this->db->where(array('projects.created>=' => $from, 'projects.created<=' => $to));
			return $this->db->get('projects')->result_array();
		}
	}

	function InvoiceReport() {
		$invoices = $this->get_all_invoices();
		$data_invoices = array();
		$invoiceArray = array();
		$invoiceTotal = 0;
		foreach ( $invoices as $invoice ) {
			$invoiceTotal = $invoiceTotal + $invoice['total'];
			$totalx = $invoice[ 'total' ];
			$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(invoice_id =' . $invoice[ 'id' ] . ') ' );
			$paytotal = $this->db->get();
			$balance = $totalx - $paytotal->row()->amount;
			if ( $balance > 0 ) {
				$invoicestatus = '';
			} else {
				$invoicestatus = lang( 'paidinv' );
			}
			$color = 'success';;
			if ( $paytotal->row()->amount < $invoice[ 'total' ] && $paytotal->row()->amount > 0 && $invoice[ 'status_id' ] == 3 ) {
				$invoicestatus = lang( 'partial' );
				$color = 'warning';
			} else {
				if ( $paytotal->row()->amount < $invoice[ 'total' ] && $paytotal->row()->amount > 0 ) {
					$invoicestatus = lang( 'partial' );
					$color = 'warning';
				}
				if ( $invoice[ 'status_id' ] == 3 ) {
					$invoicestatus = lang( 'unpaid' );
					$color = 'danger';
				}
			}
			if ( $invoice[ 'status_id' ] == 1 ) {
				$invoicestatus = lang( 'draft' );
				$color = 'muted';
			}
			if ( $invoice[ 'status_id' ] == 4 ) {
				$invoicestatus = lang( 'cancelled' );
				$color = 'danger';
			}
			if ( $invoice[ 'type' ] == 1 ) {
				$customer = $invoice[ 'individual' ];
			} else $customer = $invoice[ 'customercompany' ];
			$data_invoices[] = array(
				'id' => $invoice[ 'id' ],
				'longid' => get_number('invoices', $invoice[ 'id' ], 'invoice','inv'),
				'created' => date(get_dateFormat(),strtotime($invoice[ 'created' ])),
				'duedate' => $invoice[ 'duedate' ] ? date(get_dateFormat(),strtotime($invoice[ 'duedate' ])) : '',
				'customer_id' => $invoice[ 'customer_id' ],
				'staff_id' => $invoice[ 'staff_id' ],
				'staffname' => $invoice['staffmembername'],
				'customer' => $customer,
				'total' => +$invoice[ 'total' ],
				'status' => $invoicestatus,
				'color' => $color,
			);
		};
		$total[] = array(
			'invoiceTotal' => $invoiceTotal
		);
		$invoiceArray = array(
			'data_invoice' => $data_invoices,
			'total' => $invoiceTotal
		);
		return $invoiceArray ;
	}

	function CustomerReport() {
		$customers = $this->get_all_customers();
		$data_customers = array();
		foreach ( $customers as $customer ) {
			if ($customer[ 'type' ] == '0') {
				$name = $customer[ 'company' ];
			} else {
				$name = $customer[ 'namesurname' ];
			}
			$this->db->select_sum( 'total' )->from( 'invoices' )->where( '(status_id = 3 AND customer_id = ' . $customer[ 'id' ] . ') ' );
			$total_unpaid_invoice_amount = $this->db->get()->row()->total;
			$this->db->select_sum( 'total' )->from( 'invoices' )->where( '(status_id = 2 AND customer_id = ' . $customer[ 'id' ] . ') ' );
			$total_paid_invoice_amount = $this->db->get()->row()->total;
			$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(transactiontype = 0 AND customer_id = ' . $customer[ 'id' ] . ') ' );
			$total_paid_amount = $this->db->get()->row()->amount;
			$data_customers[] = array(
				'id' => $customer[ 'id' ],
				'name' => $name,
				'address' => $customer[ 'address' ],
				'email' => $customer[ 'email' ],
				'phone' => $customer[ 'phone' ],
				'customer_number' => get_number('customers', $customer[ 'id' ], 'customer','customer'),
				'group_name' => $customer['name'],
				'balance' => $total_unpaid_invoice_amount - $total_paid_amount + $total_paid_invoice_amount,
			);
		};
		return $data_customers;
	}

	function ExpenseReport() {
		$expenses = $this->get_all_expenses();
		$data_expenses = array();
		$expenseArray = array();
		$expenseTotal = 0;
		foreach ( $expenses as $expense ) {
			$expenseTotal = $expenseTotal + $expense['amount'];
			if ( $expense[ 'invoice_id' ] == NULL) {
				$billstatus = lang( 'notbilled' ) and $color = 'warning';
			} else {
				$billstatus = lang( 'billed' ) and $color = 'success';
			}
			if( $expense['internal'] == '1') {
				$customer = get_number('staff', $expense[ 'staffid' ], 'staff','staff');
				$customername = $expense['staff'];
				$billstatus = lang( 'internal' ) and $color = 'success';
			} else {
				$customer = get_number('customers', $expense[ 'customerid' ], 'customer','customer');
				$customername = $expense['company'] ? $expense['company'] : $expense['namesurname'];
			}
			$data_expenses[] = array(
				'id' => $expense[ 'id' ],
				'longid' => get_number('expenses', $expense[ 'id' ], 'expense','expense'),
				'title' => $expense[ 'title' ],
				'category' => $expense[ 'category' ],
				'type' => $expense['internal'] == '1' ? lang('internal') : '',
	        	'customer' => $customer,
	        	'customername' => $customername,
				'color' => $color,
				'billstatus' => $billstatus,
				'internal' => $expense['internal'],
				'staffid' => $expense[ 'staffid' ],
				'customerid' => $expense[ 'customerid' ],
				'date' => date(get_dateFormat(), strtotime($expense['date'])),
				'amount' => +$expense[ 'amount' ],
			);
		};
		$total[] = array(
			'expenseTotal' => $expenseTotal
		);
		$expenseArray = array(
			'data_expense' => $data_expenses,
			'total' => $expenseTotal,
		);
		return $expenseArray;
	}

	function ProposalReport() {
		$proposals = $this->get_all_proposals();
		$data_proposals = array();
		$proposaleArray = array();
		$proposalTotal = 0;
		foreach ( $proposals as $proposal ) {
			$proposalTotal = $proposalTotal + $proposal['total'];
			switch ( $proposal[ 'status_id' ] ) {
				case '0':
					$status = lang( 'quote' ).' '.lang( 'request' );
					$class = 'proposal-status-open';
					break;
				case '1':
					$status = lang( 'draft' );
					$class = 'proposal-status-accepted';
					break;
				case '2':
					$status = lang( 'sent' );
					$class = 'proposal-status-sent';
					break;
				case '3':
					$status = lang( 'open' );
					$class = 'proposal-status-open';
					break;
				case '4':
					$status = lang( 'revised' );
					$class = 'proposal-status-revised';
					break;
				case '5':
					$status = lang( 'declined' );
					$class = 'proposal-status-declined';
					break;
				case '6':
					$status = lang( 'accepted' );
					$class = 'proposal-status-accepted';
					break;
				default :
					$status = lang( 'open' );
					$class = 'proposal-status-open';
					break;
			};
			$data_proposals[] = array(
				'id' => $proposal[ 'id' ],
				'longid' => get_number('proposals', $proposal[ 'id' ], 'proposal','proposal'),
				'subject' => $proposal[ 'subject' ],
				'date' => date(get_dateFormat(), strtotime($proposal['date'])),
				'opentill' => date(get_dateFormat(), strtotime($proposal['opentill'])),
				'status' => $status,
				'assigned' => $proposal['staffmembername'],
				'staffid' => $proposal['staffid'],
				'staff_number' => get_number('staff', $proposal[ 'staffid' ], 'staff','staff'),
				'total' => +$proposal[ 'total' ],
				'class' => $class,
			);
		};
		$total[] = array(
				'proposalTotal' => $proposalTotal
			);
		$proposaleArray = array(
			'data_proposal' => $data_proposals,
			'total' => $proposalTotal
		);
		return $proposaleArray;
	}

	function DepositsReport() { 
	    $deposits = $this->get_all_deposits(); 
	    $data_deposits = array(); 
	    foreach ( $deposits as $deposit ) { 
	    	if ( $deposit[ 'status' ] == '1' ) {
				$billstatus = lang( 'paid' ) and $color = 'success';
			} else if( $deposit[ 'status' ] == '0' ) {
				$billstatus = lang( 'unpaid' ) and $color = 'danger';
			} else {
				$billstatus = lang( 'internal' ) and $color = 'success';
			}
			if( $deposit['status'] == '2') {
				$customer = get_number('staff', $deposit[ 'staffid' ], 'staff','staff');
				$customername = $deposit['staffname'];
			} else {
				$customer = get_number('customers', $deposit[ 'customerid' ], 'customer','customer');
				$customername = $deposit['company'] ? $deposit['company'] : $deposit['namesurname'];
			}
		    $data_deposits[] = array( 
		        'id' => $deposit[ 'id' ], 
		        'longid' => get_number('deposits', $deposit[ 'id' ], 'deposit','deposit'),
		        'title' => $deposit[ 'title' ], 
		        'category' => $deposit[ 'category' ], 
		        'created' => date(get_dateFormat(), strtotime($deposit['created'])),
		        'billstatus' => $billstatus,
				'color' => $color,
		        'amount' => +$deposit[ 'amount' ], 
		        'date' => date(get_dateFormat(), strtotime($deposit['date'])), 
		        'customer' => $customer,
	        	'customername' => $customername,
		        'status' => $deposit['status'],
	        	'staffid' => $deposit['staffid'],
	        	'customerid' => $deposit['customerid'],
		    ); 
		}; 
		return $data_deposits; 
	} 

	function OrdersReport() {
		$orders = $this->get_all_orders();
		$data_orders = array();
		foreach ( $orders as $order ) {
			if($order['relation_type'] == 'customer') {
				$customer_number = get_number('customers', $order[ 'customerid' ], 'customer','customer');
				$customer = $order['company'] ? $order['company'] : $order['namesurname'];
			} else {
				$customer_number = get_number('leads', $order[ 'leadid' ], 'lead','lead');
				$customer = $order['leadname'];	
			}
			switch ( $order[ 'status_id' ] ) {
				case '1':
					$status = lang( 'draft' );
					$class = 'proposal-status-accepted';
					break;
				case '2':
					$status = lang( 'sent' );
					$class = 'proposal-status-sent';
					break;
				case '3':
					$status = lang( 'open' );
					$class = 'proposal-status-open';
					break;
				case '4':
					$status = lang( 'revised' );
					$class = 'proposal-status-revised';
					break;
				case '5':
					$status = lang( 'declined' );
					$class = 'proposal-status-declined';
					break;
				case '6':
					$status = lang( 'accepted' );
					$class = 'proposal-status-accepted';
					break;
				default: 
					$status = lang( 'open' );
					$class = 'proposal-status-open';
					break;
			};
			$data_orders[] = array(
				'id' => $order[ 'id' ],
				'longid' => get_number('orders', $order[ 'id' ], 'order','order'),
				'subject' => $order[ 'subject' ],
				'date' =>  date(get_dateFormat(), strtotime($order['date'])),
				'opentill' => date(get_dateFormat(), strtotime($order['opentill'])),
				'status' => $status,
				'total' => +$order[ 'total' ],
				'class' => $class,
				'customer' => $customer,
				'customer_number' => $customer_number,
				'assigned' => $order['staffmembername'],
				'staff' => get_number('staff', $order[ 'staffid' ], 'staff','staff'),
				'customerid' => $order['customerid'],
				'staffid' => $order['staffid']
			);
		};
		return  $data_orders ;
	}

	function VendorsReport() {
		$vendors = $this->get_all_vendors();
		$data_vendors = array();
		foreach ( $vendors as $vendor ) {
			$this->db->select_sum( 'total' )->from( 'purchases' )->where( '(status_id = 3 AND vendor_id = ' . $vendor[ 'id' ] . ') ' );
			$total_unpaid_invoice_amount = $this->db->get()->row()->total;
			$this->db->select_sum( 'total' )->from( 'purchases' )->where( '(status_id = 2 AND vendor_id = ' . $vendor[ 'id' ] . ') ' );
			$total_paid_invoice_amount = $this->db->get()->row()->total;
			$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(transactiontype = 0 AND vendor_id = ' . $vendor[ 'id' ] . ') ' );
			$total_paid_amount = $this->db->get()->row()->amount;
			$data_vendors[] = array(
				'id' => $vendor[ 'id' ],
				'vendor_number' => get_number('vendors', $vendor[ 'id' ], 'vendor','vendor'),
				'name' => $vendor[ 'company' ],
				'email' => $vendor[ 'email' ],
				'address' => $vendor[ 'address' ],
				'group_name' => $vendor['name'],
				'phone' => $vendor[ 'phone' ],
				'balance' => $total_unpaid_invoice_amount - $total_paid_amount + $total_paid_invoice_amount,
			);
		};
		return $data_vendors ;
	}

	function PurchasesReport() {
		$purchases = $this->get_all_purchases();
		$data_purchases = array();
		$purchase_array = array();
		$purchaseTotal = 0;
		foreach ( $purchases as $purchase ) {
			$purchaseTotal = $purchaseTotal + $purchase['total'];
			$totalx = $purchase[ 'total' ];
			$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(purchase_id =' . $purchase[ 'id' ] . ') ' );
			$paytotal = $this->db->get();
			$balance = $totalx - $paytotal->row()->amount;
			if ( $balance > 0 ) {
				$purchasesstatus = '';
			} else $purchasesstatus = lang( 'paidinv' );
			$color = 'success';;
			if ( $paytotal->row()->amount < $purchase[ 'total' ] && $paytotal->row()->amount > 0 && $purchase[ 'status_id' ] == 3 ) {
				$purchasesstatus = lang( 'partial' );
				$color = 'warning';
			} else {
				if ( $paytotal->row()->amount < $purchase[ 'total' ] && $paytotal->row()->amount > 0 ) {
					$purchasesstatus = lang( 'partial' );
					$color = 'warning';
				}
				if ( $purchase[ 'status_id' ] == 3 ) {
					$purchasesstatus = lang( 'unpaid' );
					$color = 'danger';
				}
			}
			if ( $purchase[ 'status_id' ] == 1 ) {
				$purchasesstatus = lang( 'draft' );
				$color = 'muted';
			}
			if ( $purchase[ 'status_id' ] == 4 ) {
				$purchasesstatus = lang( 'cancelled' );
				$color = 'danger';
			}
			$data_purchases[] = array(
				'id' => $purchase[ 'id' ],
				'serie' => $purchase['serie'],
				'longid' => get_number("purchases",$purchase['id'],'purchase','purchase'),
				'created' => date(get_dateFormat(), strtotime($purchase['created'])),
				'duedate' => $purchase['duedate'] ? date(get_dateFormat(), strtotime($purchase['duedate'])) : '',
				'total' => +$purchase[ 'total' ],
				'status' => $purchasesstatus,
				'color' => $color,
				'vendor_id' => $purchase[ 'vendor_id' ],
				'vendor' => $purchase[ 'vendorcompany' ],
				'vendor_number' => get_number("vendors",$purchase['vendor_id'],'vendor','vendor'),
				'staffname' => $purchase['staffmembername'],
			);
			$total[] = array(
				'purchaseTotal' => $purchaseTotal
			);
			$purchase_array = array(
				'data_purchase' => $data_purchases,
				'total' => $purchaseTotal
			);
		};

		return $purchase_array;
	}

	function ContactsReport() {
		$contacts = $this->get_all_contacts();
		$data_contacts = array();
		foreach ( $contacts as $contact ) {
			$data_contacts[] = array(
				'id' => $contact[ 'contactid' ],
				'name' => $contact['name'].' '.$contact['surname'],
				'customerid' => $contact['customerid'],
				'email' => $contact[ 'email' ],
				'customer' => $contact['company'] ? $contact['company'] : $contact['namesurname'],
				'customer_number' => get_number("customers",$contact['customerid'],'customer','customer'),
				'mobile' => $contact['mobile'] ? $contact['mobile'] : $contact['phone'],
			);
		};
		return $data_contacts;
	}

	function TicketsReport() {
		$tickets = $this->get_all_tickets();
		$data_tickets = array();
		foreach ( $tickets as $ticket ) {
			switch ( $ticket[ 'priority' ] ) {
				case '1':
					$priority = lang( 'low' );
					break;
				case '2':
					$priority = lang( 'medium' );
					break;
				case '3':
					$priority = lang( 'high' );
					break;
				default:
					$priority = lang( 'medium' );
					break;
			};
			switch ( $ticket[ 'status_id' ] ) {
				case '1':
					$status = lang( 'open' );
					break;
				case '2':
					$status = lang( 'inprogress' );
					break;
				case '3':
					$status = lang( 'answered' );
					break;
				case '4':
					$status = lang( 'closed' );
					break;
				default: 
					$status = lang( 'open' );
					break;
			};
			if ( $ticket[ 'type' ] == 0 ) {
				$customer = $ticket[ 'company' ];
			} else $customer = $ticket[ 'namesurname' ];
			$data_tickets[] = array(
				'id' => $ticket[ 'id' ],
				'subject' => $ticket[ 'subject' ],
				'customer' => $customer.'('.(get_number('customers', $ticket[ 'customer_id' ], 'customer','customer')).')',
				'contactname' => '' . $ticket[ 'contactname' ] . ' ' . $ticket[ 'contactsurname' ] . '',
				'department' => $ticket[ 'department' ],
				'priority' => $priority,
				'status' => $status,
				'assigned_staff_name' => $ticket[ 'staffmembername' ],
				'last_reply_date' => $ticket[ 'lastreply' ] ? date(get_dateTimeFormat(),strtotime($ticket[ 'lastreply' ])) : '',
				'ticket_number' => get_number('tickets', $ticket[ 'id' ], 'ticket','ticket'),
			);

		}
		return $data_tickets ;
	}

	function TasksReport() {
		$tasks = $this->get_all_tasks();
		$data_tasks = array();
		foreach ( $tasks as $task ) {
			switch ( $task[ 'status_id' ] ) { 
				case '1':
					$status = lang( 'open' );
					break;
				case '2':
					$status = lang( 'inprogress' );
					break;
				case '3':
					$status = lang( 'waiting' );
					break;
				case '4':
					$status = lang( 'complete' );
					break;
				case '5':
					$status = lang( 'cancelled' );
					break;
				default:
					$status = lang( 'open' );
					break;
			};
			switch ( $task[ 'priority' ] ) {
				case '1':
					$priority = lang( 'low' );
					break;
				case '2':
					$priority = lang( 'medium' );
					break;
				case '3':
					$priority = lang( 'high' );
					break;
				default:
					$priority = lang( 'medium' );
					break;
			};
			$data_tasks[] = array(
				'id' => $task[ 'id' ],
				'task_number' => get_number('tasks',$task['id'],'task','task'),
				'name' => $task[ 'taskname' ],
				'duedate' => date(get_dateFormat(), strtotime($task['duedate'])),
				'startdate' => date(get_dateFormat(), strtotime($task['startdate'])),
				'status' => $status,
				'priority' => $priority,
				'staffname' => $task['staffname'],
				'projectid' => $task['projectid'],
				'projectname' => $task['projectname'],
				'project' => get_number('projects',$task['projectid'],'project','project'), 
			);
		};
		return  $data_tasks ;
	}

	function LeadsReport() {
		$leads = $this->get_all_leads();
		$data_leads = array();
		foreach ( $leads as $lead ) {
			$data_leads[] = array(
				'id' => $lead[ 'id' ],
				'name' => $lead[ 'leadname' ],
				'company' => $lead[ 'company' ],
				'phone' => $lead[ 'leadphone' ],
				'color' => $lead[ 'color' ]?$lead[ 'color' ]:'',
				'statusname' => $lead[ 'statusname' ]?$lead[ 'statusname' ]:'',
				'sourcename' => $lead[ 'sourcename' ]?$lead[ 'sourcename' ]:'',
				'assigned' => $lead[ 'leadassigned' ],
				'lead_number'=> get_number('leads', $lead[ 'id' ], 'lead','lead'),
			);
		};
		return $data_leads ;
	}

	function ProductReport() {
		$products = $this->get_all_products();
		$data_products = array();
		foreach ( $products as $product ) {
			$data_products[] = array(
				'product_id' => $product[ 'id' ],
				'code' => $product['code'],
				'name' => $product[ 'productname' ],
				'description' => $product[ 'description' ],
				'sales_price' => $product[ 'sale_price' ],
				'tax' => $product[ 'vat' ],
				'purchase_price' => $product[ 'purchase_price' ],
				'category_name' => $product[ 'name' ],
				'stock' => $product[ 'stock' ],
				'product_number' => get_number('products', $product[ 'id' ], 'product','product'),
			);
		};
		return $data_products ;
	}

	function staffReport() {
		$staffs = $this->get_all_staff();
		$data_staffs = array();
		foreach ( $staffs as $staff ) {
			if( $staff['admin'] == '1' ) {
				$type = lang('admin'); 
			} else if ( $staff['staffmember'] == '1' && $staff['other'] == null) {
				$type = lang('staff');
			} else {
				$type = lang('other');
			}
			$data_staffs[] = array(
				'id' => $staff[ 'id' ],
				'name' => $staff[ 'staffname' ],
				'department' => $staff[ 'department' ],
				'staff_number' => get_number('staff', $staff[ 'id' ], 'staff','staff'),
				'phone' => $staff[ 'phone' ],
				'address' => $staff[ 'address' ],
				'email' => $staff[ 'email' ],
				'type' => $type,
			);
		};
		return $data_staffs;
	}

	function ProjectsReport() {
		$projects = $this->get_all_projects();
		$data_projects = array();
		foreach ( $projects as $project ) {
			switch ( $project[ 'status' ] ) {
				case '1':
					$status = lang( 'notstarted' );
					break;
				case '2':
					$status = lang( 'started' );
					break;
				case '3':
					$status = lang( 'percentage' );
					break;
				case '4':
					$status = lang( 'cancelled' );
					break;
				case '5':
					$status = lang( 'completed' );
					break;
				default :
					$status = lang( 'started' );
					break;
			}
			if($project[ 'template' ] == '1')	{
				$customer = lang('template');
			} else {
				$customer = ($project['customercompany'])?$project['customercompany']:$project['namesurname'];
			}
			$members = $this->Projects_Model->get_members_index( $project['id'] );
			$data_projects[] = array(
				'id' => $project[ 'id' ],
				'name' => $project[ 'name' ],
				'project_number' => get_number('projects', $project[ 'id' ], 'project','project'),
				'members' => $members,
				'customer' => $customer,
				'template' => $project['template'],
				'customerid' => $project['customerid'],
				'customer_number' => get_number('customers', $project[ 'customerid' ], 'customer','customer'),
				'startdate' => date(get_dateFormat(), strtotime($project['start_date'])),
				'enddate' => date(get_dateFormat(), strtotime($project['deadline'])),
				'value' => $project[ 'projectvalue' ],
				'status' => $status,
			);
		};
		return $data_projects;
	}

	function get_timesheet_by_privileges($staff_id='') {
		$admin = $this->isAdmin();
		$this->db->select( 'tasktimer.id, tasktimer.start, tasktimer.end, tasktimer.task_id, tasks.name, tasktimer.note, staff.staffname as staff, staff.id as staff_id, staff.staffavatar as avatar, email ' );
		$this->db->join( 'staff', 'tasktimer.staff_id = staff.id', 'left' );
		$this->db->join( 'tasks', 'tasktimer.task_id = tasks.id', 'left' );
		$this->db->order_by('tasktimer.id', 'desc');
		if ($staff_id) {
			return $this->db->get_where( 'tasktimer', array( 'tasktimer.staff_id' => $staff_id ) )->result_array();
		} else {
			return $this->db->get_where( 'tasktimer', array(  ) )->result_array();
		}
	}

	function total_amount_by_status($status='',$type) {
		$this->db->select_sum( 'total' );
		$this->db->from( $type );
		if($status == '3') {
			$this->db->where( 'status_id = 3 AND CURDATE() <= duedate' );
		} else if ( $status == 'due' ) {
			$this->db->where( 'CURDATE() >= duedate AND duedate != "0000.00.00" AND status_id != "4" AND status_id != "2"' );
		} else if ( $status == '1' || $status == '2' ) {
			$this->db->where( 'status_id', $status );
		} 
		$this->db->where('staff_id', $this->session->usr_id);
		$total_value = $this->db->get()->row()->total;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function total_number_of_data_by_status($status, $type) {
		if($status == '4') {
			$this->db->from( $type )->where( 'status_id  !=', $status );
		} else if ( $status == '3' ) {
			$this->db->from( $type )->where( 'status_id ='. $status .' AND CURDATE() <= duedate' );
		} else if ( $status == 'due' ) {
			$this->db->from( $type )->where( 'CURDATE() >= duedate AND duedate != "0000.00.00" AND status_id != "4" AND status_id != "2"' );
		} else {
			$this->db->from( $type )->where( 'status_id', $status );
		}
		$this->db->where('staff_id', $this->session->usr_id);
		$query = $this->db->get();
		$total = $query->num_rows();
		return $total;
	}
}