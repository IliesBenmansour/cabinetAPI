<?php
include '../functions/functions.php';
include 'controlleur.php';
include '../functions/jwt_utils.php';

// Vérifier si le token existe
if (get_bearer_token() == null) {
    $reponse["status_code"] = 401;
    $reponse['status_message'] = "Il n'y a pas de token";
    $reponse['data'] = null;
    return deliver_response($reponse['status_code'], $reponse['status_message'], $reponse['data']);
}

$http_method = $_SERVER['REQUEST_METHOD'];
if (validToken()) {
$linkpdo = connexionBD();
switch ($http_method){
    case "GET" :
        if(isset($_GET['id_medecin'])) {
            $id = htmlspecialchars($_GET['id_medecin']);
            $matchingData = readMedecinID($id, $linkpdo);
        } else {
            $matchingData = readMedecin($linkpdo);
        }
        break;
    case "POST" :
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData, true);
        $matchingData = createMedecin($linkpdo, $data);
        break;
    case "PATCH" :
        if(isset($_GET['id_medecin'])) {
            $id = htmlspecialchars($_GET['id_medecin']);
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData, true);
            $matchingData = updateMedecin($linkpdo, $id, $data);
        }
        break;
    case "DELETE" :
        if(isset($_GET['id_medecin'])) {
            $id = htmlspecialchars($_GET['id_medecin']);
            $matchingData = deleteMedecin($linkpdo, $id);
        }
        break;
}
}else {
    return deliver_response(401, "Vous ne pouvez pas y acceder sans connexion");
}

deliver_response($matchingData['status_code'], $matchingData['status_message'], $matchingData['data']);

?>