

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Voir commande</title>
    <link rel="stylesheet" href="style_voir_commande.css">
</head>
<body>

<?php
session_start();

include '../includes/verifications.php';

if (isset($_GET['id']) AND !empty($_GET['id']) AND $_SESSION['permission'] == 1) {
    $id_commande = htmlspecialchars($_GET['id']);
    $pdo = connexion('site');


    $req = "SELECT commande.id, membres.id AS id_membre, membres.pseudo, commande.date_commande, commande.adresse_livraison, commande.prix_total
            FROM commande
            JOIN membres ON commande.id_membre = membres.id
            WHERE commande.id = :id_commande";

    $stmt = $pdo->prepare($req);    
    $stmt->bindParam(':id_commande', $id_commande);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $stmt->closeCursor();

    if (count($result) > 0) {
        $row = $result[0];
        echo "<h3>Informations de la commande:</h3>";
        echo "<ul class='informations'>";
        echo "<li>ID de la commande: " . $row["id"] . "</li>";
        echo "<li>ID du membre: " . $row["id_membre"] . "</li>";
        echo "<li>Nom du membre: " . $row["pseudo"] . "</li>";
        echo "<li>Date de la commande: " . $row["date_commande"] . "</li>";
        echo "<li>Adresse de livraison: " . $row["adresse_livraison"] . "</li>";
        echo "<li>Prix total: " . $row["prix_total"] . "€</li>";
        echo "</ul>";

        // Récupération des articles de la commande
        $req = "SELECT article.nom_article, article.prix, article.path_image, article.description
                FROM commande_article
                JOIN article ON commande_article.id_article = article.id
                WHERE commande_article.id_commande = $id_commande";

        $stmt = $pdo->prepare($req);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $stmt->closeCursor();

        if (count($result) > 0) {
            // Affichage des articles de la commande
            echo "<h3>Articles de la commande:</h3>";
            echo "<ul class='articles'>";
            foreach ($result as $row) {
                echo "<li>";
                echo "<h4>" . $row["nom_article"] . "</h4>";
                echo "<p>Prix: " . $row["prix"] . "</p>";
                echo "<p>Description: " . $row["description"] . "</p>";
                echo "<img src='../Vente/" . $row["path_image"] . "' alt='" . $row["nom_article"] . "'>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "Aucun article trouvé pour cette commande.";
        }
    } else {
        echo "Aucune commande trouvée avec cet ID.";
    }

    // Fermeture de la connexion à la base de données
    $pdo = null;   
    
    echo '<a href="./index.php">Retour</a>';
} else {
    header('Location: ./index.php');
    exit();
}


?>

</body>
</html>
