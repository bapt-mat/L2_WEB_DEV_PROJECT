<?php

include('fct_vente.php');


if (isset($_SESSION['id'])  AND isset($_POST['panier']) AND isset($_COOKIE["panier"])){

    $bdd_vente = connexion('site');
    try{
        $commande = $bdd_vente->prepare('INSERT INTO commande(id_membre, adresse_livraison, prix_total) VALUES (:id_membre, :adresse_livraison, :prix_total)');
        $commande->bindParam(':id_membre', $_POST['id_membre']);
        $commande->bindParam(':adresse_livraison', $_POST['adresse']);
        $commande->bindParam(':prix_total', $_POST['prix_total']);
        $commande->execute();
        
        $commande->closeCursor();
    }
    catch (PDOException $e){
        echo $e->getMessage();
        exit();
    }

    $id_commande = $bdd_vente->lastInsertId();

    $panier = unserialize($_COOKIE["panier"]);
    foreach ($panier as $id_article) {
        $article = recup_article($id_article);
        $bdd_vente=connexion('site');
        try{
            $commande_article = $bdd_vente->prepare('INSERT INTO commande_article(id_commande, id_article) VALUES (:id_commande, :id_article)');
            $commande_article->bindParam(':id_commande', $id_commande);
            $commande_article->bindParam(':id_article', $id_article);
            $commande_article->execute();
        }   
        catch (PDOException $e){
            echo $e->getMessage();
            exit();
        }
        $commande_article->closeCursor();
        $bdd_vente = null;
    }   

    

    setcookie("panier", "", time() - 3600, "/");

} else {
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Commande</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style_commande.css">
</head>

<body>
    <h1>Merci pour votre commande !</h1>
    <p class="merci">Votre commande a bien été prise en compte.</p>
    <div class = "resume">
    <p>Vous recevrez votre commande à l'adresse suivante :</p>
    <p><?php echo $_POST['adresse']; ?></p>
    <p>Résume de votre commande :</p>
    </div>
    <?php
    $panier = unserialize($_COOKIE["panier"]);
    $prix_total = 0;
    echo '<div class="panier">';
    foreach ($panier as $id_article) {
        $article = recup_article($id_article);
        $prix_total += $article['prix'];
        echo '<div class="article">';
            echo '<img src="'.$article['path_image'].'" alt="T-shirt">';  
            echo '<p>'.$article['nom_article'].'</p>';
            echo '<p>'.$article['prix'].' €</p>';
        echo '</div>';
    }

    echo '<h4>Prix total : '.$prix_total.' €</h4>';
    echo '</div>';
    ?>

    <a href="index.php">Retour à l'accueil</a>
</body>
</html>