<?php 
session_start();
if(!isset($_SESSION["identifiant"])){
	session_destroy();
	header("Location:index.php");
}

include("functions.inc.php");

if(isset($_POST["plafondpaiement"])){
	// verifier que c'est un nombre positif
	$newplafond = floatval($_POST["plafondpaiement"]);
	if($newplafond > 0){
		// changer le plafondPaiement dans la table
		$bdd = BDconnect();
		$req = $bdd->prepare("UPDATE CompteCourant SET plafondPaiement=? WHERE idUtilisateur = ?");
		$req->execute(array($newplafond, $_SESSION["identifiant"]));
		header("Location:../main.php");
	}	
	else{
		header("Location:../main.php");
	}
}
else{
	header("Location:../main.php");

}



?>