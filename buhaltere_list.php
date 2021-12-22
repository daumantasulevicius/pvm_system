<?php

session_start();
include("include/nustatymai.php");
include("include/functions.php");
// cia sesijos kontrole
if (!isset($_SESSION['prev']) || ($_SESSION['ulevel'] != $user_roles[BUHALTERE_LEVEL]))   { header("Location: logout.php");exit;}
$_SESSION['prev']="buhaltere";
date_default_timezone_set("Europe/Vilnius");
?>

<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Buhalterės sąsaja</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" >
    </head>
    <body>
        <table class="center" ><tr><td>
            <center><img src="include/header.png"></center>
            </td></tr><tr><td>
		<center><font size="5">Sąskaitų peržiūra</font></center></td></tr></table> <br>
		<center><b><?php echo $_SESSION['message']; ?></b></center>
	    <table class="center" style=" width:75%; border-width: 2px; border-style: dotted;">
		         <tr><td width=30%><a href="index.php">[Atgal]</a></td><td width=30%> 
		   
			<td width="40%">Čia galite peržiūrėti sąskaitas</td></tr></table> <br> 
<?php
    
	$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$sql = "SELECT *"
            . "FROM " . TBL_USERS . " WHERE pareigos = 5";
	$result = mysqli_query($db, $sql);
	if (!$result || (mysqli_num_rows($result) < 1))  
			{echo "Klaida skaitant lentelę users"; exit;}
?>
    <table class="center"  border="1" cellspacing="0" cellpadding="3">
    <tr><td><b>Vartotojo vardas</b></td><td><b>E-paštas</b></td><td><b>Paskutinį kartą aktyvus</b></td><td><b>Ką daryti?</b></td></tr>
<?php
        while($row = mysqli_fetch_assoc($result)) 
	{	 
	    $level=$row['pareigos']; 
	  	$user= $row['vardasPavarde'];
		$NaudID = $row['vartotojoID'];
	  	$email = $row['elPastas'];
      	$time = date("Y-m-d G:i", strtotime($row['timestamp']));
		echo "<tr>
		<td>".$user."</td>
		<td>".$email."</td>
		<td>".$time."</td>
		<td>[<a href=\"sask_list_buhalt.php?id=$NaudID&vardas=$user\">Vartotojo sąskaitos</a>] &nbsp;&nbsp;
		</tr>";
   }
?>
        
    <tr><td><b>Einamojo mėnesio balansas: <?php echo balansas(); ?>€</b></td></tr>
		</table>
    </body></html>

