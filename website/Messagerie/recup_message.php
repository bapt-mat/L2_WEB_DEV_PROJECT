<?php
session_start();

include ('../includes/verifications.php');

$pdo = connexion('site');
try{
    $stmt = $pdo->prepare('SELECT * FROM messages WHERE id_destinataire = :dest AND id_expediteur = :exp OR id_destinataire = :exp AND id_expediteur = :dest');
    $stmt->bindParam(':dest', $_SESSION['id']);
    $stmt->bindParam(':exp', $_GET['id']);
    $stmt->execute();
    $messages = $stmt->fetchAll();

    // mise à jour de la lecture des messages
    $stmt = $pdo->prepare('UPDATE messages SET message_lu = 1 WHERE id_destinataire = :dest AND id_expediteur = :exp');
    $stmt->bindParam(':dest', $_SESSION['id']);
    $stmt->bindParam(':exp', $_GET['id']);
    $stmt->execute();

    $stmt -> closeCursor();
    $pdo = null;
}
catch (PDOException $e){
    echo $e->getMessage();
    exit();
}

//on renvoie au format json
echo json_encode($messages);
?>
