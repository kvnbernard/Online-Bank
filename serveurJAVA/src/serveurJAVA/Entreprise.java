package serveurJAVA;

public class Entreprise {
	private String idterminal;
	private String iduser;
	private String rib;
	
	public Entreprise(String idterminal, String iduser, String rib) {
		this.setIdterminal(idterminal);
		this.setIduser(iduser);
		this.setRib(rib);
	}

	public String getRib() {
		return rib;
	}

	public void setRib(String rib) {
		this.rib = rib;
	}

	public String getIdterminal() {
		return idterminal;
	}

	public void setIdterminal(String idterminal) {
		this.idterminal = idterminal;
	}

	public String getIduser() {
		return iduser;
	}

	public void setIduser(String iduser) {
		this.iduser = iduser;
	}
	
}
