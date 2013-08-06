<?php
	function findTemplateByTransactionId($transaction_id)
	{
		$template_id = 5;
		$payments = getPaymentsByTemplateId($template_id);
		return array(
			"template_id"=>$template_id,
			"payments"=>$payments
		);
	}

	function createTemplate($name, $description, $payment_ids)
	{
		//create
		$template_id = 99;
		return $template_id;
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

		$example_sql = "UPDATE table_name ({$keys}) VALUES({$values})";
		echo $example_sql;

		return $template_id;
	}

	function deleteTemplate($template_id)
	{
		//return result of delete
		return true;
	}

?>