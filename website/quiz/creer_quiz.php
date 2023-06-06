<?php
session_start();
if(!isset($_SESSION['permission'])||($_SESSION['permission']>3)){
  header('Location: index.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Créer un quiz</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <?php 
    if(!isset($_GET['quiz_name'], $_GET['quiz_description'], $_GET['quiz_theme'], $_GET['quiz_difficultee'], $_GET['nombre_questions'])){

    //affiche un message d'erreur si il y en a un
    if( isset($_GET['erreur']) && $_GET['erreur'] == 1)
        echo '<p class="erreur">Veuillez remplir tous les champs</p>';
    if( isset($_GET['erreur']) && $_GET['erreur'] == 2)
        echo '<p class="erreur">Le nombre de questions doit être un nombre</p>';
    if( isset($_GET['erreur']) && $_GET['erreur'] == 3)
        echo '<p class="erreur">Le nombre de questions doit être un entier plus grand que 0</p>';

      echo '

    <form name="creation_1" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="get">
      <label for="quiz_name">Nom du quiz</label>
      <input type="text" name="quiz_name" id="quiz_name"><br>
      <label for="quiz_description">Description du quiz</label>
      <textarea name="quiz_description" id="quiz_description" rows="5" cols="33" placeholder="Description du quiz"></textarea><br>
      <label for="quiz_theme">Thème du quiz</label>
      <select name="quiz_theme" id="quiz_theme">  
        <option value="1">Histoire</option>
        <option value="2">Géographie</option>
        <option value="3">Sport</option>
        <option value="4">Cinéma</option>
        <option value="5">Musique</option>
        <option value="6">Littérature</option>
        <option value="7">Sciences</option>
        <option value="8">Art</option>
        <option value="9">Politique</option>
        <option value="10">Cuisine</option>
        <option value="11">Autre</option>
      </select><br> 
      <label for="quiz_difficulty">Difficulté du quiz</label>
        <select name="quiz_difficultee" id="quiz_difficulty">  
        <option value="1">Facile</option>
        <option value="2">Moyen</option>
        <option value="3">Difficile</option>  
      </select><br>
        <label for="nombre_questions">Nombre de questions</label>
        <input type="number" name="nombre_questions" id="nombre_questions" value="3"><br>
        <input type="submit" value="Poursuivre la création du quiz...">
    </form> 
';  

    // Affiche un bouton pour venir à l'index
    echo '<a href="index.php">Retour à l\'index</a>';
    }
    // Verifie que tout les donnée on été transmise par la méthode GET
    else{
    
    // Vérifie que tous les champs sont remplis
    if (empty($_GET['quiz_name']) || empty($_GET['quiz_description']) || empty($_GET['quiz_theme']) || empty($_GET['quiz_difficultee']) || empty($_GET['nombre_questions']))
       header('Location: creer_quiz.php?erreur=1');
    
    // Verifie que le nombre de question est bien un nombre
    if (!is_numeric($_GET['nombre_questions']))
       header('Location: creer_quiz.php?erreur=2');
       
    // Recup le nb de questions dans une variable et verifie qu'il soit bien un entier plus grand que 0
    $nb_questions = (int)$_GET['nombre_questions'];
    if ($nb_questions <= 0)
       header('Location: creer_quiz.php?erreur=3');
    

    echo '<h1>Création du quiz '.$_GET['quiz_name'].'</h1>';

    // Création du nouveau formulaire qui envoi vers la page de traitement des données en post avec en plus les ancien champs en hidden
    echo '<form name="creation_2" action="traitement_quiz.php" method="post">';
    echo '<input type="hidden" name="quiz_name" value="'.$_GET['quiz_name'].'">';
    echo '<input type="hidden" name="quiz_description" value="'.$_GET['quiz_description'].'">';
    echo '<input type="hidden" name="quiz_theme" value="'.$_GET['quiz_theme'].'">';
    echo '<input type="hidden" name="quiz_difficultee" value="'.$_GET['quiz_difficultee'].'">';
    echo '<input type="hidden" name="nombre_questions" value="'.$_GET['nombre_questions'].'">';
    echo '<input type="hidden" name="temps_limite" value="'.$_GET['temps_limite'].'">';

    // Boucle qui crée le nombre de question demandé  
    $nb_questions = (int)$_GET['nombre_questions'];
    
    for ($i=1; $i <= $nb_questions; $i++) {
    echo '<div class="question">
        <label for="question_'.$i.'">Question n°'.($i).'</label>
        <input type="text" name="question_'.$i.'" id="question_'.$i.'" placeholder="Question n°'.($i).'" required><br>
        <label for="reponse_1_'.$i.'">Réponse 1</label>
        <input type="text" name="reponse_1_'.$i.'" id="reponse_1_'.$i.'" placeholder="Réponse 1 de la question n°'.($i).'" required><br>
        <label for="reponse_2_'.$i.'">Réponse 2</label>
        <input type="text" name="reponse_2_'.$i.'" id="reponse_2_'.$i.'" placeholder="Réponse 2 de la question n°'.($i).'" required><br>
        <label for="reponse_3_'.$i.'">Réponse 3</label>
        <input type="text" name="reponse_3_'.$i.'" id="reponse_3_'.$i.'" placeholder="Réponse 3 de la question n°'.($i).'" required><br>
        <label for="reponse_4_'.$i.'">Réponse 4</label>
        <input type="text" name="reponse_4_'.$i.'" id="reponse_4_'.$i.'" placeholder="Réponse 4 de la question n°'.($i).'" required><br>
        <label for="reponse_correcte_'.$i.'">Réponse correcte</label>
    <input type="number" name="reponse_correcte_'.$i.'" id="reponse_correcte_'.$i.'" min="1" max="4" required><br>
    </div>';   
    }
      echo '<input type="submit" name="submit" value="Créer le quiz">';
      echo '</form>';

    }

    ?>
  </body>
</html>
