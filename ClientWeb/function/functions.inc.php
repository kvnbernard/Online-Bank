<?php 
/*
	------------------------------------------------------
				Ensemble des fonctions du site
	------------------------------------------------------
*/

// ---------------------------------------
//				FONCTIONS BD
// ---------------------------------------

// Fonction de connexion à la bd
function BDconnect(){
	try{
		$bdd=new PDO('pgsql:host=localhost;dbname=bdprojet','bduser','A123456*', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		
	}
	catch (Exception $e){
		die('Erreur : ' . $e->getMessage());
	}
	return $bdd;
}



// Fonction de fermeture de la Requete

function RequestClose($req){
	$req->closeCursor();
}


// ---------------------------------------
//			FONCTIONS AFFICHAGE
// ---------------------------------------

// recuperation monnaie compte Courant
function getMoneyCC($identifiant){
	$bdd = BDconnect();
	$req = $bdd->prepare("SELECT montant FROM CompteCourant WHERE idUtilisateur=?");
	$intid = intval($identifiant);
	$req->execute(array($intid));
	$row = $req->fetch();
	return $row[0];
}

// recuperation plafond actuel Utilisateur
function getPlafond($identifiant){
	$bdd = BDconnect();
	$req = $bdd->prepare("SELECT plafondPaiement FROM CompteCourant WHERE idUtilisateur=?");
	$intid = intval($identifiant);
	$req->execute(array($intid));
	$row = $req->fetch();
	return $row[0];
}


// recuperation de la date
function getcurrentDate(){
	$currentdate = date("d/m/Y");
	return $currentdate;
}

// recuperer infos du particulier
function getInfoParticulier($identifiant){
	$bdd = BDconnect();
	$req = $bdd->prepare("SELECT nom, prenom, mail FROM Particulier, Utilisateur WHERE Utilisateur.idUtilisateur = ?");
	$req->execute(array(intval($identifiant)));
	$row = $req->fetch();
	return $row;
}

// recuperer infos de l'entreprise
function getInfoEntreprise($identifiant){
	$bdd = BDconnect();
	$req = $bdd->prepare("SELECT nomEntreprise, idTerminal, mail FROM Entreprise, Utilisateur WHERE Utilisateur.idUtilisateur = ?");
	$req->execute(array(intval($identifiant)));
	$row = $req->fetch();
	return $row;
}

// recuperer l'argent total sur le compte 
function getTotalMoney($identifiant){
	$bdd = BDconnect();
	$total = 0;
	$req = $bdd->prepare("SELECT montant FROM CompteCourant WHERE idUtilisateur = ?");
	$req->execute(array(intval($identifiant)));
	$row = $req->fetch();
	$total+=$row[0];

	$req = $bdd->prepare("SELECT montant FROM CompteEpargne WHERE idUtilisateur = ?");
	$req->execute(array(intval($identifiant)));
	while($row = $req->fetch()){
		$total+=$row[0];
	}
	return $total;
}

// recuperer tableau les comptes actuellement possédés par l'utilisateur 
function getComptePossedeTab($identifiant){
	$bdd = BDconnect();
	$req = $bdd->prepare("SELECT nomTypeCompte, rib, montant FROM CompteEpargne WHERE idUtilisateur = ?");
	$req->execute(array($identifiant));
	while($row = $req->fetch()){
		echo "<tr>\n";
		echo "\t<td>".$row[0]."</td>\n";
		echo "\t<td>".$row[1]."</td>\n";
		echo "\t<td>".$row[2]."€</td>\n";
		echo "</tr>\n";
	}
}

// recuperer tableaux types de compte
function getTypeCompteTab(){
	$bdd = BDconnect();
	$req = $bdd->prepare("SELECT * FROM TypeCompteEpargne");
	$req->execute();
	while($row = $req->fetch()){
		echo "<tr>\n";
		echo "\t<td>".$row[0]."</td>\n";
		echo "\t<td>".$row[2]."%</td>\n";
		echo "\t<td>".$row[3]."€</td>\n";
		echo "</tr>\n";
	}
}

// recuperer tableau NomTypeComptes
function getNameTypeCompteTab(){
	$bdd = BDconnect();
	$req = $bdd->prepare("SELECT nomTypeCompte FROM TypeCompteEpargne");
	$req->execute();
	$tabNom;
	$i = 0;
	while($row = $req->fetch()){
		$tabNom[$i] = $row[0];
		$i++;
	}
	return $tabNom;
}

// recuperer options compte possedes par un utilisateur
function getOptionCompteDelete($identifiant){
	$bdd = BDconnect();
	// recuperation des comptes
	$req = $bdd->prepare("SELECT rib, nomTypeCompte FROM CompteEpargne WHERE idUtilisateur = ?");
	$req->execute(array($_SESSION["identifiant"]));
	while($row = $req->fetch()){
		echo "<option value=".$row[0].">".$row[1]." - ".$row[0]."</option>";
	}	
}

// recuperer visuel CompteEpargne en tableau pour main
function getTabCE($identifiant){
	$bdd = BDconnect();
	$req = $bdd->prepare("SELECT nomTypeCompte,rib, montant,dateDerniereModif FROM CompteEpargne WHERE idUtilisateur = ?");
	$req->execute(array($identifiant));
	while($row = $req->fetch()){
		$reqTCE = $bdd->prepare("SELECT deltaTempsVersementInterets FROM TypeCompteEpargne WHERE nomTypeCompte = ?");


		// calcul date de prochain versement

		$reqTCE->execute(array($row[0]));
		$rowTCE = $reqTCE->fetch();
		$newDate = new DateTime($row[3]);
		$newDate->modify('+'.$rowTCE[0].' day');

		echo "<tr>\n";
		echo "\t<td>".$row[0]."</td>\n";
		echo "\t<td>".$row[1]."</td>\n";
		echo "\t<td>".$row[2]."€</td>\n";
		echo "\t<td>".$newDate->format('d-m-Y') ."</td>\n";
		echo "</tr>\n";
	}
}

// option de form Compte Courant
function getOptionCC($identifiant){
	$bdd = BDconnect();
	// recuperation des comptes
	$req = $bdd->prepare("SELECT rib FROM CompteCourant WHERE idUtilisateur = ?");
	$req->execute(array($_SESSION["identifiant"]));
	$row = $req->fetch();
	echo "<option value=".$row[0]."> Compte Courant- ".$row[0]."</option>";	
}



// recuperer le rib de compte courant d'un utilisateur
function getRibCC($identifiant){
	$bdd = BDconnect();
	// recuperation des comptes
	$req = $bdd->prepare("SELECT rib FROM CompteCourant WHERE idUtilisateur = ?");
	$req->execute(array($_SESSION["identifiant"]));
	$row = $req->fetch();
	return $row[0];
}

// recuperer les infos de carte bleue d'un utilisateur
function getCarteInfoTab($identifiant, $status){
	$bdd = BDconnect();
	if($status == "particulier"){
		$req = $bdd->prepare("SELECT * FROM CompteCourant, Particulier WHERE CompteCourant.idUtilisateur = ?");
		$req->execute(array($_SESSION["identifiant"]));
		$row = $req->fetch();
		$nom = $row[10]." ".$row[11];
		$finalrow = array($row[3], $row[4], $nom, $row[5]);
		return $finalrow;
	}
	else{
		$req = $bdd->prepare("SELECT * FROM CompteCourant, Entreprise WHERE CompteCourant.idUtilisateur = ?");
		$req->execute(array($_SESSION["identifiant"]));
		$row = $req->fetch();
		$finalrow = array($row[3], $row[4],$row[10], $row[5]);
		return $finalrow;
	}
}


// Fonction d'affichage des opérations du compte Courant
function getOperationCC($identifiant, $nb){
	$bdd = BDconnect();
	// recuperer le rib de Compte Courant
	$req = $bdd->prepare("SELECT rib FROM CompteCourant WHERE idUtilisateur = ?");
	$req->execute(array($_SESSION["identifiant"]));
	$rib = $req->fetch()[0];
	$req = $bdd->prepare("SELECT * FROM Transactions WHERE ribEmetteur = ? OR ribRecepteur = ? ORDER BY idTransaction DESC");
	$req->execute(array($rib, $rib));
	$i = 0;
	while(($row = $req->fetch()) && $i < $nb ){
		if($row[4] != $row[6]){
			echo "<tr>";
			echo "<td>".$row[1]."</td>";
			echo "<td>".$row[9]."</td>";
			echo "<td>".$row[4]."</td>";
			if($row[6] == $rib){
				echo "<td>------------ €</td>";
				echo "<td>".$row[2]."€</td>";
			}
			else{
				echo "<td>".$row[2]."€</td>";
				echo "<td>------------ €</td>";
			}
			echo "<td>".$row[6]."</td>";
			echo "<td>".$row[7]."</td>";

			echo "</tr>";
			$i++;
		}
		
	}
}


// fonction d'affichage des virements inter comptes
function getVirementIC($identifiant){
	$bdd = BDconnect();
	// recuperer la liste des virements de l'utilisateur
	$req = $bdd->prepare("SELECT * FROM Transactions WHERE operationType = ? AND idEmetteur = ? AND idRecepteur = ? ORDER BY idTransaction DESC ");
	$i = 0;
	$req->execute(array("Virement", $identifiant, $identifiant));
	while(($row = $req->fetch()) && ($i < 10)){
		echo "<tr>";
		echo "<td>".$row[1]."</td>";
		echo "<td>".$row[0]."</td>";
		echo "<td>".$row[2]."€</td>";
		echo "<td>".$row[4]."</td>";
		echo "<td>".$row[6]."</td>";
		echo "</tr>";
		$i++;
	}
}


// fonction d'affichage des virements inter comptes
function getVirementIU($identifiant){
	$bdd = BDconnect();
	$nom;
	// recuperer la liste des virements de l'utilisateur
	$req = $bdd->prepare("SELECT * FROM Transactions WHERE operationType = ? AND idEmetteur = ? ORDER BY idTransaction DESC ");
	$req->execute(array("Virement", $identifiant));
	$i = 0;
	while(($row = $req->fetch()) && ($i < 10)){
		if($row[3] != $row[5]){
			echo "<tr>";
			echo "<td>".$row[1]."</td>";
			echo "<td>".$row[0]."</td>";
			echo "<td>".$row[2]."€</td>";
			echo "<td>".$row[4]."</td>";
			echo "<td>".$row[6]."</td>";
			echo "</tr>";
			$i++;
		}
		
	}

}











 ?>