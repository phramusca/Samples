<?php

if ($_SERVER['HTTP_HOST'] == "phramusca.free.fr") {
    include 'config.phramusca.php';
} else {
    include 'config.local.php';
}

$connection = mysql_connect($db_host, $db_user, $db_pass);
if (!$connection) {
    die('Échec de la connexion : ' . mysql_error());
}

$db_selected = mysql_select_db($db_name, $connection);
if (!$db_selected) {
    die('Impossible de sélectionner la base de données : ' . mysql_error());
}

// Fonctions de gestion de JSON
function json_encode_custom($data) {
    if (is_array($data)) {
        $isAssoc = array_keys($data) !== range(0, count($data) - 1);
        $json = array();
        foreach ($data as $key => $value) {
            $json[] = ($isAssoc ? '"' . $key . '":' : '') . json_encode_custom($value);
        }
        return $isAssoc ? '{' . implode(',', $json) . '}' : '[' . implode(',', $json) . ']';
    } elseif (is_object($data)) {
        $json = array();
        foreach ($data as $key => $value) {
            $json[] = '"' . $key . '":' . json_encode_custom($value);
        }
        return '{' . implode(',', $json) . '}';
    } else {
        return is_numeric($data) ? $data : '"' . addslashes($data) . '"';
    }
}

function json_decode_custom($json) {
    $comment = false;
    $out = '$x=';
    for ($i = 0; $i < strlen($json); $i++) {
        if (!$comment) {
            if ($json[$i] == '{' || $json[$i] == '[') {
                $out .= ' array(';
            } elseif ($json[$i] == '}' || $json[$i] == ']') {
                $out .= ')';
            } elseif ($json[$i] == ':') {
                $out .= '=>';
            } elseif ($json[$i] == ',') {
                $out .= ',';
            } elseif ($json[$i] == '"') {
                $out .= '"';
                for ($i++; $json[$i] != '"'; $i++) {
                    $out .= $json[$i] == '\\' ? '\\\\' : $json[$i];
                }
                $out .= '"';
            } else {
                $out .= $json[$i];
            }
        } else {
            $out .= $json[$i];
        }
    }
    eval($out . ';');
    return $x;
}

// Vérifier l'authentification HTTP
function authenticate() {
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="Protected Area"');
        header('HTTP/1.0 401 Unauthorized');
        echo json_encode_custom(array('message' => 'Authentification requise.'));
        exit;
    } else {
        global $connection;
        $username = mysql_real_escape_string($_SERVER['PHP_AUTH_USER']);
        $password = mysql_real_escape_string($_SERVER['PHP_AUTH_PW']);

        $query = mysql_query("SELECT password FROM samples_users WHERE username = '$username'");
        if (!$query) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode_custom(array('message' => 'Erreur de requête.'));
            exit;
        }

        $row = mysql_fetch_assoc($query);

        $salt = '$6$ThisIsAVeryNiceSaltTrèsSalé$';  // Utiliser le même sel que lors de la génération
        if (!$row || crypt($password, $salt) !== $row['password']) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode_custom(array('message' => 'Authentification invalide.'));
            exit;
        }
    }
}

// Définir le type de contenu pour la réponse JSON
header('Content-Type: application/json');

// Authentifier l'utilisateur
authenticate();

// Récupérer la méthode HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGet();
        break;
    case 'POST':
        handlePost();
        break;
    case 'PUT':
        handlePut();
        break;
    case 'DELETE':
        handleDelete();
        break;
    default:
        echo json_encode_custom(array('message' => 'Méthode non supportée'));
        break;
}

function handleGet() {
    global $connection;
    $result = mysql_query("SELECT * FROM samples_items", $connection);
    if (!$result) {
        echo json_encode_custom(array('message' => 'Erreur de requête.'));
        return;
    }
    $data = array();
    while ($row = mysql_fetch_assoc($result)) {
        $data[] = $row;
    }
    echo json_encode_custom($data);
}

function handlePost() {
    global $connection;
    $input = json_decode_custom(file_get_contents('php://input'), true);
    if (!$input || !isset($input['name']) || !isset($input['email'])) {
        echo json_encode_custom(array('message' => 'Données invalides'));
        return;
    }

    $name = mysql_real_escape_string($input['name']);
    $email = mysql_real_escape_string($input['email']);
    $query = "INSERT INTO samples_items (name, email) VALUES ('$name', '$email')";
    if (mysql_query($query, $connection)) {
        echo json_encode_custom(array('message' => 'Données ajoutées', 'data' => $input));
    } else {
        echo json_encode_custom(array('message' => 'Erreur de requête.'));
    }
}

function handlePut() {
    global $connection;
    $input = json_decode_custom(file_get_contents('php://input'), true);
    if (!$input || !isset($input['id']) || !isset($input['name']) || !isset($input['email'])) {
        echo json_encode_custom(array('message' => 'Données invalides'));
        return;
    }

    $id = intval($input['id']);
    $name = mysql_real_escape_string($input['name']);
    $email = mysql_real_escape_string($input['email']);
    $query = "UPDATE samples_items SET name = '$name', email = '$email' WHERE id = $id";
    if (mysql_query($query, $connection)) {
        echo json_encode_custom(array('message' => 'Données mises à jour', 'data' => $input));
    } else {
        echo json_encode_custom(array('message' => 'Erreur de requête.'));
    }
}

function handleDelete() {
    global $connection;
    $input = json_decode_custom(file_get_contents('php://input'), true);
    if (!$input || !isset($input['id'])) {
        echo json_encode_custom(array('message' => 'Données invalides'));
        return;
    }

    $id = intval($input['id']);
    $query = "DELETE FROM samples_items WHERE id = $id";
    if (mysql_query($query, $connection)) {
        echo json_encode_custom(array('message' => 'Données supprimées'));
    } else {
        echo json_encode_custom(array('message' => 'Erreur de requête.'));
    }
}

mysql_close($connection);
?>
