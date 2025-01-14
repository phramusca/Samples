<?php

if ($_SERVER['HTTP_HOST'] == "phramusca.free.fr") {
    include 'config.phramusca.php';
} else {
    include 'config.local.php';
}

try {
    $connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Échec de la connexion : ' . $e->getMessage());
}

// Créer un utilisateur administrateur
$username = 'admin';
$password = 'wDq4KKVT'; // Assurez-vous d'utiliser un mot de passe sécurisé

// Générer un sel statique (en production, utilisez un sel unique pour chaque utilisateur)
//$1$ indique que l'algorithme MD5 doit être utilisé.
//Le $ final est utilisé pour délimiter la fin du sel.
//Pour bcrypt : $2y$10$ (où 10 est le facteur de coût).
//Pour SHA-256 : $5$ (le SHA-256 par défaut).
//Pour SHA-512 : $6$ (le SHA-512 par défaut).
// $salt = '$6$usesomesalt$';
$salt = '$6$ThisIsAVeryNiceSaltTrèsSalé$';
$hashed_password = crypt($password, $salt);

// Préparer et exécuter la requête d'insertion
$query = $connection->prepare("INSERT INTO pdo_users (username, password) VALUES (:username, :password)");
$query->bindParam(':username', $username);
$query->bindParam(':password', $hashed_password);

if ($query->execute()) {
    echo "Utilisateur administrateur créé avec succès.";
} else {
    echo "Erreur lors de la création de l'utilisateur.";
}

// Fermer la connexion
$connection = null;
?>
