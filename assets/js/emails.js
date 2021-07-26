function Emails_Controller($scope, $http, $mdSidenav, $mdDialog, $filter, $sce) {
	'use strict';

	$scope.close = function () {
		$mdDialog.hide();
	};

	$scope.template = {};
	$scope.template.loader = true;
	$scope.template.loadEmails = true;
	$http.get(BASE_URL + 'emails/get_email_templates').then(function (Templates) {
		$scope.templates = Templates.data;
		$scope.template.loader = false;
	});

	$http.get(BASE_URL + 'emails/get_emails').then(function (Emails) {
		$scope.emails = Emails.data;
		$scope.template.loadEmails = false;

		$scope.viewEmail = function (index) {
			$scope.email = $scope.emails[index];
			for (var i = 0; i < $scope.emails.length; i++) {
				if ($scope.emails[i].id == index) {
					$scope.email = $scope.emails[i];
					continue;
				}
			}
			$mdDialog.show({
				templateUrl: 'emailDialog.html',
				scope: $scope,
				preserveScope: true,
				targetEvent: $scope.email.id
			});
			$scope.email.message = $sce.trustAsHtml($scope.email.message);
		};

		$scope.MoveToTrash = function(id) {
			var posturl = BASE_URL + 'emails/move_to_trash/' + id;
			$http.post(posturl, config).then(
				function (response) {
					if (response.data.success == true) {
						showToast(NTFTITLE, response.data.message, 'success');
						$mdDialog.hide();
						$http.get(BASE_URL + 'emails/get_emails').then(function (Emails) {
							$scope.emails = Emails.data;
						});
					}
				}, function(){
				}
				);
		};

		$scope.itemsPerPage = 10;
		$scope.currentPage = 0;
		$scope.range = function () {
			var rangeSize = 10;
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
			return Math.ceil($scope.emails.length / $scope.itemsPerPage) - 1;
		};
	});
}

function Email_Controller($scope, $http, $mdSidenav, $mdDialog, $filter) {
	'use strict';

	$scope.template = {};
	$scope.template_loader = true;
	$http.get(BASE_URL + 'emails/get_email_template/'+TEMPLATEID).then(function (Template) {
		$scope.template = Template.data;
		$scope.template_loader = false;
		tinyMCE.activeEditor.setContent($scope.template.message, {format : 'raw'});
	});
	$http.get(BASE_URL + 'emails/template_fields/'+TEMPLATEID).then(function (Data) {
		$scope.template_fields = Data.data;
	});
	//tinyMCE.activeEditor.dom.addClass(tinyMCE.activeEditor.dom, 'myclass');

	$scope.saving = false;
	$scope.UpdateTemplate = function() {
		$scope.saving = true;
		var status, attachment;
		if ($scope.template.status == true) {
			status = 1;
		} else {
			status = 0;
		}
		if ($scope.template.attachment == true) {
			attachment = 1;
		} else {
			attachment = 0;
		}
		var data = tinyMCE.activeEditor.getContent({format : 'raw'});
		var dataObj = $.param({
			subject: $scope.template.subject,
			from_name: $scope.template.from_name,
			message: data.replace(/&nbsp;/g, ' ').replace(/;/g, '').replace(/&nbsp/g, ' '),
			status: status,
			attachment: attachment
		});
		var posturl = BASE_URL + 'emails/update_template/' + TEMPLATEID;
		$http.post(posturl, dataObj, config)
		.then(
			function (response) {
				if (response.data.success == true) {
					showToast(NTFTITLE, response.data.message, 'success');
				} else {
					showToast(NTFTITLE, response.data.message, 'warning');
				}
				$scope.saving = false;
			}, function(){
				$scope.saving = false;
			}
			);
	};
}

CiuisCRM.controller('Emails_Controller', Emails_Controller);
CiuisCRM.controller('Email_Controller', Email_Controller);