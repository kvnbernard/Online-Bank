package app;



public class InterpretationRequete {
	
	public static int interpretation(String reception, Entreprise entreprise){
		if(reception != null) {
			String[] tabReception = reception.split(" ");
			System.out.println(reception);
			if(!tabReception[0].contentEquals("SERVER")) {
				return -1;
			}
			if(tabReception[1].contentEquals("CONNEXION")) {
				if(tabReception[2].contentEquals("DONE")) {
					return 0;
				}
				else {
					int returnvalue = Integer.parseInt(tabReception[3]);
					return returnvalue;
				}
			}
			else if(tabReception[1].contentEquals("PAYMENT")) {
				if(tabReception[2].contentEquals("DONE")) {
					return 0;
				}
				else {
					int returnvalue = Integer.parseInt(tabReception[3]);
					return returnvalue;
				}
			}
			return 0;
		}
		return -1;
	}
	
	public static String ErrorMeaning(int error) {
		if(error == -1) {
			return "Erreur de reception";
		}
		else if(error == 1) {
			return "Carte non trouvée !";
		}
		else if(error == 2) {
			return "Id de terminal non trouvé !";
		}
		else if(error == 3) {
			return "Erreur dans le montant !";
		}
		else if(error == 4) {
			return "Connexion à la Base de donnée impossible !";
		}
		else if(error == 5) {
			return "Identifiant ou Mot de passe erroné !";
		}
		return null;
	}
}