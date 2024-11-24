<?php 
/*
	script de création de compte
*/
session_start();
if(!isset($_SESSION["identifiant"])){
	session_destroy();
	header("Location:index.php");
} 

include("functions.inc.php");

if(isset($_POST["typeCompte"])){
	// connexion à la BD
	$bdd = BDConnect();
	// test si le type de compte Existe
	$req = $bdd->prepare("SELECT * FROM TypeCompteEpargne WHERE nomTypeCompte=?");
	$req->execute(array($_POST["typeCompte"]));
	if($req->rowCount() > 0){
		// creation d'un rib unique
		$req = $bdd->prepare("SELECT rib FROM Compte");
		$req->execute();
		$i = 0;
		$arrayRib = array();
		while($row = $req->fetch()){
			array_push($arrayRib, $row[0]);
		}
		do {
			$futureRib = mt_rand(100000000000, 999999999999);
		} while (in_array($futureRib,$arrayRib));
		// Enregistrement dans la BD du compteEpargne

		$req = $bdd->prepare("INSERT INTO CompteEpargne (ribCompteEpargne, rib, montant, dateDerniereModif, idUtilisateur, nomTypeCompte) VALUES (?,?,?,?,?,?)");
		$req->execute(array($futureRib, $futureRib, 0, date("d-m-Y"), $_SESSION["identifiant"], $_POST["typeCompte"]));

		// Enregistrement du compte dans latabel generique 
		$req = $bdd->prepare("INSERT INTO Compte (rib, somme ) VALUES (? , ?)");
		$req->execute(array($futureRib, 0));
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