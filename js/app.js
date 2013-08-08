var app = angular.module('ananda', ['dataServices','areaFilters',]).
		  config(['$routeProvider', function($routeProvider) {
		  $routeProvider.
		  	  when('/', {templateUrl: 'template/home.html',   controller: HomeCtrl}).
		  	  when('/bills/print/:tid', {templateUrl: 'template/print.html',   controller: BillPrintCtrl}).
		      when('/bills/preview/:tid/:uid', {templateUrl: 'template/bill.html',   controller: BillCtrl}).
		      when('/bills', {templateUrl: 'template/bills.html',   controller: BillListCtrl}).
		      when('/bills/:bid/edit', {templateUrl: 'template/bill-edit.html',   controller: BillEditCtrl}).
          when('/payments', {templateUrl: 'template/payment.html',   controller: PaymentCtrl}).
          when('/templates', {templateUrl: 'template/template.html',   controller: TemplateCtrl}).
          when('/templates/:tid/edit', {templateUrl: 'template/template-edit.html',   controller: TemplateEditCtrl}).
          when('/variables', {templateUrl: 'template/variables.html',   controller: VariablesListCtrl}).
          when('/variables/create', {templateUrl: 'template/variable-create.html',   controller: VariableCreateCtrl}).
          when('/testprint', {templateUrl:'template/print-test.html', controller: BillPrintTestCtrl}).
		      otherwise({redirectTo: '/'});
		}]);

angular.module('areaFilters', []).filter('up2area', function() {
  return function(input) {
  	if(input == "?" || input == null)
  		return "*ขึ้นอยู่กับพื้นที่จริง";
  	if(typeof(input) == 'number')
    	return input.formatMoney(2,',','.');  ;
    return input;
  };
}).filter('zeroDash', function() {
  return function(input) {
  	if(input == 0)
  		return "-";
   	if(typeof(input) == 'number')
    	return input.formatMoney(2,',','.');  ;
    return input;
  };
}).filter('areaDifference', function() {
  return function(input) {
  	if( input == null)
  		return "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;";
    return input ;
  };
}).filter('zeroSpaceDash', function() {
  return function(input) {
  	if( input == 0)
  		return "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;";
    return input ;
  };
}).filter('variableType', function() {
  return function(input) {
    switch(input)
    {
      case 0: return "Fix";
        break;
      case 1: return "Fix per Project";
        break;
      case 2: return "Fix per unit";
        break;
      case 3: return "Project attributes";
        break;
      case 4: return "Unit attributes";
        break;
    }
    

    return input ;
  };
})





;