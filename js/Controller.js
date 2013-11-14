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
		var params_name = ['ItemId', 'ProjID', 'room_type', 'Floor', 'CompanyCode','SalesName'];
		//ss.company = ss.company.toLowerCase();
		var check_params = [ss.unit, ss.project, ss.type, ss.floor, ss.company, ss.customer_name];
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


function BillEditCtrl($scope, $rootScope, $routeParams, $location, Npop)
{

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

function AppointCtrl($scope, $filter, $rootScope, $location, $routeParams, Appoint, Promotion, Bill)
{
	$scope.authorize = "0";
	$scope.is_authorize = false;
	var now = new Date();
	var getData = getGetData();
	$scope.payment_types = Appoint.getPaymentTypes(function(data){
		$scope.payment_type_obj = data.find({id:getData.id_status_transfer});
	});
	$scope.authorize_status_types = Appoint.getAppointAuthorizeStatus(function(data){
		console.log('status_types')
		console.log(data);
		$scope.authorize_status = data.find({id:getData.id_status_authorize});
	});
	console.log('test');
	console.log(getGetData());
	$scope.showPromotion = function()
	{
		console.log({itemId:$routeParams.itemId, invoiceAccount:$scope.unit.invoice_account});
		$scope.promotions = Promotion.forUser({itemId:$routeParams.itemId, invoiceAccount:$scope.unit.invoice_account}, function(data){
			console.log(data);
			for(var i =0; i < data.length;i++)
				data[i].checked = true;
		})
	}

	$scope.togglePromotion = function()
	{
		if(typeof($scope.promotions) == 'undefined')
			$scope.showPromotion();
		if($scope.promotions.length > 0)
			$scope.promotions = [];
		else
			$scope.showPromotion();
	}

	$scope.selectedPromotions = function () {
    	return $filter('filter')($scope.promotions, {checked: true});
	};
	

	$scope.appointdate =  {date: {date: new Date("2012-09-01T00:00:00.000Z")}};
	$scope.refresh = function(){
		$scope.data = Appoint.get({itemId:$routeParams.itemId}, function(data){
			$scope.unit = data.unit;
			$scope.logs = data.logs;
			$scope.people = data.appointUser.name;
			$scope.calldate = new Date();
			$scope.calltime = '';
			console.log('fetch transaction');
			console.log({unit_id:$scope.unit.id});
			$scope.transaction = Bill.viewTransaction({unit_id:$scope.unit.id}, function(data){
				console.log(data);
			})
			for(var i=0; i < $scope.logs.length; i++)
			{
				var log = $scope.logs[i];
				/*var t = "2010-06-09 13:12:01".split(/[- :]/);
				// Apply each element to the Date function
				var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);*/
				log.appoint_date_time = new Date(log.appoint_time.date);
				log.call_date_time = new Date(log.call_time.date);
				log.display_call_date = log.call_date_time.getDate() + '/' + (log.call_date_time.getMonth() +1)+ '/' + (log.call_date_time.getYear() + 1900);
				log.display_appoint_date = log.appoint_date_time.getDate() + '/' + (log.appoint_date_time.getMonth()+1) + '/' + (log.appoint_date_time.getYear() + 1900);

				log.display_call_time = log.call_date_time.getHours() + ':' + log.call_date_time.getMinutes() + ':' + log.call_date_time.getSeconds();
				log.display_appoint_time = log.appoint_date_time.getHours() + ':' + log.appoint_date_time.getMinutes() + ':' + log.appoint_date_time.getSeconds();
			}
			console.log($scope.logs);
			$scope.current_appoint = data.appoint;

		})
	}
	$scope.paymentAtTranfer = 0;
	$scope.promotionCo = 0;
	$scope.createAppoint = function()
	{
		$scope.def_appointdate = $scope.appointdate ;
		$scope.def_appointtime = $scope.appointtime;
		$scope.def_calldate = $scope.calldate;
		$scope.def_calltime  = $scope.calltime;

		$scope.appointdate = convertDateToSqlDate($scope.appointdate);
		if($scope.appointtime != '')
			$scope.appointtime = convertDateToSqlTime($scope.appointtime);
		else
			$scope.appointtime = '00:00:00';
		$scope.calldate = convertDateToSqlDate($scope.calldate);
		if($scope.calltime != '')
			$scope.calltime = convertDateToSqlTime($scope.calltime);
		else
			$scope.calltime = '00:00:00';
		$scope.paymentdate = convertDateToSqlDate($scope.paymentdate);
		$scope.contractdate = convertDateToSqlDate($scope.contractdate);


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
		console.log($scope.authorize_status)
		Appoint.create({type:$scope.type, call_date:$scope.calldate, call_time:$scope.calltime, call_duration:$scope.callduration , people:$scope.people, 
			appoint_date:$scope.appointdate, appoint_time:$scope.appointtime, status:$scope.status, payment_type:$scope.payment_type, coming_status:$scope.coming_status, remark:$scope.remark,
			unit_id:$scope.unit.id, action:'createAppoint', authorize:$scope.authorize, payment_date:$scope.paymentdate, contract_date:$scope.contractdate, tranfer_status:$scope.authorize_status.id,promotions:$scope.selectedPromotions(),
			payment:$scope.paymentAtTranfer,promotion_co:$scope.promotionCo

		}, function(data){
			console.log(data);
			$scope.refresh();
			 $scope.appointdate= $scope.def_appointdate  ;
			$scope.appointtime = $scope.def_appointtime ;
			$scope.calldate = $scope.def_calldate;
			$scope.calltime = $scope.def_calltime ;
		});

	}

	$scope.updatePaymentType = function()
	{
		$scope.payment_type = $scope.payment_type_obj.id;
		console.log( $scope.payment_type_obj);
		console.log($scope.payment_type);
	}
	$scope.refresh();
	
	doOnce(function(){
		//$('.datepicker').datepicker({format:'yyyy-mm-dd'});
		
	})

	$scope.updateIsAuthorize = function()
	{
		console.log('kap');
		console.log($scope.authorize)
		if($scope.authorize == 1)
			$scope.is_authorize = true;
		else if($scope.authorize == 0)
			$scope.is_authorize = false;
	}
	

}

function PromotionCtrl($scope, $rootScope, $location, $filter, Promotion, Unit, Type)
{
	$scope.promotion = {};

	$scope.promotions = Promotion.query();
	$scope.promotion_payment_types = Promotion.getTypes();
	$scope.phases = Promotion.getPhases();
	$scope.spacial_payments = Promotion.spacialTypes();


	$scope.promotion_types =[
		{id:-1, name:"ALL"},
		{id:0, name:"AX"},
		{id:1, name:"Not AX"}
	]

	$scope.PROMOTION_ALL = $scope.promotion_types[0];
	$scope.PROMOTION_AX = $scope.promotion_types[1];
	$scope.PROMOTION_NOT_AX = $scope.promotion_types[2];
	$scope.PROMOTION_PRE_APPROVE = $scope.promotion_types[3];
	$scope.PROMOTION_TRANFER= $scope.promotion_types[4];

	$scope.promotion.type = $scope.PROMOTION_TRANFER 



	$scope.projects = Type.getProjectsList();
	$scope.units = []
	$scope.status = {}
	$scope.search = {};

	$scope.searchUnit = function(callback)
	{
		var ss= $scope.search;
		var query = "";
		var params_name = ['ItemId', 'ProjID', 'room_type', 'Floor', 'CompanyCode','SalesName', 'Period', 'SQM'];
		//ss.company = ss.company.toLowerCase();
		var period = '';
		period += typeof(ss.date_from) == 'undefined' || ss.date_from == null ? 0 : ss.date_from;
		period += '|';
		period += typeof(ss.date_to) == 'undefined' || ss.date_to == null ? 0 : ss.date_to;
		console.log(period);
		var sqm = '';
		if (ss.area) {
			sqm += typeof(ss.area.from) == 'undefined' || ss.area.from == null ? 0 : ss.area.from;
			sqm += '|';
			sqm += typeof(ss.area.To) == 'undefined' || ss.area.To == null ? 0 : ss.area.To;
		}
		var check_params = [ss.unit, ss.project.value + '', ss.type, ss.floor, ss.company, ss.customer_name, period, sqm];
		var params_count = 0;
		console.log('check params')
		console.log(check_params)
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
		$scope.units = Unit.query({'q':query,'from':0,to:5000},function(){
			$scope.loading = false;
			if(typeof(callback) == 'function')
				callback();
		})
	}

	$scope.updateProject = function()
	{
		console.log($scope.promotion.project)
		$scope.search.project = $scope.promotion.project.value;
	}

	$scope.choosePromotion = function(){
		$scope.selectPromotion = $scope.selectedPromotions();
		$scope.units =  Unit.query({action:"units"}, function(data){
			data.promotion_status = "none";
		});	
	}
	 
	$scope.chooseUnit = function(unit)
	{
		$('#select-unit').show();
		$('#select-status').hide();
		console.log('choose unit');
		console.log(unit)
		$scope.selectItem = unit;
		$scope.selectItem.promotions = [
			{id:0, name:"ฟรีค่าโอน", cost:32000},
			{id:1, name:"ฟรีค่าธรรมเนียม", cost:30000},
			{id:2, name:"ฟรทีวี"}
		]
	}

	$scope.givePromotion = function()
	{	
		$('#select-unit').hide();
		$('#select-status').show();
		$scope.status.units = $scope.selectedUnits();
		$scope.status.promotions = $scope.selectedPromotions();
	}



	$scope.selectedPromotions = function () {
    	return $filter('filter')($scope.promotions, {checked: true});
	};	

	$scope.selectedPromotionsById = function(id)
	{
		return $filter('filter')($scope.promotions, {id: id});
	}

	$scope.selectedUnits = function () {
    	return $filter('filter')($scope.units, {checked: true});
	};	

	$scope.findStuffIn = function(arrayOfStuff, attributeOfItem, value)
	{
		for(var i =0 ;i < arrayOfStuff.length; i++)
			if(arrayOfStuff[i][attributeOfItem] == value)
				return arrayOfStuff[i];
		return null;
	}

	$scope.loadPromotion = function(id)
	{
		$scope.promotion = $scope.selectedPromotionsById(id)[0];
		$scope.promotion.type = $scope.findStuffIn($scope.promotion_payment_types, 'id', $scope.promotion.reward_id);
		var discount_type = $scope.findStuffIn($scope.promotion_payment_types, 'code', 'discount');
		
		if($scope.promotion.type.id == discount_type.id)
		{
			$scope.promotion.payment_id = $scope.promotion.option1;

		}
	}

	$scope.updatePromotion = function()
	{
		console.log('update');
		if( typeof($scope.search.promotion) != 'undefined')
		{
			$scope.loadPromotion($scope.search.promotion.selectedId);
			
		}
			
		/*if($scope.promotion.type.id  == $scope.PROMOTION_ALL.id || $scope.promotion.type.id  == $scope.PROMOTION_AX.id)
			$scope.promotions =Promotion.listAx(function(data){
				for(var i =0;i < data.length;i++)
				{
					data[i].name = data[i].ITEMNAME;
					data[i].id = data[i].RECID;
				}

				var non_ax_promotion = loadLocal();
				$scope.promotions = non_ax_promotion.concat(data);

			})
		else if($scope.promotion.type.id  == $scope.PROMOTION_AX.id)
		{
			$scope.promotions =Promotion.listAx(function(data){
				for(var i =0;i < data.length;i++)
				{
					data[i].name = data[i].ITEMNAME;
					data[i].id = data[i].RECID;
				}
			});
		}
		else if($scope.promotion.type.id == $scope.PROMOTION_NOT_AX.id)
			$scope.promotions = loadLocal();
		else
			$scope.promotions = [];*/

	}

	$scope.createCondition = function()
	{
		console.log($scope.promotion.id)
		if(typeof($scope.search.to) != 'undefined')
			$scope.search.date_to = $scope.search.to.convertToSqlDate();
		if(typeof($scope.search.from) != 'undefined')
			$scope.search.date_from = $scope.search.from.convertToSqlDate()
		if(typeof($scope.search.project)  != 'undefined')
			$scope.search.project_id  = $scope.search.project.id;
		if(typeof($scope.search.phase)  != 'undefined')
			$scope.search.phase_id =  $scope.search.phase.id
		else
			$scope.search.phase_id = -1;
		
		console.log($scope.search);
		Promotion.createCondition({action:'createCondition', condition:$scope.search, promotion_id:$scope.promotion.id}, function(data){
			console.log('after create condition')
			console.log(data);
			$scope.searchUnit(function(){
				var unit_ids = [];
				var invoice_accounts = [];
				for(var i=0; i < $scope.units.length;i++)
				{
					unit_ids.push($scope.units[i].id);
					invoice_accounts.push($scope.units[i].invoice_account);
				}
				console.log('units');
				console.log(unit_ids)
				console.log('invoices');
				console.log(invoice_accounts)
				Promotion.matchPromotion({action:'matchPromotion', condition_id:data.condition_id, unit_ids:unit_ids, invoice_accounts:invoice_accounts},function(data){

					console.log(data);
					$scope.loadMatchPromotions();
				})
			});
			

		})
		
	}

	$scope.clear = function()
	{
		$scope.search = {};
	}

	$scope.deleteCondition = function(cond)
	{
		Promotion.deleteCondition({action:'deleteCondition', condition:cond},function(data){
			$scope.loadMatchPromotions();	
		})
		
	}

	$scope.loadMatchPromotions = function()
	{
		$scope.match_promotions = Promotion.listConditions(function(data){
			for(var i =0;i <data.length;i++)
			{
				data[i].type =  $scope.findStuffIn($scope.promotion_payment_types, 'id', data[i].reward_id);
				data[i].from = new Date(data[i].date_from.date);
				data[i].to = new Date(data[i].date_to.date);
				data[i].phase =  $scope.findStuffIn($scope.phases, 'id', data[i].phase_id);
				data[i].unitCount = Promotion.countUnit({condition_id:data[i].id});
			}
			console.log(data);

		});
		
	}

	$scope.loadMatchPromotions();

	//$scope.updatePromotion();
	//$scope.searchUnit();


}

function PromotionCreateCtrl($scope, $rootScope, $location, $filter, Promotion, Unit, Type, Payment, Promotion)
{
	$scope.localPromotions = loadLocal();
	$scope.question = {};
	var PAYMENT_FIX_ID = 0, PAYMENT_PERCENT_ID = 1;
	var PAY_TYPE_DISCOUNT =2, PAY_TYPE_CASHBACK =1, PAY_TYPE_SPACIAL_DISCOUNT =4, PAY_TYPE_STUFF =0;
	$scope.promotion = {};
	$scope.projects =Type.getProjectsList();
	$scope.types =  Promotion.getTypes();

	$scope.promotion_payment_types = Promotion.getTypes();

	$scope.payments = Payment.query();

	$scope.payment_types =  Promotion.getPaymentTypes();

	$scope.getClassPayment = function()
	{
		if(typeof($scope.promotion.type) == "undefined")
			return "hide";
		if($scope.promotion.type.id == PAY_TYPE_DISCOUNT)
			return "show"
		else
			return "hide"
	}

	$scope.getClassStuff = function()
	{
		if(typeof($scope.promotion.type) == "undefined")
			return "hide";
		if($scope.promotion.type.id == PAY_TYPE_STUFF)
			return "show"
		else
			return "hide"
	}

	$scope.getClassAmount = function()
	{
		
			return "show"
	}

	$scope.save = function()
	{
		console.log($scope.promotion);
		Promotion.create({action:'createPromotion', promotion:$scope.promotion}, function(data){
			console.log(data);
			$location.path('/promotions');
		});
		/*$scope.promotion.id = localStorage.lasted;
		$scope.localPromotions.push($scope.promotion);
		console.log($scope.localPromotions);
		saveLocal($scope.localPromotions);
		$location.path('/promotions');*/
	}
}

function PromotionUpdateCtrl($scope, $rootScope, $location, $routeParams, $filter, Promotion, Unit, Type, Payment)
{
	
	$scope.question = {};

	$scope.projects =Type.getProjectsList();
	var PAYMENT_FIX_ID = 0, PAYMENT_PERCENT_ID = 1;
	var PAY_TYPE_DISCOUNT =2, PAY_TYPE_CASHBACK =1, PAY_TYPE_SPACIAL_DISCOUNT =4, PAY_TYPE_STUFF =0;
	$scope.types =  Promotion.getTypes();

	$scope.promotion_payment_types = Promotion.getTypes();

	$scope.payments = Payment.query();

	$scope.payment_types =  Promotion.getPaymentTypes();

	$scope.payments = Payment.query();

	$scope.promotion = Promotion.get({action:'promotion',id:$routeParams.pid},function(data){
		
		data.type = $scope.promotion_payment_types.findById(data.reward_id);
		var discount_type = $scope.promotion_payment_types.find({code:'discount'});
		console.log('---');
		console.log(discount_type)
		console.log(data.type)
		console.log('---');
		var stuff_type = $scope.promotion_payment_types.find({code:'stuff'});
		if(data.type.id == discount_type.id)
		{
			console.log($scope.payments);
			data.payment = $scope.payments.findById(Number(data.option1));
			data.paymentType = $scope.payment_types.findById(Number(data.option2));
		}else if(data.type.id == stuff_type.id)
		{
			data.item = data.option1;
		}
		console.log(data);
	});

	$scope.getClassPayment = function()
	{
		if(isVar($scope.promotion).defined && typeof($scope.promotion.type) == "undefined")
			return "hide";
		if($scope.promotion.type.id == PAY_TYPE_DISCOUNT)
			return "show"
		else
			return "hide"
	}

	$scope.getClassStuff = function()
	{
		if(isVar($scope.promotion).defined && typeof($scope.promotion.type) == "undefined")
			return "hide";
		if( $scope.promotion.type.id == PAY_TYPE_STUFF)
			return "show"
		else
			return "hide"
	}

	$scope.getClassAmount = function()
	{
		
			return "show"
	}

	
	$scope.save = function()
	{
		console.log($scope.promotion);
		Promotion.update({action:'updatePromotion', promotion:$scope.promotion}, function(data){
			console.log(data);
		});
		//$location.path('/promotions');
	}

	$scope.delete = function()
	{
		Promotion.delete({action:'deletePromotion',promotion:$scope.promotion}, function(data){
			$location.path('/promotions');
		})
	}
}

function PromotionAxCtrl($scope, $rootScope, $location, $routeParams, $filter, Promotion, Unit, Type, Payment)
{
	$scope.promotion_payment_types = Promotion.getTypes();
	$scope.payments = Payment.query();

	$scope.payment_types =  Promotion.getPaymentTypes();
	$scope.refresh = function()
	{
		$scope.promotions = Promotion.listAx(function(data){
			$scope.promotion_groups = [];
			$scope.unset_promotion_groups = [];
			$scope.key_groups =[];
			$scope.unset_key_groups = [];
			for(var i=0; i < data.length;i++)
			{
				var promo = data[i];
				if(typeof($scope.key_groups[promo.ITEMNAME]) == 'undefined' && promo.type_id !=null)
				{
					$scope.key_groups[promo.ITEMNAME] = $scope.promotion_groups.length;

					var group = {}
					group.name = promo.ITEMNAME;
					group.ids = [];
					group.is_set = true;
					group.type = $scope.promotion_payment_types.find({id:promo.type_id})
					group.ids.push(promo.RECID);
					$scope.promotion_groups[$scope.key_groups[promo.ITEMNAME]] = group;
				}else if(promo.type_id !=null)
				{
					$scope.promotion_groups[$scope.key_groups[promo.ITEMNAME]].ids.push(promo.RECID)
					var promotion_group = $scope.promotion_groups[$scope.key_groups[promo.ITEMNAME]];
					var discount_type = $scope.promotion_payment_types.find({code:'discount'});

					
					if(promo.type_id == discount_type.id)
					{
						promotion_group.payment = $scope.payments.find({id:discount_type.id});
						promotion_group.paymentType = $scope.payment_types.find({id:promo.option2});
					}
					//$scope.key_groups[promo.ITEMNAME].index
					//$scope.promotion_groups[promo.ITEMNAME].ids.push(promo.RECID)
				}else if(typeof($scope.unset_key_groups[promo.ITEMNAME]) == 'undefined' )
				{
					$scope.unset_key_groups[promo.ITEMNAME] = $scope.unset_promotion_groups.length;

					var group = {}
					group.name = promo.ITEMNAME;
					group.is_set = false
					group.ids = [];
					group.ids.push(promo.RECID);
					$scope.unset_promotion_groups[$scope.unset_key_groups[promo.ITEMNAME]] = group;
				}else
				{
					$scope.unset_promotion_groups[$scope.unset_key_groups[promo.ITEMNAME]].ids.push(promo.RECID)
				}
				//console.log($scope.promotion_groups);
			}
		})

	}
	
	$scope.create = function(group)
	{
		console.log(group);
	}

	$scope.convertToPromotions = function(group)
	{
		var promotions = [];
		for(var i = 0; i <  group.ids.length ;i++)
		{
			var promotion = {};
			promotion.RECID = group.ids[i];
			promotion.type_id = group.type.id;
			var discount_type = $scope.promotion_payment_types.find({code:'discount'});
			if(promotion.type_id == discount_type.id)
			{
				promotion.option1 = group.payment.id;
				promotion.option2 = group.paymentType.id;
			}
			promotions.push(promotion)
		}
		return promotions;
	}

	$scope.update = function(group)
	{
		var promotions = $scope.convertToPromotions(group);
		console.log(promotions);
		Promotion.createAx({action:'createPromotionAx', promotions:promotions},function(data){

			console.log('after create');
			console.log(data);
			$scope.refresh();
		})

	}

	$scope.delete = function(group)
	{
		var promotions = $scope.convertToPromotions(group);
		Promotion.deleteAx({action:'deletePromotionAx', promotions:promotions}, function(data){
			console.log('after delete');
			console.log(data)
			$scope.refresh();
		})
	}

	$scope.getClassPayment = function(group)
	{
		var discount_type = $scope.promotion_payment_types.find({code:'discount'});			

		if(typeof(group) != 'undefined' && typeof(group.type) != 'undefined' && group.type.id == discount_type.id)
		{
			return "show";
		}
		else
			return "hide";
	}

	$scope.refresh();

}

function PromotionMatchCtrl($scope, $rootScope, $location, $routeParams, $filter, Promotion, Unit, Type, Payment)
{	
	$scope.promotion_payment_types = Promotion.getTypes(function(data){
		console.log(data.find({id:1}));
	});

	var tmpData= getGetData();
	console.log(tmpData);
	$scope.invoice_account = tmpData.InvoiceAccount;
	console.log($scope.invoice_account);
	$scope.unit = Unit.find({unit_id:$routeParams.unit_id},function(data){
		console.log(data);
		
		$scope.tranfer_promotions = Promotion.find({unit_id:$routeParams.unit_id, invoice_account:$scope.invoice_account}, function(data){
			seed = 1;
			for(var i = 0; i < data.length;i++)
			{
				promotion = data[i];
				promotion.promotion_type = 'tranfer';
				promotion.order =  seed;
				promotion.canPress = true;
				promotion.canUpdate= false;
				promotion.attemptChange = function()
				{
					var pass=prompt("Please Enter Your password","Ask P poom");
					if(pass == "9999")
					{
						this.canUpdate = true;
						this.canPress = false;
						console.log(this);
						console.log('#promo-'+this.id)
						console.log($('#promo-'+this.id))

						$('#promo-'+this.id).focus();
					}
					else
					{
						this.canUpdate = false;
						alert("Wrong password");
					}
				}
				promotion.changeAmount = function()
				{
					console.log("Test")
					var self = this;
					Promotion.changeAmount({action:'changePromotionAmount',id:this.id, amount:this.amount}, function(data){
						self.canPress = true;
						self.canUpdate = false;
					})
	
					console.log('id : ' + this.id + ', amount ' + this.amount)
				}
				seed++;
			}
			
			console.log(data)
		});

		$scope.pre_promotions = Promotion.findPre({item_id:$scope.unit.item_id, invoice_account:$scope.invoice_account}, function(data){
			seed = 1;
			for(var i = 0; i < data.length;i++)
			{
				promotion = data[i];
				promotion.promotion_type = 'preapprove';
				promotion.order =  seed;
				seed++;
			}
			console.log(data);
		})

		$scope.ax_promotions = Promotion.findAx({item_id:$scope.unit.item_id, invoice_account:$scope.invoice_account}, function(data){
			seed = 1;
			for(var i = 0; i < data.length;i++)
			{
				promotion = data[i];
				promotion.promotion_type = 'ax';
				promotion.order =  seed;
				if(promotion.issue == null)
					promotion.is_show = false;
				else
					promotion.is_show = true;

				seed++;
			}
			console.log(data);
		})
	})

	$scope.setPromotion = function(promotion)
	{
		console.log('before');
		console.log(promotion);
		promotion.is_select = !promotion.is_select;
		if(!promotion.is_select)
			promotion.issue = 0;
		promotion.is_select = Number(promotion.is_select)


		$scope.updatePromotionServer(promotion);
		console.log('after');
		console.log(promotion);
	}

	$scope.setIssue = function(promotion)
	{
		console.log('before');
		console.log(promotion);
		
		if(typeof(promotion.issue) == 'number')
			promotion.issue = !promotion.issue;
		else if(typeof(promotion.issue) ==  'string')
			promotion.issue = !Number(promotion.issue);
		else
		{
			console.log(typeof(promotion.issue))
			 promotion.issue = 1;
		}


		if(promotion.issue)
			promotion.is_select = 1;
		promotion.issue = Number(promotion.issue)
		$scope.updatePromotionServer(promotion);
		console.log('after');
		console.log(promotion);
	}


	$scope.updatePromotionServer = function(promotion)
	{
		promotion.invoice_account = $scope.invoice_account;
		if(promotion.promotion_type == 'preapprove')
			Promotion.updatePrePromotion({action:'updatePrePromotion', promotion:promotion}, function(data){
				console.log(data);
			});
		else if(promotion.promotion_type == 'tranfer')
			Promotion.updateTranferPromotion({action:'updateTranferPromotion', promotion:promotion}, function(data){
				console.log(data);
			});
		else if(promotion.promotion_type == 'ax')
			Promotion.updateAxPromotion({action:'updateAxPromotion', promotion:promotion}, function(data){
				console.log(data);
			});
	}





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
	var month = date.getMonth() +1
		if(month < 10)
			test += '0' +month;
		else
			test += month
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


function saveLocal(data)
{
	localStorage.lasted = Number(localStorage.lasted) + 1;
	localStorage.saveData = JSON.stringify(data);
}

function loadLocal()
{
	if(	localStorage.saveData)
		return JSON.parse(localStorage.saveData );
	else
	{
		localStorage.lasted = 0;
		return [];
	}
}