<?php include('header.php'); ?>
<?php
	$warehouses = getWarehousesByUserId($GLOBALS['user']->id);
	$warehouse = $warehouses[0];
	
	//$definitions = getItemDefinitionsByWarehouseId($warehouse->id);
?>
		<section id="content">
			<?php
				include('subheader.php');
			?>
			
			<h2>Votre entrepôt "<?php echo $warehouse->name; ?>"</h2>
			<p><?php echo $warehouse->descr; ?></p>
			
			<p>Cette page contient de tous les articles qui sont ou ont été en votre possession.</p>
			
			<p class="warning">Attention, cette page ne sera disponible que pour les membres pro de Moccus. Elle vous est disponible pour l'instant gratuitement mais ne deviendra plus tard accessible que si vous êtes membre pro.
			<a class="feedback">Devenir membre pro</a></p>
			
			<p>tableau avec tous les articles qui sont ou ont été en possession. Trouver des features en plus pour différencier cette page de entrees.php (peut être que entrees.php sera plus focalisée sur les rajouts et retraits de marchandises)</p>
			
			
		</section>
<?php include('footer.php'); ?>