<body>
	Atgal į [<a href="index.php">Klientas</a>]
    <table style="margin: 0px auto;" id="zinutes">

<?php
		include("include/nustatymai.php");
		include("include/functions.php");
		session_start();
		//var_dump( $_SESSION['user']) ;
	$server="localhost";
	$user="stud";
	$password="stud";
	$dbname="projektas";
	$lentele="mokejimas";
	if(isset($_GET['id']))
	{
			$serijosNr = $_GET['id'];
	}
	// prisijungti
	$conn = new mysqli($server, $user, $password, $dbname);
	if ($conn->connect_error) die("Negaliu prisijungti: " . $conn->connect_error);
	if($_POST !=null)
	{
		$numeris = $_POST['saskaitosNr'];
		$pavadinimas = $_POST['pavadinimas'];		
		$paskirtis = $_POST['paskirtis'];
		
	
       $sql = "INSERT INTO $lentele (sasNr, pavadinimas, paskirtis, saskaitos_ID_fkk) 
             VALUES ('$numeris', '$pavadinimas', '$paskirtis','$serijosNr')";
       if (!$result = $conn->query($sql)) die("Negaliu įrašyti: " . $conn->error);
	   //echo "Įrašyta";	
			  header("Location:mokejimaiList.php?id=$serijosNr");
	   $conn->close();
	   exit();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Sąskaitų peržiūra</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js">
     	</script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js">
     </script>
	<style>
	#zinutes {
 	   font-family: Arial; border-collapse: collapse; width: 70%;
	}
	#zinutes td {
 	   border: 1px solid #ddd; padding: 8px;
	}
	#zinutes tr:nth-child(even){background-color: #f2f2f2;}
	#zinutes tr:hover {background-color: #ddd;}
</style>

	</head>
	<body>
		<center><h3>Sąskaitos mokėjimai</h3></center>
<table style="margin: 0px auto;" id="zinutes">

		
<?php	
//  nuskaityti 
	$sql =  "SELECT * FROM $lentele WHERE saskaitos_ID_fkk = '$serijosNr'";
	if (!$result = $conn->query($sql)) die("Negaliu nuskaityti: " . $conn->error);
	//print_r($sql);

	// parodyti
	
	//echo "<table border=\"1\">";
	echo "<tr>
		<td>Sąskaitos numeris</td>
		<td>Pavadinimas</td>
		<td>Paskirtis</td>
		</tr>";
	
	while($row = $result->fetch_assoc()) 
	{
		echo "<tr>
		<td>".$row['sasNr']."</td>
		<td>".$row['pavadinimas']."</td>
		<td>".$row['paskirtis']."</td>
		</tr>";
	}
	//echo "</table>";
	
	
	
	$conn->close();
?>	
</table>
		<div class="container">
		<form 
			 method='post'>
			<div class="form-group col-lg-2">
          	<label for="saskaitosNr" class="control-label">Sąskaitos numeris:</label>
          	<textarea name='saskaitosNr' class="form-control input-sm"></textarea>
      		</div>
			<div class="form-group col-lg-2">
          	<label for="pavadinimas" class="control-label">Pavadinimas:</label>
          	<textarea name='pavadinimas' class="form-control input-sm"></textarea>
      		</div>
			<div class="form-group col-lg-2">
          	<label for="paskirtis" class="control-label">Paskirtis:</label>
          	<textarea name='paskirtis' class="form-control input-sm"></textarea>
      		</div>
			<div class="form-group col-lg-2">
         	<input type='submit' name='ok' value='Apmokėti saskaitą' class="btnbtn-default">
      		</div>

			
		</form>
		</div>
	</body>
</html>