<?php
session_start();

if(isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Accueil</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<h2>Accueil</h2>

	<div>
		<form action="" method="POST">
			<input type="submit" name="jeux" value="Mes jeux" style="width:35%; height:300px; position:fixed; top:50%; right:5%">
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
			<input type="submit" name="search" value="Recherche" style="width:99%; height:40px; position:fixed; top:30%">
		</form>		
	</div>

	<div>
		<form action="" method="POST">
			<input type="submit" name="profil" value="Profil" style="width:150px; height:40px; position:fixed; top:0.5%; right:13%">
		</form>		
	</div>


	<?php

	if(isset($_POST['profil'])){
		header("location: profil.php");
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