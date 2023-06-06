<?php
session_start();

include('../includes/verifications.php');

$bdd = connexion('site');

if(isset($_POST['nom']) AND !empty($_POST['nom']) AND isset($_POST['mail']) AND !empty($_POST['mail']) AND isset($_POST['objet']) AND !empty($_POST['objet']) AND isset($_POST['message']) AND !empty($_POST['message'])){
    
    $nom = htmlspecialchars($_POST['nom']);
    $mail = htmlspecialchars($_POST['mail']);
    $objet = htmlspecialchars($_POST['objet']);
    $message = htmlspecialchars($_POST['message']);
    
    $req = $bdd->prepare('INSERT INTO contact(nom, mail, objet, message) VALUES(:nom, :mail, :obj, :mess)');
    $req->bindparam(':nom', $nom);
    $req->bindparam(':mail', $mail);
    $req->bindparam(':obj', $objet);
    $req->bindparam(':mess', $message);
    $req->execute();
    
    header('Location: ../Accueil/index.php');

}

?>