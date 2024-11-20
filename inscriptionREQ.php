<?php
require 'db.php';
session_start();

$db = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty(trim($_POST['nom'])) && filter_var(trim($_POST['mail']), FILTER_VALIDATE_EMAIL) && !empty(trim($_POST['mdp']))) {

        $nom = htmlspecialchars($_POST['nom']);
        $mail = htmlspecialchars($_POST['mail']);
        $mdp = $_POST['mdp'];

        // Vérifier si l'utilisateur existe déjà en bdd
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->execute([':email' => $mail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $exist = "L'email existe déjà";
            header('Location: inscription.php?incorrect=' . $exist);
            // envoyer une erreur
        } else {
            $mdph = password_hash($mdp, PASSWORD_DEFAULT);
            $query2 = "INSERT INTO users (nom, email, password, role) VALUE (:nom, :mail, :password, :role)";
            $stmt2 = $db->prepare($query2);
            $stmt2->execute([':nom' => $nom, ':mail' => $mail, ':password' => $mdph, ':role' => 'user']);

            $registered = "Vous êtes inscrit";
            header('Location: inscription.php?registered=' . $registered);
        }
    } else {
        $exist = "Veuillez remplir tous les champs";
        header('Location: inscription.php?incorrect=' . $exist);
    }
}

Database::disconnect();
