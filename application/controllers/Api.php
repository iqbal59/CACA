<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
header( 'Access-Control-Allow-Origin: *' );
class Api extends CIUIS_Controller {

	function index() {
		echo 'Ciuis RestAPI Service';
	}

	function settings() {
		$settings = $this->Settings_Model->get_settings_ciuis();
		$settings['smtppassoword'] = '********';
		$settings['timers'] = $this->Settings_Model->if_timer();
		$settings['newnotification'] = $this->Notifications_Model->newnotification();
		echo json_encode( $settings ); 
	}

	function settings_detail() {
		$settings = $this->Settings_Model->get_settings_ciuis_origin();
		$settings['smtppassoword'] = '********';
		$settings['timers'] = $this->Settings_Model->if_timer();
		$settings['newnotification'] = $this->Notifications_Model->newnotification();
		echo json_encode( $settings );
	}

	function languages() {
		$languages = $this->Settings_Model->get_languages();
		$lang = array();
		foreach ($languages as $language) {
			$lang[] = array(
				'name' => lang($language['name']),
				'foldername' => $language['foldername'],
				'id' => $language['id'],
				'langcode' => $language['langcode']
			);
		}
		echo json_encode($lang);
	}

	function currencies() {
		$jsonstring = include( 'assets/json/currencies.json' );
		$obj = json_decode( $jsonstring );
		print_r( $obj[ 'Data' ] ); 
	}

	function timezones() {
		$jsonstring = include( 'assets/json/timezones.json' );
		$obj = json_decode( $jsonstring );
		print_r( $obj[ 'Data' ] ); 
	}

	function get_appconfig() {
		$configs = $this->db->get_where('appconfig', array())->result_array();
		$data = array();
		foreach ($configs as $config) {
			$data[$config['name']] = $config['value'];
		}
		echo json_encode($data);
	}

	function stats() {
		$otc = $this->Report_Model->otc();
		$yms = $this->Report_Model->yms();
		$bkt = $this->Report_Model->bkt();
		$ogt = $this->Report_Model->ogt();
		$pay = $this->Report_Model->pay();
		$exp = $this->Report_Model->exp();
		$bht = $this->Report_Model->bht();
		$ohc = $this->Report_Model->ohc();
		$oak = $this->Report_Model->oak();
		$akt = $this->Report_Model->akt();
		$mex = $this->Report_Model->mex();
		$pme = $this->Report_Model->pme();
		$ycr = $this->Report_Model->ycr();
		$oyc = $this->Report_Model->oyc();
		if ( $otc > 1 ) {
			$newticketmsg = lang( 'newtickets' );
		} else $newticketmsg = lang( 'newticket' );
		if ( $yms > 1 ) {
			$newcustomermsg = lang( 'newcustomers' );
		} else $newcustomermsg = lang( 'newcustomer' );
		if ( $bkt > $ogt ) {
			$todaysalescolor = 'default';
		} else {
			$todaysalescolor = 'danger';
		}
		$todayrate = $bkt - $ogt;
		if ( empty( $ogt ) ) {
			$todayrate = 'N/A';
		} else {
			if ($ogt != 0) {
				$todayrate = floor( $todayrate / $ogt * 100 );
			} 
		}
		if ( $bkt > $ogt ) {
			$todayicon = 'icon ion-arrow-up-c';
		} else {
			$todayicon = 'icon ion-arrow-down-c';
		}
		$netcashflow = ( $pay - $exp );
		if ( $bht > $ohc ) {
			$weekstat = 'default';
		} else {
			$weekstat = 'danger';
		}
		$weekrate = $bht - $ohc;
		if ( empty( $ohc ) ) {
			$weekrate = 'N/A';
		} else {
			if ($ohc != 0) {
				$weekrate = floor( $weekrate / $ohc * 100 );
			} 
		}
		if ( $bht > $ohc ) {
			$weekratestatus = lang( 'increase' );
		} else {
			$weekratestatus = lang( 'recession' );
		}
		if ( $akt > $oak ) {
			$montearncolor = 'success';
			$monicon = 'icon ion-arrow-up-c';
		} else {
			$montearncolor = 'danger';
			$monicon = 'icon ion-arrow-down-c';
		}
		$oao = $akt - $oak;
		if ( empty( $oak ) ) {
			$monmessage = '' . lang( 'notyet' ) . '';
		} else { 
			if($oak != 0) {
				$monmessage = floor( $oao / $oak * 100 );
			}
		}
		$time = date( "H" );
		$timezone = date( "e" );
		if ( $time < "12" ) {
			$daymessage = lang( 'goodmorning' );
			$dayimage = 'morning.png';
		} else if ( $time >= "12" && $time < "17" ) {
			$daymessage = lang( 'goodafternoon' );
			$dayimage = 'afternoon.png';
		} else if ( $time >= "17" && $time < "19" ) {
			$daymessage = lang( 'goodevening' );
			$dayimage = 'evening.png';
		} else if ( $time >= "19" ) {
			$daymessage = lang( 'goodnight' );
			$dayimage = 'night.png';
		}
		if ( $mex > $pme ) {
			$expensecolor = 'warning';
		} else {
			$expensecolor = 'danger';
		}
		if ( $mex > $pme ) {
			$expenseicon = 'icon ion-arrow-up-c';
		} else {
			$expenseicon = 'icon ion-arrow-down-c';
		}
		$expenses = $mex - $pme;
		if ( empty( $pme ) ) {
			$expensestatus = '' . lang( 'notyet' ) . '';
		} else {
			if ($pme != 0) {
				$expensestatus = floor( $expenses / $pme * 100 );
			}
		}
		if ( $ycr > $oyc ) {
			$yearcolor = 'success';
		} else {
			$yearcolor = 'danger';
		}
		if ( $ycr > $oyc ) {
			$yearicon = 'icon ion-arrow-up-c';
		} else {
			$yearicon = 'icon ion-arrow-down-c';
		}
		$yearly = $ycr - $oyc;
		$yearmessage = '' . lang( 'notyet' ) . '';
		if ( empty( $oyc ) ) {
			$yearmessage = '' . lang( 'notyet' ) . '';
		} else {
			if ($oyc != 0) {
				$yearmessage = floor( $yearly / $oyc * 100 );
			} else {
				$yearmessage = '' . lang( 'notyet' ) . '';
			}
		} 
		$stats = array(
			'mex' => $mex = $this->Report_Model->mex(),
			'pme' => $pme = $this->Report_Model->pme(),
			'bkt' => $bkt = $this->Report_Model->bkt(),
			'bht' => $bht = $this->Report_Model->bht(),
			'ogt' => $ogt = $this->Report_Model->ogt(),
			'ohc' => $ohc = $this->Report_Model->ohc(),
			'otc' => $otc = $this->Report_Model->otc(),
			'ycr' => $ycr = $this->Report_Model->ycr(),
			'oyc' => $oyc = $this->Report_Model->oyc(),
			'oft' => $oft = $this->Report_Model->oft(),
			'tef' => $tef = $this->Report_Model->tef(),
			'vgf' => $vgf = $this->Report_Model->vgf(),
			'tbs' => $tbs = $this->Report_Model->tbs(),
			'akt' => $akt = $this->Report_Model->akt(),
			'oak' => $oak = $this->Report_Model->oak(),
			'tfa' => $tfa = $this->Report_Model->tfa(),
			'yms' => $yms = $this->Report_Model->yms(),
			'ttc' => $ttc = $this->Report_Model->ttc(),
			'ipc' => $ipc = $this->Report_Model->ipc(),
			'atc' => $atc = $this->Report_Model->atc(),
			'ctc' => $ctc = $this->Report_Model->ctc(),
			'put' => $put = $this->Report_Model->put(),
			'pay' => $pay = $this->Report_Model->pay(),
			'exp' => $exp = $this->Report_Model->exp(),
			'twt' => $twt = $this->Report_Model->twt(),
			'clc' => $clc = $this->Report_Model->clc(),
			'mlc' => $mlc = $this->Report_Model->mlc(),
			'mtt' => $mtt = $this->Report_Model->mtt(),
			'mct' => $mct = $this->Report_Model->mct(),
			'ues' => $ues = $this->Report_Model->ues(),
			'myc' => $myc = $this->Report_Model->myc(),
			'tpz' => $tpz = $this->Report_Model->tpz(),
			'nsp' => $nsp = $this->Report_Model->nsp(),
			'sep' => $sep = $this->Report_Model->sep(),
			'pep' => $pep = $this->Report_Model->pep(),
			'cap' => $cap = $this->Report_Model->cap(),
			'cop' => $cop = $this->Report_Model->cop(),
			'tht' => $tht = $this->Report_Model->tht(),
			'total_incomings' => $this->Report_Model->total_incomings(),
			'total_outgoings' => $this->Report_Model->total_outgoings(),
			'not_started_percent' => $tpz > 0 ? number_format( ( $nsp * 100 ) / $tpz ) : 0,
			'started_percent' => $tpz > 0 ? number_format( ( $sep * 100 ) / $tpz ) : 0,
			'percentage_percent' => $tpz > 0 ? number_format( ( $pep * 100 ) / $tpz ) : 0,
			'cancelled_percent' => $tpz > 0 ? number_format( ( $cap * 100 ) / $tpz ) : 0,
			'complete_percent' => $tpz > 0 ? number_format( ( $cop * 100 ) / $tpz ) : 0,
			'totalpaym' => $this->Report_Model->totalpaym(),
			'incomings' => $this->Report_Model->incomings(),
			'outgoings' => $this->Report_Model->outgoings(),
			'ysy' => $ysy = ( $ttc > 0 ? number_format( ( $otc * 100 ) / $ttc ) : 0 ),
			'bsy' => $bsy = ( $ttc > 0 ? number_format( ( $ipc * 100 ) / $ttc ) : 0 ),
			'twy' => $twy = ( $ttc > 0 ? number_format( ( $atc * 100 ) / $ttc ) : 0 ),
			'iey' => $iey = ( $ttc > 0 ? number_format( ( $ctc * 100 ) / $ttc ) : 0 ),
			'ofy' => $ofy = ( $tfa > 0 ? number_format( ( $tef * 100 ) / $tfa ) : 0 ),
			'clp' => $clp = ( $mlc > 0 ? number_format( ( $clc * 100 ) / $mlc ) : 0 ),
			'mtp' => $mtp = ( $mtt > 0 ? number_format( ( $mct * 100 ) / $mtt ) : 0 ),
			'inp' => $inp = ( $put > 0 ? number_format( ( $pay * 100 ) / $put ) : 0 ),
			'ogp' => $ogp = ( $put > 0 ? number_format( ( $exp * 100 ) / $put ) : 0 ),
			'newticketmsg' => $newticketmsg,
			'newcustomermsg' => $newcustomermsg,
			'todaysalescolor' => $todaysalescolor,
			'todayrate' => $todayrate,
			'todayicon' => $todayicon,
			'netcashflow' => $netcashflow,
			'weekstat' => $weekstat,
			'weekrate' => $weekrate,
			'weekratestatus' => $weekratestatus,
			'daymessage' => $daymessage,
			'dayimage' => $dayimage,
			'montearncolor' => $montearncolor,
			'monicon' => $monicon,
			'monmessage' => $monmessage,
			'expensecolor' => $expensecolor,
			'expenseicon' => $expenseicon,
			'expensestatus' => $expensestatus,
			'yearcolor' => $yearcolor,
			'yearicon' => $yearicon,
			'yearmessage' => $yearmessage,
			'newnotification' => $this->Notifications_Model->newnotification(),
			'totaltasks' => $totaltasks = $this->Report_Model->totaltasks(),
			'opentasks' => $opentasks = $this->Report_Model->opentasks(),
			'inprogresstasks' => $inprogresstasks = $this->Report_Model->inprogresstasks(),
			'waitingtasks' => $waitingtasks = $this->Report_Model->waitingtasks(),
			'completetasks' => $completetasks = $this->Report_Model->completetasks(),
			'invoice_chart_by_status' => $invoice_chart_by_status = $this->Report_Model->invoice_chart_by_status(),
			'leads_to_win_by_leadsource' => $leads_to_win_by_leadsource = $this->Report_Model->leads_to_win_by_leadsource(),
			'leads_by_leadsource' => $leads_by_leadsource = $this->Report_Model->leads_by_leadsource(),
			'incomings_vs_outgoins' => $leads_by_leadsource = $this->Report_Model->incomings_vs_outgoins(),
			'expenses_by_categories' => $expenses_by_categories = $this->Report_Model->expenses_by_categories(),
			'top_selling_staff_chart' => $top_selling_staff_chart = $this->Report_Model->top_selling_staff_chart(),
			'weekly_sales' => $weekly_expense_chart = $this->Report_Model->weekly_sales(),
			'monthly_expenses' => $this->Report_Model->monthly_expenses(),
			'monthly_sales' => $this->Report_Model->monthly_sales(),
			//'weekly_expense_chart' => $this->Report_Model->weekly_expense_chart(),
			'months' => months(),
		);
		echo json_encode( $stats );
	}

	function weekly_dashboard_chart(){
		$weekly_dash_chart = array(
		'weekly_expenses' => $this->Report_Model->weekly_dashboard_chart(),
		);
		echo json_encode( $weekly_dash_chart );
	}

	function get_consultant_data() {
		if ($this->session->userdata('other')) {
			$months = array(
				mb_substr(lang( 'january' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'february' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'march' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'april' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'may' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'june' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'july' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'august' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'september' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'october' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'november' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'december' ), 0, 3, 'UTF-8')
			);
			$lang = array(
				'amount' => lang('amount'),
				'expenses' => lang('expenses'),
				'sales' => lang('sales'),
				'sales_vs_expenses' => lang('sales_vs_expenses')
			);
			$data['months_short'] = $months;
			$data['months'] = months();
			$data['lang'] = $lang;
			$data['totalInvoices'] = $this->Report_Model->totalData('invoices');
			$data['expenses'] = $this->Report_Model->totalData('expenses');
			$data['invoices_thisweek'] = $this->Report_Model->invoices_thisweek();
			$data['expenses_thisweek'] = $this->Report_Model->expenses_thisweek();
			$data['monthly_expenses'] = $this->Report_Model->monthly_expenses();
			$data['monthly_sales'] = $this->Report_Model->monthly_sales();
			echo json_encode($data);
		}
	}

	function user() {
		$id = $this->session->userdata( 'usr_id' );
		$user = $this->Staff_Model->get_staff( $id );
		$user_data = array(
			'id' => $user[ 'id' ],
			'role_id' => $user[ 'role_id' ],
			'language' => $user[ 'language' ],
			'name' => $user[ 'staffname' ],
			'avatar' => $user[ 'staffavatar' ],
			'department_id' => $user[ 'department_id' ],
			'phone' => $user[ 'phone' ],
			'email' => $user[ 'email' ],
			'root' => $user[ 'root' ],
			'admin' => $user[ 'admin' ],
			'staffmember' => $user[ 'staffmember' ],
			'last_login' => $user[ 'last_login' ],
			'inactive' => $user[ 'inactive' ],
			'appointment_availability' => $user[ 'appointment_availability' ],
		);
		echo json_encode( $user_data );
	}

	function projects() {
		$projects = $this->Projects_Model->get_all_projects();
		$data_projects = array();
		foreach ( $projects as $project ) {
			if (($project['staff_id'] == $this->session->usr_id) || ($this->Projects_Model->check_member($project['id'], $this->session->usr_id)) == 'true' || $this->Settings_Model->isAdmin() == 'true') {
				$settings = $this->Settings_Model->get_settings_ciuis();
				$totaltasks = $this->Report_Model->totalprojecttasks( $project[ 'id' ] );
				$opentasks = $this->Report_Model->openprojecttasks( $project[ 'id' ] );
				$completetasks = $this->Report_Model->completeprojecttasks( $project[ 'id' ] );
				$progress = ( $totaltasks > 0 ? number_format( ( $completetasks * 100 ) / $totaltasks ) : 0 );
				$project_id = $project[ 'id' ];
				switch ( $project[ 'status' ] ) {
					case '1':
						$projectstatus = 'notstarted';
						$icon = 'notstarted.png';
						$status = lang( 'notstarted' );
						break;
					case '2':
						$projectstatus = 'started';
						$icon = 'started.png';
						$status = lang( 'started' );
						break;
					case '3':
						$projectstatus = 'percentage';
						$icon = 'percentage.png';
						$status = lang( 'percentage' );
						break;
					case '4':
						$projectstatus = 'cancelled';
						$icon = 'cancelled.png';
						$status = lang( 'cancelled' );
						break;
					case '5':
						$projectstatus = 'complete';
						$icon = 'complete.png';
						$status = lang( 'complete' );
						break;
				}
				if ($project[ 'status' ] == '5') {
					$projectstatus = 'complete';
					$icon = 'complete.png';
					$status = lang( 'completed' );
					$progress = 100;
				}
				if ($project[ 'template' ] == '1') {
					$projectstatus = 'template';
				}
				switch ( $settings[ 'dateformat' ] ) {
					case 'yy.mm.dd':
						$startdate = _rdate( $project[ 'start_date' ] );
						break;
					case 'dd.mm.yy':
						$startdate = _udate( $project[ 'start_date' ] );
						break;
					case 'yy-mm-dd':
						$startdate = _mdate( $project[ 'start_date' ] );
						break;
					case 'dd-mm-yy':
						$startdate = _cdate( $project[ 'start_date' ] );
						break;
					case 'yy/mm/dd':
						$startdate = _zdate( $project[ 'start_date' ] );
						break;
					case 'dd/mm/yy':
						$startdate = _kdate( $project[ 'start_date' ] );
						break;
				};
				$customer = ($project['customercompany'])?$project['customercompany']:$project['namesurname'];
				$enddate = $project[ 'deadline' ];
				$current_date = new DateTime( date( 'Y-m-d' ), new DateTimeZone( 'Asia/Dhaka' ) );
				$end_date = new DateTime( "$enddate", new DateTimeZone( 'Asia/Dhaka' ) );
				$interval = $current_date->diff( $end_date );
				$leftdays = $interval->format( '%a day(s)' );
				$members = $this->Projects_Model->get_members_index( $project_id );
				$milestones = $this->Projects_Model->get_all_project_milestones( $project_id );
				$appconfig = get_appconfig();
				$data_projects[] = array(
					'id' => $project[ 'id' ],
					'project_id' => $project[ 'id' ],
					'name' => $project[ 'name' ],
					'pinned' => $project[ 'pinned' ],
					'value' => $project[ 'projectvalue' ],
					'tax' => $project[ 'tax' ],
					'template' => $project[ 'template' ],
					'status_id' => $project[ 'status' ],
					'progress' => $progress,
					'startdate' => $startdate,
					'leftdays' => $leftdays,
					'customer' => $customer,
					'customeremail' => $project[ 'customeremail' ],
					'status_icon' => $icon,
					'status' => $status,
					'status_class' => $projectstatus,
					'customer_id' => $project[ 'customer_id' ],
					'members' => $members,
					'milestones' => $milestones,
					lang('filterbystatus') => lang($projectstatus),
					lang('filterbycustomer') => $customer,
					'project_number' => get_number('projects', $project[ 'id' ], 'project','project'),
				);
			}
		};
		echo json_encode( $data_projects );
	}

	function notes() {
		$relation_type = $this->uri->segment( 3 );
		$relation_id = $this->uri->segment( 4 );
		$notes = $this->db->select( '*,staff.staffname as notestaff,notes.id as id ' )->join( 'staff', 'notes.addedfrom = staff.id', 'left' )->order_by('notes.id', 'desc')->get_where( 'notes', array( 'relation' => $relation_id, 'relation_type' => $relation_type ) )->result_array();
		$data_projectnotes = array();
		foreach ( $notes as $note ) {
			$data_projectnotes[] = array(
				'id' => $note[ 'id' ],
				'description' => $note[ 'description' ],
				'staffid' => $note[ 'addedfrom' ],
				'staff' => $note[ 'notestaff' ],
				'date' => _adate( $note[ 'created' ] ),
			);
		};
		echo json_encode( $data_projectnotes );
	}

	function discussions() {
		$relation_type = $this->uri->segment( 3 );
		$relation_id = $this->uri->segment( 4 );
		$discussions = $this->db->select( '*,contacts.name as discussion_contact_name, contacts.surname as discussion_contact_surname, staff.staffname as discussion_staff,discussions.id as id ' )->join( 'staff', 'discussions.staff_id = staff.id', 'left' )->join( 'contacts', 'discussions.contact_id = contacts.id', 'left' )->get_where( 'discussions', array( 'relation' => $relation_id, 'relation_type' => $relation_type ) )->result_array();
		$data_discussions = array();

		foreach ( $discussions as $discussion ) {
			$comments = $this->db->get_where( 'discussion_comments', array( 'discussion_id' => $discussion[ 'id' ] ) )->result_array();
			$data_discussions[] = array(
				'id' => $discussion[ 'id' ],
				'subject' => $discussion[ 'subject' ],
				'description' => $discussion[ 'description' ],
				'datecreated' => date( DATE_ISO8601, strtotime( $discussion[ 'datecreated' ] ) ),
				'staff_id' => $discussion[ 'staff_id' ],
				'staff' => $discussion[ 'discussion_staff' ],
				'contact_id' => $discussion[ 'contact_id' ],
				'contact' => '' . $discussion[ 'discussion_contact_name' ] . ' ' . $discussion[ 'discussion_contact_surname' ] . '',
				'comments' => $comments,
			);
		};
		echo json_encode( $data_discussions );
	}

	function discussion_comments( $id ) {
		$comments = $this->db->get_where( 'discussion_comments', array( 'discussion_id' => $id ) )->result_array();
		echo json_encode( $comments );
	}

	function weekly_incomings() {
		$allsales[] = $this->Report_Model->weekly_incomings();
		for ( $i = 0; $i < count( $allsales ); $i++ ) {
			foreach ( $allsales[ $i ] as $salesc ) {
				$salesday = date( 'l', strtotime( $salesc[ 'date' ] ) );
				$salestotal = $salesc[ 'total' ];
				$data_timelogs = array();
				foreach ( weekdays_git() as $dayc ) {
					if ( $salesday == $dayc ) {
						$total = $salestotal;
					} else $total = 0;
					$data_timelogs[] = array(
						'day' => $dayc,
						'amount' => $total,
						'type' => 'incoming',
					);
				}

			}
		}
		echo json_encode( $data_timelogs );
	}

	function milestones() {
		$milestones = $this->Projects_Model->get_all_milestones();
		$data_milestones = array();
		foreach ( $milestones as $milestone ) {
			$data_milestones[] = array(
				'id' => $milestone[ 'id' ],
				'milestone_id' => $milestone[ 'id' ],
				'name' => $milestone[ 'name' ],
				'project_id' => $milestone[ 'project_id' ],
			);
		};
		echo json_encode( $data_milestones );
	}

	function staff() {
		$staffs = $this->Staff_Model->get_all_staff();
		$data_staffs = array();
		foreach ( $staffs as $staff ) {
			$data_staffs[] = array(
				'id' => $staff[ 'id' ],
				'name' => $staff[ 'staffname' ],
				'email' => $staff[ 'email' ],
				'staff_number' => get_number('staff', $staff[ 'id' ], 'staff','staff'),
				
			);
		};
		echo json_encode( $data_staffs );
	}

	function departments() {
		$departments = $this->Settings_Model->get_departments();
		$data_departments = array();
		foreach ( $departments as $department ) {
			$data_departments[] = array(
				'id' => $department[ 'id' ],
				'name' => $department[ 'name' ],
			);
		};
		echo json_encode( $data_departments );
	}

	function expenses_by_relation() {
		$relation_type = $this->uri->segment( 3 );
		$relation_id = $this->uri->segment( 4 );
		$expenses = $this->Expenses_Model->get_all_expenses_by_relation( $relation_type, $relation_id );
		$data_expenses = array();
		foreach ( $expenses as $expense ) {
			$settings = $this->Settings_Model->get_settings_ciuis();
			switch ( $settings[ 'dateformat' ] ) {
				case 'yy.mm.dd':
					$expensedate = _rdate( $expense[ 'date' ] );
					break;
				case 'dd.mm.yy':
					$expensedate = _udate( $expense[ 'date' ] );
					break;
				case 'yy-mm-dd':
					$expensedate = _mdate( $expense[ 'date' ] );
					break;
				case 'dd-mm-yy':
					$expensedate = _cdate( $expense[ 'date' ] );
					break;
				case 'yy/mm/dd':
					$expensedate = _zdate( $expense[ 'date' ] );
					break;
				case 'dd/mm/yy':
					$expensedate = _kdate( $expense[ 'date' ] ); 
					break;
			};
			if ( $expense[ 'invoice_id' ] == NULL ) {
				$billstatus = lang( 'notbilled' )and $color = 'warning'
				and $billstatus_code = 'false';
			} else $billstatus = lang( 'billed' )and $color = 'success'
			and $billstatus_code = 'true';
			if ( $expense[ 'customer_id' ] != 0 ) {
				$billable = 'true';
			} else {
				$billable = 'false';
			}
			if ( $expense[ 'internal' ] == '1') {
				$billstatus = lang( 'internal' ) and $color = 'success';
			}
			$appconfig = get_appconfig();
			$data_expenses[] = array(
				'id' => $expense[ 'id' ],
				'title' => $expense[ 'title' ],
				'prefix' => $appconfig['expense_prefix'],
				'longid' => get_number('expenses', $expense[ 'id' ], 'expense','expense'),
				'amount' => $expense[ 'amount' ],
				'staff' => $expense[ 'staff' ],
				'category' => $expense[ 'category' ],
				'billstatus' => $billstatus,
				'billstatus_code' => $billstatus_code,
				'color' => $color,
				'billable' => $billable,
				'date' => $expensedate,
			);
		};
		echo json_encode( $data_expenses );
	}

	function expensescategories() {
		$expensescategories = $this->Expenses_Model->get_all_expensecat();
		$data_expensescategories = array();
		foreach ( $expensescategories as $category ) {
			$catid = $category[ 'id' ];
			$amountby = $this->Report_Model->expenses_amount_by_category( $catid );
			if ( $amountby != NULL ) {
				$amtbc = $amountby;
			} else $amtbc = 0;
			$percent = $this->Report_Model->expenses_percent_by_category( $catid );
			$data_expensescategories[] = array(
				'id' => $category[ 'id' ],
				'name' => $category[ 'name' ],
				'description' => $category[ 'description' ],
				'amountby' => $amtbc,
				'percent' => $percent,
			);
		};
		echo json_encode( $data_expensescategories );
	}

	function proposals() {
		$proposals = array();
		if ( $this->Privileges_Model->check_privilege( 'proposals', 'all' ) ) {
			$proposals = $this->Proposals_Model->get_all_proposals_by_privileges();
		} else if ( $this->Privileges_Model->check_privilege( 'proposals', 'own' ) ) {
			$proposals = $this->Proposals_Model->get_all_proposals_by_privileges($this->session->usr_id);
		}
		$data_proposals = array();
		foreach ( $proposals as $proposal ) {
			$pro = $this->Proposals_Model->get_proposals( $proposal[ 'id' ], $proposal[ 'relation_type' ] );
			if ( $pro[ 'relation_type' ] == 'customer' ) {
				if ( ($pro[ 'customercompany' ] === NULL) || ($pro[ 'customercompany' ] == '') ) {
					$customer = $pro[ 'namesurname' ];
					$customer_email = $pro['toemail'];
				} else {
					$customer = $pro[ 'customercompany' ];
					$customer_email = $pro['toemail'];
				}
			}
			if ( $pro[ 'relation_type' ] == 'lead' ) {
				$customer = $pro[ 'leadname' ];
				$customer_email = $pro['toemail'];
			}
			$settings = $this->Settings_Model->get_settings_ciuis();
			switch ( $settings[ 'dateformat' ] ) {
				case 'yy.mm.dd':
					$date = _rdate( $proposal[ 'date' ] );
					$opentill = _rdate( $proposal[ 'opentill' ] );
					break;
				case 'dd.mm.yy':
					$date = _udate( $proposal[ 'date' ] );
					$opentill = _udate( $proposal[ 'opentill' ] );
					break;
				case 'yy-mm-dd':
					$date = _mdate( $proposal[ 'date' ] );
					$opentill = _mdate( $proposal[ 'opentill' ] );
					break;
				case 'dd-mm-yy':
					$date = _cdate( $proposal[ 'date' ] );
					$opentill = _cdate( $proposal[ 'opentill' ] );
					break;
				case 'yy/mm/dd':
					$date = _zdate( $proposal[ 'date' ] );
					$opentill = _zdate( $proposal[ 'opentill' ] );
					break;
				case 'dd/mm/yy':
					$date = _kdate( $proposal[ 'date' ] );
					$opentill = _kdate( $proposal[ 'opentill' ] );
					break;
			};
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

			};
			$appconfig = get_appconfig();
			$data_proposals[] = array(
				'id' => $proposal[ 'id' ],
				'assigned' => $proposal[ 'assigned' ],
				'prefix' => $appconfig['proposal_prefix'],
				'longid' => get_number('proposals', $proposal[ 'id' ], 'proposal','proposal'),
				'subject' => $proposal[ 'subject' ],
				'customer' => $customer,
				'relation' => $proposal[ 'relation' ],
				'date' => $date,
				'opentill' => $opentill,
				'status' => $status,
				'status_id' => $proposal[ 'status_id' ],
				'staff' => $proposal[ 'staffmembername' ],
				'staffavatar' => $proposal[ 'staffavatar' ],
				'total' => (float)$proposal[ 'total' ],
				'class' => $class,
				'relation_type' => $proposal[ 'relation_type' ],
				'customer_email' => $customer_email,
				'' . lang( 'relationtype' ) . '' => $proposal[ 'relation_type' ],
				'' . lang( 'filterbystatus' ) . '' => $status,
				'' . lang( 'filterbycustomer' ) . '' => $customer,
				'' . lang( 'filterbyassigned' ) . '' => $proposal[ 'staffmembername' ],
			);
		};
		echo json_encode( $data_proposals );
	}

	function invoices() {
		$invoices = array();
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'all' ) ) {
			$invoices = $this->Invoices_Model->get_all_invoices_by_privileges();
		} else if ( $this->Privileges_Model->check_privilege( 'invoices', 'own' ) ) {
			$invoices = $this->Invoices_Model->get_all_invoices_by_privileges($this->session->usr_id);
		} 
		$data_invoices = array();
		foreach ( $invoices as $invoice ) {
			$settings = $this->Settings_Model->get_settings_ciuis();
			switch ( $settings[ 'dateformat' ] ) {
				case 'yy.mm.dd':
					$created = _rdate( $invoice[ 'created' ] );
					$duedate = _rdate( $invoice[ 'duedate' ] );
					break;
				case 'dd.mm.yy':
					$created = _udate( $invoice[ 'created' ] );
					$duedate = _udate( $invoice[ 'duedate' ] );
					break;
				case 'yy-mm-dd':
					$created = _mdate( $invoice[ 'created' ] );
					$duedate = _mdate( $invoice[ 'duedate' ] );
					break;
				case 'dd-mm-yy':
					$created = _cdate( $invoice[ 'created' ] );
					$duedate = _cdate( $invoice[ 'duedate' ] );
					break;
				case 'yy/mm/dd':
					$created = _zdate( $invoice[ 'created' ] );
					$duedate = _zdate( $invoice[ 'duedate' ] );
					break;
				case 'dd/mm/yy':
					$created = _kdate( $invoice[ 'created' ] );
					$duedate = _kdate( $invoice[ 'duedate' ] );
					break;
			};
			if ( $invoice[ 'duedate' ] == 0000 - 00 - 00 ) {
				$realduedate = 'No Due Date';
			} else $realduedate = $duedate;
			$totalx = $invoice[ 'total' ];
			$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(invoice_id =' . $invoice[ 'id' ] . ') ' );
			$paytotal = $this->db->get();
			$balance = $totalx - $paytotal->row()->amount;
			if ( $balance > 0 ) {
				$invoicestatus = '';
			} else $invoicestatus = lang( 'paidinv' );
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
			$appconfig = get_appconfig();
			$data_invoices[] = array(
				'id' => $invoice[ 'id' ],
				'prefix' => $appconfig['inv_prefix'], 
				'longid' => get_number('invoices', $invoice[ 'id' ], 'invoice','inv'),
				'created' => $created,
				'duedate' => $realduedate,
				'customer' => $customer,
				'customer_id' => $invoice[ 'customer_id' ],
				'recurring_status' => $invoice[ 'recurring_status' ] == '0' ? true : false,
				'staff_id' => $invoice[ 'staff_id' ],
				'total' => (float)$invoice[ 'total' ],
				'status' => $invoicestatus,
				'color' => $color,
				'' . lang( 'filterbystatus' ) . '' => $invoicestatus,
				'' . lang( 'filterbycustomer' ) . '' => $customer,
			);
		};
		echo json_encode( $data_invoices );
	}

	function dueinvoices() {
		$dueinvoices = array();
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'all' ) ) {
			$dueinvoices = $this->Invoices_Model->dueinvoices();
		} else if ($this->Privileges_Model->check_privilege( 'invoices', 'own') ) {
			$dueinvoices = $this->Invoices_Model->dueinvoices_by_staff();	
		}
		if($dueinvoices) {
			$data_dueinvoices = array();
			foreach ( $dueinvoices as $invoice ) {
				if ( $invoice[ 'type' ] == 1 ) {
					$customer = $invoice[ 'individual' ];
				} else $customer = $invoice[ 'customercompany' ];
				$data_dueinvoices[] = array(
					'id' => $invoice[ 'id' ],
					'total' => $invoice[ 'total' ],
					'customer' => $customer,
				);
			};
			echo json_encode( $data_dueinvoices );
		}
	}

	function overdueinvoices() {
		$overdueinvoices = array();
		if ( $this->Privileges_Model->check_privilege( 'invoices', 'all' ) ) {
			$overdueinvoices = $this->Invoices_Model->overdueinvoices();
		} else if ($this->Privileges_Model->check_privilege( 'invoices', 'own') ) {
			$overdueinvoices = $this->Invoices_Model->overdueinvoices_by_staff();
		}
		if($overdueinvoices) {
			$data_overdueinvoices = array();
			foreach ( $overdueinvoices as $invoice ) {
				if ( $invoice[ 'type' ] == 1 ) {
					$customer = $invoice[ 'individual' ];
				} else $customer = $invoice[ 'customercompany' ];
				$today = time();
				$duedate = strtotime( $invoice[ 'duedate' ] ); // or your date as well
				$created = strtotime( $invoice[ 'created' ] );
				$paymentday = $duedate - $created; // Calculate days left.
				$paymentx = $today - $created;
				$datepaymentnet = $paymentday - $paymentx;
				if ( $datepaymentnet < 0 ) {
					$status = '' . floor( $datepaymentnet / ( 60 * 60 * 24 ) ) . ' days';
				};
				$data_overdueinvoices[] = array(
					'id' => $invoice[ 'id' ],
					'total' => $invoice[ 'total' ],
					'customer' => $customer,
					'status' => $status,
				);
			};
			echo json_encode( $data_overdueinvoices );
		}
	}

	function reminders() {
		$reminders = $this->Trivia_Model->get_reminders();
		$data_reminders['public'] = $this->public_reminders();
		$data_reminders['private'] = array();
		foreach ( $reminders as $reminder ) {
			switch ( $reminder[ 'relation_type' ] ) {
				case 'event':
					$remindertitle = lang( 'eventreminder' );
					break;
				case 'lead':
					$remindertitle = lang( 'leadreminder' );
					break;
				case 'customer':
					$remindertitle = lang( 'customerreminder' );
					break;
				case 'invoice':
					$remindertitle = lang( 'invoicereminder' );
					break;
				case 'expense':
					$remindertitle = lang( 'expensereminder' );
					break;
				case 'ticket':
					$remindertitle = lang( 'ticketreminder' );
					break;
				case 'proposal':
					$remindertitle = lang( 'proposalreminder' );
					break;
			};
			$data_reminders['private'][] = array(
				'id' => $reminder[ 'id' ],
				'title' => $remindertitle,
				'date' => date( DATE_ISO8601, strtotime( $reminder[ 'date' ] ) ),
				'description' => $reminder[ 'description' ],
				'creator' => $reminder[ 'remindercreator' ],
			);
		};
		echo json_encode( $data_reminders );
	}

	function public_reminders() {
		$reminders_public = $this->Trivia_Model->get_event_public_reminders();
		$data_reminders = array();
		foreach ( $reminders_public as $reminder ) {
			switch ( $reminder[ 'relation_type' ] ) {
				case 'event':
					$remindertitle = lang( 'eventreminder' );
					break;
				case 'lead':
					$remindertitle = lang( 'leadreminder' );
					break;
				case 'customer':
					$remindertitle = lang( 'customerreminder' );
					break;
				case 'invoice':
					$remindertitle = lang( 'invoicereminder' );
					break;
				case 'expense':
					$remindertitle = lang( 'expensereminder' );
					break;
				case 'ticket':
					$remindertitle = lang( 'ticketreminder' );
					break;
				case 'proposal':
					$remindertitle = lang( 'proposalreminder' );
					break;
			};
			$data_reminders[] = array(
				'id' => $reminder[ 'id' ],
				'title' => $remindertitle,
				'date' => $reminder[ 'date' ],
				'description' => $reminder[ 'description' ],
				'creator' => $reminder[ 'remindercreator' ],
			);
		}
		return $data_reminders;
	}

	function reminders_by_type() {
		$relation_type = $this->uri->segment( 3 );
		$relation_id = $this->uri->segment( 4 );
		$reminders = $this->db->select( '*,staff.staffname as staff,staff.staffavatar as avatar,reminders.id as id ' )->join( 'staff', 'reminders.staff_id = staff.id', 'left' )->get_where( 'reminders', array( 'relation' => $relation_id, 'relation_type' => $relation_type ) )->result_array();
		$data_reminders = array();
		foreach ( $reminders as $reminder ) {
			$data_reminders[] = array(
				'id' => $reminder[ 'id' ],
				'date' => _adate( $reminder[ 'date' ] ),
				'description' => $reminder[ 'description' ],
				'creator' => $reminder[ 'staff' ],
				'avatar' => base_url( 'uploads/images/' . $reminder[ 'avatar' ] . '' ),
			);
		};
		echo json_encode( $data_reminders );
	}

	function notifications() {
		$notifications = $this->Notifications_Model->get_all_notifications();
		$data_notifications = array();
		foreach ( $notifications as $notification ) {
			switch ( $notification[ 'markread' ] ) {
				case 0:
					$read = true;
					break;
				case 1:
					$read = false;
					break;
			};
			$data_notifications[] = array(
				'id' => $notification[ 'notifyid' ],
				'target' => $notification[ 'target' ],
				'date' => tes_ciuis( $notification[ 'date' ] ),
				'detail' => $notification[ 'detail' ],
				'avatar' => $notification[ 'perres' ],
				'read' => $read,
			);
		};
		echo json_encode( $data_notifications );
	}

	function mark_read_ntf() {
		$this->db->where('public = "1" OR staff_id = '.$this->session->userdata('usr_id'));
		$this->db->update('notifications', array('markread' => 1));
		echo true;
	}

	function tickets() {
		$tickets = array();
		if ( $this->Privileges_Model->check_privilege( 'tickets', 'all' ) ) {
			$tickets = $this->Tickets_Model->get_all_tickets_by_privileges();
		} else if ( $this->Privileges_Model->check_privilege( 'tickets', 'own' ) ) {
			$tickets = $this->Tickets_Model->get_all_tickets_by_privileges($this->session->usr_id);
		}
		if($tickets) {
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
				};
				$data_tickets[] = array(
					'id' => $ticket[ 'id' ],
					'subject' => $ticket[ 'subject' ],
					'message' => $ticket[ 'message' ],
					'staff_id' => $ticket[ 'staff_id' ],
					'contactname' => '' . $ticket[ 'contactname' ] . ' ' . $ticket[ 'contactsurname' ] . '',
					'priority' => $priority,
					'priority_id' => $ticket[ 'priority' ],
					'lastreply' => $ticket[ 'lastreply' ]?(date(get_dateTimeFormat(), strtotime( $ticket[ 'lastreply' ]))):lang('n_a'),
					'status_id' => $ticket[ 'status_id' ],
					'customer_id' => $ticket[ 'customer_id' ],
					'contactemail' => $ticket['contactemail'],
					'ticket_number' => get_number('tickets', $ticket[ 'id' ], 'ticket','ticket'),
				);
			};
			echo json_encode( $data_tickets );
		}
	}

	function newtickets() {
		$newtickets = array();
		if ( $this->Privileges_Model->check_privilege( 'tickets', 'all' ) ) {
			$newtickets = $this->Tickets_Model->get_all_open_tickets();
		} else if ($this->Privileges_Model->check_privilege( 'tickets', 'own') ) {
			$newtickets = $this->Tickets_Model->get_all_open_tickets_by_staff();
		}
		if($newtickets) {
			$data_newtickets = array();
			foreach ( $newtickets as $ticket ) {
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
				};
				$data_newtickets[] = array(
					'id' => $ticket[ 'id' ],
					'subject' => $ticket[ 'subject' ],
					'contactsurname' => $ticket[ 'contactsurname' ],
					'contactname' => $ticket[ 'contactname' ],
					'priority' => $priority,
				);
			};
			echo json_encode( $data_newtickets );
		}
	}

	function transactions() {
		if (!isAdmin()) {
			$transactions = $this->Payments_Model->todaypayments_by_staff();
		} else {
			$transactions = $this->Payments_Model->todaypayments();
		}
		if($transactions) {
			$data_transactions = array();
			foreach ( $transactions as $transaction ) {
				switch ( $transaction[ 'transactiontype' ] ) {
					case '0':
						$type = 'paymenttoday';
						$icon = 'ion-log-in';
						$title = ($transaction['deposit_id'] ? lang('deposit') : lang( 'paymentistoday' ));
						break;
					case '1':
						$type = 'expensetoday';
						$icon = 'ion-log-out';
						$title = lang( 'expensetoday' );
						break;
				};
				$data_transactions[] = array(
					'id' => $transaction[ 'id' ],
					'amount' => $transaction[ 'amount' ],
					'type' => $type,
					'title' => $title,
					'icon' => $icon,
				);
			};
			echo json_encode( $data_transactions );
		}
	}

	function logs($loadMore='') {
		if (!isAdmin()) {
			if($loadMore){
				$logs = $this->Logs_Model->panel_last_logs_by_staff($loadMore);
			} else {
				$logs = $this->Logs_Model->panel_last_logs_by_staff();
			}
		} else {
			if($loadMore){
				$logs = $this->Logs_Model->panel_last_logs($loadMore);
			} else {
				$logs = $this->Logs_Model->panel_last_logs();
			}
		}
		$data_logs = array();
		foreach ( $logs as $log ) {
			$data_logs[] = array(
				'logdate' => date( DATE_ISO8601, strtotime( $log[ 'date' ] ) ),
				'date' => tes_ciuis( $log[ 'date' ] ),
				'detail' => $log[ 'detail' ],
				'customer_id' => $log[ 'customer_id' ],
				'project_id' => $log[ 'project_id' ],
				'staff_id' => $log[ 'staff_id' ],
			);
		};
		echo json_encode( $data_logs );
	}

	// function contacts() {
	// 	$contacts = $this->Contacts_Model->get_all_contacts();
	// 	$permissions = $this->Privileges_Model->get_all_common_permissions();
	// 	$privileges = $this->Privileges_Model->get_privileges();
	// 	$data_contacts = array();
	// 	foreach ( $contacts as $contact ) {
	// 		$arr = array();
	// 		foreach ( $privileges as $privilege ) {
	// 			if ( $privilege[ 'relation' ] == $contact[ 'id' ] && $privilege[ 'relation_type' ] == 'contact' ) {
	// 				array_push( $arr, $privilege[ 'permission_id' ] );
	// 			}
	// 		}
	// 		$data_privileges = array();
	// 		foreach ( $permissions as $permission ) {
	// 			$data_privileges[] = array(
	// 				'id' => $permission[ 'id' ],
	// 				'name' => '' . lang( $permission[ 'permission' ] ) . '',
	// 				'value' => '' . ( array_search( $permission[ 'id' ], $arr ) !== FALSE ) ? true : false . ''
	// 			);
	// 		}
	// 		$data_contacts[] = array(
	// 			'id' => $contact[ 'id' ],
	// 			'customer_id' => $contact[ 'customer_id' ],
	// 			'name' => '' . $contact[ 'name' ] . '',
	// 			'surname' => '' . $contact[ 'surname' ] . '',
	// 			'email' => $contact[ 'email' ],
	// 			'phone' => $contact[ 'phone' ],
	// 			'username' => $contact[ 'username' ],
	// 			'address' => $contact[ 'address' ],
	// 			'extension' => $contact[ 'extension' ],
	// 			'mobile' => $contact[ 'mobile' ],
	// 			'password' => $contact[ 'password' ],
	// 			'language' => $contact[ 'language' ],
	// 			'skype' => $contact[ 'skype' ],
	// 			'linkedin' => $contact[ 'linkedin' ],
	// 			'position' => $contact[ 'position' ],
	// 			'primary' => $contact[ 'primary' ],
	// 			'admin' => $contact[ 'admin' ],
	// 			'inactive' => $contact[ 'inactive' ],
	// 			'privileges' => $data_privileges,
	// 		);
	// 	};
	// 	echo json_encode( $data_contacts );
	// }

	function contact($id) {
		$contacts = $this->Contacts_Model->get_customer_contacts($id);
		$permissions = $this->Privileges_Model->get_all_common_permissions();
		$privileges = $this->Privileges_Model->get_privileges();
		$data_contacts = array();
		foreach ( $contacts as $contact ) {
			$arr = array();
			foreach ( $privileges as $privilege ) {
				if ( $privilege[ 'relation' ] == $contact[ 'id' ] && $privilege[ 'relation_type' ] == 'contact' ) {
					array_push( $arr, $privilege[ 'permission_id' ] );
				}
			}
			$data_privileges = array();
			foreach ( $permissions as $permission ) {
				$data_privileges[] = array(
					'id' => $permission[ 'id' ],
					'name' => '' . lang( $permission[ 'permission' ] ) . '',
					'value' => '' . ( array_search( $permission[ 'id' ], $arr ) !== FALSE ) ? true : false . ''
				);
			}
			$data_contacts[] = array(
				'id' => $contact[ 'id' ],
				'customer_id' => $contact[ 'customer_id' ],
				'name' => '' . $contact[ 'name' ] . '',
				'surname' => '' . $contact[ 'surname' ] . '',
				'email' => $contact[ 'email' ],
				'phone' => $contact[ 'phone' ],
				'username' => $contact[ 'username' ],
				'address' => $contact[ 'address' ],
				'extension' => $contact[ 'extension' ],
				'mobile' => $contact[ 'mobile' ],
				'password' => $contact[ 'password' ],
				'language' => $contact[ 'language' ],
				'skype' => $contact[ 'skype' ],
				'linkedin' => $contact[ 'linkedin' ],
				'position' => $contact[ 'position' ],
				'primary' => $contact[ 'primary' ],
				'admin' => $contact[ 'admin' ],
				'inactive' => $contact[ 'inactive' ],
				'privileges' => $data_privileges,
			);
		};
		echo json_encode( $data_contacts );
	}

	function contact_privileges( $id ) {
		$permissions = $this->Privileges_Model->get_all_common_permissions();
		$privileges = $this->Privileges_Model->get_privileges();
		$arr = array();
		foreach ( $privileges as $privilege ) {
			if ( $privilege[ 'relation' ] == $id && $privilege[ 'relation_type' ] == 'contact' ) {
				array_push( $arr, $privilege[ 'permission_id' ] );
			}
		}
		foreach ( $permissions as $permission ) {

			$data_privileges[] = array(
				'id' => $permission[ 'id' ],
				'name' => '' . lang( $permission[ 'permission' ] ) . '',
				'value' => '' . ( array_search( $permission[ 'id' ], $arr ) !== FALSE ) ? 'true' : 'false' . '',
			);
		}
		echo json_encode( $data_privileges );
	}

	function contact_permision( ) {
		$permissions = $this->Privileges_Model->get_all_common_permissions();
		$privileges = $this->Privileges_Model->get_privileges();
		$arr = array();
		foreach ( $privileges as $privilege ) {
			if (  $privilege[ 'relation_type' ] == 'contact' ) {
				array_push( $arr, $privilege[ 'permission_id' ] );
			}
		}
		foreach ( $permissions as $permission ) {

			$data_privileges[] = array(
				'id' => $permission[ 'id' ],
				'name' => '' . lang( $permission[ 'permission' ] ) . '',
				'value' => false ,
			);
		}
		echo json_encode( $data_privileges );
	}

	function customers() {
		$customers = array();
		if ( $this->Privileges_Model->check_privilege( 'customers', 'all' ) ) {
			$customers = $this->Customers_Model->get_all_customers();
		} else if ( $this->Privileges_Model->check_privilege( 'customers', 'own' ) ) {
			$customers = $this->Customers_Model->get_all_customers($this->session->usr_id);
		}
		$data_customers = array();
		foreach ( $customers as $customer ) {
			if ($customer[ 'type' ] == '0') {
				$name = $customer[ 'company' ];
				$type = lang( 'corporatecustomers' );
			} else {
				$name = $customer[ 'namesurname' ];
				$type = lang( 'individual' );
			}
			$this->db->select_sum( 'total' )->from( 'invoices' )->where( '(status_id = 3 AND customer_id = ' . $customer[ 'id' ] . ') ' );
			$total_unpaid_invoice_amount = $this->db->get()->row()->total;
			$this->db->select_sum( 'total' )->from( 'invoices' )->where( '(status_id = 2 AND customer_id = ' . $customer[ 'id' ] . ') ' );
			$total_paid_invoice_amount = $this->db->get()->row()->total;
			$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(transactiontype = 0 AND customer_id = ' . $customer[ 'id' ] . ') ' );
			$total_paid_amount = $this->db->get()->row()->amount;
			$contacts = $this->Contacts_Model->get_customer_contacts( $customer[ 'id' ] );
			$country = get_country($customer['country_id' ]);
			$billing_country = get_country($customer['billing_country']);
			$shipping_country = get_country($customer['shipping_country']);  
			$billing_state = get_state_name($customer['billing_state'],$customer['billing_state_id']);
			$shipping_state = get_state_name($customer['shipping_state'],$customer['shipping_state_id']); 
			$appconfig = get_appconfig();
			$data_customers[] = array(
				'id' => $customer[ 'id' ],
				'customer_id' => $customer[ 'id' ],
				'name' => $name,
				'address' => $customer[ 'address' ],
				'email' => $customer[ 'email' ],
				'phone' => $customer[ 'phone' ],
				'state_id' => $customer['state_id'],
				'billing_street' => $customer[ 'billing_street' ],
				'billing_city' => $customer[ 'billing_city' ],
				'billing_state' => $billing_state,
				'billing_state_id' => $customer['billing_state_id'],
				'billing_zip' => $customer[ 'billing_zip' ],
				'billing_country' => $billing_country,
				'billing_country_id' => $customer['billing_country'],
				'shipping_street' => $customer[ 'shipping_street' ],
				'shipping_city' => $customer[ 'shipping_city' ],
				'shipping_state' => $shipping_state,
				'shipping_state_id' => $customer['shipping_state_id'],
				'shipping_zip' => $customer[ 'shipping_zip' ],
				'shipping_country' => $shipping_country,
				'shipping_country_id' => $customer['shipping_country'],
				'customer_country' => $customer['country_id' ],
				'balance' => (float)($total_unpaid_invoice_amount - $total_paid_amount + $total_paid_invoice_amount),
				'default_payment_method' => $customer[ 'default_payment_method' ],
				'group_name' => $customer['name'],
				'group_id' => $customer['groupid'],
				'contacts' => $contacts,
				'' . lang( 'filterbytype' ) . '' => $type,
				'' . lang( 'filterbycountry' ) . '' => $country,
				'customer_number' => get_number('customers', $customer[ 'id' ], 'customer','customer'),
				
			);
		};
		echo json_encode( $data_customers );
	}

	function countries() {
		$jsonstring = include( 'assets/json/countries.json' );
		$obj = json_decode( $jsonstring );
		print_r( $obj[ 'Data' ] );
	}

	function get_states($countryId) {
		$states_data = get_states($countryId);
		print_r( json_encode($states_data) );
	}

	function events() {
		$events = $this->Events_Model->get_all_events();
		$data_events = array();
		foreach ( $events as $event ) {
			if ( $event[ 'end' ] < (date( " Y-m-d h:i:s" ) )) {
				$status = 'past';
			} else {
				$status = 'next';
			};
			$data_events[] = array(
				'day' => date( 'D', strtotime( $event[ 'start' ] ) ),
				'aday' => _dDay( $event[ 'start' ] ),
				'start' => _adate( $event[ 'start' ] ),
				'end' => _adate( $event[ 'end' ] ),
				'start_iso_date' => date(get_dateTimeFormat(), strtotime($event[ 'start' ])),
				'start_date' => date(get_dateFormat(),strtotime($event['start'])),
				'end_date' => date(get_dateFormat(),strtotime($event[ 'end' ])),
				'detail' => $event[ 'detail' ],
				'title' => $event[ 'title' ],
				'staff' => $event[ 'staff' ],
				'status' => $status,
				'id' => $event[ 'id' ],
				'color' => $event[ 'color' ]?$event[ 'color' ]:'#fff',
				'textColor' => $event[ 'color' ]?$event[ 'color' ]:'#fff',
				'date' => $event['start'],
				'event_type' => $event['event_type'],
				'eventTextColor' => '#fff',
				'draggable' => false,
				'editable' => false,
				'allDay' => true,
				'relation' => 'event',
			);
		};
		echo json_encode( $data_events );
	}

	function appointments() {
		$appointments = $this->Appointments_Model->get_all_appointments();
		$data_appointments = array();
		foreach ( $appointments as $appointment ) {
			if ( $appointment[ 'booking_date' ] < date( "Y-m-d" ) ) {
				$status = 'past';
			} else {
				$status = 'next';
			};
			$data_appointments[] = array(
				'id' => $appointment[ 'id' ],
				'day' => date( 'D', strtotime( $appointment[ 'booking_date' ] ) ),
				'aday' => _dDay( '' . $appointment[ 'booking_date' ] . ' ' . $appointment[ 'start_time' ] . '' ),
				'start' => _adate( '' . $appointment[ 'booking_date' ] . ' ' . $appointment[ 'start_time' ] . '' ),
				'start_iso_date' => date( DATE_ISO8601, strtotime( '' . $appointment[ 'booking_date' ] . ' ' . $appointment[ 'start_time' ] . '' ) ),
				'start_date' => $appointment[ 'booking_date' ],
				'title' => '' . $message = sprintf( lang( 'appointment_for' ), $appointment[ 'contact_name' ] ) . '',
				'staff' => $appointment[ 'staff' ],
				'contact' => '' . $appointment[ 'contact_name' ] . ' ' . $appointment[ 'contact_surname' ] . '',
				'status_class' => $status,
				'status' => $appointment[ 'status' ],
				'date' => date( DATE_ISO8601, strtotime( $appointment[ 'booking_date' ] ) ),
			);
		};
		echo json_encode( $data_appointments );
	}

	function all_appointments() {
		$appconfig = get_appconfig();
		$appointments = $this->Appointments_Model->get_all_confirmed_appointments();
		$data_appointments = array();
		foreach ( $appointments as $appointment ) {
			$data_appointments[] = array(
				'id' => $appointment[ 'id' ],
				'title' => '' . lang('appointment') . ' : ' . $appointment[ 'contact_name' ],
				'text' => '' . $message = sprintf( lang( 'appointment_for' ), $appointment[ 'contact_name' ] ) . '',
				'start' => '' . $appointment[ 'booking_date' ] . ' ' . $appointment[ 'start_time' ] . '',
				'end' => '' . $appointment[ 'booking_date' ] . ' ' . $appointment[ 'end_time' ] . '',
				'status' => $appointment[ 'status' ],
				'start_date' =>  '' . date(get_dateFormat(),strtotime($appointment[ 'booking_date' ]))  . ' ' . $appointment[ 'start_time' ] . '',
				'end_date' => '' . date(get_dateFormat(),strtotime($appointment[ 'booking_date' ])) . ' ' . $appointment[ 'end_time' ] . '',
				'relation' => 'appointment',
				'color' => $appconfig['appointment_color'],
				'staff' => $appointment[ 'staff' ],
			);
		};
		echo json_encode( $data_appointments );
	}

	function calendar_projects() {
		$projects = array();
		if( $this->Privileges_Model->check_privilege( 'projects', 'all' ) ){
			$projects = $this->Projects_Model->get_all_projects_by_privileges();
		} else if( $this->Privileges_Model->check_privilege( 'projects', 'own' ) ) {
			$projects = $this->Projects_Model->get_all_projects_by_privileges($this->session->usr_id);
		}
		$appconfig = get_appconfig();
		$data_projects = array();
		foreach ( $projects as $project ) {
			if (($project['staff_id'] == $this->session->usr_id) || ($this->Projects_Model->check_member($project['id'], $this->session->usr_id)) == 'true' || $this->Settings_Model->isAdmin() == 'true') {
				$data_projects[] = array(
					'id' => $project[ 'id' ],
					'title' => '' . get_number('projects', $project['id'], 'project', 'project') . ' : ' . $project[ 'name' ],
					'text' => $project[ 'description' ],
					'start' => $project[ 'start_date' ],
					'end' => $project[ 'deadline' ],
					'status' => $project[ 'id' ],
					'status_class' => $project[ 'id' ],
					'start_date' => date(get_dateFormat(),strtotime($project[ 'start_date' ])),
					'end_date' => date(get_dateFormat(),strtotime($project[ 'deadline' ])),
					'relation' => 'project',
					'color' => $appconfig['project_color'],
				);
			}
		};
		echo json_encode( $data_projects );
	}

	function calendar_tasks() {
		$tasks = array();
		if( $this->Privileges_Model->check_privilege( 'tasks', 'all' ) ){
			$tasks = $this->Tasks_Model->get_all_tasks_calendar();
		} else if( $this->Privileges_Model->check_privilege( 'tasks', 'own' ) ) {
			$tasks = $this->Tasks_Model->get_all_tasks_calendar($this->session->usr_id);
		}
		if($tasks) {
			$appconfig = get_appconfig();
			$data_tasks = array();
			foreach ( $tasks as $task ) {
					if ($task['priority'] == '1') {
						$task_priority = lang( 'low' );
					} elseif ($task['priority'] == '2') {
						$task_priority = lang( 'medium' );
					} elseif ($task['priority'] == '3') {
						$task_priority = lang( 'high' );
					}
					if ($task['status_id'] == '1') {
						$task_status = lang( 'open' );
					} elseif ($task['status_id'] == '2') {
						$task_status = lang( 'inprogress' );
					} elseif ($task['status_id'] == '3') {
						$task_status = lang( 'waiting' );
					} elseif ($task['status_id'] == '4') {
						$task_status = lang( 'complete' );
					} elseif ($task['status_id'] == '5') {
						$task_status = lang( 'cancelled' );
					}
				$data_tasks[] = array(
					'id' => $task[ 'id' ],
					'title' => '' . get_number('tasks', $task['id'], 'task', 'task') . ' : ' . $task[ 'name' ],
					'text' => $task[ 'description' ],
					'start' => $task[ 'startdate' ],
					'end' => $task[ 'duedate' ],
					'start_date' => date(get_dateFormat(),strtotime($task[ 'startdate' ])),
					'end_date' => date(get_dateFormat(),strtotime($task[ 'duedate' ])),
					'relation' => 'task',
					'color' => $appconfig['task_color'],
					'priority' => $task_priority,
					'status' => $task_status,
					'staff' => $task['staffname'],
				);
			};
			echo json_encode( $data_tasks );
		}
	}

	function get_google_events( $id ) {
		$staff = $this->Staff_Model->get_staff( $id );
		$str = file_get_contents( 'https://www.googleapis.com/calendar/v3/calendars/' . $staff[ 'google_calendar_id' ] . '/events?key=' . $staff[ 'google_calendar_api_key' ] . '' );
		$json = json_decode( $str, true );
		echo json_encode( $json[ 'items' ] );
	}

	function meetings() {
		$this->db->select( '*,staff.staffname as staff_name,customers.type as type, customers.company as customercompany,customers.namesurname as individual, meetings.id as id ' );
		$this->db->join( 'customers', 'meetings.customer_id = customers.id', 'left' );
		$this->db->join( 'staff', 'meetings.staff_id = staff.id', 'left' );
		$this->db->where( 'meetings.staff_id', $this->session->userdata( 'usr_id' ) );
		$meetings = $this->db->get_where( 'meetings' )->result_array();
		$data_meetings = array();
		foreach ( $meetings as $meet ) {
			if ( $meet[ 'type' ] == 1 ) {
				$customer = $meet[ 'individual' ];
			} else $customer = $meet[ 'customercompany' ];
			$data_meetings[] = array(
				'id' => $meet[ 'id' ],
				'title' => $meet[ 'title' ],
				'description' => $meet[ 'description' ],
				'date' => date( DATE_ISO8601, strtotime( '' . $meet[ 'date' ] . ' ' . $meet[ 'start' ] . '' ) ),
				'start' => $meet[ 'start' ],
				'end' => $meet[ 'end' ],
				'staff' => $meet[ 'staff_name' ],
				'customer' => $customer,
			);
		};
		echo json_encode( $data_meetings );
	}


	function todos() {
		$todos = $this->Trivia_Model->get_todos();
		$data_todo = array();
		foreach ( $todos as $todo ) {
			$data_todo[] = array(
				'id' => $todo[ 'id' ],
				'date' => date( DATE_ISO8601, strtotime( $todo[ 'date' ] ) ),
				'description' => $todo[ 'description' ],
			);
		};
		echo json_encode( $data_todo );
	}

	function donetodos() {
		$donetodos = $this->Trivia_Model->get_done_todos();
		$data_donetodos = array();
		foreach ( $donetodos as $donetodo ) {
			$data_donetodos[] = array(
				'id' => $donetodo[ 'id' ],
				'date' => date( DATE_ISO8601, strtotime( $donetodo[ 'date' ] ) ),
				'description' => $donetodo[ 'description' ],
			);
		};
		echo json_encode( $data_donetodos );
	}

	function accounts() {
		$accounts = $this->Accounts_Model->get_all_accounts();
		$data_account = array();
		foreach ( $accounts as $account ) {
			$data_account[] = array(
				'id' => $account[ 'id' ],
				'name' => $account[ 'name' ],
			);
		};
		echo json_encode( $data_account );
	}

	function leads() {
		$leads = $this->Leads_Model->get_all_leads();
		$data_leads = array();
		foreach ( $leads as $lead ) {
			$data_leads[] = array(
				'id' => $lead[ 'id' ],
				'name' => $lead[ 'leadname' ],
				'lead_number'=> get_number('leads', $lead[ 'id' ], 'lead','lead'),
				'email' => $lead['leadmail'],
			);
		};
		echo json_encode( $data_leads );
	}

	function leads_by_leadsource_leadpage() {
		echo json_encode( $this->Report_Model->leads_by_leadsource_leadpage() );
	}

	function products() {
		$products = $this->Products_Model->get_all_products();
		$data_products = array();
		$appconfig = get_appconfig();
		foreach ( $products as $product ) {
			$data_products[] = array(
				'product_id' => $product[ 'id' ],
				'code' => $product[ 'code' ],
				'name' => $product[ 'productname' ],
				'description' => $product[ 'description' ],
				'price' => (float)$product[ 'sale_price' ],
				'tax' => $product[ 'vat' ],
				'purchase_price' => (float)$product[ 'purchase_price' ],
				'category_name' => $product[ 'name' ],
				'stock' => (float)$product[ 'stock' ],
				'product_number' => get_number('products', $product[ 'id' ], 'product','product'),
			);
		};
		echo json_encode( $data_products );
	}

	function vendors() {
		$vendors = array();
		$vendors = $this->Vendors_Model->get_all_vendors();
		$data_vendors = array();
		foreach ( $vendors as $vendor ) {
			$data_vendors[] = array(
				'id' => $vendor[ 'id' ],
				'name' => $vendor[ 'company' ],
				'email' => $vendor[ 'email' ],
				'vendor_number' => get_number('vendors', $vendor[ 'id' ], 'vendor','vendor'),
			);
		};
		echo json_encode( $data_vendors );
	}

	function lang( $lang ) {
		$lang = $this->session->userdata('language');
		$this->lang->load( $lang.'_default', $lang);
		$this->lang->load( $lang, $lang );
		$all_lang_array = $this->lang->language;
		echo json_encode( $all_lang_array );
	}

	function custom_fields_by_type( $type ) {
		$custom_fields = $this->Fields_Model->custom_fields_by_type( $type );
		$data_custom_fields = array();
		foreach ( $custom_fields as $field ) {
			$data_custom_fields[] = array(
				'id' => $field[ 'id' ],
				'name' => $field[ 'name' ],
				'type' => $field[ 'type' ],
				'order' => $field[ 'order' ],
				'data' => json_decode( $field[ 'data' ] ),
				'relation' => $field[ 'relation' ],
				'permission' => $field[ 'permission' ] === 'true' ? true : false,
				'active' => $field[ 'active' ] === 'true' ? true : false,
				'date' => null,
			);
		};
		if ( $custom_fields ) {
			echo json_encode( $data_custom_fields );
		} else {
			echo json_encode( false );
		}
	}

	function custom_fields() {
		$custom_fields = $this->Fields_Model->custom_fields();
		$data_custom_fields = array();
		foreach ( $custom_fields as $field ) {
			$data_custom_fields[] = array(
				'id' => ( $field[ 'id' ] ),
				'name' => $field[ 'name' ],
				'type' => $field[ 'type' ],
				'order' => intval( $field[ 'order' ] ),
				'data' => json_decode( $field[ 'data' ] ),
				'relation' => $field[ 'relation' ],
				'icon' => $field[ 'icon' ],
				'permission' => $field[ 'permission' ] === 'true' ? true : false,
				'active' => $field[ 'active' ] === 'true' ? true : false,
				'updated_on' => ($field['updated_on'] == null) ? '' : date(get_dateTimeFormat(), strtotime($field['updated_on'])),
			);
		};
		echo json_encode( $data_custom_fields );
	}

	function custom_field_data_by_id( $id ) {
		$field = $this->Fields_Model->custom_field_data_by_id( $id );
		$data_custom_field = array(
			'id' => intval( $field[ 'id' ] ),
			'name' => $field[ 'name' ],
			'type' => $field[ 'type' ],
			'order' => intval( $field[ 'order' ] ),
			'data' => json_decode( $field[ 'data' ] ),
			'relation' => $field[ 'relation' ],
			'icon' => $field[ 'icon' ],
			'permission' => $field[ 'permission' ] === 'true' ? true : false,
			'active' => $field[ 'active' ] === 'true' ? true : false,
			'date' => '',
		);
		echo json_encode( $data_custom_field );
	}

	function custom_fields_data_by_type( $type, $id ) {
		$fields = $this->Fields_Model->custom_fields_by_type( $type );
		$data_custom_fields = array();
		foreach ( $fields as $field ) {
			$data = $this->Fields_Model->custom_fields_data_by_type( $type, $id, $field[ 'id' ] );
			if ( $data ) {
				switch ( $field[ 'type' ] ) {
					case 'input':
						$data_last = $data[ 'data' ];
						$selected_opt = 0;
						break;
					case 'date':
						$data_last = $data[ 'data' ];
						$selected_opt = 0;
						break;
					case 'number':
						$data_last = $data[ 'data' ];
						$selected_opt = 0;
						break;
					case 'textarea':
						$data_last = $data[ 'data' ];
						$selected_opt = 0;
						break;
					case 'select':
						$data_last = json_decode( $field[ 'data' ] );
						$selected_opt = json_decode( $data[ 'data' ] );
						break;
				}
				if ( $field[ 'icon' ] != null ) {
					$icon = $field[ 'icon' ];
				} else {
					$icon = 'mdi mdi-info-outline';
				}
			} else {
				$data_last = json_decode( $field[ 'data' ] );
				$selected_opt = null;
			}
			if ( $field[ 'icon' ] != null ) {
				$icon = $field[ 'icon' ];
			} else {
				$icon = 'mdi mdi-info-outline';
			}
			$data_custom_fields[] = array(
				'id' => $field[ 'id' ],
				'name' => $field[ 'name' ],
				'type' => $field[ 'type' ],
				'order' => $field[ 'order' ],
				'data' => $data_last,
				'selected_opt' => $selected_opt,
				'relation' => $field[ 'relation' ],
				'icon' => $icon,
				'permission' => $field[ 'permission' ] === 'true' ? true : false,
				'active' => $field[ 'active' ] === 'true' ? true : false,
			);
		};
		echo json_encode( $data_custom_fields );
	}

	function search() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$input = $this->input->post( 'input' );
			if (!$input || $input == '' || strlen($input) < 2) {
				echo false;
			} else {
				echo json_encode($this->Search_Model->search_data($input));
			}
		} else {
			redirect( 'panel/' );
		}
	}

	function timer( $id = NULL ) {
		$status = $this->input->post( 'status' );
		if ($status == 'start') {
			$id = $this->Tasks_Model->start_timer($this->session->usr_id);
			if ($id) {
				$data['success'] = true;
				$data['type'] = 'Success';
				$data['message'] = lang('timer_started');
				echo json_encode($data);
			} else {
				$data['success'] = false;
				$data['type'] = lang('success');
				$data['message'] = lang('errormessage');
				echo json_encode($data);
			}
		} else if ($status == 'stop') {
			$task = $this->input->post( 'task' );
			$action = $this->input->post( 'action' );
			if ($task == '' || !$task) {
				$data['success'] = false;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('task');
				echo json_encode($data);
			} else {
				if ($action == 'assign' || $action == 'stop') {
					if ($action == 'assign') {
						$params = array(
							//'relation' => 'task',
							'task_id' => $task,
							'note' => $this->input->post( 'note' ),
						);
						$message = lang('task_assigned');
					}
					if ($action == 'stop') {
						$date = new DateTime();
						$params = array(
							//'relation' => 'task',
							'task_id' => $task,
							'note' => $this->input->post( 'note' ),
							'end' => $date->format('Y-m-d H:i:s')
						);
						$message = lang('timer_stopped');
					}
					$timer = $this->Tasks_Model->get_timer();
					$result = $this->Tasks_Model->stop_timer( $id, $params);
					if ($result) {
						$data['success'] = true;
						$data['type'] = lang('success');
						$data['message'] = $message;
						$data['params'] = $params;
						echo json_encode($data);
					} else {
						$data['success'] = false;
						$data['type'] = lang('error');
						$data['message'] = lang('errormessage');
						echo json_encode($data);
					}
				} else {
					$data['success'] = false;
					$data['message'] = lang('errormessage');
					echo json_encode($data);
				}
			}
		}
	}

	function delete_timer( $id ) {
		$timer = $this->Tasks_Model->get_timer();
		$result = $this->Tasks_Model->delete_timer($id);
		$data['success'] = true;
		$data['type'] = lang('success');
		$data['message'] = lang('timer').' '.lang('deletemessage');
		echo json_encode($data);
	}

	function get_timer() {
		$timer = $this->Tasks_Model->get_timer();
		$time_result=array();
		if($timer){
			foreach($timer as $time){
				$date1=new Datetime($time['start']);
				$diffs=$date1->diff(new DateTime());
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
				$secondss = ($h*60*60) + ($minutes*60) + ($seconds);
				$time_result[]=array(
					'id' => $time['id'],
					'started' =>  date(get_dateTimeFormat(), strtotime($time['start'])) ,
					'task_id' => $time['task_id'],
					'total' => $total,
					'seconds' => $secondss,
					'task' => $time['name'],
					'note' => $time['note'],
				);
			}
			$data = $time_result;
			echo json_encode($data);
		}else{
			$data = array();
			echo json_encode($data);
		}
	}

	function get_timer_data($id){
		$data = $this->Tasks_Model->get_timer_data($id);
		echo json_encode($data);
	}

	function get_open_tasks() {
		$tasks = $this->Tasks_Model->get_all_tasks_for_timer();
		$data_tasks = array();
		foreach ( $tasks as $task ) {
			$data_tasks[] = array(
				'id' => $task[ 'id' ],
				'name' => $task[ 'name' ],
				'status_id' => $task[ 'status_id' ],
			);
		};
		echo json_encode( $data_tasks );
	}

	function load_config() {
		$settings = $this->Settings_Model->get_rebranding_data();
		if ($settings['disable_preloader'] == '1') {
			$settings['disable_preloader'] = true;
		} else {
			$settings['disable_preloader'] = false;
		}
		if ($settings['enable_support_button_on_client'] == '1') {
			$settings['enable_support_button_on_client'] = true;
		} else {
			$settings['enable_support_button_on_client'] = false;
		}
		echo json_encode( $settings );
	}

	function eventtypes() {
		$eventtypes = $this->Events_Model->get_eventtypes();
		echo json_encode( $eventtypes );
	}

	function add_eventtype() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$name = $this->input->post( 'name' );
			$color = $this->input->post( 'color' );
			
			$hasError = false;
			$data['name'] = '';
			if ($name == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('name');
			} else if ($color == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('color');
			}
			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				$params = array(
					'color' => $color,
					'name' => $name,
					'public' => $this->input->post( 'public' ),
				);
				$eventtype_id = $this->Events_Model->add_eventtype( $params );
				if ($eventtype_id) {
					$data['success'] = true;
					$data['message'] = lang('event').' '.lang('type').' '.lang('createmessage');
					$data['id'] = $eventtype_id;
					echo json_encode($data);
				} else {
					$data['success'] = false;
					$data['message'] = lang('errormessage');
					echo json_encode($data);
				}
			}
		}
	}

	function remove_eventtype($id) {
		$eventtypes = $this->Events_Model->remove_eventtype($id);
		$data['success'] = true;
		$data['message'] = lang('event').' '.lang('type').' '.lang('deletemessage');
		echo json_encode($data);
	}

	function deposits_by_relation() { 
	    $relation_type = $this->uri->segment( 3 ); 
	    $relation_id = $this->uri->segment( 4 ); 
	    $deposits = $this->Deposits_Model->get_all_deposits_by_relation( $relation_type, $relation_id ); 
	    $data_deposits = array(); 
	    foreach ( $deposits as $deposit ) { 
	      $settings = $this->Settings_Model->get_settings_ciuis(); 
	      switch ( $settings[ 'dateformat' ] ) { 
	        case 'yy.mm.dd': 
	          $depositdate = _rdate( $deposit[ 'date' ] ); 
	          break; 
	        case 'dd.mm.yy': 
	          $depositdate = _udate( $deposit[ 'date' ] ); 
	          break; 
	        case 'yy-mm-dd': 
	          $depositdate = _mdate( $deposit[ 'date' ] ); 
	          break; 
	        case 'dd-mm-yy': 
	          $depositdate = _cdate( $deposit[ 'date' ] ); 
	          break; 
	        case 'yy/mm/dd': 
	          $depositdate = _zdate( $deposit[ 'date' ] ); 
	          break; 
	        case 'dd/mm/yy': 
	          $depositdate = _kdate( $deposit[ 'date' ] ); 
	          break; 
	      }; 
	      if ( $deposit[ 'invoice_id' ] == NULL ) { 
	      	$billstatus = lang( 'notbilled' )and $color = 'warning' and $billstatus_code = 'false'; 
	      } else {
	      	$billstatus = lang( 'billed' ) and $color = 'success' and $billstatus_code = 'true'; 	
	      }
	      if ( $deposit[ 'customer_id' ] != 0 ) { 
	      	$billable = 'true'; 
	      } else { 
	      	$billable = 'false'; 
	      } 
	      $data_deposits[] = array( 
	        'id' => $deposit[ 'id' ], 
	        'title' => $deposit[ 'title' ], 
	        'prefix' => $appconfig['deposit_prefix'], 
		    'longid' => get_number('deposits', $deposit[ 'id' ], 'deposit','deposit'),
	        'amount' => $deposit[ 'amount' ], 
	        'staff' => $deposit[ 'staff' ], 
	        'category' => $deposit[ 'category' ], 
	        'billstatus' => $billstatus, 
	        'billstatus_code' => $billstatus_code, 
	        'color' => $color, 
	        'billable' => $billable, 
	        'date' => $depositdate, 
	      ); 
	    }; 
	    echo json_encode( $data_deposits ); 
	} 
 
	function depositscategories() { 
	    $depositscategories = $this->Deposits_Model->get_all_depositcat(); 
	    $data_depositscategories = array(); 
	    foreach ( $depositscategories as $category ) { 
	      $catid = $category[ 'id' ]; 
	      if ( $this->Privileges_Model->check_privilege( 'deposits', 'all' ) ) {
	      	$amountby = $this->Report_Model->deposits_amount_by_category( $catid ); 
	      	$percent = $this->Report_Model->deposits_percent_by_category( $catid ); 
	      } else {
	      	$amountby = $this->Report_Model->deposits_amount_by_category( $catid, $this->session->usr_id ); 
	      	$percent = $this->Report_Model->deposits_percent_by_category( $catid, $this->session->usr_id ); 
	      }
	      if ( $amountby != NULL ) { 
	        $amtbc = $amountby; 
	      } else {
	      	$amtbc = 0; 	
	      }
	     
	      $data_depositscategories[] = array( 
	        'id' => $category[ 'id' ], 
	        'name' => $category[ 'name' ], 
	        'description' => $category[ 'description' ], 
	        'amountby' => $amtbc, 
	        'percent' => $percent, 
	      ); 
	    }; 
	    echo json_encode( $data_depositscategories ); 
	}

	function table_columns($relation) {
		$columns = $this->db->get_where( 'table_columns', array( 'relation' => $relation) )->result_array();
		$data = array();
		foreach ($columns as $column) {
			$data[$column['table_column']] = ($column['display'] == '1')?true:false;
		}
		echo json_encode($data);
	}

	function update_columns($relation) {
		$column = $this->input->post('column');
		$value = $this->input->post('value');
		$this->db->where(array('relation' => $relation, 'table_column' => $column));
		$this->db->update('table_columns', array('display'=> $value, 'updated_at' => date('Y-m-d H:i:s')));
		echo true;
	}

	function search_customers($q) {
		$customers = $this->Customers_Model->search_customers($q);
		$data = array();
		foreach ($customers as $customer) {
			$billing_country = get_country($customer['billing_country']);
			$shipping_country = get_country($customer['shipping_country']);  
			$billing_state = get_state_name($customer['billing_state'],$customer['billing_state_id']);
			$shipping_state = get_state_name($customer['shipping_state'],$customer['shipping_state_id']); 
			$data[] = array(
				'name' => $customer['namesurname']?$customer['namesurname']:$customer['company'],
				'email' => $customer['email'],
				'customer_number' => get_number('customers', $customer[ 'id' ], 'customer','customer'),
				'id' => $customer[ 'id' ],
				'billing_street' => $customer[ 'billing_street' ],
				'billing_city' => $customer[ 'billing_city' ],
				'billing_state' => $billing_state,
				'billing_state_id' => $customer['billing_state_id'],
				'billing_zip' => $customer[ 'billing_zip' ],
				'billing_country' => $billing_country,
				'billing_country_id' => $customer['billing_country'],
				'shipping_street' => $customer[ 'shipping_street' ],
				'shipping_city' => $customer[ 'shipping_city' ],
				'shipping_state' => $shipping_state,
				'shipping_state_id' => $customer['shipping_state_id'],
				'shipping_zip' => $customer[ 'shipping_zip' ],
				'shipping_country' => $shipping_country,
				'shipping_country_id' => $customer['shipping_country'],
			);
		}
		echo json_encode($data);
	}

	function accounts_total() {
		$total_incomings = $this->Report_Model->total_incomings();
		$total_outgoings = $this->Report_Model->total_outgoings();
		$account_total = $total_incomings - $total_outgoings;
		$total = array(
			'total_incomings' => $total_incomings,
			'total_outgoings' => $total_outgoings,
			'accounts_total' =>$account_total,
		);
		echo json_encode( $total );
	}

	function get_product_categories() {
		$categories = $this->Products_Model->get_product_categories();
		$data_categories = array();
		foreach ( $categories as $category ) {
			$data_categories[] = array(
				'name' => $category[ 'name' ],
				'id' => $category[ 'id' ],
			);
		};
		echo json_encode( $data_categories );
	}

}
