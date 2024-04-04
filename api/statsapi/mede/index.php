<?php
include '../../functions/functions.php';
include 'controlleur.php';
include '../../functions/jwt_utils.php';

if (get_bearer_token() == null) {
    $reponse["status_code"] = 401;
    $reponse['status_message'] = "Il n'y a pas de token";
    $reponse['data'] = null;
    return deliver_response($reponse['status_code'], $reponse['status_message'], $reponse['data']);
}

$http_method = $_SERVER['REQUEST_METHOD'];
$linkpdo = connexionBD();
if (validToken()) {
    switch ($http_method) {
        case "GET":
            $matchingData = getStatistiquesMedecins($linkpdo);
            break;
        default:
            break;
    }
}else {
    return deliver_response(401, "Vous ne pouvez pas y acceder sans connexion");
}


deliver_response($matchingData['status_code'], $matchingData['status_message'], $matchingData['data']);
?>