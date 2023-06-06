<?php 
// affiche les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('requetes.php');

//fonction de trie par ordre croissant
function trie_croissant($a, $b) {
    if ($a == $b) return 0;        // si  a = b alors 0
    return ($a < $b) ? -1 : 1;  // si a < b alors -1 sinon 1
}

//fonction de trie par ordre décroissant
function trie_decroissant($a, $b) {
    if ($a == $b) return 0;        // si  a = b alors 0
    return ($a > $b) ? -1 : 1;    // si a > b alors -1 sinon 1
}

function trie_alphabetique_croissant($a, $b) {
    return strcasecmp($a, $b);
}

function trie_alphabetique_decroissant($a, $b) {
    return strcasecmp($b, $a);
}

function trie_date_croissant($a, $b) {
    return ( (strtotime($a) - strtotime($b)) > 0 ) ? 1 : -1; // si la date a est plus grande que la date b alors 1 sinon -1
}

function trie_date_decroissant($a, $b) {
    return ( (strtotime($a) - strtotime($b)) > 0 ) ? -1 : 1; // si la date a est plus grande que la date b alors -1 sinon 1
}


function trie_mat_bulle_aux(&$mat, $colonne, $fonction) {
  for($i = 0; $i < count($mat); $i++) {
    for($j = 0; $j < count($mat); $j++) {
      if($fonction($mat[$i][$colonne], $mat[$j][$colonne]) == -1) {
        $tmp = $mat[$i];
        $mat[$i] = $mat[$j];
        $mat[$j] = $tmp;
      }
    }
  }
}

function trie_mat_bulle(&$mat, $colonne, $sens){


  $type = gettype($mat[0][$colonne]);

  if($type == "integer" || $type == "double"){
    $fonction = 'trie_'.$sens;
  }else if($type == "string"){
    if(est_date($mat[0][$colonne])){
      $fonction = 'trie_date_'.$sens;
    }else{
      $fonction = 'trie_alphabetique_'.$sens;
    }
  }
  trie_mat_bulle_aux($mat, $colonne, $fonction);
}

?>
