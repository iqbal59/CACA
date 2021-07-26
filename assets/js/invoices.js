function Invoices_Controller($scope, $compile, $http, $mdSidenav, $q, $timeout, $filter) {
	"use strict";

	globals.get_countries();
	$scope.InvoicesList = function() {
		$scope.loadingData = true;
		$http.get(BASE_URL + 'invoices/get_content/index').then(function (Data) {
			$scope.content = Data.data;
			var div = angular.element(document.getElementById("pageContent"));
			div.html($scope.content);
			$compile(div.contents())($scope);
			$scope.loadingData = false;
		});
	};

	$scope.GoToInvoice = function() {
		
	};

	$http.get(BASE_URL + 'api/custom_fields_by_type/' + 'invoice').then(function (custom_fields) {
		$scope.all_custom_fields = custom_fields.data;
		$scope.custom_fields = $filter('filter')($scope.all_custom_fields, {
			active: 'true',
		});
	});

	$http.get(BASE_URL + 'api/products').then(function (Products) {
		$scope.products = Products.data;
	});

	$scope.GetProduct = (function (search) {
		console.log(search);
		var deferred = $q.defer();
		$timeout(function () {
			deferred.resolve($scope.products);
		}, Math.random() * 500, false);
		return deferred.promise;
	});

	$scope.invoice = {
		items: [{
			name: new_item,
			product_id: 0,
			code: '',
			description: '',
			quantity: 1,
			unit: item_unit,
			price: 0,
			tax: 0,
			discount: 0,
		}]
	};

	$scope.add = function () {
		$scope.invoice.items.push({ 
			name: new_item,
			product_id: 0,
			code: '',
			description: '',
			quantity: 1,
			unit: item_unit,
			price: 0,
			tax: 0,
			discount: 0,
		});
	};

	$scope.remove = function (index) {
		$scope.invoice.items.splice(index, 1);
	};

	$scope.subtotal = function () {
		var subtotal = 0;
		angular.forEach($scope.invoice.items, function (item) {
			subtotal += item.quantity * item.price;
		});
		return subtotal.toFixed(2);
	};

	$scope.linediscount = function () {
		var linediscount = 0;
		angular.forEach($scope.invoice.items, function (item) {
			linediscount += ((item.discount) / 100 * item.quantity * item.price);
		});
		return linediscount.toFixed(2);
	};

	$scope.totaltax = function () {
		var totaltax = 0;
		angular.forEach($scope.invoice.items, function (item) {
			totaltax += ((item.tax) / 100 * item.quantity * item.price);
		});
		return totaltax.toFixed(2);
	};

	$scope.grandtotal = function () {
		var grandtotal = 0;
		angular.forEach($scope.invoice.items, function (item) {
			grandtotal += item.quantity * item.price + ((item.tax) / 100 * item.quantity * item.price) - ((item.discount) / 100 * item.quantity * item.price);
		});
		return grandtotal.toFixed(2);
	};

	$scope.today = new Date();

	$scope.saveAll = function () {
		$scope.savingInvoice = true;
		if ($scope.invoice.shipping_country) {
			$scope.shipping_country = $scope.invoice.billing_country_id;
		} else {
			$scope.shipping_country = null;
		}
		if ($scope.invoice.billing_country) {
			$scope.billing_country = $scope.invoice.billing_country_id;
		} else {
			$scope.billing_country = null;
		}
		if ($scope.invoice.shipping_state) {
			$scope.shipping_state = $scope.invoice.shipping_state_id;
		} else {
			$scope.shipping_state = null;
		}
		if ($scope.invoice.billing_state) {
			$scope.billing_state = $scope.invoice.billing_state_id;
		} else {
			$scope.billing_state = null;
		}
		$scope.tempArr = [];
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

		var invoice_recurring;
		if ($scope.invoice_recurring == true) {
			invoice_recurring = '1';
		} else {
			invoice_recurring = '0';
		}

		var EndRecurring;
		if ($scope.EndRecurring) {
			EndRecurring = moment($scope.EndRecurring).format("YYYY-MM-DD 00:00:00");
		} else {
			EndRecurring = 'Invalid date';
		}

		if ($scope.created) {
			$scope.created = moment($scope.created).format("YYYY-MM-DD");
		}
		if ($scope.duedate) {
			$scope.duedate = moment($scope.duedate).format("YYYY-MM-DD");
		}
		if ($scope.datepayment) {
			$scope.datepayment = moment($scope.datepayment).format("YYYY-MM-DD");
		}
		var dataObj = $.param({
			customer: $scope.customer.id,
			created: $scope.created,
			duedate: $scope.duedate,
			datepayment: $scope.datepayment,
			account: $scope.account,
			duenote: $scope.duenote,
			serie: $scope.serie,
			no: $scope.no,
			sub_total: $scope.subtotal,
			total_discount: $scope.linediscount,
			total_tax: $scope.totaltax,
			total: $scope.grandtotal,
			status: $scope.invoice_status,
			// Billing Address
			billing_street: $scope.invoice.billing_street,
			billing_city: $scope.invoice.billing_city,
			billing_state_id: $scope.invoice.billing_state_id,
			billing_zip: $scope.invoice.billing_zip,
			billing_country: $scope.billing_country,
			// Shipping Address
			shipping_street: $scope.invoice.shipping_street,
			shipping_city: $scope.invoice.shipping_city,
			shipping_state_id: $scope.invoice.shipping_state_id,
			shipping_zip: $scope.invoice.shipping_zip,
			shipping_country: $scope.shipping_country,
			// START Recurring
			recurring: invoice_recurring,
			end_recurring: EndRecurring,
			recurring_type: $scope.recurring_type,
			recurring_period: $scope.recurring_period,
			// END Recurring
			items: $scope.invoice.items,
			totalItems: $scope.invoice.items.length,
			custom_fields: $scope.tempArr,
			default_payment_method: $scope.default_payment_method
		});
		var posturl = BASE_URL + 'invoices/create';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					if (response.data.success == true) {
						window.location.href = BASE_URL + 'invoices/invoice/' + response.data.id;
					} else {
						$scope.savingInvoice = false;
						showToast(NTFTITLE, response.data.message, ' danger');
					}
				},
				function (response) {
					$scope.savingInvoice = false;
				}
			);
	};

	$scope.CopyBillingFromCustomer = function () {
		$scope.invoice.billing_street = $scope.customer.billing_street;
		$scope.invoice.billing_city = $scope.customer.billing_city;
		$scope.invoice.billing_state = $scope.customer.billing_state;
		$scope.invoice.billing_zip = $scope.customer.billing_zip;
		$scope.invoice.billing_country = $scope.customer.billing_country;
	};

	$scope.CopyShippingFromCustomer = function () {
		$scope.invoice.shipping_street = $scope.customer.shipping_street;
		$scope.invoice.shipping_city = $scope.customer.shipping_city;
		$scope.invoice.shipping_state = $scope.customer.shipping_state;
		$scope.invoice.shipping_zip = $scope.customer.shipping_zip;
		$scope.invoice.shipping_country = $scope.customer.shipping_country;
	};

	$scope.getBillingStates = function (country) {
		console.log(country);
		$http.get(BASE_URL + 'api/get_states/' + country).then(function (States) {
			$scope.billingStates = States.data;
		});
	};

	$scope.getShippingStates = function (country) {
		$http.get(BASE_URL + 'api/get_states/' + country).then(function (States) {
			$scope.shippingStates = States.data;
		});
	};

	$http.get(BASE_URL + 'api/accounts').then(function (Accounts) {
		$scope.accounts = Accounts.data;
	});

	$scope.SelectedCustomer = $scope.customer;

	$scope.invoiceLoader = true;
	var deferred = $q.defer();
	$scope.invoice_list = {
		order: '',
		limit: 5,
		page: 1
	};

	$scope.promise = deferred.promise;
	$http.get(BASE_URL + 'api/invoices').then(function (Invoices) {
		$scope.invoices = Invoices.data;
		deferred.resolve();

		$scope.limitOptions = [5, 10, 15, 20];
		if($scope.invoices.length > 20 ) {
			$scope.limitOptions = [5, 10, 15, 20, $scope.invoices.length];
		}

		$scope.invoiceLoader = false;
		$scope.search = {
			customer: ''
		};
		// Filter Buttons //
		$scope.toggleFilter = buildToggler('ContentFilter');

		function buildToggler(navID) {
			return function () {
				$mdSidenav(navID).toggle();

			};
		}
		$scope.close = function () {
			$mdSidenav('ContentFilter').close();
		};
		// Filter Buttons //
		// Filtered Datas
		$scope.filter = {};
		$scope.getOptionsFor = function (propName) {
			return ($scope.invoices || []).map(function (item) {
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
	});
}

function Invoice_Controller($scope, $http, $mdSidenav, $mdDialog, $q, $timeout, $filter) {
	"use strict";

	globals.get_countries();
	$http.get(BASE_URL + 'api/custom_fields_data_by_type/' + 'invoice/' + INVOICEID).then(function (custom_fields) {
		$scope.custom_fields = custom_fields.data;
	});

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

	$scope.sendEmail = function() {
		$scope.sendingEmail = true;
		$http.post(BASE_URL + 'invoices/send_invoice_email/' + INVOICEID)
			.then(
				function (response) {
					console.log(response);
					if (response.data.status === true) {
						showToast(NTFTITLE, response.data.message, 'success');
					}
					$scope.sendingEmail = false;
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.GeneratePDF = function (ev) {
		$mdDialog.show({
			templateUrl: 'generate-invoice.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};

	$scope.CreatePDF = function () {
		$scope.PDFCreating = true;
		$http.post(BASE_URL + 'invoices/create_pdf/' + INVOICEID)
			.then(
				function (response) {
					console.log(response);
					if (response.data.status === true) {
						$scope.PDFCreating = false;
						$scope.CreatedPDFName = response.data.file_name;
					}
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.Delete = function () {
		// Appending dialog to document.body to cover sidenav in docs app
		var confirm = $mdDialog.confirm()
			.title($scope.lang.deleteinvoice)
			.textContent($scope.lang.inv_remove_msg)
			.ariaLabel('Delete Invoice')
			.targetEvent(INVOICEID)
			.ok($scope.lang.delete)
			.cancel($scope.lang.cancel);

		$mdDialog.show(confirm).then(function () {
			$http.post(BASE_URL + 'invoices/remove/' + INVOICEID, config)
				.then(
					function (response) {
						if(response.data.success == true) {
							window.location.href = BASE_URL + 'invoices';
							globals.mdToast('success', response.data.message);
						} else {
							globals.mdToast('error', response.data.message);
						}
					},
					function (response) {
						console.log(response);
					}
				);
		});
	};

	$scope.invoiceLoader = true;
	$http.get(BASE_URL + 'invoices/get_invoice/' + INVOICEID).then(function (InvoiceDetails) {
		$scope.invoice = InvoiceDetails.data; 
		var invoice = JSON.stringify($scope.invoice);
		var cust = $scope.invoice.properties.customer;
		var searchCustomer = $scope.invoice.properties.customer?cust.split(' ')[0]:'';
		$scope.getBillingStates($scope.invoice.billing_country_id);
		$scope.getShippingStates($scope.invoice.shipping_country_id);
		$scope.search_customers(searchCustomer);
		$http.get(BASE_URL + 'api/customers/').then(function (Data) {
			$scope.customers = Data.data; 
		});
		// $http.get(BASE_URL + 'api/contacts').then(function (Contacts) {
		// 	$scope.all_contacts = Contacts.data;
		// 	$scope.contacts = $filter('filter')($scope.all_contacts, {
		// 		customer_id: $scope.invoice.customer,
		// 	});
		// });
		$scope.invoiceLoader = false;
		$scope.MarkAsDraft = function () {
			$http.post(BASE_URL + 'invoices/mark_as_draft/' + INVOICEID)
				.then(
					function (response) {
						if(response.data.success == true){
							globals.mdToast('success', response.data.message);
						} else {
							globals.mdToast('error', response.data.message);
						}
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.MarkAsCancelled = function () {
			$http.post(BASE_URL + 'invoices/mark_as_cancelled/' + INVOICEID)
				.then(
					function (response) {
						if(response.data.success == true){
							globals.mdToast('success', response.data.message);
						} else {
							globals.mdToast('error', response.data.message);
						}
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.subtotal = function () {
			var subtotal = 0;
			angular.forEach($scope.invoice.items, function (item) {
				subtotal += item.quantity * item.price;
			});
			return subtotal.toFixed(2);
		};
		$scope.linediscount = function () {
			var linediscount = 0;
			angular.forEach($scope.invoice.items, function (item) {
				linediscount += ((item.discount) / 100 * item.quantity * item.price);
			});
			return linediscount.toFixed(2);
		};
		$scope.totaltax = function () {
			var totaltax = 0;
			angular.forEach($scope.invoice.items, function (item) {
				totaltax += ((item.tax) / 100 * item.quantity * item.price);
			});
			return totaltax.toFixed(2);
		};
		$scope.grandtotal = function () {
			var grandtotal = 0;
			angular.forEach($scope.invoice.items, function (item) {
				grandtotal += item.quantity * item.price + ((item.tax) / 100 * item.quantity * item.price) - ((item.discount) / 100 * item.quantity * item.price);
			});
			return grandtotal.toFixed(2);
		};

		$scope.totalpaid = function () {
			return $scope.invoice.payments.reduce(function (total, payment) {
				return total + (payment.amount * 1 || 0);
			}, 0);
		};

		$scope.amount = $scope.invoice.balance;

		$http.get(BASE_URL + 'api/products').then(function (Products) {
			$scope.products = Products.data;
		});

		$scope.GetProduct = (function (search) {
			console.log(search);
			var deferred = $q.defer();
			$timeout(function () {
				deferred.resolve($scope.products);
			}, Math.random() * 500, false);
			return deferred.promise;
		});

		$scope.add = function () {
			$scope.invoice.items.push({
				name: new_item,
				product_id: 0,
				code: '',
				description: '',
				quantity: 1,
				unit: item_unit,
				price: 0,
				tax: 0,
				discount: 0,
			});
		};

		$scope.remove = function (index) {
			var item = $scope.invoice.items[index];
			$http.post(BASE_URL + 'invoices/remove_item/' + item.id)
				.then(
					function (response) {
						console.log(response);
						$scope.invoice.items.splice(index, 1);
						$scope.invoice.balance = $scope.invoice.balance - item.total;
						$scope.amount = $scope.invoice.balance;
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.changeBank = function(id) {
			var customer = '';
			for (var i = 0; i < $scope.customers.length; i++) {
				if ($scope.customers[i].id == id) {
					customer = $scope.customers[i];
					continue;
				}
			}
			$scope.invoice.default_payment_method = customer.default_payment_method;
		};

		$scope.saveAll = function () {
			$scope.savingInvoice = true;
			$scope.tempArr = [];
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
			var EndRecurring;
			if ($scope.invoice.recurring_endDate) {
				EndRecurring = moment($scope.invoice.recurring_endDate).format("YYYY-MM-DD 00:00:00");
			} else {
				EndRecurring = 'Invalid date';
			}
			if ($scope.invoice.created_edit) {
				$scope.invoice.created = moment($scope.invoice.created_edit).format("YYYY-MM-DD");
			}
			if ($scope.invoice.duedate_edit) {
				$scope.invoice.duedate = moment($scope.invoice.duedate_edit).format("YYYY-MM-DD");
			}
			var dataObj = $.param({
				customer: $scope.invoice.customer,
				created: $scope.invoice.created,
				duedate: $scope.invoice.duedate,
				duenote: $scope.invoice.duenote,
				serie: $scope.invoice.serie,
				no: $scope.invoice.no,
				sub_total: $scope.subtotal,
				total_discount: $scope.linediscount,
				total_tax: $scope.totaltax,
				total: $scope.grandtotal,
				// Billing Address
				billing_street: $scope.invoice.billing_street,
				billing_city: $scope.invoice.billing_city,
				billing_state_id: $scope.invoice.billing_state_id,
				billing_zip: $scope.invoice.billing_zip,
				billing_country: $scope.invoice.billing_country_id,
				// Shipping Address
				shipping_street: $scope.invoice.shipping_street,
				shipping_city: $scope.invoice.shipping_city,
				shipping_state_id: $scope.invoice.shipping_state_id,
				shipping_zip: $scope.invoice.shipping_zip,
				shipping_country: $scope.invoice.shipping_country_id,
				// START Recurring
				recurring_status: $scope.invoice.recurring_status,
				recurring: $scope.invoice.recurring_status,
				end_recurring: EndRecurring,
				recurring_type: $scope.invoice.recurring_type,
				recurring_period: $scope.invoice.recurring_period,
				recurring_id: $scope.invoice.recurring_id,
				// END Recurring
				items: $scope.invoice.items,
				totalItems: $scope.invoice.items.length,
				custom_fields: $scope.tempArr,
				default_payment_method: $scope.invoice.default_payment_method
			});
			var posturl = BASE_URL + 'invoices/update/' + INVOICEID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							window.location.href = BASE_URL + 'invoices/invoice/' + response.data.id;
						} else {
							$scope.savingInvoice = false;
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						$scope.savingInvoice = false;
						showToast(NTFTITLE, response.data.message, ' danger');
					}
				);
		};
	});

	$scope.UpdateInvoice = function (id) {
		window.location.href = BASE_URL + 'invoices/update/' + id;
	};

	$scope.RecordPayment = buildToggler('RecordPayment');
	$scope.Discussions = buildToggler('Discussions');
	$scope.NewDiscussion = buildToggler('NewDiscussion');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}

	$scope.close = function () {
		$mdSidenav('RecordPayment').close();
		$mdSidenav('Discussions').close();
		$mdSidenav('NewDiscussion').close();
	};
	$scope.CloseModal = function () {
		$mdDialog.hide();
	};

	$http.get(BASE_URL + 'api/discussions/invoice/' + INVOICEID).then(function (Discussions) {
		$scope.discussions = Discussions.data;
		$scope.Discussion_Detail = function (index) {
			var discussion = $scope.discussions[index];
			$scope.discussions_comments = discussion.comments;
			$scope.AddComment = function (index) {
				var discussion = $scope.discussions[index];
				var dataObj = $.param({
					discussion_id: discussion.id,
					content: discussion.newcontent,
					contact_id: discussion.contact_id,
					full_name: LOGGEDINSTAFFNAME,

				});
				var posturl = BASE_URL + 'trivia/add_discussion_comment';
				$http.post(posturl, dataObj, config)
					.then(
						function (response) {
							console.log(response);
							$scope.discussions_comments.push({
								'content': discussion.newcontent,
								'full_name': LOGGEDINSTAFFNAME,
								'created': new Date(),
							});
							$('.comment-description').val('');
						},
						function (response) {
							console.log(response);
						}
					);
			};
			$mdDialog.show({
				contentElement: '#Discussion_Detail-' + discussion.id,
				parent: angular.element(document.body),
				targetEvent: index,
				clickOutsideToClose: true
			});
		};
	});

	$scope.ShowCustomer = false;

	$scope.CreateDiscussion = function () {
		var dataObj = $.param({
			relation_type: 'invoice',
			relation: INVOICEID,
			subject: $scope.new_discussion.subject,
			description: $scope.new_discussion.description,
			contact_id: $scope.new_discussion.contact_id,
			show_to_customer: $scope.ShowCustomer,
			staff_id: ACTIVESTAFF,

		});
		var posturl = BASE_URL + 'trivia/create_discussion';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					console.log(response);
					$scope.discussions.push({
						'id': response.data,
						'subject': $scope.new_discussion.subject,
						'contact': $scope.new_discussion.contact_id,
					});
					$mdSidenav('NewDiscussion').close();
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$http.get(BASE_URL + 'api/accounts').then(function (Accounts) {
		$scope.accounts = Accounts.data;
	});
	$scope.doing = false;
	$scope.AddPayment = function () {
		$scope.doing = true;
		var dataObj = $.param({
			date: moment($scope.date).format("YYYY-MM-DD HH:mm:ss"),
			balance: $scope.invoice.balance - $scope.amount,
			amount: $scope.amount,
			not: $scope.not,
			account: $scope.account,
			invoicetotal: $scope.grandtotal,
			staff: ACTIVESTAFF,
			customer: INVOICECUSTOMER,
			invoice: INVOICEID,
		});
		var posturl = BASE_URL + 'invoices/record_payment';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					if (response.data.success == true) {
						$mdSidenav('RecordPayment').close();
						globals.mdToast('success', response.data.message );
						$scope.invoice.balance = $scope.invoice.balance - $scope.amount;
						$scope.doing = false;
						$http.get(BASE_URL + 'invoices/get_invoice/' + INVOICEID).then(function (InvoiceDetails) {
							$scope.invoice = InvoiceDetails.data;
						});
					} else {
						globals.mdToast('error', response.data.message );
						$scope.doing = false;
					}
				},
				function (response) {
					$scope.doing = false;
				}
			);
	};
}
CiuisCRM.controller('Invoices_Controller', Invoices_Controller);
CiuisCRM.controller('Invoice_Controller', Invoice_Controller);