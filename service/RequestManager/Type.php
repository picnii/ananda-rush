<?php

    $typeActions = array('type');

	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		$type = $_GET['type'];
		if(gotAction($action, $typeActions))
			require_once 'Controller/TypeController.php';
		if($action == 'type' && $type == 'room')
			$response = actionRoomType();
		else if($action == 'type' && $type =='projects')
			$response = actionProjectsList();
	
	}

?>