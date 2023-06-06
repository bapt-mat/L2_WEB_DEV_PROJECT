<?php
session_start();
// Connexion à la base de données
include('../includes/verifications.php');

// Vérification de la connexion
if (!isset($_SESSION['pseudo']))
{
    header('Location: connexion.php');

}

function nb_nv_messages($id_destinataire, $id_expediteur){
    try{
        $pdo = connexion('site');

        $stmt = $pdo->prepare('SELECT * FROM messages WHERE id_destinataire = :id_dest AND id_expediteur = :id_exp AND message_lu = 0');
        $stmt->bindParam(':id_dest', $id_destinataire);
        $stmt->bindParam(':id_exp', $id_expediteur);
        $stmt->execute();
        $messages = $stmt;

        $stmt = null;
        $pdo = null;
        return $messages;
    }
    catch (PDOException $e){
        echo $e->getMessage();
        exit();
    }
    
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Messagerie</title>
    <link rel="stylesheet" href="style_messagerie.css">
    <noscript>
        <style>
            .header {
                backdrop-filter: blur(20px);
            }
        </style>
    </noscript>
</head>
<body>
<header class="header"> 
        <h2 class="logo">School Learning</h2>
        <nav class="navigation">
            <a href="../Accueil/index.php" class="nav">Accueil</a>
            <a href="../Services/index.php" class="nav">Services</a>
            <a href="../Contact/index.php" class="nav">Contact</a>
            <a href="../Vente/index.php" class="nav">Boutique</a>
            <a href="../Cours/index.php" class="nav">Cours en ligne</a>
            <a href="../quiz/index.php" class="nav">Se tester</a>
            <a href="../Forum/index.php" class="nav">Forum</a>
            <a href="../perso/index.php" class="nav">Mon compte</a>    
            <?php
            if( $_SESSION['permission'] == 1){
                echo '<a href="../administration/index.php" class="nav">Administration</a>';
            }
            ?>
            <a href="../php/deconnexion.php" class="popup">Déconnexion</a>
        </nav>
    </header>
    <main>

        <!-- Champ de recherche d'un membre -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="form-recherche">
            <input type="text" name="recherche" placeholder="Rechercher un membre">
            <input type="submit" value="Rechercher">
        </form>

        <?php 
            if (isset($_POST['recherche']) AND !empty($_POST['recherche'])){
                $rechercher = htmlspecialchars($_POST['recherche']);

                $bdd_msg = connexion('site');
                try{
                    $recupUser = $bdd_msg->prepare('SELECT * FROM membres WHERE pseudo LIKE :pseudo AND pseudo != :pseudo_utilisateur');
                    $recupUser->bindValue(':pseudo', "%" . $rechercher . "%", PDO::PARAM_STR);
                    $recupUser->bindParam(':pseudo_utilisateur', $_SESSION['pseudo']);
                    $recupUser->execute();
                }
                catch (PDOException $e){
                    echo $e->getMessage();
                    exit();
                }
                $bdd_msg = null;
                echo '<div class="membres">';
                foreach ($recupUser as $user){
                    if ($user){
                        $nbNvMessages = nb_nv_messages($_SESSION['id'], $user['id']);

                        echo '<div class="membre">';
                            echo '<a href = "message.php?id=' . $user['id'] . '" class="mess" >';

                                echo '<img src = "'.path_photo("../", $user['pseudo']).'" alt="img_user">';

                                echo "<span class='name'>" . $user['pseudo'] . "</span>";

                                    if ($nbNvMessages->rowCount() > 0){
                                        echo '<span class="nbmess breath">' . $nbNvMessages->rowCount() . '</span>';
                                    }

                            echo '</a>';
                        echo '</div>';

                    }
                }

                if ($recupUser->rowCount() == 0){
                    echo '<span class = "tete"><br>Aucun membre ne correspond à votre recherche.</span>';
                }
                echo '</div>';

                $recupUser->closeCursor();
            }
            else {
                $bdd_msg = connexion('site');
                try{
                    $recupUsers = $bdd_msg->prepare('SELECT membres.*, COUNT(messages.id) AS nb_messages_non_lus 
                                        FROM membres
                                        LEFT JOIN messages
                                        ON membres.id = messages.id_expediteur 
                                        AND messages.id_destinataire = :id_dest 
                                        AND messages.message_lu = 0 
                                        WHERE membres.pseudo != :pseudo_utilisateur
                                        GROUP BY membres.id 
                                        ORDER BY nb_messages_non_lus DESC
                                        ');
                    $recupUsers->bindParam(':id_dest', $_SESSION['id']); 
                    $recupUsers->bindParam(':pseudo_utilisateur', $_SESSION['pseudo']);
                    $recupUsers->execute();
                }
                catch (PDOException $e){
                    echo $e->getMessage();
                    exit();
                }
                $bdd_msg = null;

                echo '<span class="tete"><br>Liste des membres :</span><br>';

                echo '<div class="membres">';

                while ($user = $recupUsers->fetch()){

                    $nbNvMessages = nb_nv_messages($_SESSION['id'], $user['id']);

                    echo '<div class="membre">';
                        echo '<a href = "message.php?id=' . $user['id'] . '" class="mess" >';

                        echo '<img src = "'.path_photo("../", $user['pseudo']).'" alt="img_user">';
                        echo "<span class='name'>" . $user['pseudo'] . "</span>";
                            if ($nbNvMessages->rowCount() > 0){
                                echo '<span class="nbmess breath">' . $nbNvMessages->rowCount() . '</span>';
                            }
                        echo '</a>';
                    echo '</div>';
                }
                echo '</div>';
                $recupUsers->closeCursor();
            }

        ?>
    </main>

    <script src="script_messagerie.js"></script>
</body>
</html>