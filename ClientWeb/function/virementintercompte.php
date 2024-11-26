<?php 
session_start();
if(!isset($_SESSION["identifiant"])){
	session_destroy();
	header("Location:../index.php");
}
include("functions.inc.php");

// test de l'existence des elements
if(isset($_POST["comptesource"]) && isset($_POST["comptedest"]) && isset($_POST["montant"])){
	$bdd = BDconnect();
	// verifier existence des 2 comptes
	$reqsrc = $bdd->prepare("SELECT * FROM Compte WHERE rib = ?");
	$reqsrc->execute(array($_POST["comptesource"]));
	if($reqsrc->rowCount() > 0){
		$reqdest = $bdd->prepare("SELECT * FROM Compte WHERE rib = ?");
		$reqdest->execute(array($_POST["comptedest"]));
		$idsource = $_SESSION["identifiant"];
		$iddest = $_SESSION["identifiant"];
		if($reqdest->rowCount() > 0){
			// recuperer les sommes presentes sur les 2 comptes en testant si ils sont courant ou epargne
			$reqsrc = $bdd->prepare("SELECT * FROM CompteCourant WHERE rib = ?");
			$reqsrc->execute(array($_POST["comptesource"]));
			if($reqsrc->rowCount()> 0){
				$sommesource = $reqsrc->fetch()[2];
			}
			else{
				$reqsrc = $bdd->prepare("SELECT * FROM CompteEpargne WHERE rib = ?");
				$reqsrc->execute(array($_POST["comptesource"]));
				$sommesource = $reqsrc->fetch()[2];
			}

			// verifier que les fonds sont bons
			if($sommesource >= intval($_POST["montant"])){
				// ajouter une transaction
				$req = $bdd->prepare("INSERT INTO Transactions (dateTransaction, montantTransaction, idEmetteur,ribEmetteur, idRecepteur,ribRecepteur, message, idTerminal, operationType) VALUES (?,?,?,?,?,?,?,?,?)");
				$req->execute(array(date("d-m-Y"), floatval($_POST["montant"]),$idsource,$_POST["comptesource"], $iddest, $_POST["comptedest"], NULL, NULL, "Virement"));

				//ajout des 2 comptes dans la table Effectue
				// recuperation de l'ID de transaction

				$req = $bdd->prepare("SELECT idTransaction FROM Transactions WHERE ribEmetteur=? AND ribRecepteur = ? AND dateTransaction = ? ORDER BY idTransaction DESC LIMIT 1"); 
				$req->execute(array($_POST["comptesource"], $_POST["comptedest"], date("d-m-Y")));
				$idTransaction = $req->fetch()[0];

				$req = $bdd->prepare("INSERT INTO Contient (rib, idTransaction) VALUES (?,?)");
				$req->execute(array($_POST["comptesource"], $idTransaction));
				$req->execute(array($_POST["comptedest"], $idTransaction));
				// calculer le nouveaux montants des comptes

				$varmontant = floatval($_POST["montant"]);

				// tester si le compte source est Courant ou Epargne
				$req = $bdd->prepare("SELECT * FROM CompteEpargne WHERE rib = ?");
				// realiser l'operation sur la table compteEpargne
				$req->execute(array($_POST["comptesource"]));

				if($req->rowCount() > 0){
					$req = $bdd->prepare("SELECT montant FROM CompteEpargne WHERE rib = ?");
					$req->execute(array($_POST["comptesource"]));
					$row = $req->fetch();
					$currentmontantsource = floatval($row[0]);
					$newmontantsource = floatval($currentmontantsource) - $varmontant;
					$req = $bdd->prepare("UPDATE CompteEpargne SET montant = ?, dateDerniereModif = ? WHERE rib = ?");
					$req->execute(array($newmontantsource,date("d-m-Y"),$_POST["comptesource"]));
				}
				else{
					$req = $bdd->prepare("SELECT montant FROM CompteCourant WHERE rib = ?");
					$req->execute(array($_POST["comptesource"]));
					$row = $req->fetch();
					$currentmontantsource = floatval($row[0]);
					$newmontantsource = floatval($currentmontantsource) - $varmontant;
					$req = $bdd->prepare("UPDATE CompteCourant SET montant = ? WHERE rib = ?");
					$req->execute(array($newmontantsource,$_POST["comptesource"]));
				}

				// tester si le compte destination est Courant ou Epargne
				$req = $bdd->prepare("SELECT * FROM CompteEpargne WHERE rib = ?");
				
				$req->execute(array($_POST["comptedest"]));
				if($req->rowCount() > 0){
					$req = $bdd->prepare("SELECT montant FROM CompteEpargne WHERE rib = ?");
					$req->execute(array($_POST["comptedest"]));
					$row = $req->fetch();
					$newmontantdest= floatval($row[0]) + $varmontant;
					echo $newmontantdest;
					$req = $bdd->prepare("UPDATE CompteEpargne SET montant = ?, dateDerniereModif = ? WHERE rib = ?");
					$req->execute(array($newmontantdest,date("d-m-Y"),$_POST["comptedest"]));
				}
				else{
					$req = $bdd->prepare("SELECT montant FROM CompteCourant WHERE rib = ?");
					$req->execute(array($_POST["comptedest"]));
					$row = $req->fetch();
					$newmontantdest= floatval($row[0]) + $varmontant;
					echo $newmontantdest;
					$req = $bdd->prepare("UPDATE CompteCourant SET montant = ? WHERE rib = ?");
					$req->execute(array($newmontantdest, $_POST["comptedest"]));
				}

				$req = $bdd->prepare("UPDATE Compte SET somme = ? WHERE rib = ?");
				$req->execute(array($newmontantsource, $_POST["comptesource"]));
				$req->execute(array($newmontantdest ,$_POST["comptedest"]));
				// redirection
				header('Location:../virement.php');
			}
			else{
				header('Location:../virement.php');
			}
		
		}
		else{
			header('Location:../virement.php');
		}
	}
	else{
		header('Location:../virement.php');
	}
}
else{
	header('Location:../virement.php');
}



















?>