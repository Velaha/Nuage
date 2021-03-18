<?php
session_start();

if(isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Profil</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php
	include("connexion.inc.php");
	?>	

	<div style="position:fixed; top:7%">
		<h2>Profil</h2>

		<?php
		$res = $cnx->query("select * from joueur where pseudo = '".$_SESSION['pseudo']."'");
		foreach ($res as $ligne) {
			echo "Votre nom : ".$ligne['nom']."<br>";
			echo "Votre pseudo : ".$ligne['pseudo']."<br>";
			echo "Votre mail : ".$ligne['mail']."<br>";
			echo "Votre monnaie : ".$ligne['argent'];
		}
		$res->closeCursor();
		?>		
	</div>

	<div>
		<form action="" method="POST">
			<input type="submit" name="deco" value="Déconnexion" style="width:150px; height:40px; position:fixed; top:0.5%; right:0.5%">
		</form>		
	</div>

	<div>
		<form action="" method="POST">
			<input type="submit" name="accueil" value="Accueil" style="width:150px; height:40px; position:fixed; top:0.5%; left:0.5%">
			
		</form>
	</div>

	<div>
		<form action="" method="POST">
			<input type="submit" name="jeux" value="Mes jeux" style="width:35%; height:150px; position:fixed; top:43.5%; left:5%">
		</form>		
	</div>

	<div>
		<form action="" method="POST">
			<input type="submit" name="search" value="Recherche" style="width:99%; height:40px; position:fixed; top:30%">
		</form>		
	</div>

	<div>
		<form action="" method="POST">
			<input type="submit" name="boutique" value="Boutique" style="width:35%; height:150px; position:fixed; top:43.5%; right:5%">
		</form>		
	</div>

	<div>
		<form action="" method="POST">
			<input type="submit" name="recharge" value="Recharger" style="width:150px; height:40px; position:fixed; top:24%; left:0.5%">
		</form>		
	</div>

	<div>
		<form method="POST">
			<input type="submit" name="amis" value="Mes amis" style="width:35%; height:150px; position:fixed; top:65%; left:5%">
		</form>
	</div>

	<div>
		<form method="POST">
			<input type="submit" name="partage" value="Gérer mes partages" style="width:35%; height:150px; position:fixed; top:65%; right:5%">
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

	if(isset($_POST['search'])){
		header("location: recherche.php");
	}

	if(isset($_POST['jeux'])){
		header("location: vos_jeux.php");
	}

	if(isset($_POST['recharge'])){
		header("location: recharge.php");
	}

	if(isset($_POST['amis'])){
		header("location: amis.php");
	}

	if(isset($_POST['partage'])){
		header("location: partage.php");
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