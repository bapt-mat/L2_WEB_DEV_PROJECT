<?php
session_start();

include('../includes/verifications.php');
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
        <title>Contact</title>
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
                <a href="../Vente/index.php" class="nav">Boutique</a>
                <?php
                    if(!isset($_SESSION['pseudo'])){
                        echo '<a href="../Connect/index.php" class="popup">Connexion</a>';
                    }
                    else{
                        if(countUnreadMessages($_SESSION['id']) > 0)
                            echo '<a href="../Messagerie/index.php" class="nav"><span class="notif breath">' . countUnreadMessages($_SESSION['id']) . '</span>Messagerie</a>';
                        else{
                            echo '<a href="../Messagerie/index.php" class="nav">Messagerie</a>';
                        }

                        echo '<a href="../Cours/index.php" class="nav">Cours en ligne</a>';
                        echo '<a href="../quiz/index.php" class="nav">Se tester</a>';
                        echo '<a href="../Forum/index.php" class="nav">Forum</a>';
                        echo '<a href="../perso/index.php" class="nav">Mon compte</a>';    

                        if( $_SESSION['permission'] == 1){
                            echo '<a href="../administration/index.php" class="nav">Administration</a>';
                        }

                        echo '<a href="../php/deconnexion.php" class="popup">Déconnexion</a>';
                    }
                ?>
            </nav>
        </header>

        <main>

            <div class="container">
                <form id="contact" action="contacter.php" method="post" enctype="multipart/form-data">
                    <h2>Contactez-nous</h2><br>
                    <input type="text" name="nom" class="field" placeholder="Votre nom" required="required"><br>
                    <input type="text" name="mail" class="field" placeholder="Votre email" required="required"><br>
                    <input type="text" name="objet" class="field" placeholder="Sujet" required="required"><br>
                    <textarea placeholder="Message" name="message" class="field" required="required"></textarea><br>
                    <input type="submit" class="btn" value="Envoyer">
                </form>
            </div>
	    </main>
        
    </body>
</html>
