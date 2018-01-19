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
			
			<p>Cette page contient de tous les fournisseurs à partir desquels vous vous êtes procuré vos marchandises.</p>
			
			<p class="warning">Attention, cette page contient des fonctionnalités réservées aux membres pro de Moccus, notamment les fonctions de rajout et d'édition de fournisseurs. Elles vous sont pour l'instant fournies gratuitement mais ne deviendront plus tard accessibles que si vous êtes membre pro.
			<a class="feedback">Devenir membre pro</a></p>
			
			<p>tableau avec tous les fournisseurs et en développant une sous-div, tous les articles par référence qu'ils ont fourni, avec possibilité dans rajouter et éditer des fournisseurs (feature pro).</p>
			
			
		</section>
<?php include('footer.php'); ?>