<?php

/*  
 *  WHMCS hook to handle account merge
 *  -- JC Ryan 26 Nov 2018
 * 
 * 
 */

add_hook('AfterClientMerge', 1, function($vars){
    // There's really not a lot we can do here.  The hook is run *after* the merge for some stupid reason.
    // You can't look up the from account, just the to account.
    // Doesn't matter as rolling sync should clean up when it next rolls around.
});