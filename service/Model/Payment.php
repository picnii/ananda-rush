<?php
function getPayments()
{
    $SQL  = "SELECT * FROM tranfer_payment";
    $result = DB_query($GLOBALS['connect'],$SQL);
    
    $payments =  array();
    while($row = DB_fetch_array($result))
    {
        $payment = new stdClass;
        $payment->id = $row['id'];
        $payment->name = $row['name'];
        $payment->description = $row['description'];
        $payment->formulas = array(
            $row['formula1'],
            $row['formula2'],
            $row['formula3']
        );
        $payment->is_shows = array(
            $row['is_show1'].'',
            $row['is_show2'].'',
            $row['is_show3'].''
        );
        $payment->is_add_in_cheque = $row['is_add_in_cheque'];
        $payment->is_compare_with_repayment = $row['is_compare_with_repayment'];
        array_push($payments, $payment);
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

function findPaymentById($id)
{
    $SQL = "SELECT * FROM tranfer_payment WHERE id = {$id}";
    $result = DB_query($GLOBALS['connect'],$SQL);
    $row = DB_fetch_array($result);
    if(!isset($row['id']))
        return false;

    $payment = new stdClass;
    $payment->id = $row['id'];
    $payment->name = $row['name'];
    $payment->description = $row['description'];
    $payment->formulas = array(
        $row['formula1'],
        $row['formula2'],
        $row['formula3']
    );
    $payment->is_shows = array(
        $row['is_show1'].'',
        $row['is_show2'].'',
        $row['is_show3'].''
    );
    $payment->is_add_in_cheque = $row['is_add_in_cheque'];
    $payment->is_compare_with_repayment = $row['is_compare_with_repayment'];

    return $payment;
}

function createPayment($name, $description, $formulas, $is_shows, $is_add_in_cheque, $is_compare_with_repayment)
{   
    $SQL = "INSERT INTO tranfer_payment(name,description,formula1,formula2,formula3,is_show1,is_show2,is_show3,is_add_in_cheque, is_compare_with_repayment)
         VALUES ('$name', '$description','{$formulas[0]}','{$formulas[1]}','{$formulas[2]}', '{$is_shows[0]}',  '{$is_shows[1]}', '{$is_shows[2]}','{$is_add_in_cheque}', '{$is_compare_with_repayment}');
         SELECT SCOPE_IDENTITY()
    ";
     $result = DB_query($GLOBALS['connect'],$SQL);
    if($result){
            sqlsrv_next_result($result); 
            sqlsrv_fetch($result); 
            $payment_id = sqlsrv_get_field($result, 0);
            return $payment_id; 
            //return $payment_id;
    }else{
            return false;
    }
	
}

function updatePayment($payment_id, $name, $description, $formulas, $is_shows, $is_add_in_cheque, $is_compare_with_repayment)
{
    $sql = "UPDATE tranfer_payment SET 
        name = '{$name}', description = '{$description}',
        formula1 = '{$formulas[0]}', formula2 = '$formulas[1]', formula3 = '$formulas[2]',
        is_show1 = {$is_shows[0]}, is_show2 = $is_shows[1], is_show3 = $is_shows[2],
        is_add_in_cheque = {$is_add_in_cheque}, is_compare_with_repayment = {$is_add_in_cheque}
        WHERE id = {$payment_id}
    ";
    $rs = DB_query($GLOBALS['connect'],$sql); 
    if($rs >0)
        return true;
    else
        return false;
   
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
    $payments =  array();
	while($row = DB_fetch_array($result) )
    {
        $payment = new stdClass;
        $payment->id = $row['id'];
        $payment->order = $row['orders'];
        $payment->name = $row['name'];
        $payment->description = $row['description'];
        $payment->formulas = array(
            $row['formula1'],
            $row['formula2'],
            $row['formula3']
        );
        $payment->is_shows = array(
            $row['is_show1'],
            $row['is_show2'],
            $row['is_show3']
        );
        $payment->is_add_in_cheque = $row['is_add_in_cheque'];
        $payment->is_compare_with_repayment = $row['is_compare_with_repayment'];
        array_push($payments, $payment);
    }
    //if(count($payments) >0){
    	return $payments;
    //}else{
      //  return false;
    //}
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