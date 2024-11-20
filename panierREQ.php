<?php
require 'db.php';
session_start();

// Vérif si ces variables existes et si c'est non nul
if (isset($_GET['id_item']) && isset($_GET['prix'])) {
    // On les stocke alors dans des varibales
    $id_item = $_GET['id_item'];
    $prix = $_GET['prix'];
};

// Vérif si la variable de session existe pour gérer les utilisteurs co ou non co
if (isset($_SESSION['user_id'])) {
    $user = $_SESSION['user_id'];
} else {
    $user = $_COOKIE['userTemp'];
};

$db = Database::connect();

// prepare car on récupère des paramètres
// :cle → pour binder une clé avec une valeur, pour sécuriser
$query = "SELECT * FROM panier WHERE id_item = :id_item AND userTemp = :userTemp";
$stmt = $db->prepare($query);
// bindValue ajoute une verif en plus (nom de la cle, valeur, type)
$stmt->bindValue(':id_item', $id_item, PDO::PARAM_INT);
$stmt->bindValue(':userTemp', $user, PDO::PARAM_STR);
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérif si le produit existe déjà, alors on modifie
if ($item) {
    // Update la qte dans le panier
    $newQte = $item['qte'] + 1;
    $query2 = "UPDATE panier SET qte = :qte, prix = :prix WHERE id_item = :id_item";
    $stmt = $db->prepare($query2);
    $stmt->bindValue(":qte", $newQte, PDO::PARAM_INT);
    $stmt->bindValue(":prix", $prix, PDO::PARAM_INT);
    $stmt->bindValue(":id_item", $id_item, PDO::PARAM_INT);
    $stmt->execute();
    // HTTP_REFERER permet de retourner sur la page qui a appelé panierREQ
    // $_SERVER est une super globale qui donne pas mal d'infos sur le serveur
    header('Location: ' . $_SERVER['HTTP_REFERER']);

    // Sinon on ajoute
} else {
    // Insert l'item dans le panier
    $query3 = "INSERT INTO panier(id_item, qte, prix, userTemp) VALUES(?, ?, ?, ?)";
    $stmt = $db->prepare($query3);
    $stmt->execute([$id_item, 1, $prix, $user]);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

Database::disconnect();

// Si on voulait un délai pour la redirection, on pourrait utiliser refresh
header('Location: index.php');
exit();
