<?php 
session_start();
if(!isset($_SESSION["identifiant"])){
	session_destroy();
	header("Location:../index.php");
}
include("functions.inc.php");


if(isset($_POST["ribsource"]) && isset($_POST["ribdest"]) && isset($_POST["montant"]) && isset($_POST["message"])){
	$bdd = BDconnect();
	$idsource = $_SESSION["identifiant"];
	$iddest;
	// tester si le rib de dest existe
	$req = $bdd->prepare("SELECT * FROM CompteCourant WHERE rib = ?");
	$req->execute(array(intval($_POST["ribsource"])));
	
	if($req->rowCount() > 0){
		$rowSource = $req->fetch();
		$req->execute(array(intval($_POST["ribdest"])));
		if($req->rowCount() > 0){
			$rowDest = $req->fetch();
			$iddest = $rowDest[7];
			$argentdest = floatval($_POST["montant"]);
			$argentsrc = floatval($rowSource[2]);
			echo $argentsrc;

			// tester si le montant est virable 
			if($argentsrc > $argentdest){
				// changer montant dans compte
				$montantsrc = floatval($rowSource[2]) - floatval($_POST["montant"]);
				$req = $bdd->prepare("UPDATE Compte SET somme = ? WHERE rib = ?");
				$req->execute(array(floatval($montantsrc), $_POST["ribsource"]));

				$montantdest = floatval($rowSource[2]) + floatval($_POST["montant"]);
				$req = $bdd->prepare("UPDATE Compte SET somme = ? WHERE rib = ?");
				$req->execute(array(floatval($montantdest), $_POST["ribdest"]));

				// changer montants dans CC
				$req = $bdd->prepare("UPDATE CompteCourant SET montant = ? WHERE rib = ?");
				$req->execute(array(floatval($montantsrc), $_POST["ribsource"]));

				$req = $bdd->prepare("UPDATE CompteCourant SET montant = ? WHERE rib = ?");
				$req->execute(array(floatval($montantdest), $_POST["ribdest"]));
				// ajouter transaction

				$req = $bdd->prepare("INSERT INTO Transactions (dateTransaction, montantTransaction, idEmetteur, ribEmetteur, idRecepteur ,ribRecepteur, message, operationType) VALUES(?,?,?,?,?,?,?,?)");
				$req->execute(array(date("d-m-Y"), floatval($_POST["montant"]), $idsource, $_POST["ribsource"], $iddest ,$_POST["ribdest"], $_POST["message"], "Virement"));

				// redirection 
				header('Location:../main.php');

		}
		else{
			header('Location:../main.php');
		}
	}
	else{
		header('Location:../main.php');
	}

	
	}
	else{
		header('Location:../main.php');
	}
	

}
else{
	header('Location:../main.php');
}


?>