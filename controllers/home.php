<?php

function display_home() {

    return Config::$tpl->render('home.html', tpl_array(array(
        'page' => array(
            'title' => 'IP7 – Accueil'
        ),

        // temporary solution
        'welcome' => 'Bienvenue sur le site de la future association IP7.'
    )));

}

?>

