<?php
require 'db.php';
session_start();

$db = Database::connect();

$query = 'SELECT * FROM coupons WHERE code = :codeCoupon AND debut <= NOW() AND fin >= NOW()';
$stmt = $db->prepare($query);
$stmt->bindValue(':codeCoupon', $_POST['code'], PDO::PARAM_STR);
$stmt->execute();
$coupon = $stmt->fetch(PDO::FETCH_ASSOC);

if (!empty($coupon)) {
    // Récupérer le total panier de l'utilisateur courant + appliquer la remise
    $_SESSION['coupon'] = $coupon['remise'] . ' ' . $coupon['type'];
    $totalPanier = $_POST['total'];
    $valeurtRemise = $coupon['remise'];
    $type = $coupon['type'];
    if ($coupon['type'] == '%') {
        $remise = $totalPanier * ($valeurRemise / 100);
    } else if ($coupon['type'] == 'euros') {
        $remise = $valeurRemise;
    }
    $totalAvecRemise = $totalPanier - $remise;
    header('location: panier.php?newTotal=' . $totalAvecRemise);
} else {
    header('location: panier.php?invalid="invalid"');
    // message d'erreur
}

Database::disconnect();
