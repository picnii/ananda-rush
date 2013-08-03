<?php
	if(isset($_GET))
		$jsonGetData = json_encode($_GET);
	else
		$jsonGetData = json_encode(array());
	if(isset($_POST))
		$jsonPostData = json_encode($_POST);
	else
		$jsonPostData = json_encode(array());

	
?>