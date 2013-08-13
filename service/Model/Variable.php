<?php
	$FIX_TYPE = 0; $PROJECT_TYPE = 1; $UNIT_TYPE = 2;
	$PROJECT_ATTRIBUTE = 3; $UNIT_ATTRIBUTE = 4;

	function findVariableById($id)
	{
             $SQL  = "select * from tranfer_variable where id = $id";
             //echo $SQL;
             $result = DB_query($GLOBALS['connect'],$SQL);
             $row = DB_fetch_array($result);
             if($row > 0){
                return array(
                    'id'=>$row["id"],
                    'codename'=>$row["codename"],
                    'name'=>$row["name"],
                    'value'=>$row["value"],
                    'type'=>$row["variable_type_id"]
                );
            }else{
               return false;
            }
	}

	function findAllVariables($type = null)
	{
		if($type == null)
			$SQL = "SELECT * FROM tranfer_variable";
		else
			$SQL = "SELECT * FROM tranfer_variable WHERE variable_type_id = {$type}";
		$result = DB_query($GLOBALS['connect'],$SQL);
		$variables = array();
		while($row = DB_fetch_array($result))
		{
			array_push($variables, $row);
		}
		return $variables;
	}

	function findVariableByCodename($codename)
	{
		 $SQL  = "select * from tranfer_variable where codename = '$codename'";
		
		  $result = DB_query($GLOBALS['connect'],$SQL);
             $row = DB_fetch_array($result);
             if($row > 0){
                return array(
                    'id'=>$row["id"],
                    'codename'=>$row["codename"],
                    'name'=>$row["name"],
                    'value'=>$row["value"],
                    'type'=>$row["variable_type_id"]
                );
            }else{
               return false;
            }
	}

	function createVariable($name, $codename, $description, $type, $value)
	{
		$FIX_TYPE = 0; $PROJECT_TYPE = 1; $UNIT_TYPE = 2;
		$PROJECT_ATTRIBUTE = 3; $UNIT_ATTRIBUTE = 4;
		//if($type == $FIX_TYPE)
		return createFixVariable($name, $codename, $description, $value, $type);
		/*else if($type == $PROJECT_TYPE)
		{
			return createDefaultProjectVariables($name, $codename, $description, $value);
		}else if($type == $UNIT_TYPE)
		{
			return createDefaultUnitVariables($name, $codename, $description, $value);
		}*/
	}

	function updateVariable($id, $type, $args)
	{
		return updateFixVariable($id, $args);
	}

	function deleteVariable($id, $type)
	{
		return deleteFixVariable($id);
	}

	function createFixVariable($name, $codename, $description, $value, $type)
	{
		$SQL = "INSERT INTO tranfer_variable(codename,name, variable_type_id ,value) VALUES ('{$codename}', '{$name}', {$type} ,'{$value}'); SELECT SCOPE_IDENTITY()";
		//echo $SQL."<br/>";
		$result = DB_query($GLOBALS['connect'],$SQL);
		$row = DB_fetch_array($result);
		if($result)
		{
			sqlsrv_next_result($result); 
            sqlsrv_fetch($result); 
            return sqlsrv_get_field($result, 0);
		}
		else
			return false;
	}

	function updateFixVariable($id, $args)
	{
		$SQL = "UPDATE tranfer_variable SET ";
		$isFirst = true;
		foreach ($args as $key => $value)
    	{
    		if($isFirst)
    			$isFirst = false;
    		else
    			$SQL = $SQL.", ";
    		$SQL = $SQL.$key." = ";
    		if(is_numeric($value))
    			$SQL = $SQL.$value;
    		else
    			$SQL = $SQL."'{$value}'";
    	}

		$SQL = $SQL."  WHERE id = {$id}";
		$result = DB_query($GLOBALS['connect'],$SQL);
		$row = DB_fetch_array($result);
		if($result)
			return true;
		else
			return false;
	}

	function deleteFixVariable($id)
	{
		$SQL = "DELETE FROM tranfer_variable WHERE id = {$id}";
		$result = DB_query($GLOBALS['connect'],$SQL);
		$row = DB_fetch_array($result);
		if($result)
			return true;
		else
			return false;
	}

	/*function createDefaultProjectVariables($name, $codename, $description, $value)
	{
        $SQL  = "INSERT INTO tranfer_variable(codename,name,description,value, variable_type_id)  VALUES ('$codename', '$name','$description','$value', 1) ;
         SELECT SCOPE_IDENTITY()";
        $result = DB_query($GLOBALS['connect'],$SQL);
        if($result){
	        sqlsrv_next_result($result); 
	        sqlsrv_fetch($result); 
	        $create_id = sqlsrv_get_field($result, 0);
	        $projects = getAllProjects();
	        foreach ($projects as $project) {
	        	$sql = "INSERT INTO tranfer_variable_project (variable_id, project_id, value) VALUES({$create_id}, {$project->id}, '$value');";
	        	DB_query($GLOBALS['connect'],$sql);
	        }
	       	return $create_id;
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

	function updateUnitVariable($unit_id, $args)
	{
		

	}*/

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