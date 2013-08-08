<?php
function getPayments()
{
    $SQL  = "select * from tranfer_payment";
    $result = DB_query($GLOBALS['connect'],$SQL);
    $row = DB_fetch_array($result);
	$payments =  array();
	for($i =0; $i< 10; $i++)
	{
		$payments[$i] = new stdClass;
		$payments[$i]->id = $i;
		$payments[$i]->name = "ค่าห้องชุดส่วนที่ต้องชำระ {$i}";
		$payments[$i]->description = "*อาจมีเพิ่ม/ลดตามพื้นที่จริง";
		$payments[$i]->formulas = array(
				"",
				"250",
				"{priceOnContact} - {paidAmount}"
			);
	}
	return $payments;

}

function getAllPayments()
{
    return array(
        getSamplePayments(),
         getSamplePayments(),
          getSamplePayments()

    );
}

function createPayment($name, $description, $formulas)
{   
    $formula1 = $formulas[0];
    $formula2= $formulas[1];
     $formula3 = $formulas[2];
    
    $SQL  = "INSERT INTO tranfer_template(name,description,formula1,formula2,formula3,is_show1,is_show2,is_show3)  VALUES ('$name', '$description','$formula1','$formula2','$formula3'); SELECT SCOPE_IDENTITY()";
     $result = DB_query($GLOBALS['connect'],$SQL);
    if($result){
            sqlsrv_next_result($result); 
            sqlsrv_fetch($result); 
            $payment_id = sqlsrv_get_field($result, 0); 
            return $payment_id;
    }else{
            return false;
    }
	
}

function updatePayment($payment_id, $args)
{
            if($payment_id != ""){
            $sql ="UPDATE tranfer_payment SET ";
            }if($args['name'] != ""){
                $sql.="name='".$args['name']."', ";
            }if($args['description'] != ""){
                $sql.="template_id='".$args['template_id']."', ";
            }if($args['formula1'] != ""){
                $sql.="formula1='".$args['formula1']."', ";
            }if($args['formula2'] != ""){
                $sql.="formula2='".$args['formula2']."', ";
            }if($args['formula3'] != ""){
                $sql.="formula3='".$args['formula3']."', ";
            }if($args['is_show1'] != ""){
                $sql.="is_show1='".$args['is_show1']."', ";
            }if($args['is_show2'] != ""){
                $sql.="is_show2='".$args['is_show2']."', ";
            }
                $sql.="is_show3='".$args['is_show3']."' ";
                $sql.="WHERE id='".$payment_id."' ";
                $rs = DB_query($GLOBALS['connect'],$sql); 
            if($rs >0){
                $SQL  = "SELECT * from tranfer_payment where id = $payment_id ";
                $result = DB_query($GLOBALS['connect'],$SQL);
                $row = DB_fetch_array($result);
                 return $payment_id;
                /* return array(
                    'id'=>$row["id"],
                    'unit_payment_id'=>$row["unit_payment_id"],
                    'template_id'=>$row["template_id"],
                    'create_time'=>$row["create_time"]
                ); */
            }else{
                return false;
            }
   
}

function deletePayment($payment_id)
{
	    $SQL  = " DELETE FROM tranfer_payment WHERE id = $payment_id ";
        $result = DB_query($GLOBALS['connect'],$SQL);
		return true;
}


function getPaymentsByTemplateId($template_id)
{
    $SQL  = "select *,* from tranfer_payment INNER JOIN ";
    $SQL .="tranfer_template_payment on tranfer_template_payment.payment_id = tranfer_payment.id where tranfer_template_payment.template_id = $template_id";
	$result = DB_query($GLOBALS['connect'],$SQL);
	$row = DB_fetch_array($result);
    if($row >0){
    	$payments =  array();

    	$payments[0] = new stdClass;
    	$payments[0]->order = 1;
    	$payments[0]->name = "ค่าห้องชุดส่วนที่ต้องชำระ";
    	$payments[0]->description = "*อาจมีเพิ่ม/ลดตามพื้นที่จริง";
    	$payments[0]->formulas = array(
    			"",
    			"",
    			"{priceOnContact} - {paidAmount}"
    		);
    	$payments[1] = new stdClass;
    	$payments[1]->order = 1;
    	$payments[1]->name = "ค่าห้องชุดส่วนที่ต้องชำระ";
    	$payments[1]->description = "*อาจมีเพิ่ม/ลดตามพื้นที่จริง";
    	$payments[1]->formulas = array(
    			"",
    			"",
    			"{priceOnContact} - {paidAmount}"
    		);
    	return $payments;
    }else{
        return false;
    }
}

function getSamplePayments($count)
    {
        $payments =  array();
        for($i = 0; $i < $count ;$i++)
        {
            $payments[$i] = new stdClass;
            $payments[$i]->order = ($i + 1);
            $payments[$i]->id = rand();
            $payments[$i]->name = "Payment".rand();
            $payments[$i]->description = "*อาจมีเพิ่ม/ลดตามพื้นที่จริง";
            $payments[$i]->formulas = array(
                    "",
                    "",
                    "{priceOnContact} - {paidAmount}"
                );
            $payments[$i]->is_shows = array(
                    0,
                    0,
                    1
            ); 
        }

        return $payments;
    }

?>