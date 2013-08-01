var app = angular.module('ananda', ['dataServices']).
		  config(['$routeProvider', function($routeProvider) {
		  $routeProvider.
		  	  when('/', {templateUrl: 'template/home.html',   controller: HomeCtrl}).
		      when('/bill/:bid', {templateUrl: 'template/bill.html',   controller: BillCtrl}).
		      otherwise({redirectTo: '/'});
		}]);