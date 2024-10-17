<?php

if ($_SERVER['HTTP_HOST'] == "phramusca.free.fr") {
    include 'config.phramusca.php';
} else {
    include 'config.local.php';
}

$connection = mysql_connect($db_host, $db_user, $db_pass);
if (!$connection) {
    die('Échec de la connexion : ' . mysql_error() . ' with user ' . $db_user);
}

$db_selected = mysql_select_db($db_name, $connection);
if (!$db_selected) {
    die('Impossible de sélectionner la base de données : ' . mysql_error());
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
$username = mysql_real_escape_string($username);
$query = "INSERT INTO samples_users (username, password) VALUES ('$username', '$hashed_password')";

if (mysql_query($query, $connection)) {
    echo "Utilisateur administrateur créé avec succès.";
} else {
    echo "Erreur lors de la création de l'utilisateur : " . mysql_error();
}

// Fermer la connexion
mysql_close($connection);
?>