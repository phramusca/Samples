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
// ...existing code...

function authenticate() {
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="Protected Area"');
        header('HTTP/1.0 401 Unauthorized');
        echo json_encode_custom(array('message' => 'Authentification requise.'));
        exit;
    } else {
        global $connection;
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];

        $query = $connection->prepare("SELECT password FROM pdo_users WHERE username = :username");
        $query->bindParam(':username', $username);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);

        $salt = '$6$ThisIsAVeryNiceSaltTrèsSalé$';  // Utiliser le même sel que lors de la génération
        if (!$row || crypt($password, $salt) !== $row['password']) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode_custom(array('message' => 'Authentification invalide.'));
            exit;
        }
    }
}

// ...existing code...
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
    $query = $connection->query("SELECT * FROM pdo_items");
    $data = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode_custom($data);
}

function handlePost() {
    global $connection;
    $input = json_decode_custom(file_get_contents('php://input'), true);
    if (!$input || !isset($input['name']) || !isset($input['email'])) {
        echo json_encode_custom(array('message' => 'Données invalides'));
        return;
    }

    $query = $connection->prepare("INSERT INTO pdo_items (name, email) VALUES (:name, :email)");
    $query->bindParam(':name', $input['name']);
    $query->bindParam(':email', $input['email']);
    if ($query->execute()) {
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

    $query = $connection->prepare("UPDATE pdo_items SET name = :name, email = :email WHERE id = :id");
    $query->bindParam(':id', $input['id'], PDO::PARAM_INT);
    $query->bindParam(':name', $input['name']);
    $query->bindParam(':email', $input['email']);
    if ($query->execute()) {
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

    $query = $connection->prepare("DELETE FROM pdo_items WHERE id = :id");
    $query->bindParam(':id', $input['id'], PDO::PARAM_INT);
    if ($query->execute()) {
        echo json_encode_custom(array('message' => 'Données supprimées'));
    } else {
        echo json_encode_custom(array('message' => 'Erreur de requête.'));
    }
}

$connection = null;
?>
