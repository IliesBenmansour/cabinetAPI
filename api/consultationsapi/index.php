<?php
include "controller.php";
include "../../auth/authapi.php";

if(get_bearer_token() == null){
    $reponse["status_code"] = 401;
    $reponse['status_message'] = "Token invalide";
    $reponse['data'] = null;
    return deliver_response($reponse['status_code'],$reponse['status_message'],$reponse['data']);
}


if(isValidToken(get_bearer_token(),'') == TRUE){
    $linkpdo = connexion_BD();
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
            $matchingData =creerConsult($linkpdo,$data['date_consult'],$data['heure_consult'],$data['duree_consult'],$data['id_medecin'],$data['id_usager']);
            deliver_response($matchingData['status_code'],$matchingData['status_message'],$matchingData['data']);

            break;
        case "PATCH":
            if(isset($_GET['id_consult']))
            {
                $id=htmlspecialchars($_GET['id_consult']);
                $postedData = file_get_contents('php://input');
                $data = json_decode($postedData,true);
                $matchingData =patchConsult($linkpdo,$id,$data['date_consult'],$data['heure_consult'],$data['duree_consult'],$data['id_medecin'],$data['id_usager']);
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