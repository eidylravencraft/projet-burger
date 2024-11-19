<?php

require '../db.php';
require 'verifRole.php';

$db = DataBase::connect();


$query = "DELETE FROM coupons WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->execute([
    ':id' => $_GET['id']
]);

header('Location: indexCoupon.php');