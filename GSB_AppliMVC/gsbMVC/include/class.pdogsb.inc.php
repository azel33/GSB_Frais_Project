<?php
/** 
 * Classe d'acces aux donnees. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{   		
      	private static $serveur='mysql:host=projetgswrmartin.mysql.db';
      	private static $bdd='dbname= projetgswrmartin';   		
      	private static $user='projetgswrmartin' ;    		
      	private static $mdp='Ouverture33' ;	
		private static $monPdo;
		private static $monPdoGsb=null;
/**
 * Constructeur prive, cree l'instance de PDO qui sera sollicitee
 * pour toutes les methodes de la classe
 */				
	private function __construct(){
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui crée l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
/**
 * Retourne les informations d'un visiteur
 
 * @param  $login 
 * @param  $mdp
 * @return l'id, le nom, le prenom et le profil sous la forme d'un tableau associatif 
*/
	public function getInfosSalarie($login, $mdp){
		$req = "select salaries.id as id, salaries.nom as nom, salaries.prenom as prenom, salaries.profil as profil from salaries
		where salaries.login='$login' and salaries.mdp='$mdp'";
		$rs = PdoGsb::$monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}

/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
 * concernées par les deux arguments
 
 * La boucle foreach ne peut etre utilisee ici car on procède
 * à une modification de la structure itérée - transformation du champ date-
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
*/
	public function getLesFraisHorsForfait($idVisiteur,$mois){
	    $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idvisiteur ='$idVisiteur' 
		and lignefraishorsforfait.mois = '$mois' ";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = count($lesLignes);
		for ($i=0; $i<$nbLignes; $i++){
			$date = $lesLignes[$i]['date'];
			$lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
		}
		return $lesLignes; 
	}
/**
* Retourne le montant total de frais forfait pour un mois donné
	
* @param $mois sous la forme aaaamm
* @param $idVisiteur
* @return le montant  de frais hors forfait
*/
 public function getMontantTotalFraisForfait($mois,$idVisiteur) {
 	$req = "select sum(fraisforfait.montant*lignefraisforfait.quantite) from lignefraisforfait inner join fraisforfait
 	on fraisforfait.id = lignefraisforfait.idfraisforfait
 	where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois='$mois'";
 	$res = PdoGsb::$monPdo->query($req);
 	$laLigne = $res->fetch();
 	return $laLigne ;
 }


/**
* Retourne le montant total de frais hors forfait pour un mois donné
	
* @param $mois sous la forme aaaamm
* @param $idVisiteur 
* @return le montant  de frais hors forfait
*/
	public function getMontantTotalFraisHorsForfait($mois,$idVisiteur) {
	$req = "Select sum(LigneFraisHorsForfait.montant)as montantMois from LigneFraisHorsForfait
	where LigneFraisHorsForfait.StatutLigneFraisHorsForfait is NULL  
	and lignefraishorsforfait.idvisiteur ='$idVisiteur' and lignefraishorsforfait.mois = '$mois'" ;
	$res = PdoGsb::$monPdo->query($req);
	$laLigne = $res->fetch();
	return $laLigne ;
	}
	
/**
 * Retourne le nombre de justificatif d'un visiteur pour un mois donne
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return le nombre entier de justificatifs 
*/
	public function getNbjustificatifs($idVisiteur, $mois){
		$req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['nb'];
	}
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concernees par les deux arguments
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
*/
	public function getLesFraisForfait($idVisiteur, $mois){
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
/**
 * Retourne tous les id de la table FraisForfait
 
 * @return un tableau associatif 
*/
	public function getLesIdFrais(){
		$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
	
/**
 * Retourne l'id Etat d'une fiche de frais
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'etat de la fiche de frais
 */
	public function getEtatFrais($idVisiteur,$mois){
		$req = "select fichefrais.idEtat from fichefrais where fichefrais.idVisiteur='$idVisiteur'
		and fichefrais.mois='$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}
/**
 * Met à jour la table ligneFraisForfait
 
 * Met à jour la table ligneFraisForfait pour un visiteur et
 * un mois donne en enregistrant les nouveaux montants
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
 * @return un tableau associatif 
*/
	public function majFraisForfait($idVisiteur, $mois, $lesFrais){
		$lesCles = array_keys($lesFrais);
		foreach($lesCles as $unIdFrais){
			$qte = $lesFrais[$unIdFrais];
			$req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.idvisiteur = '$idVisiteur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
			PdoGsb::$monPdo->exec($req);
		}
		
	}
/**
 * met à jour le nombre de justificatifs de la table ficheFrais
 * pour le mois et le visiteur concerné
 
 * @param $nbJustificatifs
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs){
		$req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
		where fichefrais.idvisiteur = '$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);	
	}
/**
 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function estPremierFraisMois($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		if($laLigne['nblignesfrais'] == 0){
			$ok = true;
		}
		return $ok;
	}
/**
 * Retourne le dernier mois en cours d'un visiteur
 
 * @param $idVisiteur 
 * @return le mois sous la forme aaaamm
*/	
	public function dernierMoisSaisi($idVisiteur){
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}
	
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
 
 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function creeNouvellesLignesFrais($idVisiteur,$mois){
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
		if($laDerniereFiche['idEtat']=='CR'){
				$this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');
				
		}
		$req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
		PdoGsb::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
			PdoGsb::$monPdo->exec($req);
		 }
	}
/**
 * Crée un nouveau frais hors forfait pour un visiteur un mois donné
 * à partir des informations fournies en paramètre
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $libelle : le libelle du frais
 * @param $date : la date du frais au format français jj//mm/aaaa
 * @param $montant : le montant
*/
	public function creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant){
		$dateFr = dateFrancaisVersAnglais($date);
		$req = "insert into lignefraishorsforfait 
		values('','$idVisiteur','$mois','$libelle','$dateFr','$montant', NULL)";
		PdoGsb::$monPdo->exec($req);
	}

/**
* Récupère le libelle d'un frais or forfait à partir des informations 
* fournies en paramètre
	
* @param $idFrais
* @return un libelle
*/
	public function getLibelleFraisHorsForfait($idFrais){
		$req = "Select LigneFraisHorsForfait.libelle from LigneFraisHorsForfait
		where LigneFraisHorsForfait.id = '$idFrais'" ;
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne ;
	}
	
/**
* Modifie le libelle choisi par le comptable en mettant "REFUSE" au début et son statut
* fournies en paramètre

* @param $libelle : le libelle du frais	
* @param $idFrais : l'id du frais
*/
	public function majLibelleFraisHorsForfait ($idFrais,$libelleRefuse){
		$req = "update LigneFraisHorsForfait set LigneFraisHorsForfait.libelle = '$libelleRefuse'
		where LigneFraisHorsForfait.id = $idFrais";
		PdoGsb::$monPdo->exec($req);
		$req = "update LigneFraisHorsForfait set LigneFraisHorsForfait.StatutLigneFraisHorsForfait = 'Refuse'
		where LigneFraisHorsForfait.id = $idFrais";
		PdoGsb::$monPdo->exec($req);
		
	}

/**
 * Supprime le frais hors forfait dont l'id est passé en argument
 
 * @param $idFrais 
*/
	
	public function supprimerFraisHorsForfait($idFrais){
		$req = "delete from lignefraishorsforfait where lignefraishorsforfait.id =$idFrais ";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
	public function getLesMoisDisponibles($idVisiteur){
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' 
		order by fichefrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}


/**
* Retourne les mois pour lesquel une fiche de frais a l'etat VA ou MP
	
* @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant
*/
	public function getLesMoisDisponiblesEtat(){
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idEtat ='VA' or fichefrais.idEtat ='MP'
		order by fichefrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
					"mois"=>"$mois",
					"numAnnee"  => "$numAnnee",
					"numMois"  => "$numMois"
			);
			$laLigne = $res->fetch();
		}
		return $lesMois;
	}

/**
 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
*/	
	public function getLesInfosFicheFrais($idVisiteur,$mois){
		$req = "select ficheFrais.idEtat as idEtat, ficheFrais.dateModif as dateModif, ficheFrais.nbJustificatifs as nbJustificatifs, 
			ficheFrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join Etat on ficheFrais.idEtat = Etat.id 
			where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}
/**
 * Modifie l'état et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $etat
 */
 
	public function majEtatFicheFrais($idVisiteur,$mois,$etat){
		$req = "update ficheFrais set idEtat = '$etat', dateModif = now() 
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);
	}
	
/**
* Modifie l'état et la date et le montantValide de modification d'une fiche de frais
	
* Modifie le champ idEtat et met la date de modif à aujourd'hui

* @param $idVisiteur
* @param $mois sous la forme aaaamm
* @param $etat
* @param $montantValide
* @param $dateModif
*/
	
	public function majCompletEtatFiche($idVisiteur,$mois,$etat,$montantValide,$dateModif){
		$req = "update ficheFrais set idEtat = '$etat', dateModif = now(), montantValide='$montantValide'
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Retourne la liste des noms et prenoms des visiteurs
 
 * @return un tableau avec les noms des visiteurs
 */
 	public function getNomVisiteurs(){
		$req = "select salaries.nom, salaries.prenom from  salaries where salaries.profil ='visiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchall();
		return $lesLignes ;
    }
    
/**
* Retourne l'id d'un visiteur

* @param $nomVisiteur 
* @param $PrenomVisiteur 
* @return l'id d'un visiteur
*/
    public function getIdVisiteur($nomVisiteur,$PrenomVisiteur ){
    	$req = "select salaries.id from  salaries where salaries.nom = '$nomVisiteur' and salaries.prenom = '$PrenomVisiteur'";
    	$res = PdoGsb::$monPdo->query($req);
    	$laLigne = $res->fetch();
    	return $laLigne ;
    }



/**
* Retourne le nom et prenom des visiteur qui ont une fiche de frais pour un mois donnée
* et lorsque ca fiche frais est dans un état de mise en paiement et ou dans un etat validé.

* @param $mois
* @return l'id d'un visiteur
*/
	public function getNomVisiteursEtat($mois ){
		$req = "select salaries.nom, salaries.prenom  from  salaries inner join fichefrais 
		on fichefrais.idVisiteur = salaries.id where fichefrais.mois ='$mois' and fichefrais.idEtat ='VA' or fichefrais.mois ='$mois' and fichefrais.idEtat ='MP'";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchall();
		return $lesLignes ;
  }
}

?>