<?php
include('message.php');

// Id du destinataire en get
$id_destinataire = $_GET['id_destinataire'];

$recupMessages = get_message($_SESSION['id'], $id_destinataire);

foreach ($recupMessages as $message) {
    if( $message['id_expediteur'] == $_SESSION['id'] ) {
      echo '<p class="sent">' . $message['mess'] . '<    /p>';
    } else {
      echo '<p class="received">' . $message['mess']     . '</p>';
    }
}

?>
