<?php
	include 'system/allfunction.php';
	//json_decode(file_get_contents('php://input'))
	$response = array("Error"=> "none service exists", "post"=>$_POST, "get"=>$_GET, "reqBody"=>json_decode(file_get_contents('php://input')));
	$reqBody =json_decode(file_get_contents('php://input'));
	$isReport = false;
	
	
	/* util */
	function gotAction($action, $actions)
	{
		for($i =0; $i< count($actions); $i++)
		{
			if($action == $actions[$i])
				return true;
		}
		return false;
	}
	foreach (glob("RequestManager/*.php") as $filename)
	{
		if($filename != "RequestManager/Testing.php")
	   	 include $filename;
	   	else if($_GET['force'] == "1")
	   	  include $filename;
	}/*
	include 'RequestManager/Bill.php';
	include 'RequestManager/Template.php';
	include 'RequestManager/Payment.php';
	include 'RequestManager/Type.php';
	include 'RequestManager/Unit.php';*/
	if(!$isReport)
	{
		$response = json_encode($response);
		echo $response;
	}else
	{

		header('Content-type: application/msexcel; charset=utf-8');

		// It will be called downloaded.pdf
		header('Content-Disposition: attachment; filename="report.xls"');

		echo $response_header;

		echo $response;

		echo $response_footer;

	}
	
?>