<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Inscription</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php
	include("connexion.inc.php");
	?>

	<div>
		<form action="" method="POST">
			<input type="text" name="nom" placeholder="Nom">*<br>
			<input type="text" name="naissance" size="8" placeholder="aaaa-mm-jj"/>*<br>
			<input type="text" name="pseudo" placeholder="Pseudo">*<br>
			<input type="password" name="mdp" placeholder="Mot de passe">*<br>
			<input type="password" name="mdp_verif" placeholder="Vérification">*<br>
			<input type="text" name="mail" placeholder="Adresse mail"><br>
			<input type="text" name="mail_verif" placeholder="Vérification"><br>

			<input type="reset" name="reset" value="Effacez" /> 
			<input type="submit" name="valider" value="Validez" /><br>
			<input type="submit" name="retour" value="Se connecter">
		</form>
		<p>* indique un champ obligatoire</p>
	</div>

	<?php

	/*PERMET L'INSCRIPTION D'UN NOUVEAU JOUEUR*/

	if(isset($_POST['valider'])){
		$cpt = 0;

		if($_POST['naissance'] == "" || $_POST['pseudo'] == "" || $_POST['mdp'] == "" || $_POST['nom'] == ""){
			echo "Il manque des informations.<br>";
			$cpt = 1;
		}

		/*Vérification du pseudo*/
		$res = $cnx->query("select pseudo from joueur");
		foreach($res as $ligne){
			if($_POST['pseudo'] == $ligne['pseudo']){
				echo "Le pseudo est déjà utilisé.<br>";
				$cpt = 1;
			}
		}
		$res->closeCursor();

		/*Vérification du mot de passe*/
		if($_POST['mdp'] != $_POST['mdp_verif']){
			echo "Deux mots de passe différents.<br>";
			$cpt = 1;
		}

		/*Vérification de l'adresse mail et si elle a été saisie*/
		if($_POST['mail'] != ""){

			$res = $cnx->query("select mail from joueur");
			foreach($res as $ligne){
				if($ligne['mail']!=null && $_POST['mail'] == $ligne['mail']){
					echo "Adresse mail déjà utilisée.<br>";
					$cpt = 1;
				}
			}
			$res->closeCursor();

			if($_POST['mail'] != $_POST['mail_verif']){
				echo "Deux adresses mail différentes.<br>";
				$cpt = 1;
			}
		}

		/*Vérification terminée, on rentre des données*/
		if($_POST['mail'] != "" && $cpt == 0){
			$res = $cnx->exec("insert into joueur(nom, naissance, pseudo, mdp, mail) values ('".$_POST['nom']."', '".$_POST['naissance']."', '".$_POST['pseudo']."', md5('".$_POST['mdp']."'), '".$_POST['mail']."')");
			if($res == 1){
				echo "Compte créé !";
				header('location: connexion.php');
			}
			else{
				echo "Erreur lors de la création du compte.";
			}
		}		
		elseif($cpt == 0){
			$res = $cnx->exec("insert into joueur(nom, naissance, pseudo, mdp) values ('".$_POST['nom']."', '".$_POST['naissance']."', '".$_POST['pseudo']."', md5('".$_POST['mdp']."'))");
			if($res == 1){
				echo "Compte créé !";
				header('location: connexion.php');
			}
			else{
				echo "Erreur lors de la création du compte.";
			}
		}
	}

	if(isset($_POST['retour'])){
		header("location: connexion.php");
	}
	?>

</body>
</html>