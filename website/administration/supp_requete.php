<?php

session_start();

include('../includes/verifications.php');

$bdd = connexion('site');

if(isset($_GET['id']) AND !empty($_GET['id'])){

    $id = htmlspecialchars($_GET['id']);
    $req = $bdd->prepare('DELETE FROM contact WHERE id = :id');
    $req->bindparam(':id', $id);
    $req->execute();

}

header('Location: ./index.php');

?>