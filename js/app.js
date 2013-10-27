var app = angular.module('ananda', ['dataServices','areaFilters','$strap.directives']).
		  config(['$routeProvider', function($routeProvider) {
		  $routeProvider.
		  	  when('/', {templateUrl: 'template/home.html',   controller: HomeCtrl}).
          when('/promotions/ax', {templateUrl: 'template/promotion-ax.html',   controller: PromotionAxCtrl}).
          when('/promotions/match/:unit_id', {templateUrl: 'template/promotion-match.html',   controller: PromotionMatchCtrl}).
          when('/promotions', {templateUrl: 'template/promotion-management.html',   controller: PromotionCtrl}).
          when('/promotions/create', {templateUrl: 'template/promotion-create.html',   controller: PromotionCreateCtrl}).
          when('/promotions/update/:pid', {templateUrl: 'template/promotion-create.html',   controller: PromotionUpdateCtrl}).
          when('/units', {templateUrl: 'template/units.html',   controller: UnitListCtrl}).
		  	  when('/bills/print/:tid', {templateUrl: 'template/print.html',   controller: BillPrintCtrl}).
          when('/bills/print/:tid/:uid', {templateUrl: 'template/print.html',   controller: BillPrintCtrl}).
		      when('/bills/preview/:tid/:uid', {templateUrl: 'template/bill.html',   controller: BillCtrl}).
		      when('/bills', {templateUrl: 'template/bills.html',   controller: BillListCtrl}).
		      when('/bills/:bid/edit', {templateUrl: 'template/bill-edit.html',   controller: BillEditCtrl}).
          when('/payments', {templateUrl: 'template/payment.html',   controller: PaymentCtrl}).
          when('/templates', {templateUrl: 'template/template.html',   controller: TemplateCtrl}).
          when('/templates/create', {templateUrl: 'template/template-create.html',   controller: TemplateCreateCtrl}).
          when('/templates/:tid/edit', {templateUrl: 'template/template-edit.html',   controller: TemplateEditCtrl}).
          when('/variables', {templateUrl: 'template/variables.html',   controller: VariablesListCtrl}).
          when('/variables/create', {templateUrl: 'template/variable-create.html',   controller: VariableCreateCtrl}).
          when('/testprint', {templateUrl:'template/print.html', controller: BillPrintTestCtrl}).
          when('/testcheque', {templateUrl:'template/check.html', controller: ChequeTestCtrl}).
          when('/appoint/:itemId', {templateUrl:'template/appoint.html', controller: AppointCtrl}).
          when('/transactions', {templateUrl:'template/transactions.html', controller: TransactionCtrl}).
          when('/cheque', {templateUrl:'template/check-many.html', controller: TransactionPrintCtrl}).
          when('/transactions/print',{templateUrl:'template/print.html', controller:TransactionPrintCtrl} ).
          when('/transactions/:transaction_id/edit', {templateUrl:'template/transaction-edit.html', controller:TransactionEditCtrl}).
		      otherwise({redirectTo: '/'});
		}]);

angular.module('areaFilters', ['dataServices']).filter('up2area', function() {
  return function(input) {
  	if(input == "?" || input == null)
  		return "*ขึ้นอยู่กับพื้นที่จริง";
  	if(typeof(input) == 'number')
    	return input.formatMoney(2,',','.');  ;
    return input;
  };
}).filter('zeroDash', function() {
  return function(input) {
  	if(input == 0 )
      return "-";
   	if(!isNaN(input))
    {
      
      input = Number(input);
      input = Math.round(input / 10 ) *10
      return input.formatMoney(2,',','.'); 
    }else if(typeof(input)!="string" || input == "NaN")
      return "-";

    
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
}).filter('isShowPayment', function() {
  return function(input) {
    if( input == "0")
      return "NOT SHOW";
    return "SHOW" ;
  };
}).filter('isAddCheque', function() {
  return function(input) {
    if( input == "0")
      return "NOT ADD TO CHEQUE";
    return "ADD TO CHEQUE" ;
  };
}).filter('checkWithRepayment', function() {
  return function(input) {
    if( input == "0")
      return "NOT CHECK WITH REPAYMENT";
    return " CHECK WITH REPAYMENT" ;
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
}).filter('isSelect', function() {
  return function(input) {
    switch(input)
    {
      case null: return "No";
        break;
      case false:
      case 0: 
      case "0":
        return "No";
        break;
      case true:
      case "1":
      case 1: return "Yes";

        break;
    } 
    return input ;
  };
}).filter('isIssue', function() {
  return function(input) {
    switch(input)
    {
      case null: return "No";
        break;
        case false:
      case 0: 
      case "0":
      return "No";
        break;
      case true:
      case 1: return "Yes";
        break;
      case "1": return "Yes";
        break;
    } 
    return input ;
  };
}).filter('promotionType', function() {
  return function(input) {
    if(input == null || input == '')
      return '<a href="#/promotions/ax" target="_blank" class="btn btn-danger">ยังไม่ระบุ</a>';
    return input ;
  };
}).filter('ifBlank', function() {
  return function(input) {
    if(input == null || input == '' || input == ' ')
      return '-';
    return input ;
  };
}).filter('bankFilterName', function(){
  return function(input) {
    if(input == null || input == '' || input == ' ' || input == '-' )
      return 'ธนาคาร กรุงไทย จำกัด มหาชน';
    return input ;
  };

}).filter('bankCashFilter', function(){
  return function(input) {
    if(input == null || input == '' || input == ' ' || input == '-' )
      return 'สด';
    return input ;
  };

}).filter('saleNamePrint', function(){
  return function(input) {
    if(input == null || input == '-' || input == '--' || input == ' ')
      return 'คุณสุกัญญา 081-5561037';
    return input ;
  };

}).filter('isCashFilter', function(){
  return function(input) {
    if(input)
      return 'สด';
    return '-' ;
  };

})

//bankCashFilter

app.value('$strapConfig', {
  datepicker: {
    language: 'th',
    format: 'yyyy-mm-dd'
  }
});



;