<?php
session_start();

if (isset($_SESSION['id']) AND isset($_POST["id_article"])) {
	if (isset($_COOKIE["panier"])) {
		$panier = unserialize($_COOKIE["panier"]);
	} else {
		$panier = array();
	}

	array_push($panier, $_POST["id_article"]);

	setcookie("panier", serialize($panier), time() + 300, "/");

	header ("Location: index.php");
} else {
	header("Location: index.php");
}
?>



