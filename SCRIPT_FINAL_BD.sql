-- Liste des commandes avant d'installer la bd :
-- psql -U postgres -h localhost
-- CREATE USER bduser WITH PASSWORD 'A123456*';
-- CREATE DATABASE bdprojet ;
-- \q

-- CONNEXION A LA BD
-- psql -U bduser -h localhost -d bdprojet
-- inserer le script ensuite

DROP TABLE IF EXISTS Utilisateur;
DROP TABLE IF EXISTS Particulier;
DROP TABLE IF EXISTS Entreprise;
DROP TABLE IF EXISTS CompteCourant;
DROP TABLE IF EXISTS TypeCompteEpargne;
DROP TABLE IF EXISTS CompteEpargne;
DROP TABLE IF EXISTS Compte;
DROP TABLE IF EXISTS Transactions;
DROP TABLE IF EXISTS Contient;

CREATE TABLE Utilisateur(
	idUtilisateur SERIAL,
	mail VARCHAR(50) NOT NULL,
	mdp VARCHAR(254)NOT NULL,
	CONSTRAINT Utilisateur_pk PRIMARY KEY (idUtilisateur)
);

CREATE TABLE Particulier(
	idParticulier SERIAL,
	idUtilisateur INTEGER NOT NULL,
	nom VARCHAR(50) NOT NULL,
	prenom VARCHAR(50) NOT NULL,
	CONSTRAINT Particulier_pk PRIMARY KEY (idParticulier),
	CONSTRAINT Particulier_idUtilisateur_fk FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur (idUtilisateur)
);

CREATE TABLE Entreprise(
	idEntreprise SERIAL,
	idUtilisateur INTEGER NOT NULL,
	nomEntreprise VARCHAR(50) NOT NULL,
	idTerminal CHAR(10),
	CONSTRAINT Entreprise_pk PRIMARY KEY (idEntreprise),
	CONSTRAINT Entreprise_idUtilisateur_fk FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur (idUtilisateur)
);

CREATE TABLE CompteCourant(
	ribCompteCourant CHAR(12) NOT NULL,
	rib CHAR(12),
	montant FLOAT NOT NULL,
	codeCarte CHAR(16) NOT NULL,
	cryptogramme CHAR(3) NOT NULL,
	plafondPaiement FLOAT NOT NULL,
	plafondActuel FLOAT NOT NULL,
	idUtilisateur INTEGER NOT NULL,
	CONSTRAINT CompteCourant_pk PRIMARY KEY (rib),
	CONSTRAINT CompteCourant_fk_idUtilisateur FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur (idUtilisateur)
);


CREATE TABLE TypeCompteEpargne(
	nomTypeCompte VARCHAR(30),
	deltaTempsVersementInterets INTEGER NOT NULL,
	interets FLOAT NOT NULL,
	plafond FLOAT NOT NULL,
	CONSTRAINT TypeCompteEpargne_pk PRIMARY KEY (nomTypeCompte)
);

CREATE TABLE CompteEpargne(
	ribCompteEpargne CHAR(12) NOT NULL,
	rib CHAR(12),
	montant FLOAT NOT NULL,
	dateDerniereModif DATE NOT NULL,
	idUtilisateur INTEGER NOT NULL,
	nomTypeCompte VARCHAR(30) NOT NULL,
	CONSTRAINT CompteEpargne_pk PRIMARY KEY (rib),
	CONSTRAINT CompteEpargne_fk_idUtilisateur FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur (idUtilisateur),
	CONSTRAINT CompteEpargne_fk_nomTypeCompte FOREIGN KEY (nomTypeCompte) REFERENCES TypeCompteEpargne (nomTypeCompte)
);

CREATE TABLE Compte(
	rib CHAR(12),
	somme FLOAT NOT NULL,
	CONSTRAINT Compte_pk PRIMARY KEY (rib)
);

CREATE TABLE Transactions(
	idTransaction SERIAL,
	dateTransaction DATE NOT NULL,
	montantTransaction FLOAT NOT NULL,
	idEmetteur INTEGER NOT NULL,
	ribEmetteur CHAR(12) NOT NULL,
	idRecepteur INTEGER NOT NULL,
	ribRecepteur CHAR(12) NOT NULL,
	message VARCHAR(500),
	idTerminal CHAR(10),
	operationType VARCHAR(30) NOT NULL,
	CONSTRAINT Transactions_pk PRIMARY KEY (idTransaction)
);

CREATE TABLE Contient(
	rib CHAR(12),
	idTransaction INTEGER,
	CONSTRAINT Contient_pk PRIMARY KEY (rib, idTransaction),
	CONSTRAINT Contient_fk_rib FOREIGN KEY (rib) REFERENCES Compte (rib),
	CONSTRAINT Contient_fk_idTransaction FOREIGN KEY (idTransaction) REFERENCES Transactions (idTransaction)
);

INSERT INTO TypeCompteEpargne (nomTypeCompte, deltaTempsVersementInterets, interets, plafond) VALUES ('LivretA',15 ,0.5 , 22950);
INSERT INTO TypeCompteEpargne (nomTypeCompte, deltaTempsVersementInterets, interets, plafond) VALUES ('PEL',365 ,2.2 , 61200);
INSERT INTO TypeCompteEpargne (nomTypeCompte, deltaTempsVersementInterets, interets, plafond) VALUES ('LEP',365 ,1.0 , 7700);
INSERT INTO TypeCompteEpargne (nomTypeCompte, deltaTempsVersementInterets, interets, plafond) VALUES ('CEL',30 ,0.25 , 15300);
