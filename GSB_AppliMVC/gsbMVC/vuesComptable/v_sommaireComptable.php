<!-- Division pour le sommaire -->
<div id="menuGauche">
<div id="infosUtil">

<h2>

</h2>

</div>
<ul id="menuList">
<li >
Comptable :<br>
			<?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?><br><br>
</li>
<li >
Fiche de frais :<br>
           <li class="smenu">
              <a href="index.php?uc=validerFrais&action=selectionnerVisiteur" title="Valider une fiche de frais">Valider</a>
           </li>
            <li class="smenu">
              <a href="index.php?uc=suivreFrais&action=selectionnerMoisEtat" title="Suivre paiement d'une fiche de frais">Suivre</a><br>
           </li>
 	   		<li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">DÃ©connexion</a>
           </li>
         </ul>
        
    </div>
