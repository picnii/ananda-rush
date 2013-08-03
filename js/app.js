var app = angular.module('ananda', ['dataServices','areaFilters']).
		  config(['$routeProvider', function($routeProvider) {
		  $routeProvider.
		  	  when('/', {templateUrl: 'template/home.html',   controller: HomeCtrl}).
		      when('/bills/:bid', {templateUrl: 'template/bill.html',   controller: BillCtrl}).
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
})





;