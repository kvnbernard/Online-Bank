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

	<h2 id="soustitre">Virements</h2>

	<div class="container">
		<h3>Vous voulez réaliser un virement ?</h3>
		<div id="selvirement" class="row justify-content-center">
			<a href="virementcomptes.php" id="vervirement" class="col-5">Virement entre compte</a>
			<a href="virementutilisateurs.php" id="vervirement" class="col-5">Virement vers un utilisateur</a>
		</div>
	</div>

	<div class="container">
		<h3>Historique des virements</h3>
		<h4>Virements inter-comptes - 10 derniers</h4>
		<table class="table">
			<tr>
				<th>Date</th>
				<th>Numero de Virement</th>
				<th>Montant</th>
				<th>Compte Source</th>
				<th>Compte Destination</th>
			</tr>
			<?php getVirementIC($_SESSION["identifiant"]); ?>
		</table>

		<h4>Virement vers d'autres utilisateurs - 10 derniers</h4>
		<table class="table">
			<tr>
				<th>Date</th>
				<th>Numero de Virement</th>
				<th>Montant</th>
				<th>Compte Source</th>
				<th>Destinataire</th>
			</tr>
			<?php getVirementIU($_SESSION["identifiant"]) ?>
		</table>
	</div>

	<?php include("templates/footer.inc.php"); ?>
</body>
</html>