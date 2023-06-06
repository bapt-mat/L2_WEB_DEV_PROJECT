<?php
session_start();
if( !isset($_SESSION['permission']) || $_SESSION['permission'] != 1) {
  header('Location: ../Accueil/index.php');
  exit();
}
require_once('../includes/trie.php');
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Recherche d'un membre</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
  
  <header>
    <nav>
      <a href="../Accueil/index.php">Retour</a>
    </nav>
  </header>
    <h1>Page d'Admin</h1>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form1" method="post">
    <input type="text" name="login" placeholder="Pseudo, email ou id">
    <select name="methode">
      <option value="pseudo">Pseudo</option>
      <option value="email">Email</option>
      <option value="id">Id</option>
    </select>
    <p>Tri :</p>
    <select name="objet_trie" class="obj">
      <option value="id">Id</option>
      <option value="pseudo">Pseudo</option>
      <option value="email">Email</option>
      <option value="date_inscription">Date d'inscription</option>
      <option value="date_connexion">Date de dernière connexion</option>
    </select>

    <select name="methode_trie" class="tri">
      <option value="croissant">Croissant</option>
      <option value="decroissant">Décroissant</option>
    </select>
    
    <input type="submit" name="submit" value="Rechercher">
    </form>

<?php
if (isset($_POST['submit'])) {
  $login = $_POST['login'];
  // recupere la methode de recherche
  $methode = $_POST['methode'];

  include('../includes/requetes.php'); 
  // Connexion à la base de données
  $pdo = connexion('site');
  
  // Regarde la methode de trie et la met dans une variable
  
  switch($methode) {
    case 'pseudo':
      $stmt = $pdo->prepare("SELECT * FROM membres WHERE pseudo LIKE :qui");
      break;
    case 'email':
      $stmt = $pdo->prepare("SELECT * FROM membres WHERE email LIKE :qui");
      break;
    case 'id':
      $stmt = $pdo->prepare("SELECT * FROM membres WHERE id LIKE :qui");
      break;
  }

  $stmt->bindValue(':qui', "%".$login."%", PDO::PARAM_STR);
  $stmt->execute();
  
  // Regarde si il y a des resultats 
  if( $stmt->rowCount() == 0 ) {
    echo "<h3 class='erreur'>Aucun résultat</h3>";
    exit();
  }
  
  $membres = $stmt->fetchAll();
  $objet_trie= $_POST['objet_trie'];
  $methode_trie = $_POST['methode_trie'];

   
  trie_mat_bulle($membres, $objet_trie, $methode_trie);
    echo "<form action='action_administration.php' method='post'>";
  echo "<table>
  <tr>
    <th>Selectionné</th>
    <th>Pseudo</th>
    <th>Email</th>
    <th>Photo de profil</th>
    <th>Date d'inscription</th>
    <th>Date de dernière connexion</th>
    <th>Permission</th>
  </tr>";
  foreach ($membres as $membre) {
    echo "<tr>";
    echo "<td><input type='checkbox' name='selection[]' value='".$membre['pseudo']."'></td>";
    echo "<td>".$membre['pseudo']."</td>";
    echo "<td>".$membre['email']."</td>";
    echo '<td> <img src="'.path_photo("../", $membre["pseudo"]).'" alt="pp"> </td>';
    echo "<td>".$membre['date_inscription']."</td>";
    echo "<td>".$membre['date_connexion']."</td>";
    echo "<td>".perm_user($membre['id'])."</td>";
    echo "</tr>";
  }
  echo "</table>";

  // fermeture de la connexion
  $stmt->closeCursor();
  $pdo = null;
  // Affiche plusieurs actions possible sur les membres selectioné 
  echo "
  <select name='action' class='select'>
    <option value='supprimer'>Supprimer</option>
    <option value='bannir'>Bannir</option>
    <option value='debannir'>Débannir</option>
    <option value='retrograder'>Retrograder</option>
    <option value='promouvoir'>Promouvoir</option>
    <option value='reset_photo_profil'>Reset la photo de profil</option>
  </select>
  <input type='submit' name='submit' class='select valid' value='Valider'>
  </form>";
} 

$pdo = connexion('site');

// Récupère les requêtes dans la table contact et les affiche dans un tableau

$stmt = $pdo->prepare("SELECT * FROM contact");
$stmt->execute();
$contact = $stmt->fetchAll();

echo "<h2>Requêtes</h2>";
echo "<table>
  <tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Email</th>
    <th>Sujet</th>
    <th>Message</th>
    <th>Suppression</th>
  </tr>";

foreach ($contact as $message) {
  echo "<tr>";
  echo "<td>".$message['id']."</td>";
  echo "<td>".$message['nom']."</td>";
  echo "<td>".$message['mail']."</td>";
  echo "<td>".$message['objet']."</td>";
  echo "<td>".$message['message']."</td>";
  echo "<td><a href='supp_requete.php?id=".$message['id']."' class='supp_req'>Supprimer</a></td>'";
  echo "</tr>";
}

echo "</table>";

// Récupère les commandes dans la table commande et les affiche dans un tableau

$stmt = $pdo->prepare("SELECT * FROM commande");
$stmt->execute();
$contact = $stmt->fetchAll();

echo "<h2>Commandes</h2>";
echo "<table>
  <tr>
    <th>ID-Commande</th>
    <th>ID-Membre</th>
    <th>Date-Commande</th>
    <th>Adresse-Livraison</th>
    <th>Prix-Total</th>
    <th>Voir commande</th>
    <th>Suppression</th>
  </tr>";

foreach ($contact as $commande) {
  echo "<tr>";
  echo "<td>".$commande['id']."</td>";
  echo "<td>".$commande['id_membre']."</td>";
  echo "<td>".$commande['date_commande']."</td>";
  echo "<td>".$commande['adresse_livraison']."</td>";
  echo "<td>".$commande['prix_total']."</td>";
  echo "<td><a href='voir_commande.php?id=".$commande['id']."' class='voir_commande'>Voir les détails de la commande</a></td>";
  echo "<td><a href='supp_commande.php?id=".$commande['id']."' class='supp_commande'>Supprimer</a></td>";
  echo "</tr>";
}

echo "</table>";


?>
  </body>
</html>
