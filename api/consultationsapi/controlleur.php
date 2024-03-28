<?php
function affichConsult($linkpdo){

    $sql = "SELECT * FROM consultation";
    if($sql == false){
        $reponse["status_code"] = 401;
        $reponse['status_message'] = "ya un pb avec la requete";
        $reponse['data'] = null;
        return $reponse;
    }
    $reqAllFacts = $linkpdo->prepare($sql);
    if(!$reqAllFacts->execute()){
        $reponse["status_code"] = 401;
        $reponse['status_message'] = "ressource non trouver";
        $reponse['data'] = [];
        return $reponse;
    }
    $reponse["status_code"] = 200;
    $reponse['status_message'] = "Succes";
    $reponse['data'] = $reqAllFacts->fetchAll();
    return $reponse;

}
function affichUneConsult($linkpdo, $id){

    $sql = "SELECT * FROM consultation WHERE id_consult = $id";
    if($sql == false){
        $reponse["status_code"] = 401;
        $reponse['status_message'] = "ya un pb avec la requete";
        $reponse['data'] = null;
        return $reponse;
    }
    $reqAllFacts = $linkpdo->prepare($sql);
    if(!$reqAllFacts->execute()){
        $reponse["status_code"] = 401;
        $reponse['status_message'] = "ressource non trouver";
        $reponse['data'] = [];
        return $reponse;
    }
    $reponse["status_code"] = 200;
    $reponse['status_message'] = "gg";
    $reponse['data'] = $reqAllFacts->fetchAll();
    return $reponse;

}

function creerConsult($linkpdo, $data) {
    $reponse = array();

    $sql = "INSERT INTO consultation (`date_consult`, `heure_consult`, `duree_consult`, `id_medecin`, `id_usager`) VALUES (:date_consult, :heure_consult, :duree_consult, :id_medecin, :id_usager)";

    $reqAllFacts = $linkpdo->prepare($sql);
    $reqAllFacts->bindParam(':date_consult', $data['date_consult']);
    $reqAllFacts->bindParam(':heure_consult', $data['heure_consult']);
    $reqAllFacts->bindParam(':duree_consult', $data['duree_consult']);
    $reqAllFacts->bindParam(':id_medecin', $data['id_medecin']);
    $reqAllFacts->bindParam(':id_usager', $data['id_usager']);

    $resExec = $reqAllFacts->execute();

    if (!$resExec) {
        $reponse["status_code"] = 401;
        $reponse['status_message'] = "Erreur lors de l'exécution de la requête SQL.";
        $reponse['data'] = null;
        return $reponse;
    }

    $reponse["status_code"] = 200;
    $reponse['status_message'] = "Consultation créée avec succès.";
    $reponse['data'] = $data;
    return $reponse;
}


function deleteConsult($linkpdo, $id) {
    $reponse = array();

    $sql = "DELETE FROM consultation WHERE id_consult = :id";

    $reqAllFacts = $linkpdo->prepare($sql);
    $reqAllFacts->bindParam(':id', $id, PDO::PARAM_INT);

    if (!$reqAllFacts->execute()) {
        $reponse["status_code"] = 401;
        $reponse['status_message'] = "Erreur lors de l'exécution de la requête SQL.";
        $reponse['data'] = null;
        return $reponse;
    }

    // Vérifier si une ligne a été affectée par la suppression
    if ($reqAllFacts->rowCount() == 0) {
        $reponse["status_code"] = 404; // Ressource non trouvée
        $reponse['status_message'] = "Aucune consultation trouvée avec cet identifiant.";
        $reponse['data'] = null;
        return $reponse;
    }

    $reponse["status_code"] = 200;
    $reponse['status_message'] = "Consultation supprimée avec succès.";
    $reponse['data'] = null;
    return $reponse;
}

function patchConsult($linkpdo, $id,$data) {

    $sqlRecup = "SELECT * FROM consultation WHERE id_consult = :id";
    $reqRecup = $linkpdo->prepare($sqlRecup);
    $reqRecup->bindParam(':id', $id, PDO::PARAM_INT);
    $reqRecup->execute();
    $donneRecup = $reqRecup->fetch(PDO::FETCH_ASSOC);

    if (!$donneRecup) {
        $reponse['status_code'] = 404;
        $reponse['status_message'] = 'Consultation non trouvé.';
        $reponse['data'] = null;
        return $reponse;
    }

    $sql = "UPDATE consultation SET `date_consult`=:date_consult, `heure_consult`=:heure_consult, `duree_consult`=:duree_consult, `id_medecin`=:id_medecin, `id_usager`=:id_usager WHERE `id_consult`=:id_consult" ;

    $reqAllFacts = $linkpdo->prepare($sql);
    $reqAllFacts->bindValue(':date_consult', isset($data['date_consult']) ? $data['date_consult'] : $donneRecup['date_consult']);
    $reqAllFacts->bindValue(':heure_consult', isset($data['heure_consult']) ? $data['heure_consult'] : $donneRecup['heure_consult']);
    $reqAllFacts->bindValue(':duree_consult', isset($data['duree_consult']) ? $data['duree_consult'] : $donneRecup['duree_consult']);
    $reqAllFacts->bindValue(':id_medecin', isset($data['id_medecin']) ? $data['id_medecin'] : $donneRecup['id_medecin']);
    $reqAllFacts->bindValue(':id_usager', isset($data['id_usager']) ? $data['id_usager'] : $donneRecup['id_usager']);
    $reqAllFacts->bindValue(':id_consult', $id);


    $resExec = $reqAllFacts->execute();

    if (!$resExec) {
        $reponse["status_code"] = 401;
        $reponse['status_message'] = "Erreur lors de l'exécution de la requête SQL.";
        $reponse['data'] = null;
        return $reponse;
    }

    $reponse["status_code"] = 200;
    $reponse['status_message'] = "Consultation modifié avec succès.";
    $reponse['data'] = $data;
    return $reponse;
}

?>