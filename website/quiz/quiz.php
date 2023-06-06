<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Quiz</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>


<?php


if(!isset($_GET['id'])){
    
    exit();
}else{
    $id = $_GET['id'];
    if( $id < 1){
        exit();
    }
}

include('../includes/connex.inc.php');


if(isset($_POST['submit'])){

    if(!isset($_POST['r1'])){
        echo '<p>Vous n\'avez pas répondu à toutes les questions.</p>';
        header('Location: '. htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $id);
        exit();
    }

    $i = 1;

    while( isset($_POST['r'.$i]) ){
        $reponses[$i] = $_POST['r'.$i];
        $i++;
    }

    // Récupère les réponses juste dans la bdd
    try{
        $pdo = connexion('site');
        $stmt = $pdo->prepare("SELECT est_juste FROM questions_quiz WHERE id_quiz = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $reponses_juste = $stmt->fetchAll();
        $stmt->closeCursor();
        $pdo = null;
    }catch (PDOException $e) {
        header('Location: index.php');
        exit();
    }

    $score = 0;
    $i = 1;

    // Compare les réponses de l'utilisateur avec les réponses juste
    foreach($reponses_juste as $reponse_juste){
        if($reponse_juste['est_juste'] == $reponses[$i]){
            $score++;
        }
        $i++;
    }

    $pluriel = ($score > 1) ? 's' : '';

    echo '<div class="score">';
    echo '<p>Vous avez eu ' . $score . ' bonne' .$pluriel.' réponse'.$pluriel.' sur ' . ($i-1) . ' question'.$pluriel.'.</p>';
    echo '<p> Soit une note de <span class="important">' . ($score/($i-1))*20 . '/20</span></p>';
    echo '<a href="./index.php">Revenir à la page précédente</a>';
    echo '</div>';

    exit();
}

// Récupère les infos du quiz dans la bdd
try{
    $pdo = connexion('site');
    $stmt = $pdo->prepare("SELECT * FROM quiz WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if($stmt->rowCount() != 1){
        $stmt->closeCursor();
        $pdo = null;
        header('Location: index.php');
        exit();
    }
    $quiz = $stmt->fetch();

    $stmt->closeCursor();
    $pdo = null;
}catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage() . "<br/>";
    exit();
}


// Récupère les questions du quiz dans la bdd
try{
    $pdo = connexion('site');
    $stmt = $pdo->prepare("SELECT id, id_quiz, question, reponseA, reponseB, reponseC, reponseD FROM questions_quiz  WHERE id_quiz = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $questions = $stmt->fetchAll();
    $stmt->closeCursor();
    $pdo = null;
}catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage() . "<br/>";
    exit();
}

echo '<h2 class="titre">Quiz: ' . $quiz['nom'] . ' :</h2>';


$i = 1;
echo '<form action="quiz.php?id=' . $id . '" id="form" method="post">';
//pour toute les questions récupérer
foreach($questions as $question){

    echo '<div class="question">';
    echo '<h2>' . $question['question'] . ':</h2>';
    // Affiche les 4 propositions de réponse avec checkbox

    echo '<input type="radio" name="' . "r".$i . '" value="A">' . $question['reponseA'] . '<br>';

    echo '<input type="radio" name="' . "r".$i . '" value="B">' . $question['reponseB'] . '<br>';

    echo '<input type="radio" name="' . "r".$i . '" value="C">' . $question['reponseC'] . '<br>';

    echo '<input type="radio" name="' . "r".$i . '" value="D">' . $question['reponseD'] . '<br>';
    echo '</div>';
    $i++;
}

echo '<input type="submit" name="submit" value="Envoyer mes réponses  ">';
echo '</form>';

// Bouton de retour à l'accueil
echo '<a href="./index.php">Revenir à la page précédente</a>';
?>
  </body>
</html>
