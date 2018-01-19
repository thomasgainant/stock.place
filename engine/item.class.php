<?php
$GLOBALS["Item_paramsAsString"] = 'warehouseId, definitionId, name, dateCreated, parameters, status';
$GLOBALS["Item_paramsValuesAsString"] = ':warehouseId, :definitionId, :name, :dateCreated, :parameters, :status';
$GLOBALS["Item_paramsUpdateAsString"] = 'warehouseId = :warehouseId, definitionId = :definitionId, name = :name, dateCreated = :dateCreated, parameters = :parameters, status = :status';

class Item{
    var $id;
	function getId(){
		return $this->id;
	}
	function setId($newId){
		$this->id = $newId;
	}
	
	var $warehouseId;	
	function getWarehouseId(){
		return $this->warehouseId;
	}
	function setWarehouseId($new){
		$this->warehouseId = $new;
	}
	
	var $definitionId;	
	function getDefinitionId(){
		return $this->definitionId;
	}
	function setDefinitionId($new){
		$this->definitionId = $new;
	}
	
	var $name;	
	function getName(){
		return $this->name;
	}
	function setName($newId){
		$this->name = $newId;
	}
	
	var $dateCreated;	
	function getDateCreated(){
		return $this->dateCreated;
	}
	function setDateCreated($newId){
		$this->dateCreated = $newId;
	}
	
	var $parameters;	
	function getParameters(){
		return $this->parameters;
	}
	function setParameters($newId){
		$this->parameters = $newId;
	}
	
	/*
	0: normal, in stock
	1: desactivated/deleted
	2: removed from stock
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
		
		if(isset($rawData['warehouseId']) && !empty($rawData['warehouseId'])){
			$this->warehouseId = $rawData['warehouseId'];
		}
		
		if(isset($rawData['definitionId']) && !empty($rawData['definitionId'])){
			$this->definitionId = $rawData['definitionId'];
		}
		
		if(isset($rawData['name']) && !empty($rawData['name'])){
			$this->name = $rawData['name'];
		}
		
		if(isset($rawData['dateCreated']) && !empty($rawData['dateCreated'])){
			$this->dateCreated = $rawData['dateCreated'];
		}
		
		if(isset($rawData['parameters']) && !empty($rawData['parameters'])){
			$this->parameters = $rawData['parameters'];
		}
		
		if(isset($rawData['status']) && !empty($rawData['status'])){
			$this->status = $rawData['status'];
		}
	}
	
	function getRawDataWithId(){
		return array(
			'id' => $this->id,
			'warehouseId' => $this->warehouseId,
			'definitionId' => $this->definitionId,
			'name' => $this->name,
			'dateCreated' => $this->dateCreated,
			'parameters' => $this->parameters,
			'status' => $this->status
		);
	}
	
	function getRawData(){
		return array(
			'warehouseId' => $this->warehouseId,
			'definitionId' => $this->definitionId,
			'name' => $this->name,
			'dateCreated' => $this->dateCreated,
			'parameters' => $this->parameters,
			'status' => $this->status
		);
	}
	
	//TODO gérer définitions obligatoires à définir (date d'entrée, date de sortie, ref, prix, fournisseur, etc.)
	function getCaracteristics(){
		$rawDefs = explode("|", $this->parameters);
		$defs = array();
		$defs = $rawDefs;
		return $defs;
	}
}

function sortItemsByReferences($list){
	$sorted = array();
	
	foreach($list as $item){
		if(!array_key_exists(''.$item->name, $sorted)){
			$sorted[$item->name] = array($item);
		}
		else{
			$sorted[$item->name][count($sorted[$item->name])] = $item;
		}
	}
	
	return $sorted;
}

/*
string format is #id|#id|#id...
*/
function getItemsIdByRawString($string){
	$tmps = explode('|', $string);
	
	$result = array();
	
	foreach($tmps as $tmp){
		$id = substr($tmp, 1);
		$result[count($result)] = $id;
	}
	
	return $result;
}

function addItem($warehouseId, $entryId, $name, $parameters){
	$entry = new Item;
	$entry->warehouseId = $warehouseId;
	$entry->definitionId = $entryId;
	$entry->name = $name;
	$entry->parameters = $parameters;
	$entry->dateCreated = date("Y-m-d H:i:s");
	$entry->status = 0;

	$req = $GLOBALS['bdd']->prepare('INSERT INTO '. $GLOBALS['bdd_items'] .'('.$GLOBALS["Item_paramsAsString"].')
	VALUES('.$GLOBALS["Item_paramsValuesAsString"].')');
	$req->execute($entry->getRawData());
	
	return $GLOBALS['bdd']->lastInsertId();
}

function editItem($id, $warehouseId, $entryId, $name, $parameters, $dateCreated, $status){
	$entry = new Item;
	$entry->id = $id;
	$entry->warehouseId = $warehouseId;
	$entry->definitionId = $entryId;
	$entry->name = $name;
	$entry->parameters = $parameters;
	$entry->dateCreated = $dateCreated;
	$entry->status = $status;

	$req = $GLOBALS['bdd']->prepare('UPDATE '. $GLOBALS['bdd_items'] . ' SET ' . $GLOBALS["Item_paramsUpdateAsString"] . ' WHERE id = :id');
	$req->execute($entry->getRawDataWithId());
	
	return "";
}

function deleteItem($id){	
	$req = $GLOBALS['bdd']->query('DELETE FROM '. $GLOBALS['bdd_items'] .' WHERE id=' . $id);
	return "";
}

function getItemById($id){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_items'] . ' WHERE id=\'' . $id . '\'');
	
	$result = null;
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new Item;
		$entry->processRawData($donnees);
		$result = $entry;
	}
	
	$reponse->closeCursor();
	
	return $result;
}

function getItemsByWarehouseId($entryId){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_items'] . ' WHERE warehouseId=\''.$entryId.'\'');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new Item;
		$entry->processRawData($donnees);
		$results[count($results)] = $entry;
	}
	
	$reponse->closeCursor();
	
	return $results;
}

function getInStockItemsByWarehouseId($entryId){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_items'] . ' WHERE warehouseId=\''.$entryId.'\' AND status = \'0\'');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new Item;
		$entry->processRawData($donnees);
		$results[count($results)] = $entry;
	}
	
	$reponse->closeCursor();
	
	return $results;
}

function getInStockItemsByWarehouseIdSortedByDateAsc($entryId){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_items'] . ' WHERE warehouseId=\''.$entryId.'\' AND status = \'0\' ORDER BY dateCreated ASC');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new Item;
		$entry->processRawData($donnees);
		$results[count($results)] = $entry;
	}
	
	$reponse->closeCursor();
	
	return $results;
}

function getItemsByDefinitionId($entryId){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_items'] . ' WHERE definitionId=\''.$entryId.'\'');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new Item;
		$entry->processRawData($donnees);
		$results[count($results)] = $entry;
	}
	
	$reponse->closeCursor();
	
	return $results;
}

function getItemsByWarehouseIdAndReference($warehouseId, $reference){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_items'] . ' WHERE warehouseId=\''.$warehouseId.'\' AND name=\''.$reference.'\'');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new Item;
		$entry->processRawData($donnees);
		$results[count($results)] = $entry;
	}
	
	$reponse->closeCursor();
	
	return $results;
}

function getInStockItemsByWarehouseIdAndReference($warehouseId, $reference){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_items'] . ' WHERE warehouseId=\''.$warehouseId.'\' AND name=\''.$reference.'\' AND status=\'0\'');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new Item;
		$entry->processRawData($donnees);
		$results[count($results)] = $entry;
	}
	
	$reponse->closeCursor();
	
	return $results;
}

function getInStockItemsByReferenceSortedByDateAsc($reference){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_items'] . ' WHERE name=\''.$reference.'\' AND status=\'0\' ORDER BY dateCreated ASC');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new Item;
		$entry->processRawData($donnees);
		$results[count($results)] = $entry;
	}
	
	$reponse->closeCursor();
	
	return $results;
}

function getInStockItemsByWarehouseIdAndReferenceSortedByDateAsc($warehouseId, $reference){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_items'] . ' WHERE warehouseId=\''.$warehouseId.'\' AND name=\''.$reference.'\' AND status=\'0\' ORDER BY dateCreated ASC');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new Item;
		$entry->processRawData($donnees);
		$results[count($results)] = $entry;
	}
	
	$reponse->closeCursor();
	
	return $results;
}
?>