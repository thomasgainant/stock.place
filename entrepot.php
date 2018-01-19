<?php include('header.php'); ?>
		<?php
			//var_dump($GLOBALS['user']);
			$definitions = null;
			$references = null;
		
			if(isset($GLOBALS['user']) && !empty($GLOBALS['user'])){
		?>
		<section id="content">
			<?php			
				$currentWarehouses = getWarehousesByUserId($GLOBALS['user']->id);
				$GLOBALS['warehouse'] = $currentWarehouses[0];
				$_SESSION['warehouse'] = $GLOBALS['warehouse']->id;
				echo '<script type="text/javascript">var warehouseId = '.$GLOBALS['warehouse']->id.';</script>';
				
				$definitions = getItemDefinitionsByWarehouseId($GLOBALS['warehouse']->id);
				$GLOBALS['definition'] = $definitions[0];//By default, every items are using the first definition found TODO use of multiples definitions
				$_SESSION['definition'] = $GLOBALS['definition']->id;
				
				//$items = getItemsByWarehouseId($GLOBALS['warehouse']->id);
				$items = getInStockItemsByWarehouseIdSortedByDateAsc($GLOBALS['warehouse']->id);
				$references = sortItemsByReferences($items);
			
				include('subheader.php');
			?>
			<div class="padding-on-side">
				<p class="italic"><?php loc('Cette page contient votre stock actuel, en prenant compte de vos précédentes rentrées de produits et de vos précédents retraits.:|:This page contains your current stock, given your previous product additions and withdrawals.'); ?></p>
				<h2><?php loc('Votre entrepôt:|:Your warehouse'); ?> "<?php echo $GLOBALS['warehouse']->name; ?>"</h2>
				<p class="inline-block warehouse-descr"><?php echo stripslashes($GLOBALS['warehouse']->descr); ?></p>
				<a href="#" class="icon icon-descr-edit inline-block"><img src="medias/icons/reecrire.png"/> <?php loc('Éditer:|:Edit'); ?></a>
				
				<div class="centered">
					<a class="icon icon-ajout" href="#"><img src="medias/icons/ajout.png"/> <?php loc('Nouvelle rentrée:|:New addition'); ?></a><a class="icon icon-retrait" href="#"><img src="medias/icons/retrait.png"/> <?php loc('Nouveau retrait:|:New withdrawal'); ?></a>
					<div>&nbsp;</div>
					<div class="warehouse">
						<table>
							<!--<thead>
								<tr>
									<th>Identifiant produit</th>
									<th>Catégorie du produit</th>
									<th>Référence</th>
									<th>Désignation du produit</th>
									<th>Fabricant</th>
									<th>Commentaires</th>
									<th>Fournisseur</th>
									<th>Quantité</th>
									<th>Minimum</th>
									<th>Lieu de stockage</th>
									<th>Prix unitaire d'achat</th>
									<th>Prix total d'achat</th>
									<th>Prix d'unitaire de vente</th>
									<th>Prix total en vente</th>
									<th></th>
								</tr>
							</thead>-->
							<thead>
								<tr>
									<!--Gérer les définition obligatoires-->
									<th><?php loc('Référence:|:Reference'); ?></th>
									<!--<th>Catégorie du produit</th>
									<th>Désignation du produit</th>
									<th>Fabricant</th>
									<th>Commentaires</th>
									<th>Fournisseur</th>
									<th>Quantité</th>
									<th>Minimum</th>
									<th>Lieu de stockage</th>
									<th>Prix unitaire d'achat</th>
									<th>Prix total d'achat</th>
									<th>Prix d'unitaire de vente</th>
									<th>Prix total en vente</th>
									<th></th>-->
									<?php
										foreach($GLOBALS['definition']->getDefinitions() as $def){
											echo '<th>'.$def[0].'</th>';
										}
									?>
									<th></th>
								</tr>
							</thead>
							<tbody>
									<?php
										$valeurAchatTotale = 0;
										$valeurVenteTotale = 0;
									
										$j = 0;
										foreach($references as $reference){
											if($j % 2 == 0){
												echo '<tr><td>'.$reference[0]->name.'</td>';
											}
											else{
													echo '<tr class="uneven"><td>'.$reference[0]->name.'</td>';
											}
											
											$caracteristics = $reference[0]->getCaracteristics();
											
											$index = 0;
											foreach($caracteristics as $caracteristic){
												if($index == $GLOBALS['definition']->getDefinitionIndex("quantite")){
													if(count($reference) < $caracteristics[$GLOBALS['definition']->getDefinitionIndex("minimum")]){
														echo '<td class="red">'.count($reference).'</td>';
													}
													else if(count($reference) == $caracteristics[$GLOBALS['definition']->getDefinitionIndex("minimum")]){
														echo '<td class="yellow">'.count($reference).'</td>';
													}
													else{
														echo '<td>'.count($reference).'</td>';
													}
												}
												else if($index == $GLOBALS['definition']->getDefinitionIndex("prix-total-d-achat")){
													$totalPrice = $caracteristics[$GLOBALS['definition']->getDefinitionIndex("prix-unitaire-d-achat")] * count($reference);
													echo '<td>'.$totalPrice.'</td>';
													$valeurAchatTotale += $totalPrice;
												}
												else if($index == $GLOBALS['definition']->getDefinitionIndex("prix-unitaire-de-vente")){
													$totalPrice = $caracteristics[$GLOBALS['definition']->getDefinitionIndex("prix-unitaire-de-vente")] * count($reference);
													echo '<td>'.$totalPrice.'</td>';
													$valeurVenteTotale += $totalPrice;
												}
												else if($index == $GLOBALS['definition']->getDefinitionIndex("minimum")){
													//var_dump($GLOBALS['definition']->getDefinitionIndex("quantite"));
													//var_dump($caracteristics);
													
													echo '<td>'.$caracteristic.'</td>';
												}
												else{
													echo '<td>'.$caracteristic.'</td>';
												}
												
												$index++;
											}
											
											echo '<td><a class="icon icon-editer" href="#'.$reference[0]->name.'"><img src="medias/icons/editer.png"/></a><a class="icon icon-supprimer"  href="#'.$reference[0]->name.'"><img src="medias/icons/supprimer.png"/></a></td></tr>';
											
											$j++;
										}
									?>
									<!--<td>TOURN1</td>
									<td>Maintenance</td>
									<td>Tournevis cruciforme</td>
									<td>Facom</td>
									<td>Bien ranger au-dessus de l'établi après utilisation</td>
									<td>M. Bricolage</td>
									<td>1</td>
									<td>1</td>
									<td>Etabli bleu, Hangar 3</td>
									<td>3.30€</td>
									<td>3.30€</td>
									<td>-</td>
									<td>-</td>
									<td><a class="icon icon-ajout" href="#"><img src="medias/icons/ajout.png"/></a><a class="icon icon-retrait" href="#"><img src="medias/icons/retrait.png"/></a></td>-->
							</tbody>
							<tfoot>
								<tr>
									<td colspan="9"></td>
									<td>Total</td>
									<td><?php echo $valeurAchatTotale.''; ?></td>
									<td>Total</td>
									<td><?php echo $valeurVenteTotale.''; ?></td>
									<td></td>
								</tr>
							</tfoot>
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
				<form method="post" action = "entrepot.php">
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
					<div class="g-recaptcha" data-sitekey="6LccrR4TAAAAANq0hra9R7hlcP0iBHR9cro3XJt_"></div>
				</form>				
			<div class="popup-close"><a href="#">X</a></div></div><div class="popup-wrapper">&nbsp;</div>
		</aside>
		<aside id="popup-connexion" class="popup">
			<div class="popup-content">
				<h3><?php loc("Se connecter:|:Connect"); ?></h3>
				<form method="post" action = "entrepot.php">
					<input name="query" type="hidden" value="login-user"/>
					<table>
						<tr><td><label for="login-login"><?php loc("Identifiant ::|:Login:"); ?></label></td><td><input id="login-login" name="login-login" type="text"/></td></tr>
						<tr><td><label for="login-password"><?php loc("Mot de passe ::|:Password:"); ?></label></td><td><input id="login-password" name="login-password" type="password"/></td></tr>
						<tr><td></td><td><input type="submit" value="<?php loc("Connexion:|:Connect"); ?>"/></td></tr>
					</table>
				</form>
			<div class="popup-close"><a href="#">X</a></div></div><div class="popup-wrapper">&nbsp;</div>
		</aside>
		<aside id="popup-ajout" class="popup">
			<div class="popup-content">
				<h3><?php loc("Nouvelle rentrée de produits:|:Add products"); ?></h3>
				<form method="post" action="entrepot.php">
					<input name="query" type="hidden" value="add-products"/>
					<table>
						<thead>
							<tr>
								<th><?php loc('Référence:|:Reference'); ?></th>
								<?php
									foreach($GLOBALS['definition']->getDefinitions() as $def){
										echo '<th>'.$def[0].'</th>';
									}
								?>
							</tr>
						</thead>
						<tbody>
							<?php
								$exampleItem = null;
								
								if($references != null && count($references) > 0){
									reset($references);
									$firstKey = key($references);
									$exampleItem = $references[$firstKey][0];
								}
							?>
							<tr>
								<td><select class="add-products-reference-selector" name="add-products-reference"><option value="	"<?php if(count($references) <= 0){ echo ' selected="selected"';} ?>></option>
								<?php
									$index = 0;
									foreach($references as $key => $ref){
										echo '<option value="'.$key.'"';
										if($index == 0){
											echo ' selected="selected"';
										}
										echo '>'.$key.'</option>';
										$index++;
									}
								?>
								</select></td>
								<?php
									$index = 0;
									foreach($GLOBALS['definition']->getDefinitions() as $def){
										if($exampleItem != null){
											$caracteristic = $exampleItem->getCaracteristics()[$index];
											echo '<td><input name="add-products-'.$def[2].'" type="text" size="10" value="'.$caracteristic.'"/></td>';
										}
										else{
											echo '<td><input name="add-products-'.$def[2].'" type="text" size="10" value=""/></td>';
										}
										
										$index++;
									}
								?>
							</tr>
							<tr>
								<td><label><?php loc('Nouvel article:|:New article'); ?></label><input name="add-products-new-reference" type="text" size="6"/></td>
								<?php									
									foreach($GLOBALS['definition']->getDefinitions() as $def){
										echo '<td></td>';
									}
								?>
								<td></td>
							</tr>
						</tbody>
					</table>
					<input type="submit" value="<?php loc('Confirmer:|:Confirm'); ?>"/>
				</form>
			<div class="popup-close"><a href="#">X</a></div></div><div class="popup-wrapper">&nbsp;</div>
		</aside>
		<aside id="popup-retrait" class="popup">
			<div class="popup-content">
				<h3><?php loc("Nouveau retrait de produits:|:Withdraw products"); ?></h3>
				<form method="post" action="entrepot.php">
					<input name="query" type="hidden" value="remove-products"/>
					<table>
						<tr><td><label for="remove-products-reference"><?php loc("Référence:|:Reference"); ?></label></td><td><select id="remove-products-reference" name="remove-products-reference"><?php
							foreach($references as $key => $ref){
								echo '<option value="'.$key.'"';
								if($index == 0){
									echo ' selected="selected"';
								}
								echo '>'.$key.'</option>';
							}
						?></select></td></tr>
						<tr><td><label for="remove-products-amount"><?php loc("Quantité:|:Amount"); ?></label></td><td><input id="remove-products-amount" name="remove-products-amount" type="text" size="3"/></td></tr>
						<tr><td></td><td><input type="submit" value="<?php loc('Confirmer:|:Confirm'); ?>"/></td></tr>
					</table>
				</form>
			<div class="popup-close"><a href="#">X</a></div></div><div class="popup-wrapper">&nbsp;</div>
		</aside>
		<aside id="popup-descr-edit" class="popup">
			<div class="popup-content">
				<h3><?php loc("Éditer votre entrepôt:|:Edit your warehouse"); ?></h3>
				<form method="post" action="entrepot.php">
					<input name="query" type="hidden" value="descr-edit"/>
					<input name="descr-edit-title" type="text" value="<?php echo $GLOBALS['warehouse']->name; ?>"/><br/>
					<textarea name="descr-edit-descr" rows="4" cols="50"><?php echo stripslashes($GLOBALS['warehouse']->descr); ?></textarea><br/>
					<input type="submit" value="<?php loc('Confirmer:|:Confirm'); ?>"/>
				</form>
			<div class="popup-close"><a href="#">X</a></div></div><div class="popup-wrapper">&nbsp;</div>
		</aside>
		<?php
			if(isset($GLOBALS['user']) && !empty($GLOBALS['user'])){				
				foreach($references as $key => $reference){
		?>
					<aside id="popup-item-edit-<?php echo $key; ?>" class="popup">
						<div class="popup-content">
							<h3><?php loc("Modifier un type de produits:|:Edit products"); ?></h3>
							<form method="post" action="entrepot.php">
								<input name="query" type="hidden" value="item-edit"/>
								<input name="item-edit-old-reference" type="hidden" value="<?php echo $key; ?>"/>
								<table>
									<thead>
										<tr>
											<th><?php loc('Référence:|:Reference'); ?></th>
											<?php
												foreach($GLOBALS['definition']->getDefinitions() as $def){
													echo '<th>'.$def[0].'</th>';
												}
											?>
										</tr>
									</thead>
									<tbody>
										<?php
											$exampleItem = null;
											
											if($references != null && count($references) > 0){
												reset($references);
												$firstKey = key($references);
												$exampleItem = $references[$firstKey][0];
											}
										?>
										<tr>
											<td><input name="item-edit-reference" type="text" size="6" value="<?php echo $key; ?>"/></td>
											<?php
												$index = 0;
												foreach($GLOBALS['definition']->getDefinitions() as $def){
													if($exampleItem != null){
														if($index == $GLOBALS['definition']->getDefinitionIndex("quantite")){
															$caracteristic = $exampleItem->getCaracteristics()[$index];
															$itemsForReference = getInStockItemsByWarehouseIdAndReference($GLOBALS['warehouse']->id, $key);
															echo '<td><input name="item-edit-'.$def[2].'" type="text" size="10" value="'.count($itemsForReference).'"/></td>';
														}
														else if($index == $GLOBALS['definition']->getDefinitionIndex("prix-total-d-achat")){
															$caracteristic = $exampleItem->getCaracteristics()[$GLOBALS['definition']->getDefinitionIndex("prix-unitaire-d-achat")];
															$itemsForReference = getInStockItemsByWarehouseIdAndReference($GLOBALS['warehouse']->id, $key);
															$totalPrice = $caracteristic * count($itemsForReference);
															echo '<td>'.$totalPrice.'</td>';
														}
														else if($index == $GLOBALS['definition']->getDefinitionIndex("prix-total-en-vente")){
															$caracteristic = $exampleItem->getCaracteristics()[$GLOBALS['definition']->getDefinitionIndex("prix-unitaire-de-vente")];
															$itemsForReference = getInStockItemsByWarehouseIdAndReference($GLOBALS['warehouse']->id, $key);
															$totalPrice = $caracteristic * count($itemsForReference);
															echo '<td>'.$totalPrice.'</td>';
														}
														else{
															$caracteristic = $exampleItem->getCaracteristics()[$index];
															echo '<td><input name="item-edit-'.$def[2].'" type="text" size="10" value="'.$caracteristic.'"/></td>';
														}
													}
													else{
														echo '<td><input name="item-edit-'.$def[2].'" type="text" size="10" value=""/></td>';
													}
													
													$index++;
												}
											?>
										</tr>
									</tbody>
								</table>
								<input type="submit" value="<?php loc('Confirmer:|:Confirm'); ?>"/>
							</form>
						<div class="popup-close"><a href="#">X</a></div></div><div class="popup-wrapper">&nbsp;</div>
					</aside>
					
					<aside id="popup-item-delete-<?php echo $key; ?>" class="popup">
						<div class="popup-content">
							<h3><?php loc("Supprimer un type de produits:|:Delete products"); ?></h3>
							<p><?php loc('Souhaitez-vous réellement supprimer les produits sous référence "'.$key.'" ?:|:Do you really want to delete every products under the reference "'.$key.'"?'); ?></p>
							<form method="post" action="entrepot.php">
								<input name="query" type="hidden" value="item-delete"/>
								<input name="item-delete-reference" type="hidden" value="<?php echo $key; ?>"/>
								<input type="submit" value="<?php loc('Confirmer:|:Confirm'); ?>"/>
							</form>
						<div class="popup-close"><a href="#">X</a></div></div><div class="popup-wrapper">&nbsp;</div>
					</aside>
		<?php
				}
			}
		?>
<?php include('footer.php'); ?>