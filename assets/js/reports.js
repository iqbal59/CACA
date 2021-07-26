function Reports_Controller($scope, $http) {
	"use strict";

	var retrievePath = localStorage.getItem('findPath');
	if (retrievePath) {
		retrievePath = JSON.parse(retrievePath);
	}
	$scope.overview = {};
	$scope.overview.loader = true;

	$http.get(BASE_URL + 'api/stats').then(function (Stats) {
		$scope.stats = Stats.data;
		$scope.overview.loader = false;

		if (retrievePath) {
			if (retrievePath.type == 'report' && retrievePath.view == 'timesheet') {
				$scope.ctrl = {};
				$scope.ctrl.selectedIndex = 4;
				$('#timesheetTab').click();
				$scope.getTimesheet();
				localStorage.clear();
			}
		}

		new Chart($('#invoice_chart_by_status'), {
			type: 'horizontalBar',
			data: $scope.stats.invoice_chart_by_status,
			options: {
				legend: {
					display: false,
				},
				responsive: true
			}
		});

		new Chart($('#leads_to_win_by_leadsource'), {
			type: 'horizontalBar',
			data: $scope.stats.leads_to_win_by_leadsource,
			options: {
				legend: {
					display: false,
				}
			}
		});

		new Chart($('#leads_by_leadsource'), {
			type: 'horizontalBar',
			data: $scope.stats.leads_by_leadsource,
			options: {
				legend: {
					display: false,
				}
			}
		});

		new Chart($('#expensesbycategories'), {
			type: 'bar',
			data: $scope.stats.expenses_by_categories,
			options: {
				legend: {
					display: false,
				}
			}
		});

		new Chart($('#top_selling_staff_chart'), {
			type: 'line',
			data: $scope.stats.top_selling_staff_chart,
			options: {
				legend: {
					display: false,
				}
			}
		});

		var CustomerGraph;
		$.get(BASE_URL + 'report/customer_monthly_increase_chart/' + $scope.CustomerReportMonth, function (response) {
			var ctx = $('#customergraph_ciuis-xe').get(0).getContext('2d');
			CustomerGraph = new Chart(ctx, {
				'type': 'bar',
				data: response,
				options: {
					responsive: true
				},
			});
		}, 'json');

		$scope.CustomerMonthChanged = function () {
			lead_graph.destroy();
			$.get(BASE_URL + 'report/customer_monthly_increase_chart/' + $scope.CustomerReportMonth, function (response) {
				var ctx = $('#customergraph_ciuis-xe').get(0).getContext('2d');
				CustomerGraph = new Chart(ctx, {
					'type': 'bar',
					data: response,
					options: {
						responsive: true
					},
				});
			}, 'json');
		};

		var lead_graph;
		$.get(BASE_URL + 'report/lead_graph/' + $scope.LeadReportMonth, function (response) {
			var ctx = $('#lead_graph').get(0).getContext('2d');
			lead_graph = new Chart(ctx, {
				'type': 'bar',
				data: response,
				options: {
					responsive: true
				},
			});
		}, 'json');


		$scope.LeadMonthChanged = function () {
			lead_graph.destroy();
			$.get(BASE_URL + 'report/lead_graph/' + $scope.LeadReportMonth, function (response) {
				var ctx = $('#lead_graph').get(0).getContext('2d');
				lead_graph = new Chart(ctx, {
					'type': 'bar',
					data: response,
					options: {
						responsive: true
					},
				});
			}, 'json');
		};

		var expenses_payments_graph;
		$.get(BASE_URL + 'report/expenses_payments_graph/' + $scope.paymentsExpensesByYear, function (response) {
			var ctx = $('#incomingsvsoutgoins').get(0).getContext('2d');
			expenses_payments_graph = new Chart(ctx, {
				'type': 'line',
				data: response,
				options: {
					responsive: true
				},
			});
		}, 'json');


		$scope.getPaymentsExpensesByYear = function () {
			expenses_payments_graph.destroy();
			$.get(BASE_URL + 'report/expenses_payments_graph/' + $scope.paymentsExpensesByYear, function (response) {
				var ctx = $('#incomingsvsoutgoins').get(0).getContext('2d');
				expenses_payments_graph = new Chart(ctx, {
					'type': 'line',
					data: response,
					options: {
						responsive: true
					},
				});
			}, 'json');
		};
	});

	$http.get(BASE_URL + 'report/get_reports_data').then(function (response) {
		$scope.report = response.data;

		Highcharts.chart('incomingsvsoutgoins_weekly', {
			chart: {
				type: 'column'
			},
			title: {
				text: ''
			},
			height: 380,
			colors: ['#5ba768','#e26862'],
			xAxis: {
				categories: $scope.report.weekdays,
				title: {
					text: null
				}
			},
			pointRange: 86400000,
			yAxis: {
				min: 0,
				title: {
					text: '',
					align: 'high'
				},
				labels: {
					overflow: 'justify'
				}
			},
			tooltip: {
				valueSuffix: ''
			},
			plotOptions: {
				bar: {
					dataLabels: {
						enabled: true
					}
				}
			},
			legend: {
				layout: 'horixontal',
				align: 'right',
				verticalAlign: 'top',
				floating: true,
				borderWidth: 1,
				backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
				shadow: true
			},
			credits: {
				enabled: false
			},
			options: {
				responsive: true
			},
			series: [
				{name: lang.payments,data: $scope.report.payments}, 
				{name: lang.expenses,data: $scope.report.expenses}, 
			]
		});

	});

	$scope.getTimesheet = function() {
		$scope.timesheet = {};
		$scope.timesheet.loader = true;
		$http.get(BASE_URL + 'report/get_timesheet_data').then(function (response) {
			$scope.timesheets = response.data.timesheet;
			$scope.total_time = response.data.total;
			$scope.timesheet.loader = false;

			$scope.itemsPerPage = 5;
			$scope.currentPage = 0;
			$scope.range = function () {
				var rangeSize = 5;
				var ps = [];
				var start;

				start = $scope.currentPage;
				if (start > $scope.pageCount() - rangeSize) {
					start = $scope.pageCount() - rangeSize + 1;
				}

				for (var i = start; i < start + rangeSize; i++) {
					if (i >= 0) {
						ps.push(i);
					}
				}
				return ps;
			};

			$scope.prevPage = function () {
				if ($scope.currentPage > 0) {
					$scope.currentPage--;
				}
			};

			$scope.DisablePrevPage = function () {
				return $scope.currentPage === 0 ? "disabled" : "";
			};

			$scope.nextPage = function () {
				if ($scope.currentPage < $scope.pageCount()) {
					$scope.currentPage++;
				}
			};

			$scope.DisableNextPage = function () {
				return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
			};

			$scope.setPage = function (n) {
				$scope.currentPage = n;
			};

			$scope.pageCount = function () {
				return Math.ceil($scope.timesheets.length / $scope.itemsPerPage) - 1;
			};
		});
	}

	$scope.viewReport = function() {
		$scope.loadingReport = true;
		var dataObj = $.param({
				period: $scope.period,
				exporttype: $scope.exportType,
				from: $scope.from ? moment($scope.from).format("YYYY-MM-DD") : "",
				to: $scope.to ? moment($scope.to).format("YYYY-MM-DD") : "",
			});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$scope.invoiceReport = 'true';
		$scope.customersReport = 'true';
		$scope.expensesReport = 'true';
		$scope.proposalsReport = 'true'; 
		$scope.depositsReport = 'true';
		$scope.ordersReport = 'true';
		$scope.vendorsReport = 'true';
		$scope.purchaseReport = 'true';
		$scope.contactsReport = 'true';
		$scope.ticketsReport = 'true';
		$scope.taskReport = 'true';
		$scope.leadsReport = 'true';
		$scope.productsReport = 'true';
		$scope.staffReport = 'true';
		$scope.projectsReport = 'true';
		var posturl = BASE_URL + 'report/viewReports';
			$http.post(posturl, dataObj, config).then(function (response) {
				if (response.data.success == true) {
					if ( response.data.exporttype == 'invoices') {
						$scope.invoice_list = {
							order: '',
							limit: 5,
							page: 1
						};
						$scope.invoiceReport = 'false';
						$scope.invoices = response.data.result.data_invoice;
						$scope.subtotal = response.data.result.total;
						$scope.inv_limitOptions = [5, 10, 15, 20];
						if ($scope.invoices.length > 20) {
							$scope.inv_limitOptions = [5, 10, 15, 20, $scope.invoices.length];
						}
						$scope.loadingReport = false;
					} else if ( response.data.exporttype == 'customers') {
						$scope.customer_list = {
							order: '',
							limit: 5,
							page: 1
						};
						$scope.customersReport = 'false';
						$scope.customers = response.data.result;
						$scope.cust_limitOptions = [5, 10, 15, 20];
						if ($scope.customers.length > 20) {
							$scope.cust_limitOptions = [5, 10, 15, 20, $scope.customers.length];
						}
						$scope.loadingReport = false;
					} else if ( response.data.exporttype == 'expenses') {
						$scope.expense_list = {
							order: '',
							limit: 5,
							page: 1
						};
						$scope.expensesReport = 'false';
						$scope.expenses = response.data.result.data_expense;
						$scope.subtotal = response.data.result.total;
						$scope.exp_limitOptions = [5, 10, 15, 20];
						if ($scope.expenses.length > 20) {
							$scope.exp_limitOptions = [5, 10, 15, 20, $scope.expenses.length];
						}
						$scope.loadingReport = false;
					} else if ( response.data.exporttype == 'proposals') {
						$scope.proposals_list = {
							order: '',
							limit: 5,
							page: 1
						}
						$scope.proposalsReport = 'false';
						$scope.proposals = response.data.result.data_proposal;
						$scope.subtotal = response.data.result.total;
						$scope.pro_limitOptions = [5, 10, 15, 20];
						if ($scope.proposals.length > 20) {
							$scope.proposal_limitOptions = [5, 10, 15, 20, $scope.proposals.length];
						}
						$scope.loadingReport = false;
					} else if ( response.data.exporttype == "deposits") {
						$scope.deposit_list = {
							order: '',
							limit: 5,
							page: 1
						}
						$scope.depositsReport = 'false';
						$scope.deposits = response.data.result;
						$scope.dep_limitOptions = [5, 10, 15, 20];
						if($scope.deposits.length > 20) {
							$scope.dep_limitOptions = [5, 10, 15, 20, $scope.deposits.length];
						}
						$scope.loadingReport = false;
					} else if ( response.data.exporttype == "orders") {
						$scope.order_list = {
							order: '',
							limit: 5,
							page: 1
						}
						$scope.ordersReport = 'false';
						$scope.orders = response.data.result;
						$scope.odr_limitOptions = [5, 10, 15, 20];
						if($scope.orders.length > 20) {
							$scope.odr_limitOptions = [5, 10, 15, 20, $scope.orders.length];
						}
						$scope.loadingReport = false;
					} else if ( response.data.exporttype == "vendors") {
						$scope.vendor_list = {
							order: '',
							limit: 5,
							page: 1
						}
						$scope.vendorsReport = 'false';
						$scope.vendors = response.data.result;
						$scope.ven_limitOptions = [5, 10, 15, 20];
						if($scope.vendors.length > 20) {
							$scope.ven_limitOptions = [5, 10, 15, 20, $scope.vendors.length];
						}
						$scope.loadingReport = false;
					} else if ( response.data.exporttype == "purchases") {
						$scope.purchase_list = {
							order: '',
							limit: 5,
							page: 1
						}
						$scope.purchaseReport = 'false';
						$scope.purchases = response.data.result.data_purchase;
						$scope.subtotal = response.data.result.total;
						$scope.po_limitOptions = [5, 10, 15, 20];
						if($scope.purchases.length > 20) {
							$scope.po_limitOptions = [5, 10, 15, 20, $scope.purchases.length];
						}
						$scope.loadingReport = false;
					} else if ( response.data.exporttype == "contacts") {
						$scope.contact_list = {
							order: '',
							limit: 5,
							page: 1
						}
						$scope.contactsReport = 'false';
						$scope.contacts = response.data.result;
						$scope.contact_limitOptions = [5, 10, 15, 20];
						if($scope.contacts.length > 20) {
							$scope.contact_limitOptions = [5, 10, 15, 20, $scope.contacts.length];
						}
						$scope.loadingReport = false;
					} else if ( response.data.exporttype == "tickets") {
						$scope.ticket_list = {
							order: '',
							limit: 5,
							page: 1
						}
						$scope.ticketsReport = 'false';
						$scope.tickets = response.data.result;
						$scope.tkt_limitOptions = [5, 10, 15, 20];
						if($scope.tickets.length > 20) {
							$scope.tkt_limitOptions = [5, 10, 15, 20, $scope.tickets.length];
						}
						$scope.loadingReport = false;
					} else if ( response.data.exporttype == "tasks") {
						$scope.task_list = {
							order: '',
							limit: 5,
							page: 1
						}
						$scope.taskReport = 'false';
						$scope.tasks = response.data.result;
						$scope.task_limitOptions = [5, 10, 15, 20];
						if($scope.tasks.length > 20) {
							$scope.task_limitOptions = [5, 10, 15, 20, $scope.tasks.length];
						}
						$scope.loadingReport = false;
					} else if ( response.data.exporttype == "leads") {
						$scope.lead_list = {
							order: '',
							limit: 5,
							page: 1
						}
						$scope.leadsReport = 'false';
						$scope.leads = response.data.result;
						$scope.lead_limitOptions = [5, 10, 15, 20];
						if($scope.leads.length > 20) {
							$scope.lead_limitOptions = [5, 10, 15, 20, $scope.leads.length];
						}
						$scope.loadingReport = false;
					} else if ( response.data.exporttype == "products") {
						$scope.product_list = {
							order: '',
							limit: 5,
							page: 1
						}
						$scope.productsReport = 'false';
						$scope.products = response.data.result;
						$scope.product_limitOptions = [5, 10, 15, 20];
						if($scope.products.length > 20) {
							$scope.product_limitOptions = [5, 10, 15, 20, $scope.products.length];
						}
						$scope.loadingReport = false;
					} else if ( response.data.exporttype == "staff") {
						$scope.staff_list = {
							order: '',
							limit: 5,
							page: 1
						}
						$scope.staffReport = 'false';
						$scope.staff = response.data.result;
						$scope.staff_limitOptions = [5, 10, 15, 20];
						if($scope.staff.length > 20) {
							$scope.staff_limitOptions = [5, 10, 15, 20, $scope.staff.length];
						}
						$scope.loadingReport = false;
					} else if ( response.data.exporttype == "projects") {
						$scope.project_list = {
							order: '',
							limit: 5,
							page: 1
						}
						$scope.projectsReport = 'false';
						$scope.projects = response.data.result;
						$scope.pro_limitOptions = [5, 10, 15, 20];
						if($scope.projects.length > 20) {
							$scope.pro_limitOptions = [5, 10, 15, 20, $scope.projects.length];
						}
						$scope.loadingReport = false;
					}
				} else {
					$scope.loadingReport = false;
					globals.mdToast('error', response.data.message );
				}
			});
	}

	$scope.exportData = function(type, period) {
		$scope.csvLoader = true;
		if(period == '7') {
			var dataObj = $.param({
				period: period,
				from: moment($scope.from).format("YYYY-MM-DD"),
				to: moment($scope.to).format("YYYY-MM-DD"),
				type: type,
			});
		} else {
			var dataObj = $.param({
				period: period,
				type:type,
			});
		}
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};

		var posturl = BASE_URL + 'report/exportData';
		$http.post(posturl, dataObj, config).then(
			function (response) {
				var file = new Blob([response.data], {
				type: 'application/csv'
			});
			var fileURL = URL.createObjectURL(file);
			var a = document.createElement('a');
			a.href = fileURL;
			a.target = '_blank';
			a.download = type+'.csv';
			document.body.appendChild(a);
			a.click();
			document.body.removeChild(a);
			$scope.csvLoader = false;
		});
	}

	$scope.generatePdf = function(type, period) {
		$scope.generated_url = '#';
		$scope.pdfLoader = true;
		var dataObj = $.param({
			period: period,
			type:type,
			from: moment($scope.from).format("YYYY-MM-DD"),
			to: moment($scope.to).format("YYYY-MM-DD"),
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'report/generatePdf';
		$http.post(posturl, dataObj, config).then(
			function (response) {
				console.log(response);
		        $scope.pdfLoader = false;
				if (response.data.success == true) {
		            $scope.generated_url = BASE_URL+'uploads/files/reports/'+response.data.file_name;
		            window.open(BASE_URL+'uploads/files/reports/'+response.data.file_name, '_blank');
		        } else {
		              $scope.pdfLoader = false;
		              globals.mdToast('error', 'error generating pdf' );
		        }
			},
			function (response) {
	         	$scope.pdfLoader = false;
	         	globals.mdToast('error', 'error generating pdf' );
	        }
		);
	}
}
CiuisCRM.controller('Reports_Controller', Reports_Controller);
