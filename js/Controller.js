function HomeCtrl($scope, $rootScope, $routeParams, $location)
{


}

function BillCtrl($scope, $rootScope, $routeParams, $location, Npop)
{
	$scope.datas = Npop.query();

	var billId = $routeParams.bid;
	$scope.billId = billId;
	console.log($scope.datas)

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



}

