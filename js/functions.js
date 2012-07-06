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
	
	xmlhttp.open("GET","xmlhttp/delete.php?id="+id,true);
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
	
	xmlhttp.open("GET","xmlhttp/editform.php?id="+id,true);
	xmlhttp.send();
}

function doEdit(id){
	var otherparty=document.getElementById("otherparty"+id).value;
	var desc=document.getElementById("desc"+id).value;
	var income=document.getElementById("in"+id).value;
	var out=document.getElementById("out"+id).value;
	var type=document.getElementById("type"+id).value;
	var day=document.getElementById("day"+id).value;
	var month=document.getElementById("month"+id).value;
	var year=document.getElementById("year"+id).value;
	var account=document.getElementById("account"+id).value;

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
	
	xmlhttp.open("GET","xmlhttp/doedit.php?id="+id+"&o="+otherparty+"&d="+desc+"&a="+amount+"&t="+type+"&day="+day+"&month="+month+"&year="+year+"&account="+account,true);
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
	
	xmlhttp.open("GET","xmlhttp/updatetotal.php",true);
	xmlhttp.send();	
}

function addAccount(){
	var account=document.getElementById("account").value;
	
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			//Response Text or fade out etc.
			document.getElementById('accounts').innerHTML=xmlhttp.responseText;
			document.getElementById("account").value="";
		}
	}
	
	xmlhttp.open("GET","xmlhttp/addaccount.php?account="+account,true);
	xmlhttp.send();
}

function updateCurrency(){
	var currency=document.getElementById("currency").value;
	
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			//Response Text or fade out etc.
			document.getElementById('currencycontainer').innerHTML=xmlhttp.responseText;
		}
	}
	
	xmlhttp.open("GET","xmlhttp/updatecurrency.php?currency="+currency,true);
	xmlhttp.send();
}

function addAccountEnter(e){
	if(checkEnter(e)){
		addAccount();	
	}
}

function checkEnter(e) {
    var charCode;
    
    if(e && e.which){
        charCode = e.which;
    }else if(window.event){
        e = window.event;
        charCode = e.keyCode;
    }

    if(charCode === 13) {
        return true;
    }else{
		return false;	
	}
}

function addPaymentEnter(e){
	if(checkEnter(e)){
		addPayment();	
	}
}

function addPayment(){
	var otherparty=document.getElementById("otherparty").value;
	var desc=document.getElementById("desc").value;
	var getorgive=document.getElementById("getorgive").value;
	var amount=document.getElementById("amount").value;
	var type=document.getElementById("type").value;
	var day=document.getElementById("day").value;
	var month=document.getElementById("month").value;
	var year=document.getElementById("year").value;
	var account=document.getElementById("account").value;
	
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			//Response Text or fade out etc.
			document.getElementById("statementhold").innerHTML=xmlhttp.responseText;
			document.getElementById("otherparty").value="";
			document.getElementById("amount").value="";
			document.getElementById("desc").value="";
		}
	}
	xmlhttp.open("GET","xmlhttp/addpayment.php?o="+otherparty+"&d="+desc+"&a="+amount+"&t="+type+"&day="+day+"&month="+month+"&year="+year+"&account="+account+"&getorgive="+getorgive,true);
	xmlhttp.send();	
}

function showAccount(sel){
	var account=sel.value;
	
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			//Response Text or fade out etc.
			document.getElementById("statementhold").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","xmlhttp/showaccount.php?account="+account,true);
	xmlhttp.send();
}