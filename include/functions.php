<?php
// funkcijos  include/functions.php

function inisession($arg) {   //valom sesijos kintamuosius
            if($arg =="full"){
                $_SESSION['message']="";
                $_SESSION['user']="";
	       		$_SESSION['ulevel']=0;
				$_SESSION['userid']=0;
				$_SESSION['umail']=0;
            }			    	 
		$_SESSION['name_login']="";
		$_SESSION['pass_login']="";
		$_SESSION['mail_login']="";
		$_SESSION['name_error']="";
      	$_SESSION['pass_error']="";
		$_SESSION['mail_error']=""; 
        }

function checkname ($vardasPavarde){   // Vartotojo vardo sintakse
	   if (!$vardasPavarde || strlen($vardasPavarde = trim($vardasPavarde)) == 0) 
			{$_SESSION['name_error']=
				 "<font size=\"2\" color=\"#ff0000\">* Neįvestas vartotojo vardas</font>";
			 "";
			 return false;}
            elseif (!preg_match("/^([0-9a-zA-Z])*$/", $vardasPavarde))  /* Check if username is not alphanumeric */ 
			{$_SESSION['name_error']=
				"<font size=\"2\" color=\"#ff0000\">* Vartotojo vardas gali būti sudarytas<br>
				&nbsp;&nbsp;tik iš raidžių ir skaičių</font>";
		     return false;}
	        else return true;
   }
             
 function checkpass($pwd,$dbpwd) {     //  slaptazodzio tikrinimas (tik demo: min 4 raides ir/ar skaiciai) ir ar sutampa su DB esanciu
	   if (!$pwd || strlen($pwd = trim($pwd)) == 0) 
			{$_SESSION['pass_error']=
			  "<font size=\"2\" color=\"#ff0000\">* Neįvestas slaptažodis</font>";
			 return false;}
            elseif (!preg_match("/^([0-9a-zA-Z])*$/", $pwd))  /* Check if $pass is not alphanumeric */ 
			{$_SESSION['pass_error']="* Čia slaptažodis gali būti sudarytas<br>&nbsp;&nbsp;tik iš raidžių ir skaičių";
		     return false;}
            elseif (strlen($pwd)<4)  // per trumpas
			         {$_SESSION['pass_error']=
						  "<font size=\"2\" color=\"#ff0000\">* Slaptažodžio ilgis <4 simbolius</font>";
		              return false;}
	          elseif ($dbpwd != substr(hash( 'sha256', $pwd ),5,32))
               {$_SESSION['pass_error']=
				   "<font size=\"2\" color=\"#ff0000\">* Neteisingas slaptažodis</font>";
                return false;}
            else return true;
   }

 function checkdb($username) {  // iesko DB pagal varda, grazina {vardas,slaptazodis,lygis,id} ir nustato name_error
		 $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		 $sql = "SELECT * FROM " . TBL_USERS. " WHERE vardasPavarde = '$username'";
		 $result = mysqli_query($db, $sql);
	     $uname = $upass = $ulevel = $uid = $umail = null;
		 if (!$result || (mysqli_num_rows($result) != 1))   // jei >1 tai DB vardas kartojasi, netikrinu, imu pirma
	  	 {  // neradom vartotojo DB
		    $_SESSION['name_error']=
			 "<font size=\"2\" color=\"#ff0000\">* Tokio vartotojo nėra</font>";
         }
      else {  //vardas yra DB
           $row = mysqli_fetch_assoc($result); 
           $uname= $row["vardasPavarde"]; $upass= $row["slaptazodis"]; 
           $ulevel=$row["pareigos"]; $uid= $row["vartotojoID"]; $umail = $row["elPastas"];}
     return array($uname,$upass,$ulevel,$uid,$umail);
 }

function checkmail($mail) {   // e-mail sintax error checking  
	   if (!$mail || strlen($mail = trim($mail)) == 0) 
			{$_SESSION['mail_error']=
				"<font size=\"2\" color=\"#ff0000\">* Neįvestas e-pašto adresas</font>";
			   return false;}
            elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) 
			      {$_SESSION['mail_error']=
					   "<font size=\"2\" color=\"#ff0000\">* Neteisingas e-pašto adreso formatas</font>";
		            return false;}
	        else return true;
   }

function suma($vartotojoID){
		$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	
		$suma = 0;
		$sql = "SELECT * FROM " . TBL_SASK. " WHERE vartotojoID_fk = '$vartotojoID'";
		$result = mysqli_query($db, $sql);
		while($row = $result->fetch_assoc()) 
		{
			$sql2 = "SELECT * FROM " . TBL_PREK. " WHERE sask_fk = ".$row['serijosNr']."";
			$result2 = mysqli_query($db, $sql2);
			while($row2 = $result2->fetch_assoc()) 
			{
				$suma = $suma + ($row2['kiekis'] * $row2['vntKaina']);
			}
		}

		return $suma;
}

function balansas(){
	$suma = 0;
	$year = date('Y');
	$month = date('m');
	$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$sql = "SELECT * FROM " . TBL_SASK. " WHERE MONTH(sasData) = $month AND YEAR(sasData) = $year";
	$result = mysqli_query($db, $sql);
	while($row = $result->fetch_assoc()) 
	{
		$sql2 = "SELECT * FROM " . TBL_PREK. " WHERE sask_fk = ".$row['serijosNr']."";
		$result2 = mysqli_query($db, $sql2);
		while($row2 = $result2->fetch_assoc()) 
		{
			$suma = $suma + ($row2['kiekis'] * $row2['vntKaina']);
		}
	}
	
	return $suma;
}

function getUserID($name){
	$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$sql = "SELECT vartotojoID FROM " . TBL_USERS. " WHERE vardasPavarde = '$name'";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_array($result);
	return $row[0];
}
