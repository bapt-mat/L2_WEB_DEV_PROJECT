<?php
session_start();
// Connexion à la base de données
include('../includes/verifications.php');

$bdd_msg = connexion('site');
// Vérification de la connexion
if (!isset($_SESSION['pseudo']))
{
    header('Location: ../php/connexion.php');
}

//fonction d'envoi de message
function envoi_message($message, $liste_destinataires, $expediteur)
{
    $pdo = connexion('site');
    
    foreach($liste_destinataires as $destinataire){
      try{
        $stmt = $pdo->prepare("INSERT INTO messages (mess, id_destinataire, id_expediteur) VALUES (:message, :dest, :exp)");
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':dest', $destinataire);
        $stmt->bindParam(':exp', $expediteur);
        $stmt->execute();
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }
    $stmt -> closeCursor();
    $pdo = null;
}

//fonction de récupération des messages
function get_message($id_destinataire, $id_expediteur){

    $pdo = connexion('site');

    try{
        $stmt = $pdo->prepare('SELECT * FROM messages WHERE id_destinataire = :dest AND id_expediteur = :exp OR id_destinataire = :exp AND id_expediteur = :dest');
        $stmt->bindParam(':dest', $id_destinataire);
        $stmt->bindParam(':exp', $id_expediteur);
        $stmt->execute();
        $messages = $stmt->fetchAll();
        
        //mise a jour de la lecture des messages si l'id de session est le destinataire
        if($id_destinataire == $_SESSION['id']){
            $stmt = $pdo->prepare('UPDATE messages SET message_lu = 1 WHERE id_destinataire = :dest AND id_expediteur = :exp');
            $stmt->bindParam(':dest', $id_destinataire);
            $stmt->bindParam(':exp', $id_expediteur);
            $stmt->execute();
        }

        $pdo = null;
        return $messages;
    }
    catch (PDOException $e){
        echo $e->getMessage();
        exit();
    }
}

function recup_interlocuteur($id){
    try{
        $pdo = connexion('site');

        $stmt = $pdo->prepare('SELECT * FROM membres WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $pdo = null;
        return $stmt;
    }
    catch (PDOException $e){
        echo $e->getMessage();
        exit();
    }
}


// Vérification de l'existence de l'id dans l'url
if (isset($_GET['id']) AND !empty($_GET['id'])){

    $getid = $_GET['id'];
    $recupInterlocuteur = recup_interlocuteur($getid);

    // Vérification de l'existence de l'utilisateur dans la base de données
    if ($recupInterlocuteur->rowCount() == 1){
        // Vérification de l'envoi du formulaire et envoi du message
        if (isset($_POST['message']) AND !empty($_POST['message'])){
            $message = htmlspecialchars($_POST['message']);
            $liste_destinataires = array($getid);
            $expediteur = $_SESSION['id'];
            envoi_message($message, $liste_destinataires, $expediteur);
            header('Location: message.php?id=' . $getid); // Pour éviter de renvoyer le message en cas de rafraichissement de la page
        }
    }
    else {
        echo 'Aucun utilisateur trouvé dans la base de données';
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
    <title>Messagerie</title>
    <link rel="stylesheet" href="style_message.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

    <!-- Affichage du nom de l'utilisateur avec lequel on discute -->
    <section id="pseudo_discussion">
        <?php
            $recupInterlocuteur = recup_interlocuteur($getid);
            $pseudoInterlocuteur = $recupInterlocuteur->fetch()['pseudo'];
            echo '<h1>Discussion avec ' . $pseudoInterlocuteur . '</h1>';
        ?>
    </section>

    <!-- Lien de retour à la messagerie -->
    <a href="index.php" class="retour_messagerie">Retour</a>

    <!-- Affichage des messages en JS -->
    <div id="messages-js-only" data-id_session = "<?php echo $_SESSION['id']?>" data-id_interlocuteur = "<?php echo $getid?>" data-pseudo_interlocuteur = "<?php echo $pseudoInterlocuteur?>">
    </div>

    <!-- Affichage des messages en PHP si JS désactivé-->
    <noscript>
    <?php
        $recupMessages = get_message($_SESSION['id'], $getid);
        ?>
        <div id = "messages">
        <?php
        foreach($recupMessages as $message){
            if ($message['id_expediteur'] == $_SESSION['id']){
                echo '<p class="sent">Vous : ' . $message['mess'] . '</p>';
            }
            else{
                echo '<p class="received">' . $pseudoInterlocuteur . ' : ' . $message['mess'] . '</p>';
            }
        }
        ?>
        </div>
        
    </noscript>


    <!-- Formulaire d'envoi de message -->
    <!-- action doit ici rester vide pour ajax -->
    <form method = "POST" action = "" id = "form-message">
        <textarea name = "message" placeholder = "Votre message"></textarea>
        <input type = "submit" value = "envoyer">
    </form>

    <!-- Script JS -->
    <script src = "script_message.js"></script>

</body>
</html>
