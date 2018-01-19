<?php include('header.php'); ?>
<?php
	$warehouses = getWarehousesByUserId($GLOBALS['user']->id);
	$warehouse = $warehouses[0];
	
	//$definitions = getItemDefinitionsByWarehouseId($warehouse->id);
?>
		<?php
			if(isset($GLOBALS['user']) && !empty($GLOBALS['user'])){
		?>
		<section id="content">
				<?php
					include('subheader.php');
					
					$currentWarehouses = getWarehousesByUserId($GLOBALS['user']->id);
					$GLOBALS['warehouse'] = $currentWarehouses[0];
					$_SESSION['warehouse'] = $GLOBALS['warehouse']->id;
					
					/*$definitions = getItemDefinitionsByWarehouseId($GLOBALS['warehouse']->id);
					$GLOBALS['definition'] = $definitions[0];//By default, every items are using the first definition found TODO use of multiples definitions
					$_SESSION['definition'] = $GLOBALS['definition']->id;*/
					
					$logs = getLogsByWarehouseIdSortedByDateAsc($GLOBALS['warehouse']->id);
				?>
				
				<div class="padding-on-side">
					<p class="italic"><?php loc('Cette page contient de tous les actions effectuées au sein de votre entrepôt.:|:This page displays every action done on your warehouse.'); ?></p>
					<h2><?php loc('Votre entrepôt:|:Your warehouse'); ?> "<?php echo $GLOBALS['warehouse']->name; ?>"</h2>
					<p class="inline-block warehouse-descr"><?php echo stripslashes($GLOBALS['warehouse']->descr); ?></p>
					<a href="#" class="icon icon-descr-edit inline-block"><img src="medias/icons/reecrire.png"/> <?php loc('Éditer:|:Edit'); ?></a>
					
					<?php /*<p>tableau avec toutes les opérations faites sur l'entrepôt (ajout de marchandises, retrait), en remontant dans le temps.</p>*/ ?>
					
					<div class="centered">
						<div class="warehouse">
							<table>
								<thead>
									<tr><th><?php loc("Type d'action:|:Type"); ?></th><th><?php loc("Produits concernés:|:Concerned products"); ?></th><th><?php loc("Date:|:Date"); ?></th><th><?php loc("Compte associé:|:Linked account"); ?></th><th><?php loc("Adresse IP:|:IP address"); ?></th></tr>
								</thead>
								<tbody>
									<?php
										foreach(array_reverse($logs) as $log){
											$type = "";
											$class = "";
											$content = "";
											$date = "";
											$author = explode('@', $log->author);
											$authorName = $author[0];
											$ip = $author[1];
											if($log->logType == "ADD"){
												$type = getMultiLocalisedString("RENTRÉE DE PRODUITS:|:PRODUCTS ADDITION");
												$class = 'class="green"';
											}
											else if($log->logType == "ADD-AFTER-MODIFICATION"){
												$type = getMultiLocalisedString("AJOUT DE PRODUITS SUITE A MODIFICATION:|:PRODUCTS ADDITION RESULTING FROM PRODUCT EDITION");
												$class = 'class="green"';
											}
											else if($log->logType == "REMOVE"){
												$type = getMultiLocalisedString("RETRAIT DE PRODUITS:|:PRODUCTS WITHDRAWAL");
												$class = 'class="red"';
											}
											else if($log->logType == "REMOVE-AFTER-MODIFICATION"){
												$type = getMultiLocalisedString("RETRAIT DE PRODUITS SUITE A MODIFICATION:|:PRODUCTS WITHDRAWAL RESULTING FROM PRODUCT EDITION");
												$class = 'class="red"';
											}
											else if($log->logType == "EDIT"){
												$type = getMultiLocalisedString("MODIFICATION DE PRODUITS:|:PRODUCTS EDITION");
												$class = 'class="yellow"';
												
												$content .= "<ul>";
												$itemsConcernedIds = getItemsIdByRawString($log->content);
												$itemsConcerned = array();
												foreach($itemsConcernedIds as $itemId){
													$item = getItemById($itemId);
													if($item != null){
														$itemsConcerned[count($itemsConcerned)] = $item;
													}
												}
												$references = sortItemsByReferences($itemsConcerned);
												foreach($references as $key => $reference){
													$content .= '<li>'.$key.'</li>';
												}
												$content .= "</ul>";
											}
											else if($log->logType == "DELETE"){
												$type = getMultiLocalisedString("SUPPRESSION DE PRODUITS:|:PRODUCTS DELETION");
												$class = 'class="black"';
												
												$content .= "<ul>";
												$itemsConcernedIds = getItemsIdByRawString($log->content);
												$itemsConcerned = array();
												foreach($itemsConcernedIds as $itemId){
													$item = getItemById($itemId);
													if($item != null){
														$itemsConcerned[count($itemsConcerned)] = $item;
													}
												}
												$references = sortItemsByReferences($itemsConcerned);
												foreach($references as $key => $reference){
													$content .= '<li>'.$key.'</li>';
												}
												$content .= "</ul>";
											}
											
											if($GLOBALS['lang'] == "fr"){
												$timestamp = strtotime($log->dateCreated);
												$date = date("d/m/Y H:i:s", $timestamp);
											}
											else{
												$timestamp = strtotime($log->dateCreated);
												$date = date("m/d/Y H:i:s", $timestamp);
											}
											
											echo '<tr><td '.$class.'>'.$type.'</td><td>'.$content.'</td><td>'.$date.'</td><td>'.$authorName.'</td><td>'.$ip.'</td></tr>';
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
		</section>
		<?php
			}
			else{
				echo '<section id="content" class="simple-presentation-wrapper">
							<section class="simple-presentation">
								<h1>stock.place</h1>
								<h2>'. getMultiLocalisedString("Gérer vos stocks gratuitement et simplement:|:Stock management made easy") . '</h2>
								<div class="presentation-feedback">
									<a class="inscription-clic" href="#">'. getMultiLocalisedString("Découvrir gratuitement:|:Discover for free") . '</a>
								</div>
								<div class="presentation-feedback">
									<a class="connexion-clic" href="#">'. getMultiLocalisedString("Connexion:|:Login") . '</a>
								</div>
								<div>&nbsp;</div>
								<div id="lang-select">
									<a href="index.php?lang=en"><img src="medias/icons/us.png"/></a><a href="index.php?lang=fr"><img src="medias/icons/fr.png"/></a>
								</div>
							</section><section class="simple-presentation-fake-element">&nbsp;</section><div class="fake">&nbsp;</div><img class="simple-presentation-background" src="medias/logistics-stock-room.jpg"/><div class="fake">&nbsp;</div><div class="simple-presentation-overlay"></div>
						</section>';
			}
		?>
		<?php
		if(isset($GLOBALS['info']) && !empty($GLOBALS['info'])){
		?>
			<aside id="popup-info" class="popup">
				<div class="popup-content">
					<?php
					echo $GLOBALS['info'];
					?>
				<div class="popup-close"><a href="#">X</a></div></div><div class="popup-wrapper">&nbsp;</div>
			</aside>
		<?php
		}
		?>
		<?php
		if(isset($GLOBALS['error']) && !empty($GLOBALS['error'])){
		?>
			<aside id="popup-error" class="popup">
				<div class="popup-content">
					<?php
					echo $GLOBALS['error'];
					?>
				<div class="popup-close"><a href="#">X</a></div></div><div class="popup-wrapper">&nbsp;</div>
			</aside>
		<?php
		}
		?>
		<aside id="popup-inscription" class="popup">
			<div class="popup-content">
				<h3><?php loc("Inscription:|:Register"); ?></h3>			
				<form method="post" action = "journal.php">
					<input name="query" type="hidden" value="register-user"/>
					<table>
						<tr>
							<td><label for="register-login"><?php if($GLOBALS['lang'] == "fr"){ echo 'Identifiant : '; } else{ echo 'Login: ';} ?></label></td>
							<td><input id="register-login" name="register-login" type="text"/></td>
						</tr>
						<tr>
							<td><label for="register-email"><?php if($GLOBALS['lang'] == "fr"){ echo 'Email : '; } else{ echo 'Email: ';} ?></label></td>
							<td><input id="register-email" name="register-email" type="text"/></td>
						</tr>
						<tr>
							<td><label for="register-email-conf"><?php if($GLOBALS['lang'] == "fr"){ echo 'Confirmation de l\'email : '; } else{ echo 'Email confirmation: ';} ?></label></td>
							<td><input id="register-email-conf" name="register-email-conf" type="text"/></td>
						</tr>
						<tr>
							<td><label for="register-password"><?php if($GLOBALS['lang'] == "fr"){ echo 'Mot de passe : '; } else{ echo 'Password: ';} ?></label></td>
							<td><input id="register-password" name="register-password" type="password"/></td>
						</tr>
						<tr>
							<td><label for="register-password-conf"><?php if($GLOBALS['lang'] == "fr"){ echo 'Confirmation du mot de passe : '; } else{ echo 'Password confirmation: ';} ?></label></td>
							<td><input id="register-password-conf" name="register-password-conf" type="password"/></td>
						</tr>
						<tr>
							<td><input type="submit" value="<?php if($GLOBALS['lang'] == "fr"){ echo 'Inscription'; } else{ echo 'Register';} ?>"/></td>
							<td></td>
						</tr>
					</table>
					<!--<div class="g-recaptcha" data-sitekey="6LdoiRQTAAAAADEiPyPQE_RYN8-sQebZ9t6aGO_K"></div>-->
					<div class="g-recaptcha" data-sitekey="6LdoiRQTAAAAADEiPyPQE_RYN8-sQebZ9t6aGO_K"></div>
				</form>				
			<div class="popup-close"><a href="#">X</a></div></div><div class="popup-wrapper">&nbsp;</div>
		</aside>
		<aside id="popup-connexion" class="popup">
			<div class="popup-content">
				<h3><?php loc("Se connecter:|:Connect"); ?></h3>
				<form method="post" action = "journal.php">
					<input name="query" type="hidden" value="login-user"/>
					<table>
						<tr><td><label for="login-login"><?php loc("Identifiant ::|:Login:"); ?></label></td><td><input id="login-login" name="login-login" type="text"/></td></tr>
						<tr><td><label for="login-password"><?php loc("Mot de passe ::|:Password:"); ?></label></td><td><input id="login-password" name="login-password" type="password"/></td></tr>
						<tr><td></td><td><input type="submit" value="<?php loc("Connexion:|:Connect"); ?>"/></td></tr>
					</table>
				</form>
			<div class="popup-close"><a href="#">X</a></div></div><div class="popup-wrapper">&nbsp;</div>
		</aside>
		<aside id="popup-descr-edit" class="popup">
			<div class="popup-content">
				<h3><?php loc("Éditer votre entrepôt:|:Edit your warehouse"); ?></h3>
				<form method="post" action="journal.php">
					<input name="query" type="hidden" value="descr-edit"/>
					<input name="descr-edit-title" type="text" value="<?php echo $GLOBALS['warehouse']->name; ?>"/><br/>
					<textarea name="descr-edit-descr" rows="4" cols="50"><?php echo stripslashes($GLOBALS['warehouse']->descr); ?></textarea><br/>
					<input type="submit" value="<?php loc('Confirmer:|:Confirm'); ?>"/>
				</form>
			<div class="popup-close"><a href="#">X</a></div></div><div class="popup-wrapper">&nbsp;</div>
		</aside>
<?php include('footer.php'); ?>