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

	$SQL = "SELECT * FROM tranfer_transaction WHERE where id = $q";
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
*
*/
function findAllTransaction($q)
{
	$SQL = "SELECT * FROM tranfer_transaction WHERE where id = $q";
    $result = DB_query($connect,$SQL);
	 $row = DB_fetch_array($result);
	if($row > 0){
        return $row;
    }else{
       return false;
    }
}

function updateTransaction($transaction_id, $args)
{
	//update transaction onl
	/*example $args = array(
		'create_time'=>'2010-12-20'
	)
	update only change field
	*/
            if($transaction_id != ""){
                $sql ="UPDATE tranfer_transaction SET ";
            }if($args['unit_payment_id'] != ""){
                $sql.="unit_payment_id='".$args['unit_payment_id']."', ";
            }if($args['template_id'] != ""){
                $sql.="template_id='".$args['template_id']."' ";
            }
                $sql.="WHERE id='".$transaction_id."' ";
                $rs = DB_query($connect,$sql); 
            if($rs >0){
                $SQL  = "SELECT * from tranfer_transaction where id = $transaction_id ";
                $result = DB_query($connect,$SQL);
                $row = DB_fetch_array($result);
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

function findAllLastTransaction($unit_ids)
{
    //find all Lastest transaction from unit_id
    $SQL = "SELECT * FROM tranfer_transaction WHERE where unit_payment_id = $unit_ids order by crate_time ";
    $result = DB_query($connect,$SQL);
	 $row = DB_fetch_array($result);
	if($row > 0){
        return array(
            array(
                'id'=>$row["id"],
                'unit_payment_id'=>$row["unit_payment_id"],
                'template_id'=>$row["template_id"],
                'create_time'=>$row["create_time"]
            ),
            array(
                'id'=>$row["id"],
                'unit_payment_id'=>$row["unit_payment_id"],
                'template_id'=>$row["template_id"],
                'create_time'=>$row["create_time"]
            ),
            array(
                'id'=>$row["id"],
                'unit_payment_id'=>$row["unit_payment_id"],
                'template_id'=>$row["template_id"],
                'create_time'=>$row["create_time"]
            )

        );
    }else{
       return false;
    }
}

function deleteTransactionById($transaction_id)
{
    $SQL  = " DELETE FROM tranfer_transaction WHERE id = $transaction_id ";
    $result = DB_query($connect,$SQL);
	return true;
}

function deleteTransaction($q)
{
	return true;
}

//find lastes transaction in bill form
function findBillByUnitId($unit_id)
{
    return array(
        "transaction_id"=>20,
        "unit_id"=>$unit_id,
        "template_id"=>50,
        "payments"=>array(
            array(
                "name"=>"payment1",
                "description"=>"desc1",
                "formulas"=>array(
                    "",
                    "",
                    "{bankLoan}*2"
                ),
            )
        ),
        "variables"=>array(
            array(
                array("name"=>"bankLoan", "value"=>5000),
                array("name"=>"bankSide", "value"=>10000)
            )
        )
    );
}

function findBillByTransactionId($transaction_id)
{

    return array(
        "transaction_id"=>$transaction_id,
        "unit_id"=>50,
        "template_id"=>50,
        "payments"=>array(
            array(
                "name"=>"payment1",
                "description"=>"desc1",
                "formulas"=>array(
                    "",
                    "",
                    "{bankLoan}*2"
                ),
            )
        ),
        "variables"=>array(
            array(
                array("name"=>"bankLoan", "value"=>5000),
                array("name"=>"bankSide", "value"=>10000)
            )
        )
    );
}

function findAllBill($q)
{

}

?>