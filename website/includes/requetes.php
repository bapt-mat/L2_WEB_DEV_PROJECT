<?php
if (!defined('REQUETES_PHP')) {
    define('REQUETES_PHP', true);
// fonction qui recupere le chemin vers
// la photo de l'utilisateur

// Affiche les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('connex.inc.php');
function init_info_user_var($pseudo){

    // Connexion a la base de donnees
    $pdo = connexion('site');
    
    try{
      // Requete SQL
      $stmt = $pdo->prepare('SELECT id,pseudo,email,photo_profil,date_inscription, date_connexion, ban FROM membres WHERE pseudo = :pseudo'); 

      $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);

      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      $_SESSION['info_user'] = $result;
      $stmt->closeCursor();
      $pdo = null;
    }catch(PDOException $e){
      echo $e->getMessage();
    }   
}

function path_photo($racine = "./", $user ="none"){
  
  // Connexion a la base de donnees
  $pdo = connexion('site');

  
  // Requete SQL
  $stmt = $pdo->prepare('SELECT photo_profil FROM membres WHERE pseudo = :pseudo'); 

  $stmt->bindParam(':pseudo', $user, PDO::PARAM_STR);

  $stmt->execute();
  // enregistre dans la variable bool le resultat de la requete
  $bool = $stmt->fetch(PDO::FETCH_ASSOC)['photo_profil'];
  
  // ferme la requete
  $stmt->closeCursor();
  $pdo = null;
  

  if($bool && $user != "none"){
    // l'utilisateur à une photo de profil personnalisee
    $lien = $racine.'images/users/'.$user.'.jpg';
  }else{
    // l'utilisateur n'a pas de photo de profil personnalisee
    $lien = $racine.'/images/default_pp_usr.jpg';
  }
  return $lien;
}

// fonction qui renvoi le statut d'un utilisateur
function perm_user($id_useur){
  // Connexion a la base de donnees
  $pdo = connexion('site');
  try{
    // Requete SQL
    $stmt = $pdo->prepare('SELECT id_perm FROM membres_perm WHERE id_membre = :id_membre'); 
    
    $stmt->bindParam(':id_membre', $id_useur, PDO::PARAM_INT);
    
    $stmt->execute();
    // enregistre dans la variable perm le resultat de la requete    
    $perm = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // ferme la requete
    $stmt->closeCursor();
    $pdo = null;
    
    }catch(PDOException $e){
      echo $e->getMessage();
    }
    
    return $perm['id_perm'];
}

function countUnreadMessages($userId) {
  // se connecter à la base de données
  $db = connexion('site');
  // préparer la requête SQL
  $query = "SELECT COUNT(*) AS nb_messages FROM messages WHERE id_destinataire = :userId AND message_lu = 0";

  // exécuter la requête SQL avec les paramètres appropriés
  $stmt = $db->prepare($query);
  $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
  $stmt->execute();

  // récupérer le résultat de la requête SQL et le retourner
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result['nb_messages'];
}

function est_date($date){
  return strtotime($date) !== false;
}
}
?>
