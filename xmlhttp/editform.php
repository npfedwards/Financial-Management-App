<?php
	include_once '../functions.php';
	checklogin();
	$conn=opendb();
	
	if($loggedin==1){
		$id=mysql_real_escape_string($_GET['id']);
		
		$query="SELECT * FROM payments WHERE UserID='$user' AND PaymentID='$id' AND Deleted='0' ";
		$result=mysql_query($query) or die(mysql_error());
		$row=mysql_fetch_assoc($result);
		
		$amount=$row['PaymentAmount'];
		if($amount<0){
			$amount="<input type='number' step='0.01' name='in' id='in".$row['PaymentID']."'></td><td><input type='number' step='0.01' name='out' id='out".$row['PaymentID']."' value='".$amount*(-1)."' class='red'>";
		}else{
			$amount="<input type='number' step='0.01' name='in' id='in".$row['PaymentID']."' value='".$amount."'></td><td><input type='number' step='0.01' name='out' id='out".$row['PaymentID']."' class='red'>";
		}
		
		echo "<td><select name='day' id='day".$row['PaymentID']."'>";
					$i=0;
					while($i<31){
						$i++;
						echo "<option value='".$i."'";
						if($i==date("j",$row['Timestamp'])){
							echo " selected='selected'";
						}
						echo ">".$i.date("S", strtotime("01/".$i."/2000"))."</option>";
					}
						
			echo		"</select><select name='month' id='month".$row['PaymentID']."'>";
					$i=0;
					while($i<12){
						$i++;
						echo "<option value='".$i."'";
						if($i==date("n",$row['Timestamp'])){
							echo " selected='selected'";
						}
						echo ">".date("M", strtotime($i."/01/2000"))."</option>";
					}
						
			echo		"</select><select name='year' id='year".$row['PaymentID']."'>";
					$i=2009;
					while($i<date("Y")+2){
						$i++;
						echo "<option value='".$i."'";
						if($i==date("Y",$row['Timestamp'])){
							echo " selected='selected'";
						}
						echo ">".$i."</option>";
					}
						
			echo		"</select></td>
			  <td>";
			  if($row['ToAccount']!=0){
				echo "<select id='toaccount".$row['PaymentID']."'>";
				$query="SELECT * FROM accounts WHERE UserID='$user'";
				$result2=mysql_query($query) or die(mysql_error());
				while($row2=mysql_fetch_assoc($result2)){
					echo "<option value='".$row2['AccountID']."'";
					if($row['ToAccount']==$row2['AccountID']){
						echo " selected='selected'";	
					}
					echo ">".stripslashes($row2['AccountName'])."</option>";
			  	}
				echo "</select><input type='hidden' id='otherparty".$row['PaymentID']."' value='0'>";
			  }else{
				echo "<input type='text' name='otherparty' id='otherparty".$row['PaymentID']."' value='".htmlspecialchars($row['PaymentName'],ENT_QUOTES)."'><input type='hidden' id='toaccount".$row['PaymentID']."' value='0'>";
			  }
		echo 	"</td>
			  <td><input type='text' name='desc' id='desc".$row['PaymentID']."' value='".htmlspecialchars($row['PaymentDesc'], ENT_QUOTES)."'></td>
			  <td>".$amount."</td>
			  <td>
				<select name='type' id='type".$row['PaymentID']."'>
					<option";
					if($row['PaymentType']=="Cheque"){ echo " selected='selected'";}
		echo		">Cheque</option>
					<option";
					if($row['PaymentType']=="Card"){ echo " selected='selected'";}
		echo		">Card</option>
					<option";
					if($row['PaymentType']=="Cash"){ echo " selected='selected'";}
		echo		">Cash</option>
					<option";
					if($row['PaymentType']=="Transfer"){ echo " selected='selected'";}
		echo		">Transfer</option>
					<option";
					if($row['PaymentType']=="Direct Debit"){ echo " selected='selected'";}
		echo		">Direct Debit</option>
					<option";
					if($row['PaymentType']=="Standing Order"){ echo " selected='selected'";}
		echo		">Standing Order</option>
				</select>
			  </td><td>
				<select name='account' id='account".$row['PaymentID']."'>";
			  $query="SELECT * FROM accounts WHERE UserID='$user'";
			  $result2=mysql_query($query) or die(mysql_error());
			  while($row2=mysql_fetch_assoc($result2)){
					echo "<option value='".$row2['AccountID']."'";
					if($row['AccountID']==$row2['AccountID']){
						echo " selected='selected'";	
					}
					echo ">".stripslashes($row2['AccountName'])."</option>";
			  }
		echo  	"</select>
			</td><td>
				<select name='labels' id='labelselect".$row['PaymentID']."'>
					<option value='0'";
					if($row['LabelID']==0){
						echo " selected='selected'";	
					}
		echo		">No Label</option>";
					$query="SELECT * FROM labels WHERE UserID='$user'";
					$result2=mysql_query($query) or die(mysql_error());
				
				while($row2=mysql_fetch_assoc($result2)){
					echo "<option value='".$row2['LabelID']."' style='color:".$row2['Colour']."'";
					if($row['LabelID']==$row2['LabelID']){
						echo " selected='selected'";	
					}
		echo		">".stripslashes($row2['LabelName'])."</option>";	
				}
	echo		"</select>
			  </td><td><input type='checkbox' id='reconciled' onclick=\"reconcile(this, ".$row['PaymentID'].")\"";
			if($row['Reconciled']==1){
					echo "checked='checked'";
			}
			echo		"></td><td>
				  <button onclick=\"confirmDelete('".$row['PaymentID']."')\">Delete</button>
				  <button onclick=\"doEdit('".$row['PaymentID']."')\">Confirm Edit</button>
			  </td>";
	}else{
		loginform();
	}
	
	
?>