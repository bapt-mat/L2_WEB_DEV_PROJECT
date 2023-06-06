<?php 
// inclure le fichier de connexion a la base
include('../includes/requetes.php');
// verifie si le pseudo est present dans la base de donnees
function pseudo_present($pseudo){
  $retour = false;

  // connexion a la base de donnees
  $pdo = connexion('site');
  
  try{
    // Requete SQL
    $stmt = $pdo->prepare('SELECT pseudo FROM membres WHERE pseudo = :pseudo');
    $stmt->bindParam(':pseudo', $pseudo);
    
    // execution de la requete
    $stmt->execute();
    
    if($stmt->rowCount() > 0){
      $retour = true;
    }
  }catch(PDOException $e){
    echo $e->getMessage();
  }
  $stmt->closeCursor();
  $pdo = null;
  return $retour;
}

function email_present($email){
  $retour = false;

  // connexion a la base de donnees
  $pdo = connexion('site');
  
  try{
    // Requete SQL
    $stmt = $pdo->prepare('SELECT email FROM membres WHERE email = :email');
    $stmt->bindParam(':email', $email);
    
    // execution de la requete
    $stmt->execute();
    
    if($stmt->rowCount() > 0){
      $retour = true;
    }
  }catch(PDOException $e){
    echo $e->getMessage();
  }
  $stmt->closeCursor();
  $pdo = null;
  return $retour;
}

function verification_sem($pseudo, $email, $mdp) {
    $retour = true;
 /*   
    // Vérification du pseudo
    if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $pseudo)) {
        $retour = false;
    }
    
    // Vérification de l'adresse email
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $retour = false;
    }
    
    // Vérification du mot de passe (au moins 8 caractères, 1 lettre majuscule, 1 lettre minuscule et 1 chiffre)
    else if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{8,}$/', $mdp)) {
        $retour = false;
    }*/

    return $retour;
    
}

