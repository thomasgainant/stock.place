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
				?>
				
				<div class="padding-on-side">
					<p class="italic"><?php loc('Cette page vous permet de modifier les informations conçernant votre compte.:|:On this page, you can modify your account information.'); ?></p>

					<p><?php loc('Connecté en tant que ::|:Connected as:'); ?><br/>
					<span class="bold"><?php echo $GLOBALS['user']->login; ?></span></p>
					<form method="post" action="index.php">
						<input type="hidden" name="query" value="disconnect-user" />
						<input type="submit" value="<?php loc("Se déconnecter:|:Disconnect"); ?>">
					</form>
					
					<p><?php loc('Adresse email de contact ::|:Contact email address:'); ?><br/>
					<span class="bold"><?php echo $GLOBALS['user']->email; ?></span></p>
					
					<h3><?php if($GLOBALS['lang'] == "fr"){ echo 'Changement de mot de passe'; } else{ echo 'Password change';} ?></h3>
					<form method="post" action="index.php">
						<input type="hidden" name="query" value="change-password" />
						<table>
						<tr><td><label for="change-password-old"><?php if($GLOBALS['lang'] == "fr"){ echo 'Ancien mot de passe : '; } else{ echo 'Old password: ';} ?></label></td><td><input type="password" id="change-password-old" name="change-password-old"/></td></tr>
						<tr><td><label for="change-password"><?php if($GLOBALS['lang'] == "fr"){ echo 'Nouveau mot de passe : '; } else{ echo 'New password: ';} ?></label></td><td><input type="password" id="change-password" name="change-password"/></td></tr>
						<tr><td><label for="change-password-conf"><?php if($GLOBALS['lang'] == "fr"){ echo 'Confirmation du nouveau mot de passe : '; } else{ echo 'New password confirmation: ';} ?></label></td><td><input type="password" id="change-password-conf" name="change-password-conf"/></td></tr>
						<tr><td></td><td><input type="submit" value="<?php if($GLOBALS['lang'] == "fr"){ echo 'Changer le mot de passe'; } else{ echo 'Change password';} ?>"/></td></tr>
						</table>
					</form>
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
				<form method="post" action = "compte.php">
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
				<form method="post" action = "compte.php">
					<input name="query" type="hidden" value="login-user"/>
					<table>
						<tr><td><label for="login-login"><?php loc("Identifiant ::|:Login:"); ?></label></td><td><input id="login-login" name="login-login" type="text"/></td></tr>
						<tr><td><label for="login-password"><?php loc("Mot de passe ::|:Password:"); ?></label></td><td><input id="login-password" name="login-password" type="password"/></td></tr>
						<tr><td></td><td><input type="submit" value="<?php loc("Connexion:|:Connect"); ?>"/></td></tr>
					</table>
				</form>
			<div class="popup-close"><a href="#">X</a></div></div><div class="popup-wrapper">&nbsp;</div>
		</aside>
<?php include('footer.php'); ?>