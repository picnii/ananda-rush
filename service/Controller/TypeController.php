<?php
	require_once('util.php');
	//use for preview what bills will be liked
	function actionRoomType()
	{
		
		return getRoomTypes();
		
	}

	function actionCompaniesList()
	{
		$companies_raw = getAllCompanies();
		$companies = array();
		foreach ($companies_raw as $company_raw) {
			# code...
			$company = new stdClass;
			$company->name = $company_raw['name'];
			$company->value = strtolower($company_raw['code']);
			array_push($companies, $company);
		}
		return $companies;
	}

	function actionBuildingsList()
	{

		$buildings = array();
		return $buildings;
	}

	function actionProjectsList()
	{

		return getProjectsList();
	}
	//testBill();
	//header('Content-Type: text/html; charset=utf-8');
	//actionBill(1);*/

	function getRoomTypes()
	{
		$types = array();
		$types[0] = new stdClass;
		$types[0]->name ="1 BED";
		$types[0]->value = 1;
		$types[1] = new stdClass;
		$types[1]->name ="2 BED";
		$types[1]->value = 2;
		return $types;
	}

	function getProjectsList()
	{
		$projects_raw = getAllProjects();
		$projects = array();
		foreach ($projects_raw as $project_raw) {
			# code...
			$project = new stdClass;
			$project->id = $project_raw['id'];
			$project->name = $project_raw['name'];
			$project->value = $project_raw['code'];
			array_push($projects, $project);
		}
		return $projects;
	}
?>