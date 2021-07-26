function Purchases_Controller($scope, $http, $mdSidenav, $q, $timeout) {
	"use strict";

	$http.get(BASE_URL + 'api/products').then(function (Products) {
		$scope.products = Products.data;
	});

	$http.get(BASE_URL + 'api/vendors').then(function (Vendors) {
		$scope.all_vendors = Vendors.data;
	});

	$scope.GetProduct = (function (search) {
		var deferred = $q.defer();
		$timeout(function () {
			deferred.resolve($scope.products);
		}, Math.random() * 500, false);
		return deferred.promise;
	});

	$scope.purchase = {
		items: [{
			name: 'New',
			product_id: 0,
			code: '',
			description: '',
			quantity: 1,
			unit: 'Unit',
			price: 0,
			tax: 0,
			discount: 0,
		}]
	};

	$scope.add = function () {
		$scope.purchase.items.push({
			name: 'New',
			product_id: 0,
			code: '',
			description: '',
			quantity: 1,
			unit: 'Unit',
			price: 0,
			tax: 0,
			discount: 0,
		});
	};

	$scope.remove = function (index) {
		$scope.purchase.items.splice(index, 1);
	};

	$scope.subtotal = function () {
		var subtotal = 0;
		angular.forEach($scope.purchase.items, function (item) {
			subtotal += item.quantity * item.price;
		});
		return subtotal.toFixed(2);
	};

	$scope.linediscount = function () {
		var linediscount = 0;
		angular.forEach($scope.purchase.items, function (item) {
			linediscount += ((item.discount) / 100 * item.quantity * item.price);
		});
		return linediscount.toFixed(2);
	};

	$scope.totaltax = function () {
		var totaltax = 0;
		angular.forEach($scope.purchase.items, function (item) {
			totaltax += ((item.tax) / 100 * item.quantity * item.price);
		});
		return totaltax.toFixed(2);
	};

	$scope.grandtotal = function () {
		var grandtotal = 0;
		angular.forEach($scope.purchase.items, function (item) {
			grandtotal += item.quantity * item.price + ((item.tax) / 100 * item.quantity * item.price) - ((item.discount) / 100 * item.quantity * item.price);
		});
		return grandtotal.toFixed(2);
	};
	
	$scope.saveAll = function () {
		var purchase_recurring;
		if ($scope.purchase_recurring == true) {
			purchase_recurring = '1';
		} else {
			purchase_recurring = '0';
		}

		var EndRecurring;
		$scope.savingPurchase = true;
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
			vendor: $scope.vendor,
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
			tax_code: $scope.tax_code,
			total: $scope.grandtotal,
			status: $scope.purchase_status,
			// START Recurring
			recurring: purchase_recurring,
			end_recurring: EndRecurring,
			recurring_type: $scope.recurring_type,
			recurring_period: $scope.recurring_period,
			// END Recurring
			items: $scope.purchase.items,
			totalItems: $scope.purchase.items.length,
		});
		var posturl = BASE_URL + 'Purchases/create';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					if (response.data.success == true) {
					window.location.href = BASE_URL + 'purchases/purchase/' + response.data.id;
					} else {
						$scope.savingPurchase = false;
						showToast(NTFTITLE, response.data.message, ' danger');
					}
				},
				function (response) {
					$scope.savingPurchase = false;
				}
			);
	};

	$http.get(BASE_URL + 'api/accounts').then(function (Accounts) {
		$scope.accounts = Accounts.data;
	});

	$scope.purchase_list = {
		order: '',
		limit: 5,
		page: 1
	};
	$scope.purchasesLoader = true;
	$http.get(BASE_URL + 'purchases/get_purchases').then(function (Purchases) {
		$scope.purchases = Purchases.data;
		$scope.limitOptions = [5, 10, 15, 20];
		if ($scope.purchases.length > 20) {
			$scope.limitOptions = [5, 10, 15, 20, $scope.purchases.length];
		}
		$scope.purchasesLoader = false;
		
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
		$scope.filter = {};
		$scope.getOptionsFor = function (propName) {
			return ($scope.purchases || []).map(function (item) {
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

function Purchase_Controller($scope, $http, $mdSidenav, $mdDialog, $q, $timeout) {
	"use strict";

	$scope.GeneratePDF = function (ev) {
		$mdDialog.show({
			templateUrl: 'generate-purchase.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};
	$scope.get_vendors();

	$scope.sendEmail = function() {
		$scope.sendingEmail = true;
		$http.post(BASE_URL + 'purchases/send_purchase_email/' + PURCHASEID)
			.then(
				function (response) {
					console.log(response)
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

	$scope.CreatePDF = function () {
		$scope.PDFCreating = true;
		$http.post(BASE_URL + 'purchases/create_pdf/' + PURCHASEID)
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

	$scope.Delete = function (index) {
		console.log("sdfs");
		globals.deleteDialog(lang.attention, lang.delete_meesage, PURCHASEID, lang.doIt, lang.cancel, 'purchases/remove/' + PURCHASEID, function(response) {
			if (response.success == true) {
				globals.mdToast('success', response.message);
				window.location.href = BASE_URL + 'purchases';
			} else {
				globals.mdToast('error', response.message);
			}
		});
	};

	$scope.purchaseLoader = true;
	$http.get(BASE_URL + 'purchases/get_purchase/' + PURCHASEID).then(function (PurchaseDetails) {
		$scope.purchase = PurchaseDetails.data;
		$scope.purchaseLoader = false;
		$scope.MarkAsDraft = function () {
			$http.post(BASE_URL + 'purchases/mark_as_draft/' + PURCHASEID)
				.then(
					function (response) {
						if(response.data.success == true) {
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
			$http.post(BASE_URL + 'purchases/mark_as_cancelled/' + PURCHASEID)
				.then(
					function (response) {
						if(response.data.success == true) {
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
			angular.forEach($scope.purchase.items, function (item) {
				subtotal += item.quantity * item.price;
			});
			return subtotal.toFixed(2);
		};
		$scope.linediscount = function () {
			var linediscount = 0;
			angular.forEach($scope.purchase.items, function (item) {
				linediscount += ((item.discount) / 100 * item.quantity * item.price);
			});
			return linediscount.toFixed(2);
		};
		$scope.totaltax = function () {
			var totaltax = 0;
			angular.forEach($scope.purchase.items, function (item) {
				totaltax += ((item.tax) / 100 * item.quantity * item.price);
			});
			return totaltax.toFixed(2);
		};
		$scope.grandtotal = function () {
			var grandtotal = 0;
			angular.forEach($scope.purchase.items, function (item) {
				grandtotal += item.quantity * item.price + ((item.tax) / 100 * item.quantity * item.price) - ((item.discount) / 100 * item.quantity * item.price);
			});
			return grandtotal.toFixed(2);
		};

		$scope.totalpaid = function () {
			return $scope.purchase.payments.reduce(function (total, payment) {
				return total + (payment.amount * 1 || 0);
			}, 0);
		};

		$scope.amount = $scope.purchase.balance;

		$http.get(BASE_URL + 'api/products').then(function (Products) {
			$scope.products = Products.data;
		});

		$scope.GetProduct = (function (search) {
			var deferred = $q.defer();
			$timeout(function () {
				deferred.resolve($scope.products);
			}, Math.random() * 500, false);
			return deferred.promise;
		});

		$scope.add = function () {
			$scope.purchase.items.push({
				name: 'New',
				product_id: 0,
				code: '',
				description: '',
				quantity: 1,
				unit: 'Unit',
				price: 0,
				tax: 0,
				discount: 0,
			});
		};

		$scope.remove = function (index) {
			var item = $scope.purchase.items[index];
			$http.post(BASE_URL + 'purchases/remove_item/' + item.id)
				.then(
					function (response) {
						console.log(response);
						$scope.purchase.items.splice(index, 1);
						$scope.purchase.balance = $scope.purchase.balance - item.total;
						$scope.amount = $scope.purchase.balance;
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.saveAll = function () {
			var EndRecurring;
			if ($scope.purchase.recurring_endDate) {
				EndRecurring = moment($scope.purchase.recurring_endDate).format("YYYY-MM-DD 00:00:00");
			} else {
				EndRecurring = 'Invalid date';
			}
			if ($scope.purchase.created_edit) {
				$scope.purchase.created = moment($scope.purchase.created_edit).format("YYYY-MM-DD");
			}
			if ($scope.purchase.duedate) {
				$scope.purchase.duedate = moment($scope.purchase.duedate_edit).format("YYYY-MM-DD");
			}
			var dataObj = $.param({
				vendor: $scope.purchase.vendor,
				created: $scope.purchase.created,
				duedate: $scope.purchase.duedate,
				duenote: $scope.purchase.duenote,
				serie: $scope.purchase.serie,
				no: $scope.purchase.no,
				sub_total: $scope.subtotal,
				total_discount: $scope.linediscount,
				total_tax: $scope.totaltax,
				total: $scope.grandtotal,
				tax_code: $scope.tax_code,
				// START Recurring
				recurring_status: $scope.purchase.recurring_status,
				recurring: $scope.purchase.recurring_status,
				end_recurring: EndRecurring,
				recurring_type: $scope.purchase.recurring_type,
				recurring_period: $scope.purchase.recurring_period,
				recurring_id: $scope.purchase.recurring_id,
				// END Recurring
				items: $scope.purchase.items,
				staff_isActive: $scope.isActive,
			});
			console.log(dataObj);
			var posturl = BASE_URL + 'purchases/update/' + PURCHASEID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							showToast(NTFTITLE, response.data.message, ' success');
							window.location.href = BASE_URL + 'purchases/purchase/' + response.data.id;
 						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});

	$scope.UpdateInvoice = function (id) {
		window.location.href = BASE_URL + 'purchases/update/' + id;
	};

	$scope.RecordPayment = buildToggler('RecordPayment');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}

	$scope.close = function () {
		$mdSidenav('RecordPayment').close();
	};

	$scope.CloseModal = function () {
		$mdDialog.hide();
	};

	$http.get(BASE_URL + 'api/accounts').then(function (Accounts) {
		$scope.accounts = Accounts.data;
	});

	$scope.AddPayment = function () {
		var dataObj = $.param({
			date: moment($scope.date).format("YYYY-MM-DD HH:mm:ss"),
			balance: $scope.purchase.balance - $scope.amount,
			total: $scope.amount,
			not: $scope.not,
			account: $scope.account,
			purchasetotal: $scope.grandtotal,
			vendor: PURCHASVENDOR,
			purchase: PURCHASEID,
		});
		var posturl = BASE_URL + 'purchases/record_payment';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					if (response.data.success == true) {
						$mdSidenav('RecordPayment').close();
						globals.mdToast('success', response.data.message );
						$scope.purchase.balance = $scope.purchase.balance - $scope.amount;
						$http.get(BASE_URL + 'purchases/get_purchase/' + PURCHASEID).then(function (PurchaseDetails) {
							$scope.purchase = PurchaseDetails.data;
						});
					} else {
						globals.mdToast('error', response.data.message );
					}
					
				},
				function (response) {
					console.log(response);
				}
			);
	};
}

CiuisCRM.controller('Purchases_Controller', Purchases_Controller);
CiuisCRM.controller('Purchase_Controller', Purchase_Controller);