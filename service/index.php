<?php
	$response = array("Error"=> "none service exists", "post"=>$_POST, "get"=>$_GET);
	

	
	$templateActions = array('templates', 'template');

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
	include 'RequestManager/Bill.php';
	include 'RequestManager/Template.php';
	include 'RequestManager/Payment.php';
	include 'RequestManager/Type.php';

	$response = json_encode($response);
	echo $response;
?>