<?php
// Initialise une constante pour la permission admin
define('ADMIN', 1);
define('PERM_MIN', 4);
define('PERM_MAX', 1);

session_start();
//affiche les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if( !isset($_SESSION['permission']) || $_SESSION['permission'] != ADMIN) {
  header('Location: ../Accueil/index.php');
}

require_once('../includes/requetes.php');

function reset_photo_profil() {
  // verifie si l'utilisateur a une photo
  if( $_SESSION['info_user']['photo_profil']) {
    // supprime la photo
    unlink(path_photo("../", $_SESSION['info_user']['pseudo']));
    // met à jour la base de données 

    $pdo = connexion('site');
    $stmt = $pdo->prepare("UPDATE membres SET photo_profil = 0 WHERE id = :id"); 
    $stmt->bindParam(':id', $_SESSION['info_user']['id']);  
    $stmt->execute();
    
    // ferme la connexion
    $stmt->closeCursor();
    $pdo = null;
  }
}

function supprimer_compte($id){

  try{
    // Connexion à la base de données
    $pdo = connexion('site');

    // Supprime le membre de la table membres_perm
    $stmt = $pdo->prepare("DELETE FROM membres_perm WHERE id_membre = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Supprime le membre de la table membres
    $stmt = $pdo->prepare("DELETE FROM membres WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // ferme la connexion
    $stmt->closeCursor();
    $pdo = null;
  } catch (PDOException $e) {
    exit();
  }
}

function ban($id){

    try{
      // Connexion à la base de données
      $pdo = connexion('site');
    
      // Prepare la requete  
      $stmt = $pdo->prepare("UPDATE membres SET ban = 1 WHERE id = :id");
      $stmt->bindParam(':id', $id);
      
      // Prepare la requete
      $stmt->execute();

      // ferme la connexion
      $stmt->closeCursor();
      $pdo = null;
    } catch (PDOException $e) {
      exit();
    }
}

function unban($id){

    try{
      // Connexion à la base de données
      $pdo = connexion('site');

      // Prepare la requete
      $stmt = $pdo->prepare("UPDATE membres SET ban = 0 WHERE id = :id");
      $stmt->bindParam(':id', $id);

      // execute la requete
      $stmt->execute();

      // ferme la connexion
      $stmt->closeCursor();
      $pdo = null;
    } catch (PDOException $e) {
      exit();
    }
}

function promouvoir($id){

    try{
      // Connexion à la base de données
      $pdo = connexion('site');

      // Recupere le niveau de permission de l'utilisateur
      $stmt = $pdo->prepare("SELECT id_perm FROM membres_perm WHERE id_membre = :id");
      $stmt->bindParam(':id', $id);
      $stmt->execute();
      $result = $stmt->fetch();
      $stmt->closeCursor();
    } catch (PDOException $e) {
      return ;
    }

      $new_perm = $result['id_perm'] - 1;

    try{
      if($new_perm >= PERM_MAX){
          // Prepare la requete
          $stmt = $pdo->prepare("UPDATE membres_perm SET id_perm = :new_perm WHERE id_membre = :id");
          $stmt->bindParam(':id', $id);
          $stmt->bindParam(':new_perm', $new_perm);

          // execute la requete
          $stmt->execute();
          $stmt->closeCursor();
      }
    } catch (PDOException $e) {
      return ;
    } 
    $pdo = null;
}


function retrograder($id){
  
    // Connexion à la base de données
    $pdo = connexion('site');

    try{
      // Recupere le niveau de permission de l'utilisateur
      $stmt = $pdo->prepare("SELECT id_perm FROM membres_perm WHERE id_membre = :id");
      $stmt->bindParam(':id', $id);
      $stmt->execute();
      $result = $stmt->fetch();
      $stmt->closeCursor();
    } catch (PDOException $e) {
      return ;
    }

    $new_perm = $result['id_perm'] + 1;

    if($new_perm <= PERM_MIN){
      
      try{
        // Prepare la requete
        $stmt = $pdo->prepare("UPDATE membres_perm SET id_perm = :new_perm WHERE id_membre = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':new_perm', $new_perm);

        // execute la requete
        $stmt->execute();
      } catch (PDOException $e) {
        return ;
      }
    }
    $stmt->closeCursor();
    $pdo = null;
}

function changer_perm($id, $new_perm){
    // Connexion à la base de données
    $pdo = connexion('site');

    // Permision mini est 1 et permision max est 4
    if($new_perm < PERM_MAX || $new_perm > PERM_MIN){
        return;
    }

    try{
      // Prepare la requete
      $stmt = $pdo->prepare("UPDATE membres_perm SET id_perm = :new_perm WHERE id_membre = :id");
      $stmt->bindParam(':id', $id);
      $stmt->bindParam(':new_perm', $new_perm);

      // execute la requete
      $stmt->execute();

      // ferme la connexion
      $stmt->closeCursor();
      $pdo = null;
    } catch (PDOException $e) {
      return ;
    }
}

// Traitement du formulaire
if( isset($_POST['submit']) ) {

  $action = $_POST['action'];
  // recupere la liste des  checkbox cocher
  $selection = $_POST['selection']; 
  
  foreach($selection as $pseudo) {

    init_info_user_var( $pseudo );
    switch($action) {
      case 'reset_photo_profil':
        reset_photo_profil();
        break;
      case 'supprimer':
        supprimer_compte($_SESSION['info_user']['id']);
        break;

      case 'bannir':
        ban($_SESSION['info_user']['id']);
        break;
    
     case 'debannir':
        unban($_SESSION['info_user']['id']);
        break;
      
     case 'promouvoir':
        changer_perm($_SESSION['info_user']['id'], perm_user($_SESSION['info_user']['id'])-1);
        break;

     case 'retrograder':
        changer_perm($_SESSION['info_user']['id'], perm_user($_SESSION['info_user']['id'])+1);
        break;   
        
      default:
        break;
    }
  }  
header('Location: index.php');
}
?>
