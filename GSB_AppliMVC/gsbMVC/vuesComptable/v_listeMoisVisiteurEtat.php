<!-- Vue pour afficher la liste des mois et des visiteurs qui ont une fiche de frais
en etat VA ou MP -->

<div id="contenu">
<h2>Suivre frais</h2>
<h3>Selectionner un visiteur et un mois : </h3>
<!-- Liste des mois pour lesquels il y a une fiche de frais en etat VA ou MP -->
<form method="post" action="index.php?uc=suivreFrais&action=selectionnerVisiteur">
<div class="corpsForm">
<p>
<label for="lstMois" accesskey="n">Mois : </label>
	<select id="lstMois" name="lstMois">
		<?php
		foreach ($lesMoisDispo as $unMois)
		{
			$mois = $unMois['mois'];
			$numAnnee =  $unMois['numAnnee'];
			$numMois =  $unMois['numMois'];
			if($mois == $moisASelectionner){
		?>
				<option selected value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
				<?php 
				}
				else{ ?>
				<option value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
				<?php 
				}
			
			}
           
		   ?>    
            
        </select>
        </p>
      <div class="piedForm">
      <p>
        <input id="ok" type="submit" value="Valider" size="20" />
      </p> 
      </div>
     </div>
     </form>
     
  <!-- Liste des visiteurs pour lesquels il y a une fiche de frais en etat VA ou MP -->
     
	  <form method="post" action="index.php?uc=suivreFrais&action=actualiserFicheFrais">
	  <div class="corpsForm">
	  
	  <p>
	  <label for="nomVisiteur">Nom et Prenom du visiteur :</label>
      	<select name="nomVisiteur" id="nomVisiteur">
	  		<?php 
	  			foreach ($lesNomsVisiteurs as $Visiteur)
	  			{
	  				$nomVisiteur = $Visiteur['nom'] ;
	  		?>
	  		<option value="<?php echo $nomVisiteur ?>"><?php echo $nomVisiteur ?></option>
	  		<?php 
	  			}
	  		?>
	   </select>
      	<select name="prenomVisiteur" id="prenomVisiteur">
      	<?php 
	  		foreach ($lesNomsVisiteurs as $Visiteur)
	  		{
	  			$prenomVisiteur = $Visiteur['prenom']
	  	?>
	  		<option value="<?php echo $prenomVisiteur ?>"><?php echo $prenomVisiteur ?></option>
	  	<?php 
	  		}
	  	?>
	   </select> 
	  </p>
      <div class="piedForm">
      <p>
        <input id="ok" type="submit" value="Valider" size="20" />
      </p> 
      </div>
      </div>
      </form>
</div>