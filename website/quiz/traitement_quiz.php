<?php 


// Affiche les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_POST['submit'],$_SESSION['permission'])||($_SESSION['permission']>3)){
  header('Location: index.php');
  exit;
}

// Verifie que le formulaire a bien été envoyé
if(!isset($_POST['quiz_name'], $_POST['quiz_description'], $_POST['quiz_theme'], $_POST['quiz_difficultee'], $_POST['nombre_questions'])){
  header('Location: index.php');
  exit();
}

// Recupere les données du formulaire
$quiz_name = $_POST['quiz_name'];
$quiz_description = $_POST['quiz_description'];
$quiz_theme = $_POST['quiz_theme'];
$quiz_difficultee = $_POST['quiz_difficultee'];
$nombre_questions = $_POST['nombre_questions'];

// Verifie les données ne soit pas vide
if(empty($quiz_name) || empty($quiz_description) || empty($quiz_theme) || empty($quiz_difficultee) || empty($nombre_questions)){
  header('Location: index.php');
  exit();
}


// Verifie que le nombre de question est bien un nombre 
if (!is_numeric($nombre_questions)  || $nombre_questions < 1 ){
  header('Location: index.php?error=notnumeric');
  exit();
}

switch($quiz_theme){
   case '1':
      $quiz_theme = 'Histoire';
      break;
   case '2':
      $quiz_theme = 'Géographie';
      break;
   case '3':
      $quiz_theme = 'Sport';
      break;
   case '4':
      $quiz_theme = 'Culture Générale';
      break;
   case '5':
      $quiz_theme = 'Cuisine';
      break;
   case '6':
      $quiz_theme = 'Musique';
      break;
   case '7':
      $quiz_theme = 'Cinéma';
      break;
   case '8':
      $quiz_theme = 'Littérature';
      break;
   case '9':
      $quiz_theme = 'Science';
      break;
   case '10':
      $quiz_theme = 'Art';
      break;
   case '11':
      $quiz_theme = 'Autre';
      break;
   default:
      $quiz_theme = 'Autre';
      break;
}

// Recupere chaque questions et reponses dans des tableaux et verifie que les champs ne soit pas vide
for ($i=1; $i <= $nombre_questions; $i++) {
  $question[$i] = $_POST['question_'.$i];
  $reponse_1[$i] = $_POST['reponse_1_'.$i];
  $reponse_2[$i] = $_POST['reponse_2_'.$i];
  $reponse_3[$i] = $_POST['reponse_3_'.$i];
  $reponse_4[$i] = $_POST['reponse_4_'.$i];
  $reponse_correcte[$i] = $_POST['reponse_correcte_'.$i];

  if(empty($question[$i]) || empty($reponse_1[$i]) || empty($reponse_2[$i]) || empty($reponse_3[$i]) || empty($reponse_4[$i]) || empty($reponse_correcte[$i]) || !is_numeric($reponse_correcte[$i]) || $reponse_correcte[$i] < 1 || $reponse_correcte[$i] > 4){
    header('Location: index.php');
    exit();
  }
}

// Connexion a la base de donnée
include_once('../includes/connex.inc.php');
$pdo = connexion('site');


// Rempli la table quiz
try{ 
   $stmt = $pdo->prepare("INSERT INTO quiz (id_auteur, nom, descr, theme, difficulte) VALUES (:id_auteur, :nom, :descr, :theme, :difficulte)");
   $stmt->bindParam(':id_auteur', $_SESSION['id']);
   $stmt->bindParam(':nom', $quiz_name);
   $stmt->bindParam(':descr', $quiz_description);
   $stmt->bindParam(':theme', $quiz_theme);
   $stmt->bindParam(':difficulte', $quiz_difficultee);
   $stmt->execute();
   $quiz_id = $pdo->lastInsertId();
   $stmt->closeCursor();

}catch (PDOException $e) {
   header('Location: index.php?error=404');
   exit();
}

//  Rempli la table questions_quiz
for ($i=1; $i <= $nombre_questions; $i++) { 
   try{ 
      $stmt = $pdo->prepare("INSERT INTO questions_quiz (id_quiz, question, reponseA, reponseB, reponseC, reponseD, est_juste) VALUES (:id_quiz, :question, :reponseA, :reponseB, :reponseC, :reponseD, :est_juste)");
      $stmt->bindParam(':id_quiz', $quiz_id);
      $stmt->bindParam(':question', $question[$i]);
      $stmt->bindParam(':reponseA', $reponse_1[$i]);
      $stmt->bindParam(':reponseB', $reponse_2[$i]);
      $stmt->bindParam(':reponseC', $reponse_3[$i]);
      $stmt->bindParam(':reponseD', $reponse_4[$i]);
      $stmt->bindParam(':est_juste', $reponse_correcte[$i]);
      $stmt->execute();
      // Recupere l'id de la question
      $id_question = $pdo->lastInsertId();
      $stmt->closeCursor();


}catch (PDOException $e) {
   echo "Erreur : " . $e->getMessage() . "<br/>";
   // On supprime le quiz si il y a une erreur
   try{ 

      // Supprime les questions déjà enregistré
      $stmt = $pdo->prepare("DELETE FROM questions_quiz WHERE id_quiz = :id_quiz");
      $stmt->bindParam(':id_quiz', $quiz_id);
      $stmt->execute();
      $stmt->closeCursor();

      // Supprime le quiz
      $stmt = $pdo->prepare("DELETE FROM quiz WHERE id = :id");
      $stmt->bindParam(':id', $quiz_id);
      $stmt->execute();
      $stmt->closeCursor();


   }catch (PDOException $e) {
      echo "Erreur : " . $e->getMessage() . "<br/>";
      exit();
   }
}}

$pdo = null;
header('Location: index.php');

?>