<?php
// functions to assist in testing.

function site_is_prod() {
   $prod = true; 
   // dev or staging
   if (DIR_WS_CATALOG == "/test/" || DIR_WS_CATALOG == "/cheapest/") {
      $prod = false;
   }
   // local testing
   if (strpos(HTTP_SERVER, 'localhost') !== false) { 
      $prod = false;
   }
   return $prod; 
}
