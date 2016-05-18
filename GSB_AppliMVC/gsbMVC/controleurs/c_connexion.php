
<?php
if(!isset($_REQUEST['action'])){
	$_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];

// Selon les valeurs de $_REQUEST['action'] inclusion des différents vues en rapport avec la connexion.
switch($action){
	case 'demandeConnexion':{
		include("vues/v_connexion.php");
		break;
	}
	case 'valideConnexion':{
		$login = $_REQUEST['login'];
		$mdp = $_REQUEST['mdp'];
		$salarie = $pdo->getInfosSalarie($login,$mdp);
		if(!is_array( $salarie)){
			ajouterErreur("Login ou mot de passe incorrect");
			include("vues/v_erreurs.php");
			include("vues/v_connexion.php");
		}
		else{
			$id = $salarie['id'];
			$nom =  $salarie['nom'];
			$prenom = $salarie['prenom'];
			$profil =  $salarie['profil'];
			connecter($id,$nom,$prenom,$profil);
			if ($_SESSION['profil'] == 'visiteur') {
				include("vues/v_sommaireVisiteur.php");
			} else if ($_SESSION['profil'] == 'comptable') {
				include("vuesComptable/v_sommaireComptable.php");
			}	
		}
		break;
	}
	default :{
		include("vues/v_connexion.php");
		break;
	}
}
?>