<?php
include "controlleur.php";
include "../functions/jwt_utils.php";
include "../functions/functions.php";


if(TRUE){
    $linkpdo = connexionBD();
    $http_method = $_SERVER['REQUEST_METHOD'];
    switch ($http_method){
        case "GET" :
            if(!isset($_GET['id_consult']))
            {
                $matchingData =affichConsult($linkpdo);
                deliver_response($matchingData['status_code'],$matchingData['status_message'],$matchingData['data']);
            }else{
                $id=htmlspecialchars($_GET['id_consult']);
                $matchingData =affichUneConsult($linkpdo, $id);
                deliver_response($matchingData['status_code'],$matchingData['status_message'],$matchingData['data']);
            }
            break;
        case "POST" :
            //Récupération des données dans le corps
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData,true); //Reçoit du json et renvoi une
            $matchingData =creerConsult($linkpdo,$data);
            deliver_response($matchingData['status_code'],$matchingData['status_message'],$matchingData['data']);

            break;
        case "PATCH":
            if(isset($_GET['id_consult']))
            {
                $id=htmlspecialchars($_GET['id_consult']);
                $postedData = file_get_contents('php://input');
                $data = json_decode($postedData,true);
                $matchingData =patchConsult($linkpdo,$id,$data);
                deliver_response($matchingData['status_code'],$matchingData['status_message'],$matchingData['data']);
            }
            break;
        case "DELETE":
            if(isset($_GET['id_consult']))
            {
                $id=htmlspecialchars($_GET['id_consult']);
                $matchingData =deleteConsult($linkpdo, $id);
                deliver_response($matchingData['status_code'],$matchingData['status_message'],$matchingData['data']);
                //Traitement des données
            }
            break;
    }
}else{
    deliver_response(401,"Token non valide");

}


?>