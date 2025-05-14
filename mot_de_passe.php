<?php
function genererMotDePasse($longueur = 8) {
    $lettres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chiffres = '0123456789';
    $symboles = '@#$%&*!?';

    $motDePasse = '';
    $motDePasse .= $lettres[random_int(0, strlen($lettres) - 1)];
    $motDePasse .= $symboles[random_int(0, strlen($symboles) - 1)];

    // Nombre de caractères restants à générer
    $reste = $longueur - strlen($motDePasse);

    // Tous les caractères possibles
    $tous = $lettres . $chiffres . $symboles;

    for ($i = 0; $i < $reste; $i++) {
        $motDePasse .= $tous[random_int(0, strlen($tous) - 1)];
    }

    // Mélanger les caractères pour plus d'aléatoire
    $motDePasse = str_shuffle($motDePasse);

    return $motDePasse;
}

// Exemple d'utilisation
// echo "Mot de passe généré : " . genererMotDePasse();
?>