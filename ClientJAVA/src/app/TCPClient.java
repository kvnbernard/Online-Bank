package app;

import java.io.IOException ;
import java.io.BufferedReader ;
import java.io.InputStreamReader ;
import java.io.PrintWriter ;
import java.io.IOException ;
import java.net.Socket ;
import java.net.UnknownHostException ;

public class TCPClient {
	// Attributs
	
	private Socket socket ;
    private PrintWriter flux_sortie ;
    private BufferedReader flux_entree ;
    private String chaine ;
	
	// Methodes
    
    public TCPClient() {
    	this.setSocket(null);
    	this.setFlux_entree(null);
    	this.setFlux_sortie(null);
    }

	public Socket getSocket() {
		return socket;
	}

	public void setSocket(Socket socket) {
		this.socket = socket;
	}

	public PrintWriter getFlux_sortie() {
		return flux_sortie;
	}

	public void setFlux_sortie(PrintWriter flux_sortie) {
		this.flux_sortie = flux_sortie;
	}

	public BufferedReader getFlux_entree() {
		return flux_entree;
	}

	public void setFlux_entree(BufferedReader flux_entree) {
		this.flux_entree = flux_entree;
	}

	public String getChaine() {
		return chaine;
	}

	public void setChaine(String chaine) {
		this.chaine = chaine;
	}


	
}