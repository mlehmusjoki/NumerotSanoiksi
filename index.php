<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include('numbers.php');
$Numbers = new Numbers;
if (isset($_GET['number']) && $_GET['number'] != "") {
	echo $Numbers->ToWords($_GET['number']);
	echo "<br>";
}
if (isset($_GET['str']) && $_GET['str'] != "") {
	echo $Numbers->ToNumbers($_GET['str']);
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	<p>Hei!</p>
	<p>Vehje ymmärtää kokonaislukuja 999 999 999 asti. Se ei ymmärrä desimaaleja.</p>
	<p>Vehje hämmentyy myös pahasti, jos kirjoitetussa numeraalissa on nollia. Esimerkiksi sataviisituhatta.</p>
	<p>En keksinyt luotettavaa tapaa saada sanoja muutetuksi numeroiksi, ilman että käyttäjä olisi kertonut vehkeelle, millä magnituudilla ollaan menossa missäkin kohtaa.</p>
		<form action="/" method="get">
			Numero tekstiksi <input type="number" name="number">
			Teksti numeroiksi <input type="text" name="str">
			<input type="submit" name="submit">
		</form>
	</body>
</html>