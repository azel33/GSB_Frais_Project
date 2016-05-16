<!-- Division pour le sommaire -->
<div id="menuGauche">
	<div id="infosUtil">
		<h2>
		Comptable :<br>
		</h2>
				<?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?><br>
	</div>
	<ul id="menuList">
		   <li >
			<h2>	Fiche de frais :</h2><br>
		   </li>
           <li class="smenu">
              <a href="index.php?uc=validerFrais&action=selectionnerVisiteur" title="Valider une fiche de frais">Valider</a>
           </li>
            <li class="smenu">
              <a href="index.php?uc=suivreFrais&action=selectionnerMoisEtat" title="Suivre paiement d'une fiche de frais">Suivre</a>
           </li>
 	   		<li class="smenu"><br>
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">DÃ©connexion</a>
           </li>
     </ul>
</div>
