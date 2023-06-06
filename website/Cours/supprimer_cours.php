<?php
session_start();
// Connexion à la base de données
include('../includes/verifications.php');
include ('./fct_cours.php');

// Vérification de la connexion
if (!isset($_SESSION['pseudo']))
{
    header('Location: ../Accueil/index.php');
}


if (isset($_GET['id']) && !empty($_GET['id'])){
    $recupCours = recup_cours(NULL, $_GET['id'], NULL);
    $recupCours = $recupCours->fetch();
    $matiere = $recupCours['matiere'];

    //suppression de l'image
    unlink('./bdd_cours/images/' . $recupCours['titre'] . '.png');

    supprimer_cours($_GET['id']);

    header('Location: cours_matiere.php?matiere=' . $matiere);
}
else{
    header('Location: index.php');
}

?>