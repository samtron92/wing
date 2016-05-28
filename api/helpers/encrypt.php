<?php

$string = $argv[1];
$pass = 'wingStore=>sb.iiita15@gmail.com';
$method = 'blowfish';
echo "\nAuth ID for user " . $string . " is => " . openssl_encrypt($string, $method, $pass) . "\n";

?>
