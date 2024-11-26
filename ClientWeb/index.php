<!-- 
	Page d'accueil
	Permet de se connecter et de créer un compte
-->

<?php include("templates/head.inc.php"); ?>

<body>
	
<?php include("templates/header.inc.php"); ?>

<div class="container-fluid">
	<form method="post" action="function/connexion.php" id="connectionform" class="row justify-content-end ">
		<p>
			<label for="identifiant">Identifiant :</label>
			<input type="text" name="identifiant" id="identifiant">
		</p>

		<p>
			<label for="mdp">Mot de passe :</label>
			<input type="password" name="mdp" id="mdp">
		</p>

		<p>
			<input type="submit" value="Se connecter">
		</p>
	</form>
</div>


<div class="container-fluid">
	<img class="img-fluid" src="images/shakinghands.jpg">
	<a href="#creationaccountform"><button class=" btn btn-info">Créer un compte !</button></a>
	<p id="imgtext">Vous souhaitez démarrer notre aventure avec nous ? </p>
</div>


<div class="container-fluid" id="explications">
	<h3>Une banque adaptée à vos besoins </h3>
	<div class="row justify-content-center ">
		
		<div class="col-3">
			<h4>Pour vos besoins !</h4>
			<p id="indexdesc">Dès la création de votre compte, vous avez un compte courant ouvert ainsi que 50€ d'offert, sans contreparties ! Vous avez de plus une grande offre de compte Epargne divers, avec une grande facilité d'ouverture et de fermeture. Modifier les montants sur vos comptes en un clic et profitez de tout le potentiel de notre banque !</p>
		</div>
		<div class="col-3">
			<h4>Pour tous !</h4>
			<p id="indexdesc">La création de compte est sans condition de ressources, et sans limite d'age. De plus, la prise en main de l'interface est aisée, ce qui permet de rapidement s'habituer aux possibilités offertes par notre banque ! De plus, les entreprises peuvent creer leur propre compte et avoir le moyen de réaliser des paiement via un terminal de paiement !</p>
		</div>
		<div class="col-3">
			<h4>Personnalisable !</h4>
			<p id="indexdesc">La création et suppression de compte Epargne est très rapide, et les virements simples à réaliser. De plus, que vous soyez un Particulier ou une Entreprise, vous aurez une expérience adaptée à vos besoins ! Gérez de plus les plafond de paiement quand vous le souhaitez, et envoyez de l'argent un un clic !</p>
		</div>
	</div>
</div>


<div class="container" id="accountcreationindex">
	<h3 class="text-center">Créez un compte dès maintenant !</h3>
	<p class="text-center font-italic">Et bénéficiez de 50 euros sur votre compte courant !</p>
	<div class="row justify-content-center">

		<div id="indexformcomptecreation" class="col-5 text-center">	
			<p id="indexsoustitre">Vous êtes un particulier ?</p>
			<form action="creercompteparticulier.php" method="post" id="creationaccountform" >
				<p>
					<label for="nomcreation"> Nom :</label>
					<input type="text" name="nomcreation" id="nomcreation" required="required" >
				</p>
				<p>
					<label for="prenomcreation"> Prenom :</label>
					<input type="text" name="prenomcreation" id="prenomcreation" required="required" >
				</p>
				<p>
					<label for="mdpcreation"> Mot de passe :</label>
					<input type="password" name="mdpcreation" id="mdpcreation" required="required" >
				</p>
				<p>
					<label for="mailcreation"> Adresse Mail :</label>
					<input type="text" name="mailcreation" id="mailcreation" required="required" >
				</p>
				<input type="submit" value="Creer votre compte Client!">
			</form>
		</div>
		
		<div id="indexformcomptecreation" class="col-5 text-center">	
			<p id="indexsoustitre">Vous êtes une Entreprise ?</p>
			<form action="creercompteentreprise.php" method="post" id="creationaccountform" >
				<p>
					<label for="nomentreprise"> Nom d'Entreprise :</label>
					<input type="text" name="nomentreprise" id="nomentreprise">
				</p>
				<p>
					<label for="mdpcreation"> Mot de passe :</label>
					<input type="password" name="mdpcreation" id="mdpcreation">
				</p>
				<p>
					<label for="mailcreation"> Adresse Mail :</label>
					<input type="text" name="mailcreation" id="mailcreation">
				</p>
				<input type="submit" value="Creer votre compte Entreprise!">
			</form>
		</div>

	</div>
</div>


<?php include("templates/footer.inc.php"); ?>

</body>
</html>