<?php
function readUsagerID($id, $linkpdo)
{
    $sql = "SELECT * FROM usager WHERE id_usager = :id";
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
    $data = $req->fetch();

    if($data == null){
        $reponse['status_code'] = 204;
        $reponse['status_message'] = 'Succès';
        $reponse['data'] = 'Aucun utilisateur trouvé';
        return $reponse;
    }

    $reponse['status_code'] = 200;
    $reponse['status_message'] = 'Succes';
    $reponse['data'] = $data;
    return $reponse;
}

function readUsager($linkpdo)
{
    $sql = "SELECT * FROM usager";
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

    if(!$data){
        $reponse['status_code'] = 204;
        $reponse['status_message'] = 'Succès';
        $reponse['data'] = 'Aucun utilisateur trouvé';
        return $reponse;
    }

    $reponse['status_code'] = 200;
    $reponse['status_message'] = 'Succes';
    $reponse['data'] = $data;
    return $reponse;
}


function createUsager($linkpdo, $data) {
    // Préparation de la requête SQL
    $sql = "INSERT INTO `usager` (`id_usager`, `civilite`, `nom`, `prenom`, `sexe`, `adresse`, `code_postal`, `ville`, `date_nais`, `lieu_nais`, `num_secu`, `id_medecin`)
            VALUES (0, :civ, :nom, :prenom, :sexe, :adresse, :code_postal, :ville, :date_naissance, :lieu_naissance, :numero_secu, :id_medecin)";

    // Préparation de la requête SQL
    $req = $linkpdo->prepare($sql);

    $req->bindParam(':civ', $data['civilite'], PDO::PARAM_STR);
    $req->bindParam(':nom', $data['nom'], PDO::PARAM_STR);
    $req->bindParam(':prenom', $data['prenom'], PDO::PARAM_STR);
    $req->bindParam(':sexe', $data['sexe'], PDO::PARAM_STR);
    $req->bindParam(':adresse', $data['adresse'], PDO::PARAM_STR);
    $req->bindParam(':code_postal', $data['code_postal'], PDO::PARAM_STR);
    $req->bindParam(':ville', $data['ville'], PDO::PARAM_STR);
    $req->bindParam(':date_naissance', $data['date_nais'], PDO::PARAM_STR);
    $req->bindParam(':lieu_naissance', $data['lieu_nais'], PDO::PARAM_STR);
    $req->bindParam(':numero_secu', $data['num_secu'], PDO::PARAM_STR);
    $req->bindParam(':id_medecin', $data['id_medecin'], PDO::PARAM_STR);

    // Démarrage de la transaction
    $linkpdo->beginTransaction();

    try {
        if ($req->execute()) {
            $linkpdo->commit();
            $response['status_code'] = 201;
            $response['status_message'] = 'Created';
            $response['data'] = $data;
        }
    } catch (Exception $e) {
        $linkpdo->rollBack();
        $response['status_code'] = 500; // Erreur interne du serveur
        $response['status_message'] = 'Erreur lors de l\'insertion : ' . $e->getMessage();
    }
    return json_encode($response);
}

function updateUsager($linkpdo, $id, $data)
{
    $sqlRecup = "SELECT * FROM usager WHERE id_usager = :id";
    $reqRecup = $linkpdo->prepare($sqlRecup);
    $reqRecup->bindParam(':id', $id, PDO::PARAM_INT);
    $reqRecup->execute();
    $donneRecup = $reqRecup->fetch();

    $sql = "UPDATE `usager` SET 
            `civilite` = :civ, 
            `nom` = :nom, 
            `prenom` = :prenom,
            `sexe` = :sexe,
            `adresse` = :adresse,
            `code_postal` = :code_postal,
            `ville` = :ville,
            `date_nais` = :date_nais,
            `lieu_nais` = :lieu_nais,
            `num_secu` = :num_secu,
            `id_medecin` = :id_medecin
        WHERE `id_usager` = :id";

    $req = $linkpdo->prepare($sql);

    $req->bindValue(':civ', isset($data['civilite']) ? $data['civilite'] : $donneRecup['civilite'], PDO::PARAM_STR);
    $req->bindValue(':nom', isset($data['nom']) ? $data['nom'] : $donneRecup['nom'], PDO::PARAM_STR);
    $req->bindValue(':prenom', isset($data['prenom']) ? $data['prenom'] : $donneRecup['prenom'], PDO::PARAM_STR);
    $req->bindValue(':sexe', isset($data['sexe']) ? $data['sexe'] : $donneRecup['sexe'], PDO::PARAM_STR);
    $req->bindValue(':adresse', isset($data['adresse']) ? $data['adresse'] : $donneRecup['adresse'], PDO::PARAM_STR);
    $req->bindValue(':code_postal', isset($data['code_postal']) ? $data['code_postal'] : $donneRecup['code_postal'], PDO::PARAM_STR);
    $req->bindValue(':ville', isset($data['ville']) ? $data['ville'] : $donneRecup['ville'], PDO::PARAM_STR);
    $req->bindValue(':date_nais', isset($data['date_nais']) ? $data['date_nais'] : $donneRecup['date_nais'], PDO::PARAM_STR);
    $req->bindValue(':lieu_nais', isset($data['lieu_nais']) ? $data['lieu_nais'] : $donneRecup['lieu_nais'], PDO::PARAM_STR);
    $req->bindValue(':num_secu', isset($data['num_secu']) ? $data['num_secu'] : $donneRecup['num_secu'], PDO::PARAM_STR);
    $req->bindValue(':id_medecin', isset($data['id_medecin']) ? $data['id_medecin'] : $donneRecup['id_medecin'], PDO::PARAM_STR);
    $req->bindValue(':id', $id, PDO::PARAM_INT);

    $linkpdo->beginTransaction(); //Démarage de la transaction

    if (!$req->execute()) {
        $reponse['status_code'] = 404;
        $reponse['status_message'] = 'Ressource non trouvée.';
        $reponse['data'] = null;
        return $reponse;
    }

    $reponse['status_code'] = 200;
    $reponse['status_message'] = 'Succes';
    $reponse['data'] = $data;

    $linkpdo->commit();

    return $reponse;
}

function deleteUsager($linkpdo, $id)
{
    $linkpdo->beginTransaction(); //Démarage de la transaction

    $sqlDeleteConsultations = "DELETE FROM consultation WHERE id_usager = :id_usager";
    $reqDeleteConsultations = $linkpdo->prepare($sqlDeleteConsultations);
    $reqDeleteConsultations->bindParam(':id_usager', $id, PDO::PARAM_INT);
    $reqDeleteConsultations->execute();

    $sql = "DELETE FROM `usager` WHERE id_usager = :id";
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

    $reponse['status_code'] = 200;
    $reponse['status_message'] = 'Succes';
    $reponse['data'] = null;

    $linkpdo->commit();

    return $reponse;
}