<?php
session_start();

include ('../includes/verifications.php');

$bdd = connexion('site');

if(isset($_POST['pseudo']) AND !empty($_POST['pseudo']) AND isset($_POST['email']) AND !empty($_POST['email'])){

    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email = htmlspecialchars($_POST['email']);

    $req = $bdd->prepare('UPDATE membres SET pseudo = :pseudo, email = :email WHERE id = :id');
    $req->bindparam(':pseudo', $pseudo);
    $req->bindparam(':email', $email);
    $req->bindparam(':id', $_SESSION['id']);
    $req->execute();

    $_SESSION['pseudo'] = $pseudo;
    $_SESSION['email'] = $email;
    
}

header('Location: ./index.php');

?>