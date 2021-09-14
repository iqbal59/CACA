function Contacts_Controller(
  $scope,
  $http,
  $mdSidenav,
  $filter,
  $mdDialog,
  fileUpload,
  $q
) {
  "use strict";

  $scope.contactsLoader = true;
  $scope.isContact = true;
  $scope.toggleFilter = buildToggler("ContentFilter");
  $scope.Create = buildToggler("Create");
  $scope.ImportCustomersNav = buildToggler("ImportCustomersNav");
  $scope.CreateGroup = buildToggler("CreateGroup");

  function buildToggler(navID) {
    return function () {
      $mdSidenav(navID).toggle();
    };
  }
  $scope.close = function () {
    $mdSidenav("ContentFilter").close();
    $mdSidenav("Create").close();
    $mdSidenav("ImportCustomersNav").close();
    $mdSidenav("CreateGroup").close();
  };

  $scope.importing = false;
  $scope.importerror = false;
  $scope.importCustomer = function () {
    $scope.importing = true;
    var file = $scope.customer_file;
    var uploadUrl = BASE_URL + "customers/customersimport/";
    fileUpload.uploadFileToUrl(file, uploadUrl, function (response) {
      if (response.success == true && !response.errors) {
        //showToast(NTFTITLE, response.message, ' success');
        globals.mdToast("success", response.message);
        $mdSidenav("ImportCustomersNav").close();
      } else if (response.success == false && response.errors) {
        $scope.importerror = true;
        $scope.errors = response.errors;
        console.log(response.errors);
        globals.mdToast("error", response.message);
      } else {
        $scope.importerror = true;
        $scope.errors = response.errors;
        globals.mdToast("error", response.message);
        console.log(response.errors);
      }
      $http.get(BASE_URL + "api/customers").then(function (Customers) {
        $scope.customers = Customers.data;
      });
      $scope.customerFiles = true;
      $scope.importing = false;
    });
  };

  var deferred = $q.defer();
  $scope.contact_list = {
    order: "",
    limit: 5,
    page: 1,
  };
  $scope.promise = deferred.promise;
  $http.get(BASE_URL + "api/contactsall").then(function (Contacts) {
    $scope.contacts = Contacts.data;
    deferred.resolve();

    $scope.limitOptions = [5, 10, 15, 20];
    if ($scope.contacts.length > 20) {
      $scope.limitOptions = [5, 10, 15, 20, $scope.contacts.length];
    }
    $scope.contactsLoader = false;

    $scope.GoCustomer = function (index) {
      var customer = $scope.customers[index];
      window.location.href = BASE_URL + "customers/customer/" + customer.id;
    };

    $scope.isIndividual = false;
    $scope.saving = false;

    $scope.AddCustomer = function () {
      $scope.saving = true;
      $scope.tempArr = [];
      if ($scope.isContact == true) {
        $scope.isContact = 1;
      } else {
        $scope.isContact = 0;
      }
      angular.forEach($scope.custom_fields, function (value) {
        if (value.type === "input") {
          $scope.field_data = value.data;
        }
        if (value.type === "textarea") {
          $scope.field_data = value.data;
        }
        if (value.type === "date") {
          $scope.field_data = moment(value.data).format("YYYY-MM-DD");
        }
        if (value.type === "select") {
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
          company: "",
          namesurname: "",
          group_id: "",
          taxoffice: "",
          taxnumber: "",
          ssn: "",
          executive: "",
          address: "",
          zipcode: "",
          country_id: "",
          state: "",
          state_id: "",
          city: "",
          town: "",
          phone: "",
          fax: "",
          email: "",
          web: "",
          risk: "",
          billing_street: "",
          billing_city: "",
          billing_state: "",
          billing_state_id: "",
          billing_zip: "",
          billing_country: "",
          shipping_street: "",
          shipping_city: "",
          shipping_state: "",
          shipping_state_id: "",
          shipping_zip: "",
          shipping_country: "",
          type: "",
          custom_fields: $scope.tempArr,
          default_payment_method: "",
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
      var posturl = BASE_URL + "customers/create/";
      $http.post(posturl, dataObj, config).then(
        function (response) {
          if (response.data.success == true) {
            if (response.data.id) {
              $http.get(BASE_URL + "api/customers").then(function (Customers) {
                $scope.customers = Customers.data;
              });
              globals.mdToast("success", response.data.message);
              $mdSidenav("Create").close();
            } else {
              $scope.saving = false;
              globals.mdToast("error", response.data.message);
            }
          } else {
            $scope.saving = false;
            globals.mdToast("error", response.data.message);
          }
        },
        function (response) {
          $scope.saving = false;
          $http.get(BASE_URL + "api/customers").then(function (Customers) {
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
      return ($scope.customers || [])
        .map(function (item) {
          return item[propName];
        })
        .filter(function (item, idx, arr) {
          return arr.indexOf(item) === idx;
        })
        .sort();
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

      if (_opt == "all") {
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
      name: "",
      phone: "",
      email: "",
    };
  });
}

CiuisCRM.controller("Contacts_Controller", Contacts_Controller);
