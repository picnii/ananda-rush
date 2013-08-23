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
		run_formula  = run_formula.replace(raw_var_name, replace_set[i]);
	//	console.log(run_formula);
	}
	//console.log(run_formula);

	return eval(run_formula);
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

$(document).ready(function(){
	



})

		