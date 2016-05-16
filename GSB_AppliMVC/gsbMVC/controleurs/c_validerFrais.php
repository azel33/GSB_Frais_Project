<?php
include("vuesComptable/v_sommaireComptable.php");
$action = $_REQUEST['action'];
$idComptable = $_SESSION['idSalarie'];
	switch($action) {
	
		case 'selectionnerVisiteur' :
			unset($_SESSION['Mois']);
			$lesNomsVisiteurs =$pdo->getNomVisiteurs();
		    include("vuesComptable/v_listeVisiteurs.php");
			break;
	
		// Selectionne le mois en fonction des fiches de frais disponibles d'un visiteur
		case 'selectionnerMois' :
			$lesNomsVisiteurs =$pdo->getNomVisiteurs();
			if (isset($_POST['nomVisiteur']) AND isset($_POST['prenomVisiteur'])){
				unset($_SESSION['Mois']);
				$idVisiteur = $pdo->getIdVisiteur($_POST['nomVisiteur'],$_POST['prenomVisiteur']);
				$_SESSION['nomVisiteur'] = $_POST['nomVisiteur'];
				$_SESSION['prenomVisiteur'] = $_POST['prenomVisiteur'];
				if (!empty($idVisiteur)){
					$_SESSION['nomVisiteur'] = $_POST['nomVisiteur'];
					$_SESSION['prenomVisiteur'] = $_POST['prenomVisiteur'];
					foreach ($idVisiteur as $unIdVisiteur){
							 $_SESSION['idVisiteur'] = $unIdVisiteur;
					}
				}else{
					if (!isset($_POST['lstMois'])){
						unset($_SESSION['idVisiteur']);
					}
				}
			}
			// Condition si il n'y a pas de de visiteurs parmis le nom et prenom selectionné
			// et que aucun mois n' a été sélectionné on retourne à la selection du visiteur.
			if (!isset($_SESSION['idVisiteur'])){
				include("vuesComptable/v_listeVisiteurs.php");
				ajouterErreur("Le visiteur selectionnÃ© n'exsite pas");
				include("vues/v_erreurs.php");
			}else{
				$lesMois=$pdo->getLesMoisDisponibles($_SESSION['idVisiteur']);
				// Afin de sélectionner par défaut le dernier mois dans la zone de liste
				// on demande toutes les clés, et on prend la première,
				// les mois étant triés décroissants
				$lesCles = array_keys( $lesMois );
				$moisASelectionner = $lesCles[0];
				include("vuesComptable/v_listeMoisVisiteurs.php");
				
				// Récupération de(s) fiche(s) dispo pour un visiteur
				if (isset($_POST['lstMois'])) {
				 $_SESSION['Mois']= $_POST['lstMois'];
				}
			}	
			break;
			
			// Actualise les frais au forfait selon les commandes faites par le comptable 
		case 'actualiserFraisForfait' :
			$lesNomsVisiteurs =$pdo->getNomVisiteurs();
			$lesMois=$pdo->getLesMoisDisponibles($_SESSION['idVisiteur']);
			$lesCles = array_keys( $lesMois );
			$moisASelectionner = $lesCles[0];
			include("vuesComptable/v_listeMoisVisiteurs.php");
			
			// Reçoit les valeurs mises à jours des frais forfaitisés
			$lesFrais = $_POST['lesFrais'];
			
			if (lesQteFraisValides($lesFrais)) {
				$pdo->majFraisForfait($_SESSION['idVisiteur'], $_SESSION['Mois'], $lesFrais);
				$msg = "La modification des frais au forfait a bien Ã©tÃ© prise en compte";
				ajouterMessage($msg);
				include("vues/v_message.php");
			
				} else {
					ajouterErreur("Les valeurs des frais doivent Ãªtre numÃ©riques");
					include("vues/v_erreurs.php");
				}
			
				break;
			
				// Actualisation des frais hors forfait :
		case 'actualiserFraisHorsForfait' :
				$lesNomsVisiteurs =$pdo->getNomVisiteurs();
				$lesMois=$pdo->getLesMoisDisponibles($_SESSION['idVisiteur']);
				$lesCles = array_keys( $lesMois );
				$moisASelectionner = $lesCles[0];
				include("vuesComptable/v_listeMoisVisiteurs.php");
				
				$idFrais = $_REQUEST['idFrais'];
				$libelleTab = $pdo->getLibelleFraisHorsForfait($idFrais);
				foreach ($libelleTab as $unLibelle){
					$libelle = $unLibelle ;
				}
				$libelleRefuse = 'REFUSE :'.$libelle ;
				
				// Verification de la taille du nouveau libelle
				$nombreCaracter = strlen($libelleRefuse);
				echo $nombreCaracter ;
				if ($nombreCaracter > 100){
					$libelleRefuse = substr($libelleRefuse,0,100);
				}
				$pdo->majLibelleFraisHorsForfait($idFrais,$libelleRefuse,$_SESSION['idVisiteur']);
				break;
				
		// Validation de la fiche de frais
		case 'validerFiche' :
			$lesNomsVisiteurs =$pdo->getNomVisiteurs();
			$lesMois=$pdo->getLesMoisDisponibles($_SESSION['idVisiteur']);
			$lesCles = array_keys( $lesMois );
			$moisASelectionner = $lesCles[0];
			include("vuesComptable/v_listeMoisVisiteurs.php");
					// Calcul du montant total des fraisforfait
			$montantFTab = $pdo-> getMontantTotalFraisForfait($_SESSION['Mois'],$_SESSION['idVisiteur']);
			foreach ($montantFTab as $unFMontant){
				$montantFF = $unFMontant;
			}
					// Calcul du montant total des fraisHF
			$montantFHTab = $pdo->getMontantTotalFraisHorsForfait($_SESSION['Mois'],$_SESSION['idVisiteur']);
			foreach ($montantFHTab as $unFHMontant){
				$montantFH = $unFHMontant;
			}
				// Montant total
				$montantValide = $montantFF + $unFHMontant;
				// Appel de la fonction changerEtatFiche qui met à jour aussi le montantValide
				$dateModif = dateJourAnglais($_SESSION['Mois']) ;
				$pdo->majCompletEtatFiche($_SESSION['idVisiteur'], $_SESSION['Mois'], "VA", $montantValide, $dateModif);
				
				//  unset de la variable $_SESSION['mois'] pour ne plus afficher la fiche
				unset($_SESSION['mois']);
				break ;
			
		case 'reportFraisForfait' :
			$lesNomsVisiteurs =$pdo->getNomVisiteurs();
			$lesMois = $pdo->getLesMoisDisponibles($_SESSION['idVisiteur']);
			$lesCles = array_keys( $lesMois );
			$moisASelectionner = $lesCles[0];
			include("vuesComptable/v_listeMoisVisiteurs.php");
			$idFrais = $_REQUEST['idFrais'];
			
			// Permet d'obtenir le libelle du frais  à reporter
			$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($_SESSION['idVisiteur'],$_SESSION['Mois']) ;
			// Met dans une variable ale mois initial choisi + 1
			$moisAjout = ajoutMois($_SESSION['Mois']);
			
			$testMois = $pdo->estPremierFraisMois($_SESSION['idVisiteur'],$moisAjout);
			
			// creeNouveauFraisHorsForfait
			if ($pdo->estPremierFraisMois($_SESSION['idVisiteur'],$moisAjout)){
				echo 'vrai' ;
				//cree une nouvelle fiche de frais
				$pdo-> creeNouvellesLignesFrais($_SESSION['idVisiteur'],$moisAjout);
			}
			
		    //cree une nouvelle ligne de frais hors forfait avec les élèment de celle reporté.
			foreach( $lesFraisHorsForfait as $unFraisHorsForfait){
			
				$id = $unFraisHorsForfait['id'];
				if ($id === $idFrais){
					$libelle = $unFraisHorsForfait['libelle'];
					$date = $unFraisHorsForfait['date'];
					$montant=$unFraisHorsForfait['montant'];
				}
			}
			 $pdo-> supprimerFraisHorsForfait($idFrais) ;
			 $pdo-> creeNouveauFraisHorsForfait($_SESSION['idVisiteur'],$moisAjout,$libelle,$date,$montant);
			break;

	
	
	
	}
	
	// Récupération de la fiche  dispo pour un visiteur et un mois donné.
	if (isset($_SESSION['idVisiteur']) && isset($_SESSION['Mois'])) {
		$infosFicheFrais = $pdo->getLesInfosFicheFrais($_SESSION['idVisiteur'],$_SESSION['Mois']) ;
		$etatFiche = $infosFicheFrais['idEtat'];
		$dateModif = $infosFicheFrais['dateModif'];
		$lesFraisForfait     = $pdo->getLesFraisForfait($_SESSION['idVisiteur'], $_SESSION['Mois']);
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($_SESSION['idVisiteur'], $_SESSION['Mois']);
		include("vuesComptable/v_listeFraisForfaitComptable.php");
		include ("vuesComptable/v_listeFraisHorsForfaitComptable.php");
	}
?>