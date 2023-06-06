<?php 
  session_start();
  include('../includes/requetes.php');
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Quiz</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>

  <a href="../Accueil/index.php">Retour à l'accueil</a>

  <?php 
if(isset($_SESSION['permission']) && $_SESSION['permission'] < 4){
  echo '
  <section id="ajout_quiz">
  <h3>Ajouter un quiz</h3>
    <form action="creer_quiz.php" method="post">
      <input type="submit" value="Nouveau Quiz">
     </form>
  </section>
  ';
  }else if(!isset($_SESSION['permission'])){
    echo '<h2>Si vous êtes un proffesseur, vous pouvez vous connecter pour créer un quiz</h2>';
  }

  ?>

    <section id="liste_quiz">
        <h3>Liste des quiz</h3>
      <?php
          include_once('../includes/connex.inc.php');
    
          try{
            // connexion à la bdd
            $pdo = connexion('site');
      
            // Récupère les quiz
            $stmt = $pdo->prepare("SELECT * FROM quiz");
            $stmt->execute();
            $quiz = $stmt->fetchAll();
            $stmt->closeCursor();
            $pdo = null;
          }catch (PDOException $e) {
            echo '<h2>Aucun quiz disponible</h2>';
            exit();
          }

          // Affiche un lien de ridection vers le quiz
          foreach($quiz as $q){          
            echo '<div class="quiz">';
            echo '<a href="quiz.php?id=' . $q['id'] . '">' . $q['nom'] . '</a>';
            echo '<p> Description: ' . $q['descr'] . '</p>';
            echo '<p> Theme: ' . $q['theme'] . '</p>';
            echo '<p> Difficulté: ' . $q['difficulte'] . '</p>';
            echo '</div>';
          }
    ?>
  </section>

  </body>
</html>
