<?php
require_once 'htmlpurifier-4.15.0-lite/library/HTMLPurifier.auto.php';
function sanitizeHTML($html){
    $purifier = new HTMLPurifier();
    return $purifier->purify($html);
}
?>