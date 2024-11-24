package app;


public class Entreprise extends Utilisateur{
	// Attributs
	
	private String nomEntreprise;
	private String idUtilisateur;
	// Methodes
	
	public Entreprise(String nomEntreprise, String idUtilisateur) {
		this.nomEntreprise = nomEntreprise;
		this.setNomEntreprise(nomEntreprise);
	}

	public String getNomEntreprise() {
		return nomEntreprise;
	}

	public void setNomEntreprise(String nomEntreprise) {
		this.nomEntreprise = nomEntreprise;
	}

	public String getIdUtilisateur() {
		return idUtilisateur;
	}

	public void setIdUtilisateur(String idUtilisateur) {
		this.idUtilisateur = idUtilisateur;
	}
	
}