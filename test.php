<?php

/*  
 *  Test hook to see wtf is going on.
 * --JC Ryan 27 Nov 2018
 * 
 */

add_hook('ClientAreaFooterOutput', 1, function($vars){
    $output_debug_log = true;
    $debug = "\r\n---START---\r\n";
    $debug .= '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']."\r\n";
    $debug .= var_export($_SESSION, true);
    $debug .= "\r\n";
    if ($output_debug_log){
        file_put_contents('c:/dbs/ClientAreaFooterOutput.txt', print_r($debug, true), FILE_APPEND);
    }
});

add_hook('AdminAreaFooterOutput', 1, function($vars){
    $output_debug_log = true;
    $debug = "\r\n---START---\r\n";
    $debug .= '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']."\r\n";
    $debug .= var_export($_SESSION, true);
    $debug .= "\r\n";
    if ($output_debug_log){
        file_put_contents('c:/dbs/AdminAreaFooterOutput.txt', print_r($debug, true), FILE_APPEND);
    }
});