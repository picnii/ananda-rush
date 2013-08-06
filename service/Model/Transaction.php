<?php
/*
* $args = array(
	"unit_ids" => array(),
	"template_id"=> 2
  )
*/
$id = 22;
function createTransaction($unit_id, $template_id)
{
	$SQL  = "INSERT INTO tranfer_transaction(unit_payment_id,template_id)  VALUES ('$unit_id', '$template_id'); SELECT SCOPE_IDENTITY()";
     $result = DB_query($connect,$SQL);
    if($result){
        sqlsrv_next_result($result); 
        sqlsrv_fetch($result); 
        $created_id = sqlsrv_get_field($result, 0); 
        return $created_id;
    }else{
        return false;
    }
}

function findTransactionById($id)
{
     $SQL  = "select * from tranfer_transaction where id = $id";
	 $result = DB_query($connect,$SQL);
	 $row = DB_fetch_array($result);
     if($row > 0){
        return array(
            'id'=>$row["id"],
            'unit_payment_id'=>$row["unit_payment_id"],
            'template_id'=>$row["template_id"],
            'create_time'=>$row["create_time"]
        );
    }else{
       return false;
    }
}

/*
* SELECT ONLY 1 row
*
*/
function findTransaction($q)
{

	$sql = "SELECT * FROM tranfer_transaction WHERE where id = $q";
    $result = DB_query($connect,$SQL);
	 $row = DB_fetch_array($result);
	if($row > 0){
        return array(
            'id'=>$row[0]["id"],
            'unit_payment_id'=>$row[0]["unit_payment_id"],
            'template_id'=>$row[0]["template_id"],
            'create_time'=>$row[0]["create_time"],
            'unit_payment_unit_id'=>$row[0]["unit_payment_unit_id"]
        );
    }else{
       return false;
    }
}

/*
*
*/
function findAllTransaction($q)
{
	//if none $q return all 
	//if haave $q add where
}

function updateTransaction($transaction_id, $args)
{
	//update transaction onl
	/*example $args = array(
		'create_time'=>'2010-12-20'
	)
	update only change field
	*/
	return array(
		'id'=>20,
		'unit_payment_id'=>30,
		'template_id'=>1,
		'create_time'=>'2009-12-20',
		'unit_payment_unit_id'=>30
	);
}

function deleteTransactionById($transaction_id)
{

	return true;
}

function deleteTransaction($q)
{
	return true;
}

?>