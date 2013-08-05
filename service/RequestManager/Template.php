<?php
/* Template Service */
	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $templateActions))
			include 'Controller/TemplateController.php';
		if($action == 'templates')
			$response = actionTemplates();
		if($action == 'template')
			$response = actionTemplate($_GET['template_id']);

	}

?>