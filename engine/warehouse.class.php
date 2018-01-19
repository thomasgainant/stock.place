<?php
$GLOBALS["Warehouse_paramsAsString"] = 'userId, name, dateCreated, descr, status';
$GLOBALS["Warehouse_paramsValuesAsString"] = ':userId, :name, :dateCreated, :descr, :status';
$GLOBALS["Warehouse_paramsUpdateAsString"] = 'userId = :userId, name = :name, dateCreated = :dateCreated, descr = :descr, status = :status';

class Warehouse{
    var $id;	
	function getId(){
		return $this->id;
	}
	function setId($new){
		$this->id = $new;
	}
	
	var $userId;	
	function getUserId(){
		return $this->userId;
	}
	function setUserId($new){
		$this->userId = $new;
	}
	
	var $name;	
	function getName(){
		return $this->name;
	}
	function setName($new){
		$this->name = $new;
	}
	
	var $dateCreated;	
	function getDateCreated(){
		return $this->dateCreated;
	}
	function setDateCreated($new){
		$this->dateCreated = $new;
	}
	
	var $descr;	
	function getDescr(){
		return $this->descr;
	}
	function setDescr($new){
		$this->descr = $new;
	}
	
	/*
	0: normal
	1: desactivated
	*/
	var $status;	
	function getStatus(){
		return $this->status;
	}
	function setStatus($newStatus){
		$this->status = $newStatus;
	}
	
	function processRawData($rawData){
		if(isset($rawData['id']) && !empty($rawData['id'])){
			$this->id = $rawData['id'];
		}
		
		if(isset($rawData['userId']) && !empty($rawData['userId'])){
			$this->userId = $rawData['userId'];
		}
		
		if(isset($rawData['name']) && !empty($rawData['name'])){
			$this->name = $rawData['name'];
		}
		
		if(isset($rawData['dateCreated']) && !empty($rawData['dateCreated'])){
			$this->dateCreated = $rawData['dateCreated'];
		}
		
		if(isset($rawData['descr']) && !empty($rawData['descr'])){
			$this->descr = $rawData['descr'];
		}
		
		if(isset($rawData['status']) && !empty($rawData['status'])){
			$this->status = $rawData['status'];
		}
	}
	
	function getRawDataWithId(){
		return array(
			'id' => $this->id,
			'userId' => $this->userId,
			'name' => $this->name,
			'dateCreated' => $this->dateCreated,
			'descr' => $this->descr,
			'status' => $this->status
		);
	}
	
	function getRawData(){
		return array(
			'userId' => $this->userId,
			'name' => $this->name,
			'dateCreated' => $this->dateCreated,
			'descr' => $this->descr,
			'status' => $this->status
		);
	}
}

function addWarehouse($entryId, $name, $descr){
	$entry = new Warehouse;
	$entry->userId = $entryId;
	$entry->name = $name;
	$entry->descr = $descr;
	$entry->dateCreated = date("Y-m-d H:i:s");
	$entry->status = 0;

	$req = $GLOBALS['bdd']->prepare('INSERT INTO '. $GLOBALS['bdd_warehouses'] .'('.$GLOBALS["Warehouse_paramsAsString"].')
	VALUES('.$GLOBALS["Warehouse_paramsValuesAsString"].')');
	$req->execute($entry->getRawData());
	
	return $GLOBALS['bdd']->lastInsertId();
}

function editWarehouse($id, $entryId, $name, $descr, $dateCreated, $status){
	$entry = new Warehouse;
	$entry->id = $id;
	$entry->userId = $entryId;
	$entry->name = $name;
	$entry->descr = $descr;
	$entry->dateCreated = $dateCreated;
	$entry->status = $status;

	$req = $GLOBALS['bdd']->prepare('UPDATE '. $GLOBALS['bdd_warehouses'] . ' SET ' . $GLOBALS["Warehouse_paramsUpdateAsString"] . ' WHERE id = :id');
	$req->execute($entry->getRawDataWithId());
	
	return "";
}

function deleteWarehouse($id){	
	$req = $GLOBALS['bdd']->query('DELETE FROM '. $GLOBALS['bdd_warehouses'] .' WHERE id=' . $id);
	return "";
}

function getWarehouseById($id){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_warehouses'] . ' WHERE id=\'' . $id . '\'');
	
	$result = null;
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new Warehouse;
		$entry->processRawData($donnees);
		$result = $entry;
	}
	
	$reponse->closeCursor();
	
	return $result;
}

function getWarehousesByUserId($entryId){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_warehouses'] . ' WHERE userId=\''.$entryId.'\'');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new Warehouse;
		$entry->processRawData($donnees);
		$results[count($results)] = $entry;
	}
	
	$reponse->closeCursor();
	
	return $results;
}
?>