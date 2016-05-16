<!-- Vue pour afficher la liste des frais hors forfait d'un visiteur pour un mois donn� -->


<div id="contenu">
<form>
<table class="listeLegere">
  	   <caption>Descriptif des Eléments hors forfait :
       </caption>
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
    <div class="ensembleButton" >
    <a class="lienButton" href="index.php?uc=validerFrais&action=validerFiche" onclick="return confirm('Voulez-vous vraiment valider cette fiche?');">Valider</a>
	</div>
</form>
</div>
