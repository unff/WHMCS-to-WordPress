<?php

    add_hook('ClientLogin', 1, function($vars){
    /*
        $vars contains:
        userid	int	
        contactid	int	Contact ID will be present if the login was performed by a contact/sub-account
    */

        file_put_contents('c:/dbs/contacttesting.txt', print_r($vars, true), FILE_APPEND);
    });