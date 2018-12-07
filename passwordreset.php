<?php

/*  
 *  Hook to sync WHMCS password reset to WP
 *  --JC Ryan 26 Nov 2018
 * 
 */

add_hook('ClientChangePassword', 1, function($vars){
    // $vars contains userid and password
    $wp_base_path = "c:/inetpub/wwwroot/sbsite/wp";
    include_once($wp_base_path . "/wp-load.php");
    $debug = "\r\n---START---\r\n";
    $output_debug_log = true;
    // helper functions

    $debug .= "Inside hook\r\n";
    $debug .= "\r\n$vars\r\n";
    $debug .= var_export($vars, true);
    $debug .= "\r\n\r\n";

    // $vars contains a WHMCS userid.  Look up the user's email.
    $command = 'GetClientsDetails';
    $postData = array(
        'clientid' => $vars["userid"],
        'stats' => true,
    );
    $results = localAPI($command, $postData);
    
    if ($output_debug_log){
        file_put_contents('c:/dbs/rstPassAPIcall.txt', print_r($results, true), FILE_APPEND);
    };
    // Got the user's email, look up the WP userid then reset the password.
    $user = get_userdatabylogin($results['email']);
    if ($user){
        $debug .="User exists:\r\n";
        $debug .= var_export($user, true);
        $debug .= "\r\nDeleting.\r\n";
        reset_password($user->id, $vars['password']);
    } else {
        $debug .= "user not found in WP.";
    }
    if ($output_debug_log){
        file_put_contents('c:/dbs/prstDebug.txt', print_r($debug, true), FILE_APPEND);
    };


});