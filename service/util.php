<?php


function testCon()
{
	echo "testCon";
}

function getParamsFromSearchQuery($q, $prefix='', $exceptions = null)
{
	$arr = explode(".", $q);
	$answer = array();
	for($i=0 ;$i < count($arr); $i++)
	{
		$split_str = explode("=", $arr[$i]);
		$key = $split_str[0];
		$except = _checkParamsInException($key, $exceptions);
		if($except)
			$key = $except.'.'.$key;
		else if($prefix !='')
			$key = $prefix.'.'.$key;

		$value = $split_str[1];
		$answer[$key] = $value;
	}
	return $answer;
}

function _checkParamsInException($key, $exceptions)
{
	/*$exceptions = array(
		'SalesName' => 'Sale_Tra'
	
	)


	*/
	foreach ($exceptions as $except_key => $value) {
		# code...
		if($except_key == $key)
		{
			return $value;
		} 
	}
	return false;
}

function getWhereClauseFromQuery($q)
{
	if($q == "*")
		return "";
	//echo $q;
	$params = getParamsFromSearchQuery($q);
	//print_r($params);
	$sql = "WHERE ";
	$isFirst = true;
	foreach ($params as $key => $value)
    {
    	if($isFirst)
    		$isFirst = false;
    	else
    		$sql = $sql." AND ";
    	$sql = $sql." {$key} = {$value}";
    }
	return $sql;
}

function getWhereClauseFromParams($params, $oparators = null)
{
	$sql = "WHERE ";
	$isFirst = true;
	foreach ($params as $key => $value)
    {
    	if($isFirst)
    		$isFirst = false;
    	else
    		$sql = $sql." AND ";

    	//if(is_numeric($value))
    	//	$sql = $sql." {$key} = {$value}";
    	//else
    	if(isset($oparators) && isset($oparators[$key]))
    	{
    		if($oparators[$key] == 'LIKE')
    			$sql = $sql." {$key} {$oparators[$key]} '%{$value}%'";
    		else if($oparators[$key] == 'BETWEEN') {
    			$values = explode("|", $value);
    			$from = 0;
    			$to = 10000;
    			if($values[0])
    				$from = $values[0];
    			if($values[1])
    				$to = $values[1];
				$sql = $sql." {$key} {$oparators[$key]} {$from} AND {$to}";
				print_r($sql);
    		}
    		else
    			$sql = $sql." {$key} {$oparators[$key]} '{$value}'";
    	}else
    		$sql = $sql." {$key} = '{$value}'"; 
    }
	return $sql;
}

function getIdClauseFromParams($ids, $id_key="id")
{
	$sql = "";
	for($i =0; $i < count($ids); $i++)
	{
		if($i != 0)
			$sql .= "OR ";

		$sql .= "{$id_key} = {$ids[$i]} ";
	}
	return $sql;
}


foreach (glob("Model/*.php") as $filename)
{
    include $filename;
}/*
include "Model/Transaction.php";
include "Model/Template.php";
include "Model/Payment.php";
include "Model/Payment.php";*/

function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}


?>