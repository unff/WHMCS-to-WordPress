<?php

/*  
 *  Hook to sync WHMCS password reset to WP for contact accounts
 *  --JC Ryan 26 Nov 2018
 * 
 */

add_hook('ContactChangePassword', 1, function($vars){
    // $vars contains userid, contactid and password
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
    $command = 'GetContacts';
    $postData = array(
        'userid' => $vars["userid"],
        'subaccount' => true
    );
    $results = localAPI($command, $postData);
    
    if ($output_debug_log){
        file_put_contents('c:/dbs/contactRstPassAPIcall.txt', print_r($results, true), FILE_APPEND);
    };
    // We've now got ALL of the contacts in $results["contacts"], find the right one.
    if (is_array($results["contacts"])){
        foreach($results["contacts"] as $contact){
            if ($contact["id"] = $vars["contactid"]) {
                // found the right contact ID, get the WP userid for this account using the email (username).
                $user = get_userdatabylogin($contact['email']);
                if ($user){
                    $debug .="User exists:\r\n";
                    $debug .= var_export($user, true);
                    $debug .= "\r\nUpdating Password.";
                    reset_password($user->id, $vars['password']);
                    break 1;
                } else {
                    $debug .= "user not found in WP.";
                }
            } else {
                $debug .= "no match.  next contact.\r\n";
            }
        }
    } else {
        $debug .= "no contacts found for this account.";
    }
    if ($output_debug_log){
        file_put_contents('c:/dbs/contactPWrstDebug.txt', print_r($debug, true), FILE_APPEND);
    };


});