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
	var otherparty=escape(document.getElementById("otherparty"+id).value);
	var desc=escape(document.getElementById("desc"+id).value);
	var income=escape(document.getElementById("in"+id).value);
	var out=escape(document.getElementById("out"+id).value);
	var type=escape(document.getElementById("type"+id).value);
	var day=escape(document.getElementById("day"+id).value);
	var month=escape(document.getElementById("month"+id).value);
	var year=escape(document.getElementById("year"+id).value);
	var account=escape(document.getElementById("account"+id).value);

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
			updateReconcile(document.getElementById("accbal"));
		}
	}
	
	xmlhttp.open("GET","xmlhttp/updatetotal.php",true);
	xmlhttp.send();	
}

function addAccount(){
	var account=escape(document.getElementById("account").value);
	
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
	var currency=escape(document.getElementById("currency").value);
	
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
	var otherparty=escape(document.getElementById("otherparty").value);
	var desc=escape(document.getElementById("desc").value);
	var getorgive=escape(document.getElementById("getorgive").value);
	var amount=escape(document.getElementById("amount").value);
	var type=escape(document.getElementById("type").value);
	var day=escape(document.getElementById("day").value);
	var month=escape(document.getElementById("month").value);
	var year=escape(document.getElementById("year").value);
	var account=escape(document.getElementById("account").value);
	var accsel=escape(document.getElementById("accsel").value);
	var order=getSortValue();
	var field=order.slice(1);
	order=order.slice(0,1);
	var offset=escape(document.getElementById("page").value);
	var recvalue=escape(document.getElementById("accbal").value);
	var perpage=escape(document.getElementById("numperpage").value);
	
	var repeat=document.getElementById("repeat").value;
	if(repeat==="Yes"){
		var rf=escape(document.getElementById("repeatfrequency").value);
		var rt=escape(document.getElementById("repeattimes").value);	
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
			document.getElementById("repeat").value="No";
			document.getElementById("repeatoptions").innerHTML="";
		}
	}
	xmlhttp.open("GET","xmlhttp/addpayment.php?o="+otherparty+"&d="+desc+"&a="+amount+"&t="+type+"&day="+day+"&month="+month+"&year="+year+"&account="+account+"&getorgive="+getorgive+"&accsel="+accsel+"&order="+order+"&rf="+rf+"&rt="+rt+"&offset="+offset+"&recvalue="+recvalue+"&perpage="+perpage+"&field="+field,true);
	xmlhttp.send();	
}

function showAccount(sel){
	var order=getSortValue();
	var field=order.slice(1);
	order=order.slice(0,1);
	var account=escape(sel.value);
	var perpage=escape(document.getElementById("numperpage").value);
	showStatement(account,order,perpage,0,field);
}

function orderStatement(radio){
	var order=escape(radio.value);
	var field=order.slice(1);
	order=order.slice(0,1);
	var account=escape(document.getElementById("accsel").value);
	var perpage=escape(document.getElementById("numperpage").value);
	var offset=escape(document.getElementById("page").value);
	showStatement(account,order,perpage,offset,field);
}

function numPerPage(sel){
	var account=escape(document.getElementById("accsel").value);
	var offset=escape(document.getElementById("page").value);
	var order=getSortValue();
	var field=order.slice(1);
	order=order.slice(0,1);
	var perpage=escape(sel.value);
	showStatement(account,order,perpage,offset,field);
}

function showStatement(account,order,perpage,offset,field){
	var value=escape(document.getElementById("accbal").value);
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
	xmlhttp.open("GET","xmlhttp/showaccount.php?account="+account+"&order="+order+"&perpage="+perpage+"&offset="+offset+"&value="+value+"&field="+field,true);
	xmlhttp.send();	
}

function showPage(sel){
	var account=escape(document.getElementById("accsel").value);
	var order=getSortValue();
	var field=order.slice(1);
	order=order.slice(0,1);
	var perpage=escape(document.getElementById("numperpage").value);
	var offset=escape(sel.value);
	showStatement(account,order,perpage, offset, field);
}

function editAccountForm(id, accountname){
	var divid = "account"+id;
	document.getElementById(divid).innerHTML="<input type='text' name='account"+id+"' id='accountedit"+id+"' value='"+accountname+"'> <button onclick=\"doEditAccount("+id+")\">Confirm Edit</button>";
}

function doEditAccount(id){
	var a=escape(document.getElementById("accountedit"+id).value);
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
	document.getElementById("repeatoptions").innerHTML="<select name='repeatfrequency' id='repeatfrequency'><option value='1'>Daily</option><option value='7'>Weekly</option><option value='m'>Monthly</option></select> For <input type='number' step='1' id='repeattimes' name='repeattimes'>";
}

function reconcile(checkbox, id){
	var account=escape(document.getElementById("accsel").value);
	var value=escape(document.getElementById("accbal").value);
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			//Response Text or fade out etc.
			document.getElementById("reconcilereport").innerHTML=xmlhttp.responseText;
		}
	}
	
	xmlhttp.open("GET","xmlhttp/reconcile.php?id="+id+"&c="+checkbox.checked+"&account="+account+"&value="+value,true);
	xmlhttp.send();
}

function updateReconcile(input){
	var value=escape(input.value);
	var account=escape(document.getElementById("accsel").value);
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			//Response Text or fade out etc.
			document.getElementById("updaterec").innerHTML=xmlhttp.responseText;
		}
	}
	
	xmlhttp.open("GET","xmlhttp/updatereconcile.php?account="+account+"&value="+value,true);
	xmlhttp.send();
}

function getSortValue(){
	for (var i=0; i < document.sortbuttons.sort.length; i++){
		if (document.sortbuttons.sort[i].checked){
			return document.sortbuttons.sort[i].value;
		}
	}
}

function otherAccountSelect(){
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			//Response Text or fade out etc.
			document.getElementById("tofrom").innerHTML=xmlhttp.responseText;
		}
	}
	
	xmlhttp.open("GET","xmlhttp/accountselect.php",true);
	xmlhttp.send();
}

function otherParty(){
	document.getElementById("tofrom").innerHTML="<input type='text' name='otherparty' id='otherparty'><span onclick=\"otherAccountSelect()\">Another of your accounts?</span>";	
}