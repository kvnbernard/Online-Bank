<?php
session_start();
if(!isset($_SESSION["identifiant"])){
	session_destroy();
	header("Location:../index.php");
}

if(!isset($_GET["formnbelemcc"])){
	$_GET["formnbelemcc"] = 10;
}
?>
<?php  include("templates/head.inc.php"); ?>
<body>
	<?php include("templates/header.inc.php"); ?>
	
	<?php include("templates/nav.inc.php"); ?>

	<h2 id="soustitre">Historique des Operations</h2>
	<div class="container">
		<div class="container">
			<h3>Compte courant</h3>
			<table class="table">
				<tr>
					<th>Date</th>
					<th>Type d'operation</th>
					<th>Compte Source</th>
					<th>Montant débit</th>
					<th>Montant crédit</th>
					<th>Compte Destination</th>
					<th>Message</th>
				</tr>
				<?php getOperationCC($_SESSION["identifiant"], $_GET["formnbelemcc"]); ?>
			</table>
			<form method="get" action="" id="formnbelemcc">
				<p>
					<label for="nbelemcc">Nombre d'operations à afficher</label>
					<select type="list" name="nbelemcc">
						<option value="10">10</option>
						<option value="25">25</option>
						<option value="50">50</option>
					</select>
					<input type="submit" value="Afficher">
				</p>
			</form>
		</div>
	</div>
		

	<?php include("templates/footer.inc.php"); ?>
</body>
</html>