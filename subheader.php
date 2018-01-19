<header id="header">
	<h1><a href="index.php">stock.place</a></h1>
	<h2 class="header-subtitle"><?php loc('Logiciel en ligne de gestion de stock:|:Online stock management software'); ?></h2>
</header>

<aside id="menu">
	<nav>
		<a href="entrepot.php"><?php loc('Entrepôt:|:Warehouse'); ?></a>
		<?php /*<a href="entrees.php">Entrées</a>*/ ?>
		<a href="journal.php"><?php loc('Journal:|:Log'); ?></a>
		<?php /*<a href="fournisseurs.php">Fournisseurs</a>
		<a href="acheteurs.php">Acheteurs</a>
		<a href="inventaire.php">Inventaire</a>
		<a href="analyse.php">Analyse</a>*/ ?>
		<a href="compte.php">@<?php echo $GLOBALS['user']->login; ?></a>
	</nav>
	<!--<form>
		<input type="text"/>
		<input type="submit" value="Rechercher"/>
	</form>-->
	<div id="lang-select">
		<a href="entrepot.php?lang=en"><img src="medias/icons/us.png"/></a><a href="entrepot.php?lang=fr"><img src="medias/icons/fr.png"/></a>
	</div>
</aside>