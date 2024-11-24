<?php 
session_start();
if(!isset($_SESSION["identifiant"])){
	session_destroy();
	header("Location:index.php");
}
include("functions.inc.php");

if(isset($_POST["rib"])){
	// Connexion à la BD
	$bdd = BDconnect();
	// verification de l'existence du rib
	$req = $bdd->prepare("SELECT * FROM CompteEpargne WHERE rib = ?");
	$req->execute(array($_POST["rib"]));
	if($req->rowCount() > 0){
		$row = $req->fetch();
		$moneyCE = floatval($row[2]);
		// delete ligne associée dans Compte
		$req = $bdd->prepare("DELETE FROM CompteEpargne WHERE rib = ?");
		$req->execute(array($_POST["rib"]));
		// get largent actuel du compteCourant
		$req = $bdd->prepare("SELECT montant FROM CompteCourant WHERE idUtilisateur = ?");
		$req->execute(array($_SESSION["identifiant"]));
		$elt = $req->fetch()[0];
		$actuelmoney = floatval($elt);
		$newMoney = $moneyCE + $actuelmoney;
		// ajout de l'argent dans le CompteCourant
		$req = $bdd->prepare("UPDATE CompteCourant SET montant = ? WHERE idUtilisateur = ?");
		$req->execute(array($newMoney, $_SESSION["identifiant"]));
		$req = $bdd->prepare("UPDATE Compte SET somme = ? WHERE rib = ?");
		$req->execute(array($newMoney, $_POST["rib"]));
		// On supprime tout les elements de Contient qui ont des infos sur le compte
		$req = $bdd->prepare("DELETE FROM Contient WHERE rib = ?");
		$req->execute(array($_POST["rib"]));
		// delete ligne associée dans CompteEpargne
		$req = $bdd->prepare("DELETE FROM Compte WHERE rib=?");
		$req->execute(array($_POST["rib"]));

		// redirection 
		header("Location:../gestioncompte.php");
	}
	else{
		header("Location:../gestioncompte.php");
	}
}
else{
	header("Location:../gestioncompte.php");
}







 ?>