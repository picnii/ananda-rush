<?php


function testCon()
{
	echo "testCon";
}

function getParamsFromSearchQuery($q)
{
	$arr = explode(".", $q);
	$answer = array();
	for($i=0 ;$i < count($arr); $i++)
	{
		$split_str = explode("=", $arr[$i]);
		$key = $split_str[0];
		$value = $split_str[1];
		$answer[$key] = $value;
	}
	return $answer;
}

function getWhereClauseFromQuery($q)
{
	if($q == "*")
		return "";
	$params = getParamsFromSearchQuery($q);
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


foreach (glob("Model/*.php") as $filename)
{
    include $filename;
}/*
include "Model/Transaction.php";
include "Model/Template.php";
include "Model/Payment.php";
include "Model/Payment.php";*/


?>