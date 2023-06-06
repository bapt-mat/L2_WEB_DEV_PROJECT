<?php
session_start();
// Connexion à la base de données
include('../includes/verifications.php');


//Suppression d'un sujet
if (isset($_GET['id']) AND !empty($_GET['id'])){
    $getid = $_GET['id'];


    $bdd_forum = connexion('site');
    try{
        $recupSujet = $bdd_forum->prepare('SELECT * FROM forum_sujet WHERE id = :id');
        $recupSujet->bindParam(':id', $getid);
        $recupSujet->execute();
        $sujet = $recupSujet->fetch();
        $recupSujet->closeCursor();
        $bdd_forum = null;
    }
    catch (PDOException $e){
        echo $e->getMessage();
        exit();
    }

    if ($sujet['id_auteur'] == $_SESSION['id'] OR perm_user($_SESSION['id']==1)){
        //Suppression de l'image de chaque message

        $bdd_forum = connexion('site');
        try{
            $recupMessages = $bdd_forum->prepare('SELECT * FROM forum_messages WHERE id_sujet = :id_sujet');
            $recupMessages->bindParam(':id_sujet', $getid);
            $recupMessages->execute();
            $bdd_forum = null;
        }
        catch (PDOException $e){
            echo $e->getMessage();
            exit();
        }

        while ($message = $recupMessages->fetch()){
            unlink($message['image_path']);
        }

         // Suppression des messages du sujet
        $bdd_forum = connexion('site');
        try{
            $supprimerMessages = $bdd_forum->prepare('DELETE FROM forum_messages WHERE id_sujet = :id_sujet');
            $supprimerMessages->bindParam(':id_sujet', $getid);
            $supprimerMessages->execute();
            
            // Suppression du sujet
            $supprimerSujet = $bdd_forum->prepare('DELETE FROM forum_sujet WHERE id = :id');
            $supprimerSujet->bindParam(':id', $getid);
            $supprimerSujet->execute();

            $supprimerMessages->closeCursor();
            $supprimerSujet->closeCursor();
            $bdd_forum = null;
        }
        catch (PDOException $e){
            echo $e->getMessage();
            exit();
        }
        header('Location: index.php');
    }
    else{
        header('Location: index.php');
    }

}
else{
    header('Location: index.php');
}
?>