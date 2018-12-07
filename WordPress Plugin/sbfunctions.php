<?php
/*
Plugin Name: SixBit Functions
Plugin URI: http://www.sixbitsoftware.com
Description: Wordpress functions needed for SixBit site.
Author: JC Ryan
Version: 0.1
Author URI: https://jcryan.com
*/

add_action('after_setup_theme', 'remove_admin_bar'); // <------- THIS ONE.  SWAP SESSIONS HERE.
add_action('auth_cookie_valid', 'dump_page_auth_cookie_valid');
add_action('init', 'dump_page_data_init');
add_action("user_register", "set_user_admin_bar_false_by_default", 10, 1);

function set_user_admin_bar_false_by_default($user_id) {
    update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
    update_user_meta( $user_id, 'show_admin_bar_admin', 'false' );
}
//add_action('set_current_user', 'dump_page_set_current_user');
 
function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
    // Set session data if WHMCS session exists.
    foreach ($_COOKIE as $k=>$v){
         if ($k == 'WHMCSXUEYYIFP102Y') {
             session_id($v);
             session_start();
             break;
         }
     }
}

function dump_page_auth_cookie_valid(){
    //dump_page_data("auth_cookie_valid");
}
function dump_page_data_init(){
        $output_debug_log = true;
        $debug = "\r\n---START---\r\n";
        $debug .= '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']."\r\n";
    // Check if user is logged in through WP
    $wordpress_state = is_user_logged_in();
    $whmcs_state = ($_SESSION['uid']?true:false);
    // contact account works fine with AutoAuth. No special handling needed.
    if ($wordpress_state && !$whmcs_state){
        $user = wp_get_current_user();
            $debug .= var_export($user, true);
            $debug .= "\r\n--END USER DUMP--\r\n";
        if (!$user->exists()){return false;} // safety dance
        $whmcsurl = "https://www.sixbitsoftware.com/clients/dologin.php";
        $autoauthkey = "dfxy74UV";
        $timestamp = time();
        $email = $user->user_email;
        $goto = "clientarea.php"; // needs work to grab landing page they were trying to get to.
        $hash = sha1($email.$timestamp.$autoauthkey);
        $url = $whmcsurl."?email=$email&timestamp=$timestamp&hash=$hash&goto=".urlencode($goto);
        wp_redirect($url);
            $debug .= "redirected to $url\r\n";
    } else {
            $debug .= "Wordpress State: ".(int)$wordpress_state.", WHMCS State: ".(int)$whmcs_state."\r\n";
    }
    if ($output_debug_log){
        file_put_contents('c:/dbs/sbfunctionsInitDebug.txt', print_r($debug, true), FILE_APPEND);
    }
}
function dump_page_set_current_user(){
    //dump_page_data("set_current_user");
}

function dump_page_data($sbf){
    $debug = "\r\n\r\n<br>\r\n" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']."\r\n";
    $debug .= "<br>\r\n---START ".$sbf." ---  ".session_id()." ---<br>\r\n";
    $debug .= var_export($_SESSION, true);
    $debug .= "<br>\r\nEND SESSION DUMP: <br>\r\n";
    $debug .= var_export($_COOKIE, true);
    $debug .= "<br>\r\nEND COOKIE DUMP<br>\r\n";
    file_put_contents("c:/dbs/sbfunctionsdebug.txt", print_r($debug, true), FILE_APPEND);
}