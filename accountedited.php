<?php

/*  
 *
 * 
 * 
 */

add_hook('ClientEdit', 1, function($vars){
    /* 
        $vars contains:
        userid	int	
        uuid	string	
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
        password	string	The encrypted password for the user
        currency	int	The id of the currency
        notes	string	
        status	string	
        taxexempt	bool	
        latefeeoveride	bool	
        overideduenotices	bool	
        separateinvoices	bool	
        disableautocc	bool	
        emailoptout	bool	
        marketing_emails_opt_in	bool	
        overrideautoclose	bool	
        language	string	
        billingcid	int	
        securityqid	int	
        securityqans	string	The encrypted security question answer
        groupid	int	The id of the client group
        allow_sso	bool	Is Single Sign on enabled for the client?
        olddata	array	An array of the previous contact information
        authmodule	string	The 2Factor Auth Module enabled for the user
        authdata	string	The 2Factor Auth Data for the user
        email_verified	bool	
        olddata	array	An array of the previous user information
    */

    // Do we really care about this?  I'll implement this if users are able to see their WP account details.

});