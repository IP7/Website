<?php

function display_contact_page() {
    return tpl_render('contact.html', array(
        'page' => array( 'title' => 'Contact' )
    ));
}

function display_sitemap_page() {
    return tpl_render('sitemap.html', array(
        'page' => array( 'title' => 'Plan du site' )
    ));
}

?>
