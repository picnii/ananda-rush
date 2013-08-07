<?php
	require_once('util.php');
	//use for preview what bills will be liked
	function actionRoomType($type)
	{
		if($type == "room")
		{
			return getRoomTypes();
		}
	}
	//testBill();
	//header('Content-Type: text/html; charset=utf-8');
	//actionBill(1);*/

	function getRoomTypes()
	{
		return array(
			0 => "1 BED",
			1 => "2 BED"
		);
	}
?>