<?php
include '../functions/functions.php';
include 'controlleur.php';
include '../functions/jwt_utils.php';

$http_method = $_SERVER['REQUEST_METHOD'];
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

deliver_response($matchingData['status_code'], $matchingData['status_message'], $matchingData['data']);

?>