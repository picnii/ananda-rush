<?php
	$FIX_TYPE = 0; $PROJECT_TYPE = 1; $UNIT_TYPE = 2;
	$PROJECT_ATTRIBUTE = 3; $UNIT_ATTRIBUTE = 4;

	function findVariableById($id, $args)
	{
             $SQL  = "select * from tranfer_variable where id = $id";
             $result = DB_query($GLOBALS['connect'],$SQL);
             $row = DB_fetch_array($result);
             if($row > 0){
                return array(
                    'id'=>$row["id"],
                    'codename'=>$row["codename"],
                    'name'=>$row["name"],
                    'value'=>$row["value"],
                    'variable_type_id'=>$row["variable_type_id"]
                );
            }else{
               return false;
            }
	}

	function createVariable($name, $codename, $description, $type, $value)
	{
		if($type == $FIX_TYPE)
			return createFixVariable($name, $codename, $description, $value);
		else if($type == $PROJECT_TYPE)
		{
			return createDefaultProjectVariables($name, $codename, $description, $value);
		}else if($type == $UNIT_TYPE)
		{
			return createDefaultUnitVariables($name, $codename, $description, $value);
		}
	}

	function createFixVariable($name, $codename, $description, $value)
	{

	}

	function updateFixVariable($id, $name, $codename, $description, $value)
	{

	}

	function createDefaultProjectVariables($name, $codename, $description, $value)
	{
        $SQL  = "INSERT INTO tranfer_variable(codename,name,description,value)  VALUES ('$codename', '$name',$'description','$value')";
        $result = DB_query($GLOBALS['connect'],$SQL);
        if($result){
	        sqlsrv_next_result($result); 
	        sqlsrv_fetch($result); 
	        $variable_id = sqlsrv_get_field($result, 0); 
	        $SQL  = "select * from tranfer_variable where id = $variable_id";
	        $result = DB_query($GLOBALS['connect'],$SQL);
	        $row = DB_fetch_array($result);
	        return $row;
        }else{
            return false;
        }
	}

	function updateProjectVariable($project_id, $name, $codename, $description,  $value)
	{

	}

	function createDefaultUnitVariables($name, $codename, $description, $value)
	{
        $SQL  = "INSERT INTO tranfer_variable(codename,name,description,value)  VALUES ('$codename', '$name',$'description','$value')";
         $result = DB_query($GLOBALS['connect'],$SQL);
        if($result){
	        sqlsrv_next_result($result); 
	        sqlsrv_fetch($result); 
	        $variable_id = sqlsrv_get_field($result, 0); 
	        $SQL  = "select * from tranfer_variable where id = $variable_id";
	        $result = DB_query($GLOBALS['connect'],$SQL);
	        $row = DB_fetch_array($result);
	        return $row;
        }else{
            return false;
        }
	}

	function updateUnitVariable($unit_id, $name, $codename, $description,  $value)
	{

	}

	function findVariablesByTemplateId($template_id)
	{
		return array(
			array(
				"id"=>5,
				"name"=>10
			)
		);
	}

	function getVariablesType()
	{
		$types = array();
		$fix_types = array("fix", "fix per project", "fix per unit", "project attribute", "unit attribute");

		for($i = 0; $i < count($fix_types); $i++)
		{
			$types[$i] = new stdClass;
			$types[$i]->value = $i;
			$types[$i]->name = $fix_types[$i];
		}

		return $types;
	}

	function getSampleVariable()
	{
		$variable = new stdClass;
		$variable->id = rand();
		$variable->name = "Bank Loan";
		$variable->codename ="bankLoan";
		$variable->type = 1;
		$variable->value = 5000;
		return $variable;
	}

?>