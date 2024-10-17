<?php
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

// Fonction pour écrire dans un fichier
function custom_file_put_contents($filename, $data) {
    $file = fopen($filename, 'w');
    if ($file === false) {
        return false;
    }
    $result = fwrite($file, $data);
    fclose($file);
    return $result;
}

// Définir le type de contenu pour la réponse JSON
header('Content-Type: application/json');

// Récupérer la méthode HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Lire les données actuelles à partir du fichier
$dataFile = 'data.json';
$data = file_exists($dataFile) ? json_decode_custom(file_get_contents($dataFile)) : array();

switch ($method) {
    case 'GET':
        handleGet($data);
        break;
    case 'POST':
        handlePost($data, $dataFile);
        break;
    case 'PUT':
        handlePut($data, $dataFile);
        break;
    case 'DELETE':
        handleDelete($data, $dataFile);
        break;
    default:
        echo json_encode_custom(array('message' => 'Méthode non supportée'));
        break;
}

function handleGet($data) {
    echo json_encode_custom($data);
}

function handlePost($data, $dataFile) {
    $input = json_decode_custom(file_get_contents('php://input'));
    if (!$input) {
        echo json_encode_custom(array('message' => 'Aucune donnée reçue'));
        return;
    }

    $data[] = $input;
    custom_file_put_contents($dataFile, json_encode_custom($data));
    echo json_encode_custom(array('message' => 'Données ajoutées', 'data' => $input));
}

function handlePut($data, $dataFile) {
    $input = json_decode_custom(file_get_contents('php://input'));
    if (!$input || !isset($input['id'])) {
        echo json_encode_custom(array('message' => 'Données invalides'));
        return;
    }

    for ($i = 0; $i < count($data); $i++) {
        if ($data[$i]['id'] == $input['id']) {
            $data[$i] = array_merge($data[$i], $input);
            custom_file_put_contents($dataFile, json_encode_custom($data));
            echo json_encode_custom(array('message' => 'Données mises à jour', 'data' => $data[$i]));
            return;
        }
    }
    echo json_encode_custom(array('message' => 'Données non trouvées'));
}

function handleDelete($data, $dataFile) {
    $input = json_decode_custom(file_get_contents('php://input'));
    if (!$input || !isset($input['id'])) {
        echo json_encode_custom(array('message' => 'Données invalides'));
        return;
    }

    for ($i = 0; $i < count($data); $i++) {
        if ($data[$i]['id'] == $input['id']) {
            array_splice($data, $i, 1);
            custom_file_put_contents($dataFile, json_encode_custom($data));
            echo json_encode_custom(array('message' => 'Données supprimées'));
            return;
        }
    }
    echo json_encode_custom(array('message' => 'Données non trouvées'));
}
?>
