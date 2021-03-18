<?php
session_start();

if(isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Recharge</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php
	include("connexion.inc.php");
	?>

	<h2>Recharge</h2>
	<p style="color: green;text-align: center">Ce formulaire fonctionne mais rassurez vous, notre site n'est malheureusement pas connecté à votre banque, par conséquent, votre argent ne sera pas débité.</p>

	<form action="" method="POST">
		<input type="text" name="valeur" placeholder="Somme à recharger" required><br>
		<input type="text" name="nom" placeholder="Nom" required><br>
		<input type="text" name="CB" maxlength="16" size="15" placeholder="Numéro de carte bancaire" required><br>
		<input type="text" name="crypto" maxlength="3" size="3" placeholder="Cryptogramme" required><br>
		<input type="password" name="mdp" placeholder="Mot de passe" required><br>
		<input type="reset" name="reset" value="Réinitialiser">
		<input type="submit" name="submit" value="Valider">	
	</form>

	<form method="POST">			
		<input type="submit" name="retour" value="Retour"> 
	</form>

	<?php
	if(isset($_POST['retour'])){
		header("location: profil.php");
	}

	if(isset($_POST['submit'])){

		/*RECUPERATION DE L'ID DU JOUEUR*/
		
		$res = $cnx->query("select * from joueur where pseudo = '".$_SESSION['pseudo']."' and nom = '".$_POST['nom']."' and mdp = md5('".$_POST['mdp']."')");
		$resultat = $res->fetchAll();
		$nb = count($resultat);
		if($nb == 1){
			/*AJOUT DE LA VALEUR SOUHAITE AU PORTE MONNAIE DU JEU*/
			$res2 = $cnx->exec("update joueur set argent = argent + ".$_POST['valeur']." where pseudo = '".$_SESSION['pseudo']."' and nom ='".$_POST['nom']."' and mdp = md5('".$_POST['mdp']."')");
			if($res2 == 1){
				echo "<p>Votre monnaie a pu etre rechargé</p>";
				header("location: profil.php");
			}
			else{
				echo "<p>Une erreur est survenu lors de votre rechargement.</p>";
			}
			
		}
		else{
			echo "<p style='color:red'>Informations erronnées</p>";
		}
	
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