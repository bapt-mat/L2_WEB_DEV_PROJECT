<?php
session_start();
// Inclusion des fonctions de vérification
include('../includes/verifications.php');

// Si la session est déjà démarrée
if( isset($_SESSION['pseudo']) ){
    $_SESSION['CONNEXION']= 2;
    header('Location: ../Accueil/index.php');
    exit();
}

if( isset($_POST['login']) && isset($_POST['mdp']) ){
    
    /* $_SESSION['pseudo'] = "toto"; */
    /*si on laisse ca bah on peut acceder au pages avec comme pseudo toto*/
    
    // Variable du formulaire
    $login = $_POST['login'];
    $mdp = $_POST['mdp'];

    // Vérification des champs
    if( !pseudo_present($login) && !email_present($login) ){
        header('Location: ../Connect/index.php?erreur=1');
        exit();
    }

    // Connexion à la base de données
    //include('../includes/connex.inc.php');
    $pdo = connexion('site');
    try{

    // Requête SQL
    $stmt = $pdo->prepare('SELECT * FROM membres WHERE pseudo = :pseudo OR email = :email');
    $stmt->bindValue(':pseudo', $login, PDO::PARAM_STR);
    $stmt->bindValue(':email', $login, PDO::PARAM_STR);
    
    $stmt->execute();
    $user = $stmt->fetch();
    
    // verifie si l'utilisateur est ban
    if($user['ban'] == 1){
        header('Location: ../Connect/index.php?erreur=2');
        exit();
    }
    
    // Salt specifique au code
    $salt_code = "l2_info";

    $mdp = hash('sha512', $salt_code . $mdp . $user['salt']);

    if( $mdp == $user['password'] ){


        // Modification date de dernière connexion
        try {
        $stmt = $pdo->prepare('UPDATE membres SET date_connexion = NOW() WHERE id = :id');
        $stmt->bindValue(':id', $user['id'], PDO::PARAM_INT);
        $stmt->execute();
        } catch (PDOException $e) {
        $_SESSION['CONNEXION']= 1;
        echo $e->getMessage();
        }
        $stmt->closeCursor();
        $pdo = null;
    
        // Connexion réussie
        $_SESSION['CONNEXION']= 0;

        // Création des variables de session

        // Pseudo
        $_SESSION['pseudo'] = $user['pseudo'];

        //id
        $_SESSION['id'] = $user['id'];

        // Permission
        $_SESSION['permission'] = perm_user($user['id']);

        // Redirection vers la page d'accueil
        header('Location: ../Accueil/index.php');
        exit();
    }else{
        header('Location: ../Connect/index.php?erreur=1');
        exit();
    }
  }catch(PDOException $e){
    $_SESSION['CONNEXION']= 1;
    echo $e->getMessage();
  }
}
else{
    header('Location: ../Connect/index.php?erreur=1');
    exit();
}
?>
