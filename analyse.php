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
			
			<p>Cette page contient une analyse graphique et numérique de vos stocks.</p>
			
			<p class="warning">Attention, cette page ne sera disponible que pour les membres pro de Moccus. Elle vous est disponible pour l'instant gratuitement mais ne deviendra plus tard accessible que si vous êtes membre pro.
			<a class="feedback">Devenir membre pro</a></p>
			
			<p>tableaux et graphiques avec évolution du stock dans le temps, évolution de la valeur du stock, des achats et des ventes, etc.</p>
			
			
		</section>
<?php include('footer.php'); ?>