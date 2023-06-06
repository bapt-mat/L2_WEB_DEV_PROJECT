<?php
session_start();
// Connexion à la base de données
include('../includes/verifications.php');
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
    <link rel="stylesheet" href="./css_cours/style_all_cours.css">
</head>
<body>

<header class="header">
        <h2 class="logo">School Learning</h2>
        <nav class="navigation">
            <a href="../Accueil/index.php" class="nav">Accueil</a>
            <a href="../Services/index.php" class="nav">Services</a>
            <a href="../Contact/index.php" class="nav">Contact</a>
            <a href="../Vente/index.php" class="nav">Boutique</a>

            <?php
            if(countUnreadMessages($_SESSION['id']) > 0)
            echo '<a href="../Messagerie/index.php" class="nav"><span class="notif breath">' . countUnreadMessages($_SESSION['id']) . '</span>Messagerie</a>';
            else{
            echo '<a href="../Messagerie/index.php" class="nav">Messagerie</a>';
            }
            ?>

            <a href="../quiz/index.php" class="nav">Se tester</a>
            <a href="../Forum/index.php" class="nav">Forum</a>
            <a href="../perso/index.php" class="nav">Mon Compte</a>

            <?php
            if( $_SESSION['permission'] == 1){
              echo '<a href="../administration/index.php" class="nav">Administration</a>';
            }
            ?>
            
            <a href="../php/deconnexion.php" class="popup">Déconnexion</a>
        </nav>
  </header>

  <main>

        <div class = "wrapper">
            <div class = "texte">Nous proposons des cours de</div>
            <div class = "changer_texte">
                <ul>
                <li><span>Mathématiques</span></li>
                <li><span>Français</span></li>
                <li><span>Géographie</span></li>
                <li><span>Physique</span></li>
                <li><span>Chimie</span></li>
                <li><span>SVT</span></li>
                <li><span>Musique</span></li>
                <li><span>Technologie</span></li>
                </ul>
            </div>
        </div>


        <div class="container">

            <!-- Selection de la matière -->
            <h3> Sélectionnez la matière dont vous souhaitez voir les cours </h3>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="search">
                <select name="matiere">
                    <option value="maths">Mathématiques</option>
                    <option value="francais">Français</option>
                    <option value="anglais">Anglais</option>
                    <option value="histoire">Histoire</option>
                    <option value="geographie">Géographie</option>
                    <option value="physique">Physique</option>
                    <option value="chimie">Chimie</option>
                    <option value="svt">SVT</option>
                    <option value="musique">Musique</option>
                    <option value="technologie">Technologie</option>
                </select>
                <input type="submit" value="Valider">
            </form>

            <?php
                // Récupération de la matière choisie
                if (isset($_POST['matiere']) AND !empty($_POST['matiere'])){
                    $matiere = htmlspecialchars($_POST['matiere']);
                    $recupCours = recup_cours($matiere,NULL,NULL);

                    // Vérification de l'existence de la matière dans la base de données
                    if ($recupCours->rowCount() > 0){
                        header('Location: cours_matiere.php?matiere=' . $matiere);
                    }
                    else {
                        echo '<p>/!\ Aucun cours trouvé dans la base de données /!\</p>';
                    }
                }
            ?>

        </div>

        <?php 
        if ($_SESSION['permission'] < 4){
            echo '<div class="container">';
            echo '<h3> Créer un cours </h3>';
            echo '<form method="post" action="cours_creation.php" class="create">';
            echo '<input type="submit" value="Créer un cours">';
            echo '</form>';
            echo '</div>';
        }
        ?>
    </main>

    <script> /*a refaire, solution temporaire pour eviter le form resubmit*/
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }   
    </script>

</body>
</html>
