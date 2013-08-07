<?php

    $typeActions = array('type');

	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $typeActions))
			include 'Controller/TypeController.php';
		if($action == 'type')
			$response = actionRoomType($_GET['type']);
		
	
	}

?>