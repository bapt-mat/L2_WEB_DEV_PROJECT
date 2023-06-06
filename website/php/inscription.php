<?php
// affiche les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


////////////////////////////////////////////
// LE SCRIPT UTILISE LA VARIABLE DE SESSION INSCRIPTION POUR AFFICHER UN MESSAGE DE RETOUR
// 0 = ERREUR
// 1 = INSCRIPTION REUSSIE
// 2 = PSEUDO OU EMAIL DEJA UTILISE
// 3 = PSEUDO OU EMAIL INVALIDE
// 4 = MOT DE PASSE INVALIDE
////////////////////////////////////////////


// Verifie les données du formulaire
if( isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['mdp']) ){
   
    // Inclusion des fonctions de vérification
    include_once('../includes/verifications.php');
    include_once('../includes/alea.php');

    // Démarre la session
    session_start();

    // Variables du formulaire
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    // Si le pseudo, email ou le mot de passe est invalide
    if( !verification_sem($pseudo, $email, $mdp) ){
        $_SESSION['INSCRIPTION'] = 3;
        header('Location: ../Connect/index.php');
        exit();
    }
    
    // Si le pseudo ou l'email est déjà utilisé
    if( pseudo_present($pseudo) && email_present($email) ){
        $_SESSION['INSCRIPTION'] = 2;
        header('Location: ../Connect/index.php');
        exit();
    }
    
    // PB INCLUSION MULTIPLE 
    // Connexion à la base de données
    //include('../includes/connex.inc.php');
    $pdo = connexion('site');
   
    try{ 
    
    // Salt specifique au code
    $salt_code = "l2_info";

    // Génération du sel
    //$salt = openssl_random_pseudo_bytes(16);
    $salt = gen_chaine(32);
    //$salt = password_hash();

    // Hashage du mot de passe
    $mdp = hash('sha512', $salt_code . $mdp . $salt);
    

    // Requete SQL
    $stmt = $pdo->prepare("INSERT INTO membres (pseudo, email, password, salt, photo_profil, date_inscription, date_connexion) VALUES (:pseudo, :email, :mdp, :salt, DEFAULT, DEFAULT, DEFAULT)");
   $stmt->bindParam(':pseudo', $pseudo);
   $stmt->bindParam(':email', $email);
   $stmt->bindParam(':mdp', $mdp);
   $stmt->bindParam(':salt', $salt, PDO::PARAM_LOB);

   // Execution de la requete
   $stmt->execute();
 
   // Si la requete a été executée
   if($stmt->rowCount() == 1)
       $_SESSION['INSCRIPTION'] = 1;
   else
       $_SESSION['INSCRIPTION'] = 0;
   

   //cherche l'id du nouveau membre

   $stmt = $pdo->prepare("SELECT id FROM membres WHERE pseudo = :pseudo");
   $stmt->bindParam(':pseudo', $pseudo);
   $stmt->execute();
   $id = $stmt->fetch();
   $id = $id['id'];

   // Ajoute la permission 4(membre) au nouveau membre 
   $stmt = $pdo->prepare("INSERT INTO membres_perm (id, id_membre, id_perm) VALUES (NULL, :id, 4)");
   $stmt->bindParam(':id', $id);
   $stmt->execute();

   // fermeture de la requete
   $stmt->closeCursor();
   $pdo = null;

   // Redirection vers la page d'inscription
   header('Location: ../Connect/index.php');
   exit();
  
      }catch(PDOException $e){
          echo $e->getMessage();
      }
}

?>
