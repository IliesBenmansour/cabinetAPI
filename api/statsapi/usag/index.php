<?php
include '../../functions/functions.php';
include 'controlleur.php';
include '../../functions/jwt_utils.php';

$http_method = $_SERVER['REQUEST_METHOD'];
$linkpdo = connexionBD();

switch ($http_method) {
    case "GET":
        $matchingData = getStatistiquesMedecins($linkpdo);
        break;
    default:
        break;
}

deliver_response($matchingData['status_code'], $matchingData['status_message'], $matchingData['data']);
?>