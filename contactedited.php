<?php
/**
*   WHMCS Hook to handle Contact Accounts
*   -  Only subAccounts get WP permission to log in.
*   --JC Ryan 26 Nov 2018
*/


add_hook('ContactEdit', 1, function($vars){
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
        permissions	string	A comma separated list of permissions if the contact is a sub-account
        domainemails	bool	
        generalemails	bool	
        invoiceemails	bool	
        productemails	bool	
        supportemails	bool	
        affiliateemails	bool	
        olddata	array	An array of the previous contact information
    */
    $wp_base_path = "c:/inetpub/wwwroot/sbsite/wp";
    include_once($wp_base_path . "/wp-load.php");
    $debug = '\r\n---START---\r\n';
    $output_debug_log = true;
    // helper functions
  
    $debug .= "Inside hook\r\n";
    if ($vars['subaccount']) {
        // Enable WP account
    } else {
        // Disable WP account
    }
    
    
      if ($output_debug_log){
        file_put_contents('c:/dbs/debug.txt', print_r($debug, true), FILE_APPEND);
      }

    
});