<?php
session_start();

if(isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Vos jeux</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

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

	include("connexion.inc.php");

	if(isset($_POST['voir'])){



		/* TITRE DU JEU */
		$res = $cnx->query("select titre from jeu where idjeu = ".$_POST['idjeu']);

		foreach ($res as $ligne) {
			echo "<h2>".$ligne['titre']."</h2>";
		}
		$res -> closeCursor();

		/* INFORMATION DU JEU */
		$res2 = $cnx->query("select datesortie, age_requis, description, entre1.nom as dev, entre2.nom as edi
						from jeu, entreprise entre1, entreprise entre2
						where jeu.iddev = entre1.ident and jeu.idedi = entre2.ident and jeu.idjeu = ".$_POST['idjeu']);

		foreach ($res2 as $ligne2) {
			echo "<p>";
			echo "Description : ".$ligne2['description']."<br>";
			echo "Date de sortie : ".$ligne2['datesortie']."<br>";
			echo "Age requis : ".$ligne2['age_requis']."<br>";
			echo "Catégorie : ";
			$res3 = $cnx->query("select nom from etre where idjeu=".$_POST['idjeu']);
			foreach ($res3 as $ligne3) {
				echo $ligne3['nom']." ";
			}
			$res3->closeCursor();
			echo "<br><br>Développeur : ".$ligne2['dev']."<br>";
			echo "Éditeur : ".$ligne2['edi']."<br></p>";
		}
		$res2->closeCursor();


		/* POSSIBILITÉ DE METTRE UNE NOTE */
		echo "<h3>Note</h3>";
		$res3 = $cnx->query("select * from noter where idjeu =".$_POST['idjeu']." and idjoueur = (select idjoueur from joueur where pseudo ='".$_SESSION['pseudo']."')");
		$resultat = $res3->fetchAll();
		$nb = count($resultat);

		if($nb == 1){
			echo "<p>Vous avez déjà donné une note à ce jeu.<br></p>";
		}
		else{
			echo "Un jeu peut recevoir comme note un entier compris entre 0 et 5 inclus.<br>
					Si vous rentrez un chiffre à virgule, la note sera arrondi au supérieur.<br>";
			echo "<p><form action='notation.php' method='POST'>
						<input type='text' name='notation' placeholder='Note'>
						<input type='submit' name='note' value='Soumettre la note'>
						<input type='hidden' name='idjeu' value='".$_POST['idjeu']."'>
					</form><br></p>";
		}


		/* POSSIBILIÉ DE METTRE UN COMMENTAIRE */
		echo "<h3>Commentaire</h3>";
		echo "<p><form action='commentaire.php' method='POST'>
				<input type='text' name='commentaire' placeholder='Commentaire'>
				<input type='submit' name='com' value='Soumettre le commentaire'>
				<input type='hidden' name='idjeu' value='".$_POST['idjeu']."'>
			</form></p><br>";

		
		/* SUCCES DU JEU OBTENU */
		echo "<h3>Succès obtenus</h3>";
		$res3 = $cnx->query("select * from obtenir where code in (select code from succes where idjeu = ".$_POST['idjeu'].") and idjoueur = (select idjoueur from joueur where pseudo = '".$_SESSION['pseudo']."')");
		$resultat = $res3->fetchAll();
		$nb = count($resultat);

		if($nb > 0){
			$res3 = $cnx->query("select code, date_obtention from obtenir where code in (select code from succes where idjeu = ".$_POST['idjeu'].") and idjoueur = (select idjoueur from joueur where pseudo = '".$_SESSION['pseudo']."')");
			foreach ($res3 as $ligne3) {
				$res4 = $cnx->query("select intitule, description from succes where code =".$ligne3['code']." and idjeu = ".$_POST['idjeu']."");
				foreach ($res4 as $ligne4) {
					echo "<p>".$ligne4['intitule']." : ".$ligne4['description']."<br>Obtenu le : ".$ligne3['date_obtention']."<br></p>";
				}
				$res4->closeCursor();
			}
			$res3->closeCursor();
		}
		else{
			echo "<p>Vous n'avez pas encore obtenu de succès pour ce jeu.<br></p>";
		}


		/* SUCCES ENCORE A OBTENIR */
		echo "<h3>Succès à obtenir</h3>";
		$res5 = $cnx->query("select * from obtenir where code in (select code from succes where idjeu = ".$_POST['idjeu'].") and idjoueur = (select idjoueur from joueur where pseudo = '".$_SESSION['pseudo']."')");
		$res6 = $cnx->query("select * from succes where idjeu = ".$_POST['idjeu']."");
		$resultat5 = $res5->fetchAll();
		$resultat6 = $res6->fetchAll();
		/*nombre de succès obtenus*/
		$nb5 = count($resultat5);
		/*nombre de succès disponible*/
		$nb6 = count($resultat6);

		if($nb6-$nb5 > 0){
			$res7 = $cnx->query("select intitule, description from succes where code not in (select code from obtenir where idjoueur = (select idjoueur from joueur where pseudo = '".$_SESSION['pseudo']."')) and idjeu = ".$_POST['idjeu']."");
			foreach ($res7 as $ligne7) {
				if($ligne7['description'] == ""){
					echo "<p>".$ligne7['intitule']." : Description cachée<br></p>";
				}
				else{
					echo "<p>".$ligne7['intitule']." : ".$ligne7['description']."<br></p>";
				}				
			}
			$res7->closeCursor();
		}
		else{
			echo "<p>Vous avez obtenu tous les succès de ce jeu.<br></p>";
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