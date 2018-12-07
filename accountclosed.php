<?php

/*  
 *  WHMCS hook for closing an account -> WP
 *  -- JC Ryan 26 Nov 2018
 * 
 */

add_hook('ClientClose', 1, function($vars){
    // We don't really use this function.  We never close an account,
    // just terminate the products and allow them to re-sign in at a later date
});