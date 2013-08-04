function HomeCtrl($scope, $rootScope, $routeParams, $location)
{
	var post_data =  getPostData();
	$scope.unit_ids = post_data['unit_ids'];

}

function BillCtrl($scope, $rootScope, $routeParams, $location, Npop)
{
	//$scope.datas = Npop.query();

	$scope.datas = Npop.get({uid: $routeParams.uid, tid: $routeParams.tid}, function(data) {
   	 //$scope.mainImageUrl = phone.images[0];
  	});


	var billId = $routeParams.bid;
	$scope.billId = billId;

	//console.log($scope.datas)

	$scope.getFormulaValue  = function(formula)
	{
		return getFormulaValue($scope.datas.variables, formula);
	}

	$scope.getVar = $scope.getVariablesValue = function(varname)
	{
		return getVariablesValue($scope.datas.variables, varname)
	}

	$scope.getName = function(varname)
	{
		var myvar =  getVariables($scope.datas.variables, varname);
		if(myvar == undefined)
			return undefined;
		return myvar.name;
	}

	$scope.getSumCustomerPayment = function()
	{
		if($scope.datas.payments == undefined)
			return null;
		var sum = 0;
		
		
		for(var i = 0;i < $scope.datas.payments.length; i++)
		{
			var raw_formula = $scope.datas.payments[i].formulas[CUSTOMER_INDEX];
			
			//$scope.datas.payments[i].formulas[index] = $scope.getFormulaValue(raw_formula);
			var value = $scope.getFormulaValue(raw_formula);;
			
			if(value !=null && typeof(value) != "string")
				sum += value;
			
			
		}
		return sum;
	}

	$scope.getFinalCustomerPayment = function()
	{
		var getVar  = $scope.getVar;
		var firstSum = $scope.getSumCustomerPayment();
		var commonCharge = getVar("commonFeeCharge");
		if(typeof(commonCharge) != 'number')
			commonCharge = 0;
		var commonFund = getVar("commonFeeFund");
		if(typeof(commonFund) != 'number')
			commonFund = 0;
		
		return firstSum + commonFund + commonCharge + getVar("feeForMinistryOfFinance") + getVar("feeForTranferCash")
	}

	$scope.getDiffArea = function(actual, contract)
	{
		if(actual == null)
			return null;
		else if(contract)
			return null;
		return contract - actual;
	}



}

function BillListCtrl($scope, $rootScope, $routeParams, $location, Template)
{
	var post_data =  getPostData();

	$scope.unit_ids = post_data['unit_ids'];
	$scope.templates = Template.query();

	$scope.print = function()
	{

	}

	$scope.save = function()
	{
		var obj = $scope.getSelectedChoice();
		var tid = obj.tid;
		var uids = obj.uids;
		console.log(tid);
		console.log(uids);
	}

	$scope.getSelectedChoice = function()
	{
		var tid = $('input[name="template_id"]:checked').val();
		var uids = [] ;//= $('input[name="unit_ids[]"]:checked');

		$('input[name="unit_ids[]"]:checked').each(function()
		{
		    // add $(this).val() to your array
		    uids.push($(this).val());
		});
		return {uids:uids, tid:tid};
	}

}

function BillEditCtrl($scope, $rootScope, $routeParams, $location, Npop)
{

}

function BillPrintCtrl($scope, $rootScope, $routeParams, $location, Template)
{
	$scope.template = Template.get({tid: $routeParams.tid}, function(data) {
   	 //$scope.mainImageUrl = phone.images[0];
  	});
}
