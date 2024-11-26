<?php 
session_start();
if(!isset($_SESSION["identifiant"])){
	session_destroy();
	header("Location:../index.php");
}
?>

<?php  include("templates/head.inc.php"); ?>
<body>
	<?php include("templates/header.inc.php"); ?>
	
	<?php include("templates/nav.inc.php"); ?>

	<h2 id="soustitre">Virement vers un autre Utilisateur</h2>

		<div class="container">
			<div class="row justify-content-center">
				<div id="virementdiv" class="col-6 ">
					<form method="post" action="function/virementinterutilisateur.php">
						<p>Compte Source : Compte courant - <?php echo getRibCC($_SESSION["identifiant"]); ?></p>
						<input type="hidden" name="ribsource" id="ribsource" value=<?php echo getRibCC($_SESSION["identifiant"]) ?> >
						<p>
							<label for="ribdest">Rib de destination: </label>
							<input type="text" name="ribdest">
						</p>

						<p>
							<label for="montant">Montant de la transaction : </label>
							<input type="text" name="montant" id="montant">
							<label for="montant"> Euros</label>
						</p>
						<p>
							<label for="message"> Message : </label>
							<textarea name="message" id="message" cols="30" rows="10"></textarea>
						</p>
						<input type="submit" value="Réaliser le virement ">
					</form>
				</div>
			</div>
			
		</div>

	<?php include("templates/footer.inc.php"); ?>
</body>
</html>