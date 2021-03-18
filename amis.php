<?php
session_start();

if(isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Amis</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php
	include("connexion.inc.php");
	?>

	<h2>Mes amis</h2>

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

	<ul>

	<?php

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

	/* ON RETROUVE L'ID DU JOUEUR ACTUEL */
	$res = $cnx->query("select idjoueur from joueur where pseudo = '".$_SESSION['pseudo']."'");

	foreach ($res as $ligne) {
		/* ON RETROUVE LES PERSONNES AVEC QUI LE JOUEUR EST AMIS*/
		$res2 = $cnx->query("select * from amitie where idjoueur1 = ".$ligne['idjoueur']." or idjoueur2 = ".$ligne['idjoueur']."");
		$resultat = $res2->fetchAll();

		/* SI IL A AU MOINS AMI*/
		if(count($resultat) > 0){
			$res2 = $cnx->query("select * from amitie where idjoueur1 = ".$ligne['idjoueur']." or idjoueur2 = ".$ligne['idjoueur']."");
			foreach ($res2 as $ligne2) {

				/* SI LE JOUEUR EST LE JOUEUR 1*/
				if($ligne['idjoueur'] == $ligne2['idjoueur1']){

					/* ON RECUPERE LE PSEUDO DU JOUEUR AMI*/
					$res3 = $cnx->query("select pseudo from joueur where idjoueur = ".$ligne2['idjoueur2']);
					foreach ($res3 as $ligne3) {
						echo "<li>".$ligne3['pseudo']."</li>";
					}
					$res3->closeCursor();
				}

				/* SI LE JOUEUR EST LE JOUEUR 2*/
				elseif($ligne['idjoueur'] == $ligne2['idjoueur2']){

					/* ON RECUPERE LE PSEUDO DU JOUEUR AMI*/
					$res3 = $cnx->query("select pseudo from joueur where idjoueur = ".$ligne2['idjoueur1']);
					foreach ($res3 as $ligne3) {
						echo "<li>".$ligne3['pseudo']."</li>";
					}
					$res3->closeCursor();
				}
			}
			$res2->closeCursor();			
		}
		else{
			echo "<p>Vous n'avez pas encore d'amis sniff :'( </p>";
		}
	}
	$res->closeCursor();	
	?>

	</ul>

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