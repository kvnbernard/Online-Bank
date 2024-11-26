<?php 
session_start();
	if(!isset($_SESSION["identifiant"])){
		session_destroy();
		header("Location:index.php");
	} 
?>

<?php  include("templates/head.inc.php"); ?>
<body>
	<?php include("templates/header.inc.php"); ?>
	
	<?php include("templates/nav.inc.php"); ?>

	<h2 id="soustitre"> Informations Personelles </h2>

	<div class="container">
		<div  class="row justify-content-center">
			<div id="infopersonelles"  class="col-6">
				<p>Identifiant Client : <?php echo $_SESSION["identifiant"]; ?></p>
				<?php 
				if($_SESSION["status"] == "particulier" ){
					$tabPart = getInfoParticulier($_SESSION["identifiant"]);
					echo "<p>Adresse Mail : ".$tabPart[2]."</p>";
					echo "<p class=\"font-weight-bold\">Argent total en banque : ".getTotalMoney($_SESSION["identifiant"])."€</p>";
					echo "<p>Nom : ".$tabPart[0]."</p>";
					echo "<p>Prenom : ".$tabPart[1]."</p>";

				}
				else if($_SESSION["status"] == "entreprise" ){
					$tabEnt = getInfoEntreprise($_SESSION["identifiant"]);
					echo "<p>Adresse Mail = ".$tabEnt[2]."</p>";
					echo "<p>Argent total en banque : ".getTotalMoney($_SESSION["identifiant"])."€</p>";
					echo "<p>Nom Entreprise = ".$tabEnt[0]."</p>";
					echo "<p>Identifiant de Terminal de Paiement = ".$tabEnt[1]."</p>";
				}

				?>
			</div>
		</div>
		<h3 id="soustitre">Modifications d'informations</h3>
		<div class="row justify-content-center">

			<div id="infopersonelles"  class="col-6">
				
				<form method="post" action="function/modifyinfos.php">
					<?php 
						// recuperation des données utilisateurs générales
						$bdd = BDconnect();

						$req = $bdd->prepare("SELECT * FROM Utilisateur WHERE idUtilisateur=?");
						$req->execute(array($_SESSION["identifiant"]));
						$rowUser = $req->fetch();
					?>
					<div>
						<label for="mail" >Adresse mail : </label>
						<input type="text" name="mail" value=<?php echo $rowUser[1] ?>>
					</div>
					<div>
						<label for="mdp">Mot de passe : </label>
						<input type="password" name="mdp">
					</div>
					<?php 

						if($_SESSION["status"] == "particulier" ){
							echo "<div>";
							echo "<label for=\"nom\" >Nom : </label>";
							echo "<input type=\"text\" name=\"nom\" value=".$tabPart[0].">";
							echo "</div>";

							echo "<div>";
							echo "<label for=\"prenom\" >Prenom : </label>";
							echo "<input type=\"text\" name=\"prenom\" value=".$tabPart[1].">";
							echo "</div>";

						}
						else{
							echo "<div>";
							echo "<label for=\"entreprise\" >Nom Entreprise : </label>";
							echo "<input type=\"text\" id=\"entreprise\" name=\"entreprise\" value=".$tabEnt[0].">";
							echo "</div>";
						}
					?>
					<input type="hidden" name="status" id="status" value=<?php echo $_SESSION["status"] ?>>
					<input type="submit" value="Modifier les informations">
				</form>
			</div>
		</div>
	</div>



	<?php include("templates/footer.inc.php"); ?>
</body>
</html>