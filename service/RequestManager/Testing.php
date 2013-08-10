<?php
include "util.php";
$testResult = array();
function assertEquals($expect, $value)
{
//	echo "assertEquals";
	if($expect == $value)
	{
		echo "P";
	}else
	{
		echo "F(";
		$fail = new stdClass;
		$fail->result = "F";
		$fail->expect = $expect;
		$fail->value = $value;
		print_r($fail);
		echo ")";
	}
	
}

function assertContains($expect, $container)
{
	for($i = 0; $i < count($container); $i++)
		if($expect == $container[$i])
		{
			echo "P";
			return true;
		}
	echo "F(";
	$fail = new stdClass;
	$fail->result = "F";
	$fail->expect = $expect;
	$fail->value = $container;
	print_r($fail);
	echo ")";
	return false;
}

if(isset($_GET['action']))
{
	$testName =  substr($_GET['action'], 4 );
	$evalStr = "include 'test/{$testName}.php';";
	eval($evalStr);
}
/*
if($_GET['action'] == 'testVariable')
{
	include "test/Variable.php";
}*/

$response = $testResult;
?>