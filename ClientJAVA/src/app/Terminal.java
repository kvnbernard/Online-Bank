package app;


public class Terminal {
	// Attributs
	
	private static final String ID_TERMINAL = "0000000001";
	private String idTerminal;
	private Paiement paiement;
	
	// Methodes
	
	public Terminal() {
		this.setIdTerminal(ID_TERMINAL);
	}
	
	public Terminal(Paiement paiement) {
		this.setIdTerminal(ID_TERMINAL);
		this.setPaiement(paiement);
	}

	public String getIdTerminal() {
		return idTerminal;
	}

	public void setIdTerminal(String idTerminal) {
		this.idTerminal = idTerminal;
	}

	public Paiement getPaiement() {
		return paiement;
	}

	public void setPaiement(Paiement paiement) {
		this.paiement = paiement;
	}
	
}