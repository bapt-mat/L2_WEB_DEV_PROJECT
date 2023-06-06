<?php
// affiche les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if( isset($_POST['submit']) ) {

    session_start();
    $pseudo = $_SESSION['pseudo'];
    $path_img = "../images/users/"."$pseudo".".jpg";

    $file = $_FILES['file'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    $file_ext = explode('.', $file_name);

    $file_ext_auto = array('jpg', 'jpeg', 'png');
    
    if( (count($file_ext)===2) && in_array($file_ext[1], $file_ext_auto) ) {
        if( $file_error === 0 ) {
            if( $file_size <= 2097152 ) {

                if( move_uploaded_file($file_tmp, $path_img) ) {
                  include('../includes/connex.inc.php');
                  // Change le chemin de l'image dans la base de données
                  
                  // initialisation de la connexion à la base
                  $pdo = connexion('site');

                  // préparation de la requête
                  $stmt = $pdo->prepare("UPDATE membres SET photo_profil = 1 WHERE pseudo = :pseudo");
                  $stmt->bindParam(':pseudo', $pseudo);
                  // exécution de la requête
                  $stmt->execute();

                  // fermeture de la connexion
                  $stmt->closeCursor();
                  $pdo = null;

                } else {
                    return "Une erreur est survenue lors de l'envoi de l'image";
                }
            }
        }
    }
    header('Location: ../perso/index.php');
    exit();
}
?>
