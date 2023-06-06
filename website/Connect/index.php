<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>School learning</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h2 class="logo">School Learning</h2>
        <nav class="navigation">
            <a href="../Accueil/index.php">Accueil</a>
            <a href="../Services/index.php">Services</a>
            <a href="../Contact/index.php">Contact</a>
            <a href="../Vente/index.php" class="nav">Boutique</a>
            <button class="popup">Connexion</button>
        </nav>
    </header>
    
    <?php 
        if(isset($_GET['erreur'])){
            switch($_GET['erreur']){
                
                case 1: 
                    $erreur = "La connexion a échoué";
                    break;

                case 2:
                    $erreur = "Vous êtes banni";
                    break;
                
                defalt:
                    $erreur = "Une erreur est survenue";
                    break;
            }
            echo '<p class="erreur">'.$erreur.'</p>';
        }
    ?>

    <div class="principal active-popup">

        <span class="icon-close"><ion-icon name="close"></ion-icon></span>

        <div class="cadre login">

            <h2>Connexion</h2>
            <form action="../php/connexion.php" method="post" id="formulaire1">

                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="text" name="login" required="required">
                    <label>Email</label>
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="mdp" id="mdp_connex" required="required">
                    <label>Mot de Passe</label>
                </div>
                
                <div class="afficher_mdp">
                    <label><input type="checkbox" onclick="AffichePass('mdp_connex')">Afficher le mot de passe</label>
                </div>

                <div class="souv-oubli">
                    <!-- <label><input type="checkbox">Se souvenir de moi</label> -->
                    <a href="#">Mot de Passe oublié ?</a>
                </div>

                <button type="submit" class="btn">Se Connecter</button>

                <div class="switch">
                    <p>Vous n'avez pas de compte ?<a href="#" class="inscription"> S'inscrire</a></p>
                </div>

            </form>
        </div>

        <div class="cadre register">

            <h2>Inscription</h2>
            <form action="../php/inscription.php" method="post" id="formulaire2">

                <div class="input-box">
                    <span class="icon"><ion-icon name="person"></ion-icon></span>
                    <input type="text" name="pseudo" required="required">
                    <label>Pseudonyme</label>
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="email" name="email" required="required">
                    <label>Email</label>
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="mdp" id="mdp_insc" required="required">
                    <label>Mot de Passe</label>
                </div>

                <div class="afficher_mdp">
                    <label><input type="checkbox" onclick="AffichePass('mdp_insc')">Afficher le mot de passe</label>
                </div>

                <div class="souv-oubli">
                    <label><input type="checkbox" required="required">J'accepte les termes & conditions</label>
                </div>

                <button type="submit" class="btn">S'inscrire</button>

                <div class="switch">
                    <p>Vous avez déjà un compte ?<a href="#" class="connexion"> Se connecter</a></p>
                </div>

            </form>
        </div>
    </div>

    <noscript>
        <div class="principal2 active-popup">

        <span class="icon-close"><ion-icon name="close"></ion-icon></span>

            <div class="cadre">

                <h2>Inscription</h2>
                <form action="../php/inscription.php" method="post" id="formulaire3">

                    <div class="input-box">
                        <span class="icon"><ion-icon name="person"></ion-icon></span>
                        <input type="text" name="pseudo" required="required">
                        <label>Pseudonyme</label>
                    </div>

                    <div class="input-box">
                        <span class="icon"><ion-icon name="mail"></ion-icon></span>
                        <input type="email" name="email" required="required">
                        <label>Email</label>
                    </div>

                    <div class="input-box">
                        <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                        <input type="password" name="mdp" id="mdp_insc_2" required="required">
                        <label>Mot de Passe</label>
                    </div>

                    <div class="souv-oubli">
                        <label><input type="checkbox" required="required">J'accepte les termes & conditions</label>
                    </div>

                    <button type="submit" class="btn">S'inscrire</button>

                </form>
            </div>
        </div>
    </noscript>
    
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
