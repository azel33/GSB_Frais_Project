<div id="contenu">
<h2>Valider fiche de frais</h2>
	  <strong> Selectionner un visiteur et un mois : </strong>
	  <form method="post" action="index.php?uc=validerFrais&action=selectionnerMois">
	  <div class="corpsForm">
	  
	  <!-- Liste des visiteurs --> 
	  <p>
	  <label for="nomVisiteur">Nom et Prenom du visiteur :</label>
      	<select name="nomVisiteur" id="nomVisiteur">
	  		<?php 
	  			foreach ($lesNomsVisiteurs as $Visiteur)
	  			{
	  				$nomVisiteur = $Visiteur['nom'] ;
	  				$prenomVisiteur = $Visiteur['prenom']
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
	  			$nomVisiteur = $Visiteur['nom'] ;
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