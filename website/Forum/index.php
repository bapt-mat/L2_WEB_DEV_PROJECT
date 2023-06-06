<?php
session_start();
// Connexion à la base de données
include('../includes/verifications.php');

// Vérification de la connexion
if (!isset($_SESSION['pseudo']))
{
    header('Location: ../php/connexion.php');
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>School learning</title>
    <link rel="stylesheet" href="style_forum.css">
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
            
            <?php
            if(countUnreadMessages($_SESSION['id']) > 0)
                echo '<a href="../Messagerie/index.php" class="nav"><span class="notif breath">' . countUnreadMessages($_SESSION['id']) . '</span>Messagerie</a>';
            else{
                echo '<a href="../Messagerie/index.php" class="nav">Messagerie</a>';
            }
            ?>

            <a href="../Cours/index.php" class="nav">Cours en ligne</a>
            <a href="../quiz/index.php" class="nav">Se tester</a>
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

        <!-- Créer un nouveau sujet -->
        <form method="post" action="creer_sujet.php" class="create">
            <button type="submit" class="btn">Créer un nouveau sujet de discussion</button>
        </form>

        <!-- Affichage de la liste des sujets -->
        <?php 

            $bdd_forum = connexion('site');
            try{
                $recupSujet = $bdd_forum->prepare('SELECT * FROM forum_sujet ORDER BY date_creation DESC');
                $recupSujet->execute();

                $bdd_forum = null;
            }
            catch (PDOException $e){
                echo $e->getMessage();
                exit();
            }
            echo '<h2>Liste des sujets :</h2>';?>

            <div class="sujets">
                <?php
                while ($sujet = $recupSujet->fetch()){

                    $bdd_forum = connexion('site');
                    try{
                        $dernierMessage = $bdd_forum->prepare('SELECT * FROM forum_messages WHERE id_sujet = :id_sujet ORDER BY date_message DESC LIMIT 1');
                        $dernierMessage->bindParam(':id_sujet', $sujet['id']);
                        $dernierMessage->execute();

                        $bdd_forum = null;
                    }
                    catch (PDOException $e){
                        echo $e->getMessage();
                        exit();
                    }

                    ?>
                    
                    <a href = "forum_messages.php?id=<?php echo $sujet['id']; ?>" class="link" id="forum_message">
                        <div class="sujet">
                            <p>
                                <?php
                                    //recupération du pseudo de l'auteur du sujet

                                    $bdd_forum = connexion('site');
                                    try{
                                        $requetePseudoAuteur = $bdd_forum->prepare('SELECT pseudo FROM membres WHERE id = :id_auteur');
                                        $requetePseudoAuteur->bindParam(':id_auteur', $sujet['id_auteur']);
                                        $requetePseudoAuteur->execute();

                                        $recupPseudoAuteur = $requetePseudoAuteur->fetch();
                                        $recupPseudoAuteur = $recupPseudoAuteur['pseudo'];

                                        $requetePseudoAuteur->closeCursor();
                                        $bdd_forum = null;
                                    }
                                    catch (PDOException $e){
                                        echo $e->getMessage();
                                        exit();
                                    }
                                    echo '<span class="titre">' . $sujet['sujet'] .  '</span> <br>Auteur : ' . $recupPseudoAuteur . ' | Date : ' . $sujet['date_creation'];
                                    
                                    if ($dernierMessage->rowCount() > 0){
                                    //recupération du pseudo de l'auteur du dernier message
                                        $dernierMessage = $dernierMessage->fetch();

                                        $bdd_forum = connexion('site');
                                        try{
                                            $requetePseudo = $bdd_forum->prepare('SELECT pseudo FROM membres WHERE id = :id_auteur');
                                            $requetePseudo->bindParam(':id_auteur', $dernierMessage['id_auteur']);
                                            $requetePseudo->execute();
                                            
                                            $recupPseudo = $requetePseudo->fetch();
                                            $recupPseudo = $recupPseudo['pseudo'];

                                            $requetePseudo->closeCursor();
                                            $bdd_forum = null;
                                        }
                                        catch (PDOException $e){
                                            echo $e->getMessage();
                                            exit();
                                        }

                                        echo '  <br> Dernier message  :  auteur : ' . $recupPseudo . ' | date : ' . $dernierMessage['date_message'];
                                    }
                                    else{
                                        echo '  <br>  Aucun message';
                                    }  
                                    
                                    //si l'utilisateur est l'auteur du sujet ou un admin, il peut supprimer le sujet
                                    if ($sujet['id_auteur'] == $_SESSION['id']){
                                        echo '<a href="supprimer_sujet.php?id=' . $sujet['id'] . '" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce sujet ?\')" class="supp">Supprimer le sujet</a>';
                                    }
                                    else {
                                        if (perm_user($_SESSION['id']) == 1){
                                            echo '<a href="supprimer_sujet.php?id=' . $sujet['id'] . '" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce sujet ?\')" class="supp">Supprimer le sujet</a>';
                                        }//fonction js confirm qui demande confirmation avant de supprimer le sujet
                                    }

                                ?>
                            </p>
                        </div>
                    </a>
                <?php } ?>
            </div>
    </main>

    <script src="script_forum.js"></script>
</body>
</html>