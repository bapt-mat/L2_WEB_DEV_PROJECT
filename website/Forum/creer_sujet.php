<?php
session_start();
// Connexion à la base de données
include('../includes/verifications.php');

// Vérification de la connexion
if (!isset($_SESSION['pseudo']))
{
    header('Location: ../php/connexion.php');
}

// Création d'un nouveau sujet
if (isset($_POST['sujet']) && !empty($_POST['sujet'])){
    $sujet = htmlspecialchars($_POST['sujet']);
    $id_auteur = $_SESSION['id'];
    $date_creation = date('Y-m-d H:i:s');

    //Création du sujet
    $pdo=connexion('site');

    try{
        $creationSujet = $pdo->prepare('INSERT INTO forum_sujet (sujet, id_auteur, date_creation) VALUES (:sujet, :id_auteur, :date_creation)');
        $creationSujet->bindParam(':sujet', $sujet);
        $creationSujet->bindParam(':id_auteur', $id_auteur);
        $creationSujet->bindParam(':date_creation', $date_creation);
        $creationSujet->execute();
        $creationSujet->closeCursor();

        $idSujet = $pdo->lastInsertId();
        $pdo=null;
    }
    catch(PDOException $e){
        echo 'Erreur : ' . $e->getMessage();
    }

    //recuperation de l'id du sujet crée
    header('Location: forum_messages.php?id=' . $idSujet);
}   


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Forum</title>
    <link rel="stylesheet" href="style_creer_sujet.css">
</head>
<body>

    <a href="index.php">Retour</a>

    <!-- Formulaire de création de sujet -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <textarea name = "sujet" placeholder = "Votre sujet"></textarea>
        <input type = "submit" value = "Créer">
    </form>

</body>
</html>