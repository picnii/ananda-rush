function HomeCtrl($scope, $rootScope, $routeParams, $location, Type, Template, Unit)
{
	var post_data =  getPostData();
	$scope.room_types = Type.getRoomType();
	$scope.project_types = Type.getProjectsList();
	$scope.company_types = Type.getCompaniesList();
	$scope.templates = Template.query();
	$scope.unit_ids = post_data['unit_ids'];
	$scope.units = Unit.query(function(){
		$scope.loading = false;
	});
	$scope.loading = true;

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
		var params_name = ['ItemId', 'ProjID', 'room_type', 'Floor', 'CompanyCode'];
		//ss.company = ss.company.toLowerCase();
		var check_params = [ss.unit, ss.project, ss.type, ss.floor, ss.company];
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
		$scope.loading = true;
		$scope.units = Unit.query({'q':query},function(){
			$scope.loading = false;
		})
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

function BillCtrl($scope, $rootScope, $routeParams, $location, Npop, Print, Type)
{
	//$scope.datas = Npop.query();

	$scope.billPayment = Type.getBillPayment(function(data){
		console.log('bill payment')
		console.log(data);
		$scope.meter_ids = [];
		$.each(data.meters, function(index, value) {
		    console.log(value);
		    $scope.meter_ids.push(value);
		}); 
		$scope.getPaymentBase();
	})

	$scope.datas = Npop.get({uid: $routeParams.uid, tid: $routeParams.tid}, function(data) {
   	 //$scope.mainImageUrl = phone.images[0];
   	 $scope.loading = false;
  	});
	$scope.loading = true;

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

	$scope.getPaymentByPaymentId = function(id)
	{
		//console.log('payments')
		if(typeof($scope.datas.payments) != 'undefined')
			return getPaymentByPaymentId(id, $scope.datas.payments, $scope.datas.variables);
		else
			return null;
	}

	$scope.getSumCustomerPayment = function()
	{
		if($scope.datas.payments == undefined)
			return null;
		var sum = 0;
		
		for(var i = 0;i < bill.payments.length; i++)
		{
			var raw_formula = $scope.datas.payments[i].formulas[CUSTOMER_INDEX];
			
			//$scope.datas.payments[i].formulas[index] = $scope.getFormulaValue(raw_formula);
			var value = $scope.getFormulaValue(raw_formula);;
			
			if(value !=null && typeof(value) != "string")
				sum += value;
			
			
		}
		return sum;
	}

	$scope.getSumBankPayment = function()
	{
		if($scope.datas.payments == undefined)
			return null;
		var sum = 0;
		//console.log('Sum Bank');
		for(var i = 0;i < $scope.datas.payments.length; i++)
		{
			var raw_formula = $scope.datas.payments[i].formulas[BANK_INDEX];
		//	console.log(raw_formula);
			//$scope.datas.payments[i].formulas[index] = $scope.getFormulaValue(raw_formula);
			var value = $scope.getFormulaValue(raw_formula);;		
			if(value !=null && typeof(value) != "string")
				sum += value;
		}
		return sum;
	}

	$scope.getSumCompanyPayment = function()
	{
		if($scope.datas.payments == undefined)
			return null;
		var sum = 0;
		
		for(var i = 0;i < $scope.datas.payments.length; i++)
		{
			var raw_formula = $scope.datas.payments[i].formulas[COMPANY_INDEX];
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

	$scope.getShowBankPayment = function()
	{
		var payment_base = $scope.getPaymentBase();
		//var A = payment_base.meter_payment + payment_base.room_payment;
		return Math.max(0, $scope.getRealBankPayment() );

	}

	$scope.getShowCompanyPayment = function()
	{
		var payment_base = $scope.getPaymentBase();
		var A = payment_base.room_payment + payment_base.meter_payment;
		return A - $scope.getRealBankPayment(); 
	}

	$scope.getRealBankPayment = function()
	{
		var payment_base = $scope.getPaymentBase();
		var repayment = $scope.getVar("Repayment");
		if(isNaN(repayment))
			repayment = 0;
		return repayment - payment_base.sum_bank_loan;
	}

	$scope.getPaymentBase = function()
	{
		//meter
		var meter_payment = 0;
		for(var i = 0; i < $scope.meter_ids.length ;i++)
		{
			var id = $scope.meter_ids[i];
			var payment = $scope.getPaymentByPaymentId(id);
			if(payment != null)
			{
				payment = payment[CUSTOMER_INDEX];
			meter_payment += payment;
			}
			
		}
		var room_payment = $scope.getPaymentByPaymentId($scope.billPayment.room_payment);
		room_payment = room_payment[CUSTOMER_INDEX];
		
		var share_payment = $scope.getPaymentByPaymentId($scope.billPayment.share_payment_id);	
		share_payment = share_payment[CUSTOMER_INDEX];

		var tranfer_payment = $scope.getPaymentByPaymentId($scope.billPayment.tranfer_payment_id);

		tranfer_payment = tranfer_payment[BANK_INDEX];

		var share_fund_payment = $scope.getPaymentByPaymentId($scope.billPayment.share_fund_payment_id);	
		share_fund_payment = share_fund_payment[CUSTOMER_INDEX];
	
		//ค่าห้อง
		var payment_base = {
			meter_payment:meter_payment,
			room_payment:room_payment,
			share_payment:share_payment,
			tranfer_payment:tranfer_payment,
			share_fund_payment:share_fund_payment,
			sum_bank_loan:0
		};
		//console.log(payment_base)
		return payment_base;
	}

	$scope.getMinistryPayment = function()
	{
		var estimate = $scope.getVar("EstimatePrice");
		var estimate_payment = 0.01 * estimate;
		var payment_base = $scope.getPaymentBase();
		var answer = estimate_payment + (payment_base.sum_bank_loan * 0.01);
		return answer - $scope.getCashPayment();
	}

	$scope.getCashPayment = function()
	{
		return 1000;
	}

	$scope.testing = function()
	{
		console.log('$scope.getShowBankPayment')
		console.log($scope.getShowBankPayment());
		console.log('$scope.getRealBankPayment');
		console.log($scope.getRealBankPayment());
		console.log('$scope.getShowCompanyPayment');
		console.log($scope.getShowCompanyPayment());
	}

}

function BillListCtrl($scope, $rootScope, $routeParams, $http, $location, Template, Bill)
{
	//var post_data =  getPostData();

	//$scope.unit_ids = post_data['unit_ids'];
	$scope.unit_ids = loadTempData();
	$scope.templates = Template.query(function(data){
		$scope.template_id = data[data.length-1].id;
		$scope.loading = false;
	});
	$scope.loading = true;

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
		Bill.create({action:"createBills", unit_ids:uids, template_id:tid},function(data){

			console.log('done');
			console.log(data);
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

	$scope.updateTemplateId = function(id)
	{
		console.log('update to '+ id );
		$alists = $('#units-list .preview a');
		for(var i=0; i < $alists.length; i++)
		{
			$alist = $alists[i];
			var unit_id = $($alist).attr('target-id');
			var target_url = '#/bills/preview/' + id + '/' + unit_id;
			$($alist).attr('href', target_url);
		}
	}

}

function BillEditCtrl($scope, $rootScope, $routeParams, $location, Npop)
{

}

function BillPrintCtrl($scope, $rootScope, $routeParams, $location, $http, Bill, Type)
{
	
	$scope.url = 'service/index.php';
	$scope.billPayment = Type.getBillPayment(function(data){
		console.log('bill payment')
		console.log(data);
		$scope.meter_ids = [];
		$.each(data.meters, function(index, value) {
		    console.log(value);
		    $scope.meter_ids.push(value);
		}); 
		$scope.getPaymentBase();
	})
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
	
	$scope.getFormulaValue = function(variables, formula)
	{
		return getFormulaValue(variables, formula);
	}

	$scope.getSumCustomerPayment = function(variables, payments)
	{
		return $scope.getSumColumPayment(variables, payments, CUSTOMER_INDEX);;
	}

	$scope.getSumBankPayment = function(variables, payments)
	{
		return $scope.getSumColumPayment(variables, payments, BANK_INDEX);;
	}

	$scope.getSumCompanyPayment = function(variables, payments)
	{
		return $scope.getSumColumPayment(variables, payments, COMPANY_INDEX);;
	}

	$scope.getSumColumPayment = function(variables, payments, index)
	{
		if(payments == undefined)
			return null;
		var sum = 0;
		
		for(var i = 0;i < payments.length; i++)
		{
			var raw_formula = payments[i].formulas[index];
			
			//bill.payments[i].formulas[index] = bill.getFormulaValue(raw_formula);
			var value = $scope.getFormulaValue(variables, raw_formula);;
			
			if(value !=null && typeof(value) != "string")
				sum += value;
			
			
		}
		return sum;
	}

	/*$http({
                method: 'POST',
                url: 'service/index.php',
                data: 'action=bills&template_id='+$routeParams.tid+ids_str,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(data, status) {*/
    Bill.preview({action:'bills', unit_ids:uids, template_id:$routeParams.tid}, function(data){

    	console.log(data);

    //})
      //          $scope.status = status;
                	$scope.bills = data;
                	console.log($scope.bills)
 					for(var i=0; i<$scope.bills.length;i++)
 					{
 						var variables =$scope.bills[i].variables;
 						var payments = $scope.bills[i].payments;
						var bill =$scope.bills[i];
 						
 						var bill_vars = bill.variables;

						bill.getFormulaValue  = function(formula)
						{
							return getFormulaValue(bill_vars, formula);
						}

						bill.getVar = bill.getVariablesValue = function(varname)
						{
							//console.log('log');
							//console.log(bill_vars)
							return getVariablesValue(bill_vars, varname)
						}

						bill.getName = function(varname)
						{
							var myvar =  getVariables(bill_vars, varname);
							if(myvar == undefined)
								return undefined;
							return myvar.name;
						}

						bill.getPaymentByPaymentId = function(id)
						{
							//console.log('payments')
							if(typeof(bill.payments) != 'undefined')
								return getPaymentByPaymentId(id, bill.payments, bill_vars);
							else
								return null;
						}

						bill.getFinalCustomerPayment = function()
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

						bill.getDiffArea = function(actual, contract)
						{
							if(actual == null)
								return null;
							else if(contract)
								return null;
							return contract - actual;
						}

						bill.getShowBankPayment = function()
						{
							var payment_base = bill.getPaymentBase();
							//var A = payment_base.meter_payment + payment_base.room_payment;
							return Math.max(0, bill.getRealBankPayment() );

						}

						bill.getShowCompanyPayment = function()
						{
							var payment_base = bill.getPaymentBase();
							var A = payment_base.room_payment + payment_base.meter_payment;
							return A - bill.getRealBankPayment(); 
						}

						bill.getRealBankPayment = function()
						{
							var payment_base = bill.getPaymentBase();
							var repayment = bill.getVar("Repayment");
							if(isNaN(repayment))
								repayment = 0;
							return repayment - payment_base.sum_bank_loan;
						}

						bill.getPaymentBase = function()
						{
							//meter
							var meter_payment = 0;
							for(var i = 0; i < $scope.meter_ids.length ;i++)
							{
								var id = $scope.meter_ids[i];
								var payment = bill.getPaymentByPaymentId(id);
								if(payment != null)
								{
									payment = payment[CUSTOMER_INDEX];
								meter_payment += payment;
								}
								
							}
							var room_payment = bill.getPaymentByPaymentId($scope.billPayment.room_payment);
							room_payment = room_payment[CUSTOMER_INDEX];
							
							var share_payment = bill.getPaymentByPaymentId($scope.billPayment.share_payment_id);	
							share_payment = share_payment[CUSTOMER_INDEX];

							var tranfer_payment = bill.getPaymentByPaymentId($scope.billPayment.tranfer_payment_id);

							tranfer_payment = tranfer_payment[BANK_INDEX];

							var share_fund_payment = bill.getPaymentByPaymentId($scope.billPayment.share_fund_payment_id);	
							share_fund_payment = share_fund_payment[CUSTOMER_INDEX];
						
							//ค่าห้อง
							var payment_base = {
								meter_payment:meter_payment,
								room_payment:room_payment,
								share_payment:share_payment,
								tranfer_payment:tranfer_payment,
								share_fund_payment:share_fund_payment,
								sum_bank_loan:0
							};
							//console.log(payment_base)
							return payment_base;
						}

						bill.getMinistryPayment = function()
						{
							var estimate = bill.getVar("EstimatePrice");
							var estimate_payment = 0.01 * estimate;
							var payment_base = bill.getPaymentBase();
							var answer = estimate_payment + (payment_base.sum_bank_loan * 0.01);
							return answer - bill.getCashPayment();
						}

						bill.getCashPayment = function()
						{
							return 1000;
						}	

						/**/
						for(var k =0 ; k < bill_vars.length ;k++)
						{
							var cur_var = bill_vars[k];
							var eval_str = '';
							for(var key in cur_var)
							{
								if(isNaN(key))
								{
									eval_str += "bill."+key+" = bill.getVar('"+key+"')";
									eval(eval_str);
								}
							}
							//console.log(eval_str);
						}
						for(var k =0; k < bill.payments.length;k++)
						{
							var payment = bill.payments[k];

							//payment.bankPayment = bill.getFormulaValue(payment.formulas[0]);
							//payment.companyPayment = bill.getFormulaValue(payment.formulas[1]);
							//payment.customerPayment = bill.getFormulaValue(payment.formulas[2]);
							payment.bankFormula = payment.formulas[0];
							payment.companyFormula = payment.formulas[1];
							payment.customerFormula = payment.formulas[2];
							//console.log( bill.getFormulaValue(payment.formulas[2]));
						}


 					}
               
    });

	/*$scope.template = Template.get({tid: $routeParams.tid}, function(data) {
   	 //$scope.mainImageUrl = phone.images[0];
  	});*/
}

function PaymentCtrl($scope, $rootScope, $location, Payment)
{
	function getAllPayments()
	{
		return Payment.query(function(data){
			for(var i =0 ; i < data.length ; i++)
			{
				var id = data[i].id;
				data[i].update = function(callback)
				{
					console.log('update:'+this.id);
					this.action = "updatePayment";
					Payment.updatePayment(this, function(rs){
						console.log(rs);
						callback(rs);
					})
					
				}

				data[i].delete = function(callback)
				{
					this.action = "deletePayment";
					Payment.deletePayment(this, function(rs){
						console.log(rs);
						callback(rs);
					})
				}
			}
		});
	}

	$scope.payments = getAllPayments();
	$scope.payment ={}
	$scope.payment.formulas = [];
	$scope.payment.is_shows = ["1","1","1"];
	$scope.payment.is_add_in_cheque = "0";
	$scope.payment.is_compare_with_repayment ="0";
	$scope.create = function()
	{
		var payment = $scope.payment;
		payment.action = 'createPayment';
		Payment.createPayment(payment, function(data){
			console.log(data);
			$scope.payments = getAllPayments();
		})
	}

	$scope.edit = function(tr_id, payment_id)
	{
		
		
		$('#'+tr_id+ ' .show-input').show();
		$('#'+tr_id+ ' .show-text').hide();
		var selector1 = '.show-payment-bill-'+payment_id;
		var selector2 = '.show-payment-cheque-'+payment_id;
		var selector3 = '.show-payment-repayment-'+payment_id;
		$(selector1).show();
		$(selector2).show();
		$(selector3).show();

	}

	$scope.deletePayment = function(tr_id, payment)
	{
		if(confirm("Are you sure you going to delete "+payment.name))
		{
			payment.delete(function(){
				$scope.refresh();
			});
		}
	}

	$scope.editSubmit = function(tr_id, payment)
	{
		console.log('do:'+tr_id)
		console.log('#'+tr_id+ ' span');

		console.log('#'+tr_id+ ' input');
		console.log(angular.element('#'+tr_id+' span'));
		
		console.log($scope.payments);
		$('#'+tr_id).addClass('doing');
		payment.update(function(){
			$('#'+tr_id).removeClass('doing');
			$('#'+tr_id).addClass('done');
		});
		//$scope.payments = Payment.query();
	}

	$scope.refresh = function()
	{
		$scope.payments = getAllPayments();
	}
	
}

function TemplateCtrl($scope, $rootScope, Template, $location)
{
	$scope.templates = Template.query();
}

function TemplateCreateCtrl($scope, $routeParams, $rootScope, Template, $location, Payment)
{
	$scope.template = {};
	$scope.template.indexOrder = 1;
	$scope.template.payments = [];
	$scope.payments = Payment.query(function(data){
		for(var i =0; i < data.length ;i++)
		{
			data[i].index = i;
			data[i].removeFromList = function()
			{
				$scope.payments.splice(this.index,1); 
			}
		}

	});
	$scope.removePayment = function(remove_payment)
	{
		console.log('remove');
		console.log(remove_payment)
		console.log($scope.template.payments)
		

		//var remove_payment = $scope.template.payments.pop();
		remove_payment.index = $scope.payments.length;
		$scope.template.payments.splice(remove_payment.templateIndex, 1);
		$scope.updateTemplateIndex();
		//$scope.findPaymentById(payment_id));
	}

	$scope.updateTemplateIndex = function()
	{
		for(var i =0; i < $scope.template.payments.length ;i++)
		{
			$scope.template.payments[i].templateIndex = i;
		}
	}

	$scope.findPaymentById = function(payment_id)
	{
		
		for(var i =0; i < $scope.payments.length; i++)
		{

			if($scope.payments[i].id == payment_id)
				return $scope.payments[i];
		}
		return null;
	}

	$scope.isPaymentInTemplate = function(payment)
	{
		for(var i=0; i < $scope.template.payments.length ;i++)
		{
			var tpayment = $scope.template.payments[i];
			if(payment.id == tpayment.id)
				return true;
		}
		return false;
	}

	$scope.addPayment = function(payment_id)
	{
		var payment = $scope.findPaymentById(payment_id);
		if(!$scope.isPaymentInTemplate(payment))
		{
			payment.order = $scope.template.indexOrder;
			$scope.template.indexOrder++;
			
			console.log('add:'+payment_id);
			console.log(payment);
			payment.removeFromTemplate = function()
			{
				$scope.template.payments.splice(this.templateIndex, 1);
				//this.templateIndex
			}
			$scope.template.payments.push(payment);
			$scope.updateTemplateIndex();
		}

		
	}

	$scope.create = function()
	{
		$scope.template.action = 'createTemplate';
		Template.createTemplate($scope.template, function(data){
			$location.path('/templates');
		} );
		//$location.path('/templates')
	}
}

function TemplateEditCtrl($scope, $routeParams, $rootScope, Template, $location, Payment)
{
	$scope.template = Template.get({template_id: $routeParams.tid}, function(data){
		console.log(data);
		if(typeof(data.payments[0]) == 'undefined')
			$scope.lastOrder = 0;
		else
			$scope.lastOrder = data.payments[0].order;
		console.log('lastOrder:'+$scope.lastOrder);
		for(var i=0; i < data.payments.length ;i++)
		{
			var payment = data.payments[i];
			if(payment.order > $scope.lastOrder)
				$scope.lastOrder = payment.order;
		}
	});
	
	$scope.payments = Payment.query(function(data){
		for(var i =0; i < data.length ;i++)
		{
			data[i].index = i;
			data[i].removeFromList = function()
			{
				$scope.payments.splice(this.index,1); 
			}

		}

	});

	$scope.removePayment = function(remove_payment)
	{
		Template.deleteTemplatePayment({action:'deleteTemplatePayment', template_id:$scope.template.id, payment_id:remove_payment.id}, function(data){
			remove_payment.index = $scope.payments.length;
			$scope.template.payments.splice(remove_payment.templateIndex, 1);
			$scope.updateTemplateIndex();
		})
		//var remove_payment = $scope.template.payments.pop();
		
		//$scope.findPaymentById(payment_id));
	}

	$scope.updateTemplateIndex = function()
	{
		for(var i =0; i < $scope.template.payments.length ;i++)
		{
			$scope.template.payments[i].templateIndex = i;
		}
	}

	$scope.findPaymentById = function(payment_id)
	{
		
		for(var i =0; i < $scope.payments.length; i++)
		{

			if($scope.payments[i].id == payment_id)
				return $scope.payments[i];
		}
		return null;
	}

	$scope.isPaymentInTemplate = function(payment)
	{
		for(var i=0; i < $scope.template.payments.length ;i++)
		{
			var tpayment = $scope.template.payments[i];
			if(payment.id == tpayment.id)
				return true;
		}
		return false;
	}

	$scope.addPayment = function(payment_id)
	{
		var payment = $scope.findPaymentById(payment_id);
		if(!$scope.isPaymentInTemplate(payment))
		{
			payment.order = $scope.lastOrder +1;
			Template.createTemplatePayment({action:'createTemplatePayment', template_id:$scope.template.id, payment_id:payment.id, order:payment.order}, function(){

				$scope.template.indexOrder++;
				console.log('add:'+payment_id);
				console.log(payment);
				payment.removeFromTemplate = function()
				{
					$scope.template.payments.splice(this.templateIndex, 1);
					//this.templateIndex
				}
				$scope.template.payments.push(payment);
				$scope.updateTemplateIndex();
			});			
		}	
	}

	$scope.update = function()
	{
		$scope.template.action ='updateTemplate';
		$scope.template.description = $scope.template.color;
		Template.updateTemplate($scope.template, function(){

		})
	}

	$scope.deleteTemplate = function()
	{
		if(confirm("Are you sure you want to delete this template"))
			Template.deleteTemplate({action:'deleteTemplate', id:$scope.template.id}, function(data){
				$location.path('/templates');
			});
	}

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
		$scope.variable.type = 0;
	});

	$scope.create = function()
	{
		//
		console.log('going to create');
		console.log($scope.variable.name);
		console.log($scope.variable.codename);
		console.log($scope.variable.type);
		console.log($scope.variable.value);
		Variable.create({action:'createVariable', name:$scope.variable.name, codename:$scope.variable.codename, type:0, value:$scope.variable.value} , function(data){
			console.log(data);
			//$scope.variables.push($scope.variable)
			//temp
			$scope.variables = Variable.query();
		})
	}

	$scope.delete = function(var_data)
	{
		Variable.delete({action:'deleteVariable', id:var_data.id}, function(data){
			//temp
			$scope.variables = Variable.query();
		})
	}
}

function VariableCreateCtrl($scope, $rootScope, $location, Variable)
{
	var type = Variable.getAllTypes({}, function(data){
		console.log(data);
		$scope.variablesType = data;
		$sc
	});

}

function UnitListCtrl($scope, $rootScope, $location, Type, Template, Unit)
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

	$scope.print = function()
	{

	}
}

function AppointCtrl($scope, $rootScope, $location, $routeParams, Appoint)
{
	$scope.authorize = "0";
	var now = new Date();
	$scope.appointdate =  {date: {date: new Date("2012-09-01T00:00:00.000Z")}};
	$scope.refresh = function(){
		$scope.data = Appoint.get({itemId:$routeParams.itemId}, function(data){
			$scope.unit = data.unit;
			$scope.logs = data.logs;
			
			for(var i=0; i < $scope.logs.length; i++)
			{
				var log = $scope.logs[i];
				/*var t = "2010-06-09 13:12:01".split(/[- :]/);
				// Apply each element to the Date function
				var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);*/
				log.appoint_date_time = new Date(log.appoint_time.date);
				log.call_date_time = new Date(log.call_time.date);
				log.display_call_date = log.call_date_time.getDate() + '/' + log.call_date_time.getMonth() + '/' + (log.call_date_time.getYear() + 1900);
				log.display_appoint_date = log.appoint_date_time.getDate() + '/' + log.appoint_date_time.getMonth() + '/' + (log.appoint_date_time.getYear() + 1900);

				log.display_call_time = log.call_date_time.getHours() + ':' + log.call_date_time.getMinutes() + ':' + log.call_date_time.getSeconds();
				log.display_appoint_time = log.appoint_date_time.getHours() + ':' + log.appoint_date_time.getMinutes() + ':' + log.appoint_date_time.getSeconds();
			}
			console.log($scope.logs);
			$scope.current_appoint = data.appoint;

		})
	}

	$scope.createAppoint = function()
	{
		$scope.def_appointdate = $scope.appointdate ;
		$scope.def_appointtime = $scope.appointtime;
		$scope.def_calldate = $scope.calldate;
		$scope.def_calltime  = $scope.calltime;

		$scope.appointdate = convertDateToSqlDate($scope.appointdate);
		$scope.appointtime = convertDateToSqlTime($scope.appointtime);
		$scope.calldate = convertDateToSqlDate($scope.calldate);
		$scope.calltime = convertDateToSqlTime($scope.calltime);


		console.log("type:"+ $scope.type);
		console.log("calldate:"+ $scope.calldate);
		console.log("calltime:"+ $scope.calltime);
		console.log("callduration:"+ $scope.callduration);
		console.log("people:"+ $scope.people);
		console.log("appointdate:"+ $scope.appointdate);
		console.log("appointtime:"+ $scope.appointtime);
		console.log("status:"+ $scope.status);
		console.log("payment_type:"+ $scope.payment_type);
		console.log("coming_status:"+ $scope.coming_status);
		console.log("remark:" + $scope.remark);
		console.log("authorize"+ $scope.authorize);
		Appoint.create({type:$scope.type, call_date:$scope.calldate, call_time:$scope.calltime, call_duration:$scope.callduration , people:$scope.people, 
			appoint_date:$scope.appointdate, appoint_time:$scope.appointtime, status:$scope.status, payment_type:$scope.payment_type, coming_status:$scope.coming_status, remark:$scope.remark,
			unit_id:$scope.unit.id, action:'createAppoint', authorize:$scope.authorize
		}, function(data){
			console.log(data);
			$scope.refresh();
			 $scope.appointdate= $scope.def_appointdate  ;
			$scope.appointtime = $scope.def_appointtime ;
			$scope.calldate = $scope.def_calldate;
			$scope.calltime = $scope.def_calltime ;
		});

	}
	$scope.refresh();
	
	doOnce(function(){
		//$('.datepicker').datepicker({format:'yyyy-mm-dd'});
		
	})
	

}

function TransactionCtrl($scope, $rootScope, $routeParams, $location, Npop, Print, Type)
{

}

var doOnceCount = 0;
function doOnce(callback)
{
	if(doOnceCount % 2 == 0)
	{
		callback();
			
	}
	doOnceCount++;	
}

function convertDateToSqlDate(date)
{
	var test  = (date.getYear() + 1900);
		test += '-';
		if(date.getMonth() < 10)
			test += '0' + date.getMonth();
		else
			test += date.getMonth();
		test += '-';
		if(date.getDate() < 10)
			test += '0' + date.getDate();
		else
			test += date.getDate();
	return test;
}

function convertDateToSqlTime(time)
{
	var time_set = time.split(' ');
	var times = time_set[0].split(':');
	var hours = Number(times[0]);
	var mins = times[1];	
	if(time_set[1] == 'PM')
		hours += 12;
	hours = convertToTimeString(hours);
	test = hours+':'+mins;
	test += ':00';
	return test;
}

function convertToTimeString(time)
{
	if(time < 10)
		return '0'+time;
	else
		return time;
}

function ChequeTestCtrl()
{

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
