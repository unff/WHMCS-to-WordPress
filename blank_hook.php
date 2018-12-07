<?php
/**
*   HOOK TEMPLATE
*   --JC Ryan 26 Nov 2018
*/


add_hook('ClientAdd', 1, function($vars){

    $wp_base_path = "c:/inetpub/wwwroot/sbsite/wp";
    include_once($wp_base_path . "/wp-load.php");
    $debug = '\r\n---START---\r\n';
    $output_debug_log = true;
    // helper functions
  
    $debug .= "Inside hook\r\n";

    
    
      if ($output_debug_log){
        file_put_contents('c:/dbs/debug.txt', print_r($debug, true), FILE_APPEND);
      }

    
});