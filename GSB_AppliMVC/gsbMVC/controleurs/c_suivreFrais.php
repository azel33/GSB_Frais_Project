<?php
include("vuesComptable/v_sommaireComptable.php");
$action = $_REQUEST['action'];
$idComptable = $_SESSION['idSalarie'];
$lesMoisDispo =$pdo->getLesMoisDisponiblesEtat();
$lesCles = array_keys( $lesMoisDispo );
$moisASelectionner = $lesCles[0];
switch($action) {
	
	// selection du mois
	case 'selectionnerMoisEtat' :
		unset($_SESSION['MoisEtat']);
		include("vuesComptable/v_listeMoisEtat.php");
		break;
		
		
	// selection du visiteur
	case 'selectionnerVisiteur' :
		unset($_SESSION['idVisiteurEtat']) ;
		if (isset($_POST['lstMois'])) {
			$_SESSION['MoisEtat']= $_POST['lstMois'];
		}
		$lesNomsVisiteurs = $pdo->getNomVisiteursEtat($_SESSION['MoisEtat']);
		include("vuesComptable/v_listeMoisVisiteurEtat.php");
		
		break ;
		
	// Affichage de la fiche frais
	case 'actualiserFicheFrais' :
		$lesNomsVisiteurs = $pdo->getNomVisiteursEtat($_SESSION['MoisEtat']);
		if (isset($_POST['nomVisiteur']) AND isset($_POST['prenomVisiteur'])){
			$idVisiteur = $pdo->getIdVisiteur($_POST['nomVisiteur'],$_POST['prenomVisiteur']);
			
			// Verification d'un resultat
			if (!empty($idVisiteur)){
				$_SESSION['nomVisiteurEtat'] = $_POST['nomVisiteur'] ;
				$_SESSION['prenomVisiteurEtat'] = $_POST['prenomVisiteur'];
					foreach ($idVisiteur as $unIdVisiteur){
						$_SESSION['idVisiteurEtat'] = $unIdVisiteur;
					}
		
			}else{
				unset($_SESSION['idVisiteurEtat']);
			}
		}	
		include("vuesComptable/v_listeMoisVisiteurEtat.php");
		if (!isset($_SESSION['idVisiteurEtat'])){
			ajouterErreur("Le visiteur selectionnÃ© n'exsite pas");
			include("vues/v_erreurs.php");
		}
		break ;
		
	case 'misEnPaiement' :
		$lesNomsVisiteurs = $pdo->getNomVisiteursEtat($_SESSION['MoisEtat']);
		$infosFicheFrais = $pdo->getLesInfosFicheFrais($_SESSION['idVisiteurEtat'],$_SESSION['MoisEtat']) ;
		$lesFraisForfait     = $pdo->getLesFraisForfait($_SESSION['idVisiteurEtat'], $_SESSION['MoisEtat']);
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($_SESSION['idVisiteurEtat'], $_SESSION['MoisEtat']);
		$numAnnee =substr( $_SESSION['MoisEtat'],0,4);
		$numMois =substr($_SESSION['MoisEtat'],4,2);
		$montantValide = $infosFicheFrais['montantValide'];
		$dateModif =  dateJourAnglais();
		$etatFiche = $infosFicheFrais['idEtat'];
		$etat ='MP' ;
		$pdo->majCompletEtatFiche($_SESSION['idVisiteurEtat'],$_SESSION['MoisEtat'],$etat,$montantValide,$dateModif);
		include("vuesComptable/v_listeMoisVisiteurEtat.php");
	    break ;
			
			
	case 'Rembourse' :
		$lesNomsVisiteurs = $pdo->getNomVisiteursEtat($_SESSION['MoisEtat']);
		$infosFicheFrais = $pdo->getLesInfosFicheFrais($_SESSION['idVisiteurEtat'],$_SESSION['MoisEtat']) ;
		$lesFraisForfait     = $pdo->getLesFraisForfait($_SESSION['idVisiteurEtat'], $_SESSION['MoisEtat']);
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($_SESSION['idVisiteurEtat'], $_SESSION['MoisEtat']);
		$numAnnee =substr( $_SESSION['MoisEtat'],0,4);
		$numMois =substr($_SESSION['MoisEtat'],4,2);
		$montantValide = $infosFicheFrais['montantValide'];
		$dateModif =  dateJourAnglais();
		$etatFiche = $infosFicheFrais['idEtat'];
		$etat ='RB' ;
		$pdo->majCompletEtatFiche($_SESSION['idVisiteurEtat'],$_SESSION['MoisEtat'],$etat,$montantValide,$dateModif);
		include("vuesComptable/v_listeMoisVisiteurEtat.php");
			
			break ;
}
// Récupération de la fiche  dispo pour un visiteur et un mois donné.
if (isset($_SESSION['idVisiteurEtat']) && isset($_SESSION['MoisEtat'])) {
	$infosFicheFrais = $pdo->getLesInfosFicheFrais($_SESSION['idVisiteurEtat'],$_SESSION['MoisEtat']) ;
	$etatFiche = $infosFicheFrais['idEtat'];
	$lesFraisForfait     = $pdo->getLesFraisForfait($_SESSION['idVisiteurEtat'], $_SESSION['MoisEtat']);
	$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($_SESSION['idVisiteurEtat'], $_SESSION['MoisEtat']);
	$numAnnee =substr( $_SESSION['MoisEtat'],0,4);
	$numMois =substr( $_SESSION['MoisEtat'],4,2);
	$libEtat = $infosFicheFrais['libEtat'];
	$montantValide = $infosFicheFrais['montantValide'];
	$nbJustificatifs = $infosFicheFrais['nbJustificatifs'];
	$dateModif =  $infosFicheFrais['dateModif'];
	$dateModif =  dateAnglaisVersFrancais($dateModif);
	$etatFiche = $infosFicheFrais['idEtat'];
	include("vuesComptable/v_etatFraisComptable.php");
}