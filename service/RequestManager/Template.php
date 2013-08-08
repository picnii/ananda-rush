<?php
$templateActions = array('templates', 'template', 'updateTemplate');
/* Template Service */
	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $templateActions))
			require_once 'Controller/TemplateController.php';
		if($action == 'templates')
			$response = actionTemplates();
		if($action == 'template')
			$response = actionTemplate($_GET['template_id']);

	}

	if(isset($_POST['action']))
	{
		$action = $_POST['action'];
		if(gotAction($action, $templateActions))
			require_once 'Controller/TemplateController.php';
		if($action == 'updateTemplate')
			$response = actionUpdateTemplate($_POST['template_id'], $_POST['args']);
	}

?>