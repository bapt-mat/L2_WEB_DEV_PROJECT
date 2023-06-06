<?php
session_start();
// Connexion à la base de données
include('../includes/verifications.php');

// Vérification de la connexion
if (!isset($_SESSION['pseudo']))
{
    header('Location: ../Accueil/index.php');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Cours en ligne</title>
    <link rel="stylesheet" href="./css_cours/style_cours_creation.css">
</head>
<body>

    <div class="container">
		<h2>Créer un cours</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div>
                <label for="matiere">Matière:</label>
                <select name="matiere" id="matiere">
                    <option value="maths">Mathématiques</option>
                    <option value="francais">Français</option>
                    <option value="anglais">Anglais</option>
                    <option value="histoire">Histoire</option>
                    <option value="geographie">Géographie</option>
                    <option value="physique">Physique</option>
                    <option value="chimie">Chimie</option>
                    <option value="svt">SVT</option>
                    <option value="musique">Musique</option>
                    <option value="technologie">Technologie</option>
                </select>
            </div>
			<div>
				<label for="title">Titre de la page:</label>
				<input type="text" id="title" placeholder="Titre de la page" name="title" required>
			</div>
            <div>
                <label for="title_partie_1">Titre de la partie 1:</label>
                <input type="text" id="title_partie_1" placeholder="Titre de la partie 1" name="title_partie_1" required>
            </div>
			<div>
				<label for="content1">Partie 1:</label>
				<textarea rows="5" id="content1" placeholder="Contenu" name="content1" required></textarea>
			</div>
            <div>
                <label for="title_partie_2">Titre de la partie 2 (optionnel):</label>
                <input type="text" id="title_partie_2" placeholder="Titre de la partie 2" name="title_partie_2">
            </div>
            <div>
                <label for="content2">Partie 2 (optionnel):</label>
                <textarea rows="5" id="content2" placeholder="Contenu" name="content2"></textarea>
            </div>
            <div>
                <label for="title_partie_3">Titre de la partie 3 (optionnel):</label>
                <input type="text" id="title_partie_3" placeholder="Titre de la partie 3" name="title_partie_3">
            </div>
            <div>
                <label for="content3">Partie 3 (optionnel):</label>
                <textarea rows="5" id="content3" placeholder="Contenu" name="content3"></textarea>
            </div>
			<div>
				<label for="image">Insérer une image:</label>
				<input type="file" id="image" name="image">
			</div>
			<button type="submit">Créer le cours</button>
		</form>
	</div>

    <a href="index.php">Retour</a>
    
    <script> 
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }   
    </script>

</body>
</html>

<?php

if (isset($_POST['title']) && isset($_POST['content1'])) {
    // Récupération des données
    $matiere = htmlspecialchars($_POST['matiere']);
    $title = htmlspecialchars($_POST['title'], ENT_NOQUOTES);
    $title = str_replace(' ', '_', $title);
    $title = str_replace('\'', '', $title);
    $title_partie_1 = htmlspecialchars($_POST['title_partie_1']);
    $title_partie_1 = str_replace('\'','', $title_partie_1);
    $content1 = htmlspecialchars($_POST['content1']);
    $content1 = nl2br($content1);
    $content1 = str_replace("'","&#39;", $content1);
    $id_auteur = $_SESSION['id'];
    
    // Creation et ajout du cours dans la base de données
    $newCours = './bdd_cours/' . $title . '.php';

    $bdd_cours = connexion('site');
    try{
        $ajoutCours = $bdd_cours->prepare('INSERT INTO cours(titre, matiere, id_auteur, chemin_fichier) VALUES(:titre, :matiere, :id_auteur, :chemin_fichier)');
        $ajoutCours->bindParam(':titre', $title);
        $ajoutCours->bindParam(':matiere', $matiere);
        $ajoutCours->bindParam(':id_auteur', $id_auteur);
        $ajoutCours->bindParam(':chemin_fichier', $newCours);
        $ajoutCours->execute();

        $id_cours = $bdd_cours->lastInsertId();

        $ajoutCours->closeCursor();
        $bdd_cours = null;
    }
    catch (PDOException $e){
        echo $e->getMessage();
        exit();
    }


    // Vérification de l'envoi d'une image
    if (isset($_FILES['image']) AND !empty($_FILES['image']['name'])){

        // Vérification de la taille et de l'extension de l'image
        $tailleMax = 5242880;
        $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');

        if ($_FILES['image']['size'] <= $tailleMax){

            //le fichier est valide
            $extensionImage = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
            if (in_array($extensionImage, $extensionsValides)){
                $cheminImage = './bdd_cours/images/' . $title . '.' . $extensionImage;
                move_uploaded_file($_FILES['image']['tmp_name'], $cheminImage);
            }
        }
    }

    // Création du fichier PHP
    $coursPHP = '
    <?php 
    session_start();
    include(\'../../includes/verifications.php\');
    require(\'../fpdf/fpdf.php\');
    
    if (!isset($_SESSION[\'pseudo\'])){
        header("Location: ../../Accueil/index.php");
    }

    if (isset($_POST[\'pdf\'])){
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont(\'Arial\',\'B\',16);
        $pdf->Cell(40,10,\'' . $title . '\');
        $pdf->Ln();
        $pdf->SetFont(\'Arial\',\'\',12);
        $pdf->Cell(40,10,\'Partie 1 :' . $title_partie_1 . '\');
        $pdf->Ln();
        $pdf->MultiCell(0,5,\'' . $content1 . '\');
        $pdf->Ln();
        $pdf->Cell(40,10,\'Partie 2 :' . $_POST['title_partie_2'] . '\');
        $pdf->Ln();
        $pdf->MultiCell(0,5,\'' . $_POST['content2'] . '\');
        $pdf->Ln();
        $pdf->Cell(40,10,\'Partie 3 :' . $_POST['title_partie_3'] . '\');
        $pdf->Ln();
        $pdf->MultiCell(0,5,\'' . $_POST['content3'] . '\');

        $pdf->Output();
    }

    $id_auteur = ' . $id_auteur . ';
    $id_cours = ' . $id_cours . ';
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>' . $title .'</title>
        <link rel="stylesheet" href="./style_cours.css">
        <script src="./modifier_cours.js"></script>
    </head>
    <body>
    <h1>' . $title . '</h1>
    <h3>' . $title_partie_1 . '</h3>
    <p id ="partie_1">'
     . $content1 .'
    </p>
    <br>
    ';

    // Vérification de l'envoi de la partie 2
    if (isset($_POST['content2']) AND !empty($_POST['content2'])){
	$title_partie_2 = htmlspecialchars($_POST['title_partie_2']);
	$title_partie_2 = str_replace('\'','', $title_partie_2);
	$content2 = htmlspecialchars($_POST['content2']);
        $content2 = nl2br($content2);
	$content2 = str_replace('\'','\\\'', $content2);
    	$content2 = str_replace("'","&#39;", $content2);
        $coursPHP .= '
        <h3>' . $title_partie_2 . '</h3>
        <p id = "partie_2">'
         . $content2 .'
        </p>
        <br>
        ';
    }

    // Vérification de l'envoi de la partie 3
    if (isset($_POST['content3']) AND !empty($_POST['content3'])){
        $title_partie_3 = htmlspecialchars($_POST['title_partie_3']);
	$title_partie_3 = str_replace('\'','', $title_partie_3);
        $content3 = htmlspecialchars($_POST['content3']);
        $content3 = nl2br($content3);
	$content3 = str_replace('\'','\\\'', $content3);
    	$content3 = str_replace("'","&#39;", $content3);
        $coursPHP .= '
        <h3>'. $title_partie_3 . '</h3>
        <p id = "partie_3">'
         . $content3 .'
        </p>
        <br>
        ';
    }

    // Vérification de l'envoi d'une image
    if (isset($cheminImage)){
        $coursPHP .= '
        <h3>Image du cours</h3>
        <br>
        <img src="./images/' . $title . '.' . $extensionImage . '" alt="image du cours">';
    }

    $coursPHP .= '
    <br>
    <span class="auteur">auteur: ' . $_SESSION['pseudo'] . '</span>
    <br>
    <?php
    //bouton pour modifier le cours
    if ((isset($_SESSION[\'pseudo\']) AND $_SESSION[\'pseudo\'] == \'admin\') OR ($_SESSION[\'id\'] == $id_auteur)){
        echo \'
        <form action="../modifier_cours.php" method="post" class="modif">
            <input type="hidden" name="matiere" value="' . $matiere . '">
            <input type="hidden" name="title" value="' . $title . '">
            <input type="hidden" name="id_cours" value="' . $id_cours . '">
            <input type="hidden" name="id_auteur" value="' . $id_auteur . '">
            <input type="hidden" name="image" value="' . $cheminImage . '">
            <div class="part1">
                <div>
                    <label for="title_partie_1">Titre de la partie 1</label>
                    <input id="title_partie_1" type="text" name="title_partie_1" value="' . $title_partie_1 . '">
                </div>
                <div>
                    <label for="content1">Contenu de la partie 1</label>
                    <textarea name="content1" id="content1" cols="30" rows="10">' . $content1 . '</textarea>
                </div>
            </div>
            <div class="part2">
                <div>
                    <label for="title_partie_2">Titre de la partie 2</label>
                    <input id ="title_partie_2" type="text" name="title_partie_2" value="' . $title_partie_2 . '">
                </div>
                <div>
                    <label for="content2">Contenu de la partie 2</label>
                    <textarea name="content2" id="content2" cols="30" rows="10">' . $content2 . '</textarea>
                </div>
            </div>
            <div class="part3">
                <div>
                    <label for="title_partie_3">Titre de la partie 3</label>
                    <input id="title_partie_3" type="text" name="title_partie_3" value="' . $title_partie_3 . '">
                </div>
                <div>
                    <label for="content3">Contenu de la partie 3</label>
                    <textarea name="content3" id="content3" cols="30" rows="10">' . $content3 . '</textarea>
                </div>
            </div>
            <input type="submit" value="Modifier le cours">
        </form>
        \';
    }

    echo \'<form class="telecharger" method="post" action="\' . $_SERVER[\'PHP_SELF\'] . \'">
    <input type="submit" name="pdf" value="Télécharger le cours en PDF">
    </form>\';
    ?>
    <br>
    <a href="../cours_matiere.php?matiere=' . $matiere . '">Retour</a> 
    </body>
    </html>'
    ;

    // Ecriture du fichier PHP
    $open = fopen($newCours, 'w+');

    fwrite($open, $coursPHP);

    fclose($open);

    header('Location: ./cours_matiere.php?matiere=' . $matiere);
}


?>


