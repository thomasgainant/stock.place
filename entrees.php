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
			
			<p>Cette page contient de tous les articles présents dans vos stocks, ainsi que ceux qui ont été présents dans vos stocks à un moment donné.</p>
			
			<p>tableau avec les articles présents en premier, par référence (possibilité de déployer une sous catégorie avec liste avec les articles un à un) puis ceux qui ne sont plus dans les stocks, avec la même possibilité de développer une sous-div avec les articles un à un.</p>
			
			
		</section>
<?php include('footer.php'); ?>