function confirmDelete(id){
	var dialogue = confirm("Are you sure? You can't undelete it!");
	if(dialogue===true){
		ajaxDelete(id);
	}
}

function ajaxDelete(id){
	function a(){
		//Response Text or fade out etc.
		if(xmlhttp.responseText!=""){
			var dialogue = confirm("You just deleated a repeating entry, do you want to delete all subsequent repeats?");
			if(dialogue===true){
				deleteRepeat(xmlhttp.responseText, id);
			}
		}
		showWithOffset();
	}
	ajaxRequest("xmlhttp/delete.php?id="+id, a);
}

function deleteRepeat(id, payid){
	function a(){}
	ajaxRequest("xmlhttp/deleterepeat.php?id="+id+"&payid="+payid, a);
}


function editForm(id){
	function a(id){
		var trid="payment"+id;
		document.getElementById(trid).innerHTML=xmlhttp.responseText;
	}
	
	ajaxRequest("xmlhttp/editform.php?id="+id, a, id);
}

function doEdit(id){
	var otherparty=escape(document.getElementById("otherparty"+id).value);
	var toaccount=escape(document.getElementById("toaccount"+id).value);
	var desc=escape(document.getElementById("desc"+id).value);
	var income=escape(document.getElementById("in"+id).value);
	var out=escape(document.getElementById("out"+id).value);
	var type=escape(document.getElementById("type"+id).value);
	var day=escape(document.getElementById("day"+id).value);
	var month=escape(document.getElementById("month"+id).value);
	var year=escape(document.getElementById("year"+id).value);
	var account=escape(document.getElementById("account"+id).value);
	var label=escape(document.getElementById("labelselect"+id).value);

	var amount=income-out;
	function a(id){
		var trid="payment"+id;
		document.getElementById(trid).innerHTML=xmlhttp.responseText;
		if(toaccount!=0){
			updatePairedPayment(id);
		}else{
			showWithOffset();
		}
	}
	ajaxRequest("xmlhttp/doedit.php?id="+id+"&o="+otherparty+"&d="+desc+"&a="+amount+"&t="+type+"&day="+day+"&month="+month+"&year="+year+"&account="+account+"&toaccount="+toaccount+"&label="+label, a, id);
}

function updateTotal(){
	function a(){
		document.getElementById("balance").innerHTML=xmlhttp.responseText;
		updateReconcile(document.getElementById("accbal"));
	}
	ajaxRequest("xmlhttp/updatetotal.php", a);
}

function addAccount(){
	var account=escape(document.getElementById("account").value);
	function a(){
		document.getElementById("accounts").innerHTML=xmlhttp.responseText;
		document.getElementById("account").value="";
	}
	ajaxRequest("xmlhttp/addaccount.php?account="+account, a);
}

function updateCurrency(){
	var currency=escape(document.getElementById("currency").value);
	function a(){
		displayFeedback(xmlhttp.responseText)
	}
	ajaxRequest("xmlhttp/updatecurrency.php?currency="+currency, a);
}

function displayFeedback(message, type) {
	/*	USAGE:
		message is a string (can be html) to be displayed in box
		type is the type of message to be shown.
			defaults to success (green).
			passing 'error' here makes the box red.
	*/
	//Check if there's already a timer running, and cancel it so that this message isn't cut off prematurely:
	if (typeof t!='undefined') {clearTimeout(t);}
	//check what type of message:
	if (type==='error') {
		document.getElementById('feedbackbox').className='error';
	} else{
		document.getElementById('feedbackbox').className='success';
	}
	//set the message contents
	document.getElementById('feedbackcontainer').innerHTML=message;
	//show the feedback
	document.getElementById('feedbackbox').style.display='block';
	//set the box to disappear in 5(ish) seconds...
	t = window.setTimeout(function() {document.getElementById('feedbackbox').style.display='none';}, 5000);
}


function updatePaymentMethod(){
	var method=escape(document.getElementById("paymentmethod").value);
	function a(){
		displayFeedback(xmlhttp.responseText)
	}
	ajaxRequest("xmlhttp/updatepaymentmethod.php?pm="+method, a);
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
	var label=escape(document.getElementById("labelselect").value);
	
	var repeat=document.getElementById("repeat").value;
	if(repeat==="Yes"){
		var rf=escape(document.getElementById("repeatfrequency").value);
		var rt=escape(document.getElementById("repeattimes").value);	
	}
	
	function a(){
		document.getElementById("statementhold").innerHTML=xmlhttp.responseText;
		document.getElementById("otherparty").value="";
		document.getElementById("amount").value="";
		document.getElementById("desc").value="";
		document.getElementById("repeat").value="No";
		document.getElementById("repeatoptions").innerHTML="";
	}
	ajaxRequest("xmlhttp/addpayment.php?o="+otherparty+"&d="+desc+"&a="+amount+"&t="+type+"&day="+day+"&month="+month+"&year="+year+"&account="+account+"&getorgive="+getorgive+"&accsel="+accsel+"&order="+order+"&rf="+rf+"&rt="+rt+"&offset="+offset+"&recvalue="+recvalue+"&perpage="+perpage+"&field="+field+"&label="+label, a);
}

function showWithOffset(){
	var offset=escape(document.getElementById("page").value);
	showStatement(offset);
}

function showStatement(offset){
	var order=getSortValue();
	var field=order.slice(1);
	order=order.slice(0,1);
	var account=escape(document.getElementById("accsel").value);
	var perpage=escape(document.getElementById("numperpage").value);
	var value=escape(document.getElementById("accbal").value);
	var sd=escape(document.getElementById("startday").value);
	var sm=escape(document.getElementById("startmonth").value);
	var sy=escape(document.getElementById("startyear").value);
	var ed=escape(document.getElementById("endday").value);
	var em=escape(document.getElementById("endmonth").value);
	var ey=escape(document.getElementById("endyear").value);
	
	function a(){
		document.getElementById("statementhold").innerHTML=xmlhttp.responseText;
	}
	ajaxRequest("xmlhttp/showaccount.php?sd="+sd+"&sm="+sm+"&sy="+sy+"&ed="+ed+"&em="+em+"&ey="+ey+"&account="+account+"&order="+order+"&perpage="+perpage+"&offset="+offset+"&value="+value+"&field="+field, a);
}


function editAccountForm(id, accountname){
	var divid = "account"+id;
	document.getElementById(divid).innerHTML="<td><input type='text' name='account"+id+"' id='accountedit"+id+"' value='"+accountname+"'></td><td><button onclick=\"doEditAccount("+id+")\">Confirm Edit</button></td>";
}

function doEditAccount(id){
	var a=escape(document.getElementById("accountedit"+id).value);
	function b(id){
		var divid = "account"+id;
		document.getElementById(divid).innerHTML=xmlhttp.responseText;
	}
	ajaxRequest("xmlhttp/doeditaccount.php?id="+id+"&a="+a, b, id);
}

function showHideRepeatOptions(sel){
	if(sel.value==='Yes'){
		document.getElementById("repeatoptions").innerHTML="<select name='repeatfrequency' id='repeatfrequency'><option value='1'>Daily</option><option value='7'>Weekly</option><option value='m'>Monthly</option></select> For <input type='number' step='1' id='repeattimes' name='repeattimes'>";
	}else{
		document.getElementById("repeatoptions").innerHTML="";
	}
}

function reconcile(checkbox, id){
	var account=escape(document.getElementById("accsel").value);
	var value=escape(document.getElementById("accbal").value);
	function a(){
		document.getElementById("reconcilereport").innerHTML=xmlhttp.responseText;
	}
	ajaxRequest("xmlhttp/reconcile.php?id="+id+"&c="+checkbox.checked+"&account="+account+"&value="+value, a);
}

function updateReconcile(input){
	var value=escape(input.value);
	var account=escape(document.getElementById("accsel").value);
	function a(){
		document.getElementById("updaterec").innerHTML=xmlhttp.responseText;
	}
	ajaxRequest("xmlhttp/updatereconcile.php?account="+account+"&value="+value, a);
}

function getSortValue(){
	for (var i=0; i < document.sortbuttons.sort.length; i++){
		if (document.sortbuttons.sort[i].checked){
			return document.sortbuttons.sort[i].value;
		}
	}
}

function otherAccountSelect(){
	function a(){
		document.getElementById("tofrom").innerHTML=xmlhttp.responseText;
	}
	var selectedIndex = document.getElementById("type").selectedIndex;
	ajaxRequest("xmlhttp/accountselect.php?i="+selectedIndex, a);
	document.getElementById("type").selectedIndex=3;
}

function otherParty(index){
	document.getElementById("tofrom").innerHTML="<input type='text' name='otherparty' id='otherparty'><span onclick=\"otherAccountSelect()\" class='clickable'>Another of your accounts?</span>";	
	document.getElementById("type").selectedIndex=index;
}

function updatePairedPayment(id){
	function a(){
		updatePayment(xmlhttp.responseText);
	}
	ajaxRequest("xmlhttp/getpairedid.php?id="+id, a);
}

function updatePayment(id){
	function a(id){
		var trid="payment"+id;
		document.getElementById(trid).innerHTML=xmlhttp.responseText;
		updateTotal();
	}
	ajaxRequest("xmlhttp/getpayment.php?id="+id, a, id);
}

function deleteAccount(id){
	var dialogue = confirm("Are you sure you want to delete this account? This deletes all corresponding transactions");
	if(dialogue===true){
		doDeleteAccount(id);
	}	
}

function doDeleteAccount(id){
	function a(){
		var trid="account"+id;
		document.getElementById(trid).innerHTML=xmlhttp.responseText;
	}
	ajaxRequest("xmlhttp/deleteaccount.php?id="+id, a);	
}

function archiveAccount(id,archive){
	function a(){
		var trid="account"+id;
		document.getElementById(trid).innerHTML=xmlhttp.responseText;
	}
	ajaxRequest("xmlhttp/archiveaccount.php?id="+id+"&archive="+archive, a);	
}

function ajaxRequest(url, callbackfunction, param1){
	document.getElementById("loadingbox").style.display='block';
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			//Response Text or fade out etc.
			callbackfunction(param1);
			document.getElementById("loadingbox").style.display='none';
		}
	}
	xmlhttp.open("GET",url,true);
	xmlhttp.send();
}

function addLabel(){
	var label = escape(document.getElementById('addlabel').value);
	function a(){
		document.getElementById('labels').innerHTML=xmlhttp.responseText;
		document.getElementById('addlabel').value="";
	}
	ajaxRequest('xmlhttp/addlabel.php?label='+label, a);
}

function editBudget(id, budget){
	function a(){}
	ajaxRequest('xmlhttp/editbudget.php?id='+id+'&budget='+budget, a);
}