<?php
require_once APPPATH . '/third_party/vendor/autoload.php';
use Dompdf\Dompdf;
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Report extends CIUIS_Controller {

	function __construct() {
		parent::__construct();
		$path = $this->uri->segment( 1 );
		if ( !$this->Privileges_Model->has_privilege( $path ) ) {
			$this->session->set_flashdata( 'ntf3', '' . lang( 'you_dont_have_permission' ) );
			redirect( 'panel/' );
			die;
		}
	}

	function index() { 
		$data = $this->Settings_Model->get_settings_ciuis();
		$data[ 'settings' ] = $data;
		$data[ 'title' ] = $data['crm_name'].' '. lang('reports');
		$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
		$data[ 'bkt' ] = $this->Report_Model->bkt(); // daily total sales
		$data[ 'bht' ] = $this->Report_Model->bht(); // weekly sales
		$data[ 'ycr' ] = $this->Report_Model->ycr(); // yearly sales
		$data[ 'oyc' ] = $this->Report_Model->oyc(); // yearly sales
		$data[ 'oft' ] = $this->Report_Model->oft(); // invoice total due
		$data[ 'tef' ] = $this->Report_Model->tef(); // total invoices
		$data[ 'vgf' ] = $this->Report_Model->vgf();
		$data[ 'tbs' ] = $this->Report_Model->tbs();
		$data[ 'akt' ] = $this->Report_Model->akt();
		$data[ 'oak' ] = $this->Report_Model->oak();
		$data[ 'tfa' ] = $this->Report_Model->tfa();
		$data[ 'yms' ] = $this->Report_Model->yms();
		$data[ 'ofy' ] = ( $data[ 'tfa' ] > 0 ? number_format( ( $data[ 'tef' ] * 100 ) / $data[ 'tfa' ] ) : 0 );
		$data[ 'weekly_sales_chart_report' ] = json_encode( $this->Report_Model->weekly_sales_chart_report() );
		$data[ 'monthly_sales_graph' ] = $this->Report_Model->monthly_sales_graph();
		$data[ 'monthly_expense_graph' ] = $this->Report_Model->monthly_expenses();
		$data[ 'invoice_chart_by_status' ] = json_encode( $this->Report_Model->invoice_chart_by_status() );
		$data[ 'leads_by_leadsource' ] = json_encode( $this->Report_Model->leads_by_leadsource() );
		$data[ 'leads_to_win_by_leadsource' ] = json_encode( $this->Report_Model->leads_to_win_by_leadsource() );
		$data[ 'top_selling_staff_chart' ] = json_encode( $this->Report_Model->top_selling_staff_chart() );
		$data[ 'incomings_vs_outgoins' ] = json_encode( $this->Report_Model->incomings_vs_outgoins() );
		$data[ 'expenses_by_categories' ] = json_encode( $this->Report_Model->expenses_by_categories() );
		$data[ 'events' ] = $this->Events_Model->get_all_events();
		$this->load->view( 'report/index', $data );
	}

	function get_reports_data() {
		$data[ 'totalTickets' ] = $this->Report_Model->totalData('tickets');
		$data[ 'totalCustomers' ] = $this->Report_Model->totalData('customers');
		$data[ 'totalLeads' ] = $this->Report_Model->totalData('leads');
		$data[ 'totalProjects' ] = $this->Report_Model->totalData('projects');
		$data[ 'totalProducts' ] = $this->Report_Model->totalData('products');
		$data[ 'totalTasks' ] = $this->Report_Model->totalData('tasks');
		$data[ 'totalLeads' ] = $this->Report_Model->totalData('leads');
		$data[ 'totalOrders' ] = $this->Report_Model->totalData('orders');
		$data[ 'totalQotations' ] = 0;
		$data[ 'totalInvoices' ] = $this->Report_Model->totalData('invoices');
		$report = $this->Report_Model->weekly_incomings_vs_outgoings();
		$data['payments'] = $report['payments'];
		$data['expenses'] = $report['expenses'];
		$data['weekdays'] = array(lang('monday'), lang('tuesday'), lang('wednesday'), lang('thursday'), lang('friday'), lang('saturday'), lang('sunday'), );
		echo json_encode($data);
	}

	function get_timesheet_data() {
		$result = $this->Report_Model->get_timesheet();
		$timesheet = array();
		$totalT = 0;
		$total_h = $total_m = $total_s = 0;
		foreach ( $result as $field ) {
			$end_time = $field['end'];
			$date = new DateTime();
			if ($end_time == NULL) {
				$endTime = NULL;
				$end_time = $date->format('Y-m-d H:i:s');
			} else {
				$endTime = $field['end'];
				$end_time = $field['end'];
			}
			$date1 = new DateTime($field['start']);
			$diffs = $date1->diff(new DateTime($end_time));
			$h = $diffs->days * 24;
			$h += $diffs->h;
			$minutes = $diffs->i;
			$seconds = $diffs->s;
			if ($minutes < 10) {
				$minutes = '0'.$minutes;
			}
			if ($seconds < 10) {
				$seconds = '0'.$seconds;
			}
			if ($h < 10) {
				$h = '0'.$h;
			}
			$total = $h.':'.$minutes.':'.$seconds;
			$total_h += $h;
			$total_m += $minutes;
			$total_s += $seconds;
			$timesheet[] = array(
				'id' => $field[ 'id' ],
				'name' => $field[ 'name' ],
				'start_time' => $field['start'],
				'end_time' => $endTime,
				'total_time' => $total,
				'note' => $field[ 'note' ],
				'relation_id' => $field[ 'task_id' ],
				'staff' => $field[ 'staff' ],
				'staff_id' => $field[ 'staff_id' ],
				'avatar' => $field[ 'avatar' ],
			);
		}
		if ($total_s > 59) {
			$total_m += (int)($total_s / 60);
			$total_s = $total_s % 60;
		}
		if ($total_m > 59) {
			$total_h += (int)($total_m / 60);
			$total_m = $total_m % 60;
		}
		$data = array(
			'total' => $total_h.':'.$total_m.':'.$total_s,
			'timesheet' => $timesheet,
		);
		echo json_encode($data);
	}

	function expenses_payments_graph( $year ) {
		echo json_encode( $this->Report_Model->expenses_payments_graph( $year ) );
	}

	function customer_monthly_increase_chart( $month ) {
		echo json_encode( $this->Report_Model->customer_monthly_increase_chart( $month ) );
	}

	function lead_graph( $month ) {
		echo json_encode( $this->Report_Model->lead_graph( $month ) );
	}

	function test() {
		echo json_encode( $this->Report_Model->a1() );
	}

	function viewReports() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$period = $this->input->post('period');
			$exporttype = $this->input->post('exporttype');
			$hasError = false;
			$data['message'] = '';
			if ($exporttype == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('report').' '.lang('type');
			} else if ( $period == '7') {
				$from = $this->input->post('from');
				$to = $this->input->post('to');
				if( $from == '' ) {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('from').' '.lang('date');
				} else if ( $to == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('to').' '.lang('date');
				}
			}
			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				if ( $exporttype == 'invoices') {
					$data['result'] = $this->Report_Model->InvoiceReport(); 
				} else if ( $exporttype == 'expenses') {
					$data['result'] = $this->Report_Model->ExpenseReport(); 
				} else if ( $exporttype == 'customers') {
					$data['result'] = $this->Report_Model->CustomerReport(); 
				} else if ( $exporttype == 'proposals') {
					$data['result'] = $this->Report_Model->ProposalReport(); 
				} else if ( $exporttype == 'deposits') {
					$data['result'] = $this->Report_Model->DepositsReport(); 
				} else if ( $exporttype == 'orders') {
					$data['result'] = $this->Report_Model->OrdersReport(); 
				} else if ( $exporttype == 'vendors') {
					$data['result'] = $this->Report_Model->VendorsReport(); 
				} else if ( $exporttype == 'purchases') {
					$data['result'] = $this->Report_Model->PurchasesReport(); 
				} else if ( $exporttype == 'contacts') {
					$data['result'] = $this->Report_Model->ContactsReport(); 
				} else if ( $exporttype == 'tickets') {
					$data['result'] = $this->Report_Model->TicketsReport(); 
				} else if ( $exporttype == 'tasks') {
					$data['result'] = $this->Report_Model->TasksReport(); 
				} else if ( $exporttype == 'leads') {
					$data['result'] = $this->Report_Model->LeadsReport(); 
				} else if ( $exporttype == 'products') {
					$data['result'] = $this->Report_Model->ProductReport();
				} else if ( $exporttype == 'staff') {
					$data['result'] = $this->Report_Model->staffReport(); 
				} else if ( $exporttype == 'projects') {
					$data['result'] = $this->Report_Model->ProjectsReport(); 
				} 
				$data['exporttype'] = $exporttype;
				$data['success'] = true;
				echo json_encode($data);
			}
		}
	}

	function exportData() {
		$type = $this->input->post('type');
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		if ($type == 'invoices') {
			$this->db->select('invoices.id as invid, invoice_number, serie, invoices.created as issuance_date, duedate, customers.id as custid, customer_number, customers.company as company, customers.namesurname as custname, staff.id as staffid, staff_number , staffname, invoicestatus.name as status, total_tax, total, invoices.default_payment_method as default_payment_method');
			$this->db->join( 'customers', 'invoices.customer_id = customers.id', 'left' );
			$this->db->join( 'invoicestatus', 'invoices.status_id = invoicestatus.id', 'left' );
			$this->db->join( 'staff', 'invoices.staff_id = staff.id', 'left' );
		} else if ( $type == 'customers') {
			$this->db->select('customers.id as custid, customer_number, type, created, company, namesurname, taxoffice, taxnumber, ssn, executive, address, zipcode, country_id as country, state, state_id, city, town, phone, fax, email, web, risk, customergroups.id as group, customergroups.name as groupname');
			$this->db->join('customergroups','customers.groupid = customergroups.id','left');
		} else if ( $type == 'expenses') {
			$this->db->select( 'expenses.id as expid, expense_number, invoice_id, expensecat.name as category, internal, customers.id as custid, customer_number, customers.company as company, namesurname, staff.id as staffid, staff_number, staffname, expenses.date as date, amount, accounts.name as payment_account' );
			$this->db->join( 'customers', 'expenses.customer_id = customers.id', 'left' );
			$this->db->join( 'accounts', 'expenses.account_id = accounts.id', 'left' );
			$this->db->join( 'expensecat', 'expenses.category_id = expensecat.id', 'left' );
			$this->db->join( 'staff', 'expenses.staff_id = staff.id', 'left' );
		} else if ( $type == 'proposals') {
			$this->db->select( 'proposals.id as proposalid, proposal_number, staffname as assignedstaff, subject, created, opentill, status_id, sub_total' );
			$this->db->join( 'staff', 'proposals.assigned = staff.id', 'left' );
		} else if ( $type == 'deposits') {
			$this->db->select( 'title, depositcat.name as category, customers.id as custid, customer_number, company, namesurname, staff.id as staffid, staff_number, staffname, deposits.id as depid, deposit_number, accounts.name as payment_account, deposits.date as depdate, deposits.sub_total as total, status' ); 
			$this->db->join( 'accounts', 'deposits.account_id = accounts.id', 'left' );
			$this->db->join( 'customers', 'deposits.customer_id = customers.id', 'left' ); 
			$this->db->join( 'depositcat', 'deposits.category_id = depositcat.id', 'left' ); 
			$this->db->join( 'staff', 'deposits.staff_id = staff.id', 'left' ); 
		} else if ( $type == 'orders') {
			$this->db->select( 'orders.id as orderid, order_number, subject, relation_type, orders.status_id as status_id, customers.id customerid, customer_number, customers.company, namesurname, leads.id as leadid, lead_number, leads.name as leadname, orders.status_id as status, orders.date orderdate, opentill, orders.sub_total as total, staff.id staffid, staff_number, staffname' );
			$this->db->join( 'customers', 'orders.relation = customers.id', 'left' );
			$this->db->join( 'staff', 'orders.assigned = staff.id', 'left' );
			$this->db->join( 'leads', 'orders.relation = leads.id', 'left' );
		} else if ( $type == 'vendors') {
			$this->db->select( 'vendors.id as vendorid, company, vendor_number, vendors_groups.name as groupname, email, country_id ' );
			$this->db->join('vendors_groups','vendors.groupid = vendors_groups.id','left');
		} else if ( $type == 'purchases') {
			$this->db->select( 'purchases.id as id, purchase_number, serie, vendors.id as vendorid, vendor_number, vendors.company as vendorname, purchases.created issuance_date,duedate, datepayment, purchases.sub_total as total, purchases.status_id as status_id' );
			$this->db->join( 'vendors', 'purchases.vendor_id = vendors.id', 'left' );
		} else if ( $type == 'contacts') {
			$this->db->select('contacts.id as contactid,  name, surname, contacts.email as email, contacts.phone as phone, mobile, company, namesurname, customers.id customerid, customer_number, created');
			$this->db->join('customers', 'contacts.customer_id = customers.id', 'left');
		} else if ( $type == 'tasks') {
			$this->db->select( 'projects.id as projectid, project_number, projects.name as projectname, tasks.id as taskid, task_number, tasks.name as taskname, startdate, duedate, priority, tasks.status_id as status_id, staffname' );
			$this->db->join('staff', 'tasks.assigned = staff.id', 'left' );
			$this->db->join('projects' ,'tasks.relation = projects.id', 'left');
		} else if ( $type == 'leads') {
			$this->db->select( '*,leadsstatus.name as statusname,staff.staffname as leadassigned,leadssources.name as sourcename,leads.name as leadname,leads.phone as leadphone,leads.id as id, leads.address as address' );
			$this->db->join( 'leadsstatus', 'leads.status = leadsstatus.id', 'left' );
			$this->db->join( 'leadssources', 'leads.source = leadssources.id', 'left' );
			$this->db->join( 'staff', 'leads.assigned_id = staff.id', 'left' );
		} else if ( $type == 'tickets') {
			$this->db->select( '*,customers.type as type, customers.email as customeremail, customers.company as company,customers.namesurname as namesurname,departments.name as department,staff.staffname as staffmembername,staff.email as staffemail,contacts.name as contactname,contacts.surname as contactsurname,tickets.staff_id as stid,tickets.status_id as status_id, tickets.id as id' );
			$this->db->join( 'contacts', 'tickets.contact_id = contacts.id', 'left' );
			$this->db->join( 'customers', 'contacts.customer_id = customers.id', 'left' );
			$this->db->join( 'departments', 'tickets.department_id = departments.id', 'left' );
			$this->db->join( 'staff', 'tickets.staff_id = staff.id', 'left' );
		} else if ( $type == 'products') {
			$this->db->select('productcategories.name, product_number, productcategories.id as categoryid, products.id as id, products.code, products.productname, products.description, products.purchase_price, products.sale_price, products.stock, products.vat, products.status_id, products.productimage');
			$this->db->join( 'productcategories', 'products.categoryid = productcategories.id', 'left' );
		} else if ( $type == 'staff') {
			$this->db->select( '*,departments.name as department, staff.id as id' );
			$this->db->join( 'departments', 'staff.department_id = departments.id', 'left' );
		} else if ( $type == 'projects') {
			$this->db->select( '*,customers.company as customercompany,customers.namesurname as individual,customers.address as customeraddress,projects.status_id as status, projects.id as id, projects.staff_id as staff_id ' );
			$this->db->join( 'customers', 'projects.customer_id = customers.id', 'left' );
		}
		$this->db->order_by( $type.'.id', 'desc' );
		/*** Get All Report ***/
		if($period == '0') {
			$q = $this->db->get_where( $type, array( '' ) )->result_array();
		} 
		/*** Get Current Year Report ***/
		else if ( $period == '1') {
			$year = date('Y');
			if( $type == 'contacts') {
				$this->db->from($type)->where('YEAR(customers.created)', $year);
			} else if ( $type == 'tickets') {
				$this->db->from($type)->where('YEAR('.$type.'.date)', $year);
			} else if ( $type == 'products' || $type == 'staff' ) {
				$this->db->from($type)->where( 'YEAR('.$type.'.createdat)', $year);
			} else {
				$this->db->from($type)->where('YEAR('.$type.'.created)', $year);
			}
			$q = $this->db->get()->result_array();
		} 
		/*** Get Current Month Report ***/
		else if ( $period == '2') {
			$month = date('m');
			if( $type == 'contacts') {
				$this->db->from($type)->where('MONTH(customers.created)', $month);
			} else if ( $type == 'tickets') {
				$this->db->from($type)->where('MONTH('.$type.'.date)', $month);
			} else if ( $type == 'products' || $type == 'staff' ) {
				$this->db->from($type)->where('MONTH('.$type.'.createdat)', $month);
			} else {
				$this->db->from($type)->where('MONTH('.$type.'.created)', $month);
			}
			$q = $this->db->get()->result_array();
		} 
		/*** Get Current Week Report ***/
		else if ( $period == '3') {
			if( $type == 'contacts') {
				$this->db->from($type)->where('WEEK(customers.created) = WEEK(CURRENT_DATE)');
			} else if ( $type == 'tickets') {
				$this->db->from($type)->where('WEEK('.$type.'.date) = WEEK(CURRENT_DATE)');
			} else if ( $type == 'products' || $type == 'staff' ) {
				$this->db->from($type)->where( 'WEEK('.$type.'.createdat) = WEEK(CURRENT_DATE)');
			} else {
				$this->db->from($type)->where( 'WEEK('.$type.'.created) = WEEK(CURRENT_DATE)');
			}
			$q = $this->db->get()->result_array();
		} 
		/*** Get Last Year Report ***/
		else if ( $period == '4') {
			if( $type == 'contacts') {
				$this->db->from($type)->where('YEAR(customers.created) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			} else if ( $type == 'tickets') {
				$this->db->from($type)->where('YEAR('.$type.'.date) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			} else if ( $type == 'products' || $type == 'staff' ) {
				$this->db->from($type)->where('YEAR('.$type.'.createdat)  = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			} else {
				$this->db->from($type)->where( 'YEAR('.$type.'.created) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)');
			}
			$q = $this->db->get()->result_array();
		} 
		/*** Get Last Month Report ***/
		else if ( $period == '5') {
			if( $type == 'contacts') {
				$this->db->from($type)->where('MONTH(customers.created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			} else if ( $type == 'tickets') {
				$this->db->from($type)->where('MONTH('.$type.'.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			} else if ( $type == 'products' || $type == 'staff' ) {
				$this->db->from($type)->where('MONTH('.$type.'.createdat)  = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			} else {
				$this->db->from($type)->where( 'MONTH('.$type.'.created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
			}
			$q = $this->db->get()->result_array();
		}
		/*** Get Last Week Report ***/
		else if ( $period == '6') {
			if( $type == 'contacts') {
				$this->db->from($type)->where('WEEK(customers.created) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			} else if ( $type == 'tickets') {
				$this->db->from($type)->where('WEEK('.$type.'.date) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			} else if ( $type == 'products' || $type == 'staff' ) {
				$this->db->from($type)->where('WEEK('.$type.'.createdat)  = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			} else {
				$this->db->from($type)->where( 'WEEK('.$type.'.created) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)');
			}
			$q = $this->db->get()->result_array();
		}
		/*** Get Custom Report ***/
		else if ( $period == '7') {
			if( $type == 'contacts') {
				$this->db->where(array('customers.created>=' => $from, 'customers.created<=' => $to));
			} else if ( $type == 'tickets') {
				$this->db->where(array($type.'.date>=' => $from, $type.'.date<=' => $to));
			} else if ( $type == 'products' || $type == 'staff' ) {
				$this->db->where(array($type.'.createdat>=' => $from, $type.'.createdat<=' => $to));
			} else {
				$this->db->where(array($type.'.created>=' => $from, $type.'.created<=' => $to));
			}
			$q = $this->db->get($type)->result_array();
		} 
		$filename = '';
        $export = array();
		if ($type == 'invoices') {
        	foreach ($q as $data) {
        		$customer = get_number('customers', $data[ 'custid' ], 'customer','customer');
	        	$export[] = array(
	        		lang('invoice') => $data['invoice_number']?$data['invoice_number']:$data['invid'],
	        		lang('serie') => $data['serie'],
	        		lang('issuance_date') => $data['issuance_date'],
	        		lang('duedate') => $data['duedate'],
	        		lang('customer') => $data['company'] ? $data['company'].' ('.$customer.')' : $data['custname'].'('.$customer.')',
	        		lang('staff') => $data['staffname'].' ('.get_number('staff', $data[ 'staffid' ], 'staff','staff').')',
	        		lang('status') => $data['status'],
	        		lang('tax') => $data['total_tax'],
	        		lang('total') => $data['total'],
	        		lang('default_payment_method') => $data['default_payment_method']
	        	);
	        }
	        $filename = lang('invoices');
        } else if ($type == 'customers') {
        	$appconfig = get_appconfig();
        	foreach ($q as $data) {
	        	$export[] = array(
	        		lang('customer') => $data['customer_number'] ? $data['customer_number'] : $data['custid'],
	        		lang('type') => $data['type'],
	        		lang('created') => $data['created'],
	        		lang('companyname') => $data['company'] ? $data['company'] : $data['namesurname'],
	        		$appconfig['tax_label'].' '.lang('taxoffice') => $data['taxoffice'],
	        		$appconfig['tax_label'].' '.lang('vatnumber') => $data['taxnumber'],
	        		lang('ssn') => $data['ssn'],
	        		lang('executiveupdate') => $data['executive'],
	        		lang('address') => $data['address'],
	        		lang('zipcode') => $data['zipcode'],
	        		lang('country') => get_country($data['country']),
	        		lang('state') => get_state_name($data['state'],$data['state_id']),
	        		lang('city') => $data['city'],
	        		lang('town') => $data['town'],
	        		lang('phone') => $data['phone'],
	        		lang('fax') => $data['fax'],
	        		lang('email') => $data['email'],
	        		lang('web') => $data['web'],
	        		lang('riskstatus') => $data['risk'],
	        		lang('group') => $data['group'].' '.$data['groupname'],
	        	);
	        }
	        $filename = lang('customers');
        } else if ($type == 'expenses') {
        	foreach ($q as $data) {
        		if ( $data[ 'invoice_id' ] == NULL) {
					$billstatus = lang( 'notbilled' );
				} else {
					$billstatus = lang( 'billed' );
				}
				if( $data['internal'] == '1') {
					$customer = get_number('staff', $data[ 'staffid' ], 'staff','staff');
					$customername = $data['staffname'];
					$billstatus = lang( 'internal' );
				} else {
					$customer = get_number('customers', $data[ 'custid' ], 'customer','customer');
					$customername = $data['company'] ? $data['company'] : $data['namesurname'];
				}
	        	$export[] = array(
	        		lang('expense') => $data['expense_number'] ? $data['expense_number'] : $data['expid'],
	        		lang('category') => $data['category'],
	        		lang('type') => ($data['internal'] == '1') ? lang('internal') : '',
	        		lang('customer') => $customername.' ('. $customer .')',
	        		lang('status') => $billstatus,
	        		lang('date') => $data['date'],
	        		lang('amount') => $data['amount'],
	        		lang('payment_account') => $data['payment_account'],
	        	);
	        }
	        $filename = lang('expenses');
        } else if ($type == 'proposals') {
        	foreach ($q as $data) {
        		switch ( $data[ 'status_id' ] ) {
					case '0':
						$status = lang( 'quote' ).' '.lang( 'request' );
						break;
					case '1':
						$status = lang( 'draft' );
						break;
					case '2':
						$status = lang( 'sent' );
						break;
					case '3':
						$status = lang( 'open' );
						break;
					case '4':
						$status = lang( 'revised' );
						break;
					case '5':
						$status = lang( 'declined' );
						break;
					case '6':
						$status = lang( 'accepted' );
						break;
					default:
						$status = lang( 'open' );
						break;
				};
	        	$export[] = array(
	        		lang('proposal') => $data['proposal_number'] ? $data['proposal_number'] : $data['proposalid'],
	        		lang('assignedstaff') => $data['assignedstaff'],
	        		lang('subject') => $data['subject'],
	        		lang('created') => $data['created'],
	        		lang('opentill') => $data['opentill'],
	        		lang('status') => $status,
	        		lang('total') => $data['sub_total'],
	        	);
	        }
	        $filename = lang('proposals');
        } else if ($type == 'deposits') {
        	foreach ($q as $data) {
        		if ( $data[ 'status' ] == '1' ) {
					$billstatus = lang( 'paid' ) and $color = 'success';
				} else if( $data[ 'status' ] == '0' ) {
					$billstatus = lang( 'unpaid' ) and $color = 'danger';
				} else {
					$billstatus = lang( 'internal' ) and $color = 'success';
				}
				if( $data['status'] == '2') {
					$customer = get_number('staff', $data[ 'staffid' ], 'staff','staff');
					$customername = $data['staffname'];
				} else {
					$customer = get_number('customers', $data[ 'custid' ], 'customer','customer');
					$customername = $data['company'] ? $data['company'] : $data['namesurname'];
				}
	        	$export[] = array(
	        		lang('deposits') =>get_number('deposits', $data[ 'depid' ], 'deposit','deposit'),
	        		lang('title') => $data['title'],
	        		lang('category') => $data['category'],
	        		lang('customer') => $customername.' ('.$customer.')',
	        		lang('status') => $billstatus,
	        		lang('date') => $data['depdate'],
	        		lang('amount') => $data['total'],
	        		lang('create') => $data['staffname'],
	        		lang('payment_account') => $data['payment_account'],
	        	);
	        }
	        $filename = lang('deposits');
        } else if ($type == 'orders') {
        	foreach ($q as $data) {
        		switch ( $data[ 'status_id' ] ) {
					case '1':
						$status = lang( 'draft' );
						break;
					case '2':
						$status = lang( 'sent' );
						break;
					case '3':
						$status = lang( 'open' );
						break;
					case '4':
						$status = lang( 'revised' );
						break;
					case '5':
						$status = lang( 'declined' );
						break;
					case '6':
						$status = lang( 'accepted' );
						break;
					default: 
						$status = lang( 'open' );
						break;
				};
				if($data['relation_type'] == 'customer') {
					$customer_number = get_number('customers', $data[ 'customerid' ], 'customer','customer');
					$customer = $data['company'] ? $data['company'] : $data['namesurname'];
				} else {
					$customer_number = get_number('leads', $data[ 'leadid' ], 'lead','lead');
					$customer = $data['leadname'];
				}
        		$export[] = array(
        			lang('order') => get_number('orders', $data[ 'orderid' ], 'order','order'),
        			lang('subject') => $data['subject'],
        			lang('customer').'/'.lang('lead') => $customer .' ('. $customer_number .')',
        			lang('assigned') => $data['staffname'],
        			lang('status') => $status,
        			lang('issuance_date') => $data['orderdate'],
        			lang('opentill') => $data['opentill'],
        			lang('total') => $data['total']
         		);
        	}
        	$filename = lang('orders');
        } else if ($type == 'vendors') {
        	foreach ($q as $data) { 
	        	$this->db->select_sum( 'total' )->from( 'purchases' )->where( '(status_id = 3 AND vendor_id = ' . $data[ 'vendorid' ] . ') ' );
				$total_unpaid_invoice_amount = $this->db->get()->row()->total;
				$this->db->select_sum( 'total' )->from( 'purchases' )->where( '(status_id = 2 AND vendor_id = ' . $data[ 'vendorid' ] . ') ' );
				$total_paid_invoice_amount = $this->db->get()->row()->total;
				$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(transactiontype = 0 AND vendor_id = ' . $data[ 'vendorid' ] . ') ' );
				$total_paid_amount = $this->db->get()->row()->amount;
				$export[] = array(
        			lang('vendor') => get_number('vendors', $data[ 'vendorid' ], 'vendor','vendor'),
        			lang('name') => $data['company'],
        			lang('group').' '.lang('name') => $data['groupname'],
        			lang('email') => $data['email'],
        			lang('country') => get_country($data['country_id']),
        			lang('amount') => $total_unpaid_invoice_amount - $total_paid_amount + $total_paid_invoice_amount,
 				);
        	}
        	$filename = lang('vendors');
        } else if ($type == 'purchases') {
        	foreach ($q as $data) { 
        		$vendorname = $data['vendorname'];
        		$totalx = $data[ 'total' ];
				$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(purchase_id =' . $data[ 'id' ] . ') ' );
				$paytotal = $this->db->get();
				$balance = $totalx - $paytotal->row()->amount;
				if ( $balance > 0 ) {
					$purchasesstatus = '';
				} else $purchasesstatus = lang( 'paidinv' );
				if ( $paytotal->row()->amount < $data[ 'total' ] && $paytotal->row()->amount > 0 && $data[ 'status_id' ] == 3 ) {
					$purchasesstatus = lang( 'partial' );
				} else {
					if ( $paytotal->row()->amount < $data[ 'total' ] && $paytotal->row()->amount > 0 ) {
						$purchasesstatus = lang( 'partial' );
					}
					if ( $data[ 'status_id' ] == 3 ) {
						$purchasesstatus = lang( 'unpaid' );
					}
				}
				if ( $data[ 'status_id' ] == 1 ) {
					$purchasesstatus = lang( 'draft' );
				}
				if ( $data[ 'status_id' ] == 4 ) {
					$purchasesstatus = lang( 'cancelled' );
				}
				$export[] = array(
	        		lang('purchase') => get_number("purchases",$data['id'],'purchase','purchase'), 
	        		lang('serie') => $data['serie'],
	        		lang('vendor') => $vendorname.' ('. get_number('vendors', $data[ 'vendorid' ], 'vendor','vendor').')',
	        		lang('issuance_date') => $data['issuance_date'],
	        		lang('duedate') => $data['duedate'],
	        		lang('amount') => $data['total'],
	        		lang('status') => $purchasesstatus,
	        	);
         	}
         	$filename = lang('purchases');
        } else if ($type == 'contacts') {
        	foreach ($q as $data) { 
        		$customer = $data['company'] ? $data['company'] : $data['namesurname'];
        		$export[] = array(
	        		lang('name') => $data['name'].' '.$data['surname'],
					lang('email') => $data[ 'email' ],
					lang('contactmobile').'/'.lang('phone') => $data['mobile'] ? $data['mobile'] : $data['phone'],
					lang('customer') => $customer . ' (' . get_number('customers', $data[ 'customerid' ], 'customer','customer') .')',  
				);
        	}
        	$filename = lang('contacts');
        } else if ($type == 'tasks') {
        	foreach ($q as $data) {
        		$project = $data['projectname'];
        		$task = $data[ 'taskname' ];
        		switch ( $data[ 'status_id' ] ) { 
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
				switch ( $data[ 'priority' ] ) {
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
        		$export[] = array(
					lang('task') => $task .' ('. get_number('tasks',$data['taskid'],'task','task'),
					lang('project') => $project.' ('.get_number('projects',$data['projectid'],'project','project').')', 
					lang('startdate') => date(get_dateFormat(), strtotime($data['startdate'])),
					lang('duedate') => date(get_dateFormat(), strtotime($data['duedate'])),
					lang('priority') => $priority,
					lang('status') => $status,
					lang('assigned') => $data['staffname'],
        		);
        	}
        	$filename = lang('tasks');
        } else if ($type == 'leads') {
        	foreach ($q as $data) {
        		$lead = $data[ 'leadname' ];
        		$export[] = array(
        			lang('lead') => $lead.' ('.get_number('leads', $data[ 'id' ], 'lead','lead').')',
        			lang('companyname') => $data[ 'company' ],
					lang('phone') => $data[ 'leadphone' ],
					lang('address') => $data['address'],
	        		lang('zipcode') => $data['zip'],
	        		lang('country') => get_country($data['country_id']),
	        		lang('state') => get_state_name($data['state'],$data['state_id']),
	        		lang('city') => $data['city'],
	        		lang('email') => $data['email'],
	        		lang('web') => $data['website'],
					lang('status') => $data[ 'statusname' ]?$data[ 'statusname' ]:'',
					lang('source') => $data[ 'sourcename' ]?$data[ 'sourcename' ]:'',
					lang('assigned') => $data[ 'leadassigned' ],
        		);
        	}
        	$filename = lang('leads');
        } else if ($type == 'tickets') {
        	foreach ($q as $data) {
        		if ( $data[ 'type' ] == 0 ) {
					$customer = $data[ 'company' ];
				} else { 
					$customer = $data[ 'namesurname' ];
				}
				switch ( $data[ 'priority' ] ) {
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
				switch ( $data[ 'status_id' ] ) {
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
				$export[] = array(
					lang('ticket') => get_number('tickets', $data[ 'id' ], 'ticket','ticket'),
					lang('subject') => $data[ 'subject' ],
					lang('customer') => $customer.'('.(get_number('tickets', $data[ 'customer_id' ], 'ticket','ticket')).')',
					lang('contact') => '' . $data[ 'contactname' ] . ' ' . $data[ 'contactsurname' ] . '',
					lang('department') => $data[ 'department' ],
					lang('priority') => $priority,
					lang('status') => $status,
					lang('assigned') => $data[ 'staffmembername' ],
					lang('lastreply') => $data[ 'lastreply' ] ? date(get_dateTimeFormat(),strtotime($data[ 'lastreply' ])) : '',
				);
        	}
        	$filename = lang('tickets');
        } else if ($type == 'products') {
        	$appconfig = get_appconfig();
        	foreach ($q as $data) {
        		$export[] = array(
        			lang('product') => get_number('products', $data[ 'id' ], 'product','product'),
					lang('productcode') => $data['code'],
					lang('name') => $data[ 'productname' ],
					lang('description') => $data[ 'description' ],
					lang('category') => $data[ 'name' ],
					lang('purchaseprice') => $data[ 'purchase_price' ],
					lang('salesprice') => $data[ 'sale_price' ],
					$appconfig['tax_label'] => $data[ 'vat' ],
					lang('instock') => $data[ 'stock' ],
        		);
        	}
        	$filename = lang('products');
        } else if ($type == 'staff') {
        	foreach ($q as $data) {
        		if( $data['admin'] == '1' ) {
					$type = lang('admin'); 
				} else if ( $data['staffmember'] == '1' && $data['other'] == null) {
					$type = lang('staff');
				} else {
					$type = lang('other');
				}
				$export[] = array(
					lang('staff') => get_number('staff', $data[ 'id' ], 'staff','staff'),
					lang('name') => $data[ 'staffname' ],
					lang('department') => $data[ 'department' ],
					lang('phone') => $data[ 'phone' ],
					lang('email') => $data[ 'email' ],
					lang('type') => $type,
					lang('address') => $data[ 'address' ],
				);
        	}
        	$filename = lang('staff');
        } else if ($type == 'projects') {
        	foreach ($q as $data) {
        		switch ( $data[ 'status' ] ) {
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
					default:
						$status = lang( 'started' );
						break;
				}
				if($data[ 'template' ] == '1')	{
					$customer = lang('template');
				} else {
					$customer = ($data['customercompany'])?$data['customercompany']:$data['namesurname'];
				}
				$members = $this->Projects_Model->get_members_index( $data['id'] );
				$staff =array();
				foreach ($members as $member) {
				 	$staff[] = get_number('staff', $member[ 'staff_id' ], 'staff','staff');
				}
				$export[] = array(
					lang('project') => get_number('projects', $data[ 'id' ], 'project','project'),
					lang('name') => $data[ 'name' ],
					lang('customer') => $customer,
					lang('project_start_date') => date(get_dateFormat(), strtotime($data['start_date'])),
					lang('project_end_date') => date(get_dateFormat(), strtotime($data['deadline'])),
					lang('project_value') => $data[ 'projectvalue' ],
					lang('status') => $status,
					lang('project').' '.lang('members') =>implode(',', $staff),
        		);
        	}
        	$filename = lang('projects');
        }
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"$filename".".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $report = fopen('php://output', 'w');
        $field = array_keys($export[0]);
        $lang = array();
        foreach ($field as $lg) {
        	$lang [] = $lg;
        }
        fputcsv( $report,  $lang);
        foreach ($export as $row) {
            fputcsv($report, $row);
        }
        fclose($report);
	}

	function generatePdf() {
		$type = $this->input->post('type');
		$period = $this->input->post('period');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
		if (!is_dir('uploads/files/reports')) {
			mkdir('./uploads/files/reports', 0777, true);
		}
		$data = array();
		if( $type == 'invoices' ) {
			$data['results'] = $this->Report_Model->get_all_invoices();
		} else if ( $type == 'expenses' ) {
			$data['results'] = $this->Report_Model->get_all_expenses();
		} else if ( $type == 'customers') {
			$data['results'] = $this->Report_Model->get_all_customers();
		} else if ( $type == 'proposals') {
			$data['results'] = $this->Report_Model->get_all_proposals();
		} else if ( $type == 'deposits') {
			$data['results'] = $this->Report_Model->get_all_deposits();
		} else if ( $type == 'orders') {
			$data['results'] = $this->Report_Model->get_all_orders();
		} else if ( $type == 'vendors') {
			$data['results'] = $this->Report_Model->get_all_vendors();
		} else if ( $type == 'purchases') {
			$data['results'] = $this->Report_Model->get_all_purchases();
		} else if ( $type == 'contacts') {
			$data['results'] = $this->Report_Model->get_all_contacts();
		} else if ( $type == 'tasks') {
			$data['results'] = $this->Report_Model->get_all_tasks();
		} else if ( $type == 'leads') {
			$data['results'] = $this->Report_Model->get_all_leads();
		} else if ( $type == 'tickets') {
			$data['results'] = $this->Report_Model->get_all_tickets();
		} else if ( $type == 'products') {
			$data['results'] = $this->Report_Model->get_all_products();
		} else if ( $type == 'staff') {
			$data['results'] = $this->Report_Model->get_all_staff();
		} else if ( $type == 'projects') {
			$data['results'] = $this->Report_Model->get_all_projects();
		}
		$filename = $type.'.pdf';
		$data['type'] = $type;
		$html = $this->load->view('report/pdf', $data, TRUE);
		$this->load->library( 'dom' );
		$this->dompdf->loadHtml( $html );
		$this->dompdf->set_option('isRemoteEnabled', TRUE );
		$this->dompdf->setPaper('A4', 'portrait' );
		$this->dompdf->render();
		$output = $this->dompdf->output();
		file_put_contents( 'uploads/files/reports/'. $filename . '', $output ); 
		if ($output) {
			$result = array(
				'success' => true,
				'file_name' => $filename,
			);
			echo json_encode( $result );
		}
	}
}