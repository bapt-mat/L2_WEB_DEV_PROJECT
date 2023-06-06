<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();
if(!isset($_SESSION['pseudo'])){
  header('Location: ../php/connexion.php');
  exit();
}
include('../includes/requetes.php');
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Mon compte</title>
    <link rel="stylesheet" href="style_modif.css">
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
            <?php
            if(countUnreadMessages($_SESSION['id']) > 0)
              echo '<a href="../Messagerie/index.php" class="nav"><span class="notif breath">' . countUnreadMessages($_SESSION['id']) . '</span>Messagerie</a>';
            else{
              echo '<a href="../Messagerie/index.php" class="nav">Messagerie</a>';
            }
            ?>
            <a href="../Cours/index.php" class="nav">Cours en ligne</a>
            <a href="../Forum/index.php" class="nav">Forum</a>
            <?php
            if( $_SESSION['permission'] == 1){
              echo '<a href="../administration" class="nav">Administration</a>';
            }
            ?>
            <a href="../php/deconnexion.php" class="popup">Déconnexion</a>
        </nav>
  </header>
    <main>
      <h1>Mon compte</h1>
      <p>Vous êtes connecté en tant que <?php echo $_SESSION['pseudo']; ?>.</p>

        <?php 

        // affiche id, email, photo_profil, date_inscription, date_connexion

        init_info_user_var($_SESSION['pseudo']);
        $link=path_photo("../", $_SESSION['pseudo']);
        ?>

        <form id="uploadForm" action="../php/upload_img.php" method="post" enctype="multipart/form-data">
          <div class="photo">
              <label for="pp">
                <span class="display"><ion-icon name="create"></ion-icon></span>
                <?php echo '<img src="'.$link.'" alt="photo de profil" width="100" height="100"><br>'; ?>
              </label>
          </div>
          <input type="file" name="file" id="pp" accept="image/*" style="display:none;">
          <input type ="submit" name="submit" class="save_photo" value="Enregistrer">
          
        </form>

        <div class="info">
            <form id="modif_info" action="./modifier.php" method="post">
                <?php
                echo "<label>Pseudo :<input type='text' name='pseudo' value='".$_SESSION['info_user' ]['pseudo']."' required class='form_modif'></label><br>";
                echo "<label>Email :<input type='email' name='email' value='".$_SESSION['info_user' ]['email']."' required class='form_modif'></label><br>";
                echo "<input type='submit' value='Modifier' class='modif'><br>";
                
                // affiche si l'utilisateur viens de la pleb ou est un elu
                $perm = perm_user($_SESSION['info_user' ]['id']);
                if($perm == 1){
                    echo "<span class='info'>Vous êtes un Admin</span>";
                }else{
                    echo "<span class='info'>Vous êtes un utilisateur (plèbe)</span>";
                    echo "<br><a href='../Services/index.php'>Demander un changement de grade</a><br><br>";
                }
                ?>
            </form>
        </div>

    </main>
    
    <a href="#" class="mdp">Changer de mot de passe</a>
    <a href="./index.php" class="ret">Retour</a>
    
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  </body>
</html>