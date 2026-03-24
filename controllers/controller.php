<?php
  
  session_start();

  require_once '../models/categorie.php';
  require_once '../models/inscription.php';
  require_once '../models/style.php';
  require_once '../models/livre.php';
  require_once '../models/utilisateur.php';
  require_once '../models/emprunt.php';


  $categoriedb = new categorieDB();
//   $quartierdb = new quartierDB();
//   $styledb = new styleDB();
//   $livredb = new livreDB();
//   $utilisateurdb = new UtilisateurDB();
//   $empruntdb = new empruntDB();


?>