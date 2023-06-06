<?php
session_start();
include('../includes/requetes.php');

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Candidature</title>
        <link rel="stylesheet" href="style_candidature.css">
    </head>
    <body>
        <h1> Pourquoi voulez vous devenir professeur? </h1>

        <form id="candidat" action="index.php" method="post" enctype="multipart/form-data">
            <textarea rows="10" placeholder="Entrez vos motivations" name="candidature" required="required"></textarea><br>
            <input type="submit" value="Envoyer" name="envoyer">
            <input type="file" name="envoyer" accept=".pdf">
        </form>

        <a href="./index.php">Retour</a>

    </body>
</html>