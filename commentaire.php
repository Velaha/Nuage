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

	if(isset($_POST['com']) && $_POST['commentaire'] != ""){

		/* ON VERIFIE SI IL EXISTE DEJA UN COMMENTAIRE */

		$res = $cnx->query("select * from commenter where idjeu = ".$_POST['idjeu']." and idjoueur = (select idjoueur from joueur where pseudo ='".$_SESSION['pseudo']."')");
		$resultat = $res->fetchAll();
		$nb = count($resultat);

		if($nb == 1){
			/*SI UN COMMENTAIRE DU JOUEUR EXISTE DEJA, ALORS ON LE MET A JOUR*/
			$res2 = $cnx->query("select idjoueur from joueur where pseudo = '".$_SESSION['pseudo']."'");
			foreach ($res2 as $ligne2) {
				$res3 = $cnx->exec("update commenter set commentaire = '".$_POST['commentaire']."' where idjeu = ".$_POST['idjeu']." and idjoueur = ".$ligne2['idjoueur']."");
				if($res3 == 1){
					echo "<p>Le jeu a été commenté.</p>";
				}
				else{
					echo "<p style='color:red'>Une erreur est survenue au moment de la prise en compte du commentaire.</p>";
				}
			}
			$res2->closeCursor();

		}
		/*SINON ON AJOUTE UN NOUVEAU COMMENTAIRE*/
		else{
			$res3 = $cnx->query("select idjoueur from joueur where pseudo = '".$_SESSION['pseudo']."'");
			foreach ($res3 as $ligne3) {
				$res4 = $cnx->exec("insert into commenter values(".$ligne3['idjoueur'].", ".$_POST['idjeu'].", '".$_POST['commentaire']."')");
				if($res4 == 1){
					echo "<p>Le jeu a été commenté.</p>";
				}
				else{
					echo "<p style='color:red'>Une erreur est survenue au moment de la prise en compte du commentaire.</p>";
				}
			}
			$res3->closeCursor();
		}
	}
	else{
		echo "<p style='color:red'>Une erreur est survenue au moment de la prise en compte du commentaire.</p>";
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