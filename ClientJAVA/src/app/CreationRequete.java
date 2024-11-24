package app;

import java.io.BufferedReader;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.util.ArrayList;


public class CreationRequete {
	// Attributs
	
	private Terminal terminal;
	
	// Methodes
	
	public CreationRequete() {
		this.terminal = null;
	}
	
	public CreationRequete(Terminal terminal) {
		this.terminal = terminal;
	}

	// Creation de la requete associee au paiement
	
	public String getRequetePaiements() {
		// Recuperation elements requete
		String idTerminal = terminal.getIdTerminal();
		String montant = terminal.getPaiement().getMontant();
		String numCarteClient = terminal.getPaiement().getNoCarte();
		String crypto = terminal.getPaiement().getCryptogramme();
		String motif = terminal.getPaiement().getMotif();
		
		// Creation et renvoi requete
		String requete = "CLIENT REQUEST PAYMENT "+ idTerminal + " " + montant + " " + numCarteClient + " " + crypto;
		return requete;
	}
	
	// Creation requete Connexion
	public String getRequeteConnexion(String mail, String mdp) {
		// recuperation elements
		String idTerminal = terminal.getIdTerminal();
		
		// Creation et renvoi requete
		String requete = "CLIENT REQUEST CONNEXION " + idTerminal + " " + mail + " " + mdp + " ";
		return requete;
	}
	
	
}
