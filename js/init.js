app.run(function($rootScope) {
		  $rootScope.dataIndex = 0;

});


/*
	utils
*/
var CUSTOMER_INDEX = 2;
var BANK_INDEX = 1;
var COMPANY_INDEX = 0;

function getVariables(varset, varname)
{
	if(varset == undefined)
		return undefined;
	for(var i = 0; i < varset.length; i++)
	{
		var keys = Object.keys(varset[i]);
		var key = keys[0];
		if(key == varname)
		{
			return varset[i][key];
		}

	}
	return undefined;
}


function getVariablesValue(varset, varname)
{
	if(varname == undefined)
		return undefined;
	var myvar = getVariables(varset, varname);
	if(myvar == undefined)
		return undefined;
	return myvar.value;
}

function getFormulaValue(varset, formula)
{
	if(formula == "")
		return "";
	if(!isNaN(formula))
		return formula;
	if(typeof(formula) == 'undefined')
		return 0;
	if(formula[0] == "#")
		return formula.substr(1);
	var express = /{[\w\d]*}/g;
	if(express != undefined && formula != undefined)
		var raw_varset = formula.match(express);
	else
		var raw_varset = null;
	if(raw_varset == null)
		raw_varset = [];
	var replace_set = [];
	var run_formula = formula;
	for(var i = 0; i < raw_varset.length ;i++)
	{
		var raw_var_name = raw_varset[i];
		var exp = /[\w\d]+/g;
		var var_name = raw_var_name.match(exp)[0];
		replace_set[i] = getVariablesValue(varset, var_name);
		if(replace_set[i] == '?')
			return "";
		run_formula  = run_formula.replace(raw_var_name, replace_set[i]);
	//	console.log(run_formula);
	}
	//console.log(run_formula);
	try {
    	return eval(run_formula); 
	} catch (e) {
	    if (e instanceof SyntaxError) {

	      //  console.log(e.message);
	        //console.log(run_formula);
	    }
	}
	
}

function getPaymentByPaymentId(id, payments, varset)
{
	for(var i =0; i < payments.length; i++)
	{
		if(payments[i].id == id)
		{
			var formulas = payments[i].formulas;

			var answer = [];
			for(var j =0; j < formulas.length; j++)
			{
				var formula = formulas[j];
				answer[j] = getFormulaValue(varset, formula);
			}
			
			return answer;
		}
	}
	return null;
}


function getPostData()
{
	var json_str = $('#json-post-data').html();
	return JSON.parse(json_str);
}

function getGetData()
{
	var json_str = $('#json-get-data').html();
	return JSON.parse(json_str);
}

Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
    var n = this,
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSeparator = decSeparator == undefined ? "." : decSeparator,
    thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
    sign = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};

function saveTempData(data)
{
	var json = JSON.stringify(data);
	$('#json-controller-data').html(json);
	return json;
}

function loadTempData()
{
	var json = $('#json-controller-data').html();
	return JSON.parse(json);
}

function convertDateTimeToSqlFormat(datetime)
{
	var year = datetime.getYear() + 1900;
	var month = datetime.getMonth();
	month++;
	if(month < 10)
		month = "0" + month;
	var date = datetime.getDate();
	if(date < 10)
		date = "0" + date;
	return year + "-" + month + "-" + date;
}

$(document).ready(function(){
	



})

/*
*
*   findStuffIn(value,people)->withAttribute()
*   findStuffIn(payment_code,payments)->withAttribute('code');
*/

Array.prototype.findById = function(value)
{
	for(var i =0 ;i < this.length; i++)
	{
		
		if(typeof(this[i].id) != 'undefined' && this[i].id == value)
			return  this[i];
		else if(typeof(this[i].id) == 'undefined')
			break;
	}
	return null;
}

Array.prototype.find =  function(condition)
{
	for(var i =0 ;i < this.length; i++)
	{
		
		var isPassCondition = true;
		for(var index in condition) { 
			//console.log('check:'+index);
			//console.log(this[i][index]);
			//console.log(condition[index])
			if(this[i][index] != condition[index])
			{
				isPassCondition = false;
				break;
			}
		}
		if(isPassCondition)
		{

			return this[i];
			break;
		}
	}
	return null;
}

/*
*   if(isVar($scope.search).defined)
*   
*/

function isVar(variable)
{
	var type = typeof(variable);
	var item = {}
	item.defined = (type != 'undefined');
	item.number = (type == 'number');
	item.string = (type == 'string');
	item.canCalculate = !isNaN(variable);
	item.array = (Object.prototype.toString.call( variable ) === '[object Array]')
	item.object = (type == 'object')
	return item;
}

/*
*
*
*/
Date.prototype.toSqlDate = function()
{
	var year = this.getYear() + 1900;
	var month = this.getMonth();
	month++;
	if(month < 10)
		month = "0" + month;
	var date = this.getDate();
	if(date < 10)
		date = "0" + date;
	return year + "-" +month+"-"+date;
}

/*
*
*
*/

Date.prototype.convertToSqlDate = function()
{
	var year = this.getYear() + 1900;
	var month = this.getMonth();
	month++;
	if(month < 10)
		month = "0" + month;
	var date = this.getDate();
	if(date < 10)
		date = "0" + date;
	return year + "-" +month+"-"+date;
}


		