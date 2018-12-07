<?php

/*  
 *  WHMCS Hook to sign into WP when signed into WHMCS
 *  -- JC Ryan 26 Nov 2018
 * 
 */

add_hook('ClientLogin', 1, function($vars){
    /*
        $vars contains:
        userid	int	
        contactid	int	Contact ID will be present if the login was performed by a contact/sub-account
    */

    $wp_base_path = "c:/inetpub/wwwroot/sbsite/wp";
    include_once($wp_base_path . "/wp-load.php");
    $output_debug_log = true;
    $debug = "\r\n---START---\r\n";
    $debug .= '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']."\r\n";
    $debug .= var_export($_SESSION, true);
    $debug .= "\r\n--END SESSION DUMP--\r\n";
    $user_id = -1;
    // We have a userid and maybe a contactid. We need an email address for WP.
    if ($var['contactid'] && $vars['contactid'] > 0){ // Contact exists, use it for auth. (two checks for overkill, why not)
        // Look up the details of the contact ID. Stupidly, you have to get all the contacts at once.
        $command = 'GetContacts';
        $postData = array(
            'userid' => $vars['userid'],
            'subaccount' => true
        );
        $results = localAPI($command, $postData, $adminUsername);
        /*  WHMCS Contact member looks like:
            'contacts' => 
                array (
                    'contact' => 
                    array (
                      0 => 
                      array (
                        'id' => '1695',
                        'userid' => '11554',
                        'firstname' => 'Sub',
                        'lastname' => 'Account',
                        'companyname' => '',
                        'email' => 'jc_ryan@live.com',
                        'address1' => '703 S Elmer Ave',
                        'address2' => '',
                        'city' => 'Sayre',
                        'state' => 'Pennsylvania',
                        'postcode' => '18840',
                        'country' => 'US',
                        'phonenumber' => '1234567890',
                        'subaccount' => '1',
                        'password' => '$2y$10$pWGs/uIUets5jMIkog9UV.9kPUuNE2SRM8imZssF8GbDRf355XXx.',
                        'permissions' => 'tickets',
                        'domainemails' => '0',
                        'generalemails' => '0',
                        'invoiceemails' => '0',
                        'productemails' => '0',
                        'supportemails' => '0',
                        'affiliateemails' => '0',
                        'pwresetkey' => '',
                        'created_at' => '0000-00-00 00:00:00',
                        'updated_at' => '0000-00-00 00:00:00',
                        'pwresetexpiry' => '0000-00-00 00:00:00',
                    ),
                ),
            )
        */
        //$debug .= var_export($results, true);
        foreach($results['contacts']['contact'] as $contact){
            $debug .= "contact dump\r\n";
            $debug .= var_export($contact, true);
            if ($contact['id'] == $vars['contactid']){
                // Got WHMCS user data, look up WP user data
                $u = get_user_by('email', $contact['email']);
                $user_id = $u['ID'];
            }
        }
    } else { // not a contact, use main account for auth.
        $command = 'GetClientDetails';
        $postData = array(
            'clientid' => $vars['userid']
        );
        $results = localAPI($command, $postData, $adminUsername);
        // Got WHMCS user data, look up WP user data
        $u = get_user_by('email', $client['email']);
        $user_id = $u['ID'];
    }
    if ($user_id > -1){
        $userdata = wp_set_current_user($user_id); // returns a WP_User object
        wp_set_auth_cookie($user_id, true);
        do_action('wp_login', $userdata->user_login, $userdata); // fire any hooks for wp_login
    } else {
        $debug .= "No WP user found for WHMCS user: ".$vars['userid']." : ".$vars['contactid'];
    }

    if ($output_debug_log){
        file_put_contents('c:/dbs/whmLoggedInDebug.txt', print_r($debug, true), FILE_APPEND);
    }
});