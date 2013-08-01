app.run(function($rootScope) {
		  $rootScope.dataIndex = 0;

});

/*
	utils
*/

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

	var express = /{[\w\d]*}/g;
	var raw_varset = formula.match(express);
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

		