<?php
	function findTemplateByTransactionId($transaction_id)
	{
		$template_id = 5;
		$payments = getPaymentsByTemplateId($template_id);
        $SQL  = "select template_id from tranfer_transaction where id = $transaction_id";
        $result = DB_query($connect,$SQL);
        $row = DB_fetch_array($result);
		return array(
			"template_id"=>$row['template_id'],
			"payments"=>$payments
		);
	}

	function createTemplate($name, $description, $payment_ids)
	{
		//create
		$template_id = 99;
		$is_show = 0;
        $SQL  = "INSERT INTO tranfer_template(name,description,is_show)  VALUES ('$name', '$description','$is_show'); SELECT SCOPE_IDENTITY()";
        $result = DB_query($connect,$SQL);
        if($result){
            sqlsrv_next_result($result); 
            sqlsrv_fetch($result); 
            $template_id = sqlsrv_get_field($result, 0); 
            return $template_id;
        }else{
            return false;
        }
		
	}

	function updateTemplate($template_id, $args)
	{
		$keys = "";
		$values ="";
		$isFirst = true;
		foreach ($args as $key => $value)
		{
			if($isFirst)
				$isFirst = true;
			else
			{
				$keys = $keys.",";
				$values = $values.",";
			}
			$keys = $keys.$key;
			$values = $values.$value;
			
		}

		$sql = "UPDATE tranfer_template SET ({$keys}) VALUES({$values})";
		$result = DB_query($connect,$sql);
        echo $sql;
         if($result){
            sqlsrv_next_result($result); 
            sqlsrv_fetch($result); 
            $template_id = sqlsrv_get_field($result, 0); 
            return $template_id;
        }else{
            return false;
        }
	}

	function deleteTemplate($template_id)
	{
		//return result of delete
        $SQL  = " DELETE FROM tranfer_template WHERE id = $template_id ";
        $result = DB_query($connect,$SQL);
		return true;
	}

?>