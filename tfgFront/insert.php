<?php
   	include("conexion.php");
   

	$temp1=$_GET["temp"];
	$hum1=$_GET["hum"];
	$disp1=$_GET["disp"];
	$alert1=$_GET["alerta"];

	$query = "INSERT INTO `sensores` (`temperatura`, `humedad`, `dispositivo`,`alerta`) 
      VALUES ('".$temp1."','".$hum1."','".$disp1."','".$alert1."')"; 
   	
   	mysqli_query($conexion,$query);
	mysqli_close($conexion);

   	header("Location: front.php");
?>
