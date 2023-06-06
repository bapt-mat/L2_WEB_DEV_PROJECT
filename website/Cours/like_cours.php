<?php
session_start();

include('../includes/verifications.php');
include('./fct_cours.php');


if (!isset($_SESSION['pseudo'])){
    header("Location: ../../Accueil/index.php");
}

if (isset($_GET['titre']) AND !empty($_GET['titre']) AND isset($_POST['id_membre']) AND !empty($_POST['id_membre'])){
    //On augment le nombre de like
    $titre = $_GET['titre'];
    $id_membre = $_POST['id_membre'];

    $bdd_cours = connexion('site');
    
    //On récupère les infos du cours et du membre
    $recupCours = recup_cours(NULL, NULL, $titre);
    $recupCours = $recupCours->fetch();

    $recupMembre = $bdd_cours->prepare('SELECT * FROM membres WHERE id = :id');
    $recupMembre->bindParam(':id', $id_membre);
    $recupMembre->execute();
    $recupMembre = $recupMembre->fetch();

    //On vérifie que le membre n'a pas déjà liké le cours
    $verifLike = $bdd_cours->prepare('SELECT * FROM likes WHERE id_cours = :id_cours AND id_membre = :id_membre');
    $verifLike->bindParam(':id_cours', $recupCours['id']);
    $verifLike->bindParam(':id_membre', $id_membre);
    $verifLike->execute();
    $verifLike = $verifLike->fetch();

    if (!$verifLike){
        $nb_like = $recupCours['nb_likes'] + 1;

        $majLike = $bdd_cours->prepare('UPDATE cours SET nb_likes = :nb_likes WHERE id = :id');
        $majLike->bindParam(':nb_likes', $nb_like);
        $majLike->bindParam(':id', $recupCours['id']);
        $majLike->execute();

        $ajouterLike = $bdd_cours->prepare('INSERT INTO likes(id_cours, id_membre) VALUES(:id_cours, :id_membre)');
        $ajouterLike->bindParam(':id_cours', $recupCours['id']);
        $ajouterLike->bindParam(':id_membre', $id_membre);
        $ajouterLike->execute();
    }
    else{
        $nb_like = $recupCours['nb_likes'] - 1;

        $majLike = $bdd_cours->prepare('UPDATE cours SET nb_likes = :nb_likes WHERE id = :id');
        $majLike->bindParam(':nb_likes', $nb_like);
        $majLike->bindParam(':id', $recupCours['id']);
        $majLike->execute();

        $supprLike = $bdd_cours->prepare('DELETE FROM likes WHERE id_cours = :id_cours AND id_membre = :id_membre');
        $supprLike->bindParam(':id_cours', $recupCours['id']);
        $supprLike->bindParam(':id_membre', $id_membre);
        $supprLike->execute();
    }

    $bdd_cours = null;

    header("Location: cours_matiere.php?matiere=" . $recupCours['matiere']);
    
}
?>

