function Settings_Controller($scope, $http, $mdDialog, $mdSidenav, $sce, fileUpload, $q) {
	"use strict";
	let LATEST_VERSION = null;
	$scope.close = function() {
		$mdDialog.hide();
		$mdSidenav('CreateCustomField').close();
		$mdSidenav('FieldDetail').close();
		$mdSidenav('RestoreDatabaseNav').close();
		$mdSidenav('uploadAppFiles').close();
		$mdSidenav('RunMySQL').close();
		$scope.viewPassword = false;
	};
	$scope.getStates = function(country) {
		$http.get(BASE_URL + 'api/get_states/' + country).then(function(States) {
			$scope.states = States.data;
		});
	};

	function buildToggler(navID) {
		$mdSidenav(navID).toggle();
	}
	$scope.uploadAppFiles = function() {
		$mdSidenav('uploadAppFiles').toggle();
	};
	$scope.RunMySQL = function() {
		$mdSidenav('RunMySQL').toggle();
	};
	$http.get(BASE_URL + 'api/languages').then(function(Languages) {
		$scope.languages = Languages.data;
	});
	$http.get(BASE_URL + 'api/currencies').then(function(Currencies) {
		$scope.currencies = Currencies.data;
	});
	$http.get(BASE_URL + 'api/countries').then(function(Countries) {
		$scope.countries = Countries.data;
		var countries = JSON.stringify($scope.countries);
	});
	$http.get(BASE_URL + 'api/timezones').then(function(Timezones) {
		$scope.timezones = Timezones.data;
	});
	$http.get(BASE_URL + 'api/accounts').then(function(Accounts) {
		$scope.accounts = Accounts.data;
	});
	$scope.systemInfo = function(ev) {
		$mdDialog.show({
			templateUrl: 'system_info.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};
	$scope.UninstallLicense = function(ev) {
		$mdDialog.show({
			templateUrl: 'uninstall.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};
	$scope.activateModuleToggle = function(ev) {
		$mdDialog.show({
			templateUrl: 'activate_module.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};
	globals.module_settings = function() {
		$http.get(BASE_URL + 'settings/get_modules', config).then(function(response) {
			$scope.module = response.data;
		});
	};
	$scope.checkingModule = false;
	$scope.activateModule = function() {
		$scope.checkingModule = true;
		var dataObj = $.param({
			license_key: $scope.module_license_key,
		});
		$http.post(BASE_URL + 'modules/activate_module/hr', dataObj, config)
		.then(
			function(response) {
				$scope.checkingModule = false;
				if (response.data.success == true) {
					$mdDialog.cancel();
					showToast(NTFTITLE, response.data.message, ' success');
					globals.module_settings();
				} else {
					showToast(NTFTITLE, response.data.message, ' danger');
				}
			},
			function(response) {
				$scope.checkingModule = false;
			}
			);
	};
	$scope.checkingModuleLicense = false;
	$scope.enableModule = function(ev) {
		$scope.checkingModuleLicense = true;
		$http.post(BASE_URL + 'modules/enable_module/hr', config)
		.then(
			function(response) {
				$scope.checkingModuleLicense = false;
				if (response.data.success == true) {
					showToast(NTFTITLE, response.data.message, ' success');
					globals.module_settings();
				} else {
					showToast(NTFTITLE, response.data.message, ' danger');
					$mdDialog.show({
						templateUrl: 'activate_module.html',
						scope: $scope,
						preserveScope: true,
						targetEvent: ev
					});
				}
			},
			function(response) {
				$scope.checkingModuleLicense = false;
			}
			);
	};
	$scope.disablingModule = false;
	$scope.disableModule = function() {
		$scope.disablingModule = true;
		var confirm = $mdDialog.confirm()
		.title('Disable HR Module')
		.textContent('Are you sure, you want to disable HR Module')
		.ariaLabel('Disable HR Module')
		.targetEvent()
		.ok('Disable')
		.cancel('Cancel');
		$mdDialog.show(confirm).then(function() {
			$http.post(BASE_URL + 'modules/disable_module/hr', config)
			.then(
				function(response) {
					$scope.disablingModule = false;
					if (response.data.success == true) {
						showToast(NTFTITLE, response.data.message, ' success');
						globals.module_settings();
					} else {
						showToast(NTFTITLE, response.data.message, ' danger');
					}
				},
				function(response) {
					$scope.disablingModule = false;
				}
				);
		});
	};
	$scope.executing = false;
	$scope.RunMySQLQuery = function() {
		$scope.executing = true;
		if (!$scope.mysql_query) {
			var dataObj = $.param({
				mysql_query: '',
			});
		} else {
			var dataObj = $.param({
				mysql_query: $scope.mysql_query,
			});
		}
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$http.post(BASE_URL + 'settings/execute_mysql_query/', dataObj, config)
		.then(
			function(response) {
				$scope.executing = false;
				if (response.data.success == true) {
					globals.mdToast('success', response.data.message);
					$mdSidenav('RunMySQL').close();
					$scope.mysql_query = '';
				} else if (response.data.success == 'info') {
					globals.mdToast('success', response.data.message);
					showToast(NTFTITLE, response.data.info_message, ' success');
					console.log('%cResult for your following query: ' + '%c' +
						$scope.mysql_query + '', 'font-weight: 800', 'color: blue;font-weight: bold');
					console.log(response.data.result);
					$scope.mysql_query = '';
				} else {
					globals.mdToast('error', response.data.message);
				}
			},
			function(response) {
				$scope.executing = false;
			}
			);
	};
	$scope.RunMySQLFile = function() {
		$scope.executing = true;
		var dataObj = {
			file: $scope.sql_run_file,
		};
		var uploadUrl = BASE_URL + 'settings/run_sql_file/';
		fileUpload.uploadFileWithData(dataObj, uploadUrl, function(response) {
			if (response.success == true) {
				$('input[type="file"]').val('');
				globals.mdToast('success', response.message);
				if (response.error) {
					if (response.error.message) {
						showToast(NTFTITLE, response.error.message, ' warning');
					}
				}
				$mdSidenav('RunMySQL').close();
			} else {
				globals.mdToast('error', response.message);
			}
			$scope.executing = false;
		});
	};
	$scope.replying = false;
	$scope.saveCustomization = function() {
		$scope.replying = true;
		var dataObj = {
			applogo: $scope.applogo_img,
			navlogo: $scope.navlogo_img,
			admin_login_image: $scope.admin_login_image_img,
			client_login_image: $scope.client_login_image_img,
			favicon: $scope.favicon_img,
			title: $scope.rebrand.title,
			preloader: $scope.preloader,
			admin_login_text: $scope.rebrand.admin_login_text,
			client_login_text: $scope.rebrand.client_login_text,
			meta_keywords: $scope.rebrand.meta_keywords,
			meta_description: $scope.rebrand.meta_description,
			support_button_title: $scope.rebrand.support_button_title,
			support_button_link: $scope.rebrand.support_button_link,
			disable_preloader: ($scope.rebrand.disable_preloader == true) ? '1' : '0',
			enable_support_button_on_client: ($scope.rebrand.enable_support_button_on_client == true) ? '1' : '0',
		};
		var uploadUrl = BASE_URL + 'settings/save_config/';
		fileUpload.uploadFileWithData(dataObj, uploadUrl, function(response) {
			if (response.success == true) {
				$('input[type="file"]').val('');
				$scope.admin_login_image_img = '';
				$scope.client_login_image_img = '';
				$scope.favicon_img = '';
				$scope.applogo_img = '';
				$scope.navlogo_img = '';
				$http.get(BASE_URL + 'api/load_config').then(function(Data) {
					$scope.rebrand = Data.data;
				});
				$http.get(BASE_URL + 'api/settings_detail').then(function(Settings) {
					$scope.settings_detail = Settings.data;
				});
				showToast(NTFTITLE, response.message, ' success');
			} else {
				showToast(NTFTITLE, response.message, ' danger');
			}
			$scope.replying = false;
		});
	};
	$scope.get_payment_methods = function() {
		var deferred = $q.defer();
		$http.get(BASE_URL + 'settings/get_payment_methods').then(function(Data) {
			$scope.gateways = Data.data;
			deferred.resolve();
		});
	}
	$scope.UpdatePaymentGateway = function(relation) {
		$scope.saving = true;
		var dataObj = $.param({
			input_value1: $scope.gateway.input_value1,
			input_value2: $scope.gateway.input_label2 ? $scope.gateway.input_value2 : null,
			input_value3: $scope.gateway.input_label3 ? $scope.gateway.input_value3 : null,
			payment_record_account: $scope.gateway.payment_record_account,
			active: +$scope.gateway.active,
			sandbox_account: +$scope.gateway.sandbox_account,
		});
		$http.post(BASE_URL + 'settings/update_payment_gateway/' + relation, dataObj, config)
		.then(
			function(response) {
				$scope.saving = false;
				if (response.data.success == true) {
					$mdDialog.cancel();
					showToast(NTFTITLE, response.data.message, ' success');
				} else {
					showToast(NTFTITLE, response.data.message, ' danger');
				}
			},
			function(response) {
				$scope.saving = false;
			}
			);
	};
	$scope.paymentGateway = function(gateway) {
		$scope.gateway = gateway;
		$mdDialog.show({
			templateUrl: 'update_payment_method.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: gateway
		});
	};
	$scope.get_database_backup = function() {
		$scope.dbLoader = true;
		var deferred = $q.defer();
		$scope.db_list = {
			order: '',
			limit: 5,
			page: 1
		};
		$scope.promise = deferred.promise;
		$http.get(BASE_URL + 'settings/get_backup').then(function(Data) {
			$scope.db_backup = Data.data;
			deferred.resolve();
			$scope.limitOptions = [5, 10, 15, 20];
			if ($scope.db_backup.length > 20) {
				$scope.limitOptions = [5, 10, 15, 20, $scope.db_backup.length];
			}
			$scope.dbLoader = false;
			$scope.BackupDatabase = function(ev) {
				$mdDialog.show({
					templateUrl: 'backup.html',
					parent: angular.element(document.body),
					clickOutsideToClose: false,
					fullscreen: false,
					escapeToClose: false
				});
				$http.post(BASE_URL + 'settings/db_backup').then(function(Backup) {
					if (Backup.data.success == true) {
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: Backup.data.message,
							class_name: 'color success'
						});
						$http.get(BASE_URL + 'settings/get_backup').then(function(Data) {
							$scope.db_backup = Data.data;
						});
					} else {
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: Backup.data.message,
							class_name: 'color danger'
						});
					}
					$mdDialog.cancel();
				});
			};
		});
	}
	$scope.RestoreDatabase = function(ev) {
		buildToggler('RestoreDatabaseNav');
	};
	$scope.Restoring = function(id) {
		$mdDialog.show({
			templateUrl: 'restoring.html',
			parent: angular.element(document.body),
			clickOutsideToClose: false,
			fullscreen: false,
			escapeToClose: false
		});
	};
	$scope.RestoreBackup = function(id) {
		var confirm = $mdDialog.confirm()
		.title($scope.lang.restorethisfile)
		.textContent($scope.lang.restorethisfile_msg)
		.ariaLabel('Restore this file')
		.targetEvent(id)
		.ok($scope.lang.restore)
		.cancel($scope.lang.cancel);
		$mdDialog.show(confirm).then(function() {
			$mdDialog.show({
				templateUrl: 'restoring.html',
				parent: angular.element(document.body),
				clickOutsideToClose: false,
				fullscreen: false,
				escapeToClose: false
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'settings/restore_backup/' + id, config)
			.then(
				function(response) {
					console.log(response);
					if (response.data.success == true) {
						window.location.href = BASE_URL + 'login/logout';
					} else {
						$mdDialog.cancel();
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: response.data.message,
							class_name: 'color danger'
						});
					}
				},
				function(response) {
					console.log(response);
				}
				);
		}, function() {});
	};
	$scope.RemoveBackup = function(id) {
		var confirm = $mdDialog.confirm()
		.title($scope.lang.remove_database_backup)
		.textContent($scope.lang.remove_database_backup_msg)
		.ariaLabel('Delete Backup Field')
		.targetEvent(id)
		.ok($scope.lang.delete)
		.cancel($scope.lang.cancel);
		$mdDialog.show(confirm).then(function() {
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'settings/remove_backup/' + id, config)
			.then(
				function(response) {
					$mdDialog.cancel();
					if (response.data.success == true) {
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: response.data.message,
							class_name: 'color success'
						});
						$http.get(BASE_URL + 'settings/get_backup').then(function(Data) {
							$scope.db_backup = Data.data;
						});
					} else {
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: response.data.danger,
							class_name: 'color success'
						});
					}
				},
				function(response) {
					console.log(response);
				}
				);
		}, function() {
            //
        });
	};
	$scope.VersionCheck = function(ev) {
		$scope.updated = false;
		$mdDialog.show({
			templateUrl: 'version-check-template.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
		$http.get(BASE_URL + 'settings/version_details').then(function(Version) {
			$scope.versions = Version.data;
			$scope.version_number = Version.data.versions_name;
			$scope.updated = false;
		}, function(error) {
			showToast(NTFTITLE, update_error, ' danger');
		});
	};
	$scope.checkForUpdates = function(ev) {
		$mdDialog.show({
			templateUrl: 'checking.html',
			parent: angular.element(document.body),
			clickOutsideToClose: false,
			fullscreen: false,
			escapeToClose: false
		});
		$http.get(BASE_URL + 'settings/version_detail').then(function(Version) {
			$scope.Version_detail = Version.data.settings.versions_name;
			$scope.Version_latest = Version.data.version.version_number;
			if ($scope.Version_latest) {
				LATEST_VERSION = $scope.Version_latest;
			}
			$scope.msg = Version.data.msg;
			$scope.updated = Version.data.updated;
			$scope.version_log = $sce.trustAsHtml(Version.data.list_array_log);
			$scope.changeLog = $sce.trustAsHtml(Version.data.version_changelog);
			$mdDialog.cancel();
			$mdDialog.show({
				templateUrl: 'version-check-template.html',
				scope: $scope,
				preserveScope: true,
				targetEvent: ev
			});
		}, function(error) {
			showToast(NTFTITLE, update_error, ' danger');
		});
	};
	$scope.install_update = false;
	$scope.downloadUpdate = function(ev) {
		if (LATEST_VERSION) {
			$mdDialog.show({
				templateUrl: 'updating.html',
				parent: angular.element(document.body),
				clickOutsideToClose: false,
				fullscreen: false,
				escapeToClose: false
			});
			var dataObj = $.param({
				version_number: LATEST_VERSION
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'settings/download_update', dataObj, config).then(function(response) {
				if (response.data.success) {
					$scope.install_update = true;
					$mdDialog.show({
						templateUrl: 'install-update.html',
						scope: $scope,
						preserveScope: true,
						targetEvent: ev
					});
					globals.mdToast('success', response.data.message);
				} else {
					globals.mdToast('error', response.data.message);
				}
			});
		} else {
			showToast(NTFTITLE, 'ERROR', 'danger');
		}
	};
	$scope.installing = false;
	$scope.installed = false;
	$scope.installed_message = '';
	$scope.installUpdate = function(ev) {
		if (LATEST_VERSION) {
			$scope.installing = true;
			var dataObj = $.param({
				version_number: LATEST_VERSION
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'settings/install_update', dataObj, config).then(function(response) {
				if (response.data.success) {
					globals.mdToast('success', response.data.message);
					$scope.installed_message = response.data.message;
					if (response.data.error) {
						if (response.data.error.message) {
							showToast(response.data.warning, response.data.error.message, ' warning');
						}
					}
					$scope.installing = false;
					$scope.installed = true;
					setTimeout(function() {
						location.reload(true);
					}, 1500);
				} else {
					$scope.installing = false;
					globals.mdToast('error', response.data.message);
				}
			});
		} else {
			showToast(NTFTITLE, 'ERROR', 'danger');
		}
	};
	$scope.uninstall_confirm = false;
	$scope.uninstalling = false;
	$scope.RemoveLicense = function(ev) {
		$scope.uninstalling = true;
		var dataObj = $.param({
			confirm: +$scope.uninstall_confirm
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$http.post(BASE_URL + 'settings/uninstall', dataObj, config).then(function(response) {
			if (response.data.success) {
				globals.mdToast('success', response.data.message);
				setTimeout(function() {
					location.reload(true);
				}, 1500);
			} else {
				$scope.uninstalling = false;
				globals.mdToast('error', response.data.message);
			}
		});
	};
	$http.get(BASE_URL + 'api/load_config').then(function(Data) {
		$scope.rebrand = Data.data;
		if ($scope.rebrand.enable_support_button_on_client == '1') {
			$scope.rebrand.enable_support_button_on_client = true;
		} else {
			$scope.rebrand.enable_support_button_on_client = false;
		}
	});
	$scope.settings = {};
	$scope.settings.loader = true;
	$http.get(BASE_URL + 'api/settings_detail').then(function(Settings) {
		$scope.settings_detail = Settings.data;
		$scope.settings.loader = false;
		$scope.getStates($scope.settings_detail.country_id);
		$scope.sendingTestEmail = false;
		$scope.sendTestEmail = function() {
			$scope.sendingTestEmail = true;
			var dataObj = $.param({
				email: $scope.settings_detail.testEmail
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'settings/sendTestEmail';
			$http.post(posturl, dataObj, config)
			.then(
				function(response) {
					$scope.sendingTestEmail = false;
					if (response.data.success) {
						showToast(NTFTITLE, response.data.message, ' success');
					} else {
						showToast(NTFTITLE, response.data.message, ' danger');
					}
				},
				function(response) {
					$scope.sendingTestEmail = false;
					showToast(NTFTITLE, email_error, ' danger');
				});
		};
		if ($scope.settings_detail.pushState === '1') {
			$scope.settings_detail.pushState = true;
		} else {
			$scope.settings_detail.pushState = false;
		}
		if ($scope.settings_detail.two_factor_authentication === '1') {
			$scope.settings_detail.two_factor_authentication = true;
		} else {
			$scope.settings_detail.two_factor_authentication = false;
		}
		if ($scope.settings_detail.voicenotification === '1') {
			$scope.settings_detail.voicenotification = true;
		} else {
			$scope.settings_detail.voicenotification = false;
		}
		if ($scope.settings_detail.paypalenable === '1') {
			$scope.settings_detail.paypalenable = true;
		} else {
			$scope.settings_detail.paypalenable = false;
		}
		if ($scope.settings_detail.paypalsandbox === '1') {
			$scope.settings_detail.paypalsandbox = true;
		} else {
			$scope.settings_detail.paypalsandbox = false;
		}
		if ($scope.settings_detail.authorize_enable === '1') {
			$scope.settings_detail.authorize_enable = true;
		} else {
			$scope.settings_detail.authorize_enable = false;
		}
		if ($scope.settings_detail.is_mysql == '1') {
			$scope.settings_detail.is_mysql = true;
		} else {
			$scope.settings_detail.is_mysql = false;
		}
		$scope.savingSettings = false;
		$scope.UpdateSettings = function() {
			$scope.savingSettings = true;
			var dataObj = $.param({
				crm_name: $scope.settings_detail.crm_name,
				company: $scope.settings_detail.company,
				email: $scope.settings_detail.email,
				address: $scope.settings_detail.address,
				city: $scope.settings_detail.city,
				town: $scope.settings_detail.town,
				state: $scope.settings_detail.state,
				country_id: $scope.settings_detail.country_id,
				zipcode: $scope.settings_detail.zipcode,
				phone: $scope.settings_detail.phone,
				fax: $scope.settings_detail.fax,
				vatnumber: $scope.settings_detail.vatnumber,
				taxoffice: $scope.settings_detail.taxoffice,
				currencyid: $scope.settings_detail.currencyid,
				termtitle: $scope.settings_detail.termtitle,
				termdescription: $scope.settings_detail.termdescription,
				dateformat: $scope.settings_detail.dateformat,
				languageid: $scope.settings_detail.languageid,
				default_timezone: $scope.settings_detail.default_timezone,
				smtphost: $scope.settings_detail.smtphost,
				smtpport: $scope.settings_detail.smtpport,
				emailcharset: $scope.settings_detail.emailcharset,
				email_encryption: $scope.settings_detail.email_encryption,
				smtpusername: $scope.settings_detail.smtpusername,
				smtppassoword: $scope.settings_detail.smtppassoword,
				sendermail: $scope.settings_detail.sendermail,
				sender_name: $scope.settings_detail.sender_name,
				accepted_files_formats: $scope.settings_detail.accepted_files_formats,
				allowed_ip_adresses: $scope.settings_detail.allowed_ip_adresses,
				pushState: $scope.settings_detail.pushState,
				voicenotification: $scope.settings_detail.voicenotification,
				state_id: $scope.settings_detail.state_id,
				is_mysql: $scope.settings_detail.is_mysql,
				inv_prefix: $scope.finance.inv_prefix,
				inv_suffix: $scope.finance.inv_suffix,
				project_prefix: $scope.finance.project_prefix,
				project_suffix: $scope.finance.project_suffix,
				order_prefix: $scope.finance.order_prefix,
				order_suffix: $scope.finance.order_suffix,
				expense_suffix: $scope.finance.expense_suffix,
				expense_prefix: $scope.finance.expense_prefix,
				proposal_suffix: $scope.finance.proposal_suffix,
				proposal_prefix: $scope.finance.proposal_prefix,
				tax_label: $scope.finance.tax_label,
				invoice_series: $scope.finance.invoice_series,
				project_series: $scope.finance.project_series,
				product_series: $scope.finance.product_series,
				order_series: $scope.finance.order_series,
				proposal_series: $scope.finance.proposal_series,
				vendor_series: $scope.finance.vendor_series,
				customer_series: $scope.finance.customer_series,
				expense_series: $scope.finance.expense_series,
				lead_series: $scope.finance.lead_series,
				ticket_series: $scope.finance.ticket_series,
				staff_series: $scope.finance.staff_series,
				task_series: $scope.finance.task_series,
				purchase_series: $scope.finance.purchase_series,
				product_prefix: $scope.finance.product_prefix,
				vendor_prefix: $scope.finance.vendor_prefix,
				customer_prefix: $scope.finance.customer_prefix,
				lead_prefix: $scope.finance.lead_prefix,
				ticket_prefix: $scope.finance.ticket_prefix,
				staff_prefix: $scope.finance.staff_prefix,
				purchase_prefix: $scope.finance.purchase_prefix,
				task_prefix: $scope.finance.task_prefix,
				deposit_series: $scope.finance.deposit_series,
				deposit_prefix: $scope.finance.deposit_prefix,
				thousand_separator: $scope.settings_detail.thousand_separator,
				decimal_separator: $scope.settings_detail.decimal_separator,
				currency_position: $scope.settings_detail.currency_position,
				currency_display: $scope.settings_detail.currency_display,
				email_type: $scope.settings_detail.email_type,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'settings/update/ciuis';
			$http.post(posturl, dataObj, config)
			.then(
				function(response) {
					$scope.savingSettings = false;
					if (response.data.success == true) {
						globals.mdToast('success', response.data.message);
					} else {
						globals.mdToast('error', response.data.message);
					}
				},
				function(response) {
					$scope.savingSettings = false;
					showToast(NTFTITLE, 'Error', ' danger');
				}
				);
		};
		$scope.seePasswordModal = function(ev) {
			$mdDialog.show({
				templateUrl: 'see_smtp_password.html',
				scope: $scope,
				preserveScope: true,
				targetEvent: ev
			});
		};
		$scope.viewPassword = false;
		$scope.viewing = false;
		$scope.viewSMTPPassword = function() {
			$scope.viewing = true;
			var dataObj = $.param({
				password: $scope.your_login_password,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'settings/get_smtp_password';
			$http.post(posturl, dataObj, config)
			.then(
				function(response) {
					$scope.viewing = false;
					if (response.data.success == true) {
						$scope.viewPassword = true;
						$scope.final_smtp_password = response.data.password;
					} else {
						$scope.viewPassword = false;
						showToast(NTFTITLE, response.data.message, ' danger');
					}
				},
				function(response) {
					$scope.viewPassword = false;
					$scope.viewing = false;
					showToast(NTFTITLE, 'Error', ' danger');
				}
				);
		};
	});

	$scope.get_custom_fields = function() {
		$scope.customfieldLoader = true;
		var deferred = $q.defer();
		$scope.customfield_list = {
			order: '',
			limit: 10,
			page: 1
		};
		$scope.promise = deferred.promise;
		$http.get(BASE_URL + 'api/custom_fields/').then(function(custom_fields) {
			$scope.custom_fields = custom_fields.data;
			deferred.resolve();
			$scope.limitOptions = [5, 10, 15, 20];
			if ($scope.custom_fields.length > 20) {
				$scope.limitOptions = [5, 10, 15, 20, $scope.custom_fields.length];
			}
			$scope.customfieldLoader = false;
		});
	};
	$http.get(BASE_URL + 'api/get_appconfig/').then(function(Data) {
		$scope.finance = Data.data;
		$scope.finance.decimal_separator = $scope.finance.decimal_separator;
		$scope.finance.thousand_separator = $scope.finance.thousand_separator;
		$scope.finance.invoice_series = ($scope.finance.invoice_series) ? (+$scope.finance.invoice_series) : '';
		$scope.finance.project_series = ($scope.finance.project_series) ? (+$scope.finance.project_series) : '';
		$scope.finance.product_series = ($scope.finance.product_series) ? (+$scope.finance.product_series) : '';
		$scope.finance.order_series = ($scope.finance.order_series) ? (+$scope.finance.order_series) : '';
		$scope.finance.proposal_series = ($scope.finance.proposal_series) ? (+$scope.finance.proposal_series) : '';
		$scope.finance.vendor_series = ($scope.finance.vendor_series) ? (+$scope.finance.vendor_series) : '';
		$scope.finance.customer_series = ($scope.finance.customer_series) ? (+$scope.finance.customer_series) : '';
		$scope.finance.expense_series = ($scope.finance.expense_series) ? (+$scope.finance.expense_series) : '';
		$scope.finance.lead_series = ($scope.finance.lead_series) ? (+$scope.finance.lead_series) : '';
		$scope.finance.ticket_series = ($scope.finance.ticket_series) ? (+$scope.finance.ticket_series) : '';
		$scope.finance.staff_series = ($scope.finance.staff_series) ? (+$scope.finance.staff_series) : '';
		$scope.finance.purchase_series = ($scope.finance.purchase_series) ? (+$scope.finance.purchase_series) : '';
		$scope.finance.task_series = ($scope.finance.task_series) ? (+$scope.finance.task_series) : '';
		$scope.finance.deposit_series = ($scope.finance.deposit_series) ? (+$scope.finance.deposit_series) : '';
	});
	$scope.CreateCustomField = function() {
		buildToggler('CreateCustomField');
	};
	$scope.GetFieldDetail = function(id) {
		$http.get(BASE_URL + 'api/custom_field_data_by_id/' + id).then(function(selected_field) {
			$scope.selected_field = selected_field.data;
			$scope.AddOptionToField = function() {
				$scope.selected_field.data.push({
					name: $scope.selected_field.new_option_name,
				});
				for (var i = 0; i < $scope.selected_field.data.length; i++) {
					$scope.selected_field.data[i].id = i;
				}
				$scope.selected_field.new_option_name = null;
			};
			$scope.RemoveFieldOption = function(index) {
				$scope.selected_field.data.splice(index, 1);
			};
			$scope.UpdateCustomField = function() {
				$scope.saving_customfield = true;
				if ($scope.selected_field.type === 'select') {
					$scope.field_data = JSON.stringify($scope.selected_field.data);
				} else {
					$scope.field_data = null;
				}
				var dataObj = $.param({
					name: $scope.selected_field.name,
					type: $scope.selected_field.type,
					order: $scope.selected_field.order,
					data: $scope.field_data,
					relation: $scope.selected_field.relation,
					icon: '',
					permission: $scope.selected_field.permission,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				var posturl = BASE_URL + 'settings/update_custom_field/' + $scope.selected_field.id;
				$http.post(posturl, dataObj, config)
				.then(
					function(response) {
						$scope.saving_customfield = false;
						if (response.data.success == true) {
							$mdSidenav('FieldDetail').close();
							globals.mdToast('success', response.data.message);
							$http.get(BASE_URL + 'api/custom_fields/').then(function(custom_fields) {
								$scope.custom_fields = custom_fields.data;
							});
							$scope.new_custom_field = [];
						} else {
							globals.mdToast('error', response.data.message);
						}
					},
					function(response) {
						$scope.saving_customfield = true;
						console.log(response);
					}
					);
			};
		});
	};
	$scope.RemoveCustomField = function(index) {
		var field = $scope.custom_fields[index];
		var confirm = $mdDialog.confirm()
		.title($scope.lang.remove_custom_field)
		.textContent($scope.lang.custom_field_remove_msg)
		.ariaLabel('Delete Custom Field')
		.targetEvent(index)
		.ok($scope.lang.delete)
		.cancel($scope.lang.cancel);
		$mdDialog.show(confirm).then(function() {
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'settings/remove_custom_field/' + index, config)
			.then(
				function(response) {
					$scope.saving_customfield = true;
					if (response.data.success == true) {
						globals.mdToast('success', response.data.message);
						$http.get(BASE_URL + 'api/custom_fields/').then(function(custom_fields) {
							$scope.custom_fields = custom_fields.data;
						});
					} else {
						globals.mdToast('error', response.data.message);
					}
				},
				function(response) {
					console.log(response);
				}
				);
		}, function() {});
	};
	$scope.FieldDetail = function() {
		buildToggler('FieldDetail');
	};
	$scope.select_options = [];
	$scope.new_custom_field = {
		permission: false,
		new_option_name: ''
	};
	$scope.AddOption = function() {
		$scope.select_options.push({
			name: $scope.new_custom_field.new_option_name,
		});
		for (var i = 0; i < $scope.select_options.length; i++) {
			$scope.select_options[i].id = i;
		}
		$scope.new_custom_field.new_option_name = null;
	};
	$scope.RemoveOption = function(index) {
		$scope.select_options.splice(index, 1);
	};
	$scope.AddCustomField = function() {
		$scope.saving_customfield = true;
		if ($scope.new_custom_field.type === 'select') {
			$scope.field_data = JSON.stringify($scope.select_options);
		} else {
			$scope.field_data = null;
		}
		var dataObj = $.param({
			name: $scope.new_custom_field.name,
			type: $scope.new_custom_field.type,
			order: $scope.new_custom_field.order,
			data: $scope.field_data,
			relation: $scope.new_custom_field.relation,
			icon: '',
			permission: $scope.new_custom_field.permission,
			active: 'true',
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'settings/create_custom_field/';
		$http.post(posturl, dataObj, config)
		.then(
			function(response) {
				$scope.saving_customfield = false;
				if (response.data.success == true) {
					$mdSidenav('CreateCustomField').close();
					globals.mdToast('success', response.data.message);
					$http.get(BASE_URL + 'api/custom_fields/').then(function(custom_fields) {
						$scope.custom_fields = custom_fields.data;
					});
				} else {
					globals.mdToast('error', response.data.message);
				}
			},
			function(response) {
				$scope.saving_customfield = false;
				console.log(response);
			}
			);
	};
	$scope.UpdateCustomFieldStatus = function(id, value) {
		$http.post(BASE_URL + 'settings/update_custom_field_status/' + id + '/' + value + '/')
		.then(
			function(response) {
				if (response.data.success == true) {
					$http.get(BASE_URL + 'api/custom_fields/').then(function(custom_fields) {
						$scope.custom_fields = custom_fields.data;
					});
				} else {
					globals.mdToast('error', response.data.message);
				}
			},
			function(response) {
				console.log(response);
			}
			);
	};
	$scope.viewRole = true;
	$scope.editRole = false;
	$scope.createRole = false;
	$scope.getRoles = false;
	$scope.addRole = function() {
		$scope.getRoles = true;
		$http.get(BASE_URL + 'settings/get_permission').then(function(Permissions) {
			$scope.all_permissions = Permissions.data;
			$scope.viewRole = false;
			$scope.createRole = true;
			$scope.getRoles = false;
		});
	};
	$scope.get_permissions_by_type = function(type) {
		var permission_all = +$scope.permission_all;
		$http.get(BASE_URL + 'settings/get_permission/' + type + '/' + permission_all).then(function(Permissions) {
			$scope.all_permissions = Permissions.data;
		});
	};
	$scope.create_role = function() {
		$scope.creatingRole = true;
		var dataObj = $.param({
			'role': $scope.newrole.new_role_name,
			'type': $scope.newrole.usertype,
			'permissions': $scope.all_permissions
		});
		var posturl = BASE_URL + "settings/create_role";
		$http.post(posturl, dataObj, config)
		.then(
			function(response) {
				$scope.creatingRole = false;
				if (response.data.success == true) {
					$scope.get_roles();
					globals.mdToast('success', response.data.message);
					$scope.createRole = false;
					$scope.viewRole = true;
				} else {
					globals.mdToast('error', response.data.message);
				}
			});
	};
	$scope.get_roles = function() {
		$http.get(BASE_URL + 'settings/get_roles').then(function(Roles) {
			$scope.roles = Roles.data;
		});
	};
	$scope.get_role = function(id) {
		$scope.viewRole = false;
		$scope.editRole = true;
		$http.get(BASE_URL + 'settings/get_role/' + id).then(function(Role) {
			$scope.permissions = Role.data.permissions_data;
			$scope.role_name = Role.data.role_name;
			$scope.role_type = Role.data.role_type;
			$scope.role_id = id;
		});
	};
	$scope.update_role = function(id) {
		$scope.updatingRole = true;
		var dataObj = $.param({
			'role': $scope.role_name,
			'type': $scope.role_type,
			'permissions': $scope.permissions,
		});
		var posturl = BASE_URL + "settings/update_role/" + id;
		$http.post(posturl, dataObj, config)
		.then(
			function(response) {
				$scope.updatingRole = false;
				if (response.data.success == true) {
					$scope.get_roles();
					globals.mdToast('success', response.data.message);
					$scope.editRole = false;
					$scope.viewRole = true;
				} else {
					globals.mdToast('error', response.data.message);
				}
			});
	};
	$scope.delete_role = function(id) {
		globals.deleteDialog(lang.attention, lang.delete_role_meesage, id, lang.doIt, lang.cancel, 'settings/delete_role/' + id, function(response) {
			if (response.success == true) {
				globals.mdToast('success', response.message);
				$scope.get_roles();
			} else {
				globals.mdToast('error', response.message);
			}
		});
	};
	$scope.cancel_role = function() {
		$scope.getRoles = false;
		$scope.viewRole = true;
		$scope.createRole = false;
		$scope.editRole = false;
		$scope.get_roles();
	};
	$scope.custom_fields_types = [{
		'id': '1',
		'type': 'input',
		'name': lang.input
	}, {
		'id': '2',
		'type': 'date',
		'name': lang.datepicker
	}, {
		'id': '3',
		'type': 'textarea',
		'name': lang.textarea
	}, {
		'id': '4',
		'type': 'select',
		'name': lang.select
	}];
	$scope.custom_fields_relation_types = [{
		'id': '1',
		'relation': 'invoice',
		'name': lang.invoice
	}, {
		'id': '2',
		'relation': 'proposal',
		'name': lang.proposal
	}, {
		'id': '3',
		'relation': 'customer',
		'name': lang.customer
	}, {
		'id': '4',
		'relation': 'task',
		'name': lang.task
	}, {
		'id': '5',
		'relation': 'project',
		'name': lang.project
	}, {
		'id': '7',
		'relation': 'expense',
		'name': lang.expense
	}, {
		'id': '8',
		'relation': 'product',
		'name': lang.product
	}, {
		'id': '9',
		'relation': 'lead',
		'name': lang.lead
	}];
}
CiuisCRM.controller('Settings_Controller', Settings_Controller);