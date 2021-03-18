<?php
session_start();

if(isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Mes jeux</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<h2>Mes jeux</h2>

	<!-- BOUTONS -->

	<div>
		<form action="" method="POST">
			<input type="submit" name="deco" value="Déconnexion" style="width:150px; height:40px; position:fixed; top:0.5%; right:0.5%">
		</form>		
	</div>	

	<div>
		<form action="" method="POST">
			<input type="submit" name="profil" value="Profil" style="width:150px; height:40px; position:fixed; top:0.5%; right:13%">
		</form>		
	</div>

	<div>
		<form action="" method="POST">
			<input type="submit" name="accueil" value="Accueil" style="width:150px; height:40px; position:fixed; top:0.5%; right:25.5%">
		</form>		
	</div>

	<div>
		<form action="" method="POST">
			<input type="submit" name="boutique" value="Boutique" style="width:150px; height:40px; position:fixed; top:0.5%; right:38%">
		</form>		
	</div>



	<?php
	include("connexion.inc.php");

	/*BOUTONS*/
	if(isset($_POST['accueil'])){
		header("location: accueil.php");
	}

	if(isset($_POST['deco'])){
		header("location: connexion.php");
		session_destroy();
		echo "Déconnexion réussie.";
	}

	if(isset($_POST['profil'])){
		header("location: profil.php");
	}

	if(isset($_POST['boutique'])){
		header("location: boutique.php");
	}

	/*AFFICHAGE DES JEUX ACHETES PAR L'UTILISATEUR*/

	$res = $cnx->query("select idjeu from acheter where idjoueur = (select idjoueur from joueur where pseudo = '".$_SESSION['pseudo']."');");
	foreach ($res as $ligne) {
		$res2 = $cnx->query("select titre from jeu where idjeu =".$ligne['idjeu']);
		foreach ($res2 as $ligne2) {
			echo "<div style='background-color:#72D3E3'><h3>".$ligne2['titre']."</h3>";
			echo "<form action='jeu.php' method='POST'>
					<input name='idjeu' type='hidden' value='".$ligne['idjeu']."'>
					<input type='submit' name='voir' value='Voir le jeu'>
				</form></div>";
		}
		$res2->closeCursor();
	}
	$res->closeCursor();
	?>

	<h2>Jeux prêtés</h2>

	<?php

	/*AFFICHAGE DES JEUX PRETES A L'UTILISATEUR*/
	
	$res = $cnx->query("select idjeu from partager where idjoueur2 = (select idjoueur from joueur where pseudo = '".$_SESSION['pseudo']."')");
	foreach ($res as $ligne) {
		$res2 = $cnx->query("select titre from jeu where idjeu =".$ligne['idjeu']);
		foreach ($res2 as $ligne2) {
			echo "<div style='background-color:#72D3E3'><h3>".$ligne2['titre']."</h3>";
			echo "<form action='jeu.php' method='POST'>
					<input name='idjeu' type='hidden' value='".$ligne['idjeu']."'>
					<input type='submit' name='voir' value='Voir le jeu'>
				</form></div>";
		}
		$res2->closeCursor();
	}
	$res->closeCursor();
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