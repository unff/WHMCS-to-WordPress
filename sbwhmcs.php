<?php
/*
Plugin Name: sbwhmcs
Plugin URI: http://www.sixbitsoftware.com
Description: Wordpress hook to make sure that if the user is logged in to WP they are logged into WHMCS as well
Author: JC Ryan
Version: 0.1
Author URI: https://jcryan.com
*/
/*
 * Changeup.  Use the WP cookie to maintain a logged-in state.
 * See https://docs.whmcs.com/AutoAuth
 * Site user lands here.  If they are logged in, they are logged
 * into WHMCS and then redirected to the client area.  If they were previously logged in 
 * by another site click, do nothing.
 * 
 * flow: 
 * visitor hits site
 * logged into WP?
 * y:user has whmcs uid?  y:do nothing. n:autoauth into whmcs/redirect into client area
 * n:load page
 * need a login menu that would show up for only logged off user - done
 * 
 * None of this works for admins. Nothing works period.
 * 
 */

function check_login(){
    include('../clients/scripts/get_whmcs_data.php');
    $debug = '';
	
	if($login_session):
		$debug .=  'Loggedin: ' .$first_name.' '.$last_name. ', '.$company_name. ', '.$email_address.', '.$client_address1.', '.$client_city.', '.$client_phonenumber.', '.$client_country;
	else:
		$debug .=  'Not Loggedin';
	endif;
	
	
	
	$debug .=  '<br /><br />';
	$debug .=  'Items in Cart: '.$cart_items.', '.$cart_total;
	
	$debug .=  '<br /><br />';


	$debug .=  '.com: ' .$domain_price_com;
	$debug .=  '<br />';
	$debug .=  '.net: ' .$domain_price_net;
	$debug .=  '<br />';
	$debug .=  '.org: ' .$domain_price_org;
	$debug .=  '<br />';
	$debug .=  '.biz: ' .$domain_price_biz;
	$debug .=  '<br />';
	$debug .=  '.info: ' .$domain_price_info;
	$debug .=  '<br />';
	$debug .=  '.eu: ' .$domain_price_eu;
	$debug .=  '<br />';
	$debug .=  '.host: ' .$domain_price_host;
    $debug .=  '<br />';

        file_put_contents('c:/dbs/anothertest.txt', print_r($debug, true), FILE_APPEND);

}

add_action('init', 'check_login');