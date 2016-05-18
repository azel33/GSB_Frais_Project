<!-- Division pour le sommaire -->
 <div id="menuGauche">
        <h2>
     		Visiteur :<br>
     	</h2>
				<?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?><br><br>
      <h2>
     		Choix :<br>
     	</h2>
        <ul id="menuList">
           <li class="smenu">
              <a href="index.php?uc=gererFrais&action=saisirFrais" title="Saisie fiche de frais ">Saisie fiche de frais</a>
           </li>
           <li class="smenu">
              <a href="index.php?uc=etatFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
           </li>
 	   		<li class="smenu"><br>
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Deconnexion</a>
           </li>
         </ul>
</div>