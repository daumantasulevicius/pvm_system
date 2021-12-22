<body>
	Atgal į [<a href="buhaltere_list.php">Buhalterė</a>]
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
	$lentele="saskaita";
	$lentele2="saskaitosPrekes";
		if(isset($_GET['id']))
		{
			$vartotojoID = $_GET['id'];
			$vartotojoVardas = $_GET['vardas'];
		}
	// prisijungti
	$conn = new mysqli($server, $user, $password, $dbname);
	if ($conn->connect_error) die("Negaliu prisijungti: " . $conn->connect_error);
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
		<center><h3>Naudotojo <?php echo $vartotojoVardas; ?> sąskaitos</h3></center>
<table style="margin: 0px auto;" id="zinutes">

		
<?php	
//  nuskaityti 
	$sql =  "SELECT * FROM $lentele WHERE vartotojoID_fk = '$vartotojoID'";
	if (!$result = $conn->query($sql)) die("Negaliu nuskaityti: " . $conn->error);
	//print_r($sql);

	// parodyti
	
	//echo "<table border=\"1\">";
	echo "<tr>
		<td>Serijos numeris</td>
		<td>Sąskaitos išrašymo data</td>
		<td>Prekės pavadinimas</td>
		<td>Prekių skaičius</td>
		<td>Kainos</td>
		</tr>";
	
	while($row = $result->fetch_assoc()) 
	{
		$sql2 =  "SELECT * FROM $lentele2 WHERE sask_fk = ".$row['serijosNr']."";
		
		if (!$result2 = $conn->query($sql2)) die("Negaliu nuskaityti: " . $conn->error);

		while($row2 = $result2->fetch_assoc())
		{
			echo"<tr>
			<td>".$row['serijosNr']."</td>
			<td>".$row['sasData']."</td>
			<td>".$row2['prekPavadinimas']."</td>
			<td>".$row2['kiekis']."</td>
			<td>".$row2['vntKaina']."</td>
			</tr>";
			$row['serijosNr'] = " ";
			$row['sasData'] = " ";
		}
	}
	//echo "</table>";
	echo "<tr>
		<td>Bendra vartotojo sąskaitų suma</td>
		</tr>";
	

	echo "<tr>
		<td>".suma($vartotojoID)."</td>
		</tr>"; 
	
	
	
	$conn->close();
?>	
</table>
	
	</body>
</html>