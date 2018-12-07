<?php
/**
*   WHMCS Test hook to see what data comes back on 
*   --JC Ryan 13 Nov 2018
*/


add_hook('ClientAdd', 1, function($vars){

    $wp_base_path = "c:/inetpub/wwwroot/sbsite/wp";
    include_once($wp_base_path . "/wp-load.php");
    $output_debug_log = true;
    $debug = "\r\n---START---\r\n";
    // helper functions
    function generate_nicename($data){
        $result = "";
        $result = str_replace("@", "", $data);
        $result = str_replace(".", "-", $result);
        return md5($result);
    }
    $debug .= "Inside hook\r\n";
    /*
    * Returned user data looks like this:
            Array
        (
            [userid] => 11465
            [firstname] => JC
            [lastname] => Ryan
            [companyname] => SomethingCo3
            [email] => jc_ryan@live.com
            [address1] => 703 S Elmer Ave
            [address2] => 
            [city] => Sayre
            [state] => Pennsylvania
            [postcode] => 18840
            [country] => US
            [phonenumber] => 1234567890
            [password] => [plaintext password]
            [customfields] => Array
                (
                    [2] => asdf
                    [4] =>  Link on an eBay Listing
                    [5] => asdf
                )
        )
    */
    
    // Make sure the user doesn't already exist 
    if(!username_exists($vars['email'])) {
        $debug .= "Inside !username_exists\r\n";
        $userdata = array();

        $userdata["user_login"] = $vars['email'];
        $userdata["user_pass"] = $vars['password'];
        $userdata["user_nicename"] = generate_nicename($vars['email']);
        $userdata["display_name"] = $vars['firstname']." ".$vars['lastname'];
        $userdata["first_name"] = $vars['firstname'];
        $userdata["last_name"] = $vars['lastname'];
        $userdata["nickname"] = $vars['email'];
        $userdata["user_email"] = $vars['email'];
        $userdata["role"] = "Subscriber";

        $user_id = wp_insert_user($userdata);
        $debug .= "UserID: \r\n".$user_id."\r\n";
        // On Error
        if ( is_wp_error( $user_id ) ) {
            $debug .= "is wp_error";
            file_put_contents('c:/dbs/fromWHMCS.txt', print_r($vars, true), FILE_APPEND);
            file_put_contents('c:/dbs/userdata.txt', print_r($userdata, true), FILE_APPEND);
            file_put_contents('c:/dbs/wp_error.txt', print_r($user_id, true), FILE_APPEND);
        } else {
            $debug .= "no wp_error. Logging user on.";
            // Taken from source code of wp_signon():
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id, true);
            do_action('wp_login', $userdata->user_login, $userdata);
        }

      
    } else { // end if
        $debug .= "user existed already";
    }
    if ($output_debug_log){
    file_put_contents('c:/dbs/debug.txt', print_r($debug, true), FILE_APPEND);
    }

    
});