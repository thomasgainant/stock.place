<?php
$GLOBALS["Log_paramsAsString"] = 'logType, content, dateCreated, author, warehouseId';
$GLOBALS["Log_paramsValuesAsString"] = ':logType, :content, :dateCreated, :author, :warehouseId';
$GLOBALS["Log_paramsUpdateAsString"] = 'logType = :logType, content = :content, dateCreated = :dateCreated, author = :author, warehouseId = :warehouseId';

class Log{
    var $id;
	function getId(){
		return $this->id;
	}
	function setId($newId){
		$this->id = $newId;
	}
	
	var $logType;	
	function getLogType(){
		return $this->logType;
	}
	function setLogType($newId){
		$this->logType = $newId;
	}
	
	var $content;	
	function getContent(){
		return $this->content;
	}
	function setContent($newId){
		$this->content = $newId;
	}
	
	var $dateCreated;	
	function getDateCreated(){
		return $this->dateCreated;
	}
	function setDateCreated($newId){
		$this->dateCreated = $newId;
	}
	
	var $author;	
	function getAuthor(){
		return $this->author;
	}
	function setAuthor($newId){
		$this->author = $newId;
	}
	
	var $warehouseId;	
	function getWarehouseId(){
		return $this->warehouseId;
	}
	function setWarehouseId($newId){
		$this->warehouseId = $newId;
	}
	
	function processRawData($rawData){
		if(isset($rawData['id']) && !empty($rawData['id'])){
			$this->id = $rawData['id'];
		}
		
		if(isset($rawData['logType']) && !empty($rawData['logType'])){
			$this->logType = $rawData['logType'];
		}
		
		if(isset($rawData['content']) && !empty($rawData['content'])){
			$this->content = $rawData['content'];
		}
		
		if(isset($rawData['dateCreated']) && !empty($rawData['dateCreated'])){
			$this->dateCreated = $rawData['dateCreated'];
		}
		
		if(isset($rawData['author']) && !empty($rawData['author'])){
			$this->author = $rawData['author'];
		}
		
		if(isset($rawData['author']) && !empty($rawData['author'])){
			$this->author = $rawData['author'];
		}
	}
	
	function getRawDataWithId(){
		return array(
			'id' => $this->id,
			'logType' => $this->logType,
			'content' => $this->content,
			'dateCreated' => $this->dateCreated,
			'author' => $this->author,
			'warehouseId' => $this->warehouseId
		);
	}
	
	function getRawData(){
		return array(
			'logType' => $this->logType,
			'content' => $this->content,
			'dateCreated' => $this->dateCreated,
			'author' => $this->author,
			'warehouseId' => $this->warehouseId
		);
	}
}

function addLog($logType, $content, $author, $warehouseId){
	$entry = new Log;
	$entry->logType = $logType;
	$entry->content = $content;
	$entry->dateCreated = date("Y-m-d H:i:s");
	$entry->author = $author;
	$entry->warehouseId = $warehouseId;

	$req = $GLOBALS['bdd']->prepare('INSERT INTO '. $GLOBALS['bdd_logs'] .'('.$GLOBALS["Log_paramsAsString"].')
	VALUES('.$GLOBALS["Log_paramsValuesAsString"].')');
	$req->execute($entry->getRawData());
	
	return "";
}

function editLog($id, $logType, $content, $dateCreated, $author, $warehouseId){
	$entry = new Log;
	$entry->id = $id;
	$entry->logType = $logType;
	$entry->content = $content;
	$entry->dateCreated = $dateCreated;
	$entry->author = $author;
	$entry->warehouseId = $warehouseId;

	$req = $GLOBALS['bdd']->prepare('UPDATE '. $GLOBALS['bdd_logs'] . ' SET ' . $GLOBALS["Log_paramsUpdateAsString"] . ' WHERE id = :id');
	$req->execute($entry->getRawDataWithId());
	
	return "";
}

function deleteLog($id){	
	$req = $GLOBALS['bdd']->query('DELETE FROM '. $GLOBALS['bdd_logs'] .' WHERE id=' . $id);
	return "";
}

function getLogById($id){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_logs'] . ' WHERE id=\'' . $id . '\'');
	
	$result = null;
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new Log;
		$entry->processRawData($donnees);
		$result = $entry;
	}
	
	$reponse->closeCursor();
	
	return $result;
}

function getLogsByWarehouseId($warehouseId){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_logs'] . ' WHERE warehouseId=\''.$warehouseId.'\'');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new Log;
		$entry->processRawData($donnees);
		$results[count($results)] = $entry;
	}
	
	$reponse->closeCursor();
	
	return $results;
}

function getLogsByWarehouseIdSortedByDateAsc($warehouseId){
	$reponse = $GLOBALS['bdd']->query('SELECT * FROM '. $GLOBALS['bdd_logs'] . ' WHERE warehouseId=\''.$warehouseId.'\' ORDER BY dateCreated ASC');
	
	$results = array();
	// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
		$entry = new Log;
		$entry->processRawData($donnees);
		$results[count($results)] = $entry;
	}
	
	$reponse->closeCursor();
	
	return $results;
}
?>