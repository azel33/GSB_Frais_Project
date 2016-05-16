
<!-- Vue pour afficher la liste des frais forfait d'un visiteur pour un mois donn� -->

<div id="contenu">
<strong>
Fiche de <?php echo 'Mr'.' '.$_SESSION['nomVisiteur'].' '.$_SESSION['prenomVisiteur']?><br>
Mois :<?php echo $_SESSION['Mois']?><br>
Etat : <?php echo $etatFiche ?><br>
Derniere modif : <?php echo $dateModif ?><br><br>
</strong><br><br>
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
</div>