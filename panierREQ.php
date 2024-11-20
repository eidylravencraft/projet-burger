<?php
require 'db.php';
session_start();

if (isset($_GET['id_item']) && isset($_GET['prix']) || isset($_GET['choix'])) {
    $id_item = $_GET['id_item'];
    $prix = $_GET['prix'];
    $choix = $_GET['choix'];
};

if (isset($_SESSION['user_id'])) {
    $user = $_SESSION['user_id'];
} else {
    $user = $_COOKIE['userTemp'];
};

// $qte = 1;

$db = Database::connect();

$query = "SELECT * FROM panier WHERE id_item = :id_item AND userTemp = :userTemp";
$stmt = $db->prepare($query);
$stmt->bindValue(':id_item', $id_item, PDO::PARAM_INT);
$stmt->bindValue(':userTemp', $user, PDO::PARAM_STR);
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($item) {
    // Update la qte dans le panier
    $newQte = $item['qte'] + 1;
    $query2 = "UPDATE panier SET qte = :qte, prix = :prix WHERE id_item = :id_item";
    $stmt = $db->prepare($query2);
    $stmt->bindValue(":qte", $newQte, PDO::PARAM_INT);
    $stmt->bindValue(":prix", $_GET['prix'], PDO::PARAM_INT);
    $stmt->bindValue(":id_item", $id_item, PDO::PARAM_INT);
    $stmt->execute();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    // Insert l'item dans le panier
    $query3 = "INSERT INTO panier(id_item, qte, prix, userTemp, choix) VALUES(?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query3);
    $stmt->execute([$id_item, 1, $prix, $user, $choix]);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

// Ma réponse à l'exercice
// // récupérer le panier
// $query = "SELECT qte FROM panier WHERE id_item = $id_item";
// $productCart = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
// if ($productCart) {
//     // var_dump($productCart);
//     // die();
//     $qte += $productCart[0]['qte'];
//     $prix = $qte * $prix;
//     $query = "UPDATE panier SET qte = $qte, prix = $prix WHERE id_item = $id_item";
//     $stmt = $db->prepare($query);
//     $stmt->execute();
// } else {
//     $stmt = $db->prepare("INSERT INTO panier (id_item, qte, prix) VALUES (:id_item, :qte, :prix)");
//     $stmt->bindParam(':id_item', $id_item);
//     $stmt->bindParam(':qte', $qte);
//     $stmt->bindParam(':prix', $prix);
//     $stmt->execute();
// };

Database::disconnect();

header('Location: index.php');
exit();
