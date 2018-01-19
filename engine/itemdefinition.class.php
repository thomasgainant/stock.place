<?php
$GLOBALS["ItemDefinition_paramsAsString"] = 'warehouseId, name, dateCreated, definition, status';
$GLOBALS["ItemDefinition_paramsValuesAsString"] = ':warehouseId, :name, :dateCreated, :definition, :status';
$GLOBALS["ItemDefinition_paramsUpdateAsString"] = 'warehouseId = :warehouseId, name = :name, dateCreated = :dateCreated, definition = :definition, status = :status';

class ItemDefinition{
    var $id;	
	function getId(){
		return $this->id;
	}
	function setId($new){
		$this->id = $new;
	}
	
	var $warehouseId;	
	function getWarehouseId(){
		return $this->warehouseId;
	}
	function setWarehouseId($new){
		$this->warehouseId = $new;
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
	
	var $definition;	
	function getDescr(){
		return $this->definition;
	}
	function setDescr($new){
		$this->definition = $new;
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
		
		if(isset($rawData['warehouseId']) && !empty($rawData['warehouseId'])){
			$this->warehouseId = $rawData['warehouseId'];
		}
		
		if(isset($rawData['name']) && !empty($rawData['name'])){
			$this->name = $rawData['name'];
		}
		
		if(isset($rawData['dateCreated']) && !empty($rawData['dateCreated'])){
			$this->dateCreated = $rawData['dateCreated'];
		}
		
		if(isset($rawData['definition']) && !empty($rawData['definition'])){
			$this->definition = $rawData['definition'];
		}
		
		if(isset($rawData['status']) && !empty($rawData['status'])){
			$this->status = $rawData['status'];
		}
	}
	
	function getRawDataWithId(){
		return array(
			'id' => $this->id,
			'warehouseId' => $this->warehouseId,
			'name' => $this->name,
			'dateCreated' => $this->dateCreated,
			'definition' => $this->definition,
			'status' => $this->status
		);
	}
	
	function getRawData(){
		return array(
			'warehouseId' => $this->warehouseId,
			'name' => $this->name,
			'dateCreated' => $this->dateCreated,
			'definition' => $this->definition,
			'status' => $this->status
		);
	}
	
	//TODO gérer définitions obligatoires à définir (date d'entrée, date de sortie, ref, prix, fournisseur, etc.)
	function getDefinitions(){
		$rawDefs = explode("|", $this->definition);
		$defs = array();
		foreach($rawDefs as $raw){
			$tmp = explode("[", $raw);
			$first = $tmp[0];
			$tmp2 = explode("]", $tmp[1]);
			//$second = substr($tmp[1], 0, -1);//Remove last ]
			$second = $tmp2[0];
			$third = $tmp2[1];
			$defs[count($defs)] = array($first, $second, $third);
		}
		return $defs;
	}
	
	function getDefinitionIndexByName($name){
		$result = -1;
		
		$defs = $this->getDefinitions();
		
		$index = 0;
		foreach($defs as $def){
			if($def[0] == $name){
				$result = $index;
			}
			$index++;
		}
		
		return $result;
	}
	
	function getDefinitionIndex($name){
		$result = -1;
		
		$defs = $this->getDefinitions();
		
		$index = 0;
		foreach($defs as $def){
			if($def[2] == $name){
				$result = $index;
			}
			$index++;
		}
		
		return $result;
	}
}

function addItemDefinition($entryId, $name, $definition){
	$entry = new ItemDefinition;
	$entry->warehouseId = $entryId;
	$entry->name = $name;
	$entry->definition = $definition;
	$entry->dateCreated = date("Y-m-d H:i:s");
	$entry->status = 0;

	$req = $GLOBALS['bdd']->prepare('INSERT INTO '. $GLOBALS['bdd_item_definition'] .'('.$GLOBALS["ItemDefinition_paramsAsString"].')
	VALUES('.$GLOBALS["ItemDefinition_paramsValuesAsString"].')');
	$req->execute($entry->getRawData());
	
	return $GLOBALS['bdd']->lastInsertId();
}

function editItemDefinition($id, $entryId, $name, $definition, $dateCreated, $status){
	$entry = new ItemDefinition;
	$entry->id = $id;
	$entry->warehouseId = $entryId;
	$entry->name = $name;
	$entry->definition = $definition;
	$entry->dateCreated = $dateCreated;
	$entry->status = $status;

	$req = $GLOBALS['bdd']->prepare('UPDATE '. $GLOBALS['bdd_item_definition'] . ' SET ' . $GLOBALS["ItemDefinition_paramsUpdateAsString"] . ' WHERE id = :id');
	$req->execute($entry->getRawDataWithId());
	
	return "";
}

function deleteItemDefinition($id){	
	$req = $GLOBALS['bdd']->query('DELETE FROM '. $GLOBALS['bdd_item_definition'] .' WHERE id=' . $id);
	return "";
}

function getItemDefinitionById($id){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_item_definition'] . ' WHERE id=\'' . $id . '\'');
	
	$result = null;
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new ItemDefinition;
		$entry->processRawData($donnees);
		$result = $entry;
	}
	
	$reponse->closeCursor();
	
	return $result;
}

function getItemDefinitionsByWarehouseId($entryId){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_item_definition'] . ' WHERE warehouseId=\''.$entryId.'\'');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new ItemDefinition;
		$entry->processRawData($donnees);
		$results[count($results)] = $entry;
	}
	
	$reponse->closeCursor();
	
	return $results;
}
?>