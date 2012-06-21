function confirmDelete(id){
	var x, dialogue = confirm("Are you sure? You can't undelete it!");
	if(dialogue===true){
		ajaxDelete(id);
		var trid="#payment"+id;
		$(trid).fadeOut("fast");
	}
}

function ajaxDelete(id){
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			//Response Text or fade out etc.
			document.getElementById("balance").innerHTML=xmlhttp.responseText;
		}
	}
	
	xmlhttp.open("GET","delete.php?id="+id,true);
	xmlhttp.send();
}

function editForm(id){
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			//Response Text or fade out etc.
			var trid="payment"+id;
			document.getElementById(trid).innerHTML=xmlhttp.responseText;
		}
	}
	
	xmlhttp.open("GET","editform.php?id="+id,true);
	xmlhttp.send();
}

function doEdit(id){
	var otherparty=document.getElementById("otherparty"+id).value;
	var desc=document.getElementById("desc"+id).value;
	var income=document.getElementById("in"+id).value;
	var out=document.getElementById("out"+id).value;
	var type=document.getElementById("type"+id).value;
	
	var amount=income-out;
	
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			//Response Text or fade out etc.
			var trid="payment"+id;
			document.getElementById(trid).innerHTML=xmlhttp.responseText;
			updateTotal();
		}
	}
	
	xmlhttp.open("GET","doedit.php?id="+id+"&o="+otherparty+"&d="+desc+"&a="+amount+"&t="+type,true);
	xmlhttp.send();
}

function updateTotal(){
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			//Response Text or fade out etc.
			document.getElementById("balance").innerHTML=xmlhttp.responseText;
		}
	}
	
	xmlhttp.open("GET","updatetotal.php",true);
	xmlhttp.send();	
}