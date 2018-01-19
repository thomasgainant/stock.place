<?php
$GLOBALS["User_paramsAsString"] = 'login, password, email, descr, dateJoined, status';
$GLOBALS["User_paramsValuesAsString"] = ':login, :password, :email, :descr, :dateJoined, :status';
$GLOBALS["User_paramsUpdateAsString"] = 'login = :login, password = :password, email = :email, descr=:descr, dateJoined = :dateJoined, status = :status';

class User{
    var $id;	
	function getId(){
		return $this->id;
	}
	function setId($newId){
		$this->id = $newId;
	}
	
	var $login;	
	function getLogin(){
		return $this->login;
	}
	function setLogin($newLogin){
		$this->login = $newLogin;
	}
	
	var $password;	
	function getPassword(){
		return $this->password;
	}
	function setPassword($newPassword){
		$this->password = $newPassword;
	}
	
	var $email;	
	function getEmail(){
		return $this->email;
	}
	function setEmail($newEmail){
		$this->email = $newEmail;
	}
	
	var $descr;	
	function getDescr(){
		return $this->descr;
	}
	function setDescr($newDescr){
		$this->descr = $newDescr;
	}
	
	var $dateJoined;	
	function getDateJoined(){
		return $this->dateJoined;
	}
	function setDateJoined($newDateJoined){
		$this->dateJoined = $newDateJoined;
	}
	
	/*
	0: basic account, confirmed
	1: unconfirmed account (did not click on the confirmation link in email)
	*/
	var $status;	
	function getStatus(){
		return $this->status;
	}
	function setStatus($newStatus){
		$this->status = $newStatus;
	}
	
	/*function getDecryptedMoney(){
		return openssl_decrypt($this->money, 'des-cbc', $this->getDateJoined(), 0, substr(''.$this->getDateJoined(), 0, 8));
	}*/
	
	function processRawData($rawData){
		//Crypted, but not for id and date
		if(isset($rawData['id']) && !empty($rawData['id'])){
			$this->id = $rawData['id'];
		}
		
		if(isset($rawData['login']) && !empty($rawData['login'])){
			$this->login = $rawData['login'];
		}
		
		if(isset($rawData['password']) && !empty($rawData['password'])){
			//$this->password = openssl_decrypt($rawData['password'], 'des-cbc', $GLOBALS["lemotdepassesiouplait"], 0, substr(''.$GLOBALS["lemotdepassesiouplait"], 0, 8));
			$this->password = $rawData['password'];
		}
		
		if(isset($rawData['email']) && !empty($rawData['email'])){
			$this->email = $rawData['email'];
		}
		
		if(isset($rawData['descr']) && !empty($rawData['descr'])){
			$this->descr = $rawData['descr'];
		}
		
		if(isset($rawData['dateJoined']) && !empty($rawData['dateJoined'])){
			$this->dateJoined = $rawData['dateJoined'];
		}
		
		if(isset($rawData['status']) && !empty($rawData['status'])){
			$this->status = $rawData['status'];
		}
	}
	
	function getRawDataWithId(){
		return array(
			'id' => $this->id,
			'login' => $this->login,
			//'password' => openssl_encrypt($this->password, 'des-cbc', $GLOBALS["lemotdepassesiouplait"], 0, substr(''.$GLOBALS["lemotdepassesiouplait"], 0, 8)),
			'password' => $this->password,
			'email' => $this->email,
			'descr' => $this->descr,
			'dateJoined' => $this->dateJoined,
			'status' => $this->status
		);
	}
	
	function getRawData(){
		return array(
			'login' => $this->login,
			//'password' => openssl_encrypt($this->password, 'des-cbc', $GLOBALS["lemotdepassesiouplait"], 0, substr(''.$GLOBALS["lemotdepassesiouplait"], 0, 8)),
			'password' => $this->password,
			'email' => $this->email,
			'descr' => $this->descr,
			'dateJoined' => $this->dateJoined,
			'status' => $this->status
		);
	}
}

function addUser($login, $password, $email){
	//reserved logins
	/*if($login === "login"){
		return;
	}
	if($login === "all"){
		return;
	}
	if($login === "friends"){
		return;
	}*/
	
	$user = new User;
	$user->login = $login;
	$user->password = $password;
	$user->email = $email;
	$user->descr = "";
	$user->dateJoined = date("Y-m-d H:i:s");
	$user->status = 1;
	
	//var_dump($GLOBALS["User_paramsValuesAsString"]);

	$req = $GLOBALS['bdd']->prepare('INSERT INTO '. $GLOBALS['bdd_users'] .'('.$GLOBALS["User_paramsAsString"].')
	VALUES('.$GLOBALS["User_paramsValuesAsString"].')');
	$req->execute($user->getRawData());
	
	$userId = $GLOBALS['bdd']->lastInsertId();
	$warehouseName = "";
	$descr = "";
	$definitionName = "";
	$definitionText = "";
	$article1Name = "";
	$article1Caracteristics = "";
	$article2Name = "";
	$article2Caracteristics = "";
	
	if($GLOBALS['lang'] == "fr"){
		$warehouseName = "Mon premier entrepôt";
		$descr = "Ceci est votre entrepôt, n'hésitez pas à le personnaliser, rajouter des produits en stock ou bien en supprimer.";
		
		$definitionName = "Ma classification";
		$definitionText = "Catégorie du produit[text-list]categorie-du-produit|Désignation du produit[text]designation-du-produit|Fabricant[text]fabricant|Commentaires[text]commentaires|Fournisseur[people]fournisseur|Quantité[int]quantite|Minimum[int]minimum|Lieu de stockage[text]lieu-de-stockage|Prix unitaire d'achat[price]prix-unitaire-d-achat|Prix total d'achat[price]prix-total-d-achat|Prix d'unitaire de vente[price]prix-unitaire-de-vente|Prix total en vente[price]prix-total-en-vente";
		
		$article1Name = "TOURN1";
		$article1Caracteristics = "Maintenance|Tournevis cruciforme|Facom|Bien ranger au-dessus de l'établi après utilisation|M. Bricolage|1|2|Etabli bleu, Hangar 3|3.30|-|-|-";
		
		$article2Name = "PERC1";
		$article2Caracteristics = "Maintenance|Marteau perforateur SDS|MAKITA|Enrouler câble après utilisation|M. Bricolage|1|1|Etabli bleu, Hangar 3|60.30|-|-|-";
	}
	else{
		$warehouseName = "My first warehouse";
		$descr = "This is your first warehouse, do not hesitate to customise it, to add some products or to delete some of them.";
		
		$definitionName = "My classification";
		$definitionText = "Product category[text-list]categorie-du-produit|Product name[text]designation-du-produit|Manufacturer[text]fabricant|Comments[text]commentaires|Supplier[people]fournisseur|Amount[int]quantite|Minimum[int]minimum|Location[text]lieu-de-stockage|Unit buy price[price]prix-unitaire-d-achat|Total buy price[price]prix-total-d-achat|Unit sell price[price]prix-unitaire-de-vente|Total sell price[price]prix-total-en-vente";
		
		$article1Name = "SCREWDR1";
		$article1Caracteristics = "Maintenance|Cross-head screwdriver|Facom|Remember to put it inside the workbench after use.|The Home Depot|1|2|Hangar 3, blue workbench|3.30|-|-|-";
		
		$article2Name = "DRILL1";
		$article2Caracteristics = "Maintenance|SDS hammer drill|MAKITA|Wind the cable after use.|The Home Depot|1|1|Hangar 3, blue workbench|60.30|-|-|-";
	}
	
	//Add example warehouse
	$warehouseId = addWarehouse($userId, $warehouseName, $descr);
	
	//Add example definition
	$definitionId = addItemDefinition($warehouseId, $definitionName, $definitionText);
	
	//Add example items
	for($i = 0; $i < 3; $i++){
		addItem($warehouseId, $definitionId, $article1Name, $article1Caracteristics);
	}	
	for($i = 0; $i < 2; $i++){
		addItem($warehouseId, $definitionId, $article2Name, $article2Caracteristics);
	}
	
	//return $GLOBALS['loc'][$GLOBALS['current_loc']]["member_added"];
	return $userId;
}

function editUser($id, $login, $password, $email, $descr, $dateJoined, $status){
	$user = new User;
	$user->id = $id;
	$user->login = $login;
	$user->password = $password;
	$user->email = $email;
	$user->descr = $descr;
	$user->dateJoined = $dateJoined;
	$user->status = $status;

	$req = $GLOBALS['bdd']->prepare('UPDATE '. $GLOBALS['bdd_users'] . ' SET ' . $GLOBALS["User_paramsUpdateAsString"] . ' WHERE id = :id');
	$req->execute($user->getRawDataWithId());
	
	return "";
}

function deleteUser($id){	
	$req = $GLOBALS['bdd']->query('DELETE FROM '. $GLOBALS['bdd_users'] .' WHERE id=' . $id);
	return "";
}

function getUserById($id){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_users'] . ' WHERE id=\'' . $id . '\'');
	
	$result = null;
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$user = new User;
		$user->processRawData($donnees);
		$result = $user;
	}
	
	$reponse->closeCursor();
	
	return $result;
}

function getUserByLogin($login){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_users'] . ' WHERE login=\'' . $login . '\'');
	
	$result = null;
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$user = new User;
		$user->processRawData($donnees);
		$result = $user;
	}
	
	$reponse->closeCursor();
	
	return $result;
}

function getUserByEmail($email){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_users'] . ' WHERE email=\'' . $email . '\'');
	
	$result = null;
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$user = new User;
		$user->processRawData($donnees);
		$result = $user;
	}
	
	$reponse->closeCursor();
	
	return $result;
}

//TODO sort all the shit below
function getLogsByClientIdTypeAndDates($id, $type, $startTimestamp, $endTimestamp){
	$start = date("Y-m-d H:i:s", $startTimestamp);
	$end = date("Y-m-d H:i:s", $endTimestamp);
	
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_users'] . ' WHERE clientId=\'' . openssl_encrypt($id, 'des-cbc', $GLOBALS["lemotdepassesiouplait"], 0, substr(''.$GLOBALS["lemotdepassesiouplait"], 0, 8))
	. '\' AND logType=\''.openssl_encrypt($type, 'des-cbc', $GLOBALS["lemotdepassesiouplait"], 0, substr(''.$GLOBALS["lemotdepassesiouplait"], 0, 8)).'\' AND dateOfLog>=\'' . $start	. '\' AND dateOfLog<=\''.$end.'\'');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$log = new Log;
		$log->processRawData($donnees);
		$results[count($results)] = $log;
	}
	
	$reponse->closeCursor();
	
	return $results;
}

function getLogsByTypeAndDates($type, $startTimestamp, $endTimestamp){
	$start = date("Y-m-d H:i:s", $startTimestamp);
	$end = date("Y-m-d H:i:s", $endTimestamp);
	
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_users'] . ' WHERE logType=\''.openssl_encrypt($type, 'des-cbc', $GLOBALS["lemotdepassesiouplait"], 0, substr(''.$GLOBALS["lemotdepassesiouplait"], 0, 8)).'\' AND dateOfLog>=\'' . $start . '\' AND dateOfLog<=\''.$end.'\'');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$log = new Log;
		$log->processRawData($donnees);
		$results[count($results)] = $log;
	}
	
	$reponse->closeCursor();
	
	return $results;
}

function getLogsBetweenDates($startTimestamp, $endTimestamp){
	$start = date("Y-m-d H:i:s", $startTimestamp);
	$end = date("Y-m-d H:i:s", $endTimestamp);
	
	/*echo $start;
	echo $end;*/
	
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_users'] . ' WHERE dateOfLog>=\'' . $start	. '\' AND dateOfLog<=\''.$end.'\'');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$log = new Log;
		$log->processRawData($donnees);
		$results[count($results)] = $log;
	}
	
	$reponse->closeCursor();
	
	return $results;
}

function getLogsBetweenDatesSortedByDate($startTimestamp, $endTimestamp){
	$start = date("Y-m-d H:i:s", $startTimestamp);
	$end = date("Y-m-d H:i:s", $endTimestamp);
	
	/*echo $start;
	echo $end;*/
	
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_users'] . ' WHERE dateOfLog>=\'' . $start	. '\' AND dateOfLog<=\''.$end.'\' ORDER BY dateOfLog ASC');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$log = new Log;
		$log->processRawData($donnees);
		$results[count($results)] = $log;
	}
	
	$reponse->closeCursor();
	
	return $results;
}

function getAllClientsAtDate($timestamp){
	$clients = array();
	
	$logs = getLogsByTypeAndDates("LOG IN", 0, $timestamp);
	
	foreach($logs as $log){
		$shouldAdd = true;
		
		foreach($clients as $client){
			if($log->clientId === $client){
				$shouldAdd = false;
			}
		}
		
		if($shouldAdd){
			$clients[count($clients)] = $log->clientId;
		}
	}
	
	return $clients;
}

function getAllClientsAtDates($timestampStart, $timestamp){
	$clients = array();
	
	$logs = getLogsByTypeAndDates("LOG IN", $timestampStart, $timestamp);
	
	foreach($logs as $log){
		$shouldAdd = true;
		
		foreach($clients as $client){
			if($log->clientId === $client){
				$shouldAdd = false;
			}
		}
		
		if($shouldAdd){
			$clients[count($clients)] = $log->clientId;
		}
	}
	
	return $clients;
}

function getAllActiveClientsAtDates($timestampStart, $timestamp){
	$clients = array();
	
	$logs = getLogsByTypeAndDates("START", $timestampStart, $timestamp);
	
	foreach($logs as $log){
		$shouldAdd = true;
		
		foreach($clients as $client){
			if($log->clientId === $client){
				$shouldAdd = false;
			}
		}
		
		if($shouldAdd){
			$clients[count($clients)] = $log->clientId;
		}
	}
	
	return $clients;
}

/*function getMemberByName($name){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_members'] . ' WHERE name=\'' . $name . '\'');
	
	$result = null;
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$member = new Member;
		$member->processRawData($donnees);
		$result = $member;
	}
	
	$reponse->closeCursor();
	
	return $result;
}*/

function getBestScores(){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_users'] . ' WHERE logType=\''.openssl_encrypt('NEW', 'des-cbc', $GLOBALS["lemotdepassesiouplait"], 0, substr(''.$GLOBALS["lemotdepassesiouplait"], 0, 8)).'\'');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$log = new Log;
		$log->processRawData($donnees);
		$results[count($results)] = $log;
	}
	
	$reponse->closeCursor();
	
	return $results;
}

function getLatestScores(){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_users'] . ' WHERE logType=\''.openssl_encrypt('NEW', 'des-cbc', $GLOBALS["lemotdepassesiouplait"], 0, substr(''.$GLOBALS["lemotdepassesiouplait"], 0, 8)).'\' ORDER BY dateOfLog DESC');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$log = new Log;
		$log->processRawData($donnees);
		$results[count($results)] = $log;
	}
	
	$reponse->closeCursor();
	
	return $results;
}

function getBestScoreForClientId($clientId){
	$result = 0;
	
	$logs = getLogsByClientIdAndType($clientId, "NEW");
	foreach($logs as $log){
		$score = $log->content;
		if($score > $result){
			$result = $score;
		}
	}
	
	return $result;
}

function getMoneyForClientId($clientId){
	$result = 30;
	
	$logsSpend = getLogsByClientIdAndType($clientId, "START");
	foreach($logsSpend as $log){
		$result -= $GLOBALS['money_for_new_game'];
	}
	
	$logsBuy = getLogsByClientIdAndType($clientId, "BUY");
	foreach($logsBuy as $log){
		$amount = $log->content;
		
		if($amount >= $GLOBALS['free_games_threshold']){
			$result += $amount;
		}
		else{
			$result += $GLOBALS['money_for_new_game'];
		}
	}
	
	$logsWin = getLogsByClientIdAndType($clientId, "WIN");
	foreach($logsWin as $log){
		$amount = $log->content;
		$result += $amount;
	}
	
	return $result;
}

function getMoneyForClientIdAtDate($clientId, $timestamp){
	$result = 30;
	
	$logsSpend = getLogsByClientIdTypeAndDates($clientId, "START", 0, $timestamp);
	foreach($logsSpend as $log){
		$result -= $GLOBALS['money_for_new_game'];
	}
	
	$logsBuy = getLogsByClientIdTypeAndDates($clientId, "BUY", 0, $timestamp);
	foreach($logsBuy as $log){
		$amount = $log->content;
		
		if($amount >= $GLOBALS['free_games_threshold']){
			$result += $amount;
		}
		else{
			$result += $GLOBALS['money_for_new_game'];
		}
	}
	
	$logsWin = getLogsByClientIdTypeAndDates($clientId, "WIN", 0, $timestamp);
	foreach($logsWin as $log){
		$amount = $log->content;
		$result += $amount;
	}
	
	return $result;
}

function getLastFreeGameForClient($id){
	$lastFreeLog = null;
	$lastFreeLogDate;
	
	$logs = getLogsByClientIdAndType($id, "BUY");
	foreach($logs as $log){
		if(is_numeric($log->content) && intval($log->content) < $GLOBALS['free_games_threshold']){
			if($lastFreeLog == null){
				$timestamp = $log->dateOfLog;
				$timestamp = date_create_from_format('Y-m-d H:i:s', $timestamp);
				
				$lastFreeLog = $log;
				$lastFreeLogDate = $timestamp;
			}
			else{
				$timestamp = $log->dateOfLog;
				$timestamp = date_create_from_format('Y-m-d H:i:s', $timestamp);
				
				if($timestamp > $lastFreeLogDate){
					$lastFreeLog = $log;
					$lastFreeLogDate = $timestamp;
				}
			}
		}
	}
	
	return $lastFreeLog;
}
?>