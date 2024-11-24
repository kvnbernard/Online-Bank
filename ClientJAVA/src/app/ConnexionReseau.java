package app;


import java.io.IOException ;
import java.io.BufferedReader ;
import java.io.InputStreamReader ;
import java.io.PrintWriter ;
import java.io.IOException ;
import java.net.Socket ;
import java.net.UnknownHostException ;

public class ConnexionReseau extends Thread{
	// Attributs
	
	private TCPClient client;
	
	// Methodes
	
	public ConnexionReseau(TCPClient client) {
		this.client = client;
	}
	
	// methode de connexion au serveur
	public int connexion() {
		try {
			client.setSocket(new Socket("localhost", 5000));
			client.getSocket().setSoTimeout(20000);
			client.setFlux_sortie(new PrintWriter(client.getSocket().getOutputStream (), true) );
			client.setFlux_entree(new BufferedReader (new InputStreamReader (client.getSocket().getInputStream())));
			return 0;
		} catch (UnknownHostException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			return 1;
			
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			return 1;
		}
	}
	
	
	
	// Envoi de donnees au serveur
	public void envoiRequete(String requete) {
		try {
			client.getFlux_sortie().println(requete);
		}
		catch(NullPointerException e) {
			System.err.println("erreur : envoi de donnees au serveur echoue");
		}
	}
	
	// Reception de donnes du serveur
	public String receptionServeur() {
		try {
			String data =  client.getFlux_entree().readLine();
			if(data != null) {
				return data;
			}
			return null;
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			return null;
		}
		catch (NullPointerException e) {
			System.err.println("Erreur : NullPointer lors de la reception de donnee");
			e.printStackTrace();
			return null;
		}
	}
	
	// Deconnexion
	public void deconnexion() {
		try {
			this.envoiRequete("CLIENT CONNEXION CLOSE");
			client.getFlux_entree().close();
			client.getFlux_sortie().close();
			client.getSocket().close();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}
	
}