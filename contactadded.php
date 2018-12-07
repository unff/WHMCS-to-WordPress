<?php
/**
*   WHMCS Hook to add sub account to WP
*   --JC Ryan 26 Nov 2018
*/


add_hook('ContactAdd', 1, function($vars){
    /*
        $vars contains:
        userid	int	
        contactid	int	
        firstname	string	
        lastname	string	
        companyname	string	
        email	string	
        address1	string	
        address2	string	
        city	string	
        state	string	
        postcode	string	
        country	string	
        phonenumber	string	
        subaccount	bool	
        password	string	
        permissions	string	A comma separated list of allowed permissions for a sub-account
        generalemails	bool	
        productemails	bool	
        domainemails	bool	
        invoiceemails	bool	
        supportemails	bool	
        affiliateemails	bool	
    */
    $wp_base_path = "c:/inetpub/wwwroot/sbsite/wp";
    include_once($wp_base_path . "/wp-load.php");
    $debug = '\r\n---START---\r\n';
    $output_debug_log = true;
    // helper functions
    function generate_nicename($data){
        $result = "";
        $result = str_replace("@", "", $data);
        $result = str_replace(".", "-", $result);
        return $result;
    }
    $debug .= "Inside hook\r\n";
    // Just add a regular user to WP. Make sure that email doesn't exist already though. 
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
            file_put_contents('c:/dbs/contactFromWHMCS.txt', print_r($vars, true), FILE_APPEND);
            file_put_contents('c:/dbs/contactUserdata.txt', print_r($userdata, true), FILE_APPEND);
            file_put_contents('c:/dbs/contact_wp_error.txt', print_r($user_id, true), FILE_APPEND);
        } else {
            $debug .= "no wp_error";
        }

      
    } else { // end if
        $debug .= "user existed already";
    }

    if ($output_debug_log){
    file_put_contents('c:/dbs/contactDebug.txt', print_r($debug, true), FILE_APPEND);
    }

    
});