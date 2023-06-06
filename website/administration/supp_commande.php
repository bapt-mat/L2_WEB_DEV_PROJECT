<?php

session_start();

include('../includes/verifications.php');

$pdo = connexion('site');

if(isset($_GET['id']) AND !empty($_GET['id']) AND $_SESSION['permission'] == 1) {

    $id = htmlspecialchars($_GET['id']);
    $req = $pdo->prepare('DELETE FROM commande WHERE id = :id');
    $req->bindparam(':id', $id);
    $req->execute();

}

header('Location: ./index.php');

?>