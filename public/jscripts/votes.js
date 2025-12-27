$(document).ready(function(){
	if ($("form[name=vote]").length>0) {
		$("form[name=vote]").bind("submit", function(){
			var id = $("form[name=vote] input[name=vt_id]").val();
			var answer = $("form[name=vote] input[name=answer]:checked").val();
			var code = $("form[name=vote] input[name=c]").val();
			console.log("id: "+id+", a: "+answer+", c: "+code)
			$.ajax({
				type: "POST",
				url: "/ajax.php",
				data: { 
					id: id, 
					a: answer, 
					c: code,
					action: 'vote'
					},
				success: function(response, textStatus, jqXHR){
					console.log(response);
					$('#informer_votes').empty().html(response);
				},
				error: function(jqXHR, textStatus, errorThrown){
					console.log("The following error occured: " +  textStatus, errorThrown);
				},
				complete: function(){
				}
			});
			
			return false;
		});
	}
});





function getAnswer(id, radioObj, c){ 
	var a = 0;
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked) a = radioObj.value;
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			a = radioObj[i].value;
		}
	}
	console.log(id+'|'+a+'|'+c)
	var req = getXmlHttp()
	req.open('GET', '/ajax.php?action=vote&id='+id+'&a='+a+'&c='+c, true)
	req.send(null) 
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if(req.status == 200) {
				//alert(req.responseText)
				if (req.responseText!=""){
					document.getElementById('informer_votes').innerHTML = req.responseText;
				}
			}
		}
	}
}