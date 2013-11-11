function BillCtrl($scope, $rootScope, $routeParams, $location, Npop, Print, Type, Bill)
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
	$scope.datas = {};
	$scope.datas = Npop.get({uid: $routeParams.uid, tid: $routeParams.tid}, function(data) {
   	 //$scope.mainImageUrl = phone.images[0];
   	 	$scope.loading = false;
   	 	for(var i =0; i < data.payments.length;i++)
		{
			var payment = data.payments[i];
			payment.formulas[0] = $scope.getFormulaValue(payment.formulas[0]);
			payment.formulas[1] = $scope.getFormulaValue(payment.formulas[1]);
			payment.formulas[2] = $scope.getFormulaValue(payment.formulas[2]);
		}
   	 	data.payments = updateNewPayment(data.payments, $scope.getPaymentBase().sum_bank_loan)
   	 	console.log('after load');
   	 	console.log(data);
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
		
		for(var i = 0;i < $scope.datas.payments.length; i++)
		{
			var raw_formula = $scope.datas.payments[i].formulas[CUSTOMER_INDEX];
			
			//$scope.datas.payments[i].formulas[index] = $scope.getFormulaValue(raw_formula);
			var value = $scope.getFormulaValue(raw_formula);;
			
			if(value !=null && !isNaN(value))
				sum += Number(value);
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
			var value = $scope.getFormulaValue(raw_formula);	
			if(value !=null && !isNaN(value))
				sum += Number(value);
            else
             {
             
                console.log('data payment  ' + value);
             }
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
			if(value !=null && !isNaN(value))
				sum += Number(value);
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
		return $scope.getCashPayment() 
			+ $scope.getMinistryPayment() 
			+ $scope.getPaymentBase().share_fund_payment 
			+ $scope.getPaymentBase().share_payment 
			+ $scope.getShowCompanyPayment()  
			+ $scope.getShowBankPayment();
		//return firstSum + commonFund + commonCharge + getVar("feeForMinistryOfFinance") + getVar("feeForTranferCash")
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
		//var A = payment_base.meter_payment + payment_base.room_payment;
		return Math.max(0, $scope.getReductRepayment() );

	}

    $scope.getReductRepayment = function()
    {
        var payment_base = $scope.getPaymentBase() ;
        var repayment = $scope.getVar("Repayment");
        if(isNaN(repayment))
			repayment = 0;
        return repayment - payment_base.sum_bank_loan
    }



	$scope.getShowCompanyPayment = function()
	{
		var payment_base = $scope.getPaymentBase();
		var A = payment_base.customer_room_payment + payment_base.customer_meter_payment;

		return A  - $scope.getShowBankPayment(); 
	}

	$scope.getRealBankPayment = function()
	{
		var payment_base = $scope.getPaymentBase();
		var repayment = $scope.getVar("Repayment");
		if(isNaN(repayment))
			repayment = 0;
		return repayment ;
	}

	$scope.getPaymentBase = function()
	{
		//meter
		var meter_payment = 0;
		var customer_room_payment = 0;
		var customer_meter_payment = 0;
		if(typeof($scope.meter_ids) != 'object')
			return false;
		for(var i = 0; i < $scope.meter_ids.length ;i++)
		{
			var id = $scope.meter_ids[i];
			var def_payment = $scope.getPaymentByPaymentId(id);
           // console.log('def payment');
           // console.log(def_payment);
            var payment = 0;
			if(def_payment != null)
			{
              
				payment += Number(def_payment[CUSTOMER_INDEX]);
				customer_meter_payment += payment;
                if( !isNaN( def_payment[BANK_INDEX]) )
                    payment += Number(def_payment[BANK_INDEX]);
                   
                meter_payment += Number(payment);
			}
			
		}
        var  def_room_payment = $scope.getPaymentByPaymentId($scope.billPayment.room_payment);
		var room_payment  = 0;
		if(def_room_payment!=null)
        {
            
			room_payment += def_room_payment[CUSTOMER_INDEX];
			customer_room_payment += def_room_payment[CUSTOMER_INDEX];
           if(!isNaN( def_room_payment[BANK_INDEX] ) )
                room_payment += Number(def_room_payment[BANK_INDEX]);
               
          
        }


            
		var share_payment = $scope.getPaymentByPaymentId($scope.billPayment.share_payment_id);	
		if(share_payment !=null)
			share_payment = share_payment[CUSTOMER_INDEX];

		var tranfer_payment = $scope.getPaymentByPaymentId($scope.billPayment.tranfer_payment_id);
		if(tranfer_payment!=null)
			tranfer_payment = tranfer_payment[BANK_INDEX];

		var share_fund_payment = $scope.getPaymentByPaymentId($scope.billPayment.share_fund_payment_id);	
		if(share_fund_payment !=null)
			share_fund_payment = share_fund_payment[CUSTOMER_INDEX];
		//loan_payment
		

		var sum_bank_loan = $scope.getVar('BankLoanRoom');
		var sum_bank_loan_payment =  $scope.getPaymentByPaymentId($scope.billPayment.loan_payment_id);
		var customer_sum_bank_loan = 0;
		if(!isNaN(sum_bank_loan_payment[CUSTOMER_INDEX]))
			customer_sum_bank_loan += Number(sum_bank_loan_payment[CUSTOMER_INDEX]);

		if(typeof(sum_bank_loan) == 'undefined' || sum_bank_loan == null || sum_bank_loan == '-')
			sum_bank_loan = 0;
		//console.log('sum_bank_loan:'+sum_bank_loan)

		var tax_payment = $scope.getPaymentByPaymentId($scope.billPayment.tax_payment_id);
	
		if(isNaN(tax_payment[CUSTOMER_INDEX]))
			tax_payment = 0
		else
			tax_payment = Number(tax_payment[CUSTOMER_INDEX]);
		var tax_loan_payment = $scope.getPaymentByPaymentId($scope.billPayment.tax_loan_payment_id);
		
		if(isNaN(tax_loan_payment[CUSTOMER_INDEX]))
			tax_loan_payment = 0;
		else
			tax_loan_payment = Number(tax_loan_payment[CUSTOMER_INDEX]);
		 
		var payment_base = {
			meter_payment:Number(meter_payment),
			room_payment:Number(room_payment),
			share_payment:Number(share_payment),
			tranfer_payment:Number(tranfer_payment),
			share_fund_payment:Number(share_fund_payment),
			sum_bank_loan:Number(sum_bank_loan),
			customer_room_payment:Number(customer_room_payment),
			customer_meter_payment:Number(customer_meter_payment),
			customer_sum_bank_loan:Number(customer_sum_bank_loan),
			tax_loan_payment:tax_loan_payment,
			tax_payment:tax_payment
		};
		//console.log('log');
		//console.log(payment_base)
		return payment_base;
	}

	$scope.getMinistryPayment = function()
	{
		var estimate = $scope.getVar("EstimatePrice");

		var payment_base = $scope.getPaymentBase();
		var estimate_payment = 0.01 * payment_base.tranfer_payment;
		var answer = estimate_payment + (payment_base.customer_sum_bank_loan) + payment_base.tax_payment + payment_base.tax_loan_payment;
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

	$scope.save = function()
	{
		var bill = $scope.datas;
		bill.unit_id = $scope.getVar('UnitId');
		var bills = [];
		bills[0] = bill;
		Bill.createTransaction({action:'createTransaction', template_id:$routeParams.tid, bills:bills}, function(data){
    				console.log(data);
    	});
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
		//$scope.getPaymentBase();
	})
	/*$scope.bills = Print.save({action:"bills", template_id:$routeParams.tid, unit_ids:uids}, function(data){
		console.log(data)
	})*/
	if($routeParams.tid == 'all')
	{
		var obj = loadTempData();
		var uids = obj.uids;
		var template_ids = obj.tids;
	}else if($routeParams.uid > 0){
		var uids = [];
		uids[0] = $routeParams.uid;
		var send_data = {action:"bills", template_id:$routeParams.tid, unit_ids:uids};
	
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
                method: 'GET',
                url: 'service/index.php?'+'action=bills&template_id='+$routeParams.tid+'&'+ids_str,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(data, status) {
            	$scope.bills = convertBillPrint($scope, data)          
       for(var i=0; i < $scope.bills.length;i++)
		{
			for(var j=0; j < $scope.bills[i].payments.length;j++)
			{
				var payment = $scope.bills[i].payments[j];
				//payment.test_formulas = []
				payment.formulas[0] =  $scope.getFormulaValue($scope.bills[i].variables,payment.formulas[0]);
				payment.formulas[1] =  $scope.getFormulaValue($scope.bills[i].variables, payment.formulas[1]);
				payment.formulas[2] =  $scope.getFormulaValue($scope.bills[i].variables, payment.formulas[2]);

			}	
			//console.log('update new payment');
			console.log('update-new-payment');

			$scope.bills[i].payments = updateNewPayment( $scope.bills[i].payments, 
			 	$scope.bills[i].getPaymentBase( $scope.bills[i].variables,  $scope.bills[i].payments).sum_bank_loan)


			console.log('check bank payment');
			

			var sum_bank_payment = 0;
			console.log('')
			for(var j = 0; j < $scope.bills[i].payments.length; j++)
			{
				var bill_payment = $scope.bills[i].payments[j];
				console.log('check-bill')
				console.log(bill_payment)
				 sum_bank_payment += Number(bill_payment.formulas[BANK_INDEX]);
				 console.log('sum_bank_payment:'+ sum_bank_payment );
				 console.log('biller')
				 var bill = $scope.bills[i];
				console.log('meter'+bill.getPaymentBase(bill.variables, bill.payments).meter_payment)
				console.log('room'+bill.getPaymentBase(bill.variables, bill.payments).room_payment)
				console.log('sum com'+$scope.getSumCompanyPayment(bill.variables, bill.payments));
				console.log('repayment'+bill.Repayment);
				console.log('end biller')

			}
			

			if(!isNaN(sum_bank_payment))
			{

				var payment_base = $scope.bills[i].getPaymentBase( $scope.bills[i].variables,  $scope.bills[i].payments);
				
				//console.log($scope.bills[i].payments)
				console.log('test');
				console.log('sum_bank_payment = '+sum_bank_payment)
				console.log(payment_base.sum_bank_loan);
				if(sum_bank_payment < payment_base.sum_bank_loan)
				{
					console.log('pay back occur')
					console.log('sum_bank_payment 1st = '+sum_bank_payment)
					console.log('sum_bank_loan =' + payment_base.sum_bank_loan)
					$scope.bills[i].bank_pay_back = payment_base.sum_bank_loan - sum_bank_payment;
					var bank_payback_payment_id = 47;
					for(var j =0; j < $scope.bills[i].payments.length; j++)
					{
						var bill_payment = $scope.bills[i].payments[j];
						if(bill_payment.id == bank_payback_payment_id)
						{
							bill_payment.formulas[BANK_INDEX] = $scope.bills[i].bank_pay_back;
							break;
						}
					} 
				}
			}
			
			//check if sum_bank_payment <= bank_loan?
			//convert payment bank to blablabla
		}
            });

	/*
    Bill.preview({action:'bills', unit_ids:uids, template_id:$routeParams.tid}, function(data){
       $scope.bills = convertBillPrint($scope, data)          
       for(var i=0; i < $scope.bills.length;i++)
		{
			for(var j=0; j < $scope.bills[i].payments.length;j++)
			{
				var payment = $scope.bills[i].payments[j];
				//payment.test_formulas = []
				payment.formulas[0] =  $scope.getFormulaValue($scope.bills[i].variables,payment.formulas[0]);
				payment.formulas[1] =  $scope.getFormulaValue($scope.bills[i].variables, payment.formulas[1]);
				payment.formulas[2] =  $scope.getFormulaValue($scope.bills[i].variables, payment.formulas[2]);

			}	
			console.log('update new payment');
			console.log('update-new-payment');
			$scope.bills[i].payments = updateNewPayment( $scope.bills[i].payments, 
			 	$scope.bills[i].getPaymentBase( $scope.bills[i].variables,  $scope.bills[i].payments).sum_bank_loan)
			console.log( $scope.bills[i].payments );
		}

    });*/

    $scope.save = function()
    {
    	for(var i =0 ;i < $scope.bills.length ; i++)
    	{
    		var bill = $scope.bills[i];
    		bill.unit_id = bill.getVar('UnitId', bill.variables);
    		//console.log(bill);
    	
    		/*console.log({
    			unit_id:bill.getVar('UnitId', bill.variables),
    			variables:bill.variables,
    			payments:bill.payments
    		});*/
    	}
    	console.log('createTransaction');
    	console.log({action:'createTransaction', template_id:$routeParams.tid, bills:$scope.bills});
    		Bill.createTransaction({action:'createTransaction', template_id:$routeParams.tid, bills:$scope.bills}, function(data){
    				console.log(data);
    		});
    }

	/*$scope.template = Template.get({tid: $routeParams.tid}, function(data) {
   	 //$scope.mainImageUrl = phone.images[0];
  	});*/
}


function TransactionCtrl($scope, $filter, $rootScope, $routeParams, $location, Bill, Print, Type)
{
	$scope.room_types = Type.getRoomType();
	$scope.project_types = Type.getProjectsList();
	$scope.company_types = Type.getCompaniesList();
	$scope.transactions  = Bill.list(function(transactions){
		for(var i =0; i < transactions.length ; i++)
		{

			var transaction = transactions[i];
			transaction.checked = true;

			transaction.tranfer = function()
			{
				console.log(this);
				this.is_tranfer = 1;
				if(this.tranfer_date == null || this.tranfer_date == "")
					this.tranfer_date = new Date();
				
				var args ={is_tranfer:this.is_tranfer, tranfer_time:convertDateTimeToSqlFormat(this.tranfer_date)};
				console.log('tranfer id '+ this.id);
				console.log(args)
				Bill.updateBill({transaction_id:this.id, args:args},function(data){
					console.log(data);
				});
			}
		}

	});


	$scope.selectedTransactions = function () {
    	return $filter('filter')($scope.transactions, {checked: true});
	};

	$scope.test = function()
	{
		console.log($scope.selectedTransactions());
	}

	$scope.toggleSelect = function()
	{
		for(var i =0 ; i < $scope.transactions.length; i++)
		{
			var transaction = $scope.transactions[i];
			transaction.checked = ! transaction.checked;
		}
	}

	$scope.search = function()
	{
		console.log('start search')
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
		$scope.transactions  = Bill.list({'q':query},function(transactions){
			for(var i =0; i < transactions.length ; i++)
			{
				var transaction = transactions[i];
				transaction.checked = true;
			}
		});
	}

	$scope.print = function()
	{
		var transactions = $scope.selectedTransactions();
		var transaction_ids = [];
	//	console.log('saving');
		for(var i=0; i < transactions.length; i++)
		{
	//		console.log(transactions[i])
	//		console.log(transactions[i].id);
			transaction_ids.push(transactions[i].id);
		}
		
	//	console.log({transaction_ids:transaction_ids})
		saveTempData({transaction_ids:transaction_ids});
		$location.path('/transactions/print');
	}

	$scope.tranfer = function()
	{
		var transactions = $scope.selectedTransactions();
		for(var i =0; i < transactions.length;i++)
		{
			transactions[i].tranfer_date = $scope.tranfer_time
			transactions[i].tranfer();
		}
	}

}

function TransactionPrintCtrl($scope, $rootScope, $routeParams, $location, Bill, Print, Type)
{
	$scope.billPayment = Type.getBillPayment(function(data){
		$scope.meter_ids = [];
		$.each(data.meters, function(index, value) {
		    console.log(value);
		    $scope.meter_ids.push(value);
		}); 
		$scope.getPaymentBase();
	})

	var obj = loadTempData();
	var transaction_ids = obj.transaction_ids;
	console.log('load temp data');
	console.log(obj);
	console.log(transaction_ids);
	Bill.test( {action:'transactions', transaction_ids:transaction_ids},function(transactions){
		console.log('transactions');
		console.log(transactions);
		for(var i = 0 ; i < transactions.length ;i++)
		{
			transaction = transactions[i];
			if(typeof(transaction.variables) == 'string')
			{
				//tran
				console.log(transaction.variables);
				temp = JSON.parse(transaction.variables);
			
				transaction.variables = temp.variables;
				transaction.payments = temp.payments;
			}
			//transaction.payments = JSON.parse(transaction.payments);
		}
		$scope.bills = transactions;
		$scope.bills = convertBillPrint($scope, transactions)          
       for(var i=0; i < $scope.bills.length;i++)
		{
			for(var j=0; j < $scope.bills[i].payments.length;j++)
			{
				var payment = $scope.bills[i].payments[j];
				//payment.test_formulas = []
				payment.formulas[0] =  $scope.getFormulaValue($scope.bills[i].variables,payment.formulas[0]);
				payment.formulas[1] =  $scope.getFormulaValue($scope.bills[i].variables, payment.formulas[1]);
				payment.formulas[2] =  $scope.getFormulaValue($scope.bills[i].variables, payment.formulas[2]);

			}	
			//console.log('update new payment');
			console.log('update-new-payment');
			$scope.bills[i].payments = updateNewPayment( $scope.bills[i].payments, 
			 	$scope.bills[i].getPaymentBase( $scope.bills[i].variables,  $scope.bills[i].payments).sum_bank_loan)
			console.log('check bank payment');
			

			var sum_bank_payment = 0;
			for(var j = 0; j < $scope.bills[i].payments.length; j++)
			{
				var bill_payment = $scope.bills[i].payments[j];
				//console.log('check-bill')
				 sum_bank_payment += Number(bill_payment.formulas[BANK_INDEX]);
			}
			console.log( sum_bank_payment );

			if(!isNaN(sum_bank_payment))
			{

				var payment_base = $scope.bills[i].getPaymentBase( $scope.bills[i].variables,  $scope.bills[i].payments);
				console.log(payment_base.sum_bank_loan);
				//console.log($scope.bills[i].payments)
				if(sum_bank_payment < payment_base.sum_bank_loan)
				{
					$scope.bills[i].bank_pay_back = payment_base.sum_bank_loan - sum_bank_payment;
					var bank_payback_payment_id = 47;
					for(var j =0; j < $scope.bills[i].payments.length; j++)
					{
						var bill_payment = $scope.bills[i].payments[j];
						if(bill_payment.id == bank_payback_payment_id)
						{
							bill_payment.formulas[BANK_INDEX] = $scope.bills[i].bank_pay_back;
							break;
						}
					} 
				}
			}
			
			//check if sum_bank_payment <= bank_loan?
			//convert payment bank to blablabla
		}
		console.log('check payment')
		console.log(transactions[0].payments)
	});

}

function TransactionEditCtrl($scope, $rootScope, $routeParams, $location, Bill, Print, Type)
{
	$scope.billPayment = Type.getBillPayment(function(data){
		$scope.meter_ids = [];
		$.each(data.meters, function(index, value) {
		    console.log(value);
		    $scope.meter_ids.push(value);
		}); 
		$scope.getPaymentBase();
	})
	var transaction_id = $routeParams.transaction_id;
	$scope.bill = Bill.view({id:transaction_id},function(transaction){
		console.log('view');
		console.log(transaction);
		$scope.variables = [];
		transaction.variables = transaction.variables;
		transaction.payments = transaction.payments;
			//transaction.payments = JSON.parse(transaction.payments);
		var transactions = [];
		transactions[0] = transaction;
		$scope.bills = convertBillPrint($scope, transactions);
		$scope.bill = $scope.bills[0];
	});

	$scope.save = function()
	{
		console.log('gonna create transaction');
		console.log($scope.bill);
		$scope.bill = convertBillToSaveBill($scope.bill);
		var bills = [];
		bills[0] = $scope.bill;
		console.log({action:'createTransaction', template_id:$scope.bill.template_id, bills:bills});
		Bill.createTransaction({action:'createTransaction', template_id:$scope.bill.template_id, bills:bills}, function(data){
    				console.log(data);
    		});
	}
}


function convertBillToSaveBill(bill)
{
	console.log("convert :"+ bill.variables.length)
	console.log(bill);
	if(typeof(bill.variables.variables) != 'undefined')
		var variables = bill.variables.variables;
	else
		var variables = bill.variables;

	console.log(variables)
	for(var i =0 ;i < variables.length ; i++)
		{

			var variable = variables[i];
			for(var key in variable)
			{
				if(isNaN(key))
				{
					//eval_str += "bill."+key+" = bill.getVar('"+key+"')";
					//eval(eval_str);

					console.log('gonna change:'+key+" from :"+variable[key] +" to :"+bill[key])
					variable[key].value = bill[key];
				}
			}
		}
	if(typeof(bill.variables.variables) != 'undefined')
		bill.variables = bill.variables.variables;
	else
		bill.variables = variables;
	console.log('after convert')
	console.log(bill);
	return bill;
}

function convertBillPrint($scope, data)
{

	$scope.bills = data;
	console.log('convert')
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

		bill.getVar = bill.getVariablesValue = function(varname , variables)
		{
			//console.log('log');
			//console.log(bill_vars)
			if(variables == null)
				return getVariablesValue(bill_vars, varname);
			else
				return getVariablesValue(variables, varname);
		}

		bill.getName = function(varname)
		{
			var myvar =  getVariables(bill_vars, varname);
			if(myvar == undefined)
				return undefined;
			return myvar.name;
		}

		bill.getPaymentByPaymentId = function(id, variables, payments)
		{
			//console.log('payments')
			if(typeof(bill.payments) != 'undefined')
			{
				//console.log('id:'+id+" payment:"+getPaymentByPaymentId(id, payments, variables))
				return getPaymentByPaymentId(id, payments, variables);
			}else
				return null;
		}

		bill.getFinalCustomerPayment = function(variables, payments)
		{
			var getVar  = bill.getVar;
			var firstSum = $scope.getSumCustomerPayment(variables, payments);
			//var commonCharge = getVar("commonFeeCharge", variables);
			var commonCharge = bill.commonFeeCharge;
			if(typeof(commonCharge) != 'number')
				commonCharge = 0;
			//var commonFund = getVar("commonFeeFund", variables);
			var commonFund = bill.commonFeeFund;
			if(typeof(commonFund) != 'number')
				commonFund = 0;
			
			return Number(bill.getCashPayment(variables, payments) )
				+ Number(bill.getMinistryPayment(variables, payments) )
				+ Number(bill.getPaymentBase(variables, payments).share_fund_payment )
				+ Number(bill.getPaymentBase(variables, payments).share_payment )
				+ Number(bill.getShowCompanyPayment(variables, payments)  )
				+ Number(bill.getShowBankPayment(variables, payments) )
			
			/*return  bill.getShowCompanyPayment(variables, payments)  
				+ bill.getShowBankPayment(variables, payments)
	*/
			//return firstSum + commonFund + commonCharge + getVar("feeForMinistryOfFinance", variables) + getVar("feeForTranferCash", variables)
			//return firstSum + commonFund + commonCharge + bill.feeForMinistryOfFinance + bill.feeForTranferCash;
		}

		bill.getDiffArea = function(actual, contract)
		{
			if(actual == null)
				return null;
			else if(contract)
				return null;
			return contract - actual;
		}

		bill.getShowBankPayment = function(variables, payments)
		{
			if(bill.getVar("UnitId",variables)== 2174)
				console.log(variables);;
			var payment_base = bill.getPaymentBase(variables, payments);
			//var A = payment_base.meter_payment + payment_base.room_payment;
			//return Math.max(0, bill.getRealBankPayment(variables, payments) );
			return Math.max(0, bill.getReductRepayment(variables, payments) );
		}

		bill.getShowCompanyPayment = function(variables, payments)
		{
			var payment_base = bill.getPaymentBase(variables, payments);
			//if(bill.UnitId == 2174)
				//console.log(bill);
			var A = payment_base.customer_room_payment + payment_base.customer_meter_payment;
			//console.log("A:"+A);
			return A - bill.getShowBankPayment(variables, payments); 
			var payment_base = $scope.getPaymentBase();
		}

		 bill.getReductRepayment = function(variables, payments)
	    {
	        var payment_base = bill.getPaymentBase(variables, payments) ;
	        var repayment = bill.Repayment;
	        if(isNaN(repayment))
				repayment = 0;
			//case roompayment > repayment
	        return repayment - payment_base.sum_bank_loan
	    }


		bill.getRealBankPayment = function(variables, payments)
		{
			var payment_base = bill.getPaymentBase(variables, payments);
			//var repayment = bill.getVar("Repayment", variables);
			var repayment = bill.Repayment;
			//console.log('update repayment:'+repayment)
			//console.log(variables)
			if(isNaN(repayment))
				repayment = 0;
			return repayment ;
		}

		bill.getPaymentBase = function(variables, payments)
		{
			//meter
			var meter_payment = 0;
			var customer_meter_payment = 0;
			var customer_room_payment = 0;
			for(var i = 0; i < $scope.meter_ids.length ;i++)
			{
				var id = $scope.meter_ids[i];
				var payment = bill.getPaymentByPaymentId(id, variables, payments);

				if(payment != null && !isNaN(payment[CUSTOMER_INDEX]))
				{
					customer_meter_payment +=  Number(payment[CUSTOMER_INDEX]);
					meter_payment += Number( payment[CUSTOMER_INDEX]);
					if(!isNaN(payment[BANK_INDEX]))
					{
						meter_payment += Number( payment[BANK_INDEX]);
					}
					
				}
				
			}


			var room_payment_def = bill.getPaymentByPaymentId($scope.billPayment.room_payment, variables, payments);
			customer_room_payment = 0;
			if(isNaN(room_payment_def[CUSTOMER_INDEX]))
				room_payment = 0;
			else
			{
				room_payment = Number(room_payment_def[CUSTOMER_INDEX]);
				customer_room_payment += room_payment;
			}
			
			if(!isNaN(room_payment_def[BANK_INDEX]))
			{
				room_payment += Number(room_payment_def[BANK_INDEX]);
			}



			var share_payment = bill.getPaymentByPaymentId($scope.billPayment.share_payment_id, variables, payments);	
			if(isNaN(share_payment[CUSTOMER_INDEX]))
				share_payment = 0;
			else
				share_payment = Number(share_payment[CUSTOMER_INDEX]);

			var tranfer_payment = bill.getPaymentByPaymentId($scope.billPayment.tranfer_payment_id, variables, payments);
			if(isNaN(tranfer_payment[CUSTOMER_INDEX]))
				tranfer_payment = 0;
			else
				tranfer_payment = Number(tranfer_payment[CUSTOMER_INDEX]);

			var share_fund_payment = bill.getPaymentByPaymentId($scope.billPayment.share_fund_payment_id, variables, payments);	
			if(isNaN(share_fund_payment[CUSTOMER_INDEX]))
				share_fund_payment = 0;
			else
				share_fund_payment = Number(share_fund_payment[CUSTOMER_INDEX]);
		
			var sum_bank_loan = bill.getVar('BankLoanRoom', variables);
			//console.log(bill);
			var sum_bank_loan_payment =  bill.getPaymentByPaymentId($scope.billPayment.loan_payment_id, variables, payments);
			var customer_sum_bank_loan = 0;
			if(!isNaN(sum_bank_loan_payment[CUSTOMER_INDEX]))
				customer_sum_bank_loan += Number(sum_bank_loan_payment[CUSTOMER_INDEX]);

			var tax_payment = bill.getPaymentByPaymentId($scope.billPayment.tax_payment_id, variables, payments);
	
			if(isNaN(tax_payment[CUSTOMER_INDEX]))
				tax_payment = 0
			else
				tax_payment = Number(tax_payment[CUSTOMER_INDEX]);
			var tax_loan_payment = bill.getPaymentByPaymentId($scope.billPayment.tax_loan_payment_id, variables, payments);
			
			if(isNaN(tax_loan_payment[CUSTOMER_INDEX]))
				tax_loan_payment = 0;
			else
				tax_loan_payment = Number(tax_loan_payment[CUSTOMER_INDEX]);
			 


			if(typeof(sum_bank_loan) == 'undefined' || sum_bank_loan == null || sum_bank_loan == '-')
				sum_bank_loan = 0;

			console.log('check sum bank loan')
			console.log(sum_bank_loan)
			if(sum_bank_loan == 0)
				bill.isCashTranfer = true;
			else
				bill.isCashTranfer = false;

			var cur_date = new Date();
			var cur_month = cur_date.getMonth();
			var cur_year = cur_date.getYear() +   (1900 + 543);
			var month_th = ['มกราคม', 'กุมภาพันธุ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤษจิกายน', 'ธันวาคม' ]
			cur_month = month_th[cur_month];
			bill.currentDate = (cur_date.getDate()) + ' ' + cur_month + ' ' +cur_year;

			//ค่าห้อง
			var payment_base = {
				meter_payment:meter_payment,
				room_payment:room_payment,
				share_payment:share_payment,
				tranfer_payment:tranfer_payment,
				share_fund_payment:share_fund_payment,
				sum_bank_loan:sum_bank_loan,
				customer_room_payment:customer_room_payment,
				customer_meter_payment:customer_meter_payment,
				customer_sum_bank_loan:Number(customer_sum_bank_loan),
				tax_loan_payment:tax_loan_payment,
				tax_payment:tax_payment
			};
			if(sum_bank_loan >= 4093716.09)
			{
				console.log('payment base')
				console.log(payment_base)
				console.log('trander')
				console.log(bill.getPaymentByPaymentId($scope.billPayment.tranfer_payment_id, variables, payments));
			}
			//console.log('payment base');
			//console.log(payment_base);
			
			return payment_base;
		}

		bill.getMinistryPayment = function(variables, payments)
		{
			var estimate = bill.getVar("EstimatePrice", variables);
			//var estimate = bill.EstimatePrice;
			var payment_base = bill.getPaymentBase(variables, payments);
			var estimate_payment = payment_base.tranfer_payment;
			
			var answer = estimate_payment + ( payment_base.customer_sum_bank_loan) + payment_base.tax_payment + payment_base.tax_loan_payment;
			
			bill.ministryMinus = 0;
			var real_answer =  answer - bill.getCashPayment(variables, payments);
			console.log('ministry')
			console.log(answer);
			console.log(bill.getCashPayment(variables, payments))
			console.log(real_answer)
			if(real_answer < 0)
			{
				bill.ministryMinus = real_answer;
				return 0;
			}else
			{
			//	bill.ministryMinus = 0;
				return real_answer;
			}

			//return answer - bill.getCashPayment(variables, payments);
			
		}

		bill.getCashPayment = function(variables, payments)
		{
			return 1000 + bill.ministryMinus ;
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

		var appoint_payment = {"id":-1,"order":11,"name":"ค่าใช้จ่าย ณ วันโอน","description":"","formulas":[0,0, Number(bill.AppointPayment)],"is_shows":[1,1,1],"is_add_in_cheque":0,"is_compare_with_repayment":0,"number":11}
		bill.payments.push(appoint_payment);

		bill.SumMini  = 0;
		if(!isNaN(bill.BankLoanInsurance))
			bill.SumMini += Number(bill.BankLoanInsurance);
		if(!isNaN(bill.BankLoanDecorate))
			bill.SumMini += Number(bill.BankLoanDecorate);

	}

	/* Scope Fundamental */
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
		//console.log('sum again');
		if(payments == undefined)
			return null;
		var sum = 0;
		
		for(var i = 0;i < payments.length; i++)
		{
			var raw_formula = payments[i].formulas[index];
			//console.log(raw_formula);
			//bill.payments[i].formulas[index] = bill.getFormulaValue(raw_formula);
			//console.log('is Number:'+(!isNaN(raw_formula)))

			var	value = $scope.getFormulaValue(variables, raw_formula);;
			//console.log('value:'+value)

			if(value !=null && !isNaN(value))
			{
				value = Number(value);
				sum += value;
				//console.log('after sum:'+sum)
			}
			
		}
		//console.log('get sum:'+sum)
		return sum;
	}


	


	return $scope.bills;
}

function updateNewPayment(payments, sum_bank_loan)
{
	console.log('update new payment')
	console.log('sum bank loan');
	console.log(sum_bank_loan)
	/*if(isNaN(sum_bank_loan) ||sum_bank_loan == 0)
		return payments;*/
	console.log('update payments here')
	console.log(sortPaymentByOrder(payments));
	payments = sortPaymentByOrder(payments);
	var sum_bank = sum_bank_loan;
	console.log('check payments count :' + payments.length)
	for(var i =0; i < payments.length;i++)
	{
		var payment = payments[i];
		console.log('check payment');
		console.log(payment)
		//promotion zone
		if(typeof(payment.promotion) != 'undefined')
		{
			var promotion_discount = payment.promotion.spacial_discount;
			if(payment.promotion.is_discount_percent)
			{
				promotion_discount = promotion_discount / 100 * payment.formulas[CUSTOMER_INDEX];
			}
			payment.formulas[CUSTOMER_INDEX] -= promotion_discount;
			if(!isNaN(promotion_discount))
				payment.formulas[COMPANY_INDEX] += promotion_discount
		}


		if(payment.is_compare_with_repayment && !(isNaN(sum_bank_loan) ||sum_bank_loan == 0))
		{
			//var discount = sum_bank - payment.formulas[CUSTOMER_INDEX] ;
			console.log('before '+ sum_bank);
			if(sum_bank - payment.formulas[CUSTOMER_INDEX] > 0 )
			{
				payment.formulas[BANK_INDEX] = 0;
				//console.log('customer payment ' + payment.formulas[CUSTOMER_INDEX])
				var discount = Math.min(sum_bank, payment.formulas[CUSTOMER_INDEX]);

				payment.formulas[CUSTOMER_INDEX] -= discount;
				if(!isNaN(discount))
					payment.formulas[BANK_INDEX] += discount;
				sum_bank -= discount;;
			//	console.log('after customerPayment ' + payment.formulas[CUSTOMER_INDEX]);
			//	console.log('afer bank payment' + payment.formulas[BANK_INDEX])
			}else
			{	
				//console.log('over load cause :' + payment.formulas[CUSTOMER_INDEX] )
				payment.formulas[CUSTOMER_INDEX] -= sum_bank;
				if(!isNaN(sum_bank))
					payment.formulas[BANK_INDEX] += sum_bank;
				sum_bank = 0;;
			}

			console.log('after '+ sum_bank);

		}



		
	}



	return payments;
}

function sortPaymentByOrder(payments)
{
    console.log('sort');
    console.log(payments);
    for(var i =0; i < payments.length ; i++)
    {
        for(var j = i+1 ; j < payments.length;j++)
        {
            if(payments[i].order > payments[j].order)
             {
                var temp = payments[i];
                payments[i]= payments[j];
                payments[j] = temp;
             
             }
        
        
        }
    
    }
     console.log(payments);
	return payments;
}

function ChequeCtrl($scope, $rootScope, $routeParams, $location, Bill, Print, Type)
{
	$scope.billPayment = Type.getBillPayment(function(data){
		$scope.meter_ids = [];
		$.each(data.meters, function(index, value) {
		    console.log(value);
		    $scope.meter_ids.push(value);
		}); 
		$scope.getPaymentBase();
	})

	var obj = loadTempData();
	var transaction_ids = obj.transaction_ids;
	console.log('load temp data');
	console.log(obj);
	console.log(transaction_ids);
	Bill.test( {action:'transactions', transaction_ids:transaction_ids},function(transactions){
		console.log('transactions');
		console.log(transactions);
		for(var i = 0 ; i < transactions.length ;i++)
		{
			transaction = transactions[i];
			if(typeof(transaction.variables) == 'string')
			{
				temp = JSON.parse(transaction.variables);
			
				transaction.variables = temp.variables;
				transaction.payments = temp.payments;
			}
			//transaction.payments = JSON.parse(transaction.payments);
		}
		$scope.bills = transactions;
		$scope.bills = convertBillPrint($scope, transactions)
		for(var i=0; i < transactions.length;i++)
		{
			for(var j=0; j < transactions[i].payments.length;j++)
			{
				var payment = transactions[i].payments[j];
				//payment.test_formulas = []
				payment.formulas[0] =  $scope.getFormulaValue(transactions[i].variables,payment.formulas[0]);
				payment.formulas[1] =  $scope.getFormulaValue(transactions[i].variables, payment.formulas[1]);
				payment.formulas[2] =  $scope.getFormulaValue(transactions[i].variables, payment.formulas[2]);
			}	
		}
		console.log('check payment')
		console.log(transactions[0].payments)
	});

}
