<form action="./#/bills" method="post">

	<?php for($i =0; $i < 10; $i++): ?>
		<input type="checkbox" name="unit_ids[]" value="<?php echo $i; ?>">unit id:<?php echo $i; ?><br/>
	<?php endfor; ?>
	<input type="submit" />
</form>