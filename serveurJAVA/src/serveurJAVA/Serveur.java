package serveurJAVA;

import java.net.ServerSocket ;
import java.net.Socket ;
import java.io.IOException ;
import java.io.BufferedReader ;
import java.io.InputStreamReader ;
import java.io.PrintWriter ;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.ResultSetMetaData;
import java.sql.Statement;
import java.sql.SQLException;

public class Serveur {
	public static Entreprise entreprise;
	public static PrintWriter flux_sortie;
	public static BufferedReader flux_entree;
	public static int returnval;
	public static boolean isconnected;

	
	public static void main (String argv []) throws IOException {
        ServerSocket serverSocket = null ;
        isconnected = false;
        serverSocket = new ServerSocket (5000) ;

        Socket clientSocket = null ;
        while(true) {
        	
        	try {
                clientSocket = serverSocket.accept () ;
                isconnected = true;
            } 
            catch (IOException e) {
                System.err.println ("Accept echoue.") ;
                System.exit (1) ;
            }
            
            flux_sortie = new PrintWriter (clientSocket.getOutputStream (), true) ;
            flux_entree = new BufferedReader (
                                    new InputStreamReader (
                                    clientSocket.getInputStream ())) ;

            String chaine_entree, chaine_sortie ;
            while (( (chaine_entree = flux_entree.readLine ()) != null) && (isconnected = true)) {
            	System.out.println(chaine_entree);
                requeteAnalyse(chaine_entree);
            }
        }
        	flux_sortie.close () ;
            flux_entree.close () ;
            clientSocket.close () ;
            serverSocket.close () ;
    }
	
	public static void requeteAnalyse(String requete) {
		String[] tabreq = requete.split(" ");
		if(tabreq[0].contentEquals("CLIENT")) {
			if(tabreq[1].contentEquals("REQUEST")) {
				if(tabreq[2].contentEquals("PAYMENT")) {
					System.out.println("paiement");
					
				}
				else if(tabreq[2].contentEquals("CONNEXION") ) {
					System.out.println("connexion");
					returnval = connexion(requete);
					System.out.println(returnval);
					requeteCreation("CONNEXION");
				}
			}
			else if(tabreq[1].contentEquals("CONNEXION")) {
				if(tabreq[2].contentEquals("CLOSE")) {
					System.out.println("deconnexion");
					// deconnecter
				}
			}
		}
	}
	
	
	public static int connexion(String requete) {
		try {
			Connection conn = DriverManager.getConnection("jdbc:postgresql://localhost:5432/bdprojet", "bduser","A123456*");
			if(conn != null) {
				String[] tabreq = requete.split(" ");
				String mail = tabreq[4].toString();
				String mdp = tabreq[5].toString();
				System.out.println(mail + " - " + mdp);
				String bdreq = "SELECT * FROM Utilisateur, Entreprise WHERE mail = ? AND mdp = ?";
				System.out.println(bdreq);
				PreparedStatement stm = conn.prepareStatement(bdreq,ResultSet.TYPE_SCROLL_INSENSITIVE,
															ResultSet.CONCUR_READ_ONLY,ResultSet.HOLD_CURSORS_OVER_COMMIT);
				stm.setString(1, mail);
				stm.setString(2, mdp);
				ResultSet res = stm.executeQuery();
				
				if(res.first()) {
					System.out.println("entreprise trouvee");
					System.out.println(tabreq[3] +" - "+ res.getString(7));
					if(res.getString(7).contentEquals(tabreq[3])) {
						String idter = tabreq[3];
						String iduser = tabreq[0];
						
						String ribccreq = " SELECT rib FROM CompteCourant WHERE idUtilisateur = ?" ;
						PreparedStatement ribreq =  conn.prepareStatement(ribccreq,ResultSet.TYPE_SCROLL_INSENSITIVE,
								ResultSet.CONCUR_READ_ONLY,ResultSet.HOLD_CURSORS_OVER_COMMIT);
						ribreq.setString(1, iduser);
						ResultSet rescc = stm.executeQuery();
						if(rescc.first()) {
							System.out.println("compte trouvee - connexion validee");
							String rib = rescc.getString(1);
							entreprise = new Entreprise(idter, iduser, rib);
							return 0;
						}
						else {
							System.out.println("compte non trouve");
							return 5;
						}
					}
					else {
						System.out.println("Terminal invalide");
						return 2;
					}
				}
				else {
					System.out.println("Entreprise invalide");
					return 5;
				}
				
			}else {
				System.out.println("connexion BD ratee");
				return 4;
			}
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			return 4;
		}
	}
	
	
	public static int paiement(String requete) {
		try {
			Connection conn = DriverManager.getConnection("jdbc:postgresql://localhost:5432/bdprojet", "bduser","A123456*");
			if(conn != null) {
				String[] tabreq = requete.split(" ");
				String term = tabreq[3].toString();
				float montant = Float.parseFloat(tabreq[4].toString());
				String numcarte = tabreq[5].toString();
				String crypto = tabreq[6].toString();
				System.out.println(term + " - " + montant + " - " + numcarte + " - " + crypto);
				String bdreq = "SELECT * FROM CompteCourant WHERE codeCarte = ? AND  cryptogramme = ?";
				System.out.println(bdreq);
				PreparedStatement stm = conn.prepareStatement(bdreq,ResultSet.TYPE_SCROLL_INSENSITIVE,
															ResultSet.CONCUR_READ_ONLY,ResultSet.HOLD_CURSORS_OVER_COMMIT);
				stm.setString(1, numcarte);
				stm.setString(2, crypto);
				ResultSet res = stm.executeQuery();
				
				if(res.first()) {
					float montcptcourclt = res.getFloat(2);
					float plafondpaie = res.getFloat(5);
					if((montcptcourclt > 0) || (montcptcourclt >= montant) || (plafondpaie >= montant)) {

						// Operation sur compte du client
						montcptcourclt = montcptcourclt - montant;
						System.out.println("montant compte courant client apres operation" + montcptcourclt);
						String bdreq2 = "UPDATE CompteCourant SET montant = ? WHERE codeCarte = ?";
						System.out.println(bdreq2);
						PreparedStatement stm2 = conn.prepareStatement(bdreq2,ResultSet.TYPE_SCROLL_INSENSITIVE,
																	ResultSet.CONCUR_READ_ONLY,ResultSet.HOLD_CURSORS_OVER_COMMIT);
						stm.setFloat(1, montcptcourclt);
						stm.setString(2, numcarte);
						ResultSet res2 = stm2.executeQuery();
						
						// Operation sue compte de l'entreprise		
						String bdreq3 = "SELECT * FROM CompteCourant WHERE ribCompteCourant = ?";
						System.out.println(bdreq3);
						PreparedStatement stm3 = conn.prepareStatement(bdreq3,ResultSet.TYPE_SCROLL_INSENSITIVE,
																	ResultSet.CONCUR_READ_ONLY,ResultSet.HOLD_CURSORS_OVER_COMMIT);
						stm.setString(1, entreprise.getRib());
						ResultSet res3 = stm.executeQuery();
						
						float montcptcourent = res3.getFloat(2);
						montcptcourent = montcptcourent + montant;
						System.out.println("montant compte courant entreprise apres operation" + montcptcourent);
						String bdreq4 = "UPDATE CompteCourant SET montant = ? WHERE ribComptecourant = ?";
						System.out.println(bdreq4);
						PreparedStatement stm4 = conn.prepareStatement(bdreq4,ResultSet.TYPE_SCROLL_INSENSITIVE,
																	ResultSet.CONCUR_READ_ONLY,ResultSet.HOLD_CURSORS_OVER_COMMIT);
						stm.setFloat(1, montcptcourent);
						stm.setString(2, entreprise.getRib());
						ResultSet res4 = stm4.executeQuery();
				
						// Table Compte client modification
						String ribcptcourclt = res.getString(1);
						String bdreq5 = "UPDATE Compte SET montant = ? WHERE rib = ?";
						System.out.println(bdreq5);
						PreparedStatement stm5 = conn.prepareStatement(bdreq5,ResultSet.TYPE_SCROLL_INSENSITIVE,
																	ResultSet.CONCUR_READ_ONLY,ResultSet.HOLD_CURSORS_OVER_COMMIT);
						stm.setFloat(1, montcptcourclt);
						stm.setString(2, ribcptcourclt);
						ResultSet res5 = stm5.executeQuery();
				
						// Table Compte entreprise modification
						String bdreq6 = "UPDATE Compte SET montant = ? WHERE rib = ?";
						System.out.println(bdreq6);
						PreparedStatement stm6 = conn.prepareStatement(bdreq6,ResultSet.TYPE_SCROLL_INSENSITIVE,
																	ResultSet.CONCUR_READ_ONLY,ResultSet.HOLD_CURSORS_OVER_COMMIT);
						stm.setFloat(1, montcptcourent);
						stm.setString(2, entreprise.getRib());
						ResultSet res6 = stm6.executeQuery();
						return 0;
					}
					else {
						System.out.println("montant non valide");
						return 3;
					}
				}
				else {
					System.out.println("carte non trouvee");
					return 1;
				}
			}
			else {
				System.out.println("connexion BD ratee");
				return 4;
			}
		}
		catch (SQLException e) {
		// TODO Auto-generated catch block
		e.printStackTrace();
		return 4;
		}		
	}
	
	public static void deconnexion() {
        System.out.println("Déconnexion - id :" + entreprise.getIduser() + " - terminal "+ entreprise.getIdterminal());
        entreprise = new Entreprise(null, null, null);
        isconnected = false;
    }
	
	// mettre type PAYMENT ou CONNEXION
	public static void requeteCreation(String type) {
		if(returnval == 0) {
			System.out.println("compte non trouve");
			String req = "SERVER " + type + " DONE";
			flux_sortie.println(req);
		}
		else {
			String req = ("SERVER " + type + " NOT_DONE " + returnval);
			flux_sortie.println(req);
		}	
	}
}
