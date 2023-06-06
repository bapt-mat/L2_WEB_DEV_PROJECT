<?php


function supprimer_cours($id){
    session_start();
    $bdd_cours=connexion('site');

    try{
        //On récupère le cours
        $recupCours = $bdd_cours->prepare('SELECT * FROM cours WHERE id = :id');
        $recupCours->bindParam(':id', $id);
        $recupCours->execute();
        $recupCours = $recupCours->fetch();
        $matiere = $recupCours['matiere'];

        //On supprime les likes
        $supprLikes = $bdd_cours->prepare('DELETE FROM likes WHERE id_cours = :id_cours');
        $supprLikes->bindParam(':id_cours', $id);
        $supprLikes->execute();

        //On supprime le cours
        $supprCours = $bdd_cours->prepare('DELETE FROM cours WHERE id = :id');
        $supprCours->bindParam(':id', $id);
        $supprCours->execute();
        unlink($recupCours['chemin_fichier']);

        $bdd_cours = null;
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
}

//fonction de récupération de cours soit en fonction de la matière soit en fonction de l'id soit en fonction du titre
function recup_cours($matiere, $id, $titre){
    $bdd_cours=connexion('site');

    try{
        if ($matiere != ''){
            $recupCours = $bdd_cours->prepare('SELECT * FROM cours WHERE matiere = :matiere');
            $recupCours->bindParam(':matiere', $matiere);
            $recupCours->execute();
        }
        elseif ($id != ''){
            $recupCours = $bdd_cours->prepare('SELECT * FROM cours WHERE id = :id');
            $recupCours->bindParam(':id', $id);
            $recupCours->execute();
        }
        else{
            $recupCours = $bdd_cours->prepare('SELECT * FROM cours WHERE titre = :titre');
            $recupCours->bindParam(':titre', $titre);
            $recupCours->execute();
        }

        $bdd_cours = null;
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }

    return $recupCours;
}

//fonction pour ajouter un cours dans la bdd

function ajout_cours($titre, $matiere, $id_auteur, $chemin_fichier, $nb_likes){
    $bdd_cours=connexion('site');

    try{
        $ajoutCours = $bdd_cours->prepare('INSERT INTO cours(titre, matiere, id_auteur, chemin_fichier, nb_likes) VALUES(:titre, :matiere, :id_auteur, :chemin_fichier, :nb_likes)');
        $ajoutCours->bindParam(':titre', $titre);
        $ajoutCours->bindParam(':matiere', $matiere);
        $ajoutCours->bindParam(':id_auteur', $id_auteur);
        $ajoutCours->bindParam(':chemin_fichier', $chemin_fichier);
        $ajoutCours->bindParam(':nb_likes', $nb_likes);
        $ajoutCours->execute();

        $ajoutCours->closeCursor();
        $bdd_cours = null;
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
}

?>