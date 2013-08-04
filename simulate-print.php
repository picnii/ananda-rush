<form action="./service/index.php" method="get">

	<?php for($i =0; $i < 10; $i++): ?>
		<input type="checkbox" name="unit_ids[]" value="<?php echo $i; ?>">unit id:<?php echo $i; ?><br/>
	<?php endfor; ?>
	<input type="hidden" name="action" value="bills"/>
	<input type="hidden" name="template_id" value="5"/>
	<input type="submit" />
</form>