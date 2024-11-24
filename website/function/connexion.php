<?php 
/*
	Page de connexion au compte
	Redirige vers la page main.php
*/	
	include("functions.inc.php");
	session_start();
	//Verification elements transmis existent
	if(isset($_POST["identifiant"]) && isset($_POST["mdp"])){
		// Connexion à la bd
		$bdd = BDConnect();
		 // Verification de l'existence du compte
		 $req = $bdd->prepare("SELECT * FROM Utilisateur WHERE mail=?");
		 $req->execute(array($_POST["identifiant"]));

		 if($req->rowCount() == 0){
		 	// retour a index.php
			session_destroy();
			header('Location:../index.php');
		 	

		}
		else{
			$row = $req->fetch();
			$mdp = $row[2];
			if($_POST["mdp"] == $mdp){
				$req = $bdd->prepare("SELECT * FROM Particulier WHERE idUtilisateur = (SELECT idUtilisateur FROM Utilisateur WHERE mail=?)");
				$req->execute(array($_POST["identifiant"]));
				if($req->rowCount() > 0){
					$status = "particulier";
				}
				else{
					$status = "entreprise";
				}
				// recuperation idUtilisateur
				$req = $bdd->prepare("SELECT idUtilisateur FROM Utilisateur WHERE mail = ?");
				$req->execute(array($_POST["identifiant"]));
				$elt = $req->fetch();

				// Creation variable de session et accès a main.php
				$_SESSION["identifiant"] = $elt[0];
				$_SESSION["mdp"] = $_POST["mdp"];
				$_SESSION["status"] = $status;
				header('Location:../main.php');
		 	}
		 	else{
		 		header('Location:../index.php');
		 	}
		}



		
	}
	else{
		//header('Location:../index.php');
	}



 ?>
