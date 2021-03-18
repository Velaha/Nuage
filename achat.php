<?php
session_start();

if(isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Achat</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<h2>Achat</h2>

	<?php

		include("connexion.inc.php");

		if(isset($_POST['achat'])){

			/*IL N'Y A PAS DE PRIX DÉFINIT PAR JEU, PAR CONSÉQUENT ILS COUTENT TOUS 10€*/
			$datetime = date("Y-m-d");

			$res2 = $cnx->query("select age_requis, idjoueur, naissance, argent from jeu, joueur where idjeu = '".$_POST['idjeu']."' and pseudo='".$_SESSION['pseudo']."'");
			foreach ($res2 as $ligne2) {
				if($datetime-$ligne2['naissance']<$ligne2['age_requis']){
					echo "<p style='color:red'>Vous n'avez pas l'age requis pour ce jeu.</p>";
				}
				else{
					if($ligne2['argent']-10 >= 0){
					$res = $cnx->exec("insert into acheter values (".$ligne2['idjoueur'].", ".$_POST['idjeu'].", 10)");
						if($res != 1){
							echo "<p style='color:red'>Vous possédez déjà ce jeu.</p>";
						}
						else{
							$res = $cnx->exec("update joueur set argent = argent-10 where idjoueur = ".$ligne2['idjoueur']);
							if($res != 1){
								$res = $cnx->exec("delete from acheter where idjoueur=".$ligne2['idjoueur']." and idjeu=".$_POST['idjeu']);
								echo "<p style='color:red'>L'achat n'a pas pu être affectué.</p>";
							}
							else{
								echo "<p>L'achat a été réalisé avec succès.</p>";
							}
						}
					}
					else{
						echo "<p style='color:red'>Votre solde est insuffisant.</p>";
					}

				}

				
				
			}
			$res2->closeCursor();
		}
	?>

	<div>
		<form action="" method="POST">
			<input type="submit" name="accueil" value="Accueil" style="width:150px; height:40px; position:fixed; top:0.5%; right:13%">
		</form>		
	</div>

	<div>
		<form action="" method="POST">
			<input type="submit" name="deco" value="Déconnexion" style="width:150px; height:40px; position:fixed; top:0.5%; right:0.5%">
		</form>		
	</div>

	<div>
		<form action="" method="POST">
			<input type="submit" name="boutique" value="Boutique" style="width:35%; height:300px; position:fixed; top:50%; left:5%">
		</form>		
	</div>

	<div>
		<form action="" method="POST">
			<input type="submit" name="profil" value="Profil" style="width:35%; height:300px; position:fixed; top:50%; right:5%">
		</form>		
	</div>

	<div>
		<form action="" method="POST">
			<input type="submit" name="search" value="Recherche" style="width:99%; height:40px; position:fixed; top:30%">
		</form>		
	</div>


	<?php

	if(isset($_POST['accueil'])){
		header("location: accueil.php");
	}

	if(isset($_POST['deco'])){
		header("location: connexion.php");
		session_destroy();
		echo "Déconnexion réussie.";
	}

	if(isset($_POST['boutique'])){
		header("location: boutique.php");
	}

	if(isset($_POST['profil'])){
		header("location: profil.php");
	}

	if(isset($_POST['search'])){
		header("location: recherche.php");
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