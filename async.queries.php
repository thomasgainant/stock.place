<?php
	include('engine/engine.php');
	connectToDB();
	
	if(isset($_POST['query']) && !empty($_POST['query'])){
		$type = antiInjectionCrypt($_POST['query']);
		
		if($type == "get-item-infos" && isset($_POST['get-item-infos-reference']) && !empty($_POST['get-item-infos-reference']) && isset($_POST['get-item-infos-warehouse-id']) && !empty($_POST['get-item-infos-warehouse-id'])){
			$warehouseId = antiInjectionCrypt($_POST['get-item-infos-warehouse-id']);
			$reference = antiInjectionCrypt($_POST['get-item-infos-reference']);
			
			$items = getItemsByWarehouseIdAndReference($warehouseId, $reference);
			$exampleItem = null;
			if(count($items) > 0){
				$exampleItem = $items[0];
			}
			
			if($exampleItem != null){				
				echo $exampleItem->warehouseId . ':|:';
				echo $exampleItem->definitionId . ':|:';				
				echo $exampleItem->name . ':|:';				
				echo $exampleItem->parameters . ':|:';				
				echo $exampleItem->dateCreated . ':|:';				
				echo $exampleItem->status;				
			}
		}
	}
	
	disconnectFromDB();
?>