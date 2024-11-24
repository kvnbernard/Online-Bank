package app;


public class Paiement {
	// Attributs
	
	private String montant;
	private String noCarte;
	private String cryptogramme;
	private String motif;
	
	// Methodes
	
	public Paiement(String montant, String noCarte, String cryptogramme) {
		this.setMontant(montant);
		this.setNoCarte(noCarte);
		this.setCryptogramme(cryptogramme);
	}
	
	// methods 
	
	
	public boolean validate() {
		if(this.getMontant().isEmpty() || this.getNoCarte().isEmpty() || this.getCryptogramme().isEmpty()) {
			return false;
		}
		return true;
	}
	
	public String toString() {
		return "REQUEST PAYMENT " + this.getMontant() + " " + this.getNoCarte() + " " + this.getCryptogramme();
	}
		
	
	// getters and setters

	public String getMontant() {
		return montant;
	}

	public void setMontant(String montant) {
		this.montant = montant;
	}


	public String getNoCarte() {
		return noCarte;
	}

	public void setNoCarte(String noCarte) {
		this.noCarte = noCarte;
	}

	public String getCryptogramme() {
		return cryptogramme;
	}

	public void setCryptogramme(String cryptogramme) {
		this.cryptogramme = cryptogramme;
	}


	public String getMotif() {
		return motif;
	}

	public void setMotif(String motif) {
		this.motif = motif;
	}

	

}