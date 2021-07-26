function Leads_Controller($scope, $http, $mdSidenav, $mdDialog, $mdConstant, $filter, $q) { 
	"use strict";

	$http.get(BASE_URL + 'api/staff').then(function (Staff) {
		$scope.staff = Staff.data;
	});
	
	$http.get(BASE_URL + 'api/custom_fields_by_type/' + 'lead').then(function (custom_fields) {
		$scope.all_custom_fields = custom_fields.data;
		$scope.custom_fields = $filter('filter')($scope.all_custom_fields, {
			active: 'true',
		});
	});

	$scope.updateColumns = function(column, value) {
		var dataObj = $.param({
			column: column,
			value: +value,
		});
		var posturl = BASE_URL + 'api/update_columns/leads';
		$http.post(posturl, dataObj, config)
		.then(
			function (response) {
			}, function(error) {}
			);
	};

	$scope.KanbanBoard = true;
	$http.get(BASE_URL + 'api/table_columns/leads').then(function (Data) {
		$scope.table_columns = Data.data;
		if ($scope.table_columns.list_view == true) {
			$scope.KanbanBoard = false;
		} else {
			$scope.KanbanBoard = true;
		}
	});

	$scope.ShowKanban = function () {
		$scope.KanbanBoard = true;
	};

	$scope.HideKanban = function () {
		$scope.KanbanBoard = false;
	};

	$scope.toggleFilter = buildToggler('ContentFilter');
	$scope.LeadSettings = buildToggler('LeadsSettings');
	$scope.Create = buildToggler('Create');
	$scope.Import = buildToggler('Import');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();
		};
	}

	globals.get_countries();
	$scope.getStates = function (country) {
		$http.get(BASE_URL + 'api/get_states/' + country).then(function (States) {
			$scope.states = States.data;
		});
	};

	$scope.close = function () {
		$mdSidenav('ContentFilter').close();
		$mdSidenav('LeadsSettings').close();
		$mdSidenav('Create').close();
		$mdSidenav('Import').close();
		$mdDialog.hide();
	};

	$scope.ConvertedStatus = function (ev) {
		$mdDialog.show({
			templateUrl: 'converted-status-template.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};

	$scope.keys = [$mdConstant.KEY_CODE.ENTER, $mdConstant.KEY_CODE.COMMA];
	$scope.tags = [];
	var semicolon = 186;
	$scope.customKeys = [$mdConstant.KEY_CODE.ENTER, $mdConstant.KEY_CODE.COMMA, semicolon];

	$http.get(BASE_URL + 'api/settings_detail').then(function (Settings) {
		$scope.settings_detail = Settings.data;

		$scope.ConvertedLeadStatus = $scope.settings_detail.converted_lead_status_id;
		$scope.MakeConvertedLedStatus = function () {
			$http.post(BASE_URL + 'leads/make_converted_status/' + $scope.ConvertedLeadStatus, config)
				.then(
					function (response) {
						console.log(response);
						$mdDialog.hide();
						get_leads();
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.RemoveConverted = function () {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.confirm()
				.title(MSG_TITLE)
				.textContent(MSG_REMOVE)
				.ariaLabel('Delete Converted Leads')
				.targetEvent($scope.ConvertedLeadStatus)
				.ok(MSG_OK)
				.cancel(MSG_CANCEL);

			$mdDialog.show(confirm).then(function () {
				$http.post(BASE_URL + 'leads/remove_converted/' + $scope.ConvertedLeadStatus, config)
					.then(
						function (response) {
							get_leads();
						},
						function (response) {
							console.log(response);
						}
					);
			}, function () {
				//
			});
		};
	});

	$http.get(BASE_URL + 'api/leads_by_leadsource_leadpage').then(function (LeadsBySource) {
		new Chart($('#leads_by_leadsource'), {
			type: 'horizontalBar',
			data: LeadsBySource.data,
			options: {
				legend: {
					display: false,
				}
			}
		});
	});

	function get_leads() {
		$http.get(BASE_URL + 'leads/get_leads').then(function (Leads) {
			$scope.leads = Leads.data;
			for (var i = 0; i < $scope.leads.length; i++) {
				if ($scope.leads[i].tags) {
					$scope.leads[i].tagss = JSON.parse($scope.leads[i].tags.data);
				}

			}
		});
	}

	$scope.leadsLoader = true;
	var deferred = $q.defer();
	$scope.lead_list = {
		order: '',
		limit: 5,
		page: 1
	};
	$scope.promise = deferred.promise;
	$http.get(BASE_URL + 'leads/get_leads').then(function (Leads) {
		$scope.leads = Leads.data;
		for (var i = 0; i < $scope.leads.length; i++) {
			if ($scope.leads[i].tags) {
				$scope.leads[i].tagss = JSON.parse($scope.leads[i].tags.data);
			}
		}
		deferred.resolve();
		$scope.limitOptions = [5, 10, 15, 20];
		if($scope.leads.length > 20 ) {
			$scope.limitOptions = [5, 10, 15, 20, $scope.leads.length];
		}

		$scope.leadsLoader = false;

		$scope.GoLeadDetail = function (index) {
			var lead = $scope.leads[index];
			window.location.href = BASE_URL + 'leads/lead/' + lead.id;
		};
	});

	$scope.dropSuccessHandler = function ($event, index, array) {
		$scope.selected_lead = $scope.leads[index];
		$scope.leads.splice($scope.leads.indexOf($scope.selected_lead), 1);
	};

	$scope.onDrop = function ($event, $data, array) {
		$scope.moved_lead = $data;
		var dataObj = $.param({
			lead_id: $scope.moved_lead.id,
			status_id: array,
		});
		$http.post(BASE_URL + 'leads/move_lead/', dataObj, config)
			.then(
				function (response) {
					get_leads();
				},
				function () {}
			);
	};

	$scope.saving = false;
	$scope.AddLead = function () {
		$scope.saving = true;
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
				active: value.active,
			});
		});
		if (!$scope.lead) {
			var dataObj = $.param({
				title: '',
				date_contacted: '',
				name: '',
				company: '',
				assigned: '',
				status: '',
				source: '',
				phone: '',
				email: '',
				website: '',
				country_id: '',
				state: '',
				state_id: '',
				city: '',
				zip: '',
				address: '',
				description: '',
				public: '',
				type: '',
				tags: '',
				custom_fields: $scope.tempArr,
			});
		} else {
			if ($scope.lead.public === true) {
				$scope.isPublic = 1;
			} else {
				$scope.isPublic = 0;
			}
			if ($scope.lead.type === true) {
				$scope.isIndividual = 1;
			} else {
				$scope.isIndividual = 0;
			}
			if ($scope.lead.date_contacted) {
				$scope.lead.date_contacted = moment($scope.lead.date_contacted).format("YYYY-MM-DD HH:mm:ss");
			}
			var dataObj = $.param({
				title: $scope.lead.title,
				date_contacted: $scope.lead.date_contacted,
				name: $scope.lead.name,
				company: $scope.lead.company,
				assigned: $scope.lead.assigned_id,
				status: $scope.lead.status_id,
				source: $scope.lead.source_id,
				phone: $scope.lead.phone,
				email: $scope.lead.email,
				website: $scope.lead.website,
				country_id: $scope.lead.country_id,
				state_id: $scope.lead.state_id,
				city: $scope.lead.city,
				zip: $scope.lead.zip,
				address: $scope.lead.address,
				description: $scope.lead.description,
				public: $scope.isPublic,
				type: $scope.isIndividual,
				tags: JSON.stringify($scope.tags),
				custom_fields: $scope.tempArr,
			});
		}
		var posturl = BASE_URL + 'leads/create/';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					$scope.saving = false;
					if (response.data.success == true) {
						$mdSidenav('Create').close();
						globals.mdToast('success', response.data.message);
						get_leads();
					} else {
						globals.mdToast('error', response.data.message);
					}
				},
				function (response) {
					$scope.saving = false;
				}
			);
	};

	$scope.filter = {};
	$scope.getOptionsFor = function (propName) {
		return ($scope.leads || []).map(function (item) {
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
	// Filtered Datas
	$scope.search = {
		name: '',
		statusname: ''
	};

	$http.get(BASE_URL + 'leads/leadstatuses').then(function (LeadStatuses) {
		$scope.leadstatuses = LeadStatuses.data;

		$scope.NewStatus = function () {
			globals.createDialog($scope.lang.new_status, $scope.lang.type_status_name,$scope.lang.status_name,  event, $scope.lang.add, $scope.lang.cancel, 'leads/add_status', function(response) {
				if (response.success == true) {
					showToast(NTFTITLE, response.message, ' success');
					$http.get(BASE_URL + 'leads/leadstatuses').then(function (LeadStatuses) {
						$scope.leadstatuses = LeadStatuses.data;
						get_leads();
					});
				} else {
					globals.mdToast('error', response.message);
				}
			});
		};

		$scope.EditStatus = function (status_id, lead_status, event) { 
			globals.editDialog($scope.lang.edit+' '+$scope.lang.lead+' '+$scope.lang.status, $scope.lang.lead_title+' '+$scope.lang.lead+' '+$scope.lang.status+' '+$scope.lang.name, $scope.lang.status+' '+$scope.lang.name, lead_status,  event, $scope.lang.save, $scope.lang.cancel, 'leads/update_status/'+status_id, function(response) {
				if (response.success == true) {
					showToast(NTFTITLE, response.message, ' success');
					$http.get(BASE_URL + 'leads/leadstatuses').then(function (LeadStatuses) {
						$scope.leadstatuses = LeadStatuses.data;
						get_leads();
					});
				} else {
					globals.mdToast('error', response.message);
				}
			});
		};
		
		$scope.DeleteLeadStatus = function (index) { 
			var status = $scope.leadstatuses[index];
			globals.deleteDialog($scope.lang.delete+' '+$scope.lang.status, $scope.lang.delete_meesage+' '+$scope.lang.status+'?', status.id, $scope.lang.delete, $scope.lang.cancel, 'leads/remove_status/'+status.id, function(response) {
				if (response.success == true) {
					showToast(NTFTITLE, response.message, ' success');
					$http.get(BASE_URL + 'leads/leadstatuses').then(function (LeadStatuses) {
						$scope.leadstatuses = LeadStatuses.data;
						get_leads();
					});
				} else {
					globals.mdToast('error', response.message);
				}
			});
		};
	});

	$http.get(BASE_URL + 'leads/leadsources').then(function (LeadSources) {
		$scope.leadssources = LeadSources.data;
		$scope.NewSource = function () {
			globals.createDialog($scope.lang.new_source, $scope.lang.type_source_name,$scope.lang.source_name,  event, $scope.lang.add, $scope.lang.cancel, 'leads/add_source', function(response) {
				if (response.success == true) {
					showToast(NTFTITLE, response.message, ' success');
					$http.get(BASE_URL + 'leads/leadsources').then(function (LeadSources) {
						$scope.leadssources = LeadSources.data;
					});
				} else {
					globals.mdToast('error', response.message);
				}
			});
		};

		$scope.EditSource = function (source_id, lead_source, event) { 
			globals.editDialog($scope.lang.edit+' '+$scope.lang.lead+' '+$scope.lang.source, $scope.lang.lead_title+' '+$scope.lang.lead+' '+$scope.lang.source+' '+$scope.lang.name, $scope.lang.source+' '+$scope.lang.name, lead_source,  event, $scope.lang.save, $scope.lang.cancel, 'leads/update_source/'+source_id, function(response) {
				if (response.success == true) {
					showToast(NTFTITLE, response.message, ' success');
					$http.get(BASE_URL + 'leads/leadsources').then(function (LeadSources) {
						$scope.leadssources = LeadSources.data;
					});
				} else {
					globals.mdToast('error', response.message);
				}
			});
		};
		
		$scope.DeleteLeadSource = function (index) { 
			var source = $scope.leadssources[index];
			globals.deleteDialog($scope.lang.delete+' '+$scope.lang.source, $scope.lang.delete_meesage+' '+$scope.lang.source+'?', source.id, $scope.lang.delete, $scope.lang.cancel, 'leads/remove_source/'+source.id, function(response) {
				if (response.success == true) {
					$scope.leadssources.splice($scope.leadssources.indexOf(source), 1);
					showToast(NTFTITLE, response.message, ' success');
				} else {
					globals.mdToast('error', response.message);
				}
			});
		};
	});
}

function Lead_Controller($scope, $http, $mdSidenav, $mdDialog, $filter) {
	"use strict";

	$scope.ReminderForm = buildToggler('ReminderForm');
	$scope.Update = buildToggler('Update');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}

	$http.get(BASE_URL + 'api/staff').then(function (Staff) {
		$scope.staff = Staff.data;
	});

	globals.get_countries();
	$scope.getStates = function (country) {
		$http.get(BASE_URL + 'api/get_states/' + country).then(function (States) {
			$scope.states = States.data;
		});
	};
	
	$scope.close = function () {
		$mdSidenav('ReminderForm').close();
		$mdSidenav('Update').close();
	};

	$http.get(BASE_URL + 'api/custom_fields_data_by_type/' + 'lead/' + LEADID).then(function (custom_fields) {
		$scope.custom_fields = custom_fields.data;
	});

	$scope.leadsLoader = true;
	$http.get(BASE_URL + 'leads/get_lead/' + LEADID).then(function (Lead) {
		$scope.lead = Lead.data;
		$scope.getStates($scope.lead.country_id);
		$scope.leadsLoader = false;

		$scope.MarkLeadAs = function (status) {
			if (status === 1) {
				$scope.lead.lost = 1;
				$scope.valuOn = 1;
				$scope.TypeOn = 'lost';
			}
			if (status === 2) {
				$scope.lead.lost = 0;
				$scope.valuOn = 2;
				$scope.TypeOn = 'lost';
			}
			if (status === 3) {
				$scope.lead.junk = 1;
				$scope.valuOn = 3;
				$scope.TypeOn = 'junk';
			}
			if (status === 4) {
				$scope.lead.junk = 0;
				$scope.valuOn = 4;
				$scope.TypeOn = 'junk';
			}
			var dataObj = $.param({
				value: $scope.valuOn,
			});
			$http.post(BASE_URL + 'leads/mark_as_lead/' + LEADID, dataObj, config)
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

		$scope.saving = false;
		$scope.UpdateLead = function () {
			$scope.saving = true;
			if ($scope.lead.public === true) {
				$scope.isPublic = 1;
			} else {
				$scope.isPublic = 0;
			}
			if ($scope.lead.type === true) {
				$scope.isIndividual = 1;
			} else {
				$scope.isIndividual = 0;
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
			var dataObj = $.param({
				title: $scope.lead.title,
				name: $scope.lead.name,
				company: $scope.lead.company,
				assigned_id: $scope.lead.assigned_id,
				status: $scope.lead.status_id,
				source: $scope.lead.source_id,
				phone: $scope.lead.phone,
				email: $scope.lead.email,
				website: $scope.lead.website,
				country_id: $scope.lead.country_id,
				state_id: $scope.lead.state_id,
				city: $scope.lead.city,
				zip: $scope.lead.zip,
				address: $scope.lead.address,
				description: $scope.lead.description,
				public: $scope.isPublic,
				type: $scope.isIndividual,
				custom_fields: $scope.tempArr,
				status_id: +$scope.lead.lead_status_id,
			});
			var posturl = BASE_URL + 'leads/update/' + LEADID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.saving = false;
						if (response.data.success == true) {
							showToast(NTFTITLE, response.data.message, ' success');
							$mdSidenav('Update').close();
						} else {
							globals.mdToast('error', response.data.message);
						}
					},
					function (response) {
						$scope.saving = false;
					}
				);
		};
	});

	$http.get(BASE_URL + 'leads/leadstatuses').then(function (LeadStatuses) {
		$scope.statuses = LeadStatuses.data;
	});

	$http.get(BASE_URL + 'leads/leadsources').then(function (LeadSources) {
		$scope.sources = LeadSources.data;
	});

	$scope.Delete = function (index) {
		globals.deleteDialog(lang.attention, lang.delete_lead, LEADID, lang.doIt, lang.cancel, 'leads/remove/' + LEADID, function(response) {
			if (response.success == true) {
				window.location.href = BASE_URL + 'leads';
			} else {
				globals.mdToast('error',response.message);
			}
		});
	};

	$scope.Convert = function (index) {
		globals.deleteDialog(lang.convert_title, lang.convert_text, LEADID, lang.convert, lang.cancel, 'leads/convert/' + LEADID, function(response) {
			if (response.success == true) {
				window.location.href = BASE_URL + 'customers/customer/' + response.id;
			} else {							
				globals.mdToast('error',response.message);
			}
		});
	};

	$http.get(BASE_URL + 'api/reminders_by_type/lead/' + LEADID).then(function (Reminders) {
		$scope.in_reminders = Reminders.data;
		$scope.AddReminder = function () {
			var dataObj = $.param({
				description: $scope.reminder_description,
				date: moment($scope.reminder_date).format("YYYY-MM-DD HH:mm:ss"),
				staff: $scope.reminder_staff,
				relation_type: 'lead',
				relation: LEADID,
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

	$http.get(BASE_URL + 'api/notes/lead/' + LEADID).then(function (Notes) {
		$scope.notes = Notes.data;
		$scope.AddNote = function () {
			var dataObj = $.param({
				description: $scope.note,
				relation_type: 'lead',
				relation: LEADID,
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
							$http.get(BASE_URL + 'api/notes/lead/' + LEADID).then(function (Notes) {
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

	$http.get(BASE_URL + 'api/proposals').then(function (Proposals) {
		$scope.all_proposals = Proposals.data;
		$scope.proposals = $filter('filter')($scope.all_proposals, {
			relation_type: "lead",
			relation: LEADID
		});
	});
}

CiuisCRM.controller('Leads_Controller', Leads_Controller);
CiuisCRM.controller('Lead_Controller', Lead_Controller);