<?php
session_start();

include('../includes/verifications.php');

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Services</title>
        <link rel="stylesheet" href="./style_services.css">
    </head>

    <body>
    <header class="header"> 
        <h2 class="logo">School Learning</h2>
        <nav class="navigation">
            <a href="../Accueil/index.php" class="nav">Accueil</a>
            <a href="../Contact/index.php" class="nav">Contact</a>
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
                    echo '<a href="../administration" class="nav">Administration</a>';
                }

                echo '<a href="../php/deconnexion.php" class="popup">Déconnexion</a>';
            }
        ?>
        </nav>
    </header>
        <section>

            <img src = "./images/montagne1.png" id = "montagne1" alt="imgmontagne">
            <img src = "./images/montagne2.png" id = "montagne2" alt="imgmontagne">
            <img src = "./images/arbres_droit.png" id = "arbres_droit" alt="imgarbres">
            <img src = "./images/arbres_gauche.png" id = "arbres_gauche" alt="imgarbres">
            <h2 id = "titre">Nos Services</h2>
        </section>
        <div class="container">
            <div class="all">
                <h3>Membre</h3>
                <ul>
                    <li>Grade de base</li>
                    <li>Accès aux forums</li>
                    <li>Accès aux cours des professeurs</li>
                    <li>Possibilité d'utiliser une messagerie pour communiquer entre étudiants ou avec un professeur !</li>
                    <li>Devenir Membre :
                    <?php
                        if(!isset($_SESSION['pseudo'])){
                                echo "<a href='../Connect/index.php'>Se connecter</a>";
                        }
                        else {
                            if($_SESSION['permission'] == 1 ){
                                echo "<p>Vous êtes déjà administrateur !</p>";
                            }
                            else if($_SESSION['permission'] == 2){
                                echo "<p>Vous êtes déjà modérateur !</p>";
                            }
                            else if($_SESSION['permission'] == 3){
                                echo "<p>Vous êtes déjà professeur !</p>";
                            }
                            else {
                                echo "<p>Vous êtes déjà membre !</p>";
                            }
                        }
    
                    ?>
                    </li>
                </ul>
            </div>

            <div class="all">
                <h3>Professeur</h3>
                <ul>
                    <li>Grade amélioré</li>
                    <li>Accès aux forums</li>
                    <li>Accès aux cours et possibilité de créer des cours</li>
                    <li>Messagerie disponible pour communiquer avec tout le monde !</li>
                    <li>Devenir Professeur :
                    <?php
                        if(!isset($_SESSION['pseudo'])){
                            echo "<a href='../Connect/index.php'>Se connecter</a>";
                        }
                        else {
                            if($_SESSION['permission'] == 1 ){
                                echo "<p>Vous êtes déjà administrateur !</p>";
                            }
                            else if($_SESSION['permission'] == 2){
                                echo "<p>Vous êtes déjà modérateur !</p>";
                            }
                            else if($_SESSION['permission'] == 3){
                                echo "<p>Vous êtes déjà professeur !</p>";
                            }
                            else {
                                echo "<a href='./candidature_prof.php'>Cliquez ici !</a>";
                            }
                        }
                    ?>
                    </li>
                </ul>
            </div>

            <div class=" all">
                <h3>Modérateur</h3>
                <ul>
                    <li>Modérateur du Forum, Faire régner l'ordre est nécessaire !</li>
                    <li>Modérateur des Cours</li>
                    <li>Gestion de la messagerie</li>
                    <li>Nous recrutons des modérateurs motivés à faire évoluer notre site !</li>
                    <li>Pour candidater :
                    <?php
                        if(!isset($_SESSION['pseudo'])){
                            echo "<a href='../Connect/index.php'>Se connecter</a>";
                        }
                        else {
                            if($_SESSION['permission'] == 1 ){
                                echo "<p>Vous êtes déjà administrateur !</p>";
                            }
                            else if($_SESSION['permission'] == 2){
                                echo "<p>Vous êtes déjà modérateur !</p>";
                            }
                            else{
                                echo "<a href='./candidature.php'>Candidater</a>";
                            }
                        }
                    ?>
                    </li>
                </ul>
            </div>
        </div>

        <script>
            let montagne2 = document.getElementById('montagne2');
            let montagne1 = document.getElementById('montagne1');
            let titre = document.getElementById('titre');
            let arbres_droit = document.getElementById('arbres_droit');
            let arbres_gauche = document.getElementById('arbres_gauche');

            window.addEventListener('scroll', function(){
                var value = window.scrollY  ;

                arbres_droit.style.left = value * 0.5 + 'px';
                arbres_gauche.style.left = -value * 0.5 + 'px';

                montagne1.style.top = value * 1 + 'px';
                montagne2.style.top = value * 1 + 'px'
                //le titre descend avec le scroll
                /* titre.style.top = value * 0.5 + 'px'; */

            })
        </script>
    </body>
</html> 
