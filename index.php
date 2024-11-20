<?php
// require ou include
// include affiche le script s'il ne trouve pas le fichier, require lui enverra une erreur
require 'db.php';

session_start();

if (!isset($_COOKIE['userTemp'])) {
    setcookie(
        'userTemp',
        uniqid(),
        [
            'expires' => time() + 86400 * 30,
            'secure' => true,
            'httponly' => true,
        ]
    );
    // $_SESSION['userTemp'] = uniqid();
} else {
    // $_SESSION['userTemp'] = $_SESSION['userTemp'];
    $_COOKIE['userTemp'] = $_COOKIE['userTemp'];
}

// $db est une instance

$db = Database::connect();

// La petit flèche (->) permet via un objet d'atteindre les méthodes contenues dans l'objet
// Grosse flèche (=>) pour définir une valeur dans un tableau associatif

// query n'est pas sécurisé, sensible à des failles sql
// prepare est plus sécurisé

// fetchAll : toutes les entrées
// fetch : une seule entrée
// fetchColumn : un seul champ dans une seule entrée, pas de tableau

// le paramètre PDO:FETCH_ASSO permet de ne récupérer que le tableau associatif

// récupérer les catégories
$query = "SELECT * FROM categories";
$categs = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

// récupérer les produits
$query2 = "SELECT * FROM items";
$products = $db->query($query2)->fetchAll(PDO::FETCH_ASSOC);

Database::disconnect();

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
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container site">

        <div style="text-align:center; display:flex; justify-content:center; align-items:center" class="text-logo">
            <h1>Burger Doe</h1>
            <a href="panier.php" class="bi bi-basket3 cart-icon"> </a>
        </div>

        <nav>
            <ul class="nav nav-pills" role="tablist">

                <!-- < ?php ?> permet d'ouvrir php
                 < ?= ?> est la même chose que < ?php echo ?> -->

                <!-- Boucle pour afficher les catégories -->
                <?php foreach ($categs as $categ) {
                    if ($categ['id'] == 1) {
                        $active = 'active';
                    } else {
                        $active = null;
                    } ?>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?= $active ?>"
                            data-bs-toggle="pill"
                            data-bs-target="#tab<?= $categ['id'] ?>"
                            role="tab">
                            <?= $categ['name'] ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>

        <div class="tab-content">
            <!-- On boucle sur le résultat de la requête grâce à un foreach-->
            <!-- foreach (résultat de la requête as élément courant) -->
            <?php foreach ($categs as $categ) {
                if ($categ['id'] == 1) {
                    $active = 'active';
                } else {
                    $active = null;
                } ?>
                <!-- tab-pane sait quel id il cible grâce à l'id qui arrive derrière -->
                <div class="tab-pane <?= $active ?>" id="tab<?= $categ['id'] ?>" role="tabpanel">
                    <div class="row">
                        <!-- Boucle pour afficher les produits grâce à la requête -->
                        <?php foreach ($products as $product) {

                            // Vérif si les produits de cette boucle correspondent à la catégorie de la boucle plus haut (on a une boucle dans une boucle)
                            if ($product['category'] == $categ['id']) { ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="img-thumbnail">

                                        <!-- Affichage de l'image -->
                                        <img src="images/<?= $product['image'] ?>" class="img-fluid" alt="...">

                                        <!-- Affichage du prix -->
                                        <div class="price"><?= number_format($product['price'], 2, ',', ' ') ?> €</div>
                                        <div class="caption">

                                            <!-- Affichage du nom -->
                                            <h4><?= $product['name'] ?></h4>

                                            <!-- Affichage de la description -->
                                            <p><?= $product['description'] ?></p>

                                            <!-- Le bouton commander, qui permet d'ajouter un produit au panier. On met dans notre lien la page de traitement en php qui permet d'intégrer la logique d'ajout au panier. On intègre au lien les informations du produit sur lequel on clique, qu'on pourra récupérer avec un GET dans panierREQ -->

                                            <!-- Pour ajouter des informations à une url : url?cle=valeur pour un seul paramètre et url?cle=valeur&cle=valeur pour deux paramètres (et on peut en ajouter autant qu'on veut) -->
                                            <a href="panierREQ.php?id_item=<?= $product['id'] ?>&prix=<?= $product['price'] ?>" class="btn btn-order" role="button"><span class="bi-cart-fill"></span> Commander</a>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php } ?>
        </div>
</body>

</html>