<?php 
session_start();
if(!isset($_SESSION["identifiant"])){
	session_destroy();
	header("Location:index.php");
}
include("functions.inc.php");

if(isset($_POST["mail"]) && isset($_POST["mdp"]) && isset($_POST["status"])){
	$bdd = BDconnect();
	// changement de l'identifiant et du mot de passe si voila quoi
	$req = $bdd->prepare("SELECT mail, mdp FROM Utilisateur WHERE idUtilisateur = ?");
	$req->execute(array($_SESSION["identifiant"]));
	$row = $req->fetch();

	if($row[0] != $_POST["mail"]){
		$req = $bdd->prepare("UPDATE Utilisateur SET mail = ? WHERE idUtilisateur = ?");
		$req->execute(array($_POST["mail"], $_SESSION["identifiant"]));
	}
	if(!empty($_POST["mdp"])){
		if(!($_POST["mdp"] == $row[1])){
			$req = $bdd->prepare("UPDATE Utilisateur SET mdp = ? WHERE idUtilisateur = ?");
			$req->execute(array($_POST["mdp"], $_SESSION["identifiant"]));
		}
	}
	


	// test si l'utilisateur est un particulier/entreprise
	if($_POST["status"] == "particulier"){
		if(isset($_POST["nom"]) && isset($_POST["prenom"])){
			$req = $bdd->prepare("SELECT nom, prenom FROM Particulier WHERE idUtilisateur = ?");
			$req->execute(array($_SESSION["identifiant"]));
			$row = $req->fetch();
			if($_POST["nom"] != $row[0]){
				$req = $bdd->prepare("UPDATE Particulier SET nom = ? WHERE idUtilisateur = ? ");
				$req->execute(array($_POST["nom"], $_SESSION["identifiant"]));
			}
			if($_POST["prenom"] != $row[1]){
				$req = $bdd->prepare("UPDATE Particulier SET prenom = ? WHERE idUtilisateur = ? ");
				$req->execute(array($_POST["prenom"], $_SESSION["identifiant"]));
			}
		}
	}
	else if($_POST["status"] == "entreprise"){
		if(isset($_POST["entreprise"])){
			$req = $bdd->prepare("SELECT nomEntreprise FROM Entreprise WHERE idUtilisateur = ?");
			$req->execute(array($_SESSION["identifiant"]));
			$entrname = $req->fetch();
			if($_POST["entreprise"] != $entrname){
				$req = $bdd->prepare("UPDATE Entreprise SET nomEntreprise = ? WHERE idUtilisateur = ? ");
				$req->execute(array($_POST["entreprise"], $_SESSION["identifiant"]));
			}
		}
	}
}
header('Location:../informations.php');



?>