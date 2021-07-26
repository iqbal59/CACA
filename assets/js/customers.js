function Customers_Controller($scope, $http, $mdSidenav, $filter, $mdDialog, fileUpload, $q) {
	"use strict";

	$http.get(BASE_URL + 'api/custom_fields_by_type/' + 'customer').then(function (custom_fields) {
		$scope.all_custom_fields = custom_fields.data;
		$scope.custom_fields = $filter('filter')($scope.all_custom_fields, {
			active: 'true',
		});
	});

	$scope.customersLoader = true;
	$scope.isContact = true;
	$scope.toggleFilter = buildToggler('ContentFilter');
	$scope.Create = buildToggler('Create');
	$scope.ImportCustomersNav = buildToggler('ImportCustomersNav');
	$scope.CreateGroup = buildToggler('CreateGroup');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}
	$scope.close = function () {
		$mdSidenav('ContentFilter').close();
		$mdSidenav('Create').close();
		$mdSidenav('ImportCustomersNav').close();
		$mdSidenav('CreateGroup').close();
	};

	$scope.importing = false;
	$scope.importerror = false;
	$scope.importCustomer = function () {
		$scope.importing = true;
		var file = $scope.customer_file;
		var uploadUrl = BASE_URL + 'customers/customersimport/';
		fileUpload.uploadFileToUrl(file, uploadUrl, function (response) {
			if ((response.success == true) && (!response.errors)) {
				//showToast(NTFTITLE, response.message, ' success');
				globals.mdToast('success', response.message);
				$mdSidenav('ImportCustomersNav').close();
			} else if ((response.success == false) && (response.errors)) {
				$scope.importerror = true;
				$scope.errors = response.errors;
				console.log(response.errors);
				globals.mdToast('error', response.message);
			} else {
				$scope.importerror = true;
				$scope.errors = response.errors;
				globals.mdToast('error', response.message);
				console.log(response.errors);
			}
			$http.get(BASE_URL + 'api/customers').then(function (Customers) {
				$scope.customers = Customers.data;
			});
			$scope.customerFiles = true;
			$scope.importing = false;

		});
	};

	globals.get_countries();
	$scope.getStates = function (country) {
		$http.get(BASE_URL + 'api/get_states/' + country).then(function (States) {
			$scope.states = States.data;
		});
	};

	$scope.getBillingStates = function (country) {
		$http.get(BASE_URL + 'api/get_states/' + country).then(function (States) {
			$scope.billingStates = States.data;
		});
	};

	$scope.getShippingStates = function (country) {
		$http.get(BASE_URL + 'api/get_states/' + country).then(function (States) {
			$scope.shippingStates = States.data;
		});
	};

	var gdata;
	$http.get(BASE_URL + 'customers/groups').then(function(Data){
		gdata = Data.data;
		var data = [];
		for(var i =0; i<gdata.length; i++){
			data.push([gdata[i].name,parseInt(gdata[i].y)]);
		}

		Highcharts.chart('container', {
			chart:{
				polar:true,
				plotBackgroundColor:'#f3f3f3',
				plotBorderWidth:0,
				plotShadow:false
			},
			title:{
				//text: 'Customer<br>Group',
				text: lang.customer+'<br>'+lang.group,
				align:'center',
				verticalAlign:'middle',
				y:-18
			},
			tooltip:{
				pointFormat: '<b>{point.y}</b>'
			},
			credits:{
				enabled:false
			},
			plotOptions:{
				pie:{
					dataLabels:{
						enabled:true,
						distance:-50,
						style:{
							fontWeight:'bold',
							color:'white'
						}
					},
					center:['50%','47%'],
					size:'100%'
				}
			},
			series:[
				{
					type:'pie',
					name:'',
					innerSize:'42%',
					data:data
				}
			],
			exporting:{
				buttons:{
					contextButton:{
						menuItems:['downloadPNG', 'downloadSVG','downloadPDF', 'downloadCSV', 'downloadXLS']
					}
				}
			}
		});
		function redrawchart(){
	        var chart = $('#container').highcharts();
	        var w = $('#container').closest(".wrapper").width();
	        chart.setSize(       
	            w,w * (3/4),false
	        );
	     }
	    $(window).resize(redrawchart);
	    redrawchart();
	});

	var deferred = $q.defer();
	$scope.customer_list = {
		order: '',
		limit: 5,
		page: 1
	};
	$scope.promise = deferred.promise;
	$http.get(BASE_URL + 'api/customers').then(function (Customers) {
		$scope.customers = Customers.data;
		deferred.resolve();

		$scope.limitOptions = [5, 10, 15, 20];
		if($scope.customers.length > 20 ) {
			$scope.limitOptions = [5, 10, 15, 20, $scope.customers.length];
		}
		$scope.customersLoader = false;

		$http.get(BASE_URL + 'customers/get_customer_groups').then(function (Groups){
			$scope.group = Groups.data;

			$scope.NewGroup = function () {
				globals.createDialog(lang.new+' ' + lang.customer+' ' + lang.group, $scope.lang.type_group_name, lang.group +' '+ lang.name, '', lang.add, lang.cancel, 'customers/add_group/',  function(response) {
					if (response.success == true) {
						globals.mdToast('success', response.message);
					} else {
						globals.mdToast('error', response.message);
					}
					$http.get(BASE_URL + 'customers/get_customer_groups').then(function (Groups) {
						$scope.group = Groups.data;
					});
				});
			};

			$scope.EditGroup = function (id, name, event) {
				globals.editDialog(lang.edit+' ' + lang.group+' ' + lang.name, $scope.lang.type_group_name,  lang.group +' '+ lang.name, name, event, lang.save, lang.cancel, 'customers/update_group/' + id, function(response) {
					if (response.success == true) {
						globals.mdToast('success', response.message);
						$http.get(BASE_URL + 'customers/get_customer_groups').then(function (Groups) {
							$scope.group = Groups.data;
						});
					} else {
						globals.mdToast('error', response.message);
					}
				});
			};

			$scope.DeleteCustomerGroup = function (index) {
				var name = $scope.group[index];
				globals.deleteDialog(lang.attention, $scope.lang.delete_meesage+' '+$scope.lang.group, name.id, lang.doIt, lang.cancel, 'customers/remove_group/' + name.id, function(response) {
					if (response.success == true) {
						globals.mdToast('success', response.message);
						$http.get(BASE_URL + 'customers/get_customer_groups').then(function (Groups) {
							$scope.group = Groups.data;
						});
					} else {
						globals.mdToast('error', response.message);
					}
				});
			};
		});

		$scope.GoCustomer = function (index) {
			var customer = $scope.customers[index];
			window.location.href = BASE_URL + 'customers/customer/' + customer.id;
		};

		$scope.isIndividual = false;
		$scope.saving = false;

		$scope.AddCustomer = function () {
			$scope.saving = true;
			$scope.tempArr = [];
			if( $scope.isContact == true) {
				$scope.isContact = 1;
			} else {
				$scope.isContact = 0;
			}
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
				$scope.tempArr.push({
					id: value.id,
					name: value.name,
					type: value.type,
					order: value.order,
					data: $scope.field_data,
					relation: value.relation,
					permission: value.permission,
				});
			});
			if (!$scope.customer) {
				var dataObj = $.param({
					company: '',
					namesurname: '',
					group_id:'',
					taxoffice: '',
					taxnumber: '',
					ssn: '',
					executive: '',
					address: '',
					zipcode: '',
					country_id: '',
					state: '',
					state_id : '',
					city: '',
					town: '',
					phone: '',
					fax: '',
					email: '',
					web: '',
					risk: '',
					billing_street: '',
					billing_city: '',
					billing_state: '',
					billing_state_id:'',
					billing_zip: '',
					billing_country: '',
					shipping_street: '',
					shipping_city: '',
					shipping_state: '',
					shipping_state_id:'',
					shipping_zip: '',
					shipping_country: '',
					type: '',
					custom_fields: $scope.tempArr,
					default_payment_method: ''
				});
			} else {
				var dataObj = $.param({
					company: $scope.customer.company,
					namesurname: $scope.customer.namesurname,
					groupid: $scope.customer.group_id,
					taxoffice: $scope.customer.taxoffice,
					taxnumber: $scope.customer.taxnumber,
					ssn: $scope.customer.ssn,
					executive: $scope.customer.executive,
					address: $scope.customer.address,
					zipcode: $scope.customer.zipcode,
					country_id: $scope.customer.country_id,
					state_id: $scope.customer.state_id,
					city: $scope.customer.city,
					town: $scope.customer.town,
					phone: $scope.customer.phone,
					fax: $scope.customer.fax,
					email: $scope.customer.email,
					web: $scope.customer.web,
					risk: $scope.customer.risk,
					billing_street: $scope.customer.billing_street,
					billing_city: $scope.customer.billing_city,
					billing_state_id: $scope.customer.billing_state_id,
					billing_zip: $scope.customer.billing_zip,
					billing_country: $scope.customer.billing_country,
					shipping_street: $scope.customer.shipping_street,
					shipping_city: $scope.customer.shipping_city,
					shipping_state_id: $scope.customer.shipping_state_id,
					shipping_zip: $scope.customer.shipping_zip,
					shipping_country: $scope.customer.shipping_country,
					type: $scope.isIndividual,
					custom_fields: $scope.tempArr,
					default_payment_method: $scope.customer.default_payment_method,
					contact: $scope.isContact,
				});
			}
			var posturl = BASE_URL + 'customers/create/';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							if (response.data.id) {
								$http.get(BASE_URL + 'api/customers').then(function (Customers) {
									$scope.customers = Customers.data;
								});
								globals.mdToast('success', response.data.message);
								$mdSidenav('Create').close();							
							} else {
								$scope.saving = false;
								globals.mdToast('error', response.data.message);
							}
						} else {
							$scope.saving = false;
							globals.mdToast('error', response.data.message);
						}
					},
					function (response) {
						$scope.saving = false;
						$http.get(BASE_URL + 'api/customers').then(function (Customers) {
							$scope.customers = Customers.data;
						});
					}
				);
		};

		$scope.SameAsCustomerAddress = function () {
			$scope.customer.billing_street = $scope.customer.address;
			$scope.customer.billing_city = $scope.customer.city;
			$scope.customer.billing_state = $scope.customer.state_ud;
			$scope.customer.billing_zip = $scope.customer.zipcode;
			$scope.customer.billing_country = $scope.customer.country_id;
		};

		$scope.SameAsBillingAddress = function () {
			$scope.customer.shipping_street = $scope.customer.billing_street;
			$scope.customer.shipping_city = $scope.customer.billing_city;
			$scope.customer.shipping_state = $scope.customer.billing_state_id;
			$scope.customer.shipping_zip = $scope.customer.billing_zip;
			$scope.customer.shipping_country = $scope.customer.billing_country;
		};

		$scope.filter = {};
		$scope.getOptionsFor = function (propName) {
			return ($scope.customers || []).map(function (item) {
				return item[propName];
			}).filter(function (item, idx, arr) {
				return arr.indexOf(item) === idx;
			}).sort();
		};
		$scope.FilteredData = function (item) {
			// Use this snippet for matching with AND
			var matchesAND = true;
			for (var prop in $scope.filter) {
				if (noSubFilter($scope.filter[prop])) {
					continue;
				}
				if (!$scope.filter[prop][item[prop]]) {
					matchesAND = false;
					break;
				}
			}
			return matchesAND;

		};

		function noSubFilter(subFilterObj) {
			for (var key in subFilterObj) {
				if (subFilterObj[key]) {
					return false;
				}
			}
			return true;
		}
		$scope.updateDropdown = function (_prop) {
			var _opt = this.filter_select,
				_optList = this.getOptionsFor(_prop),
				len = _optList.length;
				console.log(_optList);

			if (_opt == 'all') {
				for (var j = 0; j < len; j++) {
					$scope.filter[_prop][_optList[j]] = true;
				}
			} else {
				for (var j = 0; j < len; j++) {
					$scope.filter[_prop][_optList[j]] = false;
				}
				$scope.filter[_prop][_opt] = true;
			}
		};
		// Filtered Datas
		$scope.search = {
			name: '',
			phone: '',
			email: '',
		};
	});
}

function Customer_Controller($scope, $http, $filter, $mdSidenav, $mdDialog) {
	"use strict";

	$scope.ReminderForm = buildToggler('ReminderForm');
	$scope.NewContact = buildToggler('NewContact');
	$scope.Update = buildToggler('Update');
	$('.update-view').hide();

	$scope.customersLoader = true;
	$scope.visibleFlag = true;

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}

	$scope.close = function () {
		$mdSidenav('ReminderForm').close();
		$mdSidenav('NewContact').close();
		$mdSidenav('Update').close();
	};

	globals.get_countries();
	$scope.getStates = function (country) {
		$http.get(BASE_URL + 'api/get_states/' + country).then(function (States) {
			$scope.states = States.data;
		});
	};

	$scope.getBillingStates = function (country) {
		$http.get(BASE_URL + 'api/get_states/' + country).then(function (States) {
			$scope.billingStates = States.data;
		});
	};

	$scope.getShippingStates = function (country) {
		$http.get(BASE_URL + 'api/get_states/' + country).then(function (States) {
			$scope.shippingStates = States.data;
		});
	};

	$scope.SameAsCustomerAddress = function () {
		$scope.customer.billing_street = $scope.customer.address;
		$scope.customer.billing_city = $scope.customer.city;
		$scope.customer.billing_state = $scope.customer.state_id;
		$scope.customer.billing_zip = $scope.customer.zipcode;
		$scope.customer.billing_country = $scope.customer.country_id;
	};

	$scope.SameAsBillingAddress = function () {
		$scope.customer.shipping_street = $scope.customer.billing_street;
		$scope.customer.shipping_city = $scope.customer.billing_city;
		$scope.customer.shipping_state = $scope.customer.billing_state_id;
		$scope.customer.shipping_zip = $scope.customer.billing_zip;
		$scope.customer.shipping_country = $scope.customer.billing_country;
	};

	$http.get(BASE_URL + 'api/custom_fields_data_by_type/' + 'customer/' + CUSTOMERID).then(function (custom_fields) {
		$scope.custom_fields = custom_fields.data;
	});

	$http.get(BASE_URL + 'customers/get_customer/' + CUSTOMERID).then(function (Customer) {
		$scope.customer = Customer.data;
		$http.get(BASE_URL + 'api/contact/' + CUSTOMERID).then(function (contact) {
			$scope.contacts = contact.data;
		});
		$scope.isActive = $scope.customer.customer_isActive;
		$scope.getStates($scope.customer.country_id);
		$scope.getBillingStates($scope.customer.billing_country);
		$scope.getShippingStates($scope.customer.shipping_country);
		$scope.customersLoader = false;

		$http.get(BASE_URL + 'customers/get_customer_groups').then(function (Groups){
			$scope.groups = Groups.data;
		});

		var canvas = document.getElementById("customer_annual_sales_chart");
		var multiply = {
			beforeDatasetsDraw: function (chart, options, el) {
				chart.ctx.globalCompositeOperation = 'multiply';
			},
			afterDatasetsDraw: function (chart, options) {
				chart.ctx.globalCompositeOperation = 'source-over';
			},
		};
		var gradientThisWeek = canvas.getContext('2d').createLinearGradient(0, 0, 0, 150);
		gradientThisWeek.addColorStop(0, '#ffbc00');
		gradientThisWeek.addColorStop(1, '#fff');
		var gradientPrevWeek = canvas.getContext('2d').createLinearGradient(0, 0, 0, 150);
		gradientPrevWeek.addColorStop(0, '#616f8c');
		gradientPrevWeek.addColorStop(1, '#fff');
		var configs = {
			type: 'bar',
			data: $scope.customer.chart_data,
			options: {
				elements: {
					point: {
						radius: 0,
						hitRadius: 5,
						hoverRadius: 5
					}
				},
				legend: {
					display: false,
				},
				scales: {
					xAxes: [{
						display: false,
					}],
					yAxes: [{
						display: false,
						ticks: {
							beginAtZero: true,
						},
					}]
				},
				legend: {
					display: true
				}
			},
			plugins: [multiply],
		};
		window.chart = new Chart(canvas, configs)

		$http.get(BASE_URL + 'api/invoices').then(function (Invoices) {
			$scope.all_invoices = Invoices.data;
			$scope.invoices = $filter('filter')($scope.all_invoices, {
				customer_id: CUSTOMERID,
			});
		});

		$scope.GoInvoice = function (index) {
			var invoice = $scope.invoices[index];
			window.location.href = BASE_URL + 'invoices/invoice/' + invoice.id;
		};

		$http.get(BASE_URL + 'api/proposals').then(function (Proposals) {
			$scope.all_proposals = Proposals.data;
			$scope.proposals = $filter('filter')($scope.all_proposals, {
				relation_type: 'customer',
				relation: CUSTOMERID,
			});
		});

		$scope.GoProposal = function (index) {
			var proposal = $scope.proposals[index];
			window.location.href = BASE_URL + 'proposals/proposal/' + proposal.id;
		};

		$http.get(BASE_URL + 'api/projects').then(function (Projects) {
			$scope.all_projects = Projects.data;
			$scope.projects = $filter('filter')($scope.all_projects, {
				customer_id: CUSTOMERID,
			});
		});

		$scope.GoProject = function (index) {
			var project = $scope.projects[index];
			window.location.href = BASE_URL + 'projects/project/' + project.id;
		};

		$http.get(BASE_URL + 'api/tickets').then(function (Tickets) {
			$scope.all_tickets = Tickets.data;
			$scope.tickets = $filter('filter')($scope.all_tickets, {
				customer_id: CUSTOMERID,
			});
		});

		$scope.GoTicket = function (index) {
			var ticket = $scope.tickets[index];
			window.location.href = BASE_URL + 'tickets/ticket/' + ticket.id;
		};

		$scope.savingCustomer = false;
		$scope.UpdateCustomer = function () {
			$scope.savingCustomer = true;
			$scope.tempArr = [];
			var customer_status;
			if($scope.customer.customer_status_id == true) {
				customer_status = '1';
			} else {
				customer_status = '0';
			}
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
				$scope.tempArr.push({
					id: value.id,
					name: value.name,
					type: value.type,
					order: value.order,
					data: $scope.field_data,
					relation: value.relation,
					permission: value.permission,
				});
			});
			var dataObj = $.param({
				company: $scope.customer.company,
				namesurname: $scope.customer.namesurname,
				group_id: $scope.customer.group_id,
				taxoffice: $scope.customer.taxoffice,
				taxnumber: $scope.customer.taxnumber,
				ssn: $scope.customer.ssn,
				executive: $scope.customer.executive,
				address: $scope.customer.address,
				zipcode: $scope.customer.zipcode,
				country_id: $scope.customer.country_id,
				state_id: $scope.customer.state_id,
				city: $scope.customer.city,
				town: $scope.customer.town,
				phone: $scope.customer.phone,
				fax: $scope.customer.fax,
				email: $scope.customer.email,
				web: $scope.customer.web,
				risk: $scope.customer.risk,
				billing_street: $scope.customer.billing_street,
				billing_city: $scope.customer.billing_city,
				billing_state_id: $scope.customer.billing_state_id,
				billing_zip: $scope.customer.billing_zip,
				billing_country: $scope.customer.billing_country,
				shipping_street: $scope.customer.shipping_street,
				shipping_city: $scope.customer.shipping_city,
				shipping_state_id: $scope.customer.shipping_state_id,
				shipping_zip: $scope.customer.shipping_zip,
				shipping_country: $scope.customer.shipping_country,
				custom_fields: $scope.tempArr,
				default_payment_method: $scope.customer.default_payment_method,
				type: $scope.customer.isIndividual,
				status_id: customer_status,
			});
			var posturl = BASE_URL + 'customers/customer/' + CUSTOMERID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.savingCustomer = false;
						if (response.data.success == true) {
							$mdSidenav('Update').close();
							$http.get(BASE_URL + 'customers/get_customer/' + CUSTOMERID).then(function (Customer) {
								$scope.customer = Customer.data;
							});
							globals.mdToast('success', response.data.message);
						} else {
							globals.mdToast('error', response.data.message);
						}
					},
					function (response) {
						$scope.savingCustomer = false;
					}
				);
		};

		$scope.Delete = function (index) {
			globals.deleteDialog(lang.attention, lang.delete_customer, CUSTOMERID, lang.doIt, lang.cancel, 'customers/remove/' + CUSTOMERID, function(response) {
				if (response.success == true) {
					window.location.href = BASE_URL + 'customers';
				} else {
					globals.mdToast('error',response.message);
				}
			});
		};
		// $http.get(BASE_URL + 'api/contacts').then(function (contacts) {
		// 	$scope.all_contacts = contactscon.data;
		// 	$scope.contacts = $filter('filter')($scope.all_contacts, {
		// 		customer_id: CUSTOMERID,
		// 	});
		// });

		$scope.isPrimary = true;
		$scope.isAdmin = false;

		$scope.ContactDetail = function (index) {
			var contact = $scope.contacts[index];
			$mdDialog.show({
				contentElement: '#ContactModal-' + contact.id,
				parent: angular.element(document.body),
				targetEvent: index,
				clickOutsideToClose: true
			});
			$scope.UpdateContactPrivilege = function (id, value, privilege_id) {
				$http.post(BASE_URL + 'customers/update_contact_privilege/' + id + '/' + value + '/' + privilege_id)
					.then(
						function (response) {
							if(response.data.success == false) {
								globals.mdToast('error', response.data.message);
							}
						},
						function (response) {
							console.log(response);
						}
					);
			};
		};

		$scope.Contact = function () {
			$scope.saving = true;
			if (!$scope.newcontact) {
				var dataObj = $.param({
					name: '',
					surname: '',
					phone: '',
					extension: '',
					mobile: '',
					email: '',
					address: '',
					skype: '',
					linkedin: '',
					position: '',
					customer: CUSTOMERID,
					isPrimary: $scope.isPrimary,
					isAdmin: $scope.isAdmin,
					password: $scope.passwordNew,
				});
			} else {
				var dataObj = $.param({
					name: $scope.newcontact.name,
					surname: $scope.newcontact.surname,
					phone: $scope.newcontact.phone,
					extension: $scope.newcontact.extension,
					mobile: $scope.newcontact.mobile,
					email: $scope.newcontact.email,
					address: $scope.newcontact.address,
					skype: $scope.newcontact.skype,
					linkedin: $scope.newcontact.linkedin,
					position: $scope.newcontact.position,
					customer: CUSTOMERID,
					isPrimary: $scope.isPrimary,
					isAdmin: $scope.isAdmin,
					password: $scope.passwordNew,
				});
			}
			var posturl = BASE_URL + 'customers/contact/';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.tabIndex = 0;
						$scope.saving = false;
						if (response.data.success == true) {
							$scope.tabIndex++;
						} else {
							globals.mdToast('error', response.data.message, 6000);
						}
					},
					function (response) {
						$scope.saving = false;
					}
				);
		};

		$scope.saving = false;
		$scope.AddContact = function () {
			$scope.saving = true;
			if (!$scope.newcontact) {
				var dataObj = $.param({
					name: '',
					surname: '',
					phone: '',
					extension: '',
					mobile: '',
					email: '',
					address: '',
					skype: '',
					linkedin: '',
					position: '',
					customer: CUSTOMERID,
					isPrimary: $scope.isPrimary,
					isAdmin: $scope.isAdmin,
					password: $scope.passwordNew,
				});
			} else {
				var dataObj = $.param({
					name: $scope.newcontact.name,
					surname: $scope.newcontact.surname,
					phone: $scope.newcontact.phone,
					extension: $scope.newcontact.extension,
					mobile: $scope.newcontact.mobile,
					email: $scope.newcontact.email,
					address: $scope.newcontact.address,
					skype: $scope.newcontact.skype,
					linkedin: $scope.newcontact.linkedin,
					position: $scope.newcontact.position,
					customer: CUSTOMERID,
					isPrimary: $scope.isPrimary,
					isAdmin: $scope.isAdmin,
					password: $scope.passwordNew,
					permissions : $scope.permissions,
				});
			}

			$http.post(BASE_URL + 'customers/create_contact/', dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							globals.mdToast('success', response.data.message);
							$mdSidenav('NewContact').close();
							$http.get(BASE_URL + 'api/contact/' + CUSTOMERID).then(function (contact) {
								$scope.all_contacts = contact.data;
								$scope.contacts = $filter('filter')($scope.all_contacts, {
									customer_id: CUSTOMERID,
								});
							});
						} else {
							globals.mdToast('error', response.data.message);
						}
						$scope.saving = false;
					},
					function (response) {
						$scope.saving = false;
					}
				);
		};

		$scope.updatingContact = false;
		$scope.UpdateContact = function (index) {
			$scope.updatingContact = true;
			var contact = $scope.contacts[index];
			var contact_id = contact.id;
			$scope.contact = contact;
			var dataObj = $.param({
				name: $scope.contact.name,
				surname: $scope.contact.surname,
				phone: $scope.contact.phone,
				extension: $scope.contact.extension,
				mobile: $scope.contact.mobile,
				email: $scope.contact.email,
				address: $scope.contact.address,
				skype: $scope.contact.skype,
				linkedin: $scope.contact.linkedin,
				position: $scope.contact.position,
			});
			var posturl = BASE_URL + 'customers/update_contact/' + contact_id;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							globals.mdToast('success', response.data.message);
							$mdDialog.cancel();
							$http.get(BASE_URL + 'api/contact/' + CUSTOMERID).then(function (contact) {
								$scope.all_contacts = contact.data;
								$scope.contacts = $filter('filter')($scope.all_contacts, {
									customer_id: CUSTOMERID,
								});
							});
							$('#updatecontact' + contact_id + '').modal('hide');
						} else {
							globals.mdToast('error', response.data.message);
						}
						$scope.updatingContact = false;
					},
					function (response) {
						$scope.updatingContact = false;
					}
				);
		};

		$scope.ChangePassword = function (contact) {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.prompt()
				.title('Change Password')
				.textContent('Are sure change contact password?')
				.placeholder('Password')
				.ariaLabel('Password')
				.initialValue('')
				.targetEvent(contact)
				.required(true)
				.ok('Okay!')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function (result) {
				var dataObj = $.param({
					password: result,
				});
				$http.post(BASE_URL + 'customers/change_password_contact/' + contact, dataObj, config)
					.then(
						function (response) {
							if(response.data.success == true) {
								globals.mdToast('success', response.data.message);
							} else {
								globals.mdToast('error', response.data.message);
							}
						},
						function (response) {
						}
					);
			}, function () {

			});
		};

		$scope.RemoveContact = function (id) {
			globals.deleteDialog(lang.attention, lang.delete_contact, id, lang.doIt, lang.cancel, 'customers/remove_contact/' + id, function(response) {
				if (response.success == true) {
					globals.mdToast('success', response.message);
					$http.get(BASE_URL + 'api/contact/' + CUSTOMERID).then(function (contact) {
						$scope.contacts = contact.data;
					});
				} else {
					globals.mdToast('error',response.message);
				}
			});
		};

		$scope.CloseModal = function () {
			$mdDialog.cancel();
		};

	});

	$http.get(BASE_URL + 'api/reminders_by_type/customer/' + CUSTOMERID).then(function (Reminders) {
		$scope.in_reminders = Reminders.data;

		$scope.AddReminder = function () {
			var dataObj = $.param({
				description: $scope.reminder_description,
				date: moment($scope.reminder_date).format("YYYY-MM-DD HH:mm:ss"),
				staff: $scope.reminder_staff,
				relation_type: 'customer',
				relation: CUSTOMERID,
			});
			var posturl = BASE_URL + 'trivia/addreminder';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$scope.in_reminders.push({
							'description': $scope.reminder_description,
							'creator': LOGGEDINSTAFFNAME,
							'avatar': UPIMGURL + LOGGEDINSTAFFAVATAR,
							'staff': LOGGEDINSTAFFNAME,
							'date': $scope.reminder_date,
						});
						$mdSidenav('ReminderForm').close();
					},
					function (response) {
						console.log(response);
					}
				);
		};
		$scope.DeleteReminder = function (index) {
			var reminder = $scope.in_reminders[index];
			var dataObj = $.param({
				reminder: reminder.id
			});
			var posturl = BASE_URL + 'trivia/remove_reminder';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.in_reminders.splice($scope.in_reminders.indexOf(reminder), 1);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});

	$http.get(BASE_URL + 'api/notes/customer/' + CUSTOMERID).then(function (Notes) {
		$scope.notes = Notes.data;

		$scope.AddNote = function () {
			var dataObj = $.param({
				description: $scope.note,
				relation_type: 'customer',
				relation: CUSTOMERID,
			});
			var posturl = BASE_URL + 'trivia/addnote';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color success'
							});
							$('.note-description').val('');
							$scope.note = '';
							$http.get(BASE_URL + 'api/notes/customer/' + CUSTOMERID).then(function (Notes) {
								$scope.notes = Notes.data;
							});
						} else {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color danger'
							});
						}
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.DeleteNote = function (index) {
			var note = $scope.notes[index];
			var dataObj = $.param({
				notes: note.id
			});
			var posturl = BASE_URL + 'trivia/removenote';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.notes.splice($scope.notes.indexOf(note), 1);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});
}
CiuisCRM.controller('Customers_Controller', Customers_Controller);
CiuisCRM.controller('Customer_Controller', Customer_Controller);