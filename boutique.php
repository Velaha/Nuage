<?php
session_start();

if(isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Boutique</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<h2>Boutique</h2>

	<div>
		<form action="" method="POST">
			<input type="submit" name="accueil" value="Accueil" style="width:150px; height:40px; position:fixed; top:0.5%; right:25.5%">
		</form>		
	</div>

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

	/*VISUALISATON DES JEUX*/

	?>
	<h4>Chaque jeu coute 10€</h4>

	<?php

	$res = $cnx->query("select titre, idjeu from jeu");
	foreach ($res as $ligne) {
		echo "<div style='background-color:#72D3E3'>";
		echo "<form action='voir_jeu.php' method='POST'>
					<input name='idjeu' type='hidden' value='".$ligne['idjeu']."'>
					<input type='submit' name='titre' value='".$ligne['titre']."'>
				</form>";
		$res2 = $cnx->query("select datesortie, age_requis, description, entre1.nom as dev, entre2.nom as edi
						from jeu, entreprise entre1, entreprise entre2
						where jeu.iddev = entre1.ident and jeu.idedi = entre2.ident and jeu.idjeu = ".$ligne['idjeu']);
		foreach ($res2 as $ligne2) {
			echo "<br>Description : ".$ligne2['description']."<br>";
			echo "Date de sortie : ".$ligne2['datesortie']."<br>";
			echo "Age requis : ".$ligne2['age_requis']."<br>";
			echo "Catégorie : ";			
			$res3 = $cnx->query("select nom from etre where idjeu = ".$ligne['idjeu']);
			foreach ($res3 as $ligne3) {
				echo $ligne3['nom']." ";
			}
			$res3->closeCursor();
			echo "<br><br>Développeur : ".$ligne2['dev']."<br>";
			echo "Éditeur : ".$ligne2['edi']."<br>";
		}
		$res2->closeCursor();
		$res3 = $cnx->query("select avg(note) from noter where idjeu = ".$ligne['idjeu']."group by idjeu");
		$resultat = $res3->fetchAll();
		if(count($resultat) == 0){
			echo "Note : Non noté.";
		}
		else{
			$res3 = $cnx->query("select avg(note) from noter where idjeu = ".$ligne['idjeu']."group by idjeu");
			foreach ($res3 as $ligne3) {
				printf("Note : %.2f", $ligne3['avg']);
			}
			$res3->closeCursor();
		}
		echo "</div><br>";	
	}
	$res->closeCursor();

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