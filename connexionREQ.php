<?php
require 'db.php';
session_start();

$db = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $mail = htmlspecialchars($_POST['mail']);
    $mdp = $_POST['mdp'];
    // var_dump($mdp, $mail);
    // die();

    if (filter_var($mail, FILTER_VALIDATE_EMAIL) && !empty($mdp)) {
        // récupérer dans la base de donnée et vérifier si le mail et le mot de passe correspondent aux valeurs dans la base de données
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->execute([':email' => $mail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($user)) {
            // var_dump($mdp, $user['password']);
            // die();
            $mdpVerif = password_verify($mdp, $user['password']);
            if ($mdpVerif) {
                $_SESSION['user_mail'] = $mail;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];

                $query2 = "SELECT * FROM panier WHERE userTemp = :userTemp";
                $stmt = $db->prepare($query2);
                $stmt->execute([':userTemp' => $_COOKIE['userTemp']]);
                $userCart = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($userCart)) {
                    foreach ($userCart as $product) {
                        $query3 = "UPDATE panier SET userTemp = :userId";
                        $stmt = $db->prepare($query3);
                        $stmt->execute([':userId' => $_SESSION['user_id']]);
                    }
                }

                $registered = "Vous êtes connecté";
                header('Location: ' . $_SERVER['HTTP_REFERER'] . '?registered=' . $registered);
                exit();
            } else {
                $incorrect = "Erreur, le mot de passe ne correspond pas";
                header('Location: inscription.php?incorrect=' . $incorrect);
                exit();
            }
        } else {
            $incorrect = "Aucun compte n'existe avec cet email";
            header('Location: inscription.php?incorrect=' . $incorrect);
            exit();
        }
    } else {
        $incorrect = "Veuillez vérifier vos champs";
        header('Location: inscription.php?incorrect=' . $incorrect);
        exit();
    }
}
Database::disconnect();
