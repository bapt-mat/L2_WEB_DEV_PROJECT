<?php
session_start();
// Connexion à la base de données
include('../includes/verifications.php');
include_once('../includes/trie.php');
include('./fct_cours.php');


// Vérification de la connexion
if (!isset($_SESSION['pseudo']))
{
    header('Location: ../Accueil/index.php');
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Cours en ligne</title>
    <link rel="stylesheet" href="./css_cours/style_cours_matiere.css">
</head>
<body>
    <?php
    echo '<h1>' . $_GET['matiere'] . '</h1>';

    if (isset($_GET['matiere']) && !empty($_GET['matiere'])){
        $matiere = $_GET['matiere'];
        $recupCours = recup_cours($matiere, NULL, NULL);
        $recupCours = $recupCours->fetchAll();
        
        if (empty($recupCours)){
            echo '<p>Aucun cours n\'a été publié pour cette matière</p>';
        }
        else{
            trie_mat_bulle($recupCours,'nb_likes','decroissant');
        }

        echo '<div class="liste_cours">';
        foreach ($recupCours as $cours){

            $bdd_cours = connexion('site');
            try{
                $requeteAuteur = $bdd_cours->prepare('SELECT pseudo FROM membres WHERE id = :id_auteur');
                $requeteAuteur->bindParam(':id_auteur', $cours['id_auteur']);
                $requeteAuteur->execute();
                $recupAuteur = $requeteAuteur->fetch();
                $recupAuteur = $recupAuteur['pseudo'];

                $requeteAuteur->closeCursor();
                $bdd_cours = null;
            }
            catch (PDOException $e){
                echo $e->getMessage();
                exit();
            }

            echo '<a href=' . $cours['chemin_fichier'] . '></a>';
            echo '<div class="cours">';
            echo '<div class="info">';
            echo '<h3>' . $cours['titre'] . '</h3>';
            echo '<p>Par ' . $recupAuteur . '</p>';
            echo '<p>Le ' . $cours['date_creation'] . '</p>';
            echo '</div>';
            echo '<br>';
            

            //rajouter une description du cours ?
            //rajouter un bouton pour télécharger le cours ?
            //rajouter des commentaires, des notes?
            
            echo '<div class="like_supp">';

            //like
            echo '<form method="post" action="like_cours.php'. '?titre=' . $cours['titre'] . '">';
            echo '<label>';
            echo '<span class="icon"><ion-icon name="heart"></ion-icon></span>';
            if ($_SESSION['id'] == $cours['id_auteur'] OR perm_user($_SESSION['id'])==1){
                echo '<input type="submit" name="like" class="like trans" value="' . $cours['nb_likes'] . '">';
            }
            else{
                echo '<input type="submit" name="like" class="like" value="' . $cours['nb_likes'] . '">';
            }
            echo '</label>';
            echo '<input type="hidden" name="id_membre" value="' . $_SESSION['id'] . '">';
            echo '</form>';


            //suppression du cours
            echo '<br>';
            if ($_SESSION['id'] == $cours['id_auteur'] OR perm_user($_SESSION['id'])==1){
                echo '<form method="post" action="supprimer_cours.php'. '?id=' . $cours['id'] . '">';
                echo '<input type="submit" class="supp" value="Supprimer" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce cours ?\')">';
                echo '</form>';
            }
            echo '</div>';
            echo '</div>';
            

        }
        echo '</div>';

    }
    ?>
    <br>
    <a href="index.php" class="ret">Retour</a>

    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }  
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>
