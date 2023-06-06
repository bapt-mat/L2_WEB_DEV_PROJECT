<?php
session_start();
// Connexion à la base de données
include('../includes/verifications.php');

// Nombre de messages par page
$messages_par_page = 5;



// Vérification de la connexion
if (!isset($_SESSION['pseudo']))
{
    header('Location: ../php/connexion.php');
    
}

// Vérification de l'existence de l'id dans l'url
if (isset($_GET['id']) AND !empty($_GET['id'])){
    $getid = $_GET['id'];

    // Récupération du nombre de messages
    $bdd_forum = connexion('site');
    try {
        $recupNbMessages = $bdd_forum->prepare('SELECT numero_page FROM forum_messages WHERE id_sujet = :id_sujet ORDER BY numero_page DESC LIMIT 1');
        $recupNbMessages->bindParam(':id_sujet', $getid);
        $recupNbMessages->execute();
        $recupMsg=$recupNbMessages->fetch(PDO::FETCH_ASSOC);

        $recupNbMessages->closeCursor();
        $bdd_forum=null;
    }
    catch (PDOException $e){
        echo $e->getMessage();
        exit();
    }

    // Récupération du nombre de pages
    if ($recupMsg){
        $nombre_pages = $recupMsg['numero_page'];
    }
    else{
        $nombre_pages = 1;
    }

    // Regarde si le numéro de page est présent dans l'url et si il est valide sinon il prend la valeur 1 
    if( isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page'] <= $nombre_pages){
        $numero_page = $_GET['page'];
    }
    else{
        $numero_page = 1;
    }

    // Récupération du sujet
    $bdd_forum = connexion('site');
    try{
        $recupSujet = $bdd_forum->prepare('SELECT * FROM forum_sujet WHERE id = :id');
        $recupSujet->bindParam(':id', $getid);
        $recupSujet->execute();

        $bdd_forum=null;
    }
    catch (PDOException $e){
        echo $e->getMessage();
        exit();
    }
    
    // Vérification de l'existence du sujet dans la base de données
    if ($recupSujet->rowCount() == 1){

        // Vérification de l'envoi du formulaire et envoi du message
        if (isset($_POST['message']) AND !empty($_POST['message'])){
            $message = htmlspecialchars($_POST['message']);

            // Vérification de l'envoi d'une image
            if (isset($_FILES['image']) AND !empty($_FILES['image']['name'])){

                // Vérification de la taille et de l'extension de l'image
                $tailleMax = 2097152;
                $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');

                if ($_FILES['image']['size'] <= $tailleMax){

                    //le fichier est valide
                    $extensionImage = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
                    if (in_array($extensionImage, $extensionsValides)){
                        $cheminImage = './images_forum/' . uniqid() . '.' . $extensionImage;
                        move_uploaded_file($_FILES['image']['tmp_name'], $cheminImage);
                    }
                    else {
                        //le fichier n'est pas valide
                        echo 'Le type de fichier n\'est pas valide';
                    }
                }
                else {
                    //la taille de l'image est trop grande
                    echo 'L\'image est trop volumineuse';

                }
            }
            
            // Recupération du nombre de message au numéro de page actuel
            $bdd_forum = connexion('site');
            try{
                $recupNbMessages = $bdd_forum->prepare('SELECT COUNT(*) AS nb_messages FROM forum_messages WHERE id_sujet = :id_sujet');
                $recupNbMessages->bindParam(':id_sujet', $getid);
                $recupNbMessages->execute();
                $nb_messages = $recupNbMessages->fetch(PDO::FETCH_ASSOC)['nb_messages'];
                $recupNbMessages->closeCursor();
                
                $bdd_forum=null;
            }
            catch (PDOException $e){
                echo $e->getMessage();
                exit();
            }
            

            // Insertion du message dans la base de données
            $bdd_forum = connexion('site');
            try{
                $insererMessage = $bdd_forum->prepare('INSERT INTO forum_messages(message, id_sujet, id_auteur, numero_page) VALUES (:message, :id_sujet, :id_auteur, :numero_page)');
                $numeroNewPage = ceil(($nb_messages + 1) / $messages_par_page);
                $insererMessage->bindParam(':message', $message);
                $insererMessage->bindParam(':id_sujet', $getid);
                $insererMessage->bindParam(':id_auteur', $_SESSION['id']);
                $insererMessage->bindParam(':numero_page', $numeroNewPage);
                $insererMessage->execute();

                if (isset($cheminImage)){
                    $insererImage = $bdd_forum->prepare('UPDATE forum_messages SET image_path = :image_path WHERE id = :id');
                    $insererImage->bindParam(':image_path', $cheminImage);
                    $insererImage->bindParam(':id', $bdd_forum->lastInsertId());
                    $insererImage->execute();
                }

                $insererMessage->closeCursor();
                $bdd_forum=null;
            }
            catch (PDOException $e){
                echo $e->getMessage();
                exit();
            }
            
            header('Location: forum_messages.php?id=' . $getid. '&page=' . ($numeroNewPage == $numero_page ? $numero_page : $numeroNewPage)); // Pour éviter de renvoyer le message en cas de rafraichissement de la page
        }
    }
    else {
        echo 'Aucun sujet trouvé dans la base de données';
    }
}

else{
    header('Location: index.php');
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Forum</title>
    <link rel="stylesheet" href="style_forum_messages.css">
</head>
<body>

    <a href="index.php" class="ret">Retour au forum</a>

    <!-- Affichage de tout les messages du sujet -->
    <section id="messages_forum">
        <?php

            //recuperation des messages
            $bdd_forum = connexion('site');
            try{
                $recupMessages = $bdd_forum->prepare('SELECT * FROM forum_messages WHERE id_sujet = :id_sujet AND numero_page = :numero_page');
                $recupMessages->bindParam(':id_sujet', $getid);
                $recupMessages->bindParam(':numero_page', $numero_page);
                $recupMessages->execute();

                $bdd_forum=null;
            }
            catch (PDOException $e){
                echo $e->getMessage();
                exit();
            }

            //affichage du titre du sujet
            $titreSujet = $recupSujet->fetch(PDO::FETCH_ASSOC)['sujet'];
            echo '<h1>' . $titreSujet . '</h1>';

            
            while ($messages = $recupMessages->fetch(PDO::FETCH_ASSOC)){

                //recuperation du pseudo de l'auteur du message et affichage du message
                $bdd_forum = connexion('site');
                try{
                    $recupPseudoAuteur = $bdd_forum->prepare('SELECT * FROM membres WHERE id = :id');
                    $recupPseudoAuteur->bindParam(':id', $messages['id_auteur']);
                    $recupPseudoAuteur->execute();

                    $pseudoAuteur = $recupPseudoAuteur->fetch(PDO::FETCH_ASSOC)['pseudo'];

                    $recupPseudoAuteur->closeCursor();
                    $bdd_forum=null;
                }
                catch (PDOException $e){
                    echo $e->getMessage();
                    exit();
                }
                echo '<div class="message">';
                echo '<h3>' . $pseudoAuteur . '</h3>';
                echo '<p>' . $messages['message'] . '</p>';

                //affichage de l'image si il y en a une
                if ($messages['image_path'] != null){
                    echo '<img src="' . $messages['image_path'] . '" alt="image du message">';
                }

                //date
                echo '<div class="date_supp">';
                echo '<span class="date">' . $messages['date_message'] . ' </span>';
                
                //suppression du message
                if ($messages['message'] != 'Message supprimé' AND ($messages['id_auteur'] == $_SESSION['id'] OR $_SESSION['permission'] == 1)){
                    echo '<a href="supprimer_message.php?id=' . $messages['id'] . '&id_sujet=' . $getid . '&page=' . $numero_page . '" class="supp">Supprimer</a>';
        
                }
                echo '</div>';

                echo '</div>';
            }

        // Affichage des pages
        echo '<div class="pages">';
        echo '<span class="page">Page ' . $numero_page . ' sur ' . $nombre_pages . '</span>';
        echo '<a href="forum_messages.php?id=' . $getid . '&page=' . ($numero_page - 1 == 0 ? 1 : $numero_page - 1) . '" class="page"><span class="icon"><ion-icon name="chevron-back-outline" class="backchev"></ion-icon></span></a>';
        echo '<a href="forum_messages.php?id=' . $getid . '&page=' . ($numero_page + 1 > $nombre_pages ? $nombre_pages : $numero_page + 1) . '" class="page"><span class="icon"><ion-icon name="chevron-forward-outline"></ion-icon></span></a>';
        echo '</div>';
        ?>
    </section>


    <!-- Formulaire d'envoi de message -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" class="form2">
        <textarea name="message" placeholder="Votre message"></textarea>
        <input type ="file" name="image">
        <input type="submit" value="Envoyer" >
    </form>

    <script>
        // Script pour envoyer le message en appuyant sur la touche entrée
        var form = document.querySelector('form');
        
        function verif_entree (e){
            // Si la touche entrée est pressée
            if (e.keyCode == 13 && !e.shiftKey){
                e.preventDefault();
                form.submit();
            }
        }
        // Ajoutez un gestionnaire d'événements de frappe de touche au champ de saisie de message
        form.querySelector('textarea').addEventListener('keydown', verif_entree);
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>


    <?php $bdd_forum = null;?>
</body>
</html>
