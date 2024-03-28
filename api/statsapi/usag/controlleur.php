<?php

function getStatistiqueUsager($bdd) {
        $requeteHomme = "SELECT date_nais as dn FROM usager WHERE sexe='M'";
        $resultatHomme = $bdd->query($requeteHomme);
        $hommes = $resultatHomme->fetchAll(PDO::FETCH_ASSOC);

        $requeteFemme = "SELECT date_nais as dn FROM usager WHERE sexe='F'";
        $resultatFemme = $bdd->query($requeteFemme);
        $femmes = $resultatFemme->fetchAll(PDO::FETCH_ASSOC);

        $tranchesAge = array(array(0, 0, 0), array(0, 0, 0)); // [homme, femme]

        foreach (array($hommes, $femmes) as $cle => $patients) {
            for ($i = 0; $i < count($patients); $i++) {
                $age = calculerAge($patients[$i]['dn']);
                if ($age < 18) {
                    $tranchesAge[$cle][0]++;
                } elseif ($age >= 18 && $age < 60) {
                    $tranchesAge[$cle][1]++;
                } else {
                    $tranchesAge[$cle][2]++;
                }
            }
        }
        return array(
            'status_code' => 200,
            'status_message' => 'Succès',
            'data' => $tranchesAge
        );
}
?>
