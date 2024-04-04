<?php
function readMedecinID($id, $linkpdo)
{
    $sql = "SELECT * FROM medecin WHERE id_medecin = :id";
    if ($sql == false) {
        $reponse['status_code'] = 400;
        $reponse['status_message'] = 'La syntaxe de la requête est erronée.';
        $reponse['data'] = null;
        return $reponse;
    }
    $req = $linkpdo->prepare($sql);
    $req->bindParam(':id', $id, PDO::PARAM_INT);

    if (!$req->execute()) {
        $reponse['status_code'] = 404;
        $reponse['status_message'] = 'Ressource non trouvée.';
        $reponse['data'] = null;
        return $reponse;
    }
    $reponse['status_code'] = 200;
    $reponse['status_message'] = 'Succes';
    $reponse['data'] = $req->fetch();;
    return $reponse;
}

function readMedecin($linkpdo)
{
    $sql = "SELECT * FROM medecin";
    $reponse = array();

    $req = $linkpdo->prepare($sql);

    if (!$req) {
        $reponse['status_code'] = 400;
        $reponse['status_message'] = 'Erreur de syntaxe de requête.';
        $reponse['data'] = null;
        return $reponse;
    }

    // Exécution de la requête
    if (!$req->execute()) {
        $reponse['status_code'] = 404;
        $reponse['status_message'] = 'Ressource non trouvée.';
        $reponse['data'] = null;
        return $reponse;
    }

    $data = $req->fetchAll();
    if (empty($data)) {
        $reponse['status_code'] = 204; // Aucune donnée trouvée
        $reponse['status_message'] = 'Aucune donnée trouvée.';
    } else {
        $reponse['status_code'] = 200;
        $reponse['status_message'] = 'Succès';
    }
    $reponse['data'] = $data;
    return $reponse;
}


function createMedecin($linkpdo, $data){
    $sql = "INSERT INTO `medecin` (`id_medecin`, `civilite`, `nom`, `prenom`) VALUES
    (0, :civ , :nom, :prenom)";
    if ($sql == false) {
        $reponse['status_code'] = 400;
        $reponse['status_message'] = 'La syntaxe de la requête est erronée.';
        $reponse['data'] = null;
        return $reponse;
    }
    $req = $linkpdo->prepare($sql);
    $req->bindParam(':civ', $data['civilite'], PDO::PARAM_STR);
    $req->bindParam(':nom', $data['nom'], PDO::PARAM_STR);
    $req->bindParam(':prenom', $data['prenom'], PDO::PARAM_STR);

    $linkpdo->beginTransaction(); //Démarage de la transaction

    if (!$req->execute()) {
        $reponse['status_code'] = 404;
        $reponse['status_message'] = 'Ressource non trouvée.';
        $reponse['data'] = null;
        return $reponse;
    }

    $reponse['status_code'] = 201;
    $reponse['status_message'] = 'Created';
    $reponse['data'] = $data;

    $linkpdo->commit();

    return $reponse;
}

function UpdateMedecin($linkpdo, $id, $data)
{
    $sqlRecup = "SELECT * FROM medecin WHERE id_medecin = :id";
    $reqRecup = $linkpdo->prepare($sqlRecup);
    $reqRecup->bindParam(':id', $id, PDO::PARAM_INT);
    $reqRecup->execute();
    $donneRecup = $reqRecup->fetch(PDO::FETCH_ASSOC);

    if (!$donneRecup) {
        $reponse['status_code'] = 404;
        $reponse['status_message'] = 'Médecin non trouvé.';
        $reponse['data'] = null;
        return $reponse;
    }

    $sql = "UPDATE `medecin` SET 
            `civilite` = :civ, 
            `nom` = :nom, 
            `prenom` = :prenom
        WHERE `id_medecin` = :id";

    $req = $linkpdo->prepare($sql);

    // Liaison des paramètres
    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->bindValue(':civ', isset($data['civilite']) ? $data['civilite'] : $donneRecup['civilite'], PDO::PARAM_STR);
    $req->bindValue(':nom', isset($data['nom']) ? $data['nom'] : $donneRecup['nom'], PDO::PARAM_STR);
    $req->bindValue(':prenom', isset($data['prenom']) ? $data['prenom'] : $donneRecup['prenom'], PDO::PARAM_STR);

    $linkpdo->beginTransaction();

    if (!$req->execute()) {
        $reponse['status_code'] = 404;
        $reponse['status_message'] = 'Ressource non trouvée.';
        $reponse['data'] = null;
        return $reponse;
    }
    $linkpdo->commit();

    $reponse['status_code'] = 200;
    $reponse['status_message'] = 'Succès';
    $reponse['data'] = $data;

    return $reponse;
}

function deleteMedecin($linkpdo, $id)
{
    $linkpdo->beginTransaction(); //Démarage de la transaction

    $sqlDeleteConsultations = "DELETE FROM consultation WHERE id_medecin = :id_medecin";
    $reqDeleteConsultations = $linkpdo->prepare($sqlDeleteConsultations);
    $reqDeleteConsultations->bindParam(':id_medecin', $id, PDO::PARAM_INT);
    $reqDeleteConsultations->execute();

    $sql = "DELETE FROM `medecin` WHERE id_medecin = :id";
    if ($sql == false) {
        $reponse['status_code'] = 400;
        $reponse['status_message'] = 'La syntaxe de la requête est erronée.';
        $reponse['data'] = null;
        return $reponse;
    }
    $req = $linkpdo->prepare($sql);
    $req->bindParam(':id', $id, PDO::PARAM_STR);

    if (!$req->execute()) {
        $reponse['status_code'] = 404;
        $reponse['status_message'] = 'Ressource non trouvée.';
        $reponse['data'] = null;
        return $reponse;
    }

    $reponse['status_code'] = 204;
    $reponse['status_message'] = 'No content';
    $reponse['data'] = null;

    $linkpdo->commit();

    return $reponse;
}