<?php
function getStatistiquesMedecins($bdd) {
    try {
        $sql = "SELECT m.nom, SUM(c.duree_consult) as duree
        FROM consultation c, medecin m
        WHERE c.id_medecin = m.id_medecin
        GROUP BY c.id_medecin";

        $result = $bdd->query($sql);
        $medecins = $result->fetchAll(PDO::FETCH_ASSOC);

        $reponse['status_code'] = 200;
        $reponse['status_message'] = 'Succès';
        $reponse['data'] = $medecins;
        return $reponse;
    } catch (PDOException $e) {
        $reponse['status_code'] = 500;
        $reponse['status_message'] = 'Erreur de serveur';
        $reponse['data'] = null;
        return $reponse;
        }
    }