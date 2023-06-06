<?php
function connexion($base){
    include_once("param.inc.php");
    try {
    	if ($base == 'site'){
	   $base = 'mb00560u';
	   }
        $pdo = new PDO('mysql:host='.HOTE.';port='.PORT.';dbname='.$base, UTILISATEUR, PASSE);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("SET CHARACTER SET utf8");
    }
    catch(PDOException $e) {
        echo 'Problème à la connexion';
        echo $e->getMessage();
    die();
    }
    return $pdo;
}
?>
