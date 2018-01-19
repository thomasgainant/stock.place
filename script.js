$(document).ready(function(){
	$(".popup-close").click(function(event){
		event.preventDefault();
		$(this).parent().parent().fadeOut();
	});
	
	/*$(".popup").click(function(event){
		event.preventDefault();
		if($(this).css("display") != "none"){
			$(this).fadeOut();
		}
	});*/
	
	$(".inscription-clic").click(function(event){
		event.preventDefault();
		$("#popup-inscription").fadeIn();
	});
	
	$(".connexion-clic").click(function(event){
		event.preventDefault();
		$("#popup-connexion").fadeIn();
	});
	
	$(".icon-ajout").click(function(event){
		event.preventDefault();
		$("#popup-ajout").fadeIn();
	});
	
	$(".icon-retrait").click(function(event){
		event.preventDefault();
		$("#popup-retrait").fadeIn();
	});
	
	$(".icon-descr-edit").click(function(event){
		event.preventDefault();
		$("#popup-descr-edit").fadeIn();
	});
	
	$(".icon-editer").click(function(event){
		event.preventDefault();
		var productReference = "";
		productReference = $(this).attr("href");
		productReference = productReference.substring(1);
		$("#popup-item-edit-"+productReference).fadeIn();
	});
	
	$(".icon-supprimer").click(function(event){
		event.preventDefault();
		var productReference = "";
		productReference = $(this).attr("href");
		productReference = productReference.substring(1);
		$("#popup-item-delete-"+productReference).fadeIn();
	});
	
	if($('#popup-info').find('.popup-content').text() != ""){
		$('#popup-info').fadeIn();
	}
	
	if($('#popup-error').find('.popup-content').text() != ""){
		$('#popup-error').fadeIn();
	}
	
	$(".add-products-reference-selector").change(function() {
		var selectorObject = $(this);
		var referenceValue = $(this).val();
		//alert(referenceValue);
		//alert(warehouseId);
		$.ajax({
			method: "POST",
			url: "async.queries.php",
			data: { "query": "get-item-infos", "get-item-infos-reference": ""+referenceValue, "get-item-infos-warehouse-id": ""+warehouseId }
		})
		.done(function( msg ) {
			//alert( "Data Saved: " + msg );
			var datas = msg.split(":|:");
			var parametersRaw = datas[3];
			var parameters = parametersRaw.split("|");
			
			var currentAttr = 0;
			for(var i = 0; i < parameters.length; i++){
				//alert($(selectorObject).parent().parent().find("td:nth-child("+i+")").find("input[type=text]").attr("value"));
				//alert($(selectorObject).parent().parent().find("td:nth-child("+i+")").find("input[type=text]").prop("tagName"));
				var object = $(selectorObject).parent().parent().find("td:nth-child("+i+") input[type=text]");
				
				if(object != null && $(object).prop("tagName") != "undefined" && ($(object).prop("tagName") == "INPUT" || $(object).prop("tagName") == "input")){
					$(object).attr("value", parameters[currentAttr]);
					currentAttr++;
				}
				/*else{
					alert(i);
				}*/
				//alert($(selectorObject).parent().parent().nextAll("*:lt("+i+")").prop("tagName"));
			}
		});
	});
});