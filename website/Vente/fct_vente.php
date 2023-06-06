<?php
session_start();

include ('../includes/verifications.php');

function recup_article($id_article){
    $bdd_vente = connexion('site');
    try{
        $req = $bdd_vente->prepare('SELECT * FROM article WHERE id = ?');
        $req->execute(array($id_article));
        $article = $req->fetch();

        $req->closeCursor();
        $bdd_vente = null;
        return $article;
    }
    catch (PDOException $e){
        echo $e->getMessage();
        exit();
    }
}

function recup_all_article() {
    $bdd_vente = connexion('site');
    try{
        $req = $bdd_vente->prepare('SELECT * FROM article');
        $req->execute();
        $articles = $req->fetchAll();

        $req->closeCursor();
        $bdd_vente = null;
        return $articles;
    }
    catch (PDOException $e){
        echo $e->getMessage();
        exit();
    }
}

function vider_panier() {
    setcookie("panier", "", time() - 3600, "/");
    header('Location: panier.php');
}


?>