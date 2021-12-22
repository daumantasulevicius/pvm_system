<body>
	Atgal į [<a href="admin.php">Admin</a>]
    <table style="margin: 0px auto;" id="zinutes">

<?php
		session_start();
		//var_dump( $_SESSION['user']) ;
	$server="localhost";
	$user="stud";
	$password="stud";
	$dbname="projektas";
	$lentele="saskaita";
	$lentele2="saskaitosPrekes";
	$lentele3="mokejimas";
		if(isset($_GET['id']))
		{
			$vartotojoID = $_GET['id'];
		}
	// prisijungti
	$conn = new mysqli($server, $user, $password, $dbname);
	if ($conn->connect_error) die("Negaliu prisijungti: " . $conn->connect_error);
	if(isset($_POST['ok']) && !empty($_POST['ok']))
	{
		$numeris = $_POST['numeris'];
		
		//if($numeris < 0)
		//die("Sąskaitos numeris negali būti neigiamas");
		if($numeris > 0)
		{
			$sql = "INSERT INTO $lentele (serijosNr, sasData, vartotojoID_fk) 
				 VALUES ('$numeris', now(), '$vartotojoID')";
			if (!$result = $conn->query($sql)) die("Negaliu įrašyti: " . $conn->error);

			$pavadinimas = $_POST['pavadinimas'];
			$skaicius = $_POST['kiekis'];		
			$kaina = $_POST['kaina'];



			for ($i = 0; $i < count($skaicius); $i++)
			{
				if($skaicius[$i] > 0 && $kaina[$i] > 0)
				{
					$sql = "INSERT INTO $lentele2 (sasPrekId, prekPavadinimas, kiekis, vntKaina, sask_fk) 
				 	VALUES (NULL, '$pavadinimas[$i]', '$skaicius[$i]', $kaina[$i], '$numeris')";
					if (!$result = $conn->query($sql)) die("Negaliu įrašyti: " . $conn->error);
				}else {
					?>
					<script type="text/javascript">
					alert("Įrašytas netinkamas kintamasis, viena ar kelios prekės nepridėtos");
					</script>
					<?php
				}
				//die("Prekių skaičius negali būti neigiamas");

				//if($kaina[$i] < 0)
				//die("Prekės kaina negali būti neigiama");
				
			}

		   //echo "Įrašyta";	
				  header("Location:saskList.php?id=$vartotojoID");
		   $conn->close();
		   exit();
		} else {
			?>
			<script type="text/javascript">
				alert("Sąskaitos numeris negali būti neigiamas");
				</script>
			<?php
		}
	}
	if(isset ($_POST['delete']) && !empty($_POST['delete']))
	{
		$numerisDel = $_POST['numerisDelete'];
		if($numerisDel > 0)
		{		
			$sql1 = "DELETE FROM $lentele2 WHERE sask_fk = '$numerisDel'";
			$sql2 = "DELETE FROM $lentele WHERE serijosNr = '$numerisDel'";
			$sql3 = "DELETE FROM $lentele3 WHERE saskaitos_ID_fkk = '$numerisDel'";
			if (!$result = $conn->query($sql1)) die("Negaliu trinti: " . $conn->error);
			if (!$result = $conn->query($sql3)) die("Negaliu trinti: " . $conn->error);
			if (!$result = $conn->query($sql2)) die("Negaliu trinti: " . $conn->error);
		} else{
			?>
			<script type="text/javascript">
				alert("Sąskaitos numeris negali būti neigiamas");
				</script>
			<?php
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Sąskaitų sudarymas</title>
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
	div.form-groups{
		padding-left: 300px
	}
</style>

	</head>
	<body>
		<center><h3>Sąskaitų sistema</h3></center>
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
	$conn->close();
?>
	
</table>
		<div class="form-groups">
		<form method='post'>
			
			<div class="form-group add-field">
				<div class="user">
					<input type="text" placeholder="Prekės pavadinimas" name="pavadinimas[]">                
					<input type="text" placeholder="Prekės kaina" name="kaina[]">
					<input type="text" placeholder="Prekės vnt. kiekis" name="kiekis[]">
				
					<!--<label for="skaicius" class="control-label">Prekių skaičius:</label>
					<textarea name='skaicius' class="form-control input-sm"></textarea>
					</div>
					<div class="form-group col-lg-2">
					<label for="kainos" class="control-label">Prekių kaina:</label>
					<textarea name='kainos' class="form-control input-sm"></textarea>
					</div>-->
				
				</div>
			</div>
			<div class="add-more"><span>+</span>Pridėti daugiau prekių</div>
			
			<div class="form-group col-sm-2">
				<label for="numeris" class="control-label">Sąskaitos numeris:</label>
				<textarea name='numeris' class="form-control input-sm"></textarea>
				<input type='submit' name='ok' value='Pridėti sąskaitą' class="btnbtn-default">
			</div>
			
			<div class="form-group col-sm-2">
				<label for="numerisDelete" class="control-label">Sąskaitos numeris, kurią trinti:</label>
				<textarea name='numerisDelete' class="form-control input-sm"></textarea>
				<input type='submit' name='delete' value='Trinti sąskaitą' class="btnbtn-default">
			</div>
			
		</form>
		</div>
	</body>
</html>
		<script type="text/javascript">
			var data_fo = $('.add-field').html();
			var sd = '<div class="remove-add-more">Panaikinti</div>';
			var data_combine = data_fo.concat(sd);
			var max_fields = 5; //maximum input boxes allowed
			var wrapper = $(".user"); //Fields wrapper
			var add_button = $(".add-more"); //Add button ID
			var x = 1; //initlal text box count
			$(add_button).click(function(e){ //on add input button click
			  e.preventDefault();
			  if(x < max_fields){ //max input box allowed
				x++; //text box increment
				$(wrapper).append(data_combine); //add input box
				//$(wrapper).append('<div class="remove-add-more">Remove</div>')
			  }
			});
				$(wrapper).on("click",".remove-add-more", function(e){ //user click on remove text
				e.preventDefault();
				$(this).prev('.user').remove();
				$(this).remove();
				x--;
			})
		</script>