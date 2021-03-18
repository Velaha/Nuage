<?php
session_start();

if(isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Recherche</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php
	include("connexion.inc.php");
	?>

	<h2>Recherche</h2>

	<!-- BOUTONS -->
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
	}?>

	<!-- RECHERCHE -->

	<div>
		<form  action="" method="POST">
		Rechercher par : 
		<!-- TITRE -->
		<select name="titre">
			<option value="" selected="selected">-- titre --</option>
			<?php
			$res = $cnx->query('select titre, idjeu from jeu');
			foreach ($res as $ligne) {
				echo "<option value='".$ligne['idjeu']."'>".$ligne['titre']."</option>";
			}
			$res->closeCursor();
			?>
		</select>

		<!-- GENRE -->
		<select name="genre">
			<option value="" selected="selected">-- genre --</option>
			<?php
			$res2 = $cnx->query('select * from categorie');
			foreach ($res2 as $ligne2) {
				echo "<option value='".$ligne2['nom']."'>".$ligne2['nom']."</option>";
			}
			$res2->closeCursor();
			?>
		</select>

		<!-- EDITEUR -->
		<select name="edi">
			<option value="" selected="selected">-- éditeur --</option>
			<?php
			$res3 = $cnx->query('select ident, nom from entreprise');
			foreach ($res3 as $ligne3) {
				echo "<option value='".$ligne3['ident']."'>".$ligne3['nom']."</option>";
			}
			$res3->closeCursor();
			?>
		</select>

		<!-- DEVELOPPEUR -->
		<select name="dev">
			<option value="" selected="selected">-- développeur --</option>
			<?php
			$res4 = $cnx->query('select ident, nom from entreprise');
			foreach ($res4 as $ligne4) {
				echo "<option value='".$ligne4['ident']."'>".$ligne4['nom']."</option>";
			}
			$res4->closeCursor();
			?>
		</select>
		<input type="reset" name="reset" value="Effacez" /> 
		<input type="submit" name="submit" value="Validez" />
		</form>
	</div>

	<?php
	if(isset($_POST['submit'])){

		if($_POST['titre'] == ""){
			/*PAS DE TITRE*/
			if($_POST['edi'] == ""){
				/*PAS D'EDITEUR*/
				if($_POST['dev'] == ""){
					/*PAS DE DEVELOPPEUR*/
					$res = $cnx->query("select titre, jeu.idjeu, datesortie, age_requis, description, entre1.nom as dev, 
								entre2.nom as edi, etre.nom 
								from jeu, entreprise entre1, entreprise entre2, etre 
								where jeu.iddev = entre1.ident and jeu.idedi = entre2.ident and etre.idjeu = jeu.idjeu 
								and etre.nom like '".$_POST['genre']."%'");

					foreach ($res as $ligne) {
						echo "<br><div style='background-color:#72D3E3'>";
						echo "<form action='voir_jeu.php' method='POST'>
								<input name='idjeu' type='hidden' value='".$ligne['idjeu']."'>
								<input type='submit' name='titre' value='".$ligne['titre']."'>
							</form>";
						echo "<br>Description : ".$ligne['description']."<br>";
						echo "Date de sortie : ".$ligne['datesortie']."<br>";
						echo "Age requis : ".$ligne['age_requis']."<br>";
						echo "Catégorie : ".$ligne['nom']."<br><br>";
						echo "Développeur : ".$ligne['dev']."<br>";
						echo "Éditeur : ".$ligne['edi']."<br></div>";
					}
					$res->closeCursor();
				}

				else{
					/*UN DEVELOPPEUR*/
					$res = $cnx->query("select titre, jeu.idjeu, datesortie, age_requis, description, entre1.nom as dev, 
								entre2.nom as edi, etre.nom 
								from jeu, entreprise entre1, entreprise entre2, etre 
								where jeu.iddev = entre1.ident and jeu.idedi = entre2.ident and etre.idjeu = jeu.idjeu 
								and etre.nom like '".$_POST['genre']."%' and entre1.ident = ".$_POST['dev']."");

					foreach ($res as $ligne) {
						echo "<br><div style='background-color:#72D3E3'>";
						echo "<form action='voir_jeu.php' method='POST'>
								<input name='idjeu' type='hidden' value='".$ligne['idjeu']."'>
								<input type='submit' name='titre' value='".$ligne['titre']."'>
							</form>";
						echo "<br>Description : ".$ligne['description']."<br>";
						echo "Date de sortie : ".$ligne['datesortie']."<br>";
						echo "Age requis : ".$ligne['age_requis']."<br>";
						echo "Catégorie : ".$ligne['nom']."<br><br>";
						echo "Développeur : ".$ligne['dev']."<br>";
						echo "Éditeur : ".$ligne['edi']."<br></div>";
					}
					$res->closeCursor();
				}
			}

			else{
				/*UN EDITEUR*/
				if($_POST['dev'] == ""){
					/*PAS DE DEVELOPPEUR*/
					$res = $cnx->query("select titre, jeu.idjeu, datesortie, age_requis, description, entre1.nom as dev, 
								entre2.nom as edi, etre.nom 
								from jeu, entreprise entre1, entreprise entre2, etre 
								where jeu.iddev = entre1.ident and jeu.idedi = entre2.ident and etre.idjeu = jeu.idjeu 
								and etre.nom like '".$_POST['genre']."%' and entre2.ident = ".$_POST['edi']."");

					foreach ($res as $ligne) {
						echo "<br><div style='background-color:#72D3E3'>";
						echo "<form action='voir_jeu.php' method='POST'>
								<input name='idjeu' type='hidden' value='".$ligne['idjeu']."'>
								<input type='submit' name='titre' value='".$ligne['titre']."'>
							</form>";
						echo "<br>Description : ".$ligne['description']."<br>";
						echo "Date de sortie : ".$ligne['datesortie']."<br>";
						echo "Age requis : ".$ligne['age_requis']."<br>";
						echo "Catégorie : ".$ligne['nom']."<br><br>";
						echo "Développeur : ".$ligne['dev']."<br>";
						echo "Éditeur : ".$ligne['edi']."<br></div>";
					}
					$res->closeCursor();

				}
				else{
					/*UN DEVELOPPEUR*/
					$res = $cnx->query("select titre, jeu.idjeu, datesortie, age_requis, description, entre1.nom as dev, 
								entre2.nom as edi, etre.nom 
								from jeu, entreprise entre1, entreprise entre2, etre 
								where jeu.iddev = entre1.ident and jeu.idedi = entre2.ident and etre.idjeu = jeu.idjeu 
								and etre.nom like '".$_POST['genre']."%' and entre2.ident = ".$_POST['edi']." 
								and entre1.ident = ".$_POST['dev']."");

					foreach ($res as $ligne) {
						echo "<br><div style='background-color:#72D3E3'>";
						echo "<form action='voir_jeu.php' method='POST'>
								<input name='idjeu' type='hidden' value='".$ligne['idjeu']."'>
								<input type='submit' name='titre' value='".$ligne['titre']."'>
							</form>";
						echo "<br>Description : ".$ligne['description']."<br>";
						echo "Date de sortie : ".$ligne['datesortie']."<br>";
						echo "Age requis : ".$ligne['age_requis']."<br>";
						echo "Catégorie : ".$ligne['nom']."<br><br>";
						echo "Développeur : ".$ligne['dev']."<br>";
						echo "Éditeur : ".$ligne['edi']."<br></div>";
					}
					$res->closeCursor();
				}
			}

		}
		else{
			/*UN TITRE*/
			if($_POST['edi'] == ""){
				/*PAS D'EDITEUR*/
				if($_POST['dev'] == ""){
					/*PAS DE DEVELOPPEUR*/
					$res = $cnx->query("select titre, jeu.idjeu, datesortie, age_requis, description, entre1.nom as dev, 
								entre2.nom as edi, etre.nom 
								from jeu, entreprise entre1, entreprise entre2, etre 
								where jeu.iddev = entre1.ident and jeu.idedi = entre2.ident and etre.idjeu = jeu.idjeu 
								and etre.nom like '".$_POST['genre']."%' and jeu.idjeu = ".$_POST['titre']."");

					foreach ($res as $ligne) {
						echo "<br><div style='background-color:#72D3E3'>";
						echo "<form action='voir_jeu.php' method='POST'>
								<input name='idjeu' type='hidden' value='".$ligne['idjeu']."'>
								<input type='submit' name='titre' value='".$ligne['titre']."'>
							</form>";
						echo "<br>Description : ".$ligne['description']."<br>";
						echo "Date de sortie : ".$ligne['datesortie']."<br>";
						echo "Age requis : ".$ligne['age_requis']."<br>";
						echo "Catégorie : ".$ligne['nom']."<br><br>";
						echo "Développeur : ".$ligne['dev']."<br>";
						echo "Éditeur : ".$ligne['edi']."<br></div>";
					}
					$res->closeCursor();
				}

				else{
					/*UN DEVELOPPEUR*/
					$res = $cnx->query("select titre, jeu.idjeu, datesortie, age_requis, description, entre1.nom as dev, 
								entre2.nom as edi, etre.nom 
								from jeu, entreprise entre1, entreprise entre2, etre 
								where jeu.iddev = entre1.ident and jeu.idedi = entre2.ident and etre.idjeu = jeu.idjeu 
								and etre.nom like '".$_POST['genre']."%' and jeu.idjeu = ".$_POST['titre']." 
								and entre1.ident = ".$_POST['dev']."");

					foreach ($res as $ligne) {
						echo "<br><div style='background-color:#72D3E3'>";
						echo "<form action='voir_jeu.php' method='POST'>
								<input name='idjeu' type='hidden' value='".$ligne['idjeu']."'>
								<input type='submit' name='titre' value='".$ligne['titre']."'>
							</form>";
						echo "<br>Description : ".$ligne['description']."<br>";
						echo "Date de sortie : ".$ligne['datesortie']."<br>";
						echo "Age requis : ".$ligne['age_requis']."<br>";
						echo "Catégorie : ".$ligne['nom']."<br><br>";
						echo "Développeur : ".$ligne['dev']."<br>";
						echo "Éditeur : ".$ligne['edi']."<br></div>";
					}
					$res->closeCursor();
				}
			}

			else{
				/*UN EDITEUR*/
				if($_POST['dev'] == ""){
					/*PAS DE DEVELOPPEUR*/
					$res = $cnx->query("select titre, jeu.idjeu, datesortie, age_requis, description, entre1.nom as dev, 
								entre2.nom as edi, etre.nom 
								from jeu, entreprise entre1, entreprise entre2, etre 
								where jeu.iddev = entre1.ident and jeu.idedi = entre2.ident and etre.idjeu = jeu.idjeu 
								and etre.nom like '".$_POST['genre']."%' and jeu.idjeu = ".$_POST['titre']." 
								and entre2.ident =".$_POST['edi']."");

					foreach ($res as $ligne) {
						echo "<br><div style='background-color:#72D3E3'>";
						echo "<form action='voir_jeu.php' method='POST'>
								<input name='idjeu' type='hidden' value='".$ligne['idjeu']."'>
								<input type='submit' name='titre' value='".$ligne['titre']."'>
							</form>";
						echo "<br>Description : ".$ligne['description']."<br>";
						echo "Date de sortie : ".$ligne['datesortie']."<br>";
						echo "Age requis : ".$ligne['age_requis']."<br>";
						echo "Catégorie : ".$ligne['nom']."<br><br>";
						echo "Développeur : ".$ligne['dev']."<br>";
						echo "Éditeur : ".$ligne['edi']."<br></div>";
					}
					$res->closeCursor();
				}

				else{
					/*UN DEVELOPPEUR*/
					$res = $cnx->query("select titre, jeu.idjeu, datesortie, age_requis, description, entre1.nom as dev, 
								entre2.nom as edi, etre.nom 
								from jeu, entreprise entre1, entreprise entre2, etre 
								where jeu.iddev = entre1.ident and jeu.idedi = entre2.ident and etre.idjeu = jeu.idjeu 
								and etre.nom like '".$_POST['genre']."%' and jeu.idjeu = ".$_POST['titre']." 
								and entre1.ident = ".$_POST['dev']." and entre2.ident =".$_POST['edi']."");

					foreach ($res as $ligne) {
						echo "<br><div style='background-color:#72D3E3'>";
						echo "<form action='voir_jeu.php' method='POST'>
								<input name='idjeu' type='hidden' value='".$ligne['idjeu']."'>
								<input type='submit' name='titre' value='".$ligne['titre']."'>
							</form>";
						echo "<br>Description : ".$ligne['description']."<br>";
						echo "Date de sortie : ".$ligne['datesortie']."<br>";
						echo "Age requis : ".$ligne['age_requis']."<br>";
						echo "Catégorie : ".$ligne['nom']."<br><br>";
						echo "Développeur : ".$ligne['dev']."<br>";
						echo "Éditeur : ".$ligne['edi']."<br></div>";
					}
					$res->closeCursor();
				}
			}
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