<?php
/**
*   WHMCS Hook to delete a WP account when a contact account gets deleted.
*   --JC Ryan 26 Nov 2018
*/


add_hook('ContactDelete', 1, function($vars){
    /*
        $vars contains:
        userid	int	
        contactid	int	
    */
    $wp_base_path = "c:/inetpub/wwwroot/sbsite/wp";
    include_once($wp_base_path . "/wp-load.php");
    $debug = '\r\n---START---\r\n';
    $output_debug_log = true;
    // helper functions
  
    $debug .= "Inside hook\r\n";
        // find all of the contacts for userid
        $command = 'GetContacts';
        $postData = array(
            'userid' => $vars["userid"],
            'subaccount' => true
        );
        $results = localAPI($command, $postData);
        // loop through the contacts in $results["contacts"]
        if (is_array($results["contacts"])){
            foreach($results["contacts"] as $contact){
                if ($contact["id"] = $vars["contactid"]) {
                    // found the right contact ID, get the WP userid for this account using the email (username).
                    $user = get_userdatabylogin($contact['email']);
                    if ($user){
                        $debug .="User exists:\r\n";
                        $debug .= var_export($user, true);
                        $debug .= "\r\nDeleting Contact.";
                        wp_delete_user($user->id);
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
        file_put_contents('c:/dbs/debug.txt', print_r($debug, true), FILE_APPEND);
      }

    
});