<?php include("templates/head.inc.php"); ?>

<body>
	
<?php include("templates/header.inc.php"); ?>


<?php 
	if(isset($_POST["nomcreation"]) && isset($_POST["prenomcreation"]) && isset($_POST["mdpcreation"]) && isset($_POST["mailcreation"]) ){
		if(strlen($_POST["mdpcreation"]) < 6){
			echo "<p>Mot de passe trop court, veuillez en mettre un de plus de 6 caractères !</p>";
		}
		$bdd = BDconnect();
		// verifier la non existence du compte
		$req = $bdd->prepare("SELECT mail, nom, prenom FROM Utilisateur, Particulier WHERE mail=? OR nom=? AND prenom=? ");
		$req->execute(array($_POST["mailcreation"], $_POST["nomcreation"], $_POST["prenomcreation"]));

		if($req->rowCount() == 0){
		// si non existe, on insert l'utilisateur

			// trouver un rib unique
			$req = $bdd->prepare("SELECT rib FROM Compte");
			$req->execute();
			$i = 0;
			$arrayRib = array();
			while($row = $req->fetch()){
				array_push($arrayRib, $row[0]);
			}

			do {
				$futureRib = rand(100000000000, 999999999999);
			} while (in_array($futureRib,$arrayRib));
			// creer utilisateur
			$req = $bdd->prepare("INSERT INTO Utilisateur ( mail, mdp) VALUES ( ?, ?)");
			$req->execute(array( $_POST["mailcreation"], $_POST["mdpcreation"]));
			// creer particulier

			// on recupere d'abord l'id D'utiisateur qu'on vient d'inserer
			$req = $bdd->prepare("SELECT idUtilisateur FROM Utilisateur WHERE mail = ?");
			$req->execute(array($_POST["mailcreation"]));
			$row = $req->fetch();
			$numUtilisateur = $row[0];

			$req = $bdd->prepare("INSERT INTO Particulier ( idUtilisateur, nom, prenom) VALUES ( ?, ?, ?)");
			$req->execute(array( $numUtilisateur, $_POST["nomcreation"], $_POST["prenomcreation"]));


			// creer compte courant 

			// generation cryptogramme et code carte(code de carte UNIQUE)
			$crypto = rand(100, 999);

			$arraycodecarte = array();
			$req = $bdd->prepare("SELECT codeCarte FROM CompteCourant");
			$req->execute();
			while ($row = $req->fetch()) {
				array_push($arraycodecarte, $row[0]);
			}
			
			do {
				$futureCodeCarte = rand(1000000000000000, 9999999999999999);
			} while (in_array( $futureCodeCarte,$arraycodecarte));

			$req = $bdd->prepare("INSERT INTO CompteCourant (ribCompteCourant, rib, montant, codeCarte, cryptogramme, plafondPaiement, plafondActuel, idUtilisateur) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$req->execute(array($futureRib, $futureRib, 50, $futureCodeCarte, $crypto, 200, 0, $numUtilisateur));

			// update table de compte en insérant le compte courant crée
			$req = $bdd->prepare("INSERT INTO Compte (rib, somme ) VALUES (? , ?)");
			$req->execute(array($futureRib, 50));

			echo "Création de compte réussie !";

		}
		else{
			// sinon on affiche une erreur
			echo "<p class=\"text-center accountcreation\">Erreur dans le remplissage du formulaire !</p>";
		}
		
		

		
	}
	else{
			echo "<p class=\"text-center accountcreation\">Erreur dans le remplissage du formulaire !</p>";

	}	
	echo "<p class=\"text-center accountcreation\">Redirection ...</p>";
	header("refresh:5;url=index.php");
?>



<?php include("templates/footer.inc.php"); ?>

</body>
</html>