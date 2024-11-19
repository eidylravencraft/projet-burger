<?php
require 'db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user = $_SESSION['user_id'];
} else {
    $user = $_COOKIE['userTemp'];
}
$_POST = json_decode(file_get_contents('php://input'), true);

$db = Database::connect();


if ($_POST['action'] == 'decrease') {
    $newQte = intval($_POST['qt']) - 1;
    $newPrice = floatval($_POST['price']) * $newQte;
    $queryDown = "UPDATE panier SET qte = :qte WHERE qte > 1 AND id_item= :id AND userTemp = :userTemp";
    $stmt = $db->prepare($queryDown);
    $stmt->bindValue(":qte", $newQte, PDO::PARAM_INT);
    $stmt->bindValue(":id", $_POST['id'], PDO::PARAM_INT);
    $stmt->bindValue(":userTemp", $user, PDO::PARAM_STR);
    $stmt->execute();

    $array = [
        'qte' => $newQte,
        'price' => $newPrice,
    ];
    echo json_encode($array);
};

if ($_POST['action'] == 'increase') {
    $newQte = intval($_POST['qt']) + 1;
    $newPrice = floatval($_POST['price']) * $newQte;
    $queryDown = "UPDATE panier SET qte = :qte WHERE id_item= :id AND userTemp = :userTemp";
    $stmt = $db->prepare($queryDown);
    $stmt->bindValue(":qte", $newQte, PDO::PARAM_INT);
    $stmt->bindValue(":id", $_POST['id'], PDO::PARAM_INT);
    $stmt->bindValue(":userTemp", $user, PDO::PARAM_STR);
    $stmt->execute();

    $array = [
        'qte' => $newQte,
        'price' => $newPrice,
    ];
    echo json_encode($array);
};


