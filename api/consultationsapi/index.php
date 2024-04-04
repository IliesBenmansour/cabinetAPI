<?php
include "controlleur.php";
include "../functions/jwt_utils.php";
include "../functions/functions.php";

// Vérifier si le token existe
if (get_bearer_token() == null) {
    $reponse["status_code"] = 401;
    $reponse['status_message'] = "Il n'y a pas de token";
    $reponse['data'] = null;
    return deliver_response($reponse['status_code'], $reponse['status_message'], $reponse['data']);
}

// Vérifier si le token est valide
if (validToken()) {
    $linkpdo = connexionBD();
    $http_method = $_SERVER['REQUEST_METHOD'];
    switch ($http_method) {
        case "GET":
            if (!isset($_GET['id_consult'])) {
                $matchingData = affichConsult($linkpdo);
                deliver_response($matchingData['status_code'], $matchingData['status_message'], $matchingData['data']);
            } else {
                $id = htmlspecialchars($_GET['id_consult']);
                $matchingData = affichUneConsult($linkpdo, $id);
                deliver_response($matchingData['status_code'], $matchingData['status_message'], $matchingData['data']);
            }
            break;
        case "POST":
            // Récupération des données dans le corps
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData, true); // Reçoit du json et renvoi une
            $matchingData = creerConsult($linkpdo, $data);
            deliver_response($matchingData['status_code'], $matchingData['status_message'], $matchingData['data']);
            break;
        case "PATCH":
            if (isset($_GET['id_consult'])) {
                $id = htmlspecialchars($_GET['id_consult']);
                $postedData = file_get_contents('php://input');
                $data = json_decode($postedData, true);
                $matchingData = patchConsult($linkpdo, $id, $data);
                deliver_response($matchingData['status_code'], $matchingData['status_message'], $matchingData['data']);
            }
            break;
        case "DELETE":
            if (isset($_GET['id_consult'])) {
                $id = htmlspecialchars($_GET['id_consult']);
                $matchingData = deleteConsult($linkpdo, $id);
                deliver_response($matchingData['status_code'], $matchingData['status_message'], $matchingData['data']);
                //Traitement des données
            }
            break;
    }
} else {
    deliver_response(401, "Vous ne pouvez pas y acceder sans connexion");
}
?>
