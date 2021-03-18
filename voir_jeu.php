<?php
session_start();

if(isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Jeu</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<!-- BOUTON -->
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
			<input type="submit" name="boutique" value="Boutique" style="width:150px; height:40px; position:fixed; top:0.5%; right:25.5%">
		</form>		
	</div>

	<div>
		<form action="" method="POST">
			<input type="submit" name="search" value="Recherche" style="width:150px; height:40px; position:fixed; top:0.5%; right:38%">
		</form>		
	</div>

	<div>
		<form action="" method="POST">
			<input type="submit" name="accueil" value="Accueil" style="width:150px; height:40px; position:fixed; top:0.5%; right:50.5%">
		</form>		
	</div>



	<!-- PAGE DU JEU -->
	<?php

	include("connexion.inc.php");

	/* BOUTONS */

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

	if(isset($_POST['search'])){
		header("location: recherche.php");
	}

	/* VISUALISATION DES JEUX */

	if(isset($_POST['titre'])){
		echo "<div><p>";
		$res2 = $cnx->query("select datesortie, age_requis, description, entre1.nom as dev, entre2.nom as edi
						from jeu, entreprise entre1, entreprise entre2
						where jeu.iddev = entre1.ident and jeu.idedi = entre2.ident and jeu.idjeu = ".$_POST['idjeu']);

		/* INFORMATIONS PRINCIPALES */
		foreach ($res2 as $ligne2) {
			echo "<h3>Titre : ".$_POST['titre']."</h3>";
			echo "Description : ".$ligne2['description']."<br>";
			echo "Date de sortie : ".$ligne2['datesortie']."<br>";
			echo "Age requis : ".$ligne2['age_requis']."<br>";
			echo "Catégorie : ";
			/*AFFICHAGE DES DIFFÉRENTES CATEGORIE*/
			$res = $cnx->query("select nom from etre where idjeu =".$_POST['idjeu']);
			foreach ($res as $key => $ligne) {
				echo $ligne['nom']." ";
			}
			$res->closeCursor();
			echo "<br><br>Développeur : ".$ligne2['dev']."<br>";
			echo "Éditeur : ".$ligne2['edi']."</p>";
		}
		$res2->closeCursor();

		/* ACHAT DU JEU */

		$res3 = $cnx->query("select * from acheter where idjeu =".$_POST['idjeu']." and idjoueur = (select idjoueur from joueur where pseudo ='".$_SESSION['pseudo']."')");
		$resultat = $res3->fetchAll();
		$nb = count($resultat);
		/*SI L'UTILISATEUR POSSEDE DEJA LE JEU*/
		if($nb == 1){
			echo "<p>Vous possedez déjà ce jeu.</p>";
		}
		/*S'IL NE POSSEDE PAS LE JEU*/
		else{
			echo "<p><form action='achat.php' method='POST'>
				<input name='idjeu' type='hidden' value='".$_POST['idjeu']."'>
				<input type='submit' name='achat' value='Acheter'>
			</form></p>";
		}

		/* NOTE DU JEU */
		echo "<p>";
		$res3 = $cnx->query("select avg(note) from noter where idjeu = ".$_POST['idjeu']."group by idjeu");
		$resultat = $res3->fetchAll();
		if(count($resultat) == 0){
			echo "Note : Non noté.<br>";
		}
		else{
			$res3 = $cnx->query("select avg(note) from noter where idjeu = ".$_POST['idjeu']."group by idjeu");
			foreach ($res3 as $ligne3) {
				printf("Note : %.2f<br>", $ligne3['avg']);
			}
			$res3->closeCursor();
		}
		echo "</p>";

		/* COMMENTAIRES DU JEU */
		echo "</p>";
		$res4 = $cnx->query("select idjoueur, commentaire from commenter where idjeu = ".$_POST['idjeu']);
		foreach ($res4 as $ligne4) {
			$res5 = $cnx->query("select pseudo from joueur where idjoueur =".$ligne4['idjoueur']);
			foreach ($res5 as $ligne5) {
				echo $ligne5['pseudo']." : ".$ligne4['commentaire']."<br>";
			}
			$res5->closeCursor();
		}
		$res4->closeCursor();
		echo "</p></div>";
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