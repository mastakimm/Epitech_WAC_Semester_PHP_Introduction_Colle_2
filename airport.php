<?php

$input = $argv[1];
displayCycle($input);
function displayCycle($str)
{
    $displayLen = 30; // Taille en char de l'affichage.

    $temp = str_repeat(" ", $displayLen) . $str . str_repeat(" ", $displayLen);
    /* Mets des espaces au début et à la fin de $str pour faire l'animation
    de droite à gauche et couper l'affichage */

    $strLen = strlen($temp);

    while (true) {
        for ($i = 0; $i < $strLen - $displayLen; $i++) {
            echo substr($temp, $i, $displayLen);
            usleep(500000);  // Attendre 0.5 seconde entre chaque char.
            system('clear');
            // Attend que l'affichage de $temp soit terminé pour recommencer.
        }
    }
}
