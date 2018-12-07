<?php

/*  
 *  WHMCS hook that handles WHMCS logout and logs out of WP.
 * --JC Ryan 27 Nov 2018
 * 
 */

add_hook('ClientLogout', 1, function($vars){
    $wp_base_path = "c:/inetpub/wwwroot/sbsite/wp";
    include_once($wp_base_path . "/wp-load.php");
    // Log the user out of WP as well.
    wp_logout();
});