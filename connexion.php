<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Connexion</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div>
		<form action="" method="POST">
			<input type="text" name="pseudo" placeholder="Pseudo"><br>
			<input type="password" name="mdp" placeholder="Mot de passe"><br>
			<input type="reset" name="reset" value="Effacez" /> 
			<input type="submit" name="valider" value="Validez" /><br>
		</form>
		
		<a href="inscription.php">Pas encore inscrit ?</a>
	</div>

	<?php

	include("connexion.inc.php");

	if(isset($_POST['valider'])){

		/*PERMET DE SE CONNECTER AU SITE*/

		$_SESSION['pseudo'] = $_POST['pseudo'];
		$_SESSION['mdp'] = $_POST['mdp'];
		/*VALEURS DE SESSION QUI SERONT RÉUTILISÉ PLUS TARD*/

		$res = $cnx->query("select pseudo from joueur where pseudo ='".$_SESSION['pseudo']."'");
		$resultat = $res->fetchAll();
		$nb = count($resultat);

		if($nb != 1){
			echo "Pseudo incorrect.";
		}
		else{

			$res = $cnx->query("select * from joueur where pseudo = '".$_SESSION['pseudo']."'");
			foreach ($res as $ligne){
				if($ligne['mdp'] == md5($_SESSION['mdp'])){
					echo "Authentification réussie.";
					header('location: accueil.php');
				}
				else{
					echo "Mot de passe incorrect.";
				}
			}
			$res->closeCursor();

		}
	}
	?>

</body>
</html>