<?php
require '../db.php';
require 'verifRole.php';

$db = DataBase::connect();

$query = "SELECT * FROM coupons";
$coupons = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>
  <title>Burger Code</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <link href='http://fonts.googleapis.com/css?family=Holtwood+One+SC' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../styles.css">
</head>

<body>
  <h1 class="text-logo"> Code Coupon </h1>
  <div class="container admin">
    <div class="row">
      <h1><strong>Liste des coupons </strong><a href="insertCoupon.php" class="btn btn-success btn-lg"><span class="bi-plus"></span> Ajouter</a></h1>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Code</th>
            <th>Remise</th>
            <th>Type</th>
            <th>Date début</th>
            <th>Date fin</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($coupons as $coupon) { ?>
            <tr>
              <td><?= $coupon['code'] ?></td>
              <td><?= $coupon['remise'] ?></td>
              <td><?= $coupon['type'] ?></td>
              <td><?= date("d F Y H:i:s",strtotime($coupon['debut'])) ?></td>
              <td><?= date("d/m/Y H:i:s",strtotime($coupon['fin'])) ?></td>
              <td width=340>               
                <a class="btn btn-primary" href="admin/updateCoupon.php?id=<?= $coupon['id'] ?>"><span class="bi-pencil"></span> Modifier</a>
                <a class="btn btn-danger" href="admin/deleteCoupon.php?id=<?= $coupon['id'] ?>"><span class="bi-x"></span> Supprimer</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>