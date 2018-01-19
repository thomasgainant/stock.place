<?php include('header.php'); ?>
		<section id="content" class="simple-presentation-wrapper">
			<section class="simple-presentation">
				<h1>stock.place</h1>
				<h2><?php loc("Gérer vos stocks gratuitement et simplement:|:Stock management for the masses"); ?></h2>
				<div class="presentation-feedback">
					<a class="inscription-clic" href="#"><?php loc("Découvrir gratuitement:|:Discover for free"); ?></a>
				</div>
				<div class="presentation-feedback">
					<a class="connexion-clic" href="#"><?php loc("Connexion:|:Login"); ?></a>
				</div>
				<div>&nbsp;</div>
				<div id="lang-select">
					<a href="index.php?lang=en"><img src="medias/icons/us.png"/></a><a href="index.php?lang=fr"><img src="medias/icons/fr.png"/></a>
				</div>
			</section><section class="simple-presentation-fake-element">&nbsp;</section><div class="fake">&nbsp;</div><img class="simple-presentation-background" src="medias/logistics-stock-room.jpg"/><div class="fake">&nbsp;</div><div class="simple-presentation-overlay"></div>
			<!--<header id="header" class="centered">
				<img id="logo" src="medias/logo.png"/>
				<h1>Moccus - Logiciel en ligne de gestion de stock</h1>
				<h2>Gérer vos stocks gratuitement et simplement</h2>
				<div class="presentation-feedback">
					<a class="inscription-clic" href="#">Découvrir gratuitement</a>
				</div>
				<a class="connexion-clic" href="#">Connexion</a>
			</header>-->
			
			<!--<div class="presentation">
				<section id="step1" class="presentation-block">
					<h2>La gestion des stocks et l'inventaire facile</h2>
					<img src="medias/boxes.jpg"/>
					<p>Iam in altera philosophiae parte. quae est quaerendi ac disserendi, quae logikh dicitur, iste vester plane, ut mihi quidem videtur, inermis ac nudus est. tollit definitiones, nihil de dividendo ac partiendo docet, non quo modo efficiatur concludaturque ratio tradit, non qua via captiosa solvantur ambigua distinguantur ostendit; iudicia rerum in sensibus ponit, quibus si semel aliquid falsi pro vero probatum sit, sublatum esse omne iudicium veri et falsi putat.</p>
				</section>
				
				<section class="presentation-feedback">
					<a class="inscription-clic" href="#">Inscription</a>
				</section>
				
				<section id="step2" class="presentation-block">
					<h2>Fonctionnalités</h2>
					<img src="medias/warehouse-barcode.jpg"/>
					<p>Iam in altera philosophiae parte. quae est quaerendi ac disserendi, quae logikh dicitur, iste vester plane, ut mihi quidem videtur, inermis ac nudus est. tollit definitiones, nihil de dividendo ac partiendo docet, non quo modo efficiatur concludaturque ratio tradit, non qua via captiosa solvantur ambigua distinguantur ostendit; iudicia rerum in sensibus ponit, quibus si semel aliquid falsi pro vero probatum sit, sublatum esse omne iudicium veri et falsi putat.</p>
				</section>
				
				<section id="step3" class="presentation-block">
					<h2>Gratuit et modulaire</h2>
					<p>Vous vous êtes toujours demander comment payer les logiciels disponibles dans la grande distribution ? Ou vous voulez tout simplement tester une nouvelle solution à vos problèmes sans avoir à payer cher ? Moccus est gratuit !</p>
					<p>Modulaire, vous ne payez pas pour les fonctionnalités dont vous n'avez pas besoin.</p>
				</section>-->
			</div>
			<div id="simple-presentation-legal">&copy;<a href="http://twothirds.fr">twothirds.fr</a> - 2016</div>
		</section>
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
		<aside id="popup-mot-de-passe-perdu" class="popup">
			<div class="popup-content">
				<h3><?php loc("Mot de passe perdu:|:Lost password"); ?></h3>
				<form method="post" action = "index.php">
					<input name="query" type="hidden" value="password-lost"/>
					<label for="register-login"><?php if($GLOBALS['lang'] == "fr"){ echo 'Login : '; } else{ echo 'Login: ';} ?></label><input id="password-lost-user" name="password-lost-user" type="text"/>
					<input type="submit" value="<?php if($GLOBALS['lang'] == "fr"){ echo 'Remise à zéro du mot de passe'; } else{ echo 'Reset password';} ?>"/>
				</form>
			<div class="popup-close"><a href="#">X</a></div></div><div class="popup-wrapper">&nbsp;</div>
		</aside>
<?php include('footer.php'); ?>