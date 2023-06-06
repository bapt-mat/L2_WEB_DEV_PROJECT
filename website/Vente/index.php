<?php

include ('fct_vente.php');

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Goodies SL</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style_index.css">
</head>
<body>

    <header class="header"> 
        <h2 class="logo">School Learning</h2>
        <nav class="navigation">
            <a href="../Accueil/index.php" class="nav">Accueil</a>
            <a href="../Services/index.php" class="nav">Services</a>
            <a href="../Contact/index.php" class="nav">Contact</a>
        <?php
            if(!isset($_SESSION['pseudo'])){
                echo '<a href="../Connect/index.php" class="popup">Connexion</a>';
            }
            else{
                if(countUnreadMessages($_SESSION['id']) > 0)
                    echo '<a href="../Messagerie/index.php" class="nav"><span class="notif breath">' . countUnreadMessages($_SESSION['id']) . '</span>Messagerie</a>';
                else{
                    echo '<a href="../Messagerie/index.php" class="nav">Messagerie</a>';
                }
                echo '<a href="../Cours/index.php" class="nav">Cours en ligne</a>';
                echo '<a href="../quiz/index.php" class="nav">Se tester</a>';
                echo '<a href="../Forum/index.php" class="nav">Forum</a>';
                echo '<a href="../perso/index.php" class="nav">Mon compte</a>';    

                if( $_SESSION['permission'] == 1){
                    echo '<a href="../administration" class="nav">Administration</a>';
                }

                echo '<a href="../php/deconnexion.php" class="popup">Déconnexion</a>';
            }
        ?>
        </nav>
    </header>

  <h1>Goodies SL</h1>

  <div class="catalogue">
    <?php
    $articles = recup_all_article();
    foreach ($articles as $article) {
      
      echo '<div class="article">';
        echo '<img src="'.$article['path_image'].'" alt="T-shirt">';  
        echo '<h4>'.$article['nom_article'].'</h4>';
        echo '<p>'.$article['prix'].' €</p>';
        echo '<p>'.$article['description'].'</p>';
        
        echo '<form action="ajouter_panier.php" method="post" id="ajout_panier_'. $article['id'].'">';
          echo '<input type="hidden" name="id_article" value="'.$article['id'].'">';
          echo '<input type="submit" value="Ajouter au panier">';
        echo '</form>';

      echo '</div>';
    }
    ?>
  </div>

  <a href="panier.php">Voir le panier</a>

</body>
</html>
