<?php 
	 include('system/connect.inc.php');
	 $SQL  = "select * from preapprove_bank ";
	$result = DB_query($connect,$SQL);
    $row = DB_fetch_array($result);
	 echo $row;
?>