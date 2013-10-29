<?php

$connectType = connectType();



$hostname = '58.97.74.26';
$username = 'ASFOLDER_TMS';
$password = 'zaq1XSW@cde3VFR$';
$database = 'TMS';


/*
086 - 382 - 1894
$hostname = '58.97.74.26';
$username = 'ASFOLDER_TMS';
$password = 'zaq1XSW@cde3VFR$';
$database = 'TMSUAT';

$hostname = '203.150.224.118';
$username = 'ASFOLDER_TMS';
$password = 'qaz789wsx';
$database = 'TMS';

$hostname = '203.150.224.118';
$username = 'ASFOLDER_TMS';
$password = 'qaz789wsx';
$database = 'TMSUAT';
*/
/*
$hostname = 'idev.asfolder.co.th';
$username = 'sa';
$password = '@sf0lder';
$database = 'TMS_ananda';
*/

$connect = DB_connect($hostname,$username,$password,$database);

if(!$connect)
{
	
	echo "Error<br>";
	
} 

function connectType()
{
	
	//return 'sqlsrv';
	//return 'mysql';
	return 'mssql';

}

function DB_connect($hostname_l,$username_l,$password_l,$database_l)
{
	$connectType = connectType();
	
	if($connectType == 'mssql')
	{
		
		$conn = mssql_connect($hostname_l,$username_l,$password_l);
		$selectDB = mssql_select_db($database_l,$conn);
		
	} elseif($connectType == 'sqlsrv') {
		
		$conparam = array( "UID"=>$username_l,"PWD"=>$password_l,"Database"=>$database_l);
		$conn = sqlsrv_connect($hostname_l,$conparam);
		
	} elseif($connectType == 'mysql') {
		
		$conn = mysql_connect($hostname_l,$username_l,$password_l);
		$selectDB = mysql_select_db($database_l,$conn);
		
		mysql_query("SET character_set_results=utf8");
		mysql_query("SET character_set_client=utf8");
		mysql_query("SET character_set_connection=utf8");
		
	}
	
	return $conn;
}

function DB_query($connect,$sql){
	
	$connectType = connectType();
	
	if($connectType == 'mssql'){
		
 		try
		{
			$result = mssql_query($sql);
			
		}
		catch (Exception $e)
		{
			//echo $e->getMessage(); // if you wanna know the error message
			return $e->getMessage();
		} 
		return $result;
		
		
	} elseif($connectType == 'sqlsrv'){
		
		$result = sqlsrv_query($connect,$sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	
	} elseif($connectType == 'mysql') {
		
		$result = mysql_query($sql);
		
	}
	
	return $result;

}

function DB_query2($connect,$sql){
	
	$connectType = connectType();
	
	if($connectType == 'sqlsrv'){
		$result = sqlsrv_query($connect,$sql,array(), array());
	} 
	
	return $result;

}
function DB_fetch_array($result){
	
	$connectType = connectType();
	
	if($connectType == 'mssql'){
		
		$row = mssql_fetch_array($result,MSSQL_BOTH);
		
	} elseif($connectType == 'sqlsrv'){
		
		$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		
	} elseif($connectType == 'mysql') {
		
		$row = mysql_fetch_array($result);
		
	}
	
	return $row;

}

function DB_num_rows($result)
{
	
	$connectType = connectType();
	
	if($connectType == 'mssql'){
		
		$numrow = mssql_num_rows($result);
		
	} elseif($connectType == 'sqlsrv'){
		
		$numrow = sqlsrv_num_rows($result);
		
	} elseif($connectType == 'mysql') {
		
		$numrow = mysql_numrows($result);
		
	}
	
	return $numrow;

}

function DB_real_string($data){
		
	$connectType = connectType();
		
	if($connectType == 'mysql'){
		
		$data =  mysql_real_escape_string($data);
		
	} elseif($connectType == 'sqlsrv') {
        
		$data = sqlsrv_real_escape_string($data);
		
	}
	
	return $data;

}

function DB_close($connect)
{
	
	$connectType = connectType();
	
	if($connectType == 'mssql'){
		
		$close = mssql_close($connect);
		
	} elseif($connectType == 'sqlsrv'){
		
		$close = sqlsrv_close($connect);
		
	} elseif($connectType == 'mysql') {		
		$close = mysql_close($connect);
	}
	
	return $close;

}

?>