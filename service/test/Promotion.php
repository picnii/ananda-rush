<?php

	$units = findAllUnits();
	$unit_test = $units[3];
	//print_r($unit_test->item_id);
	//print_r($unit_test);
	$promotions = findAllPromotionsPreapprove('TH04C207009', 'C0013056');
	print_r($promotions);
?>