<?php
session_start();

if(isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Notation</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<div>
		<form action="" method="POST">
			<input type="submit" name="retour" value="Retour" style="width:150px; height:40px; position:fixed; top:0.5%; right:0.5%">
		</form>		
	</div>


	<?php
	include("connexion.inc.php");

	if(isset($_POST['retour'])){
		header("location: vos_jeux.php");
	}

	/*PERMET DE DONNER UNE NOTE À UN JEU*/

	if(isset($_POST['note']) && $_POST['notation'] >= 0 && $_POST['notation'] <= 5){
		$res3 = $cnx->query("select idjoueur from joueur where pseudo = '".$_SESSION['pseudo']."'");
		foreach ($res3 as $ligne3) {
			$res4 = $cnx->exec("insert into noter values(".$ligne3['idjoueur'].", ".$_POST['idjeu'].", ".$_POST['notation'].")");
			if($res4 == 1){
				echo "<p>Le jeu a été noté.<br></p>";
			}
			else{
				echo "<p style='color:red'>Une erreur est survenu lors de la notation.</p>";
			}
		}
		$res3->closeCursor();		
	}
	else{
		echo "<p style='color:red'>Une erreur est survenu lors de la notation.</p>";
	}
	?>

<?php
}
else{
	echo "Vous avez essayé d'accéder à une page sans vous être connecté.";
	?>
	<a href="connexion.php">Merci de vous connecter.</a>
	<?php
}
?>

</body>
</html>