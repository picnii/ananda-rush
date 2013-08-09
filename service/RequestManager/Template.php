<?php
$templateActions = array('templates', 'template', 'updateTemplate', 'createTemplate', 'deleteTemplate', 'deleteTemplatePayment', 'createTemplatePayment');
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

	if(isset($reqBody->action))
	{

		$action = $reqBody->action ;
		if(gotAction($action, $templateActions))
			require_once 'Controller/TemplateController.php';
		switch ($reqBody->action) {
			case 'createTemplate':
				# code...
				$response = actionCreateTemplate($reqBody);
				break;

			case 'updateTemplate':
				# code...
				$response = actionUpdateTemplate($reqBody);
				break;

			case 'deleteTemplate':
				# code...
				$response = actionDeleteTemplate($reqBody);
				break;

			case 'deleteTemplatePayment':
				# code...
				$response = actionDeleteTemplatePayment($reqBody);
				break;

			case 'createTemplatePayment':
				# code...
				$response = actionCreateTemplatePayment($reqBody);
				break;
			
			default:
				# code...
				break;
		}
		

	}

?>