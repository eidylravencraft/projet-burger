<?php
require 'db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user = $_SESSION['user_id'];
} else {
    $user = $_COOKIE['userTemp'];
}
// Stocke dans la super globale $_post json_decode (pour récup ce qu'on a envoyer et le décoder)
// file_get_contents interprète la requête post, true → pour dire de strocker sous forme de tableau associatif
$_POST = json_decode(file_get_contents('php://input'), true);

$db = Database::connect();

// On verif sur la clé action qu'on a envoyé
if ($_POST['action'] == 'decrease') {
    // intval : force à être un int
    $newQte = intval($_POST['qt']) - 1;
    // floatval : force à être un float
    $newPrice = floatval($_POST['price']) * $newQte;
    // Mise à jour du panier avec prepare car il y a des paramètres
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
