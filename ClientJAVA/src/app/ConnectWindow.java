package app;



import java.awt.LayoutManager;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.WindowEvent;
import javax.swing.*;


import org.apache.log4j.Logger;


public class ConnectWindow extends JFrame implements ActionListener{
	
	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;
	// Attributs
	private Terminal terminal;
	private TCPClient client;
	private ConnexionReseau connexion;
	private Entreprise entreprise;
	private CreationRequete requestmanager;
	// elements global
	private JPanel panel;
	
	// elements panel de connexion
	private JButton connect;
	private JLabel idlabel;
	private JTextField identifiant;
	private JLabel mdplabel;
	private JTextField mdp;
	
	// elements panel paiement
	private JLabel idCode;
	private JTextField code;
	private JLabel idCVC;
	private JTextField cvc;
	private JLabel idMontant;
	private JTextField montant; 
	private JButton send;
	private static Logger logger = LoggerUtility.getLogger(ConnectWindow.class, "text");
	
	public ConnectWindow() {
		logger.info("Starting the Application");
		terminal = new Terminal();
		client = new TCPClient();
		connexion = new ConnexionReseau(client);
		requestmanager = new CreationRequete(terminal);
		int co = connexion.connexion();
		logger.info("Waiting for connexion");
		if(co == 0) {
			initconnexion();
		}
		else {
			logger.warn("Connexion Error ! - Invalid informations");
			JOptionPane.showMessageDialog(null,"Erreur lors de la connexion au serveur !", "Erreur",JOptionPane.ERROR_MESSAGE);
		}
		

		this.setContentPane(panel);
		this.setVisible(true);
	}
	// Initialisation des fenetres
	
	public void initconnexion() {
		panel = new JPanel();
		logger.info("Displaying Connexion Panel");
		System.out.println("Displaying Connexion Panel");
		this.setTitle("Ecran de connexion");
		this.setSize(300,120);
		
		
		// ajout de elements
		JPanel pconnexion1 = new JPanel();
		pconnexion1.setLayout((LayoutManager) new BoxLayout(pconnexion1,BoxLayout.LINE_AXIS));
		identifiant = new JTextField();
		//identifiant.setPreferredSize(new Dimension(120,10));
		idlabel = new JLabel("Mail : ");
		pconnexion1.add(idlabel);
		pconnexion1.add(identifiant);
		
		JPanel pconnexion2 = new JPanel();
		pconnexion2.setLayout(new BoxLayout(pconnexion2,BoxLayout.LINE_AXIS));
		mdp = new JTextField();
		//mdp.setPreferredSize(new Dimension(120,30));
		mdplabel = new JLabel("Mot de passe :");
		pconnexion2.add(mdplabel);
		pconnexion2.add(mdp);
		
		JPanel pconnexion3 = new JPanel();
		pconnexion3.setLayout(new BoxLayout(pconnexion3,BoxLayout.LINE_AXIS));
		connect = new JButton("Se connecter");
		connect.addActionListener(this);
		pconnexion3.add(connect);
		
		panel.setLayout(new BoxLayout(panel, BoxLayout.PAGE_AXIS));
		panel.add(pconnexion1);
		panel.add(pconnexion2);
		panel.add(pconnexion3);
		
	    this.setLocationRelativeTo(null);               
		this.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
	}
	
	public void initTerminal() {
		panel = new JPanel();
		logger.info("Displaying terminal ");
		System.out.println("Displaying terminal ");
		logger.info("Waiting for Payment ...");
		System.out.println("Waiting for Payment ...");
		this.setTitle("Terminal de paiement");
		this.setSize(500,150);
		
		JPanel pterminal1 = new JPanel();
		pterminal1.setLayout(new BoxLayout(pterminal1,BoxLayout.LINE_AXIS));
		idCode = new JLabel("Code : ");
		code = new JTextField();
		pterminal1.add(idCode);
		pterminal1.add(code);
		
		
		JPanel pterminal2 = new JPanel();
		pterminal2.setLayout(new BoxLayout(pterminal2,BoxLayout.LINE_AXIS));
		idCVC = new JLabel("CVC : ");
		cvc = new JTextField();
		pterminal2.add(idCVC);
		pterminal2.add(cvc);
		
		
		JPanel pterminal3 = new JPanel();
		pterminal3.setLayout(new BoxLayout(pterminal3,BoxLayout.LINE_AXIS));
		idMontant = new JLabel("Montant :");
		montant = new JTextField();
		
		pterminal3.add(idMontant);
		pterminal3.add(montant);


		
		JPanel pterminal4 = new JPanel();
		pterminal4.setLayout(new BoxLayout(pterminal4,BoxLayout.LINE_AXIS));
		send = new JButton("Payer !");
		pterminal4.add(send);
		send.addActionListener(this);
		
		
		panel.setLayout(new BoxLayout(panel, BoxLayout.PAGE_AXIS));
		panel.add(pterminal1);
		panel.add(pterminal2);
		panel.add(pterminal3);
		panel.add(pterminal4);

		
		this.setContentPane(panel);
		
		this.setLocationRelativeTo(null);
		this.setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
	}
	// ActionListeners
	public void actionPerformed(ActionEvent e) {
		Object source = e.getSource();
		if(source == connect) {
			logger.info("Trying connexion ...");
			String idStr = identifiant.getText();
			String mdpStr = mdp.getText();
			
			CreationRequete req = new CreationRequete(terminal);
			String requete = req.getRequeteConnexion(idStr,  mdpStr);
			
			connexion.envoiRequete(requete);
			String result = connexion.receptionServeur();
			int error = InterpretationRequete.interpretation(result, entreprise);
			//System.out.println(error);
			if(error != 0) {
				//JOptionPane popup = new JOptionPane();
				logger.warn("Connexion error !");
				JOptionPane.showMessageDialog(null,InterpretationRequete.ErrorMeaning(error), "Erreur !",JOptionPane.ERROR_MESSAGE);
			}
			else {
				// On affiche le panneau pour le paiement
				//System.out.println("Connexion Validée");
				logger.info("Connexion accepted !");
				initTerminal();
			}
		}
		else if(source == send) {
			logger.info("Trying a Payment");
			String strCode = code.getText();
			String strCvc = cvc.getText();
			String strMontant = montant.getText();
			Paiement paiement = new Paiement(strMontant, strCode, strCvc);
			
			if(paiement.validate()) {
				terminal.setPaiement(paiement);
				String requete = requestmanager.getRequetePaiements();
				connexion.envoiRequete(requete);
				String result = connexion.receptionServeur();
				int res = InterpretationRequete.interpretation(result, entreprise);
				if(res == 0) {
					JOptionPane.showMessageDialog(null,"Paiement validé !", "Infomation de paiement",JOptionPane.PLAIN_MESSAGE);
					logger.info("Payment valid");
					System.out.println("paiement validé ");
				}
				else {
					logger.warn("Error for Payment : error " + res + " - "+ InterpretationRequete.ErrorMeaning(res));
					JOptionPane.showMessageDialog(null,InterpretationRequete.ErrorMeaning(res), "Erreur !",JOptionPane.ERROR_MESSAGE);
					System.out.println("Erreur de paiement - " + res);
				}
				code.setText("");
				cvc.setText("");
				montant.setText("");
			}
		}
		
	}

	protected void processWindowEvent(WindowEvent e) {
        super.processWindowEvent(e);
        if(e.getID() == WindowEvent.WINDOW_CLOSING) {
        	connexion.deconnexion();
        	logger.info("Disconnect !"); 
        	System.out.println("Deconnexion");
            System.exit(0);
        }
    }
	
	// getters setters
	public JButton getConnect() {
		return connect;
	}

	public void setConnect(JButton connect) {
		this.connect = connect;
	}

	public JTextField getIdentifiant() {
		return identifiant;
	}

	public void setIdentifiant(JTextField identfiant) {
		this.identifiant = identfiant;
	}

	public JTextField getMdp() {
		return mdp;
	}

	public void setMdp(JTextField mdp) {
		this.mdp = mdp;
	}

	
	
}

