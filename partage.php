<?php
session_start();

if(isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Partage</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php
	include("connexion.inc.php");
	?>

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
	}

	?>


	<h2>Partage</h2>

	<form action="" method="POST">
		<!-- LISTE D'AMIS -->
		<select name="ami">
			<option value="" selected="selected">-- amis --</option>
			<?php
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
								echo "<option value='".$ligne2['idjoueur2']."'>".$ligne3['pseudo']."</option>";
							}
							$res3->closeCursor();
						}
						/* SI LE JOUEUR EST LE JOUEUR 2*/
						elseif($ligne['idjoueur'] == $ligne2['idjoueur2']){

							/* ON RECUPERE LE PSEUDO DU JOUEUR AMI*/
							$res3 = $cnx->query("select pseudo from joueur where idjoueur = ".$ligne2['idjoueur1']);
							foreach ($res3 as $ligne3) {
								echo "<option value='".$ligne2['idjoueur1']."'>".$ligne3['pseudo']."</option>";
							}
							$res3->closeCursor();
						}
					}
					$res2->closeCursor();			
				}
			}
			?>
		</select>

		<!-- LISTE DE MES JEUX -->
		<select name="jeu">
			<option value="" selected="selected">-- jeux --</option>
			<?php

			$res = $cnx->query("select idjeu, titre from jeu where idjeu in (select idjeu from acheter where idjoueur = (select idjoueur from joueur where pseudo = '".$_SESSION['pseudo']."'))");
			$resultat = $res->fetchAll();
			if(count($resultat) > 0){
				$res = $cnx->query("select idjeu, titre from jeu where idjeu in (select idjeu from acheter where idjoueur = (select idjoueur from joueur where pseudo = '".$_SESSION['pseudo']."'))");
				foreach ($res as $ligne) {
					echo "<option value='".$ligne['idjeu']."'>".$ligne['titre']."</option>";
				}
				$res->closeCursor();
			}
			?>
		</select>

		<!-- ACTION -->
		<input type="submit" name="partage" value="Partager" /> 
		<input type="submit" name="arreter" value="Arrêter" />
	</form>

	<?php

	if(isset($_POST['partage'])){

		/*ON VÉRIFIE QUE LE JOUEUR RECEVEUR NE POSSEDE PAS DEJA LE JEU*/
		$res = $cnx->query("select * from acheter where idjeu =".$_POST['jeu']." and idjoueur=".$_POST['ami']);
		$resultat = $res->fetchAll();
		if(count($resultat) == 1){
			echo "Votre ami posède déja ce jeu.";
		}
		else{
			/*SI IL NE LE POSSÈDE PAS*/
			$res = $cnx->query("select idjoueur from joueur where pseudo = '".$_SESSION['pseudo']."'");	
			foreach ($res as $ligne) {
				$res2 = $cnx->exec("insert into partager values (".$_POST['jeu'].", ".$ligne['idjoueur'].", ".$_POST['ami'].")");
				if($res2 == 1){
					echo "Vous avez partager votre jeu avec votre ami.";
				}
				else{
					echo "Vous n'avez pas pu partagé votre jeu.";
				}
			}
			$res->closeCursor();
		}				
	}

	if(isset($_POST['arreter'])){
		$res = $cnx->query("select idjoueur from joueur where pseudo = '".$_SESSION['pseudo']."'");		
		foreach ($res as $ligne) {
			$res2 = $cnx->query("delete from partager where idjeu = ".$_POST['jeu']." and idjoueur_pret = ".$ligne['idjoueur']." and idjoueur2 = ".$_POST['ami']."");
			$resultat = $res2->fetchAll();
			if(count($resultat) == 1){
				echo "Le partage a été interrompu.";
			}
			else{
				echo "Le partage n'a pas pu être interrompu.";
			}
		}
		$res->closeCursor();
	}
	?>

	<h2>Liste des partages</h2>

	<ul>
		<?php
		$res = $cnx->query("select idjoueur from joueur where pseudo = '".$_SESSION['pseudo']."'");
		foreach ($res as $ligne) {
			$res2 = $cnx->query("select idjeu, idjoueur2 from partager where idjoueur_pret = ".$ligne['idjoueur']."");
			foreach ($res2 as $ligne2) {
				$res3 = $cnx->query("select pseudo, titre from jeu, joueur where idjoueur = ".$ligne2['idjoueur2']." and idjeu = ".$ligne2['idjeu']."");
				foreach ($res3 as $ligne3) {
					echo "<li>".$ligne3['titre']." partagé à ".$ligne3['pseudo']."</li>";
				}
				$res3->closeCursor();
			}
			$res2->closeCursor();
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