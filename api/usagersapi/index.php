<?php
include '../functions/functions.php';
include 'controlleur.php';
include '../functions/jwt_utils.php';

$http_method = $_SERVER['REQUEST_METHOD'];
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
        echo $matchingData;
        break;
    case "PATCH":
        if(isset($_GET['id_usager'])) {
            $id = htmlspecialchars($_GET['id_usager']);
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData, true);
            $matchingData = updateUsager($linkpdo, $id, $data);
        }
        break;
    case "PUT":
        if(isset($_GET['id_usager'])) {
            $id = htmlspecialchars($_GET['id_usager']);
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData, true);
            $matchingData = updateAllUsager($linkpdo, $id, $data);
        }
        break;
    case "DELETE":
        if(isset($_GET['id_usager'])) {
            $id = htmlspecialchars($_GET['id_usager']);
            $matchingData = deleteUsager($linkpdo, $id);
        }
        break;
}

deliver_response($matchingData['status_code'], $matchingData['status_message'], $matchingData['data']);
?>