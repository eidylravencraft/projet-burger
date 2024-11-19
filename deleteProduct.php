<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];

    $db = Database::connect();

    $query = "DELETE FROM panier WHERE id_item = :id_item AND userTemp = :userTemp";
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':id_item' => $id,
        ':userTemp' => $_SESSION['user_id'] ?? $_COOKIE['userTemp']
    ]);

    Database::disconnect();

    echo json_encode(['success' => true]);
    

}

