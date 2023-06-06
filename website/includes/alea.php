<?php
// Fonction génére une chaîne de caractère aléatoire
function gen_chaine($taille = 10){
  $i; $longueur; $chaine; $chaine_caract;
  $chaine_caract = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $chaine ='';
  ($taille > 255) ? $longueur = 255 : $longueur = $taille;
  for($i=0; $i< $longueur; $i++)
    $chaine .= $chaine_caract[rand(0, 51)];
  return $chaine;
}
?> 

