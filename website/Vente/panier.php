<?php

session_start();

include('fct_vente.php');

if (!isset($_SESSION['id'])){
  header('Location: ../Accueil/index.php');
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <title>panier</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style_panier.css">
</head>

<body>
  <h1>Panier</h1>

  <?php

  if (isset($_COOKIE["panier"])){
    $panier = unserialize($_COOKIE["panier"]);
    $prix_total = 0;
    echo '<div class="panier">';
    foreach ($panier as $id_article) {
      $article = recup_article($id_article);
      $prix_total += $article['prix'];
      echo '<div class="article">';
        echo '<img src="'.$article['path_image'].'" alt="T-shirt">';  
        echo '<p>'.$article['nom_article'].'</p>';
        echo '<p>/ Prix : '.$article['prix'].' €</p>';
      echo '</div>';
    }

    echo '<h4>Prix total : '.$prix_total.' €</h4>';
    echo '<a href="vider_panier.php">Vider le panier</a>';
    echo '</div>';

    echo '<div class="commande">';
    echo '<form action="passer_commande.php" method="post" id="passer_commande">';
      echo '<h2>Informations de livraison</h2>';
      echo '<label for="nom">Nom :</label>';
      echo '<input id="nom" type="text" name="nom" required><br>';
      echo '<label for="adresse">Adresse :</label>';
      echo '<input id="adresse" type="text" name="adresse" required><br>';
      echo '<input type="hidden" name="id_membre" value="'.$_SESSION['id'].'">';
      echo '<input type="hidden" name="prix_total" value="'.$prix_total.'">';
      echo '<input type="submit" name="panier" value="Passer la commande">';
    echo '</form>';
    echo'</div>';
  }
  else{
    echo '<h4>Votre panier est vide</h4>';
  }

  ?>

  <a href="index.php">Retour au catalogue</a>
  
</body>
</html>

