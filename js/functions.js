function confirmDelete(id){
	var dialogue = confirm("Are you sure? You can't undelete it!");
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
			if(xmlhttp.responseText!=""){
				var dialogue = confirm("You just deleated a repeating entry, do you want to stop future repeats?");
				if(dialogue===true){
					deleteRepeat(xmlhttp.responseText);
				}
			}
			updateTotal();
		}
	}
	
	xmlhttp.open("GET","xmlhttp/delete.php?id="+id,true);
	xmlhttp.send();
}

function deleteRepeat(id){
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			//Response Text or fade out etc.
		}
	}
	
	xmlhttp.open("GET","xmlhttp/deleterepeat.php?id="+id,true);
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
	var accsel=document.getElementById("accsel").value;
	var order=document.getElementById("datesort0").checked;
	if(order===true){
		order=0;	
	}else{
		order=1;	
	}
	var repeat=document.getElementById("repeat").value;
	if(repeat==="Yes"){
		var rf=document.getElementById("repeatfrequency").value;
		var rt=document.getElementById("repeattimes").value;	
	}
	
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
	xmlhttp.open("GET","xmlhttp/addpayment.php?o="+otherparty+"&d="+desc+"&a="+amount+"&t="+type+"&day="+day+"&month="+month+"&year="+year+"&account="+account+"&getorgive="+getorgive+"&accsel="+accsel+"&order="+order+"&rf="+rf+"&rt="+rt,true);
	xmlhttp.send();	
}

function showAccount(sel){
	var account=sel.value;
	var order=document.getElementById("datesort0").checked;
	if(order===true){
		order=0;	
	}else{
		order=1;	
	}
	var perpage=document.getElementById("numperpage").value;
	showStatement(account,order,perpage);
}

function orderStatement(radio, field){
	var order=radio.value;
	var account=document.getElementById("accsel").value;
	var perpage=document.getElementById("numperpage").value;
	showStatement(account,order,perpage);
}

function numPerPage(sel){
	var account=document.getElementById("accsel").value;
	var order=document.getElementById("datesort0").checked;
	if(order===true){
		order=0;	
	}else{
		order=1;	
	}
	var perpage=sel.value;
	showStatement(account,order,perpage);
}

function showStatement(account, order, perpage, offset){
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
	xmlhttp.open("GET","xmlhttp/showaccount.php?account="+account+"&order="+order+"&perpage="+perpage+"&offset="+offset,true);
	xmlhttp.send();	
}

function showPage(sel){
	var account=document.getElementById("accsel").value;
	var order=document.getElementById("datesort0").checked;
	if(order===true){
		order=0;	
	}else{
		order=1;	
	}
	var perpage=document.getElementById("numperpage").value;
	var offset=sel.value;
	showStatement(account,order,perpage, offset);
}

function editAccountForm(id, accountname){
	var divid = "account"+id;
	document.getElementById(divid).innerHTML="<input type='text' name='account"+id+"' id='accountedit"+id+"' value='"+accountname+"'> <button onclick=\"doEditAccount("+id+")\">Confirm Edit</button>";
}

function doEditAccount(id){
	var a=document.getElementById("accountedit"+id).value;
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			//Response Text or fade out etc.
			var divid = "account"+id;
			document.getElementById(divid).innerHTML=xmlhttp.responseText;
		}
	}
	
	xmlhttp.open("GET","xmlhttp/doeditaccount.php?id="+id+"&a="+a,true);
	xmlhttp.send();
}

function showRepeatOptions(){
	document.getElementById("repeatoptions").innerHTML="<select name='repeatfrequency' id='repeatfrequency'><option value='1'>Daily</option><option value='7'>Weekly</option><option value='31'>Monthly</option></select> For <input type='number' step='1' id='repeattimes' name='repeattimes'>";
}