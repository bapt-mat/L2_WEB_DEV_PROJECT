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
    <link rel="stylesheet" href="style.css">
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
            <a href="../Forum/index.php" class="nav">Forum</a>
            <?php
            if( $_SESSION['permission'] == 1){
              echo '<a href="../administration/index.php" class="nav">Administration</a>';
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
          <input type ="submit" name="submit" value="Enregistrer">
          
        </form>

        <div class="info">
        <?php
          echo "<span class='info'>dernière connexion : ".$_SESSION['info_user' ]['date_connexion']."</span><br>";
          echo "<span class='info'>inscription : ".$_SESSION['info_user' ]['date_inscription']."</span><br>";
          echo "<span class='info'>email : ".$_SESSION['info_user' ]['email']."</span><br>";
          echo "<span class='info'>identifiant : ".$_SESSION['info_user' ]['id']."</span><br>";
          
          // affiche si l'utilisateur viens de la pleb ou est un elu
          $perm = perm_user($_SESSION['info_user' ]['id']);
          if($perm == 1){
            echo "<span class='info'>Vous êtes un Admin</span>";
          }else{
            echo "<span class='info'>Vous êtes un utilisateur (plèbe)</span>";
          }
          ?>
        </div>

    </main>
    <a href="./modif_info.php" class="mdp">Modifier mes informations</a>
    
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  </body>
</html>
