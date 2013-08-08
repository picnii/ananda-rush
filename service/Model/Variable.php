<?php
	$FIX_TYPE = 0; $PROJECT_TYPE = 2; $UNIT_TYPE = 3;

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

	function craeteVariable($name, $codename, $description, $type, $value)
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

?>