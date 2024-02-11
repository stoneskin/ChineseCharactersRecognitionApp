<?php
require_once 'htmlpurifier-4.15.0-lite/library/HTMLPurifier.auto.php';
function sanitizeHTML($html){
    $purifier = new HTMLPurifier();
    return $purifier->purify($html);
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>