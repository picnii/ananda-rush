function HomeCtrl($scope, $rootScope, $routeParams, $location, Type, Template, Unit)
{
	var post_data =  getPostData();
	$scope.room_types = Type.getRoomType();
	$scope.project_types = Type.getProjectsList();
	$scope.templates = Template.query();
	$scope.unit_ids = post_data['unit_ids'];
	$scope.units = Unit.query();

/*
	[
		{"id":1234, "name":"ทดสอบ", "billId":5, "template_id":8},
		{"id":1234, "name":"ทดสอบ", "billId":5, "template_id":8},
		{"id":1234, "name":"ทดสอบ", "billId":5, "template_id":9},
		{"id":1234, "name":"ทดสอบ", "billId":5, "template_id":8}
	];*/

	$scope.search = function()
	{
		var ss= $scope.search;
		var query = "";
		var params_name = ['unit_id', 'project_id', 'room_type', 'template_id'];
		var check_params = [ss.unit, ss.project, ss.type, ss.template];
		var params_count = 0;
		for(var i =0; i < params_name.length; i++)
		{
			
			if(check_params[i] != "" && check_params[i] != "*" && typeof(check_params[i]) == "string")
			{
				//console.log('check' + typeof(check_params[i]));
				//console.log(check_params[i]);
				if(params_count > 0)
					query += ".";
				query += params_name[i] + "=" + check_params[i];
				
				params_count++;
			}
		}
		if(params_count == 0)
			query = "*";
		console.log(query);
		$scope.units = Unit.query({'q':query})
	}

	$scope.match = function()
	{
		var uids=[];
		$('input[name="unit_ids[]"]:checked').each(function()
		{
		    // add $(this).val() to your array
		    uids.push($(this).val());
		    saveTempData(uids);
		    $location.path('/bills');
		});
	}

	$scope.print = function()
	{
		var uids=[];
		var tids = [];
		$('input[name="unit_ids[]"]:checked').each(function()
		{
		    // add $(this).val() to your array
		    uids.push($(this).val());
		});

		$('input[name="template_ids[]"]:checked').each(function()
		{
		    // add $(this).val() to your array
		    tids.push($(this).val());
		});

		saveTempData({uids:uids, tids:tids});
		$location.path('/bills/print/all');
	}

}

function BillCtrl($scope, $rootScope, $routeParams, $location, Npop, Print)
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

function BillListCtrl($scope, $rootScope, $routeParams, $http, $location, Template)
{
	//var post_data =  getPostData();

	//$scope.unit_ids = post_data['unit_ids'];
	$scope.unit_ids = loadTempData();
	$scope.templates = Template.query();

	var unit_id_str = convertUnitIdsToStr($scope.unit_ids);
	$http({
                method: 'POST',
                url: 'service/index.php',
                data: 'action=units&'+unit_id_str,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(data, status) {
            	$scope.units = data;
            });

	$scope.print = function()
	{
		var obj = $scope.getSelectedChoice();
		var tid = obj.tid;
		var uids = obj.uids;
		saveTempData(uids);
		$location.path('/bills/print/'+tid);
	}

	$scope.save = function()
	{
		var obj = $scope.getSelectedChoice();
		var tid = obj.tid;
		var uids = obj.uids;
		console.log(tid);
		console.log(uids);
		console.log('saving');
		var unit_id_str = convertUnitIdsToStr(obj.uids);
		$http({
                method: 'POST',
                url: 'service/index.php',
                data: 'action=createBills&template_id='+tid+unit_id_str,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status) {
        		console.log('status')
        		console.log(status)
            	//$location.path('/bills/print/'+tid);
        });

		

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

function BillPrintCtrl($scope, $rootScope, $routeParams, $location, $http)
{
	
	$scope.url = 'service/index.php';
	
	/*$scope.bills = Print.save({action:"bills", template_id:$routeParams.tid, unit_ids:uids}, function(data){
		console.log(data)
	})*/
	if($routeParams.tid == 'all')
	{
		var obj = loadTempData();
		var uids = obj.uids;
		var template_ids = obj.tids;
	}else
	{
		var uids = loadTempData();
		var send_data = {action:"bills", template_id:$routeParams.tid, unit_ids:uids};
	}

	var ids_str ='';
	for(var i=0; i< uids.length;i++)
	{
		if(i!=0)
			ids_str +="&";
		ids_str += "unit_ids[]=" + uids[i]
	}
	$http({
                method: 'POST',
                url: 'service/index.php',
                data: 'action=bills&template_id='+$routeParams.tid+ids_str,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(data, status) {
                $scope.status = status;
                	$scope.bills = data;
                	console.log($scope.bills)
 					for(var i=0; i<$scope.bills.length;i++)
 					{
 						var variables =$scope.bills[i].variables;
 						var payments = $scope.bills[i].payments;
						var bill =$scope.bills[i];
 						$scope.bills[i].getVar = function(varname)
 						{

 							return getVariablesValue(variables, varname);
 						}

 						$scope.bills[i].getName = function(varname)
 						{
 							var myvar =  getVariables(variables, varname);
							if(myvar == undefined)
								return undefined;
							return myvar.name;
 						}

 						$scope.bills[i].getFormulaValue = function(formula)
						{
							return getFormulaValue(variables, formula);
						}

						/* additional get*/
						$scope.bills[i].getSumCustomerPayment = function()
						{

							if(payments == undefined)
								return null;
							var sum = 0;
							
							
							for(var i = 0;i < payments.length; i++)
							{
								var raw_formula = payments[i].formulas[CUSTOMER_INDEX];
								
								//$scope.datas.payments[i].formulas[index] = $scope.getFormulaValue(raw_formula);
								var value = bill.getFormulaValue(raw_formula);;
								
								if(value !=null && typeof(value) != "string")
									sum += value;
								
								
							}
							return sum;
						}

						$scope.bills[i].getFinalCustomerPayment = function()
						{
							var getVar  = bill.getVar;
							var firstSum = bill.getSumCustomerPayment();
							var commonCharge = getVar("commonFeeCharge");
							if(typeof(commonCharge) != 'number')
								commonCharge = 0;
							var commonFund = getVar("commonFeeFund");
							if(typeof(commonFund) != 'number')
								commonFund = 0;
							
							return firstSum + commonFund + commonCharge + getVar("feeForMinistryOfFinance") + getVar("feeForTranferCash")
						}

						$scope.bills[i].getDiffArea = function(actual, contract)
						{
							if(actual == null)
								return null;
							else if(contract)
								return null;
							return contract - actual;
						}


 					}
               
    });

	/*$scope.template = Template.get({tid: $routeParams.tid}, function(data) {
   	 //$scope.mainImageUrl = phone.images[0];
  	});*/
}

function PaymentCtrl($scope, $rootScope, $location, Payment)
{
	$scope.payments = Payment.query();
	$scope.create = function()
	{
		console.log($scope.paymentName);
		console.log($scope.paymentDescription);
		console.log($scope.formulaBank);
		console.log($scope.formulaCompany);
		console.log($scope.formulaClient);

		$scope.payments.push({
			"id":88,
			"name":$scope.paymentName,
			"description":$scope.paymentDescription,
			"formulas":[
				$scope.formulaBank,
				$scope.formulaCompany,
				$scope.formulaClient
			]
		});
	}
}

function TemplateCtrl($scope, $rootScope, Template, $location)
{
	$scope.templates = Template.query();
}

function TemplateEditCtrl($scope, $routeParams, $rootScope, Template, $location)
{
	$scope.template = Template.get({template_id: $routeParams.tid}, function(data){
		console.log(data);
	});
}

function BillPrintTestCtrl($scope, $http)
{
	$http({
                method: 'POST',
                url: 'service/index.php',
                data: 'action=bills&template_id=all',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(data, status) {
                $scope.status = status;
                $scope.bills = data;
            });


}

function VariablesListCtrl($scope, $rootScope, $location, Variable)
{
	$scope.variables = Variable.query();
	Variable.getAllTypes({}, function(data){
		console.log(data);
		$scope.variablesType = data;
		
	});

	$scope.create = function()
	{
		//
		console.log('going to create');
		console.log($scope.variable.name);
		console.log($scope.variable.codename);
		console.log($scope.variable.type);
		console.log($scope.variable.value);
		Variable.create({name:$scope.variable.name, codename:$scope.variable.codename, type:$scope.variable.type, value:$scope.variable.value})
	}
}

function VariableCreateCtrl($scope, $rootScope, $location, Variable)
{
	var type = Variable.getAllTypes({}, function(data){
		console.log(data);
		$scope.variablesType = data;
		
	});

}




function convertUnitIdsToStr(uids)
{
	var ids_str ='';
	for(var i=0; i< uids.length;i++)
	{
		if(i!=0)
			ids_str +="&";
		ids_str += "unit_ids[]=" + uids[i]
	}
	return ids_str;
}
