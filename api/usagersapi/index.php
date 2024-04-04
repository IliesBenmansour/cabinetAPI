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
        case "GET":
            if(isset($_GET['id_usager'])) {
                $id = htmlspecialchars($_GET['id_usager']);
                $matchingData = readUsagerID($id, $linkpdo);
            } else {
                $matchingData = readUsager($linkpdo);
            }
            break;
        case "POST":
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData, true);
            $matchingData = createUsager($linkpdo, $data);
            break;
        case "PATCH":
            if(isset($_GET['id_usager'])) {
                $id = htmlspecialchars($_GET['id_usager']);
                $postedData = file_get_contents('php://input');
                $data = json_decode($postedData, true);
                $matchingData = updateUsager($linkpdo, $id, $data);
            }
            break;
        case "DELETE":
            if(isset($_GET['id_usager'])) {
                $id = htmlspecialchars($_GET['id_usager']);
                $matchingData = deleteUsager($linkpdo, $id);
            }
            break;
    }
}else {
    return deliver_response(401, "Vous ne pouvez pas y acceder sans connexion");
}

deliver_response($matchingData['status_code'], $matchingData['status_message'], $matchingData['data']);
?>