<?php
	//TEST
	//$_SESSION['user'] = 'caporaltito';
	//$_SESSION['pwd'] = 'SunCdh/NahKlM';
	/*var_dump($_SESSION['user']);
	var_dump($_SESSION['pwd']);*/
	//var_dump($GLOBALS['user']);

	if(isset($_SESSION['user']) && !empty($_SESSION['user'])
		&& isset($_SESSION['pwd']) && !empty($_SESSION['pwd'])){
		$user = getUserByLogin($_SESSION['user']);
		if($user != null){
			/*var_dump($user);
			var_dump($user->password);
			var_dump($_SESSION['pwd']);*/
			
			if($user->password === $_SESSION['pwd']){
				$GLOBALS['user'] = $user;
			}
		}
	}
	
	if(isset($_SESSION['warehouse']) && !empty($_SESSION['warehouse'])){
		$warehouse = getWarehouseById($_SESSION['warehouse']);
		if($warehouse != null){
			$GLOBALS['warehouse'] = $warehouse;
		}
	}
	
	if(isset($_SESSION['definition']) && !empty($_SESSION['definition'])){
		$definition = getItemDefinitionById($_SESSION['definition']);
		if($definition != null){
			$GLOBALS['definition'] = $definition;
		}
	}
	
	if(isset($_POST['query']) && !empty($_POST['query'])){
		$type = antiInjectionCrypt($_POST['query']);
		
		//var_dump($GLOBALS['user']);
		
		/*ACCOUNT FUNCTIONALITIES*/
		if($type == "login-user"
		&& isset($_POST['login-login']) && !empty($_POST['login-login'])
		&& isset($_POST['login-password']) && !empty($_POST['login-password'])){
			$login = antiInjectionCrypt($_POST['login-login']);
			$password = antiInjectionCrypt($_POST['login-password']);
			$password = crypt($password, $GLOBALS['lemotdepassesiouplait']);
			//$password = openssl_decrypt($password, 'des-cbc', $GLOBALS["lemotdepasse"], 0, substr(''.$GLOBALS["lemotdepasse"], 0, 8)); //Comes encrypted, must decrypt
			
			$targetedUser = getUserByLogin($login);
			
			if($targetedUser != null){
				if($targetedUser->password === $password){
					$_SESSION['user'] = $targetedUser->login;
					$_SESSION['pwd'] = $targetedUser->password;
					$GLOBALS['user'] = $targetedUser;
				}
				else{					
					if($GLOBALS['lang'] == "fr"){
						$GLOBALS['error'] = "Mauvais identifiant ou mauvais mot de passe.";
					}
					else{
						$GLOBALS['error'] = "Wrong login or wrong password.";
					}
				}
			}
			else{
				if($GLOBALS['lang'] == "fr"){
					$GLOBALS['error'] = "Mauvais identifiant ou mauvais mot de passe.";
				}
				else{
					$GLOBALS['error'] = "Wrong login or wrong password.";
				}
			}
			//echo "@thomasgainant";
		}
		
		//var_dump($GLOBALS['user']);
		
		if($type == "register-user"){
			if(isset($_POST['register-login']) && !empty($_POST['register-login'])){
				if(isset($_POST['register-email']) && !empty($_POST['register-email'])){
					if(isset($_POST['register-email-conf']) && !empty($_POST['register-email-conf'])){
						if(isset($_POST['register-password']) && !empty($_POST['register-password'])){
							if(isset($_POST['register-password-conf']) && !empty($_POST['register-password-conf'])){
								$login = antiInjectionCrypt($_POST['register-login']);
								$email = antiInjectionCrypt($_POST['register-email']);
								$emailConf = antiInjectionCrypt($_POST['register-email-conf']);
								$password = antiInjectionCrypt($_POST['register-password']);
								$passwordConf = antiInjectionCrypt($_POST['register-password-conf']);
								
								//Verify login format
								if(preg_match("#^[a-z0-9]{4,20}$#", $login)){
									//Verify if login is not used
									$userByLogin = getUserByLogin($login);
									if($userByLogin != null){
										if($GLOBALS['lang'] == "fr"){
											$GLOBALS['error'] = 'Ce login est déjà utilisé.';
										}
										else{
											$GLOBALS['error'] = 'This login is already used.';
										}
									}
									else{
										//Verify email format
										if(filter_var($email,FILTER_VALIDATE_EMAIL) === false){
											if($GLOBALS['lang'] == "fr"){
												$GLOBALS['error'] = 'Votre adresse email n\'est pas conforme.';
											}
											else{
												$GLOBALS['error'] = 'Your email address is non-compliant.';
											}
										}
										else
										{
										   //Verify if email is not used
											$userByEmail = getUserByEmail($email);
											if($userByEmail != null){
												if($GLOBALS['lang'] == "fr"){
													$GLOBALS['error'] = 'Cet adresse email est déjà utilisée.';
												}
												else{
													$GLOBALS['error'] = 'This email address is already used.';
												}
											}
											else{
												//Verify if email is confirmed
												if($email == $emailConf){
													//Verify password format
													$correctPassword = true;
													if(preg_match("#^.{8,}$#", $password)){
														
													}
													else{
														$correctPassword = false;
													}
													
													if($correctPassword){
														//Verify password is confirmed
														if($password === $passwordConf){
															$captcha = false;
															
															//var_dump($_POST['g-recaptcha-response']);
															$url = 'https://www.google.com/recaptcha/api/siteverify';
															$data = array(
																//'secret' => '6LdoiRQTAAAAAJ23BUrXlZAT2MrmorXrk1of3mIZ',
																'secret' => '6LccrR4TAAAAAE8XKqMflOHCweZNQTDe6Bs3KFoL',
																'response' => ''.$_POST['g-recaptcha-response'],
																'remoteip' => $_SERVER['REMOTE_ADDR']);

															// use key 'http' even if you send the request to https://...
															$options = array(
																'http' => array(
																	'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
																	'method'  => 'POST',
																	'content' => http_build_query($data),
																),
															);
															$context  = stream_context_create($options);
															$result = file_get_contents($url, false, $context);
															
															$pos = strpos($result, '"success": true');
															//var_dump($result); donne :
															//string(75) "{ "success": false, "error-codes": [ "missing-input-response" ] }"
															//string(21) "{ "success": true }"
															if($pos === FALSE){
																$captcha = false;
															}
															else if(is_int($pos)){
																$captcha = true;
															}
															
															//Captcha is ok
															if($captcha){
																//register user
																addUser($login, crypt($password, $GLOBALS['lemotdepassesiouplait']), $email);
																
																//send confirmation email
																$userEntry = getUserByLogin($login);
																$timestamp = strtotime(''.$userEntry->dateJoined);															
																$confirmationCode = $timestamp * count($userEntry->login);
																
																$to = ''.$email;
																$subject = '';
																if($GLOBALS['lang'] == "fr"){
																	$subject = 'twothirds.fr - boutique - création de compte';
																}
																else{
																	$subject = 'twothirds.fr - shop - account creation';
																}
																$message = '';
																if($GLOBALS['lang'] == "fr"){
																	$message = '<p>Bonjour,</p>
	<p>Nous vous envoyons cet email pour demander confirmation de la création de votre compte sur Moccus. Vous ne pourrez pas utiliser toutes les fonctionnalités de l\'application tant que votre compte n\'aura pas été confirmé.
	<br/>Veuillez ignorer cet email si cette demande n\'a pas été effectuée de votre part.</p>
	<p>Cliquez sur le lien suivant pour confirmer votre compte twothirds.fr : <a href="http://twothirds.fr/moccus/index.php?query=confirm-user&user='.$login.'&c='.$confirmationCode.'">http://twothirds.fr/moccus/index.php?query=confirm-user&user='.$login.'&c='.$confirmationCode.'</a></p>
	<p>A bientôt sur <a href="http://twothirds.fr/moccus">Moccus</a> !</p>';
																}
																else{
																	$message = '<p>Hi,</p>
	<p>You are receiving this email because you created an account on Moccus. You will not be able to use every functionalities of the app until your account is not confirmed.
	<br/>Please ignore this email if this demand was not done by your part.</p>
	<p>Click on the following link to confirm your account on twothirds.fr : <a href="http://twothirds.fr/moccus/index.php?query=confirm-user&user='.$login.'&c='.$confirmationCode.'">http://twothirds.fr/moccus/index.php?query=confirm-user&user='.$login.'&c='.$confirmationCode.'</a></p>
	<p>See you soon on <a href="http://twothirds.fr/moccus">Moccus</a>!</p>';
																}
																
																// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
																$headers  = 'MIME-Version: 1.0' . "\r\n";
																$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

																// En-têtes additionnels
																$headers .= 'To: '.$login.' <'.$email.'>' . "\r\n";
																$headers .= 'From: contact@twothirds.fr' . "\r\n" .
																'Reply-To: contact@twothirds.fr' . "\r\n" .
																'X-Mailer: PHP/' . phpversion();

																mail($to, $subject, $message, $headers);
																
																if($GLOBALS['lang'] == "fr"){
																	$GLOBALS['info'] = 'Votre compte a été créé. Veuillez consulter votre boite email pour confirmer votre compte.';
																}
																else{
																	$GLOBALS['info'] = 'Your account was created. Please check your emails to confirm your account.';
																}
															}
															else{
																if($GLOBALS['lang'] == "fr"){
																	$GLOBALS['error'] = 'Captcha invalide.';
																}
																else{
																	$GLOBALS['error'] = 'Invalid captcha.';
																}
															}
														}
														else{
															if($GLOBALS['lang'] == "fr"){
																$GLOBALS['error'] = 'Veuillez confirmer votre mot de passe.';
															}
															else{
																$GLOBALS['error'] = 'Please confirm your password.';
															}
														}
													}
													else{
														if($GLOBALS['lang'] == "fr"){
															$GLOBALS['error'] = 'Votre mot de passe doit faire au moins huit caractères.';
														}
														else{
															$GLOBALS['error'] = 'Your password must be at least 8 characters long.';
														}
													}
												}
												else{
													if($GLOBALS['lang'] == "fr"){
														$GLOBALS['error'] = 'Veuillez confirmer votre adresse email.';
													}
													else{
														$GLOBALS['error'] = 'Please confirm your email address.';
													}
												}
											}
										}
									}
								}
								else{
									if($GLOBALS['lang'] == "fr"){
										$GLOBALS['error'] = 'Votre login ne peut utiliser que des lettres minuscules et des chiffres. Il doit également faire entre 4 et 20 caractères.';
									}
									else{
										$GLOBALS['error'] = 'Your login can only use lowercase letters and numbers. It must be between 4 and 20 characters long.';
									}
								}
							}
							else{
								if($GLOBALS['lang'] == "fr"){
									$GLOBALS['error'] = 'Vous devez confirmer votre mot de passe pour vous inscrire.';
								}
								else{
									$GLOBALS['error'] = 'You have to confirm your password to register.';
								}
							}
						}
						else{
							if($GLOBALS['lang'] == "fr"){
								$GLOBALS['error'] = 'Vous devez choisir un mot de passe pour vous inscrire. Ce mot de passe sera utilisé pour vous connecter à votre compte.';
							}
							else{
								$GLOBALS['error'] = 'You have to enter a password to register. This password will be used to log into your account.';
							}
						}
					}
					else{
						if($GLOBALS['lang'] == "fr"){
							$GLOBALS['error'] = 'Vous devez confirmer votre adresse email pour vous inscrire.';
						}
						else{
							$GLOBALS['error'] = 'You have to confirm your email address to register.';
						}
					}
				}
				else{
					if($GLOBALS['lang'] == "fr"){
						$GLOBALS['error'] = 'Vous devez renseigner votre adresse email pour vous inscrire. Cette adresse sera utilisé pour vous envoyer un email de confirmation ou en cas de perte de mot de passe.';
					}
					else{
						$GLOBALS['error'] = 'You have to enter your email address to register. This address will be used to send a confirmation email and in case you forgot your password.';
					}
				}
			}
			else{
				if($GLOBALS['lang'] == "fr"){
					$GLOBALS['error'] = 'Vous devez choisir un login pour vous inscrire. Ce login sera le nom de votre compte et sera utilisé pour vous connecter à ce compte.';
				}
				else{
					$GLOBALS['error'] = 'You have to enter a login to register. Your login will be the name of your account and will be used to log into this account.';
				}
			}
		}
		
		if($type == "disconnect-user"){
			unset($_SESSION['user']);
			unset($_SESSION['pwd']);
			unset($GLOBALS['user']);
		}
		
		if($type == "change-password"){
			$oldPassword = antiInjectionCrypt($_POST['change-password-old']);
			$newPassword = antiInjectionCrypt($_POST['change-password']);
			$passwordConf = antiInjectionCrypt($_POST['change-password-conf']);
			
			if(isset($GLOBALS['current_user']) && !empty($GLOBALS['current_user'])){
				$password = $GLOBALS['current_user']->password;
				if($oldPassword === $password){
					if($newPassword === $passwordConf){
						//Verify password format
						$correctPassword = true;
						if(preg_match("#^.{8,}$#", $newPassword)){
							
						}
						else{
							$correctPassword = false;
						}
						
						if($correctPassword){
							editUser($GLOBALS['current_user']->id, $GLOBALS['current_user']->login, $newPassword, $GLOBALS['current_user']->email, $GLOBALS['current_user']->descr, $GLOBALS['current_user']->dateJoined, $GLOBALS['current_user']->status);
							
							$GLOBALS['current_user']->password = $newPassword;
							
							if($GLOBALS['lang'] == "fr"){
								$GLOBALS['info'] = 'Votre mot de passe a été modifié.';
							}
							else{
								$GLOBALS['info'] = 'Your password has been modified.';
							}
						}
						else{
							if($GLOBALS['lang'] == "fr"){
								$GLOBALS['error'] = 'Votre mot de passe doit faire au moins huit caractères.';
							}
							else{
								$GLOBALS['error'] = 'Your password must be at least 8 characters long.';
							}
						}
					}
					else{
						if($GLOBALS['lang'] == "fr"){
							$GLOBALS['error'] = 'Vous n\'avez pas confirmé votre nouveau mot de passe.';
						}
						else{
							$GLOBALS['error'] = 'You did not confirm your new password.';
						}
					}
				}
				else{
					if($GLOBALS['lang'] == "fr"){
						$GLOBALS['error'] = 'Vous n\'avez pas rentré votre mot de passe actuel.';
					}
					else{
						$GLOBALS['error'] = 'You did not enter your current password.';
					}
				}
			}
		}
		
		if($type == "password-lost"
		&& isset($_POST['password-lost-user']) && !empty($_POST['password-lost-user'])){
			$login = antiInjectionCrypt($_POST['password-lost-user']);
			
			$user = getUserByLogin($login);
			if($user != null){
				$timestamp = strtotime(''.$user->dateJoined);
				$confirmationCode = ($timestamp * count($user->login)) / 3;
				
				$to = ''.$user->email;
				$subject = '';
				if($GLOBALS['lang'] == "fr"){
					$subject = 'twothirds.fr - boutique - remise à zéro de votre mot de passe';
				}
				else{
					$subject = 'twothirds.fr - shop - password reset';
				}
				$message = '';
				if($GLOBALS['lang'] == "fr"){
					$message = '<p>Bonjour '.$user->login.',</p>
<p>Nous vous envoyons cet email dans le cadre de la remise à zéro du mot de passe de votre compte twothirds.
<br/>Veuillez ignorer cet email si cette demande n\'a pas été effectuée de votre part.</p>
<p>Cliquez sur le lien suivant pour remettre à zéro votre mot de passe : <a href="http://twothirds.fr/moccus/index.php?query=reset-password-conf&user='.$user->login.'&p='.$confirmationCode.'">http://twothirds.fr/moccus/index.php?query=reset-password-conf&user='.$login.'&p='.$confirmationCode.'</a></p>
<p>A bientôt sur <a href="http://twothirds.fr/moccus">Moccus</a> !</p>';
				}
				else{
					$message = '<p>Hi '.$user->login.',</p>
<p>You are receiving this email because someone asked for the reset of the password of your twothirds account.
<br/>Please ignore this email if this demand was not done by your part.</p>
<p>Click on the following link to reset your password : <a href="http://twothirds.fr/moccus/index.php?query=reset-password-conf&user='.$user->login.'&p='.$confirmationCode.'">http://twothirds.fr/moccus/index.php?query=reset-password-conf&user='.$login.'&p='.$confirmationCode.'</a></p>
<p>See you soon on <a href="http://twothirds.fr/moccus">Moccus</a>!</p>';
				}
				
				// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

				// En-têtes additionnels
				$headers .= 'To: '.$user->login.' <'.$user->email.'>' . "\r\n";
				$headers .= 'From: contact@twothirds.fr' . "\r\n" .
				'Reply-To: contact@twothirds.fr' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();

				mail($to, $subject, $message, $headers);
				
				$GLOBALS['info'] = '';
				if($GLOBALS['lang'] == "fr"){
					$GLOBALS['info'] = 'Un email a été envoyé avec un lien de confirmation à l\'intérieur. Vous devez cliquer sur ce lien pour remettre votre mot de passe à zéro.';
				}
				else{
					$GLOBALS['info'] = 'An email has been sent to you with a confirmation link inside it. You must click on this link to reset your password.';
				}
			}
			else{
				$GLOBALS['error'] = 'This is user was not found.';
			}
		}
		
		/*ACTUAL MOCCUS FUNCTIONALITIES*/
		/*var_dump($type);*/
		if($type == "descr-edit"){
			if(isset($GLOBALS['user']) && !empty($GLOBALS['user'])){
				if(isset($_POST['descr-edit-title']) && !empty($_POST['descr-edit-title'])
				&& isset($_POST['descr-edit-descr']) && !empty($_POST['descr-edit-descr'])){
					$newTitle = antiInjectionCrypt($_POST['descr-edit-title']);
					$newDescr = antiInjectionCrypt($_POST['descr-edit-descr']);
					
					editWarehouse($GLOBALS['warehouse']->id, $GLOBALS['warehouse']->userId, $newTitle, $newDescr, $GLOBALS['warehouse']->dateCreated, $GLOBALS['warehouse']->status);
				}
			}
		}
		
		if($type == "add-products"){
			//var_dump($_POST);
			if(isset($GLOBALS['user']) && !empty($GLOBALS['user'])){
				if(isset($_POST['add-products-reference']) && !empty($_POST['add-products-reference'])){
					$reference = antiInjectionCrypt($_POST['add-products-reference']);
					
					$caracteristics = array();
					$caracteristicsStr = '';
					foreach($GLOBALS['definition']->getDefinitions() as $def){
						if(isset($_POST['add-products-'.$def[2]]) && !empty($_POST['add-products-'.$def[2]])){
							$defContent = antiInjectionCrypt($_POST['add-products-'.$def[2]]);
							
							$caracteristics[$def[2]] = $defContent;
							
							if($caracteristicsStr == ''){
								$caracteristicsStr = $defContent;
							}
							else{
								$caracteristicsStr = $caracteristicsStr . '|' . $defContent;
							}
						}
						else{
							$caracteristicsStr = $caracteristicsStr . '|';
						}
					}
					
					if(isset($_POST['add-products-new-reference']) && !empty($_POST['add-products-new-reference'])){
						$reference = antiInjectionCrypt($_POST['add-products-new-reference']);
					}
					
					$content = '';
					
					for($i = 0; $i < $caracteristics["quantite"]; $i++){
						$insertId = addItem($GLOBALS['warehouse']->id, $GLOBALS['definition']->id, strtoupper($reference), $caracteristicsStr);
						
						if($i > 0){
							$content .= '|';
						}
						$content .= '#'.$insertId;
					}
					
					addLog("ADD", $content, $GLOBALS['user']->login.'@'.$_SERVER['REMOTE_ADDR'], $GLOBALS['warehouse']->id);
				}
			}
		}
		
		if($type == "remove-products"){
			if(isset($GLOBALS['user']) && !empty($GLOBALS['user'])){
				if(isset($_POST['remove-products-reference']) && !empty($_POST['remove-products-reference']) && isset($_POST['remove-products-amount']) && !empty($_POST['remove-products-amount'])){
					$reference = antiInjectionCrypt($_POST['remove-products-reference']);
					$amount = antiInjectionCrypt($_POST['remove-products-amount']);
					
					/*var_dump($reference);
					var_dump($amount);*/
					
					$content = '';
					
					//TODO different type of remove (ex: by newer, by older, certains items), for the moment it's by older
					
					$items = getInStockItemsByWarehouseIdAndReferenceSortedByDateAsc($GLOBALS['warehouse']->id, $reference);
					if($amount > count($items)){
						$amount = count($items);
					}
					
					for($i = 0; $i < $amount; $i++){
						$item = $items[$i];
						editItem($item->id, $item->warehouseId, $item->definitionId, $item->name, $item->parameters, $item->dateCreated, 2);
						
						if($i > 0){
							$content .= '|';
						}
						$content .= '#'.$item->id;
					}
					
					addLog("REMOVE", $content, $GLOBALS['user']->login.'@'.$_SERVER['REMOTE_ADDR'], $GLOBALS['warehouse']->id);
				}
			}
		}
		
		if($type == "item-edit"){
			if(isset($GLOBALS['user']) && !empty($GLOBALS['user'])){
				if(isset($_POST['item-edit-old-reference']) && !empty($_POST['item-edit-old-reference'])){
					$oldReference = antiInjectionCrypt($_POST['item-edit-old-reference']);
					$newName = antiInjectionCrypt($_POST['item-edit-reference']);
					
					$logContent = '';

					$currentItems = getInStockItemsByWarehouseIdAndReference($GLOBALS['warehouse']->id, $oldReference);
					
					foreach($currentItems as $item){
						$caracteristics = array();
						$caracteristicsStr = '';
						
						foreach($GLOBALS['definition']->getDefinitions() as $def){
							if(isset($_POST['item-edit-'.$def[2]]) && !empty($_POST['item-edit-'.$def[2]])){
								$defContent = antiInjectionCrypt($_POST['item-edit-'.$def[2]]);
								
								$caracteristics[$def[2]] = $defContent;
								
								if($caracteristicsStr == ''){
									$caracteristicsStr = $defContent;
								}
								else{
									$caracteristicsStr = $caracteristicsStr . '|' . $defContent;
								}
							}
							else{
								$caracteristicsStr = $caracteristicsStr . '|';
							}
						}
						
						//echo '<p>'.$caracteristicsStr.'</p>';						
						editItem($item->id, $item->warehouseId, $item->definitionId, $newName, $caracteristicsStr, $item->dateCreated, $item->status);
						
						if($logContent != ""){
							$logContent .= '|';
						}
						$logContent .= '#'.$item->id;
					}
					
					addLog("EDIT", $logContent, $GLOBALS['user']->login.'@'.$_SERVER['REMOTE_ADDR'], $GLOBALS['warehouse']->id);
					
					$exampleItem = null;
					$editedItems = getInStockItemsByWarehouseIdAndReference($GLOBALS['warehouse']->id, $newName);
					if(count($editedItems)){
						$exampleItem = $editedItems[0];
					}
					
					if(isset($_POST['item-edit-quantite']) && !empty($_POST['item-edit-quantite'])){
						$newAmount = antiInjectionCrypt($_POST['item-edit-quantite']);
						
						/*var_dump($newAmount);
						var_dump(count($currentItems));*/
						if($newAmount > count($currentItems)){
							$numberOfItems = $newAmount - count($currentItems);
							
							$postLogContent = '#'.$exampleItem->id.'|'.$numberOfItems;
							
							for($i = 0; $i < $numberOfItems; $i++){
								addItem($exampleItem->warehouseId, $exampleItem->definitionId, $newName, $exampleItem->parameters);
							}
							
							addLog("ADD-AFTER-MODIFICATION", $postLogContent, $GLOBALS['user']->login.'@'.$_SERVER['REMOTE_ADDR'], $GLOBALS['warehouse']->id);
						}
						else if($newAmount < count($currentItems)){
							$numberOfItems = count($currentItems) - $newAmount;
							
							$postLogContent = '#'.$exampleItem->id.'|'.$numberOfItems;
							
							$items = getInStockItemsByWarehouseIdAndReferenceSortedByDateAsc($GLOBALS['warehouse']->id, $newName);
							if($numberOfItems > count($items)){
								$numberOfItems = count($items);
							}
							
							for($i = 0; $i < $numberOfItems; $i++){
								$item = $items[$i];
								editItem($item->id, $item->warehouseId, $item->definitionId, $item->name, $item->parameters, $item->dateCreated, 2);
							}
							
							addLog("REMOVE-AFTER-MODIFICATION", $postLogContent, $GLOBALS['user']->login.'@'.$_SERVER['REMOTE_ADDR'], $GLOBALS['warehouse']->id);
						}
					}
				}
			}
		}
		
		if($type == "item-delete"){
			if(isset($GLOBALS['user']) && !empty($GLOBALS['user'])){
				if(isset($_POST['item-delete-reference']) && !empty($_POST['item-delete-reference'])){
					$reference = antiInjectionCrypt($_POST['item-delete-reference']);
					
					$logContent = "";
					
					$currentItems = getInStockItemsByWarehouseIdAndReference($GLOBALS['warehouse']->id, $reference);
					foreach($currentItems as $item){
						editItem($item->id, $item->warehouseId, $item->definitionId, $item->name, $item->parameters, $item->dateCreated, 1);
						
						if($logContent != ""){
							$logContent .= '|';
						}
						$logContent .= '#'.$item->id;
					}

					addLog("DELETE", $logContent, $GLOBALS['user']->login.'@'.$_SERVER['REMOTE_ADDR'], $GLOBALS['warehouse']->id);					
				}
			}
		}
	}
	
	if(isset($_GET['query']) && !empty($_GET['query'])){
		$type = antiInjectionCrypt($_GET['query']);
		
		if($type == "confirm-user"){
			$login = antiInjectionCrypt($_GET['user']);
			$code = antiInjectionCrypt($_GET['c']);
			
			$userByLogin = getUserByLogin($login);
			if($userByLogin != null){
				$canConfirm = false;
				
				$tmp = $code / count($userByLogin->login);
				$timestamp = strtotime(''.$userByLogin->dateJoined);
				if($tmp === $timestamp){
					$canConfirm = true;
				}
				
				if($canConfirm){
					editUser($userByLogin->id, $userByLogin->login, $userByLogin->password, $userByLogin->email, $userByLogin->descr, $userByLogin->dateJoined, 0);
					
					$GLOBALS['info'] = '';
					if($GLOBALS['lang'] == "fr"){
						$GLOBALS['info'] = 'Votre compte a été confirmé !';
					}
					else{
						$GLOBALS['info'] = 'Your account has been confirmed!';
					}
				}
				else{
					if($GLOBALS['lang'] == "fr"){
						$GLOBALS['error'] = 'Mauvais code de confirmation.';
					}
					else{
						$GLOBALS['error'] = 'Wrong confirmation code.';
					}
				}
			}
			else{
				if($GLOBALS['lang'] == "fr"){
					$GLOBALS['error'] = 'Ce compte ne peut pas être confirmé car il est introuvable.';
				}
				else{
					$GLOBALS['error'] = 'Can\'t confirm this account, as we could not find it.';
				}
			}
		}		
		else if($type == "reset-password-conf"){
			$login = antiInjectionCrypt($_GET['user']);
			$code = antiInjectionCrypt($_GET['p']);
			
			$userByLogin = getUserByLogin($login);
			if($userByLogin != null){
				$canConfirm = false;
				
				$tmp = $code / count($userByLogin->login);
				$tmp = $tmp * 3;
				$timestamp = strtotime(''.$userByLogin->dateJoined);
				if($tmp === $timestamp){
					$canConfirm = true;
				}
				
				if($canConfirm){
					$password = '';
					for($i = 0; $i < 8; $i++){
						$rand = rand (0, 10);
						if($rand > 5){
							$asciiCode = rand (48, 57);
							$password .= chr($asciiCode);
						}
						else{
							$asciiCode = rand (97, 122);
							$password .= chr($asciiCode);
						}
					}
					
					$to = ''.$userByLogin->email;
					$subject = '';
					if($GLOBALS['lang'] == "fr"){
						$subject = 'twothirds.fr - boutique - remise à zéro de votre mot de passe';
					}
					else{
						$subject = 'twothirds.fr - shop - password reset';
					}
					$message = '';
					if($GLOBALS['lang'] == "fr"){
						$message = '<p>Bonjour '.$user->login.',</p>
	<p>Nous vous envoyons cet email dans le cadre de la remise à zéro du mot de passe de votre compte twothirds.</p>
	<p>Votre nouveau mot de passe est : '.$password.'</p>
	<p>Utilisez ce nouveau mot de passe provisoire pour vous connecter et choisir à nouveau un mot de passe personnel.</p>
	<p>A bientôt sur <a href="http://twothirds.fr">twothirds.fr</a> !</p>';
					}
					else{
						$message = '<p>Hi '.$user->login.',</p>
	<p>You are receiving this email because someone asked for the reset of the password of your twothirds account.</p>
	<p>Your new password is: '.$password.'</p>
	<p>Please use this temporary password to log into your account and then choose another more personal password.</p>
	<p>See you soon on <a href="http://twothirds.fr">twothirds.fr</a>!</p>';
					}
					
					// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

					// En-têtes additionnels
					$headers .= 'To: '.$userByLogin->login.' <'.$userByLogin->email.'>' . "\r\n";
					$headers .= 'From: contact@twothirds.fr' . "\r\n" .
					'Reply-To: contact@twothirds.fr' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();

					mail($to, $subject, $message, $headers);
					
					editUser($userByLogin->id, $userByLogin->login, $password, $userByLogin->email, $userByLogin->descr, $userByLogin->dateJoined, $userByLogin->status);
					
					$GLOBALS['info'] = '';
					if($GLOBALS['lang'] == "fr"){
						$GLOBALS['info'] = 'Votre mot de passe a été remis à zéro. Consultez votre boite email pour découvrir votre nouveau mot de passe.';
					}
					else{
						$GLOBALS['info'] = 'Your password has been reset. Check your emails to find out your new password.';
					}
				}
				else{
					if($GLOBALS['lang'] == "fr"){
						$GLOBALS['error'] = 'Mauvais code de confirmation.';
					}
					else{
						$GLOBALS['error'] = 'Wrong confirmation code.';
					}
				}
			}
			else{
				if($GLOBALS['lang'] == "fr"){
					$GLOBALS['error'] = 'Le mot de passe ne peut pas être remis à zéro car l\'entrée est introuvable.';
				}
				else{
					$GLOBALS['error'] = 'Cannot reset password, as we could not find the entry.';
				}
			}
		}
	}
	
	if(isset($_GET['lg']) && !empty($_GET['lg'])){
		$lg = antiInjectionCrypt($_GET['lg']);
		
		$_SESSION['lang'] = ''.$lg;
		refreshLanguage();
	}
?>