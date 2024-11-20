<?php
require '../db.php';

$db = Database::connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty(trim($_POST['code'])) && !empty($_POST['type']) && !empty(trim($_POST['remise'])) && !empty(trim($_POST['debut'])) && !empty(trim($_POST['fin_value']))) {
        
        $query = "SELECT code FROM coupons WHERE code = :code"; 
        $stmt = $db->prepare($query);
        $stmt->bindValue(':code', $_POST['code'], PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            header('Refresh: 2; url=insertCoupon.php?error=code');
        }
        
        $code = htmlspecialchars($_POST['code']);
        $remise = htmlspecialchars($_POST['remise']);
        $debut = htmlspecialchars($_POST['debut']);
        $fin = (int)$_POST['fin_value'];
        $fin_unit = $_POST['fin_unit'];

        $debutDateTime = new DateTime($debut);
        
        switch ($fin_unit) {
            case 'hours':
                $debutDateTime->modify('+' . $fin . ' hours');
                break;
            case 'days':
                $debutDateTime->modify('+' . $fin . ' days');
                break;
            case 'months':
                $debutDateTime->modify('+' . $fin . ' months');
                break;
            case 'years':
                $debutDateTime->modify('+' . $fin . ' years');
                break;
        }
    
        $query2 = "INSERT coupons (code, remise, type, debut, fin) VALUE (?, ?, ?, ?, ?)";
        $stmt2 = $db->prepare($query2);
        $stmt2->execute([$code, $remise, $_POST['type'], $debut, $debutDateTime->format('Y-m-d H:i:s')]);

        header('Refresh: 2; url=indexCoupon.php');        
    }
}

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
    <h1 class="text-logo">Coupon Code</h1>
    <div class="container admin">
        <div class="row">
            <h1><strong>Ajouter un coupon</strong></h1>
            <br>
            <form class="form" action="#" method="POST" >
                <br>
                <div>
                    <label class="form-label" for="code">code:</label>
                    <input type="text" class="form-control" id="code" name="code" placeholder="Code" value="">
                    <span class="help-inline"></span>
                </div>
                <br>
                <div>
                    <label class="form-label" for="remise">Remise:</label>
                    <input type="number" class="form-control" id="remise" name="remise" placeholder="Montant" value="">
                    <span class="help-inline"></span>
                </div>
                <br>
                <div>
                    <label class="form-label" for="type">Type:</label>
                    <input type="radio" id="type1" name="type" value="%">
                    <span class="help-inline">%</span>
                    <input type="radio" id="type2" name="type" value="€">
                    <span class="help-inline">€</span>
                </div>
                <br>
                <div>
                    <label class="form-label" for="debut">Date début:</label>
                    <input type="datetime-local" class="form-control" id="debut" name="debut">
                    <span class="help-inline"></input>

                </div>
                <br>
                <div>
                    <label class="form-label" for="fin">Date fin:</label>
                    <input type="number" class="form-control" id="fin_value" name="fin_value">
                    <select type="number" class="form-select" id="fin_unit" name="fin_unit">    
                        <option value="hours">Heure</option>
                        <option value="days">Jours</option>
                        <option value="months">Mois</option>
                        <option value="years">Année</option>
                    </select>
                </div>
                <br>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><span class="bi-pencil"></span> Ajouter</button>
                    <a class="btn btn-primary" href="indexCoupon.php"><span class="bi-arrow-left"></span> Retour</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>