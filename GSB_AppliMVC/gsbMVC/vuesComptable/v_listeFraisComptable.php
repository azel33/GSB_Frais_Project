<!-- Vue pour afficher la liste des frais  forfait d'un visiteur pour un mois donn� dans le cas de la validation
d'une fiche de frais -->

<div id="contenu">
<strong>
Fiche de <?php echo 'Mr'.' '.$_SESSION['nomVisiteur'].' '.$_SESSION['prenomVisiteur']?><br>
Mois :<?php echo $_SESSION['Mois']?><br>
Etat : <?php echo $etatFiche ?><br>
Derniere modif : <?php echo $dateModif ?><br><br>
</strong><br><br>

<div class="encadre">
<form method="POST"  action="index.php?uc=validerFrais&action=actualiserFraisForfait">
	<fieldset>
	<legend>Eléments forfaitisés</legend>
	<div class="corpsForm">
			<?php
				foreach ($lesFraisForfait as $unFrais)
				{
				$idFrais = $unFrais['idfrais'];
				$libelle = $unFrais['libelle'];
				$quantite = $unFrais['quantite'];
				$datemodif
				?>
					<p>
						<label for="idFrais"><?php echo $libelle ?></label>
						<input type="text" id="idFrais" name="lesFrais[<?php echo $idFrais?>]" size="10" maxlength="5" value="<?php echo $quantite?>" >
					</p>
			
			<?php
				}
			?>
	 </div>
     </fieldset>
     <div class="piedForm">
     <p>
        <input id="ok" type="submit" value="Actualiser" size="20" />
     </p> 
     </div>
</form>
<!-- Vue pour afficher la liste des frais hors forfait d'un visiteur pour un mois donn� -->



	<form>
		<table class="listeLegere">
  	   	<legend>Descriptif des Eléments hors forfait :</legend>
             <tr>
                <th class="date">Date</th>
				<th class="libelle">Libellé</th>  
                <th class="montant">Montant</th>  
                <th class="action">&nbsp;</th>              
             </tr>
          
    <?php    
	    foreach( $lesFraisHorsForfait as $unFraisHorsForfait) 
		{
			$libelle = $unFraisHorsForfait['libelle'];
			$date = $unFraisHorsForfait['date'];
			$montant=$unFraisHorsForfait['montant'];
			$id = $unFraisHorsForfait['id'];
	?>		
            <tr>
                <td> <?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
                <?php
                $verifLibelle = substr($libelle, 0 , 6);
                if ($verifLibelle != "REFUSE"){
                 ?>
                <td><a href="index.php?uc=validerFrais&action=reportFraisForfait&idFrais=<?php echo $id ?>" 
				onclick="return confirm('Voulez-vous vraiment reporter ce frais?');">Reporter</a>
                <a href="index.php?uc=validerFrais&action=actualiserFraisHorsForfait&idFrais=<?php echo $id ?>" 
				onclick="return confirm('Voulez-vous vraiment supprimer ce frais?');">Supprimer</a></td>
             </tr>
	<?php		 
                }
          }
	?>	                                      
    	</table>
	</form>
    </div> 
    <div class="ensembleButton" >
    <a class="lienButton" href="index.php?uc=validerFrais&action=validerFiche" onclick="return confirm('Voulez-vous vraiment valider cette fiche?');">Valider</a>
	</div>
</div>
