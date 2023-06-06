<?php
session_start();
// Connexion à la base de données
include('../includes/verifications.php');


//Suppression d'un message
if (isset($_GET['id']) AND !empty($_GET['id'])){
    $getid = $_GET['id'];

    //Récupération du message
    $bdd_forum = connexion('site');
    try{
        $recupMessage = $bdd_forum->prepare('SELECT * FROM forum_messages WHERE id = :id');
        $recupMessage->bindParam(':id', $getid);
        $recupMessage->execute();
        $message = $recupMessage->fetch();

        //Récupération du sujet
        $recupSujet = $bdd_forum->prepare('SELECT * FROM forum_sujet WHERE id = :id');
        $recupSujet->bindParam(':id', $message['id_sujet']);
        $recupSujet->execute();
        $sujet = $recupSujet->fetch();

        $recupMessage->closeCursor();
        $recupSujet->closeCursor();
        $bdd_forum = null;
    }
    catch (PDOException $e){
        echo $e->getMessage();
        exit();
    }

    if ($message['id_auteur'] == $_SESSION['id'] OR $_SESSION['permission']==1)){
        //Suppression de l'image du message
        if (!empty($message['image_path'])){
            unlink($message['image_path']);
        }
        
        // remplacement du message par 'message supprimé'
        $messageSuppr = "Message supprimé";

        $bdd_forum = connexion('site');
        try{
            $supprimerMessage = $bdd_forum->prepare('UPDATE forum_messages SET message = :message WHERE id = :id');
            $supprimerMessage->bindParam(':message', $messageSuppr);
            $supprimerMessage->bindParam(':id', $getid);
            $supprimerMessage->execute();
            $supprimerMessage->closeCursor();
            $bdd_forum = null;
        }
        catch (PDOException $e){
            echo $e->getMessage();
            exit();
        }

        //Redirection vers la page du sujet
        header('Location: forum_messages.php?id=' . $message['id_sujet'] . '&page=' . $message['numero_page']);
    }
    else{
        header('Location: index.php');
    }

}
else{
    header('Location: index.php');
}
?>
